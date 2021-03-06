<?php

namespace HnhDigital\Datasets\Commands;

use DB;
use HnhDigital\Datasets\Traits\CommandTrait;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasets:install {dataset?}';

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
        if (empty($this->argument('dataset'))) {
            $this->call('datasets:list');

            return 1;
        }

        $this->splash(sprintf("Installing '%s'.", $this->argument('dataset')));

        // Verify which connection to use.
        $connection = $this->verifyConnection();

        $this->exportConfig($connection);

        $result = DB::connection($connection)->select(DB::raw('SHOW TABLES LIKE \'data_'.$this->argument('dataset').'\''));
        $exit_code = 0;

        if (count($result) == 0) {
            $exit_code = $this->call('datasets:migrate', [
                'dataset'     => $this->argument('dataset'),
                '--no-splash' => 1,
            ]);
        } elseif (count($result) != 0) {
            $this->info(sprintf("Dataset '%s.%s' is already setup. Syncronizing data only.", $connection, $this->argument('dataset')));
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

    /**
     * Export config back to the config file.
     *
     * @return void
     */
    private function exportConfig($connection)
    {
        if (!config('datasets.'.$this->argument('dataset').'.connection') || config('datasets.'.$this->argument('dataset').'.connection') !== $connection) {
            config(['datasets.'.$this->argument('dataset').'.connection' => $connection]);
            $config_contents = var_export(config('datasets'), true);
            $config_contents = str_replace(['array (', '),'], ['[', '],'], $config_contents);
            $config_contents = "<?php\n\nreturn ".$config_contents."];\n";
            $config_contents = str_replace(')];', '];', $config_contents);
            $config_contents = preg_replace("/^([\s]*)([0-9]+) => (.*?)$/m", '$1$3', $config_contents);
            $config_contents = preg_replace("/=>([\s]*)\[/m", '=> [', $config_contents);
            $config_contents = preg_replace("/(^|\G) {2}/m", '    $1', $config_contents);
            file_put_contents(config_path('datasets.php'), $config_contents);
        }
    }
}
