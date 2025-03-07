

<!-- CAMPOS PARA EDICION Y CONOCER QUE OPERACION ESTAMOS REALIZANDO, INSERT, UPDATE, DELETE-->
                                                <input type="hidden" name="id" id="hidId" value='0'>
                                                <input type="hidden" name="oper" id="oper" value='add'>
                                                <input type="hidden" name="calidad" id="calidad" value='0'>
                                                <input type="hidden" name="Padre" id="Padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                                <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                                <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                                                <input type="hidden" name="estareabackoffice" id="estareabackoffice" value='<?php if(isset($_GET['tareabackoffice'])){ echo $_GET['tareabackoffice']; }else{ echo "0"; }?>' >
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- FIN  CUERPO DEL FORMULARIO CAMPOS -->
                        </div>
                <?php if(!isset($_GET['view'])){ ?>
                    </div>    
                </div>
            </div>
                <!-- FIN SECCION : VISTA FORMULARIO -->

                <!-- SECCION : VISTA HOJA DE DATOS -->
                <div class="tab-pane" id="tab_2-2"  style="display: none;">
                    <table class="table table-hover table-bordered" id="tablaDatos" width="100%"></table>
                    <div id="pager"></div>    
                </div>


                <!-- FIN SECCION : HOJA DE DATOS -->
            </div>
            <!-- FIN SECCION : CONTENIDOS -->
        </div>


        <?php }else{    ?>
        
            </div>
        </div>
        <?php } ?>  
    </div>
</div>


<script src="assets/js/moment.min.js"></script>
<script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="assets/plugins/DateTimePicker/bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="assets/plugins/DateTimePicker/bootstrap-datetimepicker.min.js"></script>
<script src="assets/plugins/BlockUi/jquery.blockUI.js"></script>
<script src="assets/js/alertify.js"></script>
<?php if(!isset($_GET['view'])) { ?>
<?php }?>

<!-- FastClick -->
<script src="assets/plugins/fastclick/fastclick.js"></script>



<link rel="stylesheet" href="assets/plugins/WinPicker/dist/wickedpicker.min.css">
<script type="text/javascript" src="assets/plugins/WinPicker/dist/wickedpicker.min.js"></script>
<script src="assets/plugins/inputmask5.0.5/jquery.inputmask.min.js"></script>
<script src="assets/js/validator.js"></script>

<script type="text/javascript" src="assets/plugins/select2/select2.min.js" ></script>
<link type="text/css" rel="stylesheet" href="assets/plugins/select2/select2.min.css" />  

<script type="text/javascript">
    var idTotal = 0;
    var inicio = 50;
    var fin = 50;
    var strSessionCargo_t = "<?php if(isset($_SESSION["CARGO"])){ echo $_SESSION["CARGO"]; }else{ echo 0; }?>"; 
    var strHTMLOpcionesCampos_t = '';
    strHTMLOpcionesCampos_t = traerCamposDelGuion($("#inpIdFormulario").val());
    const tareaBackOffice = <?php echo isset($_GET['tareabackoffice']) ? $_GET['tareabackoffice'] : 0  ?>;

    $(document).ajaxStart(function() {
        console.log("se ejecuto un ajax");
        if (tareaBackOffice) {
            return
        } else {
            try {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se cierra la gestión'
                });
            } catch (error) {
                
            }
        }
    });
    
    $( document ).ajaxError(function( event, request, settings ) {
        var url=settings.url.substr(0,38);
        if(url !='formularios/generados/PHP_Ejecutar.php'){
            try {
                $.unblockUI();
            } catch (error) {
                
            }
        }else{
            try {
                alertify.error( "Error en esta solicitud:" + settings.url);            
            } catch (error) {
    
            }
        }        
        console.log( "Error en esta solicitud:" + settings.url);
    });  
    
    $( document ).ajaxComplete(function( event, request, settings ) {
        var url=settings.url.substr(0,38);
        if(url !='formularios/generados/PHP_Ejecutar.php'){
            try {
                $.unblockUI();
            } catch (error) {
                
            }
        }else{
            try{
                alertify.success( "Exito en la solicitud");
            }catch(e){
            }
        }
        console.log( "Exito en la solicitud:" + settings.url);
    });

    function longitud(id,quitar=null){
        var longitud=$("#"+id).attr('maxlength');
        var total=$("#"+id).val().length;
        if(document.getElementById("Totalcaracter")){
            $("#Totalcaracter").html(total+" de "+longitud+" Caracteres permitidos");
        }else{
            if($("#"+id).parent().hasClass('form-group')){
                $("#"+id).parent().append("<span id='Totalcaracter' style='opacity:0.5;'>"+total+" de "+longitud+" Caracteres permitidos</span>");
            }else{
                $("#"+id).parent().parent().append("<span id='Totalcaracter' style='opacity:0.5;'>"+total+" de "+longitud+" Caracteres permitidos</span>");
            }
        }
        if(quitar){
            $("#Totalcaracter").remove();
        }
    }

    function deleteAllEstpas(id){
        $.ajax({
            url      : 'formularios/pies_CRUD.php',
            type     : 'POST',
            data     : { id : id , bd: '<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo 0;} ?> ' , deleteAllEstrat : 'si'},
            dataType : 'json',
            success  : function(data){
                if(data == 1){   
                    //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo     
                    try{
                        busqueda_lista_navegacion();
                        seleccionar_registro();
                    }catch{
                        
                    }
                        
                }else{
                    //Algo paso, hay un error
                    alert(data);
                }
            } 
        });   
    }

    $(function(){ 
        Inputmask.extendAliases({
        pesos: {
            prefix: "$ ",
            groupSeparator: ",",
            alias: "numeric",
            autoGroup: true,
            placeholder: '0.0',
            digitsOptional: false,
            clearMaskOnLostFocus: false,
            rightAlign:false
        }
        });

        $('.Fecha').inputmask('9999-99-99');

        $('.moneda').each(function(i){
            let digitos=$(this).attr('digitos');
            $(this).inputmask({
                alias:'pesos',
                digits: digitos
            });
        });
        
        $('#errorGestion').click(function(){
            try{
                cambiarRegistro();
            }catch{
                var data={
                    accion:"cambiarRegistro"
                };
                parent.postMessage(data, '*');
            }
        });

        $("#resetFiltradorAvanzado").click(function(){
            $(".rows").each(function(i){
                if ($(this).attr("id")=="row_1") {
                    $("#selCampo_1").val(0).trigger("change");
                    $("#divValor_1").html('<input type="text" class="form-control input-sm" id="valor_1" name="valor_1" placeholder="VALOR">');
                    $("#selOperador_1").html('<option value="0">Seleccione</option>');
                }else{
                    $(this).remove();
                }
            });
        });

        $("#selCampo_1").html(strHTMLOpcionesCampos_t);

        changeCampoFiltro();

        //JDBD-2020-05-03 : Realizamos el llamdo de la funcion de cada Guion "llenarListaNavegacion" para realizar el respectivo filtro.
        $("#BuscarAvanzado").click(function(){

            //JDBD-2020-05-03 : Subimos el scroll de las listas de la izquierda.
            $("#txtPruebas").scrollTop(0);

            inicio = 50;
            fin = 50;

            var intErrores_t = 0;

            //JDBD-2020-05-03 : Validamos que todas las listas CAMPO de las nuevas filas agregadas en el filtrador avanzado SI esten seleccionando algun campo a filtrar.
            $(".campoFiltro").each(function(i){
                if ($(this).val() == "0") {
                    intErrores_t++;
                    alertify.error("Debe seleccionar el campo a filtrar.");
                    $(this).closest(".form-group").addClass("has-error");
                }else{
                    $(this).closest(".form-group").removeClass("has-error");
                }
            });

            //JDBD-2020-05-03 : Si todo estabien llamamos la funcion del Guion para realizar el filtro.
            if (intErrores_t == 0) {
                llenarListaNavegacion("no",0,0);
            }

        });

        $("#btnNuevoFiltro").click(function(){

            var intCantFiltros = Number($("#inpCantFiltros").val())+1;
            var intIdFormulario_t = $("#inpIdFormulario").val();

            //JDBD-2020-05-03 : Armamos el HTMML para las filas del filtrador avanzado que vamos añadiendo con el boton verde "Nuevo Filtro"; 
            var strHTML_t = ''; 
            strHTML_t += '<div class="row rows" id="row_'+intCantFiltros+'" numero="'+intCantFiltros+'">';
                strHTML_t += '<div class="col-md-2 col-xs-2">';
                    strHTML_t += '<div class="form-group">';
                        strHTML_t += '<select class="form-control input-sm" name="selCondicion_'+intCantFiltros+'" id="selCondicion_'+intCantFiltros+'">';
                        strHTML_t += '<option value="AND">Y</option>';
                        strHTML_t += '<option value="OR">O</option>';
                        strHTML_t += '</select>';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-4 col-xs-4">';
                    strHTML_t += '<div class="form-group">';
                        strHTML_t += '<select class="form-control input-sm campoFiltro" name="selCampo_'+intCantFiltros+'" id="selCampo_'+intCantFiltros+'" numero="'+intCantFiltros+'">';
                        strHTML_t += traerCamposDelGuion(intIdFormulario_t);
                        strHTML_t += '</select>';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-2 col-xs-2">';
                    strHTML_t += '<div class="form-group">';
                        strHTML_t += '<select class="form-control input-sm" name="selOperador_'+intCantFiltros+'" id="selOperador_'+intCantFiltros+'">';
                        strHTML_t += '<option value="0">Seleccione</option>';
                        strHTML_t += '</select>';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-3 col-xs-3">';
                    strHTML_t += '<div class="form-group" id="divValor_'+intCantFiltros+'">';
                        strHTML_t += '<input type="text" class="form-control input-sm" id="valor_'+intCantFiltros+'" name="valor_'+intCantFiltros+'" placeholder="VALOR">';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-1 col-xs-1">';
                    strHTML_t += '<div class="form-group">';
                    strHTML_t += '<button class="form-control btn btn-danger btn-sm EliminarFiltro" type="button" id="btnQuitarFiltro_'+intCantFiltros+'" numero="'+intCantFiltros+'"><i class="fa fa-trash-o"></i></button>';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<input type="hidden" id="tipo_'+intCantFiltros+'" name="tipo_'+intCantFiltros+'" value="0">';
            strHTML_t += '</div>';

            $("#divFiltros").append(strHTML_t);

            $("#inpCantFiltros").val(Number($("#inpCantFiltros").val())+1);

            //JDBD-2020-05-03 : Le damos funcionalidad de eliminacion a los botones rojos de las filas nuevas que vamos añadiendo con el boton verde "Nuevo Fitro"
            eliminarFiltro();

            //JDBD-2020-05-03 : Le damos funcionalidad a las listas CAMPO de las nuevas filas del filtrador avanzado que vamos añadiendo con el boton verde "Nuevo Filtro", para que altero los input del filtrador avanzada "OPERADOR" y "VALOR".
            changeCampoFiltro();

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
                if($(this).is(':checkbox') || $(this).is(':radio') ){
                    if($(this).is(':checked')){
                        $(this).attr('checked', false);
                    }
                    $(this).attr('disabled', false); 
                }else{
                    $(this).val('');
                    $(this).attr('disabled', false); 
                }
                               
            });

            $(".modalOculto").show();
            /*$("#FormularioDatos :select").each(function(){
                $(this).val(0);
                $(this).attr('disabled', false);
            });

            $("#FormularioDatos :tetxarea").each(function(){
                $(this).val('');
                $(this).attr('disabled', false);
            })*/
           
            $("#hidId").val(0);
            $(".TxtFechaReintento").attr('disabled', true);
            $(".TxtHoraReintento").attr('disabled', true); 
             //Le informa al crud que la operaciòn a ejecutar es insertar registro
           // $("#oper").val('add');
            document.getElementById('oper').value = "add";

           
        });



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

            //Perisistir los estados de los saltos
            $('#FormularioDatos :input').each(function(){
                try {
                    $(this).change();
                } catch (error) {}
            });

            $(".modalOculto").show();

            $(".TxtFechaReintento").attr('disabled', true);
            $(".TxtHoraReintento").attr('disabled', true);
            try{
                if($('input[placeholder="NOMBRE_AUDITOR_Q_DY"]').val()== ''){
                    $('input[placeholder="NOMBRE_AUDITOR_Q_DY"]').val('<?php if(isset($_SESSION["NOMBRES"])){echo $_SESSION["NOMBRES"];}else{echo "";} ?>');
                }
                if($('input[nombre="FECHA_AUDITADO_Q_DY"]').val()== ''){
                    $('input[nombre="FECHA_AUDITADO_Q_DY"]').val('<?php if(isset($_SESSION["NOMBRES"])){echo date('Y-m-d H:i:s');}else{echo "";} ?>');
                }
            }catch{
               console.error(error); 
            }
                         
            try{
                CalcularFormula();
            }catch (error) {
                console.error(error);  
            }
        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function(){
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);
            $(".modalOculto").hide();
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function(){
            $("#oper").val('del');
            //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
            alertify.confirm("¿Está seguro de eliminar el registro seleccionado?", function (e) {
                //Si la persona acepta
                if (e) {
                    $.ajax({
                        url      : 'formularios/pies_CRUD.php?getTipoGuion=si',
                        type     : 'POST',
                        data     : { id : '<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo 0;} ?> '},
                        success  : function(data){
                            if(data == 2){
                                //Si el reultado es 2, ES PORQUE ES UNA BD
                                alertify.confirm("Este registro también será eliminado de todos las pasos donde se encuentre ¿Desea continuar?", function (e) {
                                    if(e){
                                        let id = $("#hidId").val();
                                        deleteAllEstpas(id);
                                        //se envian los datos, diciendo que la oper es "del"
                                        $.ajax({
                                            url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                                            type     : 'POST',
                                            data     : { id : id , oper : 'del'},
                                            dataType : 'json',
                                            success  : function(data){
                                                if(data == 1){   
                                                    //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo     
                                                    try{
                                                        busqueda_lista_navegacion();
                                                        seleccionar_registro();
                                                    }catch{
                                                        
                                                    }
                                                     
                                                }else{
                                                    //Algo paso, hay un error
                                                    alert(data);
                                                }
                                            } 
                                        });
                                    }else {
                                        $("#oper").val(0);    
                                    }
                                });
                            }else{
                                if(data == 1){
                                    let id = $("#hidId").val();
                                    //se envian los datos, diciendo que la oper es "del"
                                    $.ajax({
                                        url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                                        type     : 'POST',
                                        data     : { id : id , oper : 'del'},
                                        dataType : 'json',
                                        success  : function(data){
                                            if(data == 1){   
                                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo     
                                                try{
                                                    busqueda_lista_navegacion();
                                                    seleccionar_registro();
                                                }catch{
                                                    
                                                }
                                                 
                                            }else{
                                                //Algo paso, hay un error
                                                alert(data);
                                            }
                                        } 
                                    });
                                }else{
                                    alertify.error(data);
                                }
                            }
                        } 
                    });
                } else {
                    $("#oper").val(0);    
                }
            }); 
        });


        //FUNCIONALIDAD BOTON VER JOURNEY

        <?php if (!isset($_GET["view"])) {
        // SI NO VIENE EL PARAMETRO VIEW QUIERE DECIR QUE ES DESDE BACKOFFICE
        ?>

        $("#verDatosJourney").click(() => {
            const idG = $("#inpIdFormulario").val();
            const idUser = $(".CargarDatos.active").attr("id");
            $("#ifrDatosJourney").attr("src", `../manager/cruds/DYALOGOCRM_SISTEMA/G5/herramientas/POODataJourney/journeyView.php?idG=${idG}&idUser=${idUser}`);
            $("#modDatosJourney").modal()

        })
        <?php } else {
            // SI LLEGA EL PARAMETRO formularioPadre QUIERE DECIR QUE ES UN SUBFORM
            if(isset($_GET["formularioPadre"])){ ?>
                $("#verDatosJourney").click(() => {
                const idG = $("#formhijo").val();
                const idUser = $("#hidId").val();
                $("#ifrDatosJourney").attr("src", `../manager/cruds/DYALOGOCRM_SISTEMA/G5/herramientas/POODataJourney/journeyView.php?idG=${idG}&idUser=${idUser}`);
                $("#modDatosJourney").modal()
            })

        <?php }else{ ?>

            $("#verDatosJourney").click(() => {
                const idG = $("#script").val();
                const idUser = $("#codigoMiembro").val();
                $("#ifrDatosJourney").attr("src", `../manager/cruds/DYALOGOCRM_SISTEMA/G5/herramientas/POODataJourney/journeyView.php?idG=${idG}&idUser=${idUser}&from=estacion`);
                $("#modDatosJourney").modal()

            })

        <?php }} ?>

        //FUNCIONALIDAD DEL BOTÓN CANCELARAGENDA
        $("#cancelAgenda").click(function(){
            $.ajax({
                url:"formularios/agendador/agendador_CRUD.php",
                method:'POST',
                dataType:"json",
                data:{getAgendador:'si',id:'<?php if(isset($_GET['formulario'])){echo $_GET['formulario'];}else{ echo "";}?>'},
                success:function(data){
                    if(data.estado){
                        cancelAgenda(data.mensaje);
                    }else{
                        alertify.error("No se pudo cancelar la cita");
                    }
                },
                beforeSend:function(){
                    try {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se cierra la gestión'
                        });
                    } catch (error) {
                        
                    }
                },
                complete:function(){
                    try {
                        $.unblockUI();
                    } catch (error) {
                        
                    }
                },
                error:function(){
                    alertify.error("Se genero un error al procesar la solicitud");
                }
            });
        });

        function cancelAgenda(idAgendador){
            $.ajax({
                url:"formularios/agendador/agendador_CRUD.php",
                method:'POST',
                dataType:"json",
                data:{cancelarCita:'si',idAgendador:idAgendador, idCita:"<?php if(isset($_GET['registroId'])){ echo $_GET['registroId'];}else{echo "";}?>"},
                success:function(data){
                    if(data.response){
                        alertify.success(data.message);
                    }else{
                        alertify.error("No se pudo cancelar la cita");
                    }
                },
                beforeSend:function(){
                    try {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se cierra la gestión'
                        });
                    } catch (error) {
                        
                    }
                },
                complete:function(){
                    try {
                        $.unblockUI();
                    } catch (error) {
                        
                    }
                },
                error:function(){
                    alertify.error("Se genero un error al procesar la solicitud");
                }
            });
        }

        $('.reintento').change(function(){
            if($(this).val() == 2){
                $(".TxtFechaReintento").attr('disabled', false);
                $(".TxtHoraReintento").attr('disabled', false);   
            }else{
                $(".TxtFechaReintento").attr('disabled', true);
                $(".TxtHoraReintento").attr('disabled', true);   
            }
        });


        $(".tipificacion").change(function(){
            let id = $(this).attr('id');
            let valor = $("#"+ id +" option:selected").attr('efecividad');
            let monoef = $("#"+ id +" option:selected").attr('monoef');
            let TipNoEF = $("#"+ id +" option:selected").attr('TipNoEF');
            let cambio = $("#"+ id +" option:selected").attr('cambio');
            let importancia = $("#"+ id + " option:selected").attr('importancia');
            let contacto = $("#"+id+" option:selected").attr('contacto');
            $(".reintento").val(TipNoEF).change();
            $("#Efectividad").val(valor);
            $("#MonoEf").val(monoef);
            $("#TipNoEF").val(TipNoEF);
            $("#MonoEfPeso").val(importancia);
            $("#ContactoMonoEf").val(contacto);
            
            if(cambio != '-1'){
                $(".reintento").attr('disabled', true);
            }

            if($(this).val()=='0'){
                $(".reintento").val('0').change();
                $(".reintento").attr('disabled', true);
            }
        });

        $(".tipificacion").change();
       
        
        $("#txtPruebas").on('scroll', function() {


            
            <?php 

                if (!isset($idUsuario)) {
                    $idUsuario="";
                }


             ?>

            //scroll panel izquierdo
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

                // Estas variables se utilizan para cuando la consulta es de tarea de backoffice
                var tareaBackoffice = <?php echo (isset($tareaBackoffice)) ? $tareaBackoffice : 0; ?>;
                var muestra = <?php echo (isset($muestra)) ? $muestra : 0; ?>;
                var tareaTipoDist = <?php echo (isset($tipoDistribucion)) ? $tipoDistribucion : 0; ?>;

                //JDBD-2020-05-03 : Si el filtro es con el filtrador avanzado, llamamos la funcion del Guion "llenarListaNavegacion".
                if ($("#selCampo_1").val() != "0") {

                    llenarListaNavegacion("si",inicio,fin);
                    inicio += fin;

                }else{
                   $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si',idUsuario: "<?php echo $idUsuario; ?>", tareaBackoffice:tareaBackoffice, muestra:muestra, tareaTipoDist:tareaTipoDist, B : $("#table_search_lista_navegacion").val()}, function(data){
                        if(data != ""){
                            $("#tablaScroll").append(data);
                            inicio += fin;
                            busqueda_lista_navegacion();
                        }
                    });
                }




            }
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


        $(".TxtFechaReintento").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        
         //Timepicker
        var opciones = { //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: 'Hora agenda', //The Wickedpicker's title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker's input clearable (has clickable "x")
        }; 
        $(".TxtHoraReintento").wickedpicker(opciones);
        
        //tab delformulario
        $("#tabFormulario").click(function(){
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        });

        //tab de hoja de datos
        $("#tabHojaDeDatos").click(function(){
            $.jgrid.gridUnload('#tablaDatos'); 
            cargar_hoja_datos();
        });
        //datos Hoja de busqueda
        // const callResponse = () => {
        //     llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        // }

        $("#BtnBusqueda_lista_navegacion").click(() => {
            $("#txtPruebas").scrollTop(0);
            // handleResponse()
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
            inicio = 50;
            fin = 50;
        });

        // const handleResponse = () => {
        //     setInterval(callResponse, 9000)
        // }
        
        //Caja de texto de bus queda
        $("#table_search_lista_navegacion").keypress(function(e){
            if(e.keyCode == 13)
            {
                //alert("hola");
                llenar_lista_navegacion($(this).val());
            }
        });

        //preguntar cuando esta vacia la tabla para dejar solo los botones correctos habilitados
        var g = $("#tablaScroll").html();
        if(g === ''){
           $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true); 
        }

        <?php if(!isset($campSql) && !isset($_GET['view'])) { ?>
            //cargar_hoja_datos();
        <?php } ?>
            
         //cuando se invoca en modo contact center, acà inicializa los campos que vienen de la BD
         //cuando se invoca en modo contact center, acà inicializa los campos que vienen de la BD
        <?php
            if(isset($campSql)){
                //recorro la campaña para tener los datos que necesito
                // echo "campSql => $campSql <br>";
                $auxiliar='';
                $resultcampSql = $mysqli->query($campSql);

                function characterCut($cadena, $intLongitudCampo)
                {
                    # se parsea el valor a int
                    # se cuenta la Longitud de la variable[cadena] 
                    $intLongitudCampo = intval($intLongitudCampo);
                    $intCadenaLength = mb_strlen($cadena) - $intLongitudCampo;
                    if (!$intCadenaLength == 0 && $intLongitudCampo > 0 && mb_strlen($cadena) > $intLongitudCampo) {
                        # Solo se corta la cadena que llega de la BD, cuando sea mayor a la longitud, configurada en pregun
                        $cadena = mb_substr($cadena, -0, -$intCadenaLength);
                    }
                    return $cadena;
                }

                while($key = $resultcampSql->fetch_object()){
                    
                    //Preguntar por el tipo de dato
                    $Lsql = "SELECT PREGUN_Tipo______b, PREGUN_Texto_____b AS nombre_texto, PREGUN_Longitud__b AS longitud_pregun FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b = {$key->CAMINC_ConsInte__CAMPO_Gui_b}";
                    $res = $mysqli->query($Lsql);
                    $datos = $res->fetch_array();


                    //consulta de datos del usuario
                    $DatosSql = " SELECT ".$key->CAMINC_NomCamPob_b." as campo FROM ".$BaseDatos.".G".$tabla." WHERE G".$tabla."_ConsInte__b=".$_GET['user'];

                    //recorro la tabla de donde necesito los datos
                    $resultDatosSql = $mysqli->query($DatosSql);

                    if($resultDatosSql){
                        while($objDatos = $resultDatosSql->fetch_object()){ 

                            if(!is_null($objDatos->campo) && $objDatos->campo != ''){

                            $objDatos->campo = str_replace(PHP_EOL, " ",$objDatos->campo);
                            $objDatos->campo = preg_replace("/[\r\n|\n|\r]+/", " ", $objDatos->campo);
                            
                            if($datos['PREGUN_Tipo______b'] == 1 || $datos['PREGUN_Tipo______b'] == 14){
                                $objDatos->campo = characterCut($objDatos->campo, $datos['longitud_pregun']);
                            }
                            
                            ?>

                                if($('#<?php echo $key->CAMINC_NomCamGui_b;?>').length){

                                    <?php switch ($datos['PREGUN_Tipo______b']) {
                                        case '8':

                                            if($objDatos->campo == '1'){
                                                echo "$('#".$key->CAMINC_NomCamGui_b."').prop('checked', true);";
                                            }else{
                                                echo "$('#".$key->CAMINC_NomCamGui_b."').prop('checked', false);";
                                            }

                                            break;

                                        case '15':

                                            echo "var strValor_t = '".addslashes(trim($objDatos->campo))."';";

                                            echo "if(strValor_t != ''){";

                                                echo "$('#down".$key->CAMINC_NomCamGui_b."').attr('adjunto',strValor_t);";
                                                echo "var lenURL_t = strValor_t.split('/');";
                                                echo "$('#down".$key->CAMINC_NomCamGui_b."').val(lenURL_t[lenURL_t.length - 1]);";
                                                echo "$('#".$key->CAMINC_NomCamGui_b."').val(strValor_t);";

                                            
                                            echo "}else{";

                                                echo "$('#down".$key->CAMINC_NomCamGui_b."').attr('adjunto','');";
                                                echo "$('#down".$key->CAMINC_NomCamGui_b."').val('Sin Adjunto');";
                                                echo "$('#".$key->CAMINC_NomCamGui_b."').val('');";


                                            echo "}";
                                            
                                            break;

                                        case '11':
                                            $auxiliar.="$('#".$key->CAMINC_NomCamGui_b."').val('".$objDatos->campo."');";
                                            $auxiliar.="$('#".$key->CAMINC_NomCamGui_b."').attr('opt','".$objDatos->campo."').change();"; ?>
                                    
                                            <?php break;    

                                        case '6':
                                            
                                            echo "$('#".$key->CAMINC_NomCamGui_b."').attr('opt','".$objDatos->campo."');
                                            if($('#".$key->CAMINC_NomCamGui_b."').attr('valStorage')){
                                                $('#".$key->CAMINC_NomCamGui_b."').val($('#".$key->CAMINC_NomCamGui_b."').attr('valStorage')).trigger('change');
                                                setTimeout(function()".'{$'."('#".$key->CAMINC_NomCamGui_b."').change()},1000);
                                            }else{
                                                $('#".$key->CAMINC_NomCamGui_b."').val('".$objDatos->campo."').trigger('change');
                                                setTimeout(function()".'{$'."('#".$key->CAMINC_NomCamGui_b."').change()},1000);
                                            }";
                                            break;
                                        
                                        default:

                                                echo "try{";

                                                    echo "if($('#".$key->CAMINC_NomCamGui_b."').attr('valStorage')){
                                                        $('#".$key->CAMINC_NomCamGui_b."').val($('#".$key->CAMINC_NomCamGui_b."').attr('valStorage'));
                                                    }else{
                                                        $('#".$key->CAMINC_NomCamGui_b."').val('".addslashes(trim($objDatos->campo))."');
                                                    }";

                                                echo "}catch(err){";
                                                    echo "$('#".$key->CAMINC_NomCamGui_b."').val('-ERR-');";
                                                echo "}";

                                            break;
                                    } ?>
                                }
                            <?php
                            }
                        }
                    }
                    
                }   
            }
        ?>

        <?php if(!isset($_GET['view'])) { ?>
            //SECCION : INVOCACION FUNCIONES DE LA lISTA DE NAVEGACION 
            //Funcionalidades de la Lista de navegacion
            busqueda_lista_navegacion();
            //Esto se encarga de seleccionar el primer registro de la tabla
            $(".CargarDatos :first").click();
        <?php } else { ?>
            $(".TxtFechaReintento").attr('disabled', true);
            $(".TxtHoraReintento").attr('disabled', true);
        <?php } ?>

        // LLAMAR A LA FUNCIÓN DEL STORAGE QUE AGREGA EL EVENTO CHANGE() A LOS CAMPOS DEL FORMULARIO
        try {
            agregarEventos();
        } catch (e) {
            console.warn(e);
        }
    });

/**
* JDBD-2020-05-03 : Esta funcion hace que al precionar el boton rojo del filtrador avanzado elimine la fila del filtrdor avanzado
* mas los campos de operacion de la misma fila donde esta el boton rojo precionado.
*/
function eliminarFiltro(){

    $(".EliminarFiltro").click(function(){

        var intNumCampo_t = $(this).attr("numero");

        $("#row_"+intNumCampo_t).remove();

    });

}

/**
* JDBD-2020-05-03 : Esta funcion afecta los campos OPERADOR y VALOR del filtrador avanzado dependiendo el tipo de campo seleccionado en la lista de CAMPO,
* cuando el campo seleccionado en la lista de CAMPO es de tipo lista convierte el input de VALOR del filtrador avanzado en un select, cuando es fecha, pone un 
* input con calendario en el input VALOR del filtrador avanzado. Tambien dependiendo el tipo de campo seleccionado de la lista de CAMPO del filtrador avanzado,
* la lista de OPERADOR del filtrador avanzado quita o añade operadores como MAYOR QUE, MENOR QUE, estos solo aplicaria para tipos numericos.  
*/
function changeCampoFiltro(){

    $(".campoFiltro").change(function(){
        var intIdCampo_t = $(this).val();
        var intNumCampo_t = $(this).attr("numero");
        var intTipo_t = Number($("#selCampo_"+intNumCampo_t+" option:selected").attr("tipo"));

        $("#tipo_"+intNumCampo_t).val(intTipo_t);

        var strHTMLValor_t = '';

        var strHTMLOperador_t = '<option value="=" selected>IGUAL A</option>';    
            strHTMLOperador_t += '<option value="!=">DIFERENTE DE</option>';    

        if (intTipo_t==6) {

            strHTMLValor_t += '<select class="form-control input-sm" name="valor_'+intNumCampo_t+'" id="valor_'+intNumCampo_t+'">';
            strHTMLValor_t += traerOpcionesLista(intIdCampo_t);
            strHTMLValor_t += '</select>';

            $("#divValor_"+intNumCampo_t).html(strHTMLValor_t);

        }
        if (intTipo_t==10) {

            strHTMLOperador_t += '<option value=">">MAYOR QUE</option>';
            strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

            strHTMLValor_t += '<input type="text" class="form-control input-sm Hora hasWickedpicker" name="valor_'+intNumCampo_t+'" id="valor_'+intNumCampo_t+'" placeholder="HH:MM:SS" onkeypress="return false;" aria-showingpicker="false" tabindex="0">';

            $("#divValor_"+intNumCampo_t).html(strHTMLValor_t);

            $("#valor_"+intNumCampo_t).wickedpicker({ 
                twentyFour: true,
                title: 'HORAS',
                showSeconds: true,
                secondsInterval: 1,
                minutesInterval: 1,
                beforeShow: null,
                show: null,
                clearable: false,
                format: 'hh:mm:ss'
            });


        }
        if (intTipo_t==5) {

            strHTMLOperador_t += '<option value=">">MAYOR QUE</option>';
            strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

            strHTMLValor_t += '<input readonly type="text" class="form-control input-sm" name="valor_'+intNumCampo_t+'" id="valor_'+intNumCampo_t+'" placeholder="YYYY-MM-DD">';

            $("#divValor_"+intNumCampo_t).html(strHTMLValor_t);

            $("#valor_"+intNumCampo_t).datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });

        }
        if (intTipo_t==4) {

            strHTMLOperador_t += '<option value=">">MAYOR QUE</option>';
            strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

            strHTMLValor_t += '<input type="text" class="form-control input-sm Decimal" name="valor_'+intNumCampo_t+'" id="valor_'+intNumCampo_t+'" placeholder="DECIMALES">';

            $("#divValor_"+intNumCampo_t).html(strHTMLValor_t);

            $("#valor_"+intNumCampo_t).numeric({ decimal : ".",  negative : false, scale: 4 });

        }
        if (intTipo_t==3) {

            strHTMLOperador_t += '<option value=">">MAYOR QUE</option>';
            strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

            strHTMLValor_t += '<input type="text" class="form-control input-sm Decimal" name="valor_'+intNumCampo_t+'" id="valor_'+intNumCampo_t+'" placeholder="NUMERIC">';

            $("#divValor_"+intNumCampo_t).html(strHTMLValor_t);

            $("#valor_"+intNumCampo_t).numeric({ decimal : ".",  negative : false, scale: 4 });

        }
        if (intTipo_t==1 || intTipo_t==2 || intTipo_t==14) {

            strHTMLOperador_t += '<option value="LIKE_1">INICIE POR</option>';
            strHTMLOperador_t += '<option value="LIKE_2">CONTIENE</option>';
            strHTMLOperador_t += '<option value="LIKE_3">TERMINE EN</option>';

            strHTMLValor_t += '<input type="text" class="form-control input-sm" id="valor_'+intNumCampo_t+'" name="valor_'+intNumCampo_t+'" placeholder="TEXT">';

            $("#divValor_"+intNumCampo_t).html(strHTMLValor_t);

        }

        $("#selOperador_"+intNumCampo_t).html(strHTMLOperador_t);

    });

}

/**
* JDBD-2020-05-03 : Se trae las opciones de la lista seleccionada en el select de campos.
* @param Integer - Id de el campo en la tabla PREGUN.
* @return HTML - Se trae las opcicones en formato html de las opciones del campo tipo lista. 
*/
function traerOpcionesLista(intIdCampo_p){

    var strHTMLOpcionesLista_t = $.ajax({
                                    url      : 'formularios/pies_CRUD.php?traerOpcionesLista=true',
                                    type     : 'POST',
                                    data     : {intIdCampo_t : intIdCampo_p},
                                    dataType : 'html',
                                    context  : document.body,
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;

    return strHTMLOpcionesLista_t;

}

 /**
 * JDBD-2020-05-03 : Se trae en forma de lista todos los campos del guion.
 * @return HTML - Opciones para un select de los campos del guion.
 */
function traerCamposDelGuion(intIdGuion_p) {

    if (strHTMLOpcionesCampos_t == '') {

        strHTMLOpcionesCampos_t = $.ajax({
                                    url      : 'formularios/pies_CRUD.php?traerCamposDelGuion=true',
                                    type     : 'POST',
                                    data     : {intIdGuion_t : intIdGuion_p, strSessionCargo_t : strSessionCargo_t},
                                    dataType : 'html',
                                    context  : document.body,
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;
        
    }

    return strHTMLOpcionesCampos_t;

}

var EventChange=sessionStorage.getItem("gestiones");
if (EventChange !== null && EventChange !== "" && EventChange !== false && EventChange !== undefined) {    
    EventChange = JSON.parse(EventChange);
    setTimeout(function(){
        $.each(EventChange,function(i,item){
            if(typeof(item) == 'object'){
                if(item.hasOwnProperty('id_gestion') && item.id_gestion == $("#CampoIdGestionCbx").val()){
                    if(item.hasOwnProperty('RegistroInsertado')){
                        //VALIDAR QUE EL ID INSERTADO SI CORRESPONDA AL QUE ES, ESTO LO HAGO PARA EVITAR ACTUALIZAR UN REGISTRO ANTIGUO
                        $.ajax({
                            url      : 'formularios/generados/PHP_Ejecutar.php',
                            type     : 'POST',
                            data     : {
                                id_gestion : item.id_gestion, 
                                formulario:item.ObjGestion["formulario"], 
                                campana:item.ObjGestion["id_campana_crm"], 
                                origen:item.ObjGestion["origen"], 
                                id: item.RegistroInsertado, 
                                usuario:item.ObjGestion["usuario"], 
                                miembro:item.ObjGestion["consinte"],
                                validarRegistro : 'si'
                            },
                            dataType : 'JSON',
                            success  : function(data) {
                                if(data.estado == 'ok'){
                                    if(data.mensaje==item.RegistroInsertado){
                                        //HAY SINCRONIA ENTRE EL STORAGE Y LA BD
                                        $("#oper").val('edit');
                                        $("#hidId").val(item.RegistroInsertado);

                                        $("#frameContenedor").contents().find("#oper").val('edit');
                                        $("#frameContenedor").contents().find("#hidId").val(item.RegistroInsertado);

                                        $("#main #frameContenedor").contents().find("#oper").val('edit');
                                        $("#main #frameContenedor").contents().find("#hidId").val(item.RegistroInsertado);

                                        $("#frameContenedor").contents().find("#FormularioDatos").contents().find("#oper").val('edit');
                                        $("#frameContenedor").contents().find("#FormularioDatos").contents().find("#hidId").val(item.RegistroInsertado);
                                        console.log("Hay sincronia entre storage y BD");
                                    }else{
                                        console.log("El storage tiene un registro antiguo: "+item.RegistroInsertado+" Debería tener este:"+data.mensaje);
                                    }
                                }else{
                                    console.log(data.mensaje);
                                }
                            },
                            beforeSend:function(){
                                try{
                                    $.blockUI({
                                        baseZ: 2000,
                                        message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se valida la gestión'
                                    });
                                }catch(e){

                                }
                            },
                            complete : function(){
                                try {
                                    $.unblockUI();
                                } catch (error) {
                                }
                            }
                        });
                    }
                }
            }
        });        
    },500);
}

<?php 
function validaSesion(){
    if(!isset($_SESSION['HUESPED_CRM']) && !isset($_GET['token'])){
        return 'perdio';
    }
    return 'valido';
}
?>

function validaSesion(data){
    if(data == 'perdio'){
        location.reload();
    }
}

window.addEventListener('message', function(event) {
    validaSesion(event.data);
});


document.addEventListener('DOMContentLoaded', () => {
        // $("#BtnBusqueda_lista_navegacion").click()
        <?php
        if (isset($auxiliar)) {
            echo $auxiliar;
        }
        ?>
        $("#divdatosPri").hide();
        $.each($('select'),function(i,item){
            setTimeout(() => {
                if(!$(this).attr('valstorage')){
                    $(this).change();
                }
            }, 1500);
        });
        window.parent.postMessage("<?=validaSesion()?>", "*");
        // try {
        //     asignarValores();
        // } catch (error) {
        //     console.error(error);
        // }
    })
</script>