<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\InteractiveListMessage;
use BotMan\Drivers\Whatsapp\Extensions\ElementSectionList;

class InteractiveListMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $message = InteractiveListMessage::create('Sample Text', 'Action Button');
        
        $this->assertInstanceOf(InteractiveListMessage::class, $message);
        $this->assertEquals('Sample Text', $message->text);
        $this->assertEquals('Action Button', $message->action);
    }

    public function testConstructorInitialization()
    {
        $message = new InteractiveListMessage('Text Initialization', 'Button Init');
        
        $this->assertEquals('Text Initialization', $message->text);
        $this->assertEquals('Button Init', $message->action);
    }

    public function testAddSection()
    {
        $section = $this->createMock(ElementSectionList::class);
        $section->method('toArray')->willReturn(['title' => 'Section Title']);
        
        $message = new InteractiveListMessage('Section Test Text', 'Section Button');
        $message->addSection($section);

        $this->assertCount(1, $message->sections);
        $this->assertEquals([['title' => 'Section Title']], $message->sections);
    }

    public function testAddSections()
    {
        $section1 = $this->createMock(ElementSectionList::class);
        $section1->method('toArray')->willReturn(['title' => 'Section One']);
        
        $section2 = $this->createMock(ElementSectionList::class);
        $section2->method('toArray')->willReturn(['title' => 'Section Two']);
        
        $message = new InteractiveListMessage('Multiple Sections Test', 'Multi Button');
        $message->addSections([$section1, $section2]);

        $this->assertCount(2, $message->sections);
        $this->assertEquals([['title' => 'Section One'], ['title' => 'Section Two']], $message->sections);
    }

    public function testAddFooter()
    {
        $message = new InteractiveListMessage('Footer Test', 'Footer Button');
        $message->addFooter('Test Footer');

        $this->assertEquals('Test Footer', $message->getFooter());
    }

    public function testGetFooterThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a footer');

        $message = new InteractiveListMessage('No Footer Text', 'No Footer Button');
        $message->getFooter();
    }

    public function testAddHeader()
    {
        $message = new InteractiveListMessage('Header Test', 'Header Button');
        $message->addHeader('Header Text');

        $this->assertEquals('Header Text', $message->header);
    }

    public function testContextMessageId()
    {
        $message = new InteractiveListMessage('Context ID Test', 'Context Button');
        $message->contextMessageId('context123');

        $this->assertEquals('context123', $message->getContextMessageId());
        $this->assertEquals('context123', $message->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $message = new InteractiveListMessage('No Context ID Text', 'No Context Button');
        $message->getContextMessageId();
    }

    public function testToArrayWithAllFields()
    {
        $section = $this->createMock(ElementSectionList::class);
        $section->method('toArray')->willReturn(['title' => 'Array Section']);
        
        $message = new InteractiveListMessage('Array Test Text', 'Array Action');
        $message->addHeader('Array Header');
        $message->addFooter('Array Footer');
        $message->addSection($section);
        $message->contextMessageId('context456');

        $expectedArray = [
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'header' => [
                    'type' => 'text',
                    'text' => 'Array Header',
                ],
                'body' => [
                    'text' => 'Array Test Text',
                ],
                'footer' => [
                    'text' => 'Array Footer',
                ],
                'action' => [
                    'button' => 'Array Action',
                    'sections' => [['title' => 'Array Section']],
                ],
            ],
            'context' => [
                'message_id' => 'context456',
            ],
        ];

        $this->assertEquals($expectedArray, $message->toArray());
    }

    public function testToWebDriver()
    {
        $section = $this->createMock(ElementSectionList::class);
        $section->method('toArray')->willReturn(['title' => 'WebDriver Section']);
        
        $message = new InteractiveListMessage('WebDriver Test Text', 'WebDriver Button');
        $message->addHeader('WebDriver Header');
        $message->addFooter('WebDriver Footer');
        $message->addSection($section);

        $expectedWebDriverArray = [
            'type' => 'list',
            'text' => 'WebDriver Test Text',
            'sections' => [['title' => 'WebDriver Section']],
            'header' => 'WebDriver Header',
            'footer' => 'WebDriver Footer',
            'button' => 'WebDriver Button',
        ];

        $this->assertEquals($expectedWebDriverArray, $message->toWebDriver());
    }
}
