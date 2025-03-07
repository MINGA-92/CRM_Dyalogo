<div class="panel box box-primary e-collapse">
    <div class="box-header with-border contacto-header">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseCanal">
                CANALES DE COMUNICACIÓN
            </a>
        </h4>
    </div>
    <div id="collapseCanal" class="panel-collapse collapse">
        <div class="box-body">
            <h4 class="text-aqua">Voz</h4>
            @include('huesped.secciones.troncales')

            <h4 class="text-aqua">Tipos de destinos a los que va a marcar</h4>
            @include('huesped.secciones.patrones')

            <br><hr>

            <h4 class="text-aqua">Email <i class="fa fa-question-circle" id="abrirAyudaEmail"></i></h4>
            @include('huesped.secciones.cuentasCorreo')

            <br><hr>

            <h4 class="text-aqua">SMS</h4>
            @include('huesped.secciones.proveedoresSms')

            <br><hr>

            <h4 class="text-aqua">WebService</h4>
            @include('huesped.secciones.webservice')

            <br><hr>

            <h4 class="text-aqua">Whatsapp</h4>
            @include('huesped.secciones.whatsapp')

            <br><hr>

            <h4 class="text-aqua">Plantillas de whatsapp</h4>
            @include('huesped.secciones.whatsappPlantillas')

            <br><hr>

            <h4 class="text-aqua">Facebook messenger</h4>
            @include('huesped.secciones.facebook')

            <h4 class="text-aqua">Instagram</h4>
            @include('huesped.secciones.instagram')
        </div>
    </div>
</div>
