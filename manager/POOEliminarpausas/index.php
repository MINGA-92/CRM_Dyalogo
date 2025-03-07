<?php
require 'vendor/autoload.php';

date_default_timezone_set('America/Bogota');
include("../pages/conexion.php");



use Dyalogo\Eliminarpausas\models\Proyecto;
use Dyalogo\Eliminarpausas\models\Pausas;

$proyectos = new Proyecto;
// echo "\n proyectos->getProyectos", json_encode($proyectos->getProyectos()), "\n\n";

$pausas = new Pausas;
$pausas->unificarPausas($pausas->getPausas());

// echo "\n pausas->getPausas()", json_encode($pausas->getPausas()), "\n\n";

