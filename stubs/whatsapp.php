<?php

return [
    'url' => env('WHATSAPP_PARTNER', 'https://graph.facebook.com/'),
    'token' => env('WHATSAPP_TOKEN'),
    'throw_http_exceptions' => true,
    'phone_number_id'=>env('WHATSAPP_PHONE_NUMBER_ID',''),
    'version' => env('WHATSAPP_VERSION','v19.0'),
];