<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Raydelpq\WhatsappApi\Http\Controllers\WhatsAppController;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Evento recibido:', $request->all());

        $event = $request->input('event');
        $data = $request->input('data');

        switch ($event) {
            case 'onMessage':
                $this->handleIncomingMessage($data);
                break;

            case 'onQr':
                $this->handleQrUpdate($data);
                break;

            default:
                Log::warning("Evento no manejado: $event");
        }

        return response()->json(['status' => 'ok'], 200);
    }

    private function handleIncomingMessage(array $data)
    {
        $body = strtolower(trim($data['body'] ?? ''));
        $from = $data['from'] ?? null;
        $chatId = $data['chatId'] ?? null;

        Log::info("📩 Mensaje recibido de $from en $chatId: $body");

        // Validar si el mensaje es exactamente "fondo"
        if ($body === 'fondo') {
            $grupoPermitido = env('GRUPO_FONDO_ID');

            if ($chatId === $grupoPermitido) {
                Log::info("✅ Mensaje 'fondo' válido en el grupo autorizado. Ejecutando acción...");

                // Llama al método del otro controlador
                WhatsAppController::getFondo($from);
            } else {
                Log::info("❌ Mensaje 'fondo' recibido en un grupo NO autorizado: $chatId");
            }
        }
    }

    private function handleQrUpdate(array $data)
    {
        $qrCode = $data['qr'] ?? null;

        if ($qrCode) {
            Log::info("🆕 QR actualizado: $qrCode");
        } else {
            Log::warning("QR recibido pero vacío");
        }
    }
}
