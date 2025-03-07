
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


$IdFrom = $_POST['IdFrom'];
$IdTo = $_POST['IdTo'];

$ConsultaFlecha = "SELECT * FROM DYALOGOCRM_SISTEMA.CONEXIONES_IVRS WHERE ID_DESDE= '". $IdFrom ."' AND ID_HASTA= '". $IdTo ."';";
if($ResultadoTipoFlecha = $mysqli->query($ConsultaFlecha)) {
    $CantidadResultados = $ResultadoTipoFlecha->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoTipoFlecha->fetch_assoc()) {
            $IdFlecha= $FilaResultado['ID_CONEXIONES_IVRS'];
            $IdOpcion= $FilaResultado['ID_OPCION'];
            $TipoFlecha= $FilaResultado['POR_DEFECTO'];
        }
        
        $EliminarFlecha= "DELETE FROM DYALOGOCRM_SISTEMA.CONEXIONES_IVRS WHERE ID_CONEXIONES_IVRS= '". $IdFlecha ."'";
        if ($ResultadoSQL= $mysqli->query($EliminarFlecha)) {
            if($TipoFlecha == "0"){

                $EliminarOpcion= "DELETE FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id_opcion_ivr= '". $IdOpcion ."';";
                if ($ResultadoSQL= $mysqli->query($EliminarOpcion)) {
                    $InActivarClaveExterna ="SET FOREIGN_KEY_CHECKS = 0;";
                    $Ejecutar= $mysqli->query($InActivarClaveExterna);
                    
                    $EliminarAcciones= "DELETE FROM dyalogo_telefonia.dy_opciones_ivrs WHERE id= '". $IdOpcion ."';";
                    if ($ResultadoSQL_2= $mysqli->query($EliminarAcciones)) {
                        $ActivarClaveExterna ="SET FOREIGN_KEY_CHECKS = 1;";
                        $Ejecutar_2= $mysqli->query($ActivarClaveExterna);

                        //Eliminación correcta de todo
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

                }else {
                    //Error en la Eliminación
                    $php_response= array("msg" => "Error");
                    $ErrorConsulta= mysqli_error($mysqli);
                    mysqli_close($mysqli);
                    echo $ErrorConsulta;
                    exit;
                }

            }else{
                //Eliminación correcta flecha
                $php_response= array("msg" => "Ok");
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            }

        }else {
            //Error en la Eliminación
            $php_response= array("msg" => "Error");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }

    }else {
        //Sin Resultados
        $php_response = array("msg" => "Nada");
        mysqli_close($mysqli);
        echo json_encode($php_response);
        exit;
    }
    
} else {
    mysqli_close($mysqli);
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}

?>
