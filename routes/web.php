<?php

use Illuminate\Support\Facades\Route;
use Raydelpq\WhatsappApi\Http\Livewire\Config;
use Raydelpq\WhatsappApi\Http\Livewire\GetQr;

Route::get('/getqr',GetQr::class)->name("getqr");

Route::group(['middleware' => 'web'], function () {
    Route::group(['middleware' => ['auth']],function(){
        Route::get('/whatsapp_config',Config::class)->name("config_api");
    });
});

