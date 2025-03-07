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


<?php
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G27/G27_CRUD.php";

    function sanear_strings($string) { 

       // $string = utf8_decode($string);

        $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
        $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
        $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
        $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
        $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
        $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); 
        //Esta parte se encarga de eliminar cualquier caracter extraño 
        $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string ); 
        return $string; 
    }

    $opcionesPregun = '<option value="">Seleccione</option>';
    
    $strListaCampos_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS name FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET["poblacion"]." AND PREGUN_Tipo______b NOT IN (10,6,11,13,8,9,12) AND PREGUN_Texto_____b NOT LIKE '%_DY%'";
    $resListaCampos_t = $mysqli->query($strListaCampos_t);
    if($resListaCampos_t){
        while ($item = $resListaCampos_t->fetch_object()) {
            $opcionesPregun .= '<option value="'.$item->id.'">'.$item->name.'</option>';
        }
        $opcionesPregun .= '<option value="ConsInte">Registro_Id</option>';
        $opcionesPregun .= '<option value="LinkContenidoUG">Link_Contenido_Ultima_Gestion</option>';
        $opcionesPregun .= '<option value="LinkContenidoGMI">Link_Contenido_Gestion_Mas_Importante</option>';
        $opcionesPregun .= '<option value="ComentarioUG">Comentario_Ultima_Gestion</option>';
        $opcionesPregun .= '<option value="ComentarioGMI">Comentario_Gestión_Mas_Importante</option>';
    }
    
?>

<div class="box box-primary">
    <div class="box-header">
        <div class="box-tools">            
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12" id="div_formularios">
                <!-- Botones -->
                <div>
                    <button class="btn btn-default" id="Save">
                        <i class="fa fa-save"></i>
                    </button>

                    <button class="btn btn-default" id="cancel">
                        <i class="fa fa-close"></i>
                    </button>
                </div>

                <br/>

                <div class="callout callout-danger" id="seccion_error" style="display: none;">
                    <h4>
                        <i class="icon fa fa-warning"></i>
                        Paso desactivado debido a que se genero un error al realizar un envío desde este paso, realizar la corrección y reactivar el paso para que el paso pueda seguir enviando mensajes
                    </h4>
                    <p id="cuerpo_mensaje_error">
                    </p>
                </div>

                <!-- CUERPO DEL FORMULARIO CAMPOS-->
                <div>
                    <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                        <div class="row">
                            <div class="col-md-12">

                                <!-- Id paso -->
                                <input type="hidden" name="id_paso" id="id_paso" value="<?php if(isset($_GET['id_paso'])){ echo  $_GET['id_paso']; }else{ echo "0"; } ?>">
                                <input type="hidden" name="oper" id="oper" value='add'>
                                <input type="hidden" name="plantillaSaliente" id="plantillaSaliente" value="0">

                                <div class="row">
                                    <div class="col-md-11">
                                        <!-- CAMPO NOMBRE -->
                                        <div class="form-group">
                                            <label for="G14_C137"><?php echo $str_nombre_mail_ms; ?></label>
                                            <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="Saliente_whatsapp_<?php echo  $_GET['id_paso']; ?>" placeholder="<?php echo $str_nombre_mail_ms; ?>">
                                        </div>
                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="pasoActivo" id="LblpasoActivo">ACTIVO</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="-1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself" checked> 
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <!-- CAMPO CUENTA DE WHATSAPP **** AQUI DEBO CARGAR LAS CUENTAS DE WHATSAPP DE ESE HUESPED-->
                                        <div class="form-group">
                                            <label for="cuentawa">Cuenta de whatsapp a usar</label>
                                            <select class="form-control input-sm" style="width: 100%;" id="cuentawa" value=""  name="cuentawa">
                                                <option value="">Seleccione</option>
                                            </select>
                                        </div>
                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <!-- CAMPO TIPO *** NECESITO CARGAR TAMBIEN LAS PLANTILLAS EXISTENTES PARA ESE HUESPED -->
                                        <div class="form-group">
                                            <label for="plantilla">Plantillas</label>
                                            <select class="form-control input-sm" style="width: 100%;" id="plantilla" value=""  name="plantilla">
                                                <option data-contenido="Aqui estara el cuerpo de la plantilla" value="">Seleccione</option>
                                            </select>
                                        </div>
                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <!-- CAMPO CUENTA DE WHATSAPP **** AQUI DEBO CARGAR LAS CUENTAS DE WHATSAPP DE ESE HUESPED-->
                                        <div class="form-group">
                                            <label for="to">Campo de la base de datos que contiene el teléfono</label>
                                            <select class="form-control input-sm" style="width: 100%;" id="to" value=""  name="to">
                                                <option value="">Seleccione</option>
                                                <?php 
                                                    $strListaCampos_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS name FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET["poblacion"]." AND PREGUN_Tipo______b NOT IN (10,6,11,13,8,9,12) AND PREGUN_Texto_____b NOT LIKE '%_DY%'";

                                                    $resListaCampos_t = $mysqli->query($strListaCampos_t);
                                                    if($resListaCampos_t){
                                                        while ($jey = $resListaCampos_t->fetch_object()) {
                                                            echo "<option value='".$jey->id."'>".$jey->name."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <label>Texto de la plantilla</label>
                                        <textarea readonly class="form-control" name="textoPlantilla" id="textoPlantilla" cols="15" rows="5">
                                        </textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <!-- CAMPO TIPO *** DEPENDIENDO DE LA PLANTILLA DEBO MOSTRAR O TRAER LOS CAMPOS DE LAS VARIABLES DINAMICAS -->
                                        <table class="table" id="tablaCampos">
                                            <thead>
                                                <tr>
                                                    <th>Campo de la plantilla</th>
                                                    <th>Origen del dato</th>
                                                    <th>Campo de la base de datos</th>
                                                    <th>Valor estatico</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapsePrueba">Pruebas</a>
        </h4>
    </div>
    <div class="box-body">
        <div id="collapsePrueba" class="panel-collapse collapse">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Telefono de destino</label>
                        <input type="text" class="form-control input-sm" id="telefonoDestino" placeholder="XXXXXXXXXX">
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-info" onclick="ejecutarPrueba()">Ejecutar prueba</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseNotificaciones">Notificaciones</a>
        </h4>
    </div>
    <div class="box-body">
        <div id="collapseNotificaciones" class="panel-collapse collapse">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Correos a donde se enviara notificaciones en caso de fallo</label>
                        <input type="text" class="form-control input-sm" id="correosNotificacion">
                        <span>Separar correos por comas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="" href="#secReports" aria-expanded="false" class="collapsed">
                REPORTES
            </a>
        </h4>
    </div>
    <div id="secReports" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

        <div style="padding: 15px;">
            <div class="row">
                <div class="col-md-12">
                    <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=red&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(function(){

        $("#seccion_error").hide();
        
        $.ajax({
            url: '<?=$url_crud;?>?CallDatos=true',  
            type: 'POST',
            data: {huesped : <?php echo $_SESSION['HUESPED'];?>, id_paso : <?php echo $_GET['id_paso']; ?>},
            dataType : 'json',
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                console.log(data);

                // Al parecer esta reseteando los campos por eso hago esto
                let this_campos = data.campos;

                // Esto es para traer los canales que hay en el huesped
                if(data.canales){
                    data.canales.forEach(item => {
                        $("#cuentawa").append('<option value="'+item['id']+'">'+item['nombre']+' - '+item['cuenta']+'</option>');
                    });
                }

                // Aqui relleno los datos de la plantilla
                if(data.existe && data.plantillaSaliente){
                    let enp = data.plantillaSaliente;

                    $('#oper').val('edit');
                    $('#plantillaSaliente').val(enp.id);
                    $('#nombre').val(enp.nombre);
                    $('#cuentawa').val(enp.id_cuenta_whatsapp);
                    $('#to').val(enp.id_pregun_telefono);

                    if(data.paso){
                        if(data.paso['activo'] == -1){
                            $("#pasoActivo").prop("checked", true);
                        }else{
                            $("#pasoActivo").prop("checked", false);
                        }
                    }

                    // Mensaje de error
                    if(enp.mensajes_estado && enp.mensajes_estado != ''){
                        $("#cuerpo_mensaje_error").text(enp.mensajes_estado);
                        $("#seccion_error").show();
                    }

                    if(enp.correos_envio_mensaje){
                        $("#correosNotificacion").val(enp.correos_envio_mensaje);
                    }else{
                        $("#correosNotificacion").val('');
                    }

                    agregarPlantillas(data.plantillas);

                    let plantilla = data.plantillas.find(element => element.id == enp.id_wa_plantilla);

                    $("#textoPlantilla").html(plantilla.contenido);
                    $('#plantilla').val(enp.id_wa_plantilla);

                    // Muestro los campos
                    $("#tablaCampos tbody").html('');
                    if(data.campos){
                        var i = 0;
                        data.campos.forEach(item => {

                            let nombreParametro = item.nombre_variable;

                            // Validamos el nombre
                            switch (item.nombre_variable) {
                                case 'DY_IMAGE':
                                    nombreParametro = 'URL de la imagen de la plantilla';
                                    break;
                                case 'DY_VIDEO':
                                    nombreParametro = 'URL del video de la plantilla';
                                    break;
                                case 'DY_DOCUMENT':
                                    nombreParametro = 'URL del documento de la plantilla';
                                    break;
                                case 'DY_LOCATION':
                                    nombreParametro = 'Coordenadas latitud, longitud separados por coma, Ej:12.2345, -74.5456';
                                    break;
                            
                                default:
                                    break;
                            }

                            let estructura = '';
                            estructura += '<tr id="c_'+i+'">';
                            estructura += '<input type="hidden" name="campov['+i+'][hidId]" readonly value="'+item.id+'">';
                            estructura += '<input type="hidden" name="campov['+i+'][id]" readonly value="'+item.id_plantilla_saliente_variable+'">';
                            estructura += '<td><input type="text" readonly value="'+nombreParametro+'" class="form-control input-sm"></td>';
                            estructura += '<td>';
                            estructura += '<select name="campov['+i+'][accion]" id="accion_'+i+'" class="form-control input-sm" onchange="cambiarAccion('+i+')">';
                            estructura += '<option value="1">Campo de la base de datos</option>';
                            estructura += '<option value="2">Valor estatico</option>';
                            estructura += '</select>';
                            estructura += '</td>';
                            estructura += '<td>';
                            estructura += '<select name="campov['+i+'][pregun]" id="pregun_'+i+'" class="form-control input-sm">';
                            estructura += '<?php echo $opcionesPregun ?>';
                            estructura += '</select>';
                            estructura += '</td>';
                            estructura += '<td><input name="campov['+i+'][estatico]" id="estatico_'+i+'" type="text" value="" class="form-control input-sm"></td>';
                            estructura += '</tr>';
                            $("#tablaCampos tbody").append(estructura);
                            $("#accion_"+i).val(item.accion);
                            if(item.accion == 1)
                                $("#pregun_"+i).val(item.id_pregun);
                            else
                                $("#estatico_"+i).val(item.valor_estatico);

                            $("#accion_"+i).change();
                            i+=1;
                        });
                    }
                }
            },
            complete : function(){
                $.unblockUI();
            }
        });

        // Guarda el registro
        $("#Save").click(function(){

            let valido = true;

            if($("#nombre").val() == ''){
                valido = false;
                alertify.error('el campo nombre es obligatorio');
            }

            if($("#cuentawa").val() == ''){
                valido = false;
                alertify.error('Debe seleccionar una cuenta de whatsapp');
            }

            if($("#plantilla").val() == ''){
                valido = false;
                alertify.error('Debe seleccionar una plantilla');
            }

            if($("#to").val() == ''){
                valido = false;
                alertify.error('Debe seleccionar una a quien enviar el mensaje');
            }

            if(valido){
                
                let formData = new FormData($("#FormularioDatos")[0]);
                formData.append('correosNotificacion', $("#correosNotificacion").val());

                $.ajax({
                    url: '<?=$url_crud;?>?insertarDatos=true&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    },
                    success: function(data){
                        console.log(data);

                        // GENERAMOS LA VISTA DE LA MUESTRA
                        $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaMuestra=si',
                                type: 'POST',
                                data: { pasoId :  '<?php echo $_GET['id_paso']; ?>' },
                                dataType:'JSON'
                            });
                            
                        $("#FormularioDatos")[0].reset();
                        $("#editarDatos").modal('hide');
                        alertify.success('Datos guardados');
                    },
                    complete : function(){
                        $.unblockUI();
                    }
                });
            }
        });

        // Traigos las plantillas de la cuenta en especifico
        $("#cuentawa").change(function(){
            let cuenta = $("#cuentawa").val();

            $.ajax({
                url: '<?=$url_crud;?>?getPlantillas=true',  
                type: 'POST',
                data: {cuentawa : cuenta,},
                dataType : 'json',
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                success: function(data){
                    agregarPlantillas(data.plantillas);
                    $("#plantilla").change();
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });

        // Muestro el mensaje segun la plantilla
        $("#plantilla").change(function(){
            console.log($(this).find(':selected').data('contenido'));
            $("#textoPlantilla").html($(this).find(':selected').data('contenido'));

            // Traigo los campos de la plantilla
            $.ajax({
                url: '<?=$url_crud;?>?getCamposPlantilla=true',  
                type: 'POST',
                data: {idPlantilla : $("#plantilla").val()},
                dataType : 'json',
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                success: function(data){
                    console.log(data);
                    $("#tablaCampos tbody").html('');
                    if(data.campos){
                        var i = 0;
                        data.campos.forEach(item => {

                            let nombreParametro = item.nombre;
    
                            // Validamos el nombre
                            switch (item.nombre) {
                                case 'DY_IMAGE':
                                    nombreParametro = 'URL de la imagen de la plantilla';
                                    break;
                                case 'DY_VIDEO':
                                    nombreParametro = 'URL del video de la plantilla';
                                    break;
                                case 'DY_DOCUMENT':
                                    nombreParametro = 'URL del documento de la plantilla';
                                    break;
                                case 'DY_LOCATION':
                                    nombreParametro = 'Coordenadas longitud y latitud separados por coma, Ej: 12.2345, -74.5456';
                                    break;
                            
                                default:
                                    break;
                            }

                            let estructura = '';
                            estructura += '<tr id="c_'+i+'">';
                            estructura += '<input type="hidden" name="campov['+i+'][id]" readonly value="'+item.id+'">';
                            estructura += '<td><input type="text" readonly value="'+nombreParametro+'" class="form-control input-sm"></td>';
                            estructura += '<td>';
                            estructura += '<select name="campov['+i+'][accion]" id="accion_'+i+'" class="form-control input-sm" onchange="cambiarAccion('+i+')">';
                            estructura += '<option value="1">Campo de la base de datos</option>';
                            estructura += '<option value="2">Valor estatico</option>';
                            estructura += '</select>';
                            estructura += '</td>';
                            estructura += '<td>';
                            estructura += '<select name="campov['+i+'][pregun]" id="pregun_'+i+'" class="form-control input-sm">';
                            estructura += '<?php echo $opcionesPregun ?>';
                            estructura += '</select>';
                            estructura += '</td>';
                            estructura += '<td><input name="campov['+i+'][estatico]" id="estatico_'+i+'" type="text" value="" class="form-control input-sm"></td>';
                            estructura += '</tr>';
                            $("#tablaCampos tbody").append(estructura);
                            $("#accion_"+i).change();
                            i+=1;
                        });
                    }
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });

    });

    // Activo desactivo segun la accion
    function cambiarAccion(id){
        if($("#accion_"+id).val() == 1){
            $("#pregun_"+id).prop( "disabled", false );
            $("#estatico_"+id).prop( "disabled", true );
        }else{
            $("#pregun_"+id).prop( "disabled", true );
            $("#estatico_"+id).prop( "disabled", false );
        }
    }

    // Dibuja las opciones de la plantilla
    function agregarPlantillas(plantillas){
        $("#plantilla").html('<option data-contenido="Aqui estara el cuerpo de la plantilla" value="">Seleccione</option>');
        if(plantillas){
            plantillas.forEach(item => {
                $("#plantilla").append('<option data-contenido="'+item['contenido']+'" value="'+item['id']+'">'+item['nombre']+'</option>');
            });
        }
    }

    function ejecutarPrueba(){

        if($("#telefonoDestino").val() == ''){
            $("#telefonoDestino").focus();
            alertify.error("El campo telefono de destino no puede estar vacío");
            return;
        }

        guardarPasoEjecutarPrueba();
    }

    function guardarPasoEjecutarPrueba(){

        let bol_respuesta = true;

        if($("#nombre").val() == ''){
            bol_respuesta=false;
            alertify.error('el campo nombre es obligatorio');
        }

        if(bol_respuesta){

            let formData = new FormData($("#FormularioDatos")[0]);
            formData.append('correosNotificacion', $("#correosNotificacion").val());

            $.ajax({
                    url: '<?=$url_crud;?>?insertarDatos=true&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> Guardando configuracion del paso actual' });
                },
                //una vez finalizado correctamente
                success: function(data){
                    if(data){
                        
                        // Como permanecemos en la vista sea nuevo o viejo seteamos los valores como si fuera viejo
                        $("#oper").val('edit');
                        // TODO: MODIFICAR PARA QUE RECIBA UN JSON
                        $("#plantillaSaliente").val(data.id);

                        //Limpiar formulario
                        // form[0].reset();

                        enviarTest();
                    }else{
                        //Algo paso, hay un error
                        alertify.error('Un error ha ocurrido al guardar el formulario');
                        $.unblockUI();
                    }                
                },
                //si ha ocurrido un error
                error: function(){
                    after_save_error();
                    alertify.error('Ocurrio un error al momento de guardar el paso');
                    $.unblockUI();
                }
            });

        }

    }

    function enviarTest(){

        let telefono = $("#telefonoDestino").val();
        let plantillaId = $("#plantillaSaliente").val();
        let pob = '<?php echo $_GET['poblacion'] ?>';

        $.ajax({
            url: '<?=$url_crud;?>?realizarPruebaPaso=true',
            type: 'POST',
            dataType: 'JSON',
            data:{ telefono, plantillaId, pob },
            beforeSend: function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> Ejecutando prueba de envio de plantilla de whatsapp' });
            },
            success: function(data){
                $.unblockUI();

                let texto = '';
                let titulo = 'Prueba realizada';

                if(data.estado == 'ok'){
                    // si la ejecucion es correcta 
                    if(data.muestra.estado == 3){
    
                        texto = `
                            <p>Prueba ejecutada con exito</p>
                            <p>Estado del registro ${data.muestra.estado}</p>
                            <p>Fecha ${data.muestra.fechaGestion}</p>
                            <p>Respuesta ${data.muestra.comentario}</p>
                            <p>Destino ${data.muestra.datoContacto}</p>
                        `;
                        tipo = 'success';
                    }else{
    
                        if(data.estadoPaso == 0){
                            texto = '<p>No se realizo el envio debido a que el paso esta desactivado</p>';
                        }else{
                            texto = `
                                <p>Se presento un problema y no se pudo realizar el envio del sms</p>
                                <p>Respuesta de ejecucion ${data.respuesta}</p>
                                <p>Estado del registro ${data.muestra.estado}</p>
                            `;
                        }
                        tipo = 'error';
                    }
                }else{
                    texto = `
                            <p>Se presento un error durante la ejecucion de la prueba</p>
                            <p>Respuesta de ejecucion ${data.respuesta}</p>
                        `;
                    tipo = 'error';
                    titulo = 'Error de ejecución';
                }

                swal({
                    title : titulo,
                    text  : texto,
                    type  : tipo,
                    confirmButtonText : "Cerrar",
                    html: true
                });
            },
            error: function(){
                alertify.error('Ocurrio un error y no se pudo realizar la prueba de salida');
                $.unblockUI();
            }

        });

    }
</script>