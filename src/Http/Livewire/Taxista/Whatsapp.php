<?php

namespace Raydelpq\WhatsappApi\Http\Livewire\Taxista;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Raydelpq\WhatsappApi\Models\Whatsapp as ModelsWhatsapp;
use Raydelpq\WhatsappApi\Jobs\ApiWhastappAdd;
use Raydelpq\WhatsappApi\Jobs\ApiWhastappDel; 

class Whatsapp extends Component
{
    public  $taxista = null;
    public  $empleado = null;

    // Deterimina para quien se carga la plantilla, taxista o empleados
    public $para;

    // SI tiene permiso o no el taxista para trabajar sin fondo
    public $permiso;
    
    // SI esta en el grupo o no
    public $in_grupo;
    
    // Determina si el empleado esta iniciado
    public $isConected = false;

    // Codigo QR
    public $codeQR;


    public function render()
    {
        if($this->taxista != null)
            $this->initTaxista();

        if($this->empleado != null)
            $this->initEmpleado();
            


        $grupos = ModelsWhatsapp::all();
        return view('whatsapp-api::livewire.taxista.whatsapp',['grupos' => $grupos]);
    }

    public function entrar(){
        ApiWhastappAdd::dispatch($this->taxista,true);
    }

    public function sacar(){
        ApiWhastappDel::dispatch($this->taxista,true);
    }

    private function initTaxista(){
        $this->para = 'taxistas';
            if($this->taxista->permiso)
                $this->permiso = true;
            else
                $this->permiso = false;

            if($this->taxista->in_grupo)
                $this->in_grupo = true;
            else
                $this->in_grupo = false;
    }

    private function initEmpleado(){
        $this->para = 'empleados';
        $this->isOnline();
    }

    // Determina si esta en grupo o no
    public function is($w){
        $result = $this->taxista->whatsapps()->where('group_id',$w)->first();
        
        if($result == null)
            return false;

        return true;
    }

    // Agrgar o quitar de grupos
    public function change($grupo){
        $grupo = ModelsWhatsapp::find($grupo);
        
        if( !$this->is($grupo->group_id) ){
            //dd("no existe");
            $this->taxista->whatsapps()->attach($grupo->id);
        }else{
            //dd("Existe");
            $this->taxista->whatsapps()->detach($grupo->id);
        }

        $this->emit('message','Cambio Realizado','success');
    }

    // Cambiar pwewrmisos a taxistas de credito
    public function changePermiso(){
        $this->permiso = !$this->permiso;
        $this->taxista->permiso = $this->permiso;
        $this->taxista->update();
        $this->emit('message','Cambio Realizado','success');
    }
  
}
