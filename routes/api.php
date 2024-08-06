<?php

use Illuminate\Support\Facades\Route;
use Raydelpq\WhatsappApi\Http\Controllers\WhatsAppController;

Route::post('/api/whatsapp',  [WhatsAppController::class, 'loadTaxistas'])->name('whatsapp_load');
