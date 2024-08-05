<?php

namespace Botman\Drivers\Whatsapp\Traits;

use Illuminate\Http\Request;

trait MatchesFlowProfile
{
    /**
    * @param Request $request
    * @return bool
    */
    protected function matchesFlowProfile(Request $request)
    {
            $request_data=$request->all();
            if(isset($request_data['encrypted_flow_data'])&&isset($request_data['encrypted_aes_key'])&&isset($request_data['initial_vector'])) {
                return true;
            }else{
                return false;
            }
    }
}
