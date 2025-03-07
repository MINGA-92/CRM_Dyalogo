<?php

namespace Dyalogo\Eliminarpausas\utils;

class Mysql
{
    public static function mysqli()
    {
        global $mysqli;
        return $mysqli;
    }
}
