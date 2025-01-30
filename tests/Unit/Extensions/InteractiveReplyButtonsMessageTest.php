<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\InteractiveReplyButtonsMessage;
use BotMan\Drivers\Whatsapp\Extensions\ElementButton;
use BotMan\Drivers\Whatsapp\Extensions\ElementHeader;

class InteractiveReplyButtonsMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $message = InteractiveReplyButtonsMessage::create('Sample Text');
        
        $this->assertInstanceOf(InteractiveReplyButtonsMessage::class, $message);
        $this->assertEquals('Sample Text', $message->text);
    }

    public function testConstructorInitialization()
    {
        $message = new InteractiveReplyButtonsMessage('Text Initialization');
        
        $this->assertEquals('Text Initialization', $message->text);
    }

    public function testAddFooter()
    {
        $message = new InteractiveReplyButtonsMessage('Footer Test Text');
        $message->addFooter('Test Footer');

        $this->assertEquals('Test Footer', $message->getFooter());
    }

    public function testGetFooterThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a footer');

        $message = new InteractiveReplyButtonsMessage('No Footer Text');
        $message->getFooter();
    }

    public function testAddHeader()
    {
        $header = $this->createMock(ElementHeader::class);
        $header->method('toArray')->willReturn(['type' => 'text', 'text' => 'Header Text']);
        
        $message = new InteractiveReplyButtonsMessage('Header Test Text');
        $message->addHeader($header);

        $this->assertEquals(['type' => 'text', 'text' => 'Header Text'], $message->header);
    }

    public function testAddButton()
    {
        $button = $this->createMock(ElementButton::class);
        $button->method('toArray')->willReturn(['type' => 'reply', 'text' => 'Button Text']);
        
        $message = new InteractiveReplyButtonsMessage('Button Test Text');
        $message->addButton($button);

        $this->assertCount(1, $message->buttons);
        $this->assertEquals([['type' => 'reply', 'text' => 'Button Text']], $message->buttons);
    }

    public function testAddButtons()
    {
        $button1 = $this->createMock(ElementButton::class);
        $button1->method('toArray')->willReturn(['type' => 'reply', 'text' => 'Button 1']);
        
        $button2 = $this->createMock(ElementButton::class);
        $button2->method('toArray')->willReturn(['type' => 'reply', 'text' => 'Button 2']);
        
        $message = new InteractiveReplyButtonsMessage('Multiple Buttons Test');
        $message->addButtons([$button1, $button2]);

        $this->assertCount(2, $message->buttons);
        $this->assertEquals([['type' => 'reply', 'text' => 'Button 1'], ['type' => 'reply', 'text' => 'Button 2']], $message->buttons);
    }

    public function testContextMessageId()
    {
        $message = new InteractiveReplyButtonsMessage('Context ID Test');
        $message->contextMessageId('context123');

        $this->assertEquals('context123', $message->getContextMessageId());
        $this->assertEquals('context123', $message->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $message = new InteractiveReplyButtonsMessage('No Context ID Text');
        $message->getContextMessageId();
    }

    public function testToArrayWithAllFields()
    {
        $header = $this->createMock(ElementHeader::class);
        $header->method('toArray')->willReturn(['type' => 'text', 'text' => 'Array Header']);
        
        $button = $this->createMock(ElementButton::class);
        $button->method('toArray')->willReturn(['type' => 'reply', 'text' => 'Array Button']);
        
        $message = new InteractiveReplyButtonsMessage('Array Test Text');
        $message->addFooter('Array Footer');
        $message->addHeader($header);
        $message->addButton($button);
        $message->contextMessageId('context456');

        $expectedArray = [
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'header' => ['type' => 'text', 'text' => 'Array Header'],
                'body' => ['text' => 'Array Test Text'],
                'footer' => ['text' => 'Array Footer'],
                'action' => [
                    'buttons' => [['type' => 'reply', 'text' => 'Array Button']]
                ],
            ],
            'context' => ['message_id' => 'context456'],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToWebDriver()
    {
        $header = $this->createMock(ElementHeader::class);
        $header->method('toArray')->willReturn(['type' => 'text', 'text' => 'WebDriver Header']);
        
        $button = $this->createMock(ElementButton::class);
        $button->method('toArray')->willReturn(['type' => 'reply', 'text' => 'WebDriver Button']);
        
        $message = new InteractiveReplyButtonsMessage('WebDriver Test Text');
        $message->addFooter('WebDriver Footer');
        $message->addHeader($header);
        $message->addButton($button);

        $expectedWebDriverArray = [
            'type' => 'buttons',
            'text' => 'WebDriver Test Text',
            'buttons' => [['type' => 'reply', 'text' => 'WebDriver Button']],
            'header' => ['type' => 'text', 'text' => 'WebDriver Header'],
            'footer' => 'WebDriver Footer',
        ];

        $this->assertEquals($expectedWebDriverArray, $message->toWebDriver());
    }
}
