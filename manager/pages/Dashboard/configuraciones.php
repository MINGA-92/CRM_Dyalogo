<?php
	session_start();
	$_SESSION['TAMANHO_CAMPAN'] = $_POST['cmbTamanhoEstrat'];
	$_SESSION['ORDEN_CAMPAN'] = $_POST['cmbOrdenCampan'];
	$_SESSION['ORDEN_USERS'] = $_POST['cmbOrdenAgents']; 

	if($_POST['valRuta'] == '0'){
		header('Location:/manager/index.php?page=dashboard');
	}elseif($_POST['valRuta'] == '1'){
		header('Location:/manager/index.php?page=dashEstrat&estrategia='.$_POST['estrategia'].'&huesped='.$_POST['huesped'].'');
	}
	
?>