
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdTomaDigitos= $_POST['IdTomaDigitos'];
$DatosRespuesta= array();
$ConsultaRespuesta = "SELECT * FROM dyalogo_telefonia.dy_ivrs WHERE id= '". $IdTomaDigitos ."';";
if($ResultadoRespuesta = $mysqli->query($ConsultaRespuesta)) {
    $CantidadResultados = $ResultadoRespuesta->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoRespuesta->fetch_assoc()) {
            $NombreRespuesta= $FilaResultado['nombre_usuario_ivr'];
            $GrabacionDigitos= $FilaResultado['id_audio_toma_digitos'];
            $AceptarDigitos= $FilaResultado['saludo_permite_marcar_digitos'];
            $TiempoEspera= $FilaResultado['tiempo_espera_digitos'];
            $IntentosPermitidos= $FilaResultado['intentos_errados_permitidos'];
            $GrabacionOpcErrada= $FilaResultado['id_audio_opcion_errada'];
        }

        array_push($DatosRespuesta, array("0"=> $NombreRespuesta, "1"=> $GrabacionDigitos, "2"=> $AceptarDigitos, "3"=> $TiempoEspera, "4"=> $IntentosPermitidos, "5"=> $GrabacionOpcErrada));

        $php_response = array("msg" => "Ok", "DatosRespuesta" => $DatosRespuesta);
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
