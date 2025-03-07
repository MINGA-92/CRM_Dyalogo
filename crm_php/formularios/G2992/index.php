<?php 
    /*
        Document   : index
        Created on : 2023-12-06 15:20:08
        Author     : Nicolas y su poderoso generador, La gloria sea Para DIOS 
        Url 	   : id = Mjk5Mg==  
    */
    $url_crud =  "formularios/G2992/G2992_CRUD_web.php";
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../../conexion.php");
    date_default_timezone_set('America/Bogota');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Formulario de contacto</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- Date Picker -->
        <link rel="stylesheet" href="assets/plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="assets/timepicker/jquery.timepicker.css"/>
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">

        <link rel="stylesheet" type="text/css" href="assets/plugins/sweetalert/sweetalert.css">

        <link rel="stylesheet" href="assets/plugins/select2/select2.min.css" />
    
        <link href='//fonts.googleapis.com/css?family=Sansita+One' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <link rel="shortcut icon" href="assets/img/logo_dyalogo_mail.png"/>
        <style>
            
            .hed{
           
                font-family: 'Sansita One', cursive; 
                color:white;
            }

            .hed2{
                text-align:center;
                font-family: 'Sansita One', cursive; 
                font-size:25px; 
                color:#019CDE;
                margin-top: -9px;
            }
            .font_2 {
                font: normal normal normal 17px/1.4em Spinnaker,sans-serif;
                text-align:center;
            }
            
            .redonder {
                -webkit-border-radius: 20px;
                -moz-border-radius: 20px;
                border-radius: 20px;
                -webkit-box-shadow: 7px 7px 17px -9px rgba(0,0,0,0.75);
                -moz-box-shadow: 7px 7px 17px -9px rgba(0,0,0,0.75);
                box-shadow: 7px 7px 17px -9px rgba(0,0,0,0.75);
            }

            [class^='select2'] {
                border-radius: 0px !important;
            }
        </style>
    </head>
    <?php  
        echo '<body class="hold-transition" >';
    ?>
    
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6" >
                <div class="login-box">
                    <div class="login-logo hed">
                        <img src="assets/img/logo_dyalogo_mail.png"  alt="Dyalogo">
                    </div><!-- /.login-logo -->
                    <div class="login-box-body">
                        <p class="login-box-msg font_2" >FORMULARIO DE CONTACTO</p>
                        <form action="formularios/G2992/G2992_CRUD_web.php" method="post" id="formLogin">
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59910" id="LblG2992_C59910">NOMBRE Y APELLIDOS  SOLICITANTE</label>
								<input type="text" class="form-control input-sm" id="G2992_C59910" value=""  name="G2992_C59910"  placeholder="NOMBRE Y APELLIDOS  SOLICITANTE">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59911" id="LblG2992_C59911">CEDULA  SOLICITANTE</label>
								<input type="text" class="form-control input-sm" id="G2992_C59911" value=""  name="G2992_C59911"  placeholder="CEDULA  SOLICITANTE">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59913" id="LblG2992_C59913">TELEFONO 1  SOLICITANTE</label>
								<input type="text" class="form-control input-sm" id="G2992_C59913" value=""  name="G2992_C59913"  placeholder="TELEFONO 1  SOLICITANTE">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59914" id="LblG2992_C59914">TELEFONO 2  SOLICITANTE</label>
								<input type="text" class="form-control input-sm" id="G2992_C59914" value=""  name="G2992_C59914"  placeholder="TELEFONO 2  SOLICITANTE">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59915" id="LblG2992_C59915">CODIGO Y/O REFERENCIA  SOLICITANTE</label>
								<input type="text" class="form-control input-sm" id="G2992_C59915" value=""  name="G2992_C59915"  placeholder="CODIGO Y/O REFERENCIA  SOLICITANTE">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C65749" id="LblG2992_C65749">ASESOR</label>
								<input type="text" class="form-control input-sm" id="G2992_C65749" value="" disabled name="G2992_C65749"  placeholder="ASESOR">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
  
                            <!-- CAMPO TIPO FECHA -->
                            <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C66500" id="LblG2992_C66500">FECHA CREACION GESTION</label>
                                <input type="text" class="form-control input-sm Fecha" value="" disabled name="G2992_C66500" id="G2992_C66500" placeholder="YYYY-MM-DD">
                            </div>
                            <!-- FIN DEL CAMPO TIPO FECHA-->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59916" id="LblG2992_C59916">NOMBRE Y APELLIDOS SUSCRIPTOR</label>
								<input type="text" class="form-control input-sm" id="G2992_C59916" value="" disabled name="G2992_C59916"  placeholder="NOMBRE Y APELLIDOS SUSCRIPTOR">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59917" id="LblG2992_C59917">CEDULA SUSCRIPTOR</label>
								<input type="text" class="form-control input-sm" id="G2992_C59917" value="" disabled name="G2992_C59917"  placeholder="CEDULA SUSCRIPTOR">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59919" id="LblG2992_C59919">TELEFONO 1 SUSCRIPTOR</label>
								<input type="text" class="form-control input-sm" id="G2992_C59919" value="" disabled name="G2992_C59919"  placeholder="TELEFONO 1 SUSCRIPTOR">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59920" id="LblG2992_C59920">TELEFONO 2 SUSCRIPTOR</label>
								<input type="text" class="form-control input-sm" id="G2992_C59920" value="" disabled name="G2992_C59920"  placeholder="TELEFONO 2 SUSCRIPTOR">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59921" id="LblG2992_C59921">CÓDIGO SUSCRIPTOR</label>
								<input type="text" class="form-control input-sm" id="G2992_C59921" value="" disabled name="G2992_C59921"  placeholder="CÓDIGO SUSCRIPTOR">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59922" id="LblG2992_C59922">RELACION CON EL PREDIO</label>
								<input type="text" class="form-control input-sm" id="G2992_C59922" value="" disabled name="G2992_C59922"  placeholder="RELACION CON EL PREDIO">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59923" id="LblG2992_C59923">ESTADO SUSCRIPCION</label>
								<input type="text" class="form-control input-sm" id="G2992_C59923" value="" disabled name="G2992_C59923"  placeholder="ESTADO SUSCRIPCION">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C59924" id="LblG2992_C59924">MORISIDAD</label>
								<input type="text" class="form-control input-sm" id="G2992_C59924" value="" disabled name="G2992_C59924"  placeholder="MORISIDAD">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C65254" id="LblG2992_C65254">REFERENCIA SUSCRIPTOR</label>
								<input type="text" class="form-control input-sm" id="G2992_C65254" value="" disabled name="G2992_C65254"  placeholder="REFERENCIA SUSCRIPTOR">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->

                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59951" id="LblG2992_C59951">La solicitud proviene de incumplimiento o errores por parte de la empresa</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G2992_C59951" id="G2992_C59951">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3662 ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->

                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59952" id="LblG2992_C59952">SECCION</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G2992_C59952" id="G2992_C59952">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3663 ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->

                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59953" id="LblG2992_C59953">TIPO DE DOLOR O SOLICITUD</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G2992_C59953" id="G2992_C59953">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3652 ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                            <div class="form-group">
                                <label for="G2992_C61722" id="LblG2992_C61722">AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" name="G2992_C61722" id="G2992_C61722" data-error="Before you wreck yourself"  > 
                                    </label>
                                </div>
                            </div>
                            <!-- FIN DEL CAMPO SI/NO -->

                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C66507" id="LblG2992_C66507">Agenda</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G2992_C66507" id="G2992_C66507">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4161 ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->

                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C61320" id="LblG2992_C61320">Seleccion resultado llamada</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G2992_C61320" id="G2992_C61320">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3679 ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
                            <!-- CAMPO TIPO FECHA -->
                            <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C66401" id="LblG2992_C66401">Fecha llamada</label>
                                <input type="text" class="form-control input-sm Fecha" value=""  name="G2992_C66401" id="G2992_C66401" placeholder="YYYY-MM-DD">
                            </div>
                            <!-- FIN DEL CAMPO TIPO FECHA-->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C61319" id="LblG2992_C61319">Numero radicado</label>
								<input type="text" class="form-control input-sm" id="G2992_C61319" value=""  name="G2992_C61319"  placeholder="Numero radicado">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
  
                            <!-- CAMPO TIPO MEMO -->
                            <div class="form-group">
                                <label for="G2992_C66484" id="LblG2992_C66484">Comentarios</label>
                                <textarea class="form-control input-sm" name="G2992_C66484" id="G2992_C66484"  value="" placeholder="Comentarios"></textarea>
                            </div>
                            <!-- FIN DEL CAMPO TIPO MEMO -->
  
                            <!-- CAMPO TIMEPICKER -->
                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C66402" id="LblG2992_C66402">Hora llamada</label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm Hora"  name="G2992_C66402" id="G2992_C66402" placeholder="HH:MM:SS" >
                                    <div class="input-group-addon" id="TMP_G2992_C66402">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65875" id="LblG2992_C65875">1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto)</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65875" id="G2992_C65875" placeholder="1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto)">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65876" id="LblG2992_C65876">2.  Conversación: Tono amable</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65876" id="G2992_C65876" placeholder="2.  Conversación: Tono amable">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65877" id="LblG2992_C65877">3. Lenguaje Adecuado</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65877" id="G2992_C65877" placeholder="3. Lenguaje Adecuado">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65878" id="LblG2992_C65878">4.   Volumen de voz adecuado</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65878" id="G2992_C65878" placeholder="4.   Volumen de voz adecuado">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65879" id="LblG2992_C65879">5. Escucha Activa</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65879" id="G2992_C65879" placeholder="5. Escucha Activa">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65880" id="LblG2992_C65880">6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65880" id="G2992_C65880" placeholder="6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65881" id="LblG2992_C65881">7. Si hay interrupción al cliente utiliza protocolo establecido</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65881" id="G2992_C65881" placeholder="7. Si hay interrupción al cliente utiliza protocolo establecido">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65882" id="LblG2992_C65882">8. Realiza acompañamiento al cliente al dejar la llamada en espera</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65882" id="G2992_C65882" placeholder="8. Realiza acompañamiento al cliente al dejar la llamada en espera">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65883" id="LblG2992_C65883">9.   Radicación pertinente de la SPQR´s</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65883" id="G2992_C65883" placeholder="9.   Radicación pertinente de la SPQR´s">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65884" id="LblG2992_C65884">10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx </label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65884" id="G2992_C65884" placeholder="10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx ">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2992_C65885" id="LblG2992_C65885">11.   Invita al cliente a diligenciar la encuesta de satisfaccion</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G2992_C65885" id="G2992_C65885" placeholder="11.   Invita al cliente a diligenciar la encuesta de satisfaccion">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G2992_C71470" id="LblG2992_C71470">Solicitud</label>
								<input type="text" class="form-control input-sm" id="G2992_C71470" value=""  name="G2992_C71470"  placeholder="Solicitud">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->

                            <!-- SECCION : PAGINAS INCLUIDAS -->
                            <input type="hidden" name="id" id="hidId" value='<?php if(isset($_GET['u'])){ echo $_GET['u']; }else{ echo "0"; } ?>'>
                            <input type="hidden" name="oper" id="oper" value='add'>
                            <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                            <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                            <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                            <input type= "hidden" name="campana" id="campana" value="<?php if(isset($_GET['camp'])){ echo base64_decode($_GET['camp']); }else{ echo "0"; }?>">
                            <div class="row">
                                <div class="col-xs-2">
                                    &nbsp;
                                </div><!-- /.col -->
                                <div class="col-xs-8">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar datos</button>
                                </div><!-- /.col -->
                            </div>
                        </form>
                    </div><!-- /.login-box-body -->
                </div><!-- /.login-box -->
            </div>
        </div>


        <!-- jQuery 2.2.3 -->
        <script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/plugins/select2/select2.full.min.js"></script>
        <!-- datepicker -->
        <script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- FastClick -->
        <script src="assets/plugins/fastclick/fastclick.js"></script>
        <script src="assets/timepicker/jquery.timepicker.js"></script>
        <script src="assets/plugins/sweetalert/sweetalert.min.js"></script>  
        <script src="assets/plugins/iCheck/icheck.min.js"></script>
        <script src="assets/js/jquery.validate.js"></script>
        <script src="assets/js/numeric.js"></script>

        <script type="text/javascript" src="formularios/G2992/G2992_eventos.js"></script>
        <script type="text/javascript">
            $.validator.setDefaults({
                submitHandler: function() { 
                     $("#formLogin").submit();
                }
            });

            $(function(){

                $.fn.datepicker.dates['es'] = {
                    days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
                    daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                    today: "Today",
                    clear: "Clear",
                    format: "yyyy-mm-dd",
                    titleFormat: "yyyy-mm-dd", 
                    weekStart: 0
                };

                //str_Select2 estos son los guiones
                


                //datepickers
                

            $("#G2992_C66500").datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true
            });

            $("#G2992_C59901").datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true
            });

            $("#G2992_C66401").datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true
            });

                //Timepickers
                


            //Timepicker
            $("#G2992_C59902").timepicker({
                'timeFormat': 'H:i:s',
                'minTime': '08:00:00',
                'maxTime': '17:00:00',
                'setTime': '08:00:00',
                'step'  : '5',
                'showDuration': true
            });

            //Timepicker
            $("#G2992_C66402").timepicker({
                'timeFormat': 'H:i:s',
                'minTime': '08:00:00',
                'maxTime': '17:00:00',
                'setTime': '08:00:00',
                'step'  : '5',
                'showDuration': true
            });

                //Validaciones numeros Enteros
                

            $("#G2992_C65875").numeric();
            
            $("#G2992_C65876").numeric();
            
            $("#G2992_C65877").numeric();
            
            $("#G2992_C65878").numeric();
            
            $("#G2992_C65879").numeric();
            
            $("#G2992_C65880").numeric();
            
            $("#G2992_C65881").numeric();
            
            $("#G2992_C65882").numeric();
            
            $("#G2992_C65883").numeric();
            
            $("#G2992_C65884").numeric();
            
            $("#G2992_C65885").numeric();
            

                //Validaciones numeros Decimales
               


               //Si tiene dependencias
               


    //function para La solicitud proviene de incumplimiento o errores por parte de la empresa 

    $("#G2992_C59951").change(function(){  
        //Esto es la parte de las listas dependientes
        

        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '3663' , idPadre : $(this).val() },
            success : function(data){
                $("#G2992_C59952").html(data);
            }
        });
        
        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '3652' , idPadre : $(this).val() },
            success : function(data){
                $("#G2992_C59953").html(data);
            }
        });
        
    });

    //function para SECCION 

    $("#G2992_C59952").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para TIPO DE DOLOR O SOLICITUD 

    $("#G2992_C59953").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para Agenda 

    $("#G2992_C66507").change(function(){ 
            $("#G2992_C59899").prop('disabled', false);
        
            $("#G2992_C59900").prop('disabled', false);
        
            $("#G2992_C59901").prop('disabled', false);
        
            $("#G2992_C59902").prop('disabled', false);
        
            $("#G2992_C59903").prop('disabled', false);
        
            if($(this).val() == '48574'){
            $("#G2992_C66401").prop('disabled', true); 
          
            $("#G2992_C66402").prop('disabled', true); 
          
            }
         
        //Esto es la parte de las listas dependientes
        

    });

    //function para Seleccion resultado llamada 

    $("#G2992_C61320").change(function(){ 
            $("#G2992_C59899").prop('disabled', false);
        
            $("#G2992_C59900").prop('disabled', false);
        
            $("#G2992_C59901").prop('disabled', false);
        
            $("#G2992_C59902").prop('disabled', false);
        
            $("#G2992_C59903").prop('disabled', false);
        
            if($(this).val() == '46330'){
            $("#G2992_C61319").prop('disabled', true); 
          
            }
         
        //Esto es la parte de las listas dependientes
        

    });
                

               <?php
                    if(isset($_GET['result'])){
                        if($_GET['result'] ==  1){
                ?>
                        swal({
                            title: "Exito!",
                            text: "Recibimos su solicitud, pronto estaremos en contacto",
                            type: "success",
                            confirmButtonText: "Ok"
                        },function(){

                        });
                        
                        
                <?php   }else{ ?>
                            swal({
                                title: "Error!",
                                text: 'Ocurrio un error, intenta mas tarde',
                                type: "error",
                                confirmButtonText: "Ok"
                            });
                <?php      
                        }
                    }
                ?>
            });
        </script>
        <Script type="text/javascript">
            $(document).ready(function() {
                <?php
                $campana = base64_decode($_GET['camp']);
                $Guion = 0;//id de la campaña
                $tabla = 0;// $_GET['u'];//ide del usuario
                $Lsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$campana;

                $result = $mysqli->query($Lsql);
                while($obj = $result->fetch_object()){
                    $Guion = $obj->CAMPAN_ConsInte__GUION__Gui_b;
                    $tabla = $obj->CAMPAN_ConsInte__GUION__Pob_b;
                } 
                //SELECT de la camic
                $campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b, CAMINC_ConsInte__CAMPO_Gui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$campana;
                
                $resultcampSql = $mysqli->query($campSql);
                while($key = $resultcampSql->fetch_object()){
                    
                    //Pregfuntar por el tipo de dato
                    $Lsql = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$key->CAMINC_ConsInte__CAMPO_Gui_b;
                    $res = $mysqli->query($Lsql);
                    $datos = $res->fetch_array();


                    //consulta de datos del usuario
                    $DatosSql = " SELECT ".$key->CAMINC_NomCamPob_b." as campo FROM ".$BaseDatos.".G".$tabla." WHERE G".$tabla."_ConsInte__b=".$_GET['u'];

                    //echo $DatosSql;
                    //recorro la tabla de donde necesito los datos
                    $resultDatosSql = $mysqli->query($DatosSql);
                    if($resultDatosSql){
                        while($objDatos = $resultDatosSql->fetch_object()){ 
                            if(!is_null($objDatos->campo) && $objDatos->campo != ''){

                                if($datos['PREGUN_Tipo______b'] != '8'){
                            ?>
                                    document.getElementById("<?=$key->CAMINC_NomCamGui_b;?>").value = '<?=trim($objDatos->campo);?>';
                            <?php  
                                }else{
                                    if($objDatos->campo == '1'){
                                        echo "$('#".$key->CAMINC_NomCamGui_b."').attr('checked' , true);";
                                    }else{
                                        echo "$('#".$key->CAMINC_NomCamGui_b."').attr('checked' , false);";
                                    }
                                    
                                } 
                            }
                        }
                    }
                    
                }
                ?>
            });
        </script>
        
