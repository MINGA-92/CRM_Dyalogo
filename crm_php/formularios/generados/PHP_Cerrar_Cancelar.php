<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");
    date_default_timezone_set('America/Bogota');
    $arrReturn = array();
        if(isset($_GET['campana_crm'])  && isset($_GET['idMonoef']) ){
        crearMiembroDefault($_GET["campana_crm"]);
        /* primero buscamos la campaña que nos esta llegando */
        $Lsql_Campan = "SELECT CAMPAN_Nombre____b,CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];

        //echo $Lsql_Campan;

        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
        $datoCampan = $res_Lsql_Campan->fetch_array();
        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
        $str_Nombre_Campan= $datoCampan['CAMPAN_Nombre____b'];   
        $muestra="G".$int_Pobla_Camp_2."_M".$int_Muest_Campan;
        $fechaInsercion = date('Y-m-d H:i:s');
        $nombreUsuario = "NULL";
        $idUsuario = 0;
        $codigoMiembro = -1;
        $intGestion= -1;    

        if (isset($_GET["origen"]) && $_GET["origen"] != "" && $_GET["origen"] != NULL) {
            
            $strOrigen_t = $_GET["origen"];

        }else{

            $strOrigen_t = "BusquedaManual";

        }

        $idLlamada = "NULL";
        $sentido = "NULL";
        $canal = "BusquedaManual";
        $linkContenido = "";
        $detalleCanal= "busqueda_manual";
        $datoContacto = "Numero no se identifica";
        $paso = 0;        
        $duracion = "NULL";
        $clasificacion = 'NULL';
        $tipificacion = $_GET['idMonoef'];
        $reintento = 'NULL'; 
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $comentario = "Registro cerrado desde la busqueda manual";
        
        //llenamos los campos de control del sistema
        $strCampCamp="SELECT PREGUN_ConsInte__b FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b={$int_Guion_Campan} AND SECCIO_TipoSecc__b=4 and PREGUN_Texto_____b='Campaña'";
        $strCampCamp=$mysqli->query($strCampCamp);
            
        if($strCampCamp && $strCampCamp->num_rows > 0){
            $strCampCamp=$strCampCamp->fetch_object();
            $strCampCamp=$strCampCamp->PREGUN_ConsInte__b;
            $strCampCamp="G{$int_Guion_Campan}_C{$strCampCamp}";
        }
            
        if(isset($_GET['usuario'])){
            $nombreUsuario = NombreAgente($_GET['usuario']);
            $idUsuario  = isset($_GET['usuario']) ? $_GET['usuario'] :'0';
        }

         if(isset($_GET['sentido']) & $_GET['sentido'] != 0){
            if($_GET['sentido'] == '1'){
                $sentido = 'Saliente';
            }else{
                $sentido = 'Entrante';
            }
        }else{
            $sentido = "Entrante";
        }

        $valorId_Gestion_Cbx_2 = '';
        if(isset($_GET['id_gestion_cbx']) && $_GET['id_gestion_cbx'] != '' && $_GET['id_gestion_cbx'] != null){

            $idLlamada = $_GET['id_gestion_cbx'];
            $valorId_Gestion_Cbx_2 = $_GET['id_gestion_cbx'];
            
            /* Toca averiguar lo del Coninte de la gestio */
            $arrOrigen_t=explode('_', $valorId_Gestion_Cbx_2);
            //echo json_encode($arrOrigen_t);die();
            if(count($arrOrigen_t)===3){
                $valorSentido = $arrOrigen_t[1]."_".$arrOrigen_t[2];
                if(strrchr($arrOrigen_t[2],'RNC')){
                    $strOrigen_t='RingNoAnswer';
                }else{
                    $strOrigen_t='Transferida';
                }
            }else{
                $valorSentido = explode('_', $valorId_Gestion_Cbx_2)[1];
            }
            $LsqlXUnique = "SELECT unique_id FROM dyalogo_telefonia.dy_llamadas_salientes where id_dy_llamada = '".$valorSentido."';";
            $resPXunique = $mysqli->query($LsqlXUnique);
            if($resPXunique){
                $estoUnique = $resPXunique->fetch_array();
                if( $estoUnique['unique_id'] != null &&  $estoUnique['unique_id'] != ''){
                    $idLlamada = $estoUnique['unique_id'];  
                }else{
                    $idLlamada = $valorSentido;
                }
            }else{
                $idLlamada = $valorSentido;
            }
            $CanalUG='BusquedaManual';       
        }


        if($tipificacion == '-18'){

            if( $idLlamada != '' && $idLlamada != 'NULL' ){
                $Lsql = "SELECT ip_servidor FROM dyalogo_telefonia.dy_configuracion_crm WHERE id_huesped = -1 AND sistema = 0";
                if( ($query = $mysqli->query($Lsql)) == TRUE ){

                    if(isset($_GET['canal']) && $_GET['canal'] != ''){
                        $canal = $_GET['canal'];
                    }else{
                        $canal = explode('_', $valorId_Gestion_Cbx_2)[0];
                    }
                    
                    $CanalUG=$canal;            

                    $array = $query->fetch_array();    
                    $linkContenido="https://".$array["ip_servidor"].":8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=".$idLlamada."&uid2=".$idLlamada."&canal=".$CanalUG;
                    
                }
            } 
        }else{
            $linkContenido='No hubo comunicación por lo tanto no se genera un link de grabación';
        }

         
        $Lsql="SELECT ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_GET["campana_crm"]." AND (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6)";            
        if( ($query = $mysqli->query($Lsql)) == TRUE ){
            $array = $query->fetch_array();  
            if($array["ESTPAS_ConsInte__b"] != ''){
                 $paso=$array["ESTPAS_ConsInte__b"];    
                  
            }
                

        }

        $fechaInicial =  new DateTime($_GET['tiempoInicio']);
        $fechaFinal = new DateTime($fechaInsercion);
        $duracion = $fechaInicial->diff($fechaFinal);
        $duracion = $duracion->format("%H:%I:%S");
        $fechaInicial =$fechaInicial->format("Y-m-d H:i:s");    

        $LmonoEfLSql = "SELECT * FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = ".$_GET['idMonoef'];
        $resMonoEf = $mysqli->query($LmonoEfLSql);
        $dataMonoEf = $resMonoEf->fetch_array();
        $reintento = $dataMonoEf['MONOEF_TipNo_Efe_b'];
        $clasificacion = $dataMonoEf['MONOEF_Contacto__b'];
      

        /* Ahora procedemos a insertar las cosas que necesitamos */
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$int_Guion_Campan;
        $res = $mysqli->query($Lsql);
        $dato = $res->fetch_array();
             
        
        /*Inserción de datos en el SCRIPT*/
        if(isset($idUsuario) && $idUsuario > 0){
            if(isset($_GET['consinte']) && is_numeric($_GET['consinte'])){
                $LsqlScript ="UPDATE {$BaseDatos}.G{$int_Guion_Campan} SET ";
                $LsqlScript.="G{$int_Guion_Campan}_CodigoMiembro='{$codigoMiembro}'";
                $LsqlScript.=", G{$int_Guion_Campan}_Origen_b='{$strOrigen_t}'";
                $LsqlScript.=", G{$int_Guion_Campan}_IdLlamada='{$idLlamada}'";
                $LsqlScript.=", G{$int_Guion_Campan}_Sentido___b='{$sentido}'";
                $LsqlScript.=", G{$int_Guion_Campan}_Canal_____b='{$canal}'";
                $LsqlScript.=", G{$int_Guion_Campan}_LinkContenido='{$linkContenido}'";
                $LsqlScript.=", G{$int_Guion_Campan}_DetalleCanal='{$detalleCanal}'";
                $LsqlScript.=", G{$int_Guion_Campan}_Paso='{$paso}'";
                $LsqlScript.=", G{$int_Guion_Campan}_Clasificacion='{$clasificacion}'";
                $LsqlScript.=", G{$int_Guion_Campan}_Duracion___b=timediff(NOW(),G{$int_Guion_Campan}_FechaInsercion)";
                $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Tip_b']}='{$tipificacion}'";
                $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Rep_b']}='{$reintento}'";
                $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Fec_b']}=NOW()";
                $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Hor_b']}=NOW()";
                $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Com_b']}='{$comentario}'";
                $LsqlScript.=", {$strCampCamp}='{$str_Nombre_Campan}'";
                $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Age_b']}='{$nombreUsuario}'";
                $LsqlScript.=" WHERE G{$int_Guion_Campan}_ConsInte__b={$_GET['consinte']}";
                $intGestion=$_GET['consinte'];
            }else{
                $sqlGestionInsertada=$mysqli->query("SELECT MAX(G{$int_Guion_Campan}_ConsInte__b) AS id FROM {$BaseDatos}.G{$int_Guion_Campan} WHERE G{$int_Guion_Campan}_Usuario={$idUsuario} AND G{$int_Guion_Campan}_CodigoMiembro IS NULL AND G{$int_Guion_Campan}_FechaInsercion >=CURDATE() AND G{$int_Guion_Campan}_DetalleCanal={$idUsuario}");
                if($sqlGestionInsertada && $sqlGestionInsertada->num_rows>0){
                    $sqlGestionInsertada=$sqlGestionInsertada->fetch_object();
                    $idGestion=$sqlGestionInsertada->id;
                    if(is_numeric($idGestion)){
                        $LsqlScript ="UPDATE {$BaseDatos}.G{$int_Guion_Campan} SET ";
                        $LsqlScript.="G{$int_Guion_Campan}_CodigoMiembro='{$codigoMiembro}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_Origen_b='{$strOrigen_t}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_IdLlamada='{$idLlamada}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_Sentido___b='{$sentido}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_Canal_____b='{$canal}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_LinkContenido='{$linkContenido}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_DetalleCanal='{$detalleCanal}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_Paso='{$paso}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_Clasificacion='{$clasificacion}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_Duracion___b=timediff(NOW(),G{$int_Guion_Campan}_FechaInsercion)";
                        $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Tip_b']}='{$tipificacion}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Rep_b']}='{$reintento}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Fec_b']}=NOW()";
                        $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Hor_b']}=NOW()";
                        $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Com_b']}='{$comentario}'";
                        $LsqlScript.=", {$strCampCamp}='{$str_Nombre_Campan}'";
                        $LsqlScript.=", G{$int_Guion_Campan}_C{$dato['GUION__ConsInte__PREGUN_Age_b']}='{$nombreUsuario}'";
                        $LsqlScript.=" WHERE G{$int_Guion_Campan}_ConsInte__b={$idGestion}";
                        $intGestion=$idGestion;
                    }else{
                        $LsqlScript = "INSERT INTO ".$BaseDatos.".G".$int_Guion_Campan."(";
                        $LsqlScript .= "G".$int_Guion_Campan."_FechaInsercion";
                        $LsqlScript .= ",G".$int_Guion_Campan."_Usuario";
                        $LsqlScript .= ",G".$int_Guion_Campan."_CodigoMiembro";
                        $LsqlScript .= ",G".$int_Guion_Campan."_Origen_b";
                        $LsqlScript .= ",G".$int_Guion_Campan."_IdLlamada";
                        $LsqlScript .= ",G".$int_Guion_Campan."_Sentido___b";
                        $LsqlScript .= ",G".$int_Guion_Campan."_Canal_____b";
                        $LsqlScript .= ",G".$int_Guion_Campan."_LinkContenido";
                        $LsqlScript .= ",G".$int_Guion_Campan."_DetalleCanal";
                        $LsqlScript .= ",G".$int_Guion_Campan."_DatoContacto";
                        $LsqlScript .= ",G".$int_Guion_Campan."_Paso";
                        $LsqlScript .= ",G".$int_Guion_Campan."_Clasificacion";
                        $LsqlScript .= ",G".$int_Guion_Campan."_Duracion___b";
                        $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Tip_b'];
                        $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Rep_b'];
                        $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Fec_b'];
                        $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Hor_b'];
                        $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Com_b'];
                        $LsqlScript .= ",{$strCampCamp}";
                        $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Age_b']." )";
                        $LsqlScript .= "VALUES (";
                        $LsqlScript .= "'".$fechaInicial."'";
                        $LsqlScript .= ",'".$idUsuario."'";
                        $LsqlScript .= ",'".$codigoMiembro."'";
                        $LsqlScript .= ",'".$strOrigen_t."'";
                        $LsqlScript .= ",'".$idLlamada."'";
                        $LsqlScript .= ",'".$sentido."'";
                        $LsqlScript .= ",'".$canal."'";
                        $LsqlScript .= ",'".$linkContenido."'";
                        $LsqlScript .= ",'".$detalleCanal."'";
                        $LsqlScript.= ",'".$datoContacto."'";
                        $LsqlScript .= ",'".$paso."'";
                        $LsqlScript .= ",'".$clasificacion."'";
                        $LsqlScript .= ",'".$duracion."'";
                        $LsqlScript .= ",'".$tipificacion."'";
                        $LsqlScript .= ",'".$reintento."'";
                        $LsqlScript .= ",'".$fecha."'";
                        $LsqlScript .= ",'".$hora."'";
                        $LsqlScript .= ",'".$comentario."'";
                        $LsqlScript .= ",'".$str_Nombre_Campan."'";
                        $LsqlScript .= ",'".$nombreUsuario."')";
                    }
                }else{
                    $LsqlScript = "INSERT INTO ".$BaseDatos.".G".$int_Guion_Campan."(";
                    $LsqlScript .= "G".$int_Guion_Campan."_FechaInsercion";
                    $LsqlScript .= ",G".$int_Guion_Campan."_Usuario";
                    $LsqlScript .= ",G".$int_Guion_Campan."_CodigoMiembro";
                    $LsqlScript .= ",G".$int_Guion_Campan."_Origen_b";
                    $LsqlScript .= ",G".$int_Guion_Campan."_IdLlamada";
                    $LsqlScript .= ",G".$int_Guion_Campan."_Sentido___b";
                    $LsqlScript .= ",G".$int_Guion_Campan."_Canal_____b";
                    $LsqlScript .= ",G".$int_Guion_Campan."_LinkContenido";
                    $LsqlScript .= ",G".$int_Guion_Campan."_DetalleCanal";
                    $LsqlScript .= ",G".$int_Guion_Campan."_DatoContacto";
                    $LsqlScript .= ",G".$int_Guion_Campan."_Paso";
                    $LsqlScript .= ",G".$int_Guion_Campan."_Clasificacion";
                    $LsqlScript .= ",G".$int_Guion_Campan."_Duracion___b";
                    $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Tip_b'];
                    $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Rep_b'];
                    $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Fec_b'];
                    $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Hor_b'];
                    $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Com_b'];
                    $LsqlScript .= ",{$strCampCamp}";
                    $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Age_b']." )";
                    $LsqlScript .= "VALUES (";
                    $LsqlScript .= "'".$fechaInicial."'";
                    $LsqlScript .= "'".$idUsuario."'";
                    $LsqlScript .= ",'".$codigoMiembro."'";
                    $LsqlScript .= ",'".$strOrigen_t."'";
                    $LsqlScript .= ",'".$idLlamada."'";
                    $LsqlScript .= ",'".$sentido."'";
                    $LsqlScript .= ",'".$canal."'";
                    $LsqlScript .= ",'".$linkContenido."'";
                    $LsqlScript .= ",'".$detalleCanal."'";
                    $LsqlScript.= ",'".$datoContacto."'";
                    $LsqlScript .= ",'".$paso."'";
                    $LsqlScript .= ",'".$clasificacion."'";
                    $LsqlScript .= ",'".$duracion."'";
                    $LsqlScript .= ",'".$tipificacion."'";
                    $LsqlScript .= ",'".$reintento."'";
                    $LsqlScript .= ",'".$fecha."'";
                    $LsqlScript .= ",'".$hora."'";
                    $LsqlScript .= ",'".$comentario."'";
                    $LsqlScript .= ",'".$str_Nombre_Campan."'";
                    $LsqlScript .= ",'".$nombreUsuario."')";
                }
            }
        }else{
            $LsqlScript = "INSERT INTO ".$BaseDatos.".G".$int_Guion_Campan."(";
            $LsqlScript .= "G".$int_Guion_Campan."_FechaInsercion";
            $LsqlScript .= ",G".$int_Guion_Campan."_Usuario";
            $LsqlScript .= ",G".$int_Guion_Campan."_CodigoMiembro";
            $LsqlScript .= ",G".$int_Guion_Campan."_Origen_b";
            $LsqlScript .= ",G".$int_Guion_Campan."_IdLlamada";
            $LsqlScript .= ",G".$int_Guion_Campan."_Sentido___b";
            $LsqlScript .= ",G".$int_Guion_Campan."_Canal_____b";
            $LsqlScript .= ",G".$int_Guion_Campan."_LinkContenido";
            $LsqlScript .= ",G".$int_Guion_Campan."_DetalleCanal";
            $LsqlScript .= ",G".$int_Guion_Campan."_DatoContacto";
            $LsqlScript .= ",G".$int_Guion_Campan."_Paso";
            $LsqlScript .= ",G".$int_Guion_Campan."_Clasificacion";
            $LsqlScript .= ",G".$int_Guion_Campan."_Duracion___b";
            $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Tip_b'];
            $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Rep_b'];
            $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Fec_b'];
            $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Hor_b'];
            $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Com_b'];
            $LsqlScript .= ",{$strCampCamp}";
            $LsqlScript .= ",G".$int_Guion_Campan."_C".$dato['GUION__ConsInte__PREGUN_Age_b']." )";
            $LsqlScript .= "VALUES (";
            $LsqlScript .= "'".$fechaInicial."'";
            $LsqlScript .= ",".$idUsuario."";
            $LsqlScript .= ",'".$codigoMiembro."'";
            $LsqlScript .= ",'".$strOrigen_t."'";
            $LsqlScript .= ",'".$idLlamada."'";
            $LsqlScript .= ",'".$sentido."'";
            $LsqlScript .= ",'".$canal."'";
            $LsqlScript .= ",'".$linkContenido."'";
            $LsqlScript .= ",'".$detalleCanal."'";
            $LsqlScript.= ",'".$datoContacto."'";
            $LsqlScript .= ",'".$paso."'";
            $LsqlScript .= ",'".$clasificacion."'";
            $LsqlScript .= ",'".$duracion."'";
            $LsqlScript .= ",'".$tipificacion."'";
            $LsqlScript .= ",'".$reintento."'";
            $LsqlScript .= ",'".$fecha."'";
            $LsqlScript .= ",'".$hora."'";
            $LsqlScript .= ",'".$comentario."'";
            $LsqlScript .= ",'".$str_Nombre_Campan."'";
            $LsqlScript .= ",'".$nombreUsuario."')";

        }    
        if($mysqli->query($LsqlScript ) === TRUE){
            $arrReturn["SCRIPT"]="OK";    
            $arrReturn["SCRIPT-QUERY"]=$LsqlScript;
            if($intGestion == -1){
                $intGestion=$mysqli->insert_id;
            }
            $arrReturn["intConsInte_t"]=$intGestion;
        }else{
            $arrReturn["SCRIPT"]= "Error insertando Script => ".$mysqli->error;
            $queryScript="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
            VALUES(\"".$LsqlScript  ."\",\"".$mysqli->error."\",'Insercion Script Busqueda Manual')";
            $mysqli->query($queryScript);
        }            

        /*YCR
        * Actualizacion de la BD
        */
        $LsqlBD = "UPDATE ".$BaseDatos.".".$str_Pobla_Campan." SET ";
        $LsqlBD .= $str_Pobla_Campan."_CantidadIntentos = ".$str_Pobla_Campan."_CantidadIntentos +1";
        $LsqlBD .= ",".$str_Pobla_Campan."_ComentarioUG_b = '".$comentario."'";
        $LsqlBD .= ",".$str_Pobla_Campan."_DetalleCanalUG_b = '".$detalleCanal."'";
        $LsqlBD .= ",".$str_Pobla_Campan."_FecUltGes_b = '".$fechaInicial."'";
        $LsqlBD .= "  WHERE  ".$str_Pobla_Campan."_ConsInte__b = -1 ";

        
         
        if($mysqli->query($LsqlBD ) === TRUE){
            $arrReturn["BD"]="OK";   
            $arrReturn["BD-QUERY"]=$LsqlBD;   
            $idNuevo=$mysqli->insert_id;
        }else{
            $arrReturn["BD"]= "Error actualizando intentos BD => ".$mysqli->error;
            $queryBD="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
            VALUES(\"".$LsqlBD."\",\"".$mysqli->error."\",'Actualizacion BD Busqueda Manual')";
            $mysqli->query($queryBD);
        }       


        $LsqlMuestra = "UPDATE ".$BaseDatos.".".$muestra." SET ";
        $LsqlMuestra .= $muestra."_NumeInte__b = ".$muestra."_NumeInte__b + 1";
        $LsqlMuestra .= ", {$muestra}_FecUltGes_b = '{$fechaInicial}'";
        $LsqlMuestra .= "  WHERE  ".$muestra."_CoInMiPo__b = -1 ";  

        if($mysqli->query($LsqlMuestra  ) === TRUE){
            $arrReturn["MUESTRA"]="OK";
            $arrReturn["MUESTRA-QUERY"]=$LsqlMuestra;
        }else{
            $arrReturn["MUESTRA"]="Error actaulizando intentos Muestra  => ".$mysqli->error;
            $queryMuestra="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
            VALUES(\"".$LsqlMuestra."\",\"".$mysqli->error."\",'Actualizacion Muestra Busqueda Manual')";
            $mysqli->query($queryMuestra);
        }       

                    


        /*YCR 2019-10-16
        *Inserción de datos en el CONDIA
        */
        $LsqlCondia = "INSERT INTO ".$BaseDatos_systema.".CONDIA (
                        CONDIA_IndiEfec__b, 
                        CONDIA_TipNo_Efe_b, 
                        CONDIA_ConsInte__MONOEF_b, 
                        CONDIA_TiemDura__b, 
                        CONDIA_Fecha_____b, 
                        CONDIA_ConsInte__CAMPAN_b, 
                        CONDIA_ConsInte__USUARI_b, 
                        CONDIA_ConsInte__GUION__Gui_b, 
                        CONDIA_ConsInte__GUION__Pob_b, 
                        CONDIA_ConsInte__MUESTR_b, 
                        CONDIA_CodiMiem__b, 
                        CONDIA_Observacio_b, 
                        CONDIA_IdenLlam___b,
                        CONDIA_TiemPrev__b,
                        CONDIA_Canal_b,
                        CONDIA_Sentido___b,
                        CONDIA_UniqueId_b) 
                        VALUES (
                        '0', 
                        '".$reintento."',
                        '".$tipificacion."',
                        '".date('Y-m-d').' '.$duracion."',
                        '".$fechaInicial."',
                        '".$_GET["campana_crm"]."',
                        ".$idUsuario.",
                        '".$int_Guion_Campan."',
                        '".$int_Pobla_Camp_2."',
                        '".$int_Muest_Campan."',
                        '".$codigoMiembro."',
                        '".$comentario."' ,
                        '".$idLlamada."',
                        '0',
                        'B".$CanalUG."',
                        '".$_GET['sentido']."',
                        '".$idLlamada."'
                    )";
        
        if($mysqli->query($LsqlCondia) === true){
            $arrReturn["CONDIA"]="OK";
            $arrReturn["CONDIA-QUERY"]=$LsqlCondia;
        }else{
            $arrReturn["CONDIA"]="Error insertando Condia => ".$mysqli->error;
            $queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
            VALUES(\"".$LsqlCondia."\",\"".$mysqli->error."\",'Insercion Condia Busqueda Manual')";
            $mysqli->query($queryCondia);
        }
    
    }

    if(isset($_GET['usuario'])){
        echo json_encode($arrReturn);
    }    

    /* Cerrar Gestion */
    if(isset($_GET['token']) && isset($_GET['id_gestion_cbx']) && !isset($_GET['usuario'])){
        $data =  array();
        
        $data = array(  
                "strToken_t" => $_GET['token'], 
                "strIdGestion_t" => $_GET['id_gestion_cbx'], 
                "intTipoReintento_t" => -1,
                "strFechaHoraAgenda_t" => -1,
                "intConsInte_t" => $intGestion,
                "booForzarCierre_t" => true,
                "intMonoefEfectiva_t" => -1,
                "intConsInteTipificacion_t" => -1,
                "boolFinalizacionDesdeBlend_t" => false,
                "intMonoefContacto_t" => -1
            ); 
                
                
        $ch = curl_init($IP_CONFIGURADA .'gestion/finalizar');
                                                                                   
        $data_string = json_encode($data);    

        //echo $data_string;
        //especificamos el POST (tambien podemos hacer peticiones enviando datos por GET
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,     "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 

        //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,    array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                      
            ); 
        //recogemos la respuesta
        $respuesta = curl_exec ($ch);
        //o el error, por si falla
        $error = curl_error($ch);
        //y finalmente cerramos curl
        //echo "Respuesta =>  ". $respuesta;
        //echo "<br/>Error => ".$error;

        curl_close ($ch);
    }
