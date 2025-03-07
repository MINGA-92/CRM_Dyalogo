<?php



class ReporteAgenteController
{

    public function __construct(ReporteAgenteGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function proccessRequest(string $method, ?string $param): void
    {
        # proceseso todas la solicitudes

        // var_dump($param, $id);
        // var_dump("param", $param);

        if ($param === 'getcanal' && $_GET['intIdUsuario']) {
            # proceso la solicitud de canal
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestCanales($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequest($method);
        }

        if ($param === 'getultimagestion' && $_GET['intIdUsuario']) {
            # proceso la solicitud de la ultima gestion
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestUltimaGestion($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getclasificacion' && $_GET['intIdUsuario']) {
            # proceso la solicitud de clasificacion
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestClasificaciones($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getreintento' && $_GET['intIdUsuario']) {
            # proceso la solicitud de reintentos
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestReintentos($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getcampanas' && $_GET['intIdUsuario']) {
            # proceso la solicitud de campañas
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestCampanas($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getestrategia' && $_GET['intIdUsuario']) {
            # proceso la solicitud de estrategias
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestEstrategia($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getformulario' && $_GET['intIdUsuario'] && $_GET['strTipo']) {
            # proceso la solicitud de campañas
            // echo "obtengo el {$param} canal \n";
            $data = array(
                "intIdUsuario" => $_GET['intIdUsuario'] ?? null,
                "strTipo" => $_GET['strTipo'] ?? null
            );
            // echo json_encode($data);
            $this->processResourceRequestFormularios($method, $data);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getbasedatos' && $_GET['intIdUsuario']) {
            # proceso la solicitud de campañas
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestBaseDatos($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        /**CALIDAD */
        if ($param === 'getdatoprincipal' && $_GET['intIdUsuario']) {
            # proceso la solicitud de campañas
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestDatoPrincipal($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getdatosecundario' && $_GET['intIdUsuario']) {
            # proceso la solicitud de campañas
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestDatoSecundario($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
        }

        if ($param === 'getusuariocalifica' && $_GET['intIdUsuario']) {
            # proceso la solicitud de campañas
            // echo "obtengo el {$param} canal \n";
            $this->processResourceRequestUsuarioCalifica($method, $_GET['intIdUsuario']);
        } else {
            // $this->processCollectionRequestCampos($method);
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
                // $data = (array) json_decode(file_get_contents('php://input'), true);

                // $reportesAgente =  $this->gateway->getCanal($data['id']);

                echo json_encode(["message" => 'ok', "estado" => 'ok', "data" => $reportesAgente]);
                break;

            default:
                http_response_code(405);
                header('Allow: GET');
                break;
        }
    }

    private function processResourceRequestCanales(string $method, int $id): void
    {
        # code...

        $reportesAgente =  $this->gateway->getCanal($id);
        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
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


    private function processResourceRequestCampanas(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getCampanas($id);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }

    private function processResourceRequestEstrategia(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getEstrategia($id);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }


    private function processCollectionRequestCampos(string $method): void
    {
        # code...
        // echo "processCollectionRequest";
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


    // proceso la solicitud ultima gestion
    private function processResourceRequestUltimaGestion(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getUltimaGestion($id);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }

    // proceso la solicitud clasificacion
    private function processResourceRequestClasificaciones(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getClasificacion($id);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }

    // proceso la solicitud reintento
    private function processResourceRequestReintentos(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getReintento($id);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }


    // proceso la solicitud formularios gestion 
    private function processResourceRequestFormularios(string $method, array $data)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getFormulario($data);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }

    // proceso la solicitud formularios gestion 
    private function processResourceRequestBaseDatos(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getBaseDatos($id);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }

    // proceso la solicitud dato secundario calidad
    private function processResourceRequestDatoPrincipal(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getDatoPrincipal($id);

        // $reportesAgente =  $this->gateway->get($id);

        // echo empty($reportesAgente); 

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }


    // proceso la solicitud dato secundario calidad
    private function processResourceRequestDatoSecundario(string $method, int $id)
    {
        # code...
        // echo "processResourceRequestCampanas";

        $reportesAgente =  $this->gateway->getDatoSecundario($id);

        // $reportesAgente =  $this->gateway->get($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }

    // proceso la solicitud dato secundario calidad
    private function processResourceRequestUsuarioCalifica(string $method, int $id)
    {

        $reportesAgente =  $this->gateway->getUsuarioCalifica($id);

        ReporteAgenteController::processResponseRequest($reportesAgente, $method);
    }
}
