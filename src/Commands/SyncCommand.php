<?php

namespace HnhDigital\Datasets\Commands;

use HnhDigital\Datasets\Models\ImportModel;
use HnhDigital\Datasets\Traits\CommandTrait;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use League\Csv\Reader;

class SyncCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:sync {dataset} {--source-folder=} {--no-splash=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronizes data using configuration and closures';

    /**
     * The loaded configuration file.
     *
     * @var array
     */
    protected $config;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->splash(sprintf("Processing specified dataset '%s':", $this->argument('dataset')), $this->option('no-splash'));

        $this->config = $this->loadConfig($this->argument('dataset'));
        $this->checkTableExists($this->argument('dataset'));

        $this->line('Dataset configuration requirements were met.');
        $this->line('');

        $this->importData($this->readData($this->downloadPath($this->getPath())));

        $this->info('Completed import.');
        $this->line('');
    }

    /**
     * Get path from string or via a closure.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    private function getPath()
    {
        if (($path = $this->config['path']) instanceof \Closure) {
            $this->line('Generating dynamic download path.');
            $this->line('');
            if (is_numeric($path = $path($this))) {
                exit($path);
            }
            $this->line('');
        }

        if (!is_string($path)) {
            $this->error('Path has not been provided as a string.');
            $this->line('');
            $this->line('');

            exit(1);
        }

        return $path;
    }

    /**
     * Download the file from the path.
     *
     * @param string $path
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    private function downloadPath($path)
    {
        $this->info(sprintf('Downloading \'%s\'...', $path));
        $this->line('');

        // Get the file from FTP path
        if (stripos($path, 'ftp://') !== false) {
            return file_get_contents($path);
        }

        $client = new GuzzleClient();
        $res = $client->request('GET', $path);
        $code = $res->getStatusCode();

        // Download has occurred
        if ($code == 200) {
            return $res->getBody();
        }

        $this->error('Failed to download.');
        $this->line('');
        $this->line('');

        exit(1);
    }

    /**
     * Read the data and do our mapping.
     *
     * @param string $data
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function readData($data)
    {
        $this->line('Processing...');
        $this->line('');

        $reader = Reader::createFromString($data);

        // Apply any filters.
        if (array_has($this->config, 'filter')) {
            $reader = $this->config['filter']($reader);
        }

        if (!array_has($this->config, 'no_header', false)) {
            $reader->setHeaderOffset(0);
        }

        $result = [];
        $this->progress_bar = $this->output->createProgressBar(1);

        foreach ($reader as $row) {
            if (count($row = $this->processRow($row))) {
                $result[] = $row;
            }
            $this->progress_bar->advance();
        }

        // Apply any filters.
        if (array_has($this->config, 'change_read_data')) {
            $result = $this->config['change_read_data']($result);
        }

        $this->line('     ');
        $this->line('     ');

        return $result;
    }

    /**
     * Process the row in the data that is being interated.
     *
     * @param array $row
     *
     * @return void
     */
    private function processRow($row)
    {
        $this->translateRow($row);
        $this->transformRow($row);

        return $row;
    }

    /**
     * Translate the row using the configured mapping rules.
     *
     * @param array &$row
     *
     * @return void
     */
    private function translateRow(&$row)
    {
        $new_row = [];

        // Translate incoming data via mapping array.
        foreach ($row as $key => $value) {
            if (array_has($this->config['mapping'], $key)) {
                $new_row[array_get($this->config['mapping'], $key)] = $value;
            }
        }

        // Replace with translated row.
        $row = $new_row;
    }

    /**
     * Transform the row using the configured modification rules.
     *
     * @param array &$row
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CognitiveComplexity)
     */
    private function transformRow(&$row)
    {
        // Check modify for any specific key manipulations.
        if (array_has($this->config, 'modify')) {
            foreach ($row as $key => &$value) {
                if (array_has($this->config['modify'], $key)) {
                    $this->config['modify'][$key]($value, $row);
                }
                unset($value);
            }
        }
    }

    /**
     * Import data.
     *
     * @param array $data
     *
     * @return void
     */
    private function importData($data)
    {
        $this->line(sprintf('Importing %s records...', count($data)));
        $this->line('');

        $this->progress_bar = $this->output->createProgressBar(count($data));

        foreach ($data as $row) {
            $this->processImportRow($row);
        }

        $this->line('     ');
        $this->line('     ');
    }

    /**
     * Process the importing of the row.
     *
     * @param array &$row
     *
     * @return void
     */
    private function processImportRow(&$row)
    {
        // Get the model to represent this row.
        $model = $this->lookupModel($row);

        // Assign values to the model.
        foreach ($row as $key => $value) {
            if (empty($model->getKey()) || $model->$key !== $value) {
                $model->$key = $value;
            }
        }

        $model->save();

        $this->progress_bar->advance();
    }

    /**
     * Lookup the new or existing model for this row.
     *
     * @return ImportModel
     */
    private function lookupModel(&$row)
    {
        $query = new ImportModel();

        // Set the connection and table.
        $query->setConnection($this->connection($this->argument('dataset')));
        $query->setTable(sprintf('data_%s', array_get($this->config, 'table')));

        foreach (array_get($this->config, 'import_keys', []) as $key) {
            $query->where($key, array_get($row, $key, null));
        }

        $model = $query->first();
        $new_model = false;

        if (is_null($model)) {
            $model = new ImportModel();
            $new_model = true;
        }

        // Ensure connection and table are set.
        $model->setConnection($this->connection($this->argument('dataset')));
        $model->setTable(sprintf('data_%s', array_get($this->config, 'table')));

        return $model;
    }
}
