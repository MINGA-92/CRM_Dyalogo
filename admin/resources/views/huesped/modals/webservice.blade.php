<div class="modal fade" id="modal-webservice" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-webservice" method="POST">
                    @csrf
                    <input type="hidden" id="wsAccion" name="wsAccion">
                    <input type="hidden" id="wsId" name="wsId">
                    <input type="hidden" id="wsCantHeaders" name="wsCantHeaders" value="0">
                    <input type="hidden" id="wsCantParametros" name="wsCantParametros" value="0">
                    <input type="hidden" id="wsCantParametrosRetorno" name="wsCantParametrosRetorno" value="0">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Nombre</label>
                            <input type="text" name="ws_nombre" id="wsNombre" class="form-control input-sm" placeholder="Untitled Request">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="">Metodo</label>
                            <select name="wsMetodo" id="wsMetodo" class="form-control input-sm">
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                                <option value="PUT">PUT</option>
                                <option value="PATCH">PATCH</option>
                                <option value="DELETE">DELETE</option>
                            </select>
                        </div>

                        <div class="col-md-9">
                            <label for="">URL</label>
                            <input type="text" name="wsUrl" id="wsUrl" class="form-control input-sm" placeholder="Ingrese la URL de la solicitud">
                        </div>
                    </div>

                    <h4 class="text-aqua">Headers</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id="tableHeaders">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Valor</th>
                                        <th>Descripcion</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm pull-right" id="agregarHeaderWebservice">
                                <i class="fa fa-plus"></i> Agregar header
                            </button>
                        </div>
                    </div>

                    <h4 class="text-aqua">Parametros</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id="tableParametros">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm pull-right" id="agregarParametrosWebservice">
                                <i class="fa fa-plus"></i> Agregar parametro
                            </button>
                        </div>
                    </div>

                    <h4 class="text-aqua">Parametros de retorno</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id="tableParametrosRetorno">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm pull-right" id="agregarParametrosRetornoWebservice">
                                <i class="fa fa-plus"></i> Agregar parametro de retorno
                            </button>
                        </div>
                    </div>

                    <h4 class="text-aqua">Invocaciones previas</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="requiereInvocaciones">
                                    <input type="checkbox" name="requiereInvocaciones" id="requiereInvocaciones">
                                    <span>Requiere invocaciones</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" id="funcionRequerida" name="funcionRequerida" placeholder="Funcion requerida" class="form-control input-sm" disabled >
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-guardar-webservice">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
