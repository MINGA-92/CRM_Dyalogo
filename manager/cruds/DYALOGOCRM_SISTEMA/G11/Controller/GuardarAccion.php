
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdOpcion= $_POST['IdOpcion'];
$OrdenEjecucion= $_POST['OrdenEjecucion'];
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
    /*$InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '". $Accion ."', '". $ValorAccion ."', '". $TransEncuesta ."', '". $Etiqueta ."', '". $IdAplicacion ."', '". $Parametros ."', '". $IdTroncal ."', '". $IdCampana ."', '". $IdGrabacion ."', '". $IdIVR ."', '". $IdEncuesta ."');"; 
    print_r($InsercionSQL);*/
}

//Inserción
$ResultadoSQL= $mysqli->query($InsercionSQL);
if($ResultadoSQL) {
    //Inserción correcta
    $php_response= array("msg" => "Ok");
    echo json_encode($php_response);
    mysqli_close($mysqli);
    exit;

}else{
    //Error en la Inserción
    $php_response= array("msg" => "Error");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}

?>
