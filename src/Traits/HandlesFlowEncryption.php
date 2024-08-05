<?php

namespace Botman\Drivers\Whatsapp\Traits;

use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
use Illuminate\Http\Request;

trait HandlesFlowEncryption
{
     /**
     * @var string
     */
     protected $aesKeyBuffer;

    /**
    * @var string
    */
     protected $initialVectorBuffer;


    /**
     * @param Request $request
     * @return array
    */
    protected function decryptRequest(Request $request)
    {

        $privateKey = config('botman.whatsapp.private_key');
        $passphrase=config('botman.whatsapp.passphrase');

        //Check if private key is empty
        if(empty($privateKey)) {
           throw new \Exception('Private key is empty. Please check your env variable "WHATSAPP_PRIVATE_KEY".');
        }

        //Check if passphrase is empty
        if(empty($passphrase)) {
            throw new \Exception('Passphrase is empty. Please check your env variable "WHATSAPP_KEYS_PASSPHRASE".');
         }

        $body=$request->json()->all();
        $encryptedAesKey = base64_decode($body['encrypted_aes_key']);
        $encryptedFlowData = base64_decode($body['encrypted_flow_data']);
        $initialVector = base64_decode($body['initial_vector']);

        // Decrypt the AES key created by the client
        $rsa = RSA::load($privateKey,$passphrase)
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256');

        $decryptedAesKey = $rsa->decrypt($encryptedAesKey);
        if (!$decryptedAesKey) {
            throw new \Exception('Decryption of AES key failed.');
        }

         // Decrypt the Flow data
        $aes = new AES('gcm');
        $aes->setKey($decryptedAesKey);
        $aes->setNonce($initialVector);
        $tagLength = 16;
        $encryptedFlowDataBody = substr($encryptedFlowData, 0, -$tagLength);
        $encryptedFlowDataTag = substr($encryptedFlowData, -$tagLength);
        $aes->setTag($encryptedFlowDataTag);

        $decrypted = $aes->decrypt($encryptedFlowDataBody);
        if (!$decrypted) {
            throw new \Exception('Decryption of flow data failed.');
        }

        $this->aesKeyBuffer=$decryptedAesKey;
        $this->initialVectorBuffer=$initialVector;

        return [
            'decryptedBody' => json_decode($decrypted, true),
            'aesKeyBuffer' => $decryptedAesKey,
            'initialVectorBuffer' => $initialVector,
        ];
    }

    /**
     * @param array  $response
     * @return string
    */
    protected function encryptResponse($response)
    {
          // Flip the initialization vector
        $flipped_iv = ~$this->initialVectorBuffer;

        // Encrypt the response data
        $cipher = openssl_encrypt(json_encode($response), 'aes-128-gcm', $this->aesKeyBuffer, OPENSSL_RAW_DATA, $flipped_iv, $tag);
        return base64_encode($cipher . $tag);
    }
}
