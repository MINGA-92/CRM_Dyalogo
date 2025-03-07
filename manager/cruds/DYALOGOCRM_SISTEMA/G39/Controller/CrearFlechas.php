
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$IdEstrategia= $_POST['IdEstrategia'];
$IdFlujograma= $_POST['IdFlujograma'];
$TipoAccion= $_POST['TipoAccion'];
$IdAccion= $_POST['IdAccion'];
$TipoAccionFuera= $_POST['TipoAccionFuera'];
$IdAccionFuera= $_POST['IdAccionFuera'];


function CrearFlecha($mysqli, $IdDesde, $IdHasta, $IdFlujograma, $PosicionI){
    
    
    //Crear
    $InsertFlecha= "INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Deshabilitado_b) 
    VALUES ('Conector', '/', '". $IdDesde ."', '". $IdHasta ."', '". $IdFlujograma ."', '". $PosicionI ."', 'L', '-1');";
    $ResultadoSQL= $mysqli->query($InsertFlecha);
    if($ResultadoSQL) {

    }else{
        //Error en la Insercion
        $ErrorConsulta= mysqli_error($mysqli);
        $php_response= array("msg" => "Error Insert Flecha", "Error" => $ErrorConsulta);
        mysqli_close($mysqli);
        echo json_encode($php_response);
        exit;
    }
    
}


//Eliminar Existentes
$ConsultaExistentes= "SELECT * FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b= '". $IdEstrategia ."' AND ESTCON_ConsInte__ESTRAT_b= '". $IdFlujograma ."';";
if ($ResultadoExistentes= $mysqli->query($ConsultaExistentes)) { 
    $CantidadResultados= $ResultadoExistentes->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoExistentes->fetch_assoc()) {
            $IdExistentes= $FilaResultado['ESTCON_ConsInte__b'];
            $EliminarExistente= "DELETE FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__b= '". $IdExistentes ."';";
            if ($ResultadoSQL= $mysqli->query($EliminarExistente)) {

            }else{
                //Error en la Eliminación
                $php_response= array("msg" => "Error Delete");
                $ErrorConsulta= mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo $ErrorConsulta;
                exit;
            }
        }
    }
}else {
    //Error en la Consulta
    $php_response = array("msg" => "Error", "Falla" => $Falla);
    $ErrorConsulta= mysqli_error($mysqli);
    echo json_encode($ErrorConsulta);
    mysqli_close($mysqli);
    exit;
}



//Dentro De Horario
if($TipoAccion == "IVR") {
    $ConsultaIVR= "SELECT * FROM dyalogo_telefonia.dy_ivrs WHERE id= '". $IdAccion ."';";
    if ($ResultadoIVR= $mysqli->query($ConsultaIVR)) { 
        $CantidadResultados= $ResultadoIVR->num_rows;
        if($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoIVR->fetch_assoc()) {
                $IdEsfera= $FilaResultado['id_estpas'];
            }
        }
    }else {
        //Error en la Consulta
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        $ErrorConsulta= mysqli_error($mysqli);
        echo json_encode($ErrorConsulta);
        mysqli_close($mysqli);
        exit;
    }
}else{

    $ConsultaCamp= "SELECT * FROM dyalogo_telefonia.dy_campanas WHERE id= '". $IdAccion ."';";
    if ($ResultadoCamp= $mysqli->query($ConsultaCamp)) { 
        $CantidadResultados= $ResultadoCamp->num_rows;
        if($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoCamp->fetch_assoc()) {
                $IdCamp= $FilaResultado['id_campana_crm'];

                $ConsultaCamp_2= "SELECT * FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b= '". $IdCamp ."';";
                if ($ResultadoCamp_2= $mysqli->query($ConsultaCamp_2)) { 
                    $CantidadResultados= $ResultadoCamp_2->num_rows;
                    if($CantidadResultados > 0) {
                        while ($FilaResultado = $ResultadoCamp_2->fetch_assoc()) {
                            $IdEsfera= $FilaResultado['ESTPAS_ConsInte__b'];
                        }
                    }
                }else {
                    //Error en la Consulta
                    $php_response = array("msg" => "Error", "Falla" => $Falla);
                    $ErrorConsulta= mysqli_error($mysqli);
                    echo json_encode($ErrorConsulta);
                    mysqli_close($mysqli);
                    exit;
                }
                
            }
        }
    }else {
        //Error en la Consulta
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        $ErrorConsulta= mysqli_error($mysqli);
        echo json_encode($ErrorConsulta);
        mysqli_close($mysqli);
        exit;
    }

}
CrearFlecha($mysqli, $IdEstrategia, $IdEsfera, $IdFlujograma, 'R');


//Fuera De Horario
if($TipoAccionFuera == "IVR") {
    $ConsultaIVRFuera= "SELECT * FROM dyalogo_telefonia.dy_ivrs WHERE id= '". $IdAccionFuera ."';";
    if ($ResultadoIVRFuera= $mysqli->query($ConsultaIVRFuera)) { 
        $CantidadResultados= $ResultadoIVRFuera->num_rows;
        if($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoIVRFuera->fetch_assoc()) {
                $IdEsfera= $FilaResultado['id_estpas'];
            }
        }
    }else {
        //Error en la Consulta
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        $ErrorConsulta= mysqli_error($mysqli);
        echo json_encode($ErrorConsulta);
        mysqli_close($mysqli);
        exit;
    }
}else{

    $ConsultaCampFuera= "SELECT * FROM dyalogo_telefonia.dy_campanas WHERE id= '". $IdAccionFuera ."';";
    if ($ResultadoCampFuera= $mysqli->query($ConsultaCampFuera)) { 
        $CantidadResultados= $ResultadoCampFuera->num_rows;
        if($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoCampFuera->fetch_assoc()) {
                $IdCamp_2= $FilaResultado['id_campana_crm'];

                $ConsultaCampFuera_2= "SELECT * FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b= '". $IdCamp_2 ."';";
                if ($ResultadoCampFuera_2= $mysqli->query($ConsultaCampFuera_2)) { 
                    $CantidadResultados= $ResultadoCampFuera_2->num_rows;
                    if($CantidadResultados > 0) {
                        while ($FilaResultado = $ResultadoCampFuera_2->fetch_assoc()) {
                            $IdEsfera= $FilaResultado['ESTPAS_ConsInte__b'];
                        }
                    }
                }else {
                    //Error en la Consulta
                    $php_response = array("msg" => "Error", "Falla" => $Falla);
                    $ErrorConsulta= mysqli_error($mysqli);
                    echo json_encode($ErrorConsulta);
                    mysqli_close($mysqli);
                    exit;
                }
                
            }
        }
    }else {
        //Error en la Consulta
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        $ErrorConsulta= mysqli_error($mysqli);
        echo json_encode($ErrorConsulta);
        mysqli_close($mysqli);
        exit;
    }

}
CrearFlecha($mysqli, $IdEstrategia, $IdEsfera, $IdFlujograma, 'B');


//Inserción correcta
$php_response= array("msg" => "Ok");
echo json_encode($php_response);
mysqli_close($mysqli);
exit;

?>
