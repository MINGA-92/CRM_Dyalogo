<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'vendor/autoload.php';
use DataJourney\controllers\JourneyController;
require_once(__DIR__.'/../../../../../pages/conexion.php');


header('Content-type: application/json; charset=UTF-8');


$method = $_GET["method"];
if ($method !== 'getData') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$jsonData = json_decode(file_get_contents('php://input'),true );



$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the PersonController and process the HTTP request:

$controller = new JourneyController($mysqli, $requestMethod, $jsonData);
$controller->processRequest();
