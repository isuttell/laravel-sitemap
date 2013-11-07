laravel-sitemap
===============

A sitemap.xml generator for a Laravel 4

- - -

Contributor(s): Isaac Suttell <<isaac@isaacsuttell.com>>


Installation
===============
First add the following lines to the appropriate sections of your `composer.json`
```
	"require": {
		"isuttell/laravel-sitemap" : "@dev"
	},
```

```
	"repositories": [
		{
			"type": "vcs",
			"url" : "git@github.com:isuttell/laravel-sitemap.git"
		}
	]
```

and then run `composer update`. After the package has been installed, add

```
	'Isuttell\LaravelSitemap\LaravelSitemapServiceProvider',
```

to the `$providers` array found in `config/app.php`


How to use
===============
After your `Routes` in `routes.php` define which links you would like to add to the sitemap.

```
use Isuttell\LaravelSitemap\Sitemap as Sitemap;

//Standard
Sitemap::addLink( url() ); //Add the base the url of the the Laravel App
Sitemap::addLink( 'https://github.com/isuttell/laravel-sitemap' );

//Routes
Sitemap::addRoute( '/home', array() );

//Actions
Sitemap::addAction( 'HomeController@showWelcome', array() );
```

Two routes are automatically added.

```
Route::get('/sitemap.xml',[...]);
```

and

```
Route::get('/sitemap.xml.gz',[...]);
```

Additional Options
------------------
`Sitemap::addLink`, `Sitemap::addAction`, and `Sitemap::addAction` support an additional options array:
```
	[...], array('lastmod'=>'11/11/2113', 'changefreq' =>'monthly', 'priority'=>0.5));
```

* `lastmod` - The date the page was last modified. Should be be in format `Datetime` can parse.
* `changefreq` - How often the page changes. The follow are valid options: `hourly`, `daily`, `weekly`, `monthly`, `yearly`, `never`
* `priority` - How important search engines should treat each page. The default value is `0.5`.

If an option isn't specificed, it's not include in the sitemap.

### Example
```
Sitemap::addLink('https://github.com/isuttell/laravel-sitemap', array('lastmod'=>'now', 'changefreq' =>'daily', 'priority'=>1));
```

Caching
===============

To enable caching, copy the `sitemap.php` and set the `cache` variable to `true` which will cause the sitemap to be cached by Laravel for 24 hours or an `integer` for the number of minutes you want to cache the sitemap. Caching will Laravel's built in caching features.