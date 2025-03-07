
<div id="secTroncales">
    <form action="" id="form-troncales">
        @csrf
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre de usuario</th>
                    <th>Nombre interno</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </form>
    <br>
    <button type="button" id="btn-agregar-troncal" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Agregar</button>
</div>

@include('huesped.modals.troncal')
