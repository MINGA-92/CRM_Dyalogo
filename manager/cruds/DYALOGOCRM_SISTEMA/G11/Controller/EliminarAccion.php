
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


$IdAccion = $_POST['IdAccion'];

$EliminarSQL= "DELETE FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id= '". $IdAccion ."';";
if ($ResultadoSQL= $mysqli->query($EliminarSQL)) {

    //Eliminación correcta
    $php_response= array("msg" => "Ok");
    echo json_encode($php_response);
    mysqli_close($mysqli);
    exit;

   /* $EliminarSQL_2= "DELETE FROM dyalogo_telefonia.dy_opciones_ivrs WHERE id= '". $IdAccion ."';";
    if ($ResultadoSQL= $mysqli->query($EliminarSQL_2)) {
        
    }else {
        //Error en la Eliminación
        $php_response= array("msg" => "Error");
        $ErrorConsulta= mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo $ErrorConsulta;
        exit;
    } */

} else {
    //Error en la Eliminación
    $php_response= array("msg" => "Error");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}

?>
