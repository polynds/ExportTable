<?php

declare(strict_types=1);
/**
 * happy coding!!!
 */

namespace Polynds\ExportTable;

use Illuminate\Support\ServiceProvider;

class ETGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/export_table.php' => config_path('export_table.php'),
            ]);
        }
    }

    public function register()
    {
        $this->commands([
            DataTableToFileExporterCommand::class,
        ]);
    }

    public function provides()
    {
        return ['export_table'];
    }
}
