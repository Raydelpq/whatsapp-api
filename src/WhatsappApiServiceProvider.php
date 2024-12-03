<?php

namespace Raydelpq\WhatsappApi;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Raydelpq\WhatsappApi\Http\Livewire\GetQr;
use Raydelpq\WhatsappApi\Http\Livewire\Config;
use Raydelpq\WhatsappApi\Http\Livewire\Link;
use Raydelpq\WhatsappApi\Http\Livewire\Taxista\Whatsapp;

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

        //if ($this->app->runningInConsole()) {
            $this->commands([
                \Raydelpq\WhatsappApi\Console\Commands\OutTaxista::class,
            ]);
        //}

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'whatsapp-api');
        Livewire::component('taxista.whatsapp', Whatsapp::class);
        Livewire::component('get-qr', GetQr::class);
        Livewire::component('config', Config::class);
        Livewire::component('link', Link::class);
    }

    public function register()
    {
        // Register any application services.
    }
}
