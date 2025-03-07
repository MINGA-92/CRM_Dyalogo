<link href="<?= base_url ?>assets/plugins/toastr/build/toastr.min.css" rel="stylesheet">


<div class="content-wrapper">


    <section class="content-header">
        <h1> <?php echo $str_admin_customers; ?> </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard" aria-hidden="true"></i> Home</a></li>
            <li><?php echo $str_admin_customers; ?></li>
        </ol>
    </section>

    <div class="content">

        <form id="form-huesped">
            <input hidden type="" name="id" id="id">
        </form>

        <!-- SECCION DE DATOS DE HUESPED -->


        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseHuesped" id="collapseButtonHuesped">
                        DATOS DEL HUESPED
                    </a>
                </h4>
            </div>
            <?php if ($_SESSION['CARGO'] == "owner" || $_SESSION['CARGO'] == "super-administrador") : ?>
                <div id="collapseHuesped" class="panel-collapse collapse">
                    <div class="box-body" style=" overflow:hidden; min-height: 100vh">
                        <iframe id="iframeAdmin" src="https://autoprovisionamiento.cr.dyalogo.cloud?token=<?= $_SESSION['JWTTOKEN'] ?>" style="width: 100%;height: 100%; position:absolute;" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                    </div>
                </div>
            <?php endif; ?>

        </div>



        <!-- SECCION DE DATOS DE MALLAS DE TURNOS -->


        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsePausas">
                        MALLA DE TURNOS
                    </a>
                </h4>
            </div>
            <div id="collapsePausas" class="panel-collapse collapse">
                <div class="box-body">
                    <!-- Se incluye el formulario del huesped debido a que el backend requiere toda la informacion del huesped a actualizar -->
                    <?php // include('secciones/huespedForm.php'); 
                    ?>
                    <?php include('secciones/mallaTurnos.php'); ?>
                </div>
            </div>
        </div>


        <!-- SECCION DE DATOS DE FESTIVOS -->


        <div class="panel box box-primary e-collapse">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFestivos">
                        FESTIVOS Y DÍAS DE NO DISPONIBILIDAD
                    </a>
                </h4>
            </div>
            <div id="collapseFestivos" class="panel-collapse collapse">
                <div class="box-body">
                    <?php include('secciones/festivos.php'); ?>
                </div>
            </div>
        </div>


        <!-- SECCION DE DATOS DE NOTIFICACIONES -->

        <div class="panel box box-primary">
            <div class="box-header with-border contacto-header">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseNotificaciones">
                        NOTIFICACIONES
                    </a>
                </h4>
            </div>
            <div id="collapseNotificaciones" class="panel-collapse collapse">
                <div class="box-body">
                    <?php include('secciones/notificaciones.php'); ?>
                    <?php include('modals/pruebaNotificacionesMailSmsModal.php'); ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= base_url ?>assets/plugins/toastr/build/toastr.min.js"></script>
<script src="<?= base_url ?>assets/plugins/sweetalert2/dist/sweetalert2.all.js"></script>
<?php include('js/generalConfigsJs.php'); ?>