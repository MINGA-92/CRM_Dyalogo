
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdAccion= $_POST['IdAccion'];
$IdTomaDigitos= $_POST['IdTomaDigitos'];
$IdEstrategia= $_POST['IdEstrategia'];
$IdProyecto= $_POST['IdProyecto'];
$NombreIVR= $_POST['NombreIVR'];
$NombreEsfera= $_POST['NombreEsfera'];
$NombreInterno= $_POST['NombreInterno'];
$GrabacionDigitos= $_POST['GrabacionDigitos'];
$AceptarDigitos= $_POST['AceptarDigitos'];
$TiempoEspera= $_POST['TiempoEspera'];
$IntentosPermitidos= $_POST['IntentosPermitidos'];
$GrabacionOpcErrada= $_POST['GrabacionOpcErrada'];


$ActualizarSQL= "UPDATE dyalogo_telefonia.dy_ivrs SET id_proyecto= '". $IdProyecto ."', nombre_raiz= '". $NombreIVR ."', nombre_interno_ivr= '". $NombreInterno ."', nombre_usuario_ivr= '". $NombreEsfera ."', 
id_audio_toma_digitos= '". $GrabacionDigitos ."', saludo_permite_marcar_digitos= '". $AceptarDigitos ."', tiempo_espera_digitos= '". $TiempoEspera ."', intentos_errados_permitidos= '". $IntentosPermitidos ."', id_audio_opcion_errada= '". $GrabacionOpcErrada ."', ivr_principal= '0' WHERE id= '". $IdTomaDigitos ."';";
if ($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
        
    //Actualizar Esfera
    $ActualizarSQL= "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET NOMBRE_SECCION= '". $NombreEsfera ."' WHERE ID_ACCION= '". $IdAccion ."' AND TIPO_SECCION= '7';";
    if ($ResultadoActualizar= $mysqli->query($ActualizarSQL)) {
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

}else{
    //Error en la Insercion
    $php_response= array("msg" => "Error");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}


?>
