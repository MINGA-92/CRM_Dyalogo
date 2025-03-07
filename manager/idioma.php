<?php
	if(empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		$idioma = 'es';
	}else{
		$idioma = explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];	
	}
	
	switch ($idioma) {
		case 'en':
			include ('idiomas/text_en.php');
			break;
		
		case 'es':
			include ('idiomas/text_es.php');
			break;

		default:
			include ('idiomas/text_en.php');
			break;
	}
	
?>