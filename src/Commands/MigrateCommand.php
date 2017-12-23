<?php

namespace Bluora\LaravelDatasets\Commands;

use Bluora\LaravelDatasets\Traits\CommandTrait;
use DB;
use Illuminate\Console\Command;

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
        // Confirm connection and migration.
        $connection = $this->confirmConnectionAndMigration();

        // Load the migration class.
        $migration_class = $this->getMigrationClass();

        // Process the migration.
        $this->processMigration($connection, $migration_class);

        // Save this migration to the database/migrations folder.
        $this->createMigrationFile($connection, $migration_class);
    }

    /**
     * Confirm migration can occur by checking database.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    private function confirmConnectionAndMigration()
    {
        // Verify which connection to use.
        $connection = $this->verifyConnection();

        // Check if the data table exists against this connection.
        if ($this->checkTableExists($this->argument('dataset'), true, $connection)) {
            $this->error(sprintf('\'%s\' table already exists.', 'data_'.$this->argument('dataset')));
            $this->line('');

            exit(1);
        }

        return $connection;
    }

    /**
     * Get the migration class.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    private function getMigrationClass()
    {
        // Load the configuration for this dataset.
        $config = $this->loadConfig($this->argument('dataset'));

        // Calculate some reference variables.
        $namespace = $config['namespace'];
        $class_name = sprintf('CreateData%sTable', studly_case($this->argument('dataset')));
        $class = sprintf('%s\\%s', $namespace, $class_name);

        // Supplied dataset config file does not exist.
        if (!class_exists($class)) {
            $this->error(sprintf('\'%s\' does not exist.', $class));
            $this->line('');
            $this->line('');

            exit(1);
        }

        return $class;
    }

    /**
     * Process the migration.
     *
     * @param string $connection
     * @param string $class
     *
     * @return void
     */
    private function processMigration($connection, $class)
    {
        // Verbose.
        $this->info('Migrating...');
        $this->line('');

        // Migrate the database.
        $migration = new $class($connection);
        $migration->up($connection);
    }

    /**
     * Create the migration file.
     *
     * @param string $connection
     * @param string $class
     *
     * @return void
     */
    private function createMigrationFile($connection, $class)
    {
        // Interate the database file.
        $next_interation = $this->getNextInteration();

        // Create an extension of this migration script in the database/migrations folder.
        $alias_file_name = sprintf('%s_%s_create_%s_table', date('Y_m_d'), str_pad($next_interation, 3, '0', STR_PAD_LEFT), $this->argument('dataset'));
        $alias_file = sprintf('%s/%s.php', base_path('database/migrations'), $alias_file_name);
        $alias_class = sprintf('Create%sTable', studly_case($this->argument('dataset')));

        $class_name = preg_replace('/.*\\\\/', '', $class);

        // Generate contents for database file.
        $contents = sprintf("<?php\n\nuse %s;\n\nclass %s extends %s\n{\n\tprotected $connection = '%s';\n\n}\n", $class, $alias_class, $class_name, $connection);

        // Add the migration to the tracking table.
        file_put_contents($alias_file, $contents);

        // Update the migrations table.
        DB::connection(config('database.default'))->unprepared(sprintf("INSERT INTO migrations SET migration='%s',batch=(SELECT max(batch)+1 FROM (SELECT batch FROM migrations) AS source_batch)", $alias_file_name));
    }
}
