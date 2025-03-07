<?php
require_once("AtributosEstrat.php");

class InfoForm extends AtributosEstrat
{
    public function __construct(bool $bpo=false)
    {
        Parent::__construct($bpo);
    }

    // OBTENER LA INFORMACIÓN PRIMARIA DE UN FORMULARIO(BD,FORM,COMPLEMENTO)
    public function getInfoForm():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT GUION__Nombre____b,GUION__Observaci_b,GUION__Tipo______b,GUION__ConsInte__PREGUN_Pri_b,GUION__ConsInte__PREGUN_Sec_b,GUION__TipoBusqu__Manual_b,GUION__ConsInte__PREGUN_Llave_b,GUION_INSERTAUTO_b,GUION_PERMITEINSERTAR_b FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__b = {$this->getIdForm()}");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    $response['mensaje']=array(
                        'nombre'=>$row->GUION__Nombre____b,
                        'desc'=>$row->GUION__Observaci_b,
                        'tipo'=>$row->GUION__Tipo______b,
                        'campoPri'=>is_numeric($row->GUION__ConsInte__PREGUN_Pri_b) ? Helpers::encrypt($row->GUION__ConsInte__PREGUN_Pri_b) : $row->GUION__ConsInte__PREGUN_Pri_b,
                        'campoSec'=>is_numeric($row->GUION__ConsInte__PREGUN_Sec_b) ? Helpers::encrypt($row->GUION__ConsInte__PREGUN_Sec_b) : $row->GUION__ConsInte__PREGUN_Sec_b,
                        'tipoBQ'=>$row->GUION__TipoBusqu__Manual_b,
                        'campoLlave'=>is_numeric($row->GUION__ConsInte__PREGUN_Llave_b) &&  $row->GUION__ConsInte__PREGUN_Llave_b > 0 ? Helpers::encrypt($row->GUION__ConsInte__PREGUN_Llave_b) : "",
                        'insertAuto'=>$row->GUION_INSERTAUTO_b,
                        'permiteInsert'=>$row->GUION_PERMITEINSERTAR_b
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LAS SECCIONES DE UN FORMULARIO
    public function getSeccionForm():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT SECCIO_ConsInte__b,SECCIO_Nombre____b,SECCIO_Orden_____b,SECCIO_VistPest__b,SECCIO_TipoSecc__b,SECCIO_PestMini__b,SECCIO_NumColumnas_b FROM DYALOGOCRM_SISTEMA.SECCIO WHERE SECCIO_ConsInte__GUION__b = {$this->getIdForm()}");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'id'=>Helpers::encrypt($row->SECCIO_ConsInte__b),
                            'nombre'=>$row->SECCIO_Nombre____b,
                            'orden'=>$row->SECCIO_Orden_____b,
                            'vista'=>$row->SECCIO_VistPest__b,
                            'tipo'=>$row->SECCIO_TipoSecc__b,
                            'mini'=>$row->SECCIO_PestMini__b,
                            'numColumnas'=>$row->SECCIO_NumColumnas_b
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LOS CAMPOS QUE PERTENECEN A UNA SECCION
    public function getCamposSeccion():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT PREGUN_ConsInte__b AS id, REPLACE(PREGUN_Texto_____b,'\t',' ') AS nombre , PREGUN_Tipo______b AS tipo, PREGUN_ConsInte__GUION__PRE_B AS guionAux, PREGUN_ConsInte__OPCION_B AS lista, PREGUN_OrdePreg__b AS orden, PREGUN_NumeMini__b AS minimoNumero, PREGUN_NumeMaxi__b AS maximoNumero, PREGUN_FechMini__b AS fechaMinimo, PREGUN_FechMaxi__b AS fechaMaximo , PREGUN_HoraMini__b AS horaMini , PREGUN_HoraMaxi__b AS horaMaximo, PREGUN_TexErrVal_b AS error, PREGUN_Default___b AS valorDefecto, PREGUN_ContAcce__b AS vista, PREGUN_PermiteAdicion_b AS permiteAdicion, PREGUN_ConsInte_PREGUN_Depende_b AS listaPadre, PREGUN_DefaNume__b AS numDefecto, PREGUN_DefaText__b AS textDefecto, PREGUN_DefCanFec_b AS tiempoMas, PREGUN_DefUniFec_b AS periodo, PREGUN_OperEntreCamp_____b AS formula, PREGUN_TipoTel_b AS tipoTel, PREGUN_SendMail_b AS sendMail, PREGUN_SendSMS_b AS sendSms, PREGUN_textSMS_b AS txtSms, PREGUN_PrefijoSms_b AS prefijoSms, PREGUN_SearchMail_b AS buscaMail, PREGUN_consInte__ws_B AS idWebService, PREGUN_FormaIntegrarWS_b AS formaWS, PREGUN_Formato_b AS formato, PREGUN_PosDecimales_b AS posDecimales, PREGUN_Longitud__b AS longitud, PREGUN_MostrarSubForm AS mostrarSubForm, PREGUN_WebForm_b AS webForm FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$this->getIdForm()} AND PREGUN_ConsInte__SECCIO_b= {$this->getIdSeccion()}");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'id'=>Helpers::encrypt($row->id),
                            'nombre'=>$row->nombre,
                            'tipo'=>$row->tipo,
                            'guionAux'=>is_numeric($row->guionAux) && $row->guionAux > 0 ? Helpers::encrypt($row->guionAux) : $row->guionAux ,
                            'lista'=>is_numeric($row->lista) && $row->lista > 0 ? Helpers::encrypt($row->lista) : $row->lista ,
                            'orden'=>$row->orden,
                            'minimoNumero'=>$row->minimoNumero,
                            'maximoNumero'=>$row->maximoNumero,
                            'fechaMinimo'=>$row->fechaMinimo,
                            'fechaMaximo'=>$row->fechaMaximo,
                            'horaMini'=>$row->horaMini,
                            'horaMaximo'=>$row->horaMaximo,
                            'error'=>$row->error,
                            'valorDefecto'=>$row->valorDefecto,
                            'vista'=>$row->vista,
                            'permiteAdicion'=>$row->permiteAdicion,
                            'listaPadre'=>is_numeric($row->listaPadre) && $row->listaPadre > 0 ? Helpers::encrypt($row->listaPadre) : $row->listaPadre,
                            'numDefecto'=>$row->numDefecto,
                            'textDefecto'=>$row->textDefecto,
                            'tiempoMas'=>$row->tiempoMas,
                            'periodo'=>$row->periodo,
                            'formula'=>$row->formula,
                            'tipoTel'=>$row->tipoTel,
                            'sendMail'=>$row->sendMail,
                            'sendSms'=>$row->sendSms,
                            'txtSms'=>$row->txtSms,
                            'prefijoSms'=>$row->prefijoSms,
                            'buscaMail'=>$row->buscaMail,
                            'idWebService'=>is_numeric($row->idWebService) && $row->idWebService > 0 ? Helpers::encrypt($row->idWebService) : $row->idWebService,
                            'formaWS'=>$row->formaWS,
                            'formato'=>$row->formato,
                            'posDecimales'=>$row->posDecimales,
                            'longitud'=>$row->longitud,
                            'mostrarSubForm'=>$row->mostrarSubForm,
                            'webForm'=>$row->webForm
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER EL NOMBRE DE UNA LISTA
    public function getLista():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT OPCION_Nombre____b FROM DYALOGOCRM_SISTEMA.OPCION WHERE OPCION_ConsInte__b = {$this->getIdopcion()}");
        if($sql){
            if($sql->num_rows > 0){
                $sql = $sql->fetch_object();
                $response['estado']=true;
                $response['mensaje']=$sql->OPCION_Nombre____b;
            }
        }else{
            $this->error();
        }
        return $response;        
    }

    // OBTENER LAS OPCIONES DE UNA LISTA
    public function getOpciones():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT LISOPC_ConsInte__b,LISOPC_Nombre____b,LISOPC_Posicion__b,LISOPC_Clasifica_b,LISOPC_CambClas__b,LISOPC_CambRepr__b,LISOPC_Valor____b,LISOPC_Respuesta_b,LISOPC_ConsInte__LISOPC_Depende_b FROM DYALOGOCRM_SISTEMA.LISOPC WHERE LISOPC_ConsInte__OPCION_b={$this->getIdopcion()}");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'id'=>Helpers::encrypt($row->LISOPC_ConsInte__b),
                            'nombre'=>$row->LISOPC_Nombre____b,
                            'posicion'=>$row->LISOPC_Posicion__b,
                            'idMonoef'=>$row->LISOPC_Clasifica_b,
                            'cambClass'=>$row->LISOPC_CambClas__b,
                            'cambRepr'=>$row->LISOPC_CambRepr__b,
                            'idLisopcClasi'=>$row->LISOPC_Valor____b,
                            'respuesta'=>$row->LISOPC_Respuesta_b,
                            'idLisopcPadre'=>$row->LISOPC_ConsInte__LISOPC_Depende_b
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LA INFORMACIÓN DE UN CAMPO TIPO SUBFORMULARIO
    public function getInfoSubForm():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT GUIDET_Nombre____b,GUIDET_ConsInte__PREGUN_Ma1_b,GUIDET_ConsInte__PREGUN_De1_b,GUIDET_ConsInte__PREGUN_Totalizador_b,GUIDET_ConsInte__PREGUN_Totalizador_H_b,GUIDET_Oper_Totalizador_b FROM DYALOGOCRM_SISTEMA.GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b={$this->getIdForm()} AND GUIDET_ConsInte__GUION__Det_b={$this->getIdSubForm()}");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    $response['mensaje']=array(
                        'nombre'=>$row->GUIDET_Nombre____b,
                        'campoPadre'=>Helpers::encrypt($row->GUIDET_ConsInte__PREGUN_Ma1_b),
                        'campoHijo'=>Helpers::encrypt($row->GUIDET_ConsInte__PREGUN_De1_b),
                        'campoTotalPadre'=>$row->GUIDET_ConsInte__PREGUN_Totalizador_b != '0' ? Helpers::encrypt($row->GUIDET_ConsInte__PREGUN_Totalizador_b) : "",
                        'campoTotalHijo'=>$row->GUIDET_ConsInte__PREGUN_Totalizador_H_b != '0' ? Helpers::encrypt($row->GUIDET_ConsInte__PREGUN_Totalizador_H_b) : "",
                        'operTotal'=>$row->GUIDET_Oper_Totalizador_b,
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LAS COMUNICACIONES DE UN SUBFORMULARIO
    public function getComuForm():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT COMUFORM_IdPregun_Padre_b,COMUFORM_IdPregun_hijo_b FROM DYALOGOCRM_SISTEMA.COMUFORM WHERE COMUFORM_Guion_Padre_b={$this->getIdForm()} AND COMUFORM_Guion_hijo_b={$this->getIdSubForm()}");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'campoPadre'=>Helpers::encrypt($row->COMUFORM_IdPregun_Padre_b),
                            'campoHijo'=>Helpers::encrypt($row->COMUFORM_IdPregun_hijo_b),
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LA INFORMACIÓN DE UN CAMPO DE TIPO LISTA AUXILIAR
    public function getInfoListaAux():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT A.CAMPO__ConsInte__PREGUN_b AS hijo,B.CAMPO__ConsInte__PREGUN_b AS padre FROM DYALOGOCRM_SISTEMA.PREGUI LEFT JOIN DYALOGOCRM_SISTEMA.CAMPO_ A ON PREGUI_ConsInte__CAMPO__b=A.CAMPO__ConsInte__b LEFT JOIN DYALOGOCRM_SISTEMA.CAMPO_ B ON PREGUI_Consinte__CAMPO__GUI_B=B.CAMPO__ConsInte__b WHERE PREGUI_ConsInte__PREGUN_b={$this->getIdPregun()} AND PREGUI_Consinte__GUION__b={$this->getIdForm()}");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'campoPadre'=>!is_null($row->padre) ? Helpers::encrypt($row->padre) : 0,
                            'campoHijo'=>Helpers::encrypt($row->hijo),
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LOS SALTOS DE UN FORMULARIO
    public function getSaltos():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT A.PRSADE_ConsInte__PREGUN_b,B.PREGUN_Texto_____b FROM (SELECT PRSADE_ConsInte__PREGUN_b FROM DYALOGOCRM_SISTEMA.PRSADE WHERE PRSADE_ConsInte__GUION__b ={$this->getIdForm()} AND PRSADE_By_SECCIO_b= 0 GROUP BY PRSADE_ConsInte__PREGUN_b) A LEFT JOIN (SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b={$this->getIdForm()}) B ON A.PRSADE_ConsInte__PREGUN_b=B.PREGUN_ConsInte__b");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'nombre'=>$row->PREGUN_Texto_____b,
                            'campoSalto'=>$row->PRSADE_ConsInte__PREGUN_b,
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LOS SALTOS POR SECCION DE UN FORMULARIO
    public function getSaltosSeccion():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT A.PRSADE_ConsInte__PREGUN_b,B.PREGUN_Texto_____b FROM (SELECT PRSADE_ConsInte__PREGUN_b FROM DYALOGOCRM_SISTEMA.PRSADE WHERE PRSADE_ConsInte__GUION__b ={$this->getIdForm()} AND PRSADE_By_SECCIO_b= -1 GROUP BY PRSADE_ConsInte__PREGUN_b) A LEFT JOIN (SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b={$this->getIdForm()}) B ON A.PRSADE_ConsInte__PREGUN_b=B.PREGUN_ConsInte__b");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'nombre'=>$row->PREGUN_Texto_____b,
                            'campoSalto'=>$row->PRSADE_ConsInte__PREGUN_b,
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LOS ITEMS DE UN SALTO
    public function getInfoSalto($idPregun):array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT PRSADE_ConsInte__b, PRSADE_ConsInte__OPCION_b, PRSASA_ConsInte__PREGUN_b, PRSASA_Limpiar_b FROM DYALOGOCRM_SISTEMA.PRSADE JOIN DYALOGOCRM_SISTEMA.PRSASA ON PRSASA_ConsInte__PRSADE_b = PRSADE_ConsInte__b WHERE PRSADE_ConsInte__PREGUN_b ={$idPregun} AND PRSADE_ConsInte__GUION__b ={$this->getIdForm()}  AND PRSASA_ConsInte__PREGUN_b >0");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'campoPregun'=> $row->PRSADE_ConsInte__OPCION_b,
                            'camposGuion'=> $row->PRSASA_ConsInte__PREGUN_b,
                            'id' => $row->PRSADE_ConsInte__b,
                            'limpiarCampo' => $row->PRSASA_Limpiar_b
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // OBTENER LOS ITEMS DE UN SALTO POR SECCIÓN
    public function getInfoSaltoSeccion($idPregun):array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT PRSADE_ConsInte__b, PRSADE_ConsInte__OPCION_b, PRSASA_ConsInte__PREGUN_b, PRSASA_Limpiar_b FROM DYALOGOCRM_SISTEMA.PRSADE JOIN DYALOGOCRM_SISTEMA.PRSASA ON PRSASA_ConsInte__PRSADE_b = PRSADE_ConsInte__b WHERE PRSADE_ConsInte__PREGUN_b ={$idPregun} AND PRSADE_ConsInte__GUION__b ={$this->getIdForm()}  AND PRSASA_ConsInte__PREGUN_b =-1");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'campoPregun'=> $row->PRSADE_ConsInte__OPCION_b,
                            'camposGuion'=> $row->PRSASA_ConsInte__PREGUN_b,
                            'id' => $row->PRSADE_ConsInte__b,
                            'limpiarCampo' => $row->PRSASA_Limpiar_b,
                            'seccion' => $row->PRSASA_ConsInte__SECCIO_b
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }
}