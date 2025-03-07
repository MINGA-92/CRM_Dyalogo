<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."/../../conexion.php");
include_once(__DIR__."/../../funciones.php");
include_once(__DIR__."/../../../helpers/parameters.php");
date_default_timezone_set('America/Bogota');

function getIdAgendador(string $strId_p):int
{
    global $mysqli;
    global $BaseDatos_systema;

    $sql=$mysqli->query("SELECT AGENDADOR_ConsInte__b FROM {$BaseDatos_systema}.AGENDADOR WHERE md5(concat('".clave_get."', AGENDADOR_ConsInte__b)) = '{$strId_p}'");
    if($sql && $sql->num_rows > 0){
        $sql=$sql->fetch_object();
        return $sql->AGENDADOR_ConsInte__b;
    }

    return 0;
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    if(isset($_POST['getAgendador'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        if($id){
            $sql=$mysqli->query("SELECT AGENDADOR_ConsInte__b FROM {$BaseDatos_systema}.AGENDADOR WHERE AGENDADOR_ConsInte__GUION__Dis_b={$id}");
            if($sql && $sql->num_rows > 0){
                $sql=$sql->fetch_object();
                $estado=true;
                $mensaje=Url::urlSegura($sql->AGENDADOR_ConsInte__b);
            }else{
                $mensaje="No se identifico el agendador";
            }
        }

        echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
    }

    if(isset($_POST['validaCliente'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;
        $cc=isset($_POST['cc']) ? $_POST['cc'] : false;

        if($id && $cc){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
                "Identificación_solicitante" => (int)$cc,
            ];
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/validarClienteExiste",$data);
            exit;
        }

        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }

    if(isset($_POST['validaEstado'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;
        $cc=isset($_POST['cc']) ? $_POST['cc'] : false;

        if($id && $cc){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
                "Identificación_solicitante" => (int)$cc,
            ];
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/ValidarClienteEstadoValido",$data);
            exit;
        }
        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }

    if(isset($_POST['camposCondicion'])){
        $estado=false;
        $mensaje=array();    
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;
        // $mensaje['showSeccion'];
        if($id){
            $sql=$mysqli->query("SELECT CONDAGENDA_ConsInte__PREGUN_b,PREGUN_Texto_____b,PREGUN_Tipo______b,PREGUN_ConsInte__OPCION_B FROM {$BaseDatos_systema}.CONDAGENDA LEFT JOIN {$BaseDatos_systema}.PREGUN ON CONDAGENDA_ConsInte__PREGUN_b=PREGUN_ConsInte__b WHERE md5(concat('".clave_get."', CONDAGENDA_ConsInte__AGENDADOR_b)) = '{$id}' AND CONDAGENDA_Tipo_b=2");
            if($sql){
                $estado=true;
                if($sql->num_rows > 0){
                    while($row = $sql->fetch_object()){
                        array_push($mensaje, array(
                            "campo"=>$row->CONDAGENDA_ConsInte__PREGUN_b,
                            "texto"=>$row->PREGUN_Texto_____b,
                            "tipo"=>$row->PREGUN_Tipo______b,
                            "lista"=>$row->PREGUN_ConsInte__OPCION_B
                        ));
                    }
                }
            }else{
                $mensaje="No se valido si hay campos de condición";
            }
        }else{
            $mensaje="Parametros incompletos";
        }
        echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
    }

    if(isset($_POST['getOpcionesLista'])){
        $estado=false;
        $mensaje=array();    
        $id=isset($_POST['lista']) ? $_POST['lista'] : false;   
        if($id){
            $sql=$mysqli->query("SELECT LISOPC_ConsInte__b,LISOPC_Nombre____b FROM {$BaseDatos_systema}.LISOPC WHERE LISOPC_ConsInte__OPCION_b={$id}");
            if($sql){
                $estado=true;
                if($sql->num_rows > 0){
                    while($row = $sql->fetch_object()){
                        array_push($mensaje, array(
                            "id"=>$row->LISOPC_ConsInte__b,
                            "texto"=>$row->LISOPC_Nombre____b,
                        ));
                    }
                }
            }else{
                $mensaje="No se valido si hay campos de condición";
            }
        }else{
            $mensaje="Parametros incompletos";
        }
        echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje));
    }

    if(isset($_POST['filtrarTipo'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;

        if($id){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
            ];
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/listarTiposRecurso",$data);
            exit;
        }
        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }

    if(isset($_POST['filtrarUbicacion'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;

        if($id){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
            ];
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/listarUbicacionesRecurso",$data);
            exit;
        }
        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }

    if(isset($_POST['listarRecursos'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;
        $tipo=isset($_POST['tipo']) ? $_POST['tipo']: "";
        $ubicacion=isset($_POST['ubicacion']) ? $_POST['ubicacion'] : "";

        if($id){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
                "tipoRecurso"=>$tipo,
                "ubicacionRecurso"=>$ubicacion
            ];
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/listarRecursos",$data);
            exit;
        }
        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }

    if(isset($_POST['listarCitas'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;
        $tipo=isset($_POST['tipo']) ? $_POST['tipo']: "";
        $ubicacion=isset($_POST['ubicacion']) ? $_POST['ubicacion'] : "";
        $recurso=isset($_POST['recurso']) ? $_POST['recurso'] : "";
        $condiciones=isset($_POST['condiciones']) ? json_decode(stripslashes($_POST['condiciones'])) : false;
        $cc=isset($_POST['cc']) ? $_POST['cc'] : false;
        if($id && $cc){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
                "Identificación_solicitante"=>(int)$cc,
                "tipoRecurso"=>$tipo,
                "ubicacionRecurso"=>$ubicacion,
                "recurso"=>$recurso
            ];

            if($condiciones && count($condiciones) > 0){
                $data['condiciones'] = $condiciones;
            }
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/ConsultarCitasDisponibles",$data);
            exit;
        }
        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }

    if(isset($_POST['agendarCita'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;
        $cc=isset($_POST['cc']) ? $_POST['cc'] : false;
        $idCita=isset($_POST['idCita']) ? $_POST['idCita'] : false;

        if($id && $cc && $idCita){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
                "Identificación_solicitante"=>(int)$cc,
                "idCita"=>(int)$idCita,
            ];
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/AgendarCita",$data);
            exit;
        }
        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }

    if(isset($_POST['cancelarCita'])){
        $estado=false;
        $mensaje="Parametros incompletos";
        
        $id=isset($_POST['idAgendador']) ? $_POST['idAgendador'] : false;
        $idCita=isset($_POST['idCita']) ? $_POST['idCita'] : false;

        if($id && $idCita){
            $data =[
                "strUsuario_t"  => 'crm',
                "strToken_t"    => 'D43dasd321',
                "Agendador" 	=> (string)$id,
                "idCita"=>(int)$idCita,
            ];
            echo  consumirWSJSON("{$URL_ADDONS}/api/Agenda/cancelarCita",$data);
            exit;
        }
        echo json_encode(array("response"=>$estado,"message"=>$mensaje));
    }
}