<?php

namespace DataJourney\controllers;

use DataJourney\models\JourneyModel;

class JourneyController
{

    private $db;
    private $requestMethod;
    private $userId;
    private $idG;
    private $from;

    private $journeyModel;
    private $pasoModel;


    public function __construct($db, $requestMethod, $jsonData)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->idG = (isset($jsonData["idG"])) ? $jsonData["idG"] : null ;
        $this->userId = ((isset($jsonData["idUser"]))) ?  $jsonData["idUser"] : null;
        $this->from = ((isset($jsonData["from"]))) ?  $jsonData["from"] : null;

        $this->journeyModel = new JourneyModel($this->db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                if ($this->userId && $this->idG) {
                    $response = $this->getData();
                }
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getData()
    {

        $result = $this->journeyModel->getData($this->userId, $this->idG, $this->from);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
