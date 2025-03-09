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
        // Asegúrate de tener el valor de $name
        if (!$this->processName) {
            return;
        }

        // Comando a ejecutar
        $command = "pm2 start {$this->processName} --watch";

        // Crear y ejecutar el proceso
        $process = Process::fromShellCommandline($command);
        $process->run();

        // Manejar errores en la ejecución
        if (!$process->isSuccessful()) {
            //throw new ProcessFailedException($process);
            $this->emit('message','Error al reiniciar el proceso','warning');
        }

        // Output en caso de éxito
        $this->emit('message','Comando Ejecutado','success');
        //$this->emit('resetSuccessful', $process->getOutput());
    }
}
