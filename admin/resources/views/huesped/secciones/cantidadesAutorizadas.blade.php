<div class="panel box box-primary">
    <div class="box-header with-border contacto-header">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseCantidadesAutorizadas">
                USUARIO
            </a>
        </h4>
    </div>
    <div id="collapseCantidadesAutorizadas" class="panel-collapse collapse">
        <div class="box-body">
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="cantidadMaxAgentesSimultaneos">Agentes</label>
                    <input type="text" name="cantidadMaxAgentesSimultaneos" id="cantidadMaxAgentesSimultaneos" class="form-control" form="form-huesped" value="0">
                </div>
                <div class="col-md-3">
                    <label for="cantidadMaximaSupervisores">Supervisores</label>
                    <input type="text" name="cantidadMaximaSupervisores" id="cantidadMaximaSupervisores" class="form-control" form="form-huesped" value="0">
                </div>
                <div class="col-md-3">
                    <label for="cantidadMaximaBackoffice">Backoffice</label>
                    <input type="text" name="cantidadMaximaBackoffice" id="cantidadMaximaBackoffice" class="form-control" form="form-huesped" value="0">
                </div>
                <div class="col-md-3">
                    <label for="calidad">Calidad</label>
                    <input type="text" name="cantidadMaximaCalidad" id="cantidadMaximaCalidad" class="form-control" form="form-huesped" value="0">
                </div>
            </div>

            <div class="form-group row" style="display: none;">
                <div class="col-md-3">
                    <label for="dobleFactorAuth">
                        <input type="checkbox" form="form-huesped" name="dobleFactorAuth" id="dobleFactorAuth" class="minimal" value="1">
                        &nbsp;Habilitar el doble factor de autenticación
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
