<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dyalogo CRM</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../assets/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../assets/ionicons-master/css/ionicons.min.css">


    <!-- Theme style -->
    <link rel="stylesheet" href="../../assets/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../assets/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../../assets/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="../../assets/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="../../assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="../../assets/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../../assets/plugins/daterangepicker/daterangepicker.css">
        <!-- Bootstrap time Picker -->
            <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="../../assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="../../assets/css/alertify.core.css">

    <link rel="stylesheet" href="../../assets/css/alertify.default.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../../assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css" />
    <link rel="stylesheet" href="../../assets/plugins/select2/select2.min.css" />
    <link rel="stylesheet" href="../../assets/plugins/sweetalert/sweetalert.css" />
    <script src="../../assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="../../assets/plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../../assets/plugins/BlockUi/jquery.blockUI.js"></script>
    <script src="../../assets/js/alertify.js"></script>
    <script type="text/javascript" src="../../assets/plugins/select2/select2.min.js" ></script>
    
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap.min.css">
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap.min.js"></script> -->

    <style type="text/css">
        [class^='select2'] {
            border-radius: 0px !important;
        }

        .modal-lg {
            width: 1200px;
        }

        .embed-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .embed-container iframe {
            position: absolute;
            top:0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content-wrapper" style="margin:auto">
            <section class="content">

                <div class="panel box box-primary" >
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#s_3885">
                                FILTROS
                            </a>
                        </h4>
                    </div>
                
                    <div class="panel-collapse collapse in">
                        <div class="box-body">
                            <div class="row">
                                
                                <div class="col-md-3 col-xs-3">
                                    <div class="form-group">
                                        <label for="tipo">Tipo de Recurso</label>
                                        <select class="form-control input-sm select"  style="width: 100%;" name="tipo" id="tipo" disabled>
                                            <option value=''>Todos los tipos de recurso</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-xs-3">
                                    <div class="form-group">
                                        <label for="ubicacion">Ubicación de Recurso</label>
                                        <select class="form-control input-sm select"  style="width: 100%;" name="ubicacion" id="ubicacion" disabled>
                                            <option value=''>Todas las ubicaciones de recurso</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-3">
                                    <div class="form-group">
                                        <label for="recurso">Recurso</label>
                                        <select class="form-control input-sm select"  style="width: 100%;" name="recurso" id="recurso" disabled>
                                            <option value=''>Todos los recursos</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-xs-3">
                                    <div class="form-group">
                                        <label for="ubicacion" style="color: white;">.</label>
                                        <button id="enviar" class="btn btn-primary form-control btn-sm" disabled>BUSCAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel box box-primary" >
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#s_3885">
                                AGENDAS
                            </a>
                        </h4>
                    </div>
                
                    <div class="panel-collapse collapse in">
                        <div class="box-body">
                            <table id="grid" class="table table-striped table-bordered dataTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Recurso</th>
                                        <th>Tipo de recurso</th>
                                        <th>Ubicación Recurso</th>
                                        <th>Notas</th>
                                        <th>Agendar</th>
                                    </tr>
                                </thead>
                                <tbody id="listaCitas">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <button class="btn btn-success form-control" id="save" disabled>Guardar</button>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-6">
                        <div class="form-group">
                            <button class="btn form-control" style="background: gray;color:white;" id="cancel">Cancelar</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group alertas">

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
<?php include_once('agendadorEventos.php') ?>