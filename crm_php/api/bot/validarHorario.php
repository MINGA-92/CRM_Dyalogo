<?php

include_once('../../conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Validar horario por horario de chat
    if(isset($_GET['configChat'])){
        $data = json_decode(file_get_contents('php://input'), true);

        // Debe llegar el paso que se va a insertar y el registro que se insertaria
        if(!isset($data['chatId'])){
            header("HTTP/1.1 400 Bad Request");
            exit;
        }

        // traigo los horarios en la base de datos
        $sql = "SELECT * FROM dyalogo_canales_electronicos.dy_chat_horarios WHERE id_configuracion = {$data['chatId']}";
        $res = $mysqli->query($sql);

        $horarios = array();

        if($res && $res->num_rows > 0){
            while($row = $res->fetch_object()){
                $horarios[] = $row;
            }
        }

        // Traigo el dia actual
        date_default_timezone_set('America/Bogota');
        $fecha_actual = getdate();
        $dia = strtolower($fecha_actual['weekday']);

        $horarioEncontrado = null;
        $cod_dia = traducirCodigoDia($dia);

        // Miramos si el dia de hoy esta configurado en el horario
        foreach ($horarios as $dia) {
            if($dia->dia_inicial == $cod_dia){
                $horarioEncontrado = $dia;
                break;
            }
        }

        $horaValida = false;

        // Si no hay un dia configurado significa que no es un horario valido
        if(!is_null($horarioEncontrado)){
            // Valido si la fecha es correcta
            $horaValida = validarHora($horarioEncontrado->momento_inicial, $horarioEncontrado->momento_final);
        }

        echo json_encode([
            "estado" => "ok",
            "horarioValido" => $horaValida
        ]);
        exit;
    }

    // Validar por un rango de hora
    if(isset($_GET['rangoHora'])){

        $data = json_decode(file_get_contents('php://input'), true);

        // Debe llegar el paso que se va a insertar y el registro que se insertaria
        if(!isset($data['horaInicio'])){
            header("HTTP/1.1 400 Bad Request");
            exit;
        }

        if(!isset($data['horaFin'])){
            header("HTTP/1.1 400 Bad Request");
            exit;
        }

        $horaValida = validarHora($data['horaInicio'], $data['horaFin']);

        echo json_encode([
            "estado" => "ok",
            "horarioValido" => $horaValida
        ]);
        exit;
    }
}

function validarHora($horaInicio, $horaFin){

    date_default_timezone_set('America/Bogota');

    // Formateamos las horas
    $horaIngresada = DateTime::createFromFormat('H:i:s', date("H:i:s"));
    $horaInicio = DateTime::createFromFormat('H:i:s', $horaInicio);
    $horaFin = DateTime::createFromFormat('H:i:s', $horaFin);

    return $horaIngresada >= $horaInicio && $horaIngresada <= $horaFin;
}

function traducirCodigoDia($dia):int{

    $cod_dia = 0;

    switch ($dia) {
        case 'monday':
            $cod_dia = 1;
            break;
        case 'tuesday':
            $cod_dia = 2;
            break;
        case 'wednesday':
            $cod_dia = 3;
            break;
        case 'thursday':
            $cod_dia = 4;
            break;
        case 'friday':
            $cod_dia = 5;
            break;
        case 'saturday':
            $cod_dia = 6;
            break;
        case 'sunday':
            $cod_dia = 7;
            break;
    }

    return $cod_dia;
}