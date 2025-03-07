
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdFrom= $_POST['IdFrom'];

$DatosTipoFlecha= array();
$ConsultaTipoFlecha = "SELECT * FROM DYALOGOCRM_SISTEMA.SECCIONES_IVRS WHERE ID_SECCION_IVR = '". $IdFrom ."';";
if($ResultadoTipoFlecha = $mysqli->query($ConsultaTipoFlecha)) {
    $CantidadResultados = $ResultadoTipoFlecha->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoTipoFlecha->fetch_assoc()) {
            $Nombre= $FilaResultado['NOMBRE_SECCION'];
            $TIPO_SECCION= $FilaResultado['TIPO_SECCION'];

            array_push($DatosTipoFlecha, array("0"=> $Nombre, "1"=> $TIPO_SECCION));

        }

        $php_response = array("msg" => "Ok", "DatosTipoFlecha" => $DatosTipoFlecha);
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
