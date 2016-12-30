<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use League\Csv\Reader;

class MigrateCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:migrate {dataset}';

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
        $this->splash(sprintf("Migrating database for '%s'", $this->argument('dataset')));

        $this->runMigration();

        $this->info('Completed migration.');
        $this->line('');
    }

    /**
     * Load and run migration.
     *
     * @return void
     */
    private function runMigration()
    {
        $result = DB::select(DB::raw('SHOW TABLES LIKE \'data_'.$this->argument('dataset').'\''));

        if (count($result)) {
            $this->error(sprintf('\'%s\' table already exists.', 'data_'.$this->argument('dataset')));
            $this->line('');

            exit(1);
        }

        $migration_class = 'Bluora\\LaravelDatasets\\Migrations\\CreateData'.studly_case($this->argument('dataset')).'Table';

        // Supplied dataset config file does not exist.
        if (!class_exists($migration_class)) {
            $this->error(sprintf('\'%s\' does not exist.', $this->argument('dataset')));
            $this->line('');
            $this->line('');

            exit(1);
        }

        $this->info('Migrating...');
        $this->line('');

        $migration = new $migration_class();
        $migration->up();

    }
}
