<form action="{{ route('huesped.store') }}" method="POST" id="form-huesped" class="" enctype="multipart/form-data">
    @csrf
    <input style="display:none">
    <input type="password" style="display:none">
    <h4 class="text-aqua">Datos del huésped</h4>
    <input type="hidden" name="id" id="id">
    <div class="form-group row">
        <div class="col-md-6">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre">
        </div>

        <div class="col-md-6">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingresa la Dirección">
        </div>
    
        
    </div>

    <div class="form-group row">
        <div class="col-md-3">
            <label for="razon_social">Razón Social</label>
            <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Ingresa la razón social"">
        </div>
        <div class="col-md-3">
            <label for="nit">NIT</label>
            <input type="text" class="form-control" id="nit" name="nit" placeholder="Ingresa el NIT">
        </div>

        <div class="col-md-3">
            <label for="pais">Pais</label>
            <select name="pais" id="pais" class="form-control">
                <option value=''>Seleccionar</option>
                @foreach ($paises as $pais)
                    <option value="{{ $pais->pais }}">{{ $pais->pais }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="ciudad">Ciudad</label>
            <select name="ciudad" id="ciudad" class="form-control">
                <option value=''>Seleccionar</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-3">
            <label for="telefono1">Teléfono 1</label>
            <input type="tel" class="form-control" id="telefono1" name="telefono1" placeholder="Ingresa el numero de teléfono" autocomplete="off">
        </div>
        <div class="col-md-3">
            <label for="telefono2">Teléfono 2 (Opcional)</label>
            <input type="tel" class="form-control" id="telefono2" name="telefono2" placeholder="Ingresa el numero de teléfono" autocomplete="off">
        </div>
        <div class="col-md-3">
            <label for="telefono3">Teléfono 3 (Opcional)</label>
            <input type="tel" class="form-control" id="telefono3" name="telefono3" placeholder="Ingresa el numero de teléfono" autocomplete="off">
        </div>                        
    </div>

    <div class="form-group row" style="display: none;">
        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="fotoUsuarioObligatoria" name="fotoUsuarioObligatoria" form="form-huesped">
                    Foto de usuarios obligatoria
                </label>
            </div>
        </div>
    </div>
</form>
