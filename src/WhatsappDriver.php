<?php

namespace Botman\Drivers\Whatsapp;

use BotMan\BotMan\Users\User;
use Illuminate\Support\Collection;
use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Interfaces\UserInterface;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Attachments\File;
use BotMan\BotMan\Interfaces\VerifiesService;
use BotMan\BotMan\Messages\Attachments\Audio;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Outgoing\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BotMan\Drivers\Whatsapp\Extensions\TemplateMessage;
use BotMan\BotMan\Exceptions\Base\DriverException;
use Symfony\Component\HttpFoundation\ParameterBag;
use BotMan\Drivers\Whatsapp\Extensions\FlowMessage;
use BotMan\Drivers\Whatsapp\Extensions\TextMessage;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Whatsapp\Extensions\MediaMessage;
use BotMan\Drivers\Whatsapp\Extensions\ContactsMessage;
use BotMan\Drivers\Whatsapp\Extensions\LocationMessage;
use BotMan\Drivers\Whatsapp\Extensions\ReactionMessage;
use BotMan\Drivers\Whatsapp\Exceptions\WhatsappException;
use BotMan\Drivers\Whatsapp\Extensions\InteractiveListMessage;
use BotMan\Drivers\Whatsapp\Extensions\LocationRequestMessage;
use BotMan\Drivers\Whatsapp\Extensions\InteractiveReplyButtonsMessage;
use BotMan\Drivers\Whatsapp\Extensions\InteractiveCallToActionURLButtonMessage;

class WhatsappDriver extends HttpDriver implements VerifiesService
{
    const DRIVER_NAME = 'Whatsapp';

    protected $endpoint = 'messages';

     /** @var string */
     protected $signature;

     /** @var array */
     protected $messages = [];

    /** @var array */
    protected $templates = [
        InteractiveReplyButtonsMessage::class,
        InteractiveListMessage::class,
        TemplateMessage::class,
        TextMessage::class,
        MediaMessage::class,
        ContactsMessage::class,
        LocationMessage::class,
        InteractiveCallToActionURLButtonMessage::class,
        ReactionMessage::class,
        LocationRequestMessage::class,
        FlowMessage::class,
    ];

    private $supportedAttachments = [
        Video::class,
        Audio::class,
        Image::class,
        File::class,
    ];

    /**
     * @param Request $request
     * @return void
     */
    public function buildPayload(Request $request)
    {
        $this->payload =Collection::make((array)json_decode($request->getContent(), true));
        $this->event = Collection::make(isset($this->payload->get('entry')[0]['changes'][0]['value']['messages'])?(array)$this->payload->get('entry')[0]['changes'][0]['value']['messages'][0]:null);
        $this->signature = $request->headers->get('X_HUB_SIGNATURE_256','');
        $this->content = $request->getContent();
        $this->config = Collection::make($this->config->get('whatsapp', []));
    }

    /**
     * Determine if the request is for this driver.
     *
     * @return bool
     */
    public function matchesRequest()
    {
        $validSignature = empty($this->config->get('app_secret')) || $this->validateSignature();
        $machesDriverMessage=!is_null($this->payload->get('contacts')) || !is_null($this->event->get('from'));

        return $machesDriverMessage && $validSignature;
    }

    /**
     * @param  Request  $request
     * @return null|Response
     */
    public function verifyRequest(Request $request)
    {
        if($request->get('hub_mode') === 'subscribe'){
            if ($request->get('hub_verify_token') === $this->config->get('verification')) {
                return (new Response($request->get('hub_challenge'),200))->send();
            }
            else{
                return (new Response('Invalid verification token', 403))->send();
            }
        }
    }

    /**
     * @return bool
     */
    protected function validateSignature()
    {
        return hash_equals(
            $this->signature,
            'sha256=' . hash_hmac('sha256', $this->content, $this->config->get('app_secret'))
        );
    }

     /**
     * @param  IncomingMessage  $matchingMessage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function markSeen(IncomingMessage $matchingMessage)
    {
        $payload=[
            "messaging_product"=>"whatsapp",
            "status"=>"read",
            "message_id"=>$matchingMessage->getExtras('id')
        ];

        if ($this->config->get('throw_http_exceptions')) {
            return $this->postWithExceptionHandling($this->buildApiUrl($this->endpoint), [], $payload, $this->buildAuthHeader(), true);
        }

        return $this->http->post($this->buildApiUrl($this->endpoint), [], $payload, $this->buildAuthHeader(), true);

    }

    /**
     * Retrieve the chat message.
     *
     * @return array
     */
    public function getMessages()
    {
        if (empty($this->messages)) {
            $this->loadMessages();
        }

        return $this->messages;
    }

    /**
     * Load Whatsapp messages.
     */
    protected function loadMessages()
    {

        if ($this->event->get('type') == 'text') {
            $message=new IncomingMessage(
                $this->event->get('text')['body'],
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        } elseif ($this->event->get('type') == 'image') {
            $message=new IncomingMessage(
                isset($this->event->get('image')['caption']) ? $this->event->get('image')['caption'] : '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
                );
        } elseif ($this->event->get('type') == 'document') {
            $message=new IncomingMessage(
                isset($this->event->get('document')['caption']) ? $this->event->get('document')['caption'] : '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        } elseif ($this->event->get('type') == 'audio') {
            $message=new IncomingMessage(
                '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        } elseif ($this->event->get('type') == 'video') {
            $message=new IncomingMessage(
                isset($this->event->get('video')['caption']) ? $this->event->get('video')['caption'] : '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }elseif ($this->event->get('type') == 'location') {
            $message=new IncomingMessage(
                isset($this->event->get('location')['name']) ? $this->event->get('location')['name'] : '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }
        elseif ($this->event->get('type') == 'contacts') {
            $message=new IncomingMessage(
                isset($this->event->get('contacts')[0]['formatted_name']) ? $this->event->get('contacts')[0]['formatted_name'] : '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }
        elseif ($this->event->get('type') == 'reaction') {
            $message=new IncomingMessage(
                isset($this->event->get('reaction')['emoji']) ? $this->event->get('reaction')['emoji'] : '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }
        elseif ($this->event->get('type') == 'button') {
            $message=new IncomingMessage(
                $this->event->get('button')['text'],
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }
        elseif ($this->event->get('type') == 'interactive') {
            $message=$this->getInteractiveMessage();
        }
        elseif ($this->event->get('type') == 'request_welcome') {
            $message=new IncomingMessage(
                '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }
        else{
            $message=new IncomingMessage(
                '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }

        if (!empty($message)) {
            $this->messages = [
            $message
            ->addExtras('id',$this->getMessageID())
            ->addExtras('type',$this->event->get('type'))
          ];
        }


    }


    /**
     * @return IncomingMessage
     */
    protected function getInteractiveMessage()
    {
        if(isset($this->event->get('interactive')['button_reply'])||isset($this->event->get('interactive')['list_reply'])){
            $message=new IncomingMessage(
                isset($this->event->get('interactive')['button_reply'])?$this->event->get('interactive')['button_reply']['title']:$this->event->get('interactive')['list_reply']['title'],
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );

            $choice_id=isset($this->event->get('interactive')['button_reply']['id'])?$this->event->get('interactive')['button_reply']['id']:$this->event->get('interactive')['list_reply']['id'];
            $choice_text=isset($this->event->get('interactive')['button_reply']['title'])?$this->event->get('interactive')['button_reply']['title']:$this->event->get('interactive')['list_reply']['title'];
            $message->addExtras('choice_id',$choice_id);
            $message->addExtras('choice_text',$choice_text);
        }
        else{
            $message=new IncomingMessage(
                '',
                $this->getMessageSender(),
                $this->getMessageRecipient(),
                $this->getMessagePayload()
            );
        }

        return $message;
    }

     /**
     * @return array|Collection|null
     */
    protected function getMessagePayload(){
        return isset($this->payload->get('entry')[0]['changes'][0]['value'])?$this->payload->get('entry')[0]['changes'][0]['value']:null;
    }

    /**
     * @return string|null
     */
    protected function getMessageSender()
    {
        if (isset($this->payload->get('entry')[0]['changes'][0]['value']['metadata']['display_phone_number'])) {
            return $this->payload->get('entry')[0]['changes'][0]['value']['metadata']['display_phone_number'];
        } else{
            return null;
        }
    }

    /**
     * @return string|null
     */
    protected function getMessageRecipient()
    {
        if (!is_null($this->event->get('from'))) {
            return $this->event->get('from');
        } else{
            return null;
        }
    }

      /**
     * @return string|null
     */
    protected function getMessageID()
    {
        if (!is_null($this->event->get('id'))) {
            return $this->event->get('id');
        } else{
            return null;
        }
    }

    /**
     * Retrieve User information.
     * @param IncomingMessage $matchingMessage
     * @return UserInterface
     */
    public function getUser(IncomingMessage $matchingMessage)
    {
        $contact = Collection::make($matchingMessage->getPayload()['contacts'][0]);
        return new User(
            $contact->get('wa_id'),
            $contact->get('profile')['name'],
            null,
            $contact->get('wa_id'),
            $contact
        );
    }

    /**
     * @param IncomingMessage $message
     * @return \BotMan\BotMan\Messages\Incoming\Answer
     */
    public function getConversationAnswer(IncomingMessage $message)
    {
     return Answer::create($message->getText())->setMessage($message);
    }


      /**
     * Convert a Question object into a valid Whatsapp
     * quick reply response object.
     *
     * @param  Question  $question
     * @return array
     */
    private function convertQuestion(Question $question)
    {
        return [
            'type' =>'text',
            'text' => [
                'body' => $question->getText(),
            ]
        ];
    }


        /**
     * @param string|\BotMan\BotMan\Messages\Outgoing\Question $message
     * @param IncomingMessage $matchingMessage
     * @param array $additionalParameters
     * @return $this
     */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        $recipient = $matchingMessage->getRecipient() === '' ? $matchingMessage->getSender() : $matchingMessage->getRecipient();

        $parameters = array_merge_recursive([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipient,
        ], $additionalParameters);

        if ($message instanceof Question) {
            $parameters = array_replace_recursive($parameters,$this->convertQuestion($message));
        }
        elseif (is_object($message) && in_array(get_class($message), $this->templates)) {
            $parameters = array_replace_recursive($parameters,$message->toArray());
        }
        elseif ($message instanceof OutgoingMessage) {
            $attachment = $message->getAttachment();
            if (!is_null($attachment) && in_array(get_class($attachment), $this->supportedAttachments)) {
                $attachmentType = strtolower(basename(str_replace('\\', '/', get_class($attachment))));
                if($attachmentType=='file'){
                    $attachmentType='document';
                }
                $array=[
                    'type' => $attachmentType,
                     $attachmentType => [
                         'link'=>$attachment->getUrl(),
                    ],
                ];
              $parameters = array_replace_recursive($parameters,$array);
            } else {
                $parameters['text'] = [
                    'body' => $message->getText(),
                ];
                $parameters['type'] = 'text';
            }
        }
        else {
            $parameters['text'] = [
                'body' => $message->getText(),
            ];
            $parameters['type'] = 'text';
        }
        return $parameters;
    }


    /**
     * @param mixed $payload
     * @return Response
     */
    public function sendPayload($payload)
    {
        if ($this->config->get('throw_http_exceptions')) {
            return $this->postWithExceptionHandling($this->buildApiUrl($this->endpoint), [], $payload, $this->buildAuthHeader(), true);
        }

        return $this->http->post($this->buildApiUrl($this->endpoint), [], $payload, $this->buildAuthHeader(), true);
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->config->get('url'));
    }

    /**
     * Low-level method to perform driver specific API requests.
     *
     * @param string $endpoint
     * @param array $parameters
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $matchingMessage
     * @return void
     */
    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {
        $parameters = array_replace_recursive([
            'to' => $matchingMessage->getRecipient(),
        ], $parameters);

        if ($this->config->get('throw_http_exceptions')) {
            return $this->postWithExceptionHandling($this->buildApiUrl($endpoint), [], $parameters, $this->buildAuthHeader());
        }

        return $this->http->post($this->buildApiUrl($endpoint), [], $parameters, $this->buildAuthHeader());
    }

    protected function buildApiUrl($endpoint)
    {
        return $this->config->get('url') . '/' . $this->config->get('version') . '/' . $this->config->get('phone_number_id') . '/' . $endpoint;
    }

    public function buildAuthHeader()
    {
        $token = $this->config->get('token');
        return [
            "Authorization: Bearer " . $token,
            'Content-Type: application/json',
            'Accept: application/json'
        ];
    }

    /**
     * @param $url
     * @param array $urlParameters
     * @param array $postParameters
     * @param array $headers
     * @param bool $asJSON
     * @param int $retryCount
     * @return Response
     */
    // * @throws WhatsappConnectionException

    private function postWithExceptionHandling(
        $url,
        array $urlParameters = [],
        array $postParameters = [],
        array $headers = [],
        $asJSON = false,
        int $retryCount = 0
    ) {
        $response = $this->http->post($url, $urlParameters, $postParameters, $headers, $asJSON);
        $responseData = json_decode($response->getContent(), true);

        if ($response->isSuccessful()) {
            return $responseData;
        }

        $responseData['error']['code'] = $responseData['error']['code'] ?? 'No description from Vendor';
        $responseData['error']['message'] = $responseData['error']['message'] ?? 'No error code from Vendor';
        $responseData['error']['type'] = $responseData['error']['type'] ?? 'No type from Vendor';

        $message = "Status Code: {$response->getStatusCode()}\n".
            "Description: ".print_r($responseData['error']['message'], true)."\n".
            "Error Code: ".print_r($responseData['error']['code'], true)."\n".
            "Error Type: ".print_r($responseData['error']['type'], true)."\n".
            "URL: $url\n".
            "URL Parameters: ".print_r($urlParameters, true)."\n".
            "Post Parameters: ".print_r($postParameters, true)."\n".
            "Headers: ". print_r($headers, true)."\n";

        throw new WhatsappException($message);
    }
}
