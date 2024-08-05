<?php

namespace BotMan\Drivers\Whatsapp\Commands;

use Illuminate\Console\Command;
use phpseclib3\Crypt\RSA;
use phpseclib3\Exception\NoKeyLoadedException;

class GenerateKeyPair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:whatsapp:generate:keypair {passphrase}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a public and private key pair and log them to the console.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $passphrase = $this->argument('passphrase');

        if (!$passphrase) {
            $this->error('Passphrase is empty. Please include passphrase argument to generate the keys like: php artisan botman:whatsapp:generate:keypair {passphrase}');
            exit;
        }

        try {
            $rsa = RSA::createKey(2048);
            $privateKey = $rsa->withPassword($passphrase)->toString('PKCS1');
            $publicKey = $rsa->getPublicKey()->toString('PKCS8');

            $this->warn("Successfully created your public private key pair. Please copy the values below into your .env file");
            $this->info('');
            $this->warn("************* COPY PASSPHRASE,PUBLIC KEY & PRIVATE KEY BELOW TO .env FILE *************");
            $this->info('');
            $this->info("WHATSAPP_KEYS_PASSPHRASE=\"{$passphrase}\"");
            $this->info("WHATSAPP_PUBLIC_KEY=\"{$publicKey}\"");
            $this->info("WHATSAPP_PRIVATE_KEY=\"{$privateKey}\"");
            $this->info('');
            $this->warn("************* COPY PASSPHRASE,PUBLIC KEY & PRIVATE KEY ABOVE TO .env FILE *************");
            $this->info('');
        } catch (NoKeyLoadedException $e) {
            $this->error('Error while creating public private key pair: ' . $e->getMessage());
        }
    }
}
