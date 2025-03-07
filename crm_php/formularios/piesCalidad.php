

<!-- CAMPOS PARA EDICION Y CONOCER QUE OPERACION ESTAMOS REALIZANDO, INSERT, UPDATE, DELETE-->
                                                <input type="hidden" name="id" id="hidId" value='0'>
                                                <input type="hidden" name="oper" id="oper" value='add'>
                                                <input type="hidden" name="Padre" id="Padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                                <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                                <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
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



<script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- FastClick -->
<script src="assets/plugins/fastclick/fastclick.js"></script>



<link rel="stylesheet" href="assets/plugins/WinPicker/dist/wickedpicker.min.css">
<script type="text/javascript" src="assets/plugins/WinPicker/dist/wickedpicker.min.js"></script>
<script src="assets/js/validator.js"></script>

<script type="text/javascript" src="assets/plugins/select2/select2.min.js" ></script>
<link type="text/css" rel="stylesheet" href="assets/plugins/select2/select2.min.css" />  

<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;


    $(function(){

        $("#refrescarFiltros").click(function(){
            $("#table_search_lista_navegacion").val("");
            $("#busquedaFecInsercion").val("");
            $("#busquedaTipificacion").val(0).change();
            $("#busquedaAgente").val(0).change();
        });

        $("#busquedaFecInsercion").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
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
                if (this.id != 'G722_C9524' && this.id != 'G722_C9525'){
                    $(this).attr('disabled', false);
                }
                
            });

            $(".modalOculto").show();

            $(".TxtFechaReintento").attr('disabled', true);
            $(".TxtHoraReintento").attr('disabled', true);   
            
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
                        dataType : 'json',
                        success  : function(data){
                            if(data == 1){   
                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                busqueda('');
                                seleccionar_registro(); 
                                 
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
            var id = $(this).attr('id');
            var valor = $("#"+ id +" option:selected").attr('efecividad');
            var monoef = $("#"+ id +" option:selected").attr('monoef');
            var TipNoEF = $("#"+ id +" option:selected").attr('TipNoEF');
            var cambio = $("#"+ id +" option:selected").attr('cambio');
            var importancia = $("#"+ id + " option:selected").attr('importancia');
            var contacto = $("#"+id+" option:selected").attr('contacto');
            $(".reintento").val(TipNoEF).change();
            $("#Efectividad").val(valor);
            $("#MonoEf").val(monoef);
            $("#TipNoEF").val(TipNoEF);
            $("#MonoEfPeso").val(importancia);
            $("#ContactoMonoEf").val(contacto);
            
            if(cambio != '-1'){
                $(".reintento").attr('disabled', true);
            }

            //esto solo aplica para femclinic
            if($(this).val() == '7814'){
                $("#G650_C8526").attr('disabled', false);
                $("#G650_C8526").val('0').change();
            }else{
                $("#G650_C8526").val('0').change();
                $("#G650_C8526").attr('disabled', true);
            }
        });
       
        
        $("#txtPruebas").on('scroll', function() {


            
            <?php 

                if (!isset($idUsuario)) {
                    $idUsuario="";
                }


             ?>

            //scroll panel izquierdo
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
               

               $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si',idUsuario: "<?php echo $idUsuario; ?>", A : $("#busquedaAgente").val(), T : $("#busquedaTipificacion").val(), F : $("#busquedaFecInsercion").val(), E : $("#estadoCalidad").val(), B : $("#table_search_lista_navegacion").val()}, function(data){

                    if(data != ""){
                        $("#tablaScroll").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                    
                });


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
        $("#BtnBusqueda_lista_navegacion").click(function(){
            llenar_lista_navegacion($("#table_search_lista_navegacion").val(),$("#busquedaAgente").val(),$("#busquedaTipificacion").val(),$("#busquedaFecInsercion").val(),$("#estadoCalidad").val());
        });
        
//Cajaj de texto de bus queda
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
                $resultcampSql = $mysqli->query($campSql);
                while($key = $resultcampSql->fetch_object()){
                    
                    //Pregfuntar por el tipo de dato
                    $Lsql = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$key->CAMINC_ConsInte__CAMPO_Gui_b;
                    $res = $mysqli->query($Lsql);
                    $datos = $res->fetch_array();


                    //consulta de datos del usuario
                    $DatosSql = " SELECT ".$key->CAMINC_NomCamPob_b." as campo FROM ".$BaseDatos.".G".$tabla." WHERE G".$tabla."_ConsInte__b=".$_GET['user'];

                    //echo $DatosSql;
                    //recorro la tabla de donde necesito los datos
                    $resultDatosSql = $mysqli->query($DatosSql);
                    if($resultDatosSql){
                        while($objDatos = $resultDatosSql->fetch_object()){ 
                            if(!is_null($objDatos->campo) && $objDatos->campo != ''){
                            $objDatos->campo=str_replace(PHP_EOL, " ",$objDatos->campo);
				$objDatos->campo=str_replace("\n", " ",$objDatos->campo);
                 ?>
                                if($('#<?php echo $key->CAMINC_NomCamGui_b;?>').length){

                            <?php

                                if($datos['PREGUN_Tipo______b'] != '8'){
                            ?>
					try{
                                    document.getElementById("<?=$key->CAMINC_NomCamGui_b;?>").value = '<?=trim($objDatos->campo);?>';
					}catch(err){
							document.getElementById("<?=$key->CAMINC_NomCamGui_b;?>").value = '-ERR-';
					}
                            <?php  
                                }else{
                                    if($objDatos->campo == '1'){
                                        echo "$('#".$key->CAMINC_NomCamGui_b."').iCheck('check');";
                                    }else{
                                        echo "$('#".$key->CAMINC_NomCamGui_b."').iCheck('uncheck');";
                                    }
                                    
                                } 
                                ?>
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
    });
</script>
