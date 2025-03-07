<?php
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G35/G35_CRUD.php";
   $url_crud_g5 = base_url."cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php";
?>

<script>

    $(function(){

        cargarDatos();

    });

    // FUNCIÓN PARA MOSTRAR EL GIF DE CARGA
    function showBlock(){
        try {
            $.blockUI({
                baseZ: 2000,
                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
            });
        }catch(e){}
    }

    // FUNCIÓN PARA OCULTAR EL GIF DE CARGA
    function hideBlock(){
        try{
            $.unblockUI();
        }catch(e){}
    }

    function cargarDatos(){

        let pasoId = $("#id_paso").val();
        let huespedId = $("#huesped").val();

        $.ajax({
            url: '<?=$url_crud;?>?getDatos=true',
            type: 'POST',
            data: {huesped: huespedId, pasoId : pasoId, bd: <?=$_GET['poblacion']?>},
            dataType : 'json',
            beforeSend : function(){
                showBlock();
            },
            success: function(data){

                if(data.estado == 'ok'){

                    if(data.paso){
                        
                        if(data.paso.nombre){
                            $("#nombre").val(data.paso.nombre);
                        }

                        if(data.paso.activo == '-1'){
                            if(!$("#pasoActivo").is(':checked')){
                                $("#pasoActivo").prop('checked', true);  
                            }
                        }else{
                            if($("#pasoActivo").is(':checked')){
                                $("#pasoActivo").prop('checked', false);  
                            }
                        }
                    }

                

                    if(data.paso.nombre !== null){
                        
                        $("#oper").val('edit');

                    }
                }

            },
            complete : function(){
                hideBlock();
            },
            error: function(){
                hideBlock();
                alertify.error('Se ha presentado un error al cargar la informacion');
            }
        });
    }

    function guardarConfiguracion(){

        let valido = true;
        

        if($("#nombre").val() == ''){
            valido = false;
            $("#nombre").focus();
            alertify.error('el campo nombre es obligatorio');
        }

        if(valido){
            let formData = new FormData($("#FormularioDatos")[0]);

            $.ajax({
                url: '<?=$url_crud;?>?insertarDatos=true',  
                type: 'POST',
                data: formData,
                dataType : 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend : function(){
                    showBlock();
                },
                success: function(data){
                    $("#FormularioDatos")[0].reset();
                            $("#editarDatos").modal('hide');
                            alertify.success('Datos guardados');
                            location.reload();
                },
                complete : function(){
                    hideBlock();
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

    }
</script>