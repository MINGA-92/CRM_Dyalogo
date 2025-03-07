
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdOpcion = $_POST['IdOpcion'];

$EliminarSQL= "DELETE FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id_opcion_ivr= '". $IdOpcion ."';";
if ($ResultadoSQL= $mysqli->query($EliminarSQL)) {
    
    $InActivarClaveExterna ="SET FOREIGN_KEY_CHECKS = 0;";
    $Ejecutar= $mysqli->query($InActivarClaveExterna);
    $EliminarSQL_2= "DELETE FROM dyalogo_telefonia.dy_opciones_ivrs WHERE id= '". $IdOpcion ."';";
    if ($ResultadoSQL_2= $mysqli->query($EliminarSQL_2)) {
        $ActivarClaveExterna ="SET FOREIGN_KEY_CHECKS = 1;";
        $Ejecutar_2= $mysqli->query($ActivarClaveExterna);
        //Eliminación correcta
        $php_response= array("msg" => "Ok");
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;
    }else {
        //Error en la Eliminación
        $php_response= array("msg" => "Error");
        $ErrorConsulta= mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo $ErrorConsulta;
        exit;
    }

} else {
    //Error en la Eliminación
    $php_response= array("msg" => "Error");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}

?>
