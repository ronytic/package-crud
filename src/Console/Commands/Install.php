<?php

namespace ProcessMaker\Package\PackageCrud\Console\Commands;

use Artisan;
use ProcessMaker\Console\PackageInstallCommand;

class Install extends PackageInstallCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package-crud:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Package Crud Package';

    /**
     * Publish assets
     * @return void
     */
    public function publishAssets()
    {
        $this->info('Publishing assets');
        Artisan::call('vendor:publish', [
            '--tag' => 'package-crud',
            '--force' => true,
        ]);
    }

    /**
     * Add database tables
     * @return void
     */
    public function addDatabaseTables()
    {
        $this->info('Adding database tables');
        $this->installMigrations(__DIR__ . '/../../../database/migrations');
    }

    public function preinstall()
    {
        $this->publishAssets();
    }

    public function install()
    {
        $this->addDatabaseTables();
    }

    public function postinstall() {}

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();
        $this->info('Package Crud has been installed');
    }

    /**
     * Install migrations
     * @param string $pluginMigrationsPaths
     * @return void
     */
    private function installMigrations(string $pluginMigrationsPaths)
    {
        app('migrator')->run($pluginMigrationsPaths);
    }
}
