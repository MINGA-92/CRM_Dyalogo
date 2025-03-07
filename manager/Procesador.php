<?php
	ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
	include ('pages/conexion.php');
	/* priemro recorremos la tabla pasos */
	//$Lsql = "SELECT ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Coordenadas_b, ESTCON_Comentari_b, ESTCON_Consulta_sql_b, ESTCON_Tipo_Insercion_b,  ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Fecha_b, ESTCON_Operacion_Hora_b, ESTCON_Cantidad_Fecha_b, ESTCON_Cantidad_Hora_b FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_Consulta_sql_b IS NOT NULL AND ESTCON_Ejecucion_Auto_b = 1 AND ESTCON_Activo_b = -1;";
	//$Lsql = "SELECT ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTPAS_Tipo______b,ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Coordenadas_b, 
		ESTCON_Comentari_b, ESTCON_Consulta_sql_b, ESTCON_Tipo_Insercion_b, ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Fecha_b, ESTCON_Operacion_Hora_b, 
		ESTCON_Cantidad_Fecha_b, ESTCON_Cantidad_Hora_b 
			FROM DYALOGOCRM_SISTEMA.ESTCON 
			JOIN DYALOGOCRM_SISTEMA.ESTPAS ON ESTPAS_ConsInte__b = ESTCON_ConsInte__ESTPAS_Des_b
			WHERE ESTCON_Consulta_sql_b IS NOT NULL 
			AND ESTCON_Ejecucion_Auto_b = 1 
			AND ESTCON_Activo_b = -1
			AND (ESTPAS_Tipo______b != 1 AND ESTPAS_Tipo______b != 4 AND ESTPAS_Tipo______b != 5 AND ESTPAS_Tipo______b != 6 AND ESTPAS_Tipo______b != 9 AND ESTPAS_Tipo______b != 10 AND ESTPAS_Tipo______b != 11 AND ESTPAS_Tipo______b != 12 AND ESTPAS_Tipo______b != 18);
	";
	exit;
	$res_Lsql = $mysqli->query($Lsql);
	while ($key = $res_Lsql->fetch_object()) {
		/* ya tenemos los conectores que estan en el sistema */
		if($key->ESTCON_Consulta_sql_b != '' && !is_null($key->ESTCON_Consulta_sql_b) && !empty($key->ESTCON_Consulta_sql_b)){
			/* tenemos que hacer algo porque hay una instruccion que seguir */

			$pasoInicial = $key->ESTCON_ConsInte__ESTPAS_Des_b;
			/* pero que?, debemos saber que tipo de paso es el receptor */
			$Lsql = "SELECT ESTPAS_Tipo______b, ESTPAS_ConsInte__CAMPAN_b, ESTPAS_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$key->ESTCON_ConsInte__ESTPAS_Has_b;
	
       		$res_Lsql_2 = $mysqli->query($Lsql);
        	$data = $res_Lsql_2->fetch_array();			
			
			switch ($data['ESTPAS_Tipo______b']) {
				case '6':
					/* toca meter en la muestra de este paso a las personas que cumplan esta concidion */
					$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConfDinam_b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$data["ESTPAS_ConsInte__CAMPAN_b"];
	                
	                $res_Lsql_Campan = $mysqli->query($Lsql_Campan);

	                if($res_Lsql_Campan){
		                $datoCampan = $res_Lsql_Campan->fetch_array();
		                $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
		                $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
		                $rea_ConfD_Campan = $datoCampan['CAMPAN_ConfDinam_b'];

		                /* ya tenemos la muestra ahora toca insertar esa jugada */
		                $Lsql = $key->ESTCON_Consulta_sql_b;
		                $res_ConLsql = $mysqli->query($Lsql);
		            	$muestraCompleta = $str_Pobla_Campan."_M".$int_Muest_Campan;

		            	if($res_ConLsql){
			                while ($ol = $res_ConLsql->fetch_object()) {


			                	$Lsql = "SELECT * FROM ".$BaseDatos.".".$muestraCompleta." WHERE ".$muestraCompleta."_CoInMiPo__b = ".$ol->id;
			                	$resLsql = $mysqli->query($Lsql);
			                	if($resLsql->num_rows === 0){

			                		if($key->ESTCON_Tipo_Insercion_b == '-1'){
			                                /* toca agendarlo */
				                		$nuevafecha = "NULL";
					                    if($key->ESTCON_ConsInte_PREGUN_Fecha_b == '-1' || $key->ESTCON_ConsInte_PREGUN_Fecha_b == '0'){
					                        /* toca sumarle datos a la fecha actual*/
					                        
					                        if($key->ESTCON_Cantidad_Fecha_b != ''){
					 
				                				$fecha = date('Y-m-d');
				                				$nuevafecha = strtotime (' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
								                $nuevafecha = date('Y-m-d',$nuevafecha);

								                //echo $nuevafecha;
				                            }
				                        }else{
				                            /* toca sumarle dias a un campo que tiene fecha */
				                            if($key->ESTCON_Cantidad_Fecha_b != ''){
				                                            
				                				$FechaLsql = "SELECT ".$str_Pobla_Campan."_C".$key->ESTCON_ConsInte_PREGUN_Fecha_b." as Fecha FROM ".$BaseDatos.".".$str_Pobla_Campan." WHERE ".$str_Pobla_Campan."_ConsInte__b = ".$ol->id;
				                				$res = $mysqli->query($FechaLsql);
				                				if($res){
				                					$fecha = $res->fetch_array();
				                				
					                				$fecha = explode(' ', $fecha['Fecha'])[0];
					                				
					                				$nuevafecha = strtotime ( ' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
					                				$nuevafecha = date('Y-m-d' , $nuevafecha);
												
				                				}
				                				
												//echo $nuevafecha;
				                            }
				                        }

				                        $operador = '';
			                            if($key->ESTCON_Operacion_Hora_b == '1'){
			                                $operador = '+';
			                            }else{
			                                $operador = '-';
			                            }

				                        if($key->ESTCON_ConsInte_PREGUN_Hora_b == '-1' || $key->ESTCON_ConsInte_PREGUN_Hora_b == '0'){
				                            /* toca sumarle datos a la fecha actual*/
				                            
				                                        
				                            if($key->ESTCON_Cantidad_Hora_b != ''){
				                           
				                				$hora = date('00:00:00');
				                				$nuevahora = strtotime ( ' '.$operador.$key->ESTCON_Cantidad_Hora_b.' hour' , strtotime ( $hora ) ) ;
				                				$nuevahora = date('H:i:s',$nuevahora);
				                				$nuevafecha = $nuevafecha.' '.$nuevahora;
				                            }
				                        }else{
				                            /* toca sumarle dias a un campo que tiene fecha */
				                            if($key->ESTCON_Cantidad_Hora_b != ''){
				       
				                				$HoraLsql = "SELECT ".$str_Pobla_Campan."_C".$key->ESTCON_ConsInte_PREGUN_Hora_b." as Hora FROM ".$BaseDatos.".".$str_Pobla_Campan." WHERE ".$str_Pobla_Campan."_ConsInte__b = ".$ol->id;
				            					$res = $mysqli->query($HoraLsql);
				            					if($res){
					                				$hora = $res->fetch_array();
					            					$hora = $hora['Hora'];
					                				$nuevahora = strtotime (' '.$operador.$key->ESTCON_Cantidad_Hora_b.' hour' , strtotime ( $hora ) ) ;
					                				$nuevahora = date('H:i:s',$nuevahora);
					                				$nuevafecha = $nuevafecha.' '.$nuevahora;
					                			}
				                            }
				                        }


				                        if($rea_ConfD_Campan == '-1'){
				                        	/* si ese registro existe lo metemos en la muestra si no, no lo hacemos */
					                		$InsertMuestra = "INSERT INTO ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b , ".$muestraCompleta."_NumeInte__b,".$muestraCompleta."_Estado____b, ".$muestraCompleta."_FecHorAge_b ) VALUES (".$ol->id.", 0 , 2, '".$nuevafecha."');";
				                            $mysqli->query($InsertMuestra);
				                        }else{

						                	$Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM     ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = ".$ol->id." AND (".$muestraCompleta."_Estado____b <> 3 OR (".$muestraCompleta."_Estado____b IS NULL)) AND (ASITAR_Automaticos_b <> 0 OR (ASITAR_Automaticos_b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";
						                	$res = $mysqli->query($Xlsql);
						                	$datoLsql = $res->fetch_array();

						            	 	$insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b, ".$muestraCompleta."_FecHorAge_b) VALUES (".$ol->id.", 0 , 2, ".$datoLsql['ASITAR_ConsInte__USUARI_b'].", '".$nuevafecha."');";
						  
						            		if($mysqli->query($insertarMuestraLsql) !== true){
						                		echo "error muestra = > ".$mysqli->error;
						                	}
				                        }
				                        
				                    }else{
				                    	

			                        	if($rea_ConfD_Campan == '-1'){
				                        	/* si ese registro existe lo metemos en la muestra si no, no lo hacemos */
				                			$InsertMuestra = "INSERT INTO ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b , ".$muestraCompleta."_NumeInte__b,".$muestraCompleta."_Estado____b ) VALUES (".$ol->id.", 0, 0);";
			                            	$mysqli->query($InsertMuestra);
				                        }else{

						                	$Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM     ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = ".$ol->id." AND (".$muestraCompleta."_Estado____b <> 3 OR (".$muestraCompleta."_Estado____b IS NULL)) AND (ASITAR_Automaticos_b <> 0 OR (ASITAR_Automaticos_b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";
						                	$res = $mysqli->query($Xlsql);
						                	$datoLsql = $res->fetch_array();

						            	 	$insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b) VALUES (".$ol->id.", 0 , 0, ".$datoLsql['ASITAR_ConsInte__USUARI_b'].");";
						  
						            		if($mysqli->query($insertarMuestraLsql) !== true){
						                		echo "error muestra = > ".$mysqli->error;
						                	}
				                        }
				                    }	                		
			                	}
			                
			                }
		               	}
	               	}

					break;

				case '7':

					/* toca meter en la muestra de el paso */

					/* toca meter en la muestra de este paso a las personas que cumplan esta concidion */
					$int_Muest_Campan = $data['ESTPAS_ConsInte__MUESTR_b'];

					if(!empty($int_Muest_Campan) && $int_Muest_Campan != null){
						$selTabla = "SELECT MUESTR_ConsInte__GUION__b FROM ".$BaseDatos_systema.".MUESTR WHERE MUESTR_ConsInte__b = ".$int_Muest_Campan;


		            	$resDel = $mysqli->query($selTabla);
		            	$data = $resDel->fetch_array();	


		                /* ya tenemos la muestra ahora toca insertar esa jugada */
		                $Lsql = $key->ESTCON_Consulta_sql_b;
		                $res_ConLsql = $mysqli->query($Lsql);

		                $muestraCompleta = "G".$data['MUESTR_ConsInte__GUION__b']."_M".$int_Muest_Campan;
		                if($res_ConLsql){
			                while ($ol = $res_ConLsql->fetch_object()) {


			                	/* armamos el nombre de la muestra */
			                	/* preguntamos si el resgitro existe */
			                	
			                	$Lsql = "SELECT * FROM ".$BaseDatos.".".$muestraCompleta." WHERE ".$muestraCompleta."_CoInMiPo__b = ".$ol->id;

			                	$resLsql = $mysqli->query($Lsql);
			  					
			                	if($resLsql->num_rows === 0){
			                		//echo $key->ESTCON_Tipo_Insercion_b;
			                		if($key->ESTCON_Tipo_Insercion_b == '-1'){
			                			$operador = '';
				                        if($key->ESTCON_Operacion_Fecha_b == '1'){
				                            $operador = '+';
				                        }else{
				                            $operador = '-';
				                        }
			                                /* toca agendarlo */
				                		$nuevafecha = "NULL";
					                    if($key->ESTCON_ConsInte_PREGUN_Fecha_b == '-1' || $key->ESTCON_ConsInte_PREGUN_Fecha_b == '0'){
					                        /* toca sumarle datos a la fecha actual*/
					                        
					                        if($key->ESTCON_Cantidad_Fecha_b != ''){
					 
				                				$fecha = date('Y-m-d');
				                				$nuevafecha = strtotime (' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
								                $nuevafecha = date('Y-m-d',$nuevafecha);

								                //echo $nuevafecha;
				                            }
				                        }else{
				                            /* toca sumarle dias a un campo que tiene fecha */
				                            if($key->ESTCON_Cantidad_Fecha_b != ''){
				                                            
				                				$FechaLsql = "SELECT G".$data['MUESTR_ConsInte__GUION__b']."_C".$key->ESTCON_ConsInte_PREGUN_Fecha_b." as Fecha FROM ".$BaseDatos.".G".$data['MUESTR_ConsInte__GUION__b']." WHERE G".$data['MUESTR_ConsInte__GUION__b']."_ConsInte__b = ".$ol->id;
				                				$res = $mysqli->query($FechaLsql);
				                				$fecha = $res->fetch_array();
				                				
				                				$fecha = explode(' ', $fecha['Fecha'])[0];
				                				
				                				$nuevafecha = strtotime ( ' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
				                				$nuevafecha = date('Y-m-d' , $nuevafecha);
												
												//echo $nuevafecha;
				                            }
				                        }

				                        $operador = '';
			                            if($key->ESTCON_Operacion_Hora_b == '1'){
			                                $operador = '+';
			                            }else{
			                                $operador = '-';
			                            }

				                        if($key->ESTCON_ConsInte_PREGUN_Hora_b == '-1' || $key->ESTCON_ConsInte_PREGUN_Hora_b == '0'){
				                            /* toca sumarle datos a la fecha actual*/
				                            
				                                        
				                            if($key->ESTCON_Cantidad_Hora_b != ''){
				                           
				                				$hora = date('00:00:00');
				                				$nuevahora = strtotime ( ' '.$operador.$key->ESTCON_Cantidad_Hora_b.' hour' , strtotime ( $hora ) ) ;
				                				$nuevahora = date('H:i:s',$nuevahora);
				                				$nuevafecha = $nuevafecha.' '.$nuevahora;
				                            }
				                        }else{
				                            /* toca sumarle dias a un campo que tiene fecha */
				                            if($key->ESTCON_Cantidad_Hora_b != ''){
				       
				                				$HoraLsql = "SELECT G".$data['MUESTR_ConsInte__GUION__b']."_C".$key->ESTCON_ConsInte_PREGUN_Hora_b." as Hora FROM ".$BaseDatos.".G".$data['MUESTR_ConsInte__GUION__b']." WHERE G".$data['MUESTR_ConsInte__GUION__b']."_ConsInte__b = ".$ol->id;
				            					$res = $mysqli->query($HoraLsql);
				                				$hora = $res->fetch_array();
				            					$hora = $hora['Hora'];
				                				$nuevahora = strtotime (' '.$operador.$key->ESTCON_Cantidad_Hora_b.' hour' , strtotime ( $hora ) ) ;
				                				$nuevahora = date('H:i:s',$nuevahora);
				                				$nuevafecha = $nuevafecha.' '.$nuevahora;
				                            }
				                        }

				                        /* si ese registro existe lo metemos en la muestra si no, no lo hacemos */
				                		$InsertMuestra = "INSERT INTO ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b , ".$muestraCompleta."_NumeInte__b,".$muestraCompleta."_Estado____b, ".$muestraCompleta."_FecHorAge_b ) VALUES (".$ol->id.", 0 , 2, '".$nuevafecha."');";
			                            if($mysqli->query($InsertMuestra) === true){

			                            }else{
			                            	echo "Erro => ".$mysqli->error;
			                            }
				                    }else{
				                    	/* si ese registro existe lo metemos en la muestra si no, no lo hacemos */
			                			$InsertMuestra = "INSERT INTO ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b , ".$muestraCompleta."_NumeInte__b,".$muestraCompleta."_Estado____b ) VALUES (".$ol->id.", 0, 0);";
			                        	$mysqli->query($InsertMuestra);
				                    }

			                		
			                	}
			                }
						}
					}
	                

					break;

				case '8':
					/* Se meten en la muestra del SMS
					*/
					
					/* Primero obtenermos la consulta a recorrer */
					/* toca meter en la muestra de este paso a las personas que cumplan esta concidion */
					$int_Muest_Campan = $data['ESTPAS_ConsInte__MUESTR_b'];

					if(!empty($int_Muest_Campan) && !is_null($int_Muest_Campan)){
						$selTabla = "SELECT MUESTR_ConsInte__GUION__b FROM ".$BaseDatos_systema.".MUESTR WHERE MUESTR_ConsInte__b = ".$int_Muest_Campan;
	                	$resDel = $mysqli->query($selTabla);
	                	$data = $resDel->fetch_array();			

		                /* ya tenemos la muestra ahora toca insertar esa jugada */
		                $Lsql = $key->ESTCON_Consulta_sql_b;
		            
		                $res_ConLsql = $mysqli->query($Lsql);

		                $muestraCompleta = "G".$data['MUESTR_ConsInte__GUION__b']."_M".$int_Muest_Campan;
		                if($res_ConLsql){
			                while ($ol = $res_ConLsql->fetch_object()) {
			                	/* armamos el nombre de la muestra */
			                	/* preguntamos si el resgitro existe */
			                	
			                	$Lsql = "SELECT * FROM ".$BaseDatos.".".$muestraCompleta." WHERE ".$muestraCompleta."_CoInMiPo__b = ".$ol->id;

			                	$resLsql = $mysqli->query($Lsql);
			                	if($resLsql->num_rows === 0){

			                		$operador = '';
			                        if($key->ESTCON_Operacion_Fecha_b == '1'){
			                            $operador = '+';
			                        }else{
			                            $operador = '-';
			                        }

			                		if($key->ESTCON_Tipo_Insercion_b == '-1'){

			                			
		                                    /* toca agendarlo */
				                		$nuevafecha = "NULL";
					                    if($key->ESTCON_ConsInte_PREGUN_Fecha_b == '-1' || $key->ESTCON_ConsInte_PREGUN_Fecha_b == '0'){
					                        /* toca sumarle datos a la fecha actual*/
					                        
					                        if($key->ESTCON_Cantidad_Fecha_b != ''){
				 
				                				$fecha = date('Y-m-d');
				                				$nuevafecha = strtotime (' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
								                $nuevafecha = date('Y-m-d',$nuevafecha);
							               	}
							                //echo $nuevafecha;
			                            }else{
			                            	$FechaLsql = "SELECT G".$data['MUESTR_ConsInte__GUION__b']."_C".$key->ESTCON_ConsInte_PREGUN_Fecha_b." as Fecha FROM ".$BaseDatos.".G".$data['MUESTR_ConsInte__GUION__b']." WHERE G".$data['MUESTR_ConsInte__GUION__b']."_ConsInte__b = ".$ol->id;

			                				$res = $mysqli->query($FechaLsql);
			                				$fecha = $res->fetch_array();
			                				
			                	

			                				$fecha = explode(' ', $fecha['Fecha'])[0];

			                				
			                				
			                				$nuevafecha = strtotime ( ' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
			                				$nuevafecha = date('Y-m-d' , $nuevafecha);
			                            }
			                        }else{
			                            /* toca sumarle dias a un campo que tiene fecha */
			                           	if($key->ESTCON_ConsInte_PREGUN_Fecha_b == '-1' || $key->ESTCON_ConsInte_PREGUN_Fecha_b == '0' ){
					                        /* toca sumarle datos a la fecha actual*/
					                        
					                        if($key->ESTCON_Cantidad_Fecha_b != ''){
				 
				                				$fecha = date('Y-m-d');
				                				$nuevafecha = strtotime (' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
								                $nuevafecha = date('Y-m-d',$nuevafecha);
							               	}
							                //echo $nuevafecha;
			                            }else{
			                            	$FechaLsql = "SELECT G".$data['MUESTR_ConsInte__GUION__b']."_C".$key->ESTCON_ConsInte_PREGUN_Fecha_b." as Fecha FROM ".$BaseDatos.".G".$data['MUESTR_ConsInte__GUION__b']." WHERE G".$data['MUESTR_ConsInte__GUION__b']."_ConsInte__b = ".$ol->id;
			                            	echo $FechaLsql;
			                				$res = $mysqli->query($FechaLsql);
			                				$fecha = $res->fetch_array();
			                				
			                	

			                				$fecha = explode(' ', $fecha['Fecha'])[0];

			                				
			                				
			                				$nuevafecha = strtotime ( ' '.$operador.$key->ESTCON_Cantidad_Fecha_b.' day' , strtotime ( $fecha ) ) ;
			                				$nuevafecha = date('Y-m-d' , $nuevafecha);
			                            }
			                        }

			                        $operador = '';
		                            if($key->ESTCON_Operacion_Hora_b == '1'){
		                                $operador = '+';
		                            }else{
		                                $operador = '-';
		                            }

			                        if($key->ESTCON_ConsInte_PREGUN_Hora_b == '-1' || $key->ESTCON_ConsInte_PREGUN_Hora_b == '0'){
			                            /* toca sumarle datos a la fecha actual*/
			                            
			                                        
			                            if($key->ESTCON_Cantidad_Hora_b != ''){
			                           
			                				$hora = date('00:00:00');
			                				$nuevahora = strtotime ( ' '.$operador.$key->ESTCON_Cantidad_Hora_b.' hour' , strtotime ( $hora ) ) ;
			                				$nuevahora = date('H:i:s',$nuevahora);
			                				$nuevafecha = $nuevafecha.' '.$nuevahora;
			                            }
			                        }else{
			                            /* toca sumarle dias a un campo que tiene fecha */
			                            if($key->ESTCON_Cantidad_Hora_b != ''){
			       
			                				$HoraLsql = "SELECT G".$data['MUESTR_ConsInte__GUION__b']."_C".$key->ESTCON_ConsInte_PREGUN_Hora_b." as Hora FROM ".$BaseDatos.".G".$data['MUESTR_ConsInte__GUION__b']." WHERE G".$data['MUESTR_ConsInte__GUION__b']."_ConsInte__b = ".$ol->id;
			            					$res = $mysqli->query($HoraLsql);
			                				$hora = $res->fetch_array();
			            					$hora = $hora['Hora'];
			                				$nuevahora = strtotime (' '.$operador.$key->ESTCON_Cantidad_Hora_b.' hour' , strtotime ( $hora ) ) ;
			                				$nuevahora = date('H:i:s',$nuevahora);
			                				$nuevafecha = $nuevafecha.' '.$nuevahora;
			                            }
			                        }

			                        /* si ese registro existe lo metemos en la muestra si no, no lo hacemos */
			                		$InsertMuestra = "INSERT INTO ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b , ".$muestraCompleta."_NumeInte__b,".$muestraCompleta."_Estado____b, ".$muestraCompleta."_FecHorAge_b ) VALUES (".$ol->id.", 0 , 2, '".$nuevafecha."');";
		                            $mysqli->query($InsertMuestra);
			                    }else{
			                    	/* si ese registro existe lo metemos en la muestra si no, no lo hacemos */
		                			$InsertMuestra = "INSERT INTO ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b , ".$muestraCompleta."_NumeInte__b,".$muestraCompleta."_Estado____b ) VALUES (".$ol->id.", 0, 0);";
		                        	$mysqli->query($InsertMuestra);
			                    }

		                		
		                	}
		                }
	                }                
					
					
					break;

				case '9':	
					//Sin gestión
					$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConfDinam_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$data["ESTPAS_ConsInte__CAMPAN_b"];
	        		$res_Lsql_Campan = $mysqli->query($Lsql_Campan);
	        
	                if($res_Lsql_Campan){
		                $datoCampan = $res_Lsql_Campan->fetch_array();
				        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
				        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
				        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
				        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
				        $int_Guion_Camp_2 = "G".$datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
			         	$rea_ConfD_Campan = $datoCampan['CAMPAN_ConfDinam_b'];



		                /* ya tenemos la muestra ahora toca insertar esa jugada */
		                $Lsql = $key->ESTCON_Consulta_sql_b;
		                $res_ConLsql = $mysqli->query($Lsql);

		                $LsqPregun  = "SELECT PREGUN_ConsInte__b , PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Guion_Campan." AND PREGUN_Texto_____b = 'ESTADO_TAREA'";
			        	$resPregun  = $mysqli->query($LsqPregun);
			        	$idEstado = 0;
			        	$preguntaEstado = '';
						if($resPregun->num_rows > 0){
							$datoPregun = $resPregun->fetch_array();
							$preguntaEstado = $datoPregun['PREGUN_ConsInte__b'];
							/* Vamos a bscar el Id de sin gestion */
							$LisopcLsql = "SELECT LISOPC_ConsInte__b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$datoPregun['PREGUN_ConsInte__OPCION_B']." AND LISOPC_Nombre____b = 'Sin gestión'" ;


							$resLisop = $mysqli->query($LisopcLsql);
							if($resLisop){
								$datosLisop = $resLisop->fetch_array();
								$idEstado = $datosLisop['LISOPC_ConsInte__b'];
								
							}
						}else{
							$array = array(
	                    		array('Sin gestión'),
			                    array('En gestión'),
			                    array('En gestión por devolución'),
			                    array('Cerrada'),
			                    array('Devuelta')
			                );
			                $tamanho = 5;
			                /*Insertamos el OPCION */
			                $insertLsql = "INSERT INTO ".$BaseDatos_systema.".OPCION (OPCION_ConsInte__GUION__b, OPCION_Nombre____b, OPCION_ConsInte__PROYEC_b, OPCION_FechCrea__b, OPCION_UsuaCrea__b) VALUES (".$int_Guion_Campan.", 'ESTADO_TAREA - ".$int_Guion_Campan."', ".$_SESSION['HUESPED'].", '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].");";
			                if($mysqli->query($insertLsql) === true){
			                    /* Se inserto la lista perfectamente */
			                    $ultimoLista = $mysqli->insert_id;
			                    for ($i=0; $i < $tamanho ; $i++) { 
			                        $insertLisopc = "INSERT INTO ".$BaseDatos_systema.".LISOPC (LISOPC_Nombre____b, LISOPC_ConsInte__OPCION_b, LISOPC_Posicion__b) VALUES ('".$array[$i][0]."', ".$ultimoLista.", ".$i.");";
			                        if($mysqli->query($insertLisopc) === true){
			                     
			                        }else{
			                            echo $mysqli->error;
			                        }
			                    }

			                    $Lsql_Reintento_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C207, G6_C209, G6_C44) VALUES ('ESTADO_TAREA', 6, 1, ".$int_Guion_Campan.", 0, ".$ultimoLista.");";
			                    if($mysqli->query($Lsql_Reintento_campo) === true){
			                        $int_Reintento_campo = $mysqli->insert_id;
			                        $Lsql_Editar_Guion = "UPDATE ".$BaseDatos_systema.".G5 SET G5_C312 = ".$int_Reintento_campo." WHERE G5_ConsInte__b = ".$int_Guion_Campan;
			                        if($mysqli->query($Lsql_Editar_Guion) !== true){
						                echo "error => ".$mysqli->error;
						            }

			                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".G".$int_Guion_Campan." ADD G".$int_Guion_Campan."_C".$int_Reintento_campo." int(10) DEFAULT NULL";
	                    			$mysqli->query($edit_Lsql);
			                    }
			                }
						}

						$numeroPaso = 0;
						$LsqPregun  = "SELECT PREGUN_ConsInte__b , PREGUN_ConsInte__GUION__PRE_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Guion_Campan." AND PREGUN_Texto_____b = 'PASO_ID'";
			        	$resPregun  = $mysqli->query($LsqPregun);
						if($resPregun->num_rows === 0){
							$Lsql_Paso_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C207, G6_C209) VALUES ('PASO_ID', 3, 1, ".$int_Guion_Campan.",0);";
		                    if($mysqli->query($Lsql_Paso_campo) === true){
		                    	$int_Reintento_campo = $mysqli->insert_id;
		                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".G".$int_Guion_Campan." ADD G".$int_Guion_Campan."_C".$int_Reintento_campo." int(10) DEFAULT NULL";
	                			$mysqli->query($edit_Lsql);
		                    }
						}else{
							$paso = $resPregun->fetch_array();
							$numeroPaso = $paso['PREGUN_ConsInte__b'];
						}


						$LsqPregun  = "SELECT PREGUN_ConsInte__b , PREGUN_ConsInte__GUION__PRE_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Guion_Campan." AND PREGUN_Texto_____b = 'REGISTRO_ID'";
			        	$resPregun  = $mysqli->query($LsqPregun);
						if($resPregun->num_rows === 0){
							$Lsql_Paso_campo = "INSERT INTO ".$BaseDatos_systema.".G6(G6_C39, G6_C40, G6_C51, G6_C207, G6_C209) VALUES ('REGISTRO_ID', 3, 1, ".$int_Guion_Campan.", 0);";
		                    if($mysqli->query($Lsql_Paso_campo) === true){
		                    	$int_Reintento_campo = $mysqli->insert_id;
		                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".G".$int_Guion_Campan." ADD G".$int_Guion_Campan."_C".$int_Reintento_campo." int(10) DEFAULT NULL";
	                			$mysqli->query($edit_Lsql);
		                    }
						}

		                if($res_ConLsql){
		                	while ($ol = $res_ConLsql->fetch_object()) {
		                		$Lsql = "SELECT * FROM ".$BaseDatos.".".$int_Guion_Camp_2." WHERE ".$int_Guion_Camp_2."_CodigoMiembro = ".$ol->id." AND ".$int_Guion_Camp_2."_PoblacionOrigen =".$int_Pobla_Camp_2;
		                		$resLsql = $mysqli->query($Lsql);
			                	if($resLsql->num_rows === 0){
			                		$campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$data["ESTPAS_ConsInte__CAMPAN_b"];
									$resultcampSql = $mysqli->query($campSql);
									//$Lsql = 'UPDATE '.$BaseDatos.'.'.$int_Guion_Camp_2.' , '.$BaseDatos.'.'.$str_Pobla_Campan.' SET ';
									$i=0;
									$select = 'SELECT '.$str_Pobla_Campan.'_ConsInte__b';
									$insert = 'INSERT INTO '.$BaseDatos.'.'.$int_Guion_Camp_2.'(G'.$int_Guion_Campan.'_CodigoMiembro';
							        while($key = $resultcampSql->fetch_object()){
				                        $validoparaedicion = false;
				                        $valorScript = $key->CAMINC_NomCamGui_b;

				                        $LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".".$str_Pobla_Campan." WHERE Field = '".$key->CAMINC_NomCamPob_b."'";

				                        //echo $LsqlShow;
				                        $resultShow = $mysqli->query($LsqlShow);
				                        if($resultShow->num_rows === 0){
				                            //comentario el campo no existe
				                            $validoparaedicion = false;
				                        }else{
				                            $validoparaedicion = true;
				                        } 

				                        $LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".".$int_Guion_Camp_2." WHERE Field = '".$key->CAMINC_NomCamGui_b."'";
				                        //echo $LsqlShow;
				                        $resultShow = $mysqli->query($LsqlShow);
				                        if($resultShow->num_rows === 0 ){
				                            //comentario el campo no existe
				                            $validoparaedicion = false;
				                        }else{
				                            $validoparaedicion = true;
				                        } 

				                    
				                        if($validoparaedicion){
				                        	$select .= ', '.$key->CAMINC_NomCamPob_b;
				                        	$insert .= ', '.$valorScript;
				                        }
				                        
							        } 
							        $select .= ' FROM '.$BaseDatos.'.'.$str_Pobla_Campan.' WHERE '.$str_Pobla_Campan.'_ConsInte__b = '.$ol->id; 
							        $insert .= ') ';
							        $Lsql = $insert.$select; 
							        //echo "Esta select => ".$select;;
							        if($mysqli->query($Lsql) === TRUE ){
							        	/* Actualizamos el estado */
							        	if($preguntaEstado != ''){

		        							$LsqlUpdate = "UPDATE ".$BaseDatos.".G".$int_Guion_Campan." SET ".$int_Guion_Camp_2."_C".$preguntaEstado." = ".$idEstado.", ".$int_Guion_Camp_2."_PoblacionOrigen = ".$int_Pobla_Camp_2." , ".$int_Guion_Camp_2."_FechaInsercion = '".date('Y-m-d H:i:s')."', ".$int_Guion_Camp_2."_C".$numeroPaso." = ".$pasoInicial." WHERE  G".$int_Guion_Campan.'_CodigoMiembro = '.$ol->id;
		        							echo $LsqlUpdate;
						                	if($mysqli->query($LsqlUpdate) === true){
						                		//Lo logro
						                	}else{
						                		echo "Error Actualizando el estado_dy => ".$mysqli->error;
						                	}
							        	}
			        				
							        }else{
							        	echo "NO SE ACTALIZO LA BASE DE DATOS ".$mysqli->error;
							        }
			                	}	
		                	}
		                }

		            }

				break;
			}
		}
	}

	
