<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Models\ImportModel;
use DB;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use League\Csv\Reader;

class MigrateCommand extends Command
{
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

        $this->line('');
        $this->line("       _                          _   ___       _                _      ");
        $this->line("      | |   __ _ _ _ __ ___ _____| | |   \ __ _| |_ __ _ ___ ___| |_ ___");
        $this->line("      | |__/ _` | '_/ _` \ V / -_) | | |) / _` |  _/ _` (_-</ -_)  _(_-<");
        $this->line("      |____\__,_|_| \__,_|\_/\___|_| |___/\__,_|\__\__,_/__/\___|\__/__/");
        $this->line('');
        $this->line("                                                          By H&H|Digital");
        $this->line('');
        $this->line("Migrating database for '".$this->argument('dataset')."'.");
        $this->line('');

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
