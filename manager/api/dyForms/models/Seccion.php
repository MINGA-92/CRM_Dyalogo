<?php   
require_once('Form.php');

class Seccion extends Form{

    public function __construct()
    {
        parent::__construct();
    }

    // INSERTO UNA SECCIÓN A UN FORMULARIO
    public function addSeccion():array
    {
        $response=array();
        $response['estado']=true;
        $sql=parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.SECCIO (SECCIO_Nombre____b,SECCIO_Orden_____b,SECCIO_ConsInte__GUION__b,SECCIO_VistPest__b,SECCIO_FechCrea__b,SECCIO_TipoSecc__b,SECCIO_PestMini__b,SECCIO_NumColumnas_b,SECCIO_IdSeccioBPO) VALUES ('{$this->getSeccioNombre()}', {$this->getSeccioOrden()}, {$this->getIdForm()}, {$this->getSeccioVista()}, NOW(), {$this->getSeccioTipo()}, {$this->getSeccioMini()}, {$this->getNumColumnas()},{$this->getSeccioIdBPO()})");

        if(!$sql){
            $this->error();
        }

        $response['mensaje']=Helpers::encrypt(parent::$db->insert_id);
        return $response;
    }
}