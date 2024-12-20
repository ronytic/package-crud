<?php

namespace ProcessMaker\Package\PackageCrud\Console\Commands;

use Illuminate\Console\Command;

class Uninstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package-crud:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall Package Crud Package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Package Crud package Uninstalled');
        //remove migrations
        $this->uninstallMigrations(__DIR__ . '/../../../database/migrations');
    }

    /**
     * Install migrations
     *
     * @param array $pluginMigrationsPaths
     * @return void
     */
    private function uninstallMigrations(string $pluginMigrationsPaths)
    {
        app('migrator')->rollback($pluginMigrationsPaths);
    }
}
