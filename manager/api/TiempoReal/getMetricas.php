<?php

include(__DIR__ . "/./controllers/token.php");
include_once(__DIR__ . "/./controllers/Dysingleton.php");


function getMetricas()
{
    global $token;

    $dySingleton = new Dysingleton($token);

    if ($_SESSION["UNO"]) {

        $data = json_decode($dySingleton->getInfo(null, $_SESSION["HUESPED"]));

        if(isset($data->obj)){
            if(!is_null($data->obj)){
                return json_encode([json_decode($data->obj)]);
            }
        }

        return [];
    } else {
        $allData = [];
        foreach ($_SESSION["HUESPED_LIST"] as $idHuesped) {
            $dataMet = json_decode($dySingleton->getInfo(null, $idHuesped));
            if(isset($dataMet->obj)){
                if(!is_null($dataMet->obj)){
                    array_push($allData,json_decode($dataMet->obj));
                }
            }
        }
        return json_encode($allData);
    }
}


function getAgents(){
    global $token;

    $dySingleton = new Dysingleton($token);

    $data = json_decode($dySingleton->getInfo("stateAge", null));

    if(isset($data->obj)){
        if(!is_null($data->obj)){
            return json_encode(json_decode($data->obj));
        }
    }

    return [];

}
