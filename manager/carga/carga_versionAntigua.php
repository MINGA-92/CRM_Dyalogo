<?php
   
    
    $url_crud = "carga/carga_CRUD.php";
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="cheRegistrosCargarNuevo" value="1">
                                    <?php echo $str_CargarDeNuevo;?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    <input type="radio" name="cheRegistrosCargarNuevo" value="2">Inactivar registros que NO vengan en el archivo</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    <input type="radio" checked name="cheRegistrosCargarNuevo" value="3">Sin Accion</label>
                            </div>
                        </div>
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

        /** YCR- 2019-09-01
         * \funcion ajax que carga columnas de excel y base de datos
         */
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

                                tr += '<tr>';
                                tr += '<td width="33%"><select class="form-control select_excel" numero="'+ i +'" id="selExcel'+ i +'" name="selExcel'+ i +'">'+ option +'</select><input type="hidden" name="nombreExell'+ i +'" id="nombreExell'+ i +'" value="NONE"></td>';
                                tr += '<td width="34%"><select   onclick="validar('+i+');" class="form-control " id="selDB'+ i +'" name="selDB'+ i +'">'+ htmlDeaqi +'</select></td>';
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
                                  
                var form = $("#formEnvioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#formEnvioDatos")[0]); 
                formData.append('origenDY', origenDY);   
                formData.append('strIdCampan','<?php echo $strIdCampan;?>');  
                formData.append('estadoValidacion', estadoValidacion);  


                /** YCR - 2019-08-30
                    * \funcion ajax utilizada para cargar registros  
                */
                $.ajax({
                    url: 'carga/carga_CRUD.php?llenarDatos=si'+strcampo_agente,
                    type  : 'post',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function() {
                        $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="assets/img/clock.gif" /> <?php echo $str_Carga;?>' }); 
                    },
                    success: function(data){                          
                        

                        if (data.mensaje_error ) {
                            $.unblockUI();
                            swal({
                                title : "Error al subir el archivo",
                                text  : data.mensaje_error,
                                type  : "error",
                                confirmButtonText : "<?php echo $str_Cerrar;?>"
                            });
                        }else{
                            if(data.int_code == '1'){                            
                                
                                $("#title_cargue").html('<span data-toggle="tooltip"  class="badge bg-light-blue" style="margin-right: 30px !important;" >Total Registros: '+data.int_numeroRegistros+'</span> <span data-toggle="tooltip" title="" class="badge bg-green" style="margin-right: 30px !important;" >Nuevos: '+data.int_nuevos+'</span> <span data-toggle="tooltip" title="" class="badge bg-yellow" style="margin-right: 30px !important;">Actualizados: '+data.int_existentes+'</span><span data-toggle="tooltip" title="" class="badge bg-red" >Fallaron por Validación: '+data.int_fallas_validacion+'</span>');
                                $("#fallasValidacion").val(data.int_fallas_validacion);                               

                               
                                $("#strFechaInicial_t").val(data.fechaInsercion);
                                $("#intIdCampana_t").val(data.idCampan);
                                var parametros = {
                                    "generate" : "si",
                                    "sql_query" : "22",               
                                    "intIdCampana_t" : $("#intIdCampana_t").val(),
                                    "intIdCampanCbx_t" : "0",
                                    "intPeriodicidad_t": "0",
                                    "strFechaInicial_t": $("#strFechaInicial_t").val(),
                                    "strFechaFinal_t" : $("#strFechaInicial_t").val(),
                                    "intIdHuesped_t": "",
                                    "server_ip": '<?php echo $DB_Name;?>',
                                    "username": '<?php echo $DB_User;?>',
                                    "password": '<?php echo $DB_Pass;?>',
                                    "database" : '<?php echo $BaseDatos_systema;?>'

                                };
                                /** 
                                * \funcion ajax utilizada para llamar vista de resumen de base de datos
                                */
                                $.ajax({
                                        url  :  'pages/charts/report.php',
                                        type :  'post',
                                        data : parametros,                                       
                                        success : function(data){
                                            
                                            $("#divIframe").html('');
                                            $("#divIframe").html(data);
                                            $("#cargarInformacion").modal();
                                            $('#sql_query_exp').val('21');
                                            $('#id_estrategia_exp').val($("#intIdCampana_t").val());
                                            $('#fecha_inicial_exp').val($("#strFechaInicial_t").val());
                                            $('#fecha_final_exp').val($("#strFechaInicial_t").val());
                                            $('#proyecto').val('RESUMEN CARGUE');
                                            
                                            if($("#fallasValidacion").val() > 0 ){
                                                $('#export-errores').css('display','block');
                                            }                                            


                                            // $("#resultados").html(data);
                                        },complete : function(){
                                            
                                            $.unblockUI();
                                        },
                                        //si ha ocurrido un error
                                        error: function(){
                                            mensajeError();
                                            $.unblockUI();                                            

                                        }
                                }); 
                            }else{
                                ///Algo paso, hay un error
                                $.unblockUI(); 
                                mensajeError();                               

                            } 
                        }

                    },
                    complete : function(){
                        //$.unblockUI();                            
                        
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
                url: 'cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD.php',
                type  : 'post',
                data: {'opcion':'borrarLogCargue'},
                dataType : 'json',
                success:function(data){
                    
                }
         }); 
    }
</script>