<?php

namespace BotMan\Drivers\Whatsapp\Extensions;

use BotMan\BotMan\Interfaces\WebAccess;
use Illuminate\Support\Arr;
use JsonSerializable;

class InteractiveTemplate implements JsonSerializable, WebAccess
{

     /** @var string */
     public $templateId;

    /** @var array */
    public $components = [];

    /** @var string */
    public $language_code;



    /**
     * @param $text
     * @return static
     */
    public static function create($templateId,$language_code = 'en')
    {
        return new static($templateId,$language_code);
    }


    public function __construct($templateId,$language_code = 'en')
    {
        $this->templateId = $templateId;
        $this->language_code = $language_code;
    }

    /**
     * @param  ElementComponent  $component
     * @return $this
     */
    public function addComponent(ElementComponent $component)
    {
        $this->components[] = $component->toArray();

        return $this;
    }

    /**
     * @param  array  $component
     * @return $this
     */
    public function addComponents(array $components)
    {
        foreach ($components as $component) {
            if ($component instanceof ElementComponent) {
                $this->components[] = $component->toArray();
            }
        }

        return $this;
    }



    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => 'template',
            'template' => [
                'name' => $this->templateId,
                'language' => ['code' =>$this->language_code],
                "components" => $this->components
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
            'type' => 'template',
            'template' => [
                'name' => $this->templateId,
                'language' => ['code' => $this->language_code],
                "components" => $this->components
            ],
        ];
    }

}
