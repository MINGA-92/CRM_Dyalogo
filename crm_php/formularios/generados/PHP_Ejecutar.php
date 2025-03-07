<?php
	
	ini_set('display_errors', 'On');
	ini_set('display_errors', 1);
	include(__DIR__."/../../conexion.php");
	include(__DIR__."/../../funciones.php");
	date_default_timezone_set('America/Bogota');

	require 'Tools/vendor/autoload.php'; // Asegúrate de cargar las dependencias de ReactPHP
	use React\Promise\Promise;
	
	function async_updateBD($campan,$bdCampan,$guionCampan,$post) {
		return new Promise(function ($resolve, $reject) use($campan,$bdCampan,$guionCampan,$post) {
			global $mysqli;
			global $BaseDatos_systema;
			global $BaseDatos;
			$ActualizaLsql = "SELECT CAMPAN_ActPobGui_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b =".$campan; 

			$resultado = $mysqli->query($ActualizaLsql);
			$datoArray = $resultado->fetch_array();
			if($datoArray['CAMPAN_ActPobGui_b'] == '-1'){
				/* toca hacer actualizacion desde Script */
				
				$campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$campan;
				$resultcampSql = $mysqli->query($campSql);
				// $Lsql = 'UPDATE '.$BaseDatos.'.'.$str_Pobla_Campan.' SET ';
				$Lsql = "UPDATE ".$BaseDatos.".G".$bdCampan." SET ";
				$i=0;

				$longitudes=getPregunConfig($bdCampan);
				// print_r("campSql => $campSql <br><br>");
				while($key = $resultcampSql->fetch_object()){
					$validoparaedicion = false;
					$valorScript = $key->CAMINC_NomCamGui_b;
					$LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".G".$bdCampan." WHERE Field = '".$key->CAMINC_NomCamPob_b."'";

					$resultShow = $mysqli->query($LsqlShow);
					if($resultShow->num_rows === 0){
						//comentario el campo no existe
						$validoparaedicion = false;
					}else{
						$validoparaedicion = true;
					} 

					$LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".G".$guionCampan." WHERE Field = '".$key->CAMINC_NomCamGui_b."'";

					// echo "LsqlShow->2 => $LsqlShow <br><br>";

					$resultShow = $mysqli->query($LsqlShow);
					if($resultShow->num_rows === 0 ){
						//comentario el campo no existe
						$validoparaedicion = false;
					}else{
						$validoparaedicion = true;
					} 

					$LsqlPAsaNull = "SELECT ".$key->CAMINC_NomCamGui_b." as Campo_valido FROM ".$BaseDatos.".G".$guionCampan." WHERE  G".$guionCampan.'_ConsInte__b = '.$_GET['ConsInteRegresado'];

					if ($LsqlRes = $mysqli->query($LsqlPAsaNull)) {

						if ($LsqlRes->num_rows > 0) {

							$sata = $LsqlRes->fetch_array();

							if ($sata['Campo_valido'] == '' || is_null($sata['Campo_valido'])) {
								$valorScript = 'NULL';
							}

							if($validoparaedicion){
								
								if(isset($post[$valorScript])){
									# retorna la cadena del campo del guion recortada, solo si se cumple la condiccion
									$intThisCamp=stripos($post[$valorScript],"_C") ? explode("_C",$post[$valorScript])[1] : $post[$valorScript];
									$longThisCamp= array_key_exists($intThisCamp,$longitudes) ? $longitudes[$intThisCamp] :0;
									$post[$valorScript] = characterCut($post[$valorScript], $longThisCamp);
									//$patron = "/^[0-9]{2,2}\s:\s[0-9]{2,2}\s:\s[0-9]{2,2}$/";

									// if (preg_match($patron, $post[$valorScript])) {
									if (date_create_from_format("Y-m-d H:i:s", date("Y-m-d")." ".$post[$valorScript])) {
										if($post[$valorScript] != '' && !is_null($post[$valorScript])){
											$strValor_t = date("Y-m-d")." ".str_replace(" ", "", $post[$valorScript]);
										}else{
											$strValor_t = $post[$valorScript];
										}

									}else{

										$strValor_t = $post[$valorScript];

									}

									if($i == 0){

										$Lsql .= $key->CAMINC_NomCamPob_b . " = ".informacionNula($strValor_t);
									}else{

										$Lsql .= " , ".$key->CAMINC_NomCamPob_b . " = ".informacionNula($strValor_t);
										
									}
									$i++;
								}
							}

						}

					}
					
				} 
				$idBd=isset($post['codigoMiembro']) ? $post['codigoMiembro'] : $post['codigoMiembro'];
				$Lsql .= ' WHERE G'.$bdCampan.'_ConsInte__b = '.$idBd;
				// echo "Esta CONSULTA UPDATE BD => $Lsql <br><br>";
				$LsqlBD  =$mysqli->query($Lsql);
				if($LsqlBD){
					$resolve($Lsql);
				}else{
					$reject($Lsql);
				}
			}
		});
	}

	function async_ExecuteQuery($query){
		return new Promise(function ($resolve, $reject) use($query){
			global $mysqli;
			$result=$mysqli->query($query);
			if($result){
				$resolve($query);
			}
			$reject(array($query,$mysqli->error));
		});
	}
	

	function informacionNula($strCadena_p){

		if (is_null($strCadena_p) || $strCadena_p == NULL || $strCadena_p == null || $strCadena_p == "" || $strCadena_p == '' || $strCadena_p == "NULL" || $strCadena_p == "null") {
			
			return "NULL";

		}else{

			return "'".$strCadena_p."'";

		}		

	}

	function getPregunConfig($bd)
	{	
		global $mysqli;
		# Buscamos la configuracion[longitud_pregun] por el campo que nos llega del guion
		$consulta = $mysqli->query("SELECT PREGUN_ConsInte__b AS id, PREGUN_Longitud__b AS longitud_pregun FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$bd} AND ( PREGUN_Tipo______b=1 OR PREGUN_Tipo______b=14)");
		if($consulta){
			$valores=array();
			while($key=$consulta->fetch_object()){
				$valores[$key->id]=$key->longitud_pregun;
			}
		}

		return array();
	}

	function characterCut($cadena, $intLongitudCampo)
	{
		# se parsea el valor a int
		# se cuenta la Longitud de la variable[cadena] 
		$intLongitudCampo = intval($intLongitudCampo);
		$intCadenaLength = mb_strlen($cadena) - $intLongitudCampo;
		if (!$intCadenaLength == 0 && $intLongitudCampo > 0 && mb_strlen($cadena) > $intLongitudCampo) {
			# Solo se corta la cade que llega del guion, cuando sea mayor a la longitud, configurada en pregun
			$cadena = mb_substr($cadena, -0, -$intCadenaLength);

		}
		return $cadena;
	}

	//ESTA FUNCIÓN ELIMINA LAS GESTIONES DUPLICADAS CUANDO EL FORMULARIO ENVIA EN EL OPER ADD
	function gestionDuplicada($formulario,$id_llamada,$usuario,$miembro,$insertado){
		//BUSCAMOS LA GESTIÓN QUE QUEDO TIPIFICADA CON EL -22 Y VALIDAMOS QUE TENGA EL MISMO ID DE LLAMADA
		global $mysqli;
		$sql=$mysqli->query("SELECT G{$formulario}_ConsInte__b AS id, G{$formulario}_FechaInsercion AS fecha FROM DYALOGOCRM_WEB.G{$formulario} WHERE G{$formulario}_IdLlamada=(SELECT G{$formulario}_IdLlamada FROM DYALOGOCRM_WEB.G{$formulario} WHERE G{$formulario}_ConsInte__b={$insertado})");
		if($sql && $sql->num_rows ==2){
			$nuevo=false;
			$antiguo=false;
			while($row = $sql->fetch_object()){
				if($row->id == $insertado){
					$nuevo=$row->id;
					$fechaNueva=$row->fecha;
				}else{
					$antiguo=$row->id;
					$fechaVieja=$row->fecha;
				}
			}

			if($nuevo && $antiguo){
				//ACTUALIZAR EL ID NUEVO CON LA FECHA DEL ANTIGUO
				$sqlUp=$mysqli->query("UPDATE DYALOGOCRM_WEB.G{$formulario} SET G{$formulario}_FechaInsercion='{$fechaVieja}',G{$formulario}_IdLlamada='{$id_llamada}',G{$formulario}_Duracion___b=timediff('{$fechaNueva}','{$fechaVieja}') WHERE G{$formulario}_ConsInte__b={$nuevo}");

				if($sqlUp && $mysqli->affected_rows == 1){
					//SI SE ACTUALIZO EL ID NUEVO, ENTONCES SE BORRA EL ID ANTIGUO
					$sqlDel=$mysqli->query("DELETE FROM DYALOGOCRM_WEB.G{$formulario} WHERE G{$formulario}_ConsInte__b={$antiguo}");
					if($sqlDel){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function getFormulario($campan)
	{
		global $mysqli;
		if(is_numeric($campan)){
			$sql=$mysqli->query("SELECT CAMPAN_ConsInte__GUION__Gui_b AS formulario FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_ConsInte__b={$campan}");

			if($sql && $sql->num_rows ==1){
				$sql=$sql->fetch_object();
				return $sql->formulario;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function getCampoTipificacion($g){
		global $mysqli;
		global $BaseDatos_systema;
		$sql=$mysqli->query("SELECT PREGUN_ConsInte__b FROM {$BaseDatos_systema}.PREGUN LEFT JOIN {$BaseDatos_systema}.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b ={$g} AND PREGUN_Texto_____b='Tipificación' AND SECCIO_TipoSecc__b=3");

		if($sql->num_rows > 0){
			$sql=$sql->fetch_object();
			$campo=$sql->PREGUN_ConsInte__b;
			return $campo;
		}
		return false;
	}

	function validateRegisterMuestra($codigoMiembro,$idBD,$idMuestra){
		global $mysqli;
		
		//VALIDAR SI EL CÓDIGO MIEMBRO YA EXISTE
		$sqlCount=$mysqli->query("SELECT {$idBD}_M{$idMuestra}_CoInMiPo__b FROM DYALOGOCRM_WEB.{$idBD}_M{$idMuestra} WHERE {$idBD}_M{$idMuestra}_CoInMiPo__b={$codigoMiembro}");
		if($sqlCount && $sqlCount->num_rows > 0){
			return true;
		}else{
			//INSERTAMOS EL REGISTRO EN LA MUESTRA
			$query="INSERT INTO DYALOGOCRM_WEB.{$idBD}_M{$idMuestra} ({$idBD}_M{$idMuestra}_CoInMiPo__b) VALUES({$codigoMiembro})";
			$sqlInsert=$mysqli->query($query);
			if($sqlInsert){
				return true;
			}else{
				$sql=$mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b) VALUES( \"{$query}\",'{$mysqli->error}','INSERCION MUESTRA')");
			}
		}
	}

	session_start(); //DLAB Activamos la sesion para poder obtener el dato del agente activo	
	
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $arrReturn = array();
    	if (isset($_GET["traerMonoef"])) {

    		$intIdMonoef_t = $_POST["intIdMonoef_t"];

    		$strSQLTraerMonoef_t = "SELECT LISOPC_ConsInte__b AS id FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_Clasifica_b = ".$intIdMonoef_t;
    		$resSQLTraerMonoef_t = $mysqli->query($strSQLTraerMonoef_t);

    		if ($resSQLTraerMonoef_t->num_rows > 0) {
    			
				$objSQLTraerMonoef_t = $resSQLTraerMonoef_t->fetch_object();   
				$intIdLisopc_t = $objSQLTraerMonoef_t->id;

				echo $intIdLisopc_t;  			

    		}else{

    			echo -1;

    		}


    	}

    	$strOrigen_t = "Sin origen";

    	if (isset($_POST["origenGestion"])) {
    		$strOrigen_t = $_POST["origenGestion"];
    	}

    	$strCanal_t = "Sin canal";

    	if (isset($_POST["strCanal_t"]) && $_POST["strCanal_t"] != "sin canal") {
    		$strCanal_t = $_POST["strCanal_t"];
    	}

    	if (isset($_GET['action'])) {
	    	if($_GET['action'] == 'ADD'){

				/* toca insertar un registro vacio y editarlo desde el script */
				$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConfDinam_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
		        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
		        $datoCampan = $res_Lsql_Campan->fetch_array();
		        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
		        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
		        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
		        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
	         	$rea_ConfD_Campan = $datoCampan['CAMPAN_ConfDinam_b'];
                $origen=$mysqli->query("SELECT PREGUN_ConsInte__b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$datoCampan['CAMPAN_ConsInte__GUION__Pob_b']} AND PREGUN_Texto_____b = 'ORIGEN_DY_WF'");
                $strCampoOrigen='';
                $strOrigen='';
                if($origen && $origen->num_rows == 1){
                    $origen=$origen->fetch_object();
                    $idCampoOrigen=$origen->PREGUN_ConsInte__b;
                    $strCampoOrigen=$str_Pobla_Campan."_C".$idCampoOrigen;
                    if(isset($_GET['canal'])){
                        if($_GET['canal'] == 'telefonia'){
                            $strOrigen='Insertado desde el agente';
                        }else{
                            $strOrigen=$_GET['canal'];
                        }
                    }
                }

				// Tengo que buscar cual es el paso origen para que quede registrado
				$pasoOrigenId = 0;

				if(isset($_GET['campana_crm'])){
					$sqlEstpas = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Comentari_b AS nombre FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_GET['campana_crm']." LIMIT 1";
					$resEstpas = $mysqli->query($sqlEstpas);
					if($resEstpas && $resEstpas->num_rows > 0){
						$dataEstpas = $resEstpas->fetch_object();
						$pasoOrigenId = $dataEstpas->id;
					}
				}

				$Lsql = "INSERT INTO ".$BaseDatos.".".$str_Pobla_Campan." (".$str_Pobla_Campan."_FechaInsercion,{$strCampoOrigen},{$str_Pobla_Campan}_OrigenUltimoCargue,{$str_Pobla_Campan}_UltiGest__b,{$str_Pobla_Campan}_GesMasImp_b,{$str_Pobla_Campan}_TipoReintentoUG_b,{$str_Pobla_Campan}_TipoReintentoGMI_b,{$str_Pobla_Campan}_ClasificacionUG_b,{$str_Pobla_Campan}_ClasificacionGMI_b,{$str_Pobla_Campan}_EstadoUG_b,{$str_Pobla_Campan}_EstadoGMI_b,{$str_Pobla_Campan}_CantidadIntentos,{$str_Pobla_Campan}_CantidadIntentosGMI_b, {$str_Pobla_Campan}_PoblacionOrigen) VALUES ('".date('Y-m-d H:i:s')."','{$strOrigen}','{$strOrigen}',-15,-15,0,0,3,3,-15,-15,0,0, '{$pasoOrigenId}');";
				if ($mysqli->query($Lsql) === TRUE) {
	                $resultado = $mysqli->insert_id;
	                if($rea_ConfD_Campan == '-1'){
						$usuari=-1;
						if(isset($_GET['idUSUARI'])){
							$usuari=$_GET['idUSUARI'];
						}
	                	$InsertMuestra = "INSERT INTO ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." (".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b , ".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b, ".$str_Pobla_Campan."_M".$int_Muest_Campan."_Estado____b,{$str_Pobla_Campan}_M{$int_Muest_Campan}_ConIntUsu_b) VALUES ({$resultado}, 0, 0, {$usuari});";
	                	if($mysqli->query($InsertMuestra) !== true){
	                		echo "error muestra 1= > ".$mysqli->error;
                            echo $InsertMuestra."||";
	                	}
	                }else{//Si la configuracion es predefinida
	                	$muestraCompleta = $str_Pobla_Campan."_M".$int_Muest_Campan;
						//SI EL ID DE USUARIO LLEGA POR GET
						if(isset($_GET["idUSUARI"]) && is_numeric($_GET["idUSUARI"])){
							$insertarMuestraLsql = "INSERT INTO  " . $BaseDatos . "." . $muestraCompleta . " (" . $muestraCompleta . "_CoInMiPo__b ,  " . $muestraCompleta . "_NumeInte__b, " . $muestraCompleta . "_Estado____b , " . $muestraCompleta . "_ConIntUsu_b) VALUES (" . $resultado . ", 0 , 0, " . $_GET['idUSUARI'] . ");";
						}else{
							//DLAB Modificacion para setear el ID del usuario y no el de la consulta rara
							if(!isset($_SESSION['USER_ID'])){
								if (isset($_GET['token'])) {
									$token = $_GET["token"];
									$idAgente = "SELECT SESSIONS__USUARI_ConsInte__b FROM DYALOGOCRM_SISTEMA.SESSIONS where SESSIONS__Token='" . $token . "';";
									$query = $mysqli->query($idAgente);
									$datosAgente = $query->fetch_array();
									$insertarMuestraLsql = "INSERT INTO " . $BaseDatos . "." . $muestraCompleta . " (" . $muestraCompleta . "_CoInMiPo__b , " . $muestraCompleta . "_NumeInte__b, " . $muestraCompleta . "_Estado____b , " . $muestraCompleta . "_ConIntUsu_b) VALUES (" . $resultado . ", 0 , 0, " . $datosAgente['SESSIONS__USUARI_ConsInte__b'] . ");";
								} else {
									$Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM     ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = ".$_GET["campana_crm"]." AND (".$muestraCompleta."_Estado____b <> 3 OR (".$muestraCompleta."_Estado____b IS NULL)) AND (ASITAR_Automaticos_b <> 0 OR (ASITAR_Automaticos_b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";
									$res = $mysqli->query($Xlsql);
									$datoLsql = $res->fetch_array();
									$insertarMuestraLsql = "INSERT INTO  " . $BaseDatos . "." . $muestraCompleta . " (" . $muestraCompleta . "_CoInMiPo__b ,  " . $muestraCompleta . "_NumeInte__b, " . $muestraCompleta . "_Estado____b , " . $muestraCompleta . "_ConIntUsu_b) VALUES (" . $resultado . ", 0 , 0, " . $datoLsql['ASITAR_ConsInte__USUARI_b'] . ");";
								}		
							}else{ //Si el usuario si esta seteado en la sesion
								$insertarMuestraLsql = "INSERT INTO " . $BaseDatos . "." . $muestraCompleta . " (" . $muestraCompleta . "_CoInMiPo__b , " . $muestraCompleta . "_NumeInte__b, " . $muestraCompleta . "_Estado____b , " . $muestraCompleta . "_ConIntUsu_b) VALUES (" . $resultado . ", 0 , 0, " . $_SESSION['USER_ID'] . ");";
							}
						}

						if($mysqli->query($insertarMuestraLsql) !== true){
							echo "error muestra 2= > ".$mysqli->error;
                            echo $insertarMuestraLsql."||";
						}
					}

					// Insertamos la creacion del registro en el journey
					try {
						$userJ = (isset($_GET['idUSUARI'])) ? $_GET['idUSUARI'] : null;
						$idBdJ= isset($resultado) ? $resultado : null;
						$intIdPoblacion = $int_Pobla_Camp_2;

						$dataJourney = ["agente" => $userJ, "sentido" => "Entrante", "canal" => $strOrigen, "tipificacion" => -15, "clasificacion" => 3, "tipoReintento" => 0, "comentario" => 'Registro creado desde el agente' ];

						insertarJourney($idBdJ,$intIdPoblacion, $pasoOrigenId, $dataJourney);
					} catch (\Throwable $th) {}

	                echo $resultado;            
	            }
			}
		
			if($_GET['action'] == 'EDIT'){
				crearMiembroDefault($_GET["campana_crm"]);//funcioon que crea usuario por defecto
				/* primero buscamos la campaña que nos esta llegando */
				$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b,CAMPAN_TipoCamp__b,CAMPAN_ConfDinam_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];			

				//echo $Lsql_Campan;

		        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
		        $datoCampan = $res_Lsql_Campan->fetch_array();
		        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
		        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
		        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
		        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
		        $tipoMarcador=$datoCampan['CAMPAN_TipoCamp__b'];
	            $rea_ConfD_Campan = $datoCampan['CAMPAN_ConfDinam_b'];


		        /* Aqui se hace la jugada de la actualizacion */
		        if(!isset($_GET['cerrarForzado'])){
					// Llamada al método asincrónico
					async_updateBD($_GET["campana_crm"],$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'],$datoCampan['CAMPAN_ConsInte__GUION__Gui_b'],$_POST)
					->then(function ($result) {
						$arrReturn["BD-GUION"]="OK";
						$arrReturn["BD-GUION-QUERY"]=$result;
					}, function ($error) {
						global $mysqli;
						$queryBD="INSERT INTO DYALOGOCRM_SISTEMA.LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b) VALUES( \"{$error}\",'error al actualizar la bd con la información del formulario','DB')";
						$mysqli->query($queryBD);
					});
		        }

		       

		                

				$UltiGest="NULL";
				$gestionMIMP="NULL";
				$FecUltGes="NULL";
				$fechasGMIMP="NULL";
				$reintento = "NULL";			       
		        $TipoReintentoGMI="NULL";
		        $fechaAgenda = "NULL";
				$FecHorAgeGMI="NULL";
				$conatcto = "NULL";
		        $contactoMasImp = "NULL";
		        $EstadoUG="NULL";
			    $EstadoGMI="NULL";
			    $UsuarioUG="NULL";
		 		$UsuarioGMI="NULL";
		 		$CanalUG="";
		        $CanalGMI="";
		        $SentidoUG=""; 
		        $SentidoGMI="";
		 		$CantidadIntentosGMI="NULL";
		 		$ComentarioGMI="";
		 		$ComentarioUG="";
		        $LinkContenidoUG="";
				$LinkContenidoGMI="";
				$DetalleCanalUG="";
	            $DetalleCanalGMI="";
	            $DatoContactoUG="";
	            $DatoContactoGMI="";	
				$PasoUG="NULL";
	            $PasoGMI="NULL";
                $booCanal=false;
				$strTelefono_t=""; 
		
	     			
						

	            if( isset($_GET["campana_crm"]) ){
	            	$Lsql="SELECT ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_GET["campana_crm"]." AND (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6)";            
		             if( ($query = $mysqli->query($Lsql)) == TRUE ){
		                $array = $query->fetch_array();  
		                if($array["ESTPAS_ConsInte__b"] != ''){
		                	 $PasoUG=$array["ESTPAS_ConsInte__b"];    
		                	 $PasoGMI=$array["ESTPAS_ConsInte__b"];  
		                }
		                    
		  
		            }
	            }

	            
	            if(isset($_POST['datoContacto']) && $_POST['datoContacto'] != "" && $_POST['datoContacto'] != "0" && $_POST['datoContacto'] != NULL){
                        $strTelefono_t=$_POST['datoContacto'];
	            		$DatoContactoUG=str_replace("'", "", $_POST['datoContacto']);
		            	$strQuitar="A".$_GET["campana_crm"];
		            	$DatoContactoUG = str_replace($strQuitar,"", $DatoContactoUG);
		            	$DatoContactoUG=str_replace("'", "", $DatoContactoUG);
		            	$DatoContactoGMI=$DatoContactoUG;
	            	
	            }

	           
	           if ($DatoContactoUG != "NULL" && $PasoUG != "NULL" &&  $_GET["campana_crm"] != null && $DatoContactoUG != "" && $PasoUG != "" && $_GET["campana_crm"] != "" && isset($_GET["campana_crm"])){
	               $DetalleCanalUG=detalle_canal($DatoContactoUG,$PasoUG ,$_GET["campana_crm"]);
	               $DetalleCanalGMI=$DetalleCanalUG;
	           }


				
				if(isset($_POST['MonoEf'])  &&  $_POST['MonoEf'] != '' &&  $_POST['MonoEf'] !=  '0' &&  $_POST['MonoEf'] != null ){

					$LmonoEfLSql = "SELECT * FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b = ".$_POST['MonoEf'];
			        $resMonoEf = $mysqli->query($LmonoEfLSql);
			        $dataMonoEf = $resMonoEf->fetch_array();

					$gestionMIMP = $_POST['MonoEf'];
					$UltiGest=$_POST['MonoEf'];
	                

					$reintento = $dataMonoEf['MONOEF_TipNo_Efe_b'];			       
		        	$TipoReintentoGMI=$dataMonoEf['MONOEF_TipNo_Efe_b'];	

					$conatcto = $dataMonoEf['MONOEF_Contacto__b'];
					$contactoMasImp = $dataMonoEf['MONOEF_Contacto__b'];
	                
	                $FecHorMinProGes__b =$dataMonoEf['MONOEF_CanHorProxGes__b'];

					if($dataMonoEf['MONOEF_TipiCBX___b'] != '' && $dataMonoEf['MONOEF_TipiCBX___b'] != NULL && $dataMonoEf['MONOEF_TipiCBX___b'] != '0' ){

			        	$EstadoUG=$dataMonoEf['MONOEF_TipiCBX___b'];
			        	$EstadoGMI=$dataMonoEf['MONOEF_TipiCBX___b'];
	                    

			        }


				}		
				
				
				// if(isset($_GET['tiempo'])){
				// 	$fechasGMIMP = "'".$_GET['tiempo']."'";;
				// 	$FecUltGes="'".$_GET['tiempo']."'";;
				// }
		        
		        if ( isset($_GET['usuario']) ) {
		        	$UsuarioUG=$_GET['usuario'];
		 			$UsuarioGMI=$_GET['usuario'];
		        }	        
		 		
		 		if (isset($_POST['textAreaComentarios'])) {
		 			$ComentarioGMI=$_POST['textAreaComentarios'];
		 			$ComentarioUG=$_POST['textAreaComentarios'];
		 		}
		 		
		 		 if(isset($_POST['TxtFechaReintento']) && $_POST['TxtFechaReintento'] != '' && $_POST['TxtFechaReintento'] != null){
		        	$fechaAgenda =  "'".$_POST['TxtFechaReintento']." ".str_replace(" ", "",$_POST['TxtHoraReintento'])."'";
		        	$FecHorAgeGMI=$fechaAgenda;
				}            


	            $valorId_Gestion_Cbx = $_POST['id_gestion_cbx'];
	            $valorId_Gestion_Cbx_2 = $_POST['id_gestion_cbx'];

	            if(isset($_POST['idLlamada']) && $_POST['idLlamada'] != 0 && $_POST['idLlamada'] != null){
	                
	                /* Toca averiguar lo del Coninte de la gestio */
	                $valorSentido = $_POST['idLlamada'];
	                $LsqlXUnique = "SELECT unique_id FROM dyalogo_telefonia.dy_llamadas_salientes where bunique_id LIKE '%".$valorSentido."%';";
	                $resPXunique = $mysqli->query($LsqlXUnique);
	                if($resPXunique && $resPXunique->num_rows > 0){
	                    $estoUnique = $resPXunique->fetch_array();
	                    if( $estoUnique['unique_id'] != null &&  $estoUnique['unique_id'] != ''){
	                        $valorId_Gestion_Cbx = $estoUnique['unique_id'];  
	                    }else{
	                        $valorId_Gestion_Cbx = $valorSentido;
	                    }
	                }else{
	                    $valorId_Gestion_Cbx = $valorSentido;
	                }
	            	

	            }else{
	                
	                /* Toca averiguar lo del Coninte de la gestio */
	                $valorSentido = explode('_', $valorId_Gestion_Cbx_2)[1];
	                $LsqlXUnique = "SELECT unique_id FROM dyalogo_telefonia.dy_llamadas_salientes where bunique_id LIKE '%".$valorSentido."%';";
	                $resPXunique = $mysqli->query($LsqlXUnique);
	                if($resPXunique && $resPXunique->num_rows > 0){
	                    $estoUnique = $resPXunique->fetch_array();
	                    if( $estoUnique['unique_id'] != null &&  $estoUnique['unique_id'] != ''){
	                        $valorId_Gestion_Cbx = $estoUnique['unique_id'];  
	                    }else{
	                        $valorId_Gestion_Cbx = $valorSentido;
	                    }
	                }else{
	                    $valorId_Gestion_Cbx = $valorSentido;
	                }
	            }

	            //armamos el link para descargar grabaciones


	            if( $valorId_Gestion_Cbx != '' ){
                    $Lsql = "SELECT ip_servidor FROM dyalogo_telefonia.dy_configuracion_crm WHERE id_huesped = -1 AND sistema = 0";
                    if( ($query = $mysqli->query($Lsql)) == TRUE ){
                        $array = $query->fetch_array();    
                        $rest = substr($valorId_Gestion_Cbx, 0,1);
                        if($rest !== 'd' && $rest !== 'D'){
                            $booCanal=true;
                            
							$arrOrigen_t=explode('_', $valorId_Gestion_Cbx_2);
							//echo json_encode($arrOrigen_t);die();
							if(count($arrOrigen_t)===3){
								$valorId_Gestion_Cbx = $arrOrigen_t[1]."_".$arrOrigen_t[2];
								if(strrchr($arrOrigen_t[2],'RNC')){
									$strOrigen_t='RingNoAnswer';
								}else{
									$strOrigen_t='Transferida';
								}
							}
                            $LinkContenidoUG="https://".$array["ip_servidor"].":8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=".$valorId_Gestion_Cbx."&uid2=".$valorId_Gestion_Cbx;	  

                        }else{
							//PROCEDEMOS A BUSCAR LA LLAMADA EN LA TABLA
							$sqlIds=$mysqli->query("SELECT * FROM dyalogo_telefonia.dy_llamadas_salientes_ids WHERE uid_auto='{$valorId_Gestion_Cbx}' limit 1");
							if($sqlIds && $sqlIds->num_rows >0){
								$sqlIds=$sqlIds->fetch_object();
								if($sqlIds->uid_actualizado != null && $sqlIds->uid_actualizado !=''){
									$LinkContenidoUG="https://".$array["ip_servidor"].":8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=".$sqlIds->uid_actualizado."&uid2=".$sqlIds->uid_actualizado;
								}else{
									$LinkContenidoUG='No hubo comunicación por lo tanto no se genera un link de grabación';
								}
							}else{
								$LinkContenidoUG='No hubo comunicación por lo tanto no se genera un link de grabación';
							}
                        }

						$LinkContenidoGMI= $LinkContenidoUG;

                    }
				}

	            $sentidoX = NULL;
	            $sentidoY = 0;
	            $valorSentido = NULL;
	            /* Rellenar los datos del Script */
	            $validoCamposX = 1;
		      
	        	if(isset($_POST['cbx_sentido']) && $_POST['cbx_sentido'] != 0){
	                $sentidoY = $_POST['cbx_sentido'];
	        		if($_POST['cbx_sentido'] == '1'){
	        			$sentidoX = 'Saliente';
	        		}else{
	        			$sentidoX = 'Entrante';
	        		}
	        	}else{
	        		$sentidoX = 0;
	        	}

	        	$SentidoUG=$sentidoX;
	        	$SentidoGMI=$sentidoX;
	        	$valorSentido = explode('_', $valorId_Gestion_Cbx_2)[0];
	        	$CanalUG=$strCanal_t;
	        	$CanalGMI=$strCanal_t;
	        	// $fechaInicial = new DateTime($_GET['tiempo']);
	            // $fechaFinal = new DateTime($_POST['FechaFinal']);
	            // $duracion = $fechaInicial->diff($fechaFinal);
	            $fechaInsercion = date('Y-m-d H:i:s');
                $strCanalLink_t='';
                if($booCanal){
		            if ($CanalUG == "bq_manual" || $CanalUG == "BusquedaManual") {
                    	$strCanalLink_t="&canal=telefonia";
                        $CanalUG=='BusquedaManual';
		            }else{
                    	$strCanalLink_t="&canal={$CanalUG}";
		            }
                }else{
                    // $CanalUG='sin canal';
                    // $strCanal_t = "sin canal";
                    // $valorSentido= 'sin canal';
                }
                
                
                $LsqlCampoFechaG = $mysqli->query("SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b FROM {$BaseDatos_systema}.PREGUN LEFT JOIN {$BaseDatos_systema}.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE (PREGUN_Texto_____b = 'Fecha' OR PREGUN_Texto_____b='Hora' OR PREGUN_Texto_____b='Campaña' OR PREGUN_Texto_____b='Agente') AND PREGUN_ConsInte__GUION__b = {$int_Guion_Campan} AND SECCIO_TipoSecc__b=4");
                if($LsqlCampoFechaG && $LsqlCampoFechaG->num_rows >1){
                    while($campo=$LsqlCampoFechaG->fetch_object()){
                        if($campo->PREGUN_Texto_____b =='Fecha'){
                            $intCampoFechaG="G{$int_Guion_Campan}_C{$campo->PREGUN_ConsInte__b}";
                        }
                        
                        if($campo->PREGUN_Texto_____b == 'Hora'){
                            $intCampoHoraG="G{$int_Guion_Campan}_C{$campo->PREGUN_ConsInte__b}";
                        }                        
                        
                        if($campo->PREGUN_Texto_____b == 'Agente'){
                            $intCampoAgenteG="G{$int_Guion_Campan}_C{$campo->PREGUN_ConsInte__b}";
                        }
                        
                        if($campo->PREGUN_Texto_____b == 'Campaña'){
                            $qryNombreCampan =$mysqli->query("SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b =".$_GET['campana_crm']);
                            if($qryNombreCampan && $qryNombreCampan -> num_rows==1){
                                $objNombreCampan = $qryNombreCampan->fetch_object();
                                $strCampoCampan="G{$int_Guion_Campan}_C{$campo->PREGUN_ConsInte__b}";
                                $strNombreCampan=$objNombreCampan->CAMPAN_Nombre____b;
                            }
                        }
                    }
                }
	            /**
			     *actualizacion del script
			    */
                
                $canalActual='';
                $origenActual='';
                $aniActual='';
                $validaRegistro=$mysqli->query("SELECT G{$int_Guion_Campan}_Canal_____b AS canal,G{$int_Guion_Campan}_DatoContacto AS ani,G{$int_Guion_Campan}_Origen_b AS origen FROM {$BaseDatos}.G{$int_Guion_Campan} WHERE G{$int_Guion_Campan}_ConsInte__b={$_GET['ConsInteRegresado']}");
                if($validaRegistro && $validaRegistro->num_rows==1){
                    $objDataRegistro=$validaRegistro->fetch_object();
                    $canalActual=$objDataRegistro->canal;
                    $origenActual=$objDataRegistro->origen;
                    $aniActual=$objDataRegistro->ani;
                }
	        	$LsqlUpdateCamposX = "UPDATE ".$BaseDatos.".G".$int_Guion_Campan." SET ";
	        	$LsqlUpdateCamposX .= " G".$int_Guion_Campan."_IdLlamada =  '".$valorId_Gestion_Cbx."'";
		        $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_Sentido___b =  '".$SentidoUG."'";
                if($canalActual=='' || $canalActual=='Sin canal' || $canalActual=='sin canal' || is_null($canalActual)){
		          $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_Canal_____b =  '".$strCanal_t."'";  
                }
                if($origenActual=='' || $origenActual=='Sin origen' || is_null($origenActual) || $strOrigen_t=='Transferida' || $strOrigen_t=='RingNoAnswer'){
		          $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_Origen_b =  '".$strOrigen_t."'";
                }
		        $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_LinkContenido =  '".$LinkContenidoUG.$strCanalLink_t."'";
		        $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_Clasificacion =  ".$conatcto;
	            $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_Paso =  ".$PasoUG;
                if($aniActual=='' || $aniActual=='0' || is_null($aniActual)){
	               $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_DatoContacto =  '".$DatoContactoUG."'";
                }
	            $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_DetalleCanal =  '".$DetalleCanalUG."'";
	            $LsqlUpdateCamposX .= ", G".$int_Guion_Campan."_CodigoMiembro =  '".$_POST['codigoMiembro']."'";
                if(isset($intCampoFechaG) && isset($intCampoHoraG)){
                    $LsqlUpdateCamposX .= ", {$intCampoFechaG} = NOW()";
                    $LsqlUpdateCamposX .= ", {$intCampoHoraG} = NOW()";
                    $LsqlUpdateCamposX .= ", G{$int_Guion_Campan}_Duracion___b = timediff(NOW(),G{$int_Guion_Campan}_FechaInsercion)";
                }
                if(isset($strCampoCampan) && isset($strNombreCampan)){
                    $LsqlUpdateCamposX .= ", {$strCampoCampan} = '{$strNombreCampan}'";
                }                
                
                if(isset($intCampoAgenteG)){
                    $LsqlUpdateCamposX .= ", {$intCampoAgenteG} ='".NombreAgente($_GET['usuario'])."'";
                }

				if(isset($_GET['usuario']) && is_numeric($_GET['usuario'])){
					$LsqlUpdateCamposX .= ", G{$int_Guion_Campan}_Usuario = {$_GET['usuario']}";
				}

	            

	            $LsqlReintento = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN LEFT JOIN {$BaseDatos_systema}.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_Texto_____b = 'Reintento' AND PREGUN_ConsInte__GUION__b = ".$int_Guion_Campan." AND SECCIO_TipoSecc__b=3 LIMIT 1;";
	            $res = $mysqli->query($LsqlReintento);
	            if($res){
	                $datoReintento = $res->fetch_array();
	                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".G".$int_Guion_Campan." WHERE Field = 'G".$int_Guion_Campan."_C".$datoReintento['PREGUN_ConsInte__b']."' ";
	                $result = $mysqli->query($Lsql);
	                if($result->num_rows === 0){
	                    
	                }else{

	                	if (is_null($reintento) || $reintento == null || $reintento == "" || $reintento == "NULL" || $reintento == "null") {
	                		
	                		$strReintento_t = "NULL";

	                	}else{

	                		$strReintento_t = "'".$reintento."'";

	                	}

	                    $LsqlUpdateCamposX .= " , G".$int_Guion_Campan."_C".$datoReintento['PREGUN_ConsInte__b']." = ".$strReintento_t." ";
	                }
	            }
	            

	        	$LsqlUpdateCamposX .= "  WHERE G".$int_Guion_Campan."_ConsInte__b = ".$_GET['ConsInteRegresado'];

	        	if($mysqli->query($LsqlUpdateCamposX) === true){
                    $arrReturn["SCRIPT"]="OK";
                    $arrReturn["SCRIPT-QUERY"]=$LsqlUpdateCamposX;

					// Se aprovecha la misma consulta del tiempo para traer la fecha de insersion de la gestion
					$consultaDurationCondia = "SELECT G{$int_Guion_Campan}_Duracion___b AS duracion, G{$int_Guion_Campan}_FechaInsercion AS fechaInsercion  FROM {$BaseDatos}.G{$int_Guion_Campan} WHERE G{$int_Guion_Campan}_ConsInte__b = {$_GET['ConsInteRegresado']}";

					// echo "<br> consultaDurationCondia => $consultaDurationCondia <br><br>";

					$sqlDurationCondia = mysqli_query($mysqli, $consultaDurationCondia);

					if($sqlDurationCondia && mysqli_num_rows($sqlDurationCondia) > 0){
						$sqlDurationCondia=$sqlDurationCondia->fetch_object();
						$duracion=$sqlDurationCondia->duracion;
						$fechaInsercionGestion=$sqlDurationCondia->fechaInsercion;
					}else{
						$duracion='00:03:00';
					}
	        	}else{
	        		$arrReturn["SCRIPT"]="Error actualizando el sentido y canal del Script => ".$mysqli->error;        		
	        		$queryScript="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
					VALUES(\"".$LsqlUpdateCamposX."\",\"".$mysqli->error."\",'SCRIPT')";
					$mysqli->query($queryScript);
	        	}

	        	
	        	//JDBD - Verificamos si la gestion que llega es mas importante o igual que la que ya esta en la base.
	        	$strSQLGesMasImp_t = "SELECT ".$str_Pobla_Campan."_ClasificacionGMI_b AS clasificacionBD, ".$str_Pobla_Campan."_CantidadIntentosGMI_b AS intentosBD, ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CantidadIntentosGMI_b AS intentosMT, ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoGesMaIm_b AS clasificacionMT FROM ".$BaseDatos.".".$str_Pobla_Campan." LEFT JOIN ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." ON ".$str_Pobla_Campan."_ConsInte__b = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b WHERE ".$str_Pobla_Campan."_ConsInte__b = ".$_POST['codigoMiembro'];

	        	$resSQLGesMasImp_t = $mysqli->query($strSQLGesMasImp_t);

	        	$objSQLGesMasImp_t = $resSQLGesMasImp_t->fetch_object();

		        $intIntentosGMI_BD_t = $objSQLGesMasImp_t->intentosBD;
		        if( $intIntentosGMI_BD_t == '' ||  $intIntentosGMI_BD_t == null || $intIntentosGMI_BD_t == 'NULL'){
		        	 $intIntentosGMI_BD_t=0;
		        }

		        $intIntentosGMI_MT_t = $objSQLGesMasImp_t->intentosMT;
		        if( $intIntentosGMI_MT_t == '' ||  $intIntentosGMI_MT_t == null || $intIntentosGMI_MT_t == 'NULL'){
		        	 $intIntentosGMI_MT_t=0;
		        }

	        	$strSQLGesMasImpBD_t = "";
	        	$strSQLGesMasImpMT_t = "";

	        	$intClasificacionBD_t = $objSQLGesMasImp_t->clasificacionBD;

	        	if (is_null($intClasificacionBD_t)) {
	        		$intClasificacionBD_t = 0;
	        	}

	        	$intClasificacionMT_t = $objSQLGesMasImp_t->clasificacionMT;

	        	if (is_null($intClasificacionMT_t)) {
	        		$intClasificacionMT_t = 0;
	        	}

	        	if ($conatcto >= $intClasificacionBD_t) {

	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_GesMasImp_b =  ".$gestionMIMP;
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_TipoReintentoGMI_b =  ".$TipoReintentoGMI;
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_ClasificacionGMI_b =  ".$contactoMasImp;
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_UsuarioGMI_b =  ".$UsuarioGMI;
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_CantidadIntentosGMI_b =  ".($intIntentosGMI_BD_t+1);
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_EstadoGMI_b =  ".$EstadoGMI;
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_PasoGMI_b =  ".$PasoGMI; 
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_ComentarioGMI_b =  '".$ComentarioGMI."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_CanalGMI_b =  '".$CanalGMI."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_SentidoGMI_b =  '".$SentidoGMI."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_LinkContenidoGMI_b =  '".$LinkContenidoGMI.$strCanalLink_t."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_DatoContactoGMI_b =  '".$DatoContactoGMI."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_DetalleCanalGMI_b =  '".$DetalleCanalGMI."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_FeGeMaIm__b =NOW()  "; 
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_FecHorAgeGMI_b =  ".$FecHorAgeGMI;

	        	}

	        	if ($conatcto >= $intClasificacionMT_t) {

	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_GesMasImp_b = ".$gestionMIMP;
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_TipoReintentoGMI_b = ".$TipoReintentoGMI;
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoGesMaIm_b = ".$contactoMasImp;
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_UsuarioGMI_b = ".$UsuarioGMI;
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_CantidadIntentosGMI_b = ".($intIntentosGMI_MT_t+1);
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_EstadoGMI_b  =".$EstadoGMI;
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_ComentarioGMI_b  = '".$ComentarioGMI."'";
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_CanalGMI_b = '".$CanalGMI."'"; 
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_SentidoGMI_b = '".$SentidoGMI."'";
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_LinkContenidoGMI_b = '".$LinkContenidoGMI.$strCanalLink_t."'";
	        		$strSQLGesMasImpMT_t .=",".$str_Pobla_Campan."_M".$int_Muest_Campan."_DatoContactoGMI_b  = '".$DatoContactoGMI."'";
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_DetalleCanalGMI_b  = '".$DetalleCanalGMI."'";
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FeGeMaIm__b = NOW()";	
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorAgeGMI_b = ".$FecHorAgeGMI;

	        	}


				// Se guarda esta informacion cuando se cierra la gestion de un registro que esta con clasificacion sin gestionar y la nueva clasificacion es no contactable o timeout

				if($conatcto < $intClasificacionMT_t && $intClasificacionBD_t == 3){
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_UsuarioGMI_b =  ".$UsuarioGMI;
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_LinkContenidoGMI_b =  '".$LinkContenidoGMI.$strCanalLink_t."'";
					$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_ComentarioGMI_b =  '".$ComentarioGMI."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_FeGeMaIm__b =NOW()  "; 
					$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_FecHorAgeGMI_b =  ".$FecHorAgeGMI;
					$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_CanalGMI_b =  '".$CanalGMI."'";
	        		$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_SentidoGMI_b =  '".$SentidoGMI."'";
					$strSQLGesMasImpBD_t .= ",".$str_Pobla_Campan."_PasoGMI_b =  ".$PasoGMI;


	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_UsuarioGMI_b = ".$UsuarioGMI;
					$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_ComentarioGMI_b  = '".$ComentarioGMI."'";
					$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_LinkContenidoGMI_b = '".$LinkContenidoGMI.$strCanalLink_t."'";
					$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_CanalGMI_b = '".$CanalGMI."'"; 
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_SentidoGMI_b = '".$SentidoGMI."'";
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FeGeMaIm__b = NOW()";	
	        		$strSQLGesMasImpMT_t .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorAgeGMI_b = ".$FecHorAgeGMI;


				}

		       /**
		       *actualizacion muestra
		       */
			   //FUNCIÓN PARA VALIDAR SI EL REGISTRO YA EXISTE EN LA MUESTRA
			   	validateRegisterMuestra($_POST['codigoMiembro'],$str_Pobla_Campan,$int_Muest_Campan);

		        $MuestraSql  =  "UPDATE ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." SET ";
		        $MuestraSql .= $str_Pobla_Campan."_M".$int_Muest_Campan."_UltiGest__b = ".$UltiGest;
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecUltGes_b = NOW()";
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_Estado____b = ".$reintento;
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorAge_b = ".$fechaAgenda;
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_ConUltGes_b = ".$conatcto;
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_UsuarioUG_b = ".$UsuarioUG;
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b + 1";
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_Comentari_b = '".$ComentarioUG."'";
		        $MuestraSql .=",".$str_Pobla_Campan."_M".$int_Muest_Campan."_DatoContactoUG_b  = '".$DatoContactoUG."'";
		        $MuestraSql .=",".$str_Pobla_Campan."_M".$int_Muest_Campan."_DetalleCanalUG_b  = '".$DetalleCanalUG."'";        
		        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_LinkContenidoUG_b = '".$LinkContenidoUG.$strCanalLink_t."'";
	            $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_CanalUG_b = '".$CanalUG."'"; 
	            $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_SentidoUG_b = '".$SentidoUG."'";  

                if($reintento != '2'){
                    if (isset($FecHorMinProGes__b) && is_numeric($FecHorMinProGes__b)) {

                        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorMinProGes__b = DATE_ADD(NOW(), INTERVAL ".$FecHorMinProGes__b." HOUR)";

                    }else{

                        $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorMinProGes__b = DATE_ADD(NOW(), INTERVAL 0 HOUR)";

                    }
                }else{
                    $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorMinProGes__b = NULL";    
                }



	            $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_EfeUltGes_b = ".informacionNula($_POST['Efectividad'])." ";
	        	$MuestraSql.=",".$str_Pobla_Campan."_M".$int_Muest_Campan."_EstadoUG_b  = ".$EstadoUG;
		        $MuestraSql .= $strSQLGesMasImpMT_t;
	            
	            if($rea_ConfD_Campan == '-1'){
	                if($reintento=='2'){
	                    $Agenda=$mysqli->query('select CAMPAN_Agenda_fija, CAMPAN_ConfDinam_b FROM DYALOGOCRM_SISTEMA.CAMPAN where CAMPAN_ConsInte__b='.$_GET["campana_crm"]);
	                    if($Agenda){
	                        $dato=$Agenda->fetch_object();
	                        if($dato->CAMPAN_Agenda_fija == '-1'){                     
	                            $MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_ConIntUsu_b = ".$UsuarioUG;
	                        }
	                    }                   
	                }
				//else{
				//$MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_ConIntUsu_b = NULL";    
				//}

					// if($strOrigen_t == 'BusquedaManual'){
					// 	$MuestraSql .= ",".$str_Pobla_Campan."_M".$int_Muest_Campan."_ConIntUsu_b = NULL";
					// }
	            }

		        $MuestraSql .= " WHERE ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b = ".$_POST['codigoMiembro'];

				// async_ExecuteQuery($MuestraSql)
				// ->then(function ($result) {
				// 	$arrReturn["MUESTRA"]="OK";
                //     $arrReturn["MUESTRA-QUERY"]=$result;
				// }, function ($error) {
				// 	global $mysqli;
				// 	global $BaseDatos_systema;
	        	// 	$arrReturn["MUESTRA"]="Error insertando la muestra => ".$error[1];
	        	// 	$queryMuestra="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
				// 	VALUES(\"".$error[0] ."\",\"".$error[1]."\",'MUESTRA')";
				// 	$mysqli->query($queryMuestra);
				// });

	        	if($mysqli->query($MuestraSql) === true){
                    $arrReturn["MUESTRA"]="OK";
                    $arrReturn["MUESTRA-QUERY"]=$MuestraSql;
	        	}else{
	        		$arrReturn["MUESTRA"]="Error insertando la muestra => ".$mysqli->error;
	        		$queryMuestra="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
					VALUES(\"".$MuestraSql ."\",\"".$mysqli->error."\",'MUESTRA')";
					$mysqli->query($queryMuestra);
	        	}

	           
				/**
				 * Insertamos la gestion en el journey
				 * 
				**/
				$tipificacionJ = (isset($_POST["tipificacion"])) ? $_POST["tipificacion"] : null;
				$userJ = (isset($_GET['usuario'])) ? $_GET['usuario'] : null;
				$idBdJ=isset($_POST['codigoMiembro']) ? $_POST['codigoMiembro'] : $_POST['codigoMiembro'];
				$intIdPoblacion = (isset($datoCampan['CAMPAN_ConsInte__GUION__Pob_b'])) ? $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'] : null;

				$dataJourney = ["duracion" => $duracion, "agente" => $userJ, "sentido" =>  $SentidoUG, "canal" => $strCanal_t, "datoContacto" => $DatoContactoUG, "tipificacion" => $tipificacionJ, "clasificacion" => $conatcto, "tipoReintento" => $reintento, "comentario" => $ComentarioUG, "linkContenido" => $LinkContenidoUG.$strCanalLink_t ];
				try {
					insertarJourney($idBdJ,$intIdPoblacion, $PasoUG, $dataJourney);
				} catch (\Throwable $th) {}

	        	/**
		       *actualizacion Bases de datos
		       */
		     
		        $LsqlUpdateCampos = "UPDATE ".$BaseDatos.".".$str_Pobla_Campan." SET ";
		        $LsqlUpdateCampos .= $str_Pobla_Campan."_UltiGest__b =  ".$UltiGest;	      
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_FecUltGes_b =  NOW()";
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_TipoReintentoUG_b =  ".$reintento;
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_FecHorAgeUG_b =  ".$fechaAgenda;
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_ClasificacionUG_b =  ".$conatcto;
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_UsuarioUG_b =  ".$UsuarioUG;
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_CantidadIntentos =  ".$str_Pobla_Campan."_CantidadIntentos + 1";
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_ComentarioUG_b =  '".$ComentarioUG."'";
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_PasoUG_b =  ".$PasoUG; 
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_DatoContactoUG_b =  '".$DatoContactoUG."'";
	            $LsqlUpdateCampos .= ",".$str_Pobla_Campan."_DetalleCanalUG_b =  '".$DetalleCanalUG."'";         	
	          	$LsqlUpdateCampos .= ",".$str_Pobla_Campan."_LinkContenidoUG_b =  '".$LinkContenidoUG.$strCanalLink_t."'";
	          	$LsqlUpdateCampos .= ",".$str_Pobla_Campan."_Canal_____b =  '".$strCanal_t."'";
	          	$LsqlUpdateCampos .= ",".$str_Pobla_Campan."_Sentido___b =  '".$SentidoUG."'";
	          	$LsqlUpdateCampos .= ",".$str_Pobla_Campan."_IdLlamada =  '".$valorId_Gestion_Cbx."'";
	        	$LsqlUpdateCampos .= ",".$str_Pobla_Campan."_EstadoUG_b =  ".$EstadoUG;
	        	$LsqlUpdateCampos .= ",".$str_Pobla_Campan."_EstadoGMI_b =  ".$EstadoGMI;
	        	$LsqlUpdateCampos .= $strSQLGesMasImpBD_t;
	            $LsqlUpdateCampos .= " WHERE ".$str_Pobla_Campan."_ConsInte__b = ".$_POST['codigoMiembro'];

				// async_ExecuteQuery($LsqlUpdateCampos)
				// ->then(function ($result) {
				// 	$arrReturn["BD"]="OK";
                //     $arrReturn["BD-QUERY"]=$result;
				// }, function ($error) {
				// 	global $mysqli;
				// 	global $BaseDatos_systema;
	        	// 	$arrReturn["BD"]="Error actualizando la poblacion => ".$error[1];
	        	// 	$queryBD="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
				// 	VALUES(\"".$error[0] ."\",\"".$error[1]."\",'BD')";
				// 	$mysqli->query($queryBD);
				// });

	        	if($mysqli->query($LsqlUpdateCampos) === true){
                    $arrReturn["BD"]="OK";
	                $arrReturn["BD-QUERY"]=$LsqlUpdateCampos;

	        	}else{
	        		$arrReturn["BD"]="Error actualizando la poblacion => ".$mysqli->error;
	        		$queryBD="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
					VALUES(\"".$LsqlUpdateCampos ."\",\"".$mysqli->error."\",'DB')";
					$mysqli->query($queryBD);
	        	}

		        //Rellenar los datos de condia         
	            
		        

		       	$SqlGESTIEM = "SELECT GESTIEM_TiempoUP FROM ".$BaseDatos_systema.".GESTIEM WHERE GESTIEM_Id_Gestion_cbx = '".$valorId_Gestion_Cbx_2."'";   
		        $re = $mysqli->query($SqlGESTIEM);
		        $fechaTimeUp = NULL;
		        while($k = $re->fetch_object()){
		        	$fechaTimeUp = $k->GESTIEM_TiempoUP;	
		        }

		        $CONDIA_IndiEfec__b = "'".$_POST['Efectividad']."'";

		        if (is_null($_POST['Efectividad']) || $_POST['Efectividad'] == null || $_POST['Efectividad'] == "" || $_POST['Efectividad'] == "NULL") {

		        	$CONDIA_IndiEfec__b = "'0'";
		        	
		        }

		        $CONDIA_TipNo_Efe_b = informacionNula($reintento);
		        $CONDIA_ConsInte__MONOEF_b = informacionNula($_POST['MonoEf']);
		        $CONDIA_TiemDura__b = "'".date('Y-m-d').' '.$duracion."'";
		        $CONDIA_Fecha_____b = (isset($fechaInsercionGestion)) ? "'".$fechaInsercionGestion."'" : "NOW()";
		        $CONDIA_ConsInte__CAMPAN_b = informacionNula($_GET["campana_crm"]);
		        $CONDIA_ConsInte__USUARI_b = $_GET['usuario'];
		        $CONDIA_ConsInte__GUION__Gui_b = informacionNula($int_Guion_Campan);
		        $CONDIA_ConsInte__GUION__Pob_b = informacionNula($int_Pobla_Camp_2);
		        $CONDIA_ConsInte__MUESTR_b = informacionNula($int_Muest_Campan);
		        $CONDIA_CodiMiem__b = informacionNula($_POST['codigoMiembro']);
		        $CONDIA_Observacio_b = informacionNula($_POST['textAreaComentarios']);
		        $CONDIA_FechaAgenda_b = $fechaAgenda;
		        $CONDIA_IdenLlam___b = informacionNula($valorId_Gestion_Cbx);
		        $CONDIA_TiemPrev__b = informacionNula("0");
		        $CONDIA_TiemUp____b = informacionNula($fechaTimeUp);
		        $CONDIA_Canal_b = informacionNula($valorSentido);
		        $CONDIA_Sentido___b = informacionNula($sentidoY);
		        $CONDIA_UniqueId_b = informacionNula($valorId_Gestion_Cbx);

		        $CondiaSql = "INSERT INTO ".$BaseDatos_systema.".CONDIA (
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
			        	CONDIA_FechaAgenda_b,
			        	CONDIA_IdenLlam___b,
			        	CONDIA_TiemPrev__b,
			        	CONDIA_TiemUp____b, 
			        	CONDIA_Canal_b,
			        	CONDIA_Sentido___b,
                        CONDIA_UniqueId_b) 
			        	VALUES (
		        		".$CONDIA_IndiEfec__b.", 
		        		".$CONDIA_TipNo_Efe_b.",
		        		".$CONDIA_ConsInte__MONOEF_b.",
		        		".$CONDIA_TiemDura__b.",
		        		".$CONDIA_Fecha_____b.",
		        		".$CONDIA_ConsInte__CAMPAN_b.",
		        		".$CONDIA_ConsInte__USUARI_b.",
		        		".$CONDIA_ConsInte__GUION__Gui_b.",
		        		".$CONDIA_ConsInte__GUION__Pob_b.",
		        		".$CONDIA_ConsInte__MUESTR_b.",
		        		".$CONDIA_CodiMiem__b.",
		        		".$CONDIA_Observacio_b." ,
		        		".$CONDIA_FechaAgenda_b.",
		        		".$CONDIA_IdenLlam___b.",
		        		".$CONDIA_TiemPrev__b.",
		        		".$CONDIA_TiemUp____b.",
		        		".$CONDIA_Canal_b.",
		        		".$CONDIA_Sentido___b.",
                        ".$CONDIA_UniqueId_b."
	        		)";

				// async_ExecuteQuery($CondiaSql)
				// ->then(function ($result) {
				// 	$arrReturn["CONDIA"]="OK";
				// 	$arrReturn["CONDIA-QUERY"]=trim(preg_replace('/\s+/', ' ', $result));
				// }, function ($error) {
				// 	global $mysqli;
				// 	global $BaseDatos_systema;
				// 	$arrReturn["BD"]="Error insertando Condia => ".$error[1];
				// 	$queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
				// 	VALUES(\"".$error[0] ."\",\"".$error[1]."\",'CONDIA')";
				// 	$mysqli->query($queryCondia);
				// });

		        if($mysqli->query($CondiaSql) === true){
                    $arrReturn["CONDIA"]="OK";
                    $arrReturn["CONDIA-QUERY"]=trim(preg_replace('/\s+/', ' ', $CondiaSql));
		        }else{
		        	$arrReturn["CONDIA"]="Error insertando Condia => ".$mysqli->error;
	        		$queryCondia="INSERT INTO ".$BaseDatos_systema.".LOGGEST (LOGGEST_SQL_b,LOGGEST_Error_b,LOGGEST_Comentario_b)
					VALUES(\"".$CondiaSql."\",\"".$mysqli->error."\",'CONDIA')";
					$mysqli->query($queryCondia);
		        }

		        if( !(isset($_POST['llamarApi'])) ){
		        	 if(isset($_GET['token']) && isset($_GET['id_gestion_cbx']) ){
		        	
			        	$data =  array();
			        	$conatacto = 0;
			        	if(isset($_POST['ContactoMonoEf']) && $_POST['ContactoMonoEf'] != '' ){
			        		$conatacto = $_POST['ContactoMonoEf'];
			        	}

			        	$consinte = -1;
			        	if(isset($_GET['consinte']) && $_GET['consinte'] != ''){
			        		$consinte = $_GET['consinte'];
			        	}

			        	if(isset($_GET['usuario']) && $_GET['usuario'] != '')
			        	{
			        		$consinte = $_GET['usuario'];
			        	}

		        		if(!isset($_POST['TxtFechaReintento'])){
			        		$data = array(	
			        					"strToken_t" => $_GET['token'], 
										"strIdGestion_t" => $valorId_Gestion_Cbx_2, 
										"intTipoReintento_t" => $reintento,
										"intConsInte_t"	=> $_GET['ConsInteRegresado'],
										"strFechaHoraAgenda_t" => null,
										"booForzarCierre_t" => true,
										"intMonoefEfectiva_t" => $_POST['Efectividad'],
										"intConsInteTipificacion_t" => $_POST['MonoEf'],
										"boolFinalizacionDesdeBlend_t" => false,
										"intMonoefContacto_t" => $conatacto
									); 
						}else{
							
							$data = array(	
									"strToken_t" => $_GET['token'], 
									"strIdGestion_t" => $valorId_Gestion_Cbx_2, 
									"intTipoReintento_t" => $reintento,
									"strFechaHoraAgenda_t" => $_POST['TxtFechaReintento']." ".str_replace(" ", "",$_POST['TxtHoraReintento']),
									"intConsInte_t"	=> $_GET['ConsInteRegresado'],
									"booForzarCierre_t" => true,
									"intMonoefEfectiva_t" => $_POST['Efectividad'],
									"intConsInteTipificacion_t" => $_POST['MonoEf'],
									"boolFinalizacionDesdeBlend_t" => false,
									"intMonoefContacto_t" => $conatacto
							); 
						}

						$oper=isset($_POST['oper']) ? $_POST['oper'] : '';
			        	
						
                        if(isset($_GET["cerrarViaPost"])){
							if($oper == 'add'){
								if(gestionDuplicada($int_Guion_Campan,$valorId_Gestion_Cbx,$_GET['usuario'],$_POST['codigoMiembro'],$_GET['ConsInteRegresado'])){
									$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión del registro action: {$oper}, se depuro')");
								}else{
									$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión del registro action: {$oper}, quedaron 2 gestiones en el formulario')");
								}
							}else{
								$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión del registro action: {$oper}')");
							}
                            $arrReturn['dataGestion']=$data;
                        }else{
							if($oper == 'add'){
								if(gestionDuplicada($int_Guion_Campan,$valorId_Gestion_Cbx,$_GET['usuario'],$_POST['codigoMiembro'],$_GET['ConsInteRegresado'])){
									$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión del registro action: {$oper}, se depuro')");
								}else{
									$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión del registro action: {$oper}, quedaron 2 gestiones en el formulario')");
								}
							}else{
								$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión del registro action: {$oper}')");
							}
                            $arrReturn['dataGestion']=$data;
                            $ch = curl_init($IP_CONFIGURADA.'gestion/finalizar');

                            $data_string = json_encode($data);  

                            echo $data_string;  
                            //especificamos el POST (tambien podemos hacer peticiones enviando datos por GET
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 

                            //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                                'Content-Type: application/json',                                                                                
                                'Content-Length: ' . strlen($data_string))                                                                      
                            ); 
                            //recogemos la respuesta
                            $respuesta = curl_exec ($ch);
                            //o el error, por si falla
                            $error = curl_error($ch);
                            //y finalmente cerramos curl
                            echo "Respuesta =>  ". $respuesta;
                            echo "<br/>Error => ".$error;

                            curl_close ($ch);
                        }
					}
		        }else{
					$oper=isset($_POST['oper']) ? $_POST['oper'] : '';
					if($oper== 'add'){
						if(gestionDuplicada($int_Guion_Campan,$valorId_Gestion_Cbx,$_GET['usuario'],$_POST['codigoMiembro'],$_GET['ConsInteRegresado'])){
							$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión de la llamada, el agente va a iniciar otra llamada para el mismo código miembro action: {$oper}, se depuro')");
						}else{
							$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión de la llamada, el agente va a iniciar otra llamada para el mismo código miembro action: {$oper}, quedaron 2 gestiones')");
	
						}
					}else{
						$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$_GET['ConsInteRegresado']},'$strOrigen_t','{$valorId_Gestion_Cbx}',{$_GET["campana_crm"]},{$int_Guion_Campan},{$_POST['codigoMiembro']},{$_GET['usuario']},'Se finaliza la gestión de la llamada, el agente va a iniciar otra llamada para el mismo código miembro action: {$oper}')");
					}
				}
                echo json_encode($arrReturn);
				// BSV - Aqui deberia llamar la funcion de insertar en la muestra segun la flecha 
				// NOTA: SI EXPLOTA ES PORQUE FALTO HACER MAS PRUEBAS 
				if(isset($_GET['campana_crm']) && $_GET['campana_crm'] != 0){
					$psql = "SELECT ESTPAS_ConsInte__b as pasoId FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_GET['campana_crm']. " LIMIT 1";
					$pasoq = $mysqli->query($psql);
					
					if($pasoq->num_rows > 0){
						$data = $pasoq->fetch_array();

						try {
							DispararProceso($data['pasoId'], $_POST['codigoMiembro']);
						} catch (\Throwable $th) {
							//throw $th;
						}
						//Ejecuto el proceso
					}

				}                
			}
	        
	      	//NBG*2020-05* Insertar registros que existen en la bd pero no lo muestra        
	        if($_GET['action'] == 'ADD_MUESTRA'){
				$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConfDinam_b FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$_GET["campana_crm"]}";			

				//echo $Lsql_Campan;

		        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
		        $datoCampan = $res_Lsql_Campan->fetch_array();
		        $str_Pobla_Campan = "G{$datoCampan['CAMPAN_ConsInte__GUION__Pob_b']}";
		        $int_Muest_Campan = "{$str_Pobla_Campan}_M{$datoCampan['CAMPAN_ConsInte__MUESTR_b']}";
	            $rea_ConfD_Campan = $datoCampan['CAMPAN_ConfDinam_b'];
	            
	            $sqlMuestra="SELECT * FROM {$BaseDatos}.{$int_Muest_Campan} WHERE {$int_Muest_Campan}_CoInMiPo__b={$_POST['id']}";
	            $sqlMuestra=$mysqli->query($sqlMuestra);
	            if($sqlMuestra && $sqlMuestra->num_rows === 0){
					$sqlMuestra="INSERT INTO {$BaseDatos}.{$int_Muest_Campan} ({$int_Muest_Campan}_CoInMiPo__b , {$int_Muest_Campan}_NumeInte__b, {$int_Muest_Campan}_Estado____b) VALUES ({$_POST['id']}, 0, 0);";
					if($mysqli->query($sqlMuestra) !== true){
						echo "error muestra = > ".$mysqli->error;
					}
	            }
	            
	        }

			if($_GET['action'] == 'UP_MUESTRA'){
				$Lsql_Campan = "SELECT CAMPAN_Nombre____b,CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConfDinam_b, ESTPAS_Tipo______b FROM {$BaseDatos_systema}.CAMPAN LEFT JOIN {$BaseDatos_systema}.ESTPAS ON CAMPAN_ConsInte__b=ESTPAS_ConsInte__CAMPAN_b WHERE CAMPAN_ConsInte__b = {$_GET["campana_crm"]}";			

				//echo $Lsql_Campan;
		        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
		        $datoCampan = $res_Lsql_Campan->fetch_array();
		        $str_Pobla_Campan = "G{$datoCampan['CAMPAN_ConsInte__GUION__Pob_b']}";
		        $int_Muest_Campan = "{$str_Pobla_Campan}_M{$datoCampan['CAMPAN_ConsInte__MUESTR_b']}";
	            $rea_ConfD_Campan = $datoCampan['CAMPAN_ConfDinam_b'];
				$tipo			  = $datoCampan['ESTPAS_Tipo______b'];

				if($tipo == '6'){
					if($rea_ConfD_Campan == '-1'){
						$sqlValidate=$mysqli->query("SELECT {$int_Muest_Campan}_ConIntUsu_b AS usuario,{$int_Muest_Campan}_Estado____b AS estado FROM {$BaseDatos}.{$int_Muest_Campan} WHERE {$int_Muest_Campan}_CoInMiPo__b={$_POST['id']}");
						if($sqlValidate && $sqlValidate->num_rows == 1){
							$sqlValidate=$sqlValidate->fetch_object();
							if($sqlValidate->usuario !=null && $sqlValidate->usuario != -10 && $sqlValidate->estado != 2){
								if($sqlValidate->usuario==$_GET["agente"]){
									echo '1';
								}else{
									echo 'El registro se encuentra siendo gestionado por otro agente';
								}
							}else{
								$sql=$mysqli->query("UPDATE {$BaseDatos}.{$int_Muest_Campan} SET {$int_Muest_Campan}_ConIntUsu_b={$_GET['agente']} WHERE {$int_Muest_Campan}_CoInMiPo__b={$_POST['id']}");
								echo '1';
							}
						}else{
							echo '1';
						}				
					}else{
						echo '1';
					}
				}else{
					echo '1';
				}
			}
    	}
        
        if (isset($_POST['insertaRegistro'])){
			$id_comunicacion=isset($_POST['id_comunicacion']) ? explode('_', $_POST['id_comunicacion'])[1] :'';
            if(isset($_POST['formulario']) && is_numeric($_POST['formulario']) && isset($_POST['agente']) && is_numeric($_POST['agente']) && $id_comunicacion != ''){
                $tipificacion='';
                $valtipifiaccion='';
                $clasificacion=1;
                $canal=isset($_POST['canal']) ? $_POST['canal'] :'Sin canal';
                $origen=isset($_POST['origen']) ? $_POST['origen'] :'Sin origen';
                $sentido=isset($_POST['sentido']) ? $_POST['sentido'] :'NoSentido';
                $miembro=isset($_POST['miembro']) && $_POST['miembro']  !='' ? $_POST['miembro'] :'NULL';
				$fechaBD ="NULL";

                $sql=$mysqli->query("SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b,SECCIO_TipoSecc__b FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b ={$_POST['formulario']} AND PREGUN_Texto_____b='Tipificación' AND SECCIO_TipoSecc__b=3");
                if($sql && $sql->num_rows==1){
                    $sql=$sql->fetch_object();
                    $tipificacion="G{$_POST['formulario']}_C{$sql->PREGUN_ConsInte__b}";
                    $valtipifiaccion="-22";
                }
                $contacto= isset($_POST['datoContacto']) ? $_POST['datoContacto'] : false;
                
				$strContacto="G{$_POST['formulario']}_DatoContacto";
                if($contacto){
                    $valContacto=$contacto;
                }else{
                    $valContacto='';    
                }
				$pasoId=isset($_POST['campana_crm']) ? $_POST['campana_crm'] : false;

				$strPaso='';
				$intPaso='';
				if($pasoId){
					$sqlpasoId="SELECT ESTPAS_ConsInte__b FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b = {$pasoId} AND (ESTPAS_Tipo______b = 1 OR ESTPAS_Tipo______b = 6)";            
					if(($query = $mysqli->query($sqlpasoId)) == TRUE ){
					   $array = $query->fetch_array();  
						if($array["ESTPAS_ConsInte__b"] != ''){
							$strPaso=",G{$_POST['formulario']}_Paso";
							$intPaso=",".$array["ESTPAS_ConsInte__b"];     
					   	}
				   }
				}

				// Se obtiene la fecha de insercion del miembro de la poblacion

				if($miembro != 'NULL' ){
					$strGPoblacionsql = $mysqli->query("SELECT CAMPAN_ConsInte__GUION__Pob_b AS poblacion FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$pasoId} LIMIT 1");
					if($strGPoblacionsql){
						$strGPoblacionsql = $strGPoblacionsql->fetch_object();
						$strFechaPoblacion = $mysqli->query("SELECT G{$strGPoblacionsql->poblacion}_FechaInsercion AS fechaBD FROM {$BaseDatos}.G{$strGPoblacionsql->poblacion} WHERE G{$strGPoblacionsql->poblacion}_ConsInte__b = {$miembro}");
						if($strFechaPoblacion){
							//validar fecha bd
							$strFechaPoblacion = $strFechaPoblacion->fetch_object();
							if(!is_null($strFechaPoblacion->fechaBD)){
								$fechaBD = "'".$strFechaPoblacion->fechaBD."'";
							}
						}
					}
				

				}
				//SI EL CANAL 

				//VALIDAR SI EL ID DE COMUNICACIÓN YA EXISTE
				$sqlValidate=$mysqli->query("SELECT G{$_POST['formulario']}_ConsInte__b AS id FROM {$BaseDatos}.G{$_POST['formulario']} WHERE G{$_POST['formulario']}_IdLlamada='{$id_comunicacion}' AND {$tipificacion}=-22");
				if($sqlValidate){
					if($sqlValidate->num_rows==0){
						$sql=$mysqli->query("insert into {$BaseDatos}.G{$_POST['formulario']} (G{$_POST['formulario']}_ConsInte__b,G{$_POST['formulario']}_Usuario,G{$_POST['formulario']}_FechaInsercion,{$strContacto},{$tipificacion},G{$_POST['formulario']}_Canal_____b,G{$_POST['formulario']}_Origen_b,G{$_POST['formulario']}_Sentido___b,G{$_POST['formulario']}_Clasificacion,G{$_POST['formulario']}_IdLlamada{$strPaso},G{$_POST['formulario']}_DetalleCanal,G{$_POST['formulario']}_CodigoMiembro, G{$_POST['formulario']}_FechaInsercionBD_b ) values (null,{$_POST['agente']},now(),'{$valContacto}','{$valtipifiaccion}','{$canal}','{$origen}','{$sentido}','{$clasificacion}','{$id_comunicacion}'{$intPaso},{$_POST['agente']},{$miembro}, {$fechaBD})");
						if($sql){
							 $idInsertado=$mysqli->insert_id;
							//INSERTAMOS LA ACCION EN EL LOG DEL STORAGE
							if(!$pasoId){
								$pasoId="NULL";
							}
							$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),{$idInsertado},'{$origen}','{$id_comunicacion}',{$pasoId},{$_POST['formulario']},{$miembro},{$_POST['agente']},'Se inserta una gestión vacia al abrir el formulario por primera vez')");
							echo json_encode(array('estado'=>'ok','mensaje'=>$idInsertado));
						}else{
							//INSERTAR EN EL LOG DEL STORAGE
							$error=$mysqli->error;
							$sql="insert into {$BaseDatos}.G{$_POST['formulario']} (G{$_POST['formulario']}_ConsInte__b,G{$_POST['formulario']}_Usuario,G{$_POST['formulario']}_FechaInsercion,{$strContacto},{$tipificacion},G{$_POST['formulario']}_Canal_____b,G{$_POST['formulario']}_Origen_b,G{$_POST['formulario']}_Sentido___b,G{$_POST['formulario']}_Clasificacion,G{$_POST['formulario']}_IdLlamada{$strPaso},G{$_POST['formulario']}_DetalleCanal,G{$_POST['formulario']}_CodigoMiembro) values (null,{$_POST['agente']},now(),'{$valContacto}','{$valtipifiaccion}','{$canal}','{$origen}','{$sentido}','{$clasificacion}','{$id_comunicacion}'{$intPaso},{$_POST['agente']},{$miembro}, {$fechaBD})";

							$log=$mysqli->query("insert into DYALOGOCRM_SISTEMA.LOGGEST values(null,now(),\"{$sql}\",\"{$error}\",'Insercion desde el storage')");

							$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),NULL,'{$origen}','{$id_comunicacion}',{$pasoId},{$_POST['formulario']},{$miembro},{$_POST['agente']},'Fallo al insertar la gestión vacia')");
							echo json_encode(array('estado'=>'error','mensaje'=>'error al insertar la gestión'));
						}
					}else{
						//INSERTAR EN EL LOG DEL STORAGE
						$sqlValidate=$sqlValidate->fetch_object();
						$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),$sqlValidate->id,'{$origen}','{$id_comunicacion}',{$pasoId},{$_POST['formulario']},{$miembro},{$_POST['agente']},'Ya se había insertado la gestión vacia anteriormente')");
						echo json_encode(array('estado'=>'ok','mensaje'=>$sqlValidate->id));
					}
				}else{
					//INSERTAR EN EL LOG DEL STORAGE
					$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),NULL,'{$origen}','{$id_comunicacion}',{$pasoId},{$_POST['formulario']},{$miembro},{$_POST['agente']},'Fallo al insertar la gestión vacia porque no se detecto el id de la comunicación')");
					echo json_encode(array('estado'=>'error','mensaje'=>'parametros incompletos, falta el id de comunicación'));
				}

            }else{
				//INSERTAR EN EL LOG DEL STORAGE
				$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),NULL,NULL,'{$id_comunicacion}',NULL,NULL,NULL,NULL,'Fallo al insertar la gestión vacia porque no se detecto el formulario o el agente')");
				echo json_encode(array('estado'=>'error','mensaje'=>'parametros incompletos'));
			}
        }
        
		if(isset($_POST["actualizarComunicacionStorage"])){
			$actual=isset($_POST["gestionActual"]) ? explode('telefonia_', $_POST['gestionActual'])[1] : false;
			$nueva=isset($_POST["gestionNueva"]) ? explode('telefonia_', $_POST["gestionNueva"])[1] : false;
			$formulario=isset($_POST["formulario"]) && is_numeric($_POST["formulario"]) ? $_POST["formulario"] : false;
			$insertado=isset($_POST["insertado"]) ? $_POST["insertado"] : "NULL";
			$origen=isset($_POST["origen"]) ? $_POST["origen"] : "";
			$campana=isset($_POST["campan"]) ? $_POST["campan"] : "NULL";
			$miembro=isset($_POST["miembro"]) ? $_POST["miembro"] : "NULL";
			$usuario=isset($_POST["usuario"]) ? $_POST["usuario"] : "NULL";
			if($actual && $nueva && $formulario){
				// VALIDAR QUE EL FORMULARIO SEA != -1
				if($formulario == -1 && is_numeric($campana)){
					$formulario=getFormulario($campana);
					if(!$formulario){
						$formulario=-1;
					}
				}
				
				$http = "http://".$_SERVER["HTTP_HOST"];
				if (isset($_SERVER['HTTPS'])) {
					$http = "https://".$_SERVER["HTTP_HOST"];
				}
				
				$link="{$http}:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid={$nueva}&uid2={$nueva}&canal=telefonia";
				//ACTUALIZAMOS LA COMUNICACIÓN DEL FORMULARIO
				$sql=$mysqli->query("UPDATE {$BaseDatos}.G{$formulario} SET G{$formulario}_IdLlamada='{$nueva}', G{$formulario}_Canal_____b='telefonia', G{$formulario}_LinkContenido='{$link}', G{$formulario}_CodigoMiembro={$miembro} WHERE G{$formulario}_ConsInte__b={$insertado}");
				if($sql){
					try {
						unset($_SESSION[$_POST["gestionActual"]]);
						$_SESSION[$_POST["gestionNueva"]]=["idGestion" =>$_POST["gestionNueva"], "miembro"=>$miembro];
					} catch (\Throwable $th) {}
					//INSERTAR EN EL LOG DEL STORAGE
					$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES (NULL,NOW(),{$insertado},'{$origen}','{$nueva}',{$campana},{$formulario},{$miembro},{$usuario},'Se realizo una llamada, se cambia el id de comunicación de: {$actual} por: {$nueva}')");
					echo json_encode(array("estado"=>'ok'));
				}else{
					//INSERTAR EN EL LOG DEL STORAGE
					$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES (NULL,NOW(),{$insertado},'{$origen}','{$nueva}',{$campana},{$formulario},{$miembro},{$usuario},'Se realizo una llamada, pero fallo al actualizar el id de comunicación de: {$actual} por: {$nueva}')");
					echo json_encode(array("estado"=>'error',"mensaje"=>"No se actualizo el id de comunicación - 2"));
				}
			}else{
				//INSERTAR EN EL LOG DEL STORAGE
				$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES (NULL,NOW(),{$insertado},'{$origen}',NULL,{$campana},NULL,{$miembro},{$usuario},'Se realizo una llamada, pero no se recibieron todos los parametros para actualizar el id de comunicación')");
				echo json_encode(array("estado"=>'error',"mensaje"=>"No se actualizo el id de comunicación - 1"));
			}
		}

		if(isset($_POST["validarRegistro"])){
			$formulario=isset($_POST["formulario"]) ? $_POST["formulario"] : false;
			$insertado=isset($_POST["id"]) ? $_POST["id"] : "NULL";
			$origen=isset($_POST["origen"]) ? $_POST["origen"] : "";
			$campana=isset($_POST["campana"]) ? $_POST["campana"] : "NULL";
			$miembro=isset($_POST["miembro"]) ? $_POST["miembro"] : "NULL";
			$usuario=isset($_POST["usuario"]) ? $_POST["usuario"] : "NULL";
			$id=false;
			if(isset($_POST["id_gestion"])){
				$arrCanal=explode('_', $_POST["id_gestion"]);
				$id=$arrCanal[1];

				if(count($arrCanal) > 2){
					$id.="_".$arrCanal[2];
				}
			}

			if($id && $formulario && $insertado){
				$tipificacion="G{$formulario}_C".getCampoTipificacion($formulario);

				//VALIDAMOS CONTRA LA BD QUE REGISTRO ES EL QUE VAMOS A GESTIONAR
				$sql=$mysqli->query("SELECT G{$formulario}_ConsInte__b AS id FROM {$BaseDatos}.G{$formulario} WHERE G{$formulario}_IdLlamada='{$id}' AND {$tipificacion}=-22");
				if($sql && $sql->num_rows==1){
					$sql=$sql->fetch_object();
					//INSERTO EN EL LOG DEL STORAGE
					$sqlStorage=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES (NULL,NOW(),{$insertado},'{$origen}','{$id}',{$campana},{$formulario},{$miembro},{$usuario},'Se recargo la comunicación, el id insertado coincide con el del storage')");
					echo json_encode(array("estado"=>'ok',"mensaje"=>$sql->id));
				}else{
					//INSERTAR EN EL LOG DEL STORAGE
					$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES (NULL,NOW(),{$insertado},'{$origen}','{$id}',{$campana},{$formulario},{$miembro},{$usuario},'Se recargo la comunicación, pero no se encontraron registros o se encontraron muchos con el mismo id de comunicación')");
					echo json_encode(array("estado"=>"error","mensaje"=>"No se encontraron registros o se encontraron muchos con el mismo id de comunicación"));
				}
			}else{
				//INSERTAR EN EL LOG DEL STORAGE
				$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES (NULL,NOW(),{$insertado},'{$origen}','{$id}',{$campana},{$formulario},{$miembro},{$usuario},'Se recargo la comunicación, pero no se envio o el id de comunicación o el formulario')");
				echo json_encode(array("estado"=>"error","mensaje"=>"No se identifico el id de comunicación o el formulario"));
			}
		}

		if(isset($_POST['actualizarMiembro'])){
			$id=isset($_POST['id']) ? $_POST['id'] : false;
			$origen=isset($_POST['origen']) ? $_POST['origen'] : 'sin origen';
			$campana=isset($_POST['campana']) ? $_POST['campana'] : 0;
			$formulario=isset($_POST['formulario']) ? $_POST['formulario'] : false;
			$miembro=isset($_POST['miembro']) ? $_POST['miembro'] : false;
			$usuario=isset($_POST['usuario']) ? $_POST['usuario'] : 0;
			$id_gestion='sin comunicacion';
			$fechaBD = "NULL";
			if(isset($_POST["id_gestion"])){
				$arrCanal=explode('_', $_POST["id_gestion"]);
				$id_gestion=$arrCanal[1];

				if(count($arrCanal) > 2){
					$id_gestion.="_".$arrCanal[2];
				}
			}
			
			if($formulario && $id && $miembro){

				//Primero se consulta el G de la poblacion

				$strGPoblacionsql = $mysqli->query("SELECT CAMPAN_ConsInte__GUION__Pob_b AS poblacion FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$campana} LIMIT 1");
				if($strGPoblacionsql){
					$strGPoblacionsql = $strGPoblacionsql->fetch_object();


					// Se obtiene la fecha de insercion del miembro de la poblacion
					$strFechaPoblacion = $mysqli->query("SELECT G{$strGPoblacionsql->poblacion}_FechaInsercion AS fechaBD FROM {$BaseDatos}.G{$strGPoblacionsql->poblacion} WHERE G{$strGPoblacionsql->poblacion}_ConsInte__b = {$miembro}");
					
					if($strFechaPoblacion){
						$strFechaPoblacion = $strFechaPoblacion->fetch_object();
						$fechaBD = $strFechaPoblacion->fechaBD != null ? "'{$strFechaPoblacion->fechaBD}'" : "NULL";
					}
					// Se actualiza la gestion
					$sql=$mysqli->query("UPDATE {$BaseDatos}.G{$formulario} SET G{$formulario}_CodigoMiembro={$miembro},  G{$formulario}_FechaInsercionBD_b={$fechaBD} WHERE G{$formulario}_ConsInte__b={$id}");
					if($sql){
						//INSERTAR EN EL LOG DEL STORAGE
						$_SESSION[$_POST["id_gestion"]]=["idGestion" =>$_POST["id_gestion"], "miembro"=>$miembro];
						$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),$id,'{$origen}','{$id_gestion}',{$campana},{$formulario},{$miembro},{$usuario},'Se realizó una busqueda manual y se abre el formulario de gestiones')");
						echo json_encode(array('estado'=>'ok'));
					}else{
						$sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.LOGSTORAGE VALUES(NULL,NOW(),$id,'{$origen}','{$id_gestion}',{$campana},{$formulario},{$miembro},{$usuario},'Se realizó una busqueda manual y se abre el formulario de gestiones, fallo al actualizar el codigo miembro - {$mysqli->error}')");
						echo json_encode(array('estado'=>'error','mensaje'=>'error al actualizar el codigo miembro'));
					}
				}
			}else{
				echo json_encode(array('estado'=>'error','mensaje'=>'parametros incompletos')); 
			}
		}

		if(isset($_POST['llamadaTrasferida'])){
			(String) $id=isset($_POST['id']) && count(explode('telefonia_',$_POST['id'])) > 0 ? explode('telefonia_',$_POST['id'])[1] : '';
			(Int) $campana=isset($_POST["campan"]) && is_numeric($_POST["campan"]) ? $_POST["campan"] : 0;
			(String) $estado='error';
			if($id !='' && $campana > 0){
				$formulario=getFormulario($campana);
				if($formulario){
					$campo=getCampoTipificacion($formulario);
					if($campo){
						//ACTUALIZAR LA TIPIFICACIÓN DE LA GESTIÓN PARA QUE QUEDE COMO LLAMADA TRANSFERIDA
						$sql=$mysqli->query("UPDATE {$BaseDatos}.G{$formulario} SET G{$formulario}_C{$campo}=-25 WHERE G{$formulario}_IdLlamada='{$id}'");
						if($mysqli->affected_rows == 1){
							$estado='ok';
						}
					}
				}
			}
			echo json_encode(array('estado'=>$estado));
		}
	}