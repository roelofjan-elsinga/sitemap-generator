<?php

namespace SitemapGenerator;

class SitemapGenerator
{
    /**@var array*/
    private $sitemap_links = [];
    /**@var string*/
    private $domain = '';
    /**@var bool*/
    private $includes_domain = false;

    /**
     * Set the given domain to the class variable
     *
     * @param string|null $domain
     */
    public function __construct(string $domain = null)
    {
        $this->setDomain($domain);
    }

    /**
     * Provide a named constructor
     *
     * @param string|null $domain
     * @return SitemapGenerator
     */
    public static function boot(string $domain = null): SitemapGenerator
    {
        return new static($domain);
    }

    /**
     * Add the given url and related information to the links list
     *
     * @param string $url
     * @param int|float $priority
     * @param string|null $last_modified
     * @param string|null $change_frequency
     * @return SitemapGenerator
     */
    public function add(string $url, $priority = 1, string $last_modified = null, string $change_frequency = 'weekly'): SitemapGenerator
    {
        if ($this->isAvailableInList($url)) {
            return $this;
        }

        $this->sitemap_links[] = [
            'url' => $url,
            'priority' => $priority,
            'lastmod' => $last_modified ?? date('Y-m-d'),
            'changefreq' => $change_frequency ?? 'weekly'
        ];

        return $this;
    }

    /**
     * Remove the given URL from the links list
     *
     * @param string $url
     * @return SitemapGenerator
     */
    public function remove(string $url): SitemapGenerator
    {
        $this->sitemap_links = array_filter($this->sitemap_links, function ($sitemap_link) use ($url) {
            return $sitemap_link['url'] !== $url;
        });

        return $this;
    }

    /**
     * Format the XML string when this class is casted to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        $array_of_xml_strings = array_map(function ($url) {
            return $this->urlArrayToString($url);
        }, $this->sitemap_links);

        $urlset_contents = implode("\n", $array_of_xml_strings);

        $xml_string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml_string .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        $xml_string .= $urlset_contents . "\n";
        $xml_string .= "</urlset>";

        return $xml_string;
    }

    /**
     * An alias for the __toString method
     *
     * @return string
     */
    public function toXML(): string
    {
        return $this->__toString();
    }

    /**
     * Return the links
     *
     * @return array
     */
    public function links(): array
    {
        return $this->sitemap_links;
    }

    /**
     * Set a domain to prepend to any URL that's added to the list
     *
     * @param string $domain
     * @return SitemapGenerator
     */
    public function setDomain(string $domain = null): SitemapGenerator
    {
        if ($domain) {
            $this->domain = $domain;
            $this->includes_domain = true;
        }

        return $this;
    }

    /**
     * Convert the given sitemap url into an XML string
     *
     * @param array $url
     * @return string
     */
    private function urlArrayToString(array $url): string
    {
        $xml_string = "<url>";
        $xml_string .= "<loc>{$this->getUrl($url['url'])}</loc>";
        $xml_string .= "<lastmod>{$url['lastmod']}</lastmod>";
        $xml_string .= "<changefreq>{$url['changefreq']}</changefreq>";
        $xml_string .= "<priority>{$url['priority']}</priority>";
        $xml_string .= "</url>";

        return $xml_string;
    }

    /**
     * Get the URL for the given string, prepend the domain if it's set
     *
     * @param string $url
     * @return string
     */
    private function getUrl(string $url): string
    {
        if ($this->includes_domain) {
            return "{$this->domain}{$url}";
        }

        return $url;
    }

    /**
     * Determine whether the given URL is already available in the list of links
     *
     * @param string $url
     * @return bool
     */
    private function isAvailableInList(string $url): bool
    {
        $matching_links = array_filter($this->sitemap_links, function ($sitemap_link) use ($url) {
            return $sitemap_link['url'] === $url;
        });

        return count($matching_links) > 0;
    }
}
