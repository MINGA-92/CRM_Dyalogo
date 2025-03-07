<?php

namespace App\Helpers;

/**
 * Esta clase se encarga de construir una contraseña
 * @author Breiner Sanchez
 */
class GenerarPassword{

    public static function run(){
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";

        $password = "";
        
        //Reconstruimos la contraseña segun la longitud que se quiera
        for ($i = 0; $i < 8; $i++) {
            //obtenemos un caracter aleatorio escogido de la cadena de caracteres
            $password .= substr($str, rand(0, 62), 1);
        }

        //Mostramos la contraseña generada
        return $password;
    }
}