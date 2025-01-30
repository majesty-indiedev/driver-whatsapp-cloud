<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions\Contact;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\Contact\URL;

class URLTest extends TestCase
{
    public function testConstructor()
    {
        $url = new URL('https://example.com', 'WORK');

        $this->assertInstanceOf(URL::class, $url);
        $this->assertEquals('https://example.com', $url->url);
        $this->assertEquals('WORK', $url->type);
    }

    public function testCreateMethod()
    {
        $url = URL::create('https://another-example.com', 'HOME');

        $this->assertInstanceOf(URL::class, $url);
        $this->assertEquals('https://another-example.com', $url->url);
        $this->assertEquals('HOME', $url->type);
    }

    public function testToArray()
    {
        $url = new URL('https://website.com', 'PERSONAL');

        $urlArray = $url->toArray();

        $this->assertIsArray($urlArray);
        $this->assertArrayHasKey('url', $urlArray);
        $this->assertArrayHasKey('type', $urlArray);
        $this->assertEquals('https://website.com', $urlArray['url']);
        $this->assertEquals('PERSONAL', $urlArray['type']);
    }

    public function testJsonSerialize()
    {
        $url = new URL('https://someurl.com', 'WORK');

        $this->assertEquals($url->toArray(), $url->jsonSerialize());
    }
}
