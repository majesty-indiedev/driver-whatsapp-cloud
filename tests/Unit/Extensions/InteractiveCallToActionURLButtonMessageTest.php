<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\InteractiveCallToActionURLButtonMessage;
use BotMan\Drivers\Whatsapp\Extensions\ElementHeader;

class InteractiveCallToActionURLButtonMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $message = InteractiveCallToActionURLButtonMessage::create('Sample Text', 'Click Here', 'https://example.com');
        
        $this->assertInstanceOf(InteractiveCallToActionURLButtonMessage::class, $message);
        $this->assertEquals('Sample Text', $message->text);
        $this->assertEquals('Click Here', $message->action);
        $this->assertEquals('https://example.com', $message->url);
    }

    public function testConstructorInitialization()
    {
        $message = new InteractiveCallToActionURLButtonMessage('Test Text', 'Learn More', 'https://example.org');
        
        $this->assertEquals('Test Text', $message->text);
        $this->assertEquals('Learn More', $message->action);
        $this->assertEquals('https://example.org', $message->url);
    }

    public function testAddFooter()
    {
        $message = new InteractiveCallToActionURLButtonMessage('Text with Footer', 'Visit', 'https://visit.com');
        $message->addFooter('Footer Text');

        $this->assertEquals('Footer Text', $message->getFooter());
    }

    public function testGetFooterThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a footer');

        $message = new InteractiveCallToActionURLButtonMessage('Text without Footer', 'Info', 'https://info.com');
        $message->getFooter();
    }

    public function testAddHeader()
    {
        $header = $this->createMock(ElementHeader::class);
        $header->method('toArray')->willReturn(['type' => 'text', 'text' => 'Header Text']);
        
        $message = new InteractiveCallToActionURLButtonMessage('Header Test Text', 'Go', 'https://go.com');
        $message->addHeader($header);

        $this->assertEquals(['type' => 'text', 'text' => 'Header Text'], $message->header);
    }

    public function testContextMessageId()
    {
        $message = new InteractiveCallToActionURLButtonMessage('Context Test', 'Details', 'https://details.com');
        $message->contextMessageId('context456');

        $this->assertEquals('context456', $message->getContextMessageId());
        $this->assertEquals('context456', $message->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $message = new InteractiveCallToActionURLButtonMessage('No Context ID', 'Check', 'https://check.com');
        $message->getContextMessageId();
    }

    public function testToArrayWithContextMessageId()
    {
        $header = $this->createMock(ElementHeader::class);
        $header->method('toArray')->willReturn(['type' => 'text', 'text' => 'Array Header']);
        
        $message = new InteractiveCallToActionURLButtonMessage('Array Test', 'Visit Site', 'https://site.com');
        $message->addFooter('Footer Text');
        $message->addHeader($header);
        $message->contextMessageId('context123');

        $expectedArray = [
            'type' => 'interactive',
            'interactive' => [
                'type' => 'cta_url',
                'header' => ['type' => 'text', 'text' => 'Array Header'],
                'body' => ['text' => 'Array Test'],
                'footer' => ['text' => 'Footer Text'],
                'action' => [
                    'name' => 'cta_url',
                    'parameters' => [
                        'display_text' => 'Visit Site',
                        'url' => 'https://site.com',
                    ],
                ],
            ],
            'context' => [
                'message_id' => 'context123',
            ],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToWebDriver()
    {
        $header = $this->createMock(ElementHeader::class);
        $header->method('toArray')->willReturn(['type' => 'text', 'text' => 'Web Header']);
        
        $message = new InteractiveCallToActionURLButtonMessage('Web Test', 'Read More', 'https://readmore.com');
        $message->addFooter('Web Footer');
        $message->addHeader($header);

        $expectedWebDriverArray = [
            'type' => 'cta_url',
            'text' => 'Web Test',
            'header' => ['type' => 'text', 'text' => 'Web Header'],
            'footer' => 'Web Footer',
            'display_text' => 'Read More',
            'url' => 'https://readmore.com',
        ];

        $this->assertEquals($expectedWebDriverArray, $message->toWebDriver());
    }
}
