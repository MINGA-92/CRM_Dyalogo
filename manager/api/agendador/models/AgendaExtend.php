<?php

class AgendaExtend{

    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }

    // RETORNA UN ARRAY CON TODOS LOS ID DE LOS CAMPOS DE UN RECURSO EN EL G DE DISPONIBILIDADES DE CADA AGENDADOR
    private function setCampos(array $data):array
    {
        $response=[];
        $guion=$data['AGENDADOR_ConsInte__GUION__Dis_b'];
        $response['guion']=$guion;
        
        // ENCONTRAR LOS CAMPOS DEL RECURSO COMO NOMBRE,CEDULA,ETC
        $response['camTipo']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_TipR_b']}";
        $response['camCc']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_IdR_b']}";
        $response['camNombre']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_NomR_b']}";
        $response['camUbica']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_UbiR_b']}";
        $response['camCel']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_CelR_b']}";
        $response['camMail']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_MailR_b']}";
        $response['camFecha']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_Fec_b']}";
        $response['camHora']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_Hor_b']}";
        $response['camNotas']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_Not_b']}";
        $response['camEstado']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_Est_b']}";
        $response['idCita']="G{$guion}_ConsInte__b";
        $response['camCP']="G{$guion}_C{$data['AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b']}";

        return $response;
    }

    // RETORNA UN ARRAY CON LOS DATOS SEGÚN EL FILTRO PARA LISTAR RECURSOS
    private function setArray(object $sql, string $group):array
    {
        //LLENAR EL ARRAY DE LA LISTA DE RECURSOS
        $lista=[];
        while($row = $sql->fetch_object()){
            if($group == 'identificacion'){
                $recurso=array(
                    "Tipo"=>$row->tipo,
                    "Identificacion"=>$row->identificacion,
                    "Nombre"=>$row->nombre,
                    "Ubicacion"=>$row->ubicacion,
                    "Celular"=>$row->celular,
                    "Correo"=>$row->correo,
                );
                array_push($lista, $recurso);
            }else if($group == 'cita' || $group == 'citaPaciente'){
                $recurso=array(
                    "Nombre"=>$row->nombre,
                    "Tipo"=>$row->tipo,
                    "Ubicacion"=>$row->ubicacion,
                    "Fecha"=>$row->fecha,
                    "Hora"=>$row->hora,
                    "Notas"=>$row->notas,
                    "IdCita"=>$row->idCita
                );
                array_push($lista, $recurso);
            }else{
                array_push($lista, $row->{$group});
            }
        }
        return $lista;
    }

    // RETORNA EL STRING DE LA CONDICION
    private function getWhere(array $data, string $wTipo, string $wUbi, string $wRecurso, string $group, int $cedula):string
    {
        $where="CONCAT(DATE({$data['camFecha']}),' ',TIME({$data['camHora']}))  >= DATE_ADD(NOW(), INTERVAL 4 HOUR) AND ({$data['camEstado']} = -401 OR {$data['camEstado']} = -406)";

        if($wTipo != ''){
            $where.=" AND {$data['camTipo']}='{$wTipo}'";
        }

        if($wUbi != ''){
            $where.=" AND {$data['camUbica']}='{$wUbi}'";
        }

        if($wRecurso != ''){
            $where.=" AND {$data['camNombre']}='{$wRecurso}'";
        }

        if($group == 'citaPaciente'){
            $where="CONCAT({$data['camFecha']},' ',{$data['camHora']})  >= DATE_ADD(NOW(), INTERVAL 4 HOUR) AND {$data['camEstado']} = -403 AND {$data['camCP']}={$cedula}";
        }

        return $where;
    }

    // RETORNA EL STRING DEL SELECT
    private function getSelect(array $data):string{

        $select="{$data['idCita']} AS idCita, DATE({$data['camFecha']}) AS fecha, TIME({$data['camHora']}) AS hora, {$data['camNotas']} AS notas, {$data['camTipo']} AS tipo, {$data['camCc']} AS identificacion, {$data['camNombre']} AS nombre, {$data['camUbica']} AS ubicacion, {$data['camCel']} AS celular, {$data['camMail']} AS correo";
        return $select;
    }

    private function getGroupByLimit(string $group, array $data, int $limit, int $diferent):string
    {
        (string) $strGroup='GROUP BY ';
        switch($group){
            case 'tipo':
                $strGroup.=$data['camTipo'];
                break;
            case 'ubicacion':
                $strGroup.=$data['camUbica'];
                break;
            case 'identificacion':
                $strGroup.=$data['camCc'];
                break;
            case 'cita':
                if($diferent == -1){
                    $strGroup.="{$data['camFecha']} ";
                }else{
                    $strGroup='';
                }
                $strGroup.="LIMIT {$limit}";
                break;
            default:
                $strGroup='';
                break;
        }

        return $strGroup;
    }

    // MOSTRAR ERROR CUANDO EL SISTEMA NO PUEDA COMPLETAR UNA SOLICITUD
    protected function error():void
    {
        showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
    }

    // VALIDA QUE EL AGENDADOR TENGA LLENO LOS CAMPOS NECESARIOS PARA COMPROBAR QUE UNA PERSONA EXISTA EN LA BD
    protected function validarPersona(array $data):array
    {
        if(is_null($data['AGENDADOR_ConsInte__GUION__Pob_b']) || is_null($data['AGENDADOR_ConsInte__PREGUN_IdP_b'])){
            $this->error();
        }

        return $data;
    }
    
    // VALIDA QUE EL AGENDADOR TENGA LLENO LOS CAMPOS NECESARIOS PARA COMPROBAR QUE UNA PERSONA TENGA CIERTO ESTADO EN LA BD
    protected function validarEstadoPersona(array $data, array $condiciones=null):array
    {
        if(is_null($data['bd']) || is_null($data['id']) || is_null($data['AGENDADOR_ValidaEst_b'])){
            $this->error();
        }else{
            if($data['AGENDADOR_ValidaEst_b'] == 3){
                $clave="p.fs@3!@M";
                $sqlCond=self::$db->query("SELECT CONDAGENDA_ConsInte__PREGUN_b,CONDAGENDA_Tipo_b,CONDAGENDA_DATOVALIDA_b FROM DYALOGOCRM_SISTEMA.CONDAGENDA WHERE MD5(CONCAT('{$clave}',CONDAGENDA_ConsInte__AGENDADOR_b))='{$data['id']}' AND CONDAGENDA_Tipo_b = 2");
                if($sqlCond && $sqlCond->num_rows > 0){
                    if(!is_array($condiciones)){
                        showResponse("Parametros incompletos o invalidos");
                    }
                }
            }else{
                showResponse("No se ha configurado la validación por estado",true,200,"200 OK");
            }


        }

        return $data;
    }

    //DEVOLVER LOS DATOS DEL AGENDADOR
    protected function getAgendador(string $id):array
    {
        $response=[];
        $clave="p.fs@3!@M";
        $sql = self::$db->query("SELECT * FROM DYALOGOCRM_SISTEMA.AGENDADOR WHERE MD5(CONCAT('{$clave}',AGENDADOR_ConsInte__b))='{$id}'");
        if($sql){
            if($sql->num_rows == 1){
                $response=$sql->fetch_array();
            }else{
                showResponse("Sin datos de agendador");
            }
        }else{
            $this->error();
        }

        return $response;
    }

    // RETORNA LA LISTA DE RECURSOS AGRUPADOS SEGÚN EL CAMPO ENVIADO
    protected function listaRecursos(array $data, string $group, string $wTipo='', string $wUbi='', string $wRecurso='', int $identificacion=0):array
    {
        (array) $response=[];
        $response['estado']=false;

        //SABER SI TOCA OFERTAR CITAS DE FECHAS DIFERENTES Y EL LIMITE DE CITAS A OFERTAR
        $oferDif=(int) $data['AGENDADOR_OferHoy_b'];
        $limit=(int) $data['AGENDADOR_CantCitas__b'];
        
        // OBTENER LOS ID DE LOS CAMPOS DONDE ESTAN LOS DATOS DE LOS RECURSOS
        $data=$this->setCampos($data);

        //ARMAR EL STRING DE LA CONDICIÓN
        $where=$this->getWhere($data,$wTipo,$wUbi,$wRecurso,$group,$identificacion);

        //ARMAR EL STRING DEL SELECT
        $select=$this->getSelect($data);

        // IDENTIFICAR CUAL ES EL CAMPO DE AGRUPACIÓN PARA LA CONSULTA
        $groupBy=$this->getGroupByLimit($group,$data,$limit,$oferDif);

        // CONSULTAR LOS RECURSOS AGRUPADOS SEGÚN EL PARAMETRO $GROUP
        $sql=self::$db->query("SELECT {$select} FROM DYALOGOCRM_WEB.G{$data['guion']} WHERE {$where} {$groupBy}");
        
        if($sql){
            if($sql->num_rows > 0){
                $response['estado']=true;
                $response['mensaje']=$this->setArray($sql, $group);
            }else{
                $response['mensaje']="No se encontraron recursos";
            }
        }else{
            $this->error();
        }
        return $response;
    }

    // RETORNA EL STRING DEL WHERE CON LAS CONDICIONES PARA VALIDAR SI UN REGISTRO CUMPLE CON LAS CONDICIONES CONFIGURADAS
    protected function getWhereCondiciones(string $id, array $agenda, array $condiciones=null):string
    {
        $response='';
        $clave="p.fs@3!@M";
        //CONSULTAR LAS CONDICIONES DEL AGENDADOR
        $sqlCond=self::$db->query("SELECT CONDAGENDA_ConsInte__PREGUN_b,CONDAGENDA_Tipo_b,CONDAGENDA_DATOVALIDA_b FROM DYALOGOCRM_SISTEMA.CONDAGENDA WHERE MD5(CONCAT('{$clave}',CONDAGENDA_ConsInte__AGENDADOR_b))='{$id}'");
        if($sqlCond && $sqlCond->num_rows > 0){
            while($row = $sqlCond->fetch_object()){
                if($row->CONDAGENDA_Tipo_b == 1){
                    $response.="G{$agenda['bd']}_C{$row->CONDAGENDA_ConsInte__PREGUN_b}='{$row->CONDAGENDA_DATOVALIDA_b}' AND ";
                }else{
                    $valido=false;
                    $valor=0;
                    foreach($condiciones as $key){
                        if($row->CONDAGENDA_ConsInte__PREGUN_b == $key['id']){
                            $valido=true;
                            $valor=$key['valor'];
                        }
                    }
                    if($valido){
                        $response.="G{$agenda['bd']}_C{$row->CONDAGENDA_ConsInte__PREGUN_b}='{$valor}' AND ";
                    }else{
                        showResponse("Parametros incompletos o invalidodds");
                    }
                }
            }
            return $response;
        }else{
            $this->error();
        }

        return $response;
    }

    // VALIDAR QUE EL AGENDADOR TENGA LLENOS LOS CAMPOS NECESARIOS PARA PODER VALIDAR UNA CITA
    public function validarCita(array $data):array
    {
        if(is_null($data['bd']) || is_null($data['id']) || is_null($data['AGENDADOR_ConsInte__GUION__Dis_b']) || is_null($data['AGENDADOR_ConsInte__PREGUN_Est_b']) ){
            $this->error();
        }

        return $data;
    }

    // INSERTAR UN REGISTRO EN LA BASE DE DATOS DE LAS PERSONAS CONFIGURADA PARA EL AGENDADOR
    public function insertBdP(int $bd, int $campoId, int $id):int
    {
        $sql=self::$db->query("INSERT INTO DYALOGOCRM_WEB.G{$bd} (G{$bd}_FechaInsercion,G{$bd}_C{$campoId}) VALUES (NOW(),'{$id}')");

        if(!$sql){
            $this->error();
        }

        return self::$db->insert_id;
    }

    // DEVOLVER LOS DATOS DE ID EN LA BD,IDENTIFICACIÓN, NOMBRE, CELULAR Y CORREO DE UN CLIENTE
    public function getCliente(int $bd, int $id, $nombre=0, $celular=0, $correo=0):array
    {
        $strSelect='';
        $coma=false;

        if($nombre !=null && $nombre > 0){
            $strSelect.="G{$bd}_C{$nombre} AS nombre";
            $coma=true;
        }

        if($celular !=null && $celular > 0){
            if($coma){
                $strSelect.=",";
            }
            $strSelect.="G{$bd}_C{$celular} AS celular";
            $coma=true;
        }

        if($correo !=null && $correo > 0){
            if($coma){
                $strSelect.=",";
            }
            $strSelect.="G{$bd}_C{$correo} AS correo";
        }

        $sql=self::$db->query("SELECT $strSelect FROM DYALOGOCRM_WEB.G{$bd} WHERE G{$bd}_ConsInte__b={$id}");
        if($sql){
            if($sql->num_rows == 1){
                $sql=$sql->fetch_array();
            }else{
                showResponse("Cliente no existe en el sistema");
            }
        }else{
            $this->error();
        }

        return $sql;
    }

    // RETORNA DATOS DE UNA CITA
    public function getCita(int $id, array $agenda):array
    {
        $sql=self::$db->query("SELECT * FROM DYALOGOCRM_WEB.G{$agenda['AGENDADOR_ConsInte__GUION__Dis_b']} WHERE G{$agenda['AGENDADOR_ConsInte__GUION__Dis_b']}_ConsInte__b = {$id}");
        if($sql){
            if($sql->num_rows == 1){
                $sql=$sql->fetch_array();
            }else{
                showResponse("La cita no existe en el sistema");
            }
        }else{
            $this->error();
        }

        return $sql;
    }
}