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
