<?php
    $tamanho = 3;
    $tamanho_U = 9;
    $vistaStrategias = true;
    $vistaAgentes   = true;
    $str_nombreEstrategia = '';
    if(isset($_SESSION['TAMANHO_CAMPAN'])){
        $tamanho = $_SESSION['TAMANHO_CAMPAN'];
        if( $tamanho == 3){
            $tamanho_U = 9;
        }elseif ( $tamanho == 4){
            $tamanho_U = 8;
        }elseif ( $tamanho == 6){
            $tamanho_U = 6;
        }elseif ( $tamanho == 9){
            $tamanho_U = 3;
        }elseif( $tamanho == 0){
            $vistaStrategias = false;
            $tamanho_U = 12;
        }elseif( $tamanho == 12){
            $vistaAgentes = false;
        }
    }

    $orderCampanha = " ESTRAT_ConsInte__b DESC ";
    if(isset($_SESSION['ORDEN_CAMPAN'])){
        if($_SESSION['ORDEN_CAMPAN'] == "A"){
            $orderCampanha = " ESTRAT_Nombre____b ASC ";
        }else{
            $orderCampanha = " TIPO_ESTRAT_Nombre____b ASC ";
        }
    }

    $Lsql = "SELECT ESTRAT_Nombre____b , ESTRAT_Flujograma_b FROM ".$BaseDatos_systema.".ESTRAT WHERE ESTRAT_ConsInte__b = ".$_GET['estrategia'];
    $res = $mysqli->query($Lsql);
    $nombre = "";
    $flujograma = "";
    while ($je = $res->fetch_object()) {
        $nombre = strtoupper(utf8_encode($je->ESTRAT_Nombre____b));
        $flujograma = $je->ESTRAT_Flujograma_b;
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

    .arrastrado{
        width:100%;
        float:left;
        border:1px solid #ddd;
        margin:5px;
        padding:10px;
        list-style-type: none;
    }

    .arrastrado > li{
        margin:5px 0;
        background:#ddd;
        cursor:move;
        padding:5px;
        list-style-type: none;
    }
</style>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_tablero_estrategi.' '.$nombre;?> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li><a href="index.php?page=dashboard"><?php echo $str_title_Dash;?></a></li>
            <li><?php echo $str_tablero_estrategi . ' '. $nombre;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if($vistaStrategias){ ?>
            <div class="col-md-<?php echo $tamanho;?> col-xs-<?php echo $tamanho;?> col-lg-<?php echo $tamanho;?>">
                <a href="#">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $str_Estadistica_Estrat_title;?></h3>
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

                                $campanhasLsql = "SELECT ESTRAT_ConsInte__b, ESTRAT_Nombre____b, a.METMED_Nombre___b as meta1, a.METMED_Valor____b as val1 , b.METMED_Nombre___b as meta2, b.METMED_Valor____b as val2, c.METMED_Nombre___b as meta3, c.METMED_Valor____b as val3 , d.METMED_Nombre___b as meta4, d.METMED_Valor____b as val4 , TIPO_ESTRAT_Nombre____b , ESTRAT_Color____b, ESTRAT_ConsInte__PROYEC_b FROM ".$BaseDatos_systema.".ESTRAT LEFT JOIN ".$BaseDatos_systema.".METMED a ON a.METMED_Consinte__METDEF_b  = ESTRAT_Meta1____b LEFT JOIN ".$BaseDatos_systema.".METMED b ON b.METMED_Consinte__METDEF_b  = ESTRAT_Meta2____b  LEFT JOIN ".$BaseDatos_systema.".METMED c ON c.METMED_Consinte__METDEF_b  = ESTRAT_Meta3____b LEFT JOIN ".$BaseDatos_systema.".METMED d ON d.METMED_Consinte__METDEF_b  = ESTRAT_Meta4____b LEFT JOIN ".$BaseDatos_systema.".TIPO_ESTRAT ON TIPO_ESTRAT_ConsInte__b = ESTRAT_ConsInte__TIPO_ESTRAT_b WHERE ESTRAT_ConsInte__b = ".$_GET['estrategia']."  ORDER BY ".$orderCampanha." LIMIT 10;";

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

                                    $str_nombreEstrategia =  utf8_encode($campanha->ESTRAT_Nombre____b);

                        ?>
                                    <a data-togle="modal" class="llamadorModal" data-target="#editarDatos" href="#" estrategia="<?php echo $campanha->ESTRAT_ConsInte__b;?>" huesped="<?php echo $campanha->ESTRAT_ConsInte__PROYEC_b;?>">
                                        <div class="col-md-12">
                                            <!-- Widget: user widget style 1 -->
                                            <div class="box box-widget widget-user">
                                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                                <div class="widget-user-header" style="background: <?php echo $colorFondo; ?>;color: white;">
                                                    <h4 class="widget-user-desc">
                                                        <?php echo ($campanha->ESTRAT_Nombre____b);?>
                                                        <span style="float: right;font-size: 24px;"><?php echo number_format($campanha->val1 , 2);?></span>
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
                                                                <h5 class="description-header"><?php echo number_format($campanha->val2 , 2);?></h5>
                                                                <span class="description-text"  style="font-size: 10px;"><?php echo $campanha->meta2;?></span>
                                                            </div>
                                                        <!-- /.description-block -->
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-4 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo number_format($campanha->val3 , 2);?></h5>
                                                                <span class="description-text"  style="font-size: 10px;"><?php echo $campanha->meta3;?></span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-4">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo number_format($campanha->val4 , 2);?></h5>
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
                                        </div>
                                    </a>
                        <?php   } 
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if($vistaAgentes){ ?>
            <div class="col-md-<?php echo $tamanho_U;?> col-xs-<?php echo $tamanho_U;?> col-lg-<?php echo $tamanho_U;?>">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $str_Estadistica_users_titl;?></h3>
                        <div class="box-tool pull-right">
                            <span id="pausados" class="badge bg-yellow">Pausados</span>
                            <span id="ocupados" class="badge bg-red">Ocupados</span>
                            <span id="disponib" class="badge bg-green">Disponibles</span>
                            <a href="#" data-toggle="modal" data-target="#configuraciones" title="Configurar"><i class="fa fa-cog"></i></a>
                            <!--<select class="form-control col-md-2" id="cmbEstadosFiltros">
                                <option value="0"><?php echo $str_orden_ordenamiento;?></option>
                                <option value="A"><?php echo $str_orden_alfabetico;?></option>
                                <option value="E"><?php echo $str_orden_estado;?></option>
                            </select>-->
                        </div>
                    </div>
                    <div class="box-body"  id="DivUsuarios">
                        <ul class="users-list clearfix" >

                        </ul>
                    </div>
                    <div class="box-footer" style="text-align: center;">
                          <a href="#" data-toggle="modal" data-target="#AsignarDesasignarAgentes"><?php echo $str_aso_user;?></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $str_strategias_pasos;?></h3>
                        <div class="box-tool pull-right">
                            <a href="#" data-toggle="modal" data-target="#configuraciones" title="Configurar"><i class="fa fa-cog"></i></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <a href="index.php?page=flujograma&estrategia=<?php echo $_GET['estrategia']; ?>&ruta=1">
                            <div id="myDiagramDiv" style="height: 500px; width: 100%;">
                            
                            </div>
                        </a>
<textarea id="saveModel_New" style="display: none;">
{
    "class": "go.GraphLinksModel",
    "linkFromPortIdProperty": "fromPort",
    "linkToPortIdProperty": "toPort",
    "nodeDataArray": [
        <?php
            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$_GET['estrategia'];
            $res_Pasos = $mysqli->query($Lsql);
            $i = 0;
            $separador = '';
            while ($keu = $res_Pasos->fetch_object()) {
                if($i != 0){
                    $separador = ',';
                }
                echo $separador."
        {\"category\":\"".$keu->ESTPAS_Nombre__b."\",  \"tipoPaso\": ".$keu->ESTPAS_Tipo______b.", \"figure\":\"Circle\", \"key\": ".$keu->ESTPAS_ConsInte__b.", \"loc\":\"".$keu->ESTPAS_Loc______b."\"}"."\n";
                $i++;
            }
        ?>
    ],
    "linkDataArray": [
        <?php
            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTRAT_b = ".$_GET['estrategia'];
            $res_Pasos = $mysqli->query($Lsql);
            $i = 0;
            $separador = '';
            while ($keu = $res_Pasos->fetch_object()) {
                if($i != 0){
                    $separador = ',';
                }
                echo $separador."
        {\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"points\":".$keu->ESTCON_Coordenadas_b.", \"text\":\"".$keu->ESTCON_Comentari_b."\"}"."\n";
                $i++;
            }
        ?>
    ]
}
</textarea>
                    </div>
                    <div class="box-footer">
                        <div class="box-footer" style="text-align: center;">
                           <!--<a data-togle="modal" class="llamadorModal" data-target="#editarDatos" href="#" estrategia="<?php //echo $_GET['estrategia'];?>" huesped="<?php //echo $_GET['huesped'];?>"><?php// echo $str_Editar_estrategia ;?></a>-->
                        </div>
                    </div>
                </div>
            </div>
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
                            <option value="3">25 % <?php echo $str_porcentage;?></option>
                            <option value="4">33 % <?php echo $str_porcentage;?></option>
                            <option value="6">50 % <?php echo $str_porcentage;?></option>
                            <option value="9">75 % <?php echo $str_porcentage;?></option>
                            <option value="12">100 % <?php echo $str_porcentage;?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $str_orden_estrategias;?></label>
                        <select name="cmbOrdenCampan" id="cmbOrdenCampan" class="form-control">
                            <option value="0">Seleccione</option>
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
                    <input type="hidden" name="valRuta" value="1">
                    <input type="hidden" name="estrategia" value="<?php echo $_GET['estrategia'];?>">
                    <input type="hidden" name="huesped" value="<?php echo $_GET['huesped'];?>">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="btnGuardarConfiguraciones" type="submit"><?php echo $str_config_aplicar;?></button>
                    <button class="btn btn-danger" id="btnCancelarConfiguraciones" data-dismiss="modal"  type="button"><?php echo $str_cancela;?></button>
                   
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade-in" id="AsignarDesasignarAgentes" data-backdrop="static"  data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat"><?php echo $str_aso_user.' '; ?></h4>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-12">
                        <iframe style="width: 100%; height: 400px;" src="http://<?php echo $Api_Gestion; ?>dyalogocbx/paginas/dd/dd-usu-est.jsf?tip=1&idEstrat=<?php echo $_GET['estrategia'];?>&idHuesped=<?php echo $_SESSION['HUESPED'];?>" marginheight="0" marginwidth="0" noresize  frameborder="0">
                            
                        </iframe>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_strategia_edicion.' '.$str_nombreEstrategia; ?></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src=""  marginheight="0" marginwidth="0" noresize  frameborder="0">

                </iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-in" id="editarDatos2" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_strategia_edicion.' '.$str_nombreEstrategia; ?></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src="mostrar_popups.php?view=flujograma&estrategia=<?php echo $_GET['estrategia']; ?>"  marginheight="0" marginwidth="0" noresize  frameborder="0">

                </iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/plugins/multiselect/js/multiselect.min.js"></script>
<script type="text/javascript">
    var timestamp = null;
    function cargar_push()
    {
        $.ajax({
        async:  true,
        type: "POST",
        url: "pages/Dashboard/real_time_users_B.php",
        data: "&timestamp="+timestamp+"&huesped=<?php echo $_GET['huesped'];?>",
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
                    url     : "pages/Dashboard/contador2.php",
                    data    : { huesped : <?php echo $_GET['huesped'];?> , estrategia : <?php echo $_GET['estrategia'];?> },
                    dataType: "json",
                    success : function(data){
                        $("#pausados").html("Pausados "+ data.pausado);
                        $("#ocupados").html("Ocupados "+ data.ocupados);
                        $("#disponib").html("Disponibles "+ data.disponibles);                         
                    }
                });

                $.ajax({
                    async:  true,
                    type: "POST",
                    url: "pages/Dashboard/dash_users_B.php",
                    data: {
                        order : $("#cmbEstadosFiltros").val() ,
                        idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>',
                        huesped : <?php echo $_GET['huesped'];?>,
                        estrategia : <?php echo $_GET['estrategia'];?>
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
                    }
                });
            }
            setTimeout('cargar_push()',1000);

        }
        });
    }


    function cargar_push_campana()
    {
        $.ajax({
        async:  true,
        type: "POST",
        url: "pages/Dashboard/real_time_campan.php",
        data: "&timestamp="+timestamp+"&estrategia=<?php echo $_GET['estrategia'];?>",
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
                    async:  true,
                    type: "POST",
                    url: "pages/Dashboard/dash_campan.php",
                    data: "&estrategia=<?php echo $_GET['estrategia'];?>",
                    dataType:"html",
                    success: function(data)
                    {
                        $('#campanitas').html(data);
                        $(".llamadorModal").click(function(event){
                            event.preventDefault();
                            var estrategia = $(this).attr('estrategia');
                            var huesped = $(this).attr('huesped');
                            $("#frameContenedor").attr('src', 'mostrar_popups.php?view=estrategias&estrategia='+ estrategia +'&huesped='+huesped);
                            $("#editarDatos").modal();
                        });

                    }
                });
            }
            setTimeout('cargar_push_campana()',1000);

        }
        });
    }



    $(document).ready(function()
    {
        cargar_push();
        //cargar_push_campana();

        $("#refrescarGrillas").click(function(){
           window.location.reload(true);
        });

        $('#multiselect').multiselect();
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
            $("#frameContenedor").attr('src', 'mostrar_popups.php?view=estrategias&estrategia='+ estrategia +'&huesped='+huesped);
            $("#editarDatos").modal();
        });
       /* $("#agentes_disponibles li, #estrategias li" ).draggable({
            appendTo: "body",
            helper: 'clone',
        });

        $( "#agentes_disponibles, #estrategias" ).droppable({
            accept:'li',
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",

            drop: function( event, ui ) {
                ui.draggable.appendTo(this).fadeIn();
            }
        });*/

        $("#btnGuardarAgentes").click(function(){
            var datos = '';
            i = 0;
            //
            $("#multiselect_to option").each(function(){
                var separador = ",";
                if(i === 0){
                    separador = "";
                }
                datos += separador + $(this).attr('value');
                i++;
            });

            //alert(datos);

            $.ajax({
                url     : "pages/Estrategias/guardarEstrategia.php",
                type    : "post",
                data    :   {
                                oclOperacion        : "AGENTS",
                                agentes             : datos,
                                estrategia          : <?php echo $_GET['estrategia'];?>
                            },
                dataType: "json",
                success : function(data){
                    if(data.code == '1'){
                        alertify.success("<?php echo $str_Exito;?>");
                        location.reload();
                    }
                }
            });
        });


        $("#myDiagramDiv").click(function(){
            //$("#editarDatos2").modal();
        });

    });

</script>

<script src="assets/plugins/Flowchart/flowchart.js"></script>
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
                    console.log(obj.je.key);
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

        myDiagram.nodeTemplateMap.add("EnChat",
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
                            text: "\uf0e5",
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

        myDiagram.nodeTemplateMap.add("EnMail",
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
                            text: "\uf003",
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
        //myPalette.doFocus = customFocus;
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
        myDiagram.model = go.Model.fromJson(document.getElementById("saveModel_New").value);
    }

    $(document).ready(function(){
        $("#estrategias").addClass('active');
        init();
        $("#myDiagramDiv").click(function(){
            //$("#editarDatos").modal();
        });
    });
</script>