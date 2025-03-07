
<?php

    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    date_default_timezone_set('America/Bogota');
    include("conexion.php");
    include("Jwt/jwt.php");
    include("../manager/global/WSCoreClient.php");
    include("global/funcionesGenerales.php");

    if(isset($_POST['id_cbx']) && !isset($_GET['close_token'])){
        $Lsql = "select USUARI_ConsInte__b, USUARI_Codigo____b, USUARI_Nombre____b , USUARI_ConsInte__b, USUARI_FechCrea__b , USUARI_InPeToGu__b, USUARI_ConsInte__PROYEC_b FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_UsuaCBX___b = '".$_POST['id_cbx']."' ";


        $query = $mysqli->query($Lsql) or trigger_error($mysqli->error." [$Lsql]");
        if($query->num_rows > 0) {
            // creamos la session

            $datosSesion = array();
            $nombres = null;
            $userid = 0;
            while($key = $query->fetch_object()){
                $datosSesion[0]['CODIGO'] = $key->USUARI_Codigo____b;
                $datosSesion[0]['NOMBRES']   = $key->USUARI_Nombre____b;
                $datosSesion[0]['IDENTIFICACION'] = $key->USUARI_ConsInte__b;
                $datosSesion[0]['FECHA']  = $key->USUARI_FechCrea__b;
                $datosSesion[0]['ACCESO']  = $key->USUARI_InPeToGu__b;
                $datosSesion[0]['PROYECTO_CRM']  = $key->USUARI_ConsInte__PROYEC_b;
                $datosSesion[0]['HUESPED_CRM'] = $key->USUARI_ConsInte__PROYEC_b;
                $datosSesion[0]['LOGIN_OK']  = true;
                $datosSesion[0]['iat'] = time();
                $datosSesion[0]['exp'] = time() + 60;
                $nombres = $key->USUARI_Nombre____b;
                $userid  = $key->USUARI_ConsInte__b;
            }

            $jwt = JWT::encode($datosSesion, '');

            $InsertSQl = "INSERT INTO ".$BaseDatos_systema.".SESSIONS( SESSIONS__USUARI_ConsInte__b, SESSIONS__Token, SESSIONS__Estado__b) VALUES (".$userid." , '" . $jwt . "' , 1)";
            $query = $mysqli->query($InsertSQl) or trigger_error($mysqli->error." [$InsertSQl]");

            echo json_encode(array( 'code' => 1,
                                    'nombres' => $nombres,
                                    'token' => $jwt
                            ));

        }else{
            echo json_encode(array('code' => 0,
                                    'response' => 'No es un id valido!'
                            ));
        }
    }

    if(isset($_GET['close_token'])){
        $id_cbx  = $_POST['id_cbx'];
        $Lsql = "select USUARI_ConsInte__b, USUARI_Codigo____b, USUARI_Nombre____b , USUARI_ConsInte__b, USUARI_FechCrea__b , USUARI_InPeToGu__b, USUARI_ConsInte__PROYEC_b FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_UsuaCBX___b = '".$id_cbx."' ";
        $query = $mysqli->query($Lsql) or trigger_error($mysqli->error." [$Lsql]");
        $usuario = 0;
        if($query->num_rows > 0) {
            while($key = $query->fetch_object()){
                $usuario = $key->USUARI_ConsInte__b;
            }
        }

        if($usuario != 0){
            $updateSql = "UPDATE ".$BaseDatos_systema.".SESSIONS SET  SESSIONS__Estado__b = 2, SESSIONS__Fecha_final = '".date('Y-m-d H:i:s')."' WHERE SESSIONS__USUARI_ConsInte__b = ". $usuario ." AND SESSIONS__Estado__b = 1 ;";
           //jgir echo $updateSql;
            $query = $mysqli->query($updateSql) or trigger_error($mysqli->error." [$updateSql]");
            echo json_encode(array('code' => 1,
                                    'response' => 'Se ha cerrado la session!'
                            ));
        }else{
            echo json_encode(array('code' => 0,
                                    'response' => 'No es un Id valido!'
                            ));
        }



    }

    if (isset($_POST['usuario'])){
        $user = ($_POST['usuario']);
        $contrasena = encriptaPassword($_POST['password']); //md5($_POST['password']);
        // echo $contrasena;
        if (isset($_POST['quality'])) {
            $QUALITY = $_POST['quality'];
        }else{
            $QUALITY = 0;
        }
        
        $stmtLsql = "SELECT USUARI_Cargo_____b, USUARI_ConsInte__b, USUARI_IndiActi__b, USUARI_Codigo____b, USUARI_Nombre____b , USUARI_ConsInte__b, USUARI_FechCrea__b , USUARI_InPeToGu__b, USUARI_Foto______b, USUARI_UsuaCBX___b ,USUARI_ConsInte__PROYEC_b, USUARI_Clave_____b , USUARI_Identific_b, USUARI_Correo___b, USUARI_Clave_Temp_____b, USUARI_FechUpdate_Clave______b, USUARI_IntentosFallidos 
        FROM " . $BaseDatos_systema . ".USUARI WHERE USUARI_Correo___b = '" . $user . "' ";
        //$query = $mysqli->query($Lsql) or trigger_error($mysqli->error." [$Lsql]");

        $stmt = $mysqli->query($stmtLsql);


        if($stmt->num_rows > 0) {
            $datos = $stmt->fetch_array();
            if($datos['USUARI_Clave_____b'] == $contrasena){
                if($datos['USUARI_IndiActi__b'] == -1){
                    //echo "paso por aqui ";
                    $datosSesion = array();
                   // while($key = $stmt->fetch_object()){
                        $_SESSION['CODIGO'] = $user;
                        $_SESSION['NOMBRES']   = $datos['USUARI_Nombre____b'];
                        $_SESSION['IDENTIFICACION'] = $datos['USUARI_ConsInte__b'];
                        $_SESSION['FECHA']  = $datos['USUARI_FechCrea__b'];
                        $_SESSION['CORREO']         = $datos['USUARI_Correo___b'];
                        $_SESSION['ACCESO']  = $datos['USUARI_InPeToGu__b'];
                        $_SESSION['USUARICBX']      = $datos['USUARI_UsuaCBX___b'];
                        $_SESSION['PROYECTO_CRM']  = $datos['USUARI_ConsInte__PROYEC_b'];
                        $_SESSION['HUESPED_CRM']  = $datos['USUARI_ConsInte__PROYEC_b'];
                        $_SESSION['LOGIN_OK']  = true;
                        $_SESSION['QUALITY'] = $QUALITY;
                        $_SESSION['CARGO']  = $datos['USUARI_Cargo_____b'];
                        $_SESSION['IntentosFallidos']  = $datos['USUARI_IntentosFallidos'];
    

                        //Validar Configuraciones de Seguridad
                        $Huesped= $datos['USUARI_ConsInte__PROYEC_b'];
                        $Seguridad= ConsultarConfigSeguridad($Huesped);

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
    
                        $imagenUser = "assets/img/user2-160x160.jpg";
                        if (file_exists("/var/../Dyalogo/img_usuarios/usr" . $datos['USUARI_UsuaCBX___b'] . ".jpg")) {
                            $imagenUser = "/DyalogoImagenes/usr" . $datos['USUARI_Foto______b'];
                        }
                        $_SESSION['IMAGEN']  = $imagenUser;
    
    
                        $datosSesion[0]['CODIGO'] = $user;
                        $datosSesion[0]['NOMBRES']   = $datos['USUARI_Nombre____b'];
                        $datosSesion[0]['IDENTIFICACION'] = $datos['USUARI_ConsInte__b'];
                        $datosSesion[0]['FECHA']  = $datos['USUARI_FechCrea__b'];
                        $datosSesion[0]['ACCESO']  = $datos['USUARI_InPeToGu__b'];
                        $datosSesion[0]['PROYECTO_CRM']  = $datos['USUARI_ConsInte__PROYEC_b'];
                        $datosSesion[0]['HUESPED_CRM'] = $datos['USUARI_ConsInte__PROYEC_b'];
                        $datosSesion[0]['LOGIN_OK']  = true;
                        $datosSesion[0]['iat'] = time();
                        $datosSesion[0]['exp'] = time() + 60;
                       /*   $p = new OAuthProvider();
                        $_SESSION['TOKEN']  = $p->generateToken(8);  */
    
                    //}
    
                    $jwt = JWT::encode($datosSesion, '');
                    $_SESSION['TOKEN']  = $jwt;
                    if($QUALITY == '1'){
                        if($datos['USUARI_Cargo_____b'] == 'calidad' || $datos['USUARI_Cargo_____b'] == 'super-administrador' || $datos['USUARI_Cargo_____b'] == 'administrador' || $datos['USUARI_Cargo_____b'] == 'owner' || $datos['USUARI_Cargo_____b'] == 'administrador-avanzado'){
                           
                            $licenses = validateLicense($datos['USUARI_ConsInte__b'], $Huesped, 'calidad');
                            if(isset($licenses['status']) && !$licenses['status']){
                                $_SESSION['message_license'] = $licenses['message'];
                                header('Location: login.php?quality='.$QUALITY); 
                                exit;
                            }

                            saveSession($datos['USUARI_ConsInte__b'],'calidad');
                            /*
                            $activity = registerActivity($datos['USUARI_ConsInte__b'], $datos['USUARI_ConsInte__PROYEC_b'], 'login', 'calidad');
                            if (isset($activity['status']) && !$activity['status']) {
                                $_SESSION['message_license'] = $activity['message'];
                                header('Location: login.php?quality='.$QUALITY); 
                            } else {
                                $_SESSION['ACTIVITY_ID'] = $activity['activityId'];
                                $_SESSION['app_session']  = 'calidad';
                                header('Location: index.php?quality='.$QUALITY);
                            }
                            */
                            header('Location: index.php?quality='.$QUALITY);
                
                        }else{
                           $_SESSION['register']='denegado';
                           header('Location: login.php?quality='.$QUALITY); 
                        }
                    }elseif($datos['USUARI_Cargo_____b'] == 'backoffice' || $datos['USUARI_Cargo_____b'] == 'owner' || $datos['USUARI_Cargo_____b'] == 'super-administrador' || $datos['USUARI_Cargo_____b'] == 'administrador' || $datos['USUARI_Cargo_____b'] == 'agente' || $datos['USUARI_Cargo_____b'] == 'administrador-avanzado' ){

                        $licenses = validateLicense($datos['USUARI_ConsInte__b'], $Huesped, 'backoffice');
                        if(isset($licenses['status']) && !$licenses['status']){
                            $_SESSION['message_license'] = $licenses['message'];
                            header('Location: login.php?quality='.$QUALITY); 
                            exit;
                        }

                        saveSession($datos['USUARI_ConsInte__b'],'backoffice');
                        /*
                        $activity = registerActivity($datos['USUARI_ConsInte__b'], $datos['USUARI_ConsInte__PROYEC_b'], 'login', 'backoffice');
                        if (isset($activity['status']) && !$activity['status']) {
                            $_SESSION['message_license'] = $activity['message'];
                            header('Location: login.php?quality='.$QUALITY); 
                        } else {
                            $_SESSION['ACTIVITY_ID'] = $activity['activityId'];
                            $_SESSION['app_session']  = 'backoffice';
                            header('Location: index.php?quality='.$QUALITY);
                        }
                        */
                        header('Location: index.php?quality='.$QUALITY);
            
                    }else{
                        $_SESSION['register']='denegado';
                        header('Location: login.php?quality='.$QUALITY); 
                    }
                }else{
                    $_SESSION['register'] = 'inactivo';
                    header('Location: login.php?quality='.$QUALITY);
                }
            }else{
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

                $_SESSION['register']='fail';
                header('Location: login.php?quality='.$QUALITY);
                //header('Location: login.php');
            }
        } else {
             // si el resultado de la query no es positivo
             // devolvemos que el usuario o contraseña son erroneos
            $_SESSION['register']='noexiste';
            header('Location: login.php?quality='.$QUALITY);
        }


    }

    if(isset($_POST['operacion'])){
        if($_POST['operacion'] == "ADD"){
            $Lsql = "INSERT INTO ".$BaseDatos_systema.".USUARI (USUARI_Codigo____b, USUARI_Nombre____b,  USUARI_Identific_b, USUARI_Cargo_____b, USUARI_Clave_____b) VALUES ('".$_POST['CodigoUsuario']."' , '".$_POST['NombreUsuario']."' , '".$_POST['IdentificacionUsuario']."' , '".$_POST['RolesUsuario']."' , '".encriptaPassword($_POST['PasswordUsuario'])."' )";

            if ($mysqli->query($Lsql) === TRUE) {
                header('Location: index.php?pagina=usuarios');
            } else {
                echo "Error Hacieno el proceso los registros : " . $mysqli->error;
            }
        }
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

    function saveSession($usuario,$app){
        global $mysqli;
        $sql=$mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.CRMSESSION (CRMSESSION_ConsinteUsuari,CRMSESSION_App) VALUES ($usuario,'{$app}')");
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

        if($updateDate != null || $isClaveTemp == -1){

            $ConsultaSQL= "SELECT pass_cambio_periodico_requerido, pass_dias_cambio_periodico, pass_cambio_login_requerido FROM dyalogo_general.huespedes WHERE id = {$huesped} LIMIT 1;";
            $sqlExpValidation = $mysqli->query($ConsultaSQL);
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

?>
