<?php

namespace Raydelpq\WhatsappApi;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Raydelpq\WhatsappApi\Http\Livewire\Taxista\Whatsapp;
use Raydelpq\WhatsappApi\Http\Livewire\GetQr;

class WhatsappApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        $this->loadMigrationsFrom(__DIR__.'/../migrates/2024_xx_xx_xxxxxx_create_whatsapps_table.php');

        $this->publishes([
            __DIR__.'/../config/whatsappapi.php' => config_path('whatsappapi.php'),
            __DIR__.'/../lang' => $this->app->langPath('./'),
        ],'whatsappapi');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Raydelpq\WhatsappApi\Console\Commands\OutTaxista::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'whatsapp-api');
        Livewire::component('taxista.whatsapp', Whatsapp::class);
        Livewire::component('get-qr', GetQr::class);
    }

    public function register()
    {
        // Register any application services.
    }
}
