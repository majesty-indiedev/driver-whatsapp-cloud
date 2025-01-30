<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\LocationRequestMessage;

class LocationRequestMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $message = LocationRequestMessage::create('Please send your location.');
        
        $this->assertInstanceOf(LocationRequestMessage::class, $message);
        $this->assertEquals('Please send your location.', $message->text);
    }

    public function testConstructorInitialization()
    {
        $message = new LocationRequestMessage('Send your current location.');
        
        $this->assertEquals('Send your current location.', $message->text);
    }

    public function testContextMessageId()
    {
        $message = new LocationRequestMessage('Share your location.');
        $message->contextMessageId('context123');

        $this->assertEquals('context123', $message->getContextMessageId());
        $this->assertEquals('context123', $message->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $message = new LocationRequestMessage('Share your location.');
        $message->getContextMessageId();
    }

    public function testGetTextThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain text');

        $message = new LocationRequestMessage('');
        $message->getText();
    }

    public function testToArrayWithContextMessageId()
    {
        $message = new LocationRequestMessage('Please share your location.');
        $message->contextMessageId('context456');

        $expectedArray = [
            'type' => 'interactive',
            'interactive' => [
                'type' => 'location_request_message',
                'body' => [
                    'text' => 'Please share your location.',
                ],
                'action' => [
                    'name' => 'send_location',
                ],
            ],
            'context' => ['message_id' => 'context456'],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToArrayWithoutContextMessageId()
    {
        $message = new LocationRequestMessage('Share your location with us.');

        $expectedArray = [
            'type' => 'interactive',
            'interactive' => [
                'type' => 'location_request_message',
                'body' => [
                    'text' => 'Share your location with us.',
                ],
                'action' => [
                    'name' => 'send_location',
                ],
            ],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToWebDriver()
    {
        $message = new LocationRequestMessage('Please share your location.');
        $message->contextMessageId('context789');

        $expectedWebDriverArray = [
            'message_id' => 'context789',
            'text' => 'Please share your location.',
            'type' => 'location_request_message',
        ];

        $this->assertEquals($expectedWebDriverArray, $message->toWebDriver());
    }
}
