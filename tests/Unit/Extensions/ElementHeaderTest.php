<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ElementHeader;

class ElementHeaderTest extends TestCase
{
    public function testCreateMethod()
    {
        $content = 'Hello, World!';
        $header = ElementHeader::create('text', $content);

        $this->assertInstanceOf(ElementHeader::class, $header);
        $this->assertEquals('text', $header->getType());
        $this->assertEquals($content, $header->getContent());
    }

    public function testConstructorWithValidType()
    {
        $content = ['url' => 'https://example.com'];
        $header = new ElementHeader('image', $content);

        $this->assertEquals('image', $header->getType());
        $this->assertEquals($content, $header->getContent());
    }

    public function testConstructorWithInvalidType()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown header type');
        
        new ElementHeader('invalid_type', 'content');
    }

    public function testGetTypeMethod()
    {
        $header = new ElementHeader('video', 'https://example.com/video.mp4');
        
        $this->assertEquals('video', $header->getType());
    }

    public function testGetContentMethod()
    {
        $content = 'Document content here';
        $header = new ElementHeader('document', $content);
        
        $this->assertEquals($content, $header->getContent());
    }

    public function testToArrayWithTextType()
    {
        $header = new ElementHeader('text', 'Hello, World!');
        
        $expectedArray = [
            'type' => 'text',
            'text' => 'Hello, World!',
        ];

        $this->assertEquals($expectedArray, $header->toArray());
    }

    public function testToArrayWithImageType()
    {
        $content = ['url' => 'https://example.com/image.jpg'];
        $header = new ElementHeader('image', $content);

        $expectedArray = [
            'type' => 'image',
            'image' => $content,
        ];

        $this->assertEquals($expectedArray, $header->toArray());
    }

    public function testJsonSerializeWithVideoType()
    {
        $content = ['url' => 'https://example.com/video.mp4'];
        $header = new ElementHeader('video', $content);

        $expectedArray = [
            'type' => 'video',
            'video' => $content,
        ];

        $this->assertEquals($expectedArray, $header->jsonSerialize());
    }

    public function testJsonSerializeWithDocumentType()
    {
        $content = ['url' => 'https://example.com/document.pdf'];
        $header = new ElementHeader('document', $content);

        $expectedArray = [
            'type' => 'document',
            'document' => $content,
        ];

        $this->assertEquals($expectedArray, $header->jsonSerialize());
    }
}
