<?php 
    if(empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
        $idioma = 'es';
    }else{
        $idioma = explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];    
    }
    $language = null;
    switch ($idioma) {
        case 'en':
            $language = "english";
            break;
        
        case 'es':
            $language = "spanish";
            break;

        case 'po':
            $language = "portuguese";
            break;

        default:
            $language = "english";
            break;
    }
?>  
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_reportes_mios_ ;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content" id="secPrincipalContenedor">
        <form id="report_frm"  method="post">
            <input type="hidden" class="form-control" id="server_ip" value="<?php echo $ipReportes;?>" name="server_ip" >
            <input type="hidden" class="form-control" id="username" value="<?php echo $DB_User_R;?>" name="username" >
            <input type="hidden" class="form-control" id="password" value="<?php echo $DB_Pass_R;?>" name="password" >
            <input type="hidden" class="form-control" id="database" value="<?php echo $BaseDatos_systema;?>" name="database" >
            <input type="hidden" name="refresh_period" id="refresh_period" value="5">
            <input type="hidden" name="language" id="language" value="<?php echo $language; ?>">
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <h3 class="box-title">
                        Filters
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Campaings</label>
                                <select class="form-control" id="reporte_select" placeholder="Your query" name="reporte_select">
                                    <option value="0">SELECT</option>
                                    <?php 
                                        $Lsql = "SELECT id , UPPER(asunto) as asunto FROM ".$BaseDatos_general.".reportes_automatizados WHERE id_huesped = ".$_SESSION['HUESPED'];
                                        $res = $mysqli->query($Lsql);
                                        while ($key = $res->fetch_object()) {
                                            echo "<option value='".$key->id."'>".$key->asunto."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Reports</label>
                                <select class="form-control" disabled id="send_query" placeholder="Your query" name="send_query">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="pwd">Graph Type</label>
                                <select class="form-control selectpicker" name="graph_type" id="graph_type">
                                    <option value="chart">Chart</option>
                                    <option value="grid">Grid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" id="chart_types">
                                <label for="pwd">Kind of Chart</label>     
                                <select class="form-control selectpicker" name="kind_of_chart" id="kind_of_chart">
                                    <option value="lines">Lines</option>
                                    <option value="bars">Bars</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="pwd">Result Type</label>        
                                <select class="form-control selectpicker" name="result_type" id="result_type">
                                    <option value="static">Static</option>
                                    <option value="dynamic">Dynamic</option>
                                </select>
                            </div>
                        </div>
                    </div>    
                    <div>
                        <input type="hidden" name="generate" value="-1">
                        <button class="btn btn-primary pull-right" type="button" id="btnEnvioReport">
                            Generate report
                        </button>
                    </div>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">
                        Results
                    </h3>
                </div>
                <div class="box-body" id="resultados">
 
                </div>
            </div>
        </form>
    </section>
</div>
<script type="text/javascript">
    $(function(){
        $("#reporte_select").change(function(){
            var valor = $(this).val();
            if(valor != 0){
                $.ajax({
                    url  :  'pages/charts/functions.php',
                    type :  'post',
                    data : { getReportes : true, id : valor },
                    dataType : 'html',
                    success : function(data){
                        $("#send_query").html(data);
                        $("#send_query").prop('disabled' , false);
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
        });

        $("#btnEnvioReport").click(function(){
            if($("#reporte_select").val() == 0){
                alertify.error("The campaing is necessary");
                $("#reporte_select").focus();
            }else{
                var formData = new FormData($("#report_frm")[0]);
                $.ajax({
                    url  :  'pages/charts/report.php',
                    type :  'post',
                    data : formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(data){
                        $("#resultados").html(data);
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
        });
    })
</script>
