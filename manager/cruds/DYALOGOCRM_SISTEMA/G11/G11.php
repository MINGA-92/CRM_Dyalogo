
<!-- Modal General De IVRs -->
<link rel="stylesheet" href="<?=base_url?>assets/css/StyleMinga.css"/>
<div class="modal fade bd-example-modal-lg" id="ModalIVRs" tabindex="-1" role="dialog" aria-labelledby="ModalIVRsLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas" onclick="LimpiarIVRs();">×</button>
                <h4 class="modal-title" id="title_cargue"><b>Comunicación IVRs: </b></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div id="divIframe">
                    <style type="text/css">
                        .embed-container {
                            position: relative;
                            padding-bottom: 56.25%;
                            height: 0;
                            overflow: hidden;
                        }
                        .embed-container iframe {
                            position: absolute;
                            top:0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                        }
                        .lista table{
                            margin: 0;
                        }
                        .titulo-dragdrop{
                            background: #f1f1f1;
                            color: #858585;
                            border: 1px solid #eaeaea;
                            font-weight: bold;
                            padding: 6px;
                            margin-bottom: 0;
                        }
                        hr{
                            width: 90%
                        }
                    </style>
                </div>
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="box-tools"></div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12" id="div_formularioIVRs">
                                <div id="divBtnGuardarIVRs">
                                    <button class="btn btn-primary" id="BtnGuardarIVRs" onclick="GuardarIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                    <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarIVRs();"><i class="fa fa-close" aria-hidden="true"></i></button>
                                </div>
                                <div id="divBtnActualizarIVRs" hidden>
                                    <button class="btn btn-primary" id="BtnActualizarIVRs" onclick="ActualizarIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                    <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarIVRs();"><i class="fa fa-close" aria-hidden="true"></i></button>
                                </div>
                                <br></br>
                                <div>
                                    <!-- loading -->
                                    <div id="Loading_4" class="container-loader" style="margin-top: -5%; margin-left: 42%;" hidden>
                                        <div class="loader"></div>
                                        <p class="form-label text-black" style="margin-top: 2%; margin-left: 5%; color: #0028D2;"> GUARDANDO... </p>
                                        <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -20%; margin-left: -5%;">
                                    </div>
                                    <!-- Formulario IVR's -->
                                    <form action="#" id="FormularioDatos2" enctype="multipart/form-data" method="post">
                                        <div class="row">
                                            <input type="text" name="IdEstrategia" id="IdEstrategia" value="" hidden>
                                            <input type="text" name="IdIVR" id="IdIVR" value="" hidden>
                                            <input type="hidden" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'];?>">
                                            <div class="col-md-12">
                                                <div class="box-primary" style="margin-top: -2%;">
                                                    <!-- Configuracion IVRs-->
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#IVRsConfiguracion">Configuración:</a>
                                                        </h4>
                                                    </div>
                                                    <div id="IVRsConfiguracion" class="panel-collapse collapse in">
                                                        <div class="box-body">
                                                            <div class="modal-content">

                                                                <!-- Form IVR-->
                                                                <div class="modal-body" id="BodyModal">
                                                                    <div class="card-body bg-light">
                                                                        <div class="card-header bg-light text-center text-uppercase"><b>IVR'S</b></div>
                                                                        <div class="panel box box-primary"></div>
                                                                        <form class="col-md-12 mb-12" style="margin-top: 5%;">
                                                                            <div class="row">
                                                                                <div class="orm-check col-md-4">
                                                                                    <label class="form-label" for="InputNombreIVR">Nombre De IVR: </label>
                                                                                    <input type="text" class="form-control" id="InputNombreIVR" placeholder="Introduzca IVR"/>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label class="form-label" for="InputOpcionIVR">Nombre De Opción: </label>
                                                                                    <input type="text" class="form-control" id="InputOpcionIVR" placeholder="Introduzca Opción"/>
                                                                                </div>
                                                                                <div class="form-check col-md-2" id="divCheckHabilitarDISA" style="margin-top: 2%;" hidden>
                                                                                    <input class="form-check-input" type="checkbox" id="CheckHabilitarDISA">
                                                                                    <label class="form-check-label" for="CheckHabilitarDISA">Habilitar DISA</label>
                                                                                </div>
                                                                                <div class="form-check col-md-2" id="divCheckMarcadoExtensiones" style="margin-top: 2%;" hidden>
                                                                                    <input class="form-check-input" type="checkbox" id="CheckMarcadoExtensiones">
                                                                                    <label class="form-check-label" for="CheckMarcadoExtensiones">Marcado Extensiones</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row" style="margin-top: 1%;">
                                                                                <div class="orm-check col-md-4">
                                                                                    <label class="form-label">Grabación De Bienvenida: </label>
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-control" id="SelectGrabaBienvenida" name="SelectGrabaBienvenida">
                                                                                            <option disabled selected>Elige Una Opción</option>
                                                                                            <?php echo $ListaGrabaciones;?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="orm-check col-md-4">
                                                                                    <label class="form-label">Grabación De Toma De Dígitos: </label>
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-control" id="SelectGrabaDigitos" name="SelectGrabaDigitos">
                                                                                            <option disabled selected>Elige Una Opción</option>
                                                                                            <?php echo $ListaGrabaciones;?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="orm-check col-md-4">
                                                                                    <label class="form-label" for="InputTiempoEspera">Tiempo De Espera: </label>
                                                                                    <input type="number" class="form-control" id="InputTiempoEspera" placeholder="5"/>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Lista De Opciones -->
                                                    <div class="box-header with-border" id="TtlListaOpciones" hidden>
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#IVRsOpciones">Lista De Opciones:</a>
                                                        </h4>
                                                    </div>
                                                    <div id="IVRsOpciones" class="panel-collapse collapse in">
                                                        <!-- Tabla IVR Opciones-->
                                                        <div id="TablaIVRs" class='box-body' hidden>
                                                            <div class='row'>
                                                                <div class='col-md-12 col-xs-12  table-responsive'>
                                                                    <table class='table table-condensed' id=''>
                                                                        <thead>
                                                                            <tr class='active'>
                                                                                <th scope="col" class="col-md-4" style="text-align: center;"><label> OPCIÓN (NÚMERO A MARCAR) </label></th>
                                                                                <th scope="col" class="col-md-4" style="text-align: center;"><label> NOMBRE OPCIÓN </label></th>
                                                                                <th scope="col" class="col-md-4" style="text-align: center;"><label> ACCIONES </label></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id='TablaOpciones'>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                    <label id="LblRespuesta"></label>
                                                                </div>
                                                            </div>
                                                            <div class='row'>
                                                                <div class='col-md-9'>

                                                                </div>
                                                                <div class="col-md-2" style="text-align: right;">
                                                                    <button type="button" class="btn btn-sm btn-success" id="BtnNuevaOpcion">
                                                                        <i class="glyphicon glyphicon-plus"></i> Agregar 
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Flujograma IVR's -->
                                                <div class="row" style="margin-top: 15px">
                                                    <div class="col-md-12">
                                                        <div class="panel box box-primary box-solid">
                                                        <input type="hidden" name="IdPaso" id="IdPaso" value="<?php if(isset($_GET['id_paso'])){ echo  $_GET['id_paso']; }else{ echo "0"; } ?>">
                                                            <div class="box-header with-border">
                                                                <h4 class="box-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#SeccionesIVR">
                                                                        Opciones y Acciones IVR's
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="SeccionesIVR" class="panel-collapse collapse in">
                                                                <div class="box-body">
                                                                    <div class="col-md-1">
                                                                        <div id="ListaSecciones" style="background-color: #F8F8F8; width:150%; height: 592px; margin-left: -20%;"></div>
                                                                    </div>
                                                                    <div class="col-md-11">
                                                                        <div id="SeccionGrafico" style="background-color: #F8F8F8; border: solid 1px black; width:100%; height:600px;"></div>
                                                                    </div>
                                                                    <textarea name="SavedModelIVR" id="SavedModelIVR" cols="30" rows="10" style="display:none"></textarea>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Opcion -->
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="ModalNuevaOpcion">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="LimpiarOpciones();">×</button>
            <h4 class="modal-title" id="title_cargue"><b>Editando Una Opción De '</b><b id="TltIVR">  </b></h4>
        </div>
        
        <div class="panel box box-primary"></div>

        <div class="box-header" style="margin-top: -5%;">
            <div class="box-tools"></div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="divBtnGuardarOpcion">
                        <button class="btn btn-primary" id="BtnGuardarOpcion" onclick="GuardarOpcionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarOpciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                    </div>
                    <div id="divBtnActualizarOpcion" hidden>
                        <button class="btn btn-primary" id="BtnActualizarOpcion" onclick="ActualizarOpcionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarOpciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                        <input type="hidden" class="form-control" id="IdOpcion">
                    </div>
                    <br></br>
                    <div>
                        <!-- Formulario -->
                        <form id="" enctype="multipart/form-data" method="post">
                            <div class="row">
                                <div class="modal-body" style="margin-top: -4%;">
                                    <div class="card-body bg-light">
                                        <form class="col-md-12 mb-12">
                                            <div class="row">
                                                <div class="form-check col-md-4">
                                                    <label class="form-label">Opción (Número A Marcar): </label>
                                                    <div class="form-group">
                                                        <select class="form-select form-control" id="SelectOpcionNumero" name="SelectOpcionNumero">
                                                            <option disabled selected>Elige Una Opción</option>
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="X">Otro</option>
                                                            <option value="t">Si No Marca Nada</option>
                                                            <option value="i">Opción Incorrecta</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="NombreOpcion">Nombre De Opción: </label>
                                                    <input type="text" class="form-control" id="NombreOpcion" placeholder="Introduzca Opción"/>
                                                </div>
                                                <div class="form-check col-md-4" id="divCheckOpcionValida" style="margin-top: 5%;">
                                                    <input class="form-check-input" type="checkbox" id="CheckOpcionValida">
                                                    <label class="form-check-label" for="CheckOpcionValida">Opción Válida</label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

        <div class="box box-primary"></div>

        <!-- Tabla IVR Acciones-->
        <div id="TablaAccionesIVR" class='box-body' style="margin-top: -2%;" hidden>
            <div class="col-md-12 float-end" style="text-align: right;">
                <button type="button" class="btn btn-sm btn-success" id="BtnNuevaAccion"><i class="glyphicon glyphicon-plus"></i> Agregar Acción </button>
            </div>
            <div class='row'>
                <div class='col-md-12 col-xs-12  table-responsive' style="margin-top: 2%;">
                    <table class='table table-condensed' id=''>
                        <thead>
                            <tr class='active'>
                                <th scope="col" class="col-md-2" style="text-align: center;"><label> ORDEN </label></th>
                                <th scope="col" class="col-md-3" style="text-align: center;"><label> ACCIÓN </label></th>
                                <th scope="col" class="col-md-5" style="text-align: center;"><label> VALOR ACCIÓN </label></th>
                                <th scope="col" class="col-md-2" style="text-align: center;"><label> OPCIONES </label></th>
                            </tr>
                        </thead>
                        <tbody id='BodyTablaAccionesIVR'>
                            
                        </tbody>
                    </table>
                    <label id="LblRespuesta"></label>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal Nueva Accion -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalNuevaAccion">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="LimpiarAcciones();">×</button>
            <h4 class="modal-title" id="title_cargue"><b>Editando Acción '</b><b id="TltOpcion">  </b></h4>
        </div>

        <div class="panel box box-primary"></div>
        
        <div class="box-body">
            <div class="row">

                <div class="col-md-12 mb-12" >
                    <div id="divBtnGuardarAccion">
                        <button class="btn btn-primary" id="BtnGuardarAccion" onclick="GuardarAccionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                    </div>
                    <div id="divBtnActualizarAccion" hidden>
                        <button class="btn btn-primary" id="BtnActualizarAccion" onclick="ActualizarAccionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                        <input type="hidden" class="form-control" id="IdAccion">
                    </div>
                </div>
                <br></br>

                <form class="col-md-12 mb-12">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label" for="OrdenEjecucion">Orden: </label>
                            <input type="number" class="form-control" id="OrdenEjecucion" placeholder="0"/>
                        </div>
                        <div class="form-check col-md-7">
                            <label class="form-label">Acción: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectAccion" name="SelectAccion">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option value="Numero Externo">Numero Externo</option>
                                    <option value="Pasar a Una Campaña">Pasar a Una Campaña</option>
                                    <option value="Reproducir Grabacion">Reproducir Grabación</option>
                                    <option value="Pasar a Otro IVR">Pasar a Otro IVR</option>
                                    <option value="Pasar a Encuesta">Pasar a Encuesta</option>
                                    <option value="Avanzado">Avanzado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="divNumeroExterno" hidden>
                        <div class="form-check col-md-6">
                            <label class="form-label">Linea: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectLinea" name="SelectLinea">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option disabled class="bg-info text-center">Troncales Por Huesped</option>
                                    <?php echo $ListaTroncalesHuesped; ?>
                                    <option disabled class="bg-info text-center">Todas Las Troncales</option>
                                    <?php echo $ListaTroncales; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="NumeroExterno">Numero: </label>
                            <input type="number" class="form-control" id="NumeroExterno" placeholder="Introduce Numero"/>
                        </div>
                    </div>
                    <div class="row" id="divCampana" hidden>
                        <div class="form-check col-md-8">
                            <label class="form-label">Campaña: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectCampana" name="SelectCampana">
                                    <option disabled selected>Elige Una Opción</option>
                                    <?php echo $ListaCampanasIVR; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-check col-md-4" style="margin-top: 2%;">
                            <input class="form-check-input" type="checkbox" id="CheckEncuesta">
                            <label class="form-check-label" for="CheckEncuesta">Transferir Encuesta</label>
                        </div>
                    </div>
                    <div class="row" id="divGraba" hidden>
                        <div class="form-check col-md-12">
                        <label for="">Lista De Audios: </label>
                            <select name="SelectGraba" id="SelectGraba" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaGrabaciones; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="divListaIVR" hidden>
                    <label for="">Lista De IVR's: </label>
                        <select name="ListaIVR" id="ListaIVR" class="form-control input-sm">
                            <option disabled selected>Elige Una Opción</option>
                            <?php echo $ListaIVRs_2; ?>
                        </select>
                    </div>
                    <div class="row" id="divEncuesta" hidden>
                        <div class="form-check col-md-12">
                        <label for="">Lista De Encuesta: </label>
                            <select name="SelectEncuesta" id="SelectEncuesta" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaEncuesta; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="divAvanzadoIVR" hidden>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Etiqueta: </label>
                                <input type="text" id="InputEtiqueta" name="InputEtiqueta" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label for="Aplicacion">Aplicación: </label>
                            <select name="SelectAplicacion" id="SelectAplicacion" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaAplicaciones; ?>
                            </select>
                        </div>
                        <div class="form-check col-md-12">
                            <div class="form-group">
                                <label for="Parametros">Parámetros: </label>
                                <input type="text" id="InputParametros" name="InputParametros" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal Esfera De Inicio / Flujograma IVR-->
<div class="modal fade" id="ModalInicioFlujogramaIVR" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">×</button>
            <div id="divBtnCapturarDatosIVRs">
                <button class="btn btn-primary" id="BtnCapturarDatosIVRs" onclick="CapturarDatosIVRs();"><i class="fa fa-save" aria-hidden="true"></i></button>
                <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12" id="div_formularioIVRs">

                    <!-- Formulario Inicial IVR's -->
                    <form action="#" id="FormularioIVR" enctype="multipart/form-data" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-primary" style="margin-top: -2%;">
                                    <!-- Configuracion Inicial IVRs-->
                                    <div class="box-body">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="card-body bg-light">
                                                    <div class="card-header bg-light text-center text-uppercase"><b>IVR'S</b></div>
                                                    <div class="panel box box-primary"></div>
                                                    <form class="col-md-12 mb-12" style="margin-top: 5%;">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="InputOpcionIVR">Nombre De Esfera: </label>
                                                                <input type="text" class="form-control" id="InputOpcionIVR" value="Inicio IVR" placeholder="Inicio IVR"/>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-top: 1%;">
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">Grabación De Bienvenida: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaBienvenida" name="SelectGrabaBienvenida">
                                                                        <option disabled selected>Elige Una Opción</option>
                                                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">Grabación De Toma De Dígitos: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaDigitos" name="SelectGrabaDigitos">
                                                                        <option disabled selected>Elige Una Opción</option>
                                                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-top: 1%;">
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">¿Aceptar Dígitos Durante Grabación?</label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectAceptarDigitos" name="SelectAceptarDigitos">
                                                                        <option value="1" selected>Si, En Cualquier Momento</option>
                                                                        <option value="0">No, Solo Al Final</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">Grabación De Opción Errada: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaErradaIVR" name="SelectGrabaErradaIVR">
                                                                        <option disabled="" selected="">Elige Una Opción</option>
                                                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-top: 1%;">
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label" for="InputTiempoEspera">Tiempo De Espera: </label>
                                                                <input type="number" class="form-control" id="InputTiempoEspera" value="5" placeholder="5"/>
                                                            </div>
                                                            
                                                            <div class="form-check col-md-6">
                                                                <label for="TxtNotificar">Intentos Errados o Sin Respuesta Permitidos: </label>
                                                                <input type="number" class="form-control" id="IntentosPermitidos" name="IntentosPermitidos" value="3">
                                                            </div>
                                                            
                                                            <!-- <div class="form-check col-md-4">
                                                                <label class="form-label">Grabación De Opción Errada: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaErrada" name="SelectGrabaDigitos">
                                                                        <option disabled selected>Elige Una Opción</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div> 

        <div class="box box-primary"></div>

    </div>
  </div>
</div>

<!-- Modal Esfera Toma De Digitos / Flujograma IVR-->
<div class="modal fade" id="ModalTomaDeDigitos" tabindex="-1" role="dialog" aria-labelledby="ModalTomaDeDigitosTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="divBtnCapturarTomaDigitos">
                    <button class="btn btn-primary" id="BtnCapturarTomaDigitos" onclick="CapturarTomaDeDigitos();"><i class="fa fa-save" aria-hidden="true"></i></button>
                    <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
                </div>
            </div>
            <input type="text" name="IdOpcion_4"  id="IdOpcion_4" hidden/>
            <input type="text" name="IdTomaDigitos"  id="IdTomaDigitos" hidden/>
            <input type="text" name="IdAccion_3"  id="IdAccion_3" hidden/>
            <input type="text" name="OrdenEjecu_2" id="OrdenEjecu_2" hidden>
            
            <div class="modal-body">
                <div class="card-body bg-light">
                    <div class="card-header bg-light text-center text-uppercase"><b>Captura De Respuesta</b></div>
                    <div class="panel box box-primary"></div>

                    <form class="col-md-12 mb-12">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="InputOpcionIVR_2">Nombre Toma Respuesta: </label>
                                <input type="text" class="form-control" id="InputOpcionIVR_2" placeholder="Captura De Respuesta"/>
                            </div>
                            <div class="form-check col-md-6">
                                <label class="form-label">Grabación Captura De Respuesta: </label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectGrabaDigitos_2" name="SelectGrabaDigitos_2">
                                        <option disabled selected>Elige Una Opción</option>
                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                        <?php echo $ListaGrabaciones;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-check col-md-6">
                                <label class="form-label">¿Aceptar Dígitos Durante Grabación?</label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectAceptarDigitos_2" name="SelectAceptarDigitos_2">
                                        <option value="1" selected>Si, En Cualquier Momento</option>
                                        <option value="0">No, Solo Al Final</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-check col-md-6">
                                <label class="form-label">Grabación De Opción Errada: </label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectGrabaErrada_2" name="SelectGrabaErrada_2">
                                        <option disabled selected>Elige Una Opción</option>
                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                        <?php echo $ListaGrabaciones;?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-check col-md-6">
                                <label class="form-label" for="InputTiempoEspera_2">Tiempo Espera: </label>
                                <input type="number" class="form-control" id="InputTiempoEspera_2" value="5"/>
                            </div>
                            <div class="form-check col-md-6">
                                <label for="IntentosPermitidos_2">Intentos Errados Permitidos: </label>
                                <input type="number" class="form-control" id="IntentosPermitidos_2" name="IntentosPermitidos_2" value="3">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
            
            </div>
        </div>
    </div>
</div>

<!-- Modal Esferas Acciones IVR's / Flujograma -->
<div class="modal fade bd-example-modal-sm" id="ModalAccionesFlujograma" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="">×</button>
            <h4 class="modal-title" id="title_cargue"><b id="TltOpcion_2">  </b></h4>
        </div>

        <div class="panel box box-primary"></div>
        <input type="text" name="IdOpcion_3" id="IdOpcion_3" hidden>
        <input type="text" name="IdAccion_2" id="IdAccion_2" hidden>
        <input type="text" name="OrdenEjecu" id="OrdenEjecu" hidden>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12 mb-12" >
                    <div id="divBtnGuardarAcciones">
                        <button class="btn btn-primary" id="BtnGuardarAcciones" onclick="CapturarDatosAccion();"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                    </div>
                </div>
                <br></br>

                <form class="col-md-12 mb-12">

                    <div class="col-md-12" id="divNombreAccion">
                        <label class="form-label" for="NombreAccion">Nombre De Acción: </label>
                        <input type="text" class="form-control" id="NombreAccion" placeholder="Introduzca Acción"/>
                    </div>
                    
                    <div id="divNumeroExterno_2" hidden>
                        <div class="col-md-12" style="margin-top: 1%;">
                            <label class="form-label" for="NumeroExterno_2">Numero: </label>
                            <input type="number" class="form-control" id="NumeroExterno_2" placeholder="00000"/>
                        </div>
                        <div class="form-check col-md-12" style="margin-top: 1%;">
                            <label class="form-label">Troncal: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectLinea_2" name="SelectLinea_2">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option disabled class="bg-info text-center">Troncales Del Huesped</option>
                                    <?php echo $ListaTroncalesHuesped; ?>
                                    <!-- <option disabled class="bg-info text-center">Todas Las Troncales</option> -->
                                    <?php //echo $ListaTroncales; ?> 
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="divCampana_2" hidden>
                        <div class="form-check">
                            <label class="form-label">Campaña: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectCampana_2" name="SelectCampana_2">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option disabled class="bg-info text-center">Campañas De La Estrategia</option>
                                    <?php echo $ListaCampanas; ?>
                                    <option disabled class="bg-info text-center">Campañas Del Huesped</option>
                                    <?php echo $ListaCampanasIVR; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-check col-md-4" style="margin-top: 2%;" hidden>
                            <input class="form-check-input" type="checkbox" id="CheckEncuesta_2">
                            <label class="form-check-label" for="CheckEncuesta_2">Transferir Encuesta</label>
                        </div>
                    </div>
                    <div class="col-md-12" id="divGraba_2" hidden>
                        <div class="form-check">
                        <label for="">Lista De Audios: </label>
                            <select name="SelectGraba_2" id="SelectGraba_2" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaGrabaciones; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12" id="divListaIVR_2" hidden>
                        <label for="ListaIVR_2">Lista De IVR's: </label>
                        <select name="ListaIVR_2" id="ListaIVR_2" class="form-control input-sm">
                            <option disabled selected>Elige Una Opción</option>
                            <option disabled class="bg-info text-center">IVR's De La Estrategia</option>
                            <?php echo $ListaIVRs; ?>
                            <option disabled class="bg-info text-center">IVR's Del Huesped</option>
                            <?php echo $ListaIVRsHuesped; ?>
                        </select>
                    </div>
                    <div class="col-md-12" id="divEncuesta_2" hidden>
                        <div class="form-check">
                        <label for="">Lista De Encuesta: </label>
                            <select name="SelectEncuesta_2" id="SelectEncuesta_2" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaEncuesta; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="divAvanzadoIVR_2" hidden>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Etiqueta: </label>
                                <input type="text" id="InputEtiqueta_2" name="InputEtiqueta_2" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label for="Aplicacion">Aplicación: </label>
                            <select name="SelectAplicacion_2" id="SelectAplicacion_2" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaAplicaciones; ?>
                            </select>
                        </div>
                        <div class="form-check col-md-12">
                            <div class="form-group">
                                <label for="Parametros_2">Parámetros: </label>
                                <input type="text" id="InputParametros_2" name="InputParametros_2" class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="divEsferaFinal" hidden>
                        <div class="form-group col-12">
                            <label class="form-label" for="NombreFinal">Nombre Final: </label>
                            <input type="text" class="form-control" id="NombreFinal" value="Final IVR">
                        </div>
                        <div class="form-check col-12" id="divGrabaGrabaDespedida">
                            <label>Grabación De Despedida: </label>
                            <select name="SelectGrabaDespedida" id="SelectGrabaDespedida" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaGrabaciones; ?>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal Esfera Avanzado -->
<div class="modal fade bd-example-modal-lg" id="ModalAvanzado" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="LimpiarTablaAvanzado();">&times;</span></button>
                <div>
                    <button class="btn btn-primary" id="BtnGuaradarAvanzado" onclick="CapturarDatosAvanzado();"><i class="fa fa-save" aria-hidden="true"></i></button>
                    <button class="btn btn-default" id="BtnLimpiarAvanzado" data-dismiss="modal" onclick="LimpiarTablaAvanzado();"><i class="fa fa-close" aria-hidden="true"></i></button>
                </div>
                <div>
                    <input type="text" name="IdOpcion_5" id="IdOpcion_5" hidden>
                    <input type="text" name="IdAccion_4" id="IdAccion_4" hidden>
                    <input type="text" name="OrdenEjecu_3" id="OrdenEjecu_3" hidden>
                    <input type="text" name="NumFilasExistentes" id="NumFilasExistentes" value="0" hidden>
                    <textarea type="text" id="IdsActualizar" hidden></textarea>
                </div>
            </div>
            
            <div class="modal-body">
                <div class="card-body bg-light">
                    <div class="card-header bg-light text-center text-uppercase"><b>Opciones Avanzadas</b></div>
                    <div class="panel box box-primary"></div>

                    <div id="" class="panel-collapse collapse in" aria-expanded="true">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="NombreAvanzado">Nombre De Opción Avanzada: </label>
                                    <input type="text" class="form-control" name="NombreAvanzado" id="NombreAvanzado" placeholder="Opción Avanzada">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-xs-12 table-responsive">
                                    <table class="table table-condensed" id="TblAvanzado">
                                        <!-- Titulos Tabla -->
                                        <thead>
                                            <tr class="active">
                                                <th scope="col" class="col-md-3" style="visibility:collapse; display:none;"><label> Nombre Acción </label></th>
                                                <th scope="col" class="col-md-3"><label> Nombre Etiqueta </label></th>
                                                <th scope="col" class="col-md-3"><label> Aplicación </label></th>
                                                <th scope="col" class="col-md-4"><label> Parámetros </label></th>
                                                <th scope="col" class="col-md-1"><label> Editar </label></th>
                                                <th scope="col" class="col-md-1"><label> Eliminar </label></th>
                                            </tr>
                                        </thead>
                                        <!-- Cuerpo Tabla -->
                                        <tbody id="tbodyAvanzado">

                                            <!-- Fila Existente 
                                            <tr class="text-center" id="FilaAvanzado_">
                                                <td class="col-md-2">
                                                    <input type="text" class="form-control" id="Etiqueta" placeholder="Etiqueta">
                                                </td>
                                                <td class="col-md-3">
                                                    <select id="Aplicacion" class="form-control">
                                                        <option disabled selected>Elige Una Opción</option>
                                                        <?php echo $ListaAplicaciones; ?>
                                                    </select>
                                                </td>

                                                <td class="col-md-3">
                                                    <input type="text" class="form-control" id="Parametros" placeholder="Parámetros">
                                                </td>
                                                
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <button class="btn btn-danger btn-sm deleteFirme form-control" title="Borrar opcion" type="button">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            -->

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="pull-right form-group col-md-6" style="text-align: right; margin-top: 2%;">
                                <button type="button" class="btn btn-sm btn-success" id="BtnAgregarNuevo" role="button">
                                    <i class="fa fa-plus" aria-hidden="true">&nbsp; Agregar </i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Flechas -->
<div class="modal fade bd-example-modal-sm" id="ModalOpcionFlecha" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><b>Opción </b><b id="TltOpcionFlecha"></b></h4>
            </div>
            <div class="panel box box-primary"></div>
            <input type="hidden" class="form-control" id="IdOpcion_2">
            <input type="hidden" class="form-control" id="IdFlecha">
            <input type="hidden" class="form-control" id="TxtIdFrom">
            <input type="hidden" class="form-control" id="TxtIdTo">

            <div class="box-body">

                <div class="row">
                    <div class="col-md-12 mb-12" >
                        <div id="divBtn">
                            <button class="btn btn-primary" id="Btn" onclick="GuardarOpcion();"><i class="fa fa-save" aria-hidden="true"></i></button>
                            <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <br></br>
                </div>

                <form class="col-md-12 mb-12">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label" for="NombreOpcionFlecha">Nombre De Opción: </label>
                            <input type="text" class="form-control" id="NombreOpcionFlecha" placeholder="Introduzca Opción"/>
                        </div>
                        <div class="form-check col-md-12">
                            <label class="form-label">Opción (Número A Marcar): </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectOpcionNumero_2" name="SelectOpcionNumero_2">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="X">Otra</option>
                                    <option value="t">Si No Marca Nada</option>
                                    <option value="i">Opción Incorrecta</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8" id="divTxtCual" hidden>
                            <label class="form-label" for="TxtCual"> ¿Cual Opción? </label>
                            <input type="text" class="form-control" id="TxtCual" placeholder="11"/>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



<!-- Funciones Para IVR's -->
<script src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G11/G11_Eventos.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.4/sweetalert2.all.js'></script>
