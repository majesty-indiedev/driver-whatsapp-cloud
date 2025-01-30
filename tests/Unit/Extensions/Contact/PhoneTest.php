<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions\Contact;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Phone;

class PhoneTest extends TestCase
{
    public function testConstructor()
    {
        $phone = new Phone('1234567890', 'MOBILE', 'wa123456');

        $this->assertInstanceOf(Phone::class, $phone);
        $this->assertEquals('1234567890', $phone->phone);
        $this->assertEquals('MOBILE', $phone->type);
        $this->assertEquals('wa123456', $phone->wa_id);
    }

    public function testCreateMethod()
    {
        $phone = Phone::create('9876543210', 'HOME');

        $this->assertInstanceOf(Phone::class, $phone);
        $this->assertEquals('9876543210', $phone->phone);
        $this->assertEquals('HOME', $phone->type);
        $this->assertNull($phone->wa_id);  // Default wa_id is null
    }

    public function testToArray()
    {
        $phone = new Phone('5551234567', 'WORK', 'wa789101');

        $phoneArray = $phone->toArray();

        $this->assertIsArray($phoneArray);
        $this->assertArrayHasKey('phone', $phoneArray);
        $this->assertArrayHasKey('type', $phoneArray);
        $this->assertArrayHasKey('wa_id', $phoneArray);
        $this->assertEquals('5551234567', $phoneArray['phone']);
        $this->assertEquals('WORK', $phoneArray['type']);
        $this->assertEquals('wa789101', $phoneArray['wa_id']);
    }

    public function testJsonSerialize()
    {
        $phone = new Phone('8005551212', 'HOME', 'wa654321');

        $this->assertEquals($phone->toArray(), $phone->jsonSerialize());
    }
}
