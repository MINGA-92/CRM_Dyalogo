<?php

include_once('../../conexion.php');
include_once('../../funciones.php');

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
                    'objPatrones_t' => []
                ]);
                exit();
            }

            // Obtengo los patrones
            $patrones = json_decode(ObtenerPatron($huesped));

            header("HTTP/1.1 200 OK");
            echo json_encode([
                'strEstado_t' => 'ok',
                'objPatrones_t' => [
                    "codigo_pais" => $patrones->codigo_pais,
                    "expresion" => $patrones->patron_regexp
                ]
            ]);
            exit();
        }
    }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>