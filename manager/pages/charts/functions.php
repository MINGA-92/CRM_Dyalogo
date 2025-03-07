<?php
	session_start();
	include ('../conexion.php');
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		if(isset($_POST['getReportes'])){
			$Lsql = "SELECT UPPER(nombres_hojas) as nombre_hojas FROM ".$BaseDatos_general.".reportes_automatizados WHERE id = ".$_POST['id']." LIMIT 1";
            $res = $mysqli->query($Lsql);
            $key = $res->fetch_array();

            $consultas = explode(",", str_replace('"', '', $key['nombre_hojas']));

            for($i = 0; $i < count($consultas); $i++){
            	echo "<option value='".$i."'>".$consultas[$i]."</option>";	
            }
           
		}
	}