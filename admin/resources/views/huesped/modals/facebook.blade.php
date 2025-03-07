<div class="modal fade" id="modal-facebook" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-facebook" method="POST">
                    @csrf
                    <input type="hidden" id="accion">
                    <input type="hidden" id="id_facebook">
                    
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Nombre</label>
                            <input type="text" name="f_nombre" id="fNombre" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for="">Identificador de facebook</label>
                            <input type="text" name="f_identificador" id="fIdentificador" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Token</label>
                            <input type="text" name="f_token" id="fToken" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Webhook</label>
                            <input type="text" id="webhookFacebook" class="form-control" readonly value="https://middleware.dyalogo.cloud:3001/dymdw/api/facebook/msgin">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-guardar-facebook">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
            
        </div>     
    </div>
</div>
