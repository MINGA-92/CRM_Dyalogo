
<?php

$URL= "../../../pages/conexion.php";
include_once($URL);


if(!isset($_POST['TipoFiltro'])){
    
    //Consultar Id Usuario
    $IdConsultar = $_POST['IdConsultar'];
    $ConsultaID = "SELECT * FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_UsuaCBX___b = '". $IdConsultar ."';";
    if($ResultadoID = $mysqli->query($ConsultaID)) {
        $CantidadResultados = $ResultadoID->num_rows;
        if($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoID->fetch_assoc()) {
                $IdAgente= $FilaResultado['USUARI_ConsInte__b'];
            }

            //Consultar Calificaciones
            $ArrayCalificacion= array();
            $ConsultaSQL = "SELECT * FROM DYALOGOCRM_SISTEMA.CALHIS WHERE CALHIS_ConsInte__USUARI_Age_b= '". $IdAgente ."';";
            if($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
                $CantidadResultados = $ResultadoSQL->num_rows;
                if($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $Id= $FilaResultado['CALHIS_ConsInte__b'];
                        $ConsInte__PROYEC_b= $FilaResultado['CALHIS_ConsInte__PROYEC_b'];
                        $ConsInte__GUION__b= $FilaResultado['CALHIS_ConsInte__GUION__b'];
                        $IdGestion_b= $FilaResultado['CALHIS_IdGestion_b'];
                        $FechaGestion_b= $FilaResultado['CALHIS_FechaGestion_b'];
                        $ConsInte__USUARI_Age_b= $FilaResultado['CALHIS_ConsInte__USUARI_Age_b'];
                        $DatoPrincipalScript_b= $FilaResultado['CALHIS_DatoPrincipalScript_b'];
                        $DatoSecundarioScript_b= $FilaResultado['CALHIS_DatoSecundarioScript_b'];
                        $FechaEvaluacion_b= $FilaResultado['CALHIS_FechaEvaluacion_b'];
                        $ConsInte__USUARI_Cal_b= $FilaResultado['CALHIS_ConsInte__USUARI_Cal_b'];
                        $Calificacion_b= $FilaResultado['CALHIS_Calificacion_b'];
                        $ComentCalidad_b= $FilaResultado['CALHIS_ComentCalidad_b'];
                        $ComentAgente_b= $FilaResultado['CALHIS_ComentAgente_b'];
                        $LinkCalificacion= $FilaResultado['CALHIS_LinkCalificacion'];

                        //Consulta Nombre Proyecto
                        $ConsultaProyecto = "SELECT * FROM DYALOGOCRM_SISTEMA.PROYEC WHERE PROYEC_ConsInte__b= '". $ConsInte__PROYEC_b ."';";
                        if($ResultadoProyecto = $mysqli->query($ConsultaProyecto)) {
                            $CantidadResultados = $ResultadoProyecto->num_rows;
                            if($CantidadResultados > 0) {
                                while ($FilaResultado = $ResultadoProyecto->fetch_assoc()) {
                                    $IdProyecto= $ConsInte__PROYEC_b;
                                    $NombreProyecto= $FilaResultado['PROYEC_NomProyec_b'];
                                }
                            }else{
                                $IdProyecto= "";
                                $NombreProyecto= "No Registrado";
                            }

                            //Consulta Nombre Guion
                            $ConsultaGuion = "SELECT * FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b= '". $ConsInte__GUION__b ."';";
                            if($ResultadoGuion = $mysqli->query($ConsultaGuion)) {
                                $CantidadResultados = $ResultadoGuion->num_rows;
                                if($CantidadResultados > 0) {
                                    while ($FilaResultado = $ResultadoGuion->fetch_assoc()) {   
                                        $NombreGuion= $FilaResultado['GUION__Nombre____b'];
                                        $NumeroG= $ConsInte__GUION__b;
                                    }
                                }else{
                                    $NombreGuion= "No Registrado";
                                    $NumeroG= $ConsInte__GUION__b;
                                }
                                
                                //Consulta Gestion
                                $ConsultaGestion= "SELECT * FROM DYALOGOCRM_WEB.G". $NumeroG ." WHERE G". $NumeroG ."_ConsInte__b = '". $IdGestion_b ."';";
                                if($ResultadoGestion = $mysqli->query($ConsultaGestion)) {
                                    $CantidadResultados = $ResultadoGestion->num_rows;
                                    if($CantidadResultados > 0) {
                                        while ($FilaResultado = $ResultadoGestion->fetch_assoc()) {   
                                            $NumeroGestion= $FilaResultado['G'. $NumeroG .'_DatoContacto'];
                                        }
                                    }else{
                                        $NumeroGestion= "No Registrado";
                                    }

                                    //Todo Ok
                                    array_push($ArrayCalificacion, array("0" => $Id, "1" => $NombreProyecto, "2" => $NombreGuion, "3" => $NumeroGestion, "4" => $FechaGestion_b, 
                                    "5" => $ConsInte__USUARI_Age_b, "6" => $DatoPrincipalScript_b, "7" => $DatoSecundarioScript_b, "8" => $FechaEvaluacion_b, "9" => $ConsInte__USUARI_Cal_b, 
                                    "10" => $Calificacion_b, "11" => $ComentCalidad_b, "12" => $ComentAgente_b, "13" => $LinkCalificacion, "14" => $IdProyecto));
                                    

                                }else{
                                    mysqli_close($mysqli);
                                    $Falla = mysqli_error($mysqli);
                                    $php_response = array("msg" => "Error Gestion", "Falla" => $Falla);
                                    echo json_encode($php_response);
                                    exit;
                                }

                            }else{
                                mysqli_close($mysqli);
                                $Falla = mysqli_error($mysqli);
                                $php_response = array("msg" => "Error Guion", "Falla" => $Falla);
                                echo json_encode($php_response);
                                exit;
                            }

                        }else{
                            mysqli_close($mysqli);
                            $Falla = mysqli_error($mysqli);
                            $php_response = array("msg" => "Error Proyecto", "Falla" => $Falla);
                            echo json_encode($php_response);
                            exit;
                        }

                    }

                    $php_response = array("msg" => "Ok", "Resultado" => $ArrayCalificacion);
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

            }else {
                mysqli_close($mysqli);
                $Falla = mysqli_error($mysqli);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                echo json_encode($php_response);
                exit;
            }

        }else {
            //Sin Resultados
            $php_response = array("msg" => "Nada");
            mysqli_close($mysqli);
            echo json_encode($php_response);
            exit;
        }

    }else {
        mysqli_close($mysqli);
        $Falla = mysqli_error($mysqli);
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        echo json_encode($php_response);
        exit;
    }

}else{

    //Consultar Id Usuario
    $IdConsultar = $_POST['IdAgente'];
    $ConsultaID = "SELECT * FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_UsuaCBX___b = '". $IdConsultar ."';";
    if($ResultadoID = $mysqli->query($ConsultaID)) {
        $CantidadResultados = $ResultadoID->num_rows;
        if($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoID->fetch_assoc()) {
                $IdAgente= $FilaResultado['USUARI_ConsInte__b'];
            } 

            //Consultar Calificaciones
            $ArrayCalificacion= array();
            $TipoFiltro = $_POST['TipoFiltro'];
            if($TipoFiltro == "Proyecto") {
                $IdCampana= $_POST['IdProyecto'];
                $ConsultaSQL= "SELECT * FROM DYALOGOCRM_SISTEMA.CALHIS WHERE CALHIS_ConsInte__USUARI_Age_b= '". $IdAgente ."' AND CALHIS_ConsInte__PROYEC_b= '". $IdCampana ."';";
            }else if($TipoFiltro == "Fecha Gestion"){
                $HoraI= '00:00:00';
                $HoraF= '23:59:59';
                $FechaI= $_POST['FechaInicial'];
                $FechaF= $_POST['FechaFinal'];
                $FechaInicial= $FechaI. ' ' .$HoraI;
                $FechaFinal= $FechaF. ' ' .$HoraF;
                $ConsultaSQL= "SELECT * FROM DYALOGOCRM_SISTEMA.CALHIS WHERE CALHIS_ConsInte__USUARI_Age_b= '". $IdAgente ."' AND CALHIS_FechaGestion_b BETWEEN '". $FechaInicial ."' AND '". $FechaFinal ."'";
            }else if($TipoFiltro == "Fecha Evaluacion"){
                $HoraI= '00:00:00';
                $HoraF= '23:59:59';
                $FechaI= $_POST['FechaInicial'];
                $FechaF= $_POST['FechaFinal'];
                $FechaInicial= $FechaI. ' ' .$HoraI;
                $FechaFinal= $FechaF. ' ' .$HoraF;
                $ConsultaSQL= "SELECT * FROM DYALOGOCRM_SISTEMA.CALHIS WHERE CALHIS_ConsInte__USUARI_Age_b= '". $IdAgente ."' AND CALHIS_FechaEvaluacion_b BETWEEN '". $FechaInicial ."' AND '". $FechaFinal ."'";
            }else{
                $ConsultaSQL= "SELECT * FROM DYALOGOCRM_SISTEMA.CALHIS WHERE CALHIS_ConsInte__USUARI_Age_b= '". $IdAgente ."'";
            }
            
            //Ejecutar Consulta
            if($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
                $CantidadResultados = $ResultadoSQL->num_rows;
                if($CantidadResultados > 0) {
                    while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                        $Id= $FilaResultado['CALHIS_ConsInte__b'];
                        $ConsInte__PROYEC_b= $FilaResultado['CALHIS_ConsInte__PROYEC_b'];
                        $ConsInte__GUION__b= $FilaResultado['CALHIS_ConsInte__GUION__b'];
                        $IdGestion_b= $FilaResultado['CALHIS_IdGestion_b'];
                        $FechaGestion_b= $FilaResultado['CALHIS_FechaGestion_b'];
                        $ConsInte__USUARI_Age_b= $FilaResultado['CALHIS_ConsInte__USUARI_Age_b'];
                        $DatoPrincipalScript_b= $FilaResultado['CALHIS_DatoPrincipalScript_b'];
                        $DatoSecundarioScript_b= $FilaResultado['CALHIS_DatoSecundarioScript_b'];
                        $FechaEvaluacion_b= $FilaResultado['CALHIS_FechaEvaluacion_b'];
                        $ConsInte__USUARI_Cal_b= $FilaResultado['CALHIS_ConsInte__USUARI_Cal_b'];
                        $Calificacion_b= $FilaResultado['CALHIS_Calificacion_b'];
                        $ComentCalidad_b= $FilaResultado['CALHIS_ComentCalidad_b'];
                        $ComentAgente_b= $FilaResultado['CALHIS_ComentAgente_b'];
                        $LinkCalificacion= $FilaResultado['CALHIS_LinkCalificacion'];

                        //Consulta Nombre Proyecto
                        $ConsultaProyecto = "SELECT * FROM DYALOGOCRM_SISTEMA.PROYEC WHERE PROYEC_ConsInte__b= '". $ConsInte__PROYEC_b ."';";
                        if($ResultadoProyecto = $mysqli->query($ConsultaProyecto)) {
                            $CantidadResultados = $ResultadoProyecto->num_rows;
                            if($CantidadResultados > 0) {
                                while ($FilaResultado = $ResultadoProyecto->fetch_assoc()) { 
                                    $IdProyecto= $ConsInte__PROYEC_b;
                                    $NombreProyecto= $FilaResultado['PROYEC_NomProyec_b'];
                                }
                            }else{
                                $IdProyecto= "";
                                $NombreProyecto= "No Registrado";
                            }

                            //Consulta Nombre Guion
                            $ConsultaGuion = "SELECT * FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b= '". $ConsInte__GUION__b ."';";
                            if($ResultadoGuion = $mysqli->query($ConsultaGuion)) {
                                $CantidadResultados = $ResultadoGuion->num_rows;
                                if($CantidadResultados > 0) {
                                    while ($FilaResultado = $ResultadoGuion->fetch_assoc()) {   
                                        $NombreGuion= $FilaResultado['GUION__Nombre____b'];
                                        $NumeroG= $ConsInte__GUION__b;
                                    }
                                }else{
                                    $NombreGuion= "No Registrado";
                                    $NumeroG= $ConsInte__GUION__b;
                                }
                                
                                //Consulta Gestion
                                $ConsultaGestion= "SELECT * FROM DYALOGOCRM_WEB.G". $NumeroG ." WHERE G". $NumeroG ."_ConsInte__b = '". $IdGestion_b ."';";
                                if($ResultadoGestion = $mysqli->query($ConsultaGestion)) {
                                    $CantidadResultados = $ResultadoGestion->num_rows;
                                    if($CantidadResultados > 0) {
                                        while ($FilaResultado = $ResultadoGestion->fetch_assoc()) {   
                                            $NumeroGestion= $FilaResultado['G'. $NumeroG .'_DatoContacto'];
                                        }
                                    }else{
                                        $NumeroGestion= "No Registrado";
                                    }

                                    //Todo Ok
                                    array_push($ArrayCalificacion, array("0" => $Id, "1" => $NombreProyecto, "2" => $NombreGuion, "3" => $NumeroGestion, "4" => $FechaGestion_b, 
                                    "5" => $ConsInte__USUARI_Age_b, "6" => $DatoPrincipalScript_b, "7" => $DatoSecundarioScript_b, "8" => $FechaEvaluacion_b, "9" => $ConsInte__USUARI_Cal_b, 
                                    "10" => $Calificacion_b, "11" => $ComentCalidad_b, "12" => $ComentAgente_b, "13" => $LinkCalificacion, "14" => $IdProyecto));
                                    

                                }else{
                                    mysqli_close($mysqli);
                                    $Falla = mysqli_error($mysqli);
                                    $php_response = array("msg" => "Error Gestion", "Falla" => $Falla);
                                    echo json_encode($php_response);
                                    exit;
                                }

                            }else{
                                mysqli_close($mysqli);
                                $Falla = mysqli_error($mysqli);
                                $php_response = array("msg" => "Error Guion", "Falla" => $Falla);
                                echo json_encode($php_response);
                                exit;
                            }

                        }else{
                            mysqli_close($mysqli);
                            $Falla = mysqli_error($mysqli);
                            $php_response = array("msg" => "Error Proyecto", "Falla" => $Falla);
                            echo json_encode($php_response);
                            exit;
                        }

                    }

                    $php_response = array("msg" => "Ok", "Resultado" => $ArrayCalificacion);
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

            }else {
                mysqli_close($mysqli);
                $Falla = mysqli_error($mysqli);
                $php_response = array("msg" => "Error", "Falla" => $Falla);
                echo json_encode($php_response);
                exit;
            }

        }else {
            //Sin Resultados
            $php_response = array("msg" => "Nada");
            mysqli_close($mysqli);
            echo json_encode($php_response);
            exit;
        }

    }else {
        mysqli_close($mysqli);
        $Falla = mysqli_error($mysqli);
        $php_response = array("msg" => "Error", "Falla" => $Falla);
        echo json_encode($php_response);
        exit;
    }

}


?>
