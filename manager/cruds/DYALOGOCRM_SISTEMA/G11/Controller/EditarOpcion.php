
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdOpcion= $_POST['IdOpcion'];
$OpcionNumero= $_POST['OpcionNumero'];
$NombreOpcion= $_POST['NombreOpcion'];
$OpcionValida= $_POST['OpcionValida'];

$ActualizarSQL= "UPDATE dyalogo_telefonia.dy_opciones_ivrs SET nombre_interno_opcion= '". $OpcionNumero ."', nombre_usuario_opcion='". $NombreOpcion ."', valida='". $OpcionValida ."' WHERE id= '". $IdOpcion . "';";
    if ($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
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
