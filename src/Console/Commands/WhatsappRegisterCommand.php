<?php

namespace BotMan\Drivers\Whatsapp\Console\Commands;

use Illuminate\Console\Command;

class WhatsappRegisterCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'botman:whatsapp:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a new Whatsapp webhook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Coming soon...');
    }
}
