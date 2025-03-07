<link rel="stylesheet" href="<?=base_url?>assets/plugins/colorpicker/bootstrap-colorpicker.min.css">
<style type="text/css">
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

    <?php if(isset($_GET['report'])) :?>
        .skin-blue .wrapper {
            background-color: #fff !important;
        }
    <?php endif; ?>
    .select2-selection--multiple .select2-selection__choice{
        background-color: #3c8dbc !important;
    }

    .has-error .select2-selection--multiple{
        border-color: #dd4b39;
        box-shadow: none;
    }

</style>
<script type="text/javascript">
    function autofitIframe(id){
        if (!window.opera && document.all && document.getElementById){
            id.style.height=id.contentWindow.document.body.scrollHeight;
        } else if(document.getElementById) {
            id.style.height=id.contentDocument.body.scrollHeight+"px";
        }
    }
</script>

<?php
    
    $base_dir =  __DIR__;
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php";
   $url_crud_extender = base_url."cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php";
   $url_crud_herramientas = base_url."cruds/DYALOGOCRM_SISTEMA/G2/herramientas/POOEliminarDuplicados/index.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN


    $Zsql = "SELECT G2_ConsInte__b as id, G2_C7 as camp1 , TIPO_ESTRAT_Nombre____b as camp2 FROM ".$BaseDatos_systema.".G2 JOIN  ".$BaseDatos_systema.".TIPO_ESTRAT ON TIPO_ESTRAT_ConsInte__b = G2_C6 WHERE G2_C5 = ".$_SESSION['HUESPED']." ORDER BY G2_C7 ASC LIMIT 0, 50";
    

   $result = $mysqli->query($Zsql);

?>
<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_strategias_title ;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url?>index.php"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
<?php } ?>
        <?php if(!isset($_GET['report'])) :?>
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    
                </div>
            </div>
            <div class="box-body">
            <?php endif; ?>
                <div class="row">
                    <?php if(!isset($_GET['view'])){ ?>
                        <!-- SECCION LISTA NAVEGACIÓN -->
                        <div class="col-md-3" id="div_lista_navegacion">
                            <div class="input-group input-group-sm" style="width: auto;">
                                <input type="text" name="table_search_lista_navegacion" class="form-control input-sm pull-right" placeholder="<?php echo $str_busqueda;?>" id="table_search_lista_navegacion">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" id="BtnBusqueda_lista_navegacion"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <br/>
                            <!-- FIN BUSQUEDA EN LA LISTA DE NAVEGACION-->
                                
                            <!-- LISTA DE NAVEGACION -->
                            <div class="table-responsive no-padding" id="txtPruebas" style="height:553px; overflow-x:hidden; overflow-y:scroll;">
                                <table class="table table-hover" id="tablaScroll">
                                    
                                    <?php
                    
                                        while($obj = $result->fetch_object()){
                                            echo "<tr class='CargarDatos' id='".url::urlSegura($obj->id)."'>
                                                    <td>
                                                        <p style='font-size:14px;'><b>".$obj->camp1."</b></p>
                                                        <p style='font-size:12px; margin-top:-10px;'>".$obj->camp2."</p>
                                                    </td>
                                                </tr>";
                                        } 
                                    ?>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-9" id="div_formularios">
                            <!-- SECCION BOTONES -->
                            <div>
                                <button class="btn btn-default" id="add">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button class="btn btn-default"  id="delete" >
                                    <i class="fa fa-trash"></i> 
                                </button>
                                <button class="btn btn-default" id="edit" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-default" id="Save" disabled>
                                    <i class="fa fa-save"></i>
                                </button>
                                <button class="btn btn-default" id="cancel" disabled>
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                    <?php }else{ ?>
                        <div class="col-md-12" id="div_formularios">
                            <?php if(!isset($_GET['report'])) :?>
                                <div>
                                    <button class="btn btn-default" id="Save" >
                                        <i class="fa fa-save"></i>
                                    </button>
                                    <button class="btn btn-default" id="cancel" >
                                        <i class="fa fa-close"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                    <?php } ?>
                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Reportes Automaticos -->
                                        <?php if(!isset($_GET['report'])) :?>
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_resumen_estrategia">
                                                        <?php echo $str_Estadistica_Estrat_title;?>
                                                    </a>
                                                </h3>
                                                <div class="box-tools">
                                                    
                                                </div>
                                            </div>
                                            <div id="s_resumen_estrategia" class="panel-collapse ">
                                            <div class="box-body">
                                                <div class="row">
                                                    <label for="G2_C7" id="LblG2_C7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOMBRE</label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-12 col-xs-12">
                                                                    <!-- CAMPO TIPO TEXTO -->
                                                                    <div class="form-group">
                                                                        
                                                                        <input type="text" class="form-control input-sm" id="G2_C7" value=""  name="G2_C7"  placeholder="NOMBRE">
                                                                    </div>
                                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                            </div>
                                                            <input type="hidden" name="G2_C5" id="G2_C5" value="<?php echo $_SESSION['HUESPED'];?>">
                                                            <div class="col-md-4 col-xs-4">
                                                                <!-- CAMPO TIPO ENTERO -->
                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                <div class="form-group">
                                                                    <label for="G2_C6" id="LblG2_C6">TIPO ESTRATEGIA</label>
                                                                    <select class="form-control "  name="G2_C6" id="G2_C6">
                                                                    <?php

                                                                        $tipoStratLsql = "SELECT * FROM ".$BaseDatos_systema.".TIPO_ESTRAT";
                                                                        $tipoStratResu = $mysqli->query($tipoStratLsql);

                                                                        while ($tipoStrat = $tipoStratResu->fetch_object()) {
                                                                            echo "<option value='".$tipoStrat->TIPO_ESTRAT_ConsInte__b."'>".($tipoStrat->TIPO_ESTRAT_Nombre____b)."</option>";
                                                                        }

                                                                    ?>
                                                                    </select>
                                                                </div>
                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                            </div>
                                                            
                                                            <div class="col-md-4 col-xs-4">
                                                                <div class="form-group">
                                                                    <label>COLOR</label>

                                                                    <div class="input-group my-colorpicker2">
                                                                        <input name="G2_C14" size="1" id="G2_C14" type="text" class="form-control">
                                                                        <div class="input-group-addon">
                                                                            <i id="str_IdColor"></i>
                                                                        </div>
                                                                        <input type="hidden" name="txtOcultoColor" id="txtOcultoColor" value="">
                                                                    </div>
                                                                    <!-- /.input group -->
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 col-xs-4">
                                                                 <div class="form-group">
                                                                    <label>BASE DE DATOS</label>
                                                                    <select class="form-control" name="G2_C69" id="G2_C69" >
                                                                        <option value="0">SELECCIONE</option>
                                                                        <?php 
                                                                            $Lsql = "SELECT  G5_ConsInte__b , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 2 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";

                                                                            $cur_GUiones = $mysqli->query($Lsql);
                                                                            while ($key = $cur_GUiones->fetch_object()) {
                                                                                echo "<option value='".$key->G5_ConsInte__b."'>".strtoupper($key->G5_C28)."</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                 </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-3" id="flujoEstrat" style="cursor: pointer;">
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

                                                            <div class="info-box-content">
                                                              <span class="info-box-text">CONFIGURAR</span>
                                                              <span class="info-box-number">ESTRATEGIA</span>
                                                            </div>
                                                            <!-- /.info-box-content -->
                                                          </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-4">
                                                        <!-- CAMPO TIPO MEMO -->
                                                        <div class="form-group">
                                                            <label for="G2_C8" id="LblG2_C8">COMENTARIO</label>
                                                            <textarea class="form-control input-sm" name="G2_C8" id="G2_C8"  value="" placeholder="COMENTARIO"></textarea>
                                                        </div>
                                                        <input type="hidden" name="IdEstrat" id="IdEstrat">
                                                    </div>  
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <!--  REPORTES-->
                                        <?php if(!isset($_GET['report'])) :?>
                                        <div class="panel box box-primary box-solid" <?php if(isset($_SESSION['no_admin'])){ echo "hidden"; } ?>>
                                            <div class="box-header with-border">
                                                <h3 class="box-title" id="SecReportes">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_no_limit">REPORTES EN PANTALLA</a>
                                                </h3>
                                            </div>
                                        <?php endif; ?>
                                            <div id="s_no_limit" <?php if(!isset($_GET['report'])){echo 'class="panel-collapse collapse"';}?>>
                                                <div class="box-body" id="fechasPivot">
                                                    <div class="row">
                                                        <div class="col-md-10" id="divSelGraficas" hidden>
                                                            <div class="form-group">
                                                                <label>GRAFICAS</label>
                                                                <select class="form-control" id="selGraficas" placeholder="Your query" name="selGraficas">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8 col-sm-7" id="divSelReportes">
                                                                <div class="form-group">
                                                                    <label><?php echo $str_title_reportes_campan; ?></label>
                                                                    <select class="form-control" id="sql_query" placeholder="Your query" name="sql_query">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-xs-6 col-sm-3">
                                                                <div class="form-group">
                                                                    <label for="generarReporte">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                                    <button id="generarReporte" type="button" class="form-control btn btn-primary" onclick="Reportes()">Generar reporte</button>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-xs-6 col-sm-2" id="divBtnExport">
                                                                <label for="btnExport">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                                <button id="btnExport" type="button" onclick="exportarReporte()" class="form-control btn btn-info">Exportar
                                                                    <li class="fa fa-file-excel-o"></li>
                                                                </button>
                                                            </div>
                                                    </div> 
                                                </div>

                                                            <div class="panel box box-primary" id="secDivFiltros">
                                                                <div class="row">
                                                                    <div class="col-md-6 col-xs-6">
                                                                        <input type="hidden" name="inpCantFiltros" id="inpCantFiltros" value="1">
                                                                        <input type="hidden" name="inpFiltroAvanAct" id="inpFiltroAvanAct" value="0">
                                                                        <textarea hidden id="jsonCampos" name="jsonCampos"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div id="busquedaAvanzada" class="panel-collapse collapse in">

                                                                    <div class="box-body"> 
                                                                        <div id="divFiltros">
                                                                        <div class="row margin-bottom">
                                                                    <div class="col-md-12">
                                                                        <div class="col-md-8 col-xs-12 col-sm-8 " id="labelNotas"></div>
                                                                        <!-- [button] Condicion -->
                                                                        <div class="col-md-2 col-xs-6 col-sm-2 pull-right">
                                                                            
                                                                            <button class=" form-control btn btn-success" id="btnNuevoFiltro" type="button" onclick="NuevaCondicion()">
                                                                                <i class="fa fa-plus">&nbsp;&nbsp;&nbsp;&nbsp;Condición&nbsp;&nbsp;&nbsp;&nbsp;</i>
                                                                            </button>
                                                                            
                                                                        </div>
                                                                        <!-- [button] Reiniciar condicion -->
                                                                        <div class="col-md-2 col-xs-6 col-sm-2 pull-right">
                                                                           
                                                                            <button class=" form-control btn btn-warning" id="resetFiltradorAvanzado" type="button" onclick="clearFiltros(false)">
                                                                                <i class="fa fa-refresh">&nbsp;&nbsp;&nbsp;&nbsp;Reiniciar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
                                                                            </button>
                                                                           
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                        <div class="col-md-1 col-xs-2 col-sm-1">
                                                                            <div class="form-group">
                                                                                <label>APERTURA</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-5 col-sm-4">
                                                                            <div class="form-group">
                                                                                <label>CAMPO</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 col-xs-2 col-sm-2">
                                                                            <div class="form-group">
                                                                                <label>OPERADOR</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 col-xs-3 col-sm-3">
                                                                            <div class="form-group">
                                                                                <label>VALOR</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1 col-xs-2 col-sm-1">
                                                                            <div class="form-group">
                                                                                <label>CIERRE</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row rows" id="row_1" numero="1">
                                                                        <div class="col-md-1 col-xs-2 col-sm-1">
                                                                            <div class="form-group">
                                                                                <select class="form-control input-sm condApertura" name="selCondicion_1" id="selCondicion_1" numero="1">
                                                                                    <option value=" "></option>
                                                                                    <option value="(">&#40</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-5 col-sm-4">
                                                                            <div class="form-group">
                                                                                <select class="form-control input-sm campoFiltro" name="selCampo_1" id="selCampo_1" numero="1">
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 col-xs-2 col-sm-2">
                                                                            <div class="form-group">
                                                                                <select class="form-control input-sm" name="selOperador_1" id="selOperador_1">
                                                                                    <option value="0">Seleccione</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 col-xs-2 col-sm-3">
                                                                            <div class="form-group" id="divValor_1">
                                                                                <input type="text" class="form-control input-sm campoValor" id="valor_1" name="valor_1" placeholder="VALOR">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 col-xs-2 col-sm-2">
                                                                            <div class="form-group">
                                                                                <select class="form-control input-sm condCierre" name="cierre1" id="cierre1" numero="1">
                                                                                    <option value=""></option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <!-- <div class="col-md-2 col-xs-2 btn-group pull-right">
                                                                                    <div class="form-group">
                                                                                        <button class="btn btn-success" id="btnNuevoFiltro" type="button" onclick="NuevaCondicion()">
                                                                                            <i class="fa fa-plus">&nbsp;&nbsp;&nbsp;&nbsp;Condicion&nbsp;&nbsp;&nbsp;&nbsp;</i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div> -->
                                                                        <input type="hidden" id="tipo_1" name="tipo_1" value="0">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                        <div class="col-md-12">
                                                                            <h3>Previsualizacion</h3>
                                                                            <p id="textoPrevisualizacion"></p>
                                                                            <span style="color:orange" id="errorCondiciones"></span>
                                                                        </div>
                                                                    </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                <ul class="nav nav-tabs">

                                                    <input type="hidden" id="secTipoReporte" value="0">

                                                    <li class="active">
                                                        <a href="#resultados" data-toggle="tab" id="reporte_tabla" class="reportesPantalla REPORTES" tipo="1">TABLA</a>
                                                    </li>

                                                    <li class="">
                                                        <a href="#tabs_1" data-toggle="tab" id="reporte_pivot" class="reportesPantalla REPORTES" tipo="2">TABLA DINAMICA</a>
                                                    </li>

                                                    <?php if(!isset($_GET["stepUnique"])) : ?>

                                                    <li class="">
                                                        <a href="#tabs_2" data-toggle="tab" id="reporte_grafico" class="reportesPantalla REPORTES" tipo="3">GRAFICAS</a>
                                                    </li>

                                                    <?php endif; ?>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" style="overflow-x: scroll;" id="resultados">

                                                    </div>

                                                    <div class="tab-pane " style="overflow-x: scroll;" id="tabs_1">

                                                        <div id="pivotTable">
                                                            
                                                        </div>

                                                    </div>

                                                    <div class="tab-pane " style="overflow-x: scroll;" id="tabs_2">

                                                        <div class="box-body" id="lienzoGeneral">
                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaBD_1"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaBD_2"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>

                                                            </div>
                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaBD_3"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaBD_4"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="box-body" id="lienzoCampanas">
                                                            <div class="row">

                                                                <div class="col-xs-12">
                                                                    <div class="box-body">
                                                                        <table id="tabInOut" class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Gestiones</th>
                                                                                    <th>No contactable</th>
                                                                                    <th>Devoluciones</th>
                                                                                    <th>Sin gestion</th>
                                                                                    <th>No contactado</th>
                                                                                    <th>Contactado</th>
                                                                                    <th>No efectivo</th>
                                                                                    <th>Efectivo</th>
                                                                                    <th>Duracion</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td id="td_gestiones" class="tdInOut">0</td>
                                                                                    <td id="td_no_contactable" class="tdInOut">0</td>
                                                                                    <td id="td_devoluciones" class="tdInOut">0</td>
                                                                                    <td id="td_sin_gestion" class="tdInOut">0</td>
                                                                                    <td id="td_no_contactado" class="tdInOut">0</td>
                                                                                    <td id="td_contactado" class="tdInOut">0</td>
                                                                                    <td id="td_no_efectivo" class="tdInOut">0</td>
                                                                                    <td id="td_efectivo" class="tdInOut">0</td>
                                                                                    <td id="td_duracion" class="tdInOut">0</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table id="tabInIn" class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Ofrecidas</th>
                                                                                    <th>ASA</th>
                                                                                    <th>Aban</th>
                                                                                    <th>Aban_despues_tsf</th>
                                                                                    <th>Aban_porcentaje</th>
                                                                                    <th>Contestadas</th>
                                                                                    <th>Cont_despues_tsf</th>
                                                                                    <th>Cont_porcentaje</th>
                                                                                    <th>TSF</th>
                                                                                    <th>AHT</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td id="td_ofrecidas" class="tdInIn">0</td>
                                                                                    <td id="td_asa" class="tdInIn">0</td>
                                                                                    <td id="td_aban" class="tdInIn">0</td>
                                                                                    <td id="td_aban_despues_tsf" class="tdInIn">0</td>
                                                                                    <td id="td_aban_porcentaje" class="tdInIn">0</td>
                                                                                    <td id="td_contestadas" class="tdInIn">0</td>
                                                                                    <td id="td_cont_despues_tsf" class="tdInIn">0</td>
                                                                                    <td id="td_cont_porcentaje" class="tdInIn">0</td>
                                                                                    <td id="td_tsf" class="tdInIn">0</td>
                                                                                    <td id="td_aht" class="tdInIn">0</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">

                                                                <div class="col-md-12">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaSC_1"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>

                                                            </div>
                                                            <div class="row">

                                                                <div class="col-md-12">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaSC_2"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-md-12">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaSC_3"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>

                                                            </div>

                                                            <div class="row">

                                                                <div class="col-md-12">
                                                                    
                                                                    <figure class="highcharts-figure">
                                                                        <div id="graficaSC_4"></div>
                                                                        <p class="highcharts-description">
                                                                        </p>
                                                                    </figure>

                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php if(!isset($_GET['report'])) :?>
                                        </div>
                                        <?php endif; ?>

                                        <?php if(!isset($_GET['report'])) :?>
                                        <div class="panel box box-primary box-solid" <?php if(isset($_SESSION['no_admin'])){ echo "hidden"; } ?>>
                                            <div class="box-header with-border">
                                                <h3 class="box-title" id="SecReportesEmail">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_envio_reportes">
                                                        <?php echo $str_strategia_reporte;?>
                                                    </a>
                                                </h3>
                                                <div class="box-tools">
                                                    
                                                </div>
                                            </div>
                                            <div id="s_envio_reportes" class="panel-collapse collapse">
                                                <div class="box-header with-border">
                                                    <div class="row col-sm-offset-4 col-md-offset-3 col-lg-offset-5 ">
                                                        
                                                        <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4 ">
                                                            <button class="form-control btn btn-warning" title="<?php echo $mashorasdeenvio; ?>" type="button" id="envioAdherencia">
                                                                <i class="fa fa-plus"></i> ADHERENCIAS
                                                            </button>
                                                        </div>
                                                        <div class="form-group col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                            <button class="form-control btn btn-primary" title="<?php echo $mashorasdeenvio; ?>" type="button" id="horasEnvio">
                                                                <i class="fa fa-plus"></i> 
                                                                REPORTES
                                                            </button>
                                                        </div>
                                                        <div class="col-xs-4 col-sm-3 col-md-4 col-lg-4">
                                                            <button onClick="EnvioReporte()" class="form-control btn btn-success" type="button" id="EnviarReporte">
                                                            <i class="fa fa-envelope-open-o" aria-hidden="true"></i> ENVIAR REPORTES
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="box-body" id="reportesGenerados">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        
                                        <?php // echo $_SESSION['CARGO'] === "super-administrador" ? "" : "hidden" ?>
                                        
                                        
                                        <div>
                                            <?php $file = "{$base_dir}/herramientas/viewHerramientas.php";
                                            // echo "ruta => {$file}";
                                            include_once($file); ?>
                                        </div>




                                        <div class="panel box box-primary" hidden>
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#Metas">
                                                        <?php echo $str_strategia_meta2;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="Metas" class="panel-collapse collapse ">
                                                <div class="box-body" id="metasVanAqui">
                                                   
                                                    
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="panel box box-primary" hidden>
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#Flujograma">
                                                        <?php echo $str_strategia_flujo;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="Flujograma" class="panel-collapse collapse in">
                                                <div class="box-body">
                                                    <div class="row" id="sample">
                                                        <div class="col-md-12" style="width:100%; white-space:nowrap; cursor: pointer;">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <span style="display: inline-block; vertical-align: top; width:100%">
                                                                        <a disabled id="diagramaIndex">
                                                                            <div id="myDiagramDiv" style="height: 750px;"></div>
                                                                        </a>
                                                                    </span>
                                                                </div>                                                             
                                                            </div>
                                                            <br/>
                                                            <div class="row" style="display: none;">
                                                                <div class="col-md-4 col-xs-4">                         
                                                                    <!-- CAMPO TIPO MEMO -->
                                                                    <div class="form-group">
                                                                        <label for="G2_C9" id="LblG2_C9">FLUJOGRAMA</label>
                                                                        <textarea class="form-control input-sm" name="G2_C9" id="G2_C9"  value="" placeholder="FLUJOGRAMA"></textarea>
                                                                    </div>
                                                                    <!-- FIN DEL CAMPO TIPO MEMO -->
                                                                   <div class="form-group" style="display: none;">
                                                                    <textarea id="mySavedModel" class="form-control">
                                                                    {
                                                                        "class": "go.GraphLinksModel",
                                                                        "linkFromPortIdProperty": "fromPort",
                                                                        "linkToPortIdProperty": "toPort",
                                                                        "nodeDataArray": [

                                                                        ],
                                                                        "linkDataArray": [

                                                                        ]
                                                                    }
                                                                    </textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <!-- Reportes -->
                                        
                                        <!-- SECCION : PAGINAS INCLUIDAS -->
                                        <input type="hidden" name="id" id="hidId" value='0'>
                                        <input type="hidden" name="oper" id="oper" value='add'>
                                        <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if(!isset($_GET['report'])) :?>
            </div>
        </div>
        <?php endif; ?>
<?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>

<form action="<?=base_url?>pages/charts/report.php" method="post" id="export-form">
    <input type="hidden" name="tipoReport_t" id="tipoReport_t" value="">
    <input type="hidden" name="strFechaIn_t" id="strFechaIn_t" value="">
    <input type="hidden" name="strFechaFn_t" id="strFechaFn_t" value="">
    <input type="hidden" name="intIdHuesped_t" id="intIdHuesped_t" value="">
    <input type="hidden" name="intIdEstrat_t" id="intIdEstrat_t" value="">
    <input type="hidden" name="intIdGuion_t" id="intIdGuion_t" value="">
    <input type="hidden" name="intIdCBX_t" id="intIdCBX_t" value="">
    <input type="hidden" name="intIdPeriodo_t" id="intIdPeriodo_t" value="">
    <input type="hidden" name="intIdBd_t" id="intIdBd_t" value="">
    <input type="hidden" name="intIdPaso_t" id="intIdPaso_t" value="">
    <input type="hidden" name="intIdTipo_t" id="intIdTipo_t" value="">
    <input type="hidden" name="intIdMuestra_t" id="intIdMuestra_t" value="">
    <input type="hidden" name="strLimit_t" id="strLimit_t" value="">
    <input type="hidden" name="Exportar" id="Exportar" value="si">
    <input type="hidden" name="NombreExcell" id="NombreExcell" value="">
    <input type="hidden" name="middleware" id="middleware" value="">
    <input hidden name="jsonCondiciones" id="jsonCondiciones" value="">
    <input hidden name="jsonTotalFil" id="jsonTotalFil" value="">
</form>

<div class="modal fade-in" id="crearCampanhasNueva" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Nueva Estrategia</h4>
            </div>
            <div class="modal-body">
                <form id="formuarioCargarEstoEstrart">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="G2_C7" id="LblG2_C7">NOMBRE</label>
                                <input type="text" class="form-control input-sm" id="G2_C7_modal" value=""  name="G2_C7"  placeholder="NOMBRE">
                            </div>
                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                        <input type="hidden" name="G2_C5" id="G2_C5_modal" value="<?php echo $_SESSION['HUESPED'];?>">
                    </div>
                </form>
                <form id="formuarioCargarEsto" enctype="multipart/form-data">
                    <div class="row">
                        <input type="hidden"  class="form-control input-sm Numerico" value="<?php echo $_SESSION['HUESPED'] ;?>"  name="G10_C70" id="G10_C70" placeholder="HUESPED">
                        <input type="hidden"  class="form-control input-sm Numerico" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo 0; } ;?>"  name="id_paso" id="id_estpas_mio" placeholder="HUESPED">
                        <input type="hidden" name="G10_C72" value="-1">
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <?php 
                                $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 2 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
                            ?>
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="G10_C74" id="LblG10_C74">BASE DE DATOS</label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C74" id="G10_C74">
                                    <option value="0">Seleccione</option>
                                    <?php
                                        /*
                                            SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                        */
                                        $combo = $mysqli->query($str_Lsql);
                                        while($obj = $combo->fetch_object()){
                                            echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G5_C28)."</option>";

                                        }    
                                        
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" value=""  name="G10_C75"  >
                            <input type="hidden" name="G10_C71" id="G10_C71">
                            <!-- FIN DEL CAMPO TIPO LISTA -->
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="GenerarFromExel" id="GenerarFromExel" value="1">&nbsp;<?php echo $str_generar_excel_;?>
                                </label>
                            </div>
                        </div> -->

                        <!-- <div class="col-md-12 excel" style="display: none;" >
                            <div class="form-group">
                                <label><?php echo $str_archivr_excel_;?></label>
                                <input type="file" name="newGuionFile" id="newGuionFile" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12" style="display: none;" id="aja">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="insertarDataBase" id="insertarDataBase" value="1" checked="true">&nbsp;<?php echo $str_importr_excel_;?>
                                </label>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <button class="btn btn-default regresoCampains" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
                <button class="btn-primary btn pull-right" type="button" id="btnSave_Estrat">
                    <?php echo $str_guardar;?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Loading -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalLoading">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><strong>Envio De Reporte</strong></h5>
        </div>
        <div class="modal-body">
            <!-- loading -->
            <div id="Loading" class="container-loader">
                <div class="loader">
                    <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -20%; margin-left: 5%; color: #11D2FD;">
                    <p class="form-label text-black" style="margin-top: -20%; margin-left: 32%;"><strong> ENVIANDO ... </strong></p>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>



<script src="<?=base_url?>assets/plugins/Flowchart/flowchart.js"></script>
<script src="<?=base_url?>assets/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

<!--script para manipular los reportes -->
<script type="text/javascript">
    // validad si es un reporte en un iframe o no 
    <?php if ( !isset($_GET['report']) ): ?>
    var first_load = true;
    <?php else : ?>
    var first_load = false;
    <?php endif ?>
    


    $(".esconderFechasPivot").click(function(){
        $("#fechasPivot").attr("hidden",true);
    });

    $(".reportesPantalla").click(function(){

        $("#divSelReportes").show();
        $("#divBtnExport").show();
        $("#divSelGraficas").hide();

        var intTipoSecReporte_t = $(this).attr("tipo");

        if (($("#secTipoReporte").val() == "3" && intTipoSecReporte_t != "3") || ($("#secTipoReporte").val() != "3" && intTipoSecReporte_t == "3")) {

            clearFiltros(true);

            creacionFiltroDinamico(intTipoSecReporte_t);

        }

        if (intTipoSecReporte_t == "2") {

            $("#generarReporte").attr("onclick","Pivot()");

        }else if ($(this).attr("tipo") == "3") {

            $("#divSelReportes").hide();
            $("#divSelGraficas").show();
            $("#divBtnExport").hide();
            $("#generarReporte").attr("onclick","dibujarGraficas()");

        }else{

            $("#generarReporte").attr("onclick","Reportes()"); 

        }

        $("#secTipoReporte").val(intTipoSecReporte_t);

    });

    changeCampoFiltro();

    /**
    * JDBD-2020-05-03 : Esta funcion hace que al precionar el boton rojo del filtrador avanzado elimine la fila del filtrdor avanzado
    * mas los campos de operacion de la misma fila donde esta el boton rojo precionado.
    */
    function eliminarFiltro(){

        $(".EliminarFiltro").click(function(){

            var intNumCampo_t = $(this).attr("numero");

            $("#row_"+intNumCampo_t).remove();

        });

    }

    function quitarClaseError(){

        $(".campoValor").change(function(){

            $(this).closest(".form-group").removeClass("has-error");

        });

    }

    quitarClaseError();

    function NuevaCondicion(){

            var intCantFiltros = Number($("#inpCantFiltros").val())+1;

            var intIdFormulario_t = $("#inpIdFormulario").val();

            //JDBD-2020-05-03 : Armamos el HTMML para las filas del filtrador avanzado que vamos añadiendo con el boton verde "Nuevo Filtro"; 
            var strHTML_t = ''; 
            strHTML_t += '<div class="row rows" id="row_'+intCantFiltros+'" numero="'+intCantFiltros+'">';
                strHTML_t += '<div class="col-md-1 col-xs-2 col-sm-1">';
                    strHTML_t += '<div class="form-group">';
                        strHTML_t += '<select class="form-control input-sm condApertura" name="selCondicion_'+intCantFiltros+'" id="selCondicion_'+intCantFiltros+'" numero="'+intCantFiltros+'">';
                            strHTML_t += '<option value="AND"><?php echo $str_Filtro_AND__________;?></option>';
                            strHTML_t += "<option value=' AND ('><?php echo $str_Filtro_AND__________;?> &#40</option>";
                            strHTML_t += '<option value="OR"><?php echo $str_Filtro_OR___________;?></option>';
                            strHTML_t += "<option value=' OR ('><?php echo $str_Filtro_OR___________;?> &#40</option>";
                        strHTML_t += '</select>';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-4 col-xs-7 col-sm-4">';
                    strHTML_t += '<div class="form-group">';
                        strHTML_t += '<select class="form-control input-sm campoFiltro" name="selCampo_'+intCantFiltros+'" id="selCampo_'+intCantFiltros+'" numero="'+intCantFiltros+'">';

                            let strJSON_Campos_t = $("#jsonCampos").val();
                            let arrJSON_Campos_t = ""
                            strJSON_Campos_t === "" ? strJSON_Campos_t : arrJSON_Campos_t = JSON.parse(strJSON_Campos_t) 

                            strHTML_t += '<option value="0" tipo="3">Seleccione</option>';

                            $.each(arrJSON_Campos_t, function(index,value) {


                                let printCampan = '<?php 
                                    if (isset($_GET["stepUnique"])) {
                                        if ($_GET["stepUnique"] == "campan" || $_GET["stepUnique"] == "campanout") {
                                            echo 'pasoCampan="'.$_GET["paso"].'"'; 
                                        }
                                    } else {
                                        echo "";
                                    } ?>';

                                strHTML_t += '<option value="'+value.campoId+'" campoLista="'+value.campoLista+'" tipo="'+value.tipo+'" idBG="'+value.idbg+'" idPregun="'+value.idpregun+'" '+printCampan+' >'+value.nombre+'</option>';

                            });

                        strHTML_t += '</select>';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-2 col-xs-4 col-sm-2">';
                    strHTML_t += '<div class="form-group">';
                        strHTML_t += '<select class="form-control input-sm" name="selOperador_'+intCantFiltros+'" id="selOperador_'+intCantFiltros+'">';
                        strHTML_t += '<option value="0">Seleccione</option>';
                        strHTML_t += '</select>';
                strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-3 col-xs-3 col-sm-3">';
                    strHTML_t += '<div class="form-group" id="divValor_'+intCantFiltros+'">';
                        strHTML_t += '<input type="text" class="form-control input-sm campoValor" id="valor_'+intCantFiltros+'" name="valor_'+intCantFiltros+'" placeholder="VALOR">';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-1 col-xs-1 col-sm-1">';
                    strHTML_t += '<div class="form-group" id="divCierre_'+intCantFiltros+'">';
                        strHTML_t += '<select class="form-control input-sm condCierre" name="cierre'+intCantFiltros+'" id="cierre'+intCantFiltros+'" numero="'+intCantFiltros+'">';
                            strHTML_t += '<option value=""></option>';
                        strHTML_t += '</select>';
                    strHTML_t += '</div>';
                strHTML_t += '</div>';
                strHTML_t += '<div class="col-md-1 col-xs-2 col-sm-2 ">';
                    strHTML_t += '<button class="form-control btn btn-danger btn-sm EliminarFiltro" type="button" id="btnQuitarFiltro_'+intCantFiltros+'" numero="'+intCantFiltros+'"><i class="fa fa-trash-o"></i></button>';
                strHTML_t += '</div>';
                strHTML_t += '<input type="hidden" id="tipo_'+intCantFiltros+'" name="tipo_'+intCantFiltros+'" value="0">';
            strHTML_t += '</div>';

            $("#divFiltros").append(strHTML_t);

            // $("#selCampo_"+intCantFiltros).select2();

            $("#inpCantFiltros").val(Number($("#inpCantFiltros").val())+1);

            //JDBD-2020-05-03 : Le damos funcionalidad de eliminacion a los botones rojos de las filas nuevas que vamos añadiendo con el boton verde "Nuevo Fitro"
            eliminarFiltro();

            //JDBD-2020-05-03 : Le damos funcionalidad a las listas CAMPO de las nuevas filas del filtrador avanzado que vamos añadiendo con el boton verde "Nuevo Filtro", para que altero los input del filtrador avanzada "OPERADOR" y "VALOR".
            changeCampoFiltro();
            quitarClaseError();

            //Adiciono el evento para calcular los parentesis y ademas ejecutamos de una vez
            changeCampoApertura();
            cambioEtiquetaApertura();
        
    }

    /**
    * BGCR : Esta funcion adiciona el evento change a todos los campos de apertura nuevos
    */
    function changeCampoApertura(){
        $(".condApertura").change(function(){
            cambioEtiquetaApertura()
        })
    } 

    /**
    * BGCR : Esta funcion adiciona el evento change a todos los campos de los filtros, para renderizar la previsualizacion
    */
    function changeRenderizado() {
        $("#divFiltros :input:not('button')").not('input[id^="tipo_"]').change(function () {
            renderizarCondiciones();
        })
      }

    // Esta funcion ayuda a calcular los parentesis que se pueden cerrar dependiendo de los que hayan abiertos
    function cambioEtiquetaApertura(){

        var cantEtiquetaApertura = 0;

        let valoresActuales = [];

        $(".condApertura").each(function(){
            if($(this).val() == '(' || $(this).val() == ' AND (' || $(this).val() == ' OR ('){
                cantEtiquetaApertura++;
            }

            // Almaceno los valores de cierre
            let num = $(this).attr('numero');
            valoresActuales.push({ key : num, value : $("#cierre"+num).val()});
        });

        let opciones = '<option value=""></option>';
        let cantCierre = '';
        let txtCierre = '';

        // le agrego la opcion de cierre
        for (let i = 0; i < cantEtiquetaApertura; i++) {
            cantCierre += ' ) ';
            txtCierre += '&#41';
            opciones += `<option value="${cantCierre}">${txtCierre}</option>`;
        }

        $(".condCierre").html(opciones);

        // console.log("cantApertura = " + cantEtiquetaApertura);

        // console.log(valoresActuales);

        if(cantEtiquetaApertura > 0){
            for (var item in valoresActuales) {
                if($("#cierre"+valoresActuales[item].key+" option[value='"+valoresActuales[item].value+"']").length > 0){
                    $("#cierre"+valoresActuales[item].key).val(valoresActuales[item].value);
                }else{
                    $("#cierre"+valoresActuales[item].key).val('');
                }
            }
        }
    }

    function renderizarCondiciones(){

        let texto = '';
        let newCuantosvan = Number($("#inpCantFiltros").val())+1;

        for (let index = 1; index < newCuantosvan; index++) {

            if($("#divFiltros div#row_"+index).length){

                let apertura = '<b>'+$("#selCondicion_"+index+" option:selected").html()+'</b>';
                let pregunta = $("#selCampo_"+index+" option:selected").html();
                let condicion = '<i>'+$("#selOperador_"+index+" option:selected").text()+'</i>';
                let cierre = '<b>'+$("#cierre"+index).val()+'</b>';
                let valor = '';

                // Para el valor tengo que validar la tipo de pregunta
                let tipoPregunta = $("#selCampo_"+index+" option:selected").attr('tipo');
                if(tipoPregunta == 'ListaCla' || tipoPregunta == '8' || tipoPregunta == '_Activo____b' || tipoPregunta == '_Estado____b' || tipoPregunta == '6' || tipoPregunta == '11' || tipoPregunta == '_ConIntUsu_b' || tipoPregunta == 'MONOEF' || tipoPregunta == '_CanalUG_b'){
                    valor = $("#valor_"+index+" option:selected").html();
                }else{
                    valor = $("#valor_"+index).val();
                }
                
                texto += ` ${apertura} ${pregunta} ${condicion} ${valor} ${cierre} `;
            }
        }

        $("#textoPrevisualizacion").html('Condicion cuando ' + texto);

        if(!ValidarParentesis(texto)){
            $("#generarReporte").attr("disabled",true);
            $("#btnExport").attr("disabled",true);
            $("#errorCondiciones").html('Los parentesis estan mal configurados, hay mas abiertos o cerrados');
        }else{
            $("#generarReporte").attr("disabled",false);
            if(!$("#btnExport").hasClass("filtroObligatorio")){
                $("#btnExport").attr("disabled",false);
            }
            $("#errorCondiciones").html('');
        }

    }

    /**
    * BGCR : Esta funcion valida que se hayan cerrado todos los parentesis de una cadena
    */
    function ValidarParentesis(s) {
        var i;
        var va = 0;
        for (i = 0; i < s.length; i++){
            if(s.substring(i,i+1) == '('){
                va++;
            }
            else if(s.substring(i,i+1) == ')'){
                va--;
            }
            
            if (va < 0){
                return false;
            }
        }

        //si va no termina en cero la expresión está incorrectamente parentisada
        return va == 0;
    }


    /* Esta funcion ayuda a validar si se tiene la misma columna seleccion varias veces, y por defecto pone la apertura con un OR
    *@param idPregun - id del campo pregun
    *@param numero - numero de filtro
    */
    function validarAperturaPorDefecto(idPregun, numero) {

        // Validamos si no se tiene algun otro filtro con el mismo campo

        $(".campoFiltro").each((indexPregun, elementPregun) => {
            let idNumeroPregun = $(elementPregun).attr("numero");
            // Si en los filtros hay un campo repetido por defecto ponemos en la condicion un OR
            if(idNumeroPregun != numero && numero != 1){
                if($(elementPregun).val() == idPregun){
                    $("#selCondicion_"+numero).val("OR").change();
                }
            }

        })
    }


    /**
    * JDBD-2020-05-03 : Se trae las opciones de la lista seleccionada en el select de campos.
    * @param Integer - Id de el campo en la tabla PREGUN.
    * @return HTML - Se trae las opcicones en formato html de las opciones del campo tipo lista. 
    */
    function traerOpcionesLista(intIdCampo_p, strEspecial_p = null, strIdPasoCampan = null, strValue = null){

        // Necesito enviar la estrategia
        let estrategia = $("#hidId").val();

        let objData = {intIdCampo_t : intIdCampo_p, strEspecial_t : strEspecial_p, strValue: strValue, strEstrategia_t : estrategia};
        if(strIdPasoCampan != undefined || strIdPasoCampan != null){
            console.log(`paso 1 ${strIdPasoCampan}`);
            objData["intPasoCampan"] = parseInt(strIdPasoCampan);
            if (isNaN(objData["intPasoCampan"])) {
                console.log(`paso 2 ${strIdPasoCampan}`);
                objData["intPasoCampan"] = strIdPasoCampan;
            }
        };

        var strHTMLOpcionesLista_t = $.ajax({
                                        url      : '<?=$url_crud;?>?traerOpcionesLista=true',
                                        type     : 'POST',
                                        data     : objData,
                                        dataType : 'html',
                                        context  : document.body,
                                        global   : false,
                                        async    :false,
                                        success  : function(data) {
                                            return data;
                                        }
                                    }).responseText;

        return strHTMLOpcionesLista_t;

    }

    /**
    * JDBD-2020-05-03 : Esta funcion afecta los campos OPERADOR y VALOR del filtrador avanzado dependiendo el tipo de campo seleccionado en la lista de CAMPO,
    * cuando el campo seleccionado en la lista de CAMPO es de tipo lista convierte el input de VALOR del filtrador avanzado en un select, cuando es fecha, pone un 
    * input con calendario en el input VALOR del filtrador avanzado. Tambien dependiendo el tipo de campo seleccionado de la lista de CAMPO del filtrador avanzado,
    * la lista de OPERADOR del filtrador avanzado quita o añade operadores como MAYOR QUE, MENOR QUE, estos solo aplicaria para tipos numericos.  
    */
    function changeCampoFiltro(){

        $(".campoFiltro").change(function(){


            var value = $(this).val();
            var numero = $(this).attr("numero");

            var tipo = $("#selCampo_"+numero+" option:selected").attr("tipo");
            var idbg = $("#selCampo_"+numero+" option:selected").attr("idbg");
            var idpregun = $("#selCampo_"+numero+" option:selected").attr("idpregun");
            var campoLista = $("#selCampo_"+numero+" option:selected").attr("campoLista");
            var pasoCampan = $("#selCampo_"+numero+" option:selected").attr("pasoCampan");
            // valores se toman para tener el nombre para diferenciarlos de la fecha
            var nombre_select = $(`#selCampo_${numero} option[value="${value}"`).text();

            $("#tipo_"+numero).val(tipo);

            var strHTMLValor_t = '';

            var strHTMLOperador_t = '<option value="=" selected>IGUAL A</option>';    
                strHTMLOperador_t += '<option value="!=">DIFERENTE DE</option>'; 

            if (tipo == "estado") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(idbg,"estado");
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "monoef") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(idbg,"monoef",pasoCampan);
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "clasi") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '<option value="1">Devoluciones</option>';
                    strHTMLValor_t += '<option value="2">No contactable</option>';
                    strHTMLValor_t += '<option value="3">Sin gestion</option>';
                    strHTMLValor_t += '<option value="4">No contactado</option>';
                    strHTMLValor_t += '<option value="5">Contactado</option>';
                    strHTMLValor_t += '<option value="6">No efectivo</option>';
                    strHTMLValor_t += '<option value="7">Efectivo</option>';
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "usu") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(idbg,"usu");
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "usu_tel") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(idbg,"usu_tel");
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);


            }else if (tipo == "ivr_raiz") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(null,"ivr_raiz");
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);


            }else if (tipo == "ivr_opcion") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(null,"ivr_opcion");
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);


            }else if (tipo == "campanProyecto") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'[]" id="valor_'+numero+'" multiple="multiple">';
                strHTMLValor_t += traerOpcionesLista(null,"campanProyecto");
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);
                $("#valor_"+numero).select2();

                strHTMLOperador_t = '<option value="IN">IGUAL A</option>';


            }else if (tipo == "canal") {
                
                if (campoLista === "undefined") {
                    strHTMLValor_t += '<input class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '</input>';
                } else {
                    strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += traerOpcionesLista(idbg, "origen_dy_wf", idpregun, campoLista);
                    strHTMLValor_t += '</select>';
                }

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "sentido") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '<option value="Entrante">Entrante</option>';
                    strHTMLValor_t += '<option value="Saliente">Saliente</option>';
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "estado_mail") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '<option value="EN ESPERA">EN ESPERA</option>';
                    strHTMLValor_t += '<option value="ASIGNADO">ASIGNADO</option>';
                    strHTMLValor_t += '<option value="GESTION CERRADA">GESTION CERRADA</option>';
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "estado_ges") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '<option value="SIN GESTION">SIN GESTION</option>';
                    strHTMLValor_t += '<option value="CON GESTION">CON GESTION</option>';
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "estado_chat") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '<option value="EN ESPERA">EN ESPERA</option>';
                    strHTMLValor_t += '<option value="ASIGNADO">ASIGNADO</option>';
                    strHTMLValor_t += '<option value="CERRADO">CERRADO</option>';
                    strHTMLValor_t += '<option value="ABANDONADA">ABANDONADA</option>';
                    strHTMLValor_t += '<option value="ASIGNADO AUTORESPUSTA">ASIGNADO AUTORESPUSTA</option>';
                    strHTMLValor_t += '<option value="CERRADO ESPERANDO TIPIFICACION">CERRADO ESPERANDO TIPIFICACION</option>';
                    strHTMLValor_t += '<option value="CERRADO, AGENTE NO TIPIFICA">ASIGNCERRADO, AGENTE NO TIPIFICAADO</option>';
                    strHTMLValor_t += '<option value="FINALIZADO POR CRM">FINALIZADO POR CRM</option>';
                    strHTMLValor_t += '<option value="SUPERO EL LIMITE DE TIEMPO DE ASIGNACION">SUPERO EL LIMITE DE TIEMPO DE ASIGNACION</option>';
                    strHTMLValor_t += '<option value="TIMEOUT AGENTE">TIMEOUT AGENTE</option>';
                    strHTMLValor_t += '<option value="TIMEOUT CLIENTE">TIMEOUT CLIENTE</option>';
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);


            }else if (tipo == "sino") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '<option value="SI">SI</option>';
                    strHTMLValor_t += '<option value="NO">NO</option>';
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "sinoNumber") {

            strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += '<option value="1">SI</option>';
                strHTMLValor_t += '<option value="0">NO</option>';
            strHTMLValor_t += '</select>';

            $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "reintento") {

                // Se adiciona el filtro por tipo de reintento
                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += '<option value="NO GESTIONADO">NO GESTIONADO</option>';
                    strHTMLValor_t += '<option value="REINTENTO AUTOMATICO">REINTENTO AUTOMATICO</option>';
                    strHTMLValor_t += '<option value="AGENDA">AGENDA</option>';
                    strHTMLValor_t += '<option value="NO REINTENTAR">NO REINTENTAR</option>';
                    strHTMLValor_t += '<option value="VACIO">VACIO</option>';
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            } else if (tipo == "3" || tipo == "4") {

                strHTMLOperador_t += '<option value=">">MAYOR A</option>';
                strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

               
                // se agregega funcion que trae los pasos como lista desplegable
                if (nombre_select === 'PASO_GMI_DY' || nombre_select === 'PASO_UG_DY') {
                    strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += traerOpcionesLista(idbg, "estrat_paso");
                    strHTMLValor_t += '</select>';
                } else {
                    strHTMLValor_t += '<input type="text" class="form-control input-sm Decimal campoValor" name="valor_'+numero+'" id="valor_'+numero+'" placeholder="NUMERIC">';
                }
                

                $("#divValor_"+numero).html(strHTMLValor_t);

                // $("#valor_"+numero).numeric({ decimal : ".",  negative : false, scale: 4 });

            } else if (tipo == "5") {

                strHTMLOperador_t += '<option value=">">MAYOR A</option>';
                strHTMLOperador_t += '<option value=">=">MAYOR O IGUAL A</option>';
                strHTMLOperador_t += '<option value="<">MENOR QUE</option>';
                strHTMLOperador_t += '<option value="<=">MENOR O IGUAL A</option>';

                strHTMLValor_t += '<input readonly type="text" class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'" placeholder="YYYY-MM-DD">';

                $("#divValor_"+numero).html(strHTMLValor_t);

                $("#valor_"+numero).datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd'
                });

            }else if (tipo == "6" || tipo == "13") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(idpregun);
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if (tipo == "8") {

                strHTMLOperador_t += '<option value=">">MAYOR A</option>';
                strHTMLOperador_t += '<option value="<">MENOR QUE</option>';

                strHTMLValor_t += '<input type="text" class="form-control input-sm Decimal campoValor" name="valor_'+numero+'" id="valor_'+numero+'" placeholder="NUMERIC">';

                $("#divValor_"+numero).html(strHTMLValor_t);

                $("#valor_"+numero).numeric({ decimal : ".",  negative : false, scale: 4 });

            }else if (tipo == "10") {

                strHTMLOperador_t += '<option value=">">MAYOR A</option>';
                strHTMLOperador_t += '<option value=">=">MAYOR O IGUAL A</option>';
                strHTMLOperador_t += '<option value="<">MENOR QUE</option>';
                strHTMLOperador_t += '<option value="<=">MENOR O IGUAL A</option>';

                strHTMLValor_t += '<input type="text" class="form-control input-sm Hora hasWickedpicker campoValor" name="valor_'+numero+'" id="valor_'+numero+'" placeholder="HH:MM:SS" onkeypress="return false;" aria-showingpicker="false" tabindex="0">';

                $("#divValor_"+numero).html(strHTMLValor_t);

                $("#valor_"+numero).wickedpicker({ 
                    twentyFour: true,
                    title: 'HORAS',
                    showSeconds: true,
                    secondsInterval: 1,
                    minutesInterval: 1,
                    beforeShow: null,
                    show: null,
                    clearable: false,
                    format: 'hh:mm:ss'
                });

            }else if (tipo == "14") { // TIPO HORA CON EL NUEVO PLUGIN

            strHTMLOperador_t += '<option value=">">MAYOR A</option>';
            strHTMLOperador_t += '<option value=">=">MAYOR O IGUAL A</option>';
            strHTMLOperador_t += '<option value="<">MENOR QUE</option>';
            strHTMLOperador_t += '<option value="<=">MENOR O IGUAL A</option>';

            strHTMLValor_t += '<input type="text" class="form-control input-sm Hora campoValor" name="valor_'+numero+'" id="valor_'+numero+'" placeholder="HH:MM:SS" onkeypress="return false;" aria-showingpicker="false" tabindex="0">';

            $("#divValor_"+numero).html(strHTMLValor_t);

            $("#valor_"+numero).datetimepicker({
            format:"HH:mm:ss",
            //useCurrent:hora
            });


            }else if (tipo == "num_semanas") {

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    for (let i = 1; i <= 12; i++) {
                        strHTMLValor_t += `<option value="${i}">${i}</option>`;
                    }
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            }else if(tipo == "estrat_paso"){
                // BSV-2021-09-27 : Agrego un no filtro en donde lo que debo traer son los pasos de la bd

                strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                strHTMLValor_t += traerOpcionesLista(idbg, "estrat_paso");
                strHTMLValor_t += '</select>';

                $("#divValor_"+numero).html(strHTMLValor_t);

            } else if (tipo == "1") {
                // fitro para los datos de bd cuando es 
                if (campoLista === "undefined") {
                    strHTMLValor_t += '<input class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'" placeholder="TEXT">';
                    strHTMLValor_t += '</input>';
                } else {
                    strHTMLValor_t += '<select class="form-control input-sm campoValor" name="valor_'+numero+'" id="valor_'+numero+'">';
                    strHTMLValor_t += traerOpcionesLista(idbg, "origen_dy_wf", idpregun, campoLista);
                    strHTMLValor_t += '</select>';   
                }

                $("#divValor_"+numero).html(strHTMLValor_t);
               
            }else{

                strHTMLOperador_t += '<option value="LIKE_1">INICIE POR</option>';
                strHTMLOperador_t += '<option value="LIKE_2">CONTIENE</option>';
                strHTMLOperador_t += '<option value="LIKE_3">TERMINE EN</option>';

                strHTMLValor_t += '<input type="text" class="form-control input-sm" id="valor_'+numero+'" name="valor_'+numero+'" placeholder="TEXT">';

                $("#divValor_"+numero).html(strHTMLValor_t);
            }


            $("#selOperador_"+numero).html(strHTMLOperador_t);
            
            validarAperturaPorDefecto(value, numero);
            quitarClaseError();
            changeRenderizado();
        });

    }

    function Pivot(){
        

        var arrDataFil_t = new Array();

        var arrNumerosFiltros_t = new Array();

        var tipoReport_t = $("#sql_query").val();
        var strFechaIn_t = $("#fecha_inicial").val();
        var strFechaFn_t = $("#fecha_final").val();
        var intIdHuesped_t = <?=$_SESSION["HUESPED"]?>;

        var intIdEstrat_t = $("#sql_query option:selected").attr("idEstrat");
        var intIdBd_t = $("#sql_query option:selected").attr("idbd");
        var intIdPaso_t = $("#sql_query option:selected").attr("idPaso");
        var intIdTipo_t = $("#sql_query option:selected").attr("idTipo");
        var intIdGuion_t = $("#sql_query option:selected").attr("idguion");
        var intIdCBX_t = $("#sql_query option:selected").attr("idcampancbx");
        var intIdPeriodo_t = $("#sql_query option:selected").attr("periodo");
        var intIdMuestra_t = $("#sql_query option:selected").attr("idmuestra");

        var objDataReport_t = {tipoReport_t :tipoReport_t,strFechaIn_t :strFechaIn_t,strFechaFn_t :strFechaFn_t,intIdHuesped_t :intIdHuesped_t,intIdEstrat_t :intIdEstrat_t,intIdBd_t :intIdBd_t,intIdPaso_t :intIdPaso_t,intIdTipo_t :intIdTipo_t,intIdGuion_t :intIdGuion_t,intIdCBX_t :intIdCBX_t,intIdPeriodo_t :intIdPeriodo_t,intIdMuestra_t :intIdMuestra_t};

        var form = $("#divFiltros .rows > input, #divFiltros .rows .form-group > input, #divFiltros .rows .form-group > select");


        $(".rows").each(function(i){
            arrNumerosFiltros_t[i]=$(this).attr("numero");
        });

        var arrDataFil_t = JSON.parse($.ajax({
                                            url: '<?=$url_crud;?>?filtroAvanzadoJSON=true',
                                            type:'POST',
                                            data:form,
                                            dataType : 'JSON',
                                            global:false,
                                            async:false,
                                            success:function(data){
                                                return data;
                                            }
                                        }).responseText);

        objDataReport_t = Object.assign(objDataReport_t, arrDataFil_t);
        objDataReport_t = Object.assign(objDataReport_t, {totalFiltros : arrNumerosFiltros_t});

        var intErrores_t = 0;

        $(".campoFiltro").each(function(i){
            if ($(this).val() == "0") {
                intErrores_t++;
                alertify.error("Debe seleccionar el campo a filtrar.");
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).closest(".form-group").removeClass("has-error");
            }
        });

        $(".campoValor").each(function(i){

            if (this.type == "text") {

                if (this.value == "") {

                    intErrores_t++;
                    alertify.error("Debe diligenciar el campo.");
                    $(this).closest(".form-group").addClass("has-error");

                }

            }else if(this.type == "select-one"){

                if (this.value == -1 || this.value == null) {

                    intErrores_t++;
                    alertify.error("Debe diligenciar el campo.");
                    $(this).closest(".form-group").addClass("has-error");

                }



            }

        });


        if (intErrores_t == 0) {

            ejecutarReporte(objDataReport_t);

        }

        function ejecutarReporte(obj){

             $.ajax({
                url      : '<?=base_url?>pages/charts/report.php?Pivot=true',
                type     : 'POST',
                data     : objDataReport_t,
                global   : false,
                success  : function(data) {
                    $("#pivotTable").html('');

                    $(function(){

                        var derivers = $.pivotUtilities.derivers;
                        var renderers = $.extend($.pivotUtilities.renderers,$.pivotUtilities.c3_renderers,$.pivotUtilities.d3_renderers,$.pivotUtilities.export_renderers,$.pivotUtilities.plotly_renderers);

                        $("#pivotTable").pivotUI(JSON.parse(data),{renderers: renderers,rendererName: "Table Barchart "});

                    });
                },
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
             });
            
        }

    }

    function exportarReporte(){
        

        var arrDataFil_t = new Array();

        var arrNumerosFiltros_t = new Array();

        var tipoReport_t = $("#sql_query").val();
        var strFechaIn_t = $("#fecha_inicial").val();
        var strFechaFn_t = $("#fecha_final").val();
        var intIdHuesped_t = <?=$_SESSION["HUESPED"]?>;

        var intIdEstrat_t = $("#sql_query option:selected").attr("idEstrat");
        var intIdBd_t = $("#sql_query option:selected").attr("idbd");
        var intIdPaso_t = $("#sql_query option:selected").attr("idPaso");
        var intIdTipo_t = $("#sql_query option:selected").attr("idTipo");
        var intIdGuion_t = $("#sql_query option:selected").attr("idguion");
        var intIdCBX_t = $("#sql_query option:selected").attr("idcampancbx");
        var intIdPeriodo_t = $("#sql_query option:selected").attr("periodo");
        var intIdMuestra_t = $("#sql_query option:selected").attr("idmuestra");
        var middleware = $("#sql_query option:selected").attr("middleware");


        $("#tipoReport_t").val(tipoReport_t);
        $("#strFechaIn_t").val(strFechaIn_t);
        $("#strFechaFn_t").val(strFechaFn_t);
        $("#intIdHuesped_t").val(intIdHuesped_t);
        $("#intIdEstrat_t").val(intIdEstrat_t);
        $("#intIdBd_t").val(intIdBd_t);
        $("#intIdPaso_t").val(intIdPaso_t);
        $("#intIdTipo_t").val(intIdTipo_t);
        $("#intIdGuion_t").val(intIdGuion_t);
        $("#intIdCBX_t").val(intIdCBX_t);
        $("#intIdPeriodo_t").val(intIdPeriodo_t);
        $("#intIdMuestra_t").val(intIdMuestra_t);
        $("#middleware").val(middleware);

        $("#inpFiltroAvanAct").val("1");

        var form = $("#divFiltros .rows > input, #divFiltros .rows .form-group > input, #divFiltros .rows .form-group > select");


        $(".rows").each(function(i){
            arrNumerosFiltros_t[i]=$(this).attr("numero");
        });

        var jsonData_t = $.ajax({
                                url: '<?=$url_crud;?>?filtroAvanzadoJSON=true',
                                type:'POST',
                                data:form,
                                dataType : 'JSON',
                                global:false,
                                async:false,
                                success:function(data){
                                    return data;
                                }
                            }).responseText;

        $("#jsonCondiciones").val(jsonData_t);

        $("#jsonTotalFil").val(JSON.stringify(arrNumerosFiltros_t));



        var intErrores_t = 0;

        $(".campoFiltro").each(function(i){
            if ($(this).val() == "0") {
                intErrores_t++;
                alertify.error("Debe seleccionar el campo a filtrar.");
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).closest(".form-group").removeClass("has-error");
            }
        });


        $(".campoValor").each(function(i){

            if (this.type == "text") {

                if (this.value == "") {

                    intErrores_t++;
                    alertify.error("Debe diligenciar el campo.");
                    $(this).closest(".form-group").addClass("has-error");

                }

            }else if(this.type == "select-one"){

                if (this.value == -1 || this.value == null) {

                    intErrores_t++;
                    alertify.error("Debe diligenciar el campo.");
                    $(this).closest(".form-group").addClass("has-error");

                }



            }

        });


        if (intErrores_t == 0) {

            // Antes de exportar me toca validar si la consulta esta bien y nos trae registros
            $.ajax({
                url  : '<?=base_url?>pages/charts/report.php?ValidarExportar=true',
                type :  'post',
                dataType : 'json',
                data : {
                    tipoReport_t: tipoReport_t,
                    strFechaIn_t: strFechaIn_t,
                    strFechaFn_t: strFechaFn_t,
                    intIdHuesped_t: intIdHuesped_t,
                    intIdEstrat_t: intIdEstrat_t,
                    intIdBd_t: intIdBd_t,
                    intIdPaso_t: intIdPaso_t,
                    intIdTipo_t: intIdTipo_t,
                    intIdGuion_t: intIdGuion_t,
                    intIdCBX_t: intIdCBX_t,
                    intIdPeriodo_t: intIdPeriodo_t,
                    intIdMuestra_t: intIdMuestra_t,
                    middleware: middleware,
                    jsonCondiciones: $("#jsonCondiciones").val(),
                    jsonTotalFil: $("#jsonTotalFil").val()
                },
                success : function(data){
                    if(data.estado == "ok"){
                        $('#export-form').submit();
                    }else{
                        $("#resultados").html(data.mensaje);
                    }
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

    }

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

        var objDataReport_t = {consulta : $("#strtxtConsulta_t").val(),intFilas_t : intFilas_t, intRegistrosTotal_t : $("#intRegistrosTotal_t").val(), intCantidadPaginas_t : $("#intCantidadPaginas_t").val(),intLimite_t:intLimite_t,intPaginaActual_t:intPaginaActual_t, tipoReport_t: $("#sql_query").val()};

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
                
                $("#resultados").html(strHTMLReporte_t);
                $("#resultados").html(data);

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

    function Reportes(){
        

        var arrDataFil_t = new Array();

        var arrNumerosFiltros_t = new Array();

        var intFilas_t = 0;
        var intLimite_t = 50;
        var intPaginaActual_t = 1;

        var tipoReport_t = $("#sql_query").val();
        var strFechaIn_t = $("#fecha_inicial").val();
        var strFechaFn_t = $("#fecha_final").val();
        var intIdHuesped_t = <?=$_SESSION["HUESPED"]?>;

        var intIdEstrat_t = $("#sql_query option:selected").attr("idEstrat");
        var intIdBd_t = $("#sql_query option:selected").attr("idbd");
        var intIdPaso_t = $("#sql_query option:selected").attr("idPaso");
        var intIdTipo_t = $("#sql_query option:selected").attr("idTipo");
        var intIdGuion_t = $("#sql_query option:selected").attr("idguion");
        var intIdCBX_t = $("#sql_query option:selected").attr("idcampancbx");
        var intIdPeriodo_t = $("#sql_query option:selected").attr("periodo");
        var intIdMuestra_t = $("#sql_query option:selected").attr("idmuestra");
        var middleware = $("#sql_query option:selected").attr("middleware");

        var objDataReport_t = {tipoReport_t : tipoReport_t,strFechaIn_t : strFechaIn_t,strFechaFn_t : strFechaFn_t,intIdHuesped_t : intIdHuesped_t,intIdEstrat_t : intIdEstrat_t,intIdBd_t : intIdBd_t, intIdTipo_t : intIdTipo_t,intIdGuion_t : intIdGuion_t,intIdCBX_t : intIdCBX_t,intIdPeriodo_t : intIdPeriodo_t,intIdMuestra_t : intIdMuestra_t, intIdPaso_t : intIdPaso_t, strLimit_t : "si",intFilas_t: intFilas_t, intLimite_t : intLimite_t, intPaginaActual_t:intPaginaActual_t, middleware: middleware };

        var form = $("#divFiltros .rows > input, #divFiltros .rows .form-group > input, #divFiltros .rows .form-group > select");
        form.removeAttr("disabled");
        console.log(form);
        formData = form.serialize();
        
        // SE DESHABILITAN LOS FILTROS OBLIGATORIOS
        $("#divFiltros .rows .filtroObligatorio").attr("disabled","disabled");


        $(".rows").each(function(i){
            arrNumerosFiltros_t[i]=$(this).attr("numero");
        });

        var arrDataFil_t = JSON.parse($.ajax({
                                            url: '<?=$url_crud;?>?filtroAvanzadoJSON=true',
                                            type:'POST',
                                            data:formData,
                                            dataType : 'JSON',
                                            global:false,
                                            async:false,
                                            success:function(data){
                                                return data;
                                            }
                                        }).responseText);

        objDataReport_t = Object.assign(objDataReport_t, arrDataFil_t);
        objDataReport_t = Object.assign(objDataReport_t, {totalFiltros : arrNumerosFiltros_t});



        var intErrores_t = 0;

        $(".campoFiltro").each(function(i){
            if ($(this).val() == "0") {
                intErrores_t++;
                alertify.error("Debe seleccionar el campo a filtrar.");
                $(this).closest(".form-group").addClass("has-error");
            }else{
                $(this).closest(".form-group").removeClass("has-error");
            }
        });


        $(".campoValor").each(function(i){

            if (this.type == "text") {

                if (this.value == "") {

                    intErrores_t++;
                    alertify.error("Debe diligenciar el campo.");
                    $(this).closest(".form-group").addClass("has-error");

                }

            }else if(this.type == "select-one"){

                if (this.value == -1 || this.value == null) {

                    intErrores_t++;
                    alertify.error("Debe diligenciar el campo.");
                    $(this).closest(".form-group").addClass("has-error");

                }



            }else if(this.type == "select-multiple"){

                if (this.value == "" || this.value == null) {

                    intErrores_t++;
                    alertify.error("Debe diligenciar el campo.");
                    $(this).closest(".form-group").addClass("has-error");

                }

            }

        });


        if (intErrores_t == 0) {

            ejecutarReporte(objDataReport_t,intLimite_t);

        }

        function ejecutarReporte(obj,intLimite_p){

            $.ajax({
                url  :  '<?=base_url?>pages/charts/report.php?Reporte=true',
                type :  'post',
                data : obj,
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

                    $("#resultados").html(strHTMLReporte_t);
                    $("#resultados").html(data);

                    $("#selIntLimite_t").val(intLimite_p).trigger("change");
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



    }

    //JDBD - Esta funcion retorna un color segun el intervalo de alguna grafica.
    function colorGrafica(strIntervalo_p){

        if (isNaN(strIntervalo_p) == false) {
            switch(strIntervalo_p){
                case 0: 
                    return "#FF0000";
                    break;
                case 1: 
                    return "#FF7B54";
                    break;
                case 2: 
                    return "#FF8300";
                    break;
                case 3: 
                    return "#FFC500";
                    break;
                case 4: 
                    return "#137500";
                    break;
                default:
                    return "#009FE3";
                    break;
            }   
        }else{
            switch(strIntervalo_p){

                case "Sin gestion":
                    return "#FF0000";
                    break;    
                case "No contactado":
                    return "#FF8300";
                    break;    
                case "No contactable":
                    return "#881901";
                    break;    
                case "Contactado":
                    return "#137500";
                    break;    
                case "No efectivo":
                    return "#989898";
                    break;    
                case "Efectivo":
                    return "#009FE3";
                    break;    
                case "Devoluciones":
                    return "#C39EF9";
                    break;    
                default:
                    return "#000000";
                    break;    

            }
        }

    }

    //////////////////////GRAFICAS BD//////////////////////////
    //JDBD - Esta funcion trae la informacion para la primera grafica "GESTIONES MÁS IMPORTANTES POR TIPIFICACION".
        function GraficaBd_4(objDataReport_p,idBd_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrGraficaBd_4_t = {
                                    chart: {
                                        type: 'funnel3d',
                                        options3d: {
                                            enabled: true,
                                            alpha: 10,
                                            depth: 50,
                                            viewDistance: 50
                                        }
                                    },
                                    tooltip : {
                                        formatter : function(){
                                            return '<span style="color:' + this.color + '">● </span><strong>'+this.y+'</strong> Registros con <strong>'+this.x+'</strong> ( '+this.point.z+'% )';
                                        }
                                    },
                                    title: {
                                        text: 'GESTIONES MÁS IMPORTANTES POR TIPIFICACION'
                                    },
                                    plotOptions: {
                                        series: {
                                            dataLabels: {
                                                enabled: true,
                                                format: '{point.x}.',
                                                allowOverlap: false,
                                                y: 10
                                            },
                                            neckWidth: '30%',
                                            neckHeight: '25%',
                                            width: '80%',
                                            height: '80%'
                                        }
                                    },
                                    series: [{
                                        name: '',
                                        data: []
                                    }]
                                };
            objDataReport_p = Object.assign(objDataReport_p, {idBd_t : idBd_p});

            var arrDataGraficaBd_4_t = $.ajax({
                                                url: '<?php echo $url_crud;?>?DataGraficaBd_4=si',
                                                type:'POST',
                                                data:objDataReport_p,
                                                global:false,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            arrDataGraficaBd_4_t = JSON.parse(arrDataGraficaBd_4_t);

            var IntTotal_t = 0;

            $.each(arrDataGraficaBd_4_t,function(i,item){ 
                IntTotal_t = IntTotal_t+Number(item.cantidad);
            });

            $.each(arrDataGraficaBd_4_t,function(i,item){ 
                arrGraficaBd_4_t.series[0].data.push({x:item.GESTION_MAS_IMPORTANTE,y:Number(item.cantidad),z:Math.round(((Number(item.cantidad)*100)/IntTotal_t))});
            });

            return arrGraficaBd_4_t;

        }
    //JDBD - Esta funcion trae la informacion para la primera grafica "GESTIONES MÁS IMPORTANTES POR TIPO REINTENTO".
        function GraficaBd_3(objDataReport_p,idBd_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrGraficaBd_3_t = {
                                    chart: {
                                        type: 'funnel3d',
                                        options3d: {
                                            enabled: true,
                                            alpha: 10,
                                            depth: 50,
                                            viewDistance: 50
                                        }
                                    },
                                    tooltip : {
                                        formatter : function(){
                                            return '<span style="color:' + this.color + '">● </span><strong>'+this.y+'</strong> Registros con <strong>'+this.x+'</strong> ( '+this.point.z+'% )';
                                        }
                                    },
                                    title: {
                                        text: 'GESTIONES MÁS IMPORTANTES POR TIPO REINTENTO'
                                    },
                                    plotOptions: {
                                        series: {
                                            dataLabels: {
                                                enabled: true,
                                                format: '{point.x}.',
                                                allowOverlap: false,
                                                y: 10
                                            },
                                            neckWidth: '30%',
                                            neckHeight: '25%',
                                            width: '80%',
                                            height: '80%'
                                        }
                                    },
                                    series: [{
                                        name: '',
                                        data: []
                                    }]
                                };

            objDataReport_p = Object.assign(objDataReport_p, {idBd_t : idBd_p});

            var arrDataGraficaBd_3_t = $.ajax({
                                                url: '<?php echo $url_crud;?>?DataGraficaBd_3=si',
                                                type:'POST',
                                                data:objDataReport_p,
                                                global:false,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            arrDataGraficaBd_3_t = JSON.parse(arrDataGraficaBd_3_t);

            var IntTotal_t = 0;

            $.each(arrDataGraficaBd_3_t,function(i,item){ 
                IntTotal_t = IntTotal_t+Number(item.cantidad);
            });

            $.each(arrDataGraficaBd_3_t,function(i,item){ 
                arrGraficaBd_3_t.series[0].data.push({x:item.REINTENTO_GMI,y:Number(item.cantidad),z:Math.round(((Number(item.cantidad)*100)/IntTotal_t))});
            });

            return arrGraficaBd_3_t;

        }

    //JDBD - Esta funcion trae la informacion para la primera grafica "GESTIONES MÁS IMPORTANTES POR CANTIDAD DE INTENTOS".
        function GraficaBd_2(objDataReport_p,idBd_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrGraficaBd_2_t = {
                                    chart: {
                                        type: 'funnel3d',
                                        options3d: {
                                            enabled: true,
                                            alpha: 10,
                                            depth: 50,
                                            viewDistance: 50
                                        }
                                    },
                                    tooltip : {
                                        formatter : function(){
                                            return '<span style="color:' + this.color + '">● </span><strong>'+this.y+'</strong> Registros con <strong>'+this.x+'</strong> Intentos ( '+this.point.z+'% )';
                                        }
                                    },
                                    title: {
                                        text: 'GESTIONES MÁS IMPORTANTES POR CANTIDAD DE INTENTOS'
                                    },
                                    plotOptions: {
                                        series: {
                                            dataLabels: {
                                                enabled: true,
                                                format: '{point.x} Intentos.',
                                                allowOverlap: false,
                                                y: 10
                                            },
                                            neckWidth: '30%',
                                            neckHeight: '25%',
                                            width: '80%',
                                            height: '80%'
                                        }
                                    },
                                    colors: [],
                                    series: [{
                                        name: '',
                                        data: []
                                    }]
                                };

            objDataReport_p = Object.assign(objDataReport_p, {intEstratId_t : intEstratId_t, idBd_t : idBd_p});

            var arrDataGraficaBd_2_t = $.ajax({
                                                url: '<?php echo $url_crud;?>?DataGraficaBd_2=si',
                                                type:'POST',
                                                data:objDataReport_p,
                                                global:false,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            arrDataGraficaBd_2_t = JSON.parse(arrDataGraficaBd_2_t);

            var IntTotal_t = 0;

            $.each(arrDataGraficaBd_2_t,function(i,item){ 
                IntTotal_t = IntTotal_t+Number(item.cantidad);
            });

            $.each(arrDataGraficaBd_2_t,function(i,item){ 
                arrGraficaBd_2_t.series[0].data.push({x:item.CANTIDAD_INTENTOS,y:Number(item.cantidad),z:Math.round(((Number(item.cantidad)*100)/IntTotal_t))})
                arrGraficaBd_2_t.colors.push(colorGrafica(Number(item.CANTIDAD_INTENTOS)));
            });

            return arrGraficaBd_2_t;

        }

        //JDBD - Esta funcion trae la informacion para la primera grafica de campañas.
        function GraficaSC_4(strFechaInicial_p,strFechaFinal_p,idTipo_p,idBd_p,idEstpas_p,objDataReport_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrStates_t = {
              hover: {
                halo: {
                  opacity: 0.3
                }
              }
            };

            var arrDataLabel_t = {
                enabled: true,
                rotation: 0,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:,.0f}', 
                y: 10, 
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            };

            var arrGraficaSC_4_t = {
                        chart: {
                            type: 'column',
                            options3d: {
                                enabled: true,
                                alpha: 15,
                                beta: 15,
                                viewDistance: 25,
                                depth: 40
                            }
                        },

                        title: {
                            text: 'EFICIENCIA ÚLTIMA GESTIÓN POR HORA'
                        },

                        xAxis: {
                            categories: [],
                            labels: {
                                skew3d: true,
                                style: {
                                    fontSize: '16px'
                                }
                            }
                        },

                        yAxis: {
                            allowDecimals: false,
                            min: 0,
                            title: {
                                text: 'Cantidad',
                                skew3d: true
                            }
                        },

                        tooltip: {
                            headerFormat: '<b>{point.key}</b><br>',
                            pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y} / {point.stackTotal}'
                        },

                        plotOptions: {
                            column: {
                                stacking: 'normal',
                                depth: 40
                            }
                        },

                        series: [],

                        responsive: {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        align: 'center',
                                        verticalAlign: 'bottom',
                                        layout: 'horizontal'
                                    },
                                    yAxis: {
                                        labels: {
                                            align: 'left',
                                            x: 0,
                                            y: -5
                                        },
                                        title: {
                                            text: null
                                        }
                                    },
                                    subtitle: {
                                        text: null
                                    },
                                    credits: {
                                        enabled: false
                                    }
                                }
                            }]
                        }
                    };

            objDataReport_p = Object.assign(objDataReport_p, {strFechaInicial_t : strFechaInicial_p,strFechaFinal_t : strFechaFinal_p,idTipo_t : idTipo_p,idBd_t : idBd_p,idEstpas_t : idEstpas_p});

            var arrDataGraficaSC_4_t = $.ajax({
                                                url: '<?php echo $url_crud;?>?DataGraficaSC_4=si',
                                                type:'POST',
                                                data:objDataReport_p,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            arrDataGraficaSC_4_t = JSON.parse(arrDataGraficaSC_4_t);

            $.each(arrDataGraficaSC_4_t.data,function(i,item){ 



                arrGraficaSC_4_t.series.push({name:item.gestion,data:[],dataLabels:arrDataLabel_t,states:arrStates_t});

                $.each(arrDataGraficaSC_4_t.horas,function(I,item2){

                    arrGraficaSC_4_t.series[i].data.push(Number(item[item2]));
                    arrGraficaSC_4_t.xAxis.categories.push(item2);

                });

            });

            return arrGraficaSC_4_t;

        }

        //JDBD - Esta funcion trae la informacion para la primera grafica de campañas.
        function GraficaSC_3(strFechaInicial_p,strFechaFinal_p,idTipo_p,idBd_p,idEstpas_p,objDataReport_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrDataLabel_t = {
                enabled: true,
                rotation: 0,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:,.0f}', 
                y: 10, 
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            };

            var arrGraficaSC_3_t = {
                        chart: {
                            type: 'column',
                            options3d: {
                                enabled: true,
                                alpha: 15,
                                beta: 15,
                                viewDistance: 25,
                                depth: 40
                            }
                        },
                        colors:[],
                        title: {
                            text: 'EFICIENCIA CLASIFICACION POR HORA'
                        },

                        xAxis: {
                            categories: [],
                            labels: {
                                skew3d: true,
                                style: {
                                    fontSize: '16px'
                                }
                            }
                        },

                        yAxis: {
                            allowDecimals: false,
                            min: 0,
                            title: {
                                text: 'Cantidad',
                                skew3d: true
                            }
                        },

                        tooltip: {
                            headerFormat: '<b>{point.key}</b><br>',
                            pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y} / {point.stackTotal}'
                        },

                        plotOptions: {
                            column: {
                                stacking: 'normal',
                                depth: 40
                            }
                        },

                        series: []

                    };

            objDataReport_p = Object.assign(objDataReport_p, {strFechaInicial_t : strFechaInicial_p,strFechaFinal_t : strFechaFinal_p,idTipo_t : idTipo_p,idBd_t : idBd_p,idEstpas_t : idEstpas_p});



            if (idTipo_p != "1") {


                var arrDataGraficaSC_3_t = $.ajax({
                                                    url: '<?php echo $url_crud;?>?DataGraficaSC_3=si',
                                                    type:'POST',
                                                    data:objDataReport_p,
                                                    async:false,
                                                    success:function(data){
                                                        return data;
                                                    }
                                                }).responseText;

                arrDataGraficaSC_3_t = JSON.parse(arrDataGraficaSC_3_t);

                $.each(arrDataGraficaSC_3_t.data,function(i,item){ 

                    arrGraficaSC_3_t.series.push({name:item.clasificacion,data:[],dataLabels:arrDataLabel_t});
                    arrGraficaSC_3_t.colors.push(colorGrafica(item.clasificacion));

                    $.each(arrDataGraficaSC_3_t.horas,function(I,item2){

                        arrGraficaSC_3_t.series[i].data.push(Number(item[item2]));
                        arrGraficaSC_3_t.xAxis.categories.push(item2);

                    });

                });                

            }else{

                var arrDataGraficaSC_3_t = $.ajax({
                                                    url: '<?php echo $url_crud;?>?DataGraficaSC_3=in',
                                                    type:'POST',
                                                    data:objDataReport_p,
                                                    async:false,
                                                    success:function(data){
                                                        return data;
                                                    }
                                                }).responseText;

                arrDataGraficaSC_3_t = JSON.parse(arrDataGraficaSC_3_t);

                arrGraficaSC_3_t.series.push({name : 'Aban_despues_tsf', data : [],dataLabels:arrDataLabel_t});
                arrGraficaSC_3_t.series.push({name : 'Aban_antes_tsf', data : [],dataLabels:arrDataLabel_t});
                arrGraficaSC_3_t.series.push({name : 'Cont_despues_tsf', data : [],dataLabels:arrDataLabel_t});
                arrGraficaSC_3_t.series.push({name : 'Cont_antes_tsf', data : [],dataLabels:arrDataLabel_t});

                arrGraficaSC_3_t.colors = ['#FF2700','#EE5F45','#4AA1F7','#0080FF'];
                arrGraficaSC_3_t.title.text = 'EFICIENCIA LLAMADAS POR HORA';

                $.each(arrDataGraficaSC_3_t,function(i,item){ 

                    arrGraficaSC_3_t.series[0].data.push(Number(item.Aban_despues_tsf));
                    arrGraficaSC_3_t.series[1].data.push(Number(item.Aban_antes_tsf));
                    arrGraficaSC_3_t.series[2].data.push(Number(item.Cont_despues_tsf));
                    arrGraficaSC_3_t.series[3].data.push(Number(item.Cont_antes_tsf));

                    arrGraficaSC_3_t.xAxis.categories.push(Number(item.Intervalo));

                }); 

            }

            return arrGraficaSC_3_t;

        }

        //JDBD - Esta funcion trae la informacion para la primera grafica de campañas.
        function GraficaSC_2(strFechaInicial_p,strFechaFinal_p,idTipo_p,idBd_p,idEstpas_p,objDataReport_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrDataLabel_t = {
                enabled: true,
                rotation: 0,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.percentage:.1f}%</b>', 
                y: 10, 
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            };

            var arrGraficaSC_2_t = {
                        chart: {
                            type: 'pie',
                            options3d: {
                                enabled: true,
                                alpha: 45
                            }
                        },
                        title: {
                            text: 'EFICACIA ÚLTIMA GESTIÓN'
                        },
                        plotOptions: {
                            pie: {
                                innerSize: 100,
                                depth: 45
                            }
                        },
                        series: [{
                            name: 'Cantidad',
                            data: [],
                            showInLegend : true,
                            dataLabels : arrDataLabel_t
                        }]
                    };

            objDataReport_p = Object.assign(objDataReport_p, {strFechaInicial_t : strFechaInicial_p,strFechaFinal_t : strFechaFinal_p,idTipo_t : idTipo_p,idBd_t : idBd_p,idEstpas_t : idEstpas_p});

            var arrDataGraficaSC_2_t = $.ajax({
                                                url: '<?php echo $url_crud;?>?DataGraficaSC_2=si',
                                                type:'POST',
                                                data:objDataReport_p,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            arrDataGraficaSC_2_t = JSON.parse(arrDataGraficaSC_2_t);

            $.each(arrDataGraficaSC_2_t,function(i,item){ 

                arrGraficaSC_2_t.series[0].data.push([item.gestion+".",Number(item.cantidad)]);

            });

            return arrGraficaSC_2_t;

        }

        //JDBD - Esta funcion trae la informacion para la primera grafica de campañas.
        function GraficaSC_1(strFechaInicial_p,strFechaFinal_p,idTipo_p,idBd_p,idEstpas_p,objDataReport_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrDataLabel_t = {
                enabled: true,
                rotation: 0,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:,.0f}', 
                y: 10, 
                style: {
                    fontSize: '10px',
                    fontFamily: 'Verdana, sans-serif'
                }
            };

            var arrGraficaSC_1_t = {
                chart: {
                    type: 'column',
                    options3d: {
                        enabled: true,
                        alpha: 15,
                        beta: 15,
                        viewDistance: 25,
                        depth: 40
                    }
                },

                title: {
                    text: 'EFICIENCIA ÚLTIMA GESTIÓN POR AGENTE Y POR HORA'
                },

                xAxis: {
                    categories: [],
                    labels: {
                        skew3d: true,
                        style: {
                            fontSize: '16px'
                        }
                    }
                },

                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: 'Cantidad',
                        skew3d: true
                    }
                },

                tooltip: {
                    headerFormat: '<b>{point.key}</b><br>',
                    pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y} / {point.stackTotal}'
                },

                plotOptions: {
                    column: {
                        stacking: 'normal',
                        depth: 40
                    }
                },

                series: [],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                align: 'center',
                                verticalAlign: 'bottom',
                                layout: 'horizontal'
                            },
                            yAxis: {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -5
                                },
                                title: {
                                    text: null
                                }
                            },
                            subtitle: {
                                text: null
                            },
                            credits: {
                                enabled: false
                            }
                        }
                    }]
                }
            };

            objDataReport_p = Object.assign(objDataReport_p, {strFechaInicial_t : strFechaInicial_p,strFechaFinal_t : strFechaFinal_p,idTipo_t : idTipo_p,idBd_t : idBd_p,idEstpas_t : idEstpas_p});

            var arrDataGraficaSC_1_t = $.ajax({
                                                url: '<?php echo $url_crud;?>?DataGraficaSC_1=si',
                                                type:'POST',
                                                data:objDataReport_p,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            arrDataGraficaSC_1_t = JSON.parse(arrDataGraficaSC_1_t);


            
            $.each(arrDataGraficaSC_1_t.data,function(i,item){ 



                arrGraficaSC_1_t.series.push({name:item.agente,data:[],dataLabels:arrDataLabel_t});

                $.each(arrDataGraficaSC_1_t.horas,function(I,item2){

                    arrGraficaSC_1_t.series[i].data.push(Number(item[item2]));
                    arrGraficaSC_1_t.xAxis.categories.push(item2);

                });

            });

            return arrGraficaSC_1_t;

        }


        //JDBD - Esta funcion trae la informacion para la primera grafica "ESTADO DE LA BASE DE DATOS".
        function GraficaBd_1(objDataReport_p,idBd_p){

            var intEstratId_t = $("#IdEstrat").val();

            var arrGraficaBd_1_t = {
                                    chart: {
                                        type: 'funnel3d',
                                        options3d: {
                                            enabled: true,
                                            alpha: 10,
                                            depth: 50,
                                            viewDistance: 50
                                        }
                                    },
                                    tooltip : {
                                        formatter : function(){
                                            return '<span style="color:' + this.color + '">● </span><strong>'+this.x+':</strong> '+this.y;
                                        }
                                    },
                                    title: {
                                        text: 'ESTADO DE LA BASE DE DATOS'
                                    },
                                    plotOptions: {
                                        series: {
                                            dataLabels: {
                                                enabled: true,
                                                format: '<b>{point.x}</b> ({point.y:,.0f})',
                                                allowOverlap: true,
                                                y: 10
                                            },
                                            neckWidth: '30%',
                                            neckHeight: '25%',
                                            width: '80%',
                                            height: '80%'
                                        }
                                    },
                                    colors: [],
                                    series: [{
                                        name: 'CLASIFICACION_GMI',
                                        data: []
                                    }]
                                };

            objDataReport_p = Object.assign(objDataReport_p, {idBd_t : idBd_p});

            var arrDataGraficaBd_1_t = $.ajax({
                                                url: '<?php echo $url_crud;?>?DataGraficaBd_1=si',
                                                type:'POST',
                                                data:objDataReport_p,
                                                global:false,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText;

            arrDataGraficaBd_1_t = JSON.parse(arrDataGraficaBd_1_t);

            $.each(arrDataGraficaBd_1_t,function(i,item){ 
                arrGraficaBd_1_t.series[0].data.push({x:item.CLASIFICACION_GMI,y:Number(item.cantidad)});
                arrGraficaBd_1_t.colors.push(colorGrafica(item.CLASIFICACION_GMI));
            });

            return arrGraficaBd_1_t;

        }

        function llenarIndicadores(idTipo_p,idBd_p,idEstpas_p,objDataReport_p){


            objDataReport_p = Object.assign(objDataReport_p, {idTipo_t : idTipo_p, idBd_t : idBd_p, idEstpas_t : idEstpas_p});

            var strJSON_t = $.ajax({
                                url      : '<?=$url_crud;?>?llenarIndicadores=true',
                                type     : 'POST',
                                data     : objDataReport_p,
                                global   : false,
                                async    :false,
                                success  : function(data) {
                                    return data;
                                }
                             }).responseText;

            var arrJSON_t = JSON.parse(strJSON_t);

            if (idTipo_p != "1") {

                if (arrJSON_t.length > 0) {

                    $("#td_gestiones").text(arrJSON_t[0]);
                    $("#td_no_contactable").text(arrJSON_t[1]);
                    $("#td_devoluciones").text(arrJSON_t[2]);
                    $("#td_sin_gestion").text(arrJSON_t[3]);
                    $("#td_no_contactado").text(arrJSON_t[4]);
                    $("#td_contactado").text(arrJSON_t[5]);
                    $("#td_no_efectivo").text(arrJSON_t[6]);
                    $("#td_efectivo").text(arrJSON_t[7]);
                    $("#td_duracion").text(arrJSON_t[8]);


                }

            }else{

                if (arrJSON_t.length > 0) {

                    $("#td_ofrecidas").text(arrJSON_t[0]);
                    $("#td_asa").text(arrJSON_t[1]);
                    $("#td_aban").text(arrJSON_t[2]);
                    $("#td_aban_despues_tsf").text(arrJSON_t[3]);
                    $("#td_aban_porcentaje").text(arrJSON_t[4]);
                    $("#td_contestadas").text(arrJSON_t[5]);
                    $("#td_cont_despues_tsf").text(arrJSON_t[6]);
                    $("#td_cont_porcentaje").text(arrJSON_t[7]);
                    $("#td_tsf").text(arrJSON_t[8]);
                    $("#td_aht").text(arrJSON_t[9]);
                    
                }


            }

        }

        function graficasSC(idTipo_p,idBd_p,idEstpas_p,objDataReport_p){


            //JDBD - Ponemos el GIF de Cargando...
            $.blockUI({baseZ: 2000,message: '<img src="assets/img/clock.gif" /> Espere un momento, estamos generando las graficas.' });

            //JDBD - Almacenamos el rango de fechas para la informacion a graficar.
            var strFechaInicial_t = $("#fecha_inicial_grafico").val(); 
            var strFechaFinal_t = $("#fecha_final_grafico").val();

            llenarIndicadores(idTipo_p,idBd_p,idEstpas_p,objDataReport_p);

            //JDBD - Esta funcion se encarga de generar las graficas con un retardo de 1 segundo.
            function GraficasCMP(x){

                if (x < 4) {

                    switch(x){
                        case 0:
                            //JDBD - Dibujamos la primera Grafica "EFICIENCIA ÚLTIMA GESTIÓN POR DÍA". 
                                Highcharts.chart("graficaSC_1",GraficaSC_1(strFechaInicial_t,strFechaFinal_t,idTipo_p,idBd_p,idEstpas_p,objDataReport_p));
                            break; 
                        case 1:
                            //JDBD - Dibujamos la primera Grafica "EFICIENCIA ÚLTIMA GESTIÓN POR DÍA". 
                                Highcharts.chart("graficaSC_2",GraficaSC_2(strFechaInicial_t,strFechaFinal_t,idTipo_p,idBd_p,idEstpas_p,objDataReport_p));
                            break; 
                        case 2:
                            //JDBD - Dibujamos la primera Grafica "EFICIENCIA ÚLTIMA GESTIÓN POR HORA". 
                                Highcharts.chart("graficaSC_3",GraficaSC_3(strFechaInicial_t,strFechaFinal_t,idTipo_p,idBd_p,idEstpas_p,objDataReport_p));
                            break;  
                        case 3:
                            //JDBD - Dibujamos la primera Grafica "EFICACIA ÚLTIMA GESTIÓN POR HORA". 
                                Highcharts.chart("graficaSC_4",GraficaSC_4(strFechaInicial_t,strFechaFinal_t,idTipo_p,idBd_p,idEstpas_p,objDataReport_p));
                            break;  
                    }

                    setTimeout(() => GraficasCMP(x+1), 1000);

                }else{
                    //JDBD - Quitamos el GIF de Cargando.
                        $.unblockUI();
                }

            }

            GraficasCMP(0);


        }

        //JDBD - Esta funcion se encarga de generar las graficas para la seccion "REPORTE GRAFICO BASE DE DATOS".
        function graficasBd(objDataReport_p,idBd_p){

            //JDBD - Ponemos el GIF de Cargando...
            $.blockUI({baseZ: 2000,message: '<img src="assets/img/clock.gif" /> Espere un momento, estamos generando las graficas.' });

            //JDBD - Almacenamos el rango de fechas para la informacion a graficar.
            var strFechaInicial_t = $("#fecha_inicial_grafico").val(); 
            var strFechaFinal_t = $("#fecha_final_grafico").val();

            //JDBD - Esta funcion se encarga de generar las graficas con un retardo de 1 segundo.
            function Graficas(x){

                if (x < 4) {

                    switch(x){
                        case 0:
                            //JDBD - Dibujamos la primera Grafica "ESTADO DE LA BASE DE DATOS". 
                                Highcharts.chart("graficaBD_1",GraficaBd_1(objDataReport_p,idBd_p));
                            break; 
                        case 1:
                            //JDBD - Dibujamos la primera Grafica "GESTIONES MÁS IMPORTANTES POR CANTIDAD DE INTENTOS". 
                                Highcharts.chart("graficaBD_2",GraficaBd_2(objDataReport_p,idBd_p));
                            break; 
                        case 2:
                            //JDBD - Dibujamos la primera Grafica "GESTIONES MÁS IMPORTANTES POR TIPO REINTENTO". 
                                Highcharts.chart("graficaBD_3",GraficaBd_3(objDataReport_p,idBd_p));
                            break;  
                        case 3:
                            //JDBD - Dibujamos la primera Grafica "GESTIONES MÁS IMPORTANTES POR CLASIFICACION". 
                                Highcharts.chart("graficaBD_4",GraficaBd_4(objDataReport_p,idBd_p));
                            break;  
                    }

                    setTimeout(() => Graficas(x+1), 1000);

                }else{
                    //JDBD - Quitamos el GIF de Cargando.
                        $.unblockUI();
                }

            }

            Graficas(0);

        }

    /**
     * JDBD-2020-05-03 : Se trae en forma de lista todos los campos del guion.
     * @return HTML - Opciones para un select de los campos del guion.
     */
    function traerCamposDelReporte(tipoReport_p,intIdHuesped_p,intIdEstrat_p,intIdBd_p,intIdPaso_p,intIdTipo_p,intIdGuion_p,intIdCBX_p,intIdPeriodo_p,intIdMuestra_p) {

        // if (strHTMLOpcionesCampos_t == '') {

            return $.ajax({
                                        url      : '<?=$url_crud;?>?traerCamposDelReporte=true',
                                        type     : 'POST',
                                        data     : {tipoReport_t : tipoReport_p,intIdHuesped_t : intIdHuesped_p,intIdEstrat_t : intIdEstrat_p,intIdBd_t : intIdBd_p,intIdPaso_t : intIdPaso_p,intIdTipo_t : intIdTipo_p,intIdGuion_t : intIdGuion_p,intIdCBX_t : intIdCBX_p,intIdPeriodo_t : intIdPeriodo_p,intIdMuestra_t : intIdMuestra_p},
                                        dataType : 'html',
                                        context  : document.body,
                                        global   : false,
                                        async    :false,
                                        success  : function(data) {
                                            return data;
                                        }
                                    }).responseText;
            
        // }

        // return strHTMLOpcionesCampos_t;

    } 

    function obtenerTipoDistribucionCampana(campanId){
        let resultData = -1;
        $.ajax({
            url: '<?=$url_crud;?>?obtenerTipoDistribucionCampana=true',
            type: 'POST',
            dataType: 'json',
            data: {campanId},
            async: false,
            success: function(data) {
                // Asigna los datos a la variable resultData
                resultData = data.data;
            },
            error: function(xhr, status, error) {
                // Maneja los errores aquí si es necesario
                console.error("Error en la solicitud: " + status + " - " + error);
            }
        });
        return resultData;
    }

    function filtrosPorDefecto(strTipoReporte_p){


        var intOpcion_t = 2;

        if (strTipoReporte_p == "acd" || strTipoReporte_p == "acdChat" || strTipoReporte_p == "acdEmail" || strTipoReporte_p == "pausas"|| strTipoReporte_p == "bdG" || strTipoReporte_p == "scG" || strTipoReporte_p == "encuestasIVRResumenAgente" || strTipoReporte_p == "encuestasIVRResumenPregun" || strTipoReporte_p == "encuestasIVRdetallado" || strTipoReporte_p == "historicoUltOpcIVRDetallado") {

            intOpcion_t = 1;

        }else if(strTipoReporte_p == "gspaso" || strTipoReporte_p == "historicoIVRDetallado"){

            intOpcion_t = 5;

        }else if(strTipoReporte_p == "bdpaso"){

            intOpcion_t = 2;

        }else if(strTipoReporte_p == "opcionesUsadasBot" || strTipoReporte_p == "detalladoLlamadas" || strTipoReporte_p == "historicoIVRResum" || strTipoReporte_p == "ordenamiento"){
            intOpcion_t = 1;

        }else if(strTipoReporte_p == "comSms" || strTipoReporte_p == "gsComMail"  || strTipoReporte_p == "gsComSMS" || strTipoReporte_p == "gsComWhatsapp"){
            intOpcion_t = 3;
        }

        if(strTipoReporte_p == "erlang"){

            // SE CREA EL FILTRO ESPECIFICO PARA LOS REPORTES DE ERLANG

            $("#selCondicion_1").val(" ").trigger("change");
            $("#selCampo_1").val($('#selCampo_1 option:nth(1)').val()).trigger("change");
            $('#selCampo_1 option:nth(1)').html("FECHA FINAL");
            $("#selOperador_1").val("<=").trigger("change");
            $("#valor_1").val(moment().format('YYYY-MM-DD')); 

            NuevaCondicion();

            $("#selCampo_2").val($('#selCampo_2 option:nth(4)').val()).trigger("change");
            $("#selOperador_2").val("=").trigger("change");
            $("#valor_2").val(4); 
            $("#selCondicion_2").val("AND").trigger("change"); 

            NuevaCondicion();

            $("#selCampo_3").val($('#selCampo_3 option:nth(3)').val()).trigger("change");
            $('#selCampo_3 option:nth(3)').html("RANGO DE HORA INICIAL");
            $("#selOperador_3").val(">=").trigger("change");
            $("#valor_3").val("08:00:00").trigger("change"); 
            $("#selCondicion_3").val("AND").trigger("change"); 


            NuevaCondicion();

            $("#selCampo_4").val($('#selCampo_4 option:nth(3)').val()).trigger("change");
            $('#selCampo_4 option:nth(3)').html("RANGO DE HORA FINAL");
            $("#selOperador_4").val("<=").trigger("change");
            $("#valor_4").val("17:59:00").trigger("change"); 
            $("#selCondicion_4").val("AND").trigger("change"); 


            NuevaCondicion();

            $("#selCampo_5").val($('#selCampo_5 option:nth(2)').val()).trigger("change");
            $("#selOperador_5").val("IN").trigger("change"); 
            $("#selCondicion_5").val("AND").trigger("change"); 


            NuevaCondicion();

            $("#selCampo_6").val($('#selCampo_5 option:nth(5)').val()).trigger("change");
            $("#selOperador_6").val("=").trigger("change"); 
            $("#selCondicion_6").val("AND").trigger("change"); 


            for (let i = 1; i <= 6; i++) {

                $("#selCampo_"+i+" , #selOperador_"+i+", #selCondicion_"+i+", #cierre"+i).addClass("filtroObligatorio");
                $("#selCampo_"+i+" , #selOperador_"+i+", #selCondicion_"+i+", #btnQuitarFiltro_"+i+", #cierre"+i).attr("disabled","disabled");
                
            }
            

        } else if (intOpcion_t != 0) {

            var today = moment().format('YYYY-MM-DD');
            var filtroDefault_t = $('#selCampo_1 option:nth('+intOpcion_t+')').val();

            $("#selCondicion_1").val(" ").trigger("change").attr("disabled", false);
            $("#selCampo_1").val(filtroDefault_t).trigger("change");
            $("#selOperador_1").val("=").trigger("change");
            $("#selCampo_1 , #selOperador_1, #selCondicion_1").removeClass("filtroObligatorio");


            $("#valor_1").val(today); 
            $("#selCampo_1").removeAttr("disabled");
            $("#selOperador_1").removeAttr("disabled");  



            // Se agrega un segundo filtro para los reportes que son embebidos en los pasos verdes

            <?php if (isset($_GET['paso']) && $_GET['paso'] != 0 &&  isset($_GET['stepUnique']) && $_GET['stepUnique'] == 'green' ): ?>

                NuevaCondicion();

                $("#selCampo_2").val($('#selCampo_2 option:nth(10)').val()).trigger("change");
                $("#selOperador_2").val("=").trigger("change");

                $("#valor_2").val(<?=$_GET['paso']?>); 
                $("#selCondicion_2").val("AND").trigger("change"); 

                
            <?php endif; ?>

            
        }

    }

    setTimeout(function(){ $("#Flujograma").removeClass('in'); }, 3000);

    function creacionFiltroDinamicoG(){

        clearFiltros(true);

        var tipoReport_t = $("#selGraficas").val();
        var intIdBd_t = $("#selGraficas option:selected").attr("idbd");

        var strNombreFiltro_t = "FECHA_CREACION_DY";

        if (tipoReport_t == "sc") {

            strNombreFiltro_t = "FECHA_GESTION_DY";

        }

        var strJSON_Campos_t = '[{"campoId":"G'+intIdBd_t+'_FechaInsercion","nombre":"'+strNombreFiltro_t+'","tipo":"5"}]';

        $("#jsonCampos").val(strJSON_Campos_t);

        $("#selCampo_1").html('<option value="0" tipo="3">Seleccione</option>');

        arrJSON_Campos_t = JSON.parse(strJSON_Campos_t);

        $.each(arrJSON_Campos_t, function(index,value) {

            $("#selCampo_1").append('<option value="'+value.campoId+'" campoLista="'+value.campoLista+'" tipo="'+value.tipo+'" idBG="'+value.idbg+'" idPregun="'+value.idpregun+'">'+value.nombre+'</option>');

        });

        filtrosPorDefecto(tipoReport_t+"G");
        
    }

    $("#selGraficas").change(function(){

        creacionFiltroDinamicoG();

    });

    function creacionFiltroDinamico(intTipoSecReporte_p){

        if (intTipoSecReporte_p != "3") {
            
            clearFiltros(true);
            var arrJSON_Campos_t = new Array();
            var tipoReport_t = $("#sql_query").val();
            var intIdHuesped_t = <?=$_SESSION["HUESPED"]?>;
            var intIdEstrat_t = $("#sql_query option:selected").attr("idEstrat");
            var intIdBd_t = $("#sql_query option:selected").attr("idbd");
            var intIdPaso_t = $("#sql_query option:selected").attr("idPaso");
            var intIdTipo_t = $("#sql_query option:selected").attr("idTipo");
            var intIdGuion_t = $("#sql_query option:selected").attr("idguion");
            var intIdCBX_t = $("#sql_query option:selected").attr("idcampancbx");
            var intIdPeriodo_t = $("#sql_query option:selected").attr("periodo");
            var intIdMuestra_t = $("#sql_query option:selected").attr("idmuestra");
            var tipoDistribucionCampana = -1;

            var strJSON_Campos_t = traerCamposDelReporte(tipoReport_t,intIdHuesped_t,intIdEstrat_t,intIdBd_t,intIdPaso_t,intIdTipo_t,intIdGuion_t,intIdCBX_t,intIdPeriodo_t,intIdMuestra_t);

            if(tipoReport_t == "ordenamiento"){
                tipoDistribucionCampana = obtenerTipoDistribucionCampana(intIdCBX_t);
            }

            if (isNaN(tipoReport_t) === false) {

                if (Number(tipoReport_t) > 2) {

                    $("#secDivFiltros").hide();
                    
                }else{

        
                    $("#secDivFiltros").show();
                    arrJSON_Campos_t = JSON.parse(strJSON_Campos_t);


                }

            }else{

                if(tipoReport_t == "ordenamiento" && tipoDistribucionCampana == -1){
                    $("#secDivFiltros").hide();
                }else{
                    $("#secDivFiltros").show();
                    arrJSON_Campos_t = JSON.parse(strJSON_Campos_t);
                }

            }


            $("#jsonCampos").val(strJSON_Campos_t);

            $("#selCampo_1").html('<option value="0" tipo="3">Seleccione</option>');

            $.each(arrJSON_Campos_t, function(index,value) {

                let printCampan = '<?php 
                    if (isset($_GET["stepUnique"])) {
                        if ($_GET["stepUnique"] == "campan" || $_GET["stepUnique"] == "campanout") {
                            echo 'pasoCampan="'.$_GET["paso"].'"'; 
                        }
                    } else {
                        echo "";
                    } ?>';
                $("#selCampo_1").append('<option value="'+value.campoId+'" campoLista="'+value.campoLista+'" tipo="'+value.tipo+'" idBG="'+value.idbg+'" idPregun="'+value.idpregun+'" '+printCampan+' >'+value.nombre+'</option>');

            });
            
            filtrosPorDefecto(tipoReport_t);

            // Se deshabilita el exporte en los resumenes

            if(tipoReport_t == "historicoIVRResum" || tipoReport_t == "encuestasIVRResumenAgente" || tipoReport_t == "encuestasIVRResumenPregun" || tipoReport_t == "erlang" ){
                $("#btnExport").attr("disabled","disabeld");
                $("#btnExport").addClass("filtroObligatorio");
                $("#reporte_pivot").hide();
            }else{
                $("#btnExport").removeAttr("disabled");
                $("#btnExport").removeClass("filtroObligatorio");
                $("#reporte_pivot").show();
            }


            if(tipoReport_t == "erlang" || (tipoReport_t == "ordenamiento" && tipoDistribucionCampana == -1)){
                $("#btnNuevoFiltro").attr("disabled","disabeld");
            }else{
                $("#btnNuevoFiltro").removeAttr("disabled");
            }

            // Se adiciona un label para explicar el uso del input multiselectivo en erlang
            if(tipoReport_t == "erlang"){
                $("#labelNotas").html("<p><label>Nota:   </label> Puede realizar el análisis para una o varias campañas simultáneamente. Para seleccionar varias campañas, simplemente haga clic en cada una de ellas y automáticamente se agregarán etiquetas correspondientes a cada campaña seleccionada.</p>");
            }else{
                $("#labelNotas").html("");
            }
        
            
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

            // $("#resultados").html(strHTMLReporte_t);
            $("#resultados").html('');

            $("#pivotTable").html('');
             
            if (first_load) {
                $('#FormularioDatos :input').each(function(){
                    $(this).attr('disabled', true);
                });
                first_load = false;
            }
            

        }else{

            creacionFiltroDinamicoG();

        }

    }

    $('#sql_query').on('change', function(){

        creacionFiltroDinamico(0);
        
    });
    
    $('#FormularioDatos :input').each(function(){
        $(this).attr('disabled', true);
    });
    

</script>

<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
    var contadorAsuntos = 1;
    var PasosArray = '';
    $(function(){


        $('.fecha').datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        }).datepicker("setDate", new Date());

        //color picker with addon
        $('.my-colorpicker2').colorpicker();


        //Numero
        $("#G2_C69").numeric();


        $('#txtFileCampana').on('change', function(e){
            var imax = $(this).attr('valor');
            var imagen = this.files[0];
            /* Validar el tipo de imagen */
            if(imagen['type'] != 'image/jpeg'){
                $('#txtFileCampana').val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo debe estar en formato JPG",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }else if(imagen['size'] > 2000000 ) {
                $('#txtFileCampana').val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo no debe pesar mas de 2MB",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }else{
                if(imagen['type'] == 'image/jpeg' ){
                    var datosImagen = new FileReader();
                    datosImagen.readAsDataURL(imagen);

                    $(datosImagen).on("load", function(event){
                        var rutaimagen = event.target.result;
                        $('#avatar3').attr("src",rutaimagen);   
                        $("#hidOculto").val(1);                            
                    }); 
                }
                
            }   
        }); 

        $("#estrategias").addClass('active');

        busqueda_lista_navegacion();
        
        <?php if(isset($_GET['idEstrat'])){ ?>
                $("#<?php echo $_GET['idEstrat'];?>").click();
        <?php }else{ ?>
                $(".CargarDatos :first").click();
        <?php } ?>
        
      
        $("#btnLlamadorAvanzado").click(function(){
            $('#busquedaAvanzada_ :input').each(function(){
                $(this).attr('disabled', false);
            });
        });

        

        $("#tablaScroll").on('scroll', function() {
            //alert('Si llegue');
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                
                $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si' }, function(data){
                    if(data != ""){
                        $("#TablaIzquierda").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                });
            }
        });

        //SECCION FUNCIONALIDAD BOTONES

        //Funcionalidad del boton + , add
        $("#add").click(function(){

            $("#crearCampanhasNueva").modal();

            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);    

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);   
            $("#Save").attr('disabled', false);
            $("#horasEnvio").attr('disabled' ,false);
            $("#EnviarReporte").attr('disabled' ,false);
            
           

            //Inializacion campos vacios por defecto
            $('#FormularioDatos :input').each(function(){
                if($(this).is(':checkbox')){
                    if($(this).is(':checked')){
                        $(this).attr('checked', false);
                    }
                    $(this).attr('disabled', false); 
                }else{
                    $(this).val('');
                    $(this).attr('disabled', false); 
                }
                               
            });

            $(".select2").each(function(){
                $(this).val(0).change();
            });

            $("#G2_C14").val('#00a7d0');

            $("#txtOcultoColor").val('#00a7d0');

            $("#hidId").val(0);

            $("#h3mio").html('');

            $("#hidOculto").val(0);

            $("#G2_C5").val(<?php echo $_SESSION['HUESPED'];?>);
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";

            $("#avatar3").attr('src', 'assets/img/user2-160x160.jpg');



            $("#myDiagramDiv").html('');
        });

        jQuery.fn.reset = function () {
            $(this).each (function() { this.reset(); });
        }

        //funcionalidad del boton editar
        $("#edit").click(function(){

            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);    

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);   
            $("#Save").attr('disabled', false);
            $("#horasEnvio").attr('disabled' ,false);
            $("#EnviarReporte").attr('disabled' ,false);

            
            //Le informa al crud que la operaciòn a ejecutar es editar registro
            $("#oper").val('edit');
            //Habilitar todos los campos para edicion
            $('#FormularioDatos :input').each(function(){
                $(this).attr('disabled', false);
            });

            $("#flujoEstrat").attr('disabled', false);


            if($("#G2_C6").val() == 3){
                $("#flujoEstrat").attr('onClick' , 'location.href=\'<?=base_url?>modulo/flujograma/'+idTotal+'/'+$("#G2_C69").val()+'\'');
            }else if($("#G2_C6").val() == 2){
                $.ajax({
                    url    : '<?php echo $url_crud;?>',
                    type   : 'post',
                    data   : {
                        traeMiPaso : true,
                        idEstrat   : idTotal
                    },
                    success : function(data){
                        $("#flujoEstrat").attr('onClick' , 'location.href=\'<?=base_url?>modulo/paso/entrantes/'+ data  +'/'+$("#G2_C69").val()+'\'');
                    }
                });
               
            }else if($("#G2_C6").val() == 1){
                $.ajax({
                    url    : '<?php echo $url_crud;?>',
                    type   : 'post',
                    data   : {
                        traeMiPaso : true,
                        idEstrat   : idTotal
                    },
                    success : function(data){
                        $("#flujoEstrat").attr('onClick' , 'location.href=\'<?=base_url?>modulo/paso/campan/'+ data +'/'+$("#G2_C69").val()+'\'');
                    }
                });
                
            }

            if($("#G2_C69").val() != 0){
               $("#G2_C69").attr('disabled', true); 
            }else{
               $("#G2_C69").attr('disabled', false); 
            }


            // Se ejecuta el trigger change en los campos de los correos para forzar la validacion de estos
            $("input[id^='txtAquienVa_'], input[id^='txtCopiaA_']").trigger("change");
          
        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function(){
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);

            $("#horasEnvio").attr('disabled' , true);
            $("#EnviarReporte").attr('disabled' , true);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);

            <?php if(isset($_GET['view'])){ ?>
                window.location.href  = "cancelar.php";
            <?php }  ?>
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function(){
            //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
            alertify.confirm("¿Está seguro de eliminar el registro seleccionado?", function (e) {
                //Si la persona acepta
                if (e) {
                    var id = $("#hidId").val();
                    //se envian los datos, diciendo que la oper es "del"
                    $.ajax({
                        url      : '<?=$url_crud_extender?>',
                        type     : 'POST',
                        data     : { id : id , deleteAllEstrat : 'si' },
                        success  : function(data){
                            if(data == '1'){  
                                 // limpiar el formulario
                                 document.getElementById("FormularioDatos").reset(); 
                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                llenar_lista_navegacion('');
                            }else{
                                //Algo paso, hay un error
                                alert(data);
                            }
                        },
                        beforeSend : function(){
                            $.blockUI({ 
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                    
                } else {
                    
                }
            }); 
        });


        //datos Hoja de busqueda
        $("#BtnBusqueda_lista_navegacion").click(function(){
            //alert($("#table_search_lista_navegacion").val());
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        });
        
        //Cajaj de texto de bus queda
        $("#table_search_lista_navegacion").keypress(function(e){
            if(e.keyCode == 13)
            {
                llenar_lista_navegacion($(this).val());
            }
        });

        //preguntar cuando esta vacia la tabla para dejar solo los botones correctos habilitados
        var g = $("#tablaScroll").html();
        if(g === ''){
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true); 
        }
    });
</script>

<script type="text/javascript" src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_eventos.js"></script> 
<script type="text/javascript">
    $(function(){
        
        /*$("#myDiagramDiv").click(function(){
            $("#frameContenedor").attr('src', 'mostrar_popups.php?view=flujograma&estrategia='+idTotal);
            $("#editarDatos").modal();
        });*/

        init();
        <?php if(isset($_GET['estrategia'])) { ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos : 'SI', id : <?php echo $_GET['estrategia']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    $("#G2_C5").val(item.G2_C5);

                    $("#G2_C6").val(item.G2_C6);

                    $("#G2_C7").val(item.G2_C7);

                    $("#G2_C8").val(item.G2_C8);

                    $("#G2_C9").val(item.G2_C9);

                    $("#G2_C10").val(item.G2_C10);

                    $("#G2_C10").val(item.G2_C10).trigger("change"); 

                    $("#G2_C11").val(item.G2_C11);

                    $("#G2_C11").val(item.G2_C11).trigger("change"); 

                    $("#G2_C12").val(item.G2_C12);

                    $("#G2_C12").val(item.G2_C12).trigger("change"); 

                    $("#G2_C13").val(item.G2_C13);

                    $("#G2_C13").val(item.G2_C13).trigger("change"); 


                    $("#G2_C69").val(item.G2_C69).trigger("change"); 

                    //$("#G2_C14").val(item.G2_C14);
                    $("#txtOcultoColor").val(item.G2_C14);

                    $("#str_IdColor").attr('style', 'background-color :'+item.G2_C14);

                    $("#h3mio").html(item.principal);



                    $("#avatar3").attr('src', item.imagenes);
                    idTotal = <?php echo $_GET['estrategia'];?>;
                    
                    //$("#frameContenedor").attr('src', 'mostrar_popups.php?view=flujograma&estrategia='+idTotal);
                    //$("#title_estrat").html('<?php echo strtoupper($str_Editar_estrategia);?> '+ item.G2_C7);

                    $.ajax({
                        url         : '<?php echo $url_crud;?>',
                        type        : 'post',
                        data        : { id : <?php echo $_GET['estrategia']; ?>, traer_Flujograma: 'si'},
                        success     :function(data){
                            console.log('data :>> ', data);
                            $("#G2_C9").val(data);
                            load();
                        }
                    });

                    if ( $("#"+idTotal).length > 0) {
                        $("#"+idTotal).click();   
                        $("#"+idTotal).addClass('active'); 
                    }else{
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }
                });
                
            } 
        });

        
            $("#oper").val("edit");
            $("#hidId").val(<?php echo $_GET['estrategia'];?>);
            vamosRecargaLasGrillasPorfavor(<?php echo $_GET['estrategia'];?>);
        <?php } ?>
        //Select2 estos son los guiones
                                      

        //datepickers
        

        //Timepickers
        $("#G2_C6").change(function(){
            if($(this).val() == 3){
                $("#flujoEstrat").attr('onClick' , 'location.href=\'<?=base_url?>modulo/flujograma/'+idTotal+'/'+$("#G2_C69").val()+'\'');
            }else if($(this).val() == 2){
                $.ajax({
                    url    : '<?php echo $url_crud;?>',
                    type   : 'post',
                    data   : {
                        traeMiPaso : true,
                        idEstrat   : idTotal
                    },
                    success : function(data){
                        $("#flujoEstrat").attr('onClick' , 'location.href=\'<?=base_url?>modulo/paso/entrantes/'+ data  +'/='+$("#G2_C69").val()+'\'');
                    }
                });
               
            }else if($(this).val() == 1){
                $.ajax({
                    url    : '<?php echo $url_crud;?>',
                    type   : 'post',
                    data   : {
                        traeMiPaso : true,
                        idEstrat   : idTotal
                    },
                    success : function(data){
                        $("#flujoEstrat").attr('href' , 'location.href=\'<?=base_url?>modulo/paso/campan/'+ data +'/'+$("#G2_C69").val()+'\'');
                    }
                });
                
            }
        });


        //Validaciones numeros Enteros
        

        $("#G2_C5").numeric();
        
        $("#G2_C6").numeric();
        

        $("#diagramaIndex").click(function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var form = $("#FormularioDatos");
            //Se crean un array con los datos a enviar, apartir del formulario 
            var formData = new FormData($("#FormularioDatos")[0]);
            formData.append('contarAsuntos', contadorAsuntos);
            formData.append('contadorMetasViejas', 'si');
            $.ajax({
               url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data){
                    if(data){
                        window.location = href;         
                    }else{
                        //Algo paso, hay un error
                        alertify.error('Un error ha ocurrido');
                    }                
                },
                beforeSend:function(){
                    $.blockUI({ 
                        message : '<h3><?php echo $str_message_wait; ?></h3>',
                        css: { 
                            border: 'none', 
                            padding: '1px', 
                            backgroundColor: '#000', 
                            '-webkit-border-radius': '10px', 
                            '-moz-border-radius': '10px', 
                            opacity: .5, 
                            color: '#fff' 
                        } 
                    }); 
                },
                complete:function(){
                    $.unblockUI();
                },
                //si ha ocurrido un error
                error: function(){
                    after_save_error();
                    alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                }
            });
        });


        //Validaciones numeros Decimales
        $("#Save").click(function(){

            let valido = true;
            let errorMessage = "";

            //Validamos los correos que este bien

            if ($("input[id^='txtAquienVa_'], input[id^='txtCopiaA_']").parent().hasClass("has-error")) {
                valido = false;
                errorMessage = "Debe de ingresar correos validos en los reportes automatizados";
            }

            // validar si se selecciona base de datos 
            if ($('#G2_C69').val() === '0' || $('#G2_C69').val() === '0'  === null ) {
                valido = false;
                errorMessage = "Debe seleccionar una base de datos";
            }


            if(valido){
                var huespedId = '<?php echo $_SESSION['HUESPED'];?>'.trim();
                $.ajax({
                    url: '<?php echo $url_crud; ?>?ConsumirWS=si',
                    type : 'GET',
                    data: {huespedId: huespedId},
                    success: function(respuesta) {
                        console.log("crear vistas=>"+respuesta)                   
                    }
                });
                var balido = 0;
                $(".frmNuevasMetas").each(function(){
                    if($(this).val().length < 1){
                        balido = 1;
                        alertify.error('<?php echo $str_strategia_meta7; ?>');
                    }
                });
                
                if(balido == 0){
                    var form = $("#FormularioDatos");
                    //Se crean un array con los datos a enviar, apartir del formulario 
                    var formData = new FormData($("#FormularioDatos")[0]);
                    formData.append('contarAsuntos', contadorAsuntos);
                    formData.append('contadorMetasViejas', 'si');
                    $.ajax({
                    url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        //una vez finalizado correctamente
                        success: function(data){
                            if(data){
                                <?php if(!isset($_GET['campan'])){ ?>
                                    //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                    if($("#oper").val() == 'add'){
                                        idTotal = data;
                                    }else{
                                        idTotal= $("#hidId").val();
                                    }
                                    $(".modalOculto").hide();

                                    <?php if(isset($_GET['view'])){ ?>
                                        window.location.href  = "finalizado.php";
                                    <?php }  ?>
                                    //Limpiar formulario
                                    form[0].reset();
                                    after_save();
                                    <?php if(isset($_GET['registroId'])){ ?>
                                    $.ajax({
                                        url      : '<?=$url_crud;?>',
                                        type     : 'POST',
                                        data     : { CallDatos : 'SI', id : <?php echo $_GET['registroId']; ?> },
                                        dataType : 'json',
                                        success  : function(data){
                                            //recorrer datos y enviarlos al formulario
                                            $.each(data, function(i, item) {
                                                
        
                                                $("#G2_C5").val(item.G2_C5);
        
                                                $("#G2_C6").val(item.G2_C6);
        
                                                $("#G2_C7").val(item.G2_C7);
        
                                                $("#G2_C8").val(item.G2_C8);
        
                                                $("#G2_C9").val(item.G2_C9);
        
                                                $("#G2_C10").val(item.G2_C10);

                                                $("#G2_C10").val(item.G2_C10).trigger("change"); 
        
                                                $("#G2_C11").val(item.G2_C11);

                                                $("#G2_C11").val(item.G2_C11).trigger("change"); 
        
                                                $("#G2_C12").val(item.G2_C12);

                                                $("#G2_C12").val(item.G2_C12).trigger("change"); 
        
                                                $("#G2_C13").val(item.G2_C13);

                                                $("#G2_C13").val(item.G2_C13).trigger("change"); 

                                                $("#G2_C69").val(item.G2_C69).trigger("change"); 
        
                                                //$("#G2_C14").val(item.G2_C14);

                                                $("#h3mio").html(item.principal);
                                            });

                                            //Deshabilitar los campos

                                            //Habilitar todos los campos para edicion
                                            $('#FormularioDatos :input').each(function(){
                                                $(this).attr('disabled', true);
                                            });

                                            //Habilidar los botones de operacion, add, editar, eliminar
                                            $("#add").attr('disabled', false);
                                            $("#edit").attr('disabled', false);
                                            $("#delete").attr('disabled', false);

                                            //Desahabiliatra los botones de salvar y seleccionar_registro
                                            $("#cancel").attr('disabled', true);
                                            $("#Save").attr('disabled', true);
                                        } 
                                    })
                                    $("#hidId").val(<?php echo $_GET['registroId'];?>);
                                    <?php } else { ?>
                                        llenar_lista_navegacion('');
                                    <?php } ?>   

                                <?php }else{ ?>
                                
                                    
                    
                                <?php } ?>        
                            }else{
                                //Algo paso, hay un error
                                alertify.error('Un error ha ocurrido');
                            }                
                        },
                        beforeSend:function(){
                            $.blockUI({ 
                                message : '<h3><?php echo $str_message_wait; ?></h3>',
                                css: { 
                                    border: 'none', 
                                    padding: '1px', 
                                    backgroundColor: '#000', 
                                    '-webkit-border-radius': '10px', 
                                    '-moz-border-radius': '10px', 
                                    opacity: .5, 
                                    color: '#fff' 
                                } 
                            }); 
                        },
                        complete:function(){
                            $.unblockUI();
                        },
                        //si ha ocurrido un error
                        error: function(){
                            after_save_error();
                            alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                        }
                    });
                }
            }else{
                alertify.error(errorMessage);
            }
            
        });
    });

    //SECCION  : Manipular Lista de Navegacion

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(x){
        var tr = '';
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatosJson : 'SI', Busqueda : x},
            dataType : 'json',
            success  : function(data){
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {
                    tr += "<tr class='CargarDatos' id='"+data[i].id+"'>";
                    tr += "<td>";
                    tr += "<p style='font-size:14px;'><b>"+data[i].camp1+"</b></p>";
                    tr += "<p style='font-size:12px; margin-top:-10px;'>"+data[i].camp2+"</p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ( $("#"+idTotal).length > 0) {
                    $("#"+idTotal).click();   
                    $("#"+idTotal).addClass('active'); 
                }else{
                    //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                    $(".CargarDatos :first").click();
                }

            } 
        });
    }

    function clearFiltros(clearJSON){

        $(".rows").each(function(i){
            if ($(this).attr("id")=="row_1") {
                $("#tipo_1").val("0");
                if (clearJSON) {

                    $("#jsonCampos").val("");

                }
                $("#inpCantFiltros").val("1");
                $("#selCampo_1").val(0).trigger("change");
                $("#divValor_1").html('<input type="text" class="form-control input-sm campoValor" id="valor_1" name="valor_1" placeholder="VALOR">');
                $("#selOperador_1").html('<option value="0">Seleccione</option>');
            }else{
                $(this).remove();
            }
        });

    }

    function limpiarGraficas(limpiar_p,sentido_p){

        $(".tdInOut").text("");
        $(".tdInIn").text("");

        if (limpiar_p == "sc") {

                $("#graficaBD_1").html("");
                $("#graficaBD_2").html("");
                $("#graficaBD_3").html("");
                $("#graficaBD_4").html("");

                $("#lienzoGeneral").hide();

                $("#lienzoCampanas").show();

                if (sentido_p != "1") {

                    $("#tabInIn").hide();
                    $("#tabInOut").show();

                }else{

                    $("#tabInIn").show();
                    $("#tabInOut").hide();

                }

        }else if(limpiar_p == "bd"){

                $("#graficaSC_1").html("");
                $("#graficaSC_2").html("");
                $("#graficaSC_3").html("");
                $("#graficaSC_4").html("");

                $("#lienzoCampanas").hide();

                $("#lienzoGeneral").show();

                $("#tabInIn").hide();
                $("#tabInOut").hide();

        }else{

                $("#graficaBD_1").html("");
                $("#graficaBD_2").html("");
                $("#graficaBD_3").html("");
                $("#graficaBD_4").html("");

                $("#graficaSC_1").html("");
                $("#graficaSC_2").html("");
                $("#graficaSC_3").html("");
                $("#graficaSC_4").html("");

                $("#lienzoCampanas").hide();

                $("#lienzoGeneral").show();

                $("#tabInIn").hide();
                $("#tabInOut").hide();

        }

    }

    function dibujarGraficas(){

            var strValor_t = $("#selGraficas").val();
            var idBd = $("#selGraficas option:selected").attr("idBd");
            var idEstpas = $("#selGraficas option:selected").attr("idEstpas");
            var idTipo = $("#selGraficas option:selected").attr("idTipo");

            var strHTM_Lienzo_t = '';

            var arrNumerosFiltros_t = new Array();
            var objDataReport_t = new Object();

            var form = $("#divFiltros .rows > input, #divFiltros .rows .form-group > input, #divFiltros .rows .form-group > select");


            $(".rows").each(function(i){
                arrNumerosFiltros_t[i]=$(this).attr("numero");
            });

            var intErrores_t = 0;

            $(".campoFiltro").each(function(i){
                if ($(this).val() == "0") {
                    intErrores_t++;
                    alertify.error("Debe seleccionar el campo a filtrar.");
                    $(this).closest(".form-group").addClass("has-error");
                }else{
                    $(this).closest(".form-group").removeClass("has-error");
                }
            });
            
            $(".campoValor").each(function(i){

                console.log(this.type+" ID "+this.id);

                if (this.type == "text") {

                    if (this.value == "") {

                        intErrores_t++;
                        alertify.error("Debe diligenciar el campo.");
                        $(this).closest(".form-group").addClass("has-error");

                    }

                }else if(this.type == "select-one"){

                    if (this.value == -1 || this.value == null) {

                        intErrores_t++;
                        alertify.error("Debe diligenciar el campo.");
                        $(this).closest(".form-group").addClass("has-error");

                    }



                }

            });

            if (intErrores_t == 0) {

                var arrDataFil_t = JSON.parse($.ajax({
                                                url: '<?=$url_crud;?>?filtroAvanzadoJSON=true',
                                                type:'POST',
                                                data:form,
                                                dataType : 'JSON',
                                                global:false,
                                                async:false,
                                                success:function(data){
                                                    return data;
                                                }
                                            }).responseText);

                objDataReport_t = Object.assign(objDataReport_t, arrDataFil_t);
                objDataReport_t = Object.assign(objDataReport_t, {totalFiltros : arrNumerosFiltros_t});

                if (strValor_t == "sc") {

                    limpiarGraficas("sc",idTipo);

                    graficasSC(idTipo,idBd,idEstpas,objDataReport_t);

                }else{

                    limpiarGraficas("bd",idTipo);

                    graficasBd(objDataReport_t,idBd);

                }

            }


    }

    function getReports(id,paso=0){
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: { TraerReportes : id, intIdHuesped : <?=$_SESSION['HUESPED']?>, paso:paso <?php if(isset($_GET["stepUnique"])): ?> , stepUnique:"<?=$_GET["stepUnique"]?>" <?php endif; ?>},
            dataType: 'HTML',
            // async : false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> PROCESANDO PETICION'
                })
            },
            success: function(data) {
                $('#sql_query').html(data);
                $('#sql_query').val("bd").trigger("change");
                
                if(paso!=0){
                // Se valida el tipo de reporte que se debe de seleccionar automaticamente
                    <?php   
                    $stepUnique = (isset($_GET["stepUnique"])) ? $_GET["stepUnique"] : false;
                    $optionSelected = '';

                    switch ($stepUnique){
                        case "campan":
                        case "campanout":
                        case "red":
                        case false :
                            $optionSelected = "bdpaso";
                            break;
                        case "bkpaso":
                            $optionSelected = "bkpaso";
                            break;
                        case "bot":
                            $optionSelected = "gsbot";
                            break;
                        case "yellow":
                            $typeStep = (isset($_GET["typeStep"])) ? $_GET["typeStep"] : false;
                            switch ($typeStep){
                                case "comMail":
                                    $optionSelected = "comMail";
                                    break;
                                case "comSms":
                                    $optionSelected = "comSms";
                                    break;
                                case "comChat":
                                    $optionSelected = "comChat";
                                    break;
                                case "comWebForm":
                                    $optionSelected = "comWebForm";
                                    break;
                            }
                            break;
                        default:
                            $optionSelected = "bd";
                            break;
                    }

                    if($stepUnique): ?>
                        $('#sql_query').val("<?=$optionSelected?>").trigger("change");
                    <?php endif; ?>

                }

            },
            complete: function() {
                $.unblockUI();
            },
            error: function() {
                alertify.error(`Ocurrio un error al procesar la solicitud ${data}`)
                $.unblockUI();
            }
        });

        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: { TraerGraficas : id, intIdHuesped : <?=$_SESSION['HUESPED']?>},
            dataType: 'HTML',
            // async: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> PROCESANDO PETICION'
                })
            },
            success  : function(data){
                $('#selGraficas').html(data);
            },
            complete: function() {
                $.unblockUI()
            },
            error: function() {
                alertify.error(`Ocurrio un error al procesar la solicitud ${data}`)
                $.unblockUI()
            }
        });

        limpiarGraficas("gn",0);
    }

    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion(){

        $(".CargarDatos").click(function(){
            first_load = true;
            clearFiltros(true);
            
            //Deshabilitar los campos

            //Habilitar todos los campos para edicion
            $('#FormularioDatos :input').each(function(){
                $(this).attr('disabled', true);
            });

            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');
              
              
            var id = $(this).attr('id');
            $("#IdEstrat").val(id);

            $("#s_reportes_pantalla").attr("class","panel-collapse collapse");
            $("#s_envio_reportes").attr("class","panel-collapse collapse");

            getReports(id);
            $(".GSTemporal").remove();

            $("#Flujograma").addClass('in');

            setTimeout(function(){ $("#Flujograma").removeClass('in'); }, 3000);

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

            // $("#resultados").html(strHTMLReporte_t);
            $("#resultados").html('');

            $("#pivotTable").html('');
            //$("#frameContenedor").attr('src', 'mostrar_popups.php?view=flujograma&estrategia='+id);

            //buscar los datos
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    $("#mail_dinamico_1").attr("hidden",false);
                    $("#mail_dinamico_2").attr("hidden",false);
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        
                        $("#G2_C5").val(item.G2_C5);

                        $("#G2_C6").val(item.G2_C6);

                        $("#G2_C7").val(item.G2_C7);

                        $("#G2_C8").val(item.G2_C8);

                        $("#G2_C10").val(item.G2_C10);
 
                        $("#G2_C10").val(item.G2_C10).trigger("change"); 

                        $("#G2_C11").val(item.G2_C11);
 
                        $("#G2_C11").val(item.G2_C11).trigger("change"); 

                        $("#G2_C12").val(item.G2_C12);
 
                        $("#G2_C12").val(item.G2_C12).trigger("change"); 

                        $("#G2_C13").val(item.G2_C13);
 
                        $("#G2_C13").val(item.G2_C13).trigger("change"); 

                        $("#G2_C69").val(item.G2_C69);

                        $("#G2_C69").val(item.G2_C69).trigger("change"); 

                        //$("#G2_C14").val(item.G2_C14);
                        $("#txtOcultoColor").val(item.G2_C14);

                        $("#str_IdColor").attr('style', 'background-color :'+item.G2_C14);

                        $("#title_estrat").html('<?php echo strtoupper($str_Editar_estrategia);?> '+ item.G2_C7);
                        
                        $("#avatar3").attr('src', item.imagenes);
                        
                        $("#h3mio").html(item.principal);

                        callDatosPregun(item.G2_C69)
                 
                    });

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });

            $.ajax({
                url         : '<?php echo $url_crud;?>',
                type        : 'post',
                data        : { id : id, traer_Flujograma: 'si'},
                success     :function(data){
                    $("#G2_C9").val(data);
                    load();
                }
            });

            $.ajax({
                url         : '<?=$url_crud_extender?>',
                type        : 'post',
                data        : { id : id, getMetas: 'si', idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0] ;?>' },
                success     :function(data){
                    //$("#metasVanAqui").html(data);
                }
                
            });

            $.ajax({
                url         : '<?=$url_crud_extender?>',
                type        : 'post',
                data        : { id : id, getPasos: 'si', idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0] ;?>' },
                success     :function(data){
                    PasosArray = data;
                }
                
            });

            $("#hidId").val(id);

            idTotal = $("#hidId").val();

            //traer los reportes de esta estrategia 
            $("#reportesGenerados").html("");

            $.ajax({
                url   :  '<?=$url_crud_extender?>',
                type  :  'post',
                data  : {getReportes     : $("#hidId").val() , id_estrategia   : $("#hidId").val(), idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'},
                success : function(data){
                    $("#reportesGenerados").html(data);
                    $(".horaEnvioTxt").timepicker({
                        'timeFormat': 'H:i',
                        'minTime': '00:00',
                        'maxTime': '20:00',
                        'setTime': '08:00',
                        'step'  : '5',
                        'showDuration': true
                    });     
                    $(".deleteCorreoF").click(function(){
                        var id_correo = $(this).attr('aborrar');
                        alertify.confirm("<?php echo $str_message_generico_D; ?>", function (e) {
                            if (e) {
                                $.ajax({
                                    type  : 'post',
                                    data  : { deleteEnvioCorreo : true , idEnvioCorreo : id_correo },
                                    url   : '<?=$url_crud_extender?>',
                                    success : function(data){
                                        if(data == '1'){
                                            alertify.success('<?php echo $str_Exito;?>');
                                            $("#"+id_correo).remove();
                                        }else{
                                            alertify.error('<?php echo $error_de_proceso;?>');
                                        }
                                    }
                                })
                            }
                        });
                        
                    });

                    // Adicionamos los eventos change para la validacion de correos
                    validateEmailsChange();
                }
            });  

            $("#diagramaIndex").attr('disabled', true);

        });
    }

    function validateEmailsChange() {
        $("input[id^='txtAquienVa_'], input[id^='txtCopiaA_']").change((e) => {
            let inputChanged = $(e.target);
                if(inputChanged.val().trim().match(/^([a-zA-Z0-9.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})(,[a-zA-Z0-9.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})*$/) || inputChanged.val() == ""){
                    inputChanged.parent().removeClass("has-error");
                    inputChanged.parent().children("span").html("");
                }else{
                    inputChanged.parent().addClass("has-error");
                    inputChanged.parent().children("span").html("Debe de ingresar correos validos");
                }
            });
    }

    function validateNewEmailsChange() {
        $("input[id^='GtxtAquienVa'], input[id^='GtxtCopiaA_']").change((e) => {
            let inputChanged = $(e.target);
                if(inputChanged.val().trim().match(/^([a-zA-Z0-9.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})(,[a-zA-Z0-9.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})*$/) || inputChanged.val() == ""){
                    inputChanged.parent().removeClass("has-error");
                    inputChanged.parent().children("span").html("");

                }else{
                    inputChanged.parent().addClass("has-error");
                    inputChanged.parent().children("span").html("Debe de ingresar correos validos");
                }
            });
    }

    function seleccionar_registro(value){
        //Seleccinar loos registros de la Lista de navegacion, 
        if ( $("#"+idTotal).length > value) {
            $("#"+idTotal).click();   
            $("#"+idTotal).addClass('active'); 
            idTotal = 0;
            $(".modalOculto").hide();
        }else{
            $(".CargarDatos :first").click();
        } 
         
    } 

    function seleccionar_registro_avanzada(id){
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);
                    $.each(data, function(i, item) {
                        

                    

                        $("#G2_C5").val(item.G2_C5);

                        $("#G2_C6").val(item.G2_C6);

                        $("#G2_C7").val(item.G2_C7);

                        $("#G2_C8").val(item.G2_C8);

                        $("#G2_C9").val(item.G2_C9);

                        $("#G2_C10").val(item.G2_C10);
 
                        $("#G2_C10").val(item.G2_C10).trigger("change"); 

                        $("#G2_C11").val(item.G2_C11);
 
                        $("#G2_C11").val(item.G2_C11).trigger("change"); 

                        $("#G2_C12").val(item.G2_C12);
 
                        $("#G2_C12").val(item.G2_C12).trigger("change"); 

                        $("#G2_C13").val(item.G2_C13);
 
                        $("#G2_C13").val(item.G2_C13).trigger("change"); 

                        $("#G2_C14").val(item.G2_C14);
                        $("#h3mio").html(item.principal);
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function(){
                        $(this).attr('disabled', true);
                    });

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });

            $("#hidId").val(id);
            idTotal = $("#hidId").val();
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            $("#"+idTotal).addClass('active');
    }

    function addImage4(e){
        var file = e.target.files[0],
        imageType = /image.*/;

        if (!file.type.match(imageType))
            return;

        var reader = new FileReader();
        reader.onload = fileOnload4;
        reader.readAsDataURL(file);
    }

    function fileOnload4(e) {
        var result= e.target.result;
        $('#avatar3').attr("src",result);
    }

    <?php if(isset($_GET['report'])) :?>
        //Habilitar todos los campos para edicion
        $('#FormularioDatos :input').each(function(){
            $(this).attr('disabled', false);
        });
        $('#hidId').val('<?=$_GET['estrat']?>');
        getReports('<?=$_GET['estrat']?>','<?=$_GET['paso']?>');
    <?php endif; ?>
</script>

<script type="text/javascript" id="code">
    var colors = {
        blue:   "#00B5CB",
        orange: "#F47321",
        green:  "#C8DA2B",
        gray:   "#888",
        white:  "#F5F5F5"
    }
    function init() {
        if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
        var $ = go.GraphObject.make;  // for conciseness in defining templates
    myDiagram =
        $(go.Diagram, "myDiagramDiv",  // must name or refer to the DIV HTML element
            {
                initialContentAlignment: go.Spot.Center,
                allowDrop: true,  // must be true to accept drops from the Palette
                "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
                "LinkRelinked": showLinkLabel,
                "animationManager.duration": 800, // slightly longer than default (600ms) animation
                "undoManager.isEnabled": true  // enable undo & redo
            }
        );
    // when the document is modified, add a "*" to the title and enable the "Save" button
    myDiagram.addDiagramListener("Modified", function(e) {
        var button = document.getElementById("SaveButton");
            if (button) button.disabled = !myDiagram.isModified;
                var idx = document.title.indexOf("*");
            if (myDiagram.isModified) {
                if (idx < 0) document.title += "*";
            } else {
                if (idx >= 0) document.title = document.title.substr(0, idx);
            }
    });
    // helper definitions for node templates
    function nodeStyle() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                // the Node.location is at the center of each node
                locationSpot: go.Spot.Center,
                //isShadowed: true,
                //shadowColor: "#888",
                // handle mouse enter/leave events to show/hide the ports
                mouseEnter: function (e, obj) { showPorts(obj.part, true); },
                mouseLeave: function (e, obj) { showPorts(obj.part, false); },
                click:function(e, obj){
                    /*console.log(obj.je);
                    console.log(obj.je.key);*/
                    var invocador = obj.je.tipoPaso;
                    var llaveInvocar = obj.je.key;
                    if(invocador == 1){

                    }else if(invocador == 2){

                    }else if(invocador == 3){

                    }else if(invocador == 4){

                    }else if(invocador == 5){

                    }else if(invocador == 6){

                    }else if(invocador == 7){

                    }else if(invocador == 8){

                    }else if(invocador == 9){

                    }

                }
            }
        ];
    }
    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
    // and where the port is positioned on the node, and the boolean "output" and "input" arguments
    // control whether the user can draw links from or to the port.
    function makePort(name, spot, output, input) {
      // the port is basically just a small circle that has a white stroke when it is made visible
        return $(go.Shape, "Rectangle",
                   {
                        fill: "transparent",
                        stroke: null,  // this is changed to "white" in the showPorts function
                        desiredSize: new go.Size(8, 8),
                        alignment: spot,
                        alignmentFocus: spot,  // align the port on the main Shape
                        portId: name,  // declare this object to be a "port"
                        fromSpot: spot,
                        toSpot: spot,  // declare where links may connect at this port
                        fromLinkable: output,
                        toLinkable: input,  // declare whether the user may draw links to/from here
                        cursor: "pointer" // show a different cursor to indicate potential link point
                    });
        }
        // define the Node templates for regular nodes
        var lightText = 'whitesmoke';

        myDiagram.nodeTemplateMap.add("",  // the default category
            $(go.Node, "Spot", nodeStyle(),
            // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#C8DA2B",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "18px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // four named ports, one on each side:
                makePort("T", go.Spot.Top, false, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("EnPhone",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            text: "\uf095",
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        }
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("CargueDatos",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            text: "\uf016",
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        }
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        // myDiagram.nodeTemplateMap.add("EnChat",
        //     $(go.Node, "Spot", nodeStyle(),
        //         $(go.Panel, "Auto",
        //             $(go.Shape, "Circle",
        //                 {
        //                     fill: "#BDBDBD",
        //                     stroke: null
        //                 },
        //                 new go.Binding("figure", "figure")
        //             ),
        //             $(go.TextBlock,
        //                 {
        //                     font: "16px FontAwesome",
        //                     stroke: lightText,
        //                     text: "\uf0e5",
        //                     margin: 8,
        //                     maxSize: new go.Size(160, NaN),
        //                     wrap: go.TextBlock.WrapFit,
        //                     editable: true
        //                 }
        //             )
        //         ),
        //         // three named ports, one on each side except the top, all output only:
        //         makePort("L", go.Spot.Left, true, false),
        //         makePort("R", go.Spot.Right, true, false),
        //         makePort("B", go.Spot.Bottom, true, false)
        //     )
        // );

        // myDiagram.nodeTemplateMap.add("EnMail",
        //     $(go.Node, "Spot", nodeStyle(),
        //         $(go.Panel, "Auto",
        //             $(go.Shape, "Circle",
        //                 {
        //                     fill: "#BDBDBD",
        //                     stroke: null
        //                 },
        //                 new go.Binding("figure", "figure")
        //             ),
        //             $(go.TextBlock,
        //                 {
        //                     font: "16px FontAwesome",
        //                     stroke: lightText,
        //                     text: "\uf003",
        //                     margin: 8,
        //                     maxSize: new go.Size(160, NaN),
        //                     wrap: go.TextBlock.WrapFit,
        //                     editable: true
        //                 }
        //             )
        //         ),
        //         // three named ports, one on each side except the top, all output only:
        //         makePort("L", go.Spot.Left, true, false),
        //         makePort("R", go.Spot.Right, true, false),
        //         makePort("B", go.Spot.Bottom, true, false)
        //     )
        // );

        myDiagram.nodeTemplateMap.add("Formul",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#BDBDBD",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            text: "\uf022",
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // three named ports, one on each side except the top, all output only:
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("salPhone",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#42A5F5",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            text: "\uf095",
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        }
                    )
                ),
           
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("salMail",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#42A5F5",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            text: "\uf003", 
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        }
                    )
                ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );


        myDiagram.nodeTemplateMap.add("salSms",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                        {
                            fill: "#42A5F5",
                            stroke: null
                        },
                        new go.Binding("figure", "figure")
                    ),
                    $(go.TextBlock,
                        {
                            font: "16px FontAwesome",
                            stroke: lightText,
                            margin: 8,
                            text: "\uf10a",
                            maxSize: new go.Size(160, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: true
                        }
                    )
                ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );


        myDiagram.nodeTemplateMap.add("salCheck",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    $(go.Shape, "Circle",
                    {
                        minSize: new go.Size(40, 40),
                        fill: "#42A5F5",
                        stroke: null
                    }),
                  $(go.TextBlock, {
                            text: '\uf046',
                            stroke: '#FFF',
                            margin: 8,
                            font: '16px FontAwesome',
                            editable: true,
                            isMultiline: false
                        }
                    )
                ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        // replace the default Link template in the linkTemplateMap
        myDiagram.linkTemplate =
            $(go.Link,  // the whole link panel
            {
                routing: go.Link.AvoidsNodes,
                curve: go.Link.JumpOver,
                corner: 5,
                toShortLength: 4,
                relinkableFrom: true,
                relinkableTo: true,
                reshapable: true,
                resegmentable: true,
                // mouse-overs subtly highlight links:
                mouseEnter: function(e, link) {
                    link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)";
                },
                mouseLeave: function(e, link) {
                    link.findObject("HIGHLIGHT").stroke = "transparent";
                }
            },
            new go.Binding("points").makeTwoWay(),
            $(go.Shape,  // the highlight shape, normally transparent
                {
                    isPanelMain: true,
                    strokeWidth: 8,
                    stroke: "transparent",
                    name: "HIGHLIGHT"
                }
            ),
            $(go.Shape,  // the link path shape
                {
                    isPanelMain: true,
                    stroke: "gray",
                    strokeWidth: 2
                }
            ),
            $(go.Shape,  // the arrowhead
                {
                    toArrow: "standard",
                    stroke: null,
                    fill: "gray"
                }
            ),
            $(go.Panel, "Auto",  // the link label, normally not visible
                {
                    visible: false,
                    name: "LABEL",
                    segmentIndex: 2,
                    segmentFraction: 0.5
                },
                new go.Binding("visible", "visible").makeTwoWay(),
                $(go.Shape, "Rectangle",  // the label shape
                {
                    fill: "#F8F8F8",
                    stroke: null
                }),
                $(go.TextBlock, "??",  // the label
                {
                    textAlign: "center",
                    font: "8pt helvetica, arial, sans-serif",
                    stroke: "#333333",
                    editable: true
                },
                new go.Binding("text").makeTwoWay())
            )
        );
        // Make link labels visible if coming out of a "conditional" node.
        // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
        function showLinkLabel(e) {
            var label = e.subject.findObject("LABEL");
            if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Circle");
        }
        // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
        myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
        myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;
        load();  // load an initial diagram from some JSON text
        // initialize the Palette that is on the left side of the page

        // create the Palette
         /* var myPalette2 =
            $(go.Palette, "myPaletteDiv",
              { // customize the GridLayout to align the centers of the locationObjects
                layout: $(go.GridLayout, { alignment: go.GridLayout.Location })
              });

          // the Palette's node template is different from the main Diagram's
          myPalette2.nodeTemplate =
            $(go.Node, "Vertical",
              { locationObjectName: "TB", locationSpot: go.Spot.Center },
              $(go.Shape, "Circle",
                { width: 80, height: 80, fill: "white", },
                new go.Binding("fill", "color")),
              $(go.TextBlock, { name: "TB" , editable : true},
                new go.Binding("text", "foot"))
            );

          // the list of data to show in the Palette
          myPalette2.model.nodeDataArray = [
            { key: "IR", color: "indianred",  foot : "Llamadas Entrantes" },
            { key: "LC", color: "lightcoral" , foot : "Entrantes"  },
            { key: "S", color: "salmon",  foot : "Entrantes"  },
            { key: "DS", color: "darksalmon",  foot : "Entrantes"  },
            { key: "LS", color: "#42A5F5", foot : "Llamadas Salientes"  },
            { key: "LS", color: "#42A5F5", foot : "Salientes"  },
            { key: "LS", color: "#42A5F5", foot : "Salientes"  },
            { key: "LS", color: "#42A5F5", foot : "Salientes"  }
          ];*/
        

        // The following code overrides GoJS focus to stop the browser from scrolling
        // the page when either the Diagram or Palette are clicked or dragged onto.
        function customFocus() {
            var x = window.scrollX || window.pageXOffset;
            var y = window.scrollY || window.pageYOffset;
            go.Diagram.prototype.doFocus.call(this);
            window.scrollTo(x, y);
        }
        myDiagram.doFocus = customFocus;
    } // end init
    // Make all ports on a node visible when the mouse is over the node
    function showPorts(node, show) {
        var diagram = node.diagram;
        if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
        node.ports.each(function(port) {
            port.stroke = (show ? "white" : null);
        });
    }
    // Show the diagram's model in JSON format that the user may edit
    function save() {
        document.getElementById("G2_C9").value = myDiagram.model.toJson();
        myDiagram.isModified = false;
    }
    function load() {
        if(document.getElementById("G2_C9").value.length > 1){
            myDiagram.model = go.Model.fromJson(document.getElementById("G2_C9").value);
        }else{
            myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
        }
    }

    // function load_2() {
       
    //     myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
    //     console.log('myDiagram.model :>> ', myDiagram.model);
        
    // }

    $(document).ready(function(){
        $("#estrategias").addClass('active');
    });
</script>


<!-- Esto es para gusrdar la estrategia y el paso -->
<script type="text/javascript">
    $(function(){

    // $("#secFiltros").click(function(){

    //     if ($(this).attr("aria-expanded") == "false") {

    //         setTimeout(() => $("#selCampo_1").select2(), 300);

    //     }

    // });


        $(".regresoCampains").click(function(){
            $("#cancel").click();
        });

        $("#btnSave_Estrat").click(function(){
            var valido = 0;

            if($("#G2_C7_modal").val().length < 1){
                alertify.error("Es necesario escribir el nombre de la estrategia");
                $("#G2_C7_modal").focus();
                valido = 1;
            }

            if($("#G10_C74").val() == 0){
                alertify.error("Es necesario elegir la Base de datos");
                valido = 1;
            }

            if(valido == 0){
                let formData = new FormData($("#formuarioCargarEstoEstrart")[0]);
                formData.append('oper', 'add');
                formData.append('G10_C74', $("#G10_C74").val());
                
                $.ajax({
                    url: '<?=$url_crud?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        data = jQuery.parseJSON(data);
                        if(data.code !='-2'){
                            var idEstrategia = data.idEstrategia;
                            $("#G10_C71").val($("#G2_C7_modal").val());
                                $.ajax({
                                    url: '<?=$url_crud?>',  
                                    type: 'POST',
                                    data: { traePaso : 'SI', idEstrat : idEstrategia},
                                    //una vez finalizado correctamente
                                    success: function(paso){
                                        window.location.href = '<?=base_url?>modulo/flujograma/'+paso+'/'+$("#G10_C74").val();
                                    }
                                })
                                                         
                        }else{
                            if(data.code == '-2'){
                                if(data.messaje=='1062'){
                                    $.unblockUI();
                                    alertify.error('<?php echo $str_message_G_cre1; ?>');
                                }    
                            }else{
                                //Algo paso, hay un error
                                alertify.error(`Un error ha ocurrido ${data.message}`);
                            }                            
                        }
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },                  
                }); 
            }
            
        });

    });
</script>

<!-- Script para usar la funiconalidad del modal de guiones -->
<script type="text/javascript">
    $(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        
        $('#GenerarFromExel').on('ifChecked', function () { 
            $(".excel").show();
            //$("#aja").show();
            $("#G10_C73").attr('disabled', true);
            $("#G10_C74").attr('disabled', true);
        });


        $('#GenerarFromExel').on('ifUnchecked', function () { 
            $(".excel").hide();
            $("#newGuionFile").val('');
            //$("#aja").hide();
            $("#G10_C73").attr('disabled', false);
            $("#G10_C74").attr('disabled', false);
        });


        $('#newGuionFile').on('change', function(e){
            /* primero validar que sea solo excel */
            var imagen = this.files[0];
            if(imagen['type'] != "application/vnd.ms-excel" && imagen['type'] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ){
                $("#newGuionFile").val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo debe estar en formato XLS o XLSX",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }else if(imagen['size'] > 2000000 ) {
                $("#newGuionFile").val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo no debe pesar mas de 2MB",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }        
        });  
    });
</script>

<!-- Agregar mas horarios -->
<script type="text/javascript">
    
    

    $("#horasEnvio").click(function(){
        var cuero = '<div class="row" id="id_'+contadorAsuntos+'">'+
                    '<input type="hidden" value="nuevo" name="txtAsuntosNuevos_'+contadorAsuntos+'" id="txtAsuntosNuevos_'+contadorAsuntos+'">'+
                    '<div class="col-md-3">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repAsunto______; ?></label>'+
                            '<input type="text" class="form-control" name="GtxtNombreReporte_'+ contadorAsuntos +'" id="GtxtNombreReporte_'+ contadorAsuntos +'" placeholder="<?php echo $repAsunto______;?>" value="" >'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repdirijidoa___; ?></label>'+
                            '<input type="email" class="form-control" name="GtxtAquienVa_'+ contadorAsuntos +'" id="GtxtAquienVa_'+ contadorAsuntos +'" value=""  placeholder="<?php echo $repdirijidoa___;?>">'+
                            '<span class="help-block"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repcopia_______; ?></label>'+
                            '<input type="email" class="form-control" name="GtxtCopiaA_'+ contadorAsuntos +'" id="GtxtCopiaA_'+ contadorAsuntos +'" value=""  placeholder="<?php echo $repcopia_______;?>" >'+
                            '<span class="help-block"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $rephorasdeenvio; ?></label>'+
                            '<input type="text" class="form-control" name="GtxtHoraEnvio_'+ contadorAsuntos +'" id="GtxtHoraEnvio_'+ contadorAsuntos +'"  placeholder="<?php echo $rephorasdeenvio; ?>" value = "20:00">'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2 col-xs-10">'+
                        '<div class="form-group">'+
                            '<label><?php echo $campan_periodo_; ?></label>'+
                            '<select class="form-control" onchange="HoraPre(this.value,\'G\','+ contadorAsuntos +')" name="GcmbPeriodicidad_'+ contadorAsuntos +'">'+
                                '<option value="1"><?php echo $campan_diario__;?></option>'+
                                '<option value="2"><?php echo $campan_semanal_;?></option>'+
                                '<option value="3"><?php echo $campan_mensual_;?></option>'+
                            '</select>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-1 col-xs-1">'+
                        '<br>'+
                        '<button type="button" class="btn btn-sm btn-danger deleteCorreo" id= "'+ contadorAsuntos +'">'+
                        '    <i class="fa fa-trash"></i>'+
                        '</button>'+
                    '</div>'+
                '</div>';

       
        $("#horaEnvio").append(cuero);
        $("#GtxtHoraEnvio_"+ contadorAsuntos).timepicker({
            'timeFormat': 'H:i',
            'minTime': '00:00',
            'maxTime': '20:00',
            'setTime': '08:00',
            'step'  : '5',
            'showDuration': true
        });

        $(".deleteCorreo").click(function() {
            var id = $(this).attr('id');
            $("#id_"+id).remove();
        });

        //obtenemos la altura del documento
        var altura = $(document).height();
        $("html, body").animate({scrollTop:altura+"px"});
        contadorAsuntos++;
        validateNewEmailsChange();
    });

    $("#envioAdherencia").click(function(){
        var cuero = '<div class="row" id="id_'+contadorAsuntos+'">'+
                    '<input type="hidden" value="nuevo" name="txtAsuntosNuevos_'+contadorAsuntos+'" id="txtAsuntosNuevos_'+contadorAsuntos+'">'+
                    '<div class="col-md-3">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repAsunto______; ?></label>'+
                            '<input type="text" class="form-control" name="GtxtNombreReporte_'+ contadorAsuntos +'" id="GtxtNombreReporte_'+ contadorAsuntos +'" placeholder="<?php echo $repAsunto______;?>" value="" >'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repdirijidoa___; ?></label>'+
                            '<input type="email" class="form-control" name="GtxtAquienVa_'+ contadorAsuntos +'" id="GtxtAquienVa_'+ contadorAsuntos +'" value=""  placeholder="<?php echo $repdirijidoa___;?>">'+
                            '<span class="help-block"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repcopia_______; ?></label>'+
                            '<input type="email" class="form-control" name="GtxtCopiaA_'+ contadorAsuntos +'" id="GtxtCopiaA_'+ contadorAsuntos +'" value=""  placeholder="<?php echo $repcopia_______;?>" >'+
                            '<span class="help-block"></span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $rephorasdeenvio; ?></label>'+
                            '<input type="text" class="form-control" name="GtxtHoraEnvio_'+ contadorAsuntos +'" id="GtxtHoraEnvio_'+ contadorAsuntos +'"  placeholder="<?php echo $rephorasdeenvio; ?>" value = "20:00">'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2 col-xs-10">'+
                        '<div class="form-group">'+
                            '<label><?php echo $campan_periodo_; ?></label>'+
                            '<select class="form-control" name="GcmbPeriodicidad_'+ contadorAsuntos +'">'+
                                '<option value="4">DIARIO ADHERENCIAS</option>'+
                            '</select>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-1 col-xs-1">'+
                        '<br>'+
                        '<button type="button" class="btn btn-sm btn-danger deleteCorreo" id= "'+ contadorAsuntos +'">'+
                        '    <i class="fa fa-trash"></i>'+
                        '</button>'+
                    '</div>'+
                '</div>';

       
        $("#horaEnvio").append(cuero);
        $("#GtxtHoraEnvio_"+ contadorAsuntos).timepicker({
            'timeFormat': 'H:i',
            'minTime': '00:00',
            'maxTime': '20:00',
            'setTime': '08:00',
            'step'  : '5',
            'showDuration': true
        });

        $(".deleteCorreo").click(function() {
            var id = $(this).attr('id');
            $("#id_"+id).remove();
        });

        //obtenemos la altura del documento
        var altura = $(document).height();
        $("html, body").animate({scrollTop:altura+"px"});
        contadorAsuntos++;

        validateNewEmailsChange();
    });

    $(".deleteCorreoF").click(function(){
        var id = $(this).attr('aborrar');
        $.ajax({
            url     : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?borrarReporte=true',
            type    : 'post',
            data    : { idReporte : id },
            success : function(data){
                if(data != 1){
                    alertify.error(data);
                }else{
                    $("#"+id).remove();
                }
            }
        });
    });
</script>


<script type="text/javascript">

    function HoraPre(val,tip,id){
        switch(val) {
          case '1':
            $("#"+tip+"txtHoraEnvio_"+id).val('20:00');
            break;
          case '2':
            $("#"+tip+"txtHoraEnvio_"+id).val('07:00');
            break;
          case '3':
            $("#"+tip+"txtHoraEnvio_"+id).val('07:00');
            break;
        }   
    }

    function agregarMetasEstrat(){
        var NumeroNuevos = Number($("#contadorMetasNuevas").val()) + 1;
        var campo = '<div class="row" id="Metas_'+ NumeroNuevos +'">'+
                '<div class="col-md-3 col-xs-3">'+
                    '<div class="form-group">'+
                        '<input type="text" name="Gen_txtNombreMeta_'+ NumeroNuevos +'" id="Gen_txtNombreMeta_'+ NumeroNuevos +'" class="form-control frmNuevasMetas"  placeholder="<?php echo $str_Meta_nombre ?>">'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 col-xs-2">'+
                    '<div class="form-group">'+
                        '<select class="form-control" id="Gen_cmbPasos_'+ NumeroNuevos +'" name="Gen_cmbPasos_'+ NumeroNuevos +'">'+
                            PasosArray +
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 col-xs-2">'+
                    '<div class="form-group">'+
                        '<select class="form-control" id="Gen_cmbNivel_'+ NumeroNuevos +'" name="Gen_cmbNivel_'+ NumeroNuevos +'">'+
                            '<option value="1"><?php echo $str_Meta_nivel2; ?></option>'+
                            '<option value="2"><?php echo $str_Meta_nivel3; ?></option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 col-xs-2">'+
                    '<div class="form-group">'+
                        '<select class="form-control" id="Gen_cmbTipo_'+ NumeroNuevos +'" name="Gen_cmbTipo_'+ NumeroNuevos +'">'+
                            '<option value="1"><?php echo $str_Meta_tipo1 ?></option>'+
                            '<option value="2"><?php echo $str_Meta_tipo2; ?></option>'+
                            '<option value="3"><?php echo $str_Meta_tipo3; ?></option>'+
                            '<option value="4"><?php echo $str_Meta_tipo4; ?></option>'+
                            '<option value="5"><?php echo $str_Meta_tipo5; ?></option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2 col-xs-2">'+
                    '<div class="form-group">'+
                        '<select class="form-control" id="Gen_cmbSubTipo_'+ NumeroNuevos +'" name="Gen_cmbSubTipo_'+ NumeroNuevos +'">'+
                            '<option value="1"><?php echo $str_Meta_subTipo1;?></option>'+
                            '<option value="2"><?php echo $str_Meta_subTipo2;?></option>'+
                            '<option value="3"><?php echo $str_Meta_subTipo3;?></option>'+
                        '</select>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-1 col-xs-1">'+
                    '<div class="form-group">'+
                        '<button type="button" class="btn btn-sm btn-danger borrarEsto" id="quitarEsto_'+ NumeroNuevos +'" valueI="'+ NumeroNuevos +'" title="Quitar Meta"><i class="fa fa-trash-o"></i></button>'+
                    '</div>'+
                '</div>'+
            '</div>';

        $("#EstrategiaMetas").append(campo);
        $("#contadorMetasNuevas").val(NumeroNuevos);
        $("#quitarEsto_"+NumeroNuevos).click(function(){
            var id = $(this).attr('valueI');
            $("#Metas_"+id).remove();
        });
    }

    function borrarMeta(id){

        var id_ = $(id).attr('valueI');
        var idMETDEF = $(id).attr('metaId');
        alertify.confirm("<?php echo $str_message_generico_D;?>", function (e) {
            //Si la persona acepta
            if (e) {
                $.ajax({
                    url   : '<?=$url_crud_extender?>',
                    type  : 'post',
                    data  : { deleteMetDef : idMETDEF },
                    success : function(data){
                        if(data == 'ok'){
                            alertify.success('<?php echo $str_Exito; ?>');
                            $("#EstaMetas_"+id_).remove();
                        }else{
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }
                    },
                    beforeSend:function(){
                        $.blockUI({ 
                            message : '<h3><?php echo $str_message_wait; ?></h3>',
                            css: { 
                                border: 'none', 
                                padding: '1px', 
                                backgroundColor: '#000', 
                                '-webkit-border-radius': '10px', 
                                '-moz-border-radius': '10px', 
                                opacity: .5, 
                                color: '#fff' 
                            } 
                        }); 
                    },
                    complete:function(){
                        $.unblockUI();
                    }
                });
            }
        });
        
    }
    
    // Validacion de los correos de la caja
    $("#cajaCorreos").change(() => {
        if($("#cajaCorreos").val().trim().match(/^([a-zA-Z0-9.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})(,[a-zA-Z0-9.%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})*$/)){
            $('#sendEmails').attr("disabled", false);
            $("#cajaCorreos").parent().removeClass("has-error");
            $("#cajaCorreos").parent().children("span").html("");
        }else{
            $('#sendEmails').attr("disabled", true);
            $("#cajaCorreos").parent().addClass("has-error");
            $("#cajaCorreos").parent().children("span").html("Debe de ingresar correos validos");
        }
    });

    $('#sendEmails').click(function(){

        //$('#loading').attr('hidden',false);
        $("#ModalLoading").modal();

        var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;

        var huespedId = '<?php echo $_SESSION['HUESPED'];?>'.trim();

        var estrategiaId = $("#hidId").val().trim(); 

        var correoLog = '<?php echo $_SESSION['CORREO']; ?>'.trim();

        var cajaCorreos = $("#cajaCorreos").val();

            if (cajaCorreos == null || cajaCorreos == '') {
                if ($('#siEnviarme').prop('checked')) {
                    correosFinal = correoLog;
                }else{
                    correosFinal = "sin_correo";
                }
            }else{
                cajaCorreos = cajaCorreos.replace(/ /g, "");
                cajaCorreos = cajaCorreos.replace(/,,,,,/g, ",");
                cajaCorreos = cajaCorreos.replace(/,,,,/g, ",");
                cajaCorreos = cajaCorreos.replace(/,,,/g, ",");
                cajaCorreos = cajaCorreos.replace(/,,/g, ",");

                if (cajaCorreos[0] == ',') {
                    cajaCorreos = cajaCorreos.substring(1);
                }

                if (cajaCorreos[cajaCorreos.length-1] == ',') {
                    cajaCorreos = cajaCorreos.substring(0,cajaCorreos.length-1);
                }

                var porciones = cajaCorreos.split(",");

                for (var i = 0; i < porciones.length; i++) {
                if (!emailRegex.test(porciones[i])) {
                        porciones.splice(i, 1);
                }
                }

                cajaCorreos = porciones.join(',');

                if ($('#siEnviarme').prop('checked')) {
                    correosFinal = cajaCorreos+','+correoLog;
                }else{
                    correosFinal = cajaCorreos;
                }
        
            }


            if (correosFinal == "sin_correo") {
                alertify.success("!NO INGRESO NINGUN CORREO");
                $('#loading').attr('hidden',true);
                $("#ModalLoading").modal('hide');
            }else{
                $.ajax({
                    url: '<?php echo $url_crud; ?>',
                    type : 'GET',
                    dataType: 'JSON',
                    data: {correosFinal: correosFinal, huespedId: huespedId, estrategiaId: estrategiaId},
                    success: function(respuesta) {
                        $('#loading').attr('hidden',true);
                        let obj = JSON.parse(respuesta);
                        if (obj['strMensaje_t'] == 'Reportes procesadoes: 0') {
                            alertify.confirm("NO HAY REPORTES PARA ENVIAR");
                        }else{
                            alertify.success("Envio Exitoso ".toUpperCase()+obj['strMensaje_t']+"<br>Por favor espere de 5 a 15 minutos");
                        }
                    },
                    complete: function(){
                        $.ajax({
                            url: '<?php echo $url_crud; ?>?limpiar_nombre=si',
                            type : 'GET',
                            data: {huespedId: huespedId, estrategiaId: estrategiaId},
                            success: function(respuesta) {
                                console.log("respuesta: ", respuesta);
                                $("#ModalLoading").modal('hide');
                                window.location.reload();
                            }
                        }); 
                    },
                    error: function(){
                        $('#loading').attr('hidden',true);
                    }
                });
            }
        });     

    function EnvioReporte(){

        $("#enviarReportes").modal('show');

    }
</script>
