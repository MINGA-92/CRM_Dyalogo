<div class="modal fade" id="modal-huesped" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registro de huésped</h4>
            </div>
            
            <form action="/huesped" id="form-create-huesped" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <h3>INFORMACION PRINCIPAL</h3>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre">
                        </div>
                    </div>

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
                        <div class="col-md-10">
                            <label for="emails_notificaciones">Emails para notificaciones</label>
                            <input type="text" class="form-control" name="emails_notificaciones" placeholder="Ingresa los emails">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="razon_social">Razón Social</label>
                            <input type="text" class="form-control" name="razon_social" placeholder="Ingresa la razón social">
                        </div>
                        <div class="col-md-6">
                            <label for="nit">NIT</label>
                            <input type="text" class="form-control" name="nit" placeholder="Ingresa el NIT">
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

                    <h3>CONTACTO</h3>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label for="contact_name">Nombre</label>
                            <input type="text" class="form-control" name="contacto_nombre" placeholder="Ingresa el nombre">
                        </div>
                        <div class="col-md-4">
                            <label for="contact_email">Email</label>
                            <input type="email" class="form-control" name="contacto_email" placeholder="Ingresa el email">
                        </div>
                        <div class="col-md-3">
                            <label for="contacto_tipo">Tipo</label>
                            <select name="contacto_tipo" id="" class="form-control">
                                <option value="T" selected>T</option>
                                <option value="P">P</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="contacto_telefono1">Teléfono 1</label>
                            <input type="tel" class="form-control" name="contacto_telefono1" placeholder="Ingresa el numero de teléfono">
                        </div>
                        <div class="col-md-4">
                            <label for="contacto_telefono2">Teléfono 2</label>
                            <input type="tel" class="form-control" name="contacto_telefono2" placeholder="Ingresa el numero de teléfono">
                        </div>                      
                    </div>  

                    <h3>CARGA DE ARCHIVOS</h3>
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
                            <label for="certificado_bancario">Certificación bancaría</label>
                            <input type="file" name="certificado_bancario" id="">
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

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary save">Guardar</button>
                </div>
            </form>
        </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->