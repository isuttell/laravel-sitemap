<?php

/*
|--------------------------------------------------------------------------
| Sitemap Route
|--------------------------------------------------------------------------
*/
use Isuttell\LaravelSitemap\Sitemap as Sitemap;

Route::get('/sitemap.xml', function()
{
	return Sitemap::make();
});

Route::get('/sitemap.xml.gz', function()
{
	return Sitemap::make(array('compress'=>true));
});