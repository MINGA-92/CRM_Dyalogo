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
    hr{
        width: 90%
    }
</style>

<?php
   //SECCION : Definicion urls
   $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G29/G29_CRUD.php";
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
                        <input type="hidden" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'] ;?>">
                        <input type="hidden" name="configuracionId" id="configuracionId" value="0">

                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="nombre"><?php echo $str_nombre_mail_ms; ?></label>
                                    <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="whatsapp_<?php echo $_GET['id_paso']; ?>" placeholder="<?php echo $str_nombre_mail_ms; ?>">
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#whatsappConfiguracion">
                                                Configuración
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="whatsappConfiguracion" class="panel-collapse collapse in">
                                        <div class="box-body">
                                            
                                            <div class="row">

                                                <!-- Campos -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Cuenta de Whatsapp a usar</label>
                                                        <select name="datoIntegracion" id="datoIntegracion" class="form-control input-sm">
                                                            <option value="">Seleccione</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Campo de teléfono para búsqueda e inserción de registros</label>
                                                        <select name="campoBusquedaWhatsapp" id="campoBusquedaWhatsapp" class="form-control input-sm">
                                                            <option value="0" selected>Seleccione</option>
                                                            <?php
                                                                $lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET['poblacion']." AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 14) AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY') ORDER BY PREGUN_Texto_____b ASC";
                                                                $res_Resultado = $mysqli->query($lsql);
                                                                while ($key = $res_Resultado->fetch_object()) {
                                                                    echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                }   
                                                            ?>
                                                        </select>
                                                        <span>Este campo es utilizado para que los registros cuando son nuevos se inserten automáticamente</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#urlWhatsapp">
                                                Enlace para acceder a Whatsapp
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="urlWhatsapp" class="panel-collapse collapse in">
                                        <div class="box-body">
                                            
                                            <div class="row">

                                                <!-- Campos -->
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="aWhatsapp">Si quieres poner en tu pagina web un elemento para que desde allá accedan a tu Whatsapp, usa este link.</label>
                                                        <div class="form-group">
                                                            <a id="aWhatsapp" href="https://api.whatsapp.com/send?phone=??????" target="_blank">https://api.whatsapp.com/send?phone=??????</a>
                                                        </div>
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

        $.ajax({
                url: '<?php echo $url_crud;?>?NumeroAutorizado=si',
                type:'POST',
                data:{idPaso_t : <?php echo $_GET['id_paso']; ?>},
                global:false,
                success:function(data){

                    $("#aWhatsapp").attr("href","https://api.whatsapp.com/send?phone="+data);
                    $("#aWhatsapp").text("https://api.whatsapp.com/send?phone="+data);

                }
            });

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
            }

            if($("#nombre").val() == ''){
                valido = false;
                alertify.error('el campo nombre es obligatorio');
            }

            if($("#campoBusquedaWhatsapp").val() == '' || $("#campoBusquedaWhatsapp").val() == 0){
                valido = false;
                $("#campoBusquedaWhatsapp").focus();
                alertify.error('Este campo es obligatorio');
            }

            if(valido){

                let formData = new FormData($("#FormularioDatos")[0]);

                $.ajax({
                    url: '<?=$url_crud;?>?insertarDatos=true&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    dataType : 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend : function(){
                        $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
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

            if($("#dentroHorarioAccion").val() == 1){
                $("#campan").show();
            }
            if($("#dentroHorarioAccion").val() == 2){
                $("#bot").show();
            }
        });

        $("#fueraHorarioAccion").change(function(){
            $("#fueraHorarioCampana").hide();
            $("#fueraHorarioBot").hide();

            if($("#fueraHorarioAccion").val() == 1){
                $("#fueraHorarioCampana").show();
            }
            if($("#fueraHorarioAccion").val() == 2){
                $("#fueraHorarioBot").show();
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
                $.blockUI({  baseZ: 2000, message: '<img src="assets/img/clock.gif" /> <?php echo $str_message_wait_c;?>' });
            },
            success: function(data){
                console.log(data);

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

                // Traigo los canales
                if(data.canales){
                    let canales = data.canales;
                    let opcion = "";

                    canales.forEach(element => {
                        if(element.disponible){
                            opcion = `<option value="${element.cuenta}">${element.nombre}</option>`;
                        }else{
                            opcion = `<option disabled value="${element.cuenta}">${element.nombre}</option>`;
                        }

                        $("#datoIntegracion").append(opcion);
                    });
                }

                if(data.datosChat){
                    $("#oper").val("edit");
                    
                    $("#configuracionId").val(data.datosChat.id);
                    $("#datoIntegracion").val(data.datosChat.dato_integracion);
                    $("#datoIntegracion option[value='" + data.datosChat.dato_integracion + "']").attr("disabled", false);
                    $("#campoBusquedaWhatsapp").val(data.datosChat.id_pregun_campo_busqueda);

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
                    }else{
                        $("#bot").val(data.datosChat.dentro_horario_detalle_accion);
                    }

                    $("#fueraHorarioAccion").val(data.datosChat.fuera_horario_accion);
                    $("#fueraHorarioMensaje").val(data.datosChat.frase_fuera_horario);
                    if(data.datosChat.fuera_horario_accion == 1){
                        $("#fueraHorarioCampana").val(data.datosChat.fuera_horario_detalle_accion);
                    }else{
                        $("#fueraHorarioBot").val(data.datosChat.fuera_horario_detalle_accion);
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


</script>