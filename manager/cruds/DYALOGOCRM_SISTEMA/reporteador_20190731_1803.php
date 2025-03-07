<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);



    function depCadenas($string){
        $string = strtolower($string);
        $conservar = '0-9a-z'; // juego de caracteres a conservar
        $regex = sprintf('~[^%s]++~i', $conservar); //se le ordena arrancar todo segun la regla $conservar
        $cadena = preg_replace($regex, '', $string);//se reemplaza a vacio por la $regex a la cadena;
        return $cadena;
    } 
    
    function sanear_strings($string) { 

       // $string = utf8_decode($string);

        $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
        $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
        $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
        $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
        $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
        $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); 
        //Esta parte se encarga de eliminar cualquier caracter extraNHO 
        $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string ); 
        return $string; 
    }





    function ctrCrearReportesDiarios($id_estrategia, $destinatarios = null, $copia = null, $copiaOculta = null, $asunto = null, $hora = null){
        include(__DIR__."../../../pages/conexion.php");

        /* primero obtenemos la base de datos de la estrategia */
        $Lsql = "SELECT G2_C69 FROM ".$BaseDatos_systema.".G2 WHERE G2_ConsInte__b = ".$id_estrategia;
        $resL = $mysqli->query($Lsql);
        $datL = $resL->fetch_array();
        $base = $datL['G2_C69'];


        $consultas = '';
        $nombresRe = '';

        /*$estado_dy_Lsql = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'ESTADO_DY' AND PREGUN_ConsInte__GUION__b = ".$base.";";
        $res_do_dy_Lsql = $mysqli->query($estado_dy_Lsql);
        $dato_o_dy_Lsql = $res_do_dy_Lsql->fetch_array();
        $estado_dy_camp = $dato_o_dy_Lsql['PREGUN_ConsInte__b'];

        $consultas .= '"SELECT IFNULL(LISOPC_Nombre____b, \'Por gestionar\') as Estado, count(*) as Cantidad FROM '.$BaseDatos.'.G'.$base.' LEFT JOIN '.$BaseDatos_systema.'.LISOPC ON G'.$base.'_C'.$estado_dy_camp.' = LISOPC_ConsInte__b GROUP BY G'.$base.'_C'.$estado_dy_camp.' ORDER BY count(*) DESC;" ';

        $nombresRe .= '"CONO ESTADOS"';*/
        
        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);
        while ($key = $resEspaslq->fetch_object()) {
            /* Recorremos las campañas para obtener su script */
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                $CampanLsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;   
                $resPanLsql = $mysqli->query($CampanLsql);
                $dataAnLsql = $resPanLsql->fetch_array();
                $Script     = $dataAnLsql['CAMPAN_ConsInte__GUION__Gui_b']; 
                $titulodeLapregunta = $dataAnLsql['CAMPAN_Nombre____b'];
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 15);

                $nombresRe .= '"GESTIONES '.$titulodeLapregunta.'_'.$key->ESTPAS_ConsInte__CAMPAN_b.'" ,';
                /* Ahora si recorremos el Script */ 
                $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
                $joins = '';
                $alfa = 0;
                $guion_c = 'G'.$Script."_C";
                $guion_Fecha_Insercion = 'G'.$Script."_";
                $camposconsulta1 = '"SELECT ';
                $iTo = 0;
                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_FechaInsercion'";

                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }

                    $camposconsulta1 .= $separador.' G'.$Script.'_FechaInsercion AS FECHA_CREACION, YEAR(G'.$Script.'_FechaInsercion) AS ANHO_CREACION, MONTH(G'.$Script.'_FechaInsercion) AS MES_CREACION, DAY(G'.$Script.'_FechaInsercion) AS DIA_CREACION, HOUR(G'.$Script.'_FechaInsercion) AS HORA_CREACION, MINUTE(G'.$Script.'_FechaInsercion) AS MINUTO_CREACION, SECOND(G'.$Script.'_FechaInsercion) AS SEGUNDO_CREACION ';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }
                
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Fecha'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.$guion_c.$key->id.' AS FECHA_GESTION, YEAR('.$guion_c.$key->id.') AS ANHO_GESTION, MONTH('.$guion_c.$key->id.') AS MES_GESTION, DAY('.$guion_c.$key->id.') AS DIA_GESTION ';
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Hora'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' HOUR('.$guion_c.$key->id.') AS HORA_GESTION, MINUTE('.$guion_c.$key->id.') AS MINUTO_GESTION, SECOND('.$guion_c.$key->id.') AS SEGUNDO_GESTION ';
                    }
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Duracion___b'";

                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }

                    $camposconsulta1 .= $separador." DATE_FORMAT(G".$Script ."_Duracion___b, '%H:%i:%S') AS DURACION_GESTION";
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Agente'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){ 
                            $separador = '';
                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            $camposconsulta1 .= $separador.$guion_c.$key->id.' AS AGENTE';
                    }    
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Sentido___b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }
                    $camposconsulta1 .= $separador.' G'.$Script.'_Sentido___b AS SENTIDO';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Canal_____b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }
                    $camposconsulta1 .= $separador.' G'.$Script .'_Canal_____b AS CANAL';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (3)  AND PREGUN_Texto_____b IN ('Tipificacion','Reintento','Fecha Agenda','Observacion')";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){ 
                            $separador = '';
                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            if ($key->titulo_pregunta == 'Tipificación') {
                                $camposconsulta1 .= $separador.' ug.LISOPC_Nombre____b AS ULTIMA_GESTION';
                            }elseif ($key->titulo_pregunta == 'Reintento') {
                                $camposconsulta1 .= $separador." (CASE WHEN ".$guion_c.$key->id." = 0 THEN 'SIN GESTION' WHEN ".$guion_c.$key->id." = 1 THEN 'REINTENTO AUTOMATICO' WHEN ".$guion_c.$key->id." = 2 THEN 'AGENDO' WHEN ".$guion_c.$key->id." = 3 THEN 'NO REINTENTAR' ELSE 'SIN GESTION' END) AS REINTENTO";
                            }elseif ($key->titulo_pregunta == 'Fecha Agenda') {
                                $camposconsulta1 .= $separador.$guion_c.$key->id." AS FECHA_AGENDA,YEAR(".$guion_c.$key->id.") AS ANHO_AGENDA, MONTH(".$guion_c.$key->id.") AS MES_AGENDA, DAY(".$guion_c.$key->id.") AS DIA_AGENDA, HOUR(".$guion_c.$key->id.") AS HORA_AGENDA, MINUTE(".$guion_c.$key->id.") AS MINUTO_AGENDA, SECOND(".$guion_c.$key->id.") AS SECOND_AGENDA ";
                            }elseif ($key->titulo_pregunta == 'Observacion') {
                                $camposconsulta1 .= $separador.$guion_c.$key->id." AS OBSERVACION ";
                            }
                    }    
                }                

                $separador = '';
                if($iTo != 0){
                    $separador = ' , ';
                }

                $camposconsulta1 .= $separador.' CONCAT(\'https://bpo.dyalogo.cloud:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=\',  SUBSTRING_INDEX(SUBSTRING_INDEX(G'.$Script.'_IdLlamada, \'_\', 2), \'_\', -1)) as DescargaGrabacion ';
                if($iTo == 0){
                    $iTo += 1;
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b = 1 ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";
                $campos_4 = $mysqli->query($Lsqlx);
                
                while($key = $campos_4->fetch_object()){

                    if($key->titulo_pregunta != 'ORIGEN_DY_WF' && $key->titulo_pregunta != 'OPTIN_DY_WF' && $key->titulo_pregunta != 'ESTADO_DY'){

                        if($key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12 ){
                            $separador = '';

                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            

                            $titulodeLapregunta = $key->titulo_pregunta;
                            $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                            $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                            $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                            if($key->tipo_Pregunta == '6'){
                                
                                $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                                
                                $alfa++;

                            }else if($key->tipo_Pregunta =='11'){
                                
                                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                                $campoPrimario = '';
                                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                                $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                                

                                $camposconsulta1 .= $separador.' G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;

                            }else if($key->tipo_Pregunta == '5'){ 

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                            
                            }else if($key->tipo_Pregunta == '10'){

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                            }else{

                                $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                            }

                            $iTo++;
                        }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
                    }
                   
                }//cierro este while $key = $campos_4->fetch_object()
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (3)  AND PREGUN_Texto_____b = 'Tipificacion'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $joins .= " LEFT JOIN ".$BaseDatos_systema.".LISOPC ug ON ".$guion_c.$key->id." = ug.LISOPC_ConsInte__b ";
                    }
                }//cierro este while $key = $campos_4->fetch_object()

                $camposconsulta1 .= ' FROM '.$BaseDatos.'.G'.$Script;
                $camposconsulta1 .= $joins;
                $consultas  .= $camposconsulta1.' WHERE DATE_FORMAT(G'.$Script.'_FechaInsercion,\'%Y-%m-%d\') > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),\'%Y-%m-%d\') ORDER BY ANHO_CREACION DESC, MES_CREACION DESC, DIA_CREACION DESC, HORA_CREACION DESC, MINUTO_CREACION DESC, SEGUNDO_CREACION DESC;" , ';
            }//WHERE DATE_FORMAT(G1122_FechaInsercion,'%Y-%m-%d') > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),'%Y-%m-%d')
            
        }//termino de recorrer los scripts de la tabla

        $nombresRe .= ' "RESUMEN BASE DATOS"';
        
        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";



        $camposconsulta2 = '"SELECT G'.$base.'_ConsInte__b AS ID, DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') as FECHA_CREACION, YEAR(G'.$base.'_FechaInsercion) AS ANHO_CREACION, MONTH(G'.$base.'_FechaInsercion) AS MES_CREACION, DAY(G'.$base.'_FechaInsercion) AS DIA_CREACION, HOUR(G'.$base.'_FechaInsercion) AS HORA_CREACION, MINUTE(G'.$base.'_FechaInsercion) AS MINUTO_CREACION, SECOND(G'.$base.'_FechaInsercion) AS SEGUNDO_CREACION ';

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_EstadoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , D.LISOPC_Nombre____b AS ESTADO_GMI, B.MONOEF_Texto_____b AS GESTION_MAS_IMPORTANTE ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_TipoReintentoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
                        $camposconsulta2 .= ' , (CASE WHEN G'.$base.'_TipoReintentoGMI_b = 0 THEN \'SIN GESTION\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 1 THEN \'REINENTO AUTOMATICO\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 2 THEN \'AGENDO\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 3 THEN \'NO REINTENTAR\' ELSE \'SIN GESTION\' END) AS REINTENTO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecHorAgeGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecHorAgeGMI_b AS FECHA_AGENDA_GMI, YEAR(G'.$base.'_FecHorAgeGMI_b) AS ANHO_AGENDA_GMI, MONTH(G'.$base.'_FecHorAgeGMI_b) AS MES_AGENDA_GMI, DAY(G'.$base.'_FecHorAgeGMI_b) AS DIA_AGENDA_GMI, HOUR(G'.$base.'_FecHorAgeGMI_b) AS HORA_AGENDA_GMI, MINUTE(G'.$base.'_FecHorAgeGMI_b) AS MINUTO_AGENDA_GMI, SECOND(G'.$base.'_FecHorAgeGMI_b) AS SEGUNDO_AGENDA_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ComentarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_ComentarioGMI_b AS COMENTARIO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , C.USUARI_Nombre____b AS AGENTE_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_CanalGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_CanalGMI_b AS CANAL_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_SentidoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_SentidoGMI_b AS SENTIDO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FeGeMaIm__b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FeGeMaIm__b AS FECHA_GMI, YEAR(G'.$base.'_FeGeMaIm__b) AS ANHO_GMI, MONTH(G'.$base.'_FeGeMaIm__b) AS MES_GMI, DAY(G'.$base.'_FeGeMaIm__b) AS DIA_GMI, HOUR(G'.$base.'_FeGeMaIm__b) AS HORA_GMI, MINUTE(G'.$base.'_FeGeMaIm__b) AS MINUTO_GMI, SECOND(G'.$base.'_FeGeMaIm__b) AS SEGUNDO_GMI, (DATE_FORMAT(G'.$base.'_FeGeMaIm__b,\'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FechaInsercion,\'%Y-%m-%d %H:%i:%S\')) AS DIAS_MADURACION_GMI ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ClasificacionUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , ug.MONOEF_Texto_____b AS CLASIFICACION_UG ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UltiGest__b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y E.MONOEF_Texto_____b
            $camposconsulta2 .= ' , A.MONOEF_Texto_____b AS ULTIMA_GESTION ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_TipoReintentoUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
                        $camposconsulta2 .= ' , (CASE WHEN G'.$base.'_TipoReintentoUG_b = 0 THEN \'SIN GESTION\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 1 THEN \'REINENTO AUTOMATICO\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 2 THEN \'AGENDO\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 3 THEN \'NO REINTENTAR\' ELSE \'SIN GESTION\' END) AS REINTENTO_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecHorAgeUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecHorAgeUG_b AS FECHA_AGENDA_UG, YEAR(G'.$base.'_FecHorAgeUG_b) AS ANHO_AGENDA_UG, MONTH(G'.$base.'_FecHorAgeUG_b) AS MES_AGENDA_UG, DAY(G'.$base.'_FecHorAgeUG_b) AS DIA_AGENDA_UG, HOUR(G'.$base.'_FecHorAgeUG_b) AS HORA_AGENDA_UG, MINUTE(G'.$base.'_FecHorAgeUG_b) AS MINUTO_AGENDA_UG, SECOND(G'.$base.'_FecHorAgeUG_b) AS SEGUNDO_AGENDA_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ComentarioUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_ComentarioUG_b AS COMENTARIO_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , C.USUARI_Nombre____b AS AGENTE_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_Canal_____b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_Canal_____b AS CANAL_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_Sentido___b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_Sentido___b AS SENTIDO_UG ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecUltGes_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecUltGes_b AS FECHA_UG, YEAR(G'.$base.'_FecUltGes_b) AS ANHO_UG, MONTH(G'.$base.'_FecUltGes_b) AS MES_UG, DAY(G'.$base.'_FecUltGes_b) AS DIA_UG, HOUR(G'.$base.'_FecUltGes_b) AS HORA_UG, MINUTE(G'.$base.'_FecUltGes_b) AS MINUTO_UG, SECOND(G'.$base.'_FecUltGes_b) AS SEGUNDO_UG, (DAY(DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FecUltGes_b , \'%Y-%m-%d %H:%i:%S\')) - DAY(DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FeGeMaIm__b , \'%Y-%m-%d %H:%i:%S\'))) AS DIAS_SIN_CONTACTO ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_CantidadIntentos'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_CantidadIntentos AS CANTIDAD_INTENTOS ';
        }

        $Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b IN (3,4) ORDER BY PREGUN_OrdePreg__b ASC";

        $result = $mysqli->query($Lsql);
        while($key = $result->fetch_object()){
            if ($key->titulo_pregunta == 'ORIGEN_DY_WF') {
                $camposconsulta2 .= " , G".$base."_C".$key->id." AS ORIGEN_DY_WF ";
            }elseif ($key->titulo_pregunta == 'OPTIN_DY_WF') {
                $camposconsulta2 .= " , G".$base."_C".$key->id." AS OPTIN_DY_WF ";
            }
        }



        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$base."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12){

                $titulodeLapregunta = $key->titulo_pregunta;
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                if($key->tipo_Pregunta == '6'){
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else  if($key->tipo_Pregunta == '5'){ 

                    $camposconsulta2 .= ' ,  DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                
                }else  if($key->tipo_Pregunta == '10'){

                    $camposconsulta2 .= ' , DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
        }//cierro este while $key = $campos_4->fetch_object()

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ClasificacionUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF ug ON G'.$base.'_ClasificacionUG_b = ug.MONOEF_ConsInte__b ';
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= ' FROM '.$BaseDatos.'.G'.$base;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = G'.$base.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = G'.$base.'_GesMasImp_b '; 



        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.USUARI AS C ON G'.$base.'_UsuarioGMI_b = C.USUARI_ConsInte__b ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_EstadoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC AS D ON G'.$base.'_EstadoGMI_b = D.LISOPC_ConsInte__b ';
        }

        $camposconsulta2 .= ' ORDER BY ANHO_CREACION DESC, MES_CREACION DESC, DIA_CREACION DESC, HORA_CREACION DESC, MINUTO_CREACION DESC, SEGUNDO_CREACION DESC LIMIT 2000;';

        $consultas  .= $camposconsulta2.'"';

        /* Ahora obtenemos el intevalo de llamadas por campaña de la estrategia */
        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_Tipo______b = 1 AND ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);

        while ($key = $resEspaslq->fetch_object()) {
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                $CampanLsql = "SELECT CAMPAN_Nombre____b, CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;   
                $resPanLsql = $mysqli->query($CampanLsql);
                $dataAnLsql = $resPanLsql->fetch_array();
                $NombreCamp = $dataAnLsql['CAMPAN_Nombre____b']; 
                $CAMPAN_IdCamCbx__b = $dataAnLsql['CAMPAN_IdCamCbx__b'];

                $titulodeLapregunta = $NombreCamp;
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);


                $nombresRe .= ' , "ACD '.$titulodeLapregunta.'"';

                // $consultas .= ' , "SELECT intervalo_traducido, dy_informacion_intervalos_h.* FROM dyalogo_telefonia.dy_informacion_intervalos_h WHERE id_campana='.$CAMPAN_IdCamCbx__b.' and fecha  = DAY(current_date()) ORDER BY fecha DESC;"';

                $consultas .= " , \"SELECT fecha AS Fecha, nombre_campana AS Campana, intervalo_traducido AS Hora, CONCAT(dy_informacion_intervalos_h.meta_tsf, '/',dy_informacion_intervalos_h.segundos_tsf) AS ANS, recibidas AS Entran, SEC_TO_TIME(FLOOR(espera_promedio)) AS ASA, SEC_TO_TIME(FLOOR(espera_minimo)) AS 'ASAMin', SEC_TO_TIME(FLOOR(espera_maximo)) AS 'ASAMax', SEC_TO_TIME(FLOOR(espera_total)) AS 'T.TCola', contestadas AS Cont, contestadas_ns AS 'Cont<=', contestadas_despues_s_tsf AS 'Cont>', REPLACE(ROUND(((contestadas*100)/recibidas), 2), '.00', '') AS 'Cont/Entran', REPLACE(ROUND(((contestadas_ns*100)/recibidas), 2), '.00', '') AS 'TSF%', IF(ISNULL(ROUND(((contestadas_ns*100)/contestadas), 2)), 0, REPLACE(ROUND(((contestadas_ns*100)/contestadas), 2), '.00', '')) AS 'TSF<=%', REPLACE(ROUND(((contestadas_despues_s_tsf*100)/recibidas), 2), '.00', '') AS 'TSF>Cont%', IF(ISNULL(SEC_TO_TIME(FLOOR(tiempo_conversacion_total/contestadas))), SEC_TO_TIME(0), SEC_TO_TIME(FLOOR(tiempo_conversacion_total/contestadas))) AS 'Pro.Conv', SEC_TO_TIME(FLOOR(tiempo_conversacion_total)) AS 'T.T.Conv', abandonadas_total AS 'Aban', abandonadas AS 'Aban<=', abandonadas_despues_s_tsf AS 'Aban>', REPLACE(ROUND(((abandonadas_total*100)/recibidas), 2), '.00', '') AS 'Aban%', REPLACE(ROUND(((abandonadas_despues_s_tsf*100)/(contestadas+abandonadas_despues_s_tsf)), 2), '.00', '') AS 'Aban>%', SEC_TO_TIME(FLOOR(espera_promedio_abandono)) AS 'Pro.Aban', SEC_TO_TIME(FLOOR(espera_total_abandono)) AS 'T.T.Aban', SEC_TO_TIME(FLOOR(espera_minima_abandono)) AS 'T.AbanMin', SEC_TO_TIME(FLOOR(espera_maxima_abandono)) AS 'T.AbanMax' FROM dyalogo_telefonia.dy_informacion_intervalos_h LEFT JOIN dyalogo_telefonia.dy_campanas ON dy_campanas.id = dy_informacion_intervalos_h.id_campana WHERE id_campana=".$CAMPAN_IdCamCbx__b." and fecha  = DAY(current_date()) ORDER BY fecha DESC;\"";
            }
        }

        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);
        $numerosCampanha = '';
        $i = 0;
        while ($key = $resEspaslq->fetch_object()) {
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                if($i == 0){
                    $numerosCampanha .= $key->ESTPAS_ConsInte__CAMPAN_b;
                }else{
                    $numerosCampanha .= ' , '.$key->ESTPAS_ConsInte__CAMPAN_b;
                }
                $i++;
            }
        }

        /* Vamos a obtener las secciones */
        $nombresRe .= ' , "SESIONES"';
        $consultas .= ' , "SELECT agente_nombre as Agente,  DATE_FORMAT(min(fecha_hora_inicio) , \'%Y-%m-%d %H:%i:%S\') as Inicio, DATE_FORMAT(max(fecha_hora_fin), \'%Y-%m-%d %H:%i:%S\') as Fin, format(sum(duracion) / 60 , 2) as \'Duracion en minutos\' FROM dyalogo_telefonia.dy_v_historico_sesiones WHERE DATE_FORMAT(fecha_hora_inicio,\'%Y-%m-%d\') > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),\'%Y-%m-%d\') and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.')) group by agente_id;"';
        

        /* Vamos a llenar las Pausas */
        $nombresRe .= ' , "PAUSAS"';
        $consultas .= ' , "SELECT agente_nombre as Agente, DATE_FORMAT(fecha_hora_inicio , \'%Y-%m-%d %H:%i:%S\') as Inicio,  DATE_FORMAT(fecha_hora_fin , \'%Y-%m-%d %H:%i:%S\') as Fin, format(duracion/60, 2) as \'Duracion en minutos\', tipo_descanso_nombre as TipoPausa FROM dyalogo_telefonia.dy_v_historico_descansos WHERE DATE_FORMAT(fecha_hora_inicio,\'%Y-%m-%d\') > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),\'%Y-%m-%d\') and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.'));"';
        
        $nombresRe .= ' , "GESTIONES DE MAIL Y SMS"';
        $consultas .= ' , "SELECT SMS_MAIL_ESTPAS_Nombre__b  as Nombre_Paso, SMS_MAIL_Fecha__b as Fecha, SMS_MAIL_Tipo_b as Tipo_Gestion, SMS_MAIL_Total_Exitos_b AS Exitos, SMS_MAIL_Total_Fallos_b AS Fallos, SMS_MAIL_Total_b as Total_registros FROM '.$BaseDatos_systema.'.REPORTES_SMS_MAIL WHERE SMS_MAIL_ESTRAT_ConsInte__b = '.$id_estrategia.' AND DATE_FORMAT(SMS_MAIL_Fecha__b,\'%Y-%m-%d\') > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),\'%Y-%m-%d\');"';


        /* Insertamos los reportes */ 
        $reportesXX = $mysqli->real_escape_string($consultas);
        $nombresXXX = $mysqli->real_escape_string($nombresRe);
        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_estrategia =".$id_estrategia." AND tipo_periodicidad = 1 AND nombres_hojas NOT LIKE '%PausasConHorarioMuyLargas%'";
        $res = $mysqli->query($Lsql_InsercionRepor);

         //capturar id de reportes diarios
          $idReportesDiarios=[];
          while($fila = $res->fetch_assoc()){
                $idReportesDiarios[]['id']=$fila["id"];
          }  

        $LsqlEstra = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$id_estrategia;
        $res_Estra = $mysqli->query($LsqlEstra);
        $datos = $res_Estra->fetch_array();

        $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);

        // $proyecto = substr($proyecto, 0, 8);
        $proyecto = sanear_strings($proyecto);
        // echo $proyecto; die();

        $estrategia = str_replace(' ', '', $datos['ESTRAT_Nombre____b']);
        // $estrategia = substr($estrategia, 0, 8);
        $estrategia = sanear_strings($estrategia);

        $ruta_archivo = "/tmp/".$proyecto."_".$estrategia."_DIARIO_";

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();
            

            if($destinatarios != null && $asunto != null && $hora != null){
                //echo "Entro por esta lado";
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{
               
                    //actualiza los reportes todos los reportes diario
                  foreach ($idReportesDiarios as $data){

                     $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombresXXX."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '".$ruta_archivo."' WHERE id = ".$data["id"];

                      if($mysqli->query($Lsql) === true){

                        echo "diario";

                    }
                    
                    
                } 
            }

        }else{
            // echo "Entro por esta lado 3"; 
            if($destinatarios != null && $asunto != null && $hora != null){
             //   echo "Entro por esta lado"; 
                
                    $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";
                
            }else{
            //     echo "Entro por esta lado 2";
                if ($asunto != "::UPDATE::") {
                    $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$_SESSION['CORREO']."' , 1 , '23:59' , '".$_SESSION['PROYECTO']."_".sanear_strings(str_replace(' ','_',$datos['ESTRAT_Nombre____b']))."_DIARIO_".$datos['ESTRAT_ConsInte__b']."' );";
                }
            }
        }
        
        //echo  $Lsql;
        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 1 ".$mysqli->error;
        }

    }

    function ctrCrearReportesSemanales($id_estrategia, $destinatarios = null, $copia = null, $copiaOculta = null, $asunto = null, $hora = null){
        include(__DIR__."../../../pages/conexion.php");

        /* primero obtenemos la base de datos de la estrategia */
        $Lsql = "SELECT G2_C69 FROM ".$BaseDatos_systema.".G2 WHERE G2_ConsInte__b = ".$id_estrategia;
        $resL = $mysqli->query($Lsql);
        $datL = $resL->fetch_array();
        $base = $datL['G2_C69'];


        $consultas = '';
        $nombresRe = '';

        /*$estado_dy_Lsql = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'ESTADO_DY' AND PREGUN_ConsInte__GUION__b = ".$base.";";
        $res_do_dy_Lsql = $mysqli->query($estado_dy_Lsql);
        $dato_o_dy_Lsql = $res_do_dy_Lsql->fetch_array();
        $estado_dy_camp = $dato_o_dy_Lsql['PREGUN_ConsInte__b'];

        $consultas .= '"SELECT IFNULL(LISOPC_Nombre____b, \'Por gestionar\') as Estado, count(*) as Cantidad FROM '.$BaseDatos.'.G'.$base.' LEFT JOIN '.$BaseDatos_systema.'.LISOPC ON G'.$base.'_C'.$estado_dy_camp.' = LISOPC_ConsInte__b GROUP BY G'.$base.'_C'.$estado_dy_camp.' ORDER BY count(*) DESC;" ';

        $nombresRe .= '"CONO ESTADOS"';*/
        
        /* ahora obtenemos las gestiones de todas las campañas */
        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);
        while ($key = $resEspaslq->fetch_object()) {
            /* Recorremos las campañas para obtener su script */
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                $CampanLsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;   
                $resPanLsql = $mysqli->query($CampanLsql);
                $dataAnLsql = $resPanLsql->fetch_array();
                $Script     = $dataAnLsql['CAMPAN_ConsInte__GUION__Gui_b']; 
                
                $titulodeLapregunta = $dataAnLsql['CAMPAN_Nombre____b'];
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 15);

                $nombresRe .= '"GESTIONES '.$titulodeLapregunta.'_'.$key->ESTPAS_ConsInte__CAMPAN_b.'" ,';
                /* Ahora si recorremos el Script */ 
                $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
                $joins = '';
                $alfa = 0;
                $guion_c = 'G'.$Script."_C";
                $guion_Fecha_Insercion = 'G'.$Script."_";
                $camposconsulta1 = '"SELECT ';
                $iTo = 0;
                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_FechaInsercion'";

                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }

                    $camposconsulta1 .= $separador.' G'.$Script.'_FechaInsercion AS FECHA_CREACION, YEAR(G'.$Script.'_FechaInsercion) AS ANHO_CREACION, MONTH(G'.$Script.'_FechaInsercion) AS MES_CREACION, DAY(G'.$Script.'_FechaInsercion) AS DIA_CREACION, HOUR(G'.$Script.'_FechaInsercion) AS HORA_CREACION, MINUTE(G'.$Script.'_FechaInsercion) AS MINUTO_CREACION, SECOND(G'.$Script.'_FechaInsercion) AS SEGUNDO_CREACION ';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }
                
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Fecha'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.$guion_c.$key->id.' AS FECHA_GESTION, YEAR('.$guion_c.$key->id.') AS ANHO_GESTION, MONTH('.$guion_c.$key->id.') AS MES_GESTION, DAY('.$guion_c.$key->id.') AS DIA_GESTION ';
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Hora'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' HOUR('.$guion_c.$key->id.') AS HORA_GESTION, MINUTE('.$guion_c.$key->id.') AS MINUTO_GESTION, SECOND('.$guion_c.$key->id.') AS SEGUNDO_GESTION ';
                    }
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Duracion___b'";

                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }

                    $camposconsulta1 .= $separador." DATE_FORMAT(G".$Script ."_Duracion___b, '%H:%i:%S') AS DURACION_GESTION";
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Agente'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){ 
                            $separador = '';
                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            $camposconsulta1 .= $separador.$guion_c.$key->id.' AS AGENTE';
                    }    
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Sentido___b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }
                    $camposconsulta1 .= $separador.' G'.$Script.'_Sentido___b AS SENTIDO';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Canal_____b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }
                    $camposconsulta1 .= $separador.' G'.$Script .'_Canal_____b AS CANAL';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (3)  AND PREGUN_Texto_____b IN ('Tipificacion','Reintento','Fecha Agenda','Observacion')";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){ 
                            $separador = '';
                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            if ($key->titulo_pregunta == 'Tipificación') {
                                $camposconsulta1 .= $separador.' ug.LISOPC_Nombre____b AS ULTIMA_GESTION';
                            }elseif ($key->titulo_pregunta == 'Reintento') {
                                $camposconsulta1 .= $separador." (CASE WHEN ".$guion_c.$key->id." = 0 THEN 'SIN GESTION' WHEN ".$guion_c.$key->id." = 1 THEN 'REINTENTO AUTOMATICO' WHEN ".$guion_c.$key->id." = 2 THEN 'AGENDO' WHEN ".$guion_c.$key->id." = 3 THEN 'NO REINTENTAR' ELSE 'SIN GESTION' END) AS REINTENTO";
                            }elseif ($key->titulo_pregunta == 'Fecha Agenda') {
                                $camposconsulta1 .= $separador.$guion_c.$key->id." AS FECHA_AGENDA,YEAR(".$guion_c.$key->id.") AS ANHO_AGENDA, MONTH(".$guion_c.$key->id.") AS MES_AGENDA, DAY(".$guion_c.$key->id.") AS DIA_AGENDA, HOUR(".$guion_c.$key->id.") AS HORA_AGENDA, MINUTE(".$guion_c.$key->id.") AS MINUTO_AGENDA, SECOND(".$guion_c.$key->id.") AS SECOND_AGENDA ";
                            }elseif ($key->titulo_pregunta == 'Observacion') {
                                $camposconsulta1 .= $separador.$guion_c.$key->id." AS OBSERVACION ";
                            }
                    }    
                }                

                $separador = '';
                if($iTo != 0){
                    $separador = ' , ';
                }

                $camposconsulta1 .= $separador.' CONCAT(\'https://bpo.dyalogo.cloud:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=\',  SUBSTRING_INDEX(SUBSTRING_INDEX(G'.$Script.'_IdLlamada, \'_\', 2), \'_\', -1)) as DescargaGrabacion ';
                if($iTo == 0){
                    $iTo += 1;
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b = 1 ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";
                $campos_4 = $mysqli->query($Lsqlx);
                
                while($key = $campos_4->fetch_object()){

                    if($key->titulo_pregunta != 'ORIGEN_DY_WF' && $key->titulo_pregunta != 'OPTIN_DY_WF' && $key->titulo_pregunta != 'ESTADO_DY'){

                        if($key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12 ){
                            $separador = '';

                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            

                            $titulodeLapregunta = $key->titulo_pregunta;
                            $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                            $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                            $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                            if($key->tipo_Pregunta == '6'){
                                
                                $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                                
                                $alfa++;

                            }else if($key->tipo_Pregunta =='11'){
                                
                                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                                $campoPrimario = '';
                                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                                $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                                

                                $camposconsulta1 .= $separador.' G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;

                            }else if($key->tipo_Pregunta == '5'){ 

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                            
                            }else if($key->tipo_Pregunta == '10'){

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                            }else{

                                $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                            }

                            $iTo++;
                        }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
                    }
                   
                }//cierro este while $key = $campos_4->fetch_object()
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (3)  AND PREGUN_Texto_____b = 'Tipificacion'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $joins .= " LEFT JOIN ".$BaseDatos_systema.".LISOPC ug ON ".$guion_c.$key->id." = ug.LISOPC_ConsInte__b ";
                    }
                }//cierro este while $key = $campos_4->fetch_object()

                $camposconsulta1 .= ' FROM '.$BaseDatos.'.G'.$Script;
                $camposconsulta1 .= $joins;
                $consultas  .= $camposconsulta1.' WHERE WEEK(G'.$Script.'_FechaInsercion) > (WEEK(CURDATE(),0)-1) AND YEAR(G'.$Script.'_FechaInsercion) = YEAR(CURDATE()) ORDER BY ANHO_CREACION DESC, MES_CREACION DESC, DIA_CREACION DESC, HORA_CREACION DESC, MINUTO_CREACION DESC, SEGUNDO_CREACION DESC;" , ';
            }
                        
        }//termino de recorrer los scripts de la tabla

        $nombresRe .= ' "RESUMEN BASE DATOS"';
        
        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";



        $camposconsulta2 = '"SELECT G'.$base.'_ConsInte__b AS ID, DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') as FECHA_CREACION, YEAR(G'.$base.'_FechaInsercion) AS ANHO_CREACION, MONTH(G'.$base.'_FechaInsercion) AS MES_CREACION, DAY(G'.$base.'_FechaInsercion) AS DIA_CREACION, HOUR(G'.$base.'_FechaInsercion) AS HORA_CREACION, MINUTE(G'.$base.'_FechaInsercion) AS MINUTO_CREACION, SECOND(G'.$base.'_FechaInsercion) AS SEGUNDO_CREACION ';

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_EstadoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , D.LISOPC_Nombre____b AS ESTADO_GMI, B.MONOEF_Texto_____b AS GESTION_MAS_IMPORTANTE ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_TipoReintentoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
                        $camposconsulta2 .= ' , (CASE WHEN G'.$base.'_TipoReintentoGMI_b = 0 THEN \'SIN GESTION\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 1 THEN \'REINENTO AUTOMATICO\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 2 THEN \'AGENDO\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 3 THEN \'NO REINTENTAR\' ELSE \'SIN GESTION\' END) AS REINTENTO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecHorAgeGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecHorAgeGMI_b AS FECHA_AGENDA_GMI, YEAR(G'.$base.'_FecHorAgeGMI_b) AS ANHO_AGENDA_GMI, MONTH(G'.$base.'_FecHorAgeGMI_b) AS MES_AGENDA_GMI, DAY(G'.$base.'_FecHorAgeGMI_b) AS DIA_AGENDA_GMI, HOUR(G'.$base.'_FecHorAgeGMI_b) AS HORA_AGENDA_GMI, MINUTE(G'.$base.'_FecHorAgeGMI_b) AS MINUTO_AGENDA_GMI, SECOND(G'.$base.'_FecHorAgeGMI_b) AS SEGUNDO_AGENDA_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ComentarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_ComentarioGMI_b AS COMENTARIO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , C.USUARI_Nombre____b AS AGENTE_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_CanalGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_CanalGMI_b AS CANAL_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_SentidoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_SentidoGMI_b AS SENTIDO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FeGeMaIm__b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FeGeMaIm__b AS FECHA_GMI, YEAR(G'.$base.'_FeGeMaIm__b) AS ANHO_GMI, MONTH(G'.$base.'_FeGeMaIm__b) AS MES_GMI, DAY(G'.$base.'_FeGeMaIm__b) AS DIA_GMI, HOUR(G'.$base.'_FeGeMaIm__b) AS HORA_GMI, MINUTE(G'.$base.'_FeGeMaIm__b) AS MINUTO_GMI, SECOND(G'.$base.'_FeGeMaIm__b) AS SEGUNDO_GMI, (DATE_FORMAT(G'.$base.'_FeGeMaIm__b,\'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FechaInsercion,\'%Y-%m-%d %H:%i:%S\')) AS DIAS_MADURACION_GMI ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ClasificacionUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , ug.MONOEF_Texto_____b AS CLASIFICACION_UG ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UltiGest__b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y E.MONOEF_Texto_____b
            $camposconsulta2 .= ' , A.MONOEF_Texto_____b AS ULTIMA_GESTION ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_TipoReintentoUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
                        $camposconsulta2 .= ' , (CASE WHEN G'.$base.'_TipoReintentoUG_b = 0 THEN \'SIN GESTION\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 1 THEN \'REINENTO AUTOMATICO\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 2 THEN \'AGENDO\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 3 THEN \'NO REINTENTAR\' ELSE \'SIN GESTION\' END) AS REINTENTO_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecHorAgeUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecHorAgeUG_b AS FECHA_AGENDA_UG, YEAR(G'.$base.'_FecHorAgeUG_b) AS ANHO_AGENDA_UG, MONTH(G'.$base.'_FecHorAgeUG_b) AS MES_AGENDA_UG, DAY(G'.$base.'_FecHorAgeUG_b) AS DIA_AGENDA_UG, HOUR(G'.$base.'_FecHorAgeUG_b) AS HORA_AGENDA_UG, MINUTE(G'.$base.'_FecHorAgeUG_b) AS MINUTO_AGENDA_UG, SECOND(G'.$base.'_FecHorAgeUG_b) AS SEGUNDO_AGENDA_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ComentarioUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_ComentarioUG_b AS COMENTARIO_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , C.USUARI_Nombre____b AS AGENTE_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_Canal_____b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_Canal_____b AS CANAL_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_Sentido___b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_Sentido___b AS SENTIDO_UG ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecUltGes_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecUltGes_b AS FECHA_UG, YEAR(G'.$base.'_FecUltGes_b) AS ANHO_UG, MONTH(G'.$base.'_FecUltGes_b) AS MES_UG, DAY(G'.$base.'_FecUltGes_b) AS DIA_UG, HOUR(G'.$base.'_FecUltGes_b) AS HORA_UG, MINUTE(G'.$base.'_FecUltGes_b) AS MINUTO_UG, SECOND(G'.$base.'_FecUltGes_b) AS SEGUNDO_UG, (DAY(DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FecUltGes_b , \'%Y-%m-%d %H:%i:%S\')) - DAY(DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FeGeMaIm__b , \'%Y-%m-%d %H:%i:%S\'))) AS DIAS_SIN_CONTACTO ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_CantidadIntentos'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_CantidadIntentos AS CANTIDAD_INTENTOS ';
        }

        $Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b IN (3,4) ORDER BY PREGUN_OrdePreg__b ASC";

        $result = $mysqli->query($Lsql);
        while($key = $result->fetch_object()){
            if ($key->titulo_pregunta == 'ORIGEN_DY_WF') {
                $camposconsulta2 .= " , G".$base."_C".$key->id." AS ORIGEN_DY_WF ";
            }elseif ($key->titulo_pregunta == 'OPTIN_DY_WF') {
                $camposconsulta2 .= " , G".$base."_C".$key->id." AS OPTIN_DY_WF ";
            }
        }



        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$base."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12){

                $titulodeLapregunta = $key->titulo_pregunta;
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                if($key->tipo_Pregunta == '6'){
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else  if($key->tipo_Pregunta == '5'){ 

                    $camposconsulta2 .= ' ,  DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                
                }else  if($key->tipo_Pregunta == '10'){

                    $camposconsulta2 .= ' , DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
        }//cierro este while $key = $campos_4->fetch_object()

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ClasificacionUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF ug ON G'.$base.'_ClasificacionUG_b = ug.MONOEF_ConsInte__b ';
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= ' FROM '.$BaseDatos.'.G'.$base;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = G'.$base.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = G'.$base.'_GesMasImp_b '; 



        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.USUARI AS C ON G'.$base.'_UsuarioGMI_b = C.USUARI_ConsInte__b ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_EstadoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC AS D ON G'.$base.'_EstadoGMI_b = D.LISOPC_ConsInte__b ';
        }

        $camposconsulta2 .= ' ORDER BY ANHO_CREACION DESC, MES_CREACION DESC, DIA_CREACION DESC, HORA_CREACION DESC, MINUTO_CREACION DESC, SEGUNDO_CREACION DESC LIMIT 2000;';


        $consultas  .= $camposconsulta2.'"';

        /* Ahora obtenemos el intevalo de llamadas por campaña de la estrategia */
        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_Tipo______b = 1 AND ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);

        while ($key = $resEspaslq->fetch_object()) {
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                $CampanLsql = "SELECT CAMPAN_Nombre____b, CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;   
                $resPanLsql = $mysqli->query($CampanLsql);
                $dataAnLsql = $resPanLsql->fetch_array();
                $NombreCamp = $dataAnLsql['CAMPAN_Nombre____b']; 
                $CAMPAN_IdCamCbx__b = $dataAnLsql['CAMPAN_IdCamCbx__b'];

                $titulodeLapregunta = $NombreCamp;
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);


                $nombresRe .= ' , "ACD '.$titulodeLapregunta.'"';

                // $consultas .= ' , "SELECT intervalo_traducido, dy_informacion_intervalos_h.* FROM dyalogo_telefonia.dy_informacion_intervalos_h WHERE id_campana='.$CAMPAN_IdCamCbx__b.' and DATE_FORMAT(fecha,\'%Y-%m-%d %H:%i:%S\') >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 WEEK),\'%Y-%m-%d %H:%i:%S\') ORDER BY fecha DESC;"';

                $consultas .= " , \"SELECT fecha AS Fecha, nombre_campana AS Campana, intervalo_traducido AS Hora, CONCAT(dy_informacion_intervalos_h.meta_tsf, '/',dy_informacion_intervalos_h.segundos_tsf) AS ANS, recibidas AS Entran, SEC_TO_TIME(FLOOR(espera_promedio)) AS ASA, SEC_TO_TIME(FLOOR(espera_minimo)) AS 'ASAMin', SEC_TO_TIME(FLOOR(espera_maximo)) AS 'ASAMax', SEC_TO_TIME(FLOOR(espera_total)) AS 'T.TCola', contestadas AS Cont, contestadas_ns AS 'Cont<=', contestadas_despues_s_tsf AS 'Cont>', REPLACE(ROUND(((contestadas*100)/recibidas), 2), '.00', '') AS 'Cont/Entran', REPLACE(ROUND(((contestadas_ns*100)/recibidas), 2), '.00', '') AS 'TSF%', IF(ISNULL(ROUND(((contestadas_ns*100)/contestadas), 2)), 0, REPLACE(ROUND(((contestadas_ns*100)/contestadas), 2), '.00', '')) AS 'TSF<=%', REPLACE(ROUND(((contestadas_despues_s_tsf*100)/recibidas), 2), '.00', '') AS 'TSF>Cont%', IF(ISNULL(SEC_TO_TIME(FLOOR(tiempo_conversacion_total/contestadas))), SEC_TO_TIME(0), SEC_TO_TIME(FLOOR(tiempo_conversacion_total/contestadas))) AS 'Pro.Conv', SEC_TO_TIME(FLOOR(tiempo_conversacion_total)) AS 'T.T.Conv', abandonadas_total AS 'Aban', abandonadas AS 'Aban<=', abandonadas_despues_s_tsf AS 'Aban>', REPLACE(ROUND(((abandonadas_total*100)/recibidas), 2), '.00', '') AS 'Aban%', REPLACE(ROUND(((abandonadas_despues_s_tsf*100)/(contestadas+abandonadas_despues_s_tsf)), 2), '.00', '') AS 'Aban>%', SEC_TO_TIME(FLOOR(espera_promedio_abandono)) AS 'Pro.Aban', SEC_TO_TIME(FLOOR(espera_total_abandono)) AS 'T.T.Aban', SEC_TO_TIME(FLOOR(espera_minima_abandono)) AS 'T.AbanMin', SEC_TO_TIME(FLOOR(espera_maxima_abandono)) AS 'T.AbanMax' FROM dyalogo_telefonia.dy_informacion_intervalos_h LEFT JOIN dyalogo_telefonia.dy_campanas ON dy_campanas.id = dy_informacion_intervalos_h.id_campana WHERE id_campana=".$CAMPAN_IdCamCbx__b." and DATE_FORMAT(fecha,'%Y-%m-%d %H:%i:%S') >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 WEEK),'%Y-%m-%d %H:%i:%S') ORDER BY fecha DESC;\"";
            }
        }

        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);
        $numerosCampanha = '';
        $i = 0;
        while ($key = $resEspaslq->fetch_object()) {
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                if($i == 0){
                    $numerosCampanha .= $key->ESTPAS_ConsInte__CAMPAN_b;
                }else{
                    $numerosCampanha .= ' , '.$key->ESTPAS_ConsInte__CAMPAN_b;
                }
                $i++;
            }
        }

        /* Vamos a obtener las secciones */
        $nombresRe .= ' , "SESIONES"';
        $consultas .= ' , "SELECT agente_nombre as Agente,  DATE_FORMAT(min(fecha_hora_inicio) , \'%Y-%m-%d %H:%i:%S\') as Inicio, DATE_FORMAT(max(fecha_hora_fin), \'%Y-%m-%d %H:%i:%S\') as Fin, format(sum(duracion) / 60 , 2) as \'Duracion en minutos\' FROM dyalogo_telefonia.dy_v_historico_sesiones WHERE WEEK(fecha_hora_inicio) > (WEEK(CURDATE(),0)-1) AND YEAR(fecha_hora_inicio) = YEAR(CURDATE()) and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.')) group by agente_id;"';

        
        /* Vamos a llenar las Pausas */
        $nombresRe .= ' , "PAUSAS"';
        $consultas .= ' , "SELECT agente_nombre as Agente, DATE_FORMAT(fecha_hora_inicio , \'%Y-%m-%d %H:%i:%S\') as Inicio,  DATE_FORMAT(fecha_hora_fin , \'%Y-%m-%d %H:%i:%S\') as Fin, format(duracion/60, 2) as \'Duracion en minutos\', tipo_descanso_nombre as TipoPausa FROM dyalogo_telefonia.dy_v_historico_descansos WHERE WEEK(fecha_hora_inicio) > (WEEK(CURDATE(),0)-1) AND YEAR(fecha_hora_inicio) = YEAR(CURDATE()) and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.'));"';
        $nombresRe .= ' , "GESTIONES DE MAIL Y SMS"';
        $consultas .= ' , "SELECT SMS_MAIL_ESTPAS_Nombre__b  as Nombre_Paso, SMS_MAIL_Fecha__b as Fecha, SMS_MAIL_Tipo_b as Tipo_Gestion, SMS_MAIL_Total_Exitos_b AS Exitos, SMS_MAIL_Total_Fallos_b AS Fallos, SMS_MAIL_Total_b as Total_registros FROM '.$BaseDatos_systema.'.REPORTES_SMS_MAIL WHERE SMS_MAIL_ESTRAT_ConsInte__b = '.$id_estrategia.' AND WEEK(SMS_MAIL_Fecha__b) > (WEEK(CURDATE(),0)-1) AND YEAR(SMS_MAIL_Fecha__b) = YEAR(CURDATE());"';

        /* Insertamos los reportes */ 
        $reportesXX = $mysqli->real_escape_string($consultas);
        $nombresXXX = $mysqli->real_escape_string($nombresRe);
        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_estrategia =".$id_estrategia." AND tipo_periodicidad = 2 AND nombres_hojas NOT LIKE '%PausasConHorarioMuyLargas%'";
        $res = $mysqli->query($Lsql_InsercionRepor);

        //capturar id de reportes semanales
          $idReportesSemanales=[];
          while($fila = $res->fetch_assoc()){
                $idReportesSemanales[]['id']=$fila["id"];
          } 

        $LsqlEstra = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$id_estrategia;
        $res_Estra = $mysqli->query($LsqlEstra);
        $datos = $res_Estra->fetch_array();

        $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);
        // $proyecto = substr($proyecto, 0, 8);
        $proyecto = sanear_strings($proyecto);

        $estrategia = str_replace(' ', '', $datos['ESTRAT_Nombre____b']);
        // $estrategia = substr($estrategia, 0, 8);
        $estrategia = sanear_strings($estrategia);

        $ruta_archivo = "/tmp/".$proyecto."_".$estrategia."_SEMANAL_";

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();



            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 2 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                //actualiza todos los reportes semanales
                foreach ($idReportesSemanales as $data){

                    $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombresXXX."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '".$ruta_archivo."' WHERE id = ".$data["id"];  


                    if($mysqli->query($Lsql) === true){

                        echo "semanal";

                    }            
                    
                    
                }
            }

        }else{
            if($destinatarios != null && $asunto != null && $hora != null){

                
                    $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 2 , '".$hora."' , '".$asunto."' , '".$copia."');";

            }else{
                if ($asunto != "::UPDATE::") {
                     $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$_SESSION['CORREO']."' , 2 , '07:00' , '".$_SESSION['PROYECTO']."_".sanear_strings(str_replace(' ','_',$datos['ESTRAT_Nombre____b']))."_SEMANAL_".$datos['ESTRAT_ConsInte__b']."' );";
                }
            }
        }
        
        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 2 ".$mysqli->error;
        }

    }

function ctrCrearReportesMensuales($id_estrategia, $destinatarios = null, $copia = null, $copiaOculta = null, $asunto = null, $hora = null){
        include(__DIR__."../../../pages/conexion.php");

        /* primero obtenemos la base de datos de la estrategia */
        $Lsql = "SELECT G2_C69 FROM ".$BaseDatos_systema.".G2 WHERE G2_ConsInte__b = ".$id_estrategia;
        $resL = $mysqli->query($Lsql);
        $datL = $resL->fetch_array();
        $base = $datL['G2_C69'];


        $consultas = '';
        $nombresRe = '';

        /*$estado_dy_Lsql = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'ESTADO_DY'  AND PREGUN_ConsInte__GUION__b = ".$base.";";
        $res_do_dy_Lsql = $mysqli->query($estado_dy_Lsql);
        $dato_o_dy_Lsql = $res_do_dy_Lsql->fetch_array();
        $estado_dy_camp = $dato_o_dy_Lsql['PREGUN_ConsInte__b'];

        $consultas .= '"SELECT IFNULL(LISOPC_Nombre____b, \'Por gestionar\') as Estado, count(*) as Cantidad FROM '.$BaseDatos.'.G'.$base.' LEFT JOIN '.$BaseDatos_systema.'.LISOPC ON G'.$base.'_C'.$estado_dy_camp.' = LISOPC_ConsInte__b GROUP BY G'.$base.'_C'.$estado_dy_camp.' ORDER BY count(*) DESC;" ';

        $nombresRe .= '"CONO ESTADOS"';*/
        
        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);
        while ($key = $resEspaslq->fetch_object()) {
            /* Recorremos las campañas para obtener su script */
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                $CampanLsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;   
                $resPanLsql = $mysqli->query($CampanLsql);
                $dataAnLsql = $resPanLsql->fetch_array();
                $Script     = $dataAnLsql['CAMPAN_ConsInte__GUION__Gui_b']; 

                $titulodeLapregunta = $dataAnLsql['CAMPAN_Nombre____b'];
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 15);

                $nombresRe .= '"GESTIONES '.$titulodeLapregunta.'_'.$key->ESTPAS_ConsInte__CAMPAN_b.'" ,';
                /* Ahora si recorremos el Script */ 
                $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
                $joins = '';
                $alfa = 0;
                $guion_c = 'G'.$Script."_C";
                $guion_Fecha_Insercion = 'G'.$Script."_";
                $camposconsulta1 = '"SELECT ';
                $iTo = 0;
                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_FechaInsercion'";

                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }

                    $camposconsulta1 .= $separador.' G'.$Script.'_FechaInsercion AS FECHA_CREACION, YEAR(G'.$Script.'_FechaInsercion) AS ANHO_CREACION, MONTH(G'.$Script.'_FechaInsercion) AS MES_CREACION, DAY(G'.$Script.'_FechaInsercion) AS DIA_CREACION, HOUR(G'.$Script.'_FechaInsercion) AS HORA_CREACION, MINUTE(G'.$Script.'_FechaInsercion) AS MINUTO_CREACION, SECOND(G'.$Script.'_FechaInsercion) AS SEGUNDO_CREACION ';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }
                
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Fecha'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.$guion_c.$key->id.' AS FECHA_GESTION, YEAR('.$guion_c.$key->id.') AS ANHO_GESTION, MONTH('.$guion_c.$key->id.') AS MES_GESTION, DAY('.$guion_c.$key->id.') AS DIA_GESTION ';
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Hora'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' HOUR('.$guion_c.$key->id.') AS HORA_GESTION, MINUTE('.$guion_c.$key->id.') AS MINUTO_GESTION, SECOND('.$guion_c.$key->id.') AS SEGUNDO_GESTION ';
                    }
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Duracion___b'";

                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }

                    $camposconsulta1 .= $separador." DATE_FORMAT(G".$Script ."_Duracion___b, '%H:%i:%S') AS DURACION_GESTION";
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (4) AND PREGUN_Texto_____b = 'Agente'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){ 
                            $separador = '';
                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            $camposconsulta1 .= $separador.$guion_c.$key->id.' AS AGENTE';
                    }    
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Sentido___b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }
                    $camposconsulta1 .= $separador.' G'.$Script.'_Sentido___b AS SENTIDO';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$Script  ." WHERE Field = 'G".$Script ."_Canal_____b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                   //No existe ese campo
                }else{
                    //El campo existe y lo podemos modificar
                    $separador = '';
                    if($iTo != 0){
                        $separador = ' , ';
                    }
                    $camposconsulta1 .= $separador.' G'.$Script .'_Canal_____b AS CANAL';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (3)  AND PREGUN_Texto_____b IN ('Tipificacion','Reintento','Fecha Agenda','Observacion')";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){ 
                            $separador = '';
                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            if ($key->titulo_pregunta == 'Tipificación') {
                                $camposconsulta1 .= $separador.' ug.LISOPC_Nombre____b AS ULTIMA_GESTION';
                            }elseif ($key->titulo_pregunta == 'Reintento') {
                                $camposconsulta1 .= $separador." (CASE WHEN ".$guion_c.$key->id." = 0 THEN 'SIN GESTION' WHEN ".$guion_c.$key->id." = 1 THEN 'REINTENTO AUTOMATICO' WHEN ".$guion_c.$key->id." = 2 THEN 'AGENDO' WHEN ".$guion_c.$key->id." = 3 THEN 'NO REINTENTAR' ELSE 'SIN GESTION' END) AS REINTENTO";
                            }elseif ($key->titulo_pregunta == 'Fecha Agenda') {
                                $camposconsulta1 .= $separador.$guion_c.$key->id." AS FECHA_AGENDA,YEAR(".$guion_c.$key->id.") AS ANHO_AGENDA, MONTH(".$guion_c.$key->id.") AS MES_AGENDA, DAY(".$guion_c.$key->id.") AS DIA_AGENDA, HOUR(".$guion_c.$key->id.") AS HORA_AGENDA, MINUTE(".$guion_c.$key->id.") AS MINUTO_AGENDA, SECOND(".$guion_c.$key->id.") AS SECOND_AGENDA ";
                            }elseif ($key->titulo_pregunta == 'Observacion') {
                                $camposconsulta1 .= $separador.$guion_c.$key->id." AS OBSERVACION ";
                            }
                    }    
                }                

                $separador = '';
                if($iTo != 0){
                    $separador = ' , ';
                }

                $camposconsulta1 .= $separador.' CONCAT(\'https://bpo.dyalogo.cloud:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=\',  SUBSTRING_INDEX(SUBSTRING_INDEX(G'.$Script.'_IdLlamada, \'_\', 2), \'_\', -1)) as DescargaGrabacion ';
                if($iTo == 0){
                    $iTo += 1;
                }

                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b = 1 ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";
                $campos_4 = $mysqli->query($Lsqlx);
                
                while($key = $campos_4->fetch_object()){

                    if($key->titulo_pregunta != 'ORIGEN_DY_WF' && $key->titulo_pregunta != 'OPTIN_DY_WF' && $key->titulo_pregunta != 'ESTADO_DY'){

                        if($key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12 ){
                            $separador = '';

                            if($iTo != 0){
                                $separador = ' , ';
                            }
                            

                            $titulodeLapregunta = $key->titulo_pregunta;
                            $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                            $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                            $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                            if($key->tipo_Pregunta == '6'){
                                
                                $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                                
                                $alfa++;

                            }else if($key->tipo_Pregunta =='11'){
                                
                                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                                $campoPrimario = '';
                                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                                $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                                

                                $camposconsulta1 .= $separador.' G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;

                            }else if($key->tipo_Pregunta == '5'){ 

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                            
                            }else if($key->tipo_Pregunta == '10'){

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                            }else{

                                $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                            }

                            $iTo++;
                        }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
                    }
                   
                }//cierro este while $key = $campos_4->fetch_object()
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b IN (3)  AND PREGUN_Texto_____b = 'Tipificacion'";

                $campos_4 = $mysqli->query($Lsqlx);

                if ($campos_4->num_rows > 0) {
                    while($key = $campos_4->fetch_object()){
                        $joins .= " LEFT JOIN ".$BaseDatos_systema.".LISOPC ug ON ".$guion_c.$key->id." = ug.LISOPC_ConsInte__b ";
                    }
                }//cierro este while $key = $campos_4->fetch_object()

                $camposconsulta1 .= ' FROM '.$BaseDatos.'.G'.$Script;
                $camposconsulta1 .= $joins;
                $consultas  .= $camposconsulta1.' WHERE DATE_FORMAT(G'.$Script.'_FechaInsercion,\'%Y-%m\') = DATE_FORMAT(curdate(),\'%Y-%m\') ORDER BY ANHO_CREACION DESC, MES_CREACION DESC, DIA_CREACION DESC, HORA_CREACION DESC, MINUTO_CREACION DESC, SEGUNDO_CREACION DESC;" , ';
            }

        }//termino de recorrer los scripts de la tabla

        $nombresRe .= ' "RESUMEN BASE DATOS"';
        
        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";



        $camposconsulta2 = '"SELECT G'.$base.'_ConsInte__b AS ID, DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') as FECHA_CREACION, YEAR(G'.$base.'_FechaInsercion) AS ANHO_CREACION, MONTH(G'.$base.'_FechaInsercion) AS MES_CREACION, DAY(G'.$base.'_FechaInsercion) AS DIA_CREACION, HOUR(G'.$base.'_FechaInsercion) AS HORA_CREACION, MINUTE(G'.$base.'_FechaInsercion) AS MINUTO_CREACION, SECOND(G'.$base.'_FechaInsercion) AS SEGUNDO_CREACION ';

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_EstadoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , D.LISOPC_Nombre____b AS ESTADO_GMI, B.MONOEF_Texto_____b AS GESTION_MAS_IMPORTANTE ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_TipoReintentoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
                        $camposconsulta2 .= ' , (CASE WHEN G'.$base.'_TipoReintentoGMI_b = 0 THEN \'SIN GESTION\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 1 THEN \'REINENTO AUTOMATICO\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 2 THEN \'AGENDO\'
                                          WHEN G'.$base.'_TipoReintentoGMI_b = 3 THEN \'NO REINTENTAR\' ELSE \'SIN GESTION\' END) AS REINTENTO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecHorAgeGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecHorAgeGMI_b AS FECHA_AGENDA_GMI, YEAR(G'.$base.'_FecHorAgeGMI_b) AS ANHO_AGENDA_GMI, MONTH(G'.$base.'_FecHorAgeGMI_b) AS MES_AGENDA_GMI, DAY(G'.$base.'_FecHorAgeGMI_b) AS DIA_AGENDA_GMI, HOUR(G'.$base.'_FecHorAgeGMI_b) AS HORA_AGENDA_GMI, MINUTE(G'.$base.'_FecHorAgeGMI_b) AS MINUTO_AGENDA_GMI, SECOND(G'.$base.'_FecHorAgeGMI_b) AS SEGUNDO_AGENDA_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ComentarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_ComentarioGMI_b AS COMENTARIO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , C.USUARI_Nombre____b AS AGENTE_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_CanalGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_CanalGMI_b AS CANAL_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_SentidoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_SentidoGMI_b AS SENTIDO_GMI ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FeGeMaIm__b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FeGeMaIm__b AS FECHA_GMI, YEAR(G'.$base.'_FeGeMaIm__b) AS ANHO_GMI, MONTH(G'.$base.'_FeGeMaIm__b) AS MES_GMI, DAY(G'.$base.'_FeGeMaIm__b) AS DIA_GMI, HOUR(G'.$base.'_FeGeMaIm__b) AS HORA_GMI, MINUTE(G'.$base.'_FeGeMaIm__b) AS MINUTO_GMI, SECOND(G'.$base.'_FeGeMaIm__b) AS SEGUNDO_GMI, (DATE_FORMAT(G'.$base.'_FeGeMaIm__b,\'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FechaInsercion,\'%Y-%m-%d %H:%i:%S\')) AS DIAS_MADURACION_GMI ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ClasificacionUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , ug.MONOEF_Texto_____b AS CLASIFICACION_UG ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UltiGest__b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y E.MONOEF_Texto_____b
            $camposconsulta2 .= ' , A.MONOEF_Texto_____b AS ULTIMA_GESTION ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_TipoReintentoUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
                        $camposconsulta2 .= ' , (CASE WHEN G'.$base.'_TipoReintentoUG_b = 0 THEN \'SIN GESTION\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 1 THEN \'REINENTO AUTOMATICO\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 2 THEN \'AGENDO\'
                                          WHEN G'.$base.'_TipoReintentoUG_b = 3 THEN \'NO REINTENTAR\' ELSE \'SIN GESTION\' END) AS REINTENTO_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecHorAgeUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecHorAgeUG_b AS FECHA_AGENDA_UG, YEAR(G'.$base.'_FecHorAgeUG_b) AS ANHO_AGENDA_UG, MONTH(G'.$base.'_FecHorAgeUG_b) AS MES_AGENDA_UG, DAY(G'.$base.'_FecHorAgeUG_b) AS DIA_AGENDA_UG, HOUR(G'.$base.'_FecHorAgeUG_b) AS HORA_AGENDA_UG, MINUTE(G'.$base.'_FecHorAgeUG_b) AS MINUTO_AGENDA_UG, SECOND(G'.$base.'_FecHorAgeUG_b) AS SEGUNDO_AGENDA_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ComentarioUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_ComentarioUG_b AS COMENTARIO_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , C.USUARI_Nombre____b AS AGENTE_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_Canal_____b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_Canal_____b AS CANAL_UG ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_Sentido___b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_Sentido___b AS SENTIDO_UG ';
        }


        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_FecUltGes_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_FecUltGes_b AS FECHA_UG, YEAR(G'.$base.'_FecUltGes_b) AS ANHO_UG, MONTH(G'.$base.'_FecUltGes_b) AS MES_UG, DAY(G'.$base.'_FecUltGes_b) AS DIA_UG, HOUR(G'.$base.'_FecUltGes_b) AS HORA_UG, MINUTE(G'.$base.'_FecUltGes_b) AS MINUTO_UG, SECOND(G'.$base.'_FecUltGes_b) AS SEGUNDO_UG, (DAY(DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FecUltGes_b , \'%Y-%m-%d %H:%i:%S\')) - DAY(DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') - DATE_FORMAT(G'.$base.'_FeGeMaIm__b , \'%Y-%m-%d %H:%i:%S\'))) AS DIAS_SIN_CONTACTO ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_CantidadIntentos'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' , G'.$base.'_CantidadIntentos AS CANTIDAD_INTENTOS ';
        }

        $Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b IN (3,4) ORDER BY PREGUN_OrdePreg__b ASC";

        $result = $mysqli->query($Lsql);
        while($key = $result->fetch_object()){
            if ($key->titulo_pregunta == 'ORIGEN_DY_WF') {
                $camposconsulta2 .= " , G".$base."_C".$key->id." AS ORIGEN_DY_WF ";
            }elseif ($key->titulo_pregunta == 'OPTIN_DY_WF') {
                $camposconsulta2 .= " , G".$base."_C".$key->id." AS OPTIN_DY_WF ";
            }
        }



        $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
        $joins = '';
        $alfa = 0;
        $guion_c = 'G'.$base."_C";
        $campos_4 = $mysqli->query($Lsqlx);
        while($key = $campos_4->fetch_object()){

            if($key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12){

                $titulodeLapregunta = $key->titulo_pregunta;
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                if($key->tipo_Pregunta == '6'){
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else  if($key->tipo_Pregunta == '5'){ 

                    $camposconsulta2 .= ' ,  DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                
                }else  if($key->tipo_Pregunta == '10'){

                    $camposconsulta2 .= ' , DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.depCadenas($titulodeLapregunta)."_".$key->id;

                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.depCadenas($titulodeLapregunta)."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
        }//cierro este while $key = $campos_4->fetch_object()

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_ClasificacionUG_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF ug ON G'.$base.'_ClasificacionUG_b = ug.MONOEF_ConsInte__b ';
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= ' FROM '.$BaseDatos.'.G'.$base;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = G'.$base.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = G'.$base.'_GesMasImp_b '; 



        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_UsuarioGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.USUARI AS C ON G'.$base.'_UsuarioGMI_b = C.USUARI_ConsInte__b ';
        }

        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$base." WHERE Field = 'G".$base."_EstadoGMI_b'";
        $result = $mysqli->query($Lsql);
        if($result->num_rows === 0){
           //No existe ese campo
        }else{
            //El campo existe y lo podemos modificar
            $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC AS D ON G'.$base.'_EstadoGMI_b = D.LISOPC_ConsInte__b ';
        }

        $camposconsulta2 .= ' ORDER BY ANHO_CREACION DESC, MES_CREACION DESC, DIA_CREACION DESC, HORA_CREACION DESC, MINUTO_CREACION DESC, SEGUNDO_CREACION DESC LIMIT 2000;';

        


        $consultas  .= $camposconsulta2.'"';

        /* Ahora obtenemos el intevalo de llamadas por campaña de la estrategia */
        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_Tipo______b = 1 AND ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;

        $resEspaslq = $mysqli->query($EstpasLslq);

        while ($key = $resEspaslq->fetch_object()) {
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                $CampanLsql = "SELECT CAMPAN_Nombre____b, CAMPAN_IdCamCbx__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;   
                $resPanLsql = $mysqli->query($CampanLsql);
                $dataAnLsql = $resPanLsql->fetch_array();
                $NombreCamp = $dataAnLsql['CAMPAN_Nombre____b']; 
                $CAMPAN_IdCamCbx__b = $dataAnLsql['CAMPAN_IdCamCbx__b'];

                $titulodeLapregunta = $NombreCamp;
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);


                $nombresRe .= ' , "ACD '.$titulodeLapregunta.'"';

                // $consultas .= ' , "SELECT intervalo_traducido, dy_informacion_intervalos_h.* FROM dyalogo_telefonia.dy_informacion_intervalos_h WHERE id_campana='.$CAMPAN_IdCamCbx__b.' and DATE_FORMAT(fecha,\'%Y-%m\') = DATE_FORMAT(curdate(),\'%Y-%m\') ORDER BY fecha DESC;"';

                $consultas .= " , \"SELECT fecha AS Fecha, nombre_campana AS Campana, intervalo_traducido AS Hora, CONCAT(dy_informacion_intervalos_h.meta_tsf, '/',dy_informacion_intervalos_h.segundos_tsf) AS ANS, recibidas AS Entran, SEC_TO_TIME(FLOOR(espera_promedio)) AS ASA, SEC_TO_TIME(FLOOR(espera_minimo)) AS 'ASAMin', SEC_TO_TIME(FLOOR(espera_maximo)) AS 'ASAMax', SEC_TO_TIME(FLOOR(espera_total)) AS 'T.TCola', contestadas AS Cont, contestadas_ns AS 'Cont<=', contestadas_despues_s_tsf AS 'Cont>', REPLACE(ROUND(((contestadas*100)/recibidas), 2), '.00', '') AS 'Cont/Entran', REPLACE(ROUND(((contestadas_ns*100)/recibidas), 2), '.00', '') AS 'TSF%', IF(ISNULL(ROUND(((contestadas_ns*100)/contestadas), 2)), 0, REPLACE(ROUND(((contestadas_ns*100)/contestadas), 2), '.00', '')) AS 'TSF<=%', REPLACE(ROUND(((contestadas_despues_s_tsf*100)/recibidas), 2), '.00', '') AS 'TSF>Cont%', IF(ISNULL(SEC_TO_TIME(FLOOR(tiempo_conversacion_total/contestadas))), SEC_TO_TIME(0), SEC_TO_TIME(FLOOR(tiempo_conversacion_total/contestadas))) AS 'Pro.Conv', SEC_TO_TIME(FLOOR(tiempo_conversacion_total)) AS 'T.T.Conv', abandonadas_total AS 'Aban', abandonadas AS 'Aban<=', abandonadas_despues_s_tsf AS 'Aban>', REPLACE(ROUND(((abandonadas_total*100)/recibidas), 2), '.00', '') AS 'Aban%', REPLACE(ROUND(((abandonadas_despues_s_tsf*100)/(contestadas+abandonadas_despues_s_tsf)), 2), '.00', '') AS 'Aban>%', SEC_TO_TIME(FLOOR(espera_promedio_abandono)) AS 'Pro.Aban', SEC_TO_TIME(FLOOR(espera_total_abandono)) AS 'T.T.Aban', SEC_TO_TIME(FLOOR(espera_minima_abandono)) AS 'T.AbanMin', SEC_TO_TIME(FLOOR(espera_maxima_abandono)) AS 'T.AbanMax' FROM dyalogo_telefonia.dy_informacion_intervalos_h LEFT JOIN dyalogo_telefonia.dy_campanas ON dy_campanas.id = dy_informacion_intervalos_h.id_campana WHERE id_campana=".$CAMPAN_IdCamCbx__b." and DATE_FORMAT(fecha,'%Y-%m') = DATE_FORMAT(curdate(),'%Y-%m') ORDER BY fecha DESC; \"";
            }
        }

        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
        $resEspaslq = $mysqli->query($EstpasLslq);
        $numerosCampanha = '';
        $i = 0;
        while ($key = $resEspaslq->fetch_object()) {
            if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
                if($i == 0){
                    $numerosCampanha .= $key->ESTPAS_ConsInte__CAMPAN_b;
                }else{
                    $numerosCampanha .= ' , '.$key->ESTPAS_ConsInte__CAMPAN_b;
                }
                $i++;
            }
        }

        /* Vamos a obtener las secciones */
        $nombresRe .= ' , "SESIONES"';
        $consultas .= ' , "SELECT agente_nombre as Agente, DATE_FORMAT(min(fecha_hora_inicio) , \'%Y-%m-%d %H:%i:%S\') as Inicio, DATE_FORMAT(max(fecha_hora_fin), \'%Y-%m-%d %H:%i:%S\') as Fin, format(sum(duracion) / 60 , 2) as \'Duracion en minutos\' FROM dyalogo_telefonia.dy_v_historico_sesiones WHERE DATE_FORMAT(fecha_hora_inicio,\'%Y-%m\') DATE_FORMAT(curdate(),\'%Y-%m\') and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.')) group by agente_id;"';

        /* Vamos a llenar las Pausas */
        $nombresRe .= ' , "PAUSAS"';
        $consultas .= ' , "SELECT agente_nombre as Agente, DATE_FORMAT(fecha_hora_inicio , \'%Y-%m-%d %H:%i:%S\') as Inicio,  DATE_FORMAT(fecha_hora_fin , \'%Y-%m-%d %H:%i:%S\') as Fin, format(duracion/60, 2) as \'Duracion en minutos\', tipo_descanso_nombre as TipoPausa FROM dyalogo_telefonia.dy_v_historico_descansos WHERE DATE_FORMAT(fecha_hora_inicio,\'%Y-%m\') = DATE_FORMAT(curdate(),\'%Y-%m\') and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.'));"';
        $nombresRe .= ' , "GESTIONES DE MAIL Y SMS"';
        $consultas .= ' , "SELECT SMS_MAIL_ESTPAS_Nombre__b  as Nombre_Paso, SMS_MAIL_Fecha__b as Fecha, SMS_MAIL_Tipo_b as Tipo_Gestion, SMS_MAIL_Total_Exitos_b AS Exitos, SMS_MAIL_Total_Fallos_b AS Fallos, SMS_MAIL_Total_b as Total_registros FROM '.$BaseDatos_systema.'.REPORTES_SMS_MAIL WHERE SMS_MAIL_ESTRAT_ConsInte__b = '.$id_estrategia.' AND DATE_FORMAT(SMS_MAIL_Fecha__b,\'%Y-%m\') = DATE_FORMAT(curdate(),\'%Y-%m\');"';

        /* Insertamos los reportes */ 
        $reportesXX = $mysqli->real_escape_string($consultas);
        $nombresXXX = $mysqli->real_escape_string($nombresRe);
        //echo $nombresXXX;
        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_estrategia =".$id_estrategia." AND tipo_periodicidad = 3 AND nombres_hojas NOT LIKE '%PausasConHorarioMuyLargas%'";
        $res = $mysqli->query($Lsql_InsercionRepor);

          $idReportesMensuales=[];
          while($fila = $res->fetch_assoc()){
                $idReportesMensuales[]['id']=$fila["id"];
          } 


        $LsqlEstra = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$id_estrategia;
        $res_Estra = $mysqli->query($LsqlEstra);
        $datos = $res_Estra->fetch_array();

        $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);
        // $proyecto = substr($proyecto, 0, 8);
        $proyecto = sanear_strings($proyecto);

        $estrategia = str_replace(' ', '', $datos['ESTRAT_Nombre____b']);
        // $estrategia = substr($estrategia, 0, 8);
        $estrategia = sanear_strings($estrategia);

        $ruta_archivo = "/tmp/".$proyecto."_".$estrategia."_MENSUAL_";

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();



            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 3 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                //actualiza todos los reportes mensuales
                foreach ($idReportesMensuales as $data){

                      

                      $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombresXXX."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '".$ruta_archivo."' WHERE id = ".$data["id"];

                      if($mysqli->query($Lsql) === true){

                        echo "mensual";

                      }                       
                        
                }
            }

        }else{
            if($destinatarios != null && $asunto != null && $hora != null){

                
                   $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 3 , '".$hora."' , '".$asunto."' , '".$copia."');"; 
                
            }else{
                if ($asunto != "::UPDATE::") {
                    $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$_SESSION['CORREO']."' , 3 , '07:00' , '".$_SESSION['PROYECTO']."_".sanear_strings(str_replace(' ','_',$datos['ESTRAT_Nombre____b']))."_MENSUAL_".$datos['ESTRAT_ConsInte__b']."' );";
                 }   
            }
        }
        
        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 3 ".$mysqli->error;
        }

    }

    function ctrCrearReportesDirarioAdherencia($id_estrategia,$id_huesped, $destinatarios = null, $copia = null, $copiaOculta = null, $asunto = null, $hora = null){

        include(__DIR__."../../../pages/conexion.php");

        $NOMBRE_HOJAS = "";
        $CONSULTAS = "";

        $EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = " . $id_estrategia;

        $resEspaslq = $mysqli->query($EstpasLslq);


        if ($resEspaslq->num_rows > 0) {
            $numerosCampanha = '';
            $i = 0;
            while ($key = $resEspaslq->fetch_object()) {
                if ($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)) {
                    if ($i == 0) {
                        $numerosCampanha .= $key->ESTPAS_ConsInte__CAMPAN_b;
                    } else {
                        $numerosCampanha .= ' , ' . $key->ESTPAS_ConsInte__CAMPAN_b;
                    }
                    $i++;
                }
            }

            $NOMBRE_HOJAS .= '"SESIONES" , ';

            $CONSULTAS .= '"SELECT agente_nombre as Agente, min(fecha_hora_inicio) as Inicio, max(fecha_hora_fin) as Fin, sum(duracion) as DuracionT FROM dyalogo_telefonia.dy_v_historico_sesiones WHERE agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join DYALOGOCRM_SISTEMA.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join DYALOGOCRM_SISTEMA.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in(' . $numerosCampanha . ')) AND DATE_FORMAT(fecha_hora_inicio,\'%Y-%m-%d\') > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),\'%Y-%m-%d\') group by agente_id;" , ';

            $NOMBRE_HOJAS .= '"PAUSAS" , ';

            $CONSULTAS .= '"SELECT agente_nombre as Agente, fecha_hora_inicio as Inicio, fecha_hora_fin as Fin, duracion as DuracionT, tipo_descanso_nombre as TipoPausa FROM dyalogo_telefonia.dy_v_historico_descansos WHERE agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join DYALOGOCRM_SISTEMA.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join DYALOGOCRM_SISTEMA.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in(' . $numerosCampanha . ')) AND DATE_FORMAT(fecha_hora_inicio,\'%Y-%m-%d\') > DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY),\'%Y-%m-%d\');" , ';
        }

        $NOMBRE_HOJAS .='"NO SE REGISTRARON" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes LEFT JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE (NOT (HoraInicialDefinida is null)) AND HoraInicialDefinida < date_format(current_time, \'%H:%i:%S\') and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' AND qrySesionesDelDia.agente_id IS NULL Order By USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"LLEGARON TARDE" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraInicio, HoraInicialDefinida), \'%H:%i:%S\') as Retraso, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio > HoraInicialDefinida AND USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order By USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"LLEGARON A TIEMPO" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraInicialDefinida, HoraInicio as HoraInicialReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraInicio <= HoraInicialDefinida and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"SE FUERON ANTES" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraFin), \'%H:%i:%S\') as TiempoFaltante, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin < HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order By USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"SE FUERON A TIEMPO" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  HoraFin >= HoraFinalDefinida and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order By USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"SESIONES CORTAS" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(timediff(HoraFinalDefinida, HoraInicialDefinida), timediff(HoraFin, HoraInicio)), \'%H:%i:%S\') as TiempoFaltante, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), \'%H:%i:%S\') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) > date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order By USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"SESIONES DURACION OK" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, date_format(timediff(HoraFinalDefinida, HoraInicialDefinida), \'%H:%i:%S\') as DuracionDefinida, date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') as DuracionReal, HoraInicialDefinida, HoraInicio as HoraInicialReal, HoraFinalDefinida, HoraFin as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes JOIN DYALOGOCRM_SISTEMA.qrySesionesDelDia ON IdAgente = qrySesionesDelDia.agente_id WHERE  timediff(HoraFinalDefinida, HoraInicialDefinida) <= date_format(timediff(HoraFin, HoraInicio), \'%H:%i:%S\') and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order By USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"PausasConHorarioMuyLargas" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as Exceso, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) < timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"PausasConHorarioDuracionOk" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(timediff(HoraFinalProgramada, HoraInicialProgramada), timediff(fecha_hora_fin,fecha_hora_inicio)) as TiempoAFavor, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and timediff(HoraFinalProgramada, HoraInicialProgramada) >= timediff(fecha_hora_fin,fecha_hora_inicio) and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"PausasConHorarioIncumplidas" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, \'%H:%i:%S\'), timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , \'%H:%i:%S\') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin , \'%H:%i:%S\'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada > date_format(fecha_hora_inicio, \'%H:%i:%S\') or date_format(fecha_hora_fin , \'%H:%i:%S\') > HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"PausasConHorarioCumplidas" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, IF(HoraInicialProgramada > date_format(fecha_hora_inicio, \'%H:%i:%S\'),timediff(HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\')),null) as SalioAntesPor, IF(date_format(fecha_hora_fin , \'%H:%i:%S\') > HoraFinalProgramada, timediff(date_format(fecha_hora_fin, \'%H:%i:%S\'), HoraFinalProgramada),null) as LlegoTardePor, HoraInicialProgramada, date_format(fecha_hora_inicio, \'%H:%i:%S\') as HoraInicialReal, HoraFinalProgramada, date_format(fecha_hora_fin , \'%H:%i:%S\') as HoraFinalReal, timediff(timediff(fecha_hora_fin,fecha_hora_inicio), timediff(HoraFinalProgramada, HoraInicialProgramada)) as TiempoDiferencia, timediff(HoraFinalProgramada, HoraInicialProgramada) DuracionProgramada, timediff(fecha_hora_fin,fecha_hora_inicio) as DuracionReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 1 and (HoraInicialProgramada <= date_format(fecha_hora_inicio, \'%H:%i:%S\') and date_format(fecha_hora_fin , \'%H:%i:%S\') <= HoraFinalProgramada) and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' Order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"PausasSinHorarioMuyLargas" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, timediff(sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))), DuracionMaxima) as Exceso, DuracionMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, count(USUARI_ConsInte__b) as CantidadReal FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) < sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))Order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"PausasSinHorarioMuchasVeces" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, count(USUARI_ConsInte__b) - CantidadMaxima as VecesDeMas, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUPAU_Tipo_b = 0 and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' group by USUARI_ConsInte__b having CantidadMaxima < count(USUARI_ConsInte__b) Order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"PausasSinHorarioOK" , ';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo, tipo_descanso_nombre as Pausa, sec_to_time(sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio)))) as DuracionReal, DuracionMaxima, count(USUARI_ConsInte__b) as CantidadReal, CantidadMaxima FROM DYALOGOCRM_SISTEMA.qryPausasProgramadasDelDia left join dyalogo_telefonia.dy_v_historico_descansos ON IdAgente = agente_id and USUPAU_PausasId_b = tipo_descanso_id WHERE fecha_hora_inicio >= current_date() and USUARI_ConsInte__PROYEC_b = '.$id_huesped.' and USUPAU_Tipo_b = 0 group by USUARI_ConsInte__b having time_to_sec(DuracionMaxima) >= sum(time_to_sec(timediff(fecha_hora_fin,fecha_hora_inicio))) and CantidadMaxima >= count(USUARI_ConsInte__b) Order by USUARI_Nombre____b;" , ';

        $NOMBRE_HOJAS .='"AGENTES SIN MALLA DEFINIDA"';

        $CONSULTAS .= '"select USUARI_ConsInte__b as Id, USUARI_Nombre____b as Nombre, USUARI_Correo___b as Correo FROM DYALOGOCRM_SISTEMA.qryUSUARI_usuarios_agentes WHERE USUARI_ConsInte__PROYEC_b = '.$id_huesped.' and HoraInicialDefinida IS NULL Order By USUARI_Nombre____b;"';

        $CONSULTASXX = $mysqli->real_escape_string($CONSULTAS);
        $NOMBRE_HOJASXXX = $mysqli->real_escape_string($NOMBRE_HOJAS);

        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_huesped =".$id_huesped." AND nombres_hojas LIKE '%PausasConHorarioMuyLargas%';";
        $res = $mysqli->query($Lsql_InsercionRepor);

        //capturar id de reportes semanales
          $idReportesAdherencia=[];
          while($fila = $res->fetch_assoc()){
                $idReportesAdherencia[]['id']=$fila["id"];
          } 

        $LsqlEstra = "SELECT id, nombre FROM dyalogo_general.huespedes WHERE id = ".$id_huesped.";";

        $res_Estra = $mysqli->query($LsqlEstra);

        $datos = $res_Estra->fetch_array();

        $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);
        // $proyecto = substr($proyecto, 0, 8);
        $proyecto = sanear_strings($proyecto);

        $huesped = str_replace(' ', '', $datos['nombre']);
        // $estrategia = substr($estrategia, 0, 8);
        $huesped = sanear_strings($huesped);

        $ruta_archivo = "/tmp/".$huesped."_ADHERENCIA_DIARIOS";

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();



            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$CONSULTASXX."', '".$NOMBRE_HOJASXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                //actualiza todos los reportes semanales
                foreach ($idReportesAdherencia as $data){

                    $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$CONSULTASXX."', nombres_hojas = '".$NOMBRE_HOJASXXX."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '".$ruta_archivo."' WHERE id = ".$data["id"];  


                    if($mysqli->query($Lsql) === true){

                        echo "adherencia diario";

                    }            
                    
                    
                }
            }

        }else{
            if($destinatarios != null && $asunto != null && $hora != null){
                    $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$CONSULTASXX."', '".$NOMBRE_HOJASXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";

            }else{
                if ($asunto != "::UPDATE::") {
                     $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$CONSULTASXX."', '".$NOMBRE_HOJASXXX."' , ".$_SESSION['HUESPED'].", '".$ruta_archivo."' , ".$id_estrategia." , '".$_SESSION['CORREO']."' , 1 , '23:59' , '".$_SESSION['PROYECTO']."_".sanear_strings(str_replace(' ','_',$datos['nombre']))."_ADHERENCIA_DIARIOS_".$datos['id']."' );";
                }
            }
        }
        
        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 2 ".$mysqli->error;
        }


    }