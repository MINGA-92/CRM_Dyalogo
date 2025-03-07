<div class="modal fade" id="modal-whatsapp" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-whatsapp" method="POST">
                    @csrf
                    <input type="hidden" id="accion">
                    <input type="hidden" id="id_whatsapp">

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Proveedor</label>
                            <select name="w_proveedor" id="wProveedor" class="form-control">
                                <option value="0">Seleccione...</option>
                                <option value="botmaker">Botmaker</option>
                                <option value="gupshup">Gupshup</option>
                                <option value="iatech">Iatech</option>
                                <option value="hibot">Hibot</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label style="padding-top: 20px;">
                                    <input type="checkbox" name="w_activo" id="wActivo" value="1"> Activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Nombre</label>
                            <input type="text" name="w_nombre" id="wNombre" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="">Numero de whatsapp</label>
                            <input type="text" name="w_numero" id="wNumero" class="form-control">
                        </div>
                    </div>

                    <div id="provDefault">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Id del canal</label>
                                <input type="text" name="w_canal" id="wCanal" class="form-control pr-0">
                            </div>

                            <div class="col-md-6">
                                <label for="">App id</label>
                                <input type="text" name="w_appid" id="wAppid" class="form-control pr-0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">App secret</label>
                                <input type="text" name="w_appsecret" id="wAppsecret" class="form-control pr-0">
                            </div>
                            <div class="col-md-6">
                                <label for="">Token</label>
                                <input type="text" name="w_token" id="wToken" class="form-control pr-0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Fecha de vencimiento del token</label>
                                <input type="datetime-local" name="w_vencimiento" id="wVencimiento" class="form-control pr-0">
                            </div>
                        </div>
                    </div>

                    <div id="provBotmaker">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Cliente id</label>
                                <input type="text" name="p1ClienteId" id="p1ClienteId" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="">Secret id</label>
                                <input type="text" name="p1SecretId" id="p1SecretId" class="form-control pr-0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Token</label>
                                <input type="text" name="p1Token" id="p1Token" class="form-control pr-0">
                            </div>
                        </div>
                    </div>

                    <div id="provIatech">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Escenario id</label>
                                <input type="text" name="p2EscId" id="p2EscId" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="">Usuario</label>
                                <input type="text" name="p2Usuario" id="p2Usuario" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Contraseña</label>
                                <input type="text" name="p2Contrasena" id="p2Contrasena" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div id="provGupshup">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">App name</label>
                                <input type="text" name="p3AppName" id="p3AppName" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="">Api key</label>
                                <input type="text" name="p3ApiKey" id="p3ApiKey" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">App id (solo se requiere para creación de plantillas)</label>
                                <input type="text" name="p3AppId" id="p3AppId" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="">Token (solo se requiere para creación de plantillas)</label>
                                <input type="text" name="p3Token" id="p3Token" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Webhook</label>
                            <input type="text" id="webhook" class="form-control" readonly value="https://middleware.dyalogo.cloud:3001/dymdw/api/whatsapp/msgin/PROVEEDOR/CUENTA_WHATSAPP">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-guardar-whatsapp">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
