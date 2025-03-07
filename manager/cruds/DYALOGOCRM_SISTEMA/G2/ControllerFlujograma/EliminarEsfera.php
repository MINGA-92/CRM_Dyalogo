
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


$IdEsfera = $_POST['IdEsfera'];

$ConsultaEsfera = "SELECT * FROM DYALOGOCRM_SISTEMA.SECCIONES_IVRS WHERE ID_SECCION_IVR= '". $IdEsfera ."';";
if($ResultadoTipoEsfera = $mysqli->query($ConsultaEsfera)) {
    $CantidadResultados = $ResultadoTipoEsfera->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoTipoEsfera->fetch_assoc()) {
            $IdAccion= $FilaResultado['ID_ACCION'];
        }

        $EliminarEsfera= "DELETE FROM DYALOGOCRM_SISTEMA.SECCIONES_IVRS WHERE ID_SECCION_IVR= '". $IdEsfera ."'";
        if ($ResultadoSQL= $mysqli->query($EliminarEsfera)) {
            
            if(($IdAccion != "") || ($IdAccion != null)){
                $EliminarAccion= "DELETE FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id= '". $IdAccion ."';";
                if ($ResultadoSQL= $mysqli->query($EliminarAccion)) {
                    
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

            }else{
                //Eliminación correcta Esfera
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
