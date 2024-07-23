<?php

namespace BotMan\Drivers\Whatsapp\Extensions;

use BotMan\BotMan\Interfaces\WebAccess;
use Illuminate\Support\Arr;
use JsonSerializable;

class ListTemplate implements JsonSerializable, WebAccess
{

    /** @var string */
    public $text;

    /** @var string */
    public $header;

    /** @var string */
    public $footer;

    /** @var string */
    public $action;

    /** @var array */
    public $sections = [];

    /** @var string */
    public $context_message_id;



    /**
     * @param $text
     * @return static
     */
    public static function create($text, $header, $footer, $action)
    {
        return new static($text, $header, $footer, $action);
    }

    public function __construct($text, $header, $footer, $action)
    {
        $this->text = $text;
        $this->header = $header;
        $this->footer = $footer;
        $this->action = $action;
    }

    /**
     * @param  ElementSectionList  $list
     * @return $this
     */
    public function addSection(ElementSectionList $list)
    {
        $this->sections[] = $list->toArray();

        return $this;
    }

    /**
     * @param  array  $buttons
     * @return $this
     */
    public function addSections(array $sections)
    {
        foreach ($sections as $list) {
            if ($list instanceof ElementSectionList) {
                $this->sections[] = $list->toArray();
            }
        }

        return $this;
    }


        /**
     * Get the context_message_id.
     *
     * @return string
     */
    public function getContextMessageID()
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
    public function setContextMessageID($context_message_id)
    {
        $this->context_message_id = $context_message_id;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array=[
            'type' => 'interactive',
            'interactive' => [
                    'type' => 'list',
                    'header' => [
                        'type' => 'text',
                        'text' => $this->header,
                    ],
                    'body' => [
                        'text' => $this->text
                    ],
                    'footer' => [
                        'text' => $this->footer,
                    ],

                    'action' => [
                        'button' => $this->action,
                        'sections' => $this->sections,
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
            'type' => 'list',
            'text' => $this->text,
            'sections' => $this->sections,
            'header'=>$this->header,
            'footer'=>$this->footer,
            'button' => $this->action,
        ];
    }
}
