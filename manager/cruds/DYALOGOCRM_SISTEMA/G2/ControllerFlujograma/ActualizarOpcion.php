
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdOpcion= $_POST['IdOpcion'];
$OpcionNumero= $_POST['OpcionNumero'];
$NombreOpcion= $_POST['NombreOpcion'];
$OpcionValida= $_POST['OpcionValida'];

$ActualizarOpcion= "UPDATE dyalogo_telefonia.dy_opciones_ivrs SET nombre_interno_opcion= '". $OpcionNumero ."', nombre_usuario_opcion='". $NombreOpcion ."', valida='". $OpcionValida ."' WHERE id= '". $IdOpcion . "';";
if($ResultadoOpcion= $mysqli->query($ActualizarOpcion)) {
        
    $ActualizarFlecha= "UPDATE DYALOGOCRM_SISTEMA.CONEXIONES_IVRS SET NOMBRE='". $NombreOpcion ."' WHERE ID_OPCION= '". $IdOpcion ."' AND POR_DEFECTO= '0';";
    if ($ResultadoSQL= $mysqli->query($ActualizarFlecha)) {
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
