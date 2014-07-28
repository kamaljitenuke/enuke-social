<?php namespace Enuke\Socialogin;

use Illuminate\Support\ServiceProvider;
use App;

class SocialoginServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('enuke/socialogin');
		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		$this->app['socialogin'] = $this->app->share(function($app){
		    return new Socialogin($app['config']->get('socialogin::secret'));
		  });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('socialogin');
	}

}
