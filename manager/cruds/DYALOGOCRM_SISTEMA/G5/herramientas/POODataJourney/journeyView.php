<?php
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once(__DIR__ . '/../../../../../../helpers/parameters.php');
include(__DIR__ . '/../../../../../idioma.php');
?>

<!DOCTYPE html>
<html>

<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=ISO-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $str_TituloSistema; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url ?>assets/bootstrap/css/bootstrap.min.css" />
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/4207f392f9.js" crossorigin="anonymous"></script>
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url ?>assets/css/AdminLTE.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?= base_url ?>assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css" />
    <script src="<?= base_url ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?= base_url ?>assets/js/moment.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <link rel="shortcut icon" href="<?= base_url ?>assets/img/logo_dyalogo_mail.png">

    <style>
        .content-wrapper {
            margin-left: unset;
        }

        .title-icon {
            color: #999;
            padding: 0.4rem;
            font-size: 1.4rem;
        }


        .icon-step {
            background-color: var(--colorStep) !important;
            width: 40px;
            height: 40px;
            font-size: 15px;
            line-height: 40px;
            position: absolute;
            color: #ffffff;
            background: #d2d6de;
            border-radius: 50%;
            text-align: center;
            left: 15px;
            top: 0;
            font-style: normal;
        }

        .icon-step::before {
            font-family: var(--fontName);
            font-weight: var(--fontWeigh);
            content: attr(data-content);

        }

        .timeline:before {
            left: 33px;
        }
    </style>
</head>

<body>


    <div class="content-wrapper">
        <section class="content-header">
            <h1 id="tittle">
                JOURNEY
            </h1>
        </section>
        <section class="content">
            <ul class="timeline" id="timeline-body">

            </ul>
        </section>
    </div>



    <!-- Bootstrap 3.3.6 -->
    <script src="<?= base_url ?>assets/bootstrap/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    <script src="<?= base_url ?>assets/js/app.min.js"></script>

    <!-- Configuracion de iconos y nombre de los pasos -->
    <script src="<?= base_url ?>cruds/DYALOGOCRM_SISTEMA/G2/configFlujograma.js"></script>

    <script>
        $(function() {
            $.ajax({
                url: "<?= base_url ?>cruds/DYALOGOCRM_SISTEMA/G5/herramientas/POODataJourney/?method=getData",
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                data: JSON.stringify({
                    "idG": <?=$_GET["idG"]?>,
                    "idUser": <?=$_GET["idUser"]?>,
                    "from": "<?=(isset($_GET["from"])) ? $_GET["from"] : null ?>"
                }),
                success: function(response) {
                    if(response.status == "error"){
                        $("#timeline-body").append(`<p class="text-center">No hay informacion para mostrar</p>`)
                    }else{
                        createTimeLineItem(response);
                    }
                }
            });
        });


        function createTimeLineItem(data) {

            $("#tittle").html(`JOURNEY DEL REGISTRO ${data.infoTittle.PRI} - ${data.infoTittle.SEC}`);

            let html = "";
            $.each(data.message, function(indexInArray, valueOfElement) {

                let icon = getIconStep(parseInt(valueOfElement.pasoInfo.tipo));
                let agent = (valueOfElement.agente != null) ? `<span class="time"><i class="fas fa-user"></i> ${valueOfElement.agente}</span>` : ``;
                let link = (valueOfElement.link != null) ? valueOfElement.link : "";
                link = (link.match(/^https:\/\/[A-Za-z0-9\-]+\.dyalogo\.cloud.*/gm)) ? `<a class="btn btn-info" href="${link}" target="_blank" ><i class="fas fa-download"></i>  Grabacion</a>` : "";
                let sentido = "";
                if(valueOfElement.sentido != null){
                    sentido = (valueOfElement.sentido == "Saliente") ? '<span class="title-icon"><i data-toggle="tooltip" data-placement="bottom" title="Sentido Saliente" class="fa fa-sign-out"></i></span>' : '<span class="title-icon"><i data-toggle="tooltip" data-placement="bottom" title="Sentido Entrante" class="fa fa-sign-in"></i></span>';
                }

                let title = (valueOfElement.tipificacion != null ) ? normalizeString(valueOfElement.tipificacion) : "Vacío";
                let nameStep = (valueOfElement.pasoInfo.nombre != null ) ? valueOfElement.pasoInfo.nombre : "Vacío";
                nameStep = (parseInt(valueOfElement.pasoInfo.tipo) != 5 ) ? nameStep : "Cargador";
                let clasificacion = (valueOfElement.clasificacion != null ) ? normalizeString(valueOfElement.clasificacion) : "Vacío";
                let reintento = (valueOfElement.reintento != null ) ? normalizeString(valueOfElement.reintento) : "Vacío";
                let datoContacto = (valueOfElement.datoContacto != null ) ? valueOfElement.datoContacto : "Vacío";
                let comentario = (valueOfElement.comentario != null ) ? valueOfElement.comentario : "Vacío";
                

                if(valueOfElement.canal == 'cargador'){
                    icon = getIconStep(5);
                }

                html += `
                    <li>
                        <!-- timeline icon -->
                        <i class="icon-step" id="iconStep" data-content="${icon.icon}" style="--colorStep: ${icon.color}; --fontWeigh: ${icon.fontWeigh}; --fontName: ${icon.fontName}"></i>
                        <div class="timeline-item">
                            <!-- ICONOS DE FECHA Y HORA -->

                            <span class="time"><i class="fa fa-clock-o"></i> ${valueOfElement.hora}</span>
                            <span class="time"><i class="fas fa-calendar-alt"></i> ${valueOfElement.fecha}</span>
                            ${agent}



                            <h3 class="timeline-header no-border">
                                ${sentido}
                                <a data-toggle="collapse" data-parent="#accordion" href="#jurneyid_${indexInArray}" class="collapsed" aria-expanded="false">${title}</a>
                            </h3>



                            <div id="jurneyid_${indexInArray}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class=" timeline-body box-body" style="padding-left: 2rem; padding-bottom: 2rem;">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <p><b>Nombre Paso: </b> ${nameStep}  </p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><b>Clasificacion: </b> ${clasificacion}  </p> 
                                        </div>
                                        <div class="col-md-2">
                                            <p><b>Tipo de Reintento: </b> ${reintento} </p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><b>Dato de Contacto: </b> ${datoContacto}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                        <p><b>Comentarios: </b> ${comentario}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            ${link}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </li>
                `;
            });
            $("#timeline-body").html("");
            $("#timeline-body").append(html);


            $('[data-toggle="tooltip"]').tooltip()

        }

        // ESTA FUNCION ME TRAE EL COLOR Y EL ICONO DEPENDIENDO DE TIPO DE PASO
        function getIconStep(tipoPaso) {
            let color = "";
            let icon = "";
            let fontWeigh = 400;
            let fontName = "'Font Awesome 5 Free'";
            
            //SWITCH PARA ICONOS
            switch (tipoPaso) {
                case 4: case 5: case 10: case 11: case 21:
                    switch (tipoPaso) {
                        // ESTE SWITCH TRAE LOS ICONOS DE LOS PASOS VERDES

                        case 4:
                            icon = config.iconos.webform;
                            break;
                        case 5:
                            icon = config.iconos.cargador;
                            break;
                        case 10:
                            icon = config.iconos.correoEntrante;
                            break;
                        case 11:
                            icon = config.iconos.webservice;
                            fontWeigh = 900;
                            break;
                        case 21:
                            icon = config.iconos.cagueManual;
                            fontWeigh = 900;
                            break;
                    }
                    color = config.colores.green;
                    break;


                case 14: case 15: case 16: case 20: case 17: case 18: case 19:
                    switch (tipoPaso) {
                        // ESTE SWITCH TRAE LOS ICONOS DE LOS PASOS AMARILLOS
                        case 14:
                            icon = config.iconos.chatWeb;
                            break;
                        case 15:
                            icon = config.iconos.whatsapp;
                            fontWeigh = 400;
                            fontName = "'Font Awesome 5 Brands'";
                            break;
                        case 16:
                            icon = config.iconos.facebook;
                            fontWeigh = 400;
                            fontName = "'Font Awesome 5 Brands'";
                            break;
                        case 20:
                            icon = config.iconos.instagram;
                            fontWeigh = 400;
                            fontName = "'Font Awesome 5 Brands'";
                            break;
                        case 17:
                            icon = config.iconos.correoEntrante;
                            break;
                        case 18:
                            icon = config.iconos.smsEntrante;
                            fontWeigh = 900;
                            break;
                        case 19:
                            icon = config.iconos.webform;
                            break;

                    }
                    color = config.colores.yellow;
                    break;


                case 1: case 6: case 9:
                    switch (tipoPaso) {
                        // ESTE SWITCH TRAE LOS ICONOS DE LOS PASOS NARANJAS
                        case 1:
                            icon = config.iconos.campanaEntrante;
                            fontWeigh = 900;
                            break;
                        case 6:
                            icon = config.iconos.campanaSaliente;
                            fontWeigh = 900;
                            break;
                        case 9:
                            icon = config.iconos.backoffice;
                            break;

                    }
                    color = config.colores.orange;
                    break;



                case 7: case 8: case 13:
                    switch (tipoPaso) {
                        // ESTE SWITCH TRAE LOS ICONOS DE LOS PASOS ROJOS
                        case 7:
                            icon = config.iconos.correoSaliente;
                            break;
                        case 8:
                            icon = config.iconos.smsSaliete;
                            fontWeigh = 900;
                            break;
                        case 13:
                            icon = config.iconos.whatsapp;
                            fontWeigh = 400;
                            fontName = "'Font Awesome 5 Brands'";
                            break;

                    }
                    color = config.colores.red;
                    break;

                case 12:
                    icon = config.iconos.bot;
                    color = config.colores.blue;
                    fontWeigh = 900;
                    break;
            }

            return {
                color,
                icon,
                fontWeigh,
                fontName
            };
        }


        function normalizeString(str){
            let str2 = str.toLowerCase();
            str2 = str2.charAt(0).toUpperCase() + str2.slice(1);
            return str2;
        }
    </script>
</body>