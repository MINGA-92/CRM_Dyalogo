<?php

class Dysingleton
{

    private $token;
    private $alias;
    private $hostname;

    public function __construct($token)
    {
        include(__DIR__."/../../../../configuracion.php");
        $this->hostname = $ALIAS_SERVER;
        $this->token = $token;
    }


    public function initSession_dysingleton()
    {

        echo "Se obtiene el token ya que el actual es invalido \n";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://singleton.cr.dyalogo.cloud/api/auth/signup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "usr" : "test1",
                                        "pwd" : "test"
                                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        $this->token = $response->token;

        $content = '
        <?php 
        $token = "'.$this->token.'";';


        $fp = fopen(__DIR__.'/./token.php', 'w');
        fwrite($fp, $content);
        fclose($fp);

    }


    public function sendCurl($method, $idHuesped = null, $objData = null, $typeAlias = null)
    {

        switch($typeAlias){
            case "colas":
                $this->alias = $this->hostname."_colas";
                break;
            case "campUsu":
                $this->alias = $this->hostname."_campU";
                break;
            case "stateAge":
                $this->alias = $this->hostname."_stAg";
                break;
            default:
                $this->alias = $this->hostname."_tr_h".$idHuesped;
                break;
        }
       

        $data = "";

        switch($method){

            case "add":
            case "update":
                $data = '{
                            "alias" : "'.$this->alias.'",
                            "value": '.json_encode(json_encode($objData)).'
                        }';
                break;

            case "get":
                $data = '{
                            "alias" : "'.$this->alias.'"
                        }';
                break;
            
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://singleton.cr.dyalogo.cloud/api/{$method}/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer {$this->token}",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return [$response, $http_code];
    }


    // Esta funcion guarda la informacion en el singleton
    public function saveInfo($objData, $typeAlias, $idHuesped = null)
    {

        $response = $this->sendCurl("update", $idHuesped, $objData, $typeAlias);

        // SI ME RESPONDE CON UN 403 QUIERE DECIR QUE EL TOKEN NO ES VALIDO
        if ($response[1] == 403) {
            $this->initSession_dysingleton();
            $response = $this->sendCurl("update", $idHuesped, $objData, $typeAlias);
        }

        if ($response[1] == 200) {
            $message = json_decode($response[0]);
            echo ("Respuesta Webservice: {$message->msj}\n");
            if (isset($message->msj) || is_null($message->msj)) {
                if ($message->msj != null) {
                    $finalMessage = "SE GUARDO EL VALOR CORRECTAMENTE \n";
                    echo $finalMessage;
                } else {
                    // SI LA RESPUESTA ME DICE QUE EL OBJ ES NULL QUIERE DECIR QUE NO EXISTE LA LLAVE
                    $response = $this->sendCurl("add", $idHuesped, $objData, $typeAlias);
                    $message = json_decode($response[0]);
                    if ($message->msj != null) {
                        $finalMessage = "SE CREO EL VALOR CORRECTAMENTE \n";
                        echo $finalMessage;
                    }
                }
            }
        } else {
            echo ("Respuesta Webservice: {$response[0]} \n");
        }

        echo ("Objeto guardado: \nAlias = " . $this->alias . "\n");
        echo ("Objeto Final: \n" . json_encode(json_encode($objData)) . "\n");
        
    }


    public function getInfo(string $typeAlias = null, int $idHuespd = null){
        $response = $this->sendCurl("get", $idHuespd, null, $typeAlias);

        if ($response[1] == 403) {
            $this->initSession_dysingleton();
            $response = $this->sendCurl("get", $idHuespd, null, $typeAlias);
        }
    
        if ($response[1] == 200) {
            header('Content-Type: application/json; charset=utf-8');
            return $response[0];
        }
    }
}
