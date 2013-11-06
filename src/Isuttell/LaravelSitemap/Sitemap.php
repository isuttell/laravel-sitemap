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
	 * @param array  $parameters action paramaters
	 * @param array  $options    SitemapURL options
	 */
	public static function addAction($action, $parameters = array(), $options = array())
	{
		$link = new SitemapURL(action($action, $parameters), $options);
		array_push(static::$links, $link);
	}

	/**
	 * Returns the Sitemap view based upon http://www.sitemaps.org/protocol.html
	 * @return string
	 */
	private static function getView()
	{
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
		 * option: cache => true | minutes
		 *
		 * By default we won't cache the sitemap. If the cache option is set to true then
		 * we cache the sitemap for 24 hours otherwise if the cache option is an integer
		 * we cache the sitemap for that many minutes
		 */
		if(isset($options['cache']) && ($options['cache'] > 0 || $options['cache'] === true)) {
			if(Cache::has('cachedSitemapView'))
			{
				$view = Cache::get('cachedSitemapView');
			}
			else
			{
				$view = static::getView();
				Cache::put('cachedSitemapView', $view, $options['cache'] === true ? 60*24 : $options['cache']);
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