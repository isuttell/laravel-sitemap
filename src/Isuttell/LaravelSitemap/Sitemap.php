<?php namespace Isuttell\LaravelSitemap;

use Isuttell\LaravelSitemap\SitemapURL as SitemapURL;
/**
 * Sitemap Generator
 */
class Sitemap {
	/**
	 * An array of registered SitemapURL objects
	 * @var array
	 */
	protected static $links = array();


	/**
	 * Add a text link to the Sitemap
	 * @param string $loc        required Link
	 * @param string $lastmod    optional - should be in \DateTime format
	 * @param string $changefreq optional
	 * @param float  $priority   optional
	 */
	public static function addLink($loc, $options = array())
	{
		$link = new SitemapURL($loc, $options);
		array_push(static::$links, $link);
	}

	/**
	 * Lookup and add an action to the sitemap
	 * @param string $action     Controller Name
	 * @param array  $params action paramaters
	 * @param array  $options    SitemapURL options
	 */
	public static function addAction($action, $params = array(), $options = array())
	{
		$link = new SitemapURL(action($action, $params), $options);
		array_push(static::$links, $link);
	}

	/**
	 * Lookup and add a route to the sitemap
	 * @param string $route   Route name
	 * @param array  $params  route paramters
	 * @param array  $options SitemapURL options
	 */
	public static function addRoute($route, $params = array(), $options = array())
	{
		$link = new SitemapURL(route($route, $params), $options);
		array_push(static::$links, $link);
	}

	/**
	 * Sort links by priority, change freq, loc
	 */
	private static function sort()
	{
		//Setup order of importantance
		$changefreqs = array(
			'hourly'  => 5,
			'daily'   => 4,
			'weekly'  => 3,
			'monthly' => 2,
			'yearly'  => 1,
			'never'   => 0
		);

		//Setup invidual arrays to sort by
		foreach(static::$links as $index => $link)
		{
			$priority[$index]   = $link->priority ? $link->priority : 0;
			$changefreq[$index] = $link->changefreq ? $changefreqs[$link->changefreq] : 0;
			$loc[$index]        = $link->loc;
		}

		array_multisort($priority, SORT_DESC, $changefreq, SORT_DESC, $loc, SORT_ASC, static::$links);
	}

	/**
	 * Returns the Sitemap view based upon http://www.sitemaps.org/protocol.html
	 * @return string
	 */
	private static function getView()
	{
		if(\Config::get('laravel-sitemap::sitemap.sort')) static::sort();
		return \View::make('laravel-sitemap::sitemap')->with('links', static::$links);
	}

	/**
	 * Creates the sitemap from all added links and outputs either an XML file or a
	 * compress XML file
	 *
	 * Available options:
	 * cache    => (bool) | (int) minutes
	 * compress => (bool)
	 *
	 * @param  array  $options an array of options
	 * @return Response object
	 */
	public static function make($options = array())
	{
		/*
		 * By default we won't cache the sitemap. If the cache option is set to true then
		 * we cache the sitemap for 24 hours otherwise if the cache option is an integer
		 * we cache the sitemap for that many minutes
		 */
		if((\Config::get('laravel-sitemap::sitemap.cache') > 0 || \Config::get('laravel-sitemap::sitemap.cache') === true))
		{
			if(Cache::has('cachedSitemapView'))
			{
				$view = Cache::get('cachedSitemapView');
			}
			else
			{
				$view = static::getView();
				Cache::put('cachedSitemapView', $view, \Config::get('laravel-sitemap::sitemap.cache') === true ? 60 * 24 : \Config::get('laravel-sitemap::sitemap.cache'));
			}
		}
		else
		{
			/*
			 * If caching is not turned on just create the view
			 */
			$view = static::getView();
		}

		/*
		 * The default mime for sitemaps is text/xml
		 */
		$mime = 'text/xml';

		/*
		 * If the compress option is set to true then zip the output and switch
		 * the mime type to gzip
		 */
		if(isset($options['compress']) && $options['compress'] === true) {
			$view = gzencode($view);
			$mime = 'application/x-gzip';
		}

		/*
		 * Create the Response object and set the status code to 200
		 */
		$response = \Response::make($view, 200);
		$response->header('Content-Type', $mime);

		return $response;
	}

	/**
	 * Get the list of links
	 * @return array of SitemapURLs
	 */
	public static function getLinks()
	{
		return static::$links;
	}

}