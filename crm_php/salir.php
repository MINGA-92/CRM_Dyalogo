<?php
	session_start();
	if (isset($_SESSION["QUALITY"])) {
		$QUALITY = $_SESSION["QUALITY"];
	}else{
		$QUALITY = 0;
	}
	$_SESSION = [];
	session_destroy();
	header('Location: login.php?quality='.$QUALITY);
?>