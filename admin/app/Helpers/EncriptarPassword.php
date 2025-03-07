<?php

namespace App\Helpers;

/**
 * Esta clase encripta contraseñas
 * @author Breiner Sanchez
 */
class EncriptarPassword
{

    public static function run($password)
    {
        $method = 'sha256';
        $encrypted = hash($method, $password, false);
        return $encrypted;
    }
}
