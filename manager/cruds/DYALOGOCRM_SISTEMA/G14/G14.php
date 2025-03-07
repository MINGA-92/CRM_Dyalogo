
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

    .modal.in .modal-dialog {
        -webkit-transform: none;
        -ms-transform: none;
        -o-transform: none;
        transform: none;
    }
</style>


<?php
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G14/G14_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN


    $Zsql = "SELECT G14_ConsInte__b as id, G14_C137 as camp1 , G14_C138 as camp2 FROM ".$BaseDatos_systema.".G14  ORDER BY G14_C137 DESC LIMIT 0, 50";
    

   $result = $mysqli->query($Zsql);

    function sanear_strings($string) { 

       // $string = utf8_decode($string);

        $string = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string );
        $string = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string ); 
        $string = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string ); 
        $string = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string ); 
        $string = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string ); 
        $string = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string ); 
        //Esta parte se encarga de eliminar cualquier caracter extraño 
        $string = str_replace( array("\\", "¨", "º", "-", "~", "#", "@", "|", "!", "\"", "·", "$", "%", "&", "/", "(", ")", "?", "'", "¡", "¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´", ">“, “< ", ";", ",", ":", "."), '', $string ); 
        return $string; 
    }
?>

<?php if(!isset($_GET['view'])){ ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php  echo $str_title_email; ?>
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
                                <div class="row">
                                    <div class="col-md-9">

                                        <div id="18">
                                            <input type="hidden" name="G14_C136" id="G14_C136" value="<?php if(isset($_GET['id_paso'])){ echo  $_GET['id_paso']; }else{ echo "0"; } ?>">
                                            <!-- <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <?php 
                                                    //$str_Lsql = "SELECT  G3_ConsInte__b as id , G3_C15 FROM ".$BaseDatos_systema.".G3";
                                                    ?>
                                                     CAMPO DE TIPO GUION 
                                                    <div class="form-group">
                                                        <label for="G14_C136" id="LblG14_C136">PASO</label>
                                                        <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G14_C136" id="G14_C136">
                                                            <option>NOMBRE</option>
                                                            <?php
                                                                /*
                                                                    SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                                                */
                                                                //$combo = $mysqli->query($str_Lsql);
                                                                /*while($obj = $combo->fetch_object()){
                                                                    echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G3_C15)."</option>";

                                                                }   ¨*/ 
                                                                
                                                            ?>
                                                        </select>
                                                    </div>
                                                 FIN DEL CAMPO TIPO LISTA 
                                                </div>
                                            </div> -->
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12 row">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="col-md-11">
                                                        <label for="G14_C137" id="LblG14_C137"><?php echo $str_nombre_mail_ms; ?></label>
                                                        <input type="text" class="form-control input-sm" id="G14_C137" value=""  name="G14_C137"  placeholder="<?php echo $str_nombre_mail_ms; ?>" required>
                                                    </div>
                                                <div class="col-md-1 col-xs-1">
                                                    <div class="form-group">
                                                        <label for="pasoActivo" id="LblpasoActivo">ACTIVO</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="-1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself" checked> 
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>
                                            <div class="row" hidden>
                                                <div class="col-md-12 col-xs-12">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="form-group">
                                                        <label for="G14_C145" id="LblG14_C138"><?php echo $str_cuentF_mail_ms; ?></label>
                                                        <select class="form-control input-sm" id="G14_C145" value=""  name="G14_C145"  placeholder="CUENTA A USAR">
                                                            <option value="0"><?php echo $str_fija___mail_ms; ?></option>
                                                            <!--<option value="1"><?php echo $str_agente_mail_ms; ?></option>-->
                                                        </select>
                                                    </div>
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="form-group">
                                                        <label for="G14_C138" id="LblG14_C138"><?php echo $str_cuentU_mail_ms; ?></label>
                                                        <select class="form-control input-sm" style="width: 100%;" id="G14_C138" value=""  name="G14_C138">
                                                            <?php
                                                                $Lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_configuracion WHERE id_huesped=".$_SESSION['HUESPED'];
                                                                $cur_result = $mysqli->query($Lsql);
                                                                while ($key = $cur_result->fetch_object()) {
                                                                    echo "<option value='".$key->id."'>".$key->direccion_correo_electronico."</option>";
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="form-group">
                                                        <label for="G14_C139" id="LblG14_C139"><?php echo $str_para___mail_ms;?></label>
                                                        <input type="text" class="form-control input-sm" id="G14_C139" value=""  name="G14_C139"  placeholder="<?php echo $str_para___mail_ms;?>">
                                                    </div>
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="form-group">
                                                        <label for="G14_C140" id="LblG14_C140"><?php echo $str_cc_____mail_ms;?></label>
                                                        <input type="text" class="form-control input-sm" id="G14_C140" value=""  name="G14_C140"  placeholder="<?php echo $str_cc_____mail_ms;?>">
                                                    </div>
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="form-group">
                                                        <label for="G14_C141" id="LblG14_C141"><?php echo $str_cco____mail_ms;?></label>
                                                        <input type="text" class="form-control input-sm" id="G14_C141" value=""  name="G14_C141"  placeholder="<?php echo $str_cco____mail_ms;?>">
                                                    </div>
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="form-group">
                                                        <label for="G14_C142" id="LblG14_C142"><?php echo $str_asunto_mail_ms;?></label>
                                                        <input type="text" class="form-control input-sm" id="G14_C142" value=""  name="G14_C142"  placeholder="<?php echo $str_asunto_mail_ms;?>">
                                                    </div>
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    <div class="form-group">
                                                        <label for="dyTr_G14_C143" id="LbldyTr_G14_C143"><?php echo $str_cuerpo_mail_ms;?></label>
                                                        <textarea class="form-control input-sm" id="dyTr_G14_C143" name="dyTr_G14_C143"  placeholder="<?php echo $str_cuerpo_mail_ms;?>"><?php if(isset($_GET['modocampana'])){ echo "Por favor diligencie el formulario dando click <a href='http://".$_SERVER['HTTP_HOST']."/crm_php/web_forms.php?web=".base64_encode($_GET['id_formulario'])."&camp=".base64_encode($_GET['modocampana'])."&u=\${ID_REGISTRO}'>aquí</a>"; } ?></textarea>
                                                    </div>
                                                    <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <label for="">Imagenes del huesped</label>
                                                    <table class="table" id="tablaImagenes">
                                                        <thead>
                                                            <tr>
                                                                <th>Ruta imagen </th>
                                                                <th>Accion</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 col-xs-12">
                                                    <table class="table" id="tablaAdjuntos">
                                                        <thead>
                                                            <tr>
                                                                <th>Adjunto</th>
                                                                <th>Accion</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                    <!-- CAMPO TIPO TEXTO -->
                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="G14_C144" id="LblG14_C144"><?php echo $str_adjunt_mail_ms;?></label>
                                                            <input type="file" onchange="validarArchivo()" class="form-control input-sm" id="adjunto" value=""  name="adjunto" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <button type="button" id="cargarAdjunto" onclick="fileUpload()" class="btn btn-primary btn-sm">Cargar adjunto</button>
                                                    </div>
                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <table class="table-bordered table-hover table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <?php 
                                                            $Lsql = "SELECT GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$_GET['poblacion'];
                                                            $cur_result = $mysqli->query($Lsql);
                                                            $res_Lsql = $cur_result->fetch_array();
                                                            echo "VARIABLES ".$res_Lsql['GUION__Nombre____b'];
                                                        ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $Lsql = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE SECCIO_TipoSecc__b = 1 AND PREGUN_ConsInte__GUION__b = ".$_GET['poblacion'];
                                                    $cur_result = $mysqli->query($Lsql);
                                                    while ($key = $cur_result->fetch_object()) {
                                                        $titulodeLapregunta = $key->PREGUN_Texto_____b;
                                                        $titulodeLapregunta = sanear_strings($titulodeLapregunta);
                                                        $titulodeLapregunta = str_replace(' ', '_', $titulodeLapregunta);
                                                        $titulodeLapregunta = substr($titulodeLapregunta, 0 , 20);

                                                        echo '<tr>';
                                                        echo '  <td>';
                                                        echo "\${".$titulodeLapregunta."}";
                                                        echo '  </td>';
                                                        echo '</tr>';
                                                    }
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${ID_REGISTRO}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${LinkContenidoUG}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${LinkContenidoGMI}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${Comentario_Ultima_Gestion}";
                                                    echo '  </td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                    echo '  <td>';
                                                    echo "\${Comentario_Gestión_Mas_Importante}";
                                                    echo '  </td>';
                                                    echo '</tr>';

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- SECCION : PAGINAS INCLUIDAS -->
                                <input type="hidden" name="id" id="hidId" value='0'>
                                <input type="hidden" name="ruta_Adjuntos" id="ruta_Adjuntos" value='0'>
                                
                                <input type="hidden" name="oper" id="oper" value='add'>
                                <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                                <input type="hidden" name="huesped" id="huesped" value="<?php if(isset($_SESSION['HUESPED'])){ echo $_SESSION['HUESPED']; }else{ echo "0"; }?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsePrueba">Pruebas</a>
                </h4>
            </div>
            <div class="box-body">
                <div id="collapsePrueba" class="panel-collapse collapse">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Correo de destino</label>
                                <input type="text" class="form-control input-sm" id="correoDestino" placeholder="asdf@mail.com">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-info" onclick="ejecutarPrueba()">Ejecutar prueba</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box para reportes -->
        <div class="panel box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="" href="#reports" aria-expanded="false" class="collapsed">
                        REPORTES
                    </a>
                </h4>
            </div>
            <div id="reports" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                <div style="padding: 15px;">
                    <div class="row">
                        <div class="col-md-12">
                            <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=red&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>

            </div>
        </div>


<?php if(!isset($_GET['view'])){ ?>
    </section>
</div>
<?php } ?>

<script src="<?=base_url?>assets/plugins/tiny/tinymce.min.js"></script>
<script src="<?=base_url?>assets/plugins/tiny/tinymce_languages/langs/es.js"></script>

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

            <?php if(isset($_GET['id_paso'])){ ?>
                    $("#editarDatos").modal('hide');
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

<script type="text/javascript" src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G14/G14_eventos.js"></script> 
<script type="text/javascript">

    $(function(){

        $("#G14_C138").select2();

        $("#G14_C145").change(function(){
            if($(this).val() == '1'){
                $("#G14_C138").prop('disabled', true);
            }else{
                $("#G14_C138").prop('disabled', false);
            }
        });

        <?php if(isset($_GET['id_paso'])){ ?>
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatos_paso : 'SI', id_paso : <?php echo $_GET['id_paso']; ?>, poblacion: <?php echo $_GET['poblacion']; ?> },
            dataType : 'json',
            success  : function(data){
                if(data.length > 0){
                    //recorrer datos y enviarlos al formulario
                    $.each(data, function(i, item) {


                    
                        $("#G14_C137").val(item.G14_C137);
                        
                        if(item.pasoActivo != '-1'){
                            $('#pasoActivo').prop('checked',false);
                        }                        

                        $("#G14_C138").val(item.G14_C138);

                        $("#G14_C138").val(item.G14_C138).trigger("change");

                        $("#G14_C139").val(item.G14_C139);

                        $("#G14_C140").val(item.G14_C140);

                        $("#G14_C141").val(item.G14_C141);

                        $("#G14_C142").val(item.G14_C142);

                        $("#dyTr_G14_C143").val(item.dyTr_G14_C143);

                        tinymce.get("dyTr_G14_C143").setContent(item.dyTr_G14_C143);

                        $("#G14_C144").val(item.G14_C144);

                        $("#G14_C145").val(item.G14_C145);

                        $("#G14_C145").val(item.G14_C145).trigger("change");

                        $("#h3mio").html(item.principal);

                        $("#hidId").val(item.G14_ConsInte__b);

                        $("#oper").val('edit');

                        $("#TxtFechaReintento").attr('disabled', true);

                        $("#TxtHoraReintento").attr('disabled', true); 

                        recargarAdjuntos(item.G14_C144);

                        vamosRecargaLasGrillasPorfavor(item.G14_ConsInte__b);
                   
                    });
                }else{
                    $("#G14_C137").val("MAIL_SALIENTE_"+<?php echo $_GET['id_paso']; ?>);
                }
                
                //Deshabilitar los campos
            } 

        });


        
                        
        <?php } ?>

        //str_Select2 estos son los guiones
        


        
                                      

        //datepickers
        

        //Timepickers
        


        //Validaciones numeros Enteros
        


        //Validaciones numeros Decimales
       

        
        
        $("#btnLlamadorAvanzado").click(function(){
            
            $("#resultadosBusqueda").html('');
        });

        $("#btnBuscar").click(function(){
            
            $.ajax({
                url         : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G14/G14_Funciones_Busqueda_Manual.php?action=GET_DATOS',
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
                            tabla_a_mostrar += '<tr ConsInte="'+ item.G14_ConsInte__b +'" class="EditRegistro">';
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
                        var id = datosq[0].registros[0].G14_ConsInte__b;

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
            var bol_respuesta = before_save();
            if($("#G14_C137").val() == ''){
                bol_respuesta=false;
                alertify.error('el campo nombre es obligatorio');
            }

            tinymce.triggerSave();

            if(bol_respuesta){

                var form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#FormularioDatos")[0]);
                $.ajax({
                    url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&poblacion=<?php echo $_GET['poblacion'] ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data){

                            
                            // GENERAMOS LA VISTA DE LA MUESTRA
                            $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaMuestra=si',
                                type: 'POST',
                                data: { pasoId :  '<?php echo $_GET['id_paso']; ?>' },
                                dataType:'JSON'
                            });

                            <?php if(!isset($_GET['campan'])){ ?>
                                //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                if($("#oper").val() == 'add'){
                                    idTotal = data;
                                }else{
                                    idTotal= $("#hidId").val();
                                }
                                $(".modalOculto").hide();

                                <?php if(isset($_GET['id_paso'])){ ?>
                                        $("#editarDatos").modal('hide');
                                <?php }  ?>

                                <?php if(isset($_GET['modocampana'])){  ?>
                                        getCampanasEnviar()
                                <?php } ?>
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
 
                                            $("#G14_C137").val(item.G14_C137);

                                            $("#G14_C138").val(item.G14_C138);

                                            $("#G14_C138").val(item.G14_C138).trigger("change");
 
                                            $("#G14_C139").val(item.G14_C139);
 
                                            $("#G14_C140").val(item.G14_C140);
 
                                            $("#G14_C141").val(item.G14_C141);
 
                                            $("#G14_C142").val(item.G14_C142);
 
                                            $("#dyTr_G14_C143").val(item.dyTr_G14_C143);
 
                                            $("#G14_C144").val(item.G14_C144);

                                            $("#G14_C145").val(item.G14_C145);

                                            $("#G14_C145").val(item.G14_C145).trigger("change");

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

                // $.ajax({
                //    url: 'http://old-customers.dyalogodev.com:8080/dyalogocbx/api/uploadFile',  
                //     type: 'POST',
                //     data: formData,
                //     cache: false,
                //     contentType: false,
                //     processData: false,
                //     //una vez finalizado correctamente
                //     success: function(data){
                //         $("#ruta_Adjuntos").val(data);  
                //         $.ajax({
                //            url: '<?=$url_crud;?>?insertarDatosGrilla=si',  
                //             type: 'POST',
                //             data: {ruta_Adjuntos :  $("#ruta_Adjuntos").val() , id : $("#hidId").val() , oper : 'edit' },
                //             cache: false,
                //             contentType: false,
                //             processData: false,
                //             //una vez finalizado correctamente
                //             success: function(data){
          
                //             }
                //         });          
                //     }
                // });
            }
        });
    });

    traerImagenes();

    inicializarTinymce();

    // Prevent Bootstrap dialog from blocking focusin
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length) {
            e.stopImmediatePropagation();
        }
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
                        

                        $("#G14_C136").val(item.G14_C136);
 
                        $("#G14_C136").val(item.G14_C136).trigger("change"); 

                        $("#G14_C137").val(item.G14_C137);

                        $("#G14_C138").val(item.G14_C138);

                        $("#G14_C138").val(item.G14_C138).trigger("change");

                        $("#G14_C139").val(item.G14_C139);

                        $("#G14_C140").val(item.G14_C140);

                        $("#G14_C141").val(item.G14_C141);

                        $("#G14_C142").val(item.G14_C142);

                        $("#dyTr_G14_C143").val(item.dyTr_G14_C143);

                        tinymce.get("dyTr_G14_C143").setContent(item.dyTr_G14_C143);

                        $("#G14_C144").val(item.G14_C144);

                        $("#G14_C145").val(item.G14_C145);

                        $("#G14_C145").val(item.G14_C145).trigger("change");

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
                        

                        $("#G14_C136").val(item.G14_C136);
 
                        $("#G14_C136").val(item.G14_C136).trigger("change"); 

                        $("#G14_C137").val(item.G14_C137);

                        $("#G14_C138").val(item.G14_C138);

                        $("#G14_C139").val(item.G14_C139);

                        $("#G14_C140").val(item.G14_C140);

                        $("#G14_C141").val(item.G14_C141);

                        $("#G14_C142").val(item.G14_C142);

                        $("#dyTr_G14_C143").val(item.dyTr_G14_C143);

                        $("#G14_C144").val(item.G14_C144);
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
        
    }

    function fileUpload(){

        if($('#adjunto')[0].files.length == 0){
            alertify.warning("No hay un archivo para subir");
            return;
        }

        if(alcanzaLimiteAdjuntos()){
            return;
        }

        let formData = new FormData($("#FormularioDatos")[0]);

        $.ajax({
            url: '<?=$url_crud;?>?fileUpload=true',
            type: 'POST',
            data: formData,
            dataType : 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                if(data.estado == 'ok'){
                    alertify.success(data.mensaje);
                    recargarAdjuntos(data.adjuntos);
                    document.getElementById('adjunto').value = '';
                }else{
                    alertify.error(data.mensaje);
                }
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

    function validarArchivo(){
        var fileSize = $('#adjunto')[0].files[0].size;
        var maxSize = 5 * 1024 * 1024;
        if(fileSize > maxSize){
            document.getElementById('adjunto').value = '';
            alertify.warning('El archivo no puede pesar más de 5MB');
        }
    }

    function alcanzaLimiteAdjuntos(){
        var nFilas = $("#tablaAdjuntos tr").length;
        if(nFilas > 10){
            alertify.warning('Se ha alcanzado el limite máximo de adjuntos');
            return true;
        }else{
            return false;
        }
    }

    function recargarAdjuntos(adjuntos){
        console.log('Cargando adjuntos');

        if(adjuntos === null || adjuntos == ''){
            $("#tablaAdjuntos tbody").html('');
            return;
        }

        let arrAdjunto = adjuntos.split(",");

        let adj = '';

        arrAdjunto.forEach(element => {
            let onlyName = element.replace("/home/dyalogo/adjuntos/", "");

            adj += `
            <tr>
                <td><a href="download_file.php?file=${onlyName}" target="_blank">${onlyName}</a></td>
                <td><button type="button" onclick="deleteAdjunto('${onlyName}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
            </tr>
            `;
        });

        $("#tablaAdjuntos tbody").html(adj);
    }

    function deleteAdjunto(nombre){
        let id = $("#hidId").val();

        $.ajax({
            url: '<?=$url_crud;?>?deleteFile=true',
            type: 'POST',
            data: {id: id, nombre: nombre},
            dataType : 'json',
            cache: false,
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                if(data.estado == 'ok'){
                    alertify.success(data.mensaje);
                    recargarAdjuntos(data.adjuntos);
                }else{
                    alertify.error(data.mensaje);
                }
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

    function inicializarTinymce(){
        tinymce.init({
            selector: 'textarea',
            skin: 'small',
            plugins: 'emoticons image code fullscreen imagetools table preview help',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            language: 'es',
            branding: false,
            images_upload_url: '<?=base_url?>upload.php',
            relative_urls : false,
            remove_script_host : false,
            images_upload_handler: function(blobInfo, success, failure){
                var xhr, formData;

                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '<?=base_url?>upload.php');

                xhr.onload = function() {
                    var json;

                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status + ' ' + xhr.responseText);
                        return;
                    }

                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }

                    success(json.location);
                    vincularImagen(json.name);
                };

                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            }
        });
    }

    function vincularImagen(imagen){
        
        let huesped = $("#huesped").val();

        $.ajax({
            url: '<?=$url_crud;?>?vincularImagen=true',
            type: 'POST',
            data: {imagen: imagen, huesped: huesped},
            dataType : 'json',
            beforeSend : function(){
                // $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                if(data.estado){
                    console.log(data);
                    alertify.success('Imagen cargada');
                    recargarImagenes(data.imagenesHuesped);
                }else{
                    alertify.error('No se pudo vincular la imagen cargada al huesped');
                }
            },
            complete : function(){
                // $.unblockUI();
            }
        });
    }

    function traerImagenes(){
        
        let huesped = $("#huesped").val();

        $.ajax({
            url: '<?=$url_crud;?>?traerImagenes=true',
            type: 'POST',
            data: {huesped: huesped},
            dataType : 'json',
            beforeSend : function(){
                // $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                if(data.estado){
                    recargarImagenes(data.imagenesHuesped);
                }else{
                    alertify.error('No se pudo obtener la lista de imagenes del huesped');
                }
            },
            complete : function(){
                // $.unblockUI();
            }
        });
    }

    function recargarImagenes(imagenes){

        let body = '';

        let url = window.location;
        let baseUrl = url.protocol + "//" + url.host;

        if(imagenes.length > 0){

            imagenes.forEach(element => {
                
                let ruta = `${baseUrl}/manager/getFile.php?img=1&name=${element.nombre}`;

                body += `
                <tr>
                    <td><a href="${ruta}" target="_blank">${ruta}</a></td>
                    <td>
                        <button type="button" onclick="borrarImagen(${element.id})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `
            });

        }

        $("#tablaImagenes tbody").html(body);
    }

    function borrarImagen(imagen){

        let huesped = $("#huesped").val();

        $.ajax({
            url: '<?=$url_crud;?>?eliminarImagen=true',
            type: 'POST',
            data: {imagen: imagen, huesped: huesped},
            dataType : 'json',
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                if(data.estado){
                    recargarImagenes(data.imagenesHuesped);
                }else{
                    alertify.error('No se pudo obtener la lista de imagenes del huesped');
                }
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

    function ejecutarPrueba(){

        if($("#correoDestino").val() == ''){
            $("#correoDestino").focus();
            alertify.error("El campo correo de destino no puede estar vacío");
            return;
        }

        let validEmail =  /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

        if(!validEmail.test($("#correoDestino").val())){
            $("#correoDestino").focus();
            alertify.error("El campo correo de destino no es valido");
            return;
        }

        guardarPasoEjecutarPrueba();
    }

    function guardarPasoEjecutarPrueba(){

        let bol_respuesta = true;

        if($("#G14_C137").val() == ''){
            bol_respuesta=false;
            alertify.error('el campo nombre es obligatorio');
        }

        tinymce.triggerSave();

        if(bol_respuesta){

            let formData = new FormData($("#FormularioDatos")[0]);

            $.ajax({
                url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&poblacion=<?php echo $_GET['poblacion'] ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend : function(){
                    $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> Guardando configuracion del paso actual' });
                },
                //una vez finalizado correctamente
                success: function(data){
                    if(data){
                        
                        // Como permanecemos en la vista sea nuevo o viejo seteamos los valores como si fuera viejo
                        $("#oper").val('edit');
                        // TODO: MODIFICAR PARA QUE RECIBA UN JSON
                        $("#hidId").val(data);

                        //Limpiar formulario
                        // form[0].reset();

                        enviarTest();
                    }else{
                        //Algo paso, hay un error
                        alertify.error('Un error ha ocurrido al guardar el formulario');
                        $.unblockUI();
                    }                
                },
                //si ha ocurrido un error
                error: function(){
                    after_save_error();
                    alertify.error('Ocurrio un error al momento de guardar el paso');
                    $.unblockUI();
                }
            });

        }

    }

    function enviarTest(){

        let correo = $("#correoDestino").val();
        let correoId = $("#hidId").val();
        let pob = '<?php echo $_GET['poblacion'] ?>';

        $.ajax({
            url: '<?=$url_crud;?>?realizarPruebaPaso=true',
            type: 'POST',
            dataType: 'JSON',
            data:{ correo, correoId, pob },
            beforeSend: function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> Ejecutando prueba de envio de correo' });
            },
            success: function(data){
                $.unblockUI();

                let texto = '';
                let titulo = 'Prueba realizada';

                if(data.estado == 'ok'){
                    // si la ejecucion es correcta 
                    if(data.muestra.estado == 3 && data.correoSaliente !== null){
    
                        texto = `
                            <p>Prueba ejecutada con exito</p>
                            <p>Respuesta de ejecucion ${data.respuesta}</p>
                            <p>Estado del registro ${data.muestra.estado}</p>
                            <p>Fecha ${data.correoSaliente.fecha_hora}</p>
                            <p>Respuesta ${data.correoSaliente.respuesta}</p>
                            <p>Destino ${data.correoSaliente.email_destino_para}</p>
                        `;
                        tipo = 'success';
                    }else{
    
                        if(data.estadoPaso == 0){
                            texto = '<p>No se realizo el envio debido a que el paso esta desactivado</p>';
                        }else{
                            texto = `
                                <p>Se presento un problema y no se pudo realizar el envio del correo</p>
                                <p>Respuesta de ejecucion ${data.respuesta}</p>
                                <p>Estado del registro ${data.muestra.estado}</p>
                            `;
                        }
                        tipo = 'error';
                    }
                }else{
                    texto = `
                            <p>Se presento un error durante la ejecucion de la prueba</p>
                            <p>Respuesta de ejecucion ${data.respuesta}</p>
                        `;
                    tipo = 'error';
                    titulo = 'Error de ejecución';
                }

                swal({
                    title : titulo,
                    text  : texto,
                    type  : tipo,
                    confirmButtonText : "Cerrar",
                    html: true
                });
            },
            error: function(){
                alertify.error('Ocurrio un error y no se pudo realizar la prueba de salida');
                $.unblockUI();
            }

        });

    }
</script>

