
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


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
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_ivrs (nombre_interno_ivr, nombre_usuario_ivr, id_audio_bienvenida, id_audio_toma_digitos, tiempo_espera_digitos, nombre_raiz, id_proyecto, id_estpas) VALUES ('". $NombreInterno ."', '". $NombreOpcion ."', '". $GrabacionBienvenida ."', '". $GrabacionDigitos ."', '". $TiempoEspera ."', '". $NombreIVR ."', '". $IdProyecto ."', '". $IdEstrategia ."');";
    if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {
        $IdIVR = $mysqli->insert_id;

        //WS
        $ResIVR= IVR($IdIVR);
        $php_response= array("msg" => "Ok", "Id" => $IdIVR, "Resp" => $ResIVR);

        //Inserción correcta
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
