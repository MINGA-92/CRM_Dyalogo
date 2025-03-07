
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdAccion= $_POST['IdAccion'];
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

//Actualizar Registro Acciones
if($IdTroncal != "null") {
    $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= NULL, 
    avanzado_etiqueta= NULL, avanzado_id_aplicacion= NULL, avanzado_parametros= NULL, id_troncal= '". $IdTroncal ."', id_campana= NULL, id_audio= NULL, id_ivr= NULL, id_encuesta= NULL WHERE id= '". $IdAccion . "';";

}else if($IdCampana != "null") {
    $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= '". $TransEncuesta ."', 
    avanzado_etiqueta= NULL, avanzado_id_aplicacion= NULL, avanzado_parametros= NULL, id_troncal= NULL, id_campana= '". $IdCampana ."', id_audio= NULL, id_ivr= NULL, id_encuesta= NULL WHERE id= '". $IdAccion . "';";

}else if($IdGrabacion != "null"){
    $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= NULL, 
    avanzado_etiqueta= NULL, avanzado_id_aplicacion= NULL, avanzado_parametros= NULL, id_troncal= NULL, id_campana= NULL, id_audio= '". $IdGrabacion ."', id_ivr= NULL, id_encuesta= NULL WHERE id= '". $IdAccion . "';";

}else if($IdIVR != "null"){
    $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= NULL, 
    avanzado_etiqueta= NULL, avanzado_id_aplicacion= NULL, avanzado_parametros= NULL, id_troncal= NULL, id_campana= NULL, id_audio= NULL, id_ivr= '". $IdIVR ."', id_encuesta= NULL WHERE id= '". $IdAccion . "';";
    
}else if($IdEncuesta != "null"){
    $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= NULL, 
    avanzado_etiqueta= NULL, avanzado_id_aplicacion= NULL, avanzado_parametros= NULL, id_troncal= NULL, id_campana= NULL, id_audio= NULL, id_ivr= NULL, id_encuesta= '". $IdEncuesta ."' WHERE id= '". $IdAccion . "';";
    
}else if($IdAplicacion != "null"){
    $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= NULL, 
    avanzado_etiqueta= '". $Etiqueta ."', avanzado_id_aplicacion= '". $IdAplicacion ."', avanzado_parametros= '". $Parametros ."', id_troncal= NULL, id_campana= NULL, id_audio= NULL, id_ivr= NULL, id_encuesta= NULL WHERE id= '". $IdAccion . "';";

}else{
    //Update General
    $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= NULL, 
    avanzado_etiqueta= NULL, avanzado_id_aplicacion= NULL, avanzado_parametros= NULL, id_troncal= NULL, id_campana= NULL, id_audio= NULL, id_ivr= NULL, id_encuesta= NULL WHERE id= '". $IdAccion . "';";
    /*$ActualizarSQL= "UPDATE dyalogo_telefonia.dy_acciones_opcion_ivrs SET orden= '". $OrdenEjecucion ."', accion='". $Accion ."', valor_accion='". $ValorAccion ."', campana_transfiere_encuesta= '". $TransEncuesta ."', avanzado_etiqueta= '". $Etiqueta ."', 
    avanzado_id_aplicacion= '". $IdAplicacion ."', avanzado_parametros= '". $Parametros ."', id_troncal= '". $IdTroncal ."', id_campana= '". $IdCampana ."', id_audio= '". $IdGrabacion ."', id_ivr= '". $IdIVR ."', id_encuesta= '". $IdEncuesta ."' WHERE id= '". $IdAccion . "';"; */
}


if($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
        //Actualización correcta
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

?>
