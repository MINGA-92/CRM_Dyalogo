<?php
    include("configuracion.php");
    $mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

    /* comprobar la conexión */
    if ($mysqli->connect_errno) {
        printf("Falló la conexión: %s\n", $mysqli->connect_error);
        exit();
    }

    if (!$mysqli->set_charset("utf8")) {
	    printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
	    exit();
	} else {

	}

    
?>
