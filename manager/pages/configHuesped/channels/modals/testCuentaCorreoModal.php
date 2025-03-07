<div class="modal fade" id="modal-test-cuentaCorreo" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Prueba cuenta de correo</h4>
            </div>
            <div class="modal-body">

                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="callout callout-info">

                            <div style="font-size: 1.2em">
                                <p>
                                    <i class="icon fa fa-warning"></i>
                                    Recuerde haber realizado la configuración de su cuenta de correo electrónico habilitando el puerto de entrada IMAP o POP y el puerto de salida SMTP.
                                </p>
                                <p>
                                    Puedes ver el proceso de como configurarlo a través de estas URL:
                                </p>
                                <p id="link_configuracion_google"><strong>Google - <a href="https://support.google.com/accounts/answer/6010255#zippy=" target="_blank">https://support.google.com/accounts/answer/6010255#zippy=</a></strong></p>
                                <p id="link_configuracion_microsoft"><strong>Microsoft - <a href="https://support.microsoft.com/es-es/office/configuraci%C3%B3n-pop-imap-y-smtp-para-outlook-com-d088b986-291d-42b8-9564-9c414e2aa040" target="_blank">https://support.microsoft.com/es-es/office/configuraci%C3%B3n-pop-imap-y-smtp-para-outlook-com-d088b986-291d-42b8-9564-9c414e2aa040</a></strong></p>
                                <p id="link_configuracion_otros">
                                    <strong>Para otros proveedores consulte la documentación de su proveedor de correo electrónico para tener estas opciones habilitadas en su cuenta</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="" id="form-test-cuenta-correo" method="POST">
                    <input type="hidden" id="id_cuentaCorreo" name="id_cuentaCorreo">
                    <input type="hidden" id="cuentaCorreoUsuario" name="cuentaCorreoUsuario">
                    <input type="hidden" id="id_huesped" name="id_huesped">
                    <h4 class="cuenta-correo-nombre"></h4>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="destinatario">Destinatario</label>
                            <input type="text" name="destinatario" id="destinatario" class="form-control" placeholder="Correo del destinatario">
                        </div>
                    </div>
    
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <textarea name="consoleLogCorreo" id="consoleLogCorreo" cols="30" rows="10" class="form-control text-left" readonly style="display:none">
                        </textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-success pull-left estado-test-mail-salida" style="margin-top:0px;display:none"></h3><br>
                    </div>
                    <div class="col-md-12">
                        <h3 class="text-success pull-left estado-test-mail-entrada" style="margin-top:0px;display:none"></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" id="btn-test-cuenta">Iniciar prueba</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>       
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h4 style="text-align: center;">Después de iniciar la prueba espere a que el sistema termine de cargar y devuelva una respuesta, este proceso puede demorar dependiendo del proveedor del correo electrónico</h4>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
</div>
        