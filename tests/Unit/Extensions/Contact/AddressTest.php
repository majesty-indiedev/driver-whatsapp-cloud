<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions\Contact;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Address;

class AddressTest extends TestCase
{
    public function testConstructor()
    {
        $address = new Address('New York', 'USA', 'US', 'NY', '5th Avenue', 'HOME', '10001');

        $this->assertEquals('New York', $address->city);
        $this->assertEquals('USA', $address->country);
        $this->assertEquals('US', $address->country_code);
        $this->assertEquals('NY', $address->state);
        $this->assertEquals('5th Avenue', $address->street);
        $this->assertEquals('HOME', $address->type);
        $this->assertEquals('10001', $address->zip);
    }

    public function testCreateMethod()
    {
        $address = Address::create('Los Angeles', 'USA', 'US', 'CA', 'Hollywood Blvd', 'WORK', '90028');

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals('Los Angeles', $address->city);
        $this->assertEquals('USA', $address->country);
        $this->assertEquals('US', $address->country_code);
        $this->assertEquals('CA', $address->state);
        $this->assertEquals('Hollywood Blvd', $address->street);
        $this->assertEquals('WORK', $address->type);
        $this->assertEquals('90028', $address->zip);
    }

    public function testToArray()
    {
        $address = new Address('London', 'UK', 'GB', 'England', 'Baker Street', 'HOME', 'NW1');

        $addressArray = $address->toArray();

        $this->assertIsArray($addressArray);
        $this->assertArrayHasKey('city', $addressArray);
        $this->assertArrayHasKey('country', $addressArray);
        $this->assertArrayHasKey('country_code', $addressArray);
        $this->assertArrayHasKey('state', $addressArray);
        $this->assertArrayHasKey('street', $addressArray);
        $this->assertArrayHasKey('type', $addressArray);
        $this->assertArrayHasKey('zip', $addressArray);
        $this->assertEquals('London', $addressArray['city']);
        $this->assertEquals('UK', $addressArray['country']);
    }

    public function testJsonSerialize()
    {
        $address = new Address('Paris', 'France', 'FR', 'Île-de-France', 'Champs-Élysées', 'HOME', '75008');

        $this->assertEquals($address->toArray(), $address->jsonSerialize());
    }

    public function testEmptyOptionalFields()
    {
        $address = new Address('Berlin', 'Germany');

        $this->assertEquals('', $address->country_code);
        $this->assertEquals('', $address->state);
        $this->assertEquals('', $address->street);
        $this->assertEquals('HOME', $address->type);
        $this->assertEquals('', $address->zip);
    }
}
