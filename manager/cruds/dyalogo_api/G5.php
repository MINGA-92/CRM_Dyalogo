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
   $url_crud = "cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN

    if(isset($_GET['tipo'])){
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
                        <br/>
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

                                        echo "<tr class='CargarDatos' id='".$obj->id."'>
                                                <td>
                                                    <p style='font-size:14px;'><b>".strtoupper(($obj->camp1))."</b></p>
                                                    <p style='font-size:12px; margin-top:-10px;'>".strtoupper($tipo)."</p>
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
                            <a class="btn btn-default" title="Vista Previa" id="vistaPrevia" target="_blank" href="#">
                                <i class="fa  fa-eye"></i>
                            </a>
                            <!--<button class="btn btn-default" disabled title="Generar" id="generar">
                                <i class="fa  fa-magic"></i>
                            </button>-->
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
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
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
                                                            <input type="text" disabled class="form-control input-sm" id="G5_C28" value=""  name="G5_C28"  placeholder="<?php echo $str_nombre______g_;?>">
                                                        </div>
                                                        <input type="hidden" name="G5_C29" id="G5_C29" value="<?php echo $_GET['tipo'];?>">
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->      
                                                    </div>
                                                    <?php }else{ ?>
                                                    <div class="col-md-12 col-xs-12">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="G5_C28" id="LblG5_C28"><?php echo $str_nombre______g_;?></label>
                                                            <input type="text" disabled class="form-control input-sm" id="G5_C28" value=""  name="G5_C28"  placeholder="<?php echo $str_nombre______g_;?>">
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->      
                                                    </div>
                                                    <div class="col-md-12 col-xs-12" style="display: none;">
                                                        <!-- CAMPO DE TIPO LISTA -->
                                                        <div class="form-group">
                                                            <label for="G5_C29" id="LblG5_C29"><?php echo $str_tipo________g_; ?></label>
                                                            <select disabled class="form-control input-sm str_Select2"  style="width: 100%;" name="G5_C29" id="G5_C29">
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
                                                            <textarea disabled class="form-control input-sm" name="G5_C30" id="G5_C30"  value="" placeholder="<?php echo $str_meage_observa_;?>"></textarea>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO MEMO -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="box box-primary box-solid">
                                            <div class="box-header ">
                                                <h3 class="box-title"><?php echo $str_Secciones_E_g_;?></h3>
                                                <div class="box-tools">
                                                    <button class="btn btn-link" title="<?php echo $str_mas_config_wf_; ?>" id="btnAddMoreSeccion" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="box-body" id="secciones_creadas" >  
                                            </div>
                                        </div>

                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_1">
                                                        <?php echo $str_Vanzadao__E_g_; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_1" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="G5_C31" id="LblG5_C31"><?php echo $str_campo_princ_g_;?></label>
                                                                <select class="form-control input-sm" style="width: 100%;"  name="G5_C31" id="G5_C31">
                                                                   <option value="0"></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="G5_C59" id="LblG5_C59"><?php echo $str_campo_segun_g_;?></label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G5_C59" id="G5_C59">
                                                                    <option value="0"></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="primario" id="primario">
                                                        <input type="hidden" name="segundario" id="segundario">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 1) { ?>
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_2">
                                                        <?php echo $str_Saltos____E_g_; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_2" class="panel-collapse collapse">
                                                <div clss="box-body">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == 2) { ?>
                                        <div class="panel box box-primary box-solid">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_30">
                                                        <?php echo $str_config_web_f__; ?>
                                                    </a>
                                                </h4>
                                                <div class="box-tools">
                                                    <button class="btn btn-link" title="<?php echo $str_mas_config_wf_; ?>" id="btnAddMoreConfig" type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="s_30" class="panel-collapse collapse in">
                                                <div clss="box-body">
                                                    <br/>
                                                    <br/>
                                                   
                                                    <div class="row">
                                                        <div class="col-md-12" id="nuevosConfiguraciones">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <!-- SECCION : PAGINAS INCLUIDAS -->
                                        <input type="hidden" name="id" id="hidId" value='0'>
                                        <input type="hidden" name="oper" id="oper" value='add'>
                                        <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
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

<script type="text/javascript" src="assets/js/jStepper.js"></script>



<div class="modal fade" id="NuevaGuionModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_new_________g_; ?></h4>
            </div>
            <div class="modal-body">
                <form id="nuevoGuion" >
                    
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="GenerarFromExel" id="GenerarFromExel" value="1">&nbsp;<?php echo $str_generar_excel_;?>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12 excel" style="display: none;" >
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
                <button class="btn btn-default " type="button" id="cancelarModal" data-dismiss="modal" >
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
                
            </div>
            <div class="modal-body">
                <p>
                    <h4 class="modal-title" id="idTitle"><?php echo $str_message_genera; ?></h4>
                </p>
                <br/>
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
                           
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkBusqueda" value="1"><?php echo $str_message_gener1; ?>
                                        </label>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" checked name="checkWebForm" value="1"><?php echo $str_message_gener2; ?>
                                        </label>
                                    </div>
                                </div>
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
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
                
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="crearSeccionesModal">
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="G7_C33" id="LblG7_C33"><?php echo $str_nombre______S_;?></label>
                                    <input type="text" class="form-control input-sm" id="G7_C33" value=""  name="G7_C33"  placeholder="<?php echo $str_nombre______S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="G7_C35" id="LblG7_C35"><?php echo $str_Numcolumns__S_;?></label>
                                    <input type="text" class="form-control input-sm Numerico" value="2"  name="G7_C35" id="G7_C35" placeholder="<?php echo $str_Numcolumns__S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="G7_C36" id="LblG7_C36"><?php echo $str_apariencia__S_;?></label>
                                <select class="form-control input-sm str_Select2"  style="width: 100%;" name="G7_C36" id="G7_C36">
                                    <option value="0"><?php echo $str_seleccione; ?></option>
                                    <option value="3" selected="true"><?php echo $str_seccion_a_4_s_; ?></option>
                                    <option value="1"><?php echo $str_seccion_a_1_s_; ?></option>
                                    <option value="2"><?php echo $str_seccion_a_2_s_; ?></option>
                                    
                                    <option value="4"><?php echo $str_seccion_a_3_s_; ?></option>
                                    <!--<option value="5"><?php echo $str_seccion_a_5_s_; ?></option>-->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="cerradoAcordeon">
                                    <label for="G7_C36" id="LblG7_C36" style="visibility: hidden;"><?php echo $str_apariencia__S_;?></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="-1" name="G7_C37" id="G7_C37"> <?php echo $str_minimizada__S_;?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel" id="cuerpoSeccion">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 30%; text-align: center;"><?php echo $str_campo_texto___; ?></th>
                                    <th style="width: 25%; text-align: center;"><?php echo $str_campo_tipo____; ?></th>
                                    <th style="width: 25%; text-align: center;"><?php echo $str_campo_lista___; ?></th>
                                    <th style="width: 10%; text-align: center;"><?php echo $str_campo_busqu___; ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaQuesedebeGuardar">
                                
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                
                <button class="btn btn-success  pull-left" type="button" id="newFieldSeccion">
                    <?php echo $str_control_save__;?>
                </button>
                <button class="btn-primary btn" type="button" id="crearSeccionesModalButton">
                    <?php echo $str_seccion_save__;?>
                </button>
                <button class="btn btn-default pull-right limpiarSecciones" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarSeccionesModal">
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="EditarG7_C33" id="LblEditarG7_C33"><?php echo $str_nombre______S_;?></label>
                                    <input type="text" class="form-control input-sm" id="EditarG7_C33" value=""  name="EditarG7_C33"  placeholder="<?php echo $str_nombre______S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="EditarG7_C35" id="LblEditarG7_C35"><?php echo $str_Numcolumns__S_;?></label>
                                    <input type="text" class="form-control input-sm Numerico" value="2"  name="EditarG7_C35" id="EditarG7_C35" placeholder="<?php echo $str_Numcolumns__S_;?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="EditarG7_C36" id="LblEditarG7_C36"><?php echo $str_apariencia__S_;?></label>
                                <select class="form-control input-sm str_Select2"  style="width: 100%;" name="EditarG7_C36" id="EditarG7_C36">
                                    <option value="0"><?php echo $str_seleccione; ?></option>
                                    <option value="3" selected="true"><?php echo $str_seccion_a_4_s_; ?></option>
                                    <option value="1"><?php echo $str_seccion_a_1_s_; ?></option>
                                    <option value="2"><?php echo $str_seccion_a_2_s_; ?></option>
                                    
                                    <option value="4"><?php echo $str_seccion_a_3_s_; ?></option>
                                    <!--<option value="5"><?php echo $str_seccion_a_5_s_; ?></option>-->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" id="EditarcerradoAcordeon">
                                    <label for="G7_C36" id="LblG7_C36" style="visibility: hidden;"><?php echo $str_apariencia__S_;?></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="-1" name="EditarG7_C37" id="EditarG7_C37"> <?php echo $str_minimizada__S_;?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel" id="cuerpoSeccion">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 30%; text-align: center;"><?php echo $str_campo_texto___; ?></th>
                                    <th style="width: 25%; text-align: center;"><?php echo $str_campo_tipo____; ?></th>
                                    <th style="width: 25%; text-align: center;"><?php echo $str_campo_lista___; ?></th>
                                    <th style="width: 10%; text-align: center;"><?php echo $str_campo_busqu___; ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaQuesedebeGuardarEdicion">
                                
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="IdseccionEdicion" id="IdseccionEdicion" value="0" >
                    <input type="hidden" name="contadorEditablesPreguntas" id="contadorEditablesPreguntas">
                </form>
            </div>
            <div class="modal-footer">
                
                <button class="btn btn-success  pull-left" type="button" id="newFieldSeccion2">
                    <?php echo $str_control_save__;?>
                </button>
                <button class="btn-primary btn" type="button" id="editarSeccionesModalButton">
                    <?php echo $str_seccion_save__;?>
                </button>
                <button class="btn btn-default pull-right limpiarSecciones" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
                
            </div>
        </div>
    </div>
</div>

<div  id="NuevaListaModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_Lista_datos___; ?></h4>
            </div>
            <div class="modal-body">
                <form id="agrupador" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_Lista_nombre__; ?></label>
                                <input type="text" name="txtNombreLista" id="txtNombreLista" class="form-control" placeholder="<?php echo $str_Lista_nombre__; ?>">
                            </div>
                            <div class="form-group" style="display: none;">
                                <div class="checkbox">
                                    <input type="checkbox" value="1" name="checkTipificacion" id="checkTipificacion" data-error="Before you wreck yourself"  > <?php echo $str_Lista_tipifica;?>
                                    
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
                </form>
            </div>
            <div class="modal-footer">
                
                <button class="btn btn-success pull-left" type="button" id="newOpcion">
                    <?php echo $str_new_opcion____;?>
                </button>   
                <button class="btn-primary btn " type="button" id="guardarLista">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>  
                
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="assets/plugins/colorpicker/bootstrap-colorpicker.min.css">
<script src="assets/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
    var bool_estamosListos = false;
    var newContador = 0;
    var cuantosVan2 = 0;
    var ListasBd = '';
    var GuionesBD = '';
    $(function(){
        
        $.ajax({
            url     : '<?php echo $url_crud;?>',
            type    : 'post',
            data    : { getListasDelSistema :true },
            success : function(data){
                ListasBd = data;
            }
        })


        $.ajax({
            url     : '<?php echo $url_crud;?>',
            type    : 'post',
            data    : { getGuionesDelSistema :true },
            success : function(data){
                GuionesBD = data;
            }
        })

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
      
        $("#btnLlamadorAvanzado").click(function(){
            $('#busquedaAvanzada_ :input').each(function(){
                $(this).attr('disabled', false);
            });
        });

        

        $("#tablaScroll").on('scroll', function() {
            //alert('Si llegue');
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                <?php if(isset($_GET['tipo'])){ ?>
                    $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si' , tipo : <?php echo $_GET['tipo'];?> }, function(data){
                        if(data != ""){
                            $("#TablaIzquierda").append(data);
                            inicio += fin;
                            busqueda_lista_navegacion();
                        }
                    });
                <?php }else{ ?>
                    $.post("<?=$url_crud;?>", { inicio : inicio, fin : fin , callDatosNuevamente : 'si' }, function(data){
                        if(data != ""){
                            $("#TablaIzquierda").append(data);
                            inicio += fin;
                            busqueda_lista_navegacion();
                        }
                    });
                <?php } ?>
            }
        });

        //SECCION FUNCIONALIDAD BOTONES

        //Funcionalidad del boton + , add
        $("#add").click(function(){

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

            $("#G5_C31").attr('disabled', true);
            $("#G5_C59").attr('disabled', true);


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

            $('#formularioSecciones_form :input').each(function(){
                if($(this).is(':checkbox')){
                    if($(this).is(':checked')){
                        $(this).attr('checked', false);
                    }
                    $(this).attr('disabled', true); 
                }else{
                    if($(this).is(':hidden')){
                        $(this).attr('disabled', true); 
                    }else{
                        $(this).val('');
                        $(this).attr('disabled', true);    
                    }
                     
                }
                               
            });
            vamosRecargaLasGrillasPorfavor(0);
            $("#oper_seccion").val('add');
            $("#formularioSecciones").show();
            $("#formularioPregun").hide();
            $("#add_seccion").attr('disabled' , false);
        });

        jQuery.fn.reset = function () {
            $(this).each (function() { this.reset(); });
        }

        //funcionalidad del boton editar
        $("#edit").click(function(){

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
            $('#FormularioDatos :input').each(function(){
                $(this).attr('disabled', false);
            });

            $('#formularioSecciones_form :input').each(function(){
                $(this).attr('disabled', true);
            });

            before_edit();

            vamosRecargaLasGrillasPorfavor(idTotal);
          
        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function(){
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);

            $("#formularioSecciones").hide();
            $("#formularioPregun").hide();

            bool_estamosListos = false;
        });

        //funcionalidad del boton eliminar
        $("#delete").click(function(){

            $.ajax({
                url    : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                type   : 'post',
                data   : { validarParaBorrar: true , id : $("#hidId").val() },
                dataType : 'json',
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                },
                complete : function(){
                    $.unblockUI();
                },
                success  : function(data){

                    ///data = jQuery.parseJSON(data);

                    if(data.code == '0'){ 
                        alertify.confirm("<?php if(isset($_GET['tipo']) && $_GET['tipo'] == 2){ echo $str_message_G_del1; }else if(isset($_GET['tipo']) && $_GET['tipo'] == 1){ echo $str_message_G_del2; }else if(isset($_GET['tipo']) && $_GET['tipo'] == 3){ echo $str_message_G_del3; } ?>", function (e) {
                        //Si la persona acepta
                        if (e) {
                            var id = $("#hidId").val();
                            //se envian los datos, diciendo que la oper es "del"
                            $.ajax({
                                url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                                type     : 'POST',
                                data     : { id : id , oper : 'del' , tipo :  '<?php if(isset($_GET['tipo'])) { echo $_GET['tipo']; }else{ echo '0';} ?>'},
                                datatype : 'json',
                                beforeSend : function(){
                                    $.blockUI({ 
                                        baseZ: 2000,
                                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                                },
                                complete : function(){
                                    $.unblockUI();
                                },
                                success  : function(data){
                                    data = jQuery.parseJSON(data);
                                    if(data.code == '0'){   
                                        llenar_lista_navegacion('');
                                        alertify.success('<?php echo $str_Exito;?>');
                                    }else{
                                        alertify.error(data);
                                    }
                                } 
                            });
                            
                        } else {
                            
                        }
                    });
                    }else{
                        if(data.code == '-1'){

                            alertify.error('<?php echo $str_error_message_;?>'+data.campana);

                        }else if(data.code == '-2'){

                            alertify.error('<?php echo $str_error_messag2_;?>'+data.campana);

                        }else if(data.code == '-3'){

                            alertify.error('<?php echo $str_error_messag3_;?>'+data.campana);

                        }
                    }
                }
            });
            
            //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
            /*alertify.confirm("<?php //echo $str_message_generico_D;?>", function (e) {
                //Si la persona acepta
                if (e) {
                    var id = $("#hidId").val();
                    //se envian los datos, diciendo que la oper es "del"
                    $.ajax({
                        url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                        type     : 'POST',
                        data     : { id : id , oper : 'del'},
                        datatype : 'json',
                        beforeSend : function(){
                            $.blockUI({ 
                                baseZ: 2000,
                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        },
                        success  : function(data){
                            data = jQuery.parseJSON(data);
                            if(data.code == '0'){   
                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo  

                                llenar_lista_navegacion('');
                            }else{
                                if(data.code == '-1'){

                                    alertify.error('<?php echo $str_error_message_;?>'+data.campana);

                                }else if(data.code == '-2'){

                                    alertify.error('<?php echo $str_error_messag2_;?>'+data.campana);

                                }else if(data.code == '-3'){

                                    alertify.error('<?php echo $str_error_messag3_;?>'+data.campana);

                                }else{

                                    alertify.error(data);

                                }
                            }
                        } 
                    });
                    
                } else {
                    
                }
            }); */
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

        /* Generar opciones para darle funionalidad */
        
        $("#generar").click(function(){
            if($("#primario").val() == 0){
                alertify.error("<?php echo $str_message__cP_g_;?>");
            }else if($("#segundario").val() == 0){
                alertify.error("<?php echo $str_message__cS_g_;?>");   
            }else{
                //Se solicita confirmacion de la operacion, para asegurarse de que no sea por error
                <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                    $("#PreguntarGenerar_Base_Datos").modal();
                <?php }else { ?>
                alertify.confirm("¿Está seguro de que desea generar este formulario?", function (e) {
                    //Si la persona acepta
                    if (e) {
                        alertify.confirm("¿Esta operacion podria dañar algunos datos , desea seguir?", function (e) {
                            //Si la persona acepta
                            if (e) {
                                /* aqui se tira el ajax para la generación */
                                var id = $("#hidId").val();
                                //se envian los datos, diciendo que la oper es "del"
                                $.ajax({
                                    url      : '<?=$url_crud;?>?generarGuion=si',
                                    type     : 'POST',
                                    data     : { id : id , generar : 'si' , checkFormBackoffice : '-1' , checkGenerar : '-1'},
                                    success  : function(data){
                                        if(data == '1'){   
                                            alertify.success('Tabla generada con exito!');
                                        }else{
                                            //Algo paso, hay un error
                                            alert(data);
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
                            } else {
                                
                            }
                        });                 
                    } else {
                        
                    }
                }); 
                <?php } ?>   
            }
             
        });

        $("#generarTodoLoqueHalla").click(function(){
            guardarDatos(false);
        });
    });
</script>



<!-- esto es para la funcioanlidad de los botones de agregar Lista -->
<script type="text/javascript">
    var contador = 0;
    $(function(){
        var cuantosVan = 0;
        
        $("#newOpcion").click(function(){


            var cuerpo = "<div class='row' id='id_"+cuantosVan+"'>";
            cuerpo += "<div class='col-md-10'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' name='opciones[]' class='form-control opcionesGeneradas' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "<div class='col-md-2' style='text-align:center;'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<button class='btn btn-danger btn-sm deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+cuantosVan+"'><i class='fa fa-trash-o'></i></button>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuantosVan++;
            contador++;
            $("#opciones").append(cuerpo);

            
            
            $(".deleteopcion").click(function(){
                var id = $(this).attr('id');
                $("#id_"+id).remove();
                //contador = contador -1;
            });
        });

        $("#guardarLista").click(function(){
            var validator = 0;
            if($("#txtNombreLista").val().length < 1){
                validator = 1;
                alertify.error("Es necesario el nombre de la lista");
            }

            if(contador == 0){
                validator = 1;
                alertify.error("La lista no tiene opciones")
            }

            var vacios = 0;
            $(".opcionesGeneradas").each(function(){
                if($(this).val().length < 1){
                    vacios++;
                }
            });

            if(vacios > 0){
                alertify.error('No pueden haber opciones sin texto');
                validator = 1;
            }

            if(validator == 0){
                var form = $("#agrupador");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#agrupador")[0]);
                formData.append('idGuion', idTotal);
                formData.append('contador', contador);
                $.ajax({
                    url: 'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php?insertarDatosLista=si',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success: function(data){
                        if(data != '0'){
                            alertify.success("<?php echo $str_Exito;?>");
                            var id = $("#hidListaInvocada").val();
                            if($("#operLista").val() == 'add'){
                                $.ajax({
                                    url  : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?dameListas=true',
                                    type : 'post',
                                    success : function(options){
                                        $("#G6_C44_"+id).html(options);
                                        $("#G6_C44_"+id).val(data);
                                        $("#G6_C44_"+id).val(data).change();

                                        $("#agrupador")[0].reset();
                                        $("#opciones").html('');
                                        contador = 0;
                                    }
                                });
                            }else{
                                //Aqui estan editando la lista
                            }
                            
                            
                            $("#NuevaListaModal").modal('hide');
                        }else{
                            alertify.error("<?php echo $error_de_proceso;?>");
                        }
                    }
                });    
            }
            
        });
    })
</script>
<script type="text/javascript" src="cruds/DYALOGOCRM_SISTEMA/G5/G5_eventos.js"></script> 
<script type="text/javascript">
    $(function(){
        /*$('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });*/


        <?php if(isset($_GET['registroId'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos : 'SI', id : <?php echo $_GET['registroId']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    

                    $("#G5_C28").val(item.G5_C28);

                    $("#G5_C29").val(item.G5_C29);

                    $("#G5_C29").val(item.G5_C29).trigger("change"); 

                    $("#G5_C30").val(item.G5_C30);

                    $("#G5_C31").val(item.G5_C31);

                    $("#G5_C31").val(item.G5_C31).trigger("change"); 

                    $("#G5_C59").val(item.G5_C59);

                    $("#G5_C59").val(item.G5_C59).trigger("change"); 
                    
                    $("#h3mio").html(item.principal);
                    
                    idTotal = <?php echo $_GET['registroId'];?>;

                    if ( $("#"+idTotal).length > 0) {
                        $("#"+idTotal).click();   
                        $("#"+idTotal).addClass('active'); 
                    }else{
                        //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                        $(".CargarDatos :first").click();
                    }
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

        $("#hidId").val(<?php echo $_GET['registroId'];?>);

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
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G5_C31").change(function(){
            var valores = $("#G5_C31 option:selected").text();
            var campos = $("#G5_C31 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });

        $("#G6_C44").select2();
        $("#G6_C207").select2();       
                                      

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
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G5_C59").change(function(){
            var valores = $("#G5_C59 option:selected").text();
            var campos = $("#G5_C59 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                    

        //Buton gurdar
        $("#Save").click(function(){
            if($("#primario").val() == 0){
                alertify.error("<?php echo $str_message__cP_g_;?>");
            }else if($("#segundario").val() == 0){
                alertify.error("<?php echo $str_message__cS_g_;?>");   
            }else{
                $("#PreguntarGenerar_Base_Datos").modal();
            }
            
        });

        $("#btnLlamar_0").click(function(){
            guardarDatos(true);
        })
    });
        
    /* Esto es para guardar */
    function guardarDatos(isSaveOrNot){
        var bol_respuesta = before_save_Guion();
        if(bol_respuesta){
            var form = $("#FormularioDatos");
            //Se crean un array con los datos a enviar, apartir del formulario 
            var formData = new FormData($("#FormularioDatos")[0]);
            formData.append('contador' , newContador);
            $.ajax({
               url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data){
                    if(data != '-1'){
                       
                        //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                        if($("#oper").val() == 'add'){
                            idTotal = data;
                        }else{
                            idTotal= $("#hidId").val();
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
                            url         : '<?=$url_crud;?>?generarGuion=si',
                            type        : 'POST',
                            data        : formData,
                            cache       : false,
                            contentType : false,
                            processData : false,
                            success  : function(data){
                                if(data == '1'){   
                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '1'){ ?>
                                    alertify.success('<?php echo $str_message_succe2;?>');
                                    <?php } ?>

                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                                    alertify.success('<?php echo $str_message_succes;?>');
                                    <?php } ?>

                                    <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '3'){ ?>
                                    alertify.success('<?php echo $str_message_succe3;?>');
                                    <?php } ?>
                                }else{
                                    //Algo paso, hay un error
                                    alert(data);
                                }
                            },
                            complete : function(){
                                $("#checkFormBackoffice").attr('checked', true);
                                $("#checkGenerar").attr('checked', true);
                                $("#checkBusqueda").attr('checked', true);
                                $("#checkWebForm").attr('checked', true);

                                $("#PreguntarGenerar_Base_Datos").modal('hide');
                                $.unblockUI();

                            } 
                        });            

                        if(isSaveOrNot){
                            /* Aqui llamar al de las secciones para invocarlo */
                            $("#id_guion").val(idTotal);
                            $("#padre_seccion").val(idTotal);
                            vamosRecargaLasGrillasPorfavor(idTotal);  
                        }else{
                            form[0].reset();
                            llenar_lista_navegacion('');
                        }
                       
                    }else{
                        //Algo paso, hay un error
                        alertify.error('Un error ha ocurrido');
                    }                
                },
                //si ha ocurrido un error
                error: function(){
                    after_save_error();
                    alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                },
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                }
            });
        }
    }


    /* guardar datos de las preguntas */
    function guardardatosPregun(){
        var form = $("#formularioPregun_form");
        //Se crean un array con los datos a enviar, apartir del formulario 
        var formData = new FormData($("#formularioPregun_form")[0]);
        $.ajax({
            url: 'cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php?insertarDatosSubgrilla_0=si',  
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data){
                if(data){
                   /* si guardo eso perfecto */
                    alertify.confirm("<?php echo $str_exito_pregunta;?>", function (e) {
                        //Si la persona acepta
                        if (e) {
                            form[0].reset();
                            //Inializacion campos vacios por defecto
                            $('#formularioPregun_form :input').each(function(){
                                if($(this).is(':checkbox')){
                                    if($(this).is(':checked')){
                                        $(this).attr('checked', false);
                                    }
                                    $(this).attr('disabled', false); 
                                }else{
                                    if($(this).is(':hidden')){
                                        $(this).attr('disabled', false); 
                                    }else{
                                        $(this).val('');
                                        $(this).attr('disabled', false);    
                                    }
                                     
                                }
                                               
                            });

                            $("#G6_C40").val(0);
                            $("#G6_C40").val(0).change();

                            $("#formularioPregun > str_Select2").each(function(){
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
                        }else{
                            if($("#G5_C31").val() == 0 || $("#G5_C31").val() == ''){
                                $.ajax({
                                    type    : 'post',
                                    url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?camposGuion=true',
                                    data    : { guion : $("#hidId").val() },
                                    success : function(data){
                                        $("#G5_C31").html(data);
                                        $("#G5_C59").html(data);
                                        $("#G5_C31").attr('disabled', false);
                                        $("#G5_C59").attr('disabled', false);
                                   }
                                });
                            }
                            vamosRecargaLasGrillasPorfavor($("#hidId").val());
                        } 
                    }).set('labels', {ok:'Si', cancel:'No'});                    
                }else{
                    //Algo paso, hay un error
                    alertify.error('Un error ha ocurrido');
                }                
            },
            //si ha ocurrido un error
            error: function(){
                after_save_error();
                alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
            }
        });
    }


    /* guardar datos de las preguntas */
    function guardardatosSeccion(){

        //guardarDatos(true);
        var form = $("#formularioSecciones_form");
        //Se crean un array con los datos a enviar, apartir del formulario 
        var formData = new FormData($("#formularioSecciones_form")[0]);
        $.ajax({
            url: 'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php?insertarDatosSubgrilla_0=si',  
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data){
                if(data){
                    /* si guardo eso perfecto */
                    vamosRecargaLasGrillasPorfavor(idTotal);   
                    alertify.success("Se ha guardado la seccion");

                }else{
                    //Algo paso, hay un error
                    alertify.error('Un error ha ocurrido');
                }                
            },
            //si ha ocurrido un error
            error: function(){
                after_save_error();
                alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
            }
        });
    }

    //SECCION  : Manipular Lista de Navegacion

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(x){
        var tr = '';
        var url  = '<?=$url_crud;?>';
        <?php if(isset($_GET['tipo'])){ ?>
            var url  = '<?=$url_crud;?>?tipo=<?php echo $_GET['tipo'];?>';
        <?php } ?>
        $.ajax({
            url      : url,
            type     : 'POST',
            data     : { CallDatosJson : 'SI', Busqueda : x },
            dataType : 'json',
            success  : function(data){
                //Cargar la lista con los datos obtenidos en la consulta
                $.each(data, function(i, item) {

                    var tipo = '<?php echo strtoupper($str_Script______g_);?>';
                    
                    if(data[i].camp2 == 2){
                        tipo = '<?php echo strtoupper($str_Guion_______g_);?>';
                    }else if(data[i].camp2 == 3){
                        tipo = '<?php echo strtoupper($str_Otros_______g_);?>';
                    }

                    tr += "<tr class='CargarDatos' id='"+data[i].id+"'>";
                    tr += "<td>";
                    tr += "<p style='font-size:14px;'><b>"+data[i].camp1+"</b></p>";
                    tr += "<p style='font-size:12px; margin-top:-10px;'>"+tipo+"</p>";
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

    //poner en el formulario de la derecha los datos del registro seleccionado a la izquierda, funcionalidad de la lista de navegacion
    function busqueda_lista_navegacion(){

        $(".CargarDatos").click(function(){
            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');
              
            bool_estamosListos = false; 
            var id = $(this).attr('id');
            //buscar los datos
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $(".llamadores").attr("padre", id);

                    $.each(data, function(i, item) {
                    

                        $("#G5_C28").val(item.G5_C28);

                        $("#G5_C29").val(item.G5_C29);
 
                        $("#G5_C29").val(item.G5_C29).trigger("change"); 

                        $("#G5_C30").val(item.G5_C30);


                        if(item.G5_C29 == '2'){
                            if(item.encode != 'false'){
                                $("#rutaWebForm").attr('href', 'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/web_forms.php?web='+item.encode);
                                $("#rutaWebForm").html("http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/web_forms.php?web="+item.encode);
                            }
                        }else{
                            $("#rutaWebForm").attr('href', '');
                            $("#rutaWebForm").html("");
                        }

                        $("#vistaPrevia").attr('href' , 'http://<?php echo $_SERVER["HTTP_HOST"];?>/manager/preview.php?formulario='+item.encode_preview);

                        $("#generar").attr('disabled', false);

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '2'){ ?>
                        $("#idTitle").html('<?php echo $str_message_genera; ?>'+item.G5_C28);
                        <?php } ?>

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '1'){ ?>
                         $("#idTitle").html('<?php echo $str_message_generF; ?>'+item.G5_C28);   
                        <?php } ?>

                        <?php if(isset($_GET['tipo']) && $_GET['tipo'] == '3'){ ?>
                         $("#idTitle").html('<?php echo $str_message_generO; ?>'+item.G5_C28);
                        <?php } ?>

                        
                        $("#h3mio").html(item.principal);
                        
                        /* Obtener los campos de ese guion */
                        $.ajax({
                            type    : 'post',
                            url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?camposGuion=true',
                            data    : { guion : id },
                            success : function(data){
                                $("#G5_C31").html(data);
                                $("#G5_C59").html(data);
                                $("#G5_C31").val(item.G5_C31);
                                $("#G5_C31").val(item.G5_C31).trigger("change"); 
                                $("#G5_C59").val(item.G5_C59);
                                $("#G5_C59").val(item.G5_C59).trigger("change");
                            }
                        });


                        $.ajax({
                            url    : '<?php echo $url_crud;?>?dameConfiguraciones=true',
                            type   : 'post',
                            data   : { guion : id , idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'},
                            success : function(data){
                                $("#nuevosConfiguraciones").html(data);

                                $('#FormularioDatos :input').each(function(){
                                    $(this).attr('disabled', true);
                                });
                                
                                $('.my-colorpicker2').colorpicker();

                                $(".dleteTiposWebF").click(function(){
                                    idN = $(this).attr('id');
                                    fila = $(this).attr('fila');
                                    $.ajax({
                                        type    : 'post',
                                        url     : '<?php echo $url_crud;?>?deleteOptionWeb=true',
                                        data    : { id : idN },
                                        success : function(data){
                                            $("#idGen_"+fila).remove();
                                        }
                                    });
                                });

                                $(".optin2").change(function(){
                                    var x = $(this).attr('Xaja');
                                    if($(this).val() == 2){
                                        $("#cuenta_"+x).attr('disabled' , false);
                                        $("#txtAsunto_"+x).attr('disabled' , false);
                                        $("#txtCuerpoMensaje_"+x).attr('disabled' , false);
                                    }else{
                                        $("#cuenta_"+x).attr('disabled' , true);
                                        $("#txtAsunto_"+x).attr('disabled' , true);
                                        $("#txtCuerpoMensaje_"+x).attr('disabled' , true);
                                    }
                                });

                                $(".externa2").change(function(){
                                    var x = $(this).attr('Xaja');
                                    if($(this).val() == 2){
                                        $("#txtUrl_"+x).attr('disabled' , false);
                                        $("#txtCodigo_"+x).attr('disabled' , true);
                                    }else{
                                        $("#txtUrl_"+x).attr('disabled' , true);
                                        $("#txtCodigo_"+x).attr('disabled' , false);
                                    }
                                });

                            }
                        })
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
                    $("#generar").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });

            $.ajax({
                url      : '<?=$url_crud;?>?callDatosSecciones=true',
                type     : 'POST',
                data     : { 
                    id : id , 
                    idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                },
                dataType : 'html',
                success  : function(data){
                    $("#secciones_creadas").html(data);
                    $("#secciones_creadas").sortable({
                        axis: 'y',
                        update: function (event, ui) {
                            var data = $(this).sortable('serialize');
                            $("#txtOrdenData").val(data);
                        }
                    });
                    $(".btnEliminarSeccion").click(function(){
                        var idSeccion = $(this).attr('valorSeccion');
                        alertify.confirm("<?php echo $str_message_genericoDel; ?>", function(e){
                            if(e){
                                $.ajax({
                                    url      : 'cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php?insertarDatosGrilla=si',
                                    type     : 'POST',
                                    data     : { id : idSeccion , oper : 'del'},
                                    beforeSend : function(){
                                        $.blockUI({ 
                                            baseZ: 2000,
                                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                    },
                                    complete : function(){
                                        $.unblockUI();
                                    },
                                    success  : function(data){
                                        if(data == '0'){   
                                            alertify.success('<?php echo $str_Exito;?>');
                                            $("#seccion_"+idSeccion).remove();
                                        }else{
                                            alertify.error("<?php echo $error_de_proceso; ?>");
                                        }
                                    } 
                                });
                            }
                        });
                    });

                    $(".btnEditarSeccion").click(function(){
                        var id = $(this).attr('valorSeccion');
                        $.ajax({
                            url     :  'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php',
                            type    : 'post',
                            dataType: 'json',
                            data    : { getDatosSeccionesEdicion : true , id : id },
                            beforeSend : function(){
                                $.blockUI({ 
                                    baseZ: 2000,
                                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                            },
                            complete : function(){
                                $("#editarSeccionesModal").modal();
                                $.unblockUI();
                            },
                            success : function(data){
                                if(data.code == '1'){
                                    //console.log(data);
                                    $("#IdseccionEdicion").val(id);
                                    $("#EditarG7_C33").val(data.sec_nom);
                                    $("#EditarG7_C36").val(data.sec_Tip);
                                    $("#EditarG7_C36").val(data.sec_Tip).change();
                                    $("#EditarG7_C35").val(data.sec_num_col);
                                    if(data.sec_mini != '' && data.sec_mini != 0){

                                    }
                                    $("#EditarG7_C37").val(data.sec_mini);

                                    var cuerpoTablaQuesedebeGuardar = '';
                                    $("#cuerpoTablaQuesedebeGuardarEdicion").html('');

                                    $.each(data.datosPregun, function(campo , item){
                                        //alert(item.PREGUN_Texto_____b);

            cuerpoTablaQuesedebeGuardar = '<tr id="campos'+ campo +'">'+
                                    '<td style="width: 25%; text-align: center;"><input type="text" class="form-control input-sm textoMostrado" id="G6_C39_'+ campo +'" value=""  name="G6_C39_'+ campo +'"  placeholder="<?php echo $str_campo_texto___; ?>"></td>'+
                                    '<td style="width: 25%; text-align: center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_'+ campo +'" id="G6_C40_'+ campo +'" atrId = "'+ campo +'">'+
                                            '<?php
                                                echo '<option value="1">Texto</option>';
                                                echo '<option value="2">Memo</option>';
                                                echo '<option value="3">Numerico</option>';
                                                echo '<option value="4">Decimal</option>';
                                                echo '<option value="5">Fecha</option>';
                                                echo '<option value="10">Hora</option>';
                                                echo '<option value="6">Lista</option>';
                                                echo '<option value="11">Lista Compuesta</option>';
                                                echo '<option value="8">Casilla de verificación</option>';
                                                echo '<option value="9">Libreto / Label</option>';  
                                                echo '<option value="12">Maestro - Detalle</option>';   
                                                
                                            ?>'+
                                        '</select></td>'+
                                    '<td style="width: 10%; text-align: center;"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_'+ campo +'" id="G6_C44_'+ campo +'" cmpo = "'+ campo +'">'+ ListasBd +'</select><select disabled class="form-control input-sm str_Select2" style="width: 100%; display:none;"  name="G6_C43_'+ campo +'" id="G6_C43_'+ campo +'" cmpo = "'+ campo +'">'+ GuionesBD +'</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "'+ campo +'" id="nuevaListaModalH_'+ campo +'" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_'+ campo +'" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "'+ campo +'" class="pull-right">Editar Lista</a></td>'+                                    
                                    '<td style="width: 10%; text-align: center;"><label><input type="checkbox" value="-1" name="G6_C41_'+ campo +'" id="G6_C41_'+ campo +'"></label></td>'+
                                    '<td style="text-align:center;">'+
                                    '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="'+ campo +'" ><i class="fa fa-cog"></i></button>'+
                                    '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField"  title="<?php echo $str_campo_delet___;?>" valorField="'+ item.PREGUN_ConsInte__b +'" cmpo ="'+campo+'"><i class="fa fa-trash-o"></i></button></td>'+
                                    '<input type="hidden" name="G6_C43_'+ campo +'" id="G6_C43_'+ campo +'" value="0">'+
                                    '<input type="hidden" name="GuidetM_'+ campo +'" id="GuidetM_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="Guidet_'+ campo +'" id="Guidet_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="modoGuidet_'+ campo +'" id="modoGuidet_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C46_'+ campo +'" id="G6_C46_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C207_'+ campo +'" id="G6_C207_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C53_'+ campo +'" id="G6_C53_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C54_'+ campo +'" id="G6_C54_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C55_'+ campo +'" id="G6_C55_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C56_'+ campo +'" id="G6_C56_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C57_'+ campo +'" id="G6_C57_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C58_'+ campo +'" id="G6_C58_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C51_'+ campo +'" id="G6_C51_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C42_'+ campo +'" id="G6_C42_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C52_'+ campo +'" id="G6_C52_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="ConsInte_'+ campo +'" id="ConsInte_'+ campo +'"  value="0">'+

                                '</tr>';

                                        $("#cuerpoTablaQuesedebeGuardarEdicion").append(cuerpoTablaQuesedebeGuardar);
                                        $("#contadorEditablesPreguntas").val(campo);
                                        
                                        $("#G6_C39_"+ campo).val(item.PREGUN_Texto_____b);
                                        $("#ConsInte_"+campo).val(item.PREGUN_ConsInte__b);

                                        
                                        $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b);
                                        $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b).change();

                                        if(item.PREGUN_IndiBusc__b == '-1'){
                                            $("#G6_C41_"+ campo).attr('checked' , true);
                                        }else{
                                            $("#G6_C41_"+ campo).attr('checked' , false);
                                        }
                                           

                                       // console.log('This => '+item.PREGUN_ConsInte__OPCION_B);
                                        if(item.PREGUN_ConsInte__OPCION_B != null && item.PREGUN_ConsInte__OPCION_B != '' && item.PREGUN_ConsInte__OPCION_B != '0'){
                                            $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B);
                                            $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B).change();
                                            $("#G6_C44_"+campo).attr('disabled', false);
                                            $("#nuevaListaModalH_"+campo).show();
                                            $("#editarLista_"+campo).show();
                                        } 


                                        
                                        

                                        $("#G6_C44_"+campo).change(function(){
                                            var id = $(this).attr('cmpo');
                                            if($(this).val() != 0){
                                                $("#editarLista_"+id).show();
                                            }else{
                                                $("#editarLista_"+id).hide();
                                            }
                                        });

                                        $("#editarLista_"+campo).click(function(){
                                            var id = $(this).attr('cmpo');
                                            $("#agrupador")[0].reset();
                                            $("#opciones").html('');
                                            contador = 0;
                                            $("#hidListaInvocada").val(id);
                                            $.ajax({
                                                url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                                                type    : 'post',
                                                data    : { getListasEdit : true, idOpcion : $("#G6_C44_"+id).val() },
                                                dataType: 'json',
                                                beforeSend : function(){
                                                    $.blockUI({ 
                                                        baseZ: 2000,
                                                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                },
                                                complete : function(){
                                                    $.unblockUI();
                                                },
                                                success : function(data){
                                                    if(data.code == '1'){
                                                        $("#idListaE").val(data.id);
                                                        $("#txtNombreLista").val(data.opcion);
                                                        $.each(data.lista, function(i, items){
                                                            var cuerpo = "<div class='row' id='id_"+i+"'>";
                                                            cuerpo += "<div class='col-md-10'>";
                                                            cuerpo += "<div class='form-group'>";
                                                            cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                            cuerpo += "<div class='form-group'>";
                                                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "</div>";
                                                            cuerpo += "</div>";
                                                            $("#opciones").append(cuerpo);
                                                        });
                                                        $("#opciones").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                                                        $(".deleteopcionP").click(function(){
                                                            var id = $(this).attr('value');
                                                            var miId = $(this).attr('id');
                                                            alertify.confirm('<?php echo $str_deleteOptio__ ;?>' , function(e){
                                                                if(e){
                                                                    $.ajax({
                                                                        url   : '<?php echo $url_crud; ?>',
                                                                        type  : 'post',
                                                                        data  : { deleteOption: true, id : id},
                                                                        beforeSend : function(){
                                                                            $.blockUI({ 
                                                                                baseZ: 2000,
                                                                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                        },
                                                                        complete : function(){
                                                                            $.unblockUI();
                                                                        },
                                                                        success : function(data){
                                                                            if(data == '1'){
                                                                                alertify.success('<?php echo $str_Exito;?>');
                                                                                $("#id_"+miId).remove();
                                                                            }else{
                                                                                alertify.error('Error');
                                                                            }
                                                                        }
                                                                    })

                                                                }
                                                            });
                                                        });
                                                        contador = data.total;
                                                        //console.log(contador);
                                                        $("#operLista").val("edit");
                                                    }
                                                }
                                            });
                                   
                                        });

                                        $("#nuevaListaModalH_"+campo).click(function(){
                                            $("#agrupador")[0].reset();
                                            $("#opciones").html('');
                                            contador = 0;
                                            $("#hidListaInvocada").val($(this).attr('cmpo'));
                                            $("#operLista").val('add');
                                            $("#idListaE").val('0');
                                        });

                                        campo++;

                                    });
                                    
                                    $(".btnEliminarField").click(function(){
                                        var id = $(this).attr('valorField');
                                        var campo = $(this).attr('cmpo');
                                        alertify.confirm('<?php echo $str_deleteField___;?>', function(e){
                                                if(e){
                                                    $.ajax({
                                                        url   : '<?php echo $url_crud; ?>',
                                                        type  : 'post',
                                                        data  : { borraPreguntas: true, id : id},
                                                        beforeSend : function(){
                                                            $.blockUI({ 
                                                                baseZ: 2000,
                                                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                        },
                                                        complete : function(){
                                                            //$("#editarSeccionesModal").modal();
                                                            $.unblockUI();
                                                        },
                                                        success : function(data){
                                                            if(data == '1'){
                                                                alertify.success('<?php echo $str_Exito;?>');
                                                                $("#campos"+campo).remove();
                                                            }else{
                                                                alertify.error('Error');
                                                            }
                                                        }
                                                    })

                                                }
                                            }
                                        );   
                                    });

                                    $(".str_SelectTipo").change(function(){
                                        var id = $(this).attr('atrId');
                                        if($(this).val() == 6){
                                            $("#G6_C44_"+id).attr('disabled', false);
                                            $("#nuevaListaModalH_"+id).show();
                                            $("#G6_C43_"+id).attr('disabled', true); 
                                            $("#G6_C43_"+id).hide(); 
                                            $("#G6_C44_"+id).show();

                                        }else if($(this).val() == 11) {

                                            $("#G6_C43_"+id).attr('disabled', false);
                                            $("#G6_C43_"+id).show(); 
                                            $("#G6_C44_"+id).attr('disabled', true);
                                            $("#G6_C44_"+id).hide();
                                            $("#nuevaListaModalH_"+id).hide();
                                            $("#editarLista_"+id).hide();

                                        }else{

                                            $("#G6_C44_"+id).attr('disabled', true);
                                            $("#nuevaListaModalH_"+id).hide();

                                            $("#G6_C43_"+id).attr('disabled', true); 
                                            $("#G6_C43_"+id).hide();
                                            $("#G6_C44_"+id).show();
                                        }
                                    });
                                    
                                }else{
                                    alertify.error('La seccion no existe');
                                }
                            }
                        });
                    });
                }
            })

            $("#hidId").val(id);

            idTotal = $("#hidId").val();

            $("#id_guion").val(idTotal);

        });
    }


    function seleccionar_registro(){
        //Seleccinar loos registros de la Lista de navegacion, 
        if ( $("#"+idTotal).length > 0) {
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
                    

                        $("#G5_C28").val(item.G5_C28);

                        $("#G5_C29").val(item.G5_C29);
 
                        $("#G5_C29").val(item.G5_C29).trigger("change"); 

                        $("#G5_C30").val(item.G5_C30);

                        $("#G5_C31").val(item.G5_C31);
 
                        $("#G5_C31").val(item.G5_C31).trigger("change"); 

                        $("#G5_C59").val(item.G5_C59);
 
                        $("#G5_C59").val(item.G5_C59).trigger("change"); 
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

    function vamosRecargaLasGrillasPorfavor(id){
        $.ajax({
            type    : 'post',
            url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?camposGuion_incude_id=true',
            data    : { guion : id },
            success : function(data){
                $("#GuidetM").html(data);
            }
        });
    }

    function poner_botones_diabled(){
        $("#add_seccion").attr('disabled', false);
        $("#edit_seccion").attr('disabled', false);
        $("#delete_seccion").attr('disabled', false);    

        //Habilitar los botones que se pueden usar, guardar y seleccionar_registro
        $("#cancel_seccion").attr('disabled', true);   
        $("#Save_seccion").attr('disabled', true);
    }

    function before_save_Guion(){ 
        var valid = true
        
        if($("#G5_C28").val().length < 1){
            alertify.error("<?php echo $str_error_nombr_g_;?>");
            valid = false;
        }

        if($("#G5_C31").val() == 0){
            alertify.error("<?php echo $str_message__cP_g_;?>");
            valid = false;
        }

        if($("#G5_C59").val() == 0){
            alertify.error("<?php echo $str_message__cS_g_;?>");
            valid = false;   
        }

        return valid;
    }

</script>
<!-- Script para usar la funiconalidad del modal de guiones -->
<script type="text/javascript">
    $(function(){


       /* $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });*/

        $('#GenerarFromExel').change(function(){
            if($(this).is(':checked')){
                $(".excel").show();
                $("#CrearScript").prop('checked', true);
                <?php if(isset($_GET['tipo']) && $_GET['tipo'] != 1){ ?>
                //$(".excel2").show();
                <?php } ?>
            }else{
                $(".excel").hide();
                $(".excel2").hide();
                $("#CrearScript").prop('checked', false);
                $("#newGuionFile").val('');
                // $("#ImportarFromExel").iCheck('uncheck');
                $("#ImportarFromExel").prop('checked',false);
            }
        })

        /*$('#GenerarFromExel').on('ifChecked', function () { 
            $(".excel").show();
        });


        $('#GenerarFromExel').on('ifUnchecked', function () { 
            $(".excel").hide();
            $("#newGuionFile").val('');
           // $("#ImportarFromExel").iCheck('uncheck');
            $("#ImportarFromExel").prop('checked',false);
        });*/


        $('#newGuionFile').on('change', function(e){
            /* primero validar que sea solo excel */
            var imagen = this.files[0];
            //console.log(imagen);
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


        $("#cancelarModal").click(function(){
            $("#cancel").click();
        });

        $("#guardarNewGuion").click(function(){
            var valido = 0;
            if($("#txtNombreGuion").val().length < 1){
                valido = 1;
                $("#txtNombreGuion").focus();
                alertify.error("<?php echo $str_error_nombr_g_; ?>");
            }

            if($("#comboSelectTipo").val() == 0){
                valido = 1;
                $("#comboSelectTipo").focus();
                alertify.error("<?php echo $str_error_tipo__g_; ?>");
            }

            if(valido == 0){
                var form = $("#nuevoGuion");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#nuevoGuion")[0]);
                formData.append('oper', 'add');
                formData.append('G5_C31', '0');
                formData.append('G5_C59', '0');
                $.ajax({
                   url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data != '-1' && data != '-2'){
                            data = jQuery.parseJSON(data);
                            $.ajax({
                                url      : '<?=$url_crud;?>',
                                type     : 'POST',
                                data     : { CallDatos : 'SI', id : data.code},
                                dataType : 'json',
                                success  : function(data){
                                    //recorrer datos y enviarlos al formulario
                                    $.each(data, function(i, item) {
                                        

                                        $("#G5_C28").val(item.G5_C28);

                                        $("#G5_C29").val(item.G5_C29);

                                        $("#G5_C29").val(item.G5_C29).trigger("change"); 

                                        $("#G5_C30").val(item.G5_C30);

                                        $("#G5_C31").val(item.G5_C31);

                                        $("#G5_C31").val(item.G5_C31).trigger("change"); 

                                        $("#G5_C59").val(item.G5_C59);

                                        $("#G5_C59").val(item.G5_C59).trigger("change"); 
                                        
                                        $("#h3mio").html(item.principal);

                                        var tipo = '<?php echo strtoupper($str_Script______g_);?>';

                                        if(item.G5_C29 == 2){

                                            tipo = '<?php echo strtoupper($str_Guion_______g_);?>';

                                        }else if(item.G5_C29 == 3){

                                            tipo = '<?php echo strtoupper($str_Otros_______g_);?>';
                                        }

                                        idTotal = item.G5_ConsInte__b;

                                        $("#hidId").val(idTotal);
                                        
                                        $("#id_guion").val(idTotal);

                                        $(".CargarDatos").each(function(){
                                            $(this).removeClass('active');
                                        });

                                        var tr = "<tr class='CargarDatos active' id='"+item.G5_ConsInte__b+"'>"+
                                                "<td>"+
                                                    "<p style='font-size:14px;'><b>"+ item.G5_C28.toUpperCase() + "</b></p>"+
                                                   "<p style='font-size:12px; margin-top:-10px;'>"+ tipo + "</p>"+
                                                "</td>"+
                                            "</tr>";

                                        $("#tablaScroll").prepend(tr);                               

                                        busqueda_lista_navegacion();       


                                        $.ajax({
                                            url      : '<?=$url_crud;?>?callDatosSecciones=true',
                                            type     : 'POST',
                                            data     : { 
                                                id : idTotal , 
                                                idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                                            },
                                            dataType : 'html',
                                            success  : function(data){
                                                $("#secciones_creadas").html(data);
                                                $("#secciones_creadas").sortable({
                                                    axis: 'y',
                                                    update: function (event, ui) {
                                                        var data = $(this).sortable('serialize');
                                                        $("#txtOrdenData").val(data);
                                                    }
                                                });
                                                $(".btnEliminarSeccion").click(function(){
                                                    var idSeccion = $(this).attr('valorSeccion');
                                                    alertify.confirm("<?php echo $str_message_genericoDel; ?>", function(e){
                                                        if(e){
                                                            $.ajax({
                                                                url      : 'cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php?insertarDatosGrilla=si',
                                                                type     : 'POST',
                                                                data     : { id : idSeccion , oper : 'del'},
                                                                beforeSend : function(){
                                                                    $.blockUI({ 
                                                                        baseZ: 2000,
                                                                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                },
                                                                complete : function(){
                                                                    $.unblockUI();
                                                                },
                                                                success  : function(data){
                                                                    if(data == '0'){   
                                                                        alertify.success('<?php echo $str_Exito;?>');
                                                                        $("#seccion_"+idSeccion).remove();
                                                                    }else{
                                                                        alertify.error("<?php echo $error_de_proceso; ?>");
                                                                    }
                                                                } 
                                                            });
                                                        }
                                                    });
                                                });

                                                $(".btnEditarSeccion").click(function(){
                                                    var id = $(this).attr('valorSeccion');
                                                    $.ajax({
                                                        url     :  'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php',
                                                        type    : 'post',
                                                        dataType: 'json',
                                                        data    : { getDatosSeccionesEdicion : true , id : id },
                                                        beforeSend : function(){
                                                            $.blockUI({ 
                                                                baseZ: 2000,
                                                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                        },
                                                        complete : function(){
                                                            $("#editarSeccionesModal").modal();
                                                            $.unblockUI();
                                                        },
                                                        success : function(data){
                                                            if(data.code == '1'){
                                                                //console.log(data);
                                                                $("#IdseccionEdicion").val(id);
                                                                $("#EditarG7_C33").val(data.sec_nom);
                                                                $("#EditarG7_C36").val(data.sec_Tip);
                                                                $("#EditarG7_C36").val(data.sec_Tip).change();
                                                                $("#EditarG7_C35").val(data.sec_num_col);
                                                                $("#EditarG7_C37").val(data.sec_mini);

                                                                var cuerpoTablaQuesedebeGuardar = '';
                                                                $("#cuerpoTablaQuesedebeGuardarEdicion").html('');

                                                                $.each(data.datosPregun, function(campo , item){
                                                                    //alert(item.PREGUN_Texto_____b);

                                        cuerpoTablaQuesedebeGuardar = '<tr id="campos'+ campo +'">'+
                                                                '<td style="width: 25%; text-align: center;"><input type="text" class="form-control input-sm textoMostrado" id="G6_C39_'+ campo +'" value=""  name="G6_C39_'+ campo +'"  placeholder="<?php echo $str_campo_texto___; ?>"></td>'+
                                                                '<td style="width: 25%; text-align: center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_'+ campo +'" id="G6_C40_'+ campo +'" atrId = "'+ campo +'">'+
                                                                        '<?php
                                                                            echo '<option value="1">Texto</option>';
                                                                            echo '<option value="2">Memo</option>';
                                                                            echo '<option value="3">Numerico</option>';
                                                                            echo '<option value="4">Decimal</option>';
                                                                            echo '<option value="5">Fecha</option>';
                                                                            echo '<option value="10">Hora</option>';
                                                                            echo '<option value="6">Lista</option>';
                                                                            echo '<option value="11">Lista Compuesta</option>';
                                                                            echo '<option value="8">Casilla de verificación</option>';
                                                                            echo '<option value="9">Libreto / Label</option>';  
                                                                            echo '<option value="12">Maestro - Detalle</option>';   
                                                                            
                                                                        ?>'+
                                                                    '</select></td>'+
                                                                '<td style="width: 10%; text-align: center;"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_'+ campo +'" id="G6_C44_'+ campo +'" cmpo = "'+ campo +'">'+ ListasBd +'</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "'+ campo +'" id="nuevaListaModalH_'+ campo +'" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_'+ campo +'" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "'+ campo +'" class="pull-right">Editar Lista</a></td>'+                                    
                                                                '<td style="width: 10%; text-align: center;"><label><input type="checkbox" value="-1" name="G6_C41_'+ campo +'" id="G6_C41_'+ campo +'"></label></td>'+
                                                                '<td style="text-align:center;">'+
                                                                '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="'+ campo +'" ><i class="fa fa-cog"></i></button>'+
                                                                '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField"  title="<?php echo $str_campo_delet___;?>" valorField="'+ item.PREGUN_ConsInte__b +'" cmpo ="'+campo+'"><i class="fa fa-trash-o"></i></button></td>'+
                                                                '<input type="hidden" name="G6_C43_'+ campo +'" id="G6_C43_'+ campo +'" value="0">'+
                                                                '<input type="hidden" name="GuidetM_'+ campo +'" id="GuidetM_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="Guidet_'+ campo +'" id="Guidet_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="modoGuidet_'+ campo +'" id="modoGuidet_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C46_'+ campo +'" id="G6_C46_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C207_'+ campo +'" id="G6_C207_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C53_'+ campo +'" id="G6_C53_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C54_'+ campo +'" id="G6_C54_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C55_'+ campo +'" id="G6_C55_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C56_'+ campo +'" id="G6_C56_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C57_'+ campo +'" id="G6_C57_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C58_'+ campo +'" id="G6_C58_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C51_'+ campo +'" id="G6_C51_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C42_'+ campo +'" id="G6_C42_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="G6_C52_'+ campo +'" id="G6_C52_'+ campo +'"  value="0">'+
                                                                '<input type="hidden" name="ConsInte_'+ campo +'" id="ConsInte_'+ campo +'"  value="0">'+

                                                            '</tr>';

                                                                    $("#cuerpoTablaQuesedebeGuardarEdicion").append(cuerpoTablaQuesedebeGuardar);
                                                                    $("#contadorEditablesPreguntas").val(campo);
                                                                    
                                                                    $("#G6_C39_"+ campo).val(item.PREGUN_Texto_____b);
                                                                    $("#ConsInte_"+campo).val(item.PREGUN_ConsInte__b);

                                                                    
                                                                    $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b);
                                                                    $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b).change();

                                                                    if(item.PREGUN_IndiBusc__b == '-1'){
                                                                        $("#G6_C41_"+ campo).attr('checked' , true);
                                                                    }else{
                                                                        $("#G6_C41_"+ campo).attr('checked' , false);
                                                                    }
                                                                       
                                                                    if(item.PREGUN_ConsInte__OPCION_B != null && item.PREGUN_ConsInte__OPCION_B != ''){
                                                                        $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B);
                                                                        $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B).change();
                                                                        $("#G6_C44_"+campo).attr('disabled', false);
                                                                        $("#nuevaListaModalH_"+campo).show();
                                                                        $("#editarLista_"+campo).show();
                                                                    }          
                                                                    
                                                                    

                                                                    $("#G6_C44_"+campo).change(function(){
                                                                        var id = $(this).attr('cmpo');
                                                                        if($(this).val() != 0){
                                                                            $("#editarLista_"+id).show();
                                                                        }else{
                                                                            $("#editarLista_"+id).hide();
                                                                        }
                                                                    });

                                                                    $("#editarLista_"+campo).click(function(){
                                                                        var id = $(this).attr('cmpo');
                                                                        $("#agrupador")[0].reset();
                                                                        $("#opciones").html('');
                                                                        contador = 0;
                                                                        $("#hidListaInvocada").val(id);
                                                                        $.ajax({
                                                                            url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                                                                            type    : 'post',
                                                                            data    : { getListasEdit : true, idOpcion : $("#G6_C44_"+id).val() },
                                                                            dataType: 'json',
                                                                            beforeSend : function(){
                                                                                $.blockUI({ 
                                                                                    baseZ: 2000,
                                                                                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                            },
                                                                            complete : function(){
                                                                                $.unblockUI();
                                                                            },
                                                                            success : function(data){
                                                                                if(data.code == '1'){
                                                                                    $("#idListaE").val(data.id);
                                                                                    $("#txtNombreLista").val(data.opcion);
                                                                                    $.each(data.lista, function(i, items){
                                                                                        var cuerpo = "<div class='row' id='id_"+i+"'>";
                                                                                        cuerpo += "<div class='col-md-10'>";
                                                                                        cuerpo += "<div class='form-group'>";
                                                                                        cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                                                                                        cuerpo += "</div>";
                                                                                        cuerpo += "</div>";
                                                                                        cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                                                        cuerpo += "<div class='form-group'>";
                                                                                        cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                                                                                        cuerpo += "</div>";
                                                                                        cuerpo += "</div>";
                                                                                        cuerpo += "</div>";
                                                                                        $("#opciones").append(cuerpo);
                                                                                    });
                                                                                    $("#opciones").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                                            $(".deleteopcionP").click(function(){
                                                var id = $(this).attr('value');
                                                var miId = $(this).attr('id');
                                                alertify.confirm('<?php echo $str_deleteOptio__ ;?>' , function(e){
                                                    if(e){
                                                        $.ajax({
                                                            url   : '<?php echo $url_crud; ?>',
                                                            type  : 'post',
                                                            data  : { deleteOption: true, id : id},
                                                            beforeSend : function(){
                                                                $.blockUI({ 
                                                                    baseZ: 2000,
                                                                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                            },
                                                            complete : function(){
                                                                $.unblockUI();
                                                            },
                                                            success : function(data){
                                                                if(data == '1'){
                                                                    alertify.success('<?php echo $str_Exito;?>');
                                                                    $("#id_"+miId).remove();
                                                                }else{
                                                                    alertify.error('Error');
                                                                }
                                                            }
                                                        })

                                                    }
                                                });
                                            });
                                                                                    contador = data.total;
                                                                                    //console.log(contador);
                                                                                    $("#operLista").val("edit");
                                                                                }
                                                                            }
                                                                        });
                                                               
                                                                    });

                                                                    $("#nuevaListaModalH_"+campo).click(function(){
                                                                        $("#agrupador")[0].reset();
                                                                        $("#opciones").html('');
                                                                        contador = 0;
                                                                        $("#hidListaInvocada").val($(this).attr('cmpo'));
                                                                        $("#operLista").val('add');
                                                                        $("#idListaE").val('0');
                                                                    });

                                                                    campo++;

                                                                });
                                                                
                                                                $(".btnEliminarField").click(function(){
                                                                    var id = $(this).attr('valorField');
                                                                    var campo = $(this).attr('cmpo');
                                                                    alertify.confirm('<?php echo $str_deleteField___;?>', function(e){
                                                                            if(e){
                                                                                $.ajax({
                                                                                    url   : '<?php echo $url_crud; ?>',
                                                                                    type  : 'post',
                                                                                    data  : { borraPreguntas: true, id : id},
                                                                                    beforeSend : function(){
                                                                                        $.blockUI({ 
                                                                                            baseZ: 2000,
                                                                                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                                    },
                                                                                    complete : function(){
                                                                                        //$("#editarSeccionesModal").modal();
                                                                                        $.unblockUI();
                                                                                    },
                                                                                    success : function(data){
                                                                                        if(data == '1'){
                                                                                            alertify.success('<?php echo $str_Exito;?>');
                                                                                            $("#campos"+campo).remove();
                                                                                        }else{
                                                                                            alertify.error('Error');
                                                                                        }
                                                                                    }
                                                                                })

                                                                            }
                                                                        }
                                                                    );   
                                                                });

                                                                $(".str_SelectTipo").change(function(){
                                                                    var id = $(this).attr('atrId');
                                                                    if($(this).val() == 6){
                                                                        $("#G6_C44_"+id).attr('disabled', false);
                                                                        $("#nuevaListaModalH_"+id).show();
                                                                    }else{
                                                                        $("#G6_C44_"+id).attr('disabled', true);
                                                                        $("#nuevaListaModalH_"+id).hide();
                                                                    }
                                                                });
                                                                
                                                            }else{
                                                                alertify.error('La seccion no existe');
                                                            }
                                                        }
                                                    });
                                                });
                                            }
                                        })  

                                        //vamosRecargaLasGrillasPorfavor(item.G5_ConsInte__b);

                                        //alertify.success('<?php echo $str_message_succe3; ?>');
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
                            $("#hidId").val(data);

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
                        
                        }else{
                            if(data == '-2'){
                                alertify.error('<?php echo $str_message_G_cre1; ?>');
                            }else{
                                //Algo paso, hay un error
                                alertify.error('Un error ha ocurrido');    
                            }
                            
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    }
                });
            }
        });
    });
</script>

<script type="text/javascript">
    $(function(){
        $("#btnAddMoreConfig").click(function(){
            var cuerpo = "<div class='row' id='id_"+ cuantosVan2 +"'>";
            
            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' class='form-control' placeholder='<?php echo $str_title_config__;?>' name='txtTitulo_"+ cuantosVan2 +"'>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            
            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<div class='input-group my-colorpicker2'>";
            cuerpo += "<input type='text' class='form-control' placeholder='<?php echo $str_colorF_config_;?>' name='txtColor_"+ cuantosVan2 +"' id='txtColor_"+ cuantosVan2 +"'>";
            cuerpo += "<div class='input-group-addon'>";
            cuerpo += "<i></i>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            
            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<div class='input-group my-colorpicker2'>";
            cuerpo += "<input type='text' class='form-control' placeholder='<?php echo $str_colorL_config_;?>' name='txtColorLetra_"+ cuantosVan2 +"' id='txtColorLetra_"+ cuantosVan2 +"'>";
            cuerpo += "<div class='input-group-addon'>";
            cuerpo += "<i></i>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='file' class='form-control' name='txtfile_"+ cuantosVan2 +"'>";
            cuerpo += "</div>";
            cuerpo += "</div>";


            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select class='form-control optin' name='optin_"+ cuantosVan2 +"' Xaja='"+ cuantosVan2 +"'>";
            cuerpo += "<option value='0'><?php echo $str_optin_config__;?></option>";
            cuerpo += "<option value='1'><?php echo $str_simple_row____;?></option>";
            cuerpo += "<option value='2'><?php echo $str_doble_________;?></option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</div>";


            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select class='form-control' disabled name='cuenta_"+ cuantosVan2 +"' id='cuenta_"+ cuantosVan2 +"'>";
            cuerpo += "<option>Seleccione cuenta</option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</div>";


            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' class='form-control' name='txtAsunto_"+ cuantosVan2 +"'  id='txtAsunto_"+ cuantosVan2 +"' placeholder='<?php echo $str_asunto________;?>'>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select type='text' disabled class='form-control' name='cmbCampoCorreo_"+ cuantosVan2 +"'  id='cmbCampoCorreo_"+ cuantosVan2 +"'>";
            cuerpo += "<option>Campo correo</option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-12'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<textarea style='overflow:auto;resize:none' class='form-control' name='txtCuerpoMensaje_"+ cuantosVan2 +"'  id='txtCuerpoMensaje_"+ cuantosVan2 +"' placeholder='<?php echo $str_cuerpo________;?>'></textarea>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-4'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select class='form-control externa' name='extanas_"+ cuantosVan2 +"' Xaja='"+ cuantosVan2 +"'>";
            cuerpo += "<option value='0'><?php echo $str_url_salida____;?></option>";
            cuerpo += "<option value='1'><?php echo $str_externa_______;?></option>";
            cuerpo += "<option value='2'><?php echo $str_interna_row___;?></option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' readonly  class='form-control' placeholder='<?php echo $str_Url_config____;?>' name='txtUrl_"+ cuantosVan2 +"' id='txtUrl_"+ cuantosVan2 +"'>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' class='form-control' placeholder='<?php echo $str_texto_config__;?>' name='txtCodigo_"+ cuantosVan2 +"'  id='txtCodigo_"+ cuantosVan2 +"'>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            

            cuerpo += "<div class='col-md-2'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<button class='btn btn-sm btn-danger dleteTiposWeb' type='button' id='"+ cuantosVan2 +"' title='<?php echo $str_delete_row____;?>'><i class='fa fa-trash'></i></button>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<input type='hidden' name='generado_"+ cuantosVan2 +"' value='"+ cuantosVan2 +"'>";

            cuerpo += "</div><hr/>";

            $("#nuevosConfiguraciones").append(cuerpo);

            $(".dleteTiposWeb").click(function(){
                var id = $(this).attr('id');
                $("#id_"+id).remove();
            });

            $('.my-colorpicker2').colorpicker();

            $(".optin").change(function(){
                var x = $(this).attr('Xaja');
                if($(this).val() != 0){
                    $.ajax({
                        url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?traerCuentas=true',
                        type    : 'post',
                        datatype:'html',
                        success : function(data){
                            $("#cuenta_"+x).html(data);
                            $("#cuenta_"+x).attr('disabled', false);
                        }
                    });

                    $.ajax({
                        url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?camposcorreo=true',
                        type    : 'post',
                        data    : { guion : $("#hidId").val()}, 
                        datatype:'html',
                        success : function(data){
                            $("#cmbCampoCorreo_"+x).html(data);
                            $("#cmbCampoCorreo_"+x).attr('disabled', false);
                        }
                        
                    });    
                }else{

                    $("#cmbCampoCorreo_"+x).attr('disabled', true);
                    $("#cuenta_"+x).attr('disabled', true);
                }
                
            });

            $(".externa").change(function(){
                var x = $(this).attr('Xaja');
                if($(this).val() == 1){
                    $("#txtUrl_"+x).attr('readonly' , false);
                    $("#txtCodigo_"+x).attr('readonly' , true);
                }else{
                    $("#txtUrl_"+x).attr('readonly' , true);
                    $("#txtCodigo_"+x).attr('readonly' , false);
                }
            });

            
            cuantosVan2++;
            newContador++;
        });
    });
</script>

<script type="text/javascript">
    $(function(){
        
        $("#G7_C35").numeric();
        
        $("#G7_C36").change(function(){
            if($(this).val() == '3'){
                $("#cerradoAcordeon").show();
                //$("#G7_C37").attr('checked', true);
            }else{
                $("#cerradoAcordeon").hide();
                $("#G7_C37").attr('checked', false);
            }
        });

        $("#EditarG7_C36").change(function(){
            if($(this).val() == '3'){
                $("#EditarcerradoAcordeon").show();
                //$("#G7_C37").attr('checked', true);
            }else{
                $("#EditarcerradoAcordeon").hide();
                $("#EditarG7_C37").attr('checked', false);
            }
        });

        
        var iseccion = 1;
        iseccion = $("#secciones_creadas").length;
        if(iseccion == 0){
            iseccion = 1;
        }

        $("#btnAddMoreSeccion").click(function(){
            $("#crearSeccionesModal").modal();
        });

        
        var campo = 1;
        $("#newFieldSeccion").click(function(){
            var cuerpoTablaQuesedebeGuardar = '';
            cuerpoTablaQuesedebeGuardar = '<tr id="campos'+ campo +'">'+
                                    '<td style="width: 25%; text-align: center;"><input type="text" class="form-control input-sm textoMostrado" id="G6_C39_'+ campo +'" value=""  name="G6_C39_'+ campo +'"  placeholder="<?php echo $str_campo_texto___; ?>"></td>'+
                                    '<td style="width: 25%; text-align: center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_'+ campo +'" id="G6_C40_'+ campo +'" atrId = "'+ campo +'">'+
                                            '<?php
                                                echo '<option value="1">Texto</option>';
                                                echo '<option value="2">Memo</option>';
                                                echo '<option value="3">Numerico</option>';
                                                echo '<option value="4">Decimal</option>';
                                                echo '<option value="5">Fecha</option>';
                                                echo '<option value="10">Hora</option>';
                                                echo '<option value="6">Lista</option>';
                                                echo '<option value="11">Lista compuesta</option>';
                                                echo '<option value="8">Casilla de verificación</option>';
                                                echo '<option value="9">Libreto / Label</option>';  
                                                echo '<option value="12">Maestro - Detalle</option>';   
                                                
                                            ?>'+
                                        '</select></td>'+
                                    '<td style="width: 10%; text-align: center;"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_'+ campo +'" id="G6_C44_'+ campo +'" cmpo = "'+ campo +'">'+ ListasBd +'</select><select disabled class="form-control input-sm str_Select2" style="width: 100%; display:none;"  name="G6_C43_'+ campo +'" id="G6_C43_'+ campo +'" cmpo = "'+ campo +'">'+ GuionesBD +'</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "'+ campo +'" id="nuevaListaModalH_'+ campo +'" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_'+ campo +'" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "'+ campo +'" class="pull-right">Editar Lista</a></td>'+                                    
                                    '<td style="width: 10%; text-align: center;"><label><input type="checkbox" value="-1" name="G6_C41_'+ campo +'" id="G6_C41_'+ campo +'"></label></td>'+
                                    '<td style="text-align:center;">'+
                                    '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="'+ campo +'" ><i class="fa fa-cog"></i></button>'+
                                    '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField" id="btnEliminarField_'+ campo +'" title="<?php echo $str_campo_delet___;?>" valorField="'+ campo +'" ><i class="fa fa-trash-o"></i></button></td>'+
                                    '<input type="hidden" name="GuidetM_'+ campo +'" id="GuidetM_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="Guidet_'+ campo +'" id="Guidet_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="modoGuidet_'+ campo +'" id="modoGuidet_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C46_'+ campo +'" id="G6_C46_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C207_'+ campo +'" id="G6_C207_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C53_'+ campo +'" id="G6_C53_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C54_'+ campo +'" id="G6_C54_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C55_'+ campo +'" id="G6_C55_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C56_'+ campo +'" id="G6_C56_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C57_'+ campo +'" id="G6_C57_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C58_'+ campo +'" id="G6_C58_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C51_'+ campo +'" id="G6_C51_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C42_'+ campo +'" id="G6_C42_'+ campo +'"  value="0">'+
                                    '<input type="hidden" name="G6_C52_'+ campo +'" id="G6_C52_'+ campo +'"  value="0">'+

                                '</tr>';

            $("#cuerpoTablaQuesedebeGuardar").append(cuerpoTablaQuesedebeGuardar);

            $(".str_SelectTipo").change(function(){
                var id = $(this).attr('atrId');
                if($(this).val() == 6){
                    $("#G6_C44_"+id).attr('disabled', false);
                    $("#nuevaListaModalH_"+id).show();
                    $("#G6_C43_"+id).attr('disabled', true); 
                    $("#G6_C43_"+id).hide(); 
                    $("#G6_C44_"+id).show();

                }else if($(this).val() == 11) {

                    $("#G6_C43_"+id).attr('disabled', false);
                    $("#G6_C43_"+id).show(); 
                    $("#G6_C44_"+id).attr('disabled', true);
                    $("#G6_C44_"+id).hide();
                    $("#nuevaListaModalH_"+id).hide();
                    $("#editarLista_"+id).hide();

                }else{

                    $("#G6_C44_"+id).attr('disabled', true);
                    $("#nuevaListaModalH_"+id).hide();

                    $("#G6_C43_"+id).attr('disabled', true); 
                    $("#G6_C43_"+id).hide();
                    $("#G6_C44_"+id).show();
                }

            });


            $("#btnEliminarField_"+campo).click(function(){
                var id = $(this).attr('valorField');
                $("#campos"+id).remove();
            });

            //$("#G6_C44_"+campo).select2();

            $("#G6_C44_"+campo).change(function(){
                var id = $(this).attr('cmpo');
                if($(this).val() != 0){
                    $("#editarLista_"+id).show();
                }else{
                    $("#editarLista_"+id).hide();
                }
            });

            $("#editarLista_"+campo).click(function(){
                var id = $(this).attr('cmpo');
                $("#agrupador")[0].reset();
                $("#opciones").html('');
                contador = 0;
                $("#hidListaInvocada").val(id);
                $.ajax({
                    url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                    type    : 'post',
                    data    : { getListasEdit : true, idOpcion : $("#G6_C44_"+id).val() },
                    dataType: 'json',
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success : function(data){
                        if(data.code == '1'){
                            $("#idListaE").val(data.id);
                            $("#txtNombreLista").val(data.opcion);
                            $.each(data.lista, function(i, items){
                                var cuerpo = "<div class='row' id='id_"+i+"'>";
                                cuerpo += "<div class='col-md-10'>";
                                cuerpo += "<div class='form-group'>";
                                cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                                cuerpo += "</div>";
                                cuerpo += "</div>";
                                cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                cuerpo += "<div class='form-group'>";
                                cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                                cuerpo += "</div>";
                                cuerpo += "</div>";
                                cuerpo += "</div>";
                                $("#opciones").append(cuerpo);
                            });
                            $("#opciones").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                            contador = data.total;
                            console.log(contador);
                            $("#operLista").val("edit");

                            $(".deleteopcionP").click(function(){
                                var id = $(this).attr('value');
                                var miId = $(this).attr('id');
                                alertify.confirm('<?php echo $str_deleteOptio__ ;?>' , function(e){
                                    if(e){
                                        $.ajax({
                                            url   : '<?php echo $url_crud; ?>',
                                            type  : 'post',
                                            data  : { deleteOption: true, id : id},
                                            beforeSend : function(){
                                                $.blockUI({ 
                                                    baseZ: 2000,
                                                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                            },
                                            complete : function(){
                                                $.unblockUI();
                                            },
                                            success : function(data){
                                                if(data == '1'){
                                                    alertify.success('<?php echo $str_Exito;?>');
                                                    $("#id_"+miId).remove();
                                                }else{
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
       
            });

            $("#nuevaListaModalH_"+campo).click(function(){
                $("#agrupador")[0].reset();
                $("#opciones").html('');
                contador = 0;
                $("#hidListaInvocada").val($(this).attr('cmpo'));
                $("#operLista").val('add');
                $("#idListaE").val('0');
            });

            campo++;
        });
    
        
        $("#newFieldSeccion2").click(function(){
            editaCampos = $("#contadorEditablesPreguntas").val();

            editaCampos = (Number(editaCampos) + 1);

            //alert(editaCampos);
            var cuerpoTablaQuesedebeGuardar = '';
            cuerpoTablaQuesedebeGuardar = '<tr id="campos'+ editaCampos +'">'+
                                    '<td style="width: 25%; text-align: center;"><input type="text" class="form-control input-sm textoMostrado" id="G6_C39_'+ editaCampos +'" value=""  name="G6_C39_'+ editaCampos +'"  placeholder="<?php echo $str_campo_texto___; ?>"></td>'+
                                    '<td style="width: 25%; text-align: center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_'+ editaCampos +'" id="G6_C40_'+ editaCampos +'" atrId = "'+ editaCampos +'">'+
                                            '<?php
                                                echo '<option value="1">Texto</option>';
                                                echo '<option value="2">Memo</option>';
                                                echo '<option value="3">Numerico</option>';
                                                echo '<option value="4">Decimal</option>';
                                                echo '<option value="5">Fecha</option>';
                                                echo '<option value="10">Hora</option>';
                                                echo '<option value="6">Lista</option>';
                                                echo '<option value="11">Lista Compuesta</option>';
                                                echo '<option value="8">Casilla de verificación</option>';
                                                echo '<option value="9">Libreto / Label</option>';  
                                                echo '<option value="12">Maestro - Detalle</option>';   
                                                
                                            ?>'+
                                        '</select></td>'+
                                    '<td style="width: 10%; text-align: center;"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_'+ editaCampos +'" id="G6_C44_'+ editaCampos +'" cmpo = "'+ editaCampos +'">'+ ListasBd +'</select><select disabled class="form-control input-sm str_Select2" style="width: 100%; display:none;"  name="G6_C43_'+ editaCampos +'" id="G6_C43_'+ editaCampos +'" cmpo = "'+ editaCampos +'">'+ GuionesBD +'</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "'+ editaCampos +'" id="nuevaListaModalH_'+ editaCampos +'" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_'+ editaCampos +'" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "'+ editaCampos +'" class="pull-right">Editar Lista</a></td>'+                                    
                                    '<td style="width: 10%; text-align: center;"><label><input type="checkbox" value="-1" name="G6_C41_'+ editaCampos +'" id="G6_C41_'+ editaCampos +'"></label></td>'+
                                    '<td style="text-align:center;">'+
                                    '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="'+ editaCampos +'" ><i class="fa fa-cog"></i></button>'+
                                    '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField" id="btnEliminarField_'+ editaCampos +'" title="<?php echo $str_campo_delet___;?>" valorField="'+ editaCampos +'" ><i class="fa fa-trash-o"></i></button></td>'+
                                    '<input type="hidden" name="G6_C43_'+ editaCampos +'" id="G6_C43_'+ editaCampos +'" value="0">'+
                                    '<input type="hidden" name="GuidetM_'+ editaCampos +'" id="GuidetM_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="Guidet_'+ editaCampos +'" id="Guidet_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="modoGuidet_'+ editaCampos +'" id="modoGuidet_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C46_'+ editaCampos +'" id="G6_C46_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C207_'+ editaCampos +'" id="G6_C207_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C53_'+ editaCampos +'" id="G6_C53_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C54_'+ editaCampos +'" id="G6_C54_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C55_'+ editaCampos +'" id="G6_C55_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C56_'+ editaCampos +'" id="G6_C56_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C57_'+ editaCampos +'" id="G6_C57_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C58_'+ editaCampos +'" id="G6_C58_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C51_'+ editaCampos +'" id="G6_C51_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C42_'+ editaCampos +'" id="G6_C42_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="G6_C52_'+ editaCampos +'" id="G6_C52_'+ editaCampos +'"  value="0">'+
                                    '<input type="hidden" name="nuevo_campo_'+ editaCampos +'" id="nuevo_campo_'+ editaCampos +'"  value="Nuevo">'+

                                '</tr>';

            $("#cuerpoTablaQuesedebeGuardarEdicion").append(cuerpoTablaQuesedebeGuardar);

            $(".str_SelectTipo").change(function(){
                var id = $(this).attr('atrId');
                if($(this).val() == 6){
                    $("#G6_C44_"+id).attr('disabled', false);
                    $("#nuevaListaModalH_"+id).show();
                    $("#G6_C43_"+id).attr('disabled', true); 
                    $("#G6_C43_"+id).hide(); 
                    $("#G6_C44_"+id).show();

                }else if($(this).val() == 11) {

                    $("#G6_C43_"+id).attr('disabled', false);
                    $("#G6_C43_"+id).show(); 
                    $("#G6_C44_"+id).attr('disabled', true);
                    $("#G6_C44_"+id).hide();
                    $("#nuevaListaModalH_"+id).hide();
                    $("#editarLista_"+id).hide();

                }else{

                    $("#G6_C44_"+id).attr('disabled', true);
                    $("#nuevaListaModalH_"+id).hide();

                    $("#G6_C43_"+id).attr('disabled', true); 
                    $("#G6_C43_"+id).hide();
                    $("#G6_C44_"+id).show();
                }

               

            });

            $("#btnEliminarField_"+editaCampos).click(function(){
                var id = $(this).attr('valorField');
                $("#campos"+id).remove();
            });
            //$("#G6_C44_"+campo).select2();

            $("#G6_C44_"+editaCampos).change(function(){
                var id = $(this).attr('cmpo');
                if($(this).val() != 0){
                    $("#editarLista_"+id).show();
                }else{
                    $("#editarLista_"+id).hide();
                }
            });

            $("#editarLista_"+editaCampos).click(function(){
                var id = $(this).attr('cmpo');
                $("#agrupador")[0].reset();
                $("#opciones").html('');
                contador = 0;
                $("#hidListaInvocada").val(id);
                $.ajax({
                    url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                    type    : 'post',
                    data    : { getListasEdit : true, idOpcion : $("#G6_C44_"+id).val() },
                    dataType: 'json',
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success : function(data){
                        if(data.code == '1'){
                            $("#idListaE").val(data.id);
                            $("#txtNombreLista").val(data.opcion);
                            $.each(data.lista, function(i, items){
                                var cuerpo = "<div class='row' id='id_"+i+"'>";
                                cuerpo += "<div class='col-md-10'>";
                                cuerpo += "<div class='form-group'>";
                                cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                                cuerpo += "</div>";
                                cuerpo += "</div>";
                                cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                cuerpo += "<div class='form-group'>";
                                cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                                cuerpo += "</div>";
                                cuerpo += "</div>";
                                cuerpo += "</div>";
                                $("#opciones").append(cuerpo);
                            });
                            $("#opciones").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                            contador = data.total;
                            console.log(contador);
                            $("#operLista").val("edit");

                            $(".deleteopcionP").click(function(){
                                var id = $(this).attr('value');
                                var miId = $(this).attr('id');
                                alertify.confirm('<?php echo $str_deleteOptio__ ;?>' , function(e){
                                    if(e){
                                        $.ajax({
                                            url   : '<?php echo $url_crud; ?>',
                                            type  : 'post',
                                            data  : { deleteOption: true, id : id},
                                            beforeSend : function(){
                                                $.blockUI({ 
                                                    baseZ: 2000,
                                                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                            },
                                            complete : function(){
                                                $.unblockUI();
                                            },
                                            success : function(data){
                                                if(data == '1'){
                                                    alertify.success('<?php echo $str_Exito;?>');
                                                    $("#id_"+miId).remove();
                                                }else{
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
       
            });

            $("#nuevaListaModalH_"+editaCampos).click(function(){
                $("#agrupador")[0].reset();
                $("#opciones").html('');
                contador = 0;
                $("#hidListaInvocada").val($(this).attr('cmpo'));
                $("#operLista").val('add');
                $("#idListaE").val('0');
            });

            editaCampos++;
            $("#contadorEditablesPreguntas").val(editaCampos);
        });

        

        $(".limpiarSecciones").click(function(){
            $("#crearseccionesModalForm")[0].reset();
            $("#cerradoAcordeon").hide();
            $("#cuerpoTablaQuesedebeGuardar").html('');
            campo = 1;
        });

        $("#crearSeccionesModalButton").click(function(){
            var valido = 0;
            if( $("#G7_C33").val().length < 1){
                alertify.error('<?php echo $str_error_nombre__;?>');
                valido = 1;
            }
            if( $("#G7_C35").val().length < 1){
                alertify.error('<?php echo $str_error_numero__;?>');
                valido = 1;
            }

            if( $("#G7_C36").val() == '0'){
                alertify.error('<?php echo $str_error_Tipo____;?>');
                valido = 1;
            }

            var vacios = 0;
            $(".textoMostrado").each(function(){
                if($(this).val().length < 1){
                    vacios++;
                }
            });

            if(vacios > 0){
                valido = 1;
                alertify.error('<?php echo $str_campo_eror_n__;?>');
            }
            if(valido == 0){
                var form = $("#crearseccionesModalForm"); 
                var formData = new FormData($("#crearseccionesModalForm")[0]);
                formData.append('padre', $("#hidId").val());
                formData.append('contador', campo);
                formData.append('insertarSecciones','si');
                $.ajax({
                    url: 'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success: function(data){
                        if(data != '0'){   
                            alertify.success("<?php echo $str_Exito ;?>");
                            $.ajax({
                                url      : '<?=$url_crud;?>?callDatosSecciones=true',
                                type     : 'POST',
                                data     : { 
                                    id : $("#hidId").val() , 
                                    idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                                },
                                dataType : 'html',
                                success  : function(data){
                                    $("#secciones_creadas").html(data);
                                    $("#secciones_creadas").sortable({
                                        axis: 'y',
                                        update: function (event, ui) {
                                            var data = $(this).sortable('serialize');
                                            $("#txtOrdenData").val(data);
                                        }
                                    });
                                    $(".btnEliminarSeccion").click(function(){
                                        var idSeccion = $(this).attr('valorSeccion');
                                        alertify.confirm("<?php echo $str_message_genericoDel; ?>", function(e){
                                            if(e){
                                                $.ajax({
                                                    url      : 'cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php?insertarDatosGrilla=si',
                                                    type     : 'POST',
                                                    data     : { id : idSeccion , oper : 'del'},
                                                    beforeSend : function(){
                                                        $.blockUI({ 
                                                            baseZ: 2000,
                                                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                    },
                                                    complete : function(){
                                                        $.unblockUI();
                                                    },
                                                    success  : function(data){
                                                        if(data == '0'){   
                                                            alertify.success('<?php echo $str_Exito;?>');
                                                            $("#seccion_"+idSeccion).remove();
                                                        }else{
                                                            alertify.error("<?php echo $error_de_proceso; ?>");
                                                        }
                                                    } 
                                                });
                                            }
                                        });
                                    });
                                    $("#crearSeccionesModal").modal('hide');

                                    if($("#G5_C31").val() == 0 || $("#G5_C31").val() == ''){
                                        $.ajax({
                                            type    : 'post',
                                            url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?camposGuion=true',
                                            data    : { guion : $("#hidId").val() },
                                            success : function(data){
                                                $("#G5_C31").html(data);
                                                $("#G5_C59").html(data);
                                                $("#G5_C31").attr('disabled', false);
                                                $("#G5_C59").attr('disabled', false);
                                           }
                                        });
    
                                    }


                                    $(".btnEditarSeccion").click(function(){
                                        var id = $(this).attr('valorSeccion');
                                        $.ajax({
                                            url     :  'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php',
                                            type    : 'post',
                                            dataType: 'json',
                                            data    : { getDatosSeccionesEdicion : true , id : id },
                                            beforeSend : function(){
                                                $.blockUI({ 
                                                    baseZ: 2000,
                                                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                            },
                                            complete : function(){
                                                $("#editarSeccionesModal").modal();
                                                $.unblockUI();
                                            },
                                            success : function(data){
                                                if(data.code == '1'){
                                                    //console.log(data);
                                                    $("#IdseccionEdicion").val(id);
                                                    $("#EditarG7_C33").val(data.sec_nom);
                                                    $("#EditarG7_C36").val(data.sec_Tip);
                                                    $("#EditarG7_C36").val(data.sec_Tip).change();
                                                    $("#EditarG7_C35").val(data.sec_num_col);
                                                    if(data.sec_mini != 0 && data.sec_mini != ''){
                                                        $("#EditarG7_C37").attr('checked' , true); 
                                                    }else{
                                                        $("#EditarG7_C37").attr('checked' , false); 
                                                    }
                                                    

                                                    var cuerpoTablaQuesedebeGuardar = '';
                                                    $("#cuerpoTablaQuesedebeGuardarEdicion").html('');
                                                    $("#contadorEditablesPreguntas").val(0);
                                                    $.each(data.datosPregun, function(campo , item){
                                                        //alert(item.PREGUN_Texto_____b);

                            cuerpoTablaQuesedebeGuardar = '<tr id="campos'+ campo +'">'+
                                                    '<td style="width: 25%; text-align: center;"><input type="text" class="form-control input-sm textoMostrado" id="G6_C39_'+ campo +'" value=""  name="G6_C39_'+ campo +'"  placeholder="<?php echo $str_campo_texto___; ?>"></td>'+
                                                    '<td style="width: 25%; text-align: center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_'+ campo +'" id="G6_C40_'+ campo +'" atrId = "'+ campo +'">'+
                                                            '<?php
                                                                echo '<option value="1">Texto</option>';
                                                                echo '<option value="2">Memo</option>';
                                                                echo '<option value="3">Numerico</option>';
                                                                echo '<option value="4">Decimal</option>';
                                                                echo '<option value="5">Fecha</option>';
                                                                echo '<option value="10">Hora</option>';
                                                                echo '<option value="6">Lista</option>';
                                                                echo '<option value="11">Lista Compuesta</option>';
                                                                echo '<option value="8">Casilla de verificación</option>';
                                                                echo '<option value="9">Libreto / Label</option>';  
                                                                echo '<option value="12">Maestro - Detalle</option>';   
                                                                
                                                            ?>'+
                                                        '</select></td>'+
                                                    '<td style="width: 10%; text-align: center;"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_'+ campo +'" id="G6_C44_'+ campo +'" cmpo = "'+ campo +'">'+ ListasBd +'</select><select disabled class="form-control input-sm str_Select2" style="width: 100%; display:none;"  name="G6_C43_'+ campo +'" id="G6_C43_'+ editaCampos +'" cmpo = "'+ editaCampos +'">'+ GuionesBD +'</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "'+ campo +'" id="nuevaListaModalH_'+ campo +'" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_'+ campo +'" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "'+ campo +'" class="pull-right">Editar Lista</a></td>'+                                    
                                                    '<td style="width: 10%; text-align: center;"><label><input type="checkbox" value="-1" name="G6_C41_'+ campo +'" id="G6_C41_'+ campo +'"></label></td>'+
                                                    '<td style="text-align:center;">'+
                                                    '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="'+ campo +'" ><i class="fa fa-cog"></i></button>'+
                                                    '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField"  title="<?php echo $str_campo_delet___;?>" valorField="'+ item.PREGUN_ConsInte__b +'" cmpo ="'+campo+'"><i class="fa fa-trash-o"></i></button></td>'+
                                                    '<input type="hidden" name="G6_C43_'+ campo +'" id="G6_C43_'+ campo +'" value="0">'+
                                                    '<input type="hidden" name="GuidetM_'+ campo +'" id="GuidetM_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="Guidet_'+ campo +'" id="Guidet_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="modoGuidet_'+ campo +'" id="modoGuidet_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C46_'+ campo +'" id="G6_C46_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C207_'+ campo +'" id="G6_C207_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C53_'+ campo +'" id="G6_C53_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C54_'+ campo +'" id="G6_C54_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C55_'+ campo +'" id="G6_C55_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C56_'+ campo +'" id="G6_C56_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C57_'+ campo +'" id="G6_C57_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C58_'+ campo +'" id="G6_C58_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C51_'+ campo +'" id="G6_C51_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C42_'+ campo +'" id="G6_C42_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C52_'+ campo +'" id="G6_C52_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="ConsInte_'+ campo +'" id="ConsInte_'+ campo +'"  value="0">'+

                                                '</tr>';

                                                        $("#cuerpoTablaQuesedebeGuardarEdicion").append(cuerpoTablaQuesedebeGuardar);
                                                        $("#contadorEditablesPreguntas").val(campo);

                                                        $("#G6_C39_"+ campo).val(item.PREGUN_Texto_____b);

                                                        $("#ConsInte_"+campo).val(item.PREGUN_ConsInte__b);

                                                        
                                                        $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b);
                                                        $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b).change();

                                                        if(item.PREGUN_IndiBusc__b == '-1'){
                                                            $("#G6_C41_"+ campo).attr('checked' , true);
                                                        }else{
                                                            $("#G6_C41_"+ campo).attr('checked' , false);
                                                        }
                                                        

                                                        if(item.PREGUN_ConsInte__OPCION_B != null && item.PREGUN_ConsInte__OPCION_B != ''){
                                                            $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B);
                                                            $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B).change();
                                                            $("#G6_C44_"+campo).attr('disabled', false);
                                                            $("#nuevaListaModalH_"+campo).show();
                                                            $("#editarLista_"+campo).show();
                                                        }          
                                                        
                                                        
                                                        

                                                        $("#G6_C44_"+campo).change(function(){
                                                            var id = $(this).attr('cmpo');
                                                            if($(this).val() != 0){
                                                                $("#editarLista_"+id).show();
                                                            }else{
                                                                $("#editarLista_"+id).hide();
                                                            }
                                                        });

                                                        $("#editarLista_"+campo).click(function(){
                                                            var id = $(this).attr('cmpo');
                                                            $("#agrupador")[0].reset();
                                                            $("#opciones").html('');
                                                            contador = 0;
                                                            $("#hidListaInvocada").val(id);
                                                            $.ajax({
                                                                url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                                                                type    : 'post',
                                                                data    : { getListasEdit : true, idOpcion : $("#G6_C44_"+id).val() },
                                                                dataType: 'json',
                                                                beforeSend : function(){
                                                                    $.blockUI({ 
                                                                        baseZ: 2000,
                                                                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                },
                                                                complete : function(){
                                                                    $.unblockUI();
                                                                },
                                                                success : function(data){
                                                                    if(data.code == '1'){
                                                                        $("#idListaE").val(data.id);
                                                                        $("#txtNombreLista").val(data.opcion);
                                                                        $.each(data.lista, function(i, items){
                                                                            var cuerpo = "<div class='row' id='id_"+i+"'>";
                                                                            cuerpo += "<div class='col-md-10'>";
                                                                            cuerpo += "<div class='form-group'>";
                                                                            cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                                            cuerpo += "<div class='form-group'>";
                                                                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "</div>";
                                                                            $("#opciones").append(cuerpo);
                                                                        });
                                                                        $("#opciones").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                                                                        $(".deleteopcionP").click(function(){
                                                                            var id = $(this).attr('value');
                                                                            var miId = $(this).attr('id');
                                                                            alertify.confirm('<?php echo $str_deleteOptio__ ;?>' , function(e){
                                                                                if(e){
                                                                                    $.ajax({
                                                                                        url   : '<?php echo $url_crud; ?>',
                                                                                        type  : 'post',
                                                                                        data  : { deleteOption: true, id : id},
                                                                                        beforeSend : function(){
                                                                                            $.blockUI({ 
                                                                                                baseZ: 2000,
                                                                                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                                        },
                                                                                        complete : function(){
                                                                                            $.unblockUI();
                                                                                        },
                                                                                        success : function(data){
                                                                                            if(data == '1'){
                                                                                                alertify.success('<?php echo $str_Exito;?>');
                                                                                                $("#id_"+miId).remove();
                                                                                            }else{
                                                                                                alertify.error('Error');
                                                                                            }
                                                                                        }
                                                                                    })

                                                                                }
                                                                            });
                                                                        });
                                                                        contador = data.total;
                                                                        //console.log(contador);
                                                                        $("#operLista").val("edit");
                                                                    }
                                                                }
                                                            });
                                                   
                                                        });

                                                        $("#nuevaListaModalH_"+campo).click(function(){
                                                            $("#agrupador")[0].reset();
                                                            $("#opciones").html('');
                                                            contador = 0;
                                                            $("#hidListaInvocada").val($(this).attr('cmpo'));
                                                            $("#operLista").val('add');
                                                            $("#idListaE").val('0');
                                                        });

                                                        campo++;

                                                    });
                                                    
                                                    $(".btnEliminarField").click(function(){
                                                        var id = $(this).attr('valorField');
                                                        var campo = $(this).attr('cmpo');
                                                        alertify.confirm('<?php echo $str_deleteField___;?>', function(e){
                                                                if(e){
                                                                    $.ajax({
                                                                        url   : '<?php echo $url_crud; ?>',
                                                                        type  : 'post',
                                                                        data  : { borraPreguntas: true, id : id},
                                                                        beforeSend : function(){
                                                                            $.blockUI({ 
                                                                                baseZ: 2000,
                                                                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                        },
                                                                        complete : function(){
                                                                            //$("#editarSeccionesModal").modal();
                                                                            $.unblockUI();
                                                                        },
                                                                        success : function(data){
                                                                            if(data == '1'){
                                                                                alertify.success('<?php echo $str_Exito;?>');
                                                                                $("#campos"+campo).remove();
                                                                            }else{
                                                                                alertify.error('Error');
                                                                            }
                                                                        }
                                                                    })

                                                                }
                                                            }
                                                        );   
                                                    });

                                                    $(".str_SelectTipo").change(function(){
                                                        var id = $(this).attr('atrId');
                                                        if($(this).val() == 6){
                                                            $("#G6_C44_"+id).attr('disabled', false);
                                                            $("#nuevaListaModalH_"+id).show();
                                                            $("#G6_C43_"+id).attr('disabled', true); 
                                                            $("#G6_C43_"+id).hide(); 
                                                            $("#G6_C44_"+id).show();

                                                        }else if($(this).val() == 11) {

                                                            $("#G6_C43_"+id).attr('disabled', false);
                                                            $("#G6_C43_"+id).show(); 
                                                            $("#G6_C44_"+id).attr('disabled', true);
                                                            $("#G6_C44_"+id).hide();
                                                            $("#nuevaListaModalH_"+id).hide();
                                                            $("#editarLista_"+id).hide();

                                                        }else{

                                                            $("#G6_C44_"+id).attr('disabled', true);
                                                            $("#nuevaListaModalH_"+id).hide();

                                                            $("#G6_C43_"+id).attr('disabled', true); 
                                                            $("#G6_C43_"+id).hide();
                                                            $("#G6_C44_"+id).show();
                                                        }
                                                    });
                                                    
                                                }else{
                                                    alertify.error('La seccion no existe');
                                                }
                                            }
                                        });
                                    });
                                }
                            })
                        }else{
                            alertify.error("<?php echo $error_de_proceso ;?>");
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error("<?php echo $error_de_red ;?>");
                    }
                });
            }

        });
    
        $("#editarSeccionesModalButton").click(function(){
            var valido = 0;
            if( $("#EditarG7_C33").val().length < 1){
                alertify.error('<?php echo $str_error_nombre__;?>');
                valido = 1;
            }
            if( $("#EditarG7_C35").val().length < 1){
                alertify.error('<?php echo $str_error_numero__;?>');
                valido = 1;
            }

            if( $("#EditarG7_C36").val() == '0'){
                alertify.error('<?php echo $str_error_Tipo____;?>');
                valido = 1;
            }

            var vacios = 0;
            $(".textoMostrado").each(function(){
                if($(this).val().length < 1){
                    vacios++;
                }
            });

            if(vacios > 0){
                valido = 1;
                alertify.error('<?php echo $str_campo_eror_n__;?>');
            }
            if(valido == 0){
                var form = $("#editarseccionesModalForm"); 
                var formData = new FormData($("#editarseccionesModalForm")[0]);
                formData.append('padre', $("#hidId").val());
                formData.append('contador', $("#contadorEditablesPreguntas").val());
                formData.append('editarDatosSecciones','si');
                $.ajax({
                    url: 'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success: function(data){
                        if(data != '0'){   
                            alertify.success("<?php echo $str_Exito ;?>");
                            $.ajax({
                                url      : '<?=$url_crud;?>?callDatosSecciones=true',
                                type     : 'POST',
                                data     : { 
                                    id : $("#hidId").val() , 
                                    idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                                },
                                dataType : 'html',
                                success  : function(data){
                                    $("#secciones_creadas").html(data);
                                    $("#secciones_creadas").sortable({
                                        axis: 'y',
                                        update: function (event, ui) {
                                            var data = $(this).sortable('serialize');
                                            $("#txtOrdenData").val(data);
                                        }
                                    });
                                    if($("#G5_C31").val() == 0 || $("#G5_C31").val() == ''){
                                        $.ajax({
                                            type    : 'post',
                                            url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php?camposGuion=true',
                                            data    : { guion :  $("#hidId").val() },
                                            success : function(data){
                                                $("#G5_C31").html(data);
                                                $("#G5_C59").html(data);
                                                $("#G5_C31").attr('disabled', false);
                                                $("#G5_C59").attr('disabled', false);
                                           }
                                        });
                                    }

                                    $(".btnEliminarSeccion").click(function(){
                                        var idSeccion = $(this).attr('valorSeccion');
                                        alertify.confirm("<?php echo $str_message_genericoDel; ?>", function(e){
                                            if(e){
                                                $.ajax({
                                                    url      : 'cruds/DYALOGOCRM_SISTEMA/G7/G7_CRUD.php?insertarDatosGrilla=si',
                                                    type     : 'POST',
                                                    data     : { id : idSeccion , oper : 'del'},
                                                    beforeSend : function(){
                                                        $.blockUI({ 
                                                            baseZ: 2000,
                                                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                    },
                                                    complete : function(){
                                                        $.unblockUI();
                                                    },
                                                    success  : function(data){
                                                        if(data == '0'){   
                                                            alertify.success('<?php echo $str_Exito;?>');
                                                            $("#seccion_"+idSeccion).remove();
                                                        }else{
                                                            alertify.error("<?php echo $error_de_proceso; ?>");
                                                        }
                                                    } 
                                                });
                                            }
                                        });
                                    });

                                    $(".btnEditarSeccion").click(function(){
                                        var id = $(this).attr('valorSeccion');
                                        $.ajax({
                                            url     :  'cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php',
                                            type    : 'post',
                                            dataType: 'json',
                                            data    : { getDatosSeccionesEdicion : true , id : id },
                                            beforeSend : function(){
                                                $.blockUI({ 
                                                    baseZ: 2000,
                                                    message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                            },
                                            complete : function(){
                                                $("#editarSeccionesModal").modal();
                                                $.unblockUI();
                                            },
                                            success : function(data){
                                                if(data.code == '1'){
                                                    //console.log(data);
                                                    $("#IdseccionEdicion").val(id);
                                                    $("#EditarG7_C33").val(data.sec_nom);
                                                    $("#EditarG7_C36").val(data.sec_Tip);
                                                    $("#EditarG7_C36").val(data.sec_Tip).change();
                                                    $("#EditarG7_C35").val(data.sec_num_col);
                                                    if(data.sec_mini != 0 && data.sec_mini != ''){
                                                        $("#EditarG7_C37").attr('checked' , true); 
                                                    }else{
                                                        $("#EditarG7_C37").attr('checked' , false); 
                                                    }

                                                    var cuerpoTablaQuesedebeGuardar = '';
                                                    $("#cuerpoTablaQuesedebeGuardarEdicion").html('');
                                                    $.each(data.datosPregun, function(campo , item){
                                                        //alert(item.PREGUN_Texto_____b);

                            cuerpoTablaQuesedebeGuardar = '<tr id="campos'+ campo +'">'+
                                                    '<td style="width: 25%; text-align: center;"><input type="text" class="form-control input-sm textoMostrado" id="G6_C39_'+ campo +'" value=""  name="G6_C39_'+ campo +'"  placeholder="<?php echo $str_campo_texto___; ?>"></td>'+
                                                    '<td style="width: 25%; text-align: center;"><select class="form-control input-sm str_SelectTipo"  style="width: 100%;" name="G6_C40_'+ campo +'" id="G6_C40_'+ campo +'" atrId = "'+ campo +'">'+
                                                            '<?php
                                                                echo '<option value="1">Texto</option>';
                                                                echo '<option value="2">Memo</option>';
                                                                echo '<option value="3">Numerico</option>';
                                                                echo '<option value="4">Decimal</option>';
                                                                echo '<option value="5">Fecha</option>';
                                                                echo '<option value="10">Hora</option>';
                                                                echo '<option value="6">Lista</option>';
                                                                echo '<option value="11">Lista Compuesta</option>';
                                                                echo '<option value="8">Casilla de verificación</option>';
                                                                echo '<option value="9">Libreto / Label</option>';  
                                                                echo '<option value="12">Maestro - Detalle</option>';   
                                                                
                                                            ?>'+
                                                        '</select></td>'+
                                                    '<td style="width: 10%; text-align: center;"><select disabled class="form-control input-sm str_Select2" style="width: 100%;"  name="G6_C44_'+ campo +'" id="G6_C44_'+ campo +'" cmpo = "'+ campo +'">'+ ListasBd +'</select><select disabled class="form-control input-sm str_Select2" style="width: 100%; display:none;"  name="G6_C43_'+ campo +'" id="G6_C43_'+ editaCampos +'" cmpo = "'+ editaCampos +'">'+ GuionesBD +'</select><a href="#" data-toggle="modal" data-target="#NuevaListaModal" cmpo = "'+ campo +'" id="nuevaListaModalH_'+ campo +'" style="display:none;" class="pull-left">Nueva Lista</a>&nbsp;&nbsp;<a id="editarLista_'+ campo +'" data-toggle="modal" data-target="#NuevaListaModal" style="display:none;" href="#" cmpo = "'+ campo +'" class="pull-right">Editar Lista</a></td>'+                                    
                                                    '<td style="width: 10%; text-align: center;"><label><input type="checkbox" value="-1" name="G6_C41_'+ campo +'" id="G6_C41_'+ campo +'"></label></td>'+
                                                    '<td style="text-align:center;">'+
                                                    '<button type="button" class="btn btn-warning btn-sm btnAvanzadosField" title="<?php echo $str_campo_avanz___;?>" valorField="'+ campo +'" ><i class="fa fa-cog"></i></button>'+
                                                    '&nbsp;<button type="button" class="btn btn-danger btn-sm btnEliminarField"  title="<?php echo $str_campo_delet___;?>" valorField="'+ item.PREGUN_ConsInte__b +'" cmpo ="'+campo+'"><i class="fa fa-trash-o"></i></button></td>'+
                                                    '<input type="hidden" name="G6_C43_'+ campo +'" id="G6_C43_'+ campo +'" value="0">'+
                                                    '<input type="hidden" name="GuidetM_'+ campo +'" id="GuidetM_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="Guidet_'+ campo +'" id="Guidet_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="modoGuidet_'+ campo +'" id="modoGuidet_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C46_'+ campo +'" id="G6_C46_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C207_'+ campo +'" id="G6_C207_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C53_'+ campo +'" id="G6_C53_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C54_'+ campo +'" id="G6_C54_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C55_'+ campo +'" id="G6_C55_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C56_'+ campo +'" id="G6_C56_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C57_'+ campo +'" id="G6_C57_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C58_'+ campo +'" id="G6_C58_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C51_'+ campo +'" id="G6_C51_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C42_'+ campo +'" id="G6_C42_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="G6_C52_'+ campo +'" id="G6_C52_'+ campo +'"  value="0">'+
                                                    '<input type="hidden" name="ConsInte_'+ campo +'" id="ConsInte_'+ campo +'"  value="0">'+

                                                '</tr>';

                                                        $("#cuerpoTablaQuesedebeGuardarEdicion").append(cuerpoTablaQuesedebeGuardar);
                                                        $("#contadorEditablesPreguntas").val(campo);

                                                        $("#G6_C39_"+ campo).val(item.PREGUN_Texto_____b);

                                                        $("#ConsInte_"+campo).val(item.PREGUN_ConsInte__b);

                                                        
                                                        $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b);
                                                        $("#G6_C40_"+ campo).val(item.PREGUN_Tipo______b).change();

                                                        if(item.PREGUN_IndiBusc__b == '-1'){
                                                            $("#G6_C41_"+ campo).attr('checked' , true);
                                                        }else{
                                                            $("#G6_C41_"+ campo).attr('checked' , false);
                                                        }
                                                        

                                                        if(item.PREGUN_ConsInte__OPCION_B != null && item.PREGUN_ConsInte__OPCION_B != ''){
                                                            $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B);
                                                            $("#G6_C44_"+ campo).val(item.PREGUN_ConsInte__OPCION_B).change();
                                                            $("#G6_C44_"+campo).attr('disabled', false);
                                                            $("#nuevaListaModalH_"+campo).show();
                                                            $("#editarLista_"+campo).show();
                                                        }          
                                        
                                                        

                                                        $("#G6_C44_"+campo).change(function(){
                                                            var id = $(this).attr('cmpo');
                                                            if($(this).val() != 0){
                                                                $("#editarLista_"+id).show();
                                                            }else{
                                                                $("#editarLista_"+id).hide();
                                                            }
                                                        });

                                                        $("#editarLista_"+campo).click(function(){
                                                            var id = $(this).attr('cmpo');
                                                            $("#agrupador")[0].reset();
                                                            $("#opciones").html('');
                                                            contador = 0;
                                                            $("#hidListaInvocada").val(id);
                                                            $.ajax({
                                                                url     : 'cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                                                                type    : 'post',
                                                                data    : { getListasEdit : true, idOpcion : $("#G6_C44_"+id).val() },
                                                                dataType: 'json',
                                                                beforeSend : function(){
                                                                    $.blockUI({ 
                                                                        baseZ: 2000,
                                                                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                },
                                                                complete : function(){
                                                                    $.unblockUI();
                                                                },
                                                                success : function(data){
                                                                    if(data.code == '1'){
                                                                        $("#idListaE").val(data.id);
                                                                        $("#txtNombreLista").val(data.opcion);
                                                                        $.each(data.lista, function(i, items){
                                                                            var cuerpo = "<div class='row' id='id_"+i+"'>";
                                                                            cuerpo += "<div class='col-md-10'>";
                                                                            cuerpo += "<div class='form-group'>";
                                                                            cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "<div class='col-md-2' style='text-align:center;'>";
                                                                            cuerpo += "<div class='form-group'>";
                                                                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "</div>";
                                                                            cuerpo += "</div>";
                                                                            $("#opciones").append(cuerpo);
                                                                        });
                                                                        $("#opciones").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                                                                        $(".deleteopcionP").click(function(){
                                                                            var id = $(this).attr('value');
                                                                            var miId = $(this).attr('id');
                                                                            alertify.confirm('<?php echo $str_deleteOptio__ ;?>' , function(e){
                                                                                if(e){
                                                                                    $.ajax({
                                                                                        url   : '<?php echo $url_crud; ?>',
                                                                                        type  : 'post',
                                                                                        data  : { deleteOption: true, id : id},
                                                                                        beforeSend : function(){
                                                                                            $.blockUI({ 
                                                                                                baseZ: 2000,
                                                                                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                                        },
                                                                                        complete : function(){
                                                                                            $.unblockUI();
                                                                                        },
                                                                                        success : function(data){
                                                                                            if(data == '1'){
                                                                                                alertify.success('<?php echo $str_Exito;?>');
                                                                                                $("#id_"+miId).remove();
                                                                                            }else{
                                                                                                alertify.error('Error');
                                                                                            }
                                                                                        }
                                                                                    })

                                                                                }
                                                                            });
                                                                        });
                                                                        contador = data.total;
                                                                        //console.log(contador);
                                                                        $("#operLista").val("edit");
                                                                    }
                                                                }
                                                            });
                                                   
                                                        });

                                                        $("#nuevaListaModalH_"+campo).click(function(){
                                                            $("#agrupador")[0].reset();
                                                            $("#opciones").html('');
                                                            contador = 0;
                                                            $("#hidListaInvocada").val($(this).attr('cmpo'));
                                                            $("#operLista").val('add');
                                                            $("#idListaE").val('0');
                                                        });

                                                        campo++;

                                                    });
                                                    
                                                    $(".btnEliminarField").click(function(){
                                                        var id = $(this).attr('valorField');
                                                        var campo = $(this).attr('cmpo');
                                                        alertify.confirm('<?php echo $str_deleteField___;?>', function(e){
                                                                if(e){
                                                                    $.ajax({
                                                                        url   : '<?php echo $url_crud; ?>',
                                                                        type  : 'post',
                                                                        data  : { borraPreguntas: true, id : id},
                                                                        beforeSend : function(){
                                                                            $.blockUI({ 
                                                                                baseZ: 2000,
                                                                                message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                                                                        },
                                                                        complete : function(){
                                                                            //$("#editarSeccionesModal").modal();
                                                                            $.unblockUI();
                                                                        },
                                                                        success : function(data){
                                                                            if(data == '1'){
                                                                                alertify.success('<?php echo $str_Exito;?>');
                                                                                $("#campos"+campo).remove();
                                                                            }else{
                                                                                alertify.error('Error');
                                                                            }
                                                                        }
                                                                    })

                                                                }
                                                            }
                                                        );   
                                                    });

                                                    $(".str_SelectTipo").change(function(){
                                                        var id = $(this).attr('atrId');
                                                        if($(this).val() == 6){
                                                            $("#G6_C44_"+id).attr('disabled', false);
                                                            $("#nuevaListaModalH_"+id).show();
                                                            $("#G6_C43_"+id).attr('disabled', true); 
                                                            $("#G6_C43_"+id).hide(); 
                                                            $("#G6_C44_"+id).show();

                                                        }else if($(this).val() == 11) {

                                                            $("#G6_C43_"+id).attr('disabled', false);
                                                            $("#G6_C43_"+id).show(); 
                                                            $("#G6_C44_"+id).attr('disabled', true);
                                                            $("#G6_C44_"+id).hide();
                                                            $("#nuevaListaModalH_"+id).hide();
                                                            $("#editarLista_"+id).hide();

                                                        }else{

                                                            $("#G6_C44_"+id).attr('disabled', true);
                                                            $("#nuevaListaModalH_"+id).hide();

                                                            $("#G6_C43_"+id).attr('disabled', true); 
                                                            $("#G6_C43_"+id).hide();
                                                            $("#G6_C44_"+id).show();
                                                        }
                                                    });
                                                    
                                                }else{
                                                    alertify.error('La seccion no existe');
                                                }
                                            }
                                        });
                                    });

                                    $("#editarSeccionesModal").modal('hide');
                                }
                            })
                        }else{
                            alertify.error("<?php echo $error_de_proceso ;?>");
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error("<?php echo $error_de_red ;?>");
                    }
                });
            }

        });
    });
</script>

