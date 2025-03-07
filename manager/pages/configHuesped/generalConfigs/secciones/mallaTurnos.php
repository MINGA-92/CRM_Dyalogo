<div id="secMallaTurnos">
    <h4 class="text-aqua">Malla de turnos</h4>
    <form action="" id="form-malla-turnos">

        <div class="form-group row">
            <div class="col-md-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="mallaTurnoRequerida" class="chb" checked name="mallaTurnoRequerida" >
                        Malla de turno requerida
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="mallaTurnoHorarioDefecto" class="chb" name="mallaTurnoHorarioDefecto" >
                        Hora de entrada y salida por defecto de la campaña
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <label for="horaEntrada">Hora entrada por defecto</label>
                <input type="text" name="horaEntrada" id="horaEntrada" class="form-control text-hora" placeholder="00:00:00" value="08:00:00" >
                <small class="text-muted">Formato Horas:Minutos:Segundos</small>
            </div>
            <div class="col-md-3">
                <label for="horaSalida">Hora salida por defecto</label>
                <input type="text" name="horaSalida" id="horaSalida" class="form-control text-hora" placeholder="00:00:00" value="17:00:00" >
                <small class="text-muted">Formato Horas:Minutos:Segundos</small>
            </div>
        </div>
        <button type="button" id="updateMallaTurnos" class="btn btn-success btn-sm pull-right"><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
    </form>
</div>

<!-- <div id="secPausas" class="e-collapse" style="display: none;"> -->
<div id="secPausas" class="" style="display: none;">
    <h4 class="text-aqua">Lista de pausas</h4>
    <form action="" id="form-pausas">
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