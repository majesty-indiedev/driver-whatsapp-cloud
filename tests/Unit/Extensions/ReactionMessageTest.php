<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ReactionMessage;

class ReactionMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $message = ReactionMessage::create('msg123', '👍');
        
        $this->assertInstanceOf(ReactionMessage::class, $message);
        $this->assertEquals('msg123', $message->message_id);
        $this->assertEquals('👍', $message->emoji);
    }

    public function testConstructor()
    {
        $message = new ReactionMessage('msg123', '👍');

        $this->assertEquals('msg123', $message->message_id);
        $this->assertEquals('👍', $message->emoji);
    }

    public function testToArray()
    {
        $message = new ReactionMessage('msg123', '👍');

        $expectedArray = [
            'type' => 'reaction',
            'reaction' => [
                'message_id' => 'msg123',
                'emoji' => '👍',
            ],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testJsonSerialize()
    {
        $message = new ReactionMessage('msg123', '👍');

        $this->assertEquals($message->toArray(), $message->jsonSerialize());
    }

    public function testToWebDriver()
    {
        $message = new ReactionMessage('msg123', '👍');

        $expectedWebDriverArray = [
            'type' => 'reaction',
            'message_id' => 'msg123',
            'emoji' => '👍',
        ];

        $this->assertEquals($expectedWebDriverArray, $message->toWebDriver());
    }

}
