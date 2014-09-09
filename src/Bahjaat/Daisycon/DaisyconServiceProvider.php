<?php namespace Bahjaat\Daisycon;

use Illuminate\Support\ServiceProvider;

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
		    return new Commands\DaisyconImportData();
		});
		$this->app['daisycon.getprograms'] = $this->app->share(function(){
		    return new Commands\DaisyconPrograms();
		});
        $this->commands(
            'daisycon.getfeeds',
            'daisycon.getsubscriptions',
            'daisycon.import-data',
            'daisycon.getprograms'
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
