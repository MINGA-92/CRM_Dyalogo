<?php 

function controller_autoload($classname){
    $booValido=false;
    $rutas[] = "agendador/controllers/{$classname}.php";
    $rutas[] = "visor/controllers/{$classname}.php";
    $rutas[] = "dyGestion/controllers/{$classname}.php";
    $rutas[] = "viewsGenerator/controllers/{$classname}.php";
    $rutas[] = "TiempoReal/controllers/{$classname}.php";
    $rutas[] = "dyCpEstrat/controllers/{$classname}.php";
    $rutas[] = "dyForms/controllers/{$classname}.php";

    foreach ($rutas as $ruta) {
        if(file_exists($ruta)){
            if(include $ruta){
                $booValido=true;
                break;
            }
        }
    }

    if(!$booValido){
        showResponse();
    }
}
spl_autoload_register('controller_autoload');