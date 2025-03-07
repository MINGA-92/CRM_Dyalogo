<?php
require_once 'AgendaExtend.php';

class Agenda extends AgendaExtend{

    private $id;
    private $identificacion;
    private $idCita;
    private $tipo;
    private $ubicacion;
    private $recurso;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId():string
    {
        return $this->id;
    }

    public function setId(string $id):void
    {
        $this->id=parent::$db->real_escape_string($id);
    }
    
    public function getIdentificacion():int
    {
        return $this->identificacion;
    }

    public function setIdentificacion(int $identificacion):void
    {
        $this->identificacion=parent::$db->real_escape_string($identificacion);
    }

    public function getIdCita():int
    {
        return $this->idCita;
    }

    public function setIdCita(int $idCita):void
    {
        $this->idCita=parent::$db->real_escape_string($idCita);
    }

    public function getTipo():string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo):void
    {
        $this->tipo=parent::$db->real_escape_string($tipo);
    }

    public function getUbicacion():string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(string $ubi):void
    {
        $this->ubicacion=parent::$db->real_escape_string($ubi);
    }

    public function getRecurso():string
    {
        return $this->recurso;
    }

    public function setRecurso(string $recurso):void
    {
        $this->recurso=parent::$db->real_escape_string($recurso);
    }

    // VALIDAR SI EL USUARIO YA EXISTE EN LA BD
    public function validarCliente():array
    {
        $bdPersonas=$this->getAgendador($this->id);
        $this->validarPersona($bdPersonas);
        
        $response=[];
        $response['estado']=false;

        foreach($bdPersonas as $value => $key){
            $response[$value]=$bdPersonas[$value];
        }

        $response['bd']=$bdPersonas['AGENDADOR_ConsInte__GUION__Pob_b'];
        $response['id']=$bdPersonas['AGENDADOR_ConsInte__PREGUN_IdP_b'];

        $sql=parent::$db->query("SELECT G{$response['bd']}_ConsInte__b AS id FROM DYALOGOCRM_WEB.G{$response['bd']} WHERE G{$response['bd']}_C{$response['id']} = {$this->getIdentificacion()}");
        if($sql){
            $data=$sql->fetch_object();
            if($sql->num_rows == 1 && $data->id > 0){
                $response['estado']=true;
                $response['user']=$data->id;
                $response['mensaje']="Cliente existe en el sistema";
            }else{
                $response['mensaje']="Cliente no existe en el sistema";
            }
        }else{
            $this->error();
        }

        return $response;
    }

    // VALIDAR SI EL USUARIO CUMPLE CON EL ESTADO QUE ESTA CONFIGURADO PARA EL AGENDADOR
    public function validarEstado(array $array=null, array $condiciones=null):array
    {
        $bdPersonas=is_null($array) ? $this->validarCliente() : $array;
        
        $this->validarEstadoPersona($bdPersonas,$condiciones);
        
        $response=[];
        $response['estado']=false;
        
        if($bdPersonas['estado']){
            // VALIDAR SI EL USUARIO CUMPLE CON EL ESTADO CONFIGURADO PARA EL AGENDADOR
            $strCondiciones=$this->getWhereCondiciones($this->getId(),$bdPersonas,$condiciones);
            $sql=parent::$db->query("SELECT COUNT(1) AS valido FROM DYALOGOCRM_WEB.G{$bdPersonas['bd']} WHERE {$strCondiciones} G{$bdPersonas['bd']}_C{$bdPersonas['id']} = {$this->getIdentificacion()} ");
            if($sql){
                $sql=$sql->fetch_object();
                if($sql->valido == '1'){
                    $response['estado']=true;
                    $response['mensaje']="El cliente cumple con el estado requerido";
                }else{
                    $response['mensaje']="El cliente no cumple con el estado requerido";
                }
            }else{
                $this->error();
            }
        }else{
            $response['mensaje']="Cliente no existe en el sistema, no se valido el estado";
        }

        return $response;
    }

    // AGENDAR UNA CITA
    public function agendarCita(array $bdCitas, int $idCliente, array $cliente):array
    {

        $response=[];
        $response['estado']=false;

        $guion=$bdCitas['AGENDADOR_ConsInte__GUION__Dis_b'];

        // ECONTRAR EL CAMPO QUE ALMACENA EL ID DEL CLIENTE EN EL GUION DE DISPONIBILIDADES
        $camId=$bdCitas['AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b']-1;

        // CAMPOS QUE SIEMPRE DEBEN LLENARSE ESTADO, IDCLIENTE Y LA CEDULA
        $camEstado="G{$guion}_C{$bdCitas['AGENDADOR_ConsInte__PREGUN_Est_b']}='-403'";
        $camId="G{$guion}_C{$camId}={$idCliente}";
        $camCc="G{$guion}_C{$bdCitas['AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b']}={$this->getIdentificacion()}";

        // CAMPOS OPCIONALES
        $camNombre="G{$guion}_C{$bdCitas['AGENDADOR_ConsInte__PREGUN_AgendaNomP_b']}";
        $camTelefono="G{$guion}_C{$bdCitas['AGENDADOR_ConsInte__PREGUN_AgendaCelP_b']}";
        $camCorreo="G{$guion}_C{$bdCitas['AGENDADOR_ConsInte__PREGUN_AgendaMailP_b']}";

        // ARMAR LA CADENA DEL UPDATE PARA EL NOMBRE,TELEFONO Y CORREO
        $nombre=!isset($cliente['nombre']) || is_null($cliente['nombre']) ? $camNombre."=null" : $camNombre."='{$cliente['nombre']}'";
        $telefono=!isset($cliente['celular']) || is_null($cliente['celular']) ? $camTelefono."=null" : $camTelefono."='{$cliente['celular']}'";
        $correo=!isset($cliente['correo']) || is_null($cliente['correo']) ? $camCorreo."=null" : $camCorreo."='{$cliente['correo']}'";

        $sql=parent::$db->query("UPDATE DYALOGOCRM_WEB.G{$guion} SET {$camEstado}, {$camId}, {$camCc}, {$nombre}, {$telefono}, {$correo} WHERE G{$guion}_ConsInte__b ={$this->getIdCita()}");
        if($sql){
            if(parent::$db->affected_rows == 1){
                $response['estado']=true;
                $response['mensaje']="Cita agendada con exito";
            }else{
                $response['mensaje']="No se agendo la cita";
            }
        }else{
            $this->error();
        }

        return $response;
    }

    // CANCELAR UNA CITA
    public function cancelaCita():array
    {
        $response=[];
        $response['estado']=false;

        $data=$this->getAgendador($this->getId());

        // VALIDAR SI LA CITA EXISTE Y ESTA DISPONIBLE
        $cita=$this->getCita($this->getIdCita(), $data);
        $estadoCita="G{$data['AGENDADOR_ConsInte__GUION__Dis_b']}_C{$data['AGENDADOR_ConsInte__PREGUN_Est_b']}";

        if($cita[$estadoCita] == '-403'){
            $guion=$data['AGENDADOR_ConsInte__GUION__Dis_b'];
    
            // ECONTRAR EL CAMPO QUE ALMACENA EL ID DEL CLIENTE EN EL GUION DE DISPONIBILIDADES
            $camId=$data['AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b']-1;
            
            // CAMPOS QUE SIEMPRE DEBEN LLENARSE ESTADO, IDCLIENTE Y LA CEDULA
            $camEstado="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_Est_b']} = '-406'";
            $camId="G{$guion}_C{$camId} = NULL";
            $camCc="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b']} = NULL";

            // CAMPOS OPCIONALES
            $camNombre="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_AgendaNomP_b']} = NULL";
            $camTelefono="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_AgendaCelP_b']} = NULL";
            $camCorreo="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_AgendaMailP_b']} = NULL";

            $sql=parent::$db->query("UPDATE DYALOGOCRM_WEB.G{$guion} SET {$camEstado}, {$camId}, {$camCc}, {$camNombre}, {$camTelefono}, {$camCorreo} WHERE G{$guion}_ConsInte__b ={$this->getIdCita()}");
            if($sql){
                if(parent::$db->affected_rows == 1){
                    $response['estado']=true;
                    $response['mensaje']="Cita cancelada con exito";
                }else{
                    $response['mensaje']="No se cancelo la cita";
                }
            }else{
                $this->error();
            }
        }else{
            $response['mensaje']="No se puede cancelar una cita que no esta agendada";
        }
        return $response;
    }

    // LISTAR LOS TIPOS DE RECURSO
    public function listaTipoRecurso():array
    {
        $data=$this->getAgendador($this->getId());
        return $this->listaRecursos($data, "tipo");
    }

    // LISTAR POR UBICACION DE RECURSO
    public function listaUbiRecurso():array
    {
        $data=$this->getAgendador($this->getId());
        return $this->listaRecursos($data, "ubicacion");
    }

    // LISTAR LOS RECURSOS
    public function listaRecurso():array
    {
        $data=$this->getAgendador($this->getId());
        return $this->listaRecursos($data, "identificacion", $this->getTipo(), $this->getUbicacion());
    }

    // LISTAR LAS CITAS DISPONIBLES
    public function listaCita(array $condiciones=null):array
    {
        $data=$this->getAgendador($this->getId());
        if($data['AGENDADOR_ValidaEst_b'] == 3){
            $validado=$this->validarEstado(null,$condiciones);
            if($validado['estado']){
                return $this->listaRecursos($data, "cita", $this->getTipo(), $this->getUbicacion(), $this->getRecurso());
            }else{
                showResponse("los datos suministrados no coinciden con los autorizados para ofrecer citas");
            }
        }else{
            return $this->listaRecursos($data, "cita", $this->getTipo(), $this->getUbicacion(), $this->getRecurso());
        }
    }

    // LISTAR LAS CITAS AGENDADAS POR UN PACIENTE
    public function listaCitaPaciente():array
    {
        $data=$this->getAgendador($this->getId());
        return $this->listaRecursos($data, "citaPaciente", '', '', '', $this->getIdentificacion());
    }

    public function camposCondiciones():array
    {
        $response=[];
        $response['estado']=false;
        $response['mensaje']="No hay campos condiciones que se deban preguntar al cliente";
        $clave="p.fs@3!@M";

        $sqlCond=parent::$db->query("SELECT CONDAGENDA_ConsInte__PREGUN_b FROM DYALOGOCRM_SISTEMA.CONDAGENDA WHERE MD5(CONCAT('{$clave}',CONDAGENDA_ConsInte__AGENDADOR_b))='{$this->getId()}' AND CONDAGENDA_Tipo_b = 2");
        if($sqlCond && $sqlCond->num_rows > 0){
            $response['estado']=true;
            $response['mensaje']=array();
            while($row = $sqlCond->fetch_object()){
                array_push($response['mensaje'], $row->CONDAGENDA_ConsInte__PREGUN_b);
            }
        }

        return $response;
    }
}