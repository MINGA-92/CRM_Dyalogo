<?php

declare(strict_types=1);
require 'vendor/autoload.php';
// date_default_timezone_set('America/Bogota');
// include("../pages/conexion.php");
include(__DIR__ . "../../../../../../pages/conexion.php");

header('Content-type: application/json; charset=UTF-8');


use Dyalogo\Script\controllers\Controllers;
use Dyalogo\Script\models\Models;


$parts = explode("/", $_SERVER['REQUEST_URI']);

// print_r($parts);

if (count($parts) > 9) {
   # code...
   http_response_code(404);
   exit;
}

// echo count($parts);
// print_r($parts);

$params = explode("?", $parts[8]);

if ($parts[6] != "POOEliminarDuplicados" && $parts[7] != "index.php") {
   # code...
   http_response_code(404);
   exit;
}


// print_r($params[0]);

// print_r($params);


// $id = $parts[7] ?? null;


$param = $params[0] ?? null;
// $id = $parts[8] ?? null;

// print_r("id=> $id \n");

// print_r("=> $param \n");


$models = new Models();
$controllers = new Controllers($models);
$controllers->proccessRequest($_SERVER['REQUEST_METHOD'], $param);

