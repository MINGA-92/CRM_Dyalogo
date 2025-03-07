<?php
include_once(__DIR__ . "/./Dysingleton.php");

class TiempoRealController
{


    private $dySingleton;

    public function __construct()
    {
        $this->dySingleton = new Dysingleton();
    }


    // ESTE METODO OBTIENE LAS METRICAS DE LAS COLAS

    public function getColas(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $data = json_decode($this->dySingleton->getInfo("colas"));
        $response = [];
        $response['estado'] = 'error';
        $response['mensaje'] = [];

        if (isset($data->obj)) {
            if (!is_null($data->obj)) {

                $response["mensaje"] = $data->obj;
                $response["estado"] = "OK";
            }
        }


        showResponse($response['mensaje'], $response['estado'], 200, "200 OK");
    }


    // ESTE METODO OBTIENE LAS METRICAS PESADAS DEL TIEMPO REAL

    public function getMetricas(): void
    {

        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'], 'POST');
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data) {

            $valid_parameters = false;
            $booUNO = null;
            $booUNO = isset($data['UNO']) && is_bool($data['UNO']) ? $data['UNO'] : false;
            $arrHUESPED = isset($data['HUESPED']) ? $data['HUESPED'] : false;

            // Dependiendo el numero de huespedes tengo que validar como llega la variable
            if ($booUNO !== null ) {
                if($booUNO === true){
                    if (is_numeric($arrHUESPED))  $valid_parameters = true;
                }else{
                    if (is_array($arrHUESPED))  $valid_parameters = true;
                }
               
            }

            if ($valid_parameters) {

                (array)$response = array();
                $response['estado'] = 'error';

                if ($booUNO) {

                    $data = json_decode($this->dySingleton->getInfo(null, $arrHUESPED));

                    if (isset($data->obj)) {
                        if (!is_null($data->obj)) {
                            $response['mensaje'] = [json_decode($data->obj)];
                        }
                    }

                } else {
                    $allData = [];
                    foreach ($arrHUESPED as $idHuesped) {
                        $dataMet = json_decode($this->dySingleton->getInfo(null, $idHuesped));
                        if (isset($dataMet->obj)) {
                            if (!is_null($dataMet->obj)) {
                                array_push($allData, json_decode($dataMet->obj));
                            }
                        }
                    }
                    $response['mensaje'] = $allData;
                }

                showResponse($response['mensaje'],$response['estado'],200,"200 OK");

            }
        }

        showResponse("Parametros incompletos o invalidos");
    }

    // ESTE METODO OBTIENE LOS ESTADOS DE LOS AGENTES

    function getAgents()
    {

        (array)$response = array();
        $response['estado'] = 'error';
        

        $dySingleton = new Dysingleton();

        $data = json_decode($dySingleton->getInfo("stateAge", null));

        if (isset($data->obj)) {
            if (!is_null($data->obj)) {
                $response["mensaje"] = json_decode($data->obj);
                $response['estado'] = 'ok';
            }
        }

        showResponse($response['mensaje'],$response['estado'],200,"200 OK");
    }
}
