<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once(__DIR__."../../../../pages/conexion.php");
require_once(__DIR__ . "../../../../global/WSCoreClient.php");
class Agendador{
    private $bd;
    private $bdSistema;
    
    public function __construct(){
        global $mysqli;
        global $BaseDatos_systema;
        $this->bd=$mysqli;
        $this->bdSistema=$BaseDatos_systema;
    }

    // FUNCIÓN QUE SE ENCARGA DE CREAR EL FORMULARIO DE LA BD, DE CADA AGENDADOR
    private function insertGuion(int $id, string $nombre):array
    {
        $response=array();
        $response['estado']=false;

        // PRIMERO HAY QUE INSERTAR UN REGISTRO EN LA TABLA GUION_ LLAMANDO A LA FUNCION INSERTBD()
        $resBD=$this->insertBD($nombre);
        if($resBD['estado']){
            $response['guion']="ok";

            // SI SE CREO EL REGISTRO EN GUION_, PROCEDER A CREAR LAS SECCIONES LLAMANDO A LA FUNCIÓN INSERTSECCIO()
            $seccio=$this->insertSeccio($resBD['idBd']);
            if(count($seccio['exito']) > 0){
                $response['secciones']="ok";

                //VALIDAR QUE NO SE HAYAN GENERADO ERRORES AL GUARDAR LA SECCION
                if(count($seccio['error']) > 0){
                    $response['alertas-seccion']=$seccio['error'];
                }

                // UNA VEZ SE CREAN LAS SECCIONES, INSERTAR LOS CAMPOS EN PREGUN LLAMANDO A LA FUNCIÓN INSERTPREGUN()
                $pregun=$this->insertPregun($resBD['idBd'],$seccio['exito']);
                if(count($pregun['exito']) > 0){
                    $response['pregun']="ok";

                    //VALIDAR QUE NO SE HAYAN GENERADO ERRORES AL GUARDAR CAMPOS EN PREGUN
                    if(count($pregun['error']) > 0){
                        $response['alertas-pregun']=$pregun['error'];
                    }
                     //ACTUALIZAR EL AGENDADOR CON EL ID DE LA TABLA DISPONIBILIDADES CREADA y CON LOS CAMPOS CREADOS EN PREGUN POR MEDIO DE LA FUNCIÓN UPAGENDADOR()
                    $response['actualizacion']=$this->upAgendador($id, $resBD['idBd'], $pregun['exito']) ? 'ok' : 'error al actualizar el agendador';
                    
                    // ACTUALIZAR LA TABLA GUION_ CON EL CAMPO PRINCIPAL Y SECUNDARIO -- PENDIENTE
                    $response['actualizacionGuion']=$this->upGuion($resBD['idBd'], $pregun['exito']['AGENDADOR_ConsInte__PREGUN_NomR_b'], $pregun['exito']['AGENDADOR_ConsInte__PREGUN_Fec_b']) ? 'ok' : 'error al actualizar el guion';

                    // YA CON LOS CAMPOS EN PREGUN Y CON LAS TABLAS ACTUALIZADAS, TOCA GENERAR LA TABLA Y LA VISTA DEL FORMULARIO DINAMICO
                    $tabla=$this->generarTabla($resBD['idBd']);
                    if($tabla){
                        $response['estado']=true;
                        $response['tabla']='ok';
                    }else{
                        $response['tabla']='Fallo al generar la tabla y la vista';
                    }
                }else{
                    $response['pregun']="Fallo al insertar los campos";
                }
            }else{
                $response['secciones']= 'Fallo al crear las secciones';
            }
        }else{
            $response['guion']= 'Fallo al crear el registro en las bases de datos';
        }

        return $response;
    }

    // ACTUALIZAR LA TABLA GUION_ CON LOS CAMPOS PRINCIPAL Y SECUNDARIO
    private function upGuion(int $id, int $primario, int $secundario):bool
    {
        $sql=$this->bd->query("UPDATE {$this->bdSistema}.GUION_ SET GUION__ConsInte__PREGUN_Pri_b={$primario}, GUION__ConsInte__PREGUN_Sec_b={$secundario} WHERE GUION__ConsInte__b={$id}");
        return $sql ? true : false;
    }

    // ACTUALIZAR EL AGENDADOR CON LOS CAMPOS CREADOS EN PREGUN Y CON EL ID DE LA TABLA DISPONIBILIDADES
    private function upAgendador(int $id, int $bdCreada, array $pregun):bool
    {
        $set='';
        foreach($pregun as $campo=>$valor){
            $set.=",{$campo}=$valor";
        }

        $sql=$this->bd->query("UPDATE {$this->bdSistema}.AGENDADOR SET AGENDADOR_ConsInte__GUION__Dis_b={$bdCreada} {$set} WHERE AGENDADOR_ConsInte__b={$id}");

        return $sql ? true : false;
    }

    // INSERTAR UN REGISTRO EN LA TBLA GUION_
    private function insertBD(string $nombre):array
    {
        $sql=$this->bd->query("INSERT INTO {$this->bdSistema}.GUION_ (GUION__Nombre____b,GUION__Descripci_b,GUION__ConsInte__PROYEC_b,GUION__Tipo______b,GUION_ByModulo_b) VALUES('Agendas - {$nombre}','BD creada automaticamente para el agendador {$nombre}',{$_SESSION['HUESPED']},2,1)");
        $response=array();
        $response['estado']=false;
        if($sql){
            $response['estado']=true;
            $response['idBd']=$this->bd->insert_id;
        }

        return $response;
    }

    // INSERTAR LAS SECCIONES DEL AGENDADOR
    private function insertSeccio(int $id):array
    {
        $secciones=array("AGENDA","RECURSO","PERSONA QUE SOLICITA LA AGENDA");
        $response=array();
        $error=array();

        foreach($secciones as $seccion){
            // CREAR UNA SECCION POR CADA ELEMENTO DEL ARRAY DE SECCIONES
            $sql=$this->bd->query("INSERT INTO {$this->bdSistema}.SECCIO (SECCIO_Nombre____b,SECCIO_ConsInte__GUION__b,SECCIO_NumColumnas_b,SECCIO_VistPest__b) VALUES ('$seccion',{$id},2,4)");
            if($sql){
                $response[$seccion]=$this->bd->insert_id;
            }else{
                array_push($error, "{$seccion} : {$this->bd->error}");
            }

        }

        return array('error'=>$error,'exito'=>$response);
    }

    // INSERTAR CAMPOS EN PREGUN PARA ARMAR LA TABLA DE DISPONIBILIDADES
    private function insertPregun(int $bd, array $seccion):array
    {
        $insertados=array();
        $error=array();

        $sql=$this->bd->query("SELECT * FROM {$this->bdSistema}.CAMPOSAGENDADOR");
        if($sql && $sql->num_rows > 0){
            $i=0;
            while($row = $sql->fetch_object()){
                $rowSeccion=$seccion[$row->seccion];
                $sqlPregun=$this->bd->query("INSERT INTO {$this->bdSistema}.PREGUN (PREGUN_ConsInte__GUION__b,PREGUN_Texto_____b,PREGUN_Tipo______b,PREGUN_ConsInte__SECCIO_b,PREGUN_OrdePreg__b,PREGUN_ConsInte__OPCION_B,PREGUN_Default___b,PREGUN_ContAcce__b,PREGUN_MostrarSubForm) VALUES({$bd},'{$row->nombre}',{$row->tipo},{$rowSeccion},{$i},{$row->opcion},{$row->defecto},{$row->lectura},{$row->subform})");
                if($sqlPregun){
                    $i++;
                    if(!is_null($row->nombreAgendador)){
                        $insertados[$row->nombreAgendador]=$this->bd->insert_id;
                    }
                }else{
                    array_push($error, "{$row->nombre} : {$this->bd->error}");
                }
            }
        }

        return array('exito'=>$insertados,'error'=>$error);
    }

    // LLAMAR AL GENERADOR DE TABLAS
    private function generarTabla(int $idBd):bool
    {
        include_once(__DIR__."../../../../generador/generar_tablas_bd.php");
        return generar_tablas_bd($idBd,1,1,0,0,2);
    }

    // VALIDAR SI YA EXISTE LA TABLA DINAMICA DEL AGENDADOR
    private function validaTabla(string $id):bool
    {
        $sql=$this->bd->query("SELECT AGENDADOR_ConsInte__b,AGENDADOR_Nombre____b,AGENDADOR_ConsInte__GUION__Dis_b FROM {$this->bdSistema}.AGENDADOR WHERE md5(concat('".clave_get."', AGENDADOR_ConsInte__b)) = '{$id}'");
        if($sql){
            $sql=$sql->fetch_object();
            if(!is_null($sql->AGENDADOR_ConsInte__GUION__Dis_b)){
                return true;
            }else{
                $this->insertGuion($sql->AGENDADOR_ConsInte__b,$sql->AGENDADOR_Nombre____b);
            }
        }else{
            return false;
        }
    }

    // GUARDAR LA LISTA DE CONDICIONES PARA VALIDAR POR EL ESTADO
    private function saveCondiciones(array $post, string $id):bool
    {
        if(isset($post['condicion'])){
            $sql=$this->bd->query("DELETE FROM {$this->bdSistema}.CONDAGENDA WHERE md5(concat('".clave_get."', CONDAGENDA_ConsInte__AGENDADOR_b)) = '{$id}'");
            if($sql){
                $sqlId=$this->bd->query("SELECT AGENDADOR_ConsInte__b FROM {$this->bdSistema}.AGENDADOR WHERE md5(concat('".clave_get."', AGENDADOR_ConsInte__b)) = '{$id}'");
                if($sqlId && $sqlId->num_rows > 0){
                    $sqlId=$sqlId->fetch_object();
                    $intId=$sqlId->AGENDADOR_ConsInte__b;
                    foreach($post['condicion'] as $key){
                        $campo=$post['campo_'.$key];
                        $tipo=$post['tipo_'.$key];
                        $dato=isset($post['dato_'.$key]) ? "'".$post['dato_'.$key]."'" : "NULL";
                        $sqlIn=$this->bd->query("INSERT INTO {$this->bdSistema}.CONDAGENDA VALUES(NULL,{$intId},{$campo},{$tipo},{$dato})");
                        if(!$sqlIn){
                            return false;
                        }
                    }
                    return true;
                }
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    private function validaEstpas(int $id):array
    {
        $sql=$this->bd->query("SELECT ESTPAS_ConsInte__b FROM {$this->bdSistema}.ESTPAS WHERE ESTPAS_ConsInte__MUESTR_b={$id} AND ESTPAS_Tipo______b=4");
        $valido=array();
        $valido[0]=0;
        $valido[1]='add';
        if($sql){
            if($sql->num_rows == 1){
                $sql=$sql->fetch_object();
                $valido[0]=$sql->ESTPAS_ConsInte__b;
                $valido[1]='edit';
            }
        }
        return $valido;
    }

    // CREA UN REGISTRO EN ESTPAS PARA EL WEBFORM
    private function insertEstpas(array $post, string $id):array
    {
        $nombre = isset($post['wfNombre']) ? $post['wfNombre'] : false;
        $estpas[0]=false;
        if($nombre){
            $sqlId=$this->bd->query("SELECT AGENDADOR_ConsInte__b,AGENDADOR_ConsInte__GUION__Dis_b,AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b FROM {$this->bdSistema}.AGENDADOR WHERE md5(concat('".clave_get."', AGENDADOR_ConsInte__b)) = '{$id}'");
            if($sqlId && $sqlId->num_rows > 0){
                $sqlId=$sqlId->fetch_object();
                $intId=$sqlId->AGENDADOR_ConsInte__b;
                $estpas=$this->validaEstpas($intId);
                if($estpas[0] == 0 ){
                    $sql=$this->bd->query("INSERT INTO {$this->bdSistema}.ESTPAS (ESTPAS_Nombre__b,ESTPAS_Tipo______b,ESTPAS_Comentari_b,ESTPAS_activo,ESTPAS_Generado_Por_Sistema_b,ESTPAS_ConsInte__MUESTR_b) VALUES ('Formul',4,'{$nombre}',-1,-1,{$intId})");
                    if($sql){
                        $estpas[0]= $this->bd->insert_id;
                        $sqlUpG=$this->bd->query("UPDATE {$this->bdSistema}.PREGUN SET PREGUN_WebForm_b=-1 WHERE PREGUN_ConsInte__b= {$sqlId->AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b} ");
                    }
                }

            }
        }
        return $estpas;
    }

    // OBTENER LAS BASES DE DATOS PARA ASOCIAR CON EL AGENDADOR
    public static function getBd():string
    {
        global $mysqli;
        $bases=$mysqli->query("SELECT `GUION__ConsInte__b`,`GUION__Nombre____b` FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__PROYEC_b={$_SESSION['HUESPED']} AND GUION__Tipo______b=2 AND GUION_ByModulo_b=0");
        $option='';
        if($bases && $bases->num_rows > 0){
            while($row = $bases->fetch_object()){
                $option.= "<option value='{$row->GUION__ConsInte__b}'>{$row->GUION__Nombre____b}</option>";
            }
        }
        return $option;
    }

    // OBTENER LAS ESTRATEGIAS DEL HUESPED
    public function getEstrat():string
    {
        global $mysqli;
        $estrat=$mysqli->query("SELECT `ESTRAT_ConsInte__b`,`ESTRAT_Nombre____b` FROM DYALOGOCRM_SISTEMA.ESTRAT WHERE ESTRAT_ConsInte__PROYEC_b={$_SESSION['HUESPED']}");
        $option='';
        if($estrat && $estrat->num_rows > 0){
            while($row = $estrat->fetch_object()){
                $option.= "<option value='{$row->ESTRAT_ConsInte__b}'> {$row->ESTRAT_Nombre____b} </option>";
            }
        }
        return $option;
    }

    // LISTAR LOS AGENDADROES ASOCIADOS AL HUESPED
    public function llenarListaNavegacion():string
    {
        
        $Zsql = $this->bd->query("SELECT AGENDADOR_ConsInte__b as id,  AGENDADOR_Nombre____b as camp1 FROM {$this->bdSistema}.AGENDADOR LEFT JOIN {$this->bdSistema}.GUION_ ON AGENDADOR_ConsInte__GUION__Pob_b=GUION__ConsInte__b WHERE  GUION__ConsInte__PROYEC_b= {$_SESSION['HUESPED']} ORDER BY AGENDADOR_Nombre____b ASC LIMIT 0, 50");    
        if($Zsql){
            $estado='ok';
            $mensaje="";
            if($Zsql -> num_rows > 0){
                while($obj = $Zsql->fetch_object()){
                    $id=url::urlSegura($obj->id);
                    $mensaje .="<tr class='CargarDatos' id='{$id}' onclick=\"getAgendador('{$id}')\">
                                <td>
                                    <p style='font-size:14px;'><b>".strtoupper(($obj->camp1))."</b></p>
                                    <p style='font-size:12px; margin-top:-10px;'>Agendador</p>
                                </td>
                            </tr>";
                }
            }
        }else{
            $estado= "error";
            $mensaje="No se pudo obtener la lista de agendadores";
        }
    
        return json_encode(array('estado'=>$estado,'mensaje'=>$mensaje));
    }

    // CREAR O ACTUALIZAR UN AGENDADOR
    public function saveAgendador(array $post):array
    {
        $nombre = isset($post['AGENDADOR_Nombre____b']) ? $post['AGENDADOR_Nombre____b'] : false;
        $filR   = isset($post['AGENDADOR_FilRec_b']) ? -1 : 0;
        $filTr = isset($post['AGENDADOR_FilTip_b']) ? -1 : 0;
        $filUr = isset($post['AGENDADOR_FilUbi_b']) ? -1 : 0;
        $baseP = isset($post['AGENDADOR_ConsInte__GUION__Pob_b']) ? $post['AGENDADOR_ConsInte__GUION__Pob_b'] : false;
        // $validaP = isset($post['AGENDADOR_ValidaPer_b']) ? -1 : 0;
        $idenP = isset($post['AGENDADOR_ConsInte__PREGUN_IdP_b']) ? $post['AGENDADOR_ConsInte__PREGUN_IdP_b'] : false;
        $nombreP = isset($post['AGENDADOR_ConsInte__PREGUN_NomP_b']) && $post['AGENDADOR_ConsInte__PREGUN_NomP_b'] > 0 ? $post['AGENDADOR_ConsInte__PREGUN_NomP_b'] : 'null';
        $celP = isset($post['AGENDADOR_ConsInte__PREGUN_CelP_b']) && $post['AGENDADOR_ConsInte__PREGUN_CelP_b'] > 0 ? $post['AGENDADOR_ConsInte__PREGUN_CelP_b'] : 'null';
        $mailP = isset($post['AGENDADOR_ConsInte__PREGUN_MailP_b']) && $post['AGENDADOR_ConsInte__PREGUN_MailP_b'] > 0 ? $post['AGENDADOR_ConsInte__PREGUN_MailP_b'] : 'null';
        $valEstP = isset($post['validacion']) ? $post['validacion'] : 0;
        $estP = isset($post['AGENDADOR_ConsInte__PREGUN_EstP_b']) && $post['AGENDADOR_ConsInte__PREGUN_EstP_b'] > 0 ? $post['AGENDADOR_ConsInte__PREGUN_EstP_b'] : "null";
        $estReqP = isset($post['AGENDADOR_EstadoReq____b']) && $post['AGENDADOR_EstadoReq____b'] !='' ? "'{$post['AGENDADOR_EstadoReq____b']}'" : 'null';
        $cantCitas = isset($post['AGENDADOR_CantCitas__b']) && is_numeric($post['AGENDADOR_CantCitas__b']) ? $post['AGENDADOR_CantCitas__b'] : 3;
        $oferHoy = isset($post['AGENDADOR_OferHoy_b']) ? -1 : 0;
        $oper= isset($post['oper']) && $post['oper'] !=''? $post['oper'] :false;
        $id= isset($post['id']) && $post['id'] !=''? $post['id'] :false;
        $webform= isset($post['AGENDADOR_Webform_b']) ? -1 : 0;
        $idEstrat= isset($post['AGENDADOR_Estrat_b']) ? $post['AGENDADOR_Estrat_b'] : 0;
        $idEstpas= isset($post['AGENDADOR_Estpas_b']) ? $post['AGENDADOR_Estpas_b'] : 0;
        $idWebForm= isset($post['wfId']) ? $post['wfId'] : 0;

        $validaP=0;
        if($valEstP >= '2'){
            $validaP= -1;
        }

        if($nombre && $idenP && $oper){
            if($oper == 'add'){
                if($baseP){
                    // INSERTAMOS EL REGISTRO EN LA BASE DE DATOS
                    $sql=$this->bd->query("INSERT INTO {$this->bdSistema}.AGENDADOR (AGENDADOR_Nombre____b,AGENDADOR_FilRec_b,AGENDADOR_FilTip_b,AGENDADOR_FilUbi_b,AGENDADOR_ConsInte__GUION__Pob_b,AGENDADOR_ValidaPer_b,AGENDADOR_ConsInte__PREGUN_IdP_b,AGENDADOR_ConsInte__PREGUN_NomP_b,AGENDADOR_ConsInte__PREGUN_CelP_b,AGENDADOR_ConsInte__PREGUN_MailP_b,AGENDADOR_ValidaEst_b,AGENDADOR_ConsInte__PREGUN_EstP_b,AGENDADOR_EstadoReq____b,AGENDADOR_CantCitas__b,AGENDADOR_OferHoy_b,AGENDADOR_Webform_b,AGENDADOR_Estrat_b,AGENDADOR_Estpas_b) VALUES('{$nombre}', {$filR}, {$filTr}, {$filUr},{$baseP},{$validaP},{$idenP},{$nombreP},{$celP},{$mailP},{$valEstP},{$estP},{$estReqP},{$cantCitas},{$oferHoy},{$webform},{$idEstrat},{$idEstpas})");
                    if($sql){
                        $idCryp=url::urlSegura($this->bd->insert_id);
                        $proceso=$this->insertGuion($this->bd->insert_id,$nombre);
                        $cond=$this->saveCondiciones($post,$idCryp);
                        $estpas=null;
                        if($webform == -1){
                            $estpas=$this->insertEstpas($post,$idCryp);
                        }
                        // CREAR LA BD DEL AGENDADOR
                        $response = array('estado'=>'ok', 'mensaje'=>'Registro creado con exito','data'=>array('id'=>$idCryp,'tabla'=>$proceso,'cond'=>$cond,'estpas'=>$estpas));
                    }else{
                        $response = array('estado'=>'error', 'mensaje'=>$this->bd->error);
                    }
                }else{
                    $response = array('estado'=>'error', 'mensaje'=>'Datos incompletos');
                }
            }else{
                if($id){
                    //ACTUALIZAMOS EL AGENDADOR
                    $sql="UPDATE {$this->bdSistema}.AGENDADOR  SET AGENDADOR_Nombre____b='{$nombre}', AGENDADOR_FilRec_b={$filR}, AGENDADOR_FilTip_b={$filTr}, AGENDADOR_FilUbi_b={$filUr}, AGENDADOR_ValidaPer_b={$validaP}, AGENDADOR_ConsInte__PREGUN_IdP_b={$idenP}, AGENDADOR_ConsInte__PREGUN_NomP_b={$nombreP}, AGENDADOR_ConsInte__PREGUN_CelP_b={$celP}, AGENDADOR_ConsInte__PREGUN_MailP_b={$mailP}, AGENDADOR_ValidaEst_b={$valEstP}, AGENDADOR_ConsInte__PREGUN_EstP_b={$estP}, AGENDADOR_EstadoReq____b={$estReqP}, AGENDADOR_CantCitas__b={$cantCitas}, AGENDADOR_OferHoy_b={$oferHoy}, AGENDADOR_Webform_b={$webform},  AGENDADOR_Estrat_b={$idEstrat},  AGENDADOR_Estpas_b={$idEstpas} WHERE md5(concat('".clave_get."', AGENDADOR_ConsInte__b)) = '{$id}'";
                    if($this->bd->query($sql)){
                        $tabla=$this->validaTabla($id);
                        $cond=$this->saveCondiciones($post,$id);
                        $estpas=null;
                        if($webform == -1){
                            $estpas=$this->insertEstpas($post,$id);
                        }
                        $response = array('estado'=>'ok', 'mensaje'=>'Registro actualizado con exito', 'data'=>array('id'=>$id,'tabla'=>$tabla,'cond'=>$cond,'estpas'=>$estpas));
                    }else{
                        $response = array('estado'=>'error', 'mensaje'=>$this->bd->error);
                    }
                }else{
                    $response = array('estado'=>'error', 'mensaje'=>'No se envio el identificador del agendador');
                }
            }
        }else{
            $response = array('estado'=>'error', 'mensaje'=>'Datos incompletos');
        }

        return $response;
    }

    // OBTENER LOS CAMPOS TIPO TEXTO,NUMERICO Y MAIL DE UNA BASE DE DATOS
    public function getCamposBD(int $id):array
    {
        $sqlCampos=$this->bd->query("SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b,PREGUN_Tipo______b,PREGUN_ConsInte__OPCION_B FROM {$this->bdSistema}.PREGUN JOIN {$this->bdSistema}.SECCIO ON SECCIO_ConsInte__b=PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b={$id} AND SECCIO_TipoSecc__b=1 AND (PREGUN_Tipo______b=1 OR PREGUN_Tipo______b=3 OR PREGUN_Tipo______b=14 OR PREGUN_Tipo______b=6)");
        $campos = '';
        $estado='error';
        if($sqlCampos && $sqlCampos->num_rows > 0){
            $estado='ok';
            $campos=new stdClass();
            $campos->texto=array();
            $campos->mail=array();
            $campos->numerico=array();
            $campos->lista=array();
            while($row = $sqlCampos->fetch_object()){
                $intTipo=$row->PREGUN_Tipo______b;
                if( $intTipo == 1 || $intTipo == 3 || $intTipo == 4 || $intTipo == 14){ // texto,numerico,decimal,mail
                    array_push($campos->texto, array('texto'=>$row->PREGUN_Texto_____b,'id'=>$row->PREGUN_ConsInte__b));
                }

                if($row->PREGUN_Tipo______b == 6){ // lista
                    array_push($campos->lista, array('texto'=>$row->PREGUN_Texto_____b,'id'=>$row->PREGUN_ConsInte__b, 'opcion'=>$row->PREGUN_ConsInte__OPCION_B));
                }
            }
        }

        return array('estado'=>$estado, 'mensaje'=>$campos);
    }

    // OBTENER LOS DATOS DE UN AGENDADOR
    public function getAgendador(string $id):array
    {
        $sql="SELECT AGENDADOR_Nombre____b,AGENDADOR_FilRec_b,AGENDADOR_FilTip_b,AGENDADOR_FilUbi_b,AGENDADOR_ConsInte__GUION__Pob_b,AGENDADOR_ValidaPer_b,AGENDADOR_ConsInte__PREGUN_IdP_b,AGENDADOR_ConsInte__PREGUN_NomP_b,AGENDADOR_ConsInte__PREGUN_CelP_b,AGENDADOR_ConsInte__PREGUN_MailP_b,AGENDADOR_ValidaEst_b,AGENDADOR_ConsInte__PREGUN_EstP_b,AGENDADOR_EstadoReq____b, GUION__Nombre____b AS AGENDADOR_ConsInte__GUION__Dis_b,GUION__ConsInte__b, AGENDADOR_CantCitas__b,AGENDADOR_OferHoy_b,AGENDADOR_Webform_b,AGENDADOR_Estrat_b,AGENDADOR_Estpas_b FROM {$this->bdSistema}.AGENDADOR LEFT JOIN {$this->bdSistema}.GUION_ ON AGENDADOR_ConsInte__GUION__Dis_b=GUION__ConsInte__b WHERE md5(concat('".clave_get."', AGENDADOR_ConsInte__b)) = '{$id}'";
        $sql=$this->bd->query($sql);

        $mensaje='No se encontro información';
        $estado='error';
        if($sql && $sql->num_rows == 1 ){
            $estado='ok';
            $mensaje=$sql->fetch_object();
        }

        return array('estado'=>$estado,'mensaje'=>$mensaje);
    }

    // OBTENER LAS CONDICIONES DE UN AGENDADOR
    public function getCondiciones(string $id):array
    {
        $sql=$this->bd->query("SELECT CONDAGENDA_ConsInte__PREGUN_b,CONDAGENDA_Tipo_b,CONDAGENDA_DATOVALIDA_b FROM {$this->bdSistema}.CONDAGENDA WHERE md5(concat('".clave_get."', CONDAGENDA_ConsInte__AGENDADOR_b)) = '{$id}'");
        $condiciones = array();
        $estado='error';
        if($sql && $sql->num_rows > 0){
            $estado='ok';
            while($row = $sql->fetch_object()){
                array_push($condiciones, array("campo"=>$row->CONDAGENDA_ConsInte__PREGUN_b,"tipo"=>$row->CONDAGENDA_Tipo_b,"dato"=>$row->CONDAGENDA_DATOVALIDA_b));
            }
        }
        return array('estado'=>$estado, 'mensaje'=>$condiciones);
    }

    // OBTENER LA LISTA DE OPCIONES DE UNA LISTA
    public function getOpciones(int $id):array
    {
        $sqlOpciones=$this->bd->query("SELECT LISOPC_ConsInte__b AS id, LISOPC_Nombre____b AS texto FROM {$this->bdSistema}.LISOPC WHERE LISOPC_ConsInte__OPCION_b={$id}");
        $opciones = array();
        $estado='error';
        if($sqlOpciones && $sqlOpciones->num_rows > 0){
            $estado='ok';
            while($row = $sqlOpciones->fetch_object()){
                array_push($opciones, array("id"=>$row->id,"texto"=>$row->texto));
            }
        }
        return array('estado'=>$estado, 'mensaje'=>$opciones);
    }

    // OBTENER LA CONFIGURACIÓN DEL FORMULARIO WEB DEL AGENDADOR
    public function getWebForm(string $id)
    {
        $sql=$this->bd->query("SELECT ESTPAS_ConsInte__b,WEBFORM_Consinte__b,WEBFORM_Nombre_b,WEBFORM_Origen_b,WEBFORM_Guion____b FROM {$this->bdSistema}.ESTPAS LEFT JOIN {$this->bdSistema}.WEBFORM ON ESTPAS_ConsInte__b=WEBFORM_ConsInte__ESTPAS_b WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__MUESTR_b)) = '{$id}' AND ESTPAS_Tipo______b=4");
        $mensaje='No se encontro información';
        $estado='error';
        if($sql && $sql->num_rows == 1){
            $estado='ok';
            $mensaje=$sql->fetch_object();
            $mensaje->url="https://{$_SERVER["HTTP_HOST"]}/crm_php/web_forms.php?web=".base64_encode($mensaje->WEBFORM_Guion____b)."&paso={$mensaje->ESTPAS_ConsInte__b}&origen=WF_{$mensaje->WEBFORM_Origen_b}";
        }

        return array('estado'=>$estado,'mensaje'=>$mensaje);
    }

    public function getEstpas(int $id){
        $sqlEstpas=$this->bd->query("SELECT ESTPAS_ConsInte__b,ESTPAS_Comentari_b FROM {$this->bdSistema}.ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b={$id}");
        $optEstpas= array();
        $estado='error';
        if($sqlEstpas && $sqlEstpas->num_rows > 0){
            $estado='ok';
            while($row = $sqlEstpas->fetch_object()){
                array_push($optEstpas, array("id"=>$row->ESTPAS_ConsInte__b, "nombre"=>$row->ESTPAS_Comentari_b));
            }
        }
        return array('estado'=>$estado, 'mensaje'=>$optEstpas);
    }
    
}