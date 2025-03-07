<?php
	session_start();
	require_once('../conexion.php');
	require_once('conexionSingleton.php');
	date_default_timezone_set('America/Bogota');
	$huesped = '';
	if(isset($_POST['huesped'])){
		$huesped = $_POST['huesped'];
	}

	if(isset($_SESSION['HUESPED'])){

		$singley 	= conexionSingleton::singleton();
		$Disponible = $singley->getCountDisponibles($_SESSION['HUESPED']);
		$Ocupado 	=	$singley->getCountOcupados($_SESSION['HUESPED']);
		$Inicial 	=  $singley->getCountInicial($_SESSION['HUESPED']);
		$Pausado 	=  $singley->getCountPausados($_SESSION['HUESPED']);

		echo json_encode(array(	
							'disponibles' => $Disponible['total'], 
							'ocupados' => $Ocupado['total'], 
							'inicial' => $Inicial['total'], 
							'pausado' => $Pausado['total']
						)
					);


	}
?>