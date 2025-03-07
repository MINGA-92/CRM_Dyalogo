<?php

include_once(__DIR__ . "/../models/ActividadActual.php");
include_once(__DIR__ . "/../models/ASITAR.php");
include_once(__DIR__ . "/../models/CAMPAN.php");
include_once(__DIR__ . "/./Dysingleton.php");



class AgentsController
{


    private $dySingleton;
    private $ActividadActualOBJ;
    private $ASISTAROBJ;

    public function __construct()
    {
        $this->ActividadActualOBJ = new ActividadActual();
        $this->ASISTAROBJ = new ASITAR();

        include_once(__DIR__ . "/./token.php");
        $this->dySingleton = new Dysingleton($token);
    }

    // Este metodo es para crear un objeto con la informacion actal de los agentes y las campañas -- El objeto es igual al de java
    public function calcAgentStatus()
    {

        $objJsonAgents = (object)[
            "objSerializar_t" => [],   "strEstado_t" => "ok",
            "strMensaje_t" => ""
        ];

        // Obtenemos la informacion actual de la tabla de actividad actual
        $actualActivity =  $this->ActividadActualOBJ->getAllActivity();

        if ($actualActivity) {

            // Necesitamos buscar la campaña actual

            $usuariCampans =  json_decode($this->getCampanUsuariSyngleton());

            if (isset($usuariCampans->obj)) {
                if (!is_null($usuariCampans->obj)) {
                    $usuariCampans = json_decode($usuariCampans->obj);
                }
            }

            if (is_array($usuariCampans)) {
                foreach ($actualActivity as $keyAgent => $objAgent) {

                    $indexUsuariCampan = array_search($objAgent->idUsuario, array_column($usuariCampans, "idAgent"));

                    if ($indexUsuariCampan) {

                        // Para la respuesta solo necesitamos los IDs de las campañas asociaddas, por lo cual fitramos el objeto origial
                        $objAgent->{"lstCampanasAsignadas_t"} = array_map(function($valCampan){ return $valCampan->idCampan; }, $usuariCampans[$indexUsuariCampan]->lstCampanasAsignadas_t);

                        if($idCampanActual = $this->getActualCampan($objAgent->campanaActual, $usuariCampans[$indexUsuariCampan]->lstCampanasAsignadas_t)){
                            $objAgent->{"intIdCampanaActual_t"} = $idCampanActual;
                        }
                    }

                    array_push($objJsonAgents->objSerializar_t, (object)$objAgent);
                }
            }
        }

        $this->dySingleton->saveInfo($objJsonAgents, "stateAge");

    }

    // Esta funcion guarda las campañas asociadas a todos los usuarios en un objeto esto con el fin de evitar tantas consultas
    public function saveCampanUsuari()
    {
        // Necesitamos obtener la campañas a las que el usuario esta asociado
        $objAgent = $this->ASISTAROBJ->getCampanUsuari();
        $this->dySingleton->saveInfo($objAgent, "campUsu");
    }


    // Para evitar hacer la consulta de las campañas asociadas del usuario entonces los obtenemos del singleton
    private function getCampanUsuariSyngleton()
    {

        $response = $this->dySingleton->sendCurl("get", null, null, "campUsu");

        if ($response[1] == 403) {
            $this->dySingleton->initSession_dysingleton();
            $response = $this->dySingleton->sendCurl("get", null, null, "campUsu");
        }

        if ($response[1] == 200) {
            return $response[0];
        }
    }


    
    // Esta funcion nos ayuda filtrando la campaña del usuario y dandonos el id correspondinte
    private function getActualCampan(string $nameCampanActual, array $objCampans)
    {
        $indexActualCampan =  array_search($nameCampanActual, array_column($objCampans, "nameCampan") );
        return (is_bool($indexActualCampan) && $indexActualCampan === false) ? 0 : $objCampans[$indexActualCampan]->idCampan;

    }

}
