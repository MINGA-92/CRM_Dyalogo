<?php
    $Lsql = "SELECT G2_C69 FROM ".$BaseDatos_systema.".G2 WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '".$_GET['estrategia']."'";
    $res  = $mysqli->query($Lsql);
    if($res && $res->num_rows==1){
        $Poblacion = $res->fetch_array();
    }else{ ?>
        <script>
            window.location.href="<?=base_url?>modulo/error";
        </script>
<?php } ?>
<?php include_once(__DIR__."/Controller/ListasDesplegables.php"); ?>


<style type="text/css">
    .clock      {
        position:relative;left:50%;top:50%;width:36px;height:36px;padding:20px;}
     .loader {
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        opacity: .9;
        background: 50% 50% no-repeat rgb(249,249,249);
     }
    /* .modal.in .modal-dialog {
        width: 80%;
    } */
    .lista table{
        margin: 0;
    }
    .titulo-dragdrop{
        background: #f1f1f1;
        color: #858585;
        border: 1px solid #eaeaea;
        font-weight: bold;
        padding: 6px;
        margin-bottom: 0;
    }

    .box.box-warning.yellow {
        border-top-color: #f3ec12;
    }
    .error-input {
        border: 1px solid #cc0033 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
        border-color: #367fa9;
    }

</style>
<script type="text/javascript">
    function autofitIframe(id){
        if (!window.opera && document.all && document.getElementById){
            id.style.height=id.contentWindow.document.body.scrollHeight;
            document.getElementById('imageCarge').style.display = "none"; 
        } else if(document.getElementById) {
            id.style.height=id.contentDocument.body.scrollHeight+"px";
            document.getElementById('imageCarge').style.display = "none"; 
        }
    }
</script>


<div class="modal fade-in" id="modalToken" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog" style="z-index: 10000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">CREDENCIALES GENERADAS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">USUARIO</label>
                            <input type="text" id="userToken" class="form-control input-sm" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">TOKEN</label>
                            <input type="text" id="tokenToken" class="form-control input-sm" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn-primary btn" type="button" data-dismiss="modal" >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-in" id="modalConfigWS" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog" style="z-index: 10000;">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">CONFIGURAR WEB SERVICE</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                        <iframe src="" id="iframePregunWS" style="width: 100%;margin-top: 30px;height: 500px;" marginwidth="0" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button class="btn-primary btn" type="button"  data-dismiss="modal" >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="mostrar_loading" class="">
    <div class="container-fluid" id="barra" style="margin-top: 31% ;display: none;"  >
        <div class="row" >
            <div class="col-md-12">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" id="barraProgreso" role="progressbar" style="width: 1%; max-width: 100%;" > 1% </div>
                </div>
            </div>       
        </div>
    </div>
</div>

<div class="modal fade-in" id="editarDatos" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
   <input type="hidden" name="fallasValidacion" id="fallasValidacion" value="0">
   <input type="hidden" name="strFechaInicial_t" id="strFechaInicial_t" value="">
    <input type="hidden" name="intIdCampana_t" id="intIdCampana_t" value="">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_cargue"><?php echo $str_strategia_edicion.' '; ?></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <!--<img src="<?=base_url?>assets/img/clock.gif" style="text-align : center;" id="imageCarge">-->
                <div id="divIframe">
                    <!--<iframe id="frameContenedor" style="width: 100%; height: 1000px;" src="" scrolling="si"  marginheight="0" marginwidth="0" noresize  frameborder="0" onload="autofitIframe(this);">

                    </iframe>-->
                </div>
            </div>
        </div>
        <input type="hidden" name="TablaTemporal" id="TablaTemporal" value="">
    </div>
</div>

<div class="modal fade-in" id="pasoscortos" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estratPasosC"><?php echo $str_strategia_edicion.' '; ?></h4>
            </div>
            <div class="modal-body">
                <!--<img src="<?=base_url?>assets/img/clock.gif" style="text-align : center;" id="imageCarge">-->
                <div id="divIframePasosCortos">
                    <!--<iframe id="frameContenedor" style="width: 100%; height: 1000px;" src="" scrolling="si"  marginheight="0" marginwidth="0" noresize  frameborder="0" onload="autofitIframe(this);">

                    </iframe>-->
                </div>
            </div>
        </div>
    </div>
</div>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php 
                $Lsql = "SELECT G2_C7, G2_C69 FROM ".$BaseDatos_systema.".G2 WHERE md5(concat('".clave_get."', G2_ConsInte__b)) = '".$_GET['estrategia']."'";
                //echo $Lsql;
                $res_Lsql = $mysqli->query($Lsql);
                $dato = $res_Lsql->fetch_array();
                $str_nombre_estrat = $dato['G2_C7'];
                $str_poblacion = $dato['G2_C69'];
            ?>
            <?php echo $str_strategias_flujograma.$str_nombre_estrat.$str_strategias_flujograma_2;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url?>index.php"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <?php if(!isset($_GET['ruta'])){ ?>
            <li><a href="<?=base_url?>modulo/estrategias"><i class="fa fa-adjust"></i> <?php echo $str_estrategia;?></a></li>
            <?php }else{ ?>
            <li><a href="index.php?page=dashEstrat&estrategia=<?php echo $_GET['estrategia'];?>&huesped=<?php echo $_SESSION['HUESPED'];?>"><i class="fa fa-adjust"></i> <?php echo $str_estrategia;?></a></li>
            <?php } ?>
            <li class="active"><?php echo $str_flujograma;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-default">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-10">
                        &nbsp;
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <button class="btn btn-default" id="Save_modal" >
                            <i class="fa fa-save"></i>
                        </button>
                        <button class="btn btn-default" id="cancel" >
                            <i class="fa fa-close"></i>
                        </button>
                    </div>
                </div>
                <div class="row" id="sample">
                    <div class="col-md-12">
                        <div class="row">
                            <!-- Menu de pasos -->
                            <div class="col-md-2">
                                <h4><?php echo $str_type_pass;?></h4>
                                <span style="display: inline-block; vertical-align: top; width:100%;">
                                    <div id="myPaletteDiv" style="height: 600px;"></div>
                                </span>
                            </div>
                            <!-- Flujograma -->
                            <div class="col-md-10">
                                <h4><?php echo $str_flujograma; ?></h4>
                                <span style="display: inline-block; vertical-align: top; width:100%">
                                    <div id="myDiagramDiv" style="border: solid 1px black; height: 600px;"></div>
                                </span>
                            </div>                                                                                                                       
                        </div>
                        <div class="row" style="display: none;">
                            <!-- FIN DEL CAMPO TIPO MEMO -->
                            <div class="form-group" style="display: none;">
                <textarea id="mySavedModel" class="form-control">
                {
                    "class": "go.GraphLinksModel",
                    "linkFromPortIdProperty": "fromPort",
                    "linkToPortIdProperty": "toPort",
                    "nodeDataArray": [
                        <?php
                            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$_GET['estrategia']."'";
                            $res_Pasos = $mysqli->query($Lsql);
                            $i = 0;
                            $separador = '';
                            while ($keu = $res_Pasos->fetch_object()) {
                                if($i != 0){
                                    $separador = ',';
                                }
                                echo $separador."
                                {\"category\":\"".$keu->ESTPAS_Nombre__b."\", \"nombrePaso\":\"".$keu->ESTPAS_Comentari_b."\", \"active\": ".$keu->ESTPAS_activo.", \"tipoPaso\": ".$keu->ESTPAS_Tipo______b.", \"figure\":\"Circle\", \"key\": ".$keu->ESTPAS_ConsInte__b.", \"loc\":\"".$keu->ESTPAS_Loc______b."\", \"generadoPorSistema\":".$keu->ESTPAS_Generado_Por_Sistema_b."}"."\n";
                                $i++;
                            }
                        ?>
                    ],
                    "linkDataArray": [
                        <?php
                            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTCON WHERE md5(concat('".clave_get."', ESTCON_ConsInte__ESTRAT_b)) = '".$_GET['estrategia']."'";
                            $res_Pasos = $mysqli->query($Lsql);
                            $i = 0;
                            $separador = '';
                            while ($keu = $res_Pasos->fetch_object()) {
                                if($i != 0){
                                    $separador = ',';
                                }
                                if(!is_null($keu->ESTCON_Coordenadas_b) && $keu->ESTCON_Coordenadas_b != ''){
                                    echo $separador."
                                    {\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"points\":".$keu->ESTCON_Coordenadas_b.", \"text\":\"".str_replace("\n", '', $keu->ESTCON_Comentari_b)."\", \"active\":".$keu->ESTCON_Activo_b.", \"disabled\":".$keu->ESTCON_Deshabilitado_b." }"."\n";
                                }else{
                                    echo $separador."
                                    {\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"active\":".$keu->ESTCON_Activo_b.", \"disabled\":".$keu->ESTCON_Deshabilitado_b."}"."\n";
                                }
                                
                                $i++;
                            }
                        ?>
                    ]
                }
                </textarea>
                            </div>
                        </div>
                    </div>
            
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade-in" id="crearCampanhasNueva" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estratCampan"><?php echo $campan_title___;?></h4>
            </div>
            <div class="modal-body">
                <form id="formuarioCargarEstoEstrart">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="G10_C71" id="LblG10_C71"><?php echo $campan_nombre__;?></label>
                                <input type="text" class="form-control input-sm" id="G10_C71" value=""  name="G10_C71"  placeholder="<?php echo $campan_nombre__;?>">
                            </div>
                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                        <input type="hidden" name="G2_C5" id="G2_C5" value="<?php echo $_SESSION['HUESPED'];?>">
                        <input type="hidden" class="form-control input-sm Numerico" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo 0; } ;?>"  name="id_paso" id="id_estpas_mio">
                        <input type="hidden" name="G10_C72" value="-1">
                        <input type="hidden" name="G10_C74" value="<?php echo $str_poblacion; ?>">
                    </div>
                    <div class="row" id="campanasNormales">
                        <div class="col-md-12 col-xs-12">
                            <?php 
                                $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 1 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
                            ?>
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="G10_C73" id="LblG10_C73"><?php echo $campan_form_age;?></label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C73" id="G10_C73">
                                    <option  value="0">NOMBRE</option>
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
                            <!-- FIN DEL CAMPO TIPO LISTA -->
                        </div>
                    </div>
                    <div class="row" id="marcadorRoboticoIVR" style="display: none;">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="G10_C90" id="LblG10_C90">IVR</label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C90" id="G10_C90">
                                    <option  value="0">Seleccione</option>
                                </select>
                            </div>
                            <!-- FIN DEL CAMPO TIPO LISTA -->
                        </div>
                    </div>
                    <div class="row" id="backoffice" style="display: none;">
                        <div class="col-md-12 col-xs-12">
                            <?php 
                                $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 4 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
                            ?>
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="G10_C73" id="LblG10_C73"><?php echo $campan_form_age;?></label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C73B" id="G10_C73B">
                                    <option  value="0">NOMBRE</option>
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
                            <!-- FIN DEL CAMPO TIPO LISTA -->
                        </div>
                    </div>
                    <div class="row" id="checkGenerarFromBd">
                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" class="fromBAse" name="generarFromDB" id="generarFromDB" value="1">&nbsp;<?php //echo $campan_from_db_;?>
                            </label>
                        </div>
                    </div> -->
                    </div>

                </form>
            </div>
            <div class="box-footer">
                <input type="hidden" id="AddTipoCampan">
                <button class="btn btn-default regresoCampains" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
                <button class="btn-primary btn pull-right" type="button" id="btnSaveCampan">
                    <?php echo $str_guardar;?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Esta modal se encargara de la asignacion de casos de backoffice -->
<div class="modal fade" id="casoBackofficeModal" tabindex="-1" aria-labelledby="Backoffice" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Casos de BackOffice</h4>
            </div>
            <div class="modal-body">
                <form action="" id="casoBackofficeForm">
                    <!--esta accion es si para crear o editar -->
                    <input type="hidden" name="tipoAccion" id="tipoAccion">
                    <input type="hidden" name="idEstpas" id="idEstpas">
                    <input type="hidden" name="idGuion" id="idGuion">
                    <input type="hidden" name="idTareaBack" id="idTareaBack" value="0">

                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_1">
                                    Tarea de backoffice
                                </a>
                            </h4>
                        </div>
                        <div id="sec_1" class="panel-collapse collapse in">
                            <div class="form-group row">
                                <div class="col-md-11">
                                    <label for="nombreCaso">Nombre</label>
                                    <input type="text" class="form-control" name="nombreCaso" id="nombreCaso">
                                </div>
                                <div class="col-md-1 col-xs-1">
                                    <div class="form-group">
                                        <label for="pasoActivo" id="LblpasoActivo">ACTIVO</label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself"> 
                                            </label>
                                        </div>
                                    </div>
                                </div>                             
                            </div>

                        <div class="form-group row camposDeEdicion">
                            <div class="col-md-12">
                                <label for="formulario">Tipo de distribución</label>
                                <select name="tipoDistribucionTrabajo" id="tipoDistribucionTrabajo" class="form-control">
                                    <option value="" disabled>Seleccionar</option>
                                    <option value="1">Todos ven todo</option>
                                    <option value="2">Asignar al que menos registros tenga en esta condición</option>
                                    <option value="3">Asignar al que menos registros tenga</option>
                                    <option value="4">Asignar manualmente</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row camposDeEdicion">
                            <div class="col-md-6">
                                <label for="pregun">Campo de la condición</label>
                                <select name="pregun" id="pregun" class="form-control">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="lisopc">Valor de la condición</label>
                                <select name="lisopc" id="lisopc" class="form-control">
                                    <option value="">Seleccionar</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary" id="btnAdministrarRegistrosBackoffice">Administracion de registros</button>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="panel box box-primary camposDeEdicion">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_2">
                                    Asignacion de usuarios
                                </a>
                            </h4>
                        </div>
                        <div id="sec_2" class="panel-collapse collapse in">
                            <!-- En esta seccion se encuentra el dragAndDrop -->
                            <div class="form-group row" id="dragAndDrop">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" name="buscadorDisponible" id="buscadorDisponible" class="form-control">
                                        <span class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                    <p class="text-center titulo-dragdrop">Disponibles</p>
                                    <ul id="disponible" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                margin-bottom: 10px;
                                                overflow: auto;   
                                                -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                        
                                    </ul>
                                </div>
                                <div class="col-md-2 text-center" style="padding-top:100px">
                                    <button type="button" id="derecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                    <button type="button" id="todoDerecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br>
                                    
                                    <button type="button" id="izquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button> <br>
                                    <button type="button" id="todoIzquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" name="buscadorSeleccionado" id="buscadorSeleccionado" class="form-control">
                                        <span class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                    <p class="text-center titulo-dragdrop">Seleccionados</p>
                                    <ul id="seleccionado" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                margin-bottom: 10px;
                                                overflow: auto;   
                                                -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                    
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    

                </form>

                
                <!-- Box para reportes -->
                <div class="panel box box-primary" id="report_back_box">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="" href="#report_back" aria-expanded="false" class="collapsed">
                                REPORTES
                            </a>
                        </h4>
                    </div>
                    <div id="report_back" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                        <div style="padding: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <iframe id="iframeReportes_back" src="" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarCasoBackofficeButton">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-in" id="modalAdminRegistrosBackoffice" tabindex="-1" aria-labelledby="adminBackoffice" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titleAdminRegistrosBackoffice">Administrar registros de backoffice</h4>
            </div>
            <div class="modal-body">

                <form id="formAdminRegistrosBackoffice">

                    <input type="hidden" name="filtrosAdminBackoffice" id="filtrosAdminBackoffice" value="0">

                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radioCodicionesBackoffice" id="radioCodicionesBackofficeTodos" value="1"> TODOS
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radioCodicionesBackoffice" id="radioCodicionesBackofficeFiltros" value="2"> CONDICIONES
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" style="display: none" id="divAgregarFiltro">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-primary pull-right" type="button" id="btnNuevoFiltroBackoffice" onclick="agregarNuevoFiltroBackoffice()">Agregar Filtros</button>    
                            </div>
                        </div>

                        <div class="col-md-12" id="listaFiltrosBackoffice">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">

                                <div class="box-header">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#accionesAdminBackoffice">Acciones</a>
                                    </h4>
                                </div>

                                <div id="accionesAdminBackoffice" class="panel-collapse collapse">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_usuarios_asignacion">Asignar a</label>
                                                <select id="sel_usuarios_asignacion" name="sel_usuarios_asignacion" class="form-control acciones">
                                                    <option value="0">Seleccione</option> 
                                                    <option value="-1">Dejar sin agente</option> 
                                                </select> 
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

            <div class="box-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                <button class="btn-primary btn pull-right" type="button" id="btnAplicarFiltrosBackoffice" onclick="aplicarFiltrosBackoffice()">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para el paso LEAD -->
<div class="modal fade" id="modalLead" tabindex="-1" aria-labelledby="LEAD" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Insertar registro desde correo</h4>
            </div>
            <div class="modal-body">
                <div class="box box-primary">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <form id="form-lead">
                                <input type="hidden" name="id_estpas" id="id_estpas">
                                <div class="col-md-12">
                                    <div class="form-group col-md-11">
                                        <label for="nombreLead">Nombre</label>
                                        <input type="text" name="nombreLead" id="nombreLead" class="form-control">
                                    </div>
                                    <div class="col-md-1 col-xs-1">
                                        <div class="form-group">
                                            <label for="leadActivo" id="LblleadActivo">ACTIVO</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="1" name="leadActivo" id="leadActivo" data-error="Before you wreck yourself"> 
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 table-responsive">
                                    <div class="col-md-11 ">
                                        <table class="table" id="LeadTable">
                                            <thead>
                                                <tr>
                                                    <th style="padding-left: 0;"><?php echo $title_mail_cuenta;?></th>
                                                    <th><?php echo $title_mail_tipo_condicion;?></th>
                                                    <th><?php echo $title_mail_condicion;?></th>
                                                </tr>    
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <select name="leadQuienRecibe" id="leadQuienRecibe" class="form-control">
                                                            <option value="0">Seleccione</option>
                                                            <?php
                                                                $str_Lsql = 'SELECT * FROM '.$dyalogo_canales_electronicos.'.dy_ce_configuracion WHERE id_huesped = '.$_SESSION['HUESPED'];
                                                                $combo = $mysqli->query($str_Lsql);
                                                                while($obj = $combo->fetch_object()){
                                                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->direccion_correo_electronico)."</option>";

                                                                }    
                                                                
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="leadTipoCondicion" id="leadTipoCondicion" class="form-control" onchange="cambioTipoCondicionLead()">
                                                            <option value="100">Sin Condición</option>
                                                            <option value="1">Proviene del correo</option>
                                                            <option value="2">Proviene del dominio</option>
                                                            <option value="3">El asunto contiene</option>
                                                            <option value="4">El cuerpo contiene</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="leadCondicion" name="leadCondicion" class="form-control" disabled>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12 table-responsive">
                                    <div class="col-md-11 ">
                                        <table style="width:34%; margin-bottom: 10px;" class="table" id="LeadTable">
                                            <thead>
                                                <tr>
                                                    <th style="padding-left: 0;">Campo llave</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <select name="LeadCampoLlave" id="LeadCampoLlave" class="form-control"></select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h4>Campos a emparejar</h4>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-bordered" id="LeadCampos">
                                        <thead>
                                            <tr>
                                                <th>Tag inicial</th>
                                                <th>Tag final</th>
                                                <th>Campo de la base de datos</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <label>
                                        <input type="checkbox" value="1" name="desactivarTagFinal" id="desactivarTagFinal">&nbsp;
                                        Para el último campo tomar hasta el final del correo en vez de definir un tag de cierre
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <button type="button" id="campoEmparejar" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agregar campo</button>
                                    </div>
                                </div>
                                    
                            </form>
                        </div>
                    </div>
                </div>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="" href="#sec_leadReport" aria-expanded="false" class="collapsed">
                                REPORTES
                            </a>
                        </h4>
                    </div>
                    <div id="sec_leadReport" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                        <div style="padding: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <iframe id="iframeReportes_leadReport" src="" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarPasoLead" onclick="guardarLead()">Guardar</button>
            </div>
        </div>    
    </div>
</div>

<div class="modal fade" id="webserviceModal" tabindex="-1" aria-labelledby="WebService" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Web Service</h4>
            </div>
            <div class="modal-body">
                <form action="post" id="webServiceForm">
                    <!--esta accion es si para crear o editar -->
                    <input type="hidden" name="pasowsId" id="pasowsId">
                    <input type="hidden" name="valoresws" id="valoresws">
                    <input type="hidden" name="campoOrigen" id="campoOrigen">

                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_ws1">
                                    Web Service
                                </a>
                            </h4>
                        </div>
                        <div id="sec_ws1" class="panel-collapse collapse in">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="wsNombre">Nombre</label>
                                    <input type="text" class="form-control" name="wsNombre" id="wsNombre">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Parametros</label>
                                    <table class="table table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Descripcion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>strUsuario_t</td>
                                                <td>String</td>
                                                <td>El usuario autorizado para el API</td>
                                            </tr>
                                            <tr>
                                                <td>strToken_t</td>
                                                <td>String</td>
                                                <td>Token de autorizacion para el consumo</td>
                                            </tr>
                                            <tr>
                                                <td>intCodigoAccion_t</td>
                                                <td>Integer</td>
                                                <td>La accion a ejecutar con el dato</td>
                                            </tr>
                                            <tr>
                                                <td>intIdEstpas_t</td>
                                                <td>Integer</td>
                                                <td>El paso con el cual se trabaja</td>
                                            </tr>
                                            <tr>
                                                <td>booValidaConRestriccion_t</td>
                                                <td>Boolean</td>
                                                <td>Si la validación arroja un error, el proceso sigue pero se inserta como un NO Contactable</td>
                                            </tr>
                                            <tr>
                                                <td>strCamposLlave_t</td>
                                                <td>String</td>
                                                <td>Los campos que no se pueden repetir en cuanto a su valor</td>
                                            </tr>
                                            <tr>
                                                <td>mapValoresCampos_t</td>
                                                <td>Mapa</td>
                                                <td>El mapa de los valores CAMPO, VALOR que se van a usar en el request</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <label>Codigos de accion (intCodigoAccion_t)</label>
                                    <table class="table table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Codigo</th>
                                                <th>Descripcion de la accion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Insertar en la bd y en la campaña</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Insertar solo en la base de datos</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Actualizar</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Insertar sino existe y si existe, actualizar</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Sacar de la campaña (Elimina de muestra y de bd)</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Actualiza la base de datos y desactiva el regisro (pone en estado 3)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Campo llave</label>
                                        <select name="cllave" id="cllave" class="form-control" onchange="generarEjemplo()">        
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="validaConRestriccion">
                                            Restringir registros con número no valido
                                        </label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="validaConRestriccion" id="validaConRestriccion" value="1" onchange="generarEjemplo()">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
                                        <label for="infows">Ejemplo de consumo</label>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <button type="button" id="btn-copy" class="btn btn-info pull-right" style="margin-right: 20px; margin-bottom: 5px;">
                                            Copiar
                                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <textarea class="form-control" name="infows" id="infows" cols="30" rows="15" style="width: 80%" readonly>
                                    </textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input type="button" class="btn btn-success input-sm" id="generateCredencials" value="Generar credenciales">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="campOpcion">Opciones de los campos tipo lista</label>
                                    <select name="campOpcion" id="campOpcion" class="form-control" onchange="opcionLista()" style="margin-bottom: 20px;">
                                    <textarea class="form-control" name="opcionesLista" id="opcionesLista" cols="30" rows="15" style="width: 80%" readonly>
                                    </textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_ws2">
                                    URL PARA INVOCAR POR MEDIO DE UN WEBHOOK
                                </a>
                            </h4>
                        </div>
                        <div id="#sec_ws2" class="panel-collapse collapse in">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">URL</label>
                                        <input type="text" id="urlWebHook" class="form-control input-sm" readonly value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="selectWS">Web Service</label>
                                        <select name="selectWS" id="selectWS" class="form-control input-sm" style="width: 100%;">
                                            <option value="0">Seleccione</option>
                                            <?php
                                                $sql=$mysqli->query("SELECT id,nombre FROM dyalogo_general.ws_general WHERE id_huesped={$_SESSION['HUESPED']}");
                                                if($sql && $sql->num_rows > 0){
                                                    while($option = $sql->fetch_object()){
                                                        echo "<option value='{$option->id}'>{$option->nombre}</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="">Configurar</label>
                                        <button class="form-control btn btn-warning btn-sm" type="button" id="configurarWS"><i class="fa fa-cog"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>        
                </form>

                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="" href="#sec_ws3" aria-expanded="false" class="collapsed">
                                REPORTES
                            </a>
                        </h4>
                    </div>
                    <div id="sec_ws3" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                        <div style="padding: 15px;">
                            <div class="row">
                                <div class="col-md-12">
                                    <iframe id="iframeReportes_ws3" src="" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarWS" onclick="saveWebservice()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para bot inicial -->
<div class="modal fade" id="configuracionInicialBot">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Configuración inicial del bot</h4>
            </div>

            <div class="modal-body">
                <form action="#" method="POST" id="formConfiguracionIncialBot">
                    <input type="hidden" name="configuracionInicialPaso" id="configuracionInicialPaso" value="0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombre</label>
                                <input type="text" name="configuracionInicialBotNombre" id="configuracionInicialBotNombre" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tipo se seccion de Bienvenida</label>
                                <select name="configuracionInicialBotTipoSeccion" id="configuracionInicialBotTipoSeccion" class="form-control input-sm" disabled>
                                    <option value="1">Conversacional</option>
                                    <option value="2">Transaccional</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>
                <button type="button" class="btn btn-primary pull-right" id="configuracionInicialBotGuardar" onclick="guardarConfiguracionInicialBot()">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<?php include_once(__DIR__.'/../G26/G26_Modals.php') ?>
<div class="modal fade-in" id="filtrosCampanha" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat_filtros"><?php echo $filtros_campanha;?></h4>
            </div>
            <div class="modal-body">
                <form id="consultaCamposWhere">
                    <input type="hidden" name="esSmsEntrante" id="esSmsEntrante" value="0">
                    <input type="hidden" name="valorPregunSms" id="valorPregunSms" value="0">

                    <div class="row" style="margin-bottom: 12px;">
                        <div class="col-md-11">
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="estconActivo">ACTIVO</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="-1" name="estconActivo" id="estconActivo"> 
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel box box-primary secTraspasoRegistros">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#s_2">
                                    <?php echo $str_acordeon_ti_1_;?>
                                </a>
                            </h4>
                        </div>
                        <div id="s_2" class="panel-collapse collapse in">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radiocondiciones" id="radiocondiciones1" class="Radiocondiciones" value="1">
                                                <?php echo $filtros_tipo_1_c; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 condicionesTop" hidden>
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radiocondiciones" id="radiocondiciones2" class="Radiocondiciones" value="2">
                                                <?php echo $filtros_tipo_2_c; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radiocondiciones" id="radiocondiciones3" class="Radiocondiciones" value="3">
                                                <?php echo $filtros_tipo_3_c; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 condicionesTop" hidden>
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radiocondiciones" id="radiocondiciones4" class="Radiocondiciones" value="4">
                                                <?php echo $filtros_tipo_4_c; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none;" id="divCantidadCampan">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" name="txtCantidadRegistrps" id="txtCantidadRegistrps" class="form-control" placeholder="<?php echo $filtros_tipo_5_c; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="filtros">
                                    
                                </div>
                            </div>

                            <div class="row" style="display: none;" id="divFiltrosCampan">
                                <div class="col-md-12">
                                    <div class="col-md-9">
                                        &nbsp;
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="button" id="newFiltro" style="float:right">
                                                <?php echo $filtros_name_cam;?>
                                            </button>    
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <h3>Previsualizacion</h3>
                                    <p id="textoPrevisualizacion"></p>
                                    <span style="color:orange" id="errorCondiciones"></span>
                                </div>
                            </div>

                            <div class="row">
                                <input type="hidden" name="tipoLlamado" id="tipoLlamado">
                                <input type="hidden" name="from" id="id_paso_from" >
                                <input type="hidden" name="to" id="id_paso_to" >
                                <input type="hidden" name="dataBase" id="dataBase" value="<?php echo $str_poblacion;?>">
                                <input type="hidden" name="id_campanaFiltros" id="id_campanaFiltros">
                            </div>
                        </div>
                    </div>

                    <div class="panel box box-primary secTraspasoRegistros">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#s_1">
                                    <?php echo $str_acordeon_ti_2_;?>
                                </a>
                            </h4>
                        </div>
                        <div id="s_1" class="panel-collapse collapse in">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $str_tipo_insert___; ?></label>
                                        <select class="form-control" name="cmbTipoInsercion" id="cmbTipoInsercion">
                                            <option value="0"><?php echo $str_tipo_insert_1_; ?></option>
                                            <option value="-1"><?php echo $str_tipo_insert_2_; ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row campos-programado-futuro">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $str_fecha_ocamp___; ?></label>
                                        <select class="form-control" id="cmbCampoFecha" name="cmbCampoFecha">
                                            <option value="-1"><?php echo $str_fecha_now_____; ?></option>
                                            <?php 
                                                $Lsql = "SELECT PREGUN_ConsInte__b , PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN  WHERE PREGUN_ConsInte__GUION__b = ".$str_poblacion." AND PREGUN_Tipo______b = 5";
                                                $res = $mysqli->query($Lsql);
                                                while ($key = $res->fetch_object()) {
                                                    echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $str_fecha_ocamp_1_; ?></label>
                                        <select class="form-control" id="masMenosFecha" name="masMenosFecha">
                                            <option value="1"><?php echo $str_fecha_ocamp_3_; ?></option>
                                            <option value="0"><?php echo $str_fecha_ocamp_4_; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $str_fecha_ocamp_2_;?></label>
                                        <input type="text" class="form-control" id="txtRestaSumaFecha" name="txtRestaSumaFecha" placeholder="<?php echo $str_fecha_ocamp_2_;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row campos-programado-futuro">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $str_hora_ocamp____;?></label>
                                        <select class="form-control" id="cmbCampoHora" name="cmbCampoHora">
                                            <option value="-1"><?php echo $str_hora_now______; ?></option>
                                            <?php 
                                                $Lsql = "SELECT PREGUN_ConsInte__b , PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$str_poblacion." AND PREGUN_Tipo______b = 10";
                                                $res = $mysqli->query($Lsql);
                                                while ($key = $res->fetch_object()) {
                                                    echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $str_fecha_ocamp_1_; ?></label>
                                        <select class="form-control" id="masMenosHora" name="masMenosHora">
                                            <option value="1"><?php echo $str_fecha_ocamp_3_; ?></option>
                                            <option value="0"><?php echo $str_fecha_ocamp_4_; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $str_hora_ocamp__1_;?></label>
                                        <input type="text" class="form-control" id="txtRestaSumaHora" name="txtRestaSumaHora" placeholder="<?php echo $str_hora_ocamp__1_;?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel box box-primary secTraspasoRegistros">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#s_3">
                                    AVANZADO
                                </a>
                            </h4>
                        </div>
                        <div id="s_3" class="panel-collapse collapse in">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label for="sacarPasoAnterior">
                                            <input type="checkbox" name="sacarPasoAnterior" id="sacarPasoAnterior" value="1"> Sacar del paso anterior
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label for="sacarOtrosPasos">
                                            <input type="checkbox" name="sacarOtrosPasos" id="sacarOtrosPasos" value="1"> Sacar registros de estos otros pasos
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="pasosSacar[]" id="pasosSacar" class="form-control select2" multiple="multiple" style="width:80%" disabled>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label for="resucitarRegistros">
                                            <input type="checkbox" name="resucitarRegistros" id="resucitarRegistros" value="1"> Volver a ejecutar la accion en registros que ya existían previamente
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label for="heredarAgendas">
                                            <input type="checkbox" name="heredarAgendas" id="heredarAgendas" value="1"> Copiar comentarios y programación de reintento a la campaña destino
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="checkInactivarRegistros" style="display: none;">
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label for="inactivarRegistros">
                                            <input type="checkbox" name="inactivarRegistros" id="inactivarRegistros" value="1"> Inactivar registros que no vengan en el archivo
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="asignacionDeCampana" style="display: none;">
                                <div class="col-md-12">
                                    <h3>Asignacion de registros en la campaña</h3>
                                    <p><strong>Esta campaña donde esta realizando el cargue, tiene como configuración el tipo de asignación pre-definida.Esto quiere decir que cada registro ingresará a la base de Dyalogo asociado a un agente en específico.</strong></p>
                                    <p><strong>Como quiere asignar los registros?</strong></p>
                                    
                                    <div class="form-group">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipoAsignacion" id="tipoAsignacionAutomatica" onclick="cambioTipoAsignacionCampana()" value="1" checked>
                                                Asignación automática: Reparte equitativamente los registros cargados por la cantidad de agentes que pertenecen a la campaña.
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipoAsignacion" id="tipoAsignacionPredefinida" onclick="cambioTipoAsignacionCampana()" value="2">
                                                Asignación pre-definida: Asigna los registros según el campo en el Excell donde este el correo electrónico del agente al que le pertenece.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-gorup">
                                        <label><?php echo $str_title_campo_agente;?></label>
                                        <select class="form-control" name="campoAgente" id="campoAgente" disabled>
                                            <option value="0">Seleccione</option>
                                            <?php 
                                                $Lsql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$str_poblacion} AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 14)";
                                                $res = $mysqli->query($Lsql);
                                                while ($key = $res->fetch_object()) {
                                                    echo "<option value='".$key->id."'>".$key->nombre."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary" style="margin-top: 20px;" onclick="abrirModalInsertarRegistros()">Ejecutar para registros que puedan existir previamente</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="panel box box-primary secHorarios" style="display: none;">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_horario">
                                    Horarios
                                </a>
                            </h4>
                        </div>
                        <div id="sec_horario" class="panel-collapse collapse in">
                            <!-- Horarios -->
                            <div class="row">
                                <div class="col-md-12">
                                <div class="col-md-12">
                                    <h4 id="tituloHorario">Horario comprendido entre</h4>
                                    <h5 id="subHorario"></h5>
                                </div>
                                    <!-- Lunes -->
                                    <div class="col-md-12">
                                    
                                        <div class="col-md-4 col-xs-4">
                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                            <div class="form-group">
                                                <label for="G10_C108" id="LblG10_C108">Lunes</label>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" readonly="readonly" value="-1" name="G10_C108" id="G10_C108" data-error="Before you wreck yourself" checked> 
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- FIN DEL CAMPO SI/NO -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                            <!-- CAMPO TIMEPICKER -->
                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                            <div class="form-group">
                                                <label for="G10_C109" id="LblG10_C109">Hora Inicial Lunes</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm Hora"  name="G10_C109" id="G10_C109" placeholder="HH:MM:SS" >
                                                    <div class="input-group-addon" id="TMP_G10_C109">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                            <!-- CAMPO TIMEPICKER -->
                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                            <div class="form-group">
                                                <label for="G10_C110" id="LblG10_C110">Hora Final Lunes</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm Hora"  name="G10_C110" id="G10_C110" placeholder="HH:MM:SS" >
                                                    <div class="input-group-addon" id="TMP_G10_C110">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                    </div>

                                    <!-- Martes -->
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-xs-4">
                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                            <div class="form-group">
                                                <label for="G10_C111" id="LblG10_C111">Martes</label>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" readonly="readonly" value="-1" name="G10_C111" id="G10_C111" data-error="Before you wreck yourself" checked> 
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- FIN DEL CAMPO SI/NO -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                            <!-- CAMPO TIMEPICKER -->
                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                            <div class="form-group">
                                                <label for="G10_C112" id="LblG10_C112">Hora Inicial Martes</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm Hora"  name="G10_C112" id="G10_C112" placeholder="HH:MM:SS" >
                                                    <div class="input-group-addon" id="TMP_G10_C112">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                            <!-- CAMPO TIMEPICKER -->
                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                            <div class="form-group">
                                                <label for="G10_C113" id="LblG10_C113">Hora Final Martes</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control input-sm Hora"  name="G10_C113" id="G10_C113" placeholder="HH:MM:SS" >
                                                    <div class="input-group-addon" id="TMP_G10_C113">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>
                                    </div>

                                    <!-- Miercoles -->
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                <div class="form-group">
                                                    <label for="G10_C114" id="LblG10_C114">Miercoles</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" readonly="readonly" value="-1" name="G10_C114" id="G10_C114" data-error="Before you wreck yourself" checked> 
                                                        </label>
                                                    </div>
                                                </div>
                                                <!-- FIN DEL CAMPO SI/NO -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C115" id="LblG10_C115">Hora Inicial Miercoles</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C115" id="G10_C115" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C115">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C116" id="LblG10_C116">Hora Final Miercoles</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C116" id="G10_C116" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C116">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>

                                    </div>

                                    <!-- Jueves -->
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                <div class="form-group">
                                                    <label for="G10_C117" id="LblG10_C117">Jueves</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" readonly="readonly" value="-1" name="G10_C117" id="G10_C117" data-error="Before you wreck yourself" checked> 
                                                        </label>
                                                    </div>
                                                </div>
                                                <!-- FIN DEL CAMPO SI/NO -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C118" id="LblG10_C118">Hora Inicial Jueves</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C118" id="G10_C118" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C118">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>

                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIPO TEXTO -->
                                                <div class="form-group">
                                                    <label for="G10_C119" id="LblG10_C119">Hora Final Jueves</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C119" id="G10_C119" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C118">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- FIN DEL CAMPO TIPO TEXTO -->
                                        </div>
                                    </div>

                                    <!-- Viernes -->
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                <div class="form-group">
                                                    <label for="G10_C120" id="LblG10_C120">Viernes</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" readonly="readonly" value="-1" name="G10_C120" id="G10_C120" data-error="Before you wreck yourself" checked> 
                                                        </label>
                                                    </div>
                                                </div>
                                                <!-- FIN DEL CAMPO SI/NO -->
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C121" id="LblG10_C121">Hora Inicial Viernes</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C121" id="G10_C121" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C121">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C122" id="LblG10_C122">Hora Final Viernes</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C122" id="G10_C122" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C122">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                    </div>

                                    <!-- Sabado -->
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                <div class="form-group">
                                                    <label for="G10_C123" id="LblG10_C123">Sabado</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" readonly="readonly" value="-1" name="G10_C123" id="G10_C123" data-error="Before you wreck yourself"  > 
                                                        </label>
                                                    </div>
                                                </div>
                                                <!-- FIN DEL CAMPO SI/NO -->
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C124" id="LblG10_C124">Hora Inicial Sabado</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C124" id="G10_C124" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C124">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C125" id="LblG10_C125">Hora Final Sabado</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C125" id="G10_C125" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C125">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                    </div>

                                    <!-- Domingo -->
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                <div class="form-group">
                                                    <label for="G10_C126" id="LblG10_C126">Domingo</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" readonly="readonly" value="-1" name="G10_C126" id="G10_C126" data-error="Before you wreck yourself"  > 
                                                        </label>
                                                    </div>
                                                </div>
                                                <!-- FIN DEL CAMPO SI/NO -->
                                        </div>


                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C127" id="LblG10_C127">Hora Inicial Domingo</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C127" id="G10_C127" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C127">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C128" id="LblG10_C128">Hora Final Domingo</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C128" id="G10_C128" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C128">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                    </div>

                                    <!-- Festivos -->
                                    <div class="col-md-12">
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                <div class="form-group">
                                                    <label for="G10_C129" id="LblG10_C129">Festivos</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" readonly="readonly" value="-1" name="G10_C129" id="G10_C129" data-error="Before you wreck yourself"  > 
                                                        </label>
                                                    </div>
                                                </div>
                                                <!-- FIN DEL CAMPO SI/NO -->
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C130" id="LblG10_C130">Hora Inicial festivos</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C130" id="G10_C130" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C130">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                                <!-- CAMPO TIMEPICKER -->
                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                <div class="form-group">
                                                    <label for="G10_C131" id="LblG10_C131">Hora Final Festivos</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm Hora"  name="G10_C131" id="G10_C131" placeholder="HH:MM:SS" >
                                                        <div class="input-group-addon" id="TMP_G10_C131">
                                                            <i class="fa fa-clock-o"></i>
                                                        </div>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                                <!-- /.form group -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel box box-primary secEmailFiltros" style="display: none;">

                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_emailFiltros">
                                    Filtros cuenta de correo entrante
                                </a>
                            </h4>
                        </div>
                        <div id="sec_emailFiltros" class="panel-collapse collapse in">
                            <!-- Horarios -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <h4 id="tituloHorarioFiltros">Filtros del paso INGRESE_PASO_AQUI configurado con el canal CANAL HERE</h4>
                                        <h5 id="subHorario"></h5>
                                    </div>
                                    
                                    <div class="col-md-12 col-xs-12 table-responsive">
                                        <table class="table" id="correoCondiciones">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $title_mail_tipo_condicion;?></th>
                                                    <th><?php echo $title_mail_condicion;?></th>
                                                    <th></th>
                                                </tr>    
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                        <div class="pull-right" style="margin-top:5px; margin-bottom:10px">
                                            <button type="button" id="nuevaCondicionCorreo" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agregar condición</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel box box-primary secConexionPasosSms" style="display: none;">

                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_conexionesSms">
                                    Configuración
                                </a>
                            </h4>
                        </div>
                        <div id="sec_conexionesSms" class="panel-collapse collapse in">
                            <!-- Horarios -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <h4 id="tituloConexionPasosSms">
                                            En esta flecha no es necesario realizar una configuración, si deseas cambiar algún valor debes hacerlo en los pasos que están conectado a esta flecha.
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- secConexionBot -->
                    <div class="panel box box-primary secConexionBot" style="display: none;">

                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#sec_conexionesBot">
                                    Configuración
                                </a>
                            </h4>
                        </div>
                        <div id="sec_conexionesBot" class="panel-collapse collapse in">
                            <!-- Horarios -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <h4 id="tituloConexionBot">
                                            Interacciones que generan la comunicación entre bots, si deseas modificarlos debes hacerlo desde el bot de donde sale esta flecha
                                        </h4>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table" id="accionesDisparanBot">
                                            <thead>
                                                <tr>
                                                    <th>Acción que dispara el evento de pasar a otro bot</th>
                                                    <th>Respuesta generada después de la acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#s_3">
                                    <?php echo $str_acordeon_ti_3_;?>
                                </a>
                            </h4>
                        </div>
                        <div id="s_3" class="panel-collapse collapse in">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $str_acordeon_ti_4_; ?></label>
                                        <select class="form-control" name="cmbCambioEstado" id="cmbCambioEstado">
                                            <option value="0"><?php echo $str_seleccione; ?></option>
                                            <?php 
                                                if($Poblacion['G2_C69'] != null){
                                                    $LsqlEstados = "SELECT PREGUN_ConsInte__OPCION_B as OPCION_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$Poblacion['G2_C69']." AND PREGUN_Texto_____b = 'ESTADO_DY';";
                                                    $resEstados = $mysqli->query($LsqlEstados);
                                                    $datosListas = $resEstados->fetch_array();

                                                    if($datosListas['OPCION_ConsInte__b'] != null){
                                                        $Lsql = "SELECT LISOPC_ConsInte__b ,LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$datosListas['OPCION_ConsInte__b'];
                                                        $resXultado = $mysqli->query($Lsql);
                                                        while($key = $resXultado->fetch_object()){
                                                            echo "<option value='".$key->LISOPC_ConsInte__b."'>".$key->LISOPC_Nombre____b."</option>";
                                                        }    
                                                    }   
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  -->
                    
                </form> 
            </div>
            <div class="box-footer">
                <button class="btn btn-default" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
                <button class="btn-primary btn pull-right" type="button" id="btnSaveFiltros">
                    <?php echo $str_guardar;?>
                </button>

                <!-- Este boton guarda los filtros de email Entrante -->
                <button class="btn-primary btn pull-right" style="display:none" type="button" id="btnSaveFiltrosEmailEntrante">
                    <?php echo $str_guardar;?>
                </button>
            </div>
        </div>
    </div>
</div>
<!--  <div class="modal fade-in" id="configAutomatica" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 50%;position: absolute;top: 20%;left: 25%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="close_configAutomatica">&times;</button>
                <h4 class="modal-title" id="title_estrat_filtros">Configurar automáticamente con un correo de ejemplo</h4>
            </div>
            <div class="modal-body" style="padding:50px;">
                <form action="" id="dataConfigAutomatica">
                    <label>
                       <span>
                            Para detectar automáticamente la configuración, se requiere que por favor ejecute los siguientes pasos: <br>
                            1. A continuación escriba para cada campo los datos que va a usar en el ejemplo (se recomienda usar datos particulares para identificarlos con claridad por ejemplo no utilice el nombre Luis, sino LuisXIV. También se recomienda no usar espacios, ni otros caracteres sino únicamente letras y números) <br>

                            2. En el formulario o aplicación que envía los correos, haga un ejemplo usando exactamente los mismos datos que configuró acá. <br>

                            3. Presione el botón de Buscar correo de ejemplo. Por favor hacerlo dentro de los siguientes 15 minutos luego de enviar los datos de ejemplo
                        </span>    
                    </label>
                    <div class="row" id="camposConfiguracionAutomatica" style="margin-top:30px;">
                    
                    </div>
                    <div class="row" style="margin-top:30px;">
                        <input type="hidden" name="totalCampos" id="totalCampos">   
                        <button type="button" id="buscarCorreo" class="btn btn-primary pull-right">Buscar correo de ejemplo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>  -->

<div class="modal fade" id="modalEjecucionProcesoFlecha" tabindex="-1" aria-labelledby="ejecutarproceso" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ejecutar proceso</h4>
            </div>

            <div class="modal-body">
                <form action="#" method="POST" id="ejecucionProcesoFlecha">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label id="labeFechaRegistro">Fecha desde la que se quieren tomar registros</label>
                                <input type="text" name="fechaRegistrosATomar" id="fechaRegistrosATomar" class="form-control" placeholder="Fecha desde la que se quieren tomar registros">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="textoCondicionesProcesoFlechas">
                            </div>
                        </div>
                    </div>
                </form>
                <span>Este proceso no tiene en cuenta la configuración de las opciones de <strong>VOLVER A EJECUTAR LA ACCION EN REGISTROS QUE YA EXISTÍAN PREVIAMENTE</strong> y <strong>REGISTROS QUE NO VENGAN EN EL ARCHIVO</strong> al momento de ejecutarse </span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>
                <button type="button" class="btn btn-primary pull-right" id="btnIniciarProcesoEjecucionFlecha" onclick="iniciarProcesoFlecha()">
                    Iniciar proceso
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmarEjecucion" tabindex="-1" aria-labelledby="modalConfirmarEjecucion" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ejecutar proceso</h4>
            </div>

            <div class="modal-body">
                <form action="#" method="POST" id="ejecucionProcesoFlecha">
                    <input type="hidden" name="confirmBase" id="confirmBase">
                    <input type="hidden" name="confirmEstcon" id="confirmEstcon">
                    <input type="hidden" name="confirmFiltro" id="confirmFiltro">
                </form>
                <h3 id="textoCantRegistrosInsertar"></h3>
                <div id="confirmCondiciones"></div>
                <div class="row" style="margin-top: 80px;">
                    <div class="col-md-12">
                        <p style="font-size: 1.1em" id="textoConfirmacion"><i class="fa fa-warning"></i> Está seguro de hacer esta inserción ? ... tenga en cuenta que eso disparará masivamente.</p>
                        <div class="checkbox">
                            <label for="leiEntendi">
                                <input type="checkbox" name="leiEntendi" id="leiEntendi" onclick="activarBtnIniciarEjecucionFlecha()"> leí y entendí
                            </label>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>
                <button type="button" class="btn btn-primary pull-right" id="btnIniciarEjecucionFlecha" disabled onclick="iniciarEjecucionFlecha()"> Confirmar </button>
            </div>
        </div>
    </div>
</div>


<!-- MINGA -->
<!-- Modal General Llamada Entrante // G39 -->
<link rel="stylesheet" href="<?=base_url?>assets/css/StyleMinga.css"/>
<div class="modal fade bd-example-modal-lg" id="ModalLlamada" tabindex="-1" role="dialog" aria-labelledby="ModalLlamadaLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-md modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">×</button>
                <h4 class="modal-title" id="title_cargue"><b>Comunicación Llamada Entrante: </b></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div id="divIframe">
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
                    .lista table{
                        margin: 0;
                    }
                    .titulo-dragdrop{
                        background: #f1f1f1;
                        color: #858585;
                        border: 1px solid #eaeaea;
                        font-weight: bold;
                        padding: 6px;
                        margin-bottom: 0;
                    }
                    hr{
                        width: 90%
                    }
                </style>

            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-tools"></div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12" id="div_formularios">
                            <div id="divBtnGuardar">
                                <button class="btn btn-primary" id="btnGuardar" onclick="GuardarRutaEntrante('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
                            </div>
                            <div id="divBtnActualizar" hidden>
                                <button class="btn btn-primary" id="divBtnActualizar" onclick="ActualizarRutaEntrante('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
                            </div><br>
                            <div>
                                <form action="#" id="FormularioDatos" enctype="multipart/form-data" method="post">
                                    <input type="hidden" name="id_paso" id="id_paso">
                                    <input type="hidden" name="oper" id="oper" value="add">
                                    <input type="hidden" name="configuracionId" id="configuracionId" value="0">
                                    <input type="hidden" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'];?>">
                                    <input type="hidden" name="IdFlujograma" id="IdFlujograma">
                                    <input type="hidden" name="IdRutaEntrante" id="IdRutaEntrante">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel box box-primary">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#RutaEntranteConfiguracion">Configuración:</a>
                                                    </h4>
                                                </div>
                                                <!-- loading -->
                                                <div id="Loading" class="container-loader" style="margin-top: -5%; margin-left: 42%;" hidden>
                                                    <div class="loader"></div>
                                                    <p class="form-label text-black" style="margin-top: 2%; margin-left: 5%; color: #0028D2;"> GUARDANDO... </p>
                                                    <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -28%; margin-left: -6%;">
                                                </div>
                                                <div id="RutaEntranteConfiguracion" class="panel-collapse collapse in">
                                                    <div class="box-body">
                                                        <div class="modal-content">
                                                            <!-- Form -->
                                                            <div class="modal-body" id="BodyModal">
                                                                <div class="card-body bg-light">
                                                                    <div class="card-header bg-light text-center text-uppercase"><b>Ruta Entrante</b></div>
                                                                    <div class="panel box box-primary"></div>
                                                                    <form class="col-md-12 mb-12" style="margin-top: 5%;">
                                                                        <div class="col-md-2" hidden>
                                                                            <label class="form-label" for="InputEstpas">Estpas: </label>
                                                                            <input type="text" class="form-control" id="InputEstpas" value=""/>
                                                                        </div>
                                                                        <div class="col-md-2" hidden>
                                                                            <label class="form-label" for="InputHuesped">Huesped: </label>
                                                                            <input type="text" class="form-control" id="InputHuesped" value="<?php echo $_SESSION['HUESPED'];?>"/>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-check col-md-6">
                                                                                <label class="form-label" for="InputNombre">Nombre: </label>
                                                                                <input type="text" class="form-control" id="InputNombre" placeholder="Introduzca Nombre"/>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label" for="InputNumeroEntrada">Número De Entrada: </label>
                                                                                <input type="number" class="form-control" id="InputNumeroEntrada" placeholder="Número De Entrada"/>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row" style="margin-top: 2%;">
                                                                            <div class="form-check col-md-5" id="divcheck">
                                                                                <input class="form-check-input" type="checkbox" id="CheckLimitarLlamadas">
                                                                                <label class="form-check-label" for="CheckLimitarLlamadas">Limitar Llamadas Simultaneas</label>
                                                                                <label class="form-label" for="InputNumeroLimite">Número Limite: </label>
                                                                                <input type="text" class="form-control" id="InputNumeroLimite" placeholder="-1" value="-1" disabled/>
                                                                            </div>
                                                                            <div class="form-check col-md-4" id="divCheckListaNegra" style="margin-left: 9%;">
                                                                                <input class="form-check-input" type="checkbox" id="CheckListaNegra">
                                                                                <label class="form-check-label" for="CheckListaNegra">Validar Lista Negra</label>
                                                                            </div>
                                                                            <div class="col-md-3" style="margin-left: 9%;" id="divBtnListaNegra" hidden>
                                                                                <button type="button" class="btn bg-black float-left text-white" id="BtnListaNegra" data-toggle="modal" data-target=".bd-example-modal-xl">Configurar Lista Negra</button>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="panel box box-primary" style="margin-top: 2%;"><label class="card-header text-blue">Avanzado: </label>
                                                                            <div class="row" style="margin-top: 0%;" id="divAvanzado">
                                                                                <div class="col-md-6">
                                                                                    <input class="form-check-input" type="checkbox" id="CheckGenerarPausa">
                                                                                    <label class="form-check-label" for="CheckGenerarPausa">Generar Pausa Para Contestar</label>
                                                                                    <label class="form-label" for="InputPausa">Segundos De Pausa: </label>
                                                                                    <input type="number" class="form-control" id="InputPausa" placeholder="0" value="0" disabled/>
                                                                                </div>
                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label class="form-label">Lista De Festivos: </label>
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-control" id="SelectListaFestivos" name="SelectListaFestivos">
                                                                                            <option disabled selected>Elige Una Opción</option>
                                                                                            <?php echo $ListaFestivosHuesped; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <input class="form-check-input" type="checkbox" id="CheckGenerarTimbre">
                                                                                    <label class="form-check-label" for="CheckGenerarTimbre">Generar Timbre Antes De Contestar</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Seccion de horarios y acciones -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel box box-primary">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#HorariosAccionesLlamadas" aria-expanded="false" class="collapsed">
                                                            Horarios y Acciones: 
                                                        </a>
                                                    </h4>
                                                </div>

                                                <div id="HorariosAccionesLlamadas" class="panel-collapse collapse" aria-expanded="false">
                                                    <div class="box-body" id="ModalHorario">
                                                    <!-- Horarios -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3> Horario: </h3>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <!-- Lunes -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckLunes" id="LblCheckLunes">Lunes: </label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" name="CheckLunes" id="CheckLunes" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraILunes" id="LblHoraILunes">Hora Inicial Lunes</label>
                                                                            <input type="time" class="form-control" name="HoraILunes" id="HoraILunes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFLunes" id="LblHoraFLunes">Hora Final Lunes</label>
                                                                            <input type="time" class="form-control" name="HoraFLunes" id="HoraFLunes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Martes -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckMartes" id="LblCheckMartes">Martes</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckMartes" id="CheckMartes" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIMartes" id="LblHoraIMartes">Hora Inicial Martes</label>
                                                                            <input type="time" class="form-control" name="HoraIMartes" id="HoraIMartes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFMartes" id="LblHoraFMartes">Hora Final Martes</label>
                                                                            <input type="time" class="form-control" name="HoraFMartes" id="HoraFMartes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Miercoles -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckMiercoles" id="LblCheckMiercoles">Miercoles</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckMiercoles" id="CheckMiercoles" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIMiercoles" id="LblHoraIMiercoles">Hora Inicial Miercoles</label>
                                                                            <input type="time" class="form-control" name="HoraIMiercoles" id="HoraIMiercoles" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFMiercoles" id="LblHoraFMiercoles">Hora Final Miercoles</label>
                                                                            <input type="time" class="form-control" name="HoraFMiercoles" id="HoraFMiercoles" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <!-- Jueves -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckJueves" id="LblCheckJueves">Jueves</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckJueves" id="CheckJueves" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIJueves" id="LblHoraIJueves">Hora Inicial Jueves</label>
                                                                            <input type="time" class="form-control" name="HoraIJueves" id="HoraIJueves" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFJueves" id="LblHoraFJueves">Hora Final Jueves</label>
                                                                            <input type="time" class="form-control" name="HoraFJueves" id="HoraFJueves" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Viernes -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckViernes" id="LblCheckViernes">Viernes</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckViernes" id="CheckViernes" checked> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIViernes" id="LblHoraIViernes">Hora Inicial Viernes</label>
                                                                            <input type="time" class="form-control" name="HoraIViernes" id="HoraIViernes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFViernes" id="LblHoraFViernes">Hora Final Viernes</label>
                                                                            <input type="time" class="form-control" name="HoraFViernes" id="HoraFViernes" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Sabado -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckSabado" id="LblCheckSabado">Sabado</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckSabado" id="CheckSabado"> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraISabado" id="LblHoraISabado">Hora Inicial Sabado</label>
                                                                            <input type="time" class="form-control" name="HoraISabado" id="HoraISabado" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFSabado" id="LblHoraFSabado">Hora Final Sabado</label>
                                                                            <input type="time" class="form-control" name="HoraFSabado" id="HoraFSabado" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Domingo -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckDomingo" id="LblCheckDomingo">Domingo</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckDomingo" id="CheckDomingo"> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIDomingo" id="LblHoraIDomingo">Hora Inicial Domingo</label>
                                                                            <input type="time" class="form-control" name="HoraIDomingo" id="HoraIDomingo" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFDomingo" id="LblHoraFDomingo">Hora Final Domingo</label>
                                                                            <input type="time" class="form-control" name="HoraFDomingo" id="HoraFDomingo" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Festivos -->
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-2">
                                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                        <div class="form-group">
                                                                            <label for="CheckFestivos" id="LblCheckFestivos">Festivos</label>
                                                                            <div class="checkbox">
                                                                                <label><input type="checkbox" class="checkbox-day" value="-1" name="CheckFestivos" id="CheckFestivos"> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraIFestivos" id="LblHoraIFestivos">Hora Inicial Festivos</label>
                                                                            <input type="time" class="form-control" name="HoraIFestivos" id="HoraIFestivos" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-5">
                                                                        <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                        <div class="form-group">
                                                                            <label for="HoraFFestivos" id="LblHoraFFestivos">Hora Final Festivos</label>
                                                                            <input type="time" class="form-control" name="HoraFFestivos" id="HoraFFestivos" placeholder="HH:MM:SS">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Acciones -->
                                                        <div class="row">
                                                            <div class="panel box box-primary" style="margin-top: 2%;">
                                                                <div class="col-md-12">
                                                                    <h3 style='color: #3C8DBC;'>Acciones: </h3>
                                                                </div>
                                                            </div>    
                                                            <div class="col-md-6">
                                                                <div class="col-md-12" style='color: #3C8DBC;'>
                                                                    <h4> Dentro Del Horario </h4>
                                                                </div>
                                                                <div class="col-md-12" id="divTareaActual" hidden>
                                                                    <div class="col-md-6"><b style="margin-left: 62%;">Tarea Actual: </b></div>
                                                                    <div class="col-md-6"><p class="float-left" id="LblTareaActual">MINGA</p></div>
                                                                    <input type="hidden" id="IdTareaActual" value="0">
                                                                    <input type="hidden" id="IptTareaActual" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="">Posibles Tareas: </label>
                                                                            <select name="SelectPosiblesTareas" id="SelectPosiblesTareas" class="form-control input-sm" onchange="PosiblesTareas_DentroH();">
                                                                                <option disabled selected>Elige Una Opción</option>
                                                                                <option value="Pasar a Una Campaña">Pasar a Agentes</option>
                                                                                <option value="Pasar a IVR">Pasar a IVR</option>
                                                                                <option value="Número Externo">Número Externo</option>
                                                                                <option value="Reproducir Grabación">Reproducir Grabación</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divInputNumeroExterno" hidden>
                                                                            <label for="InputNumeroExterno">Número Externo: </label>
                                                                            <input type="text" id="InputNumeroExterno" name="InputNumeroExterno" class="form-control input-sm" value="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectTroncales" hidden>
                                                                        <label for="">Troncal De Dónde Sale La Llamada: </label>
                                                                            <select name="SelectTroncales" id="SelectTroncales" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">Troncales Del Huesped</option>
                                                                                <?php echo $ListaTroncalesHuesped; ?>
                                                                                <!-- <option disabled class="bg-info text-center">Todas Las Troncales</option> -->
                                                                                <?php //echo $ListaTroncales; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectCampanas" hidden>
                                                                        <label for="">Lista De Campañas: </label>
                                                                            <select name="SelectCampanas" id="SelectCampanas" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">Campañas De La Estrategia</option>
                                                                                <?php echo $ListaCampanas; ?>
                                                                                <!-- <option disabled class="bg-info text-center">Campañas Del Huesped</option> -->
                                                                                <?php //echo $ListaCampanasHuesped; ?> 
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectIVR" hidden>
                                                                        <label for="">Lista De IVR's: </label>
                                                                            <select name="SelectIVR" id="SelectIVR" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">IVR's De La Estrategia</option>
                                                                                <?php echo $ListaIVRs; ?>
                                                                                <!-- <option disabled class="bg-info text-center">IVR's Del Huesped</option> -->
                                                                                <?php //echo $ListaIVRsHuesped; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group" id="divSelectAudios" hidden>
                                                                        <label for="">Lista De Audios: </label>
                                                                            <select name="SelectAudios" id="SelectAudios" class="form-control input-sm">
                                                                                <option value= "0" disabled selected>Elige Una Opción</option>
                                                                                <option disabled class="bg-info text-center">Audios Del Huesped</option>
                                                                                <?php echo $ListaAudiosHuesped; ?>
                                                                                <!-- <option value="NewAudio">Agregar Nuevo Audio</option>
                                                                                <option disabled class="bg-info text-center">Todos Los Audios</option> -->
                                                                                <?php //echo $ListaAudios; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="col-md-12">
                                                                    <h4 style='color: #3C8DBC;'> Fuera Del Horario </h4>
                                                                </div>
                                                                <div class="col-md-12" id="divTareaFueraHora" hidden>
                                                                    <div class="col-md-6"><b style="margin-left: 62%;">Tarea Actual: </b></div>
                                                                    <div class="col-md-6"><p class="float-left" id="LblTareaFueraHora">MINGA</p></div>
                                                                    <input type="hidden" id="IptTareaFueraHora" value="">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Posibles Tareas: </label>
                                                                        <select name="FueraHSelectPosiblesTareas" id="FueraHSelectPosiblesTareas" class="form-control input-sm" onchange="PosiblesTareas_FueraH();">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option value="Pasar a Una Campaña">Pasar a Agentes</option>
                                                                            <option value="Pasar a IVR">Pasar a IVR</option>
                                                                            <option value="Número Externo">Número Externo</option>
                                                                            <option value="Reproducir Grabación">Reproducir Grabación</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivInputNumeroExterno" hidden>
                                                                        <label for="">Número Externo: </label>
                                                                        <input type="text" id="FueraHInputNumeroExterno" name="InputNumeroExterno" class="form-control input-sm" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectTroncales" hidden>
                                                                    <label for="">Troncal De Dónde Sale La Llamada: </label>
                                                                        <select name="FueraHSelectTroncales" id="FueraHSelectTroncales" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">Troncales Del Huesped</option>
                                                                            <?php echo $ListaTroncalesHuesped; ?>
                                                                            <!-- <option disabled class="bg-info text-center">Todas Las Troncales</option> -->
                                                                            <?php //echo $ListaTroncales; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectCampanas" hidden>
                                                                    <label for="">Lista De Campañas: </label>
                                                                        <select name="FueraHSelectCampanas" id="FueraHSelectCampanas" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">Campañas De La Estrategia</option>
                                                                            <?php echo $ListaCampanas; ?>
                                                                            <!-- <option disabled class="bg-info text-center">Campañas Del Huesped</option>  -->
                                                                            <?php //echo $ListaCampanasHuesped; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectIVR" hidden>
                                                                    <label for="">Lista De IVR's: </label>
                                                                        <select name="FueraHSelectIVR" id="FueraHSelectIVR" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">IVR's De La Estrategia</option>
                                                                            <?php echo $ListaIVRs; ?>
                                                                            <!-- <option disabled class="bg-info text-center">IVR's Del Huesped</option> -->
                                                                            <?php //echo $ListaIVRsHuesped; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="FueraHdivSelectAudios" hidden>
                                                                    <label for="">Lista De Audios: </label>
                                                                        <select name="FueraHSelectAudios" id="FueraHSelectAudios" class="form-control input-sm">
                                                                            <option value= "0" disabled selected>Elige Una Opción</option>
                                                                            <option disabled class="bg-info text-center">Audios Del Huesped</option>
                                                                            <?php echo $ListaAudiosHuesped; ?>
                                                                            <!-- <option value="NewAudio">Agregar Nuevo Audio</option>
                                                                            <option disabled class="bg-info text-center">Todos Los Audios</option> -->
                                                                            <?php //echo $ListaAudios; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Lista Negra -->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="ModalListaNegra">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="staticBackdropLabel"> 📓 Lista Negra: </h2>
            </div>
            <div id="Loading_2" style="margin-top: 11%; margin-left: 30%;">
                <img src="<?=base_url?>assets/img/loader.gif">
            </div>
            <div class="modal-body" id="ListaNegra" hidden>
                <button type="button" class="btn btn-primary" id="BtnAddListaNegra"> Nuevo Número <i class="fa fa-plus"></i> 📲 </button>
                <div class="col-12 table-responsive" style="margin-top: 2%;">
                    <table id="TblListaNegra" class="table table-bordered table-striped text-center mt-4 dataTable">
                        <thead class="text-black">
                            <tr class="bg-info">
                                <th>ACCIÓN</th>
                                <th>NUMERO</th>
                                <th>RAZÓN</th>
                                <th>EDITAR</th>
                                <th>ELIMINAR</th>
                            </tr>
                        </thead>
                        <tbody id="CuerpoTblListaNegra">
                            <tr id="CeldasListaNegra">
                                <th> ( ´･･)ﾉ (._.`) </th>
                                <th> !No Se Encontraron Resultados! </th>
                                <th> ( ´･･)ﾉ (._.`) </th>
                                <th> !No Se Encontraron Resultados! </th>
                                <th> ( ´･･)ﾉ (._.`) </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!--Modal Añadir a Lista Negra-->
    <div class="modal fade" id="ModalAddListaNegra" tabindex="-1" aria-labelledby="ModalAddListaNegraLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Añadir Número a Lista Negra: </h5>
            </div>
            <div id="Loading_3" style="margin-top: 11%; margin-left: 28%;" hidden>
                <img src="<?=base_url?>assets/img/loader.gif">
            </div>
            <div class="modal-body" id="BodyModalAddListaNegra">
                <form class="col-md-12 mb-12" style="margin-top: 5%;">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="InputTelefono">Número De Telefono: </label>
                            <input type="number" class="form-control" id="InputTelefono" placeholder="Numero De Telefono"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motivo o Razón: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectListaMotivo" name="SelectListaMotivo">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option value="Llamada De Broma">Llamada De Broma</option>
                                    <option value="Llamada Obscena">Llamada Obscena</option>
                                    <option value="Niños Molestando">Niños Molestando</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="BtnAgregar">Agregar Número</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal Editar Lista Negra -->
    <div class="modal fade" id="ModalEditarListaNegra" tabindex="-1" aria-labelledby="ModalEditarListaNegraLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Número De Lista Negra: </h5>
                </div>
                <div class="modal-body">
                    <form class="col-md-12 mb-12" style="margin-top: 5%;">
                        <div class="row">
                            <div class="col-md-6" hidden>
                                <label class="form-label" for="InputId">Id: </label>
                                <input type="hidden" class="form-control" id="InputId"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="InputEditarTelefono">Número De Telefono: </label>
                                <input type="number" class="form-control" id="InputEditarTelefono" placeholder="Numero De Telefono"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Motivo o Razón: </label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectEditarListaMotivo" name="SelectEditarListaMotivo">
                                        <option disabled selected>Elige Una Opción</option>
                                        <option value="Llamada De Broma">Llamada De Broma</option>
                                        <option value="Llamada Obscena">Llamada Obscena</option>
                                        <option value="Niños Molestando">Niños Molestando</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="BtnActualizar">Actualizar Número</button>
                </div>
            </div>
        </div>
    </div>
    </div>

</div>


<!-- Modal General De IVRs // G11 -->
<link rel="stylesheet" href="<?=base_url?>assets/css/StyleMinga.css"/>
<link rel="stylesheet" href="<?=base_url?>assets/plugins/iCheck/all.css">
<link rel="stylesheet" href="<?=base_url?>assets/plugins/selectize.js-master/dist/css/selectize.css">
<link rel="stylesheet" href="<?=base_url?>assets/plugins/selectize.js-master/dist/css/selectize.bootstrap3.css">
<div class="modal fade bd-example-modal-lg" id="ModalIVRs" tabindex="-1" role="dialog" aria-labelledby="ModalIVRsLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas" onclick="LimpiarTodo();">×</button>
                <h4 class="modal-title" id="title_cargue"><b>Comunicación IVRs: </b></h4>
            </div>
            <div class="modal-body" style="height: 100%;">
                <div id="divIframe">
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
                        .lista table{
                            margin: 0;
                        }
                        .titulo-dragdrop{
                            background: #f1f1f1;
                            color: #858585;
                            border: 1px solid #eaeaea;
                            font-weight: bold;
                            padding: 6px;
                            margin-bottom: 0;
                        }
                        hr{
                            width: 90%
                        }
                    </style>
                </div>
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="box-tools"></div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12" id="div_formularioIVRs">
                                <div id="divBtnGuardarTodoIVRs">
                                    <button class="btn btn-primary" id="BtnGuardarTodoIVRs" onclick="GuardarTodoIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                    <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarTodo();"><i class="fa fa-close" aria-hidden="true"></i></button>
                                    <button class="btn btn-default" onclick="ActualizarFlujograma();"><i class="fa-solid fa-arrow-rotate-right"></i></button>
                                </div>
                                <div id="divBtnActualizarTodoIVRs" hidden>
                                    <button class="btn btn-primary" id="BtnActualizarTodoIVRs" onclick="ActualizarTodoIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                                    <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarTodo();"><i class="fa fa-close" aria-hidden="true"></i></button>
                                </div>
                                <input type="text" name="IdEstrategia" id="IdEstrategia" hidden>
                                <input type="text" name="IdIVR" id="IdIVR" hidden>
                                <input type="text" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'];?>" hidden>
                                <br></br>
                                <div>
                                    <!-- loading -->
                                    <div id="Loading_4" class="container-loader" style="margin-top: -5%; margin-left: 42%;" hidden>
                                        <div class="loader"></div>
                                        <p class="form-label text-black" style="margin-top: 2%; margin-left: 5%; color: #0028D2;"> GUARDANDO... </p>
                                        <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -20%; margin-left: -5%;">
                                    </div>
                                    <!-- Formulario IVR's -->
                                    <form action="#" id="FormularioDatos2" enctype="multipart/form-data" method="post">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="box-primary" style="margin-top: -2%;">
                                                    <!-- Configuracion IVRs-->
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#IVRsConfiguracion">Configuración:</a>
                                                        </h4>
                                                    </div>
                                                    <div id="IVRsConfiguracion" class="panel-collapse collapse in">
                                                        <div class="box-body">
                                                            <div class="modal-content">
                                                                <div class="modal-body" id="BodyModal">
                                                                    <div class="card-body bg-light">
                                                                        <div class="card-header bg-light text-center text-uppercase"><b>IVR'S</b></div>
                                                                        <div class="panel box box-primary"></div>
                                                                        <!-- Form IVR-->
                                                                        <form class="col-md-12 mb-12" style="margin-top: 5%;">
                                                                            <div class="row">
                                                                                <div class="form-check col-md-5">
                                                                                    <label class="form-label" for="InputNombreIVR">Nombre De IVR: </label>
                                                                                    <input type="text" class="form-control" id="InputNombreIVR" placeholder="Introduzca IVR"/>
                                                                                </div>
                                                                            <!-- 
                                                                                <div class="col-md-4">
                                                                                    <label class="form-label" for="InputOpcionIVR">Nombre De Opción: </label>
                                                                                    <input type="text" class="form-control" id="InputOpcionIVR" placeholder="Introduzca Opción"/>
                                                                                </div>
                                                                                <div class="form-check col-md-2" id="divCheckHabilitarDISA" style="margin-top: 2%;" hidden>
                                                                                    <input class="form-check-input" type="checkbox" id="CheckHabilitarDISA">
                                                                                    <label class="form-check-label" for="CheckHabilitarDISA">Habilitar DISA</label>
                                                                                </div>
                                                                                <div class="form-check col-md-2" id="divCheckMarcadoExtensiones" style="margin-top: 2%;" hidden>
                                                                                    <input class="form-check-input" type="checkbox" id="CheckMarcadoExtensiones">
                                                                                    <label class="form-check-label" for="CheckMarcadoExtensiones">Marcado Extensiones</label>
                                                                                </div>
                                                                            -->
                                                                            </div>
                                                                        <!--
                                                                            <div class="row" style="margin-top: 1%;">
                                                                                <div class="form-check col-md-4">
                                                                                    <label class="form-label">Grabación De Bienvenida: </label>
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-control" id="SelectGrabaBienvenida" name="SelectGrabaBienvenida">
                                                                                            <option disabled selected>Elige Una Opción</option>
                                                                                            <?php echo $ListaGrabaciones;?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check col-md-4">
                                                                                    <label class="form-label">Grabación De Toma De Dígitos: </label>
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-control" id="SelectGrabaDigitos" name="SelectGrabaDigitos">
                                                                                            <option disabled selected>Elige Una Opción</option>
                                                                                            <?php echo $ListaGrabaciones;?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-check col-md-4">
                                                                                    <label class="form-label" for="InputTiempoEspera">Tiempo De Espera: </label>
                                                                                    <input type="number" class="form-control" id="InputTiempoEspera" placeholder="5"/>
                                                                                </div>
                                                                            </div>
                                                                        -->
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                    <!-- Lista De Opciones -->
                                                    <div class="box-header with-border" id="TtlListaOpciones" hidden>
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#IVRsOpciones">Lista De Opciones:</a>
                                                        </h4>
                                                    </div>
                                                    <div id="IVRsOpciones" class="panel-collapse collapse in">
                                                        <!-- Tabla IVR Opciones-->
                                                        <div id="TablaIVRs" class='box-body' hidden>
                                                            <div class='row'>
                                                                <div class='col-md-12 col-xs-12  table-responsive'>
                                                                    <table class='table table-condensed' id=''>
                                                                        <thead>
                                                                            <tr class='active'>
                                                                                <th scope="col" class="col-md-4" style="text-align: center;"><label> OPCIÓN (NÚMERO A MARCAR) </label></th>
                                                                                <th scope="col" class="col-md-4" style="text-align: center;"><label> NOMBRE OPCIÓN </label></th>
                                                                                <th scope="col" class="col-md-4" style="text-align: center;"><label> ACCIONES </label></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id='TablaOpciones'>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                    <label id="LblRespuesta"></label>
                                                                </div>
                                                            </div>
                                                            <div class='row'>
                                                                <div class='col-md-9'>

                                                                </div>
                                                                <div class="col-md-2" style="text-align: right;">
                                                                    <button type="button" class="btn btn-sm btn-success" id="BtnNuevaOpcion">
                                                                        <i class="glyphicon glyphicon-plus"></i> Agregar 
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Flujograma IVR's -->
                                                <div class="row" style="margin-top: 15px">
                                                    <div class="col-md-12">
                                                        <div class="panel box box-primary box-solid">
                                                        <input type="hidden" name="IdPaso" id="IdPaso" value="<?php if(isset($_GET['id_paso'])){ echo  $_GET['id_paso']; }else{ echo "0"; } ?>">
                                                            <div class="box-header with-border">
                                                                <h4 class="box-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#SeccionesIVR">
                                                                        Opciones y Acciones IVR's
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="SeccionesIVR" class="panel-collapse collapse in">
                                                                <div class="box-body">
                                                                    <div class="col-md-1">
                                                                        <div id="ListaSecciones" style="background-color: #F8F8F8; width:150%; height: 592px; margin-left: -20%;"></div>
                                                                    </div>
                                                                    <div class="col-md-11">
                                                                        <div id="SeccionGrafico" style="background-color: #F8F8F8; border: solid 1px black; width:100%; height:600px;"></div>
                                                                    </div>
                                                                    <textarea name="SavedModelIVR" id="SavedModelIVR" cols="30" rows="10" style="display:none"></textarea>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Opcion -->
<div class="modal fade bd-example" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="ModalNuevaOpcion">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="LimpiarOpciones();">×</button>
            <h4 class="modal-title" id="title_cargue"><b>Editando Una Opción De '</b><b id="TltIVR">  </b></h4>
        </div>
        
        <div class="panel box box-primary"></div>

        <div class="box-header" style="margin-top: -5%;">
            <div class="box-tools"></div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="divBtnGuardarOpcion">
                        <button class="btn btn-primary" id="BtnGuardarOpcion" onclick="GuardarOpcionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarOpciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                    </div>
                    <div id="divBtnActualizarOpcion" hidden>
                        <button class="btn btn-primary" id="BtnActualizarOpcion" onclick="ActualizarOpcionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarOpciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                        <input type="hidden" class="form-control" id="IdOpcion">
                    </div>
                    <br></br>
                    <div>
                        <!-- Formulario -->
                        <form id="" enctype="multipart/form-data" method="post">
                            <div class="row">
                                <div class="modal-body" style="margin-top: -4%;">
                                    <div class="card-body bg-light">
                                        <form class="col-md-12 mb-12">
                                            <div class="row">
                                                <div class="form-check col-md-4">
                                                    <label class="form-label">Opción (Número A Marcar): </label>
                                                    <div class="form-group">
                                                        <select class="form-select form-control" id="SelectOpcionNumero" name="SelectOpcionNumero">
                                                            <option disabled selected>Elige Una Opción</option>
                                                            <option value="0">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="X">Otro</option>
                                                            <option value="t">Si No Marca Nada</option>
                                                            <option value="i">Opción Incorrecta</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="NombreOpcion">Nombre De Opción: </label>
                                                    <input type="text" class="form-control" id="NombreOpcion" placeholder="Introduzca Opción"/>
                                                </div>
                                                <div class="form-check col-md-4" id="divCheckOpcionValida" style="margin-top: 5%;">
                                                    <input class="form-check-input" type="checkbox" id="CheckOpcionValida">
                                                    <label class="form-check-label" for="CheckOpcionValida">Opción Válida</label>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

        <div class="box box-primary"></div>

        <!-- Tabla IVR Acciones-->
        <div id="TablaAccionesIVR" class='box-body' style="margin-top: -2%;" hidden>
            <div class="col-md-12 float-end" style="text-align: right;">
                <button type="button" class="btn btn-sm btn-success" id="BtnNuevaAccion"><i class="glyphicon glyphicon-plus"></i> Agregar Acción </button>
            </div>
            <div class='row'>
                <div class='col-md-12 col-xs-12  table-responsive' style="margin-top: 2%;">
                    <table class='table table-condensed' id=''>
                        <thead>
                            <tr class='active'>
                                <th scope="col" class="col-md-2" style="text-align: center;"><label> ORDEN </label></th>
                                <th scope="col" class="col-md-3" style="text-align: center;"><label> ACCIÓN </label></th>
                                <th scope="col" class="col-md-5" style="text-align: center;"><label> VALOR ACCIÓN </label></th>
                                <th scope="col" class="col-md-2" style="text-align: center;"><label> OPCIONES </label></th>
                            </tr>
                        </thead>
                        <tbody id='BodyTablaAccionesIVR'>
                            
                        </tbody>
                    </table>
                    <label id="LblRespuesta"></label>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal Nueva Accion -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalNuevaAccion">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="LimpiarAcciones();">×</button>
            <h4 class="modal-title" id="title_cargue"><b>Editando Acción '</b><b id="TltOpcion">  </b></h4>
        </div>

        <div class="panel box box-primary"></div>
        
        <div class="box-body">
            <div class="row">

                <div class="col-md-12 mb-12" >
                    <div id="divBtnGuardarAccion">
                        <button class="btn btn-primary" id="BtnGuardarAccion" onclick="GuardarAccionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                    </div>
                    <div id="divBtnActualizarAccion" hidden>
                        <button class="btn btn-primary" id="BtnActualizarAccion" onclick="ActualizarAccionIVRs('<?=base_url?>');"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                        <input type="hidden" class="form-control" id="IdAccion">
                    </div>
                </div>
                <br></br>

                <form class="col-md-12 mb-12">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label" for="OrdenEjecucion">Orden: </label>
                            <input type="number" class="form-control" id="OrdenEjecucion" placeholder="0"/>
                        </div>
                        <div class="form-check col-md-7">
                            <label class="form-label">Acción: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectAccion" name="SelectAccion">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option value="Numero Externo">Numero Externo</option>
                                    <option value="Pasar a Una Campaña">Pasar a Una Campaña</option>
                                    <option value="Reproducir Grabacion">Reproducir Grabación</option>
                                    <option value="Pasar a Otro IVR">Pasar a Otro IVR</option>
                                    <option value="Pasar a Encuesta">Pasar a Encuesta</option>
                                    <option value="Avanzado">Avanzado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="divNumeroExterno" hidden>
                        <div class="form-check col-md-6">
                            <label class="form-label">Linea: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectLinea" name="SelectLinea">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option disabled class="bg-info text-center">Troncales Del Huesped</option>
                                    <?php echo $ListaTroncalesHuesped; ?>
                                    <option disabled class="bg-info text-center">Todas Las Troncales</option>
                                    <?php echo $ListaTroncales; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="NumeroExterno">Numero: </label>
                            <input type="number" class="form-control" id="NumeroExterno" placeholder="Introduce Numero"/>
                        </div>
                    </div>
                    <div class="row" id="divCampana" hidden>
                        <div class="form-check col-md-8">
                            <label class="form-label">Campaña: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectCampana" name="SelectCampana">
                                    <option disabled selected>Elige Una Opción</option>
                                    <?php echo $ListaCampanasIVR; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-check col-md-4" style="margin-top: 2%;">
                            <input class="form-check-input" type="checkbox" id="CheckEncuesta">
                            <label class="form-check-label" for="CheckEncuesta">Transferir Encuesta</label>
                        </div>
                    </div>
                    <div class="row" id="divGraba" hidden>
                        <div class="form-check col-md-12">
                        <label for="">Lista De Audios: </label>
                            <select name="SelectGraba" id="SelectGraba" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaGrabaciones; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="divListaIVR" hidden>
                    <label for="">Lista De IVR's: </label>
                        <select name="ListaIVR" id="ListaIVR" class="form-control input-sm">
                            <option disabled selected>Elige Una Opción</option>
                            <?php echo $ListaIVRs_2; ?>
                        </select>
                    </div>
                    <div class="row" id="divEncuesta" hidden>
                        <div class="form-check col-md-12">
                        <label for="">Lista De Encuesta: </label>
                            <select name="SelectEncuesta" id="SelectEncuesta" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaEncuesta; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="divAvanzadoIVR" hidden>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Etiqueta: </label>
                                <input type="text" id="InputEtiqueta" name="InputEtiqueta" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label for="Aplicacion">Aplicación: </label>
                            <select name="SelectAplicacion" id="SelectAplicacion" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaAplicaciones; ?>
                            </select>
                        </div>
                        <div class="form-check col-md-12">
                            <div class="form-group">
                                <label for="Parametros">Parámetros: </label>
                                <input type="text" id="InputParametros" name="InputParametros" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>




<!-- Flujograma IVRs-->
<!-- loading / Flujograma IVR -->
<div class="box-body">
    <div class="row">
        <div id="Loading_5" class="container-loader" style="margin-top: 11%; margin-left: 45%;" hidden>
            <div class="loader"></div>
            <p class="form-label text-black" style="margin-top: 11%; margin-left: 5%; color: #0028D2;"> UN MOMENTO... </p>
            <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -20%; margin-left: -5%;">
        </div>
    </div>
</div>

<!-- Modal Esfera De Inicio / Flujograma IVR-->
<div class="modal fade" id="ModalInicioFlujogramaIVR" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">×</button>
            <div id="divBtnCapturarDatosIVRs">
                <button class="btn btn-primary" id="BtnCapturarDatosIVRs" onclick="CapturarDatosIVRs();"><i class="fa fa-save" aria-hidden="true"></i></button>
                <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12" id="div_formularioIVRs">

                    <!-- Formulario Inicial IVR's -->
                    <form action="#" id="FormularioIVR" enctype="multipart/form-data" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-primary" style="margin-top: -2%;">
                                    <!-- Configuracion Inicial IVRs-->
                                    <div class="box-body">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="card-body bg-light">
                                                    <div class="card-header bg-light text-center text-uppercase"><b>IVR'S</b></div>
                                                    <div class="panel box box-primary"></div>
                                                    <form class="col-md-12 mb-12" style="margin-top: 5%;">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="InputOpcionIVR">Nombre De Esfera: </label>
                                                                <input type="text" class="form-control" id="InputOpcionIVR" value="Inicio IVR" placeholder="Inicio IVR"/>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-top: 1%;">
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">Grabación De Bienvenida: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaBienvenida" name="SelectGrabaBienvenida">
                                                                        <option disabled selected>Elige Una Opción</option>
                                                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">Grabación Captura De Dígitos: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaDigitos" name="SelectGrabaDigitos">
                                                                        <option disabled selected>Elige Una Opción</option>
                                                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-top: 1%;">
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">¿Aceptar Dígitos Durante Grabación?</label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectAceptarDigitos" name="SelectAceptarDigitos">
                                                                        <option value="1" selected>Si, En Cualquier Momento</option>
                                                                        <option value="0">No, Solo Al Final</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-check col-md-6">
                                                                <label class="form-label">Grabación De Opción Errada: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaErradaIVR" name="SelectGrabaErradaIVR">
                                                                        <option disabled="" selected="">Elige Una Opción</option>
                                                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-top: 1%;">
                                                            <div class="form-check col-md-6">
                                                                <label class="form-label" for="InputTiempoEspera">Tiempo De Espera: </label>
                                                                <input type="number" class="form-control" id="InputTiempoEspera" value="5" placeholder="5"/>
                                                            </div>
                                                            
                                                            <div class="form-check col-md-6">
                                                                <label for="TxtNotificar">Intentos Errados o Sin Respuesta Permitidos: </label>
                                                                <input type="number" class="form-control" id="IntentosPermitidos" name="IntentosPermitidos" value="3">
                                                            </div>
                                                            
                                                            <!-- <div class="form-check col-md-4">
                                                                <label class="form-label">Grabación De Opción Errada: </label>
                                                                <div class="form-group">
                                                                    <select class="form-select form-control" id="SelectGrabaErrada" name="SelectGrabaDigitos">
                                                                        <option disabled selected>Elige Una Opción</option>
                                                                        <?php echo $ListaGrabaciones;?>
                                                                    </select>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div> 

        <div class="box box-primary"></div>

    </div>
  </div>
</div>

<!-- Modal Esfera Toma De Digitos / Flujograma IVR-->
<div class="modal fade" id="ModalTomaDeDigitos" tabindex="-1" role="dialog" aria-labelledby="ModalTomaDeDigitosTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="divBtnCapturarTomaDigitos">
                    <button class="btn btn-primary" id="BtnCapturarTomaDigitos" onclick="CapturarTomaDeDigitos();"><i class="fa fa-save" aria-hidden="true"></i></button>
                    <button class="btn btn-default" id="cancel" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i></button>
                </div>
            </div>
            <input type="text" name="IdOpcion_4"  id="IdOpcion_4" hidden/>
            <input type="text" name="IdTomaDigitos"  id="IdTomaDigitos" hidden/>
            <input type="text" name="IdAccion_3"  id="IdAccion_3" hidden/>
            <input type="text" name="OrdenEjecu_2" id="OrdenEjecu_2" hidden>
            
            <div class="modal-body">
                <div class="card-body bg-light">
                    <div class="card-header bg-light text-center text-uppercase"><b>Captura De Respuesta</b></div>
                    <div class="panel box box-primary"></div>

                    <form class="col-md-12 mb-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label" for="InputOpcionIVR_2">Nombre Toma Respuesta: </label>
                                <input type="text" class="form-control" id="InputOpcionIVR_2" placeholder="Captura De Respuesta"/>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="form-check col-md-6">
                                <label class="form-label">Grabación Captura De Respuesta: </label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectGrabaDigitos_2" name="SelectGrabaDigitos_2">
                                        <option disabled selected>Elige Una Opción</option>
                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                        <?php echo $ListaGrabaciones;?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-check col-md-6">
                                <label class="form-label">¿Aceptar Dígitos Durante Grabación?</label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectAceptarDigitos_2" name="SelectAceptarDigitos_2">
                                        <option value="1" selected>Si, En Cualquier Momento</option>
                                        <option value="0">No, Solo Al Final</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-check col-md-6">
                                <label class="form-label">Grabación De Opción Errada: </label>
                                <div class="form-group">
                                    <select class="form-select form-control" id="SelectGrabaErrada_2" name="SelectGrabaErrada_2">
                                        <option disabled selected>Elige Una Opción</option>
                                        <option disabled class="bg-info text-center">Grabaciones Del Huesped</option>
                                        <?php echo $ListaGrabaciones;?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-check col-md-6">
                                <label class="form-label" for="InputTiempoEspera_2">Tiempo Espera: </label>
                                <input type="number" class="form-control" id="InputTiempoEspera_2" value="5"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-check col-md-6">
                                <label for="IntentosPermitidos_2">Intentos Errados Permitidos: </label>
                                <input type="number" class="form-control" id="IntentosPermitidos_2" name="IntentosPermitidos_2" value="3">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
            
            </div>
        </div>
    </div>
</div>

<!-- Modal Esferas Acciones IVR's / Flujograma -->
<div class="modal fade bd-example-modal-sm" id="ModalAccionesFlujograma" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" onclick="">×</button>
            <h4 class="modal-title" id="title_cargue"><b id="TltOpcion_2">  </b></h4>
        </div>

        <div class="panel box box-primary"></div>
        <input type="text" name="IdOpcion_3" id="IdOpcion_3" hidden>
        <input type="text" name="IdAccion_2" id="IdAccion_2" hidden>
        <input type="text" name="OrdenEjecu" id="OrdenEjecu" hidden>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12 mb-12" >
                    <div id="divBtnGuardarAcciones">
                        <button class="btn btn-primary" id="BtnGuardarAcciones" onclick="CapturarDatosAccion();"><i class="fa fa-save" aria-hidden="true"></i></button>
                        <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                    </div>
                </div>
                <br></br>

                <form class="col-md-12 mb-12">

                    <div class="col-md-12" id="divNombreAccion">
                        <label class="form-label" for="NombreAccion">Nombre De Acción: </label>
                        <input type="text" class="form-control" id="NombreAccion" placeholder="Introduzca Acción"/>
                    </div>
                    
                    <div id="divNumeroExterno_2" hidden>
                        <div class="col-md-12" style="margin-top: 1%;">
                            <label class="form-label" for="NumeroExterno_2">Numero: </label>
                            <input type="number" class="form-control" id="NumeroExterno_2" placeholder="00000"/>
                        </div>
                        <div class="form-check col-md-12" style="margin-top: 1%;">
                            <label class="form-label">Troncal: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectLinea_2" name="SelectLinea_2">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option disabled class="bg-info text-center">Troncales Del Huesped</option>
                                    <?php echo $ListaTroncalesHuesped; ?>
                                    <!-- <option disabled class="bg-info text-center">Todas Las Troncales</option> -->
                                    <?php //echo $ListaTroncales; ?> 
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" id="divCampana_2" hidden>
                        <div class="form-check">
                            <label class="form-label">Campaña: </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectCampana_2" name="SelectCampana_2">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option disabled class="bg-info text-center">Campañas De La Estrategia</option>
                                    <?php echo $ListaCampanas; ?>
                                    <option disabled class="bg-info text-center">Campañas Del Huesped</option>
                                    <?php echo $ListaCampanasIVR; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-check col-md-4" style="margin-top: 2%;" hidden>
                            <input class="form-check-input" type="checkbox" id="CheckEncuesta_2">
                            <label class="form-check-label" for="CheckEncuesta_2">Transferir Encuesta</label>
                        </div>
                    </div>
                    <div class="col-md-12" id="divGraba_2" hidden>
                        <div class="form-check">
                        <label for="">Lista De Audios: </label>
                            <select name="SelectGraba_2" id="SelectGraba_2" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaGrabaciones; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12" id="divListaIVR_2" hidden>
                        <label for="ListaIVR_2">Lista De IVR's: </label>
                        <select name="ListaIVR_2" id="ListaIVR_2" class="form-control input-sm">
                            <option disabled selected>Elige Una Opción</option>
                            <option disabled class="bg-info text-center">IVR's De La Estrategia</option>
                            <?php echo $ListaIVRs; ?>
                            <option disabled class="bg-info text-center">IVR's Del Huesped</option>
                            <?php echo $ListaIVRsHuesped; ?>
                        </select>
                    </div>
                    <div class="col-md-12" id="divEncuesta_2" hidden>
                        <div class="form-check">
                        <label for="">Lista De Encuesta: </label>
                            <select name="SelectEncuesta_2" id="SelectEncuesta_2" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaEncuesta; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="divAvanzadoIVR_2" hidden>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">Etiqueta: </label>
                                <input type="text" id="InputEtiqueta_2" name="InputEtiqueta_2" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label for="Aplicacion">Aplicación: </label>
                            <select name="SelectAplicacion_2" id="SelectAplicacion_2" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaAplicaciones; ?>
                            </select>
                        </div>
                        <div class="form-check col-md-12">
                            <div class="form-group">
                                <label for="Parametros_2">Parámetros: </label>
                                <input type="text" id="InputParametros_2" name="InputParametros_2" class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="divEsferaFinal" hidden>
                        <div class="form-group col-12">
                            <label class="form-label" for="NombreFinal">Nombre Final: </label>
                            <input type="text" class="form-control" id="NombreFinal" value="Final IVR">
                        </div>
                        <div class="form-check col-12" id="divGrabaGrabaDespedida">
                            <label>Grabación De Despedida: </label>
                            <select name="SelectGrabaDespedida" id="SelectGrabaDespedida" class="form-control input-sm">
                                <option disabled selected>Elige Una Opción</option>
                                <?php echo $ListaGrabaciones; ?>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal Esfera Avanzado -->
<div class="modal fade bd-example-modal-lg" id="ModalAvanzado" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="LimpiarTablaAvanzado();">&times;</span></button>
                <div>
                    <button class="btn btn-primary" id="BtnGuaradarAvanzado" onclick="CapturarDatosAvanzado();"><i class="fa fa-save" aria-hidden="true"></i></button>
                    <button class="btn btn-default" id="BtnLimpiarAvanzado" data-dismiss="modal" onclick="LimpiarTablaAvanzado();"><i class="fa fa-close" aria-hidden="true"></i></button>
                </div>
                <div>
                    <input type="text" name="IdOpcion_5" id="IdOpcion_5" hidden>
                    <input type="text" name="IdAccion_4" id="IdAccion_4" hidden>
                    <input type="text" name="OrdenEjecu_3" id="OrdenEjecu_3" hidden>
                    <input type="text" name="NumFilasExistentes" id="NumFilasExistentes" value="0" hidden>
                    <textarea type="text" id="IdsActualizar" hidden></textarea>
                </div>
            </div>
            
            <div class="modal-body">
                <div class="card-body bg-light">
                    <div class="card-header bg-light text-center text-uppercase"><b>Opciones Avanzadas</b></div>
                    <div class="panel box box-primary"></div>

                    <div id="" class="panel-collapse collapse in" aria-expanded="true">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="NombreAvanzado">Nombre De Opción Avanzada: </label>
                                    <input type="text" class="form-control" name="NombreAvanzado" id="NombreAvanzado" placeholder="Opción Avanzada">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-xs-12 table-responsive">
                                    <table class="table table-condensed" id="TblAvanzado">
                                        <!-- Titulos Tabla -->
                                        <thead>
                                            <tr class="active">
                                                <th scope="col" class="col-md-3" style="visibility:collapse; display:none;"><label> Nombre Acción </label></th>
                                                <th scope="col" class="col-md-3"><label> Nombre Etiqueta </label></th>
                                                <th scope="col" class="col-md-3"><label> Aplicación </label></th>
                                                <th scope="col" class="col-md-4"><label> Parámetros </label></th>
                                                <th scope="col" class="col-md-1"><label> Editar </label></th>
                                                <th scope="col" class="col-md-1"><label> Eliminar </label></th>
                                            </tr>
                                        </thead>
                                        <!-- Cuerpo Tabla -->
                                        <tbody id="tbodyAvanzado">

                                            <!-- Fila Existente 
                                            <tr class="text-center" id="FilaAvanzado_">
                                                <td class="col-md-2">
                                                    <input type="text" class="form-control" id="Etiqueta" placeholder="Etiqueta">
                                                </td>
                                                <td class="col-md-3">
                                                    <select id="Aplicacion" class="form-control">
                                                        <option disabled selected>Elige Una Opción</option>
                                                        <?php echo $ListaAplicaciones; ?>
                                                    </select>
                                                </td>

                                                <td class="col-md-3">
                                                    <input type="text" class="form-control" id="Parametros" placeholder="Parámetros">
                                                </td>
                                                
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <button class="btn btn-danger btn-sm deleteFirme form-control" title="Borrar opcion" type="button">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            -->

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="pull-right form-group col-md-6" style="text-align: right; margin-top: 2%;">
                                <button type="button" class="btn btn-sm btn-success" id="BtnAgregarNuevo" role="button">
                                    <i class="fa fa-plus" aria-hidden="true">&nbsp; Agregar </i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Flechas -->
<div class="modal fade bd-example-modal-sm" id="ModalOpcionFlecha" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><b>Opción </b><b id="TltOpcionFlecha"></b></h4>
            </div>
            <div class="panel box box-primary"></div>
            <input type="hidden" class="form-control" id="IdOpcion_2">
            <input type="hidden" class="form-control" id="IdFlecha">
            <input type="hidden" class="form-control" id="TxtIdFrom">
            <input type="hidden" class="form-control" id="TxtIdTo">

            <div class="box-body">

                <div class="row">
                    <div class="col-md-12 mb-12" >
                        <div id="divBtn">
                            <button class="btn btn-primary" id="Btn" onclick="GuardarOpcion();"><i class="fa fa-save" aria-hidden="true"></i></button>
                            <button class="btn btn-default" id="cancel" data-dismiss="modal" onclick="LimpiarAcciones();"><i class="fa fa-close" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <br></br>
                </div>

                <form class="col-md-12 mb-12">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label" for="NombreOpcionFlecha">Nombre De Opción: </label>
                            <input type="text" class="form-control" id="NombreOpcionFlecha" placeholder="Introduzca Opción"/>
                        </div>
                        <div class="form-check col-md-12">
                            <label class="form-label">Opción (Número A Marcar): </label>
                            <div class="form-group">
                                <select class="form-select form-control" id="SelectOpcionNumero_2" name="SelectOpcionNumero_2">
                                    <option disabled selected>Elige Una Opción</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="X">Otra</option>
                                    <option value="t">Si No Marca Nada</option>
                                    <option value="i">Opción Incorrecta</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8" id="divTxtCual" hidden>
                            <label class="form-label" for="TxtCual"> ¿Cual Opción? </label>
                            <input type="text" class="form-control" id="TxtCual" placeholder="11"/>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



<script src='https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.4/sweetalert2.all.js'></script>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="<?=base_url?>assets/plugins/Flowchart/flowchart.js"></script>
<script src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/configFlujograma.js"></script>
<!-- Funciones Para Ruta Entrante -->
<input type="text" value="<?=base_url?>" id="InputDirreccion" hidden/>
<script src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G39/G39_Eventos.js"></script>
<script>
    var Dirreccion= $("#InputDirreccion").val();
    EjecutarFunciones(Dirreccion);
</script>

<!-- Funciones Para IVR's -->
<script src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G11/G11_Eventos.js"></script>

<!-- funcion para llamar desde aqui esta parte -->
<script type="text/javascript">
    $(function(){
        cargarDatos(<?php echo $str_poblacion; ?>);
    });

    function cargarDatos(idGuion){
        $.ajax({
            url      : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?llenarDatosPregun=true',
            type     : 'post',
            data     : { guion  :  idGuion },
            dataType : 'json',
            success  : function(data){
                datosGuion = data;
            }
        })
    }
    
    function LlamarModal(tipoPaso, key, category){
        var invocador = tipoPaso;
        var llaveInvocar = key;
        var llevaCampan = '';
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?hayCampana=true',
            type : 'post',
            data : { pasoId :  llaveInvocar },
            success : function(data){
                llevaCampan=data;
                if(invocador == 1){
                    //window.location.href = 'index.php?page=llam_saliente&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>'
                    $.ajax({
                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?tieneCampana=true',
                        type : 'post',
                        data : { pasoId :  llaveInvocar },
                        success : function(data){
                            if(data != '0'){
                                //window.location.href = 'index.php?page=entrantes&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>&ruta=1';
                                window.location.href = '<?=base_url?>modulo/ruta/entrantes/'+llevaCampan+'/<?php echo $str_poblacion; ?>/1';
                            }else{
                                /* no tiene campaña asociada */
                                $("#title_estratCampan").html('<?php echo $campan_title___;?>');
                                $("#id_estpas_mio").val(llaveInvocar);
                                $("#backoffice").hide();
                                $("#marcadorRoboticoIVR").hide();
                                $("#campanasNormales").show();
                                $("#AddTipoCampan").val(1);
                                $("#crearCampanhasNueva").modal();
                            }
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
        
                }else if(invocador == 2){
        
                    $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>'+category);
                    $("#frameContenedor").attr('src', '');
        
                }else if(invocador == 3){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=mail_entrante&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type : 'get',
                        success : function(data){
                            $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>'+category);
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
        
                }else if(invocador == 4){   
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=web_form&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type : 'get',
                        success : function(data){
                            $("#title_estratPasosC").html('<?php echo $str_strategia_edicion.' '; ?>'+'formulario web');
                            $("#divIframePasosCortos").html(data);
                            $("#pasoscortos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                    
                }else if(invocador == 5){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=cargueDatos&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type : 'get',
                        success : function(data){
                            $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>'+category);
                            $("#title_cargue").html('<?php echo $str_carga ?>');
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
        
                }else if(invocador == 6){
                    /*$.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=campan&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type : 'get',
                        success : function(data){
                            $("#title_estrat").html('<?php //echo $str_strategia_edicion.' '; ?>'+category);
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                           
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });*/
                    /* Toca primero preguntar si tiene o no tiene una campaña asociada */
                    $.ajax({
                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?tieneCampana=true',
                        type : 'post',
                        data : { pasoId :  llaveInvocar },
                        success : function(data){
                            if(data != '0'){
                                //window.location.href = 'index.php?page=campan&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>&ruta=1';
                                window.location.href = '<?=base_url?>modulo/ruta/campan/'+llevaCampan+'/<?php echo $str_poblacion; ?>/1';
                            }else{
                                /* no tiene campaña asociada */
                                $("#title_estratCampan").html('<?php echo $campan_title___;?>');
                                $("#backoffice").hide();
                                $("#marcadorRoboticoIVR").hide();
                                $("#campanasNormales").show();
                                $("#id_estpas_mio").val(llaveInvocar);
                                $("#AddTipoCampan").val(2);
                                $("#crearCampanhasNueva").modal();
                            }
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                   
                   
        
                }else if(invocador == 7){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=mail&estrat=<?php echo $_GET['estrategia'];?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type : 'get',
                        success : function(data){
                            $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>'+category);
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                    
                }else if(invocador == 8){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=sms_S&estrat=<?php echo $_GET['estrategia'];?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type : 'get',
                        success : function(data){
                            $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>'+category);
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                       
                }else if(invocador == 9){
        
                    getDatosBackoffice(llaveInvocar);
                    
        
                    // $.ajax({
                    //     url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?tieneCampana=true',
                    //     type : 'post',
                    //     data : { pasoId :  llaveInvocar },
                    //     success : function(data){
                    //         if(data != '0'){
                    //             window.location.href = 'index.php?page=backoffice&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>&ruta=1';
                    //         }else{
                    //             /* no tiene campaña asociada */
                    //             $("#id_estpas_mio").val(llaveInvocar);
                    //             $("#AddTipoCampan").val(3);
                    //             $("#title_estratCampan").html('<?php echo $campan_titleB__;?>');
                    //             $("#backoffice").show();
                    //             $("#campanasNormales").hide();
                    //             $("#crearCampanhasNueva").modal();
                    //         }
                    //     },
                    //     beforeSend : function(){
                    //         $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    //     },
                    //     complete : function(){
                    //         $.unblockUI();
                    //     }
                    // });
                }else if(invocador == 10){
                    
                    $("#form-lead")[0].reset();
        
                    $('#id_estpas').val(llaveInvocar);
        
                    $.ajax({
                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getLead=true',
                        type : 'post',
                        dataType : 'json',
                        data : { pasoId :  llaveInvocar, bd : '<?php echo $str_poblacion; ?>' },
                        success : function(data){
        
                            if(data.dataLead.nombre != null){
                                $('#nombreLead').val(data.dataLead.nombre);
                            }else{
                                $('#nombreLead').val("MAIL_ENTRANTE_"+llaveInvocar);    
                            }
                            if(data.dataLead.quienRecibe != null){
                                if(data.dataLead.leadActivo==1){
                                    $('#leadActivo').prop('checked','checked');
                                }else{
                                    $('#leadActivo').prop('checked',false);
                                }
                                $('#leadQuienRecibe').val(data.dataLead.quienRecibe).change();
                                $('#leadTipoCondicion').val(data.dataLead.tipoCondicion).change();
                                $('#leadCondicion').val(data.dataLead.condicion);
                            }else{
                                $('#leadActivo').prop('checked','checked'); 
                            }
                            
                            $("#LeadCampos tbody").html('');
        
                            
                            $.each(data.camposEmparejarLead, function(i, item){
                                var campos = '';
        
                                campos += '<tr>';
                                campos += '<input type="hidden" name="listCamposLead[]" id="listCamposLead_'+item.id+'" value="'+item.id+'">'
                                campos += '<td>';
                                campos += '<input type="text" required class="form-control input-sm" name="tagInicial_'+item.id+'" id="tagInicial_'+item.id+'" placeholder="identificador Inicial">';
                                campos += '<td>';
                                campos += '<input type="text" class="form-control input-sm tag-final" name="tagFinal_'+item.id+'" id="tagFinal_'+item.id+'" placeholder="</ejemplo>">';
                                campos += '</td>';
                                campos += '<td>';
                                campos += '<select name="campoBD_'+item.id+'" id="campoBD_'+item.id+'" class="form-control input-sm required">';
                                campos += '<option value="0">Seleccione</option>';
                                campos += opcionesCampoPregun;         
                                campos += '</select>';
                                campos += '</td>';
                                campos += '<td>';
                                campos += '<button type="button" id="'+item.id+'" class="btn btn-danger btn-sm borrarCampoEmparejarLead"><i class="fa fa-trash"></i></button>';
                                campos += '</td>';
                                campos += '</tr>';
        
                                $("#LeadCampos tbody").append(campos);
        
                                $('#tagInicial_'+item.id).val(item.tagInicial);
                                $('#tagFinal_'+item.id).val(item.tagFinal);
                                $('#campoBD_'+item.id).val(item.campoBD).change();
                            });
        
                            // Validamos si esta checkeado el desablilitar tag final
                            if(data.dataLead.noTomarTagFinal && data.dataLead.noTomarTagFinal == -1){
                                $("#desactivarTagFinal").click();
                            }

                            // Asignamos los campos disponibles del G
                            $("#LeadCampoLlave").html("");
                            let campoLlave = '<option value="0" selected>Seleccione</option>'+opcionesCampoPregunSoloCliente;
                            $("#LeadCampoLlave").append(campoLlave)

                            if(data.dataLead.campoLlave != null){
                            // Asignamos el campo llave actual
                                $("#LeadCampoLlave").val(data.dataLead.campoLlave).trigger('change');

                            }else{
                                $("#LeadCampoLlave").val(0).trigger('change');
                            }

        
                            $(".borrarCampoEmparejarLead").click(function(){
                                var id = $(this).attr('id');
                                var self = $(this);
                                alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
                                    if (e) {
                                        $.ajax({
                                            url     : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php',
                                            type    : 'POST',
                                            dataType: 'json',
                                            data    : { borrarCampoEmparejarLead : true, idCampo : id},
                                            beforeSend : function(){
                                                $.blockUI({ 
                                                    baseZ: 2000,
                                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                            },
                                            complete : function(){
                                                $.unblockUI();
                                                desactivarTagFinal();
                                            },
                                            success : function(data){
                                                alertify.success(data.message);
                                                self.closest('tr').remove();
                                            }
                                        });
                                    }
                                }); 
                                
                            });
        

                            // Se trae el iframe de los reportes
                            $("#iframeReportes_leadReport").attr('src', "<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=green&estrat=<?php echo $_GET['estrategia'];?>&paso="+llaveInvocar);

                            if($('#sec_leadReport.in')[0]) $('a[href$="sec_leadReport"]').click();
                            

                            $("#modalLead").modal();
        
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }else if(invocador == 11){
                    //Esto se ejecuta cuando se hace click en bola de webservice
                    $("#webServiceForm")[0].reset();
                    $('#pasowsId').val(llaveInvocar);
        
                    $.ajax({
                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getwebservice=true',
                        type : 'post',
                        dataType : 'json',
                        data : { pasoId :  llaveInvocar, bd : '<?php echo $str_poblacion; ?>'},
                        success : function(data){
                            console.log(data);
                            data.paso.wsjson === null ? null : validaConRestriccion(JSON.parse(data.paso.wsjson))
                            addCopyToClipBoard()
                            if(data.paso.nombre != null && data.paso.nombre != ''){
                                $('#wsNombre').val(data.paso.nombre);
                            }else{
                                $('#wsNombre').val("WEB_SERVICE_"+llaveInvocar);
                            }
        
                            $("#campoOrigen").val(data.origen);
                            $("#selectWS").val(data.paso.id_Ws).trigger('change');
        
                            // Aqui armo el ejemplo con los datos personalizados
                            if(data.campos){
                                let campos = '';
                                let camposOption = '';
                                let camposLista = `<option value="0">Seleccione</option>`;;
                                let cam = data.campos;
        
                                cam.forEach((element, index) => {
                                    
                                    if(index == cam.length - 1)
                                    campos += `\t\t "${element.id}": "Incluya aqui el ${element.texto}" \n`;
                                    else
                                    campos += `\t\t "${element.id}": "Incluya aqui el ${element.texto}", \n`;
                                    
                                    let llave=element.llave == 1 ? 'selected' : '';
                                    camposOption += `<option value="${element.id}" ${llave}>${element.texto}</option>`;
        
                                    if(element.tipo==6){
                                        camposLista += `<option value="${element.opcion}">${element.texto}</option>`;
                                    }
                                });
                                
                                $("#cllave").html(camposOption);
                                $("#valoresws").val(campos);
                                $("#campOpcion").html(camposLista);
        
                                generarEjemplo();
                            }
        
                            $("#urlWebHook").val('https://<?=$_SERVER["HTTP_HOST"]?>/crm_php/formularios/invocaciones.php?bd=<?=$_GET["poblacion"]?>&paso='+llaveInvocar);

                            // Se trae el iframe de los reportes
                            $("#iframeReportes_ws3").attr('src', "<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=green&estrat=<?php echo $_GET['estrategia'];?>&paso="+llaveInvocar);

                            if($('#sec_ws3.in')[0]) $('a[href$="sec_ws3"]').click();
                            
                            $("#webserviceModal").modal();
                        },
                        beforeSend: function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete: function(){
                            $.unblockUI();
                        }
                        
                    });
                }else if(invocador == 121){
        
                    obtenerBot1(llaveInvocar, category);
        
                }else if(invocador == 12){
        
                    obtenerBot(llaveInvocar, category);
        
                }else if(invocador == 13){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=sal_whatsapp&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso Saliente Whatsapp");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }else if(invocador == 14){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_chat_web&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso comunicación chat web");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            // $.unblockUI();
                        }
                    });
                }else if(invocador == 15){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_chat_whatsapp&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso comunicación chat whatsapp");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            // $.unblockUI();
                        }
                    });
                }else if(invocador == 16){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_chat_facebook&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso comunicación chat facebook messenger");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            // $.unblockUI();
                        }
                    });
                }else if(invocador == 17){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_email_entrante&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso comunicación correo entrante");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            // $.unblockUI();
                        }
                    });
                }else if(invocador == 18){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_sms_entrante&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso comunicación sms entrante");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            // $.unblockUI();
                        }
                    });
                }else if(invocador == 19){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_web_form&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type : 'get',
                        success : function(data){
                            $("#title_estratPasosC").html('<?php echo $str_strategia_edicion.' '; ?>'+'formulario web');
                            $("#divIframePasosCortos").html(data);
                            $("#pasoscortos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }else if(invocador == 20){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_instagram&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso comunicación Instagram");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            // $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            // $.unblockUI();
                        }
                    });
                }else if(invocador == 21){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=cargue_manual&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso cargue manual");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }else if(invocador == 22){
                    // Marcador Robotico
                    $.ajax({
                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?tieneCampana=true',
                        type : 'post',
                        data : { pasoId :  llaveInvocar },
                        success : function(data){
                            if(data != '0'){
                                window.location.href = '<?=base_url?>modulo/ruta/marcadorRobotico/'+llevaCampan+'/<?php echo $str_poblacion; ?>/1';
                            }else{
                                /* no tiene campaña asociada */
                                $("#title_estratCampan").html('<?php echo $mRobot_title___;?>');
                                $("#backoffice").hide();
                                $("#campanasNormales").hide();
                                $("#checkGenerarFromBd").hide();
                                $("#marcadorRoboticoIVR").show();
                                $("#id_estpas_mio").val(llaveInvocar);
                                $("#AddTipoCampan").val(4);
                                $("#crearCampanhasNueva").modal();
                                getIVRList();
                            }
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }else if(invocador == 23){
                    //Ruta Entrante
                    $("#ActivarModal").click();
                    $("#title_cargue").html("Entro");
                    $("#divIframe").html("data: Entro");

                    //Consultar Estrategia
                    let FormEstrategia = new FormData();
                    var IdEstrategia= llaveInvocar;
                    console.log("IdEstrategia: ", IdEstrategia);
                    FormEstrategia.append('IdEstrategia', IdEstrategia);
                    $.ajax({
                        url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/Controller/ConsultaEstrategia.php",
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: FormEstrategia,
                        success: function(php_response) {
                            Respuesta = php_response.msg;
                            if (Respuesta == "Ya Existe"){
                                //Datos Configuracion
                                Datos= php_response.Resultado[0];
                                console.log("Datos: ", Datos);
                                $("#InputNombre").val(Datos[0]);
                                $("#InputNumeroEntrada").val(Datos[1]);
                                CkListaNegra= Datos[2];
                                if(CkListaNegra == "1"){
                                    $("#CheckListaNegra").click();
                                }
                                $("#SelectListaFestivos").val(Datos[3]);
                                $("#InputNumeroLimite").val(Datos[4]);
                                $("#InputPausa").val(Datos[5]);
                                CKGenerarTimbre= Datos[6];
                                if(CKGenerarTimbre == "1"){
                                    $("#CheckGenerarTimbre").click();
                                }
                                $("#IdRutaEntrante").val(Datos[7]);
                                $("#IdFlujograma").val(Datos[8]);
                                //Datos Horario
                                DatosHorario= php_response.Resultado_2[0];
                                console.log("DatosHorario: ", DatosHorario);

                                for (let Posicion = 0; Posicion < DatosHorario.length; Posicion++) {
                                    const Dato = DatosHorario[Posicion];

                                    if((Posicion == "2") && (Dato == "")){
                                        $("#CheckLunes").prop('checked', false);
                                        $("#HoraILunes").prop('disabled', true);
                                        $("#HoraILunes").val("");
                                        $("#HoraFLunes").prop('disabled', true);
                                        $("#HoraFLunes").val("");
                                    }else{
                                        $("#HoraILunes").val(DatosHorario[2]);
                                        $("#HoraFLunes").val(DatosHorario[3]);
                                    }

                                    if((Posicion == "4") && (Dato == "")){
                                        $("#CheckMartes").prop('checked', false);
                                        $("#HoraIMartes").prop('disabled', true);
                                        $("#HoraIMartes").val(" ");
                                        $("#HoraFMartes").prop('disabled', true);
                                        $("#HoraFMartes").val(" ");
                                    }else{
                                        $("#HoraIMartes").val(DatosHorario[4]);
                                        $("#HoraFMartes").val(DatosHorario[5]);
                                    }

                                    if((Posicion == "6") && (Dato == "")){
                                        $("#CheckMiercoles").prop('checked', false);
                                        $("#HoraIMiercoles").prop('disabled', true);
                                        $("#HoraIMiercoles").val("");
                                        $("#HoraFMiercoles").prop('disabled', true);
                                        $("#HoraFMiercoles").val("");
                                    }else{
                                        $("#HoraIMiercoles").val(DatosHorario[6]);
                                        $("#HoraFMiercoles").val(DatosHorario[7]);
                                    }

                                    if((Posicion == "8") && (Dato == "")){
                                        $("#CheckJueves").prop('checked', false);
                                        $("#HoraIJueves").prop('disabled', true);
                                        $("#HoraIJueves").val(" ");
                                        $("#HoraFJueves").prop('disabled', true);
                                        $("#HoraFJueves").val(" ");
                                    }else{
                                        $("#HoraIJueves").val(DatosHorario[8]);
                                        $("#HoraFJueves").val(DatosHorario[9]);
                                    }

                                    if((Posicion == "10") && (Dato == "")){
                                        $("#CheckViernes").prop('checked', false);
                                        $("#HoraIViernes").prop('disabled', true);
                                        $("#HoraIViernes").val(" ");
                                        $("#HoraFViernes").prop('disabled', true);
                                        $("#HoraFViernes").val(" ");
                                    }else{
                                        $("#HoraIViernes").val(DatosHorario[10]);
                                        $("#HoraFViernes").val(DatosHorario[11]);
                                    }

                                    if((Posicion == "12") && (Dato == "")){
                                        $("#CheckSabado").prop('checked', false);
                                        $("#HoraISabado").prop('disabled', true);
                                        $("#HoraISabado").val(" ");
                                        $("#HoraFSabado").prop('disabled', true);
                                        $("#HoraFSabado").val(" ");
                                    }else{
                                        $("#HoraISabado").val(DatosHorario[12]);
                                        $("#HoraFSabado").val(DatosHorario[13]);
                                    }

                                    if((Posicion == "14") && (Dato == "")){
                                        $("#CheckDomingo").prop('checked', false);
                                        $("#HoraIDomingo").prop('disabled', true);
                                        $("#HoraIDomingo").val(" ");
                                        $("#HoraFDomingo").prop('disabled', true);
                                        $("#HoraFDomingo").val(" ");
                                    }else{
                                        $("#HoraIDomingo").val(DatosHorario[14]);
                                        $("#HoraFDomingo").val(DatosHorario[15]);
                                    }

                                    if((Posicion == "12") && (Dato == "")){
                                        $("#CheckFestivos").prop('checked', false);
                                        $("#HoraIFestivos").prop('disabled', true);
                                        $("#HoraIFestivos").val(" ");
                                        $("#HoraFFestivos").prop('disabled', true);
                                        $("#HoraFFestivos").val(" ");
                                    }else{
                                        $("#HoraIFestivos").val(DatosHorario[16]);
                                        $("#HoraFFestivos").val(DatosHorario[17]);
                                    }

                                }
                                

                                //Dentro Horario
                                Tarea= DatosHorario[0];
                                ValorAccion= DatosHorario[1];
                                IdAccion= DatosHorario[20];
                                if((IdAccion == null) || (IdAccion == "")){
                                    IdAccion= "0";
                                }
                                if(Tarea == '2'){
                                    $("#SelectPosiblesTareas").val('Pasar a Una Campaña');
                                    $("#SelectCampanas").val(IdAccion);
                                    $("#divSelectCampanas").show();
                                }else if(Tarea == '3'){
                                    $("#SelectPosiblesTareas").val('Reproducir Grabación');
                                    $("#SelectAudios").val(IdAccion);
                                    $("#divSelectAudios").show();
                                }else if(Tarea == '4'){
                                    $("#SelectPosiblesTareas").val('Pasar a IVR');
                                    $("#SelectIVR").val(IdAccion);
                                    $("#divSelectIVR").show();
                                }else{
                                    $("#SelectPosiblesTareas").val('Número Externo');
                                    $("#InputNumeroExterno").val(ValorAccion);
                                    $("#divSelectPosiblesTareas").show();
                                    $("#divInputNumeroExterno").show();
                                }

                                //Fuera Horario
                                Tarea_FH= DatosHorario[18];
                                ValorAccion_FH= DatosHorario[19];
                                if((ValorAccion_FH == null) || (ValorAccion_FH == "")){
                                    ValorAccion_FH= "0";
                                }
                                if(Tarea_FH == '2'){
                                    $("#FueraHSelectPosiblesTareas").val('Pasar a Una Campaña');
                                    $("#FueraHSelectCampanas").val(ValorAccion_FH);
                                    $("#FueraHdivSelectCampanas").show();
                                }else if(Tarea_FH == '3'){
                                    $("#FueraHSelectPosiblesTareas").val('Reproducir Grabación');
                                    $("#FueraHSelectAudios").val(ValorAccion_FH);
                                    $("#FueraHdivSelectAudios").show();
                                }else if(Tarea_FH == '4'){
                                    $("#FueraHSelectPosiblesTareas").val('Pasar a IVR');
                                    $("#FueraHSelectIVR").val(ValorAccion_FH);
                                    $("#FueraHdivSelectIVR").show();
                                }else{
                                    $("#FueraHSelectPosiblesTareas").val('Número Externo');
                                    $("#FueraHInputNumeroExterno").val(ValorAccion_FH);
                                    $("#FueraHdivInputNumeroExterno").show();
                                    $("#FueraHdivSelectTroncales").show();
                                }
                                
                                $("#IdTareaActual").val(IdAccion);
                                $("#LblTareaActual").text(ValorAccion);
                                $("#IptTareaActual").val(ValorAccion);
                                $("#LblTareaFueraHora").text(ValorAccion_FH);
                                $("#IptTareaFueraHora").val(ValorAccion_FH);
                                $("#divBtnGuardar").prop('hidden', true);
                                $("#divBtnActualizar").prop('hidden', false);
                                
                            }else if (Respuesta == "Nada"){
                                console.log("¡Nueva Ruta Entrante!");
                                var IdFlujograma = php_response.IdFlujograma;
                                $("#IdFlujograma").val(IdFlujograma);
                                
                                $("#InputNombre").val("");
                                $("#InputNumeroEntrada").val("");
                                $("#SelectListaFestivos").val("");
                                $("#InputNumeroLimite").val("");
                                $("#InputPausa").val("");
                                $("#HoraILunes").val("");
                                $("#HoraFLunes").val("");
                                $("#HoraIMartes").val("");
                                $("#HoraFMartes").val("");
                                $("#HoraIMiercoles").val("");
                                $("#HoraFMiercoles").val("");
                                $("#HoraIJueves").val("");
                                $("#HoraFJueves").val("");
                                $("#HoraIViernes").val("");
                                $("#HoraFViernes").val("");
                                $("#HoraISabado").val("");
                                $("#HoraFSabado").val("");
                                $("#HoraIDomingo").val("");
                                $("#HoraFDomingo").val("");
                                $("#HoraIFestivos").val("");
                                $("#HoraFFestivos").val("");
                                $("#IdRutaEntrante").val("");
                                $("#SelectPosiblesTareas").val('Elige Una Opción');
                                $("#CheckListaNegra").prop('checked', false);
                                $("#CheckGenerarTimbre").prop('checked', false);
                                $("#divTareaActual").prop('hidden', true);
                                $("#divTareaFueraHora").prop('hidden', true);
                                $("#divBtnGuardar").prop('hidden', false);
                                $("#divBtnActualizar").prop('hidden', true);
                                $("#divInputNumeroExterno").hide();
                                $("#divSelectTroncales").hide();
                                $("#divSelectCampanas").hide();
                                $("#divSelectAudios").hide();
                                $("#divSelectIVR").hide();

                                $("#HoraISabado").prop('disabled', true);
                                $("#HoraFSabado").prop('disabled', true);
                                $("#HoraIDomingo").prop('disabled', true);
                                $("#HoraFDomingo").prop('disabled', true);
                                $("#HoraIFestivos").prop('disabled', true);
                                $("#HoraFFestivos").prop('disabled', true);
                                HoraPorDefectoLunes();
                                HoraPorDefectoMartes();
                                HoraPorDefectoMiercoles();
                                HoraPorDefectoJueves();
                                HoraPorDefectoViernes();

                            }else if (Respuesta == "Error"){
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error Al Consultar Información!  🤨',
                                    text: 'Por Favor, Contactar Con El Area De Desarrollo...',
                                    confirmButtonColor: '#2892DB'
                                })
                                console.log(php_response.Falla);
                            }
                        },
                        error: function(php_response) {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error Servidor!  😵',
                                text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                                confirmButtonColor: '#2892DB'
                            })
                            php_response = JSON.stringify(php_response);
                            console.log(php_response);
                        }
                    });
                    $("#InputEstpas").val(llaveInvocar);
                    $("#ModalLlamada").modal();
                
                }else if(invocador == 25){
                    //IVRs
                    $("#ModalIVRs").modal();

                    let FormularioE = new FormData();
                    var IdEstrategia= llaveInvocar;
                    //console.log("IdEstrategia: ", IdEstrategia);
                    $("#IdEstrategia").val(IdEstrategia);
                    FormularioE.append('IdEstrategia', IdEstrategia);
                    
                    $.ajax({
                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G11/Controller/ConsutarIVRs.php',
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: FormularioE,
                        success : function(php_response){
                            Respuesta= php_response.msg;
                            if (Respuesta == "Si Existe"){
                                //Datos Configuracion
                                var Datos= php_response.Resultado[0];
                                console.log("Datos: ", Datos);
                                var IdIVR= Datos[9];
                                var NombreIVR= Datos[7];
                                $("#InputOpcionIVR").val(Datos[1]);
                                $("#InputNombreIVR").val(NombreIVR);
                                $("#InputTiempoEspera").val(Datos[6]);
                                $("#IdIVR").val(IdIVR);
                                var IdAudioBienvenida= Datos[2];
                                var AudioBienvenida= Datos[3];
                                var IdAudioDigitos= Datos[4];
                                var AudioDigitos= Datos[5];

                                var Option = $("<option selected>");
                                Option.text(AudioBienvenida);
                                Option.val(IdAudioBienvenida);
                                $("#SelectGrabaBienvenida").append(Option);
                                 
                                var Option2 = $("<option selected>");
                                Option2.text(AudioDigitos);
                                Option2.val(IdAudioDigitos);
                                $("#SelectGrabaDigitos").append(Option2);

                                $("#divBtnGuardarIVRs").prop('hidden', true);
                                $("#divBtnActualizarIVRs").prop('hidden', false);
                                
                                var Titulo= document.getElementById("TtlListaOpciones");
                                Titulo.className = "box-header with-border";
                                //$("#TtlListaOpciones").show();
                                //$("#TablaIVRs").prop('hidden', false);
                                $("#TltIVR").text(NombreIVR+"': ");
                                $("#TtlListaOpciones").hide();

                                $("#SelectAceptarDigitos").val(Datos[10]);
                                $("#IntentosPermitidos").val(Datos[11]);

                                ConsultarDatosFlujograma(IdIVR);
                            
                            }else if (Respuesta == "Nada"){
                                console.log("¡Nuevo IVR!");
                                $("#InputOpcionIVR").val("Inicio IVR");
                                $("#InputNombreIVR").val("");
                                $("#InputTiempoEspera").val("5");
                                $("#SelectGrabaBienvenida").val("Elige Una Opción");
                                $("#SelectGrabaDigitos").val("Elige Una Opción");
                                $("#divBtnGuardarIVRs").prop('hidden', false);
                                $("#divBtnActualizarIVRs").prop('hidden', true);

                                var Titulo= document.getElementById("TtlListaOpciones");
	                            Titulo.className = "box with-border";
                                $("#TtlListaOpciones").hide();
                                $("#TablaIVRs").prop('hidden', true);

                                ConsultarFlujogramaDefecto();

                            }else if (Respuesta == "Error"){
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error Al Consultar Información!  🤨',
                                    text: 'Por Favor, Contactar Con El Area De Desarrollo...',
                                    confirmButtonColor: '#2892DB'
                                })
                                console.log(php_response.Falla);
                            }
                        },
                        error: function(php_response) {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error Servidor!  😵',
                                text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                                confirmButtonColor: '#2892DB'
                            })
                            php_response = JSON.stringify(php_response);
                            console.log(php_response);
                        }

                    });
                    
                }else if(invocador == 26){
                    $.ajax({
                        url: '<?=base_url?>mostrar_popups.php?view=com_sms_entrante_v2&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
                        type: 'get',
                        success : function(data){
                            $("#title_cargue").html("Paso comunicación sms entrante");
                            $("#divIframe").html(data);
                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            // $.unblockUI();
                        }
                    });
                }
            }
        });
    }

    function borrarNode(id){
        alertify.confirm("<?php echo $str_segurida__Ale_; ?>", function (e) {
            //Si la persona acepta
            if (e) {
                alertify.confirm("<?php echo $str_segurida__Al2_; ?>", function (e) {
                    //Si la persona acepta
                    if (e) {
                        $.ajax({
                            url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',
                            type  : 'post',
                            data  : { borrarFlujograma : true, id_paso :  id },
                            success : function(data){
                                console.log(data);
                            }
                        });
                    } else {
                        load();
                    }
                });                 
            } else {
                load();
            }
        });    
    }

    function borrarLink(from , to){
        $.ajax({
            url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',
            type  : 'post',
            data  : { borrarFlujogramaLink : true, from :  from , to : to },
            success : function(data){
                console.log(data);
            }
        });
    }

    function validarEliminarNodo(paso){

        $.ajax({
            url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',
            type  : 'post',
            dataType: 'json',
            data  : { verificarPasoABorrar: true, paso: paso},
            success : function(data){
                
                if(data.estado == 'ok'){

                    if(data.validoAEliminar){
                        borrarNode(paso);
                        console.log("borra el nodo");
                    }else{
                        alertify.warning("No se puede eliminar un paso que este conectado a una flecha estatica");
                        load();
                    }

                }else{
                    alertify.error("Se presento un error al realizar la validacion");
                }
            }
        });

    }

    function mensajeInformativo(pasoDesde = null, pasoHasta = null){

        let mensaje = "Esta flecha solo se pueden borrar o editar desde el paso origen";

        // Debo traer el nombre del paso

        if(pasoHasta){
            if(pasoHasta.tipoPaso == 7){
    
                $.ajax({
                    url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',
                    type  : 'post',
                    dataType: 'json',
                    data  : { obtenerNombrePasoOrigen: true, tipoPaso: pasoHasta.tipoPaso, pasoId: pasoHasta.key},
                    success : function(data){
                        if(data.estado == 'ok' && data.nombrePaso != ''){
                            mensaje =  'Esta flecha no se puede borrar o modificar desde aqui, debes ir al paso de chat web "' + data.nombrePaso + '" y cambiar el check "Enviar historial de chat después de que se realice una tipificación de la comunicación."';
                        }
                        alertify.set({ delay: 15000 });
                        alertify.warning(mensaje);
                    }
                });
                return;
            }
        }

        if(pasoDesde){
            if(pasoDesde.tipoPaso == 12 || pasoDesde.tipoPaso == 14 || pasoDesde.tipoPaso == 15 || pasoDesde.tipoPaso == 16){
    
                mensaje = `Esta flecha solo puede ser modificada o eliminada desde el paso "${pasoDesde.nombrePaso}" en la seccion de acciones`;
                alertify.set({ delay: 12000 });
                alertify.warning(mensaje);
                return;
            }
        }


        alertify.warning(mensaje);
    }

    function verInformacion(from , to){
        /* validamos que el paso principal sea de los que pueden ser saliente o entrante */
        $("#txtCantidadRegistrps").val('');
        $("#divCantidadCampan").hide();
        $("#divFiltrosCampan").hide();
        $("#filtros").html("");
        $("#consultaCamposWhere")[0].reset();
        $("#filtrosCampanha").modal('hide');
        $("#configAutomatica").modal('hide');

        $("#asignacionDeCampana").hide();
        $("#checkInactivarRegistros").hide();
        $("#heredarAgendas").parent().hide();

        let tipoPasoFrom = 0;
        let tipoPasoTo = 0;

        $.ajax({
            url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?validaPasosPrincipales=true',
            type  : 'post',
            dataType: 'json',
            data  : { paso :  from },
            success : function(data){
                if(data.tipo != '0'){
                    Aplica = data.tipo;
                    tipoPasoFrom = data.tipo;
                    campanhasHave(from);
                    /* es una campaña */
                    /* toca validar que las que lleguen si sean las que son */
                    $.ajax({
                        url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?validaPasosSecundarios=true',
                        type  : 'post',
                        dataType: 'json',
                        data  : { paso :  to },
                        success : function(data){
                            if(data.tipo != '0'){
                                $("#id_paso_from").val(from);
                                $("#id_paso_to").val(to);
                                tipoPasoTo = data.tipo;
                                
                                // Aqui valido para ver si muestro la lista normal o la de horarios
                                $.ajax({
                                    url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?validarPasoChat=true',
                                    type  : 'post',
                                    data  : { pasoFrom : from , pasoTo : to },
                                    dataType : 'json',
                                    success : function(data){                                  
                                        console.log(data);

                                        if(data.esPasoChat){
                                            $(".secHorarios").show();
                                            $(".secTraspasoRegistros").hide();
                                            $(".secEmailFiltros").hide();
                                            $(".secConexionBot").hide();

                                            if(data.esDentroHorario && data.esFueraHorario){
                                                $("#tituloHorario").text("Acción de horario completo. ");
                                            }else{
                                                if(data.esDentroHorario){
                                                    $("#tituloHorario").text("Acción dentro de horario. ");
                                                }else if(data.esFueraHorario){
                                                    $("#tituloHorario").text("Acción fuera de horario. ");
                                                }
                                            }

                                            $("#subHorario").html("Si desea modificar estos horarios debe hacerlo desde el paso de chat entrante <strong>"+data.nombrePaso+"</strong>");

                                            //Horarios
                                            if(data.horario.length > 0){

                                                if(data.esDentroHorario && data.esFueraHorario){
                                                    setHorario24();
                                                }else{

                                                    $("#G10_C108").prop('checked', false);  // Check lunes
                                                    $("#G10_C111").prop('checked', false);  // Check martes
                                                    $("#G10_C114").prop('checked', false);  // Check miercoles
                                                    $("#G10_C117").prop('checked', false);  // Check jueves
                                                    $("#G10_C120").prop('checked', false);  // Check viernes
                                                    $("#G10_C123").prop('checked', false);  // Check sabado
                                                    $("#G10_C126").prop('checked', false);  // Check domingo
                                                    $("#G10_C129").prop('checked', false);  // Check festivos

                                                    if(data.esFueraHorario){
                                                        setHorario24();
                                                    }

                                                    data.horario.forEach(element => {
                                                        
                                                        if(element.dia_inicial == 1 && element.dia_final == 1){
    
                                                            $("#G10_C108").prop('checked', true);                                                        

                                                            if(data.esDentroHorario){
                                                                $("#G10_C109").val(element.momento_inicial);
                                                                $("#G10_C110").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial, element.momento_final, "G10_C109", "G10_C110");
                                                            }
                                                            
                                                        }
                                                        
                                                        if(element.dia_inicial == 2 && element.dia_final == 2){

                                                            $("#G10_C111").prop('checked', true);
    
                                                            if(data.esDentroHorario){
                                                                $("#G10_C112").val(element.momento_inicial);
                                                                $("#G10_C113").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial, element.momento_final, "G10_C112", "G10_C113");
                                                            }
        
                                                        }

                                                        if(element.dia_inicial == 3 && element.dia_final == 3){
                                                            if(!$("#G10_C114").is(':checked')){
                                                                $("#G10_C114").prop('checked', true);  
                                                            }
    
                                                            if(data.esDentroHorario){
                                                                $("#G10_C115").val(element.momento_inicial);
                                                                $("#G10_C116").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial,element.momento_final,"G10_C115","G10_C116");
                                                            }
                                                            
                                                        }

                                                        if(element.dia_inicial == 4 && element.dia_final == 4){
                                                            
                                                            $("#G10_C117").prop('checked', true);  
                                                            
                                                            if(data.esDentroHorario){
                                                                $("#G10_C118").val(element.momento_inicial);
                                                                $("#G10_C119").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial,element.momento_final,"G10_C118","G10_C119");
                                                            }
                                                            
                                                        }

                                                        if(element.dia_inicial == 5 && element.dia_final == 5){
                                                            
                                                            $("#G10_C120").prop('checked', true);  
                                                            
                                                            if(data.esDentroHorario){
                                                                $("#G10_C121").val(element.momento_inicial);
                                                                $("#G10_C122").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial,element.momento_final,"G10_C121","G10_C122");
                                                            }
                                                            
                                                        }
                                                        
                                                        if(element.dia_inicial == 6 && element.dia_final == 6){
                                                            
                                                            $("#G10_C123").prop('checked', true);  
                                                            
                                                            if(data.esDentroHorario){
                                                                $("#G10_C124").val(element.momento_inicial);
                                                                $("#G10_C125").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial,element.momento_final,"G10_C124","G10_C125");
                                                            }
                                                            
                                                        }
                                                        
                                                        if(element.dia_inicial == 7 && element.dia_final == 7){
                                                            
                                                            $("#G10_C126").prop('checked', true);  
                                                            
                                                            if(data.esDentroHorario){
                                                                $("#G10_C127").val(element.momento_inicial);
                                                                $("#G10_C128").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial,element.momento_final,"G10_C127","G10_C128");
                                                            }
                                                            
                                                        }
                                                        
                                                        if(element.dia_inicial == 8 && element.dia_final == 8){
                                                            
                                                            $("#G10_C129").prop('checked', true);  
                                                            
                                                            if(data.esDentroHorario){
                                                                $("#G10_C130").val(element.momento_inicial);
                                                                $("#G10_C131").val(element.momento_final); 
                                                            }else if(data.esFueraHorario){
                                                                formaterFueraHorario(element.momento_inicial,element.momento_final,"G10_C130","G10_C131");
                                                            }
                                                            
                                                        }

                                                    });
                                                    
                                                }

                                            }

                                            inicializarTimePicker();
                                            soloLectura();
                                        }else{

                                            // Oculto las secciones
                                            $(".secHorarios").hide();
                                            $(".secTraspasoRegistros").hide();
                                            $(".secEmailFiltros").hide();
                                            $(".secConexionPasosSms").hide();
                                            $("#btnSaveFiltrosEmailEntrante").hide();
                                            $(".secConexionBot").hide();

                                            $("#btnSaveFiltrosEmailEntrante").prop('disabled', false);

                                            // Traigo el tipo_paso para validar si el paso from en 17 o correo entrante
                                            if(data.tipoPasoFrom == 17){

                                                $(".secEmailFiltros").show();
                                                $("#correoCondiciones tbody").html('');
                                                $("#btnSaveFiltrosEmailEntrante").show();
                                                $("#btnSaveFiltros").hide();
                                                $("#btnSaveFiltrosEmailEntrante").prop('disabled', false);
                                                cantFiltrosEmail = 0;

                                                // Traigo los datos
                                                $.ajax({
                                                    url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G31/G31_CRUD.php?getFiltros=true',
                                                    type  : 'post',
                                                    data  : { pasoFrom : from , pasoTo : to },
                                                    dataType : 'json',
                                                    success: function(data){
                                                        if(data.estado && data.estado == 'ok'){

                                                            let titulo = "Filtros del paso <strong>"+data.configCorreo.nombre+"</strong> configurado con la cuenta de correo <strong>"+data.canalCorreo.direccion_correo_electronico+"<strong>";
                                                            $("#tituloHorarioFiltros").html(titulo);

                                                            if(data.filtros.length > 0){
                                                                // Los filtros los muestro aqui
                                                                if(data.filtros.length > 0){
                                                                    $.each(data.filtros, function(index, value){
                                                                        agregarFila('edit');
                                                                        $("#campo_"+index).val(value.id);
                                                                        $("#mailTipoCondicion_"+index).val(value.filtro);
                                                                        
                                                                        if(value.filtro != 100){
                                                                            if(value.condicion == ''){
                                                                                $("#mailCondicion_"+index).val('null');
                                                                            }else{
                                                                                $("#mailCondicion_"+index).val(value.condicion);
                                                                            }
                                                                        }
    
                                                                        $("#mailTipoCondicion_"+index).change();
                                                                    });
                                                                }else{
                                                                    agregarFila('add');
                                                                }
                                                            }else{
                                                                agregarFila('add');
                                                            }

                                                            // flecha
                                                            if(data.flecha && data.flecha.activo == "-1"){
                                                                $("#estconActivo").click();
                                                            }
                                                            
                                                        }else if(data.estado && data.estado == 'fallo'){
                                                            $("#tituloHorarioFiltros").html(data.mensaje);
                                                            $("#btnSaveFiltrosEmailEntrante").prop('disabled', true);
                                                        }
                                                    },
                                                    error: function(data){
                                                        if(data.responseText){
                                                            alertify.error("Hubo un error al guardar la información" + data.responseText);
                                                        }else{
                                                            alertify.error("Hubo un error al guardar la información");
                                                        }
                                                    }
                                                });

                                            }else if(data.tipoPasoFrom == 8){
                                                $("#btnSaveFiltros").hide();
                                                $(".secConexionPasosSms").show();
                                            // Esto lo comente porque puede ser que desde un bot puedan salir flechas a ciertos lugares    
                                            // }else if(data.tipoPasoFrom == 12){
                                            //     $("#btnSaveFiltros").hide();
                                            //     $(".secConexionBot").show();
                                            //     $("#accionesDisparanBot tbody").html('');
                                            //     // Traigo los datos
                                            //     $.ajax({
                                            //         url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G26/G26_CRUD.php?getBotFiltros=true',
                                            //         type  : 'post',
                                            //         data  : { pasoFrom : from , pasoTo : to },
                                            //         dataType : 'json',
                                            //         success: function(data){
                                            //             if(data.estado && data.estado == 'ok'){

                                            //                 //Los filtros los muestro aqui
                                            //                 $.each(data.filtrosBot, function(index, value){
                                                                
                                            //                     let row = `
                                            //                         <tr>
                                            //                             <td>${value.tags}</td>
                                            //                             <td>${value.respuesta}</td>
                                            //                         </tr>
                                            //                     `;

                                            //                     $("#accionesDisparanBot tbody").append(row);
                                                                
                                            //                 });
                                            //             }else if(data.estado && data.estado == 'fallo'){
                                            //                 $("#tituloHorarioFiltros").html(data.mensaje);
                                            //                 $("#btnSaveFiltrosEmailEntrante").prop('disabled', true);
                                            //             }
                                            //         },
                                            //         error: function(data){
                                            //             if(data.responseText){
                                            //                 alertify.error("Hubo un error al mostrar la información" + data.responseText);
                                            //             }else{
                                            //                 alertify.error("Hubo un error al mostrar la información");
                                            //             }
                                            //         }
                                            //     });

                                            }else{
                                                // Configuracion por defecto de un paso    
                                                $("#btnSaveFiltros").show();
                                                $(".secTraspasoRegistros").show();
                                                $("#btnSaveFiltrosEmailEntrante").prop('disabled', true);

                                                // Reseteo estos valores aca(son para los sms, NO BORRAR)
                                                $("#esSmsEntrante").val(0);
                                                $("#valorPregunSms").val(0);
    
                                                // Esto obtine los campos de la seccion de cuando realizar la actividad
                                                $.ajax({
                                                    url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getInserciones=true',
                                                    type  : 'post',
                                                    data  : { pasoFrom : from , pasoTo : to },
                                                    dataType : 'json',
                                                    success : function(data){
                                                        if(data != '0'){

                                                            // Cargo la radiocondicion por defecto
                                                            $('#radiocondiciones3').click();

                                                            $.each(data , function(i , item){
                                                                $("#cmbTipoInsercion").val(item.ESTCON_Tipo_Insercion_b);
                                                                $("#cmbTipoInsercion").val(item.ESTCON_Tipo_Insercion_b).change();
    
                                                                $("#cmbCampoFecha").val(item.ESTCON_ConsInte_PREGUN_Fecha_b);
                                                                $("#cmbCampoFecha").val(item.ESTCON_ConsInte_PREGUN_Fecha_b).change();
    
                                                                $("#cmbCampoHora").val(item.ESTCON_ConsInte_PREGUN_Hora_b);
                                                                $("#cmbCampoHora").val(item.ESTCON_ConsInte_PREGUN_Hora_b).change();
    
    
                                                                $("#masMenosFecha").val(item.ESTCON_Operacion_Fecha_b);
                                                                $("#masMenosFecha").val(item.ESTCON_Operacion_Fecha_b).change();
    
                                                                $("#masMenosHora").val(item.ESTCON_Operacion_Hora_b);
                                                                $("#masMenosHora").val(item.ESTCON_Operacion_Hora_b).change();
    
    
                                                                $("#txtRestaSumaFecha").val(item.ESTCON_Cantidad_Fecha_b);
                                                                $("#txtRestaSumaHora").val(item.ESTCON_Cantidad_Hora_b);
    
                                                                $("#cmbCambioEstado").val(item.ESTCON_Estado_cambio_b);
                                                                $("#cmbCambioEstado").val(item.ESTCON_Estado_cambio_b).change();
    
                                                                // Campos de avanzado
                                                                if(item.ESTCON_Sacar_paso_anterior_b != null && item.ESTCON_Sacar_paso_anterior_b != 0){
                                                                    $("#sacarPasoAnterior").prop("checked", true);
                                                                }else{
                                                                    $("#sacarPasoAnterior").prop("checked", false);
                                                                }
    
                                                                if(item.ESTCON_resucitar_registro != null && item.ESTCON_resucitar_registro != 0){
                                                                    $("#resucitarRegistros").prop("checked", true);
                                                                }else{
                                                                    $("#resucitarRegistros").prop("checked", false);
                                                                }
                                                                
                                                                if(item.ESTCON_Hereda_MONOEF_b != null && item.ESTCON_Hereda_MONOEF_b != 0){
                                                                    $("#heredarAgendas").prop("checked", true);
                                                                    $("#cmbTipoInsercion").val('0').trigger('change');
                                                                    $("#s_1").hide();
                                                                }else{
                                                                    $("#heredarAgendas").prop("checked", false);
                                                                    $("#s_1").show();
                                                                }

                                                                if(item.ESTCON_Activo_b == '-1'){
                                                                    $("#estconActivo").prop("checked", true);
                                                                }else{
                                                                    $("#estconActivo").prop("checked", false);
                                                                }

                                                                if(item.ESTCON_Tipo_Asignacion_b == '1'){
                                                                    $('#tipoAsignacionAutomatica').click();
                                                                }else{
                                                                    $('#tipoAsignacionPredefinida').click();
                                                                    cambioTipoAsignacionCampana();
                                                                }

                                                                if(item.ESTCON_Campo_Agente_Asignacion_b != 0){
                                                                    $("#campoAgente").val(item.ESTCON_Campo_Agente_Asignacion_b);
                                                                }

                                                                if(item.ESTCON_Inactivar_Registros_b != null && item.ESTCON_Inactivar_Registros_b != 0){
                                                                    $("#inactivarRegistros").prop("checked", true);
                                                                }else{
                                                                    $("#inactivarRegistros").prop("checked", false);
                                                                }

                                                                if(item.ESTCON_Sacar_Otros_Pasos_b == '-1'){
                                                                    $("#sacarOtrosPasos").prop("checked", true);
                                                                    $("#pasosSacar").attr('disabled', false);

                                                                    let pasos = item.ESTCON_Sacar_Otros_Pasos_Ids_b.split(',');

                                                                    // Obtenemos la lista de pasos a excluir
                                                                    obtenerPasosExcluir(from, to, pasos);

                                                                }else{
                                                                    obtenerPasosExcluir(from, to, null);
                                                                    $("#sacarOtrosPasos").prop("checked", false);
                                                                    $("#pasosSacar").attr('disabled', true);
                                                                }

                                                                // Registros a los que aplica
                                                                if(item.ESTCON_Tipo_Consulta_b == '1'){
                                                                    $('#radiocondiciones1').click();
                                                                }else if(item.ESTCON_Tipo_Consulta_b == '2'){
                                                                    $('#radiocondiciones2').click();
                                                                }else if(item.ESTCON_Tipo_Consulta_b == '3'){
                                                                    $('#radiocondiciones3').click();
                                                                }else if(item.ESTCON_Tipo_Consulta_b == '4'){
                                                                    $('#radiocondiciones4').click();
                                                                }else{
                                                                    $('#radiocondiciones3').click();
                                                                }
                                                            });
                                                        } else {
                                                            // datos por defecto cuando el flujo es nuevo
                                                            $("#resucitarRegistros").prop("checked", true);

                                                        }
                                                    }
                                                });
    
                                                /* aqui toca validar q tenga pasos en el otro lado */
                                                $.ajax({
                                                    url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?validarPasosCondiciones=true',
                                                    type  : 'post',
                                                    data  : { pasoFrom : from , pasoTo : to },
                                                    dataType : 'json',
                                
                                                    success : function(data){
                                                        if(data != '0'){
                                                            $.each(data , function(i , item){
    
                                                                if(item.ESTCON_Tipo_Consulta_b == '1'){
                                                                    $('#radiocondiciones1').click();
                                                                }else if(item.ESTCON_Tipo_Consulta_b == '2'){
                                                                    $('#radiocondiciones2').click();
                                                                }else if(item.ESTCON_Tipo_Consulta_b == '3'){
                                                                    $('#radiocondiciones3').click();
                                                                }else if(item.ESTCON_Tipo_Consulta_b == '4'){
                                                                    $('#radiocondiciones4').click();
                                                                }
                                                            
    
                                                                $("#newFiltro").click();
    
                                                                var newnew = newCuantosvan -1;
    
    
                                                                $("#pregun_"+newnew).attr('EstaEs',  item.valor);
                                                                $("#pregun_"+newnew).val(item.campo);
                                                                $("#pregun_"+newnew).val(item.campo).change();
    
                                                                $("#condicion_"+newnew).val(item.condicion);
                                                                $("#condicion_"+newnew).val(item.condicion).change();
    
                                                                $("#valor_"+newnew).val(item.valor);
                                                                $("#valor_"+newnew).val(item.valor).change();
    
                                                                if(item.separador != null && item.separador != ''){
                                                                    // Valido si el separador contiene un where o no 
                                                                    if(item.separador == ' WHERE '){
                                                                        $("#condiciones_"+newnew).val(' ');
                                                                    }else if(item.separador == ' WHERE ( '){
                                                                        $("#condiciones_"+newnew).val('(');
                                                                    }else{
                                                                        $("#condiciones_"+newnew).val(item.separador);
                                                                    }

                                                                    $("#condiciones_"+newnew).change();
                                                                }
                                                                
                                                                // Agrego las etiquetas de cierre
                                                                $("#cierre"+newnew).val(item.separador_final);
                                                            });

                                                            // Ejecuto el renderizado de las condiciones
                                                            renderizarCondiciones();
                                                        }
                                                        $("#filtrosCampanha").modal();                                          
                                                        
                                                    },
                                                    complete : function(){
                                                        $.unblockUI();
                                                    }
                                                });

                                                if(data.tipoPasoFrom == 18){
                                                    // Busco el campo de smsEntrante configurado previamente
                                                    $.ajax({
                                                    url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G32/G32_CRUD.php?traerConfiguracion=true',
                                                    type  : 'post',
                                                    data  : { pasoFrom : from},
                                                    dataType : 'json',
                                
                                                    success : function(data){
                                                        // Creo una variable que me permita decir si viene desde smsEntrante

                                                        if(data.smsSaliente){

                                                            if(data.smsSaliente.esperarRespuesta && data.smsSaliente.esperarRespuesta == -1){
                                                                $("#esSmsEntrante").val(1);
                                                                $("#valorPregunSms").val(data.smsSaliente.pregun);
                                                            }
                                                        }
                                                    },
                                                    error: function(data){
                                                        console.log(data);
                                                    },
                                                    complete : function(){
                                                        $.unblockUI();
                                                    }
                                                });
                                                }

                                                if(tipoPasoFrom == 5 && tipoPasoTo == 6){
                                                    // Mostramos el check
                                                    $("#checkInactivarRegistros").show();

                                                    // Buscamos que tipo de asignacion es la campaña
                                                    $.ajax({
                                                        url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?obtenerDatosCampana=true',
                                                        type  : 'post',
                                                        data  : { pasoTo : to, bd : '<?php echo $str_poblacion; ?>'},
                                                        dataType : 'json',

                                                        success : function(data){
                                                            
                                                            if(data.estado == "ok"){
                                                                if(data.campana.tipo_distribucion == 0){
                                                                    $("#asignacionDeCampana").show();
                                                                }
                                                            }

                                                            if(data.estado == "fallo"){
                                                                alertify("Hubo un error al consultar los datos de la campaña");
                                                            }

                                                        },
                                                        error: function(data){
                                                            console.log(data);
                                                        },
                                                        complete : function(){
                                                            $.unblockUI();
                                                        }
                                                    });
                                                }

                                                if((tipoPasoFrom == 1 || tipoPasoFrom == 6) && tipoPasoTo == 6){
                                                    $("#heredarAgendas").parent().show();
                                                }
                                            }

                                        }
                                        $("#filtrosCampanha").modal();
                                    },
                                    complete : function(){
                                        $.unblockUI();
                                    }
                                });
                            }
                        },
                        complete : function(){

                        }
                    });                                     
                }else{
                    $.unblockUI();
                }
            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_mesa_adyacente;?>' });
            }
        });
    }

    function campanhasHave(from){
        $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?tieneCampana=true',
            type : 'post',
            data : { pasoId :  from },
            success : function(data){
                if(data != '0'){
                    $("#id_campanaFiltros").val(data);
                }
            }
        });
    }

    function getDatosBackoffice(llaveInvocar){
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?verificarTareasBackoffice=true',
            type : 'POST',
            data : {key : llaveInvocar, guion: <?php echo $str_poblacion; ?>},
            dataType : 'json',
            success: function(response){

                $("#casoBackofficeForm")[0].reset();
                $('#casoBackofficeForm #tipoAccion').val(response.accion);
                $('#casoBackofficeForm #idEstpas').val(llaveInvocar);
                $('#casoBackofficeForm #idGuion').val(<?php echo $str_poblacion; ?>);

                if(response.accion == "nuevo"){
                    $('#nombreCaso').val(response.nombrepaso);
                    $('#pasoActivo').prop('checked','checked');
                    $('#casoBackofficeModal .modal-title').text('Crear tarea de BackOffice');
                    $('#casoBackofficeForm .camposDeEdicion, #btnAdministrarRegistrosBackoffice, #report_back_box ').hide();
                    $('#casoBackofficeForm #idTareaBack').val("0");

                }else if(response.accion == 'editar'){

                    $('#casoBackofficeModal .modal-title').text('Editar tarea de BackOffice');
                    $('#casoBackofficeForm #idTareaBack').val(response.respuesta[0].TAREAS_BACKOFFICE_ConsInte__b);
                    $('#nombreCaso').val(response.respuesta[0].TAREAS_BACKOFFICE_Nombre_b);
                    $('#tipoDistribucionTrabajo').val(response.respuesta[0].TAREAS_BACKOFFICE_TipoDistribucionTrabajo_b).change();

                    $('#casoBackofficeForm .camposDeEdicion').show();
                    $('#pregun').html(response.pregunOption);
                    $('#lisopc').html(response.lisopcOption);
                    
                    if(response.respuesta[0].ESTPAS_activo == 1){
                        $('#pasoActivo').prop('checked','checked');
                    }else{
                        $('#pasoActivo').prop('checked',false);
                    }

                    if(response.respuesta[0].TAREAS_BACKOFFICE_TipoDistribucionTrabajo_b == 2){
                        $('#pregun').val(response.respuesta[0].TAREAS_BACKOFFICE_ConsInte__PREGUN_estado_b);
                        $('#lisopc').val(response.respuesta[0].TAREAS_BACKOFFICE_ConsInte__LISOPC_estado_b);
                    }

                    $('#disponible').html(response.listaUsu);
                    $('#seleccionado').html(response.listaUsuA);
                    
                    

                }
                $('input[type="checkbox"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-blue'
                });

                // Se trae el iframe de los reportes
                $("#iframeReportes_back").attr('src', "<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=bkpaso&estrat=<?php echo $_GET['estrategia'];?>&paso="+llaveInvocar);

                if($('#iframeReportes_back.in')[0]) $('a[href$="report_back"]').click();


                var esVisible = $("#casoBackofficeModal").is(":visible");
 
                if(!esVisible){
                    $("#casoBackofficeModal").modal('show');
                }
                
            },
            error: function(response){
                console.log(response);
            },
            beforeSend : function(){
                $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            }

        });
    }

    function setHorario24(){
        $("#G10_C109").val("00:00:01");
        $("#G10_C112").val("00:00:01");
        $("#G10_C115").val("00:00:01");
        $("#G10_C118").val("00:00:01");
        $("#G10_C121").val("00:00:01");
        $("#G10_C124").val("00:00:01");
        $("#G10_C127").val("00:00:01");
        $("#G10_C130").val("00:00:01");

        $("#G10_C110").val("23:59:59");
        $("#G10_C113").val("23:59:59");
        $("#G10_C116").val("23:59:59");
        $("#G10_C119").val("23:59:59");
        $("#G10_C122").val("23:59:59");
        $("#G10_C125").val("23:59:59");
        $("#G10_C128").val("23:59:59");
        $("#G10_C131").val("23:59:59");

        $('#sec_horario :checkbox[readonly=readonly]').prop('checked','checked');
    }

    function inicializarTimePicker(){
        //Timepickers
        $("#G10_C109").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C110").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C112").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C113").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C115").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C116").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C118").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C119").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C121").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C122").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C124").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C125").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C127").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C128").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C130").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#G10_C131").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:10:00',
            'maxTime': '23:59:59',
            'setTime': '17:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $(':checkbox[readonly=readonly]').click(function(){
            return false;         
        }); 
    }

    function soloLectura(){
        $("#G10_C109").prop("disabled", true);
        $("#G10_C112").prop("disabled", true);
        $("#G10_C115").prop("disabled", true);
        $("#G10_C118").prop("disabled", true);
        $("#G10_C121").prop("disabled", true);
        $("#G10_C124").prop("disabled", true);
        $("#G10_C127").prop("disabled", true);
        $("#G10_C130").prop("disabled", true);

        $("#G10_C110").prop("disabled", true);
        $("#G10_C113").prop("disabled", true);
        $("#G10_C116").prop("disabled", true);
        $("#G10_C119").prop("disabled", true);
        $("#G10_C122").prop("disabled", true);
        $("#G10_C125").prop("disabled", true);
        $("#G10_C128").prop("disabled", true);
        $("#G10_C131").prop("disabled", true);
    }

    function formaterFueraHorario(horaInicial, horaFinal, campoInicial, campoFinal){
        let inicial = moment(horaFinal, 'hh:mm:ss');
        let final = moment(horaInicial, 'hh:mm:ss');

        inicial.add(1, 'seconds');
        final.subtract(1, 'seconds');
        
        $("#"+campoInicial).val(inicial.format('HH:mm:ss'));
        $("#"+campoFinal).val(final.format('HH:mm:ss'));
    }
</script>

<!-- funciones para manipular la creacion de campañas -->
<script type="text/javascript">
    $(function(){
        var ch = 0;
        $('.fromBAse').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $("#G10_C73").select2({
            dropdownParent: $("#crearCampanhasNueva")
        });

        $('#generarFromDB').on('ifChecked', function () { 
            ch = 1;
            $("#G10_C73").attr('disabled', true);
            $("#G10_C73B").attr('disabled', true);
        });


        $('#generarFromDB').on('ifUnchecked', function () { 
            ch = 0;
            $("#G10_C73").attr('disabled', false);
            $("#G10_C73B").attr('disabled', false);
        });





        $("#btnSaveCampan").click(function(){
            var valido = 0;
            if( $("#G10_C72").val() < 1){
                valido = 1;
                alertify.error("<?php echo $campan_error_s_;?>");
            }
            
            if( $("#G10_C71").val() == ''){
                valido = 1;
                alertify.error("El campo nombre es obligatorio");
            }
            
            if(ch == 0){
                if($("#campanasNormales").is(":visible")){
                    if( $("#G10_C73").val() == 0){
                        valido = 1;
                        alertify.error("<?php echo $campan_error_s_;?>");
                    }
                }
                

                if($("#backoffice").is(":visible")){
                    if( $("#G10_C73B").val() == 0){
                        valido = 1;
                        alertify.error("<?php echo $campan_error_s_;?>");
                    }
                }

                if($('#marcadorRoboticoIVR').is(":visible")){     
                    if( $("#G10_C90").val() == 0){
                        valido = 1;
                        alertify.error("<?php echo $campan_error_I_;?>");
                    }
                
                }

            }



            if(valido == 0){
                /* ahora toca meter la campaña de una */
                var formData = new FormData($("#formuarioCargarEstoEstrart")[0]);
                formData.append('oper', 'add');
                var url_campan = '';
                var url_destino = '';    
                let numberG = "G10";
                let extraParameters = '';
                if($('#AddTipoCampan').val() == 2){     
                    url_campan = 'G10_CRUD.php';
                    url_destino = 'campan';   
                    
                }else if($('#AddTipoCampan').val() == 1){     
                    url_campan = 'G10_CRUD_v2.php';
                    url_destino = 'entrantes';

                }else if($('#AddTipoCampan').val() == 3){     
                    url_campan = 'G10_CRUD_v3.php';
                    url_destino = 'backoffice';
                
                }else if($('#AddTipoCampan').val() == 4){     
                    numberG = "G36";
                    url_campan = 'G36_CRUD.php';
                    url_destino = 'marcadorRobotico';
                    extraParameters = '&newMarcador=si';
                
                }

                $.ajax({
                   url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/'+numberG+'/'+url_campan+'?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(datass){
                        if(datass){
                            $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?hayCampana=true',
                                type : 'post',
                                data : { pasoId :  $("#id_estpas_mio").val() },
                                success : function(data){

                                    // Generamos la vista de una vez

                                    $.ajax({
                                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaMuestra=si',
                                        type: 'POST',
                                        data: { pasoId :  $("#id_estpas_mio").val() },
                                        dataType:'JSON'
                                    });

                                    // Si es una campaña saliente generamos el ACD
                                    if($('#AddTipoCampan').val() == 1){
                                        $.ajax({
                                        url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaACD=si',
                                        type: 'POST',
                                        data: { pasoId :  $("#id_estpas_mio").val() },
                                        dataType:'JSON'
                                    });
                                    }

                                    window.location.href = '<?=base_url?>modulo/ruta/'+url_destino+'/'+data+'/<?php echo $str_poblacion; ?>/1'+extraParameters;
                                }
                            });
                        }else{
                            $.unblockUI();
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    }
                });
            }
        });
    });
</script>

<!-- Funcionalidad del validador de condiciones Datos-->
<script type="text/javascript">
    var totalFiltros = 0;

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

        for (let index = 0; index < newCuantosvan; index++) {
            
            if($("#filtros div#id_"+index).length){

                let apertura = '<b>'+$("#condiciones_"+index+" option:selected").html()+'</b>';
                let pregunta = $("#pregun_"+index+" option:selected").html();
                let condicion = '<i>'+$("#condicion_"+index+" option:selected").text()+'</i>';
                let cierre = '<b>'+$("#cierre"+index).val()+'</b>';
                let valor = '';

                // Para el valor tengo que validar la tipo de pregunta
                let tipoPregunta = $("#pregun_"+index+" option:selected").attr('tipo');
                if(tipoPregunta == 'ListaCla' || tipoPregunta == '8' || tipoPregunta == '_Activo____b' || tipoPregunta == '_Estado____b' || tipoPregunta == '6' || tipoPregunta == '11' || tipoPregunta == '_ConIntUsu_b' || tipoPregunta == 'MONOEF' || tipoPregunta == '_CanalUG_b'){
                    valor = $("#valor_"+index+" option:selected").html();
                }else{
                    valor = $("#valor_"+index).val();
                }

                console.log(apertura, pregunta, condicion, valor, cierre);
                
                texto += ` ${apertura} ${pregunta} ${condicion} ${valor} ${cierre} `;

            }
            
        }

        $("#textoPrevisualizacion").html('Condicion cuando ' + texto);

        if(!ValidarParentesis(texto)){
            $("#errorCondiciones").html('Los parentesis estan mal configurados, hay mas abiertos o cerrados');
        }else{
            $("#errorCondiciones").html('');
        }
        
    }

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

        $(".miSelectPregun").each((indexPregun, elementPregun) => {
            let idNumeroPregun = $(elementPregun).attr("numero");
            // Si en los filtros hay un campo repetido por defecto ponemos en la condicion un OR
            if(idNumeroPregun != numero && numero != 1){
                if($(elementPregun).val() == idPregun){
                    $("#condiciones_"+numero).val(" OR ").change();
                }
            }

        })
      }

    function obtenerPasosExcluir(from, to, pasos){

        // Se obtienen los pasos a excluir
        $.ajax({
            url   : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getPasosExcluir=true',
            type    : 'post',
            data  : { pasoFrom : from , pasoTo : to },
            dataType : 'json',
            success : function(data){
                if(data.pasos && data.pasos.length > 0){

                    let opciones = '';

                    data.pasos.forEach(element => {
                        opciones += `<option value="${element.id}">${element.nombre}</option>`;
                    });

                    $("#pasosSacar").html(opciones);
                    
                    if(pasos){
                        $("#pasosSacar").val(pasos).trigger('change');
                    }
                }
            }
        });

    }

    $(function(){

        // $("#filtros").on('change', 'select', renderizarCondiciones);
        $("#filtros").on('input', ':input', renderizarCondiciones);

        $.fn.datepicker.dates['es'] = {
            days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
            daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            today: "Today",
            clear: "Clear",
            format: "yyyy-mm-dd",
            titleFormat: "yyyy-mm-dd", 
            weekStart: 0
        };

       
        $("#newFiltro").click(function(){
            var colunna1 = 2;
            var colunna2 = 4;
            var cuerpo = "<div class='row' id='id_"+newCuantosvan+"'>";
            let idActualRegistro = newCuantosvan; // Este valor lo necesito para el filtro de sms

            let condicionesActuales = $("#filtros").find('div.row').length;
        
            if(condicionesActuales == 0){
                cuerpo += `
                    <div class="col-md-1">
                        <select class="form-control condApertura" name="condiciones_${newCuantosvan}" id="condiciones_${newCuantosvan}" onchange="cambioEtiquetaApertura()" numero="${newCuantosvan}">
                            <option value=" "></option>
                            <option value="(">&#40</option>
                        </select>
                    </div>
                `;
                cuerpo += "<div class='col-md-4'>";
                colunna1 = 2;
                colunna2 = 3;
            }else{
                cuerpo += "<div class='col-md-1'>";
                cuerpo += "<div class='form-group'>";
                cuerpo += "<select class='form-control condiciones condApertura' name='condiciones_"+newCuantosvan+"'  id='condiciones_"+newCuantosvan+"' numero='"+newCuantosvan+"' onchange='cambioEtiquetaApertura()'>";
                cuerpo += "<option value=' AND '><?php echo $str_Filtro_AND__________;?></option>";
                cuerpo += "<option value=' AND ('><?php echo $str_Filtro_AND__________;?> &#40</option>";
                cuerpo += "<option value=' OR '><?php echo $str_Filtro_OR___________;?></option>";
                cuerpo += "<option value=' OR ('><?php echo $str_Filtro_OR___________;?> &#40</option>";
                cuerpo += "</select>";
                cuerpo += "</div>";
                cuerpo += "</div>";
                cuerpo += "<div class='col-md-4'>";
                colunna1 = 2;
                colunna2 = 3;
            }
            

            cuerpo += "<div class='form-group'>";
            cuerpo += "<select class='form-control miSelectPregun' name='pregun_"+newCuantosvan+"'  id='pregun_"+newCuantosvan+"' numero='"+newCuantosvan+"'>";
            cuerpo += "<option value='0'><?php echo $filtros_campos_c; ?></option>";
            cuerpo += "<option value='OrigenUltimoCargue' tipo='1' lista='0' >ORIGEN_ULTIMO_CARGUE_DY</option>";
            $.each(datosGuion, function(i, item) {
                cuerpo += "<option value='"+item.PREGUN_ConsInte__b+"' tipo='"+item.PREGUN_Tipo______b+"' lista='"+item.PREGUN_ConsInte__OPCION_B+"' >"+ item.PREGUN_Texto_____b+"</option>";
            }); 
            //console.log(Aplica);
            if(Aplica == '1' || Aplica == '6'){
                cuerpo += "<option value='_Estado____b' tipo='_Estado____b'><?php echo $muestra_traduc_1; ?></option>";
                cuerpo += "<option value='_ConIntUsu_b' tipo='_ConIntUsu_b'><?php echo $muestra_traduc_2; ?></option>";
                cuerpo += "<option value='_NumeInte__b' tipo='_NumeInte__b'><?php echo $muestra_traduc_3; ?></option>";
                cuerpo += "<option value='_UltiGest__b' tipo='MONOEF'><?php echo $muestra_traduc_4; ?></option>";
                cuerpo += "<option value='_GesMasImp_b' tipo='MONOEF'><?php echo $muestra_traduc_5; ?></option>";
                cuerpo += "<option value='_FecUltGes_b' tipo='_FecUltGes_b'><?php echo $muestra_traduc_6; ?></option>";
                cuerpo += "<option value='_FeGeMaIm__b' tipo='_FeGeMaIm__b'><?php echo $muestra_traduc_7; ?></option>";
                cuerpo += "<option value='_ConUltGes_b' tipo='ListaCla'><?php echo $muestra_traduc_8; ?></option>";
                cuerpo += "<option value='_CoGesMaIm_b' tipo='ListaCla'><?php echo $muestra_traduc_9; ?></option>";
                cuerpo += "<option value='_Activo____b' tipo='_Activo____b'><?php echo $muestra_traduc_10; ?></option>";
                cuerpo += "<option value='_CanalUG_b' tipo='_CanalUG_b'>Canal</option>";
            }
            

            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "<div class='col-md-"+colunna1+"'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select name='dyTr_condicion_"+newCuantosvan+"' id='condicion_"+newCuantosvan+"' class='form-control'>";
            cuerpo += "<option value='0'><?php echo $filtros_condic_c; ?></option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "<div class='col-md-"+colunna2+"' id='valoresRestableses_"+ newCuantosvan +"' >";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text'  name='valor_"+newCuantosvan+"' id='valor_"+newCuantosvan+"' class='form-control' placeholder='<?php echo $filtros_valor__c; ?>'>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += `
                <div class="col-md-1">
                    <select class="form-control condCierre" name="cierre${newCuantosvan}" id="cierre${newCuantosvan}">
                        <option value=""></option>
                    </select>
                </div>
            `;
            cuerpo += "<div class='col-md-1'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<button class='btn btn-danger btn-sm deleteFiltro' id='"+newCuantosvan+"' numeroFila='"+newCuantosvan+"' title='<?php echo $str_opcion_elimina;?>' type='button'><i class='fa fa-trash-o'></i></button>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";


            newCuantosvan++;
            totalFiltros++;
            contador2++;

            $("#filtros").append(cuerpo);

            // realizo el calculo de las etiquetas de apertura y cierre
            cambioEtiquetaApertura();            

            $(".miSelectPregun").change(function(){
                var id = $(this).attr('numero');
                var tipo = $("#pregun_"+id+" option:selected").attr('tipo');
                var estaEs = null;
                estaEs = $(this).attr('EstaEs');
                
                validarAperturaPorDefecto($(this).val(), id );

                var options = "";
                cuerpo = "<div class='form-group'>";
                cuerpo += "<input type='text'  name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='<?php echo $filtros_valor__c; ?>'>";
                cuerpo += "</div>";
                $("#valoresRestableses_"+id).html(cuerpo);

                if(tipo == '1' || tipo == '2'){
                    options += "<option value='='><?php echo $filtros_title_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_title_2c;?></option>";
                    options += "<option value='LIKE_1'><?php echo $filtros_title_3c;?></option>";
                    options += "<option value='LIKE_2'><?php echo $filtros_title_4c;?></option>";
                    options += "<option value='LIKE_3'><?php echo $filtros_title_5c;?></option>";
                }

                if(tipo == '4' || tipo == '3' || tipo == '_NumeInte__b'){
                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";
                    options += "<option value='>'><?php echo $filtros_numbe_3c;?></option>";
                    options += "<option value='<'><?php echo $filtros_numbe_4c;?></option>";

                    $("#valor_"+id).numeric();
                }


                if(tipo == '_NumeInte__b'){

                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";
                    options += "<option value='>'><?php echo $filtros_numbe_3c;?></option>";
                    options += "<option value='<'><?php echo $filtros_numbe_4c;?></option>";

                    $("#valor_"+id).numeric();

                    cuerpo = "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                    $("#valoresRestableses_"+id).append(cuerpo);

                }


                if(tipo == '_FecUltGes_b' || tipo == '_FeGeMaIm__b' ){
                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";
                    options += "<option value='>'><?php echo $filtros_numbe_3c;?></option>";
                    options += "<option value='<'><?php echo $filtros_numbe_4c;?></option>";
                    
                    $("#valor_"+id).datepicker({
                        language: "es",
                        autoclose: true,
                        todayHighlight: true
                    }).on('changeDate', function(){
                        renderizarCondiciones();
                    });
                    cuerpo = "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                    $("#valoresRestableses_"+id).append(cuerpo);
                }

                if(tipo == '5'){
                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";
                    options += "<option value='>'><?php echo $filtros_numbe_3c;?></option>";
                    options += "<option value='<'><?php echo $filtros_numbe_4c;?></option>";
                    options += "<option value='MayorMes'>Mayor a meses</option>";
                    options += "<option value='MenorMes'>Menor a meses</option>";
                    options += "<option value='MayorDia'>Mayor a dias</option>";
                    options += "<option value='MenorDia'>Menor a dias</option>";

                    // Creo un change que permita obtener valores tipo date o numerico
                    $("#condicion_"+id).change(function(){

                        // Si es una de estas opciones significa se se validara que la fecha este en un rango de x meses o dias(Se encuentre en los ultimos o no estes en los ultimos)
                        if($(this).val() == 'MayorMes' || $(this).val() == 'MenorMes' || $(this).val() == 'MayorDia' || $(this).val() == 'MenorDia'){
                            $("#valoresRestableses_"+id).html(cuerpo);
                            $("#valor_"+id).numeric();
                        }else{
                            $("#valoresRestableses_"+id).html(cuerpo);
                            $("#valor_"+id).datepicker({
                                language: "es",
                                autoclose: true,
                                todayHighlight: true
                            }).on('changeDate', function(){
                                renderizarCondiciones();
                            });
                        }
                    });

                    $("#valor_"+id).datepicker({
                        language: "es",
                        autoclose: true,
                        todayHighlight: true
                    }).on('changeDate', function(){
                        renderizarCondiciones();
                    });
                }


                if( tipo == '_Estado____b' || tipo == '_ConIntUsu_b' || tipo == 'MONOEF' || tipo == '8' || tipo == '6' || tipo == '11'){
                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";
                } 


                if(tipo == 'ListaCla'){

                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";

                    cuerpo  = "<div class='form-group'>";
                    cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                    cuerpo += "<option value='1'><?php echo $str_opcion_number1; ?></option>";
                    cuerpo += "<option value='2'><?php echo $str_opcion_number2; ?></option>";
                    cuerpo += "<option value='3'><?php echo $str_opcion_number3; ?></option>";
                    cuerpo += "<option value='4'><?php echo $str_opcion_number4; ?></option>";
                    cuerpo += "<option value='5'><?php echo $str_opcion_number5; ?></option>";
                    cuerpo += "<option value='6'><?php echo $str_opcion_number6; ?></option>";
                    cuerpo += "<option value='7'><?php echo $str_opcion_number7; ?></option>";
                    cuerpo += "</select>";
                    cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                    cuerpo += "</div>";  

                    $("#valoresRestableses_"+id).html(cuerpo);

                    $("#valor_"+id).val(estaEs);
                    $("#valor_"+id).val(estaEs).change();
                }


                if(tipo == '8'){
                    cuerpo  = "<div class='form-group'>";
                    cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                    cuerpo += "<option value='0'><?php echo $muesra_activo_2; ?></option>";
                    cuerpo += "<option value='-1'><?php echo $muesra_activo_1; ?></option>";
                    cuerpo += "</select>";
                    cuerpo += "</div>";  

                    $("#valoresRestableses_"+id).html(cuerpo);

                }


                if(tipo == '_Activo____b'){

                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";

                    cuerpo  = "<div class='form-group'>";
                    cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                    cuerpo += "<option value='0'><?php echo $muesra_activo_2; ?></option>";
                    cuerpo += "<option value='-1'><?php echo $muesra_activo_1; ?></option>";
                    cuerpo += "</select>";
                    cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                    cuerpo += "</div>";  

                    $("#valoresRestableses_"+id).html(cuerpo);

                }

                if(tipo == '_Estado____b'){
                    cuerpo  = "<div class='form-group'>";
                    cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                    cuerpo += "<option value='0'><?php echo $muestra_estado_1; ?></option>";
                    cuerpo += "<option value='1'><?php echo $muestra_estado_2; ?></option>";
                    cuerpo += "<option value='2'><?php echo $muestra_estado_3; ?></option>";
                    cuerpo += "<option value='3'><?php echo $muestra_estado_4; ?></option>";
                    cuerpo += "</select>";
                    cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                    cuerpo += "</div>";  

                    $("#valoresRestableses_"+id).html(cuerpo);

                }

                if(tipo == '6'){

                    $.ajax({
                        url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?Lisopc=true',
                        type   : 'post',
                        data   : { lisopc : $("#pregun_"+id+" option:selected").attr('lista') },
                        async : true,
                        success : function(data){
                            cuerpo  = "<div class='form-group'>";
                            cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                            cuerpo += data;
                            cuerpo += "</select>";
                            cuerpo += "</div>";  
                            $("#valoresRestableses_"+id).html(cuerpo);
                            if(estaEs != null){
                                $("#valor_"+id).val(estaEs);
                                $("#valor_"+id).val(estaEs).change();
                            }
                            renderizarCondiciones();
                        },
                        beforeSend : function(){
                            $.blockUI({ baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }

                if(tipo == '11'){

                    $.ajax({
                        url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?Litacompuesta=true',
                        type   : 'post',
                        data   : { idPregun : $("#pregun_"+id+" option:selected").val() },
                        async : true,
                        success : function(data){
                            cuerpo  = "<div class='form-group'>";
                            cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                            cuerpo += data;
                            cuerpo += "</select>";
                            cuerpo += "</div>";  
                            $("#valoresRestableses_"+id).html(cuerpo);
                            if(estaEs != null){
                                $("#valor_"+id).val(estaEs);
                                $("#valor_"+id).val(estaEs).change();
                            }
                            renderizarCondiciones();
                        },
                        beforeSend : function(){
                            $.blockUI({ baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }

                if(tipo == '_ConIntUsu_b'){
                    $.ajax({
                        url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?getUsuariosCampanha=true',
                        type   : 'post',
                        data   : { campan : $("#id_campanaFiltros").val() },
                        success : function(data){
                            cuerpo  = "<div class='form-group'>";
                            cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                            $.each(JSON.parse(data), function(i, item) {
                                 cuerpo += "<option value='"+item.USUARI_ConsInte__b+"'>"+ item.USUARI_Nombre____b+"</option>";
                            }); 
                            cuerpo += "</select>";
                            cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                            cuerpo += "</div>";  

                            $("#valoresRestableses_"+id).html(cuerpo);
                            renderizarCondiciones();
                        },
                        beforeSend : function(){
                            $.blockUI({ baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }

                if(tipo == 'MONOEF'){
                    $.ajax({
                        url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?getMonoefCampanha=true',
                        type   : 'post',
                        data   : { campan : $("#id_campanaFiltros").val() },
                        success : function(data){
                            cuerpo  = "<div class='form-group'>";
                            cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                            $.each(JSON.parse(data), function(i, item) {
                                 cuerpo += "<option value='"+item.MONOEF_ConsInte__b+"'>"+ item.MONOEF_Texto_____b+"</option>";
                            }); 

                            //JDBD - Tipificaciones PDS
                            cuerpo += "<option value='-8'>Marcando (PDS)</option>";
                            cuerpo += "<option value='0'>Lllamada contestada (PDS)</option>";
                            cuerpo += "<option value='-3'>No contesta (PDS)</option>";
                            cuerpo += "<option value='-4'>Ocupada (PDS)</option>";
                            cuerpo += "<option value='-5'>Fallida (PDS)</option>";
                            cuerpo += "<option value='-7'>Maquina detectada (PDS)</option>";
                            cuerpo += "<option value='-6'>No se encontro ruta de salida (PDS)</option>";
                            
                            cuerpo += "</select>";
                            cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                            cuerpo += "</div>";  

                            $("#valoresRestableses_"+id).html(cuerpo);

                            if(estaEs != null){
                                console.log(estaEs);
                                $("#valor_"+id).val(estaEs);
                                $("#valor_"+id).val(estaEs).change();
                            }
                            renderizarCondiciones();
                        },
                        beforeSend : function(){
                            $.blockUI({ baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }

                if(tipo == '_CanalUG_b'){
                    options += "<option value='='><?php echo $filtros_numbe_1c;?></option>";
                    options += "<option value='!='><?php echo $filtros_numbe_2c;?></option>";

                    cuerpo  = "<div class='form-group'>";
                    cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                    cuerpo += "<option value='telefonia'>Telefonia</option>";
                    cuerpo += "<option value='BusquedaManual'>Busqueda manual</option>";
                    cuerpo += "<option value='EMAIL'>Email</option>";
                    cuerpo += "<option value='Chat_web'>Chat web</option>";
                    cuerpo += "<option value='Whatsapp'>Whatsapp</option>";
                    cuerpo += "</select>";
                    cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                    cuerpo += "</div>";  

                    $("#valoresRestableses_"+id).html(cuerpo);

                }

                $("#condicion_"+id).html(options);
                renderizarCondiciones();
            });

            $(".deleteFiltro").click(function(){
                var id = $(this).attr('id');
                console.log("si llega "+ id);
                $("#id_"+id).remove();
                totalFiltros = totalFiltros -1;
            });
            
            // Si esto se cumple todas las condiciones se van a validar por un solo campo
            if($("#esSmsEntrante").val() == 1){
                let pregunSms = $("#valorPregunSms").val();
                $("#pregun_"+idActualRegistro).val(pregunSms);
                $("#pregun_"+idActualRegistro).change();
                $("#pregun_"+idActualRegistro+" option").attr("disabled", true);
                $("#pregun_"+idActualRegistro+" option[value='"+pregunSms+"']").attr("disabled", false);
            }
        });

        

        $(".Radiocondiciones").on('change', function () { 

            $("#textoPrevisualizacion").html('');

            if($(this).val() == 1){
                /* Es todos */  
                $("#txtCantidadRegistrps").val('');
                $("#divCantidadCampan").hide();
                $("#divFiltrosCampan").hide();
                $("#filtros").html("");
            }else if($(this).val() == 2){
                /* MontoEspecifico */
                $("#txtCantidadRegistrps").val('');
                $("#divCantidadCampan").show();
                $("#divFiltrosCampan").hide();
                $("#filtros").html("");
                
            }else if($(this).val() == 3){
                 /* Condiciones */
                $("#txtCantidadRegistrps").val('');
                $("#divCantidadCampan").hide();
                $("#divFiltrosCampan").show();
                $("#filtros").html("");

            }else if($(this).val() == 4){
                /* Registros especificos con condiciones */
                $("#txtCantidadRegistrps").val('');
                $("#divCantidadCampan").show();
                $("#divFiltrosCampan").show();
                $("#filtros").html("");
            }
        });

        $("#btnSaveFiltros").click(function(){
            valor = 1;
            $(".Radiocondiciones").each(function(){
                if($(this).is(":checked")){
                    valor = $(this).val();
            console.log("Preparando filtros " + $(this).val());
                }
            });
            valido = 1;
            if(valor == '2'){

                if($("#txtCantidadRegistrps").val() < 1){
                    alertify.error("<?php echo $error_top_regist;?>");
                    valido = 0;
                    $("#txtCantidadRegistrps").focus();
                }
            }

            if( $('#radiocondiciones3').is(':checked') ) {
                
                var cantCondiciones = $("#filtros > div").size();

                if(cantCondiciones == 0){
                    valido = 0;
                    alertify.error("Debes agregar condiciones para guardar esta configuracion");
                }
            }

            // Validamos si las condiciones estan correctas
            if( !ValidarParentesis( $("#textoPrevisualizacion").html() ) ){
                valido = 0;
                alertify.error("Los parentesis estan mal configurados, hay mas abiertos o cerrados");
            }

            if(valido == 1){
                save();
                // alert("Hola");
                $.ajax({
                   url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',  
                    type: 'POST',
                    data: { mySavedModel : $("#mySavedModel").val() , id_estrategia : '<?php echo $_GET['estrategia'];?>' , guardar_flugrama : 'SI' , poblacion : '<?php echo $str_poblacion; ?>'},
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data == '1'){
                           var data = new FormData($("#consultaCamposWhere")[0]);
                            data.append('contador' , contador2);
                            $.ajax({
                                url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?gestionar_base_datos=true",
                                type : "post",
                                data : data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success : function(data){
                                    if(data == '1'){
                                        alertify.success("<?php echo $exito_top_regist; ?>");
                                        $("#txtCantidadRegistrps").val('');
                                        $("#divCantidadCampan").hide();
                                        $("#divFiltrosCampan").hide();
                                        $("#filtros").html("");
                                        $("#consultaCamposWhere")[0].reset();
                                        $("#filtrosCampanha").modal('hide');
                                        totalFiltros = 0;
                                        location.reload();
                                    }else{
                                        alertify.error(data);
                                    }
                                },
                                complete : function(){
                                    $.unblockUI();
                                }
                            }); 
                                
                        }else{
                            //Algo paso, hay un error
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('<?php echo $error_de_red; ?>');
                    },
                    beforeSend : function(){
                        $.blockUI({ baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    }
                });
            }
        });

        $("#pasosSacar").select2({
            placeholder: "Seleccione los pasos donde saldran los registros"
        });

        $("#sacarOtrosPasos").click(function(){
            if( $(this).prop('checked') ) {
                $("#pasosSacar").attr('disabled', false);
            }else{
                $("#pasosSacar").attr('disabled', true);
            }
        });

        $("#heredarAgendas").click(function(){
            if( $(this).prop('checked') ) {
                $("#cmbTipoInsercion").val('0').trigger('change');
                $("#s_1").hide();
            }else{
                $("#s_1").show();
            }
        });

        // Evento que muestra o no los campos de programado a futuro
        $("#cmbTipoInsercion").change(function(){
            if($(this).val() == 0){
                $(".campos-programado-futuro").hide();
            }else{
                $(".campos-programado-futuro").show();
            }
        });
    });

    function cambiarTipoValor(tipollamada){
        $("#consultaCamposWhere")[0].reset();
        if(tipollamada != '1'){
            $(".condicionesTop").hide();
        }else{
            $(".condicionesTop").show();
        }
        $("#tipoLlamado").val(tipollamada);
        $("#txtCantidadRegistrps").val('');
        $("#divCantidadCampan").hide();
        $("#divFiltrosCampan").hide();
        $("#filtros").html("");
    }
</script>

<!-- Esto permite guardar los datos del formulario que tenemos para los casos de backoffice -->
<script>

    var camposPregunBd = [];

    $(function(){

        // En esta funcion se encontrara el buscador que filtrara por el nombre 
        $('#buscadorDisponible, #buscadorSeleccionado').keyup(function(){
            var tipoBuscador = $(this).attr('id');
            var nombres = '';

            if(tipoBuscador == 'buscadorDisponible'){
                nombres = $('ul#disponible .nombre');
            }else if(tipoBuscador == 'buscadorSeleccionado'){
                nombres = $('ul#seleccionado .nombre');
            }

            var buscando = $(this).val();
            var item='';

            for (let i = 0; i < nombres.length; i++) {
                item = $(nombres[i]).html().toLowerCase();
                
                for (let x = 0; x < item.length; x++) {
                    if(buscando.length == 0 || item.indexOf(buscando) > -1 ){
                        $(nombres[i]).closest('li').show();
                    }else{
                        $(nombres[i]).closest('li').hide();
                        
                    }
                }
                
            }
        });

        /** Estas funciones se encargan del funcionamiento del drag & drop */
        $("#disponible").sortable({connectWith:"#seleccionado"});
        $("#seleccionado").sortable({connectWith:"#disponible"});

        // Capturo el li cuando es puesto en la lista de usuarios disponible            
        $( "#disponible" ).on( "sortreceive", function( event, ui ) {
            let arrDisponible = [];
            let arrDisponible2 = [];
            arrDisponible[0] = ui.item.data("id");
            arrDisponible2[0] = ui.item.data("camp3");

            moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
        } );

        // Capturo el li cuando es puesto en la lista de usuarios seleccionados         
        $( "#seleccionado" ).on( "sortreceive", function( event, ui ) {
            let arrSeleccionado = [];
            let arrSeleccionado2 = [];
            arrSeleccionado[0] = ui.item.data("id");
            arrSeleccionado2[0] = ui.item.data("camp3");

            moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
        } );

        // Solo se selecciona el check cuando se clickea el li
        $("#disponible, #seleccionado").on('click', 'li', function(){
            $(this).find(".mi-check").iCheck('toggle');

            if($(this).find(".mi-check").is(':checked') ){
                $(this).addClass('seleccionado');
            }else{
                $(this).removeClass('seleccionado');
            }
            
        });

        $("#disponible, #seleccionado").on('ifToggled', 'input', function(event){
            if($(this).is(':checked') ){
                $(this).closest('li').addClass('seleccionado');
            }else{
                $(this).closest('li').removeClass('seleccionado');
            }
        });

        // Envia los elementos seleccionados a la lista de la derecha
        $('#derecha').click(function(){
            var obj = $("#disponible .seleccionado");
            $('#seleccionado').append(obj);

            let arrSeleccionado = [];
            let arrSeleccionado2 = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
                arrSeleccionado2[key] = $(value).data("camp3");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrSeleccionado.length > 0 && arrSeleccionado2.length > 0){
                moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
            }
            
        });

        // Envia los elementos seleccionados a la lista de la izquerda
        $('#izquierda').click(function(){
            var obj = $("#seleccionado .seleccionado");
            $('#disponible').append(obj);

            let arrDisponible = [];
            let arrDisponible2 = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
                arrDisponible2[key] = $(value).data("camp3");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrDisponible.length > 0 && arrDisponible2.length > 0){
                moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
            }
        });

        // Envia todos los elementos a la derecha
        $('#todoDerecha').click(function(){
            var obj = $("#disponible li");
            $('#seleccionado').append(obj);

            let arrSeleccionado = [];
            let arrSeleccionado2 = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
                arrSeleccionado2[key] = $(value).data("camp3");
            });
            
            moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
        });

        // Envia todos los elementos a la izquerda
        $('#todoIzquierda').click(function(){
            var obj = $("#seleccionado li");
            $('#disponible').append(obj);

            let arrDisponible = [];
            let arrDisponible2 = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
                arrDisponible2[key] = $(value).data("camp3");
            });
            
            moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
        });

        // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
        function moverUsuarios(arrUsuarios, arrUsuarios2, accion){
            
            var tareaBack = $('#casoBackofficeForm #idTareaBack').val();

            if(accion == 'derecha'){
                ruta = "agregarUsuarioTareaBackoffice=true";
            }else if(accion = 'izquerda'){
                ruta = "quitarUsuarioTareaBackoffice=true";
            }

            $.ajax({
                    url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?'+ruta,  
                    type: 'POST',
                    dataType : 'json',
                    data: {arrUsuarios : arrUsuarios, arrUsuarios2 : arrUsuarios2, tareaBack : tareaBack},
                    success: function(response){
                        alertify.success("Mensaje: "+response.estado);
                    },
                    error: function(response){
                        console.log(response);
                    },
                    beforeSend : function(){
                        $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    }
                });
        }

        /**---- Esta seccion es del formulario de backoffice ----*/
        $('#casoBackofficeForm #tipoDistribucionTrabajo').change(function(){

            if($('#casoBackofficeForm #tipoDistribucionTrabajo').val() != 2){
                $('#casoBackofficeForm #pregun').attr('disabled', 'disabled');
                $('#casoBackofficeForm #lisopc').attr('disabled', 'disabled');
                $('#casoBackofficeForm #pregun').val('');
                $('#casoBackofficeForm #lisopc').val('');
                
            }else{
                $('#casoBackofficeForm #pregun').removeAttr('disabled');
                $('#casoBackofficeForm #lisopc').removeAttr('disabled');
                
            }

        });

        // Se ejecuta cuando hay un cambio en el select de prengunt
        $('#casoBackofficeForm #pregun').change(function(){

            var id = $('#casoBackofficeForm #pregun').val();

            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getLisopc=true',  
                type: 'POST',
                data: {id : id},
                dataType : 'json',
                success: function(response){
                    $('#lisopc').html(response.lisopcOption);
                },
                error: function(response){
                    console.log(response);
                },
                beforeSend : function(){
                    $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });

        // Este boton guarda el formulario de tareas de backoffice
        $('#guardarCasoBackofficeButton').click(function(){
            
            var valido = 0;

            if($("#casoBackofficeForm #nombreCaso").val() < 1){
                valido = 1;
                alertify.error("El campo nombre no puede estar vacío");
                $("#casoBackofficeForm #nombreCaso").focus();
            }

            if(valido == 0){
                var formData = new FormData($("#casoBackofficeForm")[0]);
                var estadoModal = $("#casoBackofficeForm #tipoAccion").val();
                var mikey = $('#casoBackofficeForm #idEstpas').val();

                // Dependiendo de tipoAccion va a crear un registro nuevo o actualizarlo
                if(estadoModal == 'nuevo'){
                    formData.append('nuevoCasoBackoffice', true);
                }else if(estadoModal == 'editar'){
                    formData.append('editarCasoBackoffice', true);
                }
                
                $.ajax({
                   url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?guardarCasoBackoffice=true',  
                    type: 'POST',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.con1 && response.con2){
                            alertify.success("Guardado &eacute;xitoso");
                        }else{
                            alertify.error("Error al guardar");
                        }

                        if(estadoModal == 'nuevo'){
                            getDatosBackoffice(mikey);
                            
                        }else{
                            $("#casoBackofficeModal").modal('hide');
                            $.unblockUI();
                        }
                    },
                    error: function(response){
                        console.log(response);
                        $.unblockUI();
                    },
                    beforeSend : function(){
                        $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        //$.unblockUI();
                    }
                });
            }

        });

        $("#btnAdministrarRegistrosBackoffice").click(function(){
            $("#modalAdminRegistrosBackoffice").modal();
            $("#divAgregarFiltro").hide();
            $("#radioCodicionesBackofficeTodos").click();
            $("#listaFiltrosBackoffice").html('');
            $("#filtrosAdminBackoffice").val(0);

            // Obtenemos los campos de la bd
            $.ajax({
                url      : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?llenarDatosPregun=true',
                type     : 'post',
                data     : { guion  :  <?php echo $str_poblacion; ?> },
                dataType : 'json',
                success  : function(data){
                    camposPregunBd = data;
                }
            })

            // traemos los agentes asignados a esta tarea 
            $.ajax({
                url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getUsuariosBackoffice=true',
                type   : 'post',
                data   : { backoffice : $("#idTareaBack").val() },
                dataType: 'json',
                success : function(data){

                    let cuerpo = `
                        <option value="0">Seleccione</option> 
                        <option value="-1">Dejar sin agente</option> 
                    `;

                    if(data.usuarios && data.usuarios.length > 0){
                        data.usuarios.forEach(item => {
                            cuerpo += "<option value='"+item.id+"'>"+ item.nombre+"</option>";
                        });
                    }

                    $("#sel_usuarios_asignacion").html(cuerpo);
                }
            });

        });

        $("#radioCodicionesBackofficeFiltros").click(function(){
            $("#divAgregarFiltro").show();
        });

        $("#radioCodicionesBackofficeTodos").click(function(){
            $("#divAgregarFiltro").hide();
        });

        $(".deleteFiltro").click(function(){
            let id = $(this).attr('id');
            $("#id_"+id).remove();
        });
    });

    function agregarNuevoFiltroBackoffice(){
        
        let cantFiltros = $("#filtrosAdminBackoffice").val();
        var cantFiltrosCreados = document.getElementsByClassName("filtroBackoffice").length;

        let and = '';
        let botonDelete = '';

        let val1 = 5;
        let val2 = 4;

        if(cantFiltrosCreados > 0){
            and = `
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" name="operador${cantFiltros}" id="operador${cantFiltros}" numero="${cantFiltros}">
                            <option selected value="1">Y</option>
                            <option value="0">O</option>
                        </select>
                    </div>
                </div>
            `;

            botonDelete = ` 
                <div class="col-md-1">
                    <div class="form-group">
                        <button class="btn btn-danger btn-sm deleteFiltro" title="<?php echo $str_opcion_elimina;?>" type="button" id="${cantFiltros}">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>
            `;

            val1 = 3;
            val2 = 3;

        }

        let camposBd = '';

        camposPregunBd.forEach(element => {
            camposBd += `<option value="${element.PREGUN_ConsInte__b}" tipo="${element.PREGUN_Tipo______b}">${element.PREGUN_Texto_____b}</option>`;
        });

        // OPCIONES DESACTIVADAS
        // <option value='_NumeInte__b' tipo='3'>Numero de intentos</option>
        // <option value='_UltiGest__b' tipo='MONOEF'>Ultima gesti&oacute;n</option>
        // <option value='_GesMasImp_b' tipo='MONOEF'>Gesti&oacute;n mas importante</option>
        // <option value='_FecUltGes_b' tipo='5'>Fecha ultima gesti&oacute;n</option>
        // <option value='_FeGeMaIm__b' tipo='5'>Fecha gesti&oacute;n mas importante</option>
        // <option value='_ConUltGes_b' tipo='ListaCla'>Clasificaci&oacute;n ultima gesti&oacute;n</option>
        // <option value='_CoGesMaIm_b' tipo='ListaCla'>Clasificaci&oacute;n mas importante</option>

        let cuerpo = `
            <div class="row filtroBackoffice" id="id${cantFiltros}">
                ${and}

                <div class="col-md-${val1}">
                    <div class="form-group">
                        <select class="form-control mi_select_pregun" name="pregunBack${cantFiltros}" id="pregunBack${cantFiltros}" numero="${cantFiltros}">
                            <option value='0' tipo='3'>Seleccione</option>
                            <option value='_CoInMiPo__b' tipo='3'>ID BD</option>
                            <option value='_FechaInsercion' tipo='5'>FECHA CREACION</option>
                            ${camposBd}
                            <option value='_Estado____b' tipo='_Estado____b'>ESTADO_DY (tipo reintento)</option>
                            <option value='_ConIntUsu_b' tipo='_ConIntUsu_b'>Usuario</option>
                            <option value='_Activo____b' tipo='_Activo____b'>Registro activo</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <select name="condicion_${cantFiltros}" id="condicion_${cantFiltros}" class="form-control">
                            <option value="0">Condición</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3" id="divValor${cantFiltros}">
                    <div class="form-group">
                        <input type="text" name="idTareaBack${cantFiltros}" id="valor${cantFiltros}" class="form-control" placeholder="<?php echo $filtros_valor__c; ?>">
                    </div>
                </div>

                <input type="hidden" name="tipo_campo_${cantFiltros}" id="tipo_campo_${cantFiltros}" class="form-control">

                ${botonDelete}
            </div>
        `;

        cantFiltros++;
        $("#filtrosAdminBackoffice").val(cantFiltros);
        $("#listaFiltrosBackoffice").append(cuerpo);

        $(".mi_select_pregun").change(function(){

            let id = $(this).attr('numero');
            let tipo = $("#pregunBack"+id+" option:selected").attr('tipo');
            let valor = $(this).val();

            $("#tipo_campo_"+id).val(tipo);

            let options = "";
            
            if(tipo == '1' || tipo == '2'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='LIKE_1'>INICIE POR</option>";
                options += "<option value='LIKE_2'>CONTIENE</option>";
                options += "<option value='LIKE_3'>TERMINE EN</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor"+id+"' id='valor"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);
            }

            if(tipo == '4' || tipo == '3'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='<'>MENOR QUE</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor"+id+"' id='valor"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

                $("#valor"+id).numeric();
            }

            if(tipo == '5'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor"+id+"' id='valor"+id+"' class='form-control' placeholder='FECHA'>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='<'>MENOR QUE</option>";

                $("#valor"+id).datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true
                });
            }

            if(tipo == '10'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='<'>MENOR QUE</option>";

                cuerpo = "<div class='form-group'><input type='time' max='23:59:59' min='00:00:00' step='1' name='valor"+id+"' id='valor"+id+"' class='form-control' placeholder='00:00:00'></div>";

                $("#divValor"+id).html(cuerpo);
            }

            if( tipo == '_Activo____b' || tipo == '_Estado____b' || tipo == '_ConIntUsu_b' || tipo == 'MONOEF' || tipo == 'ListaCla' || tipo == '8' || tipo == '6'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
            } 

            if(tipo == '8'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control'>";
                cuerpo += "<option value='1'>ACTIVO</option>";
                cuerpo += "<option value='0'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            if(tipo == '_Activo____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control'>";
                cuerpo += "<option value='-1'>ACTIVO</option>";
                cuerpo += "<option value='0'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            if(tipo == 'ListaCla'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control'>";
                cuerpo += "<option value='1'>Devoluciones</option>";
                cuerpo += "<option value='2'>No contactable</option>";
                cuerpo += "<option value='3'>Sin gestion</option>";
                cuerpo += "<option value='4'>No contactado</option>";
                cuerpo += "<option value='5'>Contactado</option>";
                cuerpo += "<option value='6'>No efectivo</option>";
                cuerpo += "<option value='7'>Efectivo</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            if(tipo == '_Estado____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor"+id+"'  id='valor"+id+"' class='form-control'>";
                cuerpo += "<option value='0'>SIN GESTI&Oacute;N</option>";
                cuerpo += "<option value='1'>REINTENTO AUTOMATICO</option>";
                cuerpo += "<option value='2'>AGENDA</option>";
                cuerpo += "<option value='3'>NO REINTENTAR</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#divValor"+id).html(cuerpo);

            }

            // Llenamos la lista de agentes seleccionados en backoffice
            if(tipo == '_ConIntUsu_b'){

                $.ajax({
                    url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getUsuariosBackoffice=true',
                    type   : 'post',
                    data   : { backoffice : $("#idTareaBack").val() },
                    dataType: 'json',
                    success : function(data){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control'  id='valor"+id+"'>";
                        cuerpo += "<option value='-1'>No asignado</option>";

                        if(data.usuarios && data.usuarios.length > 0){
                            data.usuarios.forEach(item => {
                                cuerpo += "<option value='"+item.id+"'>"+ item.nombre+"</option>";
                            });
                        }

                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }

            if(tipo == 'MONOEF'){
                $.ajax({
                    url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php?getMonoefCampanha=true',
                    type   : 'post',
                    data   : { campan : $("#id_campanaFiltros").val() },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control'  id='valor"+id+"'>";
                        $.each(JSON.parse(data), function(i, item) {
                            cuerpo += "<option value='"+item.MONOEF_ConsInte__b+"'>"+ item.MONOEF_Texto_____b+"</option>";
                        }); 
                            cuerpo += "<option value='-12'>Mensaje enviado</option>";
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }

            if(tipo == '6'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                $.ajax({
                    url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
                    type   : 'post',
                    data   : { lista : valor , getListasDeEsecampo : true },
                    dataType: 'json',
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control'  id='valor"+id+"'>";
                        $.each(data, function(i, item) {
                            cuerpo += "<option value='"+item.LISOPC_ConsInte__b+"'>"+ item.LISOPC_Nombre____b+"</option>";
                        }); 
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }

            if(tipo == '11'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                $.ajax({
                    url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
                    type   : 'post',
                    data   : { lista : valor , getListasCompleja : true },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor"+id+"' class='form-control'  id='valor"+id+"'>";
                        cuerpo += data;
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#divValor"+id).html(cuerpo);
                    }
                });
            }
            
            $("#condicion_"+id).html(options);
        });
    }

    function aplicarFiltrosBackoffice(){

        let accionesValidas = false;
        
        // Se valida si hay alguna accion seleccionada
        $(".acciones").each(function(){
            if($(this).val() != 0){
                accionesValidas = true;
            }
        });

        if(!accionesValidas){
            alertify.warning("Debe seleccionar al menos una acción");
            return;
        }

        // validamos si seleccionan condiciones
        if( $("#radioCodicionesBackofficeFiltros").is(":checked") ){
            
            let cantidadFiltros = $("#listaFiltrosBackoffice").find('div').length;

            if(cantidadFiltros == 0){

                alertify.warning("Debe agregar un filtro en las condiciones");
                $("#btnNuevoFiltroBackoffice").click();
                return;
            }else{

                // Analizamos que no esten vacios los filtros
                $(".mi_select_pregun").each(function(){
                    if($(this).val() == 0){
                        alertify.warning("Debe seleccionar el campo a comparar");
                        $(this).focus();
                        return;
                    }
                });

            }
        }

        let data = new FormData($("#formAdminRegistrosBackoffice")[0]);
        data.append('backofficeId', $("#idTareaBack").val());

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?gestionarBackoffice=true',
            type: 'POST',
            data: data,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                if(data.exito){
                    alertify.success("Se ha actualizado los registros de backoffice exitosamente");
                    $("#modalAdminRegistrosBackoffice").modal('hide');
                    $("#formAdminRegistrosBackoffice")[0].reset();
                }else{
                    alertify.error("El proceso de actualizacion de backoffice ha fallado.");
                }
            },
            error: function(data){
                console.log(data);
            },
            beforeSend : function(){
                $.blockUI({ baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> Espere hasta que termine de procesar' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }
</script>

<!-- Funciones para LEAD -->
<script>
    /** Variables para lead */
    var opcionesCampoPregun = [];
    var opcionesCampoPregunSoloCliente = [];
    var contCamposEmparejar = 0;

    function cambioTipoCondicionLead(){
        var tipo_condicion = $('#leadTipoCondicion').val();
        if(tipo_condicion != 100){
            $('#leadCondicion').attr('disabled', false);
        }else{
            $('#leadCondicion').attr('disabled', true);
        }
    }

    function desactivarTagFinal(){

        $('.tag-final').prop( "readonly", false );

        if( $("#desactivarTagFinal").is(':checked') ) {

            $('.tag-final:last').prop( "readonly", true );

        }
    }

    function guardarLead(){
        var valido = 0;

        if($("#nombreLead").val() < 1){
            valido = 1;
            alertify.error("El campo nombre no puede estar vacío");
            $("#nombreLead").focus();
        }

        if($("#leadQuienRecibe").val() == 0){
            valido = 1;
            alertify.error("El campo debe ser seleccionado");
            $("#leadQuienRecibe").focus();
        }

        if($("#LeadCampoLlave").val() == 0){
            valido = 1;
            alertify.error("El campo Campo Llave debe ser seleccionado");
            $("#LeadCampoLlave").focus();
        }

        if(valido == 0){
            var formData = new FormData($("#form-lead")[0]);
            formData.append('contCamposEmparejar', contCamposEmparejar);
            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?guardarLead=true',  
                type: 'POST',
                data: formData,
                dataType : 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response){
                    if(response.estado = 1){
                        alertify.success("Guardado exitoso");
                        $("#modalLead").modal('hide');
                    }else{
                        alertify.error("Error al guardar");
                    }
                },
                error: function(response){
                    console.log(response);
                    //$.unblockUI();
                },
                beforeSend : function(){
                    $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        }
    }

    function buscarCorreo(correo){
        var formData=new FormData($("#dataConfigAutomatica")[0]);
        var intNoEncontrados=0;
        var strNoEncontrados='';
        var CampoEncontrado=true;
        var text='';
        var type='';
        var showAlert=false;
        formData.append('correo',correo);
        formData.append('configurarAutomatico',true);
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php',
            type: 'POST',
            data: formData,
            dataType : 'json',
            cache: false,
            contentType: false,
            processData: false,            
            success:function(data){
                if(data.estado_general==true){
                    for(var i in data){
                        if(data[i].estado==true){
                            $(".tagInicial_"+i).val(data[i].tagInicial);
                            $(".tagFinal_"+i).val(data[i].tagFinal);
                            showAlert=true;
                        }
                        
                        if(data[i].estado==false){
                            CampoEncontrado=false;
                            if(intNoEncontrados==0){
                                strNoEncontrados+=$("#campo_"+i).val();
                            }else{
                                strNoEncontrados+=","+$("#campo_"+i).val();;
                            }
                            intNoEncontrados++;
                        }
                        
                        if(CampoEncontrado){
                            text="Si encontré el correo y pude identificar todos los campos";
                            type="success";
                        }else{
                            type="warning"
                            if(intNoEncontrados>1){
                                text="No pude encontrar los campos: "+strNoEncontrados;
                            }else{
                                text="No pude encontrar el campo: "+strNoEncontrados;
                            }
                        }
                        
                    }
                    swal({
                        html : true,
                        title: "Informe de la configuración",
                        text: text,
                        type: type,
                        confirmButtonText: "Continuar",
                        showCancelButton : false,
                        closeOnConfirm : true
                    },
                        function(isconfirm){
                            if(isconfirm){
                                $("#close_configAutomatica").click();
                                if(showAlert){
                                    alertify.success('Se han llenado los tags de los campos encontrados');
                                }
                            }
                        }
                    );
                }else{
                    alertify.error(data.mensaje);
                }
            },
            beforeSend : function(){
                $.blockUI({baseZ : 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }
    
    function probarConfiguracion(correo){
        let camposBd=$("#LeadCampos tbody tr");
        let tags=[];
        let tag_inicial='';
        let tag_final='';
        let option='';
        tags['correo']=correo;
        for(let i=0; i<camposBd.length; i++){
            $(camposBd[i]).addClass('validar');
                option=$(".validar td select option:selected").html();

            $(".validar td input[placeholder='<ejemplo>']").each(function(){
                tag_inicial=$(this).val();
            });                

            $(".validar td input[placeholder='</ejemplo>']").each(function(){
                tag_final=$(this).val();
            });

            $(camposBd[i]).removeClass('validar');
            $("#totalCampos").val(i);
            tags[i]={opcion:option,tag_inicial:tag_inicial,tag_final:tag_final,correo:correo};
        }
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?probarConfiguracion=true&correo='+correo,
            type: 'POST',
            data: JSON.stringify(tags),
            dataType : 'json',
            cache: false,
            contentType: false,
            processData: false,            
            success:function(data){
                if(data.estado_general){
                    swal({
                        html : true,
                        title: "INFORMACION RECIBIDA",
                        text: data.mensaje+'<br> <strong style="font-size:25px">!</strong> Si la informaci&oacute;n que ve est&aacute; correcta, ya tiene correctamente configurada la inserci&oacute;n de datos a partir de este correo. De lo contrario por favor ajuste la configuraci&oacute;n.',
                        type: 'success',
                        confirmButtonText: "Continuar",
                        showCancelButton : false,
                        closeOnConfirm : true
                    },
                        function(isconfirm){
                            if(isconfirm){
                            }
                        }
                    );                    
                }else{
                    alertify.error(data.mensaje);    
                }
            },
            beforeSend : function(){
                $.blockUI({baseZ : 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

    $(function(){

        /**Traer los campos de PREGUN */
        $.ajax({
            url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php',
            type   : 'POST',
            data   : { getCamposPregun : true, idGuion: <?php echo $str_poblacion; ?> },
            dataType: 'json',
            success : function(data){
                opcionesCampoPregun = data.opciones;
                opcionesCampoPregunSoloCliente = data.opcionesSoloCliente;
            }
        });

        /*$('#nuevoCampoEmparejar').click(function(){
            var nuevoCampo = '';

            nuevoCampo += '<tr>';
            nuevoCampo += '<td>';
            nuevoCampo += '<input type="text" required class="form-control input-sm" name="dyTrtagInicialNuevo_'+contCamposEmparejar+'" id="tagInicialNuevo_'+contCamposEmparejar+'" placeholder="<ejemplo>">';
            nuevoCampo += '</td>';
            nuevoCampo += '<td>';
            nuevoCampo += '<input type="text" class="form-control input-sm" name="dyTr_tagFinalNuevo_'+contCamposEmparejar+'" id="tagFinalNuevo_'+contCamposEmparejar+'" placeholder="</ejemplo>">';
            nuevoCampo += '</td>';
            nuevoCampo += '<td>';
            nuevoCampo += '<select name="campoBDNuevo_'+contCamposEmparejar+'" id="campoBDNuevo_'+contCamposEmparejar+'" class="form-control input-sm">';
            nuevoCampo += '<option value="0">Seleccione</option>';
            nuevoCampo += opcionesCampoPregun;         
            nuevoCampo += '</select>';
            nuevoCampo += '</td>';
            nuevoCampo += '<td>';
            nuevoCampo += '<button type="button" class="btn btn-danger btn-sm eliminarCampoEmparejar"><i class="fa fa-trash"></i></button>';
            nuevoCampo += '</td>';
            nuevoCampo += '</tr>';

            $("#LeadCampos tbody").append(nuevoCampo);

            contCamposEmparejar += 1;

            $(".eliminarCampoEmparejar").click(function(){
                $(this).closest('tr').remove();
            });
        
        });
        */

        $('#campoEmparejar').click(function(){
            var nuevoCampo = '';

            /*if($(".borrarCampoEmparejarLead")){
                console.log($(".borrarCampoEmparejarLead"));
                var lastItem = $(".borrarCampoEmparejarLead")[$(".borrarCampoEmparejarLead").length - 1];
                 contCamposEmparejar = 1 + parseInt(lastItem.id);
            }*/

            nuevoCampo += '<tr>';
            nuevoCampo += '<td>';
            nuevoCampo += '<input type="text" required class="form-control input-sm" name="tagInicial_'+contCamposEmparejar+'" id="tagInicial_'+contCamposEmparejar+'" placeholder="identificador inicial">';
            nuevoCampo += '</td>';
            nuevoCampo += '<td>';
            nuevoCampo += '<input type="text" class="form-control input-sm tag-final" name="tagFinal_'+contCamposEmparejar+'" id="tagFinal_'+contCamposEmparejar+'" placeholder="identificador final">';
            nuevoCampo += '</td>';
            nuevoCampo += '<td>';
            nuevoCampo += '<select name="campoBD_'+contCamposEmparejar+'" id="campoBD_'+contCamposEmparejar+'" class="form-control input-sm required">';
            nuevoCampo += '<option value="0">Seleccione</option>';
            nuevoCampo += opcionesCampoPregun;         
            nuevoCampo += '</select>';
            nuevoCampo += '</td>';
            nuevoCampo += '<td>';
            nuevoCampo += '<button type="button" class="btn btn-danger btn-sm eliminarCampoEmparejar"><i class="fa fa-trash"></i></button>';
            nuevoCampo += '</td>';
            nuevoCampo += '</tr>';

            $("#LeadCampos tbody").append(nuevoCampo);

            contCamposEmparejar += 1;

            $(".eliminarCampoEmparejar").click(function(){
                $(this).closest('tr').remove();
                desactivarTagFinal();
            });
            
            desactivarTagFinal();
        });

        $('#desactivarTagFinal').click(function(){
            desactivarTagFinal();
        });

        /*
        $("#configurarAutomatico").click(function(){
            $("#totalCampos").val('');
            let html='';
            let openModal=true;
            let camposBd=$("#LeadCampos tbody tr");
            for(let i=0; i<camposBd.length; i++){
                $(camposBd[i]).addClass('validar');
                let option=$(".validar td select option:selected").html();
                let valor=$(".validar td select").val();
                
                $(".validar td input[placeholder='<ejemplo>']").each(function(){
                    $(this).addClass('tagInicial_'+i);
                });                
                
                $(".validar td input[placeholder='</ejemplo>']").each(function(){
                    $(this).addClass('tagFinal_'+i);
                });
                
                if(Number(valor) >0){
                    html+='<div class="col-md-6 col-xs-6"><div class="form-group"><label>CAMPO</label><input class="form-control input-sm" type="text" id="campo_'+i+'" value="'+option+'" readonly></div></div>';
                    html+='<div class="col-md-6 col-xs-6"><div class="form-group"><label>VALOR A BUSCAR</label><input class="form-control input-sm" type="text" value="" name="'+i+'"></div></div>';
                }else{
                    openModal=false;
                }
                
                $(camposBd[i]).removeClass('validar');
                $("#totalCampos").val(i);
            }
            
            if(openModal){
                $("#camposConfiguracionAutomatica").html(html);
                $("#configAutomatica").modal();
            }else{
                alertify.error('Debe elegir una opción para la lista de los campos de la base');
            }
        });
        */
        /*
        $("#buscarCorreo").click(function(){
            let correo=$("#leadQuienRecibe option:selected").html();    
            buscarCorreo(correo); 
        });
        */
        /*
        $("#probarConfiguracion").click(function(){
            let validaTags=true;
            $("input[placeholder='<ejemplo>']").each(function(){
                if($(this).val().length ==0){
                    validaTags=false;
                }
            });                

            $("input[placeholder='</ejemplo>']").each(function(){
                if($(this).val().length ==0){
                    validaTags=false;
                }
            });
            
            if(validaTags){
                let correo=$("#leadQuienRecibe option:selected").html();    
                probarConfiguracion(correo);
            }else{
                alertify.error('Todos los tags deben estar llenos');
            }
        });
        */
    });

</script>

<!-- Funciones para webservice -->
<script>
    $("#wsNombre").on('keyup paste', function(){
        generarEjemplo();
    });

    $("#generateCredencials").click(function(){
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?getCredencialesApi',
            type: 'POST',
            dataType: 'json',
            success: function(data){
                if(data.mensaje=='ok'){
                    $("#userToken").val(data.user);
                    $("#tokenToken").val(data.token);
                    $("#modalToken").modal();
                }else{
                    alertify.error(data.mensaje);
                }
            },
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            error:function(){
                $.unblockUI();
                alertify.error("Ocurrio un error al obtener las credenciales");
            }
    
        });
    });

    $("#configurarWS").click(function(){
        $("#iframePregunWS").attr('src','<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_webservice.php?ws='+$("#selectWS").val()+'&guion=<?=$_GET['poblacion']?>&estpas='+$("#pasowsId").val());
        $("#modalConfigWS").modal();
    });

    function cambiarLlave(){
        alert();
    }

    function generarEjemplo(){
        setTimeout(() => {
            const llave = document.getElementById("cllave").value;
            let paso = $('#pasowsId').val();
            let valores = $("#valoresws").val(); 
            let wsNombre = $("#wsNombre").val();
            
            let restriccionRegistro = false;

            // Valida el check de restriccion de registros
            if( $('#validaConRestriccion').prop('checked') ) {
                restriccionRegistro = true;
            }
            
            var campos = 'curl --location --request POST \'http://<?php echo $URL_SERVER_ADDONS ?>:8080/dy_servicios_adicionales/svrs/crm/procesarLead\' \\\n';
            campos += '--header \'Accept: application/json\' \\\n';
            campos += '--header \'Content-Type: application/json\' \\\n';
            campos += '--data \'{\n';
            campos += '\t "strUsuario_t": "USUARIO",\n';
            campos += '\t "strToken_t": "TOKEN",\n';
            campos += '\t "intCodigoAccion_t": "4",\n';
            campos += '\t "booValidaConRestriccion_t": "'+restriccionRegistro+'",\n';
            campos += '\t "intIdEstpas_t": "'+paso+'",\n';
            campos += '\t "strCamposLlave_t": "'+llave+'",\n';
            campos += '\t "strOrigenLead_t": "WS_'+wsNombre+'",\n',
            campos += '\t "mapValoresCampos_t": {\n';
            campos += valores;
            campos += '\t }\n';
            campos += '}\'';
            
            $("#infows").html(campos);  
        }, 200)
        addCopyToClipBoard()
    }

    const validaConRestriccion = (data) => {
        const { campollave, validaConRestriccion } = data
        const checkbox = document.getElementById('validaConRestriccion')
        setTimeout(() => {
            const select = document.getElementById("cllave");
            select.value = `${campollave}`
        }, 200)

        if (validaConRestriccion === "1") {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }
    }

    const addCopyToClipBoard = () => {
        setTimeout(()=>{

            const text = document.getElementById('infows').value
            const btnCopy = document.getElementById('btn-copy')  
            btnCopy.onclick = () => {
                 copyToClipBoard(text)
            }
        }, 400)
        
    }
    
    const copyToClipBoard = async (text) => {
        try {
            await navigator.clipboard.writeText(text)
           alertify.success("¡copiado!")
        } catch (error) {
            console.error('error :>> ', error)
        }
    }
    
    function saveWebservice(){
        var valido = 0;

        if($("#wsNombre").val() < 1){
            valido = 1;
            alertify.error("El campo nombre no puede estar vacío");
            $("#wsNombre").focus();
        }

        if(valido == 0){
            var formData = new FormData($("#webServiceForm")[0]);
            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?guardarwebservice=true',  
                type: 'POST',
                data: formData,
                dataType : 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response){
                    if(response.estado == 1){
                        alertify.success("Guardado exitoso");
                        $("#webserviceModal").modal('hide');
                    }else{
                        alertify.error("Error al guardar");
                    }
                },
                error: function(response){
                    console.log(response);
                    //$.unblockUI();
                },
                beforeSend : function(){
                    $.blockUI({baseZ : 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        }
    }

    function opcionLista(){
        let idLista=$("#campOpcion").val();
        
        if(idLista == 0){
            $("#opcionesLista").html('');
        }else{
            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?traeOpcionesLista',
                type: 'POST',
                dataType: 'json',
                data: {lista:idLista},
                success: function(data){
                    var opciones='';
                    $.each(data, function(val, element){ 
                        opciones +=element.id + ": " + '"' +element.texto + '" \n';
                    });
    
                    $("#opcionesLista").html(opciones);
                },
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
    
            });
        }
    }
</script>

<!-- Funciones crud flecha cuando es filtros de correo entrante -->
<script>

    var cantFiltrosEmail = 0;


    $("#nuevaCondicionCorreo").click(function(){
        agregarFila('add');
    });

    // Guarda los filtros de email entrante
    $("#btnSaveFiltrosEmailEntrante").click(function(){

        let valido = true;

        $(".validar-condicion").removeClass("error-input");

        // Primero valido dependiendo tipo de filtro valido si es requerido
        $(".validar-condicion").each(function(){

            let id = $(this).data('id');
            
            if($("#mailTipoCondicion_"+id).val() != 100){

                if($(this).val().length == 0){
                    $(this).addClass("error-input");
                    $(this).focus();

                    alertify.error("Este campo es requerido");
                    valido = false;
                }
            }
        });

        if(valido){
            let formData = new FormData($("#consultaCamposWhere")[0]);
            formData.append("cantFiltrosEmail", cantFiltrosEmail);

            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G31/G31_CRUD.php?guardarFiltros=true',  
                type: 'POST',
                data: formData,
                dataType : 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                success: function(data){
                    console.log(data);
                    $("#consultaCamposWhere")[0].reset();
                    $("#filtrosCampanha").modal('hide');
                    alertify.success('Datos guardados');
                    location.reload();
                },
                complete : function(){
                    $.unblockUI();
                },
                error: function(data){
                    if(data.responseText){
                        alertify.error("Hubo un error al guardar la información" + data.responseText);
                    }else{
                        alertify.error("Hubo un error al guardar la información");
                    }
                }
            });
        }
    });

    function agregarFila(oper) {

        let cant = cantFiltrosEmail;

        let row = `
            <tr id="fila_${cant}">
                <input type="hidden" id="campo_${cant}" name="campo_${oper}_${cant}" value="">
                <td style="display:none;">
                    <select class="form-control input-sm" id="operador_${cant}" name="operador_${oper}_${cant}">            
                        <option value="AND">&</option>
                        <option value="OR">O</option>
                    </select>
                </td>

                <td>
                    <select class="form-control input-sm" id="mailTipoCondicion_${cant}" name="mailTipoCondicion_${oper}_${cant}" onchange="cambioTipoCondicion(${cant})">
                        <option value="100">Sin Condición</option>
                        <option value="1">Proviene del correo</option>
                        <option value="2">Proviene del dominio</option>
                        <option value="3">El asunto contiene</option>
                        <option value="4">El cuerpo contiene</option>
                        <option value="5">El asunto no contiene</option>
                        <option value="6">El cuerpo no contiene</option>
                    </select>
                </td>

                <td>
                    <input type="text" data-id="${cant}" id="mailCondicion_${cant}" name="mailCondicion_${oper}_${cant}" class="form-control input-sm validar-condicion" placeholder="Ingrese la condicion" disabled>
                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFilaFiltro('${oper}', ${cant})"><i class="fa fa-trash"></i></button>
                </td>

            </tr>
        `;

        $("#correoCondiciones tbody").append(row);

        cantFiltrosEmail +=1;
    }

    function eliminarFilaFiltro(oper, id){
        if(oper == 'add'){
            // Se valida si existe algun otro filtro, si no no se deja eliminar
            if($('tr[id^="fila_"]').length > 1){
                $("#fila_"+id).remove();
            }else{
                alertify.error("Debe haber por lo menos un filtro");
            }
        }else{
            let filtroId = $("#campo_"+id).val();

            // Aqui elimina el filtro en la bd
            $.ajax({
                url     : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G31/G31_CRUD.php?deleteFiltro=true',
                type    : 'POST',
                dataType: 'json',
                data    : { filtroId : filtroId},
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                },
                complete : function(){
                    $.unblockUI();
                },
                success : function(data){
                    if(data.eliminado){
                        alertify.success(data.message);
                        $("#fila_"+id).remove();
                    }else{
                        alertify.error(data.message);
                    }
                },
                error: function(data){
                    if(data.responseText){
                        alertify.error("Hubo un error al borrar la información" + data.responseText);
                    }else{
                        alertify.error("Hubo un error al borrar la información");
                    }
                }
            });
        }
    }

    function cambioTipoCondicion(id){
        var tipo_condicion = $('#mailTipoCondicion_'+id).val();
        if(tipo_condicion != 100){
            $('#mailCondicion_'+id).attr('disabled', false);
        }else{
            $('#mailCondicion_'+id).attr('disabled', true);
        }
    }

    function eliminarFiltrosFlecha(id){
        $.ajax({
            url     : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G31/G31_CRUD.php?deleteTodosFiltros=true',
            type    : 'POST',
            dataType: 'json',
            data    : { pasoId : id},
            beforeSend : function(){
                $.blockUI({ 
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            success : function(data){
                if(data.eliminado){
                    alertify.success(data.message);
                }else{
                    alertify.error(data.message);
                }
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al borrar la información" + data.responseText);
                }else{
                    alertify.error("Hubo un error al borrar la información");
                }
            }
        });
    }

</script>

<!-- Funciones para los sms entrante -->
<script>
    function quitarConexionSmsEntrante(pasoId){
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G32/G32_CRUD.php?quitarConexiones=true',  
            type    : 'POST',
            dataType: 'json',
            data    : { pasoId : pasoId},
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                console.log(data);
                alertify.success("Link eliminado");
            },
            complete : function(){
                $.unblockUI();
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al eliminar la flecha" + data.responseText);
                }else{
                    alertify.error("Hubo un error al eliminar la flecha");
                }
            }
        });
    }

    function crearFlecha(pasoFrom, pasoTo){
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G32/G32_CRUD.php?crearFlecha=true',  
            type    : 'POST',
            dataType: 'json',
            data    : { pasoFrom : pasoFrom, pasoTo : pasoTo},
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                console.log(data);
                if(data.estado == 'ok'){
                    alertify.success("Link creado");
                    location.reload();
                }else if(data.estado == 'fallo'){
                    alertify.error(data.mensaje);
                    load();
                }else{
                    alertify.error(data.mensaje);
                    load();
                }
            },
            complete : function(){
                $.unblockUI();
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al crear la flecha" + data.responseText);
                }else{
                    alertify.error("Hubo un error al crear la flecha");
                }
            }
        });
    }
</script>

<!-- Funciones para el bot -->
<script>
    function obtenerBot(llaveInvocar, category){

        // Valido si el paso es nuevo
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G26/G26_CRUD.php?getPasoEsNuevo=true&id_paso='+llaveInvocar,
            type : 'get',
            dataType: 'json',
            success : function(data){
                if(data){
                    if(data.esNuevo === true){

                        $("#configuracionInicialBotNombre").val("bot_"+llaveInvocar);
                        $("#configuracionInicialPaso").val(llaveInvocar);

                        $("#configuracionInicialBot").modal('show');
                        $.unblockUI();
                    }else{
                        // Si ya existe solo traigo la informacion
                        
                        traerInformacionBot(llaveInvocar, category);
                    }
                }

            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                //$.unblockUI();
            }
        });
    }

    function obtenerBot1(llaveInvocar, category){

        // Valido si el paso es nuevo
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G261/G26_CRUD.php?getPasoEsNuevo=true&id_paso='+llaveInvocar,
            type : 'get',
            dataType: 'json',
            success : function(data){
                if(data){
                    if(data.esNuevo === true){

                        $("#configuracionInicialBotNombre").val("bot_"+llaveInvocar);
                        $("#configuracionInicialPaso").val(llaveInvocar);

                        $("#configuracionInicialBot").modal('show');
                        $.unblockUI();
                    }else{
                        // Si ya existe solo traigo la informacion
                        
                        traerInformacionBot1(llaveInvocar, category);
                    }
                }

            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                //$.unblockUI();
            }
        });
    }

    function traerInformacionBot(llaveInvocar, category){

        $.ajax({
            url: '<?=base_url?>mostrar_popups.php?view=chatbot&estrat=<?php echo($_GET['estrategia']);?>&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
            type : 'get',
            success : function(data){
                $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>'+category);
                $("#divIframe").html(data);
                $("#editarDatos").modal();
            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                //$.unblockUI();
            }
        });
    }

    function traerInformacionBot1(llaveInvocar, category){
        $.ajax({
            url: '<?=base_url?>mostrar_popups.php?view=chatbot1&id_paso='+llaveInvocar+'&poblacion=<?php echo $str_poblacion; ?>',
            type : 'get',
            success : function(data){
                $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>'+category);
                $("#divIframe").html(data);
                $("#editarDatos").modal();
            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                //$.unblockUI();
            }
        });
    }

    function guardarConfiguracionInicialBot(){
        let valido = true;

        let nombre = $("#configuracionInicialBotNombre").val();
        let paso = $("#configuracionInicialPaso").val();

        if(nombre == ''){
            $("#configuracionInicialBotNombre").focus();

            alertify.error("El campo nombre no puede estar vacio");
            valido = false;
        }

        if(valido){
            
            var formData = new FormData($("#formConfiguracionIncialBot")[0]);

            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G26/G26_CRUD.php?guardarConfiguracionInicial=true&id_paso='+paso+'&huesped=<?php echo $_SESSION['HUESPED'];?>',
                type: 'POST',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                },
                //una vez finalizado correctamente
                success: function(data){
                    $("#formConfiguracionIncialBot")[0].reset();
                    $("#configuracionInicialBot").modal('hide');
                    alertify.success("Información guardada con exito");
                    traerInformacionBot(paso, 'ivrTexto');
                },
                error: function(data){
                    console.log(data);
                    if(data.responseText){
                        alertify.error("Hubo un error al guardar la información" + data.responseText);
                    }else{
                        alertify.error("Hubo un error al guardar la información");
                    }
                }
            });
        }
    }

    function abrirPasoAutomatico(tipoPaso, key, categoria){
        LlamarModal(tipoPaso, key, categoria);
    }
</script>

<script>
    function cambioTipoAsignacionCampana(){
        if($('#tipoAsignacionPredefinida').is(':checked')){
            $('#campoAgente').attr('disabled', false);
        }else{
            $('#campoAgente').attr('disabled', true);
        }
    }

    function abrirModalInsertarRegistros(){
        $("#modalEjecucionProcesoFlecha").modal();

        $("#fechaRegistrosATomar").val('');

        $("#fechaRegistrosATomar").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        let from = $("#id_paso_from").val();
        let to = $("#id_paso_to").val();

        // Traer las condiciones de la flecha
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?obtenerCondiciones=true',
            type: 'post',
            data: { pasoFrom : from , pasoTo : to },
            dataType : 'json',
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success:function(res){
                $("#textoCondicionesProcesoFlechas").html(res.estructuraCondiciones);
                $("#labeFechaRegistro").html(res.estructuraLabelFecha);
            },
            complete : function(){
                $.unblockUI();
            },
            error: function(data){
                alertify.error("No se pudo obtener la informacion de las condiciones");
            }
        });
    }

    function iniciarProcesoFlecha(){

        $("#leiEntendi").prop('checked', false); 
        $("#btnIniciarEjecucionFlecha").prop('disabled', true);

        let fecha = $("#fechaRegistrosATomar").val();
        let valido = true;

        let from = $("#id_paso_from").val();
        let to = $("#id_paso_to").val();

        let poblacion = '<?php echo $str_poblacion; ?>';

        if(fecha == ''){
            valido = false;
            alertify.error("Es necesario que agrege la fecha desde la que se quieren tomar registros");
        }

        let fechaEsValida = Date.parse(fecha);
        if (isNaN(fechaEsValida)) {
            valido = false;
            alertify.error("El campo fecha no tiene un formato valido");
        }

        // Validamos y traemos la cantidad de registros a insertar
        if(valido){
            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?validarRegistrosAInsertar=true',
                type: 'post',
                data: { pasoFrom : from , pasoTo : to, fecha: fecha, poblacion: poblacion },
                dataType : 'json',
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                success:function(res){

                    if(res.estado == "ok"){
                        $("#modalConfirmarEjecucion").modal();
                        $("#textoCantRegistrosInsertar").html('Van a ser agregados '+res.cantidadRegistros+' registros que son los que cumplen con las condiciones');

                        $("#confirmBase").val(res.base);
                        $("#confirmEstcon").val(res.estcon);
                        $("#confirmFiltro").val(res.condicionFecha);

                        let condicion1 = $("#labeFechaRegistro").html();
                        let condicion2 = $("#textoCondicionesProcesoFlechas").html();
                        
                        $("#confirmCondiciones").html(`
                            ${condicion2}
                            <p style="font-size: 1.2em"><strong>${condicion1} ${res.condicionFecha}</strong><p>
                        `);

                        $("#confirmCondiciones .textoSubTituloCondiciones").hide();

                        let tipoPasoTo = res.pasoTo.tipo;
                        let tipoPasoToTexto = '';
                        switch (tipoPasoTo) {
                            case '6':
                                tipoPasoToTexto = 'llamadas';
                                break;
                            case '9':
                                tipoPasoToTexto = 'registros';
                                break;
                            case '7':
                                tipoPasoToTexto = 'correos';
                                break;
                            case '8':
                                tipoPasoToTexto = 'mensajes';
                                break;
                            default:
                                break;
                        }
                        
                        $("#textoConfirmacion").html(`<i class="fa fa-warning"></i> Está seguro de hacer esta inserción ? ... tenga en cuenta que eso disparará masivamente ${tipoPasoToTexto}`);
                    }else{
                        if(res.mensaje){
                            alertify.error(res.mensaje);
                        }else{
                            alertify.error("Se presento un error al traer la informacion");
                        }
                    }

                },
                complete : function(){
                    $.unblockUI();
                },
                error: function(data){
                    alertify.error("No se pudo obtener la informacion de los registros a insertar");
                }
            });
        }

    }

    function activarBtnIniciarEjecucionFlecha(){
        if($("#leiEntendi").is(':checked')){
            $("#btnIniciarEjecucionFlecha").prop('disabled', false);
        }else{
            $("#btnIniciarEjecucionFlecha").prop('disabled', true);
        }
    }

    function iniciarEjecucionFlecha(){

        let base = $("#confirmBase").val();
        let estconId = $("#confirmEstcon").val();
        let filtro = $("#confirmFiltro").val();

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?ejecutarProcesoFlecha=true',
            type: 'post',
            data: { base : base , estconId: estconId, filtro: filtro},
            dataType : 'json',
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success:function(res){
                $("#modalConfirmarEjecucion").modal('hide');
                $("#modalEjecucionProcesoFlecha").modal('hide');
                alertify.success("Se ha iniciado el proceso de insercion de registros");
            },
            complete : function(){
                $.unblockUI();
            },
            error: function(data){
                alertify.error("No se pudo obtener la informacion de los registros a insertar");
            }
        });
    }


    function getIVRList(){
        let proyecto = <?=$_SESSION["HUESPED"]?>;

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G36/G36_extender_funcionalidad.php?getIVRList=true',
            type: 'post',
            data: { proyecto : proyecto },
            dataType : 'json',
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success:function(res){
                if(res.state == "ok"){
                    const ivrSelect = $("#G10_C90");

                    $("#G10_C90").html('<option value="0">Seleccione</option>');

                    res.message.forEach(eIvr => {
                        ivrSelect.append(`<option value="${eIvr.idIvr}">${eIvr.nameIvr}</option>`);
                    });

                    ivrSelect.select2({
                        dropdownParent: $("#crearCampanhasNueva")
                    });

                }
            },
            complete : function(){
                $.unblockUI();
            },
            error: function(data){
                alertify.error("No se pudo obtener la informacion de los IVRs Configurados");
            }
        });
    }
</script>


<script type="text/javascript">

    $(document).on('hidden.bs.modal', function (event) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });

    $(".BorrarTabla").click(function(){
        $.ajax({
            url    : '<?=base_url?>carga/carga_CRUD.php?EliminarTablaTemporal=true',
            type   : 'POST',
            data   : { strNombreTablaTemporal_t : $("#TablaTemporal").val() }
        });
    });

    var datosGuion = [];
    var Aplica = 0;
    function before_save(){
        return true;
    }

    $(function(){
        $("#cancel").click(function(){
            <?php if(isset($_GET['ruta'])){ ?>
                window.location.href  = "<?=base_url?>index.php?page=dashEstrat&estrategia=<?php echo $_GET['estrategia'];?>&huesped=<?php echo $_SESSION['HUESPED'];?>";
            <?php } else { ?>
                window.location.href  = "<?=base_url?>modulo/estrategias"; 
            <?php } ?>
        });

        $("#txtRestaSumaFecha").numeric();
        $("#txtRestaSumaHora").numeric();


        $("#Save_modal").click(function(){
           salvarEsteFlujograma();
        });
    });

    function salvarEsteFlujograma(){
        save();
        if(before_save()){
            // alert("Hola");
            $.ajax({
               url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',  
                type: 'POST',
                data: { mySavedModel : $("#mySavedModel").val() , id_estrategia : '<?php echo $_GET['estrategia'];?>' , guardar_flugrama : 'SI' , poblacion : '<?php echo $str_poblacion; ?>'},
                //una vez finalizado correctamente
                success: function(data){
                    if(data == '1'){
                        alertify.success('<?php echo $str_Exito; ?>');
                        window.location.reload(true);  
                    }else{
                        //Algo paso, hay un error
                        alertify.error('<?php echo $error_de_proceso; ?>');
                    }                
                },
                //si ha ocurrido un error
                error: function(){
                    after_save_error();
                    alertify.error('<?php echo $error_de_red; ?>');
                },
                beforeSend : function(){
                    $.blockUI({ 
                        message: '<h3><?php echo $str_message_wait;?></h3>' ,
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
                complete : function(){
                    $.unblockUI();
                }
            });
        }
    }
</script>

<script type="text/javascript" id="code">

    $('.close').click(function(){
       borrarLogCargue();
       $("#export-errores").css('display','none');
       $("#title_cargue").html('<?php echo $str_carga;?>');
    });
    var JoseExist = false;
    
    var colors = {
        blue:   "#00B5CB",
        orange: "#F47321",
        green:  "#C8DA2B",
        gray:   "#888",
        white:  "#F5F5F5"
    }
    var newCuantosvan = 1;
    var contador2 = 1;
    var eliminarDesdePaso = false;
     /**
    * function que elimina el log de los registros recien cargados
    */
    function borrarLogCargue(){
         $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',
                type  : 'post',
                data: {'opcion':'borrarLogCargue'},
                dataType : 'json',
                success:function(data){
                    
                }
         }); 
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

        //console.log(e);
        if (e.change === go.ChangedEvent.Remove) {
            alert(evt.propertyName + " removed node with key: " + e.oldValue.key);
        }
    });

    myDiagram.addModelChangedListener(function(evt) {
        // ignore unimportant Transaction events
        if (!evt.isTransactionFinished) return;
        var txn = evt.object;  // a Transaction
        if (txn === null) return;
        // iterate over all of the actual ChangedEvents of the Transaction
        txn.changes.each(function(e) {
            // ignore any kind of change other than adding/removing a node
            if (e.modelChange !== "nodeDataArray") return;
            // record node insertions and removals
            if (e.change === go.ChangedEvent.Insert) {
                console.log(evt.propertyName + " added node with key: " + e.newValue.key);
                salvarEsteFlujograma();
            } else if (e.change === go.ChangedEvent.Remove) {
                //console.log(evt.propertyName + " removed node with key: " + e.oldValue.key);

                validarEliminarNodo(e.oldValue.key);

                eliminarDesdePaso = true;
                console.log(e);
            }
        });
    });

    myDiagram.addModelChangedListener(function(evt) {
        // ignore unimportant Transaction events
        if (!evt.isTransactionFinished) return;
        var txn = evt.object;  // a Transaction
        if (txn === null) return;
        // iterate over all of the actual ChangedEvents of the Transaction
        txn.changes.each(function(e) {
            // record node insertions and removals
            if (e.change === go.ChangedEvent.Property) {
                if (e.modelChange === "linkFromKey") {
                    //console.log(evt.propertyName + " changed From key of link: " +
                    //  e.object + " from: " + e.oldValue + " to: " + e.newValue);
                    if(e.object.disabled == -1){
                        load();
                        alertify.warning("Esta flecha solo se pueden borrar o editar desde el paso origen");
                    }

                    // Validamos cual es el paso de entrada
                    let nodeDataArray = e.model.nodeDataArray;
                    let pasoDesde = nodeDataArray.find(element => element.key == e.object.from);
                    if(pasoDesde.tipoPaso == 14 || pasoDesde.tipoPaso == 15 || pasoDesde.tipoPaso == 16){
                        load();
                        alertify.warning("La flecha no puede ser conectada directamente al paso " + pasoDesde.nombrePaso);
                    }

                    // Bloqueamos que se actualice el nodo de salida cuando es carge o webservice y va conectado a plantilla whatsapp
                    if(pasoDesde.tipoPaso == 5 || pasoDesde.tipoPaso == 11){
                        // Me toca verificar a donde esta conectado
                        let pasoTo = nodeDataArray.find(element => element.key == e.object.to);

                        if(pasoTo.tipoPaso == 13){
                            load();
                            if(pasoDesde.tipoPaso == 5){
                                alertify.warning("No es posible conectar flechas desde el paso de cargador de datos al paso de salida de plantillas de WhatsApp " + pasoTo.nombrePaso);
                            }else{
                                alertify.warning("No es posible conectar flechas desde el paso de WebService al paso de salida de plantillas de WhatsApp " + pasoTo.nombrePaso);
                            }
                        }
                    }

                } else if (e.modelChange === "linkToKey") {
                    //console.log(evt.propertyName + " changed To key of link: " +
                    // e.object + " from: " + e.oldValue + " to: " + e.newValue);

                    let nodeDataArray = e.model.nodeDataArray;

                    // Traigo el paso destio o a donde va a apuntar la flecha
                    let pasoDestino = nodeDataArray.find(element => element.key == e.newValue);

                    if(e.object.disabled == -1){
                        load();
                        alertify.warning("Esta flecha solo se pueden borrar o editar desde el paso origen");
                    }

                    // Si el paso es generado por el sistema no puede recibir flechas
                    if(pasoDestino.generadoPorSistema == -1){
                        load();
                        alertify.warning("A este paso no se le pueden conectar manualmente flechas de entrada");
                    }

                    // Bloqueamos que se actualice el nodo de llegada cuando es plantilla whatsapp y va conectado a cargue o webservice
                    if(pasoDestino.tipoPaso == 13){
                        // Me toca verificar de donde viene para ver si se deja conectar
                        let pasoFrom = nodeDataArray.find(element => element.key == e.object.from);

                        if(pasoFrom.tipoPaso == 5 || pasoFrom.tipoPaso == 11){
                            load();
                            if(pasoFrom.tipoPaso == 5){
                                alertify.warning("No es posible conectar flechas desde el paso de cargador de datos al paso de salida de plantillas de WhatsApp " + pasoDestino.nombrePaso);
                            }else{
                                alertify.warning("No es posible conectar flechas desde el paso de WebService al paso de salida de plantillas de WhatsApp " + pasoDestino.nombrePaso);
                            }
                        }
                    }
                }
            } else if (e.change === go.ChangedEvent.Insert && e.modelChange === "linkDataArray") {
                // console.log(e.newValue);
                // console.log(e.newValue.from + " added link: " + e.newValue.to);
                
                let to = e.newValue.to;
                let from = e.newValue.from;
                let nodeDataArray = e.model.nodeDataArray;
                let linkDataArray = e.model.linkDataArray;

                let pasoDesde = nodeDataArray.find(element => element.key == from);
                let pasoHasta = nodeDataArray.find(element => element.key == to);

                // Valido si el paso es 17 Correo entrante se realiza la siguiente validacion
                if(pasoDesde && pasoDesde.tipoPaso == 17 && pasoHasta && pasoHasta.tipoPaso != 1){
                    load();
                    alertify.warning("Este paso solo puede ser asociado con campañas entrantes.");
                }

                // valido que solo pueden entrar flechas de sms saliente a sms entrante
                if(pasoHasta && pasoHasta.tipoPaso == 18){

                    if(pasoDesde && pasoDesde.tipoPaso == 8){

                        // Valido que exista un solo link
                        let links = linkDataArray.find(element => element.to == to && element.from != from);
                        if(!links){
                            crearFlecha(from, to);
                        }else{
                            load();
                            alertify.error("El paso de sms entrante al cual se esta intentando de conectar solo puede tener una conexion");    
                        }

                    }else{
                        load();
                        alertify.warning("Este paso solo puede ser asociado con campañas entrantes de sms.");
                    }
                }

                if(pasoHasta && pasoHasta.generadoPorSistema == -1){
                    load();
                    alertify.warning("A este paso no se le pueden conectar manualmente flechas de entrada.");
                }

                // Valido de que no se puedan crear flechas a las comunicaciones entrantes
                if(pasoDesde && (pasoDesde.tipoPaso == 14 || pasoDesde.tipoPaso == 15 || pasoDesde.tipoPaso == 16)){
                    load();
                    alertify.warning("A este paso no se le pueden conectar manualmente flechas, para configurar una flecha debe ir a la configuracion del paso de comunicacion entrante " + pasoDesde.nombrePaso + ".");
                }

                // Valido que desde los pasos de salida de correo o sms no salgan flechas
                if(pasoDesde && (pasoDesde.tipoPaso == 7 || pasoDesde.tipoPaso == 8 || pasoDesde.tipoPaso == 13)){
                    load();
                    alertify.warning("Los pasos de salida no pueden tener flechas cuyo origen sea este mismo paso");
                }

                // Creamos una restriccion para que desde el carge y webservice no se pueda conectar a whatsapp saliente
                /*if(pasoDesde && (pasoDesde.tipoPaso == 5 || pasoDesde.tipoPaso == 11) && (pasoHasta && pasoHasta.tipoPaso == 13)){
                    load();
                    if(pasoDesde.tipoPaso == 5){
                        alertify.warning("No es posible conectar flechas desde el paso de cargador de datos al paso de salida de plantillas de WhatsApp " + pasoHasta.nombrePaso);
                    }else{
                        alertify.warning("No es posible conectar flechas desde el paso de WebService al paso de salida de plantillas de WhatsApp " + pasoHasta.nombrePaso);
                    }
                }*/
            
            } else if (e.change === go.ChangedEvent.Remove && e.modelChange === "linkDataArray") {
                // console.log(evt.propertyName + " removed link: " + e.oldValue.from);
                // esta parte lo que hace es ejecutar la accion despues de ejecutar el link, debe ser otro evento 

                let nodeDataArray = e.model.nodeDataArray;

                let pasoDesde = nodeDataArray.find(element => element.key == e.oldValue.from);

                // Ingresa solo si lo que trato de eliminar es solamente una flecha
                if(eliminarDesdePaso === false){
                    
                    if(e.oldValue.disabled == -1){
                        load();

                        let pasoHasta = nodeDataArray.find(element => element.key == e.oldValue.to);

                        mensajeInformativo(pasoDesde, pasoHasta);
                    }else{
                        
                        // Si la flecha que se borro esta conectado a sms cambio el estado para que no reciba sms
                        if(pasoDesde && pasoDesde.tipoPaso == 8){
                            quitarConexionSmsEntrante(e.oldValue.from);
                        }
        
                        if(pasoDesde && pasoDesde.tipoPaso == 17){
                            eliminarFiltrosFlecha(e.oldValue.from);
                        }

                        borrarLink(e.oldValue.from, e.oldValue.to);
                    }
                }
                
            }
        });
        eliminarDesdePaso = false;
    });

    myDiagram.addDiagramListener("ObjectDoubleClicked", function (e) {
        //console.log(e.subject.part.data);
        //console.log(e.subject.part.actualBounds.toString());
        if (e.subject.part instanceof go.Link) {
            if(!e.subject.part.data.disabled || e.subject.part.data.disabled === 0){
                let link = e.subject.part;
                verInformacion(link.data.from , link.data.to);
            }else{
                mensajeInformativo(e.subject.part.fromNode.je, e.subject.part.toNode.je);
            }
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
                selectionObjectName: "BODY",
                // the Node.location is at the center of each node
                locationSpot: go.Spot.Center,
                locationObjectName: "BODY",
                //isShadowed: true,
                //shadowColor: "#888",
                // handle mouse enter/leave events to show/hide the ports
                mouseEnter: function (e, obj) { showPorts(obj.part, true);  },
                mouseLeave: function (e, obj) { showPorts(obj.part, false);},
                doubleClick:function(e, obj){
                    if(obj.je){
                        console.log('44', obj.je.tipoPaso, obj.je.key, obj.je.category );
                        if(obj.je.key > 0){
                            LlamarModal(obj.je.tipoPaso, obj.je.key, obj.je.category );
                        }                  
                    }else{
                        LlamarModal(obj.mb.tipoPaso, obj.mb.key, obj.mb.category );
                    }
                }
            }
        ];
    }
    function textStyle() {
        return { font: "9pt  Segoe UI,sans-serif", stroke: "white" };
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
    // Esta configuracion muestra el nombre del paso
    function nombrePaso(nombre){
        // Este bloque muestra el nombre del paso
        return $(go.TextBlock,
            {
                text: nombre,
                alignment: new go.Spot(0.5, 1, 0, 15), alignmentFocus: go.Spot.Center,
                stroke: "black", font: "9pt Segoe UI, sans-serif",
                overflow: go.TextBlock.OverflowEllipsis,
                maxLines: 1,
                
            },
            new go.Binding("text", "nombrePaso")
        );
    }

    function generadoPorSistema(){
        // return new go.Binding("stroke", "active", function(v) { return null });
        return new go.Binding("stroke", "generadoPorSistema", function(v) { return v === -1 ? '#000' : null; });
    }

    function estadoFlecha(tipo){
        if(tipo === "link"){
            return new go.Binding("stroke", "", function(v) {
                if(v.disabled=='-1'){
                    return "#000";
                }else{
                    if(v.active=== -1 ){
                        return "#009fe3";
                    }else{
                        return config.colores.disabled;
                    }
                }
            });
        }

        if(tipo === "arrowhead"){
            return new go.Binding("stroke", "", function(v) {
                if(v.disabled=='-1'){
                    return "#000";
                }else{
                    if(v.active=== -1 ){
                        return "#009fe3";
                    }else{
                        return config.colores.disabled;
                    }
                }
            });
        }
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
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.orange, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),

                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.campanaEntrante,
                            editable: false
                        },
                    )
                ),

                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.campanaEntrante),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("CargueDatos",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50,
                            fill: config.colores.green,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.cargador,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.cargador),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, false),
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

        myDiagram.nodeTemplateMap.add("EnMail",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50,
                            fill: config.colores.green,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.leadsCorreo,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.leadsCorreo),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("Formul",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.green,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.webform,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.webform),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("webservice",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            minSize: new go.Size(40, 40),
                            fill: config.colores.green,
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                  $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.webservice,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.webservice),
                // Solo pueden salir las flechas de este paso
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("ivrTexto",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            minSize: new go.Size(40, 40),
                            fill: config.colores.blue,
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.bot,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.bot),
                // Solo pueden salir las flechas de este paso
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("ivrTextov1",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            minSize: new go.Size(40, 40),
                            fill: "#00A1C9",
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.bot,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.bot),
                // Solo pueden salir las flechas de este paso
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("salPhone",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.orange,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.campanaSaliente,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.campanaSaliente),
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
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.red, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.correoSaliente,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.correoSaliente),
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
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.red, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.smsSaliete,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.smsSaliete),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        //Rutas Entrantes
        myDiagram.nodeTemplateMap.add("LlamadasM",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.yellow, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.LlamadasM,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.LlamadasM),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        //IVRs
        myDiagram.nodeTemplateMap.add("IVRs",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.blue, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.IVRs,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.IVRs),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("salWhatsapp",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.red, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.whatsapp,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.salWhatsapp),
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
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            minSize: new go.Size(40, 40),
                            fill: config.colores.orange,
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.backoffice,
                            editable: false,
                            isMultiline: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.backoffice),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("comChat",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            minSize: new go.Size(40, 40),
                            fill: config.colores.yellow,
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.chatWeb,
                            editable: false,
                            isMultiline: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.chatWeb),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("comWhatsapp",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50,
                            minSize: new go.Size(40, 40),
                            fill: config.colores.yellow, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.whatsapp,
                            editable: false,
                            isMultiline: false
                        }
                    ),
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.whatsapp),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("comFacebook",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"}, 
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            minSize: new go.Size(40, 40),
                            fill: config.colores.yellow, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.facebook,
                            editable: false,
                            isMultiline: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.facebook),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("comEmail",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            minSize: new go.Size(40, 40),
                            fill: config.colores.yellow, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.correoEntrante,
                            editable: false,
                            isMultiline: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.correoEntrante),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("comSms",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.green,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.smsEntrante,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.smsEntrante),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagram.nodeTemplateMap.add("comSmsEntrante",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.yellow,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.smsEntrante,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.smsEntrante),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("comFormul",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50,
                            minSize: new go.Size(40, 40),
                            fill: config.colores.yellow, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        generadoPorSistema()
                    ),
                    $(go.TextBlock, 
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.webform,
                            editable: false,
                            isMultiline: false
                        }
                    ),
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.comWebform),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        myDiagram.nodeTemplateMap.add("comInstagram",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.yellow,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.instagram,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.instagram),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );
        
        // Bola de cargue desde subformulario
        myDiagram.nodeTemplateMap.add("cagueManual",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.green,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.cagueManual,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.cagueManual),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("L", go.Spot.Left, true, false),
                makePort("R", go.Spot.Right, true, false),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );

        // Bola marcador robotico
        myDiagram.nodeTemplateMap.add("salMRobotico",
            $(go.Node, "Spot", nodeStyle(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.red,
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconos.salMRobotico,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombrePasos.salMRobotico),
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
                    stroke: config.colores.disabled,
                    strokeWidth: 2
                },
                estadoFlecha("link")
            ),
            $(go.Shape,  // the arrowhead
                {
                    toArrow: "standard",
                    stroke: null,
                    fill: config.colores.disabled
                },
                estadoFlecha("arrowhead")
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
        myPalette =
            $(go.Palette, "myPaletteDiv",  // must name or refer to the DIV HTML element
            {
                "animationManager.duration": 800, // slightly longer than default (600ms) animation
                nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
                model: new go.GraphLinksModel([  // specify the contents of the Palette
                    { category: "CargueDatos", text: "Cargue de Datos",  tipoPaso : 5, figure : "Circle"},
                    { category: "Formul", text: "Formulario" ,  tipoPaso : 4, figure : "Circle"},
                    { category: "EnMail", text: "Lead",  tipoPaso : 10, figure : "Circle" },
                    { category: "webservice", text: "webService" ,  tipoPaso : 11, figure : "Circle"},
                    { category: "cagueManual", text: "Cargue Manual" ,  tipoPaso : 21, figure : "Circle"},
                    { category: "comSms", text: "Entrante sms", tipoPaso : 18, figure : "Circle"},
                    { category: "LlamadasM" , text: "Llamada Entrante",  tipoPaso : 23, figure : "Circle" },
                    { category: "comChat", text: "Comunicacion chat web", tipoPaso : 14, figure : "Circle"},
                    { category: "comWhatsapp", text: "Comunicacion chat whatsapp", tipoPaso : 15, figure : "Circle"},
                    { category: "comFacebook", text: "Comunicacion chat facebook", tipoPaso : 16, figure : "Circle"},
                    { category: "comInstagram", text: "Comunicacion chat instagram", tipoPaso : 20, figure : "Circle"},
                    { category: "comEmail", text: "Comunicacion email", tipoPaso : 17, figure : "Circle"},
                    { category: "comFormul", text: "Comunicacion Formulario" ,  tipoPaso : 19, figure : "Circle"},
                    { category: "comSmsEntrante", text: "Comunicacion Sms entrante" ,  tipoPaso : 26, figure : "Circle"},
                    { category: "ivrTexto", text: "ivrtexto" ,  tipoPaso : 12, figure : "Circle"},
                    { category: "IVRs", text: "IVRs" ,  tipoPaso : 25, figure : "Circle"},
                    { category: "EnPhone", text: "LLamandas entrantes", tipoPaso : 1, figure : "Circle"},
                    { category: "salPhone" , text: "Llamadas salientes",  tipoPaso : 6, figure : "Circle" },
                    { category: "salCheck" , text: "Tareas terminadas" , tipoPaso : 9 },
                    { category: "salMail" , text: "Correo saliente",  tipoPaso : 7, figure : "Circle" },
                    { category: "salSms" , text: "Mensajes de texto salientes",  tipoPaso : 8, figure : "Circle" },
                    { category: "salWhatsapp" , text: "Plantillas wa salientes",  tipoPaso : 13, figure : "Circle" },
                    { category: "salMRobotico" , text: "Marcador Robotico",  tipoPaso : 22, figure : "Circle" }
                ])
            });
        // The following code overrides GoJS focus to stop the browser from scrolling
        // the page when either the Diagram or Palette are clicked or dragged onto.
        function customFocus() {
            var x = window.scrollX || window.pageXOffset;
            var y = window.scrollY || window.pageYOffset;
            go.Diagram.prototype.doFocus.call(this);
            window.scrollTo(x, y);
        }
        myDiagram.doFocus = customFocus;
        myPalette.doFocus = customFocus;
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
        document.getElementById("mySavedModel").value = myDiagram.model.toJson();
        myDiagram.isModified = false;
    }
    function load() {
        myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
    }

    $(document).ready(function(){
        init();
    });
</script>


<!-- Funciones Para Flujograma IVR's -->
<script>
    
    //Configuracion para inicializar el flujograma
    function initIvr(){
        var $ = go.GraphObject.make;

        myDiagramIVR = $(go.Diagram, "SeccionGrafico",  // must name or refer to the DIV HTML element
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
        myDiagramIVR.addDiagramListener("Modified", function(e) {
            var button = document.getElementById("SaveButton");
            if (button) button.disabled = !myDiagram.isModified;
            var idx = document.title.indexOf("*");

            if (myDiagramIVR.isModified) {
                if (idx < 0) document.title += "*";
            } else {
                if (idx >= 0) document.title = document.title.substr(0, idx);
            }

            //console.log(e);
            if (e.change === go.ChangedEvent.Remove) {
                alert(evt.propertyName + " removed a node with key: " + e.oldValue.key);
            }
        });

        myDiagramIVR.addModelChangedListener(function(evt) {
            // ignore unimportant Transaction events
            if (!evt.isTransactionFinished) return;
            var txn = evt.object;  // a Transaction
            if (txn === null) return;

            // iterate over all of the actual ChangedEvents of the Transaction
            txn.changes.each(function(e) {
                // ignore any kind of change other than adding/removing a node
                if (e.modelChange !== "nodeDataArray") return;
                // record node insertions and removals
                if (e.change === go.ChangedEvent.Insert) { 
                    // alert(evt.propertyName + " added node with key: " + e.newValue.key);
                    CrearAccion(e.Cr);
                } else if (e.change === go.ChangedEvent.Remove) {
                    if(e.oldValue.tipoSeccion == '8' || e.oldValue.tipoSeccion == '9'){
                        alertify.warning("No Se Puede Eliminar '"+ e.oldValue.nombrePaso + "', Es Una Sección Por Defecto");
                        LoadIVR();
                    }else{
                        RemoverEsfera(e.oldValue.key);
                    }
                }
            });
        });

        myDiagramIVR.addModelChangedListener(function(evt) {
            // ignore unimportant Transaction events
            if (!evt.isTransactionFinished) return;
            var txn = evt.object;  // a Transaction
            if (txn === null) return;

            // iterate over all of the actual ChangedEvents of the Transaction
            txn.changes.each(function(e) {
                // record node insertions and removals
                if (e.change === go.ChangedEvent.Property) {
                    if (e.modelChange === "linkFromKey") {
                        console.log(e.oldValue, e.object.to, e.newValue, 'from', e.object.fromPort, e.object.toPort);
                        swal({
                            icon: 'error',
                            title: '❌ Oops...',
                            text: 'No Puedes Modificar Las Conexiones',
                            confirmButtonColor: '#2892DB'
                        })
                        ActualizarFlujograma();
                    } else if (e.modelChange === "linkToKey") {
                        console.log(e.object.from, e.oldValue, e.newValue, 'to', e.object.fromPort, e.object.toPort);
                        swal({
                            icon: 'error',
                            title: '❌ Oops...',
                            text: 'No Puedes Modificar Las Conexiones',
                            confirmButtonColor: '#2892DB'
                        })
                        ActualizarFlujograma();
                    }
                } else if (e.change === go.ChangedEvent.Insert && e.modelChange === "linkDataArray") {
                    //console.log(e.newValue.from + " added link: " + e.newValue.to);
                    CrearFlecha(e.newValue.from, e.newValue.to);
                
                } else if (e.change === go.ChangedEvent.Remove && e.modelChange === "linkDataArray") {
                    //console.log(e.oldValue.generadoPorSistema);
                    if(e.oldValue.generadoPorSistema != 1){
                        RemoverConexion(e.oldValue.from, e.oldValue.to);
                    }else{
                        RemoverConexion(e.oldValue.from, e.oldValue.to);
                    }
                }
            });
        });

        myDiagramIVR.addDiagramListener("ObjectDoubleClicked", function (e) {
            // console.log(e.subject.part.data);
            // console.log(e.subject.part.actualBounds.toString());
            if (e.subject.part instanceof go.Link) {
                let link = e.subject.part;
                // mostrarContenidoFlecha(link.data.from , link.data.to, 'show');
                //console.log("link.data: ", link.data);
                ValidarConexionFlecha(link.data.from , link.data.to);
            }
        });

        function showLinkLabel(e) {
            var label = e.subject.findObject("LABEL");
            if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Circle");
        }

        // helper definitions for node templates
        function nodeStyleIVR() {
            return [
                // The Node.location comes from the "loc" property of the node data,
                // converted by the Point.parse static method.
                // If the Node.location is changed, it updates the "loc" property of the node data,
                // converting back using the Point.stringify static method.
                new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
                {
                    selectionObjectName: "BODY",
                    // the Node.location is at the center of each node
                    locationSpot: go.Spot.Center,
                    locationObjectName: "BODY",
                    //isShadowed: true,
                    //shadowColor: "#888",
                    // handle mouse enter/leave events to show/hide the ports
                    mouseEnter: function (e, obj) { showPorts(obj.part, true); },
                    mouseLeave: function (e, obj) { showPorts(obj.part, false); },
                    doubleClick:function(e, obj){

                        var Key= obj.je.key;
                        var Category= obj.je.category;
                        var TipoPaso= obj.je.tipoPaso;
                        /*
                        console.log("obj", obj.je);
                        console.log("Key: ", Key);
                        console.log("Category: ", Category);
                        console.log("TipoPaso: ", TipoPaso);
                        */
                        if(TipoPaso){
                            DesplegarModal(TipoPaso);
                        }else{
                            var TipoPaso= obj.je.tipoSeccion;
                            ValidarConexionEsfera(Key, TipoPaso);
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
            return $(go.Shape, "Rectangle", {
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

        // Esta configuracion muestra el nombre del paso
        function nombrePaso(nombre){
            // Este bloque muestra el nombre del paso
            return $(go.TextBlock,
                {
                    text: nombre,
                    alignment: new go.Spot(0.5, 0.9, 0, 15), alignmentFocus: go.Spot.Center,
                    stroke: "black", font: "9pt Segoe UI, sans-serif",
                    overflow: go.TextBlock.OverflowEllipsis,
                    maxLines: 1,
                    
                },
                new go.Binding("text", "nombrePaso")
            );
        }

        function generadoPorSistema(){
            // return new go.Binding("stroke", "active", function(v) { return null });
            return new go.Binding("stroke", "generadoPorSistema", function(v) { return v == '1' ? '#000' : '#009fe3'; });
        }

        myDiagramIVR.nodeTemplateMap.add("",  // the default category
            $(go.Node, "Spot", nodeStyleIVR(),
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
                            stroke: "whitesmoke",
                            margin: 8,
                            maxSize: new go.Size(170, NaN),
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        },
                        new go.Binding("text").makeTwoWay()
                    )
                ),
                // four named ports, one on each side:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, false)
            )
        );
        
        myDiagramIVR.nodeTemplateMap.add("InicioIVR",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Terminator",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.green, 
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.InicioIVR,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.InicioIVR),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );
        

        myDiagramIVR.nodeTemplateMap.add("FinalIVR",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Terminator",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.red, 
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.FinalIVR,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.FinalIVR),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );
        
            
        myDiagramIVR.nodeTemplateMap.add("TomaDigitos",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.active, 
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.Digitos,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.TomaDigitos),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );
        
        myDiagramIVR.nodeTemplateMap.add("NumeroExterno",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.yellow, 
                            strokeWidth: 4,
                            stroke: null
                        },
                        new go.Binding("figure", "figure"),
                        generadoPorSistema()
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8,
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.NumeroExterno,
                            wrap: go.TextBlock.WrapFit,
                            editable: false
                        }
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.NumeroExterno),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagramIVR.nodeTemplateMap.add("PasarCampana",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.orange,
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.Campana,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.Campana),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagramIVR.nodeTemplateMap.add("ReproducirGrabacion",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.grey, 
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.Audio,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.Grabacion),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagramIVR.nodeTemplateMap.add("OtroIVR",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.blue,
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.OtroIVR,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.OtroIVR),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagramIVR.nodeTemplateMap.add("PasarEncuesta",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.red, 
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.Encuesta,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.Encuesta),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );

        myDiagramIVR.nodeTemplateMap.add("Avanzado",
            $(go.Node, "Spot", nodeStyleIVR(),
                $(go.Panel, "Auto",
                    {name: "BODY"},
                    $(go.Shape, "Circle",
                        {
                            width: 50, height: 50, 
                            fill: config.colores.purple, 
                            stroke: null,
                        }                    
                    ),
                    $(go.TextBlock,
                        {
                            margin: 8, 
                            maxSize: new go.Size(160, NaN),
                            stroke: config.colorIcono.white,
                            font: config.font,
                            text: config.iconosIVR.Avanzado,
                            editable: false
                        },
                    )
                ),
                // Esto es para mostrar el nombre del paso
                nombrePaso(config.nombreSeccionesIVR.Avanzado),
                // three named ports, one on each side except the top, all output only:
                makePort("T", go.Spot.Top, true, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, true)
            )
        );
        
        
        // Replace the default Link template in the linkTemplateMap
        myDiagramIVR.linkTemplate =
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
                    stroke: "#009fe3",
                    strokeWidth: 2
                },
                generadoPorSistema()
            ),
            $(go.Shape,  // the arrowhead
                {
                    toArrow: "standard",
                    stroke: null,
                    fill: "gray"
                },
                generadoPorSistema()
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

        // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
        myDiagramIVR.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
        myDiagramIVR.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

        LoadIVR();  // load an initial diagram from some JSON text

        // initialize the Palette that is on the left side of the page
        myPaletteIVR = $(go.Palette, "ListaSecciones",  // must name or refer to the DIV HTML element
            {
                "animationManager.duration": 800, // slightly longer than default (600ms) animation
                nodeTemplateMap: myDiagramIVR.nodeTemplateMap,  // share the templates used by myDiagram
                model: new go.GraphLinksModel([  // specify the contents of the Palette
                    
                    { category: "TomaDigitos", text: "Toma Dígitos", tipoPaso : 7, figure : "Circle"},
                    { category: "NumeroExterno", text: "Numero Externo", tipoPaso : 1, figure : "Circle"},
                    { category: "PasarEncuesta", text: "Pasar a Encuesta", tipoPaso : 5, figure : "Circle"},
                    { category: "ReproducirGrabacion", text: "Reproducir Grabación", tipoPaso : 3, figure : "Circle"},
                    { category: "OtroIVR", text: "Pasar a Otro IVR", tipoPaso : 4, figure : "Circle"},
                    { category: "PasarCampana", text: "Pasar a Una Campaña", tipoPaso : 2, figure : "Circle"},
                    { category: "Avanzado", text: "Avanzado", tipoPaso : 11, figure : "Circle"}

                ])
            }
        );

        // The following code overrides GoJS focus to stop the browser from scrolling
        // the page when either the Diagram or Palette are clicked or dragged onto.
        function customFocus() {
            var x = window.scrollX || window.pageXOffset;
            var y = window.scrollY || window.pageYOffset;
            go.Diagram.prototype.doFocus.call(this);
            window.scrollTo(x, y);
        }

        myDiagramIVR.doFocus = customFocus;
        myPaletteIVR.doFocus = customFocus;

    }
    // Make all ports on a node visible when the mouse is over the node
    function showPorts(node, show) {
        var diagram = node.diagram;
        if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
        node.ports.each(function(port) {
            port.stroke = (show ? "white" : null);
        });
    }




    //Recargar Flujograma
    function RecargarFlujograma(IdIVR){
        var FormDataId = new FormData();
        FormDataId.append('IdIVR', IdIVR);
        //console.log("IdIVR: ", IdIVR);

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaFlujogramaExistente.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormDataId,
            success: function(php_response){
                var SeccionesExistentes= php_response.DatosEsferasExistentes;
                var FlechasExistentes= php_response.DatosFlechas;
                console.log("SeccionesExistentes: ", SeccionesExistentes);
                console.log("FlechasExistentes: ", FlechasExistentes);

                
                let EstructuraSeccionesIVR = '';
                if(SeccionesExistentes != undefined){
                    SeccionesExistentes.forEach( (item, index)=> {
                        
                        if(index != 0){
                            EstructuraSeccionesIVR += ',';
                        }

                        let Categoria= item[1];
                        switch (Categoria) {
                            case "1":
                                Categoria = 'NumeroExterno';
                                break;
                            case "2":
                                Categoria = 'PasarCampana';
                                break;
                            case "3":
                                Categoria = 'ReproducirGrabacion';
                                break;
                            case "4":
                                Categoria = 'OtroIVR';
                                break;
                            case "5":
                                Categoria = 'PasarEncuesta';
                                break;
                            case "7":
                                Categoria = 'TomaDigitos';
                                break;
                            case "8":
                                Categoria = 'InicioIVR';
                                break;
                            case "9":
                                Categoria = 'FinalIVR';
                                break;
                            case "11":
                                Categoria = 'Avanzado';
                                break;
                            default:
                                break;
                        }
                        //console.log("Categoria: ", Categoria);

                        var loc= item[2];
                        let Coordenadas = '';
                        if(loc){
                            Coordenadas = loc.replace(/"/g, "");
                        }else{
                            Coordenadas = loc;
                        }

                        EstructuraSeccionesIVR += `{"category": "${Categoria}", "nombrePaso": "${item[0]}", "active": -1, "figure":"Circle", "key": ${item[3]}, "loc":"${Coordenadas}", "tipoSeccion": "${item[1]}"}`;

                    });
                    //console.log("EstructuraSeccionesIVR: ", EstructuraSeccionesIVR);
                }
                
                let EstructuraFlechasIVR = '';
                if(FlechasExistentes != undefined){
                    FlechasExistentes.forEach( (item, index)=> {
                        
                        if(index != 0){
                            EstructuraFlechasIVR += ',';
                        }

                        let CoordenadaFlecha= '';
                        if(item[3]){
                            CoordenadaFlecha= item[3].replace(/"/g, "");
                        }else{
                            CoordenadaFlecha= item[3];
                        }
                        EstructuraFlechasIVR += `{"from": ${item[1]}, "to": ${item[2]}, "fromPort": "${item[4]}", "toPort": "${item[5]}", "visible": true, "points": "${CoordenadaFlecha}", "text": "${item[0]}", "generadoPorSistema": "${item[6]}"}`;

                    });
                    //console.log("EstructuraFlechasIVR: ", EstructuraFlechasIVR);  
                }

                //Estructura Flujograma
                let Estructura= '';
                Estructura = `{
                    "class": "go.GraphLinksModel",
                    "linkFromPortIdProperty": "fromPort",
                    "linkToPortIdProperty": "toPort",
                    "nodeDataArray": [
                        ${EstructuraSeccionesIVR}
                    ],
                    "linkDataArray": [
                        ${EstructuraFlechasIVR}
                    ]
                }`;
                
                $("#SavedModelIVR").val(Estructura);
                LoadIVR();

                if(SeccionesExistentes == undefined){
                    ActualizarFlujograma();
                }
                
            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }

        });
        
    }

    
    //Actualizar Flujograma
    function ActualizarFlujograma() {
        var IdIVR= $("#IdIVR").val();
        LoadIVR();
        RecargarFlujograma(IdIVR);
    }
    
    //Pintar Datos En El Flujograma
    function LoadIVR() {
        myDiagramIVR.model = go.Model.fromJson(document.getElementById("SavedModelIVR").value);
    }
    //Añadir Datos Al Json
    function SaveIVR() {
        document.getElementById("SavedModelIVR").value = myDiagramIVR.model.toJson();
        myDiagramIVR.isModified = false;
    }

    
    //Eliminar Esfera
    function RemoverEsfera(IdEsfera) {
        console.log("IdEsfera: ", IdEsfera);

        Swal.fire({
            title: '¿Está Seguro?',
            text: "¿Deseas Eliminar Esta Esfera y Sus Acciones?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Si, Eliminar!'
        }).then((RespuestaEsfera) => {
            if (RespuestaEsfera.isConfirmed) {
                let FormDelete = new FormData();
                FormDelete.append('IdEsfera', IdEsfera);
                $.ajax({
                    url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/EliminarEsfera.php',
                    dataType: "json",
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormDelete,
                    success: function (php_response){
                        Respuesta = php_response.msg;
                        if(Respuesta == "Ok"){
                            Swal.fire({
                                title: '¡Eliminada   🗑!',
                                text: 'La Esfera Ha Sido Eliminada Exitosamente!',
                                icon: 'success',
                                showConfirmButton: false,
                                confirmButtonColor: '#2892DB',
                                timer: 2000
                            }).then(() => {
                                //window.location.reload();
                                
                            })
                        }else if(Respuesta == "Error"){
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error Al Eliminar!  🤨',
                                text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                                confirmButtonColor: '#2892DB'
                            })
                            console.log(php_response.msg);
                        }
                    },
                    error: function (php_response){
                        php_response = JSON.stringify(php_response);
                        Swal.fire({
                            icon: 'error',
                            title: '¡Fallo La Comunicacion Con El Servidor!  😵',
                            text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                            confirmButtonColor: '#2892DB'
                        })
                        console.log(php_response);
                    }
                });
            }else{
                LoadIVR();
            }
        })

    }
    
    //Eliminar Conexion
    function RemoverConexion(IdFrom, IdTo) {

        let FormDelete = new FormData();
        FormDelete.append('IdFrom', IdFrom);
        FormDelete.append('IdTo', IdTo);
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/EliminarFlecha.php',
            dataType: "json",
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: FormDelete,
            success: function (php_response){
                Respuesta = php_response.msg;
                if(Respuesta == "Ok"){
                    console.log("Respuesta: ", Respuesta);
                }else if(Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Eliminar!  🤨',
                        text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.msg);
                }
            },
            error: function (php_response){
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Fallo La Comunicacion Con El Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                console.log(php_response);
            }
        });
        

        /* 
        Swal.fire({
            title: '¿Está Seguro?',
            text: "¿Deseas Eliminar Esta Conexion?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Si, Eliminar!'
        }).then((RespuestaFlecha) => {
            if (RespuestaFlecha.isConfirmed) {
                
            }else{
                LoadIVR();
            }
        }) */
    
    }




    //Limpiar Todo
    function LimpiarTodo() {
        window.location.reload();
        //LimpiarIVRs();
    }


    //Limpiar Tabla Avanzado
    function LimpiarTablaAvanzado(){
        $("#IdOpcion_5").val("");
        $("#IdAccion_4").val("");
        $("#OrdenEjecu_3").val("");
        $("#IdsActualizar").val("");
        $("#NombreAvanzado").val("");
        var Tabla= $("#tbodyAvanzado");
        Tabla.empty();
    }
    

    //Pintar Datos Avanzado
    function PintarTablaAvanzado(IdOpcion){
        let Form= new FormData();
        Form.append("IdOpcion", IdOpcion);
        
        $.ajax({
            //url: Url+"Controller/ConsultaDatosAvanzado.php",
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaDatosAvanzado.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: Form,
            success: function(php_response) {
                var Resultado= php_response.Resultado;
                //console.log("Resultado: ", Resultado);

                console.log("php_response.msg: ", php_response.msg);
                if(php_response.msg == "Nada") {
                    $("#tbodyAvanzado").empty();
                }else{
                    
                    var Contador= 0;
                    Resultado.forEach(FilaDatos => {
                        Contador= Contador+1;
                        //console.log("FilaDatos: ", FilaDatos);

                        for (let i = 0; i < FilaDatos.length; i++){
                            const Dato = FilaDatos[i];
                            if(i == 2){
                                var Etiqueta= Dato;
                            }else if(i == 3){
                                var Aplicacion= Dato;
                            }else if(i == 4){
                                var Parametros= Dato;
                            }else if(i == 5){
                                var IdAccion= Dato;
                            }else if(i == 1){
                                var NombreAccionAvanz= Dato;
                            }else if(i == 0){
                                var Orden= Dato;
                            }
                        }
                        
                        var Row= $("<tr id='FilaAvanzado_"+IdAccion+"' style='text-align: center;'>");
                        var CampoOrden= $('<td class="col-md-2" style="visibility:collapse; display:none;"><input type="text" class="form-control" id="Orden_'+IdAccion+'" value="'+Orden+'" disabled hidden></td>');
                        var CampoNombre= $('<td class="col-md-2" style="visibility:collapse; display:none;"><input type="text" class="form-control" id="NombreAccionAvanz_'+IdAccion+'" value="'+NombreAccionAvanz+'" disabled></td>');
                        var CampoEtiqueta= $('<td class="col-md-2"><input type="text" class="form-control" id="Etiqueta_'+IdAccion+'" value="'+Etiqueta+'" placeholder="Etiqueta" disabled></td>');
                        var CampoAplicacion= $('<td class="col-md-3"><select id="Aplicacion_'+IdAccion+'" class="form-control" disabled><?php echo $ListaAplicaciones; ?></select></td>');
                        var CampoParametros= $('<td class="col-md-3"><input type="text" class="form-control" id="Parametros_'+IdAccion+'" value="'+Parametros+'" placeholder="Parámetros" disabled></td>');
                        var BotonActualizar= $('<td class="col-md-1"><button class="btn btn-primary" type="button" id="EditarAccion_'+IdAccion+'" onclick="ActualizarAvanzado('+IdAccion+')"><i style="margin-left: 11%;" class="fa fa-edit">&nbsp;</i></button></td>');
                        var BotonEliminar= $('<td class="col-md-1"><button class="btn btn-danger" type="button" id="EliminarAccion_'+IdAccion+'" style="margin-top: 2%;" onclick="EliminarAccion('+IdAccion+')"><i class="fa fa-trash-o"></i></button></td>');
                        
                        var OptionAplicacion= $('#Aplicacion_'+IdAccion).val(Aplicacion);
                        //$('#Aplicacion_'+IdAccion).text(Aplicacion);
                        OptionAplicacion.prop('disabled', true);

                        Row.append(CampoOrden);
                        Row.append(CampoNombre);
                        Row.append(CampoEtiqueta);
                        Row.append(CampoAplicacion);
                        Row.append(CampoParametros);
                        Row.append(BotonActualizar);
                        Row.append(BotonEliminar);

                        $("#TblAvanzado").append(Row);
                        $("#OrdenEjecu_3").val(Orden);
                        $("#IdAccion_4").val(IdAccion);
                        
                    });
                    
                    $("#NumFilasExistentes").val(Contador);
                    //console.log("Contador: ", Contador);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                console.log(php_response.msg);
                console.log(php_response);
            }
        });
    }


    
    //Validar Nombre Etiqueta Avanzado
    function ValidarNombreEtiqueta(IdOpcion, Etiqueta, FormAvanzado){
        console.log("IdOpcion: ", IdOpcion);
        console.log("Etiqueta: ", Etiqueta);

        var RespValidacion= "";

        let Formulario = new FormData();
        Formulario.append('IdOpcion', IdOpcion);
        Formulario.append('Etiqueta', Etiqueta);
        $.ajax({
            //url: Url+"ControllerFlujograma/ConsultaNombreEtiqueta.php",
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaNombreEtiqueta.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: Formulario,
            success: function(php_response) {
                var Respuesta = php_response.msg;
                if (Respuesta == "Ok"){
                    console.log("Respuesta: ", Respuesta);
                    GuardarAvanzado(FormAvanzado);
                }else if (Respuesta == "Ya Existe"){
                    var NombreBD= php_response.Resultado;
                    Swal.fire({
                        icon: 'info',
                        title: '¿Repetido?  🤔',
                        text: '"'+NombreBD+'", Ya Se Encuentra Registrado En El Sistema...',
                        confirmButtonColor: '#2892DB'
                    })

                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Consultar!  🤨',
                        text: 'Por Favor, Contactar Con El Area De Desarrollo...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                console.log(php_response);
            }
        });
        
    }

    //Actualizar Avanzado
    function ActualizarAvanzado(IdAccion) {
        console.log("IdAccion: ", IdAccion);
        $("#IdAccion_4").val(IdAccion);

        $('#Etiqueta_'+IdAccion).prop('disabled', false);
        $('#Aplicacion_'+IdAccion).prop('disabled', false);
        $('#Parametros_'+IdAccion).prop('disabled', false);

        var CampoIds= document.getElementById("IdsActualizar");
        CampoIds.append(IdAccion+" ");

    }

    //Guardar Datos Avanzado
    function GuardarAvanzado(FormAvanzado) {
        
        $.ajax({
            //url: Url+"ControllerFlujograma/GuardarAvanzado.php",
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarAvanzado.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormAvanzado,
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Swal.fire({
                        title: '¡Guardado!  😉',
                        text: '¡Acción Avanzada Se Registro Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    })
                }else if(Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!  🤨',
                        text: 'Al Guardar Información...',
                        confirmButtonColor: '#2892DB'
                    })
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    title: '🤨 Oops...',
                    text: "Por Favor Verificar Todas Las Etiquetas, Antes De Guardar",
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: '¡Ok, Entendido!'
                }).then((result) => {
                    if(result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }
        });
    }

    //Capturar Datos Avanzado
    function CapturarDatosAvanzado() {
        var IdEsteIVR= $("#IdIVR").val();
        var IdOpcion= $("#IdOpcion_5").val();
        var IdAccion= $("#IdAccion_4").val();
        var NombreAvanzado= $("#NombreAvanzado").val();

        if((NombreAvanzado == null) || (NombreAvanzado == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Nombre De Opción Avanzada"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            //Valida Si Se Guarda o Se Actualiza
            console.log("IdAccion: ", IdAccion);
            if((IdAccion == null) || (IdAccion == "")){
                //Guardar Nuevos
                var CantidadFilas= document.getElementById("TblAvanzado").rows.length;
                var NumeroFilas= CantidadFilas -1;
                var FilasExistentes= $("#NumFilasExistentes").val();
                var FilasNuevas= NumeroFilas - FilasExistentes;
                console.log("FilasNuevas: ", FilasNuevas);
                
                if(FilasNuevas == 0){
                    swal({
                        icon: 'error',
                        title: '❌ Oops...',
                        text: 'Debe Definir Al Menos Una Etiqueta y Sus Parámetros',
                        confirmButtonColor: '#2892DB'
                    })
                }else{
            
                    for(let i=0; i < FilasNuevas; i++) {
                        var Id= i+1;
                        var Etiqueta= $("#Etiqueta_"+Id).val();
                        var Parametros= $("#Parametros_"+Id).val();
                        var IdAplicacion= $("#Aplicacion_"+Id).val();

                        /*console.log("Etiqueta: ", Etiqueta);
                        console.log("Parametros: ", Parametros);
                        console.log("IdAplicacion: ", IdAplicacion);*/

                        let FormAvanzado = new FormData();
                        var IdIVR= null;
                        var NumAccion= 11;
                        var IdCampana= null;
                        var IdTroncal= null;
                        var IdEncuesta= null;
                        var IdGrabacion= null;
                        var TransEncuesta= null;
                        var OrdenEjecucionI= $("#OrdenEjecu_3").val();
                        if((OrdenEjecucionI == null) || (OrdenEjecucionI == "")){
                            var OrdenEjecucion= OrdenEjecucionI;
                        }else{
                            var OrdenEjecucion= parseInt(OrdenEjecucionI) + parseInt(Id);
                        }
                        var SelectAplicacion= document.getElementById("Aplicacion_"+Id);
                        var TextoAplicacion= SelectAplicacion.options[SelectAplicacion.selectedIndex].text;
                        var ValorAccion= TextoAplicacion+"("+Parametros+")";
                        
                        FormAvanzado.append('IdEsteIVR', IdEsteIVR);
                        FormAvanzado.append('IdOpcion', IdOpcion);
                        FormAvanzado.append('Accion', NumAccion);
                        FormAvanzado.append('IdTroncal', IdTroncal);
                        FormAvanzado.append('IdCampana', IdCampana);
                        FormAvanzado.append('TransEncuesta', TransEncuesta);
                        FormAvanzado.append('IdGrabacion', IdGrabacion);
                        FormAvanzado.append('IdIVR', IdIVR);
                        FormAvanzado.append('IdEncuesta', IdEncuesta);
                        FormAvanzado.append('Parametros', Parametros);
                        FormAvanzado.append('Etiqueta', Etiqueta);
                        FormAvanzado.append('IdAplicacion', IdAplicacion);
                        FormAvanzado.append('ValorAccion', ValorAccion);
                        FormAvanzado.append('NombreSeccion', NombreAvanzado);
                        FormAvanzado.append('OrdenEjecucion', OrdenEjecucion);

                        //Guardar Avanzado
                        if((Etiqueta == null) || (Etiqueta == "")){
                            swal({
                                icon: 'error',
                                title: '🤨 Oops...',
                                text: 'Debe Agregar Un Valor En El Campo "Etiqueta"',
                                confirmButtonColor: '#2892DB'
                            })
                        }else if((IdAplicacion == null) || (IdAplicacion == "")){
                            swal({
                                icon: 'error',
                                title: '🤨 Oops...',
                                text: 'Debe Agregar Un Valor En El Campo "Aplicacion"',
                                confirmButtonColor: '#2892DB'
                            })
                        }else{
                            ValidarNombreEtiqueta(IdOpcion, Etiqueta, FormAvanzado);
                            //break;
                        }

                    }

                }

            }else{
                //Actualizar Existentes
                var ListaIds= $("#IdsActualizar").val();
                ListaIds= ListaIds.trimEnd();

                var ArrayIds= ListaIds.split(" ");
                //console.log("ArrayIds: ", ArrayIds);
                ArrayIds.forEach(Id => {

                    var IdAccion= Id;
                    console.log("IdAccion: ", IdAccion);

                    var Etiqueta= $("#Etiqueta_"+IdAccion).val();
                    var IdAplicacion= $("#Aplicacion_"+IdAccion).val();
                    var Parametros= $("#Parametros_"+IdAccion).val();

                    
                    if((Etiqueta == null) || (Etiqueta == "")){
                        swal({
                            icon: 'error',
                            title: '🤨 Oops...',
                            text: 'Debe Agregar Un Valor En El Campo "Etiqueta"',
                            confirmButtonColor: '#2892DB'
                        })
                    }else if((IdAplicacion == null) || (IdAplicacion == "")){
                        swal({
                            icon: 'error',
                            title: '🤨 Oops...',
                            text: 'Debe Agregar Un Valor En El Campo "Aplicacion"',
                            confirmButtonColor: '#2892DB'
                        })
                    }else{
                    
                        let FormAvanzado = new FormData();

                        var IdIVR= null;
                        var NumAccion= 11;
                        var IdCampana= null;
                        var IdTroncal= null;
                        var IdEncuesta= null;
                        var IdGrabacion= null;
                        var TransEncuesta= null;
                        var OrdenEjecucion= $("#Orden_"+IdAccion).val();
                        var SelectAplicacion= document.getElementById("Aplicacion_"+Id);
                        var TextoAplicacion= SelectAplicacion.options[SelectAplicacion.selectedIndex].text;
                        var ValorAccion= TextoAplicacion+"("+Parametros+")";
                        
                        
                        FormAvanzado.append('IdEsteIVR', IdEsteIVR);
                        FormAvanzado.append('IdOpcion', IdOpcion);
                        FormAvanzado.append('Accion', NumAccion);
                        FormAvanzado.append('IdTroncal', IdTroncal);
                        FormAvanzado.append('IdCampana', IdCampana);
                        FormAvanzado.append('TransEncuesta', TransEncuesta);
                        FormAvanzado.append('IdGrabacion', IdGrabacion);
                        FormAvanzado.append('IdIVR', IdIVR);
                        FormAvanzado.append('IdEncuesta', IdEncuesta);
                        FormAvanzado.append('Parametros', Parametros);
                        FormAvanzado.append('Etiqueta', Etiqueta);
                        FormAvanzado.append('IdAplicacion', IdAplicacion);
                        FormAvanzado.append('ValorAccion', ValorAccion);
                        FormAvanzado.append('NombreSeccion', NombreAvanzado);
                        FormAvanzado.append('OrdenEjecucion', OrdenEjecucion);
                        

                        //Actualizar Avanzado
                        FormAvanzado.append('IdAccion', IdAccion);
                        ActualizarAccion(FormAvanzado);

                    }

                });
                
            }

        }
        
    }




    //Consultar Toma De Digitos
    function ConsultarTomaDigitos(IdTomaDigitos){
        console.log("IdTomaDigitos: ", IdTomaDigitos);

        let FormularioTomaDigitos = new FormData();
        FormularioTomaDigitos.append('IdTomaDigitos', IdTomaDigitos);

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaDatosTomaDigitos.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioTomaDigitos,
            success : function(php_response){
                Respuesta= php_response.msg;
                //console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Datos= php_response.DatosRespuesta[0];
                    //console.log("Datos: ", Datos);
                    $("#SelectGrabaDigitos_2").val(Datos[1]);
                    $("#SelectAceptarDigitos_2").val(Datos[2]);
                    $("#InputTiempoEspera_2").val(Datos[3]);
                    $("#IntentosPermitidos_2").val(Datos[4]);
                    $("#SelectGrabaErrada_2").val(Datos[5]);
                }else if(Respuesta == "Nada"){
                    console.log("Datos: ", Datos);
                    $("#SelectGrabaDigitos_2").val("");
                    $("#SelectAceptarDigitos_2").val("");
                    $("#InputTiempoEspera_2").val("");
                    $("#IntentosPermitidos_2").val("");
                    $("#SelectGrabaErrada_2").val("");
                }

            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }
            
        });

    }

    //Guardar Toma De Digitos
    function GuardarTomaDigitos(FormularioTomaDigitos){

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarTomaRespuesta.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioTomaDigitos,
            success: function(php_response) {
                Respuesta= php_response.msg;
                if (Respuesta == "Ok"){
                    console.log("Respuesta: ", Respuesta);
                    Swal.fire({
                        title: '¡Guardado!  😉',
                        text: '¡IVR Registrado Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        //window.location.reload();
                        RecargarFlujograma(IdIVR);
                        $("#ModalTomaDeDigitos").modal('hide');
                    })
                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Guardar Información!  🤨',
                        text: 'Por Favor, Contactar Con El Area De Desarrollo...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                $("#FormularioDatos2").show();
                $("#Loading_4").hide();
                console.log(php_response.msg);
                console.log(php_response);
            }
        });
        
    }

    //Actualizar Toma De Digitos
    function ActualizarTomaDigitos(FormularioTomaDigitos){

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ActualizarTomaDigitos.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioTomaDigitos,
            success: function(php_response) {
                Respuesta= php_response.msg;
                if (Respuesta == "Ok"){
                    console.log("Respuesta: ", Respuesta);
                    Swal.fire({
                        title: '¡Actualizado!  😉',
                        text: '¡IVR Modificado Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        //window.location.reload();
                        RecargarFlujograma(IdIVR);
                        $("#ModalTomaDeDigitos").modal('hide');
                    })
                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Actualizar La Información!  🤨',
                        text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                $("#FormularioDatos2").show();
                $("#Loading_4").hide();
                console.log(php_response.msg);
                console.log(php_response);
            }
        });

    }

    //Capturar Datos Toma De Digitos
    function CapturarTomaDeDigitos(){

        var IdIVR= $("#IdIVR").val();
        var IdProyecto= $("#huesped").val();
        var IdOpcion= $("#IdOpcion_4").val();
        var IdAccion= $("#IdAccion_3").val();
        var IdEstrategia= $("#IdEstrategia").val();
        var IdTomaDigitos= $("#IdTomaDigitos").val();
        var NombreRespuesta= $("#InputOpcionIVR_2").val();
        var GrabacionDigitos= $("#SelectGrabaDigitos_2").val();
        var GrabacionOpcErrada= $("#SelectGrabaErrada_2").val();
        var AceptarDigitos= $("#SelectAceptarDigitos_2").val();
        var TiempoEspera= $("#InputTiempoEspera_2").val();
        var IntentosPermitidos= $("#IntentosPermitidos_2").val();
        var GrabacionErrada= $("#SelectGrabaErrada_2").val();
        

        if((NombreRespuesta == null) || (NombreRespuesta == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Nombre Toma De Respuesta"',
                confirmButtonColor: '#2892DB'
            })
        }else if((GrabacionDigitos == null) || (GrabacionDigitos == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Grabación Captura De Respuesta"',
                confirmButtonColor: '#2892DB'
            })
        }else if((AceptarDigitos == null) || (AceptarDigitos == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "¿Aceptar Dígitos Durante Grabación?"',
                confirmButtonColor: '#2892DB'
            })
        }else if((TiempoEspera == null) || (TiempoEspera == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Tiempo De Espera"',
                confirmButtonColor: '#2892DB'
            })
        }else if((IntentosPermitidos == null) || (IntentosPermitidos == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Intentos Errados Permitidos"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            
            var NombreEsfera= NombreRespuesta;
            var NombreInternoI= NombreRespuesta.replace(" ", "_");
            var NombreInterno= NombreInternoI.replace(" ", "_");
            var NombreIVR= IdIVR+" -->  "+NombreRespuesta;

            console.log("IdIVR: ", IdIVR);
            console.log("IdTomaDigitos: ", IdTomaDigitos);
            console.log("IdAccion: ", IdAccion);
            console.log("GrabacionErrada: ", GrabacionErrada);

            let FormularioTomaDigitos = new FormData();
            FormularioTomaDigitos.append('IdIVR', IdIVR);
            FormularioTomaDigitos.append('IdEstrategia', IdEstrategia);
            FormularioTomaDigitos.append('IdProyecto', IdProyecto);
            FormularioTomaDigitos.append('NombreIVR', NombreIVR);
            FormularioTomaDigitos.append('IdOpcion', IdOpcion);
            FormularioTomaDigitos.append('NombreEsfera', NombreEsfera);
            FormularioTomaDigitos.append('NombreInterno', NombreInterno);
            FormularioTomaDigitos.append("GrabacionDigitos", GrabacionDigitos);
            FormularioTomaDigitos.append("AceptarDigitos", AceptarDigitos);
            FormularioTomaDigitos.append("TiempoEspera", TiempoEspera);
            FormularioTomaDigitos.append("IntentosPermitidos", IntentosPermitidos);
            FormularioTomaDigitos.append("GrabacionOpcErrada", GrabacionOpcErrada);


            if((IdTomaDigitos == null) || (IdTomaDigitos == "")){
                GuardarTomaDigitos(FormularioTomaDigitos);
            }else{
                FormularioTomaDigitos.append('IdTomaDigitos', IdTomaDigitos);
                FormularioTomaDigitos.append('IdAccion', IdAccion);
                ActualizarTomaDigitos(FormularioTomaDigitos);
            }

        }

    }


    //Guardar Nueva Accion
    function GuardarAccion(FormularioAcciones) {
        
        //var Url= CapturarDireccionIVR(Dirreccion);
        //console.log("Url: ", Url);
        $.ajax({
            //url: Url+"ControllerFlujograma/GuardarAccion.php",
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarAccion.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioAcciones,
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Swal.fire({
                        title: '¡Guardado!  😉',
                        text: '¡Acción Registrada Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        //window.location.reload();
                        RecargarFlujograma(IdIVR);
                        $("#ModalAccionesFlujograma").modal('hide');
                    })
                }else if(Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!  🤨',
                        text: 'Al Guardar Información...',
                        confirmButtonColor: '#2892DB'
                    })
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
            }
        });

    }

    //Consultar Datos Accion
    function ConsultarAccion(Key, IdOpcion, TipoPaso){
        let FormAccion = new FormData();
        var IdEsfera= Key;
        console.log("IdEsfera: ", IdEsfera);

        FormAccion.append('IdEsfera', IdEsfera);
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaDatosAccion.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormAccion,
            success : function(php_response){
                Respuesta= php_response.msg;
                //console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Datos= php_response.DatosAccion[0];
                    console.log("Datos Accion: ", Datos);
                    var IdAccion= Datos[12];
                    var NombreAccion= Datos[13];
                    var OrdenEjecucion= Datos[0];
                    
                    var ValorAccion= Datos[1];
                    console.log("ValorAccion: ", ValorAccion);
                    if(ValorAccion == 2){
                        var TipoAccion= "Pasar a Una Campaña";
                        var TransEncuesta= Datos[3];
                        var Campana= Datos[8];
                        $("#SelectCampana_2").val(Campana);
                    }else if(ValorAccion == 3){
                        var TipoAccion= "Reproducir Grabacion";
                        var Grabacion= Datos[9];
                        $("#SelectGraba_2").val(Grabacion);
                    }else if(ValorAccion == 4){
                        var TipoAccion= "Pasar a Otro IVR";
                        var Ivr= Datos[10];
                        $("#ListaIVR_2").val(Ivr);
                    }else if(ValorAccion == 9){
                        var TipoAccion= "Pasar a Encuesta";
                        var Encuesta= Datos[11];
                        $("#SelectEncuesta_2").val(Encuesta);
                        $("#NombreAccion").val(TipoAccion);
                    }else if(ValorAccion == 6){
                        var TipoAccion= "Numero Externo";
                        var Troncal= Datos[7];
                        var Numero= Datos[2];
                        $("#SelectLinea_2").val(Troncal);
                        $("#NumeroExterno_2").val(Numero);
                    }else if(ValorAccion == 5){
                        var TipoAccion= "Final IVR";
                        var NombreFinal= Datos[13];
                        var GrabaDespedida= Datos[9];
                        $("#NombreFinal").val(NombreFinal);
                        $("#SelectGrabaDespedida").val(GrabaDespedida);
                    }else if(ValorAccion == 11){
                        var TipoAccion= "Avanzado";
                        $("#NombreAvanzado").val(Datos[13]);
                        $("#IdOpcion_5").val(IdOpcion);
                        PintarTablaAvanzado(IdOpcion);
                    }else if(ValorAccion == 8){
                        var TipoAccion= "Captura De Respuesta";
                        var IdIVR_2= Datos[10];
                        $("#IdAccion_3").val(IdAccion);
                        $("#IdTomaDigitos").val(IdIVR_2);
                        $("#InputOpcionIVR_2").val(NombreAccion);
                        $("#OrdenEjecu_2").val(OrdenEjecucion);
                        ConsultarTomaDigitos(IdIVR_2);
                    }
                    
                    $("#IdAccion_2").val(IdAccion);
                    $("#NombreAccion").val(NombreAccion);
                    $("#OrdenEjecu").val(OrdenEjecucion);
                    DesplegarModalExistente(IdOpcion, TipoPaso);

                }else if(Respuesta == "Nada"){
                    $("#IdOpcion_3").val(IdOpcion);
                    $("#IdOpcion_4").val(IdOpcion);
                    $("#IdOpcion_5").val(IdOpcion);
                    $("#IdAccion_2").val("");
                    $("#NombreAccion").val("");
                    $("#OrdenEjecu").val("");
                    $("#IdAccion_4").val("");
                    $("#OrdenEjecu_3").val("");
                    $("#IdsActualizar").val("");
                    $("#NombreAvanzado").val("");
                    $("#NumFilasExistentes").val("");
                    $("#tbodyAvanzado").empty();
                    DesplegarModalExistente(IdOpcion, TipoPaso);
                }

            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }
            
        });

    }

    //Actualizar Accion
    function ActualizarAccion(FormularioAcciones){
    
        $.ajax({
            //url: Url+"Controller/ActualizarAccion.php",
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ActualizarAccion.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioAcciones,
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Swal.fire({
                        title: '¡Actualizado!  😉',
                        text: '¡Acción Modificada Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        var RespuestaAvanzado= php_response.IdAvanzado;
                        if((RespuestaAvanzado == null) || (RespuestaAvanzado == "null")){
                            RecargarFlujograma(IdIVR);
                            $("#ModalAccionesFlujograma").modal('hide');
                        }else{
                            window.location.reload();
                        }
                    })
                }else if(Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!  🤨',
                        text: 'Al Actualizar La Información...',
                        confirmButtonColor: '#2892DB'
                    })
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
            }
        });

    }

    //Capturar Datos Accion 
    function CapturarDatosAccion() {
        let FormAccion = new FormData();
        var IdEsteIVR= $("#IdIVR").val();
        var IdAccion= $("#IdAccion_2").val();
        var IdOpcion= $("#IdOpcion_3").val();
        var OrdenEjecucion= $("#OrdenEjecu").val();
        var NombreSeccion= $("#NombreAccion").val();
        var Accion= document.getElementById("TltOpcion_2").innerText;
        
        console.log("Accion: ", Accion);
        if(Accion == "Numero Externo"){
            var NumAccion= 6;
            var IdTroncal= $("#SelectLinea_2").val();
            var ValorAccion= $("#NumeroExterno_2").val();
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Una Campaña"){
            var NumAccion= 2;
            var IdTroncal= null;
            var SelectCampana= document.getElementById("SelectCampana_2");
            var IdCampana= SelectCampana.value;
            var ValorAccion= SelectCampana.options[SelectCampana.selectedIndex].text;
            var IdGrabacion= null;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
            var TransEncuesta= 0;
        }else if(Accion == "Reproducir Grabación"){
            var NumAccion= 3;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var SelectGraba= document.getElementById("SelectGraba_2");
            var IdGrabacion= SelectGraba.value;
            var ValorAccion= SelectGraba.options[SelectGraba.selectedIndex].text;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Otro IVR"){
            var NumAccion= 4;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var ListaIVR= document.getElementById("ListaIVR_2");
            var IdIVR= ListaIVR.value;
            var ValorAccion= ListaIVR.options[ListaIVR.selectedIndex].text;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Pasar a Encuesta"){
            var NumAccion= 5;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var IdGrabacion= null;
            var IdIVR= null;
            var SelectEncuesta= document.getElementById("SelectEncuesta_2");
            var IdEncuesta= SelectEncuesta.value;
            var ValorAccion= SelectEncuesta.options[SelectEncuesta.selectedIndex].text;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;
        }else if(Accion == "Final IVR"){
            var NombreSeccion= $("#NombreFinal").val();
            var NumAccion= 9;
            var IdTroncal= null;
            var IdCampana= null;
            var TransEncuesta= null;
            var SelectGraba= document.getElementById("SelectGrabaDespedida");
            var IdGrabacion= SelectGraba.value;
            var ValorAccion= SelectGraba.options[SelectGraba.selectedIndex].text;
            var IdIVR= null;
            var IdEncuesta= null;
            var Parametros= null;
            var Etiqueta= null;
            var IdAplicacion= null;

        }
        
        /*
        console.log("NombreSeccion: ", NombreSeccion);
        console.log("NumAccion: ", NumAccion);
        console.log("IdTroncal: ", IdTroncal);
        console.log("IdCampana: ", IdCampana);
        console.log("TransEncuesta: ", TransEncuesta);
        console.log("IdGrabacion: ", IdGrabacion);
        console.log("IdIVR: ", IdIVR);
        console.log("IdEncuesta: ", IdEncuesta);
        console.log("Parametros: ", Parametros);
        console.log("Etiqueta: ", Etiqueta);
        console.log("IdAplicacion: ", IdAplicacion);
        console.log("ValorAccion: ", ValorAccion); 
        exit(); */
        

        if((ValorAccion == "") || (ValorAccion == null)){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debes Diligenciar Todos Los Campos',
                confirmButtonColor: '#2892DB'
            })
        }else{
            var CantidadAccion= ValorAccion.length;
            //console.log("CantidadAccion: ", CantidadAccion);
            if(CantidadAccion = 18){
                var NumCorte= CantidadAccion - 2;
                var CorteAccion= ValorAccion.substring(0, NumCorte);
                console.log("CorteAccion: ", CorteAccion);
    
                if(CorteAccion == "Elige Una Opción"){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Debes Diligenciar Todos Los Campos',
                        confirmButtonColor: '#2892DB'
                    })
                }else{
                    let FormularioAcciones = new FormData();

                    console.log("Accion: ", Accion);
                    console.log("IdAccion: ", IdAccion);
                    console.log("NombreSeccion: ", NombreSeccion);

                    if((NombreSeccion == null) || (NombreSeccion == "")){
                        NombreSeccion= Accion;
                    }
                    
                    FormularioAcciones.append('IdEsteIVR', IdEsteIVR);
                    FormularioAcciones.append('IdOpcion', IdOpcion);
                    FormularioAcciones.append('Accion', NumAccion);
                    FormularioAcciones.append('IdTroncal', IdTroncal);
                    FormularioAcciones.append('IdCampana', IdCampana);
                    FormularioAcciones.append('TransEncuesta', TransEncuesta);
                    FormularioAcciones.append('IdGrabacion', IdGrabacion);
                    FormularioAcciones.append('IdIVR', IdIVR);
                    FormularioAcciones.append('IdEncuesta', IdEncuesta);
                    FormularioAcciones.append('Parametros', Parametros);
                    FormularioAcciones.append('Etiqueta', Etiqueta);
                    FormularioAcciones.append('IdAplicacion', IdAplicacion);
                    FormularioAcciones.append('ValorAccion', ValorAccion);
                    FormularioAcciones.append('NombreSeccion', NombreSeccion);

                    
                    if((IdAccion == null) || (IdAccion == "")){
                        //Guardar Accion
                        GuardarAccion(FormularioAcciones);
                    }else{
                        //Actualizar Accion
                        FormularioAcciones.append('IdAccion', IdAccion);
                        FormularioAcciones.append('OrdenEjecucion', OrdenEjecucion);
                        ActualizarAccion(FormularioAcciones);
                    }
                    

                }
            }
        }

    }



    //Consultar Datos Opcion
    function ConsultarOpcion(TipoFlecha) {
        let FormOpcion = new FormData();
        var IdTo= $("#TxtIdTo").val();
        var IdFrom= $("#TxtIdFrom").val();

        FormOpcion.append('IdFrom', IdFrom);
        FormOpcion.append('IdTo', IdTo);

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaDatosOpcion.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormOpcion,
            success : function(php_response){
                Respuesta= php_response.msg;
                //console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    Datos= php_response.DatosOpcion[0];
                    //console.log("Datos: ", Datos);
                    $("#IdFlecha").val(Datos[0]);
                    $("#IdOpcion_2").val(Datos[1]);
                    $("#TltOpcionFlecha").text(Datos[3]);
                    $("#NombreOpcionFlecha").val(Datos[3]);
                    $("#SelectOpcionNumero_2").val(Datos[2]);
                    $("#ModalOpcionFlecha").modal('show');

                }else if(Respuesta == "Nada"){
                    $("#IdFlecha").val("");
                    $("#IdOpcion_2").val("");
                    $("#TltOpcionFlecha").text("");
                    $("#NombreOpcionFlecha").val("");
                    $("#SelectOpcionNumero_2").val("Elige Una Opción");

                    $("#ModalOpcionFlecha").modal('show');
                }

            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }
            
        });

    }

    //Guardar Opcion
    function GuardarOpcion() {
        var IdIVR= $("#IdIVR").val();
        var IdTo= $("#TxtIdTo").val();
        var IdFrom= $("#TxtIdFrom").val();
        var IdOpcion= $("#IdOpcion_2").val();
        var NombreOpcion= $("#NombreOpcionFlecha").val();
        var OpcionNumero= $("#SelectOpcionNumero_2").val();
        var OpcionValida= 1;

        if((OpcionNumero == null) || (OpcionNumero == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Opción (Número A Marcar)"',
                confirmButtonColor: '#2892DB'
            })
        }else if((NombreOpcion == null) || (NombreOpcion == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Nombre De Opción"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            let FormularioOpciones = new FormData();
            FormularioOpciones.append('IdIVR', IdIVR);
            FormularioOpciones.append('IdTo', IdTo);
            FormularioOpciones.append('IdFrom', IdFrom);
            FormularioOpciones.append('OpcionNumero', OpcionNumero);
            FormularioOpciones.append('NombreOpcion', NombreOpcion);
            FormularioOpciones.append('OpcionValida', OpcionValida);

            console.log("IdOpcion: ", IdOpcion);
            if((IdOpcion == null) || (IdOpcion == "")){
                //Guardar Nueva Opcion
                $.ajax({
                    //url: Url+"Controller/GuardarOpcion.php",
                    url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarOpcion.php',
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormularioOpciones,
                    success: function(php_response) {
                        Respuesta= php_response.msg;
                        console.log("Respuesta: ", Respuesta);
                        if(Respuesta == "Ok"){
                            Swal.fire({
                                title: '¡Guardado!  😉',
                                text: '¡Opción Registrada Exitosamente!',
                                icon: 'success',
                                showConfirmButton: false,
                                confirmButtonColor: '#2892DB',
                                timer: 2000
                            }).then(() => {
                                var IdOpcion= php_response.IdOpcion;
                                RecargarFlujograma(IdIVR);
                                $("#ModalOpcionFlecha").modal('hide');
                                //window.location.reload();
                            })
                        }else if(Respuesta == "Error"){
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!  🤨',
                                text: 'Al Guardar Información...',
                                confirmButtonColor: '#2892DB'
                            })
                        }
                    },
                    error: function(php_response) {
                        php_response = JSON.stringify(php_response);
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Servidor!  😵',
                            text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                    }
                });

            }else{
                //Actualizar Opcion
                FormularioOpciones.append('IdOpcion', IdOpcion);
                $.ajax({
                    //url: Url+"Controller/ActualizarOpcion.php",
                    url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ActualizarOpcion.php',
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormularioOpciones,
                    success: function(php_response) {
                        Respuesta= php_response.msg;
                        console.log("Respuesta: ", Respuesta);
                        if(Respuesta == "Ok"){
                            Swal.fire({
                                title: '¡Actualizado!  😉',
                                text: '¡Opción Modificada Exitosamente!',
                                icon: 'success',
                                showConfirmButton: false,
                                confirmButtonColor: '#2892DB',
                                timer: 2000
                            }).then(() => {
                                //window.location.reload();
                                RecargarFlujograma(IdIVR);
                                $("#ModalOpcionFlecha").modal('hide');
                            })
                        }else if(Respuesta == "Error"){
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!  🤨',
                                text: 'Al Actualizar Información...',
                                confirmButtonColor: '#2892DB'
                            })
                        }
                    },
                    error: function(php_response) {
                        php_response = JSON.stringify(php_response);
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Servidor!  😵',
                            text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                            confirmButtonColor: '#2892DB'
                        })
                    }
                });
                
            }
            
        }
    }

    
    //Guardar IVRs
    function GuardarDatosIVRs(FormularioIVR) {
        
        $.ajax({
            //url: Url+"Controller/GuardarIVRs.php",
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarIVRs.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioIVR,
            success: function(php_response) {
                Respuesta= php_response.msg;
                if (Respuesta == "Ok"){
                    console.log("Respuesta: ", Respuesta);
                    Swal.fire({
                        title: '¡Guardado!  😉',
                        text: '¡IVR Registrado Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    })
                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Guardar Información!  🤨',
                        text: 'Por Favor, Contactar Con El Area De Desarrollo...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                $("#FormularioDatos2").show();
                $("#Loading_4").hide();
                console.log(php_response.msg);
                console.log(php_response);
            }
        });

    }

    //Actualizar IVRs
    function ActualizarDatosIVRs(FormularioIVR) {
        
        $.ajax({
            //url: Url+"Controller/ActualizarIVRs.php",
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ActualizarIVRs.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioIVR,
            success: function(php_response) {
                Respuesta= php_response.msg;
                if (Respuesta == "Ok"){
                    console.log("Respuesta: ", Respuesta);
                    Swal.fire({
                        title: '¡Actualizado!  😉',
                        text: '¡IVR Modificado Exitosamente!',
                        icon: 'success',
                        showConfirmButton: false,
                        confirmButtonColor: '#2892DB',
                        timer: 2000
                    }).then(() => {
                        //window.location.reload();
                        RecargarFlujograma(IdIVR);
                        $("#ModalInicioFlujogramaIVR").modal('hide');
                    })
                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Actualizar La Información!  🤨',
                        text: 'Por Favor, Contactar Con El Desarrollador Del Sistema...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response.Falla);
                }
            },
            error: function(php_response) {
                php_response = JSON.stringify(php_response);
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                console.log(php_response.msg);
                console.log(php_response);
            }
        });

    }

    //Capturar Datos IVRs
    function CapturarDatosIVRs() {
        
        var IdIVR= $("#IdIVR").val();
        var IdEstrategia= $("#IdEstrategia").val();
        var IdProyecto= $("#huesped").val();
        var NombreIVR= $("#InputNombreIVR").val();
        var NombreOpcion= $("#InputOpcionIVR").val();
        var GrabacionBienvenida= $("#SelectGrabaBienvenida").val();
        var GrabacionDigitos= $("#SelectGrabaDigitos").val();
        var GrabacionOpcErrada= $("#SelectGrabaErradaIVR").val();
        var AceptarDigitos= $("#SelectAceptarDigitos").val();
        var TiempoEspera= $("#InputTiempoEspera").val();
        var IntentosPermitidos= $("#IntentosPermitidos").val();

        if((NombreIVR == null) || (NombreIVR == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Nombre De IVR"',
                confirmButtonColor: '#2892DB'
            })
            $('#InputNombreIVR').prop("style", "border-color: #D22000");
        }else if((NombreOpcion == null) || (NombreOpcion == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Nombre De Opción"',
                confirmButtonColor: '#2892DB'
            })
        }else if((GrabacionBienvenida == null) || (GrabacionBienvenida == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Grabación De Bienvenida"',
                confirmButtonColor: '#2892DB'
            })
        }else if((GrabacionDigitos == null) || (GrabacionDigitos == "0")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Grabación De Toma De Dígitos"',
                confirmButtonColor: '#2892DB'
            })
        }else if((GrabacionOpcErrada == null) || (GrabacionOpcErrada == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Grabación De Opción Errada"',
                confirmButtonColor: '#2892DB'
            })
        }else if((AceptarDigitos == null) || (AceptarDigitos == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "¿Aceptar Dígitos Durante Grabación?"',
                confirmButtonColor: '#2892DB'
            })
        }else if((TiempoEspera == null) || (TiempoEspera == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Tiempo De Espera"',
                confirmButtonColor: '#2892DB'
            })
        }else if((IntentosPermitidos == null) || (IntentosPermitidos == "")){
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "Intentos Errados Permitidos"',
                confirmButtonColor: '#2892DB'
            })
        }else{
            var NombreInternoI= NombreIVR.replace(" ", "_");
            var NombreInterno= NombreInternoI.replace(" ", "_");

            let FormularioIVR = new FormData();
            FormularioIVR.append('IdEstrategia', IdEstrategia);
            FormularioIVR.append('IdProyecto', IdProyecto);
            FormularioIVR.append('NombreIVR', NombreIVR);
            FormularioIVR.append('NombreOpcion', NombreOpcion);
            FormularioIVR.append('NombreInterno', NombreInterno);
            FormularioIVR.append('GrabacionBienvenida', GrabacionBienvenida);
            FormularioIVR.append("GrabacionDigitos", GrabacionDigitos);
            FormularioIVR.append("AceptarDigitos", AceptarDigitos);
            FormularioIVR.append("TiempoEspera", TiempoEspera);
            FormularioIVR.append("IntentosPermitidos", IntentosPermitidos);
            FormularioIVR.append("GrabacionOpcErrada", GrabacionOpcErrada);

            
            //console.log("IdIVR: ", IdIVR);
            if((IdIVR == null) || (IdIVR == "")){
                GuardarDatosIVRs(FormularioIVR);
            }else{
                FormularioIVR.append('IdIVR', IdIVR);
                ActualizarDatosIVRs(FormularioIVR);
            }

        }
    }



    //Crear Flecha
    function CrearFlecha(IdFrom, IdTo) {
        let FormFlecha = new FormData();
        var IdEstrategia= $("#IdEstrategia").val();
        var IdHuesped= <?=$_SESSION['HUESPED']?>;
        var IdIVR= $("#IdIVR").val();
        FormFlecha.append('IdFrom', IdFrom);
        FormFlecha.append('IdTo', IdTo);

        //Colsultar Tipo De Flecha
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaTipoFlecha.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormFlecha,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success : function(php_response){
                var DatosTipoFlecha= php_response.DatosTipoFlecha[0];
                var TipoEsfera= DatosTipoFlecha[1];
                if((TipoEsfera == 7)||(TipoEsfera == 8)){
                    var TipoFlecha= 0;
                }else{
                    var TipoFlecha= 1;
                }
                console.log("TipoFlecha: ", TipoFlecha);

                //Crear Flecha
                SaveIVR();
                let FormularioFlecha= new FormData();
                var StringFlujograma= document.getElementById("SavedModelIVR").value;

                FormularioFlecha.append('IdIVR', IdIVR);
                FormularioFlecha.append('IdHuesped', IdHuesped);
                FormularioFlecha.append('TipoFlecha', TipoFlecha);
                FormularioFlecha.append('IdEstrategia', IdEstrategia);
                FormularioFlecha.append('StringFlujograma', StringFlujograma);

                $.ajax({
                    url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarFlecha.php',
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: FormularioFlecha,
                    success: function(php_response) {
                        Respuesta= php_response.msg;
                        console.log("Respuesta: ", Respuesta);
                        if(Respuesta == "Ok"){
                            alertify.success(" ¡Conexión Creada! 😉 ");
                            RecargarFlujograma(IdIVR);
                        }else if(Respuesta == "Actualizado Ok"){
                            alertify.success(" ¡Conexión Modificada! 😉 ");
                            RecargarFlujograma(IdIVR);
                        }else if(Respuesta == "Ya Existe"){
                            swal({
                                icon: 'error',
                                title: '❌  Oops...',
                                text: '¡Las Únicas Esferas Que Pueden Tener Más De Una Conexión Son "Inicio IVR" O "Captura Respuesta"!',
                                confirmButtonColor: '#2892DB'
                            })
                            RecargarFlujograma(IdIVR);
                        }else if(Respuesta == "Nada"){
                            swal({
                                icon: 'error',
                                title: '🤨  Oops...',
                                text: '¡Debes Configurar La Acción Anterior, Antes De Crear Una Nueva Conexion!',
                                confirmButtonColor: '#2892DB'
                            })
                            RecargarFlujograma(IdIVR);
                        }

                    },
                    error: function(php_response) {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error Servidor!  😵',
                            text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                            confirmButtonColor: '#2892DB'
                        })
                        php_response = JSON.stringify(php_response);
                        console.log(php_response);
                    }
                    
                });

            },
            complete : function(){
                $.unblockUI();
            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }

        });
        

    }
   
    //Validar Conexion y Tipo De Esfera
    function ValidarConexionEsfera(Key, TipoPaso){
        var IdEsfera= Key;
        console.log("TipoPaso: ", TipoPaso);
                    
        if(TipoPaso == 8){
            var Accion= "Inicio IVR";
            $("#title_cargue").text(Accion+"': ");
            $("#ModalInicioFlujogramaIVR").modal('show');
        }else{
            //Consultar Existencia De Opcion Flecha
            let FormDataConx= new FormData();
            FormDataConx.append('IdEsfera', IdEsfera);

            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaOpcionExistente.php',
                type: "POST",
                dataType: "json",
                cache: false,
                processData: false,
                contentType: false,
                data: FormDataConx,
                success : function(php_response){
                    var Respuesta= php_response.msg;
                    //console.log("Respuesta: ", Respuesta);
                    if(Respuesta == "Ok"){
                        var DatosConexion= php_response.DatosConexion[0];
                        //console.log("DatosConexion: ", DatosConexion);
                        var IdOpcion= DatosConexion[1];
                        var PorDefecto= DatosConexion[2];

                        if((IdOpcion == null) && (PorDefecto == "0")){
                            swal({
                                icon: 'error',
                                title: '🤨  Oops...',
                                text: '¡Primero Debes Configurar La Opción, Dentro De La Conexión!',
                                confirmButtonColor: '#2892DB'
                            })
                        }else{ 
                            
                            ConsultarAccion(Key, IdOpcion, TipoPaso);
                            
                        }

                    }else if(Respuesta == "Nada"){
                        Swal.fire({
                            icon: 'info',
                            title: '❌  Oops... ',
                            text: '¡Debes Crear Una Conexión Antes De Configurar Esta Acción!',
                            confirmButtonColor: '#2892DB'
                        })
                        RecargarFlujograma(IdIVR);
                    }

                },
                error: function(php_response) {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Servidor!  😵',
                        text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                        confirmButtonColor: '#2892DB'
                    })
                    php_response = JSON.stringify(php_response);
                    console.log(php_response);
                }

            });
            
        }

    }
    
    //Guardar Esferas
    function CrearNuevaEsfera(IdIVR, JsonEsfera, IdEstrategia) {
        let FormularioNuevaEsfera= new FormData();
        var IdHuesped= <?=$_SESSION['HUESPED']?>;
        var Categoria = JsonEsfera.category;
        var TipoEsfera= JsonEsfera.tipoPaso;

        SaveIVR();
        var StringFlujograma= document.getElementById("SavedModelIVR").value;

        /*console.log("IdIVR: ", IdIVR);
        console.log("IdHuesped: ", IdHuesped);
        console.log("IdEstrategia: ", IdEstrategia);
        console.log("JsonEsfera: ", JsonEsfera);
        console.log('StringFlujograma', StringFlujograma); */

        FormularioNuevaEsfera.append('IdIVR', IdIVR);
        FormularioNuevaEsfera.append('IdHuesped', IdHuesped);
        FormularioNuevaEsfera.append('Categoria', Categoria);
        FormularioNuevaEsfera.append('TipoEsfera', TipoEsfera);
        FormularioNuevaEsfera.append('IdEstrategia', IdEstrategia);
        FormularioNuevaEsfera.append('StringFlujograma', StringFlujograma);

        
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarNuevaEsfera.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioNuevaEsfera,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            },
            //una vez finalizado correctamente
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);

                if(Respuesta == "Ok"){
                    alertify.success(" ¡Esfera Creada! 😉 ");
                    RecargarFlujograma(IdIVR);
                    
                }else{
                    alertify.error("Hubo un error al guardar la información de la seccion");
                }
            },
            error: function(data){
                if(data.responseText){
                    alertify.error("Hubo un error al guardar la información" + data.responseText);
                }else{
                    alertify.error("Hubo un error al guardar la información");
                }
            }
        });
        

    }
    
    //Guardar Accion y Esferas Por Defecto
    function GuardarEsferasAccion(IdIVR, IdOpcion, IdHuesped, IdEstrategia, JsonEsfera) {
        let FormularioEsfera= new FormData();

        FormularioEsfera.append('IdIVR', IdIVR);
        FormularioEsfera.append('IdOpcion', IdOpcion);
        FormularioEsfera.append('IdHuesped', IdHuesped);
        FormularioEsfera.append('IdEstrategia', IdEstrategia);

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarEsferasDefecto.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioEsfera,
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);

                CrearNuevaEsfera(IdIVR, JsonEsfera, IdEstrategia);

            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }
            
        });

    }

    //Crear IVR
    function CrearIVR(IdEstrategia, IdHuesped, JsonEsfera) {
        let FormularioIVR= new FormData();
        var NombreIVR= $("#InputNombreIVR").val();
        
        if((NombreIVR == null) || (NombreIVR == " ")){
            NombreIVR == "IVR ";
        }

        var NombreInterno= NombreIVR.replace(" ", "_");
        FormularioIVR.append('IdHuesped', IdHuesped);
        FormularioIVR.append('NombreIVR', NombreIVR);
        FormularioIVR.append('IdEstrategia', IdEstrategia);
        FormularioIVR.append('NombreInterno', NombreInterno);

        console.log("NombreIVR: ", NombreIVR);
        console.log("NombreInterno: ", NombreInterno);

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/GuardarNuevoIVR.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormularioIVR,
            success: function(php_response) {
                Respuesta= php_response.msg;
                console.log("Respuesta: ", Respuesta);

                if(Respuesta == "Ok"){
                    IdIVR= php_response.IdIVR;
                    IdOpcion= php_response.IdOpcion;
                    $("#IdIVR").val(IdIVR);

                    GuardarEsferasAccion(IdIVR, IdOpcion, IdHuesped, IdEstrategia, JsonEsfera);

                }else if (Respuesta == "Error"){
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error Al Guardar Información!  🤨',
                        text: 'Por Favor, Contactar Con El Area De Desarrollo...',
                        confirmButtonColor: '#2892DB'
                    })
                    console.log(php_response);
                }

            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }
            
        });

    }
    
    //Crear Esfera Nueva
    function CrearAccion(JsonEsfera){

        let IdEstrategia= $("#IdEstrategia").val();
        var IdHuesped= <?=$_SESSION['HUESPED']?>;
        var IdIVR= $("#IdIVR").val();

        if((IdIVR == "")||(IdIVR == null)){
            CrearIVR(IdEstrategia, IdHuesped, JsonEsfera);
        }else{
            CrearNuevaEsfera(IdIVR, JsonEsfera, IdEstrategia);
        }

    }

    //Validar Tipo De Flecha
    function ValidarConexionFlecha(IdFrom, IdTo) {
        let FormFlecha = new FormData();
        FormFlecha.append('IdFrom', IdFrom);
        FormFlecha.append('IdTo', IdTo);

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaTipoFlecha.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormFlecha,
            success : function(php_response){
                var DatosTipoFlecha= php_response.DatosTipoFlecha[0];
                //console.log("DatosTipoFlecha: ", DatosTipoFlecha);
                var TipoEsfera= DatosTipoFlecha[1];
                if((TipoEsfera == 7)||(TipoEsfera == 8)){
                    var TipoFlecha= 0;
                }else{
                    var TipoFlecha= 1;
                }

                if(TipoFlecha == 0){
                    //Consultar Datos Opcion
                    $("#TxtIdFrom").val(IdFrom);
                    $("#TxtIdTo").val(IdTo);
                    ConsultarOpcion(TipoFlecha);
                }else{
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Solo Se Pueden Modificar Las Conexiones Provenientes De "Inicio IVR" o "Captura Respuesta"',
                        confirmButtonColor: '#2892DB'
                    })
                }
                

            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }

        });
        
        
    }

    //Consulta Datos Flujograma IVR
    function ConsultarDatosFlujograma(IdIVR) {
        var FormDataId = new FormData();
        FormDataId.append('IdIVR', IdIVR);

        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaFlujogramaExistente.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: FormDataId,
            success : function(php_response){
                var SeccionesExistentes= php_response.DatosEsferasExistentes;
                var FlechasExistentes= php_response.DatosFlechas;
                console.log("SeccionesExistentes: ", SeccionesExistentes);
                console.log("FlechasExistentes: ", FlechasExistentes);

                
                let EstructuraSeccionesIVR = '';
                SeccionesExistentes.forEach( (item, index)=> {
                    
                    if(index != 0){
                        EstructuraSeccionesIVR += ',';
                    }

                    let Categoria= item[1];
                    switch (Categoria) {
                        case "1":
                            Categoria = 'NumeroExterno';
                            break;
                        case "2":
                            Categoria = 'PasarCampana';
                            break;
                        case "3":
                            Categoria = 'ReproducirGrabacion';
                            break;
                        case "4":
                            Categoria = 'OtroIVR';
                            break;
                        case "5":
                            Categoria = 'PasarEncuesta';
                            break;
                        case "7":
                            Categoria = 'TomaDigitos';
                            break;
                        case "8":
                            Categoria = 'InicioIVR';
                            break;
                        case "9":
                            Categoria = 'FinalIVR';
                            break;
                        case "11":
                            Categoria = 'Avanzado';
                            break;
                        default:
                            break;
                    }
                    //console.log("Categoria: ", Categoria);

                    var loc= item[2];
                    let Coordenadas = '';
                    if(loc){
                        Coordenadas = loc.replace(/"/g, "");
                    }else{
                        Coordenadas = loc;
                    }

                    EstructuraSeccionesIVR += `{"category": "${Categoria}", "nombrePaso": "${item[0]}", "active": -1, "figure":"Circle", "key": ${item[3]}, "loc":"${Coordenadas}", "tipoSeccion": "${item[1]}"}`;

                });
                //console.log("EstructuraSeccionesIVR: ", EstructuraSeccionesIVR);
                
                let EstructuraFlechasIVR = '';
                if(FlechasExistentes != undefined){
                    FlechasExistentes.forEach( (item, index)=> {
                        
                        if(index != 0){
                            EstructuraFlechasIVR += ',';
                        }

                        let CoordenadaFlecha= '';
                        if(item[3]){
                            CoordenadaFlecha= item[3].replace(/"/g, "");
                        }else{
                            CoordenadaFlecha= item[3];
                        }
                        EstructuraFlechasIVR += `{"from": ${item[1]}, "to": ${item[2]}, "fromPort": "${item[4]}", "toPort": "${item[5]}", "visible": true, "points": "${CoordenadaFlecha}", "text": "${item[0]}", "generadoPorSistema": "${item[6]}"}`;

                    });
                    //console.log("EstructuraFlechasIVR: ", EstructuraFlechasIVR);  
                }

                //Estructura Flujograma
                let Estructura = `{
                    "class": "go.GraphLinksModel",
                    "linkFromPortIdProperty": "fromPort",
                    "linkToPortIdProperty": "toPort",
                    "nodeDataArray": [
                        ${EstructuraSeccionesIVR}
                    ],
                    "linkDataArray": [
                        ${EstructuraFlechasIVR}
                    ]
                }`;

                $("#SavedModelIVR").val(Estructura);
                initIvr();
                
            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }

        });

    }

    //Consulta Esferas Por Defecto
    function ConsultarFlujogramaDefecto() {
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/ControllerFlujograma/ConsultaEsferasDefecto.php',
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            success : function(php_response){
                var SeccionesPorDefecto= php_response.DatosEsferasDefecto;
                //console.log("SeccionesPorDefecto: ", SeccionesPorDefecto);

                let EstructuraSeccionesIVR = '';
                let EstructuraSeccionesIVRFlechas = '';
                SeccionesPorDefecto.forEach( (item, index)=> {
                    
                    if(index != 0){
                        EstructuraSeccionesIVR += ',';
                    }

                    let Categoria= item[1];
                    //console.log("Categoria: ", Categoria);
                    switch (Categoria) {
                        case "8":
                            Categoria = 'InicioIVR';
                            break;
                        case "9":
                            Categoria = 'FinalIVR';
                            break;
                        default:
                            break;
                    }

                    var loc= item[2];
                    let Coordenadas = '';
                    if(loc){
                        Coordenadas = loc.replace(/"/g, "");
                    }else{
                        Coordenadas = loc;
                    }
                    //console.log("Coordenadas: ", Coordenadas);

                    //EstructuraSeccionesIVR += `{"category": "${Categoria}", "nombrePaso": "${item.nombre}", "active": -1, "figure":"Circle", "key": ${item.id}, "autoresId": ${item.autorespuestaId}, "loc":"${Coordenadas}", "tipoSeccion": "${item.tipo_seccion}"}`;
                    EstructuraSeccionesIVR += `{"category": "${Categoria}", "nombrePaso": "${item[0]}", "active": -1, "figure":"Circle", "key": ${item[3]}, "loc":"${Coordenadas}", "tipoSeccion": "${item[1]}"}`;

                });
                console.log("EstructuraSeccionesIVR: ", EstructuraSeccionesIVR);

                let estructura = `{
                    "class": "go.GraphLinksModel",
                    "linkFromPortIdProperty": "fromPort",
                    "linkToPortIdProperty": "toPort",
                    "nodeDataArray": [
                        ${EstructuraSeccionesIVR}
                    ],
                    "linkDataArray": [
                        ${EstructuraSeccionesIVRFlechas}
                    ]
                }`;

                $("#SavedModelIVR").val(estructura);
                
                initIvr();
                
            },
            error: function(php_response) {
                Swal.fire({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Area De Desarrollo...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }

        });

    }

    //Desplegar Modal Esferas
    function DesplegarModalExistente(IdOpcion, TipoPaso) {
        var TipoPaso= parseInt(TipoPaso);
        console.log("TipoPaso: ", TipoPaso);

        if(TipoPaso == 1){
            var Accion= "Numero Externo";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").show();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
            $("#divNombreAccion").show();
            $("#divEsferaFinal").hide();
        }else if(TipoPaso == 2){
            var Accion= "Pasar a Una Campaña";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").show();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
            $("#divEsferaFinal").hide();
        }else if(TipoPaso == 3){
            var Accion= "Reproducir Grabación";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").show();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
            $("#divNombreAccion").show();
            $("#divEsferaFinal").hide();
        }else if(TipoPaso == 4){
            var Accion= "Pasar a Otro IVR";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").show();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
            $("#divNombreAccion").show();
            $("#divEsferaFinal").hide();
        }else if(TipoPaso == 5){
            var Accion= "Pasar a Encuesta";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").show();
            $("#divAvanzadoIVR_2").hide();
            $("#divNombreAccion").show();
            $("#divEsferaFinal").hide();
        }else if(TipoPaso == 7){
            var Accion= "Captura Digito";
            $("#SelectOpcionNumero_2").show();
            $("#ModalTomaDeDigitos").modal('show');
        }else if(TipoPaso == 9){
            var Accion= "Final IVR";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
            $("#divNombreAccion").hide();
            $("#divEsferaFinal").show();
        }else if(TipoPaso == 11){
            var Accion= "Avanzado";
            $("#TltOpcion_2").text(Accion);
            $("#IdOpcion_3").val(IdOpcion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").show();
            $("#divEsferaFinal").hide();
            $("#ModalAvanzado").modal('show');
        }

        NombreAccion= $("#NombreAccion").val();
        if((NombreAccion == null) || (NombreAccion == "")){
            $("#NombreAccion").val(Accion);
        }
       
        //console.log("Accion: ", Accion);
        if((Accion == "Captura Digito")||(Accion == "Avanzado")){
            $("#ModalAccionesFlujograma").modal('hide');
        }else{
            $("#IdOpcion_3").val(IdOpcion);
            $("#ModalAccionesFlujograma").modal('show');
        }


    }
    
    //Desplegar Modales Acciones
    function DesplegarModal(IdSeccion){

        let IdPaso = $("#IdEstrategia").val();
                    
        if(IdSeccion == 1){
            var Accion= "Numero Externo";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").show();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
        }else if(IdSeccion == 2){
            var Accion= "Pasar a Una Campaña";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").show();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
        }else if(IdSeccion == 3){
            var Accion= "Reproducir Grabación";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").show();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
        }else if(IdSeccion == 4){
            var Accion= "Pasar a Otro IVR";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").show();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").hide();
        }else if(IdSeccion == 5){
            var Accion= "Pasar a Encuesta";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").show();
            $("#divAvanzadoIVR_2").hide();
        }else if(IdSeccion == 11){
            var Accion= "Avanzado";
            $("#TltOpcion_2").text(Accion);
            $("#divNumeroExterno_2").hide();
            $("#divCampana_2").hide();
            $("#divGraba_2").hide();
            $("#divListaIVR_2").hide();
            $("#divEncuesta_2").hide();
            $("#divAvanzadoIVR_2").show();
            $("#ModalAvanzado").modal('show');
        }else if(IdSeccion == 7){
            var Accion= "Captura Digito";
            $("#SelectOpcionNumero_2").show();
            $("#ModalTomaDeDigitos").modal('show');
        }

        $("#NombreAccion").val(Accion);
        console.log("Accion: ", Accion);
        if((Accion == "Captura Digito")||(Accion == "Avanzado")){
            $("#ModalAccionesFlujograma").modal('hide');
        }else{
            $("#ModalAccionesFlujograma").modal('show');
        }

    }


    //Mostrar Campo Cual
    $("#SelectOpcionNumero_2").on('change', function(){
        var Opcion = $(this).val();
        console.log(Opcion);
        if(Opcion == "X"){
            $("#divTxtCual").show();
        }else{
            $("#divTxtCual").hide();
        }
    });

    //Agregar Fila a Tabla Avanzado
    let Contador= 0;
    $("#BtnAgregarNuevo").on('click', function(){
        
        Contador++;
        var Row= $("<tr id='FilaAvanzado_"+Contador+"' style='text-align: center;'>");
        //var CampoNombre= $('<td class="col-md-2"></td>');
        var CampoEtiqueta= $('<td class="col-md-2"><input type="text" class="form-control" id="Etiqueta_'+Contador+'" placeholder="Etiqueta"></td>');
        var CampoAplicacion= $('<td class="col-md-3"><select id="Aplicacion_'+Contador+'" class="form-control"><option disabled selected>Elige Una Opción</option><?php echo $ListaAplicaciones; ?></select></td>');
        var CampoParametros= $('<td class="col-md-3"><input type="text" class="form-control" id="Parametros_'+Contador+'" placeholder="Parámetros"></td>');
        
        //Row.append(CampoNombre);
        Row.append(CampoEtiqueta);
        Row.append(CampoAplicacion);
        Row.append(CampoParametros);

        $("#TblAvanzado").append(Row);
        $("#IdAccion_4").val("");

    });


    //Funciones Al Cargar El Flujograma IVR's 
    $(document).ready(function(){
        //Control Scrol Modal SubModal
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal:visible').length && $(document.body).addClass('modal-open');
        });

    });


</script>

