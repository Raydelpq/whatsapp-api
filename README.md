# whatsapp-api
 Gestionar agencias de taxis desde whatsapp

# Install
 ```bash 
   composer require raydelpq/whatsapp-api
 ```

# Public Vendor
 ```bash
   php artisan vendor:publish --tag=whatsappapi
 ```

# Ejecutar Migracion
 ```bash
   php artisan migrate
 ```

# Agragar al Model Taxista
 ```bash
    public function whatsapps(){
        return $this->belongsToMany(\Raydelpq\WhatsappApi\Models\Whatsapp::class);
    }
 ```

 ## Dentro del Modelo agregar los jobs en las funciones
  ### addSaldo
   ```bash
    \Raydelpq\WhatsappApi\Jobs\ApiWhastappAdd::dispatch($this);
   ```

   ### delSaldo
   ```bash
      \Raydelpq\WhatsappApi\Jobs\ApiWhastappDel::dispatch($this);
   ```

# Dentro de App\Http\Livewire\Taxista\AgregarFonfo
 ## El el metodo procesar antes de los eventos
 ```bash
    \Raydelpq\WhatsappApi\Jobs\ApiWhatsappFondo::dispatch($this->taxista);
 ```

# Provider
 ```bash
    Raydelpq\WhatsappApi\WhatsappApiServiceProvider::class
 ```

# Jobs
 ```bash
    Raydelpq\WhatsappApi\Jobs\ApiWhastappAdd
    Raydelpq\WhatsappApi\Jobs\ApiWhastappDel
    Raydelpq\WhatsappApi\Jobs\ApiWhastappFondo
 ```

# Componente de Livewire
 ```bash
    @if( Auth::user()->hasRole('Administrador') && config("whatsappapi.API_WA") == true )
        <button :class=" tab == 0 ? 'bg-slate-300 dark:bg-slate-800' : 'bg-slate-900' " class="text-white px-4 py-2 -mr-1.5 border-l border-slate-900 dark:border-white rounded-tr border-t" x-on:click="tab=0;">Whatsapp</button>
    @endif
 ```

 ```bash
 @if( Auth::user()->hasRole('Administrador') && config("whatsappapi.API_WA") == true )
    <div x-show="tab == 0" class="p-4 bg-slate-300 dark:bg-slate-800 rounded-b rounded-tr w-full">
        <livewire:taxista.whatsapp :taxista='$taxista'>
    </div>`
 @endif
 ```
 

# Mostrar QR
 Limpiar cache de Rutas 
 ```bash 
   php artisan route:cache
 ```
 Ruta 
 ```bash
   https://mi.domain.com/getqr
 ```

# Expulsar a todos los taxistas 
   ```bash
      php artisan out:taxista
   ```
