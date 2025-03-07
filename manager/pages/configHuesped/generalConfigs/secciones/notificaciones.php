<h4 class="text-aqua">Destinatarios</h4>
<form action="" id="form-notifications">
    <div class="form-group row">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="notificar_pausas" name="notificar_pausas" >
                    Notificar Pausas
                </label>
            </div>
        </div>
        <div class="col-md-8">
            <label for="emails_notificar_pausas">Emails para notificar pausas <small>(Emails separadas por coma)</small></label>
            <input type="text" class="form-control" id="emails_notificar_pausas" name="emails_notificar_pausas" placeholder="Ingresa los emails" >
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="notificar_sesiones" name="notificar_sesiones" >
                    Notificar Sesiones
                </label>
            </div>
        </div>
        <div class="col-md-8">
            <label for="emails_notificar_sesiones">Emails para notificar sesiones <small>(Emails separadas por coma)</small></label>
            <input type="text" class="form-control" id="emails_notificar_sesiones" name="emails_notificar_sesiones" placeholder="Ingresa los emails" >
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="notificar_incumplimientos" name="notificar_incumplimientos"  checked>
                    Notificar incumplimientos
                </label>
            </div>
        </div>
        <div class="col-md-8">
            <label for="emails_notificar_incumplimientos">Emails para notificar incumplimientos <small>(Emails separadas por coma)</small></label>
            <input type="text" class="form-control" id="emails_notificar_incumplimientos" name="emails_notificar_incumplimientos" placeholder="Ingresa los emails" >
        </div>
    </div>

    <h4 class="text-aqua">Mail desde el que se envian las notificaciones</h4>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="notificacion_usuario">Usuario <small class="text-red"><strong>Debe ser una cuenta de Google(gmail)</strong></small></label>
            <input type="text" class="form-control" name="notificacion_usuario" id="notificacion_usuario"  placeholder="ejemplo@gmail.com">
        </div>
        <div class="col-md-4">
            <label for="notificacion_password">Contraseña de aplicación</label>
            <input type="password" class="form-control" name="notificacion_password" id="notificacion_password" >
        </div>
        <div class="col-md-4">
            <input type="hidden" class="form-control" name="notificacion_servidor_smtp" id="notificacion_servidor_smtp"  value="smtp.gmail.com">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <input type="hidden" class="form-control" name="notificacion_dominio" id="notificacion_dominio"  value="gmail.com">
        </div>
        <div class="col-md-2">
            <input type="hidden" class="form-control" name="notificacion_puerto" id="notificacion_puerto" placeholder="0"  value="587">
        </div>
    </div>

    <div class="form-group row" style="display: none;">
        <div class="col-md-2">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="notificacion_ttls" name="notificacion_ttls"  checked>
                    TTLS
                </label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="notificacion_auth" name="notificacion_auth"  checked>
                    Auth
                </label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <button type="button" id="btn-test-notificacion-cuenta" data-id="-1" class="btn btn-info pull-right" style="display:none">Probar cuenta</button>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <div class="callout callout-warning">
                <div style="font-size: 1.2em">
                    <p>
                        <i class="icon fa fa-warning"></i> Asegúrese de usar una contraseña de aplicación, en el siguiente enlace puedes ver cómo obtener esta contraseña de aplicación <a href="https://support.google.com/accounts/answer/185833?hl=es-419" target="_blank" rel="noopener noreferrer">Iniciar sesión con contraseñas de aplicación</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <button type="button" id="updateNotifications" class="btn btn-success btn-sm pull-right"><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>

</form>