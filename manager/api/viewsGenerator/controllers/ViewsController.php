<?php

require_once __DIR__.'/../models/Views.php';

class ViewsController{
    
    public function generateViews():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        header('Content-Type: application/json; charset=utf-8');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){

            $valid_parameters = false;

            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $intIdHuesped=isset($data['intIdHuesped']) && is_numeric($data['intIdHuesped']) ? $data['intIdHuesped'] : false;
            $strTypeView=isset($data['intTypeView']) && is_numeric($data['intTypeView']) ? $data['intTypeView'] : false;
            $intIdPaso=isset($data['intIdPaso']) && is_numeric($data['intIdPaso']) ? $data['intIdPaso'] : false;
            $intIdGuion=isset($data['intIdGuion']) && is_numeric($data['intIdGuion']) ? $data['intIdGuion'] : false;


            if($strTypeView){

                // Primero traducimos la variable para poder llamar los modelos
                switch ($strTypeView) {
                    case 1:
                        $strTypeView = "ACD";
                        break;
                    case 2:
                        $strTypeView = "Guion";
                        break;
                    case 3:
                        $strTypeView = "Adherencia";
                        break;
                    case 4:
                        $strTypeView = "Muestra";
                        break;

                    
                }

                if($strTypeView == "ACD"){
                    if($intIdHuesped && $intIdPaso) $valid_parameters = true;
                }else if ($strTypeView == "Guion"){
                    if($intIdHuesped && $intIdGuion) $valid_parameters = true;
                }else if ($strTypeView == "Adherencia"){
                    if($intIdHuesped) $valid_parameters = true;
                }else if ($strTypeView == "Muestra"){
                    if($intIdHuesped && $intIdPaso) $valid_parameters = true;
                }
            
    
                if($valid_parameters){
    
                    $nameModel = $strTypeView."Views";
    
                    require_once __DIR__.'/../models/'.$nameModel.'.php';
    
                    $views = new $nameModel();
                    
                    $views->setIdHuesped($intIdHuesped);
                    $views->setTypeView($strTypeView);
                    $views->setIdPaso($intIdPaso);
                    $views->setIdGuion($intIdGuion);
                    $response = $views->generateViews();
                    showResponse($response['mensaje'],$response['estado'],200,"200 OK");
                }
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }
}