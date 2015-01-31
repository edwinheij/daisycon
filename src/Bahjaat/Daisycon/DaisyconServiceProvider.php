<?php namespace Bahjaat\Daisycon;

use Illuminate\Support\ServiceProvider;
use Bahjaat\Daisycon\Repository\DataImportInterface;
use Config;
//use Maatwebsite\Excel\Facades\Excel;

class DaisyconServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Booting
     */
    public function boot()
    {
        $this->package('bahjaat/daisycon');
//		$this->app->booting(function()
//		{
//			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
//			$loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
//		});
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app['daisycon.getfeeds'] = $this->app->share(function(){
		    return new Commands\DaisyconFeeds();
		});
		$this->app['daisycon.getsubscriptions'] = $this->app->share(function(){
		    return new Commands\DaisyconSubscriptions();
		});
		$this->app['daisycon.import-data'] = $this->app->share(function(){
			$this->app->register('Maatwebsite\Excel\ExcelServiceProvider');
			$this->app->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');

			$feed_type = ucfirst(strtolower(Config::get('Packages\Bahjaat\Daisycon\config.feed_type', 'Csv')));
			$this->app->bind('Bahjaat\Daisycon\Repository\DataImportInterface', 'Bahjaat\Daisycon\Repository\\'.$feed_type.'DataImport');
			$dataImportInterface = $this->app->make('Bahjaat\Daisycon\Repository\DataImportInterface');
		    return new Commands\DaisyconImportData($dataImportInterface);
		});
		$this->app['daisycon.getprograms'] = $this->app->share(function(){
		    return new Commands\DaisyconPrograms();
		});
		$this->app['daisycon.fix-data'] = $this->app->share(function(){
		    return new Commands\DaisyconFixData();
		});
        $this->commands(
            'daisycon.getfeeds',
            'daisycon.getsubscriptions',
            'daisycon.import-data',
            'daisycon.getprograms',
			'daisycon.fix-data'
        );
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
