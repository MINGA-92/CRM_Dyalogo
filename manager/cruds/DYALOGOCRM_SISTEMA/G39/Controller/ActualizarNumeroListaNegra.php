
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$Id= $_POST['Id'];
$Huesped = $_POST['Huesped'];
$Telefono= $_POST['Numero'];
$Motivo= $_POST['Motivo'];

//Verificacion De Existencia Del Telefono
$ConsultaSQL= "SELECT * FROM dyalogo_telefonia.dy_lista_negra_numeros WHERE numero= '". $Telefono ."' AND id_lista_negra != '". $Id ."';";
if ($ResultadoSQL= $mysqli->query($ConsultaSQL)) { 
    $CantidadResultados= $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        $php_response= array("msg" => "Ya Existe");
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;
    }else {
        $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_lista_negra_numeros SET numero= '". $Telefono ."' WHERE id_lista_negra= '". $Id ."';"; 
        if ($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
            $ActualizarSQL_2= "UPDATE dyalogo_telefonia.dy_lista_negra SET comparador= '". $Motivo ."' WHERE id= '". $Id ."';";
            if ($ResultadoSQL= $mysqli->query($ActualizarSQL_2)) {
                //Actualizacion correcta
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
            
        } else {
            //Error en la Actualizacion
            $php_response= array("msg" => "Error");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }
    }
}else {
    //Error en la Consulta
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}

?>
