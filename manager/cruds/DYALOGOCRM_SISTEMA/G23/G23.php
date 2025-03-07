
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
<div class="modal fade-in" id="flujoEntrante" tabindex="-1" role="dialog" aria-labelledby="flujoEntrante" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <a data-role="button" class="close" id="refrescarGrillas_horarios">&times;</a>
                <h4 class="modal-title">
                    <?php echo $str_configuracion_LE ;?>
                </h4>
            </div>
            <div class="modal-body">
                <div id="div_flujograma">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    //SECCION : Definicion urls
    $url_crud = "cruds/DYALOGOCRM_SISTEMA/G23/G23_CRUD.php";
    //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN


    $Zsql = "SELECT G23_ConsInte__b as id, G23_C214 as camp1 , G23_C215 as camp2 FROM ".$BaseDatos.".G23  ORDER BY G23_C214 DESC LIMIT 0, 50";
    

    $result = $mysqli->query($Zsql);

    $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_GET['id_paso'];
    $res_Lsql = $mysqli->query($Lsql);
    $datos = $res_Lsql->fetch_array();
?>

<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_llamEn;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?page=estrategias"><i class="fa fa-adjust"></i> <?php echo $str_estrategia;?></a></li>
            <li><a href="index.php?page=flujograma&estrategia=<?php echo $datos['ESTPAS_ConsInte__ESTRAT_b']; ?>"><?php echo $str_flujograma;?></a></li>
            <li class="active"><?php echo $str_LLamadas_entrantes;?></li>
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
                    <div class="col-md-12" id="div_formularios">
                        <div>
                            <button class="btn btn-default" id="Save">
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel">
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <?php echo $str_datos_Basicos_LE; ?>
                                                </h4>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="G23_C214" id="LblG23_C214"><?php echo $str_nombre________LE; ?></label>
                                                            <input type="text" class="form-control input-sm" id="G23_C214" value=""  name="G23_C214"  placeholder="<?php echo $str_nombre________LE; ?>">
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">    
                                                        <table id="tabla_DID"></table>
                                                        <div id="pager_DID"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header">
                                                <h4 class="box-title">
                                                    <?php echo $str_flujoLlamadas_LE; ?>
                                                </h4>
                                            </div>
                                            <div class="box-body">
                                                <table class="table table-hover table-bordered" id="tablaDatosDetalless0" width="100%">
                                                    </table>
                                                    <div id="pagerDetalles0">
                                                    </div> 
                                                    <a title="Crear FLUJOS_ENTRANTE" href="index.php?page=horarios&id_paso=<?php echo $_GET['id_paso']; ?>&formularioPadre=23&formulario=24&poblacion=<?php echo $_GET['poblacion'];?>"  data-role="button" class="btn btn-primary btn-sm" id="horariosModal"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <?php echo $str_AVANZADO______LE;?>
                                                </h4>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">                    
                                                        <!-- CAMPO TIPO ENTERO -->
                                                        <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                        <div class="form-group">
                                                            <label for="G23_C216" id="LblG23_C216"><?php echo $str_LimiteLlamadasLE;?></label>
                                                            <input type="text" class="form-control input-sm Numerico" value=""  name="G23_C216" id="G23_C216" placeholder="<?php echo $str_LimiteLlamadasLE;?>">
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                        <div class="form-group">
                                                            <label for="G23_C217" id="LblG23_C217"><?php echo $str_ListaNegra____LE;?></label>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox"  value="1" name="G23_C217" id="G23_C217" data-error="Before you wreck yourself"  > 
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <!-- FIN DEL CAMPO SI/NO -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- SI ES MAESTRO - DETALLE CREO LAS TABS --> 

                                        <!--<hr/>
                                        <div class="nav-tabs-custom">

                                            <ul class="nav nav-tabs">

                                                <li class="active">
                                                    <a href="#tab_0" data-toggle="tab" id="tabs_click_0">FLUJOS_ENTRANTE</a>
                                                </li>

                                            </ul>


                                            <div class="tab-content">

                                                <div class="tab-pane active" id="tab_0"> 
                                                    <table class="table table-hover table-bordered" id="tablaDatosDetalless0" width="100%">
                                                    </table>
                                                    <div id="pagerDetalles0">
                                                    </div> 
                                                    <button title="Crear FLUJOS_ENTRANTE" class="btn btn-primary btn-sm llamadores" padre="'<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' " id="btnLlamar_0"><i class="fa fa-plus"></i></button>
                                                </div>

                                            </div>

                                        </div>-->
                                        <input type="hidden" value="<?php echo $_SESSION['HUESPED']; ?>"  name="G23_C215" id="G23_C215" >
                                        <input type="hidden" value="<?php echo $_GET['id_paso']?>"  name="G23_C246" id="G23_C246">
                                        <!-- SECCION : PAGINAS INCLUIDAS -->
                                        <input type="hidden" name="id" id="hidId" value='0'>
                                        <input type="hidden" name="oper" id="oper" value='add'>
                                        <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                                    </div>
                                </div>
                            </form>
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
      $(function(){

        /*$("#horariosModal").click(function(){
            $("#editarDatos").modal('hide');
            $("#flujoEntrante").modal('show');
        });

        $("#refrescarGrillas_horarios").click(function(){
            $("#flujoEntrante").modal('hide');
            $("#editarDatos").modal('show');
        });*/

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

        $("#usuarios").addClass('active');
        //busqueda_lista_navegacion();
        $(".CargarDatos :first").click();
      
        $("#btnLlamadorAvanzado").click(function(){
            $('#busquedaAvanzada_ :input').each(function(){
                $(this).attr('disabled', false);
            });
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

            <?php if(isset($_GET['view'])){ ?>
                window.location.href  = "cancelado.php";
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

<script type="text/javascript" src="cruds/DYALOGOCRM_SISTEMA/G23/G23_eventos.js"></script> 
<script type="text/javascript">
    objetoFunciones  = {
        cargarHorariosEntrante : function(){
            $.ajax({
                url     : 'mostrar_popups.php?id_paso=<?php echo $_GET['id_paso']?>&view=horarios_entrante',
                type    : 'get',
                success : function(data){
                    $("#div_flujograma").html(data);
                }
            });
        }
    }


    $(function(){
        $("#refrescarGrillas_horarios").click(function(){
            $("#flujoEntrante").modal('hide');
        });

        objetoFunciones.cargarHorariosEntrante();

        <?php if(isset($_GET['id_paso'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    
                    $("#G23_C214").val(item.G23_C214);

                    $("#G23_C215").val(item.G23_C215);

                    $("#G23_C245").val(item.G23_C245);

                    $("#G23_C245").val(item.G23_C245).trigger("change"); 

                    $("#G23_C246").val(item.G23_C246);

                    $("#G23_C216").val(item.G23_C216);

                    if(item.G23_C217 == '1'){
                       if(!$("#G23_C217").is(':checked')){
                           $("#G23_C217").prop('checked', true);  
                        }
                    } else {
                        if($("#G23_C217").is(':checked')){
                           $("#G23_C217").prop('checked', false);  
                        }
                        
                    }
                        
                    $("#h3mio").html(item.principal);
                        
                    idTotal = <?php echo $_GET['id_paso'];?>;

                    if ( $("#"+idTotal).length > 0) {
                        $("#"+idTotal).click();   
                        $("#"+idTotal).addClass('active'); 
                    }else{
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }

                    $("#oper").val('edit');    
                });
                //Deshabilitar los campos

                //Habilitar todos los campos para edicion
                /*$('#FormularioDatos :input').each(function(){
                    $(this).attr('disabled', true);
                });*/

                              

                //Habilidar los botones de operacion, add, editar, eliminar
                $("#add").attr('disabled', false);
                $("#edit").attr('disabled', false);
                $("#delete").attr('disabled', false);

                //Desahabiliatra los botones de salvar y seleccionar_registro
                $("#cancel").attr('disabled', true);
                $("#Save").attr('disabled', true);
            } 
        });

        $("#hidId").val(<?php echo $_GET['id_paso'];?>);

        

        $("#TxtFechaReintento").attr('disabled', true);
        $("#TxtHoraReintento").attr('disabled', true); 

        $("#btnLlamar_0").attr('padre', <?php echo $_GET['id_paso'];?>);

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET['id_paso'];?>);
                        
        <?php } ?>

        //str_Select2 estos son los guiones
        


        $("#G23_C245").select2({ 
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

        $("#G23_C245").change(function(){
            var valores = $("#G23_C245 option:selected").text();
            var campos = $("#G23_C245 option:selected").attr("dinammicos");
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
                                      

        //datepickers
        

        //Timepickers
        


        //Validaciones numeros Enteros
        

        $("#G23_C215").numeric();
        
        $("#G23_C246").numeric();
        
        $("#G23_C216").numeric();
        
        //Buton gurdar
        

        $("#Save").click(function(){
            var bol_respuesta = before_save();
            if(bol_respuesta){
                var form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#FormularioDatos")[0]);
                $.ajax({
                   url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data){
                            <?php if(isset($_GET['id_paso'])){ ?>
                                $.ajax({
                                    url      : '<?=$url_crud;?>',
                                    type     : 'POST',
                                    data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
                                    dataType : 'json',
                                    success  : function(data){
                                        //recorrer datos y enviarlos al formulario
                                        $.each(data, function(i, item) {
                                            
                                            $("#G23_C214").val(item.G23_C214);

                                            $("#G23_C215").val(item.G23_C215);

                                            $("#G23_C245").val(item.G23_C245);

                                            $("#G23_C245").val(item.G23_C245).trigger("change"); 

                                            $("#G23_C246").val(item.G23_C246);

                                            $("#G23_C216").val(item.G23_C216);

                                            if(item.G23_C217 == '1'){
                                               if(!$("#G23_C217").is(':checked')){
                                                   $("#G23_C217").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G23_C217").is(':checked')){
                                                   $("#G23_C217").prop('checked', false);  
                                                }
                                                
                                            }
                                                
                                            $("#h3mio").html(item.principal);
                                                
                                            idTotal = <?php echo $_GET['id_paso'];?>;

                                            if ( $("#"+idTotal).length > 0) {
                                                $("#"+idTotal).click();   
                                                $("#"+idTotal).addClass('active'); 
                                            }else{
                                                //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                                                $(".CargarDatos :first").click();
                                            }

                                            $("#oper").val('edit');
                                        });               
                                    },
                                    //si ha ocurrido un error
                                    error: function(){
                                        after_save_error();
                                        alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                                    }
                                });
                            <?php  } ?>  
                        }
                    }
                    ,
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('<?php echo $error_de_red; ?>');
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
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
                        

                    $.jgrid.gridUnload('#tablaDatosDetalless0'); 
                    cargarHijos_0(id);

                        $("#G23_C214").val(item.G23_C214);

                        $("#G23_C215").val(item.G23_C215);

                        $("#G23_C245").val(item.G23_C245);
 
                        $("#G23_C245").val(item.G23_C245).trigger("change"); 

                        $("#G23_C246").val(item.G23_C246);

                        $("#G23_C216").val(item.G23_C216);
    
                        if(item.G23_C217 == '1'){
                           if(!$("#G23_C217").is(':checked')){
                               $("#G23_C217").prop('checked', true);  
                            }
                        } else {
                            if($("#G23_C217").is(':checked')){
                               $("#G23_C217").prop('checked', false);  
                            }
                            
                        }
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
        

        $.jgrid.gridUnload('#tablaDatosDetalless0'); 
    } 

    

    function cargarHijos_0(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tablaDatosDetalless0").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_0=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:[
                    'id',
                    '<?php echo $str_NOMBREJQGRID__LE;?>',
                    '<?php echo $str_LUHI__________LE;?>',
                    '<?php echo $str_LUHF__________LE;?>', 
                    '<?php echo $str_MAHI__________LE;?>', 
                    '<?php echo $str_MAHF__________LE;?>', 
                    '<?php echo $str_MIHI__________LE;?>', 
                    '<?php echo $str_MIHF__________LE;?>', 
                    '<?php echo $str_JUHI__________LE;?>', 
                    '<?php echo $str_JUHF__________LE;?>', 
                    '<?php echo $str_VIHI__________LE;?>', 
                    '<?php echo $str_VIHF__________LE;?>', 
                    '<?php echo $str_SAHI__________LE;?>', 
                    '<?php echo $str_SAHF__________LE;?>', 
                    '<?php echo $str_DOHI__________LE;?>', 
                    '<?php echo $str_DOHF__________LE;?>', 
                    '<?php echo $str_FEHI__________LE;?>',
                    '<?php echo $str_FEHF__________LE;?>', 
                    '<?php echo $str_defecto_______LE;?>', 
                    'padre'
                ],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }

                ,
                {
                    name:'G24_C243', 
                    index:'G24_C243', 
                    width:150, 
                    editable: true 
                }

                , 
                { 
                    name:'G24_C220', 
                    index:'G24_C220', 
                    width:70 ,
                    editable: true
                }

                , 
                { 
                    name:'G24_C221', 
                    index:'G24_C221', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C223', 
                    index:'G24_C223', 
                    width:70 ,
                    editable: true ,
                    formatter: 'text'
                }
 
                ,
                {  
                    name:'G24_C224', 
                    index:'G24_C224', 
                    width:80 ,
                    editable: true, 
                    searchoptions: {
                        sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                    }

                }

                ,
                { 
                    name:'G24_C226', 
                    index: 'G24_C226', 
                    width:80, 
                    resizable:false, 
                    sortable:true , 
                    editable: true 
                }

                , 
                { 
                    name:'G24_C227', 
                    index:'G24_C227', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C229', 
                    index:'G24_C229', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C230', 
                    index:'G24_C230', 
                    width:70 ,
                    editable: true
                }

                , 
                { 
                    name:'G24_C232', 
                    index:'G24_C232', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C233', 
                    index:'G24_C233', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C235', 
                    index:'G24_C235', 
                    width:70 ,
                    editable: true
                }

                , 
                { 
                    name:'G24_C236', 
                    index:'G24_C236', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C238', 
                    index:'G24_C238', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C239', 
                    index:'G24_C239', 
                    width:70 ,
                    editable: true
                }

                , 
                { 
                    name:'G24_C241', 
                    index:'G24_C241', 
                    width:70 ,
                    editable: true
                }

                ,
                {  
                    name:'G24_C242', 
                    index:'G24_C242', 
                    width:70 ,
                    editable: true 
                }

                ,
                {  
                    name:'G24_C244', 
                    index:'G24_C244', 
                    width:70 ,
                    editable: true 
                }
                
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles0",
            rowList: [40,80],
            sortable: true,
            sortname: 'G24_C243',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'FLUJOS_ENTRANTE',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            ,

            ondblClickRow: function(rowId) {
                $("#frameContenedor").attr('src', 'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_CentroDiesel/new_index.php?formulario=24&view=si&id_paso='+ rowId +'&formaDetalle=si&yourfather='+ idTotal +'&pincheCampo=247&formularioPadre=23');
                $("#editarDatos").modal('show');

            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaDatosDetalless0").setGridWidth($(window).width());
        }).trigger('resize');
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
                        

                    $.jgrid.gridUnload('#tablaDatosDetalless0'); 
                    cargarHijos_0(id);

                        $("#G23_C214").val(item.G23_C214);

                        $("#G23_C215").val(item.G23_C215);

                        $("#G23_C245").val(item.G23_C245);
 
                        $("#G23_C245").val(item.G23_C245).trigger("change"); 

                        $("#G23_C246").val(item.G23_C246);

                        $("#G23_C216").val(item.G23_C216);
    
                        if(item.G23_C217 == '1'){
                           if(!$("#G23_C217").is(':checked')){
                               $("#G23_C217").prop('checked', true);  
                            }
                        } else {
                            if($("#G23_C217").is(':checked')){
                               $("#G23_C217").prop('checked', false);  
                            }
                            
                        }
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

    function llenar_grillaDID(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '350';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tabla_DID").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubGrillaDID=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','<?php echo $str_DID___________LE;?>', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }

                ,
                { 
                    name:'did', 
                    index: 'did', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_DID=si',
                        dataInit:function(el){
                        
                        }
                    }
                }
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pager_DID",
            rowList: [40,80],
            sortable: true,
            sortname: 'did',
            sortorder: 'asc',
            viewrecords: true,
            caption: '<?php echo $str_DID___________LE;?>',
            editurl:"<?=$url_crud;?>?insertDatosSubGrillaDID=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'150px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tabla_DID').navGrid("#pager_DID", { add:false, del: true , edit: false });


        $('#tabla_DID').inlineNav('#pager_DID',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $(window).bind('resize', function() {
            $("#tabla_DID").setGridWidth($(window).width());
        }).trigger('resize');
    }

    function vamosRecargaLasGrillasPorfavor(id){
        $.jgrid.gridUnload('#tablaDatosDetalless0'); 
        cargarHijos_0(id);

        $.jgrid.gridUnload('#tabla_DID'); 
        llenar_grillaDID(id);
    }


    function before_save(){ return true; }

    function after_save(){}

    function after_save_error(){}

    function before_edit(){}

    function after_edit(){}

    function before_add(){}

    function after_add(){}

    function before_delete(){}

    function after_delete(){}

    function after_delete_error(){}


</script>

