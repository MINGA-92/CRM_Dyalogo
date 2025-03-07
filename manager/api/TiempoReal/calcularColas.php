<?php

// ESTE ARCHIVO SE DEJA APARTE PARA QUE SEA EJECUTADO POR EL CRON Y NO USAR REQUEST


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__.'/../autoload.php';
require_once __DIR__.'/../helpers/Utils.php';
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../config/dbMiddeware.php';
include_once(__DIR__."/./controllers/token.php");
include_once(__DIR__."/./controllers/ColasController.php");

$objColas = new ColasController();

$objColas->calculateColas();
