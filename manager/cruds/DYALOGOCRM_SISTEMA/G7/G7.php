<?php
   //SECCION : Definicion urls
   $url_crud = "cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN

    $PEOBUS_Escritur__b = 1 ;
    $PEOBUS_Adiciona__b = 1 ;
    $PEOBUS_Borrar____b = 1 ;

    if(!isset($_GET['view'])){
        $idUsuario = $_SESSION['IDENTIFICACION'];
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = ".$_GET['formulario'];
        $query = $mysqli->query($peobus);
        $PEOBUS_VeRegPro__b = 0 ;
        
        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            $PEOBUS_Escritur__b = $key->PEOBUS_Escritur__b ;
            $PEOBUS_Adiciona__b = $key->PEOBUS_Adiciona__b ;
            $PEOBUS_Borrar____b = $key->PEOBUS_Borrar____b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $Zsql = "SELECT G7_ConsInte__b as id, G7_C33 as camp1 , G7_C35 as camp2 FROM ".$BaseDatos.".G7  WHERE G7_Usuario = ".$idUsuario." ORDER BY G7_C33 DESC LIMIT 0, 50";
        }else{
            $Zsql = "SELECT G7_ConsInte__b as id, G7_C33 as camp1 , G7_C35 as camp2 FROM ".$BaseDatos.".G7  ORDER BY G7_C33 DESC LIMIT 0, 50";
        }
    }else{
        $Zsql = "SELECT G7_ConsInte__b as id, G7_C33 as camp1 , G7_C35 as camp2 FROM ".$BaseDatos.".G7  ORDER BY G7_C33 DESC LIMIT 0, 50";
    }

   $result = $mysqli->query($Zsql);

?>

    <section class="content">

        <form id="FormularioDatos">
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <div class="pull-right">
                        <button type="button" class="btn btn-default" id="Save">
                            <i class="fa fa-save"></i>
                        </button>
                        <button type="button" class="btn btn-default" id="cancel">
                            <i class="fa fa-close"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="G7_C33" id="LblG7_C33"><?php echo $str_nombre______S_;?></label>
                                <input type="text" class="form-control input-sm" id="G7_C33" value=""  name="G7_C33"  placeholder="<?php echo $str_nombre______S_;?>">
                            </div>
                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>          
                        <div class="col-md-6 col-xs-12">
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G7_C34" id="LblG7_C34"><?php echo $str_Orden_______S_;?></label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G7_C34" id="G7_C34" placeholder="<?php echo $str_Orden_______S_;?>">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G7_C35" id="LblG7_C35"><?php echo $str_Numcolumns__S_;?></label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G7_C35" id="G7_C35" placeholder="<?php echo $str_Numcolumns__S_;?>">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <!-- CAMPO DE TIPO LISTA -->
                            <div class="form-group">
                                <label for="G7_C36" id="LblG7_C36"><?php echo $str_apariencia__S_;?></label>
                                <select class="form-control input-sm str_Select2"  style="width: 100%;" name="G7_C36" id="G7_C36">
                                    <option value="0"><?php echo $str_seleccione; ?></option>
                                    <option value="1"><?php echo $str_seccion_a_1_s_; ?></option>
                                    <option value="2"><?php echo $str_seccion_a_2_s_; ?></option>
                                    <option value="3"><?php echo $str_seccion_a_4_s_; ?></option>
                                    <option value="4"><?php echo $str_seccion_a_3_s_; ?></option>
                                    <option value="5"><?php echo $str_seccion_a_5_s_; ?></option>
                                </select>
                            </div>
                            <!-- FIN DEL CAMPO TIPO LISTA -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <!-- CAMPO DE TIPO LISTA -->
                            <div class="form-group">
                                <label for="G7_C38" id="LblG7_C38"><?php echo $str_Tipo________S_;?></label>
                                <select class="form-control input-sm str_Select2"  style="width: 100%;" name="G7_C38" id="G7_C38">
                                    <option value="0"><?php echo $str_seleccione; ?></option>
                                    <option value="1"><?php echo $str_seccion_t_1_s_; ?></option>
                                    <option value="2"><?php echo $str_seccion_t_2_s_; ?></option>
                                    <option value="3"><?php echo $str_seccion_t_3_s_; ?></option>
                                    <option value="4"><?php echo $str_seccion_t_4_s_; ?></option>
                                </select>
                            </div>
                            <!-- FIN DEL CAMPO TIPO LISTA -->
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                            <div class="form-group">
                                <label for="G7_C37" id="LblG7_C37"></label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" name="G7_C37" id="G7_C37" data-error="Before you wreck yourself"  > <?php echo $str_minimizada__S_;?>
                                    </label>
                                </div>
                            </div>
                            <!-- FIN DEL CAMPO SI/NO -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- SI ES MAESTRO - DETALLE CREO LAS TABS --> 

            <hr/>
            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">

                    <li class="active">
                        <a href="#tab_0" data-toggle="tab" id="tabs_click_0">PREGUN</a>
                    </li>

                </ul>


                <div class="tab-content">

                    <div class="tab-pane active" id="tab_0"> 
                        <table class="table table-hover table-bordered" id="tablaDatosDetalless0" width="100%">
                        </table>
                        <div id="pagerDetalles0">
                        </div> 
                        <!--<button title="Crear PREGUN" class="btn btn-primary btn-sm llamadores" padre="'<?php //if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' " id="btnLlamar_0"><i class="fa fa-plus"></i></button>-->
                    </div>

                </div>

            </div>

            <!-- SECCION : PAGINAS INCLUIDAS -->
            <input type="hidden" name="id" id="hidId" value='0'>
            <input type="hidden" name="oper" id="oper" value='add'>
            <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
            <input type="hidden" name="padre_mientras" id="padre_mientras" value='<?php echo rand(1999, 9999);?>' >
        </form>
    </section>


<!-- iCheck -->
<link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
<script src="assets/plugins/iCheck/icheck.min.js"></script>

<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
      $(function(){

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
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

<script type="text/javascript" src="formularios/G7/G7_eventos.js"></script> 
<script type="text/javascript">
    $(function(){

        <?php if(isset($_GET['registroId'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos : 'SI', id : <?php echo $_GET['registroId']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    

                        $("#G7_C33").val(item.G7_C33);

                        $("#G7_C34").val(item.G7_C34);

                        $("#G7_C35").val(item.G7_C35);

                        $("#G7_C36").val(item.G7_C36);
 
                        $("#G7_C36").val(item.G7_C36).trigger("change"); 
    
                        if(item.G7_C37 == '1'){
                           if(!$("#G7_C37").is(':checked')){
                               $("#G7_C37").prop('checked', true);  
                            }
                        } else {
                            if($("#G7_C37").is(':checked')){
                               $("#G7_C37").prop('checked', false);  
                            }
                            
                        }

                        $("#G7_C38").val(item.G7_C38);
 
                        $("#G7_C38").val(item.G7_C38).trigger("change"); 

                        $("#G7_C60").val(item.G7_C60);
 
                        $("#G7_C60").val(item.G7_C60).trigger("change"); 
                    $("#h3mio").html(item.principal);
                    idTotal = <?php echo $_GET['registroId'];?>;

                    if ( $("#"+idTotal).length > 0) {
                        $("#"+idTotal).click();   
                        $("#"+idTotal).addClass('active'); 
                    }else{
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }
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

        $("#hidId").val(<?php echo $_GET['registroId'];?>);

        $("#TxtFechaReintento").attr('disabled', true);
        $("#TxtHoraReintento").attr('disabled', true); 

        $("#btnLlamar_0").attr('padre', <?php echo $_GET['registroId'];?>);

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET['registroId'];?>)
                        
        <?php }else{  ?>
            vamosRecargaLasGrillasPorfavor($("#padre_mientras").val());
        <?php } ?>

        //str_Select2 estos son los guiones
        


        $("#G7_C36").select2();

        $("#G7_C38").select2();

        $("#G7_C60").select2({ 
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

        $("#G7_C60").change(function(){
            var valores = $("#G7_C60 option:selected").text();
            var campos = $("#G7_C60 option:selected").attr("dinammicos");
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
        

        $("#G7_C34").numeric();
        
        $("#G7_C35").numeric();
        

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
                           
                            //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                            if($("#oper").val() == 'add'){
                                idTotal = data;
                            }else{
                                idTotal= $("#hidId").val();
                            }
                            $(".modalOculto").hide();

                          
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
                                        

                                        $("#G7_C33").val(item.G7_C33);

                                        $("#G7_C34").val(item.G7_C34);

                                        $("#G7_C35").val(item.G7_C35);

                                        $("#G7_C36").val(item.G7_C36);
  
                                        if(item.G7_C37 == 1){
                                           $("#G7_C37").attr('checked', true);
                                        } 

                                        $("#G7_C38").val(item.G7_C38);

                                        $("#G7_C60").val(item.G7_C60);

                                        $("#G7_C60").val(item.G7_C60).trigger("change"); 
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
                        

                    $.jgrid.gridUnload('#tablaDatosDetalless0'); 
                    cargarHijos_0(id);

                        $("#G7_C33").val(item.G7_C33);

                        $("#G7_C34").val(item.G7_C34);

                        $("#G7_C35").val(item.G7_C35);

                        $("#G7_C36").val(item.G7_C36);
 
                        $("#G7_C36").val(item.G7_C36).trigger("change"); 
    
                        if(item.G7_C37 == '1'){
                           if(!$("#G7_C37").is(':checked')){
                               $("#G7_C37").prop('checked', true);  
                            }
                        } else {
                            if($("#G7_C37").is(':checked')){
                               $("#G7_C37").prop('checked', false);  
                            }
                            
                        }

                        $("#G7_C38").val(item.G7_C38);
 
                        $("#G7_C38").val(item.G7_C38).trigger("change"); 

                        $("#G7_C60").val(item.G7_C60);
 
                        $("#G7_C60").val(item.G7_C60).trigger("change"); 
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
                $("#"+ rowid +"_G6_C207").val(<?php echo $_GET['yourfather'];?>);
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
            colNames:['id', 'TEXTO', 'REQUERIDO', 'TIPO','BUSQUEDA','LISTA', 'GUION', 'padre'],
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
                    name:'G6_C39', 
                    index: 'G6_C39', 
                    resizable:false, 
                    sortable:true , 
                    editable: true 
                }

                 , 
                { 
                    name:'G6_C51', 
                    index:'G6_C51', 
                    width:70 ,
                    editable: true, 
                    edittype:"checkbox",
                    editoptions: {
                        value:"1:0"
                    } 
                }


                ,
                {  
                    name:'G6_C40', 
                    index:'G6_C40', 
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosLisop_Tipos=si&idLista=45&campo=G6_C40'
                    }
                }

                
                , 
                { 
                    name:'G6_C41', 
                    index:'G6_C41', 
                    width:70 ,
                    editable: true, 
                    edittype:"checkbox",
                    editoptions: {
                        value:"1:0"
                    } 
                }
                
                ,
                {  
                    name:'G6_C44', 
                    index:'G6_C44', 
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_Guion_G6_C44=si',
                        dataInit:function(el){
                            $(el).select2({ 
                                templateResult: function(data) {
                                    var r = data.text.split('|');
                                    var row = '<div class="row">';
                                    var totalRows = 12 / r.length;
                                    for(i= 0; i < r.length; i++){
                                        row += '<div class="col-md-'+ Math.round(totalRows) +'">' + r[i] + '</div>';
                                    }
                                    row += '</div>';
                                    var $result = $(row);
                                    return $result;
                                },
                                templateSelection : function(data){
                                    var r = data.text.split('|');
                                    return r[0];
                                }
                            });
                        }
                    }
                }

                
                ,
                {  
                    name:'G6_C207', 
                    index:'G6_C207', 
                    editable: true,
                    hidden: true, 
                    editrules: {
                        edithidden:true
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
            pager: "#pagerDetalles0",
            rowList: [40,80],
            sortable: true,
            sortname: 'G6_C32',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'PREGUN',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&guion=<?php echo $_GET['yourfather'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                            $("#"+ rowid +"_G6_C32").change(function(){
                                var valores =$("#"+ rowid +"_G6_C32 option:selected").text();
                                var campos =  $("#"+ rowid +"_G6_C32 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G6_C48").change(function(){
                                var valores =$("#"+ rowid +"_G6_C48 option:selected").text();
                                var campos =  $("#"+ rowid +"_G6_C48 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G6_C49").change(function(){
                                var valores =$("#"+ rowid +"_G6_C49 option:selected").text();
                                var campos =  $("#"+ rowid +"_G6_C49 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G6_C43").change(function(){
                                var valores =$("#"+ rowid +"_G6_C43 option:selected").text();
                                var campos =  $("#"+ rowid +"_G6_C43 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G6_C44").change(function(){
                                var valores =$("#"+ rowid +"_G6_C44 option:selected").text();
                                var campos =  $("#"+ rowid +"_G6_C44 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G6_C207").change(function(){
                                var valores =$("#"+ rowid +"_G6_C207 option:selected").text();
                                var campos =  $("#"+ rowid +"_G6_C207 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                }
                lastSels = rowid;
            }
            ,

            ondblClickRow: function(rowId) {
                /*$("#frameContenedor").attr('src', 'http://<?php// echo $_SERVER["HTTP_HOST"];?>/crm_CentroDiesel/new_index.php?formulario=6&view=si&registroId='+ rowId +'&formaDetalle=si&yourfather='+ idTotal +'&pincheCampo=32&formularioPadre=7');
                $("#editarDatos").modal('show');*/

            }
        }); 

        $('#tablaDatosDetalless0').navGrid("#pagerDetalles0", { add:false, del: true , edit: false });


        $('#tablaDatosDetalless0').inlineNav('#pagerDetalles0',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: false, 
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

                        $("#G7_C33").val(item.G7_C33);

                        $("#G7_C34").val(item.G7_C34);

                        $("#G7_C35").val(item.G7_C35);

                        $("#G7_C36").val(item.G7_C36);
 
                        $("#G7_C36").val(item.G7_C36).trigger("change"); 
    
                        if(item.G7_C37 == '1'){
                           if(!$("#G7_C37").is(':checked')){
                               $("#G7_C37").prop('checked', true);  
                            }
                        } else {
                            if($("#G7_C37").is(':checked')){
                               $("#G7_C37").prop('checked', false);  
                            }
                            
                        }

                        $("#G7_C38").val(item.G7_C38);
 
                        $("#G7_C38").val(item.G7_C38).trigger("change"); 

                        $("#G7_C60").val(item.G7_C60);
 
                        $("#G7_C60").val(item.G7_C60).trigger("change"); 
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
        

            $.jgrid.gridUnload('#tablaDatosDetalless0'); 
            cargarHijos_0(id);
    }
</script>

