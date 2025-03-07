<?php
	session_start();
	
	ini_set('display_errors', 'On');
    
    ini_set('display_errors', 1);
	
	set_time_limit(0); //Establece el número de segundos que se permite la ejecución de un script.
	
	date_default_timezone_set('America/Bogota');
	
	$fecha_ac = isset($_POST['timestamp']) ? $_POST['timestamp']:0;
	
	$fecha_bd = null;

	require_once 'conexionSingleton.php';

	if(isset($_SESSION['HUESPED'])){
		
		$conexion = conexionSingleton::singleton();

		//var_dump($conexion);

		//echo $fecha_bd." Y ".$fecha_ac;
		
		while( $fecha_bd <= $fecha_ac )
		{

			$ro        = $conexion->realTimeUsers($_SESSION['HUESPED']);

			usleep(100000);//anteriormente 10000

			clearstatcache();

			$fecha_bd  = strtotime($ro['fecha_hora_cambio_estado']);
		}

		
		$row = $conexion->realTimeUsersDash($_SESSION['HUESPED']);
		
		$ar["timestamp"]          			= strtotime($row['fecha_hora_cambio_estado']);

		$ar["id_huesped"] 	 		  		= $row['id_huesped'];
		
		$ar["id_usuario"] 		        	= $row['id_usuario'];
		
		$ar["nombre_usuario"]          		= $row['nombre_usuario'];
		
		$ar["identificacion_usuario"]   	= $row['identificacion_usuario'];
		
		$ar["estado"] 	 		  			= $row['estado'];
		
		$ar["canal_actual"] 		    	= $row['canal_actual'];
		
		$ar["id_estrategia"]          		= $row['id_estrategia'];
		
		$ar["campana_actual"]          		= $row['campana_actual'];
		
		$ar["pausa"] 	 		  			= $row['pausa'];
		
		$ar["fecha_hora_cambio_estado"] 	= $row['fecha_hora_cambio_estado'];
		
		$ar["fecha_hora_inicio_gestion"]  	= $row['fecha_hora_inicio_gestion'];
		
		$ar["id_comunicacion"]          	= $row['id_comunicacion'];
		
		$ar["dato_principal"] 	 			= $row['dato_principal'];
		
		$ar["dato_secundario"] 		    	= $row['dato_secundario'];
		
		$fecha_bd 							= strtotime($row['fecha_hora_cambio_estado']);
		
		$dato_json   						= json_encode($ar);
		
		echo $dato_json;
	}

?>
