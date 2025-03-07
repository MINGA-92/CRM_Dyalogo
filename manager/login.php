
<?php

    if(isset($_SESSION['LOGIN_OK_MANAGER'])){
        header('Location:index.php');
    }
    include ('idioma.php');
    include ('global/variables.php');
    include ('sessions.php');
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $str_TituloSistema;?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="assets/ionicons-master/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/AdminLTE.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">

        <link rel="stylesheet" href="assets/css/alertify.core.css">

        <link rel="stylesheet" href="assets/css/alertify.default.css">

        <link rel="shortcut icon" href="assets/img/logo_dyalogo_mail.png">

        <link rel="stylesheet" href="assets/plugins/sweetalert/sweetalert.css"/>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    
    <body class="hold-transition login-page">
        <div class="login-box" id="Login">
            <div class="login-logo">
                <a href="#"><img src="assets/img/Logo_blanco.png"></a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body redonder" style="position: relative;">
                <p class="login-box-msg"><?php echo $str_titulo_Login;?></p>
                <form action="sessions.php" method="post">
                    <div class="form-group has-feedback">
                        <input type="email" name="txtUsuario" required class="form-control" placeholder="<?php echo $str_TxtUsuario;?>">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="txtPassword" required class="form-control" placeholder="<?php echo $str_TxtPassword;?>">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox"> <?php echo $str_recuerdama;?>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo $str_Ingreso;?></button>
                        </div>
                      <!-- /.col -->
                    </div>
                </form>
                <a href="#" id="recordarPass"><?php echo $str_recordar_pas;?></a><br>
                <!-- /.social-auth-links
                
                <a href="register.html" class="text-center">Register a new membership</a> -->
                <span style="position: absolute;right: 0;margin-right: 5px;font-size: 10px;font-weight: bold;"><?php echo $str_Version_Manager; ?></span>
            </div>
          <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <div class="login-box" style="display: none;" id="RescueLogin">
            <div class="login-logo">
                <a href="#"><img src="assets/img/logo_dyalogo_mail.png"></a>
            </div>
          <!-- /.login-logo -->
            <div class="login-box-body redonder">
                <p class="login-box-msg"><?php echo $str_titulo_Login;?></p>
                <form action="sessions.php" method="post">
                    <div class="form-group has-feedback">
                        <input type="email" name="txtMail" id="txtMail" required class="form-control" placeholder="<?php echo $str_TxtUsuario;?>">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                
                    <div class="row">
                        <div class="col-xs-6">
                        
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                            <button type="button" id="btnEnviarPass" class="btn btn-primary btn-block btn-flat"><?php echo $str_recuper_pass;?></button>
                        </div>
                      <!-- /.col -->
                    </div>
                </form>
                <a href="#" id="loginVolver"><?php echo $str_backLohin_p_;?></a><br>
                <!-- /.social-auth-links
                
                <a href="register.html" class="text-center">Register a new membership</a> -->
            </div>
          <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 2.2.3 -->
        <script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="assets/plugins/iCheck/icheck.min.js"></script>
        <script src="assets/js/alertify.js"></script>
        <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>

        <script>
            $(function () {
                <?php if(isset($_SESSION['register']) && $_SESSION['register']=='denegado') { ?>
                    alertify.error("Acceso Denegado");
                <?php
                    unset ($_SESSION['register']);
                    }
                ?>
                
                <?php if(isset($_SESSION['register']) && $_SESSION['register']=='fail') { ?>
                    alertify.error("¡Datos Erroneos!  🤨");
                    alertify.warning("Quedan Solo <span style='color:#F9280B'><?php echo $_SESSION['IntentosRestantes']; ?></span> Intentos, Antes De Que La Cuenta Sea Bloqueada");

                    <?php if(isset($_SESSION['IntentosRestantes']) && $_SESSION['IntentosRestantes'] != "false") { ?>
                        swal({
                            title: "¡Contraseña Incorrecta!  🤨",
                            text: "Quedan Solo <span style='color:#FD920D'><?php echo $_SESSION['IntentosRestantes']; ?></span> Intentos, Antes De Que La Cuenta Sea Bloqueada",
                            html: true
                        });
                    <?php } ?>

                    <?php if(isset($_SESSION['BloqueoAutomatico']) && $_SESSION['BloqueoAutomatico'] != "false") { ?>
                        alertify.error("¡Usuario Bloqueado!   🔏");
                        swal({
                            title: "¡Usuario Bloqueado!   🔏",
                            text: "Por Favor Contactar Al Administrador Del Sistema…",
                            timer: 5000
                        });
                    <?php } ?>

                <?php 
                    unset ($_SESSION['register']);
                    }
                ?>
                
                <?php if(isset($_SESSION['register']) && $_SESSION['register']=='noexiste') { ?>
                    alertify.error("El Usuario No Existe");
                <?php 
                    unset ($_SESSION['register']);
                    }
                ?>

                <?php if(isset($_SESSION['register']) && $_SESSION['register']=='inactivo') { ?>
                    alertify.error("El Usuario Se Encuentra Inactivo");
                <?php 
                    unset ($_SESSION['register']);
                    }
                ?>                
                

               
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });

                $("#recordarPass").click(function(e){
                    e.preventDefault();
                    $("#Login").hide();
                    $("#RescueLogin").fadeIn();
                });

                $("#loginVolver").click(function(e){
                    e.preventDefault();
                    $("#Login").fadeIn();
                    $("#RescueLogin").hide();
                })

                $("#btnEnviarPass").click(function(){
                    var valido = 0;
                    if($("#txtMail").val().length < 1){
                        alertify.error('<?php echo $str_mail_message_E__;?>');
                        $("#txtMail").focus();
                        valido = 1;
                    }else{
                        if(!$("#txtMail").val().trim().match(/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/) ){
                    
                            if(!$("#txtMail").val().trim().match(/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}[.][a-zA-Z]{2,4}$/) ){
                                alertify.error('<?php echo $str_mail_message_E2_;?>');
                                $("#txtMail").focus();
                                valido = 1;
                            }
                        }
                    }

                    if(valido == 0){
                        $.ajax({
                            url   : "cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php?recuperarPassWord=true",
                            type  : 'post',
                            dataType: 'json',
                            data  : { correo : $("#txtMail").val() },
                            success : function(data){
                                if(data.estado == 'ok'){
                                    alertify.success(data.mensaje);
                                }else{
                                    alertify.error(data.mensaje);
                                }
                            }

                        });
                    }
                });
            });

        </script>
        
    </body>
</html>
