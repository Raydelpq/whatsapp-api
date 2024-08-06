# whatsapp-api
 Gestionar agencias de taxis desde whatsapp

# Install
 ``composer require raydelpq/whatsapp-api``

# Public Vendor
 ``php artisan vendor:publish --tag=whatsappapi``

# Ejecutar Migracion
 ``php artisan migrate``

# Agragar al Model Taxista
 public function whatsapps(){
    return $this->belongsToMany(\Raydelpq\WhatsappApi\Models\Whatsapp::class);
 }

 ## Dentro del Modelo agregar los jobs en las funciones
  ### addSaldo
   ``\Raydelpq\WhatsappApi\Jobs\ApiWhastappAdd::dispatch($this);``

   ### addSaldo
   ``\Raydelpq\WhatsappApi\Jobs\ApiWhastappDel::dispatch($this);``

# Dentro de App\Http\Livewire\Taxista\AgregarFonfo
 ## El el metodo procesar antes de los eventos
 ``\Raydelpq\WhatsappApi\Jobs\ApiWhatsappFondo::dispatch($this->taxista);``

# Provider
 ``Raydelpq\WhatsappApi\WhatsappApiServiceProvider::class``

# Jobs
 -``Raydelpq\WhatsappApi\Jobs\ApiWhastappAdd``
 -``Raydelpq\WhatsappApi\Jobs\ApiWhastappDel``
 -``Raydelpq\WhatsappApi\Jobs\ApiWhastappFondo``

# Componente de Livewire
 @if( Auth::user()->hasRole('Administrador') )
    <button :class=" tab == 5 ? 'bg-slate-300 dark:bg-slate-800' : 'bg-slate-900' " class="text-white px-4 py-2 -mr-1.5 border-l border-slate-900 dark:border-white rounded-tr border-t" x-on:click="tab=5;">Whatsapp</button>
 @endif

 @if( Auth::user()->hasRole('Administrador') )
    <div x-show="tab == 5" class="p-4 bg-slate-300 dark:bg-slate-800 rounded-b rounded-tr w-full">
        ``<livewire:taxista.whatsapp :taxista='$taxista'>``
    </div>
 @endif
 

# Mostrar QR
 Limpiar cache de Rutas ``php artisan route:cache``
 Ruta ``/getqr``