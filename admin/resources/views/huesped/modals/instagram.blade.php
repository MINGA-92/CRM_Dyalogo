<div class="modal fade" id="modal-instagram" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-instagram" method="POST">
                    @csrf
                    <input type="hidden" id="accion" value="add">
                    <input type="hidden" id="id_instagram" value="0">
                    
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Nombre</label>
                            <input type="text" name="i_nombre" id="iNombre" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <div class="checkbox">
                                <label style="padding-top: 20px;">
                                    <input type="checkbox" name="i_activo" id="iActivo" value="1" checked> Activo
                                </label>
                            </div>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Page-id asociada a la cuenta de Instagram</label>
                            <input type="text" name="i_identificador" id="iIdentificador" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="">Identificadores de acceso(Token)</label>
                            <input type="text" name="i_token" id="iToken" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Webhook</label>
                            <input type="text" id="webhookInstagram" class="form-control" readonly value="https://middleware.dyalogo.cloud:3001/dymdw/api/instagram/PAGE_ID/msgin">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-guardar-instagram">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
            
        </div>     
    </div>
</div>