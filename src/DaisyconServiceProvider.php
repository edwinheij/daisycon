<?php

namespace Bahjaat\Daisycon;

use Bahjaat\Daisycon\Commands\DaisyconAll;
use Bahjaat\Daisycon\Commands\DaisyconFillDatabaseRelations;
use Bahjaat\Daisycon\Commands\DaisyconFixData;
use Bahjaat\Daisycon\Commands\DaisyconGetFeeds;
use Bahjaat\Daisycon\Commands\DaisyconGetLeadrequirements;
use Bahjaat\Daisycon\Commands\DaisyconGetPrograms;
use Bahjaat\Daisycon\Commands\DaisyconGetSubscriptions;
use Bahjaat\Daisycon\Commands\DaisyconGetProducts;
use Bahjaat\Daisycon\Commands\DaisyconPostLeads;
use Bahjaat\Daisycon\Models\Productinfo;
use Bahjaat\Daisycon\Repository\JsonDataImport;
use Bahjaat\Daisycon\Repository\LeagueCsvDataImport;
use Bahjaat\Daisycon\Repository\XmlDataImport;
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

//        Productinfo::fillable(DaisyconHelper::getProductinfoFields());

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        switch (strtolower(config('daisycon.feed_type'))) {
            case "csv":
                $dataImportClassname = LeagueCsvDataImport::class;
                break;
            case "json":
                $dataImportClassname = JsonDataImport::class;
                break;
            case "xml":
                $dataImportClassname = XmlDataImport::class;
                break;
            default:
                $dataImportClassname = LeagueCsvDataImport::class;
        }

        $this->app->bind(
            'Bahjaat\Daisycon\Repository\DataImportInterface',
            $dataImportClassname
        );

        $this->commands([
            DaisyconGetFeeds::class,
            DaisyconGetPrograms::class,
            DaisyconGetSubscriptions::class,
            DaisyconGetProducts::class,
            DaisyconFillDatabaseRelations::class,
            DaisyconAll::class,
            DaisyconPostLeads::class,
            DaisyconGetLeadrequirements::class,
        ]);
    }
}
