<?php

class AgendaAux{

    protected static $db;

    public function __construct()
    {
        self::$db = Helpers::connect();
    }
    // VALIDA QUE EL AGENDADOR TENGA LLENO LOS CAMPOS NECESARIOS PARA VALIDAR QUE UNA PERSONA EXISTA EN LA BD
    private function validarPersona(object $data)
    {

        if(is_null($data->AGENDADOR_ConsInte__GUION__Pob_b) || is_null($data->AGENDADOR_ConsInte__PREGUN_IdP_b)){
            showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
        }

        $response=[];
        $response['bd']=$data->AGENDADOR_ConsInte__GUION__Pob_b;
        $response['id']=$data->AGENDADOR_ConsInte__PREGUN_IdP_b;
        return $response;
    }
    
    // VALIDA QUE EL AGENDADOR TENGA LLENO LOS CAMPOS NECESARIOS PARA VALIDAR QUE UNA PERSONA EXISTA EN LA BD
    private function validarEstado(object $data)
    {

        if(is_null($data->AGENDADOR_ConsInte__GUION__Pob_b) || is_null($data->AGENDADOR_ConsInte__PREGUN_IdP_b) || is_null($data->AGENDADOR_ConsInte__PREGUN_EstP_b) || is_null($data->AGENDADOR_ConsInte__PREGUN_EstP_b)){
            showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
        }

        $response=[];
        $response['bd']=$data->AGENDADOR_ConsInte__GUION__Pob_b;
        $response['id']=$data->AGENDADOR_ConsInte__PREGUN_IdP_b;
        $response['estado']=$data->AGENDADOR_ConsInte__PREGUN_EstP_b;
        $response['estadoReq']=$data->AGENDADOR_EstadoReq____b;
        return $response;
    }


    //DEVOLVER LA BASE DE DATOS DEL AGENDADOR
    protected function getAgendador(int $id,string $function):array
    {
        $response=[];
        $sql = self::$db->query("SELECT AGENDADOR_ConsInte__GUION__Pob_b,AGENDADOR_ConsInte__PREGUN_IdP_b,AGENDADOR_ConsInte__PREGUN_EstP_b,AGENDADOR_EstadoReq____b FROM DYALOGOCRM_SISTEMA.AGENDADOR WHERE AGENDADOR_ConsInte__b={$id}");
        if($sql && $sql->num_rows == 1){
            $response=$this->$function($sql->fetch_object());
        }else{
            showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
        }

        return $response;
    }
}