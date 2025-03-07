<?php
require_once("G2/G2Funciones.php");

function showResponse($message="Parametros incompletos o no validos",$header="404 Not Found",$code=404,$response=false){
    header("HTTP/1.1 ".$header);
    $data = [
        'code' => $code,
        'message' => $message,
        'response' => $response
    ];
    echo json_encode($data,$data['code']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if($data){
    if(isset($data['createTableMuestra'])){
        $idGuion=isset($data['idGuion']) ? $data['idGuion'] : false;
        $idMuestra=isset($data['idMuestra']) ? $data['idMuestra'] : false;

        if($idGuion && $idMuestra){
            $createTable=createTableMuestra($idGuion, $idMuestra);
            if($createTable){
                showResponse("Tabla de muestra creada","200 OK",200,true);
            }
        }
        showResponse();
    }
}