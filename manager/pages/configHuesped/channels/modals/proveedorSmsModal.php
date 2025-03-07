<div class="modal fade" id="modal-proveedorSms" role="dialog">
<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <form action="" id="form-proveedor-sms" method="POST">
                <input type="hidden" id="accion">
                <input type="hidden" id="id_proveedorSms">
                
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control input-sm">
                    </div>
                    <div class="col-md-6">
                        <label for="proveedor">Proveedor</label>
                        <select name="proveedor" id="proveedor" class="form-control input-sm">
                            <option value="">Seleccionar...</option>
                            <option value="TELINTEL">TELINTEL</option>
                            <option value="ELIBOM">ELIBOM</option>
                            <option value="IATECHSAS">IATECHSAS</option>
                            <option value="HablAme">HablAme</option>
                            <option value="dyinfobip">Infobip</option>
                            <option value="contactalos">Contactalos</option>
                        </select>
                    </div>                    
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="url_api">URL API</label>
                        <input type="text" name="url_api" id="url_api" class="form-control input-sm">
                    </div>
                    <div class="col-md-6">
                        <label for="url_api_ssl">URL API SSL</label>
                        <input type="text" name="url_api_ssl" id="url_api_ssl" class="form-control input-sm">
                    </div>
                
                    <div class="col-md-6">
                        <label for="api_key">API KEY</label>
                        <input type="text" name="api_key" id="api_key" class="form-control input-sm">
                    </div>
                    <div class="col-md-6">
                        <label for="api_secret">API SECRET</label>
                        <input type="text" name="api_secret" id="api_secret" class="form-control input-sm">
                    </div>
                
                    <div class="col-md-6">
                        <label for="">Longitud máxima</label>
                        <input type="numeric" name="longitud_maxima" id="longitud_maxima" class="form-control input-sm" value="160" placeholder="Longitud máxima de sms">
                    </div>
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btn-guardar-proveedor-sms">Guardar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
        
    </div>
    
</div>

</div>
