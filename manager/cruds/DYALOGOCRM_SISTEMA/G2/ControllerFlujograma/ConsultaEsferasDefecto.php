
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$DatosEsferasDefecto= array();
$ConsultaDefecto = "SELECT * FROM DYALOGOCRM_SISTEMA.SECCIONES_IVRS WHERE POR_DEFECTO = 1;";
if($ResultadoDefecto = $mysqli->query($ConsultaDefecto)) {
    $CantidadResultados = $ResultadoDefecto->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoDefecto->fetch_assoc()) {
            $Id= $FilaResultado['ID_SECCION_IVR'];
            $Nombre= $FilaResultado['NOMBRE_SECCION'];
            $TIPO_SECCION= $FilaResultado['TIPO_SECCION'];
            $COORDENADAS= $FilaResultado['COORDENADAS'];

            array_push($DatosEsferasDefecto, array("0"=> $Nombre, "1"=> $TIPO_SECCION, "2"=> $COORDENADAS, "3"=> $Id));

        }

        $php_response = array("DatosEsferasDefecto" => $DatosEsferasDefecto);
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;


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
