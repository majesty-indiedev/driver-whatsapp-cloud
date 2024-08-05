<?php

namespace BotMan\Drivers\Whatsapp\Commands;

use BotMan\BotMan\Http\Curl;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AddPublicKey extends Command
{

    protected $endpoint = 'whatsapp_business_encryption';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:whatsapp:add-public-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set whatsapp public key';

    /**
     * @var Curl
     */
    private $http;

    /** @var Collection */
    protected $config;

    /**
     * Create a new command instance.
     *
     * @param  Curl  $http
     */
    public function __construct(Curl $http)
    {
        parent::__construct();
        $this->http = $http;
        $this->config = Collection::make(config('botman.whatsapp'));
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $public_key=config('botman.whatsapp.public_key');
        if (!$public_key) {

            $this->error('Public key is empty.Please add public key to your BotMan Whatsapp config in whatsapp.php.');
            exit;
        }

        $payload=[
            'business_public_key' => $public_key,
        ];
        $response=$this->http->post($this->buildApiUrl($this->endpoint),$payload,[], $this->buildAuthHeader(), true);
        $responseObject = json_decode($response->getContent());


        if ($response->getStatusCode() == 200) {
            $this->info('Public key was set.');
        } else {
            $this->error('Something went wrong: '.$responseObject->error->message);
        }
    }


    public function buildAuthHeader()
    {
        $token = $this->config->get('token');
        return [
            "Authorization: Bearer " . $token,
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];
    }


    protected function buildApiUrl($endpoint)
    {
        return $this->config->get('url') . '/' . $this->config->get('version') . '/' . $this->config->get('phone_number_id') . '/' . $endpoint;
    }

}
