<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\MediaMessage;

class MediaMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $message = MediaMessage::create('image');
        
        $this->assertInstanceOf(MediaMessage::class, $message);
        $this->assertEquals('image', $message->getType());
    }

    public function testConstructorThrowsExceptionForInvalidType()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Unknown media type');

        new MediaMessage('unknown');
    }

    public function testContextMessageId()
    {
        $message = new MediaMessage('image');
        $message->url('https://example.com/image.jpg');
        $message->contextMessageId('context123');

        $this->assertEquals('context123', $message->getContextMessageId());
        $this->assertEquals('context123', $message->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Image does not contain a context_message_id');

        $message = new MediaMessage('image');
        $message->getContextMessageId();
    }

    public function testUrl()
    {
        $message = new MediaMessage('image');
        $message->url('https://example.com/image.jpg');
      
        $this->assertEquals('https://example.com/image.jpg', $message->getUrl());
    }

    public function testId()
    {
        $message = new MediaMessage('image');
        $message->id('12345');

        $this->assertEquals('12345', $message->getId());
    }

    public function testGetUrlThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Image does not contain a url');

        $message = new MediaMessage('image');
        $message->getUrl();
    }

    public function testGetIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Image does not contain an ID');

        $message = new MediaMessage('image');
        $message->getId();
    }

    public function testCaptionForMediaTypesThatSupportIt()
    {
        $message = new MediaMessage('image');
        $message->caption('A beautiful image');

        $this->assertEquals('A beautiful image', $message->getCaption());
    }

    public function testCaptionThrowsExceptionForUnsupportedMediaTypes()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Audio does not support caption');

        $message = new MediaMessage('audio');
        $message->caption('Audio caption');
    }

    public function testFilenameForDocumentType()
    {
        $message = new MediaMessage('document');
        $message->fileName('document.pdf');

        $this->assertEquals('document.pdf', $message->getFileName());
    }

    public function testFilenameThrowsExceptionForNonDocumentTypes()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Image does not support filename');

        $message = new MediaMessage('image');
        $message->fileName('image.jpg');
    }

    public function testToArrayWithUrlAndId()
    {
        $message = new MediaMessage('image');
        $message->url('https://example.com/image.jpg');
        $message->contextMessageId('context456');

        $expectedArray = [
            'type' => 'image',
            'image' => [
                'link' => 'https://example.com/image.jpg',
            ],
            'context' => ['message_id' => 'context456'],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToArrayWithCaptionAndFilenameForDocument()
    {
        $message = new MediaMessage('document');
        $message->url('https://example.com/document.pdf');
        $message->fileName('document.pdf');
        $message->caption('A sample document');
        $message->contextMessageId('context789');

        $expectedArray = [
            'type' => 'document',
            'document' => [
                'link' => 'https://example.com/document.pdf',
                'caption' => 'A sample document',
                'filename' => 'document.pdf',
            ],
            'context' => ['message_id' => 'context789'],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToWebDriver()
    {
        $message = new MediaMessage('image');
        $message->url('https://example.com/image.jpg');
        $message->caption('An image caption');
        $message->contextMessageId('context012');

        $expectedWebDriverArray = [
            'message_id' => 'context012',
            'type' => 'image',
            'link' => 'https://example.com/image.jpg',
            'id' => null,
            'caption' => 'An image caption',
        ];

        $this->assertEquals($expectedWebDriverArray, $message->toWebDriver());
    }
}
