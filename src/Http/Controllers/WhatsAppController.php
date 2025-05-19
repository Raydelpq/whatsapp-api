<?php
namespace Raydelpq\WhatsappApi\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Taxista;
use App\Jobs\OptimizeImagen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Raydelpq\WhatsappApi\Models\Whatsapp;
use Illuminate\Support\Facades\Storage;

class WhatsAppController extends Controller
{

    // Devuelve el endpoint de la api donde esta la app de node
    public static function getEndponit(){
        return  config('whatsappapi.URL_WA') . ':' . config('whatsappapi.PORT_WA');
    }

    public static function registroTaxista(Request $request){

        $telefono = $request->telefono;
        if (substr($telefono, 0, 2) == 53)
                $telefono = substr($telefono, 2, 8);

        $user = new User();
        $user->name = $request->name;
        $user->apellidos = $request->apellidos;
        $user->telefono = $telefono;
        $user->password = Hash::make("12345678");
        $user->save();

        $taxista = new Taxista();
        $taxista->user_id = $user->id;
        $taxista->marca = $request->marca;
        $taxista->modelo = $request->modelo;
        $taxista->color = $request->color;
        $taxista->lic_operativa = ($request->lic_operativa == "Si" || $request->lic_operativa == "si") ? true : false;
        $taxista->aire = ($request->aire == "Si" || $request->aire == "si") ? true : false;
        $taxista->save();

        $user->assignRole('Taxista');

        /*if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('public/photos');
            $img = Storage::url($path);
            $user->addMedia($img)->toMediaCollection('avatar');
        }

        if ($request->hasFile('auto')) {
            $path = $request->file('auto')->store('public/photos');
            $img = Storage::url($path);
            $taxista->addMedia($img)->toMediaCollection('avatar');
        }*/

        $user->addMedia($request->avatar->getRealPath())->toMediaCollection('avatar');
        $taxista->addMedia($request->auto->getRealPath())->toMediaCollection('taxi');

        $imgAvatar = $user->getMedia('avatar')->first();
        OptimizeImagen::dispatch($imgAvatar->getPath());

        $imgTaxi = $taxista->getMedia('taxi')->first();
        OptimizeImagen::dispatch($imgTaxi->getPath());

        /*$user->addMediaFromBase64($request->avatar)
             ->toMediaCollection('avatar');

        $taxista->addMediaFromBase64($request->auto)
             ->toMediaCollection('taxi');*/

    }

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

    // Expulsar Taxistas de Whatsapp
    public function expulsarTaxistas(Request $request)
    {

        $grupo = Whatsapp::where('group_id', $request->groupId)->first();

        $i = 0;
        foreach ($request->participants as $participant) {

            $telefono = $participant['id']['user'];

            if (substr($telefono, 0, 2) == 53)
                $telefono = substr($telefono, 2, 8);

            $user = User::where('telefono', $telefono)->first();


            if ($user == null) {

                self::delGroup($request->groupId,$participant['id']['user']);

                $mensaje = Lang::get('whatsappapi.not_found');
                self::sendMessage($participant['id']['user'], $mensaje);
            }
            $i++;
        }


        return response()->json([
            'cantidad' => $i
        ]);
    }

     /**
     * Método estático para obtener el fondo de un taxista basado en su número de teléfono.
     *
     * @param Request $request
     * @return void
     */
    public static function getFondo(Request $request)
    {
        $telefono = $request->input('telefono');

        // Si el número comienza con 53, quitar el 53
        if (substr($telefono, 0, 2) === '53') {
            $telefono = substr($telefono, 2);
        }

        // Buscar coincidencia en la columna 'telefono' del modelo Taxista
        $user = User::where('telefono', $telefono)->first();

        if ($user) {
            // Si se encuentra el usuario, enviar mensaje con el fondo
            $taxista = $user->taxista;
            $mensaje = Lang::get('whatsappapi.fondo',['name' => $user->name,'fondo' => $taxista->fondo]);
            self::sendMessage($request->telefono, $mensaje);
        } else {
            // Si no se encuentra el taxista, enviar mensaje de error
            $mensaje = Lang::get('whatsappapi.not_found');
            self::sendMessage($request->telefono, $mensaje);
        }

        return response()->json([
            'status' => true,
            'message' => "Saldo enviado a : ".$user->name
        ]);
    }


    // Enviar Mensajes a un Usuario
    public static function sendMessage($numero, $message){
        $endpoint = self::getEndponit();
        $instance = env('EVOLUTION_INSTANCE');
        $apikey = env('EVOLUTION_APIKEY');
        try{
            $response = Http::withHeaders([
                'apikey' => $apikey
            ])->retry(3,100)->post($endpoint."/message/sendText/{$instance}",[
                'number' => $numero,
                'text' => $message,
            ]);

            if($response->successful())
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

    public static function sendFile($numero, $file, $caption, $grupo){
        $endpoint = self::getEndponit();
        try{
            $response = Http::retry(3,100)->post($endpoint."/send/file",[
                'chatId' => $numero,
                'file' => $file,
                'caption' => $caption,
                'grupo' => $grupo,
            ]);

            if($response->ok())
                return response()->json([
                    'status' => true,
                    'message' => "Archivo Enviado"
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
            $endpoint = self::getEndponit();
            $instance = config('whatsappapi.EVOLUTION_INSTANCE');
            $apikey = config('whatsappapi.EVOLUTION_APIKEY');

            $response = Http::withHeaders([
                'apikey' => $apikey
            ])->retry(3,100)->post($endpoint."/group/updateParticipant/{$instance}?groupJid={$grupoId}",[
                'action' => 'add',
                'participants' => [$numero],
            ]);

            if($response->successful())
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
    }

    // Eliminar usuario a Grupo
    public static function delGroup($grupoId,$numero){
        try{
            $endpoint = self::getEndponit();
            $instance = env('EVOLUTION_INSTANCE');
            $apikey = env('EVOLUTION_APIKEY');
            $response = Http::withHeaders([
                'apikey' => $apikey
            ])->retry(3,100)->post($endpoint."/group/updateParticipant/{$instance}?groupJid={$grupoId}",[
                'action' => 'remove',
                'participants' => [$numero],
            ]);

            if($response->successful())
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
    }

    // verificar usuario a Grupo
    public static function inGroup($groupId,$numero){
        $endpoint = env('EVOLUTION_API_URL');
        $apiKey = env('EVOLUTION_API_KEY');
        $instance = env('EVOLUTION_INSTANCE');

        try{
            $response = Http::withHeaders([
                    'apikey' => $apiKey
                ])->retry(3,100)->post($endpoint."/group/participants/{$instance}?groupJid={$groupId}",[
                    'action' => 'remove',
                    'participants' => [$numero],
                ]);


            $participantes = $response->json('participants');

            foreach ($participantes as $p) {
                $numeroEnLista = explode('@', $p['id'])[0];
                if ($numeroEnLista === $numero) {
                    return true;
                }
            }

            return false;
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
