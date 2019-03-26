<?php

namespace SitemapGenerator;

class SitemapGenerator
{
    private $sitemap_links = [];

    /**
     * Add the given url and related information to the links list
     *
     * @param string $url
     * @param int|float $priority
     * @param string|null $last_modified
     * @param string|null $change_frequency
     * @return SitemapGenerator
     */
    public function add(string $url, $priority = 1, string $last_modified = null, string $change_frequency = null): SitemapGenerator
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

        return "
            <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
                {$urlset_contents}
            </urlset>
        ";
    }

    /**
     * An alias for the __toString method
     *
     * @return string
     */
    public function toSitemap(): string
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
     * Convert the given sitemap url into an XML string
     *
     * @param array $url
     * @return string
     */
    private function urlArrayToString(array $url): string
    {
        return "
            <url>
                <loc>{$url['url']}</loc>
                <priority>{$url['priority']}</priority>
                <lastmod>{$url['lastmod']}</lastmod>
                <changefreq>{$url['changefreq']}</changefreq>
            </url>
        ";
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