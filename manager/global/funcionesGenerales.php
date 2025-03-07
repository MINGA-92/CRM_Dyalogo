<?php

function EliminarCampañaEnCascada($intIdPaso_p){
	
	global $BaseDatos_telefonia;
	global $BaseDatos_systema;
	global $BaseDatos;
	global $mysqli;

    $strSQLDatosCampaña_t = "SELECT ESTPAS_Tipo______b AS tipo, (CASE WHEN CAMPAN_ConsInte__b IS NULL THEN ESTPAS_ConsInte__CAMPAN_b ELSE CAMPAN_ConsInte__b END) AS idCampan, (CASE WHEN CAMPAN_IdCamCbx__b IS NULL THEN ESTPAS_CampanACD_b ELSE CAMPAN_IdCamCbx__b END) AS idCBX, (CASE WHEN CAMPAN_ConsInte__MUESTR_b IS NULL THEN ESTPAS_ConsInte__MUESTR_b ELSE CAMPAN_ConsInte__MUESTR_b END)AS idMuestra, CAMPAN_ConsInte__GUION__Pob_b AS idBd FROM ".$BaseDatos_systema.".ESTPAS LEFT JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__b = ".$intIdPaso_p;

    $resSQLDatos_Campaña_t = $mysqli->query($strSQLDatosCampaña_t);

    $objDatos_Campaña_t = $resSQLDatos_Campaña_t->fetch_object();

    $tipo = $objDatos_Campaña_t->tipo;
    $idCampan = $objDatos_Campaña_t->idCampan;
    $idCBX = $objDatos_Campaña_t->idCBX;
    $idMuestra = $objDatos_Campaña_t->idMuestra;
    $idBd = $objDatos_Campaña_t->idBd;

    if (is_null($idBd) && !is_null($idMuestra)) {

        $strSQLDelMUESTR_t = "SELECT MUESTR_ConsInte__GUION__b AS idBd FROM ".$BaseDatos_systema.".MUESTR WHERE MUESTR_ConsInte__b = ".$idMuestra;
        $resSQLDelMUESTR_t = $mysqli->query($strSQLDelMUESTR_t);

        if ($resSQLDelMUESTR_t->num_rows > 0) {

            $objDelMUESTR_t = $resSQLDelMUESTR_t->fetch_object();
            $idBd = $objDelMUESTR_t->idBd;

            $strSQLDelTablaMUESTR_t = "DROP TABLE ".$BaseDatos.".G".$idBd."_M".$idMuestra;

            if ($mysqli->query($strSQLDelTablaMUESTR_t)) {

                guardar_auditoria("DeleteTablaMuestra","MUESTR: G".$idBd."_M".$idMuestra." SQL : ".$strSQLDelTablaMUESTR_t);

            }

            $strSQLElMUESTR_t = "DELETE FROM ".$BaseDatos_systema.".MUESTR WHERE MUESTR_ConsInte__b = ".$idMuestra;
            
            if ($mysqli->query($strSQLElMUESTR_t)) {

                guardar_auditoria("DeleteMuestr","idMUESTR: ".$idMuestra." SQL : ".$strSQLElMUESTR_t);

            }

        }

    }else if(!is_null($idBd) && !is_null($idMuestra)){

            $strSQLDelTablaMUESTR_t = "DROP TABLE ".$BaseDatos.".G".$idBd."_M".$idMuestra;
            
            if ($mysqli->query($strSQLDelTablaMUESTR_t)) {

                guardar_auditoria("DeleteTablaMuestra","MUESTR: G".$idBd."_M".$idMuestra." SQL : ".$strSQLDelTablaMUESTR_t);

            }

            $strSQLElMUESTR_t = "DELETE FROM ".$BaseDatos_systema.".MUESTR WHERE MUESTR_ConsInte__b = ".$idMuestra;
            
            if ($mysqli->query($strSQLElMUESTR_t)) {

                guardar_auditoria("DeleteMuestr","idMUESTR: ".$idMuestra." SQL : ".$strSQLElMUESTR_t);

            }

    }

    $strSQLDelESTCON_t = "DELETE FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$intIdPaso_p;
    if($mysqli->query($strSQLDelESTCON_t)){

        guardar_auditoria("DeleteEstcon","idPaso: ".$intIdPaso_p." SQL : ".$strSQLDelESTCON_t);

    }

    $strSQLDelESTPAS_t = "DELETE FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$intIdPaso_p;

    if($mysqli->query($strSQLDelESTPAS_t)){

        guardar_auditoria("DeleteEstpas","idPaso: ".$intIdPaso_p." SQL : ".$strSQLDelESTPAS_t);

    }

    if (!is_null($idCampan)) {

        $strSQLDelASITAR_t = "DELETE FROM ".$BaseDatos_systema.".ASITAR WHERE ASITAR_ConsInte__CAMPAN_b = ".$idCampan;

        if($mysqli->query($strSQLDelASITAR_t)){

            guardar_auditoria("DeleteAsitar","idCampan: ".$idCampan." SQL : ".$strSQLDelASITAR_t);

        }

        $strSQLDelCAMPAN_t = "DELETE FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$idCampan;

        if($mysqli->query($strSQLDelCAMPAN_t)){

            guardar_auditoria("DeleteCampan","idCampan: ".$idCampan." SQL : ".$strSQLDelCAMPAN_t);

        }

    }

    if (!is_null($idCBX)) {




        $strSQLDeldy_campanas_agentes_t = "DELETE FROM ".$BaseDatos_telefonia.".dy_campanas_agentes WHERE id_campana = ".$idCBX;

        if($mysqli->query($strSQLDeldy_campanas_agentes_t)){

            guardar_auditoria("Delete_dy_campanas_agentes","idCBX: ".$idCBX." SQL : ".$strSQLDeldy_campanas_agentes_t);

        }

        $strSQLDeldy_descansos_campanas_t = "DELETE FROM ".$BaseDatos_telefonia.".dy_descansos_campanas WHERE id_campana = ".$idCBX;

        if($mysqli->query($strSQLDeldy_descansos_campanas_t)){

            guardar_auditoria("Delete_dy_descansos_campanas","idCBX: ".$idCBX." SQL : ".$strSQLDeldy_descansos_campanas_t);

        }

        $strSQLDeldy_informacion_actual_campanas_t = "DELETE FROM ".$BaseDatos_telefonia.".dy_informacion_actual_campanas WHERE id_campana = ".$idCBX;

        if($mysqli->query($strSQLDeldy_informacion_actual_campanas_t)){

            guardar_auditoria("Delete_dy_informacion_actual_campanas","idCBX: ".$idCBX." SQL : ".$strSQLDeldy_informacion_actual_campanas_t);

        }

        $strSQLDeldy_informacion_intervalos_h_t = "DELETE FROM ".$BaseDatos_telefonia.".dy_informacion_intervalos_h WHERE id_campana = ".$idCBX;

        if($mysqli->query($strSQLDeldy_informacion_intervalos_h_t)){

            guardar_auditoria("Delete_dy_informacion_intervalos_h","idCBX: ".$idCBX." SQL : ".$strSQLDeldy_informacion_intervalos_h_t);

        }

        $strSQLDeldy_campanas_t = "DELETE FROM ".$BaseDatos_telefonia.".dy_campanas WHERE id = ".$idCBX;

        if($mysqli->query($strSQLDeldy_campanas_t)){

            guardar_auditoria("Delete_dy_campanas","idCBX: ".$idCBX." SQL : ".$strSQLDeldy_campanas_t);

        }

    }

}

/**
 * JDBD - retorna una letra de orden dependiendo el tipo de estado.
 * @return string.
 */
function ordenPorEstado($strEstado_p) {
	switch ($strEstado_p) {
		case 'Disponible':
			return 'A';
			break;
		case 'Pausado':
			return 'B';
			break;
		case 'Ocupado':
			return 'D';
			break;
		default:
			return 'C';
			break;
	}
}

/**
 * JDBD - Genera un color al azar para la estrategia nueva.
 * @return string - color rgb.
 */
function color_rand() {
	return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

/**
 * JDBD - Se obtiene la ruta de la imagen de la estrategia si existe, si no una por defecto.
 * @param int id de la estrategia.
 * @return string - ruta de la imagen.
 */
function rutaImagenEstrat($indIdEstrat_p){
	if (file_exists("../CampanhasImagenes/".$indIdEstrat_p.".jpg")) {
		return "pages/CampanhasImagenes/".$indIdEstrat_p.".jpg";
	}else{
		return "assets/img/user2-160x160.jpg";
	} 
}

/**
 * JDBD - esta funcion retorna el tipo de paso en texto.
 * @param int tipo de paso.
 * @return string - tipo de paso.
 */
function nombreTipoPaso($intTipo){
	switch ($intTipo) {
		case 1:
			return "IN";
			break;
		case 6:
			return "OUT";
			break;
	}
}
/** YCR-2019-09-11
 * Function para buscar erores de cargue
 * @return  devuelve un arreglo con las columnas y validaciones encontradas correspondiente al error encontrado
 * @param1 $accion=accion que se esta ejecutando
 * @param2 $error = cadena de errores  
 */
function buscadorErrores($accion,$error){
	include(__DIR__."/../pages/conexion.php");
	$arrayColumnasErrores=[];
	$columnaCSV = "";
	$validacion_S ="";
	$strError = strstr($error, '_C');
	$strError = strstr($strError, 'at',true);
	$intIdPregunt =  str_replace( array('_','C',' '),"",$strError);

	if( is_numeric($intIdPregunt)){                                                  
		
		$Lsql="SELECT PREGUN_Texto_____b,PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN  WHERE  PREGUN_ConsInte__b =".$intIdPregunt;
		$res=$mysqli->query($Lsql);
		if($res->num_rows > 0){
			$arrayPregunt=$res->fetch_array();
			$columnaCSV = "[".$arrayPregunt['PREGUN_Texto_____b']."]";
			
			switch ($arrayPregunt['PREGUN_Tipo______b']) {
				case '1':
					$validacion_S ="[Verifique que el texto tenga maximo 250  caracteres]";
					break;
				case '3':
					$validacion_S ="[Verifique que el campo contenga solo digitos]";
					break;
				case '4':
					$validacion_S ="[Verifique que el campo contenga solo digitos y separador de decimales]";
					break;
				case '5':
					$validacion_S ="[Verifique que la fecha tenga un formato valido]";
					break;
				case '6':
					$validacion_S ="[Verifique que el texto corresponde a una opcion de la lista(puede ver la lista en el editor de la base de datos)]";
					break;
				case '8':
					$validacion_S ="[Este campo solo admite los valores 1 y 0]";
					break;
				case '10':
					$validacion_S ="[Verifique que la hora tenga un formato valido]";
					break;
				case '11':
					$validacion_S ="[Verifique que el texto corresponde a una opcion de la lista(puede ver la lista en el editor de la base de datos)]";
					break;
				case '13':
					$validacion_S ="[Verifique que el texto corresponde a una opcion de la lista(puede ver la lista en el editor de la base de datos)]";
					break;
				case '14':
					$validacion_S ="[Verifique que el texto tenga maximizo 250  caracteres y que sea un formato de correo valido]";
					break;
				default:
					$validacion_S ="[Ocurrio un error al ".$accion." , por favor verificar registro]";
					break; 
			}
		}else{
			$validacion_S ="[Ocurrio un error al ".$accion." , por favor verificar registro]";
		}

	}

	$arrayColumnasErrores[0]=$columnaCSV;
	$arrayColumnasErrores[1]=$validacion_S;
	return $arrayColumnasErrores;

}


/** BGCR
 * Function insertar informacion sobre el journey
 * @return void
 * @param $intIdRegistro = id del registros en la base de datos
 * @param $intGPoblacion = id del G de la base de datos
 * @param $intIdPaso = id del paso que se ejecuto
 * @param $arrData = Array con la info que se debe de llenar sobre el journey
 * @param $masive = True en caso de que el carge sea masivo
 */
function insertarJourney($intIdRegistro, $intGPoblacion, $intIdPaso, $arrData, $masive = false ){


	if(!$masive){
		$duracion = (isset($arrData["duracion"])) ? '"'.$arrData["duracion"].'"' : "null";
		$agente = (isset($arrData["agente"])) ? '"'.$arrData["agente"].'"': "null";
		$sentido = (isset($arrData["sentido"])) ? '"'.$arrData["sentido"].'"' : "null";
		$canal = (isset($arrData["canal"])) ? '"'.$arrData["canal"].'"' : "null";
		$datoContacto = (isset($arrData["datoContacto"])) ? '"'.$arrData["datoContacto"].'"' : "null";
		$tipificacion = (isset($arrData["tipificacion"])) ? '"'.$arrData["tipificacion"].'"' : "null";
		$clasificacion = (isset($arrData["clasificacion"])) ? '"'.$arrData["clasificacion"].'"' : "null";
		$tipoReintento = (isset($arrData["tipoReintento"])) ? '"'.$arrData["tipoReintento"].'"' : "null";
		$comentario = (isset($arrData["comentario"])) ? '"'.$arrData["comentario"].'"' : "null";
		$linkContenido = (isset($arrData["linkContenido"])) ? '"'.$arrData["linkContenido"].'"' : "null";
		$fechaHora = (isset($arrData["fechaHora"])) ? '"'.$arrData["fechaHora"].'"' : "null";
	}

	$url = (!$masive) ? 'https://journey.cr.dyalogo.cloud/journey' : 'https://journey.cr.dyalogo.cloud/journey/massive';
	$postData = (!$masive) ? '{
		"serverName": "'.$_SERVER["SERVER_NAME"].'",
		"idG": "'.$intGPoblacion.'",
		"idPoblacion"  : "'.$intIdRegistro.'",
		"duracion " : '.$duracion.',
		"agente" : '.$agente.',
		"sentido" : '.$sentido.',
		"canal" : '.$canal.',
		"datoContacto" : '.$datoContacto.',
		"idPaso" : "'.$intIdPaso.'",
		"tipificacion" : '.$tipificacion.',
		"clasificacion" : '.$clasificacion.',
		"tipoReintento" : '.$tipoReintento.',
		"comentario" : '.$comentario.',
		"linkContenido"  : '.$linkContenido.',
		"fechaHora"  : '.$fechaHora.'
	}' :
	'{
		"serverName": "'.$_SERVER["SERVER_NAME"].'",
		"idG": "'.$intGPoblacion.'",
		"idPaso" : "'.$intIdPaso.'",
		"massiveInfo" : '.json_encode($arrData).'
	}';

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);

}

?>
