<?php

include("../manager/pages/conexion.php");
if (isset($_GET["callDatos"])) {
	$SC = $_POST["sc_h"];
	$G = $_POST["idg_h"];
	$H = $_POST["idh_h"];
	$data=[];

	$gestion = "SELECT * FROM ".$BaseDatos.".G".$SC." WHERE G".$SC."_ConsInte__b = ".$G;
	$gestion = $mysqli->query($gestion);
	if ($gestion->num_rows > 0) {
		$calificacion = "SELECT A.USUARI_Nombre____b AS A, A.USUARI_Nombre____b AS C, CALHIS.* FROM ".$BaseDatos_systema.".CALHIS
					     LEFT JOIN ".$BaseDatos_systema.".USUARI AS A ON CALHIS.CALHIS_ConsInte__USUARI_Age_b = A.USUARI_ConsInte__b
					     LEFT JOIN ".$BaseDatos_systema.".USUARI AS C ON CALHIS.CALHIS_ConsInte__USUARI_Cal_b = C.USUARI_ConsInte__b WHERE CALHIS_ConsInte__b = ".$H." AND CALHIS_IdGestion_b = ".$G;
		
		
		$calificacion = $mysqli->query($calificacion);
		if ($calificacion->num_rows > 0) {
			$i = 0;
			while ($key = $calificacion->fetch_object()) {
				$data[$i]['CALHIS_ConsInte__b'] = $key->CALHIS_ConsInte__b;
				$data[$i]['CALHIS_ConsInte__PROYEC_b'] = $key->CALHIS_ConsInte__PROYEC_b;
				$data[$i]['CALHIS_ConsInte__GUION__b'] = $key->CALHIS_ConsInte__GUION__b;
				$data[$i]['CALHIS_IdGestion_b'] = $key->CALHIS_IdGestion_b;
				$data[$i]['CALHIS_FechaGestion_b'] = $key->CALHIS_FechaGestion_b;
				$data[$i]['CALHIS_ConsInte__USUARI_Age_b'] = $key->A;
				$data[$i]['CALHIS_DatoPrincipalScript_b'] = $key->CALHIS_DatoPrincipalScript_b;
				$data[$i]['CALHIS_DatoSecundarioScript_b'] = $key->CALHIS_DatoSecundarioScript_b;
				$data[$i]['CALHIS_FechaEvaluacion_b'] = $key->CALHIS_FechaEvaluacion_b;
				$data[$i]['CALHIS_ConsInte__USUARI_Cal_b'] = $key->C;
				$data[$i]['CALHIS_Calificacion_b'] = $key->CALHIS_Calificacion_b;
				$data[$i]['CALHIS_ComentCalidad_b'] = $key->CALHIS_ComentCalidad_b;
				$data[$i]['CALHIS_ComentAgente_b'] = $key->CALHIS_ComentAgente_b;
				$data[$i]['CALHIS_ComentCalidad_b'] = $key->CALHIS_ComentCalidad_b;
				$i++;	
			}
		}else{
			$data[0]['error'] = 'error 1';	
		}

	}else{
		$data[0]['error'] = 'error 2';
	}

	echo json_encode($data);
}

if(isset($_GET["ConsultarAudio"])){
	$IdGuion= $_POST["IdGuion"];
	$IdGestion= $_POST["IdGestion"];

	$ConsultaAudio = "SELECT G".$IdGuion."_LinkContenido AS UrlAudio FROM ".$BaseDatos.".G".$IdGuion." WHERE G".$IdGuion."_ConsInte__b = ".$IdGestion.";";
	$Result = $mysqli->query($ConsultaAudio);
	$UrlAudio = $Result->fetch_array();
	echo $UrlAudio["UrlAudio"];
}

if (isset($_GET["UPDATE"])) {
	$SC = $_POST["sc_h"];
	$G = $_POST["idg_h"];
	$H = $_POST["idh_h"];
	$comentario = $_POST["comentario"];

	$UPDATE = "UPDATE ".$BaseDatos_systema.".CALHIS SET CALHIS_ComentAgente_b = '".$comentario."' WHERE CALHIS_ConsInte__b = ".$H;
	if ($mysqli->query($UPDATE)) {
		$campEstadoCalidad = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$SC." AND PREGUN_Texto_____b = 'ESTADO_CALIDAD_Q_DY';";
		$campEstadoCalidad = $mysqli->query($campEstadoCalidad);
		$campEstadoCalidad = $campEstadoCalidad->fetch_object();

		$campComAgCalidad = "SELECT PREGUN_ConsInte__b AS id FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$SC." AND PREGUN_Texto_____b = 'COMENTARIO_AGENTE_Q_DY';";
		$campComAgCalidad = $mysqli->query($campComAgCalidad);
		$campComAgCalidad = $campComAgCalidad->fetch_object();

		$upGestion = "UPDATE ".$BaseDatos.".G".$SC." SET G".$SC."_C".$campEstadoCalidad->id." = -202, G".$SC."_C".$campComAgCalidad->id." = '".$comentario."' WHERE G".$SC."_ConsInte__b = ".$G;
		$upGestion = $mysqli->query($upGestion);

		echo "1";
	}else{
		echo "0";
	}
}

?>
