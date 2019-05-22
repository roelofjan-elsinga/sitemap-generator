<?php

use PHPUnit\Framework\TestCase;
use SitemapGenerator\SitemapGenerator;

class SitemapGeneratorTest extends TestCase
{

    public function testInit()
    {
        $generator = new SitemapGenerator();

        $this->assertEquals('SitemapGenerator\SitemapGenerator', get_class($generator));

        $generator = SitemapGenerator::boot();

        $this->assertEquals('SitemapGenerator\SitemapGenerator', get_class($generator));
    }

    public function testAddingLinkToSitemap()
    {
        $generator = new SitemapGenerator();

        $generator->add('https://test-url.com/');

        $links = $generator->links();

        $this->assertEquals('https://test-url.com/', $links[0]['url']);

        $this->assertEquals(1, $links[0]['priority']);
    }

    public function testAddingLinkWithCustomPriorityAddsCorrectValue()
    {
        $generator = new SitemapGenerator();

        $generator->add('https://test-url.com/', 0.9);

        $links = $generator->links();

        $this->assertEquals('https://test-url.com/', $links[0]['url']);

        $this->assertEquals(0.9, $links[0]['priority']);
    }

    public function testRemovingLinkToSitemap()
    {
        $generator = new SitemapGenerator();

        $generator->add('https://test-url.com/');

        $this->assertCount(1, $generator->links());

        $generator->remove('https://test-url.com/');

        $this->assertCount(0, $generator->links());
    }

    public function testAddingDuplicateLinkToSitemapOnlyKeepsOneCopy()
    {
        $generator = new SitemapGenerator();

        $generator->add('https://test-url.com/');

        $generator->add('https://test-url.com/');

        $generator->add('https://test-url.com/');

        $this->assertCount(1, $generator->links());
    }

    public function testToStringReturnsXMLString()
    {
        $generator = new SitemapGenerator();

        $generator->add('https://test-url.com/');

        $xml_string = (string)$generator;

        $this->assertTrue($this->isValidXml($xml_string));
    }

    private function isValidXml($xml): bool
    {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->loadXML($xml);

        $errors = libxml_get_errors();

        return empty($errors);
    }

    public function testDomainIsSetThroughInit()
    {
        $generator = new SitemapGenerator('https://test.com');

        $generator->add('/');

        $this->assertTrue(strpos($generator->toXML(), 'https://test.com/') !== false);
    }

    public function testDomainIsSetThroughBootMethod()
    {
        $generator = SitemapGenerator::boot('https://test.com');

        $generator->add('/');

        $this->assertTrue(strpos($generator->toXML(), 'https://test.com/') !== false);
    }

}