<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Dyalogo CRM</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="assets/ionicons-master/css/ionicons.min.css">

		
		<!-- Theme style -->
		<link rel="stylesheet" href="assets/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="assets/css/skins/_all-skins.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="assets/plugins/iCheck/flat/blue.css">
		<!-- Morris chart -->
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
		<!-- Date Picker -->
		<link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
		<!-- Daterange picker -->
		<link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
		 <!-- Bootstrap time Picker -->
  		<link rel="stylesheet" href="assets/plugins/timepicker/bootstrap-timepicker.min.css">
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
		<link rel="stylesheet" href="assets/css/alertify.core.css">

        <link rel="stylesheet" href="assets/css/alertify.default.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css" />
        <link rel="stylesheet" href="assets/plugins/select2/select2.min.css" />
        <link rel="stylesheet" href="assets/plugins/sweetalert/sweetalert.css" />
		<script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <link rel="shortcut icon" href="assets/img/logo_dyalogo_mail.png">
		<style type="text/css">
            [class^='select2'] {
                border-radius: 0px !important;
            }

            .modal-lg {
                width: 1200px;
            }
        </style>
	</head>

	<?php 
	//SI es invocado como detalle no se muesa el Header
	if(!isset($_GET['formaDetalle']) && !isset($_GET['view'])){ 
	
		echo '<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">';
	
	}else{
		echo '<body class="hold-transition skin-blue  layout-top-nav">';

	}

    if (isset($_SESSION["QUALITY"])) {
        $QUALITY = $_SESSION["QUALITY"];
    }else{
        $QUALITY = 0;
    }

	?>
		<div class="wrapper">
			<?php 
			//SI es invocado como detalle no se muesa el Header
			if(!isset($_GET['formaDetalle']) && !isset($_GET['view'])){ 
			?>
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
							<h5 style="color:white;"><?php echo $GION_TITLE;?></h5>
						</div>
                    </form>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="<?=$_SESSION['IMAGEN']?>"  id="user-image" class="user-image" alt="User Image">
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs">
                                        <?php echo $_SESSION['NOMBRES']; ?>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header" >
                                        <img src="<?=$_SESSION['IMAGEN']?>" id="user-image-menu" class="img-circle" alt="User Image">
                                        <p>
                                            <?php 
                                                echo $_SESSION['NOMBRES']; 
   											?>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <div class="row">
                                            <div class="col-xs-6 text-center">
                                                <a href="#" data-toggle="modal" data-target="#cambiarPerfil" class="btn btn-default btn-flat">Mi Perfil</a>
                                            </div>
                                            <div class="col-xs-6 text-center">
                                                <a href="salir.php" class="btn btn-default btn-flat">Salir</a>
                                            </div>
                                        </div>
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
                        <li class="header">NAVEGACIÓN</li>
                        <li class="treeview" id="formulariosLi">
                            <a href="#">
                                <i class="fa fa-folder"></i> <span>Formularios</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php
                                    $Lsql = '';
                                    $acceso = getAccesoUser($token);
                                    $identificacion = getIdentificacionUser($token); 

                                   /* if($acceso == '-1'){
                                        if(isset($_SESSION['PROYECTO_CRM'])){
                                            $Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__PROYEC_b =".$_SESSION['PROYECTO_CRM'];
                                        }
                                        
                                    }else{
                                        
*/
                                    $Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ JOIN ".$BaseDatos_systema.".PEOBUS ON PEOBUS_ConsInte__GUION__b = GUION__ConsInte__b WHERE PEOBUS_ConsInte__USUARI_b = ".$identificacion;
                                    //}
                          
                                    if($identificacion != 0){
                                        $result = $mysqli->query($Lsql);

                                        while($key = $result->fetch_object()){
                                            $titulo = $key->GUION__Nombre____b;
                                            $titulo = str_replace(' ', '_', $titulo);
                                            $titulo = substr($titulo, 0 , 18);

                                            echo '<li id="'.$key->GUION__ConsInte__b.'"><a href="index.php?formulario='.$key->GUION__ConsInte__b.'&quality='.$QUALITY.'"><i class="fa fa-circle"></i>'.$titulo.'</a></li>';
                                        } 

                                    }
                                ?>
                            </ul>
                        </li>
                        <li class="treeview" id="formulariosLi">
                            <a href="#">
                                <i class="fa fa-calendar-check-o"></i> <span>Tareas</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
					
                                <?php
                                    $Lsql = '';
                                    $identificacion = getIdentificacionUser($token); 

                                    if($QUALITY == 0){
                                        $Lsql = "SELECT ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b FROM ".$BaseDatos_systema.".ASITAR_BACKOFFICE WHERE ASITAR_BACKOFFICE_ConsInte__USUARI_b =" .$identificacion;
                                    }else{
                                        $Lsql = "SELECT ASITAR_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ASITAR WHERE ASITAR_ConsInte__USUARI_b = ".$identificacion;
                                    }                                    
                                    
                                    if($identificacion != 0){
                                        $result = $mysqli->query($Lsql);

                                        while($key = $result->fetch_object()){

                                            if($QUALITY == 0){
                                                $Lsql = "SELECT TAREAS_BACKOFFICE_ConsInte__b, TAREAS_BACKOFFICE_ConsInte__GUION_b, TAREAS_BACKOFFICE_Nombre_b FROM ".$BaseDatos_systema.".TAREAS_BACKOFFICE JOIN ".$BaseDatos_systema.".GUION_ ON GUION__ConsInte__b = TAREAS_BACKOFFICE_ConsInte__GUION_b WHERE TAREAS_BACKOFFICE_ConsInte__b = ".$key->ASITAR_BACKOFFICE_ConsInte__TAREAS_BACKOFFICE_b;
                                                $nombreTemp = "TAREAS_BACKOFFICE_Nombre_b";
                                            }else{
                                                $Lsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b, GUION__Nombre____b FROM ".$BaseDatos_systema.".CAMPAN JOIN ".$BaseDatos_systema.".GUION_ ON GUION__ConsInte__b = CAMPAN_ConsInte__GUION__Gui_b WHERE CAMPAN_ConsInte__b = ".$key->ASITAR_ConsInte__CAMPAN_b;
                                                $nombreTemp = "GUION__Nombre____b";
                                            }                                            

                                            $resultX = $mysqli->query($Lsql);

                                            while($keyX = $resultX->fetch_object()){

                                                $titulo = $keyX->$nombreTemp;
                                                $titulo = str_replace(' ', '_', $titulo);
                                                $titulo = substr($titulo, 0 , 18);

                                                if($QUALITY == 0){
                                                    echo '<li id="'.$keyX->TAREAS_BACKOFFICE_ConsInte__GUION_b.'"><a href="index.php?formulario='.$keyX->TAREAS_BACKOFFICE_ConsInte__GUION_b.'&quality='.$QUALITY.'&tareabackoffice='.$keyX->TAREAS_BACKOFFICE_ConsInte__b.'"><i class="fa fa-circle"></i>'.$titulo.'</a></li>';
                                                }else{
                                                    echo '<li id="'.$keyX->CAMPAN_ConsInte__GUION__Gui_b.'"><a href="index.php?formulario='.$keyX->CAMPAN_ConsInte__GUION__Gui_b.'&campana_crm='.$key->ASITAR_ConsInte__CAMPAN_b.'&quality='.$QUALITY.'"><i class="fa fa-circle"></i>'.$titulo.'</a></li>';
                                                }                                               

                                            }
                                        } 

                                    }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
		  	<?php } ?>
		  	<div class="content-wrapper">
                <!-- Main content -->
                <section class="content">


		  	<script type="text/javascript">
		  		$(function(){
                    <?php if(isset($_GET['formulario']) && !isset($_GET['campana_crm']) && !isset($_GET['tareabackoffice'])) { ?>
                        $("#formulariosLi").addClass('active');
                        $("#<?php echo $_GET['formulario'];?>").addClass('active');
                    <?php }?>
		  		});
			</script>	  
