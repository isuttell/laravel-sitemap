<{{ "?" }}xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($links as $link)
	<url>
		<loc>{{ $link->loc }}</loc>
		@if ($link->lastmod)
			<lastmod>{{ $link->lastmod }} </lastmod>
		@endif
		@if ($link->changefreq)
			<changefreq>{{ $link->changefreq }} </changefreq>
		@endif
		@if ($link->priority)
			<priority>{{ $link->priority }} </priority>
		@endif
	</url>
@endforeach
</urlset>