<?php

namespace Bluora\LaravelDatasets;

use Illuminate\Console\Command;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config_file = __DIR__.'/Datasets/'.$this->argument('dataset').'.php';

        if (!file_exists($config_file)) {

            return 1;
        }

        $config = include_once $config_file;

        print_r($config);
    }
}
