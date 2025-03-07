
<?php

include_once(__DIR__."../../../../../pages/conexion.php");

$IdEstrategia = $_POST['IdEstrategia'];
$ConsultaEstrategia= "SELECT * FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b= '" .$IdEstrategia. "';";
if($ResultadoEstrategia= $mysqli->query($ConsultaEstrategia)) {
    $CantidadResultados= $ResultadoEstrategia->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoEstrategia->fetch_assoc()) {
            $NombreEstrat= $FilaResultado['ESTPAS_Nombre__b'];
            $IdFlujograma= $FilaResultado['ESTPAS_ConsInte__ESTRAT_b'];
        }

        $DatosEstrategia= array();
        $ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_rutas_entrantes WHERE id_estpas= '" .$IdEstrategia. "';";
        if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
            $CantidadResultados = $ResultadoSQL->num_rows;
            if($CantidadResultados > 0) {
                while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                    $IdRuta= $FilaResultado['id'];
                    $Nombre= $FilaResultado['nombre'];
                    $NumeroEntrada= $FilaResultado['did'];
                    $ListaNegra= $FilaResultado['valida_lista_negra'];
                    $ListaFestivos= $FilaResultado['id_lista_festivos'];
                    $NumeroLimite= $FilaResultado['limite_llamadas'];
                    $InputPausa= $FilaResultado['pausa_antes_contestar'];
                    $GenerarTimbre= $FilaResultado['generar_timbre_antes_contestar'];
                    array_push($DatosEstrategia, array("0"=> $Nombre, "1"=> $NumeroEntrada, "2"=> $ListaNegra, "3"=> $ListaFestivos, "4"=> $NumeroLimite, "5"=> $InputPausa, "6"=> $GenerarTimbre, "7"=> $IdRuta, "8"=> $IdFlujograma));
                }

                //Consulta Acciones
                $DatosHorario= array();
                $ConsultaAcciones = "SELECT DISTINCT accion, valor_accion, id_accion FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dias_ejecucion= '2_habiles';";
                if ($ResultadoAcciones = $mysqli->query($ConsultaAcciones)) {
                    $CantidadResultadosA = $ResultadoAcciones->num_rows;
                    if($CantidadResultadosA > 0) {
                        while ($FilaResultadoA = $ResultadoAcciones->fetch_assoc()) {
                            $Accion= $FilaResultadoA['accion'];
                            $ValorAccion= $FilaResultadoA['valor_accion'];
                            $IdAccion= $FilaResultadoA['id_accion'];
                        }
                        $ConsultaAcciones_FH= "SELECT DISTINCT accion, valor_accion, id_accion FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dias_ejecucion= '3_ambos'";
                        if ($ResultadoAccionesFH = $mysqli->query($ConsultaAcciones_FH)) {
                            $CantidadResultadosFH = $ResultadoAccionesFH->num_rows;
                            if($CantidadResultadosFH > 0) {
                                while ($FilaResultadoFH = $ResultadoAccionesFH->fetch_assoc()) {
                                    $Accion_Fuera= $FilaResultadoFH['accion'];
                                    $ValorAccion_Fuera= $FilaResultadoFH['id_accion'];
                                }
                            }else{
                                $Accion_Fuera= "";
                                $ValorAccion_Fuera= "";
                            }
                        }else{
                            mysqli_close($mysqli);
                            $Falla = mysqli_error($mysqli);
                            $php_response = array("msg" => "Error: Consulta Estrategia FH", "Falla" => $Falla);
                            echo json_encode($php_response);
                            exit;
                        }

                        //Consultar Horarios
                        $ConsultaLunesSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'mon' AND dia_habil_semana_fin= 'mon';";
                        if ($ResultadoLunesSQL = $mysqli->query($ConsultaLunesSQL)) {
                            $CantidadResultadosL = $ResultadoLunesSQL->num_rows;
                            if($CantidadResultadosL > 0) {
                                while ($FilaResultado = $ResultadoLunesSQL->fetch_assoc()) {
                                    $HoraIncialLu= $FilaResultado['hora_inicial'];
                                    $HoraFinalLu= $FilaResultado['hora_final'];
                                }
                            }else{
                                $HoraIncialLu= '';
                                $HoraFinalLu= '';
                            }
                            //Martes
                            $ConsultaMartesSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'tue' AND dia_habil_semana_fin= 'tue';";
                            if ($ResultadoMartesSQL = $mysqli->query($ConsultaMartesSQL)) {
                                $CantidadResultadosL = $ResultadoMartesSQL->num_rows;
                                if($CantidadResultadosL > 0) {
                                    while ($FilaResultado = $ResultadoMartesSQL->fetch_assoc()) {
                                        $HoraIncialMa= $FilaResultado['hora_inicial'];
                                        $HoraFinalMa= $FilaResultado['hora_final'];
                                    }
                                }else{
                                    $HoraIncialMa= '';
                                    $HoraFinalMa= '';
                                }
                                //Miercoles
                                $ConsultaMiercolesSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'wed' AND dia_habil_semana_fin= 'wed';";
                                if ($ResultadoMiercolesSQL = $mysqli->query($ConsultaMiercolesSQL)) {
                                    $CantidadResultadosL = $ResultadoMiercolesSQL->num_rows;
                                    if($CantidadResultadosL > 0) {
                                        while ($FilaResultado = $ResultadoMiercolesSQL->fetch_assoc()) {
                                            $HoraIncialMi= $FilaResultado['hora_inicial'];
                                            $HoraFinalMi= $FilaResultado['hora_final'];
                                        }
                                    }else{
                                        $HoraIncialMi= '';
                                        $HoraFinalMi= '';
                                    }
                                    //Jueves
                                    $ConsultaJuevesSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'thu' AND dia_habil_semana_fin= 'thu';";
                                    if ($ResultadoJuevesSQL = $mysqli->query($ConsultaJuevesSQL)) {
                                        $CantidadResultadosL = $ResultadoJuevesSQL->num_rows;
                                        if($CantidadResultadosL > 0) {
                                            while ($FilaResultado = $ResultadoJuevesSQL->fetch_assoc()) {
                                                $HoraIncialJu= $FilaResultado['hora_inicial'];
                                                $HoraFinalJu= $FilaResultado['hora_final'];
                                            }
                                        }else{
                                            $HoraIncialJu= '';
                                            $HoraFinalJu= '';
                                        }
                                        //Viernes
                                        $ConsultaViernesSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'fri' AND dia_habil_semana_fin= 'fri';";
                                        if ($ResultadoViernesSQL = $mysqli->query($ConsultaViernesSQL)) {
                                            $CantidadResultadosL = $ResultadoViernesSQL->num_rows;
                                            if($CantidadResultadosL > 0) {
                                                while ($FilaResultado = $ResultadoViernesSQL->fetch_assoc()) {
                                                    $HoraIncialVi= $FilaResultado['hora_inicial'];
                                                    $HoraFinalVi= $FilaResultado['hora_final'];
                                                }
                                            }else{
                                                $HoraIncialVi= '';
                                                $HoraFinalVi= '';
                                            }
                                            //Sabado
                                            $ConsultaSabadoSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'sat' AND dia_habil_semana_fin= 'sat';";
                                            if ($ResultadoSabadoSQL = $mysqli->query($ConsultaSabadoSQL)) {
                                                $CantidadResultadosL = $ResultadoSabadoSQL->num_rows;
                                                if($CantidadResultadosL > 0) {
                                                    while ($FilaResultado = $ResultadoSabadoSQL->fetch_assoc()) {
                                                        $HoraIncialSa= $FilaResultado['hora_inicial'];
                                                        $HoraFinalSa= $FilaResultado['hora_final'];
                                                    }
                                                }else{
                                                    $HoraIncialSa= '';
                                                    $HoraFinalSa= '';
                                                }
                                                //Domingo 
                                                $ConsultaDomingoSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'sun' AND dia_habil_semana_fin= 'sun';";
                                                if ($ResultadoDomingoSQL = $mysqli->query($ConsultaDomingoSQL)) {
                                                    $CantidadResultadosL = $ResultadoDomingoSQL->num_rows;
                                                    if($CantidadResultadosL > 0) {
                                                        while ($FilaResultado = $ResultadoDomingoSQL->fetch_assoc()) {
                                                            $HoraIncialDo= $FilaResultado['hora_inicial'];
                                                            $HoraFinalDo= $FilaResultado['hora_final'];
                                                        }
                                                    }else{
                                                        $HoraIncialDo= '';
                                                        $HoraFinalDo= '';
                                                    }
                                                    //Festivos
                                                    $ConsultaFestivosSQL = "SELECT * FROM dyalogo_telefonia.dy_acciones_rutas_entrantes WHERE id_ruta_entrante= '". $IdRuta ."' AND dia_habil_semana_inicio= 'hol' AND dia_habil_semana_fin= 'hol';";
                                                    if ($ResultadoFestivosSQL = $mysqli->query($ConsultaFestivosSQL)) {
                                                        $CantidadResultadosL = $ResultadoFestivosSQL->num_rows;
                                                        if($CantidadResultadosL > 0) {
                                                            while ($FilaResultado = $ResultadoFestivosSQL->fetch_assoc()) {
                                                                $HoraIncialFes= $FilaResultado['hora_inicial'];
                                                                $HoraFinalFes= $FilaResultado['hora_final'];
                                                            }
                                                        }else{
                                                            $HoraIncialFes= '';
                                                            $HoraFinalFes= '';
                                                        }
                                                        //Ok
                                                        array_push($DatosHorario, array("0"=> $Accion, "1"=> $ValorAccion, "2" => $HoraIncialLu, "3" => $HoraFinalLu, "4" => $HoraIncialMa, "5" => $HoraFinalMa, "6" => $HoraIncialMi, "7" => $HoraFinalMi, "8" => $HoraIncialJu, "9" => $HoraFinalJu, "10" => $HoraIncialVi, 
                                                        "11" => $HoraFinalVi, "12" => $HoraIncialSa, "13" => $HoraFinalSa, "14" => $HoraIncialDo, "15" => $HoraFinalDo, "16" => $HoraIncialFes, "17" => $HoraFinalFes, "18" => $Accion_Fuera, "19" => $ValorAccion_Fuera, "20"=> $IdAccion));

                                                        $php_response = array("msg" => "Ya Existe", "Resultado" => $DatosEstrategia, "Resultado_2" => $DatosHorario);
                                                        echo json_encode($php_response);
                                                        mysqli_close($mysqli);
                                                        exit;
                                                        
                                                    }else{
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
                                            }else{
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
                                    }else{
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
                            }else{
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
                    }else{
                        $Accion= "";
                        $ValorAccion= "";
                        $IdAccion= "";
                    }
                }else{
                    mysqli_close($mysqli);
                    $Falla = mysqli_error($mysqli);
                    $php_response = array("msg" => "Error: Consulta Acciones", "Falla" => $Falla);
                    echo json_encode($php_response);
                    exit;
                }
            }else {
                //Sin Resultados
                $php_response = array("msg" => "Nada", "IdFlujograma" => $IdFlujograma);
                mysqli_close($mysqli);
                echo json_encode($php_response);
                exit;
            }
        } else {
            mysqli_close($mysqli);
            $Falla = mysqli_error($mysqli);
            $php_response = array("msg" => "Error: Consulta Ruta", "Falla" => $Falla);
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
    $php_response = array("msg" => "Error: Consulta Estrategia", "Falla" => $Falla);
    echo json_encode($php_response);
    exit;
}


?>
