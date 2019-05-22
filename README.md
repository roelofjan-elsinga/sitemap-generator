# Sitemap Generator

This package helps you to very easily generate a sitemap for your website.

## Installation

You can include this package through Composer using:

```bash
composer require roelofjan-elsinga/sitemap-generator
```

## Usage

```php
use SitemapGenerator\SitemapGenerator;

$generator = new SitemapGenerator();

$generator->add('https://test-url.com/');

$xml_string = (string)$generator;
// OR
$xml_string = $generator->toXML();

print $xml_string;

```

If you don't want to provide the domain name every single time, 
you can do any of the following steps:

```php
$generator = new SitemapGenerator('https://test-url.com');

// or

$generator = new SitemapGenerator();

$generator->setDomain('https://test-url.com');

// or

$generator = SiteMapGenerator::boot('https://test-url.com');
```

Now that you have set the domain name, you can simply do this:

```php
$generator->add('/');
```

This will result in:

```xml
<?xml version="1.0" encoding="UTF-8"?>
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

- ``boot()``: This provides a named constructor for the SitemapGenerator class
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
    string $change_frequency = 'weekly'
): SitemapGenerator
```

This means you can do something like this:

```php
$generator->add('https://test-url.com/blog', 0.9, date('Y-m-d'), 'monthly');

// Or if you've set the domain earlier, this is even simpler:

$generator = SitemapGenerator::boot()->setDomain('https://test-url.com');

$generator->add('/blog', 0.9, date('Y-m-d'), 'monthly');
```

## Valid values

**priority**: A number between 0 and 1, 1 being the most important page, 0 being the least important.

**last_modified**: any date in the "Y-m-d" format

**change_frequency**: yearly, monthly, weekly, daily, etc.
