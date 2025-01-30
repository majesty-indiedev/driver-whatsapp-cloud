<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions\Contact;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Contact;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Address;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Email;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Phone;
use BotMan\Drivers\Whatsapp\Extensions\Contact\URL;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Name;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Organization;

class ContactTest extends TestCase
{
    public function testConstructor()
    {
        $name = new Name('John','John Doe','Doe');
        $address = new Address('New York', 'USA', 'US', 'NY', '5th Avenue', 'HOME', '10001');
        $email = new Email('john.doe@example.com');
        $phone = new Phone('+123456789');
        $url = new URL('http://johndoe.com');
        $org = new Organization('Example Corp', 'CEO');

        $contact = new Contact(
            [$address], 
            '1990-01-01', 
            [$email], 
            $name, 
            $org, 
            [$phone], 
            [$url]
        );

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('1990-01-01', $contact->birthday);
        $this->assertEquals($name, $contact->name);
        $this->assertEquals($org, $contact->org);
        $this->assertCount(1, $contact->addresses);
        $this->assertCount(1, $contact->emails);
        $this->assertCount(1, $contact->phones);
        $this->assertCount(1, $contact->urls);
    }

    public function testCreateMethod()
    {
        $name = new Name('John','John Doe','Doe');
        $address = new Address('Los Angeles', 'USA', 'US', 'CA', 'Sunset Blvd', 'WORK', '90028');
        $email = new Email('jane.doe@example.com');
        $phone = new Phone('+987654321');
        $url = new URL('http://janedoe.com');
        $org = new Organization('Example Ltd', 'CEO');

        $contact = Contact::create(
            [$address], 
            '1985-05-15', 
            [$email], 
            $name, 
            $org, 
            [$phone], 
            [$url]
        );

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('1985-05-15', $contact->birthday);
        $this->assertEquals($name, $contact->name);
        $this->assertEquals($org, $contact->org);
        $this->assertCount(1, $contact->addresses);
        $this->assertCount(1, $contact->emails);
        $this->assertCount(1, $contact->phones);
        $this->assertCount(1, $contact->urls);
    }

    public function testToArray()
    {
        $name = new Name('Michael','Michael Smith','Smith');
        $address = new Address('Chicago', 'USA', 'US', 'IL', 'Michigan Ave', 'HOME', '60611');
        $email = new Email('michael.smith@example.com');
        $phone = new Phone('+1122334455');
        $url = new URL('http://michaelsmith.com');
        $org = new Organization('Smith Enterprises', 'CEO');

        $contact = new Contact(
            [$address], 
            '1980-08-20', 
            [$email], 
            $name, 
            $org, 
            [$phone], 
            [$url]
        );

        $contactArray = $contact->toArray();

        $this->assertIsArray($contactArray);
        $this->assertArrayHasKey('addresses', $contactArray);
        $this->assertArrayHasKey('birthday', $contactArray);
        $this->assertArrayHasKey('emails', $contactArray);
        $this->assertArrayHasKey('name', $contactArray);
        $this->assertArrayHasKey('org', $contactArray);
        $this->assertArrayHasKey('phones', $contactArray);
        $this->assertArrayHasKey('urls', $contactArray);
        $this->assertEquals('1980-08-20', $contactArray['birthday']);
    }

    public function testJsonSerialize()
    {
        $name = new Name('Sarah','Sarah Johnson','Johnson');
        $address = new Address('Austin', 'USA', 'US', 'TX', 'Congress Ave', 'HOME', '73301');
        $email = new Email('sarah.johnson@example.com');
        $phone = new Phone('+2233445566');
        $url = new URL('http://sarahjohnson.com');
        $org = new Organization('Johnson LLC','CEO');

        $contact = new Contact(
            [$address], 
            '1995-03-10', 
            [$email], 
            $name, 
            $org, 
            [$phone], 
            [$url]
        );

        $this->assertEquals($contact->toArray(), $contact->jsonSerialize());
    }

    public function testEmptyOptionalFields()
    {
        $name = new Name('Emma','Emma Williams','Williams');
        $address = new Address('San Francisco', 'USA');
        $email = new Email('emma.williams@example.com');
        $phone = new Phone('+1122334455');
        $url = new URL('http://emma.com');
        $contact = new Contact(
            [$address], 
            '1992-07-25', 
            [$email], 
            $name, 
            null, // No organization
            [$phone], 
            [$url]
        );

        $this->assertNull($contact->org);
    }
}
