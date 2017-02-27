<?php

namespace Bahjaat\Daisycon;

use Bahjaat\Daisycon\Commands\DaisyconFixData;
use Bahjaat\Daisycon\Commands\DaisyconGetFeeds;
use Bahjaat\Daisycon\Commands\DaisyconGetPrograms;
use Bahjaat\Daisycon\Commands\DaisyconGetSubscriptions;
use Bahjaat\Daisycon\Commands\DaisyconImportData;
use Config;
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
//        $this->addPublishGroup('config', [
//            __DIR__ . '/config/daisycon.php' => config_path('daisycon.php'),
//        ]);

        $this->loadMigrationsFrom([
            __DIR__ . '/database/migrations',
        ]);

        $this->publishes([
            __DIR__ . '/config/daisycon.php' => config_path('daisycon.php'),
        ], 'config');

//        $this->mergeConfigFrom(
//            __DIR__ . '/config/daisycon.php', 'daisycon'
//        );

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

//        $this->app['daisycon.get-feeds'] = $this->app->singleton('DaysiconGetFeeds', function () {
//            return new Commands\DaisyconGetFeeds();
//        });

        /*$this->app['daisycon.get-subscriptions'] = $this->app->share(function () {
            return new Commands\DaisyconGetSubscriptions();
        });

        $this->app['daisycon.get-programs'] = $this->app->share(function () {
            return new Commands\DaisyconGetPrograms();
        });

        $this->app['daisycon.fix-data'] = $this->app->share(function () {
            return new Commands\DaisyconFixData();
        });*/

//        $this->app['daisycon.import-data'] = $this->app->share(function () {
        $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');
        $this->app->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');

        $feed_type = ucfirst(strtolower(Config::get('daisycon.feed_type', 'Csv')));

//			$this->app->bind('Bahjaat\Daisycon\Repository\DataImportInterface', 'Bahjaat\Daisycon\Repository\\Raw'.$feed_type.'DataImport');
        if ($feed_type == 'Csv') {
            $this->app->bind(
                'Bahjaat\Daisycon\Repository\DataImportInterface',
                'Bahjaat\Daisycon\Repository\\League' . $feed_type . 'DataImport'
            );
        } elseif ($feed_type == 'Xml') {
            $this->app->bind(
                'Bahjaat\Daisycon\Repository\DataImportInterface',
                'Bahjaat\Daisycon\Repository\\'.$feed_type.'DataImport'
            );
        }

        $this->app->make('Bahjaat\Daisycon\Repository\DataImportInterface');
//            $dataImportInterface = $this->app->make('Bahjaat\Daisycon\Repository\DataImportInterface');
//            return new Commands\DaisyconImportData($dataImportInterface);
//        });
        $this->commands([
            DaisyconGetFeeds::class,
            DaisyconGetPrograms::class,
            DaisyconGetSubscriptions::class,
            DaisyconImportData::class,
            DaisyconFixData::class
//            'daisycon.get-feeds',
//            'daisycon.get-subscriptions',
//            'daisycon.import-data',
//            'daisycon.get-programs',
//            'daisycon.fix-data'
        ]);
    }
}
