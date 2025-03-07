<?php

include_once(__DIR__.'/configuracion.php');

// Este archivo se usa para descargar por ahora los archivos adjutos que se definen 
// desde la bola de salida de email

if(isset($_GET['file']) && !empty($_GET['file'])){

    $pathAdjuntos = "/home/dyalogo/adjuntos/";
    
    $fileName = basename($_GET['file']);
    $filePath = $pathAdjuntos.$fileName;

    // Si lo que estams descargando es un achivo de css del webform
    if(isset($_GET['type']) && $_GET['type'] == 'webform'){
        
        $pathAdjuntos = "/var/www/html/";
        $fileName = $_GET['file'];

        $filePath = $pathAdjuntos.$fileName;

        $aux = explode('/', $fileName);
        $fileName = end($aux);
    }


    if(!empty($fileName) && file_exists($filePath)){

        // Defino las cabeceras
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        // Read the file
        readfile($filePath);
        exit;
    }else{
        echo 'El archivo no existe.';
    }
}

if(isset($_GET['bot_file']) && !empty($_GET['bot_file'])){

    $path = $URL_ADJUNTOS.$_GET['h'].'/bot';

    $fileName = $_GET['bot_file'];

    $filePath = $path.'/'.$fileName;

    echo $filePath;

    if(file_exists($filePath)){

        $imginfo = getimagesize($filePath);
        $mimetype = $imginfo['mime'];
        $size = filesize($filePath);
        header("Content-Description: File Transfer");
        header('Content-type: ' . $mimetype);
        header("Content-disposition: attachment; filename=".$fileName);
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . $size);
        ob_clean();
        flush();

        // Read the file
        readfile($filePath);
        exit;
    }

    // header("HTTP/1.1 404 Not Found"); 
}

// header("HTTP/1.1 404 Not Found"); 

?>