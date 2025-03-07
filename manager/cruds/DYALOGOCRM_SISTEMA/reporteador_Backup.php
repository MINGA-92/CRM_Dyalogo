<?php
	ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    
    function sanear_strings($string) { 

       // $string = utf8_decode($string);

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

    function ctrCrearReportesDiarios($id_estrategia, $destinatarios = null, $copia = null, $copiaOculta = null, $asunto = null, $hora = null){
    	include(__DIR__."../../../pages/conexion.php");


    	/* primero obtenemos la base de datos de la estrategia */
    	$Lsql = "SELECT G2_C69 FROM ".$BaseDatos_systema.".G2 WHERE G2_ConsInte__b = ".$id_estrategia;
    	$resL = $mysqli->query($Lsql);
    	$datL = $resL->fetch_array();
    	$base = $datL['G2_C69'];


    	$consultas = '';
    	$nombresRe = '';
    	/* Pirmero sacamos el campo estado_dy */
    	$estado_dy_Lsql = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'ESTADO_DY' AND PREGUN_ConsInte__GUION__b = ".$base.";";
    	$res_do_dy_Lsql = $mysqli->query($estado_dy_Lsql);
    	$dato_o_dy_Lsql = $res_do_dy_Lsql->fetch_array();
    	$estado_dy_camp = $dato_o_dy_Lsql['PREGUN_ConsInte__b'];

    	/* 1era consulta la de estados */
    	$consultas .= '"SELECT IFNULL(LISOPC_Nombre____b, \'Por gestionar\') as Estado, count(*) as Cantidad FROM '.$BaseDatos.'.G'.$base.' LEFT JOIN '.$BaseDatos_systema.'.LISOPC ON G'.$base.'_C'.$estado_dy_camp.' = LISOPC_ConsInte__b GROUP BY G'.$base.'_C'.$estado_dy_camp.' ORDER BY count(*) DESC;"';

    	$nombresRe .= '"CONO ESTADOS"';
    	
    	/* ahora obtenemos las gestiones de todas las campañas */
    	$EstpasLslq = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$id_estrategia;
    	$resEspaslq = $mysqli->query($EstpasLslq);
    	while ($key = $resEspaslq->fetch_object()) {
    		/* Recorremos las campañas para obtener su script */
    		if($key->ESTPAS_ConsInte__CAMPAN_b != null && !empty($key->ESTPAS_ConsInte__CAMPAN_b)){
    			$CampanLsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$key->ESTPAS_ConsInte__CAMPAN_b;	
    			$resPanLsql = $mysqli->query($CampanLsql);
    			$dataAnLsql = $resPanLsql->fetch_array();
    			$Script 	= $dataAnLsql['CAMPAN_ConsInte__GUION__Gui_b']; 
                $titulodeLapregunta = $dataAnLsql['CAMPAN_Nombre____b'];
                $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

    			$nombresRe .= ' , "GESTIONES '.$titulodeLapregunta.'"';
    			/* Ahora si recorremos el Script */ 
    			$alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
       			$joins = '';
        		$alfa = 0;
        		$guion_c = 'G'.$Script."_C";
        		$camposconsulta1 = ', "SELECT ';
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b = 4 ORDER BY PREGUN_OrdePreg__b ASC";

                $iTo = 0;
                $campos_4 = $mysqli->query($Lsqlx);
                while($key = $campos_4->fetch_object()){
                    if($key->titulo_pregunta != 'Campaña'){

                        $separador = '';

                        if($iTo != 0){
                            $separador = ' , ';
                        }

                        if($key->titulo_pregunta == 'Fecha'){

                            $camposconsulta1 .= $separador.' YEAR('.$guion_c.$key->id.') AS ANHO , MONTH('.$guion_c.$key->id.') AS MES, DAY('.$guion_c.$key->id.') AS DIA';

                        }else if($key->titulo_pregunta == 'Hora'){

                            
                            $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($key->titulo_pregunta)."_".$key->id;

                        }else{

                            $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.strtolower($key->titulo_pregunta)."_".$key->id;

                        }
                        $iTo++;
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
                    $camposconsulta1 .= $separador.' DATE_FORMAT(G'.$Script .'_Duracion___b, \'%H:%i:%S\') AS DURACION_GESTION';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }


                /*$Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id, PREGUN_Tipo______b as tipo_Pregunta FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b = 3 ORDER BY PREGUN_OrdePreg__b ASC";*/

                $Lsqlx = "SELECT GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$Script;

                $campos_4 = $mysqli->query($Lsqlx);
                while($key = $campos_4->fetch_object()){
                    

                    //TIPIFICACION
                    if($key->GUION__ConsInte__PREGUN_Tip_b != '' && $key->GUION__ConsInte__PREGUN_Tip_b != null && $key->GUION__ConsInte__PREGUN_Tip_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS Tipificacion';
                        $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->GUION__ConsInte__PREGUN_Tip_b;
                        $alfa++;
                        $iTo++;
                    }
                    

                    //REINTENTO
                    if($key->GUION__ConsInte__PREGUN_Rep_b != '' && $key->GUION__ConsInte__PREGUN_Rep_b != null && $key->GUION__ConsInte__PREGUN_Rep_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' CASE '.$guion_c.$key->GUION__ConsInte__PREGUN_Rep_b.' WHEN 1 THEN \'REINTENTO AUTOMATICO\' WHEN 2 THEN \'AGENDADO\' WHEN 3 THEN \'NO REINTENTAR\' END AS Reintento';
                        $iTo++;
                    }

                    //FECHA AGENDA
                    if($key->GUION__ConsInte__PREGUN_Fag_b != '' && $key->GUION__ConsInte__PREGUN_Fag_b != null && $key->GUION__ConsInte__PREGUN_Fag_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->GUION__ConsInte__PREGUN_Fag_b.', \'%Y-%m-%d\') AS Fecha_agenda';
                        $iTo++;
                    }
                                      
                    //HORA AGENDA
                    if($key->GUION__ConsInte__PREGUN_Hag_b != '' && $key->GUION__ConsInte__PREGUN_Hag_b != null && $key->GUION__ConsInte__PREGUN_Hag_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->GUION__ConsInte__PREGUN_Hag_b.', \'%H:%i:%S\') AS Hora_agenda';
                        $iTo++;
                    }

                    //OBSERVACION
                    if($key->GUION__ConsInte__PREGUN_Com_b != '' && $key->GUION__ConsInte__PREGUN_Com_b != null && $key->GUION__ConsInte__PREGUN_Com_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' '.$guion_c.$key->GUION__ConsInte__PREGUN_Com_b.' AS comentario';
                        $iTo++;
                    }
                }

                $separador = '';
                if($iTo != 0){
                    $separador = ' , ';
                }

                $camposconsulta1 .=  $separador.' CONCAT(\'https://bpo.dyalogo.cloud:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=\',  SUBSTRING_INDEX(SUBSTRING_INDEX(G'.$Script.'_IdLlamada, \'_\', 2), \'_\', -1)) as DescargaGrabacion ';

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
                                
                                $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtolower($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                                
                                $alfa++;

                            }else if($key->tipo_Pregunta =='11'){
                                
                                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                                $campoPrimario = '';
                                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                                $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                                

                                $camposconsulta1 .= $separador.' G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;

                            }else if($key->tipo_Pregunta == '5'){ 

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d %H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;
                            
                            }else if($key->tipo_Pregunta == '10'){

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;

                            }else{

                                $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                            }

                            $iTo++;
                        }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
                    }
		           
		        }//cierro este while $key = $campos_4->fetch_object()

       		 	$camposconsulta1 .= ' FROM '.$BaseDatos.'.G'.$Script;
        		$camposconsulta1 .= $joins;
        		//$consultas  .= $camposconsulta1.' WHERE G'.$Script.'_FechaInsercion >= CURRENT_DATE();"';
                $consultas  .= $camposconsulta1.' WHERE G'.$Script.'_FechaInsercion BETWEEN \'2018-10-01\' AND \'2018-10-31\'"';
    		}
    		
    	}//termino de recorrer los scripts de la tabla

        $nombresRe .= ' , "RESUMEN BASE DATOS"';
        
        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";


        $camposconsulta2 = ' , "SELECT G'.$base.'_ConsInte__b, DATE_FORMAT(G'.$base.'_FechaInsercion, \'%Y-%m-%d %H:%i:%S\') as FECHA_CREACION ';

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
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtolower($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else  if($key->tipo_Pregunta == '5'){ 

                    $camposconsulta1 .= ' ,  DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d %H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;
                
                }else  if($key->tipo_Pregunta == '10'){

                    $camposconsulta1 .= ' , DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;

                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.strtolower($titulodeLapregunta)."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= ' ,  A.MONOEF_Texto_____b AS \'ULTIMA GESTION\' , DATE_FORMAT(G'.$base.'_FecUltGes_b, \'%Y-%m-%d %H:%i:%S\') as \'FECHA ULTIMA GESTION\', B.MONOEF_Texto_____b AS \'GESTION MAS IMPORTANTE\',  DATE_FORMAT(G'.$base.'_FeGeMaIm__b, \'%Y-%m-%d %H:%i:%S\') as \'FECHA GESTION MAS IMPORTANTE\' FROM '.$BaseDatos.'.G'.$base;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = G'.$base.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = G'.$base.'_GesMasImp_b ORDER BY G'.$base.'_ConsInte__b DESC LIMIT 15000;';


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


                $nombresRe .= ' , "ANALISIS LLAMADAS ENTRANTES '.$titulodeLapregunta.'"';

                $consultas .= ' , "SELECT intervalo_traducido, dy_informacion_intervalos_h.* FROM dyalogo_telefonia.dy_informacion_intervalos_h WHERE id_campana='.$CAMPAN_IdCamCbx__b.' and fecha BETWEEN \'2018-10-01\' AND \'2018-10-31\'"';
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
        $nombresRe .= ' , "SESSIONES"';
        $consultas .= ', "SELECT agente_nombre as Agente, min(fecha_hora_inicio) as Inicio, max(fecha_hora_fin) as Fin, sum(duracion) as DuracionT FROM dyalogo_telefonia.dy_v_historico_sesiones WHERE fecha_hora_inicio BETWEEN \'2018-10-01\' AND \'2018-10-31\' and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.')) group by agente_id;"';

        /* Vamos a llenar las Pausas */
        $nombresRe .= ' , "PAUSAS"';
        $consultas .= ', "SELECT agente_nombre as Agente, fecha_hora_inicio as Inicio, fecha_hora_fin as Fin, duracion as DuracionT, tipo_descanso_nombre as TipoPausa FROM dyalogo_telefonia.dy_v_historico_descansos WHERE fecha_hora_inicio BETWEEN \'2018-10-01\' AND \'2018-10-31\' and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.'));"';
        
        $nombresRe .= ' , "GESTIONES DE MAIL Y SMS"';
        $consultas .= ' , "SELECT SMS_MAIL_ESTPAS_Nombre__b  as Nombre_Paso, SMS_MAIL_Fecha__b as Fecha, SMS_MAIL_Tipo_b as Tipo_Gestion, SMS_MAIL_Total_Exitos_b AS Exitos, SMS_MAIL_Total_Fallos_b AS Fallos, SMS_MAIL_Total_b as Total_registros FROM '.$BaseDatos_systema.'.REPORTES_SMS_MAIL WHERE SMS_MAIL_ESTRAT_ConsInte__b = '.$id_estrategia.' AND SMS_MAIL_Fecha__b BETWEEN \'2018-10-01\' AND \'2018-10-31\';"';


        /* Insertamos los reportes */ 
        $reportesXX = $mysqli->real_escape_string($consultas);
        $nombresXXX = $mysqli->real_escape_string($nombresRe);
        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_estrategia =".$id_estrategia." AND tipo_periodicidad = 1 LIMIT 1";
        $res = $mysqli->query($Lsql_InsercionRepor);

        $LsqlEstra = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$id_estrategia;
        $res_Estra = $mysqli->query($LsqlEstra);
        $datos = $res_Estra->fetch_array();

        $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);
        $proyecto = substr($proyecto, 0, 8);
        $proyecto = sanear_strings($proyecto);

        $estrategia = str_replace(' ', '', $datos['ESTRAT_Nombre____b']);
        $estrategia = substr($estrategia, 0, 8);
        $estrategia = sanear_strings($estrategia);

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();

            if($destinatarios != null && $asunto != null && $hora != null){
                //echo "Entro por esta lado";
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_DIARIO_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{
                //echo "Entro por esta lado 4"; 
                $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombresXXX."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '/tmp/".$proyecto."_".$estrategia."_DIARIO_".$datos['ESTRAT_ConsInte__b']."' wHERE id_estrategia = ".$id_estrategia." AND tipo_periodicidad = 1";
            }

        }else{
            // echo "Entro por esta lado 3"; 
            if($destinatarios != null && $asunto != null && $hora != null){
             //   echo "Entro por esta lado"; 
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_DIARIO_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$destinatarios."' , 1 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{
            //     echo "Entro por esta lado 2"; 
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_DIARIO_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$_SESSION['CORREO']."' , 1 , '20:20' , '".$_SESSION['PROYECTO']."_".sanear_strings(str_replace(' ','_',$datos['ESTRAT_Nombre____b']))."_DIARIO_".$datos['ESTRAT_ConsInte__b']."' );";
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
        /* Pirmero sacamos el campo estado_dy */
        $estado_dy_Lsql = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'ESTADO_DY'";
        $res_do_dy_Lsql = $mysqli->query($estado_dy_Lsql);
        $dato_o_dy_Lsql = $res_do_dy_Lsql->fetch_array();
        $estado_dy_camp = $dato_o_dy_Lsql['PREGUN_ConsInte__b'];

        /* 1era consulta la de estados */
        $consultas .= '"SELECT IFNULL(LISOPC_Nombre____b, \'Por gestionar\') as Estado, count(*) as Cantidad FROM '.$BaseDatos.'.G'.$base.' LEFT JOIN '.$BaseDatos_systema.'.LISOPC ON G'.$base.'_C'.$estado_dy_camp.' = LISOPC_ConsInte__b GROUP BY G'.$base.'_C'.$estado_dy_camp.' ORDER BY count(*) DESC;"';

        $nombresRe .= '"CONO ESTADOS"';
        
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
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                $nombresRe .= ' , "GESTIONES '.$titulodeLapregunta.'"';
                /* Ahora si recorremos el Script */ 
                $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
                $joins = '';
                $alfa = 0;
                $guion_c = 'G'.$Script."_C";
                $camposconsulta1 = ', "SELECT ';
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b = 4 ORDER BY PREGUN_OrdePreg__b ASC";

                $iTo = 0;
                $campos_4 = $mysqli->query($Lsqlx);
                while($key = $campos_4->fetch_object()){
                    if($key->titulo_pregunta != 'Campaña'){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }

                        if($key->titulo_pregunta == 'Fecha'){

                            $camposconsulta1 .= $separador.' YEAR('.$guion_c.$key->id.') AS ANHO , MONTH('.$guion_c.$key->id.') AS MES, DAY('.$guion_c.$key->id.') AS DIA';


                        }else if($key->titulo_pregunta == 'Hora'){

                            
                            $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($key->titulo_pregunta)."_".$key->id;

                        }else{

                            $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.strtolower($key->titulo_pregunta)."_".$key->id;

                        }
                        $iTo++;
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

                    $camposconsulta1 .= $separador.' DATE_FORMAT(G'.$Script .'_Duracion___b, \'%H:%i:%S\') AS DURACION_GESTION';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }



                $Lsqlx = "SELECT GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$Script;

                $campos_4 = $mysqli->query($Lsqlx);
                while($key = $campos_4->fetch_object()){
                    

                    //TIPIFICACION
                    if($key->GUION__ConsInte__PREGUN_Tip_b != '' && $key->GUION__ConsInte__PREGUN_Tip_b != null && $key->GUION__ConsInte__PREGUN_Tip_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS Tipificacion';
                        $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->GUION__ConsInte__PREGUN_Tip_b;
                        $alfa++;
                        $iTo++;
                    }
                    

                    //REINTENTO
                    if($key->GUION__ConsInte__PREGUN_Rep_b != '' && $key->GUION__ConsInte__PREGUN_Rep_b != null && $key->GUION__ConsInte__PREGUN_Rep_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' CASE '.$guion_c.$key->GUION__ConsInte__PREGUN_Rep_b.' WHEN 1 THEN \'REINTENTO AUTOMATICO\' WHEN 2 THEN \'AGENDADO\' WHEN 3 THEN \'NO REINTENTAR\' END AS Reintento';
                        $iTo++;
                    }

                    //FECHA AGENDA
                    if($key->GUION__ConsInte__PREGUN_Fag_b != '' && $key->GUION__ConsInte__PREGUN_Fag_b != null && $key->GUION__ConsInte__PREGUN_Fag_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->GUION__ConsInte__PREGUN_Fag_b.', \'%Y-%m-%d\') AS Fecha_agenda';
                        $iTo++;
                    }
                                      
                    //HORA AGENDA
                    if($key->GUION__ConsInte__PREGUN_Hag_b != '' && $key->GUION__ConsInte__PREGUN_Hag_b != null && $key->GUION__ConsInte__PREGUN_Hag_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->GUION__ConsInte__PREGUN_Hag_b.', \'%H:%i:%S\') AS Hora_agenda';
                        $iTo++;
                    }

                    //OBSERVACION
                    if($key->GUION__ConsInte__PREGUN_Com_b != '' && $key->GUION__ConsInte__PREGUN_Com_b != null && $key->GUION__ConsInte__PREGUN_Com_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' '.$guion_c.$key->GUION__ConsInte__PREGUN_Com_b.' AS comentario';
                        $iTo++;
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
                                
                                $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtolower($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                                
                                $alfa++;

                            }else if($key->tipo_Pregunta =='11'){
                                
                                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                                $campoPrimario = '';
                                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                                $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                                

                                $camposconsulta1 .= $separador.' G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;

                            }else if($key->tipo_Pregunta == '5'){ 

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d %H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;
                            
                            }else if($key->tipo_Pregunta == '10'){

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;

                            }else{

                                $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                            }

                            $iTo++;
                        }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
                    }
                   
                }//cierro este while $key = $campos_4->fetch_object()

                $camposconsulta1 .= ' FROM '.$BaseDatos.'.G'.$Script;
                $camposconsulta1 .= $joins;
                $consultas  .= $camposconsulta1.' WHERE G'.$Script.'_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY);"';
            }
            
        }//termino de recorrer los scripts de la tabla

        $nombresRe .= ' , "RESUMEN BASE DATOS"';
        
        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";


        $camposconsulta2 = ' , "SELECT G'.$base.'_ConsInte__b, G'.$base.'_FechaInsercion as FECHA_CREACION ';

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
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtolower($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else  if($key->tipo_Pregunta == '5'){ 

                    $camposconsulta1 .= ' ,  DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d %H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;
                
                }else  if($key->tipo_Pregunta == '10'){

                    $camposconsulta1 .= ' , DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;

                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.strtolower($titulodeLapregunta)."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= ' ,  A.MONOEF_Texto_____b AS \'ULTIMA GESTION\' ,  DATE_FORMAT(G'.$base.'_FecUltGes_b, \'%Y-%m-%d %H:%i:%S\') as \'FECHA ULTIMA GESTION\', B.MONOEF_Texto_____b AS \'GESTION MAS IMPORTANTE\',  DATE_FORMAT(G'.$base.'_FeGeMaIm__b, \'%Y-%m-%d %H:%i:%S\')as \'FECHA GESTION MAS IMPORTANTE\' FROM '.$BaseDatos.'.G'.$base;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = G'.$base.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = G'.$base.'_GesMasImp_b ORDER BY G'.$base.'_ConsInte__b DESC LIMIT 15000;';


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


                $nombresRe .= ' , "ANALISIS LLAMADAS ENTRANTES '.$titulodeLapregunta.'"';

                $consultas .= ' , "SELECT intervalo_traducido, dy_informacion_intervalos_h.* FROM dyalogo_telefonia.dy_informacion_intervalos_h WHERE id_campana='.$CAMPAN_IdCamCbx__b.' and fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY);"';
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
        $nombresRe .= ' , "SESSIONES"';
        $consultas .= ', "SELECT agente_nombre as Agente, min(fecha_hora_inicio) as Inicio, max(fecha_hora_fin) as Fin, sum(duracion) as DuracionT FROM dyalogo_telefonia.dy_v_historico_sesiones WHERE fecha_hora_inicio  BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.')) group by agente_id;"';

        /* Vamos a llenar las Pausas */
        $nombresRe .= ' , "PAUSAS"';
        $consultas .= ', "SELECT agente_nombre as Agente, fecha_hora_inicio as Inicio, fecha_hora_fin as Fin, duracion as DuracionT, tipo_descanso_nombre as TipoPausa FROM dyalogo_telefonia.dy_v_historico_descansos WHERE fecha_hora_inicio BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.'));"';
        
        $nombresRe .= ' , "GESTIONES DE MAIL Y SMS"';
        $consultas .= ' , "SELECT SMS_MAIL_ESTPAS_Nombre__b  as Nombre_Paso, SMS_MAIL_Fecha__b as Fecha, SMS_MAIL_Tipo_b as Tipo_Gestion, SMS_MAIL_Total_Exitos_b AS Exitos, SMS_MAIL_Total_Fallos_b AS Fallos, SMS_MAIL_Total_b as Total_registros FROM '.$BaseDatos_systema.'.REPORTES_SMS_MAIL WHERE SMS_MAIL_ESTRAT_ConsInte__b = '.$id_estrategia.' AND SMS_MAIL_Fecha__b  BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY);"';


        /* Insertamos los reportes */ 
        $reportesXX = $mysqli->real_escape_string($consultas);
        $nombresXXX = $mysqli->real_escape_string($nombresRe);
        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_estrategia =".$id_estrategia." AND tipo_periodicidad = 2 LIMIT 1";
        $res = $mysqli->query($Lsql_InsercionRepor);

        $LsqlEstra = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$id_estrategia;
        $res_Estra = $mysqli->query($LsqlEstra);
        $datos = $res_Estra->fetch_array();

        $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);
        $proyecto = substr($proyecto, 0, 8);
        $proyecto = sanear_strings($proyecto);

        $estrategia = str_replace(' ', '', $datos['ESTRAT_Nombre____b']);
        $estrategia = substr($estrategia, 0, 8);
        $estrategia = sanear_strings($estrategia);

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();



            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_SEMANAL_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$destinatarios."' , 2 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombresXXX."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '/tmp/".$proyecto."_".$estrategia."_SEMANAL_".$datos['ESTRAT_ConsInte__b']."' wHERE id_estrategia = ".$id_estrategia." AND tipo_periodicidad = 2";
            }

        }else{
            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_SEMANAL_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$destinatarios."' , 2 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_SEMANAL_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$_SESSION['CORREO']."' , 2 , '20:20' , '".$_SESSION['PROYECTO']."_".sanear_strings(str_replace(' ','_',$datos['ESTRAT_Nombre____b']))."_SEMANAL_".$datos['ESTRAT_ConsInte__b']."' );";
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
        /* Pirmero sacamos el campo estado_dy */
        $estado_dy_Lsql = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_Texto_____b = 'ESTADO_DY'";
        $res_do_dy_Lsql = $mysqli->query($estado_dy_Lsql);
        $dato_o_dy_Lsql = $res_do_dy_Lsql->fetch_array();
        $estado_dy_camp = $dato_o_dy_Lsql['PREGUN_ConsInte__b'];

        /* 1era consulta la de estados */
        $consultas .= '"SELECT IFNULL(LISOPC_Nombre____b, \'Por gestionar\') as Estado, count(*) as Cantidad FROM '.$BaseDatos.'.G'.$base.' LEFT JOIN '.$BaseDatos_systema.'.LISOPC ON G'.$base.'_C'.$estado_dy_camp.' = LISOPC_ConsInte__b GROUP BY G'.$base.'_C'.$estado_dy_camp.' ORDER BY count(*) DESC;"';

        $nombresRe .= '"CONO ESTADOS"';
        
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
                $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                $nombresRe .= ' , "GESTIONES '.$titulodeLapregunta.'"';
                /* Ahora si recorremos el Script */ 
                $alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
                $joins = '';
                $alfa = 0;
                $guion_c = 'G'.$Script."_C";
                $camposconsulta1 = ', "SELECT ';
                $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_ConsInte__b as id FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$Script." AND SECCIO_TipoSecc__b = 4 ORDER BY PREGUN_OrdePreg__b ASC";

                $iTo = 0;
                $campos_4 = $mysqli->query($Lsqlx);
                while($key = $campos_4->fetch_object()){
                    if($key->titulo_pregunta != 'Campaña'){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }

                        if($key->titulo_pregunta == 'Fecha'){

                            $camposconsulta1 .= $separador.' YEAR('.$guion_c.$key->id.') AS ANHO , MONTH('.$guion_c.$key->id.') AS MES, DAY('.$guion_c.$key->id.') AS DIA';


                        }else if($key->titulo_pregunta == 'Hora'){

                            
                            $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($key->titulo_pregunta)."_".$key->id;

                        }else{

                            $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.strtolower($key->titulo_pregunta)."_".$key->id;

                        }
                        $iTo++;
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
                    $camposconsulta1 .= $separador.' DATE_FORMAT(G'.$Script .'_Duracion___b, \'%H:%i:%S\') AS DURACION_GESTION';
                    if($iTo == 0){
                        $iTo += 1;
                    }
                }

                $Lsqlx = "SELECT GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$Script;

                $campos_4 = $mysqli->query($Lsqlx);
                while($key = $campos_4->fetch_object()){
                    

                    //TIPIFICACION
                    if($key->GUION__ConsInte__PREGUN_Tip_b != '' && $key->GUION__ConsInte__PREGUN_Tip_b != null && $key->GUION__ConsInte__PREGUN_Tip_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS Tipificacion';
                        $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->GUION__ConsInte__PREGUN_Tip_b;
                        $alfa++;
                        $iTo++;
                    }
                    

                    //REINTENTO
                    if($key->GUION__ConsInte__PREGUN_Rep_b != '' && $key->GUION__ConsInte__PREGUN_Rep_b != null && $key->GUION__ConsInte__PREGUN_Rep_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' CASE '.$guion_c.$key->GUION__ConsInte__PREGUN_Rep_b.' WHEN 1 THEN \'REINTENTO AUTOMATICO\' WHEN 2 THEN \'AGENDADO\' WHEN 3 THEN \'NO REINTENTAR\' END AS Reintento';
                        $iTo++;
                    }

                    //FECHA AGENDA
                    if($key->GUION__ConsInte__PREGUN_Fag_b != '' && $key->GUION__ConsInte__PREGUN_Fag_b != null && $key->GUION__ConsInte__PREGUN_Fag_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->GUION__ConsInte__PREGUN_Fag_b.', \'%Y-%m-%d\') AS Fecha_agenda';
                        $iTo++;
                    }
                                      
                    //HORA AGENDA
                    if($key->GUION__ConsInte__PREGUN_Hag_b != '' && $key->GUION__ConsInte__PREGUN_Hag_b != null && $key->GUION__ConsInte__PREGUN_Hag_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->GUION__ConsInte__PREGUN_Hag_b.', \'%H:%i:%S\') AS Hora_agenda';
                        $iTo++;
                    }

                    //OBSERVACION
                    if($key->GUION__ConsInte__PREGUN_Com_b != '' && $key->GUION__ConsInte__PREGUN_Com_b != null && $key->GUION__ConsInte__PREGUN_Com_b != 0){
                        $separador = '';
                        if($iTo != 0){
                            $separador = ' , ';
                        }
                        $camposconsulta1 .= $separador.' '.$guion_c.$key->GUION__ConsInte__PREGUN_Com_b.' AS comentario';
                        $iTo++;
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
                                
                                $camposconsulta1 .= $separador.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtolower($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                                
                                $alfa++;

                            }else if($key->tipo_Pregunta =='11'){
                                
                                $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                                $campoPrimario = '';
                                $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                                $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                                

                                $camposconsulta1 .= $separador.' G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                                $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;

                            }else if($key->tipo_Pregunta == '5'){ 

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d %H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;
                            
                            }else if($key->tipo_Pregunta == '10'){

                                $camposconsulta1 .= $separador.' DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;

                            }else{

                                $camposconsulta1 .= $separador.' '.$guion_c.$key->id.' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                            }
                            $iTo++;
                        }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
                    }
                   
                }//cierro este while $key = $campos_4->fetch_object()

                $camposconsulta1 .= ' FROM '.$BaseDatos.'.G'.$Script;
                $camposconsulta1 .= $joins;
                $consultas  .= $camposconsulta1.' WHERE G'.$Script.'_FechaInsercion BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY);"';
            }
            
        }//termino de recorrer los scripts de la tabla

        $nombresRe .= ' , "RESUMEN BASE DATOS"';
        
        $Lsqlx = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b  FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$base." AND SECCIO_TipoSecc__b != 2  ORDER BY SECCIO_Orden_____b ASC, PREGUN_OrdePreg__b ASC";


        $camposconsulta2 = ' , "SELECT G'.$base.'_ConsInte__b, DATE_FORMAT(G'.$base.'_FechaInsercion , \'%Y-%m-%d %H:%i:%S\') as FECHA_CREACION ';

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
                    $camposconsulta2 .= ' , '.$alfabeto[$alfa].'.LISOPC_Nombre____b AS '.strtolower($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos_systema.'.LISOPC as '.$alfabeto[$alfa].' ON '.$alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$guion_c.$key->id;
                    $alfa++;
                }else if($key->tipo_Pregunta =='11'){
                     $LsqlCamposPrimairos_2 = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$key->guion." ";
                    $campoPrimario = '';
                    $camposBuscadosIzquierda_2 = $mysqli->query($LsqlCamposPrimairos_2);
                    $campoPrimario = $camposBuscadosIzquierda_2->fetch_array();
                    

                    $camposconsulta2 .= ' , G'.$key->guion."_C".$campoPrimario['GUION__ConsInte__PREGUN_Pri_b'].' AS '.strtolower($titulodeLapregunta)."_".$key->id;

                    $joins .= ' LEFT JOIN '.$BaseDatos.'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$guion_c.$key->id;
                }else  if($key->tipo_Pregunta == '5'){ 

                    $camposconsulta1 .= ' ,  DATE_FORMAT('.$guion_c.$key->id.', \'%Y-%m-%d %H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;
                
                }else  if($key->tipo_Pregunta == '10'){

                    $camposconsulta1 .= ' , DATE_FORMAT('.$guion_c.$key->id.', \'%H:%i:%S\') AS '.strtolower($titulodeLapregunta)."_".$key->id;

                }else{
                    $camposconsulta2 .= ' , '.$guion_c.$key->id.' AS '.strtolower($titulodeLapregunta)."_".$key->id;
                }
            }//cierro este if $key->tipo_Pregunta != 9 && $key->tipo_Pregunta != 12
        }//cierro este while $key = $campos_4->fetch_object()

        $camposconsulta2 .= ' ,  A.MONOEF_Texto_____b AS \'ULTIMA GESTION\' , DATE_FORMAT(G'.$base.'_FecUltGes_b , \'%Y-%m-%d %H:%i:%S\')  as \'FECHA ULTIMA GESTION\', B.MONOEF_Texto_____b AS \'GESTION MAS IMPORTANTE\',  DATE_FORMAT(G'.$base.'_FeGeMaIm__b , \'%Y-%m-%d %H:%i:%S\')  as \'FECHA GESTION MAS IMPORTANTE\' FROM '.$BaseDatos.'.G'.$base;
        $camposconsulta2 .= $joins;
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS A ON A.MONOEF_ConsInte__b = G'.$base.'_UltiGest__b ';
        $camposconsulta2 .= ' LEFT JOIN '.$BaseDatos_systema.'.MONOEF AS B ON B.MONOEF_ConsInte__b = G'.$base.'_GesMasImp_b ORDER BY G'.$base.'_ConsInte__b DESC LIMIT 15000;';


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


                $nombresRe .= ' , "ANALISIS LLAMADAS ENTRANTES '.$titulodeLapregunta.'"';

                $consultas .= ' , "SELECT intervalo_traducido, dy_informacion_intervalos_h.* FROM dyalogo_telefonia.dy_informacion_intervalos_h WHERE id_campana='.$CAMPAN_IdCamCbx__b.' and fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY);"';
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
        $nombresRe .= ' , "SESSIONES"';
        $consultas .= ', "SELECT agente_nombre as Agente, min(fecha_hora_inicio) as Inicio, max(fecha_hora_fin) as Fin, sum(duracion) as DuracionT FROM dyalogo_telefonia.dy_v_historico_sesiones WHERE fecha_hora_inicio  BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.')) group by agente_id;"';

        /* Vamos a llenar las Pausas */
        $nombresRe .= ' , "PAUSAS"';
        $consultas .= ', "SELECT agente_nombre as Agente, fecha_hora_inicio as Inicio, fecha_hora_fin as Fin, duracion as DuracionT, tipo_descanso_nombre as TipoPausa FROM dyalogo_telefonia.dy_v_historico_descansos WHERE fecha_hora_inicio BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY) and agente_id IN(SELECT id FROM dyalogo_telefonia.dy_agentes join '.$BaseDatos_systema.'.USUARI ON id_usuario_asociado = USUARI_UsuaCBX___b join '.$BaseDatos_systema.'.ASITAR on USUARI_ConsInte__b = ASITAR_ConsInte__USUARI_b WHERE ASITAR_ConsInte__CAMPAN_b in('.$numerosCampanha.'));"';
        
        $nombresRe .= ' , "GESTIONES DE MAIL Y SMS"';
        $consultas .= ' , "SELECT SMS_MAIL_ESTPAS_Nombre__b  as Nombre_Paso, SMS_MAIL_Fecha__b as Fecha, SMS_MAIL_Tipo_b as Tipo_Gestion, SMS_MAIL_Total_Exitos_b AS Exitos, SMS_MAIL_Total_Fallos_b AS Fallos, SMS_MAIL_Total_b as Total_registros FROM '.$BaseDatos_systema.'.REPORTES_SMS_MAIL WHERE SMS_MAIL_ESTRAT_ConsInte__b = '.$id_estrategia.' AND SMS_MAIL_Fecha__b  BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY);"';

        /* Insertamos los reportes */ 
        $reportesXX = $mysqli->real_escape_string($consultas);
        $nombresXXX = $mysqli->real_escape_string($nombresRe);
        //echo $nombresXXX;
        $Lsql_InsercionRepor = "SELECT * FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_estrategia =".$id_estrategia." AND tipo_periodicidad = 3 LIMIT 1";
        $res = $mysqli->query($Lsql_InsercionRepor);

        $LsqlEstra = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$id_estrategia;
        $res_Estra = $mysqli->query($LsqlEstra);
        $datos = $res_Estra->fetch_array();

        $proyecto = str_replace(' ', '', $_SESSION['PROYECTO']);
        $proyecto = substr($proyecto, 0, 8);
        $proyecto = sanear_strings($proyecto);

        $estrategia = str_replace(' ', '', $datos['ESTRAT_Nombre____b']);
        $estrategia = substr($estrategia, 0, 8);
        $estrategia = sanear_strings($estrategia);

        if($res->num_rows > 0){

            $datosDeEstoCampana = $res->fetch_array();



            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_MENSUAL_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$destinatarios."' , 3 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                $Lsql = "UPDATE ".$BaseDatos_general.".reportes_automatizados SET bd_usuario = '".$DB_User_R."', bd_contrasena = '".$DB_Pass_R."', bd_ip = '".$ipReportes."', consultas = '".$reportesXX."', nombres_hojas = '".$nombresXXX."', id_huesped = ".$_SESSION['HUESPED'].", ruta_archivo = '/tmp/".$proyecto."_".$estrategia."_MENSUAL_".$datos['ESTRAT_ConsInte__b']."' wHERE id_estrategia = ".$id_estrategia." AND tipo_periodicidad = 3";
            }

        }else{
            if($destinatarios != null && $asunto != null && $hora != null){
                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto, destinatarios_cc) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_MENSUAL_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$destinatarios."' , 3 , '".$hora."' , '".$asunto."' , '".$copia."');";
            }else{

                $Lsql = "INSERT INTO ".$BaseDatos_general.".reportes_automatizados(bd_usuario, bd_contrasena, bd_ip, consultas, nombres_hojas, id_huesped, ruta_archivo, id_estrategia ,destinatarios , tipo_periodicidad, momento_envio, asunto) VALUES('".$DB_User_R."' , '".$DB_Pass_R."' , '".$ipReportes."', '".$reportesXX."', '".$nombresXXX."' , ".$_SESSION['HUESPED'].", '/tmp/".$proyecto."_".$estrategia."_MENSUAL_".$datos['ESTRAT_ConsInte__b']."' , ".$id_estrategia." , '".$_SESSION['CORREO']."' , 3 , '20:20' , '".$_SESSION['PROYECTO']."_".sanear_strings(str_replace(' ','_',$datos['ESTRAT_Nombre____b']))."_MENSUAL_".$datos['ESTRAT_ConsInte__b']."' );";
            }
        }
        
        if($mysqli->query($Lsql) === true){

        }else{
            echo "INSERTANDO EN LOS REPORTES 3 ".$mysqli->error;
        }

    }