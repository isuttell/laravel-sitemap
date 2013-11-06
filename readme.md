Laravel Sitemap Generator
===============

Creates a sitemap for a Laravel 4 project

Contributors: Isaac Suttell <isaac@isaacsuttell.com>


Installation
===============
First add the following to your `composer.json`
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

In `routes.php` define which links you would like to add to the site map.

```
use Isuttell\LaravelSitemap\Sitemap as Sitemap;
Sitemap::addLink('https://github.com/isuttell/laravel-sitemap');
Sitemap::addAction('HomeController@showWelcome', array());
```

Two routes are automatically added.

```
Route::get('/sitemap.xml',[...]);
```

and

```
Route::get('/sitemap.xml.gz',[...]);
```