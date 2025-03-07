<h3 class="title text-aqua">Registrar huésped</h3>

<div class="panel box box-primary">
    <div class="box-header with-border">
        <h4 class="box-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseHuesped">
            INFORMACIÓN DEL HUÉSPED
        </a>
        </h4>
    </div>
    <div id="collapseHuesped" class="panel-collapse collapse in">
        <div class="box-body">
            @include('huesped.forms.huesped_form')

            <h4 class="text-aqua">Contactos del huésped</h4>
            <div id="panel-contacto">
                @include('huesped.forms.contacto')
            </div>
            <button type="button" id="add-contacto" title="Crear nuevo contacto" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Agregar contacto</button>

            <br>
            
            <h4 class="text-aqua">Archivos del huésped</h4>
            <div id="panel-archivos">
                @include('huesped.forms.archivos')
            </div>
        </div>
    </div>
</div>
