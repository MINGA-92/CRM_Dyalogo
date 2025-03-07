<!-- DataTables -->
<link rel="stylesheet" href="<?=base_url?>assets/plugins/datatables/dataTables.bootstrap.css">

<div class="row">
    <textarea id="json" style="display:none;"><?php echo $strJsonDatos_t;?></textarea>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="btn-group pull-right">
                    <button id="export-errores" type="button" class="btn btn-danger" style="display: none; margin-right: 10px;" >Se presentaron errores, presione aquí para verlos</button>
                    <button id="export-menu" type="button" class="btn btn-info" >Exportar</button>

                </div>
                    <form action="/manager/pages/charts/report.php" method="POST" id="export-form">
                        <input type="hidden" name="tipoReport_t" id="tipoReport_t" value="">
                        <input type="hidden" name="strFechaIn_t" id="strFechaIn_t" value="">
                        <input type="hidden" name="strFechaFn_t" id="strFechaFn_t" value="">
                        <input type="hidden" name="intIdHuesped_t" id="intIdHuesped_t" value="">
                        <input type="hidden" name="intIdEstrat_t" id="intIdEstrat_t" value="">
                        <input type="hidden" name="intIdGuion_t" id="intIdGuion_t" value="">
                        <input type="hidden" name="intIdCBX_t" id="intIdCBX_t" value="">
                        <input type="hidden" name="intIdPeriodo_t" id="intIdPeriodo_t" value="">
                        <input type="hidden" name="intIdPaso_t" id="intIdPaso_t" value="">
                        <input type="hidden" name="strLimit_t" id="strLimit_t" value="">
                        <input type="hidden" name="Exportar" id="Exportar" value="si">
                        <input type="hidden" name="NombreExcell" id="NombreExcell" value="">
                        <input type="hidden" name="intIdMuestra_t" id="intIdMuestra_t" value="">
                    </form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <div class="nav-tabs-custom">

                    <ul class="nav nav-tabs">

                        <li>
                            <a href="#tab_1" data-toggle="tab" id="tabs_click_1">RESUMEN CARGUE</a>
                        </li>

                        <li>
                            <a href="#tab_2" data-toggle="tab" id="tabs_click_2">FALLARON POR VALIDACIÓN</a>
                        </li>

                    </ul>


                    <div class="tab-content">

                        <div class="tab-pane " id="tab_1"> 

                            <table id="grid" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                      <?php
                                        foreach ($arrDatosHead_t as $nombre) {
                                            echo "<th>".$nombre."</th>";
                                        }?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    foreach ($arrDatos_t as $row => $dato) {
                                        echo "<tr>";
                                        foreach ($arrDatosHead_t as $nombre) {
                                            echo "<td>".$dato[$nombre]."</td>";
                                        }
                                        echo "</tr>";
                                    }

                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                      <?php
                                        foreach ($arrDatosHead_t as $nombre) {
                                            echo "<th>".$nombre."</th>";
                                        }?>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                        <div class="tab-pane " id="tab_2">

                            <table id="grid2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                      <?php
                                        if (count($arrHeadErrores_t) == 0) {
                                            echo "<th></th>";
                                        }else{
                                            foreach ($arrHeadErrores_t as $nombre) {
                                                echo "<th>".$nombre."</th>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($arrDatosErrores_t) == 0) {
                                        echo "<tr><td>No hay errores en el cargue.</td></tr>";
                                    }else{
                                        foreach ($arrDatosErrores_t as $row => $dato) {
                                            echo "<tr>";
                                            foreach ($arrHeadErrores_t as $nombre) {
                                                echo "<td>".$dato[$nombre]."</td>";
                                            }
                                            echo "</tr>";
                                        }                                        
                                    }

                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                      <?php
                                        if (count($arrHeadErrores_t) == 0) {
                                            echo "<th></th>";
                                        }else{
                                            foreach ($arrHeadErrores_t as $nombre) {
                                                echo "<th>".$nombre."</th>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<script src="<?=base_url?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- page script -->
<script type="text/javascript">
    $("#export-errores").click(function(){
         window.location.href = '<?=base_url?>exportar.php';             
     });
    $(function () {
        $('#grid').DataTable({
            "ordering": false
        });
        let rowTable = $('#grid_wrapper div')[6]
        rowTable.className = "table-responsive"

        $('#grid2').DataTable({
            "ordering": false
        });

        $("#export-menu").click(function(){
            $('#hidden-type').val('export-to-excel');
            $('#export-form').submit();
            $('#hidden-type').val('');
        });
    });

    $("#tabs_click_1").trigger("click");

</script>