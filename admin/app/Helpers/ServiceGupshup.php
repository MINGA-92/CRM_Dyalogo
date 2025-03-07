<?php
namespace App\Helpers;

class ServiceGupshup{

    private function conexionApi($url, $headers, $method, $data = null){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            )
        );

        if (!is_null($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($curl);

        $error = curl_error($curl);

        curl_close($curl);

        if($response){
            return $response;
        }else if($error){
            return $error;
        }
    }

    public function getAllTemplates($nombreCuenta, $apiKey){

        $url = 'https://api.gupshup.io/sm/api/v1/template/list/'.$nombreCuenta;

        $headers = array(
            'apikey: '.$apiKey
        );

        $response = $this->conexionApi($url, $headers, 'GET');

        if($response){
            return json_decode($response);
        }

        return ["status" => "error", "message" => "Se genero un error al obtener la lista de templates"];
    }

    public function saveTemplate($plantilla, $cuentaWhatsapp){

        $url = 'partner.gupshup.io/partner/app/'.$cuentaWhatsapp->app_id.'/templates';

        $headers = array(
            'Connection: keep-alive',
            'token: '.$cuentaWhatsapp->app_secret,
            'Content-Type: application/x-www-form-urlencoded'
        );

        // Dejamos las variables por defecto
        $dataArr = [
            'elementName' => $plantilla->nombre,
            'languageCode' => $plantilla->idioma,
            'category' => $plantilla->categoria,
            'templateType' => $plantilla->tipo,
            'vertical' => $plantilla->etiqueta,
            'content' => $plantilla->texto,
            'example' => $plantilla->texto_ejemplo
        ];

        // Validamos el tipo y el header
        if($plantilla->tipo == 'TEXT'){
            if(isset($plantilla->cabecera) && !is_null($plantilla->cabecera) && $plantilla->cabecera != ''){
                $dataArr['header'] = $plantilla->cabecera;
            }
        }

        if($plantilla->tipo == 'IMAGE' || $plantilla->tipo == 'VIDEO' || $plantilla->tipo == 'DOCUMENT'){
            // Aqui deberia cargar la imagen, video, documento de prueba que se genera por un consumo
            // $dataArr['exampleMedia'] = 'This is the handleId.';
            // $dataArr['enableSample'] = true;
        }

        // Validamos que el footer tenga algo por lo menos
        if(isset($plantilla->pie_pagina) && !is_null($plantilla->pie_pagina) && $plantilla->pie_pagina != ''){
            $dataArr['footer'] = $plantilla->pie_pagina;
        }

        // Validamos los botones
        if(isset($plantilla->botones) && !is_null($plantilla->botones) && $plantilla->botones != ''){
            $dataArr['buttons'] = json_decode($plantilla->botones);
        }

        $data = http_build_query($dataArr);

        $response = $this->conexionApi($url, $headers, 'POST', $data);

        if($response){
            return json_decode($response);
        }

        return ["status" => "error", "message" => "Se genero un error al crear el template en gupshup"];
    }

    public function deleteTemplate($plantilla, $cuentaWhatsapp){

        $url = 'partner.gupshup.io/partner/app/'.$cuentaWhatsapp->app_id.'/template/'.$plantilla->nombre;

        $headers = array(
            'Authorization: '.$cuentaWhatsapp->app_secret
        );

        $response = $this->conexionApi($url, $headers, 'DELETE');

    }

}
