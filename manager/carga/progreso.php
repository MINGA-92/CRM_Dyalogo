<?php 
	session_start();	
	$total =$_SESSION['TOTALREGISTROS'];	
	$progreso= isset($_SESSION['progreso'])?$_SESSION['progreso']:0;
	echo json_encode(array('progreso'=>$progreso,'totalRegistros'=>$total));
	session_write_close();
?>