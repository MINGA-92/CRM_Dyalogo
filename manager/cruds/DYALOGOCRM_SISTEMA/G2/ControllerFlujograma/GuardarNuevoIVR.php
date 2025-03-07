
<?php

include_once(__DIR__."../../../../../pages/conexion.php");
include_once(__DIR__."../../../../../global/WSCoreClient.php");
$mysqli = new  mysqli($DB_Name, $DB_User, $DB_Pass);

$NombreInterno= $_POST['NombreInterno'];
$IdEstrategia= $_POST['IdEstrategia'];
$NombreIVR= $_POST['NombreIVR'];
$IdProyecto= $_POST['IdHuesped'];


$ActualizarSQL = "UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_Comentari_b= '". $NombreIVR ."' WHERE ESTPAS_ConsInte__b= '". $IdEstrategia ."';";
if($ResultadoSQL = $mysqli->query($ActualizarSQL)) {
    //Si Actualización correcta
    $InsercionSQL= "INSERT INTO dyalogo_telefonia.dy_ivrs (nombre_interno_ivr, nombre_raiz, id_proyecto, id_estpas, ivr_principal) VALUES ('". $NombreInterno ."', '". $NombreIVR ."', '". $IdProyecto ."', '". $IdEstrategia ."', '1');";
    if ($ResultadoSQL= $mysqli->query($InsercionSQL)) {
        $SelectIdIVR= "SELECT @@IDENTITY AS 'IdIVR';";
        if ($ResultadoIVR= $mysqli->query($SelectIdIVR)) {
            $FilaResultado= $ResultadoIVR->fetch_array();
            $IdIVR= $FilaResultado['IdIVR'];

            $InsercionSQL_2= "INSERT INTO dyalogo_telefonia.dy_opciones_ivrs (id_ivr, nombre_usuario_opcion, opcion_por_defecto, valida) VALUES ('". $IdIVR ."', '/', '0', '1');";
            if ($ResultadoSQL_2= $mysqli->query($InsercionSQL_2)) {
                $SelectIdOpcion= "SELECT @@IDENTITY AS 'IdOpcion';";
                if ($ResultadoOpcion= $mysqli->query($SelectIdOpcion)) {
                    $FilaResultado_2= $ResultadoOpcion->fetch_array();
                    $IdOpcion= $FilaResultado_2['IdOpcion'];
                    /*
                    //Web Service
                    $ResIVR= IVR($IdIVR);
                    $php_response= array("msg" => "Ok", "IdIVR" => $IdIVR, "IdOpcion" => $IdOpcion, "Resp" => $ResIVR);
                    */

                    //Inserción correcta
                    $php_response= array("msg" => "Ok", "IdIVR" => $IdIVR, "IdOpcion" => $IdOpcion);
                    echo json_encode($php_response);
                    mysqli_close($mysqli);
                    exit;

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
                $php_response= array("msg" => "Error Insert Opcion");
                $ErrorConsulta= mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo $ErrorConsulta;
                exit;
            }

        }else{
            //Error en la Consulta
            $php_response= array("msg" => "Error Consulta IdIVR");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }
        
    }else{
        //Error en la Insercion
        $php_response= array("msg" => "Error Insert IVR");
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
