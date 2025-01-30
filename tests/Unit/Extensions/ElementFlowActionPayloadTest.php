<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ElementFlowActionPayload;

class ElementFlowActionPayloadTest extends TestCase
{
    public function testCreateMethod()
    {
        $data = ['key' => 'value'];
        $payload = ElementFlowActionPayload::create('test_screen', $data);

        $this->assertInstanceOf(ElementFlowActionPayload::class, $payload);
        $this->assertEquals('test_screen', $payload->jsonSerialize()['screen']);
        $this->assertEquals($data, $payload->jsonSerialize()['data']);
    }

    public function testConstructorWithData()
    {
        $data = ['key' => 'value'];
        $payload = new ElementFlowActionPayload('test_screen', $data);

        $this->assertEquals('test_screen', $payload->toArray()['screen']);
        $this->assertEquals($data, $payload->toArray()['data']);
    }

    public function testConstructorWithoutData()
    {
        $payload = new ElementFlowActionPayload('test_screen');

        $this->assertEquals('test_screen', $payload->toArray()['screen']);
        $this->assertArrayNotHasKey('data', $payload->toArray());
    }

    public function testToArrayWithData()
    {
        $data = ['key' => 'value'];
        $payload = new ElementFlowActionPayload('test_screen', $data);

        $expectedArray = [
            'screen' => 'test_screen',
            'data' => $data,
        ];

        $this->assertEquals($expectedArray, $payload->toArray());
    }

    public function testToArrayWithoutData()
    {
        $payload = new ElementFlowActionPayload('test_screen');

        $expectedArray = [
            'screen' => 'test_screen',
        ];

        $this->assertEquals($expectedArray, $payload->toArray());
    }

    public function testJsonSerializeWithData()
    {
        $data = ['key' => 'value'];
        $payload = new ElementFlowActionPayload('test_screen', $data);

        $expectedArray = [
            'screen' => 'test_screen',
            'data' => $data,
        ];

        $this->assertEquals($expectedArray, $payload->jsonSerialize());
    }

    public function testJsonSerializeWithoutData()
    {
        $payload = new ElementFlowActionPayload('test_screen');

        $expectedArray = [
            'screen' => 'test_screen',
        ];

        $this->assertEquals($expectedArray, $payload->jsonSerialize());
    }
}
