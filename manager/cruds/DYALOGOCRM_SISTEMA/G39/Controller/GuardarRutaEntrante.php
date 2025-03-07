
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$Huesped= $_POST['Huesped'];
$Nombre= $_POST['Nombre'];
$NumeroEntrada= $_POST['NumeroEntrada'];
$NumeroLimite= $_POST['NumeroLimite'];
$ListaNegra= $_POST['ListaNegra'];
$InputPausa= $_POST['InputPausa'];
$ListaFestivos= $_POST['ListaFestivos'];
$GenerarTimbre= $_POST['GenerarTimbre'];
$Estrategia= $_POST['Estrategia'];

$Analoga= '0';
$ManejaHorario= '1';
$HorarioPropio= '1';
$idRutaEntranteHorario= '-1';
$Maestra= '0';
$Contestar= '1';
$GrabacionDobleCanal= '0';


$ActualizarSQL = "UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_Comentari_b= '". $Nombre ."' WHERE ESTPAS_ConsInte__b= '". $Estrategia ."';";
if($ResultadoSQL = $mysqli->query($ActualizarSQL)) {
    //Si Actualización correcta
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_rutas_entrantes(nombre, did, analoga, maneja_horario, valida_lista_negra, id_ruta_entrante_horario, id_proyecto,
    id_lista_festivos, limite_llamadas, horario_propio, generar_timbre_antes_contestar, pausa_antes_contestar, maestra, contestar, grabacion_doble_canal, id_estpas) 
    VALUES ('". $Nombre ."', '". $NumeroEntrada ."', '". $Analoga ."', '". $ManejaHorario ."', '". $ListaNegra ."', '". $idRutaEntranteHorario ."', '". $Huesped ."', '". $ListaFestivos ."', 
    '". $NumeroLimite ."', '". $HorarioPropio ."', '". $GenerarTimbre ."', '". $InputPausa ."', '". $Maestra ."', '". $Contestar ."', '". $GrabacionDobleCanal ."', '". $Estrategia ."');";
    if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {
        $ConsultaSQL= "SELECT * FROM dyalogo_telefonia.dy_rutas_entrantes WHERE did= '". $NumeroEntrada ."';";
        if ($ResultadoSQL= $mysqli->query($ConsultaSQL)) { 
            $CantidadResultados = $ResultadoSQL->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $IdRuta= $FilaResultado['id'];
                }

                //WS
                $ResRutaEntrante= RutaEntrante($IdRuta);
                $php_response= array("msg" => "Ok", "Id" => $IdRuta, "Resp" => $ResRutaEntrante);

                //inserción correcta
                //$php_response= array("msg" => "Ok", "Id" => $IdRuta);
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            }
        }else {
            //Error en la Insercion
            $php_response= array("msg" => "Error");
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

}else {
    //Error en la Actualización
    mysqli_close($mysqli);
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}

?>
