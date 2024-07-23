## BotMan WhatsApp Business Cloud API Driver

BotMan driver to connect WhatsApp Business Cloud API with [BotMan](https://github.com/botman/botman)


## WhatsApp Business Cloud API

Please read the official documentation at [Meta for Developer](https://developers.facebook.com/docs/whatsapp/cloud-api)

## Installation
You can install the package via composer:

    composer require mohapinkepane/driver-whatsapp-cloud

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
        'phone_number_id'=>env('WHATSAPP_PHONE_NUMBER_ID',''),


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





## Contributing
Please see [CONTRIBUTING](https://github.com/mohapinkepane/driver-whatsapp-cloud//blob/master/CONTRIBUTING.md) for details.

## Feature List

- [x] Text Message

### TODO:
- [ ] Template Message
- [ ] Interactive Message
    - [ ] List
    - [ ] Button
    - [ ] Product
    - [ ] Product List
- [ ] Image Attachment
- [ ] Document Attachment
- [ ] Location Attachment
- [ ] Video Attachment

## Credits

- [irwan-runtuwene](https://github.com/irwan-runtuwene/driver-whatsapp)
- [rivaisali](https://github.com/rivaisali/driver-whatsapp)


## Security Vulnerabilities

If you discover a security vulnerability within BotMan, please send an e-mail to Marcel Pociot at m.pociot@gmail.com. All security vulnerabilities will be promptly addressed.

## License

BotMan is free software distributed under the terms of the MIT license.
