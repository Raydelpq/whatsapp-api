<?php

use Illuminate\Support\Facades\Route;
use Raydelpq\WhatsappApi\Http\Livewire\GetQr;

Route::get('/getqr',GetQr::class)->name("getqr");