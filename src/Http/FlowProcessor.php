<?php

namespace Botman\Drivers\Whatsapp\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Botman\Drivers\Whatsapp\Traits\ValidatesFlowToken;
use Botman\Drivers\Whatsapp\Traits\MatchesFlowProfile;
use Botman\Drivers\Whatsapp\Traits\HandlesFlowEncryption;
use Botman\Drivers\Whatsapp\Traits\ValidatesFlowSignature;

abstract class FlowProcessor
{
    use HandlesFlowEncryption,ValidatesFlowSignature,MatchesFlowProfile,ValidatesFlowToken;
    
    public function handleFlow(Request $request)
    {
    
        if(!$this->matchesFlowProfile($request)) {
            return response([],422);
        }

        $app_secret=config('botman.whatsapp.app_secret');
        $validSignature = empty($app_secret) || $this->validatesSignature($request);
        if(!$validSignature) {
            return response([],432);
        }

        // TODO: Uncomment this block and add your flow token validation logic.
        // If the flow token becomes invalid, return HTTP code 427 to disable the flow and show the message in `error_msg` to the user
        // Refer to the docs for details https://developers.facebook.com/docs/whatsapp/flows/reference/error-codes#endpoint_error_codes

        /*
        if (validatesFlowToken(decryptedBody.flow_token)) {
            const error_response = {
            error_msg: `The message is no longer available`,
            };
            return res
            .status(427)
            .send(
                encryptResponse(error_response, aesKeyBuffer, initialVectorBuffer)
            );
        }
        */

        $decrypted_data = $this->decryptRequest($request);

        $response =$this->getResponse($decrypted_data['decryptedBody']);

        $encrypted_response= $this->encryptResponse($response);
        return response($encrypted_response);
    }


   /**
   * @param array $decrypted_body
   * @return array
   */
   public function getResponse($decrypted_body)
    {
        $data = $decrypted_body['data'] ?? [];
        $action = $decrypted_body['action'] ?? null;

        if ($action === 'ping') {
            return $this->respondToPing();
        }
        if (!empty($data['error'])) {
            return $this->respondToError($data);
        }
       
        return $this->getNextScreen($decrypted_body);
    }

   /**
    * @param array $decrypted_body
    * @return array
    */
    abstract public function getNextScreen($decrypted_body);

   /**
   * @return array
   */
   public function respondToPing(){
        return ['data' => ['status' => 'active']];
   }

   /**
   * @param array $data 
   * @return array
   */
   public function respondToError($data){
        return ['data' => ['acknowledged' => true]];
    }

}
