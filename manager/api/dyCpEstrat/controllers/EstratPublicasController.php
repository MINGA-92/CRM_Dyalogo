<?php

use Dyalogo\Script\models\Estrategia;

require_once __DIR__.'/../models/Estrategias.php';

class EstratPublicasController{

    public function getEstrategias():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (bool)$bpo = isset($data['bpo']) ? true : false;
            $estrat=new Estrategias($bpo);
            $response=$estrat->getEstrategias();
                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $response['mensaje'],
                    'response' => $response['estado']
                ];
                echo json_encode($data,$data['code']);
                exit();
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addEstrat():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strIdHuesped=isset($data['strHuesped_t']) &&  $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strEstratNombre=isset($data['strEstratNombre_t']) &&  $data['strEstratNombre_t'] != '' ? $data['strEstratNombre_t'] : false;
            (string)$strEstratComentari=isset($data['strEstratComentari_t']) &&  $data['strEstratComentari_t'] != '' ? $data['strEstratComentari_t'] : NULL;
            (string)$strEstratColor=isset($data['strEstratColor_t']) &&  $data['strEstratColor_t'] != '' ? $data['strEstratColor_t'] : false;
            (string)$strEstratIdGuion=isset($data['strEstratIdGuion_t']) &&  $data['strEstratIdGuion_t'] != '' ? $data['strEstratIdGuion_t'] : false;
            if($strIdHuesped && $strEstratNombre && $strEstratColor && $strEstratIdGuion){
                $estpas=new Estrategias;
                $estpas->setStrIdHuesped($strIdHuesped);
                $estpas->setStrEstratNombre($strEstratNombre);
                $estpas->setStrEstratComentari($strEstratComentari);
                $estpas->setStrEstratColor($strEstratColor);
                $estpas->setStrEstratIdGuion($strEstratIdGuion);
                $response=$estpas->addEstrat();
                // ENVIAR LA RESPUESTA
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");   
    }
    
    public function getEstpas():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strIdEstrat=isset($data['strIdEstrat_t']) &&  $data['strIdEstrat_t'] != '' ? $data['strIdEstrat_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($strIdEstrat){
                $estpas=new Estrategias($bpo);
                $estpas->setIdEstrat($strIdEstrat,$bpo);
                $response=$estpas->getEstpas();
                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $response['mensaje'],
                    'response' => $response['estado']
                ];
                echo json_encode($data,$data['code']);
                exit();
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addEstpas():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strIdHuesped=isset($data['strHuesped_t']) &&  $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strIdEstrat=isset($data['strIdEstrat_t']) &&  $data['strIdEstrat_t'] != '' ? $data['strIdEstrat_t'] : false;
            (string)$strNombre=isset($data['strNombre_t']) &&  $data['strNombre_t'] != '' ? $data['strNombre_t'] : false;
            (int)$intTipo=isset($data['intTipo_t']) &&  is_numeric($data['intTipo_t']) && $data['intTipo_t'] > 0 ? $data['intTipo_t'] : 0;
            (string)$strComentario=isset($data['strComentario_t']) &&  $data['strComentario_t'] != '' ? $data['strComentario_t'] : NULL;
            (int)$intActivo=isset($data['intActivo_t']) &&  is_numeric($data['intActivo_t']) ? $data['intActivo_t'] : -1;
            (int)$intBySistema=isset($data['intBySistema_t']) &&  is_numeric($data['intBySistema_t']) ? $data['intBySistema_t'] : 0;
            (string)$strLocacion=isset($data['strLocacion_t']) && $data['strLocacion_t'] != '' ? $data['strLocacion_t'] : NULL;
            (string)$strIdGuion_t=isset($data['strIdGuion_t']) && $data['strIdGuion_t'] != '' ? $data['strIdGuion_t'] : NULL;
            if($strIdHuesped && $strIdEstrat && $strNombre && $intTipo){
                $estpas=new Estrategias;
                $estpas->setStrIdHuesped($strIdHuesped);
                $estpas->setIdEstrat($strIdEstrat);
                $estpas->setStrEstpasNombre($strNombre);
                $estpas->setStrEstpasTipo($intTipo);
                $estpas->setStrEstpasComentario($strComentario);
                $estpas->setIntEstpasActivo($intActivo);
                $estpas->setIntEstpasBySistema($intBySistema);
                $estpas->setStrEstpasLoca($strLocacion);
                $estpas->setIdForm($strIdGuion_t);
                $response=$estpas->addEstpas();
                // ENVIAR LA RESPUESTA
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function getEstcon():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strIdEstrat=isset($data['strIdEstrat_t']) &&  $data['strIdEstrat_t'] != '' ? $data['strIdEstrat_t'] : false;
            if($strIdEstrat){
                $estpas=new Estrategias;
                $estpas->setIdEstrat($strIdEstrat);
                $response=$estpas->getEstcon();
                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $response['mensaje'],
                    'response' => $response['estado']
                ];
                echo json_encode($data,$data['code']);
                exit();
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addEstcon():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strIdHuesped=isset($data['strIdHuesped_t']) &&  $data['strIdHuesped_t'] != '' ? $data['strIdHuesped_t'] : false;
            (string)$strIdEstrat=isset($data['strIdEstrat_t']) &&  $data['strIdEstrat_t'] != '' ? $data['strIdEstrat_t'] : false;
            (string)$strNombre=isset($data['strNombre_t']) &&  $data['strNombre_t'] != '' ? $data['strNombre_t'] : false;
            (string)$strIdEstpasOrigen=isset($data['strIdEstpasOrigen_t']) &&  $data['strIdEstpasOrigen_t'] != '' ? $data['strIdEstpasOrigen_t'] : false;
            (string)$strIdEstpasDestino=isset($data['strIdEstpasDestino_t']) &&  $data['strIdEstpasDestino_t'] != '' ? $data['strIdEstpasDestino_t'] : false;
            (string)$strPortOrigen=isset($data['strPortOrigen_t']) &&  $data['strPortOrigen_t'] != '' ? $data['strPortOrigen_t'] : false;
            (string)$strPortDestino=isset($data['strPortDestino_t']) &&  $data['strPortDestino_t'] != '' ? $data['strPortDestino_t'] : false;
            (string)$strCoordenadas=isset($data['strCoordenadas_t']) &&  $data['strCoordenadas_t'] != '' ? $data['strCoordenadas_t'] : NULL;
            (string)$strComentario=isset($data['strComentario_t']) &&  $data['strComentario_t'] != '' ? $data['strComentario_t'] : NULL;
            if($strIdHuesped && $strIdEstrat && $strNombre && $strIdEstpasOrigen && $strIdEstpasDestino && $strPortOrigen && $strPortDestino){
                $estpas=new Estrategias;
                $estpas->setStrIdHuesped($strIdHuesped);
                $estpas->setIdEstrat($strIdEstrat);
                $estpas->setStrEstconNombre($strNombre);
                $estpas->setStrEstconIdOrigen($strIdEstpasOrigen);
                $estpas->setStrEstconIdDestino($strIdEstpasDestino);
                $estpas->setStrEstconPortOrigen($strPortOrigen);
                $estpas->setStrEstconPortDestino($strPortDestino);
                $estpas->setStrEstconCoordenadas($strCoordenadas);
                $estpas->setStrEstconComentario($strComentario);
                $response=$estpas->addEstcon();
                // ENVIAR LA RESPUESTA
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function getConfigEstpas():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strIdEstpas=isset($data['strIdEstpas_t']) && $data['strIdEstpas_t'] != '' ? $data['strIdEstpas_t'] : false;
            (string)$strIdBase=isset($data['strIdBase_t']) && $data['strIdBase_t'] != "" ? $data['strIdBase_t'] : NULL;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($strIdEstpas){
                $data=new Estrategias($bpo);
                $data->setStrIdEstpas($strIdEstpas,$bpo);
                $data->setStrEstratIdGuion($strIdBase,$bpo);
                $data=$data->getConfigEstpas();
                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $data['mensaje'],
                    'response' => $data['estado']
                ];
                echo json_encode($data,$data['code']);
                exit();
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addConfigEstpas():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strIdEstpas=isset($data['strIdEstpas_t']) && $data['strIdEstpas_t'] != '' ? $data['strIdEstpas_t'] : false;
            (string)$strIdBase=isset($data['strIdBase_t']) && $data['strIdBase_t'] != "" ? $data['strIdBase_t'] : NULL;
            (array)$arrData=isset($data['arrData_t']) && is_array($data['arrData_t']) ?  $data['arrData_t'] : array();
            if($strIdEstpas && count($arrData) > 0){
                $data=new Estrategias();
                $data->setStrIdEstpas($strIdEstpas);
                $data->setStrEstratIdGuion($strIdBase);
                $data->setArrData($arrData);
                $data=$data->addConfigEstpas();
                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $data['mensaje'],
                    'response' => $data['estado']
                ];
                echo json_encode($data,$data['code']);
                exit();
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }
    
    public function addCamposWebForm():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (int)$idWebForm=isset($data['intWebFormId_t']) ? $data['intWebFormId_t'] : 0;
            (string)$strIdBase=isset($data['intIdPoblacion_t']) && $data['intIdPoblacion_t'] != "" ? $data['intIdPoblacion_t'] : NULL;
            (array)$arrData=isset($data['arrData_t']) && is_array($data['arrData_t']) ?  $data['arrData_t'] : array();
            if(count($arrData) > 0 && $idWebForm && $strIdBase){
                $data=new Estrategias();
                $arrData['webformId']=$idWebForm;
                $data->setArrData($arrData);
                $data->setStrEstratIdGuion($strIdBase);
                $data=$data->addCamposWebForm();
                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $data['mensaje'],
                    'response' => $data['estado']
                ];
                echo json_encode($data,$data['code']);
                exit();
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }
}