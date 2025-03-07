
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
    $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G15/G15_CRUD.php";
    //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN


    $Zsql = "SELECT G15_ConsInte__b as id, G15_C146 as camp1, G16_C147 as camp2 FROM ".$BaseDatos_systema.".G15  LEFT JOIN ".$BaseDatos_systema.".G16 ON G16_ConsInte__b = G15_C148 ORDER BY G15_C146 DESC LIMIT 0, 50";

    $result = $mysqli->query($Zsql);

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

<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_sms;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
<?php } ?>
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <?php if(!isset($_GET['view'])){ ?>
                    <!-- SECCION LISTA NAVEGACIÓN -->
                    <div class="col-md-3" id="div_lista_navegacion">
                        <div class="input-group input-group-sm" style="width: auto;">
                            <input type="text" name="table_search_lista_navegacion" class="form-control input-sm pull-right" placeholder="<?php echo $str_busqueda;?>" id="table_search_lista_navegacion">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-default" id="BtnBusqueda_lista_navegacion"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <br/>
                        <!-- FIN BUSQUEDA EN LA LISTA DE NAVEGACION-->
                            
                        <!-- LISTA DE NAVEGACION -->
                        <div class="table-responsive no-padding" id="txtPruebas" style="height:553px; overflow-x:hidden; overflow-y:scroll;">
                            <table class="table table-hover" id="tablaScroll">
                                
                                <?php
                 
                                    while($obj = $result->fetch_object()){
                                        echo "<tr class='CargarDatos' id='".$obj->id."'>
                                                <td>
                                                    <p style='font-size:14px;'><b>".strtoupper(utf8_encode($obj->camp1))."</b></p>
                                                    <p style='font-size:12px; margin-top:-10px;'>".utf8_encode($obj->camp2)."</p>
                                                </td>
                                            </tr>";
                                    } 
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-9" id="div_formularios">
                        <!-- SECCION BOTONES -->
                        <div>
                            <button class="btn btn-default" id="add">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-default"  id="delete" >
                                <i class="fa fa-trash"></i> 
                            </button>
                            <button class="btn btn-default" id="edit" >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-default" id="Save" disabled>
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel" disabled>
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
            <?php }else{ ?>
                    <div class="col-md-12" id="div_formularios">
                        <div>
                            <button class="btn btn-default" id="Save" >
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel" >
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
            <?php } ?>
                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>MENSAJE SALIENTE</h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-12 row">
                                                <!-- CAMPO TIPO TEXTO -->
                                                <div class="col-md-11">   
                                                    <label for="G15_C146" id="LblG15_C146">NOMBRE</label>
                                                    <input type="text" class="form-control input-sm" id="G15_C146" value=""  name="G15_C146"  placeholder="NOMBRE">
                                                </div>
                                                <div class="col-md-1 col-xs-1">
                                                    <div class="form-group">
                                                        <label for="pasoActivo" id="LblpasoActivo">ACTIVO</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="-1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself" checked> 
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <!-- FIN DEL CAMPO TIPO TEXTO -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php 
                                                    $Lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE SECCIO_TipoSecc__b = 1 AND PREGUN_ConsInte__GUION__b = ".$_GET['poblacion'];
                                                ?>
                                                <!-- CAMPO DE TIPO GUION -->
                                                <div class="form-group">
                                                    <label for="G15_C149" id="LblG15_C149">DESTINATARIO</label>
                                                    <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G15_C149" id="G15_C149">
                                                        <option value="0" data-longitudmensaje="">TEXTO</option>
                                                        <?php
                                                        $cur_result = $mysqli->query($Lsql);
                                                        while ($key = $cur_result->fetch_object()) {
                                                            echo "<option value='{$key->PREGUN_ConsInte__b}' data-longitudmensaje=''>{$key->PREGUN_Texto_____b}</option>";
                                                        }   
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- FIN DEL CAMPO TIPO LISTA -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php 
                                                $str_Lsql = "SELECT * FROM dy_sms.configuracion WHERE id_huesped = -1 or id_huesped=" . $_SESSION['HUESPED'];
                                                ?>
                                                <!-- CAMPO DE TIPO GUION -->
                                                <div class="form-group">
                                                    <label for="G15_C148" id="LblG15_C148">PROVEEDOR</label>
                                                    <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G15_C148" id="G15_C148">
                                                        <option value="0" data-longitudmensaje="160">NOMBRE</option>
                                                        <?php
                                                            /*
                                                                SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUION
                                                            */
                                                            $combo = $mysqli->query($str_Lsql);
                                                            while($obj = $combo->fetch_object()){
                                                                echo "<option value='{$obj->id}' data-longitudmensaje='{$obj->longitud_maxima_sms}'>{$obj->nombre}</option>";
                                                            }    
                                                        ?>
                                                    </select>
                                                </div>
                                                <!-- FIN DEL CAMPO TIPO LISTA -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- CAMPO TIPO MEMO -->
                                                <div class="form-group">
                                                    <label for="G15_C150" id="LblG15_C150">MENSAJE</label>
                                                    <textarea class="form-control input-sm" maxlength="160" name="G15_C150" id="G15_C150"  value="" placeholder="MENSAJE"></textarea>
                                                </div>
                                                <!-- FIN DEL CAMPO TIPO MEMO -->
                                            </div>
                                        </div>

                                        <!-- Seccion de respuesta al mensaje -->
                                        <!-- <div class="row">
                                            <div class="col-md-12 ">
                                                <h4>RESPUESTA AL MENSAJE</h4>
                                            </div>
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" value="-1" name="esperarRespuesta" id="esperarRespuesta">
                                                            <strong>Esperar respuesta</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Campo de la base de datos que guardara la respuesta</label>
                                                <select name="campoActualizar" id="campoActualizar" class="form-control" disabled>
                                                    <option value="">Seleccione</option>
                                                </select>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="col-md-3">
                                        <table class="table-bordered table-hover table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <?php 
                                                            $Lsql = "SELECT GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$_GET['poblacion'];
                                                            $cur_result = $mysqli->query($Lsql);
                                                            $res_Lsql = $cur_result->fetch_array();
                                                            echo "VARIABLES ".$res_Lsql['GUION__Nombre____b'];
                                                        ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $Lsql = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE SECCIO_TipoSecc__b = 1 AND PREGUN_ConsInte__GUION__b = ".$_GET['poblacion'];
                                                    
                                                    $cur_result = $mysqli->query($Lsql);
                                                    while ($key = $cur_result->fetch_object()) {
                                                        $titulodeLapregunta = $key->PREGUN_Texto_____b;
                                                        $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                                                        $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                                                        $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                                                        echo '<tr>';
                                                        echo '  <td>';
                                                        echo "\${".$titulodeLapregunta."}";
                                                        echo '  </td>';
                                                        echo '<tr>';
                                                    }
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${ID_REGISTRO}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${LinkContenidoUG}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${LinkContenidoGMI}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${Comentario_Ultima_Gestion}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${Comentario_Gestión_Mas_Importante}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <!-- SECCION : PAGINAS INCLUIDAS -->
                                <input type="hidden" name="id" id="hidId" value='0'>
                                <input type="hidden" name="G15_C152" id="G15_C152" value='<?php echo $_GET['id_paso'];?>'>
                                <input type="hidden" name="oper" id="oper" value='add'>
                                <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
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

        
        <!-- Box para reportes -->
        <div class="panel box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="" href="#reports" aria-expanded="false" class="collapsed">
                        REPORTES
                    </a>
                </h4>
            </div>
            <div id="reports" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                <div style="padding: 15px;">
                    <div class="row">
                        <div class="col-md-12">
                            <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=red&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        
<?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>


<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
    var maxLengthSms = 0;
      $(function(){

        // Cuando se selecciona un proveedor cambia el maxlengh del mensaje
        $("#G15_C148").change(function(){
            maxLengthSms = $(this).find(':selected').data('longitudmensaje')
            $('#G15_C150').attr('maxlength', maxLengthSms);
        });
      
        $("#btnLlamadorAvanzado").click(function(){
            $('#busquedaAvanzada_ :input').each(function(){
                $(this).attr('disabled', false);
            });
        });

        $("#esperarRespuesta").change(function(){
            if($(this).is(":checked")){
                $("#campoActualizar").attr('disabled', false);
            }else{
                $("#campoActualizar").attr('disabled', true);
            }
        }); 

        $("#tablaScroll").on('scroll', function() {
            //alert('Si llegue');
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                
                $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si' }, function(data){
                    if(data != ""){
                        $("#TablaIzquierda").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                });
            }
        });

        //SECCION FUNCIONALIDAD BOTONES

        //Funcionalidad del boton + , add
        $("#add").click(function(){

             //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);    

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);   
            $("#Save").attr('disabled', false);
            
           

            //Inializacion campos vacios por defecto
            $('#FormularioDatos :input').each(function(){
                if($(this).is(':checkbox')){
                    if($(this).is(':checked')){
                        $(this).attr('checked', false);
                    }
                    $(this).attr('disabled', false); 
                }else{
                    $(this).val('');
                    $(this).attr('disabled', false); 
                }
                               
            });

            $(".str_Select2").each(function(){
                $(this).val(0).change();
            });

            $("#hidId").val(0);
            $("#h3mio").html('');
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";
            before_add();
           
        });

        jQuery.fn.reset = function () {
            $(this).each (function() { this.reset(); });
        }

        //funcionalidad del boton editar
        $("#edit").click(function(){

            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);    

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);   
            $("#Save").attr('disabled', false);

            
            //Le informa al crud que la operaciòn a ejecutar es editar registro
            $("#oper").val('edit');
            //Habilitar todos los campos para edicion
            $('#FormularioDatos :input').each(function(){
                $(this).attr('disabled', false);
            });

            before_edit();
          
        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function(){
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);

            <?php if(isset($_GET['id_paso'])){ ?>
                    $("#editarDatos").modal('hide');
            <?php }  ?>
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function(){
            //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
            alertify.confirm("¿Está seguro de eliminar el registro seleccionado?", function (e) {
                //Si la persona acepta
                if (e) {
                    var id = $("#hidId").val();
                    //se envian los datos, diciendo que la oper es "del"
                    $.ajax({
                        url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                        type     : 'POST',
                        data     : { id : id , oper : 'del'},
                        success  : function(data){
                            if(data == '1'){   
                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                llenar_lista_navegacion('');
                            }else{
                                //Algo paso, hay un error
                                alert(data);
                            }
                        } 
                    });
                    
                } else {
                    
                }
            }); 
        });


        //datos Hoja de busqueda
        $("#BtnBusqueda_lista_navegacion").click(function(){
            //alert($("#table_search_lista_navegacion").val());
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        });
        
        //Cajaj de texto de bus queda
        $("#table_search_lista_navegacion").keypress(function(e){
            if(e.keyCode == 13)
            {
                llenar_lista_navegacion($(this).val());
            }
        });

        //preguntar cuando esta vacia la tabla para dejar solo los botones correctos habilitados
        var g = $("#tablaScroll").html();
        if(g === ''){
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true); 
        }
    });


</script>

<script type="text/javascript" src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G15/G15_eventos.js"></script> 
<script type="text/javascript">
    $(function(){
        <?php if(isset($_GET['id_paso'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?>, poblacion: <?php echo $_GET['poblacion']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                if(data==''){
                    $("#G15_C146").val('SMS_SALIENTE_'+<?php echo $_GET['id_paso']; ?>);    
                }else{
                    $.each(data, function(i, item) {
                        $("#G15_C146").val(item.G15_C146);
                        
                        if(item.pasoActivo != '-1'){
                            $('#pasoActivo').prop('checked',false);
                        }

                        $("#G15_C148").val(item.G15_C148);

                        $("#G15_C148").val(item.G15_C148).trigger("change"); 

                        $("#G15_C149").val(item.G15_C149);

                        $("#G15_C149").val(item.G15_C149).trigger("change"); 

                        $("#G15_C150").val(item.G15_C150);

                        if(item.G15_C153 === null || item.G15_C153 == 0){
                            $("#campoActualizar").val("");
                        }else{
                            $("#campoActualizar").val(item.G15_C153);
                        }

                        if(item.G15_C154 == -1){
                            
                            $("#campoActualizar").prop('disabled', false);

                            if(!$("#esperarRespuesta").is(':checked')){
                                $("#esperarRespuesta").prop('checked', true); 
                            }
                        }else{
                            if($("#esperarRespuesta").is(':checked')){
                                $("#esperarRespuesta").prop('checked', false);
                            }
                        }

                        $("#h3mio").html(item.principal);

                        $("#hidId").val(item.G15_ConsInte__b);

                        $("#TxtFechaReintento").attr('disabled', true);

                        $("#TxtHoraReintento").attr('disabled', true); 

                        vamosRecargaLasGrillasPorfavor(item.G15_ConsInte__b);

                        $("#oper").val('edit');

                    });
                }
                //Deshabilitar los campos
            } 
        });

        
                        
        <?php } ?>

        //str_Select2 estos son los guiones
        


        $("#G15_C148").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G15_C148").change(function(){
            var valores = $("#G15_C148 option:selected").text();
            var campos = $("#G15_C148 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G15_C149").select2({ 
            dropdownParent: $("#editarDatos")
        });
                                      

        //datepickers
        

        //Timepickers
        


        //Validaciones numeros Enteros
        


        //Validaciones numeros Decimales
       
   

        //Buton gurdar
        

        $("#Save").click(function(){
            var bol_respuesta = before_save();
            if($("#G15_C146").val() == ''){
                bol_respuesta=false;
                alertify.error('el campo nombre es obligatorio');
            }            
            if(bol_respuesta){
                var form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#FormularioDatos")[0]);
                $.ajax({
                    url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&poblacion=<?php echo $_GET['poblacion'] ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data){
                            
                            // GENERAMOS LA VISTA DE LA MUESTRA
                            $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaMuestra=si',
                                type: 'POST',
                                data: { pasoId :  '<?php echo $_GET['id_paso']; ?>' },
                                dataType:'JSON'
                            });

                            <?php if(!isset($_GET['campan'])){ ?>
                                //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                if($("#oper").val() == 'add'){
                                    idTotal = data;
                                }else{
                                    idTotal= $("#hidId").val();
                                }
                                $(".modalOculto").hide();

                                <?php if(isset($_GET['id_paso'])){ ?>
                                        $("#editarDatos").modal('hide');
                                <?php }  ?>
                                //Limpiar formulario
                                form[0].reset();
                                after_save();
                                <?php if(isset($_GET['registroId'])){ ?>
                                $.ajax({
                                    url      : '<?=$url_crud;?>',
                                    type     : 'POST',
                                    data     : { CallDatos : 'SI', id : <?php echo $_GET['registroId']; ?> },
                                    dataType : 'json',
                                    success  : function(data){
                                        //recorrer datos y enviarlos al formulario
                                        $.each(data, function(i, item) {
                                            
 
                                            $("#G15_C146").val(item.G15_C146);
 
                                            $("#G15_C148").val(item.G15_C148);

                                            $("#G15_C148").val(item.G15_C148).trigger("change"); 
 
                                            $("#G15_C149").val(item.G15_C149);

                                            $("#G15_C149").val(item.G15_C149).trigger("change"); 
 
                                            $("#G15_C150").val(item.G15_C150);
                                            $("#h3mio").html(item.principal);
                                        });

                                        //Deshabilitar los campos

                                        //Habilitar todos los campos para edicion
                                        $('#FormularioDatos :input').each(function(){
                                            $(this).attr('disabled', true);
                                        });

                                        //Habilidar los botones de operacion, add, editar, eliminar
                                        $("#add").attr('disabled', false);
                                        $("#edit").attr('disabled', false);
                                        $("#delete").attr('disabled', false);

                                        //Desahabiliatra los botones de salvar y seleccionar_registro
                                        $("#cancel").attr('disabled', true);
                                        $("#Save").attr('disabled', true);
                                    } 
                                })
                                $("#hidId").val(<?php echo $_GET['registroId'];?>);
                                <?php } else { ?>
                                    llenar_lista_navegacion('');
                                <?php } ?>   

                            <?php }else{ ?>
                               
                                
                
                            <?php } ?>        
                        }else{
                            //Algo paso, hay un error
                            alertify.error('Un error ha ocurrido');
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                    }
                });
            }
        });
    });


    //SECCION  : Manipular Lista de Navegacion

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(x){
        var tr = '';
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatosJson : 'SI', Busqueda : x},
            dataType : 'json',
            success  : function(data){
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {
                    tr += "<tr class='CargarDatos' id='"+data[i].id+"'>";
                    tr += "<td>";
                    tr += "<p style='font-size:14px;'><b>"+data[i].camp1+"</b></p>";
                    tr += "<p style='font-size:12px; margin-top:-10px;'>"+data[i].camp2+"</p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ( $("#"+idTotal).length > 0) {
                    $("#"+idTotal).click();   
                    $("#"+idTotal).addClass('active'); 
                }else{
                    //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                    $(".CargarDatos :first").click();
                }

            } 
        });
    }

    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion(){

        $(".CargarDatos").click(function(){
            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');
              
              
            var id = $(this).attr('id');
            //buscar los datos
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        

                        $("#G15_C146").val(item.G15_C146);

                        $("#G15_C148").val(item.G15_C148);
 
                        $("#G15_C148").val(item.G15_C148).trigger("change"); 

                        $("#G15_C149").val(item.G15_C149);
 
                        $("#G15_C149").val(item.G15_C149).trigger("change"); 

                        $("#G15_C150").val(item.G15_C150);
                        $("#h3mio").html(item.principal);
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function(){
                        $(this).attr('disabled', true);
                    });



                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
        });
    }

    function seleccionar_registro(){
        //Seleccinar loos registros de la Lista de navegacion, 
        if ( $("#"+idTotal).length > 0) {
            $("#"+idTotal).click();   
            $("#"+idTotal).addClass('active'); 
            idTotal = 0;
            $(".modalOculto").hide();
        }else{
            $(".CargarDatos :first").click();
        } 
        
    } 

    

    function seleccionar_registro_avanzada(id){
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        

                        $("#G15_C146").val(item.G15_C146);

                        $("#G15_C148").val(item.G15_C148);
 
                        $("#G15_C148").val(item.G15_C148).trigger("change"); 

                        $("#G15_C149").val(item.G15_C149);
 
                        $("#G15_C149").val(item.G15_C149).trigger("change"); 

                        $("#G15_C150").val(item.G15_C150);
                        $("#h3mio").html(item.principal);
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function(){
                        $(this).attr('disabled', true);
                    });

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            $("#"+idTotal).addClass('active');
    }

    function vamosRecargaLasGrillasPorfavor(id){
        
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

        if($("#G15_C146").val() == ''){
            bol_respuesta=false;
            alertify.error('el campo nombre es obligatorio');
        }

        if(bol_respuesta){

            let formData = new FormData($("#FormularioDatos")[0]);

            $.ajax({
                url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&poblacion=<?php echo $_GET['poblacion'] ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                type: 'POST',
                data: formData,
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
                        $("#hidId").val(data);

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
        let smsId = $("#hidId").val();
        let pob = '<?php echo $_GET['poblacion'] ?>';

        $.ajax({
            url: '<?=$url_crud;?>?realizarPruebaPaso=true',
            type: 'POST',
            dataType: 'JSON',
            data:{ telefono, smsId, pob },
            beforeSend: function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> Ejecutando prueba de envio de sms' });
            },
            success: function(data){
                $.unblockUI();

                let texto = '';
                let titulo = 'Prueba realizada';

                if(data.estado == 'ok'){
                    // si la ejecucion es correcta 
                    if(data.muestra.estado == 3 && data.smsSaliente !== null){
    
                        texto = `
                            <p>Prueba ejecutada con exito</p>
                            <p>Respuesta de ejecucion ${data.respuesta}</p>
                            <p>Estado del registro ${data.muestra.estado}</p>
                            <p>Fecha ${data.smsSaliente.fecha_hora}</p>
                            <p>Respuesta ${data.smsSaliente.respuesta}</p>
                            <p>Destino ${data.smsSaliente.tel_destino}</p>
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
