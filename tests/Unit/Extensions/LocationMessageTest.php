<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\LocationMessage;

class LocationMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $message = LocationMessage::create(40.7128, -74.0060, 'New York', 'New York City, USA');
        
        $this->assertInstanceOf(LocationMessage::class, $message);
        $this->assertEquals(40.7128, $message->longitude);
        $this->assertEquals(-74.0060, $message->latitude);
        $this->assertEquals('New York', $message->name);
        $this->assertEquals('New York City, USA', $message->address);
    }

    public function testConstructorInitialization()
    {
        $message = new LocationMessage(34.0522, -118.2437, 'Los Angeles', 'Los Angeles, USA');
        
        $this->assertEquals(34.0522, $message->longitude);
        $this->assertEquals(-118.2437, $message->latitude);
        $this->assertEquals('Los Angeles', $message->name);
        $this->assertEquals('Los Angeles, USA', $message->address);
    }

    public function testContextMessageId()
    {
        $message = new LocationMessage(37.7749, -122.4194, 'San Francisco', 'San Francisco, USA');
        $message->contextMessageId('context789');

        $this->assertEquals('context789', $message->getContextMessageId());
        $this->assertEquals('context789', $message->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $message = new LocationMessage(51.5074, -0.1278, 'London', 'London, UK');
        $message->getContextMessageId();
    }

    public function testToArrayWithAllFields()
    {
        $message = new LocationMessage(48.8566, 2.3522, 'Paris', 'Paris, France');
        $message->contextMessageId('context101');

        $expectedArray = [
            'type' => 'location',
            'location' => [
                'longitude' => 48.8566,
                'latitude' => 2.3522,
                'name' => 'Paris',
                'address' => 'Paris, France',
            ],
            'context' => ['message_id' => 'context101'],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToWebDriver()
    {
        $message = new LocationMessage(40.730610, -73.935242, 'Brooklyn', 'Brooklyn, New York');
        
        $expectedWebDriverArray = [
            'type' => 'location',
            'longitude' => 40.730610,
            'latitude' => -73.935242,
            'name' => 'Brooklyn',
            'address' => 'Brooklyn, New York',
        ];

        $this->assertEquals($expectedWebDriverArray, $message->toWebDriver());
    }
}
