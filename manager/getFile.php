<?php 

    if(isset($_GET['logo_web']) && $_GET['logo_web']){

        $ruta = ($_GET['ruta']) ? $_GET['ruta'] : 'default';

        if($ruta != 'default'){

            if (strpos($ruta, '/Dyalogo/img_chat/') !== false) {

                if (file_exists($ruta)) {
                    $imgpath = $ruta;
                    $imginfo = getimagesize($imgpath);
                    $mimetype = $imginfo['mime'];
                    header('Content-type: ' . $mimetype);
                    readfile($imgpath);    
                } else {
                    //lo que debería hacer si no existe la imagen
                    //se puede mostrar otra imagen, o devolver un 404
                    http_response_code(404);
                    die();
                }

            }else{
                http_response_code(404);
                die();
            }
        }

    }

    if(isset($_GET['img']) && $_GET['img'] == 1){

        $nombre = $_GET['name'] ?? '';

        if($nombre != ''){

            $ruta = "/Dyalogo/img_texteditor/" . $nombre;
        
            if (file_exists($ruta)) {
                $imgpath = $ruta;
                $imginfo = getimagesize($imgpath);
                $mimetype = $imginfo['mime'];
                header('Content-type: ' . $mimetype);
                readfile($imgpath);    
            } else {
                //lo que debería hacer si no existe la imagen
                //se puede mostrar otra imagen, o devolver un 404
                http_response_code(404);
                die();
            }
        }

    }
    
    http_response_code(404);
    die();    
?>
