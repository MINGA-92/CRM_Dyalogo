<div class="modal fade" id="modal-test-facebook" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Prueba de facebook messenger</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-test-facebook" method="POST">
                    <input type="hidden" id="idTestFacebook" name="idTestFacebook">
                    <input type="hidden" id="cuentaTestFacebook" name="cuentaTestFacebook">
                    <div class="form-group">
                        <div class="col-md-6">
                            <button type="button" id="test-f-iniciar" class="btn btn-success btn-block">Iniciar</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="test-f-parar" disabled class="btn btn-danger btn-block">Parar</button>
                        </div>
                    </div>
                    <h4 style="" id="test-texto1f">1. Al iniciar la prueba debes enviar un mensaje a la pagina de facebook captuar el mensaje</h4>
    
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <textarea name="logTestFacebook" id="logTestFacebook" cols="30" rows="10" class="form-control text-left" readonly>
                        </textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h4>2. Si se ha capturado un mensaje el siguente paso es devolver el mensaje, ingresa el numero del identificador que recibiste en de</h4>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="tesToF" id="tesToF" placeholder="XXXXXXXXXX">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <button class="btn btn-success" id="btnTestSalidaF">Enviar</button>
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