
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
    <div class="modal-dialog" style="width:95%; margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-header" style="padding-right: 5px; padding-bottom: 3px; padding-top: 3px;">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src=""  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php
   //SECCION : Definicion urls
   $url_crud = "cruds/DYALOGOCRM_SISTEMA/G17/G17_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN


    $Zsql = "SELECT G17_ConsInte__b as id, G17_C153 as camp1, G16_C147 as camp2 FROM ".$BaseDatos_systema.".G17  LEFT JOIN ".$BaseDatos_systema.".G16 ON G16_ConsInte__b = G17_C155 ORDER BY G17_C153 DESC LIMIT 0, 50";
    

   $result = $mysqli->query($Zsql);

?>

<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li></li>
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
                                        echo "<tr class='CargarDatos' id='".$obj->id."'>
                                                <td>
                                                    <p style='font-size:14px;'><b>".strtoupper(utf8_encode($obj->camp1))."</b></p>
                                                    <p style='font-size:12px; margin-top:-10px;'>".utf8_encode($obj->camp2)."</p>
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
                        <div>
                            <button class="btn btn-default" id="Save">
                                <i class="fa fa-save"></i>
                            </button>
                            <button class="btn btn-default" id="cancel" >
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
            <?php } ?>
                        <!-- FIN BOTONES -->
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Seccion CORREO SALIENTE -->
                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <?php echo $str_label_title_EE ;?>
                                                </h4>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="G17_C153" id="LblG17_C153"><?php echo $str_label_nombre_EE?></label>
                                                            <input type="text" class="form-control input-sm" id="G17_C153" value=""  name="G17_C153"  placeholder="NOMBRE">
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                    </div>
                                                    <div class="col-md-12 col-xs-12">
                                                        <?php 
                                                        $str_Lsql = "SELECT  G16_ConsInte__b as id , G16_C147 FROM ".$BaseDatos_systema.".G16";
                                                        ?>
                                                        <!-- CAMPO DE TIPO GUION -->
                                                        <div class="form-group">
                                                            <label for="G17_C155" id="LblG17_C155"><?php echo $str_label_cuenta_EE;?></label>
                                                            <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G17_C155" id="G17_C155">
                                                                <?php
                                                                    $Lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_configuracion;";
                                                                    $cur_result = $mysqli->query($Lsql);
                                                                    while ($key = $cur_result->fetch_object()) {
                                                                        echo "<option value='".$key->id."'>".$key->direccion_correo_electronico."</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO LISTA -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Seccion FILTROS -->
                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#id_filtros">
                                                        <?php echo $str_label_filtros ;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="id_filtros" class="panel-collapse collapse in">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G17_C156" id="LblG17_C156"><?php echo $str_label_de_EE;?></label>
                                                                <input type="text" class="form-control input-sm" id="G17_C156" value=""  name="G17_C156"  placeholder="<?php echo $str_label_de_EE;?>">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                        </div>                                          
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G17_C157" id="LblG17_C157"><?php echo $str_label_asunto_EE; ?></label>
                                                                <input type="text" class="form-control input-sm" id="G17_C157" value=""  name="G17_C157"  placeholder="<?php echo $str_label_asunto_EE; ?>">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G17_C158" id="LblG17_C158"><?php echo $str_label_Cuerpo;?></label>
                                                                <textarea class="form-control input-sm" id="G17_C158" name="G17_C158"  placeholder="<?php echo $str_label_Cuerpo;?>"></textarea>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Seccion ACCIONES -->
                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#id_acciones">
                                                        <?php echo $str_label_acciones ;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="id_acciones" class="panel-collapse collapse in">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G17_C159" id="LblG17_C159"><?php echo $str_label_tipo_accion;?></label>
                                                                <select type="text" class="form-control input-sm" id="G17_C159" name="G17_C159">
                                                                    <?php 
                                                                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".LISOPC_SISTEMA WHERE LISOPC_ConsInte__OPCION_b = 49";
                                                                        $res_Lsql = $mysqli->query($Lsql);
                                                                        while ($key = $res_Lsql->fetch_object()) {
                                                                            echo "<option value='".$key->LISOPC_ValorNum_b."'>".$key->LISOPC_Nombre____b."</option>";
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                        </div>                                          
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-xs-12">
                                                            <?php 
                                                            $str_Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__PROYEC_b =".$_SESSION['HUESPED']." ORDER BY GUION__Nombre____b ASC";
                                        
                                                            ?>
                                                            <!-- CAMPO DE TIPO GUION -->
                                                            <div class="form-group">
                                                                <label for="G17_C161" id="LblG17_C161"><?php echo $str_label_BD_EE;?></label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G17_C161" id="G17_C161">
                                                                    <option value="0">NOMBRE</option>
                                                                    <?php
                                                                        /*
                                                                            SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                                                        */
                                                                        $combo = $mysqli->query($str_Lsql);
                                                                        while($obj = $combo->fetch_object()){
                                                                            if($_GET['poblacion'] == $obj->GUION__ConsInte__b){
                                                                                echo "<option value='".$obj->GUION__ConsInte__b."' dinammicos='0' selected>".utf8_encode($obj->GUION__Nombre____b)."</option>";
                                                                            }else{
                                                                                echo "<option value='".$obj->GUION__ConsInte__b."' dinammicos='0'>".utf8_encode($obj->GUION__Nombre____b)."</option>";
                                                                            }
                                                                            
                                                                        }    
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div>
                                                        <div class="col-md-4 col-xs-4">
                                                            <?php 
                                                            $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5";
                                                            ?>
                                                            <!-- CAMPO DE TIPO GUION -->
                                                            <div class="form-group">
                                                                <label for="G17_C196" id="LblG17_C196"><?php echo $str_label_formularios;?></label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G17_C196" id="G17_C196">
                                                                    <option>NOMBRE</option>
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
                                                        <div class="col-md-4 col-xs-4">
                                                            <!-- CAMPO DE TIPO LISTA -->
                                                            <div class="form-group">
                                                                <label for="G17_C197" id="LblG17_C197"><?php echo $str_label_tipo_busqueda; ?></label>
                                                                <select class="form-control input-sm str_Select2"  style="width: 100%;" name="G17_C197" id="G17_C197">
                                                                    <option value="0">Seleccione</option>
                                                                    <?php
                                                                        /*
                                                                            SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                                                        */
                                                                        $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 50";

                                                                        $obj = $mysqli->query($str_Lsql);
                                                                        while($obje = $obj->fetch_object()){
                                                                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".utf8_encode($obje->OPCION_Nombre____b)."</option>";
                                                                        }    
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div>
                                                        <div class="col-md-4 col-xs-4">
                                                            <?php 
                                                            $str_Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6";
                                                            ?>
                                                            <!-- CAMPO DE TIPO GUION -->
                                                            <div class="form-group">
                                                                <label for="G17_C198" id="LblG17_C198"><?php echo $str_label_campo_Busqueda; ?></label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G17_C198" id="G17_C198">
                                                                    <option>TEXTO</option>
                                                                    <?php
                                                                        /*
                                                                            SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                                                        */
                                                                        $combo = $mysqli->query($str_Lsql);
                                                                        while($obj = $combo->fetch_object()){
                                                                            echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G6_C39)."</option>";
                                                                        }    
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div>
                                                    </div>
                                                    <div class="row" id="ocultAsociacion">
                                                        <div class="col-md-12">
                                                            <!-- Seccion HORARIO -->
                                                            <div class="panel box box-primary">
                                                                <div class="box-header with-border">
                                                                    <h4 class="box-title">
                                                                        <a data-toggle="collapse" data-parent="#accordion" href="#id_horarios">
                                                                        <?php echo $str_label_horario_EE;?>
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="id_horarios" class="panel-collapse collapse in">
                                                                    <div class="box-body">
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">  
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C162" id="LblG17_C162"><?php echo $str_label_lunes_EE;?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C162" id="G17_C162" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C163" id="LblG17_C163"><?php echo $str_label_hora_inicio_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C163" id="G17_C163" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C163">
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
                                                                                    <label for="G17_C164" id="LblG17_C164"><?php echo $str_label_hora_final_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C164" id="G17_C164" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C164">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                                <!-- /.form group -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C165" id="LblG17_C165"><?php echo $str_label_martes_EE; ?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C165" id="G17_C165" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C166" id="LblG17_C166"><?php echo $str_label_hora_inicio_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C166" id="G17_C166" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C166">
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
                                                                                    <label for="G17_C167" id="LblG17_C167"><?php echo $str_label_hora_final_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C167" id="G17_C167" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C167">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                                <!-- /.form group -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C168" id="LblG17_C168"><?php echo $str_label_miercoles_EE; ?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C168" id="G17_C168" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C169" id="LblG17_C169"><?php echo $str_label_hora_inicio_EE;?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C169" id="G17_C169" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C169">
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
                                                                                    <label for="G17_C170" id="LblG17_C170"><?php echo $str_label_hora_final_EE;?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C170" id="G17_C170" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C170">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                                <!-- /.form group -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C171" id="LblG17_C171"><?php echo $str_label_jueves_EE ;?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C171" id="G17_C171" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C172" id="LblG17_C172"><?php echo $str_label_hora_inicio_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C172" id="G17_C172" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C172">
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
                                                                                    <label for="G17_C173" id="LblG17_C173"><?php echo $str_label_hora_final_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C173" id="G17_C173" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C173">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                                <!-- /.form group -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C174" id="LblG17_C174"><?php echo $str_label_viernes_EE;?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C174" id="G17_C174" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C175" id="LblG17_C175"><?php echo $str_label_hora_inicio_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C175" id="G17_C175" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C175">
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
                                                                                    <label for="G17_C176" id="LblG17_C176"><?php echo $str_label_hora_final_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C176" id="G17_C176" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C176">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                                <!-- /.form group -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C177" id="LblG17_C177"><?php echo $str_label_sabado_EE ;?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C177" id="G17_C177" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C178" id="LblG17_C178"><?php echo $str_label_hora_inicio_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C178" id="G17_C178" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C178">
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
                                                                                    <label for="G17_C179" id="LblG17_C179"><?php echo $str_label_hora_final_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C179" id="G17_C179" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C179">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                                <!-- /.form group -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C180" id="LblG17_C180"><?php echo $str_label_domingo_EE ;?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C180" id="G17_C180" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C181" id="LblG17_C181"><?php echo $str_label_hora_inicio_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C181" id="G17_C181" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C181">
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
                                                                                    <label for="G17_C182" id="LblG17_C182"><?php echo $str_label_hora_final_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C182" id="G17_C182" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C182">
                                                                                            <i class="fa fa-clock-o"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.input group -->
                                                                                </div>
                                                                                <!-- /.form group -->
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C183" id="LblG17_C183"><?php echo $str_label_festivo_EE; ?></label>
                                                                                    <div class="checkbox">
                                                                                        <label>
                                                                                            <input type="checkbox" value="1" name="G17_C183" id="G17_C183" data-error="Before you wreck yourself"  > 
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO SI/NO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIMEPICKER -->
                                                                                <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C184" id="LblG17_C184"><?php echo $str_label_hora_inicio_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C184" id="G17_C184" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C184">
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
                                                                                    <label for="G17_C185" id="LblG17_C185"><?php echo $str_label_hora_final_EE; ?></label>
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control input-sm Hora"  name="G17_C185" id="G17_C185" placeholder="HH:MM:SS" >
                                                                                        <div class="input-group-addon" id="TMP_G17_C185">
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
                                                            <!-- Seccion AVANZADO -->
                                                            <div class="panel box box-primary">
                                                                <div class="box-header with-border">
                                                                    <h4 class="box-title">
                                                                        <a data-toggle="collapse" data-parent="#accordion" href="#id_avanzado">
                                                                            <?php echo $str_label_AVANZADO_EE;?>
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="id_avanzado" class="panel-collapse collapse in">
                                                                    <div class="box-body">
                                                                        <div class="row">
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIPO ENTERO -->
                                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C186" id="LblG17_C186"><?php echo $str_label_registros_agente; ?></label>
                                                                                    <input type="text" class="form-control input-sm Numerico" value=""  name="G17_C186" id="G17_C186" placeholder="<?php echo $str_label_registros_agente; ?>">
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->      
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIPO ENTERO -->
                                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C187" id="LblG17_C187"><?php echo $str_label_nivel_servicio; ?></label>
                                                                                    <input type="text" class="form-control input-sm Numerico" value=""  name="G17_C187" id="G17_C187" placeholder="<?php echo $str_label_nivel_servicio; ?>">
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                                            </div>
                                                                            <div class="col-md-4 col-xs-4">
                                                                                <!-- CAMPO TIPO ENTERO -->
                                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                                <div class="form-group">
                                                                                    <label for="G17_C188" id="LblG17_C188"><?php echo $str_label_tiempo_nivel_servicio ;?></label>
                                                                                    <input type="text" class="form-control input-sm Numerico" value=""  name="G17_C188" id="G17_C188" placeholder="<?php echo $str_label_tiempo_nivel_servicio ;?>">
                                                                                </div>
                                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->
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
                                        
                                        <!-- Seccion AUTORESPUESTA -->
                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#id_autorespuesta">
                                                        <?php echo $str_label_Autorespuesta_EE;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="id_autorespuesta" class="panel-collapse collapse in">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="row">
                                                                <div class="col-md-4 col-xs-4">
                                                                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                    <div class="form-group">
                                                                        <label for="G17_C189" id="LblG17_C189"></label>
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input type="checkbox" value="1" name="G17_C189" id="G17_C189" data-error="Before you wreck yourself"  > 
                                                                                <?php echo $str_label_Autorespuesta;?>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <!-- FIN DEL CAMPO SI/NO -->
                                                                </div>
                                                                <div class="col-md-8 col-xs-8">
                                                                    <!-- CAMPO TIPO TEXTO -->
                                                                    <div class="form-group">
                                                                        <label for="G17_C190" id="LblG17_C190"><?php echo $str_label_asunto_EE;?></label>
                                                                        <input type="text" class="form-control input-sm" id="G17_C190" value=""  name="G17_C190"  placeholder="<?php echo $str_label_asunto_EE;?>">
                                                                    </div>
                                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 col-xs-12">
                                                                    <!-- CAMPO TIPO MEMO -->
                                                                    <div class="form-group">
                                                                        <label for="G17_C191" id="LblG17_C191"><?php echo $str_label_Cuerpo;?></label>
                                                                        <textarea class="form-control input-sm" name="G17_C191" id="G17_C191"  value="" placeholder="<?php echo $str_label_Cuerpo;?>"></textarea>
                                                                    </div>
                                                                    <!-- FIN DEL CAMPO TIPO MEMO -->
                                                                </div>
                                                            </div>  
                                                        </div>
                                                        <div class="col-md-3">
                                                             <div class="table-responsive no-padding" id="diVariables" style="height:400px; overflow-x:hidden; overflow-y:scroll;">
                                                                <table class="table table-hover" id="tablaScrollVariables">
                                                                    <?php
                                                                    echo '<thead>
                                                                            <tr>
                                                                                <th>';
                                                                                    
                                                                                        $Lsql = "SELECT GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$_GET['poblacion'];
                                                                                        $cur_result = $mysqli->query($Lsql);
                                                                                        $res_Lsql = $cur_result->fetch_array();
                                                                    echo "VARIABLES ".$res_Lsql['GUION__Nombre____b'].'
                                                                                    
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>';
                                                                                
                                                                        $Lsql = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE SECCIO_TipoSecc__b = 1 AND PREGUN_ConsInte__GUION__b = ".$_GET['poblacion'];
                                                                        $cur_result = $mysqli->query($Lsql);
                                                                        while ($key = $cur_result->fetch_object()) {
                                                                            $dato = str_replace(' ', '_', utf8_encode($key->PREGUN_Texto_____b));

                                                                            echo '<tr>';
                                                                            echo '  <td>';
                                                                            echo "\${".substr($dato, 0, 20)."}";
                                                                            echo '  </td>';
                                                                            echo '<tr>';
                                                                        }
                                                                                
                                                                    echo '</tbody>';
                                                                    ?>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SI ES MAESTRO - DETALLE CREO LAS TABS --> 
                                        <hr/>
                                        <div class="nav-tabs-custom">

                                            <ul class="nav nav-tabs">

                                                <li class="active">
                                                    <a href="#tab_0" data-toggle="tab" id="tabs_click_0">CORREO_ENTRANTE_CAMPOS</a>
                                                </li>

                                            </ul>


                                            <div class="tab-content">

                                                <div class="tab-pane active" id="tab_0"> 
                                                    <table class="table table-hover table-bordered" id="tablaDatosDetalless0" width="100%">
                                                    </table>
                                                    <div id="pagerDetalles0">
                                                    </div> 
                                                    <button title="Crear CORREO_ENTRANTE_CAMPOS" class="btn btn-primary btn-sm llamadores" padre="'<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' " id="btnLlamar_0"><i class="fa fa-plus"></i></button>
                                                </div>

                                            </div>

                                        </div>

                                        <!-- SECCION : PAGINAS INCLUIDAS -->
                                        <input type="hidden" name="G17_C154" id="G17_C154" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo "0"; }?>">
                                        <input type="hidden" name="G17_C199" id="G17_C199" value="<?php echo $_SESSION['HUESPED'];?>">
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
            </div>
        </div>
<?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>


<script type="text/javascript">
    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
      $(function(){

        $("#usuarios").addClass('active');
        //busqueda_lista_navegacion();
        $(".CargarDatos :first").click();
      
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

            $(".str_Select2").each(function(){
                $(this).val(0).change();
            });

            $("#hidId").val(0);
            $("#h3mio").html('');
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";
            before_add();
           
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
                window.location.href  = "cancelado.php";
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

<script type="text/javascript" src="cruds/DYALOGOCRM_SISTEMA/G17/G17_eventos.js"></script> 
<script type="text/javascript">
    $(function(){
        $("#G17_C186").val(5);
        $("#G17_C187").val(80);
        $("#G17_C188").val(3600);

        <?php if(isset($_GET['id_paso'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
            dataType : 'json',
            success  : function(data){
                //recorrer datos y enviarlos al formulario
                $.each(data, function(i, item) {
                    

                        $("#G17_C153").val(item.G17_C153);

                        $("#G17_C155").val(item.G17_C155);
 
                        $("#G17_C155").val(item.G17_C155).trigger("change"); 

                        $("#G17_C156").val(item.G17_C156);

                        $("#G17_C157").val(item.G17_C157);

                        $("#G17_C158").val(item.G17_C158);

                        $("#G17_C159").val(item.G17_C159);

                        $("#G17_C199").val(item.G17_C199);

                        $("#G17_C161").val(item.G17_C161);
 
                        $("#G17_C161").val(item.G17_C161).trigger("change"); 
    
                        if(item.G17_C162 == '1'){
                           if(!$("#G17_C162").is(':checked')){
                               $("#G17_C162").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C162").is(':checked')){
                               $("#G17_C162").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C163").val(item.G17_C163);

                        $("#G17_C164").val(item.G17_C164);
    
                        if(item.G17_C165 == '1'){
                           if(!$("#G17_C165").is(':checked')){
                               $("#G17_C165").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C165").is(':checked')){
                               $("#G17_C165").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C166").val(item.G17_C166);

                        $("#G17_C167").val(item.G17_C167);
    
                        if(item.G17_C168 == '1'){
                           if(!$("#G17_C168").is(':checked')){
                               $("#G17_C168").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C168").is(':checked')){
                               $("#G17_C168").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C169").val(item.G17_C169);

                        $("#G17_C170").val(item.G17_C170);
    
                        if(item.G17_C171 == '1'){
                           if(!$("#G17_C171").is(':checked')){
                               $("#G17_C171").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C171").is(':checked')){
                               $("#G17_C171").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C172").val(item.G17_C172);

                        $("#G17_C173").val(item.G17_C173);
    
                        if(item.G17_C174 == '1'){
                           if(!$("#G17_C174").is(':checked')){
                               $("#G17_C174").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C174").is(':checked')){
                               $("#G17_C174").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C175").val(item.G17_C175);

                        $("#G17_C176").val(item.G17_C176);
    
                        if(item.G17_C177 == '1'){
                           if(!$("#G17_C177").is(':checked')){
                               $("#G17_C177").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C177").is(':checked')){
                               $("#G17_C177").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C178").val(item.G17_C178);

                        $("#G17_C179").val(item.G17_C179);
    
                        if(item.G17_C180 == '1'){
                           if(!$("#G17_C180").is(':checked')){
                               $("#G17_C180").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C180").is(':checked')){
                               $("#G17_C180").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C181").val(item.G17_C181);

                        $("#G17_C182").val(item.G17_C182);
    
                        if(item.G17_C183 == '1'){
                           if(!$("#G17_C183").is(':checked')){
                               $("#G17_C183").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C183").is(':checked')){
                               $("#G17_C183").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C184").val(item.G17_C184);

                        $("#G17_C185").val(item.G17_C185);

                        $("#G17_C186").val(item.G17_C186);

                        $("#G17_C187").val(item.G17_C187);

                        $("#G17_C188").val(item.G17_C188);
    
                        if(item.G17_C189 == '1'){
                           if(!$("#G17_C189").is(':checked')){
                               $("#G17_C189").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C189").is(':checked')){
                               $("#G17_C189").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C190").val(item.G17_C190);

                        $("#G17_C191").val(item.G17_C191);

                        $("#G17_C196").val(item.G17_C196);
 
                        $("#G17_C196").val(item.G17_C196).trigger("change"); 

                        $("#G17_C197").val(item.G17_C197);
 
                        $("#G17_C197").val(item.G17_C197).trigger("change"); 

                        $("#G17_C198").val(item.G17_C198);
 
                        $("#G17_C198").val(item.G17_C198).trigger("change"); 
                   
                        $("#h3mio").html(item.principal);

                        $("#hidId").val(item.G17_ConsInte__b);

                        $("#btnLlamar_0").attr('padre', item.G17_ConsInte__b);

                        vamosRecargaLasGrillasPorfavor(item.G17_ConsInte__b);                       
                                   
                });
                //Deshabilitar los campos

            } 
        });

            CKEDITOR.replace('G17_C191', {
                language: 'es'
            } );
                        
        <?php } ?>

        //str_Select2 estos son los guiones
        $("#G17_C155").select2({ 
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

        $("#G17_C155").change(function(){
            var valores = $("#G17_C155 option:selected").text();
            var campos = $("#G17_C155 option:selected").attr("dinammicos");
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
                                      

        $("#G17_C161").select2({ 
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

        $("#G17_C161").change(function(){
            var valores = $("#G17_C161 option:selected").text();
            var campos = $("#G17_C161 option:selected").attr("dinammicos");
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

            get_variables_for_poblacion($(this).val());
        });
                                      

        $("#G17_C196").select2({ 
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

        $("#G17_C196").change(function(){
            var valores = $("#G17_C196 option:selected").text();
            var campos = $("#G17_C196 option:selected").attr("dinammicos");
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
                                      

    $("#G17_C197").select2();

        $("#G17_C198").select2({ 
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

        $("#G17_C198").change(function(){
            var valores = $("#G17_C198 option:selected").text();
            var campos = $("#G17_C198 option:selected").attr("dinammicos");
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
        


        //Timepicker
        var options = {  //hh:mm 24 hour format only, defaults to current time
            'timeFormat': 'H:i:s',
            'minTime': '08:00:00',
            'maxTime': '17:00:00',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        }; 
        $("#G17_C163").timepicker(options);

        $("#G17_C164").timepicker(options);

        $("#G17_C166").timepicker(options);
 
        $("#G17_C167").timepicker(options);

        $("#G17_C169").timepicker(options);
 
        $("#G17_C170").timepicker(options);
 
        $("#G17_C172").timepicker(options);

        $("#G17_C173").timepicker(options);

        $("#G17_C175").timepicker(options);

        $("#G17_C176").timepicker(options);
 
        $("#G17_C178").timepicker(options);

        $("#G17_C179").timepicker(options);

        $("#G17_C181").timepicker(options);

        $("#G17_C182").timepicker(options);

        $("#G17_C184").timepicker(options);

        $("#G17_C185").timepicker(options);

        //Validaciones numeros Enteros
        

        $("#G17_C199").numeric();
        
        $("#G17_C186").numeric();
        
        $("#G17_C187").numeric();
        
        $("#G17_C188").numeric();
        

        //Validaciones numeros Decimales
       

        
        
    $("#btnLlamadorAvanzado").click(function(){
        
        $("#resultadosBusqueda").html('');
    });

    $("#btnBuscar").click(function(){
        
        $.ajax({
            url         : 'cruds/DYALOGOCRM_SISTEMA/G17/G17_Funciones_Busqueda_Manual.php?action=GET_DATOS',
            type        : 'post',
            dataType    : 'json',
            data        : {  },
            success     : function(datosq){
                //alert(datosq[0].cantidad_registros);
                if(datosq[0].cantidad_registros > 1){
                    var valores = null;
                    var tabla_a_mostrar = '<div class="box box-default">'+
                    '<div class="box-header">'+
                        '<h3 class="box-title">RESULTADOS DE LA BUSQUEDA</h3>'+
                    '</div>'+
                    '<div class="box-body">'+
                        '<table class="table table-hover table-bordered" style="width:100%;">';
                    tabla_a_mostrar += '<thead>';
                    tabla_a_mostrar += '<tr>';
                    tabla_a_mostrar += '  ';
                    tabla_a_mostrar += '</tr>';
                    tabla_a_mostrar += '</thead><tbody>';
                    tabla_a_mostrar += '<tbody>';
                    $.each(datosq[0].registros, function(i, item) {
                        tabla_a_mostrar += '<tr ConsInte="'+ item.G17_ConsInte__b +'" class="EditRegistro">';
                        tabla_a_mostrar += '';
                        tabla_a_mostrar += '</tr>';
                    });
                    tabla_a_mostrar += '</tbody>';
                    tabla_a_mostrar += '</table></div></div>';
                    
                    $("#resultadosBusqueda").html(tabla_a_mostrar);
                    
                    $(".EditRegistro").dblclick(function(){
                        var id = $(this).attr("ConsInte");
                        swal({
                            html : true,
                            title: "Información - Dyalogo CRM",
                            text: 'Esta seguro de editar este registro?',
                            type: "warning",
                            confirmButtonText: "Editar registro",
                            cancelButtonText : "No Editar registro",
                            showCancelButton : true,
                            closeOnConfirm : true
                        },
                            function(isconfirm){
                                if(isconfirm){
                                    seleccionar_registro_avanzada(id);
                                    
                                    $("#busquedaAvanzada_").modal('hide');
                                    $("#resultadosBusqueda").html('');
                                }else{

                                    swal("Cancelado", "No se editara este registro", "error");
                                }
                                
                            });
                        });
                }else if(datosq[0].cantidad_registros == 1){
                    var id = datosq[0].registros[0].G17_ConsInte__b;

                    seleccionar_registro_avanzada(id);
                    
                    $("#resultadosBusqueda").html('');
                    $("#busquedaAvanzada_").modal('hide');
                }else{
                    //console.log("LLego aqui");
                    swal({
                        html : true,
                        title: "Información - Dyalogo CRM",
                        text: 'No se encontraron datos, desea adicionar un registro?',
                        type: "warning",
                        confirmButtonText: "Adicionar registro",
                        cancelButtonText : "No adicionar registro",
                        showCancelButton : true,
                        closeOnConfirm : true
                    },
                    function(isconfirm){

                        if(isconfirm){
                            $("#add").click();
                            
                            
                            $("#resultadosBusqueda").html('');
                            $("#busquedaAvanzada_").modal('hide');
                           
                        }
                        else
                        {
                            
                            $("#resultadosBusqueda").html('');
                           
                            swal("Cancelado", "No se crearan nuevos registros", "error");
                        }
                        
                    });
                }
            }
        });
    });


        //Buton gurdar
        

        $("#Save").click(function(){
            var bol_respuesta = before_save();
            if(bol_respuesta){
                var form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#FormularioDatos")[0]);
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

                                <?php if(isset($_GET['yourfather'])){ ?>
                                        window.location.href  = "resultadoExitoso.php";
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
                                            
 
                                            $("#G17_C153").val(item.G17_C153);
 
                                            $("#G17_C154").val(item.G17_C154);

                                            $("#G17_C154").val(item.G17_C154).trigger("change"); 
 
                                            $("#G17_C155").val(item.G17_C155);

                                            $("#G17_C155").val(item.G17_C155).trigger("change"); 
 
                                            $("#G17_C156").val(item.G17_C156);
 
                                            $("#G17_C157").val(item.G17_C157);
 
                                            $("#G17_C158").val(item.G17_C158);
 
                                            $("#G17_C159").val(item.G17_C159);
 
                                            $("#G17_C199").val(item.G17_C199);
 
                                            $("#G17_C161").val(item.G17_C161);

                                            $("#G17_C161").val(item.G17_C161).trigger("change"); 
      
                                            if(item.G17_C162 == 1){
                                               $("#G17_C162").attr('checked', true);
                                            } 
 
                                            $("#G17_C163").val(item.G17_C163);
 
                                            $("#G17_C164").val(item.G17_C164);
      
                                            if(item.G17_C165 == 1){
                                               $("#G17_C165").attr('checked', true);
                                            } 
 
                                            $("#G17_C166").val(item.G17_C166);
 
                                            $("#G17_C167").val(item.G17_C167);
      
                                            if(item.G17_C168 == 1){
                                               $("#G17_C168").attr('checked', true);
                                            } 
 
                                            $("#G17_C169").val(item.G17_C169);
 
                                            $("#G17_C170").val(item.G17_C170);
      
                                            if(item.G17_C171 == 1){
                                               $("#G17_C171").attr('checked', true);
                                            } 
 
                                            $("#G17_C172").val(item.G17_C172);
 
                                            $("#G17_C173").val(item.G17_C173);
      
                                            if(item.G17_C174 == 1){
                                               $("#G17_C174").attr('checked', true);
                                            } 
 
                                            $("#G17_C175").val(item.G17_C175);
 
                                            $("#G17_C176").val(item.G17_C176);
      
                                            if(item.G17_C177 == 1){
                                               $("#G17_C177").attr('checked', true);
                                            } 
 
                                            $("#G17_C178").val(item.G17_C178);
 
                                            $("#G17_C179").val(item.G17_C179);
      
                                            if(item.G17_C180 == 1){
                                               $("#G17_C180").attr('checked', true);
                                            } 
 
                                            $("#G17_C181").val(item.G17_C181);
 
                                            $("#G17_C182").val(item.G17_C182);
      
                                            if(item.G17_C183 == 1){
                                               $("#G17_C183").attr('checked', true);
                                            } 
 
                                            $("#G17_C184").val(item.G17_C184);
 
                                            $("#G17_C185").val(item.G17_C185);
 
                                            $("#G17_C186").val(item.G17_C186);
 
                                            $("#G17_C187").val(item.G17_C187);
 
                                            $("#G17_C188").val(item.G17_C188);
      
                                            if(item.G17_C189 == 1){
                                               $("#G17_C189").attr('checked', true);
                                            } 
 
                                            $("#G17_C190").val(item.G17_C190);
 
                                            $("#G17_C191").val(item.G17_C191);
 
                                            $("#G17_C196").val(item.G17_C196);

                                            $("#G17_C196").val(item.G17_C196).trigger("change"); 
 
                                            $("#G17_C197").val(item.G17_C197);
 
                                            $("#G17_C198").val(item.G17_C198);

                                            $("#G17_C198").val(item.G17_C198).trigger("change"); 
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
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('Ocurrio un error relacionado con la red, al momento de guardar, intenta mas tarde');
                    }
                });
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
                        

                    $.jgrid.gridUnload('#tablaDatosDetalless0'); 
                    cargarHijos_0(id);

                        $("#G17_C153").val(item.G17_C153);

                        $("#G17_C154").val(item.G17_C154);
 
                        $("#G17_C154").val(item.G17_C154).trigger("change"); 

                        $("#G17_C155").val(item.G17_C155);
 
                        $("#G17_C155").val(item.G17_C155).trigger("change"); 

                        $("#G17_C156").val(item.G17_C156);

                        $("#G17_C157").val(item.G17_C157);

                        $("#G17_C158").val(item.G17_C158);

                        $("#G17_C159").val(item.G17_C159);

                        $("#G17_C199").val(item.G17_C199);

                        $("#G17_C161").val(item.G17_C161);
 
                        $("#G17_C161").val(item.G17_C161).trigger("change"); 
    
                        if(item.G17_C162 == '1'){
                           if(!$("#G17_C162").is(':checked')){
                               $("#G17_C162").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C162").is(':checked')){
                               $("#G17_C162").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C163").val(item.G17_C163);        

                        $("#G17_C164").val(item.G17_C164);

                        if(item.G17_C165 == '1'){
                           if(!$("#G17_C165").is(':checked')){
                               $("#G17_C165").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C165").is(':checked')){
                               $("#G17_C165").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C166").val(item.G17_C166);

                        $("#G17_C167").val(item.G17_C167);
    
                        if(item.G17_C168 == '1'){
                           if(!$("#G17_C168").is(':checked')){
                               $("#G17_C168").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C168").is(':checked')){
                               $("#G17_C168").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C169").val(item.G17_C169);       

                        $("#G17_C170").val(item.G17_C170);

                        if(item.G17_C171 == '1'){
                           if(!$("#G17_C171").is(':checked')){
                               $("#G17_C171").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C171").is(':checked')){
                               $("#G17_C171").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C172").val(item.G17_C172);

                        $("#G17_C173").val(item.G17_C173);

                        if(item.G17_C174 == '1'){
                           if(!$("#G17_C174").is(':checked')){
                               $("#G17_C174").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C174").is(':checked')){
                               $("#G17_C174").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C175").val(item.G17_C175);

                        $("#G17_C176").val(item.G17_C176);

                        if(item.G17_C177 == '1'){
                           if(!$("#G17_C177").is(':checked')){
                               $("#G17_C177").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C177").is(':checked')){
                               $("#G17_C177").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C178").val(item.G17_C178);

                        $("#G17_C179").val(item.G17_C179);

                        if(item.G17_C180 == '1'){
                           if(!$("#G17_C180").is(':checked')){
                               $("#G17_C180").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C180").is(':checked')){
                               $("#G17_C180").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C181").val(item.G17_C181);        

                        $("#G17_C182").val(item.G17_C182);

                        if(item.G17_C183 == '1'){
                           if(!$("#G17_C183").is(':checked')){
                               $("#G17_C183").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C183").is(':checked')){
                               $("#G17_C183").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C184").val(item.G17_C184);

                        $("#G17_C185").val(item.G17_C185);

                        $("#G17_C186").val(item.G17_C186);

                        $("#G17_C187").val(item.G17_C187);

                        $("#G17_C188").val(item.G17_C188);
    
                        if(item.G17_C189 == '1'){
                           if(!$("#G17_C189").is(':checked')){
                               $("#G17_C189").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C189").is(':checked')){
                               $("#G17_C189").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C190").val(item.G17_C190);

                        $("#G17_C191").val(item.G17_C191);

                        $("#G17_C196").val(item.G17_C196);
 
                        $("#G17_C196").val(item.G17_C196).trigger("change"); 

                        $("#G17_C197").val(item.G17_C197);
 
                        $("#G17_C197").val(item.G17_C197).trigger("change"); 

                        $("#G17_C198").val(item.G17_C198);
 
                        $("#G17_C198").val(item.G17_C198).trigger("change"); 
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
        

        $.jgrid.gridUnload('#tablaDatosDetalless0'); 
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
            colNames:['id','CORREO ENTRANTE','CAMPO','PREFIJO','POSTFIJO', 'padre'],
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
                    name:'G18_C192', 
                    index:'G18_C192', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_Guion_G18_C192=si',
                        dataInit:function(el){
                            $(el).select2({ 
                                templateResult: function(data) {
                                    var r = data.text.split('|');
                                    var row = '<div class="row">';
                                    var totalRows = 12 / r.length;
                                    for(i= 0; i < r.length; i++){
                                        row += '<div class="col-md-'+ Math.round(totalRows) +'">' + r[i] + '</div>';
                                    }
                                    row += '</div>';
                                    var $result = $(row);
                                    return $result;
                                },
                                templateSelection : function(data){
                                    var r = data.text.split('|');
                                    return r[0];
                                }
                            });
                        }
                    }
                }

                ,
                {  
                    name:'G18_C193', 
                    index:'G18_C193', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_Guion_G18_C193=si',
                        dataInit:function(el){
                            $(el).select2({ 
                                templateResult: function(data) {
                                    var r = data.text.split('|');
                                    var row = '<div class="row">';
                                    var totalRows = 12 / r.length;
                                    for(i= 0; i < r.length; i++){
                                        row += '<div class="col-md-'+ Math.round(totalRows) +'">' + r[i] + '</div>';
                                    }
                                    row += '</div>';
                                    var $result = $(row);
                                    return $result;
                                },
                                templateSelection : function(data){
                                    var r = data.text.split('|');
                                    return r[0];
                                }
                            });
                        }
                    }
                }

                ,
                { 
                    name:'G18_C194', 
                    index: 'G18_C194', 
                    width:160, 
                    resizable:false, 
                    sortable:true , 
                    editable: true 
                }

                ,
                { 
                    name:'G18_C195', 
                    index: 'G18_C195', 
                    width:160, 
                    resizable:false, 
                    sortable:true , 
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
            pager: "#pagerDetalles0",
            rowList: [40,80],
            sortable: true,
            sortname: 'G18_C192',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'CORREO_ENTRANTE_CAMPOS',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                            $("#"+ rowid +"_G18_C192").change(function(){
                                var valores =$("#"+ rowid +"_G18_C192 option:selected").text();
                                var campos =  $("#"+ rowid +"_G18_C192 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                            $("#"+ rowid +"_G18_C193").change(function(){
                                var valores =$("#"+ rowid +"_G18_C193 option:selected").text();
                                var campos =  $("#"+ rowid +"_G18_C193 option:selected").attr("dinammicos");
                                var r = valores.split('|');
                                if(r.length > 1){

                                    var c = campos.split('|');
                                    for(i = 1; i < r.length; i++){
                                        if(!$("#"+ rowid +"_"+c[i]).is("select")) {
                                        // the input field is not a select
                                            $("#"+ rowid +"_"+c[i]).val(r[i]);
                                        }else{
                                            var change = r[i].replace(' ', ''); 
                                            $("#"+ rowid +"_"+c[i]).val(change).change();
                                        }
                                    }
                                }
                            });
                }
                lastSels = rowid;
            }
            ,

            ondblClickRow: function(rowId) {
                $("#frameContenedor").attr('src', 'http://<?php echo $_SERVER["HTTP_HOST"];?>/crm_CentroDiesel/new_index.php?formulario=18&view=si&registroId='+ rowId +'&formaDetalle=si&yourfather='+ idTotal +'&pincheCampo=192&formularioPadre=17');
                $("#editarDatos").modal('show');

            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaDatosDetalless0").setGridWidth($(window).width());
        }).trigger('resize');
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
                        

                    $.jgrid.gridUnload('#tablaDatosDetalless0'); 
                    cargarHijos_0(id);

                        $("#G17_C153").val(item.G17_C153);

                        $("#G17_C154").val(item.G17_C154);
 
                        $("#G17_C154").val(item.G17_C154).trigger("change"); 

                        $("#G17_C155").val(item.G17_C155);
 
                        $("#G17_C155").val(item.G17_C155).trigger("change"); 

                        $("#G17_C156").val(item.G17_C156);

                        $("#G17_C157").val(item.G17_C157);

                        $("#G17_C158").val(item.G17_C158);

                        $("#G17_C159").val(item.G17_C159);

                        $("#G17_C199").val(item.G17_C199);

                        $("#G17_C161").val(item.G17_C161);
 
                        $("#G17_C161").val(item.G17_C161).trigger("change"); 
    
                        if(item.G17_C162 == '1'){
                           if(!$("#G17_C162").is(':checked')){
                               $("#G17_C162").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C162").is(':checked')){
                               $("#G17_C162").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C163").val(item.G17_C163);

                        $("#G17_C164").val(item.G17_C164);
    
                        if(item.G17_C165 == '1'){
                           if(!$("#G17_C165").is(':checked')){
                               $("#G17_C165").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C165").is(':checked')){
                               $("#G17_C165").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C166").val(item.G17_C166);

                        $("#G17_C167").val(item.G17_C167);
    
                        if(item.G17_C168 == '1'){
                           if(!$("#G17_C168").is(':checked')){
                               $("#G17_C168").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C168").is(':checked')){
                               $("#G17_C168").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C169").val(item.G17_C169);

                        $("#G17_C170").val(item.G17_C170);
    
                        if(item.G17_C171 == '1'){
                           if(!$("#G17_C171").is(':checked')){
                               $("#G17_C171").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C171").is(':checked')){
                               $("#G17_C171").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C172").val(item.G17_C172);

                        $("#G17_C173").val(item.G17_C173);
    
                        if(item.G17_C174 == '1'){
                           if(!$("#G17_C174").is(':checked')){
                               $("#G17_C174").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C174").is(':checked')){
                               $("#G17_C174").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C175").val(item.G17_C175);

                        $("#G17_C176").val(item.G17_C176);
    
                        if(item.G17_C177 == '1'){
                           if(!$("#G17_C177").is(':checked')){
                               $("#G17_C177").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C177").is(':checked')){
                               $("#G17_C177").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C178").val(item.G17_C178);

                        $("#G17_C179").val(item.G17_C179);
    
                        if(item.G17_C180 == '1'){
                           if(!$("#G17_C180").is(':checked')){
                               $("#G17_C180").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C180").is(':checked')){
                               $("#G17_C180").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C181").val(item.G17_C181);

                        $("#G17_C182").val(item.G17_C182);
    
                        if(item.G17_C183 == '1'){
                           if(!$("#G17_C183").is(':checked')){
                               $("#G17_C183").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C183").is(':checked')){
                               $("#G17_C183").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C184").val(item.G17_C184);

                        $("#G17_C185").val(item.G17_C185);

                        $("#G17_C186").val(item.G17_C186);

                        $("#G17_C187").val(item.G17_C187);

                        $("#G17_C188").val(item.G17_C188);
    
                        if(item.G17_C189 == '1'){
                           if(!$("#G17_C189").is(':checked')){
                               $("#G17_C189").prop('checked', true);  
                            }
                        } else {
                            if($("#G17_C189").is(':checked')){
                               $("#G17_C189").prop('checked', false);  
                            }
                            
                        }

                        $("#G17_C190").val(item.G17_C190);

                        $("#G17_C191").val(item.G17_C191);

                        $("#G17_C196").val(item.G17_C196);
 
                        $("#G17_C196").val(item.G17_C196).trigger("change"); 

                        $("#G17_C197").val(item.G17_C197);
 
                        $("#G17_C197").val(item.G17_C197).trigger("change"); 

                        $("#G17_C198").val(item.G17_C198);
 
                        $("#G17_C198").val(item.G17_C198).trigger("change"); 
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
        $.jgrid.gridUnload('#tablaDatosDetalless0'); 
        cargarHijos_0(id);
    }

    function get_variables_for_poblacion(porblacion){
        $.ajax({
            url      : '<?=$url_crud;?>?cargarTablaVariables=true',
            type     : 'post',
            data     : { poblacion : porblacion },
            success  : function(data){
                $("#tablaScrollVariables").html(data);
            }
        });
    }
</script>

