<?php

use Illuminate\Support\Facades\Route;
use Raydelpq\WhatsappApi\Http\Controllers\WhatsAppController;

Route::post('/api/whatsapp',  [WhatsAppController::class, 'loadTaxistas'])->name('whatsapp_load');
Route::post('/api/expulsar',  [WhatsAppController::class, 'expulsarTaxistas'])->name('whatsapp_expulsar');
Route::post('/api/getFondo',  [WhatsAppController::class, 'getFondo'])->name('whatsapp_getFondo');
