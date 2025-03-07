

<?php

include_once(__DIR__."../../../../../pages/conexion.php");


if(isset($_POST['ConsultaFinal'])){
    
    $Consulta= $_POST['ConsultaFinal'];
    $Cantidad= strlen($Consulta);
    for ($i=0; $i < $Cantidad; $i++) {
        $Letra= $Consulta[$i];
        if($Letra == "\\") {
            $NuevaConsulta= str_replace($Letra, "", $Consulta);
        }
    }

    //Consultar Reporte
    $ConsultaFinal= $NuevaConsulta;
    $ArrayReporte = array();
    if ($ResultadoSQL_2 = $mysqli->query($ConsultaFinal)) {
        $CantidadResultados_2 = $ResultadoSQL_2->num_rows;
        if($CantidadResultados_2 > 0) {
            while ($FilaResultado = $ResultadoSQL_2->fetch_assoc()) {
                array_push($ArrayReporte, array("0" => $FilaResultado));
                
            }
            
            //Si realizo Consulta
            $php_response = array("msg" => "Ok", "Respuesta" => $ArrayReporte);
            echo json_encode($php_response);
            mysqli_close($mysqli);
            exit;

        }else{
            $ErrorConsulta = mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
            exit;
        }
    }else{
        //Sin Resultados
        $php_response = array("msg" => "Nada");
        mysqli_close($mysqli);
        echo json_encode($php_response);
        exit;
    }

}else{

    $IdAccion= $_POST['IdAccion'];
    $IdUsuario= $_POST['IdUsuario'];

    //Consulta Accion
    $ConsultaSQL= "SELECT * FROM DYALOGOCRM_SISTEMA.TARPRO WHERE TARPRO_ConsInte__b= '". $IdAccion ."' AND TARPRO_ConsInte__USUARI____b= '". $IdUsuario ."';";
    if($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ConsultaGuardada= $FilaResultado['TARPRO_Consulta_sql_b'];
            }

            $php_response = array("msg" => "Ok", "Respuesta" => $ConsultaGuardada);
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

}

?>
