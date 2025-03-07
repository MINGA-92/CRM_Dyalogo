
<!--  Este es el archivo con el que se maneja el FRONT-END de las campanas salientes del sistema  -->

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

     .alertify-logs  { 
         z-index:999999 !important; 
    }
     #campo{
		display: none;
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
    .secTareasProgramadas{
        margin-bottom: 10px;
        margin-top: 10px;
        margin-left: 10px;
    }
</style>

<div id="mostrar_loading" class=""  >
    <div class="container-fluid" id="barra" style="margin-top: 31% ;display: none;"  >
        <div class="row" >
            <div class="col-md-12">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" id="barraProgreso" role="progressbar" style="width: 1%; max-width: 100%;" >
                        1%
                    </div>
                    
                </div>
            </div>       
        </div>
    </div>
</div>
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width:95%; margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-header" style="padding-right: 5px; padding-bottom: 3px; padding-top: 3px;">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat"></h4>
            </div>
            <div class="modal-body" id="CargarParaEdicion" >

            </div>
        </div>
    </div>
</div>
<div class="modal fade-in" id="modalPredefinida" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat">ASIGNACIÓN DE REGISTROS</h4>
            </div>
            <div class="modal-body">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Esta campaña tiene configurado el tipo de asignación pre-definido.<br>Esto quiere decir que cada registro ingresará a la base de Dyalogo asociado a un agente en específico.</label>
                            </div>
                            <div class="col-md-12">
                                <label>Como quiere asignar los registros?</label>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group checkbox row" style="margin-top: 10px; text-align: center;">
                                    <input type="radio" onclick="tipoDeAsignacion()" name="tipoDeAsignacion" value="1" id="automatica" checked>
                                    <label for="automatica">Asignación automática: Reparte equitativamente los registros cargados por la cantidad de agentes que pertenecen a la campaña.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group checkbox row" style="margin-top: 10px; text-align: center;">
                                    <input type="radio" onclick="tipoDeAsignacion()" name="tipoDeAsignacion" value="2" id="predefinida">
                                    <label for="predefinida">Asignación pre-definida: Asigna los registros según el campo de la base de datos donde este el correo electrónico del agente al que le pertenece.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo $str_title_campo_agente;?></label>
                                    <select class="form-control" disabled="true" id="agenteDistribucion" name="agenteDistribucion">
                                        <option value="0">Seleccione</option>
                                            <?php
                                                $sSql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET['poblacion']." AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 14) AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY') ORDER BY PREGUN_Texto_____b ASC";
                                                $res = $mysqli->query($sSql);
                                                while($row = $res->fetch_object()){
                                                    echo "<option value='{$row->id}'>{$row->nombre}</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn " type="button" id="btnSavePredefinido">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php
    //SECCION : Definicion urls
    $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD.php";
    $url_crud_extender = base_url."cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php";
    $url_crud_programa_tareas = base_url."cruds/DYALOGOCRM_SISTEMA/G38/G38.php";
    $url_modals_programa_tareas = base_url."cruds/DYALOGOCRM_SISTEMA/G38/G38_Modals.php";
    //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN

    $Zsql = "SELECT G10_ConsInte__b as id, G10_C71 as camp1 , b.LISOPC_Nombre____b as camp2 FROM ".$BaseDatos_systema.".G10  LEFT JOIN ".$BaseDatos_systema.".LISOPC as b ON b.LISOPC_ConsInte__b = G10_C76 ORDER BY G10_ConsInte__b DESC LIMIT 0, 50";
    

    $result = $mysqli->query($Zsql);
    if(isset($_GET['id_paso'])){
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__b)) = '".$_GET['id_paso']."'";
        $res_Lsql = $mysqli->query($Lsql);
        if($res_Lsql && $res_Lsql->num_rows==1){
            $datos = $res_Lsql->fetch_array();
            $_GET['id_paso']=$datos['ESTPAS_ConsInte__b'];
        }else{ ?>
            <script>
                window.location.href="<?=base_url?>modulo/error";
            </script>
<?php        }
    }
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_campan;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url?>modulo/seleccion/estrategias/<?php echo md5(clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>"><i class="fa fa-adjust"></i> <?php echo $str_estrategia;?></a></li>
            <li><a href="<?=base_url?>modulo/flujograma/<?php echo md5(clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>"><?php echo $str_flujograma;?></a></li>
            <li class="active"><?php echo $str_campan;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12" id="div_formularios">
                        <div>
                            <button class="btn btn-default" id="Save">
                                <i class="fa fa-save"></i>
                            </button>
                            <?php if(isset($_GET['ruta']) && $_GET['ruta'] == '1'){ ?>
                            <a href="<?=base_url?>modulo/flujograma/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>" class="btn btn-default">
                                <i class="fa fa-close"></i>
                            </a>
                            <?php }else{ ?>
                            <a href="<?=base_url?>modulo/estrategias" class="btn btn-default">
                                <i class="fa fa-close"></i>
                            </a>
                            <?php } ?>
                            <div class="form-group pull-right" style="cursor: pointer;" id="aColaMarcador">
                                <label for="spanColaMarcador" id="LblspanColaMarcador"></label>
                                <span id="spanColaMarcador" style="background-color : #009FE3; cursor: pointer; border-radius: 2px; box-shadow: -2pt 2pt 0 rgba(0,0,0,.4); font-size : 17px" class="badge"><i class="fa fa-bar-chart-o"></i> <u>Detalle y actividad del marcador</u></span>
                             </div>
                        </div>
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div id="12">
                                            <input type="hidden"  class="form-control input-sm Numerico" value="<?php echo $_SESSION['HUESPED'] ;?>"  name="G10_C70" id="G10_C70">
                                            <input type="hidden"  class="form-control input-sm Numerico" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo 0; } ;?>"  name="id_estpas" id="id_estpas">
                                            <div class="row">
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="G10_C71" id="LblG10_C71">NOMBRE</label>
                                                        <input type="text" class="form-control input-sm" id="G10_C71" value=""  name="G10_C71"  placeholder="NOMBRE">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="G10_C72" id="LblG10_C72">ACTIVA</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="-1" name="G10_C72" id="G10_C72" data-error="Before you wreck yourself"  > 
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="G10_C73" id="LblG10_C73">FORMULARIO</label>
                                                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C73" id="G10_C73">
                                                            <option  value="0">NOMBRE</option>
                                                            <?php
                                                                $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 1 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
                                                                $combo = $mysqli->query($str_Lsql);
                                                                while($obj = $combo->fetch_object()){
                                                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";

                                                                }    
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label for="G10_C74" id="LblG10_C74">BASE DE DATOS</label>
                                                        <input type="hidden" name="poblacion" id="poblacion">
                                                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C74" id="G10_C74">
                                                            <option value="0">NOMBRE</option>
                                                            <?php
                                                                $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 2 AND G5_C316 = ".$_SESSION['HUESPED']."  ORDER BY G5_C28 ASC";
                                                                $combo = $mysqli->query($str_Lsql);
                                                                while($obj = $combo->fetch_object()){
                                                                    echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";

                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" value=""  name="G10_C75" id="G10_C75" >
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group" id="tipoMarcador" requiredSelect>
                                                        <label for="G10_C76" id="LblG10_C76">TIPO MARCADOR</label>
                                                        <select class="form-control input-sm str_Select2"  style="width: 100%;" name="G10_C76" id="G10_C76">
                                                            <option value="0">Seleccione</option>
                                                            <?php
                                                                $str_Lsql = "SELECT LISOPC_ValorNum_b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC_SISTEMA WHERE LISOPC_ConsInte__OPCION_b = 46 AND LISOPC_ValorNum_b > 3 AND LISOPC_ValorNum_b != 8 ";

                                                                $obj = $mysqli->query($str_Lsql);
                                                                while($obje = $obj->fetch_object()){
                                                                    if(($obje->OPCION_Nombre____b) == 'PDS'){
                                                                        echo "<option value='".$obje->OPCION_ConsInte__b."' selected>".($obje->OPCION_Nombre____b)."</option>";
                                                                    }else{
                                                                        echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                                                                    }
                                                                }    
                                                                
                                                            ?>
                                                        </select>           
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-xs-6">
                                                    <div class="form-group" id="distribuciondelTrabajo">
                                                        <label for="G10_C77" id="LblG10_C77">TIPO DISTRIBUCIÓN DEL TRABAJO</label>
                                                        <select class="form-control input-sm str_Select2 prueba"  style="width: 100%;" name="G10_C77" id="G10_C77">
                                                            <?php
                                                                $str_Lsql = "SELECT LISOPC_ValorNum_b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC_SISTEMA WHERE LISOPC_ConsInte__OPCION_b = 47";

                                                                $obj = $mysqli->query($str_Lsql);
                                                                while($obje = $obj->fetch_object()){
                                                                    if(($obje->OPCION_Nombre____b) == 'Dinámico'){
                                                                        echo "<option value='".$obje->OPCION_ConsInte__b."' selected>".($obje->OPCION_Nombre____b)."</option>";
                                                                    }else{
                                                                        echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                                                                    }

                                                                }    
                                                                
                                                            ?>
                                                        </select>
                                                        <input type="hidden" id="G10_C77_pre" value="-1">
                                                        <div class="form-group" requiredSelect id="accionContesta" style="display: none;">
                                                            <label for="G10_C90" id="LblG10_C90">ACCION CUANDO CONTESTAN</label>
                                                            <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C90" id="G10_C90">
                                                                <?php
                                                                    $str_Lsql = "SELECT  G11_ConsInte__b as id , G11_C87 FROM ".$BaseDatos_systema.".G11";
                                                                    $combo = $mysqli->query($str_Lsql);
                                                                    while($obj = $combo->fetch_object()){
                                                                        echo "<option value='".$obj->id."' >".($obj->G11_C87)."</option>";

                                                                    }    
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        </div>
                                        
                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>    
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_18">
                                                        <?php echo $str_agent_campan;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_18" class="panel-collapse collapse ">
                                                <span style="color:orange">Para que los agentes sean asignados o designados a un campaña deben reiniciar la sesión en la estación de trabajo</span>
                                                <iframe src="" id="iframeUsuarios" style="width: 100%;height: 500px;" scrolling="no"  marginheight="0" marginwidth="0" noresize  frameborder="0" >
                                                    
                                                </iframe>
                                            </div>
                                        </div>
                                        
                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>    
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_25">
                                                        <?php echo $str_datos_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_25" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-4" style="text-align: center;">
                                                                    <button type="button"  data-toggle="modal" role="button" class="btn btn-app" id="cargardtaosCampanhaCompleto" title="<?php echo $str_campan_car1; ?>">
                                                                        <i class="fa fa-upload"></i> <?php echo $str_campan_car11;?>
                                                                    </button>
                                                                </div>
                                                                <!-- <div class="col-md-4" style="text-align: center;">
                                                                    <button type="button" data-toggle="modal" data-target="#filtros_campanha_delete" id="abrir_modal_delete" class="btn btn-app"  title="Administrar Registros">
                                                                        <i class="fa fa-trash-o"></i> <?php echo $str_campan_car22;?>
                                                                    </button>
                                                                </div> -->
                                                                <div class="col-md-4" style="text-align: center;">
                                                                    <button type="button" data-toggle="modal" data-target="#filtros_campanha" id="abrir_modal_admin" class="btn btn-app"  title="Administrar Registros">
                                                                        <i class="fa fa-database"></i> <?php echo $str_campan_Adm__;?>
                                                                    </button>
                                                                    <!--<a role="button" data-toggle="modal" data-target="#filtrosCampanha" class="btn btn-app" title="Reactivar Registros" onclick="javascript: cambiarTipoValor(6);">
                                                                        <i class="fa fa-play"></i> Iniciar
                                                                    </a>-->
                                                                </div>
                                                                <div class="col-md-4" style="text-align: center;">
                                                                    <button type="button" data-toggle="modal" data-target="#ProgramarModal" id="BtnAbrirPrograTareas" class="btn btn-app"  title="Programar Tareas">
                                                                        <i class="fa fa-calendar" aria-hidden="true"></i><?php echo "Programar Tareas";?>
                                                                    </button>
                                                                    <!--<a role="button" data-toggle="modal" data-target="#filtrosCampanha" class="btn btn-app" title="Reactivar Registros" onclick="javascript: cambiarTipoValor(6);">
                                                                        <i class="fa fa-play"></i> Iniciar
                                                                    </a>-->
                                                                </div>
                                                            </div>
                                                            <br/>
                                                            <!--<div class="row">
                                                                <div class="col-md-6">
                                                                    <button type="button"  data-toggle="modal" data-target="#filtrosCampanha" role="button" class="btn btn-primary btn-block" onclick="javascript: cambiarTipoValor(1);">Meter datos en la campaña (que ya estaban cargados en la base de datos)
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="button"  data-toggle="modal" data-target="#filtrosCampanha" role="button" class="btn btn-primary btn-block" onclick="javascript: cambiarTipoValor(2);">Sacar datos de la campaña (manteniendolos en la base de datos)
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <br/>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <button type="button"  data-toggle="modal" data-target="#filtrosCampanha" role="button" class="btn btn-primary btn-block" onclick="javascript: cambiarTipoValor(3);">Sacar datos de la campaña y eliminarlos de la base de datos
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="button" data-toggle="modal" role="button" class="btn btn-primary btn-block" id="cargardtaosCampanhaSinMuestra" > Cargar datos y no meterlos aún en la campaña
                                                                    </button>
                                                                </div>
                                                            </div>-->
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>    
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_16">
                                                        <?php echo $str_horar_campan;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_16" class="panel-collapse collapse ">
                                                <span style="color:orange">después de un cambio de horario los agentes deben entrar y salir de la estación para que les tome el cambio</span>
                                                <div class="box-body">

                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C108" id="LblG10_C108">Lunes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C108" id="G10_C108" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C111" id="LblG10_C111">Martes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C111" id="G10_C111" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C114" id="LblG10_C114">Miercoles</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C114" id="G10_C114" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C117" id="LblG10_C117">Jueves</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C117" id="G10_C117" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                         
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C120" id="LblG10_C120">Viernes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C120" id="G10_C120" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C123" id="LblG10_C123">Sabado</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C123" id="G10_C123" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C126" id="LblG10_C126">Domingo</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C126" id="G10_C126" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C129" id="LblG10_C129">Festivos</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" class="checkbox-day" value="-1" name="G10_C129" id="G10_C129" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
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


                                                    <div class="col-md-4 col-xs-5">

                                          
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



                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">

                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_17" class="recargarGrillas">
                                                        <?php echo $str_llama_campan;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_17" class="panel-collapse collapse <?php if(isset($_GET['callback'])){echo 'in';} ?>">
                                                <table class="table table-hover table-bordered" id="tablaDatosDetalless0" width="100%">
                                                </table>
                                                <div id="pagerDetalles0">
                                                </div> 
                                            </div>
                                        </div>

                                        
                                        <div class="panel box box-primary">   
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_19" class="camcom-v">
                                                        <?php echo $str_ctale_campan;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_19" class="panel-collapse collapse ">
                                                <table id="tablaCAMCOM">
                                                </table>
                                                <div id="pagerCAMCOM">
                                                </div>
                                            </div>
                                        </div>

                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>    
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#busquedaManual" id="formBusqueda">
                                                        COMO BUSCAR AL CLIENTE
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="busquedaManual" class="panel-collapse collapse ">
                                                <div class="box-body">

                                                    <!-- CONFIGURACIÓN GENERAL -->
                                                    <div id="s_31323" class="panel-collapse collapse in" aria-expanded="true">
                                                        <div class="panel box">
                                                            <div class="box-header with-border">
                                                                <h4 class="box-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_31_6" aria-expanded="false" class="collapsed">
                                                                        CONFIGURACIÓN GENERAL
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="s_31_6" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                                                                <div class="row">
                                                                    <div class="col-md-8 col-xs-10">
                                                                        <div class="form-group">    
                                                                            <label for="">Permitir insertar registros</label>
                                                                            <select name="insertRegistro" id="insertRegistro" class="form-control input-sm">
                                                                                <option value="-1">Si</option>
                                                                                <option value="0">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row" hidden>
                                                                    <div class="col-md-8 col-xs-10">
                                                                        <div class="form-group">    
                                                                            <label for="">Inserción automática</label>
                                                                            <select name="insertAuto" id="insertAuto" class="form-control input-sm">
                                                                                <option value="-1">Si</option>
                                                                                <option value="0">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- ALCANCE DE LA BÚSQUEDA -->
                                                                <div class="row">
                                                                    <div class="col-md-6 col-xs-6">
                                                                        <div class="form-group groupTipoBusqueda">
                                                                            <label for="">Alcance de la búsqueda</label>
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
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- DRAG & DROP -->
                                                    <div id="s_3133" class="panel-collapse collapse in" aria-expanded="true">
                                                        <div class="panel box">
                                                            <div class="box-header with-border">
                                                                <h4 class="box-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_31_5" aria-expanded="false" class="collapsed">
                                                                        CAMPOS DEL FORMULARIO DE BÚSQUEDA
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="s_31_5" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                                                                <!-- DRAG&DROP -->
                                                                <div class="form-group row" id="dragAndDrop">
                                                                    <div class="col-md-5">
                                                                        <div class="input-group">
                                                                            <input type="text" name="buscadorDisponible" id="buscadorDisponible" class="form-control">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-search"></i>
                                                                            </span>
                                                                        </div>
                                                                        <p class="text-center titulo-dragdrop" style="padding-top:8px">Campos que no están el formulario de búsqueda manual</p>
                                                                        <ul id="disponible" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                                        margin-bottom: 10px;
                                                                                        overflow: auto;   
                                                                                        -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-2 text-center" style="padding-top:100px">
                                                                        <button type="button" id="derecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                                                        <button type="button" id="todoDerecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br>
                                
                                                                        <button type="button" id="izquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button><br>
                                                                        <button type="button" id="todoIzquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <div class="input-group">
                                                                            <input type="text" name="buscadorSeleccionado" id="buscadorSeleccionado" class="form-control">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-search"></i>
                                                                            </span>
                                                                        </div>
                                                                        <p class="text-center titulo-dragdrop" style="padding-top:8px">Campos que van a aparecer en el formulario de búsqueda manual</p>
                                                                        <ul id="seleccionado" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                                        margin-bottom: 10px;
                                                                                        overflow: auto;   
                                                                                        -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                                <!-- CHECK DE GENERAR -->
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input type="checkbox" name="checkBusqueda" id="checkBusqueda" ><?php echo $str_message_gener1; ?>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- LABEL DE INFORMACION -->
                                                                <div class="row" style="margin-bottom: 8px;">
                                                                    <div class="col-md-12">
                                                                        <span class="label label-warning" >cualquier modificación que se le haga a la búsqueda aplicará para todas las campañas que utilicen la misma base de datos</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>    
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_20" id="enlacesEntreCampos">
                                                        <?php echo $str_asoci_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_20" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <span style="color:red">Para unir campos de la base de datos con campos del formulario estos deben tener el mismo formato</span>
                                                    <table class="table table-hover" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:45%;"><?php echo $campan_dbfor_1_ ;?></th>
                                                                <th style="width:45%;"><?php echo $campan_dbfor_2_ ;?></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="htmlCamminc">

                                                        </tbody>
                                                    </table>  
                                                    <button type="button" class="btn btn-sm btn-success pull-right"  id="NewAsociacion">
                                                        <i class="fa fa-plus">&nbsp; <?php echo $campan_dbfor_4_;?></i>
                                                    </button>   
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_27">
                                                        <?php echo $str_tipif_campan; ?>
                                                    </a>   
                                                </h4>
                                                 
                                            </div>
                                            <div id="s_27" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12  table-responsive">
                                                            <table class="table table-condensed" id="opciones">

                                                            </table>
                                                        </div> 
                                                    </div>  
                                                    <div class="pull-right" >
                                                        <button type="button" class="btn btn-sm btn-primary" role="button" title="<?php echo $str_Lista_tipifica;?>" id="editEstados">
                                                            <i class="fa fa-edit">&nbsp;<?php echo $str_Lista_tipifica;?></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-success" role="button" title="<?php echo $str_new_opcion____;?>" id="newOpcion">
                                                            <i class="fa fa-plus">&nbsp;<?php echo $str_new_opcion____;?></i>
                                                        </button>   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_21" class="recargarGrillas">
                                                        <?php echo $str_orden_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_21" class="panel-collapse collapse ">
                                                <table id="tablaCAMORD">
                                                </table>
                                                <div id="pagerCAMORD">
                                                </div>
                                            </div>
                                        </div> -->


                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>    
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#estrategia_marcacion" class="estrategia_marcacion">
                                                        <?php echo $str_orden_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="estrategia_marcacion" class="panel-collapse collapse">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12  table-responsive">
                                                            <table class="table table-condensed" id="table-marcacion">
                                                                <thead>
                                                                    <tr class="active">
                                                                        <th>
                                                                            <label>MARCACIÓN BASADA EN DATOS</label>
                                                                        </th>
                                                                        <th>
                                                                            <label>DATO DE LA BASE DE DATOS</label>
                                                                        </th>
                                                                        <th>
                                                                            <label>DATO DE LA GESTION</label>
                                                                        </th>
                                                                        <th>
                                                                           <label>PRIORIDAD</label>
                                                                        </th>
                                                                        <th>
                                                                            <label>ORDENAMIENTO</label>
                                                                        </th>
                                                                        <th>
                                                                            <label>ELIMINAR</label>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="divParametrosEstrategiaMarcacion">
                                                                    
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                        </div>
                                                        <div class="col-md-2" style="text-align: right;">
                                                            <button type="button" class="btn btn-sm btn-success" id="camord_nuevo">
                                                                <i class="glyphicon glyphicon-plus"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-warning" id="camord_refrescar">
                                                                <i class="fa fa-refresh"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-info" id="camord_guardar">
                                                                <i class="fa fa-save"></i>
                                                            </button>
                                                            <input type="hidden" name="camord_cantidad" id="camord_cantidad">
                                                        </div>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>

<!--
                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_25_MZIL">
                                                        
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_25_MZIL" class="panel-collapse collapse ">
                                                <div id="cuerpoDeEnvioFormulario">
                                                    
                                                </div>
                                            </div>
                                        </div>
-->
                                        <?php if(isset($_GET['callback'])) : ?>
                                        <div class="panel box box-primary" style="display: none;">
                                        <?php else : ?>
                                        <div class="panel box box-primary">
                                        <?php endif; ?>    
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_13">
                                                        <?php echo $str_avanz_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_13" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C332" id="LblG10_C332">
                                                                ASIGNACIÓN FIJA DE AGENTES
                                                                </label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" name="G10_C332" id="G10_C332" data-error="Before you wreck yourself"> 
                                                                    </label>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <!-- FIN DEL CAMPO SI/NO -->
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                            <label for="G10_C333" id="LblG10_C333">SEGUNDOS MAXIMOS PARA ESTAR EN LA BUSQUEDA MANUAL</label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C333" id="G10_C333" placeholder="<?php echo $str_campan_timeOut_time_; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                            <label for="G10_C102" id="LblG10_C102"><?php echo $str_campan_timeOut_time_; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C102" id="G10_C102" placeholder="<?php echo $str_campan_timeOut_time_; ?>">
                                                                <span class="help-block"><?php echo $str_campan_help_timeout_;?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C93" id="LblG10_C93"><?php echo $str_campan_numberRegDin_; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C93" id="G10_C93" placeholder="<?php echo $str_campan_numberRegDin_; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C79" id="LblG10_C79"><?php echo $str_campan_limiteRein___; ?></label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C79" id="G10_C79" data-error="Before you wreck yourself" checked="true"> 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- FIN DEL CAMPO SI/NO -->
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C80" id="LblG10_C80"><?php echo $str_campan_limiteReinN__; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C80" id="G10_C80" placeholder="<?php echo $str_campan_limiteReinN__; ?>">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                        </div>
                                                    </div>

<!--
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C81" id="LblG10_C81"><?php echo $str_Campan_DetenerAutom_; ?></label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C81" id="G10_C81" data-error="Before you wreck yourself" checked="true" > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C82" id="LblG10_C82"><?php echo $str_Campan_metaContact__; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C82" id="G10_C82" placeholder="<?php echo $str_Campan_metaContact__; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
-->

                                                    <div class="row" hidden>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C83" id="LblG10_C83"><?php echo $str_Campan_maxDiasFu____; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value="10000"  name="G10_C83" id="G10_C83" placeholder="<?php echo $str_Campan_maxDiasFu____; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C84" id="LblG10_C84"><?php echo $str_Campan_maxAgenDia___;?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value="10000"  name="G10_C84" id="G10_C84" placeholder="<?php echo $str_Campan_maxAgenDia___;?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C94" id="LblG10_C94"><?php echo $str_Campan_timePreview__; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C94" id="G10_C94" placeholder="<?php echo $str_Campan_timePreview__; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C334" id="LblG10_C334">TIPO GRABACIÓN</label>
                                                                <select id="G10_C334" class="form-control" name="G10_C334">
                                                                    <option value="S" selected >Grabación en un archivo.</option>
                                                                    <option value="M">Grabación en un archivo por canal.</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C326" id="LblG10_C326"><?php echo $str_Campan_TimeNoContes_; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C326" id="G10_C326" placeholder="<?php echo $str_Campan_TimeNoContes_; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C92" id="LblG10_C92"><?php echo $str_Campan_Aceleracion__; ?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C92" id="G10_C92" placeholder="<?php echo $str_Campan_Aceleracion__; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
<!--
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C85" id="LblG10_C85"><?php echo $str_Campan_detencionMaq_; ?></label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C85" id="G10_C85" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C91" id="LblG10_C91"><?php echo $str_Campan_ActionMaqui__;?></label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C91" id="G10_C91">
                                                                    <option><?php echo $str_seleccione; ?></option>
                                                                    <?php
                                                                        $str_Lsql = "SELECT  G11_ConsInte__b as id , G11_C87 FROM ".$BaseDatos_systema.".G11";
                                                                        $combo = $mysqli->query($str_Lsql);
                                                                        while($obj = $combo->fetch_object()){
                                                                            echo "<option value='".$obj->id."'>".($obj->G11_C87)."</option>";
                                                                        }    
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
-->

                                                        <!-- DLAB 20190715 - Se agrega el campo para colocar los canales del marcador robotico-->
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="G10_C331" id="LblG10_C331"><?php echo $strCampanRobotLlamadasS_t;?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value="1" name="G10_C331" id="G10_C331" placeholder="<?php echo $strCampanRobotLlamadasS_t;?>">
                                                            </div>
                                                        </div>
                                                        <!-- Se agrega el campo de Tiempo que dura inhabilitado el boton de colgado -->
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="tiempoInabilitadoBotonColgado"><?php echo $strTiempoInhabBotonColgado;?></label>
                                                                <input type="text" class="form-control input-sm Numerico" value="15" name="tiempoInabilitadoBotonColgado" id="tiempoInabilitadoBotonColgado" placeholder="<?php echo $strTiempoInhabBotonColgado;?>">
                                                            </div>
                                                        </div>
                                                </div>
                                                <!-- se mueve esta seccion avanzados -->
                                               
                                                <!-- se mueve esta seccion avanzados -->
                                                <div class="row">
                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for="G10_C85" id="LblG10_C85">Deteccion automatica de maquinas contestadoras</label>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="G10_C85" id="G10_C85" data-error="Before you wreck yourself"> 
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SECCION : PAGINAS INCLUIDAS -->
                                        <input type="hidden" name="id" id="hidId" value='0'>
                                        <input type="hidden" name="id" id="hidId2" value='0'>
                                        <input type="hidden" name="oper" id="oper" value='add'>
                                        <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="id_paso" id="id_paso" value='<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                                    </div>


                                    <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_reportes">
                                                        REPORTES
                                                    </a>   
                                                </h4>
                                            </div>
                                            <div id="s_reportes" class="panel-collapse collapse ">
                                                <iframe id="iframeReportes" src="../../../../estrategias&view=si&report=si&stepUnique=campanout&estrat=<?php echo md5(clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>&paso=<?php echo $datos['ESTPAS_ConsInte__b'] ?>" style="width: 100%;height: 800px;" marginheight="0" marginwidth="0" noresize  frameborder="0" ></iframe>
                                            </div>
                                    </div>
                                    
                                    <!-- Se elimina esta secion y se crea el nuevo modal en administrador de registros -->
                                    <!-- Se elimina esta secion y se crea el nuevo modal en administrador de registros 
          
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#programacion_tareas" class="estrategia_marcacion">
                                                    PROGRAMACION DE TAREAS
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="programacion_tareas" class="panel-collapse collapse">
                                            <?php 
                                                //Obtener el contenido de G38.php
                                                //$contenidoG38= file_get_contents($url_crud_programa_tareas); 
                                                //echo $url_crud_programa_tareas;
                                                //echo $contenidoG38;
                                            ?>
                                        </div>
                                    </div>
                                    
                                    -->
                                
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php 
    $str_Lsql = 'SELECT G10_ConsInte__b, G10_C73 , G10_C74 , G5_C311 FROM '.$BaseDatos_systema.'.G10 JOIN '.$BaseDatos_systema.'.G5 ON G5_ConsInte__b = G10_C73 JOIN '.$BaseDatos_systema.'.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b ='.$_GET['id_paso'];
    //echo $str_Lsql;

    $res = $mysqli->query($str_Lsql);
    $guion = $res->fetch_array();
    $idCampana = $guion['G10_ConsInte__b'];


    $LsqlEstados = "SELECT PREGUN_ConsInte__OPCION_B as OPCION_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion['G10_C74']." AND PREGUN_Texto_____b = 'ESTADO_DY';";
    $resEstados = $mysqli->query($LsqlEstados);
    $datosListas = $resEstados->fetch_array();


?>
<!-- JDBD - Nuevo modal de administracion de registros -->
<div class="modal fade-in" id="filtros_campanha" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog" style="z-index: 10000;">
    <div class="modal-dialog modal-lg" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat_filtros">ADMINISTRAR REGISTROS POR UNICA VEZ</h4>
            </div>
            <div class="modal-body">
                <form id="consulta_campos_where">
                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <div class="radio">
                                    <label><input type="radio" name="radio_condiciones" id="radio_todos" checked class="Radio_condiciones" value="1">TODOS</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <div class="radio">
                                    <label><input type="radio" name="radio_condiciones" id="radio_filtro" class="Radio_condiciones" value="2">CONDICIONES</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <div class="form-group">
                                <div class="radio">
                                    <label><input type="radio" name="radio_condiciones" id="Lista de registros" class="Radio_condiciones" value="3">LISTA DE REGISTROS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none;" id="div_filtros_campan">
                        <div class="col-md-2 col-md-offset-10">
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="new_filtro">Agregar Filtros</button>    
                            </div>
                        </div>
                    </div>
                     <div class="row" style="display: none;" id="div_lista_de_registros">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="campo_a_filtrar">Campo a filtrar</label>
                                <select id="campo_a_filtrar" name="campo_a_filtrar" class="form-control">
                                    <option value="0">Seleccione</option>
                                    <option value="_ConsInte__b" tipo="3" >Llave interna</option>
                                    <?php 
                                        $strListaCampos_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS name FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET["poblacion"]." AND PREGUN_Tipo______b NOT IN (10,6,11,13,8,9,12) ;";

                                        $resListaCampos_t = $mysqli->query($strListaCampos_t);
                                        if($resListaCampos_t){
                                            while ($jey = $resListaCampos_t->fetch_object()) {
                                                echo "<option value='".$jey->id."'>".$jey->name."</option>";
                                            }
                                        }
                                   ?>
                                </select> 
                            </div>
                            
                        </div>
                        <div class="col-md-6" id="div_lista_de_registros">
                            <div class="form-group">
                                <label for="listaExcell">Cargar Lista</label>
                                <input type="file" name="listaExcell" id="listaExcell" class="form-control">
                            </div>
                            
                        </div>
                    </div>
                     <div class="row">
                        <input type="hidden" name="tipoLlamado" id="tipoLlamado">
                        <input type="hidden" name="id_campana" id="id_campanaFiltros" >
                        <div class="col-md-12" id="FILTROS">
                            
                        </div>
                    </div>
                    <div class="row" id="divPrevisualizacion" style="display: none;">
                        <div class="col-md-12">
                            <label>Previsualizacion</label>
                            <p id="textoPrevisualizacion"></p>
                            <span style="color:orange" id="errorCondiciones"></span>
                        </div>
                    </div>

                    <div class="row" id="div_acciones_campan">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_32">Acciones</a>
                                    </h4>
                                </div>
                                <div id="s_32" class="panel-collapse collapse ">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_estados_acciones">Estado</label>
                                                <select id="sel_estados_acciones" name="sel_estados_acciones" class="form-control">
                                                        
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div>
                                                    <label for="sel_tipificaciones_acciones">Tipificación</label>
                                                    <span data-toggle="tooltip" title="" class="badge bg-yellow" data-original-title="si quiere cambiar el tipo de reintento, seleccione una tipificación que tenga el tipo de reintento deseado"><i class="fas fa-exclamation"></i></span>
                                                </div>
                                                <select id="sel_tipificaciones_acciones" name="sel_tipificaciones_acciones" class="form-control acciones">
                                                    
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6" hidden>
                                            <div class="form-group">
                                                <label for="sel_tipo_reintento_acciones">Tipo de reintento</label>
                                                <select id="sel_tipo_reintento_acciones" name="sel_tipo_reintento_acciones" class="form-control ">
                                                    <option value="0">Seleccione</option> 
                                                    <option value="1">REINTENTO AUTOMÁTICO</option> 
                                                    <option value="2">AGENDA</option>
                                                    <option value="3">NO REINTENTAR</option>  
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_activo_acciones">Activo / Inactivo</label>
                                                <select id="sel_activo_acciones" name="sel_activo_acciones" class="form-control acciones">
                                                    <option value="-2">Seleccione</option>    
                                                    <option value="-1">ACTIVO</option> 
                                                    <option value="0">NO ACTIVO</option>   
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_meter_sacar">Incluir en Campa&ntilde;a / Excluir de la Campa&ntilde;a</label>
                                                <select id="sel_meter_sacar" name="sel_meter_sacar" class="form-control acciones">
                                                    <option value="0">Seleccione</option> 
                                                    <option value="1">Incluir en Campa&ntilde;a</option> 
                                                    <option value="2">Excluir de la Campa&ntilde;a</option>   
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="campo">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_usuarios_asignacion">Asignar a</label>
                                                <select id="sel_usuarios_asignacion" name="sel_usuarios_asignacion" class="form-control acciones">
                                                    <option value="-2">Seleccione</option> 
                                                    <option value="-1">Dejar sin agente</option> 
                                                    <?php 
                                                        $Lsql = "SELECT USUARI_Nombre____b, USUARI_ConsInte__b FROM ".$BaseDatos_systema.".USUARI JOIN ".$BaseDatos_systema.".ASITAR ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b WHERE ASITAR_ConsInte__CAMPAN_b = ".$idCampana;
                                                        $usuarios = $mysqli->query($Lsql);
                                                        if($usuarios){
                                                            while ($jey = $usuarios->fetch_object()) {
                                                                echo "<option value='".$jey->USUARI_ConsInte__b."'>".$jey->USUARI_Nombre____b."</option>";
                                                            }
                                                        }
                                                   ?>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="conf_fechas_reintento" style="display:none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_fecha_reintento_acciones">Fecha de reintento</label>
                                                <input type="text" id="txt_fecha_reintento_acciones" name="txt_fecha_reintento_acciones" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_hora_reintento_acciones">Hora de reintento</label>
                                                <input type="text" id="txt_hora_reintento_acciones" name="txt_hora_reintento_acciones" class="form-control">
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
                <button class="btn btn-default" type="button" data-dismiss="modal"> ⬅  Atras </button>
                <button class="btn-primary btn pull-right" type="button" id="aplicar_filtros"> Aplicar </button>
            </div>
        </div>
    </div>
</div>
<!-- JDBD - Nuevo modal de administracion de registros -->
<div class="modal fade-in" id="cargarInformacion" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <input type="hidden" name="fallasValidacion" id="fallasValidacion" value="0">
    <input type="hidden" name="strFechaInicial_t" id="strFechaInicial_t" value="">
    <input type="hidden" name="intIdCampana_t" id="intIdCampana_t" value="">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title " id="title_cargue"></h4>

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


<div class="modal fade-in" id="crearCampanhasNueva" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat">Nueva Estrategia</h4>
            </div>
            <div class="modal-body">
                <form id="formuarioCargarEstoEstrart">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="G2_C7" id="LblG2_C7">NOMBRE</label>
                                <input type="text" class="form-control input-sm" id="G2_C7" value=""  name="G2_C7"  placeholder="NOMBRE">
                            </div>
                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                        <input type="hidden" name="G2_C5" id="G2_C5" value="<?php echo $_SESSION['HUESPED'];?>">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2_C6" id="LblG2_C6">TIPO CAMPAÑA</label>
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


<div class="modal fade-in" id="ModalPreguntaTipificacion" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat"><?php echo $str_configuracion_LE;?></h4>
            </div>
            <div class="modal-body">
                <p style="text-align: justify;">
                    <?php echo $campan_deleteTip; ?>
                    <div class="form-group">
                        <select id="selTipificacionesEliminadas" class="form-control">
                            
                        </select>
                        <input type="hidden" name="idValorDel" id="idValorDel" value="0">
                    </div>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn " type="button" id="btnSaveEliminacionTipificaciones">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="NuevaListaModal" class="modal fade">
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
                        <div class="col-md-12" id="opcionesListaHtml">
                            
                        </div>
                    </div>
                    <input type="hidden" id="hidListaInvocada">  
                    <input type="hidden" id="operLista" name="operLista" value="add">  
                    <input type="hidden" id="idListaE" name="idListaE" value="0">  
                    <input type="hidden" id="TipoListas" name="TipoListas" value="0">   
                </form>
            </div>
            <div class="modal-footer">
                
                <button class="btn btn-success pull-left" type="button" id="newOpcionlistas">
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

<div class="modal fade-in" id="modVisorColaMArcador" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <iframe id="ifrVisorColaMArcador" src="" style="width: 100%; height: 565px;" class="form-control">
                              
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel box box-primary">                                            
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_admin" aria-expanded="true">
                                        ADMINISTRAR REGISTROS                                                    </a>
                                </h4>
                            </div>
                            <div id="s_admin" class="panel-collapse collapse in" aria-expanded="true">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12" style="text-align: center;">
                                            <center>
                                                <button type="button" data-toggle="modal" data-target="#filtros_campanha" id="abrir_modal_admin" class="btn btn-app" title="Administrar Registros">
                                                    <i class="fa fa-database" aria-hidden="true"></i> Administrar Registros 
                                                </button>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url?>assets/plugins/ckeditor/ckeditor.js"></script>

<!-- Estas funciones se encargan del funcionamiento del drag & drop -->
<script type="text/javascript">
    // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
    function moverCampos(arrCampos, accion) {
        
        let ruta = '';
        if (accion == 'derecha') {
            ruta = "agregarCamposForm=true";
        } else if (accion = 'izquerda') {
            ruta = "quitarCamposForm=true";
        }

        $.ajax({
            url: '<?=$url_crud_extender?>?' + ruta,
            type: 'POST',
            dataType: 'json',
            data: {
                arrCampos: arrCampos
            },
            success: function(response) {
                alertify.success("Mensaje: " + response.estado);
            },
            error: function(response) {
                console.log(response);
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

    // En esta funcion se encontrara el buscador que filtrara por el nombre 
    $('#buscadorDisponible, #buscadorSeleccionado').keyup(function() {
        var tipoBuscador = $(this).attr('id');
        var nombres = '';

        if (tipoBuscador == 'buscadorDisponible') {
            nombres = $('ul#disponible .nombre');
        } else if (tipoBuscador == 'buscadorSeleccionado') {
            nombres = $('ul#seleccionado .nombre');
        }

        var buscando = $(this).val();
        var item = '';

        for (let i = 0; i < nombres.length; i++) {
            item = $(nombres[i]).html().toLowerCase();

            for (let x = 0; x < item.length; x++) {
                if (buscando.length == 0 || item.indexOf(buscando) > -1) {
                    $(nombres[i]).closest('li').show();
                } else {
                    $(nombres[i]).closest('li').hide();

                }
            }

        }
    });

    // Solo se selecciona el check cuando se clickea el li
    $("#disponible, #seleccionado").on('click', 'li', function() {
        $(this).find(".mi-check").iCheck('toggle');

        if ($(this).find(".mi-check").is(':checked')) {
            $(this).addClass('seleccionado');
        } else {
            $(this).removeClass('seleccionado');
        }

    });

    $("#disponible, #seleccionado").on('ifToggled', 'input', function(event) {
        if ($(this).is(':checked')) {
            $(this).closest('li').addClass('seleccionado');
        } else {
            $(this).closest('li').removeClass('seleccionado');
        }
    });

    // Capturo el li cuando es puesto en la lista de campos disponibles      
    $("#disponible").on("sortreceive", function(event, ui) {
        let arrDisponible = [];
        arrDisponible[0] = ui.item.data("id");

        moverCampos(arrDisponible, "izquierda");
    });

    // Capturo el li cuando es puesto en la lista de campos seleccionados  
    $("#seleccionado").on("sortreceive", function(event, ui) {
        let arrSeleccionado = [];
        arrSeleccionado[0] = ui.item.data("id");

        moverCampos(arrSeleccionado, "derecha");
    });


    // Envia los elementos seleccionados a la lista de la derecha
    $('#derecha').click(function() {
        let obj = $("#disponible .seleccionado");
        let arrSeleccionado = [];

        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#seleccionado .seleccionado_'+id).append(value);
            arrSeleccionado[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrSeleccionado.length > 0) {
            moverCampos(arrSeleccionado, "derecha");
        }

    });

    // Envia los elementos seleccionados a la lista de la izquerda
    $('#izquierda').click(function() {
        let obj = $("#seleccionado .seleccionado");
        let arrDisponible = [];

        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#disponible .disponible_'+id).append(value);
            arrDisponible[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrDisponible.length > 0) {
            moverCampos(arrDisponible, "izquierda");
        }
    });

    // Envia todos los elementos a la derecha
    $('#todoDerecha').click(function() {
        var obj = $("#disponible li");
        let arrSeleccionado = [];
        
        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#seleccionado .seleccionado_'+id).append(value);
            arrSeleccionado[key] = $(value).data("id");
        });

        moverCampos(arrSeleccionado, "derecha");
    });

    // Envia todos los elementos a la izquerda
    $('#todoIzquierda').click(function() {
        var obj = $("#seleccionado li");
        let arrDisponible = [];
        
        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#disponible .disponible_'+id).append(value);
            arrDisponible[key] = $(value).data("id");
        });

        moverCampos(arrDisponible, "izquierda");
    });
</script>

<script type="text/javascript">

    function carguePredefinido(){
        $("#G10_C77_pre").val('0');
    }

    function tipoDeAsignacion(){
        if($('#automatica').is(':checked')){
            $('#agenteDistribucion').attr('disabled', true);
        }else{
            $('#agenteDistribucion').attr('disabled', false);
        }
    }

    $("#btnSavePredefinido").click(function(){
        if($('#predefinida').is(':checked') && $("#agenteDistribucion").val() == '0'){
            alertify.error("Debe seleccionar la columna agente");
        }else{
            const idCampan = $("#hidId").val();

            $.ajax({
                url:'<?=$url_crud_extender?>',
                type:'POST',
                dataType:'json',
                data:{
                    asignarRegistros:'si',
                    idCampan:idCampan,
                    distribucion:$("input:radio[name='tipoDeAsignacion']:checked").val(),
                    agente:$("#agenteDistribucion").val()
                },
                success:function(data){
                    if(data.estado){
                        swal({
                            title: data.mensaje,
                            text: "Se han asignado "+data.asignados+" registro(s) de un total de "+data.total+" registros",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: "btn-succes",
                            confirmButtonText: "Continuar!",
                            closeOnConfirm: true,
                            closeOnCancel: true    
                        },
                            function(isConfirm) {
                                if (isConfirm) {
                                    $("#G10_C77_pre").val('0');
                                    $("#modalPredefinida").modal('hide');
                                    $("#Save").click();
                                }
                            }
                        );
                    }else{
                        alertify.error(data.mensaje);
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
                },
                error:function(){
                    alertify.error("Se generó un error al procesar la solicitud");
                }
            });
        }
    });

    $("#aColaMarcador").click(function(){
        showModalVisorColaMArcador("Analisis");
    });

    $("#modVisorColaMArcador").on('hidden.bs.modal', function(){
        $("#ifrVisorColaMArcador").attr("src","");
    });

    $(".BorrarTabla").click(function(){
        $.ajax({
            url    : '<?=base_url?>carga/carga_CRUD.php?EliminarTablaTemporal=true',
            type   : 'POST',
            data   : { strNombreTablaTemporal_t : $("#TablaTemporal").val() }
        });
    });

    $("#abrir_modal_admin").click(function(){
    // PARA OCULTAR EL CAMPO 
        let capdelcampo = $("#G10_C77").val();
        
        if(capdelcampo == '0'){
         document.getElementById('campo').style.display='block';
            
        } else {
             document.getElementById('campo').style.display='none';
            
        }
        $("#FILTROS_delete").html('');
        $("#filtros_campanha").hide();
    });

    $("#abrir_modal_delete").click(function(){
        $("#FILTROS").html('');
        $("#filtros_campanha_delete").hide();
    });

    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
    var cuantosVan = 0;
    var contador = 0;
    var contador2 = 1;
    var datosGuion = [];
    var datosForms = [];
    var contadorAsuntos = 1;
    var estados = '';
    var longitudCaminc = 0;
    $(function(){

        $('.close').click(function(){
           borrarLogCargue();
           $("#export-errores").css('display','none');
           $("#title_cargue").html('<?php echo $str_carga;?>');
        });

        $("#campains").addClass('active');
        <?php if(!isset($_GET['id_paso'])){ ?>
        busqueda_lista_navegacion();
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

            $("#hidId").val(0);
            $("#h3mio").html('');
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";
            before_add();

            $("#G10_C76 option").filter(function(){
                return $(this).text() == "PDS";
            }).prop('selected', true).change();


            $("#G10_C77 option").filter(function(){
                return $(this).text() == "Dinámico";
            }).prop('selected', true).change();

            $("#G10_C73").val(0).change();
            $("#G10_C74").val(0).change();
            $("#G10_C72").prop('checked', true);
            $("#G10_C80").val('5');
            $("#G10_C80").attr('disabled', true);
            $("#G10_C82").attr('disabled', true);
            $("#G10_C91").attr('disabled', true);
            $("#G10_C92").val('0');
            $("#G10_C92").attr('disabled', true);
            $("#G10_C93").val('10');
            $("#G10_C93").attr('disabled', true);
            $("#G10_C94").val('30');
            $("#G10_C94").attr('disabled', true);
            $("#G10_C98").attr('disabled', true);
            $("#G10_C99").val('0');

            $("#G10_C109").val('08:00:00');
            $("#G10_C110").val('17:00:00');

            $("#G10_C112").val('08:00:00');
            $("#G10_C113").val('17:00:00');

            $("#G10_C115").val('08:00:00');
            $("#G10_C116").val('17:00:00');

            $("#G10_C118").val('08:00:00');
            $("#G10_C119").val('17:00:00');

            $("#G10_C121").val('08:00:00');
            $("#G10_C122").val('17:00:00');

            $("#G10_C124").val('08:00:00');
            $("#G10_C125").val('17:00:00');

            $("#G10_C127").val('08:00:00');
            $("#G10_C128").val('17:00:00');

            $("#G10_C130").val('08:00:00');
            $("#G10_C131").val('17:00:00');
           
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

            
            //Le informa al crud que la operaciòn a ejecutar es editar registro
            $("#oper").val('edit');
            //Habilitar todos los campos para edicion
            $('#FormularioDatos :input').each(function(){
                $(this).attr('disabled', false);
            });

            before_edit();
          
        });

        //funcionalidad del boton seleccionar_registro
        $("#cancel").click(function(){
            //Se le envia como paraetro cero a la funcion seleccionar_registro
            seleccionar_registro(0);
            //Se inicializa el campo oper, nuevamente
            $("#oper").val(0);

            <?php if(isset($_GET['view'])){ ?>
                //window.location.href  = "cancelar.php";
            <?php }  ?>
            $("#editarDatos").modal('hide');
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
                        url      : '<?=$url_crud;?>?insertarDatosGrilla=si',
                        type     : 'POST',
                        data     : { id : id , oper : 'del'},
                        success  : function(data){
                            if(data == '1'){   
                                //Si el reultado es 1, se limpia la Lista de navegacion y se Inicializa de nuevo                             
                                llenar_lista_navegacion('');
                            }else{
                                //Algo paso, hay un error
                                alert(data);
                            }
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

<script type="text/javascript" src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_eventos_v2.js"></script> 

<script type="text/javascript">
    $(function(){
        var id_campana = 0;

        $("#G10_C79").change(function(){
            if($(this).is(':checked')){
                $("#G10_C80").attr('disabled', false);
            } else {
                $("#G10_C80").attr('disabled', true);
            }   
        });

        
        $("#G10_C95").change(function(){
            if($(this).is(':checked')){
                $("#G10_C98").attr('disabled', false);
            } else {
                $("#G10_C98").attr('disabled', true);
            }   
        });

        $("#G10_C81").change(function(){
            if($(this).is(':checked')){
                $("#G10_C82").attr('disabled', false);
            } else {
                $("#G10_C82").attr('disabled', true);
            }
        });

        // $("#G10_C85").change(function(){
        //     if($(this).is(':checked')){
        //         $("#G10_C91").attr('disabled', false);
        //     } else {
        //         $("#G10_C91").attr('disabled', true);
        //     } 
        // });
        
        $("#G10_C332").change(function(){
            if($(this).is(':checked')){
                $(this).val('-1');
            } else {
                swal({
                  title: "Está seguro?",
                  text: "Si apaga esta opción, todas las agendas que puedan estar reservadas para agentes específicos, van a ser ejecutadas por cualquier agente de los asignados a la campaña.",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonClass: "btn-danger",
                  confirmButtonText: "Si, Continuar!",
                  cancelButtonText: "No, Cancelar!",
                  closeOnConfirm: true,
                  closeOnCancel: true    
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $("#G10_C332").val('0');
                    } else {
                        $("#G10_C332").prop('checked', true);
                    }
                });
            }   
        });
        
        var remove =0; 
        $("#G10_C76").on('change', function() { 
            // se desactiva y mas abajo se activa solo si cumple las condiciones 
            $('#G10_C331').attr('disabled', true);  
            if(remove == 1){
                 $('#G10_C77').prepend("<option value='0'>Predefinida</option>");
                 remove=0;    
            }
            if($(this).val() == '8'){
                $("#G10_C93").attr('disabled', true);
                $("#G10_C77").attr('disabled', true);
                $("#G10_C90").attr('disabled', false);
                $("#G10_C90").prepend("<option value='0' >Seleccione</option>")

                $("#distribuciondelTrabajo").hide();
                $("#accionContesta").show();
            }else{
                $("#G10_C90").attr('disabled', true);
                $("#G10_C90 option[value='0']").remove()
                $("#G10_C77").attr('disabled', false);
                $("#distribuciondelTrabajo").show();
                $("#accionContesta").hide();
            }


            if($(this).val() == '6' || $(this).val() == '7' || $(this).val() == '8' ){
                $("#G10_C85").attr('disabled', false);
                $("#G10_C93").attr('readonly', true);
            }else{
                $("#G10_C85").attr('disabled', true);
                $("#G10_C93").attr('readonly', false);
            }

            if($(this).val() == '7'){
                // se bloquea el campo robot llamadas
                $('#G10_C331').attr('disabled', false);
                $("#G10_C92").attr('readonly', false);
                remove=1;
                $("#G10_C77 option[value='0']").remove();
            }else{
                $("#G10_C92").attr('readonly', true);
                $("#G10_C92").val(0);
            }

            if($(this).val() == '4'){
                $("#G10_C94").attr('disabled', false);
            }else{
                $("#G10_C94").attr('disabled', true);
            }

            if ( $(this).val() == '6' ) {
                $('#G10_C331').attr('disabled', false);
            }
            
        });

        $("#G10_C77").change(function(){
            if($(this).val() == '-1'){
                $("#G10_C93").attr('disabled', false);
            }else{
                $("#G10_C93").attr('disabled', true);
            }
        });

        <?php if(isset($_GET['poblacion'])){ ?>
            $("#G10_C74").val(<?php echo $_GET['poblacion'];?>);
            $("#G10_C74").val(<?php echo $_GET['poblacion'];?>).trigger("change");
        <?php } ?>


        //str_Select2 estos son los guiones
        


        $("#G10_C73").select2({ 
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

        $("#G10_C73").change(function(){
            var valores = $("#G10_C73 option:selected").text();
            var campos = $("#G10_C73 option:selected").attr("dinammicos");
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
                                      

        $("#G10_C74").select2({ 
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

        $("#G10_C74").change(function(){
            var valores = $("#G10_C74 option:selected").text();
            var campos = $("#G10_C74 option:selected").attr("dinammicos");
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
                                      

        $("#G10_C90").select2({ 
            templateResult: function(data) {
                let r = data.text.split('|');
                let result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C90").change(function(){
            var valores = $("#G10_C90 option:selected").text();
            var campos = $("#G10_C90 option:selected").attr("dinammicos");
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
                                      

        $("#G10_C91").select2({ 
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

        $("#G10_C91").change(function(){
            var valores = $("#G10_C91 option:selected").text();
            var campos = $("#G10_C91 option:selected").attr("dinammicos");
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
                                      

        $("#G10_C98").select2({ 
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

        $("#G10_C98").change(function(){
            var valores = $("#G10_C98 option:selected").text();
            var campos = $("#G10_C98 option:selected").attr("dinammicos");
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
                                      

        $("#G10_C101").select2({ 
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

        $("#G10_C101").change(function(){
            var valores = $("#G10_C101 option:selected").text();
            var campos = $("#G10_C101 option:selected").attr("dinammicos");
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
                                      

        $("#G10_C103").select2({ 
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

        $("#G10_C103").change(function(){
            var valores = $("#G10_C103 option:selected").text();
            var campos = $("#G10_C103 option:selected").attr("dinammicos");
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
                                      

        $("#G10_C104").select2({ 
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

        $("#G10_C104").change(function(){
            var valores = $("#G10_C104 option:selected").text();
            var campos = $("#G10_C104 option:selected").attr("dinammicos");
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
                                      

        //datepickers


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


        $(".horaEnvioTxt").timepicker({
            'timeFormat': 'H:i',
            'minTime': '00:00',
            'maxTime': '23:59',
            'setTime': '08:00',
            'step'  : '5',
            'showDuration': true
        });

        //Validaciones numeros Enteros
        

       // $("#G10_C70").numeric();
        
        $("#G10_C75").numeric();
        
        $("#G10_C105").numeric();
        
        $("#G10_C106").numeric();
        
        $("#G10_C107").numeric();
        
        $("#G10_C80").numeric();
        
        $("#G10_C82").numeric();
        
        $("#G10_C83").numeric();
        
        $("#G10_C84").numeric();
        
        $("#G10_C92").numeric();
        
        $("#G10_C93").numeric();
        
        $("#G10_C94").numeric();
        
        $("#G10_C99").numeric();
        
        $("#G10_C102").numeric();

        $("#G10_C333").numeric();
        
        $("#tiempoInabilitadoBotonColgado").numeric();
        
        //Validaciones numeros Decimales       




        //Buton gurdar
        $("#Save").click(function(){
            valid = true
            $("input[name='idLisop[]']").each(function(){
                    id_tip = $(this).val()
                    let tip = $(`input[name='opciones_${id_tip}'`).val()
                    if (tip === null || tip.trim() === "") {
                        alertify.error("las tipificaciones no pueden ser vacias");
                        valid = false
                    }
                   
            });

             //validar los campos que se crean desde el boton #newOpcion
             $.each(Array.from({ length: cuantosVan + 1 }), function(num) {
                let tip = $(`input[name='opciones_${num}'`)
                let stado_tip = $(`select[name='estado_${num}'`)
                console.log("tip:", tip);
                if (tip.length > 0) {
                    console.log("tip.val():", tip.val());
                    if ((tip.val() === null)||(tip.val().trim() === "")||(tip.val().trim() === "0")) {
                        alertify.error("Debe Agregar Un Valor, Al Texto Que Se Mostrara En Las Tipificaciones");
                        valid = false
                        return false
                    }
                }

                if (stado_tip.length > 0) {
                    if ((stado_tip.val() === "0")||(stado_tip.val() === null)||(stado_tip.val() === "")) {
                        alertify.error("Debe Agregar Un Valor, Al Estado De Las Tipificaciones");
                        valid = false
                        return false
                    }
                }
            });

            if (!valid) {
                return
            }
            let booleanRes = before_save();
            if($("#tablaCAMCOM").getGridParam("reccount") == '0'){
                alertify.error("la tabla de la sección PRIORIDAD DE MARCACIÓN POR CADA NÚMERO DE TELÉFONO debe tener al menos una fila");
                $('.camcom-v').css('color','red');
                booleanRes = false;
            }
            if(booleanRes && requiredCampo()){
            
                let form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                let formData = new FormData($("#FormularioDatos")[0]);
                formData.append('contador', contador);
                formData.append('contarAsuntos', contadorAsuntos);
                formData.append('contadorASociaciones', longitudCaminc);
                if($('#G10_C332').val()!='-1'){    
                    formData.append('G10_C332', $('#G10_C332').val());
                }
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
                            alertify.success("<?php echo $str_exito_campanha;?>");


                            // PRIMERO REGENERAMOS LA VISTA DE LA MUESTRA
                            $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaMuestra=si',
                                type: 'POST',
                                data: { pasoId :  '<?php echo $_GET['id_paso']; ?>' },
                                dataType:'JSON'
                            });


                            $.ajax({
                                url : '<?=$url_crud_extender?>',
                                type : 'POST',
                                data :{validaMuestra :'si', id:$("#hidId").val()},
                                success:function(data){
                                    let callback='<?php if(isset($_GET['callback'])){echo 'si';}else{ echo 'no';}?>';
                                    if(data == '2' && callback == 'no'){
                                        /*
                                        swal({
                                            title: "Campaña sin registros",
                                            text: "Ya existen registros en la base de datos ¿quiere incluirlos en la campaña que está creando?",
                                            type: "warning",
                                            showCancelButton: true,
                                            confirmButtonClass: "btn-secondary",
                                            confirmButtonText: "Si, Continuar!",
                                            cancelButtonText: "No, Cancelar!",
                                            closeOnConfirm: true,
                                            closeOnCancel: true    
                                            },
                                            function(isConfirm) {
                                                if (isConfirm) {
                                                    $("#filtros_campanha").modal();
                                                    $("#sel_meter_sacar").val('1').trigger('change');
                                                } else {
                                                }
                                            }
                                        );*/
                                        <?php if(isset($_GET['ruta']) && $_GET['ruta'] == '1'){ ?>
                                        //window.location.href ="<?=base_url?>index.php?page=flujograma&estrategia=<?php // echo $datos['ESTPAS_ConsInte__ESTRAT_b']; ?>";
                                        window.location.href ="<?=base_url?>modulo/flujograma/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>";
                                        <?php }else{ ?>
                                        window.location.href = "<?=base_url?>modulo/seleccion/estrategias/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>";
                                        <?php } ?>                              
                                        <?php if(isset($_GET['id_paso'])){ ?>
                                            $.ajax({
                                                url      : '<?=$url_crud;?>',
                                                type     : 'POST',
                                                data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
                                                dataType : 'json',
                                                success  : function(data){
                                                    if(data.length > 0){
                                                        
                                                        //recorrer datos y enviarlos al formulario
                                                        $.each(data, function(i, item) {
                                                            //$("#G10_C70").val(item.G10_C70);
            
                                                            $("#G10_C71").val(item.G10_C71);
            
                                                            if(item.G10_C72 == '-1'){
                                                            if(!$("#G10_C72").is(':checked')){
                                                                $("#G10_C72").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C72").is(':checked')){
                                                                $("#G10_C72").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C73").val(item.G10_C73);
            
                                                            $("#G10_C73").val(item.G10_C73).trigger("change"); 
            
                                                            $("#G10_C74").val(item.G10_C74);
            
                                                            $("#G10_C74").val(item.G10_C74).trigger("change"); 
            
                                                            $("#G10_C75").val(item.G10_C75);
            
                                                            $("#G10_C76").val(item.G10_C76);
            
                                                            $("#G10_C76").val(item.G10_C76).trigger("change"); 
            
                                                            $("#G10_C77").val(item.G10_C77);
            
                                                            $("#G10_C77").val(item.G10_C77).trigger("change"); 
            
                                                            $("#G10_C105").val(item.G10_C105);
            
                                                            $("#G10_C106").val(item.G10_C106);
            
                                                            $("#G10_C107").val(item.G10_C107);
            
                                                            if(item.G10_C79 == '-1'){
                                                            if(!$("#G10_C79").is(':checked')){
                                                                $("#G10_C79").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C79").is(':checked')){
                                                                $("#G10_C79").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C80").val(item.G10_C80);
            
                                                            if(item.G10_C81 == '-1'){
                                                            if(!$("#G10_C81").is(':checked')){
                                                                $("#G10_C81").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C81").is(':checked')){
                                                                $("#G10_C81").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C82").val(item.G10_C82);
            
                                                            $("#G10_C83").val(item.G10_C83);
            
                                                            $("#G10_C84").val(item.G10_C84);
            
                                                            if(item.G10_C85 == '-1'){
                                                                $("#G10_C85").prop('checked', true);  
                                                            } else {
                                                                $("#G10_C85").prop('checked', false);  
                                                            }
            
                                                            $("#G10_C90").val(item.G10_C90);
            
                                                            $("#G10_C90").val(item.G10_C90).trigger("change"); 
            
                                                            $("#G10_C91").val(item.G10_C91);
            
                                                            $("#G10_C91").val(item.G10_C91).trigger("change"); 
            
                                                            $("#G10_C92").val(item.G10_C92);
            
                                                            $("#G10_C93").val(item.G10_C93);
            
                                                            $("#G10_C94").val(item.G10_C94);
            
                                                            if(item.G10_C95 == '1'){
                                                            if(!$("#G10_C95").is(':checked')){
                                                                $("#G10_C95").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C95").is(':checked')){
                                                                $("#G10_C95").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C98").val(item.G10_C98);
            
                                                            $("#G10_C98").val(item.G10_C98).trigger("change"); 
            
                                                            $("#G10_C99").val(item.G10_C99);
            
                                                            if(item.G10_C100 == '1'){
                                                            if(!$("#G10_C100").is(':checked')){
                                                                $("#G10_C100").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C100").is(':checked')){
                                                                $("#G10_C100").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C101").val(item.G10_C101);
            
                                                            $("#G10_C101").val(item.G10_C101).trigger("change"); 
            
                                                            $("#G10_C102").val(item.G10_C102);
            
                                                            $("#G10_C333").val(item.G10_C333);
            
                                                            $("#G10_C103").val(item.G10_C103);
            
                                                            $("#G10_C103").val(item.G10_C103).trigger("change"); 
            
                                                            $("#G10_C334").val(item.G10_C334).trigger("change"); 
            
                                                            $("#G10_C104").val(item.G10_C104);
            
                                                            $("#G10_C104").val(item.G10_C104).trigger("change"); 
            
                                                            if(item.G10_C108 == '-1'){
                                                            if(!$("#G10_C108").is(':checked')){
                                                                $("#G10_C108").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C108").is(':checked')){
                                                                $("#G10_C108").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C109").val(item.G10_C109);
            
                                                            $("#G10_C110").val(item.G10_C110);
            
                                                            if(item.G10_C111 == '-1'){
                                                            if(!$("#G10_C111").is(':checked')){
                                                                $("#G10_C111").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C111").is(':checked')){
                                                                $("#G10_C111").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C112").val(item.G10_C112);
            
                                                            $("#G10_C113").val(item.G10_C113);
            
                                                            if(item.G10_C114 == '-1'){
                                                            if(!$("#G10_C114").is(':checked')){
                                                                $("#G10_C114").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C114").is(':checked')){
                                                                $("#G10_C114").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C115").val(item.G10_C115);
            
                                                            $("#G10_C116").val(item.G10_C116);
            
                                                            if(item.G10_C117 == '-1'){
                                                            if(!$("#G10_C117").is(':checked')){
                                                                $("#G10_C117").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C117").is(':checked')){
                                                                $("#G10_C117").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C118").val(item.G10_C118);
            
                                                            $("#G10_C119").val(item.G10_C119);
            
                                                            if(item.G10_C120 == '-1'){
                                                            if(!$("#G10_C120").is(':checked')){
                                                                $("#G10_C120").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C120").is(':checked')){
                                                                $("#G10_C120").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C121").val(item.G10_C121);
            
                                                            $("#G10_C122").val(item.G10_C122);
            
                                                            if(item.G10_C123 == '-1'){
                                                            if(!$("#G10_C123").is(':checked')){
                                                                $("#G10_C123").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C123").is(':checked')){
                                                                $("#G10_C123").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C124").val(item.G10_C124);
            
                                                            $("#G10_C125").val(item.G10_C125);
            
                                                            if(item.G10_C126 == '-1'){
                                                            if(!$("#G10_C126").is(':checked')){
                                                                $("#G10_C126").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C126").is(':checked')){
                                                                $("#G10_C126").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C127").val(item.G10_C127);
            
                                                            $("#G10_C128").val(item.G10_C128);
            
                                                            if(item.G10_C129 == '-1'){
                                                            if(!$("#G10_C129").is(':checked')){
                                                                $("#G10_C129").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C129").is(':checked')){
                                                                $("#G10_C129").prop('checked', false);  
                                                                }
                                                                
                                                            }
                                                            
                                                            if(item.G10_C332 == '-1'){
                                                            $("#G10_C332").prop('checked', true);
                                                            $("#G10_C332").val('-1');    
                                                            }else{
                                                            $("#G10_C332").prop('checked', false);
                                                                $("#G10_C332").val('0');
                                                            }                                              
            
                                                            $("#G10_C130").val(item.G10_C130);
            
                                                            $("#G10_C131").val(item.G10_C131);
            
                                                            $("#G10_C331").val(item.G10_C331);
            
                                                            $("#tiempoInabilitadoBotonColgado").val(item.tiempoInabilitadoBotonColgado);
            
                                                            $("#G10_C326").val(item.G10_C326);
            
                                                            $("#h3mio").html(item.principal);
                                                            idTotal = item.G10_ConsInte__b;
            
                                                            if ( $("#"+idTotal).length > 0) {
                                                                $("#"+idTotal).click();   
                                                                $("#"+idTotal).addClass('active'); 
                                                            }else{
                                                                //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                                                                $(".CargarDatos :first").click();
                                                            }
            
                                                            
                                                            $("#hidId").val(item.G10_ConsInte__b);
            
                                                            if(item.G10_ConsInte__b.length > 0){
                                                                vamosRecargaLasGrillasPorfavor(item.G10_ConsInte__b)
                                                            }else{
                                                                vamosRecargaLasGrillasPorfavor(0)
                                                            }
            
                                                            $("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b); 
                                                            
                                                        });
                                                        //Deshabilitar los campo 
                                                    }
                                                } 
                                            });
                                            $("#oper").val('edit');
                                        <?php } ?>
                                    }else{
                                        <?php if(isset($_GET['ruta']) && $_GET['ruta'] == '1'){ ?>
                                        //window.location.href ="<?=base_url?>index.php?page=flujograma&estrategia=<?php // echo $datos['ESTPAS_ConsInte__ESTRAT_b']; ?>";
                                        window.location.href ="<?=base_url?>modulo/flujograma/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>";
                                        <?php }else{ ?>
                                        window.location.href = "<?=base_url?>modulo/seleccion/estrategias/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>";
                                        <?php } ?>                              
                                        <?php if(isset($_GET['id_paso'])){ ?>
                                            $.ajax({
                                                url      : '<?=$url_crud;?>',
                                                type     : 'POST',
                                                data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
                                                dataType : 'json',
                                                success  : function(data){
                                                    if(data.length > 0){
                                                        
                                                        //recorrer datos y enviarlos al formulario
                                                        $.each(data, function(i, item) {
                                                            //$("#G10_C70").val(item.G10_C70);
            
                                                            $("#G10_C71").val(item.G10_C71);
            
                                                            if(item.G10_C72 == '-1'){
                                                               if(!$("#G10_C72").is(':checked')){
                                                                   $("#G10_C72").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C72").is(':checked')){
                                                                   $("#G10_C72").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C73").val(item.G10_C73);
            
                                                            $("#G10_C73").val(item.G10_C73).trigger("change"); 
            
                                                            $("#G10_C74").val(item.G10_C74);
            
                                                            $("#G10_C74").val(item.G10_C74).trigger("change"); 
            
                                                            $("#G10_C75").val(item.G10_C75);
            
                                                            $("#G10_C76").val(item.G10_C76);
            
                                                            $("#G10_C76").val(item.G10_C76).trigger("change"); 
            
                                                            $("#G10_C77").val(item.G10_C77);
            
                                                            $("#G10_C77").val(item.G10_C77).trigger("change"); 
            
                                                            $("#G10_C105").val(item.G10_C105);
            
                                                            $("#G10_C106").val(item.G10_C106);
            
                                                            $("#G10_C107").val(item.G10_C107);
            
                                                            if(item.G10_C79 == '-1'){
                                                               if(!$("#G10_C79").is(':checked')){
                                                                   $("#G10_C79").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C79").is(':checked')){
                                                                   $("#G10_C79").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C80").val(item.G10_C80);
            
                                                            if(item.G10_C81 == '-1'){
                                                               if(!$("#G10_C81").is(':checked')){
                                                                   $("#G10_C81").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C81").is(':checked')){
                                                                   $("#G10_C81").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C82").val(item.G10_C82);
            
                                                            $("#G10_C83").val(item.G10_C83);
            
                                                            $("#G10_C84").val(item.G10_C84);
            
                                                            if(item.G10_C85 == '-1'){
                                                                $("#G10_C85").prop('checked', true);  
                                                            } else {
                                                                $("#G10_C85").prop('checked', false);  
                                                            }
            
                                                            $("#G10_C90").val(item.G10_C90);
            
                                                            $("#G10_C90").val(item.G10_C90).trigger("change"); 
            
                                                            $("#G10_C91").val(item.G10_C91);
            
                                                            $("#G10_C91").val(item.G10_C91).trigger("change"); 
            
                                                            $("#G10_C92").val(item.G10_C92);
            
                                                            $("#G10_C93").val(item.G10_C93);
            
                                                            $("#G10_C94").val(item.G10_C94);
            
                                                            if(item.G10_C95 == '1'){
                                                               if(!$("#G10_C95").is(':checked')){
                                                                   $("#G10_C95").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C95").is(':checked')){
                                                                   $("#G10_C95").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C98").val(item.G10_C98);
            
                                                            $("#G10_C98").val(item.G10_C98).trigger("change"); 
            
                                                            $("#G10_C99").val(item.G10_C99);
            
                                                            if(item.G10_C100 == '1'){
                                                               if(!$("#G10_C100").is(':checked')){
                                                                   $("#G10_C100").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C100").is(':checked')){
                                                                   $("#G10_C100").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C101").val(item.G10_C101);
            
                                                            $("#G10_C101").val(item.G10_C101).trigger("change"); 
            
                                                            $("#G10_C102").val(item.G10_C102);
            
                                                            $("#G10_C333").val(item.G10_C333);
            
                                                            $("#G10_C103").val(item.G10_C103);
            
                                                            $("#G10_C103").val(item.G10_C103).trigger("change"); 
            
                                                            $("#G10_C334").val(item.G10_C334).trigger("change"); 
            
                                                            $("#G10_C104").val(item.G10_C104);
            
                                                            $("#G10_C104").val(item.G10_C104).trigger("change"); 
            
                                                            if(item.G10_C108 == '-1'){
                                                               if(!$("#G10_C108").is(':checked')){
                                                                   $("#G10_C108").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C108").is(':checked')){
                                                                   $("#G10_C108").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C109").val(item.G10_C109);
            
                                                            $("#G10_C110").val(item.G10_C110);
            
                                                            if(item.G10_C111 == '-1'){
                                                               if(!$("#G10_C111").is(':checked')){
                                                                   $("#G10_C111").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C111").is(':checked')){
                                                                   $("#G10_C111").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C112").val(item.G10_C112);
            
                                                            $("#G10_C113").val(item.G10_C113);
            
                                                            if(item.G10_C114 == '-1'){
                                                               if(!$("#G10_C114").is(':checked')){
                                                                   $("#G10_C114").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C114").is(':checked')){
                                                                   $("#G10_C114").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C115").val(item.G10_C115);
            
                                                            $("#G10_C116").val(item.G10_C116);
            
                                                            if(item.G10_C117 == '-1'){
                                                               if(!$("#G10_C117").is(':checked')){
                                                                   $("#G10_C117").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C117").is(':checked')){
                                                                   $("#G10_C117").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C118").val(item.G10_C118);
            
                                                            $("#G10_C119").val(item.G10_C119);
            
                                                            if(item.G10_C120 == '-1'){
                                                               if(!$("#G10_C120").is(':checked')){
                                                                   $("#G10_C120").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C120").is(':checked')){
                                                                   $("#G10_C120").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C121").val(item.G10_C121);
            
                                                            $("#G10_C122").val(item.G10_C122);
            
                                                            if(item.G10_C123 == '-1'){
                                                               if(!$("#G10_C123").is(':checked')){
                                                                   $("#G10_C123").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C123").is(':checked')){
                                                                   $("#G10_C123").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C124").val(item.G10_C124);
            
                                                            $("#G10_C125").val(item.G10_C125);
            
                                                            if(item.G10_C126 == '-1'){
                                                               if(!$("#G10_C126").is(':checked')){
                                                                   $("#G10_C126").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C126").is(':checked')){
                                                                   $("#G10_C126").prop('checked', false);  
                                                                }
                                                                
                                                            }
            
                                                            $("#G10_C127").val(item.G10_C127);
            
                                                            $("#G10_C128").val(item.G10_C128);
            
                                                            if(item.G10_C129 == '-1'){
                                                               if(!$("#G10_C129").is(':checked')){
                                                                   $("#G10_C129").prop('checked', true);  
                                                                }
                                                            } else {
                                                                if($("#G10_C129").is(':checked')){
                                                                   $("#G10_C129").prop('checked', false);  
                                                                }
                                                                
                                                            }
                                                            
                                                            if(item.G10_C332 == '-1'){
                                                               $("#G10_C332").prop('checked', true);
                                                               $("#G10_C332").val('-1');    
                                                            }else{
                                                               $("#G10_C332").prop('checked', false);
                                                                $("#G10_C332").val('0');
                                                            }                                              
            
                                                            $("#G10_C130").val(item.G10_C130);
            
                                                            $("#G10_C131").val(item.G10_C131);
            
                                                            $("#G10_C331").val(item.G10_C331);
            
                                                            $("#tiempoInabilitadoBotonColgado").val(item.tiempoInabilitadoBotonColgado);
            
                                                            $("#G10_C326").val(item.G10_C326);
            
                                                            $("#h3mio").html(item.principal);
                                                            idTotal = item.G10_ConsInte__b;
            
                                                            if ( $("#"+idTotal).length > 0) {
                                                                $("#"+idTotal).click();   
                                                                $("#"+idTotal).addClass('active'); 
                                                            }else{
                                                                //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                                                                $(".CargarDatos :first").click();
                                                            }
            
                                                            
                                                            $("#hidId").val(item.G10_ConsInte__b);
            
                                                            if(item.G10_ConsInte__b.length > 0){
                                                                vamosRecargaLasGrillasPorfavor(item.G10_ConsInte__b)
                                                            }else{
                                                                vamosRecargaLasGrillasPorfavor(0)
                                                            }
            
                                                            $("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b); 
                                                            
                                                        });
                                                        //Deshabilitar los campo 
                                                    }
                                                } 
                                            });
                                            $("#oper").val('edit');
                                        <?php } ?>
                                    }
                                },
                                error: function(){
                                    alertify.error('<?php echo $error_de_red; ?>');
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
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    }

                });
            }else{

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

    /**
    * function que elimina el log de los registros recien cargados
    */
    function borrarLogCargue(){
         $.ajax({
                url: '<?=$url_crud;?>',
                type  : 'post',
                data: {'opcion':'borrarLogCargue'},
                dataType : 'json',
                success:function(data){
                    
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
                        

                        //$("#G10_C70").val(item.G10_C70);

                        $("#G10_C71").val(item.G10_C71);
    
                        if(item.G10_C72 == '-1'){
                           if(!$("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C73").val(item.G10_C73);
 
                        $("#G10_C73").val(item.G10_C73).trigger("change"); 

                        $("#G10_C74").val(item.G10_C74);
 
                        $("#G10_C74").val(item.G10_C74).trigger("change"); 

                        $("#G10_C75").val(item.G10_C75);

                        $("#G10_C76").val(item.G10_C76);
 
                        $("#G10_C76").val(item.G10_C76).trigger("change"); 

                        $("#G10_C77").val(item.G10_C77);
 
                        $("#G10_C77").val(item.G10_C77).trigger("change"); 

                        $("#G10_C105").val(item.G10_C105);

                        $("#G10_C106").val(item.G10_C106);

                        $("#G10_C107").val(item.G10_C107);
    
                        if(item.G10_C79 == '-1'){
                           if(!$("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C80").val(item.G10_C80);
    
                        if(item.G10_C81 == '-1'){
                           if(!$("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C82").val(item.G10_C82);

                        $("#G10_C83").val(item.G10_C83);

                        $("#G10_C84").val(item.G10_C84);
    
                        if(item.G10_C85 == '-1'){
                            $("#G10_C85").prop('checked', true);  
                        } else {
                            $("#G10_C85").prop('checked', false);  
                        }

                        $("#G10_C90").val(item.G10_C90);
 
                        $("#G10_C90").val(item.G10_C90).trigger("change"); 

                        $("#G10_C91").val(item.G10_C91);
 
                        $("#G10_C91").val(item.G10_C91).trigger("change"); 

                        $("#G10_C92").val(item.G10_C92);

                        $("#G10_C93").val(item.G10_C93);

                        $("#G10_C94").val(item.G10_C94);
    
                        if(item.G10_C95 == '1'){
                           if(!$("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C98").val(item.G10_C98);
 
                        $("#G10_C98").val(item.G10_C98).trigger("change"); 

                        $("#G10_C99").val(item.G10_C99);
    
                        if(item.G10_C100 == '1'){
                           if(!$("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C101").val(item.G10_C101);
 
                        $("#G10_C101").val(item.G10_C101).trigger("change"); 

                        $("#G10_C102").val(item.G10_C102);

                        $("#G10_C333").val(item.G10_C333);

                        $("#G10_C103").val(item.G10_C103);
 
                        $("#G10_C103").val(item.G10_C103).trigger("change"); 

                        $("#G10_C334").val(item.G10_C334).trigger("change"); 

                        $("#G10_C104").val(item.G10_C104);
 
                        $("#G10_C104").val(item.G10_C104).trigger("change"); 
    
                        if(item.G10_C108 == '1'){
                           if(!$("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', false);  
                            }
                            
                        }


                        $("#G10_C109").val(item.G10_C109);

                        $("#G10_C110").val(item.G10_C110);
    
                        if(item.G10_C111 == '1'){
                           if(!$("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C112").val(item.G10_C112);

                        $("#G10_C113").val(item.G10_C113);

                        if(item.G10_C114 == '1'){
                           if(!$("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C115").val(item.G10_C115);

                        $("#G10_C116").val(item.G10_C116);        
    
                        if(item.G10_C117 == '1'){
                           if(!$("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C118").val(item.G10_C118);

                        $("#G10_C119").val(item.G10_C119);
    
                        if(item.G10_C120 == '1'){
                           if(!$("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C121").val(item.G10_C121);

                        $("#G10_C122").val(item.G10_C122);
   
                        if(item.G10_C123 == '1'){
                           if(!$("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C124").val(item.G10_C124);

                        $("#G10_C125").val(item.G10_C125);
    
                        if(item.G10_C126 == '1'){
                           if(!$("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C127").val(item.G10_C127);

                        $("#G10_C128").val(item.G10_C128);

                        if(item.G10_C129 == '1'){
                           if(!$("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C130").val(item.G10_C130);

                        $("#G10_C131").val(item.G10_C131);
                        
                        if(item.G10_C332 == '-1'){
                           $("#G10_C332").prop('checked', true);
                           $("#G10_C332").val('-1');    
                        }else{
                           $("#G10_C332").prop('checked', false);
                            $("#G10_C332").val('0');
                        }                        
        
                        $("#h3mio").html(item.principal);

                        $("#id_estpas").val(item.id_estpas);

                        $("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idHuesped=<?php echo $_SESSION['HUESPED'];?>&idCampan='+ item.G10_ConsInte__b);
                    });
        

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function(){
                        $(this).attr('disabled', true);
                    });

                    /* convocar los agentes */
                   

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });
            vamosRecargaLasGrillasPorfavor(id);
            $("#hidId").val(id);
            idTotal = $("#hidId").val();
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
                        

                        //$("#G10_C70").val(item.G10_C70);

                        $("#G10_C71").val(item.G10_C71);
    
                        if(item.G10_C72 == '-1'){
                           if(!$("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C73").val(item.G10_C73);
 
                        $("#G10_C73").val(item.G10_C73).trigger("change"); 

                        $("#G10_C74").val(item.G10_C74);
 
                        $("#G10_C74").val(item.G10_C74).trigger("change"); 

                        $("#G10_C75").val(item.G10_C75);

                        $("#G10_C76").val(item.G10_C76);
 
                        $("#G10_C76").val(item.G10_C76).trigger("change"); 

                        $("#G10_C77").val(item.G10_C77);
 
                        $("#G10_C77").val(item.G10_C77).trigger("change"); 

                        $("#G10_C105").val(item.G10_C105);

                        $("#G10_C106").val(item.G10_C106);

                        $("#G10_C107").val(item.G10_C107);
    
                        if(item.G10_C79 == '-1'){
                           if(!$("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C80").val(item.G10_C80);
    
                        if(item.G10_C81 == '-1'){
                           if(!$("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C82").val(item.G10_C82);

                        $("#G10_C83").val(item.G10_C83);

                        $("#G10_C84").val(item.G10_C84);
    
                        if(item.G10_C85 == '-1'){
                            $("#G10_C85").prop('checked', true);  
                        } else {
                            $("#G10_C85").prop('checked', false);  
                        }

                        $("#G10_C90").val(item.G10_C90);
 
                        $("#G10_C90").val(item.G10_C90).trigger("change"); 

                        $("#G10_C91").val(item.G10_C91);
 
                        $("#G10_C91").val(item.G10_C91).trigger("change"); 

                        $("#G10_C92").val(item.G10_C92);

                        $("#G10_C93").val(item.G10_C93);

                        $("#G10_C94").val(item.G10_C94);
    
                        if(item.G10_C95 == '-1'){
                           if(!$("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C98").val(item.G10_C98);
 
                        $("#G10_C98").val(item.G10_C98).trigger("change"); 

                        $("#G10_C99").val(item.G10_C99);
    
                        if(item.G10_C100 == '-1'){
                           if(!$("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C101").val(item.G10_C101);
 
                        $("#G10_C101").val(item.G10_C101).trigger("change"); 

                        $("#G10_C102").val(item.G10_C102);

                        $("#G10_C333").val(item.G10_C333);

                        $("#G10_C103").val(item.G10_C103);
 
                        $("#G10_C103").val(item.G10_C103).trigger("change"); 

                        $("#G10_C334").val(item.G10_C334).trigger("change"); 

                        $("#G10_C104").val(item.G10_C104);
 
                        $("#G10_C104").val(item.G10_C104).trigger("change"); 
    
                        if(item.G10_C108 == '-1'){
                           if(!$("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C109").val(item.G10_C109);

                        $("#G10_C110").val(item.G10_C110);
    
                        if(item.G10_C111 == '-1'){
                           if(!$("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C112").val(item.G10_C112);

                        $("#G10_C113").val(item.G10_C113);
    
                        if(item.G10_C114 == '-1'){
                           if(!$("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C115").val(item.G10_C115);

                        $("#G10_C116").val(item.G10_C116);
    
                        if(item.G10_C117 == '-1'){
                           if(!$("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C118").val(item.G10_C118);

                        $("#G10_C119").val(item.G10_C119);
    
                        if(item.G10_C120 == '-1'){
                           if(!$("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C121").val(item.G10_C121);

                        $("#G10_C122").val(item.G10_C122);
    
                        if(item.G10_C123 == '-1'){
                           if(!$("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C124").val(item.G10_C124);

                        $("#G10_C125").val(item.G10_C125);
    
                        if(item.G10_C126 == '-1'){
                           if(!$("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C127").val(item.G10_C127);

                        $("#G10_C128").val(item.G10_C128);
    
                        if(item.G10_C129 == '-1'){
                           if(!$("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C130").val(item.G10_C130);

                        $("#G10_C131").val(item.G10_C131);
                        
                        if(item.G10_C332 == '-1'){
                           $("#G10_C332").prop('checked', true);
                           $("#G10_C332").val('-1');    
                        }else{
                           $("#G10_C332").prop('checked', false);
                            $("#G10_C332").val('0');
                        }
                        
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


    function cargarHijos_0(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tablaDatosDetalless0").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_0=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','TIPOS DE DESTINO','TRONCAL','TRONCAL DE DESBORDE', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }
 
                ,
                {   name:'troncal', 
                    index:'troncal', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_troncales=si',
                        dataInit:function(el){
                        
                        }
                    }

                }
 
                ,
                {  
                    name:'troncal_desborde', 
                    index:'troncal_desborde', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_troncales2=si',
                        dataInit:function(el){
                            
                        }
                    }
                }
 
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles0",
            rowList: [40,80],
            sortable: true,
            sortname: 'tipos_destino',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'PASOS TRONCALES',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&Id_paso="+$("#id_estpas").val(),
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tablaDatosDetalless0').navGrid("#pagerDetalles0", { add:false, del: true , edit: false });


        $('#tablaDatosDetalless0').inlineNav('#pagerDetalles0',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $('#tablaDatosDetalless0').jqGrid('editRow', id, { keys: true,  
            aftersavefunc: function (rowid, response) { alert('after save'); }, 
            errorfunc: function (rowid, response) { alert('...we have a problem'); }  
        }); 

        $(window).bind('resize', function() {
            $("#tablaDatosDetalless0").setGridWidth($(window).width());
        }).trigger('resize');
    }


    function cargarCamcom(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        var id_guion = $("#G10_C74").val();
        $("#tablaCAMCOM").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_camcom=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','<?php echo $campan_dbtel_1_;?>','<?php echo $campan_dbtel_2_;?>', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }
                
                ,
                { 
                    name:'G19_C202', 
                    index: 'G19_C202', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?obtener_nombres_campos_tel=si&guion='+ id_guion +'&campo=G19_C202',
                        dataInit:function(el){
                        
                        }
                    }
                }
 
                ,
                {   name:'G19_C203', 
                    index:'G19_C203', 
                    width:300 ,
                    editable: true

                }
 
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerCAMCOM",
            rowList: [40,80],
            sortable: true,
            sortname: 'G19_C202',
            sortorder: 'asc',
            viewrecords: true,
            caption: '',
            editurl:"<?=$url_crud;?>?insertarDatosCamcom=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            afterInsertRow: function (){
                $('.camcom-v').css('color','#72afd2');
            },            
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tablaCAMCOM').navGrid("#pagerCAMCOM", { add:false, del: true , edit: false });


        $('#tablaCAMCOM').inlineNav('#pagerCAMCOM',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $('#tablaCAMCOM').navButtonAdd("#pagerCAMCOM", {
            caption:"",
            buttonicon:"glyphicon-th-list",
            onClickButton: showModalVisorOrdenaminto ,
            position: "last",
            title:"Mostrar ordenamiento de los siguientes registros",
            id : "",
            cursor: "pointer"
        });

        $(window).bind('resize', function() {
            $("#tablaCAMCOM").setGridWidth($(window).width());
        }).trigger('resize');
    }



    function cargarCamord(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';

        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        var id_guion = $("#G10_C74").val();
        $("#tablaCAMORD").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_camord=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','<?php echo $campan_orden_1_; ?>','<?php echo $campan_orden_2_; ?>', '<?php echo $campan_orden_3_; ?>', '<?php echo $campan_orden_4_; ?>', '<?php echo $campan_orden_5_; ?>',  'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }
                
                ,
                { 
                    name:'CAMORD_MUESPOBL__B', 
                    index: 'CAMORD_MUESPOBL__B', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?Llenar_datos_combo_1_ordenamiento=si&guion='+ id_guion +'&campo=CAMORD_MUESPOBL__B',
                        dataInit:function(el){
                            /*$(el).change(function(){
                                alert($(this).val());
                                if($(this).val() == 'M'){
                                    $("#jqg1_CAMORD_MUESCAMP__B").attr('enabled', false);
                                    $("#jqg1_CAMORD_POBLCAMP__B").attr('enabled', true);
                                }

                                if($(this).val() == 'P'){
                                    $("#jqg1_CAMORD_POBLCAMP__B").attr('enabled', false);
                                    $("#jqg1_CAMORD_MUESCAMP__B").attr('enabled', true);
                                }
                            });*/
                            
                        },
                        dataEvents: [
                            {  
                                type: 'change',
                                fn: function(e) {
                                    var r = this.id;
                                    var rId = r.split('_');
                                    //console.log(this);
                                    if(this.value == 'M'){
                                        $("#"+ rId[0] +"_CAMORD_MUESCAMP__B").show();
                                        $("#"+ rId[0] +"_CAMORD_POBLCAMP__B").hide();
                                    }

                                    if(this.value == 'P'){
                                        $("#"+ rId[0] +"_CAMORD_MUESCAMP__B").hide();
                                        $("#"+ rId[0] +"_CAMORD_POBLCAMP__B").show();
                                    }
                                }
                            }
                        ]
                    }
                }
                
                ,
                {   

                    name:'CAMORD_POBLCAMP__B', 
                    index:'CAMORD_POBLCAMP__B', 
                    width:300 ,
                    editable: true,
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?obtener_nombres_campos_ordenamiento=si&guion='+ id_guion +'&campo=CAMORD_MUESPOBL__B',
                        dataInit:function(el){
                        
                        },dataEvents : [
                        {
                            type : 'change',
                            fn : function(e){
                                var strId_t = $(this).attr("id");
                                var intId_t = strId_t;
                                intId_t = intId_t.split("_");
                                intId_t = intId_t[0];
                                var intTipo_t = $("#"+strId_t+" option:selected").attr("tipo");

                                    strHTML_t = '<option value="0">Seleccione</option>';
                                    strHTML_t += '<option value="ASC">ASCENDENTE</option>';
                                    strHTML_t += '<option value="DESC">DESCENDENTE</option>';

                                if (intTipo_t == 1 || intTipo_t == 2 || intTipo_t == 14) {

                                    strHTML_t = '<option value="0">Seleccione</option>';
                                    strHTML_t += '<option value="ASC">A - Z</option>';
                                    strHTML_t += '<option value="DESC">Z - A</option>';

                                }else if(intTipo_t == 3 || intTipo_t == 4){

                                    strHTML_t = '<option value="0">Seleccione</option>';
                                    strHTML_t += '<option value="ASC">MENOR A MAYOR</option>';
                                    strHTML_t += '<option value="DESC">MAYOR A MENOR</option>';

                                }else if(intTipo_t == 5){

                                    strHTML_t = '<option value="0">Seleccione</option>';
                                    strHTML_t += '<option value="ASC">ANTIGUA A RECIENTE</option>';
                                    strHTML_t += '<option value="DESC">RECIENTE A ANTIGUA</option>';

                                }

                                $("#"+intId_t+"_CAMORD_ORDEN_____B").html(strHTML_t);
                            }
                        }
                    ]
                    }

                }

                ,
                {   name:'CAMORD_MUESCAMP__B', 
                    index:'CAMORD_MUESCAMP__B', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?Llenar_datos_combo_2_ordenamiento=si&guion='+ id_guion +'&campo=CAMORD_POBLCAMP__B',
                        dataInit:function(el){
                        
                        },dataEvents : [
                        {
                                type : 'change',
                                fn : function(e){
                                    var strId_t = $(this).attr("id");
                                    var intId_t = strId_t;
                                    intId_t = intId_t.split("_");
                                    intId_t = intId_t[0];
                                    var intTipo_t = $("#"+strId_t+" option:selected").attr("tipo");

                                        strHTML_t = '<option value="0">Seleccione</option>';
                                        strHTML_t += '<option value="ASC">ASCENDENTE</option>';
                                        strHTML_t += '<option value="DESC">DESCENDENTE</option>';

                                    if (intTipo_t == 1 || intTipo_t == 2 || intTipo_t == 14) {

                                        strHTML_t = '<option value="0">Seleccione</option>';
                                        strHTML_t += '<option value="ASC">A - Z</option>';
                                        strHTML_t += '<option value="DESC">Z - A</option>';

                                    }else if(intTipo_t == 3 || intTipo_t == 4){

                                        strHTML_t = '<option value="0">Seleccione</option>';
                                        strHTML_t += '<option value="ASC">MENOR A MAYOR</option>';
                                        strHTML_t += '<option value="DESC">MAYOR A MENOR</option>';

                                    }else if(intTipo_t == 5){

                                        strHTML_t = '<option value="0">Seleccione</option>';
                                        strHTML_t += '<option value="ASC">ANTIGUA A RECIENTE</option>';
                                        strHTML_t += '<option value="DESC">RECIENTE A ANTIGUA</option>';

                                    }

                                    $("#"+intId_t+"_CAMORD_ORDEN_____B").html(strHTML_t);
                                }
                            }
                        ]
                    }

                }


                ,
                {   name:'CAMORD_PRIORIDAD_B', 
                    index:'CAMORD_PRIORIDAD_B', 
                    width:300 ,
                    editable: true

                }

                ,
                {   name:'CAMORD_ORDEN_____B', 
                    index:'CAMORD_ORDEN_____B', 
                    width:300 ,
                    editable: true,
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?Llenar_datos_combo_3_ordenamiento=si&guion='+ id_guion +'&campo=CAMORD_ORDEN_____B',
                        dataInit:function(el){
                        
                        }
                    }
                }

                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerCAMORD",
            rowList: [40,80],
            sortable: true,
            sortname: 'CAMORD_ORDEN_____B',
            sortorder: 'asc',
            viewrecords: true,
            caption: '',
            editurl:"<?=$url_crud;?>?insertarDatosCamord=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tablaCAMORD').navGrid("#pagerCAMORD", { add:false, del: true , edit: false });


        $('#tablaCAMORD').inlineNav('#pagerCAMORD',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaCAMCOM").setGridWidth($(window).width());
        }).trigger('resize');
    }


    function cargarUsuariosCampan(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tablaUsuariosCampan").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_UsuariosCampan=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','AGENTE', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }
                
                ,
                { 
                    name:'USUARI_Nombre____b', 
                    index: 'USUARI_Nombre____b', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?Llenar_datos_Combo_usuarios=si&campan='+idTotal,
                        dataInit:function(el){
                                
                        }
                    }
                }
 
 
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerUsuariosCampan",
            rowList: [40,80],
            sortable: true,
            sortname: 'USUARI_Nombre____b',
            sortorder: 'asc',
            viewrecords: true,
            caption: '',
            editurl:"<?=$url_crud;?>?insertarDatosUsuarioCampan=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tablaUsuariosCampan').navGrid("#pagerUsuariosCampan", { add:false, del: true , edit: false });


        $('#tablaUsuariosCampan').inlineNav('#pagerUsuariosCampan',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: false, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaCAMCOM").setGridWidth($(window).width());
        }).trigger('resize');
    }


    function vamosRecargaLasGrillasPorfavor(id){
        $.jgrid.gridUnload('#tablaDatosDetalless0');
        // $.jgrid.gridUnload('#tablaCAMCOM'); 
        // $.jgrid.gridUnload('#tablaCAMORD');
        //$.jgrid.gridUnload('#tablaUsuariosCampan');  
        cargarHijos_0(id, $("#id_estpas").val());
        cargarCamcom(id);
        //cargarCaminc(id);
        // cargarCamord(id);
        //cargarUsuariosCampan(id);
    }
</script>

<script type="text/javascript">
    functionsGuion = {
        cargarDatos : function(idGuion){
            $.ajax({
                url      : '<?=$url_crud_extender?>?llenarDatosPregun=true',
                type     : 'post',
                data     : { guion  :  idGuion },
                dataType : 'json',
                success  : function(data){
                    datosGuion = data;
                    //console.log(datosGuion);
                }
            })
        }

        ,

        cargarScript : function(idGuion){
            $.ajax({
                url      : '<?=$url_crud_extender?>?llenarDatosPregun=true',
                type     : 'post',
                data     : { guion  :  idGuion },
                dataType : 'json',
                success  : function(data){
                    datosForms = data;
                    //console.log(datosGuion);
                }
            })
        }
    }

    function getCampanasEnviar(){
        $.ajax({
            url    : '<?=$url_crud_extender?>',
            type   : 'post',
            data   : { 
                getEnvioCorreoFormulario : true, 
                idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>' , 
                idCampana : $("#hidId2").val()
            },
            success :  function (data){
                $("#cuerpoDeEnvioFormulario").html(data);

                $("#btnCrearCampanaEnviar").click(function(){
                    $.ajax({
                        url     : '<?=$url_crud_extender?>',
                        type    : 'post',
                        data    : {
                            crearPasoModificarEstrategia : true,
                            idCampana   : $("#hidId2").val(),
                            idPaso      : '<?php echo $_GET['id_paso']; ?>'
                        },
                        dataType : 'json',
                        beforeSend : function(){
                            $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        },
                        success : function(data){
                            if(data.code == '0'){
                                $.ajax({
                                    url  : '<?=base_url?>mostrar_popups.php?view=mail&id_paso='+data.numberPaso+'&poblacion=<?php echo $_GET['poblacion']; ?>&modocampana='+$("#hidId2").val()+"&id_formulario="+$("#G10_C73").val(),
                                    type : 'get',
                                    success : function(data){
                                        $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>');
                                        $("#CargarParaEdicion").html(data);
                                        $("#editarDatos").modal();
                                    },
                                    beforeSend : function(){
                                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                                    },
                                    complete : function(){
                                        $.unblockUI();
                                    }
                                });
                            }else{
                                alertify.error('<?php $error_de_proceso; ?>');
                            }
                            console.log(data);
                        }
                    });
                });

                $(".borrarMail").click(function(){
                    var id = $(this).attr('idCorreo');
                    alertify.confirm("<?php echo $str_message_generico_D;?> ?", function (e) {
                        //Si la persona acepta
                        if (e) {
                            $.ajax({
                                url    : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G14/G14_CRUD.php?insertarDatosGrilla=si',
                                type   : 'post',
                                data   : { 
                                    oper    : 'del', 
                                    id      : id
                                },
                                success :  function (data){
                                    getCampanasEnviar();
                                },
                                beforeSend : function(){
                                    $.blockUI({
                                        baseZ: 2000, 
                                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                                    });
                                },
                                complete : function(){
                                    $.unblockUI();
                                }
                            })
                            
                        } else {
                            
                        }
                    }); 
                   
                });

                $(".editarMail").click(function(){
                    var id = $(this).attr('idCorreo');
                    $.ajax({
                        url  : '<?=base_url?>mostrar_popups.php?view=mail&id_paso='+id+'&poblacion=<?php echo $_GET['poblacion']; ?>&modocampana='+$("#hidId2").val()+"&id_formulario="+$("#G10_C74").val(),
                        type : 'get',
                        success : function(data){
                            $("#title_estrat").html('<?php echo $str_strategia_edicion.' '; ?>');
                            $("#CargarParaEdicion").html(data);

                            $("#editarDatos").modal();
                        },
                        beforeSend : function(){
                            $.blockUI({
                                baseZ: 2000, 
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' 
                            });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                });
            }
        });
    }


    function showModalVisorOrdenaminto() {
        showModalVisorColaMArcador("Ordenamiento")
      }

    // Esta funcion se llama desde la tabla de ordenamiento y es para mostrar el modal de las barras azules en la pestaña de ordenamiento
    function showModalVisorColaMArcador(modal) {

        const idCampan = $("#hidId").val();
        const idEstpas = $("#id_estpas").val();
        const typeCampan = $("#G10_C76").val();

        $("#ifrVisorColaMArcador").attr("src","<?=base_url?>pages/Dashboard/reportMarcador.php?Campan="+idCampan+"&Estpas="+idEstpas+"&tipoMarc="+typeCampan+"&tab="+modal);

        $("#modVisorColaMArcador").modal();
      }

    $(document).ready(function() {

        var strHTMLCamordBD_t = '';
        var strHTMLCamordMT_t = '';

        function camordTipo(intCamordTipo_p){

            var arrCamordTipo_t = new Array();
            arrCamordTipo_t[0]="ASCENDENTE"; 
            arrCamordTipo_t[1]="DESCENDENTE"; 

            switch(intCamordTipo_p){

                case "1":

                    arrCamordTipo_t[0] = "A - Z";
                    arrCamordTipo_t[1] = "Z - A";

                    break;

                case "3":

                    arrCamordTipo_t[0] = "MENOR A MAYOR";
                    arrCamordTipo_t[1] = "MAYOR A MENOR";

                    break;

                case "4":

                    arrCamordTipo_t[0] = "MENOR A MAYOR";
                    arrCamordTipo_t[1] = "MAYOR A MENOR";

                    break;

                case "5":

                    arrCamordTipo_t[0] = "ANTIGUA A RECIENTE";
                    arrCamordTipo_t[1] = "RECIENTE A ANTIGUA";

                    break;

            }  
                  
            return arrCamordTipo_t;

        }

        $("#camord_guardar").click(function(){

            booPrioridad_t = true;

            var arrIdRows_t = new Array();

            $(".camord_prioridad").each(function(index) {

                arrIdRows_t[index] = $(this).attr("rowid");

                if ($(this).val() == "") {

                    booPrioridad_t = false;
                    $(this).closest(".form-group").addClass("has-error");

                }

                
            });

            if (!booPrioridad_t) {

                alertify.error("Debe diligenciar las prioridades.");

            }else{

                var form = $("#divParametrosEstrategiaMarcacion .responsiveDiv .form-group > input, #divParametrosEstrategiaMarcacion .responsiveDiv .form-group > select").serialize();

                $.ajax({
                    url : '<?=$url_crud;?>?camord_guardar=true&arrIdRows_t='+arrIdRows_t+'&intIdCampan_t='+idTotal,
                    type : 'post',
                    data : form,
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>'
                         });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success : function (data){
                        console.log(data);
                    }
                });

            }
            
        });

        $("#camord_refrescar").click(function(){

            estrategiaCamord();
            
        });


        $("#camord_nuevo").click(function(){

            intICamord = (Number($("#camord_cantidad").val())+1);

            strHTMLCamord_t = '<tr class="camord_row" id="camord_row_'+intICamord+'">';
            // -> MARCACIÓN BASADA EN DATOS
                strHTMLCamord_t += '<td class="responsiveDiv">';
                    strHTMLCamord_t += '<div class="form-group">';
                        strHTMLCamord_t += '<select class="form-control camord_basado" name="camord_basado_'+intICamord+'" id="camord_basado_'+intICamord+'" idRow="'+intICamord+'">';
                            strHTMLCamord_t += '<option value="P" >De la base de datos</option>';
                            strHTMLCamord_t += '<option value="M" >De la campaña</option>';
                        strHTMLCamord_t += '</select>';
                    strHTMLCamord_t += '</div>';
                strHTMLCamord_t += '</td>';

                strHTMLCamord_t += '<td class="responsiveDiv">';
                    strHTMLCamord_t += '<div class="form-group">';
                        strHTMLCamord_t += '<select class="form-control camord_parametro" name="camord_base_'+intICamord+'" id="camord_base_'+intICamord+'" idRow="'+intICamord+'"></select>';
                    strHTMLCamord_t += '</div>';
                strHTMLCamord_t += '</td>';

                strHTMLCamord_t += '<td class="responsiveDiv">';
                    strHTMLCamord_t += '<div class="form-group">';
                        strHTMLCamord_t += '<select class="form-control camord_parametro" name="camord_campana_'+intICamord+'" id="camord_campana_'+intICamord+'" idRow="'+intICamord+'"></select>';
                    strHTMLCamord_t += '</div>';
                strHTMLCamord_t += '</td>';

                strHTMLCamord_t += '<td class="responsiveDiv">';
                    strHTMLCamord_t += '<div class="form-group">';

                        intCamordPrioridad_t = 1;

                        $(".camord_prioridad").each(function(index) {
                            $(this).val() > intCamordPrioridad_t ? intCamordPrioridad_t = $(this).val() : false
                        });

                        intCamordPrioridad_t = (Number(intCamordPrioridad_t)+1);

                        strHTMLCamord_t += '<input type="number" class="inline-edit-cell form-control camord_prioridad" id="camord_prioridad_'+intICamord+'" name="camord_prioridad_'+intICamord+'" rowid="'+intICamord+'" value="'+intCamordPrioridad_t+'" >';
                    strHTMLCamord_t += '</div>';
                strHTMLCamord_t += '</d>';

                strHTMLCamord_t += '<td class="responsiveDiv">';
                    strHTMLCamord_t += '<div class="form-group">';
                        strHTMLCamord_t += '<select class="form-control" name="camord_orden_'+intICamord+'" id="camord_orden_'+intICamord+'" idRow="'+intICamord+'">';

                            arrCamordOrden_t = camordTipo(false); 

                            strHTMLCamord_t += '<option value="ASC" >'+arrCamordOrden_t[0]+'</option>';
                            strHTMLCamord_t += '<option value="DESC" >'+arrCamordOrden_t[1]+'</option>';

                        strHTMLCamord_t += '</select>';
                    strHTMLCamord_t += '</div>';
                strHTMLCamord_t += '</td>';

                strHTMLCamord_t += '<td class="responsiveDiv">';
                    strHTMLCamord_t += '<div class="form-group">';
                    strHTMLCamord_t += '<button class="form-control btn btn-danger btn-sm eliminarCamord" type="button" name="eliminarCamord_'+intICamord+'" id="eliminarCamord_'+intICamord+'" idRow="'+intICamord+'"><i class="fa fa-trash-o"></i></button>';
                    strHTMLCamord_t += '<input type="hidden" name="camord_id_'+intICamord+'" id="camord_id_'+intICamord+'" value="-1">';
                    strHTMLCamord_t += '</div>';
                strHTMLCamord_t += '</td>';

            strHTMLCamord_t += '</tr>';

            $("#divParametrosEstrategiaMarcacion").append(strHTMLCamord_t);

            $(".camord_parametro").change(function(){

                intRow_t = $(this).attr("idRow");
                intCamordTipo_t = $("option:selected",this).attr("tipo");

                arrCamordOrden_t = camordTipo(intCamordTipo_t);

                $("#camord_orden_"+intRow_t).html('<option value="ASC" >'+arrCamordOrden_t[0]+'</option><option value="DESC" >'+arrCamordOrden_t[1]+'</option>'); 



            });

            $("#camord_base_"+intICamord).html(strHTMLCamordBD_t);

            arrCamordOrden_t = camordTipo("3");

            $("#camord_orden_"+intICamord).html('<option value="ASC" >'+arrCamordOrden_t[0]+'</option><option value="DESC" >'+arrCamordOrden_t[1]+'</option>'); 

            $(".camord_basado").change(function(){

                intRow_t = $(this).attr("idRow");
                strBasado_t = $(this).val(); 

                if (strBasado_t == "P") {

                    $("#camord_campana_"+intRow_t).html('');
                    $("#camord_base_"+intRow_t).html(strHTMLCamordBD_t);

                    arrCamordOrden_t = camordTipo($("#camord_base_"+intRow_t+" option:selected").attr("tipo"));

                    $("#camord_orden_"+intRow_t).html('<option value="ASC" >'+arrCamordOrden_t[0]+'</option><option value="DESC" >'+arrCamordOrden_t[1]+'</option>'); 

                }else if (strBasado_t == "M") {

                    $("#camord_base_"+intRow_t).html('');
                    $("#camord_campana_"+intRow_t).html(strHTMLCamordMT_t);

                    arrCamordOrden_t = camordTipo($("#camord_campana_"+intRow_t+" option:selected").attr("tipo"));

                    $("#camord_orden_"+intRow_t).html('<option value="ASC" >'+arrCamordOrden_t[0]+'</option><option value="DESC" >'+arrCamordOrden_t[1]+'</option>'); 

                }

            });

            $(".eliminarCamord").click(function(){

                intRow_t = $(this).attr("idRow");

                $("#camord_row_"+intRow_t).remove();

            });

            $("#camord_cantidad").val(Number(intICamord));

        });

        function estrategiaCamord(){

                $.ajax({
                    url   : '<?=$url_crud;?>?callDatosSubgrilla_camord=true',
                    type  : 'post',
                    data  : {intIdCampan_t : idTotal},
                    dataType : 'json',
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                        });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success : function(objCarmord_p){

                        strHTMLCamordBD_t = $.ajax({
                                                    url: '<?=$url_crud_extender?>',
                                                    type:'post',
                                                    dataType : 'html',
                                                    data : {booCamordCamposBd : true, intIdBd : $("#G10_C74").val()},
                                                    global:false,
                                                    async:false,
                                                    success:function(data){
                                                        return data;
                                                    }
                                                }).responseText;

                        strHTMLCamordMT_t = '<option value="_Estado____b" tipo="3" >Estado</option>';
                        strHTMLCamordMT_t += '<option value="_FecUltGes_b" tipo="5" >Fecha ultima gestión</option>';
                        strHTMLCamordMT_t += '<option value="_NumeInte__b" tipo="3" >Cantidad de intentos</option>';
                        strHTMLCamordMT_t += '<option value="_UltiGest__b" tipo="3" >Ultima gestión</option>';

                        $("#camord_cantidad").val(objCarmord_p.intCantidad_t);

                        $("#divParametrosEstrategiaMarcacion").html('');

                        $.each (objCarmord_p.arrCamord_t, function(iCamord,vCarmord){

                            intICamord = (iCamord+1);

                            $("#camord_cantidad").val(intICamord);
                            

                            strHTMLCamord_t = '<tr class="camord_row" id="camord_row_'+intICamord+'">';

                                strHTMLCamord_t += '<td class="col-md-3 col-xs-3 responsiveDiv">';
                                    strHTMLCamord_t += '<div class="form-group">';
                                        strHTMLCamord_t += '<select class="form-control camord_basado" name="camord_basado_'+intICamord+'" id="camord_basado_'+intICamord+'" idRow="'+intICamord+'">';
                                            strHTMLCamord_t += '<option value="P" >De la base de datos</option>';
                                            strHTMLCamord_t += '<option value="M" >De la campaña</option>';
                                        strHTMLCamord_t += '</select>';
                                    strHTMLCamord_t += '</div>';
                                strHTMLCamord_t += '</td>';

                                strHTMLCamord_t += '<td class="col-md-3 col-xs-3 responsiveDiv">';
                                    strHTMLCamord_t += '<div class="form-group">';
                                        strHTMLCamord_t += '<select class="form-control camord_parametro" name="camord_base_'+intICamord+'" id="camord_base_'+intICamord+'" idRow="'+intICamord+'">';

                                            if (vCarmord.CAMORD_MUESPOBL__B == "P") {
                                                strHTMLCamord_t += strHTMLCamordBD_t;
                                            }

                                        strHTMLCamord_t += '</select>';
                                    strHTMLCamord_t += '</div>';
                                strHTMLCamord_t += '</td>';

                                strHTMLCamord_t += '<td class="col-md-2 col-xs-2 responsiveDiv">';
                                    strHTMLCamord_t += '<div class="form-group">';
                                        strHTMLCamord_t += '<select class="form-control camord_parametro" name="camord_campana_'+intICamord+'" id="camord_campana_'+intICamord+'" idRow="'+intICamord+'">';

                                            if (vCarmord.CAMORD_MUESPOBL__B == "M") {

                                                strHTMLCamord_t += strHTMLCamordMT_t;

                                            }

                                        strHTMLCamord_t += '</select>';
                                    strHTMLCamord_t += '</div>';
                                strHTMLCamord_t += '</td>';

                                strHTMLCamord_t += '<td class="col-md-1 col-xs-1 responsiveDiv">';
                                    strHTMLCamord_t += '<div class="form-group">';
                                        strHTMLCamord_t += '<input type="number" class="inline-edit-cell form-control camord_prioridad" id="camord_prioridad_'+intICamord+'" name="camord_prioridad_'+intICamord+'" rowid="'+intICamord+'">';
                                    strHTMLCamord_t += '</div>';
                                strHTMLCamord_t += '</td>';

                                strHTMLCamord_t += '<td class="col-md-2 col-xs-2 responsiveDiv">';
                                    strHTMLCamord_t += '<div class="form-group">';
                                        strHTMLCamord_t += '<select class="form-control" name="camord_orden_'+intICamord+'" id="camord_orden_'+intICamord+'" idRow="'+intICamord+'">';

                                            arrCamordOrden_t = camordTipo(vCarmord.PREGUN_Tipo______b); 

                                            strHTMLCamord_t += '<option value="ASC" >'+arrCamordOrden_t[0]+'</option>';
                                            strHTMLCamord_t += '<option value="DESC" >'+arrCamordOrden_t[1]+'</option>';

                                            

                                        strHTMLCamord_t += '</select>';
                                    strHTMLCamord_t += '</div>';
                                strHTMLCamord_t += '</td>';

                                strHTMLCamord_t += '<td class="col-md-1 col-xs-1 responsiveDiv">';
                                    strHTMLCamord_t += '<div class="form-group">';
                                    strHTMLCamord_t += '<button class="form-control btn btn-danger btn-sm eliminarCamord" type="button" name="eliminarCamord_'+intICamord+'" id="eliminarCamord_'+intICamord+'" idRow="'+intICamord+'"><i class="fa fa-trash-o"></i></button>';
                                    strHTMLCamord_t += '<input type="hidden" name="camord_id_'+intICamord+'" id="camord_id_'+intICamord+'" value="'+vCarmord.CAMORD_CONSINTE__B+'">';
                                    strHTMLCamord_t += '</div>';
                                strHTMLCamord_t += '</td>';

                            strHTMLCamord_t += '</tr>';

                            $("#divParametrosEstrategiaMarcacion").append(strHTMLCamord_t);

                            $("#camord_basado_"+intICamord).val(vCarmord.CAMORD_MUESPOBL__B).trigger("change");
                            $("#camord_base_"+intICamord).val(vCarmord.CAMORD_POBLCAMP__B).trigger("change");
                            $("#camord_campana_"+intICamord).val(vCarmord.CAMORD_MUESCAMP__B).trigger("change");
                            $("#camord_prioridad_"+intICamord).val(vCarmord.CAMORD_PRIORIDAD_B);
                            $("#camord_orden_"+intICamord).val(vCarmord.CAMORD_ORDEN_____B).trigger("change");
                            
                            
                        });

                        $(".camord_parametro").change(function(){

                            intRow_t = $(this).attr("idRow");
                            intCamordTipo_t = $("option:selected",this).attr("tipo");

                            arrCamordOrden_t = camordTipo(intCamordTipo_t);

                            $("#camord_orden_"+intRow_t).html('<option value="ASC" >'+arrCamordOrden_t[0]+'</option><option value="DESC" >'+arrCamordOrden_t[1]+'</option>'); 



                        });

                        $(".camord_basado").change(function(){

                            intRow_t = $(this).attr("idRow");
                            strBasado_t = $(this).val(); 

                            if (strBasado_t == "P") {

                                $("#camord_campana_"+intRow_t).html('');
                                $("#camord_base_"+intRow_t).html(strHTMLCamordBD_t);

                                arrCamordOrden_t = camordTipo($("#camord_base_"+intRow_t+" option:selected").attr("tipo"));

                                $("#camord_orden_"+intRow_t).html('<option value="ASC" >'+arrCamordOrden_t[0]+'</option><option value="DESC" >'+arrCamordOrden_t[1]+'</option>'); 

                            }else if (strBasado_t == "M") {

                                $("#camord_base_"+intRow_t).html('');
                                $("#camord_campana_"+intRow_t).html(strHTMLCamordMT_t);

                                arrCamordOrden_t = camordTipo($("#camord_campana_"+intRow_t+" option:selected").attr("tipo"));

                                $("#camord_orden_"+intRow_t).html('<option value="ASC" >'+arrCamordOrden_t[0]+'</option><option value="DESC" >'+arrCamordOrden_t[1]+'</option>'); 

                            }

                        });

                        $(".eliminarCamord").click(function(){

                            intRow_t = $(this).attr("idRow");

                            $("#camord_row_"+intRow_t).remove();

                        });

                    }
                });

        }

        $(".estrategia_marcacion").click(function(){

            if ($("#estrategia_marcacion").attr("class") == "panel-collapse collapse") {

                estrategiaCamord();
                
            }


        });

        $(".recargarGrillas").click(function(){
            vamosRecargaLasGrillasPorfavor(idTotal);
        });

        $('#G10_C73').on('select2:select', function (e) {
            var ex = $(this).val();
            vamosRecargaLasGrillasPorfavor(idTotal);
        });

        //function para BASE DE DATOS 

        $("#G10_C74").change(function(){
            vamosRecargaLasGrillasPorfavor(idTotal);
        });

        <?php if(isset($_GET['id_paso'])){ ?>
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
                dataType : 'json',
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                    });
                },
                complete : function(){
                    $.unblockUI();
                },
                success  : function(data){
                    
                    if(data.length > 0){
                        //recorrer datos y enviarlos al formulario
                        $.each(data, function(i, item) {
                            //$("#G10_C70").val(item.G10_C70);

                            $("#G10_C71").val(item.G10_C71);

                            if(item.G10_C72 == '-1'){
                               if(!$("#G10_C72").is(':checked')){
                                   $("#G10_C72").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C72").is(':checked')){
                                   $("#G10_C72").prop('checked', false);  
                                }
                                
                            }

                            //$("#G10_C73").val(item.G10_C73);

                            $("#G10_C73").val(item.G10_C73).trigger('change.select2');

                            functionsGuion.cargarDatos(item.G10_C74);

                            functionsGuion.cargarScript(item.G10_C73);

                            $("#G10_C73").attr('disabled', true);

                            //$("#G10_C74").val(item.G10_C74);

                            $("#G10_C74").val(item.G10_C74).trigger('change.select2');

                            $("#G10_C74").attr('disabled', true);


                            $("#G10_C75").val(item.G10_C75);

                            $("#G10_C76").val(item.G10_C76);

                            $("#G10_C76").val(item.G10_C76).trigger("change"); 

                            $("#G10_C77").val(item.G10_C77);
                            $("#G10_C77_pre").val(item.G10_C77);

                            $("#G10_C77").val(item.G10_C77).trigger("change"); 

                            $("#G10_C105").val(item.G10_C105);

                            $("#G10_C106").val(item.G10_C106);

                            $("#G10_C107").val(item.G10_C107);

                            if(item.G10_C79 == '-1'){
                               if(!$("#G10_C79").is(':checked')){
                                   $("#G10_C79").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C79").is(':checked')){
                                   $("#G10_C79").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C80").val(item.G10_C80);

                            if(item.G10_C81 == '-1'){
                               if(!$("#G10_C81").is(':checked')){
                                   $("#G10_C81").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C81").is(':checked')){
                                   $("#G10_C81").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C82").val(item.G10_C82);

                            $("#G10_C83").val(item.G10_C83);

                            $("#G10_C84").val(item.G10_C84);

                            if(item.G10_C85 == '-1'){
                                $("#G10_C85").prop('checked', true);  
                            } else {
                                $("#G10_C85").prop('checked', false);  
                            }

                            $("#G10_C90").val(item.G10_C90);

                            $("#G10_C90").val(item.G10_C90).trigger("change"); 

                            $("#G10_C91").val(item.G10_C91);

                            $("#G10_C91").val(item.G10_C91).trigger("change"); 

                            $("#G10_C92").val(item.G10_C92);

                            $("#G10_C93").val(item.G10_C93);

                            $("#G10_C94").val(item.G10_C94);

                            if(item.G10_C95 == '1'){
                               if(!$("#G10_C95").is(':checked')){
                                   $("#G10_C95").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C95").is(':checked')){
                                   $("#G10_C95").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C98").val(item.G10_C98);

                            $("#G10_C98").val(item.G10_C98).trigger("change"); 

                            $("#G10_C99").val(item.G10_C99);

                            if(item.G10_C100 == '1'){
                               if(!$("#G10_C100").is(':checked')){
                                   $("#G10_C100").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C100").is(':checked')){
                                   $("#G10_C100").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C101").val(item.G10_C101);

                            $("#G10_C101").val(item.G10_C101).trigger("change"); 

                            $("#G10_C102").val(item.G10_C102);

                            $("#G10_C333").val(item.G10_C333);

                            $("#G10_C103").val(item.G10_C103);

                            $("#G10_C103").val(item.G10_C103).trigger("change"); 

                            $("#G10_C334").val(item.G10_C334).trigger("change"); 

                            $("#G10_C104").val(item.G10_C104);

                            $("#G10_C104").val(item.G10_C104).trigger("change"); 

                            if(item.G10_C108 == '-1'){
                               if(!$("#G10_C108").is(':checked')){
                                   $("#G10_C108").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C108").is(':checked')){
                                   $("#G10_C108").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C109").val(item.G10_C109);

                            $("#G10_C110").val(item.G10_C110);

                            if(item.G10_C111 == '-1'){
                               if(!$("#G10_C111").is(':checked')){
                                   $("#G10_C111").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C111").is(':checked')){
                                   $("#G10_C111").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C112").val(item.G10_C112);

                            $("#G10_C113").val(item.G10_C113);

                            if(item.G10_C114 == '-1'){
                               if(!$("#G10_C114").is(':checked')){
                                   $("#G10_C114").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C114").is(':checked')){
                                   $("#G10_C114").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C115").val(item.G10_C115);

                            $("#G10_C116").val(item.G10_C116);

                            if(item.G10_C117 == '-1'){
                               if(!$("#G10_C117").is(':checked')){
                                   $("#G10_C117").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C117").is(':checked')){
                                   $("#G10_C117").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C118").val(item.G10_C118);

                            $("#G10_C119").val(item.G10_C119);

                            if(item.G10_C120 == '-1'){
                               if(!$("#G10_C120").is(':checked')){
                                   $("#G10_C120").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C120").is(':checked')){
                                   $("#G10_C120").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C121").val(item.G10_C121);

                            $("#G10_C122").val(item.G10_C122);

                            if(item.G10_C123 == '-1'){
                               if(!$("#G10_C123").is(':checked')){
                                   $("#G10_C123").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C123").is(':checked')){
                                   $("#G10_C123").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C124").val(item.G10_C124);

                            $("#G10_C125").val(item.G10_C125);

                            if(item.G10_C126 == '-1'){
                               if(!$("#G10_C126").is(':checked')){
                                   $("#G10_C126").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C126").is(':checked')){
                                   $("#G10_C126").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C127").val(item.G10_C127);

                            $("#G10_C128").val(item.G10_C128);

                            if(item.G10_C129 == '-1'){
                               if(!$("#G10_C129").is(':checked')){
                                   $("#G10_C129").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C129").is(':checked')){
                                   $("#G10_C129").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C130").val(item.G10_C130);

                            $("#G10_C131").val(item.G10_C131);
                            
                            if(item.G10_C332 == '-1'){
                               $("#G10_C332").prop('checked', true);
                               $("#G10_C332").val('-1');    
                            }else{
                               $("#G10_C332").prop('checked', false);
                                $("#G10_C332").val('0');
                            }
                            
                            $("#G10_C326").val(item.G10_C326);

                            $("#G10_C331").val(item.G10_C331);

                            $("#tiempoInabilitadoBotonColgado").val(item.tiempoInabilitadoBotonColgado);

                            $("#h3mio").html(item.principal);
                            idTotal = item.G10_ConsInte__b;

                            if ( $("#"+idTotal).length > 0) {
                                $("#"+idTotal).click();   
                                $("#"+idTotal).addClass('active'); 
                            }else{
                                //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                                $(".CargarDatos :first").click();
                            }

                            
                            $("#hidId").val(item.G10_ConsInte__b);
                            $("#hidId2").val(item.G10_ConsInte__b);
                            getCampanasEnviar();

                            $("#id_campanaFiltros").val(item.G10_ConsInte__b);
                            $("#id_campanaFiltros_delete").val(item.G10_ConsInte__b);

                            if(item.G10_ConsInte__b.length > 0){
                                vamosRecargaLasGrillasPorfavor(item.G10_ConsInte__b)
                            }else{
                                vamosRecargaLasGrillasPorfavor(0)
                            }

                            
                            $("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b);
                            
                        });

                        $("#oper").val('edit');

                        $('.checkbox-day').each(function() {
                            var $divGroup = $(this).closest('.row'); // Encontrar el grupo de divs más cercano
                            var $hoursFields = $divGroup.find('.Hora'); // Encontrar los campos correspondientes dentro del grupo de divs
                            
                            // Habilitar o deshabilitar los campos según el estado del checkbox
                            if ($(this).is(':checked')) {
                                $hoursFields.prop('disabled', false);
                            } else {
                                $hoursFields.prop('disabled', true);
                            }
                        });

                        
                        //Deshabilitar los campo 
                    }else{
                        $("#G10_C76 option").filter(function(){
                            return $(this).text() == "PDS";
                        }).prop('selected', true).change();


                        $("#G10_C77 option").filter(function(){
                            return $(this).text() == "Dinámico";
                        }).prop('selected', true).change();

                        $("#G10_C73").val(0).change();
                        $("#G10_C74").val(0).change();
                        $("#G10_C72").prop('checked', true);
                        $("#G10_C80").val('5');
                        $("#G10_C80").attr('disabled', true);
                        $("#G10_C82").attr('disabled', true);
                        $("#G10_C91").attr('disabled', true);
                        $("#G10_C92").val('0');
                        $("#G10_C92").attr('disabled', true);
                        $("#G10_C93").val('10');
                        $("#G10_C93").attr('disabled', true);
                        $("#G10_C94").val('30');
                        $("#G10_C94").attr('disabled', true);
                        $("#G10_C98").attr('disabled', true);
                        $("#G10_C99").val('0');

                        $("#G10_C109").val('08:00:00');
                        $("#G10_C110").val('17:00:00');

                        $("#G10_C112").val('08:00:00');
                        $("#G10_C113").val('17:00:00');

                        $("#G10_C115").val('08:00:00');
                        $("#G10_C116").val('17:00:00');

                        $("#G10_C118").val('08:00:00');
                        $("#G10_C119").val('17:00:00');

                        $("#G10_C121").val('08:00:00');
                        $("#G10_C122").val('17:00:00');

                        $("#G10_C124").val('08:00:00');
                        $("#G10_C125").val('17:00:00');

                        $("#G10_C127").val('08:00:00');
                        $("#G10_C128").val('17:00:00');

                        $("#G10_C130").val('08:00:00');
                        $("#G10_C131").val('17:00:00');
                    }
                                                 
                } 
            });    
        <?php } ?>
    });
</script>

<!-- Esto es para el cargador de datos que estamso metiendo aqui -->
<script type="text/javascript">
    $(function(){
        // funcion para bloquear los horarios 
        $('.checkbox-day').change(function(){
            var $divGroup = $(this).closest('.row'); // Encontrar el grupo de divs más cercano
            
            // Encontrar los campos correspondientes dentro del grupo de divs
            var $hoursFields = $divGroup.find('.Hora');
            
            // Habilitar o deshabilitar los campos según el estado del checkbox
            if($(this).is(':checked')) {
                $hoursFields.prop('disabled', false);
                
            } else {
                $hoursFields.prop('disabled', true);
            }
        });
        // funcion para bloquear los horarios 

        $("#cargardtaosCampanhaCompleto").click(function(){
            $.ajax({
                url  : '<?=base_url?>mostrar_popups.php?view=cargueDatos&id_paso=<?=$_GET["id_paso"]?>&poblacion='+$("#G10_C74").val()+"&formaInvoaca=solocampan&muestra="+$("#G10_C75").val()+'&distribucion='+$('#G10_C77').val()+"&estrat=<?php echo md5(clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>",                
                type : 'get',
                success : function(data){
                    $("#title_cargue").html("<?php echo $str_carga;?>");
                    $("#divIframe").html(data);
                    $("#cargarInformacion").modal();
                },
                beforeSend : function(){
                    $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });

        $("#cargardtaosCampanhaSinMuestra").click(function(){
            $.ajax({
                url  : '<?=base_url?>mostrar_popups.php?view=cargueDatos&poblacion='+$("#G10_C74").val()+"&formaInvoaca=solocampan&muestra=no",
                type : 'get',
                success : function(data){
                    $("#title_estrat").html("CARGAR DATOS EN LA CAMPAÑA SIN INSERTAR EN LA MUESTRA");
                    $("#divIframe").html(data);
                    $("#cargarInformacion").modal();
                },
                beforeSend : function(){
                    $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });
    });
</script>

<!-- Esto es para gusrdar la estrategia y el paso -->
<script type="text/javascript">
    $(function(){
        $(".regresoCampains").click(function(){
            $("#cancel").click();
        });
         

        

        $("#btnSave_Estrat").click(function(){
            var dtao = new FormData($("#formuarioCargarEstoEstrart")[0]);
            dtao.append('oper', 'add');
            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                type: 'POST',
                data: dtao,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data){
                    if(data){
                        var idEstrategia = data;
                        /* ahora metemos el paso y convocamos el modal del otro */
                        var mySavedModel = '{"class":"go.GraphLinksModel", "linkFromPortIdProperty":"fromPort", "linkToPortIdProperty":"toPort", "nodeDataArray":[{"category":"salPhone","text":"Llamadas salientes Simples","tipoPaso":6, "figure":"Circle","key":-6,"loc":"37.25+-22.600006103515625"}], "linkDataArray":[]}';
                         $.ajax({
                            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',  
                            type: 'POST',
                            data: { mySavedModel : mySavedModel , id_estrategia : idEstrategia , guardar_flugrama_simple : 'SI'},
                            //una vez finalizado correctamente
                            success: function(data){
                                if(data){
                                    $("#id_estpas_mio").val(data);
                                    /* guardo el flujograma toca mostrar la campaña para crear los datos */
                                    $("#crearDefinicionNueva").modal();
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
                                    baseZ: 2000,
                                    message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                                });
                            },
                            complete : function(){
                                $.unblockUI();
                            }
                        });
                    }
                }
            });
        });

        $("#btnSave_campains").click(function(){
            var formData = new FormData($("#formuarioCargarEsto")[0]);
            formData.append('oper', 'add');
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
                        $.ajax({
                            url      : '<?=$url_crud;?>',
                            type     : 'POST',
                            data     : { CallDatos_2 : 'SI', id : $("#id_estpas_mio").val() },
                            dataType : 'json',
                            success  : function(data){
                                if(data.length > 0){
                                    //recorrer datos y enviarlos al formulario
                                    $.each(data, function(i, item) {
                                        //$("#G10_C70").val(item.G10_C70);

                                        $("#G10_C71").val(item.G10_C71);

                                        if(item.G10_C72 == '-1'){
                                           if(!$("#G10_C72").is(':checked')){
                                               $("#G10_C72").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C72").is(':checked')){
                                               $("#G10_C72").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C73").val(item.G10_C73);

                                        $("#G10_C73").val(item.G10_C73).trigger("change"); 

                                        $("#G10_C74").val(item.G10_C74);

                                        $("#G10_C74").val(item.G10_C74).trigger("change"); 

                                        $("#G10_C75").val(item.G10_C75);

                                        $("#G10_C76").val(item.G10_C76);

                                        $("#G10_C76").val(item.G10_C76).trigger("change"); 

                                        $("#G10_C77").val(item.G10_C77);

                                        $("#G10_C77").val(item.G10_C77).trigger("change"); 

                                        $("#G10_C105").val(item.G10_C105);

                                        $("#G10_C106").val(item.G10_C106);

                                        $("#G10_C107").val(item.G10_C107);

                                        if(item.G10_C79 == '-1'){
                                           if(!$("#G10_C79").is(':checked')){
                                               $("#G10_C79").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C79").is(':checked')){
                                               $("#G10_C79").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C80").val(item.G10_C80);

                                        if(item.G10_C81 == '-1'){
                                           if(!$("#G10_C81").is(':checked')){
                                               $("#G10_C81").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C81").is(':checked')){
                                               $("#G10_C81").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C82").val(item.G10_C82);

                                        $("#G10_C83").val(item.G10_C83);

                                        $("#G10_C84").val(item.G10_C84);

                                        if(item.G10_C85 == '-1'){
                                            $("#G10_C85").prop('checked', true);  
                                        } else {
                                            $("#G10_C85").prop('checked', false);  
                                        }

                                        $("#G10_C90").val(item.G10_C90);

                                        $("#G10_C90").val(item.G10_C90).trigger("change"); 

                                        $("#G10_C91").val(item.G10_C91);

                                        $("#G10_C91").val(item.G10_C91).trigger("change"); 

                                        $("#G10_C92").val(item.G10_C92);

                                        $("#G10_C93").val(item.G10_C93);

                                        $("#G10_C94").val(item.G10_C94);

                                        if(item.G10_C95 == '-1'){
                                           if(!$("#G10_C95").is(':checked')){
                                               $("#G10_C95").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C95").is(':checked')){
                                               $("#G10_C95").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C98").val(item.G10_C98);

                                        $("#G10_C98").val(item.G10_C98).trigger("change"); 

                                        $("#G10_C99").val(item.G10_C99);

                                        if(item.G10_C100 == '-1'){
                                           if(!$("#G10_C100").is(':checked')){
                                               $("#G10_C100").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C100").is(':checked')){
                                               $("#G10_C100").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C101").val(item.G10_C101);

                                        $("#G10_C101").val(item.G10_C101).trigger("change"); 

                                        $("#G10_C102").val(item.G10_C102);

                                        $("#G10_C333").val(item.G10_C333);

                                        $("#G10_C103").val(item.G10_C103);

                                        $("#G10_C103").val(item.G10_C103).trigger("change"); 

                                        $("#G10_C334").val(item.G10_C334).trigger("change"); 

                                        $("#G10_C104").val(item.G10_C104);

                                        $("#G10_C104").val(item.G10_C104).trigger("change"); 

                                        if(item.G10_C108 == '-1'){
                                           if(!$("#G10_C108").is(':checked')){
                                               $("#G10_C108").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C108").is(':checked')){
                                               $("#G10_C108").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C109").val(item.G10_C109);

                                        $("#G10_C110").val(item.G10_C110);

                                        if(item.G10_C111 == '-1'){
                                           if(!$("#G10_C111").is(':checked')){
                                               $("#G10_C111").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C111").is(':checked')){
                                               $("#G10_C111").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C112").val(item.G10_C112);

                                        $("#G10_C113").val(item.G10_C113);

                                        if(item.G10_C114 == '-1'){
                                           if(!$("#G10_C114").is(':checked')){
                                               $("#G10_C114").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C114").is(':checked')){
                                               $("#G10_C114").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C115").val(item.G10_C115);

                                        $("#G10_C116").val(item.G10_C116);

                                        if(item.G10_C117 == '-1'){
                                           if(!$("#G10_C117").is(':checked')){
                                               $("#G10_C117").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C117").is(':checked')){
                                               $("#G10_C117").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C118").val(item.G10_C118);

                                        $("#G10_C119").val(item.G10_C119);

                                        if(item.G10_C120 == '-1'){
                                           if(!$("#G10_C120").is(':checked')){
                                               $("#G10_C120").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C120").is(':checked')){
                                               $("#G10_C120").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C121").val(item.G10_C121);

                                        $("#G10_C122").val(item.G10_C122);

                                        if(item.G10_C123 == '-1'){
                                           if(!$("#G10_C123").is(':checked')){
                                               $("#G10_C123").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C123").is(':checked')){
                                               $("#G10_C123").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C124").val(item.G10_C124);

                                        $("#G10_C125").val(item.G10_C125);

                                        if(item.G10_C126 == '-1'){
                                           if(!$("#G10_C126").is(':checked')){
                                               $("#G10_C126").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C126").is(':checked')){
                                               $("#G10_C126").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C127").val(item.G10_C127);

                                        $("#G10_C128").val(item.G10_C128);

                                        if(item.G10_C129 == '-1'){
                                           if(!$("#G10_C129").is(':checked')){
                                               $("#G10_C129").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C129").is(':checked')){
                                               $("#G10_C129").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C130").val(item.G10_C130);

                                        $("#G10_C131").val(item.G10_C131);
                                        
                                        if(item.G10_C332 == '-1'){
                                           $("#G10_C332").prop('checked', true);
                                           $("#G10_C332").val('-1');    
                                        }else{
                                           $("#G10_C332").prop('checked', false);
                                            $("#G10_C332").val('0');
                                        }

                                        $("#G10_C326").val(item.G10_C326);

                                        $("#G10_C331").val(item.G10_C331);

                                        $("#tiempoInabilitadoBotonColgado").val(item.tiempoInabilitadoBotonColgado);
                                        
                                        $("#h3mio").html(item.principal);
                                        idTotal = item.G10_ConsInte__b;

                                        if ( $("#"+idTotal).length > 0) {
                                            //$("#"+idTotal).click();   
                                            //$("#"+idTotal).addClass('active'); 
                                        }else{
                                            //Si el id no existe, se

                                            $(".CargarDatos").each(function(){
                                                $(this).removeClass('active');
                                            });

                                            var tr= '';
                                            tr += "<tr class='CargarDatos active' id='"+item.G10_ConsInte__b+"'>";
                                            tr += "<td>";
                                            tr += "<p style='font-size:14px;'><b>"+item.G10_C71+"</b></p>";
                                            tr += "</td>";
                                            tr += "</tr>";
                                            jQuery("#tablaScroll").prepend(tr);
                                            //$(".CargarDatos :first").click();
                                        }

                                        
                                        $("#hidId").val(item.G10_ConsInte__b);

                                        if(item.G10_ConsInte__b.length > 0){
                                            vamosRecargaLasGrillasPorfavor(item.G10_ConsInte__b)
                                        }else{
                                            vamosRecargaLasGrillasPorfavor(0)
                                        }

                                        $("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b); 
                                        
                                    });
                                    //Deshabilitar los campo 
                                }
                            } 
                        });
                        $("#oper").val('edit');  
                        $("#crearDefinicionNueva").modal('hide');
                        $("#crearCampanhasNueva").modal('hide');      
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
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }

            });
        });
    });
</script>

<script type="text/javascript">
    $(function(){
    
        
        $("#newOpcion").click(function(){
            let cuerpo = "<tbody>"
             cuerpo += "<tr class='' id='id_"+cuantosVan+"'>";

            cuerpo += "<td class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' name='opciones_"+cuantosVan+"' class='form-control' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "<td class='col-md-2'>";
            cuerpo += "<div class='form-group'>";;
            cuerpo += "<select name='Tip_NoEfe_"+cuantosVan+"' class='form-control'>";
            cuerpo += "<option value='1'><?php echo $str_opcion_tipono_; ?></option>";
            cuerpo += "<option value='2'><?php echo $str_opcion_egenda_; ?></option>";
            cuerpo += "<option value='3'><?php echo $str_opcion_norein_; ?></option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "<td class='col-md-1'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' class='form-control' placeholder='' value='0' name='txtHorasMas_"+cuantosVan+"' id='txtHorasMas_"+cuantosVan+"'>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "<td class='col-md-2'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select class='form-control' name='contacto_"+cuantosVan+"'>"
            cuerpo += "<option value='1'><?php echo $str_opcion_number1; ?></option>";
            cuerpo += "<option value='2'><?php echo $str_opcion_number2; ?></option>";
            cuerpo += "<option value='3'><?php echo $str_opcion_number3; ?></option>";
            cuerpo += "<option value='4'><?php echo $str_opcion_number4; ?></option>";
            cuerpo += "<option value='5'><?php echo $str_opcion_number5; ?></option>";
            cuerpo += "<option value='6'><?php echo $str_opcion_number6; ?></option>";
            cuerpo += "<option value='7'><?php echo $str_opcion_number7; ?></option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</td>";
            
            cuerpo += "<td class='col-md-3'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select name='estado_"+cuantosVan+"' class='form-control' >";
            cuerpo += estados;
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "<td class='col-md-1'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<button class='btn btn-danger btn-sm form-control deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+cuantosVan+"'><i class='fa fa-trash-o'></i></button>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "</tr>";
            cuerpo += "</tbody>";
            cuantosVan++;
            contador++;
            $("#opciones").append(cuerpo);

            $(".numeroImpo").numeric();

            
            $(".deleteopcion").click(function(){
                var id = $(this).attr('id');
                $("#id_"+id).remove();
            });
            
        });
    });
</script>

<!-- Funcionalidad del validador de condiciones Datos-->
<script type="text/javascript">

    var van = 1;
    function agregarFiltrosCondciones(ejecutor = "administradorRegistros"){
        
        var rowFil = document.getElementsByClassName("rowFil").length;
        var strHTMLOpcion_t = "";
        var strHTMLDelete_t = "";
        var intClMd_t = "3";
        var intLista_t = "3";
        var intCierre_t = "2";

        if (rowFil>0) {

            intClMd_t = "3";
            intLista_t = "3";
            intCierre_t = "1";


            strHTMLOpcion_t += "<div class='col-md-1'>";
                strHTMLOpcion_t += "<div class='form-group'>";
                    strHTMLOpcion_t += "<select class='form-control condApertura' name='andOr_"+van+"' id='andOr_"+van+"' numero='"+van+"'>";
                        strHTMLOpcion_t += '<option value="AND"><?php echo $str_Filtro_AND__________;?></option>';
                        strHTMLOpcion_t += "<option value=' AND ('><?php echo $str_Filtro_AND__________;?> &#40</option>";
                        strHTMLOpcion_t += '<option value="OR"><?php echo $str_Filtro_OR___________;?></option>';
                        strHTMLOpcion_t += "<option value=' OR ('><?php echo $str_Filtro_OR___________;?> &#40</option>";
                    strHTMLOpcion_t += "</select>";
                strHTMLOpcion_t += "</div>";
            strHTMLOpcion_t += "</div>";

            strHTMLDelete_t += "<div class='col-md-1'>";
                strHTMLDelete_t += "<div class='form-group'>";
                    strHTMLDelete_t += "<button class='btn btn-danger deleteFiltro'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+van+"'><i class='fa fa-trash-o'></i></button>";
                strHTMLDelete_t += "</div>";
            strHTMLDelete_t += "</div>";

        }else{

            strHTMLOpcion_t += "<div class='col-md-1'>";
                strHTMLOpcion_t += "<div class='form-group'>";
                    strHTMLOpcion_t += "<select class='form-control condApertura' name='andOr_"+van+"' id='andOr_"+van+"' numero='"+van+"'>";
                        strHTMLOpcion_t += "<option value=' '></option>";
                        strHTMLOpcion_t += "<option value='('>&#40</option>";                                                
                    strHTMLOpcion_t += "</select>";
                strHTMLOpcion_t += "</div>";
            strHTMLOpcion_t += "</div>";

        }

        var cuerpo = "<div class='row rowFil' id='id_"+van+"'>";

            cuerpo += strHTMLOpcion_t;

            cuerpo += "<div class='col-md-"+intLista_t+"'>";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<select class='form-control mi_select_pregun' name='pregun_"+van+"'  id='pregun_"+van+"' numero='"+van+"'>";
                        cuerpo += "<option value='0' tipo='3'>Seleccione</option>";
                        cuerpo += "<option value='_CoInMiPo__b' tipo='3'>ID BD</option>";
                        cuerpo += "<option value='_FechaInsercion' tipo='5'>FECHA CREACION</option>";


                        $.each(datosGuion, function(i, item) {
                            cuerpo += "<option value='"+item.PREGUN_ConsInte__b+"' tipo='"+item.PREGUN_Tipo______b+"'>"+ item.PREGUN_Texto_____b+"</option>";
                        }); 

                        cuerpo += "<option value='_Estado____b' tipo='_Estado____b'>ESTADO_DY (tipo reintento)</option>";
                        cuerpo += "<option value='_ConIntUsu_b' tipo='_ConIntUsu_b'>Usuario</option>";
                        cuerpo += "<option value='_NumeInte__b' tipo='3'>Numero de intentos</option>";
                        cuerpo += "<option value='_UltiGest__b' tipo='MONOEF'>Ultima gesti&oacute;n</option>";
                        cuerpo += "<option value='_GesMasImp_b' tipo='MONOEF'>Gesti&oacute;n mas importante</option>";
                        cuerpo += "<option value='_FecUltGes_b' tipo='5'>Fecha ultima gesti&oacute;n</option>";
                        cuerpo += "<option value='_FeGeMaIm__b' tipo='5'>Fecha gesti&oacute;n mas importante</option>";
                        cuerpo += "<option value='_ConUltGes_b' tipo='ListaCla'>Clasificaci&oacute;n ultima gesti&oacute;n</option>";
                        cuerpo += "<option value='_CoGesMaIm_b' tipo='ListaCla'>Clasificaci&oacute;n mas importante</option>";
                        cuerpo += "<option value='_Activo____b' tipo='_Activo____b'>Registro activo</option>";

                    cuerpo += "</select>";

                cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-3'>";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<select name='condicion_"+van+"' id='condicion_"+van+"' class='form-control' numero='"+van+"'>";
                        cuerpo += "<option value='0'>Condici&oacute;n</option>";
                    cuerpo += "</select>";
                cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-"+intClMd_t+"' id='valores_restableses_"+ van +"' >";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<input type='text'  name='valor_"+van+"' id='valor_"+van+"' numero='"+van+"' class='form-control' placeholder='<?php echo $filtros_valor__c; ?>'>";
                cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-"+ intCierre_t +"' id='divCierre_"+ van +"' >";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<select name='cierre"+van+"' id='cierre"+van+"' class='form-control condCierre' numero='"+van+"'>";
                        cuerpo += "<option value=''></option>";
                    cuerpo += "</select>";
                cuerpo += "</div>";
            cuerpo += "</div>";


            cuerpo += "<input type='hidden' name='tipo_campo_"+van+"' id='tipo_campo_"+van+"' class='form-control'>";

            cuerpo += strHTMLDelete_t;

            cuerpo += "</div>";

            van++;

        if (ejecutor == 'CONDICIONES_TAREAS') {
            $("#CONDICIONES_TAREAS").append(cuerpo);
        }
        if(ejecutor == 'administradorRegistros'){
            $("#FILTROS").append(cuerpo);
        }

        $(".mi_select_pregun").change(function(){

            var id = $(this).attr('numero');
            var tipo = $("#pregun_"+id+" option:selected").attr('tipo');
            var valor = $(this).val();

            $("#tipo_campo_"+id).val(tipo);

            var options = "";
            if(tipo == '1' || tipo == '2'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='LIKE_1'>INICIE POR</option>";
                options += "<option value='LIKE_2'>CONTIENE</option>";
                options += "<option value='LIKE_3'>TERMINE EN</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }

            if(tipo == '4' || tipo == '3'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value=' < '>MENOR QUE</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

                $("#valor_"+id).numeric();
            }

            if(tipo == '5'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='FECHA'>";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value=' < '>MENOR QUE</option>";
                options += "<option value='MayorMes'>Mayor a meses</option>";
                options += "<option value='MenorMes'>Menor a meses</option>";
                options += "<option value='MayorDia'>Mayor a dias</option>";
                options += "<option value='MenorDia'>Menor a dias</option>";

                // Creo un change que permita obtener valores tipo date o numerico
                $("#condicion_"+id).change(function(){

                    // Si es una de estas opciones significa se se validara que la fecha este en un rango de x meses o dias(Se encuentre en los ultimos o no estes en los ultimos)
                    if($(this).val() == 'MayorMes' || $(this).val() == 'MenorMes' || $(this).val() == 'MayorDia' || $(this).val() == 'MenorDia'){
                        $("#valores_restableses_"+id).html(cuerpo);
                        $("#valor_"+id).numeric();
                    }else{
                        $("#valores_restableses_"+id).html(cuerpo);
                        $("#valor_"+id).datepicker({
                            language: "es",
                            autoclose: true,
                            todayHighlight: true
                        });
                    }
                });

                $("#valor_"+id).datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true
                });
            }

            if(tipo == '10'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value=' < '>MENOR QUE</option>";

                cuerpo = "<div class='form-group'><input type='time' max='23:59:59' min='00:00:00' step='1' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='00:00:00'></div>";

                $("#valores_restableses_"+id).html(cuerpo);
            }

            if( tipo == '_Activo____b' || tipo == '_Estado____b' || tipo == '_ConIntUsu_b' || tipo == 'MONOEF' || tipo == 'ListaCla' || tipo == '8' || tipo == '6'){
                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
            } 

            if(tipo == '8'){

                options = "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                cuerpo += "<option value='1'>ACTIVO</option>";
                cuerpo += "<option value='0'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }


            if(tipo == '_Activo____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                cuerpo += "<option value='-1'>ACTIVO</option>";
                cuerpo += "<option value='0'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }

            if(tipo == 'ListaCla'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
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

                $("#valores_restableses_"+id).html(cuerpo);

            }

            if(tipo == '_Estado____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                cuerpo += "<option value='0'>SIN GESTI&Oacute;N</option>";
                cuerpo += "<option value='1'>REINTENTO AUTOMATICO</option>";
                cuerpo += "<option value='2'>AGENDA</option>";
                cuerpo += "<option value='3'>NO REINTENTAR</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }

            if(tipo == '_ConIntUsu_b'){

                $.ajax({
                    url    : '<?=$url_crud_extender?>?getUsuariosCampanha=true',
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

                        $("#valores_restableses_"+id).html(cuerpo);
                        changeRenderizado();
                    }
                });
            }

            if(tipo == 'MONOEF'){
                $.ajax({
                    url    : '<?=$url_crud_extender?>?getMonoefCampanha=true',
                    type   : 'post',
                    data   : { campan : $("#id_campanaFiltros").val() },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                        $.each(JSON.parse(data), function(i, item) {
                            cuerpo += "<option value='"+item.MONOEF_ConsInte__b+"'>"+ item.MONOEF_Texto_____b+"</option>";
                        }); 
                            cuerpo += "<option value='-12'>Mensaje enviado</option>";
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);
                        changeRenderizado();
                    }
                });
            }

            if(tipo == '6'){
            options = "<option value='='>IGUAL A</option>";
            options += "<option value='!='>DIFERENTE DE</option>";
                $.ajax({
                    url    : '<?=$url_crud_extender?>',
                    type   : 'post',
                    data   : { lista : valor , getListasDeEsecampo : true },
                    dataType: 'json',
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                        $.each(data, function(i, item) {
                            cuerpo += "<option value='"+item.LISOPC_ConsInte__b+"'>"+ item.LISOPC_Nombre____b+"</option>";
                        }); 
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);
                        changeRenderizado();
                    }
                });
            }

            if(tipo == '11'){
            options = "<option value='='>IGUAL A</option>";
            options += "<option value='!='>DIFERENTE DE</option>";
                $.ajax({
                    url    : '<?=$url_crud_extender?>',
                    type   : 'post',
                    data   : { lista : valor , getListasCompleja : true },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                        cuerpo += data;
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);
                        changeRenderizado();
                    }
                });
            }


            $("#condicion_"+id).html(options);


            changeRenderizado();
            // Desabilito la opcion de validar apertura por defecto ya que al armar condiciones lo que hace es que no respeta la logica que realiza el usuario
            // validarAperturaPorDefecto(valor, id);
        
        });

        $(".deleteFiltro").click(function(){
            var id = $(this).attr('id');
            $("#id_"+id).remove();
        });

        changeCampoApertura();
        cambioEtiquetaApertura();
    }
    $(function(){

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
        $("#new_filtro").click(function(){
            
            agregarFiltrosCondciones();
        });
        $(".Radio_condiciones").change(function(){
            if($(this).val() == 1){
                $("#FILTROS").html("");
                $("#div_filtros_campan").hide();
                $("#div_lista_de_registros").hide();
                $('#campo_a_filtrar').prop('selectedIndex',0);
                $('#listaExcell').val('');
                $("#divPrevisualizacion").hide()
            }else if($(this).val() == 2){
                $("#txt_cantidad_registrps").val('');
                $("#div_cantidad_campan").hide();
                $("#div_lista_de_registros").hide();
                $('#campo_a_filtrar').prop('selectedIndex',0);
                $('#listaExcell').val('');
                $("#div_filtros_campan").show();
                $("#FILTROS").html("");
                $("#divPrevisualizacion").show()
            }else if($(this).val() == 3){
                $("#FILTROS").html("");
                $("#div_filtros_campan").hide();
                $("#div_lista_de_registros").show();
                $("#divPrevisualizacion").hide()
            }    

        });
        var van_delete = 1;
        $("#new_filtro_delete").click(function(){

            var rowFilDel = document.getElementsByClassName("rowFilDel").length;
            var strHTMLOpcion_t = "";
            var strHTMLDelete_t = "";
            var intClMd_t = "4";
            var intLista_t = "5";

            if (rowFilDel>0) {

                intClMd_t = "3";
                intLista_t = "3";

                strHTMLOpcion_t += "<div class='col-md-2'>";
                    strHTMLOpcion_t += "<div class='form-group'>";
                        strHTMLOpcion_t += "<select class='form-control' name='andOr_"+van_delete+"' id='andOr_"+van_delete+"' numero='"+van_delete+"'>";
                            strHTMLOpcion_t += "<option selected value='1'>Y</option>";
                            strHTMLOpcion_t += "<option value='0'>O</option>";
                        strHTMLOpcion_t += "</select>";
                    strHTMLOpcion_t += "</div>";
                strHTMLOpcion_t += "</div>";

                strHTMLDelete_t += "<div class='col-md-1'>";
                    strHTMLDelete_t += "<div class='form-group'>";
                        strHTMLDelete_t += "<button class='btn btn-danger btn-sm deleteFiltro'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+van_delete+"'><i class='fa fa-trash-o'></i></button>";
                    strHTMLDelete_t += "</div>";
                strHTMLDelete_t += "</div>";

            }

            var cuerpo = "<div class='row rowFilDel' id='id_"+van_delete+"'>";

                    cuerpo += strHTMLOpcion_t;

                    cuerpo += "<div class='col-md-"+intLista_t+"'>";
                        cuerpo += "<div class='form-group'>";
                            cuerpo += "<select class='form-control mi_select_pregun' name='pregun_"+van_delete+"'  id='pregun_"+van_delete+"' numero='"+van_delete+"'>";
                                cuerpo += "<option value='0' tipo='3'>Seleccione</option>";
                                cuerpo += "<option value='_CoInMiPo__b' tipo='3'>ID BD</option>";
                                cuerpo += "<option value='_FechaInsercion' tipo='5'>FECHA CREACION</option>";


                                $.each(datosGuion, function(i, item) {
                                    cuerpo += "<option value='"+item.PREGUN_ConsInte__b+"' tipo='"+item.PREGUN_Tipo______b+"'>"+ item.PREGUN_Texto_____b+"</option>";
                                }); 

                                cuerpo += "<option value='_Estado____b' tipo='_Estado____b'>ESTADO_DY (tipo reintento)</option>";
                                cuerpo += "<option value='_ConIntUsu_b' tipo='_ConIntUsu_b'>Usuario</option>";
                                cuerpo += "<option value='_NumeInte__b' tipo='3'>Numero de intentos</option>";
                                cuerpo += "<option value='_UltiGest__b' tipo='MONOEF'>Ultima gesti&oacute;n</option>";
                                cuerpo += "<option value='_GesMasImp_b' tipo='MONOEF'>Gesti&oacute;n mas importante</option>";
                                cuerpo += "<option value='_FecUltGes_b' tipo='5'>Fecha ultima gesti&oacute;n</option>";
                                cuerpo += "<option value='_FeGeMaIm__b' tipo='5'>Fecha gesti&oacute;n mas importante</option>";
                                cuerpo += "<option value='_ConUltGes_b' tipo='ListaCla'>Clasificaci&oacute;n ultima gesti&oacute;n</option>";
                                cuerpo += "<option value='_CoGesMaIm_b' tipo='ListaCla'>Clasificaci&oacute;n mas importante</option>";
                                cuerpo += "<option value='_Activo____b' tipo='_Activo____b'>Registro activo</option>";

                            cuerpo += "</select>";

                        cuerpo += "</div>";
                    cuerpo += "</div>";

                    cuerpo += "<div class='col-md-3'>";
                        cuerpo += "<div class='form-group'>";
                            cuerpo += "<select name='condicion_"+van_delete+"' id='condicion_"+van_delete+"' class='form-control'>";
                                cuerpo += "<option value='0'>Condici&oacute;n</option>";
                            cuerpo += "</select>";
                        cuerpo += "</div>";
                    cuerpo += "</div>";


                    cuerpo += "<div class='col-md-"+intClMd_t+"' id='valores_restableses_"+van_delete+"' >";
                        cuerpo += "<div class='form-group'>";
                            cuerpo += "<input type='text'  name='valor_"+van_delete+"' id='valor_"+van_delete+"' class='form-control' placeholder='<?php echo $filtros_valor__c; ?>'>";
                        cuerpo += "</div>";
                    cuerpo += "</div>";

                    cuerpo += "<input type='hidden' name='tipo_campo_"+van_delete+"' id='tipo_campo_"+van_delete+"' class='form-control'>";

                    cuerpo += strHTMLDelete_t;

                cuerpo += "</div>";
                
                van_delete++;

                $("#FILTROS_delete").append(cuerpo);

                $(".mi_select_pregun").change(function(){
                    var id = $(this).attr('numero');
                    var tipo = $("#pregun_"+id+" option:selected").attr('tipo');
                    var valor = $(this).val();

                    $("#tipo_campo_"+id).val(tipo);

                    var options = "";
                    if(tipo == '1' || tipo == '2'){

                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                        options += "<option value='LIKE_1'>INICIE POR</option>";
                        options += "<option value='LIKE_2'>CONTIENE</option>";
                        options += "<option value='LIKE_3'>TERMINE EN</option>";

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }

                    if(tipo == '4' || tipo == '3'){

                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                        options += "<option value='>'>MAYOR QUE</option>";
                        options += "<option value=' < '>MENOR QUE</option>";

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                        $("#valor_"+id).numeric();
                    }

                    if(tipo == '5'){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='FECHA'>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                        options += "<option value='>'>MAYOR QUE</option>";
                        options += "<option value=' < '>MENOR QUE</option>";

                        $("#valor_"+id).datepicker({
                            language: "es",
                            autoclose: true,
                            todayHighlight: true
                        });
                    }

                    if(tipo == '10'){

                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                        options += "<option value='>'>MAYOR QUE</option>";
                        options += "<option value=' < '>MENOR QUE</option>";

                        cuerpo = "<div class='form-group'><input type='time' max='23:59:59' min='00:00:00' step='1' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='00:00:00'></div>";

                        $("#valores_restableses_"+id).html(cuerpo);
                    }

                    if( tipo == '_Activo____b' || tipo == '_Estado____b' || tipo == '_ConIntUsu_b' || tipo == 'MONOEF' || tipo == 'ListaCla' || tipo == '8' || tipo == '6'){
                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                    } 

                    if(tipo == '8'){

                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                        cuerpo += "<option value='1'>ACTIVO</option>";
                        cuerpo += "<option value='0'>NO ACTIVO</option>";
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }


                    if(tipo == '_Activo____b'){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                        cuerpo += "<option value='-1'>ACTIVO</option>";
                        cuerpo += "<option value='0'>NO ACTIVO</option>";
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }

                    if(tipo == 'ListaCla'){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
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

                        $("#valores_restableses_"+id).html(cuerpo);

                    }

                    if(tipo == '_Estado____b'){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                        cuerpo += "<option value='0'>SIN GESTI&Oacute;N</option>";
                        cuerpo += "<option value='1'>REINTENTO AUTOMATICO</option>";
                        cuerpo += "<option value='2'>AGENDA</option>";
                        cuerpo += "<option value='3'>NO REINTENTAR</option>";
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }

                    if(tipo == '_ConIntUsu_b'){

                        $.ajax({
                            url    : '<?=$url_crud_extender?>?getUsuariosCampanha=true',
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

                                $("#valores_restableses_"+id).html(cuerpo);
                            }
                        });
                    }

                    if(tipo == 'MONOEF'){
                        $.ajax({
                            url    : '<?=$url_crud_extender?>?getMonoefCampanha=true',
                            type   : 'post',
                            data   : { campan : $("#id_campanaFiltros").val() },
                            success : function(data){
                                cuerpo  = "<div class='form-group'>";
                                cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                                $.each(JSON.parse(data), function(i, item) {
                                    cuerpo += "<option value='"+item.MONOEF_ConsInte__b+"'>"+ item.MONOEF_Texto_____b+"</option>";
                                }); 
                                cuerpo += "</select>";
                                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                                cuerpo += "</div>";  

                                $("#valores_restableses_"+id).html(cuerpo);
                            }
                        });
                    }

                        if(tipo == '6'){
                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                            $.ajax({
                                url    : '<?=$url_crud_extender?>',
                                type   : 'post',
                                data   : { lista : valor , getListasDeEsecampo : true },
                                dataType: 'json',
                                success : function(data){
                                    cuerpo  = "<div class='form-group'>";
                                    cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                                    $.each(data, function(i, item) {
                                        cuerpo += "<option value='"+item.LISOPC_ConsInte__b+"'>"+ item.LISOPC_Nombre____b+"</option>";
                                    }); 
                                    cuerpo += "</select>";
                                    cuerpo += "</div>";  

                                    $("#valores_restableses_"+id).html(cuerpo);
                                }
                            });
                        }

                        if(tipo == '11'){
                        options = "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                            $.ajax({
                                url    : '<?=$url_crud_extender?>',
                                type   : 'post',
                                data   : { lista : valor , getListasCompleja : true },
                                success : function(data){
                                    cuerpo  = "<div class='form-group'>";
                                    cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                                    cuerpo += data;
                                    cuerpo += "</select>";
                                    cuerpo += "</div>";  

                                    $("#valores_restableses_"+id).html(cuerpo);
                                }
                            });
                        }


                        $("#condicion_"+id).html(options);
                    });

                    
                    $(".deleteFiltro").click(function(){
                        var id = $(this).attr('id');
                        $("#id_"+id).remove();
                    });
        });
        $(".Radio_condiciones_delete").change(function(){
            if($(this).val() == 1){
                $("#FILTROS_delete").html("");
                $("#div_filtros_campan_delete").hide();
                $("#div_lista_de_registros_delete").hide();
                $('#campo_a_filtrar_delete').prop('selectedIndex',0);
                $('#listaExcell_delete').val('');
            }else if($(this).val() == 2){
                $("#div_lista_de_registros_delete").hide();
                $('#campo_a_filtrar_delete').prop('selectedIndex',0);
                $('#listaExcell_delete').val('');
                $("#div_filtros_campan_delete").show();
                $("#FILTROS_delete").html("");
            }else if($(this).val() == 3){
                $("#FILTROS_delete").html("");
                $("#div_filtros_campan_delete").hide();
                $("#div_lista_de_registros_delete").show();
            }    

        });
        $("#aplicar_filtros_delete").click(function(){

            valor = 1;

            $(".Radio_condiciones_delete").each(function(){
                if($(this).is(":checked")){
                    valor = $(this).val();
                }
            });

            valido = 1;

            var contador = new Array();
            $(".mi_select_pregun").each(function(){
                contador.push($(this).attr("numero"));
            });

            if(valido == 1){
                var dtao = new FormData($("#consulta_campos_where_delete")[0]);
                dtao.append('contador' , contador);
                $.ajax({
                    url  : "<?php echo $url_crud;?>?delete_base=true",
                    type : "post",
                    data : dtao,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(data){

                        if (data == "exito" || data == "exitoexito") {
                            alertify.success("<?php echo $exito_top_regist; ?>");
                        }else if(data == "error_2"){
                            alertify.error("Debe agregar filtros.");
                        }else if(data == "error_5"){
                            alertify.error("Los registros deben estar en la columna 'A' del Excell.");
                        }else if(data == "error_4"){
                            alertify.error("'Campo a filtrar' y 'Carga Lista' son Obligatorios.");
                        }else if(data == "error_1"){
                            alertify.error("Hay problemas con la campaña");
                        }else{
                            alertify.error("El proceso ha fallado.");
                        }
                        $("#div_filtros_campan_delete").hide();
                        $("#FILTROS_delete").html("");
                        $("#filtros_campanha_delete").modal('hide');
                        $("#consulta_campos_where_delete")[0].reset();
                        $("#div_lista_de_registros_delete").hide();
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000, 
                            message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                        });
                    },
                    complete : function(){
                        $.unblockUI();
                    }
                }); 
            }
        });
        $("#aplicar_filtros").click(function(){

            var valor = 1;
            var valido = 1;

            $(".Radio_condiciones").each(function(){
                if($(this).is(":checked")){
                    valor = $(this).val();
                }
            });

            if(valor == 1){
                valido=validaDatos();
            }

            if(valor == 2){
                if(van == 1){
                    alertify.warning("Debe agregar un filtro en las condiciones");
                    $("#new_filtro").click();
                    $("#pregun_1").focus();
                    valido=0;
                }else{
                    valido=validaDatos();
                }
            }

            if(valor == 3){
                if($("#campo_a_filtrar").val() == '0' || $("#listaExcell").val() == ''){
                    alertify.warning("Debe seleccionar el campo a filtrar y cargar un archivo");
                    $("#campo_a_filtrar").focus();
                    valido=0;
                }else{
                    valido=validaDatos();
                }
            }

            if($("#sel_tipo_reintento_acciones").val() == '2'){
                if($("#txt_fecha_reintento_acciones").val() < 1){
                    alertify.error("<?php echo $error_fec_regist;?>");
                    valido = 0;
                    $("#txt_fecha_reintento_acciones").focus();
                }
                if($("#txt_hora_reintento_acciones").val() < 1){
                    alertify.error("<?php echo $error_hor_regist;?>");
                    valido = 0;
                    $("#txt_hora_reintento_acciones").focus();
                }
            }


            var contador = new Array();
            $(".mi_select_pregun").each(function(){
                contador.push($(this).attr("numero"));
            });
            if(valido == 1){
                var dtao = new FormData($("#consulta_campos_where")[0]);
                dtao.append('contador' , contador);
                $.ajax({
                    url  : "<?php echo $url_crud;?>?administrar_base=true",
                    type : "post",
                    data : dtao,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(data){
                        
                        if (data == "Exito" || data == "ExitoExito") {
                            alertify.success("<?php echo $exito_top_regist; ?>");
                        }else if(data == "error_2"){
                            alertify.error("Debe agregar filtros.");
                        }else if(data == "error_5"){
                            alertify.error("Los registros deben estar en la columna 'A' del Excell.");
                        }else if(data == "error_4"){
                            alertify.error("'Campo a filtrar' y 'Carga Lista' son Obligatorios.");
                        }else{
                            alertify.error("El proceso ha fallado.");
                        }
                        $("#div_filtros_campan").hide();
                        $("#FILTROS").html("");
                        $("#filtros_campanha").modal('hide');
                        $("#consulta_campos_where")[0].reset();
                        $("#conf_fechas_reintento").hide();
                        $("#div_lista_de_registros").hide();
                        let src = $("#ifrVisorColaMArcador").attr("src");
                        $("#ifrVisorColaMArcador").attr("src", src);
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000, 
                            message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                        });
                    },
                    complete : function(){
                        $.unblockUI();
                    }
                }); 
            }
        });

        $("#TxtFechaReintentoAcciones").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        $("#TxtHoraReintentoAcciones").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#txt_fecha_reintento_acciones").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        $("#txt_hora_reintento_acciones").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#selTipoReintentoAcciones").change(function(){
            if($(this).val() == '2'){
                $("#confFechasReintento").show();
            }else{
                $("#confFechasReintento").hide();
            }
        });

        $("#sel_tipo_reintento_acciones").change(function(){
            if($(this).val() == '2'){
                $("#conf_fechas_reintento").show();
            }else{
                $("#conf_fechas_reintento").hide();
                $("#txt_fecha_reintento_acciones").val("");
                $("#txt_hora_reintento_acciones").val("");
            }
        });

        $("#sel_tipificaciones_acciones").change(function(){
            let estado=$("option:selected", this).attr('estado');
            $("#sel_tipo_reintento_acciones").val('0').trigger('change');

            if(estado == 'Agenda'){
                $("#sel_tipo_reintento_acciones").val('2').trigger('change');
            }

            if(estado == 'Reintento automatico'){
                $("#sel_tipo_reintento_acciones").val('1').trigger('change');
            }

            if(estado == 'No reintentar'){
                $("#sel_tipo_reintento_acciones").val('3').trigger('change');
            }
        });

        $("#filtros_campanha").on('hidden.bs.modal', function () {
            van= 1;
            $("#FILTROS").empty();
        });



        
    });

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
        $("#FILTROS :input:not('button')").not('input[id^="tipo_campo"]').change(function () {
            renderizarCondiciones();
        })

        $("#CONDICIONES_TAREAS :input:not('button')").not('input[id^="tipo_campo"]').change(function () {
            renderizarCondiciones('CONDICIONES_TAREAS');
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

    function renderizarCondiciones(tipo = 'adminRegistros'){

        let identificador = "FILTROS";

        if(tipo == "CONDICIONES_TAREAS"){
            identificador = "CONDICIONES_TAREAS";
        }

        let texto = '';
        let newCuantosvan = $("#"+identificador+" :input:not('button')").not('input[id^="tipo_campo"]').last().attr("numero");

        for (let index = 1; index <= newCuantosvan; index++) {

            if($("#"+identificador+" div#id_"+index).length){

                let textoApertura = $("#andOr_"+index+" option:selected").html() ?? " ";

                let apertura = '<b>'+ textoApertura +'</b>';
                let pregunta = $("#pregun_"+index+" option:selected").html();
                let condicion = '<i>'+$("#condicion_"+index+" option:selected").text()+'</i>';
                let cierre = '<b>'+$("#cierre"+index).val()+'</b>';
                let valor = '';

                // Para el valor tengo que validar la tipo de pregunta
                let tipoPregunta = $("#pregun_"+index+" option:selected").attr('tipo');
                if(tipoPregunta == 'ListaCla' || tipoPregunta == '8' || tipoPregunta == '_Activo____b' || tipoPregunta == '_Estado____b' || tipoPregunta == '6' || tipoPregunta == '11' || tipoPregunta == '_ConIntUsu_b' || tipoPregunta == 'MONOEF' || tipoPregunta == '_CanalUG_b'){
                    valor = $("#valor_"+index+" option:selected").html();
                    console.log("#valor_"+index+" option:selected")
                }else{
                    valor = $("#valor_"+index).val();
                }

                texto += ` ${apertura} ${pregunta} ${condicion} ${valor} ${cierre} `;
            }
        }

        if(tipo == 'CONDICIONES_TAREAS'){
            $("#textoPrevisualizacionTareas").html('Condicion cuando ' + texto);
            if(!ValidarParentesis(texto)){
                // $("#aplicar_filtros").attr("disabled",true);
                $("#errorCondicionesTareas").html('Los parentesis estan mal configurados, hay mas abiertos o cerrados');
            }else{
                // $("#aplicar_filtros").attr("disabled",false);
                $("#errorCondicionesTareas").html('');
            }
        }else{
            $("#textoPrevisualizacion").html('Condicion cuando ' + texto);

            if(!ValidarParentesis(texto)){
                $("#aplicar_filtros").attr("disabled",true);
                $("#errorCondiciones").html('Los parentesis estan mal configurados, hay mas abiertos o cerrados');
            }else{
                $("#aplicar_filtros").attr("disabled",false);
                $("#errorCondiciones").html('');
            }
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

        $(".mi_select_pregun").each((indexPregun, elementPregun) => {
            let idNumeroPregun = $(elementPregun).attr("numero");
            // Si en los filtros hay un campo repetido por defecto ponemos en la condicion un OR
            if(idNumeroPregun != numero && numero != 1){
                if($(elementPregun).val() == idPregun){
                    $("#andOr_"+numero).val("OR").change();
                }
            }

        })
    }

    function cambiarTipoValor(tipollamada){
        $("#consultaCamposWhere")[0].reset();
        if(tipollamada != '1'){
            $(".condicionesTop").hide();
        }else{
            $(".condicionesTop").show();
        }

        if(tipollamada == '5'){
            $("#divAccionesCampan").show();
        }else{
            $("#divAccionesCampan").hide();
        }
        $("#tipoLlamado").val(tipollamada);
        $("#txtCantidadRegistrps").val('');
        $("#divCantidadCampan").hide();
        $("#divFiltrosCampan").hide();
        $("#filtros").html("");
    }

    function validaDatos(){
        let validaAccion=0;
        $(".acciones").each(function(){
            if($('option:selected', this).html() != 'Seleccione'){
                validaAccion = 1;
            }

        });

        if(validaAccion == 0){
            alertify.warning("Debe seleccionar al menos una acción");
            validado=0;
        }else{
            validado=1;
        }
        return validado;
    }
</script>

<!-- Funciones del modal de aliminar tipificacion -->
<script type="text/javascript">
    $(document).ready(function(){
         
        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getEstados : true, paso : '<?php echo $_GET['id_paso']; ?>'},
            success : function(data){
                estados = data;
                $("#selEstadosAcciones").html(data);
                $("#sel_estados_acciones").html(data);
            }
        });

        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getTipificacionesCampanas : true, paso : '<?php echo $_GET['id_paso'];?>' , idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'},
            success : function(data){
                $("#opciones").html(data);

                $(".deleteFirme").click(function(){
                    var id = $(this).attr('id');
                    $("#idValorDel").val(id);
                    $.ajax({
                        url     : '<?=$url_crud_extender?>',
                        type    : 'post',
                        data    : { getTipificaciones : true, paso : '<?php echo $_GET['id_paso'];?>', noMuestreEsta : id},
                        beforeSend : function(){
                            $.blockUI({ 
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                            });
                        },
                        complete : function(){
                            $.unblockUI();
                        },
                        success : function(data){
                            $("#selTipificacionesEliminadas").html(data);
                            $("#ModalPreguntaTipificacion").modal();
                        }
                    });
                    
                });
            }
        });  

        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getTipificaciones : true, paso : '<?php echo $_GET['id_paso'];?>'},
            success : function(data){
                $("#selTipificacionesEliminadas").html(data);
                $("#selTipificacionesAcciones").html(data);
                $("#sel_tipificaciones_acciones").html(data);
            }
        });

        $("#btnSaveEliminacionTipificaciones").click(function(){
            $.ajax({
                url     : '<?=$url_crud_extender?>',
                type    : 'post',
                data    : {
                    idLisopc : $("#idValorDel").val(),
                    idBd: '<?=$_GET['poblacion']?>',
                    idNuevaTipificacion : $("#selTipificacionesEliminadas").val(),
                    pregun : '<?php echo $guion['G5_C311']; ?>',
                    crudLisopcDelete : true
                },
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                    });
                },
                complete : function(){
                    $.unblockUI();
                },
                success:function(data){
                    if(data != ''){
                        alertify.error('<?php echo $error_de_proceso; ?>');
                    }else{
                        alertify.success('<?php echo $str_Exito; ?>');
                        var id = $("#idValorDel").val();
                        $("#id_"+id).remove();
                        $("#ModalPreguntaTipificacion").modal('hide');
                    }
                }
            });
        });
    });
</script>

<!-- Fucniones de edicion de listas -->
<script type="text/javascript">
    var contadorListaCampana = 0;
    $(function(){

        $("#editEstados").click(function(){
            $("#agrupador")[0].reset();
            $("#opcionesListaHtml").html('');
            contadorListaCampana = 0;
            $("#hidListaInvocada").val(<?php echo $datosListas['OPCION_ConsInte__b']; ?>);
            $.ajax({
                url     : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                type    : 'post',
                data    : { getListasEdit : true, idOpcion : '<?php echo $datosListas['OPCION_ConsInte__b']; ?>' },
                dataType: 'json',
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                    });
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
                            cuerpo += "<div class='col-md-10 col-xs-10'>";
                            cuerpo += "<div class='form-group'>";
                            cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                            cuerpo += "</div>";
                            cuerpo += "</div>";
                            cuerpo += "<div class='col-md-2 col-xs-2' style='text-align:center;'>";
                            cuerpo += "<div class='form-group'>";
                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                            cuerpo += "</div>";
                            cuerpo += "</div>";
                            cuerpo += "</div>";
                            $("#opcionesListaHtml").append(cuerpo);
                        });
                        $("#opcionesListaHtml").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                        contadorListaCampana = data.total;
                        console.log(contadorListaCampana);
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
                                                message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                                            });
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

                        $("#NuevaListaModal").modal();
                    }
                }
            });
   
        });

        $("#guardarLista").click(function(){
            var validator = 0;
            if($("#txtNombreLista").val().length < 1){
                validator = 1;
                alertify.error("Es necesario el nombre de la lista");
            }

            if(contadorListaCampana == 0){
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
                formData.append('idGuion', '<?php echo $guion['G10_C74']; ?>');
                formData.append('contador', contadorListaCampana);
                $.ajax({
                    url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php?insertarDatosLista=si',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                        });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success: function(data){
                        if(data != '0'){
                            alertify.success("<?php echo $str_Exito;?>");
                            var id = $("#hidListaInvocada").val();
                            $.ajax({
                                url     : '<?=$url_crud_extender?>',
                                type    : 'post',
                                data    : { getEstados : true, paso : '<?php echo $_GET['id_paso']; ?>'},
                                success : function(data){
                                    estados = data;
                                    $(".estadosCTX").html(data);
                                }
                            });
                            $("#NuevaListaModal").modal('hide');
                        }else{
                            alertify.error("<?php echo $error_de_proceso;?>");
                        }
                    }
                });    
            }
            
        });

        $("#newOpcionlistas").click(function(){
            var cuerpo = "<div class='row' id='id_"+cuantosVan+"'>";
            cuerpo += "<div class='col-md-10 col-xs-10'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' name='opciones[]' class='form-control opcionesGeneradas' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "<div class='col-md-2 col-xs-2' style='text-align:center;'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<button class='btn btn-danger btn-sm deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+cuantosVan+"'><i class='fa fa-trash-o'></i></button>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuantosVan++;
            contador++;
            $("#opcionesListaHtml").append(cuerpo);

            
            
            $(".deleteopcion").click(function(){
                var id = $(this).attr('id');
                $("#id_"+id).remove();
                //contador = contador -1;
            });
        });
        
            
            
            
    });
</script>

<!-- Funciones de edicion de Caminc -->
<script type="text/javascript">
    $(document).ready(function(){
         
        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { cargarEnlacesBDForm : true, paso : '<?php echo $_GET['id_paso'];?>' , idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'},
            success : function(data){
                $("#htmlCamminc").html(data);

                $(".btnDeleteEsto").click(function(){
                    var id_camin = $(this).attr('idCam');
                    var fila = $(this).attr('fila');
                    alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
                        if (e) {
                            $.ajax({
                                url     : '<?=$url_crud_extender?>',
                                type    : 'post',
                                data    : {
                                    deleteCaminc : true,
                                    idCaminc : id_camin
                                },
                                success : function(data){
                                    if(data == '0'){
                                        alertify.success('<?php echo $str_Exito; ?>');
                                        $("#"+fila).remove();
                                    }else{
                                        alertify.error(data);
                                    }
                                }
                            });
                        }
                    }); 
                    
                });

                longitudCaminc = $("#htmlCamminc tr").length;
            }
        });

        $.ajax({
            url:'<?=$url_crud_extender?>',
            type:'POST',
            dataType:'JSON',
            data:{getCamposBusquedaManual:'si', idBd:'<?=$_GET['poblacion']?>'},
            success:function(data){
                if(data.estado){
                    let i=0;
                    $.each(data.data,function(i,item){

                        let label=$("<label>",{
                            'class':"btn btn-block btn-sm btn-default",
                            'html':item.nombre,
                            'style':'margin-top:20px;'
                        });

                        let div=$("<div>",{
                            'class':'div_'+i,
                            'id':'div_'+i,
                            'style':'min-height:60px;'
                        });
                        
                        $('#disponible,#seleccionado').append(label);
                        $('#disponible,#seleccionado').append(div);

                        $("#disponible .div_"+i).sortable({
                            connectWith: "#seleccionado .div_"+i
                        });
                        
                        $("#seleccionado .div_"+i).sortable({
                            connectWith: "#disponible .div_"+i
                        });
                        
                        $.each(item.campos,function(e,element){
                            let li=$("<li>",{
                                'data-id':element.campo,
                                'data-guion':element.guion,
                                'style':'padding:0px 10px;'
                            }).append(
                                $("<table>",{
                                    'class':"table table-hover"
                                }).append(
                                    $("<tr>").append(
                                        $("<td>",{
                                            'width':'40px'
                                        }).append(
                                            $("<input>",{
                                                'type':'checkbox',
                                                'class':'flat-red mi-check'
                                            })
                                        )
                                    ).append(
                                        $("<td>",{
                                            'class':'nombre',
                                            'html':element.texto
                                        })
                                    )
                                )
                            );

                            if(element.busqueda==0){
                                $("#disponible .div_"+i).append(li).addClass("disponible_"+element.guion);
                            }else{
                                $("#seleccionado .div_"+i).append(li).addClass("seleccionado_"+element.guion);
                            }
                        });
                        i++;
                    });
                }else{
                    alertify.error("No se pudo obtener los campos de búsqueda de la base de datos");
                }
            }
        });

        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getConfigBusqueda : true, poblacion : '<?php echo $_GET['poblacion'];?>'},
            dataType:'JSON',
            success : function(data){
                if(data['estado'] == 'ok'){
                    $("#insertRegistro").val(data['data']['permiteInsertar']).trigger('change');
                    $("#insertAuto").val(data['data']['insertAuto']).trigger('change');
                }
            }
        });

        $("#NewAsociacion").click(function(){
            
            var campo = '<tr id="'+ longitudCaminc +'">';
            campo += '<td>';
            campo += '<select class="form-control asocias" id="asocioa_'+ longitudCaminc +'" name="asocioa_'+ longitudCaminc +'" gemelo = "'+ longitudCaminc +'">';
            campo += '<option value="0"><?php echo $str_seleccione; ?></option>';
            campo += '<option value="G<?php echo $_GET["poblacion"]; ?>_ConsInte__b" tipo="3">ID</option>';
            campo += '<option value="G<?php echo $_GET["poblacion"]; ?>_FechaInsercion" tipo="5">FECHA INSERCION</option>';
            $.each(datosGuion, function(i, item){
                campo += '<option value="'+ item.PREGUN_ConsInte__b +'" tipo="'+ item.PREGUN_Tipo______b +'" >'+ item.PREGUN_Texto_____b +'</option>';
            });
            campo += '</select>';
            campo += '</td>';
            campo += '<td id="gemelo_'+ longitudCaminc +'">';
            campo += '<select class="form-control" id="asocioaG_'+ longitudCaminc +'" name="asocioaG_'+ longitudCaminc +'" gemelo = "'+ longitudCaminc +'">';
            campo += '</select>';
            campo += '</td>';
            campo += '<td  style="text-align:center;">';
            campo += "<button title='<?php echo $campan_dbfor_3_; ?>' class='btn btn-danger btn-sm btnDeleteEsto' type='button' fila='"+ longitudCaminc +"'><i class='fa fa-trash-o'></i></button>";
            campo += '</td>';
            campo += '</tr>';

            $("#htmlCamminc").append(campo);

            $("#asocioa_"+longitudCaminc).change(function(){

                var longitud2 = $(this).attr('gemelo');
                var tipo = $("#asocioa_"+ longitud2 +" option:selected").attr('tipo');
                var campo = '<option value="0"><?php echo $str_seleccione; ?></option>';
                $.each(datosForms, function(i , item){
                    if(item.PREGUN_Tipo______b == tipo){
                        campo += '<option value="'+ item.PREGUN_ConsInte__b +'" tipo="'+ item.PREGUN_Tipo______b +'" >'+ item.PREGUN_Texto_____b +'</option>';
                    }
                });
                $("#asocioaG_"+longitud2).html(campo);
            });

            $(".btnDeleteEsto").click(function(){
                var longitud2 = $(this).attr('fila');
                $("#"+longitud2).remove();
            });

            longitudCaminc++;
        });

        $("#poblacion").val($("#G10_C74").val());
    });
</script>


<!-- Modal para seleccionar el tipo de ejecución en el administrador de registros -->
<div class="modal fade" id="TipoEjecucionModal" tabindex="-1" role="dialog" aria-labelledby="TipoEjecucionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="TipoEjecucionModalLabel">ADMINISTRAR REGISTROS</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                        <h2 class="modal-title" style="margin-left: 28%;">Tipo De Ejecución</h2>
                        <h5 class="modal-title" style="margin-left: 40%;">¿Ejecutar Cuando?</h5>
                            <div class="row" style="margin-top: 2%;">
                                <div class="col-4 col-sm-6">
                                    <button type="button" class="btn btn-primary" id="BtnPorEstaVez" style="margin-left: 30%;"> Solo Por Esta Vez  👇🏽</button>
                                </div>
                                <div class="col-8 col-sm-6">
                                    <button type="button" class="btn btn-primary float-left" id="BtnProgramar"> Programar Ejecución  ⏲ </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Para Programación De Tareas -->
<div class="modal fade" id="ProgramarModal" tabindex="-1" role="dialog" aria-labelledby="ProgramarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="ProgramarModalLabel">ADMINISTRAR REGISTROS PROGRAMADOS</h4>
            </div>
            <div class="modal-body">
                <div id="programacion_tareas" class="panel-collapse">
                    <?php 
                        //Obtener el contenido de G38.php
                        // $contenidoG38= file_get_contents($url_crud_programa_tareas);
                        //echo $url_crud_programa_tareas;
                        // echo $contenidoG38;
                    ?>
                    <div id="cuerpoTareas"></div>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modales adicionales de la programacion de tareas -->
<?php 
    $modalsG38= file_get_contents($url_modals_programa_tareas);
    echo $modalsG38;
?>


<!-- Funciones Para Tipo De Ejecución -->
<script>
    $("#BtnPorEstaVez").click(function () {
        $("#filtros_campanha").modal('show');
    });

    $("#BtnProgramar").click(function () {
        $("#ProgramarModal").modal('show');
        //$('#modVisorTareaProgramada').modal('handleUpdate');
    });
</script>

<script>
    $("#BtnAbrirPrograTareas").click(function () {
        $.ajax({
            url  : '<?=base_url?>mostrar_popups.php?view=tareasProgramadas',
            type : 'get',
            success : function(data){
                $("#cuerpoTareas").html(data);
            },
            beforeSend : function(){
                $.blockUI({ message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    });
</script>
