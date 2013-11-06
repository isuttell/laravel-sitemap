<?php namespace Isuttell\LaravelSitemap;

use Illuminate\Support\ServiceProvider;

class LaravelSitemapServiceProvider extends ServiceProvider {
	/**
	 * A list registered links
	 *
	 * @var array
	 */
	protected $links = array();

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
		$this->package('isuttell/laravel-sitemap');
		include __DIR__ . '/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
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