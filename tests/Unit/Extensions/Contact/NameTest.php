<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions\Contact;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Name;

class NameTest extends TestCase
{
    public function testConstructor()
    {
        $name = new Name('John', 'John Doe', 'Doe');

        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals('John', $name->first_name);
        $this->assertEquals('John Doe', $name->formatted_name);
        $this->assertEquals('Doe', $name->last_name);
    }

    public function testCreateMethod()
    {
        $name = Name::create('Jane', 'Jane Smith', 'Smith');

        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals('Jane', $name->first_name);
        $this->assertEquals('Jane Smith', $name->formatted_name);
        $this->assertEquals('Smith', $name->last_name);
    }

    public function testToArray()
    {
        $name = new Name('Michael', 'Michael Johnson', 'Johnson');

        $nameArray = $name->toArray();

        $this->assertIsArray($nameArray);
        $this->assertArrayHasKey('first_name', $nameArray);
        $this->assertArrayHasKey('formatted_name', $nameArray);
        $this->assertArrayHasKey('last_name', $nameArray);
        $this->assertEquals('Michael', $nameArray['first_name']);
        $this->assertEquals('Michael Johnson', $nameArray['formatted_name']);
        $this->assertEquals('Johnson', $nameArray['last_name']);
    }

    public function testJsonSerialize()
    {
        $name = new Name('Sarah', 'Sarah Connor', 'Connor');

        $this->assertEquals($name->toArray(), $name->jsonSerialize());
    }
}
