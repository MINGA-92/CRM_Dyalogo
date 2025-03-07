<?php

if (isset($_GET["guion"]) && isset($_GET["file"])) {

    (string) $guion=$_GET['guion'];
    (array) $arrRutas=array("/Dyalogo/tmp/G{$guion}/","/Dyalogo/tmp/adjuntos/G{$guion}/","/mnt/disk/grabaciones/adjuntos/{$guion}/","/mnt/disk/grabaciones/adjuntos/G{$guion}/","dy_grabaciones/adjuntos/{$guion}/");
    (array) $arrFormatos=array("PNG","png","pdf","doc","docx","pdf","jpeg","jpg","xls","xlsx");
    foreach( $arrRutas as  $ruta){
        (string) $archivo=$ruta.$_GET["file"];
        if (is_file($archivo)) {
            $size = strlen($archivo);
            if ($size>0) {
                $ext=pathinfo($archivo, PATHINFO_EXTENSION);
                if(in_array($ext,$arrFormatos)){
                    $nombre=basename($archivo);
                    $masa = filesize($archivo);
                    header("Content-Description: File Transfer");
                    header("Content-type: application/force-download");
                    header("Content-disposition: attachment; filename=".$nombre);
                    header("Content-Transfer-Encoding: binary");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate");
                    header("Pragma: public");
                    header("Content-Length: " . $masa);
                    ob_clean();
                    flush();
                    readfile($archivo);
                }
            }
        }
    }
    // SI NO SE ENCONTRO EL ARCHIVO
    header("HTTP/1.1 404 Not Found"); 

}else{
    header("HTTP/1.1 404 Not Found");
}