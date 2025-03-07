<?php
require_once __DIR__.'/../models/Form.php';
require_once __DIR__.'/../models/Seccion.php';
require_once __DIR__.'/../models/Campos.php';
require_once __DIR__.'/../models/ValidadorForm.php';

class FormController{

    public function addForm():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strNombre=isset($data['strNombre_t']) && $data['strNombre_t'] != '' ? $data['strNombre_t'] : false;
            (int)$intTipo=isset($data['intTipo_t']) && $data['intTipo_t'] > 0 ? $data['intTipo_t'] : false;
            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strDesc=isset($data['strDesc_t']) ? $data['strDesc_t'] : NULL;
            (int)$intTipoBQ=isset($data['intTipoBQ_t']) ? $data['intTipoBQ_t'] : 1;
            (int)$insertAuto=isset($data['insertAuto_t']) ? $data['insertAuto_t'] : 0;
            (int)$permiteInsert=isset($data['permiteInsert_t']) ? $data['permiteInsert_t'] : -1;
            (string)$strIdFormBPO=isset($data['strIdFormBPO_t']) && $data['strIdFormBPO_t'] != '' ? $data['strIdFormBPO_t'] : NULL;
            (bool)$booFormAux=isset($data['booFormAux_t']) ? filter_var($data['booFormAux_t'], FILTER_VALIDATE_BOOLEAN) : false;

            if($strNombre && $intTipo && $strHuesped){
                $newForm=new Form;
                $newForm->setStrNombre($strNombre);
                $newForm->setIntTipo($intTipo);
                $newForm->setStrHuesped($strHuesped);
                $newForm->setStrDesc($strDesc);
                $newForm->setIntTipoBQ($intTipoBQ);
                $newForm->setInsertAuto($insertAuto);
                $newForm->setPermiteInsert($permiteInsert);
                $newForm->setIdFormBPO($strIdFormBPO);
                if($booFormAux){
                    ValidadorForm::validateFormExistsBPO($newForm->getStrHuesped(),$newForm->getIdFormBPO());
                }
                $response=$newForm->addForm();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addSeccion():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strNombre=isset($data['strNombre_t']) && $data['strNombre_t'] != '' ? $data['strNombre_t'] : false;
            (int)$intOrden=isset($data['intOrden_t']) && $data['intOrden_t'] > 0 ? $data['intOrden_t'] : 1;
            (int)$intVista=isset($data['intVista_t']) ? $data['intVista_t'] : 3;
            (int)$intTipo=isset($data['intTipo_t']) ? $data['intTipo_t'] : 1;
            (int)$intMinimizar=isset($data['intMinimizar_t']) ? $data['intMinimizar_t'] : 1;
            (int)$intNumColumnas=isset($data['intNumColumnas_t']) ? $data['intNumColumnas_t'] : 2;
            (string)$strGuion=isset($data['strGuion_t']) && $data['strGuion_t'] != '' ? $data['strGuion_t'] : false;
            (bool)$booSeccionFormAux=isset($data['booSeccionFormAux_t']) ? filter_var($data['booSeccionFormAux_t'], FILTER_VALIDATE_BOOLEAN) : false;
            (string)$strIdSeccioBPO=isset($data['strIdSeccioBPO_t']) && $data['strIdSeccioBPO_t'] != '' ? $data['strIdSeccioBPO_t'] : NULL;

            if($strNombre && $strGuion){
                $newForm=new Seccion;
                $newForm->setSeccioNombre($strNombre);
                $newForm->setSeccioOrden($intOrden);
                $newForm->setSeccioVista($intVista);
                $newForm->setSeccioTipo($intTipo);
                $newForm->setSeccioMini($intMinimizar);
                $newForm->setNumColumnas($intNumColumnas);
                $newForm->setIdForm($strGuion);
                $newForm->setSeccioIdBPO($strIdSeccioBPO);
                ValidadorForm::validateSeccionExistsName($newForm->getSeccioNombre(),$newForm->getIdForm());
                if($booSeccionFormAux){
                    ValidadorForm::validateSeccionExistsBPO($newForm->getIdForm(), $newForm->getSeccioIdBPO());
                }
                $response=$newForm->addSeccion();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addLista():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strNombre=isset($data['strNombre_t']) && $data['strNombre_t'] != '' ? $data['strNombre_t'] : false;
            (string)$strIdListaBPO=isset($data['strIdListaBPO_t']) && $data['strIdListaBPO_t'] !='' ? $data['strIdListaBPO_t'] : NULL;
            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strGuion=isset($data['strGuion_t']) && $data['strGuion_t'] != '' ? $data['strGuion_t'] : false;

            if($strNombre && $strHuesped && $strGuion){
                $newForm=new Form;
                $newForm->setStrListaNombre($strNombre);
                $newForm->setIntListaIdBPO($strIdListaBPO);
                $newForm->setStrHuesped($strHuesped);
                $newForm->setIdForm($strGuion);
                if(!is_null($strIdListaBPO)){
                    ValidadorForm::validateListaExistBPO($newForm->getStrHuesped(),$newForm->getIntListaIdBPO());
                }
                $response=$newForm->addLista();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addOpcionLista():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strNombre=isset($data['strNombre_t']) && $data['strNombre_t'] != '' ? $data['strNombre_t'] : false;
            (string)$strIdLista=isset($data['strIdLista_t']) && $data['strIdLista_t'] !='' ? $data['strIdLista_t'] : false;
            (int)$intPosicion=isset($data['intPosicion_t']) ? $data['intPosicion_t'] : 0;
            (int)$intMonoef=isset($data['intMonoef_t']) && $data['intMonoef_t'] > 0 ? $data['intMonoef_t'] : NULL;
            (int)$intClasificacion=isset($data['intClasificacion_t']) && $data['intClasificacion_t'] > 0 ? $data['intClasificacion_t'] : NULL;
            (string)$strRpta=isset($data['strRpta_t']) && $data['strRpta_t'] !='' ? $data['strRpta_t'] : NULL;
            (int)$intOpcionPadre=isset($data['intOpcionPadre_t']) && $data['intOpcionPadre_t'] > 0 ? $data['intOpcionPadre_t'] : NULL;
            (string)$strOpcionIdLisopcBPO=isset($data['strOpcionIdLisopcBPO_t']) && $data['strOpcionIdLisopcBPO_t'] !='' ? $data['strOpcionIdLisopcBPO_t'] : NULL;

            if($strNombre && $strIdLista){
                $newForm=new Form;
                $newForm->setStrOpcionNombre($strNombre);
                $newForm->setIntListaId($strIdLista);
                $newForm->setIntOpcionPosicion($intPosicion);
                $newForm->setIntOpcionMonoef($intMonoef);
                $newForm->setIntOpcionClasificacion($intClasificacion);
                $newForm->setStrOpcionRpta($strRpta);
                $newForm->setIntOpcionIdPadre($intOpcionPadre);
                $newForm->setIntOpcionIdLisopcBPO($strOpcionIdLisopcBPO);
                if(!is_null($intOpcionPadre)){
                    ValidadorForm::validateOpcionExists($newForm->getIntOpcionIdPadre());
                }
                if(!is_null($strOpcionIdLisopcBPO)){
                    ValidadorForm::validateOpcionExistsBPO($newForm->getIntListaId(),$newForm->getIntOpcionIdLisopcBPO());
                }
                $response=$newForm->addOpcionLista();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addCampo():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);
            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strGuion=isset($data['strGuion_t']) && $data['strGuion_t'] != '' ? $data['strGuion_t'] : false;
            (string)$strSeccion=isset($data['strSeccion_t']) && $data['strSeccion_t'] != '' ? $data['strSeccion_t'] : false;
            (string)$strCampoNombre=isset($data['strCampoNombre_t']) && $data['strCampoNombre_t'] != '' ? $data['strCampoNombre_t'] : false;
            (int)$intCampoTipo=isset($data['intCampoTipo_t']) && $data['intCampoTipo_t'] > 0 ? $data['intCampoTipo_t'] : false;
            (string)$strCampoGuionAux=isset($data['strCampoGuionAux_t']) && $data['strCampoGuionAux_t'] != '' ? $data['strCampoGuionAux_t'] : NULL;
            (string)$strCampoLista=isset($data['strCampoLista_t']) && $data['strCampoLista_t'] !='' ? $data['strCampoLista_t'] : NULL;
            (int)$intCampoOrden=isset($data['intCampoOrden_t']) ? $data['intCampoOrden_t'] : 0;
            (int)$intCampoMinimoNumero=isset($data['intCampoMinimoNumero_t']) && is_numeric($data['intCampoMinimoNumero_t']) ? $data['intCampoMinimoNumero_t'] : NULL;
            (int)$intCampoMaximoNumero=isset($data['intCampoMaximoNumero_t']) && is_numeric($data['intCampoMaximoNumero_t']) ? $data['intCampoMaximoNumero_t'] : NULL;
            (string)$strCampoFechaMinimo=isset($data['strCampoFechaMinimo_t']) && $data['strCampoFechaMinimo_t'] != '' ? $data['strCampoFechaMinimo_t'] : NULL;
            (string)$strCampoFechaMaximo=isset($data['strCampoFechaMaximo_t']) && $data['strCampoFechaMaximo_t'] != '' ? $data['strCampoFechaMaximo_t'] : NULL;
            (string)$strCampoHoraMini=isset($data['strCampoHoraMini_t']) && $data['strCampoHoraMini_t'] != '' ? $data['strCampoHoraMini_t'] : NULL;
            (string)$strCampoHoraMaximo=isset($data['strCampoHoraMaximo_t']) && $data['strCampoHoraMaximo_t'] != '' ? $data['strCampoHoraMaximo_t'] : NULL;
            (string)$strCampoError=isset($data['strCampoError_t']) && $data['strCampoError_t'] != '' ? $data['strCampoError_t'] : NULL;
            (int)$intCampoValorDefecto=isset($data['intCampoValorDefecto_t']) && is_numeric($data['intCampoValorDefecto_t']) ? $data['intCampoValorDefecto_t'] : NULL;
            (int)$intCampoVista=isset($data['intCampoVista_t']) && is_numeric($data['intCampoVista_t']) ? $data['intCampoVista_t'] : 0;
            (int)$intCampoPermiteAdicion=isset($data['intCampoPermiteAdicion_t']) && is_numeric($data['intCampoPermiteAdicion_t']) ? $data['intCampoPermiteAdicion_t'] : 0;
            (string)$strCampoPregunPadre=isset($data['strCampoPregunPadre_t']) && $data['strCampoPregunPadre_t'] != '' ? $data['strCampoPregunPadre_t'] : NULL;
            (int)$intCampoNumDefecto=isset($data['intCampoNumDefecto_t']) && is_numeric($data['intCampoNumDefecto_t']) ? $data['intCampoNumDefecto_t'] : NULL;
            (string)$strCampotextDefecto=isset($data['strCampotextDefecto_t']) && $data['strCampotextDefecto_t'] != '' ? $data['strCampotextDefecto_t'] : NULL;
            (int)$intCampoTiempoMas=isset($data['intCampoTiempoMas_t']) && is_numeric($data['intCampoTiempoMas_t']) ? $data['intCampoTiempoMas_t'] : NULL;
            (string)$strCampoPeriodo=isset($data['strCampoPeriodo_t']) && $data['strCampoPeriodo_t'] != '' ? $data['strCampoPeriodo_t'] : NULL;
            (string)$strCampoformula=isset($data['strCampoformula_t']) && $data['strCampoformula_t'] != '' ? $data['strCampoformula_t'] : NULL;
            (int)$intCampoTipoTel=isset($data['intCampoTipoTel_t']) && is_numeric($data['intCampoTipoTel_t']) ? $data['intCampoTipoTel_t'] : 0;
            (int)$intCampoSendMail=isset($data['intCampoSendMail_t']) && is_numeric($data['intCampoSendMail_t']) ? $data['intCampoSendMail_t'] : 0;
            (int)$intCampoSendSms=isset($data['intCampoSendSms_t']) && is_numeric($data['intCampoSendSms_t']) ? $data['intCampoSendSms_t'] : 0;
            (int)$intCampoTxtSms=isset($data['intCampoTxtSms_t']) && is_numeric($data['intCampoTxtSms_t']) ? $data['intCampoTxtSms_t'] : 0;
            (int)$intCampoPrefijoSms=isset($data['intCampoPrefijoSms_t']) && is_numeric($data['intCampoPrefijoSms_t']) ? $data['intCampoPrefijoSms_t'] : NULL;
            (int)$intCampoBuscaMail=isset($data['intCampoBuscaMail_t']) && is_numeric($data['intCampoBuscaMail_t']) ? $data['intCampoBuscaMail_t'] : 0;
            (int)$intCampoFormato=isset($data['intCampoFormato_t']) && is_numeric($data['intCampoFormato_t']) ? $data['intCampoFormato_t'] : 0;
            (int)$intCampoPosDecimales=isset($data['intCampoPosDecimales_t']) && is_numeric($data['intCampoPosDecimales_t']) ? $data['intCampoPosDecimales_t'] : 0;
            (int)$intCampoLongitud=isset($data['intCampoLongitud_t']) && is_numeric($data['intCampoLongitud_t']) ? $data['intCampoLongitud_t'] : 0;
            (int)$intCampoMostrarSubForm=isset($data['intCampoMostrarSubForm_t']) && is_numeric($data['intCampoMostrarSubForm_t']) ? $data['intCampoMostrarSubForm_t'] : 0;
            (int)$intCampoWebForm=isset($data['intCampoWebForm_t']) && is_numeric($data['intCampoWebForm_t']) ? $data['intCampoWebForm_t'] : 0;
            (string)$strCampoIdPregunBPO=isset($data['strCampoIdPregunBPO_t']) && $data['strCampoIdPregunBPO_t'] != '' ? $data['strCampoIdPregunBPO_t'] : NULL;
            (bool)$booCampoFormAux=isset($data['booCampoFormAux_t']) ? filter_var($data['booCampoFormAux_t'], FILTER_VALIDATE_BOOLEAN) : false;

            if($strGuion && $strSeccion && $strCampoNombre && $intCampoTipo && $strHuesped){
                $newForm=new Campos;
                $newForm->setStrHuesped($strHuesped);
                $newForm->setIdForm($strGuion);
                $newForm->setIdSeccion($strSeccion);
                $newForm->setStrCampoNombre($strCampoNombre);
                $newForm->setIntCampoTipo($intCampoTipo);
                $newForm->setStrCampoGuionAux($strCampoGuionAux);
                $newForm->setStrCampoLista($strCampoLista);
                $newForm->setIntCampoOrden($intCampoOrden);
                $newForm->setIntCampoMinimoNumero($intCampoMinimoNumero);
                $newForm->setIntCampoMaximoNumero($intCampoMaximoNumero);
                $newForm->setStrCampoFechaMinimo($strCampoFechaMinimo);
                $newForm->setStrCampoFechaMaximo($strCampoFechaMaximo);
                $newForm->setStrCampoHoraMini($strCampoHoraMini);
                $newForm->setStrCampoHoraMaximo($strCampoHoraMaximo);
                $newForm->setStrCampoError($strCampoError);
                $newForm->setIntCampoValorDefecto($intCampoValorDefecto);
                $newForm->setIntCampoVista($intCampoVista);
                $newForm->setIntCampoPermiteAdicion($intCampoPermiteAdicion);
                $newForm->setStrCampoPregunPadre($strCampoPregunPadre);
                $newForm->setIntCampoNumDefecto($intCampoNumDefecto);
                $newForm->setStrCampotextDefecto($strCampotextDefecto);
                $newForm->setIntCampoTiempoMas($intCampoTiempoMas);
                $newForm->setStrCampoPeriodo($strCampoPeriodo);
                $newForm->setStrCampoformula($strCampoformula);
                $newForm->setIntCampoTipoTel($intCampoTipoTel);
                $newForm->setIntCampoSendMail($intCampoSendMail);
                $newForm->setIntCampoSendSms($intCampoSendSms);
                $newForm->setIntCampoTxtSms($intCampoTxtSms);
                $newForm->setIntCampoPrefijoSms($intCampoPrefijoSms);
                $newForm->setIntCampoBuscaMail($intCampoBuscaMail);
                $newForm->setIntCampoFormato($intCampoFormato);
                $newForm->setIntCampoPosDecimales($intCampoPosDecimales);
                $newForm->setIntCampoLongitud($intCampoLongitud);
                $newForm->setIntCampoMostrarSubForm($intCampoMostrarSubForm);
                $newForm->setIntCampoWebForm($intCampoWebForm);
                $newForm->setIntCampoIdBPO($strCampoIdPregunBPO);

                if($booCampoFormAux){
                    ValidadorForm::validateCampoExistsBPO($newForm->getIdForm(),$newForm->getIntCampoIdBPO());
                }
                ValidadorForm::validateFormExists($newForm->getStrHuesped(),$newForm->getIdForm());
                ValidadorForm::validateSeccionExists($newForm->getIdForm(),$newForm->getIdSeccion());
                if(!is_null($strCampoGuionAux)){
                    ValidadorForm::validateFormExists($newForm->getStrHuesped(),$newForm->getStrCampoGuionAux());
                }
                if(!is_null($strCampoLista)){
                    ValidadorForm::validateListaExist($newForm->getStrHuesped(),$newForm->getStrCampoLista());
                }
                if(!is_null($strCampoPregunPadre)){
                    ValidadorForm::validateCampoExists($newForm->getStrCampoPregunPadre());
                }

                $response=$newForm->addCampo();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addEnlaceAux():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strIdPregun=isset($data['strIdPregun_t']) && $data['strIdPregun_t'] != '' ? $data['strIdPregun_t'] : false;
            (string)$strIdCampoGuionAux=isset($data['strIdCampoGuionAux_t']) && $data['strIdCampoGuionAux_t'] != '' ? $data['strIdCampoGuionAux_t'] : false;
            (string)$strIdGuionAux=isset($data['strIdGuionAux_t']) && $data['strIdGuionAux_t'] != '' ? $data['strIdGuionAux_t'] : false;
            (string)$strIdCampoForm=isset($data['strIdCampoForm_t']) && $data['strIdCampoForm_t'] != '0' ? $data['strIdCampoForm_t'] : 0;
            if($strHuesped && $strIdPregun && $strIdCampoGuionAux && $strIdGuionAux){
                $addAux=new Campos;
                $addAux->setStrHuesped($strHuesped);
                $addAux->setIntCampoId($strIdPregun);
                $addAux->setIntAuxIdCampo($strIdCampoForm);
                $addAux->setIntAuxIdGuion($strIdGuionAux);
                $addAux->setIntAuxCampoGuion($strIdCampoGuionAux);
                ValidadorForm::validateCampoExists($addAux->getIntCampoId());
                ValidadorForm::validateCampoExists($addAux->getIntAuxCampoGuion());
                ValidadorForm::validateFormExists($addAux->getStrHuesped(),$addAux->getIntAuxIdGuion());
                if($strIdCampoForm){
                    ValidadorForm::validateCampoExists($addAux->getIntAuxIdCampo());
                }
                $response=$addAux->addConfigListaAuxiliar();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addConfigSubform():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strIdFormPadre=isset($data['strIdFormPadre_t']) && $data['strIdFormPadre_t'] != '' ? $data['strIdFormPadre_t'] : false;
            (string)$strIdFormHijo=isset($data['strIdFormHijo_t']) && $data['strIdFormHijo_t'] != '' ? $data['strIdFormHijo_t'] : false;
            (string)$strSubFormNombre=isset($data['strSubFormNombre_t']) && $data['strSubFormNombre_t'] != '' ? $data['strSubFormNombre_t'] : false;
            (string)$strIdLlavePadre=isset($data['strIdLlavePadre_t']) && $data['strIdLlavePadre_t'] != '' ? $data['strIdLlavePadre_t'] : false;
            (string)$strIdLlaveHijo=isset($data['strIdLlaveHijo_t']) && $data['strIdLlaveHijo_t'] != '' ? $data['strIdLlaveHijo_t'] : false;
            (string)$strIdPregun=isset($data['strIdPregun_t']) && $data['strIdPregun_t'] != '' ? $data['strIdPregun_t'] : false;
            (string)$strIdTotalPadre=isset($data['strIdTotalPadre_t']) && $data['strIdTotalPadre_t'] != '' ? $data['strIdTotalPadre_t'] : NULL;
            (string)$strIdTotalHijo=isset($data['strIdTotalHijo_t']) && $data['strIdTotalHijo_t'] != '' ? $data['strIdTotalHijo_t'] : NULL;
            (int)$intIdOperTotal=isset($data['intIdOperTotal_t']) && is_numeric($data['intIdOperTotal_t']) ? $data['intIdOperTotal_t'] : NULL;
            if($strHuesped && $strIdFormPadre && $strIdFormHijo && $strSubFormNombre && $strIdLlavePadre && $strIdLlaveHijo && $strIdPregun){
                $addSubForm=new Campos;
                $addSubForm->setStrHuesped($strHuesped);
                $addSubForm->setIdForm($strIdFormPadre);
                $addSubForm->setIntSubFormGuionHijo($strIdFormHijo);
                $addSubForm->setStrSubFormNombre($strSubFormNombre);
                $addSubForm->setIntSubFormLlavePadre($strIdLlavePadre);
                $addSubForm->setIntSubFormLlaveHijo($strIdLlaveHijo);
                $addSubForm->setIntCampoId($strIdPregun);
                $addSubForm->setIntSubFormTotalPadre($strIdTotalPadre);
                $addSubForm->setIntSubFormTotalHijo($strIdTotalHijo);
                $addSubForm->setIntSubFormOperTotal($intIdOperTotal);

                ValidadorForm::validateFormExists($addSubForm->getStrHuesped(),$addSubForm->getIdForm());
                ValidadorForm::validateFormExists($addSubForm->getStrHuesped(),$addSubForm->getIntSubFormGuionHijo());
                ValidadorForm::validateCampoExists($addSubForm->getIntSubFormLlavePadre());
                ValidadorForm::validateCampoExists($addSubForm->getIntSubFormLlaveHijo());
                ValidadorForm::validateCampoExists($addSubForm->getIntCampoId());
                if(!is_null($strIdTotalPadre)){
                    ValidadorForm::validateCampoExists($addSubForm->getIntSubFormTotalPadre());
                }
                if(!is_null($strIdTotalHijo)){
                    ValidadorForm::validateCampoExists($addSubForm->getIntSubFormTotalHijo());
                }

                $response=$addSubForm->addConfigSubForm();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function addComuSubform():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strIdFormPadre=isset($data['strIdFormPadre_t']) && $data['strIdFormPadre_t'] != '' ? $data['strIdFormPadre_t'] : false;
            (string)$strIdFormHijo=isset($data['strIdFormHijo_t']) && $data['strIdFormHijo_t'] != '' ? $data['strIdFormHijo_t'] : false;
            (string)$strIdLlavePadre=isset($data['strIdLlavePadre_t']) && $data['strIdLlavePadre_t'] != '' ? $data['strIdLlavePadre_t'] : false;
            (string)$strIdLlaveHijo=isset($data['strIdLlaveHijo_t']) && $data['strIdLlaveHijo_t'] != '' ? $data['strIdLlaveHijo_t'] : false;

            if($strHuesped && $strIdFormPadre && $strIdFormHijo && $strIdLlavePadre && $strIdLlaveHijo){
                $addSubForm=new Campos;
                $addSubForm->setStrHuesped($strHuesped);
                $addSubForm->setIdForm($strIdFormPadre);
                $addSubForm->setIntSubFormGuionHijo($strIdFormHijo);
                $addSubForm->setIntSubFormLlavePadre($strIdLlavePadre);
                $addSubForm->setIntSubFormLlaveHijo($strIdLlaveHijo);

                ValidadorForm::validateFormExists($addSubForm->getStrHuesped(),$addSubForm->getIdForm());
                ValidadorForm::validateFormExists($addSubForm->getStrHuesped(),$addSubForm->getIntSubFormGuionHijo());
                ValidadorForm::validateCampoExists($addSubForm->getIntSubFormLlavePadre());
                ValidadorForm::validateCampoExists($addSubForm->getIntSubFormLlaveHijo());

                $response=$addSubForm->addComuSubForm();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function upCamposEtiqueta():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strIdForm=isset($data['strIdForm_t']) && $data['strIdForm_t'] != '' ? $data['strIdForm_t'] : false;
            (string)$strIdCampoPrincipal=isset($data['strIdCampoPrincipal_t']) && $data['strIdCampoPrincipal_t'] != '' ? $data['strIdCampoPrincipal_t'] : false;
            (string)$strIdCampoSecundario=isset($data['strIdCampoSecundario_t']) && $data['strIdCampoSecundario_t'] != '' ? $data['strIdCampoSecundario_t'] : false;
            (string)$strIdCampoLlave=isset($data['strIdCampoLlave_t']) && $data['strIdCampoLlave_t'] != '' ? $data['strIdCampoLlave_t'] : NULL;

            if($strHuesped && $strIdForm && $strIdCampoPrincipal && $strIdCampoSecundario){
                $addEtiqueta=new Form;
                $addEtiqueta->setStrHuesped($strHuesped);
                $addEtiqueta->setIdForm($strIdForm);
                $addEtiqueta->setIntEtiquetaPrincipal($strIdCampoPrincipal);
                $addEtiqueta->setIntEtiquetaSecundaria($strIdCampoSecundario);
                $addEtiqueta->setIntEtiquetaLlave($strIdCampoLlave);

                ValidadorForm::validateFormExists($addEtiqueta->getStrHuesped(),$addEtiqueta->getIdForm());
                ValidadorForm::validateCampoExists($addEtiqueta->getIntEtiquetaPrincipal());
                ValidadorForm::validateCampoExists($addEtiqueta->getIntEtiquetaSecundaria());
                if(!is_null($strIdCampoLlave)){
                    ValidadorForm::validateCampoExists($addEtiqueta->getIntEtiquetaLlave());
                }

                $response=$addEtiqueta->addEtiquetado();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function upCamposControl():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');
        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            (string)$strHuesped=isset($data['strHuesped_t']) && $data['strHuesped_t'] != '' ? $data['strHuesped_t'] : false;
            (string)$strIdForm=isset($data['strIdForm_t']) && $data['strIdForm_t'] != '' ? $data['strIdForm_t'] : false;
            (string)$strIdCampoAgente=isset($data['strIdCampoAgente_t']) && $data['strIdCampoAgente_t'] != '' ? $data['strIdCampoAgente_t'] : false;
            (string)$strIdCampoFecha=isset($data['strIdCampoFecha_t']) && $data['strIdCampoFecha_t'] != '' ? $data['strIdCampoFecha_t'] : false;
            (string)$strIdCampoHora=isset($data['strIdCampoHora_t']) && $data['strIdCampoHora_t'] != '' ? $data['strIdCampoHora_t'] : false;
            (string)$strIdCampoTipificar=isset($data['strIdCampoTipificar_t']) && $data['strIdCampoTipificar_t'] != '' ? $data['strIdCampoTipificar_t'] : false;
            (string)$strIdCampoReintento=isset($data['strIdCampoReintento_t']) && $data['strIdCampoReintento_t'] != '' ? $data['strIdCampoReintento_t'] : false;
            (string)$strIdCampoFechaAgenda=isset($data['strIdCampoFechaAgenda_t']) && $data['strIdCampoFechaAgenda_t'] != '' ? $data['strIdCampoFechaAgenda_t'] : false;
            (string)$strIdCampoHoraAgenda=isset($data['strIdCampoHoraAgenda_t']) && $data['strIdCampoHoraAgenda_t'] != '' ? $data['strIdCampoHoraAgenda_t'] : false;
            (string)$strIdCampoObservacion=isset($data['strIdCampoObservacion_t']) && $data['strIdCampoObservacion_t'] != '' ? $data['strIdCampoObservacion_t'] : false;

            if($strHuesped && $strIdForm && $strIdCampoAgente && $strIdCampoFecha && $strIdCampoHora && $strIdCampoTipificar && $strIdCampoReintento && $strIdCampoFechaAgenda && $strIdCampoHoraAgenda && $strIdCampoObservacion){
                $upCamposControl=new Form;
                $upCamposControl->setStrHuesped($strHuesped);
                $upCamposControl->setIdForm($strIdForm);

                $arrDataCampos=array(
                    'strIdCampoAgente'=>Helpers::decodeId($strIdCampoAgente,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN"),
                    'strIdCampoFecha'=>Helpers::decodeId($strIdCampoHora,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN"),
                    'strIdCampoHora'=>Helpers::decodeId($strIdCampoTipificar,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN"),
                    'strIdCampoTipificar'=>Helpers::decodeId($strIdCampoReintento,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN"),
                    'strIdCampoReintento'=>Helpers::decodeId($strIdCampoFechaAgenda,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN"),
                    'strIdCampoFechaAgenda'=>Helpers::decodeId($strIdCampoHoraAgenda,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN"),
                    'strIdCampoHoraAgenda'=>Helpers::decodeId($strIdCampoFecha,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN"),
                    'strIdCampoObservacion'=>Helpers::decodeId($strIdCampoObservacion,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN")
                );
                ValidadorForm::validateFormExists($upCamposControl->getStrHuesped(),$upCamposControl->getIdForm());
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoAgente']);
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoFecha']);
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoHora']);
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoTipificar']);
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoReintento']);
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoFechaAgenda']);
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoHoraAgenda']);
                ValidadorForm::validateCampoExists($arrDataCampos['strIdCampoObservacion']);

                $response=$upCamposControl->upCamposControl($arrDataCampos);
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

}