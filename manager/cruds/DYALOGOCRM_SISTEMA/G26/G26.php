<?php
// Esta es la vista del bot

$url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G26/G26_CRUD.php";
?>

<link rel="stylesheet" href="<?=base_url?>assets/plugins/selectize.js-master/dist/css/selectize.css">
<link rel="stylesheet" href="<?=base_url?>assets/plugins/selectize.js-master/dist/css/selectize.bootstrap3.css">
<link rel="stylesheet" href="<?=base_url?>assets/plugins/iCheck/all.css">

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
    
    /* .modal { overflow: auto !important; } */

    .selectize-control.multi .selectize-input>div{
		background: #337ab7;
        color: #f6f6f6;
	}

    .selectize-control.multi .selectize-input>div.green{
		background: #00a65a;
        color: #f6f6f6;
	}

    .titulo-tabla-condiciones{
        padding: 8px;
        font-weight: bold
    }

    .text-color-white{
        color: whitesmoke;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
        border-color: #367fa9;
    }

    table {
        table-layout: fixed;
        word-wrap: break-word;
    }

        table th, table td {
            overflow: hidden;
        }

</style>

<!-- Principal -->
<div>

    <!-- Form para exportar a excel los registros que no cumplen la condicion -->
    <form action="<?=$url_crud;?>" method="post" id="export-excel-preguntas-no-entrega">
        <input type="hidden" id="pn_exportar" name="pn_exportar" value="si">
        <input type="hidden" id="pn_export_excel_paso_id" name="pn_export_excel_paso_id" value="0">
        <input type="hidden" id="pn_export_excel_paso_seccion" name="pn_export_excel_paso_seccion" value="0">
        <input type="hidden" id="pn_export_excel_paso_ejecutor" name="pn_export_excel_paso_ejecutor" value="">
    </form>

    <div class="box box-primary">
        <div class="box-header">
            <div class="box-tools">
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12" id="div_formularios">
                    <div>
                        <button class="btn btn-default" id="Save">
                            <i class="fa fa-save"></i>
                        </button>

                        <button type="button" class="btn btn-info pull-right" onclick="obtenerPreguntasBotNoEntrego('bot')">
                            Respuestas que el bot no pudo entregar
                        </button>
    
                        <button type="button" class="btn btn-info pull-right" style="margin-right: 10px;" onclick="abrirTagsGlobales()">
                            Tags globales
                        </button>
                    </div>

                    <div>
                        <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                            
                            <input type="hidden" name="pasoId" id="pasoId" value="<?php if(isset($_GET['id_paso'])){ echo  $_GET['id_paso']; }else{ echo "0"; } ?>">
                            <input type="hidden" name="oper" id="oper" value='add'>
                            <input type="hidden" name="seccionPrincipal" id="seccionPrincipal">
                            <input type="hidden" name="autorespuestaPrincipal" id="autorespuestaPrincipal">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="18">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <!-- CAMPO TIPO TEXTO -->
                                                <div class="form-group">
                                                    <label for="G14_C137" id="LblG14_C137"><?php echo $str_nombre_mail_ms; ?></label>
                                                    <input type="text" class="form-control input-sm" id="nombre_bot" value="bot_<?=$_GET['id_paso']?>"  name="nombre_bot"  placeholder="<?php echo $str_nombre_mail_ms; ?>" required>
                                                </div>
                                                
                                                <div class="col-md-1 col-xs-1" style="display:none">
                                                    <div class="form-group">
                                                        <label for="pasoActivo" id="LblpasoActivo">ACTIVO</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="-1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself" checked> 
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- FIN DEL CAMPO TIPO TEXTO -->
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Acción a realizar cuando no encuentre respuesta</label>
                                                            <select name="accion_NoRes" id="accion_NoRes" class="form-control input-sm" onchange="cambiarAccion('NoRes')">
                                                                <option value="0">Dar solo respuesta</option>
                                                                <option value="2_1">Transferir a otra seccion</option>
                                                                <option value="2_2">Transferir a Bot</option>
                                                                <option value="1">Transferir a campaña</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Detalle accion</label>
                                                            <select class="form-control input-sm" id="dar_respuesta_NoRes" name="dar_respuesta_NoRes" disabled>
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                            <select class="form-control input-sm" style="display: none;" id="campan_NoRes" name="campan_NoRes">
                                                            </select>
                                                            <select class="form-control input-sm" style="display: none;" id="bot_seccion_NoRes" name="bot_seccion_NoRes">
                                                            </select>
                                                            <select class="form-control input-sm" style="display: none;" id="bot_NoRes" name="bot_NoRes" onchange="mostrarSeccionesBot('NoRes')">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" style="display:none" id="row_seccion_botNoRes">
                                                        <div class="form-group">
                                                            <label for="">Seccion del bot</label>
                                                            <select class="form-control input-sm" id="seccion_bot_NoRes" name="seccion_bot_NoRes">
                                                                <option value="0">Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Frase cuando no encuentra respuesta</label>
                                                            <textarea name="dyTr_textoNoRespuesta" id="textoNoRespuesta" cols="10" rows="1" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">    
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Tiempo activo del bot (min)</label>
                                                            <input type="text" name="timeoutBot" id="timeoutBot" class="form-control input-sm" value="60">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Ejecutar accion despues del timeout</label>
                                                            <select name="actionAfterTimeout[]" id="actionAfterTimeout" class="form-control input-sm" multiple="multiple" style="width:100%">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Frase cuando pasa el tiempo activo</label>
                                                            <textarea name="dyTr_timeoutBotFrase" id="timeoutBotFrase" cols="10" rows="1" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row" style="margin-top:15px">
                                            <div class="col-md-12">
                                                <div class="panel box box-primary box-solid">
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#seccionesBot">
                                                                Secciones del bot
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="seccionesBot" class="panel-collapse collapse in">
                                                        <div class="box-body">
                                                            <div class="col-md-1">
                                                                <div id="listaSeccionesAElejir" style="background-color: #F8F8F8; width:100%; height: 600px"></div>
                                                            </div>
                                                            <div class="col-md-11">
                                                                <div id="seccionesGrafico" style="background-color: #F8F8F8; border: solid 1px black; width:100%; height:600px;"></div>
                                                            </div>
                                                            <textarea name="mySavedModelBot" id="mySavedModelBot" cols="30" rows="10" style="display:none"></textarea>
                                                        </div>
                                                    </div>
                                                </div> 

                                            </div>
                                
                                        </div>


                                        <div class="row" style="margin-top:15px">
                                            <div class="col-md-12">
                                                <div class="panel box box-primary box-solid">
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="" href="#secReports" aria-expanded="false" class="collapsed">
                                                                REPORTES
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="secReports" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                                                        <div style="padding: 15px;">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=bot&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url?>assets/plugins/selectize.js-master/dist/js/standalone/selectize.js"></script>
<script src="<?=base_url?>assets/plugins/tiny/tinymce.min.js"></script>
<script src="<?=base_url?>assets/plugins/tiny/tinymce_languages/langs/es.js"></script>

<script>
    // Variables globales
    var collapseActive = true;

    var opcionesSecciones = '';
    var opcionesBots = '';
    var opcionesCampana = '';
    var opcionesCamposBd = '';
    var opcionesCamposGestion = '';
    var opcionesPasosEjecutables = '';
    var opcionesWebservicesGlobales = '';
    var opcionesCamposG = '';

    var camposBd = []; // Aqui va los campos de la base datos
    var variablesDinamicasBot = []; // Aqui almaceno las variables dinamicas de la bd
    var variablesDinamicasWs = []; // Aqui almaceno las variables dinamicas de las respuesta del websvice
    var variablesDinamicasConsultaDy = [];
    var variablesDinamicas = ''; // Aqui ya almaceno formanteado en html las variables dinamicas

    var tagsComunes = []; // En esta variable guardo la lista de los tags comunes del huesped

    var variablesDinamicasObj = []; // Aqui va el objeto de las variables dinamicas
    var baseOrigen = 0; // es la base de la estrategia

$(function(){

    cargarDatosDelPaso();

    // Eventos

    // Eventos jquery
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });

    // Guarda la seccion principal
    $('#Save').click(function(){
        guardarFlujoBot('botonSave');
    });

    // Eventos de la seccion

    // Que la seccion transaccional sea shortable
    $("#accionesBotTransaccional").sortable({
        axis: 'y',
        start: function(e, ui){
            $(ui.item).find('textarea.myTextarea').each(function(){
                tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
            });
        },
        stop: function(e, ui) {
            $(ui.item).find('textarea.myTextarea').each(function(){
                tinymce.execCommand('mceAddEditor', false, $(this).attr('id'));
            });
        },
        update: function (event, ui) {
            var data = $(this).sortable('serialize');
            $("#txtOrdenAccionesBot").val(data);
        }
    });

    // Abre y cierra el todas las modales al tiempo
    $("#toggleAcordeon").click(function(){
        if($('#accionesBotConversacional .panel-collapse.in, #accionesBotTransaccional .panel-collapse.in, #accionesBotConsultaDatos .panel-collapse.in, #accionFinalDefecto .panel-collapse.in, #row_accion_inicial .panel-collapse.in').length > 0){
            collapseActive = false;
            $("#accionesBotConversacional .collapse, #accionesBotTransaccional .collapse, #accionesBotConsultaDatos .collapse, #accionFinalDefecto .collapse, #row_accion_inicial .collapse").collapse('hide');
        }else{
            if(collapseActive){
                collapseActive = false;
                $("#accionesBotConversacional .collapse, #accionesBotTransaccional .collapse, #accionesBotConsultaDatos .collapse, #accionFinalDefecto .collapse, #row_accion_inicial .collapse").collapse('hide');
            }else{
                collapseActive = true;
                $("#accionesBotConversacional .collapse, #accionesBotTransaccional .collapse, #accionesBotConsultaDatos .collapse, #accionFinalDefecto .collapse, #row_accion_inicial .collapse").collapse('show');
            }
        }
    });

    // Cierra la modal de configuracion
    $(".cerrar-modal-config").click(function(){
        $("#modalConfiguracionSeccion").modal("hide");
        tinymce.remove("textarea#rpta_accion_inicial");
    });

    $(".cerrar-modal-seleccionar-flecha").click(function(){
        $("#seleccionaFlecha").modal("hide");
    });

    $('#seccionBd').on('select2:select', function (e) {
        cambiarBd($(this).val());

        if($(this).val() == baseOrigen){
            $("#llavePrincipal").prop('disabled', true);console.log("ersa");
        }else{
            $("#llavePrincipal").prop('disabled', false);console.log("er");
        }
    });

    // Eventos de tags

    // Cierra la modal de tags comunes
    $(".cerrar-modal-tags-comunes").click(function(){
        $("#tagsComunesModal").modal("hide");
    });

    // Cierra la modal de tags globales
    $(".cerrar-modal-tags-globales").on('click', function(){
        $('#tagsGlobales').modal('hide');
    });

    $("#addCampotagsComunesbtn").click(function(){
        addCampotagsComunes("add");
    });

    // Eventos de preguntas que no entrega el bot

    $(".cerrar-modal-consulta-preguntas").click(function(){
        $("#consultaPreguntasModal").modal("hide");
    });

    // Eventos de avanzado

    // Cierra la modal de la seccion de avanzado
    $(".cerrar-modal-seccion-avanzado").on('click', function(){
        $('#seccionAvanzado').modal('hide');
    });

    // Habilito o desabilito la validacion de campos
    $('input[type=radio][name=validacionRadio]').change(function() {
        if (this.value == '0') {
            $("#configuracionDeLosCampos").hide();
        }
        else if (this.value == '1') {
            $("#configuracionDeLosCampos").show();
        }
    });

    $("#tipoCampo").change(function(){

        $("#maxTexto").hide();
        $("#rangoNumeros").hide();
        $("#rangoFechas").hide();
        $("#campoListas").hide();

        if(this.value == '3'){
            $("#rangoNumeros").show();
        }else if(this.value == '5'){
            $("#rangoFechas").show();
        }else if(this.value == '6'){
            $("#campoListas").show();
        }else if(this.value == '1'){
            $("#maxTexto").show();
        }

    });

    // Eventos de flechas

    $(".cerrar-modal-flechas").on('click', function(){
        $('#flechaModal').modal('hide');

        let paso = <?=$_GET['id_paso']?>;

        recargarFlujograma(paso);
    });

    // Timepickers
    $("#campoFechaMinima").datepicker({
        language: "es",
        autoclose: true,
        todayHighlight: true
    });

    $("#campoFechaMaxima").datepicker({
        language: "es",
        autoclose: true,
        todayHighlight: true
    });

    $("#campoCantMaximaTexto").numeric();

    $("#campoValorMinimo").numeric();
        
    $("#campoValorMaximo").numeric();

    $("#timeoutBot").numeric();
    
    $("#campoLista").select2({
        // dropdownParent: $("#campos_"+ campo)
    });

    $("#actionAfterTimeout").select2({
        placeholder: "Seleccione pasos que se ejecutaran después del timeout"
    });
});

// Funciones

// Configuracion para inicializar el flujograma
function init(){
    var $ = go.GraphObject.make;

    myDiagramBot = $(go.Diagram, "seccionesGrafico",  // must name or refer to the DIV HTML element
        {
            initialContentAlignment: go.Spot.Center,
            allowDrop: true,  // must be true to accept drops from the Palette
            "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
            "LinkRelinked": showLinkLabel,
            "animationManager.duration": 800, // slightly longer than default (600ms) animation
            "undoManager.isEnabled": true  // enable undo & redo
        }
    );

    // when the document is modified, add a "*" to the title and enable the "Save" button
    myDiagramBot.addDiagramListener("Modified", function(e) {
        var button = document.getElementById("SaveButton");
        if (button) button.disabled = !myDiagram.isModified;
        var idx = document.title.indexOf("*");

        if (myDiagramBot.isModified) {
            if (idx < 0) document.title += "*";
        } else {
            if (idx >= 0) document.title = document.title.substr(0, idx);
        }

        //console.log(e);
        if (e.change === go.ChangedEvent.Remove) {
            alert(evt.propertyName + " removed a node with key: " + e.oldValue.key);
        }
    });

    myDiagramBot.addModelChangedListener(function(evt) {
        // ignore unimportant Transaction events
        if (!evt.isTransactionFinished) return;
        var txn = evt.object;  // a Transaction
        if (txn === null) return;

        // iterate over all of the actual ChangedEvents of the Transaction
        txn.changes.each(function(e) {
            // ignore any kind of change other than adding/removing a node
            if (e.modelChange !== "nodeDataArray") return;
            // record node insertions and removals
            if (e.change === go.ChangedEvent.Insert) { 
                // alert(evt.propertyName + " added node with key: " + e.newValue.key);
                crearSeccion();
            } else if (e.change === go.ChangedEvent.Remove) {
                if(e.oldValue.tipoSeccion == '3' || e.oldValue.tipoSeccion == '4'){
                    alertify.warning("No se puede eliminar la seccion "+ e.oldValue.nombrePaso + " ya que es una seccion por defecto");
                    load();
                    return;
                }
                eliminarSeccion(e.oldValue.key);
            }
        });
    });

    myDiagramBot.addModelChangedListener(function(evt) {
        // ignore unimportant Transaction events
        if (!evt.isTransactionFinished) return;
        var txn = evt.object;  // a Transaction
        if (txn === null) return;

        // iterate over all of the actual ChangedEvents of the Transaction
        txn.changes.each(function(e) {
            // record node insertions and removals
            if (e.change === go.ChangedEvent.Property) {
                if (e.modelChange === "linkFromKey") {
                    alert(evt.propertyName + " changed From key of link: " + e.object + " from: " + e.oldValue + " to: " + e.newValue);
                    cambiarConexionToFlecha(e.oldValue, e.object.to, e.newValue, 'from', e.object.fromPort, e.object.toPort);
                } else if (e.modelChange === "linkToKey") {
                    // alert(evt.propertyName + " changed To key of link: " + e.object + " from: " + e.oldValue + " to: " + e.newValue);
                    // console.log(e);
                    cambiarConexionToFlecha(e.object.from, e.oldValue, e.newValue, 'to', e.object.fromPort, e.object.toPort);
                }
            } else if (e.change === go.ChangedEvent.Insert && e.modelChange === "linkDataArray") {
                // alert(e.newValue);
                // alert(e.newValue.from + " added link: " + e.newValue.to);
                // Creo una nueva flecha
                guardarFlujoBot('creacionFlecha');                
            
            } else if (e.change === go.ChangedEvent.Remove && e.modelChange === "linkDataArray") {
                // alert(evt.propertyName + " removed link: " + e.oldValue.from+ " removed link: " + e.oldValue.to);
                if(e.oldValue.generadoPorSistema != 1){
                    removerFlecha(e.oldValue.from, e.oldValue.to);
                }else{

                    let nodeDataArray = e.model.nodeDataArray;
                    let pasoDesde = nodeDataArray.find(element => element.key == e.oldValue.from);

                    alertify.warning("Solo se puede eliminar la flecha desde la seccion "+ pasoDesde.nombrePaso);
                    load();
                }
            }
        });
    });

    myDiagramBot.addDiagramListener("ObjectDoubleClicked", function (e) {
        // console.log(e.subject.part.data);
        // console.log(e.subject.part.actualBounds.toString());
        if (e.subject.part instanceof go.Link) {
            // alert("doubleClicked");
            let link = e.subject.part;
            guardarFlujoBot('abrirFlecha');
            // mostrarContenidoFlecha(link.data.from , link.data.to, 'show');
            validarConexionesFlecha(link.data.from , link.data.to);            
        }
    });

    function showLinkLabel(e) {
        var label = e.subject.findObject("LABEL");
        if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Circle");
    }

    // helper definitions for node templates
    function nodeStyle() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                selectionObjectName: "BODY",
                // the Node.location is at the center of each node
                locationSpot: go.Spot.Center,
                locationObjectName: "BODY",
                //isShadowed: true,
                //shadowColor: "#888",
                // handle mouse enter/leave events to show/hide the ports
                mouseEnter: function (e, obj) { showPorts(obj.part, true); },
                mouseLeave: function (e, obj) { showPorts(obj.part, false); },
                doubleClick:function(e, obj){
                    if(obj.je){
                        // LlamarModal(obj.je.tipoPaso, obj.je.key, obj.je.category );
                        if(obj.je.tipoSeccion == "25"){
                            abrirPasoExterno('bot', obj.je.nombrePaso, obj.je.key);
                        }else if(obj.je.tipoSeccion == "24"){
                            abrirPasoExterno('campana', obj.je.nombrePaso, obj.je.key);
                        }else{
                            editarSeccion(obj.je.key, obj.je.autoresId);
                        }
                    }else{
                        // LlamarModal(obj.mb.tipoPaso, obj.mb.key, obj.mb.category );
                        editarSeccion(obj.mb.key, obj.mb.autoresId);
                    }
                }
            }
        ];
    }

    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
    // and where the port is positioned on the node, and the boolean "output" and "input" arguments
    // control whether the user can draw links from or to the port.
    function makePort(name, spot, output, input) {
        // the port is basically just a small circle that has a white stroke when it is made visible
        return $(go.Shape, "Rectangle", {
            fill: "transparent",
            stroke: null,  // this is changed to "white" in the showPorts function
            desiredSize: new go.Size(8, 8),
            alignment: spot,
            alignmentFocus: spot,  // align the port on the main Shape
            portId: name,  // declare this object to be a "port"
            fromSpot: spot,
            toSpot: spot,  // declare where links may connect at this port
            fromLinkable: output,
            toLinkable: input,  // declare whether the user may draw links to/from here
            cursor: "pointer" // show a different cursor to indicate potential link point
        });
    }

    // Esta configuracion muestra el nombre del paso
    function nombrePaso(nombre){
        // Este bloque muestra el nombre del paso
        return $(go.TextBlock,
            {
                text: nombre,
                alignment: new go.Spot(0.5, 0.9, 0, 15), alignmentFocus: go.Spot.Center,
                stroke: "black", font: "9pt Segoe UI, sans-serif",
                overflow: go.TextBlock.OverflowEllipsis,
                maxLines: 1,
                
            },
            new go.Binding("text", "nombrePaso")
        );
    }

    function generadoPorSistema(){
        // return new go.Binding("stroke", "active", function(v) { return null });
        return new go.Binding("stroke", "generadoPorSistema", function(v) { return v == '1' ? '#000' : '#009fe3'; });
    }

    myDiagramBot.nodeTemplateMap.add("",  // the default category
        $(go.Node, "Spot", nodeStyle(),
            // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
            $(go.Panel, "Auto",
                $(go.Shape, "Circle",
                    {
                        fill: "#C8DA2B",
                        stroke: null
                    },
                    new go.Binding("figure", "figure")
                ),
                $(go.TextBlock,
                    {
                        font: "18px FontAwesome",
                        stroke: "whitesmoke",
                        margin: 8,
                        maxSize: new go.Size(160, NaN),
                        wrap: go.TextBlock.WrapFit,
                        editable: false
                    },
                    new go.Binding("text").makeTwoWay()
                )
            ),
            // four named ports, one on each side:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, false)
        )
    );

    myDiagramBot.nodeTemplateMap.add("bienvenida",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Terminator",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.green, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosBot.bienvenida,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.bienvenida),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramBot.nodeTemplateMap.add("despedida",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Terminator",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.red, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosBot.despedida,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.despedida),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramBot.nodeTemplateMap.add("conversacional",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.blue, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosBot.conversacional,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.conversacional),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramBot.nodeTemplateMap.add("transaccional",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Circle",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.blue, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: config.iconosBot.transaccional,
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.transaccional),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramBot.nodeTemplateMap.add("bot",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Diamond",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.blue, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: '\uf544',
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.transaccional),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );

    myDiagramBot.nodeTemplateMap.add("campana",
        $(go.Node, "Spot", nodeStyle(),
            $(go.Panel, "Auto",
                {name: "BODY"},
                $(go.Shape, "Diamond",
                    {
                        width: 50, height: 50, 
                        fill: config.colores.orange, 
                        stroke: null,
                    }                    
                ),
                $(go.TextBlock,
                    {
                        margin: 8, 
                        maxSize: new go.Size(160, NaN),
                        stroke: config.colorIcono.white,
                        font: config.font,
                        text: '\uf78c',
                        editable: false
                    },
                )
            ),
            // Esto es para mostrar el nombre del paso
            nombrePaso(config.nombreSeccionesBot.transaccional),
            // three named ports, one on each side except the top, all output only:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true)
        )
    );
    
    // Replace the default Link template in the linkTemplateMap
    myDiagramBot.linkTemplate =
        $(go.Link,  // the whole link panel
        {
            routing: go.Link.AvoidsNodes,
            curve: go.Link.JumpOver,
            corner: 5,
            toShortLength: 4,
            relinkableFrom: true,
            relinkableTo: true,
            reshapable: true,
            resegmentable: true,
            // mouse-overs subtly highlight links:
            mouseEnter: function(e, link) {
                link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)";
            },
            mouseLeave: function(e, link) {
                link.findObject("HIGHLIGHT").stroke = "transparent";
            }
        },
        new go.Binding("points").makeTwoWay(),
        $(go.Shape,  // the highlight shape, normally transparent
            {
                isPanelMain: true,
                strokeWidth: 8,
                stroke: "transparent",
                name: "HIGHLIGHT"
            }
        ),
        $(go.Shape,  // the link path shape
            {
                isPanelMain: true,
                stroke: "#009fe3",
                strokeWidth: 2
            },
            generadoPorSistema()
        ),
        $(go.Shape,  // the arrowhead
            {
                toArrow: "standard",
                stroke: null,
                fill: "gray"
            },
            generadoPorSistema()
        ),
        $(go.Panel, "Auto",  // the link label, normally not visible
            {
                visible: false,
                name: "LABEL",
                segmentIndex: 2,
                segmentFraction: 0.5
            },
            new go.Binding("visible", "visible").makeTwoWay(),
            $(go.Shape, "Rectangle",  // the label shape
            {
                fill: "#F8F8F8",
                stroke: null
            }),
            $(go.TextBlock, "??",  // the label
            {
                textAlign: "center",
                font: "8pt helvetica, arial, sans-serif",
                stroke: "#333333",
                editable: true
            },
            new go.Binding("text").makeTwoWay())
        )
    );

    // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
    myDiagramBot.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
    myDiagramBot.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

    load();  // load an initial diagram from some JSON text

    // initialize the Palette that is on the left side of the page
    myPaletteBot = $(go.Palette, "listaSeccionesAElejir",  // must name or refer to the DIV HTML element
        {
            "animationManager.duration": 800, // slightly longer than default (600ms) animation
            nodeTemplateMap: myDiagramBot.nodeTemplateMap,  // share the templates used by myDiagram
            model: new go.GraphLinksModel([  // specify the contents of the Palette
                
                { category: "conversacional", text: "Seccion conversacional", tipoPaso : 1, figure : "Circle"},
                { category: "transaccional", text: "Seccion transaccional", tipoPaso : 2, figure : "Circle"}

            ])
        }
    );

    // The following code overrides GoJS focus to stop the browser from scrolling
    // the page when either the Diagram or Palette are clicked or dragged onto.
    function customFocus() {
        var x = window.scrollX || window.pageXOffset;
        var y = window.scrollY || window.pageYOffset;
        go.Diagram.prototype.doFocus.call(this);
        window.scrollTo(x, y);
    }

    myDiagramBot.doFocus = customFocus;
    myPaletteBot.doFocus = customFocus;

}
// Make all ports on a node visible when the mouse is over the node
function showPorts(node, show) {
    var diagram = node.diagram;
    if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
    node.ports.each(function(port) {
        port.stroke = (show ? "white" : null);
    });
}
// Show the diagram's model in JSON format that the user may edit
function save() {
    document.getElementById("mySavedModelBot").value = myDiagramBot.model.toJson();
    myDiagramBot.isModified = false;
}
function load() {
    myDiagramBot.model = go.Model.fromJson(document.getElementById("mySavedModelBot").value);
}

// Funciones para manejar los errores

function campoError(input, mensajeError = null){
    $("#"+input).addClass("error-input");
    $("#"+input).focus();

    let mensaje = "Este campo es obligatorio";

    if(mensajeError){
        mensaje = mensajeError;
    }
    alertify.error(mensaje);
}

function quitarCampoError(){
    $(".error-input").removeClass("error-input");
}

// Funciones del paso

function guardarFlujoBot(iniciador){

    save();
    
    let valido = true;

    quitarCampoError();

    if($("#nombre_bot").val() == ''){
        campoError("nombre_bot");
        valido = false;
    }

    if($("#textoNoRespuesta").val() == ''){
        campoError("textoNoRespuesta");
        valido = false;
    }        

    if($("#timeoutBot").val() == ""){
        campoError("timeoutBot");
        valido = false;
    }
    
    if(valido){

        tinymce.triggerSave();

        // Formateamos el texto enriquecido
        formatearTextoEnriquecido('#timeoutBotFrase');
        formatearTextoEnriquecido('#textoNoRespuesta');

        //Se crean un array con los datos a enviar, apartir del formulario 
        var formData = new FormData($("#FormularioDatos")[0]);
        
        $.ajax({
            url: '<?=$url_crud;?>?guardar=si&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>&iniciador='+iniciador,  
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            //una vez finalizado correctamente
            success: function(data){

                if(iniciador == 'botonSave'){
                    alertify.success("Información guardada con &eacute;xito");
                    location.reload();
                }else if(iniciador == 'creacionFlecha'){
                    alertify.success("Flecha creada");
                }
                let paso = <?=$_GET['id_paso']?>;
                recargarFlujograma(paso);
                
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al guardar la información" + data.responseText);
                }else{
                    alertify.error("Hubo un error al guardar la información");
                }
            }
        })
    }

}


function cargarDatosDelPaso(){
    $.ajax({
        url: '<?=$url_crud;?>?getDatosPaso=true&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>',  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){

            // Traigo los datos del paso
            if(data.paso){

                $("#pasoId").val(data.paso.id);

                if(data.paso.nombre != ''){
                    $("#nombre_bot").val(data.paso.nombre);
                }else{
                    $("#nombre_bot").val('BOT_'+data.paso.id);
                }
                
                if(data.paso.activo != '-1'){
                    $('#pasoActivo').prop('checked',false);
                }
            }

            let estructuraSeccionesBot = '';
            let estructuraSeccionesBotFlechas = '';

            // Destruimos la instancia tinymce
            tinymce.remove("textarea#timeoutBotFrase");
            tinymce.remove("textarea#textoNoRespuesta");

            // Aqui traigo la lista de secciones
            if(data.secciones.length > 0){
                data.secciones.forEach( (item, index )=> {
                    
                    $("#textoNoRespuesta").val(item.frase_no_encuentra_respuesta);
                    $("#timeoutBot").val(item.timeout_cliente);
                    $("#timeoutBotFrase").val(item.frase_timeout);

                    if(index != 0){
                        estructuraSeccionesBot += ',';
                    }

                    let categoria = 'conversacional';
                    switch (item.tipo_seccion) {
                        case "1":
                            categoria = 'conversacional';
                            break;
                        case "2":
                            categoria = 'transaccional';
                            break;
                        case "3":
                            categoria = 'bienvenida';
                            break;
                        case "4":
                            categoria = 'despedida';
                            break;
                        default:
                            break;
                    }

                    let newLoc = '';

                    if(item.loc){
                        newLoc = item.loc.replace(/"/g, "");
                    }else{
                        newLoc = item.loc;
                    }
                    estructuraSeccionesBot += `{"category": "${categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.autorespuestaId}, "loc":"${newLoc}", "tipoSeccion": "${item.tipo_seccion}"}`;
                });
            }

            if(data.pasosExternos.length > 0){
                data.pasosExternos.forEach( (item, index )=> {

                    estructuraSeccionesBot += ',';

                    let categoria = 'conversacional';
                    switch (item.tipo_seccion) {
                        case "1":
                            categoria = 'conversacional';
                            break;
                        case "2":
                            categoria = 'transaccional';
                            break;
                        case "3":
                            categoria = 'bienvenida';
                            break;
                        case "4":
                            categoria = 'despedida';
                            break;
                        case "24":
                            categoria = 'campana';
                            break;
                        case "25":
                            categoria = 'bot';
                            break;
                        default:
                            break;
                    }

                    let newLoc = '';

                    if(item.loc){
                        newLoc = item.loc.replace(/"/g, "");
                    }else{
                        newLoc = item.loc;
                    }
                    estructuraSeccionesBot += `{"category": "${categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.id_paso_externo}, "loc":"${newLoc}", "tipoSeccion": "${item.tipo_seccion}"}`;
                });
            }

            if(data.conexiones.length > 0){
                data.conexiones.forEach((item, index) => {
                    
                    if(index != 0){
                        estructuraSeccionesBotFlechas += ',';
                    }

                    let newCoordenada = '';

                    if(item.coordenadas){
                        newCoordenada = item.coordenadas.replace(/"/g, "");
                    }else{
                        newCoordenada = item.coordenadas;
                    }
                    estructuraSeccionesBotFlechas += `{"from": ${item.desde}, "to": ${item.hasta}, "fromPort": "${item.from_port}", "toPort": "${item.to_port}", "visible": true, "points": "${newCoordenada}", "text": "${item.nombre}", "generadoPorSistema": "${item.generado_por_sistema}"}`;

                });
            }

            // Creamos la estructura del bot
            let estructura = `
                {
                    "class": "go.GraphLinksModel",
                    "linkFromPortIdProperty": "fromPort",
                    "linkToPortIdProperty": "toPort",
                    "nodeDataArray": [
                        ${estructuraSeccionesBot}
                    ],
                    "linkDataArray": [
                        ${estructuraSeccionesBotFlechas}
                    ]
                }
            `;

            $("#mySavedModelBot").val(estructura);

            // Creamos una instancia para la frase no encuentra respuesta y la frase de timeout
            tinymce.init({
                selector: 'textarea#timeoutBotFrase, textarea#textoNoRespuesta',
                height : 140,
                encoding: 'UTF-8',
                entity_encoding: 'raw',
                skin: 'small',
                menubar: false,
                plugins: 'emoticons paste code',
                toolbar: 'bold italic strikethrough | emoticons | code',
                language: 'es',
                branding: false,
                paste_as_text: true,
                content_style: '.mce-content-body p { padding: 0; margin: 2px 0;}'
            });

            // Agregamos los pasos externos ejecutables
            if(data.pasosEjecutables && data.pasosEjecutables.length > 0){
                
                let opciones = '';

                data.pasosEjecutables.forEach(element => {
                    opciones += `<option value="${element.id}">${element.nombre}</option>`;
                });

                $("#actionAfterTimeout").html(opciones);

                // Validamos si hay pasos guardamos para agregarlos
                if(data.pasosTimeout && data.pasosTimeout.length > 0){
                    $("#actionAfterTimeout").val(data.pasosTimeout).trigger('change');
                }
            }

            // Agrego las variables para el noRespuesta

            // Traigo la lista de bots de la estrategia
            opcionesBots = '<option value="0">Seleccione</option>';
            if(data.listaBots){
                $.each(data.listaBots, function(i, item){
                    opcionesBots += `<option value="${item.id}">${item.nombre}</option>`;
                });
            }
            $("#bot_NoRes").html(opcionesBots);

            // Traigo la lista de campanas de la estrategia
            opcionesCampana = '<option value="0">Seleccione</option>';
            if(data.listaCampanas){
                $.each(data.listaCampanas, function(i, item){
                    opcionesCampana += `<option value="${item.dy_campan}">${item.nombre}</option>`;
                });
            }
            $("#campan_NoRes").html(opcionesCampana);

            // Traigo la lista de secciones del bot
            opcionesSecciones = '<option value="0">Seleccione</option>';
            if(data.listaSecciones){
                $.each(data.listaSecciones, function(i, item){
                    opcionesSecciones += `<option value="${item.base_autorespuesta_id}">${item.nombre}</option>`;
                });
            }
            $("#bot_seccion_NoRes").html(opcionesSecciones);

            // Valido si hay configuración de la accion del NoRespuesta 
            if(data.configuracionAccionNoRespuesta){

                $("#accion_NoRes").val(data.configuracionAccionNoRespuesta.accion).trigger('change');

                // Cuando la accion es pasar a campaña
                if(data.configuracionAccionNoRespuesta.accion == '1'){
                    $("#campan_NoRes").val(data.configuracionAccionNoRespuesta.id_campana);
                }

                // Cuando la accion es transferir a otra seccion
                if(data.configuracionAccionNoRespuesta.accion == '2'){

                    // Pasar a otro bot
                    if(data.configuracionAccionNoRespuesta.id_bot_transferido && data.configuracionAccionNoRespuesta.id_bot_transferido != 0){
                        $("#accion_NoRes").val("2_2").change();
                        $("#bot_NoRes").val(data.configuracionAccionNoRespuesta.id_bot_transferido).trigger('change');
                        $("#bot_NoRes").attr('data-basetransferencia', data.configuracionAccionNoRespuesta.id_base_transferencia);
    
                    }else{
                        $("#accion_NoRes").val("2_1").change();
                        $("#bot_seccion_NoRes").val(data.configuracionAccionNoRespuesta.id_base_transferencia).trigger('change');
                    }
                }
            }else{
                $("#accion_NoRes").val(0).trigger('change');
            }

            init();
        },
        complete : function(){
            $.unblockUI();
        }
    });

    // Destruyo todas las instancias del editor de texto enriquecido de accion_inicial cuando abrimos el bot
    $("#grupTextAreaAccionInicial div.tox.tox-tinymce").remove();
    $("#rpta_accion_inicial").show();
}

function crearSeccion(){

    save();

    let formData = new FormData($("#FormularioDatos")[0]);
    let pasoId = <?=$_GET['id_paso']?>;

    $.ajax({
        url: '<?=$url_crud;?>?crearNuevaSeccion=si&id_paso='+pasoId+'&huesped=<?=$_SESSION['HUESPED']?>',  
        type: 'POST',
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            console.log(data);
            if(data.res1 && data.res2){
                recargarFlujograma(pasoId);
                // editarSeccion(data.seccionId, data.baseAutorespuestaId);
                alertify.success("Seccion creada");
            }else{
                alertify.error("Hubo un error al guardar la información de la seccion");
            }
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

// Recarga el flujograma con los nuevos cambios desde la bd
function recargarFlujograma(pasoId){
    $.ajax({
        url: '<?=$url_crud;?>?recargarFlujograma=true&id_paso='+pasoId+'&huesped=<?=$_SESSION['HUESPED']?>',  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){

            let estructuraSeccionesBot = '';
            let estructuraSeccionesBotFlechas = '';

            // Aqui traigo la lista de secciones
            if(data.secciones.length > 0){
                data.secciones.forEach( (item, index )=> {

                    if(index != 0){
                        estructuraSeccionesBot += ',';
                    }

                    let categoria = 'conversacional';
                    switch (item.tipo_seccion) {
                        case "1":
                            categoria = 'conversacional';
                            break;
                        case "2":
                            categoria = 'transaccional';
                            break;
                        case "3":
                            categoria = 'bienvenida';
                            break;
                        case "4":
                            categoria = 'despedida';
                            break;
                        default:
                            break;
                    }

                    let newLoc = '';

                    if(item.loc){
                        newLoc = item.loc.replace(/"/g, "");
                    }else{
                        newLoc = item.loc;
                    }
                    estructuraSeccionesBot += `{"category": "${categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.autorespuestaId}, "loc":"${newLoc}", "tipoSeccion": "${item.tipo_seccion}"}`;
                });
            }

            if(data.pasosExternos.length > 0){
                data.pasosExternos.forEach( (item, index )=> {

                    estructuraSeccionesBot += ',';

                    let categoria = 'conversacional';
                    switch (item.tipo_seccion) {
                        case "1":
                            categoria = 'conversacional';
                            break;
                        case "2":
                            categoria = 'transaccional';
                            break;
                        case "3":
                            categoria = 'bienvenida';
                            break;
                        case "4":
                            categoria = 'despedida';
                            break;
                        case "24":
                            categoria = 'campana';
                            break;
                        case "25":
                            categoria = 'bot';
                            break;
                        default:
                            break;
                    }

                    let newLoc = '';

                    if(item.loc){
                        newLoc = item.loc.replace(/"/g, "");
                    }else{
                        newLoc = item.loc;
                    }
                    estructuraSeccionesBot += `{"category": "${categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.id_paso_externo}, "loc":"${newLoc}", "tipoSeccion": "${item.tipo_seccion}"}`;
                });
            }

            if(data.conexiones.length > 0){
                data.conexiones.forEach((item, index) => {
                    
                    if(index != 0){
                        estructuraSeccionesBotFlechas += ',';
                    }

                    let newCoordenada = '';

                    if(item.coordenadas){
                        newCoordenada = item.coordenadas.replace(/"/g, "");
                    }else{
                        newCoordenada = item.coordenadas;
                    }
                    estructuraSeccionesBotFlechas += `{"from": ${item.desde}, "to": ${item.hasta}, "fromPort": "${item.from_port}", "toPort": "${item.to_port}", "visible": true, "points": "${newCoordenada}", "text": "${item.nombre}", "generadoPorSistema": "${item.generado_por_sistema}"}`;

                });
            }

            // Creamos la estructura del bot
            let estructura = `
                {
                    "class": "go.GraphLinksModel",
                    "linkFromPortIdProperty": "fromPort",
                    "linkToPortIdProperty": "toPort",
                    "nodeDataArray": [
                        ${estructuraSeccionesBot}
                    ],
                    "linkDataArray": [
                        ${estructuraSeccionesBotFlechas}
                    ]
                }
            `;
            console.log(estructura, estructuraSeccionesBot);
            $("#mySavedModelBot").val(estructura);

            load();
        },
        complete : function(){
            $.unblockUI();
        }
    });
}

// Funciones de la seccion

// Guarda las seccion actual
function saveSeccion(){
    let valido = true;

    if($("#seccionNombre").val() == ''){
        alertify.error('El campo nombre de la seccion no puede estar vacio');
        $("#seccionNombre").focus();
        valido = false;
    }

    if($("#tipoSeccion").val() == 1){
        $('.esteTag').each(function(){
            let esteId = $(this).attr('id');
            if($("#"+esteId).val() == ''){
                alertify.error('El campo tag no puede estar vacio');
                valido=false;
            }
        });

        // Valido las acciones
        $('.accion').each(function(){
            let num = $(this).attr('num');
            
            if($(this).val() == '1'){    
                // Verifico que el campo correspondiente este lleno
                if($('#campan_'+num).val() == ''){
                    alertify.error('El campo detalle accion del disparador '+ $("#tag_"+num).val() +' no puede quedar vacio');
                    valido = false;
                    $('#campan_'+num).focus();
                }
            }

            if($(this).val() == '2_2'){    
                // Verifico que el campo correspondiente este lleno
                if($('#bot_'+num).val() == ''){
                    alertify.error('El campo detalle accion del disparador '+ $("#tag_"+num).val() +' no puede quedar vacio');
                    valido = false;
                    $('#bot_'+num).focus();
                }
            }
        });

    }

    if($("#tipoSeccion").val() == 2){
        $('.pgta').each(function(){
            if($(this).val() == ''){
                alertify.error('El campo pregunta no puede estar vacio');
                valido=false;
            }
        });  

        // Valido las acciones finales
        $('.accion-final').each(function(){
            let num = $(this).attr('num');
            
            if($(this).val() == '1'){    
                // Verifico que el campo correspondiente este lleno
                if($('#campan_accion_final_'+num).val() == ''){
                    alertify.error('El campo detalle accion de '+ $("#tituloAccionFinal"+num).text() +' no puede quedar vacio');
                    valido = false;
                    $('#campan_accion_final_'+num).focus();
                }
            }

            if($(this).val() == '2_1'){    
                // Verifico que el campo correspondiente este lleno
                if($('#bot_seccion_accion_final_'+num).val() == ''){
                    alertify.error('El campo detalle accion de '+ $("#tituloAccionFinal"+num).text() +' no puede quedar vacio');
                    valido = false;
                    $('#bot_seccion_accion_final_'+num).focus();
                }
            }

            if($(this).val() == '2_2'){    
                // Verifico que el campo correspondiente este lleno
                if($('#bot_accion_final_'+num).val() == ''){
                    alertify.error('El campo detalle accion de '+ $("#tituloAccionFinal"+num).text() +' no puede quedar vacio');
                    valido = false;
                    $('#bot_accion_final_'+num).focus();
                }
            }
        });

        // if($("#pregunta_accion_final").val() == ''){
        //     alertify.error('El campo pregunta de la accion final no puede estar vacio');
        //     valido=false;
        // }

        // if(tinymce.get("rpta_accion_final").getContent() == ''){
        //     alertify.error('El campo respuesta de la accion final no puede estar vacio');
        //     valido=false;
        // }
    }

    // Desabilitado la validacion del campo respuesta
    // if($("#tipoSeccion").val() == 1){
    //     $('.respuesta').each(function(){
    //         if(tinymce.get($(this).attr('id')).getContent() == ''){
    //             alertify.error('El campo respuesta no puede estar vacio');
    //             valido=false;
    //         }
    //     });
    // }

    if(valido){

        tinymce.triggerSave();

        let myOrden = [];
        let pasoId = <?=$_GET['id_paso']?>;

        if($("#tipoSeccion").val() == 1){

            formatearTextoEnriquecido('#rpta_accion_inicial');

            $("#accionesBotConversacional .myTextarea.respuesta").each(function(){
                let identificador = $(this).attr('id');
                formatearTextoEnriquecido('#'+identificador);
            });
        }else{
            $("#accionesBotTransaccional .myTextarea.respuesta").each(function(){
                let identificador = $(this).attr('id');
                formatearTextoEnriquecido('#'+identificador);
            });

            $("#accionesBotTransaccional .myTextarea.preg").each(function(){
                let identificador = $(this).attr('id');
                formatearTextoEnriquecido('#'+identificador);
            });
        }

        myOrden = $("#accionesBotTransaccional").sortable('toArray');

        var formData = new FormData($("#formularioSeccion")[0]);
        formData.append('ordenSecciones', myOrden.toString());
        formData.append('tipoSeccion1', $("#tipoSeccion").val());

        $.ajax({
            url: '<?=$url_crud;?>?guardar_seccion=si&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>',  
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            //una vez finalizado correctamente
            success: function(data){
                $("#formularioSeccion")[0].reset();
                alertify.success("Información guardada con &eacute;xito");
                $("#modalConfiguracionSeccion").modal("hide");
                recargarFlujograma(pasoId);
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al guardar la información" + data.responseText);
                }else{
                    alertify.error("Hubo un error al guardar la información");
                }
            }
        })
    }
}

function editarSeccion(seccionId, autorespuestaId){

    let pasoId = <?=$_GET['id_paso']?>;
    
    $("#formularioSeccion")[0].reset();
    
    $("#accionesBotConversacional").html('');
    $("#accionesBotTransaccional").html('');

    $("#disparadoresTable tbody").html('');
    $("#disparadoresTransaccionales").html('');
    $("#otrosDisparadores").html('');

    $("#accionesFinales").html('');
    $("#accionFinalDefecto").html('');

    $("#modalConfiguracionSeccion").modal();

    $("#tipoSeccion").change();

    $("#totalFilasSeccion").val(0);
    $("#totalAccionesFinales").val(0);

    // Oculto las diferentes secciones
    $("#row_accion_inicial").hide();
    $("#row_accion_final").hide();

    // Oculto los botones para agregar campos
    $("#agregarFilaCaptura").hide();
    $("#agregarFilaConsulta").hide();
    $("#agregarFilaConsultaDyalogo").hide();
    $("#agregarFila").hide();

    obtenerTagsComunes();

    // Obtener variables
    obtenerVariables(pasoId);

    // Limpio los botones de la accion inicial
    $("#listaBotonesa_inicial tbody").html('');

    // traigo los datos de la seccion
    $.ajax({
        url: '<?=$url_crud;?>?getdatosSeccion=si&id_paso='+pasoId+'&huesped=<?=$_SESSION['HUESPED']?>&seccionId='+seccionId+'&autorespuestaId='+autorespuestaId,  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){
            if(data){
                
                if(data.seccion){
                    $("#seccionNombre").val(data.seccion.nombre);
                    
                    if(data.seccion.tipo_seccion == 1 || data.seccion.tipo_seccion == 3 || data.seccion.tipo_seccion == 4){
                        $("#tipoSeccion").val(1);
                    }else{
                        $("#tipoSeccion").val(2);
                    }

                    $("#seccionBotId").val(seccionId);
                    $("#autorespuestaId").val(autorespuestaId);

                    cambiarLabelInformativo(data.seccion.tipo_seccion);   

                    if(data.seccion.tipo_seccion == 3){
                        $("#acciones_accion_inicial").show();
                    }else{
                        $("#acciones_accion_inicial").hide();
                    }
                }
                
                inicializarTinymce();
                
                inicializarSelectizeTags('local');
                $("#tag_local")[0].selectize.destroy();
                $("#tag_local").val('');

                // lleno el campo destino de almacenamiento del dato
                if(data.listaBd.length > 0){
                    let listaBd = '';

                    $.each(data.listaBd, function(i, item){
                        listaBd += `<option value="${item.id}">${item.nombre}</option>`;
                    });

                    $("#seccionBd").html(listaBd);

                    $("#seccionBd").val(data.baseAutorespuesta.id_base);
                    $('#seccionBd').select2();
                }

                // Ahora lleno los campos de la bd
                if(data.camposBdActual){
                    let listaCampos = '';
                    $.each(data.camposBdActual, function(i, item){
                        listaCampos += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    camposBd = data.campos;
                    opcionesCamposBd = listaCampos;

                    $("#llavePrincipal").html('<option value="">Seleccione</option>'+opcionesCamposBd);
                }

                

                // Ahora lleno los campos de la gestion
                if(data.camposGestion){
                    let listaCampos = '';
                    $.each(data.camposGestion, function(i, item){
                        listaCampos += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    opcionesCamposGestion = listaCampos;
                }

                // Ahora lleno los pasos a ejecutar
                if(data.pasoEjecutables){
                    let listaPasos = '';
                    $.each(data.pasoEjecutables, function(i, item){
                        listaPasos += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    opcionesPasosEjecutables = listaPasos;
                }

                // Valido si la base de la seccion es la misma que la base de la estrategia
                if(data.baseAutorespuesta.id_base == data.baseAutorespuesta.baseOrigen){
                    $("#llavePrincipal").prop('disabled', true);
                }else{
                    $("#llavePrincipal").prop('disabled', false);
                    $("#llavePrincipal").val(data.baseAutorespuesta.id_base_llave_principal);
                }

                baseOrigen = data.baseAutorespuesta.baseOrigen;

                // Lleno listaG
                if(data.listaG && data.listaG.length > 0){
                    let listaCamposG = '';

                    $.each(data.listaG, function(i, item){
                        listaCamposG += `<option value="${item.id}">${item.nombre}</option>`;
                    });

                    opcionesCamposG = listaCamposG;
                }

                switch (data.seccion.tipo_seccion) {
                    case "1":
                    case "3":
                    case "4":
                        
                        $("#agregarFila").show();

                        // Inicialize local tags
                        inicializarSelectizeTags('local');
                        tinymce.get("rpta_accion_inicial").setContent('');

                        // muestro la accion inicial
                        $("#row_accion_inicial").show();

                        if(data.campos.length > 0){
                            data.campos.forEach( (item, i) => {

                                // Traigo la accion inicial
                                if(item.orden == 1){

                                    let myTag = item.pregunta;

                                    if(data.seccion.tipo_seccion == 3){
                                        myTag = myTag.replace('DY_SALUDO,', '');
                                        myTag = myTag.replace('DY_SALUDO', '');
                                    }

                                    if(data.seccion.tipo_seccion == 4){
                                        myTag = myTag.replace('DY_EXIT,', '');
                                        myTag = myTag.replace('DY_EXIT', '');
                                    }
                                    
                                    $("#tag_local")[0].selectize.destroy();
                                    $("#tag_local").val(myTag);
                                    inicializarSelectizeTags('local');

                                    $("#rpta_accion_inicial").val(item.respuesta); // Lo inicializo por aca
                                    tinymce.get("rpta_accion_inicial").setContent(item.respuesta);

                                    $("#accion_inicial_accion").val(item.accion).change();

                                    if(item.accion == 1){
                                        $("#accion_inicial_seccion").html(opcionesCampana);
                                        $("#accion_inicial_seccion").val(item.id_campana);
                                    }
                                    if(item.accion == 2){
                                        $("#accion_inicial_seccion").html(opcionesSecciones);
                                        $("#accion_inicial_seccion").val(item.id_base_transferencia);
                                    }

                                    // tipo de respuesta
                                    if(item.tipo_media == 'TEXT' || item.tipo_media == '' || item.tipo_media === null){
                                        $("#tipoTexto_accion_inicial").click();
                                        $("#seccionMediaActualaccion_inicial").hide();
                                        $("#mediaActualaccion_inicial").html('');
                                    }else{

                                        if(item.tipo_media == 'IMAGE'){
                                            $("#tipoImagen_accion_inicial").click();
                                        }else if(item.tipo_media == 'VIDEO'){
                                            $("#tipoVideo_accion_inicial").click();
                                        }else if(item.tipo_media == 'AUDIO'){
                                            $("#tipoAudio_accion_inicial").click();
                                        }else if(item.tipo_media == 'DOCUMENT'){
                                            $("#tipoDocumento_accion_inicial").click();
                                        }

                                        $("#mediaActualaccion_inicial").html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                        $("#seccionMediaActualaccion_inicial").show();
                                    }

                                    // Cargo los botones
                                    mostrarBotones(item.body_mensaje_interactivo, 'a_inicial');
                                }else{
                                    // El resto de las acciones
                                    agregarFilaBot('edit');
                                    
                                    $("#tag_"+i)[0].selectize.destroy();
                                    $("#tag_"+i).val(item.pregunta);
                                    inicializarSelectizeTags(i);
    
                                    $("#campo_"+i).val(item.id);
    
                                    let tituloInsertar = obtenerIconoTitulo()+' '+$('#tag_'+i).val();
    
                                    $('#tituloBot_'+i).html(tituloInsertar.substr(0, 150));
                                    
                                    $("#rpta_"+i).val(item.respuesta); // Lo inicializo por aca
                                    tinymce.get("rpta_"+i).setContent(item.respuesta);

                                    // tipo de respuesta
                                    if(item.tipo_media == 'IMAGE'){
                                        $("#tipoImagen_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else if(item.tipo_media == 'VIDEO'){
                                        $("#tipoVideo_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else if(item.tipo_media == 'AUDIO'){
                                        $("#tipoAudio_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else if(item.tipo_media == 'DOCUMENT'){
                                        $("#tipoDocumento_"+i).click();
                                        $("#seccionMediaActual"+i).show();
                                        $("#mediaActual"+i).html('<a href="'+item.media+'" target="black">'+item.nombre_media+'</a>');
                                    }else{
                                        $("#tipoTexto_"+i).click();
                                    }

                                    // Cargo los botones
                                    mostrarBotones(item.body_mensaje_interactivo, i);

    
                                    if(item.accion == 2){

                                        // Si esta condicion se cumple significa que es pasar a otro bot
                                        if(item.id_bot_transferido && item.id_bot_transferido != 0){
                                            $("#accion_"+i).val("2_2").change();
                                            $("#bot_"+i).val(item.id_bot_transferido).trigger('change');
                                            $("#bot_"+i).attr('data-basetransferencia', item.id_base_transferencia);

                                        }else{
                                            $("#accion_"+i).val("2_1").change();
                                            $("#bot_seccion_"+i).val(item.id_base_transferencia).trigger('change');
                                        }

                                    }else{
                                        $("#accion_"+i).val(item.accion).change();
                                    }
                                    
                                    $("#campan_"+i).val(item.id_campana).trigger('change');
                                    $("#pregun_"+i).val(item.id_pregun).trigger('change');

                                    if(item.id_pregun && item.id_pregun_gestion){
                                        $("#guardar_respuesta_"+i).val(3).change();
                                        $("#pregunConver_"+i).val(item.id_pregun).change();
                                    }else if(item.id_pregun){
                                        $("#guardar_respuesta_"+i).val(1).change();
                                        $("#pregunConver_"+i).val(item.id_pregun).change();
                                    }else if(item.id_pregun_gestion){
                                        $("#guardar_respuesta_"+i).val(2).change();
                                        if(item.pregun_gestion_propio == 1){
                                            $("#usarCampoGestionPropio"+i).click();
                                            $("#nombre_variable_"+i).val(item.nombre_variable);
                                        }else{
                                            $("#usarCampoGestionExistente"+i).click();
                                            $("#pregunGestionExistente_"+i).val(item.id_pregun_gestion);
                                        }
                                    }else{
                                        $("#guardar_respuesta_"+i).val(0).change();
                                    }

                                }

                            });
                        }
                        
                        break;

                    case "2":

                        $("#row_accion_final").show();
                        $("#agregarFilaCaptura").show();
                        $("#agregarFilaConsulta").show();
                        $("#agregarFilaConsultaDyalogo").show();
                        
                        // Aqui es donde lleno todas las acciones del bot
                        if(data.campos.length > 0){

                            data.campos.forEach( (item, i) => {

                                let tipoAccion = 'captura';

                                if(item.pregunta == 'DY_WS_CONSUMO'){
                                    tipoAccion = 'consulta';
                                }else if(item.pregunta == 'DY_CONSULTA_BD'){
                                    tipoAccion = 'consultaDy';
                                }

                                if(tipoAccion == 'captura'){
                                    agregarFilaBot("edit", "2");

                                    $("#pregunta_bot_"+i).val(item.pregunta);
                                    if(tinymce.get("pregunta_bot_"+i) !== null){
                                        tinymce.get("pregunta_bot_"+i).setContent(item.pregunta);
                                    }

                                    let textoPregunta = $('#pregunta_bot_'+i).val();
                                    textoPregunta = textoPregunta.replace(/(<([^>]+)>)/ig, '');
                                    let tituloInsertar = obtenerIconoTitulo()+' '+textoPregunta;
                                    $('#tituloBot_'+i).html(tituloInsertar.substr(0, 150));

                                    $("#campo_"+i).val(item.id);

                                    $("#rpta_"+i).val(item.respuesta); // Lo inicializo por aca
                                    if(tinymce.get("rpta_"+i) !== null){
                                        tinymce.get("rpta_"+i).setContent(item.respuesta);
                                    }

                                    $("#accion_"+i).val(item.accion).change();
                                    
                                    if(item.accion == 3){
                                        $("#pregun_"+i).val(item.id_pregun).trigger('change');
                                    }else if(item.accion == 4){
                                        $("#nombre_variable_"+i).val(item.nombre_variable);
                                    }else if(item.accion == 5){
                                        $("#pregun_"+i).val(item.id_pregun).trigger('change');
                                    }else if(item.accion == 6){
                                        if(item.pregun_gestion_propio == 1){
                                            $("#usarCampoGestionPropio"+i).click();
                                            $("#nombre_variable_"+i).val(item.nombre_variable);
                                        }else{
                                            $("#usarCampoGestionExistente"+i).click();
                                            $("#nombre_variable_"+i).val(item.nombre_variable);
                                            $("#pregunGestionExistente_"+i).val(item.id_pregun_gestion);
                                        }
                                    }
                                }

                                if(tipoAccion == 'consulta'){
                                    agregarFilaBot("edit", "3");

                                    let ws = data.wsBotSecciones.find(element => element.id_base_autorespuestas == item.id_base_autorespuestas);

                                    $("#campo_"+i).val(ws.id);
                                    $("#webservice_"+i).val(ws.id_ws_general);
                                    $("#webservice_"+i).data("oldvalue", ws.id_ws_general);

                                    let texto = $("#webservice_"+i+" option:selected").text();
                                    $("#tituloService_"+i).html(obtenerIconoTitulo()+'&nbsp;'+texto);

                                    // Ahora traigo los parametros de cada webservice

                                    let htmlParamsBody = '';

                                    // Armo la estructura 
                                    $.each(data.wsParametrosSeccion[ws.id], function(j, itemP){
                                        htmlParamsBody += `
                                        <tr>
                                            <input type="hidden" name="parametro_${i}_${itemP.id_parametro}" id="parametro_${i}_${itemP.id_parametro}">
                                            <td>${itemP.parametro}</td>
                                            <td>
                                                <select name="tipoParametro_${i}_${itemP.id_parametro}" id="tipoParametro_${i}_${itemP.id_parametro}" class="form-control input-sm" onchange="cambioTipoValor(${itemP.id_parametro}, ${i})">
                                                    <option value="1">Estatico</option>
                                                    <option value="2">Dinamico</option>
                                                    <option value="3">Combinado</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="valorEstatico_${i}_${itemP.id_parametro}" id="valorEstatico_${i}_${itemP.id_parametro}" class="form-control input-sm">

                                                <select name="valorDinamico_${i}_${itemP.id_parametro}" id="valorDinamico_${i}_${itemP.id_parametro}" class="form-control input-sm" style="display:none">
                                                    ${variablesDinamicas}
                                                </select> 
                                            </td>
                                        </tr>
                                        `;
                                    });

                                    // Armo la estructura de los parametros
                                    let htmlParamsEstructura = `
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre del parametro</th>
                                                    <th>Tipo de valor</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${htmlParamsBody}
                                            </tbody>
                                        </table>
                                    `;

                                    $('#webserviceParametros_'+i).html(htmlParamsEstructura);

                                    // Lleno los datos
                                    $.each(data.wsParametrosSeccion[ws.id], function(k, itemK){
                                        $("#parametro_"+i+"_"+itemK.id_parametro).val(itemK.id);
                                        $("#tipoParametro_"+i+"_"+itemK.id_parametro).val(itemK.tipo_valor);

                                        if(itemK.tipo_valor == 1){
                                            $("#valorEstatico_"+i+"_"+itemK.id_parametro).val(itemK.valor);
                                        }else{
                                            $("#valorDinamico_"+i+"_"+itemK.id_parametro).val(itemK.valor);
                                        }

                                        $("#tipoParametro_"+i+"_"+itemK.id_parametro).change();
                                    });
                                }

                                if(tipoAccion == 'consultaDy'){
                                    agregarFilaBot("add", "5");
                                    let bd = item.consulta_dy_tabla.replace('G', '');
                                    $("#bdConsultaDy_"+i).val(bd).change();
                                    $("#bdConsultaDy_"+i).attr('data-variables', item.consulta_dy_campos);
                                    $("#bdConsultaDy_"+i).attr('data-condiciones', item.consulta_dy_condicion);
                                }

                            });

                        }

                        // Acciones Finales
                        if(data.camposAccionesFinales.length > 0){

                            tamano = data.camposAccionesFinales.length;

                            data.camposAccionesFinales.forEach( (item, i) => {

                                let contador = $("#totalAccionesFinales").val();

                                $("#autorespuestaAccionFinal").val(item.id_base_autorespuestas);

                                if(i == tamano - 1){
                                    agregarAccionFinal(true, item.condicion, 'edit');
                                }else{
                                    agregarAccionFinal(false, item.condicion, 'edit');
                                }

                                $("#campo_accion_final_"+contador).val(item.id);

                                $("#rpta_accion_final_"+contador).val(item.respuesta);
                                if(tinymce.get("rpta_accion_final_"+contador) !== null){
                                    tinymce.get("rpta_accion_final_"+contador).setContent(item.respuesta);
                                }

                                if(item.accion == 2){

                                    // Si esta condicion se cumple significa que es pasar a otro bot
                                    if(item.id_bot_transferido && item.id_bot_transferido != 0){
                                        $("#accion_accion_final_"+contador).val("2_2").change();
                                        $("#bot_accion_final_"+contador).val(item.id_bot_transferido).trigger('change');
                                        $("#bot_accion_final_"+contador).attr('data-basetransferencia', item.id_base_transferencia);

                                    }else{
                                        $("#accion_accion_final_"+contador).val("2_1").change();
                                        $("#bot_seccion_accion_final_"+contador).val(item.id_base_transferencia).trigger('change');
                                    }
                                }

                                if(item.accion == 1){
                                    $("#accion_accion_final_"+contador).val(item.accion).change();
                                    $("#campan_accion_final_"+contador).val(item.id_campana).trigger('change');
                                }

                                if(item.accion == 7){
                                    $("#accion_accion_final_"+contador).val(item.accion).change();
                                    $("#pasos_externos_accion_final_"+contador).val(item.id_estpas).trigger('change');
                                }
                            });
                        }
                        
                        if(data.camposAccionesFinales.length > 1){
                            let id = data.camposAccionesFinales.length - 1;
                            let nombreAccionFinal = `<i class="fa fa-comments-o"></i>&nbsp;Acción cuando ninguna de las condiciones de las acciones anteriores se cumplió`;
                            $("#tituloAccionFinal"+id).html(nombreAccionFinal);
                        }

                        break;
                
                    default:
                        break;
                }
            }
        }
    });
}

function agregarFilaBot(oper, tipo = null){

    let noCampo = $("#totalFilasSeccion").val();

    let tipoSeccion = $("#tipoSeccion").val();

    let labelDetalleAccion = 'Detalle de la Acción';
    let labelRespuesta = 'Respuestas (Texto que escribe el bot cuando se ejecuta la accion)';

    if(tipoSeccion == 2){
        labelDetalleAccion = 'Campo de destino.';
        labelRespuesta = 'Respuestas (Texto opcional que puede escribir el bot despues de que el usuario registra el dato)';
    }

    let row = '';

    let num = noCampo;

    if(tipo != null){
        tipoSeccion = tipo;
    }

    switch (tipoSeccion) {
        case "1":
            // Conversacional

            row = agregarPlantillaConversacional(oper, noCampo, labelDetalleAccion, labelRespuesta);

            if(tipo == null){
                $("#accionesBotConversacional").append(row);
            }else{
                $("#accionesBotTransaccional").append(row);
            }

            inicializarSelectizeTags(num);

            // Inicializo los textarea respuesta
            tinymce.execCommand('mceRemoveEditor', false, 'rpta_'+noCampo);
            tinymce.execCommand('mceAddEditor', false, 'rpta_'+noCampo);

            // $('#accion_'+noCampo+' option[value="2_1"]').hide();

            break;

        case "2":
            // Captura de datos

            row = agregarPlantillaCaptura(oper, noCampo, labelDetalleAccion, labelRespuesta);

            // Si el tipo es null lo la plantilla se inserta en la seccion captura de datos o en la seccion transaccional
            if(tipo == null){
                $("#accionesBotCapturaDatos").append(row);
            }else{
                $("#accionesBotTransaccional").append(row);
            }

            $("#accion_"+noCampo).val(3);

            $("#pregunta_bot_"+num).keyup(function(){
                let tituloInsertar = obtenerIconoTitulo()+' '+$(this).val();
                $('#tituloBot_'+num).html(tituloInsertar.substr(0, 150));
            });

            // Oculto el campo de accion dado que por defecto es solo dar respuesta
            // $("#div_accion_"+noCampo).hide();
            // Muestro el campo de la base de datos
            $("#div_base_"+noCampo).show();

            // Inicializo los textarea de pregunta
            tinymce.execCommand('mceRemoveEditor', false, 'pregunta_bot_'+noCampo);
            tinymce.execCommand('mceAddEditor', false, 'pregunta_bot_'+noCampo);

            // Inicializo los textarea respuesta
            tinymce.execCommand('mceRemoveEditor', false, 'rpta_'+noCampo);
            tinymce.execCommand('mceAddEditor', false, 'rpta_'+noCampo);

            break;

        case "3":
            // Consulta de datos
            
            row = agregarPlantillaConsultaDatos(oper, noCampo);

            if(tipo == null){
                $("#accionesBotConsultaDatos").append(row);
            }else{
                $("#accionesBotTransaccional").append(row);
            }

            break;
        
        case "5":

            row = agregarPlantillaConsultaDyalogo(oper, noCampo);

            $("#accionesBotTransaccional").append(row);

            let seccionBd = $("#seccionBd").val();
            $("#bdConsultaDy_"+noCampo).val(seccionBd);
            $("#bdConsultaDy_"+noCampo).select2();

            $("#camposConsultaDy_"+noCampo).select2();

            agregarCondicionConsultaDy('add', noCampo);
        
            break;

        default:
            break;
    }

    noCampo++;
    $("#totalFilasSeccion").val(noCampo);
}

function agregarFilaTransaccional(tipo){

    let newTipo = null;

    if(tipo == 'captura'){
        newTipo = "2";
    }

    if(tipo == 'consulta'){
        newTipo = "3";
    }

    if(tipo == 'consultaDyalogo'){
        newTipo = "5";
    }

    agregarFilaBot("add", newTipo);
}

function agregarPlantillaConversacional(oper, noCampo, labelDetalleAccion, labelRespuesta){

    let row = `
        <div class="panel box box-success" id="row_${oper}_${noCampo}">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoBot_${noCampo}" id="tituloBot_${noCampo}">
                        ${obtenerIconoTitulo()}&nbsp;Titulo
                    </a>
                </h4>

                <div class="box-tools pull-right" data-toggle="tooltip" title="Avanzado">
                    <!-- <button type="button" id="btnAdvancedEditFieldConversacional_${noCampo}" class="btn btn-warning btn-sm" onclick="seccionAvanzado(${noCampo}, 'conversacional')"><i class="fa fa-cog"></i></button> -->
                    <button type="button" id="btnEliminarField_${noCampo}" onclick="eliminarRowBot('${oper}', ${noCampo})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </div>
            </div>

            <div id="cuerpoBot_${noCampo}" class="panel-collapse collapse in">
                <div class="box-body">
                <div class="row">
                    <input type="hidden" id="campo_${noCampo}" name="campo_${oper}_${noCampo}" value="" num="${noCampo}">
                    <input type="hidden" id="tipo_plantilla_${noCampo}" name="tipo_plantilla_${oper}_${noCampo}" value="conversacional" num="${noCampo}">

                    <div class="col-md-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="labelDisparador">Disparador (Texto que escribe el usuario)</label>
                                <input type="text" id="tag_${noCampo}" name="tag_${oper}_${noCampo}" class="form-control input-sm tags-input esteTag" num="${noCampo}">
                                <span class="info_disparador" class="help-block">Separe los tags con comas.</span>
                                <a id="myLink_${noCampo}" href="#" class="pull-right modalDisparador" onclick="abrirDisparadorModal(<?=$_SESSION['HUESPED']?>);return false;">Disparadores de uso frecuente</a>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipo de respuesta</label>

                                <div class="radio">
                                    <label style="padding-right: 20px;">
                                        <input type="radio" name="tipo_file_${noCampo}" id="tipoTexto_${noCampo}" value="TEXT" checked onclick="cambioTipoMensaje(${noCampo}, 'TEXT')"> Texto
                                    </label>

                                    <label style="padding-right: 20px;">
                                        <input type="radio" name="tipo_file_${noCampo}" id="tipoImagen_${noCampo}" value="IMAGE" onclick="cambioTipoMensaje(${noCampo}, 'IMAGE')"> Imagen
                                    </label>

                                    <label style="padding-right: 20px;">
                                        <input type="radio" name="tipo_file_${noCampo}" id="tipoVideo_${noCampo}" value="VIDEO" onclick="cambioTipoMensaje(${noCampo}, 'VIDEO')"> Video
                                    </label>

                                    <label style="padding-right: 20px;">
                                        <input type="radio" name="tipo_file_${noCampo}" id="tipoAudio_${noCampo}" value="AUDIO" onclick="cambioTipoMensaje(${noCampo}, 'AUDIO')"> Audio
                                    </label>
                                    
                                    <label style="padding-right: 20px;">
                                        <input type="radio" name="tipo_file_${noCampo}" id="tipoDocumento_${noCampo}" value="DOCUMENT" onclick="cambioTipoMensaje(${noCampo}, 'DOCUMENT')"> Documento
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" id="seccionMedia${noCampo}" style="display:none">
                                <label>Archivo</label>
                                <input type="file" name="archivoMedia${noCampo}" id="archivoMedia${noCampo}" onchange="return fileValidation(${noCampo})">
                                <p class="help-block" id="helpBlock${noCampo}">Help text</p>
                            </div>

                            <div class="form-group" id="seccionMediaActual${noCampo}" style="display:none">
                                <label>Archivo usado actualmente</label>
                                <p id="mediaActual${noCampo}"></p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">${labelRespuesta}</label>
                                <textarea class="myTextarea tinyMCE respuesta" id="rpta_${noCampo}" name="dyTr_rpta_${oper}_${noCampo}" cols="60" rows="1" num="${noCampo}"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Botones de respuesta (Máximo 10, si agrega 3 elementos se visualizará como botones, si se agrega mas de 3 elementos se visualizará como una lista en WhatsApp)</label>

                                <div class="col-md-8">
                                    <table class="table" id="listaBotones${noCampo}">
                                        <thead>
                                            <tr>
                                                <th>Texto(Máximo 20 caracteres)</th>
                                                <th>Respuesta</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12" style="padding-left: 0px; margin-bottom: 10px">
                                    <button type="button" class="btn btn-success btn-sm pull-left" id="agregarBoton" onclick="agregarBotonRespuesta(${noCampo})">
                                        <i class="fa fa-plus"></i> Agregar boton
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-2" style="padding-left: 0px">
                        <div class="col-md-12" style="padding-left: 0px">
                            <div class="form-group" id="div_accion_${noCampo}">
                                <label for="">Acción</label>
                                <select id="accion_${noCampo}" name="accion_${oper}_${noCampo}" class="form-control accion" onchange="cambiarAccion(${noCampo})" num="${noCampo}">
                                    <option value="0">Dar respuesta</option>
                                    <option value="2_1">Transferir a otra sección del bot</option>
                                    <option value="2_2">Transferir a otro bot</option>
                                    <option value="1">Transferir a agentes</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="padding-left: 0px">
                            <div class="form-group">
                                <label for="">${labelDetalleAccion}</label>
                                <select class="form-control" id="dar_respuesta_${noCampo}" name="dar_respuesta_${oper}_${noCampo}" disabled num="${noCampo}">
                                    <option value="0">Seleccione</option>
                                </select>
                                <select class="form-control" style="display: none;" id="campan_${noCampo}" name="campan_${oper}_${noCampo}" num="${noCampo}">
                                    ${opcionesCampana}
                                </select>
                                <select class="form-control" style="display: none;" id="bot_seccion_${noCampo}" name="bot_seccion_${oper}_${noCampo}" num="${noCampo}">
                                    ${opcionesSecciones}
                                </select>
                                <select class="form-control" style="display: none;" id="bot_${noCampo}" name="bot_${oper}_${noCampo}" num="${noCampo}" onchange="mostrarSeccionesBot(${noCampo})">
                                    ${opcionesBots}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding-left: 0px; display: none;" id="row_seccion_bot${noCampo}">
                            <div class="form-group">
                                <label for="">Seccion del bot</label>
                                <select class="form-control" id="seccion_bot_${noCampo}" name="seccion_bot_${oper}_${noCampo}" num="${noCampo}">
                                    <option value="0"></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding-left: 0px">
                            <div class="form-group">
                                <label for="">Guardar esta respuesta</label>
                                <select class="form-control campos-conversacional" id="guardar_respuesta_${noCampo}" name="guardar_respuesta_${oper}_${noCampo}" onchange="cambiarTipoGuardadoConversacional(${noCampo})" num="${noCampo}">
                                    <option value="0">No guardar</option>
                                    <option value="1">Guardar en la BD</option>
                                    <option value="2">Guardar en la Gestión</option>
                                    <option value="3">Guardar en la BD y Gestión</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding-left: 0px; display:none" id="opcionesGuardarGestion${noCampo}">
                            <div class="radio">
                                <label>
                                    <input type="radio" id="usarCampoGestionPropio${noCampo}" name="usarCampoGestion${noCampo}" value="1" onclick="usarCampoGestion(1, ${noCampo}, 'convers')" class="minimal">
                                    Usar campo propio
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" id="usarCampoGestionExistente${noCampo}" name="usarCampoGestion${noCampo}" value="0" onclick="usarCampoGestion(0, ${noCampo}, 'convers')" class="minimal">
                                    Usar campo existente
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding-left: 0px">
                            <div class="form-group">
                                <label for="" id="labelGuardarRespuestaConversacional${noCampo}" style="display:none">Selecciona el campo</label>
                                
                                <select class="form-control campos-pregunta" style="display: none;" id="pregunConver_${noCampo}" name="pregunConver_${oper}_${noCampo}" num="${noCampo}">
                                    <option value="">Seleccione</option>
                                    ${opcionesCamposBd}
                                </select>

                                <input type="text" class="form-control" style="display: none;" id="nombre_variable_${noCampo}" name="nombre_variable_${oper}_${noCampo}" onkeyup="limpiarEspacios('nombre_variable_${noCampo}')" value="" num="${noCampo}" placeholder="Nombre de la variable">

                                <select class="form-control" style="display: none;" id="pregunGestionExistente_${noCampo}" name="pregunGestionExistente_${oper}_${noCampo}" num="${noCampo}">
                                    <option value="">Seleccione</option>
                                    ${opcionesCamposGestion}
                                </select>
                            </div>
                        </div>                        

                        <!--<div class="col-md-12" style="padding-left: 0px">-->
                        <!--<button type="button" class="btn btn-primary" onclick="cambiarSeccion(${noCampo})">Ir a la seccion</button>-->
                        <!--</div>-->
                    </div>

                </div>
                </div>
            </div>
        </div>
    `;

    return row;
}

function mostrarSeccionesBot(rowId, ejecutor = 'conversacional'){

    if(ejecutor == 'accionFinal'){
        $nombreBot = 'bot_accion_final_';
        $nombreSeccion = 'accion_final_seccion_bot_';
    }else{
        $nombreBot = 'bot_';
        $nombreSeccion = 'seccion_bot_';
    }
    let botId = $("#"+$nombreBot+rowId).val();

    $("#"+$nombreSeccion+rowId).html('');

    if(botId == ''){ return; }
    
    // Obtengo las secciones especificas de este bot
    $.ajax({
        url: '<?=$url_crud;?>?obtenerSeccionesDeBot=true&botId='+botId,
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){

            let opciones = '';
            
            if(data.secciones && data.secciones.length > 0){
                data.secciones.forEach(element => {
                    opciones += `<option value="${element.id}">${element.nombre}</option>`;
                });
            }
            $("#"+$nombreSeccion+rowId).html(opciones);

            // Llenamos la opcion si existe el atributo
            if($("#"+$nombreBot+rowId).attr('data-basetransferencia') !== undefined){
                let valor = $("#"+$nombreBot+rowId).attr('data-basetransferencia');
                $("#"+$nombreSeccion+rowId).val(valor);
                $("#"+$nombreBot+rowId).removeAttr('data-basetransferencia');
            }
        },
        complete : function(){
            $.unblockUI();
        },
        error: function(){
            alertify.error("Se presento un problema al cargar la lista de secciones");
        }
    });
}

function agregarPlantillaCaptura(oper, noCampo, labelDetalleAccion, labelRespuesta){
    
    let row = `
        <div class="panel box box-success" id="row_${oper}_${noCampo}">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoBot_${noCampo}" id="tituloBot_${noCampo}">
                        ${obtenerIconoTitulo()}&nbsp;Titulo
                    </a>
                </h4>

                <div class="box-tools pull-right" data-toggle="tooltip">
                    <button type="button" id="btnAdvancedEditFieldCaptura_${noCampo}" onclick="seccionAvanzado(${noCampo}, 'transaccional')" class="btn btn-warning btn-sm"><i class="fa fa-cog"></i></button>
                    <button type="button" id="btnEliminarField_${noCampo}" onclick="eliminarRowBot('${oper}', ${noCampo})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </div>
            </div>

            <div id="cuerpoBot_${noCampo}" class="panel-collapse collapse in">
                <div class="box-body">
                <div class="row">
                    <input type="hidden" id="campo_${noCampo}" name="campo_${oper}_${noCampo}" value="" num="${noCampo}">
                    <input type="hidden" id="tipo_plantilla_${noCampo}" name="tipo_plantilla_${oper}_${noCampo}" value="captura" num="${noCampo}">

                    <div class="col-md-10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="labelDisparador">Pregunta que el bot le hace al usuario</label>
                                <textarea id="pregunta_bot_${noCampo}" name="dyTr_pregunta_bot_${oper}_${noCampo}" class="form-control input-sm preg myTextarea tinyMCE" num="${noCampo}"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">${labelRespuesta}</label>
                                <textarea class="myTextarea tinyMCE respuesta" id="rpta_${noCampo}" name="dyTr_rpta_${oper}_${noCampo}" cols="60" rows="1" num="${noCampo}"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2" style="padding-left: 0px">
                        <div class="col-md-12" style="padding-left: 0px">
                            <div class="form-group" id="div_accion_${noCampo}">
                                <label for="">Guardar respuesta</label>
                                <select id="accion_${noCampo}" name="accion_${oper}_${noCampo}" class="form-control accion" onchange="cambiarAccion(${noCampo})" num="${noCampo}">
                                    <option value="4">No guardar</option>
                                    <option value="5">Guardar en la BD</option>
                                    <option value="6">Guardar en la Gestión</option>
                                    <option value="3">Guardar en la BD y Gestión</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding-left: 0px; display:none" id="opcionesGuardarGestion${noCampo}">
                            <div class="radio">
                                <label>
                                    <input type="radio" id="usarCampoGestionPropio${noCampo}" name="usarCampoGestion${noCampo}" value="1" onclick="usarCampoGestion(1, ${noCampo}, 'captura')" class="minimal">
                                    Usar campo propio
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" id="usarCampoGestionExistente${noCampo}" name="usarCampoGestion${noCampo}" value="0" onclick="usarCampoGestion(0, ${noCampo}, 'captura')" class="minimal">
                                    Usar campo existente
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="padding-left: 0px">
                            <div class="form-group">
                                <label for="" id="labelAccionCaptura${noCampo}">${labelDetalleAccion}</label>
                                
                                <select class="form-control campos-pregunta" id="pregun_${noCampo}" name="pregun_${oper}_${noCampo}" num="${noCampo}">
                                    <option value="">Seleccione</option>
                                    ${opcionesCamposBd}
                                </select>

                                <input type="text" class="form-control" style="display: none;" id="nombre_variable_${noCampo}" name="nombre_variable_${oper}_${noCampo}" onkeyup="limpiarEspacios('nombre_variable_${noCampo}')" value="" num="${noCampo}" placeholder="Nombre de la variable">

                                <select class="form-control" style="display: none;" id="pregunGestionExistente_${noCampo}" name="pregunGestionExistente_${oper}_${noCampo}" num="${noCampo}">
                                    <option value="">Seleccione</option>
                                    ${opcionesCamposGestion}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    `;

    return row;
}

function agregarPlantillaConsultaDatos(oper, noCampo){

    let row = `
        <div class="panel box box-success" id="row_${oper}_${noCampo}">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoService_${noCampo}" id="tituloService_${noCampo}">
                        ${obtenerIconoTitulo()}&nbsp;Titulo
                    </a>
                </h4>

                <div class="box-tools pull-right" data-toggle="tooltip">
                    <button type="button" id="btnAdvancedEditFieldConsulta_${noCampo}" class="btn btn-warning btn-sm"><i class="fa fa-cog"></i></button>
                    <button type="button" id="btnEliminarFieldService_${noCampo}" onclick="eliminarRowWS('${oper}', ${noCampo})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </div>
            </div>

            <div id="cuerpoService_${noCampo}" class="panel-collapse collapse in">
                <div class="box-body">
                    <div class="row">
                        <input type="hidden" id="campo_${noCampo}" name="campo_${oper}_${noCampo}" value="" num="${noCampo}">
                        <input type="hidden" id="tipo_plantilla_${noCampo}" name="tipo_plantilla_${oper}_${noCampo}" value="consulta" num="${noCampo}">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="">WebService</label>
                                <select name="webservice_${oper}_${noCampo}" id="webservice_${noCampo}" class="form-control input-sm" onchange="cambiows(${noCampo})" data-oldvalue="0">
                                    ${opcionesWebservicesGlobales}
                                </select>
                            </div>
                        </div>

                        <div id="webserviceParametros_${noCampo}"></div>

                    </div>
                </div>
            </div>

        </div>
    `;

    return row;
}

function agregarPlantillaConsultaDyalogo(oper, noCampo){
    
    let row = `
        <div class="panel box box-success" id="row_${oper}_${noCampo}">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoConsultaDy_${noCampo}" id="tituloConsultaDy_${noCampo}">
                        ${obtenerIconoTitulo()}&nbsp;Titulo
                    </a>
                </h4>

                <div class="box-tools pull-right" data-toggle="tooltip">
                    <button type="button" id="btnAdvancedEditConsultaDy_${noCampo}" class="btn btn-warning btn-sm"><i class="fa fa-cog"></i></button>
                    <button type="button" id="btnEliminarConsultaDy_${noCampo}" onclick="eliminarRowConsultaDy('${oper}', ${noCampo})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </div>
            </div>

            <div id="cuerpoConsultaDy_${noCampo}" class="panel-collapse collapse in">
                <div class="box-body">
                    <div class="row">
                        <input type="hidden" id="campo_${noCampo}" name="campo_${oper}_${noCampo}" value="" num="${noCampo}">
                        <input type="hidden" id="tipo_plantilla_${noCampo}" name="tipo_plantilla_${oper}_${noCampo}" value="consultaDyalogo" num="${noCampo}">
                        <input type="hidden" id="cantConsultaDyCondiciones_${noCampo}" name="cantConsultaDyCondiciones_${oper}_${noCampo}" value="0" num="${noCampo}">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="">Base de datos</label>
                                <select name="bdConsultaDy_${oper}_${noCampo}" id="bdConsultaDy_${noCampo}" class="form-control input-sm" onchange="cambioBdConsultaDy(${noCampo})" data-oldvalue="0">
                                    ${opcionesCamposG}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="">Campos de la base de datos a mostrar</label>
                                <select name="camposConsultaDy_${oper}_${noCampo}[]" id="camposConsultaDy_${noCampo}" class="form-control input-sm" multiple="multiple">
                                    ${opcionesCamposBd}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="" class="">Condiciones</label>
                            <div id="condicionesConsultaDy_${noCampo}">
                            </div>
                            <button type="button" id="agregarCondicionConsultaDy_${noCampo}" class="btn btn-primary btn-sm" onclick="agregarCondicionConsultaDy('add', ${noCampo})">Agregar condicion</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    `;

    return row;
}

// Este paso se encarga de obtener las variables dinamicas para la seccion del bot
function obtenerVariables(pasoId, esSeccion = false){

    $.ajax({
        url: '<?=$url_crud;?>?obtenerVariables=true&pasoId='+pasoId+'&esSeccion='+esSeccion+'&huesped=<?=$_SESSION['HUESPED']?>',
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        success: function(data){

            // Traigo los campos de la base de datos
            opcionesCamposBd = '';
            if(data.camposBd){
                camposBd = data.camposBd;

                $.each(data.camposBd, function(i, item){
                    opcionesCamposBd += `<option value="${item.id}">${item.nombre}</option>`;
                });
            }

            // Aqui traigo las variables dinamicas que captura el bot   
            if(data.variablesBot){
                variablesDinamicasBot = data.variablesBot;
            }

            // Aqui traigo las variables dinamicas que captura el ws
            if(data.variablesWs){
                variablesDinamicasWs = data.variablesWs;
            }

            if(data.listaCamposDyalogo){
                variablesDinamicasConsultaDy = data.listaCamposDyalogo;
            }

            // Traigo la lista de campanas de la estrategia
            opcionesCampana = '<option value="">Seleccione</option>';
            if(data.listaCampanas){
                $.each(data.listaCampanas, function(i, item){
                    opcionesCampana += `<option value="${item.dy_campan}">${item.nombre}</option>`;
                });
            }

            // Traigo la lista de bots de la estrategia
            opcionesBots = '<option value="">Seleccione</option>';
            if(data.listaBots){
                $.each(data.listaBots, function(i, item){
                    opcionesBots += `<option value="${item.id}">${item.nombre}</option>`;
                });
            }

            // Traigo la lista de secciones del bot
            opcionesSecciones = '<option value="">Seleccione</option>';
            if(data.listaSeccionesBot){
                $.each(data.listaSeccionesBot, function(i, item){
                    opcionesSecciones += `<option value="${item.base_autorespuesta_id}">${item.nombre}</option>`;
                });
            }

            // LLeno la lista de webservices
            opcionesWebservicesGlobales = '<option value="">Seleccione</option>';
            if(data.webservices){
                $.each(data.webservices, function(i, item){
                    opcionesWebservicesGlobales += `<option value="${item.id}">${item.nombre}</option>`;
                });
            }

            formatearVariablesDinamicas(variablesDinamicasBot, variablesDinamicasWs, data.listaSeccionesBot, data.listaCamposDyalogo);

        },
        complete : function(){
            $.unblockUI();
        },
    });

}

function formatearVariablesDinamicas(variablesDinamicasBot, variablesDinamicasWs, listaSecciones, listaCamposDyalogo){
    let opciones = '<option value="">Seleccione</option>';
    opciones += '<option value="${DY_ANI}">${DY_ANI}</option>';
    let varDinamicasObj = [];

    if(listaSecciones.length > 0){

        listaSecciones.forEach(element => {

            let nombre = '';
            let valor = '';

            if(element.tipo_base == 2){
                opciones += `<optgroup label="${element.nombre}">`;

                opciones += variablesDinamicasBot.map(variable => {
                    if(variable.autoresId == element.base_autorespuesta_id){

                        nombre = '${'+variable.variable+'}';
                        valor = '${'+variable.variable+'}';

                        varDinamicasObj.push({"nombre": nombre, "valor": valor, "grupo":element.nombre, "valor2": variable.id_pregun});
                        return `<option value="${valor}">${nombre}</option>`;
                    }
                });

                opciones += '</optgroup>';
            }
            
            if(element.tipo_base == 3){
                opciones += `<optgroup label="${element.nombre}">`;

                opciones += variablesDinamicasWs.map(variable => {

                    if(variable.autoresId == element.base_autorespuesta_id){
                        
                        nombre = '${'+variable.nombreService.toUpperCase()+'.'+variable.variable+'}';
                        valor = '${'+variable.seccion.toUpperCase()+'.'+variable.variable+'}';

                        varDinamicasObj.push({"nombre": nombre, "valor": valor, "grupo":element.nombre, "valor2": valor});
                        return `<option value="${valor}">${nombre}</option>`;
                    }
                });

                opciones += '</optgroup>';
            }

            if(element.tipo_base == 5){
                opciones += `<optgroup label="${element.nombre}">`;
                
                opciones += listaCamposDyalogo.map(variable => {
                    if(variable.autoresId == element.base_autorespuesta_id){
                        nombre = '${'+variable.nombre+'}';
                        valor = '${'+variable.id+'}';

                        varDinamicasObj.push({"nombre": nombre, "valor": valor, "grupo":element.nombre, "valor2": valor});
                        return `<option value="${valor}">${nombre}</option>`;
                    }
                });

                opciones += '</optgroup>';
            }

        });

    }

    variablesDinamicasObj = varDinamicasObj;
    variablesDinamicas = opciones;
}

function formatearTextoEnriquecido(campo){

    // Obtenemos el valor
    let texto = $(campo).val();

    // Aplicamos el replace
    let textarea = texto.replace(/(\r\n|\n|\r)/gm, "");
    
    // Agregamos el valor formateado al campo
    $(campo).val(textarea);
}

function inicializarTinymce(){
    tinymce.init({
        selector: 'textarea.tinyMCE',
        height : 200,
        skin: 'small',
        encoding: 'UTF-8',
        entity_encoding: 'raw',
        // icons: 'small',
        menubar: false,
        plugins: 'emoticons paste',
        toolbar: 'bold italic strikethrough | emoticons | mybutton',
        language: 'es',
        branding: false,
        paste_as_text: true,
        content_style: '.mce-content-body p { padding: 0; margin: 2px 0;}',
        init_instance_callback: function (editor) {
            editor.on('KeyUp', function (e) {
                if($('#tipoSeccion').val() == 2){
                    let identificador = e.target.dataset.id;
                    if(identificador.startsWith('pregunta_bot_')){
                        let texto = e.target.textContent;

                        let num = $("#"+identificador).attr('num');

                        let tituloInsertar = obtenerIconoTitulo()+' '+texto;
                        $('#tituloBot_'+num).html(tituloInsertar.substr(0, 100));
                    }
                }
            });
        },
        setup: function(editor){
            var items = [];

            if(variablesDinamicasObj){
                for (const key in variablesDinamicasObj) {
    
                    let element = {
                        type: 'menuitem',
                        text: variablesDinamicasObj[key].nombre,
                        onAction: function () {
                            editor.insertContent(' '+variablesDinamicasObj[key].valor+' ');
                        }
                    };
    
                    items.push(element);
                }           
            }
            
            editor.ui.registry.addMenuButton('mybutton', {
                text: 'Variables dinamicas',
                fetch: function (callback) {
                    callback(items);
                }
            });
        }
    });
}

function inicializarSelectizeTags(id){
    $('#tag_'+id).selectize({
        plugins: ['remove_button'],
        persist: false,
        createOnBlur: true,
        create: true,
        render:{
            item: function(data) {
                if(data.value.startsWith('#')){

                    if(tagsComunes.length > 0){
                        if(tagsComunes.includes(data.value)){
                            return `<div class="item c-tag-${id} green" data-value="${data.value}" onclick="abrirDisparadorModal(<?=$_SESSION['HUESPED']?>, '${data.value}')">${data.value}</div>`;
                        }
                    }
                    
                }
                
                return `<div class="item c-tag-${id}" data-value="${data.value}">${data.value}</div>`;
                
            }
        },
        onItemAdd: function (value) {

            // Validar si el item ya existe en una de las listas
            if(validarTagsUnicosEnSeccion(value)){

                // Agregar el item a la lista
                let newTitulo = obtenerIconoTitulo()+' '+$('#tag_'+id).val()+ ','+value;
                if($('#tag_'+id).val() == ''){
                    newTitulo = obtenerIconoTitulo()+' '+value;
                }
                
                $('#tituloBot_'+id).html(newTitulo.substr(0, 150));

            }else{
                // Me toca darle un delay de 1seg para que le tome tiempo y ejecute el evento addItem
                setTimeout(function () {
                    limpiarTagsRepetidos(id, value);
                }, 1000);
                alertify.error("Este tag ya se encuentra agregado en otro campo de disparador");
            }
            
            
        },
        onItemRemove: function(){
            let newTitulo = obtenerIconoTitulo()+' '+$('#tag_'+id).val();
            $('#tituloBot_'+id).html(newTitulo.substr(0, 150));
        }
    });
}

function cambiarLabelInformativo(tipoSeccion){
    let texto = '';

    switch (tipoSeccion) {
        case "1":
            texto = "En estas secciones el bot va saltando a donde corresponda, según lo que escribe el cliente";
            break;
        case "2":
            texto = "En estas secciones el bot presenta cada una de las preguntas de forma secuencial en el orden que se configure acá";
            break;
        default:
            break;
    }

    $(".info_modal").html(texto);
}

function obtenerIconoTitulo(){

    let tipoSeccion = $("#tipoSeccion").val();

    let icono = '<i class="fa fa-comments-o"></i>';

    switch (tipoSeccion) {
        case '1':
            icono = '<i class="fa fa-comments-o"></i>';
            break;

        case '2':
            icono = '<i class="fa fa-arrows-v"></i>';
            break;

        case '3':
            icono = '<i class="fa fa-plug"></i>';
            break;
    
        default:
            break;
    }

    return icono;
}

function cambiarAccion(campo){

    let valorActual = $("#accion_"+campo).val();

    $('#dar_respuesta_'+campo).hide();
    $('#campan_'+campo).hide();
    $('#bot_seccion_'+campo).hide();
    $('#bot_'+campo).hide();
    $('#pregun_'+campo).hide();
    $('#nombre_variable_'+campo).hide();
    $('#row_seccion_bot'+campo).hide();

    $("#opcionesGuardarGestion"+campo).hide();
    $("#pregunGestionExistente_"+campo).hide();

    switch (valorActual) {
        case "1":
            $('#campan_'+campo).show();
            break;
        case "2_1":
            $('#bot_seccion_'+campo).show();
            break;
        case "2_2":
            $('#bot_'+campo).show();
            $('#row_seccion_bot'+campo).show();
            break;
        case "3":
            $('#pregun_'+campo).show();
            $("#labelAccionCaptura"+campo).text("Campo de destino.");
            break;
        case "4":
            $("#nombre_variable_"+campo).show();
            $("#labelAccionCaptura"+campo).text("Ingresa un nombre(Este nombre identificara al dato ingresado en otras secciones)");
            break;
        case "5":
            $('#pregun_'+campo).show();
            $("#labelAccionCaptura"+campo).text("Campo de destino.");
            break;
        case "6":
            $("#nombre_variable_"+campo).show();
            $("#labelAccionCaptura"+campo).text("Ingresa un nombre(Este nombre identificara al dato ingresado en otras secciones)");
            $("#opcionesGuardarGestion"+campo).show();
            $("#usarCampoGestionPropio"+campo).click();
            break;
        default:
            $('#dar_respuesta_'+campo).show();
            break;
    }
}

function cambiows(id){

    // Traigo el texto del select
    let texto = $("#webservice_"+id+" option:selected").text();
    let webservice = $("#webservice_"+id).val();
    let oldwebservice = $("#webservice_"+id).data("oldvalue");

    $("#tituloService_"+id).html(obtenerIconoTitulo()+'&nbsp;'+texto);

    // Hago la consulta para trae los parametros del webservice
    $.ajax({
        url: '<?=$url_crud;?>?getParametrosWebservice=si&webserviceId='+webservice,  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            if(data){
                console.log(data);

                // Primero hago el cambio de las variables dinamicas

                // Si se cambia el ws debo quitar los parametros viejos
                // if(oldwebservice != 0){
                //     variablesDinamicasWs = variablesDinamicasWs.filter((i) => i.id_ws !== oldwebservice);
                // }

                // if(data.parametrosRetorno){
                //     let nombreSeccion = $("#seccionNombre").val();
                //     nombreSeccion = nombreSeccion.slice(0, 10);

                //     let nombreWs = texto.slice(0, 10);

                //     let nombreSeccion = 

                //     $.each(data.parametrosRetorno, function(j, item2)){
                //         variablesDinamicasWs.push({"id_ws": webservice, "seccion": nombreSeccion});
                //     });
                // }

                if(data.parametros && data.parametros.length > 0){

                    let htmlParamsBody = '';

                    $.each(data.parametros, function(i, item){
                        htmlParamsBody += `
                        <tr>
                            <input type="hidden" name="parametro_${id}_${item.id}" id="parametro_${id}_${item.id}">
                            <td>${item.parametro}</td>
                            <td>
                                <select name="tipoParametro_${id}_${item.id}" id="tipoParametro_${id}_${item.id}" class="form-control input-sm" onchange="cambioTipoValor(${item.id}, ${id})">
                                    <option value="1">Estatico</option>
                                    <option value="2">Dinamico</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="valorEstatico_${id}_${item.id}" id="valorEstatico_${id}_${item.id}" class="form-control input-sm">

                                <select name="valorDinamico_${id}_${item.id}" id="valorDinamico_${id}_${item.id}" class="form-control input-sm" style="display:none">
                                    ${variablesDinamicas}
                                </select> 
                            </td>
                        </tr>
                        `;
                    });
                    
                    // Armo la estructura de los parametros
                    let htmlParamsEstructura = `
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del parametro</th>
                                    <th>Tipo de valor</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${htmlParamsBody}
                            </tbody>
                        </table>
                    `;

                    $('#webserviceParametros_'+id).html(htmlParamsEstructura);

                }else{
                    $('#webserviceParametros_'+id).html('No hay parametros para este webservice');
                }
                
            }
        },
        error: function(data){
            if(data.responseText){
                alertify.error("Hubo un error al traer la información" + data.responseText);
            }else{
                alertify.error("Hubo un error al traer la información");
            }
        }
    });

}

function cambioTipoValor(id, rowId){
    let tipoValor = $("#tipoParametro_"+rowId+"_"+id).val();
    
    $("#valorEstatico_"+rowId+"_"+id).hide();
    $("#valorDinamico_"+rowId+"_"+id).hide();

    if(tipoValor == 1){
        $("#valorEstatico_"+rowId+"_"+id).show();
    }else{
        $("#valorDinamico_"+rowId+"_"+id).show();
    }
}

function eliminarRowBot(oper, id){
    if(oper == 'add'){
        $("#row_add_"+id).remove();
    }else{
        let rowId = $("#campo_"+id).val();
        let seccionId = $("#seccionBotId").val();
    
        // Aqui elimina el filtro en la bd
        alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
            if(e){
                $.ajax({
                    url: '<?=$url_crud;?>?borrar_campo_bot=si',  
                    type: 'POST',
                    dataType: 'json',
                    data: { campoBotId:rowId, seccionId:seccionId },
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    //una vez finalizado correctamente
                    success: function(data){
                        $("#row_edit_"+id).remove();
                        alertify.success("campo eliminado");
                    }
                });     
            }
        });
    }
}

function eliminarRowWS(oper, id){
    if(oper == 'add'){
        $("#row_add_"+id).remove();
    }else{
        let rowId = $("#campo_"+id).val();
    
        // Aqui elimina el filtro en la bd
        alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
            if(e){
                $.ajax({
                    url: '<?=$url_crud;?>?borrar_campos_ws=si',  
                    type: 'POST',
                    dataType: 'json',
                    data: { rowId:rowId },
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    //una vez finalizado correctamente
                    success: function(data){
                        $("#row_edit_"+id).remove();
                        alertify.success("campo eliminado");
                    }
                });     
            }
        });
    }
}

// Esta es una function que cambia como las opciones de la base de datos
function cambiarBd(baseId){
    
    $.ajax({
        url: '<?=$url_crud;?>?get_campos_bd_especifica=si&baseId='+baseId,  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            if(data){

                let listaCampos = '';

                if(data.campos){
                    $.each(data.campos, function(i, item){
                        listaCampos += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                }
                
                $(".campos-pregunta").html(listaCampos);
                $(".campos-conversacional").html('<option value="0">NO GUARDAR</option>'+listaCampos);
                $("#llavePrincipal").html('<option value="">Seleccione</option>'+listaCampos);
                opcionesCamposGlobales = listaCampos;
                camposBd = data.campos;
                opcionesCamposBd = listaCampos;
                
            }
        },
        error: function(data){
            if(data.responseText){
                alertify.error("Hubo un error al traer la información" + data.responseText);
            }else{
                alertify.error("Hubo un error al traer la información");
            }
        }
    });
}

// Acciones finales
function agregarAccionFinal(esAccionFinalDefecto, condicion, oper){

    let contador = $("#totalAccionesFinales").val();

    let num = contador;

    let nombre = esAccionFinalDefecto ? 'Acción por defecto' : `accion condicionada ${++num} ${condicion}`;

    let row = `
        <div class="panel box box-success" id="accionFinal${contador}">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoAccionFinal${contador}" id="tituloAccionFinal${contador}">
                        <i class="fa fa-comments-o"></i>&nbsp;${nombre}
                    </a>
                </h4>

                <div class="box-tools pull-right" data-toggle="tooltip" title="Avanzado">
                    <button type="button" id="btnAdvancedEditFieldAccionFinal_${contador}" class="btn btn-warning btn-sm" onclick="seccionAvanzado(${contador}, 'accionFinal')"><i class="fa fa-cog"></i></button>
                    <button type="button" id="btnEliminarAccionFinal_${contador}" onclick="eliminarAccionFinal(${contador})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </div>
            </div>

            <div id="cuerpoAccionFinal${contador}" class="panel-collapse collapse in">
                <div class="box-body">
                    <div class="row">
                        
                        <input type="hidden" id="campo_accion_final_${contador}" name="campo_accion_final_${contador}" value="">

                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="">Respuestas (Texto que escribe el bot cuando se ejecuta la accion)</label>
                                <textarea class="tinyMCE" id="rpta_accion_final_${contador}" name="dyTr_rpta_accion_final_${contador}" cols="60" rows="1"></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-3">                                        
                            <div class="form-group">
                                <label for="">Acción</label>
                                <select id="accion_accion_final_${contador}" name="accion_accion_final_${contador}" class="form-control accion-final" onchange="cambioAccionFinal(${contador})" num="${contador}">
                                    <option value="2_1">Transferir a otra sección del bot</option>
                                    <option value="2_2">Transferir a otro bot</option>
                                    <option value="1">Transferir a agentes</option>
                                    <option value="7">Insertar en un paso de acciones salientes</option>
                                </select>
                            </div>                                            
                            <div class="form-group">
                                <label for="">Detalle de la Acción</label>
                                <select class="form-control" style="display: none;" id="campan_accion_final_${contador}" name="campan_accion_final_${contador}">
                                    ${opcionesCampana}
                                </select>
                                <select class="form-control" id="bot_seccion_accion_final_${contador}" name="bot_seccion_accion_final_${contador}">
                                    ${opcionesSecciones}
                                </select>
                                <select class="form-control" style="display: none;" id="bot_accion_final_${contador}" name="bot_accion_final_${contador}" onchange="mostrarSeccionesBot(${contador}, 'accionFinal')">
                                    ${opcionesBots}
                                </select>
                                <select class="form-control" style="display: none;" id="pasos_externos_accion_final_${contador}" name="pasos_externos_accion_final_${contador}">
                                    ${opcionesPasosEjecutables}
                                </select>
                            </div>

                            <div class="col-md-12" style="padding-left: 0px; display: none;" id="accion_final_row_seccion_bot${contador}">
                                <div class="form-group">
                                    <label for="">Seccion del bot</label>
                                    <select class="form-control" id="accion_final_seccion_bot_${contador}" name="accion_final_seccion_bot_${contador}" num="${contador}">
                                        <option value="0"></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    `;

    if(esAccionFinalDefecto){
        $("#accionFinalDefecto").append(row);
        $("#btnEliminarAccionFinal_"+contador).hide();
        $("#btnAdvancedEditFieldAccionFinal_"+contador).hide();
    }else{
        $("#accionesFinales").append(row);
    }

    // Inicializo el campo
    tinymce.execCommand('mceRemoveEditor', false, 'rpta_accion_final_'+contador);
    tinymce.execCommand('mceAddEditor', false, 'rpta_accion_final_'+contador);

    if(oper == 'add'){
        
        let autorespuesta = $("#autorespuestaAccionFinal").val();

        crearAccionFinal(contador, autorespuesta, false);
    }

    contador++;
    $("#totalAccionesFinales").val(contador);
}

function crearAccionFinal(contador, autorespuesta, creadoPorFlecha, destino = null){

    $.ajax({
        url: '<?=$url_crud;?>?crearAccionFinal=true',
        type: 'POST',
        dataType: 'json',
        data: { autorespuesta:autorespuesta, destino: destino },
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){
            if(creadoPorFlecha){
                $("#campo_disparador_"+contador).val(data.id);
                $("#oper_disparador_"+contador).val('edit');
            }else{
                $("#campo_accion_final_"+contador).val(data.id);
            }
        },
        error: function(data){
            console.log(data);
            alertify.error("Se presento un error al traer la informacion intenta nuevamente");
        }
    });
}

function cambioAccionFinal(id){
    var campo = $("#accion_accion_final_"+id).val();

    $('#campan_accion_final_'+id).hide();
    $('#bot_seccion_accion_final_'+id).hide();
    $('#bot_accion_final_'+id).hide();
    $('#accion_final_row_seccion_bot'+id).hide();
    $('#pasos_externos_accion_final_'+id).hide();

    switch (campo) {
        case "1":
            $('#campan_accion_final_'+id).show();
            break;
        case "2_1":
            $('#bot_seccion_accion_final_'+id).show();
            break;
        case "2_2":
            $('#bot_accion_final_'+id).show();
            $('#accion_final_row_seccion_bot'+id).show();
            break;
        case "7":
            $('#pasos_externos_accion_final_'+id).show();
            break;
        default:
            break;
    }
}

function eliminarAccionFinal(id){
    
    let rowId = $("#campo_accion_final_"+id).val();
    let tipoSeccion = 'transaccional';
    let from = $("#autorespuestaAccionFinal").val();

    // Aqui elimina el filtro en la bd
    alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
        if(e){
            $.ajax({
                url: '<?=$url_crud;?>?borrarDisparadorFlecha=si',
                type: 'POST',
                dataType: 'json',
                data: { id:rowId, tipoSeccion: tipoSeccion, from: from },
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                },
                //una vez finalizado correctamente
                success: function(data){
                    $("#accionFinal"+id).remove();
                    alertify.success("Accion eliminada");
                }
            });     
        }
    });
}

function eliminarSeccion(id){

    let pasoId = $("#pasoId").val();

    alertify.confirm("Eliminar seccion", function (e) {
        if(e){

            $.ajax({
                url: '<?=$url_crud;?>?eliminarSeccion=si',  
                type: 'POST',
                dataType: 'json',
                data: { id: id },
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                },
                //una vez finalizado correctamente
                success: function(data){
                    if(data.estado == 'ok'){
                        alertify.success(data.mensajeError);
                    }else{
                        alertify.error(data.mensajeError);
                    }
                    recargarFlujograma(pasoId);
                }
            });  

        }else{
            recargarFlujograma(pasoId);
        }
    });

}

// Funciones de tags

function abrirDisparadorModal(huesped, tag = null){
    $("#tagsComunesTable tbody").html('');
    $("#tagsComunesModal").modal();
    $("#totalCamposComunes").val(0);

    $.ajax({
        url: '<?=$url_crud;?>?getTagsComunes=si&id_paso=<?=$_GET['id_paso']?>&huesped='+huesped,  
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){
            if(data){

                if(data.campos){
                    $.each(data.campos, function(i, item){

                        addCampotagsComunes('edit');

                        $("#cTag_"+i).val(item.id);
                        $("#mTag_"+i).val(item.tag_disparador);
                        $("#tTag_"+i).val(item.expresiones);

                        $('#tTag_'+i).selectize({
                            plugins: ['remove_button'],
                            persist: false,
                            createOnBlur: true,
                            create: true,                           
                        });
                        
                    });

                }
            }
        }
    });
}

function obtenerTagsComunes(){

    let huesped = <?=$_SESSION['HUESPED']?>;

    $.ajax({
        url: '<?=$url_crud;?>?tagsComunes=true',
        type: 'POST',
        dataType: 'json',
        data: { huesped:huesped },
        // beforeSend : function(){
        //   // $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        // },
        // complete : function(){
        //     $.unblockUI();
        // },
        //una vez finalizado correctamente
        success: function(data){
            if(data.tagsComunes && data.tagsComunes.length > 0){
                tagsComunes = data.tagsComunes;
            }
        },
        error: function(data){
            console.log(data);
            alertify.error("Se presento un error al traer los tags comunes");
        }
    });
}

function validarTagsUnicosEnSeccion(tag){

    let valido = true;
    let contador = 0;

    // Recorro todos los elementos que tengan la class item
    $(".item").each(function(){
        if($(this).attr("data-value") === tag){
            contador+= 1;
        }
    });

    if(contador > 1){
        valido = false;
    }

    return valido;
}

function limpiarTagsRepetidos(inputId, tag){
    // Primero limpio el tag de la vista
    $('.item.c-tag-'+inputId+'[data-value="'+tag+'"]').remove();

    // Luego lo limpio del value del archivo
    let tags = $("#tag_"+inputId).val();

    // lo elimino del value
    let arrTags = tags.split(',');
    let i = arrTags.indexOf(tag);

    if (i !== -1) {
        arrTags.splice(i,1);
    }

    tags = arrTags.toString();
    $("#tag_"+inputId).val(tags);

}

function guardarTagsComunes(){
    let valido = true;

    quitarCampoError();

    $('.tag-comun-disp').each(function(){
        if($(this).val() == ''){
            campoError($(this).attr('id'));
            valido=false;
        }
    });    

    // $('.tag-expresion-comun').each(function(){
    //     if($(this).val() == ''){
    //         campoError($(this).attr('id'));
    //         valido=false;
    //     }
    // });  

    if(valido){

        var formData = new FormData($("#tagsComunesForm")[0]);

        $.ajax({
            url: '<?=$url_crud;?>?guardar_tags_comunes=si&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>',  
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            //una vez finalizado correctamente
            success: function(data){
                $("#tagsComunesForm")[0].reset();
                alertify.success("Información guardada con &eacute;xito");
                $("#tagsComunesModal").modal("hide");
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al guardar la información" + data.responseText);
                }else{
                    alertify.error("Hubo un error al guardar la información");
                }
            }
        })
    }
}

function addCampotagsComunes(oper){
    let num = $("#totalCamposComunes").val();

    let row = `
        <tr id="tags_comun_${num}">
            <input type="hidden" name="cTag_${oper}_${num}" id="cTag_${num}">
            <td>
                <input type="text" name="mTag_${oper}_${num}" id="mTag_${num}" class="form-control input-sm tag-comun-disp">
            </td>
            <td>
                <input type="text" name="tTag_${oper}_${num}" id="tTag_${num}" class="form-control input-sm tag-expresion-comun">
            </td>
            <td>
                <button type="button" id="btnEliminarEsteTag_${num}" onclick="eliminarTagComun('${oper}', ${num})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    `;

    $("#tagsComunesTable tbody").append(row);

    if(oper == 'add'){
        $('#tTag_'+num).selectize({
            plugins: ['remove_button'],
            persist: false,
            createOnBlur: true,
            create: true,                           
        });
    }

    num++;
    $("#totalCamposComunes").val(num);
}

function eliminarTagComun(oper, id){
    if(oper == 'add'){
        $("#tags_comun_"+id).remove();
    }else{
        let rowId = $("#cTag_"+id).val();
    
        // Aqui elimina el filtro en la bd
        alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
            if(e){
                $.ajax({
                    url: '<?=$url_crud;?>?borrar_campo_tag_comun=si',  
                    type: 'POST',
                    dataType: 'json',
                    data: { rowId:rowId },
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    //una vez finalizado correctamente
                    success: function(data){
                        $("#tags_comun_"+id).remove();
                        alertify.success("campo eliminado");
                    }
                });     
            }
        });
    }
}

function agregarTagAlItem(tagId, nuevoTag){
    
    let campoItem = $("#campo_item_"+tagId).val();
    let seccionAutores = $("#autorespuestaId").val();
    let seccion = $("#seccionBotId").val();
    let valido = true;

    if(campoItem == ''){
        $("#campo_item_"+tagId).focus();
        valido = false;

        alertify.error("El campo \"Agregar a esta opción\" debe estar seleccionado con una opcion");
    }

    if(valido){
        $.ajax({
            url: '<?=$url_crud;?>?agregarTagAlItem=true',
            type: 'POST',
            dataType: 'json',
            data: { campoItem:campoItem, tagId:tagId, nuevoTag:nuevoTag, seccion:seccionAutores },
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            //una vez finalizado correctamente
            success: function(data){

                if(data.valido){
                    $("#tag_row_"+tagId).remove();
                    alertify.success("tag agregado");
                    editarSeccion(seccion, seccionAutores);
                }else{
                    alertify.error("No se ha podido agregar este tag, valide si no esta ya agregado a la seccion");
                }
            }
        });
    }
}

function eliminarTagNoDeseado(tagId){

    // Aqui elimina el filtro en la bd
    alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
        if(e){
            $.ajax({
                url: '<?=$url_crud;?>?eliminarTagNoDeseado=true',  
                type: 'POST',
                dataType: 'json',
                data: { tagId:tagId },
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                },
                //una vez finalizado correctamente
                success: function(data){
                    $("#tag_row_"+tagId).remove();
                    alertify.success("tag eliminado");
                }
            });     
        }
    });
}

function abrirTagsGlobales(){

    obtenerTagsComunes();

    let pasoId = $("#pasoId").val();

    $("#tagsGlobalesTable tbody").html('');

    $.ajax({
        url: '<?=$url_crud;?>?obtenerTagsGlobales=true',
        type: 'POST',
        dataType: 'json',
        data: { pasoId:pasoId },
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
            $("#tagsGlobales").modal();
        },
        //una vez finalizado correctamente
        success: function(data){

            if(data.secciones && data.secciones.length > 0){
                
                data.secciones.forEach(element => {

                    let tipo = obtenerNombreTipoSeccionEIcono(element.tipo_seccion);

                    let tag = '';
                    if(element.tag_id !== null){
                        console.log("esta pasando por aca");
                        tag = element.tag;
                    }

                    let row = `
                        <tr>
                            <td style="max-width: 70%;"> ${element.nombre} - ${tipo}</td>
                            <td>
                                <div class="form-group">
                                    <input type="text" id="tag_global_${element.id}" name="tag_global_${element.id}" value="${tag}">
                                    <span>Separe los tags con comas.</span>
                                    <a href="#" class="pull-right modalDisparador" onclick="abrirDisparadorModal(<?=$_SESSION['HUESPED']?>);return false;">Disparadores de uso frecuente</a>
                                </div>
                            </td>
                        </tr>
                    `;

                    $("#tagsGlobalesTable tbody").append(row);
                    inicializarSelectizeTags('global_'+element.id);
                });
            }
        },
        error: function(data){
            console.log(data);
            alertify.error("Se presento un error al cargar los datos");
        }
    });

}

function guardarTagsGlobales(){

    var formData = new FormData($("#tagsGlobalesForm")[0]);

    $.ajax({
        url: '<?=$url_crud;?>?guardar_tags_globales=si&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>',  
        type: 'POST',
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            $("#tagsGlobalesForm")[0].reset();
            alertify.success("Información guardada con &eacute;xito");
            $("#tagsGlobales").modal("hide");
        },
        error: function(data){
            console.log(data);
            alertify.error("Hubo un error al guardar la información");
            
        }
    })

}

function obtenerNombreTipoSeccionEIcono(tipoSeccion){
    
    let nombre = '';

    switch (tipoSeccion) {
        case "1":
            nombre = 'Conversacional <i class="fa fa-comments-o"></i>';
            break;
        case "2":
            nombre = 'Captura de datos <i class="fa fa-list-ul"></i>';
            break;
        case "3":
            nombre = 'Consulta de datos <i class="fa fa-clone"></i>';
            break;
        
        case "2":
            nombre = 'Transaccional <i class="fa fa-list-ol"></i>';
            break;
        default:
            nombre = "Sin seccion";
            break;
    }

    return nombre;
}

// Funciones de la seccion Avanzado TODO: VALIDACIONES FALTAN AUN POR TERMINAR

function seccionAvanzado(contador, ejecucion, provieneFlecha = false){

    $("#guardarCondiciones").hide();
    $("#guardarValidaciones").hide();

    // Ocultamos la secciones de avanzados
    $("#avanzadoCondiciones").hide();
    $("#avanzadoValidacion").hide();

    if(ejecucion == 'accionFinal' || ejecucion == 'conversacional'){

        $("#guardarCondiciones").show();
        $("#avanzadoCondiciones").show();

        $("#contCondicionesAccionesFinales").val(0);
        $("#cuerpo_condiciones").html('');

        let campoId = 0;

        if(provieneFlecha){
            campoId = $("#campo_disparador_"+contador).val();
        }else{
            campoId = $("#campo_accion_final_"+contador).val();
        }

        $("#autorespuestaContenidoId").val(campoId);
        $("#idVariableDeAccionFinal").val(contador);

        if(ejecucion == "accionFinal"){ // Si es accion final el boton permanece desabilitado
            $("#tipoCondicion").val(1).change();
            $("#tipoCondicion").prop('disabled', 'disabled');
        }else if(ejecucion == "conversacional"){
            $("#tipoCondicion").val(0).change();
            $("#tipoCondicion").prop('disabled', false);
        }

        // Traer las condiciones
        $.ajax({
            url: '<?=$url_crud;?>?obtenerCondiciones=true',
            type: 'POST',
            dataType: 'json',
            data: { campoId:campoId },
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            success: function(data){
                
                if(data.condiciones.length > 0){

                    $("#tipoCondicion").val(1).change();

                    data.condiciones.forEach( (item, i) => {

                        // agrgeo la condicion
                        agregarCondicion('edit');
                        $("#condiciones_identificador_"+i).val(item.id);
                        $("#condiciones_operador_"+i).val(item.operador);
                        $("#condiciones_campo_"+i).val(item.campo);
                        $("#condiciones_condicion_"+i).val(item.condicion);
                        if(item.tipo_valor_comparar == 'estatico' && item.valor_a_comparar != 'DY_VACIO'){
                            $("#tipo_valor_"+i).val('estatico');
                        }else{
                            $("#tipo_valor_"+i).val('dinamico');
                            $("#tipo_valor_"+i).change();
                        }
                        $("#condiciones_valor_"+i).val(item.valor_a_comparar);
                    });
                }else{
                    if(ejecucion == "accionFinal"){ // Ejecute eso si solo es para accion final
                        $("#btnAgregarCondicion").click();
                    }
                }

            },
            error: function(data){
                console.log(data);
                alertify.error("Se presento un error al traer la informacion intenta nuevamente");
            }
        });

        $("#seccionAvanzado").modal("show");

        // plantillaCondiciones(contador);

    }

    if(ejecucion == 'transaccional'){

        $("#formValidacion")[0].reset();

        $("#avanzadoValidacion").show();
        $("#guardarValidaciones").show();

        $("#seccionAvanzado").modal("show");

        let accionId = $("#campo_"+contador).val();
        let tipoAccion = $("#accion_"+contador).val();
        let campoBdId = $("#pregun_"+contador).val();

        $("#validacionAccionId").val(accionId);
        $("#validacionAccionTipo").val(tipoAccion);
        if(tipoAccion == '3'){
            $("#validacionCampo").val(campoBdId);
        }else if(tipoAccion == '4'){
            // $("#validacionCampo").val(campoBdId);
        }
        
        $.ajax({
            url: '<?=$url_crud;?>?traerValidaciones=true&huesped=<?=$_SESSION['HUESPED']?>',
            type: 'POST',
            dataType: 'json',
            data: { accionId:accionId, tipoAccion:tipoAccion, campoBdId:campoBdId },
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            success: function(data){

                let opcionesListas = '';

                if(data.listas){
                    data.listas.forEach((item, index) => {
                        opcionesListas += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                }
                $("#campoLista").html(opcionesListas);

                // Validamos si tiene validaciones de campos
                if(data.autorespuesta){

                    let reglas = null;

                    if(data.autorespuesta.validar_campo == 1){
                        
                        $("#validacionRadio1").click().change();
                        reglas = data.autorespuesta;

                    }else{

                        $("#validacionRadio0").click().change();
                        reglas = data.pregun;

                    }

                    if(reglas){
                        $("#tipoCampo").val(reglas.tipo_campo).change();
    
                        // Insertamos los datos
                        if(reglas.tipo_campo == '1'){
                            $("#campoCantMaximaTexto").val(reglas.validacion_cantidad_caracteres_textos);
                        }
    
                        if(reglas.tipo_campo == '3'){
                            $("#campoValorMinimo").val(reglas.rango_numero_desde);
                            $("#campoValorMaximo").val(reglas.rango_numero_hasta);
                        }
    
                        if(reglas.tipo_campo == '5'){
                            $("#campoFechaMinima").val(reglas.rango_fecha_desde.replace(' 00:00:00', ''));
                            $("#campoFechaMaxima").val(reglas.rango_fecha_hasta.replace(' 00:00:00', ''));
                        }
    
                        if(reglas.tipo_campo == '6'){
                            $("#campoLista").val(reglas.lista_opcion_id).change();
                        }
                    }else{
                        $("#tipoCampo").val(1).change();
                    }

                }
            },
            error: function(data){
                console.log(data);
                alertify.error("Se presento un error al traer la informacion intenta nuevamente");
            }
        });


    }

}

function agregarCondicion(oper){

    let contador = $("#contCondicionesAccionesFinales").val();

    let row = `
        <div class="row" id="grupo_condiciones_${contador}">

            <input type="hidden" name="condiciones_accion_${contador}" id="condiciones_accion_${contador}" value="${oper}">
            <input type="hidden" name="condiciones_identificador_${contador}" id="condiciones_identificador_${contador}" value="0">

            <div class="col-md-1">
                <div class="form-group">
                    <select name="condiciones_operador_${contador}" id="condiciones_operador_${contador}" class="form-control input-sm">
                        <option value="AND">&</option>
                        <option value="OR">O</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <select name="condiciones_campo_${contador}" id="condiciones_campo_${contador}" class="form-control input-sm">
                        <option value="0">Seleccione un campo</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select name="condiciones_condicion_${contador}" id="condiciones_condicion_${contador}" class="form-control input-sm">
                        <option value="=">IGUAL A</option>
                        <option value="<>">DIFERENTE A</option>
                        <option value="<">MENOR QUE</option>
                        <option value=">">MAYOR QUE</option>
                        <option value="<=">MENOR O IGUAL QUE</option>
                        <option value=">=">MAYOR O IGUAL QUE</option>
                        <option value="dy_contiene">CONTIENE</option>
                        <option value="dy_inicie_por">INICIE POR</option>
                        <option value="dy_termine_en">TERMINE EN</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select name="tipo_valor_${contador}" id="tipo_valor_${contador}" class="form-control input-sm" onchange="cambiarTipoCampoValidacion(${contador})">
                        <option value="estatico">ESTATICO</option>
                        <option value="dinamico">DINAMICO</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" id="divCondicionesValor_${contador}">
                    <input type="text" id="condiciones_valor_${contador}" name="condiciones_valor_${contador}" placeholder="Valor a buscar" class="form-control input-sm">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button type="button" id="btnEliminarCondicion_${contador}" onclick="eliminarCondicion(${contador},'${oper}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>
    `;

    $("#cuerpo_condiciones").append(row);

    if(contador == 0){
        $("#condiciones_operador_"+contador).hide();
    }

    agregarCamposaCondiciones("condiciones_campo_"+contador);

    contador++;
    $("#contCondicionesAccionesFinales").val(contador);
}

function cambiarTipoCampoValidacion(id){

    let tipoDatoAComparar = $("#tipo_valor_"+id).val();

    let campo = '';

    if(tipoDatoAComparar == 'estatico'){

        campo = `
            <input type="text" id="condiciones_valor_${id}" name="condiciones_valor_${id}" placeholder="Valor a buscar" class="form-control input-sm">
        `;

        $("#divCondicionesValor_"+id).html(campo);

    }else if(tipoDatoAComparar == 'dinamico'){

        campo = `
            <select name="condiciones_valor_${id}" id="condiciones_valor_${id}" class="form-control input-sm">
                <option value="0">Seleccione un campo</option>
            </select>
        `;

        $("#divCondicionesValor_"+id).html(campo);

        agregarCamposaCondiciones("condiciones_valor_"+id, false);
    }

}

function agregarCamposaCondiciones(nombreCampo, agregarCamposBd = true){
    // Agrego los campos
    $("#"+nombreCampo).html('');

    opciones = '<option value="">Seleccione</option>';

    // Campos de la base
    if(agregarCamposBd){
        opciones += `<optgroup label="Campos de la base de datos">`;
        
        opciones += `<option value='_CoInMiPo__b' tipo='3'>BD.ID_BD</option>`;
        opciones += `option value='_FechaInsercion' tipo='5'>BD.FECHA CREACION</option>`;
    
        opciones += camposBd.map(element => {
            return `<option value="${element.id}">BD.${element.nombre}</option>`;        
        });
    
        opciones += `<option value='_Estado____b' tipo='_Estado____b'>BD.ESTADO_DY (tipo reintento)</option>`;
        opciones += `<option value='_ConIntUsu_b' tipo='_ConIntUsu_b'>BD.Usuario</option>`;
        opciones += `<option value='_NumeInte__b' tipo='3'>BD.Numero de intentos</option>`;
        opciones += `<option value='_UltiGest__b' tipo='MONOEF'>BD.Ultima gesti&oacute;n</option>`;
        opciones += `<option value='_GesMasImp_b' tipo='MONOEF'>BD.Gesti&oacute;n mas importante</option>`;
        opciones += `<option value='_FecUltGes_b' tipo='5'>BD.Fecha ultima gesti&oacute;n</option>`;
        opciones += `<option value='_FeGeMaIm__b' tipo='5'>BD.Fecha gesti&oacute;n mas importante</option>`;
        opciones += `<option value='_ConUltGes_b' tipo='ListaCla'>BD.Clasificaci&oacute;n ultima gesti&oacute;n</option>`;
        opciones += `<option value='_CoGesMaIm_b' tipo='ListaCla'>BD.Clasificaci&oacute;n mas importante</option>`;
        opciones += `<option value='_Activo____b' tipo='_Activo____b'>BD.Registro activo</option>`;
    
        opciones += '</optgroup>';
    }else{
        opciones = '<option value="DY_VACIO">VACIO</option>';
    }

    // Campos capturados del bot 
    opciones += `<optgroup label="Campos capturados del bot">`;
    opciones += '<option value="${DY_ANI}">${DY_ANI}</option>';
    opciones += variablesDinamicasBot.map(element => {

        let nombre = '${'+element.seccion+'.'+element.variable+'}';
        let valor = '${'+element.variable+'}';

        return `<option value="${valor}">${nombre}</option>`;        
    });
    opciones += '</optgroup>';
    
    // Campos del webservice
    opciones += `<optgroup label="Campos del webservice">`;
    opciones += variablesDinamicasWs.map(element => {

        let nombre = '${'+element.nombreService.toUpperCase()+'.'+element.variable+'}';
        let valor = '${'+element.seccion.toUpperCase()+'.'+element.variable+'}';

        return `<option value="${valor}">${nombre}</option>`;        
    });
    opciones += '</optgroup>';

    // Campos consultados por dyalogo
    opciones += `<optgroup label="Campos consultados en la bd">`;
    opciones += variablesDinamicasConsultaDy.map(element => {

        let nombre = '${'+element.nombre+'}';
        let valor = '${'+element.id+'}';

        return `<option value="${valor}">${nombre}</option>`;        
    });
    opciones += '</optgroup>';

    $("#"+nombreCampo).html(opciones);
}

function eliminarCondicion(id, oper){

    if(oper == 'add'){
        $("#grupo_condiciones_"+id).remove();
    }else{
        // Ejecutar servicio que elimine las condiciones
        let rowId = $("#condiciones_identificador_"+id).val();

        // Aqui elimina el filtro en la bd
        alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
            if(e){
                $.ajax({
                    url: '<?=$url_crud;?>?borrar_condicion=si',
                    type: 'POST',
                    dataType: 'json',
                    data: { condicionId:rowId },
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    //una vez finalizado correctamente
                    success: function(data){
                        $("#grupo_condiciones_"+id).remove();
                        alertify.success("Condicion eliminada");
                    }
                });     
            }
        });
    }
}

function guardarCondiciones(){

    let formData = new FormData($("#formConfiguracionAvanzada")[0]);
    formData.append('contCondiciones', $("#contCondicionesAccionesFinales").val());

    $.ajax({
        url: '<?=$url_crud;?>?guardarCondiciones=true',
        type: 'POST',
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){
            alertify.success("Se ha guardado la informacion correctamente");

            if(data.nombreCondicion){
                let nombreCondicion = `<i class="fa fa-comments-o"></i>&nbsp; accion condicionada ${data.nombreCondicion}`;

                let idAccion = $("#idVariableDeAccionFinal").val();
                $("#tituloAccionFinal"+idAccion).html(nombreCondicion);
            }

            $("#seccionAvanzado").modal('hide');
        },
        error: function(data){
            console.log(data);
            alertify.error("Se presento un error al traer la informacion intenta nuevamente");
        }
    });
}

function activarCondiciones(id){

    let condicion = $("#tipoCondicion").val();

    if(condicion == '0'){
        $("#grupo_codiciones").hide();
        $("#btnAgregarCondicion").hide();
    }else{
        $("#grupo_codiciones").show();
        $("#btnAgregarCondicion").show();
    }
}

// TODO: VALIDACIONES FALTAN AUN POR TERMINAR
function guardarValidaciones(){
    let formData = new FormData($("#formValidacion")[0]);

    $.ajax({
        url: '<?=$url_crud;?>?guardarValidaciones=true',
        type: 'POST',
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){
            alertify.success("Se ha guardado la informacion correctamente");

            $("#seccionAvanzado").modal('hide');
        },
        error: function(data){
            console.log(data);
            alertify.error("Se presento un error al guardar la informacion intenta nuevamente");
        }
    });
}

// Funciones de cambio de seccion
function cambiarSeccion(rowActual){

    let accion = $("#accion_"+rowActual).val();

    if(accion == '2_1'){

        let seccionAutores = $("#bot_seccion_"+rowActual).val();

        $.ajax({
            url: '<?=$url_crud;?>?obtenerIdSeccion=true',
            type: 'POST',
            dataType: 'json',
            data: { seccionAutores:seccionAutores },
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            success: function(data){

                if(data.valido){
                    $("#modalConfiguracionSeccion").modal("hide");
                    setTimeout(() => {
                        editarSeccion(data.seccionId, seccionAutores);
                    }, 900);
                }else{
                    alertify.error("Se presento un error al traer el identificador de la seccion");   
                }
            },
            error: function(data){
                console.log(data);
                alertify.error("Se presento un error al tratar de cambiar la seccion");
            }
        });
    }else{
        alertify.error("La accion debe ser \"transferir a otra sección del bot\" para cambiar de sección");
    }

}

// Funciones de edicion de listas

// Funciones de flechas

function validarConexionesFlecha(from, to){

    $.ajax({
        url: '<?=$url_crud;?>?validarConexionesFlecha=true',
        type: 'POST',
        dataType: 'json',
        data: {from:from, to:to},
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){

            if(!data.valido){
                if(data.sentido1.generado_por_sistema == 1){
                    alertify.warning(`Solo se puede modificar la flecha desde la seccion <b>${data.dataFrom.nombre}</b>`);
                }else{
                    mostrarContenidoFlecha(from , to, 'show');
                }
            }

            if(data.valido && !data.ambosSentidos){

                if(data.sentido1.generado_por_sistema == 1){
                    alertify.warning(`Solo se puede modificar la flecha desde la seccion <b>${data.dataFrom.nombre}</b>`);
                }else{
                    mostrarContenidoFlecha(from , to, 'show');
                }

            }

            if(data.valido && data.ambosSentidos){
                // Abrimos la modal
                $("#seleccionaFlecha").modal("show");

                $("#sentido1").html('');
                $("#sentido2").html('');
                $("#abrirConfig1").prop('disabled', false);
                $("#abrirConfig2").prop('disabled', false);
                $("#abrirConfig1").attr("onclick","");
                $("#abrirConfig1").attr("onclick","");

                let textoSentido1 = `Conexion de la flecha desde la seccion <b>${data.dataFrom.nombre}</b> hasta la seccion <b>${data.dataTo.nombre}</b>`;
                if(data.sentido1.generado_por_sistema == 1){
                    textoSentido1 += ` solo puede ser modificado desde la seccion <b>${data.dataFrom.nombre}</b>`;
                    $("#abrirConfig1").prop('disabled', true);
                }

                $("#sentido1").html(textoSentido1);
                $("#abrirConfig1").attr("onclick",`mostrarContenidoFlecha(${from} , ${to}, 'show')`);

                let textoSentido2 = `Conexion de la flecha desde la seccion <b>${data.dataTo.nombre}</b> hasta la seccion <b>${data.dataFrom.nombre}</b>`;
                if(data.sentido2.generado_por_sistema == 1){
                    textoSentido2 += ` solo puede ser modificado desde la seccion <b>${data.dataTo.nombre}</b>`;
                    $("#abrirConfig2").prop('disabled', true);
                }
                $("#sentido2").html(textoSentido2);
                $("#abrirConfig2").attr("onclick",`mostrarContenidoFlecha(${to} , ${from}, 'show')`);
            }

        },
        error: function(data){
            alertify.error("Se presento un error al traer la informacion de los disparadores, por favor recarga la pagina");
        }
    });

}

function mostrarContenidoFlecha(from, to, tipoEjecucion){

    $("#seleccionaFlecha").modal('hide');

    $("#disparadoresTable tbody").html('');
    $("#disparadoresTransaccionales").html('');

    inicializarSelectizeTags('local');
    $("#tag_local")[0].selectize.destroy();
    $("#accionesBotConversacional").html('');
    $("#accionesBotTransaccional").html('');
    $("#otrosDisparadores").html('');

    $("#totalCamposDisparadores").val(0);

    let titulo = '';

    if(tipoEjecucion == 'show'){

        titulo = 'Lista de disparadores y respuestas';
        $("#agregarDisparador").show();
        $("#tipoEjecucion").val('insert_update');

    }else if(tipoEjecucion == 'edit'){

        titulo = 'Selecciona los disparadores que cambiaran de destino';
        $("#agregarDisparador").hide();
        $("#tipoEjecucion").val('change_to');

    }else if(tipoEjecucion == 'delete'){

        titulo = 'Selecciona los disparadores que se eliminaran';
        $("#agregarDisparador").hide();
        $("#tipoEjecucion").val('delete');

    }

    $("#tituloFormularioFlechas").html(titulo);
    $("#tituloFormularioFlechas").show();

    obtenerTagsComunes();

    // Obtener variables
    obtenerVariables(from, true);

    let aux = $("#aux").val();

    // traer los disparadores especificos de cada seccion
    $.ajax({
        url: '<?=$url_crud;?>?traerDisparadoresDeFlecha=true',
        type: 'POST',
        dataType: 'json',
        data: {from:from, to:to, tipoEjecucion:tipoEjecucion, aux : aux},
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        success: function(data){

            if(data.valido){
                $("#flechaFromId").val(data.from);
                $("#flechaToId").val(data.to);
                $("#tipoSeccionFrom").val(data.tipoSeccionFrom);
                $("#tipoSeccionTo").val(data.tipoSeccionTo);
    
                inicializarTinymce();

                $("#agregarDisparador").show();
                if(data.tipoSeccionFrom == 'conversacional'){    
                    $("#agregarDisparador").hide();
                }
    
                if(data.disparadores){

                    if(data.disparadores.length > 0){
                        data.disparadores.forEach((item, index) => {
                            
                            agregarFlechaDisparadores('edit', tipoEjecucion);
                            
                            $("#campo_disparador_"+index).val(item.id);
        
                            if(data.tipoSeccionFrom == 'conversacional'){
        
                                $("#tag_disparador_"+index)[0].selectize.destroy();
                                $("#tag_disparador_"+index).val(item.pregunta);
                                inicializarSelectizeTags("disparador_"+index);
        
                            }
        
                            if(data.tipoSeccionFrom == 'transaccional'){
        
                                let nombreAccionFinal = 'Accion final condicionada '+item.orden;
        
                                if(item.pregunta == 'DY_ACCION_DEFECTO'){
                                    nombreAccionFinal = 'Acción cuando ninguna de las condiciones de las acciones anteriores se cumplió';
                                    $("#btnEliminarDisparador_"+index).hide();
                                    $("#btnAdvancedEditFieldAccionFinal_"+index).hide();
                                    $("#formDeleteDisparador_"+index).hide();
                                }
                                $("#tituloAccionFinal"+index).html(nombreAccionFinal);
                            }
        
                            $("#rpta_disparador_"+index).val(item.respuesta);
                            tinymce.get("rpta_disparador_"+index).setContent(item.respuesta);
                        });
                    }else{
                        $("#agregarDisparador").click();
                    }
    
                }

                if(data.otrosDisparadores){
                    if(data.otrosDisparadores.length > 0){
                        data.otrosDisparadores.forEach( (item, index) => {

                            $("#otrosDisparadores").append(`<input type="text" id="tag_otroDisparador${index}" name="tag_otroDisparador${index}" value="${item.pregunta}">`);
                            inicializarSelectizeTags("otroDisparador"+index);

                        });
                    }
                }

                $("#flechaModal").modal("show");
            }else{
                alertify.error("No puedes cambiar la ubicacion de la flecha del paso de donde sale con otro tipo de paso distinto");
                let paso = <?=$_GET['id_paso']?>;
                recargarFlujograma(paso);
            }

        },
        error: function(data){
            console.log(data);
            alertify.error("Se presento un error al traer la informacion de los disparadores, por favor recarga la pagina");
        }
    });
}

function guardarDisparadores(){

    tinymce.triggerSave();

    let tipoEjecucion = $("#tipoEjecucion").val();
    let ruta = '<?=$url_crud;?>?guardarDisparadoresFlecha=true';

    if(tipoEjecucion == 'insert_update'){
        ruta = '<?=$url_crud;?>?guardarDisparadoresFlecha=true';
    }else if(tipoEjecucion == 'change_to'){
        ruta = '<?=$url_crud;?>?cambiarDestinoFlecha=true';
    }else if(tipoEjecucion == 'delete'){
        ruta = '<?=$url_crud;?>?borrarFlecha=true';
    }

    let seccionFrom = $("#tipoSeccionFrom").val();

    let valido = true;

    // Si la seccion de donde sale es conversacional valido que haya por lo menos un tag
    if(seccionFrom == 'conversacional'){
        $('.tag-disparador').each(function(){
            let tagId = $(this).attr('id');
            if($("#"+tagId).val() == ''){
                alertify.error('El campo disparador no puede estar vacio');
                valido=false;
            }
        });
    }

    if(valido){
        let formData = new FormData($("#flechasForm")[0]);
    
        $.ajax({
            url: ruta+'&id_paso=<?=$_GET['id_paso']?>',
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            success: function(data){
                alertify.success("Se ha guardado la informacion correctamente");
    
                $("#flechaModal").modal('hide');
                let paso = <?=$_GET['id_paso']?>;
                recargarFlujograma(paso);
            },
            error: function(data){
                console.log(data);
                alertify.error("Se presento un error al guardar la informacion, por favor recargue la pagine e intenta nuevamente");
            }
        });
    }

}

function eliminarDisaparador(id){

    let oper = $("#oper_disparador_"+id).val();

    if(oper == 'add'){
        $("#flechaDisparador"+id).remove();
    }else{
        // Ejecutar servicio que elimine las condiciones
        let rowId = $("#campo_disparador_"+id).val();
        let tipoSeccion = $("#tipoSeccionFrom").val();
        let from = $("#flechaFromId").val();

        // Aqui elimina el filtro en la bd
        alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
            if(e){
                $.ajax({
                    url: '<?=$url_crud;?>?borrarDisparadorFlecha=si',
                    type: 'POST',
                    dataType: 'json',
                    data: { id:rowId, tipoSeccion: tipoSeccion, from: from },
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    //una vez finalizado correctamente
                    success: function(data){
                        $("#flechaDisparador"+id).remove();
                        alertify.success("Disparador eliminado");
                    }
                });     
            }
        });
    }

}

function agregarNuevoDisparador(){
    agregarFlechaDisparadores('add');
}

function agregarFlechaDisparadores(oper, tipoEjecucion = 'show'){

    let contador = $("#totalCamposDisparadores").val();
    let tipoSeccionFrom = $("#tipoSeccionFrom").val();

    if(tipoSeccionFrom == 'conversacional'){

        let row = `
            <tr id="flechaDisparador${contador}">
    
                <input type="hidden" id="campo_disparador_${contador}" name="campo_disparador_${contador}" value="0">
                <input type="hidden" id="oper_disparador_${contador}" name="oper_disparador_${contador}" value="${oper}">
    
                <td>
                    <label for="" class="labelDisparador">Disparador (Texto que escribe el usuario)</label>
                    <input type="text" id="tag_disparador_${contador}" name="tag_disparador_${contador}" class="form-control input-sm tags-input tag-disparador" num="${contador}">
                    <span class="info_disparador" class="help-block">Separe los tags con comas.</span>
                    <a id="myLink_${contador}" href="#" class="pull-right modalDisparador" onclick="abrirDisparadorModal(<?=$_SESSION['HUESPED']?>);return false;">Disparadores de uso frecuente</a>
                </td>
    
                <td style="display:none">
                    <label for="">Respuestas (Texto que escribe el bot cuando se ejecuta la accion)</label>
                    <textarea class="tinyMCE" id="rpta_disparador_${contador}" name="dyTr_rpta_disparador_${contador}" cols="60" rows="1"></textarea>
                </td>
    
                <td>
                    <!-- <button type="button" id="btnEliminarDisparador_${contador}" onclick="eliminarDisaparador(${contador})" class="btn btn-danger btn-sm" style="margin-top: 20px;"><i class="fa fa-trash"></i></button> -->
                    <label id="formDeleteDisparador${contador}" style="display:none">
                        ELIMINAR &nbsp;
                        <input type="checkbox" id="delete_disparador_${contador}" name="delete_disparador_${contador}" class="minimal">
                    </label>
                    <label id="formMoverDisparador${contador}" style="display:none">
                        MOVER &nbsp;
                        <input type="checkbox" id="mover_disparador_${contador}" name="mover_disparador_${contador}" class="minimal">
                    </label>
                </td>
    
            </tr>
        `;
    
        $("#disparadoresTable tbody").append(row);
    
        inicializarSelectizeTags('disparador_'+contador);

    }else if(tipoSeccionFrom == 'transaccional'){

        let nombre = "Accion cuando";

        let row = `
            <div class="panel box box-success" id="flechaDisparador${contador}">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoAccionFinal${contador}" id="tituloAccionFinal${contador}">
                            <i class="fa fa-comments-o"></i>&nbsp;${nombre}
                        </a>
                    </h4>

                    <div class="box-tools pull-right" data-toggle="tooltip" title="Avanzado">
                        <button type="button" id="btnAdvancedEditFieldAccionFinal_${contador}" class="btn btn-warning btn-sm" onclick="seccionAvanzado(${contador}, 'accionFinal', true)"><i class="fa fa-cog"></i></button>

                        <!-- <button type="button" id="btnEliminarDisparador_${contador}" onclick="eliminarDisaparador(${contador})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button> -->
                        
                        <label id="formDeleteDisparador${contador}" style="display:none">
                            ELIMINAR &nbsp;
                            <input type="checkbox" id="delete_disparador_${contador}" name="delete_disparador_${contador}" class="minimal">
                        </label>

                        <label id="formMoverDisparador${contador}" style="display:none">
                            MOVER &nbsp;
                            <input type="checkbox" id="mover_disparador_${contador}" name="mover_disparador_${contador}" class="minimal">
                        </label>
                    </div>
                </div>

                <div id="cuerpoAccionFinal${contador}" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="row">
                            
                            <input type="hidden" id="campo_disparador_${contador}" name="campo_disparador_${contador}" value="0">
                            <input type="hidden" id="oper_disparador_${contador}" name="oper_disparador_${contador}" value="${oper}">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Respuestas (Texto que escribe el bot cuando se ejecuta la accion)</label>
                                    <textarea class="tinyMCE" id="rpta_disparador_${contador}" name="dyTr_rpta_disparador_${contador}" cols="60" rows="1"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $("#disparadoresTransaccionales").append(row);
        
        if(oper == 'add'){
            let autorespuesta = $("#flechaFromId").val();
            let destino = $("#flechaToId").val();
            crearAccionFinal(contador, autorespuesta, true, destino);
        }
    }

    tinymce.execCommand('mceRemoveEditor', false, 'rpta_disparador_'+contador);
    tinymce.execCommand('mceAddEditor', false, 'rpta_disparador_'+contador);

    if(tipoEjecucion == 'delete'){
        $("#btnEliminarDisparador_"+contador).hide();
        $("#btnAdvancedEditFieldAccionFinal_"+contador).hide();
        $("#formDeleteDisparador"+contador).show();
        
        $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-red'
        });
    }

    if(tipoEjecucion == 'edit'){
        $("#btnEliminarDisparador_"+contador).hide();
        $("#btnAdvancedEditFieldAccionFinal_"+contador).hide();
        $("#formMoverDisparador"+contador).show();
        
        $('input[type="checkbox"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue'
        });
    }

    contador++;
    $("#totalCamposDisparadores").val(contador);

}

function cambiarConexionToFlecha(from, to, newPort, link, fromPort, toPort){
    mostrarContenidoFlecha(from, to, 'edit');
    $("#aux").val(newPort);
    $("#aux2").val(link);
    $("#puertosFlecha").val(fromPort+'_'+toPort);
}

function removerFlecha(from, to){
    // mostrarContenidoFlecha(from, to, 'delete');
    // load();

    // Muestro el mensaje para borrar el contienido de la flecha
    
    alertify.confirm("Eliminar flecha", function (e) {
        if(e){

            $.ajax({
                url: '<?=$url_crud;?>?borrarFlecha=si',  
                type: 'POST',
                dataType: 'json',
                data: { flechaFromId: from, flechaToId: to },
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                },
                //una vez finalizado correctamente
                success: function(data){
                    if(data.estado == 'ok'){
                        alertify.success(data.mensaje);
                    }else{
                        alertify.error(data.mensajeError);
                    }
                }
            });  

        }else{
            let paso = <?=$_GET['id_paso']?>;

            recargarFlujograma(paso);
        }
    });
}

function abrirPasoExterno(tipoPasoBot, nombrePaso, key){

    let titulo = '';
    let tipoPaso = 0;
    let categoria = '';

    if(tipoPasoBot == 'bot'){
        titulo = "Abrir la configuracion del bot " + nombrePaso;
        tipoPaso = 12;
        categoria = 'ivrTexto';
    }

    if(tipoPasoBot == 'campana'){
        titulo = "Abrir la configuracion de la campana entrante " + nombrePaso;
        tipoPaso = 1;
        categoria = 'EnPhone';
    }

    alertify.confirm(titulo, function (e) {
        if(e){

            guardarFlujoBot('pasoExterno');

            // Me toca traer el id verdadero del paso

            $.ajax({
                url: '<?=$url_crud;?>?obtenerIdPaso=si',  
                type: 'POST',
                dataType: 'json',
                data: { tipoPasoBot: tipoPasoBot, key: key },
                beforeSend : function(){
                    // $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    // $.unblockUI();
                },
                //una vez finalizado correctamente
                success: function(data){
                    if(data.paso && data.paso > 0){
                        $("#editarDatos").modal('hide');
                        abrirPasoAutomatico(tipoPaso, data.paso, categoria);   
                    }else{
                        alertify.error('Se presento un error y no se pudo obtener el identificador del paso destino');
                    }
                }
            });  

        }
    });
}

// Busco los campos de ese g especifico
function cambioBdConsultaDy(noCampo){
    let id = $("#bdConsultaDy_"+noCampo).val();

    $.ajax({
        url: '<?=$url_crud;?>?buscarCamposBd=si',  
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        beforeSend : function(){
            // $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            // $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            if(data.campos && data.campos.length > 0){
                let opciones = '';
                data.campos.forEach(element => {
                    opciones += `<option value="${element.id}">${element.nombre}</option>`;        
                });

                $(".camposBd-condicion").html('<option value="">Seleccione</option>'+opciones);
                $("#camposConsultaDy_"+noCampo).html(opciones);

                // Si hay valores los agrego
                if($("#bdConsultaDy_"+noCampo).attr('data-variables') !== undefined){
                    
                    // Agregamos las variables
                    let variables = $("#bdConsultaDy_"+noCampo).attr('data-variables');

                    let nombreBdEliminar = 'G'+id+'_C';
                    variables = variables.replaceAll('G'+id+'_ConsInte__b', '_ConsInte__b');
                    variables = variables.replaceAll(nombreBdEliminar, '');
                    
                    let arrVariables = variables.split(',');
                    $("#camposConsultaDy_"+noCampo).val(arrVariables).trigger('change');
                    
                    $("#bdConsultaDy_"+noCampo).removeAttr('data-variables');

                    // Agregamos las condiciones
                    if($("#bdConsultaDy_"+noCampo).attr('data-condiciones') !== undefined){
                        let strCondiciones = $("#bdConsultaDy_"+noCampo).attr('data-condiciones');
                        strCondiciones = strCondiciones.replaceAll(' AND', ',AND');
                        strCondiciones = strCondiciones.replaceAll(' OR', ',OR');
                        strCondiciones = 'AND '+strCondiciones; // Agrego por defecto un AND para que cada condicion tenga la misma estructura

                        let arrCondiciones = strCondiciones.split(',');

                        for (let i = 0; i < arrCondiciones.length; i++) {
                            const element = arrCondiciones[i];

                            // Convierto en array la condicion
                            arrCondicion = element.split(' ');

                            if(i > 0){
                                agregarCondicionConsultaDy('add', noCampo);
                            }
                            
                            $("#condConsultaDy_operador_"+noCampo+"_"+i).val(arrCondicion[0]);

                            let campoCondicion = arrCondicion[1].replace(nombreBdEliminar, '');
                            $("#condConsultaDy_campo_"+noCampo+"_"+i).attr('data-id', campoCondicion);
                            $("#condConsultaDy_campo_"+noCampo+"_"+i).val(campoCondicion);

                            let operadorCondicional = arrCondicion[2];
                            let campo2 = arrCondicion[3].replaceAll('\'', '');
                            
                            if(operadorCondicional == 'LIKE'){
                                if(campo2.startsWith('%') && campo2.endsWith('%')){
                                    operadorCondicional = 'dy_contiene';
                                }else if(campo2.startsWith('%')){
                                    operadorCondicional = 'dy_termine_en';
                                }else if(campo2.endsWith('%')){
                                    operadorCondicional = 'dy_inicie_por';
                                }                                
                            }
                            
                            $("#condConsultaDy_condicion_"+noCampo+"_"+i).val(operadorCondicional);

                            campo2 = campo2.replaceAll('%', '');

                            if(campo2.startsWith('${')){                                
                                $("#condConsultaDy_tipoValor_"+noCampo+"_"+i).val('dinamico').change();
                            }else{
                                $("#condConsultaDy_tipoValor_"+noCampo+"_"+i).val('estatico').change();
                            }
                            $("#condConsultaDy_valor_"+noCampo+"_"+i).val(campo2);
                        }

                        $("#bdConsultaDy_"+noCampo).removeAttr('data-condiciones');
                    }
                }
            }
        }
    });
}

function agregarCondicionConsultaDy(oper, id){

    let contador = $("#cantConsultaDyCondiciones_"+id).val();

    let row = `
        <div class="row" id="grupCondicionesConsultaDy_${id}_${contador}">

            <input type="hidden" name="condConsultaDy_accion_${id}_${contador}" id="condConsultaDy_accion_${id}_${contador}" value="${oper}">
            <input type="hidden" name="condConsultaDy_identificador_${id}_${contador}" id="condConsultaDy_identificador_${id}_${contador}" value="0">

            <div class="col-md-1">
                <div class="form-group">
                    <select name="condConsultaDy_operador_${id}_${contador}" id="condConsultaDy_operador_${id}_${contador}" class="form-control input-sm">
                        <option value="AND">&</option>
                        <option value="OR">O</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <select name="condConsultaDy_campo_${id}_${contador}" id="condConsultaDy_campo_${id}_${contador}" class="form-control input-sm camposBd-condicion">
                        <option value="0">Seleccione un campo</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select name="dyTr_condConsultaDy_condicion_${id}_${contador}" id="condConsultaDy_condicion_${id}_${contador}" class="form-control input-sm">
                        <option value="=">IGUAL A</option>
                        <option value="<>">DIFERENTE A</option>
                        <option value="<">MENOR QUE</option>
                        <option value=">">MAYOR QUE</option>
                        <option value="<=">MENOR O IGUAL QUE</option>
                        <option value=">=">MAYOR O IGUAL QUE</option>
                        <option value="dy_contiene">CONTIENE</option>
                        <option value="dy_inicie_por">INICIE POR</option>
                        <option value="dy_termine_en">TERMINE EN</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <select name="condConsultaDy_tipoValor_${id}_${contador}" id="condConsultaDy_tipoValor_${id}_${contador}" class="form-control input-sm" onchange="cambiarTipoCampoConsultaDy(${contador}, ${id})">
                        <option value="estatico">ESTATICO</option>
                        <option value="dinamico">DINAMICO</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" id="divCondicionesValor_${id}_${contador}">
                    <input type="text" id="condConsultaDy_valor_${id}_${contador}" name="condConsultaDy_valor_${id}_${contador}" placeholder="Valor a buscar" class="form-control input-sm">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button type="button" id="btnEliminarCondicionConsultaDy${id}_${contador}" onclick="eliminarCondicionConsultaDy(${id},${contador},'${oper}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>
    `;

    $("#condicionesConsultaDy_"+id).append(row);

    if(contador == 0){
        $("#condConsultaDy_operador_"+id+"_"+contador).hide();
        $("#btnEliminarCondicionConsultaDy"+id+"_"+contador).hide();
    }

    agregarCamposBdConsultaDy("condConsultaDy_campo_"+id+"_"+contador, id);
    

    contador++;
    $("#cantConsultaDyCondiciones_"+id).val(contador);
}

function agregarCamposBdConsultaDy(nombreCampo, noCampo){

    let id = $("#bdConsultaDy_"+noCampo).val();

    $.ajax({
        url: '<?=$url_crud;?>?buscarCamposBd=si',  
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        beforeSend : function(){
            // $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            // $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            if(data.campos && data.campos.length > 0){
                let opciones = '<option value="">Seleccione</option>';
                data.campos.forEach(element => {
                    opciones += `<option value="${element.id}">${element.nombre}</option>`;        
                });

                $("#"+nombreCampo).html(opciones);

                if($("#"+nombreCampo).attr('data-id') !== undefined){
                    let campoId = $("#"+nombreCampo).attr('data-id');
                    $("#"+nombreCampo).val(campoId);
                }
            }
        }
    });
}

function cambiarTipoCampoConsultaDy(id, sId){
    let tipoDatoAComparar = $("#condConsultaDy_tipoValor_"+sId+"_"+id).val();

    let campo = '';

    if(tipoDatoAComparar == 'estatico'){

        campo = `
            <input type="text" id="condConsultaDy_valor_${sId}_${id}" name="condConsultaDy_valor_${sId}_${id}" placeholder="Valor a buscar" class="form-control input-sm">
        `;

        $("#divCondicionesValor_"+sId+"_"+id).html(campo);

    }else if(tipoDatoAComparar == 'dinamico'){

        campo = `
            <select name="condConsultaDy_valor_${sId}_${id}" id="condConsultaDy_valor_${sId}_${id}" class="form-control input-sm">
                <option value="0">Seleccione un campo</option>
            </select>
        `;

        $("#divCondicionesValor_"+sId+"_"+id).html(campo);

        agregarCamposBotConsultaDy("condConsultaDy_valor_"+sId+"_"+id);
    }
}

function agregarCamposBotConsultaDy(nombreCampo){
    let opciones = '';
    // Campos capturados del bot 
    opciones += `<optgroup label="Campos capturados del bot">`;
    opciones += '<option value="${DY_ANI}">${DY_ANI}</option>';
    opciones += variablesDinamicasBot.map(element => {

        let nombre = '${'+element.seccion+'.'+element.variable+'}';
        let valor = '${'+element.variable+'}';

        return `<option value="${valor}">${nombre}</option>`;        
    });
    opciones += '</optgroup>';
    
    // Campos del webservice
    opciones += `<optgroup label="Campos del webservice">`;
    opciones += variablesDinamicasWs.map(element => {

        let nombre = '${'+element.nombreService.toUpperCase()+'.'+element.variable+'}';
        let valor = '${'+element.seccion.toUpperCase()+'.'+element.variable+'}';

        return `<option value="${valor}">${nombre}</option>`;        
    });
    opciones += '</optgroup>';

    // Campos consultados por dyalogo
    opciones += `<optgroup label="Campos consultados en la bd">`;
    opciones += variablesDinamicasConsultaDy.map(element => {

        let nombre = '${'+element.nombre+'}';
        let valor = '${'+element.id+'}';

        return `<option value="${valor}">${nombre}</option>`;        
    });
    opciones += '</optgroup>';

    $("#"+nombreCampo).html(opciones);
}

function eliminarRowConsultaDy(oper, id){

    if(oper == 'add'){
        $("#row_add_"+id).remove();
    }

}

function eliminarCondicionConsultaDy(id, cont, oper){
    $("#grupCondicionesConsultaDy_"+id+"_"+cont).remove();
}

function obtenerPreguntasBotNoEntrego(ejecutor){
    
    $("#consultaPreguntasModal").modal();
    $("#preguntasBotNoEntregaTable tbody").html('');

    $("#pn_export_excel_paso_ejecutor").val(ejecutor);

    let seccion = $("#autorespuestaId").val();
    let pasoId = $("#pasoId").val();

    $.ajax({
        url: '<?=$url_crud;?>?traerPreguntasBotNoResponde=si&id_paso=<?=$_GET['id_paso']?>&huesped=<?=$_SESSION['HUESPED']?>',  
        type: 'POST',
        dataType: 'json',
        data: {
            seccion:seccion, ejecutor: ejecutor, pasoId: pasoId
        },
        beforeSend : function(){
            $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
        },
        complete : function(){
            $.unblockUI();
        },
        //una vez finalizado correctamente
        success: function(data){
            if(data){

                let respuestaNombre = '';
                
                if(ejecutor == 'bot'){
                    $("#consultaPreguntasNombreSeccion").show();
                }else{
                    $("#consultaPreguntasNombreSeccion").hide();
                }

                if(data.campos.length > 0){
                    $.each(data.campos, function(i, item){

                        if(ejecutor == 'bot'){
                            respuestaNombre = `<td>${item.nombreSeccion}</td>`;
                        }

                        // Buscamos la lista de items para esa seccion en especifico
                        let lista = data.itemsPorSecciones.find(element => element.id == item.id_base_autorespuesta);

                        // Creamos la estruectura
                        let listaItems = '<option value="">Seleccione</option>';
                        if(lista){
                            if(lista.items.length > 0){
                                $.each(lista.items, function(i, item){
                                    listaItems += `<option value="${item.id}">${item.pregunta}</option>`;
                                });
                            }
                        }

                        let row = `
                            <tr id="tag_row_${item.id}">
                                <td>${item.pregunta}</td>
                                ${respuestaNombre}
                                <td>${item.fecha_hora}</td>
                                <td>${item.contador}</td>
                                <td>
                                    <select id="campo_item_${item.id}" class="form-control input-sm" style="margin-bottom:5px;">
                                        ${listaItems}
                                    </select>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="agregarTagAlItem(${item.id}, '${item.pregunta}')">Agregar</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarTagNoDeseado(${item.id})"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                        $("#preguntasBotNoEntregaTable tbody").append(row);
                    });
                }
                
            }
        }
    });
}

function exportarListadoPn(){

    let seccion = $("#autorespuestaId").val();
    let pasoId = $("#pasoId").val();

    $("#pn_export_excel_paso_id").val(pasoId);
    $("#pn_export_excel_paso_seccion").val(seccion);

    $('#export-excel-preguntas-no-entrega').submit();
}

function cambiarAccionInicial(){
    
    let valor = $("#accion_inicial_accion").val();

    if(valor == 0){
        $("#accion_inicial_seccion").attr("disabled", true);
    }else if(valor == 1){
        $("#accion_inicial_seccion").attr("disabled", false);
        $("#accion_inicial_seccion").html(opcionesCampana);
    }else if(valor == 2){
        $("#accion_inicial_seccion").attr("disabled", false);
        $("#accion_inicial_seccion").html(opcionesSecciones);
    }

}

function limpiarEspacios(nombre){
    let campo =   $("#"+nombre);
    let texto =   campo.val().replace(/ /g, "_");
    campo.val(texto);
}

function usarCampoGestion(tipo, campo, ejecutor){

    $("#pregun_"+campo).hide();
    $("#nombre_variable_"+campo).hide();
    $("#pregunGestionExistente_"+campo).hide();

    if(tipo == 1){
        $("#nombre_variable_"+campo).show();
        $("#labelAccionCaptura"+campo).html("Ingresa un nombre(Este nombre identificara al dato ingresado en otras secciones)");

        // Validamos si ya tiene texto insertado
        if($("#nombre_variable_"+campo).val() == ''){
            let titulo = '';
            if(ejecutor == 'captura'){
                titulo = $("#pregunta_bot_"+campo).val();
            }else {
                titulo = $("#tag_"+campo).val();
            }
            $('#nombre_variable_'+campo).val(titulo.replace(/<[^>]*>?/gm, '').replaceAll(' ', '_').substr(0, 30));
        }

    }else{
        $("#pregunGestionExistente_"+campo).show();
        $("#labelAccionCaptura"+campo).html("Selecciona el campo");
    }

}

function cambiarTipoGuardadoConversacional(id){

    let valor = $("#guardar_respuesta_"+id).val();

    $("#pregunConver_"+id).hide();
    $("#opcionesGuardarGestion"+id).hide();
    $("#labelGuardarRespuestaConversacional"+id).show();
    $('#nombre_variable_'+id).hide();
    $('#pregunGestionExistente_'+id).hide();
    

    if(valor == 0){
        $("#labelGuardarRespuestaConversacional"+id).hide();
    }

    if(valor == 1 || valor == 3){
        $("#pregunConver_"+id).show();
    }
    if(valor == 2){
        $("#opcionesGuardarGestion"+id).show();
        $("#usarCampoGestionPropio"+id).click();
    }

}

function cambioTipoMensaje(id, tipo){

    $("#seccionMedia"+id).show();

    let fileInput = document.getElementById('archivoMedia'+id);
    fileInput.value = '';

    let helpBlock = '';

    switch (tipo) {
        case 'TEXT':
            $("#seccionMedia"+id).hide();
            break;

        case 'IMAGE':
            helpBlock = 'Tipos de contenido admitidos: image/jpeg, image/png; Tamaño máximo: 5 MB';
            break;

        case 'VIDEO':
            helpBlock = 'Tipos de contenido admitidos: video/mp4, video/3gpp. Note: Solo se admiten el códec de video H.264 y el códec de audio AAC; Tamaño máximo de archivo: 16 MB';
            break;
        
        case 'AUDIO':
            helpBlock = 'Tipos de contenido admitidos: audio/aac, audio/mp4, audio/amr, audio/mpeg, audio/ogg; codecs=opus. Nota: No se admite el tipo de audio/ogg base; Tamaño máximo de archivo: 16 MB';
            break;

        case 'DOCUMENT':
            helpBlock = 'Tipos de contenido admitidos: pdf, doc, docx, odt, xlsx, xls, txt, csv. Tamaño máximo de archivo: 20 MB';
            break;
    
        default:
            break;
    }

    $("#helpBlock"+id).html(helpBlock);
}

function fileValidation(id){

    let tipo = $("input[name='tipo_file_"+id+"']:checked").val();
    alert("tipo de adjnuto " + id +tipo);

    let fileInput = document.getElementById('archivoMedia'+id);
    let filePath = fileInput.value;

    let fileSize = fileInput.files[0].size;

    let allowedExtensions = '';

    let tamanoPermitido = 0;

    // Allowing file type
    switch (tipo) {
        case 'IMAGE':
            allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            tamanoPermitido = 5000000;
            break;

        case 'VIDEO':
            allowedExtensions = /(\.mp4|\.3gpp)$/i;
            tamanoPermitido = 16000000;
            break;
        
        case 'AUDIO':
            allowedExtensions = /(\.aac|\.mp4|\.amr|\.mpeg|\.ogg)$/i;
            tamanoPermitido = 16000000;
            break;

        case 'DOCUMENT':
            allowedExtensions = /(\.doc|\.docx|\.odt|\.pdf|\.xlsx|\.xls|\.txt|\.csv)$/i;
            tamanoPermitido = 20000000;
            break;
    
        default:
            break;
    }

    if (!allowedExtensions.exec(filePath)) {
        alert('Tipo de archivo invalido');
        fileInput.value = '';
        return false;
    }

    if(fileSize > tamanoPermitido){
        alert('El archivo supera el tamaño máximo permitido');
        fileInput.value = '';
        return false;
    }
}

// Botones de respuesta rapida
function agregarBotonRespuesta(id, retornarId = false){

    // Validamos si ya esta el maximo
    if($("#listaBotones"+id+" tbody tr").length > 9){
        alert("no se pueden agregar mas de 10 elementos");
        return;
    }

    let uniqueId = Math.random().toString(30).substring(2);

    let cuerpo = `
    <tr id="btn_res_${id}_${uniqueId}">
        <td>
            <input type="text" id="boton_titulo${id}_${uniqueId}" name="boton_titulo${id}[]" class="form-control input-sm" maxlength="20" oninput="validarInputTitulo('${id}_${uniqueId}')">
            <div id="error_titulo${id}_${uniqueId}"></div>
        </td>
        <td>
            <input type="text" id="boton_respuesta${id}_${uniqueId}" name="boton_respuesta${id}[]" class="form-control input-sm" maxlength="255">
            <div id="error_respuesta${id}_${uniqueId}"></div>
        </td>
        <th><button type="button" class="btn btn-danger btn-sm" onclick="borrarBotonRespuesta('${id}_${uniqueId}')"><i class="fa fa-trash"></i></button></th>
    </tr>
    `;

    $("#listaBotones"+id+" tbody").append(cuerpo);

    if(retornarId){
        return uniqueId;
    }
}

function validarInputTitulo(id){

    const campo = document.querySelector("#boton_titulo"+id);
    const mensajeError = document.querySelector("#error_titulo"+id);

    const valor = campo.value;

    if(valor.length > 20){
        mensajeError.textContent = "El titulo no puede contener más de 20 caracteres";
    }else{
        mensajeError.textContent = "";
    }

}

function borrarBotonRespuesta(id){
    // Aqui elimina el filtro en la bd
    alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
        if(e){
            $("#btn_res_"+id).remove();
        }
    });
}

function mostrarBotones(cuerpo, id){
    
    let botones = JSON.parse(cuerpo);

    if(botones !== null && botones !== ''){
        botones.forEach(element => {
            let botonId = agregarBotonRespuesta(id, true);
            $("#boton_titulo"+id+"_"+botonId).val(element.titulo);
            $("#boton_respuesta"+id+"_"+botonId).val(element.postback);
        });
    }
}
</script>