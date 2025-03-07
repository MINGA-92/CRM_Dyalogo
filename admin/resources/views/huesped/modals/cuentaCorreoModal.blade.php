<div class="modal fade" id="modal-cuentaCorreo" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-cuenta-correo" method="POST">
                    @csrf

                    <input type="hidden" id="accion">
                    <input type="hidden" id="cc_id">
                    <h3>CONFIGURACIÓN</h3>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control input-sm" placeholder="Ingresa un nombre">
                        </div>
                        <div class="col-md-3">
                            <label for="direccion_correo_electronico">Correo electrónico</label>
                            <input type="text" name="direccion_correo_electronico" id="direccion_correo_electronico" class="form-control input-sm" placeholder="Ingresa un correo electrónico">
                        </div>
                        <div class="col-md-3">
                            <label for="usuario">Usuario</label>
                            <input type="text" name="usuario" id="usuario" class="form-control input-sm" placeholder="Ingresa un usuario">
                        </div>
                        <div class="col-md-3">
                            <label for="contrasena">Contraseña</label>
                            <input type="password" name="contrasena" id="contrasena" class="form-control input-sm" placeholder="Ingresa una contraseña">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="proveedor">Proveedor</label>
                            <select name="proveedor" id="proveedor" class="form-control">
                                <option value="">Seleccionar...</option>
                                <option value="gmail">Gmail/Google</option>
                                <option value="microsoft">Microsoft</option>
                                <option value="infobip">Infobip</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="mensaje_google" style="display: none;">
                        <div class="col-md-12">
                            <div class="callout callout-warning">
                                <div style="font-size: 1.2em">
                                    <p>
                                        <i class="icon fa fa-warning"></i> Si se configura una cuenta de Google la contraseña que se usara debe ser una contraseña de aplicación, en el siguiente enlace puedes ver cómo realizar esa configuración <a href="https://support.google.com/accounts/answer/185833?hl=es-419" target="_blank" rel="noopener noreferrer">Iniciar sesión con contraseñas de aplicación</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-aqua">Servidor saliente</h4>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="servidor_saliente_direccion">Dirección</label>
                            <input type="text" name="servidor_saliente_direccion" id="servidor_saliente_direccion" class="form-control input-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="servidor_saliente_tipo">Tipo</label>
                            <select name="servidor_saliente_tipo" id="servidor_saliente_tipo" class="form-control input-sm">
                                <option value="">Seleccionar...</option>
                                <option value="1">SMTP</option>
                                <option value="2">POP3</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="servidor_saliente_puerto">Puerto</label>
                            <input type="text" name="servidor_saliente_puerto" id="servidor_saliente_puerto" class="form-control input-sm">
                        </div>
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="servidor_saliente_usar_auth" name="servidor_saliente_usar_auth">
                                    Usar Auth
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="servidor_saliente_usar_start_ttls" name="servidor_saliente_usar_start_ttls">
                                    Usar start ttls
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="servidor_saliente_usar_ssl" name="servidor_saliente_usar_ssl">
                                    Usar ssl
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="s-entrante">
                    <h4 class="text-aqua">Servidor entrante</h4>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="protocoloEntrada">Protocolo de entrada</label>
                            <select name="protocoloEntrada" id="protocoloEntrada" class="form-control">
                                <option value="imaps">imaps(default)</option>
                                <option value="imap">imap</option>
                                <option value="pop3">pop3</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="servidor_entrante_direccion">Dirección</label>
                            <input type="text" name="servidor_entrante_direccion" id="servidor_entrante_direccion" class="form-control input-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="servidor_entrante_tipo">Tipo</label>
                            <select name="servidor_entrante_tipo" id="servidor_entrante_tipo" class="form-control input-sm">
                                <option value="">Seleccionar...</option>
                                <option value="2">POP3</option>
                                <option value="3">IMAP</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="servidor_entrante_puerto">Puerto</label>
                            <input type="text" name="servidor_entrante_puerto" id="servidor_entrante_puerto" class="form-control input-sm">
                        </div>
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="servidor_entrante_usar_auth" name="servidor_entrante_usar_auth">
                                    Usar Auth
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="servidor_entrante_usar_start_ttls" name="servidor_entrante_usar_start_ttls">
                                    Usar start ttls
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="servidor_entrante_usar_ssl" name="servidor_entrante_usar_ssl">
                                    Usar ssl
                                </label>
                            </div>
                        </div>
                    </div>
                    </div>

                    <h4 class="text-aqua">Saliente</h4>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="saliente_responder_a">Responder a</label>
                            <input type="text" name="saliente_responder_a" id="saliente_responder_a" class="form-control input-sm">
                        </div>
                        <div class="col-md-4">
                            <label for="saliente_nombre_remitente">Nombre remitente</label>
                            <input type="text" name="saliente_nombre_remitente" id="saliente_nombre_remitente" class="form-control input-sm">
                        </div>
                    </div>

                    <hr>

                    <h3>AVANZADO</h3>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado" class="form-control input-sm">
                                <option value="">Seleccionar...</option>
                                <option value="1">Activo</option>
                                <option value="2">Detenido</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="intervalo_refresque">Intervalo refresque</label>
                            <input type="text" name="intervalo_refresque" id="intervalo_refresque" value="300" class="form-control input-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="buzon">Buzón</label>
                            <input type="text" name="buzon" id="buzon" class="form-control input-sm" value="INBOX">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="borrar_correos_procesados" name="borrar_correos_procesados">
                                    Borrar correos procesados
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="mensajes_estado">Mensajes estado</label>
                            <input type="text" readonly name="mensajes_estado" id="mensajes_estado" class="form-control input-sm">
                        </div>
                    </div>

                </form>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <h3>
                                <i class="icon fa fa-warning"></i>
                                CONFIGURACIÓN ADICIONAL
                            </h3>

                            <div style="font-size: 1.2em">
                                <p>
                                    Después de realizar la configuración debes tener en cuenta que el correo electrónico que estas por utilizar
                                    debe tener habilitado los puertos de conexiones <strong>SMTP</strong> para <strong>conexiones salientes</strong> y el puerto <strong>IMAP O POP</strong> para <strong>conexiones entrantes</strong>.
                                </p>
                                <p>
                                    Puedes ver el proceso de como configurarlo a través de estas URL:
                                </p>
                                <p id="link_configuracion_google"><strong>*Google - <a href="https://support.google.com/accounts/answer/6010255#zippy=" target="_blank">https://support.google.com/accounts/answer/6010255#zippy=</a></strong></p>
                                <p id="pdf_apps_poco_seguras_gmail" style="margin-left:70px"><strong><a href="{{asset('pdf/Configurar-correo-google-smtp-e-imap.pdf')}}" target="_blank">Configurar-acceso-aplicaciones-poco-seguras-e-imap.pdf</a></strong></p>
                                <p id="link_configuracion_microsoft"><strong>*Microsoft - <a href="https://support.microsoft.com/es-es/office/configuraci%C3%B3n-pop-imap-y-smtp-para-outlook-com-d088b986-291d-42b8-9564-9c414e2aa040" target="_blank">https://support.microsoft.com/es-es/office/configuraci%C3%B3n-pop-imap-y-smtp-para-outlook-com-d088b986-291d-42b8-9564-9c414e2aa040</a></strong></p>
                                <p id="link_configuracion_otros">
                                <strong>*Para otros proveedores consulte la documentación de su proveedor de correo electrónico para tener estas opciones habilitadas en su cuenta</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-guardar-cuenta-correo">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>

        </div>

    </div>

</div>
