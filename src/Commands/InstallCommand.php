<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:install {dataset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the specified dataset. This will create the table in the database and do an initial sync of the data.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->splash(sprintf("Installing '%s'.", $this->argument('dataset')));

        $result = DB::select(DB::raw('SHOW TABLES LIKE \'data_'.$this->argument('dataset').'\''));
        $exit_code = 0;

        if (count($result) == 0) {
            $exit_code = $this->call('datasets:migrate', [
                'dataset'     => $this->argument('dataset'),
                '--no-splash' => 1,
            ]);
        } elseif (count($result) != 0) {
            $this->info(sprintf("Dataset '%s' is already setup. Syncronizing data only.", $this->argument('dataset')));
            $this->line('');
        }

        if ($exit_code) {
            return 1;
        }

        $this->call('datasets:sync', [
            'dataset'     => $this->argument('dataset'),
            '--no-splash' => 1,
        ]);
    }
}
