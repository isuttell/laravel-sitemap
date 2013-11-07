<?php namespace Isuttell\LaravelSitemap;
/**
 * Sitemap URL Model
 */
class SitemapURL {

	/**
	 * Full url of link
	 * @var string
	 */
	public $loc;

	/**
	 * Last modification date of link - Needs to be in DateTime::W3C  format
	 * @var string
	 */
	public $lastmod = false;

	/**
	 * How often the link changes: hourly, daily, weekly, monthly, yearly, never
	 * @var string
	 */
	public $changefreq = false;

	/**
	 * How important a link is
	 * @var float
	 */
	public $priority = false;


	/**
	 * Create the object
	 * @param string $loc        required
	 * @param string $lastmod    optional - needs to be \DateTime compatible
	 * @param string $changefreq optional
	 * @param float  $priority   optional
	 */
	function __construct($loc, $options = array())
	{
		//Full URL
		$this->loc = $loc;

		if(isset($options['lastmod']))
		{
			$lastmod = new \DateTime($options['lastmod']);
			$this->lastmod    = $lastmod->format(\DateTime::W3C);
		}

		if(isset($options['changefreq'])) $this->changefreq = $options['changefreq'];
		if(isset($options['priority']))   $this->priority = $options['priority'];

	}
}