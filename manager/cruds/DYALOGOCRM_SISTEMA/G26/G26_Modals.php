<!-- Modal de seccion -->
<div class="modal fade" id="modalConfiguracionSeccion">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-config">&times;</button>
                <h4 class="modal-title">Configuración de la sección del bot</h4>
            </div>
            
            <div class="modal-body">
                <div>
                    <button class="btn btn-default" id="saveSeccion" onclick="saveSeccion()">
                        <i class="fa fa-save"></i>
                    </button>

                    <button class="btn btn-default cerrar-modal-config" type="button">
                        <i class="fa fa-close"></i>
                    </button>

                    <button type="button" class="btn btn-info pull-right" style="margin-left: 10px;" id="preguntasBotNoEntregabtn" onclick="obtenerPreguntasBotNoEntrego('seccion')">Respuestas que el bot no pudo entregar en esta sección</button>
                </div>

                <br/>

                <form id="formularioSeccion" method="POST" enctype="multipart/form-data" action="#">

                    <input type="hidden" name="txtOrdenAccionesBot" id="txtOrdenAccionesBot">
                    <input type="hidden" name="seccionBotId" id="seccionBotId" value="0">
                    <input type="hidden" name="autorespuestaId" id="autorespuestaId" value="0">
                    <input type="hidden" name="autorespuestaAccionFinal" id="autorespuestaAccionFinal" value="0">
                    <input type="hidden" name="totalFilasSeccion" id="totalFilasSeccion" value='0'>
                    <input type="hidden" name="totalAccionesFinales" id="totalAccionesFinales" value="0">
                    <input type="hidden" name="contCondicionesAccionesFinales" id="contCondicionesAccionesFinales" value="0"> <!-- Cambiar de lugar -->
                    <input type="hidden" name="contCondicionesConsultaDy" id="contCondicionesConsultaDy" value="0"> <!-- Cambiar de lugar -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombre de la sección del bot</label>
                                <input type="text" name="seccionNombre" id="seccionNombre" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tipo de la sección del bot</label>
                                <select name="tipoSeccion" id="tipoSeccion" class="form-control input-sm" disabled>
                                    <option value="1">Conversacional</option>
                                    <option value="2">Transaccional</option>
                                </select>
                                <p id="info_modal" class="help-block"></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Destino almacenamiento del dato</label>
                                <select name="seccionBd" id="seccionBd" class="form-control input-sm">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Llave principal</label>
                                <select name="llavePrincipal" id="llavePrincipal" class="form-control input-sm">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">
                                Si simplemente quiere que al decir una palabra se dé una respuesta presione el botón agregar.
                                Si quiere que al decir una palabra se vaya a otra sección o menú, cree otra sección y ponga la condición de la palabra en una flecha
                            </p>
                        </div>
                    </div>

                    <div class="row" class="margin-top:15px;">
                        <div class="col-md-12">
                            <div class="panel box box-primary" style="border: 1px solid #e6e6e6">
                                <div class="box-header with-border" style="background-color: #009fe3">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#disparadoresAcciones" class="text-color-white">
                                            DISPARADORES Y ACCIONES DE ESTA SECCIÓN
                                        </a>
                                    </h4>
                                </div>
                                <div id="disparadoresAcciones" class="panel-collapse collapse in">

                                    <div class="box-body">

                                        <button type="button" class="btn btn-primary btn-sm" style="margin-bottom:15px" id="toggleAcordeon">Abrir/cerrar acordeones</button>

                                        <!-- Acciones que se dispara al llegar a la seccion -->
                                        <div class="panel box box-success" id="row_accion_inicial" style="display:none">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoAccionInicial">
                                                        <i class="fas fa-thumbtack"></i>&nbsp;Acción que dispara al llegar a la seccion
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="cuerpoAccionInicial" class="panel-collapse collapse in">
                                                <div class="box-body">
                                                <div class="row">
                                                    <input type="hidden" id="campo_accion_inicial" name="campo_accion_inicial" value="">
                                                    <div class="col-md-12">
                                                    
                                                        <div class="form-group">
                                                            <label for="" class="labelDisparador">Disparador local (Si el usuario escribe esto y está en esta sección se va a disparar la acción ...no es obligatorio)</label>
                                                            <input type="text" id="tag_local" name="tag_local" class="form-control input-sm">
                                                            <span class="info_disparador" class="help-block">Separe los tags con comas.</span>
                                                            <a id="myLink_local" href="#" class="pull-right modalDisparador" onclick="abrirDisparadorModal(<?=$_SESSION['HUESPED']?>);return false;">Disparadores de uso frecuente</a>
                                                        </div>

                                                        
                                                        <div class="form-group">
                                                            <label>Tipo de respuesta</label>

                                                            <div class="radio">
                                                                <label style="padding-right: 20px;">
                                                                    <input type="radio" name="tipo_file_accion_inicial" id="tipoTexto_accion_inicial" value="TEXT" checked onclick="cambioTipoMensaje('accion_inicial', 'TEXT')"> Texto
                                                                </label>

                                                                <label style="padding-right: 20px;">
                                                                    <input type="radio" name="tipo_file_accion_inicial" id="tipoImagen_accion_inicial" value="IMAGE" onclick="cambioTipoMensaje('accion_inicial', 'IMAGE')"> Imagen
                                                                </label>

                                                                <label style="padding-right: 20px;">
                                                                    <input type="radio" name="tipo_file_accion_inicial" id="tipoVideo_accion_inicial" value="VIDEO" onclick="cambioTipoMensaje('accion_inicial', 'VIDEO')"> Video
                                                                </label>

                                                                <label style="padding-right: 20px;">
                                                                    <input type="radio" name="tipo_file_accion_inicial" id="tipoAudio_accion_inicial" value="AUDIO" onclick="cambioTipoMensaje('accion_inicial', 'AUDIO')"> Audio
                                                                </label>
                                                                
                                                                <label style="padding-right: 20px;">
                                                                    <input type="radio" name="tipo_file_accion_inicial" id="tipoDocumento_accion_inicial" value="DOCUMENT" onclick="cambioTipoMensaje('accion_inicial', 'DOCUMENT')"> Documento
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" id="seccionMediaaccion_inicial" style="display:none">
                                                            <label>Archivo</label>
                                                            <input type="file" name="archivoMediaaccion_inicial" id="archivoMediaaccion_inicial" onchange="return fileValidation('accion_inicial')">
                                                            <p class="help-block" id="helpBlockaccion_inicial">Help text</p>
                                                        </div>

                                                        <div class="form-group" id="seccionMediaActualaccion_inicial" style="display:none">
                                                            <label>Archivo usado actualmente</label>
                                                            <p id="mediaActualaccion_inicial"></p>
                                                        </div>
                                                        
                                                    
                                                        <div class="form-group" id="grupTextAreaAccionInicial">
                                                            <label for="">Respuestas (Texto que escribe el bot cuando el usuario escribe los disparadores globales, locales o en general cuando llega a esta seccion desde cualquier parte ...no es obligatorio)</label>
                                                            <textarea class="tinyMCE" id="rpta_accion_inicial" name="dyTr_rpta_accion_inicial" cols="60" rows="1"></textarea>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Botones de respuesta (Máximo 10, si agrega 3 elementos se visualizará como botones, si se agrega mas de 3 elementos se visualizará como una lista en WhatsApp)</label>

                                                                <div class="col-md-8">
                                                                    <table class="table" id="listaBotonesa_inicial">
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
                                                                    <button type="button" class="btn btn-success btn-sm pull-left" id="agregarBoton" onclick="agregarBotonRespuesta('a_inicial')">
                                                                        <i class="fa fa-plus"></i> Agregar boton
                                                                    </button>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div id="acciones_accion_inicial" style="display: none;">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Accion a realizar</label>
                                                                <select name="accion_inicial_accion" id="accion_inicial_accion" class="form-control input-sm" onchange="cambiarAccionInicial()">
                                                                    <option value="0">Permanecer en la seccion</option>
                                                                    <option value="2">Transferir a otra seccion</option>
                                                                    <option value="1">Transferir a agentes</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Seccion</label>
                                                                <select name="accion_inicial_seccion" id="accion_inicial_seccion" class="form-control input-sm" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="accionesBotConversacional">
                                        </div>

                                        <div id="accionesBotTransaccional">
                                        </div>

                                        <div>
                                            <button class="btn btn-primary pull-right" type="button" id="agregarFilaCaptura" onclick="agregarFilaTransaccional('captura')" style="display: none; margin:5px;"><i class="fa fa-plus"></i> Agregar Captura de datos</button>
                                        </div>

                                        <div>
                                            <button class="btn btn-primary pull-right" type="button" id="agregarFilaConsulta" onclick="agregarFilaTransaccional('consulta')" style="display: none; margin:5px;"><i class="fa fa-plus"></i> Agregar Consulta de datos</button>
                                        </div>

                                        <div>
                                            <button class="btn btn-primary pull-right" type="button" id="agregarFilaConsultaDyalogo" onclick="agregarFilaTransaccional('consultaDyalogo')" style="display: none; margin:5px;"><i class="fa fa-plus"></i> Agregar Consulta de datos propios de DYALOGO</button>
                                        </div>

                                        <div>
                                            <button class="btn btn-primary pull-right" type="button" id="agregarFila" onclick="agregarFilaBot('add')"><i class="fa fa-plus"></i> Agregar</button>
                                        </div>

                                    </div>
                                </div>
                            </div> 

                        </div>
                    </div>

                    <!-- Accion bot final -->
                    <div class="panel box box-primary background-blue" id="row_accion_final" style="display:none;margin-top:15px;border: 1px solid #e6e6e6;">
                        <div class="box-header with-border" style="background-color: #009fe3">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#cuerpoAccionFinal" class="text-color-white">
                                    ACCIÓN A EJECUTAR
                                </a>
                            </h4>
                        </div>

                        <div id="cuerpoAccionFinal" class="panel-collapse collapse in">
                            <div class="box-body" style="padding: 25px 20px;">

                                <div id="accionesFinales">                                
                                </div>

                                <div id="accionFinalDefecto">
                                </div>

                                <div class="row" style="margin-top: 30px;">
                                    <button type="button" class="btn btn-primary pull-right" onclick="agregarAccionFinal(false, null, 'add')"><i class="fa fa-plus"></i> Agregar Accion</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Modal flechas -->
<div class="modal fade" id="flechaModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-flechas">&times;</button>
                <h4 class="modal-title">Disparadores</h4>
            </div>

            <div class="modal-body">
                <h2 id="tituloFormularioFlechas"></h2>
                <form action="#" method="POST" id="flechasForm">
                    <input type="hidden" name="totalCamposDisparadores" id="totalCamposDisparadores" value="0">
                    <input type="hidden" name="flechaFromId" id="flechaFromId" value="0">
                    <input type="hidden" name="flechaToId" id="flechaToId" value="0">
                    <input type="hidden" name="tipoEjecucion" id="tipoEjecucion" value="0">
                    <input type="hidden" name="aux" id="aux" value="0">
                    <input type="hidden" name="aux2" id="aux2" value="0">
                    <input type="hidden" name="puertosFlecha" id="puertosFlecha" value="0">
                    <input type="hidden" name="tipoSeccionFrom" id="tipoSeccionFrom" value="0">
                    <input type="hidden" name="tipoSeccionTo" id="tipoSeccionTo" value="0">
                    
                    <div class="row">
                        <div class="col-md-12"   >
                            <table class="table" id="disparadoresTable">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <div id="disparadoresTransaccionales"></div>

                            <div id="otrosDisparadores" style="display: none;"></div>

                            <button type="button" class="btn btn-primary btn-sm pull-right" id="agregarDisparador" onclick="agregarNuevoDisparador()">
                                <i class="fa fa-plus"></i> Agregar disparador
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left cerrar-modal-flechas">
                    <?php echo $str_cancela;?>
                </button>
                <button type="button" class="btn btn-primary pull-right" onclick="guardarDisparadores()">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de las preguntas que el bot no pudo entregar -->
<div class="modal fade" id="consultaPreguntasModal">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-consulta-preguntas">&times;</button>
                <h4 class="modal-title">Respuestas que el bot no pudo entregar</h4>
            </div>

            <div class="modal-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success" onclick="exportarListadoPn()">Descargar listado <i class="fa fa-file-excel-o"></i></button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style="max-height: 600px;overflow: scroll;">
                            <table class="table table-hover" id="preguntasBotNoEntregaTable">
                                <thead>
                                    <tr>
                                        <th style="width:30%;">Texto que escribió el cliente</th>
                                        <th style="width:20%;" id="consultaPreguntasNombreSeccion" style="display: none;">Sección del Bot donde se escribió</th>
                                        <th style="width:20%;">Ultima fecha en que escribieron esto</th>
                                        <th style="width:10%;">Cantidad de veces que lo han escrito</th>
                                        <th style="width:20%;">Agregar a esta opción</th>
                                        <th style="width:10%;">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>

<!-- Modal tag globales-->
<div class="modal fade" id="tagsGlobales" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-tags-globales">&times;</button>
                <h4 class="modal-title">Lista de secciones con sus disparadores globales</h4>
            </div>

            <div class="modal-body">
                <form action="#" method="POST" id="tagsGlobalesForm">
                    <!-- <input type="hidden" name="totalCamposComunes" id="totalCamposComunes" value="0"> -->
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id="tagsGlobalesTable">
                                <thead>
                                    <tr>
                                        <th>Sección</th>
                                        <th>Disparadores globales (El usuario puede escribir esto en cualquier parte del bot y va a llegar a esta sección)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left cerrar-modal-tags-globales">
                    <?php echo $str_cancela;?>
                </button>
                <button type="button" class="btn btn-primary pull-right" onclick="guardarTagsGlobales()">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal tags -->
<div class="modal fade" id="tagsComunesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-tags-comunes">&times;</button>
                <h4 class="modal-title">Disparadores de uso frecuente</h4>
            </div>

            <div class="modal-body">
                <form action="#" method="POST" id="tagsComunesForm">
                    <input type="hidden" name="totalCamposComunes" id="totalCamposComunes" value="0">
                    <div class="row">
                        <div class="col-md-12"   >
                            <table class="table" id="tagsComunesTable">
                                <thead>
                                    <tr>
                                        <th>Tag del disparador</th>
                                        <th>Expresiones que contiene</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-primary btn-sm pull-right" id="addCampotagsComunesbtn">
                                    <i class="fa fa-plus"></i> Agregar campo
                                </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left cerrar-modal-tags-comunes">
                    <?php echo $str_cancela;?>
                </button>
                <button type="button" class="btn btn-primary pull-right" onclick="guardarTagsComunes()">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Avanzado TODO: VALIDACIONES FALTAN AUN POR TERMINAR -->
<div class="modal fade" id="seccionAvanzado" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-seccion-avanzado">&times;</button>
                <h4 class="modal-title">Configuracion avanzada de la accion actual</h4>
            </div>

            <div class="modal-body">

                <div class="row" id="avanzadoValidacion">
                    <div class="col-md-12">
                        <h3>Configuracion del campo</h3>
                    </div>
                    <form method="POST" id="formValidacion">

                        <input type="hidden" name="validacionAccionId" id="validacionAccionId">
                        <input type="hidden" name="validacionAccionTipo" id="validacionAccionTipo">
                        <input type="hidden" name="validacionCampo" id="validacionCampo">

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="validacionRadio" id="validacionRadio0" value="0" checked>
                                            Sin validar
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="validacionRadio" id="validacionRadio1" value="1">
                                            Validado
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="configuracionDeLosCampos" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>TIPO</label>
                                    <select name="tipoCampo" id="tipoCampo" class="form-control input-sm">
                                        <option value="1">Solo recibir texto</option>
                                        <option value="3">Solo recibir números</option>
                                        <option value="5">Solo recibir fechas</option>
                                        <option value="6">Solo recibir respuestas de la siguiente lista</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6" id="maxTexto" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cantidad máxima de caracteres</label>
                                        <input type="text" name="campoCantMaximaTexto" id="campoCantMaximaTexto" class="form-control input-sm" value="253">
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-6" id="rangoNumeros" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Valor minimo</label>
                                        <input type="text" name="campoValorMinimo" id="campoValorMinimo" class="form-control input-sm" placeholder="Valor minimo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Valor maximo</label>
                                        <input type="text" name="campoValorMaximo" id="campoValorMaximo" class="form-control input-sm" placeholder="Valor maximo">
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-6" id="rangoFechas" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha minima</label>
                                        <input type="text" name="campoFechaMinima" id="campoFechaMinima" class="form-control input-sm" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha maxima</label>
                                        <input type="text" name="campoFechaMaxima" id="campoFechaMaxima" class="form-control input-sm" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6" id="campoListas" style="display: none;">
                                <div class="form-group">
                                    <label>Lista</label>
                                    <select name="campoLista" id="campoLista" class="form-control input-sm" style="width: 100%;">
                                        
                                    </select>
                                    <a href="#" id="nuevaLista" class="pull-left">Nueva lista</a>
                                    <a href="#" id="editarLista" class="pull-right">Editar lista</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row" id="avanzadoCondiciones">
                    <form action="#" method="POST" id="formConfiguracionAvanzada">
                        <!-- <input type="hidden" name="totalCamposComunes" id="totalCamposComunes" value="0"> -->
                        <input type="hidden" name="autorespuestaContenidoId" id="autorespuestaContenidoId" value="0">
                        <input type="hidden" name="idVariableDeAccionFinal" id="idVariableDeAccionFinal" value="0">
                        <div class="row">
                            <div class="col-md-12" id="condicionesAcciones">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="tipoCondicion" id="tipoCondicion" class="form-control input-sm" onchange="activarCondiciones()">
                                                <option value="0">Sin condiciones</option>
                                                <option value="1">Con condiciones</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="button" id="btnAgregarCondicion" onclick="agregarCondicion('add')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>Agregar Condicion</button>
                                        </div>
                                    </div>
                                </div>
    
                                <div id="grupo_codiciones" style="display: none;">
    
                                    <div class="row titulo-tabla-condiciones">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-3">Campo o variable</div>
                                        <div class="col-md-2">Condicion</div>
                                        <div class="col-md-2">Tipo dato con el que se validara</div>
                                        <div class="col-md-3">Valor contra el que se comparará</div>
                                        <div class="col-md-1"></div>
                                    </div>
    
                                    <div id="cuerpo_condiciones"></div>
    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left cerrar-modal-seccion-avanzado">
                    <?php echo $str_cancela;?>
                </button>
                <button type="button" id="guardarCondiciones" class="btn btn-primary pull-right" onclick="guardarCondiciones()">
                    Guardar
                </button>

                <button type="button" id="guardarValidaciones" class="btn btn-primary pull-right" onclick="guardarValidaciones()">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal seleccion flecha -->
<div class="modal fade" id="seleccionaFlecha" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cerrar-modal-seleccionar-flecha">&times;</button>
                <h4 class="modal-title">Selecciona la flecha a modificar</h4>
            </div>

            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sentido</th>
                            <th></th>
                        </tr>
                        <tbody>
                            <tr>
                                <td id="sentido1">Desde hasta</td>
                                <td>
                                    <button type="button" class="btn btn-primary pull-right" id="abrirConfig1">Abrir</button>
                                </td>
                            </tr>

                            <tr>
                                <td id="sentido2">Desde hasta</td>
                                <td>
                                    <button type="button" class="btn btn-primary pull-right" id="abrirConfig2">Abrir</button>
                                </td>
                            </tr>
                        </tbody>
                    </thead>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left cerrar-modal-seleccionar-flecha">
                    <?php echo $str_cancela;?>
                </button>
            </div>
        </div>
    </div>
</div>