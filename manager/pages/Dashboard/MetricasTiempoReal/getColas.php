<?php

include(__DIR__ . "/./controllers/token.php");
include_once(__DIR__ . "/./controllers/Dysingleton.php");


function getMetricas()
{
    global $token;

    $dySingleton = new Dysingleton($token);

    $data = json_decode($dySingleton->getInfo("colas"));

    if(isset($data->obj)){
        if(!is_null($data->obj)){
            return $data->obj;
        }
    }

    return json_encode([]);
    
}


header('Content-Type: application/json; charset=utf-8');
echo getMetricas();
