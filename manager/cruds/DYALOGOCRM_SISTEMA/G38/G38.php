
<?php
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
?>

<!-- MODAL PARA PROGRAMAR TAREAS-->
<div class="modal fade-in" id="modVisorTareaProgramada" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="refrescarGrillas" onclick="CerrarTareas()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="box-header with-border">
                                <label>NOMBRE DE LA TAREA</label>
                                <input type="text" class="form-control" id="txtNombreTarea" name="txtNombreTarea" placeholder="NOMBRE DE LA TAREA">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="box-header with-border">
                                <label>PROXIMA EJECUCION</label>
                                <input type="text" class="form-control" id="ProximaEjecucion" name="ProximaEjecucion" placeholder="00/00/0000" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tarConActivo">ACTIVO</label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="0" name="tarConActivo" id="tarConActivo"> 
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#s_2" class="" aria-expanded="true">
                                PERIODICIDAD PERSONALIZADA                                
                            </a>
                        </h4>
                    </div>
                    <div id="s_2" class="panel-collapse collapse in" aria-expanded="true">
                        <div class="row secTareasProgramadas">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tarRepetir">REPETIR CADA: </label>
                                </div>
                            </div>
                            <div class="col-md-1" hidden>
                                <div class="form-group">
                                    <select name="tarRepetir" id="tarRepetir" class="form-control">
                                        <option value="1" selected>1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="tarCadaCuanto" id="tarCadaCuanto" class="form-control">
                                        <option value="0" selected disabled>Elige Una Opción</option>
                                        <option value="1">Dia</option>
                                        <option value="2">Semana</option>
                                        <option value="3">Mes</option>
                                        <option value="4">Año</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dia De Cada Mes -->
                            <div id="divDiaDeCadaMes" hidden>
                                <div class="col-md-1" id="SelectDiaDeCadaMes">
                                    <div class="form-group">
                                        <select name="DiaDeCadaMes" id="DiaDeCadaMes" class="form-control">
                                            <option value="0" selected disabled>El Día</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" id="lblDeCadaMes">
                                    <div class="form-group">
                                        <label for="DeCadaMes" class="float-left">DE CADA MES</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha Para Cada Año -->
                            <div id="divFechaCadaYear" hidden>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="ElDia">El Día: </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" id="FechaCadaYear" name="FechaCadaYear">
                                </div>
                            </div>

                            <!-- Hora -->
                            <div class="form-group" id="divHora" hidden>
                                <div class="col-md-1" id="divALas">
                                    <div class="form-group">
                                        <label for="tarRepetir">A LAS: </label>
                                    </div>
                                </div>
                                <div class="col-md-2" id="divtarHor">
                                    <div class="form-group">
                                        <input type="time" class="form-control ui-timepicker-input" name="tarHor" id="tarHor" placeholder="HH:MM:SS">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Check Dias -->
                        <div class="row secTareasProgramadas" id="divSeRepiteEl" hidden>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>SE REPITE EL:</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="lunesActivo">LUNES</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="0" name="lunesActivo" id="lunesActivo"> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="martesActivo">MARTES</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="0" name="martesActivo" id="martesActivo"> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="miercolesActivo">MIERCOLES</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="0" name="miercolesActivo" id="miercolesActivo"> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="juevesActivo">JUEVES</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="0" name="juevesActivo" id="juevesActivo"> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="viernesActivo">VIERNES</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="0" name="viernesActivo" id="viernesActivo"> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="sabadoActivo">SABADO</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="0" name="sabadoActivo" id="sabadoActivo"> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="domingoActivo">DOMINGO</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="0" name="domingoActivo" id="domingoActivo"> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row secTareasProgramadas">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>TERMINA EL : </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="tarFinaliza" id="tarFinaliza" class="form-control">
                                        <option value="0" selected disabled>Elige Una Opción</option> 
                                        <option value="1">NUNCA</option>
                                        <option value="2">FECHA ESPECIFICA</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-2" id="divFechaFinal" hidden>
                                <input type="text" name="radioFecha" id="radioFecha" class="form-control Radiocondiciones" placeholder="Elige Una Fecha">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel box box-primary" id="divProgramacionAcciones">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#sectar_3" class="" aria-expanded="true">
                                ACCIONES                                
                            </a>
                        </h4>
                    </div>
                    <div id="sectar_3" class="panel-collapse collapse in" aria-expanded="true">
                        <div id="programacion_acciones_tareas">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12  table-responsive">
                                        <table class="table table-condensed" id="table_programacion_acciones_tareas">
                                            <thead>
                                                <tr class="active">
                                                    <th scope="col" class="col-md-3" style="text-align: center;">
                                                        <label>TIPO DE TAREA</label>
                                                    </th>
                                                    <th scope="col" class="col-md-3" style="text-align: center;">
                                                        <label>AL AGENTE</label>
                                                    </th>
                                                    <th scope="col" class="col-md-3" style="text-align: center;">
                                                        <label>PARA QUE REGISTROS APLICA</label>
                                                    </th>
                                                    <th scope="col" class="col-md-3" style="text-align: center;">
                                                        <label>OPCIONES</label>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="divParametrosAccionesTareasProgramadas">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                    </div>
                                    <div class="col-md-2" style="text-align: right;">
                                        <button type="button" class="btn btn-sm btn-success" id="accion_nuevo">
                                            <i class="glyphicon glyphicon-plus"></i> Nueva Acción
                                        </button>
                                        <input type="hidden" name="tarea_cantidad" id="accion_cantidad">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-info" id="accion_guardar_tarea_nueva">
                    <i class="fa fa-save" aria-hidden="true"> - Guardar Tarea </i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA AGREGAR ACCIONES A LA PROGRAMACION DE TAREAS-->
<div class="modal fade-in" id="modVisorAccionesTareaProgramada" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="CerrarCondiciones()">&times;</button>
            </div>
            <div class="modal-body">
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#secAcciones_1" class="" aria-expanded="true">
                                    DETALLES DE LA ACCION A PROGRAMAR                                
                                </a>
                            </h4>
                        </div>
                        <div class="form-group">
                            <input type="text" id="InputIdAccion" disabled hidden>
                            <input type="text" id="InputIdUsuario" disabled hidden>
                        </div>
                        <div id="#secAcciones_1" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="row secTareasProgramadas" id="accionDeLaTarea">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="tipoTarea">TIPO DE TAREA: </label>
                                                <select name="tipoTarea" id="tipoTarea" class="form-control">
                                                    <option value="0" selected="true">SELECCIONE</option>
                                                    <option value="1">ACTIVAR REGISTROS</option>
                                                    <option value="2">INACTIVAR REGISTROS</option>
                                                    <option value="3">ASIGNAR REGISTROS</option>
                                                    <option value="4">DESASIGNAR REGISTROS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="asignarAgente">AL AGENTE: </label>
                                                <select name="asignarAgente" id="asignarAgente" class="form-control">
                                                    <option value="0" selected="true">SELECCIONE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="paraQueRegistros">PARA QUE REGISTROS: </label>
                                                <select name="paraQueRegistros" id="paraQueRegistros" class="form-control">
                                                    <option value="0" selected="true">SELECCIONE</option>
                                                    <option value="1">TODOS</option>
                                                    <option value="2">CONDICIONES</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="aplicaLimite">APLICAR UN LIMITE DE CANTIDAD: </label>
                                                <select name="aplicaLimite" id="aplicaLimite" class="form-control">
                                                    <option value="-1">SI</option>
                                                    <option value="0" selected="true">NO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="col-md-3" id="divCantidadLimite">
                                            <label for="numbCantidadLimite">CANTIDAD DE REGISTROS PARA LOS QUE APLICA: </label>
                                            <input type="number" class="form-control" id="numbCantidadLimite" name="numbCantidadLimite" placeholder="CANTIDAD DE REGISTROS PARA LOS QUE APLICA">
                                        </div>

                                        <div class="col-md-4 float-end" style="margin-top: 2%; margin-left: 28%;">
                                            <div class="form-group" id="divGenerarReporte">
                                                <button class="btn btn-primary" type="button" id="BtnGenerarReporte">Generar Reporte</button>      
                                            </div>
                                            <div class="form-group" hidden>
                                                <button class="btn btn-primary" type="button" id="BtnDescargarReporte" hidden>Descargar Reporte</button>       
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" id="CONDICIONES_TAREAS"> 
                                    </div>
                                    <div>                                
                                        <input type="hidden" name="idEstpas_2" id="idEstpas_2">
                                        <input type="hidden" name="paraqueregistros_2" id="paraqueregistros_2">
                                    </div>
                                </div>

                                <div class="col-md-10" style="display: none;" id="div_filtros_tareas">
                                    <div class="col-md-offset-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="button" id="new_filtro_tareas">Agregar Condición</button>    
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-info" id="accion_guardar_accion_nueva">
                    <i class="fa fa-save" aria-hidden="true"> - Guardar Accion </i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Para Ver Asignaciónes -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="ModalReporte">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" onclick="LimpiarTabla()">&times;</button>
            <h2>Reporte Asignaciones</h2>
        </div>
            <div class="modal-body">
                <div class="panel box box-primary">
                    <div class="card-body">
                        <table id="TablaReporte" class="table table-border table-hover">
                            <thead>
                                <tr id="ContTitulos"> </tr>
                            </thead>
                            <tbody id="BodyTabla">
                                <tr id="ContFilas"> </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>


<!-- tabla de tareas programadas de la campaña -->
<div class='box-body'>
    <div class='row'>
        <div class='col-md-12 col-xs-12  table-responsive'>
            <table class='table table-condensed' id='table_programacion_tareas'>
                <thead>
                    <tr class='active'>
                        <th scope="col" class="col-md-9" style="text-align: center;">
                            <label>NOMBRE DE TAREA PROGRAMADA</label>
                        </th>
                        <th scope="col" class="col-md-2" style="text-align: center;">
                            <label>OPCIONES</label>
                        </th>
                    </tr>
                </thead>
                <tbody id='divParametrosTareasProgramadas'>
                    
                </tbody>
            </table>
            <label id="respuestaNoHayTareas"></label>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-10'>
        </div>
        <div class='col-md-2' style='text-align: right;'>
            <button type='button' class='btn btn-sm btn-success' id='tarea_nuevo'>
                <i class='glyphicon glyphicon-plus'></i> Nueva Tarea
            </button>
            <button type='button' class='btn btn-sm btn-warning' id='tarea_refrescar'>
                <i class='fa fa-refresh'></i> 
            </button>
            <input type='hidden' name='tarea_cantidad' id='tarea_cantidad'>
        </div>
    </div>

</div>

<script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
<script src="https://unpkg.com/tableexport@latest/dist/js/tableexport.min.js"></script>

<?php include_once('G38_Eventos.php') ?>
