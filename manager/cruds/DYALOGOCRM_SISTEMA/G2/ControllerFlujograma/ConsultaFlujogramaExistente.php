
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdIVR= $_POST['IdIVR'];

$DatosEsferasExistentes= array();
$ConsultaExistentes = "SELECT * FROM DYALOGOCRM_SISTEMA.SECCIONES_IVRS WHERE ID_IVR= '". $IdIVR ."' AND POR_DEFECTO = 0;";
if($ResultadoExistentes = $mysqli->query($ConsultaExistentes)) {
    $CantidadResultados = $ResultadoExistentes->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoExistentes->fetch_assoc()) {
            $Id= $FilaResultado['ID_SECCION_IVR'];
            $NOMBRE= $FilaResultado['NOMBRE_SECCION'];
            $TIPO_SECCION= $FilaResultado['TIPO_SECCION'];
            $COORDENADAS= $FilaResultado['COORDENADAS'];

            array_push($DatosEsferasExistentes, array("0"=> $NOMBRE, "1"=> $TIPO_SECCION, "2"=> $COORDENADAS, "3"=> $Id));

        }

        $DatosFlechas= array();
        $ConsultaFlecha = "SELECT * FROM DYALOGOCRM_SISTEMA.CONEXIONES_IVRS WHERE ID_IVR= '". $IdIVR ."';";
        if ($ResultadoFlecha = $mysqli->query($ConsultaFlecha)) {
            $CantidadResultados = $ResultadoFlecha->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoFlecha->fetch_assoc()) {
                    $IdFlecha = $FilaResultado['ID_CONEXIONES_IVRS'];
                    $NombreFlecha = $FilaResultado['NOMBRE'];
                    $Desde = $FilaResultado['ID_DESDE'];
                    $Hasta = $FilaResultado['ID_HASTA'];
                    $CoordenadasFlecha= $FilaResultado['COORDENADAS'];
                    $PuertoInicial = $FilaResultado['DE_PUERTO'];
                    $PuertoFinal = $FilaResultado['A_PUERTO'];
                    $TipoFlecha = $FilaResultado['POR_DEFECTO'];

                    array_push($DatosFlechas, array("0"=> $NombreFlecha, "1"=> $Desde, "2"=> $Hasta, "3"=> $CoordenadasFlecha, "4"=> $PuertoInicial, "5"=> $PuertoFinal, "6"=> $TipoFlecha, "7"=> $IdFlecha));

                }

                $php_response = array("DatosEsferasExistentes" => $DatosEsferasExistentes, "DatosFlechas" => $DatosFlechas);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;

            }else{
                //Sin Conexiones
                $php_response = array("DatosEsferasExistentes" => $DatosEsferasExistentes);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            }
        }else{
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error", "Falla" => $Falla);
            echo json_encode($php_response);
            exit;
        }

    }else {
        //Sin Resultados
        $php_response = array("msg" => "Nada");
        mysqli_close($mysqli);
        echo json_encode($php_response);
        exit;
    }
}else{
    mysqli_close($mysqli);
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}


?>
