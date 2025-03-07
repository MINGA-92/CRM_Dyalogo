
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$Huesped= $_POST['Huesped'];
$Telefono= $_POST['Telefono'];
$Motivo= $_POST['Motivo'];

//Verificacion De Existencia Del Telefono
$ConsultaSQL= "SELECT * FROM dyalogo_telefonia.dy_lista_negra_numeros WHERE numero= '". $Telefono ."';";
if ($ResultadoSQL= $mysqli->query($ConsultaSQL)) { 
    $CantidadResultados= $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        $php_response= array("msg" => "Ya Existe");
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;
    }else {
        $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_lista_negra(nombre, accion, comparador, id_proyecto) VALUES ('". $Telefono ."', '1', '". $Motivo ."', '". $Huesped ."');"; 
        if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {
            $ConsultaSQL_2= "SELECT id FROM dyalogo_telefonia.dy_lista_negra WHERE nombre= '". $Telefono ."';";
            if ($ResultadoSQL= $mysqli->query($ConsultaSQL_2)) { 
                $CantidadResultados = $ResultadoSQL->num_rows;
                if($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $IdListaNegra= $FilaResultado['id'];
                    }
                    $InsercionSQL_2= "INSERT INTO dyalogo_telefonia.dy_lista_negra_numeros(id_lista_negra, numero) VALUES ('". $IdListaNegra. "', '". $Telefono ."');";
                    if ($ResultadoSQL= $mysqli->query($InsercionSQL_2)) {
                        $ActualizarSQL = "UPDATE dyalogo_telefonia.dy_lista_negra SET nombre= 'Bloqueo De Numero' WHERE id= '". $IdListaNegra ."';";
                        if ($ResultadoSQL = $mysqli->query($ActualizarSQL)) {
                            //inserción correcta
                            $php_response= array("msg" => "Ok");
                            echo json_encode($php_response);
                            mysqli_close($mysqli);
                            exit;
                        }else {
                            //Error en la Insercion
                            $php_response= array("msg" => "Error ActualizarSQL");
                            $ErrorConsulta= mysqli_error($mysqli);
                            mysqli_close($mysqli);
                            echo $ErrorConsulta;
                            exit;
                        }
                    }else {
                        //Error en la Insercion
                        $php_response= array("msg" => "Error InsercionSQL_2");
                        $ErrorConsulta= mysqli_error($mysqli);
                        mysqli_close($mysqli);
                        echo $ErrorConsulta;
                        exit;
                    }
                }
            }else {
                //Error en la Insercion
                $php_response= array("msg" => "Error ConsultaSQL_2");
                $ErrorConsulta= mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo $ErrorConsulta;
                exit;
            }

            
        } else {
            //Error en la Insercion
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
