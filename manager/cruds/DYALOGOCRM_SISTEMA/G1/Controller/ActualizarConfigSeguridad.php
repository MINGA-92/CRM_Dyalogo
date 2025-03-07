
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


$IdSeguridad= $_POST['IdSeguridad'];
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

$ActualizarSQL= "UPDATE DYALOGOCRM_SISTEMA.SEGURIDAD SET SEGURIDAD_ACTIVAR_CADUCIDAD= '". $ActivarCaducidad ."', SEGURIDAD_DIAS_VALIDEZ='". $NumeroDiasValidez ."', SEGURIDAD_DIAS_NOTIFICAR='". $NumeroDiasAntes ."', 
SEGURIDAD_ACTIVAR_BLOQUEO_AUTOMATICO='". $ActivarBloqueo ."', SEGURIDAD_INTENTOS_FALLIDOS='". $IntentosFallidos ."' WHERE SEGURIDAD_ConsInte__b= '". $IdSeguridad ."';"; 
if($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
    //Actualizacion correcta
    $ActualizarSQL_2= "UPDATE dyalogo_general.huespedes SET pass_cambio_periodico_requerido= '". $UpActivarCaducidad ."', pass_dias_cambio_periodico= '". $NumeroDiasValidez ."' WHERE id= '". $IdProyecto ."';"; 
    if($ResultadoSQL_2= $mysqli->query($ActualizarSQL_2)) {
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
}else {
    //Error en la Actualizacion
    $php_response= array("msg" => "Error");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}

?>
