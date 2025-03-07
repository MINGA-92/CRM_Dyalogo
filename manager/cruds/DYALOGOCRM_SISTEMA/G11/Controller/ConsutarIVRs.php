
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdEstrategia = $_POST['IdEstrategia'];
$DatosIVR= array();
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_ivrs WHERE id_estpas= '" .$IdEstrategia. "';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IdIVR= $FilaResultado['id'];
            $NombreInterno= $FilaResultado['nombre_interno_ivr'];
            $NombreUsuario= $FilaResultado['nombre_usuario_ivr'];
            $IdAudioBienvenida= $FilaResultado['id_audio_bienvenida'];
            $IdTomaDigitos= $FilaResultado['id_audio_toma_digitos'];
            $TiempoEspera= $FilaResultado['tiempo_espera_digitos'];
            $NombreRaiz= $FilaResultado['nombre_raiz'];
            $IdProyecto= $FilaResultado['id_proyecto'];
            $IntentosPermitidos= $FilaResultado['intentos_errados_permitidos'];
            $AceptarDigitos= $FilaResultado['saludo_permite_marcar_digitos'];
            

            //Consultar Grabacion De Bienvenida
            $ConsultaBienvenida = "SELECT * FROM dyalogo_telefonia.dy_audios WHERE id= '". $IdAudioBienvenida ."';";
            if ($ResultadoBienvenida = $mysqli->query($ConsultaBienvenida)) {
                $CantidadResultados = $ResultadoBienvenida->num_rows;
                if($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoBienvenida->fetch_assoc()) {
                        $AudioBienvenida= $FilaResultado['nombre'];
                    }
                }else{
                    //Sin Resultados
                    $AudioBienvenida= "N/A - Sin Audio";
                }
            }else{
                $Falla = mysqli_error($mysqli);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            }
            //Consultar Grabacion Toma De Digitos
            $ConsultaDigitos = "SELECT * FROM dyalogo_telefonia.dy_audios WHERE id= '". $IdTomaDigitos ."';";
            if ($ResultadoDigitos = $mysqli->query($ConsultaDigitos)) {
                $CantidadResultados = $ResultadoDigitos->num_rows;
                if($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoDigitos->fetch_assoc()) {
                        $IdAudioDigitos= $IdTomaDigitos;
                        $AudioDigitos= $FilaResultado['nombre'];
                    }
                }else{
                    //Sin Resultados
                    $IdAudioDigitos= "0";
                    $AudioDigitos= "N/A - Sin Audio";
                }
            }else{
                $Falla = mysqli_error($mysqli);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            }

            array_push($DatosIVR, array("0"=> $NombreInterno, "1"=> $NombreUsuario, "2"=> $IdAudioBienvenida, "3"=> $AudioBienvenida, "4"=> $IdAudioDigitos, "5"=> $AudioDigitos, "6"=> $TiempoEspera, "7"=> $NombreRaiz, "8"=> $IdProyecto, "9"=> $IdIVR, "10"=> $AceptarDigitos, "11"=> $IntentosPermitidos));

        }

        $php_response = array("msg" => "Si Existe", "Resultado" => $DatosIVR);
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
