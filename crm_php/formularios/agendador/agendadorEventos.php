<?php
   $url_crudA = "../../../manager/cruds/DYALOGOCRM_SISTEMA/G33/G33_CRUD.php";
   $url_crud  = "agendador_CRUD.php";
?>
<script>

    var intIdAgendador;
    var intCc;

    function addDataTable(){
        $("#grid").DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
            }
        });
    }

    // AGREGAR EL PLUGIN SELEC2 A LOS CAMPOS TIPO SELECT
    function addSelect2(){
        $("select").select2();
    }

    // FUNCIÓN PARA MOSTRAR EL GIF DE CARGA
    function showBlock(){
        try {
            $.blockUI({
                baseZ: 2000,
                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
            });
        }catch(e){console.log(e)}
    }

    showBlock();

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

    // FUNCIÓN QUE DESHABILITAN LOS BOTONES DE OPERACION
    function deshabilitaBoton(btn){
        $(btn).attr('disabled',true);
    }

    // FUNCIÓN QUE HABILITA LOS BOTONES DE OPERACION
    function habilitaBoton(btn){
        $(btn).attr('disabled',false);
    }

    // FUNCIÓN QUE PROCESA CADA PETICIÓN DEL AGENDADOR
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

    // FUNCIÓN PARA OBTENER EL ID ENCRIPTADO DEL AGENDADOR AL QUE PERTENECE EL FORMULARIO DE DISPONIBILIDADES
    function consumirAgendador(cc,idFormAgendador){
        let agendador=ajax({getAgendador:'si',id:idFormAgendador},'application/x-www-form-urlencoded; charset=UTF-8');
        if(agendador.estado){
            getAgendador(agendador.mensaje,cc);
            intIdAgendador=agendador.mensaje;
            intCc=cc;
        }else{
            showError(agendador.mensaje);
        }
    }

    // FUNCIÓN PARA OBTENER LOS DATOS DE UN AGENDADOR PARA SABER SI PERMITE FILTRAR POR TIPO,RECURSO,UBICACIÓN
    function getAgendador(id,cc){
        getAgendador:{
            let agendador=ajax({getAgendador:'si',id:id},'application/x-www-form-urlencoded; charset=UTF-8',false,true,"JSON","<?=$url_crudA?>");
            if(agendador.estado == 'ok'){
                let arrAgenda=agendador.mensaje;
                
                if(arrAgenda['AGENDADOR_ValidaPer_b'] == '-1'){
                    if(!validaCliente(id,cc)){
                        break getAgendador;
                    }
                }

                if(arrAgenda['AGENDADOR_ValidaEst_b'] == '3'){
                    if(!addHtmlEstado(id,cc)){
                        break getAgendador;
                    }
                }

                if(arrAgenda['AGENDADOR_FilTip_b'] == '-1'){
                    if(!filtraTipo(id)){
                        break getAgendador;
                    }
                }else{
                    deshabilitaBoton("#tipo");
                }

                if(arrAgenda['AGENDADOR_FilUbi_b'] == '-1'){
                    if(!filtraUbi(id)){
                        break getAgendador;
                    }
                }else{
                    deshabilitaBoton("#ubicacion");
                }

                if(arrAgenda['AGENDADOR_FilRec_b'] == '-1'){
                    if(!filtraRecurso(id,true)){
                        break getAgendador;
                    }
                }else{
                    deshabilitaBoton("#recurso");
                }
            }else{
                showError(agendador.mensaje);
            }

        }
    }

    function validaCliente(idAgendador,cc){
        let agendador=ajax({validaCliente:'si',idAgendador:idAgendador,cc:cc},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(!agendador.response){
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"! No se permite agendar citas</div>");
            deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
            response=false;
        }else{
            habilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
            $(".alertas").html('');
        }

        return response;
    }

    function validaEstado(idAgendador,cc){
        let agendador=ajax({validaEstado:'si',idAgendador:idAgendador,cc:cc},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(!agendador.response){
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"! No se permite agendar citas</div>");
            deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
            response=false;
        }

        return response;
    }

    function addSectionCondicion(){
        let html='<div class="panel box box-primary" id="filtrosHtml">';
        html+='<div class="box-header with-border">';
        html+='<h4 class="box-title">';
        html+='<a data-toggle="collapse" data-parent="#accordion" href="#s_3889">CAMPOS DE VALIDACIÓN</a>';
        html+='</h4>';
        html+='</div>';
        html+='<div class="panel-collapse collapse in">';
        html+='<div class="box-body">';
        html+='<div class="row" id="campos">';
        html+='</div>';
        html+='</div>';
        html+='</div>';
        html+='</div>';

        return html;
    }

    function getOpcionesLista(lista){
        let opciones=ajax({getOpcionesLista:'si',lista:lista},'application/x-www-form-urlencoded; charset=UTF-8');
        let html= '';
        if(opciones.estado){
            $.each( opciones.mensaje , function( key, value ) {
                html+='<option value="'+value.id+'">'+value.texto+'</option>';
            });
        }else{
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"! No se permite agendar citas</div>");
            deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
        }

        return html;
    }

    function addCampoCondicion(id,texto,tipo,lista=0){
        let html='<div class="col-md-6 col-xs-6">';
        html+='<div class="form-group">';
        html+='<label for="'+id+'">'+texto+'</label>';
        if(tipo == '6'){
            html+='<select class="form-control input-sm select required"  style="width: 100%;" name="'+id+'" id="'+id+'">';
            html+='<option value="0">Seleccione</option>'
            html+=getOpcionesLista(lista);
            html+='</select>';
        }else{
            html+='<input type="text" class="form-control input-sm required" name="'+id+'" id="'+id+'" placeholder="'+texto+'">';
        }
        html+='</div>';
        html+='</div>';

        $("#campos").append(html);
    }

    // AGREGAR HTML DE LOS CAMPOS PARA VALIDAR EL ESTADO DE UN REGISTRO
    function addHtmlEstado(idAgendador,cc){

        let agendador=ajax({camposCondicion:'si',idAgendador:idAgendador},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(agendador.estado){
            // $("section").html('');
            if(agendador.mensaje.length > 0){
                try {
                    $("#filtrosHtml").remove();
                } catch (error) {}
                $("section").prepend(addSectionCondicion());
                $.each( agendador.mensaje , function( key, value ) {
                    addCampoCondicion(value.campo,value.texto,value.tipo,value.lista);
                });
            }else{
                validaEstado(idAgendador,cc);
            }
        }else{
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"! No se permite agendar citas</div>");
            deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
            response=false;
        }

        return response;   
    }

    function filtraTipo(idAgendador){
        let agendador=ajax({filtrarTipo:'si',idAgendador:idAgendador},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(!agendador.response){
            $("#tipo").html("<option value=''>"+agendador.message+"</option>");
            // $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"! No se permite agendar citas</div>");
            // deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
            // response=false;
        }else{
            $("#tipo").html("<option value=''>Todos los tipos de recurso</option>");
            for(let val of agendador.message){
                $("#tipo").append("<option value='"+val+"'>"+val+"</option>");
            }
        }

        return response;
    }

    function filtraUbi(idAgendador){
        let agendador=ajax({filtrarUbicacion:'si',idAgendador:idAgendador},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(!agendador.response){
            $("#ubicacion").html("<option value=''>"+agendador.message+"</option>");
            // $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"! No se permite agendar citas</div>");
            // deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
            // response=false;
        }else{
            $("#ubicacion").html("<option value=''>Todas las ubicaciones de recurso</option>");
            for(let val of agendador.message){
                $("#ubicacion").append("<option value='"+val+"'>"+val+"</option>");
            }
        }

        return response;
    }

    function filtraRecurso(idAgendador,bloquear=false){
        let agendador=ajax({listarRecursos:'si',idAgendador:idAgendador,tipo:$("#tipo").val(),ubicacion:$("#ubicacion").val()},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(!agendador.response){
            $("#recurso").html("<option value=''>"+agendador.message+"</option>");
            if(bloquear){
                $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"! No se permite agendar citas</div>");
                deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
                response=false;
            }
        }else{
            $("#recurso").html("<option value=''>Todos los recursos</option>");
            for(let val of agendador.message){
                $("#recurso").append("<option value='"+val['Nombre']+"'>"+val['Nombre']+"</option>");
            }
        }

        return response;
    }

    function listarCitas(idAgendador,cc,condiciones=''){
        deshabilitaBoton("#enviar");
        let agendador=ajax({listarCitas:'si',idAgendador:idAgendador,tipo:$("#tipo").val(),ubicacion:$("#ubicacion").val(),recurso:$("#recurso").val(),condiciones:JSON.stringify(condiciones),cc:cc},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(!agendador.response){
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"!</div>");
            response=false;
        }else{
            response=true;
            let html='';
            for(let val of agendador.message){
                html+='<tr>';
                html+="<td>"+val['Fecha']+"</td>";
                html+="<td>"+val['Hora']+"</td>";
                html+="<td>"+val['Nombre']+"</td>";
                html+="<td>"+val['Tipo']+"</td>";
                html+="<td>"+val['Ubicacion']+"</td>";
                html+="<td>"+val['Notas']+"</td>";
                html+="<td><input type='radio' value='"+val['IdCita']+"' name='idCita'>";
                html+="</tr>";
            }
            $("#listaCitas").html(html);
        }
        habilitaBoton("#enviar");

        return response;
    }

    function agendarCita(idAgendador,cc,idCita){
        let agendador=ajax({agendarCita:'si',idAgendador:idAgendador,cc:cc,idCita:idCita},'application/x-www-form-urlencoded; charset=UTF-8');
        let response= true;
        if(!agendador.response){
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>¡"+agendador.message+"!</div>");
            response=false;
        }else{
            response=true;
            deshabilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save,#cancel");
            $(".alertas").html("<div class='alert alert-success' role='alert' style='text-align:center'>¡"+agendador.message+"!</div>");
        }

        return response;
    }

    function validaCampos(){
        let valido=true;
        let condiciones=[]
        $(".required").each(function(){
            if($(this).val() == '' || $(this).val() == '0'){
                valido=false;
            }else{
                condiciones.push({id:$(this).attr('id'),valor:$(this).val()});
            }
        });

        if(valido){
            $(".alertas").html("");
            listarCitas(intIdAgendador,intCc,condiciones);
        }else{
            $(".alertas").html("<div class='alert alert-danger' role='alert' style='text-align:center'>Los campos de validación son obligatorios</div>");
        }
    }

    function bindEvent(element, eventName, eventHandler) {
        if (element.addEventListener) {
            element.addEventListener(eventName, eventHandler, false);
        } else if (element.attachEvent) {
            element.attachEvent('on' + eventName, eventHandler);
        }
    }


    $(document).ready(function(){
        addSelect2();

        $("#tipo,#ubicacion").change(function(){
            filtraRecurso(intIdAgendador);
        });

        $("#enviar").click(function(){
            validaCampos();
        });

        $("#save").click(function(){
            let idCita=$('input:radio[name=idCita]:checked').val();
            if(idCita){
                agendarCita(intIdAgendador,intCc,idCita);
            }else{
                alert("Debes seleccionar una cita");
            }
        });
    
        
        <?php
            if(isset($_GET['formulario']) && isset($_GET['yourfather'])){
                $formulario=is_numeric($_GET['formulario']) && $_GET['formulario'] > 0 ? $_GET['formulario'] : false;
                $cc=is_numeric($_GET['yourfather']) && $_GET['yourfather'] > 0 ? $_GET['yourfather'] : false;
                
                if($formulario && $cc){
                    ?>
                    habilitaBoton("#tipo,#ubicacion,#recurso,#enviar,#save");
                    consumirAgendador(<?=$cc?>,<?=$formulario?>);
                    <?php      
                }else{
                    echo "$(\".alertas\").html(\"<div class='alert alert-danger' role='alert' style='text-align:center'>¡No se identifico la cedula del registro! No se permite agendar citas</div>\");";
                }
            }else{
                echo "$(\".alertas\").html(\"<div class='alert alert-danger' role='alert' style='text-align:center'>¡No se identifico la cedula del registro! No se permite agendar citas</div>\");";
            }
        ?>

        //escuchar mensajes de  otro formulario
        bindEvent(window, 'message', function (e) {
            console.log(e.data);
            if(parseInt(e.data)){
                consumirAgendador(e.data,<?=$formulario?>);
            }
        });
    });

</script>