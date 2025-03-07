<?php
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G33/G33_CRUD.php";
?>
<script>

    // FUNCIÓN PARA MOSTRAR EL GIF DE CARGA
    function showBlock(){
        try {
            $.blockUI({
                baseZ: 2000,
                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
            });
        }catch(e){}
    }
    
    // MIENTRAS SE CARGA COMPLETAMENTE TODO EL MODULO
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

    // AGREGAR EL PLUGIN SELEC2 A LOS CAMPOS TIPO SELECT
    function addSelect2(){
        $("select").select2();
    }

    // FUNCIÓN QUE DESHABILITAN LOS BOTONES DE OPERACION
    function deshabilitaBoton(btn){
        $(btn).attr('disabled',true);
    }

    // FUNCIÓN QUE HABILITA LOS BOTONES DE OPERACION
    function habilitaBoton(btn){
        $(btn).attr('disabled',false);
    }

    // FUNCIÓN PARA LIMPIAR LOS CAMPOS DE UN FORMULARIO
    function resetForm(){
        $("#FormularioDatos")[0].reset();
        $("select").val('0').trigger('change');
    }

    // FUNCIÓN QUE DESHABILITA LOS CAMPOS DEL FORMULARIO
    function disabledForm(reset=true){
        $('input,select').each(function(i,item){
            if(this.id!='table_search_lista_navegacion'){
                $(this).attr('disabled',true);
            }
        });
        if($("#oper").val() == 'add' && reset){
            resetForm();
        }
        deshabilitaBoton("#new_row,#cargue,#copiarAlPortapapeles");
    }

    // FUNCIÓN QUE HABILITA LOS CAMPOS DEL FORMULARIO
    function enableForm(){
        $('input,select').each(function(i,item){
            $(this).attr('disabled',false);
        });
        deshabilitaBoton("#AGENDADOR_ConsInte__GUION__Dis_b,#copiarId");
        habilitaBoton("#new_row,#cargue,#copiarAlPortapapeles");
    }

    // FUNCIÓN QUE MARCA O DESMARCA LOS CHECKBOX
    function markCheck(id,val=0){
        if( val == '-1'){
            $("#"+id).prop("checked",true);
        }else{
            $("#"+id).prop("checked",false);
        }
    }

    // FUNCIÓN PARA MANEJAR LOS EVENTOS AL CAMBIAR EL CHECK DE "VALIDAR ESTADO DE LA PERSONA"
    function checkEstado(){
        if($("input:radio[name='validacion']:checked").val() == '3'){
            $(".estado").show();
        }else{
            $(".estado").hide();
        }
    }

    // FUNCIÓN PARA MANEJAR LOS EVENTOS AL CAMBIAR EL CHECK DE "VALIDAR QUE LA PERSONA EXISTA"
    // function checkPersona(){
    //     if($("#AGENDADOR_ValidaPer_b").is(":checked")){
    //         habilitaBoton("#AGENDADOR_ValidaEst_b");
    //     }else{
    //         deshabilitaBoton("#AGENDADOR_ValidaEst_b");
    //         markCheck("AGENDADOR_ValidaEst_b");
    //         $("#AGENDADOR_ValidaEst_b").trigger('change');
    //     }
    // }

    // FUNCIÓN QUE LLENA LOS CAMPOS DEL FORMULARIO
    function fillForm(data){
        $("#wfId").val('0');
        $("#idEstpas").val('0');
        $("#webOper").val('add');
        $.each( data , function( key, value ) {
            $('#FormularioDatos input, #FormularioDatos select, #FormularioDatos checkbox, #FormularioDatos radio').each(function(){
                if(this.id == key){
                    let tipo=$(this).attr('type');
                    if(tipo == 'checkbox'){
                        markCheck(this.id,value);
                        try {
                            $("#"+this.id).change();
                        } catch (error) {}
                    }else{
                        if($(this).hasClass('select2')){
                            if(value == null || value == 'null' || value == ''  || value == '0'){
                                $(this).val('0').trigger('change');
                            }else{
                                $(this).val(value).trigger('change');
                            }
                        }else{
                            $(this).val(value).trigger('change');
                        }
                    }
                }else{
                    if(key == 'AGENDADOR_ValidaEst_b'){
                        $('input:radio[name=validacion]').each(function () { $(this).prop('checked', false); });
                        $("[type=radio][name=validacion][value="+value+"]").prop("checked", true).click();
                    }
                } 
            });
        });
    }

    // FUNCIÓN QUE PROCESA CADA PETICIÓN DEL AGENDADOR
    function ajax(data,contentType=false,cache=true,processData=true,retorno="JSON",url='<?=$url_crud;?>',method="POST"){
        let response='';
        $.ajax({
            url:url,
            async:false,
            method:method,
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

    // FUNCIÓN QUE LLENA LOS SELECT DEL AGENDADOR
    function llenarSelect(id,value,texto, opcion='', strTipo=''){
        let opc='';
        if(opcion != ''){
            opc="opcion='"+opcion+"'";
        }

        let tipo='';
        if(strTipo !=''){
            tipo="tipo='"+strTipo+"'";
        }

        $("#"+id).append("<option value='"+value+"' "+opc+" "+tipo+">"+texto+"</option>");
    }

    // FUNCIÓN QUE ELIMINA LAS OPCIONES DE UN SELECT;
    function limpiarSelect(id=''){

        if(id == ''){
            $('select').each(function(i,item){
                if(this.id != 'AGENDADOR_ConsInte__GUION__Pob_b' && this.id !="AGENDADOR_Estrat_b"){
                    $(this).html("<option value='0'>Seleccione</option>");
                }
            });
        }else{
            $("#"+id).html("<option value='0'>Seleccione</option>");
        }
    }

    // FUNCIÓN QUE VALIDA LOS CAMPOS OBLIGATORIOS DEL AGENDADOR
    function validaCampos(){
        let valido=true;
        let condiciones=true;
        $('input,select').each(function(i,item){
            if($(this).hasClass('required')){
                if($(this).val() == '' || $(this).val() == '0'){
                    console.log(1);
                    let nombre=$(this).prev().html();
                    valido=false;
                    alertify.error("El campo "+ nombre + " Es requerido");
                }
            }

            if($(this).hasClass('requiredCond')){
                if($(this).prop('disabled') == false){
                    if($(this).val() == '' || $(this).val() == '0'){
                        valido=false;
                        condiciones=false;
                    }
                }
            }
        });

        if($("input:radio[name='validacion']:checked").val() == '3'){
            if($(".requiredCond").length <= 0){
                showError("Debe configurar al menos una condición");
                valido=false;
            }
        }

        if(!condiciones){
            showError("Los campos de las condiciones son obligatorios");
        }

        return valido;
    }

    // FUNCIÓN PARA MANEJAR EL COMPORTAMIENTO DEL BOTÓN CANCELAR SEGUN LA OPERACION ANTERIOR (ADD,EDIT)
    function btnCancel(){
        deshabilitaBoton("#Save,#cancel");
        disabledForm();

        if($("#tablaScroll").html() != ''){
            habilitaBoton("#add,#edit,#delete,#table_search_lista_navegacion");
            if($("#oper").val() == 'add'){
                activeTD();
            }else{
                activeTD($("#hidId").val());
            }
        }else{
            habilitaBoton("#add");
            deshabilitaBoton("#table_search_lista_navegacion");
        }

        $("#oper").val('');
    }

    // FUNCIÓN PARA MANEJAR EL COMPORTAMIENTO DEL BOTOÓN DE AGREGAR
    function btnAdd(){
        resetForm();
        enableForm();
        habilitaBoton("#cancel,#Save");
        deshabilitaBoton("#add,#delete,#edit,#table_search_lista_navegacion");
        markCheck("AGENDADOR_FilRec_b",-1);
        markCheck("AGENDADOR_FilUbi_b",-1);
        markCheck("AGENDADOR_OferHoy_b",-1);
        $("[type=radio][name=validacion][value=1]").attr("checked", "checked");
        webForm();
        // checkPersona();
        $("#AGENDADOR_CantCitas__b").val(3);
        $("#oper").val('add');
        $("#hidId").val('');
    }

    // FUNCIÓN PARA SELECCIONAR UN REGISTRO DE LA LISTA DE NAVEGACIÓN
    function activeTD(id=null){
        $(".CargarDatos").each(function() {
            $(this).removeClass('active');
        });

        if(id != null){
            $("#"+id).addClass('active');
        }else{
            $(".CargarDatos").first().addClass('active').click();
        }
    }

    // ESTA FUNCIÓN SE ENCARGA DE LISTAR LOS AGENDADORES QUE PERTENEZCAN AL HUESPED EN SESIÓN
    function llenarTabla(){
        let response=ajax({llenarListaNavegacion:'si'},'application/x-www-form-urlencoded; charset=UTF-8');
        if(response.estado == 'ok'){
            if(response.mensaje!=""){
                $("#sinRegistros").remove();
                $("#tablaScroll").html(response.mensaje);
                deshabilitaBoton("#cancel,#Save");
                activeTD();
            }else{
                $("#tablaScroll").after("<h3 id='sinRegistros' >Sin registros, para crear un nuevo registro de click en el boton de agregar</h3>");
                deshabilitaBoton("#edit,#cancel,#Save,#delete,#vistaPrevia,#table_search_lista_navegacion");
                disabledForm();
            }
        }else{
            showError(response.mensaje);
        }
    }

    // FUNCIÓN QUE LLENA LOS SELECT CON LOS CAMPOS DE LA BASE DE DATOS DE PERSONAS QUE SE ELIGA PARA EL AGENDADOR
    function changeBD(){
        limpiarSelect("");
        if($("#AGENDADOR_ConsInte__GUION__Pob_b").val() != '0'){
            let bd=$("#AGENDADOR_ConsInte__GUION__Pob_b").val();
            response=ajax({bd:bd, getCampos:true},'application/x-www-form-urlencoded; charset=UTF-8');
            if(response.estado=='ok'){
                for(key in response.mensaje){
                    for (let i of response.mensaje[key]) {
                        //LLENAR LOS CAMPOS CON LOS DATOS QUE CORRESPONDA
                        if(key == 'texto'){
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_IdP_b',i.id,i.texto);
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_NomP_b',i.id,i.texto);
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_CelP_b',i.id,i.texto);
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_MailP_b',i.id,i.texto);
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_EstP_b',i.id,i.texto);
                        }

                        if(key == 'numerico'){
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_IdP_b',i.id,i.texto);
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_CelP_b',i.id,i.texto);
                        }

                        if(key == 'mail'){
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_MailP_b',i.id,i.texto);
                        }

                        if(key == 'lista'){
                            llenarSelect('AGENDADOR_ConsInte__PREGUN_EstP_b',i.id,i.texto,i.opcion);
                        }
                    }
                }
            }else{
                showError(response.mensaje);
            }
        }
    }

    function getWebForm(id){
        if($("#AGENDADOR_Webform_b").is(":checked")){
            let data=ajax({getWebForm:'si', id:id},'application/x-www-form-urlencoded; charset=UTF-8');
            if(data.estado=='ok'){
                $("#wfId").val(data.mensaje.WEBFORM_Consinte__b);
                $("#idEstpas").val(data.mensaje.ESTPAS_ConsInte__b);
                $("#wfNombre").val(data.mensaje.WEBFORM_Nombre_b);
                $("#Web_Origen").val(data.mensaje.WEBFORM_Origen_b);
                $("#webOper").val('edit');
                $("#urlWeb").html(data.mensaje.url);
                $("#urlWeb").attr('href',data.mensaje.url);
            }
        }
    }

    // OBTENER LAS CONDICIONES DE UN AGENDADOR
    function getCondiciones(id){
        let data=ajax({getCondiciones:'si', id:id},'application/x-www-form-urlencoded; charset=UTF-8');
        if(data.estado=='ok'){
            $.each( data.mensaje , function( key, value ) {
                let id=addRow();
                $("#campo_"+id).val(value.campo).trigger('change');
                $("#tipo_"+id).val(value.tipo).trigger('change');
                $("#dato_"+id).val(value.dato).trigger('change');
            });
        }
    }

    function saveWebForm(data){
        let datas=new FormData()
        datas.append('wfPasoId',$("#idEstpas").val());
        datas.append('wfOper',$("#webOper").val());
        datas.append('wfNombre',$("#wfNombre").val());
        datas.append('wfPasoActivo',-1);
        datas.append('wfPoblacion',$("#GUION__ConsInte__b").val());
        datas.append('Web_Origen',$("#Web_Origen").val());
        let inputFileCss = document.getElementById("css");
        let fileCss = inputFileCss.files[0]
        datas.append('css',fileCss);
        let inputFileImg = document.getElementById("logoForm");
        let fileImg = inputFileImg.files[0]
        datas.append('logo_form',fileImg);
        datas.append('wfId',$("#wfId").val());
        datas.append('wfPregunValidacion',0);
        datas.append('wfObservaciones','');
        datas.append('selRedirecionFormWeb',0);
        datas.append('inpLinkPage','');
        datas.append('inpCodigoGoogle','');
        webF=ajax(datas,false,false,false,'JSON','../cruds/DYALOGOCRM_SISTEMA/G13/G13_CRUD.php?insertarDatos=true');
        if(webF.valido){
            $("#wfId").val(webF.id);
            $("#webOper").val('edit');
        }
    }

    // FUNCIÓN QUE ENVIA LA SOLICITUD PARA CREAR Y/O EDITAR UN AGENDADOR
    function saveAgendador(){
        saveAgendador:{
            if(validaCampos()){
                let formData = new FormData($("#FormularioDatos")[0]);
                let oper=$("#oper").val();
                let mensaje='';
                if(oper =='add'){
                    mensaje='creado';
                    formData.append('guardar','guardar');
                }else if(oper == 'edit'){
                    mensaje='actualizado';
                    formData.append('actualizar','actualizar');
                }else{
                    showError('Operación indefinida');
                    break saveAgendador;
                }
                data=ajax(formData,false,false,false);
                if(data.estado=='ok'){
                    alertify.success("Registro "+mensaje+ " con exito");
                    if(oper == 'add'){
                        llenarTabla();
                    }else{
                        habilitaBoton("#add,#delete,#edit");
                        deshabilitaBoton("#cancel,#Save");
                    }
                    
                    if($("#AGENDADOR_Webform_b").is(":checked")){
                        //CREAR EL WEBFORM
                        if(data.data.estpas[0] != '0'){
                            $("#idEstpas").val(data.data.estpas[0]);
                            $("#webOper").val(data.data.estpas[1]);
                            saveWebForm(data);
                            getWebForm($("#hidId").val());
                        }
                    }

                    disabledForm();

                }
            }
        }
    }

    // FUNCIÓN PARA OBTENER LOS DATOS DE UN AGENDADOR
    function getAgendador(id){
        $("#filas").html('');
        $("#wfNombre").val('');
        $("#Web_Origen").val('');
        let data=ajax({getAgendador:'si', id:id},'application/x-www-form-urlencoded; charset=UTF-8');
        if(data.estado=='ok'){
            $('#hidId').val(id);
            $("#copiarId").val(id);
            fillForm(data.mensaje);
            getCondiciones(id);
            getWebForm(id);
        }else{
            showError(data.mensaje);
        }
        activeTD(id);
        deshabilitaBoton("#cancel,#Save");
        habilitaBoton("#add,#edit,#delete,#table_search_lista_navegacion");
        disabledForm(false);
    }

    function habilitaCondicion(){
        $(".tipoValida").each(function(){
            if($(this).val() == '2'){
                $(this).change();
            }
        })
    }

    // FUNCIÓN PARA MANEJAR LOS EVENTOS DE CADA BOTON 
    function operBtn(oper){
        switch(oper){
            case 'edit':
                enableForm();
                deshabilitaBoton("#add,#delete,#edit,#AGENDADOR_ConsInte__GUION__Pob_b");
                habilitaBoton("#cancel,#Save");
                $("#oper").val('edit');
                habilitaCondicion();
                webForm();
                // checkPersona();
                break;
            case 'delete':
                break;
            default:
                break;
        }
    }

    // FUNCIÓN PARA LLAMAR A LA MODAL DE CARGAR DATOS
    function cargueDatos(){
        let Gdisponible=$("#GUION__ConsInte__b").val();
        let data=ajax('','application/x-www-form-urlencoded; charset=UTF-8',true,true,"html",'<?=base_url?>mostrar_popups.php?view=cargueDatos&id_paso=0&poblacion='+Gdisponible,"GET");
        $("#divIframe").html(data);
        $("#editarDatos").modal();
    }

    // COPIAR AL PORTAPAPELES EL ID
    function copiarAlPortapapeles(){
        let campoId = document.createElement("input");
        campoId.value=$("#copiarId").val();
        document.body.appendChild(campoId);
        campoId.select();
        document.execCommand("copy");
        document.body.removeChild(campoId);
    }

    // OBTENER LA LISTA DE OPCIONES DE UN CAMPO TIPO LISTA
    function getOpciones(id,opcion){
       let response=ajax({opcion:opcion, getOpciones:true},'application/x-www-form-urlencoded; charset=UTF-8');
       if(response.estado=='ok'){
            for(key of response.mensaje){
                llenarSelect("dato_"+id,key.id,key.texto);
            }
        }
    }

    // MANJEAR EL HTML DE LOS CAMPOS "Dato a validar" CONVIERTE A SELECT O INPUT SEGÚN CORRESPONDA
    function addHtmlDato(id,tipo){
        if(tipo == 'texto'){
            $("#htmlDato_"+id).html("<input type='text' name='dato_"+id+"' id='dato_"+id+"' class='form-control requiredCond'>");
        }else{
            let opcion=$("#campo_"+id+" option:selected").attr('opcion');
            $("#htmlDato_"+id).html("<select name='dato_"+id+"' id='dato_"+id+"' class='form-control requiredCond'><option value='0'>Seleccione</option></select>");
            getOpciones(id,opcion);
        }
    }

    // CAMPOS DE LA BD PARA LOS SELECT DE CONDICIONES
    function getCamposBdCondiciones(campo){
        limpiarSelect(campo);
        if($("#AGENDADOR_ConsInte__GUION__Pob_b").val() != '0'){
            let bd=$("#AGENDADOR_ConsInte__GUION__Pob_b").val();
            response=ajax({bd:bd, getCampos:true},'application/x-www-form-urlencoded; charset=UTF-8');
            if(response.estado=='ok'){
                for(key in response.mensaje){
                    for (let i of response.mensaje[key]) {
                        if(key == 'lista'){
                            llenarSelect(campo,i.id,i.texto,i.opcion,"lista");
                        }else{
                            llenarSelect(campo,i.id,i.texto,"","texto");
                        }
                    }
                }
            }else{
                showError(response.mensaje);
            }
        }else{
            showError("Seleccione una base de datos");
        }
    }

    // CONTROLAR EL CAMBIO DE LOS SELECTS DE "Campo sobre el que aplica la condición"
    function changeCampo(id){
        $("#tipo_"+id).val('0').trigger('change');
        if($("#campo_"+id).val() != '0'){
            $("#tipo_"+id).attr('disabled',false);
        }else{
            $("#tipo_"+id).attr('disabled',true);
        }
    }

    // CONTROLAR EL CAMBIO DE LOS SELECTS DE "Tipo de validación"
    function changeTipo(id){
        if($("#tipo_"+id).val() == '1'){
            let tipo=$("#campo_"+id+" option:selected").attr('tipo');
            $("#dato_"+id).attr('disabled',false);
            addHtmlDato(id,tipo);
        }else{
            $("#dato_"+id).val('');
            $("#dato_"+id).attr('disabled',true);
        }
    }

    // HTML DE LAS FILAS DINAMICAS DE LAS CONDICIONES
    function addHtml(thisId){
        let html='<div class="row" id="row_'+thisId+'">'
        html+='<input type="hidden" name="condicion[]" value="'+thisId+'">'
        html+='<div class="col-md-3">';
        html+='<div class="form-goup">';
        html+='<select name="campo_'+thisId+'" id="campo_'+thisId+'" class="form-control requiredCond" onchange="changeCampo('+thisId+')">';
        html+='<option value="0">Seleccione</option>';
        html+='</select>';
        html+='</div>';
        html+='</div>';
        html+='<div class="col-md-3">';
        html+='<div class="form-group">';
        html+='<select name="tipo_'+thisId+'" id="tipo_'+thisId+'" numero="'+thisId+'" class="form-control tipoValida requiredCond" disabled onchange="changeTipo('+thisId+')">';
        html+='<option value="0">Seleccione</option>';
        html+='<option value="1">Dato que se configura acá</option>';
        html+='<option value="2">Dato que se le pide al cliente</option>';
        html+='</select>';
        html+='</div>';
        html+='</div>';
        html+='<div class="col-md-3">';
        html+='<div class="form-group" id="htmlDato_'+thisId+'">';
        html+='<input type="text" name="dato_'+thisId+'" id="dato_'+thisId+'" class="form-control requiredCond" disabled>';
        html+='</div>';
        html+='</div>';
        html+='<div class="col-md-3">';
        html+='<div class="form-group">';
        html+='<button class="btn btn-danger btn-xl bg-gradient-warning eliminar-fila" id="'+thisId+'"><i class="fas fa-trash-alt eliminar-fila"></i></button>';
        html+='</div>';
        html+='</div>';
        html+='</div>';

        $("#filas").append(html);
        getCamposBdCondiciones("campo_"+thisId);

    }

    // AGREGAR UNA FILA DE CONDICIONES PARA EL ESTADO
    function addRow(){
        let thisId=Number($("#contador").val());
        addHtml(thisId);

        $(".eliminar-fila").click(function(){
            let id=this.id;
            $("#row_"+id).remove();
        });

        $("#contador").val(Number(thisId+1));
        return thisId;
    }

    //  CAMBIOS DEL CHECK DE WEBFORM
    function webForm(){
        if($("#AGENDADOR_Webform_b").is(":checked")){
            habilitaBoton("#wfNombre,#logoForm,#css,#Web_Origen");
        }else{
            deshabilitaBoton("#wfNombre,#logoForm,#css,#Web_Origen");
            $("#urlWeb").html('');
        }
    }

    function changeEstrat(){
        limpiarSelect("AGENDADOR_Estpas_b");
        if($("#AGENDADOR_Estrat_b").val() != '0'){
            let id=$("#AGENDADOR_Estrat_b").val();
            response=ajax({idEstrat:id, getEstpas:true},'application/x-www-form-urlencoded; charset=UTF-8');
            if(response.estado=='ok'){
                for(key of response.mensaje){
                    llenarSelect("AGENDADOR_Estpas_b",key.id,key.nombre);
                }
            }else{
                showError("Ocurrio un error al obtener los pasos de la estrategia");
            }
        }
    }

    // FUNCIONES A EJECUTAR DESPUÉS DE CARGAR TODA LA PÁGINA
    $(document).ready(function(){
        addSelect2();
        llenarTabla();
        disabledForm();
        hideBlock();

        <?php if(isset($_GET['add'])) : ?>
            btnAdd();
        <?php endif; ?>

        <?php if(isset($_GET['edit'])) : ?>
            getAgendador("<?php echo Url::urlSegura($_GET['id']) ?>");
            operBtn('edit');
        <?php endif; ?>

        $("#copiarAlPortapapeles").click(function(){
            copiarAlPortapapeles();
        });

        $("#new_row").click(function(e){
            e.preventDefault();
            addRow();
        });
    });
    
</script>