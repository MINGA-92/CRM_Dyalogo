
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
</style>
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width:95%; margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-header" style="padding-right: 5px; padding-bottom: 3px; padding-top: 3px;">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src=""  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php
   //SECCION : Definicion urls
   $url_crud = "cruds/DYALOGOCRM_SISTEMA/G24/G24_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_horarios;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?page=flujograma&estrategia=<?php echo $datos['ESTPAS_ConsInte__ESTRAT_b']; ?>"><?php echo $str_flujograma;?></a></li>
            <li><a href="index.php?page=llam_saliente&id_paso=<?php echo $_GET['id_paso'];?>&poblacion=<?php echo $_GET['poblacion'];?>"><?php echo $str_LLamadas_entrantes;?></a></li>
            <li class="active"><?php echo $str_Horarios_entrantes;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    
                </div>
            </div>
            <div class="box-body">
                <div class="row">
            
                    <div class="col-md-12" id="div_formularios">
                        <div>
                            <button class="btn btn-default" id="Save">
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel">
                                <i class="fa fa-close"></i>
                            </button>
                        </div>

                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <div>
                            <form id="FormularioDatosHorarios" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="panel">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    HORARIO
                                                </h4>
                                            </div>
                                            <div class="box-body">

                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C219" id="LblG24_C219">LUNES</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C219" id="G24_C219" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C220" id="LblG24_C220">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C220" id="G24_C220" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C220">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                         
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G24_C221" id="LblG24_C221">HORA FINAL</label>
                                                                <div class="input-group">
                                                                   <input type="text" class="form-control input-sm" id="G24_C221" value=""  name="G24_C221"  placeholder="HH:MM:SS">
                                                                    <div class="input-group-addon" id="TMP_G24_C220">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                          
                                                    </div>

                                          
                                                </div>


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C222" id="LblG24_C222">MARTES</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C222" id="G24_C222" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C223" id="LblG24_C223">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C223" id="G24_C223" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C223">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C224" id="LblG24_C224">HORA FINAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C224" id="G24_C224" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C224">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>

                                          
                                                </div>


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C225" id="LblG24_C225">MIERCOLES</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C225" id="G24_C225" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C226" id="LblG24_C226">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C226" id="G24_C226" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C226">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C227" id="LblG24_C227">HORA FINAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C227" id="G24_C227" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C227">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>

                                          
                                                </div>


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C228" id="LblG24_C228">JUEVES</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C228" id="G24_C228" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C229" id="LblG24_C229">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C229" id="G24_C229" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C229">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C230" id="LblG24_C230">HORA FINAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C230" id="G24_C230" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C230">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>

                                          
                                                </div>


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C231" id="LblG24_C231">VIERNES</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C231" id="G24_C231" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C232" id="LblG24_C232">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C232" id="G24_C232" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C232">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C233" id="LblG24_C233">HORA FINAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C233" id="G24_C233" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C233">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>

                                          
                                                </div>


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C234" id="LblG24_C234">SABADO</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C234" id="G24_C234" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C235" id="LblG24_C235">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C235" id="G24_C235" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C235">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C236" id="LblG24_C236">HORA FINAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C236" id="G24_C236" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C236">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>

                                          
                                                </div>


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C237" id="LblG24_C237">DOMINGO</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C237" id="G24_C237" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                         
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G24_C238" id="LblG24_C238">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C238" id="G24_C238" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C238">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C239" id="LblG24_C239">HORA FINAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C239" id="G24_C239" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C239">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>

                                          
                                                </div>


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G24_C240" id="LblG24_C240">FESTIVO</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="1" name="G24_C240" id="G24_C240" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C241" id="LblG24_C241">HORA INICIAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C241" id="G24_C241" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C241">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-4">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G24_C242" id="LblG24_C242">HORA FINAL</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm Hora"  name="G24_C242" id="G24_C242" placeholder="HH:MM:SS" >
                                                                    <div class="input-group-addon" id="TMP_G24_C242">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div>
                                                                <!-- /.input group -->
                                                            </div>
                                                            <!-- /.form group -->
                                          
                                                    </div>

                                          
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                        <div class="form-group">
                                                            <label for="G24_C244" id="LblG24_C244">ACCION POR DEFECTO</label>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" value="1" name="G24_C244" id="G24_C244" data-error="Before you wreck yourself"  > 
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <!-- FIN DEL CAMPO SI/NO -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    FLUJOGRAMA
                                                </h4>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <h4><?php echo $str_type_pass;?></h4>
                                                        <span style="display: inline-block; vertical-align: top; width:100%;">
                                                            <div id="myPaletteDiv" style="height: 600px;"></div>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-11">
                                                        <h4><?php echo $str_flujograma; ?></h4>
                                                        <span style="display: inline-block; vertical-align: top; width:100%">
                                                            <div id="myDiagramDiv" style="border: solid 1px black; height: 600px;"></div>
                                                        </span>
                                                    </div>           
                                                </div>
                                                <div class="row" style="display: none;">
                            <!-- FIN DEL CAMPO TIPO MEMO -->
                            <div class="form-group" style="display: none;">
<textarea id="mySavedModel" class="form-control">
{
    "class": "go.GraphLinksModel",
    "linkFromPortIdProperty": "fromPort",
    "linkToPortIdProperty": "toPort",
    "nodeDataArray": [
        
    ],
    "linkDataArray": [
        
    ]
}
</textarea>
                            </div>
                        </div>
                                            </div>
                                        </div>
                                        <!-- SECCION : PAGINAS INCLUIDAS -->
                                        <input type="hidden" name="id" id="hidId" value='0'>
                                        <input type="hidden" name="oper" id="oper" value='add'>
                                        <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>


<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
      $(function(){

        $("#usuarios").addClass('active');
       // busqueda_lista_navegacion();
        $(".CargarDatos :first").click();
    

        

        $("#tablaScroll").on('scroll', function() {
            //alert('Si llegue');
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                
                $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si' }, function(data){
                    if(data != ""){
                        $("#TablaIzquierda").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                });
            }
        });

        //SECCION FUNCIONALIDAD BOTONES

        //Funcionalidad del boton + , add
        $("#add").click(function(){

             //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);    

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);   
            $("#Save").attr('disabled', false);
            
           

            //Inializacion campos vacios por defecto
            $('#FormularioDatosHorarios :input').each(function(){
                if($(this).is(':checkbox')){
                    if($(this).is(':checked')){
                        $(this).attr('checked', false);
                    }
                    $(this).attr('disabled', false); 
                }else{
                    $(this).val('');
                    $(this).attr('disabled', false); 
                }
                               
            });

            $(".str_Select2").each(function(){
                $(this).val(0).change();
            });

            $("#hidId").val(0);
            $("#h3mio").html('');
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";
            before_add();
           
        });

        jQuery.fn.reset = function () {
            $(this).each (function() { this.reset(); });
        }

        //funcionalidad del boton editar
        $("#edit").click(function(){

            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);    

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);   
            $("#Save").attr('disabled', false);

            
            //Le informa al crud que la operaciòn a ejecutar es editar registro
            $("#oper").val('edit');
            //Habilitar todos los campos para edicion
            $('#FormularioDatosHorarios :input').each(function(){
                $(this).attr('disabled', false);
            });

            before_edit();
          
        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function(){
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);
            <?php if(isset($_GET['view'])){ ?>
                window.location.href  = "cancelado.php";
            <?php }  ?>
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function(){
            //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
            alertify.confirm("¿Está seguro de eliminar el registro seleccionado?", function (e) {
                //Si la persona acepta
                if (e) {
                    var id = $("#hidId").val();
                    //se envian los datos, diciendo que la oper es "del"
                    $.ajax({
                        url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                        type     : 'POST',
                        data     : { id : id , oper : 'del'},
                        success  : function(data){
                            if(data == '1'){   
                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                llenar_lista_navegacion('');
                            }else{
                                //Algo paso, hay un error
                                alert(data);
                            }
                        } 
                    });
                    
                } else {
                    
                }
            }); 
        });


        //datos Hoja de busqueda
        $("#BtnBusqueda_lista_navegacion").click(function(){
            //alert($("#table_search_lista_navegacion").val());
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        });
        
        //Cajaj de texto de bus queda
        $("#table_search_lista_navegacion").keypress(function(e){
            if(e.keyCode == 13)
            {
                llenar_lista_navegacion($(this).val());
            }
        });

        //preguntar cuando esta vacia la tabla para dejar solo los botones correctos habilitados
        var g = $("#tablaScroll").html();
        if(g === ''){
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true); 
        }
    });
</script>

<script type="text/javascript" src="cruds/DYALOGOCRM_SISTEMA/G24/G24_eventos.js"></script> 
<script type="text/javascript">
    $(function(){

        <?php if(isset($_GET['id_paso'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    
    
                    if(item.G24_C219 == '1'){
                       if(!$("#G24_C219").is(':checked')){
                           $("#G24_C219").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C219").is(':checked')){
                           $("#G24_C219").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C220").val(item.G24_C220);

                    $("#G24_C221").val(item.G24_C221);

                    if(item.G24_C222 == '1'){
                       if(!$("#G24_C222").is(':checked')){
                           $("#G24_C222").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C222").is(':checked')){
                           $("#G24_C222").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C223").val(item.G24_C223);

                    $("#G24_C224").val(item.G24_C224);

                    if(item.G24_C225 == '1'){
                       if(!$("#G24_C225").is(':checked')){
                           $("#G24_C225").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C225").is(':checked')){
                           $("#G24_C225").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C226").val(item.G24_C226);

                    $("#G24_C227").val(item.G24_C227);

                    if(item.G24_C228 == '1'){
                       if(!$("#G24_C228").is(':checked')){
                           $("#G24_C228").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C228").is(':checked')){
                           $("#G24_C228").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C229").val(item.G24_C229);

                    $("#G24_C230").val(item.G24_C230);

                    if(item.G24_C231 == '1'){
                       if(!$("#G24_C231").is(':checked')){
                           $("#G24_C231").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C231").is(':checked')){
                           $("#G24_C231").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C232").val(item.G24_C232);

                    $("#G24_C233").val(item.G24_C233);

                    if(item.G24_C234 == '1'){
                       if(!$("#G24_C234").is(':checked')){
                           $("#G24_C234").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C234").is(':checked')){
                           $("#G24_C234").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C235").val(item.G24_C235);

                    $("#G24_C236").val(item.G24_C236);

                    if(item.G24_C237 == '1'){
                       if(!$("#G24_C237").is(':checked')){
                           $("#G24_C237").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C237").is(':checked')){
                           $("#G24_C237").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C238").val(item.G24_C238);

                    $("#G24_C239").val(item.G24_C239);

                    if(item.G24_C240 == '1'){
                       if(!$("#G24_C240").is(':checked')){
                           $("#G24_C240").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C240").is(':checked')){
                           $("#G24_C240").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C241").val(item.G24_C241);

                    $("#G24_C242").val(item.G24_C242);

                    $("#G24_C243").val(item.G24_C243);

                    if(item.G24_C244 == '1'){
                       if(!$("#G24_C244").is(':checked')){
                           $("#G24_C244").prop('checked', true);  
                        }
                    } else {
                        if($("#G24_C244").is(':checked')){
                           $("#G24_C244").prop('checked', false);  
                        }
                        
                    }

                    $("#G24_C247").val(item.G24_C247);
                    $("#h3mio").html(item.principal);
                    idTotal = item.G24_ConsInte__b;
                    $("#hidId").val(item.G24_ConsInte__b);

                    if ( $("#"+idTotal).length > 0) {
                        $("#"+idTotal).click();   
                        $("#"+idTotal).addClass('active'); 
                    }else{
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }

                    $("#oper").val('edit');
                });
                //Deshabilitar los campos
            } 
        });

        //vamosRecargaLasGrillasPorfavor(<?php// echo $_GET['registroId'];?>);
                        
        <?php } ?>

        //str_Select2 estos son los guiones
        


        //datepickers
        

        //Timepickers
        


        //Timepicker
        var options = {  //hh:mm 24 hour format only, defaults to current time
            'timeFormat': 'H:i:s',
            'minTime': '08:00:00',
            'maxTime': '17:00:00',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        }; 
        $("#G24_C220").timepicker(options);
        $("#G24_C221").timepicker(options);
        $("#G24_C223").timepicker(options);
        $("#G24_C224").timepicker(options);
        $("#G24_C226").timepicker(options);
        $("#G24_C227").timepicker(options);
        $("#G24_C229").timepicker(options);
        $("#G24_C230").timepicker(options);
        $("#G24_C232").timepicker(options);
        $("#G24_C233").timepicker(options);
        $("#G24_C235").timepicker(options);
        $("#G24_C236").timepicker(options);
        $("#G24_C238").timepicker(options);
        $("#G24_C239").timepicker(options);
        $("#G24_C241").timepicker(options);
        $("#G24_C242").timepicker(options);

        //Validaciones numeros Enteros
        

        $("#G24_C247").numeric();
        

        //Validaciones numeros Decimales
       

    

        //Buton gurdar
        

        $("#Save").click(function(){
            var bol_respuesta = before_save();

            if(bol_respuesta){
            
                var form = $("#FormularioDatosHorarios");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#FormularioDatosHorarios")[0]);
                $.ajax({
                   url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data){
                            <?php if(isset($_GET['id_paso'])){ ?>
                                $.ajax({
                                    url      : '<?=$url_crud;?>',
                                    type     : 'POST',
                                    data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
                                    dataType : 'json',
                                    success  : function(data){
                                        //recorrer datos y enviarlos al formulario
                                        $.each(data, function(i, item) {
                                            
                            
                                            if(item.G24_C219 == '1'){
                                               if(!$("#G24_C219").is(':checked')){
                                                   $("#G24_C219").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C219").is(':checked')){
                                                   $("#G24_C219").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C220").val(item.G24_C220);

                                            $("#G24_C221").val(item.G24_C221);
                        
                                            if(item.G24_C222 == '1'){
                                               if(!$("#G24_C222").is(':checked')){
                                                   $("#G24_C222").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C222").is(':checked')){
                                                   $("#G24_C222").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C223").val(item.G24_C223);

                                            $("#G24_C224").val(item.G24_C224);
                        
                                            if(item.G24_C225 == '1'){
                                               if(!$("#G24_C225").is(':checked')){
                                                   $("#G24_C225").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C225").is(':checked')){
                                                   $("#G24_C225").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C226").val(item.G24_C226);

                                            $("#G24_C227").val(item.G24_C227);
                        
                                            if(item.G24_C228 == '1'){
                                               if(!$("#G24_C228").is(':checked')){
                                                   $("#G24_C228").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C228").is(':checked')){
                                                   $("#G24_C228").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C229").val(item.G24_C229);

                                            $("#G24_C230").val(item.G24_C230);
                        
                                            if(item.G24_C231 == '1'){
                                               if(!$("#G24_C231").is(':checked')){
                                                   $("#G24_C231").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C231").is(':checked')){
                                                   $("#G24_C231").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C232").val(item.G24_C232);

                                            $("#G24_C233").val(item.G24_C233);
                        
                                            if(item.G24_C234 == '1'){
                                               if(!$("#G24_C234").is(':checked')){
                                                   $("#G24_C234").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C234").is(':checked')){
                                                   $("#G24_C234").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C235").val(item.G24_C235);

                                            $("#G24_C236").val(item.G24_C236);
                        
                                            if(item.G24_C237 == '1'){
                                               if(!$("#G24_C237").is(':checked')){
                                                   $("#G24_C237").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C237").is(':checked')){
                                                   $("#G24_C237").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C238").val(item.G24_C238);

                                            $("#G24_C239").val(item.G24_C239);
                        
                                            if(item.G24_C240 == '1'){
                                               if(!$("#G24_C240").is(':checked')){
                                                   $("#G24_C240").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C240").is(':checked')){
                                                   $("#G24_C240").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C241").val(item.G24_C241);

                                            $("#G24_C242").val(item.G24_C242);

                                            $("#G24_C243").val(item.G24_C243);
                        
                                            if(item.G24_C244 == '1'){
                                               if(!$("#G24_C244").is(':checked')){
                                                   $("#G24_C244").prop('checked', true);  
                                                }
                                            } else {
                                                if($("#G24_C244").is(':checked')){
                                                   $("#G24_C244").prop('checked', false);  
                                                }
                                                
                                            }

                                            $("#G24_C247").val(item.G24_C247);
                                            $("#h3mio").html(item.principal);
                                            $("#hidId").val(item.G24_ConsInte__b);
                                            idTotal = item.G24_ConsInte__b;

                                            if ( $("#"+idTotal).length > 0) {
                                                $("#"+idTotal).click();   
                                                $("#"+idTotal).addClass('active'); 
                                            }else{
                                                //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                                                $(".CargarDatos :first").click();
                                            }
                                        });
                                        //Deshabilitar los campos
                                    } 
                                });

                                
                                $("#oper").val('edit');
                                //vamosRecargaLasGrillasPorfavor(<?php// echo $_GET['registroId'];?>             
                                <?php } ?>    
                        }else{
                            //Algo paso, hay un error
                            alertify.error('Un error ha ocurrido');
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                    }
                });

            }
        });
    });

 
    //SECCION  : Manipular Lista de Navegacion

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(x){
        var tr = '';
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatosJson : 'SI', Busqueda : x},
            dataType : 'json',
            success  : function(data){
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {
                    tr += "<tr class='CargarDatos' id='"+data[i].id+"'>";
                    tr += "<td>";
                    tr += "<p style='font-size:14px;'><b>"+data[i].camp1+"</b></p>";
                    tr += "<p style='font-size:12px; margin-top:-10px;'>"+data[i].camp2+"</p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ( $("#"+idTotal).length > 0) {
                    $("#"+idTotal).click();   
                    $("#"+idTotal).addClass('active'); 
                }else{
                    //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                    $(".CargarDatos :first").click();
                }

            } 
        });
    }

    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion(){

        $(".CargarDatos").click(function(){
            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');
              
              
            var id = $(this).attr('id');
            //buscar los datos
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        
    
                        if(item.G24_C219 == '1'){
                           if(!$("#G24_C219").is(':checked')){
                               $("#G24_C219").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C219").is(':checked')){
                               $("#G24_C219").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C220").val(item.G24_C220);

                        $("#G24_C220").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C220,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C221").val(item.G24_C221);
    
                        if(item.G24_C222 == '1'){
                           if(!$("#G24_C222").is(':checked')){
                               $("#G24_C222").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C222").is(':checked')){
                               $("#G24_C222").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C223").val(item.G24_C223);

                        $("#G24_C223").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C223,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C224").val(item.G24_C224);

                        $("#G24_C224").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C224,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        
    
                        if(item.G24_C225 == '1'){
                           if(!$("#G24_C225").is(':checked')){
                               $("#G24_C225").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C225").is(':checked')){
                               $("#G24_C225").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C226").val(item.G24_C226);

                        $("#G24_C226").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C226,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C227").val(item.G24_C227);

                        $("#G24_C227").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C227,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        
    
                        if(item.G24_C228 == '1'){
                           if(!$("#G24_C228").is(':checked')){
                               $("#G24_C228").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C228").is(':checked')){
                               $("#G24_C228").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C229").val(item.G24_C229);

                        $("#G24_C229").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C229,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C230").val(item.G24_C230);

                        $("#G24_C230").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C230,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        
    
                        if(item.G24_C231 == '1'){
                           if(!$("#G24_C231").is(':checked')){
                               $("#G24_C231").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C231").is(':checked')){
                               $("#G24_C231").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C232").val(item.G24_C232);

                        $("#G24_C232").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C232,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C233").val(item.G24_C233);

                        $("#G24_C233").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C233,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        
    
                        if(item.G24_C234 == '1'){
                           if(!$("#G24_C234").is(':checked')){
                               $("#G24_C234").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C234").is(':checked')){
                               $("#G24_C234").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C235").val(item.G24_C235);

                        $("#G24_C235").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C235,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C236").val(item.G24_C236);

                        $("#G24_C236").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C236,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        
    
                        if(item.G24_C237 == '1'){
                           if(!$("#G24_C237").is(':checked')){
                               $("#G24_C237").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C237").is(':checked')){
                               $("#G24_C237").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C238").val(item.G24_C238);

                        $("#G24_C239").val(item.G24_C239);

                        $("#G24_C239").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C239,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        
    
                        if(item.G24_C240 == '1'){
                           if(!$("#G24_C240").is(':checked')){
                               $("#G24_C240").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C240").is(':checked')){
                               $("#G24_C240").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C241").val(item.G24_C241);

                        $("#G24_C241").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C241,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C242").val(item.G24_C242);

                        $("#G24_C242").timepicker({
                            timeFormat: 'HH:mm:ss',
                            interval: 5,
                            minTime: '10',
                            maxTime: '18:00:00',
                            defaultTime: item.G24_C242,
                            startTime: '08:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true
                        });
        

                        $("#G24_C243").val(item.G24_C243);
    
                        if(item.G24_C244 == '1'){
                           if(!$("#G24_C244").is(':checked')){
                               $("#G24_C244").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C244").is(':checked')){
                               $("#G24_C244").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C247").val(item.G24_C247);
                        $("#h3mio").html(item.principal);
                    });

     
                } 
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
        });
    }

    function seleccionar_registro(){
        //Seleccinar loos registros de la Lista de navegacion, 
        if ( $("#"+idTotal).length > 0) {
            $("#"+idTotal).click();   
            $("#"+idTotal).addClass('active'); 
            idTotal = 0;
            $(".modalOculto").hide();
        }else{
            $(".CargarDatos :first").click();
        } 
        
    } 

    

    function seleccionar_registro_avanzada(id){
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        
    
                        if(item.G24_C219 == '1'){
                           if(!$("#G24_C219").is(':checked')){
                               $("#G24_C219").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C219").is(':checked')){
                               $("#G24_C219").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C220").val(item.G24_C220);

                        $("#G24_C221").val(item.G24_C221);
    
                        if(item.G24_C222 == '1'){
                           if(!$("#G24_C222").is(':checked')){
                               $("#G24_C222").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C222").is(':checked')){
                               $("#G24_C222").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C223").val(item.G24_C223);

                        $("#G24_C224").val(item.G24_C224);
    
                        if(item.G24_C225 == '1'){
                           if(!$("#G24_C225").is(':checked')){
                               $("#G24_C225").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C225").is(':checked')){
                               $("#G24_C225").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C226").val(item.G24_C226);

                        $("#G24_C227").val(item.G24_C227);
    
                        if(item.G24_C228 == '1'){
                           if(!$("#G24_C228").is(':checked')){
                               $("#G24_C228").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C228").is(':checked')){
                               $("#G24_C228").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C229").val(item.G24_C229);

                        $("#G24_C230").val(item.G24_C230);
    
                        if(item.G24_C231 == '1'){
                           if(!$("#G24_C231").is(':checked')){
                               $("#G24_C231").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C231").is(':checked')){
                               $("#G24_C231").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C232").val(item.G24_C232);

                        $("#G24_C233").val(item.G24_C233);
    
                        if(item.G24_C234 == '1'){
                           if(!$("#G24_C234").is(':checked')){
                               $("#G24_C234").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C234").is(':checked')){
                               $("#G24_C234").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C235").val(item.G24_C235);

                        $("#G24_C236").val(item.G24_C236);
    
                        if(item.G24_C237 == '1'){
                           if(!$("#G24_C237").is(':checked')){
                               $("#G24_C237").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C237").is(':checked')){
                               $("#G24_C237").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C238").val(item.G24_C238);

                        $("#G24_C239").val(item.G24_C239);
    
                        if(item.G24_C240 == '1'){
                           if(!$("#G24_C240").is(':checked')){
                               $("#G24_C240").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C240").is(':checked')){
                               $("#G24_C240").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C241").val(item.G24_C241);

                        $("#G24_C242").val(item.G24_C242);

                        $("#G24_C243").val(item.G24_C243);
    
                        if(item.G24_C244 == '1'){
                           if(!$("#G24_C244").is(':checked')){
                               $("#G24_C244").prop('checked', true);  
                            }
                        } else {
                            if($("#G24_C244").is(':checked')){
                               $("#G24_C244").prop('checked', false);  
                            }
                            
                        }

                        $("#G24_C247").val(item.G24_C247);
                        $("#h3mio").html(item.principal);
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatosHorarios :input').each(function(){
                        $(this).attr('disabled', true);
                    });

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            $("#"+idTotal).addClass('active');
    }

    function vamosRecargaLasGrillasPorfavor(id){
        
    }
</script>
<script src="assets/plugins/Flowchart/flowchart.js"></script>
<script type="text/javascript" id="code">
    var JoseExist = false;
    var colors = {
        blue:   "#00B5CB",
        orange: "#F47321",
        green:  "#C8DA2B",
        gray:   "#888",
        white:  "#F5F5F5"
    }
    function init() {
        if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
        var $ = go.GraphObject.make;  // for conciseness in defining templates
        myDiagram =
            $(go.Diagram, "myDiagramDiv",  // must name or refer to the DIV HTML element
                {
                    initialContentAlignment: go.Spot.Center,
                    allowDrop: true,  // must be true to accept drops from the Palette
                    "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
                    "LinkRelinked": showLinkLabel,
                    "animationManager.duration": 800, // slightly longer than default (600ms) animation
                    "undoManager.isEnabled": true  // enable undo & redo
                }
            );
        // when the document is modified, add a "*" to the title and enable the "Save" button
        myDiagram.addDiagramListener("Modified", function(e) {
            var button = document.getElementById("SaveButton");
                if (button) button.disabled = !myDiagram.isModified;
                    var idx = document.title.indexOf("*");
                if (myDiagram.isModified) {
                    if (idx < 0) document.title += "*";
                } else {
                    if (idx >= 0) document.title = document.title.substr(0, idx);
                }
        });
        // helper definitions for node templates
        function nodeStyle() {
            return [
                // The Node.location comes from the "loc" property of the node data,
                // converted by the Point.parse static method.
                // If the Node.location is changed, it updates the "loc" property of the node data,
                // converting back using the Point.stringify static method.
                new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
                {
                    // the Node.location is at the center of each node
                    locationSpot: go.Spot.Center,
                    //isShadowed: true,
                    //shadowColor: "#888",
                    // handle mouse enter/leave events to show/hide the ports
                    mouseEnter: function (e, obj) { showPorts(obj.part, true);  },
                    mouseLeave: function (e, obj) { showPorts(obj.part, false);},
                    doubleClick:function(e, obj){
                        LlamarModal(obj.je.tipoPaso, obj.je.key, obj.je.category );                    
                    }
                }
            ];
        }
        // Define a function for creating a "port" that is normally transparent.
        // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
        // and where the port is positioned on the node, and the boolean "output" and "input" arguments
        // control whether the user can draw links from or to the port.
    function makePort(name, spot, output, input) {
        // the port is basically just a small circle that has a white stroke when it is made visible
        return $(go.Shape, "Rectangle",
                   {
                        fill: "transparent",
                        stroke: null,  // this is changed to "white" in the showPorts function
                        desiredSize: new go.Size(8, 8),
                        alignment: spot,
                        alignmentFocus: spot,  // align the port on the main Shape
                        portId: name,  // declare this object to be a "port"
                        fromSpot: spot,
                        toSpot: spot,  // declare where links may connect at this port
                        fromLinkable: output,
                        toLinkable: input,  // declare whether the user may draw links to/from here
                        cursor: "pointer" // show a different cursor to indicate potential link point
                    });
        }
        // define the Node templates for regular nodes
        var lightText = 'whitesmoke';

        myDiagram.nodeTemplateMap.add("",  // the default category
            $(go.Node, "Spot", nodeStyle(),
            // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#C8DA2B",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "18px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // four named ports, one on each side:
                makePort("T", go.Spot.Top, false, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("EnPhone",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            text: "\uf0c0",
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        

        // replace the default Link template in the linkTemplateMap
        myDiagram.linkTemplate =
            $(go.Link,  // the whole link panel
            {
                routing: go.Link.AvoidsNodes,
                curve: go.Link.JumpOver,
                corner: 5,
                toShortLength: 4,
                relinkableFrom: true,
                relinkableTo: true,
                reshapable: true,
                resegmentable: true,
                // mouse-overs subtly highlight links:
                mouseEnter: function(e, link) {
                    link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)";
                },
                mouseLeave: function(e, link) {
                    link.findObject("HIGHLIGHT").stroke = "transparent";
                }
            },
            new go.Binding("points").makeTwoWay(),
            $(go.Shape,  // the highlight shape, normally transparent
                {
                    isPanelMain: true,
                    strokeWidth: 8,
                    stroke: "transparent",
                    name: "HIGHLIGHT"
                }
            ),
            $(go.Shape,  // the link path shape
                {
                    isPanelMain: true,
                    stroke: "gray",
                    strokeWidth: 2
                }
            ),
            $(go.Shape,  // the arrowhead
                {
                    toArrow: "standard",
                    stroke: null,
                    fill: "gray"
                }
            ),
            $(go.Panel, "Auto",  // the link label, normally not visible
                {
                    visible: false,
                    name: "LABEL",
                    segmentIndex: 2,
                    segmentFraction: 0.5
                },
                new go.Binding("visible", "visible").makeTwoWay(),
                $(go.Shape, "Rectangle",  // the label shape
                {
                    fill: "#F8F8F8",
                    stroke: null
                }),
                $(go.TextBlock, "??",  // the label
                {
                    textAlign: "center",
                    font: "8pt helvetica, arial, sans-serif",
                    stroke: "#333333",
                    editable: true
                },
                new go.Binding("text").makeTwoWay())
            )
        );
        // Make link labels visible if coming out of a "conditional" node.
        // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
        function showLinkLabel(e) {
            var label = e.subject.findObject("LABEL");
            if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Circle");
        }
        // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
        myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
        myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;
        load();  // load an initial diagram from some JSON text
        // initialize the Palette that is on the left side of the page
        myPalette =
            $(go.Palette, "myPaletteDiv",  // must name or refer to the DIV HTML element
            {
                "animationManager.duration": 800, // slightly longer than default (600ms) animation
                nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
                model: new go.GraphLinksModel([  // specify the contents of the Palette
                    { category: "EnPhone", text: "Enviar llamadas a Agentes", tipoPaso : 10, figure : "Circle"},
                ])
            });
        // The following code overrides GoJS focus to stop the browser from scrolling
        // the page when either the Diagram or Palette are clicked or dragged onto.
        function customFocus() {
            var x = window.scrollX || window.pageXOffset;
            var y = window.scrollY || window.pageYOffset;
            go.Diagram.prototype.doFocus.call(this);
            window.scrollTo(x, y);
        }
        myDiagram.doFocus = customFocus;
        myPalette.doFocus = customFocus;
    } // end init
    
    // Make all ports on a node visible when the mouse is over the node
    function showPorts(node, show) {
        var diagram = node.diagram;
        if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
        node.ports.each(function(port) {
            port.stroke = (show ? "white" : null);
        });
    }
    // Show the diagram's model in JSON format that the user may edit
    function save() {
        document.getElementById("mySavedModel").value = myDiagram.model.toJson();
        myDiagram.isModified = false;
    }
    function load() {
        myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
    }

    $(document).ready(function(){
        init();
    });


    function LlamarModal(tipoPaso, key, category){
        var invocador = tipoPaso;
        var llaveInvocar = key;

        if(invocador == 1){
            window.location.href = 'index.php?page=llam_saliente&id_paso='+llaveInvocar;
        }
        
    }
</script>

