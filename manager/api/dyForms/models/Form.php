<?php
// LOS ATRIBUTOS Y EL ENCAPSULAMIENTO DE LOS MISMOS SE MANEJAN EN EL ARCHIVO ENCAPSULADO.PHP

require_once('AtributosForm.php');
class Form extends AtributosForm{

    public function __construct()
    {
        parent::__construct();
    }

    // REEMPLAZAMOS EL ID DE LA LISTAS DE SISTEMA; LES PONEMOS EL ID DEL FORMULARIO AL QUE PERTENECEN, YA QUE LLEGAN CON EL ID DEL FORMULARIO QUE SE ESTA CLONANDO
    private function replaceNameLista(string $nameLista):string
    {
        $newNameLista=explode("ESTADO_DY_",$nameLista);
        if(count($newNameLista) > 1){
            $newNameLista=$newNameLista[1];
            $nameLista=str_replace($newNameLista,$this->getIdForm(),$nameLista);
        }
        return $nameLista;
    }

    // VALIDAR QUE LA PRIMERA OPCION DE PRSADE NO ESTE VACIA
    private function upFirstItemPrsade(int $idOpcionSalto):int
    {
        $response=0;
        $sql=Parent::$db->query("SELECT PRSADE_ConsInte__b FROM DYALOGOCRM_SISTEMA.PRSADE WHERE PRSADE_ConsInte__GUION__b={$this->getIdForm()} AND PRSADE_ConsInte__PREGUN_b={$this->getIntCampoId()} AND PRSADE_ConsInte__OPCION_b IS NULL");

        if(!$sql){
            $this->error();
        }else{
            if($sql->num_rows == 1){
                $sql=$sql->fetch_object();
                $sqlUp=Parent::$db->query("UPDATE DYALOGOCRM_SISTEMA.PRSADE SET PRSADE_ConsInte__OPCION_b={$idOpcionSalto} WHERE PRSADE_ConsInte__b={$sql->PRSADE_ConsInte__b}");
                if(!$sqlUp){
                    $this->error();
                }

                if(parent::$db->affected_rows == 1){
                    $response=$sql->PRSADE_ConsInte__b;
                }
            }
        }
        return $response;
    }

    // CREAR UN FORMULARIO
    public function addForm():array
    {
        $response=array();
        $response['estado']=true;
        $sql=Parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.GUION_ (GUION__Nombre____b,GUION__Observaci_b,GUION__ConsInte__PROYEC_b,GUION__Tipo______b,GUION__TipoBusqu__Manual_b,GUION_INSERTAUTO_b,GUION_PERMITEINSERTAR_b,GUION_IdGuionBPO) VALUES ('{$this->getStrNombre()}','{$this->getStrDesc()}',{$this->getStrHuesped()},{$this->getIntTipo()},{$this->getIntTipoBQ()},{$this->getInsertAuto()},{$this->getPermiteInsert()},{$this->getIdFormBPO()})");

        if(!$sql){
            $this->error();
        }

        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        return $response;
    }

    // CREAR UNA LISTA A UN FORMULARIO
    public function addLista():array
    {
        $response=array();
        $response['estado']=true;
        $nameLista=$this->replaceNameLista($this->getStrListaNombre());
        $sql=Parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.OPCION (OPCION_ConsInte__GUION__b,OPCION_Nombre____b,OPCION_ConsInte__PROYEC_b,OPCION_FechCrea__b,OPCION_UsuaCrea__b,OPCION_IdListaBPO) VALUES ({$this->getIdForm()},'{$nameLista}',{$this->getStrHuesped()},NOW(),-10,{$this->getIntListaIdBPO()})");
        if(!$sql){
            $this->error();
        }

        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        return $response;
    }

    // AGREGAR UNA OPCION A UNA LISTA
    public function addOpcionLista():array
    {
        $response=array();
        $response['estado']=true;
        $sql=Parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.LISOPC (LISOPC_Nombre____b,LISOPC_ConsInte__OPCION_b,LISOPC_Posicion__b,LISOPC_Clasifica_b,LISOPC_Valor____b,LISOPC_Respuesta_b,LISOPC_ConsInte__LISOPC_Depende_b,LISOPC_IdLisopcBPO) VALUES ('{$this->getStrOpcionNombre()}',{$this->getIntListaId()},{$this->getIntOpcionPosicion()},{$this->getIntOpcionMonoef()},{$this->getIntOpcionClasificacion()},{$this->getStrOpcionRpta()},{$this->getIntOpcionIdPadre()},{$this->getIntOpcionIdLisopcBPO()})");
        if(!$sql){
            $this->error();
        }

        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        return $response;
    }

    // ASIGNAR CAMPO PRINCIPAL, CAMPO SECUNDARIO Y CAMPO LLAVE A UN FORMULARIO
    public function addEtiquetado():array
    {
        $response=array();
        $response['estado']=true;
        $sql=Parent::$db->query("UPDATE DYALOGOCRM_SISTEMA.GUION_ SET GUION__ConsInte__PREGUN_Pri_b={$this->getIntEtiquetaPrincipal()}, GUION__ConsInte__PREGUN_Sec_b={$this->getIntEtiquetaSecundaria()}, GUION__ConsInte__PREGUN_Llave_b={$this->getIntEtiquetaLlave()} WHERE GUION__ConsInte__b={$this->getIdForm()}");
        if(!$sql){
            $this->error();
        }

        $response['mensaje']="Campos principal y secundario asignados";
        return $response;
    }
    
    // ACTUALIZAR EN GUION LOS CAMPOS DE CONTROL
    public function upCamposControl(array $arrCamposControl):array{
        $response=array();
        $response['estado']=true;
        $sql=Parent::$db->query("UPDATE DYALOGOCRM_SISTEMA.GUION_ SET GUION__ConsInte__PREGUN_Age_b={$arrCamposControl['strIdCampoAgente']}, GUION__ConsInte__PREGUN_Fec_b={$arrCamposControl['strIdCampoFecha']}, GUION__ConsInte__PREGUN_Hor_b={$arrCamposControl['strIdCampoHora']}, GUION__ConsInte__PREGUN_Tip_b={$arrCamposControl['strIdCampoTipificar']}, GUION__ConsInte__PREGUN_Rep_b={$arrCamposControl['strIdCampoReintento']}, GUION__ConsInte__PREGUN_Fag_b={$arrCamposControl['strIdCampoFechaAgenda']}, GUION__ConsInte__PREGUN_Hag_b={$arrCamposControl['strIdCampoHoraAgenda']}, GUION__ConsInte__PREGUN_Com_b={$arrCamposControl['strIdCampoObservacion']} WHERE GUION__ConsInte__b={$this->getIdForm()}");
        if(!$sql){
            $this->error();
        }

        $response['mensaje']="Campos de control asignados";
        return $response;
    }
    
    // AGREGAR SALTOS A UN FORMULARIO
    public function addSalto():array
    {
        $response=array();
        $response['estado']=true;
        $strNombreCampo="G{$this->getIdForm()}_C{$this->getIntCampoId()}";
        $sql=Parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.PRSADE (PRSADE_ConsInte__GUION__b,PRSADE_ConsInte__PREGUN_b,PRSADE_NombCont__b,PRSADE_By_SECCIO_b) VALUES ({$this->getIdForm()},{$this->getIntCampoId()},'{$strNombreCampo}',{$this->getIntSaltoBySeccion()})");

        if(!$sql){
            $this->error();
        }

        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        return $response;
    }

    // AGREGAR ITEMS A LOS SALTOS
    public function addItemSalto():array
    {
        $response=array();
        $response['estado']=true;
        $strNombreCampo="G{$this->getIdForm()}_C{$this->getIntCampoId()}";
        $idSalto=$this->upFirstItemPrsade($this->getIntOpcionId());
        if(!$idSalto){
            $sql=Parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.PRSADE (PRSADE_ConsInte__GUION__b,PRSADE_ConsInte__PREGUN_b,PRSADE_NombCont__b,PRSADE_By_SECCIO_b,PRSADE_ConsInte__OPCION_b) VALUES ({$this->getIdForm()},{$this->getIntCampoId()},'{$strNombreCampo}',{$this->getIntSaltoBySeccion()}, {$this->getIntOpcionId()})");
    
            if(!$sql){
                $this->error();
            }
            $idSalto=Parent::$db->insert_id;
        }

        // INSERTAMOS EN PRSASA
        $sql=Parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.PRSASA VALUES ({$idSalto},{$this->getIntItemSaltoIdPregunDet()},{$this->getIntItemSaltoIdSeccionDet()},'{$this->getStrItemSaltoNombreDet()}',{$this->getIntItemSaltoTipoPregunDet()},{$this->getIntItemSaltoLimpiarDet()})");
        if(!$sql){
            $this->error();
        }

        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        return $response;
    }

    public function addCampoRequeridoTip():void
    {
        //code here...
    }
}