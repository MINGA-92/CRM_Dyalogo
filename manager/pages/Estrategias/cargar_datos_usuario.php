<?php
	session_start();
	include(__DIR__."/../conexion.php");
	echo file_get_contents("http://".$Api_Gestion."dyalogocbx/paginas/dd/dd-usu-est.jsf?tip=1&idEstrat=".$_GET['estrategia']."&idHuesped=".$_SESSION['HUESPED']);
?>