<?php

namespace Raydelpq\WhatsappApi\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Lang;
use Raydelpq\WhatsappApi\Http\Controllers\WhatsAppController;

class ApiWhatsappFondo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $taxista;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($taxista)
    {
        $this->taxista = $taxista;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $numero = WhatsAppController::getNumero($this->taxista->user->telefono);



        if (config('whatsappapi.API_WA')) {
            $min = config('whatsappapi.MIN_WA');

            if ($this->taxista->fondo >= $min) {
                $inGrupo = null;
                foreach ($this->taxista->whatsapps as $grupo) {

                    if($inGrupo == null){
                        $res = WhatsAppController::inGroup($grupo->group_id,$numero);
                        $content = $res->getOriginalContent();
                        $inGrupo = $content['status'];
                    }

                    WhatsAppController::addGroup($grupo->group_id, $numero);
                }

                if (!$inGrupo) {
                    $mensaje = Lang::get('whatsappapi.recarga', ['name' => $this->taxista->user->name]);
                    WhatsAppController::sendMessage($numero, $mensaje);
                }
            } else
            if ($this->taxista->fondo < $min) {
                $inGrupo = null;
                if (!$this->taxista->permiso) {
                    foreach ($this->taxista->whatsapps as $grupo){

                        if($inGrupo == null){
                            $res = WhatsAppController::inGroup($grupo->group_id,$numero);
                            $content = $res->getOriginalContent();
                            $inGrupo = $content['status'];
                        }

                        WhatsAppController::delGroup($grupo->group_id, $numero);
                    }

                    if ($inGrupo) {
                        $mensaje = Lang::get('whatsappapi.retirar', ['name' => $this->taxista->user->name, 'fondo' => $this->taxista->fondo]);
                        WhatsAppController::sendMessage($numero, $mensaje);
                    }
                }
            }
        }
    } // END Handle

}
