<form action="" id="calidadForm">
    <!--esta accion es si para crear o editar -->
    <input type="hidden" name="tipoAccion" id="tipoAccion">
    <input type="hidden" name="idEstpas" id="idEstpas" value="<?=$_GET["id_paso"]?>">
    <input type="hidden" name="idGuion" id="idGuion">
    <input type="hidden" name="idTareaCal" id="idTareaCal" value="0">

    <!-- Botones -->
    <div>
        <button class="btn btn-default" id="Save">
            <i class="fa fa-save"></i>
        </button>

        <button class="btn btn-default" id="cancel">
            <i class="fa fa-close"></i>
        </button>
    </div>
    <br>

    <div class="panel box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#sec_1">
                    Tarea de calidad
                </a>
            </h4>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group alertas">

                </div>
            </div>
        </div>

        <div id="sec_1" class="panel-collapse collapse in" style="padding: 10px;">
            <div class="form-group row">
                <div class="col-md-11">
                    <label for="nombreCaso">Nombre</label>
                    <input type="text" class="form-control" name="nombreCaso" id="nombreCaso">
                </div>
                <div class="col-md-1 col-xs-1">
                    <div class="form-group">
                        <label for="pasoActivo" id="LblpasoActivo">ACTIVO</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself"> 
                            </label>
                        </div>
                    </div>
                </div>                             
            </div>

            <div class="form-group row camposDeEdicion">
                <div class="col-md-12">
                    <label for="formulario">Tipo de distribución</label>
                    <select name="tipoDistribucionTrabajo" id="tipoDistribucionTrabajo" class="form-control">
                        <option value="0">Seleccionar</option>
                        <option value="1">Todos ven todo</option>
                        <option value="2">Asignar al que menos registros tenga en esta condición</option>
                        <option value="3">Asignar al que menos registros tenga</option>
                        <option value="4">Asignar manualmente</option>
                    </select>
                </div>
            </div>

            <div class="form-group row camposDeEdicion">
                <div class="col-md-6">
                    <label for="pregun">Campo de la condición</label>
                    <select name="pregun" id="pregun" class="form-control">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="lisopc">Valor de la condición</label>
                    <select name="lisopc" id="lisopc" class="form-control">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="panel box box-primary camposDeEdicion" style="padding: 10px;">
        <div class="box-header with-border">
            <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#sec_2">
                    Asignacion de usuarios
                </a>
            </h4>
        </div>

        <div id="sec_2" class="panel-collapse collapse in">
            <!-- En esta seccion se encuentra el dragAndDrop -->
            <div class="form-group row" id="dragAndDrop">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="buscadorDisponible" id="buscadorDisponible" class="form-control">
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <p class="text-center titulo-dragdrop">Disponibles</p>
                    <ul id="disponible" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                margin-bottom: 10px;
                                overflow: auto;   
                                -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                        
                    </ul>
                </div>
                <div class="col-md-2 text-center" style="padding-top:100px">
                    <button type="button" id="derecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                    <button type="button" id="todoDerecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br>
                    
                    <button type="button" id="izquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button> <br>
                    <button type="button" id="todoIzquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="buscadorSeleccionado" id="buscadorSeleccionado" class="form-control">
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <p class="text-center titulo-dragdrop">Seleccionados</p>
                    <ul id="seleccionado" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                margin-bottom: 10px;
                                overflow: auto;   
                                -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                    
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>
<?php include_once('G37_Eventos.php') ?>
