<?php

namespace Raydelpq\WhatsappApi\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Whatsapp extends Model
{

    public function users(){
        return $this->belongsToMany(User::class);
    }

}
