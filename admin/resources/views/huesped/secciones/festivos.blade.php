<div class="panel box box-primary e-collapse">
    <div class="box-header with-border contacto-header">
        <h4 class="box-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFestivos">
            FESTIVOS Y DÍAS DE NO DISPONIBILIDAD
        </a>
        </h4>
    </div>
    <div id="collapseFestivos" class="panel-collapse collapse">
        <div class="box-body">
            <h4 class="text-aqua text-festivo"></h4>
            <button type="button" id="btn-generar-festivo" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i>Generar festivos año actual y siguiente</button>
            <form action="" id="form-festivos">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button type="button" id="btn-agregar-fecha" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Agregar fecha a la lista</button>
                <button type="button" id="btn-guardar-cambios-fecha" class="btn btn-primary btn-sm pull-right" style="margin-right:5px;"><i class="fa fa-save"></i> Guardar cambios</button>
            </form>
        </div>
    </div>
</div>
