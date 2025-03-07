<?php

include_once('../../conexion.php');
include_once('../../funciones.php');

function crearExpresionRegular($patrones){
    $regex = '';

    if($patrones->patron_regexp){
        foreach($patrones->patron_regexp as $val){
            if(!is_null($val) || $val != ""){
                $val = str_replace("'", "", $val);
                if($regex == ""){
                    $regex .= $val;
                }else{
                    $regex .= "|".$val;
                }
            }
        }
    }

    $regex = "/".$regex."/";

    return $regex;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Valido que las credenciales de auth esten bien
    if(isset($data['strUsuario_t']) && $data['strUsuario_t'] == 'crm'){

        if(isset($data['strToken_t']) && $data['strToken_t'] == 'D43dasd321'){

            $huesped = $data['intHuespedId_t'] ?? 0;

            // Si no llega huesped dejo pasar el registro?
            if($huesped === 0){
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    'strEstado_t' => 'fallo',
                    'strMensaje_t' => 'No se recibio el huesped',
                    'arrTelefono_t' => []
                ]);
                exit();
            }

            // Debo recibir un arreglo con los telefonos de lo contrario no puedo hacer nada
            if(!isset($data['arrTelefono_t']) || is_null($data['arrTelefono_t']) || !is_array($data['arrTelefono_t'])){
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    'strEstado_t' => 'fallo',
                    'strMensaje_t' => 'Debes enviarme un array de numeros telefonicos',
                    'arrTelefono_t' => []
                ]);
                exit();
            }

            // Obtengo los patrones
            $patrones = json_decode(ObtenerPatron($huesped));

            // creo la expresion regular que se implementara
            $regex = crearExpresionRegular($patrones);
            
            // Valido si es correcta
            $telefonosValidados = [];

            foreach($data['arrTelefono_t'] as $value){
                if(preg_match($regex, $value)){
                    $telefonosValidados[] = $value;
                }
            }

            header("HTTP/1.1 200 OK");
            echo json_encode([
                'strEstado_t' => 'ok',
                'strMensaje_t' => 'Se han validado todos los numeros',
                'arrTelefono_t' => $telefonosValidados
            ]);
            exit();
        }
    }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>