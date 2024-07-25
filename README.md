## BotMan WhatsApp Business Cloud API Driver

BotMan driver to connect WhatsApp Business Cloud API with [BotMan](https://github.com/botman/botman)


## WhatsApp Business Cloud API

Please read the official documentation at [Meta for Developer](https://developers.facebook.com/docs/whatsapp/cloud-api)

## Installation & Setup
First you need to pull in the Whatsapp Driver:

    composer require mohapinkepane/driver-whatsapp-cloud

Next you need to add to your .env file the following entries:

    WHATSAPP_TOKEN=your-whatsapp-token
    WHATSAPP_VERIFICATION=your-whatsapp-verification-token
    WHATSAPP_APP_SECRET=your-whatsapp-app-secret
    WHATSAPP_PHONE_NUMBER_ID=your-whatsapp-phone-number-id

This driver requires a valid and secure URL in order to set up webhooks and receive events and information from the chat users. This means your application should be accessible through an HTTPS URL.

>[ngrok](https://ngrok.com/) is a great tool to create such a public HTTPS URL for your local application. If you use Laravel Valet, you can create it with "valet share".If you use Laravel Herd, you can create it with "herd share" as well.[Serveo](https://serveo.net/) is also an excellent and headache free alternative - it is also entirely free.

To connect BotMan with WhatsApp Business, you first need to follow the official quick [start guide](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started) to create your WhatsApp Business application and retrieve an access token as well as an app secret. Switch both of them with the dummy values in your BotMan .env file.

After that you can setup the webhook, which connects the Whatsapp application with your BotMan application. This is covered in the above mentioned Quick Start Guide.

## Configuring the package
You can publish the config file with:

    php artisan vendor:publish --provider="Botman\Drivers\Whatsapp\Providers\WhatsappServiceProvider"

This is the contents of the file that will be published at config/botman/whatsapp.php:

    <?php

    return [
        /*
        |--------------------------------------------------------------------------
        | Whatsapp url
        |--------------------------------------------------------------------------
        | Your whatsapp cloud api base url
        */
        'url' => env('WHATSAPP_PARTNER', 'https://graph.facebook.com'),

        /*
        |--------------------------------------------------------------------------
        | Whatsapp Token
        |--------------------------------------------------------------------------
        | Your Whatsapp token  you received after creating
        | the application on Whatsapp(Facebook Portal).
        */
        'token' => env('WHATSAPP_TOKEN'),


        /*
        |--------------------------------------------------------------------------
        | Whatsapp App Secret
        |--------------------------------------------------------------------------
        |
        | Your Whatsapp application secret, which is used to verify
        | incoming requests from Whatsapp.
        |
        */
        'app_secret' => env('WHATSAPP_APP_SECRET'),

        /*
        |--------------------------------------------------------------------------
        | Whatsapp Verification
        |--------------------------------------------------------------------------
        | Your Whatsapp verification token, used to validate the webhooks.
        */
        'verification' => env('WHATSAPP_VERIFICATION'),

        /*
        |--------------------------------------------------------------------------
        | Whatsapp Phone Number ID
        |--------------------------------------------------------------------------
        | Your Whatsapp phone_number_id
        */
        'phone_number_id'=>env('WHATSAPP_PHONE_NUMBER_ID'),


        /*
        |--------------------------------------------------------------------------
        | Whatsapp Cloud API Version
        |--------------------------------------------------------------------------
        */
        'version' => 'v20.0',

        /*
        |--------------------------------------------------------------------------
        | throw_http_exceptions
        |--------------------------------------------------------------------------
        | Do you want the driver to throw custom(driver) exceptions or the default exceptions
        */
        'throw_http_exceptions' => true,
    ];


## Supported Features

- [x] Text Message
- [x] Contact Message
- [x] Location Message
- [x] Location Request Message
- [x] Reaction Message
- [x] Image Attachment
- [x] Document Attachment
- [x] Location Attachment
- [x] Video Attachment
- [x] Audio Attachment
- [x] Sticker Attachment
- [x] Call To Action
- [x] Interactive Message
    - [x] List
    - [x] Button

<!-- ### TODO:
- [ ] Template Message
- [ ] Interactive Message
    - [ ] Product
    - [ ] Product List -->

## Sending Whatsapp Templates

>Facebook is still experimenting a lot with its Whatsapp features. This is why some of them behave differently on certain platforms.In general it is easy to say that all of them work within the native Whatsapp App on your phones. But e.g. the List Template is not working inside the Whatsapp Desktop App.

### Text

You can send text as follows

    $bot->reply(
        TextTemplate::create('Please visit https://youtu.be/hpltvTEiRrY to inspire your day!')
        ->previewUrl(true)//Allows whatsapp to show the preview of the url(video in this case)
     );

OR more powerfully in a conversation like this

    $this->ask('Hello! What is your firstname?', function(Answer $answer) {
        $this->firstname = $answer->getText();
        $this->say(
                TextTemplate::create('Nice to meet you '.$this->firstname)
                ->contextMessageId($answer->getMessage()->getExtras('id'))
            );
    });

This is to add message context to achieve something like this

![Message Context](/assets/images/message-context.png)


### Media

You can still attach media to messages like the docs say [here](https://botman.io/2.0/sending#attachments)
,but this will limit you to images,videos,audio and files.

ALTERNATIVELY there is a Media Temblate (Different from facebook driver's) 
it supports video,image,document,sticker and audio.The cool thing about it is that you can add caption and filename where applicable.You can also chain the contextMessageId() method to provide context.

It can be used in two ways

    1. MediaTemplate::create('media-type-here')
        ->url('media-url-here')

    2. MediaTemplate::create('media-type-here')
        ->id('media-id-here')//Whatsapp media id

Examples below

    $bot->reply(
        MediaTemplate::create('image')
    ->url('https://images.pexels.com/photos/1266810/pexels-photo-1266810.jpeg')
    ->caption('This is a cool image!')
    );

    $bot->reply(
        MediaTemplate::create('audio')
    ->url('https://samplelib.com/lib/preview/mp3/sample-15s.mp3')
    );

    $bot->reply(
        MediaTemplate::create('document')
    ->url('https://pdfobject.com/pdf/sample.pdf')
    ->caption('This is a cool Document!')
    );

    $bot->reply(
        MediaTemplate::create('sticker')
    ->url('https://stickermaker.s3.eu-west-1.amazonaws.com/storage/uploads/sticker-pack/meme-pack-3/ sticker_18.webp')
    );

    $bot->reply(
        MediaTemplate::create('video')
    ->url('https://sample-videos.com/video321/mp4/480/big_buck_bunny_480p_10mb.mp4')
    ->caption('This is a cool Video!')
    );



### List

You can send a list message (in a conversation) as follows

     $this->ask(ListTemplate::create("Here is your  list of current Ticketbox listings",
            'Ticketbox listings',
            'The best place to buy tickets online'
            ,'View listings')
            ->addSection(ElementSectionList::create('Events',[
                        ElementSectionListRow::create(
                        1,//List item id
                        'Selemo Sa Basotho'////List item title
                        )
                        ->description('In 2024, we commemorate 200 years since the Basotho nation arrived..')
                        ,
                        ElementSectionListRow::create(2,'Winterfest')
                        ->description('vibrant cultural performances, music, food and a colossal Bonfire '),
                ])
            )
            ->addSection(ElementSectionList::create('Vouchers',[
                    ElementSectionListRow::create(3,'Kobo ea Seanamarena'),
                ])
     ),function(Answer $answer) {
        $payload = $answer->getMessage()->getPayload();//Get Payload
        $choice_id=$answer->getMessage()->getExtras('choice_id');//You can the select choice ID like this
        $choice_text=$answer->getMessage()->getExtras('choice_text');
        $choice=$answer->getText();
        $this->say(
                TextTemplate::create('Nice.You choose '.$choice)
                ->contextMessageId($answer->getMessage()->getExtras('id'))
            );
     });


![List](/assets/images/list.png)


### Reply Button

You can send a reply button message (in a conversation) as follows

    $this->ask(ButtonTemplate::create('How do you like BotMan so far?')
            ->addFooter('Powered by BotMan.io')
            ->addHeader(
                    ElementButtonHeader::create('image',[
                        'link'=>"https://botman.io/img/botman.png",
                    ])
            )
            ->addButtons([
            ElementButton::create(1,'Quite good'),
            ElementButton::create(2,'Love it')
        ]),function(Answer $answer) {
            $payload = $answer->getMessage()->getPayload();//Get Payload
            $choice_id=$answer->getMessage()->getExtras('choice_id');//You can the get choice ID like this
            $choice_text=$answer->getMessage()->getExtras('choice_text');
            $choice=$answer->getText();
            $this->say(
                    TextTemplate::create('Nice.You choose '.$choice)
                    ->contextMessageId($answer->getMessage()->getExtras('id'))
                );
        });

The header can be of type text,image,video or document


![Reply Buttons](/assets/images/reply-buttons.png)

### Call To Action

You can send a call to action as follows

    $bot->reply(CallToActionTemplate::create(
        'Do you want to know more about BotMan?',//Call to action body
        "Visit us", //Call to action button text
        "https://botman.io"//Call to action url
    )
    ->addFooter('Powered by BotMan.io')
    ->addHeader(
        ElementButtonHeader::create('image',[
            'link'=>"https://botman.io/img/botman.png",
        ])
    ));

The header can be of type text,image,video or document

![Call To Action](/assets/images/call-to-action.png)


### Reaction 

You can react to messages as follows

    $this->ask('Hello! Do you read me?', function(Answer $answer) {
        $message_id=$answer->getMessage()->getExtras('id');
        $this->say(
            ReactionTemplate::create($message_id,'😀')
        );
    });


### Contacts

You can send contacts as follows


    $addresses = [
        Address::create("Menlo Park", "United States"),
        // Address::create("Menlo Park", "United States", "us", "CA", "1 Hacker Way", "HOME", "94025"),
        // Address::create("Menlo Park", "United States", "us", "CA", "200 Jefferson Dr", "WORK", "94025")
    ];

    $emails = [
        Email::create("test@whatsapp.com"),
        Email::create("test@fb.com", "WORK")
    ];

    $name = Name::create("John", "John Smith", "Smith");

    $org = Organization::create("WhatsApp","Manager");

    $phones = [
        Phone::create("+1 (940) 555-1234"),
        Phone::create("+1 (940) 555-1234", "HOME"),
        Phone::create("+1 (650) 555-1234", "WORK", "16505551234")
    ];

    $urls = [
        URL::create("https://www.google.com"),
        URL::create("https://www.facebook.com", "WORK")
    ];

    $person = Contact::create($addresses, "2012-08-18", $emails, $name, $org, $phones, $urls);

    $bot->reply(
        ContactsTemplate::create([
            $person
        ])
    );


### Location

You can send location as follows

    $bot->reply(
        LocationTemplate::create(-122.425332, 37.758056, "Facebook HQ", "1 Hacker Way, Menlo Park, CA 94025")
    );

### Location Request 

You can request location from clients as follows

    $this->ask(LocationRequestTemplate::create('Please share your location'), function(Answer $answer) {
            $payload = $answer->getMessage()->getPayload();
            \Log::info('PAYLOAD'.\json_encode($payload));
            $this->say('Thanks!');
    });

## Mark seen

The markSeen() method takes a parameter of type IncomingMessage and can be use in serveral ways:

In receiving(recieved) Middleware

    public function received(IncomingMessage $message,$next, BotMan $bot)
        {
            if($bot->getDriver()->getName()=='Whatsapp'){
                $bot->markSeen($message);
            }
            return $next($message);
        }


In a coversation

    $this->ask('Hello! What is your firstname?', function(Answer $answer) {
        $this->bot->markSeen($answer->getMessage());
        $this->firstname = $answer->getText();
        $this->say('Nice to meet you '.$this->firstname);
    });


## Contributing
Please see [CONTRIBUTING](https://github.com/mohapinkepane/driver-whatsapp-cloud//blob/master/CONTRIBUTING.md) for details.


## Credits

- [irwan-runtuwene](https://github.com/irwan-runtuwene/driver-whatsapp)
- [rivaisali](https://github.com/rivaisali/driver-whatsapp)


## Security Vulnerabilities

If you discover a security vulnerability within BotMan, please send an e-mail to Marcel Pociot at m.pociot@gmail.com. All security vulnerabilities will be promptly addressed.

## License

BotMan is free software distributed under the terms of the [MIT license](https://github.com/mohapinkepane/driver-whatsapp-cloud//blob/README.md).
