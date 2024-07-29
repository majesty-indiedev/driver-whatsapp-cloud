<?php

namespace Botman\Drivers\Whatsapp\Providers;

use Illuminate\Support\ServiceProvider;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Whatsapp\WhatsappDriver;
use BotMan\Studio\Providers\StudioServiceProvider;
use BotMan\Drivers\Whatsapp\Commands\AddCoversationalComponents;

class WhatsappServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->isRunningInBotManStudio()) {
            $this->loadDrivers();

            $this->publishes([
                __DIR__ . '/../../stubs/whatsapp.php' => config_path('botman/whatsapp.php'),
            ]);

            $this->mergeConfigFrom(__DIR__ . '/../../stubs/whatsapp.php', 'botman.whatsapp');

            if ($this->app->runningInConsole()) {
                $this->commands([
                    AddCoversationalComponents::class,
                ]);
            }
        }
    }

    protected function loadDrivers()
    {
        DriverManager::loadDriver(WhatsappDriver::class);
    }

    protected function isRunningInBotManStudio()
    {
        return class_exists(StudioServiceProvider::class);
    }
}
