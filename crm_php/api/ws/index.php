<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);

include_once (__DIR__ . '/../../configuracion/configuracion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $data = json_decode(file_get_contents('php://input'), true);

    // Verifico que si hayan llegado campos
    if(count($data) === 0){
        responseError(400, 'Invalid request');
    }

    $arrMapValoresCampos = [];

    // De los campos que hay en el array busco los campos que empiecen con mapValCampo_
    foreach ($data as $key => $value) {
        if(strpos($key, 'mapValCampo_') === 0){
            // Al existir recorto el campo
            $nombre = explode('_', $key);
            $arrMapValoresCampos[$nombre[1]] = $value;
        }
    }

    $body = [
        "strUsuario_t" => $data['strUsuario_t'],
        "strToken_t" => $data['strToken_t'],
        "intCodigoAccion_t" => $data['intCodigoAccion_t'],
        "booValidaConRestriccion_t" => $data['booValidaConRestriccion_t'],
        "intIdEstpas_t" => $data['intIdEstpas_t'],
        "strCamposLlave_t" => $data['strCamposLlave_t'],
        "strOrigenLead_t" => $data['strOrigenLead_t'],
        "mapValoresCampos_t" => $arrMapValoresCampos
    ];
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $URL_ADDONS.':8080/dy_servicios_adicionales/svrs/crm/procesarLead',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json'
        ),
    ));

    $response = curl_exec($curl);

    // Verificamos si la respuesta del curl fue exitosa o erronea
    if(curl_errno($curl)){
        // Error en la solicitud
        responseError(400, 'Error en la solicitud cURL: ' . curl_error($curl));
        curl_close($curl);
        exit;
    }

    // Obtener información sobre la solicitud realizada, como el código de estado HTTP
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if($httpCode < 200 || $httpCode >= 300){
        // La solicitud no fue exitosa
        responseError(400, 'La solicitud falló con el código de estado HTTP: ' . $httpCode);
        curl_close($curl);
        exit;
    }

    responseSuccess(200, json_decode($response));
    curl_close($curl);

    exit;
}

header("HTTP/1.1 400 Bad Request");

function responseSuccess($status = 200, $message = '') {

    http_response_code($status);

    echo json_encode([
        "error" => false,
        "status" => $status,
        "body" => $message
    ]);
}

function responseError($status = 500, $message = 'Internal Server Error') {

    http_response_code($status);

    echo json_encode([
        "error" => true,
        "status" => $status,
        "body" => $message
    ]);
}

?>