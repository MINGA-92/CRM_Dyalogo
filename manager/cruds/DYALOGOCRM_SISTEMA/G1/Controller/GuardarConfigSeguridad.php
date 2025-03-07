
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdProyecto= $_POST['IdProyecto'];
$ActivarCaducidad_I= $_POST['ActivarCaducidad'];
$NumeroDiasValidez= $_POST['NumeroDiasValidez'];
$NumeroDiasAntes= $_POST['NumeroDiasAntes'];
$ActivarBloqueo_I= $_POST['ActivarBloqueo'];
$IntentosFallidos= $_POST['IntentosFallidos'];
if($ActivarCaducidad_I == "true") {
    $ActivarCaducidad= "1";
    $UpActivarCaducidad= "1";
}else{
    $ActivarCaducidad= "0";
    $UpActivarCaducidad= "0";
}
if($ActivarBloqueo_I == "true") {
    $ActivarBloqueo= "1";
}else{
    $ActivarBloqueo= "0";
}


$InsercionSQL= "INSERT INTO DYALOGOCRM_SISTEMA.SEGURIDAD (SEGURIDAD_ConsInte__PROYEC_b, SEGURIDAD_ACTIVAR_CADUCIDAD, SEGURIDAD_DIAS_VALIDEZ, SEGURIDAD_DIAS_NOTIFICAR, SEGURIDAD_ACTIVAR_BLOQUEO_AUTOMATICO, SEGURIDAD_INTENTOS_FALLIDOS) 
VALUES ('". $IdProyecto ."', '". $ActivarCaducidad ."', '". $NumeroDiasValidez ."', '". $NumeroDiasAntes ."', '". $ActivarBloqueo ."', '". $IntentosFallidos ."');";
if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {
    //Inserción correcta
    $ActualizarSQL= "UPDATE dyalogo_general.huespedes SET pass_cambio_periodico_requerido= '". $UpActivarCaducidad ."', pass_dias_cambio_periodico= '". $NumeroDiasValidez ."' WHERE id= '". $IdProyecto ."';"; 
    if($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
        //Todo Ok
        $php_response= array("msg" => "Ok");
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;
        
    }else {
        //Error en la Actualizacion
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
