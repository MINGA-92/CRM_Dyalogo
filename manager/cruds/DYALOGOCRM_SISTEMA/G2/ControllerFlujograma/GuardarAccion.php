
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdEsteIVR= $_POST['IdEsteIVR'];
$IdOpcion= $_POST['IdOpcion'];
$Accion= $_POST['Accion'];
$IdTroncal= $_POST['IdTroncal'];
$IdCampana= $_POST['IdCampana'];
$TransEncuesta= $_POST['TransEncuesta'];
$IdGrabacion= $_POST['IdGrabacion'];
$IdIVR= $_POST['IdIVR'];
$IdEncuesta= $_POST['IdEncuesta'];
$Parametros= $_POST['Parametros'];
$Etiqueta= $_POST['Etiqueta'];
$IdAplicacion= $_POST['IdAplicacion'];
$ValorAccion= $_POST['ValorAccion'];
$NombreSeccion= $_POST['NombreSeccion'];

if($Accion == "6"){
    $NewAccion= "1";
}else{
    $NewAccion= $Accion;
}

//Consultar Orden
$ConsultaOrden = "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id_opcion_ivr= '". $IdOpcion ."' ORDER BY orden DESC LIMIT 1;";
if ($ResultadoOrden = $mysqli->query($ConsultaOrden)) {
    $CantidadResultados = $ResultadoOrden->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoOrden->fetch_assoc()) {
            $OrdenEjecucionI= $FilaResultado['orden'];
            $OrdenEjecucion= $OrdenEjecucionI+1;
        }
    }else{
        $OrdenEjecucion= "1";
    }
}else{
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    mysqli_close($mysqli);
    exit;
}

//Guardar Registro Acciones
if($IdTroncal != "null") {
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', NULL,  NULL,  NULL,  NULL, '". $IdTroncal ."', NULL, NULL, NULL, NULL);";

}else if($IdCampana != "null") {
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', '". $TransEncuesta ."', NULL, NULL, NULL, NULL, '". $IdCampana ."', NULL, NULL, NULL);";

}else if($IdGrabacion != "null"){
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', NULL, NULL, NULL, NULL, NULL, NULL, '". $IdGrabacion ."', NULL, NULL);";

}else if($IdIVR != "null"){
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '". $IdIVR ."', NULL);";
    
}else if($IdEncuesta != "null"){
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '". $IdEncuesta ."');";
    
}else if($IdAplicacion != "null"){
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', NULL, '". $Etiqueta ."', '". $IdAplicacion ."', '". $Parametros ."', NULL, NULL, NULL, NULL, NULL);";

}else{
    //Insert General
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);";
}

//Ejecutar Inserción
$ResultadoSQL= $mysqli->query($InsercionSQL);
if($ResultadoSQL) {
    $IdAccion= $mysqli->insert_id;
    //Actualizar Esfera
    $ActualizarSQL= "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET ID_ACCION= '". $IdAccion ."', NOMBRE_SECCION= '". $NombreSeccion ."' WHERE ID_IVR= '". $IdEsteIVR ."' AND TIPO_SECCION= '". $NewAccion ."' AND ID_ACCION IS NULL;";
    if ($ResultadoActualizar= $mysqli->query($ActualizarSQL)) {
        //Inserción correcta
        $php_response= array("msg" => "Ok");
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;
    }else{
        //Error en la Insercion
        $php_response= array("msg" => "Error");
        $ErrorConsulta= mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo $ErrorConsulta;
        exit;
    }

}else{
    //Error en la Inserción
    $php_response= array("msg" => "Error");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}

?>
