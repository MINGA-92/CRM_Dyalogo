
<div id="secPatron">
    <form action="" id="form-patrones">
        @csrf
        <table class="table table-hover">
            <thead>
                <tr>
                <th>Tipo de destino</th>
                    <th>Prefijo pais</th>
                    <th>Patrón</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </form>
    {{-- <button type="button" id="btn-agregar-patron" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>Agregar manualmente</button> --}}
    <button type="button" id="btn-agregar-paises" class="btn btn-success btn-sm pull-right" style="margin-right:5px;"><i class="fa fa-plus"></i> Agregar</button>
</div>

@include('huesped.modals.patronesPaises')
