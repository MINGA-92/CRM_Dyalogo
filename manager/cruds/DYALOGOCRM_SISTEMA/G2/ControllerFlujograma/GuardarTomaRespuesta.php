
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdIVR= $_POST['IdIVR'];
$IdEstrategia= $_POST['IdEstrategia'];
$IdProyecto= $_POST['IdProyecto'];
$IdOpcion= $_POST['IdOpcion'];
$NombreIVR= $_POST['NombreIVR'];
$NombreOpcion= $_POST['NombreEsfera'];
$NombreInterno= $_POST['NombreInterno'];
$GrabacionDigitos= $_POST['GrabacionDigitos'];
$AceptarDigitos= $_POST['AceptarDigitos'];
$TiempoEspera= $_POST['TiempoEspera'];
$IntentosPermitidos= $_POST['IntentosPermitidos'];
$GrabacionOpcErrada= $_POST['GrabacionOpcErrada'];


//Consultar Orden
$ConsultaOrden = "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id_opcion_ivr= '". $IdOpcion ."';";
if ($ResultadoOrden = $mysqli->query($ConsultaOrden)) {
    $CantidadResultados = $ResultadoOrden->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoOrden->fetch_assoc()) {
            $OrdenEjecucionI= $FilaResultado['orden'];
            $OrdenEjecucion= $OrdenEjecucionI+1;
        }
    }else{
        $OrdenEjecucion= "1";
    }
}else{
    $Falla = mysqli_error($mysqli);
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    echo json_encode($php_response);
    mysqli_close($mysqli);
    exit;
}


//Guardar Toma Respuesta
$InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_ivrs (id_estpas, id_proyecto, nombre_raiz, nombre_interno_ivr, nombre_usuario_ivr, id_audio_toma_digitos, saludo_permite_marcar_digitos, tiempo_espera_digitos, intentos_errados_permitidos, id_audio_opcion_errada, ivr_principal) 
VALUES ('". $IdEstrategia ."', '". $IdProyecto ."', '". $NombreIVR ."', '". $NombreInterno ."', '". $NombreOpcion ."', '". $GrabacionDigitos ."', '". $AceptarDigitos ."', '". $TiempoEspera ."', '". $IntentosPermitidos ."', '". $GrabacionOpcErrada ."', '0');";
if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {  
    $IdTomaDeDigitos = $mysqli->insert_id;
    //Guardar Accion
    $InsercionSQL2= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden, accion, valor_accion, campana_transfiere_encuesta, avanzado_etiqueta, avanzado_id_aplicacion, avanzado_parametros, id_troncal, id_campana, id_audio, id_ivr, id_encuesta) 
    VALUES ('". $IdOpcion ."', '". $OrdenEjecucion ."', '8', 'Captura De Respuesta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '". $IdTomaDeDigitos ."', NULL);";
    if ($ResultadoInsercionSQL2= $mysqli->query($InsercionSQL2)) {
        $IdAccion= $mysqli->insert_id;
        //Actualizar Esfera
        $ActualizarSQL= "UPDATE DYALOGOCRM_SISTEMA.SECCIONES_IVRS SET ID_ACCION= '". $IdAccion ."', NOMBRE_SECCION= '". $NombreOpcion ."' WHERE ID_IVR= '". $IdIVR ."' AND NOMBRE_SECCION= 'TomaDigitos';";
        if ($ResultadoActualizar= $mysqli->query($ActualizarSQL)) {
            
            //WS
            $ResIVR= IVR($IdTomaDeDigitos);
            $php_response= array("msg" => "Ok", "IdTomaDeDigitos" => $IdTomaDeDigitos, "IdAccion" => $IdAccion, "Resp" => $ResIVR);

            //Inserción Correcta
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;
            
        }else{
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

}else{
    //Error en la Insercion
    $php_response= array("msg" => "Error");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}


?>
