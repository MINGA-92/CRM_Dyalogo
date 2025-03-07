
<?php

    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    require_once('../helpers/parameters.php');
    if(!isset($_SESSION['LOGIN_OK_MANAGER'])) {
        header('Location:'.base_url.'login');
    }
    include ('idioma.php');
    include ('pages/conexion.php');
    require_once('utils.php');

    $url_crudG1 = base_url."cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php";

?>

<!DOCTYPE html>
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $str_TituloSistema;?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?=base_url?>assets/bootstrap/css/bootstrap.min.css"/>
        <!-- Font Awesome -->
        <!-- <link rel="stylesheet" href="<?=base_url?>assets/font-awesome/css/font-awesome.min.css"/>-->
        <script src="https://kit.fontawesome.com/afd1d090df.js" crossorigin="anonymous"></script>
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?=base_url?>assets/ionicons-master/css/ionicons.min.css"/>
        <!-- Theme style -->
        <link rel="stylesheet" href="<?=base_url?>assets/css/AdminLTE.min.css"/>
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?=base_url?>assets/css/skins/_all-skins.min.css"/>
        <!-- iCheck -->
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/iCheck/flat/blue.css"/>
        <!-- Morris chart -->
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/morris/morris.css"/>
        <!-- jvectormap -->
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css"/>
        <!-- Date Picker -->
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/datepicker/datepicker3.css"/>
        <!-- Daterange picker -->
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/daterangepicker/daterangepicker.css"/>
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="<?=base_url?>assets/timepicker/jquery.timepicker.css"/>
        <!-- DateTime Picker -->
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/DateTimePicker/bootstrap-datetimepicker.min.css"/>
        <link rel="stylesheet" type="text/css" media="screen" href="<?=base_url?>assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css" />
        <link rel="stylesheet" href="<?=base_url?>assets/css/alertify.core.css"/>
        <link rel="stylesheet" href="<?=base_url?>assets/css/alertify.default.css"/>
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/select2/select2.min.css" />
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/sweetalert/sweetalert.css"/>
        <script src="<?=base_url?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- JDBD - Librerias para Higcharts -->
        <script src="<?=base_url?>assets/HighCharts/highcharts.js"></script>
        <script src="<?=base_url?>assets/HighCharts/highcharts-3d.js"></script>
        <script src="<?=base_url?>assets/HighCharts/modules/cylinder.js"></script>
        <script src="<?=base_url?>assets/HighCharts/modules/funnel3d.js"></script>
        <script src="<?=base_url?>assets/HighCharts/modules/exporting.js"></script>
        <script src="<?=base_url?>assets/HighCharts/modules/export-data.js"></script>
        <script src="<?=base_url?>assets/HighCharts/modules/accessibility.js"></script>
        <script src="<?=base_url?>assets/js/moment.min.js"></script>
        <script src="<?=base_url?>assets/js/blockUi.js"></script>
        <script src="<?=base_url?>assets/js/numeric.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="<?=base_url?>assets/jqueryUI/jquery-ui.min.js"></script>
        <!-- DateTime Picker -->
        <script src="<?=base_url?>assets/plugins/DateTimePicker/bootstrap-datetimepicker.min.js"/></script>
        <script rel="stylesheet" src="<?=base_url?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- JDBD - Librerias para Higcharts -->
        <link rel="stylesheet" href="<?=base_url?>assets/plugins/iCheck/square/blue.css">
        <link rel="shortcut icon" href="<?=base_url?>assets/img/logo_dyalogo_mail.png">
        <link rel="stylesheet" type="text/css" href="<?=base_url?>assets/pivotTable/pivot.min.css">
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
            
            
            /* The switch - the box around the slider */
            .switch {
                position: relative;
                display: inline-block;
                width: 47px;
                height: 22px;
            }

            /* Hide default HTML checkbox */
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            /* The slider */
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 20px;
                width: 20px;
                top: 1px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: #2196F3;
            }

            input:focus + .slider {
                box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(20px);
                -ms-transform: translateX(20px);
                transform: translateX(20px);
            }

            /* Rounded sliders */
            .slider.round {
                border-radius: 28px;
            }

            .slider.round:before {
                border-radius: 50%;
            }

        </style>
        <script type="text/javascript">
            function autofitIframe(id){
                if (!window.opera && document.all && document.getElementById){
                    id.style.height=id.contentWindow.document.body.scrollHeight;
                }else if(document.getElementById) {
                    id.style.height=id.contentDocument.body.scrollHeight+"px";
                }
            }
        </script>
    </head>
    
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
    <?php if(!isset($_GET['view'])) : ?>
        <div class="modal fade-in" id="Visor" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog" style="width: 50%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                        <h4 class="modal-title">VISOR</h4>
                        <div class="text-center" ><button id="impPDF" readonly class="btn  btn-danger" >PDF</button></div>
                    </div>
                    <div class="modal-body">
                        <iframe id="frameVisor" src="" style="width: 100%; height: 600px;"  >
                        
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade-in" id="enviarReportes" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                        <h4 class="modal-title">Enviar Reportes</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-12">
                            <div class="form-group">
                                <p>Para ingresar mas de un correo se deben <strong>SEPARAR</strong>  por una coma ( , ).</p>
                                <input type="text" class="form-control" id="cajaCorreos" name="cajaCorreos" placeholder="(Ejemplo1@ejem.com,Ejemplo2@ejem.com)">
                                <span class="help-block"></span>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>ENVIAR REPORTES A MI CORREO</label>&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="siEnviarme" id="siEnviarme">
                            </div>
                            <div class="col-md-8 text-right">
                                <img hidden id="loading" src="assets/plugins/loading.gif" width="30" height="30">&nbsp;&nbsp;&nbsp;
                                <button id="sendEmails" readonly class="btn btn-primary">Enviar Reportes</button>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade-in" id="envCorreo" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog" style="width: 50%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                        <h4 class="modal-title">Enviar Reportes</h4>
                        <div class="text-center" ><button id="impPDF" readonly class="btn  btn-danger" >PDF</button></div>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-"  style="background-color:#aaa">
                            <h1>Columna 1</h1> 
                            <p>Esto es una prueba de bootstrap.</p>
                        </div>
                        <div class="col-md-4"  style="background-color:#bbb">
                            <h1>Columna 2</h1> 
                            <p>Esto es una prueba de bootstrap.</p>
                        </div>
                        <div class="col-md-4"  style="background-color:#ccc">
                            <h1>Columna 3</h1> 
                            <p>Esto es una prueba de bootstrap.</p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade-in" id="PonerHuesped" data-backdrop="static"  data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="#" method="post" >
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo $str_huesped;?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select name="cmbHuesped" id="cmbHuesped" class="form-control" style="width: 100%;">  
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            
                            <button class="btn btn-success" id="btnGuardar" type="button"><?php echo $str_config_aplicar;?></button>
                            <button class="btn btn-danger" id="btnCancelar"  type="button"><?php echo $str_cancela;?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade-in" id="CambiarHuesped" data-backdrop="static"  data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="#" method="post" >
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo $str_huesped;?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select name="cmbHuesped2" id="cmbHuesped2" class="form-control" style="width: 100%;">
                                    <?php
                                        $Lsql = "SELECT * FROM ".$BaseDatos_general.".huespedes_usuarios JOIN ".$BaseDatos_general.".huespedes ON huespedes.id = huespedes_usuarios.id_huesped WHERE id_usuario = ".$_SESSION['USUARICBX']." ORDER BY nombre ASC";    
                                        $res  = $mysqli->query($Lsql);
                        
                                        $opxciones = "";
                                        while ($key = $res->fetch_object()) {
                                            if($key->id_huesped == $_SESSION['HUESPED']){
                                                echo "<option selected value='".md5(clave_get . $key->id_huesped)."'>".strtoupper($key->nombre)."</option>";
                                            }else{
                                                echo "<option value='".md5(clave_get . $key->id_huesped)."'>".strtoupper($key->nombre)."</option>";
                                            }
                                        }
                                    ?>  
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" id="btnGuardar2" type="button"><?php echo $str_config_aplicar;?></button>
                            <button class="btn btn-danger" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade-in" id="cambiarPerfil" data-backdrop="static"  data-keyboard="false" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="insertPerfil" enctype="multipart/form-data" method="post"  >
                        <div class="modal-header">
                            <h4 class="modal-title" style="text-align: center;"><?php echo $_SESSION['NOMBRES'];?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title text-center" id="nombrePasswordCh"><?php echo $str_passwo_usuario;?></h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                            <label for="inputName">Nueva Contraseña: </label>
                                                <a class="btn btn-outline-info" id="VerPassNueva"><i class="fa fa-eye-slash"></i></a>
                                                <input type="password" class="form-control" id="txrPasswordCh" name="txrPasswordCh" placeholder="***********">
                                            </div>
                                            <div class="form-group">
                                            <label for="inputName">Confirmar Contraseña: </label>
                                                <a class="btn btn-outline-info" id="VerPassConfirmar"><i class="fa fa-eye-slash"></i></a>
                                                <input type="password" class="form-control" id="txrPasswordChR" name="txrPasswordChR" placeholder="***********" disabled>
                                            </div>

                                            <div class="form-group" id="divActual">
                                                <label for="inputName" style="margin-top: 2%;">Contraseña Actual: </label>
                                                <a class="btn btn-outline-info" id="VerPassActual"><i class="fa fa-eye-slash"></i></a>
                                                <input type="password" class="form-control" id="ActualPassword" name="ActualPassword" placeholder="***********" disabled>
                                            </div>
                                        </div>
                                        
                                        <!-- Div Alertext -->
                                        <div id="Alertext" class="form-group">
                                            <p class="text-danger">
                                                <br><label id="Alerta" style="font-size: 92%; text-align: center;" hidden> 🤔 </label></br>
                                            </p>
                                        </div>
                                        <input type="hidden" name="IdUsuario" id="IdUsuario" value="<?php echo $_SESSION['IDENTIFICACION'];?>">
                                        <input type="hidden" name="IdHuesped" id="IdHuesped" value="<?php echo $_SESSION['HUESPED_CRM'];?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Profile Image -->
                                    <div class="box box-default">
                                        <div class="box-body box-profile">
                                            <img id="avatar_Imagen" class="profile-user-img img-responsive img-circle" src="<?php echo $_SESSION['IMAGEN'];?>" alt="User profile picture">
                                            <h3 class="profile-username text-center"><?php echo $_SESSION['NOMBRES'];?></h3>
                                            <p class="text-muted text-center"><?php echo $_SESSION['CARGO'];?></p>

                                            <ul class="list-group list-group-unbordered">
                                                <!--<li class="list-group-item">
                                                    <b>Followers</b> <a class="pull-right">1,322</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Following</b> <a class="pull-right">543</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Friends</b> <a class="pull-right">13,287</a>
                                                </li>-->
                                            </ul>

                                            <input type="file" name="inpFotoPerfil" id="inpFotoPerfil2" class="form-control" accept="image/jpg, image/jpeg" >
                                            <input type="hidden" name="hidUsuari" id="hidUsuari" value="<?php echo $_SESSION['IDENTIFICACION'];?>">
                                            <input type="hidden" name="ruta" value="<?php if(isset($_GET['page'])){ echo $_GET['page'];}else{ echo "dashboard"; }?>">
                                        </div>
                                    <!-- /.box-body -->
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                        <button type="button" name="BtnCambiarPass" id="BtnCambiarPass" class="btn btn-primary" disabled="true">Guardar Cambios</button>
                            <button type="button" id="btnCambiarFoto" class="btn btn-primary" hidden="true"><?php echo $str_guardar;?> Todo</button>
                            <button class="btn btn-danger" id="btnCancelar_2" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade-in" id="ModalAjustesSeguridad" data-backdrop="static"  data-keyboard="false" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="FormAjustesSeguridad" enctype="multipart/form-data" method="post"  >
                        <div class="modal-header">
                            <h2 class="modal-title" style="text-align: center;"> Ajustes De Seguridad </h2>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="col-md-8"> 
                                        Caducidad Contraseñas 
                                    </span>
                                    <div class="col-md-4">
                                        <label class="switch">
                                            <input type="checkbox" id="BtnActivarCaducidad">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="col-md-12"> 
                                        <div class="box" id="DivCaducidad"></div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="TxtNumeroDias">Contraseña Valida Por: </label>
                                            <input type="number" class="form-control" id="TxtNumeroDias" name="TxtNumeroDias" placeholder="90">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 11%; margin-left: -5%;" >
                                        <strong id="lblCaducidad"> Días </strong>
                                    </div>
                                    
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="TxtNotificar">Notificar Al Usuario: </label>
                                            <input type="number" class="form-control" id="TxtNotificar" name="TxtNotificar" placeholder="2">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-top: 8%; margin-left: -5%;">
                                        <strong id="lblCaducidad_2"> Días Antes </strong>
                                    </div>
                                    
                                </div>

                                <div class="col-md-6">
                                    
                                    <spam class="col-md-8"> 
                                        Bloqueo Automático
                                    </spam>
                                    <div class="col-md-4">
                                        <label class="switch">
                                            <input type="checkbox" id="BtnActivarBloqueo">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="box" id="DivBloqueo"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="TxtNumeroIntentos">Numero De Intentos Fallidos: </label>
                                        <input type="number" class="form-control" id="TxtNumeroIntentos" name="TxtNumeroIntentos" placeholder="3 Intentos">
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="IdProyecto" id="IdProyecto" value="<?php echo $_SESSION['HUESPED_CRM'];?>" hidden>
                                        <input type="text" name="IdSeguridad" id="IdSeguridad" hidden>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer" id="DivGuardarAjustes">
                            <button type="button" name="BtnGuardarAjustes" id="BtnGuardarAjustes" class="btn btn-primary">Guardar Ajustes</button>
                            <button class="btn btn-danger" id="btnCancelarAjustes" data-dismiss="modal" type="button"><?php echo $str_cancela;?></button>
                        </div>

                        <div class="modal-footer" id="DivActualizarAjustes" hidden="true">
                            <button type="button" name="BtnActualizarAjustes" id="BtnActualizarAjustes" class="btn btn-primary">Guardar Ajustes</button>
                            <button class="btn btn-danger" id="btnCancelarAjustes" data-dismiss="modal" type="button"><?php echo $str_cancela;?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endif;?>
        <div class="wrapper">
        <?php if(!isset($_GET['view'])) : ?>
            <header class="main-header" >
                <!-- Logo -->
                <a href="<?=base_url?>index.php" class="logo" >
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><img src="<?=base_url?>assets/img/Logo_blanco.png" style="width: 100%;" ></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><img src="<?=base_url?>assets/img/Logo_blanco.png" style="width: 50%;"></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" >
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <form class="navbar-form navbar-left" role="search" style="color:white;">
                        <div class="form-group">
                            <h5 style="color:white;"><b><?php if(isset($_SESSION['PROYECTO'])){ echo $_SESSION['PROYECTO']; }else{  } ?></b></h5>
                        </div>
                        <div class="form-group">&nbsp;&nbsp;&nbsp;</div>
                    </form>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="<?php echo $_SESSION['IMAGEN'];?>" id="user-image" class="user-image" alt="User Image">
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs">
                                        <?php echo $_SESSION['NOMBRES']; ?>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header" >
                                        <img src="<?php echo $_SESSION['IMAGEN'];?>" id="user-image-menu" class="img-circle" alt="User Image">
                                        <p>
                                            <?php echo $_SESSION['NOMBRES']; ?>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <?php 
                                            if(isset($_SESSION['UNO'])){
                                                if($_SESSION['UNO']){
                                        ?>
                                        <div class="row">
                                            <div class="col-xs-4 text-center">
                                                <a href="#" data-toggle="modal" data-target="#CambiarHuesped" class="btn btn-default btn-flat"><?php echo $str_cambiarHuesped;?></a>
                                            </div>
                                            <div class="col-xs-4 text-center">
                                                <a href="#" data-toggle="modal" data-target="#cambiarPerfil" class="btn btn-default btn-flat">Mi <?php echo $str_cambiarpassword;?></a>
                                            </div>
                                            <div class="col-xs-4 text-center">
                                                <a href="<?=base_url?>salir.php" class="btn btn-default btn-flat"><?php echo $str_salir;?></a>
                                            </div>
                                        </div>
                                        <!-- /.row -->

                                        <?php
                                                }else{
                                        ?>

                                        <div class="row">
                                            <div class="col-xs-4 text-center">
                                                <a href="#" data-toggle="modal" data-target="#CambiarHuesped" class="btn btn-default btn-flat"><?php echo $str_cambiarHuesped;?></a>
                                            </div>
                                            <div class="col-xs-4 text-center">
                                                <a href="#" data-toggle="modal" data-target="#cambiarPerfil" class="btn btn-default btn-flat">Mi <?php echo $str_cambiarpassword;?></a>
                                            </div>
                                            <div class="col-xs-4 text-center">
                                                <a href="<?=base_url?>salir.php" class="btn btn-default btn-flat">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $str_salir;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                            </div>
                                        </div>
                                        <!-- /.row -->
                                        <?php
                                                }
                                            }
                                        ?>
                                        
                                        
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="sidebar-form">
                            <div class="input-group">
                                <!--<input type="text" name="q" id="q" class="form-control" placeholder="<?php echo $str_busqueda;?>">
                                <span class="input-group-btn">
                                    <button type="button" name="search" id="search-btn" class="btn btn-flat">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>-->
                            </div>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header"><?php echo $str_navegacion;?></li>
                        <?php if(!isset($_SESSION['no_admin'])){ ?>
                            <li>
                                <a href="<?=base_url?>modulo/usuarios">
                                    <i class="fa fa-users"></i> <span><?php echo $str_usuarios;?></span>
                                </a>
                            </li>    
                        <?php }?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-folder"></i> <span><?php echo $str_estrategia;?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                
                                <li id="guiones_tipo_2">
                                    <a href="<?=base_url?>modulo/guion/<?=md5(clave_get . '2')?>">
                                        <i class="fa fa-database"></i> <span><?php echo $str_guion_;?></span>
                                    </a>
                                </li>
                                <li id="guiones_tipo_1">
                                    <a href="<?=base_url?>modulo/guion/<?=md5(clave_get . '1')?>">
                                        <i class="glyphicon glyphicon-list-alt"></i> <span><?php echo $str_script_;?></span>
                                    </a>
                                </li>
                                <li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-table"></i> <span>Complementos</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li>                                        
                                            <a href="<?=base_url?>modulo/guion/<?=md5(clave_get . '3')?>">
                                                <i class="glyphicon glyphicon-list-alt"></i> 
                                                <span>Listados simples</span>
                                            </a>
                                        </li>
                                        <li>                                        
                                            <a href="<?=base_url?>modulo/agendador">
                                                <i class="fa fa-calendar" aria-hidden="true"></i> 
                                                <span>Agendadores</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li id="guiones_tipo_4">
                                    <a href="<?=base_url?>modulo/guion/<?=md5(clave_get . '4')?>">
                                        <i class="fa fa-check-square-o"></i> <span><?php echo $str_script_4tre;?></span>
                                    </a>
                                </li>
                                <li class="treeview">
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"><?php echo $str_canales;?></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li><a href="#"><i class="fa fa-circle-o"></i> <?php echo $str_voz; ?></a></li>
                                        <li><a href="#"><i class="fa fa-circle-o"></i> <?php echo $str_chat; ?></a></li>
                                        <li><a href="#"><i class="fa fa-circle-o"></i> <?php echo $str_correo; ?></a></li>
                                    </ul>
                                </li>
                                
                            </ul>
                        </li>
                        <li id="estrategias">
                            <a href="<?=base_url?>modulo/estrategias">
                                <i class="fa fa-sitemap"></i> <span><?php echo $str_flujo;?></span>
                            </a>
                        </li>
                        <li class="treeview" hidden>
                            <a href="#">
                                <i class="fa fa-folder"></i> <span><?php echo $str_reportes;?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li id="detallado">
                                    <a href="<?=base_url?>modulo/detallado">
                                        <i class="fa fa-bars"></i> <span><?php echo $str_detallado;?></span>
                                    </a>
                                </li>
                                <li id="grafico">
                                    <a href="<?=base_url?>modulo/grafico">
                                        <i class="fa fa-bars"></i> <span><?php echo $str_consultas;?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                       
                        
                        <li id="reportes" hidden>
                            <a href="#">
                                <i class="fa fa-adjust"></i> <span><?php echo $str_calidad;?></span>
                            </a>
                        </li>
                        <li id="dashboard">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span><?php echo $str_dashboard;?></span>
                            </a>
                            <ul class="treeview-menu">
                                
                                <li id="tiempo_real_1.0">
                                    <a href="<?=base_url?>index.php?page=dashboard">
                                        <span>Tiempo Real 1.0</span>
                                    </a>
                                </li>
                                <li id="tiempo_real_2.0">
                                    <a href="<?=base_url?>index.php?page=TiempoReal">
                                        <span>Tiempo Real 2.0</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="<?=base_url?>modulo/detallado">
                                <i class="fa fa-table"></i> <span><?php echo $str_detallado;?></span>
                            </a>
                        </li>

                        <li class="treeview">
                            <a href="<?php if($_SESSION['CARGO'] == "administrador-avanzado" || $_SESSION['CARGO'] == "owner" || $_SESSION['CARGO'] == "super-administrador" ) echo base_url."modulo/canalesComunicacion" ?>" id="communicationsButton">
                                <i class="fas fa-phone-square-alt"></i> <span><?php echo $str_canales_comunicacion;?></span>
                            </a>
                        </li>

                        <li class="treeview">
                            <a href="<?php if($_SESSION['CARGO'] == "administrador-avanzado" || $_SESSION['CARGO'] == "owner" || $_SESSION['CARGO'] == "super-administrador") echo base_url."modulo/admin" ?>" id="generalConfigsButton">
                                <i class="fas fa-sliders-h"></i> <span><?php echo $str_admin_customers;?></span>
                            </a>
                        </li>


                        <li class="treeview">
                            <a onclick="AjustesSeguridad();">
                                <i class="fa fa-lock"></i> <span> Ajustes De Seguridad </span>
                            </a>
                        </li>
                        <script>
                            function AjustesSeguridad() {
                                $("#ModalAjustesSeguridad").modal();
                            }
                        </script>

                        
                        <!--<li id="dashboard">
                            <a href="index.php?page=dashboard">
                                <i class="fa fa-dashboard"></i> <span><?php echo $str_dashboard;?></span>
                            </a>
                        </li>
                        <li id="estrategias">
                            <a href="index.php?page=estrategias">
                                <i class="fa fa-adjust"></i> <span><?php echo $str_estrategia;?></span>
                            </a>
                        </li>
                        <li id="usuarios">
                            <a href="index.php?page=usuariosG1">
                                <i class="fa fa-users"></i> <span><?php echo $str_usuarios;?></span>
                            </a>
                        </li>
                        <li id="carga">
                            <a href="index.php?page=carga">
                                <i class="fa fa-file-excel-o"></i> <span><?php echo $str_carga;?></span>
                            </a>
                        </li>
                        <li id="guiones">
                            <a href="index.php?page=guion">
                                <i class="fa fa-table"></i> <span><?php echo $str_guion_;?></span>
                            </a>
                        </li>
                        <!--<li id="guiones">
                            <a href="index.php?page=guion&tipo=1">
                                <i class="fa fa-table"></i> <span><?php echo $str_script_;?></span>
                            </a>
                        </li>
                        <li id="campains">
                            <a href="index.php?page=campains">
                                <i class="fa fa-bell"></i> <span><?php //echo $str_campan;?></span>
                            </a>
                        </li>-->
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
        <?php endif;?>

            <?php
                //Contenido
                if(isset($_GET['page'])){
                    switch ($_GET['page']) {
                        case 'dashboard':
                            include('pages/Dashboard/dashboard.php');
                            break;
                        case 'TiempoReal':
                            include('pages/Dashboard/TiempoReal.php');
                            break;
                        case 'estrategias':
                            //include('pages/Estrategias/estrategias.php');
                            include ('cruds/DYALOGOCRM_SISTEMA/G2/G2.php');
                            break;

                        case 'detallado':
                            //include('pages/Estrategias/estrategias.php');
                            include ('pages/dy_temporales/detallado.php');
                            break;  

                        case 'grafico':
                            //include('pages/Estrategias/estrategias.php');
                            include ('grafico.php');
                            break;  

                        case 'pasos':
                            //include('pages/Estrategias/estrategias.php');
                            include ('cruds/DYALOGOCRM_SISTEMA/G3/G3.php');
                            break;

                        case 'conectores':
                            //include('pages/Estrategias/estrategias.php');
                            include ('cruds/DYALOGOCRM_SISTEMA/G9/G9.php');
                            break;

                        case 'web_form':
                            include ('cruds/DYALOGOCRM_SISTEMA/G13/G13.php');
                            break;
                        
                        case 'dashEstrat':
                            include('pages/Estrategias/dashboardEstrategias.php');
                            break;

                        case 'usuarios':
                            include ('cruds/DYALOGOCRM_SISTEMA/G1/G1.php');
                            break;

                        case 'carga':
                            include ('carga/carga.php');
                            break;

                        case 'flujograma':
                            include ('cruds/DYALOGOCRM_SISTEMA/G2/flujograma.php');
                            break;

                        case 'llam_saliente':
                            include ('cruds/DYALOGOCRM_SISTEMA/G23/G23.php');
                            break;

                        case 'campan':
                            include ('cruds/DYALOGOCRM_SISTEMA/G10/G10.php');
                            break;

                        case 'horarios':
                            include ('cruds/DYALOGOCRM_SISTEMA/G24/G24.php');
                            break;
                        
                        case 'guion':
                            include ('cruds/DYALOGOCRM_SISTEMA/G5/G5.php');
                            break;

                        case 'showData':
                            include ('cruds/DYALOGOCRM_SISTEMA/G5/dataPreview.php');
                            break;

                        case 'campains':
                            include ('cruds/DYALOGOCRM_SISTEMA/G10/G10.php');
                            break;

                        case 'entrantes':
                            include ('cruds/DYALOGOCRM_SISTEMA/G10/G10_v2.php');
                            break;

                        case 'backoffice':
                            include ('cruds/DYALOGOCRM_SISTEMA/G10/G10_v3.php');
                            break;
                        
                        case 'error':
                            include('page_error.php');
                            break;

                        case 'agendador':
                            include('cruds/DYALOGOCRM_SISTEMA/G33/G33.php');
                            break;

                        case 'admin':
                            include('pages/configHuesped/generalConfigs/admin.php');
                            break;

                        case 'canalesComunicacion':
                            include('pages/configHuesped/channels/channels.php');
                            break;

                        case 'marcadorRobotico':
                            include ('cruds/DYALOGOCRM_SISTEMA/G36/G36.php');
                            break;

                        default:
                            include ('page_error.php');
                            break;
                    }
                }else{
                    include('pages/Dashboard/dashboard.php');
                }
            ?>
        </div>
        
        <!-- jQuery 2.2.3 -->
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?=base_url?>assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?=base_url?>assets/plugins/select2/select2.full.min.js"></script>
        <!-- Sparkline -->
        <script src="<?=base_url?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
        <!-- Slimscroll -->
        <script src="<?=base_url?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="<?=base_url?>assets/timepicker/jquery.timepicker.js"></script>
        <!-- Jqgrid -->
        <script src="<?=base_url?>assets/Guriddo_jqGrid_/js/i18n/grid.locale-es.js" type="text/javascript"></script>
        <script src="<?=base_url?>assets/Guriddo_jqGrid_/js/jquery.jqGrid.min.js" type="text/javascript"></script>
        <script src="<?=base_url?>assets/plugins/ckeditorv2/ckeditor.js"></script>
        <!-- AdminLTE App -->
        <script src="<?=base_url?>assets/js/app.min.js"></script>
        <script src="<?=base_url?>assets/js/alertify.js"></script>
        <script src="<?=base_url?>assets/plugins/sweetalert/sweetalert.min.js"></script>
        <!-- iCheck -->
        <script src="<?=base_url?>assets/plugins/iCheck/icheck.min.js"></script>
        <script src="<?=base_url?>assets/js/jquery.validate.js"></script>
        <!--script pivot Table-->
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/d3.min.js"></script>
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/c3.min.js"></script>
        <script src="<?=base_url?>assets/pivotTable/plotybasic.min.js"></script>
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/pivot.js"></script>
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/export_renderers.js"></script>
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/d3_renderers.js"></script>
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/c3_renderers.js"></script>
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/plotly_renderers.js"></script>
        <script type="text/javascript" src="<?=base_url?>assets/pivotTable/show_code.js"></script>
        <!--fin pivot Table-->
        <script type="text/javascript">
            busquedas = {
                buscar : function(valorBusqueda){
                    $.ajax({
                        url    : 'buscar_menu.php',
                        type   : 'post',
                        data   : { q : valorBusqueda },
                        success: function(data){
                            $("#JoseMenu").html(data);
                            $("#search-btn").click(function(){
                                busquedas.buscar($("#q").val());
                            });
                            $("#q").keypress(function(e) {
                                if(e.which == 13) {
                                    busquedas.buscar($("#q").val());
                                }
                            });
                        }
                    });
                },

                colocarPausa : function(x){
                    $.ajax({
                        url    : 'buscar_menu.php?cambioEstado=YES',
                        type   : 'post',
                        data   : { cambioDetado : x },
                        dataType : 'json',
                        success: function(data){
                            $("#estadosJodidos").html("<i class=\"fa fa-circle "+ data.color +"\"></i> "+data.estado);
                        }
                    });    
                }
            }

            $(function(){
                $("#search-btn").click(function(){
                    busquedas.buscar($("#q").val());
                });

                $("#q").keypress(function(e) {
                    if(e.which == 13) {
                        busquedas.buscar($("#q").val());
                    }
                });

                $("#cambioDetado").on('change', function(){
                    busquedas.colocarPausa($(this).val());
                });

            
                $('#inpFotoPerfil2').on('change', function(e){
                    var imax = $(this).attr('valor');
                    var imagen = this.files[0];
                    console.log(imagen);
                    /* Validar el tipo de imagen */
                    if(imagen['type'] != 'image/jpeg' ){
                        $('#inpFotoPerfil2').val('');
                        swal({
                            icon: 'error',
                            title: '¡Error, Formato Incorrecto! 😅 ',
                            text: 'El Archivo Debe Estar En Formato JPG',
                            confirmButtonColor: '#2892DB'
                        });
                    }else if(imagen['size'] > 2000000 ) {
                        $('#inpFotoPerfil2').val('');
                        swal({
                            icon: 'error',
                            title: '¡Error, Muy Pesado! ⚖ ',
                            text: 'El Archivo No Debe Pesar Mas De 2MB',
                            confirmButtonColor: '#2892DB'
                        });
                    }else{
                        if(imagen['type'] == 'image/jpeg' ){
                            var datosImagen = new FileReader();
                            datosImagen.readAsDataURL(imagen);

                            $(datosImagen).on("load", function(event){
                                var rutaimagen = event.target.result;
                                $('#avatar_Imagen').attr("src",rutaimagen);                               
                            });
                            $("#ActualPassword").prop("disabled", false);
                            $("#BtnCambiarPass").prop("disabled", false);
                        }
                        
                    }   
                }); 
            });

            $('#impPDF').click(function(){
                $('#frameVisor').get(0).contentWindow.focus(); 
                $("#frameVisor").get(0).contentWindow.print(); 
            });

        </script>

        <!-- Funciones Contraseña y Huesped -->
        <script type="text/javascript">
            $(function(){
                jQuery.extend(jQuery.validator.messages, {
                    required: "Este campo es obligatorio.",
                    remote: "Por favor, rellena este campo.",
                    email: "Por favor, escribe una dirección de correo válida",
                    url: "Por favor, escribe una URL válida.",
                    date: "Por favor, escribe una fecha válida.",
                    dateISO: "Por favor, escribe una fecha (ISO) válida.",
                    number: "Por favor, escribe un número entero válido.",
                    digits: "Por favor, escribe sólo dígitos.",
                    creditcard: "Por favor, escribe un número de tarjeta válido.",
                    equalTo: "Por favor, escribe el mismo valor de nuevo.",
                    accept: "Por favor, escribe un valor con una extensión aceptada.",
                    maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
                    minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres."),
                    rangelength: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
                    range: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1}."),
                    max: jQuery.validator.format("Por favor, escribe un valor menor o igual a {0}."),
                    min: jQuery.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
                });

                $("#cmbHuesped2").select2();
                
                <?php
                    if(!isset($_SESSION['PROYECTO'])){
                        $Lsql = "SELECT * FROM ".$BaseDatos_general.".huespedes_usuarios JOIN ".$BaseDatos_general.".huespedes ON huespedes.id = huespedes_usuarios.id_huesped WHERE id_usuario = ".$_SESSION['USUARICBX']." ORDER BY nombre ASC";    
                        $res  = $mysqli->query($Lsql);
                        if($res->num_rows > 1){

                            $huesped_list = [];

                            $opxciones = "<option value='0'>SELECCIONE</option>";
                            while ($key = $res->fetch_object()) {
                                $opxciones .= "<option value='".md5 (clave_get . $key->id_huesped)."'>".strtoupper($key->nombre)."</option>";
                                array_push($huesped_list, $key->id_huesped);
                            }
                            echo " $(\"#cmbHuesped\").html(\"".$opxciones."\");";
                            echo " $(\"#cmbHuesped\").select2();";
                            echo " $(\"#PonerHuesped\").modal();";
                            $_SESSION['UNO'] = false;
                            $_SESSION['HUESPED_LIST'] = $huesped_list;
                        }else{
                            while ($key = $res->fetch_object()) {
                                $_SESSION['PROYECTO'] = strtoupper($key->nombre);
                                $_SESSION['HUESPED']  = strtoupper($key->id_huesped);
                                $_SESSION['UNO'] = true;
                                echo 'window.location.href = "'.base_url.'index.php?page=TiempoReal";';

                            }
                            
                        }
                    }
                ?>

                // Se deshabilita el boton de configuraciones generales
                <?php if($_SESSION['CARGO'] != "owner" &&  $_SESSION['CARGO'] != "administrador-avanzado" && $_SESSION['CARGO'] != "super-administrador" ){ ?>
                    $("#generalConfigsButton").click((e) => {
                        e.preventDefault();
                        alertify.error("<?php echo $str_permisos_configuraciones_generales; ?>");
                    });

                    $("#communicationsButton").click((e) => {
                        e.preventDefault();
                        alertify.error("<?php echo $str_permisos_configuraciones_generales; ?>");
                    });

                <?php } ?>


                $("#btnGuardar").click(function(){
                    if($("#cmbHuesped").val() != 0){
                        $.ajax({
                            url     : "cambiar_huesped.php",
                            type    : "post",
                            data    : { huesped : $("#cmbHuesped").val() }, 
                            success : function(data){
                                if(data == "1"){
                                    window.location.href = "<?=base_url?>index.php?page=TiempoReal";
                                }
                            }
                        });
                    }else{
                        alertify.error("<?php echo $str_huesped; ?>");
                    }
                    
                });

                $("#btnGuardar2").click(function(){
                    $.ajax({
                        url     : "<?=base_url?>cambiar_huesped.php",
                        type    : "post",
                        data    : { huesped : $("#cmbHuesped2").val() }, 
                        success : function(data){
                            if(data == "1"){
                                window.location.href = "<?=base_url?>index.php?page=TiempoReal";
                            }
                        }
                    });
                });

                $("#btnCancelar").click(function(){
                    alertify.error("<?php echo $str_huesped; ?>");
                });

                
                //Funciones De Perfil - Contraseña y Foto
                $(document).ready(function(){
                    $("#btnCambiarFoto").hide();
                });

                //Funcion para validar si las contraseñas son iguales
                const validarPassword = () => {
                    const txrPasswordCh = document.getElementById('txrPasswordCh');
                    const txrPasswordChR = document.getElementById('txrPasswordChR');
                    const btnCambiarFoto = document.getElementById('btnCambiarFoto');
                    const form_group_pass2 = txrPasswordChR.closest('.form-group');

                    // password1
                    txrPasswordCh.oninput = (e) => {
                        const p2 = form_group_pass2.querySelector('p');
                        if (e.target.value === txrPasswordChR.value){
                            btnCambiarFoto.removeAttribute('disabled');
                            txrPasswordChR.closest('.form-group').classList.remove('has-error');
                            $("#Alerta").css("color", "green");
                            $("#Alerta").text(" ✔️ ¡Las Contraseñas Coinciden!");
                            $("#BtnCambiarPass").prop("disabled", false);
                            $("#ActualPassword").prop("disabled", false);
                            if(p2 !== null){
                                p2.remove();
                            }
                
                        }else{
                            btnCambiarFoto.setAttribute('disabled', true)
                            txrPasswordChR.closest('.form-group').classList.add('has-error');
                            $("#Alerta").css("color", "red");
                            $("#Alerta").text("❌ ¡Las Contraseñas Deben Coincidir! ");
                            $("#BtnCambiarPass").prop("disabled", true);

                        }
                    }

                    // password2
                    txrPasswordChR.oninput = (e) => {
                        const p2 = form_group_pass2.querySelector('p');
                        if (e.target.value === txrPasswordCh.value){
                            btnCambiarFoto.removeAttribute('disabled');
                            txrPasswordChR.closest('.form-group').classList.remove('has-error');
                            $("#Alerta").css("color", "green");
                            $("#Alerta").text(" ✔️ ¡Las Contraseñas Coinciden!");
                            $("#BtnCambiarPass").prop("disabled", false);
                            $("#ActualPassword").prop("disabled", false);
                            if(p2 !== null){
                                p2.remove();
                            }
                        
                        }else{
                            btnCambiarFoto.setAttribute('disabled', true);
                            txrPasswordChR.closest('.form-group').classList.add('has-error');
                            $("#Alerta").css("color", "red");
                            $("#Alerta").text("❌ ¡Las Contraseñas Deben Coincidir! ");
                            $("#BtnCambiarPass").prop("disabled", true);

                        }
                    }
                }
                

                <?php if($_SESSION['CLAVE_TEMPORAL'] == "-1"): ?>
                    $("#cambiarPerfil").modal("show");
                    $("#nombrePasswordCh").css("color", "red");
                    $("#nombrePasswordCh").text("!Tu contraseña ha expirado, debes cambiarla ahora!");
                    $("#btnCancelar_2").hide();
                <?php endif; ?>

                // SI SE CARGA ALGUN ARCHIVO SE INTENTA ACTUALIZAR LA IMAGEN DE PERFIL
                $("#inpFotoPerfil2").on("change", (e) => {
                    $("#btnCambiarFoto").removeAttr("disabled");
                })

                $("#btnCambiarFoto").click(function(){
                    const event = new Event('input')
                    const txrPasswordCh = document.getElementById('txrPasswordCh')
                    const txrPasswordChR = document.getElementById('txrPasswordChR')

                    txrPasswordCh.dispatchEvent(event)
                    txrPasswordChR.dispatchEvent(event)
                    
                    //Se valida si lo que se quiere es cambia la imagen o la contraseña
                    if($("#txrPasswordCh").val() != ""){
                        let passData = new FormData($("#insertPerfil")[0]);
                        let data = {
                            "txrPasswordCh": passData.get('txrPasswordCh'),
                            "txrPasswordChR": passData.get('txrPasswordChR'),
                            "hidUsuari": passData.get('hidUsuari')
                        }
                        console.log("passData", passData);
                        console.log("data", data);

                        $.ajax({
                            url: "<?= $url_crudG1 ?>?modificarPassword=true",
                            type: 'POST',
                            data:  data,
                            beforeSend: function () {
                                $.blockUI({
                                    baseZ : 2000,
                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' 
                                })
                            },
                            success : function(data){
                                let json = JSON.parse(data)
                                if(json.estado === "success"){
                                    alertify.success('<?php echo $str_message_Log_.' '.$_SESSION['CORREO'];?>');
                                    $("#cambiarPerfil").modal('hide');
                                    $("#btnCancelar_2").show();
                                    $("#ActualPassword").val('');
                                    $("#txrPasswordCh").val('');
                                    $("#txrPasswordChR").val('');
                                    swal({
                                        title: "¡Actualizado!  😏",
                                        text: "Contraseña Cambiada Exitosamente!",
                                        icon: "success",
                                        showConfirmButton: false,
                                        confirmButtonColor: '#2892DB',
                                        timer: 2000
                                    });
                                }else{
                                    console.log(" ☠ error modificarPassword");
                                    alertify.error(json.message);
                                }
                            },
                            complete: function () {
                                $.unblockUI()
                            },
                            error: function (res) {
                                console.log(res);
                                alertify.error(`Se presento un error al guardar la informacion ${res}`);
                            }
                        });
                    }

                    if($("#inpFotoPerfil2").val() != ""){
                        let imageData = new FormData($("#insertPerfil")[0]);
                        imageData.delete("txrPasswordCh");
                        imageData.delete("txrPasswordChR")
                        console.log("imageData", imageData);

                        $.ajax({
                            url: "<?= $url_crudG1 ?>?modificarImagen=true",
                            type: 'POST',
                            data: imageData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function () {
                                $.blockUI({
                                    baseZ : 2000,
                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' 
                                })
                            },
                            success : function(data){
                                let json = JSON.parse(data)
                                if(json.estado === "success"){
                                    alertify.success('<?php echo $str_mesaage_image_change;?>');
                                    $("#user-image").attr("src", json.image_url);
                                    $("#user-image-menu").attr("src", json.image_url);
                                    $("#cambiarPerfil").modal('hide');
                                    $("#ActualPassword").val('');
                                    $("#txrPasswordCh").val('');
                                    $("#txrPasswordChR").val('');
                                }else{
                                    console.log(" ☠ error modificarImagen");
                                    alertify.error(json.message);
                                }
                            },
                            complete: function () {
                                $.unblockUI()
                            },
                            error: function (res) {
                                console.log(res);
                                alertify.error(`Se presento un error al guardar la informacion ${res}`);
                            }
                        });
                    }
                    //window.location.reload();
                    
                });

                //Politicas de contraseñas
                const PasswordPolicy = (password) => {
                    let pass = 0;
                    let obligatoria = 0;
                    let RegxMayus = /[A-Z]/;
                    let RegxCaracter = /[\W]/;
                    let Regxminusculas = /[a-z]/;
                    let RegxNumeros = /[0-9]/;

                    //Evaluacion de politicas 
                    password.length >= 8 ? (obligatoria += 1) : (obligatoria += 0);
                    RegxMayus.test(password) ? (pass += 1) : (pass += 0);
                    RegxCaracter.test(password) ? (pass += 1) : (pass += 0);
                    RegxNumeros.test(password) ? (pass += 1) : (pass += 0);
                    Regxminusculas.test(password) ? (pass += 1) : (pass += 0);

                    return obligatoria == 1 && pass >= 3;
                };

                //Campo Nueva Contraseña
                $("#txrPasswordCh").keyup(function () {
                    var password = $("#txrPasswordCh").val();
                    var validacion = PasswordPolicy(password);
                    if(validacion){
                        $("#BtnCambiarPass").prop("disabled", false);
                    }else{
                        $("#error_text").css("display", "block");
                        $("#BtnCambiarPass").prop("disabled", true);
                    }
                    $("#Alerta").prop("hidden", false);

                    //Validacion de contraseña
                    if(password.length >= 8){
                        if (/[A-Z]/.test(password)) {
                            if (/[a-z]/.test(password)) {
                                if (/[0-9]/.test(password)) {
                                    if(/[\W]/.test(password)){
                                        $("#Alerta").css("color", "green");
                                        $("#Alerta").text("!Contraseña Valida!  ✔️ ");
                                        $("#txrPasswordChR").prop("disabled", false);
                                    }else{
                                        $("#Alerta").css("color", "red");
                                        $("#Alerta").text("❌ La Contraseña Debe Contener Algún Caracter Especial Como '!#$%&/?' ");
                                        $("#txrPasswordChR").prop("disabled", true);
                                        $("#BtnCambiarPass").prop("disabled", true);
                                    }
                                }else{
                                    $("#Alerta").css("color", "red");
                                    $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo Un Numero ");
                                    $("#txrPasswordChR").prop("disabled", true);
                                    $("#BtnCambiarPass").prop("disabled", true);
                                }
                            }else{
                                $("#Alerta").css("color", "red");
                                $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo Una Letra 'minuscula' ");
                                $("#txrPasswordChR").prop("disabled", true);
                                $("#BtnCambiarPass").prop("disabled", true);
                            }
                        }else{
                            $("#Alerta").css("color", "red");
                            $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo Una Letra 'MAYUSCULA' ");
                            $("#txrPasswordChR").prop("disabled", true);
                            $("#BtnCambiarPass").prop("disabled", true);
                        }
                    }else{
                        $("#Alerta").css("color", "red");
                        $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo 8 Caracteres ");
                        $("#txrPasswordChR").prop("disabled", true);
                        $("#BtnCambiarPass").prop("disabled", true);
                    }
                });

                //Campo Confirmar Contraseña
                $("#txrPasswordChR").keyup(function () {
                    validarPassword();
                });

                //Mostrar Contraeñas
                function VerContrasena(Actual){
                    if(Actual == "text"){
                        Tipo= "password"
                    }else{
                        Tipo= "text"
                    }
                    return Tipo
                }
                $("#VerPassActual").click(function () {
                    var Actual= $("#ActualPassword").attr("type");
                    var Ahora= VerContrasena(Actual);
                    $("#ActualPassword").attr("type", Ahora);
                });
                $("#VerPassNueva").click(function () {
                    var Actual= $("#txrPasswordCh").attr("type");
                    var Ahora= VerContrasena(Actual);
                    $("#txrPasswordCh").attr("type", Ahora);
                });
                $("#VerPassConfirmar").click(function () {
                    var Actual= $("#txrPasswordChR").attr("type");
                    var Ahora= VerContrasena(Actual);
                    $("#txrPasswordChR").attr("type", Ahora);
                });

                //Validar Antes De Guardar
                $("#BtnCambiarPass").click(function () {
                    let Formulario = new FormData();
                    NewPassword= $("#txrPasswordCh").val();
                    ConfirPassword= $("#txrPasswordChR").val();
                    IdUsuario= $("#IdUsuario").val();
                    IdHuesped= $("#IdHuesped").val();
                    ActualPassword= $("#ActualPassword").val();

                    if(NewPassword != ConfirPassword){
                        $("#Alerta").css("color", "red");
                        $("#Alerta").text("❌ ¡Las Contraseñas Deben Coincidir! ");
                        $("#ActualPassword").prop("disabled", true);
                    }else{
                        $("#Alerta").css("color", "green");
                        $("#Alerta").text(" ✔️ ¡Las Contraseñas Coinciden!");
                        $("#ActualPassword").prop("disabled", false);

                        //console.log("ActualPassword: ", ActualPassword);
                        if((ActualPassword == null) || (ActualPassword == "")){
                            swal({
                                icon: 'error',
                                title: '🤨 Oops...',
                                text: 'Se Debe Diligenciar El Campo "Contraseña Actual"',
                                confirmButtonColor: '#2892DB'
                            })
                            $("#divActual").css("color", "red");
                            $("#ActualPassword").prop("style", "border-color: red;");
                        }else{
                            $("#divActual").css("color", "black");
                            $("#ActualPassword").prop("style", "border-color: black;");
                            Formulario.append('IdUsuario', IdUsuario);
                            Formulario.append('IdHuesped', IdHuesped);
                            Formulario.append('ActualPassword', ActualPassword);
                            
                            $.ajax({
                                url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/Controller/ValidaPassword.php",
                                type: "POST",
                                dataType: "json",
                                cache: false,
                                processData: false,
                                contentType: false,
                                data: Formulario,
                                success: function(php_response) {
                                    Respuesta = php_response.msg;
                                    console.log(Respuesta);
                                    if (Respuesta == "Ok"){
                                        ListaPassword= php_response.Resultado[0];
                                        ClaveSQL= ListaPassword[0];
                                        ClaveDigitada= ListaPassword[1];
                                        if(ClaveDigitada == ClaveSQL){
                                            //console.log("ClaveSQL: ", ClaveSQL);
                                            //console.log("ClaveDigitada: ", ClaveDigitada);
                                            $("#btnCambiarFoto").click();
                                        }else{
                                            swal({
                                                icon: 'error',
                                                title: '¡Contraseña Incorrecta! 🤨 ',
                                                text: 'La Contraseña Digitada, No Coincide Con La Registrada...',
                                                confirmButtonColor: '#2892DB'
                                            })
                                            console.log("ClaveSQL: ", ClaveSQL);
                                            console.log("ClaveDigitada: ", ClaveDigitada);
                                        }
                                    }else if (Respuesta == "Nada"){
                                        swal({
                                            icon: 'info',
                                            title: ' 🤔 ¡Nada!',
                                            text: 'No Se Encontró Contraseña Registrada, Para Este Usuario...',
                                            confirmButtonColor: '#2892DB'
                                        })
                                    }else if (Respuesta == "Error"){
                                        swal({
                                            icon: 'error',
                                            title: '¡Error Al Validar Contraseña!  😵 ',
                                            text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                                            confirmButtonColor: '#2892DB'
                                        })
                                        console.log(php_response.Falla);
                                    }
                                },
                                error: function(php_response) {
                                    swal({
                                        icon: 'error',
                                        title: ' ☠ ¡Error Servidor!',
                                        text: 'Por Favor, Contactar Al Administrador Del Sistema...',
                                        confirmButtonColor: '#2892DB'
                                    })
                                    console.log(php_response.msg);
                                    php_response = JSON.stringify(php_response);
                                    console.log(php_response);
                                }
                            });
                        }
                    }
                    
                });

            });
        </script>

        <!-- Funciones Ajustes De Seguridad -->
        <script>
            $(document).ready(function(){
                
                //Comprobar ConfigSeguridad
                let FormSeg = new FormData();
                var IdProyecto= $("#IdProyecto").val();
                console.log("IdProyecto: ", IdProyecto);

                FormSeg.append('IdProyecto', IdProyecto);
                $.ajax({
                    url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/Controller/ConsultarConfigSeguridad.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormSeg,
                    success: function(php_response) {
                        Respuesta= php_response.msg;

                        if(Respuesta == "Ok"){
                            Resultado= php_response.Resultado;
                            //console.log("Respuesta: ", Respuesta);
                            var ResultadoConfigSeg= Resultado[0];
                            console.log("ResultadoConfigSeg: ", ResultadoConfigSeg);

                            var ActivarCaducidad= ResultadoConfigSeg[0];
                            var DiasValidez= ResultadoConfigSeg[1];
                            var DiasNotificar= ResultadoConfigSeg[2];
                            var ActivarBloqueoAutomatico= ResultadoConfigSeg[3];
                            var IntentosFallidos= ResultadoConfigSeg[4];
                            var FechaRegistro= ResultadoConfigSeg[5];
                            var IdSeguridad= ResultadoConfigSeg[6];

                            /*console.log("ActivarCaducidad: ", ActivarCaducidad);
                            console.log("DiasValidez: ", DiasValidez);
                            console.log("DiasNotificar: ", DiasNotificar);
                            console.log("ActivarBloqueoAutomatico: ", ActivarBloqueoAutomatico);
                            console.log("IntentosFallidos: ", IntentosFallidos);
                            console.log("FechaRegistro: ", FechaRegistro); */
                            
                            var CaducidadActivada= ActivarCaducidad;
                            if(CaducidadActivada == "true") {
                                $("#BtnActivarCaducidad").prop("checked", true);
                                $("#TxtNumeroDias").val(DiasValidez);
                                $("#TxtNotificar").val(DiasNotificar);
                            }else{
                                $("#TxtNumeroDias").prop("disabled", true);
                                $("#TxtNotificar").prop("disabled", true);
                            }

                            var BloqueoActivado= ActivarBloqueoAutomatico;
                            if(BloqueoActivado == "true") {
                                $("#BtnActivarBloqueo").prop("checked", true);
                                $("#TxtNumeroIntentos").val(IntentosFallidos);
                            }else{
                                $("#TxtNumeroIntentos").prop("disabled", true);
                            }

                            $("#DivGuardarAjustes").prop("hidden", true);
                            $("#DivActualizarAjustes").prop("hidden", false);
                            $("#IdSeguridad").val(IdSeguridad);

                        }else{
                            $("#TxtNumeroDias").prop("disabled", true);
                            $("#TxtNotificar").prop("disabled", true);
                            $("#TxtNumeroIntentos").prop("disabled", true);
                            $("#BtnGuardarAjustes").prop("disabled", true);
                        }

                    },
                    error: function(php_response) {
                        swal({
                            icon: 'error',
                            title: '¡Error Servidor!  😵',
                            text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                            confirmButtonColor: '#2892DB'
                        })
                        php_response = JSON.stringify(php_response);
                        console.log(php_response);
                    }

                });
                
            });

            //Activar Caducidad
            $("#BtnActivarCaducidad").click(function(){
                var ActivarCaducidad= this.checked;
                //console.log("ActivarCaducidad: ", ActivarCaducidad);
                if(ActivarCaducidad == true){
                    $("#TxtNumeroDias").prop("disabled", false);
                    $("#TxtNumeroDias").val("");
                    $("#TxtNotificar").prop("disabled", false);
                    $("#TxtNotificar").val("");
                    $("#DivCaducidad").prop("class", 'box btn-info');
                    $("#lblCaducidad").show();
                    $("#lblCaducidad_2").show();
                    $("#BtnGuardarAjustes").prop("disabled", false);
                }else{
                    $("#TxtNumeroDias").prop("disabled", true);
                    $("#TxtNumeroDias").val(90);
                    $("#TxtNotificar").prop("disabled", true);
                    $("#TxtNotificar").val(2);
                    $("#DivCaducidad").prop("class", 'box');
                    $("#lblCaducidad").hide();
                    $("#lblCaducidad_2").hide();
                    $("#BtnGuardarAjustes").prop("disabled", true);
                }
                //Comprobar Bloqueo
                var BloqueoActivado= document.getElementById('BtnActivarBloqueo').checked;
                if(BloqueoActivado == true){
                    $("#BtnGuardarAjustes").prop("disabled", false);
                }

            });

            //Activar Bloqueo
            $("#BtnActivarBloqueo").click(function(){
                var ActivarBloqueo= this.checked;
                //console.log("ActivarBloqueo: ", ActivarBloqueo);
                if(ActivarBloqueo == true){
                    $("#DivBloqueo").prop("class", 'box btn-info');
                    $("#BtnGuardarAjustes").prop("disabled", false);
                    $("#TxtNumeroIntentos").prop("disabled", false);
                }else{
                    $("#DivBloqueo").prop("class", 'box');
                    $("#BtnGuardarAjustes").prop("disabled", true);
                    $("#TxtNumeroIntentos").prop("disabled", true);
                    $("#TxtNumeroIntentos").val("");
                }

                //Comprobar Caducidad
                var CaducidadActivada= document.getElementById('BtnActivarCaducidad').checked;
                if(CaducidadActivada == true){
                    $("#BtnGuardarAjustes").prop("disabled", false);
                }

            });


            //Guardar Config Seguridad
            function GuardarConfigSeguridad(FormularioSeguridad) {
                
                $.ajax({
                    url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/Controller/GuardarConfigSeguridad.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormularioSeguridad,
                    success: function(php_response) {
                        Respuesta= php_response.msg;
                        console.log("Respuesta: ", Respuesta);
                        if (Respuesta == "Ok"){
                            swal({
                                title: "¡Registrado!  😏",
                                text: "Configuraciones De Seguridad Guardadas Exitosamente!",
                                icon: "success",
                                showConfirmButton: false,
                                confirmButtonColor: '#2892DB',
                                timer: 2000
                            });
                            console.log("Recargando...");
                            setTimeout(function(){
                                window.location.reload();
                            }, 2000);

                        }else{
                            console.log("Respuesta: ", Respuesta);
                            swal({
                                icon: 'error',
                                title: '¡Error Al Guardar Información!  🤨',
                                text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                                confirmButtonColor: '#2892DB'
                            })
                            console.log(php_response.Falla);
                        }
                    },
                    error: function(php_response) {
                        php_response = JSON.stringify(php_response);
                        swal({
                            icon: 'error',
                            title: '☠️  ¡Error Servidor!  😵',
                            text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                            confirmButtonColor: '#2892DB'
                        });
                        console.log(php_response.msg);
                    }
                });

            }

            //Capturar Ajustes De Seguridad
            $("#BtnGuardarAjustes").click(function(){
                let FormularioSeguridad = new FormData();
                var IdProyecto= $("#IdProyecto").val();
                var CheckActivarCaducidad= document.getElementById('BtnActivarCaducidad').checked;
                var CheckActivarBloqueo= document.getElementById('BtnActivarBloqueo').checked;
                console.log("CheckActivarCaducidad: ", CheckActivarCaducidad);
                console.log("CheckActivarBloqueo: ", CheckActivarBloqueo);

                //Validar Check Activados
                if((CheckActivarCaducidad == true) && (CheckActivarBloqueo == false)){
                    var ActivarCaducidad= true;
                    var NumeroDiasValidez= $("#TxtNumeroDias").val();
                    var NumeroDiasAntes= $("#TxtNotificar").val();
                    var ActivarBloqueo= false;
                    var IntentosFallidos= "0"; 
                }else if((CheckActivarCaducidad == false) && (CheckActivarBloqueo == true)){
                    var ActivarCaducidad= false;
                    var NumeroDiasValidez= 0;
                    var NumeroDiasAntes=  0;
                    var ActivarBloqueo= true;
                    var IntentosFallidos= $("#TxtNumeroIntentos").val();
                }else if((CheckActivarCaducidad == true) && (CheckActivarBloqueo == true)){
                    var ActivarCaducidad= true;
                    var NumeroDiasValidez= $("#TxtNumeroDias").val();
                    var NumeroDiasAntes= $("#TxtNotificar").val();
                    var ActivarBloqueo= true;
                    var IntentosFallidos= $("#TxtNumeroIntentos").val();
                }

                
                //Validar Campos Vacios Caducidad
                if((NumeroDiasValidez == "")||(NumeroDiasValidez == undefined)){
                    var NumeroDiasValidez= 90;
                }
                if((NumeroDiasAntes == "")||(NumeroDiasAntes == undefined)){
                    var NumeroDiasAntes= 2;
                }
                
                //Validar Campos Vacios Bloqueo
                if((IntentosFallidos == "")||(IntentosFallidos == undefined)){
                    var IntentosFallidos= 2;
                }

                /*console.log('IdProyecto: ', IdProyecto);
                console.log('ActivarCaducidad: ', ActivarCaducidad);
                console.log('NumeroDiasValidez: ', NumeroDiasValidez);
                console.log('NumeroDiasAntes: ', NumeroDiasAntes);
                console.log('ActivarBloqueo: ', ActivarBloqueo);
                console.log("IntentosFallidos: ", IntentosFallidos);*/

                FormularioSeguridad.append('IdProyecto', IdProyecto);
                FormularioSeguridad.append('ActivarCaducidad', ActivarCaducidad);
                FormularioSeguridad.append('NumeroDiasValidez', NumeroDiasValidez);
                FormularioSeguridad.append('NumeroDiasAntes', NumeroDiasAntes);
                FormularioSeguridad.append('ActivarBloqueo', ActivarBloqueo);
                FormularioSeguridad.append('IntentosFallidos', IntentosFallidos);

                GuardarConfigSeguridad(FormularioSeguridad);

            });

            //Actualizar Config Seguridad
            function ActualizarConfigSeguridad(FormularioSeguridad) {
                
                $.ajax({
                    url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/Controller/ActualizarConfigSeguridad.php",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormularioSeguridad,
                    success: function(php_response) {
                        Respuesta= php_response.msg;
                        console.log("Respuesta: ", Respuesta);
                        if (Respuesta == "Ok"){
                            swal({
                                icon: "success",
                                title: "¡Guardado!  😏",
                                text: "Configuraciones De Seguridad Modificadas Exitosamente!",
                                showConfirmButton: false,
                                confirmButtonColor: '#2892DB',
                                timer: 2000
                            });
                            console.log("Recargando...");
                            setTimeout(function(){
                                window.location.reload();
                            }, 2000);

                        }else{
                            console.log("Respuesta: ", Respuesta);
                            swal({
                                icon: 'error',
                                title: '¡Error Al Modificar Información!  🤨',
                                text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                                confirmButtonColor: '#2892DB'
                            })
                            console.log(php_response.Falla);
                        }
                    },
                    error: function(php_response) {
                        php_response = JSON.stringify(php_response);
                        swal({
                            icon: 'error',
                            title: '☠️  ¡Error Servidor!  😵',
                            text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                            confirmButtonColor: '#2892DB'
                        });
                        console.log(php_response.msg);
                    }
                });

            }

            //Actualizar Ajustes De Seguridad
            $("#BtnActualizarAjustes").click(function(){

                console.log("Actualizando... ");

                let FormularioSeguridad = new FormData();
                var IdSeguridad= $("#IdSeguridad").val();
                var IdProyecto= $("#IdProyecto").val();
                var CheckActivarCaducidad= document.getElementById('BtnActivarCaducidad').checked;
                var CheckActivarBloqueo= document.getElementById('BtnActivarBloqueo').checked;

                console.log("CheckActivarCaducidad: ", CheckActivarCaducidad);
                console.log("CheckActivarBloqueo: ", CheckActivarBloqueo);

                //Validar Check Activados
                if((CheckActivarCaducidad == true) && (CheckActivarBloqueo == false)){
                    var ActivarCaducidad= true;
                    var NumeroDiasValidez= $("#TxtNumeroDias").val();
                    var NumeroDiasAntes= $("#TxtNotificar").val();
                    var ActivarBloqueo= false;
                    var IntentosFallidos= "0"; 
                }else if((CheckActivarCaducidad == false) && (CheckActivarBloqueo == true)){
                    var ActivarCaducidad= false;
                    var NumeroDiasValidez= 0;
                    var NumeroDiasAntes=  0;
                    var ActivarBloqueo= true;
                    var IntentosFallidos= $("#TxtNumeroIntentos").val();
                }else if((CheckActivarCaducidad == true) && (CheckActivarBloqueo == true)){
                    var ActivarCaducidad= true;
                    var NumeroDiasValidez= $("#TxtNumeroDias").val();
                    var NumeroDiasAntes= $("#TxtNotificar").val();
                    var ActivarBloqueo= true;
                    var IntentosFallidos= $("#TxtNumeroIntentos").val();
                }else if((CheckActivarCaducidad == false) && (CheckActivarBloqueo == false)){
                    var ActivarCaducidad= false;
                    var NumeroDiasValidez= "0";
                    var NumeroDiasAntes=  "0";
                    var ActivarBloqueo= false;
                    var IntentosFallidos= "0";
                }

                
                //Validar Campos Vacios Caducidad
                if((NumeroDiasValidez == "")||(NumeroDiasValidez == undefined)){
                    var NumeroDiasValidez= 90;
                }
                if((NumeroDiasAntes == "")||(NumeroDiasAntes == undefined)){
                    var NumeroDiasAntes= 2;
                }
                
                //Validar Campos Vacios Bloqueo
                if((IntentosFallidos == "")||(IntentosFallidos == undefined)){
                    var IntentosFallidos= 2;
                }
                
                console.log('IdSeguridad: ', IdSeguridad);
                console.log('IdProyecto: ', IdProyecto);
                console.log('ActivarCaducidad: ', ActivarCaducidad);
                console.log('NumeroDiasValidez: ', NumeroDiasValidez);
                console.log('NumeroDiasAntes: ', NumeroDiasAntes);
                console.log('ActivarBloqueo: ', ActivarBloqueo);
                console.log("IntentosFallidos: ", IntentosFallidos);

                FormularioSeguridad.append('IdSeguridad', IdSeguridad);
                FormularioSeguridad.append('IdProyecto', IdProyecto);
                FormularioSeguridad.append('ActivarCaducidad', ActivarCaducidad);
                FormularioSeguridad.append('NumeroDiasValidez', NumeroDiasValidez);
                FormularioSeguridad.append('NumeroDiasAntes', NumeroDiasAntes);
                FormularioSeguridad.append('ActivarBloqueo', ActivarBloqueo);
                FormularioSeguridad.append('IntentosFallidos', IntentosFallidos);

                DiasValidez = parseInt(NumeroDiasValidez, 10);
                DiasNotificar = parseInt(NumeroDiasAntes, 10);
                if(DiasValidez < DiasNotificar){
                    swal({
                        icon: 'error',
                        title: '¿Te Sucede Algo?  🤨',
                        text: '¡El número de días para notificar al usuario, debe ser menor a los días de validez de la contraseña!',
                        confirmButtonColor: '#2892DB'
                    });
                }else{
                    ActualizarConfigSeguridad(FormularioSeguridad);
                }
                
            });


            //Validar Si Se Debe Notificar Caducidad o Bloquear Automaticamente
            $(document).ready(function(){

                //Notificar Caducidad
                var Notificar= <?php echo $_SESSION['NotificarCaducidad']; ?>;
                console.log("Notificar: ", Notificar);
                if(Notificar == true){
                    
                    swal({
                        title: "¡Tu Contraseña Vence Pronto!  🫣 ",
                        text: "Por Favor, Modificarla Para Evitar Bloqueos...",
                        type: "warning",
                        confirmButtonColor: "#2892DB",
                        confirmButtonText: "Cambiar Ahora",
                        showCancelButton: true,
                        closeOnConfirm: true,
                        cancelButtonText: "Mas Tarde",
                        },
                        function(){
                        $("#cambiarPerfil").modal("show");
                    });

                }

                //Bloquear Automaticamente
                var Bloquear= <?php echo $_SESSION['BloqueoAutomatico']; ?>;
                console.log("Bloquear: ", Bloquear);
                if(Bloquear == true){
                    swal({
                        title: "¡Usuario Bloqueado!   🔏",
                        text: "Por Favor Contactar Al Administrador Del Sistema…",
                        timer: 3000
                    });
                    window.location.href="login.php";
                }

            });

            
        </script>

    </body>
</html>
