<?php

namespace BotMan\Drivers\Whatsapp\Extensions;

use BotMan\BotMan\Interfaces\WebAccess;
use JsonSerializable;

class LocationRequestMessage implements JsonSerializable, WebAccess
{
    /** @var string */
    protected $id;

    /** @var string */
    public $text;

    /** @var bool */
    public $preview_url=false;

    /** @var string */
    public $context_message_id;


    /**
     * @param $text
     * @return static
     */
    public static function create($text)
    {
        return new static($text);
    }

    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Get the context_message_id.
     *
     * @return string
     */
    public function getContextMessageId()
    {
        if (empty($this->context_message_id)) {
            throw new \UnexpectedValueException('This message does not contain a context_message_id');
        }
        return $this->context_message_id;
    }


    /**
     * Set the context_message_id.
     * @param  string  $context_message_id
     * @return $this
     */
    public function contextMessageId($context_message_id)
    {
        $this->context_message_id = $context_message_id;

        return $this;
    }


    /**
     * Get the text.
     *
     * @return string
     */
    public function getText(){

        if (empty($this->text)) {
            throw new \UnexpectedValueException('This message does not contain text');
        }
        return $this->text;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $array=[
            'type' => 'interactive',
            'interactive' => [
                "type"=>"location_request_message",
                'body'=>[
                    'text'=>$this->text
                ],
                'action'=>[
                    'name'=>'send_location'
                ]
            ],
        ];

        if(isset($this->context_message_id)){
            $array['context']['message_id'] = $this->context_message_id;
        }

        return $array;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the instance as a web accessible array.
     * This will be used within the WebDriver.
     *
     * @return array
     */
    public function toWebDriver()
    {
        return [
            'message_id'=>isset($this->context_message_id)?$this->context_message_id:null,
            'text' => $this->text,
            "type"=>"location_request_message"
        ];
    }
}
