<?php
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G34/G34_CRUD.php";
?>

<script>

    $(function(){

        cargarDatos();

        $("#dentroHorarioAccion").change(function(){
            
            $("#dentroHorarioCampan").hide();
            $("#dentroHorarioBot").hide();
            $("#dentroHorarioMensaje").attr('disabled', false);

            if($("#dentroHorarioAccion").val() == 1){
                $("#dentroHorarioCampan").show();
            }
            if($("#dentroHorarioAccion").val() == 2){
                $("#dentroHorarioBot").show();
                $("#dentroHorarioMensaje").attr('disabled', true);
            }
        });

        $("#fueraHorarioAccion").change(function(){
            
            $("#fueraHorarioCampana").hide();
            $("#fueraHorarioBot").hide();
            $("#fueraHorarioMensaje").attr('disabled', false);

            if($("#fueraHorarioAccion").val() == 1){
                $("#fueraHorarioCampana").show();
            }
            if($("#fueraHorarioAccion").val() == 2){
                $("#fueraHorarioBot").show();
                $("#fueraHorarioMensaje").attr('disabled', true);
            }
        });

        //Timepickers
        $("#G10_C109").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C110").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C112").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C113").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C115").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C116").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C118").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C119").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C121").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C122").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C124").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C125").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C127").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C128").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C130").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C131").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });
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
        
        inicializarHorarios();

        let pasoId = $("#id_paso").val();
        let huespedId = $("#huesped").val();

        $("#dentroHorarioCampan").html('');
        $("#fueraHorarioCampana").html('');
        $("#dentroHorarioBot").html('');
        $("#fueraHorarioBot").html('');

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

                    if(data.campanas){
                        let opciones = '<option value="">Seleccione</option>';

                        data.campanas.forEach(element => {
                            opciones += `<option value="${element.campanCbx}">${element.nombre}</option>`;
                        });

                        $("#dentroHorarioCampan").html(opciones);
                        $("#fueraHorarioCampana").html(opciones);
                    }

                    if(data.bots){
                        let opciones = '<option value="">Seleccione</option>';

                        data.bots.forEach(element => {
                            opciones += `<option value="${element.id}">${element.nombre}</option>`;
                        });

                        $("#dentroHorarioBot").html(opciones);
                        $("#fueraHorarioBot").html(opciones);
                    }

                    if(data.canales && data.canales.length > 0){

                        let opciones = '<option value="">Seleccione</option>';

                        data.canales.forEach(element => {
                            if(element.disponible){
                                opciones += `<option value="${element.cuenta}">${element.nombre}</option>`;
                            }else{
                                opciones += `<option disabled value="${element.cuenta}">${element.nombre}</option>`;
                            }
                        });

                        $("#datoIntegracion").html(opciones);
                    }

                    if(data.chat !== null){
                        
                        $("#oper").val('edit');

                        $("#configuracionId").val(data.chat.id);
                        $("#datoIntegracion").val(data.chat.dato_integracion).change();
                        $("#datoIntegracion option[value='" + data.chat.dato_integracion + "']").attr("disabled", false);
                        $("#campoBusquedaIg").val(data.chat.id_pregun_campo_busqueda);

                        //Horarios
                        if(data.horario){
                            if(data.horario[1]){

                                if(!$("#G10_C108").is(':checked')){
                                    $("#G10_C108").prop('checked', true);  
                                }
                                
                                $("#G10_C109").val(data.horario[1].momento_inicial);
                                $("#G10_C110").val(data.horario[1].momento_final); 
                                
                            }else{
                                if($("#G10_C108").is(':checked')){
                                    $("#G10_C108").prop('checked', false);  
                                }
                            }

                            if(data.horario[2]){

                                if(!$("#G10_C111").is(':checked')){
                                    $("#G10_C111").prop('checked', true);  
                                }

                                $("#G10_C112").val(data.horario[2].momento_inicial);
                                $("#G10_C113").val(data.horario[2].momento_final); 

                            }else{
                                if($("#G10_C111").is(':checked')){
                                    $("#G10_C111").prop('checked', false);  
                                }
                            }

                            if(data.horario[3]){
                                if(!$("#G10_C114").is(':checked')){
                                    $("#G10_C114").prop('checked', true);  
                                }
                                $("#G10_C115").val(data.horario[3].momento_inicial);
                                $("#G10_C116").val(data.horario[3].momento_final); 
                            }else{
                                if($("#G10_C114").is(':checked')){
                                    $("#G10_C114").prop('checked', false);  
                                }
                            }

                            if(data.horario[4]){
                                if(!$("#G10_C117").is(':checked')){
                                    $("#G10_C117").prop('checked', true);  
                                }
                                $("#G10_C118").val(data.horario[4].momento_inicial);
                                $("#G10_C119").val(data.horario[4].momento_final); 
                            }else{
                                if($("#G10_C117").is(':checked')){
                                    $("#G10_C117").prop('checked', false);  
                                }
                            }

                            if(data.horario[5]){
                                if(!$("#G10_C120").is(':checked')){
                                    $("#G10_C120").prop('checked', true);  
                                }
                                $("#G10_C121").val(data.horario[5].momento_inicial);
                                $("#G10_C122").val(data.horario[5].momento_final); 
                            }else{
                                if($("#G10_C120").is(':checked')){
                                    $("#G10_C120").prop('checked', false);  
                                }
                            }
        
                            if(data.horario[6]){
                                if(!$("#G10_C123").is(':checked')){
                                    $("#G10_C123").prop('checked', true);  
                                }
                                $("#G10_C124").val(data.horario[6].momento_inicial);
                                $("#G10_C125").val(data.horario[6].momento_final); 
                            }else{
                                if($("#G10_C123").is(':checked')){
                                    $("#G10_C123").prop('checked', false);  
                                }
                            }

                            if(data.horario[7]){
                                if(!$("#G10_C126").is(':checked')){
                                    $("#G10_C126").prop('checked', true);  
                                }
                                $("#G10_C127").val(data.horario[7].momento_inicial);
                                $("#G10_C128").val(data.horario[7].momento_final); 
                            }else{
                                if($("#G10_C126").is(':checked')){
                                    $("#G10_C126").prop('checked', false);  
                                }
                            }

                            if(data.horario[8]){
                                if(!$("#G10_C129").is(':checked')){
                                    $("#G10_C129").prop('checked', true);  
                                }
                                $("#G10_C130").val(data.horario[8].momento_inicial);
                                $("#G10_C131").val(data.horario[8].momento_final); 
                            }else{
                                if($("#G10_C129").is(':checked')){
                                    $("#G10_C129").prop('checked', false);  
                                }
                            }
                        }

                        //Acciones

                        $("#dentroHorarioAccion").val(data.chat.dentro_horario_accion);
                        if(data.chat.dentro_horario_accion == 1){
                            $("#dentroHorarioCampan").val(data.chat.dentro_horario_detalle_accion);
                            $("#dentroHorarioMensaje").val(data.chat.frase_bienvenida_autorespuesta);
                        }else{
                            $("#dentroHorarioBot").val(data.chat.dentro_horario_detalle_accion);
                        }

                        $("#fueraHorarioAccion").val(data.chat.fuera_horario_accion);
                        if(data.chat.fuera_horario_accion == 1){
                            $("#fueraHorarioCampana").val(data.chat.fuera_horario_detalle_accion);
                            $("#fueraHorarioMensaje").val(data.chat.frase_fuera_horario);
                        }else{
                            $("#fueraHorarioBot").val(data.chat.fuera_horario_detalle_accion);
                        }

                    }

                    $("#dentroHorarioAccion").change();
                    $("#fueraHorarioAccion").change();
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

    function inicializarHorarios(){
        $("#G10_C109").val('08:00:00');
        $("#G10_C110").val('17:00:00');

        $("#G10_C112").val('08:00:00');
        $("#G10_C113").val('17:00:00');

        $("#G10_C115").val('08:00:00');
        $("#G10_C116").val('17:00:00');

        $("#G10_C118").val('08:00:00');
        $("#G10_C119").val('17:00:00');

        $("#G10_C121").val('08:00:00');
        $("#G10_C122").val('17:00:00');

        $("#G10_C124").val('08:00:00');
        $("#G10_C125").val('17:00:00');

        $("#G10_C127").val('08:00:00');
        $("#G10_C128").val('17:00:00');

        $("#G10_C130").val('08:00:00');
        $("#G10_C131").val('17:00:00');
    }

    function guardarConfiguracion(){

        let valido = true;
        
        if($("#dentroHorarioAccion").val() == 1){
            if($("#dentroHorarioCampan").val() == ''){
                valido = false;
                $("#dentroHorarioCampan").focus();
                alertify.error('El campo campaña es obligatorio');
            }
        }else if($("#dentroHorarioAccion").val() == 2){
            
            if($("#dentroHorarioBot").val() == ''){
                valido = false;
                $("#dentroHorarioBot").focus();
                alertify.error('El campo bot es obligatorio');
            }
        }

        if($("#fueraHorarioAccion").val() == 1){
            
            if($("#fueraHorarioCampana").val() == ''){
                valido = false;
                $("#fueraHorarioCampana").focus();
                alertify.error('El campo campaña es obligatorio');
            }
        }else if($("#fueraHorarioAccion").val() == 2){
            
            if($("#fueraHorarioBot").val() == ''){
                valido = false;
                $("#fueraHorarioBot").focus();
                alertify.error('El campo bot es obligatorio');
            }
        }

        if($("#nombre").val() == ''){
            valido = false;
            $("#nombre").focus();
            alertify.error('el campo nombre es obligatorio');
        }

        if($("#campoBusquedaIg").val() == '' || $("#campoBusquedaIg").val() == 0){
            valido = false;
            $("#campoBusquedaIg").focus();
            alertify.error('El campo busqueda es obligatorio');
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