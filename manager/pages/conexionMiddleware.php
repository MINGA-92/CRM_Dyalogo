<?php

    include(__DIR__."/../../crm_php/configuracion/configuracion.php");
    require_once(__DIR__."/../utils.php");
    
    $mysqliMiddleware = new  mysqli('10.142.0.13',$User,$pass );
    
    /* comprobar la conexiÃ³n */
    if ($mysqliMiddleware->connect_errno) {
        printf("FallÃ³ la conexiÃ³n: %s\n", $mysqli->connect_error);
        exit();
    }
    
    if (!$mysqliMiddleware->set_charset("utf8mb4")) {
        printf("Error cargando el conjunto de caracteres utf8mb4: %s\n", $mysqli->error);
        exit();
    } 
    
?>
