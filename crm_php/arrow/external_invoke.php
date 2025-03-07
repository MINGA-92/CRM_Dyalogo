<?php

include_once '../conexion.php';
include_once '../funciones.php';
include_once 'proceso.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data = json_decode(file_get_contents('php://input'), true);

    // Valido que las credenciales de auth esten bien
    if(isset($data['usuario']) && $data['usuario'] == 'crm'){

        if(isset($data['token']) && $data['token'] == 'D43dasd321'){

            if(isset($data['registro_id']) && !is_null($data['registro_id']) && $data['registro_id'] !== ''){

                if(isset($data['paso_id']) && !is_null($data['paso_id']) && $data['paso_id'] !== ''){

                    // $arrow = new Proceso;

                    // $arrow->run($data['paso_id'], $data['registro_id']);

                    DispararProceso($data['paso_id'], $data['registro_id']);
                    header("HTTP/1.1 200 OK");
                    echo json_encode(['message' => 'Proceso: registrado']);
                    exit();
                }

            }
        }
    }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>