<?php

namespace Raydelpq\WhatsappApi\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Raydelpq\WhatsappApi\Http\Controllers\WhatsAppController;

class ApiWhastappDel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $taxista;
    private $modo;
    private $mensaje;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($taxista, $modo = false, $mensaje = null)
    {
        $this->taxista = $taxista;
        $this->modo = $modo;
        $this->mensaje = $mensaje;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->taxista->permiso)
            if (config('whatsappapi.API_WA')) {
                $min = config('whatsappapi.MIN_WA');

                $numero = WhatsAppController::getNumero($this->taxista->user->telefono);

                if ($this->modo == true) {
                    $this->sacar($numero);
                } else
                if ($this->taxista->fondo <= $min) {
                    $this->sacar($numero);
                }
            }
    }

    private function sacar($numero)
    {
        $inGrupo = true;
        foreach ($this->taxista->whatsapps as $grupo) {
            $res = WhatsAppController::inGroup($grupo->group_id,$numero);
            $content = $res->getOriginalContent();
            $inGrupo = $content['status'];
            WhatsAppController::delGroup($grupo->group_id,$numero);
        }

        if($inGrupo){
            if($this->mensaje == null)
                $this->mensaje = Lang::get('whatsappapi.expulsar',['name' => $this->taxista->user->name,'fondo' => $this->taxista->fondo]);

            if(!$this->modo)
                WhatsAppController::sendMessage($numero,$this->mensaje);
        }
    } // end Sacar
}
