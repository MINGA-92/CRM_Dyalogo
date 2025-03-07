
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);


$IdEstrategia= $_POST['IdEstrategia'];
$IdProyecto= $_POST['IdProyecto'];
$NombreIVR= $_POST['NombreIVR'];
$NombreOpcion= $_POST['NombreOpcion'];
$NombreInterno= $_POST['NombreInterno'];
$GrabacionBienvenida= $_POST['GrabacionBienvenida'];
$GrabacionDigitos= $_POST['GrabacionDigitos'];
$AceptarDigitos= $_POST['AceptarDigitos'];
$TiempoEspera= $_POST['TiempoEspera'];
$IntentosPermitidos= $_POST['IntentosPermitidos'];
$GrabacionOpcErrada= $_POST['GrabacionOpcErrada'];


$ActualizarSQL= "UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_Comentari_b= '". $NombreIVR ."' WHERE ESTPAS_ConsInte__b= '". $IdEstrategia ."';";
if($ResultadoSQL= $mysqli->query($ActualizarSQL)) {
    //Si Actualización correcta
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_ivrs (nombre_interno_ivr, nombre_usuario_ivr, id_audio_bienvenida, id_audio_toma_digitos, tiempo_espera_digitos, nombre_raiz, id_proyecto, id_estpas, saludo_permite_marcar_digitos, intentos_errados_permitidos, id_audio_opcion_errada) VALUES ('". $NombreInterno ."', '". $NombreOpcion ."', '". $GrabacionBienvenida ."', '". $GrabacionDigitos ."', '". $TiempoEspera ."', '". $NombreIVR ."', '". $IdProyecto ."', '". $IdEstrategia ."', '". $AceptarDigitos ."', '". $IntentosPermitidos ."', '". $GrabacionOpcErrada ."');";
    if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {  
        $IdIVR = $mysqli->insert_id;

        //Crear Esferas
        $InsertEsferaInicial= "INSERT INTO DYALOGOCRM_SISTEMA.SECCIONES_IVRS (ID_HUESPED, ID_ESTPAS, ID_IVR, NOMBRE_SECCION, TIPO_SECCION, COORDENADAS, POR_DEFECTO) VALUES ('". $IdProyecto ."', '". $IdEstrategia ."', '". $IdIVR ."', '". $NombreOpcion ."', '8', '-542.0302734374999 120.70000381022595', '0');";
        if($ResultadoSQL= $mysqli->query($InsertEsferaInicial)) {
            $InsertEsferaFinal= "INSERT INTO DYALOGOCRM_SISTEMA.SECCIONES_IVRS (ID_HUESPED, ID_ESTPAS, ID_IVR, NOMBRE_SECCION, TIPO_SECCION, COORDENADAS, POR_DEFECTO) VALUES ('". $IdProyecto ."', '". $IdEstrategia ."', '". $IdIVR ."', 'Final IVR', '9', '420.87207031249994 120.70000381022595', '0');";
            if($ResultadoSQL= $mysqli->query($InsertEsferaFinal)) {

                //WS
                $ResIVR= IVR($IdIVR);
                $php_response= array("msg" => "Ok", "Id" => $IdIVR, "Resp" => $ResIVR);

                //Inserción Correcta
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;

            }else{
                //Error en la Insercion
                $php_response= array("msg" => "Error Insert Esfera");
                $ErrorConsulta= mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo $ErrorConsulta;
                exit;
            }

        }else{
            //Error en la Insercion
            $php_response= array("msg" => "Error Insert Esfera");
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
