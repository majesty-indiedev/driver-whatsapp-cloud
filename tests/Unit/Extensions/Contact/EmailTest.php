<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions\Contact;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Email;

class EmailTest extends TestCase
{
    public function testConstructor()
    {
        $email = new Email('john.doe@example.com', 'WORK');

        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('john.doe@example.com', $email->email);
        $this->assertEquals('WORK', $email->type);
    }

    public function testCreateMethod()
    {
        $email = Email::create('jane.doe@example.com', 'HOME');

        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('jane.doe@example.com', $email->email);
        $this->assertEquals('HOME', $email->type);
    }

    public function testToArray()
    {
        $email = new Email('michael.smith@example.com', 'WORK');

        $emailArray = $email->toArray();

        $this->assertIsArray($emailArray);
        $this->assertArrayHasKey('email', $emailArray);
        $this->assertArrayHasKey('type', $emailArray);
        $this->assertEquals('michael.smith@example.com', $emailArray['email']);
        $this->assertEquals('WORK', $emailArray['type']);
    }

    public function testJsonSerialize()
    {
        $email = new Email('sarah.johnson@example.com', 'HOME');

        $this->assertEquals($email->toArray(), $email->jsonSerialize());
    }
}
