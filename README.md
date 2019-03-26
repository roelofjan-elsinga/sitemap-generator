# Sitemap Generator

This package helps you to very easily generate a sitemap for your website.

## Usage

```php

use SitemapGenerator\SitemapGenerator;

$generator = new SitemapGenerator();

$generator->add('https://test-url.com/');

$xml_string = (string)$generator;
// OR
$xml_string = $generator->toSitemap();

print $xml_string;

```

This will result in:

```xml
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://test-url.com/</loc>
        <priority>1</priority>
        <lastmod>2019-03-26</lastmod>
        <changefreq>weekly</changefreq>
    </url>
</urlset>
```

## Available methods:

- ``remove(string $url)``: This will remove the given URL from the sitemap
- ``links()``: This will given you the added URL's as an array

## No duplication

The ``add()`` method will filter out any duplicate links, 
so adding a link twice will not result in an additional link in the sitemap.

## Customization

You can customize the priority, lastmod, and changefreq values by providing 
the ``add()`` method with additional information:

```php
public function add(
    string $url, 
    $priority = 1, 
    string $last_modified = null, 
    string $change_frequency = null
): SitemapGenerator
```

This means you can do something like this:

```php
$generator->add('https://test-url.com/blog', 0.9, date('Y-m-d'), 'monthly');
```

## Valid values

**priority**: A number between 0 and 1, 1 being the most important page, 0 being the least important.

**last_modified**: any date in the "Y-m-d" format

**change_frequency**: yearly, monthly, weekly, daily, etc.
