<div class="modal fade" id="modal-usuario-asignar" role="dialog">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <form action="" id="form-usuario-asignar" method="POST">
                @csrf
                <input type="hidden" id="id_usuario">
                
                <div class="form-group row">
                    <div class="col-md-8">
                        <label for="usuarioAsignar">Usuario</label>
                        <select name="usuarioAsignar" id="usuarioAsignar" class="form-control select2" style="width: 100%;">
                        
                        </select>
                    </div>
                </div>                

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btn-guardar-usuario-asignacion">Guardar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
        
    </div>
    
</div>
</div>
                