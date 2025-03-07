<?php

require_once __DIR__.'/../models/Agenda.php';

class AgendaController{

    private function listar( string $tipoLista, array $condiciones=null):void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            // VALIDAR NOMBRES Y TIPO DE PARAMETROS
            $agendador  = isset($data['Agendador']) && is_string($data['Agendador']) ? $data['Agendador'] : false;
            $tipo  = isset($data['tipoRecurso']) ? $data['tipoRecurso'] : '';
            $ubi  = isset($data['ubicacionRecurso']) ? $data['ubicacionRecurso'] : '';
            $recurso  = isset($data['recurso']) ? $data['recurso'] : '';

            if($agendador){
                $agenda = new Agenda();
                $agenda->setId($agendador);
                $agenda->setTipo((string)$tipo);
                $agenda->setUbicacion((string)$ubi);
                $agenda->setRecurso((string)$recurso);

                // OBTENER LA LISTA DE TIPO O UBICACIÓN DE LOS RECURSOS
                if($tipoLista == 'listaCita'){
                    $condiciones=isset($data['condiciones']) && is_array($data['condiciones']) ? $data['condiciones'] : null;
                    $identificacion = isset($data['Identificación_solicitante']) && is_numeric($data['Identificación_solicitante']) ? $data['Identificación_solicitante'] : false;
                    if($identificacion){
                        $agenda->setIdentificacion($identificacion);
                        $response=$agenda->listaCita($condiciones);
                    }else{
                        showResponse("Parametros incompletos o invalidos");
                    }
                }else{
                    $response=$agenda->$tipoLista();
                }

                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $response['mensaje'],
                    'response' => $response['estado']
                ];
            
                echo json_encode($data,$data['code']);
                exit();
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function validarClienteExiste():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            // VALIDAR NOMBRES Y TIPO DE PARAMETROS
            $agendador      = isset($data['Agendador']) && is_string($data['Agendador']) ? $data['Agendador'] : false;
            $identificacion = isset($data['Identificación_solicitante']) && is_numeric($data['Identificación_solicitante']) ? $data['Identificación_solicitante'] : false;
    
            if($agendador && $identificacion){
                $cliente=new Agenda();
                $cliente->setId($agendador);
                $cliente->setIdentificacion($identificacion);
    
                $response = $cliente->validarCliente();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function validarClienteEstadoValido():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            // VALIDAR NOMBRES Y TIPO DE PARAMETROS
            $agendador      = isset($data['Agendador']) && is_string($data['Agendador']) ? $data['Agendador'] : false;
            $identificacion = isset($data['Identificación_solicitante']) && is_numeric($data['Identificación_solicitante']) ? $data['Identificación_solicitante'] : false;
            $condiciones    = isset($data['condiciones']) && is_array($data['condiciones']) ? $data['condiciones'] : null;
            if($agendador && $identificacion){
                $cliente=new Agenda();
                $cliente->setId($agendador);
                $cliente->setIdentificacion($identificacion);
    
                $response = $cliente->validarEstado(null,$condiciones);
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function agendarCita():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            // VALIDAR NOMBRES Y TIPO DE PARAMETROS
            $agendador      = isset($data['Agendador']) && is_string($data['Agendador']) ? $data['Agendador'] : false;
            $identificacion = isset($data['Identificación_solicitante']) && is_numeric($data['Identificación_solicitante']) ? $data['Identificación_solicitante'] : false;
            $idCita         = isset($data['idCita']) && is_numeric($data['idCita']) ? $data['idCita'] : false;

            if($agendador && $identificacion && $idCita){

                $cliente=new Agenda();
                $cliente->setId($agendador);
                $cliente->setIdentificacion($identificacion);
                $cliente->setIdCita($idCita);

                // SE INICIALIZA LA RESPUESTA
                $response=[];
                $response['estado']=false;
                
                // OBTENER LOS DATOS DEL AGENDADOR
                $bdPersonas=$cliente->validarCliente();
                $cliente->validarCita($bdPersonas);
                $idcliente=false;
                
                // VALIDAR SI LA CITA EXISTE Y ESTA DISPONIBLE
                $cita=$cliente->getCita($cliente->getIdCita(), $bdPersonas);
                $estadoCita="G{$bdPersonas['AGENDADOR_ConsInte__GUION__Dis_b']}_C{$bdPersonas['AGENDADOR_ConsInte__PREGUN_Est_b']}";

                if($cita[$estadoCita] == '-401' || $cita[$estadoCita] == '-406'){

                    // VALIDAR SI EL CLIENTE YA EXISTE O TOCA CREARLO
                    if($bdPersonas['estado']){
                        //VALIDAR SI EL CLIENTE DEBE ESTAR EN CIERTO ESTADO
                        // if($bdPersonas['AGENDADOR_ValidaEst_b'] == '-1'){
                        //     $estado=$cliente->validarEstado($bdPersonas);
                        //     if($estado['estado']){
                        //         $idcliente=$bdPersonas['user'];
                        //     }else{
                        //         showResponse("Cliente no cumple con el estado requerido, no se agendo la cita");
                        //     }
                        // }else{
                        //     $idcliente=$bdPersonas['user'];
                        // }
                        $idcliente=$bdPersonas['user'];
                    }else{
                        // VALIDAR SI EL CLIENTE DEBE EXISTIR PARA PODER AGENDAR LA CITA
                        if($bdPersonas['AGENDADOR_ValidaPer_b'] == '0'){
                            // TOCA INSERTAR EL CLIENTE EN LA BASE DE DATOS DE LAS PERSONAS
                            $idcliente=$cliente->insertBdP($bdPersonas['bd'],$bdPersonas['id'],$cliente->getIdentificacion());
                        }else{
                            showResponse("Cliente no existe en el sistema, no se agendo la cita");
                        }
                    }
                    
                    // VALIDAR SI SE OBTUVO EL ID DEL CLIENTE BIEN SEA PORQUE SE INSERTO O PORQUE YA EXISTIA
                    if(is_numeric($idcliente) && $idcliente > 0){
                        // OBTENER LOS DATOS DEL CLIENTE
                        $getCliente=$cliente->getCliente($bdPersonas['bd'],$idcliente,$bdPersonas['AGENDADOR_ConsInte__PREGUN_NomP_b'],$bdPersonas['AGENDADOR_ConsInte__PREGUN_CelP_b'],$bdPersonas['AGENDADOR_ConsInte__PREGUN_MailP_b']);
    
                        //AGENDAR LA CITA
                        $response = $cliente->agendarCita($bdPersonas, $idcliente, $getCliente);
                        showResponse($response['mensaje'],$response['estado'],200,"200 OK");
                    }else{
                        showResponse("No se pudo completar la solicitud",false,500,"500 Internal Server Error");
                    }
                }else{
                    showResponse("La cita no esta disponible");
                }
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function cancelarCita():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            // VALIDAR NOMBRES Y TIPO DE PARAMETROS
            $agendador  = isset($data['Agendador']) && is_string($data['Agendador']) ? $data['Agendador'] : false;
            $idCita     = isset($data['idCita']) && is_numeric($data['idCita']) ? $data['idCita'] : false;

            if($agendador && $idCita){
                $agenda = new Agenda();
                $agenda->setIdCita($idCita);
                $agenda->setId($agendador);

                // CANCELAR LA CITA
                $response=$agenda->cancelaCita();
                showResponse($response['mensaje'],$response['estado'],200,"200 OK");
            }
        }
        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function listarTiposRecurso():void
    {
        $this->listar("listaTipoRecurso");
    }

    public function listarUbicacionesRecurso():void
    {
        $this->listar("listaUbiRecurso");
    }

    public function listarRecursos():void
    {
        $this->listar("listaRecurso");
    }

    public function consultarCitasDisponibles():void
    {
        $this->listar("listaCita");
    }

    public function consultarCitasPaciente():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            // VALIDAR NOMBRES Y TIPO DE PARAMETROS
            $agendador  = isset($data['Agendador']) && is_string($data['Agendador']) ? $data['Agendador'] : false;
            $identificacion = isset($data['Identificación_solicitante']) && is_numeric($data['Identificación_solicitante']) ? $data['Identificación_solicitante'] : false;

            if($agendador && $identificacion){
                $agenda = new Agenda();
                $agenda->setId($agendador);
                $agenda->setIdentificacion($identificacion);

                // OBTENER LA LISTA DE CITAS DEL PACIENTE
                $response=$agenda->listaCitaPaciente();

                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $response['mensaje'],
                    'response' => $response['estado']
                ];
            
                echo json_encode($data,$data['code']);
                exit();
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }

    public function consultarCamposCondicionesEstado():void
    {
        //VALIDAR QUE EL REQUEST SEA DE TIPO POST
        Helpers::method($_SERVER['REQUEST_METHOD'],'POST');

        $data = json_decode(file_get_contents('php://input'), true);
        if($data){
            //VALIDAR AUTENTICACIÓN
            Helpers::auth($data);

            // VALIDAR NOMBRES Y TIPO DE PARAMETROS
            $agendador  = isset($data['Agendador']) && is_string($data['Agendador']) ? $data['Agendador'] : false;

            if($agendador){
                $agenda = new Agenda();
                $agenda->setId($agendador);

                // OBTENER LA LISTA DE CAMPOS CONDICIONES QUE SE LE DEBEN SOLICITAR AL CLIENTE
                $response=$agenda->camposCondiciones();

                // ENVIAR LA RESPUESTA
                header("HTTP/1.1 200 OK");
                $data = [
                    'code' => 200,
                    'message' => $response['mensaje'],
                    'response' => $response['estado']
                ];
            
                echo json_encode($data,$data['code']);
                exit();
            }
        }

        // CUANDO NO SE RECIBAN LOS PARAMETROS O ESTOS NO CUMPLAN CON LAS CONDICIONES
        showResponse("Parametros incompletos o invalidos");
    }
}