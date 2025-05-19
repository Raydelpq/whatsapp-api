<?php

namespace Raydelpq\WhatsappApi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{

    public function QRCODE_UPDATED(Request $request)
    {
          $data = $request->all();

        if ($data) {
            Log::info("🆕 QR actualizado: $data");
        } else {
            Log::warning("QR recibido pero vacío");
        }
    }

    public function SEND_MESSAGE(Request $request)
    {
        $data = $request->all();

        if ($data) {
            Log::info("🆕 Mensaje actualizado: $data");
        } else {
            Log::warning("Mensaje recibido pero vacío");
        }
    }
}
