<?php

namespace HnhDigital\Datasets\Commands;

use HnhDigital\Datasets\Traits\CommandTrait;
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
     */
    public function handle()
    {
        $this->splash('Listed below are the datasets available to this package:');

        $source_packages = config('datasets.source', []);

        foreach ($source_packages as $folder) {

            // Get folder contents of datasets
            $datasets = new Filesystem(new Adapter(base_path('vendor/'.$folder.'/datasets/')));

            try {
                $files = $datasets->listContents();
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());

                exit(1);
            }

            $this->line($folder);
            $this->line('');

            // Iterate over each one
            foreach ($files as $file) {
                $this->checkTableExists($file['filename'], true)
                    ? $this->info('* '.$file['filename'].' (installed)')
                    : $this->line('* '.$file['filename']);
            }

            $this->line('');
        }

        $this->line("You can run 'php artisan datasets:install [dataset]' to install the specified dataset.");
        $this->line('');
    }
}
