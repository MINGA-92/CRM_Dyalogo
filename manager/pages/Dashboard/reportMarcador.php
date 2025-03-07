<?php


session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../../helpers/parameters.php');
require_once('../conexion.php');
include (__DIR__.'/../../idioma.php');


if (isset($_GET["logMarcador"])) {
    $statusMarcador = getStatusMarcador($_GET["logMarcador"], true);
    echo json_encode($statusMarcador);
} else if (isset($_GET["Campan"]) && isset($_GET["Estpas"]) && isset($_GET["tipoMarc"])) {

    $tabSelected = (isset($_GET["tab"])) ? $_GET["tab"] : "Analisis";
    $intCampan_t = $_GET["Campan"];
    $intEstpas_t = $_GET["Estpas"];
    $intIdBd_t = 0;
    $intIdMuestra_t = 0;

    // Se obtiene la info de campan para traer el ordenamiento
    $strSqlCampanInfo = "SELECT CAMPAN_ConsInte__GUION__Pob_b AS poblacion, CAMPAN_ConsInte__MUESTR_b AS muestra FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b = '{$intCampan_t}'";
    $resSqlCampanInfo = $mysqli->query($strSqlCampanInfo);
    if($resSqlCampanInfo && $resSqlCampanInfo->num_rows > 0){
        if($CampanInfoObj = $resSqlCampanInfo->fetch_object()){
            $intIdBd_t = $CampanInfoObj->poblacion;
            $intIdMuestra_t = $CampanInfoObj->muestra;
        }
    }


    if(isset($_GET["tipoMarc"]) && $_GET["tipoMarc"] == 6 ||$_GET["tipoMarc"] == 7  ||$_GET["tipoMarc"] == 8){
        $cola = getCola($intEstpas_t, $intCampan_t);
        $statusMarcador = getStatusMarcador($cola["intIdMarcador_t"]);
    }


?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reporte de Marcador</title>
        <link rel="stylesheet" href="<?= base_url ?>assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url ?>assets/css/AdminLTE.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?= base_url ?>assets/Guriddo_jqGrid_/css/ui.jqgrid-bootstrap.css">
        <link rel="stylesheet" href="<?= base_url ?>assets/css/alertify.core.css"/>
        <link rel="stylesheet" href="<?= base_url ?>assets/css/alertify.default.css"/>
        <link rel="stylesheet" href="<?= base_url ?>assets/font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="<?= base_url ?>assets/ionicons-master/css/ionicons.min.css"/>

    <!-- JDBD - Librerias para Higcharts -->
        <script src="<?= base_url ?>assets/HighCharts/highcharts.js"></script>
        <script src="<?= base_url ?>assets/HighCharts/highcharts-3d.js"></script>
        <script src="<?= base_url ?>assets/HighCharts/modules/cylinder.js"></script>
        <script src="<?= base_url ?>assets/HighCharts/modules/funnel3d.js"></script>
        <script src="<?= base_url ?>assets/HighCharts/modules/exporting.js"></script>
        <script src="<?= base_url ?>assets/HighCharts/modules/export-data.js"></script>
        <script src="<?= base_url ?>assets/HighCharts/modules/accessibility.js"></script>
        <script src="<?= base_url ?>assets/js/alertify.js"></script>
        <script src="<?= base_url ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="<?= base_url ?>assets/js/blockUi.js"></script>
        <script src="https://kit.fontawesome.com/4207f392f9.js" crossorigin="anonymous"></script>
    </head>

    <body>

      <input type="text"  id="idCampanInput" value="<?=$intCampan_t?>" hidden>
      <input type="text"  id="intIdBdInput" value="<?=$intIdBd_t?>" hidden>
      <input type="text"  id="intIdMuestraInput" value="<?=$intIdMuestra_t?>" hidden>

        <div>
            <ul class="nav nav-tabs">
                <li id="analisisTab" <?=($tabSelected == "Analisis")?'class="active"':""?>>
                    <a href="#analisis" data-toggle="tab" aria-expanded="false">ANALISIS</a>
                </li>
                <li id="ordenamientoTab" <?=($tabSelected == "Ordenamiento")?'class="active"':""?>>
                    <a href="#ordenamiento" data-toggle="tab" aria-expanded="false">ORDENAMIENTO</a>
                </li>
                <?php if (isset($_GET["tipoMarc"]) && $_GET["tipoMarc"] == 6 ||$_GET["tipoMarc"] == 7 ||$_GET["tipoMarc"] == 8 ) {?>
                <li id="marcadorTab">
                    <a href="#marcador" data-toggle="tab" aria-expanded="false">ACTIVIDAD MARCADOR</a>
                </li>
                <?php } ?>

            </ul>
        </div>

        <!-- tab grafico de barras -->
        <div class="tab-content">
            <div class="tab-pane <?=($tabSelected == "Analisis")?'active':""?>" style="overflow-x: scroll;" id="analisis">

                <div class="row">
                    <div class="col-md-12">

                        <figure class="highcharts-figure">
                            <div id="container"></div>
                            <p class="highcharts-description"><div style="width:30px;border-radius:50%;background:#0DB2FF"></div>Los grupos de registros en azul, son los que se van a gestionar.</p>
                            <p class="highcharts-description">Los grupos de registros en amarillo, solo se van a gestionar cuando se cumpla la fecha y hora programada para la gestión.</p>
                            <p class="highcharts-description">Los grupos de registros en rojo NO se van a gestionar, a menos que se ejecuten las acciones que se sugieren al poner el mouse sobre cada uno.</p>
                            <h3>Puede descargar un informe en <strong>Excel</strong> dando <strong>CLICK</strong> en alguna <strong>Columna</strong> de las métricas.</h3><br>
                        </figure>

                    </div>

                    <div class="col-md-12">

                        <figure class="highcharts-figure">
                            <div id="noMarcables"></div>
                            <p class="highcharts-description">
                            </p>
                        </figure>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                    </div>

                </div>

            </div>


            <div class="tab-pane <?=($tabSelected == "Ordenamiento")?'active':""?>" style="overflow-x: scroll;" id="ordenamiento">
            
            </div>

            <!-- tab analisis de marcador predictivo -->

            <?php if (isset($_GET["tipoMarc"]) && $_GET["tipoMarc"] == 6 ||$_GET["tipoMarc"] == 7 ||$_GET["tipoMarc"] == 8 ) {?>

                <div class="tab-pane" style="overflow-x: scroll;" id="marcador">
                    <!-- Seccion para el reporte del estado del marcador -->


                    <div class="panel box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#s_estado" aria-expanded="true">Estado del marcador</a>
                            </h3>
                            <div class="box-tools pull-right">
                                <span data-toggle="tooltip" class="badge bg-light-blue">Actualizacion Automatica</span>
                                <button class="btn btn-box-tool" data-toggle="tooltip" id="btnAutoRefreshLog" state="refresh"><i class="fas fa-pause"></i></button>
                            </div>
                        </div>
                        <div id="s_estado" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="box-body table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID_INTERNO</th>
                                            <th>NOMBRE</th>
                                            <th>COLA_ACD</th>
                                            <th>TIEMPO_TIMBRE</th>
                                            <th>ESTADO</th>
                                            <th>TIPO</th>
                                            <th>ACELERACION</th>
                                            <th>LOG</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td><?php echo $statusMarcador->id_interno ?></td>
                                        <td><?php echo $statusMarcador->nombre ?></td>
                                        <td><?php echo $statusMarcador->acd ?></td>
                                        <td><?php echo $statusMarcador->tiempo_timbre ?></td>
                                        <td><?php echo $statusMarcador->activa == -1 ? "Activo" : "Inactivo" ?></td>
                                        <td><?php echo translateMarcador($statusMarcador->tipo) ?></td>
                                        <td><?php echo $statusMarcador->aceleracion ?></td>
                                        <td id="idTableLog"><?php echo $statusMarcador->log ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>


                    <!-- Seccion para el reporte del actividad del marcador -->
                    <div class="panel box box-primary box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#s_resultados" aria-expanded="true">Reporte de actividad del marcador</a>
                            </h3>
                            <div class="box-tools pull-right">
                                <span data-toggle="tooltip" class="badge bg-light-blue">Actualizacion Automatica</span>
                                <button class="btn btn-box-tool" data-toggle="tooltip" id="btnAutoRefreshReport" state="refresh"><i class="fas fa-pause"></i></button>
                            </div>
                        </div>

                        <div id="s_resultados" class="panel-collapse collapse in" aria-expanded="true">

                        </div>
                    </div>

                </div>
            <?php } ?>

        </div>




        

        <!-- script para el grafico de barras del marcado -->
        <script type="text/javascript">


            var intEstpas = '<?php echo $_GET["Estpas"]; ?>';

            var strJSONInfoCampan_t = $.ajax({
                                                url: '<?= base_url ?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
                                                type:'post',
                                                dataType : 'json',
                                                data : {JSONInfoCampan : true, idPaso_t : intEstpas, idAgente_t : 0},
                                                global:false,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            var arrJSONInfoCampan_t = JSON.parse(strJSONInfoCampan_t);

            var arrNoMarcables_t = {
                chart: {
                    type: 'column',
                    options3d: {
                        enabled: true,
                        alpha: 10,
                        beta: 15,
                        depth: 70
                    }
                },
                title: {
                    text: 'Cantidad por clasificación de registros marcables y no marcables.'
                },

                subtitle: {
                    text: 'Total : '
                },
                plotOptions: {
            series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {

                                alertifyDescarga(this.y,this.x);

                            }
                        }
                    }
                },
                    column:{
                        colorByPoint: true,
                    colors: [
                    '#FF2D00','#FF6848','#FF846A','#FFA38F','#FFCE33','#FFDA65','#0084C2','#0DB2FF','#00B7FB'
                    ]
                    }
                },
                tooltip : {
                    formatter : function(){

                        return consejoInstructivo(this.point.index,this.color);
                        
                    }
                },
                yAxis: {
                title:{
                    text: 'Cantidad'
                }
                },
                xAxis: {
                    categories: ['Telefonos errados','No reintentar','Superan el limite de intentos','Registros inactivos','Reintentos automaticos a futuro','Agendas a futuro','Sin gestion','Reintentos automáticos','Agendas vencidas'],
                    labels: {
                        rotation: 22,
                        style: {
                            fontSize: '9pt',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },

                series: [{
                    type: 'column',
                    colorByPoint: true,
                    data: [0,0,0,0,0,0,0,0,0],
                    dataLabels: {
                        enabled: true,
                        rotation: 0,
                        color: '#FFFFFF',
                        align: 'right',
                        format: '{point.y:,.0f}', // one decimal
                        y: 10, // 10 pixels down from the top
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    },
                    showInLegend: false
                }]

            };

            var arrData_t = [0,0,0,0,0,0,0,0];


            var arrJSONData_t = JSON.parse($.ajax({
                                url: '<?= base_url ?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
                                type:'post',
                                dataType : 'json',
                                data : {bd : arrJSONInfoCampan_t.bd, dt : arrJSONInfoCampan_t.dt, ic : arrJSONInfoCampan_t.ic, lr : arrJSONInfoCampan_t.lr, mt : arrJSONInfoCampan_t.mt, arrTl : arrJSONInfoCampan_t.arrTl, NRGXP : arrJSONInfoCampan_t.NRGXP, metricaGraf : true},
                                global:false,
                                async:false,
                                success:function(data){
                                    return data;
                                }
                            }).responseText);

            var suma = 0;
            $.each (arrJSONData_t, function(i,numero){
                suma = suma+numero;
            });

            arrNoMarcables_t.subtitle.text='Total : '+suma;
            arrNoMarcables_t.series[0].data=arrJSONData_t;

            Highcharts.chart('container', arrNoMarcables_t);

            function exportarMetrica(intIdMetrica){

                window.location = '<?= base_url ?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?intIdMetrica='+intIdMetrica+'&arrTl='+JSON.stringify(arrJSONInfoCampan_t.arrTl)+'&mt='+arrJSONInfoCampan_t.mt+'&lr='+arrJSONInfoCampan_t.lr+'&ic='+arrJSONInfoCampan_t.ic+'&dt='+arrJSONInfoCampan_t.dt+'&bd='+arrJSONInfoCampan_t.bd+'&NRGXP='+JSON.stringify(arrJSONInfoCampan_t.NRGXP);

            }

            function alertifyDescarga(intCantidadRegistros_p,intIdMetrica_p){

                if (intCantidadRegistros_p <= 0) {

                    alertify.error('No hay registros.');

                }else if (intCantidadRegistros_p <= 5000) {

                    alertify.dyalogo('Espere mientras generamos el Excell.');
                    
                }else if (intCantidadRegistros_p <= 15000) {

                    alertify.success('Espere mientras generamos el Excell.');
                    
                }else if (intCantidadRegistros_p <= 35000) {

                    alertify.warning('Supera los 15.000 registros, por favor espere.');

                }else{

                    alertify.log('Supera los 35.000 registros, podria haber problemas para descargar, por favor ESPERE...');

                }

                if (intCantidadRegistros_p > 0) {

                    exportarMetrica(intIdMetrica_p);

                }

            }

            function consejoInstructivo(intIdMetrica_p,strColor_p){

                switch(intIdMetrica_p){

                    case 0:

                        return '<span style="color:'+strColor_p+'">● </span><p>Puede realizar nuevamente el cargue de los números telefónicos de los registros, después de asegurarse de que los números sean correctos.</p>';
                        
                        break;

                    case 1:

                        
                        return '<span style="color:'+strColor_p+'">● </span><p>Puede cambiar el estado de los registros a RE INTENTO-AUTOMATICO en el administrador de registros para que vuelvan a ser marcados nuevamente.</p>';
                        break;
                        
                    case 2:

                        
                        return '<span style="color:'+strColor_p+'">● </span><p>Los registros superaron el limite de re intentos definido en los ajustes de la campaña, puede aumentar el limite de intentos en el administrador de registros para que los registros puedan ser marcados nuevamente.</p>';
                        break;
                        
                    case 3:

                        return '<span style="color:'+strColor_p+'">● </span><p>Puede reactivar los registros desde la opción administrar registros que encuentra abriendo la estrategia y dando doble click sobre el paso de la campaña saliente.</p>';
                        
                        break;
                        
                    case 4:

                        return '<span style="color:'+strColor_p+'">● </span><p>Se llamaran tan pronto cumplan el tiempo definido para el siguiente intento.</p>';
                        
                        break;
                        
                    case 5:

                        return '<span style="color:'+strColor_p+'">● </span><p>Registros agendados para los que aun no se ha cumplido la fecha y hora programada.</p>';
                        
                        break;
                        
                    case 6:

                        return '<span style="color:'+strColor_p+'">● </span><p>Registros nuevos sin ninguna gestión próximos a ser marcados.</p>';
                        
                        break;
                        
                    case 7:

                        return '<span style="color:'+strColor_p+'">● </span><p>Registros ya gestionados pero que se van a marcar proximamente.</p>';
                        
                        break;
                    case 8:

                        return '<span style="color:'+strColor_p+'">● </span><p>Registros agendados para los que ya se cumplio la fecha y hora programada.</p>';
                        
                        break;
                }


            }

            </script>

            <!-- script para el reporte de ordenamiento -->
            <script>
                // Para invocar el reporte de ordenamiento toca hacerlo por medio de un post a report

                function getOrdenamiento() {
                    $.ajax({
                        type: "post",
                        url: "<?=base_url?>pages/charts/report.php?Reporte=true",
                        data: {
                            tipoReport_t: "ordenamiento",
                            intIdHuesped_t: "<?=$_SESSION["HUESPED"]?>",
                            intIdEstrat_t: null,
                            intIdBd_t: "<?=$intIdBd_t?>",
                            intIdTipo_t: null,
                            intIdGuion_t: null,
                            intIdCBX_t: "<?=$intCampan_t?>",
                            intIdPeriodo_t: null,
                            intIdMuestra_t: "<?=$intIdMuestra_t?>",
                            intIdPaso_t: "<?=$intEstpas_t?>",
                            strLimit_t: "si",
                            intFilas_t: 0,
                            intLimite_t: 50,
                            intPaginaActual_t: 1,

                        },
                        success: function (response) {
                            $("#ordenamiento").html(response);
                        }
                    });
                  }

                $("#ordenamientoTab").click(() => {
                    getOrdenamiento();
                });

                if($("#ordenamientoTab").hasClass("active")){
                    getOrdenamiento();
                }


                // Como a la final es un reporte se necesita tambien la funcionalidad de paginar
                function paginar(intTipoPaginado_p,intPagina_p = null){

                    intLimite_t = Number($("#intLimite_t").val());

                    if (intTipoPaginado_p == "A") {

                        intFilas_t = Number($("#intFilas_t").val())-Number($("#intLimite_t").val());
                        var intPaginaActual_t = Number($("#intPaginaActual_t").val())-1;

                    }else if(intTipoPaginado_p == "B"){
                        
                        intFilas_t = Number($("#intFilas_t").val())+Number($("#intLimite_t").val());
                        var intPaginaActual_t = Number($("#intPaginaActual_t").val())+1;

                    }else{

                        if (intPagina_p) {

                            if (Number(intPagina_p) > 1) {

                                intFilas_t = (Number(intPagina_p)-1)*Number($("#intLimite_t").val());

                            }else{

                                intFilas_t = 0;  

                            }

                            var intPaginaActual_t = Number(intPagina_p);
                            
                        }

                    }

                    var objDataReport_t = {consulta : $("#strtxtConsulta_t").val(),intFilas_t : intFilas_t, intRegistrosTotal_t : $("#intRegistrosTotal_t").val(), intCantidadPaginas_t : $("#intCantidadPaginas_t").val(),intLimite_t:intLimite_t,intPaginaActual_t:intPaginaActual_t, tipoReport_t: "ordenamiento"};

                    $.ajax({
                        url  :  '<?=base_url?>pages/charts/report.php?Paginado=true',
                        type :  'post',
                        data : objDataReport_t,
                        success : function(data){
                            var strHTMLReporte_t = '';

                            strHTMLReporte_t += '<div class="col-md-3">';
                            strHTMLReporte_t +=     '<div class="box-header">';
                            strHTMLReporte_t +=         '<select class="form-control" id="selIntLimite_t" placeholder="Your query" name="selIntLimite_t">';
                            strHTMLReporte_t +=         '<option selected value="5" >Mostrar : 5</option>';
                            strHTMLReporte_t +=         '<option value="10" >Mostrar : 10</option>';
                            strHTMLReporte_t +=         '<option value="15" >Mostrar : 15</option>';
                            strHTMLReporte_t +=         '<option value="30" >Mostrar : 30</option>';
                            strHTMLReporte_t +=         '<option value="50" >Mostrar : 50</option>';
                            strHTMLReporte_t +=         '</select>';
                            strHTMLReporte_t +=     '</div>';
                            strHTMLReporte_t += '</div>';
                            
                            $("#ordenamiento").html(strHTMLReporte_t);
                            $("#ordenamiento").html(data);

                            $("#selIntLimite_t").val(intLimite_t).trigger("change");
                        },
                        beforeSend : function(){
                            $.blockUI({ 
                                baseZ: 2000,
                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });

                    }




            </script>


            <!-- script para el reporte del marcador -->

            <?php if (isset($_GET["tipoMarc"]) && $_GET["tipoMarc"] == 6 ||$_GET["tipoMarc"] == 7 ||$_GET["tipoMarc"] == 8 ) {?>

                <script>
                    // BGCR -  Se llama el reporte del marcador correspondiente al cargar la pagina

                    window.onload = () => {
                        reportMarcador();
                    }


                    // BGCR -  Eventos para pausar o reanudar la actualizacion constante del reporte

                    $("#btnAutoRefreshReport").click(() => {
                        let btnAutoRefresh = $("#btnAutoRefreshReport");

                        if (btnAutoRefresh.attr('state') == "refresh") {
                            btnAutoRefresh.html('<i class="fas fa-play">');
                            btnAutoRefresh.attr('state', "pause");

                            clearInterval(refreshIntervalReport);

                        } else {
                            btnAutoRefresh.html('<i class="fas fa-pause">');
                            btnAutoRefresh.attr('state', "refresh");

                            refreshIntervalReport = setInterval(reportMarcador, 15000);

                        }

                    })



                    $("#btnAutoRefreshLog").click(() => {
                        let btnAutoRefresh = $("#btnAutoRefreshLog");

                        if (btnAutoRefresh.attr('state') == "refresh") {
                            btnAutoRefresh.html('<i class="fas fa-play">');
                            btnAutoRefresh.attr('state', "pause");

                            clearInterval(refreshIntervalLog);

                        } else {
                            btnAutoRefresh.html('<i class="fas fa-pause">');
                            btnAutoRefresh.attr('state', "refresh");

                            refreshIntervalLog = setInterval(logMarcador, 15000);

                        }

                    })


                    // BGCR -  Evento para evitar la actualizacion del reporte cuando el usuario este afuera del tab


                    $("#analisisTab a").click(() => {
                        clearInterval(refreshIntervalLog);
                        clearInterval(refreshIntervalReport);
                    })

                    $("#marcadorTab a").click(() => {
                        refreshIntervalReport = setInterval(reportMarcador, 15000);
                        refreshIntervalLog = setInterval(logMarcador, 15000);
                    })

                    // BGCR -  Funcion para realizar el paginado del reporte y realizar las consultas con limites


                    function paginar(intTipoPaginado_p, intPagina_p = null) {

                        intLimite_t = Number($("#intLimite_t").val());

                        if (intTipoPaginado_p == "A") {

                            intFilas_t = Number($("#intFilas_t").val()) - Number($("#intLimite_t").val());
                            var intPaginaActual_t = Number($("#intPaginaActual_t").val()) - 1;

                        } else if (intTipoPaginado_p == "B") {

                            intFilas_t = Number($("#intFilas_t").val()) + Number($("#intLimite_t").val());
                            var intPaginaActual_t = Number($("#intPaginaActual_t").val()) + 1;

                        } else {

                            if (intPagina_p) {

                                if (Number(intPagina_p) > 1) {

                                    intFilas_t = (Number(intPagina_p) - 1) * Number($("#intLimite_t").val());

                                } else {

                                    intFilas_t = 0;

                                }

                                var intPaginaActual_t = Number(intPagina_p);

                            }

                        }

                        var objDataReport_t = {
                            consulta: $("#strtxtConsulta_t").val(),
                            intFilas_t: intFilas_t,
                            intRegistrosTotal_t: $("#intRegistrosTotal_t").val(),
                            intCantidadPaginas_t: $("#intCantidadPaginas_t").val(),
                            intLimite_t: intLimite_t,
                            intPaginaActual_t: intPaginaActual_t
                        };

                        $.ajax({
                            url: '<?= base_url ?>pages/charts/report.php?Paginado=true',
                            type: 'post',
                            data: objDataReport_t,
                            success: function(data) {

                                $("#s_resultados").html(data);

                                $("#selIntLimite_t").val(intLimite_t).trigger("change");
                            },
                            beforeSend: function() {
                                $.blockUI({
                                    baseZ: 2000,
                                    message: '<img src="<?= base_url ?>assets/img/clock.gif" /> Espere un momento por favor, estamos guardando la informaci&oacute;n'
                                });
                            },
                            complete: function() {
                                $.unblockUI();
                            }
                        });

                    }


                    function reportMarcador() {
                        $.ajax({
                            url: "../charts/report.php?Reporte=true",
                            type: "POST",
                            data: {
                                tipoReport_t: 'marcador',
                                strLimit_t: "si",
                                intLimite_t: 30,
                                intIdBd_t: <?php echo $intCampan_t ?>,
                                intIdPaso_t: <?php echo $cola["intIdCola_t"] ?>,
                            },
                            success: function(data) {
                                $("#s_resultados").html(data);
                            },
                        });
                    }


                    function logMarcador() {

                        const strCola = <?php echo $cola["intIdMarcador_t"]; ?>;

                        $.ajax({
                            url: "./reportMarcador.php?logMarcador=" + strCola,
                            dataType: "json",
                            success: function(data) {
                                $("#idTableLog").html(data.log);
                            },
                        });
                    }

                    // Se inicia un intervalo para actualizar el reporte
                    let refreshIntervalReport;
                    let refreshIntervalLog;
                </script>
            <?php } ?>




        <script src="<?= base_url ?>assets/bootstrap/js/bootstrap.min.js"></script>
    </body>

    </html>

<?php

} else {
    echo "No se puede acceder a esta pagina por medio de este dominio";
}



/**
 *BGCR - Esta funcion nos trae el id y la cola del marcador
 *@param intEstpas_t = id del paso, intCampan_p = id de la campaña
 *@return array [idMarcardo, idCola]
 */


function getCola(int $intEstpas_t, int $intCampan_p): array
{
    global $mysqli;
    global $BaseDatos_telefonia;
    global $BaseDatos_systema;


    $sqlMarcador_t = "SELECT M.id, C.CAMPAN_IdCamCbx__b FROM {$BaseDatos_telefonia}.dy_marcador_campanas M JOIN {$BaseDatos_systema}.CAMPAN C ON C.CAMPAN_ConsInte__b = M.id_campana_crm where M.nombre  = 'MarAuto_P{$intEstpas_t}_CCRM_{$intCampan_p}' ";

    $resMarcador_t = $mysqli->query($sqlMarcador_t);
    $intIdMarcador_t = 0;
    $strIdCola_t  = 0;
    if ($resMarcador_t && $resMarcador_t->num_rows > 0) {
        $resMarcador_t = $resMarcador_t->fetch_object();

        $intIdMarcador_t = $resMarcador_t->id;
        $strIdCola_t = $resMarcador_t->CAMPAN_IdCamCbx__b;
    }


    return ["intIdMarcador_t" => $intIdMarcador_t, "intIdCola_t" => $strIdCola_t];
}


/**
 *BGCR - Esta funcion nos trae la informacion de estado del marcador
 *@param intIdMarcador_p = id del marcador
 *@return object
 */



function getStatusMarcador(int $intIdMarcador_p, bool $onlyLog = false): object
{
    global $mysqli;
    global $BaseDatos_telefonia;

    $onlyLog == false ? $sqlStatusMarcador_t = "SELECT ID_INTERNO, NOMBRE, ACD, TIEMPO_TIMBRE, ACTIVA, LOG, ACELERACION, TIPO FROM {$BaseDatos_telefonia}.v_datos_marcador WHERE id = {$intIdMarcador_p} " : $sqlStatusMarcador_t = "SELECT LOG FROM {$BaseDatos_telefonia}.v_datos_marcador WHERE id = {$intIdMarcador_p} ";

    $resStatusMarcador_t = $mysqli->query($sqlStatusMarcador_t);

    if ($resStatusMarcador_t && $resStatusMarcador_t->num_rows > 0) {
        $resStatusMarcadorr_t = $resStatusMarcador_t->fetch_object();
    }

    return $resStatusMarcadorr_t;
}




/**
 *BGCR - Esta funcion nos ayuda a traducir el tipo de marcador 
 *@param intTipoMarcador_p = tipo de marcador
 *@return string Nombre del tipo del marcador
 */


function translateMarcador(int $intTipoMarcador_p): string
{
    $strTipoMarcador = '';

    switch ($intTipoMarcador_p) {
        case '5':
            $strTipoMarcador = 'Progresivo';
            break;

        case '6':
            $strTipoMarcador = 'PDS';
            break;

        case '7':
            $strTipoMarcador = 'Predictivo';
            break;

        case '8':
            $strTipoMarcador = 'Marcador Predictivo';
            break;
    }

    return $strTipoMarcador;
}


?>