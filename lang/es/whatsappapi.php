<?php

return [
    'recarga' => "Hola :name, su cuenta ha sido recargada y añadido automáticamente al grupo de choferes de la agencia *" . env("APP_NAME") . "*. Su fondo actual es de *:fondo CUP*, en caso de no ser añadido *Acepte la invitación* \n\nSaludos",
    'retirar' => "Hola :name, a su cuenta se le ha retirado crédito y se ha quedado con un fondo de *:fondo CUP* que es menor que el mínimo de *".config('whatsappapi.MIN_WA')." CUP*. Por lo que ha sido eliminado del grupo de trabajo. \n\n Saludos *" . env("APP_NAME")."*",
    'del_viaje' => "Hola :name, se le a reintegrado a *" . env("APP_NAME") . "* porque uno de sus viajes ha sido cancelado y se le ha devuelto su crédito. Su fondo actual es de *:fondo CUP*, por favor si no ha entrado al grupo directamente acceda mediante la invitación",
    'expulsar' => "Hola :name, el Sistema le ha retirado del grupo por falta de *fondo*\nSu crédito actual es de: *:fondo CUP*\n\nPor favor, contacte con uno de los administradores para recargar.\n\n Saludos, Agencia *".env("APP_NAME")."*",
    'fondo' => "Hola :name, su crédito en la agencia *".env("APP_NAME")."* es de : *:fondo CUP*.\n\n Tenga una buena jornada",
    'not_found' => "Usted no tiene una cuenta activa en la agencia *".env("APP_NAME")."*. Saludos",
];
