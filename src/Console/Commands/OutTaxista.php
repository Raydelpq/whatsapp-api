<?php

namespace Raydelpq\WhatsappApi\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Taxista;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Raydelpq\WhatsappApi\Http\Controllers\WhatsAppController;

class OutTaxista extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'out:taxista';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saca a todos los taxistas sin fondo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (config('whatsappapi.API_WA')) {
            $min = config('whatsappapi.MIN_WA');
            

            try{
                $taxistas = Taxista::where('fondo','<=',$min)->where('permiso',false)->get();
                
                foreach($taxistas as $taxista){
                    $numero = WhatsAppController::getNumero($taxista->user->telefono);

                    foreach($taxista->whatsapps as $grupo){
                        $res = WhatsAppController::inGroup($grupo->group_id,$numero);
                        $content = $res->getOriginalContent();
                        $inGrupo = $content['status'];

                        if($inGrupo){
                            WhatsAppController::delGroup($grupo->group_id,$numero);
                            $mensaje = Lang::get('whatsappapi.expulsar',['name' => $taxista->user->name,'fondo' => $taxista->fondo]);
                            WhatsAppController::sendMessage($numero,$mensaje);
                            $this->info("ID: {$taxista->id} - {$taxista->user->name} {$taxista->user->apellidos} - {$numero}");
                        }
                        
                    }

                }

            }catch(Exception $error){

            }
        }else{
            $this->info("La Api de Whatsapp no está habilitada");
        }

        $this->info("Operacion Completada");
        return Command::SUCCESS;
    }
}
