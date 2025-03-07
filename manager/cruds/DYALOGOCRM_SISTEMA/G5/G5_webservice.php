<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    require_once('../../../../helpers/parameters.php');
    if(!isset($_SESSION['LOGIN_OK_MANAGER'])){
        header('Location:'.base_url.'login');
    }
    include ('../../../idioma.php');
    include ('../../../pages/conexion.php');
    require_once('../../../utils.php');

    $url_crud_extender = base_url."cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php";

    $_GET["llave"]=isset($_GET["llave"]) ? $_GET["llave"] : $_GET["estpas"];
    $estpas=isset( $_GET["estpas"]) ?  $_GET["estpas"] : 0;
    ?>
<!DOCTYPE html>
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?=base_url?>assets/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://interno.dyalogo.cloud/manager/assets/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?=base_url?>assets/css/alertify.core.css"/>
        <link rel="stylesheet" href="<?=base_url?>assets/css/alertify.default.css"/>
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/select2/select2.min.css" />
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/sweetalert/sweetalert.css"/>
        <script src="<?=base_url?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="https://kit.fontawesome.com/4207f392f9.js" crossorigin="anonymous"></script>
        <script src="<?=base_url?>assets/js/blockUi.js"></script>

        <style type="text/css">
            [class^='select2'] {
                border-radius: 0px !important;
            }
        </style>    
    </head>
    <body style="padding: 0px 30px;">
            <div class="modal fade" id="modaListas" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <button type="button" class="close limpiarSecciones" data-dismiss="modal" id="refrescarImagenes">&times;</button>
                            <h4 class="modal-title">Enlazar listas con WebService</h4>
                        </div>
                        <div class="modal-body">
                            <form action="#" id=formListas> 
                                <input type="hidden" name="iterListas" id="iterListas" value="0">
                                <input type="hidden" name="pregunID" id="pregunID" value="">
                                <div class="row camposG">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="camposG">CAMPO QUE CONTIENE EL CÓDIGO DE LA LISTA DEL WEB SERVICE</label>
                                            <select name="camposG" id="camposG" class="form-control input-sm" style="width: 100%;" disabled></select>
                                            <input type="hidden" name="operG" id="operG" value="add" disabled>
                                            <input type="hidden" name="padreG" id="padreG" value="" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="listas">

                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-primary btn" type="button" id="saveListWS">
                                Guardar
                            </button>
                            <button class="btn btn-default pull-right limpiarSecciones" type="button" data-dismiss="modal">
                                <?php echo $str_cancela;?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <form action="G5_webservice_CRUD.php?saveData" id="formWS" method="POST">
            <input type="hidden" name="campollave" id="campollave" value="<?=$_GET["llave"]?>">
            <input type="hidden" name="guion" id="guion" value="<?=$_GET["guion"]?>">    
            <input type="hidden" name="webservice" id="webservice" value="<?=$_GET["ws"]?>">    
            <input type="hidden" name="estpas" id="estpas" value="<?=$estpas?>">    

            <div class="row" id="seccionEnvioWS">
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#enlaceEnvioCamposWS">
                                Configurar parametros de envio
                            </a>
                        </h3>
                    </div>
                    <div id="enlaceEnvioCamposWS" class="panel-collapse collapse ">
                        <div class="box-body" id="aquiCamposEnvioWS">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PARAMETROS DEL WEBSERVICE</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tipo de valor</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>CAMPOS DEL FORMULARIO</label>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $sqlParams=$mysqli->query("SELECT * FROM dyalogo_general.ws_parametros where id_ws={$_GET['ws']}");
                                $sqlCampos=$mysqli->query("SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS texto, PREGUN_Tipo______b AS tipo, PREGUN_ConsInte__OPCION_B AS opcion, PREGUN_ConsInte__GUION__PRE_B AS tabla FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE SECCIO_ConsInte__GUION__b={$_GET['guion']} AND SECCIO_TipoSecc__b=1 AND PREGUN_Tipo______b !=12 AND PREGUN_Tipo______b !=9");

                                $option='';
                                $i=0;
                                While($campo = $sqlCampos->fetch_object()){
                                    if($campo->tipo=='11'){
                                        $lista=$campo->tabla;
                                    }else{
                                        $lista=$campo->opcion;
                                    }
                                    $option.= "<option value='{$campo->id}' tipo='{$campo->tipo}' opcion='{$lista}'>{$campo->texto}</option>";
                                }
                                while($param = $sqlParams->fetch_object()){
                                    if($param->sentido === 'IN'){
                                        echo '<div class="row">';
                                        echo '<div class="col-md-4">';
                                        echo '<div class="form-group">';
                                        echo "<input type='text' name='attr_{$i}' id='attr_{$param->id}' class='form-control input-sm' value='{$param->parametro}' disabled>";
                                        echo "<input type='hidden' name='attr_{$i}' id='attr_{$param->id}' class='form-control input-sm' value='{$param->id}'>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo '<div class="col-md-2">';
                                        echo '<div class="form-group">';
                                        echo "<select name='valor_{$i}' id='valor_{$param->id}' param='{$param->id}' class='form-control input-sm selectValor' style='width:100%'>";
                                        echo "<option value='1'>Dinamico</option><option value='2'>Estatico</option>";
                                        echo "</select>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo '<div class="col-md-5">';
                                        echo '<div class="form-group">';
                                        echo "<input type='text' name='valorFijo_{$i}' id='valorFijo_{$param->id}' class='form-control input-sm' style='display: none;' disabled>";
                                        echo "<input type='hidden' name='oper_{$i}' id='oper_{$param->id}' class='form-control input-sm' value='add'>";
                                        echo "<select name='select_{$i}' id='select_{$param->id}' param='{$param->id}' class='form-control input-sm select' style='width:100%'>";
                                        echo "<option value='0'>Seleccione</option>";
                                        echo $option;
                                        echo "</select>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo '<div class="col-md-1">';
                                        echo '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" param="'.$param->id.'" title="Opciones avanzadas" id="btnAvanzadosField_'.$param->id.'"><i class="fa fa-cog" aria-hidden="true"></i></button>';
                                        echo "</div>";
                                        echo "</div>";

                                        $i++;
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="seccionReturnWS">
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#enlaceReturnCamposWS">
                                Configurar parametros de retorno
                            </a>
                        </h3>
                    </div>
                    <div id="enlaceReturnCamposWS" class="panel-collapse collapse ">
                        <div class="box-body" id="aquiCamposReturnWS">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PARAMETROS DEL WEBSERVICE</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tipo de valor</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>CAMPOS DEL FORMULARIO</label>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $sqlParams=$mysqli->query("SELECT * FROM dyalogo_general.ws_parametros where id_ws={$_GET['ws']}");
                                while($param = $sqlParams->fetch_object()){
                                    if($param->sentido === 'OUT'){
                                        echo '<div class="row">';
                                        echo '<div class="col-md-4">';
                                        echo '<div class="form-group">';
                                        echo "<input type='text' name='attr_{$i}' id='attr_{$param->id}' class='form-control input-sm' value='{$param->parametro}' disabled>";
                                        echo "<input type='hidden' name='attr_{$i}' id='attr_{$param->id}' class='form-control input-sm' value='{$param->id}'>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo '<div class="col-md-2">';
                                        echo '<div class="form-group">';
                                        echo "<select name='valor_{$i}' id='valor_{$param->id}' param='{$param->id}' class='form-control input-sm selectValor' style='width:100%'>";
                                        echo "<option value='1'>Dinamico</option><option value='2'>Estatico</option>";
                                        echo "</select>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo '<div class="col-md-5">';
                                        echo '<div class="form-group">';
                                        echo "<input type='text' name='valorFijo_{$i}' id='valorFijo_{$param->id}' class='form-control input-sm' style='display: none;' disabled>";
                                        echo "<input type='hidden' name='oper_{$i}' id='oper_{$param->id}' class='form-control input-sm' value='add'>";
                                        echo "<select name='select_{$i}' id='select_{$param->id}' param='{$param->id}' class='form-control input-sm select' style='width:100%'>";
                                        echo "<option value='0'>Seleccione</option>";
                                        echo $option;
                                        echo "</select>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo '<div class="col-md-1">';
                                        echo '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" param="'.$param->id.'" title="Opciones avanzadas" id="btnAvanzadosField_'.$param->id.'"><i class="fa fa-cog" aria-hidden="true"></i></button>';
                                        echo "</div>";
                                        echo "</div>";
                                        $i++;
                                    }
                                }
                                echo "<input type='hidden' name='contador' id='contador' value='{$i}'>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>             
        </form>
    </body>
    <script src="<?=base_url?>assets/jqueryUI/jquery-ui.min.js"></script>
    <script src="<?=base_url?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=base_url?>assets/plugins/select2/select2.full.min.js"></script>
    <script src="<?=base_url?>assets/js/alertify.js"></script>
    <script src="<?=base_url?>assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        function llenarListas(tipo){
            let llave=$("#campollave").val();
            let estpas=$("#estpas").val();
            $.ajax({
                url: 'G5_webservice_CRUD.php?getListas',
                type: 'post',
                data: {llave: llave, estpas: estpas},
                dataType: 'json',
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                success: function(data) {
                    if(data.mensaje == 'ok'){
                        $.each(data.data, function(item, value){
                            if(tipo=='lisopc'){
                                $("#opcionesWS_"+value.lisopc).val(value.opcWS);
                                $("#oper_"+value.lisopc).val(value.id);
                            }else{
                                if(value.tablaG != null){
                                    $("#camposG").val(value.tablaG).trigger('change');
                                    $("#operG").val(value.id);
                                }
                            }
                        });
                    }else{
                        alertify.error(data.mensaje);
                    }
                },
                complete: function() {
                    $.unblockUI();
                },
                error:function(){
                    alertify.error("Ocurrio un error al procesar la solicitud");
                    $.unblockUI();
                }
            });    
        }

        $(function(){
            $(".select").select2();
            $("#camposG").select2();
            $(".btnAvanzadosField").attr('disabled',true);

            $(".select").change(function(){
                let param=$(this).attr('param');
                let tipo=$("#select_"+param+" :selected").attr('tipo');
                if(tipo == '6' || tipo == '11'){
                    $("#btnAvanzadosField_"+param).attr('disabled',false);
                }else{
                    $("#btnAvanzadosField_"+param).attr('disabled',true);
                }
            });

            $(".selectValor").change(function(){
                let param=$(this).attr('param');
                if($(this).val() == '1'){
                    $("#valorFijo_"+param).css('display','none');
                    $("#valorFijo_"+param).attr('disabled',true);
                    $("#select_"+param).css('display','inherit');
                    $("#select_"+param).attr('disabled',false);
                    $("#select_"+param).select2().next().show();
                }else{
                    $("#select_"+param).css('display','none');
                    $("#select_"+param).select2().next().hide();
                    $("#select_"+param).attr('disabled',true);
                    $("#valorFijo_"+param).css('display','inherit');
                    $("#valorFijo_"+param).attr('disabled',false);
                }
            });

            $(".btnAvanzadosField").click(function(){
                let param=$(this).attr('param');
                let opcion=$("#select_"+param+" :selected").attr('opcion');
                let tipo=$("#select_"+param+" :selected").attr('tipo');

                $("#pregunID").val($("#select_"+param).val());

                if(tipo=='6'){
                    $(".listas").css('display','inherit');
                    $(".camposG").css('display','none');
                    $("#camposG").attr('disabled',true);
                    $("#operG").attr('disabled',true);
                    $("#padreG").attr('disabled',true);
                    $.ajax({
                        url: '<?=$url_crud_extender?>',
                        type: 'post',
                        data: {getListasEdit: true,idOpcion: opcion},
                        dataType: 'json',
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        },
                        success: function(data) {
                            if (data.code == '1') {
                                $(".listas").empty();
                                $.each(data.lista, function(i, items) {
                                    var cuerpo = "<div class='row' id='id_" + i + "'>";
                                    cuerpo += "<div class='col-md-6'>";
                                    cuerpo += "<div class='form-group'>";
                                    cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>' readonly><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    cuerpo += "<div class='col-md-6'";
                                    cuerpo += "<div class='form-group'>";
                                    cuerpo += "<input type='text' name='opcionesWS_" + i + "' id='opcionesWS_" + items.LISOPC_ConsInte__b + "' class='form-control opcionesGeneradas' placeholder='Codigo de la lista del WS'>";
                                    cuerpo += "<input type='hidden' name='oper_" + i + "' id='oper_" + items.LISOPC_ConsInte__b + "' value='add'>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    $("#iterListas").val(Number($("#iterListas").val())+1);
                                    $(".listas").append(cuerpo);
                                });
                                llenarListas('lisopc');
                                $("#modaListas").modal();
                            }else{
                                alertify.error("Ocurrio un error al procesar la solicitud");
                            }
                        },
                        complete: function() {
                            $.unblockUI();
                        },
                        error:function(){
                            alertify.error("Ocurrio un error al procesar la solicitud");
                            $.unblockUI();
                        }
                    });
                }else{
                    $(".listas").css('display','none');
                    $(".camposG").css('display','inherit');
                    $("#camposG").attr('disabled',false);
                    $("#operG").attr('disabled',false);
                    $("#padreG").attr('disabled',false);
                    $("#padreG").val(opcion);
                    $.ajax({
                        url: '<?=$url_crud_extender?>?camposGuion=true',
                        type: 'post',
                        data: {guion: opcion, esMaster:'si'},
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        },
                        success: function(data) {
                            $("#camposG").html("<option value='0'>Seleccione</option>");
                            $("#camposG").append(data);
                            llenarListas('camposG');
                            $("#modaListas").modal();
                        },
                        complete: function() {
                            $.unblockUI();
                        },
                        error:function(){
                            alertify.error("Ocurrio un error al procesar la solicitud");
                            $.unblockUI();
                        }
                    });    
                }
            });

            $("#saveListWS").click(function(){
                var formData = new FormData($("#formListas")[0]);
                formData.append("campollave", $("#campollave").val());
                formData.append("webservice", $("#webservice").val());
                formData.append("estpas", $("#estpas").val());
                $.ajax({
                    url:'G5_webservice_CRUD.php?saveListas',
                    type:'post',
                    data:formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> PROCESANDO PETICION'
                        });
                    },
                    success:function(data){
                        if(data.mensaje=='ok'){
                            alertify.success('Enlaces guardados con exito');
                            $("#modaListas").modal('hide');
                        }else{
                            alertify.error(data.mensaje);
                        }
                    },
                    complete:function(){
                        $.unblockUI();
                    },
                    error:function(){
                        alertify.error("Ocurrio un error al procesar la solicitud");
                        $.unblockUI();
                    }
                });
            });

            $.ajax({
                url:'G5_webservice_CRUD.php?getData',
                type:"POST",
                data:{llave:<?=$_GET["llave"]?>, estpas:$("#estpas").val()},
                dataType:'json',
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                success:function(data){
                    $.each(data,function(item,value){
                        if($("#attr_"+value.CAMCONWS_ConsInte__ws_parametros__b)){
                            $("#valor_"+value.CAMCONWS_ConsInte__ws_parametros__b).val(value.CAMCONWS_TipValor_b).trigger('change');
                            $("#valor_"+value.CAMCONWS_ConsInte__ws_parametros__b).val(value.CAMCONWS_TipValor_b).trigger('change');
                            if(value.CAMCONWS_TipValor_b == '1'){
                                $("#select_"+value.CAMCONWS_ConsInte__ws_parametros__b).val(value.CAMCONWS_ConsInte__PREGUN__b).trigger('change');
                            }else{
                                $("#valorFijo_"+value.CAMCONWS_ConsInte__ws_parametros__b).val(value.CAMCONWS_Valor_b);
                            }
                            $("#oper_"+value.CAMCONWS_ConsInte__ws_parametros__b).val(value.CAMCONWS_ConsInte__b);
                        }
                    });
                },
                complete:function(){
                    $.unblockUI();
                }

            })
        });
    </script>
</html>     
