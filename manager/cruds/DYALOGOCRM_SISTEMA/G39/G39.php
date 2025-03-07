
<!-- MINGA -->
<!-- Modal General Llamada Entrante // G39 -->
<link rel="stylesheet" href="<?=base_url?>assets/css/StyleMinga.css"/>
<div class="modal fade bd-example-modal-lg" id="ModalLlamada" tabindex="-1" role="dialog" aria-labelledby="ModalLlamadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-md modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">×</button>
                <h4 class="modal-title" id="title_cargue"><b>Comunicación Llamada Entrante: </b></h4>
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

            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-tools"></div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12" id="div_formularios">
                            <div id="divBtnGuardar">
                                <button class="btn btn-primary" id="btnGuardar" onclick="GuardarRutaEntrante('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
                            </div>
                            <div id="divBtnActualizar" hidden>
                                <button class="btn btn-primary" id="divBtnActualizar" onclick="ActualizarRutaEntrante('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
                            </div><br>
                            <div>
                                <form action="#" id="FormularioDatos" enctype="multipart/form-data" method="post">
                                    <input type="hidden" name="id_paso" id="id_paso">
                                    <input type="hidden" name="oper" id="oper" value="add">
                                    <input type="hidden" name="configuracionId" id="configuracionId" value="0">
                                    <input type="hidden" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'];?>">
                                    <input type="hidden" name="IdFlujograma" id="IdFlujograma">
                                    <input type="hidden" name="IdRutaEntrante" id="IdRutaEntrante">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel box box-primary">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#RutaEntranteConfiguracion">Configuración:</a>
                                                    </h4>
                                                </div>
                                                <!-- loading -->
                                                <div id="Loading" class="container-loader" style="margin-top: -5%; margin-left: 42%;" hidden>
                                                    <div class="loader"></div>
                                                    <p class="form-label text-black" style="margin-top: 2%; margin-left: 5%; color: #0028D2;"> GUARDANDO... </p>
                                                    <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -28%; margin-left: -6%;">
                                                </div>
                                                <div id="RutaEntranteConfiguracion" class="panel-collapse collapse in">
                                                    <div class="box-body">
                                                        <div class="modal-content">
                                                            <!-- Form -->
                                                            <div class="modal-body" id="BodyModal">
                                                                <div class="card-body bg-light">
                                                                    <div class="card-header bg-light text-center text-uppercase"><b>Ruta Entrante</b></div>
                                                                    <div class="panel box box-primary"></div>
                                                                    <form class="col-md-12 mb-12" style="margin-top: 5%;">
                                                                        <div class="col-md-2" hidden>
                                                                            <label class="form-label" for="InputEstpas">Estpas: </label>
                                                                            <input type="text" class="form-control" id="InputEstpas" value=""/>
                                                                        </div>
                                                                        <div class="col-md-2" hidden>
                                                                            <label class="form-label" for="InputHuesped">Huesped: </label>
                                                                            <input type="text" class="form-control" id="InputHuesped" value="<?php echo $_SESSION['HUESPED'];?>"/>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-check col-md-6">
                                                                                <label class="form-label" for="InputNombre">Nombre: </label>
                                                                                <input type="text" class="form-control" id="InputNombre" placeholder="Introduzca Nombre"/>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label" for="InputNumeroEntrada">Número De Entrada: </label>
                                                                                <input type="number" class="form-control" id="InputNumeroEntrada" placeholder="Número De Entrada"/>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row" style="margin-top: 2%;">
                                                                            <div class="form-check col-md-5" id="divcheck">
                                                                                <input class="form-check-input" type="checkbox" id="CheckLimitarLlamadas">
                                                                                <label class="form-check-label" for="CheckLimitarLlamadas">Limitar Llamadas Simultaneas</label>
                                                                                <label class="form-label" for="InputNumeroLimite">Número Limite: </label>
                                                                                <input type="text" class="form-control" id="InputNumeroLimite" placeholder="-1" value="-1" disabled/>
                                                                            </div>
                                                                            <div class="form-check col-md-4" id="divCheckListaNegra" style="margin-left: 9%;">
                                                                                <input class="form-check-input" type="checkbox" id="CheckListaNegra">
                                                                                <label class="form-check-label" for="CheckListaNegra">Validar Lista Negra</label>
                                                                            </div>
                                                                            <div class="col-md-3" style="margin-left: 9%;" id="divBtnListaNegra" hidden>
                                                                                <button type="button" class="btn bg-black float-left text-white" id="BtnListaNegra" data-toggle="modal" data-target=".bd-example-modal-xl">Configurar Lista Negra</button>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="panel box box-primary" style="margin-top: 2%;"><label class="card-header text-blue">Avanzado: </label>
                                                                            <div class="row" style="margin-top: 0%;" id="divAvanzado">
                                                                                <div class="col-md-6">
                                                                                    <input class="form-check-input" type="checkbox" id="CheckGenerarPausa">
                                                                                    <label class="form-check-label" for="CheckGenerarPausa">Generar Pausa Para Contestar</label>
                                                                                    <label class="form-label" for="InputPausa">Segundos De Pausa: </label>
                                                                                    <input type="number" class="form-control" id="InputPausa" placeholder="0" value="0" disabled/>
                                                                                </div>
                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label class="form-label">Lista De Festivos: </label>
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-control" id="SelectListaFestivos" name="SelectListaFestivos">
                                                                                            <option disabled selected>Elige Una Opción</option>
                                                                                            <?php echo $ListaFestivosHuesped; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <input class="form-check-input" type="checkbox" id="CheckGenerarTimbre">
                                                                                    <label class="form-check-label" for="CheckGenerarTimbre">Generar Timbre Antes De Contestar</label>
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
                                    
                                    <!-- Seccion de horarios y acciones -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel box box-primary">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#HorariosAccionesLlamadas" aria-expanded="false" class="collapsed">
                                                            Horarios y Acciones: 
                                                        </a>
                                                    </h4>
                                                </div>

                                                <div id="HorariosAccionesLlamadas" class="panel-collapse collapse" aria-expanded="false">
                                                    <div class="box-body" id="ModalHorario">
                                                        <!-- Horarios -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3> Horario: </h3>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <!-- Lunes -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckLunes" id="LblCheckLunes">Lunes: </label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" name="CheckLunes" id="CheckLunes" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraILunes" id="LblHoraILunes">Hora Inicial Lunes</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraILunes" id="HoraILunes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFLunes" id="LblHoraFLunes">Hora Final Lunes</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFLunes" id="HoraFLunes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Martes -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckMartes" id="LblCheckMartes">Martes</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckMartes" id="CheckMartes" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIMartes" id="LblHoraIMartes">Hora Inicial Martes</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraIMartes" id="HoraIMartes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFMartes" id="LblHoraFMartes">Hora Final Martes</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFMartes" id="HoraFMartes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Miercoles -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckMiercoles" id="LblCheckMiercoles">Miercoles</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckMiercoles" id="CheckMiercoles" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIMiercoles" id="LblHoraIMiercoles">Hora Inicial Miercoles</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraIMiercoles" id="HoraIMiercoles" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFMiercoles" id="LblHoraFMiercoles">Hora Final Miercoles</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFMiercoles" id="HoraFMiercoles" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <!-- Jueves -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckJueves" id="LblCheckJueves">Jueves</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckJueves" id="CheckJueves" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIJueves" id="LblHoraIJueves">Hora Inicial Jueves</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraIJueves" id="HoraIJueves" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFJueves" id="LblHoraFJueves">Hora Final Jueves</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFJueves" id="HoraFJueves" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Viernes -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckViernes" id="LblCheckViernes">Viernes</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckViernes" id="CheckViernes" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIViernes" id="LblHoraIViernes">Hora Inicial Viernes</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraIViernes" id="HoraIViernes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFViernes" id="LblHoraFViernes">Hora Final Viernes</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFViernes" id="HoraFViernes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Sabado -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckSabado" id="LblCheckSabado">Sabado</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckSabado" id="CheckSabado"> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraISabado" id="LblHoraISabado">Hora Inicial Sabado</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraISabado" id="HoraISabado" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFSabado" id="LblHoraFSabado">Hora Final Sabado</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFSabado" id="HoraFSabado" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Domingo -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckDomingo" id="LblCheckDomingo">Domingo</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckDomingo" id="CheckDomingo"> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIDomingo" id="LblHoraIDomingo">Hora Inicial Domingo</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraIDomingo" id="HoraIDomingo" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFDomingo" id="LblHoraFDomingo">Hora Final Domingo</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFDomingo" id="HoraFDomingo" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Festivos -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckFestivos" id="LblCheckFestivos">Festivos</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckFestivos" id="CheckFestivos"> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIFestivos" id="LblHoraIFestivos">Hora Inicial Festivos</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraIFestivos" id="HoraIFestivos" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFFestivos" id="LblHoraFFestivos">Hora Final Festivos</label>
                                                                            <input type="time" class="form-control ui-timepicker-input" name="HoraFFestivos" id="HoraFFestivos" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Acciones -->
                                                        <div class="row">
                                                            <div class="panel box box-primary" style="margin-top: 2%;">
                                                                <div class="col-md-12">
                                                                    <h3 style='color: #3C8DBC;'>Acciones: </h3>
                                                                </div>
                                                            </div>    
                                                            <div class="col-md-6">
                                                                <div class="col-md-12" style='color: #3C8DBC;'>
                                                                    <h4> Dentro Del Horario </h4>
                                                                </div>
                                                                <div class="col-md-12" id="divTareaActual" hidden>
                                                                    <div class="col-md-6"><b style="margin-left: 62%;">Tarea Actual: </b></div>
                                                                    <div class="col-md-6"><p class="float-left" id="LblTareaActual">MINGA</p></div>
                                                                    <input type="hidden" id="IdTareaActual" value="0">
                                                                    <input type="hidden" id="IptTareaActual" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Posibles Tareas: </label>
                                                                            <select name="SelectPosiblesTareas" id="SelectPosiblesTareas" class="form-control input-sm" onchange="PosiblesTareas_DentroH();">
                                                                                <option disabled selected>Elige Una Opción</option>
                                                                                <option value="Pasar a Una Campaña">Pasar a Agentes</option>
                                                                                <option value="Pasar a IVR">Pasar a IVR</option>
                                                                                <option value="Número Externo">Número Externo</option>
                                                                                <option value="Reproducir Grabación">Reproducir Grabación</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divInputNumeroExterno" hidden>
                                                                            <label for="InputNumeroExterno">Número Externo: </label>
                                                                            <input type="text" id="InputNumeroExterno" name="InputNumeroExterno" class="form-control input-sm" value="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectTroncales" hidden>
                                                                        <label for="">Troncal De Dónde Sale La Llamada: </label>
                                                                            <select name="SelectTroncales" id="SelectTroncales" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">Troncales Del Huesped</option>
                                                                                <?php echo $ListaTroncalesHuesped; ?>
                                                                                <!-- <option disabled class="bg-info text-center">Todas Las Troncales</option> -->
                                                                                <?php //echo $ListaTroncales; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectCampanas" hidden>
                                                                        <label for="">Lista De Campañas: </label>
                                                                            <select name="SelectCampanas" id="SelectCampanas" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">Campañas De La Estrategia</option>
                                                                                <?php echo $ListaCampanas; ?>
                                                                                <!-- <option disabled class="bg-info text-center">Campañas Del Huesped</option> -->
                                                                                <?php //echo $ListaCampanasHuesped; ?> 
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectIVR" hidden>
                                                                        <label for="">Lista De IVR's: </label>
                                                                            <select name="SelectIVR" id="SelectIVR" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">IVR's De La Estrategia</option>
                                                                                <?php echo $ListaIVRs; ?>
                                                                                <!-- <option disabled class="bg-info text-center">IVR's Del Huesped</option> -->
                                                                                <?php //echo $ListaIVRsHuesped; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectAudios" hidden>
                                                                        <label for="">Lista De Audios: </label>
                                                                            <select name="SelectAudios" id="SelectAudios" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">Audios Del Huesped</option>
                                                                                <?php echo $ListaAudiosHuesped; ?>
                                                                                <!-- <option value="NewAudio">Agregar Nuevo Audio</option>
                                                                                <option disabled class="bg-info text-center">Todos Los Audios</option> -->
                                                                                <?php //echo $ListaAudios; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="col-md-12">
                                                                    <h4 style='color: #3C8DBC;'> Fuera Del Horario </h4>
                                                                </div>
                                                                <div class="col-md-12" id="divTareaFueraHora" hidden>
                                                                    <div class="col-md-6"><b style="margin-left: 62%;">Tarea Actual: </b></div>
                                                                    <div class="col-md-6"><p class="float-left" id="LblTareaFueraHora">MINGA</p></div>
                                                                    <input type="hidden" id="IptTareaFueraHora" value="">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Posibles Tareas: </label>
                                                                        <select name="FueraHSelectPosiblesTareas" id="FueraHSelectPosiblesTareas" class="form-control input-sm" onchange="PosiblesTareas_FueraH();">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option value="Pasar a Una Campaña">Pasar a Agentes</option>
                                                                            <option value="Pasar a IVR">Pasar a IVR</option>
                                                                            <option value="Número Externo">Número Externo</option>
                                                                            <option value="Reproducir Grabación">Reproducir Grabación</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivInputNumeroExterno" hidden>
                                                                        <label for="">Número Externo: </label>
                                                                        <input type="text" id="FueraHInputNumeroExterno" name="InputNumeroExterno" class="form-control input-sm" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectTroncales" hidden>
                                                                    <label for="">Troncal De Dónde Sale La Llamada: </label>
                                                                        <select name="FueraHSelectTroncales" id="FueraHSelectTroncales" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">Troncales Del Huesped</option>
                                                                            <?php echo $ListaTroncalesHuesped; ?>
                                                                            <!-- <option disabled class="bg-info text-center">Todas Las Troncales</option> -->
                                                                            <?php //echo $ListaTroncales; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectCampanas" hidden>
                                                                    <label for="">Lista De Campañas: </label>
                                                                        <select name="FueraHSelectCampanas" id="FueraHSelectCampanas" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">Campañas De La Estrategia</option>
                                                                            <?php echo $ListaCampanas; ?>
                                                                            <!-- <option disabled class="bg-info text-center">Campañas Del Huesped</option>  -->
                                                                            <?php //echo $ListaCampanasHuesped; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectIVR" hidden>
                                                                    <label for="">Lista De IVR's: </label>
                                                                        <select name="FueraHSelectIVR" id="FueraHSelectIVR" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">IVR's De La Estrategia</option>
                                                                            <?php echo $ListaIVRs; ?>
                                                                            <!-- <option disabled class="bg-info text-center">IVR's Del Huesped</option> -->
                                                                            <?php //echo $ListaIVRsHuesped; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectAudios" hidden>
                                                                    <label for="">Lista De Audios: </label>
                                                                        <select name="FueraHSelectAudios" id="FueraHSelectAudios" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">Audios Del Huesped</option>
                                                                            <?php echo $ListaAudiosHuesped; ?>
                                                                            <!-- <option value="NewAudio">Agregar Nuevo Audio</option>
                                                                            <option disabled class="bg-info text-center">Todos Los Audios</option> -->
                                                                            <?php //echo $ListaAudios; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
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

    <!-- Lista Negra -->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="ModalListaNegra">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="staticBackdropLabel"> 📓 Lista Negra: </h2>
            </div>
            <div id="Loading_2" style="margin-top: 11%; margin-left: 30%;">
                <img src="<?=base_url?>assets/img/loader.gif">
            </div>
            <div class="modal-body" id="ListaNegra" hidden>
                <button type="button" class="btn btn-primary" id="BtnAddListaNegra"> Nuevo Número <i class="fa fa-plus"></i> 📲 </button>
                <div class="col-12 table-responsive" style="margin-top: 2%;">
                    <table id="TblListaNegra" class="table table-bordered table-striped text-center mt-4 dataTable">
                        <thead class="text-black">
                            <tr class="bg-info">
                                <th>ACCIÓN</th>
                                <th>NUMERO</th>
                                <th>RAZÓN</th>
                                <th>EDITAR</th>
                                <th>ELIMINAR</th>
                            </tr>
                        </thead>
                        <tbody id="CuerpoTblListaNegra">
                            <tr id="CeldasListaNegra">
                                <th> ( ´･･)ﾉ (._.`) </th>
                                <th> !No Se Encontraron Resultados! </th>
                                <th> ( ´･･)ﾉ (._.`) </th>
                                <th> !No Se Encontraron Resultados! </th>
                                <th> ( ´･･)ﾉ (._.`) </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!--Modal Añadir a Lista Negra-->
    <div class="modal fade" id="ModalAddListaNegra" tabindex="-1" aria-labelledby="ModalAddListaNegraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Añadir Número a Lista Negra: </h5>
            </div>
            <div id="Loading_3" style="margin-top: 11%; margin-left: 28%;" hidden>
                <img src="<?=base_url?>assets/img/loader.gif">
            </div>
            <div class="modal-body" id="BodyModalAddListaNegra">
                <form class="col-md-12 mb-12" style="margin-top: 5%;">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="InputTelefono">Número De Telefono: </label>
                            <input type="number" class="form-control" id="InputTelefono" placeholder="Numero De Telefono"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motivo o Razón: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectListaMotivo" name="SelectListaMotivo">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option value="Llamada De Broma">Llamada De Broma</option>
                                    <option value="Llamada Obscena">Llamada Obscena</option>
                                    <option value="Niños Molestando">Niños Molestando</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="BtnAgregar">Agregar Número</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal Editar Lista Negra -->
    <div class="modal fade" id="ModalEditarListaNegra" tabindex="-1" aria-labelledby="ModalEditarListaNegraLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Número De Lista Negra: </h5>
                </div>
                <div class="modal-body">
                    <form class="col-md-12 mb-12" style="margin-top: 5%;">
                        <div class="row">
                            <div class="col-md-6" hidden>
                                <label class="form-label" for="InputId">Id: </label>
                                <input type="hidden" class="form-control" id="InputId"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="InputEditarTelefono">Número De Telefono: </label>
                                <input type="number" class="form-control" id="InputEditarTelefono" placeholder="Numero De Telefono"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Motivo o Razón: </label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectEditarListaMotivo" name="SelectEditarListaMotivo">
                                        <option disabled selected>Elige Una Opción</option>
                                        <option value="Llamada De Broma">Llamada De Broma</option>
                                        <option value="Llamada Obscena">Llamada Obscena</option>
                                        <option value="Niños Molestando">Niños Molestando</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="BtnActualizar">Actualizar Número</button>
                </div>
            </div>
        </div>
    </div>
    </div>

</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.4/sweetalert2.all.js'></script>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="<?=base_url?>assets/plugins/Flowchart/flowchart.js"></script>
<script src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/configFlujograma.js"></script>
