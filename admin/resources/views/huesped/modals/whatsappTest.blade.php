<div class="modal fade" id="modal-test-whatsapp" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Prueba de whatsapp</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-test-whatsapp" method="POST">
                    @csrf
                    <input type="hidden" id="idTestWhatsapp" name="idTestWhatsapp">
                    <input type="hidden" id="cuentaTestWhatsapp" name="cuentaTestWhatsapp">
                    <div class="form-group">
                        <div class="col-md-6">
                            <button type="button" id="test-w-iniciar" class="btn btn-success btn-block">Iniciar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="test-w-parar" disabled class="btn btn-danger btn-block">Parar</button>
                        </div>
                    </div>
                    <h4 style="" id="test-texto1">1. Al iniciar la prueba debes enviar un mensaje de whatsapp al numero XXXXX para captuar el mensaje</h4>
    
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <textarea name="logTestWhatsapp" id="logTestWhatsapp" cols="30" rows="10" class="form-control text-left" readonly>
                        </textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h4>2. Si se ha capturado un mensaje el siguente paso es devolver el mensaje, ingresa el numero de whatsapp con el enviaste el mensaje</h4>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="tesToW" id="tesToW" placeholder="57XXXXXXXXXX">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button class="btn btn-success" id="btnTestSalida">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>       
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h4 style="text-align: center;">Despues de iniciar la prueba espere a que el sistema termine de cargar y devuelva una respuesta</h4>
                    </div>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
</div>