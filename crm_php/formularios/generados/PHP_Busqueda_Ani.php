<?php
	ini_set('display_errors', 'On');
	ini_set('display_errors', 1);
	include(__DIR__."/../../conexion.php");
	date_default_timezone_set('America/Bogota');
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    	if($_GET['action'] == "GET_DATOS"){

    		$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_CamAd1Cbx_b , CAMPAN_CamAd2Cbx_b , CAMPAN_CamAd3Cbx_b, CAMPAN_CamAd4Cbx_b , CAMPAN_CamAd5Cbx_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
	        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
	        $datoCampan = $res_Lsql_Campan->fetch_array();
	        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
	        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
			$strMuestra=$str_Pobla_Campan."_M".$int_Muest_Campan;
            $objCamposivr = new stdClass();

	        $CAMPAN_CamAd1Cbx_b = null;
			$CAMPAN_CamAd2Cbx_b = null;
			$CAMPAN_CamAd3Cbx_b = null;
			$CAMPAN_CamAd4Cbx_b = null;
			$CAMPAN_CamAd5Cbx_b = null;

	        $CAMPAN_CamAd1Cbx_b = $datoCampan['CAMPAN_CamAd1Cbx_b'];
			$CAMPAN_CamAd2Cbx_b = $datoCampan['CAMPAN_CamAd2Cbx_b'];
			$CAMPAN_CamAd3Cbx_b = $datoCampan['CAMPAN_CamAd3Cbx_b'];
			$CAMPAN_CamAd4Cbx_b = $datoCampan['CAMPAN_CamAd4Cbx_b'];
			$CAMPAN_CamAd5Cbx_b = $datoCampan['CAMPAN_CamAd5Cbx_b'];

			//BUSCAMOS LOS CAMPOS CONFIGURADOS PARA LA BUSQUEDA
			$strCamposBD="A.*";
			$sqlCamposBD=$mysqli->query("SELECT PREGUN_ConsInte__b as id FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$datoCampan['CAMPAN_ConsInte__GUION__Pob_b']} AND PREGUN_IndiBusc__b != 0");
			if($sqlCamposBD && $sqlCamposBD->num_rows > 0){
				$strCamposBD="A.{$str_Pobla_Campan}_ConsInte__b";
				while($row = $sqlCamposBD->fetch_object()){
					$strCamposBD .=", A.{$str_Pobla_Campan}_C{$row->id}";
				}
			}

			$Lsql = " SELECT {$strCamposBD}, {$strMuestra}_CoInMiPo__b AS id_muestra, {$strMuestra}_ConIntUsu_b AS agente FROM {$BaseDatos}.{$str_Pobla_Campan} A LEFT JOIN {$BaseDatos}.{$strMuestra} ON A.{$str_Pobla_Campan}_ConsInte__b = {$strMuestra}_CoInMiPo__b";
			$Where = " WHERE ";
			
			if(!isset($_POST['consinte'])){
				$usados = 0;
				
				if(isset($_POST['dato_adicional_1'])){
					if($CAMPAN_CamAd1Cbx_b != '' && !is_null($CAMPAN_CamAd1Cbx_b)){
						/* si tenia definido los campos de busqueda  */
						$and = '';
						if($usados != 0){
							$and = ' OR ';
						}
						
						$strDatoBus="dato_adicional_1";
						if(isset($_POST['buscarAni']) && isset($_POST['ani'])){
							$strDatoBus="ani";
						}
						$Where .= $and." ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd1Cbx_b." = '". $_POST[$strDatoBus]."'";
						$usados = 1;
                        $campo=$str_Pobla_Campan.'_C'.$CAMPAN_CamAd1Cbx_b;
                        $objCamposivr->$campo =$_POST['dato_adicional_1'];
                        
					}
				}

				if(isset($_POST['dato_adicional_2'])){
					if($CAMPAN_CamAd2Cbx_b != '' && !is_null($CAMPAN_CamAd2Cbx_b)){
						/* si tenia definido los campos de busqueda  */
						$and = '';
						if($usados != 0){
							$and = ' OR ';
						}

						$strDatoBus="dato_adicional_2";
						if(isset($_POST['buscarAni']) && isset($_POST['ani'])){
							$strDatoBus="ani";
						}

						$Where .= $and." ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd2Cbx_b." = '". $_POST[$strDatoBus]."'";
						$usados = 1;
                        $campo=$str_Pobla_Campan.'_C'.$CAMPAN_CamAd2Cbx_b;
                        $objCamposivr->$campo =$_POST['dato_adicional_2'];
					}
				}

				if(isset($_POST['dato_adicional_3'])){
					if($CAMPAN_CamAd3Cbx_b != '' && !is_null($CAMPAN_CamAd3Cbx_b)){
						/* si tenia definido los campos de busqueda  */
						$and = '';
						if($usados != 0){
							$and = ' OR ';
						}

						$strDatoBus="dato_adicional_3";
						if(isset($_POST['buscarAni']) && isset($_POST['ani'])){
							$strDatoBus="ani";
						}

						$Where .= $and." ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd3Cbx_b." = '". $_POST[$strDatoBus]."'";
						$usados = 1;
                        $campo=$str_Pobla_Campan.'_C'.$CAMPAN_CamAd3Cbx_b;
                        $objCamposivr->$campo =$_POST['dato_adicional_3'];
					}
				}

				if(isset($_POST['dato_adicional_4'])){
					if($CAMPAN_CamAd4Cbx_b != '' && !is_null($CAMPAN_CamAd4Cbx_b)){
						/* si tenia definido los campos de busqueda  */
						$and = '';
						if($usados != 0){
							$and = ' OR ';
						}

						$strDatoBus="dato_adicional_4";
						if(isset($_POST['buscarAni']) && isset($_POST['ani'])){
							$strDatoBus="ani";
						}

						$Where .= $and." ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd4Cbx_b." = '". $_POST[$strDatoBus]."'";
						$usados = 1;
                        $campo=$str_Pobla_Campan.'_C'.$CAMPAN_CamAd4Cbx_b;
                        $objCamposivr->$campo =$_POST['dato_adicional_4'];
					}
				}

				if(isset($_POST['dato_adicional_5'])){
					if($CAMPAN_CamAd5Cbx_b != '' && !is_null($CAMPAN_CamAd5Cbx_b)){
						/* si tenia definido los campos de busqueda  */
						$and = '';
						if($usados != 0){
							$and = ' OR ';
						}

						$strDatoBus="dato_adicional_5";
						if(isset($_POST['buscarAni']) && isset($_POST['ani'])){
							$strDatoBus="ani";
						}

						$Where .= $and." ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd5Cbx_b." = '". $_POST[$strDatoBus]."'";
						$usados = 1;
                        $campo=$str_Pobla_Campan.'_C'.$CAMPAN_CamAd5Cbx_b;
                        $objCamposivr->$campo =$_POST['dato_adicional_5'];
					}
				}

				if(isset($_POST['canal']) && isset($_POST['ani'])){
					
					if ($_POST['canal'] == "chat") {

						$intIdCampo_t = campoBusquedaChat($_GET["campana_crm"],'chat');

						if (!is_null($intIdCampo_t) && $intIdCampo_t != 0 && $intIdCampo_t !== '') {
							
							$and = '';
							if($usados != 0){
								$and = ' OR ';
							}

							$Where .= $and." ".$str_Pobla_Campan.'_C'.$intIdCampo_t." LIKE '".$_POST['ani']."'";
							$usados = 1;

						}

					}

					if (($_POST['canal'] == "whatsapp" || $_POST['canal'] == "Whatsapp") && isset($_POST['ani'])) {

						$intIdCampo_t = campoBusquedaChat($_GET["campana_crm"],'whatsapp');

						if (!is_null($intIdCampo_t) && $intIdCampo_t != 0 && $intIdCampo_t !== '') {
							
							$and = '';
							if($usados != 0){
								$and = ' OR ';
							}

							$Where .= $and." ".$str_Pobla_Campan.'_C'.$intIdCampo_t." LIKE '".$_POST['ani']."'";
							$usados = 1;

						}

					}

					if ($_POST['canal'] == "email" && filter_var($_POST['ani'], FILTER_VALIDATE_EMAIL)) {

						$intIdCampo_t = campoBusquedaChat($_GET["campana_crm"],'email');

						if (!is_null($intIdCampo_t) && $intIdCampo_t != 0 && $intIdCampo_t !== '') {
							
							$and = '';
							if($usados != 0){
								$and = ' OR ';
							}

							$Where .= $and." ".$str_Pobla_Campan.'_C'.$intIdCampo_t." LIKE '".$_POST['ani']."'";
							$usados = 1;

						}

					}

					if ($_POST['canal'] == "webform" && isset($_POST['ani'])) {
							$and = '';
							if($usados != 0){
								$and = ' OR ';
							}

							$Where .= $and." ".$str_Pobla_Campan."_ConsInte__b = '".$_POST['ani']."'";
							$usados = 1;
					}

				}

				if($usados == 0){
					$Where = " WHERE 1 ";
				}

			}else{
				if(!is_null($_POST['consinte']) && $_POST['consinte'] != ''){
					$Where .= $str_Pobla_Campan."_ConsInte__b = ". $_POST['consinte'];
				}
			}
			

                
			$Lsql .= $Where." LIMIT 10";
			//echo $Lsql;
			$resultado = $mysqli->query($Lsql);
			$arrayDatos = array();
			$newJson = array();
			$newJson[0]['cantidad_registros'] = 0;
			$newJson[0]['registros'] = array();
			$newJson[0]['camposivr'] = $objCamposivr;
			if($resultado){
				while ($key = $resultado->fetch_assoc()) {
					$arrayDatos[] = $key;
				}

				$newJson[0]['cantidad_registros'] = $resultado->num_rows;
				$newJson[0]['registros'] = $arrayDatos;
			}


			echo json_encode($newJson);
		}

		if($_GET['action'] == 'ADD'){
			/* toca insertar un registro vacio y editarlo desde el script */
			
			$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_CamAd1Cbx_b , CAMPAN_CamAd2Cbx_b , CAMPAN_CamAd3Cbx_b, CAMPAN_CamAd4Cbx_b , CAMPAN_CamAd5Cbx_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];

	        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
	        $datoCampan = $res_Lsql_Campan->fetch_array();

	        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
	        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];


	        $CAMPAN_CamAd1Cbx_b = null;
			$CAMPAN_CamAd2Cbx_b = null;
			$CAMPAN_CamAd3Cbx_b = null;
			$CAMPAN_CamAd4Cbx_b = null;
			$CAMPAN_CamAd5Cbx_b = null;

	        $CAMPAN_CamAd1Cbx_b = $datoCampan['CAMPAN_CamAd1Cbx_b'];
			$CAMPAN_CamAd2Cbx_b = $datoCampan['CAMPAN_CamAd2Cbx_b'];
			$CAMPAN_CamAd3Cbx_b = $datoCampan['CAMPAN_CamAd3Cbx_b'];
			$CAMPAN_CamAd4Cbx_b = $datoCampan['CAMPAN_CamAd4Cbx_b'];
			$CAMPAN_CamAd5Cbx_b = $datoCampan['CAMPAN_CamAd5Cbx_b'];



			$Lsql = "INSERT INTO ".$BaseDatos.".".$str_Pobla_Campan." (".$str_Pobla_Campan."_FechaInsercion";
			$Vsql = "VALUES ('".date('Y-m-d H:i:s')."'";

			$usados = 0;
			if(isset($_POST['dato_adicional_1'])){
				if($CAMPAN_CamAd1Cbx_b != '' && !is_null($CAMPAN_CamAd1Cbx_b)){
					/* si tenia definido los campos de busqueda  */
					$and = '';
					if($usados != 0){
						$and = ' , ';
					}

					$Lsql .= " , ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd1Cbx_b;
					$Vsql .= " , '".$_POST['dato_adicional_1']."'";
					$usados = 1;
				}
			}

			if(isset($_POST['dato_adicional_2'])){
				if($CAMPAN_CamAd2Cbx_b != '' && !is_null($CAMPAN_CamAd2Cbx_b)){
					/* si tenia definido los campos de busqueda  */
					$and = '';
					if($usados != 0){
						$and = ' , ';
					}

					$Lsql .= " , ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd2Cbx_b;
					$Vsql .= " , '".$_POST['dato_adicional_2']."'";
					$usados = 1;
				}
			}

			if(isset($_POST['dato_adicional_3'])){
				if($CAMPAN_CamAd3Cbx_b != '' && !is_null($CAMPAN_CamAd3Cbx_b)){
					/* si tenia definido los campos de busqueda  */
					$and = '';
					if($usados != 0){
						$and = ' , ';
					}

					$Lsql .= " , ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd3Cbx_b;
					$Vsql .= " , '".$_POST['dato_adicional_3']."'";
					$usados = 1;
				}
			}

			if(isset($_POST['dato_adicional_4'])){
				if($CAMPAN_CamAd4Cbx_b != '' && !is_null($CAMPAN_CamAd4Cbx_b)){
					/* si tenia definido los campos de busqueda  */
					$and = '';
					if($usados != 0){
						$and = ' , ';
					}

					$Lsql .= " , ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd4Cbx_b;
					$Vsql .= " , '".$_POST['dato_adicional_4']."'";
					$usados = 1;
				}
			}

			if(isset($_POST['dato_adicional_5'])){
				if($CAMPAN_CamAd5Cbx_b != '' && !is_null($CAMPAN_CamAd5Cbx_b)){
					/* si tenia definido los campos de busqueda  */
					$and = '';
					if($usados != 0){
						$and = ' , ';
					}

					$Lsql .= " , ".$str_Pobla_Campan.'_C'.$CAMPAN_CamAd5Cbx_b;
					$Vsql .= " , '".$_POST['dato_adicional_5']."'";
					$usados = 1;
				}
			}

			$insertarLSql = $Lsql.") ".$Vsql.");";
			//echo $insertarLSql;
			if ($mysqli->query($insertarLSql) === TRUE) {
                $resultado = $mysqli->insert_id;
                $InsertMuestra = "INSERT INTO ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." (".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b , ".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b) VALUES ($resultado, 0);";
                $mysqli->query($InsertMuestra);
                echo $resultado;
            }else{
            	echo "Error insertando el registro => ".$mysqli->error;
            }

		}	

	}

function campoBusquedaChat($intIdCampan_p,$strCanal){

	global $mysqli;
	global $BaseDatos_systema;
	global $BaseDatos;

	$intIdCampo_t = null;

	if ($strCanal == "chat") {
		$strSQLCampoBusqueda_t = "SELECT id_pregun_campo_busqueda AS idCampo FROM dyalogo_canales_electronicos.dy_chat_configuracion WHERE id_campana_crm = ".$intIdCampan_p." AND integrado_con = 'web'";
	}

	if ($strCanal == "whatsapp") {
		$strSQLCampoBusqueda_t = "SELECT CAMPAN_id_pregun_campo_busqueda_whatsapp AS idCampo FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$intIdCampan_p;
	}

	if ($strCanal == "email") {
		$strSQLCampoBusqueda_t = "SELECT CAMPAN_id_pregun_campo_busqueda_email AS idCampo FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$intIdCampan_p;
	}


	$resSQLCampoBusqueda_t = $mysqli->query($strSQLCampoBusqueda_t);

	if ($resSQLCampoBusqueda_t->num_rows > 0) {
		
		$resSQLCampoBusqueda_t = $resSQLCampoBusqueda_t->fetch_object();

		$intIdCampo_t = $resSQLCampoBusqueda_t->idCampo;

	}

	return $intIdCampo_t;

}
?>


