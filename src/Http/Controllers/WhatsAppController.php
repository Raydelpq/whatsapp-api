<?php
namespace Raydelpq\WhatsappApi\Http\Controllers;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Raydelpq\WhatsappApi\Models\Whatsapp;

class WhatsAppController extends Controller
{

    // Cargar Taxistas de Whatsapp
    public function loadTaxistas(Request $request)
    {

        $grupo = Whatsapp::where('group_id', $request->groupId)->first();

        $i = 0;
        foreach ($request->participants as $participant) {

            $telefono = $participant['id']['user'];

            if (substr($telefono, 0, 2) == 53)
                $telefono = substr($telefono, 2, 8);

            $user = User::where('telefono', $telefono)->first();


            if ($user != null) {

                if ($user->taxista != null) {
                    $taxista = $user->taxista;
                    $taxista->whatsapps()->syncWithoutDetaching([$grupo->id]);
                }
            }
            $i++;
        }


        return response()->json([
            'cantidad' => $i
        ]);
    }

    // Devuelve el endpoint de la api donde esta la app de node
    public static function getEndponit(){
        return  config('whatsappapi.URL_WA') . ':' . config('whatsappapi.PORT_WA') . config('whatsappapi.ENDPOINT_WA');
    }

    // Enviar Mensajes a un Usuario
    public static function sendMessage($numero, $message){
        $endpoint = WhatsAppController::getEndponit();
        try{
            $response = Http::retry(3,100)->post($endpoint."/send",[
                'phoneNumber' => $numero,
                'message' => $message,
                'clientId' => env("clienteID")
            ]);

            if($response->ok())
                return response()->json([
                    'status' => true,
                    'message' => "Mensaje Enviado"
                ]);

        }catch(Exception $error){
            return response()->json([
                'status' => false,
                'message' => $error
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "Algo ha salido mal, vuelva a intentarlo"
        ]);
    }

    // Agregar usuario a Grupo
    public static function addGroup($grupoId,$numero){
        try{
            $endpoint = WhatsAppController::getEndponit();
            $response = Http::retry(3,100)->post($endpoint."group/add",[
                'groupId' => $grupoId,
                'participante' => $numero,
                'clientId' => env("clienteID")
            ]);

            if($response->ok())
                return response()->json([
                    'status' => true,
                    'message' => "Agregado Correctamente"
                ]);

        }catch(Exception $error){
            return response()->json([
                'status' => false,
                'message' => $error
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "Algo ha salido mal, vuelva a intentarlo"
        ]);
    }

    // Eliminar usuario a Grupo
    public static function delGroup($grupoId,$numero){
        try{
            $endpoint = WhatsAppController::getEndponit();
            $response = Http::retry(3,100)->post($endpoint."group/del",[
                'groupId' => $grupoId,
                'participante' => $numero,
                'clientId' => env("clienteID")
            ]);

            if($response->ok())
            return response()->json([
                'status' => true,
                'message' => "Eliminado Correctamente"
            ]);

        }catch(Exception $error){
            return response()->json([
                'status' => false,
                'message' => $error
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "Algo ha salido mal, vuelva a intentarlo"
        ]);
    }

    // verificar usuario a Grupo
    public static function inGroup($grupoId,$numero){
        try{
            $endpoint = WhatsAppController::getEndponit();
            $response = Http::retry(3,100)->post($endpoint."group/is",[
                'groupId' => $grupoId,
                'participante' => $numero
            ])->json();

            return response()->json([
                'status' => $response['ok'],
                'message' => ""
            ]);


        }catch(Exception $error){
            return response()->json([
                'status' => false,
                'message' => $error
            ]);
        }
    }

    // Retorna el numero si errores
    public static function getNumero($telefono){
        $numero = str_replace(" ","", $telefono);

        if (str_ends_with($numero, "‬")) {
            $numero = substr($numero, 0, -3);
        }

        if (str_starts_with($numero, "‪")) {
            $numero = substr($numero, 3);
        }

        $numero = str_replace("�","", $numero);
        $numero = strlen($numero) == 8 ? '53'.$numero : $numero;

        return $numero;
    }
}
