<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\TextMessage;

class TextMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $textMessage = TextMessage::create('Hello, world!');

        $this->assertInstanceOf(TextMessage::class, $textMessage);
        $this->assertEquals('Hello, world!', $textMessage->text);
    }

    public function testConstructor()
    {
        $textMessage = new TextMessage('Hello, world!');

        $this->assertEquals('Hello, world!', $textMessage->text);
    }

    public function testPreviewUrl()
    {
        $textMessage = new TextMessage('Hello, world!');
        $textMessage->previewUrl(false);

        $this->assertFalse($textMessage->preview_url);

        $textMessage->previewUrl(true);
        $this->assertTrue($textMessage->preview_url);
    }

    public function testSetAndGetContextMessageId()
    {
        $textMessage = new TextMessage('Hello, world!');
        $textMessage->contextMessageId('context123');

        $this->assertEquals('context123', $textMessage->getContextMessageId());
    }

    public function testMissingContextMessageId()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $textMessage = new TextMessage('Hello, world!');
        $textMessage->getContextMessageId();
    }

    public function testGetText()
    {
        $textMessage = new TextMessage('Hello, world!');

        $this->assertEquals('Hello, world!', $textMessage->getText());
    }

    public function testMissingText()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain text');

        $textMessage = new TextMessage('');
        $textMessage->getText();
    }

    public function testToArray()
    {
        $textMessage = new TextMessage('Hello, world!');
        $textMessage->previewUrl(false);
        $textMessage->contextMessageId('context123');

        $expectedArray = [
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => 'Hello, world!',
            ],
            'context' => [
                'message_id' => 'context123',
            ],
        ];

        $this->assertEquals($expectedArray, $textMessage->toArray());
    }

    public function testJsonSerialize()
    {
        $textMessage = new TextMessage('Hello, world!');
        $textMessage->previewUrl(false);
        $textMessage->contextMessageId('context123');

        $this->assertEquals($textMessage->toArray(), $textMessage->jsonSerialize());
    }

    public function testToWebDriver()
    {
        $textMessage = new TextMessage('Hello, world!');
        $textMessage->contextMessageId('context123');

        $expectedWebDriverArray = [
            'message_id' => 'context123',
            'text' => 'Hello, world!',
        ];

        $this->assertEquals($expectedWebDriverArray, $textMessage->toWebDriver());
    }
}
