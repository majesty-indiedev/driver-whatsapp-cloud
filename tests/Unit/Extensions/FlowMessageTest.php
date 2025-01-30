<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\FlowMessage;
use BotMan\Drivers\Whatsapp\Extensions\ElementHeader;
use BotMan\Drivers\Whatsapp\Extensions\ElementFlowActionPayload;

class FlowMessageTest extends TestCase
{
    public function testCreateMethod()
    {
        $flowMessage = FlowMessage::create('flow_id', 'token123', 'Click here', 'Sample Text');

        $this->assertInstanceOf(FlowMessage::class, $flowMessage);
        $this->assertEquals('Sample Text', $flowMessage->text);
        $this->assertEquals('flow_id', $flowMessage->toArray()['interactive']['action']['parameters']['flow_id']);
        $this->assertEquals('token123', $flowMessage->toArray()['interactive']['action']['parameters']['flow_token']);
        $this->assertEquals('Click here', $flowMessage->toArray()['interactive']['action']['parameters']['flow_cta']);
    }

    public function testConstructorInitialization()
    {
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');

        $this->assertEquals('Sample Text', $flowMessage->text);
        $this->assertEquals('flow_id', $flowMessage->toArray()['interactive']['action']['parameters']['flow_id']);
        $this->assertEquals('token123', $flowMessage->toArray()['interactive']['action']['parameters']['flow_token']);
    }

    public function testAddFooter()
    {
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');
        $flowMessage->addFooter('Sample Footer');

        $this->assertEquals('Sample Footer', $flowMessage->getFooter());
    }

    public function testGetFooterThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a footer');

        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');
        $flowMessage->getFooter();
    }

    public function testAddHeader()
    {
        $header = new ElementHeader('text', 'Sample Header');
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');
        $flowMessage->addHeader($header);

        $this->assertEquals($header->toArray(), $flowMessage->toArray()['interactive']['header']);
    }

    public function testAddActionPayload()
    {
        $payload = new ElementFlowActionPayload('sample_screen');
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text', 'published', 'navigate');
        $flowMessage->addActionPayload($payload);

        $this->assertEquals($payload->toArray(), $flowMessage->toArray()['interactive']['action']['parameters']['flow_action_payload']);
    }

    public function testAddActionPayloadThrowsExceptionIfActionIsNotNavigate()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Action payload is required if flow_action is navigate. Should be omitted otherwise.');

        $payload = new ElementFlowActionPayload('sample_screen');
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text', 'published', 'not_navigate');
        $flowMessage->addActionPayload($payload);
    }

    public function testContextMessageId()
    {
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');
        $flowMessage->contextMessageId('context123');

        $this->assertEquals('context123', $flowMessage->getContextMessageId());
        $this->assertEquals('context123', $flowMessage->toArray()['context']['message_id']);
    }

    public function testGetContextMessageIdThrowsExceptionIfEmpty()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('This message does not contain a context_message_id');

        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');
        $flowMessage->getContextMessageId();
    }

    public function testToArrayWithActionPayloadAndContextMessageId()
    {
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');
        $payload = new ElementFlowActionPayload('sample_screen');
        $flowMessage->addActionPayload($payload)->contextMessageId('context123');

        $expectedArray = [
            'type' => 'interactive',
            'interactive' => [
                'type' => 'flow',
                'header' => null,
                'body' => [
                    'text' => 'Sample Text',
                ],
                'footer' => [
                    'text' => null,
                ],
                'action' => [
                    'name' => 'flow',
                    'parameters' => [
                        'flow_message_version' => 3,
                        'flow_token' => 'token123',
                        'flow_id' => 'flow_id',
                        'flow_cta' => 'Click here',
                        'mode' => 'published',
                        'flow_action' => 'navigate',
                        'flow_action_payload' => $payload->toArray(),
                    ],
                ],
            ],
            'context' => [
                'message_id' => 'context123',
            ],
        ];

      
        $this->assertEquals($expectedArray, $flowMessage->toArray());
    }

    public function testToWebDriver()
    {
        $flowMessage = new FlowMessage('flow_id', 'token123', 'Click here', 'Sample Text');
        $flowMessage->addFooter('Footer Text');

        $expectedWebDriverArray = [
            'type' => 'buttons',
            'text' => 'Sample Text',
            'header' => null,
            'footer' => 'Footer Text',
            'message_version' => 3,
            'token' => 'token123',
            'id' => 'flow_id',
            'cta_text' => 'Click here',
            'action' => 'navigate',
        ];

        $this->assertEquals($expectedWebDriverArray, $flowMessage->toWebDriver());
    }
}
