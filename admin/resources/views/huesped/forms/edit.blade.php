<form action="huesped" id="form-huesped" class="edit" method="POST">
    @csrf
    <div class="">
        <input type="hidden" name="id_huesped" id="id_huesped" value="{{ $huespedes->first()->id }}" form="form-huesped">
        <h3>Información Principal</h3>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre" value="{{ $huespedes->first()->nombre }}" form="form-huesped">
            </div>
        
            <div class="col-md-3">
                <label for="razon_social">Razón Social</label>
                <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Ingresa la razón social" value="{{ $huespedes->first()->razon_social }}" form="form-huesped">
            </div>
            <div class="col-md-3">
                <label for="nit">NIT</label>
                <input type="text" class="form-control" id="nit" name="nit" placeholder="Ingresa el NIT" value="{{ $huespedes->first()->nit }}" form="form-huesped">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label for="pais">Pais</label>
                <select name="pais" id="pais" class="form-control" form="form-huesped">
                    <option value=''>Seleccionar</option>
                    @foreach ($paises as $pais)
                        <option value="{{ $pais->pais }}">{{ $pais->pais }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="ciudad">Ciudad</label>
                <select name="ciudad" id="ciudad" class="form-control" form="form-huesped">
                    <option value=''>Seleccionar</option>
                    <option value="{{ $huespedes->first()->id_pais_ciudad }}">{{ $huespedes->first()->id_pais_ciudad }}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingresa la Dirección" value="{{ $huespedes->first()->direccion }}" form="form-huesped">
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-md-4">
                <label for="telefono1">Teléfono 1</label>
                <input type="tel" class="form-control" id="telefono1" name="telefono1" placeholder="Ingresa el numero de teléfono" value="{{ $huespedes->first()->telefono1 }}" form="form-huesped">
            </div>
            <div class="col-md-4">
                <label for="telefono2">Teléfono 2 (Opcional)</label>
                <input type="tel" class="form-control" id="telefono2" name="telefono2" placeholder="Ingresa el numero de teléfono" value="{{ $huespedes->first()->telefono2 }}" form="form-huesped">
            </div>
            <div class="col-md-4">
                <label for="telefono3">Teléfono 3 (Opcional)</label>
                <input type="tel" class="form-control" id="telefono3" name="telefono3" placeholder="Ingresa el numero de teléfono" value="{{ $huespedes->first()->telefono3 }}" form="form-huesped">
            </div>                        
        </div>    

        <h3 class="text-aqua">Notificaciones</h3>
        <div class="form-group row">
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="notificar_pausas" name="notificar_pausas" @if($huespedes->first()->notificar_pausas) checked @endif form="form-huesped">
                        Notificar Pausas
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="notificar_sesiones" name="notificar_sesiones" @if($huespedes->first()->notificar_sesiones) checked @endif form="form-huesped">
                        Notificar Sesiones
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <label for="emails_notificaciones">Emails para notificaciones</label>
                <input type="text" class="form-control" id="emails_notificaciones" name="emails_notificaciones" placeholder="Ingresa los emails" value="{{ $huespedes->first()->emails_notificaciones }}" form="form-huesped">

            </div>
        </div>

    </div>
    <button type="submit" class="btn btn-primary pull-right save">Guardar cambios</button>

</form>
