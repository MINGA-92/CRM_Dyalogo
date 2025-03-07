<?php
    $url_crud = "carga/carga_CRUD.php";
    $destinos = array();
    $validaciones_array = array();

    if(isset($_GET['muestra'])){
        if(isset($_GET['distribucion']) && $_GET['distribucion'] == 0){
            $Lsql = "UPDATE ".$BaseDatos_systema.".CAMPAN SET CAMPAN_ConfDinam_b = ".$_GET['distribucion']." WHERE CAMPAN_ConsInte__MUESTR_b = ".$_GET['muestra']." AND CAMPAN_ConsInte__GUION__Pob_b = ".$_GET['poblacion'].";";
            $res = $mysqli->query($Lsql);
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
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">
                    <?php echo $str_configuracion_carg;?>
                </h3>
            </div>
            <div class="box-body">
                <form id="formEnvioDatos" data-toggle="validator" enctype="multipart/form-data" action="#" method="post">
                    <div class="row">
                        <?php if(!isset($_GET['formaInvoaca'])){ ?>
                        <div class="col-md-2">
                        <?php }else{ ?>
                        <div class="col-md-2" style="display: none;">
                        <?php } ?>
                            <div class="form-group">
                                <label><?php echo $str_based_label;?></label>
                                <select class="form-control" id="cmbControl" name="cmbControl">
                                    <option value="0">SELECCIONE</option>
                                    <?php
                                        $Lsql = '';
                                        //$Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__PROYEC_b =".$_SESSION['HUESPED']." ORDER BY GUION__Nombre____b ASC";

                                        $Lsql = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__PROYEC_b = ".$_SESSION['HUESPED']." AND GUION__Tipo______b = 2 ORDER BY GUION__Nombre____b ASC";
                                        
                                        $result = $mysqli->query($Lsql);
                                        while($key = $result->fetch_object()){
                                            echo '<option value= "'.$key->GUION__ConsInte__b.'">'.utf8_encode($key->GUION__Nombre____b).'</option>';
                                        } 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php 
                            if(isset($_GET['muestra'])){
                                echo "<input type='hidden' name='muestraAInsertar' id='muestraAInsertar' value='".$_GET['muestra']."'>";
                            }else{
                                echo "<input type='hidden' name='muestraAInsertar' id='muestraAInsertar' value='0'>";
                            }
                        ?>
                        
                       
                        <?php if(!isset($_GET['formaInvoaca'])){ ?>
                        <div class="col-md-10">
                        <?php }else{ ?>
                        <div class="col-md-12">
                        <?php } ?>

                            <div class="form-group">
                                <label><?php echo $str_carga_label;?></label>
                                <input type="file" name="arcExcell" disabled="true" id="arcExcell" class="form-control">
                                <input type="hidden" name="NombrearcExcell"  id="NombrearcExcell" >
                                <input type="hidden" name="NombrearcExcell_BD"  id="NombrearcExcell__BD" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $str_colunm_prinD;?></label>
                                <select class="form-control" disabled="true" id="cmbColumnaD" name="cmbColumnaD">
                                    <option value="0"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
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
                                                echo '<option value="'.$i.'">'.utf8_encode($key->SIEXIN_CampOrig__b).'</option>';
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
                                                echo '<option value="'.$i.'">'.utf8_encode($key->SIEXIN_CampOrig__b).'</option>';
                                                $i++;
                                            } 
                                        }
                                    ?>
                                </select>
                                <input type="hidden" name="txtLlaveExcell" id="txtLlaveExcell" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $str_tipo_accion;?></label>
                                <select class="form-control" disabled="true" id="cmbAction" name="cmbAction">
                                    <option value="3"><?php echo $str_ambas;?></option>
                                    <option value="1"><?php echo $str_insertar; ?></option>
                                    <option value="2"><?php echo $str_actualizar; ?></option>
                                    <!--<option value="4"><?php echo $str_ambas_borrar; ?></option>-->
                                </select>
                            </div>
                        </div>
                        <?php if(isset($_GET['distribucion']) && $_GET['distribucion'] == 0){ ?>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-top: 10px; text-align: center;">
                                    <div>
                                    <input type="radio" onclick="TipoAsignacion()" name="tipoasignacion" value="1" id="checkAutomatico" checked>
                                    <label for="checkAutomatico">Asignacion Automatica</label>
                                    </div>
                                    <div style="margin-right: 18px;">
                                    <input type="radio" onclick="TipoAsignacion()" name="tipoasignacion" value="2" id="checkManual">
                                    <label for="checkManual">Asignacion Definida</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                                    echo '<option value="'.$i.'">'.utf8_encode($key->SIEXIN_CampOrig__b).'</option>';
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
                                                    echo '<option value="'.$i.'">'.utf8_encode($key->SIEXIN_CampOrig__b).'</option>';
                                                    $i++;
                                                } 
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
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
                                <?php 
                                    $totales = 0;
                                    if(isset($_GET['poblacion'])){
                                        if($res_Siexin->num_rows > 0){

                                            $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET['poblacion']." ORDER BY PREGUN_OrdePreg__b";

                                            $campos_contador = $mysqli->query($LsqlDetalle);
                                            $totales = $campos_contador->num_rows;

                                            for($i = 0; $i < $campos_contador->num_rows; $i++){
                                ?>
                                    <tr>
                                        <td width="33%">
                                            <select class="form-control select_excel" numero="<?php echo $i;?>" id="selExcel<?php echo $i;?>" name="selExcel<?php echo $i;?>">
                                                <option value="NONE">SELECCIONE</option>
                                                <?php
                                                    $Lsq_Siexin = "SELECT  SIEXIN_CampOrig__b, SIEXIN_CampDest__b FROM ".$BaseDatos_systema.".SIEXIN JOIN   ".$BaseDatos_systema.".SIDAEX ON SIEXIN_ConsInte__SIDAEX_b = SIDAEX_ConsInte__b WHERE SIDAEX_Destino___b = ".$_GET['poblacion'];
                                                        $nombreCampo = 'NONE';
                                                        $res_Siexin2 = $mysqli->query($Lsq_Siexin);
                                                        $x = 0;

                                                        while ($key2 = $res_Siexin2->fetch_object()) {
                                                            if($x == $i){
                                                                $nombreCampo = ($key2->SIEXIN_CampOrig__b);
                                                                echo '<option value="'.$x.'" selected>'.($key2->SIEXIN_CampOrig__b).'</option>';
                                                            }else{
                                                                echo '<option value="'.$x.'">'.($key2->SIEXIN_CampOrig__b).'</option>';
                                                            }
                                                            
                                                            $x++;
                                                        } 
                                                ?>
                                            </select>
                                            <input type="hidden" name="nombreExell<?php echo $i;?>" id="nombreExell<?php echo $i;?>" value="<?php echo $nombreCampo; ?>">    
                                        </td>
                                        <td width="34%">
                                            <select class="form-control" id="selDB<?php echo $i;?>" name="selDB<?php echo $i;?>">
                                                <option value="NONE">SELECCIONE</option>
                                                <?php
                                                    $LsqlDetalle = "SELECT PREGUN_Texto_____b as titulo_pregunta , PREGUN_Tipo______b as tipo_Pregunta, PREGUN_ConsInte__b as id , PREGUN_ConsInte__GUION__PRE_B as guion, PREGUN_ConsInte__OPCION_B as lista, PREGUN_OrdePreg__b , PREGUN_NumeMini__b as minimoNumero, PREGUN_NumeMaxi__b as maximoNumero, PREGUN_FechMini__b as fechaMinimo, PREGUN_FechMaxi__b as fechaMaximo , PREGUN_HoraMini__b as horaMini , PREGUN_HoraMaxi__b as horaMaximo, PREGUN_TexErrVal_b as error FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET['poblacion']." ORDER BY PREGUN_OrdePreg__b";

                                                    $campos = $mysqli->query($LsqlDetalle);
                                                    echo "<option value=\"NONE\">SELECCIONE</option>";
                                                    while ($key3 = $campos->fetch_object()){
                                                        if($key3->tipo_Pregunta != '9'){
                                                            if(isset($destinos[$i]) && $key3->id == $destinos[$i]){
                                                                echo "<option value='".$key3->id."' selected>".utf8_encode($key3->titulo_pregunta)."</option>";    
                                                            }else{
                                                                echo "<option value='".$key3->id."'>".utf8_encode($key3->titulo_pregunta)."</option>";
                                                            }
                                                            
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td width="33%">
                                            <select class="form-control" id="valDB<?php echo $i;?>" name="valDB<?php echo $i;?>">
                                                <option value='1'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 1){ echo "selected"; } } ?>><?php echo $str_validacion_1;?></option>
                                                <option value='2'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 2){ echo "selected"; } }?>><?php echo $str_validacion_2;?></option>
                                                <option value='3'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 3){ echo "selected"; } }?>><?php echo $str_validacion_3;?></option>
                                                <option value='4'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 4){ echo "selected"; } }?>><?php echo $str_validacion_4;?></option>
                                                <option value='5'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 5){ echo "selected"; } }?>><?php echo $str_validacion_5;?></option>
                                                <option value='6'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 6){ echo "selected"; } }?>><?php echo $str_validacion_6;?></option>
                                                <option value='7'  <?php if(count($validaciones_array) > $i) { if($validaciones_array[$i] == 7){ echo "selected"; } }?>><?php echo $str_validacion_7;?></option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php
                                            }
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                            <div class="row"> 
                                <div class="col-md-10">
                                    <button id="click_Exportar" style="display: none;" type="button" >Exportar datos Csv</button>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="totales" id="totales" value="<?php echo $totales; ?>">
                                        <input type="hidden" name="pasoId" id='pasoId' value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo '0';}?>">
                                        <input type="hidden" name="sincronizar" id='sincronizar' value="<?php if(isset($_GET['sincronizar'])){ echo $_GET['sincronizar']; }else{ echo '0';}?>">
                                        <button type="button" disabled name="btnExcell" id="btnExcell" class="btn btn-primary btn-block" ><i class="fa fa-save"></i>&nbsp; <?php echo $str_button_label;?></button>
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

        $("#click_Exportar").click(function(){
            window.location.href = 'exportar.php';
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
                    htmlDeaqi = data;
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
                    htmlDeaqi = data;
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
                    htmlDeaqi = data;
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

        $('#arcExcell').on('change', function(e){
            /* primero validar que sea solo excel */
            var imagen = this.files[0];
            //console.log(imagen);
            if(imagen['type'] != "application/vnd.ms-excel" && imagen['type'] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ){
                $("#arcExcell").val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo debe estar en formato XLS o XLSX",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }else if(imagen['size'] > 5000000 ) {
                $("#arcExcell").val('');
                swal({
                    title : "Error al subir el archivo",
                    text  : "El archivo no debe pesar mas de 2MB",
                    type  : "error",
                    confirmButtonText : "Cerrar"
                });
            }else{
                $("#NombrearcExcell").val(imagen.name);
                if($("#NombrearcExcell__BD").val().length > 1){
                    if($("#NombrearcExcell").val() != $("#NombrearcExcell__BD").val()){
                        tiene_configuracion = false;
                    }
                }

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

                        var total = data.total;
                        $("#totales").val(total);
                        var tr = '';
                        var option = '<option value="NONE">SELECCIONE</option>';
                        for(i = 0; i < total; i++){
                            option += '<option value="'+ i +'">'+ data.opciones[i]['Nombres'] +'</option>';
                        }
                        var valcmbColumnaP = $("#cmbColumnaP").val();
                        $("#cmbColumnaP").html(option);
                        $("#cmbColumnaP").val(valcmbColumnaP);
                        $("#cmbAgent").html(option);
                        $("#cmbColumnaP").attr('disabled', false);
                        $("#cmbColumnaP").change(function(){
                            var combo = document.getElementById("cmbColumnaP");
                            var selected = combo.options[combo.selectedIndex].text;
                            $("#txtLlaveExcell").val(selected);
                        });
                        
                        if(!tiene_configuracion){

                            var option_validacion = '<option value=\'1\'><?php echo $str_validacion_1;?></option>';
                            option_validacion += '<option value=\'2\'><?php echo $str_validacion_2;?></option>';
                            option_validacion += '<option value=\'3\'><?php echo $str_validacion_3;?></option>';
                            option_validacion += '<option value=\'4\'><?php echo $str_validacion_4;?></option>';
                            option_validacion += '<option value=\'5\'><?php echo $str_validacion_5;?></option>';
                            option_validacion += '<option value=\'6\'><?php echo $str_validacion_6;?></option>';
                            option_validacion += '<option value=\'7\'><?php echo $str_validacion_7;?></option>';

                            for(i = 0; i < total; i++){

                                tr += '<tr>';
                                tr += '<td width="33%"><select class="form-control select_excel" numero="'+ i +'" id="selExcel'+ i +'" name="selExcel'+ i +'">'+ option +'</select><input type="hidden" name="nombreExell'+ i +'" id="nombreExell'+ i +'" value="NONE"></td>';
                                tr += '<td width="34%"><select class="form-control" id="selDB'+ i +'" name="selDB'+ i +'">'+ htmlDeaqi +'</select></td>';
                                tr += '<td width="33%"><select class="form-control" id="valDB'+ i +'" name="valDB'+ i +'">'+ option_validacion +'</select></td>';
                                tr += '</tr>';

                            }

                            $("#curpo_tabla_validaciones").html(tr);

                            $(".select_excel").change(function(){

                                var combo = document.getElementById('selExcel'+ $(this).attr('numero'));
                                var selected = combo.options[combo.selectedIndex].text;
                                $("#nombreExell"+$(this).attr('numero')).val(selected);
                            });

                        }

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


        $("#btnExcell").click(function(){
            var valido = 0;
            var strcampo_agente = '';
            if($('#checkManual').is(':checked') && $('#cmbAgent').val() == 'NONE'){
                alertify.error("Debes seleccionar la columna de agente");
                valido = 1;
            }
            if(valido == 0){
                var form = $("#formEnvioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#formEnvioDatos")[0]);
                $.ajax({
                    url: 'carga/carga_CRUD.php?llenarDatos=si'+strcampo_agente,
                    type  : 'post',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function() {
                       $.blockUI({ baseZ: 2000, message: "<?php echo $str_message_wait  ;?>" });
                    },
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data.int_code == '1'){
                            alertify.success("El total de registros recorridos es "+ data.int_numeroRegistros +
                                " , se guardaron "+ data.int_exitos + " , la cantidad de registros nuevos es "+ data.int_nuevos +
                                " , existentes " + data.int_existentes + " , Fallaron por validaciones "+ data.int_fallas_validacion );   

                            if(data.int_fallas > 0){
                                console.log(data.int_falla);;
                                $("#click_Exportar").click();
                            }

                            if(data.int_fallas_validacion > 0){
                                console.log(data.int_fallas_validacion);
                                $("#click_Exportar").click();
                            }

                        }else{
                            //Algo paso, hay un error
                            alertify.error('<?php echo $error_de_proceso;?>');
                        }                
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    //si ha ocurrido un error
                    error: function(){
                        //after_save_error();
                        alertify.error('<?php echo $error_de_red;?>');
                    }
                });
            }
        });
    });
    function TipoAsignacion(){
        if($('#checkManual').is(':checked')){
            $('#cmbAgent').attr('disabled', false);
        }else{
            $('#cmbAgent').attr('disabled', true);
        }
    }
</script>