<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    if(!isset($_SESSION['LOGIN_OK_MANAGER'])){
        header('Location:login.php');
    }
    include ('idioma.php');
    include ('pages/conexion.php');
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
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css"/>
        <!-- Ionicons -->
        <link rel="stylesheet" href="assets/ionicons-master/css/ionicons.min.css"/>
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/AdminLTE.min.css"/>
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
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


        <script type="text/javascript">
            function autofitIframe(id){
                if (!window.opera && document.all && document.getElementById){
                    id.style.height=id.contentWindow.document.body.scrollHeight;
                } else if(document.getElementById) {
                    id.style.height=id.contentDocument.body.scrollHeight+"px";
                }
            }
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">

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
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Enviar Reportes</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12">
                        <p >Para ingresar mas de un correo se deben <strong>SEPARAR</strong>  por una coma ( , ).</p>
                        <input type="text" class="form-control" id="cajaCorreos" name="cajaCorreos" placeholder="(Ejemplo1@ejem.com,Ejemplo2@ejem.com)">
 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>ENVIAR REPORTES A MI CORREO</label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="siEnviarme" id="siEnviarme">
                    </div>
                    <div class="col-md-8 text-right">
                        <img hidden id="loading" src="assets/plugins/loading.gif" width="30" height="30">&nbsp;&nbsp;&nbsp;
                        <button id="sendEmails" readonly class="btn btn-primary" >Enviar Reportes</button>
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
                                                    echo "<option selected value='".$key->id_huesped."'>".strtoupper($key->nombre)."</option>";
                                                }else{
                                                   echo "<option value='".$key->id_huesped."'>".strtoupper($key->nombre)."</option>";
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="insertPerfil" enctype="multipart/form-data" method="post"  >
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo $str_usuarios;?></h4>
                               
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="box">
                                            <div class="box-header">
                                                <h3 class="box-title"><?php echo $_SESSION['NOMBRES'];?></h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="inputName"><?php echo $str_passwo_usuario;?></label>
                                                    <input type="password" class="form-control" id="txrPasswordCh" name="txrPasswordCh" placeholder="<?php echo $str_passwo_usuario;?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputName"><?php echo $str_passRe_usuario;?></label>
                                                    <input type="password" class="form-control" id="txrPasswordChR" name="txrPasswordChR" placeholder="<?php echo $str_passRe_usuario;?>">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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

                                                <input type="file" name="inpFotoPerfil" id="inpFotoPerfil2" class="form-control">
                                                <input type="hidden" name="hidUsuari" id="hidUsuari" value="<?php echo $_SESSION['IDENTIFICACION'];?>">
                                                <input type="hidden" name="ruta" value="<?php if(isset($_GET['page'])){ echo $_GET['page'];}else{ echo "dashboard"; }?>">
                                            </div>
                                        <!-- /.box-body -->
                                        </div>
                                        <!-- /.box -->
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="btnCambiarFoto" class="btn btn-primary"><?php echo $str_guardar;?></button>
                                <button class="btn btn-danger" id="btnCancelar_2" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        <div class="wrapper">
            <header class="main-header" >
                <!-- Logo -->
                <a href="index.php" class="logo" >
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><img src="assets/img/Logo_blanco.png" style="width: 100%;" ></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><img src="assets/img/Logo_blanco.png" style="width: 50%;"></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" >
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <form class="navbar-form navbar-left" role="search" style="color:white;">
                        <div class="form-group">
                            <h5 style="color:white;"><b><?php if(isset($_SESSION['PROYECTO'])){ echo $_SESSION['PROYECTO']; } else {  } ?></b></h5>
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
                                    <img src="<?php echo $_SESSION['IMAGEN'];?>" class="user-image" alt="User Image">
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs">
                                        <?php echo $_SESSION['NOMBRES']; ?>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header" >
                                        <img src="<?php echo $_SESSION['IMAGEN'];?>" class="img-circle" alt="User Image">
                                        <p>
                                            <?php 
                                                echo $_SESSION['NOMBRES']; 
                                            ?>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <?php 
                                            if(isset($_SESSION['UNO'])){
                                                if($_SESSION['UNO']){
                                        ?>
                                        <div class="row">
                                            <div class="col-xs-6 text-center">
                                                <a href="#" data-toggle="modal" data-target="#cambiarPerfil" class="btn btn-default btn-flat"><?php echo $str_cambiarpassword;?></a>
                                            </div>
                                            <div class="col-xs-6 text-center">
                                                <a href="salir.php" class="btn btn-default btn-flat"><?php echo $str_salir;?></a>
                                            </div>
                                        </div>
                                        <!-- /.row -->

                                        <?php
                                                }else{
                                        ?>

                                        <div class="row">
                                            <div class="col-xs-4 text-center" hidden>
                                                <a href="#" data-toggle="modal" data-target="#cambiarPerfil" class="btn btn-default btn-flat"><?php echo $str_cambiarpassword;?></a>
                                            </div>
                                            <div class="col-xs-6 text-center">
                                                <a href="#" data-toggle="modal" data-target="#CambiarHuesped" class="btn btn-default btn-flat"><?php echo $str_cambiarHuesped;?></a>
                                            </div>
                                            <div class="col-xs-6 text-center">
                                                <a href="salir.php" class="btn btn-default btn-flat">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $str_salir;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
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
                                <a href="index.php?page=usuariosG1">
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
                                    <a href="index.php?page=guion&tipo=2">
                                        <i class="fa fa-database"></i> <span><?php echo $str_guion_;?></span>
                                    </a>
                                </li>
                                <li id="guiones_tipo_1">
                                    <a href="index.php?page=guion&tipo=1">
                                        <i class="glyphicon glyphicon-list-alt"></i> <span><?php echo $str_script_;?></span>
                                    </a>
                                </li>
                                <li id="guiones_tipo_3">
                                    <a href="index.php?page=guion&tipo=3">
                                        <i class="fa fa-table"></i> <span><?php echo $str_script_otre;?></span>
                                    </a>
                                </li>
                                <li id="guiones_tipo_4">
                                    <a href="index.php?page=guion&tipo=4">
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
                            <a href="index.php?page=estrategias">
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
                                    <a href="index.php?page=detallado">
                                        <i class="fa fa-bars"></i> <span><?php echo $str_detallado;?></span>
                                    </a>
                                </li>
                                <li id="grafico">
                                    <a href="index.php?page=grafico">
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
                            <a href="index.php?page=dashboard">
                                <i class="fa fa-dashboard"></i> <span><?php echo $str_dashboard;?></span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="index.php?page=detallado">
                                <i class="fa fa-table"></i> <span><?php echo $str_detallado;?></span>
                            </a>
                        </li>

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


            <?php
                //Contenido
                if(isset($_GET['page'])){
                    switch ($_GET['page']) {
                        case 'dashboard':
                            include('pages/Dashboard/dashboard.php');
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

                        case 'usuarios':
                            include('pages/Usuarios/usuarios.php');
                            break;

                        case 'dashEstrat':
                            include('pages/Estrategias/dashboardEstrategias.php');
                            break;

                        case 'usuariosG1':
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
                        
                        default:
                            # code...
                            break;
                    }
                }else{
                    include('pages/Dashboard/dashboard.php');
                }
            ?>
        </div>


        
        <!-- jQuery 2.2.3 -->
        
        <!-- jQuery UI 1.11.4 -->
        <script src="assets/jqueryUI/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.6 -->
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>


        <script src="assets/plugins/select2/select2.full.min.js"></script>
        <!-- Sparkline -->
        <script src="assets/plugins/sparkline/jquery.sparkline.min.js"></script>
        <!-- Slimscroll -->
        <script src="assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>

        <script src="assets/timepicker/jquery.timepicker.js"></script>

        <!-- Date Picker -->
        <script rel="stylesheet" src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>

        <!-- AdminLTE App -->

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

        <!--fin pivot Table-->  




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
                            $opxciones = "<option value='0'>SELECCIONE</option>";
                            while ($key = $res->fetch_object()) {
                                $opxciones .= "<option value='".$key->id_huesped."'>".strtoupper($key->nombre)."</option>";
                            }
                            echo " $(\"#cmbHuesped\").html(\"".$opxciones."\");";
                            echo " $(\"#cmbHuesped\").select2();";
                            echo " $(\"#PonerHuesped\").modal();";
                            $_SESSION['UNO'] = false;
                        }else{
                            while ($key = $res->fetch_object()) {
                                $_SESSION['PROYECTO'] = strtoupper($key->nombre);
                                $_SESSION['HUESPED']  = strtoupper($key->id_huesped);
                                $_SESSION['UNO'] = true;
                                echo 'window.location.href = "index.php?page=dashboard";';

                            }
                            
                        }
                    }
                ?>
                $("#btnGuardar").click(function(){
                    if($("#cmbHuesped").val() != 0){
                        $.ajax({
                            url     : "cambiar_huesped.php",
                            type    : "post",
                            data    : { huesped : $("#cmbHuesped").val() }, 
                            success : function(data){
                                if(data == "1"){
                                    window.location.href = "index.php?page=dashboard";
                                }
                            }
                        });
                    }else{
                        alertify.error("<?php echo $str_huesped; ?>");
                    }
                    
                });

                $("#btnGuardar2").click(function(){
                    $.ajax({
                        url     : "cambiar_huesped.php",
                        type    : "post",
                        data    : { huesped : $("#cmbHuesped2").val() }, 
                        success : function(data){
                            if(data == "1"){
                                window.location.href = "index.php?page=dashboard";
                            }
                        }
                    });
                });

                $("#btnCancelar").click(function(){
                    alertify.error("<?php echo $str_huesped; ?>");
                });

                $("#btnCambiarFoto").click(function(){
                    
                    valido = 0;

                    if($("#txrPasswordCh").val() != '' && $("#txrPasswordCh").val().length > 1){
                        if($("#txrPasswordCh").val().length < 7){
                            alertify.error('<?php echo $str_pass_message_e1_; ?>');
                            valido = 1;
                        }else{
                            if($("#txrPasswordCh").val() != $("#txrPasswordChR").val()){
                                alertify.error('<?php echo $str_pass_message_e2_; ?>');
                                valido = 1 ;
                            }
                        }
                    }

                    if(valido == 0){
                        var form = $("#insertPerfil");
                        //Se crean un array con los datos a enviar, apartir del formulario 
                        var formData = new FormData($("#insertPerfil")[0]);
                        $.ajax({
                            url     : "cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php?modificarImagen=true",
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success : function(data){
                                $("#txrPasswordCh").val('');
                                $("#txrPasswordChR").val('');
                                if(data == "1"){
                                    if($("#txrPasswordCh").val() != '' && $("#txrPasswordCh").val().length > 1){
                                        alertify.success('<?php echo $str_message_Log_.' '.$_SESSION['CORREO'];?>');
                                        $("#cambiarPerfil").modal('hide');
                                    }else{
                                        alertify.success('<?php echo $str_mesaage_password;?>');
                                        window.location.reload(true);
                                    }
                                    
                                }else{
                                    alertify.error('Error');
                                }
                            }
                        });
                    }
                });
            }); 
        </script>
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
                            title : "Error al subir el archivo",
                            text  : "El archivo debe estar en formato JPG",
                            type  : "error",
                            confirmButtonText : "Cerrar"
                        });
                    }else if(imagen['size'] > 2000000 ) {
                        $('#inpFotoPerfil2').val('');
                        swal({
                            title : "Error al subir el archivo",
                            text  : "El archivo no debe pesar mas de 2MB",
                            type  : "error",
                            confirmButtonText : "Cerrar"
                        });
                    }else{
                        if(imagen['type'] == 'image/jpeg' ){
                            var datosImagen = new FileReader();
                            datosImagen.readAsDataURL(imagen);

                            $(datosImagen).on("load", function(event){
                                var rutaimagen = event.target.result;
                                $('#avatar_Imagen').attr("src",rutaimagen);                               
                            }); 
                        }
                        
                    }   
                }); 
            });

             $('#impPDF').click(function(){
                $('#frameVisor').get(0).contentWindow.focus(); 
                $("#frameVisor").get(0).contentWindow.print(); 
             });

        </script>
    </body>
</html>


