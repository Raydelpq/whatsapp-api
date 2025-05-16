<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use Raydelpq\WhatsappApi\Http\Controllers\ConfigController;
use Raydelpq\WhatsappApi\Http\Controllers\WhatsAppController;

Route::post('/api/webhook/serviweb/evolution',  [WebhookController::class, 'handle'])->name('whatsapp_webhook');
Route::post('/api/whatsapp',  [WhatsAppController::class, 'loadTaxistas'])->name('whatsapp_load');
Route::post('/api/whatsapp/registro',  [WhatsAppController::class, 'registroTaxista'])->name('whatsapp_registro');
Route::post('/api/expulsar',  [WhatsAppController::class, 'expulsarTaxistas'])->name('whatsapp_expulsar');
Route::post('/api/getFondo',  [WhatsAppController::class, 'getFondo'])->name('whatsapp_getFondo');
Route::post('/api/update-api-wa', [ConfigController::class, 'updateApiWa']);

