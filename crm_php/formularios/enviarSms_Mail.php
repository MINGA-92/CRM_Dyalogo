<script src="assets/plugins/BlockUi/jquery.blockUI.js"></script>
<div class="modal fade-in" id="modal_sendMail" data-backdrop="static" data-keyboard="false" role="dialog" style="z-index:1111">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Enviar correo electronico</h4>
            </div>
            <div class="modal-body cuerpoMail">
                <form action="#" method="post" id="formMail" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="para" id="Lblpara">PARA</label>
                                <input type="text" class="form-control input-sm" id="para" value="" name="para" placeholder="PARA" required>
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="cc" id="Lblcc">CC</label>
                                <input type="text" class="form-control input-sm" id="cc" value="" name="cc" placeholder="CC">
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="cco" id="Lblcco">CCO</label>
                                <input type="text" class="form-control input-sm" id="cco" value="" name="cco" placeholder="CCO">
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="asunto" id="Lblasunto">ASUNTO</label>
                                <input type="text" class="form-control input-sm" id="asunto" value="" name="asunto" placeholder="ASUNTO" required>
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- CAMPO TIPO MEMO -->
                            <div class="form-group">
                                <label for="cuerpo" id="Lblcuerpo">CUERPO</label>
                                <textarea class="form-control input-sm" name="cuerpo" id="cuerpo" value="" placeholder="MENSAJE" required></textarea>
                            </div>
                            <!-- FIN DEL CAMPO TIPO MEMO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="adjuntos"> 
                            </div>
                            <div class="form-group">
                                <label for=""> </label>
                                <button title="Adjunto" class="btn btn-primary btn-sm" id="addAdjunto">+ Adjunto</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for=""> </label>
                                <button title="Enviar" class="btn btn-primary btn-sm" id="sendMail">ENVIAR</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="cuentaMail" name="cuenta" value="">
                    <input type="hidden" id="stradjuntos" name="stradjuntos" value="">
                    <input type="hidden" id="responder" name="responder" value="">
                    <input type="hidden" id="countAdjuntos" name="countAdjuntos" id="countAdjuntos" value="0">
                    <input type="hidden" id="formMail" name="formMail" value="">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-in" id="modal_SendSms" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">ENVIAR MENSAJE DE TEXTO</h4>
            </div>
            <div class="modal-body cuerpoMail">
                <form action="#" method="post" id="formSms">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="destinatario" id="Lbldestinatario">DESTINATARIO</label>
                                <input type="text" class="form-control input-sm" id="destinatario" value="" name="destinatario" placeholder="DESTINATARIO">
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- CAMPO TIPO MEMO -->
                            <div class="form-group">
                                <label for="mensaje" id="Lblmensaje">MENSAJE</label>
                                <textarea class="form-control input-sm" maxlength="160" name="mensaje" id="mensaje" value="" placeholder="MENSAJE"></textarea>
                            </div>
                            <!-- FIN DEL CAMPO TIPO MEMO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for=""> </label>
                                <button title="Enviar" class="btn btn-primary btn-sm" id="sendSms">ENVIAR</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="cuentaSms" name="cuenta" value="">
                    <input type="hidden" id="campoSms" name="campoSms" value="">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-in" id="modal_SearchMail" data-backdrop="static" data-keyboard="false" role="dialog" style="overflow:auto">
    <div class="modal-dialog" style="width:90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Buscar Correo</h4>
            </div>
            <div class="modal-body cuerpoMail">
                <form id="formBusqueda">
                    <input type="hidden" id="cuentaSearchMail" name="cuenta" value="">
                    <input type="hidden" id="campoBuscar" name="campoBuscar" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">CAMPOS DE BUSQUEDA</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">FECHA INICIAL</label>
                                <input type="text" class="form-control input-sm fecha" name="fecha_inicial" id="fecha_inicial" value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">FECHA FINAL</label>
                                <input type="text" class="form-control input-sm fecha" name="fecha_final" id="fecha_final" value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">ASUNTO</label>
                                <input type="text" class="form-control input-sm" name="asunto" id="asunto" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">EL CUERPO CONTIENE</label>
                                <input type="text" class="form-control input-sm" name="cuerpo" id="cuerpo" value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">DE</label>
                                <input type="text" class="form-control input-sm" name="de" id="de" value="">
                            </div>
                        </div>
                        <div class="col-md-1 col-xs-1">
                            <div class="form-group">
                                <label for="adjuntos" id="">ADJUNTOS</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="adjuntos" id="adjuntos">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display:flex;justify-content:space-evenly;">
                        <div style="display: flex;flex-direction: row;">
                            <p style="margin-right: 5px;">Solo correos sin Gestión</p>
                            <input type="checkbox" name="sinGestion" id="sinGestion">
                        </div>

                        <div style="display: flex;flex-direction: row;">
                            <p style="margin-right: 5px;">Solo correos Gestionados</p>
                            <input type="checkbox" name="conGestion" id="conGestion">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for=""> </label>
                                <button class="btn btn-primary form-control input-sm" id="searchMail">BUSCAR</button><span>Se mostrará un maximo de 20 correos</span>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="mails" style="width:90%;margin:auto"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function enviarMailDesdeBtn(mail, cuenta, formulario=null) {
        $("#modal_sendMail").modal('show');
        $("#para").val(mail);
        $("#cuentaMail").val(cuenta);
        $("formMail").val(formulario);
    }

    function enviarSmsDesdeBtn(destino, cuenta, campo = null, valorDefecto = null, prefijo) {
        $("#mensaje").val('');
        $("#destinatario").val('');
        $("#modal_SendSms").modal('show');
        $("#cuentaSms").val(cuenta);
        $("#destinatario").val(prefijo+destino);
        $("#prefijo").val(prefijo);
        $("#campoSms").val(valorDefecto);
        if (valorDefecto != null && valorDefecto != '' && valorDefecto != 0 && campo != null && campo != '' && campo != 0) {
            $.ajax({
                url: 'formularios/enviarSms_Mail_crud.php?textDefaultSms=si',
                type: 'POST',
                dataType: 'json',
                data: {
                    campo: campo,
                    valor: $("#" + valorDefecto).val()
                },
                cache: false,
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="assets/img/clock.gif" /> Espere por favor'
                    });
                },
                success: function(data) {
                    if (data.estado == 'ok') {
                        if (data.mensaje != null && data.mensaje != '' && data.mensaje != 0) {
                            $("#mensaje").val(data.mensaje);
                            $("#mensaje").html(data.mensaje);
                            $("#mensaje").attr('readonly', true);
                        } else {
                            $("#mensaje").attr('readonly', false);
                        }
                    } else {
                        alertify.error(data.mensaje);
                        $("#mensaje").attr('readonly', false);
                    }
                },
                error: function() {
                    alertify.error("Ocurrio un error al cargar el mensaje predefinido");
                },
                complete: function() {
                    $.unblockUI();
                }
            });
        } else {
            $("#mensaje").attr('readonly', false);
        }
        limiteCaracteres(cuenta);
    }

    function buscarMailDesdeBtn(cuenta,campo=null) {
        $("#modal_SearchMail").modal('show');
        $("#cuentaSearchMail").val(cuenta);
        $("#campoBuscar").val(campo);
    }

    function sendMail() {
        var form = new FormData($("#formMail")[0]);
        form.append('formulario',GetFormulario('formulario'));
        form.append('miembro', getMiembro());
        form.append('agente', GetAgente());
        $.ajax({
            url: 'formularios/enviarSms_Mail_crud.php?sendMail=si',
            type: 'POST',
            dataType: 'json',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="assets/img/clock.gif" /> Espere mientras se envia el correo',
                    css: {
                        'z-index': 10500
                    }
                });
            },
            success: function(data) {
                if (data.strEstado_t == 'ok') {
                    alertify.success('correo enviado exitosamente');
                    $("#modal_sendMail").modal('hide');
                    try{
                        afterSendMail(data.objSerializar_t);
                    }catch(e){
                        console.warn("la función afterSendMail no existe");
                    }
                } else {
                    alertify.error(data.strMensaje_t);
                }
            },
            error: function() {
                alertify.error("Ocurrio un error al enviar el correo");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    function sendSms() {
        var form = new FormData($("#formSms")[0]);
        form.append('agente', GetAgente());
        form.append('miembro', getMiembro());
        $.ajax({
            url: 'formularios/enviarSms_Mail_crud.php?sendSms=si',
            type: 'POST',
            dataType: 'json',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="assets/img/clock.gif" /> Espere mientras se envia el SMS'
                });
            },
            success: function(data) {
                if (data.strEstado_t == 'ok') {
                    alertify.success('SMS enviado exitosamente');
                    $("#modal_SendSms").modal('hide');
                    try{
                        afterSendSms(data.objSerializar_t);
                    }catch(e){
                        console.warn("la función afterSendSms no existe");
                    }
                } else {
                    alertify.error(data.strMensaje_t);
                }
            },
            error: function() {
                alertify.error("Ocurrio un error al enviar el SMS");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    function searchMail() {
        var form = new FormData($("#formBusqueda")[0]);
        form.append('formulario',GetFormulario('formulario'));
        form.append('agente',GetAgente());
        $.ajax({
            url: 'formularios/enviarSms_Mail_crud.php?searchMail=si',
            type: 'POST',
            dataType: 'json',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="assets/img/clock.gif" /> Espere mientras el sistema realiza la busqueda',
                    css: {
                        'z-index': 10500
                    }
                });
            },
            success: function(data) {
                if (data.estado == 'ok') {
                    $("#mails").html(data.mensaje);
                } else {
                    alertify.error(data.mensaje);
                }
            },
            error: function() {
                alertify.error("Ocurrio un error al realizar la busqueda");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }
    
    function saveGestionCorreo(id){
        var form = new FormData($("#formulario_"+id)[0]);
        form.append('formulario',GetFormulario('formulario'));
        form.append('base',GetFormulario('base'));
        form.append('muestra',GetFormulario('muestra'));
        form.append('strCampana',GetFormulario('nombre'));
        form.append('estpas',GetFormulario('estpas'));
        form.append('campan',GetFormulario('idcampan'));
        form.append('agente',GetAgente());
        form.append('fila',id);
        form.append('campoBuscar',$("#campoBuscar").val());
        $.ajax({
            url: 'formularios/enviarSms_Mail_crud.php?closeGestionCorreo=si',
            type: 'POST',
            dataType: 'json',
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="assets/img/clock.gif" /> Espere mientras el sistema cierra gestión',
                    css: {
                        'z-index': 10500
                    }
                });
            },
            success: function(data) {
                if(data.estado=='ok'){
                    alertify.success(data.mensaje);
                    $("#formulario_"+id).remove();
                }else{
                    alertify.error(data.mensaje);
                }
            },
            error: function() {
                alertify.error("Ocurrio un error al finalizar la gestión");
            },
            complete: function() {
                $.unblockUI();
            }
        });        
    }
    
    function GetFormulario(accion){
        var dato=false;
        <?php
            include(__DIR__."/../conexion.php");
            $formulario=false;
            $base=false;
            $muestrabd=false;
            $strCampana=false;
            $estpas=false;
            $idcampan=false;
            if(isset($_GET['formulario'])){
                $formulario=$_GET['formulario'];
                if(isset($_GET['id_campana_crm'])){
                    $sql=$mysqli->query("SELECT CAMPAN_Nombre____b,CAMPAN_ConsInte__GUION__Gui_b,CAMPAN_ConsInte__GUION__Pob_b,CAMPAN_ConsInte__MUESTR_b,ESTPAS_ConsInte__b FROM DYALOGOCRM_SISTEMA.CAMPAN JOIN DYALOGOCRM_SISTEMA.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b=CAMPAN_ConsInte__b WHERE CAMPAN_ConsInte__b={$_GET['id_campana_crm']}");
                    $sql=$sql->fetch_object();
                    $formulario=$sql->CAMPAN_ConsInte__GUION__Gui_b;
                    $base=$sql->CAMPAN_ConsInte__GUION__Pob_b;
                    $muestrabd=$sql->CAMPAN_ConsInte__MUESTR_b;
                    $strCampana=$sql->CAMPAN_Nombre____b;
                    $estpas=$sql->ESTPAS_ConsInte__b;
                    $idcampan=$_GET['id_campana_crm'];
                }
            }elseif(isset($_GET['id_campana_crm'])){
                $sql=$mysqli->query("SELECT CAMPAN_Nombre____b,CAMPAN_ConsInte__GUION__Gui_b,CAMPAN_ConsInte__GUION__Pob_b,CAMPAN_ConsInte__MUESTR_b,ESTPAS_ConsInte__b FROM DYALOGOCRM_SISTEMA.CAMPAN JOIN DYALOGOCRM_SISTEMA.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b=CAMPAN_ConsInte__b WHERE CAMPAN_ConsInte__b={$_GET['id_campana_crm']}");
                $sql=$sql->fetch_object();
                $formulario=$sql->CAMPAN_ConsInte__GUION__Gui_b;
                $base=$sql->CAMPAN_ConsInte__GUION__Pob_b;
                $muestrabd=$sql->CAMPAN_ConsInte__MUESTR_b;
                $strCampana=$sql->CAMPAN_Nombre____b;
                $estpas=$sql->ESTPAS_ConsInte__b;
                $idcampan=$_GET['id_campana_crm'];
            }elseif(isset($_GET['campana_crm'])){
                $sql=$mysqli->query("SELECT CAMPAN_Nombre____b,CAMPAN_ConsInte__GUION__Gui_b,CAMPAN_ConsInte__GUION__Pob_b,CAMPAN_ConsInte__MUESTR_b,ESTPAS_ConsInte__b FROM DYALOGOCRM_SISTEMA.CAMPAN JOIN DYALOGOCRM_SISTEMA.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b=CAMPAN_ConsInte__b WHERE CAMPAN_ConsInte__b={$_GET['campana_crm']}");
                $sql=$sql->fetch_object();
                $formulario=$sql->CAMPAN_ConsInte__GUION__Gui_b;
                $base=$sql->CAMPAN_ConsInte__GUION__Pob_b;
                $muestrabd=$sql->CAMPAN_ConsInte__MUESTR_b;
                $strCampana=$sql->CAMPAN_Nombre____b;
                $estpas=$sql->ESTPAS_ConsInte__b;
                $idcampan=$_GET['campana_crm'];
            }
        ?>
        switch(accion){
            case 'formulario':
                dato='<?=$formulario?>';
                break;
            case 'base':
                dato='<?=$base?>';
                break;
            case 'muestra':
                dato='<?=$muestrabd?>';
                break;
            case 'nombre':
                dato='<?=$strCampana?>';
                break;
            case 'estpas':
                dato='<?=$estpas?>';
                break;
            case 'idcampan':
                dato='<?=$idcampan?>';
                break;
            default:
                dato=false;
                break;
        }
        return dato;
    }

    function GetAgente(){
        <?php 
            $agente=isset($_GET['idUSUARI']) ? $_GET['idUSUARI'] :false;
            if(!$agente){
                $agente=isset($_GET['usuario']) ? $_GET['usuario'] :0;
            }
        ?>
        
        return '<?=$agente?>'
    }
    
    function getMiembro(){
        <?php 
            $miembro=isset($_GET['consinte']) ? $_GET['consinte'] :false;
            if(!$miembro){ 
        ?>
                return $("#hidId").val();
        <?php    
            }else{
                $miembro=isset($_GET['user']) ? $_GET['user'] : $miembro;
                $miembro=isset($_GET['idFather']) ? $_GET['idFather'] : $miembro;
        ?>
                return '<?=$miembro?>';    
        <?php 
            }
        ?>
        
    }
    
    function pintarCampoAdjunto() {
        var filaID = parseInt($("#countAdjuntos").val());
        //inicia el html de la nueva fila
        var htmlCampos ='<div class="row" id="fila_'+filaID+'"><div class="col-md-10"><div class="form-group"><input type="file" name="adjunto_'+filaID+'"></div></div><div class="col-md-2"><div class="form-group"><button type="button" class="btn btn-danger btn-sm btnEliminarAdjunto" idFila="' + filaID + '"><i class="fa fa-trash-o"></i></button></div></div></div>';

        $(".adjuntos").append(htmlCampos);
        filaID++;
        $("#countAdjuntos").val(filaID);


        //Funcion del boton de eliminar fila
        $(".btnEliminarAdjunto").click(function() {
            var id = $(this).attr('idFila');
            $("#fila_" + id).remove();
        });
    }

    function limiteCaracteres(cuenta){
        $.ajax({
            url: 'formularios/enviarSms_Mail_crud.php?getCaracteres=si',
            type: 'POST',
            dataType: 'json',
            data: {cuenta:cuenta},
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="assets/img/clock.gif" /> Espere mientras se procesa la información',
                    css: {
                        'z-index': 10500
                    }
                });
            },
            success: function(data) {
                if (data.estado == 'ok') {
                    $("#mensaje").attr('maxlength',data.mensaje);
                } else {
                    alertify.error(data.mensaje);
                }
            },
            error: function() {
                alertify.error("Ocurrio un error al obtener la longitud permitida del sms");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    $("#sendMail").on('click', function(e) {
        e.preventDefault();
        sendMail();
    });    
    
    $("#addAdjunto").on('click', function(e) {
        e.preventDefault();
        pintarCampoAdjunto();
    });

    $("#sendSms").on('click', function(e) {
        e.preventDefault();
        sendSms();
    });

    $("#searchMail").on('click', function(e) {
        e.preventDefault();
        searchMail();
    });

    $.fn.datepicker.dates['es'] = {
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

    $("#fecha_inicial").datepicker({
        language: "es",
        autoclose: true,
        todayHighlight: true,
        dateFormat: 'yyyy-mm-dd'
    });

    $("#fecha_final").datepicker({
        language: "es",
        autoclose: true,
        todayHighlight: true,
        dateFormat: 'yyyy-mm-dd'
    });

</script>
