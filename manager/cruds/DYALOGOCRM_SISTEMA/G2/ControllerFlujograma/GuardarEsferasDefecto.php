
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdIVR= $_POST['IdIVR'];
$IdOpcion= $_POST['IdOpcion'];
$IdHuesped= $_POST['IdHuesped'];
$IdEstrategia= $_POST['IdEstrategia'];

$InsercionAccion= "INSERT INTO dyalogo_telefonia.dy_acciones_opcion_ivrs (id_opcion_ivr, orden) VALUES ('". $IdOpcion ."', '1');";
if ($ResultadoSQL= $mysqli->query($InsercionAccion)) {
    $SelectIdAccion= "SELECT @@IDENTITY AS 'IdAccion';";
    if ($ResultadoOpcion= $mysqli->query($SelectIdAccion)) {
        $FilaResultado= $ResultadoOpcion->fetch_array();
        $IdAccion= $FilaResultado['IdAccion'];

        //GuardarEsferasDefecto
        $InsercionInicial= "INSERT INTO DYALOGOCRM_SISTEMA.SECCIONES_IVRS (ID_HUESPED, ID_ESTPAS, ID_IVR, NOMBRE_SECCION, TIPO_SECCION, COORDENADAS, POR_DEFECTO) VALUES ('". $IdHuesped ."', '". $IdEstrategia ."', '". $IdIVR ."', 'Inicio IVR', '8', '28.8720703125 25.5', '0');";
        if ($ResultadoSQL2= $mysqli->query($InsercionInicial)) {
            $InsercionFinal= "INSERT INTO DYALOGOCRM_SISTEMA.SECCIONES_IVRS (ID_HUESPED, ID_ESTPAS, ID_IVR, NOMBRE_SECCION, TIPO_SECCION, COORDENADAS, POR_DEFECTO) VALUES ('". $IdHuesped ."', '". $IdEstrategia ."', '". $IdIVR ."', 'Final IVR', '9', '107.8720703125 25.5', '0');";
            if ($ResultadoSQL3= $mysqli->query($InsercionFinal)) {
                
                //Inserción correcta
                $php_response= array("msg" => "Ok");
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            
            }else{
                //Error en la Insercion
                $php_response= array("msg" => "Error Insert Acción");
                $ErrorConsulta= mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo $ErrorConsulta;
                exit;
            }
        
        }else{
            //Error en la Insercion
            $php_response= array("msg" => "Error Insert Acción");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }

    }else{
        //Error en la Consulta
        $php_response= array("msg" => "Error Consulta IdOpcion");
        $ErrorConsulta= mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo $ErrorConsulta;
        exit;
    }

}else{
    //Error en la Insercion
    $php_response= array("msg" => "Error Insert Acción");
    $ErrorConsulta= mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo $ErrorConsulta;
    exit;
}
        
?>
