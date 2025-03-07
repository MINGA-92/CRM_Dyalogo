<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);

require_once "pages/conexion.php";

// FUNCIÓN QUE VALIDA SI EXISTE EL DIRECTORIO CONFIGURADO EN LA VARIABLE GLOBAL, SI NO EXISTE SE CREA EL DIRECTORIO
function validaRutaG():bool
{
    global $URL_ADJUNTOS;

    (bool) $response=true;
    if(!is_dir($URL_ADJUNTOS)){
        if(!mkdir($URL_ADJUNTOS, 0777, true)){
            $response=false;
        }
    }

    return $response;
}

// FUNCIÓN QUE CREA LA CARPETA DE CADA HUESPED DENTRO DEL DIRECTORIO GLOBAL
function creaCarpetaHuesped(int $idHuesped):bool
{
    global $URL_ADJUNTOS;

    // VALIDAR SI YA EL DIRECTORIO EXISTE
    (bool) $response=true;
    if(!is_dir($URL_ADJUNTOS."/".$idHuesped."/")){
        if(!mkdir($URL_ADJUNTOS."/".$idHuesped."/", 0777, true)) {
            $response=false;
            echo "<h1>Carpeta del huesped <strong>{$idHuesped}</strong> NO se pudo crear</h1>";
        }else{
            echo "<h1>Carpeta del huesped <strong>{$idHuesped}</strong> creada con exito</h1>";
        }
    }

    return $response;
}

// FUNCIÓN QUE CREA LA CARPETA DE CADA FORMULARIO DENTRO DEL DIRECTORIO GLOBAL/CARPETAHUESPED/
function creaCarpetaG(int $idHuesped, int $idG):bool
{
    global $URL_ADJUNTOS;

    // VALIDAR SI YA EL DIRECTORIO EXISTE
    (bool) $response=true;
    if(!is_dir($URL_ADJUNTOS."/".$idHuesped."/".$idG."/")){
        if(!mkdir($URL_ADJUNTOS."/".$idHuesped."/".$idG."/", 0777, true)) {
            $response=false;
            echo "<h2>Carpeta del formulario <strong>{$idG}</strong> No se pudo crear</h2>";
        }else{
            echo "<h2>Carpeta del formulario <strong>{$idG}</strong> creada con exito</h2>";
        }
    }

    return $response;
}

function countFilesRoute(string $ruta):bool
{
    (array) $arrFiles=@scandir($ruta);
    (bool) $boolResponse=false;
    if(count($arrFiles) > 2){
        $boolResponse=true;
    }

    return $boolResponse;
}

// FUNCIÓN QUE CONSULTA LOS CAMPOS DE LOS FORMULARIOS TIPO ADJUNTOS Y LLAMA A LAS FUNCIONES DE CREAR CARPETAS
function sqlAdjuntosG():array
{
    global $mysqli;
    (object) $sql=$mysqli->query("SELECT PREGUN_ConsInte__GUION__b,PREGUN_ConsInte__b,GUION__ConsInte__PROYEC_b FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.GUION_ ON PREGUN_ConsInte__GUION__b=GUION__ConsInte__b WHERE PREGUN_Tipo______b=15 AND GUION__ConsInte__PROYEC_b IS NOT NULL");

    (array) $arrIdGuion=array();
    if($sql && $sql->num_rows > 0){
        while((array) $row = $sql->fetch_object()){
            (int) $intHuesped = $row->GUION__ConsInte__PROYEC_b;
            (int) $intG = $row->PREGUN_ConsInte__GUION__b;
            if(creaCarpetaHuesped($intHuesped)){
                if(creaCarpetaG($intHuesped,$intG)){
                    array_push($arrIdGuion, array('huesped'=>$intHuesped, 'guion'=>$intG, 'campo'=>$row->PREGUN_ConsInte__b, 'fin'=>false, 'sqlUP'=>false));
                }
            }
        }
    }

    return $arrIdGuion;
}

// FUNCIÓN QUE RETORNA LA RUTA DE LOS ADJUNTOS EN CADA CAMPO DE LOS FORMULARIOS
function getRutaAdjuntosGuion(int $intIdG, int $campo):array
{
    global $mysqli;
    global $URL_ADJUNTOS;

    (array) $arrEstado=array();
    $arrEstado['adjuntos']=false;
    $arrEstado['ruta']='';

    (object) $sql=$mysqli->query("SELECT G{$intIdG}_C{$campo} AS ruta FROM DYALOGOCRM_WEB.G{$intIdG} WHERE G{$intIdG}_C{$campo} IS NOT NULL AND G{$intIdG}_C{$campo} NOT LIKE '{$URL_ADJUNTOS}%' AND  G{$intIdG}_C{$campo} LIKE '%/%' LIMIT 1");
    if($sql && $sql->num_rows > 0){
        $arrEstado['adjuntos']=true;
        $sql=$sql->fetch_object();
        (array) $arrRuta=explode('/',$sql->ruta);
        if(count($arrRuta) > 0){
            unset($arrRuta[count($arrRuta)-1]);
            $arrEstado['ruta']=implode("/", $arrRuta)."/";
        }
    }

    return $arrEstado;
}

function moveAdjuntos(array $arrGuiones):array
{
    global $URL_ADJUNTOS;

    (array) $arrProceso=array();
    $arrProceso['terminado']=true;
    foreach($arrGuiones as $index => $item){
        if(!$item['fin']){
            $arrProceso['terminado']=false;
            $arrGuiones[$index]['move']=false;
            $arrGuiones[$index]['fin']=true;
            (string) $rutaNueva=$URL_ADJUNTOS.$item['huesped']."/".$item['guion']."/";
            (array) $ruta=getRutaAdjuntosGuion($item['guion'],$item['campo']);
            if($ruta['adjuntos']){
                if($ruta['ruta'] != ''){
                    if(is_dir($rutaNueva)){
                        if(is_dir($ruta['ruta'])){
                            //MOVER LOS ARCHIVOS
                            if(countFilesRoute($ruta['ruta'])){
                                try {
                                    (string) $strMove="mv -n {$ruta['ruta']}/* {$rutaNueva}";
                                    exec($strMove, $output, $return_val);
                                    if ($return_val == 0) {
                                       echo "<h3>Se movieron con exito los adjuntos del formulario {$item['guion']} a la ruta '{$rutaNueva}'</h3>";
                                       $arrGuiones[$index]['move']=true;
                                       $arrGuiones[$index]['fin']=false;
                                       $arrGuiones[$index]['oldRuta']=$ruta['ruta'];
                                       $arrGuiones[$index]['newRuta']=$rutaNueva;
                                    } else {
                                       echo "<h3>No se movieron los adjuntos del formulario {$item['guion']}</h3><br>";
                                    }
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            }else{
                                echo "El directorio actual del formulario {$item['guion']} esta vacio"."<br>";
                            }
                        }else{
                            echo "El formulario {$item['guion']} no tiene directorio de adjuntos"."<br>";
                        }
                    }else{
                        echo "No se pueden mover archivos del formulario {$item['guion']}, la ruta nueva no es un directorio "."<br>";
                    }
                }else{
                    echo "<h1>¡¡¡ NO SE PUDO OBTENER LA RUTA ACTUAL DE LOS ADJUNTOS DEL FORMULARIO {$item['guion']} !!!"."<br>";
                }
            }
        }
    }

    $arrProceso['arrayG']=$arrGuiones;
    return $arrProceso;
}

function upBd(array $arrGuiones):array
{
    global $mysqli;

    foreach($arrGuiones as $index => $item){
        if($item['move']){
            (string) $strGuion="G{$item['guion']}";
            (string) $strCampo=$strGuion."_C{$item['campo']}";
            //HAY QUE ACTUALIZAR LA TABLA DE CADA GUION PARA CAMBIAR LA RUTA
            (object) $sql=$mysqli->query("UPDATE DYALOGOCRM_WEB.{$strGuion} SET {$strCampo}=REPLACE({$strCampo}, \"{$item['oldRuta']}\", \"{$item['newRuta']}\") WHERE {$strCampo} IS NOT NULL");
            echo "UPDATE DYALOGOCRM_WEB.{$strGuion} SET {$strCampo}=REPLACE({$strCampo}, \"{$item['oldRuta']}\", \"{$item['newRuta']}\") WHERE {$strCampo} IS NOT NULL";
            if($sql){
                if($mysqli->affected_rows > 0){
                    echo "<h3>Se cambio la ruta de {$mysqli->affected_rows} en el formulario {$strGuion} y campo {$strCampo}</h3><br>";
                    $arrGuiones[$index]['sqlUp']=true;
                }
            }else{
                echo "<h1>! Ocurrio un error al actualizar la ruta de los adjuntos del formulario {$strGuion} y campo {$strCampo}</h1><br>";
            }
        }else{

        }
    }

    return $arrGuiones;
}

function crearSh(array $arrGuiones)
{
    $fp = fopen("../recursos_clientes/arregloMasivoAdjuntos.sh" , "w");
    fputs($fp,'#!/bin/bash');
    // foreach ($arrGuiones as $key) {
    //     fputs($fp,'
    // echo Arreglando /var/www/html/crm_php/formularios/G'.$base["id"].'/G'.$base["id"].'_Busqueda_Manual.php
    // sed -i "/guardarStorage/d" /var/www/html/crm_php/formularios/G'.$base['id'].'/G'.$base['id'].'_Busqueda_Manual.php
    // sed -i "/funcioneslocalstorage.js/d" /var/www/html/crm_php/formularios/G'.$base['id'].'/G'.$base['id'].'_Busqueda_Manual.php');
    // }
}

function ejecutar(array $arrIsset=array()){
    if(count($arrIsset) == 0){
        if(validaRutaG()){
            (array) $arrGuionA=sqlAdjuntosG();
        }else{
            echo "<h1>¡¡¡ NO SE PUDO CREAR LA CARPETA PRINCIPAL DE LOS ADJUNTOS !!! -- VALIDAR PERMISOS DE ESA RUTA</h1>";
        }
    }else{
        $arrGuionA=$arrIsset;
    }

    if(count($arrGuionA) > 0){
        (array) $adjuntosMove=moveAdjuntos($arrGuionA);
        (array) $adjuntosUpSql=upBd($adjuntosMove['arrayG']);
        if(!$adjuntosMove['terminado']){
            ejecutar($adjuntosUpSql);
        }else{
            // crearSh($adjuntosUpSql);
            echo "<hr>".json_encode($adjuntosUpSql)."<hr>";
            echo "<h1>¡ PROCESO TERMINADO, YA PUEDE CERRAR ESTA PESTAÑA !</h1>";
        }
    }else{
        echo "<h1>¡¡¡ NO HAY ADJUNTOS EN EL SERVIDOR !!!<h1>";
    }
}

ejecutar();