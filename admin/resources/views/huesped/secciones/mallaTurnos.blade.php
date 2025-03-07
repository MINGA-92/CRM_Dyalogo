<div class="panel box box-primary">
    <div class="box-header with-border contacto-header">
        <h4 class="box-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseMallaTurnos">
            MALLA DE TURNOS
        </a>
        </h4>
    </div>
    <div id="collapseMallaTurnos" class="panel-collapse collapse">
        <div class="box-body">
            <div id="secMallaTurnos">
                <h4 class="text-aqua">Malla de turnos</h4>

                <div class="form-group row">
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="mallaTurnoRequerida" class="chb" checked name="mallaTurnoRequerida" form="form-huesped">
                                Malla de turno requerida
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="mallaTurnoHorarioDefecto" class="chb" name="mallaTurnoHorarioDefecto" form="form-huesped">
                                Hora de entrada y salida por defecto de la campaña
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="horaEntrada">Hora entrada por defecto</label>
                        <input type="text" name="horaEntrada" id="horaEntrada" class="form-control text-hora" placeholder="00:00:00" value="08:00:00" form="form-huesped">
                        <small class="text-muted">Formato Horas:Minutos:Segundos</small>
                    </div>
                    <div class="col-md-3">
                        <label for="horaSalida">Hora salida por defecto</label>
                        <input type="text" name="horaSalida" id="horaSalida" class="form-control text-hora" placeholder="00:00:00" value="17:00:00" form="form-huesped"> 
                        <small class="text-muted">Formato Horas:Minutos:Segundos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
