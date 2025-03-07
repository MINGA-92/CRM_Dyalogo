<div id="secPasswordPolicy">
    <h4 class="text-aqua">Politicas de seguridad en el cambio de contraseñas</h4>

    <div class="form-group row">
        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="passLongitudRequerida" name="passLongitudRequerida" checked form="form-huesped">
                    Longitud mínima de 8 caracteres
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="passMayusculaRequerida" name="passMayusculaRequerida" checked form="form-huesped">
                    Uso mínimo de una letra en mayúscula
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="passNumeroRequerido" name="passNumeroRequerido" checked form="form-huesped">
                    Uso mínimo de un numero
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="passSimboloRequerido" name="passSimboloRequerido" checked form="form-huesped">
                    Uso mínimo de un símbolo
                </label>
            </div>
        </div>
    </div>

    <br>
    <h4 class="text-aqua">Políticas de seguridad en el inicio de sesión</h4>

    
    <div class="form-group row">

        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="passCambioObligatorioRequerido"  name="passCambioObligatorioRequerido" checked form="form-huesped">
                    Cambio de contraseña obligatorio en el primer ingreso
                </label>
            </div>
        </div>

        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="passHistoricoRequerido"  name="passHistoricoRequerido" checked form="form-huesped">
                    Verificar histórico de contraseñas
                </label>
            </div>
        </div>

        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="passCambioPeriodicoRequerido"  name="passCambioPeriodicoRequerido" form="form-huesped">
                    Cambio de contraseña de forma periódica
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <label for="passDiasCambioPeriodico">Días máximos para el cambio de contraseña</label>
            <input type="text" name="passDiasCambioPeriodico" id="passDiasCambioPeriodico" class="form-control" value="90" form="form-huesped" disabled="disabled">
        </div>
    </div>

</div>
