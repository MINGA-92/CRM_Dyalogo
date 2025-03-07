<?php
ob_start();
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);

require_once 'autoload.php';
require_once 'helpers/Utils.php';
require_once 'config/db.php';
require_once 'config/dbMiddeware.php';
require_once 'config/parameters.php';

function showResponse( string $status="Error de solicitud", bool $response=false, int $code=404, string $header="404 Not Found"){
    header("HTTP/1.1 {$header}");
    $data = [
        'code' => $code,
        'message' => $status,
        'response' => $response
    ];

    echo json_encode($data,$data['code']);
    exit();
}

if(isset($_GET['controller'])){
    $nombre_controlador = $_GET['controller'].'Controller';
}else{
    showResponse();
}

if(class_exists($nombre_controlador)){
    $controlador = new $nombre_controlador();

    if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])){
        $action = $_GET['action'];
        $controlador->$action();
    }else{
        showResponse();
    }
}else{
    showResponse();
}

