
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
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src=""  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php
   //SECCION : Definicion urls
   $url_crud = "cruds/DYALOGOCRM_SISTEMA/G4/G4_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN

    if(!isset($_GET['view'])){
        $idUsuario = $_SESSION['IDENTIFICACION'];
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = ".$_GET['formulario'];
        $query = $mysqli->query($peobus);
        $PEOBUS_VeRegPro__b = 0 ;
        $PEOBUS_Escritur__b = 0 ;
        $PEOBUS_Adiciona__b = 0 ;
        $PEOBUS_Borrar____b = 0 ;

        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            $PEOBUS_Escritur__b = $key->PEOBUS_Escritur__b ;
            $PEOBUS_Adiciona__b = $key->PEOBUS_Adiciona__b ;
            $PEOBUS_Borrar____b = $key->PEOBUS_Borrar____b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $Zsql = "SELECT G4_ConsInte__b as id, G4_C21 as camp1 , G4_C22 as camp2 FROM ".$BaseDatos_systema.".G4  WHERE G4_Usuario = ".$idUsuario." ORDER BY G4_C21 DESC LIMIT 0, 50";
        }else{
            $Zsql = "SELECT G4_ConsInte__b as id, G4_C21 as camp1 , G4_C22 as camp2 FROM ".$BaseDatos_systema.".G4  ORDER BY G4_C21 DESC LIMIT 0, 50";
        }
    }else{
        $Zsql = "SELECT G4_ConsInte__b as id, G4_C21 as camp1 , G4_C22 as camp2 FROM ".$BaseDatos_systema.".G4  ORDER BY G4_C21 DESC LIMIT 0, 50";
    }

   $result = $mysqli->query($Zsql);

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_usuarios;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li><?php echo $str_usuarios;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    
                </div>
            </div>
            <div class="box-body">
                <div class="row">
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
                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-9">

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
            METDEF
        </h4>
    </div>
    <div class="box-body">

        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G4_C21" id="LblG4_C21">NOMBRE</label>
                        <input type="text" class="form-control input-sm" id="G4_C21" value=""  name="G4_C21"  placeholder="NOMBRE">
                    </div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G4_C22" id="LblG4_C22">TIPO</label>
                        <input type="text" class="form-control input-sm Numerico" value=""  name="G4_C22" id="G4_C22" placeholder="TIPO">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G4_C23" id="LblG4_C23">ESTRATEGIA</label>
                        <input type="text" class="form-control input-sm Numerico" value=""  name="G4_C23" id="G4_C23" placeholder="ESTRATEGIA">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G4_C24" id="LblG4_C24">PASO</label>
                        <input type="text" class="form-control input-sm Numerico" value=""  name="G4_C24" id="G4_C24" placeholder="PASO">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G4_C25" id="LblG4_C25">RANGO</label>
                        <input type="text" class="form-control input-sm Numerico" value=""  name="G4_C25" id="G4_C25" placeholder="RANGO">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G4_C26" id="LblG4_C26">NIVEL</label>
                        <input type="text" class="form-control input-sm Numerico" value=""  name="G4_C26" id="G4_C26" placeholder="NIVEL">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G4_C27" id="LblG4_C27">SUBTIPO</label>
                        <input type="text" class="form-control input-sm Numerico" value=""  name="G4_C27" id="G4_C27" placeholder="SUBTIPO">
                    </div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div>

  
        </div>


    </div>
</div>

<div class="modal fade-in" id="busquedaAvanzada_" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="botonCerradorModal">&times;</button>
                <h4 class="modal-title">Busqueda Avanzada</h4>
            </div>
            <div class="modal-body">

                <div class="row" id="buscador">
                    <div class="col-md-12">
                        <div class="row">
                            <form id="formBusquedaAquiLLave">
  
                        </form>
                    </div>
                </div>
                <div class="col-md-1">&nbsp;</div>
            </div>
            <div class="row" id="botones">
                <div class="col-xs-6">
                    <button class="btn btn-block btn-danger" id="btnCancelar" data-dismiss="modal"  type="button">Cancelar</button>
                </div>
                <div class="col-xs-6">
                    <button class="btn btn-block btn-success" id="btnBuscar" type="button"><i class="fa fa-search"></i> Busqueda</button>
                </div>
            </div>
            <br/>
            <div class="row" id="resulados">
                <div class="col-md-12" id="resultadosBusqueda">
                </div>
            </div>

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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


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

            $(".select2").each(function(){
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
                        url      : '<?=$url_crud;?>?guardarDatos=si',
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

<script type="text/javascript" src="cruds/DYALOGOCRM_SISTEMA/G4/G4_eventos.js"></script> 
<script type="text/javascript">
    $(function(){

        //Select2 estos son los guiones
        


        //datepickers
        

        //Timepickers
        


        //Validaciones numeros Enteros
        

        $("#G4_C22").numeric();
        
        $("#G4_C23").numeric();
        
        $("#G4_C24").numeric();
        
        $("#G4_C25").numeric();
        
        $("#G4_C26").numeric();
        
        $("#G4_C27").numeric();
        

        //Validaciones numeros Decimales
       

        
        
    $("#btnLlamadorAvanzado").click(function(){
        
        $("#resultadosBusqueda").html('');
    });

    $("#btnBuscar").click(function(){
        
        $.ajax({
            url         : 'cruds/DYALOGOCRM_SISTEMA/G4/G4_Funciones_Busqueda_Manual.php?action=GET_DATOS',
            type        : 'post',
            dataType    : 'json',
            data        : {  },
            success     : function(datosq){
                //alert(datosq[0].cantidad_registros);
                if(datosq[0].cantidad_registros > 1){
                    var valores = null;
                    var tabla_a_mostrar = '<div class="box box-default">'+
                    '<div class="box-header">'+
                        '<h3 class="box-title">RESULTADOS DE LA BUSQUEDA</h3>'+
                    '</div>'+
                    '<div class="box-body">'+
                        '<table class="table table-hover table-bordered" style="width:100%;">';
                    tabla_a_mostrar += '<thead>';
                    tabla_a_mostrar += '<tr>';
                    tabla_a_mostrar += '  ';
                    tabla_a_mostrar += '</tr>';
                    tabla_a_mostrar += '</thead><tbody>';
                    tabla_a_mostrar += '<tbody>';
                    $.each(datosq[0].registros, function(i, item) {
                        tabla_a_mostrar += '<tr ConsInte="'+ item.G4_ConsInte__b +'" class="EditRegistro">';
                        tabla_a_mostrar += '';
                        tabla_a_mostrar += '</tr>';
                    });
                    tabla_a_mostrar += '</tbody>';
                    tabla_a_mostrar += '</table></div></div>';
                    
                    $("#resultadosBusqueda").html(tabla_a_mostrar);
                    
                    $(".EditRegistro").dblclick(function(){
                        var id = $(this).attr("ConsInte");
                        swal({
                            html : true,
                            title: "Información - Dyalogo CRM",
                            text: 'Esta seguro de editar este registro?',
                            type: "warning",
                            confirmButtonText: "Editar registro",
                            cancelButtonText : "No Editar registro",
                            showCancelButton : true,
                            closeOnConfirm : true
                        },
                            function(isconfirm){
                                if(isconfirm){
                                    seleccionar_registro_avanzada(id);
                                    
                                    $("#busquedaAvanzada_").modal('hide');
                                    $("#resultadosBusqueda").html('');
                                }else{

                                    swal("Cancelado", "No se editara este registro", "error");
                                }
                                
                            });
                        });
                }else if(datosq[0].cantidad_registros == 1){
                    var id = datosq[0].registros[0].G4_ConsInte__b;

                    seleccionar_registro_avanzada(id);
                    
                    $("#resultadosBusqueda").html('');
                    $("#busquedaAvanzada_").modal('hide');
                }else{
                    //console.log("LLego aqui");
                    swal({
                        html : true,
                        title: "Información - Dyalogo CRM",
                        text: 'No se encontraron datos, desea adicionar un registro?',
                        type: "warning",
                        confirmButtonText: "Adicionar registro",
                        cancelButtonText : "No adicionar registro",
                        showCancelButton : true,
                        closeOnConfirm : true
                    },
                    function(isconfirm){

                        if(isconfirm){
                            $("#add").click();
                            
                            
                            $("#resultadosBusqueda").html('');
                            $("#busquedaAvanzada_").modal('hide');
                           
                        }
                        else
                        {
                            
                            $("#resultadosBusqueda").html('');
                           
                            swal("Cancelado", "No se crearan nuevos registros", "error");
                        }
                        
                    });
                }
            }
        });
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
                        

                        $("#G4_C21").val(item.G4_C21);

                        $("#G4_C22").val(item.G4_C22);

                        $("#G4_C23").val(item.G4_C23);

                        $("#G4_C24").val(item.G4_C24);

                        $("#G4_C25").val(item.G4_C25);

                        $("#G4_C26").val(item.G4_C26);

                        $("#G4_C27").val(item.G4_C27);
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
                        

                        $("#G4_C21").val(item.G4_C21);

                        $("#G4_C22").val(item.G4_C22);

                        $("#G4_C23").val(item.G4_C23);

                        $("#G4_C24").val(item.G4_C24);

                        $("#G4_C25").val(item.G4_C25);

                        $("#G4_C26").val(item.G4_C26);

                        $("#G4_C27").val(item.G4_C27);
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
</script>

