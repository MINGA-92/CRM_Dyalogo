<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url ?>assets/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="<?=base_url?>assets/plugins/select2/select2.min.css" />

</br>
<div class="row" style="padding-inline: 10px; padding-bottom: 10px;">
    <div class="col-md-10 col-xs-12 col-sm-10">
        <p><b>Nota:</b> En el reporte se muestra unicamente los siguientes 500 registros que se van a marcar, al utilizar la opcion de Actualizacion Automatica el reporte se refrescara cada 5 segundos, de tal forma que se quitan los registros que se van marcando en el momento</p>
    </div>
    <div class="col-md-2 col-xs-6 col-sm-2">
        <button id="btnRefreshReport" type="button" class="form-control btn btn-success" refresh="pause" >Actualizacion Automatica
            <li class="fas fa-play" aria-hidden="true"></li>
        </button>
    </div>
</div>
<div class="row" style="padding-inline: 10px; padding-bottom: 10px;">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            <div class="radio">
                <label>
                    <input type="radio" name="radio_filtro_user" id="radio_todos" checked="" value="1" class="radio_filtro_user" value="1">MOSTRAR TODOS</label>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-xs-12">
        <div class="form-group">
            <div class="radio">
                <label>
                    <input type="radio" name="radio_filtro_user" id="radio_filtro_user" value="2" class="radio_filtro_user" value="2">FILTRAR POR AGENTE</label>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            <div class="radio">
                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="user_filter" id="user_filter" disabled="disabled">
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <textarea id="json" style="display:none;"><?php echo $strJsonDatos_t; ?></textarea>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body col-md-12 col-xs-12 table-responsive">
                <table id="grid" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <?php
                            foreach ($arrDatosHead_t as $nombre) {
                                echo "<th>" . $nombre . "</th>";
                            } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- contenido body -->

                        <?php

                        foreach ($arrDatos_t as $row => $dato) {
                            echo "<tr>";
                            foreach ($arrDatosHead_t as $nombre) {
                                echo "<td>" . $dato[$nombre] . "</td>";
                            }
                            echo "</tr>";
                        }

                        ?>
                        <!-- contenido body -->

                    </tbody>
                    <tfoot>
                        <tr>
                            <?php
                            foreach ($arrDatosHead_t as $nombre) {
                                echo "<th>" . $nombre . "</th>";
                            } ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12">
        <div class="dataTables_info" id="grid_info" role="status" aria-live="polite">
            <strong>Mostrando del :</strong> <?php echo number_format($intFilas_t); ?>, <strong>Hasta el :</strong> <?php echo (number_format($intFilas_t + $intLimite_t)); ?> | <strong>Registros :</strong> <?php echo number_format($arrDimensiones_t[0]); ?> | <strong>Paginas :</strong> <?php echo number_format($arrDimensiones_t[1]); ?>.
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <div class="dataTables_paginate paging_simple_numbers" id="grid_paginate">
            <textarea hidden id="strtxtConsulta_t" name="strtxtConsulta_t"><?php echo $arrDimensiones_t[3]; ?></textarea>
            <input type="hidden" id="intCantidadPaginas_t" name="intCantidadPaginas_t" value="<?php echo $arrDimensiones_t[1]; ?>">
            <input type="hidden" id="intRegistrosTotal_t" name="intRegistrosTotal_t" value="<?php echo $arrDimensiones_t[0]; ?>">
            <input type="hidden" id="intFilas_t" name="intFilas_t" value="<?php echo $intFilas_t; ?>">
            <input type="hidden" id="intLimite_t" name="intLimite_t" value="<?php echo $intLimite_t; ?>">
            <input type="hidden" id="intPaginaActual_t" name="intPaginaActual_t" value="<?php echo $intPaginaActual_t; ?>">
            <ul class="pagination">
                <li class="paginate_button previous  <?php if ($intPaginaActual_t <= 1) {
                                                            echo "disabled";
                                                        } ?>" id="grid_previous">
                    <a href="javascript:void(0)" <?php if ($intPaginaActual_t > 1) { ?> onclick="paginar('A');" <?php } ?>>Previa</a>
                </li>

                <?php if ($arrDimensiones_t[1] <= 18) { ?>

                    <li class="paginate_button <?php if ($intPaginaActual_t == 1) {
                                                    echo "active";
                                                } ?>">
                        <a href="javascript:void(0)" onclick="paginar('','1');" aria-controls="grid" tabindex="1">1</a>
                    </li>
                    <li class="paginate_button disabled" id="grid_ellipsis">
                        <a href="#" aria-controls="grid" data-dt-idx="6" tabindex="0">…</a>
                    </li>

                    <?php for ($i = 0; $i < $arrDimensiones_t[1]; $i++) { ?>

                        <?php if (($i + 1) > 1) { ?>

                            <li class="paginate_button <?php if ($intPaginaActual_t == ($i + 1)) {
                                                            echo "active";
                                                        } ?>">
                                <a href="javascript:void(0)" onclick="paginar('',<?php echo ($i + 1); ?>);" aria-controls="grid" tabindex="<?php echo ($i + 1); ?>"><?php echo ($i + 1); ?></a>
                            </li>

                        <?php } ?>

                    <?php } ?>

                <?php } else { ?>

                    <?php $iter = 1; ?>

                    <li class="paginate_button <?php if ($intPaginaActual_t == 1) {
                                                    echo "active";
                                                } ?>">
                        <a href="javascript:void(0)" onclick="paginar('','1');" aria-controls="grid" tabindex="1">1</a>
                    </li>

                    <li class="paginate_button disabled" id="grid_ellipsis">
                        <a href="#" aria-controls="grid" data-dt-idx="6" tabindex="0">…</a>
                    </li>

                    <?php for ($i = 0; $i < $arrDimensiones_t[1]; $i++) { ?>

                        <?php $iter = ($iter + 1); ?>

                        <?php if (($i + 1) != $arrDimensiones_t[1] && ($i + 1) > 1 && (($i + 1) >= ($intPaginaActual_t - 7) && ($i + 1) <= ($intPaginaActual_t + 7))) { ?>

                            <li class="paginate_button <?php if ($intPaginaActual_t == ($i + 1)) {
                                                            echo "active";
                                                        } ?>">
                                <a href="javascript:void(0)" onclick="paginar('',<?php echo ($i + 1); ?>);" aria-controls="grid" tabindex="<?php echo ($i + 1); ?>"><?php echo ($i + 1); ?></a>
                            </li>

                        <?php } ?>


                    <?php } ?>

                    <li class="paginate_button disabled" id="grid_ellipsis">
                        <a href="#" aria-controls="grid" data-dt-idx="6" tabindex="0">…</a>
                    </li>
                    <li class="paginate_button <?php if ($intPaginaActual_t == $arrDimensiones_t[1]) {
                                                    echo "active";
                                                } ?>">
                        <a href="javascript:void(0)" onclick="paginar('','<?php echo $arrDimensiones_t[1]; ?>');" aria-controls="grid" tabindex="<?php echo $arrDimensiones_t[1]; ?>"><?php echo $arrDimensiones_t[1]; ?></a>
                    </li>

                <?php } ?>

                <li class="paginate_button next <?php if ($intPaginaActual_t >= $arrDimensiones_t[1]) {
                                                    echo "disabled";
                                                } ?>" id="grid_next">
                    <a href="javascript:void(0)" <?php if ($intPaginaActual_t < $arrDimensiones_t[1]) { ?> onclick="paginar('B');" <?php } ?>>Siguiente</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- DataTables -->
<script src="<?= base_url ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?=base_url?>assets/plugins/select2/select2.full.min.js"></script>


<!-- page script -->
<script type="text/javascript">
    $(function() {

        // Necesito guardar la consulta original a futuro
        let consultaOriginal = `<?=$arrDimensiones_t[3]?>`;
        let consultaFiltroAgente = `<?=$arrDimensiones_t[3]?>`;
        let valueSelectedFilter = 0;

        $("#export-errores").click(function() {
            window.location.href = '<?= base_url ?>exportar.php';
        });


        if(typeof intervalRefresh == "undefined"){
            let intervalRefresh;
        }else{
            clearInterval(intervalRefresh)
        }


        $("#btnRefreshReport").click((e) => {
            const buttonRefresh = $(e.target);
            if(buttonRefresh.attr("refresh") == "refresh"){
                buttonRefresh.attr("refresh", "pause");
                buttonRefresh.children("li").removeClass("fa-pause");
                buttonRefresh.children("li").addClass("fa-play");
                buttonRefresh.removeClass("btn-danger");
                buttonRefresh.addClass("btn-success");

                clearInterval(intervalRefresh);

            }else if (buttonRefresh.attr("refresh") == "pause"){
                buttonRefresh.attr("refresh", "refresh");
                buttonRefresh.children("li").removeClass("fa-play");
                buttonRefresh.children("li").addClass("fa-pause");
                buttonRefresh.removeClass("btn-success");
                buttonRefresh.addClass("btn-danger");

                intervalRefresh = setInterval(actualizacionAutomatica, 5000);
            }
        })


        // Debemos de para el interval si solicitan otro reporte
        $("#sql_query").change(() => {
            clearInterval(intervalRefresh)
        })


        $(".radio_filtro_user").change((e) => {;
            if($(e.target).val() == 1){
                $("#user_filter").attr("disabled",true);
                valueSelectedFilter = 1;
                actualizacionAutomatica();
            }else if($(e.target).val() == 2){
                $("#user_filter").attr("disabled",false);
                valueSelectedFilter = 2;
                actualizarFiltroAgent();
                actualizacionAutomatica()
            }
        })


        // Se llen el filtro de los agentes
        $.ajax({
            type: "POST",
            url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php?traerOpcionesLista=true",
            data: {
                intIdCampo_t: $("#idCampanInput").val(),
                strEspecial_t: "usu_campan",
                strEstrategia_t: ""
            },
            success: function (response) {
                $("#user_filter").html(response);
                $("#user_filter").select2();
            }
        });


        $("#user_filter").change(() => {
            actualizarFiltroAgent();
            actualizacionAutomatica();
        });

        function actualizacionAutomatica() {  

            let consulta = consultaOriginal;

            if(valueSelectedFilter == 2){
                consulta = consultaFiltroAgente;
            }

            $.ajax({
                type: "post",
                url: "<?=base_url?>pages/charts/report.php?Paginado=true",
                data: {
                    consulta: consulta,
                    intFilas_t: `<?=$intFilas_t?>`,
                    intRegistrosTotal_t: `<?=$arrDimensiones_t[0]?>`,
                    intCantidadPaginas_t: `<?=$arrDimensiones_t[1]?>`,
                    intLimite_t: 50,
                    intPaginaActual_t: <?=$intPaginaActual_t?>,
                    tipoReport_t: `ordenamiento`,
                    stateRefresh: $("#btnRefreshReport").attr("refresh")

                },
                success: function (response) {
                    let contend = response.split("<!-- contenido body -->")[1];
                    if( contend != undefined){
                        $("#grid").children("tbody").html(contend);
                    }else{
                        $("#grid").children("tbody").html("");
                    }
                    
                }
            });
        }

        function actualizarFiltroAgent() { 

            //Como el paginado lo que recibe es la conslta entonces debo modifiarla por js
            let whereConsultaOriginal = consultaOriginal.split("WHERE")[1].split(") ORDENAMIENTO")[0];
            
            // adicionamos el filtro que necesitamos 
            whereConsultaOriginal += " AND G"+$("#intIdBdInput").val()+"_M"+$("#intIdMuestraInput").val()+"_ConIntUsu_b = '"+$("#user_filter").val()+"' ";

            consultaFiltroAgente = consultaOriginal.split("WHERE")[0] + " WHERE " + whereConsultaOriginal + " ) ORDENAMIENTO " + consultaOriginal.split(") ORDENAMIENTO")[1];

         }

    });

</script>

