<?php

namespace Bahjaat\Daisycon;

use Bahjaat\Daisycon\Commands\DaisyconFillDatabaseRelations;
use Bahjaat\Daisycon\Commands\DaisyconFixData;
use Bahjaat\Daisycon\Commands\DaisyconGetFeeds;
use Bahjaat\Daisycon\Commands\DaisyconGetPrograms;
use Bahjaat\Daisycon\Commands\DaisyconGetSubscriptions;
use Bahjaat\Daisycon\Commands\DaisyconGetProducts;
use Illuminate\Support\ServiceProvider;

class DaisyconServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/database/migrations',
        ]);

        $this->publishes([
            __DIR__ . '/config/daisycon.php' => config_path('daisycon.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Bahjaat\Daisycon\Repository\DataImportInterface',
            'Bahjaat\Daisycon\Repository\\XmlDataImport'
        );

//        $this->app->register(
//            'Jenssegers\Date\Date'
//        );

        $this->app->make('Bahjaat\Daisycon\Repository\DataImportInterface');

        $this->commands([
            DaisyconGetFeeds::class,
            DaisyconGetPrograms::class,
            DaisyconGetSubscriptions::class,
            DaisyconGetProducts::class,
            DaisyconFillDatabaseRelations::class,
        ]);
    }
}
