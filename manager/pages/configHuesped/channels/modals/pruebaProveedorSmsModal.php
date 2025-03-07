<div class="modal fade" id="modal-prueba-proveedorSms" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Prueba cuenta de SMS</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-prueba-proveedor-sms" method="POST">
                    <input type="hidden" id="id_proveedorSms" name="id_proveedorSms">
                    <input type="hidden" id="id_huesped" name="id_huesped">
                    <h4 class="nombre-proveedor-sms"></h4>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="sms_telefono">Número de teléfono</label>
                            <input type="text" name="sms_telefono" id="sms_telefono" class="form-control" placeholder="CodigoPais+NumeroTelefono">
                        </div>
                    </div>
    
                </form>
                
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="consoleLog" id="consoleLog" cols="30" rows="10" class="form-control text-left" readonly style="display:none">
                        </textarea>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <h3 class="text-success pull-left estado-test-sms" style="margin-top:0px;display:none"></h3>
                <button type="button" class="btn btn-primary" id="btn-probar-proveedor-sms">Iniciar prueba</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                <div class="row">
                    <div class="col-md-12">
                        <h4 style="text-align: center;">Despues de iniciar la prueba espere a que el sistema termine de cargar y devuelva una respuesta</h4>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>
        