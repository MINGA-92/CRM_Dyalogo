<?php
trait InfoEstpas
{
    public $idEstpas;

    private function armarCamposPost(array $data):string
    {
        $strData='';
        foreach($data as $i=>$item){
            if(is_array($item) && count($item) > 0){
                foreach($item as $value){
                    $concat=$strData === '' ? '' : "&";
                    $strData.=$concat.$i."[]=".$value;
                }
            }else{
                $concat=$strData === '' ? '' : "&";
                $strData.=$concat.$i."=".$item;
            }
        }
        return $strData;
    }

    public function getInfoCargueManual():array
    {
        $postFields="pasoId={$this->idEstpas}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G35/G35_CRUD.php?getDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoWebService(int $bd):array
    {
        $postFields="pasoId={$this->idEstpas}&bd={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getwebservice=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoWebForm(int $bd):array
    {
        $postFields="obtenerDatos=true&pasoId={$this->idEstpas}&poblacion={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G13/G13_CRUD.php";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoCampanaSaliente():array
    {
        $postFields="CallDatos_2=Si&id={$this->idEstpas}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD.php";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoCampanaEntrante():array
    {
        $postFields="CallDatos_2=Si&id={$this->idEstpas}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD_v2.php";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoCorreoEntrante(int $bd):array
    {
        $postFields="pasoId={$this->idEstpas}&bd={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G31/G31_CRUD.php?getDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoChatEntrante(int $bd):array
    {
        $postFields="pasoId={$this->idEstpas}&bd={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G28/G28_CRUD.php?getDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }
    
    public function getInfoWhatsapp(int $bd):array
    {
        $postFields="pasoId={$this->idEstpas}&bd={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G29/G29_CRUD.php?getDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoInstagram(int $bd):array
    {
        $postFields="pasoId={$this->idEstpas}&bd={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G34/G34_CRUD.php?getDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoFacebook(int $bd):array
    {
        $postFields="pasoId={$this->idEstpas}&bd={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G30/G30_CRUD.php?getDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoCorreoSaliente(int $bd):array
    {
        $postFields="id_paso={$this->idEstpas}&poblacion={$bd}&CallDatos_paso=SI&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G14/G14_CRUD.php";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoSmsSaliente(int $bd):array
    {
        $postFields="id={$this->idEstpas}&poblacion={$bd}&CallDatos_2=SI&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G15/G15_CRUD.php";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function getInfoSmsEntrante(int $bd):array
    {
        $postFields="pasoId={$this->idEstpas}&bd={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G32/G32_CRUD.php?getDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }
    
    public function getInfoBackoffice(int $bd):array
    {
        $postFields="key={$this->idEstpas}&guion={$bd}&infoWS=si";
        $url=IP_CRUDS_BPO."cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?verificarTareasBackoffice=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function saveInfoWebService(array $data):array
    {
        $data['cllave']=Helpers::decodeId($data['cllave'],"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN");
        $data['pasowsId']=$this->idEstpas;
        if($data['validaConRestriccion'] != 1){
            unset($data['validaConRestriccion']);
        }
        $postFields=$this->armarCamposPost($data);
        $url=IP_CRUDS_G."G2/G2_extender_funcionalidad.php?guardarwebservice=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function saveInfoCargueManual(array $data):array
    {
        $data['id_paso']=$this->idEstpas;
        $data['oper']='edit';
        if($data['pasoActivo'] != -1){
            unset($data['pasoActivo']);
        }
        $postFields=$this->armarCamposPost($data);
        $url=IP_CRUDS_G."G35/G35_CRUD.php?insertarDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function saveInfoWebForm(array $data, int $bd):array
    {
        $data['wfPasoId']=$this->idEstpas;
        $data['wfPoblacion']=$bd;
        $data['wfPregunValidacion']=Helpers::decodeId($data['wfPregunValidacion'],"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN");
        $postFields=$this->armarCamposPost($data);
        $url=IP_CRUDS_G."G13/G13_CRUD.php?insertarDatos=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }

    public function saveCamposWebform(array $data, int $bd):array
    {
        $idWeb=$data['webformId'];
        unset($data['webformId']);
        foreach($data as $i=>$item){
            $data['arrCampos'][$i]=Helpers::decodeId($item,"PREGUN_ConsInte__b","DYALOGOCRM_SISTEMA.PREGUN");
            unset($data[$i]);
        }
        $data['webformId']=$idWeb;
        $data['poblacion']=$bd;
        $data['generarByWS']="si";
        $postFields=$this->armarCamposPost($data);
        $url=IP_CRUDS_G."G13/G13_CRUD.php?agregarCamposWeb=true";
        return array('postFields'=>$postFields,'url'=>$url);
    }
}