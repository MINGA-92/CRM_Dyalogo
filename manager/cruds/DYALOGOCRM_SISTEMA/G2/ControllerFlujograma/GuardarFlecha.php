
<?php

include_once(__DIR__."../../../../../pages/conexion.php");


$IdIVR= $_POST['IdIVR'];
$IdHuesped= $_POST['IdHuesped'];
$TipoFlecha= $_POST['TipoFlecha'];
$IdEstrategia= $_POST['IdEstrategia'];
$StringFlujograma= $_POST['StringFlujograma'];

//Esta funcion traduce lo que llega como strin a un json
function traducirFljujograma($stringFlujograma){

    $stringFlujograma = str_replace("\\n", "", $stringFlujograma);
    $stringFlujograma = str_replace("\\r", "", $stringFlujograma);
    $stringFlujograma = str_replace('\\', "", $stringFlujograma);
    $jsonFlujo = json_decode($stringFlujograma);

    return $jsonFlujo;
}

$JsonFlujograma = traducirFljujograma($StringFlujograma);
foreach ($JsonFlujograma->linkDataArray as $key => $value) {
    
    $Coordenadas= json_encode($value->points);
    $IdDesde= json_encode($value->from);
    $IdHasta= json_encode($value->to);
    $DePuerto= json_encode($value->fromPort);
    $APuerto= json_encode($value->toPort);
    $DePuerto= str_replace('"', '', $DePuerto);
    $APuerto= str_replace('"', '', $APuerto);

    
    //Se Verifica Que Exista La Misma Conexión
    $sqlExiste = "SELECT * FROM DYALOGOCRM_SISTEMA.CONEXIONES_IVRS WHERE ID_DESDE = {$value->from} AND ID_HASTA = {$value->to};";
    $resExiste = $mysqli->query($sqlExiste);

    //Si Es Mayor A Cero Significa Que Existe
    if($resExiste && $resExiste->num_rows > 0){
        $ActualizarConexion = "UPDATE DYALOGOCRM_SISTEMA.CONEXIONES_IVRS SET DE_PUERTO= '". $DePuerto ."', A_PUERTO= '". $APuerto ."', COORDENADAS= '". $Coordenadas ."' WHERE ID_DESDE= '". $IdDesde ."' AND ID_HASTA= '". $IdHasta ."'";
        if($ResultadoSQL= $mysqli->query($ActualizarConexion)) {
            
        }else{
            $php_response= array("msg" => "Error Update Flecha");
            $ErrorConsulta= mysqli_error($mysqli);
            mysqli_close($mysqli);
            echo $ErrorConsulta;
            exit;
        }

    }else{
        if($TipoFlecha == "1"){

            //Se Verifica Que No Exista Otra Conexión
            $ConsultaFlecha= "SELECT * FROM DYALOGOCRM_SISTEMA.CONEXIONES_IVRS WHERE ID_DESDE = {$value->from};";
            if($ResultadoFlecha = $mysqli->query($ConsultaFlecha)) {
                if($ResultadoFlecha->num_rows > 0) {
                    
                    $php_response= array("msg" => "Ya Existe");
                    echo json_encode($php_response);
                    mysqli_close($mysqli);
                    exit;
                
                }else{

                    $ConsultaAccion = "SELECT * FROM DYALOGOCRM_SISTEMA.SECCIONES_IVRS WHERE ID_SECCION_IVR = '". $IdDesde ."';";
                    if($ResultadoAccion = $mysqli->query($ConsultaAccion)) {
                        if($ResultadoAccion->num_rows > 0) {
                            while ($FilaResultado = $ResultadoAccion->fetch_assoc()) {
                                $IdAccion= $FilaResultado['ID_ACCION'];
                                
                                $ConsultaOpcion = "SELECT * FROM dyalogo_telefonia.dy_acciones_opcion_ivrs WHERE id= '". $IdAccion ."';";
                                if($ResultadoOpcion = $mysqli->query($ConsultaOpcion)) {
                                    if($ResultadoOpcion->num_rows > 0) {
                                        while ($FilaResultado = $ResultadoOpcion->fetch_assoc()) {
                                            $IdOpcion= $FilaResultado['id_opcion_ivr'];
                                        }
                                        
                                        $InsertFlecha= "INSERT INTO DYALOGOCRM_SISTEMA.CONEXIONES_IVRS (ID_ESTPAS, ID_IVR, ID_OPCION, NOMBRE, ID_DESDE, ID_HASTA, COORDENADAS, DE_PUERTO, A_PUERTO, POR_DEFECTO) 
                                        VALUES ('". $IdEstrategia ."', '". $IdIVR ."', '". $IdOpcion ."', '/', '". $IdDesde ."',  '". $IdHasta ."', '". $Coordenadas ."', '". $DePuerto ."', '". $APuerto ."', '". $TipoFlecha ."');";
                                        if ($ResultadoSQL= $mysqli->query($InsertFlecha)) {
                                            $IdFlecha = $mysqli->insert_id;
                                            
                                            //Inserción correcta
                                            $php_response= array("msg" => "Ok");
                                            echo json_encode($php_response);
                                            mysqli_close($mysqli);
                                            exit;

                                        }else{
                                            //Error en la Insercion
                                            $php_response= array("msg" => "Error Insert Flecha");
                                            $ErrorConsulta= mysqli_error($mysqli);
                                            mysqli_close($mysqli);
                                            echo $ErrorConsulta;
                                            exit;
                                        }

                                    }else{
                                        //Sin Resultados
                                        $php_response = array("msg" => "Nada");
                                        echo json_encode($php_response);
                                        exit;
                                    }
                                } 
                            }
                        }else{
                            //Sin Resultados
                            $php_response = array("msg" => "Nada Accion");
                            echo json_encode($php_response);
                            exit;
                        }
                    }
                    
                }
            }

        }else{

            $InsertFlecha= "INSERT INTO DYALOGOCRM_SISTEMA.CONEXIONES_IVRS (ID_ESTPAS, ID_IVR, NOMBRE, ID_DESDE, ID_HASTA, COORDENADAS, DE_PUERTO, A_PUERTO, POR_DEFECTO) 
            VALUES ('". $IdEstrategia ."', '". $IdIVR ."', '/', '". $IdDesde ."',  '". $IdHasta ."', '". $Coordenadas ."', '". $DePuerto ."', '". $APuerto ."', '". $TipoFlecha ."');";
            if ($ResultadoSQL= $mysqli->query($InsertFlecha)) {
                $IdFlecha = $mysqli->insert_id;
                //Inserción correcta
                $php_response= array("msg" => "Ok");
                echo json_encode($php_response);
                mysqli_close($mysqli);
                exit;
            }else{
                //Error en la Insercion
                $php_response= array("msg" => "Error Insert Flecha");
                $ErrorConsulta= mysqli_error($mysqli);
                mysqli_close($mysqli);
                echo $ErrorConsulta;
                exit;
            }
        }

    }
    
}


//Actualizacion Correcta
$php_response= array("msg" => "Actualizado Ok");
echo json_encode($php_response);
mysqli_close($mysqli);
exit;


?>

