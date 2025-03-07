<div class="panel box box-primary">
    <div class="box-header with-border contacto-header">
        <h4 class="box-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapsePausas">
            PAUSAS
        </a>
        </h4>
    </div>
    <div id="collapsePausas" class="panel-collapse collapse">
        <div class="box-body">

            <div class="secPausas2">
                <h4 class="text-aqua">Pausas</h4>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="tipoPausa">Cuando un agente solicita una pausa, esta pausa se activa</label>
                        <select name="tipoPausa" id="tipoPausa" class="form-control" form="form-huesped">
                            <option value="1">Tan pronto termine una comunicación</option>
                            <option value="2">Solo cuando termine todas las comunicaciones que ya tenga asignada</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- <div id="secPausas" class="e-collapse" style="display: none;"> -->
            <div id="secPausas" class="" style="display: none;">
                <h4 class="text-aqua">Lista de pausas</h4>
                <form action="" id="form-pausas">
                    @csrf   
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th style="width:120px;">Clasificación</th>
                                <th style="width:130px;"> Tipo de programación</th>
                                <td style="width:100px;">Hora inicial por defecto</td>
                                <td style="width:100px;">Hora final por defecto</td>
                                <td style="width:80px;">Duración máxima al día min.</td>
                                <td style="width:80px;">Cantidad máxima al día</td>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>
                </form>
                <br>
                <button type="button" id="btn-agregar-pausa" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Agregar</button>
                <button type="button" id="btn-guardar-cambios-pausas" class="btn btn-primary btn-sm pull-right" style="margin-right:5px;"><i class="fa fa-save"></i> Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
