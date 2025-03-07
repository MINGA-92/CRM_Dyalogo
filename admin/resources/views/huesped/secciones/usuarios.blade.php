<div class="panel box box-primary e-collapse">
    <div class="box-header with-border">
        <h4 class="box-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseUsuarios">
            PERMISOS DE USUARIOS
        </a>
        </h4>
    </div>
    <div id="collapseUsuarios" class="panel-collapse collapse">
        <div class="box-body">
            <h4 class="text-aqua">Usuarios</h4>
            <form action="" id="form-usuarios">
                @csrf
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Correo electronico</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </form>
            <br>
            <button type="button" id="btn-asignar-usuario" class="btn btn-success btn-sm pull-right"><i class="fa fa-link"></i> Asociar usuarios inter-huésped</button>
        </div>
    </div>
</div>

@include('huesped.modals.usuarioAsignar')
