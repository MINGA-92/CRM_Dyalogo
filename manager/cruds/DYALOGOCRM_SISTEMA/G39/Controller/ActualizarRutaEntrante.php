
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$Huesped= $_POST['Huesped'];
$IdEstrategia= $_POST['IdEstrategia'];
$Nombre= $_POST['Nombre'];
$NumeroEntrada= $_POST['NumeroEntrada'];
$NumeroLimite= $_POST['NumeroLimite'];
$ListaNegra= $_POST['ListaNegra'];
$InputPausa= $_POST['InputPausa'];
$ListaFestivos= $_POST['ListaFestivos'];
$GenerarTimbre= $_POST['GenerarTimbre'];

$Actualizar = "UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_Comentari_b= '". $Nombre ."' WHERE ESTPAS_ConsInte__b= '". $IdEstrategia ."';";
if($ResultadoSQL = $mysqli->query($Actualizar)){
    $ActualizarSQL = "UPDATE dyalogo_telefonia.dy_rutas_entrantes SET nombre= '". $Nombre ."', did= '". $NumeroEntrada ."', valida_lista_negra= '". $ListaNegra ."', id_proyecto= '". $Huesped ."', 
    id_lista_festivos= '". $ListaFestivos ."', limite_llamadas= '". $NumeroLimite ."', generar_timbre_antes_contestar='". $GenerarTimbre ."', pausa_antes_contestar='". $InputPausa ."' WHERE id_estpas= '". $IdEstrategia ."';";
    if ($ResultadoSQL = $mysqli->query($ActualizarSQL)) {
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

                //Actualización correcta
                //$php_response = array("msg" => "Ok");
                mysqli_close($mysqli);
                echo json_encode($php_response);
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
        
    }else {
        //Error en la Actualización
        mysqli_close($mysqli);
        $Falla = mysqli_error($mysqli);
        $php_response = array("msg" => "Error", "Falla" => $Falla);
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
