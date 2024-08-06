# whatsapp-api
 Gestionar agencias de taxis desde whatsapp

# Install
 ``composer require raydelpq/whatsapp-api``

# Public Vendor
 ``php artisan vendor:publish --tag=whatsappapi``

# Provider
 ``Raydelpq\WhatsappApi\WhatsappApiServiceProvider::class``

# Jobs
 -``Raydelpq\WhatsappApi\Jobs\ApiWhastappAdd``
 -``Raydelpq\WhatsappApi\Jobs\ApiWhastappDel``
 -``Raydelpq\WhatsappApi\Jobs\ApiWhastappFondo``

# Componente de Livewire
 ``<livewire:taxista.whatsapp :taxista='$taxista'>``

# Mostrar QR
 Limpiar cache de Rutas ``php artisan route:cache``
 Ruta ``/getqr``