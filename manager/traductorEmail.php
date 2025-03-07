<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."/pages/conexion.php");

// Este es un script que traducira todos los valores

// Listo todos los pasos de sms
$sql = "SELECT CORREO_SALIENTE_ConsInte__b AS id, CORREO_SALIENTE_Nombre_b AS nombre, CORREO_SALIENTE_Para_b AS para, CORREO_SALIENTE_CC_b AS cc, CORREO_SALIENTE_CCO_b AS cco, CORREO_SALIENTE_Asunto_b AS asunto, CORREO_SALIENTE_Cuerpo_b AS cuerpo, ESTRAT_ConsInte_GUION_Pob AS poblacion  FROM DYALOGOCRM_SISTEMA.CORREO_SALIENTE
    JOIN DYALOGOCRM_SISTEMA.ESTPAS ON CORREO_SALIENTE_ConsInte__ESTPAS_b = ESTPAS_ConsInte__b
    JOIN DYALOGOCRM_SISTEMA.ESTRAT ON ESTPAS_ConsInte__ESTRAT_b = ESTRAT_ConsInte__b
WHERE CORREO_SALIENTE_Cuerpo_b IS NOT NULL AND ESTRAT_ConsInte_GUION_Pob IS NOT NULL;";

$res = $mysqli->query($sql);

if($res && $res->num_rows > 0){

    $i = 0;

    while($row = $res->fetch_object()){

        // Obtengo el array de variables a traducir
        $arrVariables = obtenerVariables($row->poblacion);
        
        //Empiezo la traduccion
        $para = $row->para;
        $cc = $row->cc;
        $cco = $row->cco;
        $asunto = $row->asunto;
        $cuerpo = $row->cuerpo;

        foreach ($arrVariables as $key => $value) {
            $para = str_replace('${'.$key.'}', '${'.$value.'}', $para);
            $cc = str_replace('${'.$key.'}', '${'.$value.'}', $cc);
            $cco = str_replace('${'.$key.'}', '${'.$value.'}', $cco);
            $asunto = str_replace('${'.$key.'}', '${'.$value.'}', $asunto);
            $cuerpo = str_replace('${'.$key.'}', '${'.$value.'}', $cuerpo);
        }

        // Ahora guardo el mensaje traducido
        $uSql = "UPDATE DYALOGOCRM_SISTEMA.CORREO_SALIENTE SET CORREO_SALIENTE_Para_b = '{$para}', CORREO_SALIENTE_CC_b = '{$cc}', CORREO_SALIENTE_CCO_b = '{$cco}', CORREO_SALIENTE_Asunto_b = '{$asunto}', CORREO_SALIENTE_Cuerpo_b = '{$cuerpo}' WHERE CORREO_SALIENTE_ConsInte__b = {$row->id}";
        $mysqli->query($uSql);

        echo "$i sql $uSql <br>";
        $i++;
    }
}


function obtenerVariables($bd){

    global $mysqli;
    global $BaseDatos_systema;

    $arrVariables = [];

    // Traigo la lista de campos de la base de datos
    $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE SECCIO_TipoSecc__b = 1 AND PREGUN_ConsInte__GUION__b = ".$bd;
    $res = $mysqli->query($sql);

    if($res && $res->num_rows > 0){

        while($row = $res->fetch_object()){

            $nombre = $row->nombre;

            $nombre = sanear_strings($nombre);
            $nombre = str_replace(' ', '_', $nombre);
            $nombre = substr($nombre, 0, 20);

            $arrVariables[$nombre] = 'G'.$bd.'_C'.$row->id;
        }
    }

    return $arrVariables;
}

function sanear_strings($string) { 

    $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
    $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
    $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
    $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
    $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
    $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); 
    //Esta parte se encarga de eliminar cualquier caracter extraño 
    $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string ); 

    return $string; 
}