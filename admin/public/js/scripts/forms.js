/** 
 * Obtiene el formulario de registro
 * @return string
*/
function getFormHuesped(){
    const html = `
    <div class="col-md-12">
        <form action="huesped" id="form-huesped" class="new" method="POST" enctype="multipart/form-data" style="margin-top:30px">
                <h3>REGISTRAR HUÉSPED</h3>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre">
                    </div>
                    <div class="col-md-3">
                        <label for="razon_social">Razón Social</label>
                        <input type="text" class="form-control" name="razon_social" placeholder="Ingresa la razón social">
                    </div>
                    <div class="col-md-3">
                        <label for="nit">NIT</label>
                        <input type="text" class="form-control" name="nit" placeholder="Ingresa el NIT">
                    </div>
                    <div class="col-md-6">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingresa el correo electrónico">
                    </div>

                    <div class="col-md-6">
                        <label for="identificacion">Identificacion</label>
                        <input type="text" class="form-control" id="identificacion" name="identificacion" placeholder="Ingresa el numero de identificación ">
                    </div>
                </div>

                <div class="form-group row">
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

                    <div class="col-md-6">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" name="direccion" placeholder="Ingresa la Dirección">
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="telefono1">Teléfono 1</label>
                        <input type="tel" class="form-control" name="telefono1" placeholder="Ingresa el numero de teléfono">
                    </div>
                    <div class="col-md-4">
                        <label for="telefono2">Teléfono 2 (Opcional)</label>
                        <input type="tel" class="form-control" name="telefono2" placeholder="Ingresa el numero de teléfono">
                    </div>
                    <div class="col-md-4">
                        <label for="telefono3">Teléfono 3 (Opcional)</label>
                        <input type="tel" class="form-control" name="telefono3" placeholder="Ingresa el numero de teléfono">
                    </div>                        
                </div>    

                <h3 class="text-aqua">Notificaciones</h3>
                <div class="form-group row">
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="notificar_pausas">
                                Notificar Pausas
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="notificar_sesiones">
                                Notificar Sesiones
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="emails_notificaciones">Emails para notificaciones</label>
                        <input type="text" class="form-control" name="emails_notificaciones" placeholder="Ingresa los emails">
                    </div>
                </div>

                <h3 class="text-aqua">Contacto</h3>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="contact_name">Nombre</label>
                        <input type="text" class="form-control" name="contacto_nombre" placeholder="Ingresa el nombre">
                    </div>
                    <div class="col-md-3">
                        <label for="contact_email">Email</label>
                        <input type="email" class="form-control" name="contacto_email" placeholder="Ingresa el email">
                    </div>
                    <div class="col-md-2">
                        <label for="contacto_tipo">Tipo</label>
                        <select name="contacto_tipo" id="" class="form-control">
                            <option value="T" selected>Tecnico</option>
                            <option value="P">Pagos</option>
                            <option value="F">Funcional</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="contacto_telefono1">Teléfono 1</label>
                        <input type="tel" class="form-control" name="contacto_telefono1" placeholder="Ingresa el numero de teléfono">
                    </div>
                    <div class="col-md-2">
                        <label for="contacto_telefono2">Teléfono 2</label>
                        <input type="tel" class="form-control" name="contacto_telefono2" placeholder="Ingresa el numero de teléfono">
                    </div> 
                </div>

                <h3 class="text-aqua">Archivos <small>(Formato pdf, tamaño máximo por archivo 3MB)</small></h3>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="camara_comercio">Cámara de comercio</label>
                        <input type="file" name="camara_comercio" id="">
                    </div>
                    <div class="col-md-6">
                        <label for="rut">RUT</label>
                        <input type="file" name="rut" id="">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="certificacion_bancaria">Certificación bancaría</label>
                        <input type="file" name="certificacion_bancaria" id="">
                    </div>
                    <div class="col-md-6">
                        <label for="orden_compra">Orden de compra</label>
                        <input type="file" name="orden_compra" id="">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="alcances">Alcances</label>
                        <input type="file" name="alcances" id="">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary pull-right save">Guardar</button>            

        </form>
    </div>
    `;

    return html;
}

/**
 * retorna estructuta acordeon en html
 */
function getEstructuraAcoordeon(){
    const html = `
    
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border"></div>
                
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-header with-border contacto-header">
                                <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    Contactos
                                </a>
                                </h4>
                                <button type="button" class="btn btn-default btn-sm add-contacto"><i class="fa fa-plus"></i></button>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="box-body" id="panel-contacto">
                                    @include('huesped.forms.contacto_edit')
                                </div>
                            </div>
                        </div>

                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    Archivos
                                </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="box-body" id="panel-archivos">
                                    @include('huesped.forms.archivos')
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>  
        </div> 
    </div> 
    `;

    return html;
}

/**
 * Obtengo solo el formulario huesped
 */
function getPartHuesped(){
    const html = `
    <div class="col-md-12">
    <form action="huesped" id="form-huesped" class="edit" method="POST" enctype="multipart/form-data">
        
            <h3>Información Principal</h3>
            <input type="hidden" name="id_huesped" id="id_huesped" value="">
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre">
                </div>
                <div class="col-md-3">
                    <label for="razon_social">Razón Social</label>
                    <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Ingresa la razón social">
                </div>
                <div class="col-md-3">
                    <label for="nit">NIT</label>
                    <input type="text" class="form-control" id="nit" name="nit" placeholder="Ingresa el NIT" >
                </div>
            </div>

            <div class="form-group row">
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
                        <option value="{{ $huespedes->first()->id_pais_ciudad }}">{{ $huespedes->first()->id_pais_ciudad }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingresa la Dirección">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="telefono1">Teléfono 1</label>
                    <input type="tel" class="form-control" id="telefono1" name="telefono1" placeholder="Ingresa el numero de teléfono">
                </div>
                <div class="col-md-4">
                    <label for="telefono2">Teléfono 2 (Opcional)</label>
                    <input type="tel" class="form-control" id="telefono2" name="telefono2" placeholder="Ingresa el numero de teléfono">
                </div>
                <div class="col-md-4">
                    <label for="telefono3">Teléfono 3 (Opcional)</label>
                    <input type="tel" class="form-control" id="telefono3" name="telefono3" placeholder="Ingresa el numero de teléfono">
                </div>                        
            </div>    

            <h3 class="text-aqua">Notificaciones</h3>
            <div class="form-group row">
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="notificar_pausas" name="notificar_pausas" >
                            Notificar Pausas
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="notificar_sesiones" name="notificar_sesiones">
                            Notificar Sesiones
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label for="emails_notificaciones">Emails para notificaciones</label>
                    <input type="text" class="form-control" id="emails_notificaciones" name="emails_notificaciones" placeholder="Ingresa los emails">

                </div>
            </div>

        
        <button type="submit" class="btn btn-primary pull-right save">Guardar cambios</button>

    </form>
    </div>
    `;

    return html;
}

/**
 * Obtengo solo el formulario contactos
 */
function getPartContacto(){
    const html = `
    <form class="edit-huesped-contacto" action="huesped/add-new-contacto" method="POST">
        <div class="form-group row">
            <div class="col-md-3">
                <label for="contact_name">Nombre</label>
                <input type="text" class="form-control" id="contact_nombre" name="contact_nombre" placeholder="Ingresa el nombre" >
            </div>
            <div class="col-md-3">
                <label for="contact_email">Email</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Ingresa el email" >
            </div>
            <div class="col-md-2">
                <label for="contacto_tipo">Tipo</label>
                <select name="contacto_tipo" id="" class="form-control">
                    <option value="T" >Tecnico</option>
                    <option value="P" >Pagos</option>
                    <option value="F" >Funcional</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="contacto_telefono1">Teléfono 1</label>
                <input type="tel" class="form-control" id="contact_telefono1" name="contacto_telefono1" placeholder="Ingresa el numero de teléfono" >
            </div>
            <div class="col-md-2">
                <label for="contacto_telefono2">Teléfono 2</label>
                <input type="tel" class="form-control" id="contact_telefono1" name="contacto_telefono2" placeholder="Ingresa el numero de teléfono" >
            </div> 
        </div>

        <button type="submit" class="btn btn-primary save">Guardar cambios</button>

    </form>
    `;

    return html;
}

/**
 * Obtengo solo el formulario archivo
 */
function getPartArchivo(){
    const html = `
    <table class="table">
    <tr>
        <th>Tipo de archivo</th>
        <th>Descargar</th>
        <th colspan="2">Subir nuevo archivo</th>
    </tr>
    <tr>
        <th>Cámara de comercio</th>
        <td>
            <a href=" {{ url('huesped/file/'.$huesped->first()->id.'/camara_comercio.pdf') }} " class="btn btn-success btn-sm camara_comercio" target="_blank"> <i class="fa fa-download"></i> </a>
        </td>
        <td>
            <form action="huesped/subir-archivo" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="camara_comercio">
                
                <div class="input-group input-group-sm">
                    <input type="file" class="form-control" name="file">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-upload"></i></button>
                    </span>
                </div>               

            </form>
        </td>
    </tr>

    <tr>
        <th>RUT</th>
        <td>
            <a href=" {{ url('huesped/file/'.$huesped->first()->id.'/rut.pdf') }} " class="rut btn btn-success btn-sm" target="_blank"><i class="fa fa-download"></i></a>
        </td>
        <td>
            <form action="huesped/subir-archivo" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="rut">
                
                <div class="input-group input-group-sm">
                    <input type="file" class="form-control" name="file">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-upload"></i></button>
                    </span>
                </div>               

            </form>
        </td>
    </tr>

    <tr>
        <th>Certificación bancaría</th>
        <td>
            <a href=" {{ url('huesped/file/'.$huesped->first()->id.'/certificacion_bancaria.pdf') }} " class="certificacion_bancaria btn-success btn-sm" target="_blank"><i class="fa fa-download"></i></a>
        </td>
        <td>
            <form action="huesped/subir-archivo" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="certificacion_bancaria">
                
                <div class="input-group input-group-sm">
                    <input type="file" class="form-control" name="file">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-upload"></i></button>
                    </span>
                </div>               

            </form>
        </td>
    </tr>

    <tr>
        <th>Orden de compra</th>
        <td>
            <a href=" {{ url('huesped/file/'.$huesped->first()->id.'/orden_compra.pdf') }} " class="orden_compra btn-success btn-sm" target="_blank"><i class="fa fa-download"></i></a>
        </td>
        <td>
            <form action="huesped/subir-archivo" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="orden_compra">
                
                <div class="input-group input-group-sm">
                    <input type="file" class="form-control" name="file">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-upload"></i></button>
                    </span>
                </div>               

            </form>
        </td>
    </tr>

    <tr>
        <th>Alcances</th>
        <td>
            <a href=" {{ url('huesped/file/'.$huesped->first()->id.'/alcances.pdf') }} " class="alcances btn-success btn-sm" target="_blank"><i class="fa fa-download"></i></a>
        </td>
        <td>
            <form action="huesped/subir-archivo" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="alcances">
                
                <div class="input-group input-group-sm">
                    <input type="file" class="form-control" name="file">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-upload"></i></button>
                    </span>
                </div>               

            </form>
        </td>
    </tr>
</table>


    `;

    return html;
}
