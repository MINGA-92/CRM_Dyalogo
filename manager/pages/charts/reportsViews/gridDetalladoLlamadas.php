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
                            <th>Detalle</th>
                            <th>REC</th>
                          <?php
                            foreach ($arrDatosHead_t as $nombre) {
                                if($nombre != "grabacion"){
                                    echo "<th>".$nombre."</th>";
                                }
                            }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($arrDatos_t as $row => $dato) { ?>
                            <tr>
                                <td class="text-center detalleButton"><a href="#" data-toggle="modal" data-target="#detalladoModal" idTelefonia="<?=$dato["UID"]?>">Detalle</a></td>
                                <td class="text-center">
                                    <?php if($dato[$nombre] != "" && $dato[$nombre] != null){ ?>
                                        <a class="btn button" href="https://<?=$_SERVER['SERVER_NAME']?>:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=<?=$dato["UID"]?>&uid2=<?=$dato["UID"]?>&canal=telefonia" target="_blank"><i class='fas fa-download'></i></a>
                                    <?php } ?>

                                </td>
                                <?php foreach ($arrDatosHead_t as $nombre) {
                                    if($nombre != "grabacion"){ ?>
                                        <td><?=$dato[$nombre]?></td>
                                <?php }} ?>
                             </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Detalle</th>
                            <th>Calidad</th>
                          <?php
                            foreach ($arrDatosHead_t as $nombre) {
                                if($nombre != "grabacion"){
                                    echo "<th>".$nombre."</th>";
                                }
                            }?>
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

     const detalladoModal = `
     <!-- Modal detallado de la llamada -->

<div class="modal fade-in" id="detalladoModal" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg modal-detallado">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detallado de llamada</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table text-center" style="white-space: nowrap;">
                                <tbody>
                                    <tr>
                                        <th style="width:50%">Fecha hora inicial:</th>
                                        <td id="detFechaIni"> </td>
                                        <th style="width:50%">Fecha hora final:</th>
                                        <td id="detFechaFin"> </td>
                                        <th style="width:50%">Agente:</th>
                                        <td id="detAgente"></td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Extensión:</th>
                                        <td id="detExt"></td>
                                        <th style="width:50%">Teléfono:</th>
                                        <td id="detTel"></td>
                                        <th style="width:50%">Transferida:</th>
                                        <td id="detTransfer"></td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Campaña:</th>
                                        <td id="detCampan"> </td>
                                        <th style="width:50%">Resultado:</th>
                                        <td id="detResultado"></td>
                                        <th style="width:50%">Sentido:</th>
                                        <td id="detSentido"></td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Duración total:</th>
                                        <td id="detTotal"></td>
                                        <th style="width:50%">Duración al aire:</th>
                                        <td id="detAire"></td>
                                        <th style="width:50%">Redondeo minuto:</th>
                                        <td id="detRedondeo"></td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Duración espera:</th>
                                        <td id="detEspra"></td>
                                        <th style="width:50%">Tiempo timbrando:</th>
                                        <td id="detTimbr"></td>
                                        <th style="width:50%">Troncal:</th>
                                        <td id="detTroncal"></td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">ID:</th>
                                        <td id="detIdLlamada"></td>
                                        <th style="width:50%">Tipo de llamada:</th>
                                        <td id="detTipo"></td>
                                        <th style="width:50%">Costo de llamada:</th>
                                        <td id="detCost"></td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Llamada excepcionada:</th>
                                        <td id="detExcep"></td>
                                        <th style="width:50%">Etiqueta:</th>
                                        <td id="detTag"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading panel-heading-nav">
                                <ul class="nav nav-tabs">
                                    <li role="presentation" class="active">
                                        <a href="#detallePlanta" aria-controls="detallePlanta" role="tab" data-toggle="tab">Detalle Planta</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#detalleACD" aria-controls="detalleACD" role="tab" data-toggle="tab">Detalle ACD</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="detallePlanta">
                                        <div class="table-responsive">
                                            <table class="table text-center table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Grabación</th>
                                                        <th>Fecha hora</th>
                                                        <th>Id llamante</th>
                                                        <th>Fuente</th>
                                                        <th>Destino</th>
                                                        <th>Contexto</th>
                                                        <th>Canal</th>
                                                        <th>Canal de destino</th>
                                                        <th>Ultima aplicación</th>
                                                        <th>Ultima data</th>
                                                        <th>Duración</th>
                                                        <th>Tiempo</th>
                                                        <th>Resultado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="detalleACD">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table text-center table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha hora</th>
                                                            <th>Cola</th>
                                                            <th>Agente</th>
                                                            <th>Evento</th>
                                                            <th>Datos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <h3 id="h3mio" style="color : rgb(110, 197, 255);">Ayuda</h3>

                                            <p>
                                                Eventos posibles:<br>

                                                ABANDON: Un cliente abandona una cola, los datos que trae son: (posición final | posición inicial| tiempo de espera).<br>
                                                COMPLETEAGENT: Llamada completada por un agente, los datos que trae son (tiempo espera | tiempo de llamada | posición de origen).<br>
                                                COMPLETECALLER: Llamada completada por un cliente, los datos que trae son (tiempo espera | tiempo de llamada | posición de origen).<br>
                                                CONNECT: Llamada conectada con un agente, los datos que trae son: (tiempo espera | canal de puente | uniqueid).<br>
                                                ENTERQUEUE: Una llamada entra a una cola, los datos que trae son: (url | callerid).<br>
                                                RINGNOANSWER: Llamada timbra y no contesta, los datos que trae son: (tiempo de timbra ms).<br>
                                                TRANSFER: Llamada transferida, los datos que trae son: (extensión | contexto | tiempo de espera | tiempo de llamada).<br>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="btnCancelar_2" data-dismiss="modal" type="button">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
     `;

     if($("#detalladoModal").length <= 0){
        $("body").prepend(detalladoModal);
     }


     const fillCallData = (data) => {
        $("#detFechaIni").html(data.fecha_hora);
        $("#detFechaFin").html(data.fecha_hora_final);
        $("#detAgente").html(data.agente_nombre);
        $("#detExt").html(data.extension);
        $("#detTel").html(data.numero_telefonico);
        $("#detTransfer").html(data.trans);
        $("#detCampan").html(data.campana);
        $("#detResultado").html(data.resultado);
        $("#detSentido").html(data.sentido);
        $("#detTotal").html(data.duracion);
        $("#detAire").html(data.al_aire);
        $("#detRedondeo").html(data.redondeo_minuto);
        $("#detEspra").html(data.espera);
        $("#detTimbr").html(data.timbrado);
        $("#detTroncal").html(data.troncal);
        $("#detIdLlamada").html(data.llamada_id_asterisk);
        $("#detTipo").html(data.tipo_llamada);
        $("#detCost").html(data.costo_llamada);
        $("#detExcep").html(data.disa);
        $("#detTag").html(data.etiqueta);
     }


     const fillPlantData = (data, idtelefonia) => {
        const tableBody = $("#detallePlanta tbody");
        let strhtml = "";
        console.log(strhtml);
        if(data.length > 0){
            data.forEach(element => {
                let buttonDownload = "";
                if(element.userfield != "" && element.userfield != undefined && element.userfield != null ){
                    buttonDownload = `<a class="btn button" href="https://<?=$_SERVER['SERVER_NAME']?>:8181/dyalogocore/api/voip/downloadrecord?tk=25L8cKxojzX5HFeXgy2L&uid=${idtelefonia}&uid2=${idtelefonia}&canal=telefonia" target="_blank"><i class='fas fa-download'></i></a>`;
                }

                strhtml += `
                    <tr>
                        <td>${buttonDownload}</td>
                        <td>${element.calldate}</td>
                        <td>${element.clid}</td>
                        <td>${element.src}</td>
                        <td>${element.dst}</td>
                        <td>${element.dcontext}</td>
                        <td>${element.channel}</td>
                        <td>${element.dstchannel}</td>
                        <td>${element.lastapp}</td>
                        <td>${element.lastdata}</td>
                        <td>${element.duration}</td>
                        <td>${element.billsec}</td>
                        <td>${element.disposition}</td>
                    </tr>`;
                });

        }else{
            strhtml += `<tr><td colspan="100%">No hay datos para mostrar!</td></tr>`;
        }

        tableBody.html(strhtml);

     }


     const fillACDData = (data) => {
        const tableBody = $("#detalleACD tbody");
        let strhtml = "";
        console.log(strhtml);
        if(data.length > 0){
            data.forEach(element => {
                strhtml += `
                    <tr>
                        <td>${element.fecha_hora}</td>
                        <td>${element.cola}</td>
                        <td>${element.agente}</td>
                        <td>${element.evento}</td>
                        <td>${element.datos}</td>
                    </tr>`;
                });

        }else{
            strhtml += `<tr><td colspan="100%">No hay datos para mostrar!</td></tr>`;
        }

        tableBody.html(strhtml);

     }

     $(".detalleButton a").click((e) => {
        e.preventDefault();
        const idTelefonia = $(e.target).attr("idtelefonia");
        $.ajax({
            type: "POST",
            url: "<?=base_url?>pages/charts/reportsViews/detalladoLlamada_CRUD.php?getData=true",
            data: {
                idTelephony: idTelefonia
            },
            dataType: "json",
            success: function (response) {
                fillCallData(response.call);
                fillPlantData(response.plant, idTelefonia);
                fillACDData(response.acd);
            }
        });
     })
     
</script>
<style>
    @media (min-width: 992px) {
        .modal-detallado {
            width: 1000px;
        }
    }
</style>