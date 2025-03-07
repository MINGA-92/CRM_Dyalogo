
<div class="modal fade" id="modal-troncal" role="dialog">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <form action="" id="form-troncal" method="POST">
                <input type="hidden" id="accion">
                <input type="hidden" id="id_troncal">

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="troncal_nombre_usuario">Nombre</label>
                        <input type="text" name="troncal_nombre_usuario" id="troncal_nombre_usuario" class="form-control">
                    </div>

                    <div class="col-md-6" style="display: none;">
                        <label for="troncal_codigo_cuenta">Codigo Cuenta</label>
                        <input type="text" name="troncal_codigo_cuenta" id="troncal_codigo_cuenta" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label hidden>
                                <input type="checkbox" id="troncal_usar_codigo_antepuesto" name="usar_codigo_antepuesto" value="0">
                                Número Antepuesto
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <input type="text" name="troncal_codigo_antepuesto" id="troncal_codigo_antepuesto" class="form-control input-sm" style="display: none" placeholder="Número antepuesto">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="generarRegistro" name="generarRegistro">
                                Generar registro
                            </label>
                        </div>
                    </div>
                </div>

                <hr>

                <h4>PROPIEDADES DE LA TRONCAL</h4>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="troncal_tipo">Tipo</label>
                        <select name="troncal_tipo" id="troncal_tipo" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="friend" selected>friend</option>
                            <option value="peer">peer</option>
                            <option value="user">user</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_direccion_servidor">Dirección servidor</label>
                        <input type="text" name="troncal_direccion_servidor" id="troncal_direccion_servidor" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_usuario_defecto">Usuario por defecto</label>
                        <input type="text" name="troncal_usuario_defecto" id="troncal_usuario_defecto" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_contrasena">Contraseña</label>
                        <input type="password" name="troncal_contrasena" id="troncal_contrasena" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="troncal_fuente">Fuente</label>
                        <input type="text" name="troncal_fuente" id="troncal_fuente" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_compensar_rfc2833">Compensar RFC2833</label>
                        <select name="troncal_compensar_rfc2833" id="troncal_compensar_rfc2833" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="yes" selected>Si</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_limite_llamadas">Limite de llamadas</label>
                        <input type="numeric" name="troncal_limite_llamadas" id="troncal_limite_llamadas" class="form-control" placeholder="0" value="0">
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_contexto">Contexto</label>
                        <input type="text" name="troncal_contexto" id="troncal_contexto" class="form-control" value="entrada">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="troncal_habilitar_puente_rtp">Habilitar puente (RTP)</label>
                        <select name="troncal_habilitar_puente_rtp" id="troncal_habilitar_puente_rtp" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="yes" selected>Si</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_autenticacion">Autenticación</label>
                        <input type="text" name="troncal_autenticacion" id="troncal_autenticacion" class="form-control" value="port,invite">
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_nat">NAT</label>
                        <select name="troncal_nat" id="troncal_nat" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="force_rport">force_rport</option>
                            <option value="comedia">comedia</option>
                            <option value="force_rport,comedia" selected>force_rport,comedia</option>
                            <option value="no">no</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="troncal_permitir_verificacion">Permitir la verificación</label>
                        <select name="troncal_permitir_verificacion" id="troncal_permitir_verificacion" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="yes" selected>Si</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>DTMF MODE</label>
                        <select name="troncal_mododtmf" id="troncal_mododtmf" class="form-control">
                            <option value="inband">INBAND</option>
                            <option value="rfc2833" selected>rfc2833</option>
                            <option value="info">info</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="troncal_codec_u" name="troncal_codec_u" checked>
                                Codec U
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="troncal_codec_a" name="troncal_codec_a" checked>
                                Codec A
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="troncal_g729" name="troncal_g729">
                                G729
                            </label>
                        </div>
                    </div>
                </div>

                <h4>PROPIEDADES ADICIONALES</h4>
                <div class="row">
                    <div class="col-md-12">
                        <ul id="propiedadesAdicionales" class="list-group">
                            
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success btn-sm pull-right" id="agregarPropiedad" style="margin-bottom: 3px; margin-left: 3px;"><i class="fa fa-plus"></i> propieadad existente</button>
                        <button type="button" class="btn btn-success btn-sm pull-right" id="agregarPropiedadNueva" style="margin-bottom: 3px;"><i class="fa fa-plus"></i>Agregar propieadad nueva</button>
                    </div>
                </div>

            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btn-guardar-troncal">Guardar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>

    </div>

</div>
</div>
