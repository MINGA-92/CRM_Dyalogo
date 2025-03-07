<div class="modal fade" id="modal-usuario-crear" role="dialog">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <form action="" id="form-usuario-crear" method="POST">
                @csrf
                               
                <h4>Datos del usuario</h4>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="usu_nombre">Nombre</label>
                        <input type="text" class="form-control" id="usu_nombre" name="usu_nombre" placeholder="Ingresa el nombre del usuario">
                    </div>

                    <div class="col-md-6">
                        <label for="usu_correo">Correo electrónico</label>
                        <input type="email" class="form-control" id="usu_correo" name="usu_correo" placeholder="Ingresa el correo electrónico">
                    </div>

                    <div class="col-md-6">
                        <label for="usu_identificacion">Identificacion</label>
                        <input type="text" class="form-control" id="usu_identificacion" name="usu_identificacion" placeholder="Ingresa el numero de identificación " pattern="[0-9]+">
                    </div>
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btn-store-usuario">Guardar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
        
    </div>
    
</div>
</div>
                    