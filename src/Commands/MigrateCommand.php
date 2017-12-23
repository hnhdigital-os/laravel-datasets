<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use Illuminate\Console\Command;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;

class MigrateCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:migrate {dataset} {--no-splash=0}';

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
        $this->splash(sprintf(" Migrating database for '%s'.", $this->argument('dataset')), $this->option('no-splash'));

        $this->runMigration();

        $this->info(' Completed migration.');
        $this->line('');

        $this->line('');
        $this->line(sprintf(" You can now run 'php artisan datasets:sync %s' to populate this database table.", $this->argument('dataset')));
        $this->line('');
    }

    /**
     * Load and run migration.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    private function runMigration()
    {
        // Verify which connection to use.
        $connection = $this->verifyConnection();

        // Check if the data table exists against this connection.
        if ($this->checkTableExists($this->argument('dataset'), true, $connection)) {
            $this->error(sprintf('\'%s\' table already exists.', 'data_'.$this->argument('dataset')));
            $this->line('');

            exit(1);
        }

        // Load the configuration for this dataset.
        $dataset_config = $this->loadConfig($this->argument('dataset'));

        // Calculate some reference variables.
        $migration_namespace = $dataset_config['namespace'];
        $migration_class_name = sprintf('CreateData%sTable', studly_case($this->argument('dataset')));
        $migration_class = sprintf('%s\\%s', $migration_namespace, $migration_class_name);

        // Supplied dataset config file does not exist.
        if (!class_exists($migration_class)) {
            $this->error(sprintf('\'%s\' does not exist.', $migration_class));
            $this->line('');
            $this->line('');

            exit(1);
        }

        $this->info('Migrating...');
        $this->line('');

        // Migrate the database.
        $migration = new $migration_class();
        $migration->up($connection);

        // Interate the database file.
        $next_interation = $this->getNextInteration();

        // Create an extension of this migration script in the database/migrations folder.
        $migration_alias_file_name = sprintf('%s_%s_create_%s_table', date('Y_m_d'), str_pad($next_interation, 3, '0', STR_PAD_LEFT), $this->argument('dataset'));
        $migration_alias_file = sprintf('%s/%s.php', base_path('database/migrations'), $migration_alias_file_name);
        $migration_alias_class = sprintf('Create%sTable', studly_case($this->argument('dataset')));

        // Generate contents for database file.
        $contents = sprintf("<?php\n\nuse %s;\n\nclass %s extends %s\n{\n\tprotected $connection = '%s';\n\n}\n", $migration_class, $migration_alias_class, $migration_class_name, $connection);

        // Add the migration to the tracking table.
        file_put_contents($migration_alias_file, $contents);

        // Update the migrations table.
        DB::connection(config('database.default'))->unprepared(sprintf("INSERT INTO migrations SET migration='%s',batch=(SELECT max(batch)+1 FROM (SELECT batch FROM migrations) AS source_batch)", $migration_alias_file_name));
    }
}
