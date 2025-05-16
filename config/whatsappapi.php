<?php

return [
    'API_WA' => env('API_WA') ? env('API_WA') : false,
    'MIN_WA' => env('MIN_WA') ? env('MIN_WA') : 150,
    'NOTIFICAR' => env('NOTIFICAR') ? env('NOTIFICAR') : false,
    'MIN_NOTIFICAR' => env('MIN_NOTIFICAR') ? env('MIN_NOTIFICAR') : 300,
    'URL_WA' => env('URL_WA') ? env('URL_WA') : 'http://localhost',
    'PORT_WA' => env('PORT_WA') ? env('PORT_WA') : '99000',
    'ENDPOINT_WA' => env('ENDPOINT_WA') ? env('ENDPOINT_WA') : '/api/v1',
    'CANAL_WA' => env('CANAL_WA') ? env('CANAL_WA') : 'setQR_',
    'Pusher_WA' => env('PUSHER_APP_KEY') ? env('PUSHER_APP_KEY') : '',
    'procesoName' => env('procesoName') ? env('procesoName') : '',
    'EVOLUTION_INSTANCE' => env('EVOLUTION_INSTANCE') ? env('EVOLUTION_INSTANCE') : '',
    'EVOLUTION_APIKEY' => env('EVOLUTION_APIKEY') ? env('EVOLUTION_APIKEY') : 'Prueba123*'
];
