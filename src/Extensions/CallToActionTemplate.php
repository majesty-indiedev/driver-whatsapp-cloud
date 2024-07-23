<?php

namespace BotMan\Drivers\Whatsapp\Extensions;

use JsonSerializable;
use BotMan\BotMan\Interfaces\WebAccess;
use BotMan\Drivers\Whatsapp\Extensions\ElementButtonHeader;

class CallToActionTemplate implements JsonSerializable, WebAccess
{
    /** @var string */
    protected $id;

    /** @var string */
    public $text;

     /** @var string */
     public $footer;

       /** @var string */
    public $action;

    /** @var string */
    public $url;


    /** @var array */
     public $header=[];

    /**
     * @param $text
     * @return static
     */
    public static function create($text,$action,$url)
    {
        return new static($text,$action,$url);
    }

    public function __construct($text,$action,$url)
    {
        $this->text = $text;
        $this->action = $action;
        $this->url = $url;
    }

     /**
     * Get the Footer.
     *
     * @return string
     */
    public function getFooter(){

        if (empty($this->footer)) {
            throw new \UnexpectedValueException('This message does not contain a footer');
        }
        return $this->footer;
    }

    /**
     * Set the Footer.
     * @param  string  $footer
     * @return $this
     */
    public function addFooter($footer){
        $this->footer=$footer;
        return $this;
    }

    /**
     * @param  array $header
     * @return $this
     */
    public function addHeader(ElementButtonHeader $header)
    {
        $this->header = $header->toArray();
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => 'interactive',
            'interactive' => [
                "type"=>"cta_url",
                'header' => $this->header,
                'body' => [
                    "text"=>$this->text
                ],
                'footer' => [
                    "text"=>$this->footer
                ],
                'action' => [
                        "name"=>"cta_url",
                        "parameters"=>[
                            "display_text"=>$this->action,
                            "url"=>$this->url
                        ]
                ]
            ],
        ];
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
            'type' => 'cta_url',
            'text' => $this->text,
            'buttons' => $this->buttons,
            'header'=>$this->header,
            'footer'=>$this->footer,
            "display_text"=>$this->action,
            "url"=>$this->url
        ];
    }

}
