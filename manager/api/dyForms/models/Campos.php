<?php

require_once('Seccion.php');

class Campos extends Seccion{
    public function __construct()
    {
        parent::__construct();
    }

    // INSERTA UN REGISTRO EN CAMPO_ DEL CAMPO CREADO EN PREGUN
    private function insertAtCampo_(int $idPregun):int
    {
        $nameCampo="G{$this->getIdForm()}_C{$idPregun}";
        $sql=parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.CAMPO_ (CAMPO__Nombre____b,CAMPO__Tipo______b,CAMPO__ConsInte__PREGUN_b) VALUES ('{$nameCampo}',{$this->getIntCampoTipo()},{$idPregun})");
        if(!$sql){
            $this->error();
        }
        return parent::$db->insert_id;
    }

    // OBTENGO EL ID DE CAMPO_ PARA INSERTAR ENLACE DE LISTAS AUXILIARES
    private function getIdCampo_($idPregun):int
    {
        $sql=parent::$db->query("SELECT CAMPO__ConsInte__b FROM DYALOGOCRM_SISTEMA.CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b={$idPregun}");
        if(!$sql){
            $this->error();
        }
        if($sql->num_rows == 0){
            $this->error();
        }
        $sql=$sql->fetch_object();
        return $sql->CAMPO__ConsInte__b;
    } 

    // AGREGAR UN CAMPO EN PREGUN_
    public function addCampo():array
    {
        $response=array();
        $response['estado']=true;
        $campos="
            '{$this->getStrCampoNombre()}',
            {$this->getIntCampoTipo()},
            {$this->getStrCampoGuionAux()},
            {$this->getStrCampoLista()},
            {$this->getIntCampoOrden()},
            {$this->getIntCampoMinimoNumero()},
            {$this->getIntCampoMaximoNumero()},
            {$this->getStrCampoFechaMinimo()},
            {$this->getStrCampoFechaMaximo()},
            {$this->getStrCampoHoraMini()},
            {$this->getStrCampoHoraMaximo()},
            {$this->getStrCampoError()},
            {$this->getIntCampoValorDefecto()},
            {$this->getIntCampoVista()},
            {$this->getIntCampoPermiteAdicion()},
            {$this->getStrCampoPregunPadre()},
            {$this->getIntCampoNumDefecto()},
            {$this->getStrCampotextDefecto()},
            {$this->getIntCampoTiempoMas()},
            {$this->getStrCampoPeriodo()},
            {$this->getStrCampoformula()},
            {$this->getIntCampoTipoTel()},
            {$this->getIntCampoSendMail()},
            {$this->getIntCampoSendSms()},
            {$this->getIntCampoTxtSms()},
            {$this->getIntCampoPrefijoSms()},
            {$this->getIntCampoBuscaMail()},
            {$this->getIntCampoFormato()},
            {$this->getIntCampoPosDecimales()},
            {$this->getIntCampoLongitud()},
            {$this->getIntCampoMostrarSubForm()},
            {$this->getIntCampoWebForm()},
            {$this->getIdForm()},
            {$this->getIdSeccion()},
            0,
            {$this->getIntCampoIdBPO()}
        ";
        $sql=parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.PREGUN (PREGUN_Texto_____b,PREGUN_Tipo______b,PREGUN_ConsInte__GUION__PRE_B,PREGUN_ConsInte__OPCION_B,PREGUN_OrdePreg__b,PREGUN_NumeMini__b,PREGUN_NumeMaxi__b,PREGUN_FechMini__b,PREGUN_FechMaxi__b ,PREGUN_HoraMini__b,PREGUN_HoraMaxi__b,PREGUN_TexErrVal_b,PREGUN_Default___b,PREGUN_ContAcce__b,PREGUN_PermiteAdicion_b,PREGUN_ConsInte_PREGUN_Depende_b,PREGUN_DefaNume__b,PREGUN_DefaText__b,PREGUN_DefCanFec_b,PREGUN_DefUniFec_b,PREGUN_OperEntreCamp_____b,PREGUN_TipoTel_b,PREGUN_SendMail_b,PREGUN_SendSMS_b,PREGUN_textSMS_b,PREGUN_PrefijoSms_b,PREGUN_SearchMail_b,PREGUN_Formato_b,PREGUN_PosDecimales_b,PREGUN_Longitud__b,PREGUN_MostrarSubForm,PREGUN_WebForm_b,PREGUN_ConsInte__GUION__b,PREGUN_ConsInte__SECCIO_b,PREGUN_FueGener_b,PREGUN_IdPregunBPO) VALUES ({$campos})");
        if(!$sql){
            $this->error();
        }
        $response['mensaje']=Helpers::encrypt(parent::$db->insert_id);
        //INSERTAMOS EN CAMPO_
        $this->insertAtCampo_(parent::$db->insert_id);
        return $response;
    }

    // AGREGA ENLACE A UN CAMPO DE TIPO LISTA AUXILIAR
    public function addConfigListaAuxiliar():array
    {
        $response=array();
        $response['estado']=true;
        $idCampoPregun=$this->getIntAuxIdCampo() != 0 ? $this->getIdCampo_($this->getIntAuxIdCampo()) : 0;
        $idCampoAux=$this->getIdCampo_($this->getIntAuxCampoGuion());
        $sql=parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.PREGUI (PREGUI_ConsInte__PREGUN_b,PREGUI_ConsInte__CAMPO__b,PREGUI_Consinte__GUION__b,PREGUI_Consinte__CAMPO__GUI_B) VALUES ({$this->getIntCampoId()},{$idCampoAux},{$this->getIntAuxIdGuion()},{$idCampoPregun})");
        if(!$sql){
            $this->error();
        }
        $response['mensaje']=Helpers::encrypt(parent::$db->insert_id);
        return $response;
    }

    // INSERTA LA CONFIGURACIÓN DE UN SUBFORMULARIO
    public function addConfigSubForm():array
    {
        $response=array();
        $response['estado']=true;
        $campos="
            {$this->getIdForm()},
            {$this->getIntSubFormGuionHijo()},
            '{$this->getStrSubFormNombre()}',
            {$this->getIntSubFormLlavePadre()},
            {$this->getIntSubFormLlaveHijo()},
            0,
            {$this->getIntCampoId()},
            {$this->getIntSubFormTotalPadre()},
            {$this->getIntSubFormTotalHijo()},
            {$this->getIntSubFormOperTotal()}
        ";
        $sql=parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.GUIDET (GUIDET_ConsInte__GUION__Mae_b,GUIDET_ConsInte__GUION__Det_b,GUIDET_Nombre____b,GUIDET_ConsInte__PREGUN_Ma1_b,GUIDET_ConsInte__PREGUN_De1_b,GUIDET_Modo______b,GUIDET_ConsInte__PREGUN_Cre_b,GUIDET_ConsInte__PREGUN_Totalizador_b,GUIDET_ConsInte__PREGUN_Totalizador_H_b,GUIDET_Oper_Totalizador_b) VALUES ({$campos})");
        if(!$sql){
            $this->error();
        }
        $response['mensaje']=Helpers::encrypt(parent::$db->insert_id);
        return $response;
    }

    // INSERTA UN ENLACE DE COMUNICACIÓN ENTRE FORM Y SUBFORMULARIO
    public function addComuSubForm():array
    {
        $response=array();
        $response['estado']=true;
        $campos="
            {$this->getIdForm()},
            {$this->getIntSubFormLlavePadre()},
            {$this->getIntSubFormGuionHijo()},
            {$this->getIntSubFormLlaveHijo()}
        ";
        $sql=parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.COMUFORM (COMUFORM_Guion_Padre_b,COMUFORM_IdPregun_Padre_b,COMUFORM_Guion_hijo_b,COMUFORM_IdPregun_hijo_b) VALUES ({$campos})");
        if(!$sql){
            $this->error();
        }
        $response['mensaje']=Helpers::encrypt(parent::$db->insert_id);
        return $response;
    }   
}