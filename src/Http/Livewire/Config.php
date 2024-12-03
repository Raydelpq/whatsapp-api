<?php

namespace Raydelpq\WhatsappApi\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
<<<<<<< Updated upstream

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
=======
use Illuminate\Support\Facades\Auth;

class Config extends Component
{
    public $resultado = null;
    public $title;
    public $processName;
    private $url = "https://back.serviweb.nat.cu/api/";

    public function mount() {
        $this->processName = config('whatsappapi.procesoName');
    }

    public function render()
    {  
        $this->title = "Configuración de WhatsApp";
        return view('whatsapp-api::livewire.config')->layoutData(['titulo' => $this->title]);
    }

    public function sacar(){
        // Ejecutar el comando Artisan
        Artisan::call('out:taxista');
>>>>>>> Stashed changes
        // Obtener el resultado del comando
        $this->resultado = Artisan::output();
        $this->emit('message','Comando Ejecutado','success');
    }

    public function restar(){
<<<<<<< Updated upstream
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
=======
        $url = $this->url."pm2/start/{$this->processName}";
        //dd($url);
        $response = Http::withHeaders(
            [ 'Content-Type' => 'application/json' ]
        )->get($url);

        // Maneja la respuesta
        if ($response->successful()) {
           
            $this->emit('message','Comando Ejecutado','success');
        } else {
            $this->emit('message','Error al reiniciar el proceso','warning');
        }
        //$this->resultado = $response->body();
>>>>>>> Stashed changes
    }
}
