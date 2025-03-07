<?php
	session_start();

    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);

    $id_usuario =  149;
    generarReportesExcell_diarios($id_usuario);
    generarReportesExcell_semanales($id_usuario);
    generarReportesExcell_mensuales($id_usuario);


 function generarReportesExcell_diarios($id_usuario, $destinatarios = null, $copia=null, $asunto = null, $hora = null){
        include(__DIR__."/pages/conexion.php"); 
        $ScriptLsql = "SELECT G10_C73 , G10_C74 , G10_C75 , G10_C106, G10_C71 FROM ".$BaseDatos_systema.".G10 WHERE G10_ConsInte__b = ".$id_usuario;
        $resScript  = $mysqli->query($ScriptLsql);
        $datosSc    = $resScript->fetch_array();
        $Script     = $datosSc['G10_C73'];
        $Bdtraducir = $datosSc['G10_C74'];
        $MuestraTra = $datosSc['G10_C75'];
        $idCbxCaman = $datosSc['G10_C106'];
        $nombreCamp = $datosSc['G10_C71'];

        $guion_s = 'G'.$Bdtraducir;
        $guion_c = 'G'.$Bdtraducir."_C";
        $Scrip_s = 'G'.$Script;
        $muestraTraducida = "G".$Bdtraducir."_M".$MuestraTra;

        $consultas = '';

        $consultas .= "\"SELECT CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, COUNT(*) AS TOTAL FROM ".$BaseDatos.".".$guion_s." LEFT JOIN ".$BaseDatos.".".$muestraTraducida." ON ".$guion_s."_ConsInte__b = ".$muestraTraducida."_CoInMiPo__b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY MONOEF_Contacto__b ORDER BY MONOEF_Contacto__b DESC;\"";

        $consultas .= ", \"SELECT CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b as 'TIPIFICACION MAS IMPORTANTE', COUNT(*) AS TOTAL FROM ".$BaseDatos.".".$guion_s." LEFT JOIN ".$BaseDatos.".".$muestraTraducida." ON ".$guion_s."_ConsInte__b = ".$muestraTraducida."_CoInMiPo__b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY MONOEF_ConsInte__b ORDER BY MONOEF_Contacto__b DESC;\"";


        $consultas .= ", \"SELECT ".$muestraTraducida."_NumeInte__b AS NUMERO_DE_INTENTOS, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS 'TIPIFICACION MAS IMPORTANTE', Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$muestraTraducida." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY ".$muestraTraducida."_NumeInte__b, MONOEF_ConsInte__b ORDER BY ".$muestraTraducida."_NumeInte__b ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";
        
        $LsqlTipificacion = "SELECT G5_C311 FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$Script;
        $resLsqlTipificacion = $mysqli->query($LsqlTipificacion);
        $dataTipificacion = $resLsqlTipificacion->fetch_array();

        

        $consultas .= ", \"SELECT DATE_FORMAT(".$Scrip_s."_FechaInsercion, '%H') AS HORA, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b WHERE ".$Scrip_s."_FechaInsercion >= CURRENT_DATE() GROUP BY HORA, MONOEF_ConsInte__b  ORDER BY HORA ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";

        $consultas .= ", \"SELECT USUARI_Nombre____b AS AGENTE, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b LEFT JOIN ".$BaseDatos_systema.".USUARI ON USUARI_ConsInte__b = ".$Scrip_s."_Usuario WHERE ".$Scrip_s."_FechaInsercion >= CURRENT_DATE() GROUP BY USUARI_ConsInte__b, MONOEF_ConsInte__b  ORDER BY USUARI_Nombre____b ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";


        $consultas .= ", \"SELECT USUARI_Nombre____b AS AGENTE, DATE_FORMAT(".$Scrip_s."_FechaInsercion, '%H') AS HORA, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b LEFT JOIN ".$BaseDatos_systema.".USUARI ON USUARI_ConsInte__b = ".$Scrip_s."_Usuario WHERE ".$Scrip_s."_FechaInsercion >= CURRENT_DATE() GROUP BY USUARI_ConsInte__b, HORA, MONOEF_ConsInte__b  ORDER BY USUARI_Nombre____b ASC, HORA ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";


        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";

       //    echo $Lsqlx;

        $camposconsulta1 = ', "SELECT G'.$Script.'_FechaInsercion as FECHA_CREACION ';

        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$Script."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9){
                if($key->tipo_Pregunta == '6'){
                    $camposconsulta1 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta1 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else{
                    $camposconsulta1 .= ' , '.$guion_c.$key->id.' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta1 .= ' , CONCAT(\'http://bpo.dyalogo.cloud:8080/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=\',  SUBSTRING_INDEX(SUBSTRING_INDEX('.$Scrip_s.'_IdLlamada, \'_\', 2), \'_\', -1)) as DescargaGrabacion FROM '.$BaseDatos.'.G'.$Script;
        $camposconsulta1 .= $joins;


        $consultas  .= $camposconsulta1.' WHERE '.$Scrip_s.'_FechaInsercion >= CURRENT_DATE();"';

       


        /* traducimos la base de datos */


        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Bdtraducir." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";


        $camposconsulta2 = ' , "SELECT G'.$Bdtraducir.'_ConsInte__b, G'.$Bdtraducir.'_FechaInsercion as FECHA_CREACION ';

        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$Bdtraducir."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9){

                if($key->tipo_Pregunta == '6'){
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= " , ".$muestraTraducida."_FecUltGes_b  AS FECHA_ULTIMA_GESTION , ".$muestraTraducida."_Comentari_b AS COMENTARIO, ".$muestraTraducida."_NumeInte__b AS NUMERO_DE_INTENTOS , CASE ".$muestraTraducida."_Estado____b WHEN '0' THEN 'SIN GESTION' WHEN 1 THEN 'REINTENTO AUTOMATICO' WHEN 2 THEN 'AGENDA' WHEN 3 THEN 'NO REINTENTAR'  END AS ESTADO, A.MONOEF_Texto_____b AS 'ULTIMA GESTION', B.MONOEF_Texto_____b AS 'GESTION MAS IMPORTANTE', ".$muestraTraducida."_FeGeMaIm__b AS FECHA_GESTION_MAS_IMPORTANTE , CASE ".$muestraTraducida."_ConUltGes_b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION";
        $camposconsulta2 .= ' FROM '.$BaseDatos.'.G'.$Bdtraducir;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' JOIN '.$BaseDatos.'.'.$muestraTraducida.' ON '.$muestraTraducida.'_CoInMiPo__b = G'.$Bdtraducir.'_ConsInte__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = '.$muestraTraducida.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = '.$muestraTraducida.'_GesMasImp_b ORDER BY G'.$Bdtraducir.'_ConsInte__b DESC LIMIT 5000;';


        $consultas  .= $camposconsulta2.'"';


        $consultas .= ', "SELECT * FROM '.$BaseDatos_telefonia.'.dy_informacion_actual_campanas WHERE id_campana='.$idCbxCaman.' AND fecha >= current_date();", "SELECT * FROM '.$BaseDatos_telefonia.'.dy_informacion_intervalos_h WHERE id_campana='.$idCbxCaman.' AND fecha >= current_date();"';

        $nombres = '"ConoEfectividad","Tipificaciones","TipificacionesPorReintento","TipificacionesPorHora","TipificacionesPorAgente","TipificacionesPorAgenteHora","SabanaGestiones","BDconResumenGestion","LlamadasEntrantesPorDia","LlamadasEntrantesPorHora"';

        $reportesXX = $mysqli->real_escape_string($consultas);

        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_campana_crm =".$id_usuario." AND tipo_periodicidad = 1 LIMIT 1";
        $res = $mysqli->query($Lsql_InsercionRepor);

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();

            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombres."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '/tmp/reportes_".$id_usuario."' wHERE id = ".$datosDeEstoCampana['id'];
            }

        }else{
            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$_SESSION['CORREO']."' , 1 , '20:20' , 'REPORTE ".$nombreCamp." - Diario' );";
            }
        }
        

        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 1 ".$mysqli->error;
        }

    }

    function generarReportesExcell_semanales($id_usuario, $destinatarios = null, $copia=null, $asunto = null, $hora = null){
        include(__DIR__."/pages/conexion.php"); 
        $ScriptLsql = "SELECT G10_C73 , G10_C74 , G10_C75 , G10_C106, G10_C71 FROM ".$BaseDatos_systema.".G10 WHERE G10_ConsInte__b = ".$id_usuario;
        $resScript  = $mysqli->query($ScriptLsql);
        $datosSc    = $resScript->fetch_array();
        $Script     = $datosSc['G10_C73'];
        $Bdtraducir = $datosSc['G10_C74'];
        $MuestraTra = $datosSc['G10_C75'];
        $idCbxCaman = $datosSc['G10_C106'];
        $nombreCamp = $datosSc['G10_C71'];

        $guion_s = 'G'.$Bdtraducir;
        $guion_c = 'G'.$Bdtraducir."_C";
        $Scrip_s = 'G'.$Script;
        $muestraTraducida = "G".$Bdtraducir."_M".$MuestraTra;

        $consultas = '';

        $consultas .= "\"SELECT CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, COUNT(*) AS TOTAL FROM ".$BaseDatos.".".$guion_s." LEFT JOIN ".$BaseDatos.".".$muestraTraducida." ON ".$guion_s."_ConsInte__b = ".$muestraTraducida."_CoInMiPo__b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY MONOEF_Contacto__b ORDER BY MONOEF_Contacto__b DESC;\"";

        $consultas .= ", \"SELECT CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b as 'TIPIFICACION MAS IMPORTANTE', COUNT(*) AS TOTAL FROM ".$BaseDatos.".".$guion_s." LEFT JOIN ".$BaseDatos.".".$muestraTraducida." ON ".$guion_s."_ConsInte__b = ".$muestraTraducida."_CoInMiPo__b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY MONOEF_ConsInte__b ORDER BY MONOEF_Contacto__b DESC;\"";


        $consultas .= ", \"SELECT ".$muestraTraducida."_NumeInte__b AS NUMERO_DE_INTENTOS, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS 'TIPIFICACION MAS IMPORTANTE', Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$muestraTraducida." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY ".$muestraTraducida."_NumeInte__b, MONOEF_ConsInte__b ORDER BY ".$muestraTraducida."_NumeInte__b ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";
        
        $LsqlTipificacion = "SELECT G5_C311 FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$Script;
        $resLsqlTipificacion = $mysqli->query($LsqlTipificacion);
        $dataTipificacion = $resLsqlTipificacion->fetch_array();

        

        $consultas .= ", \"SELECT DATE_FORMAT(".$Scrip_s."_FechaInsercion, '%H') AS HORA, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b WHERE ".$Scrip_s."_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURRENT_DATE() GROUP BY HORA, MONOEF_ConsInte__b  ORDER BY HORA ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";

        $consultas .= ", \"SELECT USUARI_Nombre____b AS AGENTE, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b LEFT JOIN ".$BaseDatos_systema.".USUARI ON USUARI_ConsInte__b = ".$Scrip_s."_Usuario WHERE ".$Scrip_s."_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURRENT_DATE() GROUP BY USUARI_ConsInte__b, MONOEF_ConsInte__b  ORDER BY USUARI_Nombre____b ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";


        $consultas .= ", \"SELECT USUARI_Nombre____b AS AGENTE, DATE_FORMAT(".$Scrip_s."_FechaInsercion, '%H') AS HORA, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b LEFT JOIN ".$BaseDatos_systema.".USUARI ON USUARI_ConsInte__b = ".$Scrip_s."_Usuario WHERE ".$Scrip_s."_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURRENT_DATE() GROUP BY USUARI_ConsInte__b, HORA, MONOEF_ConsInte__b  ORDER BY USUARI_Nombre____b ASC, HORA ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";


        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";

       //    echo $Lsqlx;

        $camposconsulta1 = ', "SELECT G'.$Script.'_FechaInsercion as FECHA_CREACION ';

        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$Script."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9){
                if($key->tipo_Pregunta == '6'){
                    $camposconsulta1 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta1 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else{
                    $camposconsulta1 .= ' , '.$guion_c.$key->id.' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta1 .= ' , CONCAT(\'http://bpo.dyalogo.cloud:8080/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=\',  SUBSTRING_INDEX(SUBSTRING_INDEX('.$Scrip_s.'_IdLlamada, \'_\', 2), \'_\', -1)) as DescargaGrabacion FROM '.$BaseDatos.'.G'.$Script;
        $camposconsulta1 .= $joins;


        $consultas  .= $camposconsulta1.' WHERE '.$Scrip_s.'_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURRENT_DATE(); "';

       


        /* traducimos la base de datos */


        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Bdtraducir." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";


        $camposconsulta2 = ' , "SELECT G'.$Bdtraducir.'_ConsInte__b, G'.$Bdtraducir.'_FechaInsercion as FECHA_CREACION ';

        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$Bdtraducir."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9){

                if($key->tipo_Pregunta == '6'){
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= " , ".$muestraTraducida."_FecUltGes_b  AS FECHA_ULTIMA_GESTION , ".$muestraTraducida."_Comentari_b AS COMENTARIO, ".$muestraTraducida."_NumeInte__b AS NUMERO_DE_INTENTOS , CASE ".$muestraTraducida."_Estado____b WHEN '0' THEN 'SIN GESTION' WHEN 1 THEN 'REINTENTO AUTOMATICO' WHEN 2 THEN 'AGENDA' WHEN 3 THEN 'NO REINTENTAR' END AS ESTADO , A.MONOEF_Texto_____b AS 'ULTIMA GESTION', B.MONOEF_Texto_____b AS 'GESTION MAS IMPORTANTE', ".$muestraTraducida."_FeGeMaIm__b AS FECHA_GESTION_MAS_IMPORTANTE , CASE ".$muestraTraducida."_ConUltGes_b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION";
        $camposconsulta2 .= ' FROM '.$BaseDatos.'.G'.$Bdtraducir;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' JOIN '.$BaseDatos.'.'.$muestraTraducida.' ON '.$muestraTraducida.'_CoInMiPo__b = G'.$Bdtraducir.'_ConsInte__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = '.$muestraTraducida.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = '.$muestraTraducida.'_GesMasImp_b ORDER BY G'.$Bdtraducir.'_ConsInte__b DESC LIMIT 5000;';


        $consultas  .= $camposconsulta2.'"';


        $consultas .= ', "SELECT * FROM '.$BaseDatos_telefonia.'.dy_informacion_actual_campanas WHERE id_campana='.$idCbxCaman.' AND fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURRENT_DATE() ;", "SELECT * FROM '.$BaseDatos_telefonia.'.dy_informacion_intervalos_h WHERE id_campana='.$idCbxCaman.' AND fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURRENT_DATE() ;"';

        $nombres = '"ConoEfectividad","Tipificaciones","TipificacionesPorReintento","TipificacionesPorHora","TipificacionesPorAgente","TipificacionesPorAgenteHora","SabanaGestiones","BDconResumenGestion","LlamadasEntrantesPorDia","LlamadasEntrantesPorHora"';

        $reportesXX = $mysqli->real_escape_string($consultas);

        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_campana_crm =".$id_usuario." AND tipo_periodicidad = 2 LIMIT 1";
        $res = $mysqli->query($Lsql_InsercionRepor);

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();

           if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$destinatarios."' , 2, '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombres."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '/tmp/reportes_".$id_usuario."' wHERE id = ".$datosDeEstoCampana['id'];
            }
        }else{
            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$destinatarios."' , 2 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$_SESSION['CORREO']."' , 2 , '20:20' , 'REPORTE ".$nombreCamp." - Semanal' );";
            }
        }
        

        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 2 ".$mysqli->error;
        }

    }

    function generarReportesExcell_mensuales($id_usuario, $destinatarios = null, $copia=null, $asunto = null, $hora = null){
        include(__DIR__."/pages/conexion.php"); 
        $ScriptLsql = "SELECT G10_C73 , G10_C74 , G10_C75 , G10_C106, G10_C71 FROM ".$BaseDatos_systema.".G10 WHERE G10_ConsInte__b = ".$id_usuario;
        $resScript  = $mysqli->query($ScriptLsql);
        $datosSc    = $resScript->fetch_array();
        $Script     = $datosSc['G10_C73'];
        $Bdtraducir = $datosSc['G10_C74'];
        $MuestraTra = $datosSc['G10_C75'];
        $idCbxCaman = $datosSc['G10_C106'];
        $nombreCamp = $datosSc['G10_C71'];

        $guion_s = 'G'.$Bdtraducir;
        $guion_c = 'G'.$Bdtraducir."_C";
        $Scrip_s = 'G'.$Script;
        $muestraTraducida = "G".$Bdtraducir."_M".$MuestraTra;

        $consultas = '';

        $consultas .= "\"SELECT CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, COUNT(*) AS TOTAL FROM ".$BaseDatos.".".$guion_s." LEFT JOIN ".$BaseDatos.".".$muestraTraducida." ON ".$guion_s."_ConsInte__b = ".$muestraTraducida."_CoInMiPo__b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY MONOEF_Contacto__b ORDER BY MONOEF_Contacto__b DESC;\"";

        $consultas .= ", \"SELECT CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b as 'TIPIFICACION MAS IMPORTANTE', COUNT(*) AS TOTAL FROM ".$BaseDatos.".".$guion_s." LEFT JOIN ".$BaseDatos.".".$muestraTraducida." ON ".$guion_s."_ConsInte__b = ".$muestraTraducida."_CoInMiPo__b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY MONOEF_ConsInte__b ORDER BY MONOEF_Contacto__b DESC;\"";


        $consultas .= ", \"SELECT ".$muestraTraducida."_NumeInte__b AS NUMERO_DE_INTENTOS, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS 'TIPIFICACION MAS IMPORTANTE', Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$muestraTraducida." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = ".$muestraTraducida."_GesMasImp_b GROUP BY ".$muestraTraducida."_NumeInte__b, MONOEF_ConsInte__b ORDER BY ".$muestraTraducida."_NumeInte__b ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";
        
        $LsqlTipificacion = "SELECT G5_C311 FROM ".$BaseDatos_systema.".G5 WHERE G5_ConsInte__b = ".$Script;
        $resLsqlTipificacion = $mysqli->query($LsqlTipificacion);
        $dataTipificacion = $resLsqlTipificacion->fetch_array();

        

        $consultas .= ", \"SELECT DATE_FORMAT(".$Scrip_s."_FechaInsercion, '%H') AS HORA, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b WHERE ".$Scrip_s."_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURRENT_DATE() GROUP BY HORA, MONOEF_ConsInte__b  ORDER BY HORA ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";

        $consultas .= ", \"SELECT USUARI_Nombre____b AS AGENTE, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b LEFT JOIN ".$BaseDatos_systema.".USUARI ON USUARI_ConsInte__b = ".$Scrip_s."_Usuario WHERE ".$Scrip_s."_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURRENT_DATE() GROUP BY USUARI_ConsInte__b, MONOEF_ConsInte__b  ORDER BY USUARI_Nombre____b ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";


        $consultas .= ", \"SELECT USUARI_Nombre____b AS AGENTE, DATE_FORMAT(".$Scrip_s."_FechaInsercion, '%H') AS HORA, CASE MONOEF_Contacto__b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION, MONOEF_Texto_____b AS TIPIFICACION, Count(*) AS CANTIDAD FROM ".$BaseDatos.".".$Scrip_s." LEFT JOIN ".$BaseDatos_systema.".LISOPC ON LISOPC_ConsInte__b = ".$Scrip_s."_C".$dataTipificacion['G5_C311']." LEFT JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b LEFT JOIN ".$BaseDatos_systema.".USUARI ON USUARI_ConsInte__b = ".$Scrip_s."_Usuario WHERE ".$Scrip_s."_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURRENT_DATE() GROUP BY USUARI_ConsInte__b, HORA, MONOEF_ConsInte__b  ORDER BY USUARI_Nombre____b ASC, HORA ASC, MONOEF_Contacto__b DESC, MONOEF_Texto_____b ASC;\"";


        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";

       //    echo $Lsqlx;

        $camposconsulta1 = ', "SELECT G'.$Script.'_FechaInsercion as FECHA_CREACION ';

        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$Script."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9){
                if($key->tipo_Pregunta == '6'){
                    $camposconsulta1 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta1 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else{
                    $camposconsulta1 .= ' , '.$guion_c.$key->id.' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta1 .= ' , CONCAT(\'http://bpo.dyalogo.cloud:8080/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=\',  SUBSTRING_INDEX(SUBSTRING_INDEX('.$Scrip_s.'_IdLlamada, \'_\', 2), \'_\', -1)) as DescargaGrabacion FROM '.$BaseDatos.'.G'.$Script;
        $camposconsulta1 .= $joins;


        $consultas  .= $camposconsulta1.' WHERE '.$Scrip_s.'_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURRENT_DATE();"';

       


        /* traducimos la base de datos */


        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Bdtraducir." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";


        $camposconsulta2 = ' , "SELECT G'.$Bdtraducir.'_ConsInte__b, G'.$Bdtraducir.'_FechaInsercion as FECHA_CREACION ';

        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$Bdtraducir."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9){

                if($key->tipo_Pregunta == '6'){
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.strtoupper(str_replace(' ', '_',sanear_string( substr($key->titulo_pregunta, 0 , 20))))."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= " , ".$muestraTraducida."_FecUltGes_b  AS FECHA_ULTIMA_GESTION , ".$muestraTraducida."_Comentari_b AS COMENTARIO, ".$muestraTraducida."_NumeInte__b AS NUMERO_DE_INTENTOS , CASE ".$muestraTraducida."_Estado____b WHEN '0' THEN 'SIN GESTION' WHEN 1 THEN 'REINTENTO AUTOMATICO' WHEN 2 THEN 'AGENDA' WHEN 3 THEN 'NO REINTENTAR' END AS ESTADO , A.MONOEF_Texto_____b AS 'ULTIMA GESTION', B.MONOEF_Texto_____b AS 'GESTION MAS IMPORTANTE', ".$muestraTraducida."_FeGeMaIm__b AS FECHA_GESTION_MAS_IMPORTANTE , CASE ".$muestraTraducida."_ConUltGes_b WHEN 1 THEN 'CARGADO' WHEN 2 THEN 'NO GESTIONABLES' WHEN 3 THEN 'SIN GESTION' WHEN 4 THEN 'NO CONTACTADO' WHEN 5 THEN 'CONTACTO NO PERTINENTE' WHEN 6 THEN 'NO EFECTIVO' WHEN 7 THEN 'EFECTIVO' END AS CLASIFICACION";
        $camposconsulta2 .= ' FROM '.$BaseDatos.'.G'.$Bdtraducir;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' JOIN '.$BaseDatos.'.'.$muestraTraducida.' ON '.$muestraTraducida.'_CoInMiPo__b = G'.$Bdtraducir.'_ConsInte__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = '.$muestraTraducida.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = '.$muestraTraducida.'_GesMasImp_b ORDER BY G'.$Bdtraducir.'_ConsInte__b DESC LIMIT 5000;';


        $consultas  .= $camposconsulta2.'"';


        $consultas .= ', "SELECT * FROM '.$BaseDatos_telefonia.'.dy_informacion_actual_campanas WHERE id_campana='.$idCbxCaman.' AND fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURRENT_DATE();", "SELECT * FROM '.$BaseDatos_telefonia.'.dy_informacion_intervalos_h WHERE id_campana='.$idCbxCaman.' AND fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURRENT_DATE();"';

        $nombres = '"ConoEfectividad","Tipificaciones","TipificacionesPorReintento","TipificacionesPorHora","TipificacionesPorAgente","TipificacionesPorAgenteHora","SabanaGestiones","BDconResumenGestion","LlamadasEntrantesPorDia","LlamadasEntrantesPorHora"';


        $reportesXX = $mysqli->real_escape_string($consultas);

        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_campana_crm =".$id_usuario." AND tipo_periodicidad = 3 LIMIT 1";
        $res = $mysqli->query($Lsql_InsercionRepor);

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();

            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$destinatarios."' , 3 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombres."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '/tmp/reportes_".$id_usuario."' wHERE id = ".$datosDeEstoCampana['id'];
            }
        }else{
            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$destinatarios."' , 3 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_campana_crm ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombres."' , ".$_SESSION['HUESPED'].", '/tmp/reportes_".$id_usuario."' , ".$id_usuario." , '".$_SESSION['CORREO']."' , 3 , '20:20' , 'REPORTE ".$nombreCamp." - Mensual' );";
            }
        }
        
        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 3 ".$mysqli->error;
        }

    }


    function sanear_string($string) { 
       
        $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
        $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
        $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
        $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
        $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
        $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); 
        //Esta parte se encarga de eliminar cualquier caracter extraño 
        $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string ); 
        return $string; 
    }