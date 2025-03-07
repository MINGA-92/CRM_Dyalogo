<?php

include("conexion.php");

if(!isset($_GET['est'])){
    echo "No se puede acceder a esta direccion";
    exit;
}

$est = $_GET['est'];
$url_crud = "distribuir_backoffice_crud.php";
$url_crud_manager = "https://".$_SERVER['SERVER_NAME']."/manager";

// TODO: DEBO ELIMINARLO AL SUBIRLO AL SERVIDOR
// define('base_url', "http://{$_SERVER['SERVER_NAME']}/manager/");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribucion de backoffice</title>

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Date Picker -->
    <link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="assets/timepicker/jquery.timepicker.css"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
    <!-- Alertify -->
    <link rel="stylesheet" href="assets/css/alertify.core.css">
    <link rel="stylesheet" href="assets/css/alertify.default.css">

    <link rel="stylesheet" type="text/css" href="../../assets/plugins/sweetalert/sweetalert.css">

    <link rel="stylesheet" href="assets/plugins/select2/select2.min.css" />

    <link href='//fonts.googleapis.com/css?family=Sansita+One' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>

    <style>
        .header-fixed {
            width: 100% 
        }

        .header-fixed > thead,
        .header-fixed > tbody,
        .header-fixed > thead > tr,
        .header-fixed > tbody > tr,
        .header-fixed > thead > tr > th,
        .header-fixed > tbody > tr > td {
            display: block;
        }

        .header-fixed > tbody > tr:after,
        .header-fixed > thead > tr:after {
            content: ' ';
            display: block;
            visibility: hidden;
            clear: both;
        }

        .header-fixed > tbody {
            overflow-y: auto;
            max-height: 450px;
        }

        .header-fixed > tbody > tr > td.titulo,
        .header-fixed > thead > tr > th.titulo {
            width: 60%;
            float: left;
        }

        .header-fixed > tbody > tr > td.check,
        .header-fixed > thead > tr > th.check {
            width: 40%;
            float: left;
        }
    </style>
</head>
<body>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <section class="content-header">
                <h1>Distribucion de registros de backoffice</h1>
            </section>
        
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Distribucion de registros de backoffice</h3>
                            </div>

                            <div class="box-body">

                                <form id="formAsignarRegistros">

                                    <input type="hidden" name="filtros" id="filtros" value="0">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="tipoBusquedaRegistros" id="buscarNoAsignados" value="noAsignados" checked onclick="cambioTipoBusqueda()">
                                                        Sin asignar
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="tipoBusquedaRegistros" id="buscarAsignados" value="asignados" onclick="cambioTipoBusqueda()">
                                                        Asignados
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="tipoBusquedaRegistros" id="buscarTodos" value="todos" onclick="cambioTipoBusqueda()">
                                                        Todos
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Paso/Tarea de backoffice</label>
                                                <select name="pasoBackoffice" id="pasoBackoffice" class="form-control" onchange="analizarPasoBackoffice()">
                                                    <option value="0">Seleccione</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="button" id="btnAgregarNuevoFiltro" class="btn btn-primary btn-sm pull-right" style="display: none;" onclick="agregarNuevoFiltro()">Agregar filtros</button>
                                        </div>
                                        <div class="col-md-12" id="listaFiltros" style="margin-top: 30px;">

                                        </div>
                                        <div class="col-md-12">
                                            <button type="button" id="btnBuscarPorFiltro" class="btn btn-primary btn-sm pull-right" onclick="BuscarPorNuevoFiltro()" style="display: none;">Buscar</button>
                                        </div>
                                    </div>
    
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-md-12">
                                            <p id="textoContador">Debe seleccionar un paso de backoffice para continuar</p>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="seleccionarTodosRegistros()">Seleccionar todos los registros</button>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="deseleccionarTodosRegistros()">Deseleccionar todos los registros</button>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Solo se mostrarán los primeros 250 registros que coinciden con la búsqueda, Si son mas por favor realice la asignación de estos registros y luego repita la consulta para los demás</h4>
                                            <div>
                                                <table id="tablaRegistros" class="table table-hover header-fixed">
                                                    <thead>
                                                        <tr>
                                                            <th class="titulo">Nombre</th>
                                                            <th class="check">Seleccionar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Asignar a</h3>
                                            <h4>Agentes Asignados al paso</h4>
                                            <div class="form-group" id="listaAgentes">
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary" id="btnAsignacionRegistros" onclick="asignarRegistros()" disabled>Asignar registros al agente seleccionado</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</body>
<!-- jQuery 2.2.3 -->
<script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/select2/select2.full.min.js"></script>
<!-- datepicker -->
<script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- FastClick -->
<script src="assets/plugins/fastclick/fastclick.js"></script>
<script src="assets/timepicker/jquery.timepicker.js"></script>
<script src="assets/plugins/sweetalert/sweetalert.min.js"></script>  
<script src="assets/plugins/iCheck/icheck.min.js"></script>
<script src="assets/js/jquery.validate.js"></script>
<script src="assets/js/numeric.js"></script>
<script src="assets/js/blockui.js"></script>
<script src="assets/js/alertify.js"></script>

<script>

    // VARIABLES GLOBALES
    var pregunBd = [];
    var tieneCampanas = false;

    $(function(){

        cargaInicial();

    });

    function cargaInicial(){
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            dataType: 'json',
            data: { obtenerTareasBackoffice:true, est:'<?php echo $est ?>'},
            success: function(data){

                if(data.pasos && data.pasos.length > 0){

                    let opcionesBackoffice = '<option value="0">Seleccione</option>';

                    data.pasos.forEach(element => {
                        opcionesBackoffice += `<option value="${element.id}">${element.nombre}</option>`;
                    });

                    $("#pasoBackoffice").html(opcionesBackoffice);

                }

            },
            error: function(data){
                console.log(data);
            }
        });
    }

    function analizarPasoBackoffice(){
        
        let pasoBackofficeId = $("#pasoBackoffice").val();
        let tipoBusqueda = document.querySelector('input[name="tipoBusquedaRegistros"]:checked').value;
        
        $("#tablaRegistros tbody").html('');
        $("#listaAgentes").html('');

        $("#btnAsignacionRegistros").attr('disabled', true);

        $("#listaFiltros").html('');

        if(pasoBackofficeId == 0){
            $("#textoContador").html('Debe seleccionar un paso de backoffice para continuar');
            $("#listaAgentes").html('Debe seleccionar un paso backoffice para traer la lista de agentes');
            $("#btnAgregarNuevoFiltro").hide();
            $("#btnBuscarPorFiltro").hide();
            return;
        }

        // Necesito validar las flechas que estan conectadas a este paso
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            dataType: 'json',
            data: { validarRegistrosBackoffice:true, paso:pasoBackofficeId, tipoBusqueda:tipoBusqueda},
            success: function(data){

                if(data.valido){

                    $("#btnAgregarNuevoFiltro").show();

                    let texto = `Registros que cumplen condiciones para el paso <strong>${data.pasoBackoffice.nombre}</strong> es ${data.cantidadRegistrosValidos}`;
                    $("#textoContador").html(texto);
                    
                    let registrosValidosHtml = '';

                    if(data.registrosValidos && data.registrosValidos.length > 0){

                        data.registrosValidos.forEach( (element, index )=> {

                            let agente = '';
                            let registroId = element.id + '_M0';

                            // Valido si el registro esta en la muestra o no
                            if(element.muestraId){
                                // Ahora si tiene muestra

                                // Seteamos el valor del registro
                                registroId = element.id + '_M' +element.muestraId;

                                // Mostramos el agente
                                if(element.agenteAsignado){
                                    agente = ` - Asignado a <b>${element.agenteAsignado}</b>`;
                                }else{
                                    agente = ' - Asignado al paso pero no tiene agente asignado'
                                }
                            }else{
                                if(tipoBusqueda == 'todos'){
                                    agente = ` - Sin asignar al paso ${data.pasoBackoffice.nombre}`;
                                }
                            }


                            registrosValidosHtml += `
                                <tr>
                                    
                                    <td class="titulo">
                                        <p><strong>${element.principal}</strong></p>
                                        <span>${element.secundario}</span>
                                        <span>${agente}</span>
                                    </td>
                                    <td class="check">
                                        <input type="checkbox" class="check-registro" name="camposInsertar[]" value="${registroId}">
                                    </td>
                                </tr>
                            `;
                        });
                        
                    }else{
                        registrosValidosHtml = '<p>No hay registros validos</p>';
                    }
                    
                    $("#tablaRegistros tbody").html(registrosValidosHtml);

                    let listaAgentesHtml = '';

                    // lista agentes
                    if(data.agentes && data.agentes.length > 0){

                        data.agentes.forEach(element => {
                            listaAgentesHtml += `
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="agente" id="agente${element.id}" value="${element.id}">${element.camp1} - ${element.camp2}, <b>(Hoy asig ${element.cant_hoy}) - (Total asig ${element.cant_total})</b>
                                    </label>
                                </div>
                            `;
                        });

                    }else{
                        listaAgentesHtml = 'Este paso de backoffice no tiene agentes asignados';
                    }
                    $("#listaAgentes").html(listaAgentesHtml);

                    $("#btnAsignacionRegistros").attr('disabled', false);

                }else{
                    $("#textoContador").html('Esta tarea no está conectada al proceso, por lo tanto no existen registros para asignar. Si desea conectarla puede dibujar en el flujograma una flecha desde otro paso del proceso hasta este.');
                    alertify.error('Esta tarea no está conectada al proceso, por lo tanto no existen registros para asignar. Si desea conectarla puede dibujar en el flujograma una flecha desde otro paso del proceso hasta este.');
                    $("#tablaRegistros tbody").html('<p>No hay registros validos</p>');
                }
                
                if(data.camposBd){
                    pregunBd = data.camposBd;
                }

                tieneCampanas = data.contieneCampana;

            },
            error: function(data){
                console.log(data);
                alertify.error("Se presento un error al traer la informacion del paso backoffice, verifica si todo esta bien configurado");
            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> Cargando informacion' });
            },
            complete : function(){
                $.unblockUI();
            }
        });

    }

    function BuscarPorNuevoFiltro(){

        let pasoBackofficeId = $("#pasoBackoffice").val();

        let tipoBusqueda = document.querySelector('input[name="tipoBusquedaRegistros"]:checked').value;
        console.log('desde los filtros',tipoBusqueda);

        if(pasoBackofficeId == 0){
            $("#textoContador").html('Debe seleccionar un paso de backoffice para continuar');
            $("#listaAgentes").html('Debe seleccionar un paso backoffice para traer la lista de agentes');
            return;
        }

        let campo = [], operador = [1], condicion = [], valor = [], tipoCampo = [];

        // Transformo las condiciones en arreglos
        $(".mi_select_pregun").each(function(){
            campo.push($(this).val());
        });

        $(".mi-operador").each(function(){
            operador.push($(this).val());
        });

        $(".mi-condicion").each(function(){
            condicion.push($(this).val());
        });

        $(".mi-valor").each(function(){
            valor.push($(this).val());
        });

        $(".mi-tipoCampo").each(function(){
            tipoCampo.push($(this).val());
        });


        // Necesito validar las flechas que estan conectadas a este paso
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            dataType: 'json',
            data: { validarRegistrosBackoffice:true, paso:pasoBackofficeId, tipoBusqueda:tipoBusqueda, filtrosPersonalizados:true, campo:campo, operador:operador, dyTr_condicion:condicion, valor:valor, tipoCampo:tipoCampo},
            success: function(data){

                if(data.valido){
                    let texto = `Registros que cumplen condiciones para el paso <strong>${data.pasoBackoffice.nombre}</strong> es ${data.cantidadRegistrosValidos}`;
                    $("#textoContador").html(texto);
                    
                    let registrosValidosHtml = '';

                    if(data.registrosValidos && data.registrosValidos.length > 0){

                        data.registrosValidos.forEach( (element, index )=> {

                            let agente = '';
                            let registroId = element.id + '_M0';

                            // Valido si el registro esta en la muestra o no
                            if(element.muestraId){
                                // Ahora si tiene muestra

                                // Seteamos el valor del registro
                                registroId = element.id + '_M' +element.muestraId;

                                // Mostramos el agente
                                if(element.agenteAsignado){
                                    agente = ` - Asignado a <b>${element.agenteAsignado}</b>`;
                                }else{
                                    agente = ' - Asignado al paso pero no tiene agente asignado'
                                }
                            }else{
                                if(tipoBusqueda == 'todos'){
                                    agente = ` - Sin asignar al paso ${data.pasoBackoffice.nombre}`;
                                }
                            }

                            registrosValidosHtml += `
                                <tr>
                                    
                                    <td class="titulo">
                                        <p><strong>${element.principal}</strong></p>
                                        <span>${element.secundario}</span>
                                        <span>${agente}</span>
                                    </td>
                                    <td class="check">
                                        <input type="checkbox" class="check-registro" name="camposInsertar[]" value="${registroId}">
                                    </td>
                                </tr>
                            `;
                        });
                        
                    }else{
                        registrosValidosHtml = '<p>No hay registros validos</p>';
                    }
                    
                    $("#tablaRegistros tbody").html(registrosValidosHtml);

                    $("#btnAsignacionRegistros").attr('disabled', false);

                }else{
                    $("#textoContador").html('Esta tarea no está conectada al proceso, por lo tanto no existen registros para asignar. Si desea conectarla puede dibujar en el flujograma una flecha desde otro paso del proceso hasta este.');
                    alertify.error('Esta tarea no está conectada al proceso, por lo tanto no existen registros para asignar. Si desea conectarla puede dibujar en el flujograma una flecha desde otro paso del proceso hasta este.');
                    $("#tablaRegistros tbody").html('<p>No hay registros validos</p>');
                }
                
                if(data.camposBd){
                    pregunBd = data.camposBd;
                }

                tieneCampanas = data.contieneCampana;

            },
            error: function(data){
                console.log(data);
                alertify.error("Se presento un error al traer la informacion del paso backoffice, verifica si todo esta bien configurado");
            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> Cargando informacion' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

    function asignarRegistros(){

        if(!$("#formAsignarRegistros input[name='agente']:radio").is(':checked')){
            alertify.error("Debe seleccionar un agente");
            return;
        }

        if(!$("#formAsignarRegistros input[name='camposInsertar[]']:checkbox").is(':checked')){
            alertify.error("Debe seleccionar por lo menos un registro");
            return;
        }

        let formData = new FormData($("#formAsignarRegistros")[0]);

        $.ajax({
            url: '<?=$url_crud;?>?asignarRegistros=true',
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){

                if(data.valido){
                    alertify.success("Los registros se insertaron correctamente");
                }else{
                    alertify.error("Hubo un problema y no se pudo insertar todos los registros");
                    console.log(data.error);
                }

                analizarPasoBackoffice();

            },
            error: function(data){
                console.log(data);
                alertify.error("Se genero un error al asignar los registros");
            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> Realizando proceso de asignacion' });
            },
            complete : function(){
                $.unblockUI();
            }
        });

    }

    function seleccionarTodosRegistros(){
        $('.check-registro').prop('checked', true);
    }

    function deseleccionarTodosRegistros(){
        $('.check-registro').prop('checked', false);
    }

    function agregarNuevoFiltro(){
        
        let cantFiltros = $("#filtros").val();
        var cantFiltrosCreados = document.getElementsByClassName("filtroBackoffice").length;

        $("#btnBuscarPorFiltro").show();

        let and = '';
        let botonDelete = '';

        let val1 = 5;
        let val2 = 3;

        if(cantFiltrosCreados > 0){
            and = `
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control mi-operador" name="operador${cantFiltros}" id="operador${cantFiltros}" numero="${cantFiltros}">
                            <option selected value="1">Y</option>
                            <option value="0">O</option>
                        </select>
                    </div>
                </div>
            `;

            val1 = 3;
        }

        let camposBd = '';

        pregunBd.forEach(element => {
            camposBd += `<option value="${element.PREGUN_ConsInte__b}" tipo="${element.PREGUN_Tipo______b}">${element.PREGUN_Texto_____b}</option>`;
        });

        let opcionesPreguntas = `
            <option value='0' tipo='3'>Seleccione</option>
            <option value='_CoInMiPo__b' tipo='3'>ID BD</option>
            <option value='_FechaInsercion' tipo='5'>FECHA CREACION</option>
            ${camposBd}
            <option value='_ConIntUsu_b' tipo='_ConIntUsu_b'>Usuario</option>
        `;

        // Lo desabilito debido a que funcionan con muestras de campaña
        // if(tieneCampanas === true){
        //     opcionesPreguntas += `
        //         <option value='_NumeInte__b' tipo='3'>Numero de intentos</option>
        //         <option value='_UltiGest__b' tipo='MONOEF'>Ultima gesti&oacute;n</option>
        //         <option value='_GesMasImp_b' tipo='MONOEF'>Gesti&oacute;n mas importante</option>
        //         <option value='_FecUltGes_b' tipo='5'>Fecha ultima gesti&oacute;n</option>
        //         <option value='_FeGeMaIm__b' tipo='5'>Fecha gesti&oacute;n mas importante</option>
        //         <option value='_ConUltGes_b' tipo='ListaCla'>Clasificaci&oacute;n ultima gesti&oacute;n</option>
        //         <option value='_CoGesMaIm_b' tipo='ListaCla'>Clasificaci&oacute;n mas importante</option>
        //     `;
        // }

        // opcionesPreguntas += `
        //     <option value='_Estado____b' tipo='_Estado____b'>ESTADO_DY (tipo reintento)</option>
        //     <option value='_Activo____b' tipo='_Activo____b'>Registro activo</option>
        // `;

        let cuerpo = `
            <div class="row filtroBackoffice" id="id${cantFiltros}">
                ${and}

                <div class="col-md-${val1}">
                    <div class="form-group">
                        <select class="form-control mi_select_pregun" name="pregunBack${cantFiltros}" id="pregunBack${cantFiltros}" numero="${cantFiltros}">
                            ${opcionesPreguntas}
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <select name="condicion_${cantFiltros}" id="condicion_${cantFiltros}" class="form-control mi-condicion">
                            <option value="0">Condición</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3" id="divValor${cantFiltros}">
                    <div class="form-group">
                        <input type="text" name="idTareaBack${cantFiltros}" id="valor${cantFiltros}" class="form-control mi-valor" placeholder="<?php echo $filtros_valor__c; ?>">
                    </div>
                </div>

                <input type="hidden" name="tipo_campo_${cantFiltros}" id="tipo_campo_${cantFiltros}" class="form-control mi-tipoCampo">

                <div class="col-md-1">
                    <div class="form-group">
                        <button class="btn btn-danger btn-sm deleteFiltro" title="<?php echo $str_opcion_elimina;?>" type="button" id="${cantFiltros}">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        cantFiltros++;
        $("#filtros").val(cantFiltros);
        $("#listaFiltros").append(cuerpo);

        $(".mi_select_pregun").change(function(){

            let id = $(this).attr('numero');
            let tipo = $("#pregunBack"+id+" option:selected").attr('tipo');
            let valor = $(this).val();

            $("#tipo_campo_"+id).val(tipo);

            let options = "";
            
            if(tipo == '1' || tipo == '2'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='LIKE_1'>INICIE POR</option>";
                options += "<option value='LIKE_2'>CONTIENE</option>";
                options += "<option value='LIKE_3'>TERMINE EN</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor"+id+"' id='valor"+id+"' class='form-control mi-valor' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);
            }

            if(tipo == '4' || tipo == '3'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='menorQue'>MENOR QUE</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor"+id+"' id='valor"+id+"' class='form-control mi-valor' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

                $("#valor"+id).numeric();
            }

            if(tipo == '5'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor"+id+"' id='valor"+id+"' class='form-control mi-valor' placeholder='FECHA'>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='menorQue'>MENOR QUE</option>";

                $("#valor"+id).datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    format: "yyyy-mm-dd",
                });
            }

            if(tipo == '10'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='menorQue'>MENOR QUE</option>";

                cuerpo = "<div class='form-group'><input type='time' max='23:59:59' min='00:00:00' step='1' name='valor"+id+"' id='valor"+id+"' class='form-control mi-valor' placeholder='00:00:00'></div>";

                $("#divValor"+id).html(cuerpo);
            }

            if( tipo == '_Activo____b' || tipo == '_Estado____b' || tipo == '_ConIntUsu_b' || tipo == 'MONOEF' || tipo == 'ListaCla' || tipo == '8' || tipo == '6'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
            } 

            if(tipo == '8'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control mi-valor'>";
                cuerpo += "<option value='1'>ACTIVO</option>";
                cuerpo += "<option value='0'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            if(tipo == '_Activo____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control mi-valor'>";
                cuerpo += "<option value='-1'>ACTIVO</option>";
                cuerpo += "<option value='0'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            if(tipo == 'ListaCla'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control mi-valor'>";
                cuerpo += "<option value='1'>Devoluciones</option>";
                cuerpo += "<option value='2'>No contactable</option>";
                cuerpo += "<option value='3'>Sin gestion</option>";
                cuerpo += "<option value='4'>No contactado</option>";
                cuerpo += "<option value='5'>Contactado</option>";
                cuerpo += "<option value='6'>No efectivo</option>";
                cuerpo += "<option value='7'>Efectivo</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            if(tipo == '_Estado____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control mi-valor'>";
                cuerpo += "<option value='0'>SIN GESTI&Oacute;N</option>";
                cuerpo += "<option value='1'>REINTENTO AUTOMATICO</option>";
                cuerpo += "<option value='2'>AGENDA</option>";
                cuerpo += "<option value='3'>NO REINTENTAR</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            // Llenamos la lista de agentes seleccionados en backoffice
            if(tipo == '_ConIntUsu_b'){

                $.ajax({
                    url    : '<?=$url_crud;?>?getUsuariosBackoffice=true',
                    type   : 'post',
                    data   : { backoffice : $("#pasoBackoffice").val() },
                    dataType: 'json',
                    success : function(data){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control mi-valor'  id='valor"+id+"'>";
                        cuerpo += "<option value='-1'>No asignado</option>";

                        if(data.usuarios && data.usuarios.length > 0){
                            data.usuarios.forEach(item => {
                                cuerpo += "<option value='"+item.id+"'>"+ item.nombre+"</option>";
                            });
                        }

                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }

            if(tipo == 'MONOEF'){
                $.ajax({
                    url    : '<?php echo $url_crud_manager?>/cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?getMonoefCampanha=true',
                    type   : 'post',
                    data   : { campan : $("#id_campanaFiltros").val() },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control mi-valor'  id='valor"+id+"'>";
                        $.each(JSON.parse(data), function(i, item) {
                            cuerpo += "<option value='"+item.MONOEF_ConsInte__b+"'>"+ item.MONOEF_Texto_____b+"</option>";
                        }); 
                            cuerpo += "<option value='-12'>Mensaje enviado</option>";
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }

            if(tipo == '6'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                $.ajax({
                    url    : '<?php echo $url_crud_manager?>/cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
                    type   : 'post',
                    data   : { lista : valor , getListasDeEsecampo : true },
                    dataType: 'json',
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control mi-valor'  id='valor"+id+"'>";
                        $.each(data, function(i, item) {
                            cuerpo += "<option value='"+item.LISOPC_ConsInte__b+"'>"+ item.LISOPC_Nombre____b+"</option>";
                        }); 
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }

            if(tipo == '11'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                $.ajax({
                    url    : '<?php echo $url_crud_manager?>/cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
                    type   : 'post',
                    data   : { lista : valor , getListasCompleja : true },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control mi-valor'  id='valor"+id+"'>";
                        cuerpo += data;
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }
            
            $("#condicion_"+id).html(options);
        });

        $(".deleteFiltro").click(function(){
            let id = $(this).attr('id');
            $("#id"+id).remove();

            let cantFiltrosCreados = document.getElementsByClassName("filtroBackoffice").length;
            if(cantFiltrosCreados == 0){
                $("#btnBuscarPorFiltro").hide();
            }
        });

        let tipoBusqueda = document.querySelector('input[name="tipoBusquedaRegistros"]:checked').value;

        if(tipoBusqueda == 'noAsignados'){
            $("#listaFiltros .mi_select_pregun option[value=_ConIntUsu_b]").hide();
        }
    }

    function cambioTipoBusqueda(){

        if($("#pasoBackoffice").val() != 0){

            // Primero bloqueamos los filtros que sean de usuario o agente asignado
            let tipoBusqueda = document.querySelector('input[name="tipoBusquedaRegistros"]:checked').value;
            if(tipoBusqueda == 'noAsignados'){
                
                $(".mi_select_pregun option[value=_ConIntUsu_b]").hide();

                // Busco todas las condiciones cuyo filtro sea usuario
                $("#listaFiltros .mi_select_pregun").each(function(){
                    if($(this).val() == '_ConIntUsu_b'){
                        // traigo el num para eliminarlo
                        let num = $(this).attr('numero');
                        $("button#"+num).click();
                    }
                });

            }else{
                $(".mi_select_pregun option[value=_ConIntUsu_b]").show();
            }


            let cantFiltros = document.getElementsByClassName("filtroBackoffice").length;

            if(cantFiltros > 0){
                BuscarPorNuevoFiltro();
            }else{
                analizarPasoBackoffice();
            }

        }

    }

</script>

</html>