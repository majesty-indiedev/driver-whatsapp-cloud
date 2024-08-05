<?php

namespace Botman\Drivers\Whatsapp\Traits;

use Illuminate\Http\Request;

trait ValidatesFlowToken
{
    /**
    * @param Request $request
    * @return bool
    */
    protected function validatesFlowToken(Request $request)
    {
       //TODO: Implement validateFlow() method in details.
       return true;
    }
}
