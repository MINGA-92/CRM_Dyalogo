<?php
    $tamanho = 3;
    $tamanho_U = 9;
    $tamanho_campans = 12;
    $vistaStrategias = true;
    $vistaAgentes   = true;
    if(isset($_SESSION['TAMANHO_CAMPAN'])){
        $tamanho = $_SESSION['TAMANHO_CAMPAN'];
        if( $tamanho == 3){
            $tamanho_U = 9;
            $tamanho_campans = 12;
        }elseif ( $tamanho == 4){
            $tamanho_U = 8;
            $tamanho_campans = 12;
        }elseif ( $tamanho == 6){
            $tamanho_U = 6;
            $tamanho_campans = 6;
        }elseif ( $tamanho == 9){
            $tamanho_U = 3;
            $tamanho_campans = 4;
        }elseif( $tamanho == 0){
            $vistaStrategias = false;
            $tamanho_U = 12;

        }elseif( $tamanho == 12){
            $tamanho_campans = 3;
            $vistaAgentes = false;
        }
    }

    $orderCampanha = " ESTRAT_Nombre____b ASC ";
    if(isset($_SESSION['ORDEN_CAMPAN'])){
        if($_SESSION['ORDEN_CAMPAN'] == "AA"){
            $orderCampanha = " ESTRAT_Nombre____b ASC ";
        }elseif($_SESSION['ORDEN_CAMPAN'] == "EA"){
            $orderCampanha = " TIPO_ESTRAT_Nombre____b ASC ";
        }elseif($_SESSION['ORDEN_CAMPAN'] == "AD"){
            $orderCampanha = " TIPO_ESTRAT_Nombre____b DESC ";
        }elseif($_SESSION['ORDEN_CAMPAN'] == "ED"){
            $orderCampanha = " TIPO_ESTRAT_Nombre____b DESC ";
        }
    }
?>
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
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src=""  marginheight="0" marginwidth="0" noresize  frameborder="0" onload="autofitIframe(this);">

                </iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-in" id="CrearUsuario" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_CrearUsuario;?></h4>
            </div>
            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label><?php echo $str_code;?></label>
                                        <input type="text" class="form-control" name="CodigoUsuario" id="CodigoUsuario" placeholder="<?php echo $str_code;?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label><?php echo $str_nombre_usuario;?></label>
                                        <input type="text" class="form-control" name="NombreUsuario" id="NombreUsuario" placeholder="Nombre del usuario">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label><?php echo $str_identificacion;?></label>
                                        <input type="text" class="form-control" name="IdentificacionUsuario" id="IdentificacionUsuario" placeholder="<?php echo $str_nombre_usuario;?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label><?php echo $str_correo;?></label>
                                        <input type="email" class="form-control" placeholder="<?php echo $str_correo;?>" name="Correo" id="Correo">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label><?php echo $str_cargo;?></label>
                                        <input type="text" class="form-control" placeholder="<?php echo $str_cargo;?>" name="Cargo" id="Cargo">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label><?php echo $str_passwo_usuario;?></label>
                                        <input type="password" class="form-control" name="txtPassword" id="txtPassword" placeholder="Contraseña"> 
                                        <input type="hidden" name="passwordActual" id="passwordActual"> 
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox"  name="backofice" id="backofice"><?php echo $str_backofice;?>
                                        </label>
                                        
                                    </div>
                                </div>
                            </div>
                            <hr/>
                        </div>
                        <div class="col-md-3">
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <img id="avatar3" class="profile-user-img img-responsive img-circle" src="assets/img/Kakashi.fw.png" alt="User profile picture">

                                    <h3 class="profile-username text-center" id="NombreUsers"></h3>

                                    <p class="text-muted text-center" id="CargoUsers"></p>
                                    <input type="file" name="inpFotoPerfil" id="inpFotoPerfil" class="form-control">
                                    <input type="hidden" name="hidOculto" id="hidOculto" value="0">
                                </div>
                            <!-- /.box-body -->
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="hidId" value='0'>
                    <input type="hidden" name="oper" id="oper" value='add'>
                    <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                    <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                    <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $str_cancela;?></button>
                    <button type="button" id="Save" class="btn btn-primary"><?php echo $str_guardar;?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_Dash;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li><a href="index.php?page=dashboard"><?php echo $str_dashboard;?></a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if($vistaStrategias){ ?>
            <div class="col-md-<?php echo $tamanho;?> col-lg-<?php echo $tamanho;?> col-xs-12">
                <a href="#">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $str_Estadistica_campanha_title;?></h3>
                        <div class="box-tool pull-right">
                            <a href="#" data-toggle="modal" data-target="#configuraciones" title="Configurar">
                                <i class="fa fa-cog"></i>
                            </a>
                        </div>
                    </div>
                    <div class="box-body" id="campanitas">
                       <?php
                            if(isset($_SESSION['HUESPED'])){
                                date_default_timezone_set('America/Bogota');

                                $campanhasLsql = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b, a.METMED_Nombre___b as meta1, a.METMED_Valor____b as val1 , b.METMED_Nombre___b as meta2, b.METMED_Valor____b as val2, c.METMED_Nombre___b as meta3, c.METMED_Valor____b as val3 , d.METMED_Nombre___b as meta4, d.METMED_Valor____b as val4 , TIPO_ESTRAT_Nombre____b , ESTRAT_Color____b, ESTRAT_ConsInte__PROYEC_b FROM ".$BaseDatos_systema.".ESTRAT LEFT JOIN ".$BaseDatos_systema.".METMED a ON a.METMED_Consinte__METDEF_b  = ESTRAT_Meta1____b LEFT JOIN ".$BaseDatos_systema.".METMED b ON b.METMED_Consinte__METDEF_b  = ESTRAT_Meta2____b  LEFT JOIN ".$BaseDatos_systema.".METMED c ON c.METMED_Consinte__METDEF_b  = ESTRAT_Meta3____b LEFT JOIN ".$BaseDatos_systema.".METMED d ON d.METMED_Consinte__METDEF_b  = ESTRAT_Meta4____b LEFT JOIN ".$BaseDatos_systema.".TIPO_ESTRAT ON TIPO_ESTRAT_ConsInte__b = ESTRAT_ConsInte__TIPO_ESTRAT_b WHERE ESTRAT_ConsInte__PROYEC_b = ".$_SESSION['HUESPED']."  ORDER BY ".$orderCampanha." LIMIT 10;";

                                $campanhasResu = $mysqli->query($campanhasLsql);
                                $colorFondo = '#00a7d0';
                                while ($campanha = $campanhasResu->fetch_object()) {
                                    if(!is_null($campanha->ESTRAT_Color____b)){
                                        $colorFondo = $campanha->ESTRAT_Color____b;    
                                    }
                                    
                                    $imagenUser = "assets/img/user2-160x160.jpg";
                                    if(file_exists("../CampanhasImagenes/".$campanha->ESTRAT_ConsInte__b.".jpg")){
                                        $imagenUser = "pages/CampanhasImagenes/".$campanha->ESTRAT_ConsInte__b.".jpg";
                                    }
                        ?>
                                    
                                    <div class="col-md-<?php echo $tamanho_campans;?> col-xs-12">
                                        <a href="index.php?page=dashEstrat&estrategia=<?php echo $campanha->ESTRAT_ConsInte__b;?>&huesped=<?php echo $campanha->ESTRAT_ConsInte__PROYEC_b;?>">
                                            <!-- Widget: user widget style 1 -->
                                            <div class="box box-widget widget-user">
                                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                                <div class="widget-user-header" style="background: <?php echo $colorFondo; ?>;color: white;">
                                                    <h4 class="widget-user-desc">
                                                        <?php echo ($campanha->ESTRAT_Nombre____b);?>
                                                        <span style="float: right;font-size: 24px;">
                                                            <?php 
                                                                echo $campanha->val1;    
                                                            ?>
                                                        </span>
                                                    </h4>
                                                    <h5 class="widget-user-desc">
                                                        <?php echo ($campanha->TIPO_ESTRAT_Nombre____b);?>
                                                        <span style="float: right;"><?php echo $campanha->meta1;?></span>
                                                    </h5>
                                                </div>
                                                <div class="widget-user-image">
                                                    <img class="img-circle" src="<?php echo $imagenUser;?>" alt="User Avatar">
                                                </div>
                                                <div class="box-footer">
                                                    <div class="row">
                                                        <div class="col-sm-4 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $campanha->val2;?></h5>
                                                                <span class="description-text"  style="font-size: 10px;"><?php echo $campanha->meta2;?></span>
                                                            </div>
                                                        <!-- /.description-block -->
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-4 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $campanha->val3;?></h5>
                                                                <span class="description-text"  style="font-size: 10px;"><?php echo $campanha->meta3;?></span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-4">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $campanha->val4;?></h5>
                                                                <span class="description-text"  style="font-size: 10px;"><?php echo $campanha->meta4;?></span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->
                                                </div>
                                            </div>
                                            <!-- /.widget-user -->
                                        </a>
                                    </div>
                        <?php   } 
                            }
                        ?>
                    </div>
                    <div class="box-footer" style="text-align: center;">
                        <a href="#" class="llamadorModal2"><?php echo $str_new_campain;?></a>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if($vistaAgentes){ ?>
            <div class="col-md-<?php echo $tamanho_U;?> col-lg-<?php echo $tamanho_U;?> col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $str_Estadistica_users_title;?></h3>
                        <div class="box-tool pull-right">
                            <span id="pausados" class="badge bg-yellow"><?php echo $str_pausados_____t; ?></span>
                            <span id="ocupados" class="badge bg-red"><?php echo $str_ocupados_____t; ?></span>
                            <span id="disponib" class="badge bg-green"><?php echo $str_disponibles__t; ?></span>
                            <a href="#" data-toggle="modal" data-target="#configuraciones" title="Configurar"><i class="fa fa-cog"></i></a>
                            <!--<select class="form-control col-md-2" id="cmbEstadosFiltros">
                                <option value="0"><?php echo $str_orden_ordenamiento;?></option>
                                <option value="A"><?php echo $str_orden_alfabetico;?></option>
                                <option value="E"><?php echo $str_orden_estado;?></option>
                            </select>-->
                        </div>
                    </div>
                    <div class="box-body"  id="DivUsuarios">
                       
                    </div>
                    <div class="box-footer" style="text-align: center;">
                          <a href="#" class="usuariosG1" ><?php echo $str_new_user;?></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
</div>

<div class="modal fade-in" id="configuraciones" data-backdrop="static"  data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="envioDatos" method="post" action="pages/Dashboard/configuraciones.php">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $str_configuracion;?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo $str_tamanho_strat;?></label>
                        <select name="cmbTamanhoEstrat" id="cmbTamanhoEstrat" class="form-control">
                            <option value="0">0 % <?php echo $str_porcentage;?></option>
                            <option value="3" selected="true">25 % <?php echo $str_porcentage;?></option>
                            <option value="4">33 % <?php echo $str_porcentage;?></option>
                            <option value="6">50 % <?php echo $str_porcentage;?></option>
                            <option value="9">75 % <?php echo $str_porcentage;?></option>
                            <option value="12">100 % <?php echo $str_porcentage;?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $str_orden_estrategias;?></label>
                        <select name="cmbOrdenCampan" id="cmbOrdenCampan" class="form-control">
                            <option value='0'>Seleccione</option>
                            <option value="AA"><?php echo $str_orden_alfabetico_a;?></option>
                            <option value="EA"><?php echo $str_tipe_estrategia;?></option>  
                            <option value="AD"><?php echo $str_orden_alfabetico;?></option>
                            <option value="ED"><?php echo $str_tipe_estrategia_a;?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $str_orden_ordenamiento;?></label>
                        <select name="cmbOrdenAgents" id="cmbOrdenAgents" class="form-control"> 
                            <option value="0">Seleccione</option>
                            <option value="AA"><?php echo $str_orden_alfabetico_a;?></option>
                            <option value="EA"><?php echo $str_orden_estado_a;?></option> 
                            <option value="AD"><?php echo $str_orden_alfabetico;?></option>
                            <option value="ED"><?php echo $str_orden_estado;?></option> 
                        </select>
                    </div>    
                    <input type="hidden" name="valRuta" value="0">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="btnGuardarConfiguraciones" type="submit"><?php echo $str_config_aplicar;?></button>
                    <button class="btn btn-danger" id="btnCancelarConfiguraciones" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade-in" id="crearCampanhasNueva"  aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
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
                        <input type="hidden" name="G2_C5" id="G2_C5_modal" value="<?php if(isset($_SESSION['HUESPED'])){ echo $_SESSION['HUESPED']; }?>">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2_C6" id="LblG2_C6">TIPO ESTRATEGIA</label>
                                <select class="form-control "  name="G2_C6" id="G2_C6_modal">
                                    <option value="0">Seleccione</option>
                                <?php

                                    $tipoStratLsql = "SELECT * FROM ".$BaseDatos_systema.".TIPO_ESTRAT";
                                    $tipoStratResu = $mysqli->query($tipoStratLsql);

                                    while ($tipoStrat = $tipoStratResu->fetch_object()) {
                                        echo "<option value='".$tipoStrat->TIPO_ESTRAT_ConsInte__b."'>".utf8_encode($tipoStrat->TIPO_ESTRAT_Nombre____b)."</option>";
                                    }

                                ?>
                                </select>
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                        </div>
                    </div>
                </form>
                <form id="formuarioCargarEsto">
                    <div class="row">
                        <input type="hidden"  class="form-control input-sm Numerico" value="<?php if(isset($_SESSION['HUESPED'])){ echo $_SESSION['HUESPED']; }?>"  name="G10_C70" id="G10_C70" placeholder="HUESPED">
                        <input type="hidden"  class="form-control input-sm Numerico" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo 0; } ;?>"  name="id_paso" id="id_estpas_mio" placeholder="HUESPED">
                        <input type="hidden" name="G10_C72" value="-1">
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <?php 
                                if(isset($_SESSION['HUESPED'])){
                                    $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 1 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
                                }else{
                                    $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 1  ORDER BY G5_C28 ASC";
                                }
                                
                            ?>
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="G10_C73" id="LblG10_C73">SCRIPT</label>
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
                        <div class="col-md-6 col-xs-6">
                            <?php 
                                if(isset($_SESSION['HUESPED'])){
                                    $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 2 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
                                }else{
                                    $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 2  ORDER BY G5_C28 ASC";
                                }
                            ?>
                            <!-- CAMPO DE TIPO GUION -->
                            <div class="form-group">
                                <label for="G10_C74" id="LblG10_C74">BASE DE DATOS</label>
                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C74" id="G10_C74">
                                    <option value="0">NOMBRE</option>
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

<script type="text/javascript">
    var timestamp = null;
    var timestamp2 = null;
    function cargar_push()
    {
        $.ajax({
            async:  true,
            type: "POST",
            url: "pages/Dashboard/dash_users.php",
            data: {
                order : $("#cmbEstadosFiltros").val() ,
                idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
            },
            dataType:"html",
            success: function(data)
            {
                $('#DivUsuarios').html(data);
                $('[data-toggle="popover"]').popover({
                    html : true,
                    placement: 'left'
                });
                setTimeout('cargar_push()',1000);
            }
        });

       /* $.ajax({
            async:  true,
            type: "POST",
            url: "pages/Dashboard/real_time_users_B.php",
            data: "&timestamp="+timestamp,
            dataType:"html",
            success: function(data)
            {
                var json           = eval("(" + data + ")");
                timestamp          = json.timestamp;

                if(timestamp == null)
                {

                }
                else
                {
                    $.ajax({
                        async   :  true,
                        type    : "POST",
                        url     : "pages/Dashboard/contador.php",
                        dataType: "json",
                        success : function(data){
                            $("#pausados").html("<?php// echo $str_pausados_____t; ?>  "+ data.pausado);
                            $("#ocupados").html("<?php// /echo $str_ocupados_____t; ?>  "+ data.ocupados);
                            $("#disponib").html("<?php// echo $str_disponibles__t; ?>  "+ data.disponibles);                          
                        }
                    });

                    $.ajax({
                        async:  true,
                        type: "POST",
                        url: "pages/Dashboard/dash_users.php",
                        data: {
                            order : $("#cmbEstadosFiltros").val() ,
                            idioma : '<?php// echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                        },
                        dataType:"html",
                        success: function(data)
                        {
                            $('#DivUsuarios').html(data);
                            $('[data-toggle="popover"]').popover({
                                html : true,
                                placement: 'left'
                            });
                        }
                    });
                }
                setTimeout('cargar_push()',1000);

            },
            error : function(data){
                timestamp = null;
                cargar_push();
            }
        });*/
    }


    function cargar_push_campana()
    {
        $.ajax({
            async:  true,
            type: "POST",
            url: "pages/Dashboard/real_time_campan.php",
            data: "&timestamp="+timestamp2,
            dataType:"html",
            success: function(data)
            {
                var json           = eval("(" + data + ")");
                timestamp2          = json.timestamp;

                if(timestamp2 == null)
                {

                }
                else
                {
                    $.ajax({
                        async:  true,
                        type: "POST",
                        url: "pages/Dashboard/dash_campan.php",
                        data: "",
                        dataType:"html",
                        success: function(data)
                        {
                            $('#campanitas').html(data);
                        }
                    });
                }
                setTimeout('cargar_push_campana()',1000);

            },
            error : function(data){
                timestamp2 = null;
                cargar_push_campana();
            }
        });
    }



    $(document).ready(function()
    {
         cargar_push();
        //cargar_push_campana();

        $("#G10_C73").select2({
            dropdownParent: $("#crearCampanhasNueva")
        });
        $("#G10_C74").select2({
            dropdownParent: $("#crearCampanhasNueva")
        });

        $("#dashboard").addClass('active');
        $("#cmbEstadosFiltros").change(function(){
            var timestamp = null;
            $.ajax({
                async:  true,
                type: "POST",
                url: "pages/Dashboard/dash_users.php",
                data: {
                    order : $("#cmbEstadosFiltros").val() ,
                    idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
                },
                dataType:"html",
                success: function(data)
                {
                    $('#DivUsuarios').html(data);
                    $('[data-toggle="popover"]').popover({
                        html : true,
                        placement: 'left'
                    });

                    $(".imagenuUsuarios").click(function(){
                        /*$("#frameContenedor").attr('src', 'http://192.168.2.124/Manager/index.php?page=usuariosG1&view=si&registroId='+ $(this).attr('idUsuario') +'&formaDetalle=si&pincheCampo=121&formularioPadre=1');
                        $("#editarDatos").modal('show');*/
                    });

                    cargar_push();
                }

            });

        });

        <?php if(isset($_SESSION['TAMANHO_CAMPAN'])){ ?>
            $('#cmbTamanhoEstrat').val('<?php echo $_SESSION['TAMANHO_CAMPAN'];?>').change();
            $('#cmbOrdenAgents').val('<?php echo $_SESSION['ORDEN_USERS'];?>').change();
            $('#cmbOrdenCampan').val('<?php echo $_SESSION['ORDEN_CAMPAN'];?>').change();
        <?php } ?>

        $(".llamadorModal").click(function(event){
            event.preventDefault();
            var estrategia = $(this).attr('estrategia');
            var huesped = $(this).attr('huesped');
            /*$("#frameContenedor").attr('src', 'mostrar_popups.php?view=estrategias&estrategia='+ estrategia +'&huesped='+huesped);*/
            $("#editarDatos").modal();
        });


        $(".llamadorModal2").click(function(event){
            event.preventDefault();
            /*$.ajax({
                url    : 'cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',
                type   : 'post',
                data   : { crearRegistroNuevo : 'si' },
                success : function(data){
                    $("#frameContenedor").attr('src', 'mostrar_popups.php?view=estrategias&estrategia='+data);
                    $("#editarDatos").modal();
                }
            })
            $("#frameContenedor").attr('src', 'mostrar_popups.php?view=estrategias');*/
            $("#crearCampanhasNueva").modal();
        });

        $(".usuariosG1").click(function(event){
            event.preventDefault(); 
            $("#CrearUsuario").modal();
        });

        $("#Save").click(function(){
            var form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
            var formData = new FormData($("#FormularioDatos")[0]);
            $.ajax({
               url: 'cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php?guardarDatos=1',  
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType : 'json',
                //una vez finalizado correctamente
                success: function(data){
                    if(data.code == '1'){
                        //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                        if($("#oper").val() == 'add'){
                            idTotal = data.id;
                        }else{
                            idTotal= $("#hidId").val();
                        }
                        //Limpiar formulario
                        form[0].reset();
                        alertify.success(data.message);
                    }else{
                        //Algo paso, hay un error
                        alertify.error(data.message);
                    }                
                },
                //si ha ocurrido un error
                error: function(){
                    alertify.error('<?php echo $error_de_red;?>');
                }
            });
        });
    });

</script>
<!-- Esto es para gusrdar la estrategia y el paso -->
<script type="text/javascript">
    $(function(){

        $("#G2_C6_modal").change(function(){
            if($(this).val() == 3){
                $("#G10_C74").show();
                $("#G10_C73").hide();
            }else{
                $("#G10_C74").show();
                $("#G10_C73").show();
            }
        });

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

            if($("#G2_C6_modal").val() == 0){
                alertify.error("Es necesario elegir el tipo de estrategia");
                valido = 1;
            }else{

                if($("#G2_C6_modal").val() == 3){

                }else{
                    if($("#G10_C74").val() == 0){
                        alertify.error("Es necesario elegir la Base de datos");
                        valido = 1;
                    }

                    if($("#G10_C73").val() == 0){
                        alertify.error("Es necesario elegir el Script");
                        valido = 1;
                    }
                }
            }

            if(valido == 0){
                var dtao = new FormData($("#formuarioCargarEstoEstrart")[0]);
                dtao.append('oper', 'add');
                $.ajax({
                    url: 'cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: dtao,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data){
                            var idEstrategia = data;
                            $("#G10_C71").val($("#G2_C7_modal").val());
                            if($("#G2_C6_modal").val() == '3'){
                                window.location.href = 'index.php?page=flujograma&estrategia='+idEstrategia;
                            }else{
                                /* ahora metemos el paso y convocamos el modal del otro */
                                if($("#G2_C6_modal").val() == '1'){
                                    var mySavedModel = '{"class":"go.GraphLinksModel", "linkFromPortIdProperty":"fromPort", "linkToPortIdProperty":"toPort", "nodeDataArray":[{"category":"salPhone","text":"Llamadas salientes Simples","tipoPaso":6, "figure":"Circle","key":-6,"loc":"37.25+-22.600006103515625"}], "linkDataArray":[]}';
                                    $.ajax({
                                        url: 'cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',  
                                        type: 'POST',
                                        data: { mySavedModel : mySavedModel , id_estrategia : idEstrategia , guardar_flugrama_simple : 'SI'},
                                        //una vez finalizado correctamente
                                        success: function(datas){
                                            if(datas){
                                                $("#id_estpas_mio").val(datas);

                                                /* ahora toca meter la campaña de una */
                                                var formData = new FormData($("#formuarioCargarEsto")[0]);
                                                formData.append('oper', 'add');
                                                $.ajax({
                                                   url: 'cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD.php?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                                                    type: 'POST',
                                                    data: formData,
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    //una vez finalizado correctamente
                                                    success: function(datass){
                                                        if(datass){              
                                                           window.location.href = 'index.php?page=campan&view=true&id_paso='+$("#id_estpas_mio").val()+'&poblacion='+$("#G10_C74").val();
                                                        }else{
                                                            $.unblockUI();
                                                            alertify.error('<?php echo $error_de_proceso; ?>');
                                                        }
                                                    }
                                                });

                                            }else{
                                            //Algo paso, hay un error
                                                $.unblockUI();
                                                alertify.error('<?php echo $error_de_proceso; ?>');
                                            }                
                                        },
                                        //si ha ocurrido un error
                                        error: function(){
                                            after_save_error();
                                            alertify.error('<?php echo $error_de_red; ?>');
                                        },
                                        complete : function(){
                                            
                                        }
                                    }); 
                                }else if($("#G2_C6_modal").val() == '2'){

                                } 
                            }
                            
                        }
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait;?>' });
                    }
                }); 
            }
            
        });
    });
</script>
