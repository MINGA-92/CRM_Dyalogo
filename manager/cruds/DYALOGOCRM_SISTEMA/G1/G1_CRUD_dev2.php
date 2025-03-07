<?php

session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include(__DIR__ . "../../../../pages/conexion.php");
require_once(__DIR__ . "../../../../global/WSCoreClient.php");
require_once('../../../../helpers/parameters.php');
include('../../../global/funcionesGenerales.php');


function guardar_auditoria($accion, $superAccion) {
    global $mysqli;
    global $BaseDatos_systema;
    $Lsql = "INSERT INTO " . $BaseDatos_systema . ".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('" . date('Y-m-d H:s:i') . "', '" . date('Y-m-d H:s:i') . "', " . $_SESSION['IDENTIFICACION'] . ", 'USUARI', '" . $accion . "', '" . $superAccion . "', '" . $_SESSION['HUESPED'] . "' );";
    $res = $mysqli->query($Lsql);
}

date_default_timezone_set('America/Bogota');

function enviarMessages($code, $id = NULL, array $mensaje = ['strMensaje' => '', 'strEstado' => ''])
{
    switch ($code) {
        case '1':
            echo json_encode(array('code' => 1, 'message' => $mensaje['strMensaje'], 'id' => $id, 'estado' => $mensaje['strEstado']));
            break;

        case '2':
            echo json_encode(array('code' => 2, 'message' => "El correo ya se encuentra en uso!"));
            break;

        case '3':
            echo json_encode(array('code' => 3, 'message' => "El usuario ya se encuentra en uso!"));
            break;

        case '4':
            echo json_encode(array('code' => 4, 'message' => "La identificación ya esta registrada!"));
            break;
        
        case 'error':
            echo json_encode(array('code' => 'error', 'message' => $mensaje['strMensaje'] , 'estado' => $mensaje['strEstado']));
            break;

        default:
            echo json_encode(array('code' => 'default', 'message' => "Algo salio mal al crear el usuario!"));
            break;
    }
}

function obtenerIdAgente(int $id_usuarioCBX): int
{
    global $mysqli;
    global $BaseDatos_telefonia;

    $consulta = "SELECT id AS id_agente FROM {$BaseDatos_telefonia}.dy_agentes WHERE id_usuario_asociado = {$id_usuarioCBX}";

    $sql = mysqli_query($mysqli, $consulta);
    if ($sql && mysqli_num_rows($sql) > 0) {
        $id = mysqli_fetch_object($sql)->id_agente;
        // echo json_encode($id);
        // $id = mysqli_fetch_object($sql)->id;
    } else {
        $id = 0;
    }

    return $id;
}

function obtenerIdUsuari(string $id): int
{
    global $mysqli;
    global $BaseDatos_systema;

    $consulta = "SELECT USUARI_ConsInte__b AS id_usuari FROM {$BaseDatos_systema}.USUARI WHERE MD5(CONCAT('" . clave_get . "', USUARI_ConsInte__b)) = '{$id}'";
    $sql = mysqli_query($mysqli, $consulta);

    if ($sql && mysqli_num_rows($sql) > 0) {
        $id = mysqli_fetch_object($sql)->id_usuari;
    } else {
        $id = 0;
    }

    return $id;
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    //Datos del formulari
    if (isset($_POST['CallDatos'])) {
        $id_usuari = obtenerIdUsuari($_POST['id']);
        $Lsql = "SELECT * FROM {$BaseDatos_systema}.USUARI WHERE  USUARI_ConsInte__b = '{$id_usuari}'";
        $result = $mysqli->query($Lsql);
        $datos = array();
        $i = 0;
        $cargo = "";

        while ($key = $result->fetch_object()) {
            // echo "key => ",json_encode(utf8_encode($key->USUARI_Nombre____b)) ;

            $datos[$i]['USUARI_Nombre____b'] = $key->USUARI_Nombre____b;

            $datos[$i]['USUARI_Identific_b'] = $key->USUARI_Identific_b;

            $datos[$i]['USUARI_Cargo_____b'] = $key->USUARI_Cargo_____b;

            $cargo = $key->USUARI_Cargo_____b;

            $datos[$i]['USUARI_Codigo____b'] = $key->USUARI_Codigo____b;

            $datos[$i]['principal'] = $key->USUARI_Nombre____b;

            $datos[$i]['USUARI_Correo___b'] = $key->USUARI_Correo___b;

            $datos[$i]['id_usuario_agente'] = obtenerIdAgente($key->USUARI_UsuaCBX___b);

            $datos[$i]['id_usuari'] = $key->USUARI_ConsInte__b;

            $datos[$i]['USUARI_ConsInte__USUPER_b'] = $key->USUARI_ConsInte__USUPER_b;

            $datos[$i]['USUARI_Clave_____b'] = $key->USUARI_Clave_____b;

            $datos[$i]['USUARI_HorIniLun_b'] = $key->USUARI_HorIniLun_b;

            $datos[$i]['USUARI_HorFinLun_b'] = $key->USUARI_HorFinLun_b;

            $datos[$i]['USUARI_HorIniMar_b'] = $key->USUARI_HorIniMar_b;

            $datos[$i]['USUARI_HorFinMar_b'] = $key->USUARI_HorFinMar_b;

            $datos[$i]['USUARI_HorIniMie_b'] = $key->USUARI_HorIniMie_b;

            $datos[$i]['USUARI_HorFinMie_b'] = $key->USUARI_HorFinMie_b;

            $datos[$i]['USUARI_HorIniJue_b'] = $key->USUARI_HorIniJue_b;

            $datos[$i]['USUARI_HorFinJue_b'] = $key->USUARI_HorFinJue_b;

            $datos[$i]['USUARI_HorIniVie_b'] = $key->USUARI_HorIniVie_b;

            $datos[$i]['USUARI_HorFinVie_b'] = $key->USUARI_HorFinVie_b;

            $datos[$i]['USUARI_HorIniSab_b'] = $key->USUARI_HorIniSab_b;

            $datos[$i]['USUARI_HorFinSab_b'] = $key->USUARI_HorFinSab_b;

            $datos[$i]['USUARI_HorIniDom_b'] = $key->USUARI_HorIniDom_b;

            $datos[$i]['USUARI_HorFinDom_b'] = $key->USUARI_HorFinDom_b;

            $datos[$i]['USUARI_HorIniFes_b'] = $key->USUARI_HorIniFes_b;

            $datos[$i]['USUARI_HorFinFes_b'] = $key->USUARI_HorFinFes_b;

            $datos[$i]['USUARI_Bloqueado_b'] = $key->USUARI_Bloqueado_b;

            $datos[$i]['USUARI_IdMalla_b'] = $key->USUARI_IdMalla_b;

            $tipoContrato = 0;
            if ($key->USUARI_TipoContr_b != null) {
                $tipoContrato = $key->USUARI_TipoContr_b;
            }
            $datos[$i]['USUARI_TipoContr_b'] = $tipoContrato;

            $fechaInicioContrato = date('Y-m-d');
            if ($key->USUARI_FechIniContr_b != null) {
                $fechaInicioContrato = $key->USUARI_FechIniContr_b;
                $fechaInicioContrato = date("Y-m-d", strtotime($fechaInicioContrato));
            }
            $datos[$i]['USUARI_FechIniContr_b'] = $fechaInicioContrato;

            $fechaFinContrato = '';
            if ($key->USUARI_FechFinContr_b != null) {
                $fechaFinContrato = $key->USUARI_FechFinContr_b;
                $fechaFinContrato = date("Y-m-d", strtotime($fechaFinContrato));
            }
            $datos[$i]['USUARI_FechFinContr_b'] = $fechaFinContrato;

            $imagenUser = "assets/img/Kakashi.fw.png?foto=" . rand(10, 9999);
            if($key->USUARI_Foto______b != null && $key->USUARI_Foto______b != ""){
                if (file_exists("/var/../Dyalogo/img_usuarios/usr" . $key->USUARI_UsuaCBX___b . ".jpg") || file_exists("/var/../Dyalogo/img_usuarios/usr" . $key->USUARI_UsuaCBX___b . ".jpeg") || file_exists("/var/../Dyalogo/img_usuarios/usr" . explode("?", $key->USUARI_Foto______b)[0] )) {
                    $imagenUser = "/DyalogoImagenes/usr" . $key->USUARI_Foto______b;
                }
            }

            $datos[$i]['USUARI_Foto______b'] = $imagenUser;

            $i++;
        }

        $datosUsuPau1 = array();
        $datosUsuPau2 = array();
        $datosUsuPau3 = array();

        if ($cargo == "agente" || $cargo == "supervision") {

            $Lsql = "SELECT * FROM {$BaseDatos_systema}.USUPAU WHERE md5(concat('" . clave_get . "', USUPAU_ConsInte__USUARI_b))  = '{$_POST['id']}' AND  USUPAU_PausasId_b = (SELECT pausa_por_defecto_1 FROM dyalogo_general.huespedes WHERE id = " . $_SESSION['HUESPED'] . ") LIMIT 1";
            $result = $mysqli->query($Lsql);
            if ($result->num_rows > 0) {
                $j = 0;
                while ($key = $result->fetch_object()) {

                    $datosUsuPau1[$j]['USUPAU_ConsInte__b'] = $key->USUPAU_ConsInte__b;
                    $datosUsuPau1[$j]['USUPAU_PausasId_b'] = $key->USUPAU_PausasId_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniLun_b'] = $key->USUPAU_HorIniLun_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinLun_b'] = $key->USUPAU_HorFinLun_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniMar_b'] = $key->USUPAU_HorIniMar_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinMar_b'] = $key->USUPAU_HorFinMar_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniMie_b'] = $key->USUPAU_HorIniMie_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinMie_b'] = $key->USUPAU_HorFinMie_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniJue_b'] = $key->USUPAU_HorIniJue_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinJue_b'] = $key->USUPAU_HorFinJue_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniVie_b'] = $key->USUPAU_HorIniVie_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinVie_b'] = $key->USUPAU_HorFinVie_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniSab_b'] = $key->USUPAU_HorIniSab_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinSab_b'] = $key->USUPAU_HorFinSab_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniDom_b'] = $key->USUPAU_HorIniDom_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinDom_b'] = $key->USUPAU_HorFinDom_b;
                    $datosUsuPau1[$j]['USUPAU_HorIniFes_b'] = $key->USUPAU_HorIniFes_b;
                    $datosUsuPau1[$j]['USUPAU_HorFinFes_b'] = $key->USUPAU_HorFinFes_b;

                    $j++;
                }
            }


            $Lsql = "SELECT * FROM " . $BaseDatos_systema . ".USUPAU WHERE md5(concat('" . clave_get . "', USUPAU_ConsInte__USUARI_b))  = '" . $_POST['id'] . "'  AND  USUPAU_PausasId_b = (SELECT pausa_por_defecto_2 FROM dyalogo_general.huespedes WHERE id = " . $_SESSION['HUESPED'] . ") LIMIT 1";
            $result = $mysqli->query($Lsql);
            $k = 0;
            while ($key = $result->fetch_object()) {

                $datosUsuPau2[$k]['USUPAU_ConsInte__b'] = $key->USUPAU_ConsInte__b;
                $datosUsuPau2[$k]['USUPAU_PausasId_b'] = $key->USUPAU_PausasId_b;
                $datosUsuPau2[$k]['USUPAU_HorIniLun_b'] = $key->USUPAU_HorIniLun_b;
                $datosUsuPau2[$k]['USUPAU_HorFinLun_b'] = $key->USUPAU_HorFinLun_b;
                $datosUsuPau2[$k]['USUPAU_HorIniMar_b'] = $key->USUPAU_HorIniMar_b;
                $datosUsuPau2[$k]['USUPAU_HorFinMar_b'] = $key->USUPAU_HorFinMar_b;
                $datosUsuPau2[$k]['USUPAU_HorIniMie_b'] = $key->USUPAU_HorIniMie_b;
                $datosUsuPau2[$k]['USUPAU_HorFinMie_b'] = $key->USUPAU_HorFinMie_b;
                $datosUsuPau2[$k]['USUPAU_HorIniJue_b'] = $key->USUPAU_HorIniJue_b;
                $datosUsuPau2[$k]['USUPAU_HorFinJue_b'] = $key->USUPAU_HorFinJue_b;
                $datosUsuPau2[$k]['USUPAU_HorIniVie_b'] = $key->USUPAU_HorIniVie_b;
                $datosUsuPau2[$k]['USUPAU_HorFinVie_b'] = $key->USUPAU_HorFinVie_b;
                $datosUsuPau2[$k]['USUPAU_HorIniSab_b'] = $key->USUPAU_HorIniSab_b;
                $datosUsuPau2[$k]['USUPAU_HorFinSab_b'] = $key->USUPAU_HorFinSab_b;
                $datosUsuPau2[$k]['USUPAU_HorIniDom_b'] = $key->USUPAU_HorIniDom_b;
                $datosUsuPau2[$k]['USUPAU_HorFinDom_b'] = $key->USUPAU_HorFinDom_b;
                $datosUsuPau2[$k]['USUPAU_HorIniFes_b'] = $key->USUPAU_HorIniFes_b;
                $datosUsuPau2[$k]['USUPAU_HorFinFes_b'] = $key->USUPAU_HorFinFes_b;

                $k++;
            }

            $Lsql = "SELECT * FROM " . $BaseDatos_systema . ".USUPAU WHERE md5(concat('" . clave_get . "', USUPAU_ConsInte__USUARI_b))  = '" . $_POST['id'] . "'  AND  USUPAU_PausasId_b = (SELECT pausa_por_defecto_3 FROM dyalogo_general.huespedes WHERE id = " . $_SESSION['HUESPED'] . ") LIMIT 1";
            $result = $mysqli->query($Lsql);
            $m = 0;
            while ($key = $result->fetch_object()) {

                $datosUsuPau3[$m]['USUPAU_ConsInte__b'] = $key->USUPAU_ConsInte__b;
                $datosUsuPau3[$m]['USUPAU_PausasId_b'] = $key->USUPAU_PausasId_b;
                $datosUsuPau3[$m]['USUPAU_HorIniLun_b'] = $key->USUPAU_HorIniLun_b;
                $datosUsuPau3[$m]['USUPAU_HorFinLun_b'] = $key->USUPAU_HorFinLun_b;
                $datosUsuPau3[$m]['USUPAU_HorIniMar_b'] = $key->USUPAU_HorIniMar_b;
                $datosUsuPau3[$m]['USUPAU_HorFinMar_b'] = $key->USUPAU_HorFinMar_b;
                $datosUsuPau3[$m]['USUPAU_HorIniMie_b'] = $key->USUPAU_HorIniMie_b;
                $datosUsuPau3[$m]['USUPAU_HorFinMie_b'] = $key->USUPAU_HorFinMie_b;
                $datosUsuPau3[$m]['USUPAU_HorIniJue_b'] = $key->USUPAU_HorIniJue_b;
                $datosUsuPau3[$m]['USUPAU_HorFinJue_b'] = $key->USUPAU_HorFinJue_b;
                $datosUsuPau3[$m]['USUPAU_HorIniVie_b'] = $key->USUPAU_HorIniVie_b;
                $datosUsuPau3[$m]['USUPAU_HorFinVie_b'] = $key->USUPAU_HorFinVie_b;
                $datosUsuPau3[$m]['USUPAU_HorIniSab_b'] = $key->USUPAU_HorIniSab_b;
                $datosUsuPau3[$m]['USUPAU_HorFinSab_b'] = $key->USUPAU_HorFinSab_b;
                $datosUsuPau3[$m]['USUPAU_HorIniDom_b'] = $key->USUPAU_HorIniDom_b;
                $datosUsuPau3[$m]['USUPAU_HorFinDom_b'] = $key->USUPAU_HorFinDom_b;
                $datosUsuPau3[$m]['USUPAU_HorIniFes_b'] = $key->USUPAU_HorIniFes_b;
                $datosUsuPau3[$m]['USUPAU_HorFinFes_b'] = $key->USUPAU_HorFinFes_b;

                $m++;
            }
        }

        echo json_encode($arrayDatos = array('datosUsuario' => $datos, 'datosUsuPau1' =>  $datosUsuPau1, 'datosUsuPau2' =>  $datosUsuPau2, 'datosUsuPau3' =>  $datosUsuPau3));
    }

    //Datos de la lista de la izquierda
    if (isset($_POST['CallDatosJson'])) {
        $Lsql = "SELECT USUARI_ConsInte__b AS id, USUARI_Nombre____b AS camp1, USUARI_Correo___b AS camp2, USUARI_Bloqueado_b AS estado FROM {$BaseDatos_systema}.USUARI WHERE USUARI_ConsInte__PROYEC_b = {$_SESSION['HUESPED']} AND USUARI_ConsInte__PERUSU_b IS NULL AND USUARI_Eliminado_b <> '-1'";

        if ($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])) {
            $Lsql .= "AND (USUARI_Nombre____b LIKE('%{$_POST['Busqueda']}%') OR USUARI_Identific_b LIKE('%{$_POST['Busqueda']}%') OR USUARI_Codigo____b LIKE('%{$_POST['Busqueda']}%'))";
        }
        $Lsql .= "ORDER BY USUARI_Bloqueado_b DESC , USUARI_Nombre____b ASC LIMIT 0 , 50";

        // echo "<br> Lsql =>$Lsql <br>" ;

        $result = $mysqli->query($Lsql);
        $datos = array();
        $i = 0;
        while ($key = $result->fetch_object()) {
            $datos[$i]['camp1'] = $key->camp1;
            $datos[$i]['camp2'] = $key->camp2;
            $datos[$i]['estado'] = $key->estado;
            $datos[$i]['id'] = url::urlSegura($key->id);
            $i++;
        }
        echo json_encode($datos);
    }

    //insertar un tipo de pausa en dyalogo_telefonia.dy_tipos_descanso
    if(isset($_POST['insertarDatosPausa'])){
        $aletorio = uniqid();
        // echo $aletorio;
        $Lsql="INSERT INTO dyalogo_telefonia.dy_tipos_descanso (tipo) values ('{$aletorio}')";
        if ($mysqli->query($Lsql) === TRUE) {
            $ultimoId = $mysqli->insert_id;
        }
        echo json_encode($arrayData = array('ultimoId' => $ultimoId));
    }
    //consultar tipos de descanso

    /**YCR 2019-09-27
     * Agregar fila en la seccion de pausas con horario y sin horario
     */
    if(isset($_POST['agregarFilaPausa'])){
        $arrayTipoPausa = array();
        $ultimoId=0;
        $status='lleno';
        $i=0;
        if(  $_POST['tipo'] == '1'){            
            $Lsql = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = ".$_POST['tipo']." AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '".$_SESSION['HUESPED']."') AND  id  NOT IN('".$_POST['pausa1']."','".$_POST['pausa2']."','".$_POST['pausa3']."')";           
        }
        if(  $_POST['tipo'] == '0'){            
            $Lsql = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = ".$_POST['tipo']." AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '".$_SESSION['HUESPED']."') ";             
        }

        if(  ($result= $mysqli->query($Lsql)) == TRUE ){
            if($result->num_rows > 0){
                while ($key = $result->fetch_object()) {
                    $cadena=$key->tipo;

                    if (strpos($cadena, '-') !== false) {
                            $tipo = explode("-", $cadena);
                            $cadena =$tipo[1];
                    }
                    
                    $arrayTipoPausa[$i]['id'] = $key->id;
                    $arrayTipoPausa[$i]['tipo'] = $cadena;
                    $i++;
                }
                $Lsql = "INSERT INTO ".$BaseDatos_systema.".USUPAU (USUPAU_Tipo_b) values (".$_POST['tipo'].")"; 
                if($mysqli->query($Lsql) == TRUE){
                    $ultimoId = $mysqli->insert_id;
                }                   
                
            }else{
               $status='vacio';
            }
        } 

        echo json_encode($arrayData = array('ultimoId' => $ultimoId,'tipoPausa'=>$arrayTipoPausa,'status'=>$status));
    }

    if (isset($_GET['callDatosTipoDescanso'])) {

        $huespedId = $_SESSION['HUESPED'];

        // Traigo las pausas por defecto
        $sql = "SELECT id, pausa_por_defecto_1, pausa_por_defecto_2, pausa_por_defecto_3 FROM dyalogo_general.huespedes WHERE id = {$huespedId}";
        $res = $mysqli->query($sql);
        $huesped = $res->fetch_object();

        $arrayTipoPausa = array();
        $i = 0;
        $Lsql = "SELECT * FROM dyalogo_telefonia.dy_tipos_descanso WHERE id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '{$_SESSION['HUESPED']}') ORDER BY tipo ASC";
        if( ( $result = $mysqli->query($Lsql)) == TRUE ){
            
            while ($key = $result->fetch_object()) {
                $horaInicioDefecto='';
                $horaFinDefecto='';
                $cantidadMaxima='';
                $duracionMaxima='';
                $arrayTipoPausa[$i]['id'] = $key->id;
                $esPausaPorDefecto = false;

                $cadena = $key->tipo;
                if (strpos($cadena, '-') !== false) {
                    $tipo = explode("-", $cadena);
                    $cadena = $tipo[1];
                }
                if($key->hora_inicial_por_defecto != null){
                    $horaInicioDefecto = $key->hora_inicial_por_defecto ;
                }
                if($key->hora_final_por_defecto != null){
                    $horaFinDefecto = $key->hora_final_por_defecto ;
                }
                if($key->duracion_maxima != null){
                    $duracionMaxima = $key->duracion_maxima;
                }
                if($key->cantidad_maxima_evento_dia != null){
                    $cantidadMaxima = $key->cantidad_maxima_evento_dia;
                }

                if($key->id == $huesped->pausa_por_defecto_1 || $key->id == $huesped->pausa_por_defecto_2 || $key->id == $huesped->pausa_por_defecto_3){
                    $esPausaPorDefecto = true;
                }

                $arrayTipoPausa[$i]['tipo'] = trim($cadena);
                $arrayTipoPausa[$i]['descanso'] = $key->descanso;
                $arrayTipoPausa[$i]['tipo_pausa'] = $key->tipo_pausa;
                $arrayTipoPausa[$i]['hora_inicial_por_defecto'] = $horaInicioDefecto;
                $arrayTipoPausa[$i]['hora_final_por_defecto'] = $horaFinDefecto;
                $arrayTipoPausa[$i]['duracion_maxima'] =  $duracionMaxima;
                $arrayTipoPausa[$i]['cantidad_maxima_evento_dia'] = $cantidadMaxima;
                $arrayTipoPausa[$i]['es_pausa_por_defecto'] = $esPausaPorDefecto;

                $i++;
            }
        }
        // echo json_encode($arrayTipoPausa);
        echo json_encode($arrayData = array('tipoPausa'=>$arrayTipoPausa ));

    }

    if( isset($_GET['callHorarioPordefecto']) ){

        if($_POST['tipo'] == '1'){
            $Lsql = "SELECT * FROM dyalogo_telefonia.dy_tipos_descanso where id = ".$_POST['idPausa'];
            $result = $mysqli->query($Lsql);
            $data=$result->fetch_array();

            echo json_encode($arrayData = array('horaInicio'=>$data['hora_inicial_por_defecto'],'horaFin'=>$data['hora_final_por_defecto']));
        }
        if($_POST['tipo'] == '0'){
            $Lsql = "SELECT * FROM dyalogo_telefonia.dy_tipos_descanso where id = ".$_POST['idPausa'];
            $result = $mysqli->query($Lsql);
            $data=$result->fetch_array();
            $duracionMaxima=$data['duracion_maxima']*60;
            $duracionMaxima = gmdate("H:i:s",$duracionMaxima); 

            echo json_encode($arrayData = array('durMax'=>$duracionMaxima,'cantMax'=>$data['cantidad_maxima_evento_dia']));
        }
        
    }

    if( isset($_POST['eliminarFilaPausa'])){
        $Lsql = "DELETE FROM ".$BaseDatos_systema.".USUPAU WHERE USUPAU_ConsInte__b =".$_POST['index'];
        if ($mysqli->query($Lsql) == TRUE) {
             echo json_encode(true);             
        }
    }

    if(isset($_POST['eliminarFilaPausaSistema'])){
        $valido = 0;
        $status=false;
        
        $Lsql="SELECT * FROM dyalogo_general.huespedes WHERE id = ".$_SESSION['HUESPED']." 
        AND (pausa_por_defecto_1 = ".$_POST['index']." OR pausa_por_defecto_2 = ".$_POST['index']." OR pausa_por_defecto_3 = ".$_POST['index'].")";
        
        if( ($result = $mysqli->query($Lsql))  == TRUE){
            if($result->num_rows > 0  ){
                $valido = 1;
                $status=true;
                $mensaje = ["booEstado" => $status, "strMensaje" => "Tiene pausas asociadas"];
            }
        }

        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".USUPAU WHERE  USUPAU_PausasId_b = ".$_POST['index'];
        if( ($result = $mysqli->query($Lsql))  == TRUE){
            if($result->num_rows > 0  ){
                
                $valido = 1;
                $status=true;
                $mensaje = ["booEstado" => $status, "strMensaje" => "Tiene pausas asociadas"];
            }
        }


        $consulta = "SELECT * FROM dyalogo_telefonia.dy_descansos WHERE id_tipo_descanso = {$_POST['index']}";
        $sql = mysqli_query($mysqli, $consulta);
        if($sql && mysqli_num_rows($sql) > 0) {
            // echo "consulta->2", $consulta, "<br><br>";
            $valido = 1;
            $status=true;
            $mensaje = ["booEstado" => $status, "strMensaje" => "Tiene pausas asociadas"];
        }

        if($valido == 0){
            $Lsql = "DELETE FROM dyalogo_telefonia.dy_tipos_descanso WHERE id = ".$_POST['index'];
            // echo "Lsql->3",$Lsql, "<br><br>";
            if ($mysqli->query($Lsql) == TRUE) {
                $status=false;     
                $mensaje = ["booEstado" => $status, "strMensaje" => "No, tiene pausas asociadas, {$mysqli->error}"];     
            }
              
        }

        echo json_encode($mensaje);       

    }

    if (isset($_POST['eliminarPausa'])) {

        $idPausaReplace = $_POST['idPausaReplace'];
        $idPausaEliminar = $_POST['idPausaEliminar'];
        $valido = true;
        $response;
        
        // SE ACTUALIZA LA PAUSA SOBRE EL HISTORICO DE PAUSAS
        $sqlUpdateDescansos = "UPDATE {$BaseDatos_telefonia}.dy_descansos SET id_tipo_descanso = '{$idPausaReplace}' WHERE id_tipo_descanso = '{$idPausaEliminar}' AND id_proyecto = ( SELECT id FROM {$BaseDatos_telefonia}.dy_proyectos where id_huesped = '{$_SESSION['HUESPED']}' )";
        if ($mysqli->query($sqlUpdateDescansos)) {
            $valido = true;
        }else{
            $valido = false;
            $error = $mysqli->error;
        }

        // SE ACTUALIZA LA PAUSAS EN LOS USUARIOS CONFIGURADOS
        $sqlUpdatePau = "UPDATE {$BaseDatos_systema}.USUPAU SET USUPAU_PausasId_b = '{$idPausaReplace}' WHERE USUPAU_PausasId_b = '{$idPausaEliminar}'";
        if ($mysqli->query($sqlUpdatePau)) {
            $valido = true;
        }else{
            $valido = false;
            $error = $mysqli->error;
        }


        if($valido){
            $consultaDelete = "DELETE FROM {$BaseDatos_telefonia}.dy_tipos_descanso WHERE id = '{$idPausaEliminar}' AND id_proyecto = (SELECT id FROM {$BaseDatos_telefonia}.dy_proyectos where id_huesped = '{$_SESSION['HUESPED']}' )";

            if($mysqli->query($consultaDelete)){
                $response = json_encode(array("message" => "Se eliminó con éxito la pausa", "status" => true));
            }else{
                $error = $mysqli->error;
                $response = json_encode(array("message" => "La solicitud fallo {$error}", "status" => false));
            }
        }else{
            $response = json_encode(array("message" => "La solicitud fallo {$error}", "status" => false));
        }
        echo $response;

    }

    if (isset($_GET['guardarDatosPausa'])) {

        if (isset($_POST['idTipoDescanso'])  && isset($_POST['pausa'])   && isset($_POST['selectClasificacion']) &&  isset($_POST['selectTipoProgramacion']) && isset($_POST['horaInicioDefecto']) && isset($_POST['horaFinalDefecto']) &&  isset($_POST['duracionMax']) &&  isset($_POST['cantidadMax'])  ) {

            $idTipoDescanso=$_POST['idTipoDescanso'];
            $pausa=$_POST['pausa'];
            $selectClasificacion=$_POST['selectClasificacion'];
            $selectTipoProgramacion=$_POST['selectTipoProgramacion'];
            $horaInicioDefecto=$_POST['horaInicioDefecto'];
            $horaFinalDefecto=$_POST['horaFinalDefecto'];
            $duracionMax=$_POST['duracionMax'];
            $cantidadMax=$_POST['cantidadMax'];

            $i = 0;
            $valido = false;
            $arrayData = array();
             while($i < count($_POST['idTipoDescanso'])){

                $Lsql = "UPDATE dyalogo_telefonia.dy_tipos_descanso SET tipo = CONCAT((SELECT nombre FROM dyalogo_general.huespedes where id = " . $_SESSION['HUESPED'] . "),'-','" . $pausa[$i] . "') ,descanso = '" . $selectClasificacion[$i] . "',id_proyecto=(SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '" . $_SESSION['HUESPED'] . "'),tipo_pausa = '" . $selectTipoProgramacion[$i] . "'";
                if ($cantidadMax[$i] != '') {
                    $Lsql .= ", cantidad_maxima_evento_dia = '" . $cantidadMax[$i] . "'";
                }
                if ($duracionMax[$i] != '') {
                    $Lsql .= ", duracion_maxima = '" . $duracionMax[$i] . "'";
                }
                if ($horaInicioDefecto[$i] != '') {
                    $Lsql .= ", hora_inicial_por_defecto = '" . $horaInicioDefecto[$i] . "'";
                }
                if ($horaFinalDefecto[$i] != '') {
                    $Lsql .= ", hora_final_por_defecto = '" . $horaFinalDefecto[$i] . "'";
                }

                $Lsql .= " WHERE id = '" . $idTipoDescanso[$i] . "'";
                if ($mysqli->query($Lsql) === TRUE) {
                    $valido = true;
                    //    echo " actualizadoOtro => $i <br><br>";
                    array_push($arrayData, array("status" => $valido, "mensaje" => "Actualización realizada de manera exitosa", "idCampo" => $idTipoDescanso[$i], "campo" => $pausa[$i]));
                    // $arrayData = array("status" => $valido, "mensaje" => "Actualización realizada de manera exitosa", "campo" => $pausa[$i]);
                }

                if ($mysqli->error) {
                    $valido = false;
                    $error = $mysqli->error;
                    array_push($arrayData, array('status' => $valido, 'mensaje' => "La actualización fallo", "error" => $error, "idCampo" => $idTipoDescanso[$i], "campo" => $pausa[$i]));
                }
                $i = $i + 1;    
            }
            
        }
         
        echo json_encode($arrayData);
    }

    if (isset($_GET['cargarPausas']) && $_GET['cargarPausas'] == 'si') {
        $datosUsuPau1 = array();
        $datosUsuPau0 = array();
        $arrayTipoPausa = array();
        $idUsuario = obtenerIdUsuari($_POST['idUsuario']);
        if ($_POST['tipo'] == '1' && $_POST['idUsuario'] != '') {
            $Lsql = "SELECT * FROM {$BaseDatos_systema}.USUPAU WHERE USUPAU_ConsInte__USUARI_b = '{$idUsuario}' AND USUPAU_Tipo_b = '{$_POST['tipo']}' AND  USUPAU_PausasId_b  NOT IN('{$_POST['pausa1']}','{$_POST['pausa2']}','{$_POST['pausa3']}')";
            
            $result = $mysqli->query($Lsql);
            
            $j = 0;
            while ($key = $result->fetch_object()) {


                $datosUsuPau1[$j]['USUPAU_ConsInte__b'] = $key->USUPAU_ConsInte__b;
                $datosUsuPau1[$j]['USUPAU_PausasId_b'] = $key->USUPAU_PausasId_b;
                $datosUsuPau1[$j]['USUPAU_HorIniLun_b'] = $key->USUPAU_HorIniLun_b;
                $datosUsuPau1[$j]['USUPAU_HorFinLun_b'] = $key->USUPAU_HorFinLun_b;
                $datosUsuPau1[$j]['USUPAU_HorIniMar_b'] = $key->USUPAU_HorIniMar_b;
                $datosUsuPau1[$j]['USUPAU_HorFinMar_b'] = $key->USUPAU_HorFinMar_b;
                $datosUsuPau1[$j]['USUPAU_HorIniMie_b'] = $key->USUPAU_HorIniMie_b;
                $datosUsuPau1[$j]['USUPAU_HorFinMie_b'] = $key->USUPAU_HorFinMie_b;
                $datosUsuPau1[$j]['USUPAU_HorIniJue_b'] = $key->USUPAU_HorIniJue_b;
                $datosUsuPau1[$j]['USUPAU_HorFinJue_b'] = $key->USUPAU_HorFinJue_b;
                $datosUsuPau1[$j]['USUPAU_HorIniVie_b'] = $key->USUPAU_HorIniVie_b;
                $datosUsuPau1[$j]['USUPAU_HorFinVie_b'] = $key->USUPAU_HorFinVie_b;
                $datosUsuPau1[$j]['USUPAU_HorIniSab_b'] = $key->USUPAU_HorIniSab_b;
                $datosUsuPau1[$j]['USUPAU_HorFinSab_b'] = $key->USUPAU_HorFinSab_b;
                $datosUsuPau1[$j]['USUPAU_HorIniDom_b'] = $key->USUPAU_HorIniDom_b;
                $datosUsuPau1[$j]['USUPAU_HorFinDom_b'] = $key->USUPAU_HorFinDom_b;
                $datosUsuPau1[$j]['USUPAU_HorIniFes_b'] = $key->USUPAU_HorIniFes_b;
                $datosUsuPau1[$j]['USUPAU_HorFinFes_b'] = $key->USUPAU_HorFinFes_b;

                $j++;
            }
            $ZLsql = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = " . $_POST['tipo'] . " AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '" . $_SESSION['HUESPED'] . "') AND  id  NOT IN('" . $_POST['pausa1'] . "','" . $_POST['pausa2'] . "','" . $_POST['pausa3'] . "')";
        }

        if ($_POST['tipo'] == '0' && $_POST['idUsuario'] != '') {
            $Lsql = "SELECT * FROM {$BaseDatos_systema}.USUPAU WHERE MD5(CONCAT('" . clave_get . "', USUPAU_ConsInte__USUARI_b)) = '{$_POST['idUsuario']}' AND USUPAU_Tipo_b = '{$_POST['tipo']}'";
            // echo "Lsql=> {$Lsql}";
            $result = $mysqli->query($Lsql);
            $k = 0;
            while ($key = $result->fetch_object()) {

                $datosUsuPau0[$k]['USUPAU_ConsInte__b'] = $key->USUPAU_ConsInte__b;
                $datosUsuPau0[$k]['USUPAU_PausasId_b'] = $key->USUPAU_PausasId_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxLun_b'] = $key->USUPAU_DurMaxLun_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxLun_b'] = $key->USUPAU_CanMaxLun_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxMar_b'] = $key->USUPAU_DurMaxMar_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxMar_b'] = $key->USUPAU_CanMaxMar_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxMie_b'] = $key->USUPAU_DurMaxMie_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxMie_b'] = $key->USUPAU_CanMaxMie_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxJue_b'] = $key->USUPAU_DurMaxJue_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxJue_b'] = $key->USUPAU_CanMaxJue_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxVie_b'] = $key->USUPAU_DurMaxVie_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxVie_b'] = $key->USUPAU_CanMaxVie_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxSab_b'] = $key->USUPAU_DurMaxSab_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxSab_b'] = $key->USUPAU_CanMaxSab_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxDom_b'] = $key->USUPAU_DurMaxDom_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxDom_b'] = $key->USUPAU_CanMaxDom_b;
                $datosUsuPau0[$k]['USUPAU_DurMaxFes_b'] = $key->USUPAU_DurMaxFes_b;
                $datosUsuPau0[$k]['USUPAU_CanMaxFes_b'] = $key->USUPAU_CanMaxFes_b;

                $k++;
            }
            $ZLsql = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = " . $_POST['tipo'] . " AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '" . $_SESSION['HUESPED'] . "')";
        }


        $i = 0;
        if (($result = $mysqli->query($ZLsql)) == TRUE) {
            while ($key = $result->fetch_object()) {
                $cadena = $key->tipo;
                if (strpos($cadena, '-') !== false) {
                    $tipo = explode("-", $cadena);
                    $cadena = $tipo[1];
                }

                $arrayTipoPausa[$i]['id'] = $key->id;
                $arrayTipoPausa[$i]['tipo'] = $cadena;
                $i++;
            }
        }
        echo json_encode($arrayDatos = array('datosUsuPau1' =>  $datosUsuPau1, 'datosUsuPau0' =>  $datosUsuPau0, 'tipoPausas' => $arrayTipoPausa));
    }


    if (isset($_POST['callDatosNuevamente'])) {
        $inicio = $_POST['inicio'];
        $fin = $_POST['fin'];
        $B = "";

        if (isset($_POST["B"])) {
            $B = $_POST["B"];
        }

        $Zsql = "SELECT USUARI_ConsInte__b AS id, USUARI_Nombre____b AS camp1, USUARI_Correo___b AS camp2, USUARI_Bloqueado_b AS estado FROM {$BaseDatos_systema}.USUARI WHERE USUARI_ConsInte__PROYEC_b = {$_SESSION['HUESPED']} AND USUARI_Eliminado_b <> '-1' AND USUARI_ConsInte__PERUSU_b IS NULL ";
        if ($B != "") {
            $Zsql .= "AND (USUARI_Nombre____b LIKE('%{$B}%') OR USUARI_Correo___b LIKE('%{$B}%'))";
        }
        $Zsql .= "ORDER BY USUARI_Bloqueado_b DESC , USUARI_Nombre____b ASC LIMIT {$inicio}, {$fin}";

        $result = $mysqli->query($Zsql);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($obj = $result->fetch_object()) {
                $inactivo = '';
                if ($obj->estado == -1) {

                    $inactivo = 'INACTIVO';
                }
                echo "<tr class='CargarDatos' id='" . url::urlSegura($obj->id) . "'>
                            <td>
                                <p style='font-size:14px;'><b>{$obj->camp1}</b> <strong style='float:right; color:red'>{$inactivo}</strong></p>
                                <p style='font-size:12px; margin-top:-10px;'>{$obj->camp2}</p>
                            </td>
                        </tr>";
            }
        }
    }

    //Inserciones o actualizaciones
    if (isset($_GET['guardarDatos'])) {
        $idUsuario=0;
              
        if($_POST['oper'] != 'del'){
            
            //arreglos para pausas por defecto
            $arraybreakHorIniLun=$_POST['breakHorIniLun'];
            $arraybreakHorFinLun=$_POST['breakHorFinLun'];
            $arraybreakHorIniMar=$_POST['breakHorIniMar'];
            $arraybreakHorFinMar=$_POST['breakHorFinMar'];
            $arraybreakHorIniMie=$_POST['breakHorIniMie'];
            $arraybreakHorFinMie=$_POST['breakHorFinMie'];
            $arraybreakHorIniJue=$_POST['breakHorIniJue'];
            $arraybreakHorFinJue=$_POST['breakHorFinJue'];
            $arraybreakHorIniVie=$_POST['breakHorIniVie'];
            $arraybreakHorFinVie=$_POST['breakHorFinVie'];
            $arraybreakHorIniSab=$_POST['breakHorIniSab'];
            $arraybreakHorFinSab=$_POST['breakHorFinSab'];
            $arraybreakHorIniDom=$_POST['breakHorIniDom'];
            $arraybreakHorFinDom=$_POST['breakHorFinDom'];
            $arraybreakHorIniFes=$_POST['breakHorIniFes'];
            $arraybreakHorFinFes=$_POST['breakHorFinFes'];
            $arraybreakselect=$_POST['breakselect'];

            if($_POST['fechaFinContr'] == ''){
                $_POST['fechaFinContr'] = date('Y-m-d');
            }
        }

        if ($_POST['oper'] == "add" || $_POST['oper'] == "edit") {
            $mensajeValidar = validarMalla();
            if (isset($mensajeValidar['strMensaje'])){
                enviarMessages('error', null, $mensajeValidar);
                $valido = 1;
                return false;
            }

        }

     
        
        if ($_POST['oper'] == "add") {

            $imagenNombre = "";

            $ValidarLsql_Correo = "SELECT USUARI_Correo___b FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_Correo___b =  '" . trim($_POST['Correo']) . "'";
            $res_ValidarLsql_Correo = $mysqli->query($ValidarLsql_Correo);
            $valido = 0;
            if ($res_ValidarLsql_Correo->num_rows > 0) {
                enviarMessages(2);
                $valido = 1;
                return false;
            }

            $validarLsql_identificacion = "SELECT USUARI_Identific_b FROM  " . $BaseDatos_systema . ".USUARI WHERE USUARI_Identific_b =  '" . trim($_POST['IdentificacionUsuario']) . "'";
            $res_validarLsql_identificacion = $mysqli->query($validarLsql_identificacion);
            $valido = 0;
            if ($res_validarLsql_identificacion->num_rows > 0) {
                enviarMessages(4);
                $valido = 1;
                return false;
            }


            if(isset($_POST['idUsuPauTipo0'])  && isset($_POST['selectTipo0']) && isset($_POST['PDMLun']) && isset($_POST['PCLun']) && isset($_POST['PDMMar']) && isset($_POST['PCMar']) && isset($_POST['PDMMie']) && isset($_POST['PCMie']) && isset($_POST['PDMJue']) && isset($_POST['PCJue']) && isset($_POST['PDMVie']) && isset($_POST['PCVie']) && isset($_POST['PDMSab']) && isset($_POST['PCSab']) && isset($_POST['PDMDom']) && isset($_POST['PCDom']) && isset($_POST['PDMFes']) && isset($_POST['PCFes']) ){
                
                $arrayidUsuPauTipo0 = $_POST['idUsuPauTipo0'];
                $arrayselectTipo0 = $_POST['selectTipo0'];
                $arrayPDMLun = $_POST['PDMLun'];
                $arrayPCLun = $_POST['PCLun'];
                $arrayPDMMar = $_POST['PDMMar'];
                $arrayPCMar = $_POST['PCMar'];
                $arrayPDMMie = $_POST['PDMMie'];
                $arrayPCMie = $_POST['PCMie'];
                $arrayPDMJue = $_POST['PDMJue'];
                $arrayPCJue = $_POST['PCJue'];
                $arrayPDMVie = $_POST['PDMVie'];
                $arrayPCVie = $_POST['PCVie'];
                $arrayPDMSab = $_POST['PDMSab'];
                $arrayPCSab = $_POST['PCSab'];
                $arrayPDMDom = $_POST['PDMDom'];
                $arrayPCDom = $_POST['PCDom'];
                $arrayPDMFes = $_POST['PDMFes'];
                $arrayPCFes = $_POST['PCFes'];
    
                $k = 0;
                while($k < count($arrayPDMLun)){                    

                    $Lsql="UPDATE {$BaseDatos_systema}.USUPAU SET 
                    USUPAU_DurMaxLun_b = '".$arrayPDMLun[$k]."' , USUPAU_CanMaxLun_b = '".$arrayPCLun[$k]."',
                    USUPAU_DurMaxMar_b = '".$arrayPDMMar[$k]."' , USUPAU_CanMaxMar_b = '".$arrayPCMar[$k]."',
                    USUPAU_DurMaxMie_b = '".$arrayPDMMie[$k]."' , USUPAU_CanMaxMie_b = '".$arrayPCMie[$k]."',
                    USUPAU_DurMaxJue_b = '".$arrayPDMJue[$k]."' , USUPAU_CanMaxJue_b = '".$arrayPCJue[$k]."',
                    USUPAU_DurMaxVie_b = '".$arrayPDMVie[$k]."' , USUPAU_CanMaxVie_b = '".$arrayPCVie[$k]."',
                    USUPAU_DurMaxSab_b = '".$arrayPDMSab[$k]."' , USUPAU_CanMaxSab_b = '".$arrayPCSab[$k]."',
                    USUPAU_DurMaxDom_b = '".$arrayPDMDom[$k]."' , USUPAU_CanMaxDom_b = '".$arrayPCDom[$k]."',
                    USUPAU_DurMaxFes_b = '".$arrayPDMFes[$k]."' , USUPAU_CanMaxFes_b = '".$arrayPCFes[$k]."',
                    USUPAU_ConsInte__USUARI_b = '".$idUsuario."',USUPAU_PausasId_b='".$arrayselectTipo0[$k]."' WHERE USUPAU_ConsInte__b = ".$arrayidUsuPauTipo0[$k];
                    
                    // echo "Lsql => ", $Lsql;
                    mysqli_query($mysqli,$Lsql);
                        
                    $k=$k+1;            
                }
    
            }


            if ($valido == 0) {

                $contrasenha = crearPassword();
                /*YCR 2019-09-26
                *llenado de campos tabla usuari
                */
                if( $_POST['Cargo'] == 'agente' || $_POST['Cargo'] == 'supervision'){
                        $Lsql = "INSERT INTO " . $BaseDatos_systema . ".USUARI (USUARI_TipoContr_b,USUARI_FechIniContr_b,USUARI_FechFinContr_b,USUARI_Bloqueado_b,USUARI_Codigo____b, USUARI_Nombre____b,  USUARI_Identific_b, USUARI_Clave_____b, USUARI_Cargo_____b, USUARI_Correo___b, USUARI_ConsInte__PROYEC_b, USUARI_IndiActi__b,USUARI_HorIniLun_b,USUARI_HorFinLun_b,USUARI_HorIniMar_b,USUARI_HorFinMar_b,USUARI_HorIniMie_b,USUARI_HorFinMie_b,USUARI_HorIniJue_b,USUARI_HorFinJue_b,USUARI_HorIniVie_b,USUARI_HorFinVie_b,USUARI_HorIniSab_b,USUARI_HorFinSab_b,USUARI_HorIniDom_b,USUARI_HorFinDom_b,USUARI_HorIniFes_b,USUARI_HorFinFes_b, USUARI_Clave_Temp_____b, USUARI_FechUpdate_Clave______b )
                    VALUES ('".$_POST['tipoContrato'] ."','".$_POST['fechaIniContr'] ."','".$_POST['fechaFinContr'] ."','".$_POST['estadoUsuario'] ."','" . $_POST['CodigoUsuario'] . "' , '" . removeSpacesCharacters($_POST['NombreUsuario']) . "' , '" . trim($_POST['IdentificacionUsuario']) . "'  , '" . encriptaPassword($contrasenha) . "', '" . $_POST['Cargo'] . "' , '" . trim($_POST['Correo']) . "', " . $_SESSION['HUESPED'] . ", '-1','".$_POST['HorIniLun']."','".$_POST['HorFinLun']."','".$_POST['HorIniMar']."','".$_POST['HorFinMar']."','".$_POST['HorIniMie']."','".$_POST['HorFinMie']."','".$_POST['HorIniJue']."','".$_POST['HorFinJue']."','".$_POST['HorIniVie']."','".$_POST['HorFinVie']."','".$_POST['HorIniSab']."','".$_POST['HorFinSab']."','".$_POST['HorIniDom']."','".$_POST['HorFinDom']."','".$_POST['HorIniFes']."','".$_POST['HorFinFes']."', '-1', NOW())";
                }else{
                   $Lsql = "INSERT INTO " . $BaseDatos_systema . ".USUARI (USUARI_TipoContr_b,USUARI_FechIniContr_b,USUARI_FechFinContr_b,USUARI_Bloqueado_b,USUARI_Codigo____b, USUARI_Nombre____b,  USUARI_Identific_b, USUARI_Clave_____b, USUARI_Cargo_____b, USUARI_Correo___b, USUARI_ConsInte__PROYEC_b, USUARI_IndiActi__b, USUARI_Clave_Temp_____b, USUARI_FechUpdate_Clave______b)
                    VALUES ('".$_POST['tipoContrato'] ."','".$_POST['fechaIniContr'] ."','".$_POST['fechaFinContr'] ."','".$_POST['estadoUsuario'] ."','" . $_POST['CodigoUsuario'] . "' , '" . removeSpacesCharacters($_POST['NombreUsuario']) . "' , '" . trim($_POST['IdentificacionUsuario']) . "'  , '" . encriptaPassword($contrasenha) . "', '" . $_POST['Cargo'] . "' , '" . trim($_POST['Correo']) . "', " . $_SESSION['HUESPED'] . ", '-1', '-1', NOW() )"; 
                }
                

                if ($mysqli->query($Lsql) === TRUE) {
                    $id_Usuario_Nuevo=$mysqli->insert_id;
                    $idUsuario = $id_Usuario_Nuevo;            
                
                    if (!isset($_POST['backofice'])) {
                        
                        $respuesta = usuarioPersistir($_POST['NombreUsuario'], null, $_POST['Correo'], trim($_POST['IdentificacionUsuario']), $contrasenha, true, $_SESSION['HUESPED'], -1);

                        if (!empty($respuesta) && !is_null($respuesta)) {
                            
                            $json = json_decode($respuesta);
                            if ($json->strEstado_t == "ok") {
                                $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_UsuaCBX___b = " . $json->intIdCreacion_t . " WHERE USUARI_ConsInte__b = " . $id_Usuario_Nuevo;
                                $mysqli->query($Lsql);
                                
                                $response = sendMailPassword($_POST['Correo'], $_POST['NombreUsuario'], $contrasenha);
                                $jsonEmail = json_decode($response, true);

                                $data = [];
                                if (!empty($response) && !is_null($response)) {
                                    $data = array(
                                        "strEstado" => $jsonEmail['strEstado_t'],
                                        "strMensaje" => $jsonEmail['strMensaje_t']
                                    );
                                }
                                

                                /* Insertar el usuario en el huesped */
                                $str_insertHuespedSql = "INSERT INTO " . $BaseDatos_general . ".huespedes_usuarios(id_huesped, id_usuario) VALUES(" . $_SESSION['HUESPED'] . ", " . $json->intIdCreacion_t . ");";
                                $mysqli->query($str_insertHuespedSql);

                                $ver = (float) phpversion();
                                if ($ver > 7.0) {
                                    if (isset($_FILES['inpFotoPerfil']['tmp_name']) && !empty($_FILES['inpFotoPerfil']['tmp_name'])) {
                                        $filetype=$_FILES['inpFotoPerfil']['type'];
                                        if($filetype == "image/jpeg"){
                                            $extencion = explode('.', basename($_FILES['inpFotoPerfil']['name']));
                                            $target_path = '/var/../Dyalogo/img_usuarios/usr' . $json->intIdCreacion_t . '.jpg';
                                            $target_path = str_replace(' ', '', $target_path);

                                            $nuevoAncho = 500;
                                            $nuevoAlto = 500;
                                            list($ancho, $alto) = getimagesize($_FILES['inpFotoPerfil']['tmp_name']);
                                            $origen = imagecreatefromjpeg($_FILES['inpFotoPerfil']['tmp_name']);

                                            //le decimos que vamos acrear una imagen en el destino con esos ancho y alto
                                            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                                            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                                            imagejpeg($destino, $target_path);
                                            $imagenNombre = $json->intIdCreacion_t . '.jpg';
                                            $imagenNombre = str_replace(' ', '', $imagenNombre);

                                            $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_Foto______b = '" . $imagenNombre . "' WHERE USUARI_ConsInte__b = " . $id_Usuario_Nuevo;
                                            $mysqli->query($Lsql);
                                        }else{
                                            
                                        }
                                    }
                                } else {
                                    if (isset($_FILES['inpFotoPerfil']['tmp_name']) && !empty($_FILES['inpFotoPerfil']['tmp_name'])) {
                                        $filetype=$_FILES['inpFotoPerfil']['type'];
                                        if($filetype == "image/jpeg"){
                                            $extencion = explode('.', basename($_FILES['inpFotoPerfil']['name']));
                                            $target_path = '/var/../Dyalogo/img_usuarios/usr' . $json->intIdCreacion_t . '.jpg';
                                            $target_path = str_replace(' ', '', $target_path);

                                            copy($_FILES['inpFotoPerfil']['tmp_name'], $target_path);
                                            $imagenNombre = $json->intIdCreacion_t . '.jpg';
                                            $imagenNombre = str_replace(' ', '', $imagenNombre);

                                            $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_Foto______b = '" . $imagenNombre . "' WHERE USUARI_ConsInte__b = " . $id_Usuario_Nuevo;
                                            $mysqli->query($Lsql);
                                        }else{
                                            
                                        }
                                    }
                                }
                            } else {
                                json_encode($respuesta);    
                            }
                        } else {
                            json_encode($respuesta);
                        }
                    }

                    guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN USUARI");
                    $mensaje = array('strMensaje' => $data['strMensaje'], "strEstado" => $data['strEstado']);
                    enviarMessages(1, $id_Usuario_Nuevo, $mensaje);

                } else {
                    $mensaje = array('strMensaje' => 'Ha ocurrido un error al registrar el usuario', "strEstado" => $data['strEstado']);
                    enviarMessages('error', null, $mensajeValidar);
                }
            } else {
                echo json_encode(array('code' => 4, 'message' => 'Es necesaria la imagen del agente'));
            }
        } else if ($_POST['oper'] == "edit") {

            /* Validación Campo USUARIO */
            $id_usuari = obtenerIdUsuari($_POST['id']);
            $ValidarLsql_Correo = "SELECT USUARI_Correo___b FROM " . $BaseDatos_systema . ".USUARI WHERE md5(concat('" . clave_get . "', USUARI_ConsInte__b)) = '" . $_POST['id'] . "'";
            $res_ValidarLsql_Correo = $mysqli->query($ValidarLsql_Correo);
            $valido = 0;
            if ($res_ValidarLsql_Correo->num_rows > 0) {
                $datos = $res_ValidarLsql_Correo->fetch_array();
                if ($datos['USUARI_Correo___b'] != trim($_POST['Correo'])) {
                    $ValidarLsql_Correo = "SELECT USUARI_Correo___b FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_Correo___b =  '" . trim($_POST['Correo']) . "'";
                    $res_ValidarLsql_Correo = $mysqli->query($ValidarLsql_Correo);
                    if ($res_ValidarLsql_Correo->num_rows > 0) {
                        enviarMessages(2);
                        $valido = 1;
                        return false;
                    }
                }
            }

            $ValidarLsql_Identificacion = "SELECT USUARI_Identific_b FROM " . $BaseDatos_systema . ".USUARI WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '".$_POST['id']."'";
            $res_ValidarLsql_Identificacion = $mysqli->query($ValidarLsql_Identificacion);
            $valido = 0;
            if ($res_ValidarLsql_Identificacion->num_rows > 0) {
                $datos = $res_ValidarLsql_Identificacion->fetch_array();
                if ($datos['USUARI_Identific_b'] != trim($_POST['IdentificacionUsuario'])) {
                    $ValidarLsql_Identificacion = "SELECT USUARI_Identific_b FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_Identific_b =  '" . trim($_POST['IdentificacionUsuario']) . "'";
                    $res_ValidarLsql_Identificacion = $mysqli->query($ValidarLsql_Identificacion);
                    if ($res_ValidarLsql_Identificacion->num_rows > 0) {
                        enviarMessages(4);
                        $valido = 1;
                        return false;
                    }
                }
            }


            if ($valido == 0) {
                $contrasenha = $_POST['passwordActual'];
                if (isset($_POST['txtPassword']) && $_POST['txtPassword'] != '') {
                    $contrasenha = encriptaPassword($_POST['txtPassword']);
                }

                if($_POST['Cargo'] == 'agente' || $_POST['Cargo'] == 'supervision'){
                    $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_TipoContr_b = '" . $_POST['tipoContrato'] . "',USUARI_FechIniContr_b = '" . $_POST['fechaIniContr'] . "' , USUARI_FechFinContr_b = '" . $_POST['fechaFinContr'] . "' , USUARI_Bloqueado_b ='" . $_POST['estadoUsuario'] . "' , USUARI_Codigo____b = '" . $_POST['CodigoUsuario'] . "' , USUARI_Nombre____b ='" . removeSpacesCharacters($_POST['NombreUsuario']) . "' , USUARI_Identific_b = '" . trim($_POST['IdentificacionUsuario']) . "' , USUARI_Cargo_____b = '" . $_POST['Cargo'] . "', USUARI_Correo___b = '" . trim($_POST['Correo']) . "', USUARI_IndiActi__b = '-1', USUARI_HorIniLun_b = '" . $_POST['HorIniLun'] . "' , USUARI_HorFinLun_b = '" . $_POST['HorFinLun'] . "' ,USUARI_HorIniMar_b = '" . $_POST['HorIniMar'] . "' , USUARI_HorFinMar_b = '" . $_POST['HorFinMar'] . "' , USUARI_HorIniMie_b = '" . $_POST['HorIniMie'] . "' , USUARI_HorFinMie_b = '" . $_POST['HorFinMie'] . "' , USUARI_HorIniJue_b = '" . $_POST['HorIniJue'] . "' , USUARI_HorFinJue_b = '" . $_POST['HorFinJue'] . "' , USUARI_HorIniVie_b = '" . $_POST['HorIniVie'] . "' , USUARI_HorFinVie_b = '" . $_POST['HorFinVie'] . "' , USUARI_HorIniSab_b = '" . $_POST['HorIniSab'] . "' , USUARI_HorFinSab_b = '" . $_POST['HorFinSab'] . "' , USUARI_HorIniDom_b = '" . $_POST['HorIniDom'] . "' , USUARI_HorFinDom_b = '" . $_POST['HorFinDom'] . "', USUARI_HorIniFes_b = '" . $_POST['HorIniFes'] . "' , USUARI_HorFinFes_b = '" . $_POST['HorFinFes'] . "' WHERE md5(concat('" . clave_get . "', USUARI_ConsInte__b)) = '" . $_POST['id'] . "'";
                    if (isset($_POST['idUsuPauTipo0'])  && isset($_POST['selectTipo0']) && isset($_POST['PDMLun']) && isset($_POST['PCLun']) && isset($_POST['PDMMar']) && isset($_POST['PCMar']) && isset($_POST['PDMMie']) && isset($_POST['PCMie']) && isset($_POST['PDMJue']) && isset($_POST['PCJue']) && isset($_POST['PDMVie']) && isset($_POST['PCVie']) && isset($_POST['PDMSab']) && isset($_POST['PCSab']) && isset($_POST['PDMDom']) && isset($_POST['PCDom']) && isset($_POST['PDMFes']) && isset($_POST['PCFes'])) {

                        $arrayidUsuPauTipo0 = $_POST['idUsuPauTipo0'];
                        $arrayselectTipo0 = $_POST['selectTipo0'];
                        $arrayPDMLun = $_POST['PDMLun'];
                        $arrayPCLun = $_POST['PCLun'];
                        $arrayPDMMar = $_POST['PDMMar'];
                        $arrayPCMar = $_POST['PCMar'];
                        $arrayPDMMie = $_POST['PDMMie'];
                        $arrayPCMie = $_POST['PCMie'];
                        $arrayPDMJue = $_POST['PDMJue'];
                        $arrayPCJue = $_POST['PCJue'];
                        $arrayPDMVie = $_POST['PDMVie'];
                        $arrayPCVie = $_POST['PCVie'];
                        $arrayPDMSab = $_POST['PDMSab'];
                        $arrayPCSab = $_POST['PCSab'];
                        $arrayPDMDom = $_POST['PDMDom'];
                        $arrayPCDom = $_POST['PCDom'];
                        $arrayPDMFes = $_POST['PDMFes'];
                        $arrayPCFes = $_POST['PCFes'];

                        $k = 0;
                        while ($k < count($arrayPDMLun)) {

                            $sql = "UPDATE {$BaseDatos_systema}.USUPAU SET 
                            USUPAU_DurMaxLun_b = '{$arrayPDMLun[$k]}', USUPAU_CanMaxLun_b = '{$arrayPCLun[$k]}', USUPAU_DurMaxMar_b = '{$arrayPDMMar[$k]}', USUPAU_CanMaxMar_b = '{$arrayPCMar[$k]}', USUPAU_DurMaxMie_b = '{$arrayPDMMie[$k]}', USUPAU_CanMaxMie_b = '{$arrayPCMie[$k]}', USUPAU_DurMaxJue_b = '{$arrayPDMJue[$k]}', USUPAU_CanMaxJue_b = '{$arrayPCJue[$k]}', USUPAU_DurMaxVie_b = '{$arrayPDMVie[$k]}', USUPAU_CanMaxVie_b = '{$arrayPCVie[$k]}',USUPAU_DurMaxSab_b = '{$arrayPDMSab[$k]}', USUPAU_CanMaxSab_b = '{$arrayPCSab[$k]}', USUPAU_DurMaxDom_b = '{$arrayPDMDom[$k]}' , USUPAU_CanMaxDom_b = '{$arrayPCDom[$k]}', USUPAU_DurMaxFes_b = '{$arrayPDMFes[$k]}', USUPAU_CanMaxFes_b = '{$arrayPCFes[$k]}', USUPAU_ConsInte__USUARI_b = '{$id_usuari}',USUPAU_PausasId_b='{$arrayselectTipo0[$k]}' WHERE USUPAU_ConsInte__b = '{$arrayidUsuPauTipo0[$k]}'";

                            // echo "Lsql => ", $Lsql;
                            mysqli_query($mysqli,$sql);

                            $k = $k + 1;
                        }
                    }

                    //OTRAS PAUSAS CON HORARIO
                    if (isset($_POST['idUsuPauTipo1']) && isset($_POST['selectTipo1']) && isset($_POST['PHorIniLun']) && isset($_POST['PHorFinLun']) && isset($_POST['PHorIniMar']) && isset($_POST['PHorFinMar']) && isset($_POST['PHorIniMie']) && isset($_POST['PHorFinMie']) && isset($_POST['PHorIniJue']) && isset($_POST['PHorFinJue']) && isset($_POST['PHorIniVie']) && isset($_POST['PHorFinVie']) && isset($_POST['PHorIniSab']) && isset($_POST['PHorFinSab']) && isset($_POST['PHorIniDom']) && isset($_POST['PHorFinDom']) && isset($_POST['PHorIniFes']) && isset($_POST['PHorFinFes'])) {

                        $arrayidUsuPauTipo1 = $_POST['idUsuPauTipo1'];
                        $arrayselectTipo1 = $_POST['selectTipo1'];
                        $arrayPHorIniLun = $_POST['PHorIniLun'];
                        $arrayPHorFinLun = $_POST['PHorFinLun'];
                        $arrayPHorIniMar = $_POST['PHorIniMar'];
                        $arrayPHorFinMar = $_POST['PHorFinMar'];
                        $arrayPHorIniMie = $_POST['PHorIniMie'];
                        $arrayPHorFinMie = $_POST['PHorFinMie'];
                        $arrayPHorIniJue = $_POST['PHorIniJue'];
                        $arrayPHorFinJue = $_POST['PHorFinJue'];
                        $arrayPHorIniVie = $_POST['PHorIniVie'];
                        $arrayPHorFinVie = $_POST['PHorFinVie'];
                        $arrayPHorIniSab = $_POST['PHorIniSab'];
                        $arrayPHorFinSab = $_POST['PHorFinSab'];
                        $arrayPHorIniDom = $_POST['PHorIniDom'];
                        $arrayPHorFinDom = $_POST['PHorFinDom'];
                        $arrayPHorIniFes = $_POST['PHorIniFes'];
                        $arrayPHorFinFes = $_POST['PHorFinFes'];


                        $j = 0;
                        while ($j < count($arrayPHorIniLun)) {

                            $consulta = "UPDATE {$BaseDatos_systema}.USUPAU SET 
                                USUPAU_HorIniLun_b = '{$arrayPHorIniLun[$j]}' ,USUPAU_HorFinLun_b = '{$arrayPHorFinLun[$j]}',
                                USUPAU_HorIniMar_b = '{$arrayPHorIniMar[$j]}' ,USUPAU_HorFinMar_b = '{$arrayPHorFinMar[$j]}',
                                USUPAU_HorIniMie_b = '{$arrayPHorIniMie[$j]}' ,USUPAU_HorFinMie_b = '{$arrayPHorFinMie[$j]}',
                                USUPAU_HorIniJue_b = '{$arrayPHorIniJue[$j]}' ,USUPAU_HorFinJue_b = '{$arrayPHorFinJue[$j]}',
                                USUPAU_HorIniVie_b = '{$arrayPHorIniVie[$j]}' ,USUPAU_HorFinVie_b = '{$arrayPHorFinVie[$j]}',
                                USUPAU_HorIniSab_b = '{$arrayPHorIniSab[$j]}' ,USUPAU_HorFinSab_b = '{$arrayPHorFinSab[$j]}',
                                USUPAU_HorIniDom_b = '{$arrayPHorIniDom[$j]}' ,USUPAU_HorFinDom_b = '{$arrayPHorFinDom[$j]}',
                                USUPAU_HorIniFes_b = '{$arrayPHorIniFes[$j]}' ,USUPAU_HorFinFes_b = '{$arrayPHorFinFes[$j]}',
                                USUPAU_ConsInte__USUARI_b = '{$id_usuari}',  USUPAU_PausasId_b='{$arrayselectTipo1[$j]}'
                                WHERE USUPAU_ConsInte__b = {$arrayidUsuPauTipo1[$j]}";

                                // echo "<br> consulta => ", $consulta, "<br>";
                                mysqli_query($mysqli,$consulta);
                                
                            $j = $j + 1;
                        }
                    }

                }else{
                    $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_TipoContr_b = '".$_POST['tipoContrato']."',USUARI_FechIniContr_b = '".$_POST['fechaIniContr']."' , USUARI_FechFinContr_b = '".$_POST['fechaFinContr']."' ,USUARI_Bloqueado_b ='".$_POST['estadoUsuario']."' , USUARI_Codigo____b = '" . $_POST['CodigoUsuario'] . "' , USUARI_Nombre____b ='" . removeSpacesCharacters($_POST['NombreUsuario']) . "' , USUARI_Identific_b = '" . trim($_POST['IdentificacionUsuario']) . "' , USUARI_Cargo_____b = '" . $_POST['Cargo'] . "', USUARI_Correo___b = '" . trim($_POST['Correo']) . "', USUARI_IndiActi__b = '-1'  WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '".$_POST['id']."'";
                }

                if ($mysqli->query($Lsql) === TRUE) {
                    $XLsql = "SELECT USUARI_ConsInte__b as id,USUARI_UsuaCBX___b FROM " . $BaseDatos_systema . ".USUARI WHERE md5(concat('" . clave_get . "', USUARI_ConsInte__b)) = '" . $_POST['id'] . "'";
                    $res_XLsql = $mysqli->query($XLsql);
                    $datos = $res_XLsql->fetch_array();
                    $idUsuario = $datos['id'];

                    if (isset($_POST['txtPassword']) && $_POST['txtPassword'] != '') {
                        $contrasenha = encriptaPassword($_POST['txtPassword']);
                    }

                    if (!isset($_POST['backofice'])) {
                        //aqui hago el proceso del CBX
                        $respuesta = usuarioPersistir($_POST['NombreUsuario'], null, $_POST['Correo'], trim($_POST['IdentificacionUsuario']), $contrasenha, false, $_SESSION['HUESPED'], $datos['USUARI_UsuaCBX___b']);
                        $json = json_decode($respuesta, true);
                        // var_dump("xxx", $json);
                        // echo "<br>", json_encode($json['strEstado_t']), "<br>" ;
                        // echo "<br>", json_encode($json['strMensaje_t']), "<br>" ;
                        $mensaje = ['strMensaje' => 'Registro actualizado', 'strEstado' => "ok"];

                        if (!empty($respuesta) && !is_null($respuesta)) {

                            if ($json['strEstado_t'] == "ok") {
                                $Lsql = "UPDATE {$BaseDatos_systema}.USUARI SET USUARI_UsuaCBX___b = {$json['intIdCreacion_t']} WHERE md5(concat('" . clave_get . "', USUARI_ConsInte__b)) = '{$_POST['id']}'";
                                $mysqli->query($Lsql);

                                $ver = (float) phpversion();
                                if ($ver > 7.0) {
                                    if (isset($_FILES['inpFotoPerfil']['tmp_name']) && !empty($_FILES['inpFotoPerfil']['tmp_name'])) {
                                        $filetype = $_FILES['inpFotoPerfil']['type'];
                                        if ($filetype == "image/jpeg") {
                                            $extencion = explode('.', basename($_FILES['inpFotoPerfil']['name']));
                                            $target_path = '/var/../Dyalogo/img_usuarios/usr' . $json['intIdCreacion_t'] . '.jpg';
                                            $target_path = str_replace(' ', '', $target_path);

                                            if (file_exists($target_path)) {
                                                unlink($target_path);
                                            }

                                            $nuevoAncho = 500;
                                            $nuevoAlto = 500;
                                            list($ancho, $alto) = getimagesize($_FILES['inpFotoPerfil']['tmp_name']);
                                            $origen = imagecreatefromjpeg($_FILES['inpFotoPerfil']['tmp_name']);
                                            //le decimos que vamos acrear una imagen en el destino con esos ancho y alto
                                            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                                            //cortamos esta imagen
                                            /* imagecopyresized();
                                              1- destino
                                              2- Origen
                                              3- donde se inicia el corte en el EjeX destino
                                              4- donde se inicia el corte en el ejeY destino
                                              5- donde se inicia el corte en el ejeX Origen
                                              6- donde se inicia el corte en el ejeY Origen
                                              7- nuevo ancho
                                              8- nuebp alto
                                              9- ancho original
                                              10- alto original
                                             */

                                            imagecopyresized(
                                                $destino,
                                                $origen,
                                                0,
                                                0,
                                                0,
                                                0,
                                                $nuevoAncho,
                                                $nuevoAlto,
                                                $ancho,
                                                $alto
                                            );
                                            imagejpeg($destino, $target_path);
                                            $imagenNombre = $json['intIdCreacion_t'] . '.jpg?foto='. rand(10, 9999) ;
                                            $imagenNombre = str_replace(' ', '', $imagenNombre);

                                            $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_Foto______b = '" . $imagenNombre . "' WHERE md5(concat('" . clave_get . "', USUARI_ConsInte__b)) = '" . $_POST['id'] . "'";
                                            if($mysqli->query($Lsql) && $_POST["id_usuari"] == $_SESSION['IDENTIFICACION']){
                                                $imagenUser = "/manager/assets/img/Kakashi.fw.png";
                                                if (file_exists("/var/../Dyalogo/img_usuarios/usr" . $json['intIdCreacion_t'] . ".jpg")) {
                                                    $imagenUser = "/DyalogoImagenes/usr" . $imagenNombre;
                                                }
                                                $_SESSION['IMAGEN'] = $imagenUser;
                                            }
                                        }else {

                                        }
                                    }
                                }else {

                                    if (isset($_FILES['inpFotoPerfil']['tmp_name']) && !empty($_FILES['inpFotoPerfil']['tmp_name'])) {
                                        $filetype = $_FILES['inpFotoPerfil']['type'];
                                        if ($filetype == "image/jpeg") {
                                            $extencion = explode('.', basename($_FILES['inpFotoPerfil']['name']));
                                            $target_path = '/var/../Dyalogo/img_usuarios/usr' . $json['intIdCreacion_t'] . '.jpg';
                                            $target_path = str_replace(' ', '', $target_path);

                                            if (file_exists($target_path)) {
                                                unlink($target_path);
                                            }

                                            copy($_FILES['inpFotoPerfil']['tmp_name'], $target_path);
                                            $imagenNombre = $json['intIdCreacion_t'] . '.jpg?foto='.rand(10,9999);
                                            $imagenNombre = str_replace(' ', '', $imagenNombre);

                                            $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_Foto______b = '" . $imagenNombre . "' WHERE md5(concat('" . clave_get . "', USUARI_ConsInte__b)) = '" . $id_Usuario_Nuevo . "'";
                                            if($mysqli->query($Lsql) && $_POST["id_usuari"] == $_SESSION['IDENTIFICACION']){
                                                $imagenUser = "assets/img/Kakashi.fw.png";
                                                if (file_exists("/var/../Dyalogo/img_usuarios/usr" . $json['intIdCreacion_t'] . ".jpg")) {
                                                    $imagenUser = "/DyalogoImagenes/usr" . $imagenNombre;
                                                }
                                                $_SESSION['IMAGEN'] = $imagenUser;
                                            }
                                        } else {
                                        }
                                    }
                                }
                            } else {
                                // echo "<br> fallo else ", json_encode($json['strEstado_t']), "<br>";
                                $mensaje = ['strMensaje' => $json['strMensaje_t'], 'strEstado' => $json['strEstado_t']];
                            }
                        } else {
                            // echo "<br> fallo else <br>";
                            $mensaje = ['strMensaje' => $json['strMensaje_t'], 'strEstado' => $json['strEstado_t']];
                        }
                    }

                    guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # " . $idUsuario . " EN USUARI");
                    enviarMessages(1, $idUsuario, $mensaje);

                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        } else if ($_POST['oper'] == "del") {
            

            $Lsql = "SELECT USUARI_ConsInte__b as id,USUARI_UsuaCBX___b FROM  " . $BaseDatos_systema . ".USUARI WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '".$_POST['id']."'";
            $t = $mysqli->query($Lsql);
            $to = $t->fetch_array();

            guardar_auditoria("ELIMINAR", "USUARIO ID " . $to['id'] . " CBX: " . $to['USUARI_UsuaCBX___b']);

            $LsqlDeleAsitar = "DELETE FROM " . $BaseDatos_systema . ".ASITAR WHERE md5(concat('".clave_get."', ASITAR_ConsInte__USUARI_b)) = '".$_POST['id']."'";

            echo $mysqli->query($LsqlDeleAsitar) === true ? "is true!": $mysqli->error;
            
            $LsqlDeleHuesped="DELETE FROM dyalogo_general.huespedes_usuarios WHERE id_usuario = " . $to['USUARI_UsuaCBX___b'];
            
            echo $mysqli->query($LsqlDeleHuesped) === true ? "is true!": $mysqli->error;

            $LsqlDeledyagentes="UPDATE dyalogo_telefonia.dy_agentes SET identificacion = NULL, nombre = CONCAT(nombre,'(Eliminado)'), email = NULL,contrasena = NULL WHERE id_usuario_asociado = ". $to['USUARI_UsuaCBX___b'];
            
            echo $mysqli->query($LsqlDeledyagentes) === true ? "is true!": $mysqli->error;
            
            $LsqlDeledyusuarios="UPDATE dyalogo_telefonia.dy_usuarios SET usuario=NULL, nombre=CONCAT(nombre,'(Eliminado)'),email=NULL,contrasena=NULL,identificacion=NULL WHERE id =". $to['USUARI_UsuaCBX___b'];
            
            echo $mysqli->query($LsqlDeledyusuarios) === true ? "is true!": $mysqli->error;            
            
            $LsqlDelete = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_Codigo____b=NULL, USUARI_Correo___b=NULL,USUARI_IndiActi__b=0,USUARI_Eliminado_b=-1,USUARI_Bloqueado_b=-1,USUARI_Foto______b=NULL,USUARI_Correo___b=NULL,USUARI_Identific_b=NULL WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '".$_POST['id']."'";
            
            if ($mysqli->query($LsqlDelete) === true) {
                /*$data = array(
                    "strUsuario_t" => 'crm',
                    "strToken_t" => 'D43dasd321',
                    "intIdUsuario_t" => $to['USUARI_UsuaCBX___b']
                );
                $data_string = json_encode($data);
                echo $data_strings;
                $ch = curl_init($Api_Gestion . 'dyalogocore/api/usuarios/eliminar');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
                );
                $respuesta = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);
                echo " Respuesta => ".$respuesta;
                echo " Error => ".$error;
                if (!empty($respuesta) && !is_null($respuesta)) {
                    $json = json_decode($respuesta);
                    if ($json->strEstado_t == "ok") {
                        $target_path = '/var/../Dyalogo/img_usuarios/usr' . $to['USUARI_UsuaCBX___b'] . '.jpg';
                        $target_path = str_replace(' ', '', $target_path);

                        if (file_exists($target_path)) {
                            unlink($target_path);
                        }
                    }
                }*/
              echo json_encode(array('code' => 1, 'message' => 'Exito! operación finalizada'));

            } else {
                echo $mysqli->error;
            }
        }
        if($_POST['oper'] != 'del'){
            if( $_POST['Cargo'] == 'agente' || $_POST['Cargo'] == 'supervision' ){
            $i = 0;
            while($i < count($arraybreakHorIniLun)){  
                $Lsql = "SELECT * FROM ".$BaseDatos_systema.".USUPAU WHERE USUPAU_ConsInte__USUARI_b = '".$idUsuario."' AND USUPAU_PausasId_b = '".$arraybreakselect[$i]."'";
                if(($result = $mysqli->query($Lsql)) == TRUE){
                    if($result->num_rows > 0){

                        $Lsql="UPDATE ".$BaseDatos_systema.".USUPAU SET 
                        USUPAU_HorIniLun_b = '".$arraybreakHorIniLun[$i]."' ,USUPAU_HorFinLun_b = '".$arraybreakHorFinLun[$i]."',
                        USUPAU_HorIniMar_b = '".$arraybreakHorIniMar[$i]."' ,USUPAU_HorFinMar_b = '".$arraybreakHorFinMar[$i]."',
                        USUPAU_HorIniMie_b = '".$arraybreakHorIniMie[$i]."' ,USUPAU_HorFinMie_b = '".$arraybreakHorFinMie[$i]."',
                        USUPAU_HorIniJue_b = '".$arraybreakHorIniJue[$i]."' ,USUPAU_HorFinJue_b = '".$arraybreakHorFinJue[$i]."',
                        USUPAU_HorIniVie_b = '".$arraybreakHorIniVie[$i]."' ,USUPAU_HorFinVie_b = '".$arraybreakHorFinVie[$i]."',
                        USUPAU_HorIniSab_b = '".$arraybreakHorIniSab[$i]."' ,USUPAU_HorFinSab_b = '".$arraybreakHorFinSab[$i]."',
                        USUPAU_HorIniDom_b = '".$arraybreakHorIniDom[$i]."' ,USUPAU_HorFinDom_b = '".$arraybreakHorFinDom[$i]."',
                        USUPAU_HorIniFes_b = '".$arraybreakHorIniFes[$i]."' ,USUPAU_HorFinFes_b = '".$arraybreakHorFinFes[$i]."',
                        USUPAU_ConsInte__USUARI_b = '".$idUsuario."',  USUPAU_PausasId_b='".$arraybreakselect[$i]."', USUPAU_Tipo_b = '1' 
                        WHERE  USUPAU_ConsInte__USUARI_b = '".$idUsuario."'  AND  USUPAU_PausasId_b = '".$arraybreakselect[$i]."'";

                    }else{
                        $Lsql="INSERT INTO  ".$BaseDatos_systema.".USUPAU  (USUPAU_ConsInte__USUARI_b,USUPAU_PausasId_b,USUPAU_Tipo_b,USUPAU_HorIniLun_b,USUPAU_HorFinLun_b,USUPAU_HorIniMar_b,USUPAU_HorFinMar_b,USUPAU_HorIniMie_b,USUPAU_HorFinMie_b,USUPAU_HorIniJue_b,USUPAU_HorFinJue_b,USUPAU_HorIniVie_b,USUPAU_HorFinVie_b,USUPAU_HorIniSab_b,USUPAU_HorFinSab_b,USUPAU_HorIniDom_b,USUPAU_HorFinDom_b,USUPAU_HorIniFes_b,USUPAU_HorFinFes_b) values('".$idUsuario."','".$arraybreakselect[$i]."','1','".$arraybreakHorIniLun[$i]."','".$arraybreakHorFinLun[$i]."','".$arraybreakHorIniMar[$i]."','".$arraybreakHorFinMar[$i]."','".$arraybreakHorIniMie[$i]."','".$arraybreakHorFinMie[$i]."','".$arraybreakHorIniJue[$i]."','".$arraybreakHorFinJue[$i]."','".$arraybreakHorIniVie[$i]."','".$arraybreakHorFinVie[$i]."','".$arraybreakHorIniSab[$i]."','".$arraybreakHorFinSab[$i]."','".$arraybreakHorIniDom[$i]."','".$arraybreakHorFinDom[$i]."','".$arraybreakHorIniFes[$i]."','".$arraybreakHorFinFes[$i]."') " ; 
                    }
                    $mysqli->query($Lsql);
                }                                            
                $i=$i+1;            
           }
                if (isset($_POST['idUsuPauTipo1']) && isset($_POST['selectTipo1']) && isset($_POST['PHorIniLun']) && isset($_POST['PHorFinLun']) && isset($_POST['PHorIniMar']) && isset($_POST['PHorFinMar']) && isset($_POST['PHorIniMie']) && isset($_POST['PHorFinMie']) && isset($_POST['PHorIniJue']) && isset($_POST['PHorFinJue']) && isset($_POST['PHorIniVie']) && isset($_POST['PHorFinVie']) && isset($_POST['PHorIniSab']) && isset($_POST['PHorFinSab']) && isset($_POST['PHorIniDom']) && isset($_POST['PHorFinDom']) && isset($_POST['PHorIniFes']) && isset($_POST['PHorFinFes'])) {

                    $arrayidUsuPauTipo1 = $_POST['idUsuPauTipo1'];
                    $arrayselectTipo1 = $_POST['selectTipo1'];
                    $arrayPHorIniLun = $_POST['PHorIniLun'];
                    $arrayPHorFinLun = $_POST['PHorFinLun'];
                    $arrayPHorIniMar = $_POST['PHorIniMar'];
                    $arrayPHorFinMar = $_POST['PHorFinMar'];
                    $arrayPHorIniMie = $_POST['PHorIniMie'];
                    $arrayPHorFinMie = $_POST['PHorFinMie'];
                    $arrayPHorIniJue = $_POST['PHorIniJue'];
                    $arrayPHorFinJue = $_POST['PHorFinJue'];
                    $arrayPHorIniVie = $_POST['PHorIniVie'];
                    $arrayPHorFinVie = $_POST['PHorFinVie'];
                    $arrayPHorIniSab = $_POST['PHorIniSab'];
                    $arrayPHorFinSab = $_POST['PHorFinSab'];
                    $arrayPHorIniDom = $_POST['PHorIniDom'];
                    $arrayPHorFinDom = $_POST['PHorFinDom'];
                    $arrayPHorIniFes = $_POST['PHorIniFes'];
                    $arrayPHorFinFes = $_POST['PHorFinFes'];


                    $j = 0;
                    while ($j < count($arrayPHorIniLun)) {

                        $Lsql = "UPDATE " . $BaseDatos_systema . ".USUPAU SET 
                            USUPAU_HorIniLun_b = '" . $arrayPHorIniLun[$j] . "' ,USUPAU_HorFinLun_b = '" . $arrayPHorFinLun[$j] . "',
                            USUPAU_HorIniMar_b = '" . $arrayPHorIniMar[$j] . "' ,USUPAU_HorFinMar_b = '" . $arrayPHorFinMar[$j] . "',
                            USUPAU_HorIniMie_b = '" . $arrayPHorIniMie[$j] . "' ,USUPAU_HorFinMie_b = '" . $arrayPHorFinMie[$j] . "',
                            USUPAU_HorIniJue_b = '" . $arrayPHorIniJue[$j] . "' ,USUPAU_HorFinJue_b = '" . $arrayPHorFinJue[$j] . "',
                            USUPAU_HorIniVie_b = '" . $arrayPHorIniVie[$j] . "' ,USUPAU_HorFinVie_b = '" . $arrayPHorFinVie[$j] . "',
                            USUPAU_HorIniSab_b = '" . $arrayPHorIniSab[$j] . "' ,USUPAU_HorFinSab_b = '" . $arrayPHorFinSab[$j] . "',
                            USUPAU_HorIniDom_b = '" . $arrayPHorIniDom[$j] . "' ,USUPAU_HorFinDom_b = '" . $arrayPHorFinDom[$j] . "',
                            USUPAU_HorIniFes_b = '" . $arrayPHorIniFes[$j] . "' ,USUPAU_HorFinFes_b = '" . $arrayPHorFinFes[$j] . "',
                            USUPAU_ConsInte__USUARI_b = '" . $idUsuario . "',  USUPAU_PausasId_b='" . $arrayselectTipo1[$j] . "'
                            WHERE USUPAU_ConsInte__b = " . $arrayidUsuPauTipo1[$j];
                        if ($mysqli->query($Lsql) === TRUE) {
                            //pausa actualizada
                        }
                        $j = $j + 1;
                    }
                }
            if(isset($_POST['idUsuPauTipo0'])  && isset($_POST['selectTipo0']) && isset($_POST['PDMLun']) && isset($_POST['PCLun']) && isset($_POST['PDMMar']) && isset($_POST['PCMar']) && isset($_POST['PDMMie']) && isset($_POST['PCMie']) && isset($_POST['PDMJue']) && isset($_POST['PCJue']) && isset($_POST['PDMVie']) && isset($_POST['PCVie']) && isset($_POST['PDMSab']) && isset($_POST['PCSab']) && isset($_POST['PDMDom']) && isset($_POST['PCDom']) && isset($_POST['PDMFes']) && isset($_POST['PCFes']) ){

                $arrayidUsuPauTipo0 = $_POST['idUsuPauTipo0'];
                $arrayselectTipo0 = $_POST['selectTipo0'];
                $arrayPDMLun = $_POST['PDMLun'];
                $arrayPCLun = $_POST['PCLun'];
                $arrayPDMMar = $_POST['PDMMar'];
                $arrayPCMar = $_POST['PCMar'];
                $arrayPDMMie = $_POST['PDMMie'];
                $arrayPCMie = $_POST['PCMie'];
                $arrayPDMJue = $_POST['PDMJue'];
                $arrayPCJue = $_POST['PCJue'];
                $arrayPDMVie = $_POST['PDMVie'];
                $arrayPCVie = $_POST['PCVie'];
                $arrayPDMSab = $_POST['PDMSab'];
                $arrayPCSab = $_POST['PCSab'];
                $arrayPDMDom = $_POST['PDMDom'];
                $arrayPCDom = $_POST['PCDom'];
                $arrayPDMFes = $_POST['PDMFes'];
                $arrayPCFes = $_POST['PCFes'];
                
    
                $k = 0;
                while($k < count($arrayPDMLun)){                    

                    $Lsql="UPDATE ".$BaseDatos_systema.".USUPAU SET 
                    USUPAU_DurMaxLun_b = '".$arrayPDMLun[$k]."' , USUPAU_CanMaxLun_b = '".$arrayPCLun[$k]."',
                    USUPAU_DurMaxMar_b = '".$arrayPDMMar[$k]."' , USUPAU_CanMaxMar_b = '".$arrayPCMar[$k]."',
                    USUPAU_DurMaxMie_b = '".$arrayPDMMie[$k]."' , USUPAU_CanMaxMie_b = '".$arrayPCMie[$k]."',
                    USUPAU_DurMaxJue_b = '".$arrayPDMJue[$k]."' , USUPAU_CanMaxJue_b = '".$arrayPCJue[$k]."',
                    USUPAU_DurMaxVie_b = '".$arrayPDMVie[$k]."' , USUPAU_CanMaxVie_b = '".$arrayPCVie[$k]."',
                    USUPAU_DurMaxSab_b = '".$arrayPDMSab[$k]."' , USUPAU_CanMaxSab_b = '".$arrayPCSab[$k]."',
                    USUPAU_DurMaxDom_b = '".$arrayPDMDom[$k]."' , USUPAU_CanMaxDom_b = '".$arrayPCDom[$k]."',
                    USUPAU_DurMaxFes_b = '".$arrayPDMFes[$k]."' , USUPAU_CanMaxFes_b = '".$arrayPCFes[$k]."',
                    USUPAU_ConsInte__USUARI_b = '".$idUsuario."',USUPAU_PausasId_b='".$arrayselectTipo0[$k]."' WHERE USUPAU_ConsInte__b = ".$arrayidUsuPauTipo0[$k];

                

                        if ($mysqli->query($Lsql) === TRUE) {
                        // echo "actualizadoOtro=".$j;
                        }
                    $k=$k+1;            
                }
    
                
            }


       }
        }
        /**
         * En esta parte es donde se hace toda la logica de insercion, actualizacion y eliminacion 
         * de los permisos de backoffice y calidad
         */

        // validamos que operacion es para obtener el id del usuario
        $idUser = 0;
        if ($_POST['oper'] == "add") {
            $idUser = $mysqli->insert_id;
            $idUser = $id_Usuario_Nuevo;
        } else if ($_POST['oper'] == "edit") {
            $idUser = $idUsuario;
        }

        $idHuesped = $_SESSION['HUESPED'];

        // Se valida si los campos existen de lo contrario se llena con un arreglo vacio
        $arrPermisoId = (isset($_POST['permisoId'])? $_POST['permisoId'] : array());
        $arrPermisoFormulario = (isset($_POST['permisoFormulario'])?  $_POST['permisoFormulario'] : array());
        $arrPermisoVer = (isset($_POST['permisoVer'])?  $_POST['permisoVer'] : array());
        $arrPermisoEditar = (isset($_POST['permisoEditar'])?  $_POST['permisoEditar'] : array());
        $arrPermisoAgregar = (isset($_POST['permisoAgregar'])?  $_POST['permisoAgregar'] : array());
        $arrPermisoEliminar = (isset($_POST['permisoEliminar'])?  $_POST['permisoEliminar'] : array());

        // Se eliminan los registros guardados que no estan en permisoId
        $sqlRegistrosActuales = "SELECT PEOBUS_ConsInte__b FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b =".$idUser." AND PEOBUS_ConsInte__GUION__b IN (SELECT GUION__ConsInte__b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__PROYEC_b = ".$idHuesped.")";
        $resultPeobus = $mysqli->query($sqlRegistrosActuales);
        
        while($key = $resultPeobus->fetch_array()){
            // Se valida para hacer la eliminacion si no lo encuentra

            if(!in_array($key[0], $arrPermisoId)){
                $sqlEliminarRegistro = "DELETE FROM ".$BaseDatos_systema.".PEOBUS WHERE (PEOBUS_ConsInte__b = '".$key[0]."')";
                $mysqli->query($sqlEliminarRegistro);
            }
        }
        
        // Se reccorre el arreglo para insertar o actualizar
        for ($i=0; $i <  count($arrPermisoId); $i++) { 
            
            if(in_array($arrPermisoId[$i], $arrPermisoVer)){
                $permisoVer = -1;
            }else {
                $permisoVer = 0;
            }

            if(in_array($arrPermisoId[$i], $arrPermisoEditar)){
                $permisoEditar = -1;
            }else {
                $permisoEditar = 0;
            }

            if(in_array($arrPermisoId[$i], $arrPermisoAgregar)){
                $permisoAgregar = -1;
            }else {
                $permisoAgregar = 0;
            }

            if(in_array($arrPermisoId[$i], $arrPermisoEliminar)){
                $permisoEliminar = -1;
            }else {
                $permisoEliminar = 0;
            }
            
            if($arrPermisoId[$i] < 0){
                $sqlNuevoPermiso = "INSERT INTO ".$BaseDatos_systema.".PEOBUS (
                    PEOBUS_ConsInte__USUARI_b, 
                    PEOBUS_ConsInte__GUION__b, 
                    PEOBUS_VeRegPro__b, 
                    PEOBUS_Escritur__b, 
                    PEOBUS_Adiciona__b, 
                    PEOBUS_Borrar____b, 
                    PEOBUS_VeReCargo_b) VALUES ('".$idUser."', '".$arrPermisoFormulario[$i]."', '".$permisoVer."', '".$permisoEditar."', '".$permisoAgregar."', '".$permisoEliminar."', '0')";

                    $mysqli->query($sqlNuevoPermiso);
            }else{
                $sqlActualizarPermiso = "UPDATE ".$BaseDatos_systema.".PEOBUS SET 
                PEOBUS_ConsInte__GUION__b = '".$arrPermisoFormulario[$i]."', 
                PEOBUS_VeRegPro__b = '".$permisoVer."', 
                PEOBUS_Escritur__b = '".$permisoEditar."', 
                PEOBUS_Adiciona__b = '".$permisoAgregar."', 
                PEOBUS_Borrar____b = '".$permisoEliminar."' 
                WHERE (PEOBUS_ConsInte__b = '".$arrPermisoId[$i]."');";

                $mysqli->query($sqlActualizarPermiso);
            }
        } 

        // Actualizamos la lista de la malla de turno
        
        if(isset($_POST['malla_turno']) && $_POST['malla_turno'] != ''){

            $mallaTurnoId = $_POST['malla_turno'];

            $sqlUpdate = "UPDATE {$BaseDatos_systema}.USUARI SET USUARI_IdMalla_b = {$mallaTurnoId} WHERE USUARI_ConsInte__b = {$idUser}";
            $mysqli->query($sqlUpdate);
        }

    }

    if (isset($_GET['dameCampanas'])) {

        if (isset($_POST['idioma'])) {
            switch ($_POST['idioma']) {
                case 'en':
                    include(__DIR__ . "../../../../idiomas/text_en.php");
                    break;

                case 'es':
                    include(__DIR__ . "../../../../idiomas/text_es.php");
                    break;

                default:
                    include(__DIR__ . "../../../../idiomas/text_en.php");
                    break;
            }
        }

        $id_usuario = $_POST['id_usuario'];
        $Lsql = "SELECT CAMPAN_Nombre____b , ASITAR_ConsInte__USUARI_b FROM " . $BaseDatos_systema . ".ASITAR JOIN " . $BaseDatos_systema . ".CAMPAN ON CAMPAN_ConsInte__b = ASITAR_ConsInte__CAMPAN_b WHERE md5(concat('".clave_get."', ASITAR_ConsInte__USUARI_b)) = '".$id_usuario."'";
        $res = $mysqli->query($Lsql);
        echo '<div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#s_campan">
                                ' . $str_campanas_aignada . '
                            </a>
                        </h4>
                    </div>
                    <div id="s_campan" class="panel-collapse collapse in">
                        <table class="table table-bordered table-hover" style="width:100%;">
                            <tbody>';

        while ($key = $res->fetch_object()) {
            echo "<tr>";
            echo "<td>";
            echo mb_strtoupper($key->CAMPAN_Nombre____b);
            echo "</td>";
            echo "<tr>";
        }
        echo "</tbody>";
        echo "</table>
                    </div>
                </div>";
    }

    if (isset($_POST['dameCampanas'])) {
        $id_usuario = $_POST['id_usuario'];
        $Lsql = "SELECT CAMPAN_Nombre____b FROM " . $BaseDatos_systema . ".ASITAR JOIN " . $BaseDatos_systema . ".CAMPAN ON CAMPAN_ConsInte__b = ASITAR_ConsInte__CAMPAN_b WHERE md5(concat('".clave_get."', ASITAR_ConsInte__USUARI_b)) = '".$id_usuario."'";
        $res = $mysqli->query($Lsql);
        if ($res->num_rows > 0) {
            $datos = array();
            $i = 0;
            while ($key = $res->fetch_object()) {
                $datos[$i]['campanas'] = $key->CAMPAN_Nombre____b;
                $i++;
            }
            echo json_encode(array('code' => 1, 'datos' => $datos));
        } else {
            echo json_encode(array('code' => 0, 'message' => 'Sin Campanas'));
        }
    }



    // Determine if a string contains a given substring
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }


    function validateAmbiguosPass(string $contrasenha, array $datosUsuario):array{

        $valido = true;
        $message = "";

        $dataValidation = [];

        // SE PASA TODO A MINUSCULAS PARA VALIDAR MEJOR
        $contrasenhaLower = strtolower($contrasenha);

        // VALIDAMOS QUE NO CONTENGA NADA DEL CORREO
        $correo = explode("@", $datosUsuario["USUARI_Codigo____b"]);
        array_push($dataValidation, strtolower($correo[0]));
        array_push($dataValidation, explode(".",strtolower($correo[1]))[0]);


        // VALIDAMOS QUE NO CONTENGA EL NOMBRE O APELLIDO
        $nombres = explode(" ", $datosUsuario["USUARI_Nombre____b"]);
        foreach ($nombres as $key => $value) {
            array_push($dataValidation, strtolower($value));
        }
        
        // VALIDAMOS QUE NO CONTENGA LA CEDULA // SE VALIDA APARTE DEBIDO A QUE ES UN NUMERO
        if(str_contains($contrasenhaLower, strtolower($datosUsuario["USUARI_Identific_b"]))){
            $valido = false;
            $message = "La constraseña no debe de contener informacion personal o informacion del usuario";
        }

        foreach ($dataValidation as $key => $value) {
            if (str_contains($contrasenhaLower, $value) && !is_numeric($value)){
            $valido = false;
            $message = "La constraseña no debe de contener informacion personal o informacion del usuario";
            }
        }


        return ["valido" => $valido, "message" => $message ];
    }


    // Funcion para validar la contraseña contra las politicas configuradas en el huesped

    function validatePolicyPassword(string $contrasenha,  array $datosUsuario):array{

        global $BaseDatos_general;
        global $mysqli;


        $valido = true;
        $messages = [];

        // SE TRAE LA CONFIGURACION DE POLITICAS DEL HUESPED
        $PolicyPassLsql = "SELECT pass_longitud_requerida, pass_mayuscula_requerida, pass_numero_requerido, pass_simbolo_requerido, pass_historico_requerido FROM {$BaseDatos_general}.huespedes WHERE id = '{$_SESSION['HUESPED_CRM']}' LIMIT 1";
            $res_PolicyPass = $mysqli->query($PolicyPassLsql);
            if ($res_PolicyPass = $res_PolicyPass->fetch_object()) {


                // SE VALIDA EL HISTORICO DE CONTRASEÑAS DEL USUARIO

                {
                    $validoHistorico = true;
                    $claveEncryp = encriptaPassword($contrasenha);

                    // SI YA HAY UN HISTORICO EN LA BASE SE REALIZA LA COMPARATIVA Y SE ACTUALIZA DICHO HISTORICO
                    if($datosUsuario["USUARI_Clave_Historico_____b"] !== null) {
                        $jsonHistorico = json_decode($datosUsuario["USUARI_Clave_Historico_____b"]);

                        foreach ($jsonHistorico as $key => $value) {
                            if($claveEncryp == $value){
                                $validoHistorico = false;
                            }
                        }

                        if(count($jsonHistorico) == 4){
                            array_pop($jsonHistorico);
                            array_unshift($jsonHistorico, $claveEncryp);
                        }else{
                            array_unshift($jsonHistorico, $claveEncryp);
                        }

                    }else{
                        $jsonHistorico = [$claveEncryp];
                    }

                    // SE VALIDA SI SE TIENE HABILITADO LA VERIFICACION DEL HISTORICO
                    if($res_PolicyPass->pass_historico_requerido == 1){
                        if($validoHistorico){
                            
                            $jsonHistorico = json_encode($jsonHistorico);

                        }else{
                            $valido = false;
                            return ["valido" => $valido, "message" => "Su nueva contraseña no puede ser igual a una anteriormente utilizada" ];
                        }
                    }else{
                        // SIEMPRE GUARDO EL HISTORICO ASI NO ESTE CONFIGURADA LA POLITICA
                        $jsonHistorico = json_encode($jsonHistorico);

                    }
                }

                // SE VALIDA QUE LA CONTRASEÑA NO SEA AMBIGUA
                $passAmbigua = validateAmbiguosPass($contrasenha, $datosUsuario);
                if(!$passAmbigua["valido"]){
                    $valido = false;
                    return ["valido" => $valido, "message" => $passAmbigua["message"] ];
                }

                // SE VALIDA LA LONGITUD
                if($res_PolicyPass->pass_longitud_requerida == 1){
                    if(!preg_match_all( "/^.{8,}/m" , $contrasenha)){
                        $valido = false;
                        array_push($messages, "mínimo 8 caracteres");
                    }
                }
                // SE VALIDA SI CONTIENE ALGUNA MAYUSCULA
                if($res_PolicyPass->pass_mayuscula_requerida == 1){
                    if(!preg_match_all( "/[A-Z]/m" , $contrasenha)){
                        $valido = false;
                        array_push($messages, "una letra en mayúscula");
                    }
                }
                // SE VALIDA SI CONTIENE ALGUN NUMERO
                if($res_PolicyPass->pass_numero_requerido == 1){
                    if(!preg_match_all( "/[0-9]/m" , $contrasenha)){
                        $valido = false;
                        array_push($messages, "un numero");
                    }
                }
                // SE VALIDA SI CONTIENE ALGUN CARACTER ESPECIAL
                if($res_PolicyPass->pass_simbolo_requerido == 1){
                    if(!preg_match_all( "/[^a-zA-Z0-9]/m" , $contrasenha)){
                        $valido = false;
                        array_push($messages, "un carácter especial");
                    }
                }

            }

            // SE CONSTRUYE EL MENSAJE EN CASO DE QUE LA CONSTRASEÑA NUEVA NO SEA VALIDA

            $finalMessage = "La contraseña debe de contener ";

            $separador = "";
            for ($i=0; $i < count($messages) ; $i++) { 
                $finalMessage .= $separador.$messages[$i];

                if(count($messages)-2 == $i){
                    $separador = " y ";
                }else{
                    $separador = ", ";
                }
            };

            return ["valido" => $valido, "message" => $finalMessage, "historico" => $jsonHistorico];
    }


    if(isset($_GET["modificarPassword"])){
        if (isset($_POST['txrPasswordCh']) && $_POST['txrPasswordCh']) {
            $contrasenha = $_POST['txrPasswordCh'];            
            $DatosLsql = "SELECT * FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_ConsInte__b = " . $_SESSION['IDENTIFICACION'] . " LIMIT 1";
            $res_Datos = $mysqli->query($DatosLsql);
            if ($res_Datos->num_rows > 0) {
                $datosUsuario = $res_Datos->fetch_array();

                // SE VALIDA LA CONTRASEÑA CONTRA LAS POLITICAS DE SEGURIDAD
                $validacion = validatePolicyPassword($contrasenha, $datosUsuario);

                if($validacion["valido"]){

                    // SE ACTUALIZA LA TABLA USUARI
                    $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_Clave_____b = '" . encriptaPassword($contrasenha) . "', USUARI_Clave_Historico_____b = '".$validacion["historico"]."', USUARI_Clave_Temp_____b = 0, USUARI_FechUpdate_Clave______b = NOW() WHERE USUARI_ConsInte__b = " . $_SESSION['IDENTIFICACION'];
                    $_SESSION['CLAVE_TEMPORAL'] = "0";

                    if ($mysqli->query($Lsql) === true) {

                        // SE ACTUALIZA LA CONTRASEÑA 
                        $respuesta = usuarioPersistir($datosUsuario['USUARI_Nombre____b'], null, $datosUsuario['USUARI_Correo___b'], $datosUsuario['USUARI_Identific_b'], $_POST['txrPasswordCh'], true, $datosUsuario['USUARI_ConsInte__PROYEC_b'], $datosUsuario['USUARI_UsuaCBX___b']);
                        if (!empty($respuesta) && !is_null($respuesta)) {
                            $json = json_decode($respuesta);

                            if ($json->strEstado_t == "ok") {

                                $ConsultaSQL = "SELECT * FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_ConsInte__b= '". $_SESSION['IDENTIFICACION'] ."';";
                                if($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
                                    $CantidadResultados = $ResultadoSQL->num_rows;
                                    if($CantidadResultados > 0) {
                                        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                                            $PassTemporal= $FilaResultado['USUARI_Clave_Temp_____b'];
                                        }
                                    }else{
                                        //Sin Resultados
                                        json_encode(array("estado" => "error"));
                                        mysqli_close($mysqli);
                                        exit;
                                    }
                                }else{
                                    //Error
                                    $Falla = mysqli_error($mysqli);
                                    json_encode(array("estado" => "error", "Falla" => $Falla));
                                    mysqli_close($mysqli);
                                    exit;
                                }

                                if($PassTemporal == "-1"){
                                    $res= sendMailPassword($datosUsuario['USUARI_Correo___b'], $datosUsuario['USUARI_Nombre____b'], $_POST['txrPasswordCh'],);
                                }else{
                                    $res= sendMailPassword('dev2@dyalogo.com', $datosUsuario['USUARI_Correo___b'], $_POST['txrPasswordCh'],);
                                }

                                $res= json_decode($res);
                                if($res->strEstado_t === "ok"){
                                    echo json_encode(array("estado" => "success"));
                                } else {
                                    json_encode(array("estado" => "error"));
                                }
                                
                            } else {
                                echo json_encode(array("estado" => "error"));
                            }
                        }
                    }
                }else{
                    echo json_encode(array("estado" => "invalida", "message" => $validacion["message"]));
                }
            }
        }
    }

    if (isset($_GET['modificarImagen'])) {

        $ver = (float) phpversion();
        if ($ver > 7.0) {
            if (isset($_FILES['inpFotoPerfil']['tmp_name']) && !empty($_FILES['inpFotoPerfil']['tmp_name'])) {

                $filetype=$_FILES['inpFotoPerfil']['type'];
                if($filetype == "image/jpeg"){
                    $Lsql = "SELECT USUARI_UsuaCBX___b FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_ConsInte__b = " . $_SESSION['IDENTIFICACION'];

                    $query = $mysqli->query($Lsql) or trigger_error($mysqli->error . " [$Lsql]");

                    if ($query->num_rows > 0) {

                        $idFoto = $query->fetch_array();

                        $extencion = explode('.', basename($_FILES['inpFotoPerfil']['name']));
                        $target_path = '/var/../Dyalogo/img_usuarios/usr' . $idFoto['USUARI_UsuaCBX___b'] . '.jpg';
                        $target_path = str_replace(' ', '', $target_path);

                        if (file_exists($target_path)) {
                            unlink($target_path);
                        }


                        $nuevoAncho = 500;
                        $nuevoAlto = 500;
                        list($ancho, $alto) = getimagesize($_FILES['inpFotoPerfil']['tmp_name']);
                        $origen = imagecreatefromjpeg($_FILES['inpFotoPerfil']['tmp_name']);
                        //le decimos que vamos acrear una imagen en el destino con esos ancho y alto
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                        //cortamos esta imagen
                        /* imagecopyresized();
                          1- destino
                          2- Origen
                          3- donde se inicia el corte en el EjeX destino
                          4- donde se inicia el corte en el ejeY destino
                          5- donde se inicia el corte en el ejeX Origen
                          6- donde se inicia el corte en el ejeY Origen
                          7- nuevo ancho
                          8- nuebp alto
                          9- ancho original
                          10- alto original
                         */

                        imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                        imagejpeg($destino, $target_path);


                        /* copy($_FILES['inpFotoPerfil']['tmp_name'], $target_path); */
                        $imagenNombre = $idFoto['USUARI_UsuaCBX___b'] . '.jpg' . "?foto=" . rand(10,9999);
                        $imagenNombre = str_replace(' ', '', $imagenNombre);

                        $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_Foto______b = '" . $imagenNombre . "' WHERE USUARI_ConsInte__b = " . $_SESSION['IDENTIFICACION'];
                        if ($mysqli->query($Lsql) === true) {
                            $imagenUser = "assets/img/Kakashi.fw.png";
                            if (file_exists("/var/../Dyalogo/img_usuarios/usr" . $idFoto['USUARI_UsuaCBX___b'] . ".jpg")) {
                                $imagenUser = "/DyalogoImagenes/usr" . $imagenNombre;
                            }
                            $_SESSION['IMAGEN'] = $imagenUser;
                            echo json_encode(array("estado" => "success", "image_url" => $imagenUser));
                        } else {
                            echo json_encode(array("estado" => "error", "message" => $mysqli->error));
                        }
                    }
                }else{
                    echo json_encode(array("estado" => "error", "message" => "La imagen debe de ser en formato JPG"));
                }
            }
        }
    }


    function recordarPassword(string $strCorreo)
    {
        $respuesta = generarpassword($strCorreo);
        $json = json_decode($respuesta);

        $data = [];
        if (!empty($respuesta) && !is_null($respuesta)) {

            $data = array(
                "estado" => $json->strEstado_t ?? "error",
                "mensaje" => $json->strMensaje_t ?? "Respuesta: Fallo al momento de regenerar contraseña"
            );
        } else {
            $data = array(
                "estado" => "error",
                "mensaje" => "Fallo al momento de regenerar contraseña {$json}"
            );
        }

        return $data;
    }

    //Funcionalidad para saber recuperar el password
    if (isset($_GET['recuperarPassWord'])) {
        echo json_encode(recordarPassword($_POST['correo']));
    }

   
    if (isset($_GET['insertarMassivo'])) {
        require "../../../carga/Excel.php";
        $name = $_FILES['txtUsuariosMontar']['name'];
        $tname = $_FILES['txtUsuariosMontar']['tmp_name'];
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);

        if ($_FILES['txtUsuariosMontar']["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $obj_excel = $objReader->load($tname);
        } else if ($_FILES['txtUsuariosMontar']["type"] == 'application/vnd.ms-excel') {
            $obj_excel = PHPExcel_IOFactory::load($tname);
        }

        $sheetData = $obj_excel->getActiveSheet()->toArray(null, true, true, true);
        $arr_datos = array();
        $highestColumm = $obj_excel->setActiveSheetIndex(0)->getHighestColumn(); // e.g. "EL"
        $highestRow = $obj_excel->setActiveSheetIndex(0)->getHighestRow();
        $repetidos = 0;
        $insertado = 0;
        $fallidos_ = 0;
        $arrayFallas = array();
        $arrayRepeti = array();
        $x = 0;

        foreach ($sheetData as $index => $value) {
            if ($index > 1) {
                if ((!is_null($value['A']) OR ! empty($value['A'])) && (!is_null($value['B']) OR ! empty($value['B'])) && (!is_null($value['C']) OR ! empty($value['C'])) && (!is_null($value['D']) OR ! empty($value['D']))) {

                    /* Validamos que no este repetido el correo */
                    $ValidarLsql_Correo = "SELECT USUARI_Correo___b FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_Correo___b =  '" . trim($value['B']) . "'";
                    $res_ValidarLsql_Correo = $mysqli->query($ValidarLsql_Correo);
                    $valido = 0;
                    if ($res_ValidarLsql_Correo->num_rows > 0) {
                        $valido = 1;
                    }

                    /* Validamos que no este Repetida la con */
                    $validarLsql_identificacion = "SELECT USUARI_Identific_b FROM  " . $BaseDatos_systema . ".USUARI WHERE USUARI_Identific_b =  '" . trim($value['C']) . "'";
                    $res_validarLsql_identificacion = $mysqli->query($validarLsql_identificacion);
                    $valido = 0;
                    if ($res_validarLsql_identificacion->num_rows > 0) {
                        $valido = 1;
                    }
                    
                    if ((!is_null($value['E']) OR ! empty($value['E']))){
                        
                        if(is_numeric($value['E'])){
                            $UNIX_DATE = ($value['E'] - 25569) * 86400;
                            $value['E'] = gmdate("Y-m-d H:i:s", $UNIX_DATE);

                        }else{
                            $arrayVariable = explode('/', $value['E']);

                            if(count($arrayVariable) > 1){
                                $totalLongitud = $arrayVariable[0];

                                if($totalLongitud < 10){
                                    $totalLongitud = "0".$arrayVariable[0];
                                }
                                $value['E'] = $arrayVariable[2] ."-".$arrayVariable[1]."-".$totalLongitud;
                            }
                        }                    
                    }                    
                    
                    if ((!is_null($value['F']) OR ! empty($value['F']))){
                        
                        if(is_numeric($value['F'])){
                            $UNIX_DATE = ($value['F'] - 25569) * 86400;
                            $value['F'] = gmdate("Y-m-d H:i:s", $UNIX_DATE);

                        }else{
                            $arrayVariable = explode('/', $value['E']);

                            if(count($arrayVariable) > 1){
                                $totalLongitud = $arrayVariable[0];

                                if($totalLongitud < 10){
                                    $totalLongitud = "0".$arrayVariable[0];
                                }
                                $value['F'] = $arrayVariable[2] ."-".$arrayVariable[1]."-".$totalLongitud;
                            }
                        }                    
                    }

                    if ($valido == 0) {
                        $contrasenha = crearPassword();

                        $Lsql = "INSERT INTO " . $BaseDatos_systema . ".USUARI (USUARI_TipoContr_b,USUARI_FechIniContr_b,USUARI_FechFinContr_b,USUARI_Codigo____b, USUARI_Nombre____b,  USUARI_Identific_b, USUARI_Clave_____b, USUARI_Cargo_____b, USUARI_Correo___b, USUARI_ConsInte__PROYEC_b, USUARI_IndiActi__b,USUARI_Foto______b,USUARI_HorIniLun_b,USUARI_HorFinLun_b,USUARI_HorIniMar_b,USUARI_HorFinMar_b,USUARI_HorIniMie_b,USUARI_HorFinMie_b,USUARI_HorIniJue_b,USUARI_HorFinJue_b,USUARI_HorIniVie_b,USUARI_HorFinVie_b, USUARI_Clave_Temp_____b) VALUES (NULL,'{$value['E']}','{$value['F']}','" . $value['B'] . "' , '" . $value['A'] . "' , '" . trim($value['C']) . "'  , '" . encriptaPassword($contrasenha)."','".$value['D']."','".trim($value['B'])."',".$_SESSION['HUESPED'].",'-1','41.jpg','".$_POST['horaEntradaPorDefecto']."','".$_POST['horaFinalPorDefecto']."','".$_POST['horaEntradaPorDefecto']."','".$_POST['horaFinalPorDefecto']."','".$_POST['horaEntradaPorDefecto']."','".$_POST['horaFinalPorDefecto']."','".$_POST['horaEntradaPorDefecto']."','".$_POST['horaFinalPorDefecto']."','".$_POST['horaEntradaPorDefecto']."','".$_POST['horaFinalPorDefecto']."', '-1')";
                        
                        if ($mysqli->query($Lsql) === TRUE) {
                            $id_Usuario_Nuevo=$mysqli->insert_id;
                            
                            //Llenamos Pausas por defecto
                            $i = 1;
                            while($i <= 3){  
                                $Lsql="INSERT INTO  ".$BaseDatos_systema.".USUPAU  (USUPAU_ConsInte__USUARI_b,USUPAU_PausasId_b,USUPAU_Tipo_b,USUPAU_HorIniLun_b,USUPAU_HorFinLun_b,USUPAU_HorIniMar_b,USUPAU_HorFinMar_b,USUPAU_HorIniMie_b,USUPAU_HorFinMie_b,USUPAU_HorIniJue_b,USUPAU_HorFinJue_b,USUPAU_HorIniVie_b,USUPAU_HorFinVie_b) values('".$id_Usuario_Nuevo."','".$_POST['breakselect'.$i]."','1','".$_POST['horaInicialPorDefecto'.$i]."','".$_POST['horaFinalPorDefecto'.$i]."','".$_POST['horaInicialPorDefecto'.$i]."','".$_POST['horaFinalPorDefecto'.$i]."','".$_POST['horaInicialPorDefecto'.$i]."','".$_POST['horaFinalPorDefecto'.$i]."','".$_POST['horaInicialPorDefecto'.$i]."','".$_POST['horaFinalPorDefecto'.$i]."','".$_POST['horaInicialPorDefecto'.$i]."','".$_POST['horaFinalPorDefecto'.$i]."') " ; 
                                $mysqli->query($Lsql);                                          
                                $i=$i+1;
                            }
                            
                            $insertado++;


                            $respuesta = usuarioPersistir($value['A'], null, $value['B'], trim($value['C']), $contrasenha, true, $_SESSION['HUESPED'], -1);

                            if (!empty($respuesta) && !is_null($respuesta)) {
                                $json = json_decode($respuesta);

                                if ($json->strEstado_t == "ok") {
                                    $Lsql = "UPDATE " . $BaseDatos_systema . ".USUARI SET USUARI_UsuaCBX___b = " . $json->intIdCreacion_t . " WHERE USUARI_ConsInte__b = " . $id_Usuario_Nuevo;
                                    $mysqli->query($Lsql);

                                     //Insertar el usuario en el huesped 
                                    $str_insertHuespedSql = "INSERT INTO " . $BaseDatos_general . ".huespedes_usuarios(id_huesped, id_usuario) VALUES(" . $_SESSION['HUESPED'] . ", " . $json->intIdCreacion_t . ");";
                                    $mysqli->query($str_insertHuespedSql);
                                    
                                    sendMailPassword($value['B'], $value['A'], $contrasenha);
                                }
                            }
                        } else {
                            $fallidos_++;
                            $arrayFallas[$x]['nombres'] = $value['A'];
                            $arrayFallas[$x]['correo'] = $value['B'];
                            $arrayFallas[$x]['identificacion'] = trim($value['C']);
                            $arrayFallas[$x]['cargo'] = $value['D'];
                        }
                    } else {
                         /*Esta repetido toca guardarlo enalguna parte para que lo podamos escargar*/ 
                        $repetidos++;
                        $arrayRepeti[$x]['nombres'] = $value['A'];
                        $arrayRepeti[$x]['correo'] = $value['B'];
                        $arrayRepeti[$x]['identificacion'] = trim($value['C']);
                        $arrayRepeti[$x]['cargo'] = $value['D'];
                    }
                    $x++;
                }
            }
        }

        echo json_encode(array('code' => '0', 'exitos' => $insertado, 'Fallas' => $fallidos_, 'repetidos' => $repetidos, 'arrayFallas' => $arrayFallas, 'arrayRepetidos' => $arrayRepeti, 'total' => $x));
    }

    // Obtiene la lista de permisos de backoffice y calidad
    if(isset($_GET['cargarPermisosBOC']) && $_GET['cargarPermisosBOC'] == 'si'){

        $idHuesped = $_POST['idHuesped'];
        $idUsuario = $_POST['idUsuario'];

        $sqlFormularios = 'SELECT GUION__ConsInte__b AS id, GUION__Nombre____b AS nombre, GUION__ConsInte__PROYEC_b AS id_huesped FROM '.$BaseDatos_systema.'.GUION_ WHERE GUION__ConsInte__PROYEC_b ='.$idHuesped.' ORDER BY nombre ASC';
        $result = $mysqli->query($sqlFormularios);

        $arrFormulario = array();

        while($fila = $result->fetch_object()) {
            $arrFormulario[] = $fila;
        }

        $sqlUsuarioPermiso = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE md5(concat('".clave_get."', PEOBUS_ConsInte__USUARI_b)) = '".$idUsuario."' AND PEOBUS_ConsInte__GUION__b IN (SELECT GUION__ConsInte__b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__PROYEC_b = ".$idHuesped.") ORDER BY PEOBUS_ConsInte__b ASC";
        $result2 = $mysqli->query($sqlUsuarioPermiso);

        $arrPEOBUS = array();

        while($fila = $result2->fetch_object()) {
            $arrPEOBUS[] = $fila;
        }

        echo json_encode($arr = array('arrPEOBUS'=> $arrPEOBUS, 'arrFormulario'=> $arrFormulario));
    }
    
    // CARGAR LAS MALLAS CONFIGURADAS DEL HUESPED
    if(isset($_POST['cargarMallas'])){
        $estado=array();
        if(isset($_SESSION['HUESPED']) && is_numeric($_SESSION['HUESPED'])){
            $sql=$mysqli->query("SELECT MALLATURNO_ConsInte__b,MALLATURNO__Nombre_b FROM {$BaseDatos_systema}.MALLATURNO WHERE MALLATURNO__ConsInte__HUESPED_b={$_SESSION['HUESPED']}");
            if($sql){
                $estado['estado']="2";
                if($sql->num_rows > 0){
                    $estado['html']="<option value='0'>Seleccione</option>";
                    while($malla = $sql->fetch_object()){
                        $estado['html'].="<option value='{$malla->MALLATURNO_ConsInte__b}'>{$malla->MALLATURNO__Nombre_b}</option>"; //crear una opción por cada malla de turnos encontrada
                    }
                }else{
                    $estado['html']="<option value='0'>Seleccione</option>"; //aún no se han agregado mallas de turno al huesped
                }
            }else{
                $estado['estado']="1"; //algo paso y fallo la consulta
            }
        }else{
            $estado['estado']="0"; // no existe la variable que almacena el id del huesped
        }
        echo json_encode($estado);
    }

    if (isset($_POST['guardarMalla'])) {
        if (isset($_SESSION['HUESPED']) && is_numeric($_SESSION['HUESPED'])) {
            $id_malla = isset($_POST['idMalla']) ? $_POST['idMalla'] : false;
            $nombre = isset($_POST['nombreMalla']) ? $_POST['nombreMalla'] : false;

            //hora de la entrada y salida de la jornada laboral
            $iniLunes    = isset($_POST['HorIniLunDefecto']) ? $_POST['HorIniLunDefecto'] : false;
            $finLunes    = isset($_POST['HorFinLunDefecto']) ? $_POST['HorFinLunDefecto'] : false;
            $iniMartes   = isset($_POST['HorIniMarDefecto']) ? $_POST['HorIniMarDefecto'] : false;
            $finMartes   = isset($_POST['HorFinMarDefecto']) ? $_POST['HorFinMarDefecto'] : false;
            $iniMiercoles = isset($_POST['HorIniMieDefecto']) ? $_POST['HorIniMieDefecto'] : false;
            $finMiercoles = isset($_POST['HorFinMieDefecto']) ? $_POST['HorFinMieDefecto'] : false;
            $iniJueves   = isset($_POST['HorIniJueDefecto']) ? $_POST['HorIniJueDefecto'] : false;
            $finJueves   = isset($_POST['HorFinJueDefecto']) ? $_POST['HorFinJueDefecto'] : false;
            $iniViernes  = isset($_POST['HorIniVieDefecto']) ? $_POST['HorIniVieDefecto'] : false;
            $finViernes  = isset($_POST['HorFinVieDefecto']) ? $_POST['HorFinVieDefecto'] : false;
            $iniSabado   = isset($_POST['HorIniSabDefecto']) ? $_POST['HorIniSabDefecto'] : false;
            $finSabado   = isset($_POST['HorFinSabDefecto']) ? $_POST['HorFinSabDefecto'] : false;
            $iniDomingo  = isset($_POST['HorIniDomDefecto']) ? $_POST['HorIniDomDefecto'] : false;
            $finDomingo  = isset($_POST['HorFinDomDefecto']) ? $_POST['HorFinDomDefecto'] : false;
            $iniFestivo  = isset($_POST['HorIniFesDefecto']) ? $_POST['HorIniFesDefecto'] : false;
            $finFestivo  = isset($_POST['HorFinFesDefecto']) ? $_POST['HorFinFesDefecto'] : false;

            //horario de las pausas por defecto
            $arraybreakHorIniLun = $_POST['breakHorIniLunDefecto'];
            $arraybreakHorFinLun = $_POST['breakHorFinLunDefecto'];
            $arraybreakHorIniMar = $_POST['breakHorIniMarDefecto'];
            $arraybreakHorFinMar = $_POST['breakHorFinMarDefecto'];
            $arraybreakHorIniMie = $_POST['breakHorIniMieDefecto'];
            $arraybreakHorFinMie = $_POST['breakHorFinMieDefecto'];
            $arraybreakHorIniJue = $_POST['breakHorIniJueDefecto'];
            $arraybreakHorFinJue = $_POST['breakHorFinJueDefecto'];
            $arraybreakHorIniVie = $_POST['breakHorIniVieDefecto'];
            $arraybreakHorFinVie = $_POST['breakHorFinVieDefecto'];
            $arraybreakHorIniSab = $_POST['breakHorIniSabDefecto'];
            $arraybreakHorFinSab = $_POST['breakHorFinSabDefecto'];
            $arraybreakHorIniDom = $_POST['breakHorIniDomDefecto'];
            $arraybreakHorFinDom = $_POST['breakHorFinDomDefecto'];
            $arraybreakHorIniFes = $_POST['breakHorIniFesDefecto'];
            $arraybreakHorFinFes = $_POST['breakHorFinFesDefecto'];
            $arraybreakselect = $_POST['breakselectDefecto'];

            //VARIABLES PARA EL HORARIO DE LAS PAUSAS CON HORARIO PERSONALIZADAS
            $arrayidUsuPauTipo1Predefinida = isset($_POST['idUsuPauTipo1Predefinida']) ? $_POST['idUsuPauTipo1Predefinida'] : false;
            $arrayselectTipo1Predefinida  = isset($_POST['selectTipo1Predefinida'])   ? $_POST['selectTipo1Predefinida']   : false;
            $arraypredefinidaPHorIniLun   = isset($_POST['predefinidaPHorIniLun'])    ? $_POST['predefinidaPHorIniLun']    : false;
            $arraypredefinidaPHorFinLun   = isset($_POST['predefinidaPHorFinLun'])    ? $_POST['predefinidaPHorFinLun']    : false;
            $arraypredefinidaPHorIniMar   = isset($_POST['predefinidaPHorIniMar'])    ? $_POST['predefinidaPHorIniMar']    : false;
            $arraypredefinidaPHorFinMar   = isset($_POST['predefinidaPHorFinMar'])    ? $_POST['predefinidaPHorFinMar']    : false;
            $arraypredefinidaPHorIniMie   = isset($_POST['predefinidaPHorIniMie'])    ? $_POST['predefinidaPHorIniMie']    : false;
            $arraypredefinidaPHorFinMie   = isset($_POST['predefinidaPHorFinMie'])    ? $_POST['predefinidaPHorFinMie']    : false;
            $arraypredefinidaPHorIniJue   = isset($_POST['predefinidaPHorIniJue'])    ? $_POST['predefinidaPHorIniJue']    : false;
            $arraypredefinidaPHorFinJue   = isset($_POST['predefinidaPHorFinJue'])    ? $_POST['predefinidaPHorFinJue']    : false;
            $arraypredefinidaPHorIniVie   = isset($_POST['predefinidaPHorIniVie'])    ? $_POST['predefinidaPHorIniVie']    : false;
            $arraypredefinidaPHorFinVie   = isset($_POST['predefinidaPHorFinVie'])    ? $_POST['predefinidaPHorFinVie']    : false;
            $arraypredefinidaPHorIniSab   = isset($_POST['predefinidaPHorIniSab'])    ? $_POST['predefinidaPHorIniSab']    : false;
            $arraypredefinidaPHorFinSab   = isset($_POST['predefinidaPHorFinSab'])    ? $_POST['predefinidaPHorFinSab']    : false;
            $arraypredefinidaPHorIniDom   = isset($_POST['predefinidaPHorIniDom'])    ? $_POST['predefinidaPHorIniDom']    : false;
            $arraypredefinidaPHorFinDom   = isset($_POST['predefinidaPHorFinDom'])    ? $_POST['predefinidaPHorFinDom']    : false;
            $arraypredefinidaPHorIniFes   = isset($_POST['predefinidaPHorIniFes'])    ? $_POST['predefinidaPHorIniFes']    : false;
            $arraypredefinidaPHorFinFes   = isset($_POST['predefinidaPHorFinFes'])    ? $_POST['predefinidaPHorFinFes']    : false;

            //VARIABLES PARA EL HORARIO DE LAS PAUSAS SIN HORARIO PERSONALIZADAS
            $arrayidUsuPauTipo0Predefinida = isset($_POST['idUsuPauTipo0Predefinida']) ? $_POST['idUsuPauTipo0Predefinida'] : false;
            $arrayselectTipo0Predefinida  = isset($_POST['selectTipo0Predefinida'])   ? $_POST['selectTipo0Predefinida']   : false;
            $arraypredefinidaPCLun        = isset($_POST['predefinidaPCLun'])         ? $_POST['predefinidaPCLun']         : false;
            $arraypredefinidaPDMLun       = isset($_POST['predefinidaPDMLun'])        ? $_POST['predefinidaPDMLun']        : false;
            $arraypredefinidaPCMar        = isset($_POST['predefinidaPCMar'])         ? $_POST['predefinidaPCMar']         : false;
            $arraypredefinidaPDMMar       = isset($_POST['predefinidaPDMMar'])        ? $_POST['predefinidaPDMMar']        : false;
            $arraypredefinidaPCMie        = isset($_POST['predefinidaPCMie'])         ? $_POST['predefinidaPCMie']         : false;
            $arraypredefinidaPDMMie       = isset($_POST['predefinidaPDMMie'])        ? $_POST['predefinidaPDMMie']        : false;
            $arraypredefinidaPCJue        = isset($_POST['predefinidaPCJue'])         ? $_POST['predefinidaPCJue']         : false;
            $arraypredefinidaPDMJue       = isset($_POST['predefinidaPDMJue'])        ? $_POST['predefinidaPDMJue']        : false;
            $arraypredefinidaPCVie        = isset($_POST['predefinidaPCVie'])         ? $_POST['predefinidaPCVie']         : false;
            $arraypredefinidaPDMVie       = isset($_POST['predefinidaPDMVie'])        ? $_POST['predefinidaPDMVie']        : false;
            $arraypredefinidaPCSab        = isset($_POST['predefinidaPCSab'])         ? $_POST['predefinidaPCSab']         : false;
            $arraypredefinidaPDMSab       = isset($_POST['predefinidaPDMSab'])        ? $_POST['predefinidaPDMSab']        : false;
            $arraypredefinidaPCDom        = isset($_POST['predefinidaPCDom'])         ? $_POST['predefinidaPCDom']         : false;
            $arraypredefinidaPDMDom       = isset($_POST['predefinidaPDMDom'])        ? $_POST['predefinidaPDMDom']        : false;
            $arraypredefinidaPCFes        = isset($_POST['predefinidaPCFes'])         ? $_POST['predefinidaPCFes']         : false;
            $arraypredefinidaPDMFes       = isset($_POST['predefinidaPDMFes'])        ? $_POST['predefinidaPDMFes']        : false;

            if ($iniLunes && $finLunes && $iniMartes && $finMartes && $iniMiercoles && $finMiercoles && $iniJueves && $finJueves && $iniViernes && $finViernes && $nombre) {
                // ACÁ SE EJECUTA LA ACCIÓN ADD, CUANDO CREAMOS UNA NUEVA MALLA
                if ($_POST['operMalla'] == 'add') {
                    $sql = "INSERT INTO {$BaseDatos_systema}.MALLATURNO VALUES (NULL,{$_SESSION['HUESPED']},'{$nombre}','{$iniLunes}','{$finLunes}','{$iniMartes}','{$finMartes}','{$iniMiercoles}','{$finMiercoles}','{$iniJueves}','{$finJueves}','{$iniViernes}','{$finViernes}'";

                    //validar que no sean nulos los horarios para los dias no obligatorios
                    if ($iniSabado && $iniSabado != '') {
                        $sql .= ",'{$iniSabado}'";
                    } else {
                        $sql .= ",null";
                    }

                    if ($finSabado && $finSabado != '') {
                        $sql .= ",'{$finSabado}'";
                    } else {
                        $sql .= ",null";
                    }

                    if ($iniDomingo && $iniDomingo != '') {
                        $sql .= ",'{$iniDomingo}'";
                    } else {
                        $sql .= ",null";
                    }

                    if ($finDomingo && $finDomingo != '') {
                        $sql .= ",'{$finDomingo}'";
                    } else {
                        $sql .= ",null";
                    }

                    if ($iniFestivo && $iniFestivo != '') {
                        $sql .= ",'{$iniFestivo}'";
                    } else {
                        $sql .= ",null";
                    }

                    if ($finFestivo && $finFestivo != '') {
                        $sql .= ",'{$finFestivo}'";
                    } else {
                        $sql .= ",null";
                    }

                    $sql .= ")";
                    // echo "sql => $sql <br><br>";
                    if ($mysqli->query($sql)) {
                        $i = 0;
                        $id = $mysqli->insert_id;
                        //INSERTAMOS EL HORARIO DE LA MALLA
                        while ($i < count($arraybreakHorIniLun)) {
                            $Lsql = "INSERT INTO {$BaseDatos_systema}.PAUSASMALLA (PAUSASMALLA_ConsInte__MALLATURNO_b,PAUSASMALLA_PausasId_b,PAUSASMALLA_Tipo_b,PAUSASMALLA_HorIniLun_b,PAUSASMALLA_HorFinLun_b,PAUSASMALLA_HorIniMar_b,PAUSASMALLA_HorFinMar_b,PAUSASMALLA_HorIniMie_b,PAUSASMALLA_HorFinMie_b,PAUSASMALLA_HorIniJue_b,PAUSASMALLA_HorFinJue_b,PAUSASMALLA_HorIniVie_b,PAUSASMALLA_HorFinVie_b,PAUSASMALLA_HorIniSab_b,PAUSASMALLA_HorFinSab_b,PAUSASMALLA_HorIniDom_b,PAUSASMALLA_HorFinDom_b,PAUSASMALLA_HorIniFes_b,PAUSASMALLA_HorFinFes_b) VALUES ({$id},{$arraybreakselect[$i]},'1','{$arraybreakHorIniLun[$i]}','$arraybreakHorFinLun[$i]','$arraybreakHorIniMar[$i]','$arraybreakHorFinMar[$i]','$arraybreakHorIniMie[$i]','$arraybreakHorFinMie[$i]','$arraybreakHorIniJue[$i]','$arraybreakHorFinJue[$i]','$arraybreakHorIniVie[$i]','$arraybreakHorFinVie[$i]'";

                            if ($arraybreakHorIniSab[$i] != '') {
                                $Lsql .= ",'{$arraybreakHorIniSab[$i]}'";
                            } else {
                                $Lsql .= ",null";
                            }

                            if ($arraybreakHorFinSab[$i] != '') {
                                $Lsql .= ",'{$arraybreakHorFinSab[$i]}'";
                            } else {
                                $Lsql .= ",null";
                            }

                            if ($arraybreakHorIniDom[$i] != '') {
                                $Lsql .= ",'{$arraybreakHorIniDom[$i]}'";
                            } else {
                                $Lsql .= ",null";
                            }

                            if ($arraybreakHorFinDom[$i] != '') {
                                $Lsql .= ",'{$arraybreakHorFinDom[$i]}'";
                            } else {
                                $Lsql .= ",null";
                            }

                            if ($arraybreakHorIniFes[$i] != '') {
                                $Lsql .= ",'{$arraybreakHorIniFes[$i]}'";
                            } else {
                                $Lsql .= ",null";
                            }

                            if ($arraybreakHorFinFes[$i] != '') {
                                $Lsql .= ",'{$arraybreakHorFinFes[$i]}'";
                            } else {
                                $Lsql .= ",null";
                            }

                            $Lsql .= ")";
                            // echo $Lsql.";<br>";
                            $Lsql = $mysqli->query($Lsql);
                            $i++;
                        }
                        echo json_encode(array('estado' => 'ok', 'mensaje' => 'Exito', "id_malla" => $id));
                    } else {
                        echo json_encode(array('estado' => 'error', 'mensaje' => $sql));
                    }
                }

                // ACÁ SE EJECUTA LA ACCIÓN ADIT, CUANDO EDITAMOS LA MALLA
                if ($_POST['operMalla'] == 'edit') {
                    if ($id_malla) {
                        // echo "edit->malla de turno <br><br> $id_malla";
                        $sql = "UPDATE {$BaseDatos_systema}.MALLATURNO SET MALLATURNO__Nombre_b='{$nombre}',MALLATURNO_HorIniLun_b='{$iniLunes}',MALLATURNO_HorFinLun_b='{$finLunes}',MALLATURNO_HorIniMar_b='{$iniMartes}',MALLATURNO_HorFinMar_b='{$finMartes}',MALLATURNO_HorIniMie_b='{$iniMiercoles}',MALLATURNO_HorFinMie_b='{$finMiercoles}',MALLATURNO_HorIniJue_b='{$iniJueves}',MALLATURNO_HorFinJue_b='{$finJueves}', MALLATURNO_HorIniVie_b='{$iniViernes}', MALLATURNO_HorFinVie_b='{$finViernes}'";

                        //validar que no sean nulos los horarios para los dias no obligatorios
                        $sql .= ($iniSabado && $iniSabado != '') ? ", MALLATURNO_HorIniSab_b='{$iniSabado}' " : ", MALLATURNO_HorIniSab_b=null ";
                        $sql .= ($finSabado && $finSabado != '') ? ", MALLATURNO_HorFinSab_b='{$finSabado}' " : ", MALLATURNO_HorFinSab_b=null ";
                        $sql .= ($iniDomingo && $iniDomingo != '') ? ", MALLATURNO_HorIniDom_b='{$iniDomingo}' " : ", MALLATURNO_HorIniDom_b=null ";
                        $sql .= ($finDomingo && $finDomingo != '') ? ", MALLATURNO_HorFinDom_b='{$finDomingo}' " : ", MALLATURNO_HorFinDom_b=null ";
                        $sql .= ($iniFestivo && $iniFestivo != '') ? ", MALLATURNO_HorIniFes_b='{$iniFestivo}' " : ", MALLATURNO_HorIniFes_b=null ";
                        $sql .= ($finFestivo && $finFestivo != '') ? ", MALLATURNO_HorFinFes_b='{$finFestivo}' " : ", MALLATURNO_HorFinFes_b=null ";

                        $sql .= "WHERE MALLATURNO_ConsInte__b={$id_malla}";
                        //  echo "edit->malla de turno => $sql <br><br> ";
                        if ($mysqli->query($sql)) {
                            //ACTUALIZAR HORARIO DE LAS PAUSAS POR DEFECTO
                            $i = 0;
                            while ($i < count($arraybreakselect)) {
                                $Lsql = "UPDATE {$BaseDatos_systema}.PAUSASMALLA SET PAUSASMALLA_HorIniLun_b='{$arraybreakHorIniLun[$i]}', PAUSASMALLA_HorFinLun_b='{$arraybreakHorFinLun[$i]}', PAUSASMALLA_HorIniMar_b='{$arraybreakHorIniMar[$i]}', PAUSASMALLA_HorFinMar_b='{$arraybreakHorFinMar[$i]}', PAUSASMALLA_HorIniMie_b='{$arraybreakHorIniMie[$i]}', PAUSASMALLA_HorFinMie_b='{$arraybreakHorFinMie[$i]}', PAUSASMALLA_HorIniJue_b='{$arraybreakHorIniJue[$i]}', PAUSASMALLA_HorFinJue_b='{$arraybreakHorFinJue[$i]}', PAUSASMALLA_HorIniVie_b='{$arraybreakHorIniVie[$i]}', PAUSASMALLA_HorFinVie_b='{$arraybreakHorFinVie[$i]}'";

                                // Validamos los horarios que lleguen vacios para ponerlos null en la BD
                                $Lsql .= ($arraybreakHorIniSab[$i] != '') ? ", PAUSASMALLA_HorIniSab_b='{$arraybreakHorIniSab[$i]}' " : ", PAUSASMALLA_HorIniSab_b=null " ;
                                $Lsql .= ($arraybreakHorFinSab[$i] != '') ? ", PAUSASMALLA_HorFinSab_b='{$arraybreakHorFinSab[$i]}' " : ", PAUSASMALLA_HorFinSab_b=null " ;
                                $Lsql .= ($arraybreakHorIniDom[$i] != '') ? ", PAUSASMALLA_HorIniDom_b='{$arraybreakHorIniDom[$i]}' " : ", PAUSASMALLA_HorIniDom_b=null " ;
                                $Lsql .= ($arraybreakHorFinDom[$i] != '') ? ", PAUSASMALLA_HorFinDom_b='{$arraybreakHorFinDom[$i]}' " : ", PAUSASMALLA_HorFinDom_b=null " ;
                                $Lsql .= ($arraybreakHorIniFes[$i] != '') ? ", PAUSASMALLA_HorIniFes_b='{$arraybreakHorIniFes[$i]}' " : ", PAUSASMALLA_HorIniFes_b=null " ;
                                $Lsql .= ($arraybreakHorFinFes[$i] != '') ? ", PAUSASMALLA_HorFinFes_b='{$arraybreakHorFinFes[$i]}' " : ", PAUSASMALLA_HorFinFes_b=null " ;

                                $Lsql .= "WHERE PAUSASMALLA_PausasId_b='{$arraybreakselect[$i]}' AND PAUSASMALLA_ConsInte__MALLATURNO_b={$id_malla}";
                                //echo $Lsql.";<br>";
                                $Lsql = $mysqli->query($Lsql);
                                $i++;
                            }

                            //VALIDAR Y ACTUALIZAR LA PAUSA PERSONALIZADA CON HORARIO FIJO
                            if ($arrayidUsuPauTipo1Predefinida && $arrayselectTipo1Predefinida && $arraypredefinidaPHorIniLun && $arraypredefinidaPHorFinLun && $arraypredefinidaPHorIniMar && $arraypredefinidaPHorFinMar && $arraypredefinidaPHorIniMie && $arraypredefinidaPHorFinMie && $arraypredefinidaPHorIniJue && $arraypredefinidaPHorFinJue && $arraypredefinidaPHorIniVie && $arraypredefinidaPHorFinVie && $arraypredefinidaPHorIniSab && $arraypredefinidaPHorFinSab && $arraypredefinidaPHorIniDom && $arraypredefinidaPHorFinDom && $arraypredefinidaPHorIniFes && $arraypredefinidaPHorFinFes) {
                                $j = 0;
                                while ($j < count($arraypredefinidaPHorIniLun)) {
                                    $Lsql = "UPDATE {$BaseDatos_systema}.PAUSASMALLA SET PAUSASMALLA_HorIniLun_b='{$arraypredefinidaPHorIniLun[$j]}', PAUSASMALLA_HorFinLun_b='{$arraypredefinidaPHorFinLun[$j]}', PAUSASMALLA_HorIniMar_b='{$arraypredefinidaPHorIniMar[$j]}', PAUSASMALLA_HorFinMar_b='{$arraypredefinidaPHorFinMar[$j]}', PAUSASMALLA_HorIniMie_b='{$arraypredefinidaPHorIniMie[$j]}', PAUSASMALLA_HorFinMie_b='{$arraypredefinidaPHorFinMie[$j]}', PAUSASMALLA_HorIniJue_b='{$arraypredefinidaPHorIniJue[$j]}', PAUSASMALLA_HorFinJue_b='{$arraypredefinidaPHorFinJue[$j]}', PAUSASMALLA_HorIniVie_b='{$arraypredefinidaPHorIniVie[$j]}', PAUSASMALLA_HorFinVie_b='{$arraypredefinidaPHorFinVie[$j]}'";

                                    // Validamos los horarios que lleguen vacios para ponerlos null en la BD
                                    $Lsql .= ($arraypredefinidaPHorIniSab[$j] != '') ? ", PAUSASMALLA_HorIniSab_b='{$arraypredefinidaPHorIniSab[$j]}' " : ", PAUSASMALLA_HorIniSab_b=null ";
                                    $Lsql .= ($arraypredefinidaPHorFinSab[$j] != '') ? ", PAUSASMALLA_HorFinSab_b='{$arraypredefinidaPHorFinSab[$j]}' " : ", PAUSASMALLA_HorFinSab_b=null ";
                                    $Lsql .= ($arraypredefinidaPHorIniDom[$j] != '') ? ", PAUSASMALLA_HorIniDom_b='{$arraypredefinidaPHorIniDom[$j]}' " : ", PAUSASMALLA_HorIniDom_b=null ";
                                    $Lsql .= ($arraypredefinidaPHorFinDom[$j] != '') ? ", PAUSASMALLA_HorFinDom_b='{$arraypredefinidaPHorFinDom[$j]}' " : ", PAUSASMALLA_HorFinDom_b=null ";
                                    $Lsql .= ($arraypredefinidaPHorIniFes[$j] != '') ? ", PAUSASMALLA_HorIniFes_b='{$arraypredefinidaPHorIniFes[$j]}' " : ", PAUSASMALLA_HorIniFes_b=null ";
                                    $Lsql .= ($arraypredefinidaPHorFinFes[$j] != '') ? ", PAUSASMALLA_HorFinFes_b='{$arraypredefinidaPHorFinFes[$j]}' " : ", PAUSASMALLA_HorFinFes_b=null ";

                                    $Lsql .= ",PAUSASMALLA_ConsInte__MALLATURNO_b={$id_malla},PAUSASMALLA_PausasId_b={$arrayselectTipo1Predefinida[$j]}";
                                    $Lsql .= " WHERE PAUSASMALLA_ConsInte__b='{$arrayidUsuPauTipo1Predefinida[$j]}'";
                                    // echo $Lsql.";<br>";
                                    $Lsql = $mysqli->query($Lsql);
                                    $j++;
                                }
                            }
                            if ($arrayidUsuPauTipo0Predefinida && $arrayselectTipo0Predefinida && $arraypredefinidaPCLun && $arraypredefinidaPDMLun && $arraypredefinidaPCMar && $arraypredefinidaPDMMar && $arraypredefinidaPCMie && $arraypredefinidaPDMMie && $arraypredefinidaPCJue && $arraypredefinidaPDMJue && $arraypredefinidaPCVie && $arraypredefinidaPDMVie && $arraypredefinidaPCSab && $arraypredefinidaPDMSab && $arraypredefinidaPCDom && $arraypredefinidaPDMDom && $arraypredefinidaPCFes && $arraypredefinidaPDMFes) {
                                $j = 0;
                                while ($j < count($arraypredefinidaPCLun)) {
                                    $Lsql = "UPDATE {$BaseDatos_systema}.PAUSASMALLA SET PAUSASMALLA_CanMaxLun_b='{$arraypredefinidaPCLun[$j]}', PAUSASMALLA_DurMaxLun_b='{$arraypredefinidaPDMLun[$j]}', PAUSASMALLA_CanMaxMar_b='{$arraypredefinidaPCMar[$j]}', PAUSASMALLA_DurMaxMar_b='{$arraypredefinidaPDMMar[$j]}', PAUSASMALLA_CanMaxMie_b='{$arraypredefinidaPCMie[$j]}', PAUSASMALLA_DurMaxMie_b='{$arraypredefinidaPDMMie[$j]}', PAUSASMALLA_CanMaxJue_b='{$arraypredefinidaPCJue[$j]}', PAUSASMALLA_DurMaxJue_b='{$arraypredefinidaPDMJue[$j]}', PAUSASMALLA_CanMaxVie_b='{$arraypredefinidaPCVie[$j]}', PAUSASMALLA_DurMaxVie_b='{$arraypredefinidaPDMVie[$j]}'";

                                    // Validamos los horarios que lleguen vacios para ponerlos null en la BD
                                    $Lsql .=  ($arraypredefinidaPCSab[$j] != '') ? ", PAUSASMALLA_CanMaxSab_b='{$arraypredefinidaPCSab[$j]}' "  : ", PAUSASMALLA_CanMaxSab_b=null " ;
                                    $Lsql .=  ($arraypredefinidaPDMSab[$j] != '') ? ", PAUSASMALLA_DurMaxSab_b='{$arraypredefinidaPDMSab[$j]}' " : ", PAUSASMALLA_DurMaxSab_b=null " ;
                                    $Lsql .=  ($arraypredefinidaPCDom[$j] != '') ? ", PAUSASMALLA_CanMaxDom_b='{$arraypredefinidaPCDom[$j]}' "  : ", PAUSASMALLA_CanMaxDom_b=null " ;
                                    $Lsql .=  ($arraypredefinidaPDMDom[$j] != '') ? ", PAUSASMALLA_DurMaxDom_b='{$arraypredefinidaPDMDom[$j]}' " : ", PAUSASMALLA_DurMaxDom_b=null " ;
                                    $Lsql .=  ($arraypredefinidaPCFes[$j] != '') ? ", PAUSASMALLA_CanMaxFes_b='{$arraypredefinidaPCFes[$j]}' "  : ", PAUSASMALLA_CanMaxFes_b=null " ;
                                    $Lsql .=  ($arraypredefinidaPDMFes[$j] != '') ? ", PAUSASMALLA_DurMaxFes_b='{$arraypredefinidaPDMFes[$j]}' " : ", PAUSASMALLA_DurMaxFes_b=null " ;

                                    $Lsql .= ",PAUSASMALLA_ConsInte__MALLATURNO_b={$id_malla},PAUSASMALLA_PausasId_b={$arrayselectTipo0Predefinida[$j]}";
                                    $Lsql .= " WHERE PAUSASMALLA_ConsInte__b='{$arrayidUsuPauTipo0Predefinida[$j]}'";
                                    // echo $Lsql.";<br>";
                                    $Lsql = $mysqli->query($Lsql);
                                    $j++;
                                }
                            }
                            actualizaragentes($id_malla, $arraybreakselect);
                            echo json_encode(array('estado' => 'ok', 'mensaje' => 'Exito', "id_malla" => $id_malla));
                        } else {
                            echo json_encode(array('estado' => 'Error', 'mensaje' => $sql));
                        }
                    } else {
                        echo json_encode(array('estado' => 'Error', 'mensaje' => 'No se identifico la malla de turnos'));
                    }
                }
            } else {
                echo json_encode(array('estado' => 'Error', 'mensaje' => 'Los datos enviados no son validos'));
            }
        } else {
            echo json_encode(array('estado' => 'Error', 'mensaje' => 'No se identifico el proyecto, inicia sesión nuevamente'));
        }
    }
    
    // actulizar malla cuando se guarda desde el boton de guardar
    if(isset($_GET['actulizarMalla'])){

        $objJson = json_decode(stripslashes($_GET['actulizarMalla']));

        $arrPausas = [$objJson->breakselectDefecto1, $objJson->breakselectDefecto2, $objJson->breakselectDefecto3];
        
        $result = actualizaragentes($objJson->id_malla, $arrPausas);

        echo json_encode($result);
    }

    if(isset($_POST['renderMalla'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        if($id){
            $sql=$mysqli->query("select * from {$BaseDatos_systema}.MALLATURNO where MALLATURNO_ConsInte__b={$id}");
            if($sql){
                $data=array();
                while($malla=$sql->fetch_object()){
                    $data['nombreMalla']=$malla->MALLATURNO__Nombre_b;
                    $data['id_malla'] = $malla->MALLATURNO_ConsInte__b;
                    $data['HorIniLunDefecto']=$malla->MALLATURNO_HorIniLun_b;
                    $data['HorFinLunDefecto']=$malla->MALLATURNO_HorFinLun_b;
                    $data['HorIniMarDefecto']=$malla->MALLATURNO_HorIniMar_b;
                    $data['HorFinMarDefecto']=$malla->MALLATURNO_HorFinMar_b;
                    $data['HorIniMieDefecto']=$malla->MALLATURNO_HorIniMie_b;
                    $data['HorFinMieDefecto']=$malla->MALLATURNO_HorFinMie_b;
                    $data['HorIniJueDefecto']=$malla->MALLATURNO_HorIniJue_b;
                    $data['HorFinJueDefecto']=$malla->MALLATURNO_HorFinJue_b;
                    $data['HorIniVieDefecto']=$malla->MALLATURNO_HorIniVie_b;
                    $data['HorFinVieDefecto']=$malla->MALLATURNO_HorFinVie_b;
                    $data['HorIniSabDefecto']=$malla->MALLATURNO_HorIniSab_b;
                    $data['HorFinSabDefecto']=$malla->MALLATURNO_HorFinSab_b;
                    $data['HorIniDomDefecto']=$malla->MALLATURNO_HorIniDom_b;
                    $data['HorFinDomDefecto']=$malla->MALLATURNO_HorFinDom_b;
                    $data['HorIniFesDefecto']=$malla->MALLATURNO_HorIniFes_b;
                    $data['HorFinFesDefecto']=$malla->MALLATURNO_HorFinFes_b;
                }
                $sqlPausas=$mysqli->query("select pausa_por_defecto_1,pausa_por_defecto_2,pausa_por_defecto_3 from dyalogo_general.huespedes where id = {$_SESSION['HUESPED']}");
                if($sqlPausas && $sqlPausas->num_rows>0){
                    $sqlPausas=$sqlPausas->fetch_object();
                    $intPausa1=$sqlPausas->pausa_por_defecto_1;
                    $intPausa2=$sqlPausas->pausa_por_defecto_2;
                    $intPausa3=$sqlPausas->pausa_por_defecto_3;
                    
                    $sqlHorarioPausas=$mysqli->query("SELECT * FROM {$BaseDatos_systema}.PAUSASMALLA WHERE PAUSASMALLA_PausasId_b IN($intPausa1,$intPausa2,$intPausa3) AND PAUSASMALLA_ConsInte__MALLATURNO_b={$id}");
                    if($sqlHorarioPausas && $sqlHorarioPausas->num_rows>0){
                        $intConteo=1;
                        while($objPausa = $sqlHorarioPausas->fetch_object()){
                            if($intConteo == 1){
                                $data['breakHorIniLun1Defecto']=$objPausa->PAUSASMALLA_HorIniLun_b;
                                $data['breakHorFinLun1Defecto']=$objPausa->PAUSASMALLA_HorFinLun_b;
                                $data['breakHorIniMar1Defecto']=$objPausa->PAUSASMALLA_HorIniMar_b;
                                $data['breakHorFinMar1Defecto']=$objPausa->PAUSASMALLA_HorFinMar_b;
                                $data['breakHorIniMie1Defecto']=$objPausa->PAUSASMALLA_HorIniMie_b;
                                $data['breakHorFinMie1Defecto']=$objPausa->PAUSASMALLA_HorFinMie_b;
                                $data['breakHorIniJue1Defecto']=$objPausa->PAUSASMALLA_HorIniJue_b;
                                $data['breakHorFinJue1Defecto']=$objPausa->PAUSASMALLA_HorFinJue_b;
                                $data['breakHorIniVie1Defecto']=$objPausa->PAUSASMALLA_HorIniVie_b;
                                $data['breakHorFinVie1Defecto']=$objPausa->PAUSASMALLA_HorFinVie_b;
                                $data['breakHorIniSab1Defecto']=$objPausa->PAUSASMALLA_HorIniSab_b;
                                $data['breakHorFinSab1Defecto']=$objPausa->PAUSASMALLA_HorFinSab_b;
                                $data['breakHorIniDom1Defecto']=$objPausa->PAUSASMALLA_HorIniDom_b;
                                $data['breakHorFinDom1Defecto']=$objPausa->PAUSASMALLA_HorFinDom_b;
                                $data['breakHorIniFes1Defecto']=$objPausa->PAUSASMALLA_HorIniFes_b;
                                $data['breakHorFinFes1Defecto']=$objPausa->PAUSASMALLA_HorFinFes_b;
                            }
                            
                            if($intConteo == 2){
                                $data['breakHorIniLun2Defecto']=$objPausa->PAUSASMALLA_HorIniLun_b;
                                $data['breakHorFinLun2Defecto']=$objPausa->PAUSASMALLA_HorFinLun_b;
                                $data['breakHorIniMar2Defecto']=$objPausa->PAUSASMALLA_HorIniMar_b;
                                $data['breakHorFinMar2Defecto']=$objPausa->PAUSASMALLA_HorFinMar_b;
                                $data['breakHorIniMie2Defecto']=$objPausa->PAUSASMALLA_HorIniMie_b;
                                $data['breakHorFinMie2Defecto']=$objPausa->PAUSASMALLA_HorFinMie_b;
                                $data['breakHorIniJue2Defecto']=$objPausa->PAUSASMALLA_HorIniJue_b;
                                $data['breakHorFinJue2Defecto']=$objPausa->PAUSASMALLA_HorFinJue_b;
                                $data['breakHorIniVie2Defecto']=$objPausa->PAUSASMALLA_HorIniVie_b;
                                $data['breakHorFinVie2Defecto']=$objPausa->PAUSASMALLA_HorFinVie_b;
                                $data['breakHorIniSab2Defecto']=$objPausa->PAUSASMALLA_HorIniSab_b;
                                $data['breakHorFinSab2Defecto']=$objPausa->PAUSASMALLA_HorFinSab_b;
                                $data['breakHorIniDom2Defecto']=$objPausa->PAUSASMALLA_HorIniDom_b;
                                $data['breakHorFinDom2Defecto']=$objPausa->PAUSASMALLA_HorFinDom_b;
                                $data['breakHorIniFes2Defecto']=$objPausa->PAUSASMALLA_HorIniFes_b;
                                $data['breakHorFinFes2Defecto']=$objPausa->PAUSASMALLA_HorFinFes_b;    
                            }
                            
                            if($intConteo == 3){
                                $data['breakHorIniLun3Defecto']=$objPausa->PAUSASMALLA_HorIniLun_b;
                                $data['breakHorFinLun3Defecto']=$objPausa->PAUSASMALLA_HorFinLun_b;
                                $data['breakHorIniMar3Defecto']=$objPausa->PAUSASMALLA_HorIniMar_b;
                                $data['breakHorFinMar3Defecto']=$objPausa->PAUSASMALLA_HorFinMar_b;
                                $data['breakHorIniMie3Defecto']=$objPausa->PAUSASMALLA_HorIniMie_b;
                                $data['breakHorFinMie3Defecto']=$objPausa->PAUSASMALLA_HorFinMie_b;
                                $data['breakHorIniJue3Defecto']=$objPausa->PAUSASMALLA_HorIniJue_b;
                                $data['breakHorFinJue3Defecto']=$objPausa->PAUSASMALLA_HorFinJue_b;
                                $data['breakHorIniVie3Defecto']=$objPausa->PAUSASMALLA_HorIniVie_b;
                                $data['breakHorFinVie3Defecto']=$objPausa->PAUSASMALLA_HorFinVie_b;
                                $data['breakHorIniSab3Defecto']=$objPausa->PAUSASMALLA_HorIniSab_b;
                                $data['breakHorFinSab3Defecto']=$objPausa->PAUSASMALLA_HorFinSab_b;
                                $data['breakHorIniDom3Defecto']=$objPausa->PAUSASMALLA_HorIniDom_b;
                                $data['breakHorFinDom3Defecto']=$objPausa->PAUSASMALLA_HorFinDom_b;
                                $data['breakHorIniFes3Defecto']=$objPausa->PAUSASMALLA_HorIniFes_b;
                                $data['breakHorFinFes3Defecto']=$objPausa->PAUSASMALLA_HorFinFes_b;
                            }
                            $intConteo++;
                        }
                    }
                }
                echo json_encode(array('estado'=>'ok','mensaje'=>$data, "id_malla" => $id));
            }else{
                echo json_encode(array('estado'=>'error','mensaje'=>'No se pudo cargar la malla de turnos'));
            }
        }else{
            echo json_encode(array('estado'=>'error','mensaje'=>'No se identifico la malla de turnos'));
        }
    }
    
    // EVENTOS EN LA MALLA DE TURNOS
    if( isset($_POST['eliminarFilaPausaPredefinida'])){
        $Lsql = "DELETE FROM ".$BaseDatos_systema.".PAUSASMALLA WHERE PAUSASMALLA_ConsInte__b =".$_POST['index'];
        if ($mysqli->query($Lsql) == TRUE) {
            $sql=$mysqli->query("DELETE FROM {$BaseDatos_systema}.USUPAU WHERE USUPAU_IdPausaMalla_B={$_POST['index']}");
             echo json_encode(true);             
        }
    }
    
    if(isset($_POST['agregarFilaPausaPredefinida'])){
            $arrayTipoPausa = array();
            $ultimoId=0;
            $status='lleno';
            $i=0;
            if(  $_POST['tipo'] == '1'){            
                $Lsql = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = ".$_POST['tipo']." AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '".$_SESSION['HUESPED']."') AND  id  NOT IN('".$_POST['pausa1']."','".$_POST['pausa2']."','".$_POST['pausa3']."')";           
            }
        
            if(  $_POST['tipo'] == '0'){            
                $Lsql = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = ".$_POST['tipo']." AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '".$_SESSION['HUESPED']."') ";             
            }

            if(  ($result= $mysqli->query($Lsql)) == TRUE ){
                if($result->num_rows > 0){
                    while ($key = $result->fetch_object()) {
                        $cadena=$key->tipo;

                        if (strpos($cadena, '-') !== false) {
                                $tipo = explode("-", $cadena);
                                $cadena =$tipo[1];
                        }

                        $arrayTipoPausa[$i]['id'] = $key->id;
                        $arrayTipoPausa[$i]['tipo'] = $cadena;
                        $i++;
                    }
                    $Lsql = "INSERT INTO ".$BaseDatos_systema.".PAUSASMALLA (PAUSASMALLA_Tipo_b) values (".$_POST['tipo'].")"; 
                    if($mysqli->query($Lsql) == TRUE){
                        $ultimoId = $mysqli->insert_id;
                    }                   
                }else{
                   $status='vacio';
                }
            } 
            echo json_encode($arrayData = array('ultimoId' => $ultimoId,'tipoPausa'=>$arrayTipoPausa,'status'=>$status));
        }
    
    if(isset($_GET['cargarPausasPredefinida']) && $_GET['cargarPausasPredefinida'] == 'si'){
        $datosUsuPau1 = array();
        $datosUsuPau0 = array();
        $arrayTipoPausa = array();
        if($_POST['tipo'] == '1' && $_POST['idMalla'] != '' ){
            $Lsql= "SELECT * FROM {$BaseDatos_systema}.PAUSASMALLA WHERE PAUSASMALLA_ConsInte__MALLATURNO_b = {$_POST['idMalla']} AND PAUSASMALLA_Tipo_b = {$_POST['tipo']} AND  PAUSASMALLA_PausasId_b  NOT IN('{$_POST['pausa1']}','{$_POST['pausa2']}','{$_POST['pausa3']}')";
            $result = $mysqli->query($Lsql);            
            $j = 0;

            while ($key = $result->fetch_object()) {
                $datosUsuPau1[$j]['PAUSASMALLA_ConsInte__b'] = $key->PAUSASMALLA_ConsInte__b;
                $datosUsuPau1[$j]['PAUSASMALLA_PausasId_b']  = $key->PAUSASMALLA_PausasId_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniLun_b'] = $key->PAUSASMALLA_HorIniLun_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinLun_b'] = $key->PAUSASMALLA_HorFinLun_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniMar_b'] = $key->PAUSASMALLA_HorIniMar_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinMar_b'] = $key->PAUSASMALLA_HorFinMar_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniMie_b'] = $key->PAUSASMALLA_HorIniMie_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinMie_b'] = $key->PAUSASMALLA_HorFinMie_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniJue_b'] = $key->PAUSASMALLA_HorIniJue_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinJue_b'] = $key->PAUSASMALLA_HorFinJue_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniVie_b'] = $key->PAUSASMALLA_HorIniVie_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinVie_b'] = $key->PAUSASMALLA_HorFinVie_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniSab_b'] = $key->PAUSASMALLA_HorIniSab_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinSab_b'] = $key->PAUSASMALLA_HorFinSab_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniDom_b'] = $key->PAUSASMALLA_HorIniDom_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinDom_b'] = $key->PAUSASMALLA_HorFinDom_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorIniFes_b'] = $key->PAUSASMALLA_HorIniFes_b;
                $datosUsuPau1[$j]['PAUSASMALLA_HorFinFes_b'] = $key->PAUSASMALLA_HorFinFes_b;
                $j++;
            }
            $consulta = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = ".$_POST['tipo']." AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '".$_SESSION['HUESPED']."') AND  id  NOT IN('".$_POST['pausa1']."','".$_POST['pausa2']."','".$_POST['pausa3']."')";
            // echo "<br><br> consulta => {$consulta} <br>";
            $i = 0;
            if(($result = $mysqli->query($consulta)) == TRUE ){
                
                while ($key = $result->fetch_object()) {
    
                    $cadena=$key->tipo;
    
                    if (strpos($cadena, '-') !== false) {
                         $tipo = explode("-", $cadena);
                         $cadena =$tipo[1];
                    }
                   
                    $arrayTipoPausa[$i]['id'] = $key->id;
                    $arrayTipoPausa[$i]['tipo'] = $cadena;
                    $i++;
                }
            }
        }
        if($_POST['tipo'] == '0' && $_POST['idMalla'] != ''){
            $Lsql= "SELECT * FROM ".$BaseDatos_systema.".PAUSASMALLA WHERE PAUSASMALLA_ConsInte__MALLATURNO_b = {$_POST['idMalla']} AND PAUSASMALLA_Tipo_b = {$_POST['tipo']}";
            $result = $mysqli->query($Lsql);            
            $k = 0;
            while ($key = $result->fetch_object()) {

                $datosUsuPau0[$k]['PAUSASMALLA_ConsInte__b'] = $key->PAUSASMALLA_ConsInte__b;
                $datosUsuPau0[$k]['PAUSASMALLA_PausasId_b']  = $key->PAUSASMALLA_PausasId_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxLun_b'] = $key->PAUSASMALLA_DurMaxLun_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxLun_b'] = $key->PAUSASMALLA_CanMaxLun_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxMar_b'] = $key->PAUSASMALLA_DurMaxMar_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxMar_b'] = $key->PAUSASMALLA_CanMaxMar_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxMie_b'] = $key->PAUSASMALLA_DurMaxMie_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxMie_b'] = $key->PAUSASMALLA_CanMaxMie_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxJue_b'] = $key->PAUSASMALLA_DurMaxJue_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxJue_b'] = $key->PAUSASMALLA_CanMaxJue_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxVie_b'] = $key->PAUSASMALLA_DurMaxVie_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxVie_b'] = $key->PAUSASMALLA_CanMaxVie_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxSab_b'] = $key->PAUSASMALLA_DurMaxSab_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxSab_b'] = $key->PAUSASMALLA_CanMaxSab_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxDom_b'] = $key->PAUSASMALLA_DurMaxDom_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxDom_b'] = $key->PAUSASMALLA_CanMaxDom_b;
                $datosUsuPau0[$k]['PAUSASMALLA_DurMaxFes_b'] = $key->PAUSASMALLA_DurMaxFes_b;
                $datosUsuPau0[$k]['PAUSASMALLA_CanMaxFes_b'] = $key->PAUSASMALLA_CanMaxFes_b;
                $k++;
            }
            $ZLsql = "SELECT id,tipo FROM dyalogo_telefonia.dy_tipos_descanso WHERE tipo_pausa = ".$_POST['tipo']." AND  id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped= '".$_SESSION['HUESPED']."')";
            
            // echo "<br><br> ZLsql => {$ZLsql} <br>";
            $i = 0;
            if(($result = $mysqli->query($ZLsql)) == TRUE ){
                
                while ($key = $result->fetch_object()) {
    
                    $cadena=$key->tipo;
    
                    if (strpos($cadena, '-') !== false) {
                         $tipo = explode("-", $cadena);
                         $cadena =$tipo[1];
                    }
                   
                    $arrayTipoPausa[$i]['id'] = $key->id;
                    $arrayTipoPausa[$i]['tipo'] = $cadena;
                    $i++;
                }
            }
        }
        echo json_encode($arrayDatos = array('datosUsuPau1' =>  $datosUsuPau1,'datosUsuPau0' =>  $datosUsuPau0,'tipoPausas'=>$arrayTipoPausa));
    }
    
    if(isset($_POST['deleteMalla'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        if($id){
            $deleteUsupau=$mysqli->query("DELETE {$BaseDatos_systema}.USUPAU FROM {$BaseDatos_systema}.USUPAU JOIN {$BaseDatos_systema}.PAUSASMALLA ON USUPAU_IdPausaMalla_B=PAUSASMALLA_PausasId_b WHERE PAUSASMALLA_ConsInte__MALLATURNO_b={$id}");
            if($deleteUsupau){
                $deletePausas=$mysqli->query("DELETE FROM {$BaseDatos_systema}.PAUSASMALLA WHERE PAUSASMALLA_ConsInte__MALLATURNO_b={$id}");
                if($deletePausas){
                    $deleteMalla=$mysqli->query("DELETE FROM {$BaseDatos_systema}.MALLATURNO WHERE MALLATURNO_ConsInte__b={$id}");
                    if($deleteMalla){
                        echo json_encode(array('estado'=>'ok','mensaje'=>'Malla de turnos eliminada con éxito'));
                    }
                }
            }
        }
    }
    
    // CARGAR, AGREGAR Y QUITAR AGENTES DE LA MALLA DE TURNO PREDEFINIDA
    if(isset($_GET['agregarAgentes'])){
        $arrAgentes = $_POST['arrAgentes'];
        $idMalla=isset($_POST['idMalla']) ? $_POST['idMalla'] :false;
        if($idMalla){
            for ($i=0; $i < count($arrAgentes); $i++) {
                $LsqlInsert="UPDATE {$BaseDatos_systema}.USUARI SET USUARI_IdMalla_b={$idMalla} WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '".$arrAgentes[$i]."'";
                if($mysqli->query($LsqlInsert) === true){
                    $estado = 'ok';
                } else  {
                    $estado = 'error';
                    break;
                }
            }

            echo json_encode(['estado' => $estado]);
        }
    }        

    if(isset($_GET['quitarAgentes'])){
        $arrAgentes = $_POST['arrAgentes'];
        for ($i=0; $i < count($arrAgentes); $i++) {
            $LsqlInsert="UPDATE {$BaseDatos_systema}.USUARI SET USUARI_IdMalla_b=0 WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '".$arrAgentes[$i]."'";
            if($mysqli->query($LsqlInsert) === true){
                $estado = 'ok';
            } else  {
                $estado = 'error';
                break;
            }
        }

        echo json_encode(['estado' => $estado]);
    }
    
    if(isset($_POST['cargarAgentesMalla'])){
        $idMalla=isset($_POST['idMalla']) ? $_POST['idMalla'] :false;
        $listaAgentesNo='';
        $listaAgentesSi='';        
        if($idMalla && isset($_SESSION['HUESPED'])){
            $agentes=$mysqli->query("SELECT USUARI_ConsInte__b, USUARI_Nombre____b,USUARI_IdMalla_b from {$BaseDatos_systema}.USUARI WHERE USUARI_ConsInte__PROYEC_b={$_SESSION['HUESPED']} AND USUARI_Cargo_____b='agente' AND USUARI_Bloqueado_b=0 AND USUARI_Eliminado_b=0");
            if($agentes && $agentes ->num_rows>0){
                while($agente=$agentes->fetch_object()){
                    if($agente->USUARI_IdMalla_b==$idMalla){
                        $listaAgentesSi .= "<li data-id='".url::urlSegura($agente->USUARI_ConsInte__b)."'><table class='table table-hover'><tr><td width='40px'><input type='checkbox' class='flat-red mi-check'></td><td class='nombre'>{$agente->USUARI_Nombre____b}</td></tr></table></li>";
                    }else{
                        $listaAgentesNo .= "<li data-id='".url::urlSegura($agente->USUARI_ConsInte__b)."'><table class='table table-hover'><tr><td width='40px'><input type='checkbox' class='flat-red mi-check'></td><td class='nombre'>{$agente->USUARI_Nombre____b}</td></tr></table></li>";
                    }
                }
            }
            $datos['listaAgentesSi']=$listaAgentesSi;
            $datos['listaAgentesNo']=$listaAgentesNo;
            echo json_encode(array('estado'=>'ok','agentes'=>$datos));
        }
    }
    # secalcula la prioridad de las campañas de salidad
    function calcularPrioridad($data)
    {

        foreach ($data as $clave => $fila) {
            $prioridad[$clave] = intval($fila['prioridad']);
        }

        return max($prioridad) + 1;
    }

    // Retornamos la lista de campañas saientes asignadas y no asignadas por pate del usuario
    if(isset($_GET['traerTareas'])){
        
        $huespedId = $_POST['huespedId'];
        $usuarioId = $_POST['usuarioId'];
        $sentido = $_POST['sentido'];

        $tipoCampana = 0;

        if($sentido == 'saliente'){
            $tipoCampana = 6;
        }
        if($sentido == 'entrante'){
            $tipoCampana = 1;
        }

        $arrCampanasNoAsignadas = [];
        $arrCampanasAsignadas = [];

        // Traigo la informacion del usuario
        $sqlUsuario = "SELECT USUARI_ConsInte__b AS id, USUARI_UsuaCBX___b AS cbxId FROM {$BaseDatos_systema}.USUARI WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '{$usuarioId}' LIMIT 1";
        $resUsuario = $mysqli->query($sqlUsuario);
        $usuario = $resUsuario->fetch_object();

        // Campanas no asignadas al usuario
        $sql = "SELECT c.ESTRAT_ConsInte__b as estrategiaId, c.ESTRAT_Nombre____b as estrategiaNombre, a.CAMPAN_ConsInte__b campanId, CAMPAN_Nombre____b campanNombre, d.ASITAR_ConsInte__b as asitarId
            FROM DYALOGOCRM_SISTEMA.CAMPAN a
                INNER JOIN dyalogo_telefonia.dy_campanas f ON f.id = a.CAMPAN_IdCamCbx__b
                INNER JOIN DYALOGOCRM_SISTEMA.ESTPAS b ON b.ESTPAS_ConsInte__CAMPAN_b = a.CAMPAN_ConsInte__b 
                INNER JOIN DYALOGOCRM_SISTEMA.ESTRAT c ON c.ESTRAT_ConsInte__b = b.ESTPAS_ConsInte__ESTRAT_b
                INNER JOIN (SELECT * FROM dyalogo_general.huespedes_usuarios WHERE id_usuario = {$usuario->cbxId}) AS e ON e.id_huesped = c.ESTRAT_ConsInte__PROYEC_b
                LEFT JOIN (SELECT * FROM DYALOGOCRM_SISTEMA.ASITAR WHERE ASITAR_ConsInte__USUARI_b = {$usuario->id}) AS d ON a.CAMPAN_ConsInte__b = d.ASITAR_ConsInte__CAMPAN_b
            WHERE b.ESTPAS_Tipo______b = {$tipoCampana}
            AND d.ASITAR_ConsInte__b IS NULL
            ORDER BY c.ESTRAT_Nombre____b, a.CAMPAN_Nombre____b";

        $res = $mysqli->query($sql);
        
        if($res && $res->num_rows > 0){            
            while($row = $res->fetch_object()){
                $arrCampanasNoAsignadas[] = $row;
            }
        }

        // Campanas asignadas al usuario
        $sql = "SELECT c.ESTRAT_ConsInte__b as estrategiaId, c.ESTRAT_Nombre____b as estrategiaNombre, a.CAMPAN_ConsInte__b campanId, CAMPAN_Nombre____b campanNombre, d.ASITAR_ConsInte__b as asitarId, d.ASITAR_Prioridad_b AS prioridad
            FROM DYALOGOCRM_SISTEMA.CAMPAN a
                INNER JOIN DYALOGOCRM_SISTEMA.ESTPAS b ON b.ESTPAS_ConsInte__CAMPAN_b = a.CAMPAN_ConsInte__b 
                INNER JOIN DYALOGOCRM_SISTEMA.ESTRAT c ON c.ESTRAT_ConsInte__b = b.ESTPAS_ConsInte__ESTRAT_b
                LEFT JOIN (SELECT * FROM DYALOGOCRM_SISTEMA.ASITAR WHERE ASITAR_ConsInte__USUARI_b = {$usuario->id}) AS d ON a.CAMPAN_ConsInte__b = d.ASITAR_ConsInte__CAMPAN_b
            WHERE b.ESTPAS_Tipo______b = {$tipoCampana}
            AND d.ASITAR_ConsInte__b IS NOT NULL";

        $res = $mysqli->query($sql);


        if ($sql && mysqli_num_rows($res) > 0) {
            $contador = 1;
            $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $prioridadMax = calcularPrioridad($result); # Devuelvo el max number de prioridad, aumentado
            
            foreach ($result as $value) {
                # si la prioridad es null, aumento el numero máximo de prioridad y le asigno como prioridad el numero máximo aumentado
                $value['prioridad'] = $value['prioridad'] === null ? $prioridadMax ++ : $value['prioridad'];
                $arrCampanasAsignadas[] = $value;
            }
            # devuelvo el array de las campañas, ordenado de menor a mayor
            $prioridad  = array_column($arrCampanasAsignadas, 'prioridad');
            array_multisort($prioridad, SORT_ASC, $arrCampanasAsignadas);

        }

        echo json_encode([
            "campanasNoAsignadas" => $arrCampanasNoAsignadas,
            "campanasAsignadas" => $arrCampanasAsignadas,
        ]);
    }

    // Guardamos las prioridades de las campanas
    if(isset($_GET['guardarPrioridades'])){

        $sentido = $_POST['sentido'];
        $prioridad = $_POST['prioridad'];
        $arrCampanas = $_POST['arrCampanasSeleccionadas'];
        $usuarioId = $_POST['usuarioId'];

        if($sentido == 'saliente'){
            $tipoCampana = 6;
        }
        if($sentido == 'entrante'){
            $tipoCampana = 1;
        }

        $exito = true;
        $msgError = '';

        // Valido que hayan campanas
        if(count($arrCampanas) > 0){

            // Traigo agente id
            $sqlUsuario = "SELECT a.USUARI_ConsInte__b AS id, a.USUARI_UsuaCBX___b AS idCbx, b.id AS agenteId FROM {$BaseDatos_systema}.USUARI a
                JOIN dyalogo_telefonia.dy_agentes b ON a.USUARI_UsuaCBX___b = b.id_usuario_asociado
            WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '{$usuarioId}' LIMIT 1";
            $resUsuario = $mysqli->query($sqlUsuario);

            $agenteId = 0;
            $usuarioCbx = 0;
            $usuarioIdDecript = 0;

            if($resUsuario && $resUsuario->num_rows > 0){
                $dataUsuario = $resUsuario->fetch_object();
                $usuarioIdDecript = $dataUsuario->id;
                $agenteId = $dataUsuario->agenteId;
                $usuarioCbx = $dataUsuario->idCbx;
            }

            if($sentido == 'saliente'){

                // Inicio el proceso de ordenamiento para las salientes
                $sql = "SELECT c.ESTRAT_ConsInte__b, c.ESTRAT_Nombre____b, c.ESTRAT_ConsInte__PROYEC_b,
                    b.ESTPAS_ConsInte__b, b.ESTPAS_Comentari_b, b.ESTPAS_Tipo______b,
                    a.CAMPAN_ConsInte__b AS campanaId
                    FROM DYALOGOCRM_SISTEMA.CAMPAN a
                        INNER JOIN DYALOGOCRM_SISTEMA.ESTPAS b ON b.ESTPAS_ConsInte__CAMPAN_b = a.CAMPAN_ConsInte__b 
                        INNER JOIN DYALOGOCRM_SISTEMA.ESTRAT c ON c.ESTRAT_ConsInte__b = b.ESTPAS_ConsInte__ESTRAT_b
                        LEFT JOIN (SELECT * FROM DYALOGOCRM_SISTEMA.ASITAR WHERE ASITAR_ConsInte__USUARI_b = {$usuarioIdDecript}) AS d ON a.CAMPAN_ConsInte__b = d.ASITAR_ConsInte__CAMPAN_b
                    WHERE b.ESTPAS_Tipo______b = {$tipoCampana}
                    AND d.ASITAR_ConsInte__b IS NOT NULL
                ORDER BY d.ASITAR_Prioridad_b";

                $resOrden = $mysqli->query($sql);
                if($resOrden && $resOrden->num_rows > 0){

                    $array1 = [];

                    while($row = $resOrden->fetch_object()){

                        if(!in_array($row->campanaId, $arrCampanas)){
                            $array1[] = $row->campanaId;
                        }

                    }

                    // Procedo a ordernar
                    array_splice($array1, $prioridad - 1, 0, $arrCampanas);

                    // Ahora actualizo los registros con el nuevo orden
                    for ($i=0; $i < count($array1); $i++) { 
                    
                        $campanaId = $array1[$i];
                        
                        // Traigo el id de campanaCbx
                        $sqlCampanaCbx = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS campanaCbx FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$campanaId} LIMIT 1";
                        $resCampanaCbx = $mysqli->query($sqlCampanaCbx);

                        if($resCampanaCbx && $resCampanaCbx->num_rows > 0){
                            $dataCampana = $resCampanaCbx->fetch_object();
                        }

                        $orden = $i+1;
                        // actualizo la prioridad en asitar
                        $sql = "UPDATE {$BaseDatos_systema}.ASITAR SET ASITAR_Prioridad_b = {$orden} WHERE ASITAR_ConsInte__CAMPAN_b = {$campanaId} AND ASITAR_ConsInte__USUARI_b = {$usuarioIdDecript}";
                        if($mysqli->query($sql) !== true){
                            $exito = false;
                            $msgError = "Se presento un error al guardar asitar " . $mysqli->error;
                        }
                        
                        // Guardo la prioridad en dy_campanas_agentes
                        $sql = "UPDATE {$BaseDatos_telefonia}.dy_campanas_agentes SET prioridad = {$orden} WHERE id_campana = {$dataCampana->campanaCbx} AND id_agente = {$agenteId}";
                        if($mysqli->query($sql) !== true){
                            $exito = false;
                            $msgError = "Se presento un error al guardar campaña agentes " . $mysqli->error;
                        }
                        
                    }

                }
            }

            if($sentido == 'entrante'){
                // Al ser entrate solo tengo que cambiar la prioridad
                for ($i=0; $i < count($arrCampanas); $i++) { 
                    
                    $campanaId = $arrCampanas[$i];

                    
                    // Traihgo el id de campanaCbx
                    $sqlCampanaCbx = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS campanaCbx FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$campanaId} LIMIT 1";
                    $resCampanaCbx = $mysqli->query($sqlCampanaCbx);

                    if($resCampanaCbx && $resCampanaCbx->num_rows > 0){
                        $dataCampana = $resCampanaCbx->fetch_object();
                    }

                    // actualizo la prioridad en asitar
                    $sql = "UPDATE {$BaseDatos_systema}.ASITAR SET ASITAR_Prioridad_b = {$prioridad} WHERE ASITAR_ConsInte__CAMPAN_b = {$campanaId} AND ASITAR_ConsInte__USUARI_b = {$usuarioIdDecript}";
                    if($mysqli->query($sql) !== true){
                        $exito = false;
                        $msgError = "Se presento un error al guardar asitar " . $mysqli->error;
                    }

                    // Guardo la prioridad en dy_campanas_agentes
                    $sql = "UPDATE {$BaseDatos_telefonia}.dy_campanas_agentes SET prioridad = {$prioridad} WHERE id_campana = {$dataCampana->campanaCbx} AND id_agente = {$agenteId}";
                    if($mysqli->query($sql) !== true){
                        $exito = false;
                        $msgError = "Se presento un error al guardar campaña agentes " . $mysqli->error;
                    }
                }
            }
        }

        echo json_encode([
            "estado" => $exito,
            "msgError" => $msgError
        ]);
    }

    if(isset($_GET['agregarCampanasUsuario'])){

        $arrCampanas = $_POST['arrCampanas'];
        $sentido = $_POST['sentido'];
        $prioridad = $_POST['prioridad'];
        $usuario = $_POST['usuarioId'];

        if($sentido == 'saliente'){
            $tipoCampana = 6;
        }
        if($sentido == 'entrante'){
            $tipoCampana = 1;
        }

        $exito = true;
        $msgError = '';

        if(count($arrCampanas) > 0){

            // Traigo la informacion del usuario
            
            $sqlUsuario = "SELECT a.USUARI_ConsInte__b AS id, a.USUARI_UsuaCBX___b AS idCbx, b.id AS agenteId FROM {$BaseDatos_systema}.USUARI a
                JOIN dyalogo_telefonia.dy_agentes b ON a.USUARI_UsuaCBX___b = b.id_usuario_asociado
            WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '{$usuario}' LIMIT 1";
            $resUsuario = $mysqli->query($sqlUsuario);

            $usuarioId = 0;
            $usuarioCbx = 0;
            $agenteId = 0;

            if($resUsuario && $resUsuario->num_rows > 0){
                $dataUsuario = $resUsuario->fetch_object();
                $usuarioId = $dataUsuario->id;
                $usuarioCbx = $dataUsuario->idCbx;
                $agenteId = $dataUsuario->agenteId;
            }

            // Recorro todas las campanas
            for ($i=0; $i < count($arrCampanas); $i++) { 
                
                $campanaId = $arrCampanas[$i];

                $campanaCbxId = 0;
                
                // Traigo el id de campanaCbx
                $sqlCampanaCbx = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS campanaCbx FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$campanaId} LIMIT 1";
                $resCampanaCbx = $mysqli->query($sqlCampanaCbx);

                if($resCampanaCbx && $resCampanaCbx->num_rows > 0){
                    $dataCampana = $resCampanaCbx->fetch_object();
                    $campanaCbxId = $dataCampana->campanaCbx;
                }

                $prioridadMaxima = 1;

                if($sentido == 'saliente'){
                    // Traigo el maximo valor de prioridad si es saliente en entrante no aplica
                    $sqlPrioridadValorMaximo = "SELECT max(a.ASITAR_Prioridad_b) AS prioridadMaxima FROM DYALOGOCRM_SISTEMA.ASITAR a
                        INNER JOIN {$BaseDatos_systema}.CAMPAN b ON b.CAMPAN_ConsInte__b = a.ASITAR_ConsInte__CAMPAN_b
                        INNER JOIN {$BaseDatos_systema}.ESTPAS c ON c.ESTPAS_ConsInte__CAMPAN_b = b.CAMPAN_ConsInte__b 
                    WHERE c.ESTPAS_Tipo______b = {$tipoCampana}
                    AND a.ASITAR_ConsInte__USUARI_b = {$usuarioId}";
    
                    $resPrioridadMaximo = $mysqli->query($sqlPrioridadValorMaximo);
                    $dataPrioridadMaxima = $resPrioridadMaximo->fetch_object();
                    $prioridadMaxima = $dataPrioridadMaxima->prioridadMaxima + 1;
    
                    if(is_null($prioridadMaxima)){
                        $prioridadMaxima = 1;
                    }
                }

                // Inserto en asitar
                $sql = "INSERT INTO {$BaseDatos_systema}.ASITAR (ASITAR_ConsInte__CAMPAN_b, ASITAR_ConsInte__USUARI_b, ASITAR_ConsInte__GUION__Gui_b, ASITAR_ConsInte__GUION__Pob_b, ASITAR_ConsInte__MUESTR_b, ASITAR_Prioridad_b, ASITAR_IndiConc__b, ASITAR_UsuarioCBX_b) VALUES (
                    {$campanaId}, {$usuarioId}, 0, 0, 0, {$prioridadMaxima}, 0, {$usuarioCbx}
                )";
                if($mysqli->query($sql) !== true){
                    $exito = false;
                    $msgError = "Se presento un error al guardar asitar " . $mysqli->error;
                }

                if(!is_null($campanaCbxId)){
                    // guardo en dy_campanas_agentes
                    $sql = "INSERT INTO dyalogo_telefonia.dy_campanas_agentes (id_campana, id_agente, prioridad, fijo, responde_correos_electronicos, responde_chat, responde_llamadas) VALUES ({$campanaCbxId}, {$agenteId}, {$prioridadMaxima}, 0, 1, 1, 1)";
                    if($mysqli->query($sql) !== true){
                        $exito = false;
                        $msgError = "Se presento un error al guardar campana_agente " . $mysqli->error;
                    }
                }
                
            }

        }

        echo json_encode([
            "estado" => $exito,
            "msgError" => $msgError
        ]);

    }

    if(isset($_GET['quitarCampanasUsuario'])){

        $arrCampanas = $_POST['arrCampanas'];
        $sentido = $_POST['sentido'];
        $prioridad = $_POST['prioridad'];
        $usuario = $_POST['usuarioId'];

        if($sentido == 'saliente'){
            $tipoCampana = 6;
        }
        if($sentido == 'entrante'){
            $tipoCampana = 1;
        }

        $exito = true;
        $msgError = '';

        if(count($arrCampanas) > 0){

            // Traigo la informacion del usuario
            $sqlUsuario = "SELECT a.USUARI_ConsInte__b AS id, a.USUARI_UsuaCBX___b AS idCbx, b.id AS agenteId FROM {$BaseDatos_systema}.USUARI a
                JOIN dyalogo_telefonia.dy_agentes b ON a.USUARI_UsuaCBX___b = b.id_usuario_asociado
            WHERE md5(concat('".clave_get."', USUARI_ConsInte__b)) = '{$usuario}' LIMIT 1";
            $resUsuario = $mysqli->query($sqlUsuario);

            $usuarioId = 0;
            $usuarioCbx = 0;
            $agenteId = 0;

            if($resUsuario && $resUsuario->num_rows > 0){
                $dataUsuario = $resUsuario->fetch_object();
                $usuarioId = $dataUsuario->id;
                $usuarioCbx = $dataUsuario->idCbx;
                $agenteId = $dataUsuario->agenteId;
            }

            // Recorro todas las campanas
            for ($i=0; $i < count($arrCampanas); $i++) { 
                
                $campanaId = $arrCampanas[$i];

                $campanaCbxId = 0;
                
                // Traigo el id de campanaCbx
                $sqlCampanaCbx = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS campanaCbx FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$campanaId} LIMIT 1";
                $resCampanaCbx = $mysqli->query($sqlCampanaCbx);

                if($resCampanaCbx && $resCampanaCbx->num_rows > 0){
                    $dataCampana = $resCampanaCbx->fetch_object();
                    $campanaCbxId = $dataCampana->campanaCbx;
                }

                // elimino en asitar
                $sql = "DELETE FROM {$BaseDatos_systema}.ASITAR WHERE ASITAR_ConsInte__CAMPAN_b = {$campanaId} AND ASITAR_ConsInte__USUARI_b = {$usuarioId}";
                if($mysqli->query($sql) !== true){
                    $exito = false;
                    $msgError = "Se presento un error al guardar asitar " . $mysqli->error;
                }

                if(!is_null($campanaCbxId)){
                    // guardo en dy_campanas_agentes
                    $sql = "DELETE FROM dyalogo_telefonia.dy_campanas_agentes WHERE id_campana = {$campanaCbxId} AND id_agente = {$agenteId}";
                    if($mysqli->query($sql) !== true){
                        $exito = false;
                        $msgError = "Se presento un error al guardar campana_agente " . $mysqli->error;
                    }
                }
            }

            if($sentido == 'saliente'){
                // Inicio el proceso de Reordenamiento si es saliente
                $sql = "SELECT c.ESTRAT_ConsInte__b, c.ESTRAT_Nombre____b, c.ESTRAT_ConsInte__PROYEC_b,
                    b.ESTPAS_ConsInte__b, b.ESTPAS_Comentari_b, b.ESTPAS_Tipo______b,
                    a.CAMPAN_ConsInte__b AS campanaId
                    FROM DYALOGOCRM_SISTEMA.CAMPAN a
                    INNER JOIN DYALOGOCRM_SISTEMA.ESTPAS b ON b.ESTPAS_ConsInte__CAMPAN_b = a.CAMPAN_ConsInte__b 
                    INNER JOIN DYALOGOCRM_SISTEMA.ESTRAT c ON c.ESTRAT_ConsInte__b = b.ESTPAS_ConsInte__ESTRAT_b
                    LEFT JOIN (SELECT * FROM DYALOGOCRM_SISTEMA.ASITAR WHERE ASITAR_ConsInte__USUARI_b = {$usuarioId}) AS d ON a.CAMPAN_ConsInte__b = d.ASITAR_ConsInte__CAMPAN_b
                    WHERE b.ESTPAS_Tipo______b = {$tipoCampana}
                    AND d.ASITAR_ConsInte__b IS NOT NULL
                ORDER BY d.ASITAR_Prioridad_b";
    
                $resOrden = $mysqli->query($sql);
                if($resOrden && $resOrden->num_rows > 0){
    
                    $array1 = [];
    
                    while($row = $resOrden->fetch_object()){
                        $array1[] = $row->campanaId;
                    }
    
                    // Ahora actualizo los registros con el nuevo orden
                    for ($i=0; $i < count($array1); $i++) { 
    
                        $campanaId = $array1[$i];
                        $campanaCbxId = 0;
    
                        // Traigo el id de campanaCbx
                        $sqlCampanaCbx = "SELECT CAMPAN_ConsInte__b AS id, CAMPAN_IdCamCbx__b AS campanaCbx FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = {$campanaId} LIMIT 1";
                        $resCampanaCbx = $mysqli->query($sqlCampanaCbx);
    
                        if($resCampanaCbx && $resCampanaCbx->num_rows > 0){
                            $dataCampana = $resCampanaCbx->fetch_object();
                            $campanaCbxId = $dataCampana->campanaCbx;
                        }
    
                        $orden = $i+1;
                        // actualizo la prioridad en asitar
                        $sql = "UPDATE {$BaseDatos_systema}.ASITAR SET ASITAR_Prioridad_b = {$orden} WHERE ASITAR_ConsInte__CAMPAN_b = {$campanaId} AND ASITAR_ConsInte__USUARI_b = {$usuarioId}";
                        if($mysqli->query($sql) !== true){
                            $exito = false;
                            $msgError = "Se presento un error al guardar asitar " . $mysqli->error;
                        }
    
                        if(!is_null($campanaCbxId)){
        
                            // actualizo la prioridad en dy_campanas_agentes
                            $sql = "UPDATE dyalogo_telefonia.dy_campanas_agentes SET prioridad = {$orden} WHERE id_campana = {$campanaCbxId} AND id_agente = {$agenteId}";
                            if($mysqli->query($sql) !== true){
                                $exito = false;
                                $msgError = "Se presento un error al guardar campana_agente " . $mysqli->error;
                            }
                        }
                    }
    
                }
            }
        }

        echo json_encode([
            "estado" => $exito,
            "msgError" => $msgError
        ]);
    }


    if (isset($_GET['updateActivity'])) {
        if (isset($_POST['app']) && !empty($_POST['app'])) {
            // validar id de la session  
            $id_session = $_SESSION['ACTIVITY_ID'];
            $id_user = $_SESSION['IDENTIFICACION'];
            $id_huesped = $_SESSION['HUESPED_CRM'];

            $querySession = "SELECT US.id, US.`status`, US.id_session
            FROM DYALOGOCRM_SISTEMA.USUARI_SESSIONS AS US 
            WHERE US.id_usuario = $id_user AND US.id_huesped = $id_huesped AND application = '" . $_POST['app']. "'";
            
            $userSession = $mysqli->prepare($querySession);
            $userSession->execute();
            $dataUsSe = $userSession->get_result();
            if ($dataUsSe->num_rows > 0) {
                $dataSession = $dataUsSe->fetch_assoc();
                if ($dataSession['id_session'] === $id_session) {
                    $activity = registerActivity($_SESSION['IDENTIFICACION'], $_SESSION['HUESPED_CRM'], 'login', $_POST['app'] );
                    if ($activity['status']) {
                        $_SESSION['ACTIVITY_ID'] = $activity['activityId'];
                    }
                    echo json_encode( array('status' => $activity['status'], 'message' => $activity['message'] , 'disconnect' => false));
                } else {
                    // echo $dataSession['id_session'] .' === ' .$id_session;
                    echo json_encode(array('status' => false, 'message' => 'Has iniciado sesión en otro lugar. Serás desconectado por razones de seguridad', 'disconnect' => true));  
                }
            } else {
                echo json_encode(array('status' => false, 'message' => 'No se encontro una sesión Serás desconectado por razones de seguridad', 'disconnect' => true));
            } 

        } else{
            echo json_encode(array('status' => false, 'message' => 'app parametros requerido'));
        }
    }
}

function actualizaragentes($id, $pausas){
    
    global $mysqli, $BaseDatos_systema;
    
    //ACTUALIZAR HORARIO DE ENTRADA Y SALIDA EN USUARI
    $sql = $mysqli->query("UPDATE {$BaseDatos_systema}.USUARI JOIN {$BaseDatos_systema}.MALLATURNO ON USUARI_IdMalla_b=MALLATURNO_ConsInte__b SET USUARI_HorIniLun_b=MALLATURNO_HorIniLun_b,USUARI_HorFinLun_b=MALLATURNO_HorFinLun_b,USUARI_HorIniMar_b=MALLATURNO_HorIniMar_b,USUARI_HorFinMar_b=MALLATURNO_HorFinMar_b,USUARI_HorIniMie_b=MALLATURNO_HorIniMie_b,USUARI_HorFinMie_b=MALLATURNO_HorFinMie_b,USUARI_HorIniJue_b=MALLATURNO_HorIniJue_b,USUARI_HorFinJue_b=MALLATURNO_HorFinJue_b,USUARI_HorIniVie_b=MALLATURNO_HorIniVie_b,USUARI_HorFinVie_b=MALLATURNO_HorFinVie_b,USUARI_HorIniSab_b=MALLATURNO_HorIniSab_b,USUARI_HorFinSab_b=MALLATURNO_HorFinSab_b,USUARI_HorIniDom_b=MALLATURNO_HorIniDom_b,USUARI_HorFinDom_b=MALLATURNO_HorFinDom_b,USUARI_HorIniFes_b=MALLATURNO_HorIniFes_b,USUARI_HorFinFes_b=MALLATURNO_HorFinFes_b WHERE USUARI_IdMalla_b={$id}");
    //INSERTAR PAUSAS PERSONALIZADAS EN USUPAU
    $i = 0;
    $strpausas = '';
    while ($i < count($pausas)) {
        if ($i == 0) {
            $strpausas = $pausas[$i];
        } else {
            $strpausas .= "," . $pausas[$i];
        }
        $i++;
    }
    //ACTUALIZAR LAS PAUSAS POR DEFECTO CON EL ID DE LA TABLA PAUSASMALLA
    $sqlPAusasDefault = $mysqli->query("UPDATE DYALOGOCRM_SISTEMA.USUPAU JOIN DYALOGOCRM_SISTEMA.PAUSASMALLA ON USUPAU_PausasId_b=PAUSASMALLA_PausasId_b JOIN DYALOGOCRM_SISTEMA.USUARI ON USUPAU_ConsInte__USUARI_b=USUARI_ConsInte__b SET USUPAU_IdPausaMalla_B=PAUSASMALLA_ConsInte__b WHERE PAUSASMALLA_ConsInte__MALLATURNO_b={$id} AND USUARI_IdMalla_b={$id}");
    //ACTUALIZAR HORARIO DE LAS PAUSAS EN USUPAU
    $sql = $mysqli->query("UPDATE {$BaseDatos_systema}.USUPAU JOIN {$BaseDatos_systema}.PAUSASMALLA ON USUPAU_PausasId_b=PAUSASMALLA_PausasId_b SET USUPAU_HorIniLun_b=PAUSASMALLA_HorIniLun_b,USUPAU_HorFinLun_b=PAUSASMALLA_HorFinLun_b,USUPAU_HorIniMar_b=PAUSASMALLA_HorIniMar_b,USUPAU_HorFinMar_b=PAUSASMALLA_HorFinMar_b,USUPAU_HorIniMie_b=PAUSASMALLA_HorIniMie_b,USUPAU_HorFinMie_b=PAUSASMALLA_HorFinMie_b,USUPAU_HorIniJue_b=PAUSASMALLA_HorIniJue_b,USUPAU_HorFinJue_b=PAUSASMALLA_HorFinJue_b,USUPAU_HorIniVie_b=PAUSASMALLA_HorIniVie_b,USUPAU_HorFinVie_b=PAUSASMALLA_HorFinVie_b,USUPAU_HorIniSab_b=PAUSASMALLA_HorIniSab_b,USUPAU_HorFinSab_b=PAUSASMALLA_HorFinSab_b,USUPAU_HorIniDom_b=PAUSASMALLA_HorIniDom_b,USUPAU_HorFinDom_b=PAUSASMALLA_HorFinDom_b,USUPAU_HorIniFes_b=PAUSASMALLA_HorIniFes_b,USUPAU_HorFinFes_b=PAUSASMALLA_HorFinFes_b,USUPAU_DurMaxLun_b=PAUSASMALLA_DurMaxLun_b,USUPAU_CanMaxLun_b=PAUSASMALLA_CanMaxLun_b,USUPAU_DurMaxMar_b=PAUSASMALLA_DurMaxMar_b,USUPAU_CanMaxMar_b=PAUSASMALLA_CanMaxMar_b,USUPAU_DurMaxMie_b=PAUSASMALLA_DurMaxMie_b,USUPAU_CanMaxMie_b=PAUSASMALLA_CanMaxMie_b,USUPAU_DurMaxJue_b=PAUSASMALLA_DurMaxJue_b,USUPAU_CanMaxJue_b=PAUSASMALLA_CanMaxJue_b,USUPAU_DurMaxVie_b=PAUSASMALLA_DurMaxVie_b,USUPAU_CanMaxVie_b=PAUSASMALLA_CanMaxVie_b,USUPAU_DurMaxSab_b=PAUSASMALLA_DurMaxSab_b,USUPAU_CanMaxSab_b=PAUSASMALLA_CanMaxSab_b,USUPAU_DurMaxDom_b=PAUSASMALLA_DurMaxDom_b,USUPAU_CanMaxDom_b=PAUSASMALLA_CanMaxDom_b,USUPAU_DurMaxFes_b=PAUSASMALLA_DurMaxFes_b,USUPAU_CanMaxFes_b=PAUSASMALLA_CanMaxFes_b WHERE PAUSASMALLA_ConsInte__MALLATURNO_b={$id} AND USUPAU_IdPausaMalla_B=PAUSASMALLA_ConsInte__b");

    //SELECCIONAR LOS AGENTES ASIGNADOS A LA MALLA DE TURNOS
    $sqlAgentes = $mysqli->query("SELECT USUARI_ConsInte__b FROM {$BaseDatos_systema}.USUARI WHERE USUARI_IdMalla_b={$id}");

    //VALIDAR SI YA HABIAN AGENTES ASIGNADOS ANTERIORMENTE A LA MALLA DE TURNOS
    $sql = $mysqli->query("SELECT PAUSASMALLA_ConsInte__b,PAUSASMALLA_ConsInte__MALLATURNO_b,PAUSASMALLA_PausasId_b,USUARI_ConsInte__b,USUARI_Nombre____b,USUARI_IdMalla_b,USUPAU_ConsInte__b,USUPAU_ConsInte__USUARI_b,USUPAU_PausasId_b FROM {$BaseDatos_systema}.PAUSASMALLA LEFT JOIN {$BaseDatos_systema}.USUPAU ON PAUSASMALLA_PausasId_b=USUPAU_PausasId_b LEFT JOIN {$BaseDatos_systema}.USUARI ON USUPAU_ConsInte__USUARI_b=USUARI_ConsInte__b WHERE USUARI_IdMalla_b={$id} AND PAUSASMALLA_ConsInte__MALLATURNO_b={$id} AND USUPAU_PausasId_b NOT IN ({$strpausas})");

    if ( $sqlAgentes && $sqlAgentes->num_rows > 0) {
        if ($sql && $sql->num_rows > 0) {
            //YA HABÍA MINIMO UN AGENTE ASIGNADO A LA MALLA, POR LO CUAL TOCA VALIDAR AGENTE POR AGENTE
            $sqlSelect = $mysqli->query("SELECT USUARI_ConsInte__b,USUARI_Codigo____b,USUARI_IdMalla_b,PAUSASMALLA_ConsInte__b,PAUSASMALLA_PausasId_b,PAUSASMALLA_ConsInte__MALLATURNO_b FROM {$BaseDatos_systema}.USUARI LEFT JOIN {$BaseDatos_systema}.PAUSASMALLA ON USUARI_IdMalla_b=PAUSASMALLA_ConsInte__MALLATURNO_b WHERE USUARI_IdMalla_b={$id} AND PAUSASMALLA_ConsInte__MALLATURNO_b={$id} AND PAUSASMALLA_PausasId_b NOT IN ({$strpausas})");
            if ($sqlSelect && $sqlSelect->num_rows > 0) {
                while ($agente = $sqlSelect->fetch_object()) {
                    $sqlValidaAgente = $mysqli->query("SELECT * FROM {$BaseDatos_systema}.USUPAU WHERE USUPAU_PausasId_b={$agente->PAUSASMALLA_PausasId_b} AND USUPAU_ConsInte__USUARI_b={$agente->USUARI_ConsInte__b} AND USUPAU_IdPausaMalla_B={$agente->PAUSASMALLA_ConsInte__b}");
                    if ($sqlValidaAgente->num_rows < 1) {
                        $sqlUP = $mysqli->query("INSERT INTO {$BaseDatos_systema}.USUPAU (USUPAU_ConsInte__USUARI_b,USUPAU_PausasId_b,USUPAU_Tipo_b,USUPAU_HorIniLun_b,USUPAU_HorFinLun_b,USUPAU_DurMaxLun_b,USUPAU_CanMaxLun_b,USUPAU_HorIniMar_b,USUPAU_HorFinMar_b,USUPAU_DurMaxMar_b,USUPAU_CanMaxMar_b,USUPAU_HorIniMie_b,USUPAU_HorFinMie_b,USUPAU_DurMaxMie_b,USUPAU_CanMaxMie_b,USUPAU_HorIniJue_b,USUPAU_HorFinJue_b,USUPAU_DurMaxJue_b,USUPAU_CanMaxJue_b,USUPAU_HorIniVie_b,USUPAU_HorFinVie_b,USUPAU_DurMaxVie_b,USUPAU_CanMaxVie_b,USUPAU_HorIniSab_b,USUPAU_HorFinSab_b,USUPAU_DurMaxSab_b,USUPAU_CanMaxSab_b,USUPAU_HorIniDom_b,USUPAU_HorFinDom_b,USUPAU_DurMaxDom_b,USUPAU_CanMaxDom_b,USUPAU_HorIniFes_b,USUPAU_HorFinFes_b,USUPAU_DurMaxFes_b,USUPAU_CanMaxFes_b,USUPAU_IdPausaMalla_B) SELECT '{$agente->USUARI_ConsInte__b}',PAUSASMALLA_PausasId_b,PAUSASMALLA_Tipo_b,PAUSASMALLA_HorIniLun_b,PAUSASMALLA_HorFinLun_b,PAUSASMALLA_DurMaxLun_b,PAUSASMALLA_CanMaxLun_b,PAUSASMALLA_HorIniMar_b,PAUSASMALLA_HorFinMar_b,PAUSASMALLA_DurMaxMar_b,PAUSASMALLA_CanMaxMar_b,PAUSASMALLA_HorIniMie_b,PAUSASMALLA_HorFinMie_b,PAUSASMALLA_DurMaxMie_b,PAUSASMALLA_CanMaxMie_b,PAUSASMALLA_HorIniJue_b,PAUSASMALLA_HorFinJue_b,PAUSASMALLA_DurMaxJue_b,PAUSASMALLA_CanMaxJue_b,PAUSASMALLA_HorIniVie_b,PAUSASMALLA_HorFinVie_b,PAUSASMALLA_DurMaxVie_b,PAUSASMALLA_CanMaxVie_b,PAUSASMALLA_HorIniSab_b,PAUSASMALLA_HorFinSab_b,PAUSASMALLA_DurMaxSab_b,PAUSASMALLA_CanMaxSab_b,PAUSASMALLA_HorIniDom_b,PAUSASMALLA_HorFinDom_b,PAUSASMALLA_DurMaxDom_b,PAUSASMALLA_CanMaxDom_b,PAUSASMALLA_HorIniFes_b,PAUSASMALLA_HorFinFes_b,PAUSASMALLA_DurMaxFes_b,PAUSASMALLA_CanMaxFes_b,PAUSASMALLA_ConsInte__b FROM {$BaseDatos_systema}.PAUSASMALLA WHERE PAUSASMALLA_ConsInte__b={$agente->PAUSASMALLA_ConsInte__b})");
                    }
                }
            }
            
            $strEstado = $mysqli->errno === 0 ? 'ok': 'fallo';
            $data = ["strMensaje" => "Ya hay agentes asignados{$mysqli->error}", "strEstado" => "$strEstado"];
        } else {
            //NINGÚN AGENTE HA SIDO ASIGNADO ANTERIORMENTE A LAS PAUSAS PERSONALIZADAS DE LA MALLA DE TURNOS
            
            while ($agente = $sqlAgentes->fetch_object()) {
                $sql = $mysqli->query("INSERT INTO {$BaseDatos_systema}.USUPAU (USUPAU_ConsInte__USUARI_b,USUPAU_PausasId_b,USUPAU_Tipo_b,USUPAU_HorIniLun_b,USUPAU_HorFinLun_b,USUPAU_DurMaxLun_b,USUPAU_CanMaxLun_b,USUPAU_HorIniMar_b,USUPAU_HorFinMar_b,USUPAU_DurMaxMar_b,USUPAU_CanMaxMar_b,USUPAU_HorIniMie_b,USUPAU_HorFinMie_b,USUPAU_DurMaxMie_b,USUPAU_CanMaxMie_b,USUPAU_HorIniJue_b,USUPAU_HorFinJue_b,USUPAU_DurMaxJue_b,USUPAU_CanMaxJue_b,USUPAU_HorIniVie_b,USUPAU_HorFinVie_b,USUPAU_DurMaxVie_b,USUPAU_CanMaxVie_b,USUPAU_HorIniSab_b,USUPAU_HorFinSab_b,USUPAU_DurMaxSab_b,USUPAU_CanMaxSab_b,USUPAU_HorIniDom_b,USUPAU_HorFinDom_b,USUPAU_DurMaxDom_b,USUPAU_CanMaxDom_b,USUPAU_HorIniFes_b,USUPAU_HorFinFes_b,USUPAU_DurMaxFes_b,USUPAU_CanMaxFes_b,USUPAU_IdPausaMalla_B) SELECT '{$agente->USUARI_ConsInte__b}',PAUSASMALLA_PausasId_b,PAUSASMALLA_Tipo_b,PAUSASMALLA_HorIniLun_b,PAUSASMALLA_HorFinLun_b,PAUSASMALLA_DurMaxLun_b,PAUSASMALLA_CanMaxLun_b,PAUSASMALLA_HorIniMar_b,PAUSASMALLA_HorFinMar_b,PAUSASMALLA_DurMaxMar_b,PAUSASMALLA_CanMaxMar_b,PAUSASMALLA_HorIniMie_b,PAUSASMALLA_HorFinMie_b,PAUSASMALLA_DurMaxMie_b,PAUSASMALLA_CanMaxMie_b,PAUSASMALLA_HorIniJue_b,PAUSASMALLA_HorFinJue_b,PAUSASMALLA_DurMaxJue_b,PAUSASMALLA_CanMaxJue_b,PAUSASMALLA_HorIniVie_b,PAUSASMALLA_HorFinVie_b,PAUSASMALLA_DurMaxVie_b,PAUSASMALLA_CanMaxVie_b,PAUSASMALLA_HorIniSab_b,PAUSASMALLA_HorFinSab_b,PAUSASMALLA_DurMaxSab_b,PAUSASMALLA_CanMaxSab_b,PAUSASMALLA_HorIniDom_b,PAUSASMALLA_HorFinDom_b,PAUSASMALLA_DurMaxDom_b,PAUSASMALLA_CanMaxDom_b,PAUSASMALLA_HorIniFes_b,PAUSASMALLA_HorFinFes_b,PAUSASMALLA_DurMaxFes_b,PAUSASMALLA_CanMaxFes_b,PAUSASMALLA_ConsInte__b FROM {$BaseDatos_systema}.PAUSASMALLA WHERE PAUSASMALLA_ConsInte__MALLATURNO_b={$id} AND PAUSASMALLA_PausasId_b NOT IN ({$strpausas})");
            }
            $strEstado = $mysqli->errno === 0 ? 'ok': 'fallo';
            $data = ["strMensaje" => "Ningún agente asignado{$mysqli->error}", "strEstado" => "$strEstado"];
        }
    }else {
        $strEstado = $mysqli->errno === 0 ? 'ok': 'fallo';
        $data = ["strMensaje" => "Ningún agente asignado {$mysqli->error}", "strEstado" => "$strEstado"];
    }

    return $data;
}

function validarAgentes($idAgente,$arrAgentes){
    $i=0;
    $valido=0;
    while($i < count($arrAgentes)){
        if($idAgente==$arrAgentes[$i]){
           $valido=1;
        }
        $i++;    
    }
    return $valido;
}

function RandomString($length = 8, $uc = TRUE, $n = TRUE) {
    $source = 'abcdefghijklmnopqrstuvwxyz';
    if ($uc == 1)
        $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($n == 1)
        $source .= '1234567890';
    if ($length > 0) {
        $rstr = "";
        $source = str_split($source, 1);
        for ($i = 1; $i <= $length; $i++) {
            mt_srand((double) microtime() * 1000000);
            $num = mt_rand(1, count($source));
            $rstr .= $source[$num - 1];
        }
    }
    return $rstr;
}

function encriptaPassword($password) {
    $method = 'sha256';
    $encrypted = hash($method, $password, false);
    return $encrypted;
}

function crearPassword() {

    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";

    $password = "";
    //Reconstruimos la contraseña segun la longitud que se quiera
    for ($i = 0; $i < 8; $i++) {
        //obtenemos un caracter aleatorio escogido de la cadena de caracteres
        $password .= substr($str, rand(0, 62), 1);
    }

    //Mostramos la contraseña generada
    return $password;
}

function validarMalla()  {
    // lunes 
    if (!validarHoraFormato24($_POST['HorIniLun']) && !empty($_POST['HorIniLun'])) {
        return ['strMensaje' => "Hora inicio lunes no tiene un formato valido", 'strEstado' => false];
    }

    if (!validarHoraFormato24($_POST['HorFinLun']) && !empty($_POST['HorFinLun'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora fin lunes no tiene un formato valido");
    }

    //martes 
    if (!validarHoraFormato24($_POST['HorIniMar']) && !empty($_POST['HorIniMar']) ) {
        return array('strEstado' => false, 'strMensaje' => "Hora inicio martes no tiene un formato valido");
    }

    if (!validarHoraFormato24($_POST['HorFinMar']) && !empty($_POST['HorFinMar'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora fin martes no tiene un formato valido");
    }

    // //Miercoles
    if (!validarHoraFormato24($_POST['HorIniMie']) && !empty($_POST['HorIniMie'])) {
        return ['strMensaje' => "Hora inicio miercoles no tiene un formato valido", 'strEstado' => false];
        
    }

    if (!validarHoraFormato24($_POST['HorFinMie']) && !empty($_POST['HorFinMie'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora fin miercoles no tiene un formato valido");
    }

    // //Jueves 
    if (!validarHoraFormato24($_POST['HorIniJue']) && !empty($_POST['HorIniJue']) ) {
        return array('strEstado' => false, 'strMensaje' => "Hora inicio jueves no tien un formato valido");
    }

    if (!validarHoraFormato24($_POST['HorFinJue']) && !empty($_POST['HorFinJue'])) {
        return  array('strEstado' => false, 'strMensaje' => "Hora fin jueves no tien un formato valido");
    }

    // //Viernes 
    if (!validarHoraFormato24($_POST['HorIniVie']) && !empty($_POST['HorIniVie'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora inicio viernes no tien un formato valido");
    }

    if (!validarHoraFormato24($_POST['HorFinVie']) && !empty($_POST['HorFinVie'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora fin viernes no tien un formato valido");
    }

    // //Sabado
    if (!validarHoraFormato24($_POST['HorIniSab']) && !empty($_POST['HorIniSab'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora inicio sabado no tien un formato valido");
    }

    if (!validarHoraFormato24($_POST['HorFinSab']) && !empty($_POST['HorFinSab'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora fin sabado no tien un formato valido");
    }


    // //domingo
    if (!validarHoraFormato24($_POST['HorIniDom']) && !empty($_POST['HorIniDom'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora inicio domingo no tien un formato valido");
    }

    if (!validarHoraFormato24($_POST['HorFinDom']) && !empty($_POST['HorFinDom'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora fin domingo no tien un formato valido");
    }

    // //Festivo
    if (!validarHoraFormato24($_POST['HorIniFes']) && !empty($_POST['HorIniFes'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora inicio festivo no tien un formato valido");
    }

    if (!validarHoraFormato24($_POST['HorFinFes']) && !empty($_POST['HorFinFes'])) {
        return array('strEstado' => false, 'strMensaje' => "Hora fin festivo no tien un formato valido");
    }

    return true;

}

function validarHoraFormato24($hora)  {
    $dateTime = DateTime::createFromFormat('H:i:s', $hora);
    return $dateTime && $dateTime->format('H:i:s') === $hora;
}

?>
