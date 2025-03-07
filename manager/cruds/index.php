<?php
	session_start();
	if(!isset($_SESSION['LOGIN_OK'])){
		header('Location:login.php');
	}
	include ('idioma.php');
	include ('pages/conexion.php');


?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $str_TituloSistema;?></title>
	<meta http-equiv='cache-control' content='no-cache'>
	<meta http-equiv='expires' content='0'>
	<meta http-equiv='pragma' content='no-cache'>
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

        <style type="text/css">
            [class^='select2'] {
                border-radius: 0px !important;
            }

            .modal-lg {
                width: 90%;
            }
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
                                         
                                        <div class="row">
                                            <div class="col-xs-6 text-center">
                                                <a href="#" data-toggle="modal" data-target="#cambiarPerfil" class="btn btn-default btn-flat"><?php echo $str_cambiarpassword;?></a>
                                            </div>
                                            <div class="col-xs-6 text-center">
                                                <a href="salir.php" class="btn btn-default btn-flat"><?php echo $str_salir;?></a>
                                            </div>
                                        </div>
                                        <!-- /.row -->
                                        
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
                                <input type="text" name="q" id="q" class="form-control" placeholder="<?php echo $str_busqueda;?>">
                                <span class="input-group-btn">
                                    <button type="button" name="search" id="search-btn" class="btn btn-flat">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header"><?php echo $str_navegacion;?></li>
                        <li id="dashboard">
                            <a href="index.php">
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

                        <li id="campan">
                            <a href="index.php?page=campan">
                                <i class="fa fa-bell"></i> <span><?php echo $str_campan;?></span>
                            </a>
                        </li>

                        <li id="web_form">
                            <a href="index.php?page=web_form">
                                <i class="fa fa-list-alt"></i> <span><?php echo $str_web_form;?></span>
                            </a>
                        </li>
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
                            
                        
						default:
							# code...
							break;
					}
				}else{
					include('pages/Dashboard/dashboard.php');
				}
			?>
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
                                <select name="cmbHuesped" id="cmbHuesped" class="form-control">  
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            
                            <button class="btn btn-success" id="btnGuardar" type="button"><?php echo $str_config_aplicar;?></button>
                            <button class="btn btn-danger" id="btnCancelar" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade-in" id="cambiarPerfil" data-backdrop="static"  data-keyboard="false" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="insertPerfil" enctype="multipart/form-data" method="post" action="pages/Usuarios/guardarUsuarios.php" >
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo $str_usuarios;?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title"><?php echo $str_datos__usuario;?></h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="inputName"><?php echo $str_nombre_usuario;?></label>
                                                <input type="text" class="form-control" id="txtNombre" readonly="true" name="txtNombre" placeholder="<?php echo $str_nombre_usuario;?>" value="<?php echo $_SESSION['NOMBRES'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail"><?php echo $str_passwo_usuario;?></label>
                                                <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="<?php echo $str_passwo_usuario;?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail"><?php echo $str_passRe_usuario;?></label>
                                                <input type="password" class="form-control" id="txtRepeatPassword" name="txtRepeatPassword" placeholder="<?php echo $str_passRe_usuario;?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Profile Image -->
                                    <div class="box box-default">
                                        <div class="box-body box-profile">
                                            <img id="avatar3" class="profile-user-img img-responsive img-circle" src="<?php echo $_SESSION['IMAGEN'];?>" alt="User profile picture">

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

                                            <input type="file" name="inpFotoPerfil" id="inpFotoPerfil" class="form-control">
                                            <input type="hidden" name="hidOculto" id="hidOculto" value="0">
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
                            <button type="submit" class="btn btn-primary"><?php echo $str_guardar;?></button>
                            <button class="btn btn-danger" id="btnCancelar_2" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                            
                        </div>
                    </form>
                </div>
            </div>
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


        <script type="text/javascript">
            $(function(){
                <?php
                    if(!isset($_SESSION['PROYECTO'])){
                        $Lsql = "SELECT * FROM ".$BaseDatos_general.".huespedes_usuarios JOIN ".$BaseDatos_general.".huespedes ON huespedes.id = huespedes_usuarios.id_huesped WHERE id_usuario = ".$_SESSION['USUARICBX'];    
                        $res  = $mysqli->query($Lsql);
                        if($res->num_rows > 1){
                            $opxciones = "";
                            while ($key = $res->fetch_object()) {
                                $opxciones .= "<option value='".$key->id_huesped."'>".strtoupper($key->nombre)."</option>";
                            }
                            echo " $(\"#cmbHuesped\").html(\"".$opxciones."\");";
                            echo " $(\"#PonerHuesped\").modal();";
                        }else{
                            while ($key = $res->fetch_object()) {
                                $_SESSION['PROYECTO'] = strtoupper($key->nombre);
                                $_SESSION['HUESPED']  = strtoupper($key->id_huesped);
                            }
                            
                        }
                    }
                ?>
                $("#btnGuardar").click(function(){
                    $.ajax({
                        url     : "cambiar_huesped.php",
                        type    : "post",
                        data    : { huesped : $("#cmbHuesped").val() }, 
                        success : function(data){
                            if(data == "1"){
                                window.location.href = "index.php";
                            }
                        }
                    });
                });

                $("#btnCancelar").click(function(){
                    swal({
                        title: "Error!",
                        html : true,
                        text: 'Es necesario elegir el huesped',
                        type: "error",
                        confirmButtonText: "Ok"
                    }, function(){
                        $("#PonerHuesped").modal();
                    });
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

                $('#inpFotoPerfil').on('change', function(e){
                    addImage4(e); 
                    $("#hidOculto").val(1);
                });   
            });

            function addImage4(e){
                var file = e.target.files[0],
                imageType = /image.*/;

                if (!file.type.match(imageType))
                    return;

                var reader = new FileReader();
                reader.onload = fileOnload4;
                reader.readAsDataURL(file);
            }

            function fileOnload4(e) {
                var result= e.target.result;
                $('#avatar3').attr("src",result);
            }
        </script>
    </body>
</html>


