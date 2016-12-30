<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use Illuminate\Console\Command;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;

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
     *
     * @SupressWarnings(PHPMD.ExitExpression)
     * @SupressWarnings(PHPMD.ElseExpression)
     */
    public function handle()
    {
        $this->splash('Listing available datasets');

        // Get folder contents of datasets
        $datasets = new Filesystem(new Adapter(__DIR__.'/../Datasets'));

        try {
            $files = $datasets->listContents();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            exit(1);
        }

        // Iterate over each one
        foreach ($files as $file) {
            $result = DB::select(DB::raw('SHOW TABLES LIKE \'data_'.$file['filename'].'\''));
            if (count($result)) {
                $this->info('* '.$file['filename'].' (installed)');
            } else {
                $this->line('* '.$file['filename']);
            }
        }

        $this->line('');
        $this->line("You can run 'php artisan datasets:migrate [dataset]' to install the required database table.");
        $this->line('');
    }
}
