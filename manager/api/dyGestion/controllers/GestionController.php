<?php

require_once __DIR__.'/../models/Gestion.php';
class GestionController{
    
    public function addManagement():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $intIdCampanaCrm=isset($data['intIdCampanaCrm_t']) && is_numeric($data['intIdCampanaCrm_t']) ? $data['intIdCampanaCrm_t'] : false;
            $strCanal=isset($data['strCanal_t']) ? $data['strCanal_t'] :false;
            $strOrigen=isset($data['strOrigen_t']) ? $data['strOrigen_t'] :false;
            $intSentido=isset($data['intSentido_t']) ? $data['intSentido_t'] :false;
            $intMiembro=isset($data['intCodMiembro_t']) && is_numeric($data['intCodMiembro_t']) ? $data['intCodMiembro_t'] :false;
            $intAgente=isset($data['intAgente_t']) ? $data['intAgente_t'] :false;
            $strIdComunicacion=isset($data['strIdComunicacion_t']) ? $data['strIdComunicacion_t'] :false;
            $strDatoContacto=isset($data['strDatoContacto_t']) ? $data['strDatoContacto_t'] :'NULL';

            if($intIdCampanaCrm && $strCanal && $strOrigen && $intSentido && $intAgente && $intMiembro && $strIdComunicacion){
                (Object)$gestion = new Gestion();
                $gestion->setIdCampanaCrm($intIdCampanaCrm);
                $gestion->setstrCanal($strCanal);
                $gestion->setstrOrigen($strOrigen);
                $gestion->setintSentido($intSentido);
                $gestion->setintMiembro($intMiembro);
                $gestion->setintAgente($intAgente);
                $gestion->setstrIdComunicacion($strIdComunicacion);
                $gestion->setstrDatoContacto($strDatoContacto);
                $response=$gestion->saveGestion();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }
}