<?php
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include(__DIR__."/../conexion.php");

if(isset($_GET['sendMail'])){
    $cuenta=isset($_POST['cuenta']) ? $_POST['cuenta'] :false;
    $para=isset($_POST['para']) ? $_POST['para'] :false;
    $cc=isset($_POST['cc']) ? $_POST['cc'] :null;
    $cco=isset($_POST['cco']) ? $_POST['cco'] :null;
    $asunto=isset($_POST['asunto']) ? $_POST['asunto'] :false;
    $cuerpo=isset($_POST['cuerpo']) ? $_POST['cuerpo'] :false;
    $adjuntos=isset($_POST['stradjuntos']) ? $_POST['stradjuntos'] :null;
    $responder=isset($_POST['responder']) ? $_POST['responder'] :null;
    $origen=isset($_POST['responder']) ? $_POST['para'] :null;
    $countAdjuntos=isset($_POST['countAdjuntos']) ? $_POST['countAdjuntos'] :false;
    $formulario=isset($_POST['formulario']) ? $_POST['formulario'] :false;
    $agente=isset($_POST['agente']) ? $_POST['agente'] :  false;
    if(!$agente  || $agente ==0){
        $agente=isset($_SESSION['IDENTIFICACION']) ? $_SESSION['IDENTIFICACION'] :  false;
        if(!$agente){
            $agente=isset($_SESSION['HUESPED']) ? $_SESSION['HUESPED'] : '-10';
        }
    }
    $miembro=isset($_POST['miembro']) ? $_POST['miembro'] :  "null";

    $data = array(
        "strUsuario_t" => "crm",
        "strToken_t" => "D43dasd321",
        "strIdCfg_t" => $cuenta,
        "strTo_t" => $para,
        "strCC_t" => $cc,
        "strCCO_t" => $cco,                
        "strSubject_t" => $asunto,
        "strIdMessage_t" =>$responder,
        "strToFw_t" =>$origen,
        "intIdEstPas_t" => "-".$formulario,
        "intConsinteMiembro_t" => $miembro,
        "intIdAgente_t" => $agente
    );

    if($countAdjuntos > 0){
        $adjuntos='';
        for($i=0; $i<$countAdjuntos; $i++){
            if (isset($_FILES["adjunto_{$i}"]["tmp_name"]) && $_FILES["adjunto_{$i}"]["size"] != 0) {
                if (!file_exists("/Dyalogo/tmp/adjuntos")){
                    mkdir("/Dyalogo/tmp/adjuntos", 0777);
                }
                if (!file_exists("/Dyalogo/tmp/adjuntos/G{$formulario}")){
                    mkdir("/Dyalogo/tmp/adjuntos/G{$formulario}", 0777);
                }
                
                $archivoNombre = $_FILES["adjunto_{$i}"]['name'];
                if (is_uploaded_file($_FILES["adjunto_{$i}"]["tmp_name"])) {
                    move_uploaded_file($_FILES["adjunto_{$i}"]["tmp_name"], "/Dyalogo/tmp/adjuntos/G{$formulario}/{$archivoNombre}");
                    if($adjuntos==''){
                        $adjuntos.="/Dyalogo/tmp/adjuntos/G{$formulario}/{$archivoNombre}";
                    }else{
                        $adjuntos.=",/Dyalogo/tmp/adjuntos/G{$formulario}/{$archivoNombre}";
                    }
                }
                $data["strListaAdjuntos_t"] = $adjuntos;
            }      
        }
    }

    if($cuenta && $para && $asunto && $cuerpo){
        $cuerpo = str_replace('\r\n', '<br>', $cuerpo);
        $data["strMessage_t"]=$cuerpo;
        $sql=$mysqli->query("SELECT id_huesped FROM dyalogo_canales_electronicos.dy_ce_configuracion WHERE id={$cuenta}");
        if($sql && $sql->num_rows==1){
            $sql=$sql->fetch_object();
            $huesped=$sql->id_huesped;
            $data['intIdHuesped']=$huesped;
            $data_string = json_encode($data);
            //echo $data_string;
            $ch = curl_init('http://localhost:8080/dyalogocore/api/ce/correo/sendmailservice');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );
            $respuesta = curl_exec($ch);
            echo $respuesta;
            $error = curl_error($ch);
            echo $error;
            curl_close($ch);
        }else{
            echo json_encode(array('strEstado_t' => 'error', 'strMensaje_t' => 'No se pudo identificar la cuenta'));
        }
    }else{
        echo json_encode(array('strEstado_t' => 'error', 'strMensaje_t' => 'Datos no validos'));
    }

}

if(isset($_GET['sendSms'])){
    $cuenta=isset($_POST['cuenta']) ? $_POST['cuenta'] :false;
    $para=isset($_POST['destinatario']) ? $_POST['destinatario'] :false;
    $cuerpo=isset($_POST['mensaje']) ? $_POST['mensaje'] :false;
    $agente=isset($_POST['agente']) ? $_POST['agente'] :  false;
    $miembro=isset($_POST['miembro']) ? $_POST['miembro'] :  "null";
    $formulario=isset($_POST['campoSms']) ? '-'.explode('G',explode('_',$_POST['campoSms'])[0])[1] :  "null";
    if(!$agente  || $agente ==0){
        $agente=isset($_SESSION['IDENTIFICACION']) ? $_SESSION['IDENTIFICACION'] :  false;
        if(!$agente){
            $agente=isset($_SESSION['HUESPED']) ? $_SESSION['HUESPED'] : '-10';
        }
    }
    if($cuenta && $para && $cuerpo){
        $sql=$mysqli->query("SELECT id_huesped FROM dy_sms.configuracion WHERE id={$cuenta}");
        if($sql && $sql->num_rows==1){
            $sql=$sql->fetch_object();
            $huesped=$sql->id_huesped;
            $data = array(
                "strUsuario_t" => "crm",
                "strToken_t" => "D43dasd321",
                "strTelefono_t"  => $para,
                "strMensaje_t"  =>  $cuerpo, 
                "intIdConfiguracion_t"  =>  $cuenta, 
                "intIdEstPas_t"  =>  $formulario,
                "intConsinteMiembro_t"  =>  $miembro,
                "intIdAgente_t"  =>  $agente,
                "intIdHuesped_t"  =>  $huesped
            );

            $data_string = json_encode($data);
            //echo $data_string;
            $ch = curl_init('http://localhost:8080/dyalogocore/api/bi/enviarSMS');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );
            $respuesta = curl_exec($ch);
            echo $respuesta;
            $error = curl_error($ch);
            echo $error;
            curl_close($ch);
        }else{
            echo json_encode(array('strEstado_t' => 'error', 'strMensaje_t' => 'No se pudo identificar la cuenta'));
        }
    }else{
        echo json_encode(array('strEstado_t' => 'error', 'strMensaje_t' => 'Datos no validos'));
    }

}

if(isset($_GET['searchMail'])){
    $fecha_inicial=isset($_POST['fecha_inicial']) ? $_POST['fecha_inicial'] :false;
    $fecha_final=isset($_POST['fecha_final']) ? $_POST['fecha_final'] :false;
    $asunto=isset($_POST['asunto']) ? $_POST['asunto'] :false;
    $de=isset($_POST['de']) ? $_POST['de'] :false;
    $adjuntos=isset($_POST['adjuntos']) ? $_POST['adjuntos'] :false;
    $cuerpo=isset($_POST['cuerpo']) ? $_POST['cuerpo'] :false;
    $cuenta=isset($_POST['cuenta']) ? $_POST['cuenta'] :false;
    $gestion=isset($_POST['conGestion']) ? $_POST['conGestion'] :false;
    $sinGestion=isset($_POST['sinGestion']) ? $_POST['sinGestion'] :false;
    
    if($cuenta){
        //se arma la consulta
        if($adjuntos){
            $sql="select a.*,b.nombre as nombre_archivo,b.ruta_fisica,c.nombre from dyalogo_canales_electronicos.dy_ce_entrantes a join dyalogo_canales_electronicos.dy_ce_entrante_adjuntos b on a.id=b.id_ce_entrante left join dyalogo_telefonia.dy_agentes c ON a.id_agente_asignado=c.id where a.id_ce_configuracion={$cuenta} and b.ruta_fisica is not null";
        }else{
            $sql="select a.*,b.nombre as nombre_archivo,b.ruta_fisica,c.nombre from dyalogo_canales_electronicos.dy_ce_entrantes a left join dyalogo_canales_electronicos.dy_ce_entrante_adjuntos b on a.id=b.id_ce_entrante left join dyalogo_telefonia.dy_agentes c ON a.id_agente_asignado=c.id where a.id_ce_configuracion={$cuenta}";
        }
        
        if($fecha_inicial && $fecha_final){
            $sql.=" and fecha_hora_recibido_servidor between '{$fecha_inicial}' and '{$fecha_final} 23:59:59'";
        }elseif($fecha_inicial){
            $sql.=" and fecha_hora_recibido_servidor >= '{$fecha_inicial}'";
        }elseif($fecha_final){
            $sql.=" and fecha_hora_recibido_servidor <= '{$fecha_final} 23:59:59'";
        }
        
        if($asunto){
            $sql.=" and asunto like '%{$asunto}%'";
        }
        
        if($de){
            $sql.=" and de='{$de}'";
        }
        
        if($cuerpo){
            $sql.=" and cuerpo like '%{$cuerpo}%'";
        }
        
        if($sinGestion){
            $sql.=" and leido='0' and estado_gestion='1' and es_respuesta='0'";
        }
        
        if($gestion){
            $sql.=" and leido='1' and estado_gestion='2'";
        }
        $sql.=" group by id order by fecha_hora_recibido_servidor desc limit 20";
        $sql=$mysqli->query($sql);
        
        if($sql && $sql->num_rows>0){
            $html='';
            $fila=0;
            $adjunto=false;
            $strAdjuntos='';
            //se dibuja el html de cada correo encontrado
            while($mail=$sql->fetch_object()){
                $sqlRastreo=$mysqli->query("select fecha_hora,cuerpo,nombre_usuario from dyalogo_canales_electronicos.dy_ce_rastreo where id_ce_entrante={$mail->id} and id_agente is not null");
                if($sqlRastreo && $sqlRastreo->num_rows>0){
                    $sqlRastreo=$sqlRastreo->fetch_object();
                    $html.="<form class='form_fila' id='formulario_{$fila}'><div class=\"panel box box-primary box-solid\" style=\"margin-bottom:10px;\"><div class=\"box-header with-border\" style=\"height:30px\"><h3 class=\"box-title\" style=\"width:100%\"><a class='seccion' fila='{$fila}' data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#fila_{$fila}\" style=\"display:flex;justify-content:space-between;font-size:14px\"><span>Fecha: {$mail->fecha_hora}</span><span>De: {$mail->de}</span><span>Asunto: ".urldecode($mail->asunto)."</span></a></h3></div><div id=\"fila_{$fila}\" class=\"panel-collapse collapse\"><div class=\"box-body\"><div class=\"row\"><div class=\"col-md-12 col-xs-12 form-group\"><div class=\"form-group\"><div style=\"display:flex;justify-content:space-between\"><div><strong>Fecha: </strong><span>{$mail->fecha_hora}</span></div><div><div class=\"btn btn-block btn-primary reenviar\" fila='{$fila}'>Reenviar</div><div class=\"btn btn-block btn-primary responder\" fila='{$fila}'>Responder</div></div></div></div><div class=\"form-group\"><div style=\"display:flex;justify-content:space-between\"><div><strong>Asunto: </strong><span>".urldecode($mail->asunto)."</span></div><div></div></div></div><div class=\"form-group\"><div style=\"display:flex;justify-content:space-between\"><div><strong>De: </strong><span>{$mail->de}</span></div><div></div></div></div><div class=\"form-group\"><table class=\"table table-bordered\"><thead><tr><th>Cuerpo del mail recibido</th><th>Cuerpo del mail enviado</th></tr></thead><tbody><tr><td id='cuerpo_{$fila}'>".urldecode($mail->cuerpo)."</td><td id='cuerpoRastro_{$fila}'>".urldecode($sqlRastreo->cuerpo)."</td></tr></tbody></table></div>";
                }else{
                    $html.="<form class='form_fila' id='formulario_{$fila}'><div class=\"panel box box-primary box-solid\" style=\"margin-bottom:10px;\"><div class=\"box-header with-border\" style=\"height:30px\"><h3 class=\"box-title\" style=\"width:100%\"><a class='seccion' fila='{$fila}' data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#fila_{$fila}\" style=\"display:flex;justify-content:space-between;font-size:14px\"><span>Fecha: {$mail->fecha_hora}</span><span>De: {$mail->de}</span><span>Asunto: ".urldecode($mail->asunto)."</span></a></h3></div><div id=\"fila_{$fila}\" class=\"panel-collapse collapse\"><div class=\"box-body\"><div class=\"row\"><div class=\"col-md-12 col-xs-12 form-group\"><div class=\"form-group\"><div style=\"display:flex;justify-content:space-between\"><div><strong>Fecha: </strong><span>{$mail->fecha_hora}</span></div><div><div class=\"btn btn-block btn-primary reenviar\" fila='{$fila}'>Reenviar</div><div class=\"btn btn-block btn-primary responder\" fila='{$fila}'>Responder</div></div></div></div><div class=\"form-group\"><div style=\"display:flex;justify-content:space-between\"><div><strong>Asunto: </strong><span>".urldecode($mail->asunto)."</span></div><div></div></div></div><div class=\"form-group\"><div style=\"display:flex;justify-content:space-between\"><div><strong>De: </strong><span>{$mail->de}</span></div><div></div></div></div><div class=\"form-group\"><table class=\"table table-bordered\"><thead><tr><th>Cuerpo del mail recibido</th></tr></thead><tbody><tr><td id='cuerpo_{$fila}'>".urldecode($mail->cuerpo)."</td></tr></tbody></table></div>";
                }
                
                $sqlAdjuntos=$mysqli->query("select nombre,ruta_fisica from dyalogo_canales_electronicos.dy_ce_entrante_adjuntos where id_ce_entrante={$mail->id}");
                if($sqlAdjuntos && $sqlAdjuntos->num_rows>0){
                    //Aqui se realiza el dibujo en html de los adjuntos
                    $html.="<div class=\"form-group\"><table class=\"table table-bordered\"><thead><tr><th>Adjuntos</th></tr></thead><tbody>";
                    $i=0;
                    while($esteAdjunto = $sqlAdjuntos->fetch_object()){
                        $html.="<tr><td><a href='#' target='_blank' onclick=\"location.href='formularios/enviarSms_Mail_crud.php?descargarAdjunto=si&ruta={$esteAdjunto->ruta_fisica}'\">{$esteAdjunto->nombre}</a></td></tr>";
                        if($i==0){
                            $strAdjuntos.="{$esteAdjunto->ruta_fisica}";
                        }else{
                            $strAdjuntos.=",{$esteAdjunto->ruta_fisica}";
                        }
                        $i++;
                    }
                    $html.="</tbody></table></div></div></div>";
                    $i=0;
                }else{
                    $html.="</div></div>";
                }
                
                //aqui se dibuja el html de la sección de tipificacion
                if($mail->leido=='0' && $mail->estado_gestion=='1' && $mail->es_respuesta=='0'){
                    $html.="<div class='row'><div class='col-md-5 col-xs-12'><div class='form-group'><select required class=\"form-control input-sm tipificacion_correo\" name=\"{$fila}_tipificacion\" id=\"{$fila}_tipificacion\" fila='{$fila}' ><option value=\"\">Tipificación</option>";                    
                    $sqlTipificacion=$mysqli->query("SELECT PREGUN_ConsInte__b,PREGUN_ConsInte__OPCION_B FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b ={$_POST['formulario']} AND PREGUN_Texto_____b='Tipificación' AND SECCIO_TipoSecc__b=3");
                    if($sqlTipificacion && $sqlTipificacion->num_rows==1){
                        $sqlTipificacion=$sqlTipificacion->fetch_object();
                        $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM DYALOGOCRM_SISTEMA.LISOPC 
                                JOIN DYALOGOCRM_SISTEMA.MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                                WHERE LISOPC.LISOPC_ConsInte__OPCION_b = {$sqlTipificacion->PREGUN_ConsInte__OPCION_B};";
                        $obj = $mysqli->query($Lsql);
                        while($obje = $obj->fetch_object()){
                            $html.="<option value='".$obje->OPCION_ConsInte__b."' efecividad = '".$obje->MONOEF_EFECTIVA__B."' monoef='".$obje->MONOEF_ConsInte__b."' TipNoEF = '".$obje->MONOEF_TipNo_Efe_b."' cambio='".$obje->LISOPC_CambRepr__b."' importancia = '".$obje->MONOEF_Importanc_b."' contacto='".$obje->MONOEF_Contacto__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                    }
                    

                    $html.="</select></div></div><div class='col-md-5 col-xs-12'><div class='form-group'><textarea class='form-control input-sm' placeholder='Observaciones' name=\"{$fila}_observacion\" id=\"{$fila}_observacion\" fila='{$fila}'></textarea></div></div><div class='col-md-2 col-xs-12'><div class=\"btn btn-primary btn-block SaveGestionCorreo\" id=\"{$fila}\">Cerrar Gestión</div></div></div>";
                    $html.='<input type="hidden" name="Efectividad" id="Efectividad" value="0">
                    <input type="hidden" name="'.$fila.'_MonoEf" id="'.$fila.'_MonoEf" value="0">
                    <input type="hidden" name="'.$fila.'_TipNoEF" id="'.$fila.'_TipNoEF" value="0">
                    <input type="hidden" name="'.$fila.'_FechaInicio" id="'.$fila.'_FechaInicio" value="0">
                    <input type="hidden" name="'.$fila.'_FechaFinal" id="'.$fila.'_FechaFinal" value="0">
                    <input type="hidden" name="'.$fila.'_MonoEfPeso" id="'.$fila.'_MonoEfPeso" value="0">
                    <input type="hidden" name="'.$fila.'_ContactoMonoEf" id="'.$fila.'_ContactoMonoEf" value="0">
                    <input type="hidden" name="'.$fila.'_reintento" id="'.$fila.'_reintento" value="0" value="0">
                    <input type="hidden" name="'.$fila.'_correoId" id="'.$fila.'_correoId" value="'.$mail->id.'">
                    <input type="hidden" name="'.$fila.'_correo" id="'.$fila.'_correo" value="'.$mail->de.'">';
                }else{
                    $html.="<span style='color:gray'>Registro gestionado por: <strong> {$mail->nombre} </strong></span>";
                }
                
                //pintar los campos ocultos
                $html.="</div></div><input type='hidden' name='{$fila}_inicio' id='{$fila}_inicio'><input type='hidden' id='de_{$fila}' de='{$mail->de}'><input type='hidden' id='cc_{$fila}' cc='{$mail->cc}'><input type='hidden' id='cco_{$fila}' cco='{$mail->cco}'><input type='hidden' id='asunto_{$fila}' asunto='".urldecode($mail->asunto)."'><input type='hidden' id='adjuntos_{$fila}' adjuntos='".$strAdjuntos."'><input type='hidden' id='responder_{$fila}' responder='".$mail->message_id."'></div></form>";
                $adjunto=true;
                $fila++;
                $strAdjuntos='';
            }
            
            $html.="<script>
                var meses = new Array(12);
                meses[0] = '01';
                meses[1] = '02';
                meses[2] = '03';
                meses[3] = '04';
                meses[4] = '05';
                meses[5] = '06';
                meses[6] = '07';
                meses[7] = '08';
                meses[8] = '09';
                meses[9] = '10';
                meses[10] ='11';
                meses[11] ='12';

                var d = new Date();
                var h = d.getHours();
                var horas = (h < 10) ? '0' + h : h;
                var dia = d.getDate();
                var dias = (dia < 10) ? '0' + dia : dia;
                var fechaInicial = d.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias + ' '+ horas +':'+d.getMinutes()+':'+d.getSeconds();
                
                $('.reenviar').on('click', function(e){
                e.preventDefault();
                var fila=$(this).attr('fila');
                $('#modal_sendMail').modal('show');
                $('#asunto').val($('#asunto_'+fila).attr('asunto'));
                $('#cuerpo').val($('#cuerpo_'+fila).html());
                $('#cuerpo').html($('#cuerpo_'+fila).html());
                $('#stradjuntos').val($('#adjuntos_'+fila).attr('adjuntos'));
                $('#cuentaMail').val($('#cuentaSearchMail').val());            
                });
                
                $('.responder').on('click', function(e){
                e.preventDefault();
                var fila=$(this).attr('fila');
                $('#modal_sendMail').modal('show');
                $('#para').val($('#de_'+fila).attr('de'));
                $('#asunto').val($('#asunto_'+fila).attr('asunto'));
                $('#responder').val($('#responder_'+fila).attr('responder'));
                $('#cuentaMail').val($('#cuentaSearchMail').val());            
                });
                
                $('.tipificacion_correo').change(function() {
                    var id = $(this).attr('id');
                    var fila = $(this).attr('fila');
                    var valor = $('#' + id + ' option:selected').attr('efecividad');
                    var monoef = $('#' + id + ' option:selected').attr('monoef');
                    var TipNoEF = $('#' + id + ' option:selected').attr('TipNoEF');
                    var cambio = $('#' + id + ' option:selected').attr('cambio');
                    var importancia = $('#' + id + ' option:selected').attr('importancia');
                    var contacto = $('#' + id + ' option:selected').attr('contacto');
                    $('#' + fila + '_reintento').val(TipNoEF).change();
                    $('#' + fila + '_Efectividad').val(valor);
                    $('#' + fila + '_MonoEf').val(monoef);
                    $('#' + fila + '_TipNoEF').val(TipNoEF);
                    $('#' + fila + '_MonoEfPeso').val(importancia);
                    $('#' + fila + '_ContactoMonoEf').val(contacto);
                });
                
                $('.seccion').click(function() {
                    var fila = $(this).attr('fila');
                    $('#' + fila + '_FechaInicio').val(fechaInicial);
                });
                
                $('.SaveGestionCorreo').click(function(e) {
                    e.preventDefault();
                    var id=$(this).attr('id');
                    swal({
                        html: true,
                        title: 'Está seguro de marcarlo como gestionado ?',
                        text: 'recuerde que si lo hace este registro ya no va a ser gestionado por ninguna otra persona.',
                        type: 'warning',
                        confirmButtonText: 'Gestionar',
                        cancelButtonText: 'Cancelar',
                        showCancelButton: true,
                        closeOnConfirm: true
                    },
                        function(isconfirm) {
                            if (isconfirm) {
                                saveGestionCorreo(id);
                            }
                        }
                    );
                });
        </script>";                
            echo json_encode(array('estado' => 'ok', 'mensaje' => $html));
        }else{
            echo json_encode(array('estado' => 'error', 'mensaje' => 'No se encontraron correos'));
        }
    }else{
        echo json_encode(array('estado' => 'error', 'mensaje' => 'No hay cuenta configurada'));
    }
}

if(isset($_GET['closeGestionCorreo'])){
    $fila=isset($_POST['fila']) ? $_POST['fila'] : false;
    if($fila || $fila==0){
        $id=isset($_POST[$fila.'_correoId']) ? $_POST[$fila.'_correoId'] : false;
        $correo=isset($_POST[$fila.'_correo']) ? $_POST[$fila.'_correo'] : false;
        $inicio=isset($_POST[$fila.'_FechaInicio']) ? $_POST[$fila.'_FechaInicio'] : false;
        $formulario=isset($_POST['formulario']) ? $_POST['formulario'] : false;
        $base=isset($_POST['base']) ? $_POST['base'] : false;
        $muestra=isset($_POST['muestra']) ? $_POST['muestra'] : false;
        $campanid=isset($_POST['campan']) ? $_POST['campan'] : false;
        $campoBuscar=isset($_POST['campoBuscar']) ? $_POST['campoBuscar'] : false;
        $strcola=isset($_POST['strCampana']) ? $_POST['strCampana'] : false;
        $estpas=isset($_POST['estpas']) ? $_POST['estpas'] : false;
        $observacion=isset($_POST[$fila.'_observacion']) ? $_POST[$fila.'_observacion'] : '';
        
        $agente=isset($_POST['agente']) ? $_POST['agente'] : false;
        if(!$agente  || $agente ==0){
            $agente=isset($_SESSION['IDENTIFICACION']) ? $_SESSION['IDENTIFICACION'] :  false;
            if(!$agente){
                $agente=isset($_SESSION['HUESPED']) ? $_SESSION['HUESPED'] : '-10';
            }
        }
        
        $contacto=isset($_POST[$fila.'_ContactoMonoEf']) ? $_POST[$fila.'_ContactoMonoEf'] : 'NULL';
        $tipificacion=isset($_POST[$fila.'_tipificacion']) ? $_POST[$fila.'_tipificacion'] : 'NULL';
        $reintento=isset($_POST[$fila.'_reintento']) ? $_POST[$fila.'_reintento'] : 'NULL';
        $monoEf=isset($_POST[$fila.'_MonoEf']) ? $_POST[$fila.'_MonoEf'] : 'NULL';
        $MonoEfPeso=isset($_POST[$fila.'__MonoEfPeso']) ? $_POST[$fila.'__MonoEfPeso'] : 'NULL';
        
        if($id && $agente && $formulario && $campoBuscar && $base && $muestra && $correo && $inicio){
            
            //ACTUALIZAR EL CORREO COMO GESTIONADO
            $sql=$mysqli->query("update dyalogo_canales_electronicos.dy_ce_entrantes set leido=true, estado_gestion=2, estado = 3, cco = 'Gestionado por boton' where id ={$id}");

            //BORRAR DE DY_CE_ESPERA
            $sql=$mysqli->query("delete from dyalogo_canales_electronicos.dy_ce_espera where id_entrante ={$id}");

            //INSERTAR O ACTUALIZAR BD
            $strCorreo=$mysqli->query("select * from DYALOGOCRM_WEB.G{$base} where G{$base}_C{$campoBuscar}='{$correo}' limit 1");
            if($strCorreo && $strCorreo->num_rows>0){
                $strCorreo=$strCorreo->fetch_object();
                $miembro=$strCorreo->G.$base."_ConsInte__b";
                //ACTUALIZAR BD
                $sql="update DYALOGOCRM_WEB.G{$base} set 
                G{$base}_IdLlamada={$id},
                G{$base}_UltiGest__b={$monoEf},
                G{$base}_FecUltGes_b=now(),
                G{$base}_TipoReintentoUG_b={$reintento},
                G{$base}_ClasificacionUG_b={$MonoEfPeso},
                G{$base}_EstadoUG_b={$contacto},
                G{$base}_UsuarioUG_b={$agente},
                G{$base}_Canal_____b='email',
                G{$base}_Sentido___b='Entrante',
                G{$base}_CantidadIntentos=(G{$base}_CantidadIntentos+1),
                G{$base}_PasoUG_b='{$estpas}',
                G{$base}_ComentarioUG_b='{$observacion}',
                G{$base}_LinkContenidoUG_b='https://{$_SERVER['SERVER_NAME']}:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid={$id}&uid2={$id}&canal=email'
                where G{$base}_ConsInte__b={$miembro}";
                $sql=$mysqli->query($sql);
                //echo $sql."<br>";
            }else{
                $sql="insert into DYALOGOCRM_WEB.G{$base} (G{$base}_FechaInsercion,G{$base}_CantidadIntentos,G{$base}_C{$campoBuscar}) values(now(),0,'{$correo}')";
                $sql=$mysqli->query($sql);
                //echo $sql."<br>";
                $miembro=$mysqli->insert_id;
                $sql="update DYALOGOCRM_WEB.G{$base} set 
                G{$base}_IdLlamada={$id},
                G{$base}_UltiGest__b={$monoEf},
                G{$base}_FecUltGes_b=now(),
                G{$base}_TipoReintentoUG_b={$reintento},
                G{$base}_ClasificacionUG_b={$MonoEfPeso},
                G{$base}_EstadoUG_b={$contacto},
                G{$base}_UsuarioUG_b={$agente},
                G{$base}_Canal_____b='email',
                G{$base}_Sentido___b='Entrante',
                G{$base}_CantidadIntentos=(G{$base}_CantidadIntentos+1),
                G{$base}_PasoUG_b='{$estpas}',
                G{$base}_ComentarioUG_b='{$observacion}',
                G{$base}_LinkContenidoUG_b='https://{$_SERVER['SERVER_NAME']}:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid={$id}&uid2={$id}&canal=email'
                where G{$base}_ConsInte__b={$miembro}";
                $sql=$mysqli->query($sql);
                //echo $sql."<br>";
            }

            //INSERTAR EN EL SCRIPT
            $sql=$mysqli->query("SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b = {$formulario} AND (SECCIO_TipoSecc__b=4 OR SECCIO_TipoSecc__b=3)");
            if($sql && $sql->num_rows>0){
                while($campoGuion=$sql->fetch_object()){
                    if($campoGuion->PREGUN_Texto_____b =='Fecha'){
                        $intCampoFechaG="G{$formulario}_C{$campoGuion->PREGUN_ConsInte__b}";
                    }

                    if($campoGuion->PREGUN_Texto_____b == 'Hora'){
                        $intCampoHoraG="G{$formulario}_C{$campoGuion->PREGUN_ConsInte__b}";
                    }                        

                    if($campoGuion->PREGUN_Texto_____b == 'Agente'){
                        $intCampoAgenteG="G{$formulario}_C{$campoGuion->PREGUN_ConsInte__b}";
                    }

                    if($campoGuion->PREGUN_Texto_____b == 'Campaña'){
                        $strCampoCampan="G{$formulario}_C{$campoGuion->PREGUN_ConsInte__b}";
                    }

                    if($campoGuion->PREGUN_Texto_____b == 'Tipificación'){
                        $intCampoTipificacion="G{$formulario}_C{$campoGuion->PREGUN_ConsInte__b}";
                    }

                    if($campoGuion->PREGUN_Texto_____b == 'Reintento'){
                        $strCampoReintento="G{$formulario}_C{$campoGuion->PREGUN_ConsInte__b}";
                    }

                    if($campoGuion->PREGUN_Texto_____b == 'Observacion'){
                        $strCampoObservacion="G{$formulario}_C{$campoGuion->PREGUN_ConsInte__b}";
                    }
                }

                $sql="insert into DYALOGOCRM_WEB.G{$formulario} (
                G{$formulario}_FechaInsercion,
                G{$formulario}_Usuario,
                G{$formulario}_CodigoMiembro,
                G{$formulario}_Origen_b,
                G{$formulario}_IdLlamada,
                G{$formulario}_Sentido___b,
                G{$formulario}_Canal_____b,
                G{$formulario}_Paso,
                G{$formulario}_Clasificacion,
                G{$formulario}_Duracion___b,
                {$intCampoTipificacion},
                {$strCampoReintento},
                {$intCampoFechaG},
                {$intCampoHoraG},
                {$strCampoCampan},
                G{$formulario}_DatoContacto,
                G{$formulario}_LinkContenido,
                {$strCampoObservacion}
                ) values(
                now(),
                {$agente},
                {$miembro},
                'Boton de correo',
                {$id},
                'Entrante',
                'email',
                '{$estpas}',
                {$contacto},
                timediff(NOW(),'{$inicio}'),
                {$tipificacion},
                {$reintento},
                now(),
                date_format(now(), '%H:%i:%s'),
                '{$strcola}',
                '{$correo}',
                'https://{$_SERVER['SERVER_NAME']}:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid={$id}&uid2={$id}&canal=email',
                '{$observacion}'
                )";
                //echo $sql."<br>";
                $sql=$mysqli->query($sql);
            }

            //INSERTAR EN CONDIA
            $sql="insert into DYALOGOCRM_SISTEMA.CONDIA (
            CONDIA_IndiEfec__b,
            CONDIA_TipNo_Efe_b
            ,CONDIA_ConsInte__MONOEF_b,
            CONDIA_TiemDura__b,
            CONDIA_Fecha_____b,
            CONDIA_ConsInte__CAMPAN_b,
            CONDIA_ConsInte__USUARI_b,
            CONDIA_ConsInte__GUION__Gui_b,
            CONDIA_ConsInte__GUION__Pob_b,
            CONDIA_ConsInte__MUESTR_b,
            CONDIA_CodiMiem__b,
            CONDIA_Sentido___b,
            CONDIA_IdenLlam___b,
            CONDIA_UniqueId_b,
            CONDIA_Canal_b
            ) VALUES(
            0,
            {$reintento},
            {$monoEf},
            concat(curdate(),' ',timediff(NOW(),'{$inicio}')),
            now(),
            '{$campanid}',
            '{$agente}',
            '{$formulario}',
            '{$base}',
            '{$muestra}',
            '{$miembro}',
            '2',
            '{$id}',
            '{$id}',
            'email'
            )";
            //echo $sql."<br>";
            $sql=$mysqli->query($sql);
            
            echo json_encode(array('estado'=>'ok','mensaje'=>'Correo gestionado exitosamente'));
        }else{
            echo json_encode(array('estado'=>'error','mensaje'=>'No se puede gestionar desde los formularios de Backoffice'));
        }
    }else{
        echo json_encode(array('estado'=>'error','mensaje'=>'No se pudo obtener los datos del correo a gestionar'));
    }
}

if(isset($_GET['descargarAdjunto'])){
    $ruta=isset($_GET['ruta']) ? $_GET['ruta'] :false;
    
    if($ruta){
        if (is_file($ruta)){
            $size = strlen($ruta);

            if ($size>0) {
                $nombre=basename($ruta);
                $tamano = filesize($ruta);
                header("Content-Description: File Transfer");
                header("Content-type: application/force-download");
                header("Content-disposition: attachment; filename=".$nombre);
                header("Content-Transfer-Encoding: binary");
                header("Expires: 0");
                header("Cache-Control: must-revalidate");
                header("Pragma: public");
                header("Content-Length: " . $tamano);
                ob_clean();
                flush();
                readfile($ruta);
            }else{
                echo json_encode(array('estado' => 'error', 'mensaje' => 'Archivo dañado'));
            }
        }else{
            echo json_encode(array('estado' => 'error', 'mensaje' => 'Archivo no encontrado'));
        }
    }else{
        echo json_encode(array('estado' => 'error', 'mensaje' => 'Archivo sin ruta'));    
    }
}

if(isset($_GET['textDefaultSms'])){
    $campo= isset($_POST['campo']) ? $_POST['campo'] :false;
    $valor= isset($_POST['valor']) ? $_POST['valor'] :false;
    if($campo){
        $sql=$mysqli->query("SELECT PREGUN_Tipo______b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__b={$campo}");
        if($sql){
            $sql=$sql->fetch_object();
            $tipo=$sql->PREGUN_Tipo______b;
            
            if($tipo == 6 && $valor!=0){
                $sqlOption=$mysqli->query("SELECT LISOPC_Nombre____b FROM DYALOGOCRM_SISTEMA.LISOPC WHERE LISOPC_ConsInte__b={$valor}");
                if($sqlOption){
                    $sqlOption=$sqlOption->fetch_object();
                    $valor=$sqlOption->LISOPC_Nombre____b;
                }else{
                    echo json_encode(array('estado'=>'error', 'mensaje' => 'No se pudo obtener el valor del mensaje predefinido'));
                    exit();
                }
            }
            
            echo json_encode(array('estado'=>'ok', 'mensaje'=> $valor));
        }else{
            echo json_encode(array('estado'=>'error', 'mensaje' => 'No se pudo obtener el tipo de campo del mensaje'));
        }
    }else{
        echo json_encode(array('estado'=>'error', 'mensaje' => 'No se pudo obtener el campo del mensaje'));
    }
}

if(isset($_GET['getCaracteres'])){
    $cuenta= isset($_POST['cuenta']) ? $_POST['cuenta'] :false;
    if($cuenta){
        $sql=$mysqli->query("SELECT longitud_maxima_sms FROM dy_sms.configuracion WHERE id={$cuenta}");
        if($sql && $sql->num_rows == 1){
            $sql=$sql->fetch_object();
            $largo=$sql->longitud_maxima_sms;
            echo json_encode(array('estado'=>'ok', 'mensaje'=> $largo));
        }else{
            echo json_encode(array('estado'=>'error', 'mensaje' => 'No se pudo obtener la longitud del sms'));
        }
    }else{
        echo json_encode(array('estado'=>'error', 'mensaje' => 'No se pudo obtener la cuenta para enviar el sms'));
    }
}
?>