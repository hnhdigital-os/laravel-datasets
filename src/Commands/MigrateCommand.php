<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

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

        $migration_namespace = 'Bluora\\LaravelDatasets\\Migrations';
        $migration_class_name = sprintf('CreateData%sTable', studly_case($this->argument('dataset')));
        $migration_class = sprintf('%s\\%s', $migration_namespace, $migration_class_name);

        // Supplied dataset config file does not exist.
        if (!class_exists($migration_class)) {
            $this->error(sprintf('\'%s\' does not exist.', $this->argument('dataset')));
            $this->line('');
            $this->line('');

            exit(1);
        }

        $this->info('Migrating...');
        $this->line('');

        // Migrate the database.
        $migration = new $migration_class();
        $migration->up();

        $next_interation = $this->getNextInteration();

        // Create an extension of this migration script in the database/migrations folder.
        $migration_alias_file_name = sprintf('%s_%s_create_%s_table', date('Y_m_d'), str_pad($next_interation, 6, '0', STR_PAD_LEFT), $this->argument('dataset'));
        $migration_alias_file = sprintf('%s/%s.php', base_path('database/migrations'), $migration_alias_file_name);
        $migration_alias_class = sprintf('Create%sTable', studly_case($this->argument('dataset')));

        $contents = sprintf("<?php\n\nuse %s;\n\nclass %s extends %s\n{\n\n}\n", $migration_class, $migration_alias_class, $migration_class_name);

        // Add the migration to the tracking table.
        file_put_contents($migration_alias_file, $contents);

        DB::unprepared(sprintf("INSERT INTO migrations SET migration='%s',batch=(SELECT max(batch)+1 FROM (SELECT batch FROM migrations) AS source_batch)", $migration_alias_file_name));
    }

    /**
     * Get the next interation.
     *
     * @return integer
     */
    private function getNextInteration()
    {
        // Get the next interator.
        $migrations = new Filesystem(new Adapter(base_path('database/migrations')));

        try {
            $files = $migrations->listContents();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            exit(1);
        }

        $files_filtered = array_filter($files, function($value) {
            return stripos($value['filename'], date('Y_m_d')) !== false;
        });

        if (count($files_filtered) == 0) {
            return 1;
        }

        $files_filtered = array_column($files_filtered, 'path');

        sort($files_filtered);
        $latest_file = array_pop($files_filtered);
        $latest_details = explode('_', $latest_file);

        return (int)[3]+1;
    }
}
