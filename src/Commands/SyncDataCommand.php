<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Models\ImportModel;
use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use League\Csv\Reader;

class SyncDataCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:sync {dataset} {--source-folder=}';

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
        $this->splash(sprintf("Processing specified dataset '%s'", $this->argument('dataset')));

        $this->loadConfig();

        $this->info('Configuration passed.');
        $this->line('');

        $this->importData($this->readData($this->downloadPath($this->getPath())));

        $this->info('Completed import.');
        $this->line('');
    }

    /**
     * Load config file.
     *
     * @return void
     *
     * @SupressWarnings(PHPMD.ExitExpression)
     */
    private function loadConfig()
    {
        $datasets_source = __DIR__.'/../Datasets';

        if (!empty($this->option('source-folder'))) {
            $datasets_source = $this->option('source-folder');

            if (!file_exists($datasets_source)) {
                $this->error(sprintf('\'%s\' does not exist.', $option->option('source-folder')));
                $this->line('');

                exit(1);
            }
        }

        $config_file = $datasets_source.'/'.$this->argument('dataset').'.php';

        // Supplied dataset config file does not exist.
        if (!file_exists($config_file)) {
            $this->error(sprintf('\'%s\' does not exist.', $this->argument('dataset')));
            $this->line('');

            exit(1);
        }

        $this->checkConfig(include_once $config_file);
    }

    /**
     * Check config file.
     *
     * @return void
     *
     * @SupressWarnings(PHPMD.ExitExpression)
     */
    private function checkConfig($config)
    {
        $required_fields = ['table', 'path', 'mapping', 'import_keys'];

        foreach ($required_fields as $key) {
            if (!array_has($config, $key)) {
                $this->error(sprintf('Missing \'%s\' from the dataset configuration file.', $key));
                $this->line('');
                $this->line('');

                exit(1);
            }
        }

        $result = DB::select(DB::raw('SHOW TABLES LIKE \'data_'.$this->argument('dataset').'\''));

        if (count($result) == 0) {
            $this->error(sprintf('\'%s\' table does not exist. Please migrate it first.', 'data_'.$this->argument('dataset')));
            $this->line('');

            exit(1);
        }

        $this->config = $config;
    }

    /**
     * Get path from string or via a closure.
     *
     * @return string
     *
     * @SupressWarnings(PHPMD.ExitExpression)
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
     * @SupressWarnings(PHPMD.ExitExpression)
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
            $reader = $reader->fetchAssoc(0);
        }

        $result = [];
        $this->progress_bar = $this->output->createProgressBar(1);

        foreach ($reader as $index => $row) {
            $new_row = [];

            // Translate incoming data via mapping array.
            foreach ($row as $key => $value) {
                if (array_has($this->config['mapping'], $key)) {
                    $new_row[array_get($this->config['mapping'], $key)] = $value;
                }
            }

            // Check modify for any specific key manipulations.
            if (array_has($this->config, 'modify')) {
                foreach ($new_row as $key => &$value) {
                    if (array_has($this->config['modify'], $key)) {
                        $this->config['modify'][$key]($value, $new_row);
                    }
                }
            }

            if (count($new_row)) {
                $result[] = $new_row;
            }
            $this->progress_bar->advance();
        }

        $this->line('     ');
        $this->line('     ');

        return $result;
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
            $model_lookup = new ImportModel();
            $model_lookup->setTable(sprintf('data_%s', array_get($this->config, 'table')));

            foreach (array_get($this->config, 'import_keys', []) as $key) {
                $model_lookup = $model_lookup->where($key, array_get($row, $key, null));
            }

            $model = $model_lookup->first();
            $new_model = false;

            if (is_null($model)) {
                $model = new ImportModel();
                $new_model = true;
            }

            $model->setTable(sprintf('data_%s', array_get($this->config, 'table')));

            $count = 0;

            foreach ($row as $key => $value) {
                if ($new_model || $model->$key !== $value) {
                    $model->$key = $value;
                    $count++;
                }
            }

            $model->save();
            $this->progress_bar->advance();
        }

        $this->line('     ');
        $this->line('     ');
    }
}
