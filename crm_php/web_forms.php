<?php
	if(isset($_GET['web']) || isset($_GET['web2'])){

		$version = 2;

		if(isset($_GET['web'])){
			$numero_form = base64_decode($_GET['web']);
			$version = 1;
		}

		if(isset($_GET['web2'])){
			$numero_form = base64_decode($_GET['web2']);
			// Dividimos lo que llega
			$web2 = explode('_', $numero_form);
			$numero_form = $web2[0];
		}

		include("conexion.php");

		$Lsql = "SELECT * FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$numero_form;
		$res_Lsql = $mysqli->query($Lsql);

		if($res_Lsql){
			
			if(isset($_GET['gestion'])){
				include ('formularios/G'.$numero_form.'/atender_caso.php');
			}else{
				if($version == 1){
					include ('formularios/G'.$numero_form.'/index.php');
				}

				if($version == 2){
					include ('formularios/G'.$numero_form.'/G'.$web2[0].'_WEB'.$web2[1].'.php');
				}
			}
			
		}else{
			echo "Se ha modificado la url del form, el formulario que intentas acceder no existe";
		}
	}else{
		echo "No hemos recibido ningun parametro, no se puede mostrar ningun formulario";
	}
	

?>