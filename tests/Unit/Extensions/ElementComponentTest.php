<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ElementComponent;

class ElementComponentTest extends TestCase
{
    public function testCreateMethod()
    {
        $parameters = ['param1' => 'value1'];
        $component = ElementComponent::create('test_component', $parameters);

        $this->assertInstanceOf(ElementComponent::class, $component);
        $this->assertEquals('test_component', $component->jsonSerialize()['type']);
        $this->assertEquals($parameters, $component->jsonSerialize()['parameters']);
    }


    public function testTypeMethod()
    {
        $component = new ElementComponent('test_component', []);
        $this->assertEquals('test_component',$component->toArray()['type']);
    }

    public function testToArrayMethod()
    {
        $parameters = ['param1' => 'value1'];
        $component = new ElementComponent('test_component', $parameters);

        $expectedArray = [
            'type' => 'test_component',
            'sub_type' => 'url',
            'index' => '0',
            'parameters' => $parameters,
        ];

        $this->assertEquals($expectedArray, $component->toArray());
    }

    public function testJsonSerializeMethod()
    {
        $parameters = ['param1' => 'value1'];
        $component = new ElementComponent('test_component', $parameters);

        $expectedArray = [
            'type' => 'test_component',
            'sub_type' => 'url',
            'index' => '0',
            'parameters' => $parameters,
        ];

        $this->assertEquals($expectedArray, $component->jsonSerialize());
    }
}
