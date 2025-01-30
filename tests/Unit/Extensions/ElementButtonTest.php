<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ElementButton;

class ElementButtonTest extends TestCase
{
    public function testCreateMethod()
    {
        $button = ElementButton::create('1', 'Test Button');

        $this->assertInstanceOf(ElementButton::class, $button);
        $this->assertEquals('Test Button', $button->toArray()['reply']['title']);
        $this->assertEquals('1', $button->toArray()['reply']['id']);
    }

    public function testTypeMethod()
    {
        $button = ElementButton::create('1', 'Test Button')->type('custom_type');

        $this->assertEquals('custom_type', $button->toArray()['type']);
    }

    public function testToArrayMethod()
    {
        $button = ElementButton::create('1', 'Test Button')
            ->type('custom_type');
  
        $expectedArray = [
            'type' => 'custom_type',
            'reply' => [
                'id' => '1',
                'title' => 'Test Button',
            ],
        ];

        $this->assertEquals($expectedArray, $button->toArray());
    }

    public function testJsonSerializeMethod()
    {
        $button = ElementButton::create('1', 'Test Button')
            ->type('custom_type');

        $expectedJson = json_encode([
            'type' => 'custom_type',
            'reply' => [
                'id' => '1',
                'title' => 'Test Button',
            ],
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($button));
    }


}
