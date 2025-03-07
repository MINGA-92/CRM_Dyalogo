<?php

declare(strict_types=1);
// require __DIR__ . "../../../../../configuracion.php";
include(__DIR__ . "../../../../../pages/conexion.php");


spl_autoload_register(function ($class) {
    require __DIR__ . "/src/{$class}.php";

    // echo __DIR__ . "/controllers/{$class}.php";
});

set_exception_handler("ErrorHandler::handleException");

header('Content-type: application/json; charset=UTF-8');


$parts = explode("/", $_SERVER['REQUEST_URI']);


if (count($parts) > 8) {
    # code...
    http_response_code(404);
    exit;
}

// echo count($parts);
// print_r($parts);

$params = explode("?", $parts[7]);

if ($parts[5] != "reportesAgente" && $parts[6] != "index.php") {
    # code...
    http_response_code(404);
    exit;
}


// var_dump($params[0]);

// var_dump($_GET['intIdUsuario']);

// var_dump("xxx",);


// $id = $parts[7] ?? null;


$param = $params[0] ?? null;
// $id = $parts[8] ?? null;

// print_r("id=> $id \n");
// print_r("par$param => $param \n");


$database = new Database($DB_Name, $DB_User, $DB_Pass, array(
    "Basedatos_systema" => $BaseDatos_systema,
    "BaseDatos" => $BaseDatos,

));



$gateway = new ReporteAgenteGateway($database);

$controller = new ReporteAgenteController($gateway);

$controller->proccessRequest($_SERVER['REQUEST_METHOD'], $param);
// $controllerCampos->proccessRequest($_SERVER['REQUEST_METHOD'], $campos);
