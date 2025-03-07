
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdEsfera= $_POST['IdEsfera'];

$DatosAccion= array();
$ConsultaEsfera = "SELECT * FROM DYALOGOCRM_SISTEMA.SECCIONES_IVRS WHERE ID_SECCION_IVR = '". $IdEsfera ."';";
if($ResultadoEsfera = $mysqli->query($ConsultaEsfera)) {
    $CantidadResultados = $ResultadoEsfera->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoEsfera->fetch_assoc()) {
            $IdAccion= $FilaResultado['ID_ACCION'];
            $NombreAccion= $FilaResultado['NOMBRE_SECCION'];
        }

        $ConsultaAccion = "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id= '". $IdAccion ."';";
        if ($ResultadoAccion = $mysqli->query($ConsultaAccion)) {
            $CantidadResultados = $ResultadoAccion->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoAccion->fetch_assoc()) {
                    $IdAccion= $FilaResultado['id'];
                    $Orden= $FilaResultado['orden'];
                    $Accion= $FilaResultado['accion'];
                    $ValorAccion= $FilaResultado['valor_accion'];
                    $TransEncuesta= $FilaResultado['campana_transfiere_encuesta'];
                    $Etiqueta= $FilaResultado['avanzado_etiqueta'];
                    $IdAplicacion= $FilaResultado['avanzado_id_aplicacion'];
                    $Parametros= $FilaResultado['avanzado_parametros'];
                    $IdTroncal= $FilaResultado['id_troncal'];
                    $IdCampana= $FilaResultado['id_campana'];
                    $IdGrabacion= $FilaResultado['id_audio'];
                    $IdIVR= $FilaResultado['id_ivr'];
                    $IdEncuesta= $FilaResultado['id_encuesta'];
                    
                }

                array_push($DatosAccion, array("0"=> $Orden, "1"=> $Accion, "2"=> $ValorAccion, "3"=> $TransEncuesta, "4"=> $Etiqueta, "5"=> $IdAplicacion, "6"=> $Parametros, "7"=> $IdTroncal, "8"=> $IdCampana, "9"=> $IdGrabacion, "10"=> $IdIVR, "11"=> $IdEncuesta, "12"=> $IdAccion, "13"=> $NombreAccion));
            
                $php_response = array("msg" => "Ok", "DatosAccion" => $DatosAccion);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;

            }else{
                //Sin Resultados Accion
                $php_response = array("msg" => "Nada");
                echo json_encode($php_response);
                exit;
            }
        }else{
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;
        }

    }else {
        //Sin Resultados Esfera
        $php_response = array("msg" => "Nada Esfera");
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
