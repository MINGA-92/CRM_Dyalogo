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
    hr{
        width: 90%
    }
</style>

<?php
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G28/G28_CRUD.php";
   $sql = $mysqli->query("select ESTPAS_ConsInte__ESTRAT_b AS estrat FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__b ={$_GET['id_paso']}");

if($sql && $sql->num_rows == '1'){

    $sql=$sql->fetch_object();
    $estrat=$sql->estrat;

    $huesped=$mysqli->query("SELECT ESTRAT_ConsInte__PROYEC_b AS huesped FROM {$BaseDatos_systema}.ESTRAT WHERE ESTRAT_ConsInte__b = {$estrat}");

    if($huesped && $huesped->num_rows == '1'){
        $huesped=$huesped->fetch_object();
        $huesped=$huesped->huesped;
        
        // Llenamos las opciones de campaña
        $opcionesCampana = '<option value="">Seleccione</option>';
        $campanas = $mysqli->query("SELECT ESTPAS_ConsInte__CAMPAN_b AS id_campan ,B.CAMPAN_IdCamCbx__b AS dy_campan, ESTPAS_Comentari_b AS nombre_campan FROM DYALOGOCRM_SISTEMA.ESTPAS LEFT JOIN DYALOGOCRM_SISTEMA.CAMPAN B ON ESTPAS_ConsInte__CAMPAN_b=CAMPAN_ConsInte__b WHERE ESTPAS_ConsInte__ESTRAT_b={$estrat} AND ESTPAS_Tipo______b =1 AND ESTPAS_ConsInte__CAMPAN_b IS NOT NULL");
        
        while($campan = $campanas->fetch_object()){
            $opcionesCampana .= '<option value="'.$campan->dy_campan.'">'.sanear_strings($campan->nombre_campan).'</option>';
        }
                    
        // Llenamos las opciones de bots
        $opcionesBot = '<option value="">Seleccione</option>';
        $sqlBot = "SELECT e.ESTPAS_Comentari_b AS nombre, a.id AS id FROM DYALOGOCRM_SISTEMA.ESTPAS e 
                JOIN dyalogo_canales_electronicos.secciones_bot b ON b.id_estpas = e.ESTPAS_ConsInte__b
                JOIN dyalogo_canales_electronicos.dy_base_autorespuestas a ON a.id_seccion = b.id
            WHERE e.ESTPAS_Tipo______b = 12 AND e.ESTPAS_ConsInte__ESTRAT_b = {$estrat} AND e.ESTPAS_Comentari_b IS NOT NULL
            AND b.orden = 1";
        $bots = $mysqli->query($sqlBot);
        
        while($bot =$bots->fetch_object()){
            $opcionesBot .= '<option value="'.$bot->id.'">'.sanear_strings($bot->nombre).'</option>';
        }

        // Llenamos las opciones de webforms
        $opcioneswf = '<option value="">Seleccione</option>';
        $wf = $mysqli->query("SELECT ESTPAS_ConsInte__b as id, ESTPAS_Comentari_b as nombre FROM DYALOGOCRM_SISTEMA.ESTPAS WHERE ESTPAS_Tipo______b = 4 AND ESTPAS_ConsInte__ESTRAT_b = {$estrat} AND ESTPAS_Comentari_b IS NOT NULL");
        
        while($row =$wf->fetch_object()){
            $opcioneswf .= '<option value="'.$row->id.'">'.sanear_strings($row->nombre).'</option>';
        }
    }    
}

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

<div class="box box-primary">
    <div class="box-header">
        <div class="box-tools"></div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12" id="div_formularios">
                <div>
                    <button class="btn btn-default" id="save">
                        <i class="fa fa-save"></i>
                    </button>

                    <button class="btn btn-default" id="cancel">
                        <i class="fa fa-close"></i>
                    </button>
                </div>

                <br/>
                <div>
                    <form action="#" id="FormularioDatos" enctype="multipart/form-data" method="post">
                        
                        <input type="hidden" name="id_paso" id="id_paso" value="<?php if(isset($_GET['id_paso'])){ echo  $_GET['id_paso']; }else{ echo "0"; } ?>">
                        <input type="hidden" name="oper" id="oper" value='add'>
                        <input type="hidden" name="configuracionId" id="configuracionId" value="0">
                        <input type="hidden" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'] ;?>">

                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="nombre"><?php echo $str_nombre_mail_ms; ?></label>
                                    <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="chatWeb_<?php echo $_GET['id_paso']; ?>" placeholder="<?php echo $str_nombre_mail_ms; ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="pasoActivo" id="LblpasoActivo">ACTIVO</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="-1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself" checked> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#chatWebConfiguracion">
                                                Configuración
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="chatWebConfiguracion" class="panel-collapse collapse in">
                                        <div class="box-body">
                                            
                                            <div class="col-md-12">
                                                <h3>Generalidades del formulario de bienvenida del chat</h3>
                                            </div>
                                            <div class="row">
                                                <!-- Campos -->
                                                <div class="col-md-6">
                                                                    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Titulo que ve el usuario en el chat</label>
                                                            <input type="text" name="tituloWeb" id="tituloWeb" class="form-control input-sm" value="Chat por defecto">
                                                        </div>
                                                    </div>
    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Texto de bienvenida del formulario</label>
                                                            <input type="text" name="mensajeBienvenida" class="form-control input-sm" id="mensajeBienvenida" value="Bienvenido a nuestro chat">
                                                        </div>
                                                    </div>
    
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>URL de políticas de privacidad</label>
                                                            <input type="text" name="politicasPrivacidad" class="form-control input-sm" id="politicasPrivacidad" placeholder="https://url_de_politicas_de_privacidad">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" value="1" name="enviarHistorial" id="enviarHistorial"> 
                                                                    <b>Enviar historial de chat después de que se realice una tipificación de la comunicación.</b>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                </div>
                                                <!-- Logo -->
                                                <div class="col-md-6">
                                                    <label for="">Logo visible en el chat(Tamaño max:1MB)</label>
                                                    <div class="box">
                                                        <img src="<?=base_url?>assets/img/Kakashi.fw.png?foto=8859" alt="logo chat" id="filePreview" class="profile-user-img img-responsive">
                                                        <input type="file" name="logo_web" id="logoChat_1"> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Campos del formulario de bienvenida del chat</h3>
                                                </div>

                                                <!-- forulario del drag an drop -->
                                                <div class="div-col-12">
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <input type="text" name="buscadorDisponible" id="buscadorDisponible" class="form-control">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-center titulo-dragdrop">Disponibles</p>
                                                        <ul id="disponible" class="nav nav-pills nav-stacked lista" style="height: 300px;max-height: 300px;
                                                                    margin-bottom: 10px;
                                                                    overflow: auto;   
                                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">                                                                            
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-2 text-center" style="padding-top:100px">
                                                        <button type="button" id="derecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                                        <button type="button" id="todoDerecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br>
                                                        
                                                        <button type="button" id="izquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button> <br>
                                                        <button type="button" id="todoIzquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <input type="text" name="buscadorSeleccionado" id="buscadorSeleccionado" class="form-control">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-search"></i>
                                                            </span>
                                                        </div>
                                                        <p class="text-center titulo-dragdrop">Seleccionados</p>
                                                        <ul id="seleccionado" class="nav nav-pills nav-stacked lista" style="height: 300px;max-height: 300px;
                                                                    margin-bottom: 10px;
                                                                    overflow: auto;   
                                                                    -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                                        </ul>
                                                    </div>
                                                </div>   
                                            </div>

                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Campo correo para usar en el formulario de bienvenida y buscar automáticamente</label>
                                                        <select name="campoBusquedaWeb" id="campoBusquedaWeb" class="form-control input-sm">
                                                            <option value="0" selected>Seleccione</option>
                                                            <?php
                                                                $lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET['poblacion']." AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 14) AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY') ORDER BY PREGUN_Texto_____b ASC";
                                                                $res_Resultado = $mysqli->query($lsql);
                                                                while ($key = $res_Resultado->fetch_object()) {
                                                                    echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                }   
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <h4>Exportar chat</h4>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Opcion 1. Link</label>
                                                    <div class="form-group">
                                                        <input type="text" name="linkChat_1" id="linkChat" class="form-control" readonly value="" placeholder="(estará disponible después de guardar)">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label>Opcion 2. Html para embeber</label>
                                                    <div class="form-group">
                                                        <textarea rows="5" name="htmlembebido" id="htmlEmbebido" class="form-control" readonly placeholder="(estará disponible después de guardar)"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Seccion de horarios y acciones -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#chatWebHorariosAcciones">
                                                Horarios y acciones
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="chatWebHorariosAcciones" class="panel-collapse collapse">
                                        <div class="box-body">
                                            <!-- Horarios -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Horario</h3>
                                                </div>
                                                <div class="col-md-12">
                                                    <!-- Lunes -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C108" id="LblG10_C108">Lunes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C108" id="G10_C108" data-error="Before you wreck yourself" checked> 
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

                                                    <!-- Martes -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C111" id="LblG10_C111">Martes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C111" id="G10_C111" data-error="Before you wreck yourself" checked> 
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

                                                    <!-- Miercoles -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C114" id="LblG10_C114">Miercoles</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C114" id="G10_C114" data-error="Before you wreck yourself" checked> 
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

                                                    <!-- Jueves -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C117" id="LblG10_C117">Jueves</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C117" id="G10_C117" data-error="Before you wreck yourself" checked> 
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

                                                    <!-- Viernes -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C120" id="LblG10_C120">Viernes</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C120" id="G10_C120" data-error="Before you wreck yourself" checked> 
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

                                                    <!-- Sabado -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C123" id="LblG10_C123">Sabado</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C123" id="G10_C123" data-error="Before you wreck yourself"  > 
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

                                                    <!-- Domingo -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C126" id="LblG10_C126">Domingo</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C126" id="G10_C126" data-error="Before you wreck yourself"  > 
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

                                                    <!-- Festivos -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-2">
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C129" id="LblG10_C129">Festivos</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C129" id="G10_C129" data-error="Before you wreck yourself"  > 
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

                                            <!-- Acciones -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Acciones</h3>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="col-md-12">
                                                        <h4>Dentro del horario</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Acción</label>
                                                            <select name="dentroHorarioAccion" id="dentroHorarioAccion" class="form-control input-sm">
                                                                <option value="1">Pasar a agente</option>
                                                                <option value="2">Pasar a bot</option>
                                                                <option value="3">Pasar a formulario web</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Detalle de la acción</label>
                                                            <select name="campan" id="campan" class="form-control input-sm">
                                                                <?php echo $opcionesCampana ?>
                                                            </select>
                                                            <select name="bot" id="bot" class="form-control input-sm">
                                                                <?php echo $opcionesBot ?>
                                                            </select>
                                                            <select name="webform" id="webform" class="form-control input-sm">
                                                                <?php echo $opcioneswf ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Mensaje</label>
                                                            <input type="text" id="dentroHorarioMensaje" name="dentroHorarioMensaje" class="form-control input-sm" value="Bienvenido, por favor espere mientras asignamos un agente para usted.">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="col-md-12">
                                                        <h4>Fuera del horario</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Acción</label>
                                                            <select name="fueraHorarioAccion" id="fueraHorarioAccion" class="form-control input-sm">
                                                                <option value="1">Pasar a agente</option>
                                                                <option value="2">Pasar a bot</option>
                                                                <option value="3">Pasar a formulario web</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Detalle de la acción</label>
                                                            <select name="fueraHorarioCampana" id="fueraHorarioCampana" class="form-control input-sm">
                                                                <?php echo $opcionesCampana ?>
                                                            </select>
                                                            <select name="fueraHorarioBot" id="fueraHorarioBot" class="form-control input-sm">
                                                                <?php echo $opcionesBot ?>
                                                            </select>
                                                            <select name="fueraHorarioWebform" id="fueraHorarioWebform" class="form-control input-sm">
                                                                <?php echo $opcioneswf ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Mensaje</label>
                                                            <input type="text" id="fueraHorarioMensaje" name="fueraHorarioMensaje" class="form-control input-sm" value="Actualmente no estamos en horario de atención">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </form>


                    <div class="row" style="margin-top:15px">
                        <div class="col-md-12">
                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="" href="#secReports" aria-expanded="false" class="collapsed">
                                            REPORTES
                                        </a>
                                    </h4>
                                </div>
                                <div id="secReports" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                                    <div style="padding: 15px;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <iframe id="iframeReportes" src="<?= base_url ?>modulo/estrategias&view=si&report=si&stepUnique=yellow&typeStep=comChat&estrat=<?= $_GET['estrat'] ?>&paso=<?= $_GET['id_paso'] ?>" style="width: 100%;height: auto; min-height: 800px" marginheight="0" marginwidth="0" noresize="" frameborder="0"></iframe>
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
</div>

<script>

    $(function(){
        cargarDatos();

        // Aqui guardo el registro
        $("#save").click(function(){
            let valido = true;

            if($("#dentroHorarioAccion").val() == 1){
                if($("#campan").val() == ''){
                    valido = false;
                    $("#campan").focus();
                    alertify.error('Este campo es obligatorio');
                }
            }else if($("#dentroHorarioAccion").val() == 2){
                
                if($("#bot").val() == ''){
                    valido = false;
                    $("#bot").focus();
                    alertify.error('Este campo es obligatorio');
                }
            }else if($("#dentroHorarioAccion").val() == 3){
                
                if($("#webform").val() == ''){
                    valido = false;
                    $("#webform").focus();
                    alertify.error('Este campo es obligatorio');
                }
            } 

            if($("#fueraHorarioAccion").val() == 1){
                
                if($("#fueraHorarioCampana").val() == ''){
                    valido = false;
                    $("#fueraHorarioCampana").focus();
                    alertify.error('Este campo es obligatorio');
                }
            }else if($("#fueraHorarioAccion").val() == 2){
                
                if($("#fueraHorarioBot").val() == ''){
                    valido = false;
                    $("#fueraHorarioBot").focus();
                    alertify.error('Este campo es obligatorio');
                }
            }else if($("#fueraHorarioAccion").val() == 3){
                
                if($("#fueraHorarioWebform").val() == ''){
                    valido = false;
                    $("#fueraHorarioWebform").focus();
                    alertify.error('Este campo es obligatorio');
                }
            }

            if($("#nombre").val() == ''){
                valido = false;
                alertify.error('el campo nombre es obligatorio');
            }

            if($("#campoBusquedaWeb").val() == '' || $("#campoBusquedaWeb").val() == 0){
                valido = false;
                $("#campoBusquedaWeb").focus();
                alertify.error('El campo correo para usar en el formulario de bienvenida y buscar automáticamente es obligatorio');
            }

            if(valido){
                var camposFormulario = JSON.stringify(cargarCamposSeleccionados());

                let formData = new FormData($("#FormularioDatos")[0]);
                formData.append('camposFormulario', camposFormulario);

                $.ajax({
                    url: '<?=$url_crud;?>?insertarDatos=true&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&poblacion=<?php echo $_GET['poblacion'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
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
                        console.log(data);
                        $("#FormularioDatos")[0].reset();
                        $("#editarDatos").modal('hide');
                        alertify.success('Datos guardados');
                        location.reload();
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    error: function(data){
                        if(data.responseText){
                            alertify.error("Hubo un error al guardar la información" + data.responseText);
                        }else{
                            alertify.error("Hubo un error al guardar la información");
                        }
                    }
                });
            }
        });

        $("#dentroHorarioAccion").change(function(){
            $("#campan").hide();
            $("#bot").hide();
            $("#webform").hide();

            if($("#dentroHorarioAccion").val() == 1){
                $("#campan").show();
            }
            if($("#dentroHorarioAccion").val() == 2){
                $("#bot").show();
            }
            if($("#dentroHorarioAccion").val() == 3){
                $("#webform").show();
            }
        });

        $("#fueraHorarioAccion").change(function(){
            $("#fueraHorarioCampana").hide();
            $("#fueraHorarioBot").hide();
            $("#fueraHorarioWebform").hide();

            if($("#fueraHorarioAccion").val() == 1){
                $("#fueraHorarioCampana").show();
            }
            if($("#fueraHorarioAccion").val() == 2){
                $("#fueraHorarioBot").show();
            }
            if($("#fueraHorarioAccion").val() == 3){
                $("#fueraHorarioWebform").show();
            }
        });

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
    });

    function cargarDatos(){

        inicializarHorarios();

        $.ajax({
            url: '<?=$url_crud;?>?getDatos=true',
            type: 'POST',
            data: {huesped : <?php echo $_SESSION['HUESPED'];?>, pasoId : <?php echo $_GET['id_paso']; ?>, bd: <?=$_GET['poblacion']?>},
            dataType : 'json',
            beforeSend : function(){
                $.blockUI({  baseZ: 2000, message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                console.log(data);

                $('#disponible').html(data.camposFormularioDisponible);
                $('#seleccionado').html(data.camposFormularioSeleccionados);

                if(data.datosPaso){

                    if(data.datosPaso.nombre){
                        $("#nombre").val(data.datosPaso.nombre);
                    }

                    if(data.datosPaso.activo == '-1'){
                        if(!$("#pasoActivo").is(':checked')){
                            $("#pasoActivo").prop('checked', true);  
                        }
                    } else {
                        if($("#pasoActivo").is(':checked')){
                            $("#pasoActivo").prop('checked', false);  
                        }
                    }
                }

                if(data.datosChat){
                    $("#oper").val("edit");
                    $("#configuracionId").val(data.datosChat.id);
                    $("#tituloWeb").val(data.datosChat.nombre);
                    $("#mensajeBienvenida").val(data.datosChat.mensaje_bienvenida);
                    $("#politicasPrivacidad").val(data.datosChat.link_politica_privacidad);
                    $("#campoBusquedaWeb").val(data.datosChat.id_pregun_campo_busqueda);

                    if(data.datosChat.id_estpas_envio_historial > 0){
                        $("#enviarHistorial").prop('checked', true);
                    }else{
                        $("#enviarHistorial").prop('checked', false);
                    }

                    //logo
                    if(data.datosChat.ruta_logo != ''){
                        $('#filePreview').attr("src","getFile.php?logo_web=true&ruta="+data.datosChat.ruta_logo);
                    }else{
                        $('#filePreview').attr("src","<?=base_url?>assets/img/Kakashi.fw.png?foto=8859");
                    }
                    
                    //link
                    var link_chat = '<?php echo $url_usuarios; ?>dyalogocbx/customers/dy/chat/'+data.datosChat.id;
                    $('#linkChat').val(link_chat);

                    var chat_embebido = chatEmbebido(link_chat);
                    $('#htmlEmbebido').text(chat_embebido);

                    //Horarios
                    if(data.horario){

                        if(data.horario[1]){

                            if(!$("#G10_C108").is(':checked')){
                                $("#G10_C108").prop('checked', true);  
                            }
                            
                            $("#G10_C109").val(data.horario[1].momento_inicial);
                            $("#G10_C110").val(data.horario[1].momento_final); 
                            
                        }else{
                            if($("#G10_C108").is(':checked')){
                                $("#G10_C108").prop('checked', false);  
                            }
                        }

                        if(data.horario[2]){

                            if(!$("#G10_C111").is(':checked')){
                                $("#G10_C111").prop('checked', true);  
                            }

                            $("#G10_C112").val(data.horario[2].momento_inicial);
                            $("#G10_C113").val(data.horario[2].momento_final); 

                        }else{
                            if($("#G10_C111").is(':checked')){
                                $("#G10_C111").prop('checked', false);  
                            }
                        }

                        if(data.horario[3]){
                            if(!$("#G10_C114").is(':checked')){
                                $("#G10_C114").prop('checked', true);  
                            }
                            $("#G10_C115").val(data.horario[3].momento_inicial);
                            $("#G10_C116").val(data.horario[3].momento_final); 
                        }else{
                            if($("#G10_C114").is(':checked')){
                                $("#G10_C114").prop('checked', false);  
                            }
                        }

                        if(data.horario[4]){
                            if(!$("#G10_C117").is(':checked')){
                                $("#G10_C117").prop('checked', true);  
                            }
                            $("#G10_C118").val(data.horario[4].momento_inicial);
                            $("#G10_C119").val(data.horario[4].momento_final); 
                        }else{
                            if($("#G10_C117").is(':checked')){
                                $("#G10_C117").prop('checked', false);  
                            }
                        }

                        if(data.horario[5]){
                            if(!$("#G10_C120").is(':checked')){
                                $("#G10_C120").prop('checked', true);  
                            }
                            $("#G10_C121").val(data.horario[5].momento_inicial);
                            $("#G10_C122").val(data.horario[5].momento_final); 
                        }else{
                            if($("#G10_C120").is(':checked')){
                                $("#G10_C120").prop('checked', false);  
                            }
                        }
       
                        if(data.horario[6]){
                            if(!$("#G10_C123").is(':checked')){
                                $("#G10_C123").prop('checked', true);  
                            }
                            $("#G10_C124").val(data.horario[6].momento_inicial);
                            $("#G10_C125").val(data.horario[6].momento_final); 
                        }else{
                            if($("#G10_C123").is(':checked')){
                                $("#G10_C123").prop('checked', false);  
                            }
                        }

                        if(data.horario[7]){
                            if(!$("#G10_C126").is(':checked')){
                                $("#G10_C126").prop('checked', true);  
                            }
                            $("#G10_C127").val(data.horario[7].momento_inicial);
                            $("#G10_C128").val(data.horario[7].momento_final); 
                        }else{
                            if($("#G10_C126").is(':checked')){
                                $("#G10_C126").prop('checked', false);  
                            }
                        }

                        if(data.horario[8]){
                            if(!$("#G10_C129").is(':checked')){
                                $("#G10_C129").prop('checked', true);  
                            }
                            $("#G10_C130").val(data.horario[8].momento_inicial);
                            $("#G10_C131").val(data.horario[8].momento_final); 
                        }else{
                            if($("#G10_C129").is(':checked')){
                                $("#G10_C129").prop('checked', false);  
                            }
                        }
                        
                    }

                    //Acciones
                    $("#dentroHorarioAccion").val(data.datosChat.dentro_horario_accion);
                    $("#dentroHorarioMensaje").val(data.datosChat.frase_bienvenida_autorespuesta);
                    if(data.datosChat.dentro_horario_accion == 1){
                        $("#campan").val(data.datosChat.dentro_horario_detalle_accion);
                    }
                    if(data.datosChat.dentro_horario_accion == 2){
                        $("#bot").val(data.datosChat.dentro_horario_detalle_accion);
                    }
                    if(data.datosChat.dentro_horario_accion == 3){
                        $("#webform").val(data.datosChat.dentro_horario_detalle_accion);
                    }

                    $("#fueraHorarioAccion").val(data.datosChat.fuera_horario_accion);
                    $("#fueraHorarioMensaje").val(data.datosChat.frase_fuera_horario);
                    if(data.datosChat.fuera_horario_accion == 1){
                        $("#fueraHorarioCampana").val(data.datosChat.fuera_horario_detalle_accion);
                    }
                    if(data.datosChat.fuera_horario_accion == 2){
                        $("#fueraHorarioBot").val(data.datosChat.fuera_horario_detalle_accion);
                    }
                    if(data.datosChat.fuera_horario_accion == 3){
                        $("#fueraHorarioWebform").val(data.datosChat.fuera_horario_detalle_accion);
                    }
                }

                $("#dentroHorarioAccion").change();
                $("#fueraHorarioAccion").change();

                $('input[type="checkbox"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-blue'
                });
            },
            complete : function(){
                $.unblockUI();
            },
            error: function(){
                $.unblockUI();
                alertify.error('Se ha presentado un error al cargar la informacion');
            }
        });
    }

    function inicializarHorarios(){
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

    function chatEmbebido(url){
        let midata = `
        
        <style>

        /* Button used to open the chat form - fixed at the bottom of the page */
        .open-button {

        color: white;
        padding: 1px 1px;
        border: none;
        cursor: pointer;
        position: fixed;
        bottom: 5px;
        right: 5px;
        
        }

        /* The popup chat - hidden by default */
        .chat-popup {
            -webkit-box-shadow: 2px 2px 20px 2px #EDEDED;
            -moz-box-shadow:    2px 2px 20px 2px #EDEDED;
            box-shadow:         2px 2px 20px 2px #EDEDED;
        display: none;
        position: fixed;
        bottom: 0;
        right: 15px;
        
            
        z-index: 9;
            height: 65%;
            width: 23%;
        }

        /* Add styles to the form container */
        .form-container {
        max-width: 600px;
        padding: 1px;
        background-color: white;
        }


        /* Set a style for the submit/send button */
        .form-container .btn {
        background-color: #4CAF50;
        color: white;
        padding: 16px 20px;
        border: none;
        cursor: pointer;
        width: 80px;
        margin-bottom:10px;
        opacity: 0.8;
        }

        /* Add a red background color to the cancel button */
        .form-container .cancel {
        background-color: white;
            color: black;
            z-index: 10;
            width: 10px;
            text-align: left;
            padding: 0px;
            
            
        }

        /* Add some hover effects to buttons */
        .form-container .btn:hover, .open-button:hover {
        opacity: 1;
        }
        </style>

        <a class="open-button" title="Dyalogamos ?" onclick="openForm()"><img src="https://storage.googleapis.com/dy-recursos-publicos/div_chat/icon.png"></a>

        <div class="chat-popup" id="myForm">
        <form  class="form-container">
            <button type="button" class="btn cancel" onclick="closeForm()">X</button>
            <iframe src="${url}" width="100%"  style="border:none;height: 80vh">
            </iframe>
        </form>
        </div>

        <script>
        function openForm() {
        document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
        document.getElementById("myForm").style.display = "none";
        }
        <\/script>
        `;

        return midata;
    }

    function cargarCamposSeleccionados(){
        var arr = [];
        $('ul#seleccionado li').each(function(index){
            arr.push([
                $(this).data('id'),
                $(this).data('nombre'),
                $(this).data('formulario'),
                $(this).data('requerido'),
                $(this).data('tipo')
            ]);
        } );

        return arr;
    }

    var fileUpload = document.getElementById('logoChat_1');
    fileUpload.onchange = function(e){
        readFile(e.srcElement);
    }

    function readFile(input){
        if(input.files && input.files[0]){
            
            var uploadFile = input.files[0];
            if(!(/\.(jpg|png|gif|jpeg)$/i).test(uploadFile.name)){
                document.getElementById('logoChat_1').value = '';
                alertify.error('Este archivo no tiene el formato especificado ');
            }else{
                console.log(uploadFile.size);
                var maxImage = 1 * 1024 * 1024;
                if(uploadFile.size > maxImage){
                    document.getElementById('logoChat_1').value = '';
                    alertify.error('La imagen no puede pesar más de 1MB');
                }else{
                    var reader = new FileReader();
                    reader.onload = function(e){
                        var myFilePreview = document.getElementById('filePreview');
                        myFilePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
    }
</script>

<!-- Drag and drop -->
<script>

    $(function(){

        // En esta funcion se encontrara el buscador que filtrara por el nombre 
        $('#buscadorDisponible, #buscadorSeleccionado').keyup(function(){
            var tipoBuscador = $(this).attr('id');
            var nombres = '';

            if(tipoBuscador == 'buscadorDisponible'){
                nombres = $('ul#disponible .nombre');
            }else if(tipoBuscador == 'buscadorSeleccionado'){
                nombres = $('ul#seleccionado .nombre');
            }

            var buscando = $(this).val();
            var item='';

            for (let i = 0; i < nombres.length; i++) {
                item = $(nombres[i]).html().toLowerCase();
                
                for (let x = 0; x < item.length; x++) {
                    if(buscando.length == 0 || item.indexOf(buscando) > -1 ){
                        $(nombres[i]).closest('li').show();
                    }else{
                        $(nombres[i]).closest('li').hide();
                        
                    }
                }
                
            }
        });

        /** Estas funciones se encargan del funcionamiento del drag & drop */
        $("#disponible").sortable({connectWith:"#seleccionado"});
        $("#seleccionado").sortable({connectWith:"#disponible"});

        // Capturo el li cuando es puesto en la lista de usuarios disponible        	
        $( "#disponible" ).on( "sortreceive", function( event, ui ) {
            let arrDisponible = [];
            let arrDisponible2 = [];
            arrDisponible[0] = ui.item.data("id");
            arrDisponible2[0] = ui.item.data("camp3");

            moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
        } );

        // Capturo el li cuando es puesto en la lista de usuarios seleccionados        	
        $( "#seleccionado" ).on( "sortreceive", function( event, ui ) {
            let arrSeleccionado = [];
            let arrSeleccionado2 = [];
            arrSeleccionado[0] = ui.item.data("id");
            arrSeleccionado2[0] = ui.item.data("camp3");

            moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
        } );

        // Solo se selecciona el check cuando se clickea el li
        $("#disponible, #seleccionado").on('click', 'li', function(){
            $(this).find(".mi-check").iCheck('toggle');

            if($(this).find(".mi-check").is(':checked') ){
                $(this).addClass('seleccionado');
            }else{
                $(this).removeClass('seleccionado');
            }
        });

        $("#disponible, #seleccionado").on('ifToggled', 'input', function(event){
            if($(this).is(':checked') ){
                $(this).closest('li').addClass('seleccionado');
            }else{
                $(this).closest('li').removeClass('seleccionado');
            }
        });

        // Envia los elementos seleccionados a la lista de la derecha
        $('#derecha').click(function(){
            var obj = $("#disponible .seleccionado");
            $('#seleccionado').append(obj);

            let arrSeleccionado = [];
            let arrSeleccionado2 = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
                arrSeleccionado2[key] = $(value).data("camp3");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrSeleccionado.length > 0 && arrSeleccionado2.length > 0){
                moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
            }
            
        });

        // Envia los elementos seleccionados a la lista de la izquerda
        $('#izquierda').click(function(){
            var obj = $("#seleccionado .seleccionado");
            $('#disponible').append(obj);

            let arrDisponible = [];
            let arrDisponible2 = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
                arrDisponible2[key] = $(value).data("camp3");
            });

            obj.removeClass('seleccionado');
            obj.find(".mi-check").iCheck('toggle');

            if(arrDisponible.length > 0 && arrDisponible2.length > 0){
                moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
            }
        });

        // Envia todos los elementos a la derecha
        $('#todoDerecha').click(function(){
            var obj = $("#disponible li");
            $('#seleccionado').append(obj);

            let arrSeleccionado = [];
            let arrSeleccionado2 = [];
            obj.each(function (key, value){
                arrSeleccionado[key] = $(value).data("id");
                arrSeleccionado2[key] = $(value).data("camp3");
            });
            
            moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
        });

        // Envia todos los elementos a la izquerda
        $('#todoIzquierda').click(function(){
            var obj = $("#seleccionado li");
            $('#disponible').append(obj);

            let arrDisponible = [];
            let arrDisponible2 = [];
            obj.each(function (key, value){
                arrDisponible[key] = $(value).data("id");
                arrDisponible2[key] = $(value).data("camp3");
            });
            
            moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
        });

        // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
        function moverUsuarios(arrUsuarios, arrUsuarios2, accion){
            console.log('Se ha movido un objeto');
            
        }
    });

</script>