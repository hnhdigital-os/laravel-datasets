<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ListDatasetsCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists the available datasets provided by this package.';

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
        $this->splash('Listing available datasets');

    }
}
