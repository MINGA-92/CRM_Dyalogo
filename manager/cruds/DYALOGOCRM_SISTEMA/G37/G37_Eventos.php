<?php
   $url_crud  = base_url."cruds/DYALOGOCRM_SISTEMA/G37/G37_CRUD.php";
?>
<script>

    // FUNCIÓN PARA MOSTRAR EL GIF DE CARGA
    function showBlock(){
        try {
            $.blockUI({
                baseZ: 2000,
                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
            });
        }catch(e){console.log(e)}
    }

    // FUNCIÓN PARA OCULTAR EL GIF DE CARGA
    function hideBlock(){
        try{
            $.unblockUI();
        }catch(e){}
    }
    
    // FUNCION QUE MUESTRA UNA ALERTA PARA LOS ERRORES
    function showError(data=null){
        let mensaje='Ocurrio un error y no se pudo procesar la solictud';
        if(data != null){
            mensaje=data;
        }
        alertify.error(mensaje);
    }
    
    // FUNCIÓN QUE PROCESA CADA PETICIÓN DEL ARCHIVO
    function ajax(data,contentType=false,cache=true,processData=true,retorno="JSON",url='<?=$url_crud;?>'){
        let response='';
        $.ajax({
            url:url,
            async:false,
            method:'POST',
            dataType:retorno,
            data:data,
            cache:cache,
            processData:processData,
            contentType:contentType,
            success:function(data){
                response=data;
            },
            beforeSend:function(){
                showBlock();
            },
            complete:function(){
                hideBlock();
            },
            error:function(){
                showError();
            }
        });
        
        return response;
    }
    
    // OBTENGO EL FORMULARIO DE GESTIONES DE
    async function getForm(paso){
        let response=0;
        let form=ajax({paso:paso, getForm:'si'},'application/x-www-form-urlencoded; charset=UTF-8');
        if(form.estado){
            response = parseInt(form.mensaje);
        }else{
            alertify.warning(form.mensaje)
        }

        return response;
    }
    
    function getCamposLista(guion){
        let campos=ajax({guion:guion, getCamposLista:'si'},'application/x-www-form-urlencoded; charset=UTF-8');
        let html="<option value='0'>Seleccione</option>";

        if(campos.estado){
            $.each(campos.mensaje, function(i,item){
                html+="<option value='" + item.id + "'>" + item.nombre + "</option>";
            });
        }else{
            alertify.warning(campos.mensaje);
        }

        $("#pregun").html(html);
    }

    $(function(){
        <?php
            $connect=isset($_GET['connect']) ? $_GET['connect'] : 0;
        ?>
        let connect=parseInt('<?=$connect?>');
        let idPaso=parseInt($("#idEstpas").val());

        if(connect > 0){
            getForm(connect).then(
                function(value){
                    getCamposLista(value);
                }
            )         
        }else{
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>Debes conectar un paso de campaña a esta tarea de calidad para que funcione</div>");
        }

        $('#tipoDistribucionTrabajo').change(function(){
            if($('#tipoDistribucionTrabajo').val() != 2){
                $('#pregun').attr('disabled', 'disabled');
                $('#lisopc').attr('disabled', 'disabled');
                $('#pregun').val('');
                $('#lisopc').val('');   
            }else{
                $('#pregun').removeAttr('disabled');
                $('#lisopc').removeAttr('disabled');
            }
        });

        $('#tipoDistribucionTrabajo').val(0).trigger('change');

        // Se ejecuta cuando hay un cambio en el select de pregunt
        $('#pregun').change(function(){

            let id = $('#pregun').val();

            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getLisopc=true',  
                type: 'POST',
                data: {id : id},
                dataType : 'json',
                success: function(response){
                    $('#lisopc').html(response.lisopcOption);
                },
                error: function(response){
                    console.log(response);
                },
                beforeSend : function(){
                    $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });

        // Este boton guarda el formulario de tareas de backoffice
        $('#guardarCasoBackofficeButton').click(function(){
            
            var valido = 0;

            if($("#nombreCaso").val() < 1){
                valido = 1;
                alertify.error("El campo nombre no puede estar vacío");
                $("#nombreCaso").focus();
            }

            if(valido == 0){
                var formData = new FormData($()[0]);
                var estadoModal = $("#tipoAccion").val();
                var mikey = $('#idEstpas').val();

                // Dependiendo de tipoAccion va a crear un registro nuevo o actualizarlo
                if(estadoModal == 'nuevo'){
                    formData.append('nuevoCasoBackoffice', true);
                }else if(estadoModal == 'editar'){
                    formData.append('editarCasoBackoffice', true);
                }
                
                $.ajax({
                   url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?guardarCasoBackoffice=true',  
                    type: 'POST',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.con1 && response.con2){
                            alertify.success("Guardado &eacute;xitoso");
                        }else{
                            alertify.error("Error al guardar");
                        }

                        if(estadoModal == 'nuevo'){
                            getDatosBackoffice(mikey);
                            
                        }else{
                            $("#casoBackofficeModal").modal('hide');
                            $.unblockUI();
                        }
                    },
                    error: function(response){
                        console.log(response);
                        $.unblockUI();
                    },
                    beforeSend : function(){
                        $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        //$.unblockUI();
                    }
                });
            }

        });
    });
</script>