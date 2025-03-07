<?php
    $tamanho = 6;
    $tamanho_U = 6;
    $tamanho_campans = 12;
    $vistaStrategias = true;
    $vistaAgentes   = true;
    if(isset($_SESSION['TAMANHO_CAMPAN'])){
        $tamanho = $_SESSION['TAMANHO_CAMPAN'];
        if( $tamanho == 5){
            $tamanho_U = 7;
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
.tool {
    opacity: 1 !important;
}

/* Tooltip text */
.tool .tooltiptext {
  position: absolute; 
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
  z-index: 10;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tool:hover .tooltiptext {
  visibility: visible;
}
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
    .tableEstrat {
    border-collapse:collapse;border:none;
    mso-border-alt:solid windowtext .5pt;
    mso-yfti-tbllook:1184;
    mso-padding-alt:0cm 5.4pt 0cm 5.4pt
  }

  .tdNomPaso {
    width:176.9pt;
    border-bottom:none;
    border-right:none;
    background:white;
    mso-background-themecolor:background1;
    padding:0cm 5.4pt 0cm 5.4ptt
  }

  .pNomPaso {
    margin-bottom:0cm;
    margin-bottom:.0001pt;
    line-height:normal
  }

  .sPaso {
    font-size:14.0pt;
  }

  .tdMeta {
    width:63.5pt;
    border-top:none;
    border-right:none;
    background:white;
    mso-background-themecolor:
    background1;
    padding:0cm 5.4pt 0cm 5.4pt
  }

  .pCifra {
    margin-bottom:0cm;
    margin-bottom:.0001pt;
    text-align:center;
    line-height:normal
  }

  .pNomMeta {
    margin-bottom:0cm;
    margin-bottom:.0001pt;
    text-align:center;
    line-height:normal
  }

  .sNomTipo {
    margin-bottom:0cm;
    margin-bottom:.0001pt;
    float:right;
    line-height:normal
    color:#7F7F7F;
  }

  .sNomMeta {
    font-size:9.0pt;
    color:#7F7F7F;
    mso-themecolor:text1;
    mso-themetint:128;
    mso-style-textfill-fill-color:
    #7F7F7F;
    mso-style-textfill-fill-themecolor:text1;
    mso-style-textfill-fill-alpha:
    100.0%;
    mso-style-textfill-fill-colortransforms:lumm=50000 lumo=50000
  }

  .tdCMeta{
    width:2.0cm;
    border:none;
    background:white;
    mso-background-themecolor:
    background1;
    padding:0cm 5.4pt 0cm 5.4pt
  }

  .tdEstrat {
    width:260.25pt;
    border-top:none;
    padding:0cm 5.4pt 0cm 5.4pt
  }

  .pEstrat {
    margin-bottom:0cm;
    margin-bottom:.0001pt;
    line-height:normal
  }

  .sNomEstrat {
    font-size:16.0pt;
    color:white;
  }

  .sMetaPrincipal {
    font-size:26.0pt;
    color:black;
    mso-themecolor:text1
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
<div class="modal fade-in" id="modVisorColaMArcador" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
            </div>
            <div class="modal-body">
                <iframe id="ifrVisorColaMArcador" src="" style="width: 100%; height: 535px;">
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
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if($vistaStrategias){ ?>
            <div class="col-md-<?php echo $tamanho;?> col-lg-<?php echo $tamanho;?> col-xs-12" style="padding: 1mm;">
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
                    <div class="box-body" id="campanitas" style="padding: 1px;">

                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if($vistaAgentes){ ?>
            <div class="col-md-<?php echo $tamanho_U;?> col-lg-<?php echo $tamanho_U;?> col-xs-12" style="padding: 1mm;">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Agentes Conectados</h3>
                        <div class="box-tool pull-right">
                            <span id="sinServicio" class="badge bg-grey">No disponibles</span>
                            <span id="pausados" class="badge bg-yellow"><?php echo $str_pausados_____t; ?></span>
                            <span id="ocupados" class="badge bg-red"><?php echo $str_ocupados_____t; ?></span>
                            <span id="disponib" class="badge bg-green"><?php echo $str_disponibles__t; ?></span>
                            <!--<a href="#" data-toggle="modal" data-target="#configuraciones" title="Configurar"><i class="fa fa-cog"></i></a>
                            <select class="form-control col-md-2" id="cmbEstadosFiltros">
                                <option value="0"><?php echo $str_orden_ordenamiento;?></option>
                                <option value="A"><?php echo $str_orden_alfabetico;?></option>
                                <option value="E"><?php echo $str_orden_estado;?></option>
                            </select>-->
                        </div>
                    </div>
                    <div class="box-body"  id="DivUsuarios">
                       
                    </div>
                    <!--<div class="box-footer" style="text-align: center;">
                        <a href="#" class="usuariosG1" ><?php echo $str_new_user;?></a>
                    </div>-->
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
                            <option value="5">25 % <?php echo $str_porcentage;?></option>
                            <option value="6" selected >50 % <?php echo $str_porcentage;?></option>
                            <option value="9">75 % <?php echo $str_porcentage;?></option>
                            <option value="12">100 % <?php echo $str_porcentage;?></option>
                        </select>
                    </div>
                    <div class="form-group" hidden>
                        <label><?php echo $str_orden_estrategias;?></label>
                        <select name="cmbOrdenCampan" id="cmbOrdenCampan" class="form-control">
                            <option value='0'>Seleccione</option>
                            <option value="AA">Ascendente meta principal</option> 
                            <option value="AD">Descendente meta principal</option>
                        </select>
                    </div>
                    <div hidden class="form-group">
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
       
    // $.ajax({
    //         async:  true,
    //         type: "POST",
    //         url: "pages/Dashboard/dash_users.php?contador=si",
    //         data: {
    //             ord : $("#cmbEstadosFiltros").val() ,
    //             idi : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'
    //         },
    //         dataType:"html",
    //         success: function(data)
    //         {
    //             let obj = JSON.parse(data);
    //             $("#sinServicio").html("No disponibles  "+ obj.nodis);
    //             $("#pausados").html("<?php echo $str_pausados_____t; ?>  "+ obj.pau);
    //             $("#ocupados").html("<?php echo $str_ocupados_____t; ?>  "+ obj.ocu);
    //             $("#disponib").html("<?php echo $str_disponibles__t; ?>  "+ obj.dis);
    //         }
    //     });

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
                calcAllTimesDrawed();

                var agnts = $('#divAge');
                var div = agnts.children('div').get();
                div.sort(function(aa, bb) {
                   var A = $(aa).attr('estado').toUpperCase();
                   var B = $(bb).attr('estado').toUpperCase();
                   return (A < B) ? -1 : (A > B) ? 1 : 0;                   
                });
                $.each(div, function(idx, itm) { agnts.append(itm); });  

                $("#sinServicio").html("No disponibles  "+ $("#nodis").val());
                $("#pausados").html("<?php echo $str_pausados_____t; ?>  "+ $("#pau").val());
                $("#ocupados").html("<?php echo $str_ocupados_____t; ?>  "+ $("#ocu").val());
                $("#disponib").html("<?php echo $str_disponibles__t; ?>  "+ $("#dis").val());
            }
        });
    }

    function cargar_push_campana()
    {   
        $.ajax({
            async:  true,
            type: "POST",
            url: "pages/Dashboard/dash_campan.php",
            data: "",
            dataType:"html",
            success: function(data)
            {
                var obj = $.parseJSON(data);
                $('#campanitas').html(obj[0]);

                var objDiv_t = $('#campanitas');
                var objDivMenor = objDiv_t.children('div').get();
                objDivMenor.sort(function(a, b) {
                   var compA = parseInt($(a).attr('valor').toUpperCase());
                   var compB = parseInt($(b).attr('valor').toUpperCase());
                   switch(obj[1]) {
                      case "AA":
                        return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
                        break;
                      case "AD":
                        return (compA < compB) ? 1 : (compA > compB) ? -1 : 0;
                        break;
                    }
                   
                });
                $.each(objDivMenor, function(idx, itm) { objDiv_t.append(itm); });
                
                $(".visorLlamadas").click(function(){

                    var strb64IdPaso_t = btoa($(this).attr("idpaso")+"|0");

                    $("#ifrVisorColaMArcador").attr("src","ColaMarcador.php?encrypt="+strb64IdPaso_t);

                    $("#modVisorColaMArcador").modal();

                });
            }
        });
    }



    
    /**
     *BGCR - Esta funcion remplaza las colas de la campañas que se encuentren pintadas
    *@param none
    *@return void
    */

    function colasTiempoReal () { 
        $.ajax({
            url: "pages/Dashboard/MetricasTiempoReal/getColas.php",
            type: "GET",
            dataType: 'JSON',
            success: function (data) {
                data.forEach(eCampan => {
                    // Se valida si la campaña esta pintada
                    if($("#sMetaPrincipal_"+eCampan.CAMPAN_ConsInte__b).length){
                        // Por cada cola se valida si el valor es diferente a off
                        eCampan.lstDefinicionColas_t.forEach(cola => {
                            let colaView = $("#sMetaPrincipal_"+eCampan.CAMPAN_ConsInte__b);
                            if(colaView.html() != "off" && cola.typeChannel == "1"){
                                colaView.html(cola.value);
                            }
                        });
                    }
                });
            }
        });
     }


     // Esta funcion calcula la difenrecia de tiempo entra un fecha y ahora
     function calcTime(timeChange) {
        timeChange = moment(timeChange);
        let timeNow = moment();

        // Calcular la diferencia en milisegundos entre las dos fechas
        const diferenciaEnMilisegundos = timeNow.diff(timeChange);

        // Obtener las horas, minutos y segundos de la diferencia en formato HH:mm:ss
        const horas = Math.floor(diferenciaEnMilisegundos / 3600000);
        const minutos = Math.floor((diferenciaEnMilisegundos % 3600000) / 60000);
        const segundos = Math.floor(((diferenciaEnMilisegundos % 3600000) % 60000) / 1000);

        // Imprimir la diferencia en formato HH:mm:ss
        return`${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;

      }

    function calcAllTimesDrawed() {
        $('input[id^="reloj_ChangeDate"]').each((i,elementTime) => {
            elementTime = $(elementTime);
            let timeChanged = calcTime(elementTime.val());
            let idRelojNum = elementTime.attr("id").split("reloj_ChangeDate")[1];
            let idReloj = "relojJose_"+idRelojNum;
            let idRelojTD = "relojTDJose_"+idRelojNum;
            $("#"+idReloj).html(timeChanged);
            $("#"+idRelojTD).html(timeChanged);
        });
    }


    $(document).ready(function()
    {   
        cargar_push();
        var controlsession = setInterval(cargar_push, 3000);
        var controlTiempos = setInterval(calcAllTimesDrawed, 1000);
        cargar_push_campana();
        var controlsession2 = setInterval(() => {
            let minute = new Date().getMinutes();
            let seconds = new Date().getSeconds();
            // Se consultan las metricas despues de 30 segundos de que la hora sea un multiplo de 5 minutos
            if(minute%5 == 0 && seconds/30 == 1){
                cargar_push_campana();
            }
        }, 1000);
        colasTiempoReal();
        var controlColas = setInterval(colasTiempoReal, 5000);


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
