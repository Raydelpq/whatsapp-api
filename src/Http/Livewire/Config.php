<?php

namespace Raydelpq\WhatsappApi\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class Config extends Component
{
    public $resultado;

    public function render()
    {

        return view('whatsapp-api::livewire.config')->layoutData(['titulo' => "Configuración de WhatsApp"]);
    }

    public function sacar(){

        // Ejecutar el comando Artisan
        Artisan::call('tu:comando');
        // Obtener el resultado del comando
        $this->resultado = Artisan::output();
        $this->emit('message','Comando Ejecutado','success');
    }

    public function restar(){
        $url = "https://back.serviweb.nat.cu/api/";
        $processName = config('whatsappapi.procesoName');;
        $response = Http::withHeaders(
            [ 'Content-Type' => 'application/json' ]
        )->get("{$url}/pm2/start/{$processName}");

        // Maneja la respuesta
        if ($response->successful()) {
            return response()->json(['data' => $response->json()]);
        } else {
            return response()->json(['error' => 'Error al iniciar el proceso'], 500);
        }
    }
}
