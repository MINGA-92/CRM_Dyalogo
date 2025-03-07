<?php

class UsuariosController
{

    public function __construct(UsuariosGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function proccessRequest(string $method, ?string $param): void
    {
        # proceseso todas la solicitudes

        // var_dump($param, $id);

        // var_dump($param);

        if ($param === 'true') {
            # proceso la solicitud de canal
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestActulizarPassUsuarios($method, $param);
        } else {
            // $this->processCollectionRequest($method);
        }
    }


    // funcion encargada de procesar la solictud y responder
    private static function processResponseRequest(array $reportesAgente, string $method)
    {
        # code...
        if (!$reportesAgente && empty($reportesAgente)) {
            # code...
            // http_response_code(404);
            echo json_encode(["message" => 'No hay datos', "estado" => 'fallo', "data" => $reportesAgente]);
            return;
        }

        switch ($method) {
            case 'GET':

                echo json_encode(["message" => 'ok', "estado" => 'ok', "data" => $reportesAgente]);
                break;

            default:
                http_response_code(405);
                header('Allow: GET');
                break;
        }
    }


    private function processResourceRequestActulizarPassUsuarios(string $method, bool $param): void
    {
        # code...

        $arrUsuarios =  $this->gateway->getAllUsuarios($param);
        $this->gateway->updateUsuios($arrUsuarios);

        UsuariosController::processResponseRequest($arrUsuarios, $method);
    }
}
