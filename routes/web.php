<?php

use Illuminate\Support\Facades\Route;
use Raydelpq\WhatsappApi\Http\Livewire\Link;
use Raydelpq\WhatsappApi\Http\Livewire\GetQr;

Route::get('/getqr',GetQr::class)->name("getqr");
Route::get('/apiwhatsapp',Link::class)->name("config_api");
