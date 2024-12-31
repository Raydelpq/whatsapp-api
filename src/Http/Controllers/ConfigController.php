<?php

namespace Raydelpq\WhatsappApi\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class ConfigController extends Controller
{
    public function updateApiWa(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'API_WA' => 'required|boolean',
        ]);

        // Obtener el nuevo valor
        $newValue = $request->input('API_WA');

        // Actualizar el valor en el archivo de configuración
        Config::set('whatsappapi.API_WA', $newValue);

        // Escribe la nueva configuración en el archivo .env
        file_put_contents(app()->environmentFilePath(), str_replace(
            'API_WA=' . env('API_WA'),
            'API_WA=' . ($newValue ? 'true' : 'false'),
            file_get_contents(app()->environmentFilePath())
        ));


        // Guardar el archivo de configuración (esto puede variar según cómo manejes tus configuraciones)
        // Aquí podrías necesitar escribir manualmente el nuevo valor en el archivo .env o config.php

        return response()->json(['message' => 'Configuración actualizada con éxito']);
    }
}
