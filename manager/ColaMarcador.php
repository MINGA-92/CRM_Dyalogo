<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/alertify.core.css"/>
    <link rel="stylesheet" href="assets/css/alertify.default.css"/>
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="assets/ionicons-master/css/ionicons.min.css"/>
    <script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- JDBD - Librerias para Higcharts -->
    <script src="assets/HighCharts/highcharts.js"></script>
    <script src="assets/HighCharts/highcharts-3d.js"></script>
    <script src="assets/HighCharts/modules/cylinder.js"></script>
    <script src="assets/HighCharts/modules/funnel3d.js"></script>
    <script src="assets/HighCharts/modules/exporting.js"></script>
    <script src="assets/HighCharts/modules/export-data.js"></script>
    <script src="assets/HighCharts/modules/accessibility.js"></script>
    <script src="assets/js/alertify.js"></script>

    <!-- JDBD - Librerias para Higcharts -->
</head>
<body>
<?php

        if (isset($_GET["encrypt"])) { ?>

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


<?php   }else{ ?>

            <div class=" col-sm-10 col-sm-offset-1">
                <div class="alert alert-danger mensaje">
                    <ul>
                        <li>No puede ingresar a este sitio desde este dominio !</li>
                    </ul>
                </div>
            </div>

<?php   }




?>

<script type="text/javascript">


var strEncrypt_t = '<?php if (isset($_GET["encrypt"])) { echo $_GET["encrypt"]; }else{ echo "MHww";} ?>';

arrEncrypt_t = atob(strEncrypt_t).split("|");

var strJSONInfoCampan_t = $.ajax({
                                    url: 'cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
                                    type:'post',
                                    dataType : 'json',
                                    data : {JSONInfoCampan : true, idPaso_t : arrEncrypt_t[0], idAgente_t : arrEncrypt_t[1]},
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
                    url: 'cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
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

    window.location = 'cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?intIdMetrica='+intIdMetrica+'&arrTl='+JSON.stringify(arrJSONInfoCampan_t.arrTl)+'&mt='+arrJSONInfoCampan_t.mt+'&lr='+arrJSONInfoCampan_t.lr+'&ic='+arrJSONInfoCampan_t.ic+'&dt='+arrJSONInfoCampan_t.dt+'&bd='+arrJSONInfoCampan_t.bd+'&NRGXP='+JSON.stringify(arrJSONInfoCampan_t.NRGXP);

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
</body>
</html>