<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$Sesion= session_start();
if(isset($Sesion)){
    //session_start();
    include("pages/conexion.php");
    include("global/WSCoreClient.php");
    require_once ("../helpers/parameters.php");
    require_once "vendor/autoload.php";
    include("global/funcionesGenerales.php");
}else{
    header('Location: login.php?quality='.$quality);
    echo $Sesion;
    exit;
}

use Firebase\JWT\JWT;
use phpDocumentor\Reflection\Types\Boolean;

if (isset($_POST['txtUsuario'])) {
    $user = ($_POST['txtUsuario']);
    $contrasena = encriptaPassword($_POST['txtPassword']);


    $stmtLsql = "SELECT USUARI_Cargo_____b, USUARI_ConsInte__b, USUARI_IndiActi__b, USUARI_Codigo____b, USUARI_Nombre____b , USUARI_ConsInte__b, USUARI_FechCrea__b , USUARI_InPeToGu__b, USUARI_Foto______b, USUARI_UsuaCBX___b ,USUARI_ConsInte__PROYEC_b, USUARI_Clave_____b , USUARI_Identific_b, USUARI_Correo___b, USUARI_Clave_Temp_____b, USUARI_FechUpdate_Clave______b, USUARI_IntentosFallidos 
    FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_Correo___b = '" . $user . "' ";

    //$query = $mysqli->query($Lsql) or trigger_error($mysqli->error." [$Lsql]");

    $stmt = $mysqli->query($stmtLsql);
    if ($stmt->num_rows > 0) {
        $datos = $stmt->fetch_array();
        if($datos['USUARI_IndiActi__b'] == -1){
            if ($datos['USUARI_Cargo_____b'] == 'administrador' || $datos['USUARI_Cargo_____b'] == 'super-administrador' || $datos['USUARI_Cargo_____b'] == 'administradorlimitado' || $datos['USUARI_Cargo_____b'] == 'owner' || $datos['USUARI_Cargo_____b'] == 'administrador-avanzado' ) {
    
                if ($datos['USUARI_Clave_____b'] == $contrasena) {
                    $_SESSION['CODIGO']         = $user;
                    $_SESSION['NOMBRES']        = $datos['USUARI_Nombre____b'];
                    $_SESSION['IDENTIFICACION'] = $datos['USUARI_ConsInte__b'];
                    $_SESSION['CORREO']         = $datos['USUARI_Correo___b'];
                    $_SESSION['FECHA']          = $datos['USUARI_FechCrea__b'];
                    $_SESSION['ACCESO']         = $datos['USUARI_InPeToGu__b'];
                    $_SESSION['USUARICBX']      = $datos['USUARI_UsuaCBX___b'];
                    $_SESSION['HUESPED_CRM']  = $datos['USUARI_ConsInte__PROYEC_b'];
                    $_SESSION['LOGIN_OK_MANAGER']       = true;
                    $_SESSION['QUALITY'] = 1;
                    $imagenUser = "assets/img/Kakashi.fw.png";
                    if (file_exists("/var/../Dyalogo/img_usuarios/usr" . $datos['USUARI_UsuaCBX___b'] . ".jpg")) {
                        $imagenUser = "/DyalogoImagenes/usr" . $datos['USUARI_Foto______b'];
                    }
                    $_SESSION['IMAGEN'] = $imagenUser;
                    $_SESSION['FOTO']   = $datos['USUARI_Foto______b'];
                    $_SESSION['CARGO']  = $datos['USUARI_Cargo_____b'];
                    $_SESSION['IntentosFallidos']  = $datos['USUARI_IntentosFallidos'];
    
                     
                   

                    //Validar Configuraciones de Seguridad
                    $Huesped = $datos['USUARI_ConsInte__PROYEC_b'];
                    $Seguridad = ConsultarConfigSeguridad($Huesped);
                    
                    //Si Existe Resultado
                    if($Seguridad != false){
                        $ResulConfigSeg= $Seguridad[0];
                        print_r($ResulConfigSeg);
                        print_r("</br>");

                        //Si Caducidad Activada
                        $Caducidad= $ResulConfigSeg[0];
                        if($Caducidad == "true") {
                            $DiasValidez= $ResulConfigSeg[1];
                            $DiasNotificar= $ResulConfigSeg[2];

                            //Notificar Caducidad de la contraseña
                            $NotificarCaducidad= NotificarCaducidad($datos['USUARI_FechUpdate_Clave______b'], $DiasNotificar, $DiasValidez);
                            $_SESSION['NotificarCaducidad']= $NotificarCaducidad;

                        }else{
                            $_SESSION['NotificarCaducidad']= "false";
                            
                        }
                        print_r($_SESSION['NotificarCaducidad']);


                        //Si Bloqueo Activado
                        $Bloqueo= $ResulConfigSeg[3];
                        if($Bloqueo == "true") {
                            //Limpiar Intentos Fallidos
                            $LimpiarIntentosFallidos= LimpiarIntentosFallidos($user);
                            $_SESSION['LimpiarIntentosFallidos']= $LimpiarIntentosFallidos;
                            $_SESSION['BloqueoAutomatico']= "false";
                        }else{
                            $_SESSION['BloqueoAutomatico']= "false";
                        }

                    }else{
                        $_SESSION['NotificarCaducidad']= "false";
                        $_SESSION['BloqueoAutomatico']= "false";
                    }

                    //Valida La Caducidad de la contraseña
                    if(validExpirationPassword($datos['USUARI_FechUpdate_Clave______b'], $datos['USUARI_ConsInte__PROYEC_b'], $datos['USUARI_Clave_Temp_____b'])){
                        $_SESSION['CLAVE_TEMPORAL'] = "-1";
                        $_SESSION['NotificarCaducidad']= "false";

                    }else{
                        $_SESSION['CLAVE_TEMPORAL'] = "0";
                    }

                    
                    // validar licencias 
                    $licenses = validateLicense($datos['USUARI_ConsInte__b'], $Huesped, 'manager');
                    if(isset($licenses['status']) && !$licenses['status']){
                       $_SESSION['message_license'] = $licenses['message'];
                       header('Location: login.php');
                       exit;
                    }

                    $_SESSION['TOKEN']  =  Time() . rand();
                    if(saveSession($datos['USUARI_ConsInte__b'])){
                        $jwtToken = generateJWT($datos['USUARI_ConsInte__b'],$datos['USUARI_Identific_b'],$datos['USUARI_ConsInte__PROYEC_b']);
                        $_SESSION['JWTTOKEN']  =  $jwtToken['token'];

                        /*
                        $activity = registerActivity($datos['USUARI_ConsInte__b'], $Huesped, 'login', 'manager');
                        if (isset($activity['status']) && !$activity['status']) {
                            $_SESSION['message_license'] = $activity['message'];
                            header('Location: login.php');
                        } else {
                            $_SESSION['ACTIVITY_ID'] = $activity['activityId'];
                            header('Location: login.php');
                        }
                        */
                        header('Location: index.php?page=dashboard');
                    }
                    
                    header('Location: index.php?page=dashboard');

                }else {
                    //Contraseña Incorrecta
                    RegistrarIntentoFallido($user);

                    $Huesped= $datos['USUARI_ConsInte__PROYEC_b'];
                    $Seguridad= ConsultarConfigSeguridad($Huesped);
                    if($Seguridad != false){
                        $ResulConfigSeg= $Seguridad[0];
                        print_r($ResulConfigSeg);
                        print_r("</br>");
                        //Si Bloqueo Activado
                        $Bloqueo= $ResulConfigSeg[3];
                        if($Bloqueo == "true") {
                            //Notificar Intentos Fallidos
                            $IntentosFallidos= $datos['USUARI_IntentosFallidos'];
                            $IntentosPermitidos= $ResulConfigSeg[4];
                            $IntentosRestantes= $IntentosPermitidos-$IntentosFallidos;
                            $_SESSION['IntentosRestantes'] = $IntentosRestantes;
                            
                            //Notificar Bloqueo Automatico
                            $BloqueoAutomatico= BloqueoAutomatico($IntentosPermitidos, $IntentosFallidos, $user);
                            $_SESSION['BloqueoAutomatico']= $BloqueoAutomatico;
                            
                        }else{
                            $_SESSION['IntentosRestantes']= "false";
                            $_SESSION['BloqueoAutomatico']= "false";
                        }
                    }else{
                        $_SESSION['IntentosRestantes']= "false";
                        $_SESSION['BloqueoAutomatico']= "false";
                    }

                    $_SESSION['register'] = 'fail';
                    header('Location: login.php');
                }
            } else {
                $_SESSION['register'] = 'denegado';
                header('Location: login.php');
            }
        }else{
            $_SESSION['register'] = 'inactivo';
            header('Location: login.php');
        }
    } else {
        $_SESSION['register'] = 'noexiste';
        header('Location: login.php');
    }
}

function RandomString($length = 8, $uc = TRUE, $n = TRUE)
{
    $source = 'abcdefghijklmnopqrstuvwxyz';
    if ($uc == 1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($n == 1) $source .= '1234567890';
    if ($length > 0) {
        $rstr = "";
        $source = str_split($source, 1);
        for ($i = 1; $i <= $length; $i++) {
            mt_srand((float)microtime() * 1000000);
            $num = mt_rand(1, count($source));
            $rstr .= $source[$num - 1];
        }
    }
    return $rstr;
}

function getPassword(array $arrData_p, string $strpass_p)
{
    # code...
    $respuesta = usuarioPersistir($arrData_p['USUARI_Nombre____b'], null, $arrData_p['USUARI_Correo___b'], $arrData_p['USUARI_Identific_b'], $strpass_p, true, $arrData_p['USUARI_ConsInte__PROYEC_b'], $arrData_p['USUARI_UsuaCBX___b']);
    
    // echo $respuesta;

    if (!empty($respuesta) && !is_null($respuesta)) {
        $json = json_decode($respuesta);
        
        if ($json->strEstado_t == "ok") {
            $json = $json->strPass_t;
        }
    }
    // $json = json_decode($respuesta);
    return $json;
}

function encriptaPassword($password)
{
    $method = 'sha256';
    $encrypted = hash($method, $password, false);
    return $encrypted;
}

function saveSession($usuario)
{
    global $mysqli;
    $sql = $mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.CRMSESSION (CRMSESSION_ConsinteUsuari,CRMSESSION_App) VALUES ($usuario,'manager')");
    return true;
}

function generateJWT(int $user, string $identificacion, int $huesped):array
{
    (bool) $estado=false;
    (int) $time = time();
    (array) $token = array(
        'iss'=>"nicolasBG",
        'aud' =>"nicolasBG",
        'iat' => $time, //TIEMPO EN QUE INICIA SESIÓN
        'nbf' => $time - (60*60*24), // TIEMPO PARA EXPIRAR EL TOKEN (1 DÍA)
        'exp' => $time + (60*60*24), // TIEMPO PARA EXPIRAR EL TOKEN (1 DÍA)
        'data' => [
            "server"=>$_SERVER['SERVER_NAME'],
            "identificacion"=>$identificacion,
            "huesped"=>$huesped
        ]
    );

    $jwt = JWT::encode($token, clave_get, 'HS256');
    if(upToken($user,$jwt,$token['exp'])){
        $estado=true;
    }

    return array('estado'=>$estado, 'token'=>$jwt);
}

function upToken(int $id, string $token, int $exp):bool
{
    global $mysqli;

    $response=false;
    clearTokenUsuari($id);
    $sql=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.CRMSESSION SET CRMSESSION_UserToken='{$token}', CRMSESSION_UserToken_Expire={$exp} WHERE CRMSESSION_Consinte=(SELECT A.CRMSESSION_Consinte FROM (SELECT MAX(CRMSESSION_Consinte) AS CRMSESSION_Consinte FROM DYALOGOCRM_SISTEMA.CRMSESSION WHERE CRMSESSION_ConsinteUsuari={$id}) A )");
    if($sql && $mysqli->affected_rows == 1){
        $response=true;
    }

    return $response;
}

function clearTokenUsuari(int $id):bool
{
    global $mysqli;
    
    $mysqli->query("UPDATE DYALOGOCRM_SISTEMA.CRMSESSION SET CRMSESSION_UserToken=NULL, CRMSESSION_UserToken_Expire=NULL WHERE CRMSESSION_ConsinteUsuari={$id}");
    return true;
}


/*Fuciones De Seguridad*/
//Consultar Configuraciones Seguridad
function ConsultarConfigSeguridad($Huesped){
    global $mysqli;
    $ListaConfigSeg = array();
    $ConsultaSQL = "SELECT * FROM DYALOGOCRM_SISTEMA.SEGURIDAD WHERE SEGURIDAD_ConsInte__PROYEC_b= '". $Huesped ."' AND SEGURIDAD_ESTADO= 'Activo';";
    $ResultadoSQL = $mysqli->query($ConsultaSQL);
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ID = $FilaResultado['SEGURIDAD_ConsInte__b'];
            $ACTIVAR_CADUCIDAD_I = $FilaResultado['SEGURIDAD_ACTIVAR_CADUCIDAD'];
            $DIAS_VALIDEZ = $FilaResultado['SEGURIDAD_DIAS_VALIDEZ'];
            $DIAS_NOTIFICAR = $FilaResultado['SEGURIDAD_DIAS_NOTIFICAR'];
            $ACTIVAR_BLOQUEO_AUTOMATICO_I = $FilaResultado['SEGURIDAD_ACTIVAR_BLOQUEO_AUTOMATICO'];
            $INTENTOS_FALLIDOS = $FilaResultado['SEGURIDAD_INTENTOS_FALLIDOS'];
            $FECHA_REGISTRO = $FilaResultado['SEGURIDAD_FECHA_REGISTRO'];

            if($ACTIVAR_CADUCIDAD_I == 1){
                $ACTIVAR_CADUCIDAD= "true";
            }else{
                $ACTIVAR_CADUCIDAD= "false";
            }

            if($ACTIVAR_BLOQUEO_AUTOMATICO_I == 1){
                $ACTIVAR_BLOQUEO_AUTOMATICO= "true";
            }else{
                $ACTIVAR_BLOQUEO_AUTOMATICO= "false";
            }
            array_push($ListaConfigSeg, array("0" => $ACTIVAR_CADUCIDAD, "1" => $DIAS_VALIDEZ, "2" => $DIAS_NOTIFICAR, "3" => $ACTIVAR_BLOQUEO_AUTOMATICO, "4" => $INTENTOS_FALLIDOS, "5" => $FECHA_REGISTRO, "6" => $ID));
        }

        return $ListaConfigSeg;

    }else{
        return false;
    }
};

//Notificar Caducidad Contraseña
function NotificarCaducidad($FechaCambioClave, $DiasNotificar, $DiasValidez){
    
    $DiasParaNotificar= $DiasValidez - $DiasNotificar;
    $FechaNotificacion= date_add(date_create($FechaCambioClave), date_interval_create_from_date_string($DiasParaNotificar." days"));
    $FechaActual= date_create();
    //print_r($DiasParaNotificar."</br>");
    //print_r($FechaNotificacion);
    //print_r($FechaActual);

    if($FechaActual > $FechaNotificacion){
        $Notificar= "true";
    }else{
        $Notificar= "false";
    }
    return $Notificar;
    
}

//Limpiar Intentos Fallidos
function LimpiarIntentosFallidos($Usuario){
    global $mysqli;
    $Limpiar="UPDATE DYALOGOCRM_SISTEMA.USUARI SET USUARI_IntentosFallidos= '0' WHERE USUARI_Codigo____b= '". $Usuario ."';";
    $mysqli->query($Limpiar);
}

//Registrar Intento Fallido
function RegistrarIntentoFallido($Usuario){
    global $mysqli;
    $ConsultaIntentos = "SELECT * FROM DYALOGOCRM_SISTEMA.USUARI WHERE USUARI_Codigo____b= '". $Usuario ."';";
    $ResultadoSQL = $mysqli->query($ConsultaIntentos);
    $CantidadResultados = $ResultadoSQL->num_rows;
    if($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $IntentosFallidosA = $FilaResultado['USUARI_IntentosFallidos'];
        }
    }

    $IntentosFallidos= $IntentosFallidosA+1;
    print_r($IntentosFallidos."</br>");

    $GuardarIntentosFallidos="UPDATE DYALOGOCRM_SISTEMA.USUARI SET USUARI_IntentosFallidos= '". $IntentosFallidos ."' WHERE USUARI_Codigo____b= '". $Usuario ."';";
    $mysqli->query($GuardarIntentosFallidos);

}

//Bloqueo Automatico
function BloqueoAutomatico($NumeroIntentosPermitidos, $NumeroIntentosFallidos, $user){
    global $mysqli;
    $Usuario= $user;
    if($NumeroIntentosFallidos >= $NumeroIntentosPermitidos){
        $BloquearUsuario="UPDATE DYALOGOCRM_SISTEMA.USUARI SET USUARI_IndiActi__b= '0', USUARI_Bloqueado_b='-1' WHERE USUARI_Codigo____b= '". $Usuario ."';";
        $mysqli->query($BloquearUsuario);
        $Bloquear= "true";
    }else{
        $Bloquear= "false";
    }
    return $Bloquear;
}

//Validar Si El Huésped Tiene La Vencimiento De La Contraseña Habilitada Y, Si La Tiene, Evaluar Si La Contraseña Aún Es Válida O Requiere Cambio
function validExpirationPassword(string $updateDate = null, string $huesped, string $isClaveTemp):bool {

    global $mysqli;
    global $BaseDatos_general;

    if($updateDate != null || $isClaveTemp == -1){

        $sqlExpValidation = $mysqli->query("SELECT pass_cambio_periodico_requerido, pass_dias_cambio_periodico, pass_cambio_login_requerido FROM {$BaseDatos_general}.huespedes WHERE id = {$huesped} LIMIT 1;");
        if ($sqlExpValidation->num_rows > 0) {
            $resExpValidation = $sqlExpValidation->fetch_array();

            if($resExpValidation["pass_cambio_login_requerido"] == 1 && $isClaveTemp == -1){
                return true;
            }


            if($resExpValidation["pass_cambio_periodico_requerido"] == 1){
                $maxDate = date_add(date_create($updateDate), date_interval_create_from_date_string($resExpValidation["pass_dias_cambio_periodico"]." days"));

                //SE VALIDA SI LA FECHA ACTUAL ES MAYOR A LA FECHA MAXIMA PARA EL CAMBIO DE CLAVE
                return date_create() >= $maxDate ;

            }
        }
    }
    return false;
}



/**
 * Valida la cantidad de licencias activas para la session
 *
 * Valida Cuantas sesiones estan activas las compara con el numero de licencias permitidas 
 * si es igual o mayor a la permitidas no permitira iniciar sesion
 *
 * @param int $idHuesped id del huesped donde esta asociada el usuario
 * @return Boolean true si puede iniciar sesion false si ha exedido el maximo de licencias
 **/
/**
 * Valida la cantidad de licencias activas para la session
 *
 * Valida Cuantas sesiones estan activas las compara con el numero de licencias permitidas 
 * si es igual o mayor a la permitidas no permitira iniciar sesion
 *
 * @param int $idHuesped id del huesped donde esta asociada el usuario
 * @return Boolean true si puede iniciar sesion false si ha exedido el maximo de licencias
 **/
function validateLicense($idUser, $id_huesped, $app)
{
    global $mysqli;
    global $BaseDatos_general;
   

    $licenses = $mysqli->query("SELECT cantidad_max_supervisores, cantidad_max_bo, cantidad_max_calidad  FROM {$BaseDatos_general}.huespedes WHERE id = {$id_huesped} LIMIT 1;");
    if ($licenses->num_rows > 0) {
        $resultLicenses = $licenses->fetch_assoc();
        $cantidad_max_supervisores = $resultLicenses['cantidad_max_supervisores'];
        $cantidadMaximaBackoffice = $resultLicenses['cantidad_max_bo'];
        $cantidadMaximaCalidad = $resultLicenses['cantidad_max_calidad'];

    } else {
        return array ('status' => false, 'message' => 'No fue posible consultar las licencias permitidas intenta nuevamente');
    }

    $countLicense = 0;
    if ($app === 'manager') {
        $countLicense = $cantidad_max_supervisores;
    } elseif($app === 'backoffice') {
        $countLicense = $cantidadMaximaBackoffice;
    } elseif ($app === 'calidad') {
        $countLicense = $cantidadMaximaCalidad;
    }

    $countSession = "SELECT COUNT(*) AS count_session 
    FROM DYALOGOCRM_SISTEMA.USUARI_SESSIONS AS US
    WHERE US.`status` = 'online' AND  US.`application` IN ( '$app' ) AND US.id_huesped = $id_huesped";
   
    $resultSession = $mysqli->prepare($countSession);
    $resultSession->execute();
    $resultS = $resultSession->get_result();
    
    if ($resultS) {
        if ($resultS->num_rows > 0 ) {
            $datasession = $resultS->fetch_array();

            if ($datasession['count_session'] >= $countLicense) {
                return array ('status' => false, 'message' => 'Ha sobrepasado el limite de licencias permitidas.');
            } else {
                return array ('status' => true, 'message' => 'lo deja pasar por que no ha exedido el limite');
            }
        }else {
            return array ('status' => true, 'message' => 'lo deja pasar por que no ha exedido el limite');
        }
    } else {
        return array ('status' => false, 'message' => 'fallo al consultar la cantidad de sesiones ');
    }  
   
}


function validateUniqueGuest($_userCbx)  {
    global $mysqli;
    global $BaseDatos_general;
    
    $qHuesped = "SELECT * FROM ".$BaseDatos_general.".huespedes_usuarios JOIN ".$BaseDatos_general.".huespedes ON huespedes.id = huespedes_usuarios.id_huesped WHERE id_usuario = ".$_userCbx." ORDER BY nombre ASC";
    if ($response = $mysqli->query($qHuesped)) {
        if ($response->num_rows > 1) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }


}


?>
