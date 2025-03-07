
<!DOCTYPE html>
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>QA</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css"/>
        <!-- Ionicons -->
        <link rel="stylesheet" href="assets/ionicons-master/css/ionicons.min.css"/>
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/AdminLTE.min.css"/>
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="assets/css/skins/_all-skins.min.css"/>
        <!-- iCheck -->
        <link rel="stylesheet" href="assets/plugins/iCheck/flat/blue.css"/>
        <!-- Morris chart -->
        <link rel="stylesheet" href="assets/plugins/morris/morris.css"/>
        <!-- jvectormap -->
        <link rel="stylesheet" href="assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css"/>
        <!-- Date Picker -->
        <link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css"/>
        <!-- Daterange picker -->
        <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css"/>
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="assets/timepicker/jquery.timepicker.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css" />
        <link rel="stylesheet" href="assets/css/alertify.core.css"/>
        <link rel="stylesheet" href="assets/css/alertify.default.css"/>
        <link rel="stylesheet" href="assets/plugins/select2/select2.min.css" />
        <link rel="stylesheet" href="assets/plugins/sweetalert/sweetalert.css"/>
        <script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- iCheck -->
        <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
        <link rel="shortcut icon" href="assets/img/logo_dyalogo_mail.png">
        <link rel="stylesheet" type="text/css" href="assets/pivotTable/pivot.min.css">

        <style type="text/css">
            [class^='select2'] {
                border-radius: 0px !important;
            }

            .modal-lg {
                width: 90%;
            }
            .filasMalla tr td input{
                width: 65px !important;
            }
            .validacion{
                border: 2px solid red;
            }
            .tamano{
                font-size:12px;
            }
            .centrado{
                text-align: center;
            }
            .pausaFija td input{
            width: 65px !important; 
            }
            .pausaNoFija td input{
            width: 65px !important; 
            }
            
            label.error { float: none; color: red; padding-left: .5em; vertical-align: middle; font-size: 12px; }
        </style>

    </head>

    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <section class="content">
            <section class="content" style="margin-top: 30px;">
                <div class="row">
                    <div id="Error" class=" col-sm-10 col-sm-offset-1" hidden>
                        <div class="alert alert-error mensaje">
                            <ul>
                                <li> ¡La Calificación No Existe! </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="box box-primary" style="padding-left: 20px;">
                            <div class="box-header with-border">
                            <h3 class="box-title">Calificacion de la Gestion: #<?php if (isset($_GET["G"])) { echo $_GET["G"]; } ?></h3>
                            </div>
                            <div class="box-body">
                                <form id="FormUpdate" method="POST" role = "form" novalidate>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fechaGestion" id="LblfechaGestion">FECHA DE LA GESTION</label>
                                                <input readonly type="text" class="form-control input-sm" id="fechaGestion" value="" name="fechaGestion" placeholder="FECHA DE LA GESTION">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="agente" id="Lblagente">AGENTE</label>
                                                <input readonly type="text" class="form-control input-sm" id="agente" value="" name="agente" placeholder="AGENTE">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="datoPrincipal" id="LbldatoPrincipal">DATO PRINCIPAL</label>
                                                <input readonly type="text" class="form-control input-sm" id="datoPrincipal" value="" name="datoPrincipal" placeholder="DATO PRINCIPAL">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="datoSecundario" id="LbldatoSecundario">DATO SECUNDARIO</label>
                                                <input readonly type="text" class="form-control input-sm" id="datoSecundario" value="" name="datoSecundario" placeholder="DATO SECUNDARIO">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="fechaEvaluacion" id="LblfechaEvaluacion">FECHA EVALUACION</label>
                                                <input readonly type="text" class="form-control input-sm" id="fechaEvaluacion" value="" name="fechaEvaluacion" placeholder="FECHA EVALUACION">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="calidad" id="Lblcalidad">CALIDAD</label>
                                                <input readonly type="text" class="form-control input-sm" id="calidad" value="" name="calidad" placeholder="CALIDAD">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="calificacion" id="Lblcalificacion">CALIFICACION</label>
                                                <input readonly type="text" class="form-control input-sm" id="calificacion" value="" name="calificacion" placeholder="CALIFICACION">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="comentarioCalidad" id="Lblcomentario">COMENTARIO CALIDAD</label>
                                                <textarea readonly type="text" class="form-control input-sm" id="comentarioCalidad" value="" name="comentarioCalidad" placeholder="COMENTARIO CALIDAD"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="comentario" id="Lblcomentario">COMENTARIO</label>
                                                <textarea type="text" class="form-control input-sm" id="comentario" value="" name="comentario" placeholder="COMENTARIO"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <!--Audio Con Controles -->
                                            <audio id="divAudio" controls="controls" style="width: 100%">
                                                <source id="InputAudio" src="" type="audio/mp3"/>
                                                <!-- <source id="btn" src="https://onuris.dyalogo.cloud:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=1707333835.7095&uid2=1707333835.7095&canal=telefonia&streaming=true" type="audio/mp3"/> -->
                                            </audio>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?=$_GET["SC"]?>" name="sc_h" id="sc_h">
                                    <input type="hidden" value="<?=$_GET["G"]?>" name="idg_h" id="idg_h">
                                    <input type="hidden" value="<?=$_GET["H"]?>" name="idh_h" id="idh_h">
                                </form>
                                <div class="box-footer with-border">
                                    <button id="Save" class="btn btn-info">Guardar Comentario</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </body>

    <!-- Bootstrap 3.3.6 -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/select2/select2.full.min.js"></script>
    <!-- Jquery // Sparkline - Slimscroll -->
    <script src="assets/jqueryUI/jquery-ui.min.js"></script>
    <script src="assets/timepicker/jquery.timepicker.js"></script>
    <script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- Date Picker -->
    <script rel="stylesheet" src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Jqgrid -->
    <script src="assets/Guriddo_jqGrid_/js/i18n/grid.locale-es.js" type="text/javascript"></script>
    <script src="assets/Guriddo_jqGrid_/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script src="assets/plugins/ckeditor/ckeditor.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/numeric.js"></script>
    <script src="assets/js/alertify.js"></script>
    <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="assets/js/blockUi.js"></script>
    <!-- iCheck -->
    <script src="assets/plugins/iCheck/icheck.min.js"></script>
    <script src="assets/js/jquery.validate.js"></script>
    <!--script pivot Table-->
    <script type="text/javascript" src="assets/pivotTable/d3.min.js"></script>
    <script type="text/javascript" src="assets/pivotTable/c3.min.js"></script>
    <script src="assets/pivotTable/plotybasic.min.js"></script>
    <script type="text/javascript" src="assets/pivotTable/pivot.js"></script>
    <script type="text/javascript" src="assets/pivotTable/export_renderers.js"></script>
    <script type="text/javascript" src="assets/pivotTable/d3_renderers.js"></script>
    <script type="text/javascript" src="assets/pivotTable/c3_renderers.js"></script>
    <script type="text/javascript" src="assets/pivotTable/plotly_renderers.js"></script>
    <script type="text/javascript" src="assets/pivotTable/show_code.js"></script>
    
    <!-- Funciones Formulario -->
    <script>
        //Cargar Datos Calificacion
        $(function(){
            $.ajax({
                url: 'QA_CRUD.php?callDatos=si',  
                type: 'POST',
                data : {sc_h : $("#sc_h").val(), idg_h : $("#idg_h").val(),idh_h : $("#idh_h").val()},
                dataType : 'json',
                success: function(data){
                    if (data[0].error) {
                        $("#Error").attr("hidden",false)
                        $("#Save").attr("disabled",true)
                    }else{
                        var DatosCalificacion= data[0];
                        //console.log("DatosCalificacion: ", DatosCalificacion);

                        var DatoPrincipal= DatosCalificacion.CALHIS_DatoPrincipalScript_b;
                        if(DatoPrincipal == "") {
                            DatoPrincipal= "Sin Dato Principal";
                        }

                        var ComentarioAgente= DatosCalificacion.CALHIS_ComentAgente_b;
                        if((ComentarioAgente != null) || (ComentarioAgente != "")){
                            $("#comentario").prop('disabled', true);
                        }else{
                            $("#comentario").prop('disabled', false);
                        }

                        $("#fechaGestion").val(DatosCalificacion.CALHIS_FechaGestion_b);
                        $("#agente").val(DatosCalificacion.CALHIS_ConsInte__USUARI_Age_b);
                        $("#datoPrincipal").val(DatoPrincipal);
                        $("#datoSecundario").val(DatosCalificacion.CALHIS_DatoSecundarioScript_b);
                        $("#fechaEvaluacion").val(DatosCalificacion.CALHIS_FechaEvaluacion_b);
                        $("#calidad").val(DatosCalificacion.CALHIS_ConsInte__USUARI_Cal_b);
                        $("#calificacion").val(DatosCalificacion.CALHIS_Calificacion_b);
                        $("#comentario").val(ComentarioAgente);
                        $("#comentarioCalidad").val(DatosCalificacion.CALHIS_ComentCalidad_b);
                        

                    }
                }
            });
        });

        //Consultar Audio
        $(document).ready(function() {
            var IdGuion= $("#sc_h").val();
            var IdGestion= $("#idg_h").val();
            var IdCalificacion= $("#idh_h").val();

            $.ajax({
                url: 'QA_CRUD.php?ConsultarAudio=si',
                type     : 'POST',
                data     : {IdGuion:IdGuion, IdGestion:IdGestion, IdCalificacion:IdCalificacion},
                success  : function(data){
                    //console.log("dataAudio: ", data);
                    var Audio= $("#divAudio");
                    $("#InputAudio").attr("src",data+"&streaming=true").appendTo(Audio);
                    Audio.load();
                }
            });

        });

        //Guardar Comentario Agente
        $("#Save").click(function(){
            var Comentario= $("#comentario").val();
            //console.log(Comentario);
            if((Comentario == null) || (Comentario == "")){
                swal({
                    icon: 'error',
                    title: '🤨 Oops...',
                    text: 'Debes Agregar Un Comentario',
                    confirmButtonColor: '#2892DB'
                });
            }else{
                Comentario= Comentario.toLowerCase();
                var ArrayComentario= Comentario.split(" ");
                //console.log(ArrayComentario);  
                for (let i = 0; i < ArrayComentario.length; i++) {
                    const Palabra = ArrayComentario[i];
                    if((Palabra == "insert")||(Palabra == "update")||(Palabra == "delete")){
                        swal({
                            icon: 'error',
                            title: '😡 ¡Restringido!',
                            text: 'Estas Ingresando Un Valor No Permitido',
                            confirmButtonColor: '#2892DB'
                        });
                        var Guardar= false;
                        break;
                    }else{
                        var Guardar= true;
                    }
                };
                //console.log("Guardar: ", Guardar);

                if(Guardar == true){
                    swal({
                        title: "¿Estás Seguro?  🤔",
                        text: "¡No Podrás Modificar El Comentario Una Vez Guardado!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#2892DB",
                        confirmButtonText: "Si, Guardar",
                        closeOnConfirm: false
                    },
                    function(){
                        var formData = new FormData($("#FormUpdate")[0]);
                        $.ajax({
                            url: 'QA_CRUD.php?UPDATE=si',  
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data){
                                if (data == 1) {
                                    alertify.success("¡Comentario Guardado!");
                                    swal("¡Guardado!", "Tu Comentario Ha Sido Registrado", "success");
                                    setTimeout(function(){
                                        window.location.reload();
                                    }, 2000);
                                }else{
                                    alertify.error("El Comentario No Se Pudo Guardar, Por Favor Intente Mas Tarde");
                                }
                            }
                        });
                    });
                }

            }

        });
    </script>

</html>
