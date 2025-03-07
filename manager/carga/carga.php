<?php
   
    
    $url_crud = base_url."carga/carga_CRUD.php";
    $destinos = array();
    $validaciones_array = array();
    $strIdCampan=0;
   

    if(isset($_GET['muestra'])){
        if(isset($_GET['distribucion']) && $_GET['distribucion'] == 0){
            $Lsql = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_ConfDinam_b = ".$_GET['distribucion']." WHERE CAMPAN_ConsInte__MUESTR_b = ".$_GET['muestra']." AND CAMPAN_ConsInte__GUION__Pob_b = ".$_GET['poblacion'].";";
            $res = $mysqli->query($Lsql);
        }
    }
    //obtenemos el id campan
    $strIdCampan=0;
    if( isset($_GET['poblacion']) ){
        
        $query1 = "SELECT  CAMPAN_ConsInte__b FROM  ".$BaseDatos_systema.".CAMPAN 
                    WHERE CAMPAN_ConsInte__GUION__Pob_b =".$_GET['poblacion'];

        if( ($results1 = $mysqli->query($query1)) == TRUE ){
            while($key1 = $results1->fetch_object()){

                $query2="SELECT td.patron FROM dyalogo_telefonia.pasos_troncales as p
                join dyalogo_telefonia.tipos_destino as td on p.id_tipos_destino = td.id
                where  id_campana =".$key1->CAMPAN_ConsInte__b;

                if( ($results2 = $mysqli->query($query2)) == TRUE ){

                    if($results2->num_rows > 0 ){
                        $strIdCampan = $key1->CAMPAN_ConsInte__b;
                    }
                }


            }
        }
    }
    
?>
<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_cargue_datos ;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $home;?></a></li>
            <li> <?php echo $str_carga;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
<?php } ?>
       <style>
           .contienebarra{
                position: fixed!important;
                width: 90%;
                margin-left:auto;
                margin-right:auto;
                left:0;
                right:0;
                z-index: 99 !important;
                background-color: #D0CCCC;
                border-radius: 10px;
                padding-bottom: 20px;
                visibility:hidden;
           }
           
           #cierraimportacion{
               visibility:hidden;
           }
           
           .estado1,
           .estado2,
           .estado3{
               color:orangered;
           }
           
           .animacion{
               transition: all 1s ease .2;
           }
           
           
        
        </style>      
        <div class="container contienebarra">
            <center>
                <h2 id="titulo_cargue">CARGANDO REGISTROS</h2>
                <p id="mensaje_cargue">Por favor espere hasta que termine el cargue.</p>
            </center>
            <div class="form-group">
                <strong>Importando Datos</strong>
                <div class="row">
                    <div class="col-md-11">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active animacion progress-bar1" role="progressbar" aria-valuenow="" aria-valuemin="70" aria-valuemax="100">0%
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div>
                            <strong class="estado1">Pendiente</strong>
                        </div>
                    </div>
                </div>                              
            </div>
            <div class="form-group">
                <strong>Validando Datos</strong>
                <div class="row">
                    <div class="col-md-11">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active animacion progress-bar2" role="progressbar" aria-valuenow="" aria-valuemin="70" aria-valuemax="100">0%
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div>
                            <strong class="estado2">Pendiente</strong>
                        </div>
                    </div>
                </div>                              
            </div>
            <div class="form-group">
                <strong>Insertando y/o Actualizando Registros</strong>
                <div class="row">
                    <div class="col-md-11">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active animacion progress-bar3" role="progressbar" aria-valuenow="" aria-valuemin="70" aria-valuemax="100">0%
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div>
                            <strong class="estado3">Pendiente</strong>
                        </div>
                    </div>
                </div>                              
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div></div>
                </div>
                <div class="col-md-2">
                    <button type="button" name="cierraimportacion" id="cierraimportacion" class="btn btn-primary btn-block" >Finalizar</button>
                </div>
            </div>                                                     
        </div>                    
        <div class="box modalcargue">
            <div class="box-body">
                <form id="formEnvioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                    <?php if (!isset($_GET['formaInvoaca'])){ ?>
                    <div class="row">
                    <?php }else{ ?>
                    <div class="row" style="display: none;">
                    <?php } ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_based_label;?></label>
                                <select readonly class="form-control" id="cmbControl" name="cmbControl">
                                    <option value="0">SELECCIONE</option>
                                    <?php
                                        $Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__PROYEC_b = {$_SESSION['HUESPED']} AND GUION__ConsInte__b = {$_GET['poblacion']} AND GUION__Tipo______b IN(2,3) ORDER BY GUION__Nombre____b ASC";

                                        
                                        $result = $mysqli->query($Lsql);
                                        while($key = $result->fetch_object()){
                                            echo '<option value= "'.$key->GUION__ConsInte__b.'">'.$key->GUION__Nombre____b.'</option>';
                                        } 
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_carga_label;?></label>
                                <input type="file" name="arcExcell" disabled="true" id="arcExcell" class="form-control">
                                <input type="hidden" name="NombrearcExcell"  id="NombrearcExcell" >
                                <input type="hidden" name="NombrearcExcell_BD"  id="NombrearcExcell__BD" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="box-header">
                            <h3 class="box-title" style="margin-left:5px;">Definición para saber cada fila del archivo a cuál registro de Dyalogo corresponde</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $str_colunm_princ;?></label>
                                <select class="form-control" disabled="true" id="cmbColumnaP" name="cmbColumnaP">
                                    <option value="NONE">SELECCIONE</option>    
                                    <?php
                                        if(isset($_GET['poblacion']) && !isset($_GET['muestra'])){
                                            $Lsq_Siexin = "SELECT SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$_GET['poblacion'];
                                            //echo $Lsq_Siexin;
                                            $res_Siexin = $mysqli->query($Lsq_Siexin);
                                            $i = 0;

                                            while ($key = $res_Siexin->fetch_object()) {
                                                $destinos[$i] = $key->SIEXIN_CampDest__b;
                                                $validaciones_array[$i] = $key->SIEXIN_Validacion_b;
                                                echo '<option value="'.$i.'">'.$key->SIEXIN_CampOrig__b.'</option>';
                                                $i++;
                                            } 
                                        }else if(isset($_GET['poblacion']) && isset($_GET['muestra'])){
                                            $Lsq_Siexin = "SELECT SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Muestra___b = ".$_GET['muestra'];
                                            //echo $Lsq_Siexin;
                                            $res_Siexin = $mysqli->query($Lsq_Siexin);
                                            $i = 0;

                                            while ($key = $res_Siexin->fetch_object()) {
                                                $destinos[$i] = $key->SIEXIN_CampDest__b;
                                                $validaciones_array[$i] = $key->SIEXIN_Validacion_b;
                                                echo '<option value="'.$i.'">'.$key->SIEXIN_CampOrig__b.'</option>';
                                                $i++;
                                            } 
                                        }
                                    ?>
                                </select>
                                <input type="hidden" name="txtLlaveExcell" id="txtLlaveExcell" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $str_colunm_prinD;?></label>
                                <select class="form-control" disabled="true" id="cmbColumnaD" name="cmbColumnaD">
                                    <option value="0"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php if (!isset($_GET['formaInvoaca'])){ ?>
                    <div class="row" style="display: none;">
                    <?php }else{ ?>
                    <div class="row">
                    <?php } ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="cheRegistrosCargarNuevo" id="cheRegistrosCargarNuevo">
                                    Volver a llamar los registros que ya existían en Dyalogo y vienen nuevamente en el archivo
                                </label>
                                <div class="form-group">
                                    <label style="font-weight:normal;">
                                        <input type="radio" name="changeTip" id="option1" value="todos" class="radioTip" disabled> Todos
                                        </label style="font-weight:normal;">
                                    <label style="font-weight:normal;margin-left:15px">
                                        <input type="radio" name="changeTip" id="option2" value="noTodos" class="radioTip" disabled> Los que <strong>NO</strong> tengan las siguientes tipificaciones
                                    </label style="font-weight:normal;">   
                                </div>
                                <select class="form-control select2" name="noCambiaEstado[]" id="noCambiaEstado" multiple="multiple" disabled style="width:80%">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="cheRegistrosInactivar" id="cheRegistrosInactivar">Inactivar registros que NO vengan en el archivo</label>
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none;">
                            <div class="form-group">
                                <label><?php echo $str_tipo_accion;?></label>
                                <select class="form-control" disabled="true" id="cmbAction" name="cmbAction">
                                    <option value="3"><?php echo $str_ambas;?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                        <?php 
                            if(isset($_GET['muestra'])){
                                echo "<input type='hidden' name='muestraAInsertar' id='muestraAInsertar' value='".$_GET['muestra']."'>";
                            }else{
                                echo "<input type='hidden' name='muestraAInsertar' id='muestraAInsertar' value='0'>";
                            }
                        ?>
                    <?php if(isset($_GET['distribucion']) && $_GET['distribucion'] == 0){ ?>
                    <div class="row">
                        <div class="col-md-12">
                        <label>Esta campaña donde esta realizando el cargue, tiene como configuración el tipo de asignación pre-definida.<br>Esto quiere decir que cada registro ingresará a la base de Dyalogo asociado a un agente en específico.</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <label>Como quiere asignar los registros?</label>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="margin-top: 10px; text-align: center;">
                                    <input type="radio" onclick="TipoAsignacion()" name="tipoasignacion" value="1" id="checkAutomatico" checked>
                                    <label for="checkAutomatico">Asignación automática: Reparte equitativamente los registros cargados por la cantidad de agentes que pertenecen a la campaña.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="margin-top: 10px; text-align: center;">
                                    <input type="radio" onclick="TipoAsignacion()" name="tipoasignacion" value="2" id="checkManual">
                                    <label for="checkManual">Asignación pre-definida: Asigna los registros según el campo en el Excell donde este el correo electrónico del agente al que le pertenece.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_title_campo_agente;?></label>
                                <select class="form-control" disabled="true" id="cmbAgent" name="cmbAgent">
                                    <option value="NONE">SELECCIONE</option>    
                                    <?php
                                        if(isset($_GET['poblacion']) && !isset($_GET['muestra'])){
                                            $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$_GET['poblacion'];
                                            //echo $Lsq_Siexin;
                                            $res_Siexin = $mysqli->query($Lsq_Siexin);
                                            $i = 0;

                                            while ($key = $res_Siexin->fetch_object()) {
                                                $destinos[$i] = $key->SIEXIN_CampDest__b;
                                                $validaciones_array[$i] = $key->SIEXIN_Validacion_b;
                                                echo '<option value="'.$i.'">'.$key->SIEXIN_CampOrig__b.'</option>';
                                                $i++;
                                            } 
                                        }else if(isset($_GET['poblacion']) && isset($_GET['muestra'])){
                                            $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b, SIEXIN_Validacion_b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Muestra___b = ".$_GET['muestra'];
                                            //echo $Lsq_Siexin;
                                            $res_Siexin = $mysqli->query($Lsq_Siexin);
                                            $i = 0;

                                            while ($key = $res_Siexin->fetch_object()) {
                                                $destinos[$i] = $key->SIEXIN_CampDest__b;
                                                $validaciones_array[$i] = $key->SIEXIN_Validacion_b;
                                                echo '<option value="'.$i.'">'.$key->SIEXIN_CampOrig__b.'</option>';
                                                $i++;
                                            } 
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo $str_title_table_cDB;?></h3>

                        </div>
                        <div class="box-body">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo $str_title_excel;?></th>
                                        <th><?php echo $str_title_BD;?></th>
                                         <th><?php echo $str_title_validaciones;?></th> 
                                    </tr>

                                </thead>
                                <tbody id="curpo_tabla_validaciones">
                                
                                </tbody>
                            </table>
                            <div class="row"> 
                                <div class="col-md-10">
                                    <button id="click_Exportar" style="display: none;" type="button" >Exportar datos Csv</button>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                       
                                        <input type="hidden" name="totales" id="totales" value="">
                                        <input type="hidden" name="pasoId" id='pasoId' value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo '0';}?>">
                                        <input type="hidden" name="sincronizar" id='sincronizar' value="<?php if(isset($_GET['sincronizar'])){ echo $_GET['sincronizar']; }else{ echo '0';}?>">
                                        <button type="button" disabled name="btnExcell" id="btnExcell" class="btn btn-primary btn-block" ><i class="fa fa-save"></i>&nbsp; <?php echo $str_button_label;?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- REPORTES DEL PASO -->


                    <div class="box">
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_reports" class="collapsed" aria-expanded="true">
                                        REPORTES
                                    </a>
                                </h4>
                            </div>
                            <div id="s_reports" class="panel-collapse collapse" aria-expanded="true">
                                <div class="box-body">
                                    <div class="row">
                                        <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=green&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
<?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>
<script type="text/javascript">

    $(function(){

        $("#noCambiaEstado").hide();

        $.ajax({
            url     : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php',
            type    : 'post',
            data    : { getTipificaciones : true, paso : '<?php echo $_GET['id_paso'];?>'},
            success : function(data){
                $("#noCambiaEstado").html(data);
                $("#noCambiaEstado").select2({
                    placeholder: "Seleccione las tipificaciones a excluir"
                });
            }
        });

        $("#click_Exportar").click(function(){
            window.location.href = 'exportar.php';
        });

        $("#cheRegistrosCargarNuevo").on('change',function(){
            if($(this).is(':checked')){
                $(".radioTip").attr('disabled',false);
                $("#noCambiaEstado").show();
            }else{
                $(".radioTip").attr('disabled',true);
                $("#noCambiaEstado").hide();
            }
        });

        $("input[name=changeTip]").click(function () {    
            if($(this).val() == 'todos'){
                $("#noCambiaEstado").attr('disabled',true);
            }else{
                $("#noCambiaEstado").attr('disabled',false);
            }
        });
        
        var htmlDeaqi = '';
        var html_excel = '<option value="NONE">SELECCIONE</option>';
        var tiene_configuracion = false;
        <?php 
           if(isset($_GET['poblacion']) && !isset($_GET['muestra'])){
        ?>
            $("#cmbControl").val('<?php echo $_GET['poblacion'];?>');
            $("#cmbControl").change();

            $.ajax({
                url         : '<?=$url_crud;?>',
                type        : 'post',
                data        : {  llenarDatosGs : 'si', cmbControl : <?php echo $_GET['poblacion'];?> },
                success     : function(data){
                
                    $("#cmbColumnaD").html(data);
                    htmlDeaqi = data.replace('selected','');
                    $("#cmbColumnaD").attr('disabled', false);
                    $("#arcExcell").attr('disabled', false);
                    $("#cmbAction").attr('disabled', false);
                    $("#btnExcell").attr('disabled', false);
                    $("#cmbColumnaP").attr('disabled', false);
                    <?php
                        //primero es validar que efectivamente ya esten cargada la configuracion de esto
                        $Lsql_SIDAEX = "SELECT SIDAEX_TipoInse_b, SIDAEX_RutaArchi_b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Destino___b = ".$_GET['poblacion'];
                        $result_SIDAEX = $mysqli->query($Lsql_SIDAEX);
                        $cur_SIDAEX = $result_SIDAEX->fetch_array();

                        $Lslq_Siexll = "SELECT SIEXLL_LlavDest__b, SIEXLL_LlavOrig__b FROM ".$BaseDatos_systema.".SIEXLL JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXLL_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$_GET['poblacion'];

                        $result_Siexll = $mysqli->query($Lslq_Siexll);
                        $cur_Siexll = $result_Siexll->fetch_array();
                    ?>

                    <?php if($res_Siexin->num_rows > 0){ ?>
                    tiene_configuracion = true;
                    $("#NombrearcExcell__BD").val('<?php echo $cur_SIDAEX['SIDAEX_RutaArchi_b'];?>');
                    $("#cmbAction").val('<?php echo $cur_SIDAEX['SIDAEX_TipoInse_b'];?>').change();
                    $("#cmbColumnaD").val('<?php echo $cur_Siexll['SIEXLL_LlavDest__b'];?>').change();
                    $("#cmbColumnaP option").filter(function() {
                        return $(this).text() == "<?php echo $cur_Siexll['SIEXLL_LlavOrig__b']; ?>";
                    }).prop('selected', true).change();
                    <?php } ?>
                    
                }
            });
        <?php 
            }
        ?>

        <?php 
            if(isset($_GET['muestra']) && isset($_GET['poblacion']) ){
        ?>
            $("#cmbControl").val('<?php echo $_GET['poblacion'];?>');
            $("#cmbControl").change();

            $.ajax({
                url         : '<?=$url_crud;?>',
                type        : 'post',
                data        : {  llenarDatosGs : 'si', cmbControl : <?php echo $_GET['poblacion'];?> },
                success     : function(data){
                    
                    $("#cmbColumnaD").html(data);
                    htmlDeaqi = data.replace('selected','');
                    $("#cmbColumnaD").attr('disabled', false);
                    $("#arcExcell").attr('disabled', false);
                    $("#cmbAction").attr('disabled', false);
                    $("#btnExcell").attr('disabled', false);
                    $("#cmbColumnaP").attr('disabled', false);

                    <?php
                        //primero es validar que efectivamente ya esten cargada la configuracion de esto

                        $Lsql_SIDAEX = "SELECT SIDAEX_TipoInse_b, SIDAEX_RutaArchi_b FROM ".$BaseDatos_systema.".SIDAEX WHERE SIDAEX_Muestra___b = ".$_GET['muestra'];
                            $result_SIDAEX = $mysqli->query($Lsql_SIDAEX);
                            $cur_SIDAEX = $result_SIDAEX->fetch_array();

                        $Lslq_Siexll = "SELECT SIEXLL_LlavDest__b, SIEXLL_LlavOrig__b FROM ".$BaseDatos_systema.".SIEXLL JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXLL_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Muestra___b = ".$_GET['muestra'];

                        $result_Siexll = $mysqli->query($Lslq_Siexll);
                        $cur_Siexll = $result_Siexll->fetch_array();
                    
                    ?>

                    <?php if($res_Siexin->num_rows > 0){ ?>
                    tiene_configuracion = true;
                    $("#NombrearcExcell__BD").val('<?php echo $cur_SIDAEX['SIDAEX_RutaArchi_b'];?>');
                    $("#cmbAction").val('<?php echo $cur_SIDAEX['SIDAEX_TipoInse_b'];?>').change();
                    $("#cmbColumnaD").val('<?php echo $cur_Siexll['SIEXLL_LlavDest__b'];?>').change();
                    $("#cmbColumnaP option").filter(function() {
                        return $(this).text() == "<?php echo $cur_Siexll['SIEXLL_LlavOrig__b']; ?>";
                    }).prop('selected', true).change();
                    <?php } ?>
                    
                }
            });
        <?php 
            }
        ?>

        $("#cargueDeDatos").addClass('active');

        $("#cmbColumnaP").change(function(){
            controlarCampoLlave();
        });

        $("#cmbColumnaD").change(function(){
            controlarCampoLlave();
        });


        $("#cmbControl").change(function(){
            tiene_configuracion = false;
            var id = $(this).val() ;
            $.ajax({
                url         : '<?=$url_crud;?>',
                type        : 'post',
                data        : {  llenarDatosGs : 'si', cmbControl : id },
                beforeSend : function() {
                   $.blockUI({ baseZ: 2000 , message: "<?php echo $str_message_wait  ;?>" });
                },
                success     : function(data){
                    $("#cmbColumnaD").html(data);
                    htmlDeaqi = data.replace('selected','');
                    $("#cmbColumnaD").attr('disabled', false);
                    $("#arcExcell").attr('disabled', false);
                    $("#cmbAction").attr('disabled', false);
                    $("#btnExcell").attr('disabled', false);

                    $.ajax({
                        url   :  '<?=$url_crud;?>',
                        type  : 'post',
                        data  : { validar_configuraciones : 'si' , cmbControl : id  },
                        success : function(datos){
                            $("#cmbColumnaP").html(datos);
                            $("#cmbColumnaP").attr('disabled', false);
                            //iene_configuracion = true;
                            if(datos.length > 1){
                                tiene_configuracion = true;
                            }

                            $.ajax({
                                url   :  '<?=$url_crud;?>',
                                type  : 'post',
                                data  : { validar_campos_primarios : 'si' , cmbControl : id  },
                                dataType : 'json',
                                success : function(datos){
                                    $("#cmbColumnaD").val(datos.Principal_Destino).change();
                                    $("#cmbColumnaP option").filter(function() {
                                        return $(this).text() == datos.Principal_origen ;
                                    }).prop('selected', true).change();

                                    $("#txtLlaveExcell").val(datos.Principal_origen);
                                }
                            })
                            
                        }
                    });


                    $.ajax({
                        url   :  '<?=$url_crud;?>',
                        type  : 'post',
                        data  : { obtener_configuraciones : 'si' , cmbControl : id  },
                        success : function(datos){
                            $("#curpo_tabla_validaciones").html(datos);
                        }
                    });
                },
                complete: function(){
                    $.unblockUI();
                }
            });


        });

        /** YCR- 2019-09-01
         * \funcion ajax que carga columnas de excel y base de datos
         */
        $('#arcExcell').on('change', function(e){

            var strExtension_t = this.files[0].name;

            var ext = strExtension_t.split('.').pop();
            /* primero validar que sea solo excel */
            var imagen = this.files[0];
            //console.log(imagen);
            if((imagen['type'] != "application/vnd.ms-excel" && imagen['type'] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") || ext == "xls"){
                $("#arcExcell").val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo debe estar en formato XLSX",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }else if(imagen['size'] > 5000000 ) {
                $("#arcExcell").val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo no debe pesar mas de 5MB",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }else{
                $("#NombrearcExcell").val(imagen.name);
                tiene_configuracion = false;                

                var formData = new FormData($("#formEnvioDatos")[0]);
                $.ajax({
                    url         : '<?=$url_crud;?>?getcolumns=si',
                    type        : 'post',
                    data: formData,
                    dataType    : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function() {
                       $.blockUI({ baseZ: 2000 , message: "<?php echo $str_message_wait  ;?>" });
                    },
                    //una vez finalizado correctamente
                    success: function(data){


                        //total columnas detectadads en el excel
                        var total = data.total;  
                        var nulos=0;                      

                        var option = '<option value="NONE">SELECCIONE</option>';
                        for(i = 0; i < total; i++){
                            //opcion = columnas del excel
                            if(data.opciones[i]['Nombres'] != null && data.opciones[i]['Nombres'] != ''){
                                option += '<option  value="'+ i +'">'+ data.opciones[i]['Nombres'] +'</option>';
                               
                            }else{
                                nulos++;
                            }
                          
                        }
                        total = total - nulos;
                        $("#totales").val(total);
                        var tr = '';                        

                        //columna principal en el excel
                        var valcmbColumnaP = $("#cmbColumnaP").val();

                        $("#cmbColumnaP").html(option);
                        $("#cmbColumnaP").val(valcmbColumnaP);
                        $("#cmbAgent").html(option);
                        $("#cmbColumnaP").attr('disabled', false);
                        $("#cmbColumnaP").change(function(){
                            var combo = document.getElementById("cmbColumnaP");//select de columnas del excel
                            var selected = combo.options[combo.selectedIndex].text;//toma el texto del select col excel
                            $("#txtLlaveExcell").val(selected);//guarda texto de la columna selecionado del excel
                        });


                        if(!tiene_configuracion){                            
                        //si cumple con el formato xls o xlsx entra

                            var option_validacion = '<option value=\'1\'><?php echo $str_validacion_1;?></option>';
                            option_validacion += '<option value=\'2\'><?php echo $str_validacion_2;?></option>';
                            

                            for(i = 0; i < total; i++){

                                let disabledAttr = (i == 0) ? "disabled" : "";

                                tr += '<tr>';
                                tr += '<td width="33%"><select  '+disabledAttr+' class="form-control select_excel" numero="'+ i +'" id="selExcel'+ i +'" name="selExcel'+ i +'">'+ option +'</select><input type="hidden" name="nombreExell'+ i +'" id="nombreExell'+ i +'" value="NONE"></td>';
                                tr += '<td width="34%"><select  '+disabledAttr+'  onchange="validar('+i+');" class="form-control " id="selDB'+ i +'" name="selDB'+ i +'">'+ htmlDeaqi +'</select></td>';
                                tr += '<td width="33%"><select class="form-control" id="valDB'+ i +'" name="valDB'+ i +'">'+ option_validacion +'</select></td>';
                                tr += '</tr>';

                            }

                            $("#curpo_tabla_validaciones").html(tr);

                            $(".select_excel").change(function(){

                                var combo = document.getElementById('selExcel'+ $(this).attr('numero'));
                                var selected = combo.options[combo.selectedIndex].text;//captura el text del select 
                                $("#nombreExell"+$(this).attr('numero')).val(selected);//llena input oculto con la info del select

                            });

                        }


                        controlarCampoLlave();

                    },
                    complete: function(){
                        $.unblockUI();
                    },
                    //si ha ocurrido un error
                    error: function(){
                        alertify.error('<?php echo $error_de_proceso; ?>');
                    }
                })
                   
            }
        });   

        /** YCR - 2019-08-30
         * \funcion que se ejecuta para ver progreso del cargue de registros
         */
        $("#btnExcell").click(function(){           
            
            var valido = 0;
            var strcampo_agente = '';
            var mensaje="";
            var camposHaValidar='';
            var origenDY=0; 
            var estadoValidacion=1;
            var conteoProgress=0;
            borrarLogCargue();        

            if($('#checkManual').is(':checked') && $('#cmbAgent').val() == 'NONE'){
                mensaje="¡Debes seleccionar la columna de agente!  ";
                valido = 1;
            }
            var archivoExcel =$('#arcExcell').val();
            if(archivoExcel == ''){
                mensaje+="  ¡Debes seleccionar un archivo!  ";
                valido =1;
            }
            var cmbColumnaD =$('#cmbColumnaD').val();
            if(cmbColumnaD == 'NONE'){
                mensaje+="  ¡Debes seleccionar Campo de apareamiento con dyalogo!  ";
                valido =1;
            }
            var cmbColumnaP =$('#cmbColumnaP').val();
            if(cmbColumnaP == 'NONE'){
                 mensaje+="  ¡Debes seleccionar Campo de apareamiento con el excel!  ";
                valido =1;
            }
            
            if(valido == 1){
                swal({
                    title : "Error al subir el archivo",
                    text  : mensaje,
                    type  : "error",
                    confirmButtonText : "<?php echo $str_Cerrar;?>"
                });
            }           

            if(valido == 0){
                
                var totalColumnas=$("#totales").val();
                for (i=0;i<totalColumnas;i++){                   

                    if($("#valDB"+i+"  option:selected").val() == '2'){
                       camposHaValidar+=$("#selDB"+i+"  option:selected").text()+",";  
                       estadoValidacion = 0;                 
                                              
                    }
                    if($("#selDB"+i+"  option:selected").text() == 'ORIGEN_DY_WF'){
                         origenDY=1;
                         
                    }

                 }             

            }            

            if(valido == 0){    
                
                $("#selExcel0").removeAttr('disabled');
                $("#selDB0").removeAttr('disabled');
                                  
                var form = $("#formEnvioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var intIdPaso_t = <?php if (isset($_GET["id_paso"])) { echo $_GET["id_paso"]; }else{echo 0;} ?>;
                var formData = new FormData($("#formEnvioDatos")[0]); 
                formData.append('origenDY', origenDY);   
                formData.append('strIdCampan','<?php echo $strIdCampan;?>');  
                formData.append('estadoValidacion', estadoValidacion);
                formData.append('id_paso',intIdPaso_t);

                /** YCR - 2019-08-30
                    * \funcion ajax utilizada para cargar registros  
                */
                $.ajax({
                    url: '<?=$url_crud?>?llenarDatos=si'+strcampo_agente,
                    type  : 'post',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function() {
                        $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_Carga;?>' }); 
                    },
                    success: function(data){

                        var strTablaTemp_t =data.strTablaTemp_t;
                        var intIdBase_t = data.intIdBase_t;
                        var strCampoUnion_t = data.strCampoUnion_t;
                        var strAgente_t = data.strAgente_t;
                        var arrCamposEnlazados_t = data.arrCamposEnlazados_t;
                        var arrIdAgentes_t = data.arrIdAgentes_t;
                        var arrValidaTelefonos_t = JSON.parse(data.arrValidaTelefonos_t);
                        var strUnionDeCampos_t = data.strUnionDeCampos_t;
                        var strCampoNoExiste_t = data.strCampoNoExiste_t;
                        var strJar_t = data.strJar_t;
                        var strNombreExcell_t=data.strNombreExcell_t;    
                        var intReLlamar_t = data.intReLlamar_t;
                        var strTipificacionNoUpdate_t = data.strTipificacionNoUpdate_t;
                        var intInactivar_t = data.intInactivar_t;
                        var intCount_t=0;  
                        var intCantidadRegistros_t = 0;
                        var intIdMuestra_t = <?php if(isset($_GET["muestra"])){echo $_GET["muestra"]; } else{ echo -1;}?>;
                        var intCantidadCargaPaginada_t = data.intCantidadCargaPaginada_t;
                        var intLapsoPorPagina_t = data.intLapsoPorPagina_t;
                        var intIdOrigen_DY_t = data.intIdOrigen_DY_t;
                        var booProcedemos_t = true;
                        var intSentido_t = arrValidaTelefonos_t[0];


                        console.log(strJar_t); 

                        if (strCampoNoExiste_t == "") {

                            if (arrValidaTelefonos_t[2]) {

                                if (arrValidaTelefonos_t[0] == 6) {

                                    if (arrValidaTelefonos_t[1] == 0) {

                                        booProcedemos_t = false;

                                    }

                                }

                            }
                            if (booProcedemos_t) {

                                //JDBD - Mostramos las barras de progreso.

                                $("#TablaTemporal").val(strTablaTemp_t);
                                $('.contienebarra').css('visibility','visible');
                                $('.modalcargue').css('opacity','.1');
                                $('.estado1').html('En Progreso');
                                $('.estado1').css('color', 'blue');


                                //JDBD - Empezamos con el progreso de las barras.

                                var intervalBarrasDeProgreso = setInterval(function(){


                                    if(intCount_t < 100){

                                        if(conteoProgress > 7 && intCount_t === 0){
                                            clearInterval(intervalBarrasDeProgreso);
                                            $('.contienebarra').css('visibility','hidden');
                                            $("#cargarInformacion").modal('hide');
                                            alertify.error('El excel no es valido para cargar');
                                            swal({
                                                html : true,
                                                title: "El archivo de excel no pudo ser leído correctamente",
                                                text: 'Revise que no tenga emoticons, fórmulas o que no estén apareadas columnas en blanco.Por si el archivo está corrupto una opción es crear un archivo nuevo, copiar del archivo original y pegar LOS VALORES en el nuevo.',
                                                type: "error",
                                                confirmButtonText: "Aceptar",
                                                showCancelButton : false,
                                                closeOnConfirm : true
                                            },
                                            function(isconfirm){}
                                            );
                                        }else{
                                            //JDBD - Llenamos la primera barra que es la del cargue en la tabla temporal con el .jar
                                                $.ajax({
                                                    url:'<?=$url_crud?>?conteo=si',
                                                    type: 'post',
                                                    dataType : 'json',
                                                    data: {base:strTablaTemp_t},
                                                    success: function(data){
    
                                                        if (data.excede != "") {
    
                                                            swal({
                                                                html : true,
                                                                title : "!!Longitud Excedida¡¡",
                                                                text  : "Los siguientes campos no fueron cargados ya que exceden la capacidad limite de caracteres.<br><br><strong>"+Exentos(data.excede)+"</strong>",
                                                                type  : "error",
                                                                confirmButtonText : "Cerrar"
                                                            });
    
                                                        }
    
                                                        intCount_t=data.porcentaje;
                                                        $('.progress-bar1').html('');
                                                        $('.progress-bar1').html(intCount_t+'%');
                                                        $('.progress-bar1').width(intCount_t+'%');
    
                                                    }
                                                });
                                        }
                                        conteoProgress++;
                                    }else{

                                        //JDBD - Detenemos el llenado de la primera barra.
                                            clearInterval(intervalBarrasDeProgreso);
                                            $('.estado1').html('Finalizado');
                                            $('.estado1').css('color', 'green');  

                                        //JDBD - Obtenemos el array con la informacion de los campos enlazados.    
                                            var arrInfoCampos_t = infoCamposEnlazados(strTablaTemp_t,intIdBase_t,arrCamposEnlazados_t);

                                        //JDBD - Empezamos con el llenado de la segunda barra de la validacion de datos.    
                                            $('.estado2').html('En Progreso');
                                            $('.estado2').css('color', 'blue');      

                                            var intTotalCampos_t = arrInfoCampos_t.length;

                                            function validarDatosCampos(x){

                                                if (x < intTotalCampos_t) {

                                                    var strNombreCampo_t = arrInfoCampos_t[x].strNombreCampo_t;
                                                    var intTipo_t = arrInfoCampos_t[x].intTipo_t;
                                                    var intIdCampo_t = arrInfoCampos_t[x].intIdCampo_t;
                                                    var intValidador_t=arrInfoCampos_t[x].intValidador_t;
                                                    let longitudCampo = arrInfoCampos_t[x].longitud_pregun

                                                    var intValidacionPorcentaje_t = datoCampoValidar(strTablaTemp_t,strNombreCampo_t,intTipo_t,longitudCampo,intIdCampo_t,intValidador_t,x,intTotalCampos_t,intIdPaso_t,intIdMuestra_t,intSentido_t);

                                                    $('.progress-bar2').html('');
                                                    $('.progress-bar2').html(intValidacionPorcentaje_t+'%');
                                                    $('.progress-bar2').width(intValidacionPorcentaje_t+'%'); 

                                                    if(intValidacionPorcentaje_t == 100){    
                                                        $('.estado2').html('Finalizado');
                                                        $('.estado2').css('color', 'green');  
                                                        $('.estado3').html('En Progreso');
                                                        $('.estado3').css('color', 'blue');   
                                                    }

                                                    setTimeout(() => validarDatosCampos(x+1), 500);

                                                }else{

                                                    //JDBD - Ahora iniciamos las transacciones a la base de datos.
                                                        $('.estado3').html('En Progreso');
                                                        $('.estado3').css('color', 'blue'); 

                                                        var arrResumenCargue = depurarBase(strTablaTemp_t,intIdBase_t,strCampoUnion_t,intIdPaso_t,intIdMuestra_t,strUnionDeCampos_t);

                                                        //JDBD - Seteamos las variables con el resumen de los conteos.
                                                            var intTotalReg_t = arrResumenCargue.intTotalReg_t;
                                                            var intTotalRep_t = arrResumenCargue.intTotalRep_t;
                                                            var intTotalNew_t = arrResumenCargue.intTotalNew_t;
                                                            var intTotalAct_t = arrResumenCargue.intTotalAct_t;
                                                            var intTotalFail_t = arrResumenCargue.intTotalFail_t;
                                                            var intTotalTemp_t = arrResumenCargue.intTotalTemp_t;
                                                            var strFechaInsercion_t = arrResumenCargue.strFechaInsercion_t;
                                                            var intTiempo_t = arrResumenCargue.intTiempo_t;

                                                        var intPaginacion_t = intCantidadCargaPaginada_t;
                                                        var intPaginas_t = Math.ceil(intTotalTemp_t/intPaginacion_t);

                                                        var intRegPagina_t = 0;
                                                        var intPorcentajeCargue_t = 0;
                                                        var strEstadoPaginado_t = "";

                                                        //JDBD - Esta funcion es para tramitar los registros de la tabla temporal a las bases reales con una espera de  2 segundos por paginacion.
                                                            function cargueRetardado(x) {

                                                              if (x < intPaginas_t) {


                                                                strEstadoPaginado_t = paginarRegistros(strTablaTemp_t,intPaginacion_t);

                                                                //JDBD - Validamos que la paginacion sea un exito y procedemos a tramitar esos registros a las bases reales.
                                                                    if (strEstadoPaginado_t == "ok") {
                                                                        transaccionesBd(arrInfoCampos_t,strTablaTemp_t,strCampoUnion_t,intIdBase_t,intIdPaso_t,intIdMuestra_t,strUnionDeCampos_t,intReLlamar_t,strTipificacionNoUpdate_t,arrIdAgentes_t,strNombreExcell_t,intInactivar_t,strAgente_t,strFechaInsercion_t,intTiempo_t,intIdOrigen_DY_t);
                                                                    }

                                                                //JDBD - Calculamos el porcenta de los registros trabajados sobre la cantidad total.
                                                                    intRegPagina_t = intRegPagina_t+intPaginacion_t;
                                                                    intPorcentajeCargue_t = Math.round((intRegPagina_t*100)/intTotalTemp_t);
                                                                    if (intPorcentajeCargue_t > 100) { intPorcentajeCargue_t = 100;}

                                                                //JDBD - Hacemos crecer la ultima barra de progreso.
                                                                    $('.progress-bar3').html('');
                                                                    $('.progress-bar3').html(intPorcentajeCargue_t+'%');
                                                                    $('.progress-bar3').width(intPorcentajeCargue_t+'%');

                                                                setTimeout(() => cargueRetardado(x+1), intLapsoPorPagina_t);

                                                              }else{

                                                                //JDBD - Validamos si se deben iniactivar los registros.
                                                                if (intInactivar_t == 1) {

                                                                    InactivarRegistros(intIdBase_t,intIdMuestra_t,strTablaTemp_t,strCampoUnion_t);

                                                                }

                                                                procesarFlechas(intIdPaso_t, intIdBase_t, strCampoUnion_t, strTablaTemp_t, intIdMuestra_t);

                                                                $('.estado3').html('Finalizado');
                                                                $('.estado3').css('color', 'green');
                                                                $('#cierraimportacion').css('visibility','visible');

                                                                detallesCargue(intTotalReg_t,intTotalRep_t,intTotalNew_t,intTotalAct_t,intTotalFail_t,strFechaInsercion_t,intTiempo_t,intIdMuestra_t,intIdBase_t,strTablaTemp_t,strCampoUnion_t)

                                                              }
                                                            }

                                                        //JDBD - Ejecutamos la funcion que empezara a cargar la base.
                                                        cargueRetardado(0);

                                                }

                                            }

                                            //JDBD - Llamamos la funcion para Validar los datos.
                                            validarDatosCampos(0);
                                    }

                                },2000);
                                
                            }else{

                                swal({
                                    title : "!No hay Troncal",
                                    text  : "Si desea validar los telefonos, debe asignar por lo menos una troncal a la campaña.",
                                    type  : "error",
                                    confirmButtonText : "Cerrar"
                                });

                            }
                            

                        }else{

                            swal({
                                title : "Error al cargar",
                                text  : strCampoNoExiste_t,
                                type  : "error",
                                confirmButtonText : "Cerrar"
                            });

                        }



                    },
                    complete : function(){
                        $.unblockUI();                            
                        
                    },
                    //si ha ocurrido un error
                    error: function(){ 
                        $.unblockUI();                                                                           
                        mensajeError();                       
                        
                    }
                });              
            }
        });
    });
    
    $('#cierraimportacion').click(function(){
        $('#cierraimportacion').css('visibility','hidden');
        $('.contienebarra').css('visibility','hidden');
        $('.modalcargue').css('opacity','1');
        try {
            carguePredefinido();
        } catch (error) {}
    });
   
    /** YCR - 2019-29-08
     * \funcion que verifica qe tipo de asignacion se selecciono(predefinida o dinamica).
     */
    function TipoAsignacion(){
        if($('#checkManual').is(':checked')){
            $('#cmbAgent').attr('disabled', false);
        }else{
            $('#cmbAgent').attr('disabled', true);
        }
    }
    /** YCR - 2019-09-02
     * \funcion que arroja mensaje de error cuando falla algo en las peticiones  ajax
     */
    function mensajeError(){
        swal({
            title : "Error al subir el archivo",
            text  : "<?php echo $error_de_red;?>",
            type  : "error",
            confirmButtonText : "<?php echo $str_Cerrar;?>"
        });  
    }

    /** YCR - 2019-09-03
     * \funcion que arroja mensaje de error cuando falla algo en las peticiones  ajax
     * @param recibe id de la fila que queremos validar
     */
     function validar(numero){
        var regex = /(telefono|teléfono|tel|celular|cel)/i;
        if($("#selDB"+numero+"  option:selected").text().match(regex) != null){
               $("#valDB"+numero).val(2);                                    
                                      
        }else{
           $("#valDB"+numero).val(1); 
        }       
    }

    /**YCR 2019-09-16
    * function que elimina el log de los registros recien cargados
    */
    function borrarLogCargue(){
         $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD.php',
                type  : 'post',
                data: {'opcion':'borrarLogCargue'},
                dataType : 'json',
                success:function(data){
                    
                }
         }); 
    }   
    /**JDBD
    * function que trae toda la informacion de los campos enlazados.
    */
    function infoCamposEnlazados(strTablaTemp_p,intIdBase_p,arrCamposEnlazados_p){

        strJSONInfo_t = $.ajax({
                                    url      : '<?=$url_crud?>?getInformation=si',
                                    type     : 'POST',
                                    data     : {strTablaTemp_t: strTablaTemp_p, intIdBase_t : intIdBase_p, arrCamposEnlazados_t : arrCamposEnlazados_p},
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;

        return JSON.parse(strJSONInfo_t);

    }
    /**JDBD
    * function que valida los datos del campo enlazado segun su tipo.
    */
    function datoCampoValidar(strTablaTemp_p, strNombreCampo_p, intTipo_p, longitudCampo, intIdCampo_p,  intValidador_p, intProgreso_p, intTotal_p, intIdPaso_p, intIdMuestra_p, intSentido_p){

        var intIdPaso_t = intIdPaso_p;

        if (intIdMuestra_p == -1) {

            intIdPaso_t = 0;

        }

        strPorcentajeValidacion_t = $.ajax({
                                    url      : '<?=$url_crud?>?validaCampos=si',
                                    type     : 'POST',
                                    data     : {strTablaTemp_t: strTablaTemp_p, strNombreCampo_t: strNombreCampo_p, intTipo_t: intTipo_p, longitud_campo: longitudCampo, intIdCampo_t: intIdCampo_p, intValidador_t: intValidador_p,intProgreso_t: intProgreso_p, intTotal_t : intTotal_p, intIdPaso_t : intIdPaso_t, intIdHuesped_t : <?=$_SESSION["HUESPED"]?>, intSentido_t : intSentido_p},
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;

        return strPorcentajeValidacion_t;

    }

    /**JDBD
    * function que cuenta los registros nuevos, atualizados y con errores, ademas de eliminar los repetidos..
    */
    function depurarBase(strTablaTemp_p,intIdBase_p,strCampoUnion_p,intIdPaso_p,intIdMuestra_p,strUnionDeCampos_p){

        strJSONResumen_t = $.ajax({
                                    url      : '<?=$url_crud?>?depurarBase=si',
                                    type     : 'POST',
                                    data     : {strTablaTemp_t : strTablaTemp_p, intIdBase_t : intIdBase_p, strCampoUnion_t : strCampoUnion_p, intIdPaso_t : intIdPaso_p, intIdMuestra_t : intIdMuestra_p, strUnionDeCampos_t :strUnionDeCampos_p},
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;

        return JSON.parse(strJSONResumen_t);

    }

    /**JDBD
    * function que pagina los registros a tramitar..
    */
    function paginarRegistros(strTablaTemp_p,intPaginacion_p){

        strEstadoPaginado_t = $.ajax({
                                    url      : '<?=$url_crud?>?Paginar=si',
                                    type     : 'POST',
                                    data     : {strTablaTemp_t : strTablaTemp_p, intPaginacion_t : intPaginacion_p},
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;

        return strEstadoPaginado_t;

    }

    /**JDBD
    * Esta funcion realiza todo el proceso del traspaso de los datos cargados en la tabla temporal a las tablas reales de las campañas.
    */
    function transaccionesBd(arrInfoCampos_p,strTablaTemp_p,strCampoUnion_p,intIdBase_p,intIdPaso_p,intIdMuestra_p,strUnionDeCampos_p,intReLlamar_p,strTipificacionNoUpdate_p,arrIdAgentes_p,strNombreExcell_p,intInactivar_p,strAgente_p,strFechaInsercion_p,intTiempo_p,intIdOrigen_DY_p){

        strTransaccionBd_t = $.ajax({
                                    url      : '<?=$url_crud?>?transaccionesBd=si',
                                    type     : 'POST',
                                    data     : {arrInfoCampos_t : arrInfoCampos_p, strTablaTemp_t : strTablaTemp_p, strCampoUnion_t : strCampoUnion_p, intIdBase_t : intIdBase_p, intIdPaso_t : intIdPaso_p, intIdMuestra_t : intIdMuestra_p, strUnionDeCampos_t : strUnionDeCampos_p, intReLlamar_t : intReLlamar_p, strTipificacionNoUpdate_t : strTipificacionNoUpdate_p, arrIdAgentes_t : arrIdAgentes_p, strNombreExcell_t : strNombreExcell_p, intInactivar_t : intInactivar_p, strAgente_t : strAgente_p, strFechaInsercion_t : strFechaInsercion_p, intTiempo_t : intTiempo_p, intIdOrigen_DY_t : intIdOrigen_DY_p},
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;

        return strTransaccionBd_t;

    }
    /**JDBD
    * Esta funcion es para iniactivar los registros de la campaña que no esten en el Excell.
    */
    function InactivarRegistros(intIdBase_p,intIdMuestra_p,strTablaTemp_p,strCampoUnion_p){
                 $.ajax({
                    url: '<?=$url_crud?>?InactivarRegistros=si',
                    type  : 'post',
                    data: {intIdBase_t : intIdBase_p,intIdMuestra_t : intIdMuestra_p,strTablaTemp_t : strTablaTemp_p,strCampoUnion_t : strCampoUnion_p}
             }); 
    }
    /**JDBD
    * Esta funcion es para mostrar el resumen del cargue despues de finalizar.
    */
    function detallesCargue(intTotal_p,intRepetidos_p,intNuevos_p,intActualizados_p,intFallos_p,strFechaInsercion_p,intTiempo_p,intIdMuestra_p,intIdBase_p,strTablaTemp_p,strCampoUnion_p){
        $("#cierraimportacion").click(function(){

            $("#title_cargue").html('<span data-toggle="tooltip"  class="badge bg-light-blue" style="margin-right: 30px !important;" >Total Registros: '+intTotal_p+'</span> <span data-toggle="tooltip"  class="badge bg-light-gray" style="margin-right: 30px !important;" >Registros Repetidos: '+intRepetidos_p+'</span> <span data-toggle="tooltip" title="" class="badge bg-green" style="margin-right: 30px !important;" >Nuevos: '+intNuevos_p+'</span> <span data-toggle="tooltip" title="" class="badge bg-yellow" style="margin-right: 30px !important;">Actualizados: '+intActualizados_p+'</span><span data-toggle="tooltip" title="" class="badge bg-red" >Fallaron por Validación: '+intFallos_p+'</span>:<span id="exportarErrores" class="badge bg-red" style="cursor:pointer;">Exportar</span>');

                $("#fallasValidacion").val(intFallos_p);

                var tipoReport_t = "cargue";
                var intIdMuestra_t = intIdMuestra_p;
                var strFechaIn_t = intTiempo_p;
                var strFechaFn_t = intTiempo_p;
                var intIdHuesped_t = <?=$_SESSION["HUESPED"]?>;
                var intIdEstrat_t = 0;
                var intIdGuion_t = intIdBase_p;
                var intIdCBX_t = 0;
                var intIdPeriodo_t = 0;
                var intIdPaso_t = "";

                $.ajax({
                        url  :  '<?=base_url?>pages/charts/report.php',
                        type :  'post',
                        data : {tipoReport_t : tipoReport_t,intIdMuestra_t : intIdMuestra_t,strFechaIn_t : strFechaIn_t,strFechaFn_t : strFechaFn_t,intIdHuesped_t : intIdHuesped_t,intIdEstrat_t : intIdEstrat_t,intIdGuion_t : intIdGuion_t,intIdCBX_t : intIdCBX_t,intIdPeriodo_t : intIdPeriodo_t,intIdPaso_t : intIdPaso_t, strLimit_t : "50", strTablaTemp_t : strTablaTemp_p, strCampoUnion_t : strCampoUnion_p},                                       
                        success : function(data){
                            exportarErrores();

                            $("#divIframe").html(data);
                        },
                        beforeSend : function(){
                            $.blockUI({baseZ: 2000,message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                            $("#intIdMuestra_t").val(intIdMuestra_t);
                            $("#tipoReport_t").val(tipoReport_t);
                            $("#strFechaIn_t").val(strFechaIn_t);
                            $("#strFechaFn_t").val(strFechaFn_t);
                            $("#intIdHuesped_t").val(intIdHuesped_t);
                            $("#intIdEstrat_t").val(intIdEstrat_t);
                            $("#intIdGuion_t").val(intIdGuion_t);
                            $("#intIdCBX_t").val(intIdCBX_t);
                            $("#intIdPeriodo_t").val(intIdPeriodo_t);
                            $("#intIdPaso_t").val(intIdPaso_t);
                            $("#strLimit_t").val("no");
                        }
                });

                function exportarErrores(){
                    
                    $("#exportarErrores").click(function(){

                        window.location.href = '<?=base_url?>exportar.php?strTablaTemp_t='+strTablaTemp_p+'&strCampoUnion_t='+strCampoUnion_p; 

                    });

                }

        });

    }  
    

    /**JDBD
    * Esta funcion realiza la traduccion por ID PREGUN de los campos que superan la longitud de texto.
    */
    function Exentos(strCampos_p){

        strExentos_t = $.ajax({
                                    url      : '<?=$url_crud?>?Exentos=si',
                                    type     : 'POST',
                                    data     : {strCampos_t : strCampos_p},
                                    global   : false,
                                    async    :false,
                                    success  : function(data) {
                                        return data;
                                    }
                                 }).responseText;

        return strExentos_t;

    }

    // Esta funcion se encarga de ejecutar el procesador de flechas
    function procesarFlechas(intIdPaso_p, intIdBase_p, strCampoUnion_p, strTablaTemp_p, intIdMuestra_p){

        $.ajax({
            url: '<?=$url_crud?>?procesoFlecha=si',
            type: 'post',
            dataType: 'json',
            data: {
                intIdPaso_t:intIdPaso_p, intIdBase_t: intIdBase_p, strCampoUnion_t: strCampoUnion_p, strTablaTemp_t: strTablaTemp_p, intIdMuestra_t: intIdMuestra_p
            },                                       
            success : function(data){
                console.log(data);
            },
            complete : function(){
                console.log('proceso de flechas iniciado');
            }
        });
    }

    /**BGCR
    * Esta funcion iguala los campos llave contra el primer campo que se empareja, esto ya que es necesario para que funcione el cargue
    */
    function controlarCampoLlave() {  


        let htmlOptions = $("#cmbColumnaD").html().replace('selected','');

        $("#selDB0").val($("#cmbColumnaD").val()).trigger('change');
        $("#selExcel0").val($("#cmbColumnaP").val()).trigger('change');



        // Eliminamos el campo que se selecciono como llave
        $('select[id^="selDB"]').each((key, value) => {
            if(value.id.split("selDB")[1] > 0){
                //Obtenemos el valor actual en caso de tener algo seleccionado
                let actualValor = $(value).val();
                // Recargamos los campos asignables de la BD
                $(value).html(htmlOptions);
                // Quitamos el campo que se selecciono como llave
                $(value).find(`option[value^="${$("#cmbColumnaD").val()}"]`).remove();

                // Reasignamos el valor anterior que se tenia
                if($(value).find(`option[value^="${actualValor}"]`).length > 0){
                    $(value).val(actualValor);
                }else{
                    $(value).val('NONE');
                }
            }
        });

        
    }
    
</script>