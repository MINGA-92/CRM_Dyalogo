<div class="modal fade" id="modalPlantillaWhatsapp" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form action="" id="formPlantillaWhatsapp" method="POST">
                    <input type="hidden" id="pAccion" name="pAccion" value="add">
                    <input type="hidden" id="plantillaId" name="plantillaId" value="0">
                    <input type="hidden" id="contParametrosPlnatilla" name="contParametrosPlnatilla" value="0">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Cuenta de whatsapp</label>
                            <select type="text" name="pCuenta" id="pCuenta" class="form-control input-sm">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Accion a realizar</label>
                            <select type="text" name="pAccionPlantilla" id="pAccionPlantilla" class="form-control input-sm">
                                <option value="registrarNuevo">Crear plantilla desde cero</option>
                                <option value="registrarExistente">Registrar plantilla creada desde la plataforma de gupshup</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="">Plantilla ID</label>
                            <input type="text" name="pPlantillaId" id="pPlantillaId" class="form-control input-sm" disabled></input>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Nombre</label>
                            <input type="text" name="pNombre" id="pNombre" class="form-control input-sm" placeholder="Asigna un nombre a la plantilla" maxlength="255">
                        </div>

                        <div class="col-md-6">
                            <label for="">Categoría</label>
                            <select name="pCategoria" id="pCategoria" class="form-control input-sm">
                                <option value="ACCOUNT_UPDATE">Actualizacion de cuenta</option>
                                <option value="PAYMENT_UPDATE">Actualizacion de pago</option>
                                <option value="PERSONAL_FINANCE_UPDATE">Actualizacion de finanzas personales</option>
                                <option value="SHIPPING_UPDATE">Actualizacion de envio</option>
                                <option value="RESERVATION_UPDATE">Actualizacion de reservas</option>
                                <option value="ISSUE_RESOLUTION">Solucion de problemas</option>
                                <option value="APPOINTMENT_UPDATE">Actualizacion de cita</option>
                                <option value="TRANSPORTATION_UPDATE">Actualizazion sobre transporte</option>
                                <option value="TICKET_UPDATE">Actualizacion sobre entradas</option>
                                <option value="ALERT_UPDATE">Actualizacion sobre alertas</option>
                                <option value="AUTO_REPLY">Auto-reply</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">

                        <div class="col-md-6">
                            <label for="">Etiquetas</label>
                            <input type="text" name="pEtiqueta" id="pEtiqueta" class="form-control input-sm" maxlength="100">
                            <span>(Defina para qué caso de uso sirve esta plantilla, por ejemplo, actualización de cuenta, OTP, etc. en 2-3 palabras)</span>
                        </div>

                        <div class="col-md-6">
                            <label for="">Idioma</label>
                            <select name="pIdioma" id="pIdioma" class="form-control input-sm">
                                <option value="af">Afrikaans</option>
                                <option value="sq">Albanian</option>
                                <option value="ar">Arabic</option>
                                <option value="az">Azerbaijani</option>
                                <option value="bn">Bengali</option>
                                <option value="bg">Bulgarian</option>
                                <option value="ca">Catalan</option>
                                <option value="zh_CN">Chinese (CHN)</option>
                                <option value="zh_HK">Chinese (HKG)</option>
                                <option value="zh_TW">Chinese (TAI)</option>
                                <option value="hr">Croatian</option>
                                <option value="cs">Czech</option>
                                <option value="da">Danish</option>
                                <option value="nl">Dutch</option>
                                <option value="en">English</option>
                                <option value="en_GB">English (UK)</option>
                                <option value="en_US">English (US)</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="hi">Hindi</option>
                                <option value="it">Italian</option>
                                <option value="ja">Japanese</option>
                                <option value="pt_BR">Portuguese (BR)</option>
                                <option value="pt_PT">Portuguese (POR)</option>
                                <option value="ru">Russian</option>
                                <option value="es" selected>Spanish</option>
                                <option value="es_AR">Spanish (ARG)</option>
                                <option value="es_ES">Spanish (SPA)</option>
                                <option value="es_MX">Spanish (MEX)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Tipo</label>
                            <select name="pTipo" id="pTipo" class="form-control input-sm">
                                <option value="TEXT">Texto</option>
                                <option value="IMAGE">Media: Imagen</option>
                                <option value="VIDEO">Media: Video</option>
                                <option value="DOCUMENT">Media: Documento</option>
                                <option value="LOCATION">Locacion</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12" id="cabeceraTexto">
                            <label for="">Cabecera (Opcional, Max 60 caracteres)</label>
                            <input type="text" name="pCabeceraTexto" id="pCabeceraTexto" class="form-control input-sm" maxlength="60">
                        </div>
                        <div class="col-md-12" id="cabeceraMedia" style="display: none;">
                            <label for="">Agregar un ejemplo de multimedia</label>
                            <input type="file" name="pCabeceraMedia" id="pCabeceraMedia">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Texto (Max 1024 caracteres)</label>
                            <textarea name="pContenido" id="pContenido" cols="20" rows="5" class="form-control input-sm" maxlength="1024" autocomplete="disabled"></textarea>
                            <span></span>
                        </div>
                    </div>

                    <h4 class="text-aqua">Variables</h4>
                    <span>Las variables deben inicializarse como 1, 2, etc. e incluir el número correcto de corchetes (es decir, 2 en el lado izquierdo del número y 2 en el lado derecho del número)</span>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id="tablePlantillaParametros">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm pull-right" id="agregarParametroPlantilla">
                                <i class="fa fa-plus"></i> Agregar parametro
                            </button>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Pie de página (Opcional, Max 60 caracteres)</label>
                            <input type="text" name="pFooterTexto" id="pFooterTexto" class="form-control input-sm" maxlength="60">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="">Botones</label>
                                <select name="pUsarBotones" id="pUsarBotones" class="form-control input-sm">
                                    <option value="ninguno">Ninguno</option>
                                    <option value="llamada_a_la_accion">Llamada a la accion</option>
                                    <option value="respuesta_rapida">Respuesta rapida</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="contenidoBoton"></div>
                        </div>

                        <div class="col-md-12">
                            <button type="button" class="btn btn-success btn-sm pull-right" id="agregarBotonPlantillaW" style="display: none;"><i class="fa fa-plus"></i> Agregar boton</button>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Ejemplo de mensaje</label>
                            <textarea name="pContenidoEjemplo" id="pContenidoEjemplo" cols="20" rows="5" class="form-control input-sm" maxlength="1024" autocomplete="disabled"></textarea>
                            <span>Un mensaje de muestra debe ser un mensaje completo que reemplace el texto escrito en la plantilla con una palabra/declaración/números/caracteres especiales significativos. El texto marcado debe comenzar y terminar con corchetes. Por ejemplo, <b>Su código de verificación es [232323]<b></span>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-guardar-plantilla-whatsapp">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
