

<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$Id= $_POST['IdEliminar'];
$Huesped = $_POST['Huesped'];

$EliminarSQL= "DELETE FROM dyalogo_telefonia.dy_lista_negra_numeros WHERE id_lista_negra= '". $Id ."';";
if ($ResultadoSQL= $mysqli->query($EliminarSQL)) {
    $EliminarSQL_2= "DELETE FROM dyalogo_telefonia.dy_lista_negra WHERE id= '". $Id ."' AND id_proyecto= '". $Huesped ."';";
    if ($ResultadoSQL= $mysqli->query($EliminarSQL_2)) {
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
