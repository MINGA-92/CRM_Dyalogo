<?php
    // G13 - CRUD Formulario WEB
    $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G13/G13_CRUD.php";
?>

<style type="text/css">
    .embed-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }
    .embed-container iframe {
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<div class="row" style="padding: 0px 15px;">
    <div class="col-md-12" id="div_formularios">

        <!-- Botones -->
        <div class="row">

            <button class="btn btn-default" id="Save">
                <i class="fa fa-save"></i>            
            </button>
            <button class="btn btn-default" id="cancel">
                <i class="fa fa-close"></i>
            </button>

        </div>

        <!-- Cuerpo del formulario -->
        <div class="row" style="margin-top: 25px;">
            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                
                <input type="hidden" name="wfPasoId" id="wfPasoId" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo '0'; } ?>">
                <input type="hidden" name="wfTipo" id="wfTipo" value="0">
                <input type="hidden" name="wfId" id="wfId" value='0'>
                <input type="hidden" name="wfOper" id="wfOper" value='add'>
                <input type="hidden" name="wfUrl" id="wfUrl" value="">
                <input type="hidden" name="wfWeb2" id="wfWeb2" value="">

                <div class="row">
                    <div class="col-md-12">
                    <div class="panel box box-primary camposDeEdicion">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="" href="#sec_1">
                                    Configuración
                                </a>
                            </h4>
                        </div>
                        <div id="sec_1" class="panel-collapse collapse in">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Nombre -->
                                    <div class="col-md-11"> 
                                        <div class="form-group">
                                            <label for=""><?php echo $str_Fw__nombre_B;?></label>
                                            <input type="text" class="form-control input-sm" id="wfNombre" name="wfNombre" placeholder="<?php echo $str_Fw__nombre_B;?>" required>
                                        </div>
                                    </div>

                                    <!-- Activo -->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="wfPasoActivo" id="LblpasoActivo">ACTIVO</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="-1" name="wfPasoActivo" id="wfPasoActivo" checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Base de datos -->
                                    <div class="col-md-12">
                                        <?php 
                                            $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 2 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
                                        ?>
                                        <div class="form-group">
                                            <label><?php echo $str_Fw__Base_D_B;?></label>
                                            <input type="hidden" name="wfPoblacion" id="wfPoblacion" value="<?php echo $_GET['poblacion']; ?>">
                                            <select class="form-control input-sm str_Select2" disabled="true" style="width: 100%;" name="wfBd" id="wfBd">
                                                <?php
                                                    // Se recorre la consulta del guion
                                                    $combo = $mysqli->query($str_Lsql);
                                                    while($obj = $combo->fetch_object()){
                                                        if($_GET['poblacion'] == $obj->id){
                                                            echo "<option selected value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G5_C28)."</option>";
                                                        }else{
                                                            echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G5_C28)."</option>";
                                                        }
                                                    }    
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Campo para validar registros repetidos -->
                                    <div class="col-md-12">
                                        <label for="">Campo para validar que no inserten registros repetidos</label>
                                        <select name="wfPregunValidacion" id="wfPregunValidacion" class="form-control input-sm">
                                            <option value="0">No validar</option>
                                            <?php
                                                $sSql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre, IF(GUION__ConsInte__PREGUN_Llave_b IS NOT NULL,TRUE,FALSE) AS llave FROM {$BaseDatos_systema}.PREGUN LEFT JOIN {$BaseDatos_systema}.GUION_ ON PREGUN_ConsInte__b=GUION__ConsInte__PREGUN_Llave_b WHERE PREGUN_ConsInte__GUION__b = ".$_GET['poblacion']." AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 14) AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY') ORDER BY PREGUN_Texto_____b ASC";
                                                $res = $mysqli->query($sSql);
                                                while($row = $res->fetch_object()){
                                                    $strLlave=$row->llave == 1 ? 'selected' : '';
                                                    echo "<option value='{$row->id}' {$strLlave}>{$row->nombre}</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Hoja de estilos -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="css">Adjuntar hoja de estilos (.css)</label>
                                            <input type="file" name="css" id="css" class="form-control" accept=".css">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <!-- Logo -->
                                    <label for="">Logo visible en el formulario web(Tamaño max:1MB)</label>
                                    <div class="box" style="padding: 15px;">
                                        <img src="<?=base_url?>assets/img/Kakashi.fw.png?foto=8859" alt="logo formweb" id="filePreview" class="profile-user-img img-responsive">
                                        <input type="file" name="logo_form" id="logoForm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel box box-primary camposDeEdicion">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="" href="#sec_2">
                                        Definir campos para el formulario web
                                    </a>
                                </h4>
                            </div>
                            <div id="sec_2" class="panel-collapse collapse in">

                                <div style="padding: 15px;">
                                    <!-- En esta seccion se encuentra el dragAndDrop -->
                                    <div class="form-group row" id="dragAndDrop">
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <input type="text" name="buscadorDisponible" id="buscadorDisponible" class="form-control">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-search"></i>
                                                </span>
                                            </div>
                                            <p class="text-center titulo-dragdrop">Campos no definidos</p>
                                            <ul id="disponible" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                            margin-bottom: 10px;
                                                            overflow: auto;   
                                                            -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
    
                                            </ul>
                                        </div>
                                        <div class="col-md-2 text-center" style="padding-top:100px">
                                            <button type="button" id="derecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                            <button type="button" id="todoDerecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br>
    
                                            <button type="button" id="izquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button><br>
                                            <button type="button" id="todoIzquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <input type="text" name="buscadorSeleccionado" id="buscadorSeleccionado" class="form-control">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-search"></i>
                                                </span>
                                            </div>
                                            <p class="text-center titulo-dragdrop">Campos que van a aparecer en el formulario web</p>
                                            <ul id="seleccionado" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                            margin-bottom: 10px;
                                                            overflow: auto;   
                                                            -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
    
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- Campo origen -->
                                            <div class="form-group">
                                                <label for="Web_Origen" id="LblWeb_Origen">En este campo ponga la palabra o frase con la que quiera identificar en su base de datos, los registros que entren a través de este formulario. Por ejemplo "PublicidadGoogle" o "FacebookNavidad"</label>
                                                <input type="text" class="form-control input-sm" id="Web_Origen" value="" name="Web_Origen" placeholder="Origen">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <!-- Link formulario web -->
                                            <div class="form-group">
                                                <strong>Formulario Web : </strong>
                                                <a id="urlWebForm" href="#" target="_blank"></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- Observaciones -->
                                            <div class="form-group">
                                                <label for="wfObservaciones"><?php echo $str_FW__Observ_B;?></label>
                                                <textarea class="form-control input-sm" id="wfObservaciones" name="wfObservaciones" value="" placeholder="<?php echo $str_FW__Observ_B;?>"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php if ($_GET["view"]  == "com_web_form"): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="" href="#sec_res_mail">
                                            Respuesta por correo electronico
                                        </a>
                                    </h4>
                                </div>
                                <div id="sec_res_mail" class="panel-collapse collapse in">

                                    <div style="padding: 15px;">
                                        <!-- En esta seccion se encuentra el dragAndDrop -->
                                        <div class="form-group row" id="dragAndDrop">
                                            <div class="col-md-6">
                                                <!-- Email para respuesta de webforms -->
                                                <label for="">Correo saliente para dar respuesta a los webform</label>
                                                <select name="wfMailSaliente" id="wfMailSaliente" class="form-control input-sm">
                                                    <option value="-1">Sin respuesta por mail</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Campo en el que se va a digilencia el correo del cliente</label>
                                                <select name="wfMailClient" id="wfMailClient" class="form-control input-sm" disabled>
                                                    <option value="0">Seleccione</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel box box-primary camposDeEdicion">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="" href="#sec_3">
                                        Google conversion
                                    </a>
                                </h4>
                            </div>
                            <div id="sec_3" class="panel-collapse collapse in">

                                <div style="padding: 15px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" id="div_opciones">
                                                <label for="selRedirecionFormWeb" >A continuacion, seleccion la opcion preferida para confirmarle a Google las inserciones exitosas.</label>

                                                <label id="labelGoogleADS" style="color:red" hidden>La opcion “Redirigir a URL propietario Analytics (por ejemplo, página de gracias)” no esta disponible, debido a que el Formulario Web ya se encuentra con una URL definida en el modulo Redirección.</label>
                                            </div>



                                            <select name="selRedirecionFormWeb" id="selRedirecionFormWeb" class="form-control input-sm">
                                                <option value="-1">Seleccione</option>
                                                <option value="false">Redirigir a URL propietario Analytics (por ejemplo, página de gracias)</option>
                                                <option value="true">Asignar código de propiedad de Google Analytics</option>
                                            </select><br>

                                            <div class="form-group" id="divCodigoGoogle" hidden>
                                                <label for="selRedirecionFormWeb" >Esta opción permitirá tener un registro de la totalidad de inserciones exitosas realizadas en el formulario web, si cuenta con un codigo de propiedad de Google Analytics, puede adicionarlo en el siguiente campo.</label>
                                                <input type="text" class="form-control input-sm" id="inpCodigoGoogle" name="inpCodigoGoogle" placeholder="ejemplo : UA-123456-1">
                                            </div>
                                            <div class="form-group" id="divLinkPage" hidden>
                                                <label for="selRedirecionFormWeb" >A continuación, inserte una URL que sera utilizada para ser redireccionada después de una inserción exitosa desde el formulario web, esto le permitirá tener un control de trafico en un su plataforma de análisis:</label><br>
                                                <input type="text" class="form-control input-sm" id="inpLinkPage" name="inpLinkPage" placeholder="URL">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel box box-primary camposDeEdicion">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="" href="#sec_4">
                                        Redirección
                                    </a>
                                </h4>
                            </div>
                            <div id="sec_4" class="panel-collapse collapse in">

                                <div style="padding: 15px;">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="form-group">

                                                <label id="labelRedireccion" style="color:red" hidden>Esta lista no esta disponible porque el Formulario Web ya tiene una URL para redireccionar definida en el modulo Google ADS.</label>

                                                <select name="selRedireccion" id="selRedireccion" class="form-control input-sm">
                                                    <option value="-1">Seleccione</option>
                                                    <option value="0">URL Externa</option>
                                                </select>

                                            </div>
                                            <div class="form-group">

                                                <input type="text" class="form-control input-sm" id="inpLinkRedireccion" name="inpLinkRedireccion" placeholder="URL">

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="" href="#sec_5" aria-expanded="false" class="collapsed">
                                        REPORTES
                                    </a>
                                </h4>
                            </div>
                            <div id="sec_5" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                                <div style="padding: 15px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                         <?php if ($_GET["view"]  == "com_web_form"): ?>

                                            <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=yellow&typeStep=comWebForm&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>

                                            <?php else: ?>

                                            <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=green&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

<script type="text/javascript" src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G13/G13_eventos.js"></script>
<script>

$(function(){
    
    $("#selRedirecionFormWeb").change(function(){
            
        $("#divLinkPage").hide();
        $("#divCodigoGoogle").hide();

        if($(this).val() == "true"){

            $("#divLinkPage").hide();
            $("#divCodigoGoogle").show();

        }else if($(this).val() == "false"){

            $("#divCodigoGoogle").hide();
            $("#divLinkPage").show();
        }
    });

    cargarDatos();

    // Inicializa el select2 en el select de la bd
    $("#wfBd").select2({
        templateResult: function(data) {
            var r = data.text.split('|');
            var $result = $(
                '<div class="row">' +

                '<div class="col-md-12">' + r[0] + '</div>' +
                '</div>'
            );
            return $result;
        },
        templateSelection: function(data) {
            var r = data.text.split('|');
            return r[0];
        }
    });

    // Realiza el cambio de opcion en el select2
    $("#wfBd").change(function() {
        var valores = $("#wfBd option:selected").text();
        var campos = $("#wfBd option:selected").attr("dinammicos");
        var r = valores.split('|');
        if (r.length > 1) {

            var c = campos.split('|');
            for (i = 1; i < r.length; i++) {
                if (!$("#" + c[i]).is("select")) {
                    // the input field is not a select
                    $("#" + c[i]).val(r[i]);
                } else {
                    var change = r[i].replace(' ', '');
                    $("#" + c[i]).val(change).change();
                }

            }
        }
    });


    <?php if ($_GET["view"]  == "com_web_form"): ?>
    // Se habilita el campo para seleccionar el campo en donde el cliente va a digilencia el correo para responde en caso que se permita las respuesta por mails
    $("#wfMailSaliente").change((e) => {
        if($("#wfMailSaliente").val() != '-1'){
            $("#wfMailClient").removeAttr("disabled");
            if($("#wfMailClient").val() == 0){
                let optionTypeMail = $("#wfMailClient").find(`option[data-type="14"]`);
                if(optionTypeMail.length > 0){
                    $("#wfMailClient").val(optionTypeMail.val()).trigger('change');
                }
            }
        }else{
            $("#wfMailClient").attr("disabled","true");

        }
    })

    <?php endif; ?>

    // Esto es si cambian el nombre quede actualizado la url sin guardar
    $("#Web_Origen").on('keyup paste', function() {
        generarUrl($(this).val());
    });

    // Evento del boton de guardado
    $("#Save").click(function(){
        guardarDatos();
    });

});



function changeSelRedireccion(){

    $("#selRedireccion").change(function(){

        $("#inpLinkRedireccion").val("");

        var tipo = $("#selRedireccion option:selected").attr("tipo");
        var valor = $("#selRedireccion option:selected").attr("valor");

        if (tipo == "14") {

            $("#inpLinkRedireccion").val("<?php echo $url_usuarios; ?>dyalogocbx/customers/dy/chat/"+valor);                                    

        }else if (tipo == "15") {

            $("#inpLinkRedireccion").val("https://api.whatsapp.com/send?phone="+valor);                                    

        }

    });

}

function labelModulos(modulo){

    $("#labelGoogleADS").hide();
    $("#labelRedireccion").hide();
    $("#selRedirecionFormWeb option[value=false]").attr("disabled",false);
    $("#selRedireccion").attr("readonly",false);
    $("#inpLinkRedireccion").attr("readonly",false);

    if (modulo == 1) {

        $("#selRedirecionFormWeb option[value=false]").attr("disabled",true);
        $("#labelGoogleADS").show();

    }else if (modulo == 2) {

        $("#labelRedireccion").show();
        $("#selRedireccion").val("-1").trigger("change");
        $("#selRedireccion").attr("disabled",true);
        $("#inpLinkRedireccion").attr("disabled",true);

    }

}

// Esta funcion se encarga de cargar los datos cuando se habre la modal del bot
function cargarDatos(){

    var origen = null;

    $.ajax({
        url: '<?=$url_crud;?>',
        type: 'POST',
        data: {
            <?php if($_GET["view"] == "com_web_form") echo "com_web_form: 'si'," ?>
            obtenerDatos: true,
            pasoId: '<?php echo $_GET['id_paso']; ?>',
            poblacion: <?=$_GET["poblacion"]?>

        },
        dataType: 'json',
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){
            
            // Esto lo ejecuta Juan David, pero no sabria cual es la funcion correcta de este codigo
            $("#selRedirecionFormWeb").change(function(){
            
                $("#divLinkPage").hide();
                $("#divCodigoGoogle").hide();

                if($(this).val() == "true"){

                    $("#divLinkPage").hide();
                    $("#divCodigoGoogle").show();

                }else if($(this).val() == "false"){

                    $("#divCodigoGoogle").hide();
                    $("#divLinkPage").show();

                }
            });

            // Se adiciona los mails disponibles
            <?php if($_GET["view"] == "com_web_form"): ?>

            $('#wfMailSaliente').append(data.listMails);
            $("#wfMailClient").append(data.listaCamposMail);

            <?php endif; ?>

            // Valido que traiga datos
            if(data.existe && data.datos){

                $("#wfWeb2").val(data.web2);

                $("#wfObservaciones").val(data.datos.observaciones);

                // Si esta el paso activo, active el check o desactivelo
                if (data.datos.activo != '-1') {
                    $('#wfPasoActivo').prop('checked', false);
                }else{
                    $('#wfPasoActivo').prop('checked', true);
                }

                $("#wfNombre").val(data.datos.nombre);

                $("#wfTipo").val(data.datos.tipo);


                $("#divLinkPage").hide();
                $("#divCodigoGoogle").hide();

                if (data.datos.tipo_redireccion == 1) {

                    $("#selRedirecionFormWeb").val("true").trigger("change");
                    $("#inpCodigoGoogle").val(data.datos.codigo_propiedad);
                }else if(data.datos.tipo_redireccion == 0){

                    labelModulos(2);

                    $("#selRedirecionFormWeb").val("false").trigger("change");
                    $("#inpLinkPage").val(data.datos.url_analytics);

                }

                $("#wfId").val(data.datos.id);

                // Si el logo existe muestrelo de lo contrario muestre un logo por defecto
                if (data.datos.logo != '') {
                    $('#filePreview').attr("src", data.datos.logo);
                } else {
                    $('#filePreview').attr("src", "<?=base_url?>assets/img/Kakashi.fw.png?foto=8859");
                }

                $("#Web_Origen").val(data.datos.origen);
                if(data.datos.pregun_validacion != 0){
                    $("#wfPregunValidacion").val(data.datos.pregun_validacion);
                }

                $("#wfUrl").val(data.datos.url);

                $('#disponible').html(data.listaCamposN);
                $('#seleccionado').html(data.listaCamposA);

                $('#wfMailSaliente').val(data.datos.wfMailSaliente).trigger('change');

                if (data.datos.wfMailClient != null) {
                    $('#wfMailClient').val(data.datos.wfMailClient).trigger('change');
                }
                if (data.datos.origen != null && data.datos.origen != '') {
                    origen = data.datos.origen;
                }

                // miramos si hay registro de css
                if(data.datos.css){
                    let arrNombre = data.datos.css.split('/');
                    let tamano = arrNombre.length;
                    $("#css").after(`<p class="help-block">Estilos css cargados actualmente <a href="/manager/download_file.php?file=${data.datos.css}&type=webform" target="_blank">${arrNombre[tamano-1]}</a> </p`);
                }

                // Como hay datos se setea el operador como edit
                $("#wfOper").val('edit');

            }else{
                // Si no trae nada significa que es un registro nuevo
                $("#wfNombre").val("WebForm_" + <?php echo $_GET['id_paso']; ?>);
                $("#oper").val('add');
                $('#disponible').html(data.listaCamposN);
                $('#seleccionado').html(data.listaCamposA);
            }

            generarUrl(origen);



            $.ajax({

                    url: '<?php echo $url_crud;?>?Redireccion=si',
                    type:'POST',
                    data:{intIdPaso_t : <?php echo $_GET['id_paso']; ?>},
                    dataType : "JSON",
                    global:false,
                    success:function(data2){

                        $("#selRedireccion").html('<option value="-1">Seleccione</option>');

                        $.each(data2, function(index,value) {

                            if (value.tipo == "14") {

                                if (value.id_chat != null) {

                                    $("#selRedireccion").append('<option tipo="14" valor="'+value.id_chat+'" value="'+value.id+'">'+value.nombre+'</option>');

                                } 


                            }else if (value.tipo == "15") {

                                if (value.numero_autorizado != null) {

                                    $("#selRedireccion").append('<option tipo="15" valor="'+value.numero_autorizado+'" value="'+value.id+'">'+value.nombre+'</option>');

                                } 


                            }

                        });

                        $("#selRedireccion").append('<option value="0">URL Externa</option>');

                        changeSelRedireccion();

                        if (data.datos.selRedireccion != null && data.datos.selRedireccion > -1) {

                            labelModulos(1);

                        }

                        $("#selRedireccion").val(data.datos.selRedireccion).trigger("change");
                        $("#inpLinkRedireccion").val(data.datos.inpLinkRedireccion);

                    }
            });
        },
        complete : function(){
            $.unblockUI();
        },
        error: function(){
            $.unblockUI();
            alertify.error('Se ha presentado un error al cargar la informacion');
        }
    });

}

// Se ejecuta cuando se guarde el formulario
function guardarDatos(){

    let valido = true;

    // Valido que el nombre no este vacio
    if ($("#wfNombre").val() == '') {
        valido = false;
        $("#wfNombre").focus();
        alertify.error('el campo nombre es obligatorio');
    }

    // Valido que no este vacio
    if ($("#selRedirecionFormWeb").val() == "false") {
        if ($("#inpLinkPage").val() == "") {
            bol_respuesta = false;
            alertify.error('Debe ingresar una URL.');
        }
    }

    // Si se seleccion un correo saliente para dar respuesta, se valida que wfMailClient  se encuentra seleecionado
    if ($("#wfMailSaliente").val() != "-1") {
        if ($("#wfMailClient").val() == "0") {
            valido = false;
            alertify.error('Debe seleccionar un campo en que el usuario diligencie su correo.');
        }
    }




    if(valido){

        // Mostramos la alerta si el webform con anterioridad estaba usando la version 1
        if($("#wfUrl").val() !== 'web2'){
            swal({
                title : "",
                text  : "La URL cambio, por favor actualícela en los sitios donde la esté usando. De no hacerlo seguirá viendo la versión anterior del formulario",
                type  : "warning",
                confirmButtonText : "Cerrar"
            });
        }

        //Se crean un array con los datos a enviar, apartir del formulario 
        let form = $("#FormularioDatos");
        let formData = new FormData($("#FormularioDatos")[0]);

        // Obtenemos el orden de los seleccionados
        let ordenCamposSeleccionados = '';

        $("#seleccionado li").each(function(){
            if(ordenCamposSeleccionados != ''){
                ordenCamposSeleccionados += ',';
            }
            ordenCamposSeleccionados += $(this).attr('data-id');
        });

        formData.append('ordenCampos', ordenCamposSeleccionados);

        $.ajax({
            url: '<?=$url_crud;?>?insertarDatos=true',
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data) {
                
                // Valido si los campos se guardaron correctamente
                if(data.valido){

                    let esValido = true;

                    if(data.respuestaCss.length > 0){
                        if(data.respuestaCss[0] == false){
                            esValido = false;
                            alertify.error(data.respuestaCss[1]);
                        }else{
                            alertify.success(data.respuestaCss[1]);
                        }
                    }

                    if(data.respuestaLogo.length > 0){
                        if(data.respuestaLogo[0] == false){
                            esValido = false;
                            alertify.error(data.respuestaLogo[1]);
                        }else{
                            alertify.success(data.respuestaLogo[1]);
                        }
                    }

                    if(esValido){
                        form[0].reset();
                        alertify.success("Se ha guardado correctamente el registro");
                        $("#pasoscortos").modal('hide');
                    }
                }else{
                    alertify.error("Se ha presentado un error al guardar el registro");
                }
            },
                complete : function(){
                $.unblockUI();
            },
            error: function(){
                $.unblockUI();
                alertify.error('Se ha presentado un error al guardar la informacion');
            }
        });
    }

}

</script>

<!-- Script para manejar los eventos del drag & drog -->
<script>
    // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
    function moverUsuarios(arrUsuarios, accion) {

        <?php if($_GET["view"] == "com_web_form"): ?>
        moverCamposMail(arrUsuarios, accion);
        <?php endif; ?>

        let webformId = $("#wfId").val();

        let ruta = '';
        if (accion == 'derecha') {
            ruta = "agregarCamposWeb=true";
        } else if (accion = 'izquerda') {
            ruta = "quitarCamposWeb=true";
        }

        $.ajax({
            url: '<?=$url_crud?>?' + ruta,
            type: 'POST',
            dataType: 'json',
            data: {
                arrCampos: arrUsuarios, webformId: webformId
            },
            success: function(response) {
                alertify.success("Mensaje: " + response.estado);
            },
            error: function(response) {
                console.log(response);
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }


    function moverCamposMail(arrUsuarios, accion){

        
        if (accion == 'derecha') {
            let newOptions = "";
            for (let i = 0; i < arrUsuarios.length ; i++){
                let campo = $(`#seleccionado li[data-id="${arrUsuarios[i]}"]`);
                let tipo = campo.attr("data-type");
                let nombre = campo.find(".nombre").html();

                if (tipo == 1 || tipo == 2 || tipo == 14){
                    newOptions += `<option value="${arrUsuarios[i]}" data-type="${tipo}">${nombre}</option>` ;
                }


            }

            $("#wfMailClient").append(newOptions);

        } else if (accion = 'izquerda') {
            for (let i = 0; i < arrUsuarios.length ; i++){
                
                $("#wfMailClient").find(`option[value="${arrUsuarios[i]}"]`).remove();
            }
        }

        
    }

    // En esta funcion se encontrara el buscador que filtrara por el nombre 
    $('#buscadorDisponible, #buscadorSeleccionado').keyup(function() {
        var tipoBuscador = $(this).attr('id');
        var nombres = '';

        if (tipoBuscador == 'buscadorDisponible') {
            nombres = $('ul#disponible .nombre');
        } else if (tipoBuscador == 'buscadorSeleccionado') {
            nombres = $('ul#seleccionado .nombre');
        }

        var buscando = $(this).val();
        var item = '';

        for (let i = 0; i < nombres.length; i++) {
            item = $(nombres[i]).html().toLowerCase();

            for (let x = 0; x < item.length; x++) {
                if (buscando.length == 0 || item.indexOf(buscando) > -1) {
                    $(nombres[i]).closest('li').show();
                } else {
                    $(nombres[i]).closest('li').hide();

                }
            }

        }
    });
    /** Estas funciones se encargan del funcionamiento del drag & drop */
    $("#disponible").sortable({
        connectWith: "#seleccionado"
    });
    $("#seleccionado").sortable({
        connectWith: "#disponible"
    });

    // Capturo el li cuando es puesto en la lista de usuarios disponible            
    $("#disponible").on("sortreceive", function(event, ui) {
        let arrDisponible = [];
        arrDisponible[0] = ui.item.data("id");

        moverUsuarios(arrDisponible, "izquierda");
    });

    // Capturo el li cuando es puesto en la lista de usuarios seleccionados         
    $("#seleccionado").on("sortreceive", function(event, ui) {
        let arrSeleccionado = [];
        arrSeleccionado[0] = ui.item.data("id");

        moverUsuarios(arrSeleccionado, "derecha");
    });

    // Solo se selecciona el check cuando se clickea el li
    $("#disponible, #seleccionado").on('click', 'li', function() {
        $(this).find(".mi-check").iCheck('toggle');

        if ($(this).find(".mi-check").is(':checked')) {
            $(this).addClass('seleccionado');
        } else {
            $(this).removeClass('seleccionado');
        }

    });

    $("#disponible, #seleccionado").on('ifToggled', 'input', function(event) {
        if ($(this).is(':checked')) {
            $(this).closest('li').addClass('seleccionado');
        } else {
            $(this).closest('li').removeClass('seleccionado');
        }
    });

    // Envia los elementos seleccionados a la lista de la derecha
    $('#derecha').click(function() {
        var obj = $("#disponible .seleccionado");
        $('#seleccionado').append(obj);

        let arrSeleccionado = [];
        obj.each(function(key, value) {
            arrSeleccionado[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrSeleccionado.length > 0) {
            moverUsuarios(arrSeleccionado, "derecha");
        }

    });

    // Envia los elementos seleccionados a la lista de la izquerda
    $('#izquierda').click(function() {
        var obj = $("#seleccionado .seleccionado");
        $('#disponible').append(obj);

        let arrDisponible = [];
        obj.each(function(key, value) {
            arrDisponible[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrDisponible.length > 0) {
            moverUsuarios(arrDisponible, "izquierda");
        }
    });

    // Envia todos los elementos a la derecha
    $('#todoDerecha').click(function() {
        var obj = $("#disponible li");
        $('#seleccionado').append(obj);

        let arrSeleccionado = [];
        obj.each(function(key, value) {
            arrSeleccionado[key] = $(value).data("id");
        });

        moverUsuarios(arrSeleccionado, "derecha");
    });

    // Envia todos los elementos a la izquerda
    $('#todoIzquierda').click(function() {
        var obj = $("#seleccionado li");
        $('#disponible').append(obj);

        let arrDisponible = [];
        obj.each(function(key, value) {
            arrDisponible[key] = $(value).data("id");
        });

        moverUsuarios(arrDisponible, "izquierda");
    });

</script>

<script>
    // Este script me permite previsualizar el logo o una imagen
    var fileUpload = document.getElementById('logoForm');
    fileUpload.onchange = function(e) {
        readFile(e.srcElement);
    }

    // Esta funcion se encarga de validar la imagen y precargarla en la vista
    function readFile(input) {
        if (input.files && input.files[0]) {

            var uploadFile = input.files[0];
            if (!(/\.(jpg|png|gif)$/i).test(uploadFile.name)) { // Valida si lo que se esta subiendo es valido
                document.getElementById('logoForm').value = '';
                alertify.error('Este archivo no es una imagen ');
            } else {
                console.log(uploadFile.size);
                var maxImage = 1 * 1024 * 1024;
                if (uploadFile.size > maxImage) { // Se valida que no exceda el tamano maximo
                    document.getElementById('logoForm').value = '';
                    alertify.error('La imagen no puede pesar más de 1MB');
                } else {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var myFilePreview = document.getElementById('filePreview');
                        myFilePreview.src = e.target.result; // Cargamos la imagen en la preview
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
    }

    // Esta funcion se encarga de generar la url
    function generarUrl(origen = null) {
        
        let strOrigen = $("#wfNombre").val();
        let web2 = $("#wfWeb2").val();

        if (origen != null && origen != '') {
            strOrigen = origen;
        }
        strOrigen = strOrigen.replace(/ /g, "_");

        let url = '<?php echo "https://{$_SERVER["HTTP_HOST"]}/crm_php/web_forms.php?web2=" ?>' + web2 + '<?php echo "&paso={$_GET["id_paso"]}&origen=WF_" ?>' + strOrigen;

        $("#urlWebForm").attr("href", url);
        $("#urlWebForm").text(url);
    }

</script>
