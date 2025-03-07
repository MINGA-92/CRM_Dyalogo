<?php

namespace Dyalogo\Script\controllers;

use Dyalogo\Script\models\Models;
use Dyalogo\Script\controllers\PregunController;

class Controllers
{
    public function __construct(Models $models)
    {

        $this->models = $models;
    }

    public function proccessRequest(string $method, ?string $param): void
    {
        # code...
        if ($param === 'getRepetidos') {
            # proceso la solicitud de canal
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestObtenerRepetidos($method, $param);
        } else {
            // $this->processCollectionRequest($method);
        }

        # code...
        if ($param === 'depurarRegistros') {
            # proceso la solicitud de depurar registros
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestDepurarRegistros($method, $param);
        } else {
            // $this->processCollectionRequest($method);
        }

        if ($param === 'getPregun') {
            # proceso la solicited de obtener los datos de pregun

            $this->processResourceRequestObtenerPregun($method, $param);
        }
    }




    // funcion encargada de procesar la solictud y responder
    protected static function processResponseRequest(array $data, string $method): void
    {
        # code...
        if (!$data && empty($data)) {
            # code...
            // http_response_code(404);
            echo json_encode(["message" => 'No hay datos', "estado" => 'fallo', "data" => $data]);
            return;
        }

        switch ($method) {
            case 'GET':

                echo json_encode(["message" => 'ok', "estado" => 'ok', "data" => $data]);
                break;

            default:
                http_response_code(405);
                header('Allow: GET');
                break;
        }
    }



    private function processResourceRequestObtenerRepetidos(string $method, bool $param): void
    {
        # obtengo todos los repetidos de la base de datos

        $repetidos = $this->models->obtenerRepetidos($_GET['strEstrategia'], $_GET['strCampoLlave']);

        Controllers::processResponseRequest($repetidos, $method);
    }

    private function processResourceRequestDepurarRegistros(string $method, bool $param): void
    {
        # obtengo todos los repetidos de la base de datos

        $repetidos = $this->models->depurarDuplicados($_GET['strEstrategia'], $_GET['strCampoLlave']);

        Controllers::processResponseRequest($repetidos, $method);
    }

    private function processResourceRequestObtenerPregun(string $method, bool $param): void
    {
        # obtengo todos los repetidos de la base de datos

        $controller = new PregunController($this->models);

        $pregun = $controller->obtenerPregun($_GET['intIdBD']);

        Controllers::processResponseRequest($pregun, $method);
    }
}
