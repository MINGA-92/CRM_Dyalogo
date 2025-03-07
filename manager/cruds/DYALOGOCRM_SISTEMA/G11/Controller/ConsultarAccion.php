
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdAccion = $_POST['IdAccion'];
$DatosAccion= array();
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id= '". $IdAccion ."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
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

            array_push($DatosAccion, array("0"=> $Orden, "1"=> $Accion, "2"=> $ValorAccion, "3"=> $TransEncuesta, "4"=> $Etiqueta, "5"=> $IdAplicacion, "6"=> $Parametros, "7"=> $IdTroncal, "8"=> $IdCampana, "9"=> $IdGrabacion, "10"=> $IdIVR, "11"=> $IdEncuesta, "12"=> $IdAccion));

        }
    
        $php_response = array("msg" => "Ok", "Resultado" => $DatosAccion);
        echo json_encode($php_response);
        mysqli_close($mysqli);
        exit;

    }else{
        //Sin Resultados
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

?>
