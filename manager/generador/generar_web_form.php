<?php
    function generar_Web_Form($int_Id_Generar, $webFormId = null, $webformTipo = null){

        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;
        
        if($int_Id_Generar != 0){

            $strConfirmamosQueElGuionTieneAdjuntos_t = 0;
            $str_guion      = 'G'.$int_Id_Generar;
            $codigoBody     = '';
            $url_gracias    = '';
            $scriptInsertar = '';
            $codigoForm     = '';
            $imagen         = '';
            $optin          = '';
            $correos_        = '';
            $captcha          ='';
            $enviocaptcha ='';
            $nombreCampana = '';
            $recibocaptcha ='';
            $cierracaptcha='';

            // Obtengo el idHuesped
            $idHuesped = isset($_SESSION['HUESPED']) ? $_SESSION['HUESPED'] : 0;

            $patacon        = '';
            /* primero obtenemos el paso */

            // Este paso solo se realizara solo si webformId es null EN DONDE LO QUE PARECE QUE HACE ES QUE AGREGA FUNCIONALIDAD DE INSERTAR EN MUESTRA DE CAMPAÑA SALIENTE CONECTADA
            if(is_null($webFormId)){
                $LsqlPaso = "SELECT G13_C134 FROM ".$BaseDatos_systema.".G13 WHERE G13_C132 = ".$int_Id_Generar;
                $res = $mysqli->query($LsqlPaso);
                if($res){
                    $dataP = $res->fetch_array();
                    /* ahora toca ver si ese paso esta asociado a una campaña saliente */
                    $LslEscon = "SELECT ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_Tipo_Insercion_b,  ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Fecha_b, ESTCON_Operacion_Hora_b, ESTCON_Cantidad_Fecha_b, ESTCON_Cantidad_Hora_b FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTPAS_Des_b = ".$dataP['G13_C134'];
                    $resEscon = $mysqli->query($LslEscon);
                    if($resEscon){
                        while ($key = $resEscon->fetch_object()) {
                            /* toca ver si son de tipo campaña saliente */
                            $lsqLPasoN = "SELECT ESTPAS_Tipo______b , ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$key->ESTCON_ConsInte__ESTPAS_Has_b;
                            $reslsqlPasoN = $mysqli->query($lsqLPasoN);
                            while ($obj = $reslsqlPasoN->fetch_object()) {
                                /* preguntamos si el paso es campaña saliente */
                                if($obj->ESTPAS_Tipo______b == '6' && !is_null($obj->ESTPAS_ConsInte__CAMPAN_b)){
                                    /* CAMPAN_ConsInte__MUESTR_b */
                                    $CampanLsql = "SELECT CAMPAN_ConsInte__MUESTR_b , CAMPAN_ConsInte__GUION__Pob_b , CAMPAN_ConfDinam_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$obj->ESTPAS_ConsInte__CAMPAN_b;
                                    $resCampan  = $mysqli->query($CampanLsql);
                                    if($resCampan && $resCampan->num_rows == 1){
                                        $dataC      = $resCampan->fetch_array();
    
    
                                    if($key->ESTCON_Tipo_Insercion_b == '-1'){
                                        /* toca agendarlo */
                                        $fechaHora = '
                    $nuevafecha = "NULL";';
                                        if($key->ESTCON_ConsInte_PREGUN_Fecha_b == '-1'){
                                            /* toca sumarle datos a la fecha actual*/
                                            $operador = '';
                                            if($key->ESTCON_Operacion_Fecha_b == '1'){
                                                $operador = '+';
                                            }else{
                                                $operador = '-';
                                            }
                                            if($key->ESTCON_Cantidad_Fecha_b != ''){
                                                $fechaHora .= '
                    $fecha = date(\'Y-m-d\');
                    $nuevafecha = strtotime ( \''.$operador.$key->ESTCON_Cantidad_Fecha_b.' day\' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date(\'Y-m-d\',$nuevafecha);';
                                            }
                                        }else{
                                            /* toca sumarle dias a un campo que tiene fecha */
                                            if($key->ESTCON_Cantidad_Fecha_b != ''){
                                                $fechaHora .= '
                    $FechaLsql = "SELECT G'.$int_Id_Generar.'_C'.$key->ESTCON_ConsInte_PREGUN_Fecha_b.' as Fecha FROM ".$BaseDatos.".G'.$int_Id_Generar.' WHERE G'.$int_Id_Generar.'_ConsInte__b = ".$ultimoResgistroInsertado;
                    $res = $mysqli->query($FechaLsql);
                    $fecha = $res->fetch_array();
                    $fecha = date(\'Y-m-d\' , $fecha[\'Fecha\']);
                    $nuevafecha = strtotime ( \''.$operador.$key->ESTCON_Cantidad_Fecha_b.' day\' , strtotime ( $fecha ) ) ;
                    $nuevafecha = date(\'Y-m-d\',$nuevafecha);';
                                            }
                                        }
    
    
                                        if($key->ESTCON_ConsInte_PREGUN_Hora_b == '-1'){
                                            /* toca sumarle datos a la fecha actual*/
                                            $operador = '';
                                            if($key->ESTCON_Operacion_Hora_b == '1'){
                                                $operador = '+';
                                            }else{
                                                $operador = '-';
                                            }
                                            
                                            if($key->ESTCON_Cantidad_Hora_b != ''){
                                                $fechaHora .= '
                    $hora = date(\'H:i:s\');
                    $nuevahora = strtotime ( \''.$operador.$key->ESTCON_Cantidad_Hora_b.' hour\' , strtotime ( $hora ) ) ;
                    $nuevahora = date(\'H:i:s\',$nuevahora);
                    $nuevafecha = $nuevafecha.$nuevahora;';
                                            }
                                        }else{
                                            /* toca sumarle dias a un campo que tiene fecha */
                                            if($key->ESTCON_Cantidad_Hora_b != ''){
                                                $fechaHora .= '
                    $HoraLsql = "SELECT G'.$int_Id_Generar.'_C'.$key->ESTCON_ConsInte_PREGUN_Hora_b.' as Hora FROM ".$BaseDatos.".G'.$int_Id_Generar.' WHERE G'.$int_Id_Generar.'_ConsInte__b = ".$ultimoResgistroInsertado;
                    $res = $mysqli->query($HoraLsql);
                    $hora = $res->fetch_array();
                    $hora = date(\'H:i:s\' , $hora[\'Hora\']);
                    $nuevahora = strtotime ( \''.$operador.$key->ESTCON_Cantidad_Hora_b.' hour\' , strtotime ( $hora ) ) ;
                    $nuevahora = date(\'H:i:s\',$nuevahora);
                    $nuevafecha = $nuevafecha.$nuevahora;';
                                            }
                                        }
    
                                        if($dataC['CAMPAN_ConfDinam_b'] == '-1'){
                                            $patacon .= '
                    $muestraCompleta = "G'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'_M'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'";
                    '.$fechaHora.'
                    $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b, ".$muestraCompleta."_FecHorAge_b ) VALUES (".$ultimoResgistroInsertado.", 0 , 2, \'".$nuevafecha."\');";
                    $mysqli->query($insertarMuestraLsql);  
                    ';
    
                                        }else{
                                            /* toca buscar al agente con menos registros */
                                            $patacon .= '
                    $muestraCompleta = "G'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'_M'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'";
    
                    '.$fechaHora.'
    
                    /* primero buscamos el que tenga el menor numero de registros */
                    
                    $Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM     ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = '.$obj->ESTPAS_ConsInte__CAMPAN_b.' AND (".$muestraCompleta."_Estado____b <> 3 OR (".$muestraCompleta."_Estado____b IS NULL)) AND (ASITAR_Automaticos_b <> 0 OR (ASITAR_Automaticos_b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";
                    $res = $mysqli->query($Xlsql);
                    $datoLsql = $res->fetch_array();
    
                    /* ahora insertamos los datos en la muestra */
                    
                    $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b, ".$muestraCompleta."_FecHorAge_b) VALUES (".$ultimoResgistroInsertado.", 0 , 2, ".$datoLsql[\'ASITAR_ConsInte__USUARI_b\'].", \'".$nuevafecha."\');";
                    $mysqli->query($insertarMuestraLsql);  
    
    
                                        ';
                                        }
    
                                        
                                    }else{
    
                                        if($dataC['CAMPAN_ConfDinam_b'] == '-1'){
                                            $patacon .= '
                    $muestraCompleta = "G'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'_M'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'";
                    $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b) VALUES (".$ultimoResgistroInsertado.", 0 , 0);";
                    $mysqli->query($insertarMuestraLsql);  
                    ';
                                        }else{
                                            /* toca buscar al agente con menos registros */
                                            $patacon .= '
                    $muestraCompleta = "G'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'_M'.$dataC['CAMPAN_ConsInte__GUION__Pob_b'].'";
                    /* primero buscamos el que tenga el menor numero de registros */
                    
                    $Xlsql = "SELECT ASITAR_ConsInte__USUARI_b, COUNT(".$muestraCompleta."_ConIntUsu_b) AS total FROM     ".$BaseDatos_systema.".ASITAR LEFT JOIN ".$BaseDatos.".".$muestraCompleta." ON ASITAR_ConsInte__USUARI_b = ".$muestraCompleta."_ConIntUsu_b WHERE ASITAR_ConsInte__CAMPAN_b = '.$obj->ESTPAS_ConsInte__CAMPAN_b.' AND (".$muestraCompleta."_Estado____b <> 3 OR (".$muestraCompleta."_Estado____b IS NULL)) AND (ASITAR_Automaticos_b <> 0 OR (ASITAR_Automaticos_b IS NULL)) GROUP BY ASITAR_ConsInte__USUARI_b ORDER BY COUNT(".$muestraCompleta."_ConIntUsu_b) LIMIT 1;";
                    $res = $mysqli->query($Xlsql);
                    $datoLsql = $res->fetch_array();
    
                    /* ahora insertamos los datos en la muestra */
                    
                    $insertarMuestraLsql = "INSERT INTO  ".$BaseDatos.".".$muestraCompleta." (".$muestraCompleta."_CoInMiPo__b ,  ".$muestraCompleta."_NumeInte__b, ".$muestraCompleta."_Estado____b , ".$muestraCompleta."_ConIntUsu_b) VALUES (".$ultimoResgistroInsertado.", 0 , 0, ".$datoLsql[\'ASITAR_ConsInte__USUARI_b\'].");";
                    $mysqli->query($insertarMuestraLsql);  
    
    
                                            ';
                                            }
                                        }
                                    }
                                }                            
                            }                       
                        }
                    }
                      
                }
            }
            
            // Traemos la configureacion especifica del webform, aqui vamos a tener dos opciones por el momento, si viene solo bd o bd/webform
            $WFsql = "SELECT WEBFORM_Ruta_Logo_b AS rutaLogo,WEBFORM_Ruta_Css_b AS rutaCss, WEBFORM_tipo_redireccion, WEBFORM_url_analytics_b, WEBFORM_codigo_propiedad_b, WEBFORM_id_redireccion_b, WEBFORM_url_redireccion_b FROM ".$BaseDatos_systema.".WEBFORM";
            if(is_null($webFormId)){
                $WFsql .= " WHERE WEBFORM_Guion____b = ".$int_Id_Generar." LIMIT 1";
            }else{
                $WFsql .= " WHERE WEBFORM_Consinte__b = " . $webFormId;
            }
            $res_wf = $mysqli->query($WFsql);

            $rutaLogo = "";
            $rutaCss = "";

            $WEBFORM_tipo_redireccion = null;
            $WEBFORM_url_analytics_b = null;
            $WEBFORM_codigo_propiedad_b = null;

            $WEBFORM_id_redireccion_b = null;
            $WEBFORM_url_redireccion_b = null;

            if($res_wf->num_rows > 0){
                $itemWf = $res_wf->fetch_array();

                $WEBFORM_tipo_redireccion = $itemWf["WEBFORM_tipo_redireccion"];
                $WEBFORM_url_analytics_b = $itemWf["WEBFORM_url_analytics_b"];
                $WEBFORM_codigo_propiedad_b = $itemWf["WEBFORM_codigo_propiedad_b"];

                $WEBFORM_id_redireccion_b = $itemWf["WEBFORM_id_redireccion_b"];
                $WEBFORM_url_redireccion_b = $itemWf["WEBFORM_url_redireccion_b"];

                if(isset($itemWf) && !is_null($itemWf['rutaLogo'])){
                    $rutaLogo = $itemWf['rutaLogo'];
                    $rutaLogo = '<img src="'.$rutaLogo.'" style="width:100%;max-width:400px;" alt="logo">';
                    
                    if(!is_null($itemWf['rutaCss'])){
                        $rutaCss = str_replace('/var/www/html/', '/', $itemWf['rutaCss']);
                    }
                }
            }

            // CODIGO PARA LOS FORMULARIO WEB DEL AGENDADOR
            $iframe='';
            $classBody="login-box";
            $agendador=false;
            $script='';
            $btnSubmit='<div class="row">
            <div class="col-xs-2">
                &nbsp;
            </div><!-- /.col -->
            <div class="col-xs-8">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar datos</button>
            </div><!-- /.col -->
        </div>';
            $sqlAg=$mysqli->query("SELECT AGENDADOR_ConsInte__b,AGENDADOR_ConsInte__GUION__Dis_b FROM {$BaseDatos_systema}.AGENDADOR WHERE AGENDADOR_ConsInte__GUION__Dis_b={$int_Id_Generar}");
            if($sqlAg && $sqlAg->num_rows == 1){
                $sqlAg=$sqlAg->fetch_object();
                $classBody="box-body";
                $iframe="<iframe src=\"https://interno.dyalogo.cloud/crm_php/formularios/agendador/agendador.php?formulario={$int_Id_Generar}&yourfather=123\" frameborder=\"0\" width='100%' height='700px' id='agCitas'></iframe>";
                $agendador=true;
                $btnSubmit='';
            }

            $Lsql = "SELECT id, titulo, color_fondo, color_letra, url_gracias, codigo_a_insertar ,nombre_imagen, tipo_optin, tipo_gracias, id_dy_ce_configuracion, Asunto_mail, Cuerpo_mail, id_pregun  FROM ".$BaseDatos_systema.".GUION_WEBFORM WHERE id_guion = ".$int_Id_Generar;
            $res = $mysqli->query($Lsql);

            if($res->num_rows > 0){

                while ($key = $res->fetch_object()) {
                    
                    if($key->color_fondo != ''){
                        $codigoBody .= '
            if($_GET[\'v\'] == \''.$key->id.'\'){
                echo \'<body class="hold-transition" style="background-color:'.$key->color_fondo.'; color:'.$key->color_letra.'" >\';
            }';

                    $codigoForm     .= '
            if($_GET[\'v\'] == \''.$key->id.'\'){
                echo \'<div class="login-box-body"  style="background-color:'.$key->color_fondo.'; color:'.$key->color_letra.'">\';
                echo \'<p class="login-box-msg font_2" >'.$key->titulo.'</p>\';
            }';
                    }
                    
                    if($key->url_gracias != ''){
                        $url_gracias .= '
                                if($_GET[\'v\'] == \''.$key->id.'\'){
                                    echo \'window.location.href = "'.$key->url_gracias.'"\';
                                }';
                    }
                    
                    if( $key->codigo_a_insertar != ''){
                        $scriptInsertar .= '
                        if($_GET[\'v\'] == \''.$key->id.'\'){
                            echo \''.str_replace("'", "\'", $key->codigo_a_insertar).'\';
                        }';
                    }
                   

                    $imagen .='
                        if($_GET[\'v\'] == \''.$key->id.'\'){';
                        if(!is_null($key->nombre_imagen) && $key->nombre_imagen != ''){
                            $imagen .='echo \'<img style="width:100%;height:auto;" src="assets/img/plantilla/'.$int_Id_Generar.'/'.$key->nombre_imagen.'"  alt="'.$key->titulo.'">\';
                        }';
                        }else{
                            $imagen .='echo \'<img src="assets/img/logo_dyalogo_mail.png"  alt="Dyalogo">\';
                        }';
                        }

                    if($key->tipo_optin != 0){
                        $optin .= '
                                <?php if($_GET[\'v\'] == \''.$key->id.'\'){ ?>
                                    <input type= "hidden" name="OPTIN_DY_WF" id="OPTIN_DY_WF" value="'.$key->tipo_optin.'" >
                                    <input type= "hidden" name="v" id="v" value="'.$key->id.'" >
                                <?php } ?>';
                    }
                   

                    $urlparaheader = 'if(!isset($_POST[\'v\'])){ header(\'Location:https://\'.$_SERVER[\'HTTP_HOST\'].\'/crm_php/web_forms.php?web2='.base64_encode($int_Id_Generar.'_'.$webFormId).'&result=1\'); } else { header(\'Location:https://\'.$_SERVER[\'HTTP_HOST\'].\'/crm_php/web_forms.php?web2='.base64_encode($int_Id_Generar.'_'.$webFormId).'&result=1&v=\'.$_POST[\'v\']); }';
                    if($key->url_gracias != '' && $key->url_gracias != null && !is_null($key->url_gracias)){
                        $urlparaheader = 'header(\'Location:'.$key->url_gracias.'\');';
                    }


                    if( (!empty($key->Cuerpo_mail)) && (!is_null($key->Cuerpo_mail)) ){
                        
                        $LsqCorreo = "SELECT direccion_correo_electronico, servidor_saliente_direccion, servidor_saliente_puerto, contrasena FROM ".$dyalogo_canales_electronicos.".dy_ce_configuracion WHERE id = ".$key->id_dy_ce_configuracion;
                        $resLsql =  $mysqli->query($LsqCorreo);
                        $datosCorreo = $resLsql->fetch_array();



                        $correos_ .='
                    if($_POST[\'v\'] == \''.$key->id.'\'){
                        $strCuerpo_t = \'<html><body><font face="arial" size="3">\';
                        $strCuerpo_t .= \''.$key->Cuerpo_mail.'\';

                        if($_POST[\'OPTIN_DY_WF\'] == 2){
                            $strCuerpo_t .= \'
<p style="text-align:justify;">
    <table width="100%">
        <tr>
            <td width="50%" style="text-align:right;">
                <a style="text-decoration: none; padding: 10px; font-weight: 600; font-size: 20px; color: #ffffff; background-color: #1883ba; border-radius: 6px; border: 2px solid #0016b0;" href="https://'.$_SERVER['HTTP_HOST'].'/crm_php/web_forms.php?web2='.base64_encode($int_Id_Generar.'_'.$webFormId).'&aceptaTerminos=acepto&cons=\'.base64_encode($ultimoResgistroInsertado).\'">
                    Si acepto.
                </a>
            </td>
            <td width="50%" style="text-align:left;">
                <a style="text-decoration: none; padding: 10px; font-weight: 600; font-size: 20px; color: #ffffff; background-color: #1883ba; border-radius: 6px; border: 2px solid #0016b0;" href="https://'.$_SERVER['HTTP_HOST'].'/crm_php/web_forms.php?web2='.base64_encode($int_Id_Generar.'_'.$webFormId).'&aceptaTerminos=declino&cons=\'.base64_encode($ultimoResgistroInsertado).\'">
                    No acepto.
                </a>
            </td>
        </tr>
    </table>
</p>\';
                        }
                        $strCuerpo_t .= \'
</font></body></html>\';
                        $email_user = "'.$datosCorreo['direccion_correo_electronico'].'";
                        $email_password = "'.$datosCorreo['contrasena'].'";
                        $the_subject = "'.$key->titulo.'";

                        
                        $from_name = "Notificaciones";

                        require "../PHPMailer/class.phpmailer.php";
                        require "../PHPMailer/class.smtp.php";

                        $phpmailer = new PHPMailer(); 
                        $phpmailer->CharSet = \'UTF-8\';
                        // ---------- datos de la cuenta de Gmail -------------------------------
                        $phpmailer->Username = $email_user;
                        $phpmailer->Password = $email_password; 
                        //-----------------------------------------------------------------------
                        // $phpmailer->SMTPDebug = 1;
                        $phpmailer->SMTPSecure = \'ssl\';
                        $phpmailer->Host = "'.$datosCorreo['servidor_saliente_direccion'].'"; // GMail
                        $phpmailer->Port = '.$datosCorreo['servidor_saliente_puerto'].';
                        $phpmailer->IsSMTP(); // use SMTP
                        $phpmailer->SMTPAuth = true;
                        $phpmailer->setFrom($phpmailer->Username,$from_name);
                        $phpmailer->AddAddress($_POST[\'G'.$int_Id_Generar.'_C'.$key->id_pregun.'\']);
                        
                        $phpmailer->Subject = $the_subject; 
                        $phpmailer->Body = $strCuerpo_t;
                        $phpmailer->IsHTML(true);   
                        if (!$phpmailer->Send()){
                            echo $phpmailer->ErrorInfo." \n";
                        } else {
                            '.$urlparaheader.'
                        }
                    }';

                    }
                        
                }
            }
            $validacaptcha='select GUION_WebFormCaptcha as captcha from '.$BaseDatos_systema.'.GUION_ WHERE GUION__ConsInte__b = '.$int_Id_Generar;
            $validacaptcha=$mysqli->query($validacaptcha);
            if($validacaptcha){
                $validacaptcha=$validacaptcha->fetch_object();
                if($validacaptcha->captcha == '1'){
                    $captcha='<script src="https://www.google.com/recaptcha/api.js?render=6Lcc1dYUAAAAAIEO_HV7QhIhz36kA_7EfngIkhwG"></script>';
                    $enviocaptcha="event.preventDefault(); 
            grecaptcha.ready(function() {            
                grecaptcha.execute('6Lcc1dYUAAAAAIEO_HV7QhIhz36kA_7EfngIkhwG', {action: 'envio_captcha'}).then(function(token) {
                    $('#formLogin').prepend('<input type=\"hidden\" name=\"tokens\" value='+ token + '>');
                    $('#formLogin').prepend('<input type=\"hidden\" name=\"action\" value=\"envio_captcha\">');
                    $('#formLogin').unbind('submit').submit();
                });
            });";
            $recibocaptcha='if(isset($_POST["tokens"])){
        $token = $_POST["tokens"];
        $action = $_POST["action"];
        
        // call curl to POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("secret" => RECAPTCHA_V3_SECRET_KEY, "response" => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);

        // verificamos la respuesta
        if($arrResponse["success"] == "1" && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {';    
            $cierracaptcha='} else {
            echo header(\'Location:https://\'.$_SERVER[\'HTTP_HOST\'].\'/crm_php/web_forms.php?web=MjA5Mw==&result=0\');
        }         
    }';    
                        
                }
            }
            $index = '<?php 
    /*
        Document   : index v2
        Created on : '.date('Y-m-d H:i:s').'
        Author     : Jose David y su poderoso generador, La gloria sea Para DIOS 
        Url        : id = '.base64_encode($int_Id_Generar.'_'.$webFormId).'  
    */
    $url_crud =  "formularios/'.$str_guion.'/'.$str_guion.'_CRUD_WEB'.$webFormId.'.php";
    include_once(__DIR__."/../../funciones.php");

    $arrPatron = json_decode(ObtenerPatron('.$idHuesped.'));    
    $patron = "";
    $patronSimple = "";

    if(!is_null($arrPatron)){

        if($arrPatron->patron_regexp){
            foreach($arrPatron->patron_regexp as $val){
                if(!is_null($val) || $val != ""){
                    $val = str_replace("\'", "", $val);
                    if($patron == ""){
                        $patron .= $val;
                    }else{
                        $patron .= "|".$val;
                    }
                }
            }
        }

        if($arrPatron->patron){
            foreach ($arrPatron->patron_ejemplo as $val) {
                if(!is_null($val) || $val != ""){
                    if($patronSimple == ""){
                        $patronSimple .= $val;
                    }else{
                        $patronSimple .= " o ".$val;
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Formulario de contacto</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        '.$captcha.'
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Date Picker -->
        <link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="assets/timepicker/jquery.timepicker.css"/>
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">

        <link rel="stylesheet" type="text/css" href="assets/plugins/sweetalert/sweetalert.css">

        <link rel="stylesheet" href="assets/plugins/select2/select2.min.css" />
        <!-- aqui va el css personalizado -->
        <link rel="stylesheet" href="'.$rutaCss.'" />
    
        <link href=\'//fonts.googleapis.com/css?family=Sansita+One\' rel=\'stylesheet\' type=\'text/css\'>
        <link href=\'//fonts.googleapis.com/css?family=Open+Sans+Condensed:300\' rel=\'stylesheet\' type=\'text/css\'>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <link rel="shortcut icon" href="assets/img/logo_dyalogo_mail.png"/>
        <style>
            
            .hed{
           
                font-family: \'Sansita One\', cursive; 
                color:white;
            }

            .hed2{
                text-align:center;
                font-family: \'Sansita One\', cursive; 
                font-size:25px; 
                color:#019CDE;
                margin-top: -9px;
            }
            .font_2 {
                font: normal normal normal 17px/1.4em Spinnaker,sans-serif;
                text-align:center;
            }
            
            .redonder {
                -webkit-border-radius: 20px;
                -moz-border-radius: 20px;
                border-radius: 20px;
                -webkit-box-shadow: 7px 7px 17px -9px rgba(0,0,0,0.75);
                -moz-box-shadow: 7px 7px 17px -9px rgba(0,0,0,0.75);
                box-shadow: 7px 7px 17px -9px rgba(0,0,0,0.75);
            }

            [class^=\'select2\'] {
                border-radius: 0px !important;
            }

            form#formLogin h3{
                font-size: 14px;
            }
        </style>
    </head>
    <?php  
        if(isset($_GET[\'v\'])){
            '.$codigoBody.'
        }else{
            echo \'<body class="hold-transition" >\';
        }
    ?>
    
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6" >
                <div class="'.$classBody.'">
                    <?php if(!isset($_GET[\'aceptaTerminos\'])){ ?>
                    <div class="login-logo hed">
                        <?php if(!isset($_GET[\'v\'])){ ?>
                            '.$rutaLogo.'
                        <?php }else{ 
                                '.$imagen.'
                            }
                        ?>

                    </div><!-- /.login-logo -->
                    <?php if(isset($_GET[\'v\'])){ 
                        '.$codigoForm.'
                    }else { ?>
                    <div class="login-box-body">
                        <p class="login-box-msg font_2" >FORMULARIO DE CONTACTO</p>
                    <?php } ?>
                        <form action="formularios/'.$str_guion.'/'.$str_guion.'_CRUD_WEB'.$webFormId.'.php" method="post" id="formLogin" enctype="multipart/form-data">';
            $str_Funciones_Js = '';
            $str_Funciones_Jsx = '';
            
       // $checkColumnas = $_POST['checkColumnas'];



            $str_guion = 'G'.$int_Id_Generar;
            $str_Alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az','ba','bb','bc','bd','be','bf','bg','bh','bi','bj','bk','bl','bm','bn','bo','bp','bq','br','bs','bt','bu','bv','bw','bx','by','bz','ca','cb','cc','cd','ce','cf','cg','ch','ci','cj','ck','cl','cm','cn','co','cp','cq','cr','cs','ct','cu','cv','cw','cx','cy','cz','da','db','dc','dd','de','df','dg','dh','di','dj','dk','dl','dm','dn','do','dp','dq','dr','ds','dt','du','dv','dw','dx','dy','dz','ea','eb','ec','ed','ee','ef','eg','eh','ei','ej','ek','el','em','en','eo','ep','eq','er','es','et','eu','ev','ew','ex','ey','ez','fa','fb','fc','fd','fe','ff','fg','fh','fi','fj','fk','fl','fm','fn','fo','fp','fq','fr','fs','ft','fu','fv','fw','fx','fy','fz','ga','gb','gc','gd','ge','gf','gg','gh','gi','gj','gk','gl','gm','gn','go','gp','gq','gr','gs','gt','gu','gv','gw','gx','gy','gz','ha','hb','hc','hd','he','hf','hg','hh','hi','hj','hk','hl','hm','hn','ho','hp','hq','hr','hs','ht','hu','hv','hw','hx','hy','hz','ia','ib','ic','id','ie','if','ig','ih','ii','ij','ik','il','im','in','io','ip','iq','ir','is','it','iu','iv','iw','ix','iy','iz','ja','jb','jc','jd','je','jf','jg','jh','ji','jj','jk','jl','jm','jn','jo','jp','jq','jr','js','jt','ju','jv','jw','jx','jy','jz');

            $str_Guion_C = $str_guion."_C";
            $str_Guionstr_Select2 ='';
            $str_ExtrasParaGuiones = '';
            $str_Funciones_Carga_Padres_Maestros = '';
            $str_Cancelar_Modal = "";
            $str_Guardar_Modal = "";

            $str_NombreGuion_ = "";

            $str_Lsql = "";

            // Si no llega el id del webform le hacemos con el G
            if(is_null($webFormId)){
                $str_Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b , PREGUN_IndiRequ__b, PREGUN_DefaNume__b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND PREGUN_WebForm_b = '-1' AND SECCIO_TipoSecc__b != 2 ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";
            }else{
                $str_Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b , PREGUN_IndiRequ__b, PREGUN_DefaNume__b FROM ".$BaseDatos_systema.".PREGUN INNER JOIN ".$BaseDatos_systema.".WEBFORM_CAMPOS ON WEBFORM_CAMPOS_ConsInte__PREGUN_b = PREGUN_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND WEBFORM_CAMPOS_ConsInte__WEBFORM_b = ".$webFormId." ORDER BY WEBFORM_CAMPOS_Orden_b ASC";
            }

            $str_Lsql2 = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b, PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b , PREGUN_DefaNume__b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND PREGUN_WebForm_b = '-1' AND SECCIO_TipoSecc__b != 2 ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";

            $str_LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b, GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$int_Id_Generar ." AND GUION__Tipo______b = 1";

            $GUION__ConsInte__PREGUN_Pri_b = null;
            $GUION__ConsInte__PREGUN_Sec_b = null;
            $GUION__ConsInte__PREGUN_Tip_b = null;
            $GUION__ConsInte__PREGUN_Rep_b = null;
            $GUION__ConsInte__PREGUN_Fag_b = null;
            $GUION__ConsInte__PREGUN_Hag_b = null;
            $GUION__ConsInte__PREGUN_Com_b = null;


            $res_CamposBuscadosIzquierda = $mysqli->query($str_LsqlCamposPrimairos);
            while($key = $res_CamposBuscadosIzquierda->fetch_object()){
                $GUION__ConsInte__PREGUN_Tip_b = $key->GUION__ConsInte__PREGUN_Tip_b;
                $GUION__ConsInte__PREGUN_Rep_b = $key->GUION__ConsInte__PREGUN_Rep_b;
                $GUION__ConsInte__PREGUN_Fag_b = $key->GUION__ConsInte__PREGUN_Fag_b;
                $GUION__ConsInte__PREGUN_Hag_b = $key->GUION__ConsInte__PREGUN_Hag_b;
                $GUION__ConsInte__PREGUN_Com_b = $key->GUION__ConsInte__PREGUN_Com_b;
            }//Cierro el While $key = $res_CamposBuscadosIzquierda->fetch_object()

            $str_LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$int_Id_Generar;
            $res_CamposBuscadosIzquierda = $mysqli->query($str_LsqlCamposPrimairos);
            while($key = $res_CamposBuscadosIzquierda->fetch_object()){
                $GUION__ConsInte__PREGUN_Pri_b = $key->GUION__ConsInte__PREGUN_Pri_b;
                $GUION__ConsInte__PREGUN_Sec_b = $key->GUION__ConsInte__PREGUN_Sec_b;
            }//cierro el While $key = $res_CamposBuscadosIzquierda->fetch_object()


            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $carpeta = "C:/www/crm_php/formularios/".$str_guion;
            } else {
                $carpeta = "/var/www/html/crm_php/formularios/".$str_guion;
            }
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777);
            }

            $fp = fopen($carpeta."/".$str_guion."_WEB".$webFormId.".php" , "w");

            $res_Campos_6 = $mysqli->query($str_Lsql2);
            $str_CamposTabla = '';
            $str_OrdenTabla = '';
            $str_campTabla = '';
            $str_str_JoinsTabla = '';
            $str_PrimerCampoaBuscar = '';
            $str_SegundoCampoaBuscar = '';
            $j = 0;

            $str_CamposValidaciones = '';
            $res_ValoresValidados = $mysqli->query($str_Lsql);
            $str_FechaValidadaOno = '';
            $str_HoraValidadaOno = '';
            $str_BotonSalvar = '';
            $int_HayQueValidar = 0;
            $str_Select2 = '';
            $str_FuncionesCampoGuion = '';
            $str_NumeroFuncion = '';
            $str_DecimalFuncion = '';
            $str_PrimerCamposJoin ='0';
            $str_Joins = '';

            while ($key = $res_ValoresValidados->fetch_object()) {

                if($key->PREGUN_IndiRequ__b != 0){
                    if($key->tipo_Pregunta == '6' || $key->tipo_Pregunta == '11'){
                        $str_CamposValidaciones .= "\n".' 
            if($("#'.$str_Guion_C.$key->id.'").val() < 1){
                alertify.error(\''.$key->error.'\');
                $("#'.$str_Guion_C.$key->id.'").focus();
                valido = 1;
            }'; 
                    }else if($key->tipo_Pregunta == '8'){
                        $str_CamposValidaciones .= "\n".' 
            if($("#'.$str_Guion_C.$key->id.'").is(\'checked\')){
                alertify.error(\''.$key->error.'\');
                $("#'.$str_Guion_C.$key->id.'").focus();
                valido = 1;
            }'; 
                    }else{
                        $str_CamposValidaciones .= "\n".' 
            if($("#'.$str_Guion_C.$key->id.'").val().length < 1){
                alertify.error(\''.$key->error.'\');
                $("#'.$str_Guion_C.$key->id.'").focus();
                valido =1;
            }'; 
                    }
                
                    $int_HayQueValidar += 1;
                }//cierro el If de $key->PREGUN_IndiRequ__b != 0


                if($key->tipo_Pregunta == '3' ){
                    $str_NumeroFuncion .= '
            $("#'.$str_Guion_C.$key->id.'").numeric();
            ';    
                }

                if($key->tipo_Pregunta == '4' ){
                    $str_DecimalFuncion .= '
            $("#'.$str_Guion_C.$key->id.'").inputmask("numeric", {
                radixPoint: ",",
                groupSeparator: ".",
                digits: 2,
                autoGroup: true,
                rightAlign: false,
                oncleared: function () { self.Value(\'\'); }
            });
            ';
                }

                if($key->tipo_Pregunta == '3' || $key->tipo_Pregunta == '4'){
                    if(!is_null($key->minimoNumero) && !is_null($key->maximoNumero) ){
                        $str_CamposValidaciones .= "\n".'
                if($("#'.$str_Guion_C.$key->id.'").val().length > 0){
                    if( $("#'.$str_Guion_C.$key->id.'").val() > '.($key->minimoNumero - 1).' && $("#'.$str_Guion_C.$key->id.'").val() < '.($key->maximoNumero + 1).'){

                    }else{
                        alertify.error(\''.$key->error.'\');
                        $("#'.$str_Guion_C.$key->id.'").focus();
                        valido =1;
                    }    
                }';
                    }else if(!is_null($key->minimoNumero) && is_null($key->maximoNumero)){
                        $str_CamposValidaciones .= "\n".'
                if($("#'.$str_Guion_C.$key->id.'").val().length > 0){
                    if( $("#'.$str_Guion_C.$key->id.'").val() > '.($key->minimoNumero - 1).'){
                        
                    }else{
                        alertify.error(\''.$key->error.'\');
                        $("#'.$str_Guion_C.$key->id.'").focus();
                        valido =1;
                    }    
                }';
                    }else if(is_null($key->minimoNumero) && !is_null($key->maximoNumero)){
                        $str_CamposValidaciones .= "\n".'
                if($("#'.$str_Guion_C.$key->id.'").val().length > 0){
                    if(  $("#'.$str_Guion_C.$key->id.'").val() < '.($key->maximoNumero + 1).'){
                        
                    }else{
                        alertify.error(\''.$key->error.'\');
                        $("#'.$str_Guion_C.$key->id.'").focus();
                        valido =1;
                    }    
                }';
                    }
                }//if $key->tipo_Pregunta == '3' || $key->tipo_Pregunta == '4'



                if($key->tipo_Pregunta == '5'){
                    if(!is_null($key->fechaMinimo) && !is_null($key->fechaMaximo) ){
                        $str_FechaValidadaOno .= "\n".'
            var startDate = new Date(\''.$key->fechaMinimo.'\');
            var FromEndDate = new Date(\''.$key->fechaMaximo.'\');
            $("#'.$str_Guion_C.$key->id.'").datepicker({
                language: "es",
                autoclose: true,
                startDate: startDate,
                endDate : FromEndDate,
                todayHighlight: true
            });';
                    }else if(!is_null($key->fechaMinimo) && is_null($key->fechaMaximo)){
                        $str_FechaValidadaOno .= "\n".'
            var startDate = new Date(\''.$key->fechaMinimo.'\');
            $("#'.$str_Guion_C.$key->id.'").datepicker({
                language: "es",
                autoclose: true,
                startDate: startDate,
                todayHighlight: true
            });';
                    }else if(is_null($key->fechaMinimo) && !is_null($key->fechaMaximo)){
                        $str_FechaValidadaOno .= "\n".'
            var FromEndDate = new Date(\''.$key->fechaMaximo.'\');
            $("#'.$str_Guion_C.$key->id.'").datepicker({
                language: "es",
                autoclose: true,
                endDate : FromEndDate,
                todayHighlight: true
            });';
                    }else{
                        $str_FechaValidadaOno .= "\n".'
            $("#'.$str_Guion_C.$key->id.'").datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true
            });';
                    }
                }//if $key->tipo_Pregunta == '5'

                if($key->tipo_Pregunta == '10'){
                    if(!is_null($key->horaMini) && !is_null($key->horaMaximo)){
                        $str_HoraValidadaOno .= "\n".'
            //Timepicker
            $("#'.$str_Guion_C.$key->id.'").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \''.$key->horaMini.'\',
                \'maxTime\': \''.$key->horaMaximo.'\',
                \'setTime\': \''.$key->horaMini.'\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';


                    }else if(!is_null($key->horaMini) && is_null($key->horaMaximo)){
                        $str_HoraValidadaOno .= "\n".'
            //Timepicker
            $("#'.$str_Guion_C.$key->id.'").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \''.$key->horaMini.'\',
                \'maxTime\': \'17:00:00\',
                \'setTime\': \''.$key->horaMini.'\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';

                    }else if(is_null($key->horaMini) && !is_null($key->horaMaximo)){
                        $str_HoraValidadaOno .= "\n".'
            //Timepicker
            $("#'.$str_Guion_C.$key->id.'").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \'08:00:00\',
                \'maxTime\': \''.$key->horaMaximo.'\',
                \'setTime\': \'08:00:00\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';

                    }else{
                        $str_HoraValidadaOno .= "\n".'
            //Timepicker
            $("#'.$str_Guion_C.$key->id.'").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \'08:00:00\',
                \'maxTime\': \'17:00:00\',
                \'setTime\': \'08:00:00\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';
                    }
                }//$key->tipo_Pregunta == '10'

                if(!is_null($key->minimoNumero) || !is_null($key->maximoNumero)){
                    $int_HayQueValidar += 1;
                }
            }//$key = $res_ValoresValidados->fetch_object()

            fputs($fp , $index);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea    

            // $SeccionSsql = "SELECT SECCIO_ConsInte__b, SECCIO_TipoSecc__b, SECCIO_Nombre____b, SECCIO_PestMini__b, SECCIO_NumColumnas_b FROM ".$BaseDatos_systema.".SECCIO WHERE SECCIO_ConsInte__GUION__b =  ".$int_Id_Generar." AND SECCIO_TipoSecc__b != 2 AND SECCIO_TipoSecc__b != 4 ORDER BY SECCIO_Orden_____b ASC ";

            // $Secciones = $mysqli->query($SeccionSsql);
            $Columnas = 1;

            $str_Select2_hojadeDatos = '';

            // while ($seccionAqui = $Secciones->fetch_object()) {

                // $id_seccion = $seccionAqui->SECCIO_ConsInte__b;
                // if(!empty($seccionAqui->SECCIO_NumColumnas_b)){
                //     $Columnas = $seccionAqui->SECCIO_NumColumnas_b ;
                // }
                
                
                //Aqui hacemos el dibujo de los campos
                if(is_null($webFormId)){
                    // Si no trae el id del webform me toca generarlo basado en la bd
                    $str_LsqlXD = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_PermiteAdicion_b , PREGUN_DefaNume__b , PREGUN_IndiRequ__b, PREGUN_TipoTel_b FROM ".$BaseDatos_systema.".PREGUN INNER JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND PREGUN_FueGener_b != 3 AND PREGUN_WebForm_b = '-1' ORDER BY SECCIO_Orden_____b ASC , PREGUN_OrdePreg__b ASC";
                }else{
                    $str_LsqlXD = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_PermiteAdicion_b , PREGUN_DefaNume__b , PREGUN_IndiRequ__b, PREGUN_TipoTel_b FROM ".$BaseDatos_systema.".PREGUN INNER JOIN ".$BaseDatos_systema.".WEBFORM_CAMPOS ON WEBFORM_CAMPOS_ConsInte__PREGUN_b = PREGUN_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND WEBFORM_CAMPOS_ConsInte__WEBFORM_b = ".$webFormId." AND PREGUN_FueGener_b != 3 ORDER BY WEBFORM_CAMPOS_Orden_b ASC";
                }


                $campos = $mysqli->query($str_LsqlXD);
                $rowsss = 0;

                $seccion = '';
                $seccionActual = '';

                $maxColumns = $Columnas;
                $filaActual = 0;
                $checkColumnas = (12 / $Columnas);
                while($obj = $campos->fetch_object()){
                    
                    if( $obj->id != $GUION__ConsInte__PREGUN_Tip_b && 
                        $obj->id != $GUION__ConsInte__PREGUN_Rep_b &&
                        $obj->id != $GUION__ConsInte__PREGUN_Fag_b && 
                        $obj->id != $GUION__ConsInte__PREGUN_Hag_b &&
                        $obj->id != $GUION__ConsInte__PREGUN_Com_b){
                
                        $valorPordefecto = $obj->PREGUN_Default___b;
                        $valoraMostrar = "";
                        $disableds = '';
                        $asterix = '';
                        if($obj->PREGUN_ContAcce__b == 2){
                            $disableds = 'readonly';
                            
                        }
                        switch ($valorPordefecto) {
                            case '501':
                                $valoraMostrar = '<?php echo date(\'Y-m-d\');?>';
                                break;

                            case '1001':
                                $valoraMostrar = '<?php echo date(\'H:i:s\');?>';
                                break;

                            case '102':
                                $valoraMostrar = '<?php echo getNombreUser($token);?>';
                                break;
                            
                            case '105':
                                $valoraMostrar = '<?php $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
                        $resCampa = $mysqli->query($cmapa);
                        $dataCampa = $resCampa->fetch_array(); echo $dataCampa["CAMPAN_Nombre____b"];?>';
                                break;

                            case '3001':

                                $valoraMostrar = $obj->PREGUN_DefaNume__b;

                                break;

                            case '3002':

                                //Es el autonumerico
                                $valoraMostrar = '<?php $Lsql = "SELECT CONTADORES_Valor_b FROM ".$BaseDatos_systema.".CONTADORES WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'"; $res = $mysqli->query($Lsql); $dato = $res->fetch_array(); echo ($dato["CONTADORES_Valor_b"] + 1); $XLsql = "UPDATE ".$BaseDatos_systema.".CONTADORES SET CONTADORES_Valor_b = CONTADORES_Valor_b + 1 WHERE CONTADORES_ConsInte__PREGUN_b = '.$obj->id.'"; $mysqli->query($XLsql);?>';
                                
                                break;

                            default:
                                $valoraMostrar = null;
                                break;
                        }

                        $required = '';
                        if($obj->PREGUN_IndiRequ__b == '-1'){
                            $required = 'required';
                            $asterix  = '*';
                        }

                        $validarPatron = '';
                        $classTelefono = '';
                        $placeholderTelefono = '';
                        if($obj->PREGUN_TipoTel_b == '-1'){
                            $validarPatron = '<?php if($patron != ""){?> pattern="<?php echo $patron ?>" title="<?php echo $patronSimple ?>" <?php } ?>';
                            $classTelefono = 'telefono';
                            $placeholderTelefono = ' <?php if($patron != ""){ echo \'con formato como \'.$patronSimple;}else{ echo "";} ?>';
                        }

                        switch ($obj->tipo_Pregunta) {
                            case '15':
                            $strConfirmamosQueElGuionTieneAdjuntos_t ++;
                                $campo = ' 
                            <!-- CAMPO TIPO ADJUNTO -->
                            <div class="form-group">
                                <label for="F'.$str_Guion_C.$obj->id.'" id="LblF'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <input type="file" class="adjuntos" id="F'.$str_Guion_C.$obj->id.'" name="F'.$str_Guion_C.$obj->id.'">
                                <p class="help-block">
                                    Peso maximo del archivo 9 MB
                                </p>
                            </div>';

                               fputs($fp , $campo);
                               fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                            break;
                            case '1':
                                $campo = ' 
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <input type="text" class="form-control input-sm '.$classTelefono.'" id="'.$str_Guion_C.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$str_Guion_C.$obj->id.'"  placeholder="'.($obj->titulo_pregunta.$placeholderTelefono).'" '.$required.' '.$validarPatron.'>
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->';
                                $str_Funciones_Js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){});';
    if($agendador){
        $script="\n".'
        //function para '.($obj->titulo_pregunta).' '."\n".'
        $("#'.$str_Guion_C.$obj->id.'").keyup(function(){
            if($(this).val().length >= 6){
                let iframe = document.getElementById(\'agCitas\');
                iframe.contentWindow.postMessage($(this).val(), \'*\');
            }
        });';
    }
                               fputs($fp , $campo);
                               fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                            break;

                            
                            case '14':
                                $campo = ' 
                            <!-- CAMPO TIPO EMAIL -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <input type="email" class="form-control input-sm" id="'.$str_Guion_C.$obj->id.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$str_Guion_C.$obj->id.'"  placeholder="'.($obj->titulo_pregunta).'" '.$required.'>
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->';
                                $str_Funciones_Js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){});';
                               fputs($fp , $campo);
                               fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                            break;

                            case '2':
                                $campo = '  
                            <!-- CAMPO TIPO MEMO -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <textarea class="form-control input-sm" name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" '.$disableds.' value="'.$valoraMostrar.'" placeholder="'.($obj->titulo_pregunta).'" '.$required.'></textarea>
                            </div>
                            <!-- FIN DEL CAMPO TIPO MEMO -->';
                                $str_Funciones_Js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){});';
                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                            break;

                            case '3':
                                $campo = ' 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <input type="text" class="form-control input-sm Numerico '.$classTelefono.'" value="'.$valoraMostrar.'" '.$disableds.' name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" placeholder="'.($obj->titulo_pregunta.$placeholderTelefono).'" '.$required.' '.$validarPatron.'>
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->';

                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                                $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
                    $cur_result = $mysqli->query($LsqlCadena);
                    $itsCadena = false;
                    while ($key = $cur_result->fetch_object()) {    
                        /* ahora toca buscar el valor de esos campos en la jugada */
                        $dato = str_replace(' ', '_', ($obj->titulo_pregunta));

                        $buscar = '${'.substr(sanear_strings($dato), 0, 20).'}';

                    
                        //$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
                        
                        
                        if (stristr(trim($key->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
                            $itsCadena = true;
                            //echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
                            /* Toca hacer todo el frito */
                            $str_Funciones_Js .= "\n".'
        //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){';

                            $LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
                            $cadenaFinalX = trim($key->PREGUN_OperEntreCamp_____b);

                            $cur_resultX = $mysqli->query($LsqlCadenaX);

                            while ($keyX = $cur_resultX->fetch_object()) {

                                /* ahora toca buscar el valor de esos campos en la jugada */
                                $datoX = str_replace(' ', '_', ($keyX->PREGUN_Texto_____b));

                                $buscarX = '${'.substr(sanear_strings($datoX), 0, 20).'}';

                                $reemplazo = 'Number($("#'.$str_Guion_C.$keyX->PREGUN_ConsInte__b.'").val())';

                                $cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
                            }

                            $str_Funciones_Js .= "\n".'

        $("#'.$str_Guion_C.$key->PREGUN_ConsInte__b.'").val('.$cadenaFinalX.');';

                            $str_Funciones_Js .= "\n".'
    });';
                        }
                    }

                    if($itsCadena == false){
                        /* No esta metido en ningun lado */
                        $str_Funciones_Js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){});';

                    }
                            break;

                            case '4':
                                $campo = '  
                            <!-- CAMPO TIPO DECIMAL -->
                            <!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <input type="text" class="form-control input-sm Decimal" value="'.$valoraMostrar.'" '.$disableds.' name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" placeholder="'.($obj->titulo_pregunta).'" '.$required.'>
                            </div>
                            <!-- FIN DEL CAMPO TIPO DECIMAL -->';
                                
                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                                $LsqlCadena = "SELECT PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4) AND PREGUN_OperEntreCamp_____b IS NOT NULL "; 
                    $cur_result = $mysqli->query($LsqlCadena);
                    $itsCadena = false;
                    while ($key = $cur_result->fetch_object()) {    
                        /* ahora toca buscar el valor de esos campos en la jugada */
                        $dato = str_replace(' ', '_', ($obj->titulo_pregunta));

                        $buscar = '${'.substr(sanear_strings($dato), 0, 20).'}';

                    
                        //$pos = strpos($key->PREGUN_OperEntreCamp_____b, $buscar);
                        
                        
                        if (stristr(trim($key->PREGUN_OperEntreCamp_____b), $buscar) !== false) {
                            $itsCadena = true;
                            //echo "Es esto => ".$buscar." Aqui => ".$key->PREGUN_OperEntreCamp_____b;
                            /* Toca hacer todo el frito */
                            $str_Funciones_Js .= "\n".'
        //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){';

                            $LsqlCadenaX = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_OperEntreCamp_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar."  AND (PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4)"; 
                            $cadenaFinalX = trim($key->PREGUN_OperEntreCamp_____b);

                            $cur_resultX = $mysqli->query($LsqlCadenaX);

                            while ($keyX = $cur_resultX->fetch_object()) {

                                /* ahora toca buscar el valor de esos campos en la jugada */
                                $datoX = str_replace(' ', '_', ($keyX->PREGUN_Texto_____b));

                                $buscarX = '${'.substr(sanear_strings($datoX), 0, 20).'}';

                                $reemplazo = 'Number($("#'.$str_Guion_C.$keyX->PREGUN_ConsInte__b.'").val())';

                                $cadenaFinalX = str_replace($buscarX, $reemplazo , $cadenaFinalX);
                            }

                            $str_Funciones_Js .= "\n".'

        $("#'.$str_Guion_C.$key->PREGUN_ConsInte__b.'").val('.$cadenaFinalX.');';

                            $str_Funciones_Js .= "\n".'
    });';
                        }
                    }

                    if($itsCadena == false){
                        /* No esta metido en ningun lado */
                        $str_Funciones_Js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){});';

                    }
                            break;

                            case '5':
                                $campo = '  
                            <!-- CAMPO TIPO FECHA -->
                            <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <input type="text" class="form-control input-sm Fecha" value="'.$valoraMostrar.'" '.$disableds.' name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" placeholder="YYYY-MM-DD" '.$required.'>
                            </div>
                            <!-- FIN DEL CAMPO TIPO FECHA-->';
                                $str_Funciones_Js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){});';
                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                            break;
                
                            case '10':
                                $campo = '  
                            <!-- CAMPO TIMEPICKER -->
                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm Hora" '.$disableds.' name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" placeholder="HH:MM:SS" '.$required.'>
                                    <div class="input-group-addon" id="TMP_'.$str_Guion_C.$obj->id.'">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->';
                                $str_Funciones_Js .= "\n".'
    //function para '.($obj->titulo_pregunta).' '."\n".'
    $("#'.$str_Guion_C.$obj->id.'").on(\'blur\',function(e){});';
                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                            break;

                           case '6':

$campo = '
                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" '.$required.'>
                            <option value="">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = '.$obj->lista.' ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->';

$GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '".$obj->id."' GROUP BY PRSADE_ConsInte__OPCION_b";
$result = $mysqli->query($GuionsSql);
$saltos = '';
if($result->num_rows > 0){
    $sinsalto = '';
    $tamanho = 0;
    $SqlLosquenoEstan = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND PREGUN_ContAcce__b IS NULL;";
   // echo $SqlLosquenoEstan;
    $ResultLosqueNoestan = $mysqli->query($SqlLosquenoEstan);
    while ($newObjrQueNoestan = $ResultLosqueNoestan->fetch_object()) {
        $saltos .= '
            $("#'.$str_Guion_C.$newObjrQueNoestan->PREGUN_ConsInte__b.'").prop(\'disabled\', false);
        ';
    }
    while($objr = $result->fetch_object()){
        /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
        $saltos .= '
            if($(this).val() == \''.$objr->PRSADE_ConsInte__OPCION_b.'\'){';
        $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b = ".$objr->PRSADE_ConsInte__OPCION_b.";";
        $newResult = $mysqli->query($newSql);
        while ($newObjr = $newResult->fetch_object()) {
          $saltos .= '
            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
          ';
        }
        $saltos .= '
            }
        ';
    }
}






//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
$validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = ".$obj->id;
$resEsPadre = $mysqli->query($validarSiEsPadre);
$hijosdeEsteGuion = "\n";
if($resEsPadre->num_rows > 0){
    while ($keyPadre = $resEsPadre->fetch_object()) {
        $hijosdeEsteGuion .='
        $.ajax({
            url    : \'<?php echo $url_crud; ?>\',
            type   : \'post\',
            data   : { getListaHija : true ,  opcionID : \''.$keyPadre->PREGUN_ConsInte__OPCION_B.'\' , idPadre : $(this).val() },
            success : function(data){
                $("#'.$str_Guion_C.$keyPadre->PREGUN_ConsInte__b.'").html(data);
            }
        });
        ';
    }   
}

                    $str_Funciones_Jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$str_Guion_C.$obj->id.'").change(function(){ '.$saltos.' 
        //Esto es la parte de las listas dependientes
        '.$hijosdeEsteGuion.'
    });';


                    
                            fputs($fp , $campo);
                            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                        break;


                        case '13':


$campo = '
                    <!-- CAMPO DE TIPO LISTA / Respuesta -->
                    <div class="form-group">
                        <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" '.$required.'>
                            <option value="">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, LISOPC_Respuesta_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = '.$obj->lista.' ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\' respuesta = \'".$obje->LISOPC_Respuesta_b."\'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="respuesta_'.$str_Guion_C.$obj->id.'" id="respuesta_Lbl'.$str_Guion_C.$obj->id.'">Respuesta</label>
                        <textarea id="respuesta_'.$str_Guion_C.$obj->id.'" class="form-control" placeholder="Respuesta"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA  / Respuesta -->';

$GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '".$obj->id."' GROUP BY PRSADE_ConsInte__OPCION_b";
$result = $mysqli->query($GuionsSql);
$saltos = '';
if($result->num_rows > 0){
    $sinsalto = '';
    $tamanho = 0;
    $SqlLosquenoEstan = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND PREGUN_ContAcce__b IS NULL;";
   // echo $SqlLosquenoEstan;
    $ResultLosqueNoestan = $mysqli->query($SqlLosquenoEstan);
    while ($newObjrQueNoestan = $ResultLosqueNoestan->fetch_object()) {
        $saltos .= '
            $("#'.$str_Guion_C.$newObjrQueNoestan->PREGUN_ConsInte__b.'").prop(\'disabled\', false);
        ';
    }
    while($objr = $result->fetch_object()){
        /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
        $saltos .= '
            if($(this).val() == \''.$objr->PRSADE_ConsInte__OPCION_b.'\'){';
        $newSql = "SELECT * FROM ".$BaseDatos_systema.".PRSASA JOIN ".$BaseDatos_systema.".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = ".$obj->id." AND PRSADE_ConsInte__OPCION_b = ".$objr->PRSADE_ConsInte__OPCION_b.";";
        $newResult = $mysqli->query($newSql);
        while ($newObjr = $newResult->fetch_object()) {
          $saltos .= '
            $("#'.$newObjr->PRSASA_NombCont__b.'").prop(\'disabled\', true); 
          ';
        }
        $saltos .= '
            }
        ';
    }
}


//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
$validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = ".$obj->id;
$resEsPadre = $mysqli->query($validarSiEsPadre);
$hijosdeEsteGuion = "\n";
if($resEsPadre->num_rows > 0){
    while ($keyPadre = $resEsPadre->fetch_object()) {
        $hijosdeEsteGuion .='
        $.ajax({
            url    : \'<?php echo $url_crud;?>\',
            type   : \'post\',
            data   : { getListaHija : true , opcionID : \''.$keyPadre->PREGUN_ConsInte__OPCION_B.'\' , idPadre : $(this).val() },
            success : function(data){
                $("#'.$str_Guion_C.$keyPadre->PREGUN_ConsInte__b.'").html(data);
            }
        });
        ';
    }   
}

                    $str_Funciones_Jsx .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$str_Guion_C.$obj->id.'").change(function(){ '.$saltos.' 
        //Esto es la parte de las listas dependientes
        '.$hijosdeEsteGuion.'
    });';


                        
                            fputs($fp , $campo);
                            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                        break;

                            case '11':

                                //Primero necesitamos obener el campo que vamos a usar
                                $campoGuion = $obj->id;
                                $str_Guionstr_Select2 = $obj->guion;
                                //Luego buscamos los campos en la tabla Pregui
                                $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$campoGuion;
                                //recorremos esos campos para ir a buscarlos en la tabla campo_
                                $CampoSqlR = $mysqli->query($CampoSql);
                                $str_CamposConsultaGuion = ' G'.$obj->guion.'_ConsInte__b as id ';


                                $str_Campos_A_Mostrar = '';
                                $int_Valor_Del_Array = 0;
                                $str_Nombres_De_Campos = '';
                                $camposAcolocarDinamicamente = '0';

                                while($objet = $CampoSqlR->fetch_object()){
                                    //aqui obtenemos los campos que se colocara el valor dinamicamente al seleccionar una opcion del guion, ejemplo ciudad - departamento- pais..
                                    if($objet->PREGUI_Consinte__CAMPO__GUI_B != 0){
                                        //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                                        $campoamostrarSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$objet->PREGUI_Consinte__CAMPO__GUI_B;
                                        $campoamostrarSqlE = $mysqli->query($campoamostrarSql);
                                        while($campoNombres = $campoamostrarSqlE->fetch_object()){
                                            $camposAcolocarDinamicamente .= '|'.$campoNombres->CAMPO__Nombre____b;
                                        }
                                    }

                                    //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                                    $campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$objet->PREGUI_ConsInte__CAMPO__b;
                                    //echo $campoObtenidoSql;
                                    $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);
                                    while ($objCampo = $resultCamposObtenidos->fetch_object()) {

                                        //Busco el nombre del campo para el nombre de las columnas
                                        $selectGuion = "SELECT PREGUN_Texto_____b as titulo_pregunta FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b =".$objCampo->CAMPO__ConsInte__PREGUN_b;
                                        $selectGuionE = $mysqli->query($selectGuion);
                                        while($o = $selectGuionE->fetch_object()){
                                            if($int_Valor_Del_Array == 0){
                                                $str_Nombres_De_Campos .= ($o->titulo_pregunta);
                                            }else{
                                                $str_Nombres_De_Campos .= ' | '.($o->titulo_pregunta).'';
                                            }
                                        }
                                        //añadimos los campos a la consulta que se necesita para cargar el guion
                                        $str_CamposConsultaGuion .=', '.$objCampo->CAMPO__Nombre____b;
                                        if($int_Valor_Del_Array == 0){
                                            $str_Campos_A_Mostrar .= '".($obj->'.$objCampo->CAMPO__Nombre____b.')."';
                                        }else{
                                            $str_Campos_A_Mostrar .= ' | ".($obj->'.$objCampo->CAMPO__Nombre____b.')."';
                                        }
                                        
                                        $int_Valor_Del_Array++;
                                    }
                                }
                                $datosaEnviar = "";

                                $campo = ' 
                            <?php 
                            $str_Lsql = "SELECT '.$str_CamposConsultaGuion.' FROM '.$BaseDatos.'.G'.$obj->guion.'";
                            ?>
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" '.$required.'>
                                    <option>'.$str_Nombres_De_Campos.'</option>
                                    <?php
                                        /*
                                            SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                        */
                                        $combo = $mysqli->query($str_Lsql);
                                        while($obj = $combo->fetch_object()){
                                            echo "<option value=\'".$obj->id."\' dinammicos=\''.$camposAcolocarDinamicamente.'\'>'.$str_Campos_A_Mostrar.'</option>";

                                        }    
                                        
                                    ?>
                                </select>
                            </div>
                            <!-- FIN DEL CAMPO TIPO LISTA -->';

               

                                $str_FuncionesCampoGuion .= "\n".'
        if(isset($_GET[\'CallDatosCombo_Guion_'.$str_Guion_C.$obj->id.'\'])){
            $Ysql = \'SELECT  '.$str_CamposConsultaGuion.' FROM ".$BaseDatos_systema.".G'.$obj->guion.'\';
            $guion = $mysqli->query($Ysql);
            echo \'<select class="form-control input-sm"  name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'">\';
            echo \'<option >'.$str_Nombres_De_Campos.'</option>\';
            while($obj = $guion->fetch_object()){
               echo "<option value=\'".$obj->id."\' dinammicos=\''.$camposAcolocarDinamicamente.'\'>'.$str_Campos_A_Mostrar.'</option>";
            } 
            echo \'</select>\';
        }';

                                if($int_Valor_Del_Array > 0){
                                    $totalRows = 12 / $int_Valor_Del_Array;
                                }else{
                                    $totalRows = 12;
                                }
                        
                                $rows = '';
                                for($i= 0; $i < $int_Valor_Del_Array; $i++){
                                    $rows .= ' 
                                \'<div class="col-md-'.number_format($totalRows).'">\' + r['.$i.'] + \'</div>\' +';
                                }



                                $str_Select2 .= "\n".'
        $("#'.$str_Guion_C.$obj->id.'").select2({ 
            templateResult: function(data) {
                var r = data.text.split(\'|\');
                var $result = $(
                    \'<div class="row">\' +
                        '.$rows .'
                    \'</div>\'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split(\'|\');
                return r[0];
            }
        });'."\n".'
        $("#'.$str_Guion_C.$obj->id.'").change(function(){
            var valores = $("#'.$str_Guion_C.$obj->id.' option:selected").text();
            var campos = $("#'.$str_Guion_C.$obj->id.' option:selected").attr("dinammicos");
            var r = valores.split(\'|\');
            if(r.length > 1){

                var c = campos.split(\'|\');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(\' \', \'\'); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });';
                                $str_Select2_hojadeDatos .= "\n".'
                    $("#"+ rowid +"_'.$str_Guion_C.$obj->id.'").change(function(){
                        var valores = $("#"+ rowid +"_'.$str_Guion_C.$obj->id.' option:selected").text();
                        var campos = $("#"+ rowid +"_'.$str_Guion_C.$obj->id.' option:selected").attr("dinammicos");
                        var r = valores.split(\'|\');

                        if(r.length > 1){

                            var c = campos.split(\'|\');
                            for(i = 1; i < r.length; i++){
                                if(!$("#"+c[i]).is("select")) {
                                // the input field is not a select
                                    $("#"+ rowid +"_"+c[i]).val(r[i]);
                                }else{
                                    var change = r[i].replace(\' \', \'\'); 
                                    $("#"+ rowid +"_"+c[i]).val(change).change();
                                }
                                
                            }
                        }
                    });';



                                $revisarAjax = '';
                                $revisarstr_Lsql = 'SELECT PREGUN_ConsInte__b, PREGUN_DepGuion_b, PREGUN_DepColGui_b, PREGUN_Agrupar_b FROM '.$BaseDatos_systema.'.PREGUN WHERE PREGUN_DepPadre_b = '.$obj->id;

                                $revisarResultado = $mysqli->query($revisarstr_Lsql);
                                $PREGUN_Dependiente = null;
                                $PREGUN_DepGuion_b = null;
                                $PREGUN_DepColGui_b = null;
                                $PREGUN_Agrupar_b = null;
                                while ($kj = $revisarResultado->fetch_object()) {
                                    $PREGUN_Dependiente = $kj->PREGUN_ConsInte__b;
                                }

                                //si de verdad el man es padre
                                if(!is_null($PREGUN_Dependiente)){
                                    $revisarAjax .= '
        $.ajax({
            url     : \'formularios/'.$str_guion.'/'.$str_guion.'_CRUD.php?mostrarPadre_'.$PREGUN_Dependiente.'=si\',
            type    : \'post\',
            data    : { padre : $(this).val() },
            success : function(data){
                $("#'.$str_guion.'_C'.$PREGUN_Dependiente.'").html(data);
            } 
        });
    ';
                                }

                                $revisarstr_Lsql2 = 'SELECT PREGUN_DepPadre_b , PREGUN_DepGuion_b, PREGUN_DepColGui_b, PREGUN_Agrupar_b FROM '.$BaseDatos_systema.'.PREGUN WHERE PREGUN_ConsInte__b = '.$obj->id;
                
                                $revisarResultado2 = $mysqli->query($revisarstr_Lsql2);
                                $PREGUN_DepGuion_b = null;
                                $PREGUN_DepColGui_b = null;
                                $PREGUN_Agrupar_b = null;
                                while ($kj = $revisarResultado2->fetch_object()) {
                                    $PREGUN_DepGuion_b = $kj->PREGUN_DepGuion_b;
                                    $PREGUN_DepColGui_b = $kj->PREGUN_DepColGui_b;
                                    $PREGUN_Agrupar_b = $kj->PREGUN_Agrupar_b;
                                }

                                if(!is_null($PREGUN_DepGuion_b) && !is_null($PREGUN_DepColGui_b) && !is_null($PREGUN_Agrupar_b)){
                                    $str_Funciones_Carga_Padres_Maestros .= "\n".'
        if(isset($_GET[\'mostrarPadre_'.$obj->id.'\'])){
            $padre = $_POST[\'padre\'];
            if(!is_null($padre) && $padre != \'\'){
                $str_Lsql  = "SELECT '.$str_CamposConsultaGuion.' FROM ".$BaseDatos_systema.".G'.$obj->guion.' WHERE G'.$PREGUN_DepGuion_b.'_C'.$PREGUN_DepColGui_b.' = ".$padre;
                $res = $mysqli->query($str_Lsql); 
                while($obj = $res->fetch_object()){
                     echo "<option value=\'".$obj->id."\' dinammicos=\''.$camposAcolocarDinamicamente.'\'>'.$str_Campos_A_Mostrar.'</option>";
                }
            }
        }
                        ';


                                }


                                $str_Funciones_Js .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$str_Guion_C.$obj->id.'").change(function(){
        '.$revisarAjax.'
    });';
                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea */
                            break;

                            case '8':
                                $campo = '  
                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                            <div class="form-group">
                                <label for="'.$str_Guion_C.$obj->id.'" id="Lbl'.$str_Guion_C.$obj->id.'">'.($obj->titulo_pregunta).$asterix.'</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" name="'.$str_Guion_C.$obj->id.'" id="'.$str_Guion_C.$obj->id.'" data-error="Before you wreck yourself" '.$required.' > 
                                    </label>
                                </div>
                            </div>
                            <!-- FIN DEL CAMPO SI/NO -->';
                                $str_Funciones_Js .="\n".'
    //function para '.($obj->titulo_pregunta).' '. "\n".'
    $("#'.$str_Guion_C.$obj->id.'").change(function(){
        if($(this).is(":checked")){

        }else{

        }
    });';
                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
                            break;
            
            

                            case '9':
                                $campo = '  
                            <!-- lIBRETO O LABEL -->
                            <h3>'.($obj->titulo_pregunta).$asterix.'</h3>
                            <!-- FIN LIBRETO -->';
                                fputs($fp , $campo);
                                fputs($fp , chr(13).chr(10)); // Genera saldo de linea 

                            break;
                            default:
                  
                            break;
                        }//Cierro el Swich


                    }//cierro el IF


                }//Cierro el Wile de campos

            //}//Este es el While de secciones

            $index = '
                            '.$iframe.'
                            <!-- SECCION : PAGINAS INCLUIDAS -->
                            <input type="hidden" name="pasoId" id="pasoId" value="<?php if (isset($_GET["paso"])) {
                                echo $_GET["paso"];
                            }else{ echo "0"; } ?>">
                            <input type="hidden" name="id" id="hidId" value=\'0\'>
                            <input type="hidden" name="oper" id="oper" value=\'add\'>
                            <input type="hidden" name="padre" id="padre" value=\'<?php if(isset($_GET[\'yourfather\'])){ echo $_GET[\'yourfather\']; }else{ echo "0"; }?>\' >
                            <input type="hidden" name="formpadre" id="formpadre" value=\'<?php if(isset($_GET[\'formularioPadre\'])){ echo $_GET[\'formularioPadre\']; }else{ echo "0"; }?>\' >
                            <input type="hidden" name="formhijo" id="formhijo" value=\'<?php if(isset($_GET[\'formulario\'])){ echo $_GET[\'formulario\']; }else{ echo "0"; }?>\' >
                            <input type="hidden" name="ORIGEN_DY_WF" id="ORIGEN_DY_WF" value=\'<?php if(isset($_GET[\'origen\'])){ echo $_GET[\'origen\']; }else{ echo "webForm"; }?>\' >
                            
                            <?php if (isset($_GET[\'v\'])){ ?>
                                    '.$optin.'
                            <?php }else{ ?>
                                    <input type= "hidden" name="OPTIN_DY_WF" id="OPTIN_DY_WF" value="SIMPLE">
                            <?php }?>

                                    '.$btnSubmit.'
                        </form>
                    </div><!-- /.login-box-body -->
                    <?php 
                        }else{ ?>
                            <?php if(isset($_GET[\'v\'])){ 
                                '.$codigoForm.'
                            }else { ?>
                            <div class="login-box-body">
                                <p class="login-box-msg font_2" >FORMULARIO DE CONTACTO</p>
                            <?php } ?>
                    <?php 
                            if($_GET[\'aceptaTerminos\'] == \'acepto\'){
                                $ultimoResgistroInsertado = base64_decode($_GET[\'cons\']);

                                $OPTIN_DY_WF = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = '.$int_Id_Generar.' AND PREGUN_Texto_____b = \'OPTIN_DY_WF\'";
                                $res_OPTIN_DY_WF = $mysqli->query($OPTIN_DY_WF);
                                if($res_OPTIN_DY_WF->num_rows > 0){


                                    $Sel = "SELECT * FROM ".$BaseDatos.".G'.$int_Id_Generar.' WHERE '.$str_Guion_C.'".$dataOPTIN_DY_WF[\'PREGUN_ConsInte__b\']." IS NULL AND G871_ConsInte__b =".$ultimoResgistroInsertado;
                                    $resSel = $mysqli->query($Sel);
                                    if($resSel->num_rows > 0){

                                        $dataOPTIN_DY_WF = $res_OPTIN_DY_WF->fetch_array();
                                        $Lsql = "UPDATE ".$BaseDatos.".G'.$int_Id_Generar.' SET '.$str_Guion_C.'".$dataOPTIN_DY_WF[\'PREGUN_ConsInte__b\']." = \'CONFIRMADO\' WHERE G'.$int_Id_Generar.'_ConsInte__b =".$ultimoResgistroInsertado;
                                        if ($mysqli->query($Lsql) === TRUE) {
                                            /* Acepto toca meterlo en la muestra  G626_M285*/

                                            '.$patacon.'

                                            echo "<div class=\"alert alert-info\">Gracias por aceptar nuestra <a href=\'#\'>Politica de tratamiento de datos</a>, nos pondremos en contacto pronto!</div>";

                                        }
                                    }else{
                                        echo "<div class=\"alert alert-info\">Gracias por aceptar nuestra <a href=\'#\'>Politica de tratamiento de datos</a>, nos pondremos en contacto pronto!</div>";   
                                    }


                                }

                            }else{
                                /* NO CONFIRMAN */
                                $ultimoResgistroInsertado = base64_decode($_GET[\'cons\']);
                                $OPTIN_DY_WF = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = '.$int_Id_Generar.' AND PREGUN_Texto_____b = \'OPTIN_DY_WF\'";
                                $res_OPTIN_DY_WF = $mysqli->query($OPTIN_DY_WF);
                                if($res_OPTIN_DY_WF->num_rows > 0){
                                    $dataOPTIN_DY_WF = $res_OPTIN_DY_WF->fetch_array();
                                    $Sel = "SELECT * FROM ".$BaseDatos.".G'.$int_Id_Generar.' WHERE '.$str_Guion_C.'".$dataOPTIN_DY_WF[\'PREGUN_ConsInte__b\']." IS NULL AND G871_ConsInte__b =".$ultimoResgistroInsertado;
                                    $resSel = $mysqli->query($Sel);
                                    if($resSel->num_rows > 0){

                                        $Lsql = "UPDATE ".$BaseDatos.".G'.$int_Id_Generar.' SET '.$str_Guion_C.'".$dataOPTIN_DY_WF[\'PREGUN_ConsInte__b\']." = \'NO CONFIRMADO\' WHERE G'.$int_Id_Generar.'_ConsInte__b =".$ultimoResgistroInsertado;

                                        if ($mysqli->query($Lsql) === TRUE) {
                                            echo "<div class=\"alert alert-info\">Hemos eliminado su información de la base de datos.</div>";
                                        }
                                    }else{
                                        echo "<div class=\"alert alert-info\">Hemos eliminado su información de la base de datos.</div>";            
                                    }

                                }
                            }

                                
                        } ?>


                </div><!-- /.login-box -->

            </div>
        </div>


        <!-- jQuery 2.2.3 -->
        <script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/plugins/select2/select2.full.min.js"></script>
        <!-- datepicker -->
        <script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- FastClick -->
        <script src="assets/plugins/fastclick/fastclick.js"></script>
        <script src="assets/timepicker/jquery.timepicker.js"></script>
        <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>  
        <script src="assets/plugins/iCheck/icheck.min.js"></script>
        <script src="assets/js/jquery.validate.js"></script>
        <script src="assets/js/numeric.js"></script>

        <script type="text/javascript" src="formularios/'.$str_guion.'/'.$str_guion.'_eventos.js"></script>
        ';

        $strSCRIPT_t = '';

        if (!is_null($WEBFORM_tipo_redireccion)) {

            if (!is_null($WEBFORM_codigo_propiedad_b) && strlen($WEBFORM_codigo_propiedad_b) > 5) {

                if ($WEBFORM_tipo_redireccion == 1) {

            $index .= '
            <script async src="https://www.googletagmanager.com/gtag/js?id='.$WEBFORM_codigo_propiedad_b.'"></script>';

            $strSCRIPT_t = '
                            window.dataLayer = window.dataLayer || [];
                            function gtag(){dataLayer.push(arguments);}
                            gtag("js", new Date());

                            gtag("config", "'.$WEBFORM_codigo_propiedad_b.'");';

                }

            }


        }

        if ($WEBFORM_id_redireccion_b == -1) {

            if($WEBFORM_tipo_redireccion == 0){

                if ($WEBFORM_url_analytics_b != "" || !is_null($WEBFORM_url_analytics_b)) {

        $strSCRIPT_t .= '
                        window.location.href = "'.$WEBFORM_url_analytics_b.'";';

                    

                }
                
            }

        }else{

        $strSCRIPT_t .= '
                        window.location.href = "'.$WEBFORM_url_redireccion_b.'";';


        }


    $index .= '
        <script type="text/javascript">

                '.$script.'
                $("#formLogin").submit(function(event) {
                // $(\'.error-pattern\').remove();

                // let patrones = <?php if($patron != \'\'){echo $patron;}else{echo "0";} ?>;

                // if(patrones != "0"){

                //     let expreg = /patrones/;
                //     let valido = true;
                    
                //     $(\'.telefono\').each(function(){
                //         if(!expreg.test($(this).val())){
                //             $(this).after(\'<span class="error-pattern" style="color: red;">Por favor envia un formato valido <?php echo $patronSimple ?></span>\');
                //             valido = false;
                //         }
                //     });
                    
                //     if(valido){ return; }
                    
                //     event.preventDefault();
                // }

                '.$enviocaptcha.'
            });
            $(function(){';
            if ($strConfirmamosQueElGuionTieneAdjuntos_t > 0) {
$index .= '
                $(".adjuntos").change(function(){
                    var imax = $(this).attr("valor");
                    var imagen = this.files[0];

                    // if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png" && imagen["type"] != "application/pdf"){
                    //     swal({
                    //         title : "Error al subir el archivo",
                    //         text  : "El archivo debe estar en formato PNG , JPG, PDF",
                    //         type  : "error",
                    //         confirmButtonText : "Cerrar"
                    //     });
                    // }else 
                    if(imagen["size"] > 9000000 ) {
                        $(this).val("");
                        swal({
                            title : "Error al subir el archivo",
                            text  : "El archivo no debe pesar mas de 9 MB",
                            type  : "error",
                            confirmButtonText : "Cerrar"
                        });
                    }
                });';            
            }
$index .= '
                $.fn.datepicker.dates[\'es\'] = {
                    days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
                    daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    today: "Today",
                    clear: "Clear",
                    format: "yyyy-mm-dd",
                    titleFormat: "yyyy-mm-dd", 
                    weekStart: 0
                };

                //str_Select2 estos son los guiones
                '."\n".$str_Select2.'

                //datepickers
                '.$str_FechaValidadaOno.'

                //Timepickers
                '."\n".$str_HoraValidadaOno.'

                //Validaciones numeros Enteros
                '."\n".$str_NumeroFuncion.'

                //Validaciones numeros Decimales
               '."\n".$str_DecimalFuncion.'

               /* Si tiene dependiendias */
               '."\n".$str_Funciones_Jsx.'


               <?php
                    if(isset($_GET[\'result\'])){
                        if($_GET[\'result\'] ==  1){
                ?>';

                $index .= $strSCRIPT_t;

             $index .= '

                        swal({
                            title: "Exito!",
                            text: "Recibimos su solicitud, pronto estaremos en contacto",
                            type: "success",
                            confirmButtonText: "Ok"
                        },function(){
                            <?php 
                            if(isset($_GET[\'v\'])) {
                                '.$url_gracias.'
                            } 
                            ?>
                        });
                        
                        
                <?php   }else{ ?>
                            swal({
                                title: "Error!",
                                text: \'Ocurrio un error, intenta mas tarde\',
                                type: "error",
                                confirmButtonText: "Ok"
                            });
                <?php      
                        }
                    }
                ?>
            });
        </script>

        <?php 
        if(isset($_GET[\'v\'])) {
            '.$scriptInsertar.'
        } 
        ?>
        ';

            fputs($fp , $index);
            fputs($fp , chr(13).chr(10)); // Genera saldo de linea 
            fclose($fp);

            $nombre_fichero = $carpeta."/".$str_guion."_eventos.js";
            if (!file_exists($nombre_fichero)) {
                $fjs = fopen($carpeta."/".$str_guion."_eventos.js", "w");
                $nuewJs = '$(function(){'.$str_Funciones_Js.' '."\n".'});';
                $nuewJs .= "\n".'
    function before_save(){ return true; }'."\n".'
    function after_save(){}'."\n".'
    function after_save_error(){}';
                $nuewJs .= "\n".'
    function before_edit(){}'."\n".'
    function after_edit(){}';
                $nuewJs .= "\n".'
    function before_add(){}'."\n".'
    function after_add(){}';
                $nuewJs .= "\n".'
    function before_delete(){}'."\n".'
    function after_delete(){}'."\n".'
    function after_delete_error(){}';
                fputs($fjs, $nuewJs);
                fclose($fjs);
            }

            $nombre_fichero2 = $carpeta."/".$str_guion."_extender_funcionalidad.php";
            if (!file_exists($nombre_fichero2)) {
                $fjss = fopen($carpeta."/".$str_guion."_extender_funcionalidad.php", "w");
                $nuewJss = '<?php';
                $nuewJss .= "\n".'
        include(__DIR__."/../../conexion.php");';
                $nuewJss .= "\n".'
        //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan';
                $nuewJss .= "\n".'
        //Cosas como nuevas consultas, nuevos Inserts, Envio de correos_, etc, en fin extender mas los formularios en PHP';
                $nuewJss .= "\n".'?>';
                fputs($fjss, $nuewJss);
                fclose($fjss);
            }

            //Este es el crud
            $fcrud = fopen($carpeta."/".$str_guion."_CRUD_WEB".$webFormId.".php" , "w");

            //Esta consulta la hago para los campos del select
            $campos_4 = $mysqli->query($str_Lsql);
            $camposconsulta12 = '';
            $camposconsulta1 = '
                    $str_Lsql = \'SELECT '.$str_guion.'_ConsInte__b, '.$str_Guion_C.$GUION__ConsInte__PREGUN_Pri_b.' as principal ';
            $camposconsulta12 = $camposconsulta1;
            $str_Joins = '';
                $alfa = 0;
            $camposGrid = '';
            $camposExcell = '';
            $horas = 0;
            while($key = $campos_4->fetch_object()){

                if($key->tipo_Pregunta != 9){
                    $camposconsulta1 .= ','.$str_Guion_C.$key->id;
        
                    if($key->tipo_Pregunta == '5'){
                        $camposGrid .= ', explode(\' \', $fila->'.$str_Guion_C.$key->id.')[0] ';
                        $camposExcell .='                           <td>\'.explode(\' \', $fila->'.$str_Guion_C.$key->id.')[0].\'</td>'."\n";
                    }else if($key->tipo_Pregunta == '10'){
                        $camposGrid .= ', $hora_'.$str_Alfabeto[$horas].' ';
                        $camposExcell .= '                          <td>\'.$hora_'.$str_Alfabeto[$horas].'.\'</td>'."\n";
                        $horas++;
                    }else if($key->tipo_Pregunta =='11'){
                        $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                        $campoSqlE = $mysqli->query($CampoSql);

                        while ($cam = $campoSqlE->fetch_object()) {
                            //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                            $campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
                            //echo $campoObtenidoSql;
                            $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                            while ($o = $resultCamposObtenidos->fetch_object()) {
                                $camposGrid .= ', ($fila->'.$o->CAMPO__Nombre____b.') ';
                                $camposExcell .= '                          <td>\'.($fila->'.$o->CAMPO__Nombre____b.').\'</td>'."\n";
                            }
                        }

                    }else{
                        $camposGrid .= ', ($fila->'.$str_Guion_C.$key->id.') ';
                        $camposExcell .= '                          <td>\'.($fila->'.$str_Guion_C.$key->id.').\'</td>'."\n";
                    }

                    if($key->tipo_Pregunta == '6'){
                      $camposconsulta12 .= ', '.$str_Alfabeto[$alfa].'.LISOPC_Nombre____b as '.$str_Guion_C.$key->id;
                      $str_Joins .= ' LEFT JOIN \'.$BaseDatos_systema.\'.LISOPC as '.$str_Alfabeto[$alfa].' ON '.$str_Alfabeto[$alfa].'.LISOPC_ConsInte__b =  '.$str_Guion_C.$key->id;
                      $alfa++;
                    }else if($key->tipo_Pregunta =='11'){
                        $CampoSql = "SELECT * FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$key->id." ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                        $campoSqlE = $mysqli->query($CampoSql);

                        while ($cam = $campoSqlE->fetch_object()) {
                            //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                            $campoObtenidoSql = 'SELECT * FROM '.$BaseDatos_systema.'.CAMPO_ WHERE CAMPO__ConsInte__b = '.$cam->PREGUI_ConsInte__CAMPO__b;
                            //echo $campoObtenidoSql;
                            $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                            while ($o = $resultCamposObtenidos->fetch_object()) {
                                $camposconsulta12 .= ', '.$o->CAMPO__Nombre____b;
                            }
                        }
                        
                        $str_Joins .= ' LEFT JOIN \'.$BaseDatos_systema.\'.G'.$key->guion.' ON G'.$key->guion.'_ConsInte__b  =  '.$str_Guion_C.$key->id;
                    }else{
                        $camposconsulta12 .= ','.$str_Guion_C.$key->id;
                    }
                }
            }

            $camposconsulta1 .= ' FROM \'.$BaseDatos.\'.'.$str_guion;
            $camposconsulta12 .= ' FROM \'.$BaseDatos.\'.'.$str_guion;

            $camposconsulta1 .= ' WHERE '.$str_guion.'_ConsInte__b =\'.$_POST[\'id\'];';

            $str_LsqlHora = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Id_Generar." AND PREGUN_Tipo______b = 10 ORDER BY PREGUN_OrdePreg__b";
            $esHora = $mysqli->query($str_LsqlHora);
            $variablesDeLahora = '';
            $horas = 0;
            while ($key = $esHora->fetch_object()) {
                $variablesDeLahora .= "\n".'
                $hora_'.$str_Alfabeto[$horas].' = \'\';
                //esto es para todo los tipo fecha, para que no muestre la parte de la hora
                if(!is_null($fila->'.$str_Guion_C.$key->id.')){
                    $hora_'.$str_Alfabeto[$horas].' = explode(\' \', $fila->'.$str_Guion_C.$key->id.')[1];
                }';
                $horas++;
            }

            $crud = '<script>
    document.cookie = "same-site-cookie=foo; SameSite=Lax"; 
    document.cookie = "cross-site-cookie=bar; SameSite=None; Secure";
</script>
            
<?php
    session_start();
    ini_set(\'display_errors\', \'On\');
    ini_set(\'display_errors\', 1);
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");
    date_default_timezone_set(\'America/Bogota\');
    define("RECAPTCHA_V3_SECRET_KEY", "6Lcc1dYUAAAAAHgqTohTDsl2g-0V5-egYLC4atVb");
    
    if(isset($_POST[\'getListaHija\'])){

        $Lsql = "SELECT LISOPC_ConsInte__b , LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__LISOPC_Depende_b = ".$_POST[\'idPadre\']." AND LISOPC_ConsInte__OPCION_b = ".$_POST[\'opcionID\'];
        //echo $Lsql;
        $res = $mysqli->query($Lsql);
        echo "<option value=\'0\'>Seleccione</option>";
        while($key = $res->fetch_object()){
            echo "<option value=\'".$key->LISOPC_ConsInte__b."\'>".$key->LISOPC_Nombre____b."</option>";
        }
    }   
    '.$recibocaptcha.'
        //Inserciones o actualizaciones
        if(isset($_POST["oper"])){
            $str_Lsql  = \'\';

        $validar = 0;
        $str_LsqlU = "UPDATE ".$BaseDatos.".'.$str_guion.' SET '.$str_guion.'_FechaUltimoCargue = NOW(), "; 
        $str_LsqlI = "INSERT INTO ".$BaseDatos.".'.$str_guion.'( '.$str_guion.'_FechaInsercion ,";
        $str_LsqlV = " VALUES (NOW() ,"; '."\n";

                    //Esta consulta la hago para los campos del select
            $campos_7 = $mysqli->query($str_Lsql);

            while ($key = $campos_7->fetch_object()) {
              
                if( $key->id != $GUION__ConsInte__PREGUN_Tip_b && 
                            $key->id != $GUION__ConsInte__PREGUN_Rep_b &&
                            $key->id != $GUION__ConsInte__PREGUN_Fag_b && 
                            $key->id != $GUION__ConsInte__PREGUN_Hag_b &&
                            $key->id != $GUION__ConsInte__PREGUN_Com_b){
            
                    $valorPordefecto = $key->PREGUN_Default___b;                
    


                    if($key->PREGUN_ContAcce__b != 2){
                        if($key->tipo_Pregunta == 5){ // tipo fecha
                            $crud .= ' 
        $'.$str_Guion_C.$key->id.' = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        if(isset($_POST["'.$str_Guion_C.$key->id.'"])){    
            if($_POST["'.$str_Guion_C.$key->id.'"] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $'.$str_Guion_C.$key->id.' = "\'".str_replace(\' \', \'\',$_POST["'.$str_Guion_C.$key->id.'"])." 00:00:00\'";
                $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = ".$'.$str_Guion_C.$key->id.';
                $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
                $str_LsqlV .= $separador.$'.$str_Guion_C.$key->id.';
                $validar = 1;
            }
        }'."\n";
                        }else if($key->tipo_Pregunta == 10){ // tipo timer
                            $crud .= '  
        $'.$str_Guion_C.$key->id.' = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if(isset($_POST["'.$str_Guion_C.$key->id.'"])){   
            if($_POST["'.$str_Guion_C.$key->id.'"] != \'\' && $_POST["'.$str_Guion_C.$key->id.'"] != \'undefined\' && $_POST["'.$str_Guion_C.$key->id.'"] != \'null\'){
                $separador = "";
                $fecha = date(\'Y-m-d\');
                if($validar == 1){
                    $separador = ",";
                }

                $'.$str_Guion_C.$key->id.' = "\'".$fecha." ".str_replace(\' \', \'\',$_POST["'.$str_Guion_C.$key->id.'"])."\'";
                $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = ".$'.$str_Guion_C.$key->id.'."";
                $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
                $str_LsqlV .= $separador.$'.$str_Guion_C.$key->id.';
                $validar = 1;
            }
        }'."\n";
                        }else if($key->tipo_Pregunta == 3){ // tipo Entero
                            $crud .= '  
        $'.$str_Guion_C.$key->id.' = NULL;
        //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
        if(isset($_POST["'.$str_Guion_C.$key->id.'"])){
            if($_POST["'.$str_Guion_C.$key->id.'"] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$'.$str_Guion_C.$key->id.' = $_POST["'.$str_Guion_C.$key->id.'"];
                $'.$str_Guion_C.$key->id.' = str_replace(".", "", $_POST["'.$str_Guion_C.$key->id.'"]);
                $'.$str_Guion_C.$key->id.' =  str_replace(",", ".", $'.$str_Guion_C.$key->id.');
                $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = \'".$'.$str_Guion_C.$key->id.'."\'";
                $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
                $str_LsqlV .= $separador."\'".$'.$str_Guion_C.$key->id.'."\'";
                $validar = 1;
            }
        }'."\n";
                        }else if($key->tipo_Pregunta == 4){ // tipo Decimal
                            $crud .= '  
        $'.$str_Guion_C.$key->id.' = NULL;
        //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
        if(isset($_POST["'.$str_Guion_C.$key->id.'"])){
            if($_POST["'.$str_Guion_C.$key->id.'"] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                
                $'.$str_Guion_C.$key->id.' = str_replace(".", "", $_POST["'.$str_Guion_C.$key->id.'"]);
                $'.$str_Guion_C.$key->id.' =  str_replace(",", ".", $'.$str_Guion_C.$key->id.');
                $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = \'".$'.$str_Guion_C.$key->id.'."\'";
                $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
                $str_LsqlV .= $separador."\'".$'.$str_Guion_C.$key->id.'."\'";
                $validar = 1;
            }
        }'."\n";

                        }else if($key->tipo_Pregunta == 8){ // tipo Check
                            $crud .= '  
        $'.$str_Guion_C.$key->id.' = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if(isset($_POST["'.$str_Guion_C.$key->id.'"])){
            if($_POST["'.$str_Guion_C.$key->id.'"] == \'Yes\'){
                $'.$str_Guion_C.$key->id.' = 1;
            }else if($_POST["'.$str_Guion_C.$key->id.'"] == \'off\'){
                $'.$str_Guion_C.$key->id.' = 0;
            }else if($_POST["'.$str_Guion_C.$key->id.'"] == \'on\'){
                $'.$str_Guion_C.$key->id.' = 1;
            }else if($_POST["'.$str_Guion_C.$key->id.'"] == \'No\'){
                $'.$str_Guion_C.$key->id.' = 0;
            }else{
                $'.$str_Guion_C.$key->id.' = $_POST["'.$str_Guion_C.$key->id.'"] ;
            }   

            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = ".$'.$str_Guion_C.$key->id.'."";
            $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
            $str_LsqlV .= $separador.$'.$str_Guion_C.$key->id.';

            $validar = 1;
        }else{
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = ".$'.$str_Guion_C.$key->id.'."";
            $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
            $str_LsqlV .= $separador.$'.$str_Guion_C.$key->id.';

            $validar = 1;
        }'."\n";

                        }else if($key->tipo_Pregunta == 15){ //JDBD tipos ADJUNTOS
    
                            $crud .= '  
        if (isset($_FILES["F'.$str_Guion_C.$key->id.'"]["tmp_name"])) {
            $destinoFile = "/Dyalogo/tmp/G'.$int_Id_Generar.'/";
            $fechUp = date("Y-m-d_H:i:s");
            if (!file_exists("/Dyalogo/tmp/G'.$int_Id_Generar.'")){
                mkdir("/Dyalogo/tmp/G'.$int_Id_Generar.'", 0777);
            }
            if ($_FILES["F'.$str_Guion_C.$key->id.'"]["size"] != 0) {
                $'.$str_Guion_C.$key->id.' = $_FILES["F'.$str_Guion_C.$key->id.'"]["tmp_name"];
                $n'.$str_Guion_C.$key->id.' = $fechUp."_".$_FILES["F'.$str_Guion_C.$key->id.'"]["name"];
                $n'.$str_Guion_C.$key->id.' = str_replace(" ", "_", $n'.$str_Guion_C.$key->id.');
                $rutaFinal = $destinoFile.$n'.$str_Guion_C.$key->id.';
                if (is_uploaded_file($'.$str_Guion_C.$key->id.')) {
                    move_uploaded_file($'.$str_Guion_C.$key->id.', $rutaFinal);
                }

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."'.$str_Guion_C.$key->id.' = \'".$rutaFinal."\'";
                $str_LsqlI .= $separador."'.$str_Guion_C.$key->id.'";
                $str_LsqlV .= $separador."\'".$rutaFinal."\'";
                $validar = 1;
            }
        }'."\n";
    
    
                        }else{ // tipos norrmales
    
                            $crud .= '  
        if(isset($_POST["'.$str_Guion_C.$key->id.'"]) && $_POST["'.$str_Guion_C.$key->id.'"] != \'\'){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."'.$str_Guion_C.$key->id.' = \'".$_POST["'.$str_Guion_C.$key->id.'"]."\'";
            $str_LsqlI .= $separador."'.$str_Guion_C.$key->id.'";
            $str_LsqlV .= $separador."\'".$_POST["'.$str_Guion_C.$key->id.'"]."\'";
            $validar = 1;
        }
         '."\n";
    
    
                        }

                    }else{

                        // Si es un texto o un numero deberia poder insertarlo asi este desabilitado, pero si tiene data
                        if($key->tipo_Pregunta == 3 && $key->PREGUN_ContAcce__b == 2 && $key->PREGUN_Default___b == 3001){
                            $crud .= '  
        $'.$str_Guion_C.$key->id.' = NULL;
        //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
        if(isset($_POST["'.$str_Guion_C.$key->id.'"])){
            if($_POST["'.$str_Guion_C.$key->id.'"] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$'.$str_Guion_C.$key->id.' = $_POST["'.$str_Guion_C.$key->id.'"];
                $'.$str_Guion_C.$key->id.' = str_replace(".", "", $_POST["'.$str_Guion_C.$key->id.'"]);
                $'.$str_Guion_C.$key->id.' =  str_replace(",", ".", $'.$str_Guion_C.$key->id.');
                $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = \'".$'.$str_Guion_C.$key->id.'."\'";
                $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
                $str_LsqlV .= $separador."\'".$'.$str_Guion_C.$key->id.'."\'";
                $validar = 1;
            }
        }'."\n";
                        }

                        switch ($valorPordefecto) {
                            case '501':
                                $crud .= ' 
        $'.$str_Guion_C.$key->id.' = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        $separador = "";
        if($validar == 1){
            $separador = ",";
        }
        $'.$str_Guion_C.$key->id.' = "\'".date(\'Y-m-d H:i:s\')."\'";
        $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = ".$'.$str_Guion_C.$key->id.';
        $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
        $str_LsqlV .= $separador.$'.$str_Guion_C.$key->id.';
        $validar = 1;
       '."\n";                  
                            break;

                            case '1001':
                                $crud .= ' 
        $'.$str_Guion_C.$key->id.' = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        $separador = "";
        if($validar == 1){
            $separador = ",";
        }
        $'.$str_Guion_C.$key->id.' = "\'".date(\'Y-m-d H:i:s\')."\'";
        $str_LsqlU .= $separador." '.$str_Guion_C.$key->id.' = ".$'.$str_Guion_C.$key->id.';
        $str_LsqlI .= $separador." '.$str_Guion_C.$key->id.'";
        $str_LsqlV .= $separador.$'.$str_Guion_C.$key->id.';
        $validar = 1;
       '."\n";  
                            break;

                            case '102':
                                $crud .= '  
        $separador = "";
        if($validar == 1){
            $separador = ",";
        }

        $str_LsqlU .= $separador."'.$str_Guion_C.$key->id.' = \'".getNombreUser($_GET[\'token\'])."\'";
        $str_LsqlI .= $separador."'.$str_Guion_C.$key->id.'";
        $str_LsqlV .= $separador."\'".getNombreUser($_GET[\'token\'])."\'";
        $validar = 1;
    
     '."\n";
                            break;
                    
                            case '105':
                                $crud .= '  
        $separador = "";
        if($validar == 1){
            $separador = ",";
        }

        $str_LsqlU .= $separador."'.$str_Guion_C.$key->id.' = \''.$nombreCampana.'\'";
        $str_LsqlI .= $separador."'.$str_Guion_C.$key->id.'";
        $str_LsqlV .= $separador."\''.$nombreCampana.'\'";
        $validar = 1;

     '."\n";
                            break;

                            default:

                            break;
                        }
                    }
            
                }
            }
            $crud .= ' 
        $padre = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        if(isset($_POST["padre"])){    
            if($_POST["padre"] != \'0\' && $_POST[\'padre\'] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //primero hay que ir y buscar los campos
                $str_Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST[\'formpadre\']." AND GUIDET_ConsInte__GUION__Det_b = ".$_POST[\'formhijo\'];

                $GuidRes = $mysqli->query($str_Lsql);
                $campo = null;
                while($ky = $GuidRes->fetch_object()){
                    $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                }
                $valorG = "'.$str_Guion_C.'";
                $valorH = $valorG.$campo;
                $str_LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                $str_LsqlI .= $separador." ".$valorH;
                $str_LsqlV .= $separador.$_POST[\'padre\'] ;
                $validar = 1;
            }
        }'."\n";

        //  Validamos si al generar el paso llego algo
        $crud .= '
            if(isset($_POST["pasoId"])){
                $pasoWebFormId = $_POST["pasoId"];
            }else{
                $pasoWebFormId = 0;
            }

            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlI .= $separador."'.$str_guion.'_PoblacionOrigen";
            $str_LsqlV .= $separador."\'$pasoWebFormId\'";
            $validar = 1;            
        '."\n";   

            $crud .= '
        if(isset($_GET[\'id_gestion_cbx\'])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."'.$str_guion.'_IdLlamada = \'".$_GET[\'id_gestion_cbx\']."\'";
            $str_LsqlI .= $separador."'.$str_guion.'_IdLlamada";
            $str_LsqlV .= $separador."\'".$_GET[\'id_gestion_cbx\']."\'";
            $validar = 1;
        }


        if(isset($_POST[\'ORIGEN_DY_WF\'])){
            if($_POST[\'ORIGEN_DY_WF\'] != \'0\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $Origen = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = '.$int_Id_Generar.' AND PREGUN_Texto_____b = \'ORIGEN_DY_WF\'";
                $res_Origen = $mysqli->query($Origen);
                if($res_Origen->num_rows > 0){
                    $dataOrigen = $res_Origen->fetch_array();
                    // En el update me intereza actualizar el campo de ultimo origen de cargue
                    $str_LsqlU .= $separador."'.$str_guion.'_OrigenUltimoCargue = \'".$_POST[\'ORIGEN_DY_WF\']."\'";

                    // En el insert agrego el origen en los dos campos
                    $str_LsqlI .= $separador."'.$str_guion.'_OrigenUltimoCargue";
                    $str_LsqlI .= $separador."'.$str_Guion_C.'".$dataOrigen[\'PREGUN_ConsInte__b\'];
                    $str_LsqlV .= $separador."\'".$_POST[\'ORIGEN_DY_WF\']."\'";
                    $str_LsqlV .= $separador."\'".$_POST[\'ORIGEN_DY_WF\']."\'";
                    $validar = 1;
                }
                

            }
        }

        if(isset($_POST[\'OPTIN_DY_WF\'])){
            if($_POST[\'OPTIN_DY_WF\'] != \'0\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $confirmado = null;
                if($_POST[\'OPTIN_DY_WF\'] == \'SIMPLE\'){
                    $confirmado  = "\'CONFIRMADO\'";
                }
                /*
                $OPTIN_DY_WF = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = '.$int_Id_Generar.' AND PREGUN_Texto_____b = \'OPTIN_DY_WF\'";
                $res_OPTIN_DY_WF = $mysqli->query($OPTIN_DY_WF);
                if($res_OPTIN_DY_WF->num_rows > 0){
                    $dataOPTIN_DY_WF = $res_OPTIN_DY_WF->fetch_array();

                    // $str_LsqlU .= $separador."'.$str_Guion_C.'".$dataOPTIN_DY_WF[\'PREGUN_ConsInte__b\']." = ".$confirmado;
                    $str_LsqlI .= $separador."'.$str_Guion_C.'".$dataOPTIN_DY_WF[\'PREGUN_ConsInte__b\'];
                    $str_LsqlV .= $separador." ".$confirmado;
                    $validar = 1;
                }*/
            }
        }


        // Aqui es donde tengo que definir si el registro ya existe con anterioridad basados en la llave
        $sqlPregun = "SELECT WEBFORM_ConsInte_PREGUN_Campo_Llave_b AS pregun FROM $BaseDatos_systema.WEBFORM WHERE WEBFORM_Consinte__b = '.$webFormId.' LIMIT 1";
        $resPregun = $mysqli->query($sqlPregun);

        $registroExiste = false;
        $idRegistroExiste = 0;

        if($resPregun && $resPregun->num_rows > 0){
            $row = $resPregun->fetch_array();
            
            // Para que valide la llave tiene que ser diferente de cero
            if($row[\'pregun\'] != 0){
                $llave = $row[\'pregun\'];
                
                if(isset($_POST["'.$str_Guion_C.'".$llave])){
    
                    $registroSql = "SELECT G'.$int_Id_Generar.'_ConsInte__b AS id FROM $BaseDatos.G'.$int_Id_Generar.' WHERE '.$str_Guion_C.'$llave = \'".$_POST["'.$str_Guion_C.'".$llave]."\'";
                    $resRegistro = $mysqli->query($registroSql);

                    if($resRegistro && $resRegistro->num_rows > 0){
                        
                        $registro = $resRegistro->fetch_array();

                        $registroExiste = true;
                        $idRegistroExiste = $registro[\'id\'];
                        
                    }
                }
            }
        }

        if(isset($_POST[\'oper\'])){
            if($_POST["oper"] == \'add\' && !$registroExiste){
                
                $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
            }else{
                $str_Lsql = $str_LsqlU. " WHERE G'.$int_Id_Generar.'_ConsInte__b = ".$idRegistroExiste;
            }
        }

        //si trae algo que insertar inserta

        //echo $str_Lsql;
        if($validar == 1){
            if ($mysqli->query($str_Lsql) === TRUE) {
                $ultimoResgistroInsertado = $mysqli->insert_id;

                // Si el registro no existe se inicializan los campos
                if(!$registroExiste){
                    // La tipificacion en el Journey cambia si el registro no existe
                    $tipificacionJ = -302;

                    // Criterios de inicialización que se usan en el cargador
                    $UpdContext = "UPDATE ".$BaseDatos.".'.$str_guion.' SET '.$str_guion.'_UltiGest__b = -14, '.$str_guion.'_GesMasImp_b = -14, '.$str_guion.'_TipoReintentoUG_b = 0, '.$str_guion.'_TipoReintentoGMI_b = 0, '.$str_guion.'_ClasificacionUG_b = 3, '.$str_guion.'_ClasificacionGMI_b = 3, '.$str_guion.'_EstadoUG_b = -14, '.$str_guion.'_EstadoGMI_b = -14, '.$str_guion.'_CantidadIntentos = 0, '.$str_guion.'_CantidadIntentosGMI_b = 0 WHERE '.$str_guion.'_ConsInte__b = ".$ultimoResgistroInsertado;
                    $UpdContext = $mysqli->query($UpdContext);
                }else{
                    $tipificacionJ = -303;

                    // Si el registro existe paso el id
                    $ultimoResgistroInsertado = $idRegistroExiste;
                }

                $strParamPaso_t =\'\';

                ';

                if ($webformTipo == 19){
                    $crud .= '
                    $pasoIdJ = (isset($_POST["pasoId"])) ? $_POST["pasoId"] : null;
                $journeyData = ["sentido" => "Entrante", "tipificacion" => -501, "clasificacion" => 3, "tipoReintento" => 0];
                insertarJourney($ultimoResgistroInsertado, '.$int_Id_Generar.' , $pasoIdJ , $journeyData );
                    ';
                }else{
                    $crud .= '
                    $pasoIdJ = (isset($_POST["pasoId"])) ? $_POST["pasoId"] : null;
                    $journeyData = ["sentido" => "Entrante", "tipificacion" => $tipificacionJ, "clasificacion" => 3, "tipoReintento" => 0];
                    insertarJourney($ultimoResgistroInsertado, '.$int_Id_Generar.' , $pasoIdJ , $journeyData );
                        ';
                }

                $crud .= '

                if (isset($_POST["pasoId"])) {
                    
                    $strParamPaso_t = \'&paso=\'.$_POST["pasoId"].\'&origen=\'.$_POST[\'ORIGEN_DY_WF\'];
                    ';

                    if ($webformTipo == 19){
                        $crud .= ' enviarCorreoParaDyalogo($_POST["pasoId"], $ultimoResgistroInsertado);
                        ';
                    }else{
                        $crud .= ' DispararProceso($_POST["pasoId"], $ultimoResgistroInsertado);
                        ';
                    }
                    
                    $crud .= '

                }

                header(\'Location:https://\'.$_SERVER[\'HTTP_HOST\'].\'/crm_php/web_forms.php?web2='.base64_encode($int_Id_Generar.'_'.$webFormId).'&result=1\'.$strParamPaso_t);

            } else {
                echo "Error Haciendo el proceso los registros : " . $mysqli->error;
            }
        }
    }
    '.$cierracaptcha.'


?>
';    
            
            fputs($fcrud , $crud);      
            fclose($fcrud); 

        }else{
            echo "No se ah recibido nada";
        }
    }
