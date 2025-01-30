<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\TemplateMessage;
use BotMan\Drivers\Whatsapp\Extensions\ElementComponent;

class TemplateMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $templateMessage = TemplateMessage::create('template_1', 'en');

        $this->assertInstanceOf(TemplateMessage::class, $templateMessage);
        $this->assertEquals('template_1', $templateMessage->templateId);
        $this->assertEquals('en', $templateMessage->language_code);
    }

    public function testConstructor()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');

        $this->assertEquals('template_1', $templateMessage->templateId);
        $this->assertEquals('en', $templateMessage->language_code);
    }

    public function testAddComponent()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');
        $component = $this->createMock(ElementComponent::class);
        $component->method('toArray')->willReturn(['component' => 'sample_component']);

        $templateMessage->addComponent($component);

        $this->assertCount(1, $templateMessage->components);
        $this->assertEquals(['component' => 'sample_component'], $templateMessage->components[0]);
    }

    public function testAddComponents()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');
        $component1 = $this->createMock(ElementComponent::class);
        $component1->method('toArray')->willReturn(['component' => 'sample_component1']);
        $component2 = $this->createMock(ElementComponent::class);
        $component2->method('toArray')->willReturn(['component' => 'sample_component2']);

        $templateMessage->addComponents([$component1, $component2]);

        $this->assertCount(2, $templateMessage->components);
        $this->assertEquals(['component' => 'sample_component1'], $templateMessage->components[0]);
        $this->assertEquals(['component' => 'sample_component2'], $templateMessage->components[1]);
    }

    public function testSetAndGetContextMessageId()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');
        $templateMessage->contextMessageId('context123');

        $this->assertEquals('context123', $templateMessage->getContextMessageId());
    }

    public function testMissingContextMessageId()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $templateMessage = new TemplateMessage('template_1', 'en');
        $templateMessage->getContextMessageId();
    }

    public function testCategory()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');
        $templateMessage->category('promotion');

        $this->assertEquals('promotion', $templateMessage->category);
    }

    public function testToArray()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');
        $component = $this->createMock(ElementComponent::class);
        $component->method('toArray')->willReturn(['component' => 'sample_component']);
        $templateMessage->addComponent($component);
        $templateMessage->contextMessageId('context123');
        $templateMessage->category('promotion');

        $expectedArray = [
            'type' => 'template',
            'template' => [
                'name' => 'template_1',
                'language' => ['code' => 'en'],
                'components' => [['component' => 'sample_component']],
                'category' => 'promotion',
            ],
            'context' => [
                'message_id' => 'context123',
            ],
        ];

        $this->assertEquals($expectedArray, $templateMessage->toArray());
    }

    public function testJsonSerialize()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');
        $component = $this->createMock(ElementComponent::class);
        $component->method('toArray')->willReturn(['component' => 'sample_component']);
        $templateMessage->addComponent($component);
        $templateMessage->contextMessageId('context123');
        $templateMessage->category('promotion');

        $this->assertEquals($templateMessage->toArray(), $templateMessage->jsonSerialize());
    }

    public function testToWebDriver()
    {
        $templateMessage = new TemplateMessage('template_1', 'en');
        $component = $this->createMock(ElementComponent::class);
        $component->method('toArray')->willReturn(['component' => 'sample_component']);
        $templateMessage->addComponent($component);

        $expectedWebDriverArray = [
            'type' => 'template',
            'template' => [
                'name' => 'template_1',
                'language' => ['code' => 'en'],
                'components' => [['component' => 'sample_component']],
            ],
        ];

        $this->assertEquals($expectedWebDriverArray, $templateMessage->toWebDriver());
    }
}
