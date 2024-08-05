<?php

namespace Botman\Drivers\Whatsapp\Traits;

use Illuminate\Http\Request;

trait ValidatesFlowSignature
{
    /**
    * @param Request $request
    * @return bool
    */
    protected function validatesSignature(Request $request)
    {
        $app_secret=config('botman.whatsapp.app_secret');

        //Check if App secret is empty
        if(empty($app_secret)) {
          throw new \Exception('App secret is empty. Please check your env variable "WHATSAPP_APP_SECRET".');
        }

        $signature = $request->headers->get('X_HUB_SIGNATURE_256','');
        $content=$request->getContent();

        return hash_equals(
            $signature,
            'sha256=' . hash_hmac('sha256', $content,$app_secret)
        );
    }
}
