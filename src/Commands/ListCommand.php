<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use Illuminate\Console\Command;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;

class ListCommand extends Command
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
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function handle()
    {
        $this->splash('Listed below are the datasets available to this package:');

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
                $this->info('* '.$file['filename'].' (setup)');
            } else {
                $this->line('* '.$file['filename']);
            }
        }

        $this->line('');
        $this->line("You can run 'php artisan datasets:install [dataset]' to install the specified dataset.");
        $this->line('');
    }
}
