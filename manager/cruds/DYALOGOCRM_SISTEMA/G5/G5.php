<style type="text/css">
    .embed-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }

    .embed-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .modal {
        overflow: auto !important;
    }

    .camsubf {
        border-top: 2px solid #3c8dbc;
    }

    .optionsave {
        margin-left: 5% !important;
    }

    /* Centra el input personalizado con el texto */
    .custom-radio-checkbox {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        font-family: arial;
        font-weight: 400 !important;
    }

    .sidebar-mini {
        padding-right: 0px !important;
    }

    /* Modificador para dar la imagen de checkbox */
    .custom-radio-checkbox__show--checkbox {
        background-image: url('<?=base_url?>assets/img/checkbox-uncheck.png');
    }

    /* Oculta input original */
    .custom-radio-checkbox>.custom-radio-checkbox__input {
        display: none;
    }

    /* Radio personalizado usando <span> */
    .custom-radio-checkbox>.custom-radio-checkbox__show {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: .5rem;
        background-size: cover;
    }

    /* Cambia el checkbox personalizado cuando es pulsado */
    .custom-radio-checkbox>.custom-radio-checkbox__input:checked+.custom-radio-checkbox__show--checkbox {
        background-image: url("<?=base_url?>assets/img/checkbox-check.png");
    }

</style>
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="idFrame">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php";
   $url_crud_extender = base_url."cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN

    if(isset($_GET['tipo'])){
        if($_GET['tipo'] == '02f0858f054629ab02fea45f8d262b83'){
            $_GET['tipo']='2';
        }elseif($_GET['tipo']=='73dc7ba1230f53df37ed3b07e5d22464'){
            $_GET['tipo']='1';
        }elseif($_GET['tipo']=='44ab9263e401d99a1c3ffa8cf8fb948a'){
            $_GET['tipo']='3';
        }elseif($_GET['tipo']=='3eeb959370f7f7edb745d16ce74fb0f1'){
            $_GET['tipo']='4';
        }else{ ?>
            <script>
                window.location.href="<?=base_url?>modulo/error";
            </script>
        <?php }
        $Zsql = "SELECT G5_ConsInte__b as id,  G5_C28 as camp1 , G5_C29 as camp2  FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = ".$_GET['tipo']." AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC LIMIT 0, 50";
    }else{
        $Zsql = "SELECT G5_ConsInte__b as id, G5_C28 as camp1 , G5_C29 as camp2 FROM ".$BaseDatos_systema.".G5 WHERE G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC LIMIT 0, 50";
    }



   $result = $mysqli->query($Zsql);

?>

<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_guiones_;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li><?php echo $str_guion_; ?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php } ?>
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">

                </div>
            </div>
            <div class="box-body">
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
                        <br />
                        <!-- FIN BUSQUEDA EN LA LISTA DE NAVEGACION-->

                        <!-- LISTA DE NAVEGACION -->
                        <div class="table-responsive no-padding" id="txtPruebas" style="height:553px; overflow-x:hidden; overflow-y:scroll;">
                            <table class="table table-hover" id="tablaScroll">

                                <?php

                                    while($obj = $result->fetch_object()){
                                        $tipo = $str_Script______g_;
                                        if($obj->camp2 == 2){
                                            $tipo = $str_Guion_______g_;
                                        }elseif($obj->camp2 == 3){
                                            $tipo = $str_Otros_______g_;
                                        }

                                        echo "<tr class='CargarDatos' id='".url::urlSegura($obj->id)."'>
                                                <td>
                                                    <p style='font-size:14px;'><b>{$obj->camp1}</b></p>
                                                    <p style='font-size:12px; margin-top:-10px;'>".mb_strtoupper($tipo)."</p>
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
                            <button class="btn btn-default" id="delete">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button class="btn btn-default" id="edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-default" id="Save" disabled>
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel" disabled>
                                <i class="fa fa-close"></i>
                            </button>
                            <?php if(!isset($_SESSION['no_admin'])){ ?>
                            <a class="btn btn-default" title="Vista Previa" id="vistaPrevia" target="_blank" href="#">
                                <i class="fa  fa-eye"></i>
                            </a>
                            <a target="_blank" href="#" class="btn btn-default" title="Ver Datos" id="verDatosGuion">
                                <i class="fa  fa-table"></i>
                            </a>
                            <?php } ?>
                            <?php if ($_GET['tipo'] == 3) { ?>
                                    <button title="recordLoader" class="btn btn-default" id="recordLoader">
                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                    </button>
                            <?php } ?>
                        </div>
                        <?php }else{ ?>
                        <div class="col-md-12" id="div_formularios">
                            <div>
                                <button class="btn btn-default" id="Save">
                                    <i class="fa fa-save"></i>
                                </button>
                                <button class="btn btn-default" id="cancel">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                            <?php } ?>
                            <!-- FIN BOTONES -->
                            <!-- CUERPO DEL FORMULARIO CAMPOS-->
                            <br />
                            <div>
                                <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                                    <input type="hidden" name="txtOrdenData" id="txtOrdenData" value="0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title" id="h3mio">

                                                    </h4>
                                                </div>
                                                <div class="box-body">
                                                    <div class="row">
                                                        <input type="hidden" name="G5_C316" id="G5_C316" value="<?php echo $_SESSION['HUESPED'];?>">

                                                        <?php if(isset($_GET['tipo'])){?>
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G5_C28" id="LblG5_C28"><?php echo $str_nombre______g_;?></label>
                                                                <input type="text" disabled class="form-control input-sm" id="G5_C28" value="" name="G5_C28" placeholder="<?php echo $str_nombre______g_;?>">
                                                            </div>
                                                            <input type="hidden" name="G5_C29" id="G5_C29" value="<?php echo $_GET['tipo'];?>">
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                        </div>
                                                        <?php }else{ ?>
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G5_C28" id="LblG5_C28"><?php echo $str_nombre______g_;?></label>
                                                                <input type="text" disabled class="form-control input-sm" id="G5_C28" value="" name="G5_C28" placeholder="<?php echo $str_nombre______g_;?>">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                        </div>
                                                        <div class="col-md-12 col-xs-12" style="display: none;">
                                                            <!-- CAMPO DE TIPO LISTA -->
                                                            <div class="form-group">
                                                                <label for="G5_C29" id="LblG5_C29"><?php echo $str_tipo________g_; ?></label>
                                                                <select disabled class="form-control input-sm str_Select2" style="width: 100%;" name="G5_C29" id="G5_C29">
                                                                    <option value="0"><?php echo $str_seleccione;?></option>
                                                                    <option value="1"><?php echo $str_Script______g_;?></option>
                                                                    <option value="2"><?php echo $str_Guion_______g_;?></option>
                                                                    <option value="3"><?php echo $str_Otros_______g_;?></option>
                                                                </select>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div>
                                                        <?php } ?>
                                                        <div class="col-md-12">
                                                            <span class="help-block">
                                                                <a href="" id="rutaWebForm"></a>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO MEMO -->
                                                            <div class="form-group">
                                                                <label for="G5_C30" id="LblG5_C30"> <?php echo $str_observacion_g_;?></label>
                                                                <textarea disabled class="form-control input-sm" name="G5_C30" id="G5_C30" value="" placeholder="<?php echo $str_meage_observa_;?>"></textarea>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO MEMO -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_24">
                                                            <?php echo $str_Secciones_E_g_; ?>
                                                        </a>
                                                    </h3>
                                                    <div class="box-tools">

                                                    </div>
                                                </div>
                                                <div id="s_24" class="panel-collapse collapse ">
                                                    <div class="box-body">
                                                        <div id="secciones_creadas">

                                                        </div>
                                                        <button class="btn btn-primary pull-right" title="<?php echo $str_add_seccion___; ?>" id="btnAddMoreSeccion" type="button">
                                                            <i class="fa fa-plus">&nbsp;<?php echo $str_add_seccion___; ?></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_25">
                                                            <?php echo $str_Vanzadao__E_g_; ?>
                                                        </a>
                                                    </h3>
                                                </div>
                                                <div id="s_25" class="panel-collapse collapse ">
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="G5_C31" id="LblG5_C31"><?php echo $str_campo_princ_g_;?></label>
                                                                    <select class="form-control input-sm" style="width: 100%;" name="G5_C31" id="G5_C31">
                                                                        <option value="0"></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-xs-12">
                                                                <div class="form-group">
                                                                    <label for="G5_C59" id="LblG5_C59"><?php echo $str_campo_segun_g_;?></label>
                                                                    <select class="form-control input-sm str_Select2" style="width: 100%;" name="G5_C59" id="G5_C59">
                                                                        <option value="0"></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="primario" id="primario">
                                                            <input type="hidden" name="segundario" id="segundario">
                                                        </div>
                                                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 2) { ?>
                                                            <div class="row">
                                                                <div class="col-md-6 col-xs-12">
                                                                    <div class="form-group">
                                                                        <label for="G5_C319">Campo llave</label>
                                                                        <select class="form-control input-sm str_Select2" style="width: 100%;" name="G5_C319" id="G5_C319">
                                                                            <option value="0">Seleccione</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <hr />

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p style="text-align: justify; font-size: 16px;"><?php echo $str_label_guion_prinSe; ?></p>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                            </div>

                                                            <div class="col-md-4">
                                                                <img src="<?=base_url?>assets/img/imagen3.jpeg" style="width: 100%;">
                                                                <p style="text-align: justify; font-size: 12px;"><?php echo $str_label_guion_prinS2; ?></p>
                                                            </div>

                                                            <div class="col-md-4">
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>


                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_23">
                                                            <?php echo $str_Saltos____E_g_; ?>
                                                        </a>
                                                    </h3>
                                                    <div class="box-tools">

                                                    </div>
                                                </div>
                                                <div id="s_23" class="panel-collapse collapse ">
                                                    <div class="box-body">
                                                        <div id="cuerpoSalto">

                                                        </div>
                                                        <button class="btn btn-primary pull-right" title="<?php echo $str_add_salto______; ?>" id="btnAddmoreJumps" type="button">
                                                            <i class="fa fa-plus">&nbsp;<?php echo $str_add_salto______; ?></i>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                               
                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_seccion">
                                                            Saltos por sección
                                                        </a>
                                                    </h3>
                                                    <div class="box-tools">

                                                    </div>
                                                </div>
                                                <div id="s_seccion" class="panel-collapse collapse ">
                                                    <div class="box-body">
                                                        <div id="cuerpoSaltoSeccion">

                                                        </div>
                                                        <button class="btn btn-primary pull-right" title="<?php echo $str_add_salto______; ?>" id="btnAddJumpSeccion" type="button">
                                                            <i class="fa fa-plus">&nbsp;<?php echo $str_add_salto______; ?></i>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                            
                                            <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 1 || $_GET['tipo'] == 4) { ?>
                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_tip">
                                                            Requeridos por tipificación.
                                                        </a>
                                                    </h3>
                                                    <div class="box-tools">

                                                    </div>
                                                </div>
                                                <div id="s_tip" class="panel-collapse collapse ">
                                                    <input type="hidden" name="iterTip" id="iterTip" value="0">
                                                    <input type="hidden" name="idTip" id="idTip" value="0">
                                                    <div class="box-body">
                                                        <div id="cuerpoTip">
                                                            <div class="row">
                                                                <div class="col-md-5 col-xs-5">
                                                                    <div class="form-group">
                                                                        <label>Tipificación</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5 col-xs-5">
                                                                    <div class="form-group">
                                                                        <label>Campo</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-2">
                                                                    <div class="form-group">
                                                                        <label>Eliminar</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-primary pull-right" title="<?php echo $str_add_salto______; ?>" id="btnAddTip" type="button">
                                                            <i class="fa fa-plus">&nbsp;Agregar Requerido</i>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php } ?>
                                            
                                            <!-- SECCIÓN PARA EJEMPLO DE CONSUMO PARA LEER LEDS -->
                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_iws">
                                                            Ejemplo de consumo de servicio para leer datos
                                                        </a>
                                                    </h3>
                                                    <div class="box-tools">

                                                    </div>
                                                </div>
                                                <div id="s_iws" class="panel-collapse collapse ">
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">EJEMPLO DE COMO SE DEBE HACER EL CONSUMO</label>
                                                                    <textarea class="form-control" name="modelWs" id="modelWS" cols="30" rows="15" style="width: 80%" readonly>
                                                                    </textarea>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">EJEMPLO DEL JSON QUE RETORNA</label>
                                                                    <textarea class="form-control" name="intentoWS" id="intentoWS" cols="30" rows="15" style="width: 80%" readonly>
                                                                    </textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="campOpcion">Opciones de los campos tipo lista</label>
                                                                    <select name="campOpcion" id="campOpcion" class="form-control input-sm" onchange="opcionLista()" style="margin-bottom: 20px; width:80%">
                                                                    <textarea class="form-control" name="opcionesLista" id="opcionesLista" cols="30" rows="15" style="width: 80%" readonly>
                                                                    </textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            
                                            <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 1 || $_GET['tipo'] == 4) { ?>
                                            <!-- SECCIÓN PARA INTEGRAR CON APLICACIONES EXTERNAS -->
                                            <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_wsExterno">
                                                            Invocacion de aplicaciones externas
                                                        </a>
                                                    </h3>
                                                </div>
                                                <div id="s_wsExterno" class="panel-collapse collapse ">
                                                    <input type="hidden" name="iterWS" id="iterWS" value="0">
                                                    <input type="hidden" id="idWS" value="0">
                                                    <div class="box-body">
                                                        <div id="cuerpoWS">
                                                            <div class="row">
                                                                <div class="col-md-4 col-xs-6">
                                                                    <div class="form-group">
                                                                        <label>Nombre</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4 col-xs-6">
                                                                    <div class="form-group">
                                                                        <label>Web Service</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-2">
                                                                    <div class="form-group">
                                                                        <label>Metodo de integración</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-xs-2">
                                                                    <div class="form-group">
                                                                        <label>Acciones</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-primary pull-right" title="Nueva integración" id="addWS" type="button">
                                                            <i class="fa fa-plus">Agregar integración</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            

                                            <?php // if(isset($_GET['tipo']) && $_GET['tipo'] == 2) { ?>
                                            <!-- <div class="panel box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_22">
                                                            <?php // echo $str_config_web_f__; ?>
                                                        </a>
                                                    </h3>
                                                    <div class="box-tools">

                                                    </div>
                                                </div>
                                                <div id="s_22" class="panel-collapse collapse ">
                                                    <div class="box-body">
                                                        <div id="nuevosConfiguraciones">

                                                        </div>
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" id="captcha" value="0">
                                                                    Activar validación por ReCaptcha
                                                                </label>

                                                            </div>
                                                        </div>
                                                        <!-- <button class="btn btn-primary pull-right" title="<?php //echo $str_mas_config_wf_; ?>" id="btnAddMoreConfig" type="button">
                                                        <i class="fa fa-plus">&nbsp;<?php //echo $str_mas_config_wf_; ?></i>
                                                    </button> -->
                                                    <!-- </div> -->
                                                <!-- </div> -->
                                            <!-- </div> -->
                                            <?php //} ?>
                                            <!-- SECCION : PAGINAS INCLUIDAS -->
                                            <input type="hidden" name="id" id="hidId" value='0'>
                                            <input type="hidden" name="oper" id="oper" value='add'>
                                            <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>'>
                                            <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>'>
                                            <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>'>
                                        </div>
                                    </div>
                                </form>
                                <!-- SI ES MAESTRO - DETALLE CREO LAS TABS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>

<div class="modal fade-in" id="cargarInformacion" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <input type="hidden" name="fallasValidacion" id="fallasValidacion" value="0">
    <input type="hidden" name="strFechaInicial_t" id="strFechaInicial_t" value="">
    <input type="hidden" name="intIdCampana_t" id="intIdCampana_t" value="">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_cargue">Cargar datos desde archivo</h4>
            </div>
            <div class="modal-body">
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

<script type="text/javascript" src="<?=base_url?>assets/js/jStepper.js"></script>

<div class="modal fade" id="NuevaGuionModal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_new_________g_; ?></h4>
            </div>
            <div class="modal-body">
                <form id="nuevoGuion">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_nombre______g_; ?></label>
                                <input type="text" name="G5_C28" id="txtNombreGuion" class="form-control" placeholder="<?php echo $str_nombre______g_; ?>">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="G5_C316" id="G5_C316_modal" value="<?php echo $_SESSION['HUESPED'];?>">
                    <?php if(isset($_GET['tipo'])) { ?>
                    <input type="hidden" name="G5_C29" id="G5_C29_1" value="<?php echo $_GET['tipo'];?>">
                    <?php }else{ ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_tipo________g_; ?></label>
                                <select name="G5_C29" id="comboSelectTipo" class="form-control">
                                    <option value="0"><?php echo $str_seleccione;?></option>
                                    <option value="1"><?php echo $str_Script______g_;?></option>
                                    <option value="2"><?php echo $str_Guion_______g_;?></option>
                                    <option value="3"><?php echo $str_Otros_______g_;?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php } ?>


                    <div class="row">
                       <?php if($_GET['tipo'] == 3) : ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="GenerarFromExel" id="GenerarFromExel" value="1">&nbsp;<?php echo $str_generar_excel_;?>
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-12 excel" style="display: none;">
                            <div class="form-group">
                                <label><?php echo $str_archivr_excel_;?></label>
                                <input type="file" name="newGuionFile" id="newGuionFile" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12 excel2" style="display: none;">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="CrearScript" id="CrearScript" value="1">&nbsp;<?php echo $str_importr_excel_;?>
                                </label>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default " type="button" id="cancelarModal" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>
                <button class="btn-primary btn pull-right" type="button" id="guardarNewGuion">
                    <?php echo $str_guardar_2;?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="PreguntarGenerar_Base_Datos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4>
                    <?php echo $str_message_genreY; ?>
                </h4>
            </div>
            <div class="modal-body">
                <p>
                <h4 class="modal-title" id="idTitle"><?php echo $str_message_genera; ?></h4>
                </p>
                <br />
                <div class="panel">
                    <div class="box-header ">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#s_Y2">
                                <?php echo $str_avanz_campan; ?>
                            </a>
                        </h4>
                    </div>
                    <div id="s_Y2" class="panel-collapse collapse">
                        <div clss="box-body">
                            <form id="validatorGenerador">
                                <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkFormBackoffice" value="1"><?php echo $str_message_gener3; ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkGenerar" value="1"><?php echo $str_message_gener4; ?>
                                        </label>
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkBusqueda" value="1"><?php echo $str_message_gener1; ?>
                                        </label>
                                        <div class="form-group">
                                            <ul class="sidebar-menu optionsave">
                                                <li>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="1" name="TipoBusqManual">Buscar en toda la base de datos
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="2" name="TipoBusqManual">Buscar sobre los registros incluidos en la campaña
                                                        </label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" value="3" name="TipoBusqManual">Buscar en los registros incluidos en la campaña y asignados al agente
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkWebForm" value="1"><?php echo $str_message_gener2; ?>
                                        </label>
                                    </div>
                                </div> -->
                                <?php } else { ?>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkFormBackoffice" value="1"><?php echo $str_message_gener3; ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkGenerar" value="1"><?php echo $str_message_gener4; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php } ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn " type="button" id="generarTodoLoqueHalla">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="crearSeccionesModal" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close limpiarSecciones" data-dismiss="modal" id="refrescarImagenes">&times;</button>
                <h4 class="modal-title"><?php echo $str_Secciones___g_; ?></h4>
            </div>
            <div class="modal-body">
                <form id="crearseccionesModalForm">
                    <div class="panel">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="G7_C33" id="LblG7_C33"><?php echo $str_nombre______S_;?></label>
                                    <input type="text" class="form-control input-sm" id="G7_C33" value="" name="G7_C33" placeholder="<?php echo $str_nombre______S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="G7_C35" id="LblG7_C35"><?php echo $str_Numcolumns__S_;?></label>
                                    <input type="text" class="form-control input-sm Numerico" value="2" name="G7_C35" id="G7_C35" placeholder="<?php echo $str_Numcolumns__S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="G7_C33" id="LblG7_C38">TIPO DE SECCIÓN</label>
                                    <select class="form-control input-sm str_Select2" style="width: 100%;" name="G7_C38" id="G7_C38">
                                        <option value="1">Normal</option>
                                        <!-- JDBD secciones calidad solo para script no BD-->
                                        <?php if (isset($_GET["tipo"]) && $_GET["tipo"] == 1) { ?>
                                        <option value="2">Calidad</option>
                                        <?php } ?>
                                        <option value="5">Agendamiento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="G7_C36" id="LblG7_C36"><?php echo $str_apariencia__S_;?></label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;" name="G7_C36" id="G7_C36">
                                    <option value="0"><?php echo $str_seleccione; ?></option>
                                    <option value="3" selected="true"><?php echo $str_seccion_a_4_s_; ?></option>
                                    <option value="1"><?php echo $str_seccion_a_1_s_; ?></option>
                                    <option value="2"><?php echo $str_seccion_a_2_s_; ?></option>

                                    <option value="4"><?php echo $str_seccion_a_3_s_; ?></option>
                                    <option value="5"><?php echo $str_seccion_a_5_s_; ?></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="cerradoAcordeon">
                                    <label for="G7_C37" id="LblG7_C37" style="visibility: hidden;"><?php echo $str_apariencia__S_;?></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="-1" name="G7_C37" id="G7_C37"> <?php echo $str_minimizada__S_;?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="agenda" hidden>
                            <div class="col-md-3">
                                <label for="idAgendador">Agendador a usar</label>
                                <select name="idAgendador" id="idAgendador" class="form-control" style="width: 100%;"></select>
                                <a href="#" data-toggle="modal" data-target="#nuevoAgendador" class="pull-left" id="newAgendador">Nuevo</a>
                                <a href="#" data-toggle="modal" data-target="#nuevoAgendador" class="pull-right" id="editAgendador">Editar</a>
                            </div>
                            <div class="col-md-4">
                                <label for="ccAgendador" style="font-size: 13px;">Campo identificación en el formulario que se está configurando</label>
                                <select name="ccAgendador" id="ccAgendador" class="form-control" style="width: 100%;"></select>
                            </div>
                            <div class="col-md-2">
                                <label for="" style="color: white;">...</label>
                                <button class="btn btn-primary form-control" id="crono" onclick="generaAgenda()" disabled>Generar campos</button>
                            </div>
                        </div>

                    </div>
                    <div class="panel" id="cuerpoSeccion">
                        <h3><?php echo $str_nombre_CamP___;?></h3>
                        <hr />
                        <div class="row" style="text-align: center;">
                            <div class="col-md-4">
                                <label>
                                    <?php echo $str_campo_texto___; ?>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <?php echo $str_campo_tipo____; ?>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <?php echo $str_campo_lista___; ?>
                                </label>
                            </div>
                            <!-- <div class="col-md-1 busquedaCampo">
                                <label>
                                    <?php echo $str_campo_busqu___; ?>
                                </label>
                            </div> -->
                            <div class="col-md-1">

                            </div>
                        </div>
                        <div id="cuerpoTablaQuesedebeGuardar">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="orderCamposCrearSecciones" id="orderCamposCrearSecciones">
                <button class="btn btn-success  pull-left" type="button" id="newFieldSeccion">
                    <?php echo $str_control_save__;?>
                </button>
                <button class="btn-primary btn" type="button" id="crearSeccionesModalButton">
                    <?php echo $str_seccion_save__;?>
                </button>
                <button class="btn btn-default pull-right limpiarSecciones" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="nuevoAgendador" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Nuevo agendador</h4>
            </div>
            <div class="modal-body">
                <div class="row" id="seccionWS">
                    <iframe src="#" id="iframeAgenda" style="width: 100%; height:835px; margin-top: 10px;" marginwidth="0" frameborder="0"></iframe>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarSeccionesModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close limpiarSecciones" data-dismiss="modal" id="refrescarImagenes">&times;</button>
                <h4 class="modal-title"><?php echo $str_Secciones___g_; ?></h4>
            </div>
            <div class="modal-body">
                <form id="editarseccionesModalForm">
                    <div class="panel">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="EditarG7_C33" id="LblEditarG7_C33"><?php echo $str_nombre______S_;?></label>
                                    <input type="text" class="form-control input-sm" id="EditarG7_C33" value="" name="EditarG7_C33" placeholder="<?php echo $str_nombre______S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="EditarG7_C35" id="LblEditarG7_C35"><?php echo $str_Numcolumns__S_;?></label>
                                    <input type="text" class="form-control input-sm Numerico" value="2" name="EditarG7_C35" id="EditarG7_C35" placeholder="<?php echo $str_Numcolumns__S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="EditarG7_C38" id="LblEditarG7_C38">TIPO DE SECCIÓN</label>
                                    <select class="form-control input-sm str_Select2" style="width: 100%;" name="EditarG7_C38" id="EditarG7_C38">
                                        <option value="1">Normal</option>
                                        <!-- JDBD secciones calidad solo para script no BD-->
                                        <?php if (isset($_GET["tipo"]) && $_GET["tipo"] == 1) { ?>
                                        <option value="2">Calidad</option>
                                        <?php } ?>
                                        <option value="5">Agendamiento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="EditarG7_C36" id="LblEditarG7_C36"><?php echo $str_apariencia__S_;?></label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;" name="EditarG7_C36" id="EditarG7_C36">
                                    <option value="0"><?php echo $str_seleccione; ?></option>
                                    <option value="3" selected="true"><?php echo $str_seccion_a_4_s_; ?></option>
                                    <option value="1"><?php echo $str_seccion_a_1_s_; ?></option>
                                    <option value="2"><?php echo $str_seccion_a_2_s_; ?></option>

                                    <option value="4"><?php echo $str_seccion_a_3_s_; ?></option>
                                    <option value="5"><?php echo $str_seccion_a_5_s_; ?></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="EditarcerradoAcordeon">
                                    <label for="G7_C37" id="LblG7_C37" style="visibility: hidden;"><?php echo $str_apariencia__S_;?></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="-1" name="EditarG7_C37" id="EditarG7_C37"> <?php echo $str_minimizada__S_;?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row" id="agendaE" hidden>
                            <div class="col-md-3">
                                <label for="idAgendador2">Agendador a usar</label>
                                <select name="idAgendador2" id="idAgendador2" class="form-control input-sm" style="width: 100%;" disabled></select>
                            </div>
                            <div class="col-md-4">
                                <label for="ccAgendador2" style="font-size: 13px;">Campo identificación en el formulario que se está configurando</label>
                                <select name="ccAgendador2" id="ccAgendador2" class="form-control input-sm" style="width: 100%;" disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="panel" id="cuerpoSeccion">
                        <h3><?php echo $str_nombre_CamP___;?></h3>
                        <hr />
                        <div class="row" style="text-align: center;">
                            <div class="col-md-4">
                                <label>
                                    <?php echo $str_campo_texto___; ?>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <?php echo $str_campo_tipo____; ?>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <?php echo $str_campo_lista___; ?>
                                </label>
                            </div>
                            <!-- <div class="col-md-1 busquedaCampo">
                                <label>
                                    <?php echo $str_campo_busqu___; ?>
                                </label>
                            </div> -->
                            <div class="col-md-1">

                            </div>
                        </div>
                        <div id="cuerpoTablaQuesedebeGuardarEdicion">

                        </div>
                    </div>
                    <div id="edicionCamposPregui" style="display: none;">

                    </div>
                    <input type="hidden" name="IdseccionEdicion" id="IdseccionEdicion" value="0">
                    <input type="hidden" name="contadorEditablesPreguntas" id="contadorEditablesPreguntas" value="0">
                    <input type="hidden" name="orderCamposCrearSeccionesEdicion" id="orderCamposCrearSeccionesEdicion">
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success  pull-left" type="button" id="newFieldSeccion2">
                    <?php echo $str_control_save__;?>
                </button>
                <button class="btn-primary btn" type="button" id="editarSeccionesModalButton">
                    <?php echo $str_seccion_save__;?>
                </button>
                <button class="btn btn-default pull-right limpiarSecciones" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="NuevaListaModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_Lista_datos___; ?></h4>
            </div>
            <div class="modal-body">
                <form id="agrupador">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $str_Lista_nombre__; ?></label>
                                <input type="text" name="txtNombreLista" id="txtNombreLista" class="form-control" placeholder="<?php echo $str_Lista_nombre__; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="listExcel">
                                    <input type="checkbox" value="0" name="listExcel" id="listExcel" data-error="Before you wreck yourself">Cargar desde excel
                                </label>
                                <input type="file" class="form-control" id="arExcel" name="arExcel">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="preguntaDependiente">
                                <label><?php echo $str_PreguntaDepen__; ?></label>
                                <select name="txtPreguntaPadre" id="txtPreguntaPadre" class="form-control" placeholder="<?php echo $str_PreguntaDepen__; ?>">
                                </select>
                            </div>
                            <div class="form-group" style="display: none;">
                                <div class="checkbox">
                                    <input type="checkbox" value="1" name="checkTipificacion" id="checkTipificacion" data-error="Before you wreck yourself"> <?php echo $str_Lista_tipifica;?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row panel">

                    </div>

                    <div class="row">
                        <div class="col-md-12" id="opciones">

                        </div>
                    </div>
                    <input type="hidden" id="hidListaInvocada">
                    <input type="hidden" id="operLista" name="operLista" value="add">
                    <input type="hidden" id="idListaE" name="idListaE" value="0">
                    <input type="hidden" id="TipoListas" name="TipoListas" value="0">
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success pull-left" type="button" id="newOpcion">
                    <?php echo $str_new_opcion____;?>
                </button>
                <button class="btn-primary btn " type="button" id="guardarLista">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" id="LimpiarCamposNewOpcion" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="ConfiguracionesAdvancedModal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 100%;">
        <div class="modal-content" id="contentAdvanced" style="width:700px;margin:auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="cerrarModalAdvanced">&times;</button>
                <h4 class="modal-title"><?php echo $str_avanz_campan; ?></h4>
            </div>
            <div class="modal-body">
                <form id="loqueVengaGenerad0" style="padding: 0px 20px;">
                    <div class='row' id="FechaDiv">
                        <div class='col-md-6'>
                            <label for="G6_C55" id="LblG6_C55"><?php echo $str_minFecha_______; ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" aria-label="..." value="" name="G6_C55" id="G6_C55" placeholder="<?php echo $str_minFecha_______; ?>">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" id='fecha_minima' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Elegir<span class="caret"></span></button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="#" onclick="tipo_duracion(this);">Hoy</a></li>
                                        <li><a href="#" onclick="tipo_duracion(this);">Elegir Fecha</a></li>
                                    </ul>
                                </div><!-- /btn-group -->
                            </div><!-- /input-group -->
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group" id="numero_mini_oculto">
                                <label for="G6_C56" id="LblG6_C56"><?php echo $str_maxFecha_______; ?></label>
                                <input type="text" class="form-control input-sm" value="" name="G6_C56" id="G6_C56" placeholder="<?php echo $str_maxFecha_______; ?>">
                            </div>
                        </div>
                    </div>
                    <div class='row' id="NumericoDiv">
                        <div class='col-md-6 formato formatoLista' style="display: none;">
                            <div class="form-group">
                                <label for="G6_C337" id="LblG6_C337">Formato</label>
                                <select class="form-control" name="G6_C337" id="G6_C337">
                                    <option value="1">Número</option>
                                    <option value="2">Moneda</option>
                                    <option value="3">Porcentaje</option>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-4 formato' style="display: none;">
                            <div class="form-group">
                                <label for="G6_C338" id="LblG6_C338">Posiciones decimales</label>
                                <input class="form-control" type="number" id="G6_C338" name="G6_C338">
                            </div>
                        </div>
                        <div class='col-md-6' id="minNumero">
                            <div class="form-group" id="numero_mini_oculto">
                                <label for="G6_C53" id="LblG6_C53"><?php echo $str_minNumero______; ?></label>
                                <input type="text" class="form-control input-sm Numerico" value="" name="G6_C53" id="G6_C53" placeholder="<?php echo $str_minNumero______; ?>">
                            </div>
                        </div>
                        <div class='col-md-6' id="maxNumero">
                            <div class="form-group">
                                <label for="G6_C54" id="LblG6_C54"><?php echo $str_maxNumero______; ?></label>
                                <input type="text" class="form-control input-sm Numerico" value="" name="G6_C54" id="G6_C54" placeholder="<?php echo $str_maxNumero______; ?>">
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label for="G6_C318" id="LblG6_C318"><?php echo $str_tipo_valor_____; ?></label>
                                <select class="form-control" id="G6_C318">
                                </select>
                            </div>
                        </div>
                        <div class='col-md-6' id="numero_defecto" style="display: none;">
                            <div class="form-group">
                                <label for="G6_C319" id="LblG6_C319"><?php echo $str_tipo_valor1____; ?></label>
                                <input type="text" class="form-control input-sm Numerico" value="" name="G6_C319" id="G6_C319" placeholder="<?php echo $str_tipo_valor1____; ?>">
                            </div>
                        </div>
                        <div class='col-md-6 formulaMatematica' style="display: none;" id="aquiVanVariables">
                            <ul id="ListaVariablesNumericas">

                            </ul>
                        </div>
                        <div class='col-md-12 formulaMatematica' style="display: none;">
                            <div class="form-group">
                                <label for="G6_C321" id="LblG6_C321"><?php echo $str_tipo_valor4____; ?></label>
                                <input type="text" class="form-control input-sm Numerico" value="" name="G6_C321" id="G6_C321" placeholder="<?php echo $str_tipo_valor4____; ?>">
                                <span style="color:orange" id="errorFormula"></span>
                            </div>
                        </div>

                        <div class='col-md-6' id="texto_defecto" style="display: none;">
                            <div class="form-group">
                                <label for="G6_C323" id="LblG6_C323"><?php echo $str_tipo_valor1____; ?></label>
                                <input type="text" class="form-control input-sm" value="" name="G6_C323" id="G6_C323" placeholder="<?php echo $str_tipo_valor1____; ?>">
                            </div>
                        </div>
                        <div class='col-md-6' id="tiempoCantidad" style="display: none;">
                            <div class="form-group">
                                <label for="G6_C324" id="LblG6_C324">Cantidad Tiempo</label>
                                <input type="number" class="form-control input-sm" value="" name="G6_C324" id="G6_C324">
                                <label for="G6_C325" id="LblG6_C325">Periodo</label>
                                <select class="form-control" id="G6_C325">
                                    <option value="0">Seleccione</option>
                                    <option value="day">Dias</option>
                                    <option value="week">Semanas</option>
                                    <option value="month">Meses</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class='row' id="HoraDiv">
                        <div class='col-md-6'>
                            <div class="form-group" id="numero_mini_oculto">
                                <label for="G6_C57" id="LblG6_C57"><?php echo $str_minHora________; ?></label>
                                <input type="text" class="form-control input-sm" value="" name="G6_C57" id="G6_C57" placeholder="<?php echo $str_minHora________; ?>">
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group" id="numero_mini_oculto">
                                <label for="G6_C58" id="LblG6_C58"><?php echo $str_maxHora________; ?></label>
                                <input type="text" class="form-control input-sm" value="" name="G6_C58" id="G6_C58" placeholder="<?php echo $str_maxHora________; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hora por Defecto</label>
                                <div class="input-group">
                                    <select type="text" class="form-control" id="horaDefecto" name="horaDefecto">
                                        <option value="">Seleccione</option>
                                        <option value="1001">Hora Actual</option>
                                    </select>
                                    <div class="input-group-addon" id="TMP_G2122_C38944">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="camposGuionNuevos">
                        <div class="col-md-12">
                            <table id="tablaguionesNew" class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 45%;"><?php echo $str_campoGuion____; ?></th>
                                        <th style="width: 45%;"><?php echo $str_campoMioFo____; ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="CuerpoTablaNuevosFields">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" id="MasterDetailsDiv">
                        <div class='col-md-6'>
                            <div class="form-group" id="gidetm_oculto">
                                <label for="G6_C57" id="LblG6_C57"><?php echo $str_Md_campo_mast_; ?></label>
                                <select class="form-control input-sm" value="" name="GuidetM" id="GuidetM">

                                </select>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group" id="gidet_oculo">
                                <label for="G6_C58" id="LblG6_C58"><?php echo $str_Md_campo_deta_; ?></label>
                                <select class="form-control input-sm" value="" name="Guidet" id="Guidet">

                                </select>
                            </div>
                        </div>
                        <div class='col-md-4' hidden>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="0" id="modoGuidet" data-error="Before you wreck yourself"><?php echo $str_Md_campo_inse_;?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="modalListaValorDefecto">
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label for="G6_C58" id="LblG6_C58">Opción por defecto</label>
                                <select class="form-control input-sm" value="" name="valLisDefecto" id="valLisDefecto">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-12" style="padding: 0;" id="divG6_C339">
                            <div class='col-md-6 form-group'>
                                <label for="G6_C339" id="LblG6_C339">Longitud(Max 253 caracteres)</label>
                                <input type="text" class="form-control input-sm Numerico" name="G6_C339" id="G6_C339" placeholder="Longitud">
                            </div>                            
                        </div>
                        <div class='col-md-3' hidden>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="-1" id="G6_C42" data-error="Before you wreck yourself"><?php //echo $str_campo_encri___;?>
                                </label>
                            </div>
                        </div>

                        <div class='col-md-3' hidden>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="-1" id="G6_C52" data-error="Before you wreck yourself"><?php //echo $str_campo_unico___;?>
                                </label>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="-1" id="G6_C51" data-error="Before you wreck yourself"><?php echo $str_campo_reque___;?>
                                </label>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="2" id="G6_C320" data-error="Before you wreck yourself"><?php echo $str_campo_desab___;?>
                                </label>
                            </div>
                        </div>

                        <!-- <div class='col-md-4'>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="-1" id="G6_C322" data-error="Before you wreck yourself"><?php // echo $str_campo_WebFo___;?>
                                </label>
                            </div>
                        </div> -->

                        <div class='col-md-4'>
                            <div class="checkbox">
                                <label id="lbl_G6_C327">
                                    <input type="checkbox" value="-1" id="G6_C327" data-error="Before you wreck yourself">ENVIAR CORREO
                                </label>
                            </div>

                            <div>
                                <label id="lbl_G6_C329">
                                    <select name="G6_C329" id="G6_C329" class="form-control input-sm">
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class="checkbox">
                                <label id="lbl_G6_C328">
                                    <input type="checkbox" value="-1" id="G6_C328" data-error="Before you wreck yourself">ENVIAR SMS
                                </label>
                            </div>

                            <div>
                                <label id="lbl_G6_C330">
                                    <select name="G6_C330" id="G6_C330" class="form-control input-sm">
                                    </select>
                                </label>
                            </div>

                            <div id="lbl_G6_C334">
                                Prefijo
                                <label style="width:100%">
                                    <input type="number" name="G6_C334" id="G6_C334" class="form-control input-sm" />
                                </label>
                            </div>

                            <div id="lbl_G6_C331">
                                Campo del mensaje
                                <label style="width:100%">
                                    <select name="G6_C331" id="G6_C331" class="form-control input-sm">
                                    </select>
                                </label>
                            </div>

                            <div id="lbl_G6_C333">
                                Base de datos
                                <label style="width:100%;margin-top: 9px;">
                                    <select name="G6_C333" id="G6_C333" class="form-control input-sm">
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <div class="checkbox">
                                <label id="lbl_G6_C326">
                                    <input type="checkbox" value="-1" id="G6_C326" data-error="Before you wreck yourself">LLAMAR AL HACER CLICK
                                </label>
                            </div>

                            <div id="lbl_G6_C332">
                                Campo correo para buscar
                                <label style="width:100%;margin-top: 9px;">
                                    <select name="G6_C332" id="G6_C332" class="form-control input-sm">
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class='col-md-6' id="div_G6_C335">
                            <div id="lbl_G6_C335">
                                <label style="width:100%;margin-top: 9px;">
                                WebService
                            </label>
                            <select name="G6_C335" id="G6_C335" class="form-control input-sm" style="width: 100%">
                                <option value="0">Seleccione</option>
                                <?php
                                    $sql=$mysqli->query("SELECT id,nombre FROM dyalogo_general.ws_general WHERE id_huesped={$_SESSION['HUESPED']}");
                                    while($option = $sql->fetch_object()){
                                        echo "<option value='{$option->id}'>{$option->nombre}</option>";
                                    }
                                ?>
                            </select>
                            </div>
                        </div>

                        <div class='col-md-6' id="div_G6_C336" >
                            <div id="lbl_G6_C336" style="display:none">
                                <label style="width:100%;margin-top: 9px;">
                                    Forma de integrar
                                </label>
                                <select name="G6_C336" id="G6_C336" class="form-control input-sm" style="width: 100%">
                                        <!-- <option value="0">Seleccione</option> -->
                                        <!-- <option value="1">Al cargar el formulario</option> -->
                                        <option value="2">Generar un botón</option>
                                        <!-- <option value="3">Al cerrar gestión</option> -->
                                </select>
                            </div>
                        </div>

                        

                    </div>
                    <div class="row" id="campossubform">
                        <div class="panel box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#asocampos">
                                        <?php echo $str_campoSubForm_; ?>
                                    </a>
                                </h3>
                            </div>
                            <div id="asocampos" class="panel-collapse collapse ">
                                <div class="box-body">
                                    <div class="row AquiCamposSub">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="modaltotalizador">
                        <div class="panel box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpototalizador">
                                        CALCULAR TOTALES EN EL FORMULARIO A PARTIR DE CAMPOS DEL SUBFORMULARIO
                                    </a>
                                </h3>
                            </div>
                            <div id="cuerpototalizador" class="panel-collapse collapse ">
                                <div class="box-body">
                                    <div class="row totalizadores">
                                        <div class='col-md-5'>
                                            <div class="form-group" id="">
                                                <label for="" id="">CAMPO PADRE</label>
                                                <select class="form-control input-sm" value="" name="GuidetPadre" id="GuidetPadre">

                                                </select>
                                            </div>
                                        </div>
                                        <div class='col-md-2'>
                                            <div class="form-group" id="">
                                                <label for="" id="">OPERACIÓN</label>
                                                <select class="form-control input-sm" value="" name="GuidetOper" id="GuidetOper">
                                                    <option value="0">Seleccione</option>
                                                    <option value="1">Suma</option>
                                                    <option value="2">Resta</option>
                                                    <option value="3">Copiar Valor</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='col-md-5'>
                                            <div class="form-group" id="">
                                                <label for="" id="">CAMPO HIJO</label>
                                                <select class="form-control input-sm" value="" name="GuidetHijo" id="GuidetHijo">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="modalcomunicacion">
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#cuerpocomunicacion">
                                        LLENAR CAMPOS DEL SUBFORMULARIO A PARTIR DE CAMPOS DEL FORMULARIO
                                    </a>
                                </h3>
                            </div>
                            <div id="cuerpocomunicacion" class="panel-collapse collapse ">
                                <div class="box-body">
                                    <div id="comunicaciones" class="comunicaciones">
                                        <input type="hidden" id="conteocomunicacion" value="0" class="selector" name="conteo">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-sm btn-success" id="nuevaComunicacion">
                                                    <i class="fa fa-plus">&nbsp; Agregar relacion Formulario - Subformulario</i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="seccionWS">
                        <iframe src="" id="iframeWS" style="width: 100%;margin-top: 30px;" marginwidth="0" frameborder="0"></iframe>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="tipoCampo" value="0">
                <input type="hidden" id="idFila" value="0">
                <button class="btn-success btn pull-left" type="button" id="addNewGuionConf">
                    <?php echo $str_guion_Camp____ ;?>
                </button>
                <button class="btn-primary btn " type="button" id="guardarConfiguracion" numero="">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="NuevoSaltoModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_Saltos_________; ?></h4>
            </div>
            <div class="modal-body">
                <form id="agrupadorSalto">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_campo_Salto____; ?></label>
                                <select name="cmbListaParaSalto" id="cmbListaParaSalto" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="opciones">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;"><?php echo $str_Opcion_salto___;?></th>
                                        <th style="width: 40%;"><?php echo $str_Campo_Guion_S__;?></th>
                                        <th style="width: 10%;">Limpiar</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoSaltosTB">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="hidPregunInvocado">
                    <input type="hidden" id="operPregun" name="operPregun" value="add">
                    <input type="hidden" id="contadorSaltos" name="contadorSaltos">
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success pull-left" type="button" id="newOpcionSalto">
                    <?php echo $str_new_opcion____;?>
                </button>
                <button class="btn-primary btn " type="button" id="guardarSalto">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>
   
<div id="NuevoSaltoModalSeccion" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_Saltos_________; ?></h4>
            </div>
            <div class="modal-body">
                <form id="agrupadorSaltoSeccion">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_campo_Salto____; ?></label>
                                <select name="cmbListaParaSaltoSeccion" id="cmbListaParaSaltoSeccion" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="opcionesSeccion">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 45%;"><?php echo $str_Opcion_salto___;?></th>
                                        <th style="width: 45%;">Sección a ocultar</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoSaltosTBSeccion">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="hidPregunInvocadoSeccion">
                    <input type="hidden" id="operPregunSeccion" name="operPregunSeccion" value="add">
                    <input type="hidden" id="contadorSaltosSeccion" name="contadorSaltosSeccion">
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success pull-left" type="button" id="newOpcionSaltoSeccion">
                    <?php echo $str_new_opcion____;?>
                </button>
                <button class="btn-primary btn " type="button" id="guardarSaltoSeccion">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="NuevoTipModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Configurar Requerido</h4>
            </div>
            <div class="modal-body">
                <form id="agrupadorTip">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipificaci'on a ejecutar requerido</label>
                                <select name="cmbListaParaTip" id="cmbListaParaTip" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="opciones">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 45%;"><?php echo $str_Opcion_salto___;?></th>
                                        <th style="width: 45%;"><?php echo $str_Campo_Guion_S__;?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTipTB">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="hidPregunInvocadoTip">
                    <input type="hidden" id="operPregunTip" name="operPregunTip" value="add">
                    <input type="hidden" id="contadorTip" name="contadorTip">
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success pull-left" type="button" id="newOpcionTip">
                    <?php echo $str_new_opcion____;?>
                </button>
                <button class="btn-primary btn " type="button" id="guardarTip">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="EditarSaltoModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_Saltos_________; ?></h4>
            </div>
            <div class="modal-body">
                <form id="agrupadorSaltoEdicion">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_campo_Salto____; ?></label>
                                <select name="" id="cmbListaParaSaltoEdicion" disabled class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="opciones">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;"><?php echo $str_Opcion_salto___;?></th>
                                        <th style="width: 40%;"><?php echo $str_Campo_Guion_S__;?></th>
                                        <th style="width: 10%;">Limpiar</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoSaltosTBEdicion">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="hidPregunInvocadoX" name="cmbListaParaSalto">
                    <input type="hidden" id="operPregun" name="operPregun" value="add">
                    <input type="hidden" id="contadorSaltosEdicion" name="contadorSaltosEdicion">

                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success pull-left" type="button" id="editarOpcionSalto">
                    <?php echo $str_new_opcion____;?>
                </button>
                <button class="btn-primary btn " type="button" id="editarSalto">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="EditarSaltoModalSeccion" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_Saltos_________; ?></h4>
            </div>
            <div class="modal-body">
                <form id="agrupadorSaltoEdicionSeccion">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Campo que ejecuta el salto</label>
                                <select name="" id="cmbListaParaSaltoEdicionSeccion" disabled class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="opciones">
                            <table class="table table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 45%;"><?php echo $str_Opcion_salto___;?></th>
                                        <th style="width: 45%;">Sección a deshabilitar</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoSaltosTBEdicionSeccion">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" id="hidPregunInvocadoXSeccion" name="cmbListaParaSaltoSeccion">
                    <input type="hidden" id="operPregunSeccion" name="operPregunSeccion" value="add">
                    <input type="hidden" id="contadorSaltosEdicionSeccion" name="contadorSaltosEdicionSeccion">

                </form>
            </div>
            <div class="modal-footer">

                <button class="btn btn-success pull-left" type="button" id="editarOpcionSaltoSeccion">
                    <?php echo $str_new_opcion____;?>
                </button>
                <button class="btn-primary btn " type="button" id="editarSaltoSeccion">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="EdicionFormaWeb" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $str_config_web_f__; ?></h4>
            </div>
            <div class="modal-body">
                <form id="guardarFromasWeb">
                    <div class="box box-default box-solid">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo $str_apariencia__wf; ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_title_config__;?></label>
                                        <input type='text' class='form-control' placeholder='<?php echo $str_title_config__;?>' name='txtTitulo' id="txtTitulo">
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_colorF_config_;?></label>
                                        <div class='input-group my-colorpicker2'>
                                            <input type='text' class='form-control' placeholder='<?php echo $str_colorF_config_;?>' name='txtColor' id='txtColor'>
                                            <div class='input-group-addon'>
                                                <i id="colorfondos">&nbsp;</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_colorL_config_;?></label>
                                        <div class='input-group my-colorpicker2'>
                                            <input type='text' class='form-control' placeholder='<?php echo $str_colorL_config_;?>' name='txtColorLetra' id='txtColorLetra'>
                                            <div class='input-group-addon'>
                                                <i id="colorLetra">&nbsp;</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_Logo_row______;?></label>
                                        <input type='file' class='form-control' name='txtfile' id='txtfile'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-10">
                            <div class="row">
                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_title_config__;?></label>
                                        <input type='text' class='form-control' placeholder='<?php echo $str_title_config__;?>' name='txtTitulo' id="txtTitulo">
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_colorF_config_;?></label>
                                        <div class='input-group my-colorpicker2'>
                                            <input type='text' class='form-control' placeholder='<?php echo $str_colorF_config_;?>' name='txtColor' id='txtColor'>
                                            <div class='input-group-addon'>
                                                <i id="colorfondos">&nbsp;</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_colorL_config_;?></label>
                                        <div class='input-group my-colorpicker2'>
                                            <input type='text' class='form-control' placeholder='<?php echo $str_colorL_config_;?>' name='txtColorLetra' id='txtColorLetra'>
                                            <div class='input-group-addon'>
                                                <i id="colorLetra">&nbsp;</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_Logo_row______;?></label>
                                        <input type='file' class='form-control' name='txtfile' id='txtfile'>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_optin_con_titl;?></label>
                                        <select class='form-control optin' name='optin' id="optin">
                                            <option value='0'><?php echo $str_seleccione;?></option>
                                            <option value='1'><?php echo $str_simple_row____;?></option>
                                            <option value='2'><?php echo $str_doble_________;?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_cuenta_decorre;?></label>
                                        <select class='form-control' disabled name='cuenta' id='cuenta'>
                                            <option>Seleccione cuenta</option>
                                        </select>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_asunto________;?></label>
                                        <input type='text' class='form-control' name='txtAsunto' id='txtAsunto' placeholder='<?php echo $str_asunto________;?>'>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_campocorreo_fo;?></label>
                                        <select type='text' disabled class='form-control' name='cmbCampoCorreo' id='cmbCampoCorreo'>
                                            <option>Campo correo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class='col-md-12'>
                                    <div class='form-group'>
                                        <label><?php echo $str_cuerpo________; ?></label>
                                        <textarea style='overflow:auto;resize:none' class='form-control' name='txtCuerpoMensaje' id='txtCuerpoMensaje' placeholder='<?php echo $str_cuerpo________;?>'></textarea>
                                    </div>
                                </div>

                                <div class='col-md-3'>
                                    <div class='form-group'>
                                        <label><?php echo $str_url_salida____; ?></label>
                                        <select class='form-control externa' name='extanas' id="extanas">
                                            <option value='2'><?php echo $str_interna_row___;?></option>
                                            <option value='1'><?php echo $str_externa_______;?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class='col-md-9'>
                                    <div class='form-group'>
                                        <label><?php echo $str_Url_config____; ?></label>
                                        <input type='text' readonly class='form-control' placeholder='<?php echo $str_Url_config____;?>' name='txtUrl' id='txtUrl'>
                                    </div>
                                </div>

                                <div class='col-md-12'>
                                    <div class='form-group'>
                                        <label><?php echo $str_texto_config__; ?></label>
                                        <input type='text' class='form-control' placeholder='<?php echo $str_texto_config__;?>' name='txtCodigo' id='txtCodigo'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <img src="<?=base_url?>assets/img/user2-160x160.jpg" class="img-circle" id="fotoLogo" style="width: 100%; height: auto;">
                            <label><?php echo $str_Logo_formaWeb_; ?></label>
                        </div>

                    </div>
                    <input type="hidden" id="hidVersionForm" name="hidVersionForm">
                    <input type="hidden" id="operVersin" name="operVersin" value="add">
                </form>
            </div>
            <div class="modal-footer">

                <button class="btn-primary btn " type="button" id="guardarVersionForm">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="configPregunWS" class="modal fade" data-backdrop="static" role="dialog">
    <div class="modal-dialog" style="width: 100%;">
        <div class="modal-content" style="width:90%;margin: auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">CONFIGURAR INTEGRACION</h4>
            </div>
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                        <iframe src="" id="iframePregunWS" style="width: 100%;margin-top: 30px;height: 500px;" marginwidth="0" frameborder="0"></iframe>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn " type="button" id="guardarPregunWS">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button" data-dismiss="modal">
                    <?php echo $str_cancela;?>
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="cuantoscampos" value="0">
<input type="hidden" id="guionWS" value="">
<input type="hidden" id="llave" value="">

<link rel="stylesheet" href="<?=base_url?>assets/plugins/colorpicker/bootstrap-colorpicker.min.css">

<script src="<?=base_url?>assets/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<link rel="stylesheet" href="<?=base_url?>assets/plugins/WinPicker/dist/wickedpicker.min.css">
<script type="text/javascript" src="<?=base_url?>assets/plugins/WinPicker/dist/wickedpicker.min.js"></script>

<script type="text/javascript">

    function tipo_duracion(e){
        $("#fecha_minima").html($(e).html()+'&nbsp;<span class="caret"></span>');
        if($(e).html() == 'Hoy'){
            $('#G6_C55').val('');
            $('#G6_C55').attr('disabled',true);
        }else{
            $('#G6_C55').attr('disabled',false);
        }
    }
    var strHTMLOpcionesTip_t = '';
    var strHTMLOpcionesCam_t = '';
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
    var bool_estamosListos = false;
    var newContador = 0;
    var cuantosVan2 = 0;
    var ListasBd = '';
    var GuionesBD = '';
    var ListaCampoGuionAux = 0;
    var ListaCampoGuionMio = 0;
    var LongitudNuevosCampos = 0;
    var OpcionesListas = 0;
    var datosJsonguion = '<option value="0"><?php echo $str_OpcionDependen_; ?></option>';
    var nombreGuion = '';
    var byWS;
    $(function() {

        $('#captcha').click(function() {
            if ($(this).val() == 0) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }

        });

        getListasSistemas();

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

        $("#usuarios").addClass('active');
        busqueda_lista_navegacion();
        $(".CargarDatos :first").click();

        $("#btnLlamadorAvanzado").click(function() {
            $('#busquedaAvanzada_ :input').each(function() {
                $(this).attr('disabled', false);
            });
        });



        $("#txtPruebas").on('scroll', function() {
            // alert('Si llegue');
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                <?php if(isset($_GET['tipo'])){ ?>
                $.post("<?=$url_crud;?>", {
                    inicio: inicio,
                    fin: fin,
                    callDatosNuevamente: 'si',
                    tipo: <?php echo $_GET['tipo'];?>,
                    idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                }, function(data) {
                    if (data != "") {
                        $("#tablaScroll").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                });
                <?php }else{ ?>
                $.post("<?=$url_crud;?>", {
                    inicio: inicio,
                    fin: fin,
                    callDatosNuevamente: 'si',
                    idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                }, function(data) {
                    if (data != "") {
                        $("#tablaScroll").append(data);
                        inicio += fin;
                        busqueda_lista_navegacion();
                    }
                });
                <?php } ?>
            }
        });

        //SECCION FUNCIONALIDAD BOTONES

        //Funcionalidad del boton + , add
        $("#add").click(function() {

            $("#txtNombreGuion").val("")

            $("#comboSelectTipo").val(0);
            $("#comboSelectTipo").val(0).change();


            $("#NuevaGuionModal").modal();
            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);
            $("#Save").attr('disabled', false);



            //Inializacion campos vacios por defecto
            $('#FormularioDatos :input').each(function() {
                if ($(this).is(':checkbox')) {
                    if ($(this).is(':checked')) {
                        $(this).prop('checked', false);
                    }
                    $(this).attr('disabled', false);
                } else {
                    $(this).val('');
                    $(this).attr('disabled', false);
                }

            });

            $("#G5_C31").attr('disabled', true);
            $("#G5_C59").attr('disabled', true);
            $("#G5_C319").attr('disabled', true);


            $("#primario").val(0);

            $("#segundario").val(0);


            $("#hidId").val(0);
            $("#h3mio").html('');
            $("#G5_C316").val('<?php echo $_SESSION['HUESPED'];?>');
            $("#G5_C29").val("");

            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";
            before_add();

            bool_estamosListos = true;

            $('#formularioSecciones_form :input').each(function() {
                if ($(this).is(':checkbox')) {
                    if ($(this).is(':checked')) {
                        $(this).prop('checked', false);
                    }
                    $(this).attr('disabled', true);
                } else {
                    if ($(this).is(':hidden')) {
                        $(this).attr('disabled', true);
                    } else {
                        $(this).val('');
                        $(this).attr('disabled', true);
                    }

                }

            });
            vamosRecargaLasGrillasPorfavor(0);
            $("#oper_seccion").val('add');
            $("#formularioSecciones").show();
            $("#formularioPregun").hide();
            $("#add_seccion").attr('disabled', false);

            $("#cuerpoTablaQuesedebeGuardarEdicion").html('');
            $("#cuerpoTablaQuesedebeGuardar").html('');
        });

        jQuery.fn.reset = function() {
            $(this).each(function() {
                this.reset();
            });
        }

        //funcionalidad del boton editar
        $("#edit").click(function() {

            bool_estamosListos = true;
            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
            $("#add").attr('disabled', true);
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);

            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
            $("#cancel").attr('disabled', false);
            $("#Save").attr('disabled', false);


            //Le informa al crud que la operaciòn a ejecutar es editar registro
            $("#oper").val('edit');
            //Habilitar todos los campos para edicion
            $('#FormularioDatos :input').each(function() {
                $(this).attr('disabled', false);
            });

            $('#formularioSecciones_form :input').each(function() {
                $(this).attr('disabled', true);
            });

            before_edit();

            vamosRecargaLasGrillasPorfavor(idTotal);

        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function() {
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);

            $("#formularioSecciones").hide();
            $("#formularioPregun").hide();

            bool_estamosListos = false;
        });
        
        $("#recordLoader").click(function() {
            const intIdFormulario = document.getElementById('guionWS').value
            
            $.ajax({
                url: `<?=base_url?>mostrar_popups.php?view=cargueDatos&poblacion=${intIdFormulario}`,
                type: 'get',
                
                success: function(data) {
                    $("#title_cargue").html("<?php echo $str_carga;?>");
                    $('#divIframe').html(data)
                    $('#cargarInformacion').modal()
                },
                beforeSend: function() {
                    try {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> Por favor espere mientras se cierra la gestión'
                        });
                    } catch (error) {}
                },
                complete: function() {
                    try {
                        $.unblockUI();
                    } catch (error) {}
                },
                error: function() {
                    alertify.error("Se genero un error al procesar la solicitud");
                }
            });
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function() {

            $.ajax({
                url: '<?=$url_crud_extender?>',
                type: 'post',
                data: {
                    validarParaBorrar: true,
                    id: $("#hidId").val(),
                    tipo: '<?php echo $_GET['tipo'];?>'
                },
                dataType: 'json',
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                complete: function() {
                    $.unblockUI();
                },
                success: function(data) {

                    ///data = jQuery.parseJSON(data);

                    if (data.code == '0') {
                        alertify.confirm("<?php if(isset($_GET['tipo']) && $_GET['tipo'] == 2){ echo $str_message_G_del1; }else if(isset($_GET['tipo']) && $_GET['tipo'] == 1 || $_GET['tipo'] == 4){ echo $str_message_G_del2; }else if(isset($_GET['tipo']) && $_GET['tipo'] == 3){ echo $str_message_G_del3; } ?>", function(e) {
                            //Si la persona acepta
                            if (e) {
                                var id = $("#hidId").val();
                                //se envian los datos, diciendo que la oper es "del"
                                $.ajax({
                                    url: '<?=$url_crud;?>?insertarDatosGrilla=si',
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        oper: 'del',
                                        tipo: '<?php if(isset($_GET['tipo'])) { echo $_GET['tipo']; }else{ echo '0';} ?>'
                                    },
                                    datatype: 'json',
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>'
                                        });
                                    },
                                    complete: function() {
                                        $.unblockUI();
                                    },
                                    success: function(data) {
                                        data = jQuery.parseJSON(data);
                                        if (data.code == '0') {
                                            llenar_lista_navegacion('');
                                            alertify.success('<?php echo $str_Exito;?>');
                                        } else {
                                            alertify.error(data);
                                        }
                                    }
                                });

                            } else {

                            }
                        });
                    } else {
                        if (data.code == '-1') {

                            alertify.error('<?php echo $str_error_message_;?>' + data.campana);

                        } else if (data.code == '-2') {

                            alertify.error('<?php echo $str_error_messag2_;?>' + data.campana);

                        } else if (data.code == '-3') {

                            alertify.error('<?php echo $str_error_messag3_;?>' + data.campana);

                        }
                    }
                }
            });

        });


        //datos Hoja de busqueda
        $("#BtnBusqueda_lista_navegacion").click(function() {
            //alert($("#table_search_lista_navegacion").val());
            llenar_lista_navegacion($("#table_search_lista_navegacion").val());
        });

        //Cajaj de texto de bus queda
        $("#table_search_lista_navegacion").keypress(function(e) {
            if (e.keyCode == 13) {
                llenar_lista_navegacion($(this).val());
            }
        });

        //preguntar cuando esta vacia la tabla para dejar solo los botones correctos habilitados
        var g = $("#tablaScroll").html();
        if (g === '') {
            $("#edit").attr('disabled', true);
            $("#delete").attr('disabled', true);
        }

        /* Generar opciones para darle funionalidad */

        $("#generar").click(function() {
            if ($("#primario").val() == 0) {
                alertify.error("<?php echo $str_message__cP_g_;?>");
            } else if ($("#segundario").val() == 0) {
                alertify.error("<?php echo $str_message__cS_g_;?>");
            } else {
                //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
                <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                $("#PreguntarGenerar_Base_Datos").modal();
                <?php }else { ?>
                alertify.confirm("¿Está seguro de que desea generar este formulario?", function(e) {
                    //Si la persona acepta
                    if (e) {
                        alertify.confirm("¿Esta operacion podria dañar algunos datos , desea seguir?", function(e) {
                            //Si la persona acepta
                            if (e) {
                                /* aqui se tira el ajax para la generación */
                                var id = $("#hidId").val();
                                //se envian los datos, diciendo que la oper es "del"
                                $.ajax({
                                    url: '<?=$url_crud;?>?generarGuion=si',
                                    type: 'POST',
                                    dataType : "JSON",
                                    data: {
                                        id: id,
                                        generar: 'si',
                                        checkFormBackoffice: '-1',
                                        checkGenerar: '-1'
                                    },
                                    success: function(data) {
                                        if (data.estado == 'ok') {
                                            alertify.success('Tabla generada con exito!');
                                        } else {
                                            console.log(data);
                                        }
                                    },
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>'
                                        });
                                    },
                                    complete: function() {
                                        $.unblockUI();
                                    }
                                });
                            } else {

                            }
                        });
                    } else {

                    }
                });
                <?php } ?>
            }

        });

        $("#generarTodoLoqueHalla").click(function() {
            var id = $("#hidId").val();
            var intTipoBusqManual_t = '';
            if ($("input[name='TipoBusqManual']:radio").is(':checked')) {
                intTipoBusqManual_t = $('input:radio[name=TipoBusqManual]:checked').val();
            }
            $.ajax({
                url: '<?=$url_crud;?>',
                type: 'POST',
                data: {
                    guardartipobusqueda: 'si',
                    tipobusqueda: intTipoBusqManual_t,
                    id: id
                },
                success: function(data) {}
            });
            guardarDatos(false);
        });
    });

</script>

<!-- esto es para la funcioanlidad de los botones de agregar Lista -->
<script type="text/javascript">
    var contador = 0;
    var cuantosVan = 0;
    $(function() {

        $("#txtPreguntaPadre").change(function() {
            var preguntaLista = $(this).val();
            $.ajax({
                url: '<?=$url_crud_extender?>',
                type: 'post',
                data: {
                    getDatosListaByPregun: true,
                    preguntaLista: $(this).val(),
                    idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                },
                success: function(options) {
                    datosJsonguion = options;
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                complete: function() {
                    $.unblockUI();
                }
            });

            if ($("#operLista").val() == "edit") {
                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'post',
                    data: {
                        getListasEdit: true,
                        idOpcion: $("#idListaE").val()
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {

                    },
                    success: function(data) {
                        if (data.code == '1') {
                            $("#opciones").empty();
                            $.each(data.lista, function(i, items) {
                                if ($("#txtPreguntaPadre").val() != 0 && $("#txtPreguntaPadre").val() != '') {

                                    $.ajax({
                                        url: '<?=$url_crud_extender?>',
                                        type: 'post',
                                        Async: false,
                                        data: {
                                            getDatosListaByPregun: true,
                                            preguntaLista: preguntaLista,
                                            idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                                        },
                                        success: function(options) {
                                            datosJsonguion = options;
                                            var cuerpo = "<div class='row' id='id_" + i + "'>";
                                            cuerpo += "<div class='col-md-5'>";
                                            cuerpo += "<div class='form-group'>";
                                            cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas'  value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                            cuerpo += "</div>";
                                            cuerpo += "</div>";
                                            cuerpo += "<div class='col-md-5'>";
                                            cuerpo += "<div class='form-group'>";
                                            cuerpo += "<select type='text' name='OpcionPadreX_" + i + "' id='OpcionPadreX_" + i + "' class='form-control opcionesGeneradas' placeholder='<?php echo $str_OpcionDependen_; ?>'>";
                                            cuerpo += datosJsonguion;
                                            cuerpo += "</select>";
                                            cuerpo += "</div>";
                                            cuerpo += "</div>";
                                            cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                            cuerpo += "<div class='form-group'>";
                                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "'  value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                            cuerpo += "</div>";
                                            cuerpo += "</div>";
                                            cuerpo += "</div>";

                                            $("#opciones").append(cuerpo);
                                            //$("#OpcionPadre_"+ i).val(items.LISOPC_ConsInte__LISOPC_Depende_b);
                                            //$("#OpcionPadre_"+ i).val(items.LISOPC_ConsInte__LISOPC_Depende_b).change();
                                        }
                                    });


                                } else {
                                    var cuerpo = "<div class='row' id='id_" + i + "'>";
                                    cuerpo += "<div class='col-md-10'>";
                                    cuerpo += "<div class='form-group'>";
                                    cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                    cuerpo += "<div class='form-group'>";
                                    cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "' value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    $("#opciones").append(cuerpo);
                                }


                            });

                            $("#opciones").append("<input type='hidden' name='contadorEditables' value='" + data.total + "'>");
                            contador = data.total;
                            $("#operLista").val("edit");

                            $(".deleteopcionP").click(function() {
                                var id = $(this).attr('value');
                                var miId = $(this).attr('id');
                                alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                    if (e) {
                                        $.ajax({
                                            url: '<?php echo $url_crud; ?>',
                                            type: 'post',
                                            data: {
                                                deleteOption: true,
                                                id: id
                                            },
                                            beforeSend: function() {
                                                $.blockUI({
                                                    baseZ: 2000,
                                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                });
                                            },
                                            complete: function() {
                                                $.unblockUI();
                                            },
                                            success: function(data) {
                                                if (data == '1') {
                                                    alertify.success('<?php echo $str_Exito;?>');
                                                    $("#id_" + miId).remove();
                                                } else {
                                                    alertify.error('Error');
                                                }
                                            }
                                        })

                                    }
                                });
                            });

                            $.unblockUI();
                        }
                    }
                });
            } else {
                // la estan creando y me vale verga
                cuantosVan = 0;
                contador = 0;
                $("#opciones").empty();
            }

        });


        $("#newOpcion").click(function() {
            var cuantosVan = contador;
            if ($("#TipoListas").val() == '13') {

                var cuerpo = "<div class='row' id='id_" + cuantosVan + "'>";
                cuerpo += "<div class='col-md-5'>";
                cuerpo += "<div class='form-group'>";
                cuerpo += "<input type='text' name='opciones_" + cuantosVan + "' class='form-control opcionesGeneradas' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
                cuerpo += "</div>";
                cuerpo += "</div>";
                cuerpo += "<div class='col-md-5'>";
                cuerpo += "<div class='form-group'>";
                cuerpo += "<input type='text' name='Respuesta_" + cuantosVan + "' id='Respuesta_" + cuantosVan + "' class='form-control opcionesGeneradas' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
                cuerpo += "</div>";
                cuerpo += "</div>";
                cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                cuerpo += "<div class='form-group'>";
                cuerpo += "<button class='btn btn-danger btn-sm deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + cuantosVan + "'><i class='fa fa-trash-o'></i></button>";
                cuerpo += "</div>";
                cuerpo += "</div>";
                cuerpo += "</div>";

                $("#opciones").append(cuerpo);
            } else {

                if ($("#txtPreguntaPadre").val() > 0) {

                    //console.log(datosJsonguion);

                    var cuerpo = "<div class='row' id='id_" + cuantosVan + "'>";
                    cuerpo += "<div class='col-md-5'>";
                    cuerpo += "<div class='form-group'>";
                    cuerpo += "<input type='text' name='opciones_" + cuantosVan + "' class='form-control input-sm  opcionesGeneradas' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
                    cuerpo += "</div>";
                    cuerpo += "</div>";
                    cuerpo += "<div class='col-md-5'>";
                    cuerpo += "<div class='form-group'>";
                    cuerpo += "<select type='text' name='OpcionPadre_" + cuantosVan + "' id='OpcionPadre_" + cuantosVan + "' class='form-control input-sm  opcionesGeneradas' placeholder='<?php echo $str_OpcionDependen_; ?>'>";
                    cuerpo += datosJsonguion;
                    cuerpo += "</select>";
                    cuerpo += "</div>";
                    cuerpo += "</div>";
                    cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                    cuerpo += "<div class='form-group'>";
                    cuerpo += "<button class='btn btn-danger btn-sm deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + cuantosVan + "'><i class='fa fa-trash-o'></i></button>";
                    cuerpo += "</div>";
                    cuerpo += "</div>";
                    cuerpo += "</div>";

                    $("#opciones").append(cuerpo);

                    $("#OpcionPadre_" + cuantosVan).select2({
                        dropdownParent: $("#opciones")
                    });


                } else {

                    var cuerpo = "<div class='row' id='id_" + cuantosVan + "'>";
                    cuerpo += "<div class='col-md-10'>";
                    cuerpo += "<div class='form-group'>";
                    cuerpo += "<input type='text' name='opciones_" + cuantosVan + "' class='form-control opcionesGeneradas' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
                    cuerpo += "</div>";
                    cuerpo += "</div>";
                    cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                    cuerpo += "<div class='form-group'>";
                    cuerpo += "<button class='btn btn-danger btn-sm deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + cuantosVan + "'><i class='fa fa-trash-o'></i></button>";
                    cuerpo += "</div>";
                    cuerpo += "</div>";
                    cuerpo += "</div>";

                    $("#opciones").append(cuerpo);

                }

            }

            cuantosVan++;
            contador++;




            $(".deleteopcion").click(function() {
                var id = $(this).attr('id');
                $("#id_" + id).remove();
                //contador = contador -1;
            });
        });

        $("#LimpiarCamposNewOpcion").click(function() {
            $("#agrupador")[0].reset();
            $("#opciones").html('');
            contador = 0;
            cuantosVan = 0;
        });

        $("#guardarLista").click(function() {
            var validator = 0;
            if ($("#txtNombreLista").val().length < 1) {
                validator = 1;
                alertify.error("Es necesario el nombre de la lista");
            }

            if ($("#listExcel").prop('checked')) {
                var excel = $('#arExcel').get(0).files[0];
                console.log(typeof(excel));
                if (typeof(excel) == 'undefined') {
                    alertify.error("Debe cargar un archivo");
                    validator = 1;
                }
            } else {
                if (contador == 0) {
                    validator = 1;
                    alertify.error("La lista no tiene opciones")
                }
            }

            var vacios = 0;
            $(".opcionesGeneradas").each(function() {
                if ($(this).val().length < 1) {
                    vacios++;
                }
            });

            if (vacios > 0) {
                alertify.error('No pueden haber opciones sin texto');
                validator = 1;
            }

            if (validator == 0) {
                var form = $("#agrupador");
                //Se crean un array con los datos a enviar, apartir del formulario
                var formData = new FormData($("#agrupador")[0]);
                formData.append('idGuion', idTotal);
                formData.append('contador', contador);
                $.ajax({
                    url: '<?=$url_crud?>?insertarDatosLista=si',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                    success: function(data) {
                        if (data != '0') {
                            alertify.success("<?php echo $str_Exito;?>");
                            var id = $("#hidListaInvocada").val();
                            if ($("#operLista").val() == 'add') {
                                $.ajax({
                                    url: '<?=$url_crud_extender?>?dameListas=true',
                                    type: 'post',
                                    success: function(options) {
                                        $("#G6_C44_" + id).html(options);
                                        $("#G6_C44_" + id).val(data);
                                        $("#G6_C44_" + id).val(data).change();

                                        $("#depende_" + id).val($("#txtPreguntaPadre").val());

                                        $("#agrupador")[0].reset();
                                        $("#opciones").html('');
                                        contador = 0;
                                        cuantosVan = 0;
                                    }
                                });
                            } else {
                                $("#depende_" + id).val($("#txtPreguntaPadre").val());
                            }


                            $("#NuevaListaModal").modal('hide');
                        } else {
                            alertify.error("<?php echo $error_de_proceso;?>");
                        }
                    }
                });
            }

        });
    })

</script>

<script type="text/javascript" src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_eventos.js"></script>

<!-- Manipular el crud, guardar , traer datos del guion etc -->
<script type="text/javascript">
    $(function() {
        /*$('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });*/


        <?php if(isset($_GET['registroId'])){ ?>
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                CallDatos: 'SI',
                id: <?php echo $_GET['registroId']; ?>
            },
            dataType: 'json',
            success: function(data) {
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {

                    $("#G5_C28").val(item.G5_C28);

                    $("#G5_C29").val(item.G5_C29);

                    $("#G5_C29").val(item.G5_C29).trigger("change");

                    $("#G5_C30").val(item.G5_C30);

                    $("#G5_C31").val(item.G5_C31);

                    $("#G5_C31").val(item.G5_C31).trigger("change");

                    $("#G5_C59").val(item.G5_C59);

                    
                    if (item.captcha == '1') {
                        $('#captcha').val('1');
                        $('#captcha').prop('checked', true);
                    } else {
                        $('#captcha').val('0');
                        $('#captcha').prop('checked', false);
                    }
                    
                    $("#G5_C59").val(item.G5_C59).trigger("change");

                    $("#G5_C319").val(item.G5_C319).trigger("change");

                    $("#h3mio").html(item.principal);

                    var idTotal = <?php echo $_GET['registroId'];?>;

                    if ($("#" + idTotal).length > 0) {
                        $("#" + idTotal).click();
                        $("#" + idTotal).addClass('active');
                    } else {
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }
                });
                //Deshabilitar los campos

                //Habilitar todos los campos para edicion
                $('#FormularioDatos :input').each(function() {
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

        $("#hidId").val('<?php echo $_GET['registroId'];?>');

        $("#TxtFechaReintento").attr('disabled', true);
        $("#TxtHoraReintento").attr('disabled', true);

        $("#btnLlamar_0").attr('padre', <?php echo $_GET['registroId'];?>);

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET['registroId'];?>)

        <?php } ?>

        //str_Select2 estos son los guiones

        $("#G5_C31").select2({
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +

                    '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection: function(data) {
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G5_C31").change(function() {
            var valores = $("#G5_C31 option:selected").text();
            var campos = $("#G5_C31 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if (r.length > 1) {

                var c = campos.split('|');
                for (i = 1; i < r.length; i++) {
                    if (!$("#" + c[i]).is("select")) {
                        // the input field is not a select
                        $("#" + c[i]).val(r[i]);
                    } else {
                        var change = r[i].replace(' ', '');
                        $("#" + c[i]).val(change).change();
                    }

                }
            }
        });

        $("#G6_C44").select2();
        $("#G6_C207").select2();
        $("#G6_C335").select2();
        $("#G6_C336").select2();


        $("#G5_C59").select2({
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +

                    '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection: function(data) {
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G5_C59").change(function() {
            var valores = $("#G5_C59 option:selected").text();
            var campos = $("#G5_C59 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if (r.length > 1) {

                var c = campos.split('|');
                for (i = 1; i < r.length; i++) {
                    if (!$("#" + c[i]).is("select")) {
                        // the input field is not a select
                        $("#" + c[i]).val(r[i]);
                    } else {
                        var change = r[i].replace(' ', '');
                        $("#" + c[i]).val(change).change();
                    }

                }
            }
        });


        $("#G5_C319").select2();

        //Buton gurdar
        $("#Save").click(function() {
            if ($("#primario").val() == 0) {
                alertify.error("<?php echo $str_message__cP_g_;?>");
            } else if ($("#segundario").val() == 0) {
                alertify.error("<?php echo $str_message__cS_g_;?>");
            } else {
                $("#PreguntarGenerar_Base_Datos").modal();
                var id = $("#hidId").val();
                $.ajax({
                    url: '<?=$url_crud;?>',
                    type: 'post',
                    data: {
                        guardartipobusqueda: 'NO',
                        id: id
                    },
                    success: function(data) {
                        if ($("input[name='TipoBusqManual']:radio").is(':checked')) {
                            $('input:radio[name=TipoBusqManual]:checked').prop('checked', false);
                        }
                        $(".optionsave input[value=" + data + "]").prop('checked', true);
                    }
                });
            }

        });

        $("#btnLlamar_0").click(function() {

            guardarDatos(true);
        });

        $('#G6_C327').on('change', function() {
            if ($(this).is(':checked') ) {
                llenarSelectMail_sms('mail');
                if($(this).attr('campo')==16){
                    getBasesHuesped();
                    $("#lbl_G6_C332").show();
                    $("#lbl_G6_C333").show();
                }
            } else {
                $("#G6_C329").html('');
                $("#lbl_G6_C329").hide();
                $("#lbl_G6_C332").hide();
                $("#lbl_G6_C333").hide();
            }
        });

        $('#G6_C328').on('change', function() {
            if ($(this).is(':checked') ) {
                llenarSelectMail_sms('sms');
                llenarText_sms();
            } else {
                $("#G6_C330").html('');
                $("#lbl_G6_C330").hide();
                $("#G6_C331").html('');
                $("#lbl_G6_C331").hide();
                $("#G6_C334").val('');
                $("#lbl_G6_C334").hide();
            }
        });

        $('#G6_C333').on('change', function() {
            var id=$(this).val();
            if(id !=0){
                getCamposBd(id,null);
            }else{
                var html="<option value='0'>Seleccione</option>";
                $("#G6_C332").html(html);
            }
        });
    });

    // OPCIONES PARA EL CAMPO TIPO DE SECCIÓN
    function addOptSec(){
        let html="<option value=\"1\">Normal</option>";
        <?php if (isset($_GET["tipo"]) && $_GET["tipo"] == 1) { ?>
            html+="<option value=\"2\">Calidad</option>";
        <?php } ?>
        html+="<option value=\"5\">Agendamiento</option>";
        $("#EditarG7_C38").html(html);
    }

    function alertLongitudCampo(data){
        
        let campos='';
        $.each(data,function(i,item){
            campos+="* "+ data[i].nombre + "\n";
        });
        swal({
            title: 'Advertencia',
            text: "En los siguientes campos no se cambió la longitud porque contienen información con mas caracteres de los configurados. \n \n "+ campos +" \n Si continua, la información de estos campos sera recortada a la longitud configurada y no se podrán recuperar los caracteres recortados. \n \n ¿Está seguro de continuar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'CONTINUAR!',
            cancelButtonText: 'CANCELAR!',
            closeOnConfirm: true,
            closeOnCancel: true     
        },
        function(isConfirm) {
            if (isConfirm) {
                reducirCampos(data);
            }
        });        
    }

    function reducirCampos(data){
        $.ajax({
            'url':'<?=$url_crud;?>?RecortarCampos=si',
            'type':"POST",
            dataType:'JSON',
            data:{data: JSON.stringify(data)},
            success:function(datos){
                if(datos.Estado=='ok'){
                    guardarDatos(true);
                }else{
                    alertify.error("No se pudo recortar la longitud de los campo");
                }
            },
            beforeSend:function(){
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>'
                });
            },
            complete:function(){
                $.unblockUI();
            }
        });
    }

    /* Esto es para guardar */
    function guardarDatos(isSaveOrNot) {

        var strFilasTip_t = "";

        $(".rowTip").each(function() {
            strFilasTip_t += $(this).attr("numero") + ",";
        });

        strFilasTip_t = strFilasTip_t.slice(0, -1);

        var bol_respuesta = before_save_Guion();
        if (bol_respuesta) {
            var form = $("#FormularioDatos");
            //Se crean un array con los datos a enviar, apartir del formulario
            var formData = new FormData($("#FormularioDatos")[0]);
            formData.append('contador', newContador);
            formData.append('captcha', $('#captcha').val());
            formData.append('strFilasTip_t', strFilasTip_t);
            $.ajax({
                url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data) {
                    if (data != '-1') {

                        //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                        if ($("#oper").val() == 'add') {
                            idTotal = data;
                        } else {
                            idTotal = $("#hidId").val();
                        }
                        //$(".modalOculto").hide();

                        //Limpiar formulario

                        after_save();


                        var id = $("#hidId").val();
                        var form = $("#validatorGenerador");
                        //Se crean un array con los datos a enviar, apartir del formulario
                        var formData = new FormData($("#validatorGenerador")[0]);
                        formData.append('generar', 'si');
                        formData.append('id', id);
                        //se envian los datos, diciendo que la oper es "del"
                        $.ajax({
                            url: '<?=$url_crud;?>?generarGuion=si',
                            type: 'POST',
                            data: formData,
                            dataType:'JSON',
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                if (data.estado == 'ok') {
                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '1'){ ?>
                                    alertify.success('<?php echo $str_message_succe2;?>');
                                    <?php } ?>

                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                                    alertify.success('<?php echo $str_message_succes;?>');
                                    <?php } ?>

                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '3'){ ?>
                                    alertify.success('<?php echo $str_message_succe3;?>');
                                    <?php } ?>

                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                                    $("#idTitle").html('<?php echo $str_message_genera; ?>' + nombreGuion);
                                    <?php } ?>

                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '1'){ ?>
                                    $("#idTitle").html('<?php echo $str_message_generF; ?>' + nombreGuion);
                                    <?php } ?>

                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '3'){ ?>
                                    $("#idTitle").html('<?php echo $str_message_generO; ?>' + nombreGuion);
                                    <?php } ?>


                                    // Si se genera correctamente solicitamos tambien que se genere la vista

                                    var id = $("#hidId").val();
                                    //Se crean un array con los datos a enviar, apartir del formulario
                                    var formDataVista = new FormData();
                                    formDataVista.append('id', id);

                                    $.ajax({
                                        url: '<?=$url_crud;?>?generarVista=si',
                                        type: 'POST',
                                        data: formDataVista,
                                        dataType:'JSON',
                                        cache: false,
                                        contentType: false,
                                        processData: false
                                    });

                                } else {
                                    alertLongitudCampo(data.alertas);
                                    console.log(data);
                                }
                            },
                            complete: function() {
                                $("#checkFormBackoffice").prop('checked', true);
                                $("#checkGenerar").prop('checked', true);
                                $("#checkBusqueda").prop('checked', true);
                                // $("#checkWebForm").prop('checked', true);

                                $("#PreguntarGenerar_Base_Datos").modal('hide');
                                $.unblockUI();

                            }
                        });


                        form[0].reset();
                        llenar_lista_navegacion($("#table_search_lista_navegacion").val());


                    } else {
                        //Algo paso, hay un error
                        alertify.error('Un error ha ocurrido');
                    }
                },
                //si ha ocurrido un error
                error: function() {
                    after_save_error();
                    alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>'
                    });
                }
            });
        }
    }

    /* guardar datos de las preguntas */
    function guardardatosPregun() {
        var form = $("#formularioPregun_form");
        //Se crean un array con los datos a enviar, apartir del formulario
        var formData = new FormData($("#formularioPregun_form")[0]);
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php?insertarDatosSubgrilla_0=si',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data) {
                if (data) {
                    /* si guardo eso perfecto */
                    alertify.confirm("<?php echo $str_exito_pregunta;?>", function(e) {
                        //Si la persona acepta
                        if (e) {
                            form[0].reset();
                            //Inializacion campos vacios por defecto
                            $('#formularioPregun_form :input').each(function() {
                                if ($(this).is(':checkbox')) {
                                    if ($(this).is(':checked')) {
                                        $(this).prop('checked', false);
                                    }
                                    $(this).attr('disabled', false);
                                } else {
                                    if ($(this).is(':hidden')) {
                                        $(this).attr('disabled', false);
                                    } else {
                                        $(this).val('');
                                        $(this).attr('disabled', false);
                                    }

                                }

                            });

                            $("#G6_C40").val(0);
                            $("#G6_C40").val(0).change();

                            $("#formularioPregun > str_Select2").each(function() {
                                $(this).val(0).change();
                            });


                            $("#G6_C40").val(1);
                            $("#G6_C40").val(1).change();

                            //Le informa al crud que la operaciòn a ejecutar es insertar registro
                            document.getElementById('id_oper').value = "add";
                            //Deshabilitar los botones que no vamos a utilizar, add, editar, borrar
                            $("#add_pregun").attr('disabled', true);
                            $("#edit_pregun").attr('disabled', true);
                            $("#delete_pregun").attr('disabled', true);

                            //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
                            $("#cancel_pregun").attr('disabled', false);
                            $("#Save_pregun").attr('disabled', false);

                            //vamosRecargaLasGrillasPorfavor($("#hidId").val());
                        } else {
                            $.ajax({
                                type: 'post',
                                url: '<?=$url_crud_extender?>?camposGuion=true',
                                data: {
                                    guion: idTotal,
                                    esMaster: 'si',
                                    seccion: $("#IdseccionEdicion").val()
                                },
                                success: function(data) {
                                    $("#G5_C31").html(data);
                                    $("#G5_C59").html(data);
                                    $("#G5_C319").html(data);

                                    $("#G5_C31").attr('disabled', false);
                                    $("#G5_C59").attr('disabled', false);
                                    $("#G5_C319").attr('disabled', false);
                                }
                            });
                            vamosRecargaLasGrillasPorfavor($("#hidId").val());
                        }
                    }).set('labels', {
                        ok: 'Si',
                        cancel: 'No'
                    });
                } else {
                    //Algo paso, hay un error
                    alertify.error('Un error ha ocurrido');
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
            }
        });
    }

    /* guardar datos de las preguntas */
    function guardardatosSeccion() {

        //guardarDatos(true);
        var form = $("#formularioSecciones_form");
        //Se crean un array con los datos a enviar, apartir del formulario
        var formData = new FormData($("#formularioSecciones_form")[0]);
        $.ajax({
            url: '<?=$url_crud?>?insertarDatosSubgrilla_0=si',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data) {
                if (data) {
                    /* si guardo eso perfecto */
                    vamosRecargaLasGrillasPorfavor(idTotal);
                    alertify.success("Se ha guardado la seccion");

                } else {
                    //Algo paso, hay un error
                    alertify.error('Un error ha ocurrido');
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
            }
        });
    }

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(x) {
        var tr = '';
        var url = '<?=$url_crud;?>';
        <?php if(isset($_GET['tipo'])){ ?>
        var url = '<?=$url_crud;?>?tipo=<?php echo $_GET['tipo'];?>';
        <?php } ?>
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                CallDatosJson: 'SI',
                Busqueda: x
            },
            dataType: 'json',
            success: function(data) {
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {

                    var tipo = '<?php echo mb_strtoupper($str_Script______g_);?>';

                    if (data[i].camp2 == 2) {
                        tipo = '<?php echo mb_strtoupper($str_Guion_______g_);?>';
                    } else if (data[i].camp2 == 3) {
                        tipo = '<?php echo mb_strtoupper($str_Otros_______g_);?>';
                    }

                    tr += "<tr class='CargarDatos' id='" + data[i].id + "'>";
                    tr += "<td>";
                    tr += "<p style='font-size:14px;'><b>" + data[i].camp1 + "</b></p>";
                    tr += "<p style='font-size:12px; margin-top:-10px;'>" + tipo + "</p>";
                    tr += "</td>";
                    tr += "</tr>";
                });
                $("#tablaScroll").html(tr);
                //aplicar funcionalidad a la Lista de navegacion
                busqueda_lista_navegacion();

                //SI el Id existe, entonces le damos click,  para traer sis datos y le damos la clase activa
                if ($("#" + idTotal).length > 0) {
                    $("#" + idTotal).click();
                    $("#" + idTotal).addClass('active');
                } else {
                    //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                    $(".CargarDatos :first").click();
                }

            }
        });
    }

    function IdTip() {

        $(".IdTip").change(function() {

            $("#idTip").val($('option:selected', this).attr("tip"));

        });

    }

    function eliminarTip() {
        $(".EliminarTip").click(function() {
            var IntFilaTip_t = $(this).attr("numero");
            $("#rowTip_" + IntFilaTip_t).remove();
        });
    }

    function addWS(oper){
        $("#iterWS").val(Number($("#iterWS").val()) + 1);

        var iterWS = $("#iterWS").val();

        var strHTMLSelect_t = $("#G6_C335").html();

        var strHTMLTip_t = '';

        strHTMLTip_t += '<div id="rowWS_' + iterWS + '" class="rowWS" numero="' + iterWS + '">';
        strHTMLTip_t += '<input type="hidden" id="operPregunWS_'+ iterWS +'" name="operPregunWS_'+ iterWS +'" value="'+oper+'">';
        strHTMLTip_t += '<input type="hidden" id="idPregunWS_'+ iterWS +'" name="idPregunWS_'+ iterWS +'" value="0">';
        strHTMLTip_t += '<div class="col-md-3 col-xs-5">';
        strHTMLTip_t += '<div class="form-group">';
        strHTMLTip_t += '<input type="text" id="text_'+ iterWS +'" name="text_'+ iterWS +'" class="form-control input-sm">';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '<div class="col-md-4 col-xs-7">';
        strHTMLTip_t += '<div class="form-group">';
        strHTMLTip_t += '<select id="ws_' + iterWS + '" name="ws_' + iterWS + '" class="form-control input-sm IdWs">';
        strHTMLTip_t += strHTMLSelect_t;
        strHTMLTip_t += '</select>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '<div class="col-md-3 col-xs-6">';
        strHTMLTip_t += '<div class="form-group">';
        strHTMLTip_t += '<select id="metodo_' + iterWS + '" name="metodo_' + iterWS + '" class="form-control input-sm">';
        strHTMLTip_t += '<option value="1">Al cargar el formulario</option><option value="3">Al cerrar gestión</option>';
        strHTMLTip_t += '</select>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '<div class="col-md-1 col-xs-3">';
        strHTMLTip_t += '<div class="form-group">';
        strHTMLTip_t += '<button class="form-control btn btn-warning btn-sm configurarWS" type="button" id="btnConfigurarWS_' + iterWS + '" numero="' + iterWS + '"><i class="fa fa-cog"></i></button>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '<div class="col-md-1 col-xs-3">';
        strHTMLTip_t += '<div class="form-group">';
        strHTMLTip_t += '<button class="form-control btn btn-danger btn-sm EliminarWS" type="button" id="btnEliminarWS_' + iterWS + '" numero="' + iterWS + '"><i class="fa fa-trash-o"></i></button>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '</div>';
        strHTMLTip_t += '</div>';

        $("#cuerpoWS").append(strHTMLTip_t);

        eliminarWS();
        IdWs();
        configurarWS();
        return iterWS;
    }

    function saveWS(numero){
        let guion=$("#guionWS").val();
        let texto=$("#text_"+numero).val();
        let ws=$("#ws_"+numero).val();
        let metodo=$("#metodo_"+numero).val();
        let oper=$("#operPregunWS_"+numero).val();
        let id=$("#idPregunWS_"+numero).val();
        $.ajax({
            url:'<?=$url_crud_extender?>?addPregunWS',
            type: 'POST',
            data:{guion:guion, texto:texto, ws:ws, metodo:metodo, oper:oper, id:id},
            dataType:'json',
            beforeSend:function(){
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> PROCESANDO PETICION'
                });
            },
            success:function(data){
                if(data.mensaje=='ok'){
                    if(oper=='delete'){
                        $("#rowWS_" + numero).remove();
                        alertify.success('Integración eliminada con exito');
                    }else{
                        if(oper=='add'){
                            $("#idPregunWS_"+numero).val(data.id);
                            $("#operPregunWS_"+numero).val('actualizar');
                        }
                    }
                }else{
                    alertify.error(data.mensaje);
                }
            },
            complete:function(){
                $.unblockUI();
            },
            error:function(){
                alertify.error("Ocurrio un error al procesar la solicitud");
                $.unblockUI();
            }
        });
    }

    function getPregunWS(guion){
        $("#cuerpoWS").empty();
        $.ajax({
            url:'<?=$url_crud_extender?>?getPregunWS',
            type: 'POST',
            data:{guion:guion},
            dataType:'json',
            beforeSend:function(){
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> PROCESANDO PETICION'
                });
            },
            success:function(data){
                if(data.estado==2){
                    $.each(data.data, function(item, value){
                        let numero= addWS('actualizar');
                        let texto=$("#text_"+numero).val(value.texto);
                        let ws=$("#ws_"+numero).val(value.ws).trigger('change');
                        let metodo=$("#metodo_"+numero).val(value.metodo).trigger('change');
                        let oper=$("#operPregunWS_"+numero).val('actualizar');
                        let id=$("#idPregunWS_"+numero).val(value.id);
                    });
                }else{
                    if(data.estado==0){
                        alertify.error(data.mensaje);
                    }
                }
            },
            complete:function(){
                $.unblockUI();
            },
            error:function(){
                alertify.error("Ocurrio un error al procesar la solicitud");
                $.unblockUI();
            }
        });
    }

    function eliminarWS() {
        $(".EliminarWS").click(function() {
            let IntFilaTip_t = $(this).attr("numero");
            let id=Number($("#idPregunWS_"+IntFilaTip_t).val());
            if(id >0){
                $("#operPregunWS_" + IntFilaTip_t).val('delete');
                saveWS(IntFilaTip_t);
            }
        });
    }

    function IdWs() {

        $(".IdWs").change(function() {
            $("#idWs").val($('option:selected', this).attr("tip"));
        });

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

    function configurarWS(){
        $(".configurarWS").click(function(){
            let numero=$(this).attr('numero');
            let guion=$("#guionWS").val();
            let texto=$("#text_"+numero).val();
            let ws=$("#ws_"+numero).val();
            let metodo=$("#metodo_"+numero).val();
            let oper=$("#operPregunWS_"+numero).val();
            let id=$("#idPregunWS_"+numero).val();

            if(texto !='' && ws!='0'){
                saveWS(numero);
                $("#iframePregunWS").attr('src', '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_webservice.php?ws='+$("#ws_"+numero).val()+'&guion='+$("#guionWS").val()+'&llave='+$("#idPregunWS_"+numero).val());
                $("#configPregunWS").modal();
            }else{
                alertify.warning("Debe completar todos los campos");
            }
        });
    }
    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion() {

        $(".CargarDatos").click(function() {
            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function() {
                $(this).removeClass('active');
            });

            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');

            bool_estamosListos = false;

            var id = $(this).attr('id');
            let byModulo=0;

            $("#IdGuion").val(id);
            //buscar los datos
            $.ajax({
                url: '<?=$url_crud;?>',
                async:false,
                type: 'POST',
                data: {
                    CallDatos: 'SI',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);

                    $.each(data, function(i, item) {


                        $("#G5_C28").val(item.G5_C28);

                        $("#G5_C29").val(item.G5_C29);

                        $("#G5_C29").val(item.G5_C29).trigger("change");

                        $("#G5_C30").val(item.G5_C30);
                        
                        $("#guionWS").val(item.byWs);

                        byModulo=item.G5_C318;

                        var camposWS = 'curl --location --request POST \'http://addons.<?php echo $URL_SERVER ?>:8080/dy_servicios_adicionales/svrs/dm/info/data\' \\\n';
                            camposWS += '--header \'Accept: application/json\' \\\n';
                            camposWS += '--header \'Content-Type: application/json\' \\\n';
                            camposWS += '--data-raw \'{\n';
                            camposWS += '\t "strUsuario_t": "USUARIO",\n';
                            camposWS += '\t "strToken_t": "TOKEN",\n';
                            camposWS += '\t "intIdG_t": "'+item.byWs+'",\n';
                            camposWS += '\t "strSQLWhere_t": "Condicion de el/los registros a consultar",\n';
                            camposWS += '\t "intLimit_t": "Total de registros que seran devueltos",\n';
                            camposWS += '}\'';
                            
                        $("#modelWS").html(camposWS);
                        
                        
                        

                        if (item.captcha == '1') {
                            $('#captcha').val('1');
                            $('#captcha').prop('checked', true);
                        } else {
                            $('#captcha').val('0');
                            $('#captcha').prop('checked', false);
                        }

                        nombreGuion = item.G5_C28;


                        if (item.G5_C29 == '2') {
                            if (item.encode != 'false') {
                                $("#rutaWebForm").attr('href', 'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/web_forms.php?web=' + item.encode);
                                $("#rutaWebForm").html("http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/web_forms.php?web=" + item.encode);
                            }
                        } else {
                            $("#rutaWebForm").attr('href', '');
                            $("#rutaWebForm").html("");
                        }

                        $("#vistaPrevia").attr('href', 'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/index.php?formulario=' + id);

                        $("#verDatosGuion").attr('href', 'http://<?php echo $_SERVER["HTTP_HOST"];?>/manager/index.php?page=showData&formulario=' + item.encode_preview + '&tipo=<?php echo $_GET['tipo']; ?>');

                        $("#generar").attr('disabled', false);


                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                        $("#idTitle").html('<?php echo $str_message_genera; ?>' + item.G5_C28);
                        <?php } ?>

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '1'){ ?>
                        $("#idTitle").html('<?php echo $str_message_generF; ?>' + item.G5_C28);
                        <?php } ?>

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '3'){ ?>
                        $("#idTitle").html('<?php echo $str_message_generO; ?>' + item.G5_C28);
                        <?php } ?>


                        $("#h3mio").html(item.principal);

                        /* Obtener los campos de ese guion */
                        $.ajax({
                            type: 'post',
                            url: '<?=$url_crud_extender?>?camposGuion=true',
                            data: {
                                guion: id,
                                esMaster: 'si',
                                seccion: $("#IdseccionEdicion").val()
                            },
                            success: function(data) {
                                $("#G5_C31").html(data);
                                $("#G5_C59").html(data);
                                $("#G5_C319").html(data);
                                $("#G5_C31").val(item.G5_C31);
                                $("#G5_C31").val(item.G5_C31).trigger("change");
                                $("#G5_C319").val(item.G5_C319);
                                $("#G5_C319").val(item.G5_C319).trigger("change");
                                $("#G5_C59").val(item.G5_C59);
                                $("#G5_C59").val(item.G5_C59).trigger("change");
                            }
                        });

                        //OBTENER LOS CAMPO TIPO LISTA DEL GUION
                        $.ajax({
                            type: 'post',
                            url: '<?=$url_crud_extender?>?camposGuion=true',
                            data: {
                                guion: id,
                                seccion: $("#IdseccionEdicion").val()
                            },
                            dataType : 'json',
                            success: function(data) {
                                let htmlOption='<option value="0">Seleccione</option>';
                                $.each(data, function(item, value){
                                    if(value.tipoCampo=='6'){
                                        htmlOption+="<option value='"+value.lista+"'>"+value.nombre+"</option>";
                                    }
                                });
                                $("#campOpcion").html(htmlOption);
                            }
                        });

                        getPregunWS(item.byWs);

                        traerConfiguraciones(id);
                    });

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function() {
                        $(this).attr('disabled', true);
                    });



                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);
                    $("#generar").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                }
            });
            
           //EJEMPLO DE LO QUE RETORNA EN JSON

                $.ajax({
                url: '<?=$url_crud;?>',
                type: 'POST',
                data: {
                    id: id,
                    traerDatosWs: true
                },
                dataType: 'json',
                success: function(data){

                    if(data.estado == "ok"){

                        let camposObjSerializar_t = '';
                        let camposStrMensaje_t = '';
                        let camposStrprueba_t = '';


                        if(data.camposWs.length > 0){

                            data.camposWs.forEach(element => {
                                
                                camposObjSerializar_t += `
                                                         'Valor dentro del campo 
                                                          ${element.PREGUN_Texto_____b}',`;
                                camposStrMensaje_t += `
                                                      G${data.baseId}_C${element.PREGUN_ConsInte__b},${element.PREGUN_Texto_____b},`;
                               
                                                              


                            });


                        }

                        let estructura = `
                            "objSerializar_t": [
                                    [
                                        1,
                                        ${camposObjSerializar_t}
                                    ],
                                    [
                                        2,
                                        ${camposObjSerializar_t}
                                    ],
                                ],
                             

                                "strEstado_t": "ok",
                                "strMensaje_t":   "G${data.baseId}_ConsInte__b, ${camposStrMensaje_t},  "
                        `;

                        $("#intentoWS").html(estructura);

                    }

                }
            });



            //TRAER LA SECCION DE SALTOS
            getSaltosSeccion(id);
            //TRAER LA SECCIÓN DE SALTOS POR SECCION
            getSaltosSecciones(id);

            getTipAccion(id);

            $("#btnAddTip").attr("guion", id);

            $("#hidId").val(id);

            //TRAER LA SECCION DE SECCION
            getSeccionesGuion(byModulo);


            idTotal = $("#hidId").val();

            $("#id_guion").val(idTotal);

        });
    }

    function traerTip(intIdGuion_p, strOpciones_p) {

        switch (strOpciones_p) {
            case 'tipificaciones':

                strHTMLOpcionesTip_t = $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'POST',
                    data: {
                        getTip: strOpciones_p,
                        intIdGuion_t: intIdGuion_p
                    },
                    dataType: 'html',
                    context: document.body,
                    global: false,
                    async: false,
                    success: function(data) {
                        return data;
                    }
                }).responseText;


                return strHTMLOpcionesTip_t;

                break;
            case 'campos':

                strHTMLOpcionesCam_t = $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'POST',
                    data: {
                        getTip: strOpciones_p,
                        intIdGuion_t: intIdGuion_p
                    },
                    dataType: 'html',
                    context: document.body,
                    global: false,
                    async: false,
                    success: function(data) {
                        return data;
                    }
                }).responseText;

                return strHTMLOpcionesCam_t;

                break;
        }





    }

    function getTipAccion(intId_p) {

        $("#iterTip").val("0");
        $("#cuerpoTip").html('<div class="row"><div class="col-md-5 col-xs-5"><div class="form-group"><label>Tipificación</label></div></div><div class="col-md-5 col-xs-5"><div class="form-group"><label>Campo</label></div></div><div class="col-md-2 col-xs-2"><div class="form-group"><label>Eliminar</label></div></div></div>');

        $.ajax({
            url: '<?=$url_crud_extender?>',
            type: 'POST',
            data: {
                getTipAccion: 'si',
                idGuion: intId_p
            },
            dataType: 'json',
            success: function(data) {


                if (data) {
                    var strHTMLSelect_t = traerTip(intId_p, 'tipificaciones');

                    var strHTMLSelect2_t = traerTip(intId_p, 'campos');


                    $.each(data, function(i, v) {

                        $("#iterTip").val(Number($("#iterTip").val()) + 1);

                        var strHTMLTip_t = '';
                        strHTMLTip_t += '<div id="rowTip_' + (i + 1) + '" class="rowTip" numero="' + (i + 1) + '">';
                        strHTMLTip_t += '<div class="col-md-5 col-xs-5">';
                        strHTMLTip_t += '<div class="form-group">';
                        strHTMLTip_t += '<select disabled id="tip_' + (i + 1) + '" name="tip_' + (i + 1) + '" class="form-control input-sm IdTip">';
                        strHTMLTip_t += strHTMLSelect_t;
                        strHTMLTip_t += '</select>';
                        strHTMLTip_t += '</div>';
                        strHTMLTip_t += '</div>';
                        strHTMLTip_t += '<div class="col-md-5 col-xs-5">';
                        strHTMLTip_t += '<div class="form-group">';
                        strHTMLTip_t += '<select disabled id="campo_' + (i + 1) + '" name="campo_' + (i + 1) + '" class="form-control input-sm">';
                        strHTMLTip_t += strHTMLSelect2_t;
                        strHTMLTip_t += '</select>';
                        strHTMLTip_t += '</div>';
                        strHTMLTip_t += '</div>';
                        strHTMLTip_t += '<div class="col-md-2 col-xs-2">';
                        strHTMLTip_t += '<div class="form-group">';
                        strHTMLTip_t += '<button disabled class="form-control btn btn-danger btn-sm EliminarTip" type="button" id="btnQuitarTip_' + (i + 1) + '" numero="' + (i + 1) + '"><i class="fa fa-trash-o"></i></button>';
                        strHTMLTip_t += '</div>';
                        strHTMLTip_t += '</div>';
                        strHTMLTip_t += '</div>';

                        $("#cuerpoTip").append(strHTMLTip_t);

                        $(".EliminarTip").click(function() {
                            var IntFilaTip_t = $(this).attr("numero");
                            $("#rowTip_" + IntFilaTip_t).remove();
                        });

                        $(".IdTip").change(function() {

                            $("#idTip").val($('option:selected', this).attr("tip"));

                        });

                        $("#tip_" + (i + 1)).val(v.ACCIONTIPI_ConsInte_LISOPC_Tipi_b).trigger("change");
                        $("#campo_" + (i + 1)).val(v.ACCIONTIPI_ConsInte_PREGUN_Campo_b).trigger("change");

                    });

                }
            }

        });

    }

    function seleccionar_registro() {
        //Seleccinar loos registros de la Lista de navegacion,
        if ($("#" + idTotal).length > 0) {
            $("#" + idTotal).click();
            $("#" + idTotal).addClass('active');
            idTotal = 0;
            $(".modalOculto").hide();
        } else {
            $(".CargarDatos :first").click();
        }

    }

    function seleccionar_registro_avanzada(id) {
        $.ajax({
            url: '<?=$url_crud;?>',
            type: 'POST',
            data: {
                CallDatos: 'SI',
                id: id
            },
            dataType: 'json',
            success: function(data) {
                //recorrer datos y enviarlos al formulario
                $(".llamadores").attr("padre", id);
                $.each(data, function(i, item) {


                    $("#G5_C28").val(item.G5_C28);

                    $("#G5_C29").val(item.G5_C29);

                    $("#G5_C29").val(item.G5_C29).trigger("change");

                    $("#G5_C30").val(item.G5_C30);

                    $("#G5_C31").val(item.G5_C31);

                    $("#G5_C31").val(item.G5_C31).trigger("change");

                    $("#G5_C319").val(item.G5_C319).trigger("change");

                    $("#G5_C59").val(item.G5_C59);

                    if (item.captcha == '1') {
                        $('#captcha').val('1');
                        $('#captcha').prop('checked', true);
                    } else {
                        $('#captcha').val('0');
                        $('#captcha').prop('checked', false);
                    }

                    $("#G5_C59").val(item.G5_C59).trigger("change");
                    $("#h3mio").html(item.principal);
                });

                //Deshabilitar los campos

                //Habilitar todos los campos para edicion
                $('#FormularioDatos :input').each(function() {
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
        $(".CargarDatos").each(function() {
            $(this).removeClass('active');
        });
        $("#" + idTotal).addClass('active');
    }

    function vamosRecargaLasGrillasPorfavor(id) {
        $.ajax({
            type: 'post',
            url: '<?=$url_crud_extender?>?camposGuion_incude_id=true',
            data: {
                guion: id
            },
            success: function(data) {
                $("#GuidetM").html(data);
            }
        });
    }

    function poner_botones_diabled() {
        $("#add_seccion").attr('disabled', false);
        $("#edit_seccion").attr('disabled', false);
        $("#delete_seccion").attr('disabled', false);

        //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
        $("#cancel_seccion").attr('disabled', true);
        $("#Save_seccion").attr('disabled', true);
    }

    function before_save_Guion() {
        var valid = true

        if ($("#G5_C28").val().length < 1) {
            alertify.error("<?php echo $str_error_nombr_g_;?>");
            valid = false;
        }

        if ($("#G5_C31").val() == 0) {
            alertify.error("<?php echo $str_message__cP_g_;?>");
            valid = false;
        }

        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 2) { ?>

            if ($("#G5_C319").val() == 0 || $("#G5_C319").val() == '' || $("#G5_C319").val() == undefined) {
                alertify.error("El campo llave es obligatorio");
                valid = false;
            }
        <?php } ?>

        if ($("#G5_C59").val() == 0) {
            alertify.error("<?php echo $str_message__cS_g_;?>");
            valid = false;
        }

        return valid;
    }

    function alertRequerido(id){
        swal({
            title: 'Campo requerido',
            text: "Este campo actualmente está configurado como requerido. Si lo incluyes en un salto dejará de ser requerido. \n ¿Estás seguro de continuar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'CONTINUAR!',
            cancelButtonText: 'CANCELAR!',
            closeOnConfirm: true,
            closeOnCancel: true     
        },
        function(isConfirm) {
            if (!isConfirm) {
                $("#"+id).val(0).change();
            }
        });        
    }

    function alertSeccionRequerida(id){
        swal({
            title: 'Campos requeridos',
            text: "Esta sección actualmente contiene campos requeridos. Si la incluyes en un salto los campos dejarán de ser requeridos. \n ¿Estás seguro de continuar?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'CONTINUAR!',
            cancelButtonText: 'CANCELAR!',
            closeOnConfirm: true,
            closeOnCancel: true     
        },
        function(isConfirm) {
            if (!isConfirm) {
                $("#"+id).val(0).change();
            }
        });        
    }

    function getSaltosSeccion(id) {
        $("#cuerpoSaltosTBEdicion").empty();
        $("#cuerpoSaltosTB").empty();

        $.ajax({
            url: '<?=$url_crud_extender?>',
            data: {
                guion: id,
                getSaltos: true,
                bool_estamosListos: bool_estamosListos
            },
            type: 'post',
            success: function(data) {
                $("#cuerpoSalto").html(data);

                $(".btnEliminarSalto").click(function() {
                    var idpregun = $(this).attr('valorSalto');
                    var guion = $(this).attr('guion');
                    alertify.confirm('<?php echo $str_deleteField___;?>', function(e) {
                        if (e) {
                            $.ajax({
                                url: '<?=$url_crud_extender?>',
                                data: {
                                    pregunta: idpregun,
                                    deleteSalto: true,
                                    guion: guion
                                },
                                type: 'post',
                                success: function(data) {
                                    if (data == '0') {
                                        alertify.success("<?php echo $str_Exito; ?>");
                                        $("#salto_" + idpregun).remove();
                                        //getSaltosSeccion($("#hidId").val());
                                    } else {
                                        alertify.success("<?php echo $error_de_proceso; ?>");
                                    }
                                },
                                beforeSend: function() {
                                    $.blockUI({
                                        baseZ: 2000,
                                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                    });
                                },
                                complete: function() {
                                    $.unblockUI();
                                }
                            });
                        }
                    });
                });

                $(".btnEditarSalto").click(function() {
                    var idpregun = $(this).attr('valorSalto');
                    var guion = $(this).attr('guion');
                    $.ajax({
                        url: '<?=$url_crud_extender?>',
                        type: 'post',
                        data: {
                            getCamposLista: true,
                            guion: $("#hidId").val()
                        },
                        success: function(data) {
                            $("#cmbListaParaSaltoEdicion").html(data);
                            $("#cmbListaParaSaltoEdicion").val(idpregun);
                            $("#cmbListaParaSaltoEdicion").val(idpregun).change();
                            $("#hidPregunInvocadoX").val(idpregun);

                            $.ajax({
                                url: '<?=$url_crud_extender?>?camposGuionSaltos=true',
                                data: {
                                    guion: $("#hidId").val(),
                                    esMaster: 'si',
                                    seccion: $("#IdseccionEdicion").val()
                                },
                                type: 'post',
                                success: function(datas) {
                                    ListaCampoGuionMio = '<option value="0">Seleccione</option>';
                                    ListaCampoGuionMio += datas;

                                    $.ajax({
                                        url: '<?=$url_crud_extender?>',
                                        type: 'post',
                                        dataType: 'json',
                                        data: {
                                            getListasEdit: true,
                                            idOpcion: $("#cmbListaParaSaltoEdicion option:selected").attr('idoption')
                                        },
                                        success: function(datax) {
                                            if (datax.code == '1') {
                                                OpcionesListas = datax.lista;

                                                /* ahora toca poner esto en lo que vallamos a pintar */
                                                $.ajax({
                                                    url: '<?=$url_crud_extender?>',
                                                    type: 'post',
                                                    dataType: 'json',
                                                    data: {
                                                        getDatosPrasasaPrsade: true,
                                                        pregun: idpregun,
                                                        guion: $("#hidId").val()
                                                    },
                                                    success: function(dataZ) {
                                                        $("#cuerpoSaltosTBEdicion").empty();
                                                        $.each(dataZ, function(i, items) {

                                                            var campo = "<tr id='idtr_" + i + "' numero=\"" + i + "\" class=\"rowSaltoEdit\">";
                                                            campo += "<td style='width:45%;'>";
                                                            campo += "<select class='form-control' name='opcionesCampo_" + i + "' id='opcionesCampo_" + i + "' fila='" + i + "'>";
                                                            $.each(OpcionesListas, function(j, itms) {
                                                                campo += "<option value='" + itms.LISOPC_ConsInte__b + "'>" + itms.LISOPC_Nombre____b + "</option>";
                                                            });
                                                            campo += "</select>";
                                                            campo += "</td>";
                                                            campo += "<td>";
                                                            campo += "<select class='form-control' name='camposCOnfiguradosGuionTo_" + i + "'  id='camposCOnfiguradosGuionTo_" + i + "' fila='" + i + "'>";
                                                            campo += ListaCampoGuionMio;
                                                            campo += "</select>";
                                                            campo += "</td>";
                                                            campo += "<td style = 'text-align: center;'>";
                                                            campo += "<input type='checkbox' id = 'limpiarCampos_" + i + "' name = 'limpiarCampos_" + i + "'>";
                                                            campo += "</td>";
                                                            campo += "<td>";
                                                            campo += "<button class='btn btn-sm btn-danger' id='delEstaRowE_" + i + "' type='button' fila='" + i + "' idprasade='" + items.id + "'><i class='fa fa-trash-o'></i></button>";
                                                            campo += "</td>";

                                                            $("#cuerpoSaltosTBEdicion").append(campo);

                                                            $("#camposCOnfiguradosGuionTo_" + i).val(items.camposGuion);
                                                            $("#camposCOnfiguradosGuionTo_" + i).val(items.camposGuion).change();

                                                            $("#opcionesCampo_" + i).val(items.campoPregun);
                                                            $("#opcionesCampo_" + i).val(items.campoPregun).change();

                                                            if (items.limpiarCampo == 1) {
                                                                $("#limpiarCampos_" + i).prop("checked", true);
                                                            }


                                                            $("#delEstaRowE_" + i + "").click(function() {
                                                                var id = $(this).attr('fila');
                                                                var idprasade = $(this).attr('idprasade');
                                                                alertify.confirm('<?php echo $str_deleteField___;?>', function(e) {
                                                                    if (e) {
                                                                        $.ajax({
                                                                            url: '<?=$url_crud_extender?>',
                                                                            type: 'post',
                                                                            dataType: 'json',
                                                                            data: {
                                                                                deletePrasadeByid: true,
                                                                                id: idprasade
                                                                            },
                                                                            success: function(dataZ) {
                                                                                if (dataZ == '0') {
                                                                                    alertify.success('<?php echo $str_Exito; ?>');
                                                                                    $("#idtr_" + id).remove();
                                                                                } else {
                                                                                    alertify.error('<?php echo $error_de_proceso; ?>');
                                                                                }
                                                                            },
                                                                            beforeSend: function() {
                                                                                $.blockUI({
                                                                                    baseZ: 2000,
                                                                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                                                });
                                                                            },
                                                                            complete: function() {
                                                                                $.unblockUI();
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            });

                                                            $("#camposCOnfiguradosGuionTo_" + i).change(function() {
                                                                try {
                                                                    let requerido=$(this).val().split("_");
                                                                    if(requerido.length > 1){
                                                                        alertRequerido(this.id);
                                                                    }
                                                                } catch (error) {
                                                                    console.info(error);
                                                                }
                                                            });

                                                            $("#contadorSaltosEdicion").val(i);
                                                        });
                                                    },
                                                    complete: function() {
                                                        $.unblockUI();
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }
                            });
                        },
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        }
                    });



                    $("#hidPregunInvocado").val(idpregun);
                    $("#EditarSaltoModal").modal();
                });
            }
        })
    }
    
    function getSaltosSecciones(id) {
        $("#cuerpoSaltosTBEdicionSeccion").empty();
        $("#cuerpoSaltosTBSeccion").empty();

        $.ajax({
            url: '<?=$url_crud_extender?>',
            data: {
                guion: id,
                getSaltosSeccion: true,
                bool_estamosListos: bool_estamosListos
            },
            type: 'post',
            success: function(data) {
                $("#cuerpoSaltoSeccion").html(data);

                $(".btnEliminarSaltoSeccion").click(function() {
                    var idpregun = $(this).attr('valorSalto');
                    var guion = $(this).attr('guion');
                    alertify.confirm('<?php echo $str_deleteField___;?>', function(e) {
                        if (e) {
                            $.ajax({
                                url: '<?=$url_crud_extender?>',
                                data: {
                                    pregunta: idpregun,
                                    deleteSaltoSeccion: true,
                                    guion: guion
                                },
                                type: 'post',
                                success: function(data) {
                                    if (data == '0') {
                                        alertify.success("<?php echo $str_Exito; ?>");
                                        $("#saltoSeccion_" + idpregun).remove();
                                        //getSaltosSeccion($("#hidId").val());
                                    } else {
                                        alertify.success("<?php echo $error_de_proceso; ?>");
                                    }
                                },
                                beforeSend: function() {
                                    $.blockUI({
                                        baseZ: 2000,
                                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                    });
                                },
                                complete: function() {
                                    $.unblockUI();
                                }
                            });
                        }
                    });
                });

                $(".btnEditarSaltoSeccion").click(function() {
                    var idpregun = $(this).attr('valorSalto');
                    var guion = $(this).attr('guion');
                    $.ajax({
                        url: '<?=$url_crud_extender?>',
                        type: 'post',
                        data: {
                            getCamposLista: true,
                            guion: $("#hidId").val()
                        },
                        success: function(data) {
                            $("#cmbListaParaSaltoEdicionSeccion").html(data);
                            $("#cmbListaParaSaltoEdicionSeccion").val(idpregun);
                            $("#cmbListaParaSaltoEdicionSeccion").val(idpregun).change();
                            $("#hidPregunInvocadoXSeccion").val(idpregun);

                            $.ajax({
                                url: '<?=$url_crud_extender?>?camposGuionSeccion=true',
                                data: {
                                    guion: $("#hidId").val(),
                                },
                                type: 'post',
                                success: function(datas) {
                                    ListaCampoGuionMio = '<option value="0">Seleccione</option>';
                                    ListaCampoGuionMio += datas;

                                    $.ajax({
                                        url: '<?=$url_crud_extender?>',
                                        type: 'post',
                                        dataType: 'json',
                                        data: {
                                            getListasEdit: true,
                                            idOpcion: $("#cmbListaParaSaltoEdicionSeccion option:selected").attr('idoption')
                                        },
                                        success: function(datax) {
                                            if (datax.code == '1') {
                                                OpcionesListas = datax.lista;

                                                /* ahora toca poner esto en lo que vallamos a pintar */
                                                $.ajax({
                                                    url: '<?=$url_crud_extender?>',
                                                    type: 'post',
                                                    dataType: 'json',
                                                    data: {
                                                        getDatosPrasasaPrsadeSeccion: true,
                                                        pregun: idpregun,
                                                        guion: $("#hidId").val()
                                                    },
                                                    success: function(dataZ) {
                                                        $("#cuerpoSaltosTBEdicionSeccion").empty();
                                                        $.each(dataZ, function(i, items) {

                                                            var campo = "<tr id='idtrSeccion_" + i + "' numero=\"" + i + "\" class=\"rowSaltoEditSeccion\">";
                                                            campo += "<td style='width:45%;'>";
                                                            campo += "<select class='form-control' name='opcionesCampoSeccion_" + i + "' id='opcionesCampoSeccion_" + i + "' fila='" + i + "'>";
                                                            $.each(OpcionesListas, function(j, itms) {
                                                                campo += "<option value='" + itms.LISOPC_ConsInte__b + "'>" + itms.LISOPC_Nombre____b + "</option>";
                                                            });
                                                            campo += "</select>";
                                                            campo += "</td>";
                                                            campo += "<td>";
                                                            campo += "<select class='form-control' name='camposCOnfiguradosGuionToSeccion_" + i + "'  id='camposCOnfiguradosGuionToSeccion_" + i + "' fila='" + i + "'>";
                                                            campo += ListaCampoGuionMio;
                                                            campo += "</select>";
                                                            campo += "</td>";
                                                            campo += "<td>";
                                                            campo += "<button class='btn btn-sm btn-danger' id='delEstaRowESeccion_" + i + "' type='button' fila='" + i + "' idprasade='" + items.id + "'><i class='fa fa-trash-o'></i></button>";
                                                            campo += "</td>";

                                                            $("#cuerpoSaltosTBEdicionSeccion").append(campo);

                                                            $("#camposCOnfiguradosGuionToSeccion_" + i).val(items.seccion);
                                                            $("#camposCOnfiguradosGuionToSeccion_" + i).val(items.seccion).change();

                                                            $("#opcionesCampoSeccion_" + i).val(items.campoPregun);
                                                            $("#opcionesCampoSeccion_" + i).val(items.campoPregun).change();


                                                            $("#delEstaRowESeccion_" + i + "").click(function() {
                                                                var id = $(this).attr('fila');
                                                                var idprasade = $(this).attr('idprasade');
                                                                alertify.confirm('<?php echo $str_deleteField___;?>', function(e) {
                                                                    if (e) {
                                                                        $.ajax({
                                                                            url: '<?=$url_crud_extender?>',
                                                                            type: 'post',
                                                                            dataType: 'json',
                                                                            data: {
                                                                                deletePrasadeByid: true,
                                                                                id: idprasade
                                                                            },
                                                                            success: function(dataZ) {
                                                                                if (dataZ == '0') {
                                                                                    alertify.success('<?php echo $str_Exito; ?>');
                                                                                    $("#idtrSeccion_" + id).remove();
                                                                                } else {
                                                                                    alertify.error('<?php echo $error_de_proceso; ?>');
                                                                                }
                                                                            },
                                                                            beforeSend: function() {
                                                                                $.blockUI({
                                                                                    baseZ: 2000,
                                                                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                                                });
                                                                            },
                                                                            complete: function() {
                                                                                $.unblockUI();
                                                                            }
                                                                        });
                                                                    }
                                                                });
                                                            });

                                                            $("#camposCOnfiguradosGuionToSeccion_" + i).change(function() {
                                                                try {
                                                                    let requerido=$(this).val().split("_");
                                                                    if(requerido.length > 1){
                                                                        alertSeccionRequerida(this.id);
                                                                    }
                                                                } catch (error) {
                                                                    console.info(error);
                                                                }
                                                            });

                                                            $("#contadorSaltosEdicionSeccion").val(i);
                                                        });
                                                    },
                                                    complete: function() {
                                                        $.unblockUI();
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }
                            });
                        },
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        }
                    });



                    $("#hidPregunInvocadoSeccion").val(idpregun);
                    $("#EditarSaltoModalSeccion").modal();
                });
            }
        })
    }

    function traerConfiguraciones(id) {
        $.ajax({
            url: '<?php echo $url_crud;?>?dameConfiguraciones=true',
            type: 'post',
            data: {
                guion: id,
                idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
            },
            success: function(data) {
                $("#nuevosConfiguraciones").html(data);

                $(".btnEliminarForma").click(function() {
                    var id = $(this).attr("formaID");
                    alertify.confirm('<?php echo $str_message_generico_D ;?>', function(e) {
                        if (e) {
                            $.ajax({
                                url: '<?=$url_crud_extender?>',
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    deletefomaWeb: true,
                                    idForma: id
                                },
                                success: function(data) {
                                    if (data.code == 1) {
                                        alertify.success('<?php echo $str_Exito; ?>');
                                        traerConfiguraciones($("#hidId").val());
                                    } else {
                                        alertify.error('<?php echo $error_de_proceso; ?>');
                                    }
                                },
                                beforeSend: function() {
                                    $.blockUI({
                                        baseZ: 2000,
                                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                    });
                                },
                                complete: function() {
                                    $.unblockUI();
                                }
                            });
                        }
                    });
                });

                $(".btnEditarForma").click(function() {
                    var id = $(this).attr("formaID");
                    $.ajax({
                        url: '<?=$url_crud_extender?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            getEdicionFormaWeb: true,
                            idForma: id
                        },
                        success: function(data) {
                            if (data.code == 1) {
                                $.each(data.datos, function(i, item) {
                                    $("#txtColor").val(item.color_fondo);
                                    $("#colorfondos").css('background-color', item.color_fondo);
                                    $("#txtTitulo").val(item.titulo);
                                    $("#txtColorLetra").val(item.color_letra);
                                    $("#colorLetra").css('background-color', item.color_letra);
                                    $("#optin").val(item.tipo_optin);

                                    $("#hidVersionForm").val(item.id);
                                    $("#txtAsunto").val(item.Asunto_mail);
                                    // $("#txtCuerpoMensaje").val(item.Cuerpo_mail);
                                    CKEDITOR.instances['txtCuerpoMensaje'].setData(item.Cuerpo_mail);
                                    $("#extanas").val(item.tipo_gracias);
                                    $("#extanas").val(item.tipo_gracias).change();
                                    $("#txtUrl").val(item.url_gracias);
                                    $("#txtCodigo").val(item.codigo_a_insertar);

                                    $("#fotoLogo").attr('src', item.nombre_imagen);



                                    $("#operVersin").val('edit');

                                    $.ajax({
                                        url: '<?=$url_crud_extender?>?traerCuentas=true',
                                        type: 'post',
                                        datatype: 'html',
                                        success: function(data) {
                                            $("#cuenta").html(data);
                                            $("#cuenta").attr('disabled', false);
                                            $("#cuenta").val(item.id_dy_ce_configuracion);
                                            $("#cuenta").val(item.id_dy_ce_configuracion).change();
                                            $.ajax({
                                                url: '<?=$url_crud_extender?>?camposcorreo=true',
                                                type: 'post',
                                                data: {
                                                    guion: $("#hidId").val()
                                                },
                                                datatype: 'html',
                                                success: function(data) {
                                                    $("#cmbCampoCorreo").html(data);
                                                    $("#cmbCampoCorreo").attr('disabled', false);
                                                    $("#cmbCampoCorreo").val(item.id_pregun);
                                                    $("#cmbCampoCorreo").val(item.id_pregun).change();
                                                },
                                                complete: function() {
                                                    $.unblockUI();
                                                    $("#EdicionFormaWeb").modal();
                                                }
                                            });
                                        }
                                    });

                                });
                            } else {
                                alertify.error('<?php echo $error_de_proceso; ?>');
                            }
                        },
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        }
                    });
                });
            }
        })
    }

    function getListasSistemas() {
        $.ajax({
            url: '<?php echo $url_crud;?>',
            type: 'post',
            data: {
                getListasDelSistema: true
            },
            success: function(data) {
                ListasBd = data;
            }
        })


        $.ajax({
            url: '<?php echo $url_crud;?>',
            type: 'post',
            data: {
                getGuionesDelSistema: true,
                huesped: '<?php echo $_SESSION['HUESPED']; ?>'
            },
            success: function(data) {
                GuionesBD = data;
            }
        })
    }

    function getSeccionesGuion(byModulo=0) {
        $.ajax({
            url: '<?=$url_crud;?>?callDatosSecciones=true',
            type: 'POST',
            data: {
                id: $("#hidId").val(),
                idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>',
                byModulo:byModulo
            },
            dataType: 'html',
            success: function(data) {
                
                $("#secciones_creadas").html(data);

                $("#secciones_creadas").sortable({
                    axis: 'y',
                    update: function(event, ui) {
                        var data = $(this).sortable('serialize');
                        $("#txtOrdenData").val(data);
                    }
                });

                $(".btnEliminarSeccion").click(function() {
                    var idSeccion = $(this).attr('valorSeccion');
                    alertify.confirm("<?php echo $str_message_genericoDel; ?>", function(e) {
                        if (e) {
                            $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php?insertarDatosGrilla=si',
                                type: 'POST',
                                data: {
                                    id: idSeccion,
                                    oper: 'del'
                                },
                                beforeSend: function() {
                                    $.blockUI({
                                        baseZ: 2000,
                                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                    });
                                },
                                complete: function() {
                                    $.unblockUI();
                                },
                                success: function(data) {
                                    if (data == '0') {
                                        alertify.success('<?php echo $str_Exito;?>');
                                        $("#seccion_" + idSeccion).remove();
                                    } else {
                                        alertify.error("<?php echo $error_de_proceso; ?>");
                                    }
                                }
                            });
                        }
                    });
                });

                $(".btnEditarSeccion").click(function() {
                    addOptSec();
                    editaSeccion($(this).attr('valorseccion'));
                });
            }
        });
    }

    $("#G6_C335").change(function(){
        $("#iframeWS").attr('src', '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_webservice.php?ws='+$("#G6_C335").val()+'&guion='+$("#guionWS").val()+'&llave='+$("#llave").val());
    });

    $("#addWS").click(function(){
            addWS('add');
    });

    $("#guardarPregunWS").click(function(e){
        $('#iframePregunWS').contents().find('form').submit();
    });
</script>

<!-- Script para usar la funiconalidad del modal de guiones , para crearlos-->
<script type="text/javascript">
    $(function() {
        $("#arExcel").hide();

        /* $('input').iCheck({
             checkboxClass: 'icheckbox_square-blue',
             radioClass: 'iradio_square-blue',
             increaseArea: '20%' // optional
         });*/

         //mostrar ocultar el importador desde excel
        $("#GenerarFromExel").change(function() {
           if($('#GenerarFromExel').is(':checked')) {
               $('.excel').show();
               $('.excel2').show();
           }else{
                $('.excel').hide();
                $('.excel2').hide();
           }
       });

        $('#listExcel').change(function() {
            if ($(this).is(':checked')) {
                $("#arExcel").show();
                $(this).val(1);
            } else {
                $("#arExcel").hide();
                $(this).val(0);
            }
        });


        $('#newGuionFile').on('change', function(e) {
            /* primero validar que sea solo excel */
            var imagen = this.files[0];
            //console.log(imagen);
            if (imagen['type'] != "application/vnd.ms-excel" && imagen['type'] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $("#newGuionFile").val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo debe estar en formato XLS o XLSX",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            } else if (imagen['size'] > 2000000) {
                $("#newGuionFile").val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo no debe pesar mas de 2MB",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            }
        });


        $("#cancelarModal").click(function() {
            $("#cancel").click();
        });

        $("#guardarNewGuion").click(function() {
            var valido = 0;
            if ($("#txtNombreGuion").val().length < 1) {
                valido = 1;
                $("#txtNombreGuion").focus();
                alertify.error("<?php echo $str_error_nombr_g_; ?>");
            }

            if ($("#comboSelectTipo").val() == 0) {
                valido = 1;
                $("#comboSelectTipo").focus();
                alertify.error("<?php echo $str_error_tipo__g_; ?>");
            }

            if (valido == 0) {
                var form = $("#nuevoGuion");
                //Se crean un array con los datos a enviar, apartir del formulario
                var formData = new FormData($("#nuevoGuion")[0]);
                formData.append('oper', 'add');
                formData.append('G5_C31', '0');
                formData.append('G5_C59', '0');
                formData.append('G5_C319', '0');
                $.ajax({
                    url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data) {
                        data = jQuery.parseJSON(data);
                        if (data.code != '-1' && data.code != '-2') {
                            $.ajax({
                                url: '<?=$url_crud;?>',
                                type: 'POST',
                                data: {
                                    CallDatos: 'SI',
                                    id: data.code
                                },
                                dataType: 'json',
                                success: function(data) {
                                    console.log('data :>> ', data);
                                    //recorrer datos y enviarlos al formulario
                                    $.each(data, function(i, item) {


                                        $("#G5_C28").val(item.G5_C28);

                                        $("#G5_C29").val(item.G5_C29);

                                        $("#G5_C29").val(item.G5_C29).trigger("change");

                                        $("#G5_C30").val(item.G5_C30);

                                        $("#G5_C31").val(item.G5_C31).trigger("change");

                                        $("#G5_C31").val(item.G5_C31).trigger("change");

                                        $("#G5_C59").val(item.G5_C59).trigger("change");

                                        $("#G5_C59").val(item.G5_C59).trigger("change");

                                        $("#G5_C319").val(item.G5_C319).trigger("change");

                                        $("#h3mio").html(item.principal);

                                        var tipo = '<?php echo mb_strtoupper($str_Script______g_);?>';

                                        if (item.G5_C29 == 2) {

                                            tipo = '<?php echo mb_strtoupper($str_Guion_______g_);?>';

                                        } else if (item.G5_C29 == 3) {

                                            tipo = '<?php echo mb_strtoupper($str_Otros_______g_);?>';
                                        }

                                        idTotal = item.G5_ConsInte__b;



                                        $("#hidId").val(idTotal);

                                        $("#id_guion").val(idTotal);

                                        $(".CargarDatos").each(function() {
                                            $(this).removeClass('active');
                                        });

                                        var tr = "<tr class='CargarDatos active' id='" + item.G5_ConsInte__b + "'>" +
                                            "<td>" +
                                            "<p style='font-size:14px;'><b>" + item.G5_C28.toUpperCase() + "</b></p>" +
                                            "<p style='font-size:12px; margin-top:-10px;'>" + tipo + "</p>" +
                                            "</td>" +
                                            "</tr>";

                                        $("#tablaScroll").prepend(tr);

                                        busqueda_lista_navegacion();
                                        
                                        pintarNombreTituloModal(item)
                                        getSeccionesGuion();
                                    });
                                    //Deshabilitar los campos



                                    //Habilidar los botones de operacion, add, editar, eliminar
                                    $("#add").attr('disabled', true);
                                    $("#edit").attr('disabled', true);
                                    $("#delete").attr('disabled', true);
                                    $("#generar").attr('disabled', true);

                                    //Desahabiliatra los botones de salvar y seleccionar_registro
                                    $("#cancel").attr('disabled', false);
                                    $("#Save").attr('disabled', false);

                                    $("#oper").val('edit');
                                }
                            });
                            $("#hidId").val(data.code);

                            $.ajax({
                                type: 'post',
                                url: '<?=$url_crud_extender?>?camposGuion=true',
                                data: {
                                    guion: $("#hidId").val(),
                                    esMaster: 'si',
                                    seccion: $("#IdseccionEdicion").val()
                                },
                                success: function(data) {
                                    $("#G5_C31").html('<option value="">Seleccione</option>');
                                    $("#G5_C59").html('<option value="">Seleccione</option>');
                                    $("#G5_C319").html('<option value="">Seleccione</option>');

                                    $("#G5_C31").append(data);
                                    $("#G5_C59").append(data);
                                    $("#G5_C319").append(data);

                                    $("#G5_C31").attr('disabled', false);
                                    $("#G5_C59").attr('disabled', false);
                                    $("#G5_C319").attr('disabled', false);
                                }
                            });

                            $("#NuevaGuionModal").modal('hide');
                            <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '1'){ ?>
                            alertify.success('<?php echo $str_message_succe2;?>');
                            <?php } ?>

                            <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                            alertify.success('<?php echo $str_message_succes;?>');
                            <?php } ?>

                            <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '3'){ ?>
                            alertify.success('<?php echo $str_message_succe3;?>');
                            <?php } ?>

                        } else {
                            if (data.code == '-2') {
                                if (data.messaje == '1062') {
                                    alertify.error('<?php echo $str_message_G_cre1; ?>');
                                }
                            } else {
                                //Algo paso, hay un error
                                alertify.error('Un error ha ocurrido');
                            }

                        }
                    },
                    //si ha ocurrido un error
                    error: function() {
                        after_save_error();
                        alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
            }
        });
        const pintarNombreTituloModal = (item)=> {
            if (item.G5_C29==="2") {
                const idTitle = document.getElementById('idTitle')
                idTitle.innerHTML = "Vas a guardar la base de datos"
                idTitle.innerHTML += ` ${item.G5_C28}`
            }else{
                const idTitle = document.getElementById('idTitle')
                idTitle.innerHTML = "Vas a guardar el formulario"
                idTitle.innerHTML += ` ${item.G5_C28}`   
            }
        }
    });

</script>

<!-- Script para usar el modal de las formas Web -->
<script type="text/javascript">
    $(function() {
        $("#btnAddMoreConfig").click(function() {
            $("#txtAsunto").attr('disabled', true);
            $("#operVersin").val('add');
            $("#EdicionFormaWeb").modal();
        });

        CKEDITOR.replace('txtCuerpoMensaje', {
            language: 'es'
        });

        $(".optin").change(function() {
            if ($(this).val() != 0) {
                $.ajax({
                    url: '<?=$url_crud_extender?>?traerCuentas=true',
                    type: 'post',
                    datatype: 'html',
                    success: function(data) {
                        $("#cuenta").html(data);
                        $("#cuenta").attr('disabled', false);
                        $.ajax({
                            url: '<?=$url_crud_extender?>?camposcorreo=true',
                            type: 'post',
                            data: {
                                guion: $("#hidId").val()
                            },
                            datatype: 'html',
                            success: function(data) {
                                $("#cmbCampoCorreo").html(data);
                                $("#cmbCampoCorreo").attr('disabled', false);
                                $("#txtAsunto").attr('disabled', false);
                            },
                            complete: function() {
                                $.unblockUI();
                            }
                        });
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    }
                });

            } else {

                $("#cmbCampoCorreo").attr('disabled', true);
                $("#cuenta").attr('disabled', true);
                $("#txtAsunto").attr('disabled', true);
            }

        });

        $(".externa").change(function() {
            if ($(this).val() == 1) {
                $("#txtUrl").attr('readonly', false);
                $("#txtCodigo").attr('readonly', true);
            } else {
                $("#txtUrl").attr('readonly', true);
                $("#txtCodigo").attr('readonly', false);
            }
        });

        $('.my-colorpicker2').colorpicker();

        $("#guardarVersionForm").click(function() {
            var form = $("#guardarFromasWeb");
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            //Se crean un array con los datos a enviar, apartir del formulario
            var formData = new FormData($("#guardarFromasWeb")[0]);
            formData.append('crearFormasWeb', true);
            formData.append('id', $("#hidId").val());

            $.ajax({
                url: '<?=$url_crud_extender?>',
                type: 'post',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if (data.code == 1) {
                        alertify.success('<?php echo $str_Exito; ?>');
                        traerConfiguraciones($("#hidId").val());
                        $("#EdicionFormaWeb").modal('hide');
                    } else {
                        alertify.error('<?php echo $error_de_proceso; ?>');
                    }
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                complete: function() {
                    $.unblockUI();
                }
            })
        });

        $("#txtfile").on('change', function(e) {
            var imax = $(this).attr('valor');
            var imagen = this.files[0];
            //console.log(imagen);
            /* Validar el tipo de imagen */
            if (imagen['type'] != 'image/jpeg' && imagen['type'] != 'image/png') {
                $('#inpFotoPerfil').val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo debe estar en formato JPG",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            } else if (imagen['size'] > 2000000) {
                $('#inpFotoPerfil').val('');
                swal({
                    title: "Error al subir el archivo",
                    text: "El archivo no debe pesar mas de 2MB",
                    type: "error",
                    confirmButtonText: "Cerrar"
                });
            } else {
                if (imagen['type'] == 'image/jpeg' || imagen['type'] == 'image/png') {
                    var datosImagen = new FileReader();
                    datosImagen.readAsDataURL(imagen);

                    $(datosImagen).on("load", function(event) {
                        var rutaimagen = event.target.result;
                        $('#fotoLogo').attr("src", rutaimagen);
                    });
                }

            }
        });

    });

</script>

<!-- Crear y editar secciones -->
<script type="text/javascript">

    function paddedFormat(num) {
        return num < 10 ? "0" + num : num; 
    }

    function startCountDown(duration, element) {

        let secondsRemaining = duration;
        let min = 0;
        let sec = 0;

        countInterval = setInterval(function () {

            min = parseInt(secondsRemaining / 60);
            sec = parseInt(secondsRemaining % 60);

            element.textContent = `Generar campos automáticamente (${paddedFormat(sec)})`;

            secondsRemaining = secondsRemaining - 1;
            if (secondsRemaining < 0) { 
                element.textContent = "Generando campos";
                stopInterval();
                generaAgenda();
            }

        }, 1000);
    }

    function start(){
        let time_minutes = 0; // Value in minutes
        let time_seconds = 10; // Value in seconds

        let duration = time_minutes * 60 + time_seconds;

        element = document.querySelector('#crono');
        element.textContent = `Generar campos automáticamente (${paddedFormat(time_seconds)})`;

        startCountDown(--duration, element);
    }

    function stopInterval(){
        try {
            clearInterval(countInterval);
            element = document.querySelector('#crono');
            element.textContent = `Generar campos automáticamente`;
        } catch (error) {}
    }
    
    function generaAgenda(){
        // stopInterval();
        $("#crono").attr('disabled',true);
        $("#crearSeccionesModalButton").attr('disabled',false);
        $("#newFieldSeccion").click();

        let campo=$("#cuerpoTablaQuesedebeGuardar > div:nth-child(1)").attr("numero");
        $("#G6_C39_"+campo).val('citas');
        $("#G6_C40_"+campo).html("<option value='12'>Subformulario</option>").val('12').trigger('change');
        $("#G6_C43_"+campo).val($("#idAgendador option:selected").attr('guion')).trigger('change');
        $("#G6_C43_"+campo).html("<option value='"+$("#G6_C43_"+campo).val()+"'>"+$("#G6_C43_"+campo+" option:selected").html()+"</option>");
        $("#GuidetM_"+campo).val($("#ccAgendador").val());
        $("#Guidet_"+campo).val($("#idAgendador option:selected").attr('cc'));
        $("#btnEliminarField_"+campo).css('display','none');
        $("#btnAvanzadosField_"+campo).css('display','none');
        $("#G6_C41_"+campo).css('display','none');
        $("#idAgendador").attr('disabled',true);
        $("#ccAgendador").attr('disabled',true);
    }

    function changeAgenda(val,val2){
        // stopInterval();
        $("#cuerpoTablaQuesedebeGuardar").html('');
        if(Number(val) > 0 && Number(val2) > 0){
            // start();
            $("#crono").attr('disabled',false);
        }else{
            $("#crono").attr('disabled',true);
        }
    }


    $(function() {

        $("#G7_C35").numeric();
        $("#idAgendador").select2();
        $("#ccAgendador").select2();

        $("#G7_C36").change(function() {
            if ($(this).val() == '3') {
                $("#cerradoAcordeon").show();
                //$("#G7_C37").prop('checked', true);
            } else {
                $("#cerradoAcordeon").hide();
                $("#G7_C37").prop('checked', false);
            }
        });

        $("#EditarG7_C36").change(function() {
            if ($(this).val() == '3') {
                $("#EditarcerradoAcordeon").show();
                //$("#G7_C37").prop('checked', true);
            } else {
                $("#EditarcerradoAcordeon").hide();
                $("#EditarG7_C37").prop('checked', false);
            }
        });

        $("#G7_C38").change(function(){
            $("#cuerpoTablaQuesedebeGuardar").html('');
            // stopInterval();
            if($(this).val() == '5'){
                $("#agenda").removeAttr('hidden');
                $(".busquedaCampo").css('display','none');
                $("#newFieldSeccion").attr('disabled',true);
                $("#crearSeccionesModalButton").attr('disabled',true);
                $("#idAgendador").attr('disabled',false);
                $("#ccAgendador").attr('disabled',false);

                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'POST',
                    data: {
                        getAgendadores: 'SI',
                    },
                    success: function(data) {
                        $("#idAgendador").html(data);
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
                
                $.ajax({
                    url: '<?=$url_crud_extender?>?camposGuion=si',
                    type: 'POST',
                    data: {
                        guion: $("#hidId").val(),
                        esMaster :'si'
                    },
                    success: function(data) {
                        $("#ccAgendador").html("<option value='0'>Seleccione</option>");
                        $("#ccAgendador").append(data);
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
            }else{
                $("#agenda").attr('hidden','hidden');
                $("#newFieldSeccion").removeAttr('disabled');
                $("#crearSeccionesModalButton").removeAttr('disabled');
                $(".busquedaCampo").css('display','initial');
            }
        });

        $("#EditarG7_C38").change(function(){
            if($(this).val() == '5'){
                $("#agendaE").removeAttr('hidden');
                $("#idAgendador").attr('disabled',true);
                $("#ccAgendador").attr('disabled',true);
                $(".busquedaCampo").css('display','none');
                let campo=$("#cuerpoTablaQuesedebeGuardarEdicion > div:nth-child(1)").attr("numero");
                $("#G6_C40_"+campo).html("<option value='12'>Subformulario</option>").val('12').trigger('change');
                $("#G6_C43_"+campo).html("<option value='"+$("#G6_C43_"+campo).val()+"'>"+$("#G6_C43_"+campo+" option:selected").html()+"</option>").trigger('change');
                $("#btnEliminarField_"+campo).css('display','none');
                $("#btnAvanzadosField_"+campo).css('display','none');
                $("#newFieldSeccion2").attr('disabled',true);
                $("#G6_C41_"+campo).css('display','none');

                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'POST',
                    data: {
                        getAgendadorGuion: 'SI',
                        id:$("#G6_C43_"+campo).val()
                    },
                    success: function(data) {
                        $("#idAgendador2").html(data);
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });

                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'POST',
                    data: {
                        getCampoCcAgendador: 'SI',
                        hijo:$("#G6_C43_"+campo).val(),
                        padre:$("#hidId").val()
                    },
                    success: function(data) {
                        $("#ccAgendador2").html(data);
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });

            }else{
                $("#agendaE").attr('hidden','hidden');
                $("#newFieldSeccion2").removeAttr('disabled');
                $(".busquedaCampo").css('display','initial');
            }
        });
        
        $("#newAgendador").click(function(){
            $("#iframeAgenda").attr('src',"<?=base_url?>index.php?page=agendador&view=si&add=si");
        });

        $("#editAgendador").click(function(){
            let id=$("#idAgendador").val();
            $("#iframeAgenda").attr('src',"<?=base_url?>index.php?page=agendador&view=si&edit=si&id="+id);
        });

        $("#idAgendador").change(function(){
            let val2=$("#ccAgendador").val();
            changeAgenda($(this).val(),val2);
        });

        $("#ccAgendador").change(function(){
            let val2=$("#idAgendador").val();
            changeAgenda($(this).val(),val2);
        });

        var iseccion = 1;
        iseccion = $("#secciones_creadas").length;
        if (iseccion == 0) {
            iseccion = 1;
        }

        $("#btnAddMoreSeccion").click(function() {
            $("#crearseccionesModalForm")[0].reset();
            $("#cerradoAcordeon").show();
            $("#cuerpoTablaQuesedebeGuardar").html('');
            addOptSec();
            $("#crearSeccionesModal").modal();
            $("#cuantoscampos").val(0);
            $("#G7_C38").val('1').change();
        });


        var campo = 0;
        $("#newFieldSeccion").click(function() {
            editaCampos=parseInt($("#cuantoscampos").val())+1;
            var cuerpoTablaQuesedebeGuardar = '';
            cuerpoTablaQuesedebeGuardar = '<div class="row" id="campos_' + editaCampos + '" numero="' + editaCampos + '">' +
                '<div class="col-md-4"  style="text-align:center;"><i class="fa fa-arrows-v pull-left"></i><input type="text" class="form-control input-sm textoMostrado" style="width:95%;" id="G6_C39_' + editaCampos + '" value=""  name="G6_C39_' + editaCampos + '"  placeholder="<?php echo $str_campo_texto___; ?>"></div>' +
                '<div class="col-md-3"  style="text-align:center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_' + editaCampos + '" id="G6_C40_' + editaCampos + '" atrId = "' + editaCampos + '">' +
                '<?php
                                                echo '<option value="1">'.$str_tipoCamp1_____.'</option>';
                                                echo '<option value="2">'.$str_tipoCamp2_____.'</option>';
                                                echo '<option value="14">'.$str_tipoCamp14____.'</option>';
                                                echo '<option value="3">'.$str_tipoCamp3_____.'</option>';
                                                echo '<option value="4">'.$str_tipoCamp4_____.'</option>';
                                                echo '<option value="5">'.$str_tipoCamp5_____.'</option>';
                                                echo '<option value="10">'.$str_tipoCamp6_____.'</option>';
                                                echo '<option value="6">'.$str_tipoCamp7_____.'</option>';
                                                echo '<option value="11">'.$str_tipoCamp8_____.'</option>';
                                                echo '<option value="13">'.$str_tipoCamp9_____.'</option>';
                                                echo '<option value="8">'.$str_tipoCamp10_____.'</option>';
                                                echo '<option value="9">'.$str_tipoCamp11_____.'</option>';
                                                echo '<option value="12">'.$str_tipoCamp12_____.'</option>';
                                                echo '<option value="15">Adjunto(Max 9 MB)</option>';
                                                echo '<option value="16">Boton consulta de correo</option>';
                                                echo '<option value="17">Boton</option>';

                                            ?>' +
                '</select></div>' +
                '<div class="col-md-3"  style="text-align:center;" id="select2Listas_' + editaCampos + '"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_' + editaCampos + '" id="G6_C44_' + editaCampos + '" cmpo = "' + editaCampos + '">' + ListasBd + '</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "' + editaCampos + '" id="nuevaListaModalH_' + editaCampos + '" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_' + editaCampos + '" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "' + editaCampos + '" class="pull-right">Editar Lista</a></div>' +

                '<div class="col-md-3" style="text-align:center;display:none;" id="select2ListasCompuestas_' + editaCampos + '"><select disabled class="form-control input-sm str_Select2" style="width: 100%;" name="G6_C43_' + editaCampos + '" id="G6_C43_' + editaCampos + '" cmpo="' + editaCampos + '">' + GuionesBD + '</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "' + editaCampos + '" id="copuestas_' + editaCampos + '" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="copuestas_' + editaCampos + '" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "' + editaCampos + '" class="pull-right">Editar Lista</a></div>' +
                '<div class="col-md-1"  style="text-align:center;display:none"><label><input type="checkbox" value="-1" name="G6_C41_' + editaCampos + '" id="G6_C41_' + editaCampos + '"></label></div>' +
                '<div class="col-md-2" style="text-align:left;">' +
                '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="' + editaCampos + '" id="btnAvanzadosField_' + editaCampos + '"><i class="fa fa-cog"></i></button>' +
                '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField" id="btnEliminarField_' + editaCampos + '" title="<?php echo $str_campo_delet___;?>" valorField="' + editaCampos + '" ><i class="fa fa-trash-o"></i></button></div>' +
                '<input type="hidden" name="GuidetM_' + editaCampos + '" id="GuidetM_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="Guidet_' + editaCampos + '" id="Guidet_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="GuidetPadre_' + editaCampos + '" id="GuidetPadre_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="GuidetHijo_' + editaCampos + '" id="GuidetHijo_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="GuidetOper_' + editaCampos + '" id="GuidetOper_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="modoGuidet_' + editaCampos + '" id="modoGuidet_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C46_' + editaCampos + '" id="G6_C46_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C207_' + editaCampos + '" id="G6_C207_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C53_' + editaCampos + '" id="G6_C53_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C54_' + editaCampos + '" id="G6_C54_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C55_' + editaCampos + '" id="G6_C55_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C56_' + editaCampos + '" id="G6_C56_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C57_' + editaCampos + '" id="G6_C57_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C58_' + editaCampos + '" id="G6_C58_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C51_' + editaCampos + '" id="G6_C51_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C42_' + editaCampos + '" id="G6_C42_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C52_' + editaCampos + '" id="G6_C52_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C318_' + editaCampos + '" id="G6_C318_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C319_' + editaCampos + '" id="G6_C319_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C320_' + editaCampos + '" id="G6_C320_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C321_' + editaCampos + '" id="G6_C321_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C322_' + editaCampos + '" id="G6_C322_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C323_' + editaCampos + '" id="G6_C323_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C324_' + editaCampos + '" id="G6_C324_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C325_' + editaCampos + '" id="G6_C325_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C326_' + editaCampos + '" id="G6_C326_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C327_' + editaCampos + '" id="G6_C327_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C328_' + editaCampos + '" id="G6_C328_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C329_' + editaCampos + '" id="G6_C329_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C330_' + editaCampos + '" id="G6_C330_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C331_' + editaCampos + '" id="G6_C331_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C332_' + editaCampos + '" id="G6_C332_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C333_' + editaCampos + '" id="G6_C333_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C334_' + editaCampos + '" id="G6_C334_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C335_' + editaCampos + '" id="G6_C335_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C336_' + editaCampos + '" id="G6_C336_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C337_' + editaCampos + '" id="G6_C337_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C338_' + editaCampos + '" id="G6_C338_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="G6_C339_' + editaCampos + '" id="G6_C339_' + editaCampos + '"  value="253">' +
                '<input type="hidden" name="depende_' + editaCampos + '" id="depende_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="nuevo_campo_' + editaCampos + '" id="nuevo_campo_' + editaCampos + '"  value="0">' +
                '<input type="hidden" name="rowListaCompleja_' + editaCampos + '" id="rowListaCompleja_' + editaCampos + '"  value="-1">' +

                '<div id="configdataEto_' + editaCampos + '"></div>' +
                '</div>';

            $("#cuerpoTablaQuesedebeGuardar").append(cuerpoTablaQuesedebeGuardar);

            $(".str_SelectTipo").change(function() {
                var id = $(this).attr('atrId');
                if ($(this).val() == 6 || $(this).val() == 13) {
                    $("#G6_C44_" + id).attr('disabled', false);
                    $("#nuevaListaModalH_" + id).show();
                    $("#G6_C43_" + id).attr('disabled', true);
                    $("#select2ListasCompuestas_" + id).hide();
                    $("#select2Listas_" + id).show();

                } else if ($(this).val() == 11 || $(this).val() == 12) {

                    $("#G6_C43_" + id).attr('disabled', false);
                    $("#select2ListasCompuestas_" + id).show();
                    $("#select2Listas_" + id).hide();
                    $("#G6_C44_" + id).attr('disabled', true);


                } else {

                    $("#G6_C44_" + id).attr('disabled', true);
                    $("#nuevaListaModalH_" + id).hide();

                    $("#G6_C43_" + id).attr('disabled', true);
                    $("#select2ListasCompuestas_" + id).hide();
                    $("#select2Listas_" + id).show();

                    if($(this).val() == 14){
                        $("#G6_C339_" + id).val(100);
                    }else{
                        $("#G6_C339_" + id).val(253);
                    }
                }

            });

            $("#G6_C44_" + editaCampos).select2({
                dropdownParent: $("#campos_" + editaCampos)
            });

            $("#G6_C43_" + editaCampos).select2({
                dropdownParent: $("#campos_" + editaCampos)
            });

            $("#btnAvanzadosField_" + editaCampos).click(function() {
                var id = $(this).attr('valorField');
                tipoCampo = $("#G6_C40_" + id).val();
                $("#guardarConfiguracion").attr("numero", id);
                mostrarModalConfiguracion(tipoCampo, id);
            });

            $("#btnEliminarField_" + editaCampos).click(function() {
                let id = $(this).attr('valorField');
                $("#campos_" + id).remove();
                calcularContadorCampos('cuantoscampos')
            });

            $("#G6_C44_" + editaCampos).select2();

            $("#G6_C44_" + editaCampos).change(function() {
                var id = $(this).attr('cmpo');
                if ($(this).val() != 0) {
                    $("#editarLista_" + id).show();
                } else {
                    $("#editarLista_" + id).hide();
                }
            });

            $("#editarLista_" + editaCampos).click(function() {
                var id = $(this).attr('cmpo');
                var tipo = $("#G6_C40_" + id).val();
                $("#TipoListas").val(tipo);
                $("#agrupador")[0].reset();
                $("#opciones").html('');
                contador = 0;
                $("#hidListaInvocada").val(id);
                if (tipo == '13') {
                    $.ajax({
                        url: '<?=$url_crud_extender?>',
                        type: 'post',
                        data: {
                            getListasEdit: true,
                            idOpcion: $("#G6_C44_" + id).val()
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        },
                        complete: function() {
                            $.unblockUI();
                        },
                        success: function(data) {
                            if (data.code == '1') {
                                $("#idListaE").val(data.id);
                                $("#txtNombreLista").val(data.opcion);
                                $.each(data.lista, function(i, items) {
                                    var cuerpo = "<div class='row' id='id_" + i + "'>";
                                    cuerpo += "<div class='col-md-5'>";
                                    cuerpo += "<div class='form-group'>";
                                    cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    cuerpo += "<div class='col-md-5'>";
                                    cuerpo += "<div class='form-group'>";
                                    cuerpo += "<input type='text' name='Respuesta_" + i + "' id='Respuesta_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Respuesta_b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                    cuerpo += "<div class='form-group'>";
                                    cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "' value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    cuerpo += "</div>";
                                    $("#opciones").append(cuerpo);
                                });
                                $("#opciones").append("<input type='hidden' name='contadorEditables' value='" + data.total + "'>");
                                contador = data.total;
                                //console.log(contador);
                                $("#operLista").val("edit");

                                $(".deleteopcionP").click(function() {
                                    var id = $(this).attr('value');
                                    var miId = $(this).attr('id');
                                    alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                        if (e) {
                                            $.ajax({
                                                url: '<?php echo $url_crud; ?>',
                                                type: 'post',
                                                data: {
                                                    deleteOption: true,
                                                    id: id
                                                },
                                                beforeSend: function() {
                                                    $.blockUI({
                                                        baseZ: 2000,
                                                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                    });
                                                },
                                                complete: function() {
                                                    $.unblockUI();
                                                },
                                                success: function(data) {
                                                    if (data == '1') {
                                                        alertify.success('<?php echo $str_Exito;?>');
                                                        $("#id_" + miId).remove();
                                                    } else {
                                                        alertify.error('Error');
                                                    }
                                                }
                                            })

                                        }
                                    });
                                });
                            }
                        }
                    });
                } else {
                    $.ajax({
                        url: '<?=$url_crud_extender?>',
                        type: 'post',
                        data: {
                            getListasEdit: true,
                            idOpcion: $("#G6_C44_" + id).val()
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        },
                        complete: function() {
                            $.unblockUI();
                        },
                        success: function(data) {
                            if (data.code == '1') {
                                $("#idListaE").val(data.id);
                                $("#txtNombreLista").val(data.opcion);

                                $.ajax({
                                    url: '<?=$url_crud_extender?>',
                                    type: 'POST',
                                    data: {
                                        getCamposLista: 'SI',
                                        guion: $("#hidId").val()
                                    },
                                    success: function(data) {
                                        $("#txtPreguntaPadre").html(data);
                                        $("#txtPreguntaPadre").val($("#depende_" + id).val());
                                        //$("#txtPreguntaPadre").attr('readonly', true);
                                        $("#preguntaDependiente").show();
                                    }
                                });


                                $.each(data.lista, function(i, items) {
                                    if ($("#depende_" + id).val() != 0 && $("#depende_" + id).val() != '') {

                                        $.ajax({
                                            url: '<?=$url_crud_extender?>',
                                            type: 'post',
                                            Async: false,
                                            data: {
                                                getDatosListaByPregun: true,
                                                preguntaLista: $("#depende_" + id).val()
                                            },
                                            success: function(options) {
                                                datosJsonguion = options;
                                                var cuerpo = "<div class='row' id='id_" + i + "'>";
                                                cuerpo += "<div class='col-md-5'>";
                                                cuerpo += "<div class='form-group'>";
                                                cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas'  value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";
                                                cuerpo += "<div class='col-md-5'>";
                                                cuerpo += "<div class='form-group'>";
                                                cuerpo += "<select type='text' name='OpcionPadre_" + i + "' id='OpcionPadre_" + i + "' class='form-control opcionesGeneradas' placeholder='<?php echo $str_OpcionDependen_; ?>'>";
                                                cuerpo += datosJsonguion;
                                                cuerpo += "</select>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";
                                                cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                cuerpo += "<div class='form-group'>";
                                                cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "'  value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";

                                                $("#opciones").append(cuerpo);
                                                $("#OpcionPadre_" + i).val(items.LISOPC_ConsInte__LISOPC_Depende_b);
                                                $("#OpcionPadre_" + i).val(items.LISOPC_ConsInte__LISOPC_Depende_b).change();
                                            }
                                        });


                                    } else {
                                        var cuerpo = "<div class='row' id='id_" + i + "'>";
                                        cuerpo += "<div class='col-md-10'>";
                                        cuerpo += "<div class='form-group'>";
                                        cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                        cuerpo += "</div>";
                                        cuerpo += "</div>";
                                        cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                        cuerpo += "<div class='form-group'>";
                                        cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "' value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                        cuerpo += "</div>";
                                        cuerpo += "</div>";
                                        cuerpo += "</div>";
                                        $("#opciones").append(cuerpo);
                                    }


                                });
                                $("#opciones").append("<input type='hidden' name='contadorEditables' value='" + data.total + "'>");
                                contador = data.total;
                                //console.log(contador);
                                $("#operLista").val("edit");

                                $(".deleteopcionP").click(function() {
                                    var id = $(this).attr('value');
                                    var miId = $(this).attr('id');
                                    alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                        if (e) {
                                            $.ajax({
                                                url: '<?php echo $url_crud; ?>',
                                                type: 'post',
                                                data: {
                                                    deleteOption: true,
                                                    id: id
                                                },
                                                beforeSend: function() {
                                                    $.blockUI({
                                                        baseZ: 2000,
                                                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                    });
                                                },
                                                complete: function() {
                                                    $.unblockUI();
                                                },
                                                success: function(data) {
                                                    if (data == '1') {
                                                        alertify.success('<?php echo $str_Exito;?>');
                                                        $("#id_" + miId).remove();
                                                    } else {
                                                        alertify.error('Error');
                                                    }
                                                }
                                            })

                                        }
                                    });
                                });
                            }
                        }
                    });
                }


            });

            $("#nuevaListaModalH_" + campo).click(function() {
                $("#agrupador")[0].reset();
                $("#opciones").html('');
                contador = 0;
                $("#hidListaInvocada").val($(this).attr('cmpo'));
                $("#operLista").val('add');
                $("#idListaE").val('0');
                var tipo = $("#G6_C40_" + $(this).attr('cmpo')).val();
                if (tipo == '6') {
                    $("#preguntaDependiente").show();
                    $.ajax({
                        url: '<?=$url_crud_extender?>',
                        type: 'POST',
                        data: {
                            getCamposLista: 'SI',
                            guion: $("#hidId").val(),
                            tipoSec: $("#G7_C38").val()
                        },
                        success: function(data) {
                            $("#txtPreguntaPadre").html(data);
                        },
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        },
                        complete: function() {
                            $.unblockUI();
                        }
                    });
                } else {
                    $("#preguntaDependiente").hide();
                }

                $("#TipoListas").val(tipo);
            });

            $("#cuerpoTablaQuesedebeGuardar").sortable({
                axis: 'y',
                update: function(event, ui) {
                    var sortableLinks = $("#cuerpoTablaQuesedebeGuardar");
                    var data = $(sortableLinks).sortable('serialize');
                    //alert(data);
                    $("#orderCamposCrearSecciones").val(data);
                }
            });
            $("#cuantoscampos").val((Number(editaCampos)));
        });



        $("#newFieldSeccion2").click(function() {
            editaCampos = $("#contadorEditablesPreguntas").val();
            if (parseInt(editaCampos) > 29) {
                alertify.warning('Se ha alcanzado el limite de campos maximo por sección,por favor cree una nueva seccion');
            } else {
                var cuerpoTablaQuesedebeGuardar = '';
                cuerpoTablaQuesedebeGuardar = '<div class="row rowCampos" id="campos_' + editaCampos + '" numero="' + editaCampos + '">' +
                    '<div class="col-md-4"  style="text-align:center;"><i class="fa fa-arrows-v pull-left"></i><input type="text" class="form-control input-sm textoMostrado" style="width:95%;" id="G6_C39_' + editaCampos + '" value=""  name="G6_C39_' + editaCampos + '"  placeholder="<?php echo $str_campo_texto___; ?>"></div>' +
                    '<div class="col-md-3"  style="text-align:center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_' + editaCampos + '" id="G6_C40_' + editaCampos + '" atrId = "' + editaCampos + '">' +
                    '<?php
                                                echo '<option value="1">'.$str_tipoCamp1_____.'</option>';
                                                echo '<option value="2">'.$str_tipoCamp2_____.'</option>';
                                                echo '<option value="14">'.$str_tipoCamp14____.'</option>';
                                                echo '<option value="3">'.$str_tipoCamp3_____.'</option>';
                                                echo '<option value="4">'.$str_tipoCamp4_____.'</option>';
                                                echo '<option value="5">'.$str_tipoCamp5_____.'</option>';
                                                echo '<option value="10">'.$str_tipoCamp6_____.'</option>';
                                                echo '<option value="6">'.$str_tipoCamp7_____.'</option>';
                                                echo '<option value="11">'.$str_tipoCamp8_____.'</option>';
                                                echo '<option value="13">'.$str_tipoCamp9_____.'</option>';
                                                echo '<option value="8">'.$str_tipoCamp10_____.'</option>';
                                                echo '<option value="9">'.$str_tipoCamp11_____.'</option>';
                                                echo '<option value="12">'.$str_tipoCamp12_____.'</option>';
                                                echo '<option value="15">Adjunto(Max 9 MB)</option>';
                                                echo '<option value="16">Boton consulta de correo</option>';
                                                echo '<option value="17">Boton</option>';

                                            ?>' +
                    '</select></div>' +

                    '<div class="col-md-3"  style="text-align:center;" id="select2Listas_' + editaCampos + '"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_' + editaCampos + '" id="G6_C44_' + editaCampos + '" cmpo = "' + editaCampos + '">' + ListasBd + '</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "' + editaCampos + '" id="nuevaListaModalH_' + editaCampos + '" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_' + editaCampos + '" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "' + editaCampos + '" class="pull-right">Editar Lista</a></div>' +

                    '<div class="col-md-3"  style="text-align:center;display:none;" id="select2ListasCompuestas_' + editaCampos + '"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C43_' + editaCampos + '" id="G6_C43_' + editaCampos + '" cmpo = "' + editaCampos + '">' + GuionesBD + '</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "' + editaCampos + '" id="copuestas_' + editaCampos + '" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="copuestas_' + editaCampos + '" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "' + editaCampos + '" class="pull-right">Editar Lista</a></div>' +

                    '<div class="col-md-1"  style="text-align:center;display:none"><label><input type="checkbox" value="-1" name="G6_C41_' + editaCampos + '" id="G6_C41_' + editaCampos + '"></label></div>' +
                    '<div class="col-md-2" style="text-align:left;">' +
                    '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="' + editaCampos + '" id="btnAvanzadosField_' + editaCampos + '"><i class="fa fa-cog"></i></button>' +
                    '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField" id="btnEliminarField_' + editaCampos + '" title="<?php echo $str_campo_delet___;?>" valorField="' + editaCampos + '" ><i class="fa fa-trash-o"></i></button></div>' +
                    '<input type="hidden" name="GuidetM_' + editaCampos + '" id="GuidetM_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="Guidet_' + editaCampos + '" id="Guidet_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="GuidetPadre_' + editaCampos + '" id="GuidetPadre_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="GuidetHijo_' + editaCampos + '" id="GuidetHijo_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="GuidetOper_' + editaCampos + '" id="GuidetOper_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="modoGuidet_' + editaCampos + '" id="modoGuidet_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C46_' + editaCampos + '" id="G6_C46_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C207_' + editaCampos + '" id="G6_C207_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C53_' + editaCampos + '" id="G6_C53_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C54_' + editaCampos + '" id="G6_C54_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C55_' + editaCampos + '" id="G6_C55_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C56_' + editaCampos + '" id="G6_C56_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C57_' + editaCampos + '" id="G6_C57_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C58_' + editaCampos + '" id="G6_C58_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C51_' + editaCampos + '" id="G6_C51_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C42_' + editaCampos + '" id="G6_C42_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C52_' + editaCampos + '" id="G6_C52_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C318_' + editaCampos + '" id="G6_C318_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C319_' + editaCampos + '" id="G6_C319_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C320_' + editaCampos + '" id="G6_C320_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C321_' + editaCampos + '" id="G6_C321_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C322_' + editaCampos + '" id="G6_C322_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C323_' + editaCampos + '" id="G6_C323_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C324_' + editaCampos + '" id="G6_C324_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C325_' + editaCampos + '" id="G6_C325_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C326_' + editaCampos + '" id="G6_C326_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C327_' + editaCampos + '" id="G6_C327_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C328_' + editaCampos + '" id="G6_C328_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C329_' + editaCampos + '" id="G6_C329_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C330_' + editaCampos + '" id="G6_C330_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C331_' + editaCampos + '" id="G6_C331_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C332_' + editaCampos + '" id="G6_C332_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C333_' + editaCampos + '" id="G6_C333_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C334_' + editaCampos + '" id="G6_C334_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C335_' + editaCampos + '" id="G6_C335_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C336_' + editaCampos + '" id="G6_C336_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C337_' + editaCampos + '" id="G6_C337_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C338_' + editaCampos + '" id="G6_C338_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="G6_C339_' + editaCampos + '" id="G6_C339_' + editaCampos + '"  value="253">' +
                    '<input type="hidden" name="depende_' + editaCampos + '" id="depende_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="nuevo_campo_' + editaCampos + '" id="nuevo_campo_' + editaCampos + '"  value="0">' +
                    '<input type="hidden" name="rowListaCompleja_' + editaCampos + '" id="rowListaCompleja_' + editaCampos + '"  value="-1">' +
                    '<div id="configdataEto_' + editaCampos + '"></div>' +
                    '</div>';

                $("#cuerpoTablaQuesedebeGuardarEdicion").append(cuerpoTablaQuesedebeGuardar);

                $(".str_SelectTipo").change(function() {
                    var id = $(this).attr('atrId');
                    if ($(this).val() == 6 || $(this).val() == 13) {
                        $("#G6_C44_" + id).attr('disabled', false);
                        $("#nuevaListaModalH_" + id).show();
                        $("#G6_C43_" + id).attr('disabled', true);
                        $("#select2ListasCompuestas_" + id).hide();
                        $("#select2Listas_" + id).show();

                    } else if ($(this).val() == 11 || $(this).val() == 12) {

                        $("#G6_C43_" + id).attr('disabled', false);
                        $("#select2ListasCompuestas_" + id).show();
                        $("#select2Listas_" + id).hide();
                        $("#G6_C44_" + id).attr('disabled', true);
                        $("#nuevaListaModalH_" + id).hide();
                        $("#editarLista_" + id).hide();

                    } else {

                        $("#G6_C44_" + id).attr('disabled', true);
                        $("#nuevaListaModalH_" + id).hide();

                        $("#G6_C43_" + id).attr('disabled', true);
                        $("#select2ListasCompuestas_" + id).hide();
                        $("#select2Listas_" + id).show();

                        if($(this).val() == 14){
                            $("#G6_C339_" + id).val(100);
                        }else{
                            $("#G6_C339_" + id).val(253);
                        }
                    }
                });

                $("#btnAvanzadosField_" + editaCampos).click(function() {
                    var id = $(this).attr('valorField');
                    tipoCampo = $("#G6_C40_" + id).val();
                    $("#guardarConfiguracion").attr("numero", id);
                    mostrarModalConfiguracion(tipoCampo, id);
                });

                $("#btnEliminarField_" + editaCampos).click(function() {
                    var id = $(this).attr('valorField');
                    $("#campos_" + id).remove();
                    calcularContadorCampos('contadorEditablesPreguntas')
                });
                $("#G6_C44_" + campo).select2();

                $("#G6_C44_" + editaCampos).change(function() {
                    var id = $(this).attr('cmpo');
                    if ($(this).val() != 0) {
                        $("#editarLista_" + id).show();
                    } else {
                        $("#editarLista_" + id).hide();
                    }
                });

                $("#G6_C44_" + editaCampos).select2({
                    dropdownParent: $("#campos_" + editaCampos)
                });

                $("#G6_C43_" + editaCampos).select2({
                    dropdownParent: $("#campos_" + editaCampos)
                });

                $("#editarLista_" + editaCampos).click(function() {
                    var id = $(this).attr('cmpo');
                    var tipo = $("#G6_C40_" + id).val();
                    $("#TipoListas").val(tipo);
                    $("#agrupador")[0].reset();
                    $("#opciones").html('');
                    contador = 0;
                    $("#hidListaInvocada").val(id);
                    if (tipo == '13') {
                        $.ajax({
                            url: '<?=$url_crud_extender?>',
                            type: 'post',
                            data: {
                                getListasEdit: true,
                                idOpcion: $("#G6_C44_" + id).val()
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                $.blockUI({
                                    baseZ: 2000,
                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                });
                            },
                            complete: function() {
                                $.unblockUI();
                            },
                            success: function(data) {
                                if (data.code == '1') {
                                    $("#idListaE").val(data.id);
                                    $("#txtNombreLista").val(data.opcion);
                                    $.each(data.lista, function(i, items) {
                                        var cuerpo = "<div class='row' id='id_" + i + "'>";
                                        cuerpo += "<div class='col-md-5'>";
                                        cuerpo += "<div class='form-group'>";
                                        cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                        cuerpo += "</div>";
                                        cuerpo += "</div>";
                                        cuerpo += "<div class='col-md-5'>";
                                        cuerpo += "<div class='form-group'>";
                                        cuerpo += "<input type='text' name='Respuesta_" + i + "' id='Respuesta_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Respuesta_b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
                                        cuerpo += "</div>";
                                        cuerpo += "</div>";
                                        cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                        cuerpo += "<div class='form-group'>";
                                        cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "' value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                        cuerpo += "</div>";
                                        cuerpo += "</div>";
                                        cuerpo += "</div>";
                                        $("#opciones").append(cuerpo);
                                    });
                                    $("#opciones").append("<input type='hidden' name='contadorEditables' value='" + data.total + "'>");
                                    contador = data.total;
                                    //console.log(contador);
                                    $("#operLista").val("edit");

                                    $(".deleteopcionP").click(function() {
                                        var id = $(this).attr('value');
                                        var miId = $(this).attr('id');
                                        alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                            if (e) {
                                                $.ajax({
                                                    url: '<?php echo $url_crud; ?>',
                                                    type: 'post',
                                                    data: {
                                                        deleteOption: true,
                                                        id: id
                                                    },
                                                    beforeSend: function() {
                                                        $.blockUI({
                                                            baseZ: 2000,
                                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                        });
                                                    },
                                                    complete: function() {
                                                        $.unblockUI();
                                                    },
                                                    success: function(data) {
                                                        if (data == '1') {
                                                            alertify.success('<?php echo $str_Exito;?>');
                                                            $("#id_" + miId).remove();
                                                        } else {
                                                            alertify.error('Error');
                                                        }
                                                    }
                                                })

                                            }
                                        });
                                    });
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            url: '<?=$url_crud_extender?>',
                            type: 'post',
                            data: {
                                getListasEdit: true,
                                idOpcion: $("#G6_C44_" + id).val()
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                $.blockUI({
                                    baseZ: 2000,
                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                });
                            },
                            complete: function() {
                                $.unblockUI();
                            },
                            success: function(data) {
                                if (data.code == '1') {
                                    $("#idListaE").val(data.id);
                                    $("#txtNombreLista").val(data.opcion);

                                    $.ajax({
                                        url: '<?=$url_crud_extender?>',
                                        type: 'POST',
                                        data: {
                                            getCamposLista: 'SI',
                                            guion: $("#hidId").val()
                                        },
                                        success: function(data) {
                                            $("#txtPreguntaPadre").html(data);
                                            $("#txtPreguntaPadre").val($("#depende_" + id).val());
                                            //$("#txtPreguntaPadre").attr('readonly', true);
                                            $("#preguntaDependiente").show();
                                        }
                                    });


                                    $.each(data.lista, function(i, items) {
                                        if ($("#depende_" + id).val() != 0 && $("#depende_" + id).val() != '') {

                                            $.ajax({
                                                url: '<?=$url_crud_extender?>',
                                                type: 'post',
                                                Async: false,
                                                data: {
                                                    getDatosListaByPregun: true,
                                                    preguntaLista: $("#depende_" + id).val()
                                                },
                                                success: function(options) {
                                                    datosJsonguion = options;
                                                    var cuerpo = "<div class='row' id='id_" + i + "'>";
                                                    cuerpo += "<div class='col-md-5'>";
                                                    cuerpo += "<div class='form-group'>";
                                                    cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas'  value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "<div class='col-md-5'>";
                                                    cuerpo += "<div class='form-group'>";
                                                    cuerpo += "<select type='text' name='OpcionPadre_" + i + "' id='OpcionPadre_" + i + "' class='form-control opcionesGeneradas' placeholder='<?php echo $str_OpcionDependen_; ?>'>";
                                                    cuerpo += datosJsonguion;
                                                    cuerpo += "</select>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                    cuerpo += "<div class='form-group'>";
                                                    cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "'  value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "</div>";

                                                    $("#opciones").append(cuerpo);
                                                    $("#OpcionPadre_" + i).val(items.LISOPC_ConsInte__LISOPC_Depende_b);
                                                    $("#OpcionPadre_" + i).val(items.LISOPC_ConsInte__LISOPC_Depende_b).change();
                                                }
                                            });


                                        } else {
                                            var cuerpo = "<div class='row' id='id_" + i + "'>";
                                            cuerpo += "<div class='col-md-10'>";
                                            cuerpo += "<div class='form-group'>";
                                            cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                            cuerpo += "</div>";
                                            cuerpo += "</div>";
                                            cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                            cuerpo += "<div class='form-group'>";
                                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "' value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                            cuerpo += "</div>";
                                            cuerpo += "</div>";
                                            cuerpo += "</div>";
                                            $("#opciones").append(cuerpo);
                                        }


                                    });
                                    $("#opciones").append("<input type='hidden' name='contadorEditables' value='" + data.total + "'>");
                                    contador = data.total;
                                    //console.log(contador);
                                    $("#operLista").val("edit");

                                    $(".deleteopcionP").click(function() {
                                        var id = $(this).attr('value');
                                        var miId = $(this).attr('id');
                                        alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                            if (e) {
                                                $.ajax({
                                                    url: '<?php echo $url_crud; ?>',
                                                    type: 'post',
                                                    data: {
                                                        deleteOption: true,
                                                        id: id
                                                    },
                                                    beforeSend: function() {
                                                        $.blockUI({
                                                            baseZ: 2000,
                                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                        });
                                                    },
                                                    complete: function() {
                                                        $.unblockUI();
                                                    },
                                                    success: function(data) {
                                                        if (data == '1') {
                                                            alertify.success('<?php echo $str_Exito;?>');
                                                            $("#id_" + miId).remove();
                                                        } else {
                                                            alertify.error('Error');
                                                        }
                                                    }
                                                })

                                            }
                                        });
                                    });
                                }
                            }
                        });
                    }


                });


                $("#nuevaListaModalH_" + editaCampos).click(function() {
                    $("#agrupador")[0].reset();
                    $("#opciones").html('');
                    contador = 0;
                    $("#hidListaInvocada").val($(this).attr('cmpo'));
                    $("#operLista").val('add');
                    $("#idListaE").val('0');
                    var tipo = $("#G6_C40_" + $(this).attr('cmpo')).val();
                    if (tipo == '6') {
                        $("#preguntaDependiente").show();
                        $.ajax({
                            url: '<?=$url_crud_extender?>',
                            type: 'POST',
                            data: {
                                getCamposLista: 'SI',
                                guion: $("#hidId").val(),
                                tipoSec: $("#EditarG7_C38").val()
                            },
                            success: function(data) {
                                $("#txtPreguntaPadre").html(data);
                            },
                            beforeSend: function() {
                                $.blockUI({
                                    baseZ: 2000,
                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                });
                            },
                            complete: function() {
                                $.unblockUI();
                            }
                        });
                    } else {
                        $("#preguntaDependiente").hide();
                    }

                    $("#TipoListas").val(tipo);
                });

                editaCampos++;
                $("#contadorEditablesPreguntas").val(editaCampos);

                $("#cuerpoTablaQuesedebeGuardarEdicion").sortable({
                    axis: 'y',
                    update: function(event, ui) {
                        var data = $(this).sortable('serialize');
                        //alert(data);
                        $("#orderCamposCrearSeccionesEdicion").val(data);
                    }
                });

                campo++;
            }
        });



        $(".limpiarSecciones").click(function() {
            $("#crearseccionesModalForm")[0].reset();
            $("#cerradoAcordeon").hide();
            $("#cuerpoTablaQuesedebeGuardar").html('');
            campo = 1;
        });


        $("#crearSeccionesModalButton").click(function() {
            var valido = 0;
            // nombre seccion
            if ($("#G7_C33").val() === "") {
                alertify.error('<?php echo $str_error_nombre__;?>');
                valido = 1;
            }

            //NUMERO DE COLUMNAS
            if ($("#G7_C35").val().length < 1) {
                alertify.error('<?php echo $str_error_numero__;?>');
                valido = 1;
            }
            //APARIENCIA
            if ($("#G7_C36").val() == '0') {
                alertify.error('<?php echo $str_error_Tipo____;?>');
                valido = 1;
            }
            // TIPO DE SECCIÓN
            if ($("#G7_C38").val() == -1) {
                alertify.error('<?php echo $str_error_Tipo____;?>');
                valido = 1;
            }

            var vacios = 0;
            $(".textoMostrado").each(function() {
                if ($(this).val().length < 1) {
                    vacios++;
                }
            });

            if (vacios > 0) {
                valido = 1;
                alertify.error('<?php echo $str_campo_eror_n__;?>');
            }
            
            let validosEnlaces=true;
            $("#cuerpoTablaQuesedebeGuardar .row").each(function(i) {
                const intFila_t = $(this).attr("numero");
                if($("#G6_C40_"+intFila_t).val() == '11' && $("#enlazados_"+intFila_t).length == 0){
                    validosEnlaces=false;
                    $("#btnAvanzadosField_"+intFila_t).css('border','3px solid red');
                    alertify.warning("Por favor configura los enlaces para la lista auxiliar del campo " + $("#G6_C39_"+intFila_t).val());
                }
            });

            if(!validosEnlaces){
                valido = 1;
            }

            if (valido == 0) {
                var form = $("#crearseccionesModalForm");
                var formData = new FormData($("#crearseccionesModalForm")[0]);
                formData.append('padre', $("#hidId").val());
                formData.append('contador', parseInt($("#cuantoscampos").val())+1);
                formData.append('insertarSecciones', 'si');
                $.ajax({
                    url: '<?=$url_crud?>',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    success: function(data) {
                        if (data != '0') {
                            getListasSistemas();

                            getSeccionesGuion();

                            //Toca es refrescar siempre y volver a poner los calores por defecto que habian

                            var primario = $("#G5_C31").val();
                            var segundar = $("#G5_C59").val();
                            var llave = $("#G5_C319").val();

                            $.ajax({
                                type: 'post',
                                url: '<?=$url_crud_extender?>?camposGuion=true',
                                data: {
                                    guion: $("#hidId").val(),
                                    esMaster: 'si',
                                    seccion: $("#IdseccionEdicion").val()
                                },
                                success: function(data) {
                                    $("#G5_C31").html(data);
                                    $("#G5_C59").html(data);
                                    $("#G5_C319").html(data);

                                    $("#G5_C31").val(primario);
                                    $("#G5_C31").val(primario).change();

                                    $("#G5_C59").val(segundar);
                                    $("#G5_C59").val(segundar).change();

                                    $("#G5_C319").val(llave);
                                    $("#G5_C319").val(llave).change();

                                    $("#G5_C31").attr('disabled', false);
                                    $("#G5_C59").attr('disabled', false);
                                    $("#G5_C319").attr('disabled', false);
                                },
                                complete: function() {
                                    $.unblockUI();
                                },
                            });
                        } else {
                            alertify.error("<?php echo $error_de_proceso ;?>");
                        }
                        $("#crearSeccionesModal").modal('hide');
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                    //si ha ocurrido un error
                    error: function() {
                        after_save_error();
                        alertify.error("<?php echo $error_de_red ;?>");
                    }
                });
            }

        });

        $("#editarSeccionesModalButton").click(function() {
            guardaSeccion(true);
        });

    });

</script>

<!-- Con este codigo vamos a manipular el advanced de los campos -->
<script type="text/javascript">
    $(function() {
        $('.modal').on('hidden.bs.modal', function(e) {
            if ($('.modal').hasClass('in')) {
                $('body').addClass('modal-open');
            }
        });


        $("#G6_C318").change(function() {

            var value = $(this).val();

            $("#texto_defecto").hide();
            $("#numero_defecto").hide();
            $(".formulaMatematica").hide();
            $("#tiempoCantidad").hide();

            if (value == '5002' || value == '5003') {
                $("#tiempoCantidad").show();
            } else if (value == '1000') {
                $("#texto_defecto").show();
            } else if (value == '3001') {
                $("#numero_defecto").show();
                $(".formulaMatematica").hide();

            } else if (value == '3003') {

                $.ajax({
                    url: '<?=$url_crud_extender?>?camposNumericos=true',
                    data: {
                        guion: $("#hidId").val()
                    },
                    type: 'post',
                    success: function(data) {
                        $("#ListaVariablesNumericas").html(data);
                        $(".formulaMatematica").show();
                        $("#numero_defecto").hide();
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    }
                });

            }
        });
        
        /**
        * si el id[$("#ConsInte_"+$("#idFila").val()).val()] es = a undefined. 
        * es porque el campo no existe y se esta creando, entonces es true.
        * si se esta editando el campo debe tener valor y se llama la funcion[validaRequerido(id)
        **/
        $('#G6_C51').on('change', function() {
            if ($(this).is(':checked')) {
                let id = $("#ConsInte_"+$("#idFila").val()).val();
                let valido = validarCrearSeccionesModal() === true ? true : validaRequerido(id);
                if(!valido){
                    $("#G6_C51").prop('checked', false);
                }
            }
        });

        $.fn.datepicker.dates['es'] = {
            days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
            daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            today: "Today",
            clear: "Clear",
            format: "yyyy-mm-dd",
            weekStart: 0
        };


        $("#G6_C55").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function(selected) {
            $('#G6_C56').val('');
            var minDate = new Date(selected.date.valueOf());
            $('#G6_C56').datepicker('setStartDate', minDate);
        });

        $("#G6_C56").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });


        $("#guardarConfiguracion").click(function() {
            $("#G6_C336").val("2");
            var valido=true;
            var intFilaCampo_t = $(this).attr("numero");

            var strNumListComp_t = "";

            $(".rowListaCompleja_" + intFilaCampo_t).each(function() {
                var intListaCom_t = $(this).attr("numero");
                strNumListComp_t += intListaCom_t + ",";
            });

            strNumListComp_t = strNumListComp_t.slice(0, -1);
            $("#rowListaCompleja_" + intFilaCampo_t).val(strNumListComp_t);

            var tipoCampo = $("#tipoCampo").val();
            var idFila = $("#idFila").val();

            if (tipoCampo == '3' || tipoCampo == '4') {

                if ($("#G6_C53").val().length > 1) {
                    $("#G6_C53_" + idFila).val($("#G6_C53").val());
                }

                if ($("#G6_C54").val().length > 1) {
                    $("#G6_C54_" + idFila).val($("#G6_C54").val());
                }

                if ($("#G6_C318").val() != 0) {
                    $("#G6_C318_" + idFila).val($("#G6_C318").val());
                }

                if ($("#G6_C319").val().length > 0) {
                    $("#G6_C319_" + idFila).val($("#G6_C319").val());
                }
                if($("#G6_C318").val()==3003){
                    if ($("#G6_C321").val().length > 0) {
                        if(ValidarFormula()){
                            $("#G6_C321_" + idFila).val($("#G6_C321").val());
                        }else{
                            valido=false;
                        }
                    }else{
                        alertify.warning('El campo "Formula para calcular el valor" debe completarse');
                        valido=false;
                    }
                }


                $("#G6_C337_" + idFila).val($("#G6_C337").val());

                $("#G6_C338_" + idFila).val($("#G6_C338").val());


                if ($("#G6_C42").is(':checked')) {
                    $("#G6_C42_" + idFila).val('-1');
                } else {
                    $("#G6_C42_" + idFila).val('0');
                }

                if ($("#G6_C51").is(':checked')) {
                    $("#G6_C51_" + idFila).val('-1');
                } else {
                    $("#G6_C51_" + idFila).val('0');
                }

                if ($("#G6_C52").is(':checked')) {
                    $("#G6_C52_" + idFila).val('-1');
                } else {
                    $("#G6_C52_" + idFila).val('0');
                }

                if ($("#G6_C320").is(':checked')) {
                    $("#G6_C320_" + idFila).val('2');
                } else {
                    $("#G6_C320_" + idFila).val('0');
                }

                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322_" + idFila).val('-1');
                } else {
                    $("#G6_C322_" + idFila).val('0');
                }

                if ($("#G6_C326").is(':checked')) {
                    $("#G6_C326_" + idFila).val('-1');
                } else {
                    $("#G6_C326_" + idFila).val('0');
                }

                if ($("#G6_C328").is(':checked')) {
                    $("#G6_C328_" + idFila).val('-1');
                    $("#G6_C331_" + idFila).val($("#G6_C331").val());

                    if ($("#G6_C330").val() != 0) {
                        $("#G6_C330_" + idFila).val($("#G6_C330").val());
                    }else{
                        valido=false;
                        alertify.error('Seleccione el proveedor a usar');
                    }

                    $("#G6_C334_" + idFila).val($("#G6_C334").val());

                    // if ($("#G6_C334").val() != '') {
                    // }else{
                    //     valido=false;
                    //     alertify.error('Falta el prefijo a usar');
                    // }
                } else {
                    $("#G6_C328_" + idFila).val('0');
                }

            } else if (tipoCampo == '5') {

                if ($("#G6_C318").val() != 0) {
                    $("#G6_C318_" + idFila).val($("#G6_C318").val());
                }

                if ($("#G6_C324").val() != "") {
                    $("#G6_C324_" + idFila).val($("#G6_C324").val());
                }
                if ($("#G6_C325").val() != 0) {
                    $("#G6_C325_" + idFila).val($("#G6_C325").val());
                }

                if ($("#G6_C55").val().length > 1) {
                    $("#G6_C55_" + idFila).val($("#G6_C55").val());
                }else{
                    if($("#G6_C55").attr('disabled') == 'disabled'){
                        $("#G6_C55_" + idFila).val('0001-01-01');
                    }else{
                        $("#G6_C55_" + idFila).val($("#G6_C55").val());
                    }
                }

                if ($("#G6_C56").val().length > 1) {
                    $("#G6_C56_" + idFila).val($("#G6_C56").val());
                }

                if ($("#G6_C42").is(':checked')) {
                    $("#G6_C42_" + idFila).val('-1');
                } else {
                    $("#G6_C42_" + idFila).val('0');
                }

                if ($("#G6_C51").is(':checked')) {
                    $("#G6_C51_" + idFila).val('-1');
                } else {
                    $("#G6_C51_" + idFila).val('0');
                }

                if ($("#G6_C52").is(':checked')) {
                    $("#G6_C52_" + idFila).val('-1');
                } else {
                    $("#G6_C52_" + idFila).val('0');
                }

                if ($("#G6_C320").is(':checked')) {
                    $("#G6_C320_" + idFila).val('2');
                } else {
                    $("#G6_C320_" + idFila).val('0');
                }

                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322_" + idFila).val('-1');
                } else {
                    $("#G6_C322_" + idFila).val('0');
                }

            } else if(tipoCampo == '6'){

                if ($("#G6_C318").val() != 0) {
                    $("#G6_C318_" + idFila).val($("#G6_C318").val());
                }

                if ($("#G6_C51").is(':checked')) {
                    $("#G6_C51_" + idFila).val('-1');
                } else {
                    $("#G6_C51_" + idFila).val('0');
                }

                if ($("#G6_C320").is(':checked')) {
                    $("#G6_C320_" + idFila).val('3');
                } else {
                    $("#G6_C320_" + idFila).val('0');
                }

                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322_" + idFila).val('-1');
                } else {
                    $("#G6_C322_" + idFila).val('0');
                }

                $("#G6_C337_" + idFila).val($("#G6_C337").val());

            } else if (tipoCampo == '10') {

                if ($("#G6_C57").val().length > 1) {
                    $("#G6_C57_" + idFila).val($("#G6_C57").val());
                } else {
                    $("#G6_C57_" + idFila).val('');
                }

                if ($("#G6_C58").val().length > 1) {
                    $("#G6_C58_" + idFila).val($("#G6_C58").val());
                } else {
                    $("#G6_C58_" + idFila).val('');
                }

                if ($("#G6_C42").is(':checked')) {
                    $("#G6_C42_" + idFila).val('-1');
                } else {
                    $("#G6_C42_" + idFila).val('0');
                }

                if ($("#G6_C51").is(':checked')) {
                    $("#G6_C51_" + idFila).val('-1');
                } else {
                    $("#G6_C51_" + idFila).val('0');
                }

                if ($("#G6_C52").is(':checked')) {
                    $("#G6_C52_" + idFila).val('-1');
                } else {
                    $("#G6_C52_" + idFila).val('0');
                }

                if ($("#G6_C320").is(':checked')) {
                    $("#G6_C320_" + idFila).val('2');
                } else {
                    $("#G6_C320_" + idFila).val('0');
                }

                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322_" + idFila).val('-1');
                } else {
                    $("#G6_C322_" + idFila).val('0');
                }

                $("#G6_C318_" + idFila).val($("#horaDefecto").val());

            } else if (tipoCampo == '11') {


                let nuevosCampos = $("#CuerpoTablaNuevosFields").html();
                if($("#CuerpoTablaNuevosFields .rowListaCompleja_" + idFila).length == 0){
                    valido=false;
                    alertify.warning("Debes configurar al menos un(1) enlace de la lista auxiliar");
                    $("#enlazados_" + idFila).remove();
                }else{
                    let vacios=0;
                    $("#CuerpoTablaNuevosFields .rowListaCompleja_" + idFila).each(function(){
                        const intFila=$(this).find('select').attr('fila');
                        if($("#CuerpoTablaNuevosFields #camposCOnfiguradosGuionAux_" + idFila + intFila).val() == '0'){
                            $("#camposCOnfiguradosGuionAux_" + idFila + intFila).css('border','2px solid red');
                            valido=false;
                        }

                        if($("#CuerpoTablaNuevosFields #camposCOnfiguradosGuionTo_" + idFila + intFila).val() == '0'){
                            if(vacios==0){
                                vacios++;
                            }else{
                                valido=false;
                                $("#camposCOnfiguradosGuionTo_" + idFila + intFila).css('border','2px solid red');
                            }
                        }
                    });

                    if(!vacios){
                        valido=false;
                        alertify.warning("La configuración no es la correcta");
                        $("#enlazados_" + idFila).remove();
                    }else{
                        if(!valido){
                            alertify.warning("Debes completar los campos");
                            $("#enlazados_" + idFila).remove();
                        }else{
                            $("#campos_"+idFila).append("<input type='hidden' id='enlazados_" + idFila + "'>");
                        }
                    }
                }
                
                $("#edicionCamposPregui").append('<div id="configdataEto_' + idFila + '" >' + nuevosCampos + "<input type='hidden' name='contadorEto_" + idFila + "' value='" + LongitudNuevosCampos + "'></div>");

                if ($("#G6_C42").is(':checked')) {
                    $("#G6_C42_" + idFila).val('-1');
                } else {
                    $("#G6_C42_" + idFila).val('0');
                }

                if ($("#G6_C51").is(':checked')) {
                    $("#G6_C51_" + idFila).val('-1');
                } else {
                    $("#G6_C51_" + idFila).val('0');
                }

                if ($("#G6_C52").is(':checked')) {
                    $("#G6_C52_" + idFila).val('-1');
                } else {
                    $("#G6_C52_" + idFila).val('0');
                }

                if ($("#G6_C320").is(':checked')) {
                    $("#G6_C320_" + idFila).val('2');
                } else {
                    $("#G6_C320_" + idFila).val('0');
                }

                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322_" + idFila).val('-1');
                } else {
                    $("#G6_C322_" + idFila).val('0');
                }

            } else if (tipoCampo == '12') {

                /* Esto es el campo tipo Guion */
                if ($("#Guidet").val() != 0) {
                    $("#Guidet_" + idFila).val($("#Guidet").val());
                }

                if ($("#GuidetM").val() != 0) {
                    $("#GuidetM_" + idFila).val($("#GuidetM").val());
                }

                if ($("#GuidetPadre").val() != 0) {
                    $("#GuidetPadre_" + idFila).val($("#GuidetPadre").val());
                }

                if ($("#GuidetHijo").val() != 0) {
                    $("#GuidetHijo_" + idFila).val($("#GuidetHijo").val());
                }

                if ($("#GuidetOper").val() != 0) {
                    $("#GuidetOper_" + idFila).val($("#GuidetOper").val());
                }

                if ($("#modoGuidet").is(":checked")) {
                    $("#modoGuidet_" + idFila).val(1);
                } else {
                    $("#modoGuidet_" + idFila).val(0);
                }


                if ($("#G6_C42").is(':checked')) {
                    $("#G6_C42_" + idFila).val('-1');
                } else {
                    $("#G6_C42_" + idFila).val('0');
                }

                if ($("#G6_C51").is(':checked')) {
                    $("#G6_C51_" + idFila).val('-1');
                } else {
                    $("#G6_C51_" + idFila).val('0');
                }

                if ($("#G6_C52").is(':checked')) {
                    $("#G6_C52_" + idFila).val('-1');
                } else {
                    $("#G6_C52_" + idFila).val('0');
                }

                if ($("#G6_C320").is(':checked')) {
                    $("#G6_C320_" + idFila).val('2');
                } else {
                    $("#G6_C320_" + idFila).val('0');
                }

                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322_" + idFila).val('-1');
                } else {
                    $("#G6_C322_" + idFila).val('0');
                }

                var form = $("#comunicaciones .selector");
                var formData = [];
                $.each(form, function(campo, item) {
                    formData[campo] = item;
                });
                $.ajax({
                    url: '<?=$url_crud_extender?>?insertarcamposcomunicacionsub=true',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    success: function(data) {
                        // console.log('data guardarConfiguracion :>> ', data);
                    },
                    complete: function() {
                        $.unblockUI();
                    },
                    error: function() {
                        after_save_error();
                        alertify.error("<?php echo $error_de_red ;?>");
                    }
                });

                var arrIDCamposGui = [];
                $('input[name=genero]').each(function() {
                    if ($(this).is(':checked')) {
                        arrIDCamposGui.push($(this).val());
                    }
                });

                $.ajax({
                url: '<?=$url_crud_extender?>?insertarcampossub=true',
                data: {
                    guion: $("#G6_C43_" + idFila).val(),
                    datos: arrIDCamposGui
                },
                type: 'post',
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                success: function(data) {
                },
                complete: function() {
                    $.unblockUI();
                }
            });

            } else if(tipoCampo == '16'){
                if ($("#G6_C327").is(':checked')) {
                    $("#G6_C327_" + idFila).val('-1');
                    if ($("#G6_C329").val() != 0) {
                        $("#G6_C329_" + idFila).val($("#G6_C329").val());
                    }else{
                        valido=false;
                        alertify.error('Seleccione el correo a usar');
                    }

                    if ($("#G6_C332").val() != 0) {
                        $("#G6_C332_" + idFila).val($("#G6_C332").val());
                    }else{
                        valido=false;
                        alertify.error('Seleccione el correo para buscar');
                    }

                    if ($("#G6_C333").val() != 0) {
                        $("#G6_C333_" + idFila).val($("#G6_C333").val());
                    }else{
                        valido=false;
                        alertify.error('Seleccione la base de datos');
                    }
                } else {
                    $("#G6_C327_" + idFila).val('0');
                }
            } else if(tipoCampo == '17'){
                if($("#G6_C335").val()=='0'){
                    valido=false;
                    alertify.error('Seleccione un WebService');
                }else{
                    $("#G6_C335_" + idFila).val($("#G6_C335").val());
                }

                if($("#G6_C336").val()=='0'){
                    valido=false;
                    alertify.error('Seleccione una forma de integrar el WebService');
                }else{
                    $("#G6_C336_" + idFila).val($("#G6_C336").val());
                }

                if(valido){
                    $('#iframeWS').contents().find('form').submit();
                }

            }else{
                if ($("#G6_C323").val() != "") {
                    $("#G6_C323_" + idFila).val($("#G6_C323").val());
                }
                if ($("#G6_C318").val() != 0) {
                    $("#G6_C318_" + idFila).val($("#G6_C318").val());
                }

                if ($("#G6_C42").is(':checked')) {
                    $("#G6_C42_" + idFila).val('-1');
                } else {
                    $("#G6_C42_" + idFila).val('0');
                }

                if ($("#G6_C51").is(':checked')) {
                    $("#G6_C51_" + idFila).val('-1');
                } else {
                    $("#G6_C51_" + idFila).val('0');
                }

                if ($("#G6_C52").is(':checked')) {
                    $("#G6_C52_" + idFila).val('-1');
                } else {
                    $("#G6_C52_" + idFila).val('0');
                }

                if ($("#G6_C320").is(':checked')) {
                    $("#G6_C320_" + idFila).val('2');
                } else {
                    $("#G6_C320_" + idFila).val('0');
                }

                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322_" + idFila).val('-1');
                } else {
                    $("#G6_C322_" + idFila).val('0');
                }

                if ($("#G6_C326").is(':checked')) {
                    $("#G6_C326_" + idFila).val('-1');
                } else {
                    $("#G6_C326_" + idFila).val('0');
                }

                if ($("#G6_C327").is(':checked')) {
                    $("#G6_C327_" + idFila).val('-1');
                    if ($("#G6_C329").val() != 0) {
                        $("#G6_C329_" + idFila).val($("#G6_C329").val());
                    }else{
                        valido=false;
                        alertify.error('Seleccione el correo a usar');
                    }
                } else {
                    $("#G6_C327_" + idFila).val('0');
                }

                if ($("#G6_C328").is(':checked')) {
                    $("#G6_C328_" + idFila).val('-1');
                    $("#G6_C331_" + idFila).val($("#G6_C331").val());

                    if ($("#G6_C330").val() != 0) {
                        $("#G6_C330_" + idFila).val($("#G6_C330").val());
                    }else{
                        valido=false;
                        alertify.error('Seleccione el proveedor a usar');
                    }

                    $("#G6_C334_" + idFila).val($("#G6_C334").val());
                    // if ($("#G6_C334").val() != '') {
                    // }else{
                    //     valido=false;
                    //     alertify.error('Falta el prefijo a usar');
                    // }
                } else {
                    $("#G6_C328_" + idFila).val('0');
                }

                if(tipoCampo == '1' || tipoCampo == '14'){
                    if(Number($("#G6_C339").val()) >= 1 && Number($("#G6_C339").val()) <= 253){
                        $("#G6_C339_" + idFila).val($("#G6_C339").val());
                    }else{                        
                        alertify.error("La longitud debe ser entre 1 y 253 caracteres");
                        valido=false;
                    }
                }
            }

            if(valido){
                $("#ConfiguracionesAdvancedModal").modal('hide');
            }

        });

        $("#addNewGuionConf").click(function() {

            LongitudNuevosCampos++;
            campo = "<tr id='idtr_" + $("#idFila").val() + LongitudNuevosCampos + "' numero=\"" + LongitudNuevosCampos + "\" class=\"rowListaCompleja_" + $("#idFila").val() + "\">";
            campo += "<td style='width:45%;'>";
            campo += "<select class='form-control' id='camposCOnfiguradosGuionAux_" + $("#idFila").val() + LongitudNuevosCampos + "' fila='" + LongitudNuevosCampos + "'>";
            campo += ListaCampoGuionAux;
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<select class='form-control' id='camposCOnfiguradosGuionTo_" + $("#idFila").val() + LongitudNuevosCampos + "' fila='" + LongitudNuevosCampos + "'>";
            campo += ListaCampoGuionMio;
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<button class='btn btn-sm btn-danger' id='delEstaRow_" + $("#idFila").val() + LongitudNuevosCampos + "' type='button' fila='" + LongitudNuevosCampos + "'><i class='fa fa-trash-o'></i></button>";
            campo += "</td>";
            campo += "<input type='hidden' value='0'  name='camposCOnfiguradosGuionAux_" + $("#idFila").val() + LongitudNuevosCampos + "' id='inputocultoAux_" + $("#idFila").val() + LongitudNuevosCampos + "' >";
            campo += "<input type='hidden' value='0'  name='camposCOnfiguradosGuionTo_" + $("#idFila").val() + LongitudNuevosCampos + "' id='inputocultoMio_" + $("#idFila").val() + LongitudNuevosCampos + "' >";

            campo += "<tr>";
            $("#CuerpoTablaNuevosFields").append(campo);

            $("#delEstaRow_" + $("#idFila").val() + LongitudNuevosCampos).click(function() {
                var id = $(this).attr('fila');
                $("#idtr_" + $("#idFila").val() + id).remove();
            });

            $("#camposCOnfiguradosGuionAux_" + $("#idFila").val() + LongitudNuevosCampos).change(function() {
                var id = $(this).attr('fila');
                $("#inputocultoAux_" + $("#idFila").val() + id).val($(this).val());
            });

            $("#camposCOnfiguradosGuionTo_" + $("#idFila").val() + LongitudNuevosCampos).change(function() {
                var id = $(this).attr('fila');
                $("#inputocultoMio_" + $("#idFila").val() + id).val($(this).val());
            });

        });

        //aqui manejamos la adicion y eliminación de nodos en la modal de comunicacion entre formularios
        $("#nuevaComunicacion").click(function() {
            TraerCamposSubformulario($("#hidId").val(), $("#G6_C43_" + $("#idFila").val()).val());
        });
    });

    //ESTA FUNCIÓN VALIDA QUE UN CAMPO CONFIGURADO COMO REQUERIDO NO ESTE SIENDO USADO EN SALTOS O SALTOS POR SECCIÓN
    function validaRequerido(id){
        let result=true;
        // $.ajax({
        //     url: '<?php // echo $url_crud_extender; ?>?validaRequerido=si',
        //     async:false,
        //     type:'POST',
        //     dataType:'JSON',
        //     data:{id:id},
        //     success:function(data){
        //         if(data.estado =='error'){
        //             alertify.error(data.mensaje);
        //             result=false;
        //         }
                
        //         if(data.estado=='warning'){
        //             alertify.warning(data.mensaje);
        //             result=false;
        //         }

        //     },
        //     beforeSend:function(){
        //         $.blockUI({
        //             baseZ: 2000,
        //             message: '<img src="<?php //echo base_url; ?>assets/img/clock.gif" /> <?php // echo $str_message_wait;?>'
        //         });
        //     },
        //     complete:function(){
        //         $.unblockUI();
        //     }
        // });

        return result;
    }

    function editaSeccion(id, tipoCampo = null, idFila = null) {
        $("#crearseccionesModalForm")[0].reset();
        $("#cerradoAcordeon").hide();
        $("#cuerpoTablaQuesedebeGuardar").html('');
        let contadorEditablesPreguntas = document.getElementById('contadorEditablesPreguntas')
        contadorEditablesPreguntas.value = 1
        var id = id;
        $("#edicionCamposPregui").html('');
        $.ajax({
            url: '<?=$url_crud?>',
            type: 'post',
            dataType: 'json',
            data: {
                getDatosSeccionesEdicion: true,
                id: id,
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                if (data.code === 1) {
                    $("#IdseccionEdicion").val(id);
                    $("#EditarG7_C33").val(data.sec_nom);
                    $("#EditarG7_C36").val(data.sec_Tip).change();
                    $("#EditarG7_C35").val(data.sec_num_col);
                    if (data.sec_mini != '' && data.sec_mini != 0) {

                    }
                    $("#EditarG7_C37").val(data.sec_mini);

                    var cuerpoTablaQuesedebeGuardar = '';
                    $("#cuerpoTablaQuesedebeGuardarEdicion").html('');
                    
                    data.datosPregun.forEach((item, index) => {
                        let campo = index + 1
                        contadorEditablesPreguntas.value = campo;
                        cuerpoTablaQuesedebeGuardar = '<div class="row rowCampos" id="campos_' + campo + '" numero="' + campo + '">' +
                            '<div class="col-md-4"  style="text-align:center;"><i class="fa fa-arrows-v pull-left"></i><input type="text" class="form-control input-sm textoMostrado" id="G6_C39_' + campo + '" style="width:95%;" value=""  name="G6_C39_' + campo + '"  placeholder="<?php echo $str_campo_texto___; ?>"></div>' +
                            '<div class="col-md-3"  style="text-align:center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_' + campo + '" id="G6_C40_' + campo + '" atrId = "' + campo + '">' +
                            '<?php
                                        echo '<option value="1">'.$str_tipoCamp1_____.'</option>';
                                        echo '<option value="2">'.$str_tipoCamp2_____.'</option>';
                                        echo '<option value="14">'.$str_tipoCamp14____.'</option>';
                                        echo '<option value="3">'.$str_tipoCamp3_____.'</option>';
                                        echo '<option value="4">'.$str_tipoCamp4_____.'</option>';
                                        echo '<option value="5">'.$str_tipoCamp5_____.'</option>';
                                        echo '<option value="10">'.$str_tipoCamp6_____.'</option>';
                                        echo '<option value="6">'.$str_tipoCamp7_____.'</option>';
                                        echo '<option value="11">'.$str_tipoCamp8_____.'</option>';
                                        echo '<option value="13">'.$str_tipoCamp9_____.'</option>';
                                        echo '<option value="8">'.$str_tipoCamp10_____.'</option>';
                                        echo '<option value="9">'.$str_tipoCamp11_____.'</option>';
                                        echo '<option value="12">'.$str_tipoCamp12_____.'</option>';
                                        echo '<option value="15">Adjunto(Max 9 MB)</option>';
                                        echo '<option value="16">Boton consulta de correo</option>';
                                        echo '<option value="17">Boton</option>';

                                    ?>' +
                            '</select></div>' +
                            '<div class="col-md-3"  style="text-align:center;" id="select2Listas_' + campo + '"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_' + campo + '" id="G6_C44_' + campo + '" cmpo = "' + campo + '">' + ListasBd + '</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "' + campo + '" id="nuevaListaModalH_' + campo + '" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_' + campo + '" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "' + campo + '" class="pull-right">Editar Lista</a></div>' +

                            '<div class="col-md-3"  style="text-align:center;display:none;" id="select2ListasCompuestas_' + campo + '"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C43_' + campo + '" id="G6_C43_' + campo + '" cmpo = "' + campo + '">' + GuionesBD + '</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "' + campo + '" id="copuestas_' + campo + '" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="copuestas_' + campo + '" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "' + campo + '" class="pull-right">Editar Lista</a></div>' +

                            '<div class="col-md-1"  style="text-align:center;display:none"><label><input type="checkbox" value="-1" name="G6_C41_' + campo + '" id="G6_C41_' + campo + '"></label></div>' +
                            '<div class="col-md-2" style="text-align:left;">' +
                            '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="' + campo + '" id="btnAvanzadosField_' + campo + '"><i class="fa fa-cog"></i></button>' +
                            '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField" id="btnEliminarField_' + campo + '" title="<?php echo $str_campo_delet___;?>" valorField="' + item.PREGUN_ConsInte__b + '" cmpo = "' + campo + '" ><i class="fa fa-trash-o"></i></button></div>' +
                            '<input type="hidden" name="GuidetM_' + campo + '" id="GuidetM_' + campo + '"  value="0">' +
                            '<input type="hidden" name="Guidet_' + campo + '" id="Guidet_' + campo + '"  value="0">' +
                            '<input type="hidden" name="GuidetPadre_' + campo + '" id="GuidetPadre_' + campo + '"  value="0">' +
                            '<input type="hidden" name="GuidetHijo_' + campo + '" id="GuidetHijo_' + campo + '"  value="0">' +
                            '<input type="hidden" name="GuidetOper_' + campo + '" id="GuidetOper_' + campo + '"  value="0">' +
                            '<input type="hidden" name="modoGuidet_' + campo + '" id="modoGuidet_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C46_' + campo + '" id="G6_C46_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C207_' + campo + '" id="G6_C207_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C53_' + campo + '" id="G6_C53_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C54_' + campo + '" id="G6_C54_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C55_' + campo + '" id="G6_C55_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C56_' + campo + '" id="G6_C56_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C57_' + campo + '" id="G6_C57_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C58_' + campo + '" id="G6_C58_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C51_' + campo + '" id="G6_C51_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C42_' + campo + '" id="G6_C42_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C52_' + campo + '" id="G6_C52_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C318_' + campo + '" id="G6_C318_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C319_' + campo + '" id="G6_C319_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C320_' + campo + '" id="G6_C320_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C321_' + campo + '" id="G6_C321_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C322_' + campo + '" id="G6_C322_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C323_' + campo + '" id="G6_C323_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C324_' + campo + '" id="G6_C324_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C325_' + campo + '" id="G6_C325_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C326_' + campo + '" id="G6_C326_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C327_' + campo + '" id="G6_C327_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C328_' + campo + '" id="G6_C328_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C329_' + campo + '" id="G6_C329_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C330_' + campo + '" id="G6_C330_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C331_' + campo + '" id="G6_C331_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C332_' + campo + '" id="G6_C332_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C333_' + campo + '" id="G6_C333_' + campo + '"  value="0">' +
                            '<input type="hidden" name="G6_C334_' + campo + '" id="G6_C334_' + campo + '"  value="">' +
                            '<input type="hidden" name="G6_C335_' + campo + '" id="G6_C335_' + campo + '"  value="">' +
                            '<input type="hidden" name="G6_C336_' + campo + '" id="G6_C336_' + campo + '"  value="">' +
                            '<input type="hidden" name="G6_C337_' + campo + '" id="G6_C337_' + campo + '"  value="">' +
                            '<input type="hidden" name="G6_C338_' + campo + '" id="G6_C338_' + campo + '"  value="">' +
                            '<input type="hidden" name="G6_C339_' + campo + '" id="G6_C339_' + campo + '"  value="0">' +
                            '<input type="hidden" name="depende_' + campo + '" id="depende_' + campo + '"  value="0">' +
                            '<input type="hidden" name="ConsInte_' + campo + '" id="ConsInte_' + campo + '"  value="0">' +
                            '<input type="hidden" name="rowListaCompleja_' + campo + '" id="rowListaCompleja_' + campo + '"  value="-1">' +
                            '<div id="configdataEto_' + campo + '"></div>' +
                            '</div>';

                        $("#cuerpoTablaQuesedebeGuardarEdicion").append(cuerpoTablaQuesedebeGuardar);

                        $("#cuerpoTablaQuesedebeGuardarEdicion").sortable({
                            axis: 'y',
                            update: function(event, ui) {
                                var data = $("#cuerpoTablaQuesedebeGuardarEdicion").sortable('serialize');
                                $("#orderCamposCrearSeccionesEdicion").val(data);
                            }
                        });

                        $("#G6_C39_" + campo).val(item.PREGUN_Texto_____b);
                        $("#ConsInte_" + campo).val(item.PREGUN_ConsInte__b);


                        $("#G6_C40_" + campo).val(item.PREGUN_Tipo______b);
                        $("#G6_C40_" + campo).val(item.PREGUN_Tipo______b).change();

                        if (item.PREGUN_IndiBusc__b == '-1') {
                            $("#G6_C41_" + campo).prop('checked', true);
                        } else {
                            $("#G6_C41_" + campo).prop('checked', false);
                        }

                        $("#G6_C44_" + campo).select2({
                            dropdownParent: $("#campos_" + campo)
                        });

                        $("#G6_C43_" + campo).select2({
                            dropdownParent: $("#campos_" + campo)
                        });

                        // console.log('This => '+item.PREGUN_ConsInte__OPCION_B);
                        if (item.PREGUN_ConsInte__OPCION_B != null && item.PREGUN_ConsInte__OPCION_B != '' && item.PREGUN_ConsInte__OPCION_B != '0') {
                            $("#G6_C44_" + campo).val(item.PREGUN_ConsInte__OPCION_B);
                            $("#G6_C44_" + campo).val(item.PREGUN_ConsInte__OPCION_B).change();
                            $("#G6_C44_" + campo).attr('disabled', false);
                            $("#nuevaListaModalH_" + campo).show();
                            $("#editarLista_" + campo).show();
                        }


                        if (item.G6_C43 != null && item.G6_C43 != '' && item.G6_C43 != '0') {
                            $("#G6_C43_" + campo).val(item.G6_C43);
                            $("#G6_C43_" + campo).val(item.G6_C43).change();
                            $("#G6_C43_" + campo).attr('disabled', false);
                            $("#select2ListasCompuestas_" + campo).show();
                            $("#select2Listas_" + campo).hide();
                        }



                        if($("#G6_C40_" + campo).val() == '11'){
                            $.ajax({
                                url: '<?=$url_crud_extender?>',
                                async:false,
                                data: {campo: item.PREGUN_ConsInte__b,validarPreguiPorCampo: true},
                                type: 'post',
                                dataType: 'json',
                                success: function(data) {
                                    if(data.code == '0'){
                                        $("#campos_"+campo).append("<input type='hidden' id='enlazados_" + campo + "'>");
                                    }
                                }
                            });
                        }

                        $("#G6_C46_" + campo).val(item.G6_C46);
                        $("#G6_C207_" + campo).val(item.G6_C207);
                        $("#G6_C53_" + campo).val(item.G6_C53);
                        $("#G6_C54_" + campo).val(item.G6_C54);
                        $("#G6_C55_" + campo).val(item.G6_C55);
                        $("#G6_C56_" + campo).val(item.G6_C56);
                        $("#G6_C57_" + campo).val(item.G6_C57);
                        $("#G6_C58_" + campo).val(item.G6_C58);
                        $("#G6_C51_" + campo).val(item.G6_C51);
                        $("#G6_C52_" + campo).val(item.G6_C52);
                        $("#G6_C42_" + campo).val(item.G6_C42);


                        $("#G6_C318_" + campo).val(item.G6_C318);
                        $("#G6_C318_" + campo).val(item.G6_C318).trigger('change');
                        $("#G6_C319_" + campo).val(item.G6_C319);
                        $("#G6_C320_" + campo).val(item.G6_C320);
                        $("#G6_C321_" + campo).val(item.G6_C321);
                        $("#G6_C322_" + campo).val(item.G6_C322);
                        $("#G6_C323_" + campo).val(item.G6_C323);
                        $("#G6_C324_" + campo).val(item.G6_C324);
                        $("#G6_C325_" + campo).val(item.G6_C325);
                        $("#G6_C326_" + campo).val(item.G6_C326);
                        $("#G6_C327_" + campo).val(item.G6_C327);
                        $("#G6_C328_" + campo).val(item.G6_C328);
                        $("#G6_C329_" + campo).val(item.G6_C329).trigger('change');
                        $("#G6_C330_" + campo).val(item.G6_C330).trigger('change');
                        $("#G6_C331_" + campo).val(item.G6_C331).trigger('change');
                        $("#G6_C332_" + campo).val(item.G6_C332).trigger('change');
                        $("#G6_C333_" + campo).val(item.G6_C333).trigger('change');
                        $("#G6_C334_" + campo).val(item.G6_C334);
                        $("#G6_C335_" + campo).val(item.G6_C335).trigger('change');
                        $("#G6_C336_" + campo).val(item.G6_C336).trigger('change');
                        $("#G6_C337_" + campo).val(item.G6_C337).trigger('change');
                        $("#G6_C338_" + campo).val(item.G6_C338);
                        $("#G6_C339_" + campo).val(item.G6_C339);
                        $("#depende_" + campo).val(item.PREGUN_ConsInte_PREGUN_Depende_b);

                        if (item.PREGUN_Texto_____b == 'CALIFICACION_Q_DY' || item.PREGUN_Texto_____b == 'ESTADO_CALIDAD_Q_DY' || item.PREGUN_Texto_____b == 'COMENTARIO_CALIDAD_Q_DY' || item.PREGUN_Texto_____b == 'COMENTARIO_AGENTE_Q_DY' || item.PREGUN_Texto_____b == 'FECHA_AUDITADO_Q_DY' || item.PREGUN_Texto_____b == 'NOMBRE_AUDITOR_Q_DY') {
                            $("#G6_C39_" + campo).attr('readonly', 'readonly');
                            $("#G6_C44_" + campo).attr('disabled', 'disabled');
                            if (item.PREGUN_Texto_____b == 'CALIFICACION_Q_DY') {
                                $("#G6_C40_" + campo).html('<option value="4">Decimal</option>');
                            }
                            if (item.PREGUN_Texto_____b == 'ESTADO_CALIDAD_Q_DY') {
                                $("#G6_C40_" + campo).html('<option value="6">Lista</option>');
                                $("#G6_C44_" + campo).attr('disabled', false);
                                $("#G6_C44_" + campo).html('<option value="-3">Estados de Calidad</option>');
                                $("#nuevaListaModalH_" + campo).remove();
                                $("#editarLista_" + campo).remove();

                            }
                            if (item.PREGUN_Texto_____b == 'COMENTARIO_CALIDAD_Q_DY' || item.PREGUN_Texto_____b == 'COMENTARIO_AGENTE_Q_DY') {
                                $("#G6_C40_" + campo).html('<option value="2">Memo(+1000 caracteres)</option>');
                            }
                            if (item.PREGUN_Texto_____b == 'FECHA_AUDITADO_Q_DY') {
                                $("#G6_C40_" + campo).html('<option value="5">Fecha</option>');
                            }
                            if (item.PREGUN_Texto_____b == 'NOMBRE_AUDITOR_Q_DY') {
                                $("#G6_C40_" + campo).html('<option value="1">Texto(Max 253 caracteres)</option>');
                            }
                            $("#btnEliminarField_" + campo).remove();
                        }

                        $("#G6_C44_" + campo).change(function() {
                            var id = $(this).attr('cmpo');
                            if ($(this).val() != 0) {
                                $("#editarLista_" + id).show();
                            } else {
                                $("#editarLista_" + id).hide();
                            }
                        });

                        $("#editarLista_" + campo).click(function() {
                            var id = $(this).attr('cmpo');
                            var tipo = $("#G6_C40_" + id).val();
                            $("#TipoListas").val(tipo);
                            $("#agrupador")[0].reset();
                            $("#opciones").html('');
                            contador = 0;
                            $("#hidListaInvocada").val(id);
                            if (tipo == '13') {
                                $.ajax({
                                    url: '<?=$url_crud_extender?>',
                                    type: 'post',
                                    data: {
                                        getListasEdit: true,
                                        idOpcion: $("#G6_C44_" + id).val()
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                        });
                                    },
                                    complete: function() {
                                        $.unblockUI();
                                    },
                                    success: function(data) {
                                        if (data.code == '1') {
                                            $("#idListaE").val(data.id);
                                            $("#txtNombreLista").val(data.opcion);
                                            $.each(data.lista, function(i, items) {
                                                var cuerpo = "<div class='row' id='id_" + i + "'>";
                                                cuerpo += "<div class='col-md-5'>";
                                                cuerpo += "<div class='form-group'>";
                                                cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";
                                                cuerpo += "<div class='col-md-5'>";
                                                cuerpo += "<div class='form-group'>";
                                                cuerpo += "<input type='text' name='Respuesta_" + i + "' id='Respuesta_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Respuesta_b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";
                                                cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                cuerpo += "<div class='form-group'>";
                                                cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='" + i + "' value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";
                                                cuerpo += "</div>";
                                                $("#opciones").append(cuerpo);
                                            });
                                            $("#opciones").append("<input type='hidden' name='contadorEditables' value='" + data.total + "'>");
                                            contador = data.total;
                                            cuantosvan = data.total;

                                            // console.log(contador);
                                            $("#operLista").val("edit");

                                            $(".deleteopcionP").click(function() {
                                                var id = $(this).attr('value');
                                                var miId = $(this).attr('id');
                                                alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                                    if (e) {
                                                        $.ajax({
                                                            url: '<?php echo $url_crud; ?>',
                                                            type: 'post',
                                                            data: {
                                                                deleteOption: true,
                                                                id: id
                                                            },
                                                            beforeSend: function() {
                                                                $.blockUI({
                                                                    baseZ: 2000,
                                                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                                });
                                                            },
                                                            complete: function() {
                                                                $.unblockUI();
                                                            },
                                                            success: function(data) {
                                                                if (data == '1') {
                                                                    alertify.success('<?php echo $str_Exito;?>');
                                                                    $("#id_" + miId).remove();
                                                                } else {
                                                                    alertify.error('Error');
                                                                }
                                                            }
                                                        })

                                                    }
                                                });
                                            });
                                        }
                                    }
                                });
                            } else {

                                $.ajax({
                                    url: '<?=$url_crud_extender?>',
                                    type: 'post',
                                    data: {
                                        getListasEdit: true,
                                        idOpcion: $("#G6_C44_" + id).val()
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                        });
                                    },
                                    complete: function() {

                                    },
                                    success: function(data) {
                                        if (data.code == '1') {
                                            $("#idListaE").val(data.id);
                                            $("#txtNombreLista").val(data.opcion);


                                            $.ajax({
                                                url: '<?=$url_crud_extender?>',
                                                type: 'POST',
                                                data: {
                                                    getCamposLista: 'SI',
                                                    guion: $("#hidId").val(),
                                                    tipoSec: $("#EditarG7_C38").val()
                                                },
                                                success: function(datax) {

                                                    $("#txtPreguntaPadre").html(datax);
                                                    $("#txtPreguntaPadre").val($("#depende_" + id).val());
                                                    if (data.lista == null && data.lista.length == 0) {
                                                        $("#txtPreguntaPadre").val($("#depende_" + id).val()).change();

                                                    }


                                                    if (data.total == 0) {
                                                        $.unblockUI();
                                                    }
                                                    //$("#txtPreguntaPadre").attr('readonly', true);
                                                    $("#preguntaDependiente").show();
                                                }
                                            });


                                            $.each(data.lista, function(i, items) {
                                                if ($("#depende_" + id).val() != 0 && $("#depende_" + id).val() != '') {

                                                    $.ajax({
                                                        url: '<?=$url_crud_extender?>',
                                                        type: 'post',
                                                        Async: true,
                                                        data: {
                                                            getDatosListaByPregun: true,
                                                            preguntaLista: $("#depende_" + id).val(),
                                                            selected: items.LISOPC_ConsInte__LISOPC_Depende_b
                                                        },
                                                        success: function(options) {
                                                            datosJsonguion = options;
                                                            var cuerpo = "<div class='row' id='idX_" + i + "'>";
                                                            cuerpo += "<div class='col-md-5'>";
                                                            cuerpo += "<div class='form-group'>";
                                                            cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control input-sm opcionesGeneradas'  value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "<div class='col-md-5'>";
                                                            cuerpo += "<div class='form-group'>";
                                                            cuerpo += "<select type='text' name='OpcionPadreX_" + i + "' id='OpcionPadreX_" + i + "' class='form-control input-sm opcionesGeneradas' placeholder='<?php echo $str_OpcionDependen_; ?>'>";
                                                            cuerpo += datosJsonguion;
                                                            cuerpo += "</select>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                            cuerpo += "<div class='form-group'>";
                                                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' numero='" + i + "' id='norrarP_" + i + "'  value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "</div>";

                                                            $("#opciones").append(cuerpo);

                                                            $("#norrarP_" + i).click(function() {
                                                                var id = $(this).attr('value');
                                                                var miId = $(this).attr('numero');
                                                                alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                                                    if (e) {
                                                                        $.ajax({
                                                                            url: '<?php echo $url_crud; ?>',
                                                                            type: 'post',
                                                                            data: {
                                                                                deleteOption: true,
                                                                                id: id
                                                                            },
                                                                            beforeSend: function() {
                                                                                $.blockUI({
                                                                                    baseZ: 2000,
                                                                                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                                                });
                                                                            },
                                                                            complete: function() {
                                                                                $.unblockUI();
                                                                            },
                                                                            success: function(data) {
                                                                                if (data == '1') {
                                                                                    alertify.success('<?php echo $str_Exito;?>');
                                                                                    $("#idX_" + miId).remove();
                                                                                } else {
                                                                                    alertify.error('Error');
                                                                                }
                                                                            }
                                                                        })

                                                                    }
                                                                });
                                                            });

                                                            $("#OpcionPadreX_" + i).select2({
                                                                dropdownParent: $("#opciones")
                                                            });
                                                        }
                                                    });

                                                    if (i == (data.total - 1)) {
                                                        $.ajax({
                                                            url: '<?=$url_crud_extender?>',
                                                            type: 'post',
                                                            Async: true,
                                                            data: {
                                                                getDatosListaByPregun: true,
                                                                preguntaLista: $("#depende_" + id).val()
                                                            },
                                                            success: function(options) {
                                                                datosJsonguion = options;
                                                            },
                                                            complete: function() {
                                                                $.unblockUI();
                                                            }
                                                        });
                                                    }
                                                } else {
                                                    var cuerpo = "<div class='row' id='id_" + i + "'>";
                                                    cuerpo += "<div class='col-md-10'>";
                                                    cuerpo += "<div class='form-group'>";
                                                    cuerpo += "<input type='text' name='opcionesEditar_" + i + "' class='form-control opcionesGeneradas' value='" + items.LISOPC_Nombre____b + "' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_" + i + "' value='" + items.LISOPC_ConsInte__b + "'>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                    cuerpo += "<div class='form-group'>";
                                                    cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' numero='" + i + "' id='norrarP_" + i + "' value='" + items.LISOPC_ConsInte__b + "'><i class='fa fa-trash-o'></i></button>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "</div>";
                                                    cuerpo += "</div>";
                                                    $("#opciones").append(cuerpo);

                                                    $("#norrarP_" + i).click(function() {
                                                        var id = $(this).attr('value');
                                                        var miId = $(this).attr('numero');
                                                        alertify.confirm('<?php echo $str_deleteOptio__ ;?>', function(e) {
                                                            if (e) {
                                                                $.ajax({
                                                                    url: '<?php echo $url_crud; ?>',
                                                                    type: 'post',
                                                                    data: {
                                                                        deleteOption: true,
                                                                        id: id
                                                                    },
                                                                    beforeSend: function() {
                                                                        $.blockUI({
                                                                            baseZ: 2000,
                                                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                                        });
                                                                    },
                                                                    complete: function() {
                                                                        $.unblockUI();
                                                                    },
                                                                    success: function(data) {
                                                                        if (data == '1') {
                                                                            alertify.success('<?php echo $str_Exito;?>');
                                                                            $("#id_" + miId).remove();
                                                                        } else {
                                                                            alertify.error('Error');
                                                                        }
                                                                    }
                                                                })

                                                            }
                                                        });
                                                    });


                                                    if (i == (data.total - 1)) {
                                                        $.ajax({
                                                            url: '<?=$url_crud_extender?>',
                                                            type: 'post',
                                                            Async: true,
                                                            data: {
                                                                getDatosListaByPregun: true,
                                                                preguntaLista: $("#depende_" + id).val()
                                                            },
                                                            success: function(options) {
                                                                datosJsonguion = options;
                                                            },
                                                            complete: function() {
                                                                $.unblockUI();
                                                            }
                                                        });
                                                    }
                                                }





                                            });
                                            $("#opciones").append("<input type='hidden' name='contadorEditables' value='" + data.total + "'>");


                                            contador = data.total;
                                            //console.log(contador);
                                            $("#operLista").val("edit");
                                        }
                                    }
                                });
                            }


                        });

                        $("#nuevaListaModalH_" + campo).click(function() {
                            $("#agrupador")[0].reset();
                            $("#opciones").html('');
                            contador = 0;
                            $("#hidListaInvocada").val($(this).attr('cmpo'));
                            $("#operLista").val('add');
                            $("#idListaE").val('0');
                            var tipo = $("#G6_C40_" + $(this).attr('cmpo')).val();
                            if (tipo == '6') {
                                $("#preguntaDependiente").show();
                                $.ajax({
                                    url: '<?=$url_crud_extender?>',
                                    type: 'POST',
                                    data: {
                                        getCamposLista: 'SI',
                                        guion: $("#hidId").val(),
                                        tipoSec: $("#EditarG7_C38").val()
                                    },
                                    success: function(data) {
                                        $("#txtPreguntaPadre").html(data);
                                    },
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                        });
                                    },
                                    complete: function() {
                                        $.unblockUI();
                                    }
                                });
                            } else {
                                $("#preguntaDependiente").hide();
                            }

                            $("#TipoListas").val(tipo);
                        });


                        $("#btnAvanzadosField_" + campo).click(function() {
                            var id = $(this).attr('valorField');
                            tipoCampo = $("#G6_C40_" + id).val();
                            $("#guardarConfiguracion").attr("numero", id);
                            mostrarModalConfiguracion(tipoCampo, id);
                        });

                        if($("#byModulo").val() == '1'){
                            $("#btnEliminarField_"+campo).remove();
                            $("#btnAvanzadosField_"+campo).remove();
                            $("#G6_C40_"+campo).html("<option value='"+$("#G6_C40_"+campo).val()+"'>"+$("#G6_C40_"+campo+" option:selected").html()+"</option>").trigger('change');
                            if(item.PREGUN_Tipo______b == 6){
                                $("#G6_C44_"+campo).html("<option value='-5' dinammicos='0'>ESTADOS DISPONIBILIDADES</option>");
                            }
                            $("#newFieldSeccion2").attr('disabled',true);

                        }else{
                            if(!$("#newFieldSeccion2").attr('disabled')){
                                $("#newFieldSeccion2").attr('disabled',false);
                            }
                        }

                        campo++;
                        $("#contadorEditablesPreguntas").val(campo);


                    });

                    $(".btnEliminarField").click(function() {
                        var id = $(this).attr('valorField');
                        var campo = $(this).attr('cmpo');
                        alertify.confirm('<?php echo $str_deleteField___;?>', function(e) {
                            if (e) {
                                $.ajax({
                                    url: '<?php echo $url_crud; ?>',
                                    type: 'post',
                                    data: {
                                        borraPreguntas: true,
                                        id: id
                                    },
                                    beforeSend: function() {
                                        $.blockUI({
                                            baseZ: 2000,
                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                        });
                                    },
                                    success: function(data) {
                                        if (data == '1') {
                                            calcularContadorCampos('contadorEditablesPreguntas')
                                            alertify.success('<?php echo $str_Exito;?>');
                                            $("#campos_" + campo).remove();
                                        } else {
                                            alertify.error('Error');
                                        }
                                    },
                                    complete: function() {
                                        $.unblockUI();
                                    },
                                })

                            }
                        });
                    });

                    $(".str_SelectTipo").change(function() {
                        var id = $(this).attr('atrId');
                        if ($(this).val() == 6 || $(this).val() == 13) {
                            $("#G6_C44_" + id).attr('disabled', false);
                            $("#nuevaListaModalH_" + id).show();
                            $("#G6_C43_" + id).attr('disabled', true);
                            $("#select2ListasCompuestas_" + id).hide();
                            $("#select2Listas_" + id).show();

                        } else if ($(this).val() == 11 || $(this).val() == 12) {

                            $("#G6_C43_" + id).attr('disabled', false);
                            $("#select2ListasCompuestas_" + id).show();
                            $("#select2Listas_" + id).hide();
                            $("#G6_C44_" + id).attr('disabled', true);
                            $("#nuevaListaModalH_" + id).hide();
                            $("#editarLista_" + id).hide();

                        } else {

                            $("#G6_C44_" + id).attr('disabled', true);
                            $("#nuevaListaModalH_" + id).hide();

                            $("#G6_C43_" + id).attr('disabled', true);
                            $("#select2ListasCompuestas_" + id).hide();
                            $("#select2Listas_" + id).show();

                            if($(this).val() == 14){
                                $("#G6_C339_" + id).val(100);
                            }else{
                                $("#G6_C339_" + id).val(253);
                            }
                        }

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                        $("#idTitle").html('<?php echo $str_message_genera; ?>' + nombreGuion + '<?php echo $str_message_generX; ?>');
                        <?php } ?>

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '1'){ ?>
                        $("#idTitle").html('<?php echo $str_message_generF; ?>' + nombreGuion + '<?php echo $str_message_generX; ?>');
                        <?php } ?>

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '3'){ ?>
                        $("#idTitle").html('<?php echo $str_message_generO; ?>' + nombreGuion + '<?php echo $str_message_generX; ?>');
                        <?php } ?>
                    });

                    $("#EditarG7_C38").val(data.sec_tip_sec).change();
                    $("#EditarG7_C38").html("<option value='"+data.sec_tip_sec+"'>"+$('#EditarG7_C38 option:selected').html()+"</option>");
                    if (tipoCampo != null) {
                        mostrarModalConfiguracion(tipoCampo, idFila, false);
                    }
                    $.unblockUI();
                } else {
                    alertify.error('La seccion no existe');
                }
            },
            complete: function() {
                $.unblockUI();
                $("#editarSeccionesModal").modal()
            }
        });
        return true;
    }

    function guardaSeccion(cerrar, tipoCampo = null, idFila = null) {
        var arrCampos_t = new Array();
        let valido=0;
        $(".rowCampos").each(function(i) {
            var intFila_t = $(this).attr("numero");
            arrCampos_t[i] = intFila_t;
        });

        if ($("#EditarG7_C33").val().length < 1) {
            alertify.error('<?php echo $str_error_nombre__;?>');
            valido = 1;
        }
        if ($("#EditarG7_C35").val().length < 1) {
            alertify.error('<?php echo $str_error_numero__;?>');
            valido = 1;
        }

        if ($("#EditarG7_C36").val() == '0') {
            alertify.error('<?php echo $str_error_Tipo____;?>');
            valido = 1;
        }

        if ($("#EditarG7_C38").val() == -1) {
            alertify.error('<?php echo $str_error_Tipo____;?>');
            valido = 1;
        }

        var vacios = 0;
        $(".textoMostrado").each(function() {
            if ($(this).val().length < 1) {
                vacios++;
            }
        });

        if (vacios > 0) {
            valido = 1;
            alertify.error('<?php echo $str_campo_eror_n__;?>');
            try {
                $.unblockUI();
            } catch (error) {}
        }

        if (cerrar) {
            let validosEnlaces=true;
            $(".rowCampos").each(function(i) {
                const intFila_t = $(this).attr("numero");
                if($("#G6_C40_"+intFila_t).val() == '11' && $("#enlazados_"+intFila_t).length == 0){
                    validosEnlaces=false;
                    $("#btnAvanzadosField_"+intFila_t).css('border','3px solid red');
                    alertify.warning("Por favor configura los enlaces para la lista auxiliar del campo " + $("#G6_C39_"+intFila_t).val());
                }
            });

            if(!validosEnlaces){
                valido = 1;
            }
        }

        if (valido == 0) {
            var form = $("#editarseccionesModalForm");
            var formData = new FormData($("#editarseccionesModalForm")[0]);
            formData.append('padre', $("#hidId").val());
            formData.append('contador', $("#contadorEditablesPreguntas").val());
            formData.append('arrCampos_t', arrCampos_t);
            formData.append('editarDatosSecciones', 'si');
            $.ajax({
                url: '<?=$url_crud?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                success: function(data) {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                    if (data != '0') {
                        getListasSistemas();
                        getSeccionesGuion($("#byModulo").val());

                        var primario = $("#G5_C31").val();
                        var segundar = $("#G5_C59").val();
                        var llave = $("#G5_C319").val();

                        $.ajax({
                            type: 'post',
                            url: '<?=$url_crud_extender?>?camposGuion=true',
                            data: {
                                guion: $("#hidId").val(),
                                esMaster: 'si',
                                seccion: $("#IdseccionEdicion").val()
                            },
                            complete: function() {
                                $.unblockUI();
                            },
                            success: function(data) {
                                $("#G5_C31").html(data);
                                $("#G5_C59").html(data);
                                $("#G5_C319").html(data);

                                $("#G5_C31").val(primario);
                                $("#G5_C31").val(primario).change();

                                $("#G5_C59").val(segundar);
                                $("#G5_C59").val(segundar).change();

                                $("#G5_C319").val(llave);
                                $("#G5_C319").val(llave).change();

                                $("#G5_C31").attr('disabled', false);
                                $("#G5_C59").attr('disabled', false);
                                $("#G5_C319").attr('disabled', false);
                            }
                        });

                        $("#orderCamposCrearSeccionesEdicion").val('');
                        if (cerrar) {
                            $("#editarSeccionesModal").modal('hide');
                            $.unblockUI();
                            alertify.success("<?php echo $str_Exito ;?>");
                        } else {
                            editaSeccion($("#IdseccionEdicion").val(), tipoCampo, idFila);
                        }


                    } else {
                        alertify.error("<?php echo $error_de_proceso ;?>");
                    }
                },
                complete: function() {
                    $.unblockUI();
                },
                //si ha ocurrido un error
                error: function() {
                    after_save_error();
                    alertify.error("<?php echo $error_de_red ;?>");
                }
            });
        }
    }

    const calcularContadorCampos = (strCampo) => {
        let contador = document.getElementById(`${strCampo}`)
        contador.value = contador.value-1
        console.log('contador.value :>> ', contador.value);
    }

    const validarCrearSeccionesModal = () => {
        return $('#crearSeccionesModal').is(':visible'); 
    }
 
    function mostrarModalConfiguracion(tipoCampo, idFila, modal = true) {
        let valido = true;
        let valueModal = validarCrearSeccionesModal();
        if (modal && tipoCampo != 12 ) {
            valido = valueModal === true ?  false : guardaSeccion(false, tipoCampo, idFila);
            console.log('valido',valido);      
        } else {
            valido = false;
        }
        console.log("valido[mostrarModalConfiguracion]",valido);
        if (valido === false) {
            var strOptValorDefect_t = '';
            $("#contentAdvanced").css("width","700px");
            $("#HoraDiv").hide();
            $("#FechaDiv").hide();
            $("#NumericoDiv").hide();
            $("#minNumero").hide();
            $("#maxNumero").hide();
            $(".formato").hide();
            $("#numero_defecto").hide();
            $("#camposGuionNuevos").hide();
            $("#addNewGuionConf").hide();
            $("#MasterDetailsDiv").hide();
            $("#campossubform").hide();
            $("#modaltotalizador").hide();
            $("#modalcomunicacion").hide();
            $("#modalListaValorDefecto").hide();
            $("#ListaVariablesNumericas").html('');
            $(".formulaMatematica").hide();
            $("#tiempoCantidad").hide();
            $("#texto_defecto").hide();
            $("#lbl_G6_C326").hide();
            $("#lbl_G6_C327").hide();
            $("#G6_C327").attr('campo','0');
            $("#lbl_G6_C328").hide();
            $("#lbl_G6_C329").hide();
            $("#lbl_G6_C330").hide();
            $("#lbl_G6_C331").hide();
            $("#lbl_G6_C332").hide();
            $("#lbl_G6_C333").hide();
            $("#lbl_G6_C334").hide();
            $("#div_G6_C335").hide();
            $("#div_G6_C336").hide();
            $("#divG6_C339").hide();
            $(".filasComunicacion").remove();
            $("#conteocomunicacion").val(0);
            $("#iframeWS").attr('src','');
            $("#iframeWS").css('height','0px');

            $("#G6_C51").parent().show();
            $("#G6_C320").parent().show();
            $("#G6_C322").parent().show();
            $("#llave").val($("#ConsInte_"+idFila).val());

            if ($("#G6_C53_" + idFila).val() != '0') {
                $("#G6_C53").val($("#G6_C53_" + idFila).val());
            } else {
                $("#G6_C53").val('');
            }

            if ($("#G6_C54_" + idFila).val() != '0') {
                $("#G6_C54").val($("#G6_C54_" + idFila).val());
            } else {
                $("#G6_C54").val('');
            }

            if ($("#G6_C57_" + idFila).val() != '0') {
                $("#G6_C57").val($("#G6_C57_" + idFila).val());
            } else {
                $("#G6_C57").val('');
            }

            if ($("#G6_C58_" + idFila).val() != '0') {
                $("#G6_C58").val($("#G6_C58_" + idFila).val());
            } else {
                $("#G6_C58").val('');
            }

            if ($("#G6_C55_" + idFila).val() != '0' && $("#G6_C55_" + idFila).val() != '') {
                if($("#G6_C55_" + idFila).val() == '0001-01-01'){
                    $("#G6_C55").val('');
                    $("#G6_C55").attr('disabled',true);
                    $("#fecha_minima").html('Hoy <span class="caret"></span>');
                    
                }else{
                    $("#G6_C55").val($("#G6_C55_" + idFila).val());
                    $("#G6_C55").attr('disabled',false);
                    $("#fecha_minima").html('Elegir Fecha <span class="caret"></span>')
                }
            } else {
                $("#G6_C55").val('');
                $("#fecha_minima").html('Seleccione <span class="caret"></span>');
            }

            if ($("#G6_C56_" + idFila).val() != '0') {
                $("#G6_C56").val($("#G6_C56_" + idFila).val());
            } else {
                $("#G6_C56").val('');
            }

            if ($("#G6_C42_" + idFila).val() == '-1') {
                $("#G6_C42").prop('checked', true);
            } else {
                $("#G6_C42").prop('checked', false);
            }

            if ($("#G6_C51_" + idFila).val() == '-1') {
                $("#G6_C51").prop('checked', true);
            } else {
                $("#G6_C51").prop('checked', false);
            }

            if ($("#G6_C52_" + idFila).val() == '-1') {
                $("#G6_C52").prop('checked', true);
            } else {
                $("#G6_C52").prop('checked', false);
            }

            if ($("#G6_C320_" + idFila).val() == '2' || $("#G6_C320_" + idFila).val() == '3') {
                $("#G6_C320").prop('checked', true);
            } else {
                $("#G6_C320").prop('checked', false);
            }

            // alert($("#G6_C322_"+idFila).val());
            if ($("#G6_C322_" + idFila).val() != '0' && $("#G6_C322_" + idFila).val() != '') {
                $("#G6_C322").prop('checked', true);
            } else {
                if ($("#G6_C322").is(':checked')) {
                    $("#G6_C322").prop('checked', false);
                }

            }

            if ($("#G6_C326_" + idFila).val() == '-1') {
                $("#G6_C326").prop('checked', true);
            } else {
                $("#G6_C326").prop('checked', false);
            }

            if ($("#G6_C327_" + idFila).val() == '-1') {
                $("#G6_C327").prop('checked', true);
                llenarSelectMail_sms('mail',$("#G6_C329_" + idFila).val());
                $("#lbl_G6_C329").show();
            } else {
                $("#G6_C327").prop('checked', false);
            }

            if ($("#G6_C328_" + idFila).val() == '-1') {
                $("#G6_C328").prop('checked', true);
                llenarSelectMail_sms('sms',$("#G6_C330_" + idFila).val());
                llenarText_sms($("#G6_C331_" + idFila).val());
                $("#lbl_G6_C330").show();
                $("#lbl_G6_C331").show();
                $("#lbl_G6_C334").show();
                $("#G6_C334").val($("#G6_C334_" + idFila).val());
            } else {
                $("#G6_C328").prop('checked', false);
            }

            $("#G6_C339").val($("#G6_C339_"+ idFila).val());

            if (tipoCampo == '1') {

                strOptValorDefect_t += '<option value="0"><?php echo $str_seleccione;?></option>';
                strOptValorDefect_t += '<option value="1000"><?php echo $str_tipo_valor1____;?></option>';
                strOptValorDefect_t += '<option value="1002">Nombre del usuario</option>';
                strOptValorDefect_t += '<option value="1003">Mail del usuario</option>';
                strOptValorDefect_t += '<option value="1004">Numero telefonico de la llamada entrante</option>';
                strOptValorDefect_t += '<option value="1005">Identificación del usuario</option>';
                $("#G6_C318").html(strOptValorDefect_t);
                $("#NumericoDiv").show();
                $("#lbl_G6_C326").show();
                $("#lbl_G6_C327").show();
                $("#lbl_G6_C328").show();
                $("#divG6_C339").show();
            }

            if (tipoCampo == '3' || tipoCampo == '4') {

                strOptValorDefect_t += '<option value="0"><?php echo $str_seleccione;?></option>';
                strOptValorDefect_t += '<option value="3001"><?php echo $str_tipo_valor1____;?></option>';
                strOptValorDefect_t += '<option value="3002"><?php echo $str_tipo_valor2____;?></option>';
                strOptValorDefect_t += '<option value="3003"><?php echo $str_tipo_valor3____;?></option>';

                $("#G6_C318").html(strOptValorDefect_t);
                $("#NumericoDiv").show();
                $("#minNumero").show();

                htmlOp="<option value='1'>Número</option>";
                htmlOp+="<option value='2'>Moneda</option>";
                htmlOp+="<option value='3'>Porcentaje</option>";
                $("#G6_C337").html(htmlOp);
                $(".formato").show();
                $("#maxNumero").show();

                if (tipoCampo == '3') {
                    $("#lbl_G6_C326").show();
                    $("#lbl_G6_C328").show();
                }

            }

            if (tipoCampo == '5') {

                strOptValorDefect_t += '<option value="0"><?php echo $str_seleccione;?></option>';
                strOptValorDefect_t += '<option value="5001">Fecha Actual</option>';
                strOptValorDefect_t += '<option value="5002">Fecha Actual Mas</option>';
                strOptValorDefect_t += '<option value="5003">Fecha Actual Menos</option>';

                $("#G6_C318").html(strOptValorDefect_t);
                $("#NumericoDiv").show();
                $("#FechaDiv").show();
            }

            if (tipoCampo == '6') {
                var valorLista = $('#G6_C44_' + idFila).val();
                $('#valLisDefecto').html('')
                if (valorLista != '0') {
                    $.ajax({
                        url: '<?=$url_crud_extender?>?opcionLista=true',
                        data: {
                            lista: valorLista
                        },
                        type: 'post',
                        beforeSend: function() {
                            $.blockUI({
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                            });
                        },
                        complete: function() {
                            $.unblockUI();
                        },
                        success: function(data) {
                            var option = '<option value="0">Seleccione</option>';
                            option += data;
                            $('#G6_C318').html(option);
                            if ($("#G6_C318_" + idFila).val() != 0) {
                                $("#G6_C318").val($("#G6_C318_" + idFila).val());
                                $("#G6_C318").val($("#G6_C318_" + idFila).val()).change();
                            } else {
                                $("#G6_C318").val(0).change();
                            }
                        }
                    });
                }
                $("#NumericoDiv").show();
                htmlOp="<option value='4'>Lista desplegable</option>";
                htmlOp+="<option value='5'>Botones de opción</option>";
                $("#G6_C337").html(htmlOp);
                $(".formatoLista").show();
            }

            if ($("#G6_C318_" + idFila).val() != 0) {
                $("#G6_C318").val($("#G6_C318_" + idFila).val());
                $("#G6_C318").val($("#G6_C318_" + idFila).val()).change();
            } else {
                $("#G6_C318").val(0).change();
            }

            if ($("#G6_C324_" + idFila).val() != "") {
                $("#G6_C324").val($("#G6_C324_" + idFila).val());
            } else {
                $("#G6_C324").val("");
            }

            if ($("#G6_C325_" + idFila).val() != 0) {
                $("#G6_C325").val($("#G6_C325_" + idFila).val());
                $("#G6_C325").val($("#G6_C325_" + idFila).val()).change();
            } else {
                $("#G6_C325").val(0).change();
            }

            if ($("#G6_C319_" + idFila).val() != 0) {
                $("#G6_C319").val($("#G6_C319_" + idFila).val());
            } else {
                $("#G6_C319").val('');
            }

            if ($("#G6_C321_" + idFila).val() != 0) {
                $("#G6_C321").val($("#G6_C321_" + idFila).val());
            } else {
                $("#G6_C321").val('');
            }

            $("#G6_C337").val($("#G6_C337_" + idFila).val()).trigger('change');
            $("#G6_C338").val($("#G6_C338_" + idFila).val());

            if ($("#G6_C323_" + idFila).val() != "") {
                $("#G6_C323").val($("#G6_C323_" + idFila).val());
            } else {
                $("#G6_C323").val('');
            }

            if (tipoCampo == '14') {
                $("#lbl_G6_C327").show();
                $("#divG6_C339").show();
            }

            if (tipoCampo == '16'){
                $("#G6_C51").parent().hide();
                $("#G6_C320").parent().hide();
                $("#G6_C322").parent().hide();
                $("#G6_C327").attr('campo','16');
                $("#lbl_G6_C327").show();
                if($("#G6_C327_" + idFila).val()==-1){
                    getBasesHuesped($("#G6_C333_" + idFila).val());
                    getCamposBd($("#G6_C333_" + idFila).val(),$("#G6_C332_" + idFila).val());
                    $("#lbl_G6_C332").show();
                    $("#lbl_G6_C333").show();

                }
            }

            if (tipoCampo == '17'){
                $("#G6_C51").parent().hide();
                $("#G6_C320").parent().hide();
                $("#G6_C322").parent().hide();
                $("#div_G6_C335").show();
                $("#div_G6_C336").show();
                $("#G6_C335").val($("#G6_C335_" + idFila).val()).trigger('change');
                $("#G6_C336").val($("#G6_C336_" + idFila).val()).trigger('change');
                $("#contentAdvanced").css("width","90%");
                $("#iframeWS").css('height','450px');
            }


            if (tipoCampo == '10') {
                $("#HoraDiv").show();
                $("#horaDefecto").val($("#G6_C318_" + idFila).val()).change();
                 valueModal === true ? null : $.ajax({
                    url: '<?=$url_crud_extender?>?traehora=true',
                    data: {
                        campo: $("#ConsInte_" + idFila).val()
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        // console.log('data[traehora] :>> ', data);
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
            } else if (tipoCampo == '11') {
                $("#CuerpoTablaNuevosFields").html('');
                $.ajax({
                    url: '<?=$url_crud_extender?>?camposGuion=true',
                    data: {
                        guion: $("#G6_C43_" + idFila).val(),
                        esMaster: 'si',
                        seccion: $("#IdseccionEdicion").val()
                    },
                    type: 'post',
                    complete: function() {
                        $.unblockUI();
                    },
                    success: function(data) {
                        ListaCampoGuionAux = '<option value="0">Seleccione</option>';
                        ListaCampoGuionAux += data;

                        $.ajax({
                            url: '<?=$url_crud_extender?>?camposGuion=true',
                            data: {
                                guion: $("#hidId").val(),
                                esMaster: 'si',
                                seccion: $("#IdseccionEdicion").val()
                            },
                            type: 'post',
                            success: function(datas) {
                                ListaCampoGuionMio = '<option value="0">Seleccione</option>';
                                ListaCampoGuionMio += datas;

                                /* Ahor atoca buscar si esa campo tiene asignados pregui */
                                if ($("#ConsInte_" + idFila)) {
                                    /*Si esta pregunta esta editandose */
                                    $.ajax({
                                        url: '<?=$url_crud_extender?>',
                                        data: {
                                            campo: $("#ConsInte_" + idFila).val(),
                                            validarPreguiPorCampo: true
                                        },
                                        type: 'post',
                                        dataType: 'json',
                                        complete: function() {
                                            $.unblockUI();
                                        },
                                        success: function(data) {
                                            if (data.code == '0') {
                                                /* Si tiene pregui toca meterlo */
                                                $("#CuerpoTablaNuevosFields").html('');
                                                $.each(data.datosDeCampos, function(i, items) {

                                                    LongitudNuevosCampos = i;

                                                    campo = "<tr id='idtr_" + idFila + i + "' numero=\"" + i + "\" class=\"rowListaCompleja_" + idFila + "\">";
                                                    campo += "<td style='width:45%;'>";
                                                    campo += "<select class='form-control' id='camposCOnfiguradosGuionAux_" + idFila + i + "' fila='" + i + "'>";
                                                    campo += ListaCampoGuionAux;
                                                    campo += "</select>";
                                                    campo += "</td>";
                                                    campo += "<td>";
                                                    campo += "<select class='form-control' id='camposCOnfiguradosGuionTo_" + idFila + i + "' fila='" + i + "'>";
                                                    campo += ListaCampoGuionMio;
                                                    campo += "</select>";
                                                    campo += "</td>";
                                                    campo += "<td>";
                                                    campo += "<button class='btn btn-sm btn-danger' id='delEstaRow_" + idFila + i + "' type='button' idPregui='" + items.PREGUI_ConsInte__b + "' fila='" + i + "'><i class='fa fa-trash-o'></i></button>";
                                                    campo += "</td>";
                                                    campo += "<input type='hidden' value='0'  name='camposCOnfiguradosGuionAux_" + idFila + i + "' id='inputocultoAux_" + idFila + i + "' >";
                                                    campo += "<input type='hidden' value='0'  name='camposCOnfiguradosGuionTo_" + idFila + i + "' id='inputocultoMio_" + idFila + i + "' >";
                                                    campo += "<tr>";
                                                    $("#CuerpoTablaNuevosFields").append(campo);

                                                    $("#camposCOnfiguradosGuionAux_" + idFila + i).val(items.campoGuion);
                                                    $("#camposCOnfiguradosGuionAux_" + idFila + i).val(items.campoGuion).val();
                                                    $("#inputocultoAux_" + idFila + i).val(items.campoGuion);


                                                    $("#camposCOnfiguradosGuionTo_" + idFila + i).val(items.campoGuionX);
                                                    $("#camposCOnfiguradosGuionTo_" + idFila + i).val(items.campoGuionX).val();
                                                    $("#inputocultoMio_" + idFila + i).val(items.campoGuionX);

                                                    $("#delEstaRow_" + idFila + i).click(function() {
                                                        var id = $(this).attr('fila');
                                                        var pregui = $(this).attr('idPregui');
                                                        alertify.confirm('<?php echo $str_deleteField___;?>', function(e) {
                                                            if (e) {
                                                                $.ajax({
                                                                    url: '<?=$url_crud_extender?>',
                                                                    type: 'post',
                                                                    data: {
                                                                        eliminarPreguiPorCampo: true,
                                                                        idPregui: pregui
                                                                    },
                                                                    beforeSend: function() {
                                                                        $.blockUI({
                                                                            baseZ: 2000,
                                                                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                                                                        });
                                                                    },
                                                                    complete: function() {
                                                                        $.unblockUI();
                                                                    },
                                                                    success: function(data) {
                                                                        if (data == '1') {
                                                                            alertify.success('<?php echo $str_Exito; ?>');
                                                                            $("#idtr_" + idFila + id).remove();
                                                                        } else {
                                                                            alertify.error('<?php echo $error_de_proceso; ?>');
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        });

                                                    });

                                                });

                                            } else {

                                            }
                                        }
                                    });
                                }
                            },
                            complete: function() {
                                $.unblockUI();
                            },
                        });
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    }
                });



                $("#camposGuionNuevos").show();
                $("#addNewGuionConf").show();

            } else if (tipoCampo == '12') {

                //  *NBG*-05-2020*ACTUALIZAR LOS CAMPOS A MOSTRAR DE LOS SUBFORMULARIOS
                $.ajax({
                    url: '<?=$url_crud_extender?>?camposGuion_sub=true',
                    data: {
                        guion: $("#G6_C43_" + idFila).val(),
                        seccion: $("#IdseccionEdicion").val()
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function(data) {

                        var strHTMLOptions_t = '<option value="0">Seleccione</option>';
                        var strHTMLOptionsHijo_t = '<option value="0">Seleccione</option>';
                        var strHTMLCampos_t = '';
                        var intValidar_t = 0;

                        $.each(data, function(index, value) {

                            strHTMLCampos_t += '<div class="col-md-4"><div class="form-group"><label class="custom-radio-checkbox"><input class="custom-radio-checkbox__input" type="checkbox" name="genero" seccion="' + value["TipoSeccion"] + '" value="' + value["id"] + '"';

                            if (value['mostrarSubForm'] == 1) {
                                strHTMLCampos_t += 'checked="checked"';
                                intValidar_t = 1;
                            }

                            strHTMLCampos_t += '><span class="custom-radio-checkbox__show custom-radio-checkbox__show--checkbox"></span><span class="custom-radio-checkbox__text">' + value["nombre"] + '</span></label></div></div>';

                            if (value["TipoSeccion"] == 1) {
                                strHTMLOptions_t += '<option value="' + value["id"] + '">' + value["nombre"] + '</option>'
                            }
                            //if ((value["TipoSeccion"] == 1 || value["TipoSeccion"] == 2) && (value["tipoCampo"] == 3 || value["tipoCampo"] == 4)) {
                                strHTMLOptionsHijo_t += '<option value="' + value["id"] + '">' + value["nombre"] + '</option>'
                            //}
                        });
                        $("#Guidet").html(strHTMLOptions_t);
                        $("#GuidetHijo").html(strHTMLOptionsHijo_t);
                        $('.AquiCamposSub').html(strHTMLCampos_t);

                        if (intValidar_t == 0) {
                            $('input[name=genero]').each(function() {
                                if ($(this).attr('seccion') == 1) {
                                    $(this).attr('checked', true);
                                }
                            });
                        }

                        $.ajax({
                            url: '<?=$url_crud_extender?>?camposGuion=true',
                            data: {
                                guion: $("#hidId").val(),
                                seccion: $("#IdseccionEdicion").val(),
                                numero: 'si',
                                esMaster: true,
                                idioma: '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                            },
                            type: 'post',
                            success: function(datas) {
                                var strHTMLOptionsM_t = '<option value="_ConsInte__b"><?php echo $str_Md_value_def__?></option>';
                                strHTMLOptionsM_t += '<option value="-1">Id de la base de datos</option>';
                                var strHTMLOptionsPadre_t = '<option value="0">Seleccione</option>';
                                try {
                                    datas = JSON.parse(datas);
                                    $.each(datas, function(index, value) {
                                            strHTMLOptionsPadre_t += '<option value="' + value["id"] + '">' + value["nombre"] + '</option>';
                                            strHTMLOptionsM_t += '<option value="' + value["id"] + '">' + value["nombre"] + '</option>';
                                        /*if (value["tipoCampo"] == 3 || value["tipoCampo"] == 4) {
                                            strHTMLOptionsPadre_t += '<option value="' + value["id"] + '">' + value["nombre"] + '</option>';
                                            if (value["TipoSeccion"] == 1) {
                                                strHTMLOptionsM_t += '<option value="' + value["id"] + '">' + value["nombre"] + '</option>';
                                            }
                                        } else {
                                            if (value["TipoSeccion"] == 1) {
                                                strHTMLOptionsM_t += '<option value="' + value["id"] + '">' + value["nombre"] + '</option>';
                                            }
                                        } */
                                    });
                                    $("#GuidetM").html(strHTMLOptionsM_t);
                                    $("#GuidetPadre").html(strHTMLOptionsPadre_t);
                                } catch {
                                    $("#GuidetPadre").html(strHTMLOptionsPadre_t);
                                    $("#GuidetM").html(strHTMLOptionsM_t);
                                }

                                $.ajax({
                                    url: '<?=$url_crud_extender?>?getMasterDetail=true',
                                    data: {
                                        guionMa: $("#hidId").val(),
                                        guionDet: $("#G6_C43_" + idFila).val(),
                                        id: $("#ConsInte_" + idFila).val()
                                    },
                                    type: 'post',
                                    dataType: 'json',
                                    success: function(datax) {
                                        if (datax.code == '1') {
                                            $.each(datax.datos, function(i, item) {
                                                if (item.GUIDET_ConsInte__PREGUN_Ma1_b != null) {

                                                    $("#GuidetM").val(item.GUIDET_ConsInte__PREGUN_Ma1_b);
                                                    $("#GuidetM").val(item.GUIDET_ConsInte__PREGUN_Ma1_b).change();

                                                } else {

                                                    $("#GuidetM").val('_ConsInte__b');
                                                    $("#GuidetM").val('_ConsInte__b').change();


                                                }
                                                $("#Guidet").val(item.GUIDET_ConsInte__PREGUN_De1_b);
                                                $("#Guidet").val(item.GUIDET_ConsInte__PREGUN_De1_b).change();

                                                if (item.GUIDET_Modo______b == 1) {
                                                    $("#modoGuidet").iCheck('check');
                                                } else {
                                                    $("#modoGuidet").iCheck('uncheck');
                                                }
                                            });
                                        } else {
                                            $("#modoGuidet").iCheck('check');
                                        }
                                        $.ajax({
                                            url: '<?=$url_crud_extender?>?getCamposTotalizador=true',
                                            data: {
                                                guionMa: $("#hidId").val(),
                                                guionDet: $("#G6_C43_" + idFila).val()
                                            },
                                            type: 'post',
                                            dataType: 'json',
                                            success: function(total) {
                                                if (total.code == '1') {
                                                    $.each(total.datos, function(i, item) {
                                                        if (item.papa != null) {
                                                            $("#GuidetPadre").val(item.papa).trigger('change');
                                                            $("#GuidetHijo").val(item.hijo).trigger('change');
                                                            $("#GuidetOper").val(item.oper).trigger('change');
                                                        }
                                                    });
                                                }
                                            },
                                            complete: function() {
                                                $.unblockUI();
                                            }
                                        });
                                    }
                                });

                            }
                        });
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                });

                var padre = $("#hidId").val();
                var hijo = $("#G6_C43_" + idFila).val();
                $.ajax({
                    url: '<?=$url_crud_extender?>?GetComunicacion=true',
                    type: 'post',
                    data: {
                        padre: padre,
                        hijo: hijo
                    },
                    dataType: 'json',
                    cache: false,
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    success: function(data) {
                        if (data.mensaje == 'ok') {
                            $.each(data.campos, function(valor, fila) {
                                TraerCamposSubformulario(padre, hijo, fila);
                            });
                        } else {
                            if (data.mensaje != 'sin filas') {
                                alertify.error(data.mensaje);
                            }
                        }
                    },
                    //si ha ocurrido un error
                    error: function() {
                        after_save_error();
                        alertify.error("<?php echo $error_de_red ;?>");
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });


                $("#MasterDetailsDiv").show();
                $("#campossubform").show();
                $("#modaltotalizador").show();
                $("#modalcomunicacion").show();

                if ($("#modoGuidet_" + idFila).is(":checked")) {
                    $("#modoGuidet").prop('checked', true);
                } else {
                    $("#modoGuidet").prop('checked', false);
                }

            }

            $("#tipoCampo").val(tipoCampo);
            $("#idFila").val(idFila);
            $("#ConfiguracionesAdvancedModal").modal('show');
        }
    }

    //    *NBG*11-05-201*FUNCION PARA BUSCAR EN PREGUN LOS CAMPOS DEL FORMULARIO PADRE Y SUBFORMULARIO PARA LA SECCIÓN DE COMUNICACION ENTRE FORMULARIOS
    //    PADRE=FORMULARIO PRINCIPAL
    //    HIJO=SUBFORMULARIO
    function TraerCamposSubformulario(padre, hijo, opciones = null) {
        $.ajax({
            url: '<?=$url_crud_extender?>?TraerCamposSubformulario=si',
            type: 'POST',
            data: {
                padre: padre,
                hijo: hijo
            },
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                if (data.estado == 'ok') {
                    pintarCamposComunicacion(data, padre, hijo, opciones);
                } else {
                    alertify.error(data.mensaje);
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error("<?php echo $error_de_red ;?>");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    function pintarCamposComunicacion(data, padre, hijo, opciones = null) {
        var filaID = parseInt($("#conteocomunicacion").val());
        //inicia el html de la nueva fila
        var htmlCampos = '<div class="row filasComunicacion" id="fila_' + filaID + '">';

        //select del formulario padre
        htmlCampos += '<div class="col-md-5">';
        htmlCampos += '<div class="form-group">';
        htmlCampos += '<input type="hidden" class="selector" value="' + padre + '" name="padre_' + filaID + '">';
        htmlCampos += '<select class="form-control input-sm selector" name="select_padre_' + filaID + '" id="select_padre_' + filaID + '">';
        htmlCampos += '<option value="0">CAMPOS DEL FORMULARIO</option>';
        $.each(data.padre, function(campo, item) {
            htmlCampos += '<option value="' + item.id + '" tipo="' + item.tipo + '">' + item.texto + '</option>';
        });
        htmlCampos += '</select>';
        htmlCampos += '</div>';
        htmlCampos += '</div>';

        //select del subformulario hijo
        htmlCampos += '<div class="col-md-5">';
        htmlCampos += '<div class="form-group">';
        htmlCampos += '<input type="hidden" class="selector" value="' + hijo + '" name="hijo_' + filaID + '">';
        htmlCampos += '<select class="form-control input-sm selector" name="select_hijo_' + filaID + '" id="select_hijo_' + filaID + '">';
        htmlCampos += '<option value="0">CAMPOS DEL SUBFORMULARIO</option>';
        $.each(data.hijo, function(campo, item) {
            htmlCampos += '<option value="' + item.id + '" tipo="' + item.tipo + '">' + item.texto + '</option>';
        });
        htmlCampos += '</select>';
        htmlCampos += '</div>';
        htmlCampos += '</div>';


        //el boton de eliminar la fila
        htmlCampos += '<div class="col-md-2">';
        htmlCampos += '<div class="form-group">';
        htmlCampos += '<button type="button" class="btn btn-danger btn-sm btnEliminarComunicacion" idFila="' + filaID + '"><i class="fa fa-trash-o"></i></button>';
        htmlCampos += '</div>';
        htmlCampos += '</div>';

        //finaliza el html de la nueva fila
        htmlCampos += '</div>';

        $(".comunicaciones").append(htmlCampos);

        if (opciones != null) {
            $('#select_padre_' + filaID).val(opciones.campoPadre).trigger("change");
            $('#select_hijo_' + filaID).val(opciones.campoHijo).trigger("change");
            $('#fila_' + filaID).append('<input type="hidden" class="selector" idFila="' + filaID + '" value="' + opciones.idComunicacion + '" name="comunicacion_' + filaID + '">');
            $('#fila_' + filaID).attr('id_comunicacion', opciones.idComunicacion);
        } else {}
        filaID++;
        $("#conteocomunicacion").val(filaID);


        //Funcion del boton de eliminar fila
        $(".btnEliminarComunicacion").click(function() {
            var id = $(this).attr('idFila');
            var comunicacion = $("#fila_" + id).attr('id_comunicacion');
            if (comunicacion) {
                eliminarComunicacion(comunicacion, id);
            } else {
                $("#fila_" + id).remove();
            }

        });
    }

    function eliminarComunicacion(comunicacion, id) {
        $.ajax({
            url: '<?=$url_crud_extender?>?EliminarComunicacion=si',
            type: 'POST',
            data: {
                id: comunicacion,
            },
            cache: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                if (data == '1') {
                    $("#fila_" + id).remove();
                } else {
                    alertify.error("No se pudo eliminar la relación");
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error("<?php echo $error_de_red ;?>");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    function llenarSelectMail_sms(accion,valorOption=null) {
        let texto='';
        if(accion == 'mail'){
            texto='la cuenta';
        }else{
            texto='el proveedor';
        }
        $.ajax({
            url: '<?=$url_crud_extender?>?TraerOptionMailSms=si',
            type: 'POST',
            data: {
                operacion: accion,
            },
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                if(data.estado=='ok'){
                    var options='<option value="0">Seleccione '+texto+' a usar</option>';
                    $.each(data.mensaje, function(campo, item) {
                        options += '<option value="' + item.id + '">' + item.cuenta + '</option>';
                    });
                    if(accion == 'mail'){
                        $("#G6_C329").html(options);
                        $("#lbl_G6_C329").show();
                        if(valorOption !=null){
                            $("#G6_C329").val(valorOption).trigger('change');
                        }
                    }else{
                        $("#G6_C330").html(options);
                        $("#lbl_G6_C330").show();
                        if(valorOption !=null){
                            $("#G6_C330").val(valorOption).trigger('change');
                        }
                    }
                }else{
                    alertify.error(data.mensaje);
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error("<?php echo $error_de_red ;?>");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    function llenarText_sms(valorOption=null) {
        $.ajax({
            url: '<?=$url_crud_extender?>?getCamposParaSms=si',
            type: 'POST',
            data: {
                guion: $("#hidId").val(),
            },
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                if(data.estado=='ok'){
                    var options='<option value="0">Seleccione</option>';
                    $.each(data.mensaje, function(campo, item) {
                        options += '<option value="' + item.id + '">' + item.texto + '</option>';
                    });

                    $("#G6_C331").html(options);
                    $("#lbl_G6_C331").show();
                    $("#lbl_G6_C334").show();
                    if(valorOption !=null){
                        $("#G6_C331").val(valorOption).trigger('change');
                    }
                }else{
                    alertify.error(data.mensaje);
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error("<?php echo $error_de_red ;?>");
            },
            complete: function() {
                $.unblockUI();
            }
        });

        if(valorOption !=null){
        }
    }

    function getBasesHuesped(option=null){
        $.ajax({
            url: '<?=$url_crud_extender?>?TraerBasesHuesped=si',
            type: 'POST',
            cache: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                $("#G6_C333").html(data);
                if(option != null){
                    $("#G6_C333").val(option).trigger('change');
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error("<?php echo $error_de_red ;?>");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    function getCamposBd(id,option=null){
        $.ajax({
            url: '<?=$url_crud_extender?>?getCamposBd=si',
            type: 'POST',
            data:{id : id},
            cache: false,
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            success: function(data) {
                $("#G6_C332").html(data);
                if(option != null){
                    console.log(option);
                    setTimeout(function(){
                        $("#G6_C332").val(option).trigger('change');

                    },500);
                }
            },
            //si ha ocurrido un error
            error: function() {
                after_save_error();
                alertify.error("<?php echo $error_de_red ;?>");
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

</script>

<!-- Con este codigo vamos a manipular los saltos -->
<script type="text/javascript">
    var longitud_de_opciones = 0;
    var longitud_de_opcionesSeccion = 0;
    $(function() {
        
        $("#btnAddTip").click(function() {

            $("#iterTip").val(Number($("#iterTip").val()) + 1);

            var iterTip = $("#iterTip").val();

            var intIdGuion_t = $(this).attr("guion");

            var strHTMLSelect_t = traerTip(intIdGuion_t, 'tipificaciones');

            var strHTMLSelect2_t = traerTip(intIdGuion_t, 'campos');

            var strHTMLTip_t = '';

            strHTMLTip_t += '<div id="rowTip_' + iterTip + '" class="rowTip" numero="' + iterTip + '">';
            strHTMLTip_t += '<div class="col-md-5 col-xs-5">';
            strHTMLTip_t += '<div class="form-group">';
            strHTMLTip_t += '<select id="tip_' + iterTip + '" name="tip_' + iterTip + '" class="form-control input-sm IdTip">';
            strHTMLTip_t += strHTMLSelect_t;
            strHTMLTip_t += '</select>';
            strHTMLTip_t += '</div>';
            strHTMLTip_t += '</div>';
            strHTMLTip_t += '<div class="col-md-5 col-xs-5">';
            strHTMLTip_t += '<div class="form-group">';
            strHTMLTip_t += '<select id="campo_' + iterTip + '" name="campo_' + iterTip + '" class="form-control input-sm">';
            strHTMLTip_t += strHTMLSelect2_t;
            strHTMLTip_t += '</select>';
            strHTMLTip_t += '</div>';
            strHTMLTip_t += '</div>';
            strHTMLTip_t += '<div class="col-md-2 col-xs-2">';
            strHTMLTip_t += '<div class="form-group">';
            strHTMLTip_t += '<button class="form-control btn btn-danger btn-sm EliminarTip" type="button" id="btnQuitarTip_' + iterTip + '" numero="' + iterTip + '"><i class="fa fa-trash-o"></i></button>';
            strHTMLTip_t += '</div>';
            strHTMLTip_t += '</div>';
            strHTMLTip_t += '</div>';

            $("#cuerpoTip").append(strHTMLTip_t);

            eliminarTip();
            IdTip();

        });

        $("#btnAddmoreJumps").click(function() {
            $.ajax({
                url: '<?=$url_crud_extender?>',
                type: 'post',
                data: {
                    getCamposLista: true,
                    guion: $("#hidId").val()
                },
                success: function(data) {
                    $("#cmbListaParaSalto").html(data);

                    $.ajax({
                        url: '<?=$url_crud_extender?>?camposGuionSaltos=true',
                        data: {
                            guion: $("#hidId").val(),
                            esMaster: 'si',
                            seccion: $("#IdseccionEdicion").val()
                        },
                        type: 'post',
                        success: function(datas) {
                            ListaCampoGuionMio = '<option value="0">Seleccione</option>';
                            ListaCampoGuionMio += datas;
                        },
                        complete: function() {
                            $.unblockUI();
                        }
                    });
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                }
            });



            $("#NuevoSaltoModal").modal();
        });
        
        $("#btnAddJumpSeccion").click(function() {
            $.ajax({
                url: '<?=$url_crud_extender?>',
                type: 'post',
                data: {
                    getCamposLista: true,
                    guion: $("#hidId").val()
                },
                success: function(data) {
                    $("#cmbListaParaSaltoSeccion").html(data);

                    $.ajax({
                        url: '<?=$url_crud_extender?>?camposGuionSeccion=true',
                        data: {
                            guion: $("#hidId").val(),
                        },
                        type: 'post',
                        success: function(datas) {
                            ListaCampoGuionMio = '<option value="0">Seleccione</option>';
                            ListaCampoGuionMio += datas;
                        },
                        complete: function() {
                            $.unblockUI();
                        }
                    });
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                }
            });



            $("#NuevoSaltoModalSeccion").modal();
        });

        $("#cmbListaParaSalto").change(function() {
            var option = $("#cmbListaParaSalto option:selected").attr('idOption');
            $.ajax({
                url: '<?=$url_crud_extender?>',
                type: 'post',
                dataType: 'json',
                data: {
                    getListasEdit: true,
                    idOpcion: option
                },
                success: function(data) {
                    if (data.code == '1') {
                        OpcionesListas = data.lista;
                    }
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                complete: function() {
                    $.unblockUI();
                }
            })
        });
        
        $("#cmbListaParaSaltoSeccion").change(function() {
            var option = $("#cmbListaParaSaltoSeccion option:selected").attr('idOption');
            $.ajax({
                url: '<?=$url_crud_extender?>',
                type: 'post',
                dataType: 'json',
                data: {
                    getListasEdit: true,
                    idOpcion: option
                },
                success: function(data) {
                    if (data.code == '1') {
                        OpcionesListas = data.lista;
                    }
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                complete: function() {
                    $.unblockUI();
                }
            })
        });

        $("#newOpcionSalto").click(function() {
            var campo = "<tr id='idtr_" + longitud_de_opciones + "' numero=\"" + longitud_de_opciones + "\" class=\"rowSaltoNew\">";
            campo += "<td style='width:45%;'>";
            campo += "<select class='form-control' name='opcionesCampo_" + longitud_de_opciones + "' fila='" + longitud_de_opciones + "'>";
            $.each(OpcionesListas, function(i, itms) {
                campo += "<option value='" + itms.LISOPC_ConsInte__b + "'>" + itms.LISOPC_Nombre____b + "</option>";
            });
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<select class='form-control' id='camposCOnfiguradosGuionTo_" + longitud_de_opciones + "' name='camposCOnfiguradosGuionTo_" + longitud_de_opciones + "' fila='" + longitud_de_opciones + "'>";
            campo += ListaCampoGuionMio;
            campo += "</select>";
            campo += "</td>";
            campo += "<td style='text-align: center;'>";
            campo += "<input type = 'checkbox' id = 'limpiarCampos_" + longitud_de_opciones + "' name = 'limpiarCampos_" + longitud_de_opciones + "'>";
            campo += "</td>";
            campo += "<td>";
            campo += "<button class='btn btn-sm btn-danger' id='delEstaRow_" + longitud_de_opciones + "' type='button' fila='" + longitud_de_opciones + "'><i class='fa fa-trash-o'></i></button>";
            campo += "</td>";

            $("#cuerpoSaltosTB").append(campo);

            $("#delEstaRow_" + longitud_de_opciones + "").click(function() {
                var id = $(this).attr('fila');
                $("#idtr_" + id).remove();
            });

            $("#camposCOnfiguradosGuionTo_" + longitud_de_opciones).change(function() {
                try {
                    let requerido=$(this).val().split("_");
                    if(requerido.length > 1){
                        alertRequerido(this.id);
                    }
                } catch (error) {
                    console.info(error);
                }
            });

            longitud_de_opciones++;
            $("#contadorSaltos").val(longitud_de_opciones);

        });
        
        $("#newOpcionSaltoSeccion").click(function() {
            var campo = "<tr id='idtrSeccion_" + longitud_de_opcionesSeccion + "' numero=\"" + longitud_de_opcionesSeccion + "\" class=\"rowSaltoNewSeccion\">";
            campo += "<td style='width:45%;'>";
            campo += "<select class='form-control' name='opcionesCampoSeccion_" + longitud_de_opcionesSeccion + "' fila='" + longitud_de_opcionesSeccion + "'>";
            $.each(OpcionesListas, function(i, itms) {
                campo += "<option value='" + itms.LISOPC_ConsInte__b + "'>" + itms.LISOPC_Nombre____b + "</option>";
            });
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<select class='form-control' id='camposCOnfiguradosGuionToSeccion_" + longitud_de_opcionesSeccion + "' name='camposCOnfiguradosGuionToSeccion_" + longitud_de_opcionesSeccion + "' fila='" + longitud_de_opcionesSeccion + "'>";
            campo += ListaCampoGuionMio;
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<button class='btn btn-sm btn-danger' id='delEstaRowSeccion_" + longitud_de_opcionesSeccion + "' type='button' fila='" + longitud_de_opcionesSeccion + "'><i class='fa fa-trash-o'></i></button>";
            campo += "</td>";

            $("#cuerpoSaltosTBSeccion").append(campo);

            $("#delEstaRowSeccion_" + longitud_de_opcionesSeccion + "").click(function() {
                var id = $(this).attr('fila');
                $("#idtrSeccion_" + id).remove();
            });

            $("#camposCOnfiguradosGuionToSeccion_" + longitud_de_opcionesSeccion).change(function() {
                try {
                    let requerido=$(this).val().split("_");
                    if(requerido.length > 1){
                        alertSeccionRequerida(this.id);
                    }
                } catch (error) {
                    console.info(error);
                }
            });

            longitud_de_opcionesSeccion++;
            $("#contadorSaltosSeccion").val(longitud_de_opcionesSeccion);

        });

        $("#editarOpcionSalto").click(function() {
            longitud_de_opciones = $("#contadorSaltosEdicion").val();
            longitud_de_opciones++;

            var campo = "<tr id='idtr_" + longitud_de_opciones + "' numero=\"" + longitud_de_opciones + "\" class=\"rowSaltoEdit\">";
            campo += "<td style='width:45%;'>";
            campo += "<select class='form-control' name='opcionesCampo_" + longitud_de_opciones + "' fila='" + longitud_de_opciones + "'>";
            $.each(OpcionesListas, function(i, itms) {
                campo += "<option value='" + itms.LISOPC_ConsInte__b + "'>" + itms.LISOPC_Nombre____b + "</option>";
            });
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<select class='form-control' id='camposCOnfiguradosGuionTo_" + longitud_de_opciones + "' name='camposCOnfiguradosGuionTo_" + longitud_de_opciones + "' fila='" + longitud_de_opciones + "'>";
            campo += ListaCampoGuionMio;
            campo += "</select>";
            campo += "</td>";
            campo += "<td style = 'text-align: center;'>";
            campo += "<input type='checkbox' id='limpiarCampos_" + longitud_de_opciones + "' name = 'limpiarCampos_" + longitud_de_opciones + "'>";
            campo += "</td>";
            campo += "<td>";
            campo += "<button class='btn btn-sm btn-danger' id='delEstaRow_" + longitud_de_opciones + "' type='button' fila='" + longitud_de_opciones + "'><i class='fa fa-trash-o'></i></button>";
            campo += "</td>";

            $("#cuerpoSaltosTBEdicion").append(campo);

            $("#delEstaRow_" + longitud_de_opciones + "").click(function() {
                var id = $(this).attr('fila');
                $("#idtr_" + id).remove();
            });

            $("#camposCOnfiguradosGuionTo_" + longitud_de_opciones).change(function() {
                try {
                    let requerido=$(this).val().split("_");
                    if(requerido.length > 1){
                        alertRequerido(this.id);
                    }
                } catch (error) {
                    console.info(error);
                }
            });

            $("#contadorSaltosEdicion").val(longitud_de_opciones);
        });
        
        $("#editarOpcionSaltoSeccion").click(function() {
            longitud_de_opcionesSeccion = $("#contadorSaltosEdicionSeccion").val();
            longitud_de_opcionesSeccion++;

            var campo = "<tr id='idtrSeleccion_" + longitud_de_opcionesSeccion + "' numero=\"" + longitud_de_opcionesSeccion + "\" class=\"rowSaltoEditSeccion\">";
            campo += "<td style='width:45%;'>";
            campo += "<select class='form-control' name='opcionesCampoSeccion_" + longitud_de_opcionesSeccion + "' fila='" + longitud_de_opcionesSeccion + "'>";
            $.each(OpcionesListas, function(i, itms) {
                campo += "<option value='" + itms.LISOPC_ConsInte__b + "'>" + itms.LISOPC_Nombre____b + "</option>";
            });
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<select class='form-control' id='camposCOnfiguradosGuionToSeccion_" + longitud_de_opcionesSeccion + "' name='camposCOnfiguradosGuionToSeccion_" + longitud_de_opcionesSeccion + "' fila='" + longitud_de_opcionesSeccion + "'>";
            campo += ListaCampoGuionMio;
            campo += "</select>";
            campo += "</td>";
            campo += "<td>";
            campo += "<button class='btn btn-sm btn-danger' id='delEstaRowSeccion_" + longitud_de_opcionesSeccion + "' type='button' fila='" + longitud_de_opcionesSeccion + "'><i class='fa fa-trash-o'></i></button>";
            campo += "</td>";

            $("#cuerpoSaltosTBEdicionSeccion").append(campo);

            $("#delEstaRowSeccion_" + longitud_de_opcionesSeccion + "").click(function() {
                var id = $(this).attr('fila');
                $("#idtrSeleccion_" + id).remove();
            });

            $("#camposCOnfiguradosGuionToSeccion_" + longitud_de_opcionesSeccion).change(function() {
                try {
                    let requerido=$(this).val().split("_");
                    if(requerido.length > 1){
                        alertSeccionRequerida(this.id);
                    }
                } catch (error) {
                    console.info(error);
                }
            });

            $("#contadorSaltosEdicionSeccion").val(longitud_de_opcionesSeccion);
        });

        $("#guardarSalto").click(function() {

            var arrNumeroSalto_t = new Array();
            $(".rowSaltoNew").each(function(i) {
                arrNumeroSalto_t[i] = $(this).attr("numero");
            });

            var valido = 1;
            if ($("#cmbListaParaSalto").val() == 0) {
                alertify.error('<?php echo $str_error_Salto____;?>');
                valido = 0;
            }

            if (valido == 1) {
                var form = $("#agrupadorSalto");
                //Se crean un array con los datos a enviar, apartir del formulario
                var formData = new FormData($("#agrupadorSalto")[0]);
                formData.append('agregarSalto', true);
                formData.append('guion', $("#hidId").val());
                formData.append('arrNumeroSalto_t', arrNumeroSalto_t);

                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'post',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.code == 1) {
                            alertify.success('<?php echo $str_Exito; ?>');
                            getSaltosSeccion($("#hidId").val());
                            $("#NuevoSaltoModal").modal('hide');
                        } else {
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                })
            }
        });        
        
        $("#guardarSaltoSeccion").click(function() {

            var arrNumeroSalto_t = new Array();
            $(".rowSaltoNewSeccion").each(function(i) {
                arrNumeroSalto_t[i] = $(this).attr("numero");
            });

            var valido = 1;
            if ($("#cmbListaParaSaltoSeccion").val() == 0) {
                alertify.error('<?php echo $str_error_Salto____;?>');
                valido = 0;
            }

            if (valido == 1) {
                var form = $("#agrupadorSaltoSeccion");
                //Se crean un array con los datos a enviar, apartir del formulario
                var formData = new FormData($("#agrupadorSaltoSeccion")[0]);
                formData.append('agregarSaltoSeccion', true);
                formData.append('guion', $("#hidId").val());
                formData.append('arrNumeroSalto_t', arrNumeroSalto_t);

                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'post',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.code == 1) {
                            alertify.success('<?php echo $str_Exito; ?>');
                            getSaltosSecciones($("#hidId").val());
                            $("#NuevoSaltoModalSeccion").modal('hide');
                        } else {
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                })
            }
        });

        $("#editarSalto").click(function() {
            var arrNumeroSalto_t = new Array();

            $(".rowSaltoEdit").each(function(i) {
                arrNumeroSalto_t[i] = $(this).attr("numero");
            });

            var valido = 1;
            if ($("#cmbListaParaSaltoEdicion").val() == 0) {
                alertify.error('<?php echo $str_error_Salto____;?>');
                valido = 0;
            }

            if (valido == 1) {
                var form = $("#agrupadorSaltoEdicion");
                //Se crean un array con los datos a enviar, apartir del formulario
                var formData = new FormData($("#agrupadorSaltoEdicion")[0]);
                formData.append('editarSalto', true);
                formData.append('guion', $("#hidId").val());
                formData.append('arrNumeroSalto_t', arrNumeroSalto_t);

                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'post',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.code == 1) {
                            alertify.success('<?php echo $str_Exito; ?>');
                            getSaltosSeccion($("#hidId").val());
                            $("#EditarSaltoModal").modal('hide');
                        } else {
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                })
            }
        });        
        
        $("#editarSaltoSeccion").click(function() {
            var arrNumeroSalto_t = new Array();

            $(".rowSaltoEditSeccion").each(function(i) {
                arrNumeroSalto_t[i] = $(this).attr("numero");
            });

            var valido = 1;
            if ($("#cmbListaParaSaltoEdicionSeccion").val() == 0) {
                alertify.error('<?php echo $str_error_Salto____;?>');
                valido = 0;
            }

            if (valido == 1) {
                var form = $("#agrupadorSaltoEdicionSeccion");
                //Se crean un array con los datos a enviar, apartir del formulario
                var formData = new FormData($("#agrupadorSaltoEdicionSeccion")[0]);
                formData.append('editarSaltoSeccion', true);
                formData.append('guion', $("#hidId").val());
                formData.append('arrNumeroSalto_t', arrNumeroSalto_t);

                $.ajax({
                    url: '<?=$url_crud_extender?>',
                    type: 'post',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        if (data.code == 1) {
                            alertify.success('<?php echo $str_Exito; ?>');
                            getSaltosSecciones($("#hidId").val());
                            $("#EditarSaltoModalSeccion").modal('hide');
                        } else {
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }
                    },
                    beforeSend: function() {
                        $.blockUI({
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                })
            }
        });
    });

</script>

<!-- CON ESTE SCRIPT VALIDAMOS QUE EL USUARIO ESCRIBA UNA FORMULA VALIDA PARA LOS CAMPOS NUMERO Y DECIMAL -->
<script type="text/javascript" language="javascript">
	
    var _numeros = '0123456789';
    var _operadores = '/*-+^';
    var _cadenas = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ_';

    function _porId(id){
        return document.getElementById(id);
    }

    function rtrim(s, que){
        var j=0;

        // Busca el último caracter segun el especificado
        for(var i=s.length-1; i>-1; i--)
            if(s.substring(i,i+1) != que){
                j=i;
                break;
            }
        return s.substring(0, j+1);
    }

    function InsEnCursor(idcampo, valor) {
        var campo = document.getElementById(idcampo);
        //IE support
        if (document.selection) {
            campo.focus();
            sel = document.selection.createRange();
            sel.text = valor;
        }
        //MOZILLA/NETSCAPE support
        else if (campo.selectionStart || campo.selectionStart == '0') {
            var startPos = campo.selectionStart;
            var endPos = campo.selectionEnd;
            campo.value = campo.value.substring(0, startPos)
            + valor
            + campo.value.substring(endPos, campo.value.length);
        } 
        else {
            campo.value += valor;
        }
    }

    function Seleccionado(idcampo) {
        var campo = document.getElementById(idcampo);
        //IE support
        if (document.selection) {
            campo.focus();
            sel = document.selection.createRange();
            return sel.text;
        }
        //MOZILLA/NETSCAPE support
        else if (campo.selectionStart || campo.selectionStart == '0') {
            var startPos = campo.selectionStart;
            var endPos = campo.selectionEnd;
            return campo.value.substring(startPos, endPos);
        } 
        else {
            return campo.value;
        }
    }
    
    function CaracterAntesCursor (idcampo){
        var campo = _porId(idcampo);
        //IE support
        if (document.selection) {
            campo.focus();
            sel = document.selection.createRange();
            sel.moveStart("character", -1);
            
            sel2 = document.selection.createRange();

            //Si se había seleccionado desde el principio de texto
            if (sel.text == sel2.text){
                return null;
            }
            return sel.text.substr(0, 1);
        }
        //MOZILLA/NETSCAPE support
        else if (campo.selectionStart || campo.selectionStart == '0') {
            var startPos = campo.selectionStart;
            
            if (startPos == 0){
                return null;
            }
            
            return campo.value.substr(startPos - 1, 1);
        }
        
        return null;
    }

    function CaracterDespuesCursor (idcampo){
        var campo = _porId(idcampo);
        //IE support
        if (document.selection) {
            campo.focus();
            sel = document.selection.createRange();
            sel.moveStart("character", sel.text.length);
            sel.moveEnd("character", 1);
            return sel.text.substr(0, 1);
        }
        //MOZILLA/NETSCAPE support
        else if (campo.selectionStart || campo.selectionStart == '0') {
            var endPos = campo.selectionEnd;
            
            if (endPos == campo.value.length - 1){
                return null;
            }
            
            return campo.value.substr(endPos, 1);
        }
        
        return null;
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

    function ValidarCorchetes(s) {
        var i;
        var va = 0;
        for (i = 0; i < s.length; i++){
            if(s.substring(i,i+1) == '{'){
                va++;
            }
            else if(s.substring(i,i+1) == '}'){
                va--;
            }
            
            if (va < 0){
                return false;
            }
        }
        
        //si va no termina en cero la expresión está incorrectamente parentisada
        return va == 0;
    }

    function ValidarCampos(s) {
        var i;
        
        for (i = 0; i < s.length; i++){
            if(s.substring(i,i+1) == '{'){
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
    
    function ValidarFormula () {
        var s = _porId('G6_C321').value;
        
        if ($("#G6_C321").val()== ''){
            $("#errorFormula").html('El campo "Formula para calcular el valor" no puede estar vacío');
            return false;
        }
        
        if (!ValidarParentesis(s)){
            $("#errorFormula").html('Los parentesis estan mal configurados, hay mas abiertos o cerrados');
            return false;
        }

        if (!ValidarCorchetes(s)){
            $("#errorFormula").html('Las variables no estan bien definidas');
            return false;
        }
        
        var c = '';
        var estado = 0;
        for (var i = 0; i < s.length; i++){
            c = EsteCaracter (s, i);
            switch (estado) {
                case 0:
                    switch (true){
                        case EsNumero(c):
                            estado = 2;
                            continue;
                        case c == '-':
                            estado = 3;
                            continue;
                        case c == '(':
                            estado = 0;
                            continue;

                        case c == '$':
                            estado = 8;
                            continue;
                    }
                    return Error(i, c, '0-9, -, (, $');
                    break;
                case 1:
                    switch (true){
                        case EsLetra(c):
                            estado = 7;
                            continue;
                    }
                    return Error(i, c, 'a-z, A-Z, _');
                    break;
                case 2:
                    switch (true){
                        case EsNumero(c):
                            estado = 2;
                            continue;
                        case c == '-' || EsOperador(c):
                            estado = 3;
                            continue;
                        case c == ')':
                            estado = 4;
                            continue;
                        case c == '.':
                            estado = 5;
                            continue;
                    }
                    return Error(i, c, '0-9, ), ., -, *, +, /, ^');
                    break;
                case 3:
                    switch (true){
                        case EsNumero(c):
                            estado = 2;
                            continue;
                        case c == '(':
                            estado = 0;
                            continue;
                        case c == '$':
                            estado=8;
                            continue;
                    }
                    return Error(i, c, '0-9, (, $');
                    break;
                case 4:
                    switch (true){
                        case c == '-' || EsOperador(c):
                            estado = 3;
                            continue;
                        case c == ')':
                            estado = 4;
                            continue;
                    }
                    return Error(i, c, '), -, *, +, /, ^');
                    break;
                case 5:
                    switch (true){
                        case EsNumero(c):
                            estado = 6;
                            continue;
                    }
                    return Error(i, c, '0-9');
                    break;
                case 6:
                    switch (true){
                        case EsNumero(c):
                            estado = 6;
                            continue;
                        case c == '-' || EsOperador(c):
                            estado = 3;
                            continue;
                        case c == ')':
                            estado = 4;
                            continue;
                            case c == '}':
                            estado = 4;
                            continue;
                    }
                    return Error(i, c, '0-9, ), -, *, +, /, ^');
                    break;
                case 7:
                    switch (true){
                        case EsLetra(c):
                            estado = 7;
                            continue;
                        case c == '}':
                            estado = 4;
                            continue;
                        case EsNumero(c):
                            estado = 6;
                            continue;
                    }
                    return Error(i, c, 'a-z, A-Z, _, }');
                    break;
                case 8:
                    if(c == '{'){
                        estado=1;
                        continue;
                    };
                    return Error(i, c, "{");
            }
            return;
        }
        
        if (estado != 2 && estado != 4 && estado != 6) {
            $("#errorFormula").html('La formula se encuentra incompleta');
            return false;
        }
        
        return true;
    }
    
    function Error(i, c, esperaba) {
        $("#errorFormula").html('En el caracter de la posición ' + (i + 1) + ' se encontro "' + c + '" cuando se esperaba uno de los siguientes caracteres: ' + esperaba );
        return false;
    }
    
    function EsNumero (c) {
        if (_numeros.indexOf(c) != -1){
            return true;
        }
        
        return false;
    }
    
    function EsLetra (c) {
        if (_cadenas.indexOf(c) != -1){
            return true;
        }
        
        return false;
    }

    function EsOperador (c) {
        if (_operadores.indexOf(c) != -1){
            return true;
        }
        
        return false;
    }
    
    function EsteCaracter (s, k){
        if (s.length < k){
            return null;
        }
        
        return s.substr(k, 1);
    }

</script>