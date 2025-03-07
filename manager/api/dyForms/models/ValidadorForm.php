<?php
require_once('Form.php');
class ValidadorForm extends Form{
    
    public function __construct()
    {
        parent::__construct();
    }

    // VALIDAR QUE UN FORMULARIO EXISTA EN UN HUESPED PARA SER USADO COMO SUBFORMULARIO O LISTA AUXILIAR
    public static function validateFormExists(int $idHuesped, int $idForm):void
    {
        (object)$sql=parent::$db->query("SELECT GUION__ConsInte__b FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__PROYEC_b={$idHuesped} AND GUION__ConsInte__b = {$idForm}");
        if($sql){
            if($sql->num_rows == 0){
                showResponse("El formulario no existe, debe crearlo");
            }
        }else{
            parent::error();
        }
    }
    
    // VALIDAR QUE UN FORMULARIO QUE VENGA DE BPO YA EXISTA EN UN  HUESPED PARA SER USADO COMO SUBFORMULARIO O LISTA AUXILIAR
    public static function validateFormExistsBPO(int $idHuesped, int $idFormBPO):void
    {
        (object)$sql=parent::$db->query("SELECT GUION__ConsInte__b FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__PROYEC_b={$idHuesped} AND GUION_IdGuionBPO = {$idFormBPO}");
        if($sql){
            if($sql->num_rows > 0){
                $sql=$sql->fetch_object();
                showResponse(Helpers::encrypt($sql->GUION__ConsInte__b),true,200,"200 OK");
            }
        }else{
            parent::error();
        }
    }

    // VALIDAR QUE UNA SECCIÓN EXISTA PARA AGREGARLE CAMPOS
    public static function validateSeccionExists(int $idForm, int $idSeccion):void
    {
        (object)$sql=parent::$db->query("SELECT SECCIO_ConsInte__b FROM DYALOGOCRM_SISTEMA.SECCIO WHERE SECCIO_ConsInte__GUION__b={$idForm} AND SECCIO_ConsInte__b = {$idSeccion}");
        if($sql){
            if($sql->num_rows == 0){
                showResponse("La sección no existe, debe crearla");
            }
        }else{
            parent::error();
        }
    }

    // VALIDAR QUE UNA SECCIÓN EXISTA PARA AGREGARLE CAMPOS
    public static function validateSeccionExistsBPO(int $idForm, int $idSeccionBPO):void
    {
        (object)$sql=parent::$db->query("SELECT SECCIO_ConsInte__b FROM DYALOGOCRM_SISTEMA.SECCIO WHERE SECCIO_ConsInte__GUION__b={$idForm} AND SECCIO_IdSeccioBPO = {$idSeccionBPO}");
        if($sql){
            if($sql->num_rows > 0){
                $sql=$sql->fetch_object();
                showResponse(Helpers::encrypt($sql->SECCIO_ConsInte__b),true,200,"200 OK");
            }
        }else{
            parent::error();
        }
    }

    // VALIDAMOS SECCIONES POR NOMBRE Y FORMULARIO
    public static function validateSeccionExistsName(string $name, int $idForm):void
    {
        (object)$sql=parent::$db->query("SELECT SECCIO_ConsInte__b FROM DYALOGOCRM_SISTEMA.SECCIO WHERE SECCIO_Nombre____b='{$name}' AND SECCIO_ConsInte__GUION__b = {$idForm}");
        if($sql){
            if($sql->num_rows > 0){
                $sql=$sql->fetch_object();
                showResponse(Helpers::encrypt($sql->SECCIO_ConsInte__b),true,200,"200 OK");
            }
        }else{
            parent::error();
        }
    }

    // VALIDAR QUE UNA LISTA EXISTA EN EL SISTEMA PARA UN HUESPED
    public static function validateListaExist(int $idHuesped, int $idLista):void
    {
        (object)$sql=parent::$db->query("SELECT OPCION_ConsInte__b FROM DYALOGOCRM_SISTEMA.OPCION WHERE OPCION_ConsInte__PROYEC_b={$idHuesped} AND OPCION_ConsInte__b={$idLista}");
        if($sql){
            if($sql->num_rows == 0){
                showResponse("La lista no existe, debe crearla");
            }
        }else{
            parent::error();
        }
    }

    // VALIDAR QUE UNA LISTA DE BPO NO EXISTA EN EL SISTEMA PARA UN HUESPED
    public static function validateListaExistBPO(int $idHuesped, int $idOpcionBPO):void
    {
        if($idOpcionBPO != 0){
            (object)$sql=parent::$db->query("SELECT OPCION_ConsInte__b,OPCION_UsuaCrea__b,OPCION_Nombre____b FROM DYALOGOCRM_SISTEMA.OPCION WHERE OPCION_ConsInte__PROYEC_b={$idHuesped} AND OPCION_IdListaBPO={$idOpcionBPO} AND OPCION_Nombre____b NOT LIKE 'Tipificaciones - %'");
            if($sql){
                if($sql->num_rows > 0){
                    $sql=$sql->fetch_object();
                    showResponse(Helpers::encrypt($sql->OPCION_ConsInte__b),true,200,"200 OK");
                }
            }else{
                parent::error();
            }
        }
    }

    // VALIDAR QUE UN CAMPO EXISTA PARA PODER INCLUIRLO EN SALTOS,LISTAS AUXILIARES, SUBFORMS, SALTOS POR SECCION
    public static function validateCampoExists(int $idCampo):void
    {
        (object)$sql=parent::$db->query("SELECT PREGUN_ConsInte__b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__b={$idCampo}");
        if($sql){
            if($sql->num_rows == 0){
                showResponse("El campo no existe, debe crearlo");
            }
        }else{
            parent::error();
        }
    }

    // VALIDAR QUE UN CAMPO QUE VENGA DE BPO EXISTA PARA UN SUBFORMULARIO,LISTA AUXILIAR PARA PODER INCLUIRLO EN SALTOS,LISTAS AUXILIARES, SUBFORMS, SALTOS POR SECCION
    public static function validateCampoExistsBPO(int $idForm, int $idPregunBPO):void
    {
        (object)$sql=parent::$db->query("SELECT PREGUN_ConsInte__b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b={$idForm} AND PREGUN_IdPregunBPO={$idPregunBPO}");
        if($sql){
            if($sql->num_rows > 0){
                $sql=$sql->fetch_object();
                showResponse(Helpers::encrypt($sql->PREGUN_ConsInte__b),true,200,"200 OK");
            }
        }else{
            parent::error();
        }
    }
    
    // VALIDAR QUE UNA OPCION DE LISTA EXISTA PARA PODER INCLUIRLA EN SALTOS,LISTAS AUXILIARES, SUBFORMS, SALTOS POR SECCION
    public static function validateOpcionExists(int $idOpcion):void
    {
        (object)$sql=parent::$db->query("SELECT LISOPC_ConsInte__b FROM DYALOGOCRM_SISTEMA.LISOPC WHERE LISOPC_ConsInte__b={$idOpcion}");
        if($sql){
            if($sql->num_rows == 0){
                showResponse("La opcion no existe, debe crearla");
            }
        }else{
            parent::error();
        }
    }
    
    // VALIDAR QUE UNA OPCION DE LISTA EXISTA PARA PODER INCLUIRLA EN SALTOS,LISTAS AUXILIARES, SUBFORMS, SALTOS POR SECCION
    public static function validateOpcionExistsBPO(int $idOpcion, int $idLisopcBPO):void
    {
        (object)$sql=parent::$db->query("SELECT LISOPC_ConsInte__b,LISOPC_Clasifica_b FROM DYALOGOCRM_SISTEMA.LISOPC WHERE LISOPC_ConsInte__OPCION_b={$idOpcion} AND LISOPC_IdLisopcBPO={$idLisopcBPO}");
        if($sql){
            if($sql->num_rows > 0){
                $sql=$sql->fetch_object();
                if(is_null($sql->LISOPC_Clasifica_b)){
                    showResponse(Helpers::encrypt($sql->LISOPC_ConsInte__b),true,200,"200 OK");
                }
            }
        }else{
            parent::error();
        }
    }
    // VALIDAR QUE UN SALTO YA EXISTA
    public static function validateSaltoExists():void
    {

    }
}