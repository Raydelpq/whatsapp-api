<?php

namespace Raydelpq\WhatsappApi\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        // Obtener el resultado del comando
        $this->resultado = Artisan::output();
        $this->emit('message','Comando Ejecutado','success');
    }

    public function restar0(){
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
    }

    public function restar(){

        if (!$this->processName) {
            $this->emit('message', 'Por favor, proporciona el nombre del proceso', 'warning');
            return;
        }

        // Construye la ruta completa al archivo reset.sh
        // __DIR__ se refiere al directorio donde se encuentra este archivo (por ejemplo, src/)
        $scriptPath = realpath(__DIR__ . '/../../../scripts/reset.sh');

        // Asegúrate de que la ruta exista
        if (!$scriptPath || !file_exists($scriptPath)) {
            $this->emit('message', 'No se encontró el script reset.sh', 'warning');
            return;
        }

        // Prepara el comando, pasando el nombre del proceso como parámetro
        $command = escapeshellcmd("{$scriptPath} {$this->processName}");

        // Ejecuta el comando y captura la salida y el código de retorno
        $output = [];
        exec($command, $output, $exitCode);

        // Emite mensaje según el resultado
        if ($exitCode === 0) {
            $this->emit('message', 'Proceso reiniciado exitosamente', 'success');
        } else {
            $this->emit('message', 'Error al reiniciar el proceso', 'warning');
        }
    }
}
