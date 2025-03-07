<?php

function generar_Script_Web($int_Id_Generar) {

        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;

    if ($int_Id_Generar != 0) {
        $str_guion = 'G' . $int_Id_Generar;
        $codigoBody = '';
        $url_gracias = '';
        $scriptInsertar = '';
        $codigoForm = '';
        $imagen = '';
        $optin = '';
        $correos_ = '';

        $nombreCampana = '';
        $patacon = '';

        $index = '<?php
    /*
        Document   : index
        Created on : ' . date('Y-m-d H:s:i') . '
        Author     : Nicolas y su poderoso generador, La gloria sea Para DIOS
        Url 	   : id = ' . base64_encode($int_Id_Generar) . '
    */
    $url_crud =  "formularios/' . $str_guion . '/' . $str_guion . '_CRUD_web.php";
    ini_set(\'display_errors\', \'On\');
    ini_set(\'display_errors\', 1);
    include(__DIR__."/../../conexion.php");
    date_default_timezone_set(\'America/Bogota\');
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

        <link href=\'//fonts.googleapis.com/css?family=Sansita+One\' rel=\'stylesheet\' type=\'text/css\'>
        <link href=\'//fonts.googleapis.com/css?family=Open+Sans+Condensed:300\' rel=\'stylesheet\' type=\'text/css\'>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link rel="shortcut icon" href="assets/img/logo_dyalogo_mail.png"/>
        <style>

            .hed{

                font-family: \'Sansita One\', cursive;
                color:white;
            }

            .hed2{
                text-align:center;
                font-family: \'Sansita One\', cursive;
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

            [class^=\'select2\'] {
                border-radius: 0px !important;
            }
        </style>
    </head>
    <?php
        echo \'<body class="hold-transition" >\';
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
                        <form action="formularios/' . $str_guion . '/' . $str_guion . '_CRUD_web.php" method="post" id="formLogin">';
        $str_Funciones_Js = '';
        $str_Funciones_Jsx = '';
        // $checkColumnas = $_POST['checkColumnas'];



        $str_guion = 'G' . $int_Id_Generar;
        $str_Alfabeto = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z','aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az','ba','bb','bc','bd','be','bf','bg','bh','bi','bj','bk','bl','bm','bn','bo','bp','bq','br','bs','bt','bu','bv','bw','bx','by','bz','ca','cb','cc','cd','ce','cf','cg','ch','ci','cj','ck','cl','cm','cn','co','cp','cq','cr','cs','ct','cu','cv','cw','cx','cy','cz','da','db','dc','dd','de','df','dg','dh','di','dj','dk','dl','dm','dn','do','dp','dq','dr','ds','dt','du','dv','dw','dx','dy','dz','ea','eb','ec','ed','ee','ef','eg','eh','ei','ej','ek','el','em','en','eo','ep','eq','er','es','et','eu','ev','ew','ex','ey','ez','fa','fb','fc','fd','fe','ff','fg','fh','fi','fj','fk','fl','fm','fn','fo','fp','fq','fr','fs','ft','fu','fv','fw','fx','fy','fz','ga','gb','gc','gd','ge','gf','gg','gh','gi','gj','gk','gl','gm','gn','go','gp','gq','gr','gs','gt','gu','gv','gw','gx','gy','gz','ha','hb','hc','hd','he','hf','hg','hh','hi','hj','hk','hl','hm','hn','ho','hp','hq','hr','hs','ht','hu','hv','hw','hx','hy','hz','ia','ib','ic','id','ie','if','ig','ih','ii','ij','ik','il','im','in','io','ip','iq','ir','is','it','iu','iv','iw','ix','iy','iz','ja','jb','jc','jd','je','jf','jg','jh','ji','jj','jk','jl','jm','jn','jo','jp','jq','jr','js','jt','ju','jv','jw','jx','jy','jz');

        $str_Guion_C = $str_guion . "_C";
        $str_Guionstr_Select2 = '';
        $str_ExtrasParaGuiones = '';
        $str_Funciones_Carga_Padres_Maestros = '';
        $str_Cancelar_Modal = "";
        $str_Guardar_Modal = "";

        $str_NombreGuion_ = "";

        $str_Lsql = "";

        $str_Lsql = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b , PREGUN_IndiRequ__b  FROM " . $BaseDatos_systema . ".PREGUN JOIN " . $BaseDatos_systema . ".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = " . $int_Id_Generar . " AND SECCIO_TipoSecc__b != 2 ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";

        $str_Lsql2 = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b, PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b FROM " . $BaseDatos_systema . ".PREGUN JOIN " . $BaseDatos_systema . ".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE PREGUN_ConsInte__GUION__b = " . $int_Id_Generar . " AND SECCIO_TipoSecc__b != 2 ORDER BY PREGUN_ConsInte__SECCIO_b ASC, PREGUN_OrdePreg__b ASC";

        $str_LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b, GUION__ConsInte__PREGUN_Tip_b, GUION__ConsInte__PREGUN_Rep_b, GUION__ConsInte__PREGUN_Fag_b, GUION__ConsInte__PREGUN_Hag_b, GUION__ConsInte__PREGUN_Com_b FROM " . $BaseDatos_systema . ".GUION_ WHERE GUION__ConsInte__b = " . $int_Id_Generar . " AND GUION__Tipo______b = 1";

        $GUION__ConsInte__PREGUN_Pri_b = null;
        $GUION__ConsInte__PREGUN_Sec_b = null;
        $GUION__ConsInte__PREGUN_Tip_b = null;
        $GUION__ConsInte__PREGUN_Rep_b = null;
        $GUION__ConsInte__PREGUN_Fag_b = null;
        $GUION__ConsInte__PREGUN_Hag_b = null;
        $GUION__ConsInte__PREGUN_Com_b = null;


        $res_CamposBuscadosIzquierda = $mysqli->query($str_LsqlCamposPrimairos);
        while ($key = $res_CamposBuscadosIzquierda->fetch_object()) {
            $GUION__ConsInte__PREGUN_Tip_b = $key->GUION__ConsInte__PREGUN_Tip_b;
            $GUION__ConsInte__PREGUN_Rep_b = $key->GUION__ConsInte__PREGUN_Rep_b;
            $GUION__ConsInte__PREGUN_Fag_b = $key->GUION__ConsInte__PREGUN_Fag_b;
            $GUION__ConsInte__PREGUN_Hag_b = $key->GUION__ConsInte__PREGUN_Hag_b;
            $GUION__ConsInte__PREGUN_Com_b = $key->GUION__ConsInte__PREGUN_Com_b;
        }//Cierro el While $key = $res_CamposBuscadosIzquierda->fetch_object()

        $str_LsqlCamposPrimairos = "SELECT GUION__ConsInte__PREGUN_Pri_b,  GUION__ConsInte__PREGUN_Sec_b FROM " . $BaseDatos_systema . ".GUION_ WHERE GUION__ConsInte__b =" . $int_Id_Generar;
        $res_CamposBuscadosIzquierda = $mysqli->query($str_LsqlCamposPrimairos);
        while ($key = $res_CamposBuscadosIzquierda->fetch_object()) {
            $GUION__ConsInte__PREGUN_Pri_b = $key->GUION__ConsInte__PREGUN_Pri_b;
            $GUION__ConsInte__PREGUN_Sec_b = $key->GUION__ConsInte__PREGUN_Sec_b;
        }//cierro el While $key = $res_CamposBuscadosIzquierda->fetch_object()


        $carpeta = "/var/www/html/crm_php/formularios/" . $str_guion;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777);
        }

        $fp = fopen($carpeta . "/index.php", "w");

        $res_Campos_6 = $mysqli->query($str_Lsql2);
        $str_CamposTabla = '';
        $str_OrdenTabla = '';
        $str_campTabla = '';
        $str_str_JoinsTabla = '';
        $str_PrimerCampoaBuscar = '';
        $str_SegundoCampoaBuscar = '';
        $j = 0;

        $str_CamposValidaciones = '';
        $res_ValoresValidados = $mysqli->query($str_Lsql);
        $str_FechaValidadaOno = '';
        $str_HoraValidadaOno = '';
        $str_BotonSalvar = '';
        $int_HayQueValidar = 0;
        $str_Select2 = '';
        $str_FuncionesCampoGuion = '';
        $str_NumeroFuncion = '';
        $str_DecimalFuncion = '';
        $str_PrimerCamposJoin = '0';
        $str_Joins = '';

        while ($key = $res_ValoresValidados->fetch_object()) {

            if ($key->PREGUN_IndiRequ__b != 0) {

                if ($key->tipo_Pregunta == '6' || $key->tipo_Pregunta == '11') {

//echo "INDICA REQUEDI " . $key->PREGUN_IndiRequ__b .  " val: " . $str_CamposValidaciones;

                    $str_CamposValidaciones .= "\n" . '
            if($("#' . $str_Guion_C . $key->id . '").val() < 1){
                alertify.error(\'' . $key->error . '\');
                $("#' . $str_Guion_C . $key->id . '").focus();
                valido = 1;
            }';
                } else if ($key->tipo_Pregunta == '8') {
                    $str_CamposValidaciones .= "\n" . '
            if($("#' . $str_Guion_C . $key->id . '").is(\'checked\')){
                alertify.error(\'' . $key->error . '\');
                $("#' . $str_Guion_C . $key->id . '").focus();
                valido = 1;
            }';
                } else {
                    $str_CamposValidaciones .= "\n" . '
            if($("#' . $str_Guion_C . $key->id . '").val().length < 1){
                alertify.error(\'' . $key->error . '\');
                $("#' . $str_Guion_C . $key->id . '").focus();
                valido =1;
            }';
                }

                $int_HayQueValidar += 1;
            }//cierro el If de $key->PREGUN_IndiRequ__b != 0


            if ($key->tipo_Pregunta == '3') {
                $str_NumeroFuncion .= '
            $("#' . $str_Guion_C . $key->id . '").numeric();
            ';
            }

            if ($key->tipo_Pregunta == '4') {
                $str_DecimalFuncion .= '
            $("#' . $str_Guion_C . $key->id . '").numeric();
            ';
            }

            if ($key->tipo_Pregunta == '3' || $key->tipo_Pregunta == '4') {
                if (!is_null($key->minimoNumero) && !is_null($key->maximoNumero)) {
                    $str_CamposValidaciones .= "\n" . '
                if($("#' . $str_Guion_C . $key->id . '").val().length > 0){
                    if( $("#' . $str_Guion_C . $key->id . '").val() > ' . ($key->minimoNumero - 1) . ' && $("#' . $str_Guion_C . $key->id . '").val() < ' . ($key->maximoNumero + 1) . '){

                    }else{
                        alertify.error(\'' . $key->error . '\');
                        $("#' . $str_Guion_C . $key->id . '").focus();
                        valido =1;
                    }
                }';
                } else if (!is_null($key->minimoNumero) && is_null($key->maximoNumero)) {
                    $str_CamposValidaciones .= "\n" . '
                if($("#' . $str_Guion_C . $key->id . '").val().length > 0){
                    if( $("#' . $str_Guion_C . $key->id . '").val() > ' . ($key->minimoNumero - 1) . '){

                    }else{
                        alertify.error(\'' . $key->error . '\');
                        $("#' . $str_Guion_C . $key->id . '").focus();
                        valido =1;
                    }
                }';
                } else if (is_null($key->minimoNumero) && !is_null($key->maximoNumero)) {
                    $str_CamposValidaciones .= "\n" . '
                if($("#' . $str_Guion_C . $key->id . '").val().length > 0){
                    if(  $("#' . $str_Guion_C . $key->id . '").val() < ' . ($key->maximoNumero + 1) . '){

                    }else{
                        alertify.error(\'' . $key->error . '\');
                        $("#' . $str_Guion_C . $key->id . '").focus();
                        valido =1;
                    }
                }';
                }
            }//if $key->tipo_Pregunta == '3' || $key->tipo_Pregunta == '4'



            if ($key->tipo_Pregunta == '5') {
                if (!is_null($key->fechaMinimo) && !is_null($key->fechaMaximo)) {
                    $str_FechaValidadaOno .= "\n" . '
            var startDate = new Date(\'' . $key->fechaMinimo . '\');
            var FromEndDate = new Date(\'' . $key->fechaMaximo . '\');
            $("#' . $str_Guion_C . $key->id . '").datepicker({
                language: "es",
                autoclose: true,
                startDate: startDate,
                endDate : FromEndDate,
                todayHighlight: true
            });';
                } else if (!is_null($key->fechaMinimo) && is_null($key->fechaMaximo)) {
                    $str_FechaValidadaOno .= "\n" . '
            var startDate = new Date(\'' . $key->fechaMinimo . '\');
            $("#' . $str_Guion_C . $key->id . '").datepicker({
                language: "es",
                autoclose: true,
                startDate: startDate,
                todayHighlight: true
            });';
                } else if (is_null($key->fechaMinimo) && !is_null($key->fechaMaximo)) {
                    $str_FechaValidadaOno .= "\n" . '
            var FromEndDate = new Date(\'' . $key->fechaMaximo . '\');
            $("#' . $str_Guion_C . $key->id . '").datepicker({
                language: "es",
                autoclose: true,
                endDate : FromEndDate,
                todayHighlight: true
            });';
                } else {
                    $str_FechaValidadaOno .= "\n" . '
            $("#' . $str_Guion_C . $key->id . '").datepicker({
                language: "es",
                autoclose: true,
                todayHighlight: true
            });';
                }
            }//if $key->tipo_Pregunta == '5'

            if ($key->tipo_Pregunta == '10') {
                if (!is_null($key->horaMini) && !is_null($key->horaMaximo)) {
                    $str_HoraValidadaOno .= "\n" . '
            //Timepicker
            $("#' . $str_Guion_C . $key->id . '").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \'' . $key->horaMini . '\',
                \'maxTime\': \'' . $key->horaMaximo . '\',
                \'setTime\': \'' . $key->horaMini . '\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';
                } else if (!is_null($key->horaMini) && is_null($key->horaMaximo)) {
                    $str_HoraValidadaOno .= "\n" . '
            //Timepicker
            $("#' . $str_Guion_C . $key->id . '").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \'' . $key->horaMini . '\',
                \'maxTime\': \'17:00:00\',
                \'setTime\': \'' . $key->horaMini . '\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';
                } else if (is_null($key->horaMini) && !is_null($key->horaMaximo)) {
                    $str_HoraValidadaOno .= "\n" . '
            //Timepicker
            $("#' . $str_Guion_C . $key->id . '").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \'08:00:00\',
                \'maxTime\': \'' . $key->horaMaximo . '\',
                \'setTime\': \'08:00:00\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';
                } else {
                    $str_HoraValidadaOno .= "\n" . '
            //Timepicker
            $("#' . $str_Guion_C . $key->id . '").timepicker({
                \'timeFormat\': \'H:i:s\',
                \'minTime\': \'08:00:00\',
                \'maxTime\': \'17:00:00\',
                \'setTime\': \'08:00:00\',
                \'step\'  : \'5\',
                \'showDuration\': true
            });';
                }
            }//$key->tipo_Pregunta == '10'

            if (!is_null($key->minimoNumero) || !is_null($key->maximoNumero)) {
                $int_HayQueValidar += 1;
            }
        }//$key = $res_ValoresValidados->fetch_object()

        fputs($fp, $index);
        fputs($fp, chr(13) . chr(10)); // Genera saldo de linea

        $SeccionSsql = "SELECT SECCIO_ConsInte__b, SECCIO_TipoSecc__b, SECCIO_Nombre____b, SECCIO_PestMini__b, SECCIO_NumColumnas_b FROM " . $BaseDatos_systema . ".SECCIO WHERE SECCIO_ConsInte__GUION__b =  " . $int_Id_Generar . " AND SECCIO_TipoSecc__b = 1 ORDER BY SECCIO_Orden_____b ASC ";

        $Secciones = $mysqli->query($SeccionSsql);
        $Columnas = 1;

        $str_Select2_hojadeDatos = '';

        while ($seccionAqui = $Secciones->fetch_object()) {

            $id_seccion = $seccionAqui->SECCIO_ConsInte__b;
            if (!empty($seccionAqui->SECCIO_NumColumnas_b)) {
                $Columnas = $seccionAqui->SECCIO_NumColumnas_b;
            }


            //Aqui hacemos el dibujo de los campos
            $str_LsqlXD = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error , PREGUN_ConsInte__SECCIO_b, PREGUN_Default___b, PREGUN_ContAcce__b, PREGUN_PermiteAdicion_b FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__GUION__b = " . $int_Id_Generar . " AND PREGUN_ConsInte__SECCIO_b = " . $id_seccion . " AND PREGUN_FueGener_b != 3 ORDER BY PREGUN_OrdePreg__b ASC";


            $campos = $mysqli->query($str_LsqlXD);
            $rowsss = 0;

            $seccion = '';
            $seccionActual = '';

            $maxColumns = $Columnas;
            $filaActual = 0;
            $checkColumnas = (12 / $Columnas);
            while ($obj = $campos->fetch_object()) {

                if ($obj->id != $GUION__ConsInte__PREGUN_Tip_b &&
                        $obj->id != $GUION__ConsInte__PREGUN_Rep_b &&
                        $obj->id != $GUION__ConsInte__PREGUN_Fag_b &&
                        $obj->id != $GUION__ConsInte__PREGUN_Hag_b &&
                        $obj->id != $GUION__ConsInte__PREGUN_Com_b) {

                    $valorPordefecto = $obj->PREGUN_Default___b;
                    $valoraMostrar = "";
                    $disableds = '';
                    if ($obj->PREGUN_ContAcce__b == 2) {
                        $disableds = 'disabled';
                    }

                    switch ($obj->tipo_Pregunta) {
                        case '1':
                            $campo = '
							<!-- CAMPO TIPO TEXTO -->
							<div class="form-group">
								<label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
								<input type="text" class="form-control input-sm" id="' . $str_Guion_C . $obj->id . '" value="' . $valoraMostrar . '" ' . $disableds . ' name="' . $str_Guion_C . $obj->id . '"  placeholder="' . ($obj->titulo_pregunta) . '">
							</div>
							<!-- FIN DEL CAMPO TIPO TEXTO -->';
                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").on(\'blur\',function(e){});';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;

                        case '2':
                            $campo = '
                            <!-- CAMPO TIPO MEMO -->
                            <div class="form-group">
                                <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                                <textarea class="form-control input-sm" name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '" ' . $disableds . ' value="' . $valoraMostrar . '" placeholder="' . ($obj->titulo_pregunta) . '"></textarea>
                            </div>
                            <!-- FIN DEL CAMPO TIPO MEMO -->';
                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").on(\'blur\',function(e){});';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;

                        case '3':
                            $campo = '
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                                <input type="text" class="form-control input-sm Numerico" value="' . $valoraMostrar . '" ' . $disableds . ' name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '" placeholder="' . ($obj->titulo_pregunta) . '">
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->';
                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").on(\'blur\',function(e){});';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;

                        case '4':
                            $campo = '
                            <!-- CAMPO TIPO DECIMAL -->
                            <!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                                <input type="text" class="form-control input-sm Decimal" value="' . $valoraMostrar . '" ' . $disableds . ' name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '" placeholder="' . ($obj->titulo_pregunta) . '">
                            </div>
                            <!-- FIN DEL CAMPO TIPO DECIMAL -->';
                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").on(\'blur\',function(e){});';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;

                        case '5':
                            $campo = '
                            <!-- CAMPO TIPO FECHA -->
                            <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                                <input type="text" class="form-control input-sm Fecha" value="' . $valoraMostrar . '" ' . $disableds . ' name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '" placeholder="YYYY-MM-DD">
                            </div>
                            <!-- FIN DEL CAMPO TIPO FECHA-->';
                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").on(\'blur\',function(e){});';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;

                        case '10':
                            $campo = '
                            <!-- CAMPO TIMEPICKER -->
                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm Hora" ' . $disableds . ' name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '" placeholder="HH:MM:SS" >
                                    <div class="input-group-addon" id="TMP_' . $str_Guion_C . $obj->id . '">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->';
                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").on(\'blur\',function(e){});';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;

                        case '6':

                            $campo = '
                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ' . $obj->lista . ' ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\'>".($obje->OPCION_Nombre____b)."</option>";

                                }

                            ?>
                        </select>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->';

                            $GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM " . $BaseDatos_systema . ".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '" . $obj->id . "' GROUP BY PRSADE_ConsInte__OPCION_b";
                            $result = $mysqli->query($GuionsSql);
                            $saltos = '';
                            if ($result->num_rows > 0) {
                                $sinsalto = '';
                                $tamanho = 0;
                                $SqlLosquenoEstan = "SELECT PREGUN_ConsInte__b FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__GUION__b = " . $int_Id_Generar . " AND PREGUN_ContAcce__b IS NULL;";
                                // echo $SqlLosquenoEstan;
                                $ResultLosqueNoestan = $mysqli->query($SqlLosquenoEstan);
                                while ($newObjrQueNoestan = $ResultLosqueNoestan->fetch_object()) {
                                    $saltos .= '
            $("#' . $str_Guion_C . $newObjrQueNoestan->PREGUN_ConsInte__b . '").prop(\'disabled\', false);
        ';
                                }
                                while ($objr = $result->fetch_object()) {
                                    /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
                                    $saltos .= '
            if($(this).val() == \'' . $objr->PRSADE_ConsInte__OPCION_b . '\'){';
                                    $newSql = "SELECT * FROM " . $BaseDatos_systema . ".PRSASA JOIN " . $BaseDatos_systema . ".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = " . $obj->id . " AND PRSADE_ConsInte__OPCION_b = " . $objr->PRSADE_ConsInte__OPCION_b . ";";
                                    $newResult = $mysqli->query($newSql);
                                    while ($newObjr = $newResult->fetch_object()) {
                                        $saltos .= '
            $("#' . $newObjr->PRSASA_NombCont__b . '").prop(\'disabled\', true);
          ';
                                    }
                                    $saltos .= '
            }
        ';
                                }
                            }


//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
                            $validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = " . $obj->id;
                            $resEsPadre = $mysqli->query($validarSiEsPadre);
                            $hijosdeEsteGuion = "\n";
                            if ($resEsPadre->num_rows > 0) {
                                while ($keyPadre = $resEsPadre->fetch_object()) {
                                    $hijosdeEsteGuion .= '
        $.ajax({
            url    : \'<?php echo $url_crud; ?>\',
            type   : \'post\',
            data   : { getListaHija : true , opcionID : \'' . $keyPadre->PREGUN_ConsInte__OPCION_B . '\' , idPadre : $(this).val() },
            success : function(data){
                $("#' . $str_Guion_C . $keyPadre->PREGUN_ConsInte__b . '").html(data);
            }
        });
        ';
                                }
                            }

                            $str_Funciones_Jsx .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").change(function(){ ' . $saltos . '
        //Esto es la parte de las listas dependientes
        ' . $hijosdeEsteGuion . '
    });';



                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;


                        case '13':


                            $campo = '
                    <!-- CAMPO DE TIPO LISTA / Respuesta -->
                    <div class="form-group">
                        <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                        <select class="form-control input-sm select2"  style="width: 100%;" name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '">
                            <option value="0">Seleccione</option>
                            <?php
                                /*
                                    SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                */
                                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, LISOPC_Respuesta_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ' . $obj->lista . ' ORDER BY LISOPC_Nombre____b ASC";

                                $obj = $mysqli->query($Lsql);
                                while($obje = $obj->fetch_object()){
                                    echo "<option value=\'".$obje->OPCION_ConsInte__b."\' respuesta = \'".$obje->LISOPC_Respuesta_b."\'>".($obje->OPCION_Nombre____b)."</option>";

                                }

                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="respuesta_' . $str_Guion_C . $obj->id . '" id="respuesta_Lbl' . $str_Guion_C . $obj->id . '">Respuesta</label>
                        <textarea id="respuesta_' . $str_Guion_C . $obj->id . '" class="form-control" placeholder="Respuesta"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO LISTA  / Respuesta -->';

                            $GuionsSql = "SELECT PRSADE_ConsInte__OPCION_b  FROM " . $BaseDatos_systema . ".PRSADE WHERE PRSADE_ConsInte__PREGUN_b = '" . $obj->id . "' GROUP BY PRSADE_ConsInte__OPCION_b";
                            $result = $mysqli->query($GuionsSql);
                            $saltos = '';
                            if ($result->num_rows > 0) {
                                $sinsalto = '';
                                $tamanho = 0;
                                $SqlLosquenoEstan = "SELECT PREGUN_ConsInte__b FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__GUION__b = " . $int_Id_Generar . " AND PREGUN_ContAcce__b IS NULL;";
                                // echo $SqlLosquenoEstan;
                                $ResultLosqueNoestan = $mysqli->query($SqlLosquenoEstan);
                                while ($newObjrQueNoestan = $ResultLosqueNoestan->fetch_object()) {
                                    $saltos .= '
            $("#' . $str_Guion_C . $newObjrQueNoestan->PREGUN_ConsInte__b . '").prop(\'disabled\', false);
        ';
                                }
                                while ($objr = $result->fetch_object()) {
                                    /* Ya tengo la opcion ahora vamos a buscar los PRSASA de estas opciones */
                                    $saltos .= '
            if($(this).val() == \'' . $objr->PRSADE_ConsInte__OPCION_b . '\'){';
                                    $newSql = "SELECT * FROM " . $BaseDatos_systema . ".PRSASA JOIN " . $BaseDatos_systema . ".PRSADE ON PRSADE_ConsInte__b = PRSASA_ConsInte__PRSADE_b WHERE PRSADE_ConsInte__PREGUN_b = " . $obj->id . " AND PRSADE_ConsInte__OPCION_b = " . $objr->PRSADE_ConsInte__OPCION_b . ";";
                                    $newResult = $mysqli->query($newSql);
                                    while ($newObjr = $newResult->fetch_object()) {
                                        $saltos .= '
            $("#' . $newObjr->PRSASA_NombCont__b . '").prop(\'disabled\', true);
          ';
                                    }
                                    $saltos .= '
            }
        ';
                                }
                            }


//Aqui lo que estamos haciendo es validar que la pregunta tenga o no hijos o dependencias
                            $validarSiEsPadre = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__OPCION_B FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte_PREGUN_Depende_b = " . $obj->id;
                            $resEsPadre = $mysqli->query($validarSiEsPadre);
                            $hijosdeEsteGuion = "\n";
                            if ($resEsPadre->num_rows > 0) {
                                while ($keyPadre = $resEsPadre->fetch_object()) {
                                    $hijosdeEsteGuion .= '
        $.ajax({
            url    : \'<?php echo $url_crud;?>\',
            type   : \'post\',
            data   : { getListaHija : true , opcionID : \'' . $keyPadre->PREGUN_ConsInte__OPCION_B . '\' , idPadre : $(this).val() },
            success : function(data){
                $("#' . $guion_c . $keyPadre->PREGUN_ConsInte__b . '").html(data);
            }
        });
        ';
                                }
                            }

                            $str_Funciones_Jsx .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").change(function(){ ' . $saltos . '
        //Esto es la parte de las listas dependientes
        ' . $hijosdeEsteGuion . '
    });';



                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;





                        case '11':

                            //Primero necesitamos obener el campo que vamos a usar
                            $campoGuion = $obj->id;
                            $str_Guionstr_Select2 = $obj->guion;
                            //Luego buscamos los campos en la tabla Pregui
                            $CampoSql = "SELECT * FROM " . $BaseDatos_systema . ".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = " . $campoGuion;
                            //recorremos esos campos para ir a buscarlos en la tabla campo_
                            $CampoSqlR = $mysqli->query($CampoSql);
                            $str_CamposConsultaGuion = ' G' . $obj->guion . '_ConsInte__b as id ';


                            $str_Campos_A_Mostrar = '';
                            $int_Valor_Del_Array = 0;
                            $str_Nombres_De_Campos = '';
                            $camposAcolocarDinamicamente = '0';

                            while ($objet = $CampoSqlR->fetch_object()) {
                                //aqui obtenemos los campos que se colocara el valor dinamicamente al seleccionar una opcion del guion, ejemplo ciudad - departamento- pais..
                                if ($objet->PREGUI_Consinte__CAMPO__GUI_B != 0) {
                                    //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                                    $campoamostrarSql = 'SELECT * FROM ' . $BaseDatos_systema . '.CAMPO_ WHERE CAMPO__ConsInte__b = ' . $objet->PREGUI_Consinte__CAMPO__GUI_B;
                                    // echo $campoamostrarSql;
                                    $campoamostrarSqlE = $mysqli->query($campoamostrarSql);
                                    while ($campoNombres = $campoamostrarSqlE->fetch_object()) {
                                        $camposAcolocarDinamicamente .= '|' . $campoNombres->CAMPO__Nombre____b;
                                    }
                                }

                                //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                                $campoObtenidoSql = 'SELECT * FROM ' . $BaseDatos_systema . '.CAMPO_ WHERE CAMPO__ConsInte__b = ' . $objet->PREGUI_ConsInte__CAMPO__b;
                                //echo $campoObtenidoSql;
                                $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);
                                while ($objCampo = $resultCamposObtenidos->fetch_object()) {

                                    //Busco el nombre del campo para el nombre de las columnas
                                    $selectGuion = "SELECT PREGUN_Texto_____b as titulo_pregunta FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__b =" . $objCampo->CAMPO__ConsInte__PREGUN_b;
                                    $selectGuionE = $mysqli->query($selectGuion);
                                    while ($o = $selectGuionE->fetch_object()) {
                                        if ($int_Valor_Del_Array == 0) {
                                            $str_Nombres_De_Campos .= ($o->titulo_pregunta);
                                        } else {
                                            $str_Nombres_De_Campos .= ' | ' . ($o->titulo_pregunta) . '';
                                        }
                                    }
                                    //añadimos los campos a la consulta que se necesita para cargar el guion
                                    $str_CamposConsultaGuion .= ', ' . $objCampo->CAMPO__Nombre____b;
                                    if ($int_Valor_Del_Array == 0) {
                                        $str_Campos_A_Mostrar .= '".($obj->' . $objCampo->CAMPO__Nombre____b . ')."';
                                    } else {
                                        $str_Campos_A_Mostrar .= ' | ".($obj->' . $objCampo->CAMPO__Nombre____b . ')."';
                                    }

                                    $int_Valor_Del_Array++;
                                }
                            }
                            $datosaEnviar = "";

                            $campo = '
                            <?php
                            $str_Lsql = "SELECT ' . $str_CamposConsultaGuion . ' FROM ".$BaseDatos_systema.".G' . $obj->guion . '";
                            ?>
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '">
                                    <option>' . $str_Nombres_De_Campos . '</option>
                                    <?php
                                        /*
                                            SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                        */
                                        $combo = $mysqli->query($str_Lsql);
                                        while($obj = $combo->fetch_object()){
                                            echo "<option value=\'".$obj->id."\' dinammicos=\'' . $camposAcolocarDinamicamente . '\'>' . $str_Campos_A_Mostrar . '</option>";

                                        }

                                    ?>
                                </select>
                            </div>
                            <!-- FIN DEL CAMPO TIPO LISTA -->';



                            $str_FuncionesCampoGuion .= "\n" . '
        if(isset($_GET[\'CallDatosCombo_Guion_' . $str_Guion_C . $obj->id . '\'])){
            $Ysql = \'SELECT  ' . $str_CamposConsultaGuion . ' FROM ".$BaseDatos_systema.".G' . $obj->guion . '\';
            $guion = $mysqli->query($Ysql);
            echo \'<select class="form-control input-sm"  name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '">\';
            echo \'<option >' . $str_Nombres_De_Campos . '</option>\';
            while($obj = $guion->fetch_object()){
               echo "<option value=\'".$obj->id."\' dinammicos=\'' . $camposAcolocarDinamicamente . '\'>' . $str_Campos_A_Mostrar . '</option>";
            }
            echo \'</select>\';
        }';

                            if ($int_Valor_Del_Array > 0) {
                                $totalRows = 12 / $int_Valor_Del_Array;
                            } else {
                                $totalRows = 12;
                            }

                            $rows = '';
                            for ($i = 0; $i < $int_Valor_Del_Array; $i++) {
                                $rows .= '
                                \'<div class="col-md-' . number_format($totalRows) . '">\' + r[' . $i . '] + \'</div>\' +';
                            }



                            $str_Select2 .= "\n" . '
        $("#' . $str_Guion_C . $obj->id . '").select2({
            templateResult: function(data) {
                var r = data.text.split(\'|\');
                var $result = $(
                    \'<div class="row">\' +
                        ' . $rows . '
                    \'</div>\'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split(\'|\');
                return r[0];
            }
        });' . "\n" . '
        $("#' . $str_Guion_C . $obj->id . '").change(function(){
            var valores = $("#' . $str_Guion_C . $obj->id . ' option:selected").text();
            var campos = $("#' . $str_Guion_C . $obj->id . ' option:selected").attr("dinammicos");
            var r = valores.split(\'|\');
            if(r.length > 1){

                var c = campos.split(\'|\');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(\' \', \'\');
                        $("#"+c[i]).val(change).change();
                    }

                }
            }
        });';
                            $str_Select2_hojadeDatos .= "\n" . '
                    $("#"+ rowid +"_' . $str_Guion_C . $obj->id . '").change(function(){
                        var valores = $("#"+ rowid +"_' . $str_Guion_C . $obj->id . ' option:selected").text();
                        var campos = $("#"+ rowid +"_' . $str_Guion_C . $obj->id . ' option:selected").attr("dinammicos");
                        var r = valores.split(\'|\');

                        if(r.length > 1){

                            var c = campos.split(\'|\');
                            for(i = 1; i < r.length; i++){
                                if(!$("#"+c[i]).is("select")) {
                                // the input field is not a select
                                    $("#"+ rowid +"_"+c[i]).val(r[i]);
                                }else{
                                    var change = r[i].replace(\' \', \'\');
                                    $("#"+ rowid +"_"+c[i]).val(change).change();
                                }

                            }
                        }
                    });';



                            $revisarAjax = '';
                            $revisarstr_Lsql = 'SELECT PREGUN_ConsInte__b, PREGUN_DepGuion_b, PREGUN_DepColGui_b, PREGUN_Agrupar_b FROM ' . $BaseDatos_systema . '.PREGUN WHERE PREGUN_DepPadre_b = ' . $obj->id;

                            $revisarResultado = $mysqli->query($revisarstr_Lsql);
                            $PREGUN_Dependiente = null;
                            $PREGUN_DepGuion_b = null;
                            $PREGUN_DepColGui_b = null;
                            $PREGUN_Agrupar_b = null;
                            while ($kj = $revisarResultado->fetch_object()) {
                                $PREGUN_Dependiente = $kj->PREGUN_ConsInte__b;
                            }

                            //si de verdad el man es padre
                            if (!is_null($PREGUN_Dependiente)) {
                                $revisarAjax .= '
        $.ajax({
            url     : \'formularios/' . $str_guion . '/' . $str_guion . '_CRUD.php?mostrarPadre_' . $PREGUN_Dependiente . '=si\',
            type    : \'post\',
            data    : { padre : $(this).val() },
            success : function(data){
                $("#' . $str_guion . '_C' . $PREGUN_Dependiente . '").html(data);
            }
        });
    ';
                            }

                            $revisarstr_Lsql2 = 'SELECT PREGUN_DepPadre_b , PREGUN_DepGuion_b, PREGUN_DepColGui_b, PREGUN_Agrupar_b FROM ' . $BaseDatos_systema . '.PREGUN WHERE PREGUN_ConsInte__b = ' . $obj->id;

                            $revisarResultado2 = $mysqli->query($revisarstr_Lsql2);
                            $PREGUN_DepGuion_b = null;
                            $PREGUN_DepColGui_b = null;
                            $PREGUN_Agrupar_b = null;
                            while ($kj = $revisarResultado2->fetch_object()) {
                                $PREGUN_DepGuion_b = $kj->PREGUN_DepGuion_b;
                                $PREGUN_DepColGui_b = $kj->PREGUN_DepColGui_b;
                                $PREGUN_Agrupar_b = $kj->PREGUN_Agrupar_b;
                            }

                            if (!is_null($PREGUN_DepGuion_b) && !is_null($PREGUN_DepColGui_b) && !is_null($PREGUN_Agrupar_b)) {
                                $str_Funciones_Carga_Padres_Maestros .= "\n" . '
        if(isset($_GET[\'mostrarPadre_' . $obj->id . '\'])){
            $padre = $_POST[\'padre\'];
            if(!is_null($padre) && $padre != \'\'){
                $str_Lsql  = "SELECT ' . $str_CamposConsultaGuion . ' FROM ".$BaseDatos_systema.".G' . $obj->guion . ' WHERE G' . $PREGUN_DepGuion_b . '_C' . $PREGUN_DepColGui_b . ' = ".$padre;
                $res = $mysqli->query($str_Lsql);
                while($obj = $res->fetch_object()){
                     echo "<option value=\'".$obj->id."\' dinammicos=\'' . $camposAcolocarDinamicamente . '\'>' . $str_Campos_A_Mostrar . '</option>";
                }
            }
        }
                        ';
                            }


                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").change(function(){
        ' . $revisarAjax . '
    });';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea */
                            break;

                        case '8':
                            $campo = '
                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                            <div class="form-group">
                                <label for="' . $str_Guion_C . $obj->id . '" id="Lbl' . $str_Guion_C . $obj->id . '">' . ($obj->titulo_pregunta) . '</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" name="' . $str_Guion_C . $obj->id . '" id="' . $str_Guion_C . $obj->id . '" data-error="Before you wreck yourself"  >
                                    </label>
                                </div>
                            </div>
                            <!-- FIN DEL CAMPO SI/NO -->';
                            $str_Funciones_Js .= "\n" . '
    //function para ' . ($obj->titulo_pregunta) . ' ' . "\n" . '
    $("#' . $str_Guion_C . $obj->id . '").change(function(){
        if($(this).is(":checked")){

        }else{

        }
    });';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
                            break;



                        case '9':
                            $campo = '
                            <!-- lIBRETO O LABEL -->
                            <h3>' . ($obj->titulo_pregunta) . '</h3>
                            <!-- FIN LIBRETO -->';
                            fputs($fp, $campo);
                            fputs($fp, chr(13) . chr(10)); // Genera saldo de linea

                            break;
                        default:

                            break;
                    }//Cierro el Swich
                }//cierro el IF
            }//Cierro el Wile de campos
        }//Este es el While de secciones

        $index = '
                            <!-- SECCION : PAGINAS INCLUIDAS -->
                            <input type="hidden" name="id" id="hidId" value=\'<?php if(isset($_GET[\'u\'])){ echo $_GET[\'u\']; }else{ echo "0"; } ?>\'>
                            <input type="hidden" name="oper" id="oper" value=\'add\'>
                            <input type="hidden" name="padre" id="padre" value=\'<?php if(isset($_GET[\'yourfather\'])){ echo $_GET[\'yourfather\']; }else{ echo "0"; }?>\' >
                            <input type="hidden" name="formpadre" id="formpadre" value=\'<?php if(isset($_GET[\'formularioPadre\'])){ echo $_GET[\'formularioPadre\']; }else{ echo "0"; }?>\' >
                            <input type="hidden" name="formhijo" id="formhijo" value=\'<?php if(isset($_GET[\'formulario\'])){ echo $_GET[\'formulario\']; }else{ echo "0"; }?>\' >
                            <input type= "hidden" name="campana" id="campana" value="<?php if(isset($_GET[\'camp\'])){ echo base64_decode($_GET[\'camp\']); }else{ echo "0"; }?>">
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

        <script type="text/javascript" src="formularios/' . $str_guion . '/' . $str_guion . '_eventos.js"></script>
        <script type="text/javascript">
            $.validator.setDefaults({
                submitHandler: function() {
                     $("#formLogin").submit();
                }
            });

            $(function(){

                $.fn.datepicker.dates[\'es\'] = {
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
                ' . "\n" . $str_Select2 . '

                //datepickers
                ' . $str_FechaValidadaOno . '

                //Timepickers
                ' . "\n" . $str_HoraValidadaOno . '

                //Validaciones numeros Enteros
                ' . "\n" . $str_NumeroFuncion . '

                //Validaciones numeros Decimales
               ' . "\n" . $str_DecimalFuncion . '

               //Si tiene dependencias
               ' . "\n" . $str_Funciones_Jsx . '


               <?php
                    if(isset($_GET[\'result\'])){
                        if($_GET[\'result\'] ==  1){
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
                                text: \'Ocurrio un error, intenta mas tarde\',
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
                $campana = base64_decode($_GET[\'camp\']);
                $Guion = 0;//id de la campaña
                $tabla = 0;// $_GET[\'u\'];//ide del usuario
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
                    $DatosSql = " SELECT ".$key->CAMINC_NomCamPob_b." as campo FROM ".$BaseDatos.".G".$tabla." WHERE G".$tabla."_ConsInte__b=".$_GET[\'u\'];

                    //echo $DatosSql;
                    //recorro la tabla de donde necesito los datos
                    $resultDatosSql = $mysqli->query($DatosSql);
                    if($resultDatosSql){
                        while($objDatos = $resultDatosSql->fetch_object()){
                            if(!is_null($objDatos->campo) && $objDatos->campo != \'\'){

                                if($datos[\'PREGUN_Tipo______b\'] != \'8\'){
                            ?>
                                    document.getElementById("<?=$key->CAMINC_NomCamGui_b;?>").value = \'<?=trim($objDatos->campo);?>\';
                            <?php
                                }else{
                                    if($objDatos->campo == \'1\'){
                                        echo "$(\'#".$key->CAMINC_NomCamGui_b."\').attr(\'checked\' , true);";
                                    }else{
                                        echo "$(\'#".$key->CAMINC_NomCamGui_b."\').attr(\'checked\' , false);";
                                    }

                                }
                            }
                        }
                    }

                }
                ?>
            });
        </script>
        ';

        fputs($fp, $index);
        fputs($fp, chr(13) . chr(10)); // Genera saldo de linea
        fclose($fp);

        $nombre_fichero = $carpeta . "/" . $str_guion . "_eventos.js";
        if (!file_exists($nombre_fichero)) {
            $fjs = fopen($carpeta . "/" . $str_guion . "_eventos.js", "w");
            $nuewJs = '$(function(){' . $str_Funciones_Js . ' ' . "\n" . '});';
            $nuewJs .= "\n" . '
    function before_save(){ return true; }' . "\n" . '
    function after_save(){}' . "\n" . '
    function after_save_error(){}';
            $nuewJs .= "\n" . '
    function before_edit(){}' . "\n" . '
    function after_edit(){}';
            $nuewJs .= "\n" . '
    function before_add(){}' . "\n" . '
    function after_add(){}';
            $nuewJs .= "\n" . '
    function before_delete(){}' . "\n" . '
    function after_delete(){}' . "\n" . '
    function after_delete_error(){}';
            fputs($fjs, $nuewJs);
            fclose($fjs);
        }

        $nombre_fichero2 = $carpeta . "/" . $str_guion . "_extender_funcionalidad.php";
        if (!file_exists($nombre_fichero2)) {
            $fjss = fopen($carpeta . "/" . $str_guion . "_extender_funcionalidad.php", "w");
            $nuewJss = '<?php';
            $nuewJss .= "\n" . '
    include(__DIR__."/../../conexion.php");';
            $nuewJss .= "\n" . '
    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan';
            $nuewJss .= "\n" . '
    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos_, etc, en fin extender mas los formularios en PHP';
            $nuewJss .= "\n" . '';
            $nuewJss .= "\n" . '?>';
            fputs($fjss, $nuewJss);
            fclose($fjss);
        }

        //Este es el crud
        $fcrud = fopen($carpeta . "/" . $str_guion . "_CRUD_web.php", "w");

        //Esta consulta la hago para los campos del select
        $campos_4 = $mysqli->query($str_Lsql);
        $camposconsulta12 = '';
        $camposconsulta1 = '
		            $str_Lsql = \'SELECT ' . $str_guion . '_ConsInte__b, ' . $str_Guion_C . $GUION__ConsInte__PREGUN_Pri_b . ' as principal ';
        $camposconsulta12 = $camposconsulta1;
        $str_Joins = '';
        $alfa = 0;
        $camposGrid = '';
        $camposExcell = '';
        $horas = 0;
        while ($key = $campos_4->fetch_object()) {

            if ($key->tipo_Pregunta != 9) {
                $camposconsulta1 .= ',' . $str_Guion_C . $key->id;

                if ($key->tipo_Pregunta == '5') {
                    $camposGrid .= ', explode(\' \', $fila->' . $str_Guion_C . $key->id . ')[0] ';
                    $camposExcell .= '                           <td>\'.explode(\' \', $fila->' . $str_Guion_C . $key->id . ')[0].\'</td>' . "\n";
                } else if ($key->tipo_Pregunta == '10') {
                    $camposGrid .= ', $hora_' . $str_Alfabeto[$horas] . ' ';
                    $camposExcell .= '                          <td>\'.$hora_' . $str_Alfabeto[$horas] . '.\'</td>' . "\n";
                    $horas++;
                } else if ($key->tipo_Pregunta == '11') {
                    $CampoSql = "SELECT * FROM " . $BaseDatos_systema . ".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = " . $key->id . " ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                    $campoSqlE = $mysqli->query($CampoSql);

                    while ($cam = $campoSqlE->fetch_object()) {
                        //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                        $campoObtenidoSql = 'SELECT * FROM ' . $BaseDatos_systema . '.CAMPO_ WHERE CAMPO__ConsInte__b = ' . $cam->PREGUI_ConsInte__CAMPO__b;
                        //echo $campoObtenidoSql;
                        $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                        while ($o = $resultCamposObtenidos->fetch_object()) {
                            $camposGrid .= ', ($fila->' . $o->CAMPO__Nombre____b . ') ';
                            $camposExcell .= '                          <td>\'.($fila->' . $o->CAMPO__Nombre____b . ').\'</td>' . "\n";
                        }
                    }
                } else {
                    $camposGrid .= ', ($fila->' . $str_Guion_C . $key->id . ') ';
                    $camposExcell .= '                          <td>\'.($fila->' . $str_Guion_C . $key->id . ').\'</td>' . "\n";
                }

                if ($key->tipo_Pregunta == '6') {
                    $camposconsulta12 .= ', ' . $str_Alfabeto[$alfa] . '.LISOPC_Nombre____b as ' . $str_Guion_C . $key->id;
                    $str_Joins .= ' LEFT JOIN \'.$BaseDatos_systema.\'.LISOPC as ' . $str_Alfabeto[$alfa] . ' ON ' . $str_Alfabeto[$alfa] . '.LISOPC_ConsInte__b =  ' . $str_Guion_C . $key->id;
                    $alfa++;
                } else if ($key->tipo_Pregunta == '11') {
                    $CampoSql = "SELECT * FROM " . $BaseDatos_systema . ".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = " . $key->id . " ORDER BY PREGUI_ConsInte__b ASC LIMIT 0, 1 ";
                    $campoSqlE = $mysqli->query($CampoSql);

                    while ($cam = $campoSqlE->fetch_object()) {
                        //Consulto por el id del campo que necesitamos y se repite las veces que sean necesarias
                        $campoObtenidoSql = 'SELECT * FROM ' . $BaseDatos_systema . '.CAMPO_ WHERE CAMPO__ConsInte__b = ' . $cam->PREGUI_ConsInte__CAMPO__b;
                        //echo $campoObtenidoSql;
                        $resultCamposObtenidos = $mysqli->query($campoObtenidoSql);

                        while ($o = $resultCamposObtenidos->fetch_object()) {
                            $camposconsulta12 .= ', ' . $o->CAMPO__Nombre____b;
                        }
                    }

                    $str_Joins .= ' LEFT JOIN \'.$BaseDatos_systema.\'.G' . $key->guion . ' ON G' . $key->guion . '_ConsInte__b  =  ' . $str_Guion_C . $key->id;
                } else {
                    $camposconsulta12 .= ',' . $str_Guion_C . $key->id;
                }
            }
        }

        $camposconsulta1 .= ' FROM \'.$BaseDatos.\'.' . $str_guion;
        $camposconsulta12 .= ' FROM \'.$BaseDatos.\'.' . $str_guion;

        $camposconsulta1 .= ' WHERE ' . $str_guion . '_ConsInte__b =\'.$_POST[\'id\'];';

        $str_LsqlHora = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b FROM " . $BaseDatos_systema . ".PREGUN WHERE PREGUN_ConsInte__GUION__b = " . $int_Id_Generar . " AND PREGUN_Tipo______b = 10 ORDER BY PREGUN_OrdePreg__b";
        $esHora = $mysqli->query($str_LsqlHora);
        $variablesDeLahora = '';
        $horas = 0;
        while ($key = $esHora->fetch_object()) {
            $variablesDeLahora .= "\n" . '
                $hora_' . $str_Alfabeto[$horas] . ' = \'\';
                //esto es para todo los tipo fecha, para que no muestre la parte de la hora
                if(!is_null($fila->' . $str_Guion_C . $key->id . ')){
                    $hora_' . $str_Alfabeto[$horas] . ' = explode(\' \', $fila->' . $str_Guion_C . $key->id . ')[1];
                }';
            $horas++;
        }

        $crud = '<?php

    ini_set(\'display_errors\', \'On\');
    ini_set(\'display_errors\', 1);
    include(__DIR__."/../../conexion.php");
    date_default_timezone_set(\'America/Bogota\');

    //Inserciones o actualizaciones

    if(isset($_POST[\'getListaHija\'])){
        $Lsql = "SELECT LISOPC_ConsInte__b , LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__LISOPC_Depende_b = ".$_POST[\'idPadre\']." AND LISOPC_ConsInte__OPCION_b = ".$_POST[\'opcionID\'];
        $res = $mysqli->query($Lsql);
        echo "<option value=\'0\'>Seleccione</option>";
        while($key = $res->fetch_object()){
            echo "<option value=\'".$key->LISOPC_ConsInte__b."\'>".$key->LISOPC_Nombre____b."</option>";
        }
    }

    if(isset($_POST["oper"])){
            $str_Lsql  = \'\';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos.".' . $str_guion . ' SET ";
            $str_LsqlI = "INSERT INTO ".$BaseDatos.".' . $str_guion . '( ' . $str_guion . '_FechaInsercion ,";
            $str_LsqlV = " VALUES (\'".date(\'Y-m-d H:s:i\')."\',"; ' . "\n";

        //Esta consulta la hago para los campos del select
        $campos_7 = $mysqli->query($str_Lsql);

        while ($key = $campos_7->fetch_object()) {

            if ($key->id != $GUION__ConsInte__PREGUN_Tip_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Rep_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Fag_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Hag_b &&
                    $key->id != $GUION__ConsInte__PREGUN_Com_b) {

                $valorPordefecto = $key->PREGUN_Default___b;



                if ($key->PREGUN_ContAcce__b != 2) {
                    if ($key->tipo_Pregunta == 5) { // tipo fecha
                        $crud .= '
        $' . $str_Guion_C . $key->id . ' = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        if(isset($_POST["' . $str_Guion_C . $key->id . '"])){
            if($_POST["' . $str_Guion_C . $key->id . '"] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $' . $str_Guion_C . $key->id . ' = "\'".str_replace(\' \', \'\',$_POST["' . $str_Guion_C . $key->id . '"])." 00:00:00\'";
                $str_LsqlU .= $separador." ' . $str_Guion_C . $key->id . ' = ".$' . $str_Guion_C . $key->id . ';
                $str_LsqlI .= $separador." ' . $str_Guion_C . $key->id . '";
                $str_LsqlV .= $separador.$' . $str_Guion_C . $key->id . ';
                $validar = 1;
            }
        }' . "\n";
                    } else if ($key->tipo_Pregunta == 10) { // tipo timer
                        $crud .= '
        $' . $str_Guion_C . $key->id . ' = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if(isset($_POST["' . $str_Guion_C . $key->id . '"])){
            if($_POST["' . $str_Guion_C . $key->id . '"] != \'\' && $_POST["' . $str_Guion_C . $key->id . '"] != \'undefined\' && $_POST["' . $str_Guion_C . $key->id . '"] != \'null\'){
                $separador = "";
                $fecha = date(\'Y-m-d\');
                if($validar == 1){
                    $separador = ",";
                }

                $' . $str_Guion_C . $key->id . ' = "\'".$fecha." ".str_replace(\' \', \'\',$_POST["' . $str_Guion_C . $key->id . '"])."\'";
                $str_LsqlU .= $separador." ' . $str_Guion_C . $key->id . ' = ".$' . $str_Guion_C . $key->id . '."";
                $str_LsqlI .= $separador." ' . $str_Guion_C . $key->id . '";
                $str_LsqlV .= $separador.$' . $str_Guion_C . $key->id . ';
                $validar = 1;
            }
        }' . "\n";
                    } else if ($key->tipo_Pregunta == 3) { // tipo Entero
                        $crud .= '
        $' . $str_Guion_C . $key->id . ' = NULL;
        //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
        if(isset($_POST["' . $str_Guion_C . $key->id . '"])){
            if($_POST["' . $str_Guion_C . $key->id . '"] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$' . $str_Guion_C . $key->id . ' = $_POST["' . $str_Guion_C . $key->id . '"];
                $' . $str_Guion_C . $key->id . ' = str_replace(".", "", $_POST["' . $str_Guion_C . $key->id . '"]);
                $' . $str_Guion_C . $key->id . ' =  str_replace(",", ".", $' . $str_Guion_C . $key->id . ');
                $str_LsqlU .= $separador." ' . $str_Guion_C . $key->id . ' = \'".$' . $str_Guion_C . $key->id . '."\'";
                $str_LsqlI .= $separador." ' . $str_Guion_C . $key->id . '";
                $str_LsqlV .= $separador."\'".$' . $str_Guion_C . $key->id . '."\'";
                $validar = 1;
            }
        }' . "\n";
                    } else if ($key->tipo_Pregunta == 4) { // tipo Decimal
                        $crud .= '
        $' . $str_Guion_C . $key->id . ' = NULL;
        //este es de tipo numero no se deja ir asi \'\', si est avacio lo mejor es no mandarlo
        if(isset($_POST["' . $str_Guion_C . $key->id . '"])){
            if($_POST["' . $str_Guion_C . $key->id . '"] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }


                $' . $str_Guion_C . $key->id . ' = str_replace(".", "", $_POST["' . $str_Guion_C . $key->id . '"]);
                $' . $str_Guion_C . $key->id . ' =  str_replace(",", ".", $' . $str_Guion_C . $key->id . ');
                $str_LsqlU .= $separador." ' . $str_Guion_C . $key->id . ' = \'".$' . $str_Guion_C . $key->id . '."\'";
                $str_LsqlI .= $separador." ' . $str_Guion_C . $key->id . '";
                $str_LsqlV .= $separador."\'".$' . $str_Guion_C . $key->id . '."\'";
                $validar = 1;
            }
        }' . "\n";
                    } else if ($key->tipo_Pregunta == 8) { // tipo Check
                        $crud .= '
        $' . $str_Guion_C . $key->id . ' = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if(isset($_POST["' . $str_Guion_C . $key->id . '"])){
            if($_POST["' . $str_Guion_C . $key->id . '"] == \'Yes\'){
                $' . $str_Guion_C . $key->id . ' = 1;
            }else if($_POST["' . $str_Guion_C . $key->id . '"] == \'off\'){
                $' . $str_Guion_C . $key->id . ' = 0;
            }else if($_POST["' . $str_Guion_C . $key->id . '"] == \'on\'){
                $' . $str_Guion_C . $key->id . ' = 1;
            }else if($_POST["' . $str_Guion_C . $key->id . '"] == \'No\'){
                $' . $str_Guion_C . $key->id . ' = 0;
            }else{
                $' . $str_Guion_C . $key->id . ' = $_POST["' . $str_Guion_C . $key->id . '"] ;
            }

            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador." ' . $str_Guion_C . $key->id . ' = ".$' . $str_Guion_C . $key->id . '."";
            $str_LsqlI .= $separador." ' . $str_Guion_C . $key->id . '";
            $str_LsqlV .= $separador.$' . $str_Guion_C . $key->id . ';

            $validar = 1;
        }else{
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador." ' . $str_Guion_C . $key->id . ' = ".$' . $str_Guion_C . $key->id . '."";
            $str_LsqlI .= $separador." ' . $str_Guion_C . $key->id . '";
            $str_LsqlV .= $separador.$' . $str_Guion_C . $key->id . ';

            $validar = 1;
        }' . "\n";
                    } else { // tipos norrmales
                        $crud .= '
        if(isset($_POST["' . $str_Guion_C . $key->id . '"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."' . $str_Guion_C . $key->id . ' = \'".$_POST["' . $str_Guion_C . $key->id . '"]."\'";
            $str_LsqlI .= $separador."' . $str_Guion_C . $key->id . '";
            $str_LsqlV .= $separador."\'".$_POST["' . $str_Guion_C . $key->id . '"]."\'";
            $validar = 1;
        }
         ' . "\n";
                    }
                }
            }
        }
        $crud .= '
        $padre = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        if(isset($_POST["padre"])){
            if($_POST["padre"] != \'0\' && $_POST[\'padre\'] != \'\'){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //primero hay que ir y buscar los campos
                $str_Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST[\'formpadre\']." AND GUIDET_ConsInte__GUION__Det_b = ".$_POST[\'formhijo\'];

                $GuidRes = $mysqli->query($str_Lsql);
                $campo = null;
                while($ky = $GuidRes->fetch_object()){
                    $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                }
                $valorG = "' . $str_Guion_C . '";
                $valorH = $valorG.$campo;
                $str_LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                $str_LsqlI .= $separador." ".$valorH;
                $str_LsqlV .= $separador.$_POST[\'padre\'] ;
                $validar = 1;
            }
        }' . "\n";

        $crud .= '
        if(isset($_GET[\'id_gestion_cbx\'])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."' . $str_guion . '_IdLlamada = \'".$_GET[\'id_gestion_cbx\']."\'";
            $str_LsqlI .= $separador."' . $str_guion . '_IdLlamada";
            $str_LsqlV .= $separador."\'".$_GET[\'id_gestion_cbx\']."\'";
            $validar = 1;
        }


        if(isset($_POST[\'oper\'])){
            if($_POST["oper"] == \'add\' ){

                $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
            }
        }

        //Si trae algo que insertar inserta

        //echo $str_Lsql;
        if($validar == 1){
            if ($mysqli->query($str_Lsql) === TRUE) {
                $ultimoResgistroInsertado = $mysqli->insert_id;
                //ahora toca ver lo de la muestra asi que toca ver que pasa
                /* primero buscamos la campaña que nos esta llegando */
                $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_ActPobGui_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST["campana"];

                //echo $Lsql_Campan;

                $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
                $datoCampan = $res_Lsql_Campan->fetch_array();
                $str_Pobla_Campan = "G".$datoCampan[\'CAMPAN_ConsInte__GUION__Pob_b\'];
                $int_Pobla_Camp_2 = $datoCampan[\'CAMPAN_ConsInte__GUION__Pob_b\'];
                $int_Muest_Campan = $datoCampan[\'CAMPAN_ConsInte__MUESTR_b\'];
                $int_Guion_Campan = $datoCampan[\'CAMPAN_ConsInte__GUION__Gui_b\'];
                $int_CAMPAN_ActPo = $datoCampan[\'CAMPAN_ActPobGui_b\'];


                if($int_CAMPAN_ActPo == \'-1\'){
                    /* toca hacer actualizacion desde Script */

                    $campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$_POST["campana"];
                    $resultcampSql = $mysqli->query($campSql);
                    $Lsql = \'UPDATE \'.$BaseDatos.\'.\'.$str_Pobla_Campan.\' , \'.$BaseDatos.\'.G\'.$int_Guion_Campan.\' SET \';
                    $i=0;
                    while($key = $resultcampSql->fetch_object()){

                        if($i == 0){
                            $Lsql .= $key->CAMINC_NomCamPob_b . \' = \'.$key->CAMINC_NomCamGui_b;
                        }else{
                            $Lsql .= " , ".$key->CAMINC_NomCamPob_b . \' = \'.$key->CAMINC_NomCamGui_b;
                        }
                        $i++;
                    }
                    $Lsql .= \' WHERE  G\'.$int_Guion_Campan.\'_ConsInte__b = \'.$ultimoResgistroInsertado.\' AND G\'.$int_Guion_Campan.\'_CodigoMiembro = \'.$str_Pobla_Campan.\'_ConsInte__b\';
                    //echo "Esta ".$Lsql;
                    if($mysqli->query($Lsql) === TRUE ){

                    }else{
                        echo "NO sE ACTALIZO LA BASE DE DATOS ".$mysqli->error;
                    }
                }


                //Ahora toca actualizar la muestra
                $MuestraSql = "UPDATE ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." SET
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_Estado____b = 3,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b + 1,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_UltiGest__b = \'-9\' ,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_ConUltGes_b = 7,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecUltGes_b = \'".date(\'Y-m-d H:i:s\')."\',
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_EfeUltGes_b = 3,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_Comentari_b = \'No desea participar\',
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorAge_b = NULL,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_GesMasImp_b = \'-9\',
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoGesMaIm_b = 7,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_FeGeMaIm__b =\'".date(\'Y-m-d H:i:s\')."\'";
                $MuestraSql .= " WHERE ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b = ".$_POST[\'id\'];
                // echo $MuestraSql;
                if($mysqli->query($MuestraSql) === true){

                }else{
                    echo "Error insertando la muesta => ".$mysqli->error;
                }

                header(\'Location:http://\'.$_SERVER[\'HTTP_HOST\'].\'/crm_php/web_forms.php?web=' . base64_encode($int_Id_Generar) . '&result=1\');

            } else {
                echo "Error Hacieno el proceso los registros : " . $mysqli->error;
            }
        }
    }



?>
';

        fputs($fcrud, $crud);
        fclose($fcrud);
    } else {
        echo "No se a recibido nada";
    }
}