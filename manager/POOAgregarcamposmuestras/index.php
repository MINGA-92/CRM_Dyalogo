<?php
require "vendor/autoload.php";
// include_once(__DIR__ . "/../../helpers/parameters.php");

date_default_timezone_set('America/Bogota');
include("../pages/conexion.php");

use Dyalogo\Poomuestras\models\Muestras;


$muestras = new Muestras;
$muestras->ejecutar();
