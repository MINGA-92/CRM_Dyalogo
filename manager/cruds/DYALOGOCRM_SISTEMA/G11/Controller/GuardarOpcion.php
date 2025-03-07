
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdIVR= $_POST['IdIVR'];
$OpcionNumero= $_POST['OpcionNumero'];
$NombreOpcion= $_POST['NombreOpcion'];
$OpcionValida= $_POST['OpcionValida'];


//Consultar Existente
$ConsultaOpcion = "SELECT * FROM dyalogo_telefonia.dy_opciones_ivrs WHERE id_ivr= '". $IdIVR . "' AND id_opcion IS NULL AND nombre_interno_opcion IS NULL;";
if ($ResultadoOpcion = $mysqli->query($ConsultaOpcion)) {
    $CantidadResultados = $ResultadoOpcion->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoOpcion->fetch_assoc()) {
            $IdOpcion= $FilaResultado['id'];
        }
    
        //Actualizar Existente
        $ActualizarSQL= "UPDATE dyalogo_telefonia.dy_opciones_ivrs SET nombre_interno_opcion= '". $OpcionNumero ."', nombre_usuario_opcion='". $NombreOpcion ."', valida='". $OpcionValida ."' WHERE id= '". $IdOpcion . "';";
        if ($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
            //Actualización correcta
            $php_response= array("msg" => "Ok");
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;
        }else{
            //Error en la Insercion
            $php_response= array("msg" => "Error Actualizar");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }

    }else{
        
        //Guardar Registro
        $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_opciones_ivrs (id_ivr, nombre_interno_opcion, nombre_usuario_opcion, valida) VALUES ('". $IdIVR ."', '". $OpcionNumero ."', '". $NombreOpcion ."', '". $OpcionValida ."');";
        if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {
            //Consultar Id
            $ConsultarId= "SELECT id AS LastID FROM dyalogo_telefonia.dy_opciones_ivrs WHERE id= @@Identity;";
            if($ResultadoSQL_2= $mysqli->query($ConsultarId)) {
                $CantidadResultados = $ResultadoSQL_2->num_rows;
                if($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL_2->fetch_assoc()) {
                        $IdOpcion= $FilaResultado['LastID'];
                    }
                    //UpDate - Actualización Id
                    $ActualizarSQL = "UPDATE dyalogo_telefonia.dy_opciones_ivrs SET id_opcion= '". $IdOpcion ."' WHERE id= '". $IdOpcion ."';";
                    if($ResultadoSQL = $mysqli->query($ActualizarSQL)) {
                        //Inserción correcta
                        $php_response= array("msg" => "Ok", "IdOpcion" => $IdOpcion);
                        echo json_encode($php_response);
                        mysqli_close($mysqli);
                        exit;
                    }else{
                        //Error en la Actualización
                        mysqli_close($mysqli);
                        $Falla = mysqli_error($mysqli);
                        $php_response = array("msg" => "Error", "Falla" => $Falla);
                        echo json_encode($php_response);
                        exit;
                    }

                }
            }else{
                //Error en la Id
                $php_response= array("msg" => "Error Id");
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

    }
}else{
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    mysqli_close($mysqli);
    exit;
}


?>
