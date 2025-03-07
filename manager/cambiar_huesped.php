<?php
	session_start();
	include ('pages/conexion.php');
    require_once('../helpers/parameters.php');
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$Lsql = "SELECT * FROM ".$BaseDatos_general.".huespedes  WHERE md5(concat('".clave_get."', id)) = '".$_POST['huesped']."'";    
        $res  = $mysqli->query($Lsql);
		while ($key = $res->fetch_object()) {
            $_SESSION['PROYECTO'] = strtoupper($key->nombre);
			$_SESSION['HUESPED']  = strtoupper($key->id);
			$logoHuesped = 'assets/img/Logo_blanco.png';
			if(file_exists("/etc/dyalogo/clientes/img_huespedes/".$key->id."/logo_".$key->id.".png") ){	
				$logoHuesped = '1';
			}
			$_SESSION['LOGO_HUESPED'] = $logoHuesped;
            $_SESSION['UNO'] = false;
        }
    	echo "1";
	}
?>