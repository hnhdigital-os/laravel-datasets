<?php

namespace Bluora\LaravelDatasets;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use League\Csv\Reader;

class SyncDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:sync {dataset}';

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
        $this->loadConfig();
        $this->checkConfig();

        $result = $this->readData($this->downloadPath($this->getPath()));

        print_r($result);
    }

    /**
     * Load config file.
     *
     * @return void
     */
    private function loadConfig()
    {
        $config_file = __DIR__.'/Datasets/'.$this->argument('dataset').'.php';

        // Supplied dataset config file does not exist.
        if (!file_exists($config_file)) {
            $this->error(sprintf('\'%s\' does not exist.', $this->argument('dataset')));

            exit(1);
        }

        $this->config = include_once $config_file;
    }

    /**
     * Check config file.
     *
     * @return void
     */
    private function checkConfig()
    {
        //  Certain keys are required for the config to be valid.
        if (!array_has($this->config, 'path') || !array_has($this->config, 'table') || !array_has($this->config, 'mapping')) {
            $this->error('Missing a required key - path, table, or mapping.');

            exit(1);
        }
    }

    /**
     * Get path from string or via a closure.
     *
     * @return string
     */
    private function getPath()
    {
        if (($path = $this->config['path']) instanceof \Closure) {
            if (is_numeric($path = $path($this))) {

                exit($path);
            }
        }

        if (!is_string($path)) {
            $this->error('Path has not been provided as a string.');

            exit(1);
        }

        return $path;
    }

    /**
     * Download the file from the path.
     *
     * @param  string $path
     *
     * @return string
     */
    private function downloadPath($path)
    {
        $this->line(sprintf('Downloading \'%s\'...', $path));

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

        $this->error('Failed to download');

        exit(1);
    }

    /**
     * Read the data and do our mapping.
     *
     * @param  string $data
     *
     * @return array
     */
    private function readData($data)
    {
        $reader = Reader::createFromString($data)
            ->fetchAssoc(0);

        // Apply any filters.
        if (array_has($this->config, 'filter')) {
            $reader = $this->config['filter']($reader);
        }

        $result = [];

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
        }

        return $result;
    }
}
