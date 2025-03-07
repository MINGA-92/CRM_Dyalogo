
<link href="<?= base_url ?>assets/plugins/toastr/build/toastr.min.css" rel="stylesheet">

<div class="content-wrapper">

    <form id="form-huesped">
        <input hidden type="" name="id" id="id">
    </form>

    <section class="content-header">
        <h1> <?php echo  $str_canales_comunicacion; ?> </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard" aria-hidden="true"></i> Home</a></li>
            <li><?php echo  $str_canales_comunicacion; ?></li>
        </ol>
    </section>

    <div class="content">

        <!-- SECCION DE VOZ -->
        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseVoz" aria-expanded="false" class="collapsed">
                        VOZ
                    </a>
                </h4>
            </div>
            <div id="collapseVoz" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="box-body">
                    <h4 class="text-aqua">Troncales</h4>
                    <?php include('secciones/troncales.php') ?>
                    <br>
                    <hr>
                    <h4 class="text-aqua">Tipos de destinos a los que va a marcar</h4>
                    <?php include('secciones/patrones.php') ?>
                </div>
            </div>
        </div>


        <!-- SECCION DE EMAIL -->
        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseEmail" aria-expanded="false" class="collapsed">
                        EMAIL
                    </a>
                </h4>
            </div>
            <div id="collapseEmail" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="box-body">
                    <?php include('secciones/cuentasCorreo.php') ?>
                </div>
            </div>
        </div>


        <!-- SECCION DE SMS -->
        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseSMS" aria-expanded="false" class="collapsed">
                        SMS
                    </a>
                </h4>
            </div>
            <div id="collapseSMS" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="box-body">
                    <?php include('secciones/proveedoresSms.php') ?>
                </div>
            </div>
        </div>


        <!-- SECCION DE WEBSERVICE -->
        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseWebService" aria-expanded="false" class="collapsed">
                        WEB SERVICE
                    </a>
                </h4>
            </div>
            <div id="collapseWebService" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="box-body">
                    <?php include('secciones/webservice.php') ?>
                </div>
            </div>
        </div>


        <!-- SECCION DE WHATSAPP -->
        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseWhatsapp" aria-expanded="false" class="collapsed">
                        WHATSAPP
                    </a>
                </h4>
            </div>
            <div id="collapseWhatsapp" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="box-body">
                    <h4 class="text-aqua">Cuentas de whatsapp</h4>
                    <?php include('secciones/whatsapp.php') ?>
                    <br>
                    <hr>
                    <h4 class="text-aqua">Plantillas de whatsapp</h4>
                    <?php include('secciones/whatsappPlantillas.php') ?>
                </div>
            </div>
        </div>

        <!-- SECCION DE FACEBOOK -->
        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsefacebook" aria-expanded="false" class="collapsed">
                        FACEBOOK MESSENGER
                    </a>
                </h4>
            </div>
            <div id="collapsefacebook" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="box-body">
                    <?php include('secciones/facebook.php') ?>
                </div>
            </div>
        </div>


        <!-- SECCION DE INSTAGRAM -->
        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseinstagram" aria-expanded="false" class="collapsed">
                        INSTAGRAM
                    </a>
                </h4>
            </div>
            <div id="collapseinstagram" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="box-body">
                    <?php include('secciones/instagram.php') ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= base_url ?>assets/plugins/toastr/build/toastr.min.js"></script>
<script src="<?= base_url ?>assets/plugins/sweetalert2/dist/sweetalert2.all.js"></script>

<?php include(__DIR__ . "/js/channelsjs.php"); ?>