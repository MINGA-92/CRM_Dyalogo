
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
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width:95%; margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-header" style="padding-right: 5px; padding-bottom: 3px; padding-top: 3px;">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">
                    <?php echo $str_configuracion_LE ;?>
                </h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src="mostrar_popups.php?id_paso=<?php echo $_GET['id_paso']?>&view=horarios_entrante"  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php
   //SECCION : Definicion urls
   $url_crud = "formularios/G22/G22_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN


    $Zsql = "SELECT G22_ConsInte__b as id, G22_C212 as camp1 , G22_C213 as camp2 FROM ".$BaseDatos.".G22  ORDER BY G22_C212 DESC LIMIT 0, 50";
    

   $result = $mysqli->query($Zsql);

?>

<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            
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
                                                    <p style='font-size:14px;'><b>".strtoupper(($obj->camp1))."</b></p>
                                                    <p style='font-size:12px; margin-top:-10px;'>".($obj->camp2)."</p>
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
                            <button class="btn btn-default" id="Save">
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel">
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
                                        <div class="panel box box-primary">
                                            <div class="box-header width-border">
                                                <h4>
                                                    <?php echo $str_datos_Basicos_LE; ?>
                                                </h4>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="G22_C213" id="LblG22_C213"><?php echo $str_nombre________LE; ?></label>
                                                            <input type="text" class="form-control input-sm" id="G22_C213" value=""  name="G22_C213"  placeholder="<?php echo $str_nombre________LE; ?>">
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
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <?php echo $str_flujoLlamadas_LE; ?>
                                                </h4>
                                            </div>
                                            <div class="box-body">
                                                <table id="tabla_FLUJO"></table>
                                                <div id="pager_FLUJO"></div> 
                                                <a href="#" data-target="#editarDatos" data-toggle="modal" data-role="button" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-plus"></i>
                                                </a>     
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
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="G22_C213" id="LblG22_C213"><?php echo $str_LimiteLlamadasLE;?></label>
                                                            <input type="text" class="form-control input-sm" id="txtlimiteLlamadas" value=""  name="txtlimiteLlamadas"  placeholder="<?php echo $str_LimiteLlamadasLE;?>">
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="G10_C72" id="LblG10_C72"><?php echo $str_ListaNegra____LE;?></label>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" value="1" name="checkListanegra" id="checkListanegra" data-error="Before you wreck yourself"  > 
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

        $("#usuarios").addClass('active');
        busqueda_lista_navegacion();
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

<script type="text/javascript" src="formularios/G22/G22_eventos.js"></script> 
<script type="text/javascript">
    $(function(){
        $("#txtlimiteLlamadas").numeric();
        <?php if(isset($_GET['id_paso'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos : 'SI', id : <?php echo $_GET['id_paso']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    

                    $("#G22_C212").val(item.G22_C212);

                    $("#G22_C213").val(item.G22_C213);
                    
                    $("#h3mio").html(item.principal);
                    
                    idTotal = <?php echo $_GET['id_paso'];?>;

                    if ( $("#"+idTotal).length > 0) {
                        $("#"+idTotal).click();   
                        $("#"+idTotal).addClass('active'); 
                    }else{
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }
                });
                //Deshabilitar los campos
                              

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

        

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET['id_paso'];?>)
                        
        <?php } ?>

        //str_Select2 estos son los guiones
        


        //datepickers
        

        //Timepickers
        


        //Validaciones numeros Enteros
        


        //Validaciones numeros Decimales
       

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
                            <?php if(!isset($_GET['campan'])){ ?>
                                //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                if($("#oper").val() == 'add'){
                                    idTotal = data;
                                }else{
                                    idTotal= $("#hidId").val();
                                }
                                $(".modalOculto").hide();

                                <?php if(isset($_GET['yourfather'])){ ?>
                                        window.location.href  = "resultadoExitoso.php";
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
                                            
 
                                            $("#G22_C212").val(item.G22_C212);
 
                                            $("#G22_C213").val(item.G22_C213);
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
                        

                        $("#G22_C212").val(item.G22_C212);

                        $("#G22_C213").val(item.G22_C213);
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
                        

                        $("#G22_C212").val(item.G22_C212);

                        $("#G22_C213").val(item.G22_C213);
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
        llenar_grillaDID(id)
        llenar_grillaFlujograma(id);
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
            url:'<?=$url_crud;?>?callDatosSubgrilla_0=si&id='+id,
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
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
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
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
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


    function llenar_grillaFlujograma(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '500';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tabla_FLUJO").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_0=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:[  'id',
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
                        'padre'],
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
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
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
            pager: "#pager_FLUJO",
            rowList: [40,80],
            sortable: true,
            sortname: 'did',
            sortorder: 'asc',
            viewrecords: true,
            caption: '<?php echo $str_flujoLlamadas_LE ; ?>',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'150px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tabla_FLUJO').navGrid("#pager_FLUJO", { add:false, del: true , edit: false });

        $('#tabla_FLUJO').inlineNav('#pager_FLUJO',
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
            $("#tabla_FLUJO").setGridWidth($(window).width());
        }).trigger('resize');
    }

</script>

