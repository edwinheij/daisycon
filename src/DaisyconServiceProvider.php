<?php

namespace Bahjaat\Daisycon;

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
        $this->publishes([
            __DIR__ . '/config/daisycon.php' => config_path('daisycon.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/daisycon.php', 'daisycon'
        );

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app['daisycon.get-feeds'] = $this->app->share(function () {
            return new Commands\DaisyconGetFeeds();
        });

        $this->app['daisycon.get-subscriptions'] = $this->app->share(function () {
            return new Commands\DaisyconGetSubscriptions();
        });

        $this->app['daisycon.get-programs'] = $this->app->share(function () {
            return new Commands\DaisyconGetPrograms();
        });

        $this->app['daisycon.fix-data'] = $this->app->share(function () {
            return new Commands\DaisyconFixData();
        });

        $this->app['daisycon.import-data'] = $this->app->share(function () {
            $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');
            $this->app->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');

            $feed_type = ucfirst(strtolower(Config::get('daisycon.feed_type', 'Csv')));

//			$this->app->bind('Bahjaat\Daisycon\Repository\DataImportInterface', 'Bahjaat\Daisycon\Repository\\Raw'.$feed_type.'DataImport');
            if ($feed_type == 'Csv') {
                $this->app->bind('Bahjaat\Daisycon\Repository\DataImportInterface',
                    'Bahjaat\Daisycon\Repository\\League' . $feed_type . 'DataImport');
            } elseif ($feed_type == 'Xml') {
                $this->app->bind('Bahjaat\Daisycon\Repository\DataImportInterface', 'Bahjaat\Daisycon\Repository\\'.$feed_type.'DataImport');
            }

            $dataImportInterface = $this->app->make('Bahjaat\Daisycon\Repository\DataImportInterface');
            return new Commands\DaisyconImportData($dataImportInterface);
        });

        $this->commands(
            'daisycon.get-feeds',
            'daisycon.get-subscriptions',
            'daisycon.import-data',
            'daisycon.get-programs',
            'daisycon.fix-data'
        );
    }
}
