<!-- DataTables -->
<link rel="stylesheet" href="<?=base_url?>assets/plugins/datatables/dataTables.bootstrap.css">

<div class="row">
    <textarea id="json" style="display:none;"><?php echo $strJsonDatos_t;?></textarea>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body col-md-12 col-xs-12 table-responsive">
                <table id="grid" class="table table-bordered table-striped" style="white-space: nowrap;">
                    <thead>
                        <tr>
                           <?php
                                foreach ($arrDatosHead_t as $nombre) {
                                    echo "<th>".$nombre."</th>";
                            }?>
                            <th>RESPUESTAS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            foreach ($arrDatos_t as $row => $dato) { ?>
                                <tr>
                                <?php foreach ($arrDatosHead_t as $nombre) { ?>
                                    <td><?=$dato[$nombre]?></td>
                                <?php } ?>
                                    <td>
                                        <table class="table table-bordered-dark table-condensed">
                                            <thead class="thead-dark">
                                                <th>RESPUESTA</th>
                                                <th>CANTIDAD</th>
                                            </thead>

                                            <tbody>
                                                <?php
                                                // POR CADA AGENTE SE CONSULTA LAS RESPUESTAS OBTENIDAS

                                                if($tipoReport_t == "encuestasIVRResumenPregun"){
                                                    $sqlRespuestas =  $mysqli->query("SELECT r.respuesta, count(1) as cantidad FROM {$BaseDatos_telefonia}.dy_encuestas_resultados r JOIN {$BaseDatos_telefonia}.dy_encuestas e ON e.id = r.id_encuesta JOIN {$BaseDatos_telefonia}.dy_encuestas_preguntas p ON p.id = r.id_pregunta where p.nombre = '{$dato["PREGUNTA"]}' and r.id_proyecto = '{$idProyecto}' AND {$strCondicionGlobal_t}  group by r.respuesta");
                                                }else{
                                                    $sqlRespuestas =  $mysqli->query("SELECT r.respuesta, count(1) as cantidad FROM {$BaseDatos_telefonia}.dy_encuestas_resultados r JOIN {$BaseDatos_telefonia}.dy_encuestas e ON e.id = r.id_encuesta JOIN {$BaseDatos_telefonia}.dy_encuestas_preguntas p ON p.id = r.id_pregunta WHERE r.nombre_agente = '{$dato["AGENTE"]}' and r.id_proyecto = '{$idProyecto}' AND {$strCondicionGlobal_t} group by r.respuesta");
                                                }

                                                if($sqlRespuestas){
                                                    while ($resRespuestas = $sqlRespuestas->fetch_object()){ ?>
                                                    <tr>
                                                        <td><?=(is_null($resRespuestas->respuesta)) ? "Sin respuesta" : $resRespuestas->respuesta ?></td>
                                                        <td><?=$resRespuestas->cantidad?></td>
                                                    </tr>
                                                <?php
                                                    }
                                                } 
                                                ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            <?php }

                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <?php
                                foreach ($arrDatosHead_t as $nombre) {
                                    echo "<th>".$nombre."</th>";
                            }?>
                            <th>RESPUESTAS</th>
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
            <strong>Mostrando del :</strong> <?php echo number_format($intFilas_t); ?>, <strong>Hasta el :</strong> <?php echo (number_format($intFilas_t+$intLimite_t)); ?> | <strong>Registros :</strong> <?php echo number_format($arrDimensiones_t[0]); ?> | <strong>Paginas :</strong> <?php echo number_format($arrDimensiones_t[1]); ?>. 
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
                <li class="paginate_button previous  <?php if ($intPaginaActual_t <= 1) {echo "disabled";} ?>" id="grid_previous">
                    <a href="javascript:void(0)" <?php if ($intPaginaActual_t > 1) { ?> onclick="paginar('A');"  <?php } ?>>Previa</a>
                </li>

                    <?php if ($arrDimensiones_t[1] <= 18) { ?>

                        <li class="paginate_button <?php if ($intPaginaActual_t == 1) { echo "active"; } ?>">
                            <a href="javascript:void(0)" onclick="paginar('','1');" aria-controls="grid" tabindex="1">1</a>
                        </li>
                        <li class="paginate_button disabled" id="grid_ellipsis">
                            <a href="#" aria-controls="grid" data-dt-idx="6" tabindex="0">…</a>
                        </li>

                        <?php for ($i=0; $i < $arrDimensiones_t[1]; $i++) { ?>

                            <?php if (($i+1)>1) { ?>

                                <li class="paginate_button <?php if ($intPaginaActual_t == ($i+1)) { echo "active"; } ?>">
                                    <a href="javascript:void(0)" onclick="paginar('',<?php echo ($i+1); ?>);" aria-controls="grid" tabindex="<?php echo ($i+1); ?>"><?php echo ($i+1); ?></a>
                                </li>

                            <?php } ?>

                        <?php } ?>

                    <?php }else{ ?>

                         <?php $iter = 1; ?>

                        <li class="paginate_button <?php if ($intPaginaActual_t == 1) { echo "active"; } ?>">
                            <a href="javascript:void(0)" onclick="paginar('','1');" aria-controls="grid" tabindex="1">1</a>
                        </li>

                        <li class="paginate_button disabled" id="grid_ellipsis">
                            <a href="#" aria-controls="grid" data-dt-idx="6" tabindex="0">…</a>
                        </li>

                         <?php for ($i=0; $i < $arrDimensiones_t[1]; $i++) { ?>

                            <?php $iter = ($iter+1); ?>

                            <?php if (($i+1) != $arrDimensiones_t[1] && ($i+1) > 1 && (($i+1) >= ($intPaginaActual_t-7) && ($i+1) <= ($intPaginaActual_t+7))) { ?>

                            <li class="paginate_button <?php if ($intPaginaActual_t == ($i+1)) { echo "active"; } ?>">
                                <a href="javascript:void(0)" onclick="paginar('',<?php echo ($i+1); ?>);" aria-controls="grid" tabindex="<?php echo ($i+1); ?>"><?php echo ($i+1); ?></a>
                            </li>

                            <?php } ?>


                        <?php } ?>

                        <li class="paginate_button disabled" id="grid_ellipsis">
                            <a href="#" aria-controls="grid" data-dt-idx="6" tabindex="0">…</a>
                        </li>
                        <li class="paginate_button <?php if ($intPaginaActual_t == $arrDimensiones_t[1]) { echo "active"; } ?>">
                            <a href="javascript:void(0)" onclick="paginar('','<?php echo $arrDimensiones_t[1]; ?>');" aria-controls="grid" tabindex="<?php echo $arrDimensiones_t[1]; ?>"><?php echo $arrDimensiones_t[1]; ?></a>
                        </li>

                    <?php } ?>

                <li class="paginate_button next <?php if ($intPaginaActual_t >= $arrDimensiones_t[1]) {echo "disabled";} ?>" id="grid_next">
                    <a href="javascript:void(0)" <?php if ($intPaginaActual_t < $arrDimensiones_t[1]) { ?> onclick="paginar('B');"  <?php } ?>>Siguiente</a>
                </li>
            </ul>
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
</script>
<style>
    .thead-dark tr {
        background-color: #3c8dbc;
        color: #ffffff;
    }

    .table-bordered-dark {
        border: 1px solid #3c8dbc;
        border-radius: 3px;
    }
</style>