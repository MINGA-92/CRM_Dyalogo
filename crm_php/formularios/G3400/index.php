<?php 
    /*
        Document   : index
        Created on : 2024-03-06 12:59:21
        Author     : Nicolas y su poderoso generador, La gloria sea Para DIOS 
        Url 	   : id = MzQwMA==  
    */
    $url_crud =  "formularios/G3400/G3400_CRUD_web.php";
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
                        <form action="formularios/G3400/G3400_CRUD_web.php" method="post" id="formLogin">
  
                            <!-- lIBRETO O LABEL -->
                            <h3>Buenos días|tardes|noches, podría comunicarme con el señor(a) |NombreCliente|</h3>
                            <!-- FIN LIBRETO -->
  
                            <!-- lIBRETO O LABEL -->
                            <h3>Mi nombre es |Agente|, le estoy llamando de |Empresa| con el fin de ...</h3>
                            <!-- FIN LIBRETO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G3400_C69405" id="LblG3400_C69405">nombre</label>
								<input type="text" class="form-control input-sm" id="G3400_C69405" value=""  name="G3400_C69405"  placeholder="nombre">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->
 
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G3400_C69650" id="LblG3400_C69650">DOCUMENTO</label>
                                <input type="text" class="form-control input-sm Numerico" value=""  name="G3400_C69650" id="G3400_C69650" placeholder="DOCUMENTO">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
 
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="G3400_C70063" id="LblG3400_C70063">TELEFONO</label>
								<input type="text" class="form-control input-sm" id="G3400_C70063" value=""  name="G3400_C70063"  placeholder="TELEFONO">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->

                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C70150" id="LblG3400_C70150">LISTAA</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G3400_C70150" id="G3400_C70150">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4405 ORDER BY LISOPC_Nombre____b ASC";

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
                        <label for="G3400_C70151" id="LblG3400_C70151">LISTAB</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G3400_C70151" id="G3400_C70151">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4406 ORDER BY LISOPC_Nombre____b ASC";

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
                        <label for="G3400_C70152" id="LblG3400_C70152">LISTAC</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="G3400_C70152" id="G3400_C70152">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4407 ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";

                                }    
                                
                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->

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

        <script type="text/javascript" src="formularios/G3400/G3400_eventos.js"></script>
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
                

            $("#G3400_C69396").datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true
            });

                //Timepickers
                


            //Timepicker
            $("#G3400_C69397").timepicker({
                'timeFormat': 'H:i:s',
                'minTime': '08:00:00',
                'maxTime': '17:00:00',
                'setTime': '08:00:00',
                'step'  : '5',
                'showDuration': true
            });

                //Validaciones numeros Enteros
                

            $("#G3400_C69650").numeric();
            

                //Validaciones numeros Decimales
               


               //Si tiene dependencias
               


    //function para LISTAA 

    $("#G3400_C70150").change(function(){  
        //Esto es la parte de las listas dependientes
        

        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '4406' , idPadre : $(this).val() },
            success : function(data){
                $("#G3400_C70151").html(data);
            }
        });
        
    });

    //function para LISTAB 

    $("#G3400_C70151").change(function(){  
        //Esto es la parte de las listas dependientes
        

        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '4407' , idPadre : $(this).val() },
            success : function(data){
                $("#G3400_C70152").html(data);
            }
        });
        
    });

    //function para LISTAC 

    $("#G3400_C70152").change(function(){  
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
        
