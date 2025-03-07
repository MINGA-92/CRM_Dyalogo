<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
    }

    $uri .= $_SERVER['HTTP_HOST'];
    $uri .= '/admin';
	header('Location: '.$uri.'/public/');
	exit;
?>
Something is wrong :-(
