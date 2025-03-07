<?php
 //Este es el archivo con el que se maneja el FRONT-END de las campanas entrates del sistema
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
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width:95%; margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-header" style="padding-right: 5px; padding-bottom: 3px; padding-top: 3px;">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body embed-container">
                <iframe id="frameContenedor" src=""  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php
    //SECCION : Definicion urls
    $url_crud = base_url."cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD_v2.php";
    $url_crud_extender = base_url."cruds/DYALOGOCRM_SISTEMA/G10/G10_extender_funcionalidad.php";
    //SECCION : CARGUE DATOS LISTA DE NAVEGACIÓN

    $Zsql = "SELECT G10_ConsInte__b as id, G10_C71 as camp1 , b.LISOPC_Nombre____b as camp2 FROM ".$BaseDatos_systema.".G10  LEFT JOIN ".$BaseDatos_systema.".LISOPC as b ON b.LISOPC_ConsInte__b = G10_C76 ORDER BY G10_ConsInte__b DESC LIMIT 0, 50";
    

    $result = $mysqli->query($Zsql);
        if(isset($_GET['id_paso'])){
            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__b)) = '".$_GET['id_paso']."'";
            $res_Lsql = $mysqli->query($Lsql);
            if($res_Lsql && $res_Lsql->num_rows==1){
                $datos = $res_Lsql->fetch_array();
                $_GET['id_paso']=$datos['ESTPAS_ConsInte__b'];
        }else{ ?>
            <script>
                window.location.href="<?=base_url?>modulo/error";
            </script>
<?php        }
    }


    if(isset($_GET['id_paso'])){
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_GET['id_paso'];
        $res_Lsql = $mysqli->query($Lsql);
        $datos = $res_Lsql->fetch_array();

        $Lsql = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_Nombre____b, CAMPAN_ConsInte__b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$datos['ESTPAS_ConsInte__CAMPAN_b'];
        $resCampan = $mysqli->query($Lsql);
        $dataCampanGuion = $resCampan->fetch_array();
    }
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $str_title_campan;?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url?>modulo/seleccion/estrategias/<?php echo md5(clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>"><i class="fa fa-adjust"></i> <?php echo $str_estrategia;?></a></li>
            <li><a href="<?=base_url?>modulo/flujograma/<?php echo md5(clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>"><?php echo $str_flujograma;?></a></li>
            <li class="active"><?php echo $str_campan;?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12" id="div_formularios">
                        <div>
                            <button class="btn btn-default" id="Save">
                                <i class="fa fa-save"></i>
                            </button>
                            <?php if(isset($_GET['ruta']) && $_GET['ruta'] == '1'){ ?>
                            <a href="<?=base_url?>modulo/flujograma/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>" class="btn btn-default">
                                <i class="fa fa-close"></i>
                            </a>
                            <?php }else{ ?>
                            <a href="<?=base_url?>modulo/estrategias" class="btn btn-default">
                                <i class="fa fa-close"></i>
                            </a>
                            <?php } ?>
                        </div>
                        <!-- CUERPO DEL FORMULARIO CAMPOS-->
                        <br/>
                        <div>
                            <form id="FormularioDatos" data-toggle="validator" enctype="multipart/form-data"   action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div id="12">


                                            <input type="hidden"  class="form-control input-sm Numerico" value="<?php echo $_SESSION['HUESPED'] ;?>"  name="G10_C70" id="G10_C70" placeholder="HUESPED">
                                            <input type="hidden"  class="form-control input-sm Numerico" value="<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo 0; } ;?>"  name="id_estpas" id="id_estpas" placeholder="HUESPED">
                                            <input type="hidden"  class="form-control input-sm Numerico" value="<?php if(isset($datos['ESTPAS_ConsInte__ESTRAT_b'])){ echo $datos['ESTPAS_ConsInte__ESTRAT_b']; }else{ echo 0; } ;?>"  name="id_estrategia" id="id_estrategia" placeholder="Estrategia">
                                            <div class="row">
                                                 <div class="col-md-6 col-xs-6">
                                                        <!-- CAMPO TIPO TEXTO -->
                                                        <div class="form-group">
                                                            <label for="G10_C71" id="LblG10_C71">NOMBRE</label>
                                                            <input type="text" class="form-control input-sm" id="G10_C71" value=""  name="G10_C71"  placeholder="NOMBRE">
                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                </div>

                                                <div class="col-md-6 col-xs-6">
                                                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                    <div class="form-group">
                                                        <label for="G10_C72" id="LblG10_C72">ACTIVA</label>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="-1" name="G10_C72" id="G10_C72" data-error="Before you wreck yourself"  > 
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!-- FIN DEL CAMPO SI/NO -->
                                                </div>  
                                            </div>


                                            <div class="row">
                                                 <div class="col-md-6 col-xs-6">
                                                        <?php 
                                                        $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 1 AND G5_C316 = ".$_SESSION['HUESPED']." ORDER BY G5_C28 ASC";
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
                                                                        echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";

                                                                    }    
                                                                ?>
                                                            </select>
                                                            

                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO LISTA -->
                                      
                                                </div>

                                                <div class="col-md-6 col-xs-6">

                                     
                                                        <?php 
                                                        $str_Lsql = "SELECT  G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5 WHERE G5_C29 = 2 AND G5_C316 = ".$_SESSION['HUESPED']."  ORDER BY G5_C28 ASC";
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
                                                                        echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";

                                                                    }    
                                                                    
                                                                ?>
                                                            </select>
                                                            

                                                        </div>
                                                        <!-- FIN DEL CAMPO TIPO LISTA -->
                                      
                                                </div>
                
                                            </div>
                                        </div>

                                        <div class="panel box box-primary" id="llamadasCampan">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_30" class="collapseLlamadas">
                                                        <?php echo $str_title_campan_entrante;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_30" class="panel-collapse collapse ">
                                                <div class="row">
                                                    <input type="hidden" value="" name="G10_C75" id="G10_C75" >
                                                     <div class="col-md-6 col-xs-6">
                                                            <!-- CAMPO TIPO TEXTO -->
                                                            <div class="form-group">
                                                                <label for="G10_C106" id="LblG10_C106">Codigo para transferir a la campana desde el ivr</label>
                                                                <input type="text" class="form-control input-sm" id="G10_C106" value=""  name="G10_C106"  placeholder="Codigo ivr">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO TEXTO -->
                                                    </div>                                                    
                                                </div>

                                                <div class="row" id="s_click_to_call">
                                                    <div class="col-md-12">
                                                        <h4><strong>Click to call</strong></h4>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="activarClickToCall" id="activarClickToCall">
                                                                    Activar click to call
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xs-12">
                                                        <label>Texto que van a aparecer al click to call</label>
                                                        <div class="form-group">
                                                            <textarea rows="2" name="dyTr_ctcHtml" id="dyTr_ctcHtml" class="form-control input-sm"><h2>Da click aquí para hablar con nosotros</h2></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        <label>Link</label>
                                                        <div class="form-group">
                                                            <input type="text" name="ctcLink" id="ctcLink" class="form-control" readonly value="" placeholder="(estará disponible después de guardar)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                        
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_31">
                                                        <?php echo $str_title_chat;?>
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="s_31" class="panel-collapse collapse">

                                                <div class="panel box">
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#s_31_2">
                                                                Mensajes
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="s_31_2" class="panel-collapse collapse">
                                                        <div class="row">
                                                            
                                                            <!-- TODO: DEBO ELIMINAR ESTE PASO DE AQUI, Y HACER LA RESPECTIVA ELIMINACION EN EL CRUD -->
                                                            <div class="col-md-6 col-xs-6" style="display:none">
                                                                <label>Mensaje bienvenida de auto respuesta</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="FraseBienvenidaAutorespuesta" id="FraseBienvenidaAutorespuesta" value="Bienvenido, por favor espere mientras asignamos un agente para usted.">
                                                                </div>
                                                            </div>

                                                            <!-- TODO: DEBO ELIMINAR ESTE PASO DE AQUI, Y HACER LA RESPECTIVA ELIMINACION EN EL CRUD -->
                                                            <div class="col-md-6 col-xs-6" style="display:none">
                                                                <label>Mensaje fuera de horario</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="FraseFueraDeHorario" id="FraseFueraDeHorario" value="Actualmente no estamos en horario de atención">
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- TODO: DEBO ELIMINAR ESTE PASO DE AQUI, Y HACER LA RESPECTIVA ELIMINACION EN EL CRUD -->
                                                            <div class="col-md-6 col-xs-6" style="display:none">
                                                                <label>Mensajes sin agentes disponibles</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="FraseSinAgentesDisponibles" id="FraseSinAgentesDisponibles" value="Por favor espera mientras tenemos agentes disponibles.">
                                                                </div>
                                                            </div>
                                                        </div>    
                                                        
                                                        <h3>Agente asignado</h3>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Mensaje agente asignado</label>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" name="FraseAgenteAsignado" id="FraseAgenteAsignado" value="Ha sido asignado el agente.">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- En este punto configuro los mensajes del chat que se ejecutan cuando se asigna a una campana -->
                                                        <h3>Cierre de chat por el agente</h3>
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <label>Mensajes al cerrar el chat</label>
                                                                <div class="form-group">
                                                                    <input type="text" name="cierreChatMensaje" id="cierreChatMensaje" class="form-control input-sm" value="Gracias por comunicarte con nosotros, Hasta luego">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="">Enviar a</label>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="cierreChatEnviarBot" id="cierreChatEnviarBot" value="1" onclick="checkstate('cierreChatEnviarBot')">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar al bot</label>
                                                                <select class="form-control input-sm" name="cierreChatBot" id="cierreChatBot" onchange="buscarSeccionesBot('cierreChatBot', 'cierreChatSeccion')" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar a la sección del Bot</label>
                                                                <select class="form-control input-sm" data-id="0" name="cierreChatSeccion" id="cierreChatSeccion" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <h3>En espera de asignación</h3>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                <input type="checkbox" name="enEsperaPosicion" id="enEsperaPosicion" value="1" checked onclick="toogleMensajeEsperaPosicion()">
                                                                    <label for="enEsperaPosicion">Mostrar la posición en la cual se encuentra en espera de asignación</label>
                                                                </div>                                                                
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>Tiempo de cada cuanto se muestra el mensaje de espera</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm" name="enEsperaTiempo" id="enEsperaTiempo" style="width: 20%;" value="1" onkeyup="analizarTiempoTimeout('enEsperaTiempo')">
                                                                    <span class="input-group-addon pull-left" style="border: none"><b>Minutos</b></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label>Mensajes sin agentes disponibles</label>
                                                                <div class="form-group">
                                                                    <input type="text" name="enEsperaMensaje" id="enEsperaMensaje" class="form-control input-sm" value="Por favor espera mientras tenemos agentes disponibles.">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="">Enviar a</label>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="enEsperaEnviarBot" id="enEsperaEnviarBot" value="1" onclick="checkstate('enEsperaEnviarBot')">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar al bot</label>
                                                                <select class="form-control input-sm" name="enEsperaBot" id="enEsperaBot" onchange="buscarSeccionesBot('enEsperaBot', 'enEsperaSeccion')" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar a la sección del Bot</label>
                                                                <select class="form-control input-sm" data-id="0" name="enEsperaSeccion" id="enEsperaSeccion" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label>Cantidad de veces que se enviara el mensaje de espera</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm" name="cantMaxMensajeEspera" id="cantMaxMensajeEspera" style="width: 22%;" value="1" onkeyup="analizarTiempoTimeout('cantMaxMensajeEspera')">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <h3>Cierre del chat cuando alcanza el tiempo máximo de asignación</h3>
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <label><?php echo $label_chat_tiempo_maximo;?></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control input-sm" name="maximoAsignacionTiempo" id="maximoAsignacionTiempo" style="width: 20%;" value="10" onkeyup="analizarTiempoTimeout('maximoAsignacionTiempo')">
                                                                    <span class="input-group-addon pull-left" style="border: none"><b>Minutos</b></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label>Tiempo de Asignación</label>
                                                                <div class="form-group">
                                                                    <input type="text" name="maximoAsignacionMensaje" id="maximoAsignacionMensaje" class="form-control input-sm" value="Nuestros agentes continúan ocupados, por favor intenta de nuevo mas tarde.">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="">Enviar a</label>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="maximoAsignacionEnviarBot" id="maximoAsignacionEnviarBot" value="1" onclick="checkstate('maximoAsignacionEnviarBot')">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar al bot</label>
                                                                <select class="form-control input-sm" name="maximoAsignacionBot" id="maximoAsignacionBot" onchange="buscarSeccionesBot('maximoAsignacionBot', 'maximoAsignacionSeccion')" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar a la sección del Bot</label>
                                                                <select class="form-control input-sm" data-id="0" name="maximoAsignacionSeccion" id="maximoAsignacionSeccion" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <h3>Cierre del chat cuando el agente esta inactivo</h3>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Activar cierre de chat</label>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="inactividadAgenteActivar" id="inactividadAgenteActivar" value="1" onclick="checkstate('inactividadAgenteTimeout')" checked>
                                                                </div>
                                                                <span>
                                                                    Si usted configura el mismo tiempo de timeout para cliente y agente se ejecutara primero el de agente.
                                                                </span>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <!-- <label><?php echo $tiempo_maximo_inactividad_agente;?></label> -->
                                                                <label>Tiempo de cierre</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="inactividadAgenteTiempo" id="inactividadAgenteTiempo" class="form-control input-sm" value="60" style="width: 20%;" onkeyup="analizarTiempoTimeout('inactividadAgenteTiempo')">
                                                                    <span class="input-group-addon pull-left" style="border: none"><b>Minutos</b></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label><?php echo $frase_inactividad_agente;?></label>
                                                                <div class="form-group">
                                                                    <input type="text" name="inactividadAgenteMensaje" id="inactividadAgenteMensaje" class="form-control input-sm" value="Lo sentimos, la comunicación dejó de estar activa, intenta nuevamente.">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="">Enviar a</label>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="inactividadAgenteEnviarBot" id="inactividadAgenteEnviarBot" value="1" onclick="checkstate('inactividadAgenteEnviarBot')">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar al bot</label>
                                                                <select class="form-control input-sm" name="inactividadAgenteBot" id="inactividadAgenteBot" onchange="buscarSeccionesBot('inactividadAgenteBot', 'inactividadAgenteSeccion')" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar a la sección del Bot</label>
                                                                <select class="form-control input-sm" data-id="0" name="inactividadAgenteSeccion" id="inactividadAgenteSeccion" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <h3>Cierre del chat cuando el cliente esta inactivo</h3>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Activar cierre de chat</label>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="inactividadClienteActivar" id="inactividadClienteActivar" value="1" onclick="checkstate('inactividadClienteTimeout')" checked>
                                                                </div>
                                                                <span>
                                                                    Si usted configura el mismo tiempo de timeout para cliente y agente se ejecutara primero el de agente.
                                                                </span>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <!-- <label><?php echo $tiempo_maximo_inactividad_cliente;?></label> -->
                                                                <label>Tiempo de cierre</label>
                                                                <div class="input-group">
                                                                <input type="text" name="inactividadClienteTiempo" id="inactividadClienteTiempo" class="form-control input-sm" style="width: 20%;" value="60" onkeyup="analizarTiempoTimeout('inactividadClienteTiempo')">
                                                                    <span class="input-group-addon pull-left" style="border: none"><b>Minutos</b></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label><?php echo $frase_inactividad_cliente;?></label>
                                                                <div class="form-group">
                                                                    <input type="text" name="inactividadClienteMensaje" id="inactividadClienteMensaje" class="form-control input-sm" value="Seguramente te ocupaste, porque dejaste de hablarnos. No importa, cuando lo desees puedes comunicarte con nosotros nuevamente.">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label for="">Enviar a</label>
                                                                <div class="form-group">
                                                                    <input type="checkbox" name="inactividadClienteEnviarBot" id="inactividadClienteEnviarBot" value="1" onclick="checkstate('inactividadClienteEnviarBot')">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar al bot</label>
                                                                <select class="form-control input-sm" name="inactividadClienteBot" id="inactividadClienteBot" onchange="buscarSeccionesBot('inactividadClienteBot', 'inactividadClienteSeccion')" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">Enviar a la sección del Bot</label>
                                                                <select class="form-control input-sm" data-id="0" name="inactividadClienteSeccion" id="inactividadClienteSeccion" disabled>
                                                                    <option value="0">Seleccione</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_32">
                                                        <?php echo $str_title_correo;?>
                                                    </a>
                                                </h4>
                                            </div>

                                            <div id="s_32" class="panel-collapse collapse ">
                                                <!-- <div class="row" hidden>

                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="ActivaMailCampana" id="ActivaMailCampana">
                                                                    <strong><?php echo $title_mail_enabled;?></strong>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label for=""><?php echo $title_mail_cuenta;?></label>
                                                            <select name="mailCuentaCorreo" id="mailCuentaCorreo" class="form-control input-sm str_Select2" style="width: 100%;">
                                                                <option value="0" selected>Seleccione</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-xs-6">
                                                        <div class="form-group">
                                                            <label>Campo correo para buscar automáticamente</label>
                                                            <select name="emailCampoBusqueda" id="emailCampoBusqueda" class="form-control input-sm">
                                                                <option value="0" selected>Seleccione</option>
                                                                <?php
                                                                    $lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCampanGuion['CAMPAN_ConsInte__GUION__Pob_b']." AND (PREGUN_Tipo______b = 1 OR PREGUN_Tipo______b = 14) AND PREGUN_Texto_____b NOT LIKE '%_DY%' ORDER BY PREGUN_Texto_____b ASC";
                                                                    $res_Resultado = $mysqli->query($lsql);
                                                                    while ($key = $res_Resultado->fetch_object()) {
                                                                        echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                    }   
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 col-xs-12 table-responsive">
                                                        <table class="table" id="correoCondiciones">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th><?php echo $title_mail_tipo_condicion;?></th>
                                                                    <th><?php echo $title_mail_condicion;?></th>
                                                                </tr>    
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                        <div class="pull-right" style="margin-top:5px">
                                                            <button type="button" id="nuevaCondicionCorreo" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agregar condición</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="configMensajeBienvenida" id="configMensajeBienvenida" onchange="configurarMensajeBienvenida(this)">
                                                                    Configurar mensaje de bienvenida
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div id="seccionMensajeBienvenida" class="col-md-12" style="display: none;">
                                                        <div class="col-md-9">
                                                            <textarea name="dyTr_correoMensajeBienvenida" id="dyTr_correoMensajeBienvenida" cols="30" rows="10"></textarea>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <table class="table-bordered table-hover table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Variables dinámicas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>${fecha}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>${hora}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>${id_comunicacion}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                                </div> -->

                                                <div class="row" style="margin-top:30px">
                                                    <div class="col-md-12 col-xs-12">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="configFirma" id="configFirma" onchange="configurarFirma(this)">
                                                                    Configurar firma de cada correo
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div id="seccionFirmaCorreo" class="col-md-12" style="display: none;">
                                                        <div class="col-md-9">
                                                            <textarea name="dyTr_firmaCorreo" id="dyTr_firmaCorreo" cols="10" rows="5"></textarea>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <table class="table-bordered table-hover table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Variables dinámicas</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>${agente}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>${telefono}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>${id_comunicacion}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_18">
                                                        <?php echo $str_agent_campan;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_18" class="panel-collapse collapse ">
                                                <span style="color:orange">Para que los agentes sean asignados o designados a un campaña deben reiniciar la sesión en la estación de trabajo</span>
                                                <iframe id="iframeUsuarios" src="<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan=<?php echo $dataCampanGuion['CAMPAN_ConsInte__b'];?>" style="width: 100%;height: 500px;" scrolling="no"  marginheight="0" marginwidth="0" noresize  frameborder="0" >
                                                    
                                                </iframe>
                                                <!--<table id="tablaUsuariosCampan">
                                                    
                                                </table>
                                                <div id="pagerUsuariosCampan"></div>-->
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_25">
                                                        <?php echo $str_datos_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_25" class="panel-collapse collapse ">
                                                <div class="row bg-info">
                                                    <div class="col-md-3">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12" style="text-align: center;">
                                                                    <button type="button"  data-toggle="modal" role="button" class="btn btn-app" id="cargardtaosCampanhaCompleto" title="<?php echo $str_campan_car1; ?>">
                                                                        <i class="fa fa-upload"></i> <?php echo $str_campan_car11;?>
                                                                    </button>
                                                                </div>
                                                                <!-- <div class="col-md-4" style="text-align: center;">
                                                                    <button type="button" data-toggle="modal" data-target="#filtros_campanha_delete" id="abrir_modal_delete" class="btn btn-app"  title="Administrar Registros">
                                                                        <i class="fa fa-trash-o"></i> <?php echo $str_campan_car22;?>
                                                                    </button>
                                                                </div> -->
                                                                <!-- <div class="col-md-4" style="text-align: center;">
                                                                    <button type="button" data-toggle="modal" data-target="#filtros_campanha" id="abrir_modal_admin" class="btn btn-app"  title="Administrar Registros">
                                                                        <i class="fa fa-database"></i> <?php echo $str_campan_Adm__;?>
                                                                    </button>
                                                                </div> -->
                                                            </div>
                                                            <br/>
                                                            <!--<div class="row">
                                                                <div class="col-md-6">
                                                                    <button type="button"  data-toggle="modal" data-target="#filtrosCampanha" role="button" class="btn btn-primary btn-block" onclick="javascript: cambiarTipoValor(1);">Meter datos en la campaña (que ya estaban cargados en la base de datos)
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="button"  data-toggle="modal" data-target="#filtrosCampanha" role="button" class="btn btn-primary btn-block" onclick="javascript: cambiarTipoValor(2);">Sacar datos de la campaña (manteniendolos en la base de datos)
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <br/>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <button type="button"  data-toggle="modal" data-target="#filtrosCampanha" role="button" class="btn btn-primary btn-block" onclick="javascript: cambiarTipoValor(3);">Sacar datos de la campaña y eliminarlos de la base de datos
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="button" data-toggle="modal" role="button" class="btn btn-primary btn-block" id="cargardtaosCampanhaSinMuestra" > Cargar datos y no meterlos aún en la campaña
                                                                    </button>
                                                                </div>
                                                            </div>-->
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_16">
                                                        <?php echo $str_horar_campan;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_16" class="panel-collapse collapse ">
                                               <span style="color:orange">después de un cambio de horario los agentes deben entrar y salir de la estación para que les tome el cambio</span>
                                                <div class="box-body">

                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C108" id="LblG10_C108">Lunes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C108" id="G10_C108" data-error="Before you wreck yourself"  > 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <!-- FIN DEL CAMPO SI/NO -->
                                          
                                                    </div>


                                                    <div class="col-md-4 col-xs-5">

                                          
                                                            <!-- CAMPO TIMEPICKER -->
                                                            <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-2ontrol input-sm Hora" , de l contrario seria solo un campo de texto mas -->
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C111" id="LblG10_C111">Martes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C111" id="G10_C111" data-error="Before you wreck yourself"  > 
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C114" id="LblG10_C114">Miercoles</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C114" id="G10_C114" data-error="Before you wreck yourself"  > 
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C117" id="LblG10_C117">Jueves</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C117" id="G10_C117" data-error="Before you wreck yourself"  > 
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


                                                <div class="row">
                                                

                                                    <div class="col-md-4 col-xs-2">

                                          
                                                            <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                            <div class="form-group">
                                                                <label for="G10_C120" id="LblG10_C120">Viernes</label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C120" id="G10_C120" data-error="Before you wreck yourself"  > 
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
                                        </div>

                                        <div class="panel box box-primary"  id="Seccion_Busqueda_TEL" style="display: none;">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_19">
                                                        <?php echo $campan_title_b2_;?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_19" class="panel-collapse collapse ">
                                                <table id="tablaCAMCOM">
                                                </table>
                                                <div id="pagerCAMCOM">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary"> 
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#busquedaManual" id="formBusqueda">
                                                        COMO BUSCAR AL CLIENTE
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="busquedaManual" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <!-- CONFIGURACIÓN GENERAL -->
                                                    <div id="s_31323" class="panel-collapse collapse in" aria-expanded="true">
                                                        <div class="panel box">
                                                            <div class="box-header with-border">
                                                                <h4 class="box-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_31_6" aria-expanded="false" class="collapsed">
                                                                        CONFIGURACIÓN GENERAL DE LA BÚSQUEDA
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="s_31_6" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                                                                
                                                                <div class="row">
                                                                    <div class="col-md-8 col-xs-10">
                                                                        <div class="form-group">    
                                                                            <label for="">Permitir insertar registros</label>
                                                                            <select name="insertRegistro" id="insertRegistro" class="form-control input-sm">
                                                                                <option value="-1">Si</option>
                                                                                <option value="0">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-8 col-xs-10">
                                                                        <div class="form-group">    
                                                                            <label for="">Inserción automática</label>
                                                                            <select name="insertAuto" id="insertAuto" class="form-control input-sm">
                                                                                <option value="-1">Si</option>
                                                                                <option value="0">No</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- TIPO DE BÚSQUEDA -->
                                                                <div class="row">
                                                                    <div class="col-md-6 col-xs-6">
                                                                        <div class="form-group" id="tipoBusqueda" requiredSelect > 
                                                                            <label for="G10_C76" id="LblG10_C76">Tipo de búsqueda</label>
                                                                            <select class="form-control input-sm str_Select2"  style="width: 100%;" name="G10_C76" id="G10_C76">
                                                                                <option value="0">Seleccione</option>
                                                                                <option value="1">Búsqueda manual (El agente busca usando un formulario)</option>
                                                                                <option value="2">Búsqueda por dato de origen (Número telefónico si es llamada o whatsapp, cuenta de correo si es emal, etc)</option>
                                                                                <option value="3">Búsqueda por dato solicitado al cliente (Por ejemplo si en el IVR o el chatbot se le pide el número de identificación)</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
            
                                                                <!--AQUI VAN LOS CAMPOS QUE SE USAN PARA LA BUSQUEDA POR DATO ADICIONAL-->
                                                                <div class="row">
                                                                    <div class="col-md-1"></div>
                                                                    <div class="col-md-2 col-xs-11">
                                                                        <?php 
                                                                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCampanGuion['CAMPAN_ConsInte__GUION__Pob_b']." AND (PREGUN_Tipo______b != 9 AND PREGUN_Tipo______b != 12 AND PREGUN_Tipo______b !=15 AND PREGUN_Tipo______b !=16 AND PREGUN_Tipo______b !=17) AND PREGUN_FueGener_b != 3 ;";
                                                                        ?>
                                                                        <!-- CAMPO DE TIPO GUION -->
                                                                        <div class="form-group">
                                                                            <label for="G10_C318" id="LblG10_C318">Campo 1 para buscar el registro</label>
                                                                            <select class="form-control input-sm str_Select2 Seccion_Busqueda_IVR" style="width: 100%;"  name="G10_C318" id="G10_C318">
                                                                                <option  value="0">SELECCIONE</option>
                                                                                <?php
                                                                                    $res_Resultado = $mysqli->query($Lsql);
                                                                                    while ($key = $res_Resultado->fetch_object()) {
                                                                                        echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                                    }   
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
            
                                                                    <div class="col-md-2 col-xs-11">
                                                                        <?php 
                                                                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCampanGuion['CAMPAN_ConsInte__GUION__Pob_b']." AND (PREGUN_Tipo______b != 9 AND PREGUN_Tipo______b != 12 AND PREGUN_Tipo______b !=15 AND PREGUN_Tipo______b !=16 AND PREGUN_Tipo______b !=17) AND PREGUN_FueGener_b != 3 ;";
                                                                        ?>
                                                                        <!-- CAMPO DE TIPO GUION -->
                                                                        <div class="form-group">
                                                                            <label for="G10_C319" id="LblG10_C319">Campo 2 para buscar el registro</label>
                                                                            <select class="form-control input-sm str_Select2 Seccion_Busqueda_IVR" style="width: 100%;"  name="G10_C319" id="G10_C319">
                                                                                <option  value="0">SELECCIONE</option>
                                                                                <?php
                                                                                    $res_Resultado = $mysqli->query($Lsql);
                                                                                    while ($key = $res_Resultado->fetch_object()) {
                                                                                        echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                                    }   
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
            
                                                                    <div class="col-md-2 col-xs-11">
                                                                        <?php 
                                                                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCampanGuion['CAMPAN_ConsInte__GUION__Pob_b']." AND (PREGUN_Tipo______b != 9 AND PREGUN_Tipo______b != 12 AND PREGUN_Tipo______b !=15 AND PREGUN_Tipo______b !=16 AND PREGUN_Tipo______b !=17) AND PREGUN_FueGener_b != 3 ;";
                                                                        ?>
                                                                        <!-- CAMPO DE TIPO GUION -->
                                                                        <div class="form-group">
                                                                            <label for="G10_C320" id="LblG10_C320">Campo 3 para buscar el registro</label>
                                                                            <select class="form-control input-sm str_Select2 Seccion_Busqueda_IVR" style="width: 100%;"  name="G10_C320" id="G10_C320">
                                                                                <option  value="0">SELECCIONE</option>
                                                                                <?php
                                                                                    $res_Resultado = $mysqli->query($Lsql);
                                                                                    while ($key = $res_Resultado->fetch_object()) {
                                                                                        echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                                    }   
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
            
                                                                    <div class="col-md-2 col-xs-11">
                                                                        <?php 
                                                                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCampanGuion['CAMPAN_ConsInte__GUION__Pob_b']." AND (PREGUN_Tipo______b != 9 AND PREGUN_Tipo______b != 12 AND PREGUN_Tipo______b !=15 AND PREGUN_Tipo______b !=16 AND PREGUN_Tipo______b !=17) AND PREGUN_FueGener_b != 3 ;";
                                                                        ?>
                                                                        <!-- CAMPO DE TIPO GUION -->
                                                                        <div class="form-group">
                                                                            <label for="G10_C321" id="LblG10_C321">Campo 4 para buscar el registro</label>
                                                                            <select class="form-control input-sm str_Select2 Seccion_Busqueda_IVR" style="width: 100%;"  name="G10_C321" id="G10_C321">
                                                                                <option  value="0">SELECCIONE</option>
                                                                                <?php
                                                                                    $res_Resultado = $mysqli->query($Lsql);
                                                                                    while ($key = $res_Resultado->fetch_object()) {
                                                                                        echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                                    }   
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
            
                                                                    <div class="col-md-2 col-xs-11">
                                                                        <?php 
                                                                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCampanGuion['CAMPAN_ConsInte__GUION__Pob_b']." AND (PREGUN_Tipo______b != 9 AND PREGUN_Tipo______b != 12 AND PREGUN_Tipo______b !=15 AND PREGUN_Tipo______b !=16 AND PREGUN_Tipo______b !=17) AND PREGUN_FueGener_b != 3 ;";
                                                                        ?>
                                                                        <div class="form-group">
                                                                            <label for="G10_C322" id="LblG10_C322">Campo 5 para buscar el registro</label>
                                                                            <select class="form-control input-sm str_Select2 Seccion_Busqueda_IVR" style="width: 100%;"  name="G10_C322" id="G10_C322">
                                                                                <option  value="0">SELECCIONE</option>
                                                                                <?php
                                                                                    $res_Resultado = $mysqli->query($Lsql);
                                                                                    while ($key = $res_Resultado->fetch_object()) {
                                                                                        echo "<option value='".$key->PREGUN_ConsInte__b."'>".$key->PREGUN_Texto_____b."</option>";
                                                                                    }   
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
            
                                                                <!-- ALCANCE DE LA BÚSQUEDA -->
                                                                <div class="row">
                                                                    <div class="col-md-6 col-xs-6">
                                                                        <div class="form-group groupTipoBusqueda">
                                                                            <label for="">Alcance de la búsqueda</label>
                                                                            <ul class="sidebar-menu optionsave">
                                                                                <li>
                                                                                    <div class="radio">
                                                                                        <label>
                                                                                            <input type="radio" value="1" name="TipoBusqManual">Buscar en toda la base de datos
                                                                                        </label>
                                                                                    </div>
                                                                                </li>
                                                                                <li>
                                                                                    <div class="radio">
                                                                                        <label>
                                                                                            <input type="radio" value="2" name="TipoBusqManual">Buscar sobre los registros incluidos en la campaña
                                                                                        </label>
                                                                                    </div>
                                                                                </li>
                                                                                <li>
                                                                                    <div class="radio">
                                                                                        <label>
                                                                                            <input type="radio" value="3" name="TipoBusqManual">Buscar en los registros incluidos en la campaña y asignados al agente
                                                                                        </label>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- DRAG & DROP -->
                                                    <div id="s_3133" class="panel-collapse collapse in" aria-expanded="true">
                                                        <div class="panel box">
                                                            <div class="box-header with-border">
                                                                <h4 class="box-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_31_5" aria-expanded="false" class="collapsed">
                                                                        CAMPOS DEL FORMULARIO DE BÚSQUEDA
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="s_31_5" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">

                                                                <!-- DRAG&DROP -->
                                                                <div class="form-group row" id="dragAndDrop">
                                                                    <div class="col-md-5">
                                                                        <div class="input-group">
                                                                            <input type="text" name="buscadorDisponible" id="buscadorDisponible" class="form-control">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-search"></i>
                                                                            </span>
                                                                        </div>
                                                                        <p class="text-center titulo-dragdrop" style="padding-top:8px">Campos que no están el formulario de búsqueda manual</p>
                                                                        <ul id="disponible" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                                        margin-bottom: 10px;
                                                                                        overflow: auto;   
                                                                                        -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-2 text-center" style="padding-top:100px">
                                                                        <button type="button" id="derecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">></button> <br>
                                                                        <button type="button" id="todoDerecha" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm">>></button> <br>
                                
                                                                        <button type="button" id="izquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><</button><br>
                                                                        <button type="button" id="todoIzquierda" style="width:90px; height:30px; margin-bottom:5px" class="btn btn-info btn-sm"><<</button>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <div class="input-group">
                                                                            <input type="text" name="buscadorSeleccionado" id="buscadorSeleccionado" class="form-control">
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-search"></i>
                                                                            </span>
                                                                        </div>
                                                                        <p class="text-center titulo-dragdrop" style="padding-top:8px">Campos que van a aparecer en el formulario de búsqueda manual</p>
                                                                        <ul id="seleccionado" class="nav nav-pills nav-stacked lista" style="height: 400px;max-height: 400px;
                                                                                        margin-bottom: 10px;
                                                                                        overflow: auto;   
                                                                                        -webkit-overflow-scrolling: touch;border: 1px solid #eaeaea;">
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                                <!-- CHECK DE GENERAR -->
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input type="checkbox" name="checkBusqueda" id="checkBusqueda" ><?php echo $str_message_gener1; ?>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- LABEL DE INFORMACIÓN -->
                                                                <div class="row" style="margin-bottom: 8px;">
                                                                    <div class="col-md-12">
                                                                        <span class="label label-warning" >cualquier modificación que se le haga a la búsqueda aplicará para todas las campañas que utilicen la misma base de datos</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_20" id="enlacesEntreCampos">
                                                        <?php echo $str_asoci_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_20" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <span style="color:red">Para unir campos de la base de datos con campos del formulario estos deben tener el mismo formato</span>   
                                                    <table class="table table-hover" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:45%;"><?php echo $campan_dbfor_1_ ;?></th>
                                                                <th style="width:45%;"><?php echo $campan_dbfor_2_ ;?></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="htmlCamminc">

                                                        </tbody>
                                                    </table>  
                                                    <button type="button" class="btn btn-sm btn-success pull-right"  id="NewAsociacion">
                                                        <i class="fa fa-plus">&nbsp; <?php echo $campan_dbfor_4_;?></i>
                                                    </button>   
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_27">
                                                        <?php echo $str_tipif_campan; ?>
                                                    </a>   
                                                </h4>
                                            </div>
                                            <div id="s_27" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-12 table-responsive">
                                                            <table class="table table-condensed" id="opciones">

                                                            </table>
                                                                
                                                        </div> 
                                                    </div>  
                                                    <div class="pull-right" >
                                                        <button type="button" class="btn btn-sm btn-primary" role="button" title="<?php echo $str_Lista_tipifica;?>" id="editEstados">
                                                            <i class="fa fa-edit">&nbsp;<?php echo $str_Lista_tipifica;?></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-success" role="button" title="<?php echo $str_new_opcion____;?>" id="newOpcion">
                                                            <i class="fa fa-plus">&nbsp;<?php echo $str_new_opcion____;?></i>
                                                        </button>   
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_metas">
                                                        METAS
                                                    </a>   
                                                </h4>
                                            </div>
                                            <div id="s_metas" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-xs-3">
                                                            <div class="form-group row">
                                                                <label for="tsf" class="col-sm-7 col-md-7 col-form-label">% de contactos a responder</label>
                                                                <div class="col-md-5 col-xs-5">
                                                                    <input type="number" class="form-control" id="tsf" name="tsf" value="80">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3 col-xs-3">
                                                            <div class="form-group row">
                                                                <label for="tsfs" class="col-sm-7 col-md-7 col-form-label">antes de (segundos)</label>
                                                                <div class="col-md-5 col-xs-5">
                                                                    <input type="number" class="form-control" id="tsfs" name="tsfs" value="20">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 col-xs-3">
                                                            <div class="form-group row">
                                                                <label for="tcMin" class="col-sm-8 col-md-7 col-form-label">Tiempo conversación mínimo (segundos)</label>
                                                                <div class="col-md-5 col-xs-4">
                                                                    <input type="number" class="form-control" id="tcMin"  name="tcMin" value="30">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3 col-xs-3">
                                                            <div class="form-group row">
                                                                <label for="tcMax" class="col-sm-8 col-md-7 col-form-label">Tiempo conversación máximo (segundos)</label>
                                                                <div class="col-md-5 col-xs-4">
                                                                    <input type="number" class="form-control" id="tcMax"  name="tcMax" value="180">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_audios">
                                                        CONFIGURACIÓN TEMAS TELEFONICOS                 
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_audios" class="panel-collapse collapse ">
                                                <div class="box-body">
    
                                                    <div class="row">
    
                                                        <div class="col-md-2 col-xs-4">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        AUDIO QUE ESCUCHAN LOS AGENTES AL RECIBIR LA LLAMADA 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-6 col-xs-8">
                                                            <div class="form-group">
                                                                <select class="form-control" id="G10_C329" name="G10_C329">
                                                                    <?php 
                                                                        $Lsql = "SELECT id, nombre FROM dyalogo_telefonia.dy_audios WHERE id_proyecto= (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                        $res = $mysqli->query($Lsql);
                                                                        echo "<option value='0'>AUDIO POR DEFECTO</option>";
                                                                        if($res && $res->num_rows>0){
                                                                            while ($key = $res->fetch_object()) {
                                                                                echo "<option value='".$key->id."'>".$key->nombre."</option>";
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
    
                                                    </div>
    
                                                    <div class="row">        
                                                        <div class="col-md-2 col-xs-4">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" name="G10_C328_check" id="G10_C328_check" data-error="Before you wreck yourself"  > <?php echo $campan_encuesta; ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6 col-xs-8">
                                                            <div class="form-group">
                                                                <select class="form-control" id="G10_C328" name="G10_C328">
                                                                    <?php echo $str_seleccione; ?>
                                                                    <?php 
                                                                        $Lsql = "SELECT id,nombre FROM dyalogo_telefonia.dy_encuestas where id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                        $res = $mysqli->query($Lsql);
                                                                        echo "<option value='-1'>ENCUESTA POR DEFECTO</option>";
                                                                        if($res && $res->num_rows>0){
                                                                            while ($key = $res->fetch_object()) {
                                                                                echo "<option value=".$key->id.">".$key->nombre."</option>";
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="row">
                                                    
                                                        <div class="col-md-2 col-xs-4">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        AUDIO ESPERA 
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6 col-xs-8">
                                                            <div class="form-group">
                                                                <select class="form-control" id="G10_C335" name="G10_C335">
                                                                    <?php 
                                                                        $Lsql = "SELECT id, nombre FROM dyalogo_telefonia.dy_audios WHERE id_proyecto= (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                        $res = $mysqli->query($Lsql);
                                                                        echo "<option value='0'>AUDIO POR DEFECTO</option>";
                                                                        if($res && $res->num_rows>0){
                                                                            while ($key = $res->fetch_object()) {
                                                                                echo "<option value='".$key->id."'>".$key->nombre."</option>";
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="row">
                                                        <div class="col-md-2 col-xs-4">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C330" id="G10_C330" data-error="Before you wreck yourself"  > <?php echo $campan_callback; ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xs-8">
                                                            <div class="form-group">
                                                                <select class="form-control" id="ivr_encuesta" name="ivr_encuesta">
                                                                        <?php echo $str_seleccione; ?>
                                                                        <?php 
                                                                            $Lsql = "SELECT nombre_interno_ivr FROM dyalogo_telefonia.dy_ivrs where id_proyecto = (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                            $res = $mysqli->query($Lsql);
                                                                            echo "<option value='0'>IVR POR DEFECTO</option>";
                                                                            if($res && $res->num_rows>0){
                                                                                while ($key = $res->fetch_object()) {
                                                                                    echo "<option value='ivr-".$key->nombre_interno_ivr."'>".$key->nombre_interno_ivr."</option>";
                                                                                }
                                                                            }
                                                                        ?>
                                                                </select>
                                                            </div>
                                                        </div>    
                                                    </div>
    
                                                    <div class="row">
                                                        <div class="col-md-2 col-xs-4">
                                                                <div class="form-group">
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            PRIORIDAD CAMPAÑA
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6 col-xs-8">
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control" id="pesoCampan" name="pesoCampan" value="1" maxlength="1">
                                                                </div>
                                                            </div>
                                                    </div>
    
                                                    <div class="row">
                                                    
                                                        <div class="col-md-2 col-xs-3">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        MENSAJE MIENTRAS SE ESPERA
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <select class="form-control" id="audioEspera" name="audioEspera">
                                                                    <?php 
                                                                        $Lsql = "SELECT id, nombre FROM dyalogo_telefonia.dy_audios WHERE id_proyecto= (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                        $res = $mysqli->query($Lsql);
                                                                        echo "<option value='DY_AUDIO_OFRECE_DEVOLUCION_CB'>NINGUNO</option>";
                                                                        if($res && $res->num_rows>0){
                                                                            while ($key = $res->fetch_object()) {
                                                                                echo "<option value='".$key->id."'>".$key->nombre."</option>";
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-4 col-xs-3 frecuenciaEspera" hidden>
                                                            <div class="form-group row">
                                                                <label for="frecuenciaEspera" class="col-sm-4 col-md-4">Frecuencia (seg)</label>
                                                                <div class="col-md-3 col-xs-3">
                                                                    <input type="number" id="frecuenciaEspera" name="frecuenciaEspera" class="form-control" value="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="row">
                                                    
                                                        <div class="col-md-2 col-xs-3">
                                                            <div class="form-group">
                                                                <label for=""></label>
                                                                <div class="checkbox">
                                                                    <label>
                                                                        SACAR LLAMADA DE LA COLA
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3 col-xs-3">
                                                            <div class="form-group">
                                                                <label for="accionDesborde">Acción</label>
                                                                <select class="form-control" id="accionDesborde" name="accionDesborde">
                                                                    <option value="">Seleccione</option>
                                                                    <option value="2">Pasar a una campaña</option>
                                                                    <option value="3">Reproducir grabación</option>
                                                                    <option value="4">Pasar a IVR</option>
                                                                </select>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-3 col-xs-3 campanDesborde" hidden>
                                                            <div class="form-group">
                                                                <label for="campanDesborde">Campaña</label>
                                                                <select class="form-control" id="campanDesborde" name="valorDesborde" disabled>
                                                                    <?php 
                                                                        $Lsql = "SELECT id, nombre_usuario FROM dyalogo_telefonia.dy_campanas WHERE tipo_campana= 1 AND id_proyecto= (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                        $res = $mysqli->query($Lsql);
                                                                        echo "<option value='0'>Seleccione</option>";
                                                                        if($res && $res->num_rows>0){
                                                                            while ($key = $res->fetch_object()) {
                                                                                echo "<option value='".$key->id."'>".$key->nombre_usuario."</option>";
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-3 col-xs-3 audioDesborde" hidden>
                                                            <div class="form-group">
                                                                <label for="audioDesborde">Grabación</label>
                                                                <select class="form-control" id="audioDesborde" name="valorDesborde" disabled>
                                                                    <?php 
                                                                        $Lsql = "SELECT id, nombre FROM dyalogo_telefonia.dy_audios WHERE id_proyecto= (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                        $res = $mysqli->query($Lsql);
                                                                        echo "<option value='0'>Seleccione</option>";
                                                                        if($res && $res->num_rows>0){
                                                                            while ($key = $res->fetch_object()) {
                                                                                echo "<option value='".$key->id."'>".$key->nombre."</option>";
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-3 col-xs-3 ivrDesborde" hidden>
                                                            <div class="form-group">
                                                                <label for="ivrDesborde">IVR</label>
                                                                <select class="form-control" id="ivrDesborde" name="valorDesborde" disabled>
                                                                    <?php 
                                                                        $Lsql = "SELECT id, nombre_usuario_ivr FROM dyalogo_telefonia.dy_ivrs WHERE borrado=0 AND id_proyecto= (SELECT id FROM dyalogo_telefonia.dy_proyectos WHERE id_huesped={$_SESSION['HUESPED']})";
                                                                        $res = $mysqli->query($Lsql);
                                                                        echo "<option value='0'>Seleccione</option>";
                                                                        if($res && $res->num_rows>0){
                                                                            while ($key = $res->fetch_object()) {
                                                                                echo "<option value='".$key->id."'>".$key->nombre_usuario_ivr."</option>";
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-3 col-xs-3 tiempoDesborde" hidden>
                                                            <div class="form-group row">
                                                                <label for="tiempoSalida">Tiempo máximo de espera para salir (seg)</label>
                                                                <input type="number" id="tiempoDesborde" name="tiempoDesborde" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="row">
                                                        <div class="col-md-2 col-xs-3">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        ESTRATEGIA PARA DISTRIBUIR LLAMADAS
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-6 col-xs-8">
                                                            <div class="form-group">
                                                                <select class="form-control" id="estratDistribuir" name="estratDistribuir">
                                                                    <option value="rrmemory" selected>RRMemory</option>
                                                                    <option value="ringall">Ring All</option>	
                                                                    <option value="leastrecent">Least Recent</option>	
                                                                    <option value="fewestcalls">Fewest Calls</option>	
                                                                    <option value="random">Random</option>	
                                                                    <option value="linear">Linear</option>
                                                                </select>
                                                            </div>
                                                        </div>	
                                                    </div>
    
                                                    <div class="row">
                                                        <div class="col-md-2 col-xs-4">
                                                                <div class="form-group">
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            LIMITE LLAMADAS EN COLA
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6 col-xs-8">
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control" id="llamadasCola" name="llamadasCola" value="0">
                                                                </div>
                                                            </div>
                                                    </div>
    
                                                    <div class="row">
                                                        <div class="col-md-2 col-xs-3">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        DEJAR ENTRAR A COLA CUANDO NO HAY AGENTES
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-6 col-xs-8">
                                                            <div class="form-group">
                                                                <select class="form-control" id="unirLlamadas" name="unirLlamadas">
                                                                    <option value="yes" selected>Si</option>
                                                                    <option value="no">No</option>
                                                                </select>
                                                            </div>
                                                        </div>	
                                                    </div>
    
                                                    <div class="row">
                                                        <div class="col-md-2 col-xs-3">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        SACAR LLAMADAS DE COLA CUANDO NO HAYA AGENTES
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="col-md-6 col-xs-8">
                                                            <div class="form-group">
                                                                <select class="form-control" id="dejarVacia" name="dejarVacia">
                                                                    <option value="yes">Si</option>
                                                                    <option value="no" selected>No</option>
                                                                </select>
                                                            </div>
                                                        </div>	
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_13">
                                                        <?php echo $str_avanz_campan; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_13" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <div class="row">

                                                        <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" value="-1" name="G10_C95" id="G10_C95" data-error="Before you wreck yourself"> Blend agresivo (cuelga la llamada saliente)
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--FIN DEL CAMPO SI/NO -->

                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                            <label for="G10_C333" id="LblG10_C333">SEGUNDOS MAXIMOS PARA ESTAR EN LA BUSQUEDA MANUAL</label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C333" id="G10_C333" placeholder="<?php echo $str_campan_timeOut_time_; ?>">
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-6">
                                                            <!-- CAMPO DE TIPO GUION -->
                                                            <div class="form-group">
                                                                <label for="G10_C102" id="LblG10_C102">TIEMPO MAXIMO DESPUES DE COLGAR</label>
                                                                <input class="form-control input-sm" id="G10_C102" name="G10_C102">
                                                                <span class="help-block"><?php echo $str_campan_help_timeout_;?></span>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div> 

                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="chatCantMax">CANTIDAD MÁXIMA DE CHATS POR AGENTE</label>
                                                                <input type="number" name="chat_cant_max" id="chatCantMax" class="form-control" value="3" min="1" max="10">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="chatCantMax">CANTIDAD MÁXIMA DE MAILS POR AGENTE</label>
                                                                <input type="number" name="mails_cant_max" id="mailsCantMax" class="form-control" value="3" min="1" max="10">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="tiempoMaximoEmailSinGestion">TIEMPO MÁXIMO CON EL QUE EL AGENTE PUEDE TENER UN CORREO SIN GESTIÓN (Minutos)</label>
                                                                <input type="number" name="tiempoMaximoEmailSinGestion" id="tiempoMaximoEmailSinGestion" class="form-control" value="240" min="1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 col-xs-6">
                                                            <div class="form-group">
                                                                <label for="">Tiempo mínimo de abandono para estadisticas</label>
                                                                <input type="number" name="tiempoAbandono" id="tiempoAbandono" class="form-control" value="0" maxlength="11" size="4">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row hidden">

                                                        <div class="col-md-6 col-xs-6">

                                            
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C79" id="LblG10_C79">MANEJA LIMITE DE REINTENTOS</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C79" id="G10_C79" data-error="Before you wreck yourself"  > 
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <!-- FIN DEL CAMPO SI/NO -->
                                            
                                                        </div>


                                                        <div class="col-md-6 col-xs-6">

                                            
                                                                <!-- CAMPO TIPO ENTERO -->
                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                <div class="form-group">
                                                                    <label for="G10_C80" id="LblG10_C80">LIMITE DE REINTENTOS</label>
                                                                    <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C80" id="G10_C80" placeholder="LIMITE DE REINTENTOS">
                                                                </div>
                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->
                                            
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        <div class="col-md-6 col-xs-6">

                                            
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C81" id="LblG10_C81">DETENER AUTOMÁTICAMENTE</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C81" id="G10_C81" data-error="Before you wreck yourself"  > 
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <!-- FIN DEL CAMPO SI/NO -->
                                            
                                                        </div>
                                                        

                                                        <div class="col-md-6 col-xs-6">

                                            
                                                                <!-- CAMPO TIPO ENTERO -->
                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                <div class="form-group">
                                                                    <label for="G10_C82" id="LblG10_C82">META CONTACTOS EFECTIVOS</label>
                                                                    <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C82" id="G10_C82" placeholder="META CONTACTOS EFECTIVOS">
                                                                </div>
                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->
                                            
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                    
                                                        <div class="col-md-6 col-xs-6">

                                            
                                                                <!-- CAMPO TIPO ENTERO -->
                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                <div class="form-group">
                                                                    <label for="G10_C83" id="LblG10_C83">MAXIMO DIAS A FUTURO PARA AGENDA</label>
                                                                    <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C83" id="G10_C83" placeholder="MAXIMO DIAS AGENDA">
                                                                </div>
                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->
                                            
                                                        </div>

                                                        <div class="col-md-6 col-xs-6">

                                            
                                                                <!-- CAMPO TIPO ENTERO -->
                                                                <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                                <div class="form-group">
                                                                    <label for="G10_C84" id="LblG10_C84">MAXIMO AGENDAS POR DIA</label>
                                                                    <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C84" id="G10_C84" placeholder="MAXIMO AGENDAS POR DIA">
                                                                </div>
                                                                <!-- FIN DEL CAMPO TIPO ENTERO -->
                                            
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                    
                                                        <div class="col-md-6 col-xs-6">

                                            
                                                                <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                                                                <div class="form-group">
                                                                    <label for="G10_C85" id="LblG10_C85">DETECCION DE MAQUINA</label>
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input type="checkbox" value="-1" name="G10_C85" id="G10_C85" data-error="Before you wreck yourself"  > 
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <!-- FIN DEL CAMPO SI/NO -->
                                            
                                                        </div>


                                                        <div class="col-md-6 col-xs-6">
                                                            <?php 
                                                            $str_Lsql = "SELECT  G11_ConsInte__b as id , G11_C87 FROM ".$BaseDatos_systema.".G11";
                                                            ?>
                                                            <!-- CAMPO DE TIPO GUION -->
                                                            <div class="form-group">
                                                                <label for="G10_C91" id="LblG10_C91">ACCION MAQUINA</label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C91" id="G10_C91">
                                                                    <option>NOMBRE USUARIO</option>
                                                                    <?php
                                                                        /*
                                                                            SE RECORRE LA CONSULTA QUE TRAE LOS CAMPOS DEL GUIÓN
                                                                        */
                                                                        $combo = $mysqli->query($str_Lsql);
                                                                        while($obj = $combo->fetch_object()){
                                                                            echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G11_C87)."</option>";

                                                                        }    
                                                                        
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        <div class="col-md-6 col-xs-6">
                                                            <!-- CAMPO TIPO ENTERO -->
                                                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G10_C92" id="LblG10_C92">ACELERACION</label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C92" id="G10_C92" placeholder="ACELERACION">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                        </div>
                                                        <div class="col-md-6 col-xs-6 hidden">
                                                            <!-- CAMPO TIPO ENTERO -->
                                                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G10_C93" id="LblG10_C93">CANTIDAD REGISTROS AISGNACION DINAMIMCA</label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C93" id="G10_C93" placeholder="CANTIDAD REGISTROS AISGNACION DINAMIMCA">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO ENTERO -->
                                                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                            <div class="form-group">
                                                                <label for="G10_C94" id="LblG10_C94">TIEMPO PREVIEW</label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C94" id="G10_C94" placeholder="TIEMPO PREVIEW">
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        
                                                        <div class="col-md-6 col-xs-6" style="display: none;">
                                                            <!-- CAMPO DE TIPO GUION -->
                                                            <div class="form-group">
                                                                <label for="G10_C98" id="LblG10_C98">TIPIFICAICON BLEND</label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  name="G10_C98" id="G10_C98">
                                                                </select>
                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div>
                                                    </div>
                                                    <div class="row hidden" style="display: none;">
                                                        <div class="col-md-12 col-xs-12">
                                                            <!-- CAMPO TIPO ENTERO -->
                                                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                                                            <!--
                                                            <div class="form-group">
                                                                <label for="G10_C99" id="LblG10_C99">TIEMPO DE ESPERA ENTRE LLAMADAS</label>
                                                                <input type="text" class="form-control input-sm Numerico" value=""  name="G10_C99" id="G10_C99" placeholder="TIEMPO DE ESPERA ENTRE LLAMADAS">
                                                            </div>
                                                            -->
                                                            <!-- FIN DEL CAMPO TIPO ENTERO -->
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        <div class="col-md-6 col-xs-6 hidden">
                                                            <!-- CAMPO DE TIPO GUION -->
                                                            <div class="form-group">
                                                                <label for="G10_C103" id="LblG10_C103">CAMPO TIPIFICACION</label>
                                                                <select class="form-control input-sm str_Select2" style="width: 100%;"  id="G10_C103">

                                                                </select>
                                                                

                                                            </div>
                                                            <!-- FIN DEL CAMPO TIPO LISTA -->
                                                        </div>    
                                                                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- plantillas respuesta -->
                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_plantillas">
                                                        PLANTILLAS DE RESPUESTAS
                                                    </a>   
                                                </h4>
                                            </div>
                                            <div id="s_plantillas" class="panel-collapse collapse ">
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="">Seleccionar la plantilla a usar</label>
                                                                    <select name="G10_C336" id="listaPlantilla" class="form-control select2">
                                                                        <option value="0">Seleccione</option>
                                                                    </select>
                                                                    <a href="#" data-toggle="modal" data-target="#modalPlantillas" cmpo="0" id="newPlantilla" class="pull-left">Nueva Lista</a>
                                                                    <a href="#" data-toggle="modal" data-target="#modalPlantillas" cmpo="0" id="editPlantilla" hidden class="pull-right">Editar Lista</a>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_reportes">
                                                        REPORTES
                                                    </a>   
                                                </h4>
                                            </div>
                                            <div id="s_reportes" class="panel-collapse collapse ">
                                                <iframe id="iframeReportes" src="../../../../estrategias&view=si&report=si&stepUnique=campan&estrat=<?php echo md5(clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>&paso=<?php echo $datos['ESTPAS_ConsInte__b'] ?>" style="width: 100%;height: 800px;" marginheight="0" marginwidth="0" noresize  frameborder="0" ></iframe>
                                            </div>
                                        </div>

                                        <!-- SECCION : PAGINAS INCLUIDAS -->
                                        <input type="hidden" name="id" id="hidId" value='0'>
                                        <input type="hidden" name="oper" id="oper" value='add'>
                                        <input type="hidden" name="padre" id="padre" value='<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="id_paso" id="id_paso" value='<?php if(isset($_GET['id_paso'])){ echo $_GET['id_paso']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formpadre" id="formpadre" value='<?php if(isset($_GET['formularioPadre'])){ echo $_GET['formularioPadre']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="formhijo" id="formhijo" value='<?php if(isset($_GET['formulario'])){ echo $_GET['formulario']; }else{ echo "0"; }?>' >
                                        <input type="hidden" name="poblacion" id="poblacion" value='<?php if(isset($_GET['poblacion'])){ echo $_GET['poblacion']; }else{ echo "0"; }?>' >
                                    </div>


                                        <div class="panel box box-primary callback" style="display:none;">
                                            <div class="box-header with-border">
                                                <h4 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#s_callback" class="recargarGrillas">
                                                        CallBack
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="s_callback" class="panel-collapse collapse">
                                                <table class="table table-hover table-bordered" id="tablaDatosDetallesscallback" width="100%">
                                                </table>
                                                <div id="pagerDetalles0">
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
    </section>
</div>

<?php 
    $str_Lsql = 'SELECT G10_ConsInte__b, G10_C73 , G10_C74 , G5_C311 FROM '.$BaseDatos_systema.'.G10 JOIN '.$BaseDatos_systema.'.G5 ON G5_ConsInte__b = G10_C73 JOIN '.$BaseDatos_systema.'.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b ='.$_GET['id_paso'];
    //echo $str_Lsql;

    $res = $mysqli->query($str_Lsql);
    $guion = $res->fetch_array();
    $idCampana = $guion['G10_ConsInte__b'];

    $LsqlEstados = "SELECT PREGUN_ConsInte__OPCION_B as OPCION_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion['G10_C74']." AND PREGUN_Texto_____b = 'ESTADO_DY';";
    $resEstados = $mysqli->query($LsqlEstados);
    $datosListas = $resEstados->fetch_array();


?>
<!-- MODAL DE PLANTILLAS DE RESPUESTA RAPIDA -->
<div class="modal fade-in" id="modalPlantillas" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat_filtros">ADMINISTRAR REGISTROS</h4>
            </div>
            <div class="modal-body">
                <form id="formPlantillas">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombre de la plantilla</label>
                                <input type="text" name="nombrePlantilla" id="nombrePlantilla" class="form-control" placeholder="Nombre de la plantilla">
                                <input type="hidden" name="idPlantilla" id="idPlantilla">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="totalPlantillas" id="totalPlantillas" value="0">
                        <div class="col-md-3 col-xs-5">
                            <div class="form-group">
                                <label>Pregunta</label>
                            </div>
                        </div>
                        <div class="col-md-8 col-xs-5">
                            <div class="form-group">
                                <label>Respuesta</label>
                            </div>
                        </div>
                        <div class="col-md-1 col-xs-2">
                            <div class="form-group">
                                <label>Accion</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="Plantillas" style="padding-left: 15px;">
                    </div>

                    <div class="row" style="margin-top:30px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" class="btn btn-sm btn-success" role="button" title="Agregar opcion" id="addPlantilla">
                                    <i class="fa fa-plus">&nbsp;<?php echo $str_new_opcion____;?></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn " type="button" id="btnSavePlantillas">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JDBD - Nuevo modal de administracion de registros -->
<div class="modal fade-in" id="filtros_campanha" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat_filtros">ADMINISTRAR REGISTROS</h4>
            </div>
            <div class="modal-body">
                <form id="consulta_campos_where">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radio_condiciones" id="radio_todos" checked class="Radio_condiciones" value="1">TODOS</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radio_condiciones" id="radio_filtro" class="Radio_condiciones" value="2">CONDICIONES</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radio_condiciones" id="Lista de registros" class="Radio_condiciones" value="3">LISTA DE REGISTROS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none;" id="div_filtros_campan">
                        <div class="col-md-9">
                            &nbsp;
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="new_filtro">Agregar Filtros</button>    
                            </div>
                        </div>
                    </div>
                     <div class="row" style="display: none;" id="div_lista_de_registros">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="campo_a_filtrar">Campo a filtrar</label>
                                <select id="campo_a_filtrar" name="campo_a_filtrar" class="form-control">
                                    <option value="0">Seleccione</option>
                                    <option value="_ConsInte__b" tipo="3" >Llave interna</option>
                                    <?php 
                                        $strListaCampos_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS name FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET["poblacion"]." AND PREGUN_Tipo______b NOT IN (10,6,11,13,8,9,12) ;";

                                        $resListaCampos_t = $mysqli->query($strListaCampos_t);
                                        if($resListaCampos_t){
                                            while ($jey = $resListaCampos_t->fetch_object()) {
                                                echo "<option value='".$jey->id."'>".$jey->name."</option>";
                                            }
                                        }
                                   ?>
                                </select> 
                            </div>
                            
                        </div>
                        <div class="col-md-6" id="div_lista_de_registros">
                            <div class="form-group">
                                <label for="listaExcell">Cargar Lista</label>
                                <input type="file" name="listaExcell" id="listaExcell" class="form-control">
                            </div>
                            
                        </div>
                    </div>
                     <div class="row">
                        <input type="hidden" name="tipoLlamado1" id="tipoLlamado1">
                        <input type="hidden" name="id_campana" id="id_campanaFiltros" >
                        <div class="col-md-12" id="FILTROS">
                            
                        </div>
                    </div>

                    <div class="row" id="div_acciones_campan">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#s_666">Acciones</a>
                                    </h4>
                                </div>
                                <div id="s_666" class="panel-collapse collapse ">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_estados_acciones">Estado</label>
                                                <select id="sel_estados_acciones" name="sel_estados_acciones" class="form-control">
                                                        
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_tipificaciones_acciones">Tipificación</label>
                                                <select id="sel_tipificaciones_acciones" name="sel_tipificaciones_acciones" class="form-control">
                                                    
                                                </select>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_tipo_reintento_acciones">Tipo de reintento</label>
                                                <select id="sel_tipo_reintento_acciones" name="sel_tipo_reintento_acciones" class="form-control">
                                                    <option value="0">Seleccione</option> 
                                                    <option value="1">REINTENTO AUTOMÁTICO</option> 
                                                    <option value="2">AGENDA</option>
                                                    <option value="3">NO REINTENTAR</option>  
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_activo_acciones">Activo / Inactivo</label>
                                                <select id="sel_activo_acciones" name="sel_activo_acciones" class="form-control">
                                                    <option value="-2">Seleccione</option>    
                                                    <option value="-1">ACTIVO</option> 
                                                    <option value="0">NO ACTIVO</option>   
                                                </select> 
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_meter_sacar">Incluir en Campa&ntilde;a / Excluir de la Campa&ntilde;a</label>
                                                <select id="sel_meter_sacar" name="sel_meter_sacar" class="form-control">
                                                    <option value="0">Seleccione</option> 
                                                    <option value="1">Incluir en Campa&ntilde;a</option> 
                                                    <option value="2">Excluir de la Campa&ntilde;a</option>   
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sel_usuarios_asignacion">Asignar a</label>
                                                <select id="sel_usuarios_asignacion" name="sel_usuarios_asignacion" class="form-control">
                                                    <option value="-2">Seleccione</option> 
                                                    <option value="-1">Dejar sin agente</option> 
                                                    <?php 
                                                        $Lsql = "SELECT USUARI_Nombre____b, USUARI_ConsInte__b FROM ".$BaseDatos_systema.".USUARI JOIN ".$BaseDatos_systema.".ASITAR ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b WHERE ASITAR_ConsInte__CAMPAN_b = ".$idCampana;
                                                        $usuarios = $mysqli->query($Lsql);
                                                        if($usuarios){
                                                            while ($jey = $usuarios->fetch_object()) {
                                                                echo "<option value='".$jey->USUARI_ConsInte__b."'>".$jey->USUARI_Nombre____b."</option>";
                                                            }
                                                        }
                                                   ?>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="conf_fechas_reintento" style="display:none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_fecha_reintento_acciones">Fecha de reintento</label>
                                                <input type="text" id="txt_fecha_reintento_acciones" name="txt_fecha_reintento_acciones" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txt_hora_reintento_acciones">Hora de reintento</label>
                                                <input type="text" id="txt_hora_reintento_acciones" name="txt_hora_reintento_acciones" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> 
            </div>
            <div class="box-footer">
                <button class="btn btn-default" type="button"  data-dismiss="modal" >Cancelar</button>
                <button class="btn-primary btn pull-right" type="button" id="aplicar_filtros">Aplicar</button>
            </div>
        </div>
    </div>
</div>
<!-- JDBD - Nuevo modal de administracion de registros -->

<div class="modal fade-in" id="cargarInformacion" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <input type="hidden" name="fallasValidacion" id="fallasValidacion" value="0">
    <input type="hidden" name="strFechaInicial_t" id="strFechaInicial_t" value="">
    <input type="hidden" name="intIdCampana_t" id="intIdCampana_t" value="">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close BorrarTabla" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_cargue"><?php echo $str_strategia_edicion.' '; ?></h4>
            </div>
            <div class="modal-body">
                <!--<img src="<?=base_url?>assets/img/clock.gif" style="text-align : center;" id="imageCarge">-->
                <div id="divIframe">
                    <!--<iframe id="frameContenedor" style="width: 100%; height: 1000px;" src="" scrolling="si"  marginheight="0" marginwidth="0" noresize  frameborder="0" onload="autofitIframe(this);">

                    </iframe>-->
                </div>
            </div>
        </div>
        <input type="hidden" name="TablaTemporal" id="TablaTemporal" value="">
    </div>
</div>


<div class="modal fade-in" id="crearCampanhasNueva" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat">Nueva Estrategia</h4>
            </div>
            <div class="modal-body">
                <form id="formuarioCargarEstoEstrart">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO TEXTO -->
                            <div class="form-group">
                                <label for="G2_C7" id="LblG2_C7">NOMBRE</label>
                                <input type="text" class="form-control input-sm" id="G2_C7" value=""  name="G2_C7"  placeholder="NOMBRE">
                            </div>
                        <!-- FIN DEL CAMPO TIPO TEXTO -->
                        </div>
                        <input type="hidden" name="G2_C5" id="G2_C5" value="<?php echo $_SESSION['HUESPED'];?>">
                        <div class="col-md-12 col-xs-12">
                            <!-- CAMPO TIPO ENTERO -->
                            <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                            <div class="form-group">
                                <label for="G2_C6" id="LblG2_C6">TIPO CAMPAÑA</label>
                                <select class="form-control "  name="G2_C6" id="G2_C6">
                                <?php

                                    $tipoStratLsql = "SELECT * FROM ".$BaseDatos_systema.".TIPO_ESTRAT";
                                    $tipoStratResu = $mysqli->query($tipoStratLsql);

                                    while ($tipoStrat = $tipoStratResu->fetch_object()) {
                                        echo "<option value='".$tipoStrat->TIPO_ESTRAT_ConsInte__b."'>".($tipoStrat->TIPO_ESTRAT_Nombre____b)."</option>";
                                    }

                                ?>
                                </select>
                            </div>
                            <!-- FIN DEL CAMPO TIPO ENTERO -->
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

<div class="modal fade-in" id="filtros_campanha_delete" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat_filtros">ELIMINAR REGISTROS</h4>
            </div>
            <div class="modal-body">
                <form id="consulta_campos_where_delete">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radio_condiciones_delete" id="radio_todos_delete" checked class="Radio_condiciones_delete" value="1">TODOS</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radio_condiciones_delete" id="radio_filtro_delete" class="Radio_condiciones_delete" value="2">CONDICIONES</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="radio_condiciones_delete" id="radio_lista_delete" class="Radio_condiciones_delete" value="3">LISTA DE REGISTROS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none;" id="div_filtros_campan_delete">
                        <div class="col-md-9">
                            &nbsp;
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="new_filtro_delete">Agregar Filtros</button>    
                            </div>
                        </div>
                    </div>
                     <div class="row" style="display: none;" id="div_lista_de_registros_delete">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="campo_a_filtrar_delete">Campo a filtrar</label>
                                <select id="campo_a_filtrar_delete" name="campo_a_filtrar_delete" class="form-control">
                                    <option value="0">Seleccione</option>
                                    <option value="_ConsInte__b" tipo="3" >Llave interna</option>
                                    <?php 
                                        $strListaCampos_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS name FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$_GET["poblacion"]." AND PREGUN_Tipo______b NOT IN (10,6,11,13,8,9,12) ;";

                                        $resListaCampos_t = $mysqli->query($strListaCampos_t);
                                        if($resListaCampos_t){
                                            while ($jey = $resListaCampos_t->fetch_object()) {
                                                echo "<option value='".$jey->id."'>".$jey->name."</option>";
                                            }
                                        }
                                   ?>
                                </select> 
                            </div>
                            
                        </div>
                        <div class="col-md-6" id="div_lista_de_registros_delete">
                            <div class="form-group">
                                <label for="listaExcell">Cargar Lista</label>
                                <input type="file" name="listaExcell_delete" id="listaExcell_delete" class="form-control">
                            </div>
                            
                        </div>
                    </div>
                     <div class="row">
                        <input type="hidden" name="tipoLlamado" id="tipoLlamado">
                        <input type="hidden" name="id_campana_delete" id="id_campanaFiltros_delete" >
                        <div class="col-md-12" id="FILTROS_delete">
                            
                        </div>
                    </div>
                    
                </form> 
            </div>
            <div class="box-footer">
                <button class="btn btn-default" type="button"  data-dismiss="modal" >Cancelar</button>
                <button class="btn-primary btn pull-right" type="button" id="aplicar_filtros_delete">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade-in" id="ModalPreguntaTipificacion" tabindex="-1" aria-labelledby="editarDatos" aria-hidden="true" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title" id="title_estrat"><?php echo $str_configuracion_LE;?></h4>
            </div>
            <div class="modal-body">
                <p style="text-align: justify;">
                    <?php echo $campan_deleteTip; ?>
                    <div class="form-group">
                        <select id="selTipificacionesEliminadas" class="form-control">
                            
                        </select>
                        <input type="hidden" name="idValorDel" id="idValorDel" value="0">
                    </div>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-primary btn " type="button" id="btnSaveEliminacionTipificaciones">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>
            </div>
        </div>
    </div>
</div>

<div  id="NuevaListaModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title"><?php echo $str_Lista_datos___; ?></h4>
            </div>
            <div class="modal-body">
                <form id="agrupador" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $str_Lista_nombre__; ?></label>
                                <input type="text" name="txtNombreLista" id="txtNombreLista" class="form-control" placeholder="<?php echo $str_Lista_nombre__; ?>">
                            </div>
                            <div class="form-group" style="display: none;">
                                <div class="checkbox">
                                    <input type="checkbox" value="1" name="checkTipificacion" id="checkTipificacion" data-error="Before you wreck yourself"  > <?php echo $str_Lista_tipifica;?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row panel">

                    </div>

                    <div class="row">
                        <div class="col-md-12" id="opcionesListaHtml">
                            
                        </div>
                    </div>
                    <input type="hidden" id="hidListaInvocada">  
                    <input type="hidden" id="operLista" name="operLista" value="add">  
                    <input type="hidden" id="idListaE" name="idListaE" value="0">    
                    <input type="hidden" id="TipoListas" name="TipoListas" value="0"> 
                </form>
            </div>
            <div class="modal-footer">
                
                <button class="btn btn-success pull-left" type="button" id="newOpcionlistas">
                    <?php echo $str_new_opcion____;?>
                </button>   
                <button class="btn-primary btn " type="button" id="guardarLista">
                    <?php echo $str_guardar;?>
                </button>
                <button class="btn btn-default pull-right" type="button"  data-dismiss="modal" >
                    <?php echo $str_cancela;?>
                </button>  
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(".BorrarTabla").click(function(){
        $.ajax({
            url    : '<?=base_url?>carga/carga_CRUD.php?EliminarTablaTemporal=true',
            type   : 'POST',
            data   : { strNombreTablaTemporal_t : $("#TablaTemporal").val() }
        });
    });

    $("#abrir_modal_admin").click(function(){
        $("#filtros_campanha").hide();
    });

    $("#abrir_modal_delete").click(function(){
        $("#filtros_campanha_delete").hide();
    });

    var idTotal = 0;
    var inicio = 51;
    var fin = 50;
    var cuantosVan = 0;
    var contador = 0;
    var contador2 = 1;
    var datosGuion = [];
    var contadorAsuntos = 1;
    var estados = '';
    var datosForms = [];
    var longitudCaminc = 0;
    /**Variables Globales */
    var opcionesCorreos = [];
    var contNuevaCondicionCorreo = 0;
    $(function(){

        $('.close').click(function(){
           borrarLogCargue();
           $("#export-errores").css('display','none');
           $("#title_cargue").html('<?php echo $str_carga;?>');
        });

        $("#campains").addClass('active');
        <?php if(!isset($_GET['id_paso'])){ ?>
        busqueda_lista_navegacion();
        $(".CargarDatos :first").click();
        <?php } ?>
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
            $("#crearCampanhasNueva").modal();
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

            $(".select2").each(function(){
                $(this).val(0).change();
            });

            $("#hidId").val(0);
            $("#h3mio").html('');
            //Le informa al crud que la operaciòn a ejecutar es insertar registro
            document.getElementById('oper').value = "add";
            before_add();

            $("#G10_C76 option").filter(function(){
                return $(this).text() == "PDS";
            }).prop('selected', true).change();


            $("#G10_C77 option").filter(function(){
                return $(this).text() == "Dinámico";
            }).prop('selected', true).change();

            $("#G10_C73").val(0).change();
            $("#G10_C74").val(0).change();
            $("#G10_C72").prop('checked', true);
            $("#G10_C80").val('5');
            $("#G10_C80").attr('disabled', true);
            $("#G10_C82").attr('disabled', true);
            $("#G10_C91").attr('disabled', true);
            $("#G10_C92").val('0');
            $("#G10_C92").attr('disabled', true);
            $("#G10_C93").val('10');
            $("#G10_C93").attr('disabled', true);
            $("#G10_C94").val('30');
            $("#G10_C94").attr('disabled', true);
            $("#G10_C98").attr('disabled', true);
            $("#G10_C99").val('0');

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

            <?php if(isset($_GET['view'])){ ?>
                //window.location.href  = "cancelar.php";
            <?php }  ?>
            $("#editarDatos").modal('hide');
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
<script type="text/javascript" src="<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_eventos_v2.js"></script>

<script type="text/javascript">
    $(function(){

        //PARA LOS CAMPOS DEL CBX
        function addAttrCbx(campo){
            $("#campanDesborde").attr('disabled',true).parent().removeAttr('requiredSelect');
            $("#audioDesborde").attr('disabled',true).parent().removeAttr('requiredSelect');
            $("#ivrDesborde").attr('disabled',true).parent().removeAttr('requiredSelect');

            $(".campanDesborde").hide();
            $(".audioDesborde").hide();
            $(".ivrDesborde").hide();

            try {
                $("#"+campo).attr('disabled',false).parent().attr('requiredSelect',true);
                $("."+campo).show();
            } catch (error) {}
        }

        $("#accionDesborde").change(function(){
            let opcion='';
            switch($(this).val()){
                case '2':
                    opcion="campanDesborde";
                    $(".tiempoDesborde").show();
                    break;
                case '3':
                    opcion="audioDesborde";
                    $(".tiempoDesborde").show();
                    break;
                case '4':
                    opcion="ivrDesborde";
                    $(".tiempoDesborde").show();
                    break;
                default:
                    $(".tiempoDesborde").hide();
                    break;
            }
            addAttrCbx(opcion);
        });

        $("#audioEspera").change(function(){
            if($(this).val() != 'DY_AUDIO_OFRECE_DEVOLUCION_CB'){
                $(".frecuenciaEspera").show();
                $("#frecuenciaEspera").show().attr('disabled',false);
            }else{
                $(".frecuenciaEspera").hide();
                $("#frecuenciaEspera").attr('disabled',true);
            }
        })
        //FIN CAMPOS CBX

        var id_campana = 0;
        $('.callback').hide();
        $("#G10_C79").change(function(){
            if($(this).is(':checked')){
                $("#G10_C80").attr('disabled', false);
            } else {
                $("#G10_C80").attr('disabled', true);
            }   
        });

        
        $("#G10_C95").change(function(){
            if($(this).is(':checked')){
                $("#G10_C98").attr('disabled', false);
            } else {
                $("#G10_C98").attr('disabled', true);
            }   
        });

        $("#G10_C81").change(function(){
            if($(this).is(':checked')){
                $("#G10_C82").attr('disabled', false);
            } else {
                $("#G10_C82").attr('disabled', true);
            }
        });

        $("#G10_C85").change(function(){
            if($(this).is(':checked')){
                $("#G10_C91").attr('disabled', false);
            } else {
                $("#G10_C91").attr('disabled', true);
            } 
        });

        $("#G10_C76").on('change', function(){
            if($(this).val() == '8'){
                $("#G10_C93").attr('disabled', true);
                $("#G10_C77").attr('disabled', true);
                $("#G10_C90").attr('disabled', false);
                $("#distribuciondelTrabajo").hide();
                $("#accionContesta").show();
            }else{
                $("#G10_C90").attr('disabled', true);
                $("#G10_C77").attr('disabled', false);
                $("#distribuciondelTrabajo").show();
                $("#accionContesta").hide();
            }


            if($(this).val() == '6' || $(this).val() == '7' || $(this).val() == '8' ){
                $("#G10_C85").attr('disabled', false);
            }else{
                $("#G10_C85").attr('disabled', true);
            }

            if($(this).val() == '7'){
                $("#G10_C92").attr('disabled', false);
            }else{
                $("#G10_C92").attr('disabled', true);
            }

            if($(this).val() == '4'){
                $("#G10_C94").attr('disabled', false);
            }else{
                $("#G10_C94").attr('disabled', true);
            }
            
        });

        $("#G10_C77").change(function(){
            if($(this).val() == '-1'){
                $("#G10_C93").attr('disabled', false);
            }else{
                $("#G10_C93").attr('disabled', true);
            }
        });
        
        $("#G10_C330").change(function(){
            if($(this).is(':checked')){
                $("#ivr_encuesta").attr('disabled',false);
            }else{
                $("#ivr_encuesta").attr('disabled',true);
            }
        });

        $("#G10_C328_check").change(function(){
            if($(this).is(':checked')){
                $("#G10_C328").attr('disabled',false);
            }else{
                $("#G10_C328").attr('disabled',true);
            }
        });

        <?php if(isset($_GET['poblacion'])){ ?>
            $("#G10_C74").val(<?php echo $_GET['poblacion'];?>);
            $("#G10_C74").val(<?php echo $_GET['poblacion'];?>).trigger("change");
        <?php } ?>


        //str_Select2 estos son los guiones
        


        $("#G10_C73").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C73").change(function(){
            var valores = $("#G10_C73 option:selected").text();
            var campos = $("#G10_C73 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G10_C74").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C74").change(function(){
            var valores = $("#G10_C74 option:selected").text();
            var campos = $("#G10_C74 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G10_C90").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C90").change(function(){
            var valores = $("#G10_C90 option:selected").text();
            var campos = $("#G10_C90 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G10_C91").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C91").change(function(){
            var valores = $("#G10_C91 option:selected").text();
            var campos = $("#G10_C91 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G10_C98").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C98").change(function(){
            var valores = $("#G10_C98 option:selected").text();
            var campos = $("#G10_C98 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G10_C101").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C101").change(function(){
            var valores = $("#G10_C101 option:selected").text();
            var campos = $("#G10_C101 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G10_C103").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C103").change(function(){
            var valores = $("#G10_C103 option:selected").text();
            var campos = $("#G10_C103 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        $("#G10_C104").select2({ 
            templateResult: function(data) {
                var r = data.text.split('|');
                var $result = $(
                    '<div class="row">' +
                         
                        '<div class="col-md-12">' + r[0] + '</div>' +
                    '</div>'
                );
                return $result;
            },
            templateSelection : function(data){
                var r = data.text.split('|');
                return r[0];
            }
        });

        $("#G10_C104").change(function(){
            var valores = $("#G10_C104 option:selected").text();
            var campos = $("#G10_C104 option:selected").attr("dinammicos");
            var r = valores.split('|');
            if(r.length > 1){

                var c = campos.split('|');
                for(i = 1; i < r.length; i++){
                    if(!$("#"+c[i]).is("select")) {
                    // the input field is not a select
                        $("#"+c[i]).val(r[i]);
                    }else{
                        var change = r[i].replace(' ', ''); 
                        $("#"+c[i]).val(change).change();
                    }
                    
                }
            }
        });
                                      

        //datepickers
        

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


        $(".horaEnvioTxt").timepicker({
            'timeFormat': 'H:i',
            'minTime': '00:00',
            'maxTime': '23:59',
            'setTime': '08:00',
            'step'  : '5',
            'showDuration': true
        });

        //Validaciones numeros Enteros
        

       // $("#G10_C70").numeric();
        
        $("#G10_C75").numeric();
        
        $("#G10_C105").numeric();
        
        $("#G10_C106").numeric();
        
        $("#G10_C107").numeric();
        
        $("#G10_C80").numeric();
        
        $("#G10_C82").numeric();
        
        $("#G10_C83").numeric();
        
        $("#G10_C84").numeric();
        
        $("#G10_C92").numeric();
        
        $("#G10_C93").numeric();
        
        $("#G10_C94").numeric();
        
        $("#G10_C99").numeric();
        
        $("#G10_C102").numeric();
        $("#G10_C333").numeric();
        

        //$("#CAMPAN_WrapTime").numeric();
        //Validaciones numeros Decimales
        //Buton gurdar
        $("#Save").click(function(){
            var booleanRes = before_save();

            if(booleanRes && requiredCampo()){

                for ( instance in CKEDITOR.instances ) {
                    CKEDITOR.instances[instance].updateElement();
                }

                let camposFormulario = JSON.stringify(cargarCamposSeleccionados());

                let form = $("#FormularioDatos");
                //Se crean un array con los datos a enviar, apartir del formulario 
                let formData = new FormData($("#FormularioDatos")[0]);
                formData.append('contador', contador);
                formData.append('contarAsuntos', contadorAsuntos);
                formData.append('contadorASociaciones', longitudCaminc);
                formData.append('contNuevaCondicionCorreo', contNuevaCondicionCorreo);
                formData.append('camposFormulario', camposFormulario);
                $.ajax({
                   url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    success: function(data){
                        if(data){
                            alertify.success("<?php echo $str_exito_campanha;?>");

                             // PRIMERO REGENERAMOS LA VISTA DE LA MUESTRA
                             $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaMuestra=si',
                                type: 'POST',
                                data: { pasoId :  '<?php echo $_GET['id_paso']; ?>' },
                                dataType:'JSON'
                            });

                            $.ajax({
                                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_extender_funcionalidad.php?generarVistaACD=si',
                                type: 'POST',
                                data: { pasoId :  '<?php echo $_GET['id_paso']; ?>' },
                                dataType:'JSON'
                            });



                            <?php if(isset($_GET['ruta']) && $_GET['ruta'] == '1'){ ?>
                                if($("#G10_C330").is(':checked')){
                                    getDataFlugograma('<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>');
                                }else{
                                    window.location.href ="<?=base_url?>modulo/flujograma/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>";
                                }
                            //window.location.href ="<?=base_url?>index.php?page=flujograma&estrategia=<?php // echo $datos['ESTPAS_ConsInte__ESTRAT_b']; ?>";
                            <?php }else{ ?>
                            window.location.href = "<?=base_url?>modulo/seleccion/estrategias/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>";
                            <?php } ?> 
                        }else{
                            //Algo paso, hay un error
                            alertify.error('<?php echo $error_de_proceso; ?>');
                        }                
                    },
                    //si ha ocurrido un error
                    error: function(){
                        after_save_error();
                        alertify.error('<?php echo $error_de_red; ?>');
                    },
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                    },
                    complete : function(){
                        $.unblockUI();
                    }

                });
            }else{
                booleanRes === false ? alertify.error("<?php echo $str_label_error_horario;?>") : false
            }
        });
    });

    function upCampanCallback(id,idEstpas){
        //PONER EL ID DE LA CAMPAÑA ENTRANTE EN EL CAMPO CAMPAN_ConsInte_Callback_b DE LA CAMPAÑA QUE ACABAMOS DE CREAR
        $.ajax({
            url: '<?=$url_crud_extender?>?insertarCallback',  
            type: 'POST',
            data: {idcampan:$("#hidId").val(),idCallback:id},
            dataType:'json',
            //una vez finalizado correctamente
            success: function(data){
                if(data.estado){
                    $.ajax({
                        url: '<?=$url_crud?>',  
                        type: 'POST',
                        data: {callPersistir:'si',id_estpas:$("#id_estpas").val()},
                        //una vez finalizado correctamente
                        success: function(data){
                            //saveFlugograma('<?php //echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']); ?>',data.data);
                            swal({
                                title: 'Callback configurado',
                                text: "Como activaste el call back, al cerrar este mensaje serás enviado a la configuración de la campaña saliente de devolución de llamadas, para que allá definas por que troncal telefónica deben salir esas llamadas.",
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'CONTINUAR!',
                                closeOnConfirm: true,
                                closeOnCancel: true     
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    window.location.href = '<?=base_url?>modulo/ruta/campan/'+idEstpas+'/<?php echo $_GET['poblacion']; ?>/1&callback';
                                }
                            });
                        },
                        //si ha ocurrido un error
                        error: function(){
                            after_save_error();
                            alertify.error('<?php echo $error_de_red; ?>');
                        },
                        beforeSend : function(){
                            $.blockUI({ 
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                        },
                        complete : function(){
                            $.unblockUI();
                        }
                    });
                }else{
                    alertify.error("No se pudo configurar el callback");
                }
            }
        });
    }

    function addCampan(estrategia,poblacion,idEstpas){
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G10/G10_CRUD.php?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?>',  
            type: 'POST',
            data: {G10_C71:'callback_entrante_'+idEstpas,G2_C5:<?=$_SESSION['HUESPED']?>,id_paso:idEstpas,G10_C72:'-1',G10_C74:<?=$_GET['poblacion']?>,G10_C73:$("#G10_C73").val(),G10_C73B:'0',oper:'add',byCallback:'si',campanEntrante:$("#hidId").val()},
            dataType:'json',
            //una vez finalizado correctamente
            success: function(data){
                //PONER EL ID DE LA CAMPAÑA ENTRANTE EN EL CAMPO CAMPAN_ConsInte_Callback_b DE LA CAMPAÑA QUE ACABAMOS DE CREAR
                upCampanCallback(data.id,data.estpas);
            },
            //si ha ocurrido un error
            error: function(){
                after_save_error();
                alertify.error('<?php echo $error_de_red; ?>');
            },
            beforeSend : function(){
                $.blockUI({ 
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

    function saveFlugograma(estrategia=null,data,Campan=null){
        $.ajax({
            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',  
            type: 'POST',
            data: { mySavedModel : JSON.stringify(data) , id_estrategia : estrategia , guardar_flugrama : 'SI' , poblacion : '<?=$_GET['poblacion']?>',byCallback:'si'},
            success : function(data){
                if(Campan != null){
                    addCampan(estrategia,<?=$_GET['poblacion']?>,data);
                }
            },
            //si ha ocurrido un error
            error: function(){
                after_save_error();
                alertify.error('<?php echo $error_de_red; ?>');
            },
            beforeSend : function(){
                $.blockUI({ 
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
            },
            complete : function(){
                $.unblockUI();
            }
        });
    }

    /// Funcion para traer la información del flugograma, esto se hace para poder agregar la bola de salida al flugograma de manera dinamica
    function getDataFlugograma(estrategia){
        $.ajax({
            url:'<?=$url_crud_extender?>?getDataFlugograma',
            type:'POST',
            data:{estrategia:estrategia,campan:$("#hidId").val()},
            dataType:'json',
            success : function(data){
                if(data.estado){
                    if(data.addCallback){
                        saveFlugograma(estrategia,data.data,true);
                    }else{
                        window.location.href ="<?=base_url?>modulo/flujograma/<?php echo md5( clave_get . $datos['ESTPAS_ConsInte__ESTRAT_b']).'/'.$_GET['poblacion']; ?>";
                    }
                }else{
                    alertify.error("No se pudo configurar el callback");
                }
            },
            //si ha ocurrido un error
            error: function(){
                after_save_error();
                alertify.error('<?php echo $error_de_red; ?>');
            },
            beforeSend : function(){
                $.blockUI({ 
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
            },
            complete : function(){
                $.unblockUI();
            }

        });
    }

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

      /**
    * function que elimina el log de los registros recien cargados
    */
    function borrarLogCargue(){
         $.ajax({
                url: '<?=$url_crud;?>',
                type  : 'post',
                data: {'opcion':'borrarLogCargue'},
                dataType : 'json',
                success:function(data){
                    
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
                        

                        //$("#G10_C70").val(item.G10_C70);

                        $("#G10_C71").val(item.G10_C71);
    
                        if(item.G10_C72 == '-1'){
                           if(!$("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C73").val(item.G10_C73);
 
                        $("#G10_C73").val(item.G10_C73).trigger("change"); 

                        $("#G10_C74").val(item.G10_C74);
 
                        $("#G10_C74").val(item.G10_C74).trigger("change"); 

                        $("#G10_C75").val(item.G10_C75);

                        $("#G10_C76").val(item.G10_C76);
                        
                        $("#G10_C76").val(item.G10_C76).trigger("change"); 

                        $("#G10_C77").val(item.G10_C77);
 
                        $("#G10_C77").val(item.G10_C77).trigger("change"); 

                        $("#G10_C105").val(item.G10_C105);

                        $("#G10_C106").val(item.G10_C106);

                        $("#G10_C107").val(item.G10_C107);
    
    
                        if(item.G10_C79 == '1'){
                           if(!$("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C80").val(item.G10_C80);
    
                        if(item.G10_C81 == '1'){
                           if(!$("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C82").val(item.G10_C82);

                        $("#G10_C83").val(item.G10_C83);

                        $("#G10_C84").val(item.G10_C84);
    
                        if(item.G10_C85 == '1'){
                           if(!$("#G10_C85").is(':checked')){
                               $("#G10_C85").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C85").is(':checked')){
                               $("#G10_C85").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C90").val(item.G10_C90);
 
                        $("#G10_C90").val(item.G10_C90).trigger("change"); 

                        $("#G10_C91").val(item.G10_C91);
 
                        $("#G10_C91").val(item.G10_C91).trigger("change"); 

                        $("#G10_C92").val(item.G10_C92);

                        $("#G10_C93").val(item.G10_C93);

                        $("#G10_C94").val(item.G10_C94);
    
                        if(item.G10_C95 == '-1'){
                           if(!$("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C98").val(item.G10_C98);
 
                        $("#G10_C98").val(item.G10_C98).trigger("change"); 

                        $("#G10_C99").val(item.G10_C99);
    
                        if(item.G10_C100 == '1'){
                           if(!$("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C101").val(item.G10_C101);
 
                        $("#G10_C101").val(item.G10_C101).trigger("change"); 

                        $("#G10_C102").val(item.G10_C102);

                        $("#G10_C333").val(item.G10_C333);

                        $("#G10_C103").val(item.G10_C103);
 
                        $("#G10_C103").val(item.G10_C103).trigger("change"); 

                        $("#G10_C104").val(item.G10_C104);
 
                        $("#G10_C104").val(item.G10_C104).trigger("change"); 
    
                        if(item.G10_C108 == '1'){
                           if(!$("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', false);  
                            }
                            
                        }


                        $("#G10_C109").val(item.G10_C109);

                        $("#G10_C110").val(item.G10_C110);
    
                        if(item.G10_C111 == '1'){
                           if(!$("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C112").val(item.G10_C112);

                        $("#G10_C113").val(item.G10_C113);

                        if(item.G10_C114 == '1'){
                           if(!$("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C115").val(item.G10_C115);

                        $("#G10_C116").val(item.G10_C116);        
    
                        if(item.G10_C117 == '1'){
                           if(!$("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C118").val(item.G10_C118);

                        $("#G10_C119").val(item.G10_C119);
    
                        if(item.G10_C120 == '1'){
                           if(!$("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C121").val(item.G10_C121);

                        $("#G10_C122").val(item.G10_C122);
   
                        if(item.G10_C123 == '1'){
                           if(!$("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C124").val(item.G10_C124);

                        $("#G10_C125").val(item.G10_C125);
    
                        if(item.G10_C126 == '1'){
                           if(!$("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C127").val(item.G10_C127);

                        $("#G10_C128").val(item.G10_C128);

                        if(item.G10_C129 == '1'){
                           if(!$("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C130").val(item.G10_C130);

                        $("#G10_C131").val(item.G10_C131);                      
        
                        $("#h3mio").html(item.principal);

                        $("#id_estpas").val(item.id_estpas);

                        $("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idHuesped=<?php echo $_SESSION['HUESPED'];?>&idCampan='+ item.G10_ConsInte__b);
                    });
        

                    //Deshabilitar los campos

                    //Habilitar todos los campos para edicion
                    $('#FormularioDatos :input').each(function(){
                        $(this).attr('disabled', true);
                    });

                    /* convocar los agentes */
                   

                    //Habilidar los botones de operacion, add, editar, eliminar
                    $("#add").attr('disabled', false);
                    $("#edit").attr('disabled', false);
                    $("#delete").attr('disabled', false);

                    //Desahabiliatra los botones de salvar y seleccionar_registro
                    $("#cancel").attr('disabled', true);
                    $("#Save").attr('disabled', true);
                } 
            });
            vamosRecargaLasGrillasPorfavor(id);
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
                        

                        //$("#G10_C70").val(item.G10_C70);

                        $("#G10_C71").val(item.G10_C71);
    
                        if(item.G10_C72 == '-1'){
                           if(!$("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C72").is(':checked')){
                               $("#G10_C72").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C73").val(item.G10_C73);
 
                        $("#G10_C73").val(item.G10_C73).trigger("change"); 

                        $("#G10_C74").val(item.G10_C74);
 
                        $("#G10_C74").val(item.G10_C74).trigger("change"); 

                        $("#G10_C75").val(item.G10_C75);

                        $("#G10_C76").val(item.G10_C76);
 
                        $("#G10_C76").val(item.G10_C76).trigger("change"); 

                        $("#G10_C77").val(item.G10_C77);
 
                        $("#G10_C77").val(item.G10_C77).trigger("change"); 

                        $("#G10_C105").val(item.G10_C105);

                        $("#G10_C106").val(item.G10_C106);

                        $("#G10_C107").val(item.G10_C107);
    
    
                        if(item.G10_C79 == '-1'){
                           if(!$("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C79").is(':checked')){
                               $("#G10_C79").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C80").val(item.G10_C80);
    
                        if(item.G10_C81 == '-1'){
                           if(!$("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C81").is(':checked')){
                               $("#G10_C81").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C82").val(item.G10_C82);

                        $("#G10_C83").val(item.G10_C83);

                        $("#G10_C84").val(item.G10_C84);
    
                        if(item.G10_C85 == '-1'){
                           if(!$("#G10_C85").is(':checked')){
                               $("#G10_C85").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C85").is(':checked')){
                               $("#G10_C85").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C90").val(item.G10_C90);
 
                        $("#G10_C90").val(item.G10_C90).trigger("change"); 

                        $("#G10_C91").val(item.G10_C91);
 
                        $("#G10_C91").val(item.G10_C91).trigger("change"); 

                        $("#G10_C92").val(item.G10_C92);

                        $("#G10_C93").val(item.G10_C93);

                        $("#G10_C94").val(item.G10_C94);
    
                        if(item.G10_C95 == '-1'){
                           if(!$("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C95").is(':checked')){
                               $("#G10_C95").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C98").val(item.G10_C98);
 
                        $("#G10_C98").val(item.G10_C98).trigger("change"); 

                        $("#G10_C99").val(item.G10_C99);
    
                        if(item.G10_C100 == '-1'){
                           if(!$("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C100").is(':checked')){
                               $("#G10_C100").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C101").val(item.G10_C101);
 
                        $("#G10_C101").val(item.G10_C101).trigger("change"); 

                        $("#G10_C102").val(item.G10_C102);

                        $("#G10_C333").val(item.G10_C333);

                        $("#G10_C103").val(item.G10_C103);
 
                        $("#G10_C103").val(item.G10_C103).trigger("change"); 

                        $("#G10_C104").val(item.G10_C104);
 
                        $("#G10_C104").val(item.G10_C104).trigger("change"); 
    
                        if(item.G10_C108 == '-1'){
                           if(!$("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C108").is(':checked')){
                               $("#G10_C108").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C109").val(item.G10_C109);

                        $("#G10_C110").val(item.G10_C110);
    
                        if(item.G10_C111 == '-1'){
                           if(!$("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C111").is(':checked')){
                               $("#G10_C111").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C112").val(item.G10_C112);

                        $("#G10_C113").val(item.G10_C113);
    
                        if(item.G10_C114 == '-1'){
                           if(!$("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C114").is(':checked')){
                               $("#G10_C114").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C115").val(item.G10_C115);

                        $("#G10_C116").val(item.G10_C116);
    
                        if(item.G10_C117 == '-1'){
                           if(!$("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C117").is(':checked')){
                               $("#G10_C117").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C118").val(item.G10_C118);

                        $("#G10_C119").val(item.G10_C119);
    
                        if(item.G10_C120 == '-1'){
                           if(!$("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C120").is(':checked')){
                               $("#G10_C120").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C121").val(item.G10_C121);

                        $("#G10_C122").val(item.G10_C122);
    
                        if(item.G10_C123 == '-1'){
                           if(!$("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C123").is(':checked')){
                               $("#G10_C123").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C124").val(item.G10_C124);

                        $("#G10_C125").val(item.G10_C125);
    
                        if(item.G10_C126 == '-1'){
                           if(!$("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C126").is(':checked')){
                               $("#G10_C126").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C127").val(item.G10_C127);

                        $("#G10_C128").val(item.G10_C128);
    
                        if(item.G10_C129 == '-1'){
                           if(!$("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', true);  
                            }
                        } else {
                            if($("#G10_C129").is(':checked')){
                               $("#G10_C129").prop('checked', false);  
                            }
                            
                        }

                        $("#G10_C130").val(item.G10_C130);

                        $("#G10_C131").val(item.G10_C131);
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



    function cargarCamcom(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        var id_guion = $("#G10_C74").val();
        $("#tablaCAMCOM").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_camcom=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','<?php echo $campan_dbtel_1_;?>','<?php echo $campan_dbtel_2_;?>', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }
                
                ,
                { 
                    name:'G19_C202', 
                    index: 'G19_C202', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?obtener_nombres_campos=si&guion='+ id_guion +'&campo=G19_C202',
                        dataInit:function(el){
                        
                        }
                    }
                }
 
                ,
                {   name:'G19_C203', 
                    index:'G19_C203', 
                    width:300 ,
                    editable: true

                }
 
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerCAMCOM",
            rowList: [40,80],
            sortable: true,
            sortname: 'G19_C202',
            sortorder: 'asc',
            viewrecords: true,
            caption: '',
            editurl:"<?=$url_crud;?>?insertarDatosCamcom=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tablaCAMCOM').navGrid("#pagerCAMCOM", { add:false, del: true , edit: false });


        $('#tablaCAMCOM').inlineNav('#pagerCAMCOM',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaCAMCOM").setGridWidth($(window).width());
        }).trigger('resize');
    }


    function cargarUsuariosCampan(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tablaUsuariosCampan").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_UsuariosCampan=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','AGENTE', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }
                
                ,
                { 
                    name:'USUARI_Nombre____b', 
                    index: 'USUARI_Nombre____b', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud_extender?>?Llenar_datos_Combo_usuarios=si&campan='+idTotal,
                        dataInit:function(el){
                                
                        }
                    }
                }
 
 
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerUsuariosCampan",
            rowList: [40,80],
            sortable: true,
            sortname: 'USUARI_Nombre____b',
            sortorder: 'asc',
            viewrecords: true,
            caption: '',
            editurl:"<?=$url_crud;?>?insertarDatosUsuarioCampan=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tablaUsuariosCampan').navGrid("#pagerUsuariosCampan", { add:false, del: true , edit: false });


        $('#tablaUsuariosCampan').inlineNav('#pagerUsuariosCampan',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: false, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaCAMCOM").setGridWidth($(window).width());
        }).trigger('resize');
    }
    
    function cargarHijos_0(id){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        $.extend(true, $.jgrid.inlineEdit, {
            beforeSaveRow: function (options, rowid) {
                $("#"+ rowid +"_Padre").val(id);
                return true;
            }
        });
        var lastSels;
        $("#tablaDatosDetallesscallback").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_callback=si&id='+id,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','TIPOS DE DESTINO','TRONCAL','TRONCAL DE DESBORDE', 'padre'],
            colModel:[

                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,editable:true, 
                    editrules:{
                        required:false, 
                        edithidden:true
                    },
                    hidden:true, 
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).attr("readonly", "readonly"); 
                        } 
                    }
                }

                ,
                { 
                    name:'tipos_destino', 
                    index: 'tipos_destino', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_tipos_destino=si',
                        dataInit:function(el){
                        
                        }
                    }
                }
 
                ,
                {   name:'troncal', 
                    index:'troncal', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_troncales=si',
                        dataInit:function(el){
                        
                        }
                    }

                }
 
                ,
                {  
                    name:'troncal_desborde', 
                    index:'troncal_desborde', 
                    width:300 ,
                    editable: true, 
                    edittype:"select" , 
                    editoptions: {
                        dataUrl: '<?=$url_crud;?>?CallDatosCombo_troncales2=si',
                        dataInit:function(el){
                            
                        }
                    }
                }
 
                ,
                { 
                    name: 'Padre', 
                    index:'Padre', 
                    hidden: true , 
                    editable: true, 
                    editrules: {
                        edithidden:true
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles0",
            rowList: [40,80],
            sortable: true,
            sortname: 'tipos_destino',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'PASOS TRONCALES',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_callback=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&Id_paso="+$("#id_estpas").val(),
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            
        });
        $('#tablaDatosDetallesscallback').navGrid("#pagerDetalles0", { add:false, del: true , edit: false });


        $('#tablaDatosDetallesscallback').inlineNav('#pagerDetalles0',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            del: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        }); 

        $('#tablaDatosDetallesscallback').jqGrid('editRow', id, { keys: true,  
            aftersavefunc: function (rowid, response) { alert('after save'); }, 
            errorfunc: function (rowid, response) { alert('...we have a problem'); }  
        }); 

        $(window).bind('resize', function() {
            $("#tablaDatosDetallesscallback").setGridWidth($(window).width());
        }).trigger('resize');
    }    


    function vamosRecargaLasGrillasPorfavor(id){
        $.jgrid.gridUnload('#tablaCAMCOM');
        $.jgrid.gridUnload('#tablaDatosDetallesscallback');
        //$.jgrid.gridUnload('#tablaUsuariosCampan');  
        cargarCamcom(id);
        cargarHijos_0(id, $("#id_estpas").val());

        //cargarUsuariosCampan(id);
    }
</script>

<!-- Aqui despliego nuevas condiciones a correo -->
<script type="text/javascript">
    
    function cambioTipoCondicion(id){
        var tipo_condicion = $('#MailTipoCondicion_'+id).val();
        if(tipo_condicion != 100){
            $('#MailCondicion_'+id).attr('disabled', false);
        }else{
            $('#MailCondicion_'+id).attr('disabled', true);
        }
    }

    $(function(){
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.replace('dyTr_correoMensajeBienvenida', {
            language: 'es'
        });
    
        CKEDITOR.replace('dyTr_firmaCorreo', {
            language: 'es',
            height: '150px'
        });
    
        /**Traer las condiciones existentes */
        $.ajax({
            url    : '<?=$url_crud?>',
            type   : 'POST',
            data   : { getListaRecibeMails : true },
            dataType: 'json',
            success : function(data){
                opcionesCorreos = data.opciones;
                $("#mailCuentaCorreo").append(data.opciones);
            }
        });

        // Esta funcion es para agregar una nueva condicion
        $("#nuevaCondicionCorreo").click(function(){
            var cant = $("#correoCondiciones tbody tr").toArray().length;
            console.log(cant);
            var nuevaCondicion = '';
            nuevaCondicion += '<tr>';
            nuevaCondicion += '<td>';
            nuevaCondicion += '<select class="form-control input-sm str_Select2" onchange="" style="width: 100%;" name="operador_'+contNuevaCondicionCorreo+'" id="operador_'+contNuevaCondicionCorreo+'">'
            nuevaCondicion += '<option value="AND">&</option>';
            nuevaCondicion += '<option value="OR">O</option>';
            nuevaCondicion += '</select>'
            nuevaCondicion += '</td>';
            nuevaCondicion += '<td>';
            nuevaCondicion += '<select class="form-control input-sm str_Select2" onchange="cambioTipoCondicion('+contNuevaCondicionCorreo+')" style="width: 100%;" name="MailTipoCondicion_'+contNuevaCondicionCorreo+'" id="MailTipoCondicion_'+contNuevaCondicionCorreo+'">';
            nuevaCondicion += '<option value="100">Sin Condición</option>';
            nuevaCondicion += '<option value="1">Proviene del correo</option>';
            nuevaCondicion += '<option value="2">Proviene del dominio</option>';
            nuevaCondicion += '<option value="3">El asunto contiene</option>';
            nuevaCondicion += '<option value="4">El cuerpo contiene</option>';
            nuevaCondicion += '<option value="5">El asunto no contiene</option>';
            nuevaCondicion += '<option value="6">El cuerpo no contiene</option>';
            nuevaCondicion += '</select>';
            nuevaCondicion += '</td>';
            nuevaCondicion += '<td>';
            nuevaCondicion += '<input type="text" name="MailCondicion_'+contNuevaCondicionCorreo+'" disabled id="MailCondicion_'+contNuevaCondicionCorreo+'" class="form-control input-sm" value="" placeholder="Ingrese la condicion">';
            nuevaCondicion += '</td>';
            nuevaCondicion += '<td>';
            nuevaCondicion += '<button type="button" class="btn btn-danger btn-sm eliminarCondicion"><i class="fa fa-trash"></i></button>';
            nuevaCondicion += '</td>';
            nuevaCondicion += '</tr>';            

            $("#correoCondiciones tbody").append(nuevaCondicion);

            if(cant < 1){
                $('#operador_'+contNuevaCondicionCorreo).prop('disabled', true);
            }

            contNuevaCondicionCorreo += 1;

            $(".eliminarCondicion").click(function(){
                $(this).closest('tr').remove();
            });
                                                
        });
    });
</script>

<!-- este carga la info de la campaing -->
<script type="text/javascript">
    functionsGuion = {
        cargarDatos : function(idGuion){
            $.ajax({
                url      : '<?=$url_crud_extender?>?llenarDatosPregun=true',
                type     : 'post',
                data     : { guion  :  idGuion },
                dataType : 'json',
                success  : function(data){
                    datosGuion = data;
                    // console.log(datosGuion);
                }
            })
        },

        cargarScript : function(idGuion){
            $.ajax({
                url      : '<?=$url_crud_extender?>?llenarDatosPregun=true',
                type     : 'post',
                data     : { guion  :  idGuion },
                dataType : 'json',
                success  : function(data){
                    datosForms = data;
                    //console.log(datosGuion);
                }
            })
        }
    }

    $(document).ready(function() {

        $("a").click(function(){
            vamosRecargaLasGrillasPorfavor(idTotal);
        });

        $('#G10_C73').on('select2:select', function (e) {
            var ex = $(this).val();
            vamosRecargaLasGrillasPorfavor(idTotal);
        });

        //function para BASE DE DATOS 

        $("#G10_C74").change(function(){
            vamosRecargaLasGrillasPorfavor(idTotal);
        });

        <?php if(isset($_GET['id_paso'])){ ?>
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos_2 : 'SI', id : <?php echo $_GET['id_paso']; ?> },
                dataType : 'json',
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>'
                    });
                },
                complete : function(){
                    $.unblockUI();
                },
                success  : function(data){
             
                    if(data.length > 0){
                        //LLENAR LOS CAMPOS DEL CBX
                        fillCamposCbx(data[0].camposCbx);
                        //recorrer datos y enviarlos al formulario
                        $.each(data, function(i, item) {
                            //$("#G10_C70").val(item.G10_C70);

                            $("#G10_C71").val(item.G10_C71);
                            // $("#tituloWeb").val(item.G10_C71);

                            if(item.G10_C72 == '-1'){
                               if(!$("#G10_C72").is(':checked')){
                                   $("#G10_C72").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C72").is(':checked')){
                                   $("#G10_C72").prop('checked', false);  
                                }
                                
                            }

                            //$("#G10_C73").val(item.G10_C73);

                            $("#G10_C73").val(item.G10_C73).trigger('change.select2');

                            functionsGuion.cargarDatos(item.G10_C74);

                            functionsGuion.cargarScript(item.G10_C73);

                            $("#G10_C73").attr('disabled', true);

                            //$("#G10_C74").val(item.G10_C74);

                            $("#G10_C74").val(item.G10_C74).trigger('change.select2');

                            $("#G10_C74").attr('disabled', true);


                            $("#G10_C75").val(item.G10_C75);

                            $("#G10_C76").val(item.G10_C76);

                            $("#G10_C76").val(item.G10_C76).trigger("change"); 

                            $("#G10_C77").val(item.G10_C77);

                            $("#G10_C77").val(item.G10_C77).trigger("change"); 

                            $("#G10_C105").val(item.G10_C105);

                            $("#G10_C106").val(item.G10_C106);

                            $("#G10_C107").val(item.G10_C107);

                            if(item.G10_C79 == '1'){
                               if(!$("#G10_C79").is(':checked')){
                                   $("#G10_C79").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C79").is(':checked')){
                                   $("#G10_C79").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C80").val(item.G10_C80);

                            if(item.G10_C81 == '1'){
                               if(!$("#G10_C81").is(':checked')){
                                   $("#G10_C81").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C81").is(':checked')){
                                   $("#G10_C81").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C82").val(item.G10_C82);

                            $("#G10_C83").val(item.G10_C83);

                            $("#G10_C84").val(item.G10_C84);

                            if(item.G10_C85 == '1'){
                               if(!$("#G10_C85").is(':checked')){
                                   $("#G10_C85").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C85").is(':checked')){
                                   $("#G10_C85").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C90").val(item.G10_C90);

                            $("#G10_C90").val(item.G10_C90).trigger("change"); 

                            $("#G10_C91").val(item.G10_C91);

                            $("#G10_C91").val(item.G10_C91).trigger("change"); 

                            $("#G10_C92").val(item.G10_C92);

                            $("#G10_C93").val(item.G10_C93);

                            $("#G10_C94").val(item.G10_C94);

                            if(item.G10_C95 == '-1'){
                               if(!$("#G10_C95").is(':checked')){
                                   $("#G10_C95").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C95").is(':checked')){
                                   $("#G10_C95").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C98").val(item.G10_C98);

                            $("#G10_C98").val(item.G10_C98).trigger("change"); 

                            $("#G10_C99").val(item.G10_C99);

                            if(item.G10_C100 == '1'){
                               if(!$("#G10_C100").is(':checked')){
                                   $("#G10_C100").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C100").is(':checked')){
                                   $("#G10_C100").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C101").val(item.G10_C101);

                            $("#G10_C101").val(item.G10_C101).trigger("change"); 

                            $("#G10_C102").val(item.G10_C102);

                            $("#G10_C333").val(item.G10_C333);

                            $("#G10_C103").val(item.G10_C103);

                            $("#G10_C103").val(item.G10_C103).trigger("change"); 

                            $("#G10_C104").val(item.G10_C104);

                            $("#G10_C104").val(item.G10_C104).trigger("change"); 

                            if(item.G10_C108 == '-1'){
                               if(!$("#G10_C108").is(':checked')){
                                   $("#G10_C108").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C108").is(':checked')){
                                   $("#G10_C108").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C109").val(item.G10_C109);

                            $("#G10_C110").val(item.G10_C110);

                            if(item.G10_C111 == '-1'){
                               if(!$("#G10_C111").is(':checked')){
                                   $("#G10_C111").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C111").is(':checked')){
                                   $("#G10_C111").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C112").val(item.G10_C112);

                            $("#G10_C113").val(item.G10_C113);

                            if(item.G10_C114 == '-1'){
                               if(!$("#G10_C114").is(':checked')){
                                   $("#G10_C114").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C114").is(':checked')){
                                   $("#G10_C114").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C115").val(item.G10_C115);

                            $("#G10_C116").val(item.G10_C116);

                            if(item.G10_C117 == '-1'){
                               if(!$("#G10_C117").is(':checked')){
                                   $("#G10_C117").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C117").is(':checked')){
                                   $("#G10_C117").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C118").val(item.G10_C118);

                            $("#G10_C119").val(item.G10_C119);

                            if(item.G10_C120 == '-1'){
                               if(!$("#G10_C120").is(':checked')){
                                   $("#G10_C120").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C120").is(':checked')){
                                   $("#G10_C120").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C121").val(item.G10_C121);

                            $("#G10_C122").val(item.G10_C122);

                            if(item.G10_C123 == '-1'){
                               if(!$("#G10_C123").is(':checked')){
                                   $("#G10_C123").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C123").is(':checked')){
                                   $("#G10_C123").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C124").val(item.G10_C124);

                            $("#G10_C125").val(item.G10_C125);

                            if(item.G10_C126 == '-1'){
                               if(!$("#G10_C126").is(':checked')){
                                   $("#G10_C126").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C126").is(':checked')){
                                   $("#G10_C126").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C127").val(item.G10_C127);

                            $("#G10_C128").val(item.G10_C128);

                            if(item.G10_C129 == '-1'){
                               if(!$("#G10_C129").is(':checked')){
                                   $("#G10_C129").prop('checked', true);  
                                }
                            } else {
                                if($("#G10_C129").is(':checked')){
                                   $("#G10_C129").prop('checked', false);  
                                }
                                
                            }

                            $("#G10_C130").val(item.G10_C130);

                            $("#G10_C131").val(item.G10_C131);
                            $("#h3mio").html(item.principal);
                            idTotal = item.G10_ConsInte__b;

                            if ( $("#"+idTotal).length > 0) {
                                $("#"+idTotal).click();   
                                $("#"+idTotal).addClass('active'); 
                            }else{
                                //Si el id no existe, se selecciona el primer registro de la Lista de navegacion
                                $(".CargarDatos :first").click();
                            }

                            $("#G10_C319").val(item.G10_C319);

                            $("#G10_C319").val(item.G10_C319).trigger("change"); 

                            $("#G10_C318").val(item.G10_C318);

                            $("#G10_C318").val(item.G10_C318).trigger("change"); 

                            $("#G10_C320").val(item.G10_C320);

                            $("#G10_C320").val(item.G10_C320).trigger("change"); 

                            $("#G10_C321").val(item.G10_C321);

                            $("#G10_C321").val(item.G10_C321).trigger("change"); 

                            $("#G10_C322").val(item.G10_C322);

                            $("#G10_C322").val(item.G10_C322).trigger("change"); 
      
                            if(item.G10_C323 == '1'){
                               if(!$("#ActivaChatCampana").is(':checked')){
                                   $("#ActivaChatCampana").prop('checked', true);  
                                }
                            } else {
                                if($("#ActivaChatCampana").is(':checked')){
                                   $("#ActivaChatCampana").prop('checked', false);  
                                }
                                
                            }

                            // if(item.G10_C324 == '1'){
                            //    if(!$("#ActivaMailCampana").is(':checked')){
                            //        $("#ActivaMailCampana").prop('checked', true);  
                            //     }
                            // } else {
                            //     if($("#ActivaMailCampana").is(':checked')){
                            //        $("#ActivaMailCampana").prop('checked', false);  
                            //     }
                                
                            // }


                            if(item.G10_C328 != null){
                               if(!$("#G10_C328_check").is(':checked')){
                                   $("#G10_C328_check").prop('checked', true);
                                   $("#G10_C328").val(item.G10_C328).trigger('change');
                                }
                                $("#G10_C328").attr('disabled',false);
                                
                            } else {
                                if($("#G10_C328_check").is(':checked')){
                                   $("#G10_C328_check").prop('checked', false);
                                }
                                $("#G10_C328").attr('disabled',true);
                                
                            }

                            if(item.G10_C330 == '-1'){
                               if(!$("#G10_C330").is(':checked')){
                                    $("#G10_C330").prop('checked', true);
                                   //$('.callback').show();
                                    if(item.ivr_encuesta!=null){
                                        $("#ivr_encuesta").val(item.ivr_encuesta).trigger('change');
                                    }
                                    $("#ivr_encuesta").attr('disabled',false);
                                }
                            } else {
                                if($("#G10_C330").is(':checked')){
                                   $("#G10_C330").prop('checked', false);
                                }
                                $("#ivr_encuesta").attr('disabled',true);
                                
                            }
                            
                            if(item.G10_C335 == null){
                                $("#G10_C335").val(0).trigger('change');
                            } else {
                                $("#G10_C335").val(item.G10_C335).trigger('change');
                            }
                            
                            let idPlantillaActual=0;
                            if(item.G10_C336 == null){
                                $("#G10_C336").val(0).trigger('change');
                            } else {
                                $("#G10_C336").val(item.G10_C336).trigger('change');
                                idPlantillaActual=item.G10_C336;
                            }

                            $("#G10_C329").val(item.G10_C329);

                            $("#G10_C329").val(item.G10_C329).trigger("change");

                            
                            //CORREO
                            $("#MailCuenta").val(item.G10_C325);
                            //CORREO
                            $("#MailCuenta").val(item.G10_C325).trigger("change");

                            $("#hidId").val(item.G10_ConsInte__b);

                            $("#id_campanaFiltros").val(item.G10_ConsInte__b);
                            $("#id_campanaFiltros_delete").val(item.G10_ConsInte__b);

                            if(item.G10_ConsInte__b.length > 0){
                                vamosRecargaLasGrillasPorfavor(item.G10_ConsInte__b)
                            }else{
                                vamosRecargaLasGrillasPorfavor(0)
                            }

                            getPlantillas(idPlantillaActual);

                           // estedeaqui
                           /* $("#s_18").load('http://gdev.dyalogodev.com:8080/dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b, function() {
                              alert( "Load was performed." );
                            });*/

                           /* var url = 'http://gdev.dyalogodev.com:8080/dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b;

                            var xhr = createCORSRequest('GET', url);
                            if (!xhr) {
                                alert('CORS not supported');
                                return;
                            }

                            // Response handlers.
                            xhr.onload = function() {
                                var text = xhr.responseText;
                                alert('Response from CORS request to ' + url + ': ' + text);
                            };

                            xhr.onerror = function() {
                                alert('Woops, there was an error making the request.');
                            };

                            xhr.send();
                            
                            //$("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b);*/
                            
                        });

                        $.ajax({
                            url      : '<?=$url_crud;?>',
                            type     : 'POST',
                            data     : { InfoChat : 'SI', id : <?php echo $_GET['id_paso']; ?>, bd: <?php echo $dataCampanGuion['CAMPAN_ConsInte__GUION__Pob_b'] ?>  },
                            dataType : 'json',
                            success  : function(data){
                                
                                // Ingreso la configuracion del chat de la campana
                                if(data.bots.length > 0){

                                    let opciones = '<option value="0">Seleccione</option>';
                                    data.bots.forEach(element => {
                                        opciones += `<option value="${element.id}">${element.nombre}</option>`;
                                    });

                                    $("#cierreChatBot").html(opciones);
                                    $("#enEsperaBot").html(opciones);
                                    $("#maximoAsignacionBot").html(opciones);
                                    $("#inactividadAgenteBot").html(opciones);
                                    $("#inactividadClienteBot").html(opciones);
                                    }

                                    // lleno la informacion de los mensajes del bot
                                    if(data.existeConfigChatCampana){

                                    // -------- Agente asignado
                                    $('#FraseAgenteAsignado').val(data.configChatCampana.agente_asignado_frase);

                                    // -------- cierre chat
                                    $("#cierreChatMensaje").val(data.configChatCampana.cierre_chat_frase);
                                    if(data.configChatCampana.cierre_chat_enviar_bot == 1){
                                        $("#cierreChatEnviarBot").click();
                                    }
                                    $("#cierreChatSeccion").attr('data-id', data.configChatCampana.cierre_chat_id_autorespuesta);
                                    $("#cierreChatBot").val(data.configChatCampana.cierre_chat_id_estpas_bot).change();

                                    // -------- En espera
                                    $("#enEsperaTiempo").val(data.configChatCampana.en_espera_intervalo_mensaje);
                                    $("#cantMaxMensajeEspera").val(data.configChatCampana.cant_max_mensajes);
                                    $("#enEsperaMensaje").val(data.configChatCampana.en_espera_frase);
                                    if(data.configChatCampana.en_espera_enviar_bot == 1){
                                        $("#enEsperaEnviarBot").click();
                                    }
                                    $("#enEsperaSeccion").attr('data-id', data.configChatCampana.en_espera_id_autorespuesta);
                                    $("#enEsperaBot").val(data.configChatCampana.en_espera_id_estpas_bot).change();
                                    
                                    if(data.configChatCampana.en_espera_posicion == 1){
                                        if($("#enEsperaPosicion").is(':checked')){
                                            toogleMensajeEsperaPosicion();
                                        }else{
                                            $("#enEsperaPosicion").click();
                                        }
                                    }else{
                                        if($("#enEsperaPosicion").is(':checked')){
                                            $("#enEsperaPosicion").click();
                                        }else{
                                            toogleMensajeEsperaPosicion();
                                        }
                                    }

                                    // -------- Tiempo maximo asignacion
                                    $("#maximoAsignacionTiempo").val(data.configChatCampana.tiempo_asignacion_excedido);
                                    $("#maximoAsignacionMensaje").val(data.configChatCampana.asignacion_excedido_frase);
                                    if(data.configChatCampana.asignacion_excedido_enviar_bot == 1){
                                        $("#maximoAsignacionEnviarBot").click();
                                    }
                                    $("#maximoAsignacionSeccion").attr('data-id', data.configChatCampana.asignacion_excedido_id_autorespuesta);
                                    $("#maximoAsignacionBot").val(data.configChatCampana.asignacion_excedido_id_estpas_bot).change();

                                    // -------- Inactividad agente
                                    $("#inactividadAgenteTiempo").val(data.configChatCampana.tiempo_maximo_inactividad_agente);
                                    $("#inactividadAgenteMensaje").val(data.configChatCampana.inactividad_agente_frase);
                                    if(data.configChatCampana.inactividad_agente_enviar_bot == 1){
                                        $("#inactividadAgenteEnviarBot").click();
                                    }
                                    $("#inactividadAgenteSeccion").attr('data-id', data.configChatCampana.inactividad_agente_id_autorespuesta);
                                    $("#inactividadAgenteBot").val(data.configChatCampana.inactividad_agente_id_estpas_bot).change();

                                    // -------- Inactividad cliente
                                    $("#inactividadClienteTiempo").val(data.configChatCampana.tiempo_maximo_inactividad_cliente);
                                    $("#inactividadClienteMensaje").val(data.configChatCampana.inactividad_cliente_frase);
                                    if(data.configChatCampana.inactividad_cliente_enviar_bot == 1){
                                        $("#inactividadClienteEnviarBot").click();
                                    }
                                    $("#inactividadClienteSeccion").attr('data-id', data.configChatCampana.inactividad_cliente_id_autorespuesta);
                                    $("#inactividadClienteBot").val(data.configChatCampana.inactividad_cliente_id_estpas_bot).change();

                                    if(data.configChatCampana.activar_timeout_agente == 1){
                                        if(!$("#inactividadAgenteActivar").is(":checked")){
                                            $("#inactividadAgenteActivar").click();
                                        }
                                    }else{
                                        if($("#inactividadAgenteActivar").is(":checked")){
                                            $("#inactividadAgenteActivar").click();
                                        }
                                    }

                                    if(data.configChatCampana.activar_timeout_cliente == 1){
                                        if(!$("#inactividadClienteActivar").is(":checked")){
                                            $("#inactividadClienteActivar").click();
                                        }
                                    }else{
                                        if($("#inactividadClienteActivar").is(":checked")){
                                            $("#inactividadClienteActivar").click();
                                        }
                                    }
                                }

                                $('input[type="checkbox"].flat-red').iCheck({
                                    checkboxClass: 'icheckbox_flat-blue'
                                });
                            }
                        });

                        // Carga los datos de Email
                        $.ajax({
                            url      : '<?=$url_crud;?>',
                            type     : 'POST',
                            data     : { InfoMail : 'SI', id : <?php echo $_GET['id_paso']; ?> },
                            dataType : 'json',
                            success  : function(data){
                                
                                // $('#s_32').find('input, textarea, button, select').not("#ActivaMailCampana").attr('disabled', true);
                                // $("#ActivaMailCampana").change();

                                // var primeraCondicion = true;

                                // $.each(data.mail, function(i, item) {

                                //     var condicion = '';
                                //     condicion += '<tr>';
                                //     condicion += '<input type="hidden" name="condLis[]" id="condList_'+item.id+'" value="'+item.id+'">'
                                //     condicion += '<td>';
                                //     condicion += '<select class="form-control input-sm str_Select2" style="width: 100%;" name="operador_'+item.id+'" id="operador_'+item.id+'">'
                                //     condicion += '<option value="AND">&</option>';
                                //     condicion += '<option value="OR">O</option>';
                                //     condicion += '</select>'
                                //     condicion += '</td>';
                                //     condicion += '<td>';
                                //     condicion += '<select class="form-control input-sm str_Select2" onchange="cambioTipoCondicion('+item.id+')" style="width: 100%;" name="MailTipoCondicion_'+item.id+'" id="MailTipoCondicion_'+item.id+'">';
                                //     condicion += '<option value="100">Sin Condición</option>';
                                //     condicion += '<option value="1">Proviene del correo</option>';
                                //     condicion += '<option value="2">Proviene del dominio</option>';
                                //     condicion += '<option value="3">El asunto contiene</option>';
                                //     condicion += '<option value="4">El cuerpo contiene</option>';
                                //     condicion += '<option value="5">El asunto no contiene</option>';
                                //     condicion += '<option value="6">El cuerpo no contiene</option>';
                                //     condicion += '</select>';
                                //     condicion += '</td>';
                                //     condicion += '<td>';
                                //     condicion += '<input type="text" name="MailCondicion_'+item.id+'" disabled id="MailCondicion_'+item.id+'" class="form-control input-sm" value="" placeholder="Ingrese la condicion">';
                                //     condicion += '</td>';
                                //     condicion += '<td>';
                                //     condicion += '<button type="button" class="btn btn-danger btn-sm borrarCondicionCorreo" id="'+item.id+'"><i class="fa fa-trash"></i></button>';
                                //     condicion += '</td>';
                                //     condicion += '</tr>';       

                                //     $("#correoCondiciones tbody").append(condicion);

                                //     $('#mailCuentaCorreo').val(item.cuenta).change();
                                //     $('#MailTipoCondicion_'+item.id).val(item.filtro).change();//CORREO
                                //     $('#MailCondicion_'+item.id).val(item.condicion).change();//CORREO
                                //     $('#operador_'+item.id).val(item.operador).change();//CORREO

                                //     if(primeraCondicion){
                                //         $('#operador_'+item.id).prop('disabled', true);;
                                //         primeraCondicion = false;
                                //     }
                                // });

                                // if(data.campoBusqueda) 
                                //     $("#emailCampoBusqueda").val(data.campoBusqueda); 
                                // else 
                                //     $("#emailCampoBusqueda").val(0);

                                // if(data.checkCorreoMensaje){
                                //     $("#configMensajeBienvenida").click();
                                //     CKEDITOR.instances['dyTr_correoMensajeBienvenida'].setData(data.correoMensaje);
                                // }

                                if(data.checkFirma){
                                    $("#configFirma").click();
                                    CKEDITOR.instances['dyTr_firmaCorreo'].setData(data.dyTr_firmaCorreo);
                                }
                                
                                // $(".borrarCondicionCorreo").click(function(){
                                //     var id = $(this).attr('id');
                                //     var self = $(this);
                                //     alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
                                //         if (e) {
                                //             $.ajax({
                                //                 url     : '<?=$url_crud?>',
                                //                 type    : 'POST',
                                //                 dataType: 'json',
                                //                 data    : { deleteCondicionCorreo : true, idCondicion : id},
                                //                 beforeSend : function(){
                                //                     $.blockUI({ 
                                //                         baseZ: 2000,
                                //                         message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>'
                                //                     });
                                //                 },
                                //                 complete : function(){
                                //                     $.unblockUI();
                                //                 },
                                //                 success : function(data){
                                //                     if(data.eliminado){
                                //                         alertify.success(data.message);
                                //                         self.closest('tr').remove();
                                //                     }else{
                                //                         alertify.error(data.message);
                                //                     }
                                //                 }
                                //             });
                                //         }
                                //     }); 
                                    
                                // });
                            }
                        });

                        $.ajax({
                            url      : '<?=$url_crud;?>',
                            type     : 'POST',
                            data     : { infoAvanzado : true, pasoId : <?php echo $_GET['id_paso']; ?> },
                            dataType : 'json',
                            success: function(data){
                                $('#chatCantMax').val(data.avanzadoChat.max_chats);
                                $('#mailsCantMax').val(data.avanzadoChat.max_correos_electronicos);

                                if(data.campan){
                                    $('#tiempoMaximoEmailSinGestion').val(data.campan.correoTiempoMaxGestion);
                                }
                            }
                        });

                        // Traigo los datos del click to call
                        $.ajax({
                            url      : '<?=$url_crud;?>',
                            type     : 'POST',
                            data     : { infoClickToCall : true, pasoId : <?php echo $_GET['id_paso']; ?> },
                            dataType : 'json',
                            success: function(data){

                                if(data.clickToCall){
                                    $("#activarClickToCall").prop('checked', true);
                                    $("#activarClickToCall").change();

                                    $("#dyTr_ctcHtml").text(data.clickToCall.codigo_html);
                                    CKEDITOR.config.allowedContent = true;
                                    CKEDITOR.replace('dyTr_ctcHtml', {
                                        language: 'es',
                                        height: '150px'
                                    } );
                                    let newCtcLink = "<?php echo $url_usuarios; ?>dy_public_front/api/ctc/"+data.clickToCall.id;
                                    $("#ctcLink").val(newCtcLink);
                                }else{
                                    CKEDITOR.config.allowedContent = true;
                                    CKEDITOR.replace('dyTr_ctcHtml', {
                                        language: 'es',
                                        height: '150px'
                                    });
                                }
                            }
                        });

                        $("#oper").val('edit');

                        
                        //Deshabilitar los campo 
                    }else{
                        $("#G10_C76 option").filter(function(){
                            return $(this).text() == "PDS";
                        }).prop('selected', true).change();


                        $("#G10_C77 option").filter(function(){
                            return $(this).text() == "Dinámico";
                        }).prop('selected', true).change();

                        $("#G10_C73").val(0).change();
                        $("#G10_C74").val(0).change();
                        $("#G10_C72").prop('checked', true);
                        $("#G10_C80").val('5');
                        $("#G10_C80").attr('disabled', true);
                        $("#G10_C82").attr('disabled', true);
                        $("#G10_C91").attr('disabled', true);
                        $("#G10_C92").val('0');
                        $("#G10_C92").attr('disabled', true);
                        $("#G10_C93").val('10');
                        $("#G10_C93").attr('disabled', true);
                        $("#G10_C94").val('30');
                        $("#G10_C94").attr('disabled', true);
                        $("#G10_C98").attr('disabled', true);
                        $("#G10_C99").val('0');

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
                                                 
                } 
            });    
        <?php } ?>
    });

    function createCORSRequest(method, url) {
        var xhr = new XMLHttpRequest();
        if ("withCredentials" in xhr) {
        // XHR for Chrome/Firefox/Opera/Safari.
            xhr.open(method, url, true);
        } else if (typeof XDomainRequest != "undefined") {
        // XDomainRequest for IE.
            xhr = new XDomainRequest();
            xhr.open(method, url);
        } else {
        // CORS not supported.
            xhr = null;
        }
        return xhr;
    }

    function fillCamposCbx(data){
        for(const campo in data){
            $("#"+campo).val(data[campo]).trigger('change');
            if(campo == "audioEspera"){
                if(data[campo] == null){
                    $("#"+campo).val('DY_AUDIO_OFRECE_DEVOLUCION_CB').trigger('change');
                }
            }
        }
        //$("select[name='valorDesborde']").val(data['valorDesborde']).trigger('change');
    }
</script>

<!-- Esto es para el cargador de datos que estamso metiendo aqui -->
<script type="text/javascript">
    $(function(){
        $("#cargardtaosCampanhaCompleto").click(function(){
            $.ajax({
                url  : '<?=base_url?>mostrar_popups.php?view=cargueDatos&id_paso=<?=$_GET["id_paso"]?>&poblacion='+$("#G10_C74").val()+"&formaInvoaca=solocampan&muestra="+$("#G10_C75").val(),
                type : 'get',
                success : function(data){
                    $("#title_cargue").html("<?php echo $str_carga;?>");
                    $("#divIframe").html(data);
                    $("#cargarInformacion").modal();
                },
                beforeSend : function(){
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                    });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });

        $("#cargardtaosCampanhaSinMuestra").click(function(){
            $.ajax({
                url  : '<?=base_url?>mostrar_popups.php?view=cargueDatos&poblacion='+$("#G10_C74").val()+"&formaInvoaca=solocampan&muestra=no",
                type : 'get',
                success : function(data){
                    $("#title_cargue").html("CARGAR DATOS EN LA CAMPAÑA SIN INSERTAR EN LA MUESTRA");
                    $("#divIframe").html(data);
                    $("#cargarInformacion").modal();
                },
                beforeSend : function(){
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                    });
                },
                complete : function(){
                    $.unblockUI();
                }
            });
        });
    });
</script>


<!-- Esto es para gusrdar la estrategia y el paso -->
<script type="text/javascript">
    $(function(){
        $(".regresoCampains").click(function(){
            $("#cancel").click();
        });

        $("#btnSave_Estrat").click(function(){
            var dtao = new FormData($("#formuarioCargarEstoEstrart")[0]);
            dtao.append('oper', 'add');
            $.ajax({
                url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                type: 'POST',
                data: dtao,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data){
                    if(data){
                        var idEstrategia = data;
                        /* ahora metemos el paso y convocamos el modal del otro */
                        var mySavedModel = '{"class":"go.GraphLinksModel", "linkFromPortIdProperty":"fromPort", "linkToPortIdProperty":"toPort", "nodeDataArray":[{"category":"salPhone","text":"Llamadas salientes Simples","tipoPaso":6, "figure":"Circle","key":-6,"loc":"37.25+-22.600006103515625"}], "linkDataArray":[]}';
                         $.ajax({
                            url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G2/G2_CRUD.php',  
                            type: 'POST',
                            data: { mySavedModel : mySavedModel , id_estrategia : idEstrategia , guardar_flugrama_simple : 'SI'},
                            //una vez finalizado correctamente
                            success: function(data){
                                if(data){
                                    $("#id_estpas_mio").val(data);
                                    /* guardo el flujograma toca mostrar la campaña para crear los datos */
                                    $("#crearDefinicionNueva").modal();
                                }else{
                                //Algo paso, hay un error
                                    alertify.error('<?php echo $error_de_proceso; ?>');
                                }                
                            },
                            //si ha ocurrido un error
                            error: function(){
                                after_save_error();
                                alertify.error('<?php echo $error_de_red; ?>');
                            },
                            beforeSend : function(){
                                $.blockUI({
                                    baseZ: 2000,
                                    message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                                });
                            },
                            complete : function(){
                                $.unblockUI();
                            }
                        });
                    }
                }
            });
        });

        $("#btnSave_campains").click(function(){
            var formData = new FormData($("#formuarioCargarEsto")[0]);
            formData.append('oper', 'add');
            $.ajax({
               url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?php echo $_SESSION['IDENTIFICACION'];?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data){
                    if(data){                               
                        $.ajax({
                            url      : '<?=$url_crud;?>',
                            type     : 'POST',
                            data     : { CallDatos_2 : 'SI', id : $("#id_estpas_mio").val() },
                            dataType : 'json',
                            success  : function(data){
                                if(data.length > 0){
                                    //recorrer datos y enviarlos al formulario
                                    $.each(data, function(i, item) {
                                        //$("#G10_C70").val(item.G10_C70);

                                        $("#G10_C71").val(item.G10_C71);

                                        if(item.G10_C72 == '-1'){
                                           if(!$("#G10_C72").is(':checked')){
                                               $("#G10_C72").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C72").is(':checked')){
                                               $("#G10_C72").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C73").val(item.G10_C73);

                                        $("#G10_C73").val(item.G10_C73).trigger("change"); 

                                        $("#G10_C74").val(item.G10_C74);

                                        $("#G10_C74").val(item.G10_C74).trigger("change"); 

                                        $("#G10_C75").val(item.G10_C75);

                                        $("#G10_C76").val(item.G10_C76);

                                        $("#G10_C76").val(item.G10_C76).trigger("change"); 

                                        $("#G10_C77").val(item.G10_C77);

                                        $("#G10_C77").val(item.G10_C77).trigger("change"); 

                                        $("#G10_C105").val(item.G10_C105);

                                        $("#G10_C106").val(item.G10_C106);

                                        $("#G10_C107").val(item.G10_C107);
            

                                        if(item.G10_C79 == '1'){
                                           if(!$("#G10_C79").is(':checked')){
                                               $("#G10_C79").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C79").is(':checked')){
                                               $("#G10_C79").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C80").val(item.G10_C80);

                                        if(item.G10_C81 == '1'){
                                           if(!$("#G10_C81").is(':checked')){
                                               $("#G10_C81").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C81").is(':checked')){
                                               $("#G10_C81").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C82").val(item.G10_C82);

                                        $("#G10_C83").val(item.G10_C83);

                                        $("#G10_C84").val(item.G10_C84);

                                        if(item.G10_C85 == '1'){
                                           if(!$("#G10_C85").is(':checked')){
                                               $("#G10_C85").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C85").is(':checked')){
                                               $("#G10_C85").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C90").val(item.G10_C90);

                                        $("#G10_C90").val(item.G10_C90).trigger("change"); 

                                        $("#G10_C91").val(item.G10_C91);

                                        $("#G10_C91").val(item.G10_C91).trigger("change"); 

                                        $("#G10_C92").val(item.G10_C92);

                                        $("#G10_C93").val(item.G10_C93);

                                        $("#G10_C94").val(item.G10_C94);

                                        if(item.G10_C95 == '-1'){
                                           if(!$("#G10_C95").is(':checked')){
                                               $("#G10_C95").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C95").is(':checked')){
                                               $("#G10_C95").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C98").val(item.G10_C98);

                                        $("#G10_C98").val(item.G10_C98).trigger("change"); 

                                        $("#G10_C99").val(item.G10_C99);

                                        if(item.G10_C100 == '1'){
                                           if(!$("#G10_C100").is(':checked')){
                                               $("#G10_C100").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C100").is(':checked')){
                                               $("#G10_C100").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C101").val(item.G10_C101);

                                        $("#G10_C101").val(item.G10_C101).trigger("change"); 

                                        $("#G10_C102").val(item.G10_C102);

                                        $("#G10_C333").val(item.G10_C333);

                                        $("#G10_C103").val(item.G10_C103);

                                        $("#G10_C103").val(item.G10_C103).trigger("change"); 

                                        $("#G10_C104").val(item.G10_C104);

                                        $("#G10_C104").val(item.G10_C104).trigger("change"); 

                                        if(item.G10_C108 == '-1'){
                                           if(!$("#G10_C108").is(':checked')){
                                               $("#G10_C108").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C108").is(':checked')){
                                               $("#G10_C108").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C109").val(item.G10_C109);

                                        $("#G10_C110").val(item.G10_C110);

                                        if(item.G10_C111 == '-1'){
                                           if(!$("#G10_C111").is(':checked')){
                                               $("#G10_C111").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C111").is(':checked')){
                                               $("#G10_C111").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C112").val(item.G10_C112);

                                        $("#G10_C113").val(item.G10_C113);

                                        if(item.G10_C114 == '-1'){
                                           if(!$("#G10_C114").is(':checked')){
                                               $("#G10_C114").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C114").is(':checked')){
                                               $("#G10_C114").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C115").val(item.G10_C115);

                                        $("#G10_C116").val(item.G10_C116);

                                        if(item.G10_C117 == '-1'){
                                           if(!$("#G10_C117").is(':checked')){
                                               $("#G10_C117").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C117").is(':checked')){
                                               $("#G10_C117").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C118").val(item.G10_C118);

                                        $("#G10_C119").val(item.G10_C119);

                                        if(item.G10_C120 == '-1'){
                                           if(!$("#G10_C120").is(':checked')){
                                               $("#G10_C120").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C120").is(':checked')){
                                               $("#G10_C120").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C121").val(item.G10_C121);

                                        $("#G10_C122").val(item.G10_C122);

                                        if(item.G10_C123 == '-1'){
                                           if(!$("#G10_C123").is(':checked')){
                                               $("#G10_C123").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C123").is(':checked')){
                                               $("#G10_C123").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C124").val(item.G10_C124);

                                        $("#G10_C125").val(item.G10_C125);

                                        if(item.G10_C126 == '-1'){
                                           if(!$("#G10_C126").is(':checked')){
                                               $("#G10_C126").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C126").is(':checked')){
                                               $("#G10_C126").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C127").val(item.G10_C127);

                                        $("#G10_C128").val(item.G10_C128);

                                        if(item.G10_C129 == '-1'){
                                           if(!$("#G10_C129").is(':checked')){
                                               $("#G10_C129").prop('checked', true);  
                                            }
                                        } else {
                                            if($("#G10_C129").is(':checked')){
                                               $("#G10_C129").prop('checked', false);  
                                            }
                                            
                                        }

                                        $("#G10_C130").val(item.G10_C130);

                                        $("#G10_C131").val(item.G10_C131);
                                        $("#h3mio").html(item.principal);
                                        idTotal = item.G10_ConsInte__b;

                                        if ( $("#"+idTotal).length > 0) {
                                            //$("#"+idTotal).click();   
                                            //$("#"+idTotal).addClass('active'); 
                                        }else{
                                            //Si el id no existe, se

                                            $(".CargarDatos").each(function(){
                                                $(this).removeClass('active');
                                            });

                                            var tr= '';
                                            tr += "<tr class='CargarDatos active' id='"+item.G10_ConsInte__b+"'>";
                                            tr += "<td>";
                                            tr += "<p style='font-size:14px;'><b>"+item.G10_C71+"</b></p>";
                                            tr += "</td>";
                                            tr += "</tr>";
                                            jQuery("#tablaScroll").prepend(tr);
                                            //$(".CargarDatos :first").click();
                                        }

                                        
                                        $("#hidId").val(item.G10_ConsInte__b);

                                        if(item.G10_ConsInte__b.length > 0){
                                            vamosRecargaLasGrillasPorfavor(item.G10_ConsInte__b)
                                        }else{
                                            vamosRecargaLasGrillasPorfavor(0)
                                        }

                                        $("#iframeUsuarios").attr('src', '<?php echo $url_usuarios ?>dyalogocbx/paginas/dd/dd-usu-cam.jsf?tip=3&idUsuario=<?php echo $_SESSION['USUARICBX'];?>&idCampan='+ item.G10_ConsInte__b); 
                                        
                                    });
                                    //Deshabilitar los campo 
                                }
                            } 
                        });
                        $("#oper").val('edit');  
                        $("#crearDefinicionNueva").modal('hide');
                        $("#crearCampanhasNueva").modal('hide');      
                    }else{
                        //Algo paso, hay un error
                        alertify.error('<?php echo $error_de_proceso; ?>');
                    }                
                },
                //si ha ocurrido un error
                error: function(){
                    after_save_error();
                    alertify.error('<?php echo $error_de_red; ?>');
                },
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait_g;?>' });
                },
                complete : function(){
                    $.unblockUI();
                }

            });
        });
    });
</script>


<!-- agregar tipificaiones -->
<script type="text/javascript">
    $(function(){
    

        $("#newOpcion").click(function(){
            var cuerpo = "<tr class='' id='id_"+cuantosVan+"'>";

            cuerpo += "<td class='col-md-5'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' name='opciones_"+cuantosVan+"' class='form-control' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "<div class='col-md-2' style='display:none;'>";
            cuerpo += "<div class='form-group'>";;
            cuerpo += "<select name='Tip_NoEfe_"+cuantosVan+"' class='form-control'>";
            cuerpo += "<option value='1'><?php echo $str_opcion_tipono_; ?></option>";
            cuerpo += "<option value='2'><?php echo $str_opcion_egenda_; ?></option>";
            cuerpo += "<option value='3'><?php echo $str_opcion_norein_; ?></option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<td class='col-md-1'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' class='form-control' placeholder='' value='0' name='txtHorasMas_"+cuantosVan+"' id='txtHorasMas_"+cuantosVan+"'>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "<td class='col-md-2'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select class='form-control' name='contacto_"+cuantosVan+"'>"
            cuerpo += "<option value='1'><?php echo $str_opcion_number1; ?></option>";
            cuerpo += "<option value='2'><?php echo $str_opcion_number2; ?></option>";
            cuerpo += "<option value='3'><?php echo $str_opcion_number3; ?></option>";
            cuerpo += "<option value='4'><?php echo $str_opcion_number4; ?></option>";
            cuerpo += "<option value='5'><?php echo $str_opcion_number5; ?></option>";
            cuerpo += "<option value='6'><?php echo $str_opcion_number6; ?></option>";
            cuerpo += "<option value='7'><?php echo $str_opcion_number7; ?></option>";
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</td>";
            
            cuerpo += "<td class='col-md-3 col-xs-10'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<select name='estado_"+cuantosVan+"' class='form-control' >";
            cuerpo += estados;
            cuerpo += "</select>";
            cuerpo += "</div>";
            cuerpo += "</td>";

            cuerpo += "<td class='col-md-1 col-xs-2'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<button class='btn btn-danger btn-sm form-control deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+cuantosVan+"'><i class='fa fa-trash-o'></i></button>";
            cuerpo += "</div>";
            cuerpo += "</td>";
            cuerpo += "</tr>";
            cuantosVan++;
            contador++;
            $("#opciones").append(cuerpo);

            $(".numeroImpo").numeric();

            
            $(".deleteopcion").click(function(){
                var id = $(this).attr('id');
                $("#id_"+id).remove();
            });
            
        });
    });
</script>

<!-- Funcionalidad del validador de condiciones Datos-->
<script type="text/javascript">
    $(function(){

        $.fn.datepicker.dates['es'] = {
            days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
            daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            today: "Today",
            clear: "Clear",
            format: "yyyy-mm-dd",
            titleFormat: "yyyy-mm-dd", 
            weekStart: 0
        };
        var van = 1;
        $("#new_filtro").click(function(){
            var cuerpo = "<div class='row' id='id_"+van+"'>";
                    cuerpo += "<div class='col-md-4'>";
                        cuerpo += "<div class='form-group'>";
                            cuerpo += "<select class='form-control mi_select_pregun' name='pregun_"+van+"'  id='pregun_"+van+"' numero='"+van+"'>";
                                cuerpo += "<option value='0' tipo='3'>Seleccione</option>";
                                cuerpo += "<option value='_CoInMiPo__b' tipo='3'>Llave interna</option>";


                                $.each(datosGuion, function(i, item) {
                                    cuerpo += "<option value='"+item.PREGUN_ConsInte__b+"' tipo='"+item.PREGUN_Tipo______b+"'>"+ item.PREGUN_Texto_____b+"</option>";
                                }); 

                                cuerpo += "<option value='_Estado____b' tipo='_Estado____b'>Tipo de reintento</option>";
                                cuerpo += "<option value='_ConIntUsu_b' tipo='_ConIntUsu_b'>Usuario</option>";
                                cuerpo += "<option value='_NumeInte__b' tipo='3'>Numero de intentos</option>";
                                cuerpo += "<option value='_UltiGest__b' tipo='MONOEF'>Ultima gesti&oacute;n</option>";
                                cuerpo += "<option value='_GesMasImp_b' tipo='MONOEF'>Gesti&oacute;n mas importante</option>";
                                cuerpo += "<option value='_FecUltGes_b' tipo='5'>Fecha ultima gesti&oacute;n</option>";
                                cuerpo += "<option value='_FeGeMaIm__b' tipo='5'>Fecha gesti&oacute;n mas importante</option>";
                                cuerpo += "<option value='_ConUltGes_b' tipo='ListaCla'>Clasificaci&oacute;n ultima gesti&oacute;n</option>";
                                cuerpo += "<option value='_CoGesMaIm_b' tipo='ListaCla'>Clasificaci&oacute;n mas importante</option>";
                                cuerpo += "<option value='_Activo____b' tipo='_Activo____b'>Registro activo</option>";

                            cuerpo += "</select>";

                        cuerpo += "</div>";
                    cuerpo += "</div>";

                    cuerpo += "<div class='col-md-3'>";
                        cuerpo += "<div class='form-group'>";
                            cuerpo += "<select name='condicion_"+van+"' id='condicion_"+van+"' class='form-control'>";
                                cuerpo += "<option value='0'>Condici&oacute;n</option>";
                            cuerpo += "</select>";
                        cuerpo += "</div>";
                    cuerpo += "</div>";


                    cuerpo += "<div class='col-md-4 col-xs-10' id='valores_restableses_"+ van +"' >";
                        cuerpo += "<div class='form-group'>";
                            cuerpo += "<input type='text'  name='valor_"+van+"' id='valor_"+van+"' class='form-control' placeholder='<?php echo $filtros_valor__c; ?>'>";
                        cuerpo += "</div>";
                    cuerpo += "</div>";

                    cuerpo += "<input type='hidden' name='tipo_campo_"+van+"' id='tipo_campo_"+van+"' class='form-control'>";

                    cuerpo += "<div class='col-md-1 col-xs-2'>";
                        cuerpo += "<div class='form-group'>";
                            cuerpo += "<button class='btn btn-danger btn-sm deleteFiltro'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+van+"'><i class='fa fa-trash-o'></i></button>";
                        cuerpo += "</div>";
                    cuerpo += "</div>";

                cuerpo += "</div>";
                
                van++;

                $("#FILTROS").append(cuerpo);

                $(".mi_select_pregun").change(function(){
                    var id = $(this).attr('numero');
                    var tipo = $("#pregun_"+id+" option:selected").attr('tipo');
                    var valor = $(this).val();

                    $("#tipo_campo_"+id).val(tipo);

                    var options = "";
                    if(tipo == '1' || tipo == '2'){

                        options += "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                        options += "<option value='LIKE_1'>INICIE POR</option>";
                        options += "<option value='LIKE_2'>CONTIENE</option>";
                        options += "<option value='LIKE_3'>TERMINE EN</option>";

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }

                    if(tipo == '4' || tipo == '3'){

                        options += "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                        options += "<option value='>'>MAYOR QUE</option>";
                        options += "<option value='<'>MENOR QUE</option>";

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                        $("#valor_"+id).numeric();
                    }

                    if(tipo == '5'){

                        options += "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                        options += "<option value='>'>MAYOR QUE</option>";
                        options += "<option value='<'>MENOR QUE</option>";

                        $("#valor_"+id).datepicker({
                            language: "es",
                            autoclose: true,
                            todayHighlight: true
                        });
                    }

                    if( tipo == '_Activo____b' || tipo == '_Estado____b' || tipo == '_ConIntUsu_b' || tipo == 'MONOEF' || tipo == 'ListaCla' || tipo == '8' || tipo == '6'){
                        options += "<option value='='>IGUAL A</option>";
                        options += "<option value='!='>DIFERENTE DE</option>";
                    } 

                    if(tipo == '8'){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                        cuerpo += "<option value='0'>ACTIVO</option>";
                        cuerpo += "<option value='-1'>NO ACTIVO</option>";
                        cuerpo += "</select>";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }


                    if(tipo == '_Activo____b'){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                        cuerpo += "<option value='-1'>ACTIVO</option>";
                        cuerpo += "<option value='0'>NO ACTIVO</option>";
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }

                    if(tipo == '_Estado____b'){

                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                        cuerpo += "<option value='0'>SIN GESTI&Oacute;N</option>";
                        cuerpo += "<option value='1'>REINTENTO AUTOMATICO</option>";
                        cuerpo += "<option value='2'>AGENDA</option>";
                        cuerpo += "<option value='3'>NO REINTENTAR</option>";
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);

                    }

                    if(tipo == '_ConIntUsu_b'){
                        $.ajax({
                            url    : '<?=$url_crud_extender?>?getUsuariosCampanha=true',
                            type   : 'post',
                            data   : { campan : $("#id_campanaFiltros").val() },
                            success : function(data){
                                cuerpo  = "<div class='form-group'>";
                                cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                                $.each(JSON.parse(data), function(i, item) {
                                    cuerpo += "<option value='"+item.USUARI_ConsInte__b+"'>"+ item.USUARI_Nombre____b+"</option>";
                                }); 
                                cuerpo += "</select>";
                                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                                cuerpo += "</div>";  

                                $("#valores_restableses_"+id).html(cuerpo);
                            }
                        });
                    }

                    if(tipo == 'MONOEF'){
                        $.ajax({
                            url    : '<?=$url_crud_extender?>?getMonoefCampanha=true',
                            type   : 'post',
                            data   : { campan : $("#id_campanaFiltros").val() },
                            success : function(data){
                                cuerpo  = "<div class='form-group'>";
                                cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                                $.each(JSON.parse(data), function(i, item) {
                                    cuerpo += "<option value='"+item.MONOEF_ConsInte__b+"'>"+ item.MONOEF_Texto_____b+"</option>";
                                }); 
                                cuerpo += "</select>";
                                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                                cuerpo += "</div>";  

                                $("#valores_restableses_"+id).html(cuerpo);
                            }
                        });
                    }

                        if(tipo == '6'){
                            $.ajax({
                                url    : '<?=$url_crud_extender?>',
                                type   : 'post',
                                data   : { lista : valor , getListasDeEsecampo : true },
                                dataType: 'json',
                                success : function(data){
                                    cuerpo  = "<div class='form-group'>";
                                    cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                                    $.each(data, function(i, item) {
                                        cuerpo += "<option value='"+item.LISOPC_ConsInte__b+"'>"+ item.LISOPC_Nombre____b+"</option>";
                                    }); 
                                    cuerpo += "</select>";
                                    cuerpo += "</div>";  

                                    $("#valores_restableses_"+id).html(cuerpo);
                                }
                            });
                        }


                        $("#condicion_"+id).html(options);
                    });

                    
                    $(".deleteFiltro").click(function(){
                        var id = $(this).attr('id');
                        $("#id_"+id).remove();
                    });
        });
        $(".Radio_condiciones").change(function(){
            if($(this).val() == 1){
                $("#FILTROS").html("");
                $("#div_filtros_campan").hide();
                $("#div_lista_de_registros").hide();
                $('#campo_a_filtrar').prop('selectedIndex',0);
                $('#listaExcell').val('');
            }else if($(this).val() == 2){
                $("#txt_cantidad_registrps").val('');
                $("#div_cantidad_campan").hide();
                $("#div_lista_de_registros").hide();
                $('#campo_a_filtrar').prop('selectedIndex',0);
                $('#listaExcell').val('');
                $("#div_filtros_campan").show();
                $("#FILTROS").html("");
            }else if($(this).val() == 3){
                $("#FILTROS").html("");
                $("#div_filtros_campan").hide();
                $("#div_lista_de_registros").show();
            }    

        });
        var van_delete = 1;
        $("#new_filtro_delete").click(function(){
        var cuerpo = "<div class='row' id='id_"+van_delete+"'>";
            cuerpo += "<div class='col-md-4'>";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<select class='form-control mi_select_pregun' name='pregun_"+van_delete+"'  id='pregun_"+van_delete+"' numero='"+van_delete+"'>";
                        cuerpo += "<option value='0' tipo='3'>Seleccione</option>";
                        cuerpo += "<option value='_CoInMiPo__b' tipo='3'>Llave interna</option>";


                        $.each(datosGuion, function(i, item) {
                             cuerpo += "<option value='"+item.PREGUN_ConsInte__b+"' tipo='"+item.PREGUN_Tipo______b+"'>"+ item.PREGUN_Texto_____b+"</option>";
                        }); 

                        cuerpo += "<option value='_Estado____b' tipo='_Estado____b'>Tipo de reintento</option>";
                        cuerpo += "<option value='_ConIntUsu_b' tipo='_ConIntUsu_b'>Usuario</option>";
                        cuerpo += "<option value='_NumeInte__b' tipo='3'>Numero de intentos</option>";
                        cuerpo += "<option value='_UltiGest__b' tipo='MONOEF'>Ultima gesti&oacute;n</option>";
                        cuerpo += "<option value='_GesMasImp_b' tipo='MONOEF'>Gesti&oacute;n mas importante</option>";
                        cuerpo += "<option value='_FecUltGes_b' tipo='5'>Fecha ultima gesti&oacute;n</option>";
                        cuerpo += "<option value='_FeGeMaIm__b' tipo='5'>Fecha gesti&oacute;n mas importante</option>";
                        cuerpo += "<option value='_ConUltGes_b' tipo='ListaCla'>Clasificaci&oacute;n ultima gesti&oacute;n</option>";
                        cuerpo += "<option value='_CoGesMaIm_b' tipo='ListaCla'>Clasificaci&oacute;n mas importante</option>";
                        cuerpo += "<option value='_Activo____b' tipo='_Activo____b'>Registro activo</option>";

                    cuerpo += "</select>";

                cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<div class='col-md-3'>";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<select name='condicion_"+van_delete+"' id='condicion_"+van_delete+"' class='form-control'>";
                        cuerpo += "<option value='0'>Condici&oacute;n</option>";
                    cuerpo += "</select>";
                cuerpo += "</div>";
            cuerpo += "</div>";


            cuerpo += "<div class='col-md-4 col-xs-10' id='valores_restableses_"+van_delete+"' >";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<input type='text'  name='valor_"+van_delete+"' id='valor_"+van_delete+"' class='form-control' placeholder='<?php echo $filtros_valor__c; ?>'>";
                cuerpo += "</div>";
            cuerpo += "</div>";

            cuerpo += "<input type='hidden' name='tipo_campo_"+van_delete+"' id='tipo_campo_"+van_delete+"' class='form-control'>";

            cuerpo += "<div class='col-md-1 col-xs-2'>";
                cuerpo += "<div class='form-group'>";
                    cuerpo += "<button class='btn btn-danger btn-sm deleteFiltro'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+van_delete+"'><i class='fa fa-trash-o'></i></button>";
                cuerpo += "</div>";
            cuerpo += "</div>";

        cuerpo += "</div>";
        
        van_delete++;

        $("#FILTROS_delete").append(cuerpo);

        $(".mi_select_pregun").change(function(){
            var id = $(this).attr('numero');
            var tipo = $("#pregun_"+id+" option:selected").attr('tipo');
            var valor = $(this).val();

            $("#tipo_campo_"+id).val(tipo);

            var options = "";
            if(tipo == '1' || tipo == '2'){

                options += "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='LIKE_1'>INICIE POR</option>";
                options += "<option value='LIKE_2'>CONTIENE</option>";
                options += "<option value='LIKE_3'>TERMINE EN</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }

            if(tipo == '4' || tipo == '3'){

                options += "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='<'>MENOR QUE</option>";

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<input type='text' name='valor_"+id+"' id='valor_"+id+"' class='form-control' placeholder='VALOR A FILTRAR'>";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

                $("#valor_"+id).numeric();
            }

            if(tipo == '5'){

                options += "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
                options += "<option value='>'>MAYOR QUE</option>";
                options += "<option value='<'>MENOR QUE</option>";

                $("#valor_"+id).datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true
                });
            }

            if( tipo == '_Activo____b' || tipo == '_Estado____b' || tipo == '_ConIntUsu_b' || tipo == 'MONOEF' || tipo == 'ListaCla' || tipo == '8' || tipo == '6'){
                options += "<option value='='>IGUAL A</option>";
                options += "<option value='!='>DIFERENTE DE</option>";
            } 

            if(tipo == '8'){
                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                cuerpo += "<option value='0'>ACTIVO</option>";
                cuerpo += "<option value='-1'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }


            if(tipo == '_Activo____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                cuerpo += "<option value='-1'>ACTIVO</option>";
                cuerpo += "<option value='0'>NO ACTIVO</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }

            if(tipo == '_Estado____b'){

                cuerpo  = "<div class='form-group'>";
                cuerpo += "<select name='valor_"+id+"'  id='valor_"+id+"' class='form-control'>";
                cuerpo += "<option value='0'>SIN GESTI&Oacute;N</option>";
                cuerpo += "<option value='1'>REINTENTO AUTOMATICO</option>";
                cuerpo += "<option value='2'>AGENDA</option>";
                cuerpo += "<option value='3'>NO REINTENTAR</option>";
                cuerpo += "</select>";
                cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                cuerpo += "</div>";  

                $("#valores_restableses_"+id).html(cuerpo);

            }

            if(tipo == '_ConIntUsu_b'){
                $.ajax({
                    url    : '<?=$url_crud_extender?>?getUsuariosCampanha=true',
                    type   : 'post',
                    data   : { campan : $("#id_campanaFiltros_delete").val() },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                        $.each(JSON.parse(data), function(i, item) {
                             cuerpo += "<option value='"+item.USUARI_ConsInte__b+"'>"+ item.USUARI_Nombre____b+"</option>";
                        }); 
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);
                    }
                });
            }

            if(tipo == 'MONOEF'){
                $.ajax({
                    url    : '<?=$url_crud_extender?>?getMonoefCampanha=true',
                    type   : 'post',
                    data   : { campan : $("#id_campanaFiltros_delete").val() },
                    success : function(data){
                        cuerpo  = "<div class='form-group'>";
                        cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                        $.each(JSON.parse(data), function(i, item) {
                             cuerpo += "<option value='"+item.MONOEF_ConsInte__b+"'>"+ item.MONOEF_Texto_____b+"</option>";
                        }); 
                        cuerpo += "</select>";
                        cuerpo += "<input type='hidden' name='esMuestra_"+id+"' value='SI' >";
                        cuerpo += "</div>";  

                        $("#valores_restableses_"+id).html(cuerpo);
                    }
                });
            }

                if(tipo == '6'){
                    $.ajax({
                        url    : '<?=$url_crud_extender?>',
                        type   : 'post',
                        data   : { lista : valor , getListasDeEsecampo : true },
                        dataType: 'json',
                        success : function(data){
                            cuerpo  = "<div class='form-group'>";
                            cuerpo += "<select name='valor_"+id+"' class='form-control'  id='valor_"+id+"'>";
                            $.each(data, function(i, item) {
                                 cuerpo += "<option value='"+item.LISOPC_ConsInte__b+"'>"+ item.LISOPC_Nombre____b+"</option>";
                            }); 
                            cuerpo += "</select>";
                            cuerpo += "</div>";  

                            $("#valores_restableses_"+id).html(cuerpo);
                        }
                    });
                }


                $("#condicion_"+id).html(options);
            });

            
            $(".deleteFiltro").click(function(){
                var id = $(this).attr('id');
                $("#id_"+id).remove();
            });
        });
        $(".Radio_condiciones_delete").change(function(){
            if($(this).val() == 1){
                $("#FILTROS_delete").html("");
                $("#div_filtros_campan_delete").hide();
                $("#div_lista_de_registros_delete").hide();
                $('#campo_a_filtrar_delete').prop('selectedIndex',0);
                $('#listaExcell_delete').val('');
            }else if($(this).val() == 2){
                $("#div_lista_de_registros_delete").hide();
                $('#campo_a_filtrar_delete').prop('selectedIndex',0);
                $('#listaExcell_delete').val('');
                $("#div_filtros_campan_delete").show();
                $("#FILTROS_delete").html("");
            }else if($(this).val() == 3){
                $("#FILTROS_delete").html("");
                $("#div_filtros_campan_delete").hide();
                $("#div_lista_de_registros_delete").show();
            }    

        });
        $("#aplicar_filtros_delete").click(function(){

            valor = 1;

            $(".Radio_condiciones_delete").each(function(){
                if($(this).is(":checked")){
                    valor = $(this).val();
                }
            });

            valido = 1;

            var contador = new Array();
            $(".mi_select_pregun").each(function(){
                contador.push($(this).attr("numero"));
            });

            if(valido == 1){
                var dtao = new FormData($("#consulta_campos_where_delete")[0]);
                dtao.append('contador' , contador);
                $.ajax({
                    url  : "<?php echo $url_crud;?>?delete_base=true",
                    type : "post",
                    data : dtao,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(data){
                        if (data == "exito" || data == "exitoexito") {
                            alertify.success("<?php echo $exito_top_regist; ?>");
                        }else if(data == "error_2"){
                            alertify.error("Debe agregar filtros.");
                        }else if(data == "error_5"){
                            alertify.error("Los registros deben estar en la columna 'A' del Excell.");
                        }else if(data == "error_4"){
                            alertify.error("'Campo a filtrar' y 'Carga Lista' son Obligatorios.");
                        }else if(data == "error_1"){
                            alertify.error("Hay problemas con la campaña");
                        }else{
                            alertify.error("El proceso ha fallado.");
                        }
                        $("#div_filtros_campan_delete").hide();
                        $("#FILTROS_delete").html("");
                        $("#filtros_campanha_delete").modal('hide');
                        $("#consulta_campos_where_delete")[0].reset();
                        $("#div_lista_de_registros_delete").hide();
                    },
                    beforeSend : function(){
                        $.blockUI({
                            baseZ: 2000, 
                            message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                        });
                    },
                    complete : function(){
                        $.unblockUI();
                    }
                }); 
            }
        });
        $("#aplicar_filtros").click(function(){

            valor = 1;

            $(".Radio_condiciones").each(function(){
                if($(this).is(":checked")){
                    valor = $(this).val();
                }
            });

            valido = 1;

            if($("#sel_tipo_reintento_acciones").val() == '2'){
                if($("#txt_fecha_reintento_acciones").val() < 1){
                    alertify.error("<?php echo $error_fec_regist;?>");
                    valido = 0;
                    $("#txt_fecha_reintento_acciones").focus();
                }
                if($("#txt_hora_reintento_acciones").val() < 1){
                    alertify.error("<?php echo $error_hor_regist;?>");
                    valido = 0;
                    $("#txt_hora_reintento_acciones").focus();
                }
            }
            var contador = new Array();
            $(".mi_select_pregun").each(function(){
                contador.push($(this).attr("numero"));
            });

            if(valido == 1){
                var dtao = new FormData($("#consulta_campos_where")[0]);
                dtao.append('contador' , contador);
                $.ajax({
                    url  : "<?php echo $url_crud;?>?administrar_base=true",
                    type : "post",
                    data : dtao,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function(data){
                        if (data == "exito" || data == "exitoexito") {
                            alertify.success("<?php echo $exito_top_regist; ?>");
                        }else if(data == "error_2"){
                            alertify.error("Debe agregar filtros.");
                        }else if(data == "error_5"){
                            alertify.error("Los registros deben estar en la columna 'A' del Excell.");
                        }else if(data == "error_4"){
                            alertify.error("'Campo a filtrar' y 'Carga Lista' son Obligatorios.");
                        }else{
                            alertify.error("El proceso ha fallado.");
                        }
                        $("#div_filtros_campan").hide();
                        $("#FILTROS").html("");
                        $("#filtros_campanha").modal('hide');
                        $("#consulta_campos_where")[0].reset();
                        $("#conf_fechas_reintento").hide();
                        $("#div_lista_de_registros").hide();
                    },
                    beforeSend : function(){
                        $.blockUI({
                            baseZ: 2000, 
                            message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete : function(){
                        $.unblockUI();
                    }
                }); 
            }
        });

        $("#TxtFechaReintentoAcciones").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        $("#TxtHoraReintentoAcciones").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#txt_fecha_reintento_acciones").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        $("#txt_hora_reintento_acciones").timepicker({
            'timeFormat': 'H:i:s',
            'minTime': '00:00:00',
            'maxTime': '23:59:59',
            'setTime': '08:00:00',
            'step'  : '5',
            'showDuration': true
        });

        $("#selTipoReintentoAcciones").change(function(){
            if($(this).val() == '2'){
                $("#confFechasReintento").show();
            }else{
                $("#confFechasReintento").hide();
            }
        });

        $("#sel_tipo_reintento_acciones").change(function(){
            if($(this).val() == '2'){
                $("#conf_fechas_reintento").show();
            }else{
                $("#conf_fechas_reintento").hide();
                $("#txt_fecha_reintento_acciones").val("");
                $("#txt_hora_reintento_acciones").val("");
            }
        });
    });

    function cambiarTipoValor(tipollamada){
        $("#consultaCamposWhere")[0].reset();
        if(tipollamada != '1'){
            $(".condicionesTop").hide();
        }else{
            $(".condicionesTop").show();
        }

        if(tipollamada == '5'){
            $("#divAccionesCampan").show();
        }else{
            $("#divAccionesCampan").hide();
        }

        $("#tipoLlamado").val(tipollamada);
        $("#txtCantidadRegistrps").val('');
        $("#divCantidadCampan").hide();
        $("#divFiltrosCampan").hide();
        $("#filtros").html("");
    }
</script>

<!-- Agregar mas horarios -->
<script type="text/javascript">
    
    

    $("#horasEnvio").click(function(){
        var cuero = '<div class="row" id="id_'+contadorAsuntos+'">'+
                    '<input type="hidden" value="nuevo" name="txtAsuntosNuevos_'+contadorAsuntos+'" id="txtAsuntosNuevos_'+contadorAsuntos+'">'+
                    '<div class="col-md-3">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repAsunto______; ?></label>'+
                            '<input type="text" class="form-control" name="GtxtNombreReporte_'+ contadorAsuntos +'" id="GtxtNombreReporte_'+ contadorAsuntos +'" placeholder="<?php echo $repAsunto______;?>" value="" >'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repdirijidoa___; ?></label>'+
                            '<input type="email" class="form-control" name="GtxtAquienVa_'+ contadorAsuntos +'" id="GtxtAquienVa_'+ contadorAsuntos +'" value=""  placeholder="<?php echo $repdirijidoa___;?>">'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $repcopia_______; ?></label>'+
                            '<input type="email" class="form-control" name="GtxtCopiaA_'+ contadorAsuntos +'" id="GtxtCopiaA_'+ contadorAsuntos +'" value=""  placeholder="<?php echo $repcopia_______;?>" >'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $rephorasdeenvio; ?></label>'+
                            '<input type="text" class="form-control" name="GtxtHoraEnvio_'+ contadorAsuntos +'" id="GtxtHoraEnvio_'+ contadorAsuntos +'"  placeholder="<?php echo $rephorasdeenvio; ?>">'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label><?php echo $campan_periodo_; ?></label>'+
                            '<select class="form-control" name="GcmbPeriodicidad_'+ contadorAsuntos +'">'+
                                '<option value="1"><?php echo $campan_diario__;?></option>'+
                                '<option value="2"><?php echo $campan_semanal_;?></option>'+
                                '<option value="3"><?php echo $campan_mensual_;?></option>'+
                            '</select>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-1">'+
                        '<label style="visibility:hidden;">JoseDavid</label>'+
                        '<button type="button" class="btn btn-sm btn-danger deleteCorreo" id= "'+ contadorAsuntos +'">'+
                        '    <i class="fa fa-trash"></i>'+
                        '</button>'+
                    '</div>'+
                '</div>';

       
        $("#horaEnvio").append(cuero);
        $("#GtxtHoraEnvio_"+ contadorAsuntos).timepicker({
            'timeFormat': 'H:i',
            'minTime': '00:00',
            'maxTime': '23:59',
            'setTime': '08:00',
            'step'  : '5',
            'showDuration': true
        });

        $(".deleteCorreo").click(function() {
            var id = $(this).attr('id');
            $("#id_"+id).remove();
        });

        //obtenemos la altura del documento
        var altura = $(document).height();
        $("html, body").animate({scrollTop:altura+"px"});
        contadorAsuntos++;
    });


    $(".deleteCorreoF").click(function(){
        var id = $(this).attr('aborrar');
        $.ajax({
            url     : '<?=$url_crud_extender?>?borrarReporte=true',
            type    : 'post',
            data    : { idReporte : id },
            success : function(data){
                if(data != 1){
                    alertify.error(data);
                }else{
                    $("#"+id).remove();
                }
            }
        });
    });
</script>


<!-- Funciones del modal de aliminar tipificacion -->
<script type="text/javascript">
    $(document).ready(function(){
         
        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getEstados : true, paso : '<?php echo $_GET['id_paso']; ?>'},
            success : function(data){
                estados = data;
                $("#selEstadosAcciones").html(data);
                $("#sel_estados_acciones").html(data);
            }
        });

        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getTipificacionesCampanas : true, paso : '<?php echo $_GET['id_paso'];?>' , idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>' , tipificacionEntrante : true },
            success : function(data){
                $("#opciones").html(data);

                $(".deleteFirme").click(function(){
                    var id = $(this).attr('id');
                    $("#idValorDel").val(id);
                    $.ajax({
                        url     : '<?=$url_crud_extender?>',
                        type    : 'post',
                        data    : { getTipificaciones : true, paso : '<?php echo $_GET['id_paso'];?>', noMuestreEsta : id},
                        beforeSend : function(){
                            $.blockUI({ 
                                baseZ: 2000,
                                message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>'
                            });
                        },
                        complete : function(){
                            $.unblockUI();
                        },
                        success : function(data){
                            $("#selTipificacionesEliminadas").html(data);
                            $("#ModalPreguntaTipificacion").modal();
                        }
                    });
                    
                });
            }
        });  

        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getTipificaciones : true, paso : '<?php echo $_GET['id_paso'];?>'},
            success : function(data){
                $("#selTipificacionesEliminadas").html(data);
                $("#selTipificacionesAcciones").html(data);
                $("#sel_tipificaciones_acciones").html(data);
            }
        });

        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { getConfigBusqueda : true, poblacion : '<?php echo $_GET['poblacion'];?>'},
            dataType:'JSON',
            success : function(data){
                if(data['estado'] == 'ok'){
                    $("#insertRegistro").val(data['data']['permiteInsertar']).trigger('change');
                    $("#insertAuto").val(data['data']['insertAuto']).trigger('change');
                }
            }
        });

        
        

        $("#btnSaveEliminacionTipificaciones").click(function(){
            $.ajax({
                url     : '<?=$url_crud_extender?>',
                type    : 'post',
                data    : {
                    idLisopc : $("#idValorDel").val(),
                    idBd: '<?=$_GET['poblacion']?>',
                    idNuevaTipificacion : $("#selTipificacionesEliminadas").val(),
                    pregun : '<?php echo $guion['G5_C311']; ?>',
                    crudLisopcDelete : true
                },
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                    });
                },
                complete : function(){
                    $.unblockUI();
                },
                success:function(data){
                    if(data != ''){
                        alertify.error('<?php echo $error_de_proceso; ?>');
                    }else{
                        alertify.success('<?php echo $str_Exito; ?>');
                        var id = $("#idValorDel").val();
                        $("#id_"+id).remove();
                        $("#ModalPreguntaTipificacion").modal('hide');
                    }
                }
            });
        });
    });
</script>

<!-- Fucniones de edicion de listas -->
<script type="text/javascript">
    var contadorListaCampana = 0;
    $(function(){

        $("#editEstados").click(function(){
            $("#agrupador")[0].reset();
            $("#opcionesListaHtml").html('');
            contadorListaCampana = 0;
            $("#hidListaInvocada").val(<?php echo $datosListas['OPCION_ConsInte__b']; ?>);
            $.ajax({
                url     : '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_extender_funcionalidad.php',
                type    : 'post',
                data    : { getListasEdit : true, idOpcion : '<?php echo $datosListas['OPCION_ConsInte__b']; ?>' },
                dataType: 'json',
                beforeSend : function(){
                    $.blockUI({ 
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                    });
                },
                complete : function(){
                    $.unblockUI();
                },
                success : function(data){
                    if(data.code == '1'){
                        $("#idListaE").val(data.id);
                        $("#txtNombreLista").val(data.opcion);
                        $.each(data.lista, function(i, items){
                            var cuerpo = "<div class='row' id='id_"+i+"'>";
                            cuerpo += "<div class='col-md-10 col-xs-10'>";
                            cuerpo += "<div class='form-group'>";
                            cuerpo += "<input type='text' name='opcionesEditar_"+ i +"' class='form-control opcionesGeneradas' value='"+ items.LISOPC_Nombre____b +"' placeholder='<?php echo $str_opcion_nombre_; ?>'><input type='hidden' name='hidIdOpcion_"+ i +"' value='"+ items.LISOPC_ConsInte__b +"'>";
                            cuerpo += "</div>";
                            cuerpo += "</div>";
                            cuerpo += "<div class='col-md-2 col-xs-2' style='text-align:center;'>";
                            cuerpo += "<div class='form-group'>";
                            cuerpo += "<button class='btn btn-danger btn-sm deleteopcionP'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+i+"' value='"+ items.LISOPC_ConsInte__b +"'><i class='fa fa-trash-o'></i></button>";
                            cuerpo += "</div>";
                            cuerpo += "</div>";
                            cuerpo += "</div>";
                            $("#opcionesListaHtml").append(cuerpo);
                        });
                        $("#opcionesListaHtml").append("<input type='hidden' name='contadorEditables' value='"+data.total+"'>");
                        contadorListaCampana = data.total;
                        console.log(contadorListaCampana);
                        $("#operLista").val("edit");
                        $(".deleteopcionP").click(function(){
                            var id = $(this).attr('value');
                            var miId = $(this).attr('id');
                            alertify.confirm('<?php echo $str_deleteOptio__ ;?>' , function(e){
                                if(e){
                                    $.ajax({
                                        url   : '<?php echo $url_crud; ?>',
                                        type  : 'post',
                                        data  : { deleteOption: true, id : id},
                                        beforeSend : function(){
                                            $.blockUI({ 
                                                baseZ: 2000,
                                                message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>' 
                                            });
                                        },
                                        complete : function(){
                                            $.unblockUI();
                                        },
                                        success : function(data){
                                            if(data == '1'){
                                                alertify.success('<?php echo $str_Exito;?>');
                                                $("#id_"+miId).remove();
                                            }else{
                                                alertify.error('Error');
                                            }
                                        }
                                    })

                                }
                            });
                        });

                        $("#NuevaListaModal").modal();
                    }
                }
            });
   
        });

        $("#guardarLista").click(function(){
            var validator = 0;
            if($("#txtNombreLista").val().length < 1){
                validator = 1;
                alertify.error("Es necesario el nombre de la lista");
            }

            if(contadorListaCampana == 0){
                validator = 1;
                alertify.error("La lista no tiene opciones")
            }

            var vacios = 0;
            $(".opcionesGeneradas").each(function(){
                if($(this).val().length < 1){
                    vacios++;
                }
            });

            if(vacios > 0){
                alertify.error('No pueden haber opciones sin texto');
                validator = 1;
            }

            if(validator == 0){
                var form = $("#agrupador");
                //Se crean un array con los datos a enviar, apartir del formulario 
                var formData = new FormData($("#agrupador")[0]);
                formData.append('idGuion', '<?php echo $guion['G10_C74']; ?>');
                formData.append('contador', contadorListaCampana);
                $.ajax({
                    url: '<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G5/G5_CRUD.php?insertarDatosLista=si',  
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    //una vez finalizado correctamente
                    beforeSend : function(){
                        $.blockUI({ 
                            baseZ: 2000,
                            message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>'
                        });
                    },
                    complete : function(){
                        $.unblockUI();
                    },
                    success: function(data){
                        if(data != '0'){
                            alertify.success("<?php echo $str_Exito;?>");
                            var id = $("#hidListaInvocada").val();
                            $.ajax({
                                url     : '<?=$url_crud_extender?>',
                                type    : 'post',
                                data    : { getEstados : true, paso : '<?php echo $_GET['id_paso']; ?>'},
                                success : function(data){
                                    estados = data;
                                    $(".estadosCTX").html(data);
                                }
                            });
                            $("#NuevaListaModal").modal('hide');
                        }else{
                            alertify.error("<?php echo $error_de_proceso;?>");
                        }
                    }
                });    
            }
            
        });

        $("#newOpcionlistas").click(function(){
            var cuerpo = "<div class='row' id='id_"+cuantosVan+"'>";
            cuerpo += "<div class='col-md-10 col-xs-10'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<input type='text' name='opciones[]' class='form-control opcionesGeneradas' placeholder='<?php echo $str_opcion_nombre_; ?>'>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "<div class='col-md-2 col-xs-2' style='text-align:center;'>";
            cuerpo += "<div class='form-group'>";
            cuerpo += "<button class='btn btn-danger btn-sm deleteopcion'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+cuantosVan+"'><i class='fa fa-trash-o'></i></button>";
            cuerpo += "</div>";
            cuerpo += "</div>";
            cuerpo += "</div>";

            cuantosVan++;
            contador++;
            $("#opcionesListaHtml").append(cuerpo);

            
            
            $(".deleteopcion").click(function(){
                var id = $(this).attr('id');
                $("#id_"+id).remove();
                //contador = contador -1;
            });
        });
    });
</script>


<!-- Funciones de edicion de Caminc -->
<script type="text/javascript">
    $(document).ready(function(){
         
        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { cargarEnlacesBDForm : true, paso : '<?php echo $_GET['id_paso'];?>' , idioma : '<?php echo explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0];?>'},
            success : function(data){
                $("#htmlCamminc").html(data);

                $(".btnDeleteEsto").click(function(){
                    var id_camin = $(this).attr('idCam');
                    var fila = $(this).attr('fila');
                    alertify.confirm("<?php echo $str_message_generico_D;?>?", function (e) {
                        if (e) {
                            $.ajax({
                                url     : '<?=$url_crud_extender?>',
                                type    : 'post',
                                data    : {
                                    deleteCaminc : true,
                                    idCaminc : id_camin
                                },
                                success : function(data){
                                    if(data == '0'){
                                        alertify.success('<?php echo $str_Exito; ?>');
                                        $("#"+fila).remove();
                                    }else{
                                        alertify.error(data);
                                    }
                                }
                            });
                        }
                    }); 
                    
                });

                longitudCaminc = $("#htmlCamminc tr").length;
            }
        });

        $("#NewAsociacion").click(function(){
            
            var campo = '<tr id="'+ longitudCaminc +'">';
            campo += '<td>';
            campo += '<select class="form-control asocias" id="asocioa_'+ longitudCaminc +'" name="asocioa_'+ longitudCaminc +'" gemelo = "'+ longitudCaminc +'">';
            campo += '<option value="0"><?php echo $str_seleccione; ?></option>';
            campo += '<option value="G<?php echo $_GET["poblacion"]; ?>_ConsInte__b" tipo="3">ID</option>';
            campo += '<option value="G<?php echo $_GET["poblacion"]; ?>_FechaInsercion" tipo="5">FECHA INSERCION</option>';
            $.each(datosGuion, function(i, item){
                campo += '<option value="'+ item.PREGUN_ConsInte__b +'" tipo="'+ item.PREGUN_Tipo______b +'" >'+ item.PREGUN_Texto_____b +'</option>';
            });
            campo += '</select>';
            campo += '</td>';
            campo += '<td id="gemelo_'+ longitudCaminc +'">';
            campo += '<select class="form-control" id="asocioaG_'+ longitudCaminc +'" name="asocioaG_'+ longitudCaminc +'" gemelo = "'+ longitudCaminc +'">';
            campo += '</select>';
            campo += '</td>';
            campo += '<td  style="text-align:center;">';
            campo += "<button title='<?php echo $campan_dbfor_3_; ?>' class='btn btn-danger btn-sm btnDeleteEsto' type='button' fila='"+ longitudCaminc +"'><i class='fa fa-trash-o'></i></button>";
            campo += '</td>';
            campo += '</tr>';

            $("#htmlCamminc").append(campo);

            $("#asocioa_"+longitudCaminc).change(function(){

                var longitud2 = $(this).attr('gemelo');
                var tipo = $("#asocioa_"+ longitud2 +" option:selected").attr('tipo');
                var campo = '<option value="0"><?php echo $str_seleccione; ?></option>';
                $.each(datosForms, function(i , item){
                    if(item.PREGUN_Tipo______b == tipo){
                        campo += '<option value="'+ item.PREGUN_ConsInte__b +'" tipo="'+ item.PREGUN_Tipo______b +'" >'+ item.PREGUN_Texto_____b +'</option>';
                    }
                });
                $("#asocioaG_"+longitud2).html(campo);
            });

            $(".btnDeleteEsto").click(function(){
                var longitud2 = $(this).attr('fila');
                $("#"+longitud2).remove();
            });

            longitudCaminc++;
        });
    });
</script>

<script>
    function configurarMensajeBienvenida(obj){
        if(obj.checked){
            document.getElementById('seccionMensajeBienvenida').style.display = '';
        }else{
            document.getElementById('seccionMensajeBienvenida').style.display = 'none';
        }
    }

    function configurarFirma(obj){
        if(obj.checked){
            document.getElementById('seccionFirmaCorreo').style.display = '';
        }else{
            document.getElementById('seccionFirmaCorreo').style.display = 'none';
        }
    }

    // var fileUpload = document.getElementById('logoChat_1');
    // fileUpload.onchange = function(e){
    //     readFile(e.srcElement);
    // }

    function readFile(input){
        if(input.files && input.files[0]){
            
            var uploadFile = input.files[0];
            if(!(/\.(jpg|png|gif)$/i).test(uploadFile.name)){
                document.getElementById('logoChat_1').value = '';
                alertify.error('Este archivo no es una imagen ');
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

    function checkstate(checkName){

        switch (checkName) {
            case 'cierreChatEnviarBot':
                document.getElementById('cierreChatBot').disabled = !document.getElementById('cierreChatEnviarBot').checked;
                document.getElementById('cierreChatSeccion').disabled = !document.getElementById('cierreChatEnviarBot').checked;       
                break;

            case 'enEsperaEnviarBot':
                document.getElementById('enEsperaBot').disabled = !document.getElementById('enEsperaEnviarBot').checked;
                document.getElementById('enEsperaSeccion').disabled = !document.getElementById('enEsperaEnviarBot').checked;
                break;

            case 'maximoAsignacionEnviarBot':
                document.getElementById('maximoAsignacionBot').disabled = !document.getElementById('maximoAsignacionEnviarBot').checked;
                document.getElementById('maximoAsignacionSeccion').disabled = !document.getElementById('maximoAsignacionEnviarBot').checked;
                break;

            case 'inactividadAgenteEnviarBot':
                document.getElementById('inactividadAgenteBot').disabled = !document.getElementById('inactividadAgenteEnviarBot').checked;
                document.getElementById('inactividadAgenteSeccion').disabled = !document.getElementById('inactividadAgenteEnviarBot').checked;
                break;

            case 'inactividadClienteEnviarBot':
                document.getElementById('inactividadClienteBot').disabled = !document.getElementById('inactividadClienteEnviarBot').checked;
                document.getElementById('inactividadClienteSeccion').disabled = !document.getElementById('inactividadClienteEnviarBot').checked;
                break;

            case 'inactividadAgenteTimeout':
                document.getElementById('inactividadAgenteTiempo').disabled = !document.getElementById('inactividadAgenteActivar').checked;
                document.getElementById('inactividadAgenteMensaje').disabled = !document.getElementById('inactividadAgenteActivar').checked;
                document.getElementById('inactividadAgenteEnviarBot').disabled = !document.getElementById('inactividadAgenteActivar').checked;

                if(document.getElementById('inactividadAgenteActivar').checked){
                    document.getElementById('inactividadAgenteBot').disabled = !document.getElementById('inactividadAgenteEnviarBot').checked;
                    document.getElementById('inactividadAgenteSeccion').disabled = !document.getElementById('inactividadAgenteEnviarBot').checked;
                }else{
                    document.getElementById('inactividadAgenteBot').disabled = !document.getElementById('inactividadAgenteActivar').checked;
                    document.getElementById('inactividadAgenteSeccion').disabled = !document.getElementById('inactividadAgenteActivar').checked;
                }

                break;
            
            case 'inactividadClienteTimeout':
                document.getElementById('inactividadClienteTiempo').disabled = !document.getElementById('inactividadClienteActivar').checked;
                document.getElementById('inactividadClienteMensaje').disabled = !document.getElementById('inactividadClienteActivar').checked;
                document.getElementById('inactividadClienteEnviarBot').disabled = !document.getElementById('inactividadClienteActivar').checked;

                if(document.getElementById('inactividadClienteActivar').checked){
                    document.getElementById('inactividadClienteBot').disabled = !document.getElementById('inactividadClienteEnviarBot').checked;
                    document.getElementById('inactividadClienteSeccion').disabled = !document.getElementById('inactividadClienteEnviarBot').checked;
                }else{
                    document.getElementById('inactividadClienteBot').disabled = !document.getElementById('inactividadClienteActivar').checked;
                    document.getElementById('inactividadClienteSeccion').disabled = !document.getElementById('inactividadClienteActivar').checked;
                }
                break;

            default:
                break;
        }
    }

    function buscarSeccionesBot(bot, seccion){

        let botId = $("#"+bot).val();
        let seccionId = $("#"+seccion).attr('data-id');

        // Traigo la lista de secciones
        $.ajax({
            url     : '<?=$url_crud_extender?>',
            type    : 'post',
            data    : { traerSecciones: true, botId: botId},
            dataType: 'json',
            beforeSend : function(){
                if(seccionId == 0){
                    $.blockUI({
                        baseZ: 2000, 
                        message: '<img src="<?=base_url?>assets/img/clock.gif"/> <?php echo $str_message_wait;?>'
                    });
                }
            },
            success : function(data){
                
                let opciones = '<option value="0">Seleccione</option>';

                if(data.existe){
                    data.secciones.forEach(element => {
                        opciones += `<option value="${element.id}">${element.nombre}</option>`;
                    });
                }

                $("#"+seccion).html(opciones);

                // Al traer una seccion agrego la opcion
                $("#"+seccion).val(seccionId);
                
            },
            complete : function(){
                $.unblockUI();
            },
            error: function(){
                $.unblockUI();
                alertify.error('Se ha presentado un error al cargar las secciones');
            }
        });

    }

    function toogleMensajeEsperaPosicion(){
        
        let texto = $("#enEsperaMensaje").val();

        if($("#enEsperaPosicion").is(':checked')){
            // Valido que la posicion esta para autocompletar el texto
            if(!texto.includes('${DY_POSICION}')){
                $("#enEsperaMensaje").val(texto + ' Eres el ${DY_POSICION} en la cola');
            }
        }else{
            // Valido que el texto por defecto este
            if(texto.includes('Eres el ${DY_POSICION} en la cola')){
                $("#enEsperaMensaje").val(texto.replace(' Eres el ${DY_POSICION} en la cola', '').replace('Eres el ${DY_POSICION} en la cola', ''));
            }
        }
    }
</script>

<!-- Seccion busqueda web -->
<script>
    $(function(){
        
        $("#campoBusquedaWeb").on('change', function(){
        
            var dato = $('#campoBusquedaWeb').val();

            $("#seleccionado li").each(function(index) {
                if($(this).data('id') == dato){
                    $("#disponible").append($(this));
                }
            });
            
            $("#disponible li").each(function(index) {
                if($(this).data('id') == dato){
                    $(this).hide();
                }else{
                    $(this).show();
                }
            });
            
        });
    });
</script>

<script>
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
</script>

<!-- Eventos para el drag and drop -->
<script>

    $(function(){

        // // En esta funcion se encontrara el buscador que filtrara por el nombre 
        // $('#buscadorDisponible, #buscadorSeleccionado').keyup(function(){
        //     var tipoBuscador = $(this).attr('id');
        //     var nombres = '';

        //     if(tipoBuscador == 'buscadorDisponible'){
        //         nombres = $('ul#disponible .nombre');
        //     }else if(tipoBuscador == 'buscadorSeleccionado'){
        //         nombres = $('ul#seleccionado .nombre');
        //     }

        //     var buscando = $(this).val();
        //     var item='';

        //     for (let i = 0; i < nombres.length; i++) {
        //         item = $(nombres[i]).html().toLowerCase();
                
        //         for (let x = 0; x < item.length; x++) {
        //             if(buscando.length == 0 || item.indexOf(buscando) > -1 ){
        //                 $(nombres[i]).closest('li').show();
        //             }else{
        //                 $(nombres[i]).closest('li').hide();
                        
        //             }
        //         }
                
        //     }
        // });

        // /** Estas funciones se encargan del funcionamiento del drag & drop */
        // $("#disponible").sortable({connectWith:"#seleccionado"});
        // $("#seleccionado").sortable({connectWith:"#disponible"});

        // // Capturo el li cuando es puesto en la lista de usuarios disponible            
        // $( "#disponible" ).on( "sortreceive", function( event, ui ) {
        //     let arrDisponible = [];
        //     let arrDisponible2 = [];
        //     arrDisponible[0] = ui.item.data("id");
        //     arrDisponible2[0] = ui.item.data("camp3");

        //     moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
        // } );

        // // Capturo el li cuando es puesto en la lista de usuarios seleccionados         
        // $( "#seleccionado" ).on( "sortreceive", function( event, ui ) {
        //     let arrSeleccionado = [];
        //     let arrSeleccionado2 = [];
        //     arrSeleccionado[0] = ui.item.data("id");
        //     arrSeleccionado2[0] = ui.item.data("camp3");

        //     moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
        // } );

        // // Solo se selecciona el check cuando se clickea el li
        // $("#disponible, #seleccionado").on('click', 'li', function(){
        //     $(this).find(".mi-check").iCheck('toggle');

        //     if($(this).find(".mi-check").is(':checked') ){
        //         $(this).addClass('seleccionado');
        //     }else{
        //         $(this).removeClass('seleccionado');
        //     }
        // });

        // $("#disponible, #seleccionado").on('ifToggled', 'input', function(event){
        //     if($(this).is(':checked') ){
        //         $(this).closest('li').addClass('seleccionado');
        //     }else{
        //         $(this).closest('li').removeClass('seleccionado');
        //     }
        // });

        // // Envia los elementos seleccionados a la lista de la derecha
        // $('#derecha').click(function(){
        //     var obj = $("#disponible .seleccionado");
        //     $('#seleccionado').append(obj);

        //     let arrSeleccionado = [];
        //     let arrSeleccionado2 = [];
        //     obj.each(function (key, value){
        //         arrSeleccionado[key] = $(value).data("id");
        //         arrSeleccionado2[key] = $(value).data("camp3");
        //     });

        //     obj.removeClass('seleccionado');
        //     obj.find(".mi-check").iCheck('toggle');

        //     if(arrSeleccionado.length > 0 && arrSeleccionado2.length > 0){
        //         moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
        //     }
            
        // });

        // // Envia los elementos seleccionados a la lista de la izquerda
        // $('#izquierda').click(function(){
        //     var obj = $("#seleccionado .seleccionado");
        //     $('#disponible').append(obj);

        //     let arrDisponible = [];
        //     let arrDisponible2 = [];
        //     obj.each(function (key, value){
        //         arrDisponible[key] = $(value).data("id");
        //         arrDisponible2[key] = $(value).data("camp3");
        //     });

        //     obj.removeClass('seleccionado');
        //     obj.find(".mi-check").iCheck('toggle');

        //     if(arrDisponible.length > 0 && arrDisponible2.length > 0){
        //         moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
        //     }
        // });

        // // Envia todos los elementos a la derecha
        // $('#todoDerecha').click(function(){
        //     var obj = $("#disponible li");
        //     $('#seleccionado').append(obj);

        //     let arrSeleccionado = [];
        //     let arrSeleccionado2 = [];
        //     obj.each(function (key, value){
        //         arrSeleccionado[key] = $(value).data("id");
        //         arrSeleccionado2[key] = $(value).data("camp3");
        //     });
            
        //     moverUsuarios(arrSeleccionado, arrSeleccionado2, "derecha");
        // });

        // // Envia todos los elementos a la izquerda
        // $('#todoIzquierda').click(function(){
        //     var obj = $("#seleccionado li");
        //     $('#disponible').append(obj);

        //     let arrDisponible = [];
        //     let arrDisponible2 = [];
        //     obj.each(function (key, value){
        //         arrDisponible[key] = $(value).data("id");
        //         arrDisponible2[key] = $(value).data("camp3");
        //     });
            
        //     moverUsuarios(arrDisponible, arrDisponible2, "izquierda");
        // });

        // // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
        // function moverUsuarios(arrUsuarios, arrUsuarios2, accion){
        //     console.log('Se ha movido un objeto');
            
        // }
    });

</script>

<!-- eventos para las plantillas de respuestas en los chats-->
<script>
    function renderPlantilla(accion,valor='',titulo='',texto=''){
        var fila=parseInt($("#totalPlantillas").val());
        var cuerpo = "<div class='row' id='idPlantilla_"+fila+"'>";
        cuerpo += "<div class='col-md-3 col-xs-5'>";
        cuerpo += "<div class='form-group'>";
        cuerpo += "<input type='text'   name='titulo_"+fila+"' class='form-control' placeholder='TITULO DE LA PLANTILLA' value='"+titulo+"'>";
        cuerpo += "</div>";
        cuerpo += "</div>";
        
        cuerpo += "<div class='col-md-8 col-xs-5'>";
        cuerpo += "<div class='form-group'>";
        cuerpo += "<input type='text' name='plantilla_"+fila+"' class='form-control' placeholder='TEXTO DE LA PLANTILLA' value='"+texto+"'>";
        cuerpo += "<input type='hidden' name='accion_"+fila+"' id='accion_"+fila+"' value='"+accion+"'>";
        cuerpo += "<input type='hidden' name='valor_"+fila+"' id='valor_"+fila+"' value='"+valor+"'>";
        cuerpo += "</div>";
        cuerpo += "</div>";

        cuerpo += "<div class='col-md-1 col-xs-2'>";
        cuerpo += "<div class='form-group'>";
        cuerpo += "<button class='btn btn-danger btn-sm deletePlantilla'  title='<?php echo $str_opcion_elimina;?>' type='button' id='"+fila+"'><i class='fa fa-trash-o'></i></button>";
        cuerpo += "</div>";
        cuerpo += "</div>";
        cuerpo += "</div>";
        fila++;
        $("#totalPlantillas").val(fila);
        
        
        $("#Plantillas").append(cuerpo);
        
        $(".deletePlantilla").click(function(){
            let id = $(this).attr('id');
            $("#idPlantilla_"+id).css('display','none');
            $("#accion_"+id).val('delete');
        });
    }

    function getPlantillas(id=0){
        $.ajax({
            url:"<?=$url_crud?>",
            type:"POST",
            dataType:"JSON",
            data:{getPlantillas:"si", id:$("#hidId").val()},
            success:function(data){
                if(data.estado){
                    let html="<option value='0'>Seleccione</option>";
                    $.each(data.mensaje,function(i,item){
                        html+="<option value='"+item.id+"'>"+item.nombre+"</option>";
                    });
                    $("#listaPlantilla").html(html);
                    $("#listaPlantilla").val(id).trigger("change");
                }else{
                    alertify.error(data.mensaje);
                }
            },
            error:function(){
                alertify.error("Se generó un erro al cargar las plantillas de respuestas rapidas");
            }
        });
    }
    
    $(function(){
        $("#addPlantilla").click(function(){
            renderPlantilla('add');
        });
        
        $("#newPlantilla").click(function(){
            $("#idPlantilla").val(0);
            $("#nombrePlantilla").val('');
            $("#Plantillas").html('');
        });
        
        $("#editPlantilla").click(function(){
            $("#idPlantilla").val($("#listaPlantilla").val());
            $("#Plantillas").html('');
            $.ajax({
                url:"<?=$url_crud?>",
                type:"POST",
                dataType:"JSON",
                data:{getRespuestasPlantilla:"si", id:$("#listaPlantilla").val()},
                success:function(data){
                    if(data.estado){
                        $("#nombrePlantilla").val($('select[name="G10_C336"] option:selected').text());
                        $.each(data.mensaje, function(i, item) {
                            renderPlantilla('update',item.id,item.pregunta,item.respuesta)
                        });
                    }else{
                        alertify.error(data.mensaje);
                    }
                },
                beforeSend: function() {
                    $.blockUI({
                        baseZ: 2000,
                        message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                    });
                },
                complete: function() {
                    $.unblockUI();
                },
                error:function(){
                    alertify.error("Se generó un erro al cargar las plantillas de respuestas rapidas");
                }
            });
        });

        $("#listaPlantilla").change(function(){
            $("#editPlantilla").hide();
            if($(this).val() != "0"){
                $("#editPlantilla").show();
            }
        });

        $("#btnSavePlantillas").click(function(){
            let nameplantilla=$('select[name="G10_C336"] option:selected').text();
            if($("#nombrePlantilla").val() == ""){
                alertify.warning("Debe completar el campo 'nombre de la plantilla'");
            }else{
                let formData = new FormData($("#formPlantillas")[0]);
                formData.append("saveRespuestasPlantilla","si");
                $.ajax({
                    url:"<?=$url_crud?>",
                    type:"POST",
                    dataType:"JSON",
                    data:formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(data){
                        if(data.estado){
                            if($("#idPlantilla").val()== '0'){
                                getPlantillas(data.mensaje);
                            }

                            if($("#nombrePlantilla").val() != nameplantilla){
                                $('select[name="G10_C336"] option:selected').text($("#nombrePlantilla").val());
                            }

                            alertify.success("éxito");
                            $("#modalPlantillas").modal('hide');
                        }else{
                            alertify.error(data.mensaje);
                        }
                    },
                    error:function(){
                        alertify.error("Se generó un erro al guardar la lista de respuestas");
                    }
                });
            }
        });
    });


</script>

<script>
    function analizarTiempoTimeout(campo){
        let tiempo = $("#"+campo).val();

        let tiempoPorDefecto = 1380;

        if(isNaN(tiempo)){
            $("#"+campo).val(1);
            alertify.warning("Debes ingresar un valor numerico");
            return;
        }

        if(tiempo == 0){
            if(tiempo == ''){
                return;
            }
            $("#"+campo).val(1);
        }

        if(campo == 'cantMaxMensajeEspera'){
            tiempoPorDefecto = 10;
        }

        if(tiempo > tiempoPorDefecto){
            $("#"+campo).val(tiempoPorDefecto);
            if(campo == 'cantMaxMensajeEspera'){
                alertify.warning("La cantidad maxima de veces de ejecucion son "+tiempoPorDefecto);
            }else{
                alertify.warning("El maximo de tiempo de timeout son "+tiempoPorDefecto+" minutos");
            }
        }
    }
</script>

<!-- SCRIPT DE EVENTOS Y CARGAR DEL DRAG & DROP DEL FORMULARIO DE BÚSQUEDA MANUAL -->
<script type="text/javascript">
    $(document).ready(function(){
        $.ajax({
            url:'<?=$url_crud_extender?>',
            type:'POST',
            dataType:'JSON',
            data:{getCamposBusquedaManual:'si', idBd:'<?=$_GET['poblacion']?>'},
            success:function(data){
                if(data.estado){
                    let i=0;
                    $.each(data.data,function(i,item){
    
                        let label=$("<label>",{
                            'class':"btn btn-block btn-sm btn-default",
                            'html':item.nombre,
                            'style':'margin-top:20px;'
                        });
    
                        let div=$("<div>",{
                            'class':'div_'+i,
                            'id':'div_'+i,
                            'style':'min-height:60px;'
                        });
                        
                        $('#disponible,#seleccionado').append(label);
                        $('#disponible,#seleccionado').append(div);
    
                        $("#disponible .div_"+i).sortable({
                            connectWith: "#seleccionado .div_"+i
                        });
                        
                        $("#seleccionado .div_"+i).sortable({
                            connectWith: "#disponible .div_"+i
                        });
                        
                        $.each(item.campos,function(e,element){
                            let li=$("<li>",{
                                'data-id':element.campo,
                                'data-guion':element.guion,
                                'style':'padding:0px 10px;'
                            }).append(
                                $("<table>",{
                                    'class':"table table-hover"
                                }).append(
                                    $("<tr>").append(
                                        $("<td>",{
                                            'width':'40px'
                                        }).append(
                                            $("<input>",{
                                                'type':'checkbox',
                                                'class':'mi-check'
                                            })
                                        )
                                    ).append(
                                        $("<td>",{
                                            'class':'nombre',
                                            'html':element.texto
                                        })
                                    )
                                )
                            );
    
                            if(element.busqueda==0){
                                $("#disponible .div_"+i).append(li).addClass("disponible_"+element.guion);
                            }else{
                                $("#seleccionado .div_"+i).append(li).addClass("seleccionado_"+element.guion);
                            }
                        });
                        i++;
                    });
                }else{
                    alertify.error("No se pudo obtener los campos de búsqueda de la base de datos");
                }
            }
        });
    });

    // ejecuto el ajax para insertar los usuarios a la lista de la izquierda o derecha
    function moverCampos(arrCampos, accion) {
        
        let ruta = '';
        if (accion == 'derecha') {
            ruta = "agregarCamposForm=true";
        } else if (accion = 'izquerda') {
            ruta = "quitarCamposForm=true";
        }

        $.ajax({
            url: '<?=$url_crud_extender?>?' + ruta,
            type: 'POST',
            dataType: 'json',
            data: {
                arrCampos: arrCampos
            },
            success: function(response) {
                alertify.success("Mensaje: " + response.estado);
            },
            error: function(response) {
                console.log(response);
            },
            beforeSend: function() {
                $.blockUI({
                    baseZ: 2000,
                    message: '<img src="<?=base_url?>assets/img/clock.gif" /> <?php echo $str_message_wait;?>'
                });
            },
            complete: function() {
                $.unblockUI();
            }
        });
    }

    // En esta funcion se encontrara el buscador que filtrara por el nombre 
    $('#buscadorDisponible, #buscadorSeleccionado').keyup(function() {
        var tipoBuscador = $(this).attr('id');
        var nombres = '';

        if (tipoBuscador == 'buscadorDisponible') {
            nombres = $('ul#disponible .nombre');
        } else if (tipoBuscador == 'buscadorSeleccionado') {
            nombres = $('ul#seleccionado .nombre');
        }

        var buscando = $(this).val();
        var item = '';

        for (let i = 0; i < nombres.length; i++) {
            item = $(nombres[i]).html().toLowerCase();

            for (let x = 0; x < item.length; x++) {
                if (buscando.length == 0 || item.indexOf(buscando) > -1) {
                    $(nombres[i]).closest('li').show();
                } else {
                    $(nombres[i]).closest('li').hide();

                }
            }

        }
    });

    // Solo se selecciona el check cuando se clickea el li
    $("#disponible, #seleccionado").on('click', 'li', function() {
        $(this).find(".mi-check").iCheck('toggle');

        if ($(this).find(".mi-check").is(':checked')) {
            $(this).addClass('seleccionado');
        } else {
            $(this).removeClass('seleccionado');
        }

    });

    $("#disponible, #seleccionado").on('ifToggled', 'input', function(event) {
        if ($(this).is(':checked')) {
            $(this).closest('li').addClass('seleccionado');
        } else {
            $(this).closest('li').removeClass('seleccionado');
        }
    });

    // Capturo el li cuando es puesto en la lista de campos disponibles      
    $("#disponible").on("sortreceive", function(event, ui) {
        let arrDisponible = [];
        arrDisponible[0] = ui.item.data("id");

        moverCampos(arrDisponible, "izquierda");
    });

    // Capturo el li cuando es puesto en la lista de campos seleccionados  
    $("#seleccionado").on("sortreceive", function(event, ui) {
        let arrSeleccionado = [];
        arrSeleccionado[0] = ui.item.data("id");

        moverCampos(arrSeleccionado, "derecha");
    });


    // Envia los elementos seleccionados a la lista de la derecha
    $('#derecha').click(function() {
        let obj = $("#disponible .seleccionado");
        let arrSeleccionado = [];

        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#seleccionado .seleccionado_'+id).append(value);
            arrSeleccionado[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrSeleccionado.length > 0) {
            moverCampos(arrSeleccionado, "derecha");
        }

    });

    // Envia los elementos seleccionados a la lista de la izquerda
    $('#izquierda').click(function() {
        let obj = $("#seleccionado .seleccionado");
        let arrDisponible = [];

        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#disponible .disponible_'+id).append(value);
            arrDisponible[key] = $(value).data("id");
        });

        obj.removeClass('seleccionado');
        obj.find(".mi-check").iCheck('toggle');

        if (arrDisponible.length > 0) {
            moverCampos(arrDisponible, "izquierda");
        }
    });

    // Envia todos los elementos a la derecha
    $('#todoDerecha').click(function() {
        var obj = $("#disponible li");
        let arrSeleccionado = [];
        
        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#seleccionado .seleccionado_'+id).append(value);
            arrSeleccionado[key] = $(value).data("id");
        });

        moverCampos(arrSeleccionado, "derecha");
    });

    // Envia todos los elementos a la izquerda
    $('#todoIzquierda').click(function() {
        var obj = $("#seleccionado li");
        let arrDisponible = [];
        
        obj.each(function(key, value) {
            let id=$(value).data("guion");
            $('#disponible .disponible_'+id).append(value);
            arrDisponible[key] = $(value).data("id");
        });

        moverCampos(arrDisponible, "izquierda");
    });
</script>