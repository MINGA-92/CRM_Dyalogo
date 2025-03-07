<?php
require_once("AtributosEstrat.php");
require_once("InfoEstpas.php");
class Estrategias extends AtributosEstrat
{
    use InfoEstpas;
    public function __construct(bool $bpo=false)
    {
        Parent::__construct($bpo);
    }

    // CREAMOS LA TABLA DE LA MUESTRA PARA LOS PASOS QUE USAN MUESTRA
    private function createTableMuestra(int $idGuion, int $idMuestra)
    {
        $data =[
            "idGuion" 	=> $idGuion,
            "idMuestra" => $idMuestra,
            "createTableMuestra" => true
        ];
        $response = json_decode($this->consumirWSJSON(IP_CRUDS,$data),true);
        return $response['response'];
    }

    // INSERTAMOS UN REGISTRO PARA ASIGNAR UNA MUESTRA A UN PASO
    private function insertarMuestra(int $idGuion, int $idEstpas):bool
    {
        $nameMuestra="{$idGuion}_MUESTRA_".rand();
        $sql=Parent::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.MUESTR (MUESTR_Nombre____b, MUESTR_ConsInte__GUION__b) VALUES ('{$nameMuestra}', {$idGuion})");
        if($sql){
            //CREAMOS LA TABLA DE LA MUESTRA
            $idMuestra=Parent::$db->insert_id;
            if($this->createTableMuestra($idGuion,$idMuestra)){
                $sql=Parent::$db->query("UPDATE DYALOGOCRM_SISTEMA.ESTPAS SET ESTPAS_ConsInte__MUESTR_b={$idMuestra}, ESTPAS_key_____b={$idEstpas} WHERE ESTPAS_ConsInte__b={$idEstpas}");
                if($sql){
                    return true;
                }
            }
        }
        return false;
    }

    // OBTENER EL TIPO DE PASO POR EL ID
    private function obtenerTipoPaso(int $idEstpas):int
    {
        $sql=self::$db->query("SELECT ESTPAS_Tipo______b FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__b = {$idEstpas}");
        if($sql && $sql->num_rows == 1){
            $sql=$sql->fetch_object();
            return $sql->ESTPAS_Tipo______b;
        }
        $this->error();
    }

    //OBTENEMOS LA CONFIGURACIÓN DE UN PASO
    private function getConfigEstpasById(int $idEstpas):string
    {
        //OBTENEMOS EL TIPO DE PASO
        (int)$tipo=$this->obtenerTipoPaso($idEstpas);
        (array)$info=json_encode(array("data"=>$tipo));
        if($tipo != 5){
            switch($tipo){
                case 1:
                    $info=$this->getInfoCampanaEntrante();
                    break;
                case 4:
                    $info=$this->getInfoWebForm($this->getStrEstratIdGuion());
                    break;
                case 6:
                    $info=$this->getInfoCampanaSaliente();
                    break;
                case 7:
                    $info=$this->getInfoCorreoSaliente($this->getStrEstratIdGuion());
                    break;
                case 8:
                    $info=$this->getInfoSmsSaliente($this->getStrEstratIdGuion());
                    break;
                case 9:
                    $info=$this->getInfoBackoffice($this->getStrEstratIdGuion());
                    break;
                case 10:
                    // PARA EL PASO DE LEADS QUE INGRESAN POR CORREOS
                    break;
                case 11:
                    $info=$this->getInfoWebService($this->getStrEstratIdGuion());
                    break;
                case 13:
                    // PARA EL PASO DE LAS PLANTILLAS DE WHATSAPP
                    break;
                case 14:
                    $info=$this->getInfoChatEntrante($this->getStrEstratIdGuion());
                    break;
                case 15:
                    $info=$this->getInfoWhatsapp($this->getStrEstratIdGuion());
                    break;
                case 16:
                    $info=$this->getInfoFacebook($this->getStrEstratIdGuion());
                    break;
                case 17:
                    $info=$this->getInfoCorreoEntrante($this->getStrEstratIdGuion());
                    break;
                case 18:
                    $info=$this->getInfoSmsEntrante($this->getStrEstratIdGuion());
                    break;
                case 19:
                    $info=$this->getInfoWebForm($this->getStrEstratIdGuion());
                    break;
                case 20:
                    $info=$this->getInfoInstagram($this->getStrEstratIdGuion());
                    break;
                case 21:
                    $info=$this->getInfoCargueManual();
                    break;
                case 22:
                    // PARA EL PASO DE MARCADOR ROBOTICO
                    break;
            }
            if(is_array($info)){
                $info=$this->consumirFormData($info['url'],$info['postFields']);
            }
        }
        return $info;
    }

    private function addConfigEstpasById(array $info):string
    {
        if($info['tipoPaso'] != 5){
            switch($info['tipoPaso']){
                case 1:
                    $info=$this->getInfoCampanaEntrante();
                    break;
                case 4:
                    $info=$this->saveInfoWebForm($info,$this->getStrEstratIdGuion());
                    break;
                case 6:
                    $info=$this->getInfoCampanaSaliente();
                    break;
                case 11:
                    $info=$this->saveInfoWebService($info);
                    break;
                case 21:
                    $info=$this->saveInfoCargueManual($info);
                    break;
                }
                $info=$this->consumirFormData($info['url'],$info['postFields']);
        }
        return $info;
    }

    // OBTENER LAS ESTRATEGIAS QUE ESTAN PUBLICADAS EN BPO PARA USARLAS COMO PLANTILLAS
    public function getEstrategias():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT ESTRAT_Nombre____b,ESTRAT_ConsInte__b,ESTRAT_Comentari_b,ESTRAT_Color____b,ESTRAT_ConsInte_GUION_Pob FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__PROYEC_b = 198 AND ESTRAT_Publicar = -1");
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    $id=Helpers::encrypt($row->ESTRAT_ConsInte__b);
                    $nombre=$row->ESTRAT_Nombre____b;
                    $comentario=$row->ESTRAT_Comentari_b;
                    $idPoblacion=Helpers::encrypt($row->ESTRAT_ConsInte_GUION_Pob);
                    array_push($response['mensaje'],array('id'=>$id,'nombre'=>$nombre,'descripcion'=>$comentario,'idPoblacion'=>$idPoblacion,'color'=>$row->ESTRAT_Color____b));
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // CREAR UNA ESTRATEGIA
    public function addEstrat():array
    {
        $response=array();
        $response['estado']=true;
        $sql=self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.ESTRAT (ESTRAT_ConsInte__PROYEC_b,ESTRAT_ConsInte__TIPO_ESTRAT_b,ESTRAT_Nombre____b,ESTRAT_Comentari_b,ESTRAT_Color____b,ESTRAT_ConsInte_GUION_Pob) VALUES ({$this->getStrIdHuesped()},3,'{$this->getStrEstratNombre()}',{$this->getStrEstratComentari()},'{$this->getStrEstratColor()}',{$this->getStrEstratIdGuion()})");
        if(!$sql){
            $this->error();
        }
        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        return $response;
    }

    // OBTENER INFORMACIÓN DE TODOS LOS PASOS DE UNA ESTRATEGIA
    public function getEstpas():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT ESTPAS_ConsInte__b,ESTPAS_Nombre__b,ESTPAS_Tipo______b,ESTPAS_Comentari_b,ESTPAS_ConsInte__CAMPAN_b,ESTPAS_Loc______b,ESTPAS_activo,ESTPAS_Generado_Por_Sistema_b FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = {$this->getIdEstrat()}");
        
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    $id=Helpers::encrypt($row->ESTPAS_ConsInte__b);
                    $namePaso=$row->ESTPAS_Nombre__b;
                    $tipoPaso=$row->ESTPAS_Tipo______b;
                    $comentarioPaso=$row->ESTPAS_Comentari_b;
                    $idCampan=is_numeric($row->ESTPAS_ConsInte__CAMPAN_b) ? Helpers::encrypt($row->ESTPAS_ConsInte__CAMPAN_b) : $row->ESTPAS_ConsInte__CAMPAN_b;
                    $locPaso=$row->ESTPAS_Loc______b;
                    $pasoActivo=$row->ESTPAS_activo;
                    $pasoBySistema=$row->ESTPAS_Generado_Por_Sistema_b;
                    array_push(
                        $response['mensaje'],
                        array(
                            'id'=>$id,
                            'nombre'=>$namePaso,
                            'tipo'=>$tipoPaso,
                            'comentario'=>$comentarioPaso,
                            'idCampan'=>$idCampan,
                            "ubicacion"=>$locPaso,
                            "activo"=>$pasoActivo,
                            "bySistema"=>$pasoBySistema
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    public function addEstpas():array
    {
        $response=array();
        $response['estado']=true;
        $tipo=$this->getStrEstpasTipo();
        $campos="
            '{$this->getStrEstpasNombre()}',
            {$tipo},
            {$this->getStrEstpasComentario()},
            {$this->getIdEstrat()},
            {$this->getIntEstpasActivo()},
            {$this->getIntEstpasBySistema()},
            {$this->getStrEstpasLoca()}
        ";
        $sql=self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.ESTPAS (ESTPAS_Nombre__b,ESTPAS_Tipo______b,ESTPAS_Comentari_b,ESTPAS_ConsInte__ESTRAT_b,ESTPAS_activo,ESTPAS_Generado_Por_Sistema_b,ESTPAS_Loc______b) VALUES ({$campos})");
        if(!$sql){
            $this->error();
        }

        //INSERTAR LA MUESTRA
        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        if($tipo == '7' || $tipo == '8' || $tipo == '9' || $tipo == '13' || $tipo == '23'){
           if(!$this->insertarMuestra($this->getIdForm(),Parent::$db->insert_id)){
                $this->error();
           }
        }
        return $response;
    }

    public function getEstcon():array
    {
        $response=array();
        $response['estado']=false;
        $response['mensaje']=array();
        $sql=self::$db->query("SELECT ESTCON_ConsInte__b,ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Coordenadas_b, ESTCON_Comentari_b FROM DYALOGOCRM_SISTEMA.ESTCON WHERE ESTCON_ConsInte__ESTRAT_b = {$this->getIdEstrat()}");
        
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                while($row = $sql->fetch_object()){
                    array_push(
                        $response['mensaje'],
                        array(
                            'id'=>Helpers::encrypt($row->ESTCON_ConsInte__b),
                            'nombre'=>$row->ESTCON_Nombre____b,
                            'origen'=>Helpers::encrypt($row->ESTCON_ConsInte__ESTPAS_Des_b),
                            'destino'=>Helpers::encrypt($row->ESTCON_ConsInte__ESTPAS_Has_b),
                            'portOrigen'=>$row->ESTCON_FromPort_b,
                            'portDestino'=>$row->ESTCON_ToPort_b,
                            "coordenadas"=>$row->ESTCON_Coordenadas_b,
                            "comentario"=>$row->ESTCON_Comentari_b,
                        )
                    );
                }
            }
        }else{
            $this->error();
        }
        return $response;
    }

    public function addEstcon():array
    {
        $response=array();
        $response['estado']=true;
        $campos="
            '{$this->getStrEstconNombre()}',
            {$this->getStrEstconIdOrigen()},
            {$this->getStrEstconIdDestino()},
            '{$this->getStrEstconPortOrigen()}',
            '{$this->getStrEstconPortDestino()}',
            {$this->getStrEstconCoordenadas()},
            {$this->getStrEstconComentario()},
            {$this->getIdEstrat()},
            0, 0, -1, 1, -1, 1
        ";
        $sql=self::$db->query("INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Coordenadas_b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b, ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_Operacion_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Hora_b) VALUES ({$campos})");
        echo "INSERT INTO DYALOGOCRM_SISTEMA.ESTCON (ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Coordenadas_b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b, ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_Operacion_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Hora_b) VALUES ({$campos})";
        if(!$sql){
            $this->error();
        }
        $response['mensaje']=Helpers::encrypt(Parent::$db->insert_id);
        return $response;
    }

    public function getConfigEstpas():array
    {
        $response=array();
        $response['estado']=true;
        $this->idEstpas=$this->getStrIdEstpas();
        $data=$this->getConfigEstpasById($this->getStrIdEstpas());
        $data=json_decode($data,true);
        if(is_array($data)){
            if(isset($data[0])){
                $response['mensaje']=$data[0];
            }else{
                if(isset($data['paso']['wsjson'])){
                    try {
                        @$data['paso']['wsjson']=json_decode($data['paso']['wsjson'],true);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                $response['mensaje']=$data;
            }
            return $response;
        }
        showResponse("Error al conectar con el servicio de campañas");
    }

    public function addConfigEstpas():array
    {
        $response=array();
        $response['estado']=true;
        $this->idEstpas=$this->getStrIdEstpas();
        $data=$this->addConfigEstpasById($this->getArrData());
        $data=json_decode($data,true);
        if(is_array($data)){
            $response['mensaje']=$data;
            return $response;
        }
        showResponse("Error al conectar con el servicio de campañas");
    }

    public function addCamposWebForm():array
    {
        $response=array();
        $response['estado']=true;
        $data=$this->saveCamposWebform($this->getArrData(), $this->getStrEstratIdGuion());
        $info=$this->consumirFormData($data['url'],$data['postFields']);
        $data=json_decode($info,true);
        if(is_array($data)){
            $response['mensaje']=$data;
            return $response;
        }
        showResponse("Error al conectar con el servicio de campañas");
    }
}