<?php

namespace Raydelpq\WhatsappApi\Http\Livewire;

use Livewire\Component;

class GetQr extends Component
{
    public $canal;
    public $pusherKey;

    public function render()
    {
        $this->canal = config('whatsappapi.CANAL_WA');
        $this->pusherKey = config('whatsappapi.Pusher_WA');
        return view('whatsapp-api::livewire.get-qr')->layout('whatsapp-api::layouts.layout_whatsapp');;
    }
}
