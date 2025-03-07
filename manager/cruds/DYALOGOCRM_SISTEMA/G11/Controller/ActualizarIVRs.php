
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdIVR= $_POST['IdIVR'];
$IdEstrategia= $_POST['IdEstrategia'];
$IdProyecto= $_POST['IdProyecto'];
$NombreIVR= $_POST['NombreIVR'];
$NombreOpcion= $_POST['NombreOpcion'];
$NombreInterno= $_POST['NombreInterno'];
$GrabacionBienvenida= $_POST['GrabacionBienvenida'];
$GrabacionDigitos= $_POST['GrabacionDigitos'];
$TiempoEspera= $_POST['TiempoEspera'];


$ActualizarSQL = "UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_Comentari_b= '". $NombreIVR ."' WHERE ESTPAS_ConsInte__b= '". $IdEstrategia ."';";
if($ResultadoSQL = $mysqli->query($ActualizarSQL)) {
    //Si Actualización correcta
    $ActualizarSQL2= "UPDATE dyalogo_telefonia.dy_ivrs SET nombre_interno_ivr= '". $NombreInterno ."', nombre_usuario_ivr= '". $NombreOpcion ."', id_audio_bienvenida= '". $GrabacionBienvenida ."', id_audio_toma_digitos= '". $GrabacionDigitos ."', tiempo_espera_digitos= '". $TiempoEspera ."', nombre_raiz= '". $NombreIVR ."', id_proyecto= '". $IdProyecto ."', id_estpas= '". $IdEstrategia ."' WHERE id= '". $IdIVR ."';";
    if ($ResultadoSQL= $mysqli->query($ActualizarSQL2)) {
           
        //WS
        $ResIVR= IVR($IdIVR);
        $php_response= array("msg" => "Ok", "Id" => $IdIVR, "Resp" => $ResIVR);

        //Actualización correcta
        //$php_response= array("msg" => "Ok");
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

}else {
    //Error en la Actualización
    mysqli_close($mysqli);
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}

?>
