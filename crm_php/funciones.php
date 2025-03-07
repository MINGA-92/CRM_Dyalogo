<?php

	include('Jwt/jwt.php');
	include_once(__DIR__.'/configuracion/configuracion.php');


	function procesarPaso($intEstpas_p,$intIdBd_p,$intIdMuestra_p,$intConsInte_p){

		global $IP_FRONT_EJB;

		$data =[
			"strUsuario_t"  => 'crmphp',
			"strToken_t"    => 'vYjQ0GOlEiD3i9ZACvS06Ro8xXkCQ7Xf',
			"intEstpas_t" 	=> (int)$intEstpas_p,
			"intIdBd_t"  	=> (int)$intIdBd_p,
			"intIdMuestra_t"=> (int)$intIdMuestra_p,
			"intConsInte_t" => (int)$intConsInte_p
		];

		return consumirWSJSON($IP_FRONT_EJB."/dyjmsq/qs/pasos/procesar",$data );
	}

	function procesarPasoWhatsapp($intEstpas_p,$intIdBd_p,$intIdMuestra_p,$intConsInte_p, $huesped){

		global $IP_DY_MIDDLEWARE;
		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

		// Valido si el paso esta activo, de lo contrario no hago nada

		$sqlPaso = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_activo AS activo FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$intEstpas_p} LIMIT 1";
		$resPaso = $mysqli->query($sqlPaso);

		if($resPaso && $resPaso->num_rows > 0){

			$paso = $resPaso->fetch_object();

			if($paso->activo == '0'){
				return;
			}

		}
		
		// Traigo el registro
		$registroSql = "SELECT * FROM ".$BaseDatos.".G".$intIdBd_p." WHERE G".$intIdBd_p."_ConsInte__b =".$intConsInte_p. " LIMIT 1";
		$resRegistro = $mysqli->query($registroSql);
		$registro = $resRegistro->fetch_object();

		// Traigo la plantilla 
		$plantillaSql = "SELECT a.id AS id_plantilla_saliente, a.id_cuenta_whatsapp, a.id_pregun_telefono, b.nombre, b.id_plantilla_facebook, b.tipo, b.proveedor, b.idioma FROM dyalogo_canales_electronicos.dy_wa_plantillas_salientes a 
			JOIN dyalogo_canales_electronicos.dy_wa_plantillas b ON a.id_wa_plantilla = b.id 
		WHERE a.id_estpas =".$intEstpas_p." LIMIT 1";

		$resPlantilla = $mysqli->query($plantillaSql);
		$plantilla = $resPlantilla->fetch_object();

		// Si de tipo texto la plantilla, este campo se va vacio
		$mediaTemplate = null;

		// Parametros de la plantilla
		$parametros = [];
		$parametrosSql = "SELECT e.id AS id, p.nombre, e.accion, e.id_pregun, e.valor_estatico FROM dyalogo_canales_electronicos.dy_wa_plantillas_variables p 
		JOIN dyalogo_canales_electronicos.dy_wa_plantillas_salientes_variables e ON p.id = e.id_wa_plantilla_variable 
		WHERE e.id_wa_plantilla_saliente = ".$plantilla->id_plantilla_saliente;

		
		
		$resParametros = $mysqli->query($parametrosSql);
		if($resParametros){
			if($resParametros->num_rows > 0){

				// Obtengo la lista de campos de tipo fecha hora de la bd
				$arrCamposFechaHora = obtenerCamposFechaHora($intIdBd_p);

				// Recorro la lista de parametros
				while ($row = $resParametros->fetch_object()) {

					// Dependiendo del nombre del parametro tiene que comportarse de manera distinta

					if($row->nombre == 'DY_IMAGE' || $row->nombre == 'DY_VIDEO' || $row->nombre == 'DY_DOCUMENT' || $row->nombre == 'DY_LOCATION'){

						// Nos toca ver si es estatico o dinamico
						if($row->accion == 1){
							// Valido si el campo que llega es un numero o texto para identificar si es variable de pregun o de sistema
							if(is_numeric($row->id_pregun)){
								$newCampo = "G".$intIdBd_p."_C".$row->id_pregun;
							}else{
								if($row->id_pregun == 'ConsInte'){
									$newCampo = "G".$intIdBd_p."_".$row->id_pregun."__b";
								}else{
									$newCampo = "G".$intIdBd_p."_".$row->id_pregun."_b";
								}
							}
							if(!is_null($registro->$newCampo)){
								$mediaTemplate = $registro->$newCampo;
							}
						}else{
							// Valido que el valor estatico no sea nulo
							if(!is_null($row->valor_estatico)){
								$mediaTemplate = $row->valor_estatico;
							}
						}

						// Si es una ubicacion lo separo en dos
						if($row->nombre == 'DY_LOCATION'){
							$aux = explode(',', $mediaTemplate);
							$mediaTemplate = [];
							$mediaTemplate['longitud'] = isset($aux[0]) ? trim($aux[0]) : 0;
							$mediaTemplate['latitud'] = isset($aux[1]) ? trim($aux[1]) : 0;
						}

					}else{
						$valor = '-';
						// Aqui valido si el campo tiene un valor estatico o es un valor de la base de datos
						if($row->accion == 1){
							// Valido si el campo que llega es un numero o texto para identificar si es variable de pregun o de sistema
							if(is_numeric($row->id_pregun)){
								$newCampo = "G".$intIdBd_p."_C".$row->id_pregun;
							}else{
								if($row->id_pregun == 'ConsInte'){
									$newCampo = "G".$intIdBd_p."_".$row->id_pregun."__b";
								}else{
									$newCampo = "G".$intIdBd_p."_".$row->id_pregun."_b";
								}
							}
							if(!is_null($registro->$newCampo)){
								// Si es un campo de la bd verificare si tiene fechas o hora para reformatearlo
								$valor = verificarFormatoCampo($arrCamposFechaHora, $newCampo, $registro->$newCampo);
							}
						}else{
							// Valido que el valor estatico no sea nulo
							if(!is_null($row->valor_estatico)){
								$valor = $row->valor_estatico;
							}
						}
	
						$parametros[$row->nombre] = $valor;
					}

				}
			}
		}
		

		$campoTelefono = "G".$intIdBd_p."_C".$plantilla->id_pregun_telefono;

		$telefono = $registro->$campoTelefono;

		// Validamos si hay patrones en el huesped
		$patrones = json_decode(ObtenerPatron($huesped, null, true));
		
		if($patrones && count($patrones->patron_regexp) > 0){

			for ($i=0; $i < count($patrones->patron_regexp); $i++) {
				// Analizo patron por patron para ver si hay coincidencia
				if(preg_match($patrones->patron_regexp[$i], $telefono)){
					// Validamos si este numero ya viene con el codigo de pais para no agregarlo
					if(strpos($telefono, $patrones->codigo_pais[$i]) !== 0){
						$telefono = $patrones->codigo_pais[$i] . $telefono;
					}
					break;
				}
			}
		}else{
			// Si no hay patrones por defecto lo validamos por el numero de celular de colombia y agregarle el codigo de pais
			if(preg_match('/^[3][0-9]{9,9}$/', $telefono)){
				$telefono = '57' . $telefono;
			}
		}

		// Comprobamos que a quien se envia la plantilla este autorizado solo si es gupshup
		if($plantilla->proveedor === 'gupshup'){
			verificarOptIn($registro, $intIdBd_p, $plantilla->id_cuenta_whatsapp, $telefono);
		}
		
		// Array que se envia como el body
		$data = [
			"usuario"	=> 'crmphp',
			"token"		=> 'vYjQ0GOlEiD3i9ZACvS06Ro8xXkCQ7Xf',
			"reg_id" => $intConsInte_p,
			"paso_id" => $intEstpas_p,
			"base_id" => $intIdBd_p,
			"from" 		=> $plantilla->id_cuenta_whatsapp,
			"to" 		=> $telefono,
			"plantilla" => $plantilla->nombre,
			"plantilla_id" => $plantilla->id_plantilla_facebook,
			"plantilla_tipo" => $plantilla->tipo,
			"plantilla_media" => $mediaTemplate,
			"parametros"=> $parametros,
			"plantilla_lenguaje" => $plantilla->idioma
		];

		$respuesta = consumirWSJSON($IP_DY_MIDDLEWARE."/dymdw/api/whatsapp/templateout", $data);
		$res = json_decode($respuesta);
		
		// Idependientemente de cual sea la respuesta me toca guardar el resultado en la muestra

		$gestion = -12;

		// Validamos de que nos llega sea un objeto
		if(is_object($res)){
			if($res->strEstado_t == 'ok'){
				$gestion = -12;
			}else{
				$gestion = -13;
			}
			$comentario = "{$res->strEstado_t} - {$res->strMensaje_t} - {$res->objSerializar_t}";
		}else{
			$comentario = json_encode($res);
		}

		$muestra = "G{$intIdBd_p}_M{$intIdMuestra_p}";
		$fecha =  date("Y-m-d H:i:s");
		$nuevaFecha = date("Y-m-d H:i:s", strtotime($fecha." +1 hour"));

		$updateMuestra = "UPDATE {$BaseDatos}.{$muestra} SET 
			{$muestra}_ConIntUsu_b = -10, {$muestra}_FecHorMinProGes__b = '{$nuevaFecha}', {$muestra}_UltiGest__b = '{$gestion}', {$muestra}_GesMasImp_b = '{$gestion}', {$muestra}_FecUltGes_b = '{$fecha}',
			{$muestra}_FeGeMaIm__b = '{$fecha}', {$muestra}_Estado____b = 3, {$muestra}_TipoReintentoGMI_b = 3, {$muestra}_ConUltGes_b = 5, {$muestra}_CoGesMaIm_b = 5, {$muestra}_UsuarioUG_b = -10,
			{$muestra}_UsuarioGMI_b = -10, {$muestra}_CanalUG_b = 'Whatsapp', {$muestra}_CanalGMI_b = 'Whatsapp', {$muestra}_SentidoUG_b = 'Saliente', {$muestra}_SentidoGMI_b = 'Saliente', 
			{$muestra}_NumeInte__b = {$muestra}_NumeInte__b + 1, {$muestra}_CantidadIntentosGMI_b = {$muestra}_CantidadIntentosGMI_b + 1, {$muestra}_Comentari_b = '{$comentario}', {$muestra}_ComentarioGMI_b = '{$comentario}',
			{$muestra}_LinkContenidoUG_b = 'No existe grabación porque no hubo comunicacion', {$muestra}_LinkContenidoGMI_b = 'No existe grabación porque no hubo comunicacion', {$muestra}_DetalleCanalUG_b = '{$plantilla->id_cuenta_whatsapp} - {$plantilla->id_plantilla_facebook}',
			{$muestra}_DetalleCanalGMI_b = '{$plantilla->id_cuenta_whatsapp} - {$plantilla->id_plantilla_facebook}', {$muestra}_DatoContactoUG_b = '{$registro->$campoTelefono}', {$muestra}_DatoContactoGMI_b = '{$registro->$campoTelefono}',
			{$muestra}_TienGest__b = NULL
		WHERE {$muestra}_CoInMiPo__b = {$intConsInte_p}";

		// TODO: Modificar el log para que pueda recibir parametros de monitoreo y parametros obligatorios por si falla esta consulta
		if($mysqli->query($updateMuestra)){
			// En caso de que todo funcione se inserta en el journey
			// Nota aun no se ejecuta porque primero se quiere probar con otros pasos
			// $arrJourney = ["sentido" => "Saliente", "canal" => 'Whatsapp', "datoContacto" => $registro->$campoTelefono, "tipificacion" => -502, "clasificacion" => 5, "tipoReintento" => 3];
			// insertarJourney($intConsInte_p,$intIdBd_p, $intEstpas_p,  $arrJourney);
		}else{
			// TODO: AQUI ejecutaria el log
		}

		// Por ultimo marcamos el registro en la bd la plantilla saliente
		$updateBd = "UPDATE {$BaseDatos}.G{$intIdBd_p} SET G{$intIdBd_p}_Template_b = {$intEstpas_p}, G{$intIdBd_p}_TemplateFechaEnvio_b = '{$fecha}' WHERE G{$intIdBd_p}_ConsInte__b = {$intConsInte_p}";
		$mysqli->query($updateBd);
	}

	function verificarFormatoCampo($arrCampos, $campoVerificarId, $valor){

		if(is_array($arrCampos) && count($arrCampos) > 0){
			
			foreach ($arrCampos as $campo) {
				// si el campo coincide con lo del arreglo significa que el campo es fecha/hora
				if("G".$campo->guion."_C".$campo->id == $campoVerificarId){
	
					// Valido si el campo es fecha
					if($campo->tipo == 5){

						$fecha = new DateTime($valor);

						// Formato deseado
						$formato_deseado = 'Y-m-d';

						// Convertir la fecha al formato deseado
						$valor = $fecha->format($formato_deseado);
					}
	
					// Valido si el campo es hora
					if($campo->tipo == 10){
						$fecha = new DateTime($valor);

						// Formato deseado
						$formato_deseado = 'H:i:s';

						// Convertir la fecha al formato deseado
						$valor = $fecha->format($formato_deseado);
					}
	
					break;
				}
			}
		}

		return $valor;
	}

	function obtenerCamposFechaHora($bdId){

		global $mysqli;
		global $BaseDatos_systema;

		$campos = [];

		// Traigo todos los campos fecha y hora de la base de datos
		$sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_ConsInte__GUION__b AS guion, PREGUN_Tipo______b AS tipo FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$bdId} AND (PREGUN_Tipo______b = 5 OR PREGUN_Tipo______b = 10)";
		$result = $mysqli->query($sql);
		if ($result && $result->num_rows > 0) {
			while ($row = $result->fetch_object()) {
				$campos[] = $row;
			}
		}

		return $campos;
	}

	function verificarOptIn($registro, $baseId, $canalId, $telefono){

		global $mysqli;
		global $BaseDatos_systema;

		// Buscamos el campo optin
		$campoOptIn = 0;

		$sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$baseId} AND PREGUN_Texto_____b = 'OPTIN_DY_WF' LIMIT 1";
		$res = $mysqli->query($sql);

		if($res && $res->num_rows > 0){
			$campoPregun = $res->fetch_object();
			$campoOptIn = 'G' . $baseId . '_C' . $campoPregun->id;
		}

		// Buscamos si ya esta habilitado el optin en el registro
		if($registro->$campoOptIn !== 'WHATSAPP_FORZADO'){
			$campoId = 'G' . $baseId .'_ConsInte__b';
			$registroId = $registro->$campoId;
			registrarOptIn($registroId, $telefono, $campoOptIn, $canalId, $baseId);
		}

	}

	function registrarOptIn($registroId, $telefono, $campoOptIn, $canalId, $baseId){

		global $IP_DY_MIDDLEWARE;
		global $mysqli;
		global $BaseDatos;

		$data = [
			"usuario" => "crmphp",
			"token" => "vYjQ0GOlEiD3i9ZACvS06Ro8xXkCQ7Xf",
			"canalId" => $canalId,
			"telefono" => $telefono
		];

		$respuesta = consumirWSJSON($IP_DY_MIDDLEWARE."/dymdw/api/whatsapp/optin", $data);

		$resObj = json_decode($respuesta);

		if(is_object($resObj) && isset($resObj->strEstado_t) && isset($resObj->objSerializar_t)){
			if($resObj->strEstado_t == 'ok' && $resObj->objSerializar_t == '202'){
				$valorOptIn = 'WHATSAPP_FORZADO';
			}else{
				$valorOptIn = 'FALLO_OPTIN_'.$resObj->objSerializar_t;
			}
		}else{
			$valorOptIn = 'FALLO_OPTIN_WS';
		}

		// Registramos el optIn en la base
		$sql = "UPDATE {$BaseDatos}.G{$baseId} SET {$campoOptIn} = '{$valorOptIn}' WHERE G{$baseId}_ConsInte__b = {$registroId}";
		$mysqli->query($sql);
	}

	function consumirWSJSON($strURL_p, $arrayDatos_p){

	    //Codificamos el arreglo en formato JSON
	    $strDatosJSON_t = json_encode($arrayDatos_p);
	    
	    //Inicializamos la conexion CURL al web service local para ser consumido
	    $objCURL_t = curl_init($strURL_p);
	    
	    //Asignamos todos los parametros del consumo
	    curl_setopt($objCURL_t, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($objCURL_t, CURLOPT_POSTFIELDS, $strDatosJSON_t); 
	    curl_setopt($objCURL_t,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($objCURL_t, CURLOPT_HTTPHEADER, array(
	        'Accept: application/json',
	        'Content-Type: application/json',
	        'Content-Length: ' . strlen($strDatosJSON_t))                                                                      
	    );

	    //Obtenemos la respuesta
	    $objRespuestaCURL_t = curl_exec($objCURL_t);

	    //Obtenemos el error 
	    $objRespuestaError_t = curl_error($objCURL_t);

	    //Cerramos la conexion
	    curl_close ($objCURL_t);

	    //Validamos la respuesta y generamos el rerno
	    if (isset($objRespuestaCURL_t)) {
	        //Decodificamos la respuesta en JSON y la retornamos
	        return $objRespuestaCURL_t;
	    }else {
	        return $objRespuestaError_t;
	    }
	}

	function getFechaAgenda($fecha, $operador, $cantidad){

		$operador = ($operador == 1) ? "+" : "-";
		
		if($fecha == -1){
			$actual = date("Y-m-d");
			$newFecha = date("Y-m-d", strtotime($actual."$operador $cantidad days"));
		}else{
			$newFecha = date("Y-m-d", strtotime($fecha."$operador $cantidad days"));
		}
		
		return $newFecha;
	}

	function getHoraAgenda($hora, $operador, $cantidad){

		$operador = ($operador == 1) ? "+" : "-";

		if($hora == -1){
			$actual = "00:00:00";
			$newHora = date("H:i:s", strtotime($actual."$operador $cantidad hour"));
		}else{
			$newHora = date("H:i:s", strtotime($hora."$operador $cantidad hour"));
		}

		return $newHora;
	}

	function getTipoDistribucion($paso, $tipoPaso){
		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

		// Si el tipo de distribucion retorna 1 tratara de asignar un agente
		// Si el tipo de distribucion retorna 0 no agrega agente
		
		// Busco el tipo de distribucion en la campaña
		if($tipoPaso == 6){
			$sqlCampana = "SELECT CAMPAN_ConfDinam_b AS distribucion, CAMPAN_ConsInte__b AS id FROM ".$BaseDatos_systema.".CAMPAN JOIN ".$BaseDatos_systema.".ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__b = ".$paso;
			$resSqlCampana = $mysqli->query($sqlCampana);

			if($resSqlCampana){
				if($resSqlCampana->num_rows > 0){
					$r = $resSqlCampana->fetch_array();

					//En campaña si es -1 es dinamica y no se asigna; si es 0 es predefinida y se asigna
					if($r['distribucion'] == -1){
						return array(0, $r['id'], 6);
					}else{
						return array(1, $r['id'], 6, $r['distribucion']);
					}
				}
			}
		}

		// Busco el tipo de distribucion si es backoffice
		if($tipoPaso == 9){
			$sqlBackoffice = "SELECT TAREAS_BACKOFFICE_TipoDistribucionTrabajo_b AS distribucion, TAREAS_BACKOFFICE_ConsInte__b AS id, TAREAS_BACKOFFICE_ConsInte__PREGUN_estado_b AS condicion, TAREAS_BACKOFFICE_ConsInte__LISOPC_estado_b AS valor FROM ".$BaseDatos_systema.".TAREAS_BACKOFFICE JOIN ".$BaseDatos_systema.".ESTPAS ON ESTPAS_ConsInte__b = TAREAS_BACKOFFICE_ConsInte__ESTPAS_b WHERE ESTPAS_ConsInte__b = ".$paso;
			$resSqlBackoffice = $mysqli->query($sqlBackoffice);

			if ($resSqlBackoffice) {
				if ($resSqlBackoffice->num_rows > 0) {
					$r = $resSqlBackoffice->fetch_array();

					// En backoffice 1 no asigna agente; 2-3 asigna un agente
					if($r['distribucion'] != 1){
						return array(1, $r['id'], 9, $r['distribucion'], $r['condicion'], $r['valor']);
					}else{
						return array(0, $r['id'], 9);
					}
				}
			}
		}
		
		return array(0, 0);
	}

	function asignarAgente($condicion, $tipoPaso, $intBd_p, $intM_p, $distribucion = null, $variable = null, $valor = null){
		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

		if($tipoPaso == 6){
			$sql = "SELECT ASITAR_ConsInte__USUARI_b as agente FROM ".$BaseDatos_systema.".ASITAR LEFT JOIN DYALOGOCRM_WEB.G".$intBd_p."_M".$intM_p." ON G".$intBd_p."_M".$intM_p."_ConIntUsu_b = ASITAR_ConsInte__USUARI_b AND G".$intBd_p."_M".$intM_p."_Estado____b <> 3 WHERE ASITAR_ConsInte__CAMPAN_b = ".$condicion." GROUP BY (ASITAR_ConsInte__USUARI_b) ORDER BY COUNT(G".$intBd_p."_M".$intM_p."_CoInMiPo__b) LIMIT 1";
			$resSql = $mysqli->query($sql);
			
			if($resSql){
				if($resSql->num_rows > 0){
					$r = $resSql->fetch_array();
					return $r['agente'];
				}
			}
		}

		if($tipoPaso == 9){

			if($distribucion == 2){
				$sql = "SELECT ASITAR_BACKOFFICE_ConsInte__USUARI_b as agente FROM ".$BaseDatos_systema.".ASITAR_BACKOFFICE 
					LEFT JOIN DYALOGOCRM_WEB.G".$intBd_p."_M".$intM_p." 
						ON G".$intBd_p."_M".$intM_p."_ConIntUsu_b = ASITAR_BACKOFFICE_ConsInte__USUARI_b AND G".$intBd_p."_M".$intM_p."_Estado____b <> 3 
					LEFT JOIN DYALOGOCRM_WEB.G".$intBd_p." 
						ON G".$intBd_p."_ConsInte__b = G".$intBd_p."_M".$intM_p."_CoInMiPo__b AND G".$intBd_p."_C".$variable." = ".$valor."
					WHERE ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$condicion." 
					GROUP BY (ASITAR_BACKOFFICE_ConsInte__USUARI_b) 
					ORDER BY COUNT(G".$intBd_p."_ConsInte__b) LIMIT 1";
			}else{
				$sql = "SELECT ASITAR_BACKOFFICE_ConsInte__USUARI_b as agente FROM ".$BaseDatos_systema.".ASITAR_BACKOFFICE LEFT JOIN DYALOGOCRM_WEB.G".$intBd_p."_M".$intM_p." ON G".$intBd_p."_M".$intM_p."_ConIntUsu_b = ASITAR_BACKOFFICE_ConsInte__USUARI_b AND G".$intBd_p."_M".$intM_p."_Estado____b <> 3 WHERE ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b = ".$condicion." GROUP BY (ASITAR_BACKOFFICE_ConsInte__USUARI_b) ORDER BY COUNT(G".$intBd_p."_M".$intM_p."_CoInMiPo__b) LIMIT 1";
			}
			$resSql = $mysqli->query($sql);
			
			if($resSql){
				if($resSql->num_rows > 0){
					$r = $resSql->fetch_array();
					return $r['agente'];
				}
			}
		}
		
		return 0;
	}

	// Aca solo traigo el tipo de paso
	function getTipoPaso($intPasoId){

		global $mysqli;
		global $BaseDatos_systema;

		$sql = "SELECT ESTPAS_ConsInte__b as id, ESTPAS_Tipo______b AS tipo FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$intPasoId} LIMIT 1";
		$res = $mysqli->query($sql);

		if($res && $res->num_rows > 0){
			$row = $res->fetch_object();
			return $row->tipo;
		}

		return 0;
	}

	function getPaso($intPasoId){

		global $mysqli;
		global $BaseDatos_systema;

		$sql = "SELECT ESTPAS_ConsInte__b as id, ESTPAS_Comentari_b AS nombre FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b = {$intPasoId} LIMIT 1";
		$res = $mysqli->query($sql);

		if($res && $res->num_rows > 0){
			$row = $res->fetch_object();
			return $row;
		}

		return null;
	}

	// Traigo la muestra de una campaña basado en el id del paso
	function muestraCampanaPasoSalida($intPasoId){
		global $mysqli;
		global $BaseDatos_systema;

		$Ssql = "SELECT CAMPAN_ConsInte__MUESTR_b AS muestra FROM ".$BaseDatos_systema.".CAMPAN INNER JOIN ".$BaseDatos_systema.".ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE ESTPAS_ConsInte__b = ".$intPasoId." LIMIT 1";
		$res = $mysqli->query($Ssql);

		$muestra = 0;

		if($res){
			if($res->num_rows > 0){
				$r = $res->fetch_array();
				$muestra = $r['muestra'];
			}
		}

		return $muestra;
	}

	// Esta funcion es para sacar el registro del paso anterior
	function sacarPasoAnterior($intPasoId, $intRegistroId, $intBaseId, $muestraCampana = null){
		
		global $mysqli;
		global $BaseDatos_systema;
		global $BaseDatos;

		// Primero traigo la muestra del paso
		$pSql = "SELECT ESTPAS_ConsInte__b, ESTPAS_ConsInte__MUESTR_b, ESTPAS_Tipo______b FROM $BaseDatos_systema.ESTPAS WHERE ESTPAS_ConsInte__b = ".$intPasoId. " AND (ESTPAS_Tipo______b = 9 || ESTPAS_Tipo______b = 1 || ESTPAS_Tipo______b = 6) LIMIT 1";
		$res = $mysqli->query($pSql);

		if($res && $res->num_rows > 0){
			$row = $res->fetch_array();

			$muestraId = null;

			// Valido que el paso sea de backoffice o campaña
			if($row['ESTPAS_Tipo______b'] == 9){
				if(!is_null($row['ESTPAS_ConsInte__MUESTR_b']) && $row['ESTPAS_ConsInte__MUESTR_b'] > 0){
					$muestraId = $row['ESTPAS_ConsInte__MUESTR_b'];
				}
			}

			if($row['ESTPAS_Tipo______b'] == 1 || $row['ESTPAS_Tipo______b'] == 6){
				if(!is_null($muestraCampana)){
					$muestraId = $muestraCampana;
				}
			}

			// Revalido que $muestraId si tenga un valor
			if(!is_null($muestraId)){
				$baseCompleta = "G".$intBaseId."_M".$muestraId;

				// Ahora si armo la consulta para sacarlo del paso anterior osea eliminar el registro
				$dSql = "DELETE FROM ".$BaseDatos.".".$baseCompleta." WHERE ".$baseCompleta."_CoInMiPo__b = ".$intRegistroId;

				dyLog("Inicio el proceso de sacar del paso anterior Registro ". $intRegistroId . " Paso " . $intPasoId . " Base " . $intBaseId . " y el sql => " . $dSql);

				$mysqli->query($dSql);
			}
		}	

	}

	function sacarRegistrosDeOtrosPasos($registroId, $pasos, $baseId, $pasoFrom, $pasoTo){

		global $BaseDatos_systema;
		global $BaseDatos;
		global $mysqli;

		$arrPasos = explode(',', $pasos);

		if(count($arrPasos) > 0){

			$sql = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_ConsInte__MUESTR_b AS muestraId, CAMPAN_ConsInte__MUESTR_b AS muestraCampanaId, ESTPAS_Tipo______b AS tipo FROM {$BaseDatos_systema}.ESTPAS 
			LEFT JOIN {$BaseDatos_systema}.CAMPAN ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b
			WHERE ESTPAS_ConsInte__b IN ({$pasos})";
			$res = $mysqli->query($sql);

			if($res && $res->num_rows > 0){

				$fecha =  date("Y-m-d H:i:s");
				$nuevaFecha = date("Y-m-d H:i:s", strtotime($fecha." +1 hour"));

				$dataPasoFrom = getPaso($pasoFrom);
				$dataPasoTo = getPaso($pasoTo);

				$comentario = "Acción ejecutada desde la flecha que va del paso {$dataPasoFrom->nombre} al paso {$dataPasoTo->nombre}";

				while($row = $res->fetch_object()){

					if($row->tipo == 1 || $row->tipo == 6){
						$muestra = 'G'.$baseId.'_M'.$row->muestraCampanaId;
					}else{
						$muestra = 'G'.$baseId.'_M'.$row->muestraId;
					}

					// Ya teniendo la muestra podemos empezar con el update
					$updateMuestra = "UPDATE {$BaseDatos}.{$muestra} SET 
					{$muestra}_ConIntUsu_b = -10, {$muestra}_FecHorMinProGes__b = '{$nuevaFecha}', {$muestra}_UltiGest__b = -23, {$muestra}_GesMasImp_b = -23, {$muestra}_FecUltGes_b = '{$fecha}',
					{$muestra}_FeGeMaIm__b = '{$fecha}', {$muestra}_Estado____b = 3, {$muestra}_TipoReintentoGMI_b = 3, {$muestra}_Comentari_b = '{$comentario}', {$muestra}_ComentarioGMI_b = '{$comentario}'
					WHERE {$muestra}_CoInMiPo__b = {$registroId} AND {$muestra}_Estado____b != 3";
					
					$mysqli->query($updateMuestra);
				}
			}

		}
	}

	function insertarEnMuestra($intBd_p, $intM_p, $intC_p, $intAgente_p, $resucitarRegistro = false, $intE_p = null, $intF_p = null, $intH_p = null, $arrDataOrigen=array(), $ultGes_p = null){

		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

		$intEstado_t = 0;
		$strFecha_t = ",G".$intBd_p."_M".$intM_p."_FecHorAge_b";
		$strFechaV_t = ",NULL AS Agenda";
		$strFechaVU_t = "NULL";

		$strAgente = ",G".$intBd_p."_M".$intM_p."_ConIntUsu_b";
		$strAgenteV = ",NULL AS Agente";
		$strAgenteVU = "NULL";

		$strComentario= ",G".$intBd_p."_M".$intM_p."_Comentari_b";
		$strComentarioV= ",NULL AS Comentario";
		$strComentarioVU= "NULL";

		// if (!is_null($intF_p) && $intF_p != -1) {

		// 	$strFechaV_t = ",G".$intBd_p."_C".$intF_p;

		// }

		if($intAgente_p > 0){
			$strAgenteV = ",".$intAgente_p;
			$strAgenteVU = $intAgente_p;
		}

		if (!is_null($intE_p) && $intE_p == -1) {
			if (!is_null($intF_p) && $intF_p != "") {
				if (!is_null($intH_p) && $intH_p != "") {

					$intEstado_t = 2;
					$strFechaV_t = ",'".trim($intF_p)." ".trim($intH_p)."'";
					$strFechaVU_t = "'".trim($intF_p)." ".trim($intH_p)."'";
				}
			}
			$intEstado_t = 2;
		}


		if(count($arrDataOrigen) > 0){
			$intEstado_t = $arrDataOrigen['estado'];
			$strComentarioV=",'{$arrDataOrigen['comentario']}' AS Comentario";
			$strComentarioVU="'{$arrDataOrigen['comentario']}'";
			if($intEstado_t == 2){
				$strFechaV_t = ",'{$arrDataOrigen['agenda']}' AS Agenda";
				$strFechaVU_t = "'{$arrDataOrigen['agenda']}'";
			}else{
				$strFechaV_t = ",NULL AS Agenda";
				$strFechaVU_t = "NULL";
			}
		}

		// Se valida si la ultima gestion de la base de datos fue no contactable
		$ultGes_v = 3;
		if($ultGes_p == 2){
			$ultGes_v = 2;
		}


		dyLog("Inicio el proceso de insercion del registro " . $intC_p . " en la muestra G".$intBd_p."_M".$intM_p);
		$strSQLInsertM_t = "INSERT INTO ".$BaseDatos.".G".$intBd_p."_M".$intM_p." (G".$intBd_p."_M".$intM_p."_CoInMiPo__b,G".$intBd_p."_M".$intM_p."_Activo____b,G".$intBd_p."_M".$intM_p."_Estado____b,G".$intBd_p."_M".$intM_p."_TipoReintentoGMI_b,G".$intBd_p."_M".$intM_p."_NumeInte__b,G".$intBd_p."_M".$intM_p."_CantidadIntentosGMI_b, G".$intBd_p."_M".$intM_p."_ConUltGes_b ".$strFecha_t."".$strAgente."".$strComentario." ) SELECT G".$intBd_p."_ConsInte__b,-1 AS Activo____b,".$intEstado_t." AS Estado____b,0 AS TipoReintentoGMI_b,0 AS NumeInte__b,0 AS CantidadIntentosGMI_b, ".$ultGes_v." ".$strFechaV_t."".$strAgenteV."".$strComentarioV." FROM ".$BaseDatos.".G".$intBd_p." WHERE G".$intBd_p."_ConsInte__b = ".$intC_p;

		if($resucitarRegistro){
			dyLog("El registro " . $intC_p . " ya existe entonces procedo a actualizarlo con la siguiente informacion G".$intBd_p."_M".$intM_p . " G".$intBd_p."_M".$intM_p."_Activo____b = -1, G".$intBd_p."_M".$intM_p."_Estado____b = ".$intEstado_t." ".$strFecha_t." = ".$strFechaVU_t." ".$strAgente." = ".$strAgenteVU." ");
			$strSQLInsertM_t = "UPDATE ".$BaseDatos.".G".$intBd_p."_M".$intM_p." SET G".$intBd_p."_M".$intM_p."_Activo____b = -1, G".$intBd_p."_M".$intM_p."_FecHorMinProGes__b = NULL, G".$intBd_p."_M".$intM_p."_ConUltGes_b = ".$ultGes_v.", G".$intBd_p."_M".$intM_p."_FecHorAge_b = NULL ".$strFecha_t." = ".$strFechaVU_t.", G".$intBd_p."_M".$intM_p."_Estado____b = ".$intEstado_t." ".$strFecha_t." = ".$strFechaVU_t." ".$strAgente." = ".$strAgenteVU." ".$strComentario." = ".$strComentarioVU." WHERE G".$intBd_p."_M".$intM_p."_CoInMiPo__b = ".$intC_p;
		}
		
		if ($mysqli->query($strSQLInsertM_t)) {
			dyLog("La consulta de insercion/actualizacion fue correcta para " . $intC_p . " en la muestra G".$intBd_p."_M".$intM_p);

			$fechaModificacion = date("Y-m-d H:i:s");

			// Actualizo la fecha de creacion/actualizacion aparte por si hay tablas de muestras que no tienen estos archivos
			if($resucitarRegistro){
				// Reactivacion
				$upd = "UPDATE {$BaseDatos}.G{$intBd_p}_M{$intM_p} SET G{$intBd_p}_M{$intM_p}_FechaReactivacion_b = '{$fechaModificacion}' WHERE G{$intBd_p}_M{$intM_p}_CoInMiPo__b = {$intC_p}";
			}else{
				// Creacion
				$upd = "UPDATE {$BaseDatos}.G{$intBd_p}_M{$intM_p} SET G{$intBd_p}_M{$intM_p}_FechaCreacion_b = '{$fechaModificacion}' WHERE G{$intBd_p}_M{$intM_p}_CoInMiPo__b = {$intC_p}";
			}

			$mysqli->query($upd);

			return true;

		}else{

			return false;

		}
	}

	
	function getAgendaOrigen($intBase,$intMuestra,$intRegistro){
		global $mysqli;
		global $BaseDatos;

		$response=array();
		$strMuestra="G{$intBase}_M{$intMuestra}";
		$strCampos="{$strMuestra}_Estado____b AS estado, {$strMuestra}_FecHorAge_b AS agenda, {$strMuestra}_Comentari_b AS comentario";
		$sql=$mysqli->query("SELECT {$strCampos} FROM {$BaseDatos}.{$strMuestra} WHERE {$strMuestra}_CoInMiPo__b=$intRegistro");
		if($sql && $sql->num_rows == 1){
			$response=$sql->fetch_array();
		}

		return $response;
	}

	function DispararProceso($intPasoId_p, $intRegistroId_p = null, $intUsuAsigado = null){

		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

		dyLog("Inicio el proceso de flecha para el registro " . $intRegistroId_p . " con el paso de entrada " . $intPasoId_p);

		$campos = "ESTCON_ConsInte__b AS flecha_id, ESTCON_Consulta_sql_b AS consulta, ESTCON_Tipo_Insercion_b AS tipo_insercion, 
			ESTCON_ConsInte_PREGUN_Fecha_b AS pregun_fecha, ESTCON_ConsInte_PREGUN_Hora_b AS pregun_hora, 
			ESTCON_Operacion_Fecha_b AS operacion_fecha, ESTCON_Operacion_Hora_b AS orperacion_hora, 
			ESTCON_Cantidad_Fecha_b AS cantidad_fecha, ESTCON_Cantidad_Hora_b AS cantidad_hora, 
			ESTCON_Sacar_paso_anterior_b AS sacar_paso_anterior, ESTCON_resucitar_registro AS resucitar_registro, 
			ESTCON_Sacar_Otros_Pasos_b AS sacar_de_otro_paso, ESTCON_Sacar_Otros_Pasos_Ids_b AS sacar_de_otro_paso_id,ESTCON_Hereda_MONOEF_b,
			CAMPAN_ConsInte__MUESTR_b AS muestra_campana, 
			ESTRAT_ConsInte_GUION_Pob AS guion, ESTRAT_ConsInte__PROYEC_b AS huesped,
			ESTPAS_ConsInte__MUESTR_b AS muestra, ESTPAS_Tipo______b AS estpas_tipo, ESTPAS_ConsInte__b AS estpas_id";

		// Primero valido que tipo de paso es de donde sale la flecha, para: si es una campana debo traer la muestra de la campana, si es la respuesta de un sms saliente cambiar la consulta de estcon
		$tipoPaso = getTipoPaso($intPasoId_p);
		
		// Si el tipo de paso es sms saliente debo cambiar la consulta de estcon, tambien que a la flecha a la que apunta debe ser de sms entrante
		if($tipoPaso == 8){
			
			$sqlPasoSmsEntrante = "SELECT ESTPAS_ConsInte__b AS id, ESTPAS_Tipo______b AS tipo FROM {$BaseDatos_systema}.ESTCON 
				JOIN {$BaseDatos_systema}.ESTPAS ON ESTCON_ConsInte__ESTPAS_Has_b = ESTPAS_ConsInte__b 
				WHERE ESTCON_ConsInte__ESTPAS_Des_b = {$intPasoId_p} AND ESTPAS_Tipo______b = 18 LIMIT 1";

			$resPasoSiguiente = $mysqli->query($sqlPasoSmsEntrante);

			if($resPasoSiguiente && $resPasoSiguiente->num_rows > 0){
				
				$rowPasoSiguiente = $resPasoSiguiente->fetch_object();

				// Reasigno el id paso de donde sale
				$intPasoId_p = $rowPasoSiguiente->id;
				$tipoPaso = $rowPasoSiguiente->tipo;
			}
		}

		$strSQLEstcon_t = "SELECT $campos FROM ".$BaseDatos_systema.".ESTCON JOIN ".$BaseDatos_systema.".ESTPAS ON ESTCON_ConsInte__ESTPAS_Has_b = ESTPAS_ConsInte__b  JOIN ".$BaseDatos_systema.".ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b LEFT JOIN ".$BaseDatos_systema.".CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$intPasoId_p." AND ESTCON_Consulta_sql_b IS NOT NULL AND ESTCON_Activo_b = -1 AND ESTPAS_Tipo______b IN (6, 7, 8, 9, 13, 18, 22)";
		$resSQLEstcon_t = $mysqli->query($strSQLEstcon_t);

		// Valido si el paso de donde salen es campana entrante o saliente
		$pasoSalidaEsCampana = false;

		$muestraCampana = null;
		
		if ($tipoPaso == 1 || $tipoPaso == 6) {
			
			$pasoSalidaEsCampana = true;
			// Traigo la muestra de la campana de donde sale la flecha para armar el nuevo $strSQLInsert_t
			$muestraCampana = muestraCampanaPasoSalida($intPasoId_p);
		}

		if ($resSQLEstcon_t && $resSQLEstcon_t->num_rows > 0) {
			
			// Aqui ya recorro cada flecha conectada al paso de salida
			while ($row = $resSQLEstcon_t->fetch_object()) {
				
				dyLog("Recorriendo la flecha " . $row->flecha_id . " para el registro " . $intRegistroId_p);

				// Extraigo solo el where de la consulta
				$strFiltros_t = $row->consulta;
				$strFiltros_t = explode("WHERE", $strFiltros_t);

				if(isset($strFiltros_t[1])){
					$strFiltros_t = " AND ( ". $strFiltros_t[1]." )";
				}else{
					$strFiltros_t = "";
				}

				$huesped = $row->huesped;

				$intBase = $row->guion;
				$strTipoPaso_t = $row->estpas_tipo;
				$intIdPasoCondicion_t = $row->estpas_id;

				$resucitarRegistro = $row->resucitar_registro;
				$sacarPasoAnterior = $row->sacar_paso_anterior;

				$sacarDeOtroPaso = $row->sacar_de_otro_paso;
				$pasosDondeSaldranRegistros = $row->sacar_de_otro_paso_id;

				// Campos de agendamiento
				$estconCampoFecha = $row->pregun_fecha;
				$estconCampoHora = $row->pregun_hora;
				$estconOperacionFecha = $row->operacion_fecha;
				$estconOperacionHora = $row->orperacion_hora;
				$estconCantidadFecha = $row->cantidad_fecha;
				$estconCantidadHora = $row->cantidad_hora;
				$estconHeredaAgenda = $row->ESTCON_Hereda_MONOEF_b == -1 ? getAgendaOrigen($intBase,$muestraCampana,$intRegistroId_p) : array();				// Valido el tipo de paso al que va, si es campana o un paso normal para setear la muestra
				if ($strTipoPaso_t == 1 || $strTipoPaso_t == 6) {
					$intMuestra = $row->muestra_campana;
				}else{
					$intMuestra = $row->muestra;
				}

				// Que tipo de insercion es, ejecucion inmediata o agendado
				$strTipoInsercion_t = $row->tipo_insercion;

				$strFechas = '';

				// Si es agendamiento traigo la fecha  y hora del G
				if($strTipoInsercion_t == -1){
					if(!is_null($estconCampoFecha) && $estconCampoFecha != -1 && $estconCampoFecha != 0){
						
						$strFechas .= ", DATE(G".$intBase."_C".$estconCampoFecha.") AS fecha";
					}
	
					if(!is_null($estconCampoHora) && $estconCampoHora != -1 && $estconCampoHora != 0){
						
						$strFechas .= ", TIME(G".$intBase."_C".$estconCampoHora.") AS hora";
					}
				}

				// Este sql trae los registros que cumplan con la condicion
				$strSQLInsert_t = "SELECT G".$intBase."_ConsInte__b AS id ".$strFechas.", B.G".$intBase."_M".$intMuestra."_CoInMiPo__b AS muestra, G".$intBase."_ClasificacionUG_b as ultGes FROM ".$BaseDatos.".G".$intBase." A LEFT JOIN ".$BaseDatos.".G".$intBase."_M".$intMuestra." B ON A.G".$intBase."_ConsInte__b = B.G".$intBase."_M".$intMuestra."_CoInMiPo__b WHERE G".$intBase."_ConsInte__b = ".$intRegistroId_p." ".$strFiltros_t;
				
				// Si es una campana modifico la consulta para traer el registro
				if($pasoSalidaEsCampana){
					$strSQLInsert_t = "SELECT G".$intBase."_ConsInte__b AS id ".$strFechas.", B.G".$intBase."_M".$intMuestra."_CoInMiPo__b AS muestra, G".$intBase."_ClasificacionUG_b as ultGes FROM ".$BaseDatos.".G".$intBase." A LEFT JOIN ".$BaseDatos.".G".$intBase."_M".$intMuestra." B ON A.G".$intBase."_ConsInte__b = B.G".$intBase."_M".$intMuestra."_CoInMiPo__b LEFT JOIN ".$BaseDatos.".G".$intBase."_M".$muestraCampana." C ON A.G".$intBase."_ConsInte__b = C.G".$intBase."_M".$muestraCampana."_CoInMiPo__b WHERE G".$intBase."_ConsInte__b = ".$intRegistroId_p." ".$strFiltros_t;
				}

				// ejecuto la consulta para obtener los registros
				$resSQLInsert_t = $mysqli->query($strSQLInsert_t);
				
				if ($resSQLInsert_t && $resSQLInsert_t->num_rows > 0) {

					dyLog("El registro " . $intRegistroId_p . " cumple la condicion de la flecha " .$row->flecha_id);

					while ($fila = $resSQLInsert_t->fetch_object()) {
						
						if($fila->id == $intRegistroId_p){

							$agente = 0;

							// Buscar si se asigna el agente aqui, cuando el registro cumple con kas condiciones de insertar
							$arrDistribucion = getTipoDistribucion($intIdPasoCondicion_t, $strTipoPaso_t);
							
							if($arrDistribucion[0] == 1){
								if(!is_null($intUsuAsigado) && $intUsuAsigado > 0){
									$agente = $intUsuAsigado;
								}else{
									// Verifico si es bacoffice o campaña
									if($arrDistribucion[2] == 6){
										$agente = asignarAgente($arrDistribucion[1], $strTipoPaso_t, $intBase, $intMuestra);
									}else if($arrDistribucion[2] == 9){
										$agente = asignarAgente($arrDistribucion[1], $strTipoPaso_t, $intBase, $intMuestra, $arrDistribucion[3], $arrDistribucion[4], $arrDistribucion[5]);
									}
								}
							}
							
							// Aqui valido si el registro es nuevo o ya existe
							if(is_null($fila->muestra)){

								dyLog("El registro " . $intRegistroId_p . " es nuevo, en la muestra G".$intBase."_M".$intMuestra." con la flecha ".$row->flecha_id."  tipo de insercion = " . $strTipoInsercion_t);

								if ($strTipoInsercion_t == 0) {
									// Si es ejecucion inmediata
									if (insertarEnMuestra($intBase, $intMuestra, $fila->id, $agente,false,null,null,null,$estconHeredaAgenda, $fila->ultGes)) {
	
										if ($strTipoPaso_t == 7 || $strTipoPaso_t == 8) {
											// Si el paso es email saliente o sms saliente
											procesarPaso($intIdPasoCondicion_t,$intBase,$intMuestra,$fila->id);
										} 
										
										if($strTipoPaso_t == 13){
											//Si el paso es saliente whatsapp (plantillas)
											procesarPasoWhatsapp($intIdPasoCondicion_t,$intBase,$intMuestra,$fila->id, $huesped);
										}
										
										// Valido que el paso de donde sale la flecha es backoffice, campaña entrante o saliente
										if(($tipoPaso == 9 || $tipoPaso == 1 || $tipoPaso == 6 ) && $sacarPasoAnterior == -1){
											// Si el paso es tarea backoffice y esta activada la opcion de sacar de paso anterior
											sacarPasoAnterior($intPasoId_p, $intRegistroId_p, $intBase, $muestraCampana);
										}
									}
	
								}else{
									// Si es agenda realizo el calculo de la insercion
									$campoFecha = isset($fila->fecha) ? $fila->fecha : -1;
									$campoHora = isset($fila->hora) ? $fila->hora : -1;
	
									$fecha = getFechaAgenda($campoFecha, $estconOperacionFecha, $estconCantidadFecha);
		
									$hora = getHoraAgenda($campoHora, $estconOperacionHora, $estconCantidadHora);
		
									insertarEnMuestra($intBase, $intMuestra, $fila->id, $agente, false, -1, $fecha, $hora, $estconHeredaAgenda, $fila->ultGes);

									// Valido que el paso de donde sale la flecha es backoffice, campaña entrante o saliente
									if(($tipoPaso == 9 || $tipoPaso == 1 || $tipoPaso == 6 ) && $sacarPasoAnterior == -1){
										// Si el paso es tarea backoffice y esta activada la opcion de sacar de paso anterior
										sacarPasoAnterior($intPasoId_p, $intRegistroId_p, $intBase, $muestraCampana);
									}
								}

								// Validamos si podemos ejecutar la funcion de sacar el registro de otros pasos
								if($sacarDeOtroPaso == -1){
									sacarRegistrosDeOtrosPasos($intRegistroId_p, $pasosDondeSaldranRegistros, $intBase, $intPasoId_p, $intIdPasoCondicion_t);
								}
							}else{
								// Sino trato de resucitar el registro

								dyLog("El registro " . $intRegistroId_p . " ya existe en la muestra G".$intBase."_M".$intMuestra." con la flecha ".$row->flecha_id." y resucitar registro = " . $resucitarRegistro);

								if($resucitarRegistro){
									if ($strTipoInsercion_t == 0) {

										if (insertarEnMuestra($intBase,$intMuestra,$fila->id, $agente, true, null, null, null, $estconHeredaAgenda)) {
		
											if ($strTipoPaso_t == 7 || $strTipoPaso_t == 8) {
												// Si el paso es email saliente o sms saliente
												procesarPaso($intIdPasoCondicion_t,$intBase,$intMuestra,$fila->id);
											}
											
											if($strTipoPaso_t == 13){
												//Si el paso es saliente whatsapp (plantillas)
												procesarPasoWhatsapp($intIdPasoCondicion_t,$intBase,$intMuestra,$fila->id, $huesped);
											}

											// Valido que el paso de donde sale la flecha es backoffice, campaña entrante o saliente
										if(($tipoPaso == 9 || $tipoPaso == 1 || $tipoPaso == 6 ) && $sacarPasoAnterior == -1){
												// Si el paso es tarea backoffice y esta activada la opcion de sacar de paso anterior
												sacarPasoAnterior($intPasoId_p, $intRegistroId_p, $intBase, $muestraCampana);
											}
											
										}
		
									}else{
										// Si es agenda realizo el calculo de la insercion
										$campoFecha = isset($fila->fecha) ? $fila->fecha : -1;
										$campoHora = isset($fila->hora) ? $fila->hora : -1;
		
										$fecha = getFechaAgenda($campoFecha, $estconOperacionFecha, $estconCantidadFecha);
			
										$hora = getHoraAgenda($campoHora, $estconOperacionHora, $estconCantidadHora);
			
										insertarEnMuestra($intBase, $intMuestra, $fila->id, $agente, true, -1, $fecha, $hora, $estconHeredaAgenda);

										// Valido que el paso de donde sale la flecha es backoffice, campaña entrante o saliente
										if(($tipoPaso == 9 || $tipoPaso == 1 || $tipoPaso == 6 ) && $sacarPasoAnterior == -1){
											// Si el paso es tarea backoffice y esta activada la opcion de sacar de paso anterior
											sacarPasoAnterior($intPasoId_p, $intRegistroId_p, $intBase, $muestraCampana);
										}
									}

									// Validamos si podemos ejecutar la funcion de sacar el registro de otros pasos
									if($sacarDeOtroPaso == -1){
										sacarRegistrosDeOtrosPasos($intRegistroId_p, $pasosDondeSaldranRegistros, $intBase, $intPasoId_p, $intIdPasoCondicion_t);
									}
								}
							}
						}
					}

				}

			}	


		}

		return true;
	}


	function enviarCorreoParaDyalogo($intIdPaso_p, $intIdRegistro_p){

		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;



		// Se valida el guion,el id, Y el mail del webform

		$strSQLPaso_t = "SELECT WEBFORM_Consinte__b AS id, WEBFORM_Guion____b AS guion, WEBFORM_IdMailSaliente__b as correEntranteId, WEBFORM_IdCampoMailCliente__b as idCampoMailCliente FROM ".$BaseDatos_systema.".WEBFORM WHERE WEBFORM_ConsInte__ESTPAS_b  = ".$intIdPaso_p;
		$resSQLPaso_t = $mysqli->query($strSQLPaso_t);
		if ($resSQLPaso_t && $resSQLPaso_t->num_rows > 0) {

			$resSQLPaso_t = $resSQLPaso_t->fetch_object();


			// Se valida si el paso tiene flechas conectadas Y activas

			$strSQLFlechas_t = "SELECT f.ESTCON_ConsInte__ESTPAS_Has_b as pasoDestino, f.ESTCON_Consulta_sql_b as consultaFiltro, e.ESTRAT_ConsInte__PROYEC_b AS huesped, p.ESTPAS_Comentari_b AS nombreCampana, p.ESTPAS_CampanACD_b AS idCampanCbx FROM ".$BaseDatos_systema.".ESTCON f JOIN ".$BaseDatos_systema.".ESTRAT e ON ESTCON_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b JOIN ".$BaseDatos_systema.".ESTPAS p ON f.ESTCON_ConsInte__ESTPAS_Has_b =  p.ESTPAS_ConsInte__b  WHERE f.ESTCON_ConsInte__ESTPAS_Des_b =  ".$intIdPaso_p;
			$resSQLFlechas_t = $mysqli->query($strSQLFlechas_t);

			if ($resSQLFlechas_t && $resSQLFlechas_t->num_rows > 0) {

				while ($rowFlecha = $resSQLFlechas_t->fetch_object()) {

						// Se valida si el filtro coincide con el registro
	
						$strSQLValidarFiltro_t = $rowFlecha->consultaFiltro." AND G".$resSQLPaso_t->guion."_ConsInte__b = ".$intIdRegistro_p;
						$resSQLValidarFiltro_t = $mysqli->query($strSQLValidarFiltro_t);


						if ($resSQLValidarFiltro_t && $resSQLValidarFiltro_t->num_rows > 0) {
							
							// Aqui obtengo los campos del formulario para construir el mail
							
							$strSQLCampos_t = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_PermiteAdicion_b , PREGUN_DefaNume__b , PREGUN_IndiRequ__b, PREGUN_TipoTel_b FROM ".$BaseDatos_systema.".PREGUN INNER JOIN ".$BaseDatos_systema.".WEBFORM_CAMPOS ON WEBFORM_CAMPOS_ConsInte__PREGUN_b = PREGUN_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$resSQLPaso_t->guion." AND WEBFORM_CAMPOS_ConsInte__WEBFORM_b = ".$resSQLPaso_t->id." AND PREGUN_FueGener_b != 3 ORDER BY WEBFORM_CAMPOS_Orden_b ASC";
							$resSQLCampos_t = $mysqli->query($strSQLCampos_t);
							if ($resSQLCampos_t && $resSQLCampos_t->num_rows > 0) {


								// Construimos el cuerpo del mail
								$strSqlCampos = "";
								$camposMail = "";
								$arrCampos = [];
								$strCampoFromMail = ($resSQLPaso_t->correEntranteId != -1)? "G".$resSQLPaso_t->guion."_C".$resSQLPaso_t->idCampoMailCliente : null;
						
								
								while ($rowCampo = $resSQLCampos_t->fetch_object()) {
									$strSqlCampos .= "G".$resSQLPaso_t->guion."_C".$rowCampo->id.", ";

									array_push($arrCampos, ["name" =>  $rowCampo->titulo_pregunta , "field" => "G".$resSQLPaso_t->guion."_C".$rowCampo->id]);

								}

								$strSQLdata = "SELECT ".$strSqlCampos." G".$resSQLPaso_t->guion."_ConsInte__b FROM ".$BaseDatos.".G".$resSQLPaso_t->guion." WHERE G".$resSQLPaso_t->guion."_ConsInte__b = ".$intIdRegistro_p;
								$resSQLdata = $mysqli->query($strSQLdata);

								if($resSQLdata && $resSQLdata->num_rows > 0){

									while ($rowdata = $resSQLdata->fetch_object()) {

										foreach ($arrCampos as $key => $value) {
											$camposMail .= '<p><label style="font-weight: bold; font-size:14px">'.$value["name"].': </label><a style="font-weight: lighter; font-size:14px">'.$rowdata->{$value["field"]}.'</a></p>';

										}

									// Se saca el dato del correo del cliente en caso de que se tenga establecido el campo para esto
									$coreoDinamico = ($resSQLPaso_t->correEntranteId != -1) ? "'".$rowdata->{$strCampoFromMail}."'"  : "NULL";
									// Se saca el dato del primer campo seleccionado en el webform
									$nombre_contacto_crm = $rowdata->{$arrCampos[0]["field"]};
		
									}
									
								}


									// Inicializo los campos


									$correEntranteId = ($resSQLPaso_t->correEntranteId != -1 ) ? $resSQLPaso_t->correEntranteId : "-1" ;
									$filtro = "null";
									$accionFiltro = "null";
									$huesped = $rowFlecha->huesped;
									$colaDistribucion = $rowFlecha->idCampanCbx;
									$nombreCampana = $rowFlecha->nombreCampana;
									$para = $resSQLPaso_t->id; // Sobre la columna PARA se guarda el id del webform que llenaron
									$de = $intIdRegistro_p; // Sobre la columna DE se guarda el id de la base de datos, para la busqueda por ani
									$cc = $coreoDinamico; // Sobre la columna CC se guarda el correo al que se debe de enviar la respuesta

									// Estaticos
									$asunto = "Correo enviado desde webform";
									$cuerpo ='<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>@import url("https://fonts.googleapis.com/css?family=Source+Sans+Pro");.avenir-font {font-family: "Source Sans Pro";}</style></head><body class="avenir-font"><h1 style="font-weight: bold; font-size:20px">Campos de Webform</h1>'.$camposMail.'</body></html>';
									$fecha = date("Y-m-d H:i:s");
									$estado = 1;
									$esRespuesta = 0;
									$leido = 0;
									$nombreContacto = $de;
									$estadoGestion = 1;
									
									
									$sqlInsert = "INSERT INTO dyalogo_canales_electronicos.dy_ce_entrantes (
										id_ce_configuracion, id_ce_filtro, id_accion_filtro, de, para, cc, asunto, cuerpo, 
										id_huesped, fecha_hora, fecha_hora_recibido_servidor, fecha_hora_proceso_accion, 
										id_cola_distribucion, fecha_hora_paso_cola, estado, es_respuesta, leido, 
										nombre_campana_crm, nombre_contacto_crm, estado_gestion, origen) 
									VALUES (
										{$correEntranteId}, {$filtro}, {$accionFiltro}, '{$de}', '{$para}', {$cc}, '{$asunto}', '{$cuerpo}', 
										{$huesped}, '{$fecha}', '{$fecha}', '{$fecha}',
										{$colaDistribucion}, '{$fecha}', {$estado}, {$esRespuesta}, {$leido},
										'{$nombreCampana}', '{$nombre_contacto_crm}', {$estadoGestion}, 'webform'
									)";
									echo($sqlInsert);

									if($mysqli->query($sqlInsert) === TRUE){
										$registro = $mysqli->insert_id;

										$posicion = date("ymdHi");

										$sqlInsert2 = "INSERT INTO dyalogo_canales_electronicos.dy_ce_espera (id_entrante, id_cola, remitente, fecha_hora, posicion, peso_campana) 
										VALUES ({$registro}, {$colaDistribucion}, '{$de}', '{$fecha}', {$posicion}, 1)";

										$mysqli->query($sqlInsert2);

										$sqlInsert3 = "INSERT INTO dyalogo_canales_electronicos.dy_ce_rastreo (
											canale_email, id_ce_entrante, fecha_hora, cuerpo, comentario_adicional, nombre_usuario, duracion, privado, fecha_hora_proxima_gestion
										) VALUES (
											1, {$registro}, '{$fecha}', '{$cuerpo}', 'entrada', '{$nombreContacto}', 0, 0, '{$fecha}'
										)";

										$mysqli->query($sqlInsert3);
									}
								}
							}
						}
				}
			}
		}


			
		


	// creamos una funcion para hacerle seguimiento a lo que esta pasando por aca
	function dyLog($text){
		$path = "/var/log/dyalogo/";
		$filename = "procesadorFlecha";
		$date = date("Y-m-d H:i:s");
		$ip = ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0;

		$log = $date . " [ip] " . $ip . " [text] " . $text . PHP_EOL;

		try {
			// $result = (file_put_contents($path . $filename . ".log", $log, FILE_APPEND)) ? 1 : 0;
		} catch (\Throwable $th) {
			//throw $th;
		}
	}

	 /**
	 * JDBD-2020-05-03 : Se obtiene un fragmento cadena para MySQL con el campo mas operador mas valor Eje: (G###_C### = 'valor').
	 * @param String - Nombre del campo de la tabla Guion en la base.
	 * @param String - Operador Ejem: = , != , <, >, etc.
	 * @param Integer - Tipo de campo Ejem_: 3=Int, 1=Text, 6=Lista, etc.
	 * @param String - Valor o dato a filtar segun para el campo y tipo de campo que venga.
	 * @return String - Retorna la condicion armada para MySQL segun el campo, operador y valor a filtrar.
	 */
	function operadorYFiltro($strCampo_p,$strOperador_p,$intTipo_p,$valor_p){

		$strSQLFiltros_t = "";

		if ($intTipo_p != 5 && $intTipo_p != 10) {

			$strSQLFiltros_t .= $strCampo_p." ";

			if ($intTipo_p == 6 || $intTipo_p == 4 || $intTipo_p == 3){

				if ($intTipo_p == 6) {
					if ($valor_p == "0") {
						switch ($strOperador_p) {
							case '!=':
								$strSQLFiltros_t .= " IS NOT NULL ";
								break;
							default:
								$strSQLFiltros_t .= " IS NULL ";
								break;
						}
					}else{
						$strSQLFiltros_t .= $strOperador_p." ".$valor_p." ";
					}
				}else{
					if ($valor_p == "") {
						switch ($strOperador_p) {
							case '!=':
								$strSQLFiltros_t .= " IS NOT NULL ";
								break;
							default:
								$strSQLFiltros_t .= " IS NULL ";
								break;
						}
					}else{
						$strSQLFiltros_t .= $strOperador_p." ".$valor_p." ";
					}
				}


			}else{

				if ($valor_p == "") {
					switch ($strOperador_p) {
						case '!=':
							$strSQLFiltros_t = "(".$strCampo_p." IS NOT NULL OR ".$strCampo_p." != '') ";
							break;
						default:
							$strSQLFiltros_t = "(".$strCampo_p." IS NULL OR ".$strCampo_p." = '') ";
							break;
					}
				}else{
					switch ($strOperador_p) {
						case 'LIKE_1':
							$strSQLFiltros_t .= " LIKE '".$valor_p."%' ";
							break;
						case 'LIKE_2':
							$strSQLFiltros_t .= " LIKE '%".$valor_p."%' ";
							break;
						case 'LIKE_3':
							$strSQLFiltros_t .= " LIKE '%".$valor_p."' ";
							break;
						default:
							$strSQLFiltros_t .= $strOperador_p." '".$valor_p."' ";
							break;
					}
				}


			}			
		}elseif($intTipo_p == 5){

			if ($valor_p == "") {
				switch ($strOperador_p) {
					case '!=':
						$strSQLFiltros_t .= $strCampo_p." IS NOT NULL ";
						break;
					default:
						$strSQLFiltros_t .= $strCampo_p." IS NULL ";
						break;
				}
			}else{
				switch ($strOperador_p) {
					case 'LIKE_1':
						$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%Y-%m-%d') LIKE '".$valor_p."%' ";
						break;
					case 'LIKE_2':
						$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%Y-%m-%d') LIKE '%".$valor_p."%' ";
						break;
					case 'LIKE_3':
						$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%Y-%m-%d') LIKE '%".$valor_p."' ";
						break;
					default:
						$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%Y-%m-%d') ".$strOperador_p." '".$valor_p."' ";
						break;
				}
			}			


		}elseif($intTipo_p == 10){

			$valor_p = str_replace(" ", "", $valor_p);

			switch ($strOperador_p) {
				case 'LIKE_1':
					$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%H:%i:%s') LIKE '".$valor_p."%' ";
					break;
				case 'LIKE_2':
					$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%H:%i:%s') LIKE '%".$valor_p."%' ";
					break;
				case 'LIKE_3':
					$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%H:%i:%s') LIKE '%".$valor_p."' ";
					break;
				default:
					$strSQLFiltros_t .= "DATE_FORMAT(".$strCampo_p.",'%H:%i:%s') ".$strOperador_p." '".$valor_p."' ";
					break;
			}

		}

		return $strSQLFiltros_t;

	}

	/**
	 * JDBD - Se obtienen el texto traducido de la opcion de la lista.
	 * @param Integer id de LISOPC.
	 * @param Integer tipo del campo.
	 * @return string - texto traducido de la opcion.
	 */
	function traductor($val_p,$tip_p){
		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

		
		$arrTipList_t = [6,11,13];
		if ($tip_p == 6 || $tip_p == 11 || $tip_p == 13) {
				
			$opcion = "SELECT LISOPC_Nombre____b as nom 
					   FROM ".$BaseDatos_systema.".LISOPC 
					   WHERE LISOPC_ConsInte__b = ".$val_p;

		    $opcion = $mysqli->query($opcion);

		    $opcion = $opcion->fetch_array();

		    return $opcion["nom"];
		}else{
			return $val_p;
		}	
	}	
	/*YCR 2019-10-11
	*Funcion para crear usuario por defecto
	*/
	function crearMiembroDefault($idCampan){
		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

		if($idCampan != 0 || $idCampan != ''){
			$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$idCampan;
	        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
	        $datoCampan = $res_Lsql_Campan->fetch_array();
	        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
	        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
			$muestraCompleta = $str_Pobla_Campan."_M".$int_Muest_Campan;

	        $Lsql = "SELECT * FROM  ".$BaseDatos.".".$str_Pobla_Campan." WHERE ".$str_Pobla_Campan."_ConsInte__b = -1 ";
	        if( ($result = $mysqli->query($Lsql) ) == TRUE  ){
	        	if($result->num_rows ==  0){
	        		$fecha_creacion = date('Y-m-d H:i:s');
	        		$LsqlBD= "INSERT INTO ".$BaseDatos.".".$str_Pobla_Campan." (".$str_Pobla_Campan."_ConsInte__b,".$str_Pobla_Campan."_FechaInsercion)
							VALUES ('-1','".$fecha_creacion."')";						
					$mysqli->query($LsqlBD);
					
	        	}	
	        }

			$LsqlMuestra=$mysqli->query("SELECT * FROM {$BaseDatos}.{$muestraCompleta} WHERE {$muestraCompleta}_CoInMiPo__b = -1");
			if($LsqlMuestra && $LsqlMuestra->num_rows == 0){
				$LsqlMuestra="INSERT INTO  ".$BaseDatos.".".$muestraCompleta."  (".$muestraCompleta ."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b ) VALUES ('-1', '0','3')";
				$mysqli->query($LsqlMuestra);
			}
		}
	}
	/**
	 * fn   valida que los campos no esten vacios o nulos
	 */
	function validador($strCampo){
		if($strCampo == "" || $strCampo == null ){
			$valor="NULL";
		}else{
			$valor=$strCampo;
		}
		return $valor;
	}
	//validar el acceso del usuario por token o por Session
	function detalle_canal($n,$p,$c){
		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;

    	$nombre_usuario = "Numero no se identifica";

	    if (is_numeric($n)) {

	        $consul = "SELECT B.id, B.patron FROM dyalogo_telefonia.pasos_troncales A JOIN dyalogo_telefonia.tipos_destino B ON A.id_tipos_destino = B.id  WHERE A.id_campana = ".$c;

	        $obj = $mysqli->query($consul);

	        $canPat = 0;
	        $canNum = 0;
	        $idDestino = 0;
            if($obj -> num_rows > 0){
                while($obje = $obj->fetch_object()){
                    $canPat = strlen($obje->patron);
                    $canNum = strlen($n);
                    if ($canPat == $canNum) {
                        $idDestino = $obje->id;
                    }

               }
            }

	        $consul = "SELECT id_troncal FROM dyalogo_telefonia.pasos_troncales WHERE id_estpas = ".$p." AND id_tipos_destino  = ".$idDestino;

	        $obj = $mysqli->query($consul);

	        $id_troncal = 0;
	        while($obje = $obj->fetch_object()){
	            $id_troncal = $obje->id_troncal;
	       }

	       $consul = "SELECT nombre_usuario FROM dyalogo_telefonia.dy_troncales WHERE id = ".$id_troncal;

	       $obj = $mysqli->query($consul);


	        while($obje = $obj->fetch_object()){
	            $nombre_usuario = $obje->nombre_usuario;
	       }
	    }    

    	return $nombre_usuario;

    }

	function getMailUser($token = null){
		if(isset($_SESSION['CODIGO'])){
			return $_SESSION['CODIGO'];
		}

		if(!is_null($token)){
			$user = JWT::decode(trim($token, '"'));
			if(!is_null($user)){
				$nombres;
				//var_dump($user);
				foreach ($user as $key) {
					$nombres = $key->NOMBRES ;
				}
				return $nombres;
			}else{
				return 0;
			}
		}

		if(!isset($_SESSION['CODIGO']) && is_null($token)){
			return 0;
		}

	}	
	
	function getAccesoUser($token = null){

		if(isset($_SESSION['ACCESO'])){
			return $_SESSION['ACCESO'];
		}

		if(!is_null($token)){
			$user = JWT::decode(trim($token, '"'));
			if(!is_null($user)){
				$acceso = null;
				foreach ($user as $key) {
					$acceso = $key->ACCESO ;
				}
				return $acceso;
			}else{
				return 0;
			}
		}

		if(!isset($_SESSION['ACCESO']) && is_null($token)){
			return 0;
		}
	}


	function getIdentificacionUser($token = null){
	
		if(isset($_SESSION['IDENTIFICACION'])){
			return $_SESSION['IDENTIFICACION'];
		}

		if(!is_null($token)){
			$user = JWT::decode(trim($token, '"'));
			if(!is_null($user)){
				$id;
				foreach ($user as $key) {
					$id = $key->IDENTIFICACION ;
				}
				return $id;
			}else{
				return 0;
			}
		}

		if(!isset($_SESSION['IDENTIFICACION']) && is_null($token)){
			return 0;
		}
	}

	function getCedulaUser($token = null){
		global $mysqli;
		$numero=getIdentificacionUser($token);
		if($numero){
			$sql=$mysqli->query("SELECT USUARI_Identific_b FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_ConsInte__b={$numero}");
			if($sql && $sql->num_rows == 1){
				$sql=$sql->fetch_object();
				if(!is_null($sql->USUARI_Identific_b) && $sql->USUARI_Identific_b != ''){
					return $sql->USUARI_Identific_b;
				}
			}
		}
		return '';		
	}

	function getIdToken($token){
		$user = JWT::decode(trim($token, '"'));
		if(!is_null($user)){
			//var_dump($user);
			$id;
			foreach ($user as $key) {
				$id = $key->IDENTIFICACION ;
			}
			return $id;
		}else{
			return 0;
		}
	}

	function getNombreUser($token = null){
		if(isset($_SESSION['NOMBRES'])){
			return $_SESSION['NOMBRES'];
		}

		if(!is_null($token)){
			$user = JWT::decode(trim($token, '"'));
			if(!is_null($user)){
				$nombres;
				//var_dump($user);
				foreach ($user as $key) {
					$nombres = $key->NOMBRES ;
				}
				return $nombres;
			}else{
				return 0;
			}
		}

		if(!isset($_SESSION['NOMBRES']) && is_null($token)){
			return 0;
		}

	}


	function getProyectoUser($token = null){

		
		if(isset($_SESSION['PROYECTO_CRM'])){
			return $_SESSION['PROYECTO_CRM'];
		}

		if(!is_null($token)){
			$user = JWT::decode(trim($token, '"'));
			if(!is_null($user)){
				$nombres;
				//var_dump($user);
				foreach ($user as $key) {
					$nombres = $key->PROYECTO_CRM ;
				}
				return $nombres;
			}else{
				return 0;
			}
		}

		if(!isset($_SESSION['PROYECTO_CRM']) && is_null($token)){
			return 0;
		}
	}

	function getFechaUser($token = null){
		if(isset($_SESSION['FECHA'])){
			return $_SESSION['FECHA'];
		}

		if(!is_null($token)){
			$user = JWT::decode(trim($token, '"'));
			if(!is_null($user)){
				$fecha;
				//var_dump($user);
				foreach ($user as $key) {
					$fecha = $key->FECHA ;
				}
				return $fecha;
			}else{
				return 0;
			}
		}

		if(!isset($_SESSION['FECHA']) && is_null($token)){
			return 0;
		}
	}

    
    function NombreAgente($id_usuari){
		global $mysqli;
		global $BaseDatos;
		global $BaseDatos_systema;
        
        $strNameAgente="SELECT USUARI_Nombre____b FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_ConsInte__b = {$id_usuari} LIMIT 1";
        $strNameAgente=$mysqli->query($strNameAgente);
        if($strNameAgente && $strNameAgente->num_rows > 0 ){
            $strNameAgente=$strNameAgente->fetch_object();
            $strNameAgente=$strNameAgente->USUARI_Nombre____b;
        }else{
            $strNameAgente='N/A';
        }
        
        return $strNameAgente;
    }
	/*NBG 2020-10-21
	*Funcion para obtener los patrones de marcación del huesped
	*/
    function ObtenerPatron($id_huesped,$id_campan=null, $isTemplateWhatsapp = false){
		global $mysqli;       
        
        if(is_null($id_campan)){
            $sql=$mysqli->query("SELECT patron_validacion AS patron_regexp,patron, patron_ejemplo, codigo_antepuesto FROM dyalogo_telefonia.tipos_destino where id_huesped={$id_huesped} AND patron_validacion IS NOT NULL;");
        }elseif(is_numeric($id_campan) and $id_campan >0){
            $sql=$mysqli->query("select b.patron_validacion as patron_regexp,patron, patron_ejemplo, b.codigo_antepuesto from dyalogo_telefonia.pasos_troncales a join dyalogo_telefonia.tipos_destino b on a.id_tipos_destino=b.id where id_campana={$id_campan} and b.id_huesped={$id_huesped};");
        }

		if($isTemplateWhatsapp == true){
			$sql=$mysqli->query("SELECT patron_validacion AS patron_regexp,patron, patron_ejemplo, codigo_antepuesto FROM dyalogo_telefonia.tipos_destino where id_huesped={$id_huesped} AND (codigo_antepuesto IS NOT NULL AND codigo_antepuesto != '' AND codigo_antepuesto != 'null' AND codigo_antepuesto != 'NULL') AND patron_validacion IS NOT NULL;");
		}

        if($sql && $sql->num_rows > 0){
            $regexp= array();
			$patron= array();
			$patronEjemplo = array();
			$codigoPais = array();
            $i=0;
            while($row = $sql->fetch_assoc()) {
                $regexp[$i]=$row['patron_regexp'];
                $patron[$i]=$row['patron'];
                $patronEjemplo[$i] = $row['patron_ejemplo'];
				$codigoPais[$i] = $row['codigo_antepuesto'];
                $i++;
            }
            $result=array(
                'patron_regexp' =>$regexp,
                'patron' =>$patron,
                'patron_ejemplo' => $patronEjemplo,
				'codigo_pais' => $codigoPais
            );
            return json_encode($result);
        }else{
            return false;
        }      
    }

	//FN PARA VALIDAR LAS FECHAS DE LOS CAMPOS TIPO FECHA Y HORA DE LOS FORMULARIO
	function validateDate($date, $format = 'Y-m-d H:i:s'):bool
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
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