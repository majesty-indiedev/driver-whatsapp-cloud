<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ContactsMessage;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Contact;

class ContactsMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $contact = $this->createMock(Contact::class);
        $contact->method('toArray')->willReturn(['name' => 'John Doe', 'phone' => '123456789']);
        $contactsMessage = ContactsMessage::create([$contact]);

        $this->assertInstanceOf(ContactsMessage::class, $contactsMessage);
        $this->assertCount(1, $contactsMessage->toArray()['contacts']);
        $this->assertEquals(['name' => 'John Doe', 'phone' => '123456789'], $contactsMessage->toArray()['contacts'][0]);
    }

    public function testConstructorInitialization()
    {
        $contact = $this->createMock(Contact::class);
        $contact->method('toArray')->willReturn(['name' => 'Jane Doe', 'phone' => '987654321']);
        $contactsMessage = new ContactsMessage([$contact]);

        $this->assertCount(1, $contactsMessage->toArray()['contacts']);
        $this->assertEquals(['name' => 'Jane Doe', 'phone' => '987654321'], $contactsMessage->toArray()['contacts'][0]);
    }

    public function testAddContextMessageId()
    {
        $contact = $this->createMock(Contact::class);
        $contactsMessage = new ContactsMessage([$contact]);
        $contactsMessage->contextMessageId('context123');

        $this->assertEquals('context123', $contactsMessage->getContextMessageId());
        $this->assertEquals('context123', $contactsMessage->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $contact = $this->createMock(Contact::class);
        $contactsMessage = new ContactsMessage([$contact]);
        $contactsMessage->getContextMessageId();
    }

    public function testToArrayWithContextMessageId()
    {
        $contact = $this->createMock(Contact::class);
        $contact->method('toArray')->willReturn(['name' => 'Alice', 'phone' => '555123456']);
        
        $contactsMessage = new ContactsMessage([$contact]);
        $contactsMessage->contextMessageId('context789');

        $expectedArray = [
            'type' => 'contacts',
            'contacts' => [['name' => 'Alice', 'phone' => '555123456']],
            'context' => ['message_id' => 'context789'],
        ];

        $this->assertEquals($expectedArray, $contactsMessage->toArray());
    }

    public function testToWebDriver()
    {
        $contact = $this->createMock(Contact::class);
        $contact->method('toArray')->willReturn(['name' => 'Bob', 'phone' => '333444555']);
        
        $contactsMessage = new ContactsMessage([$contact]);

        $expectedWebDriverArray = [
            'type' => 'contacts',
            'contacts' => [['name' => 'Bob', 'phone' => '333444555']],
        ];

        $this->assertEquals($expectedWebDriverArray, $contactsMessage->toWebDriver());
    }
}
