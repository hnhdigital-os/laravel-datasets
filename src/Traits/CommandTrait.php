<?php

namespace Bluora\LaravelDatasets\Traits;

use DB;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;

trait CommandTrait
{
    /**
     * Display the splash.
     *
     * @param  string  $text
     * @param  boolean $hide_splash
     * 
     * @return void
     * 
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function splash($text, $hide_splash = false)
    {
        if (!$hide_splash) {
            $this->line('');
            $this->line('    ___        _                     _         _  _      __                                 _ ');
            $this->line('   /   \ __ _ | |_  __ _  ___   ___ | |_  ___ | || |    / /   __ _  _ __  __ _ __   __ ___ | |');
            $this->line("  / /\ // _` || __|/ _` |/ __| / _ \| __|/ __|| || |_  / /   / _` || '__|/ _` |\ \ / // _ \| |");
            $this->line(' / /_//| (_| || |_| (_| |\__ \|  __/| |_ \__ \|__   _|/ /___| (_| || |  | (_| | \ V /|  __/| |');
            $this->line("/___,'  \__,_| \__|\__,_||___/ \___| \__||___/   |_|  \____/ \__,_||_|   \__,_|  \_/  \___||_|");
            $this->line('');
            $this->line('                                                                                By H&H|Digital');
            $this->line('');
            $this->line($text);
            $this->line('');
        }
    }

    /**
     * Get list of dataset's paths, or a specific dataset's path.
     *
     * @param  boolean $dataset
     *
     * @return array|string
     * 
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    protected function getDatasets($dataset = false)
    {
        $source_packages = config('datasets.source', []);

        $result = [];

        foreach ($source_packages as $folder) {

            // Get folder contents of datasets
            $datasets = new Filesystem(new Adapter(base_path('vendor/'.$folder.'/datasets/')));

            try {
                $files = $datasets->listContents();
            } catch (\Exception $exception) {
            }

            foreach ($files as $file) {
                $result[$file['filename']] = base_path('vendor/'.$folder.'/datasets/'.$file['basename']);
            }
        }

        if ($dataset !== false) {
            if (!isset($result[$dataset])) {
                $this->error(sprintf("Requested dataset '%s' could not be found.", $dataset));

                exit(1);
            }

            // Supplied dataset config file does not exist.
            if (!file_exists($result[$dataset])) {
                $this->error(sprintf('\'%s\' dataset does not exist.', $this->argument('dataset')));
                $this->line('');

                exit(1);
            }

            return $result[$dataset];
        }

        return $result;
    }

    /**
     * Load config file.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    protected function loadConfig($dataset)
    {
        $config_file = $this->getDatasets($dataset);
        $config = include $config_file;
        $this->checkConfig($config);
        return $config;
    }

    /**
     * Check config file.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    protected function checkConfig($config)
    {
        $required_fields = ['namespace', 'table', 'path', 'mapping', 'import_keys'];

        foreach ($required_fields as $key) {
            if (!array_has($config, $key)) {
                $this->error(sprintf('Missing \'%s\' from the dataset configuration file.', $key));
                $this->line('');
                $this->line('');

                exit(1);
            }
        }
    }

    /**
     * Check if the table exists.
     *
     * @param string  $dataset
     * @param boolean $no_exit
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    protected function checkTableExists($dataset, $no_exit = false)
    {
        $result = DB::select(DB::raw('SHOW TABLES LIKE \'data_'.$dataset.'\''));

        if (count($result) == 0) {
            if ($no_exit) {
                return false;
            }

            $this->error(sprintf('\'%s\' table does not exist. Please migrate it first.', 'data_'.$dataset));
            $this->line('');
            exit(1);
        }

        return true;
    }
}
