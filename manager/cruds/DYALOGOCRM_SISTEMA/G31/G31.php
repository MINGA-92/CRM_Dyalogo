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
    .lista table{
        margin: 0;
    }
    hr{
        width: 90%
    }
</style>

<?php
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G31/G31_CRUD.php";

   $sql = $mysqli->query("select ESTPAS_ConsInte__ESTRAT_b AS estrat FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b ={$_GET['id_paso']}");

if($sql && $sql->num_rows == '1'){

    $sql=$sql->fetch_object();
    $estrat=$sql->estrat;

    $huesped=$mysqli->query("SELECT ESTRAT_ConsInte__PROYEC_b AS huesped FROM {$BaseDatos_systema}.ESTRAT WHERE ESTRAT_ConsInte__b = {$estrat}");

    if($huesped && $huesped->num_rows == '1'){
        $huesped=$huesped->fetch_object();
        $huesped=$huesped->huesped;
        
        // Llenamos las opciones de campaña
        $opcionesCampana = '<option value="">Seleccione</option>';
        $campanas = $mysqli->query("SELECT ESTPAS_ConsInte__CAMPAN_b AS id_campan ,B.CAMPAN_IdCamCbx__b AS dy_campan, ESTPAS_Comentari_b AS nombre_campan FROM DYALOGOCRM_SISTEMA.ESTPAS LEFT JOIN DYALOGOCRM_SISTEMA.CAMPAN B ON ESTPAS_ConsInte__CAMPAN_b=CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b={$estrat} AND ESTPAS_Tipo______b =1 AND ESTPAS_ConsInte__CAMPAN_b IS NOT NULL");
        
        while($campan = $campanas->fetch_object()){
            $opcionesCampana .= '<option value="'.$campan->dy_campan.'">'.sanear_strings($campan->nombre_campan).'</option>';
        }
                    
        // Llenamos las opciones de bots
        $opcionesBot = '<option value="">Seleccione</option>';
        $bots = $mysqli->query("SELECT id, nombre FROM DYALOGOCRM_SISTEMA.ESTPAS JOIN dyalogo_canales_electronicos.dy_base_autorespuestas ON ESTPAS_ConsInte__b = id_estpas WHERE ESTPAS_Tipo______b = 12 AND ESTPAS_ConsInte__ESTRAT_b = {$estrat} AND ESTPAS_Comentari_b IS NOT NULL");
        
        while($bot =$bots->fetch_object()){
            $opcionesBot .= '<option value="'.$bot->id.'">'.sanear_strings($bot->nombre).'</option>';
        }
    }    
}

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
    
?>

<div class="box box-primary">
    <div class="box-header">
        <div class="box-tools"></div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12" id="div_formularios">
                <div>
                    <button class="btn btn-default" id="save">
                        <i class="fa fa-save"></i>
                    </button>

                    <button class="btn btn-default" id="cancel">
                        <i class="fa fa-close"></i>
                    </button>
                </div>

                <br/>
                <div>
                    <form action="#" id="FormularioDatos" enctype="multipart/form-data" method="post">
                        
                        <input type="hidden" name="id_paso" id="id_paso" value="<?php if(isset($_GET['id_paso'])){ echo  $_GET['id_paso']; }else{ echo "0"; } ?>">
                        <input type="hidden" name="oper" id="oper" value='add'>
                        <input type="hidden" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'] ;?>">
                        <input type="hidden" name="configuracionId" id="configuracionId" value="0">

                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="nombre"><?php echo $str_nombre_mail_ms; ?></label>
                                    <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="Correo_entrante_<?php echo $_GET['id_paso']; ?>" placeholder="<?php echo $str_nombre_mail_ms; ?>">
                                </div>
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
                            <div class="col-md-12">
                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#emailConfiguracion">
                                                Configuración
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="emailConfiguracion" class="panel-collapse collapse in">
                                        <div class="box-body">
                                            
                                            <div class="row">

                                                <!-- Campos -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for=""><?php echo $title_mail_cuenta;?></label>
                                                        <select name="mailCuentaCorreo" id="mailCuentaCorreo" class="form-control input-sm str_Select2" style="width: 100%;">
                                                            <option value="0" selected>Seleccione</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Campo correo para buscar automáticamente</label>
                                                        <select name="mailCampoBusqueda" id="mailCampoBusqueda" class="form-control input-sm">
                                                            <option value="0" selected>Seleccione</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="configMensajeBienvenida" id="configMensajeBienvenida" value="-1" onchange="configurarMensajeBienvenida(this)">
                                                                Configurar mensaje de bienvenida
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div id="seccionMensajeBienvenida" class="col-md-12" style="display: none;">
                                                    <div class="col-md-9">
                                                        <textarea name="correoMensajeBienvenida" id="correoMensajeBienvenida" cols="30" rows="10"></textarea>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <table class="table-bordered table-hover table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Variables dinámicas</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>${fecha}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>${hora}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>${id_comunicacion}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="row" style="margin-top:15px">
                        <div class="col-md-12">
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
                                                <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=yellow&typeStep=comMail&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(function(){
        cargarDatos();

        // Aqui guardo el registro
        $("#save").click(function(){
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }

            let valido = true;

            if($("#nombre").val() == ''){
                valido = false;
                alertify.error('el campo nombre es obligatorio');
            }

            if(valido){

                let formData = new FormData($("#FormularioDatos")[0]);

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
                        $("#FormularioDatos")[0].reset();
                        $("#editarDatos").modal('hide');
                        alertify.success('Datos guardados');
                        location.reload();
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    error: function(data){
                        if(data.responseText){
                            alertify.error("Hubo un error al guardar la información" + data.responseText);
                        }else{
                            alertify.error("Hubo un error al guardar la información");
                        }
                    }
                });
            }
        });
        
    });

    function cargarDatos(){

        $.ajax({
            url: '<?=$url_crud;?>?getDatos=true',
            type: 'POST',
            data: {huesped : <?php echo $_SESSION['HUESPED'];?>, pasoId : <?php echo $_GET['id_paso']; ?>, bd: <?=$_GET['poblacion']?>},
            dataType : 'json',
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                console.log(data);

                if(data.datosPaso){

                    if(data.datosPaso.nombre){
                        $("#nombre").val(data.datosPaso.nombre);
                    }

                    if(data.datosPaso.activo == '-1'){
                        if(!$("#pasoActivo").is(':checked')){
                            $("#pasoActivo").prop('checked', true);  
                        }
                    } else {
                        if($("#pasoActivo").is(':checked')){
                            $("#pasoActivo").prop('checked', false);  
                        }
                    }
                }

                //Opciones Correo
                if(data.opcionesCorreo){
                    let correos = data.opcionesCorreo;

                    for (const key in correos) {
                        let opcion = `<option value="${key}">${correos[key]}</option>`;
                        $("#mailCuentaCorreo").append(opcion);
                    }
                }

                // Opciones campos bd
                if(data.camposBd){
                    let campos = data.camposBd;

                    for(const key in campos){
                        let opcion = `<option value="${key}">${campos[key]}</option>`;
                        $("#mailCampoBusqueda").append(opcion);
                    }
                }

                // Datos de la configuracion
                if(data.correoConf){

                    let correoConf = data.correoConf;

                    $("#oper").val("edit");
                    $("#configuracionId").val(correoConf.id);

                    $("#mailCuentaCorreo").val(correoConf.cuenta);
                    console.log(correoConf.cuenta);

                    if(correoConf.cuerpoBienvenida.length != 0){
                        $("#configMensajeBienvenida").prop('checked', true);
                        $("#correoMensajeBienvenida").val(correoConf.cuerpoBienvenida);

                        $("#configMensajeBienvenida").change();
                    }

                    $("#mailCampoBusqueda").val(correoConf.campoBusqueda);
                }

                CKEDITOR.config.allowedContent = true;
                CKEDITOR.replace('correoMensajeBienvenida', {
                    language: 'es'
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

    function configurarMensajeBienvenida(obj){
        if(obj.checked){
            document.getElementById('seccionMensajeBienvenida').style.display = '';
        }else{
            document.getElementById('seccionMensajeBienvenida').style.display = 'none';
        }
    }
</script>