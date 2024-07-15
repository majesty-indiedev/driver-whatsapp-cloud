## BotMan WhatsApp Business Cloud API Driver

BotMan driver to connect WhatsApp Business Cloud API with [BotMan](https://github.com/botman/botman)


## WhatsApp Business Cloud API

Please read the official documentation at [Meta for Developer](https://developers.facebook.com/docs/whatsapp/cloud-api)

##Installation
You can install the package via composer:

    composer require mohapinkepane/driver-whatsapp-cloud

##Configuring the package
You can publish the config file with:

    php artisan vendor:publish --provider="Botman\Drivers\Whatsapp\Providers\WhatsappServiceProvider"

This is the contents of the file that will be published at config/botman/whatsapp.php:

    <?php

    return [
        'url' => env('WHATSAPP_PARTNER', 'https://graph.facebook.com/'),
        'token' => env('WHATSAPP_TOKEN'),
        'throw_http_exceptions' => true,
        'phone_number_id'=>env('WHATSAPP_PHONE_NUMBER_ID',''),
        'version' => env('WHATSAPP_VERSION','v19.0'),
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

#Credits

- [irwan-runtuwene](https://github.com/irwan-runtuwene/driver-whatsapp)
- [rivaisali](https://github.com/rivaisali/driver-whatsapp)


## Security Vulnerabilities

If you discover a security vulnerability within BotMan, please send an e-mail to Marcel Pociot at m.pociot@gmail.com. All security vulnerabilities will be promptly addressed.

## License

BotMan is free software distributed under the terms of the MIT license.