
<div class="modal fade" id="modal-patronesPaises" role="dialog">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Lista de patrones por pais</h4>
        </div>
        <div class="modal-body">
            <form action="" method="POST">          
                <label>Pais</label>
                <div class="form-group row">
                    <div class="col-md-6">
                        <select name="listaPais" id="listaPais" class="form-control">
                            <optgroup label="Norteamerica">
                                <option value="7">Canadá</option>
                                <option value="15">Estados Unidos</option>
                                <option value="20">Mexico</option>
                            </optgroup>
                            <optgroup label="Caribe">
                                <option value="2">Aruba</option>
                                <option value="3">Bahamas</option>
                                <option value="11">Cuba</option>
                                <option value="17">Haití</option>
                                <option value="19">Jamaica</option>
                                <option value="25">Puerto Rico</option>
                                <option value="26">República Dominicana</option>
                            </optgroup>
                            <optgroup label="Centro America">
                                <option value="4">Belice</option>
                                <option value="10">Costa Rica</option>
                                <option value="13">El Salvador</option>
                                <option value="16">Guatemala</option>
                                <option value="18">Honduras</option>
                                <option value="21">Nicaragua</option>
                                <option value="22">Panamá</option>
                            </optgroup>
                            <optgroup label="Sudamerica">
                                <option value="1">Argentina</option>
                                <option value="5">Bolivia</option>
                                <option value="6">Brazil</option>
                                <option value="8">Chile</option>
                                <option value="9">Colombia</option>
                                <option value="12">Ecuador</option>
                                <option value="23">Paraguay</option>
                                <option value="24">Perú</option>
                                <option value="27">Uruguay</option>
                                <option value="28">Venezuela</option>
                            </optgroup>
                            <optgroup label="Europa">
                                <option value="14">España</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="button" id="buscarPatronByPais" class="btn btn-primary btn-sm">Buscar</button>
                        <button type="button" id="agregarPersonalizado" class="btn btn-primary btn-sm">Patron personalizado</button>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <table class="table" id="detallePatron">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Patron</th>
                                    <th>Código País / Prefijo</th>
                                    <th hidden="true">Agregar Codigo De País</th>
                                    <th hidden="true">Ejemplo Personalizado<br><small>(Este sera visible en un formulario, si la validacion falla)</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        </div>
        
    </div>
    
</div>
</div>
