<?php

    include('configuracion/configuracion.php');
    require_once(__DIR__."/../helpers/ClearData.php");
 
    $mysqli = new  mysqli($Localhost,$User,$pass);

    if ($mysqli->connect_errno) {
        printf("Falló la conexión: %s\n", $mysqli->connect_error);
        exit();
    }

    /* cambiar el conjunto de caracteres a utf8 */
	if (!$mysqli->set_charset("utf8")) {
	    printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
	    exit();
	} else {

    }
    
    $limpiar = new ClearData($mysqli);
    
    if($_POST){
        $_POST = $limpiar->sanearInput($_POST);
    }
    if($_GET){
        $_GET = $limpiar->sanearInput($_GET);
    }
?>
