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
    $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G34/G34_CRUD.php";

    function sanear_strings($string) { 
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
                    <button class="btn btn-default" id="save" onclick="guardarConfiguracion()">
                        <i class="fa fa-save"></i>
                    </button>

                    <button class="btn btn-default" id="cancel">
                        <i class="fa fa-close"></i>
                    </button>
                </div>

                <br/>

                <div>
                    <form action="#" id="FormularioDatos" enctype="multipart/form-data" method="POST">
                        
                        <input type="hidden" name="id_paso" id="id_paso" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo "0"; } ?>">
                        <input type="hidden" name="oper" id="oper" value='add'>
                        <input type="hidden" name="huesped" id="huesped" value="<?php echo $_SESSION['HUESPED'];?>">
                        <input type="hidden" name="configuracionId" id="configuracionId" value="0">

                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="nombre"><?php echo $str_nombre_mail_ms; ?></label>
                                    <input type="text" class="form-control input-sm" id="nombre" name="nombre" value="Instagram_<?php echo $_GET['id_paso']; ?>" placeholder="<?php echo $str_nombre_mail_ms; ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="pasoActivo">ACTIVO</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="-1" name="pasoActivo" id="pasoActivo" data-error="Before you wreck yourself" checked> 
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <!-- <p><a href="/admin/public/pdf/creacion_cuenta_fb_messenger.pdf" target="_blank">Instructivo de creación de canal de Facebook Messenger</a></p> -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel box box-primary">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#instagramConfiguracion">
                                                Configuraciósn
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="instagramConfiguracion" class="panel-collapse collapse in">
                                        <div class="box-body">
                                            
                                            <div class="row">

                                                <!-- Campos -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">cuenta de Instagram a usar</label>
                                                        <select name="datoIntegracion" id="datoIntegracion" class="form-control input-sm">
                                                            <option value="">Seleccione</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Campo para búsqueda del registro en la base de datos</label>
                                                        <select name="campoBusquedaIg" id="campoBusquedaIg" class="form-control input-sm">
                                                            <option value="0" selected>Seleccione</option>
                                                            <?php
                                                                $lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET['poblacion']." AND (PREGUN_Texto_____b != 'ORIGEN_DY_WF' AND PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY') ORDER BY PREGUN_Texto_____b ASC";
                                                                $res_Resultado = $mysqli->query($lsql);
                                                                while ($key = $res_Resultado->fetch_object()) {
                                                                    echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                }   
                                                            ?>
                                                        </select>
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
                                            <a data-toggle="collapse" data-parent="#accordion" href="#chatIgHorariosAcciones">
                                                Horarios y acciones
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="chatIgHorariosAcciones" class="panel-collapse collapse">
                                        <div class="box-body">

                                            <!-- Horarios -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Horario</h3>
                                                </div>
                                                <div class="col-md-12">

                                                    <!-- Lunes -->
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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

                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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


                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                        <div class="col-md-4 col-xs-4">
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
                                                            <select name="dentroHorarioCampan" id="dentroHorarioCampan" class="form-control input-sm">
                                                            </select>
                                                            <select name="dentroHorarioBot" id="dentroHorarioBot" class="form-control input-sm">
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
                                                            </select>
                                                            <select name="fueraHorarioBot" id="fueraHorarioBot" class="form-control input-sm">
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('G34_Eventos.php') ?>