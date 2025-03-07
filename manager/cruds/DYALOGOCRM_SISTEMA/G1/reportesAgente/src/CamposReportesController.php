<?php


class CamposReportesController
{

    public function __construct(CamposReportesGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function proccessRequest(string $method, ?bool $campos): void
    {
        // var_dump($campos);
        # code...

        if ($campos) {
            # code...
            $this->processCollectionRequest($method);
        } else {
            $this->processResourceRequest($method, $campos);
        }

        // if ($id == 'getcampos') {
        //     $this->processResourceRequestCampos($method, $id);
        // } else {
        //     $this->processCollectionRequestCampos($method);
        // }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        # code...


        $reportesAgente =  $this->gateway->get($id);

        if (!$reportesAgente) {
            # code...
            http_response_code(404);
            echo json_encode(["menssage" => 'Report not faund']);
            return;
        }
        switch ($method) {
            case 'GET':
                echo json_encode($reportesAgente);
                break;

            default:
                http_response_code(405);
                header('Allow: GET');
                break;
        }
    }

    private function processCollectionRequest(string $method): void
    {
        # code...
        switch ($method) {
            case 'GET':
                # code...
                echo json_encode($this->gateway->getAll());
                break;
            case 'POST':

                $data = (array) json_decode(file_get_contents('php://input'), true);
                var_dump($data);
                break;

            default:
                # code...
                http_response_code(405);
                header('Allow: GET, POST');
                break;
        }
    }


    private function processResourceRequestCampos(string $method, string $id)
    {
        # code...

        echo "processResourceRequestCampos";
    }

    private function processCollectionRequestCampos(string $method): void
    {
        # code...
        echo "processCollectionRequest";
        # code...
        switch ($method) {
            case 'GET':
                # code...
                // echo json_encode($this->gateway->getAll());
                break;
            case 'POST':

                // $data = (array) json_decode(file_get_contents('php://input'), true);
                // var_dump($data);
                break;

            default:
                # code...
                http_response_code(405);
                header('Allow: GET, POST');
                break;
        }
    }
}
