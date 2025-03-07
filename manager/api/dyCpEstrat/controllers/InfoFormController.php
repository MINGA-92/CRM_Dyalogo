<?php
require_once __DIR__.'/../models/InfoForm.php';
class InfoFormController
{
    public function getInfoForm():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $strGuion=isset($data['strGuion_t']) &&  $data['strGuion_t'] != '' ? $data['strGuion_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($strGuion){
                $form=new InfoForm($bpo);
                $form->setIdForm($strGuion,$bpo);
                $response=$form->getInfoForm();
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

    public function getSeccionForm():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $strGuion=isset($data['strGuion_t']) &&  $data['strGuion_t'] != '' ? $data['strGuion_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($strGuion){
                $seccion=new InfoForm($bpo);
                $seccion->setIdForm($strGuion,$bpo);
                $response=$seccion->getSeccionForm();
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

    public function getCamposSeccion():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $strGuion=isset($data['strGuion_t']) &&  $data['strGuion_t'] != '' ? $data['strGuion_t'] : false;
            $strSeccion=isset($data['strSeccion_t']) &&  $data['strSeccion_t'] != '' ? $data['strSeccion_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($strGuion && $strSeccion){
                $campos=new InfoForm($bpo);
                $campos->setIdForm($strGuion,$bpo);
                $campos->setIdSeccion($strSeccion,$bpo);
                $response=$campos->getCamposSeccion();
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

    public function getLista():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $strLista=isset($data['strLista_t']) &&  $data['strLista_t'] != '' ? $data['strLista_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($strLista){
                $lista=new InfoForm($bpo);
                $lista->setIdopcion($strLista,$bpo);
                $response=$lista->getLista();
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

    public function getOpciones():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $strLista=isset($data['strLista_t']) &&  $data['strLista_t'] != '' ? $data['strLista_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($strLista){
                $opciones=new InfoForm($bpo);
                $opciones->setIdopcion($strLista,$bpo);
                $response=$opciones->getOpciones();
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

    public function getInfoSubForm():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $stridFormPadre=isset($data['stridFormPadre_t']) &&  $data['stridFormPadre_t'] != '' ? $data['stridFormPadre_t'] : false;
            $stridFormHijo=isset($data['stridFormHijo_t']) &&  $data['stridFormHijo_t'] != '' ? $data['stridFormHijo_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($stridFormPadre && $stridFormHijo){
                $subform=new InfoForm($bpo);
                $subform->setIdForm($stridFormPadre,$bpo);
                $subform->setIdSubForm($stridFormHijo,$bpo);
                $response=$subform->getInfoSubForm();
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

    public function getComuForm():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $stridFormPadre=isset($data['stridFormPadre_t']) &&  $data['stridFormPadre_t'] != '' ? $data['stridFormPadre_t'] : false;
            $stridFormHijo=isset($data['stridFormHijo_t']) &&  $data['stridFormHijo_t'] != '' ? $data['stridFormHijo_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($stridFormPadre && $stridFormHijo){
                $subform=new InfoForm($bpo);
                $subform->setIdForm($stridFormPadre,$bpo);
                $subform->setIdSubForm($stridFormHijo,$bpo);
                $response=$subform->getComuForm();
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

    public function getInfoListaAux():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            $stridPregun=isset($data['stridPregun_t']) &&  $data['stridPregun_t'] != '' ? $data['stridPregun_t'] : false;
            $stridForm=isset($data['stridForm_t']) &&  $data['stridForm_t'] != '' ? $data['stridForm_t'] : false;
            (bool)$bpo = isset($data['bpo']) ? true : false;
            if($stridPregun && $stridForm){
                $subform=new InfoForm($bpo);
                $subform->setIdPregun($stridPregun,$bpo);
                $subform->setIdForm($stridForm,$bpo);
                $response=$subform->getInfoListaAux();
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
}   