
<?php date_default_timezone_set('America/Bogota'); ?>
<style>
    .datepicker-days .disabled{
        color: gray !important;
        cursor: not-allowed !important;
        opacity : .4 !important;
    }
</style>

<div class="modal fade-in" id="enviarCalificacion" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="CerrarCalificacion">&times;</button>
                <h4 class="modal-title">Enviar Calificacion</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12">
                        <p >Para enviar la calificacion a otros correos, ingresarlos <strong>SEPARANDOLOS</strong>  por una coma ( , ).</p>
                        <input type="text" class="form-control" id="cajaCorreos" name="cajaCorreos" placeholder="Ejemplo1@ejem.com,Ejemplo2@ejem.com">
 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <img hidden id="loading" src="/crm_php/assets/plugins/loading.gif" width="30" height="30">&nbsp;&nbsp;&nbsp;
                        <button id="sendEmails" readonly class="btn btn-primary" >Enviar Calificacion</button>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
                
<input type="hidden" id="IdGestion">
<div class="modal fade-in" id="editarDatos" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog" style="width:95%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="refrescarGrillas">&times;</button>
                <h4 class="modal-title">Edicion</h4>
            </div>
            <div class="modal-body">
                <iframe id="frameContenedor" src="" style="width: 100%; height: 900px;"  marginheight="0" marginwidth="0" noresize  frameborder="0">
                  
                </iframe>
            </div>
        </div>
    </div>
</div>
<?php
   //SECCION : Definicion urls
   $url_crud = "formularios/G3400/G3400_CRUD.php";
   //SECCION : CARGUE DATOS LISTA DE NAVEGACIÃ“N

    $PEOBUS_Escritur__b = 1 ;
    $PEOBUS_Adiciona__b = 1 ;
    $PEOBUS_Borrar____b = 1 ;

    if(!isset($_GET['view'])){
        $userid= isset($userid) ? $userid : $_SESSION["IDENTIFICACION"];
        $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = ".$_GET['formulario'];
        $query = $mysqli->query($peobus);
        $PEOBUS_VeRegPro__b = 0 ;
        
        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            $PEOBUS_Escritur__b = $key->PEOBUS_Escritur__b ;
            $PEOBUS_Adiciona__b = $key->PEOBUS_Adiciona__b ;
            $PEOBUS_Borrar____b = $key->PEOBUS_Borrar____b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $Zsql = "SELECT G3400_ConsInte__b as id, G3400_C69405 as camp1 , G3400_C69405 as camp2 FROM ".$BaseDatos.".G3400  WHERE G3400_Usuario = ".$idUsuario." ORDER BY G3400_ConsInte__b DESC LIMIT 0, 50";
        }else{
            $Zsql = "SELECT G3400_ConsInte__b as id, G3400_C69405 as camp1 , G3400_C69405 as camp2 FROM ".$BaseDatos.".G3400  ORDER BY G3400_ConsInte__b DESC LIMIT 0, 50";
        }

        $muestra = 0;
        $tipoDistribucion = 0;
        $tareaBackoffice = 0;

        if(isset($_GET['tareabackoffice'])){
            $tareaBackoffice = 1;

            $tareaBsql = "SELECT TAREAS_BACKOFFICE_ConsInte__b as id, TAREAS_BACKOFFICE_ConsInte__ESTPAS_b as estpas, TAREAS_BACKOFFICE_TipoDistribucionTrabajo_b as tipoDist FROM ".$BaseDatos_systema.".TAREAS_BACKOFFICE WHERE TAREAS_BACKOFFICE_ConsInte__b = ".$_GET['tareabackoffice'];
            $tareaBQuery = $mysqli->query($tareaBsql);

            while ($key =  $tareaBQuery->fetch_object()) {
                $resultTareaB = $key;
            }

            $estpassql = "SELECT ESTPAS_ConsInte__MUESTR_b as muestr FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$resultTareaB->estpas;
            $estpasQuery = $mysqli->query($estpassql);

            while ($key =  $estpasQuery->fetch_object()) {
                $resultEstpas = $key;
            }

            $muestra = $resultEstpas->muestr;
            $tipoDistribucion = $resultTareaB->tipoDist;

            if($resultTareaB->tipoDist == 1){
                $Zsql = "SELECT G3400_ConsInte__b as id, G3400_C69405 as camp1 , G3400_C69405 as camp2 FROM ".$BaseDatos.".G3400 JOIN ".$BaseDatos.".G3400_M".$resultEstpas->muestr." ON G3400_ConsInte__b = G3400_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( (G3400_M".$resultEstpas->muestr."_Estado____b = 0 OR G3400_M".$resultEstpas->muestr."_Estado____b = 1 OR G3400_M".$resultEstpas->muestr."_Estado____b = 3) OR (G3400_M".$resultEstpas->muestr."_Estado____b = 2 AND G3400_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) ) 
                ORDER BY G3400_ConsInte__b DESC LIMIT 0, 50";
            }else{
                $Zsql = "SELECT G3400_ConsInte__b as id, G3400_C69405 as camp1 , G3400_C69405 as camp2 FROM ".$BaseDatos.".G3400 JOIN ".$BaseDatos.".G3400_M".$resultEstpas->muestr." ON G3400_ConsInte__b = G3400_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( (G3400_M".$resultEstpas->muestr."_Estado____b = 0 OR G3400_M".$resultEstpas->muestr."_Estado____b = 1 OR G3400_M".$resultEstpas->muestr."_Estado____b = 3) OR (G3400_M".$resultEstpas->muestr."_Estado____b = 2 AND G3400_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) )
                AND G3400_M".$resultEstpas->muestr."_ConIntUsu_b = ".$idUsuario." 
                ORDER BY G3400_ConsInte__b DESC LIMIT 0, 50";
            }
            
        }

    }else{
        $userid= isset($userid) ? $userid : "-10";
        $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;
        $Zsql = "SELECT G3400_ConsInte__b as id, G3400_C69405 as camp1 , G3400_C69405 as camp2 FROM ".$BaseDatos.".G3400  ORDER BY G3400_ConsInte__b DESC LIMIT 0, 50";
    }

   $result = $mysqli->query($Zsql);

?>

<?php 

    include(__DIR__ ."/../cabecera.php");

?>

<?php
if(isset($_GET['user'])){
    $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;

    $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_Nombre____b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
    $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
    $datoCampan = $res_Lsql_Campan->fetch_array();
    $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
    $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
    $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
    $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
    $str_Nombr_Campan = $datoCampan['CAMPAN_Nombre____b'];


    $getPrincipales = "SELECT GUION__ConsInte__PREGUN_Pri_b FROM ".$BaseDatos_systema.".GUION_  WHERE GUION__ConsInte__b = ".$int_Pobla_Camp_2;
    $resLsql = $mysqli->query($getPrincipales);
    //echo $getPrincipales;
    $dato = $resLsql->fetch_array();

    $XLsql = $mysqli->query("SELECT ".$str_Pobla_Campan."_C".$dato['GUION__ConsInte__PREGUN_Pri_b']." as nombre FROM ".$BaseDatos.".".$str_Pobla_Campan." WHERE ".$str_Pobla_Campan."_ConsInte__b = ".$_GET['user'].";");

    if($XLsql && $XLsql->num_rows>0){
        //JDBD - Validamos si se pudo obtener el dato principal.
        $nombre = $XLsql;

        $nombreUsuario = NULL;
        //echo $XLsql;
        while ($key = $nombre->fetch_object()) {
            echo "<h3 style='color: rgb(110, 197, 255);'>".$key->nombre."</h3>";  
            $nombreUsuario = $key->nombre;

            //MOSTRAR EL ESTADO ACTUAL DEL REGISTRO EN LA MUESTRA
            $sqlEstado=$mysqli->query("SELECT dyalogo_general.fn_tipo_reintento_traduccion(G{$int_Pobla_Camp_2}_M{$int_Muest_Campan}_Estado____b) AS intento FROM {$BaseDatos}.G{$int_Pobla_Camp_2}_M{$int_Muest_Campan} WHERE G{$int_Pobla_Camp_2}_M{$int_Muest_Campan}_CoInMiPo__b={$_GET['user']}");
            if($sqlEstado && $sqlEstado->num_rows ==1){
                $sqlEstado=$sqlEstado->fetch_object();
                echo "<span class='text-muted'>Tipo de reintento actual del registro: {$sqlEstado->intento}</span>";
            }
            break;
        }
            


        if(isset($_GET['token']) && isset($_GET['id_gestion_cbx'])){


                        
            $data = array(  "strToken_t" => $_GET['token'], 
                            "strIdGestion_t" => $_GET['id_gestion_cbx'],
                            "strDatoPrincipal_t" => $nombreUsuario,
                            "strNombreCampanaCRM_t" => $str_Nombr_Campan);                                                                    
            $data_string = json_encode($data);    

            $ch = curl_init($IP_CONFIGURADA.'gestion/asignarDatoPrincipal');
            //especificamos el POST (tambien podemos hacer peticiones enviando datos por GET
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
            //le decimos que queremos recoger una respuesta (si no esperas respuesta, ponlo a false)
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                      
            ); 
            //recogemos la respuesta
            $respuesta = curl_exec ($ch);
            //o el error, por si falla
            $error = curl_error($ch);
            //y finalmente cerramos curl
            //echo "Respuesta =>  ". $respuesta;
            //echo "<br/>Error => ".$error;
            //include "Log.class.php";
            //$log = new Log("log", "./Log/");
            //$log->insert($error, $respuesta, false, true, false);
            //echo "nada";
            curl_close ($ch);
        }
    }else{
        echo "<script>console.log('NO SE PUDO OBTENER EL DATO PRINCIPAL DEL REGISTRO.');</script>";
    }
}else{
    echo "<h3 id='h3mio' style='color : rgb(110, 197, 255);'></h3>";    
}
?>
<input type="hidden" id="CampoIdGestionCbx" value="<?php if(isset($_GET['id_gestion_cbx'])){ echo $_GET["id_gestion_cbx"];}else{echo "";}?>">
<input type="hidden" name="intConsInteBd" id="intConsInteBd" value="<?php if(isset($_GET["user"])) { echo $_GET["user"]; }else{ echo "-1";  } ?>">
<?php if(isset($_GET['user'])){ ?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="box">
            <div class="box-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th colspan="4">
                                Historico de gestiones
                            </th>
                        </tr>
                        <tr>
                            <th>Gesti&oacute;n</th>
                            <th>Comentarios</th>
                            <th>Fecha - hora</th>
                            <th>Agente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $Lsql = "SELECT * FROM ".$BaseDatos_systema.".CONDIA JOIN ".$BaseDatos_systema.".USUARI ON CONDIA_ConsInte__USUARI_b = USUARI_ConsInte__b JOIN ".$BaseDatos_systema.".MONOEF ON CONDIA_ConsInte__MONOEF_b = MONOEF_ConsInte__b WHERE CONDIA_ConsInte__CAMPAN_b = ".$_GET["campana_crm"]." AND CONDIA_ConsInte__GUION__Gui_b = ".$int_Guion_Campan." AND CONDIA_ConsInte__GUION__Pob_b = ".$int_Pobla_Camp_2." AND CONDIA_ConsInte__MUESTR_b = ".$int_Muest_Campan." AND CONDIA_CodiMiem__b = ".$_GET['user']." ORDER BY CONDIA_Fecha_____b DESC LIMIT 5;";

                            
                            $res = $mysqli->query($Lsql);
                            if($res && $res->num_rows > 0){
                                while($key = $res->fetch_object()){
                                    echo "<tr>";
                                    echo "<td>".($key->MONOEF_Texto_____b)."</td>";
                                    echo "<td>".$key->CONDIA_Observacio_b."</td>";
                                    echo "<td>".$key->CONDIA_Fecha_____b."</td>";
                                    echo "<td>".$key->USUARI_Nombre____b."</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="10882" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_10882c">
                CONVERSACION
            </a>
        </h4>
        
    </div>
    <div id="s_10882c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- lIBRETO O LABEL -->
                    <p style="text-align:justify;" id="G3400_C69403">Buenos días|tardes|noches, podría comunicarme con el señor(a) |NombreCliente|</p>
                    <!-- FIN LIBRETO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- lIBRETO O LABEL -->
                    <p style="text-align:justify;" id="G3400_C69404">Mi nombre es |Agente|, le estoy llamando de |Empresa| con el fin de ...</p>
                    <!-- FIN LIBRETO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="10879" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_10879c">
                GENERAL
            </a>
        </h4>
        
    </div>
    <div id="s_10879c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G3400_C69405" id="LblG3400_C69405">nombre</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G3400_C69405" value="<?php if (isset($_GET['G3400_C69405'])) {
                            echo $_GET['G3400_C69405'];
                        } ?>"  name="G3400_C69405"  placeholder="nombre"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G3400_C69650" id="LblG3400_C69650">DOCUMENTO</label><input type="number" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G3400_C69650'])) {
                            echo $_GET['G3400_C69650'];
                        } ?>"  name="G3400_C69650" id="G3400_C69650" placeholder="DOCUMENTO"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G3400_C70063" id="LblG3400_C70063">TELEFONO</label><div class="input-group">
                            <input type="text" maxlength="253" onKeyDown="longitud(this.id,'nel')" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id)" class="form-control input-sm" id="G3400_C70063" value="<?php if (isset($_GET['G3400_C70063'])) {
                            echo $_GET['G3400_C70063'];
                        } ?>"  name="G3400_C70063"  placeholder="TELEFONO">
                            <div class="input-group-addon telefono" style="cursor:pointer" id="TLF_G3400_C70063" title="Click para llamar">
                        <i class="fa fa-phone"></i>
                    </div>
                            
                            
                        </div></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="10881" style='display:none;'>
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_10881c">
                CONTROL
            </a>
        </h4>
        
    </div>
    <div id="s_10881c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G3400_C69399" id="LblG3400_C69399">Agente</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G3400_C69399" value="<?php isset($userid) ? NombreAgente($userid) : getNombreUser($token);?>" readonly name="G3400_C69399"  placeholder="Agente"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G3400_C69400" id="LblG3400_C69400">Fecha</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G3400_C69400" value="<?php echo date('Y-m-d H:i:s');?>" readonly name="G3400_C69400"  placeholder="Fecha"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G3400_C69401" id="LblG3400_C69401">Hora</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G3400_C69401" value="<?php echo date('H:i:s');?>" readonly name="G3400_C69401"  placeholder="Hora"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G3400_C69402" id="LblG3400_C69402">Campaña</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G3400_C69402" value="<?php if(isset($_GET["campana_crm"])){ $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
                $resCampa = $mysqli->query($cmapa);
                $dataCampa = $resCampa->fetch_array(); echo $dataCampa["CAMPAN_Nombre____b"]; } else { echo "NO TIENE CAMPAÑA";}?>" readonly name="G3400_C69402"  placeholder="Campaña"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="11086" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_11086c">
                LISTASSS
            </a>
        </h4>
        
    </div>
    <div id="s_11086c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C70150" id="LblG3400_C70150">LISTAA</label><select  class="form-control G3400_C70150 input-sm select2"  style="width: 100%;" name="G3400_C70150" id="G3400_C70150">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4405 ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        if($obje->OPCION_ConsInte__b ==0){
                            echo "<option selected='true' value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                                    }    
                                    
                                ?>
                            </select></div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C70151" id="LblG3400_C70151">LISTAB</label><select  class="form-control G3400_C70151 input-sm select2"  style="width: 100%;" name="G3400_C70151" id="G3400_C70151">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4406 ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        if($obje->OPCION_ConsInte__b ==0){
                            echo "<option selected='true' value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                                    }    
                                    
                                ?>
                            </select></div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C70152" id="LblG3400_C70152">LISTAC</label><select  class="form-control G3400_C70152 input-sm select2"  style="width: 100%;" name="G3400_C70152" id="G3400_C70152">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4407 ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        if($obje->OPCION_ConsInte__b ==0){
                            echo "<option selected='true' value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                                    }    
                                    
                                ?>
                            </select></div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<div id="10880" >
<h3 class="box box-title"></h3>

</div>

<?php if(isset($_GET["quality"]) && $_GET["quality"]=="1") : ?>
<div class="panel box box-primary" id="s_11599" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_11599c">
                calidad
            </a>
        </h4>
        <a style="float: right;" class="btn btn-success pull-right FinalizarCalificacion" role="button" >Finalizar Calificacion&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-paper-plane-o"></i></a>
    </div>
    <div id="s_11599c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C113521" id="LblG3400_C113521">a1</label><select  class="form-control G3400_C113521 input-sm select2"  style="width: 100%;" name="G3400_C113521" id="G3400_C113521">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4614 ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        if($obje->OPCION_ConsInte__b ==0){
                            echo "<option selected='true' value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                                    }    
                                    
                                ?>
                            </select></div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C113522" id="LblG3400_C113522">a2</label><select  class="form-control G3400_C113522 input-sm select2"  style="width: 100%;" name="G3400_C113522" id="G3400_C113522">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4614 ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        if($obje->OPCION_ConsInte__b ==0){
                            echo "<option selected='true' value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                                    }    
                                    
                                ?>
                            </select></div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C113523" id="LblG3400_C113523">a3</label><select  class="form-control G3400_C113523 input-sm select2"  style="width: 100%;" name="G3400_C113523" id="G3400_C113523">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4614 ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        if($obje->OPCION_ConsInte__b ==0){
                            echo "<option selected='true' value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                                    }    
                                    
                                ?>
                            </select></div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G3400_C113525" id="LblG3400_C113525">ESTADO_CALIDAD_Q_DY</label><select disabled class="form-control G3400_C113525 input-sm select2"  style="width: 100%;" name="G3400_C113525" id="G3400_C113525">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = -3 ORDER BY LISOPC_Nombre____b ASC";
    
                                    $obj = $mysqli->query($Lsql);
                                    while($obje = $obj->fetch_object()){
                                        if($obje->OPCION_ConsInte__b ==-203){
                            echo "<option selected='true' value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }else{
                            echo "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }
                                    }    
                                    
                                ?>
                            </select></div>
                    <!-- FIN DEL CAMPO TIPO LISTA -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO DECIMAL -->
                    <!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G3400_C113524" id="LblG3400_C113524">CALIFICACION_Q_DY</label>
                        <input type="number" class="form-control input-sm Decimal "  value="<?php if (isset($_GET['G3400_C113524'])) {
                            echo $_GET['G3400_C113524'];
                        } ?>"  name="G3400_C113524" id="G3400_C113524" placeholder="CALIFICACION_Q_DY">
                    </div>
                    <!-- FIN DEL CAMPO TIPO DECIMAL -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G3400_C113526" id="LblG3400_C113526">COMENTARIO_CALIDAD_Q_DY</label>
                        <textarea class="form-control input-sm" name="G3400_C113526" id="G3400_C113526"  value="<?php if (isset($_GET['G3400_C113526'])) {
                            echo $_GET['G3400_C113526'];
                        } ?>" placeholder="COMENTARIO_CALIDAD_Q_DY"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G3400_C113527" id="LblG3400_C113527">COMENTARIO_AGENTE_Q_DY</label>
                        <textarea class="form-control input-sm" name="G3400_C113527" id="G3400_C113527" readonly value="<?php if (isset($_GET['G3400_C113527'])) {
                            echo $_GET['G3400_C113527'];
                        } ?>" placeholder="COMENTARIO_AGENTE_Q_DY"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="bootstrap-datepicker">
                        <div class="form-group">
                            <label for="G3400_C113528" id="LblG3400_C113528">FECHA_AUDITADO_Q_DY</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm Fecha" value="<?php if (isset($_GET['G3400_C113528'])) {
                            echo $_GET['G3400_C113528'];
                        } ?>" readonly name="G3400_C113528" id="G3400_C113528" placeholder="YYYY-MM-DD" nombre="FECHA_AUDITADO_Q_DY">
                                <div class="input-group-addon" id="DTP_G3400_C113528">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G3400_C113529" id="LblG3400_C113529">NOMBRE_AUDITOR_Q_DY</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G3400_C113529" value="<?php if (isset($_GET['G3400_C113529'])) {
                            echo $_GET['G3400_C113529'];
                        } ?>" readonly name="G3400_C113529"  placeholder="NOMBRE_AUDITOR_Q_DY"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->


                <div class="row">
                    <div class="col-md-12 col-xs-12">       
                        <!--Audio Con Controles -->
                        <audio id="Abtns_11599" controls="controls" style="width: 100%">
                            <source id="btns_11599" src="" type="audio/mp3"/>
                        </audio>
                    </div>
                    <input type="hidden" name="IdProyecto" id="IdProyecto" value="">
                </div>

                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="row" style="background-color: #FAFAFA; ">
    <br/>
    <?php if(isset($_GET['user'])){ ?>
    <div class="col-md-10 col-xs-9">
        <div class="form-group">
            <select class="form-control input-sm tipificacion" name="tipificacion" id="G3400_C69394">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = 4342;";
                $obj = $mysqli->query($Lsql);
                while($obje = $obj->fetch_object()){
                    echo "<option value='".$obje->OPCION_ConsInte__b."' efecividad = '".$obje->MONOEF_EFECTIVA__B."' monoef='".$obje->MONOEF_ConsInte__b."' TipNoEF = '".$obje->MONOEF_TipNo_Efe_b."' cambio='".$obje->LISOPC_CambRepr__b."' importancia = '".$obje->MONOEF_Importanc_b."' contacto='".$obje->MONOEF_Contacto__b."'>".($obje->OPCION_Nombre____b)."</option>";

                }          
                ?>
            </select>
            
            <input type="hidden" name="Efectividad" id="Efectividad" value="0">
            <input type="hidden" name="MonoEf" id="MonoEf" value="0">
            <input type="hidden" name="TipNoEF" id="TipNoEF" value="0">
            <input type="hidden" name="FechaInicio" id="FechaInicio" value="0">
            <input type="hidden" name="FechaFinal" id="FechaFinal" value="0">
            <input type="hidden" name="MonoEfPeso" id="MonoEfPeso" value="0">
            <input type="hidden" name="ContactoMonoEf" id="ContactoMonoEf" value="0">
        </div>
    </div>
    <div class="col-md-2 col-xs-3" style="text-align: center;">
        <button class="btn btn-primary btn-block" id="Save" type="button">
            Cerrar Gesti&oacute;n
        </button>
        <a id="errorGestion" style="text-align: center; font-size: 12px; color: gray; cursor: pointer;">
            <u>Cambiar registro</u>
        </a>
    </div>
    <?php }else{ ?>
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <select class="form-control input-sm tipificacion" name="tipificacion" id="G3400_C69394">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = 4342;";
                $obj = $mysqli->query($Lsql);
                while($obje = $obj->fetch_object()){
                    echo "<option value='".$obje->OPCION_ConsInte__b."' efecividad = '".$obje->MONOEF_EFECTIVA__B."' monoef='".$obje->MONOEF_ConsInte__b."' TipNoEF = '".$obje->MONOEF_TipNo_Efe_b."' cambio='".$obje->LISOPC_CambRepr__b."' importancia = '".$obje->MONOEF_Importanc_b."' contacto='".$obje->MONOEF_Contacto__b."' >".($obje->OPCION_Nombre____b)."</option>";

                }            
                ?>
            </select>
            
            <input type="hidden" name="Efectividad" id="Efectividad" value="0">
            <input type="hidden" name="MonoEf" id="MonoEf" value="0">
            <input type="hidden" name="TipNoEF" id="TipNoEF" value="0">
            <input type="hidden" name="FechaInicio" id="FechaInicio" value="0">
            <input type="hidden" name="FechaFinal" id="FechaFinal" value="0">
            <input type="hidden" name="MonoEfPeso" id="MonoEfPeso" value="0">
            <input type="hidden" name="ContactoMonoEf" id="ContactoMonoEf" value="0">
        </div>
    </div>
    <?php } ?>
</div>
<div class="row" style="background-color: #FAFAFA; <?php if(isset($_GET['sentido']) && $_GET['sentido'] == '2'){ echo ""; } ?> ">
    <div class="col-md-4 col-xs-4">
        <div class="form-group">
            <select class="form-control input-sm reintento" name="reintento" id="G3400_C69395">
                <option value="0">Reintento</option>
                <option value="1">REINTENTO AUTOMATICO</option>
                <option value="2">AGENDADO</option>
                <option value="3">NO REINTENTAR</option>
            </select>     
        </div>
    </div>
    <div class="col-md-4 col-xs-4">
        <div class="form-group">
            <input type="text" name="TxtFechaReintento" id="G3400_C69396" class="form-control input-sm TxtFechaReintento" placeholder="Fecha Reintento"  >
        </div>
    </div>
    <div class="col-md-4 col-xs-4" style="text-align: left;">
        <div class="form-group">
            <input type="text" name="TxtHoraReintento" id="G3400_C69397" class="form-control input-sm TxtHoraReintento" placeholder="Hora Reintento">
        </div>
    </div>
</div>
<div class="row" style="background-color: #FAFAFA;">
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <textarea class="form-control input-sm textAreaComentarios" name="textAreaComentarios" id="G3400_C69398" placeholder="Observaciones"></textarea>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- SECCION : PAGINAS INCLUIDAS -->

<?php 

    include(__DIR__ ."/../pies.php");

?>
<script type="text/javascript" src="formularios/G3400/G3400_eventos.js"></script>
<?php require_once "G3400_extender_funcionalidad.php";?>
<?php require_once __DIR__."/../enviarSms_Mail.php";?>
<?php require_once __DIR__."/../integracionesCrm.php";?>

<script type="text/javascript">
    function bindEvent(element, eventName, eventHandler) {
        if (element.addEventListener) {
            element.addEventListener(eventName, eventHandler, false);
        } else if (element.attachEvent) {
            element.attachEvent('on' + eventName, eventHandler);
        }
    }
    
    //escuchar mensajes de  otro formulario
    bindEvent(window, 'message', function (e) {
        console.log(e.data);
        
        
    });
    
    //enviar mensajes al formulario padre
    var sendMessage = function (msg) {
        window.parent.postMessage(msg, '*');
    };    
    var messageButton = document.getElementById('Save');    
    bindEvent(messageButton, 'click', function (e) {
        var mensaje;
        sendMessage('' + mensaje);
    });

    //JDBD - Funcion para descargar los adjuntos
    function bajarAdjunto(id){

        var strURL_t = $("#"+id).attr("adjunto");

        if (strURL_t != "") {

            location.href='<?=$url_crud;?>?adjunto='+strURL_t;
            
        }


    }
    
    

    $(function(){
    // JDBD Envio de calificacion por correo.
    //NBG - Esto es para mostrar la secciÃ³n de calidad solo cuando se ingrese por esta
    //////////////////////////////////////////////////////////////////////////////////
        
    
<?php
    //JDBD - validamos que no estemos en la estacion
    if(!isset($_GET["id_gestion_cbx"])){
        //JDBD - validamos que estemos en el modulo calidad
        if(isset($_SESSION["QUALITY"]) && $_SESSION["QUALITY"] ==1){
            //JDBD - validamos que tenga permisos para acceder a calidad.
            if(isset($_SESSION["CARGO"]) && ($_SESSION["CARGO"] == "calidad" || $_SESSION["CARGO"] == "administrador" || $_SESSION["CARGO"] == "super-administrador")){?>
                //JDBD abrimos el modal de correos.
                $(".FinalizarCalificacion").click(function(){
                    $("#calidad").val("1");
                    $("#enviarCalificacion").modal("show");
                });

                $("#sendEmails").click(function(){
                    $("#Save").click();
                    $("#loading").attr("hidden",false);
                    var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                    var cajaCorreos = $("#cajaCorreos").val();

                    if (cajaCorreos == null || cajaCorreos == "") {
                        cajaCorreos = "";
                    }else{
                        cajaCorreos = cajaCorreos.replace(/ /g, "");
                        cajaCorreos = cajaCorreos.replace(/,,,,,/g, ",");
                        cajaCorreos = cajaCorreos.replace(/,,,,/g, ",");
                        cajaCorreos = cajaCorreos.replace(/,,,/g, ",");
                        cajaCorreos = cajaCorreos.replace(/,,/g, ",");

                        if (cajaCorreos[0] == ",") {
                            cajaCorreos = cajaCorreos.substring(1);
                        }

                        if (cajaCorreos[cajaCorreos.length-1] == ",") {
                            cajaCorreos = cajaCorreos.substring(0,cajaCorreos.length-1);
                        }

                        var porciones = cajaCorreos.split(",");

                        for (var i = 0; i < porciones.length; i++) {
                            if (!emailRegex.test(porciones[i])) {
                                porciones.splice(i, 1);
                            }
                        }

                        cajaCorreos = porciones.join(",");
                    }

                    var formData = new FormData($("#FormularioDatos")[0]);
                    formData.append("IdGestion",$("#IdGestion").val());
                    formData.append("IdGuion",<?=$_GET["formulario"];?>);
                    formData.append("IdCal",<?=$_SESSION["IDENTIFICACION"];?>);
                    formData.append("Correos",cajaCorreos);

                    $.ajax({
                        url: "<?=$url_crud;?>?EnviarCalificacion=si",  
                        type: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data){
                            alertify.success("¡Calificación Enviada!");
                            window.location.reload();
                        },
                        error : function(){
                            alertify.error("No se pudo enviar la calificacion.");   
                        },
                        complete : function(){
                            $("#loading").attr("hidden",true);
                            $("#CerrarCalificacion").click();
                        }

                    }); 
                    
                });
                
                $("#s_11599").attr("hidden", false);
    <?php   }
        }
    }
?>      
    
    //JDBD - Esta seccion es solo para la interaccion con el formulario Padre
    /////////////////////////////////////////////////////////////////////////
    <?php if(isset($_GET["yourfather"]) && isset($_GET["idFather"]) && isset($_GET["pincheCampo"])){ ?>
        <?php 
            if($_GET["yourfather"] != "NULL"){ 
                if($_GET["yourfather"] == "-1") {
                    if(isset($_GET["token"]) && isset($_GET["idFather"])){ ?>
                        $("#G3400_C<?=$_GET['pincheCampo'];?>").attr("opt","<?=$_GET["idFather"]?>");
                        $("#G3400_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET["idFather"]?>");
                        setTimeout(function(){
                            $("#G3400_C<?=$_GET['pincheCampo'];?>").change();       
                        },1000);                        
                    <?php }else{
                    $sqlMiembro=$mysqli->query("SELECT G{$_GET['formularioPadre']}_CodigoMiembro AS miembro FROM DYALOGOCRM_WEB.G{$_GET['formularioPadre']} WHERE G{$_GET['formularioPadre']}_ConsInte__b={$_GET['idFather']}");
                    if($sqlMiembro && $sqlMiembro-> num_rows ==1){
                        $sqlMiembro=$sqlMiembro->fetch_object();
                        $intMiembro=$sqlMiembro->miembro;
                    }
        ?>
                        $("#G3400_C<?=$_GET['pincheCampo'];?>").attr("opt","<?=$intMiembro?>");
                        $("#G3400_C<?=$_GET['pincheCampo'];?>").val("<?=$intMiembro?>");
                        setTimeout(function(){
                            $("#G3400_C<?=$_GET['pincheCampo'];?>").change();       
                        },1000);                        
                <?php } ?>
        <?php }else{ ?>
                $("#G3400_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET['yourfather'];?>");
        <?php } ?>        
        <?php }else{ ?>
            if(document.getElementById("G3400_C<?=$_GET['pincheCampo'];?>").type == "select-one"){
                $.ajax({
                    url      : '<?=$url_crud;?>?Combo_Guion_G<?php echo $_GET['formulario'];?>_C<?php echo $_GET['pincheCampo']; ?>=si',
                    type     : 'POST',
                    data     : { q : <?php echo $_GET["idFather"]; ?> },
                    success  : function(data){
                        $("#G<?php echo $_GET["formulario"]; ?>_C<?php echo $_GET["pincheCampo"]; ?>").html(data);
                    }
                });
            }else{
                $("#G3400_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET['idFather'];?>");
            }
        <?php } ?>
        
    <?php } ?>
    /////////////////////////////////////////////////////////////////////////
    <?php if (!isset($_GET["view"])) {?>
        $("#add").click(function(){
            
            //JDBD - Damos el valor asignado por defecto a este campo.
            $("#G3400_C69401").val("<?php echo date('H:i:s');?>");
            $("#G3400_C70150").val("0").trigger("change");
            $("#G3400_C70151").val("0").trigger("change");
            $("#G3400_C70152").val("0").trigger("change");
            $("#G3400_C113521").val("0").trigger("change");
            $("#G3400_C113522").val("0").trigger("change");
            $("#G3400_C113523").val("0").trigger("change");
            $("#G3400_C113525").val("-203").trigger("change");
            
            
        });
    <?php } ?>;
    var meses = new Array(12);
    meses[0] = "01";
    meses[1] = "02";
    meses[2] = "03";
    meses[3] = "04";
    meses[4] = "05";
    meses[5] = "06";
    meses[6] = "07";
    meses[7] = "08";
    meses[8] = "09";
    meses[9] = "10";
    meses[10] = "11";
    meses[11] = "12";

    var d = new Date();
    var h = d.getHours();
    var horas = (h < 10) ? '0' + h : h;
    var dia = d.getDate();
    var dias = (dia < 10) ? '0' + dia : dia;
    var fechaInicial = d.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias + ' '+ horas +':'+d.getMinutes()+':'+d.getSeconds();
    $("#FechaInicio").val(fechaInicial);
            

    //Esta es por si lo llaman en modo formulario de edicion LigthBox
    <?php if(isset($_GET['registroId'])){ ?>
    $.ajax({
        url      : '<?=$url_crud;?>',
        type     : 'POST',
        data     : { CallDatos : 'SI', id : <?php echo $_GET['registroId']; ?> },
        dataType : 'json',
        success  : function(data){
            //recorrer datos y enviarlos al formulario
            $.each(data, function(i, item) {
                     
                $("#G3400_C69405").val(item.G3400_C69405); 
                $("#G3400_C69650").val(item.G3400_C69650); 
                $("#G3400_C70063").val(item.G3400_C70063); 
                $("#G3400_C69394").val(item.G3400_C69394).trigger("change");
                $("#opt_"+item.G3400_C69394).prop("checked",true).trigger("change");  
                $("#G3400_C69395").val(item.G3400_C69395).trigger("change");
                $("#opt_"+item.G3400_C69395).prop("checked",true).trigger("change");  
                $("#G3400_C69396").val(item.G3400_C69396);
                $("#G3400_C69397").val(item.G3400_C69397).trigger("change");  
                $("#G3400_C69398").val(item.G3400_C69398); 
                $("#G3400_C69399").val(item.G3400_C69399); 
                $("#G3400_C69400").val(item.G3400_C69400); 
                $("#G3400_C69401").val(item.G3400_C69401); 
                $("#G3400_C69402").val(item.G3400_C69402);   
                if(item.G3400_C69403 == "1"){
                    $("#G3400_C69403").prop('checked', true);
                }else{
                    $("#G3400_C69403").prop('checked', false);
                }    
                if(item.G3400_C69404 == "1"){
                    $("#G3400_C69404").prop('checked', true);
                }else{
                    $("#G3400_C69404").prop('checked', false);
                }  
                $("#G3400_C70150").val(item.G3400_C70150).trigger("change");
                $("#opt_"+item.G3400_C70150).prop("checked",true).trigger("change");  
                $("#G3400_C70151").attr("opt",item.G3400_C70151);  
                $("#G3400_C70152").attr("opt",item.G3400_C70152);  
                $("#G3400_C113521").val(item.G3400_C113521).trigger("change");
                $("#opt_"+item.G3400_C113521).prop("checked",true).trigger("change");  
                $("#G3400_C113522").val(item.G3400_C113522).trigger("change");
                $("#opt_"+item.G3400_C113522).prop("checked",true).trigger("change");  
                $("#G3400_C113523").val(item.G3400_C113523).trigger("change");
                $("#opt_"+item.G3400_C113523).prop("checked",true).trigger("change");  
                $("#G3400_C113525").val(item.G3400_C113525).trigger("change");
                $("#opt_"+item.G3400_C113525).prop("checked",true).trigger("change");  
                $("#G3400_C113524").val(item.G3400_C113524); 
                $("#G3400_C113526").val(item.G3400_C113526); 
                $("#G3400_C113527").val(item.G3400_C113527); 
                $("#G3400_C113528").val(item.G3400_C113528); 
                $("#G3400_C113529").val(item.G3400_C113529);
                
                $("#h3mio").html(item.principal);

            });

            //Deshabilitar los campos 3

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

        $("#hidId").val(<?php echo $_GET['registroId'];?>);
        idTotal = <?php echo $_GET['registroId'];?>;

        $("#TxtFechaReintento").attr('disabled', true);
        $("#TxtHoraReintento").attr('disabled', true); 
        

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET['registroId'];?>)

        <?php } ?>

        <?php if(isset($_GET['user'])){ ?>
            /*
            vamosRecargaLasGrillasPorfavor('<?php echo $_GET['user'];?>');
            idTotal = <?php echo $_GET['user'];?>; */
        <?php } ?>

        $("#refrescarGrillas").click(function(){
            
            
        });

        //Esta es la funcionalidad de los Tabs
        
 
        //Select2 estos son los guiones
        


    $("#G3400_C70150").select2();

    $("#G3400_C70151").select2();

    $("#G3400_C70152").select2();

    $("#G3400_C113521").select2();

    $("#G3400_C113522").select2();

    $("#G3400_C113523").select2();

    $("#G3400_C113525").select2();
        //datepickers
        

        $("#G3400_C69396").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        $("#DTP_G3400_C69396").click(function(){
            $("#G3400_C69396").focus();
        });

        //Timepickers
        


        //Timepicker
        var options = { //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: 'Hora Agenda', //The Wickedpicker's title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker's input clearable (has clickable "x")
        }; 
        $("#G3400_C69397").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }
            }
            $("#G3400_C69397").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#G3400_C69397").val($(this).val()).trigger("change")});
        });
        $("#G3400_C69397").change();
        $("#TMP_G3400_C69397").click(function(){
            $("#G3400_C69397").focus();
        });

        //Validaciones numeros Enteros
        

        $("#G3400_C69650").numeric();
                

        //Validaciones numeros Decimales
        

        $("#G3400_C113524").numeric({ decimal : ".",  negative : false, scale: 4 });
                

        /* Si son d formulas */
        


        //Si tienen dependencias

        


    //function para TELEFONO 

    $("#TLF_G3400_C70063").click(function(){
        strTel_t=$("#G3400_C70063").val();
        llamarDesdeBtnTelefono(strTel_t);
    });

    //function para LISTAA 

    $(".G3400_C70150").change(function(){  
        //Esto es la parte de las listas dependientes
        

        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '4406' , idPadre : $(this).val() },
            success : function(data){
                var optG3400_C70151 = $("#G3400_C70151").attr("opt");
                $("#G3400_C70151").html(data);
                if (optG3400_C70151 != null) {
                    $("#G3400_C70151").val(optG3400_C70151).trigger("change");
                }
            }
        });
        
    });

    //function para LISTAB 

    $(".G3400_C70151").change(function(){  
        //Esto es la parte de las listas dependientes
        

        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '4407' , idPadre : $(this).val() },
            success : function(data){
                var optG3400_C70152 = $("#G3400_C70152").attr("opt");
                $("#G3400_C70152").html(data);
                if (optG3400_C70152 != null) {
                    $("#G3400_C70152").val(optG3400_C70152).trigger("change");
                }
            }
        });
        
    });

    //function para LISTAC 

    $(".G3400_C70152").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para a1 

    $(".G3400_C113521").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para a2 

    $(".G3400_C113522").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para a3 

    $(".G3400_C113523").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para ESTADO_CALIDAD_Q_DY 

    $(".G3400_C113525").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });
        
        //Funcionalidad del botob guardar
        


        function cierroGestion(){
                var bol_respuesta = before_save();
                if(bol_respuesta){            
                    $("#Save").attr("disabled",true);
                    var form = $("#FormularioDatos");
                    //Se crean un array con los datos a enviar, apartir del formulario 
                    var formData = new FormData($("#FormularioDatos")[0]);
                    $.ajax({
                       url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>&campana_crm=<?php if(isset($_GET['campana_crm'])){ echo $_GET['campana_crm']; } else{ echo "0"; } ?>',  
                        type: 'POST',
                        data: formData,
                        dataType: "JSON",
                        cache: false,
                        contentType: false,
                        processData: false,
                        //una vez finalizado correctamente
                        success: function(data){
                            try{
                                afterSave(data);
                            }catch(e){}
                            if(data.estado == 'ok'){
                                llamarIntegracionWS(5,112017,3400)

                                <?php if(!isset($_GET['campan'])){ ?>
                                    if($("#calidad").val() =="0"){
                                    
                                    //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                    if($("#oper").val() == 'add'){
                                        idTotal = data.mensaje;
                                    }else{
                                        idTotal= $("#hidId").val();
                                    }
                                   
                                    //Limpiar formulario
                                    form[0].reset();
                                    after_save();
                                    <?php if(isset($_GET['registroId'])){ ?>
                                        var ID = <?=$_GET['registroId'];?>
                                    <?php }else{ ?> 
                                        var ID = data.mensaje;
                                    <?php } ?>  
                                    $.ajax({
                                        url      : '<?=$url_crud;?>',
                                        type     : 'POST',
                                        data     : { CallDatos : 'SI', id : ID },
                                        dataType : 'json',
                                        success  : function(data){
                                            //recorrer datos y enviarlos al formulario
                                            $.each(data, function(i, item) {
                                            
 
                                                $("#G3400_C69405").val(item.G3400_C69405);
 
                                                $("#G3400_C69650").val(item.G3400_C69650);
 
                                                $("#G3400_C70063").val(item.G3400_C70063);
 
                    $("#G3400_C69394").val(item.G3400_C69394).trigger("change");
                    $("#opt_"+item.G3400_C69394).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C69395").val(item.G3400_C69395).trigger("change");
                    $("#opt_"+item.G3400_C69395).prop("checked",true).trigger("change"); 
 
                                                $("#G3400_C69396").val(item.G3400_C69396);

                                                $("#G3400_C69397").val(item.G3400_C69397).trigger("change"); 
 
                                                $("#G3400_C69398").val(item.G3400_C69398);
 
                                                $("#G3400_C69399").val(item.G3400_C69399);
 
                                                $("#G3400_C69400").val(item.G3400_C69400);
 
                                                $("#G3400_C69401").val(item.G3400_C69401);
 
                                                $("#G3400_C69402").val(item.G3400_C69402);
      
                                                if(item.G3400_C69403 == "1"){
                                                   $("#G3400_C69403").prop('checked', true);
                                                }else{
                                                    $("#G3400_C69403").prop('checked', false);
                                                } 
      
                                                if(item.G3400_C69404 == "1"){
                                                   $("#G3400_C69404").prop('checked', true);
                                                }else{
                                                    $("#G3400_C69404").prop('checked', false);
                                                } 
 
                    $("#G3400_C70150").val(item.G3400_C70150).trigger("change");
                    $("#opt_"+item.G3400_C70150).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C70151").attr("opt",item.G3400_C70151); 
 
                    $("#G3400_C70152").attr("opt",item.G3400_C70152); 
 
                    $("#G3400_C113521").val(item.G3400_C113521).trigger("change");
                    $("#opt_"+item.G3400_C113521).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C113522").val(item.G3400_C113522).trigger("change");
                    $("#opt_"+item.G3400_C113522).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C113523").val(item.G3400_C113523).trigger("change");
                    $("#opt_"+item.G3400_C113523).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C113525").val(item.G3400_C113525).trigger("change");
                    $("#opt_"+item.G3400_C113525).prop("checked",true).trigger("change"); 
 
                                                $("#G3400_C113524").val(item.G3400_C113524);
 
                                                $("#G3400_C113526").val(item.G3400_C113526);
 
                                                $("#G3400_C113527").val(item.G3400_C113527);
 
                                                $("#G3400_C113528").val(item.G3400_C113528);
 
                                                $("#G3400_C113529").val(item.G3400_C113529);
                                                $("#h3mio").html(item.principal);
                                            });

                                            //Deshabilitar los campos 2

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
                                    $("#hidId").val(ID);  
                                    }else{
                                        $("#calidad").val("0");
                                    }
                                <?php }else{ 
                                    if(!isset($_GET['formulario'])){
                                ?>

                                    $.ajax({
                                        url   : 'formularios/generados/PHP_Ejecutar.php?action=EDIT&tiempo=<?php echo $tiempoDesdeInicio;?>&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?>&ConsInteRegresado='+data.mensaje +'<?php if(isset($_GET['token'])) { echo "&token=".$_GET['token']; }?><?php if(isset($_GET['id_gestion_cbx'])) { echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?>&campana_crm=<?php if(isset($_GET['campana_crm'])){ echo $_GET['campana_crm']; }else{ echo "0"; } ?><?php if(isset($_GET['predictiva'])) { echo "&predictiva=".$_GET['predictiva'];}?><?php if(isset($_GET['consinte'])) { echo "&consinte=".$_GET['consinte']; }?>&cerrarViaPost=true',
                                        type  : "post",
                                        dataType : "json",
                                        data  : formData,
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        success : function(xt){
                                            borrarStorage($("#CampoIdGestionCbx").val());
                                            
                                            try{
                                                var origen="formulario";
                                                finalizarGestion(xt,origen);
                                            }catch{
                                                var data={
                                                    accion:"cerrargestion",
                                                    datos:xt
                                                };
                                                parent.postMessage(data, '*');
                                            }
                                        }
                                    });
                                    
                
                                <?php } 
                                    }
                                ?>            
                            }else{
                                //Algo paso, hay un error
                                $("#Save").attr('disabled', false);
                                alertify.error(data.mensaje);
                            }                
                        },
                        //si ha ocurrido un error
                        error: function(){
                            after_save_error();
                            $("#Save").attr('disabled', false);
                            alertify.error('Un error ha ocurrido y no pudimos guardar la informaciÃ³n');
                        }
                    });
                }        
        }
        
        $("#Save").click(function(){
            var d = new Date();
            var h = d.getHours();
            var horas = (h < 10) ? '0' + h : h;
            var dia = d.getDate();
            var dias = (dia < 10) ? '0' + dia : dia;
            var fechaFinal = d.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias + ' '+ horas +':'+d.getMinutes()+':'+d.getSeconds();
            $("#FechaFinal").val(fechaFinal);
            var valido = 0;
            
            if($(".tipificacion").val() == '0'){
                alertify.error("Es necesaria la tipificaciÃ³n!");
                valido = 1;
            }

            $(".saltoRequerido").each(function() {
                if ($(this).prop("disabled")==false) {
                    if (this.type == "select-one") {
                        if ($(this).val() == 0 || $(this).val() == null || $(this).val()== -1) {
                            $(this).closest(".form-group").addClass("has-error");
                            valido = 1;
                        }
                    }else{
                        if ($(this).val()=="") {
                            $(this).closest(".form-group").addClass("has-error");
                            valido = 1;
                        }
                    }
                }
            });

            $(".ReqForTip").each(function() {
                if ($(this).prop("disabled")==false) {
                    if (this.type == "select-one") {
                        if ($(this).val() == 0 || $(this).val() == null || $(this).val()== -1) {
                            $(this).closest(".form-group").addClass("has-error");
                            alertify.error("La lista debe ser diligenciada.");
                            valido = 1;
                        }
                    }else{
                        if ($(this).val()=="") {
                            $(this).closest(".form-group").addClass("has-error");
                            alertify.error("El campo debe ser diligenciado.");
                            valido = 1;
                        }
                    }
                }
            });

            if($(".reintento").val() == '2'){
                if($(".TxtFechaReintento").val().length < 1){
                    alertify.error("Es necesario llenar la fecha de reintento!");
                    $(".TxtFechaReintento").focus();
                    valido = 1;
                }

                if($(".TxtHoraReintento").val().length < 1){
                    alertify.error("Es necesario llenar la hora de reintento!");
                    $(".TxtHoraReintento").focus();
                    valido = 1;
                }
            }
            
            /*let booValido=false;
            let showModal=false;
            let strPatrones="";
            let strEjemplo="";
            $('.error-phone').remove();
            $.each($('.telefono').prev(), function(b,key){
                if(this.value !="" && this.value !=0){
                    let strTelefono=this.value;
                    $.each(arr['patron_regexp'], function(i, item){
                        let regex=arr['patron_regexp'][i];
                        let delComillas=/'/g;
                        regex=regex.replace(delComillas,"");
                        let patron= new RegExp(regex);
                        if(patron.test(strTelefono)){
                            booValido=true;
                        }
                        strPatrones+=arr['patron'][i]+'  ';
                        strEjemplo+=arr['patron_ejemplo'][i]+'  ';
                    });
                    if(!booValido){
                        valido=1;
                        showModal=true;
                        $(this).closest(".form-group").append("<span class='error-phone' style='color:red;cursor:pointer' data-toggle='popover' data-trigger='hover' data-content='El número de teléfono digitado no es valido con estos formatos <br> "+strPatrones+" <br> Ejemplo: <br>"+strEjemplo+"'>Este número de teléfono no es valido <i style='color:red;' class='fa fa-question-circle'></i></span>");
                        $(this).closest(".form-group").addClass("has-error");
                        $('.error-phone').css("margin-top:7px");
                        $(this).focus();
                        $('[data-toggle="popover"]').popover({
                            html : true,
                            placement: "right"
                        });
                    }
                }
                
            });

            if(showModal){
            swal({
                html : true,
                title: "Número de télefono no valido",
                text: 'El registro que está guardando, no tiene ningún teléfono con un formato válido según lo definido.',
                type: "warning",
                confirmButtonText: "dejar los teléfonos así y guardar",
                cancelButtonText : "Modificar el/los télofonos",
                showCancelButton : true,
                closeOnConfirm : true
            },
                function(isconfirm){
                    if(isconfirm){
                        cierroGestion();
                    }else{
                        valido==1
                    }
                });                
            }*/

            if(valido == '0'){
                cierroGestion();
            }
        });
    });

        //funcionalidad del boton Gestion botonCerrarErronea
        




    <?php if(!isset($_GET['view'])) { ?>
    //SECICON : CARGUE INFORMACION EN HOJA DE DATOS
    //Cargar datos de la hoja de datos
    function cargar_hoja_datos(){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        var lastsel2;
        $("#tablaDatos").jqGrid({
            url:'<?=$url_crud;?>?CallDatosJson=si',
            datatype: 'json',
            mtype: 'POST',
            colNames:['id','nombre','DOCUMENTO','TELEFONO','Agente','Fecha','Hora','Campaña','LISTAA','LISTAB','LISTAC','a1','a2','a3','ESTADO_CALIDAD_Q_DY','CALIFICACION_Q_DY','COMENTARIO_CALIDAD_Q_DY','COMENTARIO_AGENTE_Q_DY','FECHA_AUDITADO_Q_DY','NOMBRE_AUDITOR_Q_DY'],
            colModel:[
                //Traigo los datos de la base de dtaos y los defino en que columna va cada uno, tambien definimos con es su forma de edicion, sea Tipo listas, tipo Textos, etc.
                {
                    name:'providerUserId',
                    index:'providerUserId', 
                    width:100,
                    editable:true, 
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
                        name:'G3400_C69405', 
                        index: 'G3400_C69405', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }
 
                    ,
                    {  
                        name:'G3400_C69650', 
                        index:'G3400_C69650', 
                        width:80 ,
                        editable: true, 
                        searchoptions: {
                            sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                        }, 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                $(el).numeric();
                            }
                        } 
                    }

                    ,
                    { 
                        name:'G3400_C70063', 
                        index: 'G3400_C70063', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3400_C69399', 
                        index: 'G3400_C69399', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3400_C69400', 
                        index: 'G3400_C69400', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3400_C69401', 
                        index: 'G3400_C69401', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3400_C69402', 
                        index: 'G3400_C69402', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3400_C70150', 
                        index:'G3400_C70150', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=4405&campo=G3400_C70150'
                        }
                    }

                    ,
                    { 
                        name:'G3400_C70151', 
                        index:'G3400_C70151', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=4406&campo=G3400_C70151'
                        }
                    }

                    ,
                    { 
                        name:'G3400_C70152', 
                        index:'G3400_C70152', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=4407&campo=G3400_C70152'
                        }
                    }

                    ,
                    { 
                        name:'G3400_C113521', 
                        index:'G3400_C113521', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=4614&campo=G3400_C113521'
                        }
                    }

                    ,
                    { 
                        name:'G3400_C113522', 
                        index:'G3400_C113522', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=4614&campo=G3400_C113522'
                        }
                    }

                    ,
                    { 
                        name:'G3400_C113523', 
                        index:'G3400_C113523', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=4614&campo=G3400_C113523'
                        }
                    }

                    ,
                    { 
                        name:'G3400_C113525', 
                        index:'G3400_C113525', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=-3&campo=G3400_C113525'
                        }
                    }

                    ,
                    {  
                        name:'G3400_C113524', 
                        index:'G3400_C113524', 
                        width:80 ,
                        editable: true, 
                        searchoptions: {
                            sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                        }, 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                $(el).numeric({ decimal : ".",  negative : false, scale: 4 });
                            }
                        } 
                    }

                    ,
                    { 
                        name:'G3400_C113526', 
                        index:'G3400_C113526', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3400_C113527', 
                        index:'G3400_C113527', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    {  
                        name:'G3400_C113528', 
                        index:'G3400_C113528', 
                        width:120 ,
                        editable: true ,
                        formatter: 'text', 
                        searchoptions: {
                            sopt: ['eq', 'ne', 'lt', 'le', 'gt', 'ge']
                        }, 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                $(el).datepicker({
                                    language: "es",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                            },
                            defaultValue: function(){
                                var currentTime = new Date();
                                var month = parseInt(currentTime.getMonth() + 1);
                                month = month <= 9 ? "0"+month : month;
                                var day = currentTime.getDate();
                                day = day <= 9 ? "0"+day : day;
                                var year = currentTime.getFullYear();
                                return year+"-"+month + "-"+day;
                            }
                        }
                    }

                    ,
                    { 
                        name:'G3400_C113529', 
                        index: 'G3400_C113529', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }
            ],
            pager: "#pager" ,
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastsel2){
                    
                }
                lastsel2=rowid;
            },
            rowNum: 50,
            rowList:[50,100],
            loadonce: false,
            sortable: true,
            sortname: 'G3400_C69405',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'PRUEBAS',
            editurl:"<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>",
            autowidth: true
            
        });

        $('#tablaDatos').navGrid("#pager", { add:false, del: true , edit: false });
        $('#tablaDatos').inlineNav('#pager',
        // the buttons to appear on the toolbar of the grid
        { 
            edit: true, 
            add: true, 
            cancel: true,
            editParams: {
                keys: true,
            },
            addParams: {
                keys: true
            }
        });
      
        //para cuando se Maximice o minimize la pantalla.
        $(window).bind('resize', function() {
            $("#tablaDatos").setGridWidth($(window).width());
        }).trigger('resize'); 
    }

    //JDBD-2020-05-03 : Nueva funcion de filtro Avanzado y Scroll. 
    function llenarListaNavegacion(strScroll_p,intInicio_p,intFin_p){

        var strHTMLTr_t = "";
        var arrNumerosFiltros_t = new Array();

        $(".rows").each(function(i){
            arrNumerosFiltros_t[i]=$(this).attr("numero");
        });

        if (arrNumerosFiltros_t.length > 0) {

            var objFormFiltros_t = new FormData($("#forBusquedaAvanzada")[0]);
            objFormFiltros_t.append("arrNumerosFiltros_t",arrNumerosFiltros_t);
            objFormFiltros_t.append("CallDatosJson","SI");
            objFormFiltros_t.append("strScroll_t",strScroll_p);
            objFormFiltros_t.append("inicio_t",intInicio_p);
            objFormFiltros_t.append("fin_t",intFin_p);
            objFormFiltros_t.append("idUsuario",<?=$idUsuario?>);
            objFormFiltros_t.append("tareaBackoffice",<?=$tareaBackoffice;?>);
            objFormFiltros_t.append("muestra",<?=$muestra;?>);
            objFormFiltros_t.append("tareaTipoDist",<?=$tipoDistribucion;?>);

            $.ajax({
                url         : '<?=$url_crud;?>',
                type        : 'POST',
                data        : objFormFiltros_t,
                cache       : false,
                contentType : false,
                processData : false,
                dataType    : 'json',
                success  : function(data){

                    $.each(data, function(i, item){
                        strHTMLTr_t += "<tr class='CargarDatos' id='"+data[i].id+"'>";
                        strHTMLTr_t += "<td>";
                        strHTMLTr_t += "<p style='font-size:14px;'><b>"+data[i].camp1+"</b></p>";
                        strHTMLTr_t += "<p style='font-size:12px; margin-top:-10px;'>"+data[i].camp2+"</p>";
                        strHTMLTr_t += "</td>";
                        strHTMLTr_t += "</tr>";
                    });


                    if (strScroll_p == "no") {
                        $("#tablaScroll").html(strHTMLTr_t);

                        //JDBD - Activamos el click a los nuevos <tr>.
                        busqueda_lista_navegacion();

                        if ( $("#"+idTotal).length > 0) {
                            //JDBD - Damos click al al registro siexiste.
                            $("#"+idTotal).click();
                            $("#"+idTotal).addClass('active'); 
                        }else{
                            //JDBD - Damos click al primer registro de la lista.
                            $(".CargarDatos :first").click();
                        }
                    }else{
                        $("#tablaScroll").append(strHTMLTr_t);
                        busqueda_lista_navegacion();
                    }
                }
            });

        }

    }

    //buscar registro en la Lista de navegacion
    function llenar_lista_navegacion(B,A=null,T=null,F=null,E=null){
        var tr = '';
        $.ajax({
            url      : '<?=$url_crud;?>',
            type     : 'POST',
            data     : { CallDatosJson : 'SI', B : B, A : A, T : T, F : F, E : E, idUsuario : <?=$idUsuario?>, tareaBackoffice: <?php echo $tareaBackoffice; ?>, muestra: <?php echo $muestra; ?>, tareaTipoDist: <?php echo $tipoDistribucion ?>},
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

            $("#IdGestion").val(id);

            
            //buscar los datos
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $.each(data, function(i, item) {
                        

                        $("#G3400_C69405").val(item.G3400_C69405);

                        $("#G3400_C69650").val(item.G3400_C69650);

                        $("#G3400_C70063").val(item.G3400_C70063);
 
                    $("#G3400_C69394").val(item.G3400_C69394).trigger("change");
                    $("#opt_"+item.G3400_C69394).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C69395").val(item.G3400_C69395).trigger("change");
                    $("#opt_"+item.G3400_C69395).prop("checked",true).trigger("change"); 

                        $("#G3400_C69396").val(item.G3400_C69396);
                        $("#G3400_C69397").val(item.G3400_C69397).trigger("change"); 

                        $("#G3400_C69398").val(item.G3400_C69398);

                        $("#G3400_C69399").val(item.G3400_C69399);

                        $("#G3400_C69400").val(item.G3400_C69400);

                        $("#G3400_C69401").val(item.G3400_C69401);

                        $("#G3400_C69402").val(item.G3400_C69402);
    
                        if(item.G3400_C69403 == "1"){
                           $("#G3400_C69403").prop('checked', true);
                        }else{
                            $("#G3400_C69403").prop('checked', false);
                        } 
    
                        if(item.G3400_C69404 == "1"){
                           $("#G3400_C69404").prop('checked', true);
                        }else{
                            $("#G3400_C69404").prop('checked', false);
                        } 
 
                    $("#G3400_C70150").val(item.G3400_C70150).trigger("change");
                    $("#opt_"+item.G3400_C70150).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C70151").attr("opt",item.G3400_C70151); 
 
                    $("#G3400_C70152").attr("opt",item.G3400_C70152); 
 
                    $("#G3400_C113521").val(item.G3400_C113521).trigger("change");
                    $("#opt_"+item.G3400_C113521).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C113522").val(item.G3400_C113522).trigger("change");
                    $("#opt_"+item.G3400_C113522).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C113523").val(item.G3400_C113523).trigger("change");
                    $("#opt_"+item.G3400_C113523).prop("checked",true).trigger("change"); 
 
                    $("#G3400_C113525").val(item.G3400_C113525).trigger("change");
                    $("#opt_"+item.G3400_C113525).prop("checked",true).trigger("change"); 

                        $("#G3400_C113524").val(item.G3400_C113524);

                        $("#G3400_C113526").val(item.G3400_C113526);

                        $("#G3400_C113527").val(item.G3400_C113527);

                        $("#G3400_C113528").val(item.G3400_C113528);

                        $("#G3400_C113529").val(item.G3400_C113529);
                               
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
                },complete : function(data){
                    
                    $.ajax({// JDBD - Obtener el link de la llamada y reproducir
                    url      : '<?=$url_crud;?>?llenarBtnLlamada=si',
                    type     : 'POST',
                    data     : {idReg : id},
                    success  : function(data){
                        var audio = $("#Abtns_11599");
                        $("#btns_11599").attr("src",data+"&streaming=true").appendTo(audio);
                        audio.load();
                    }});
                
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
        }else{
            $(".CargarDatos :first").click();
        } 
        
    }
    
    function CalcularFormula(){
        
    }

    <?php } ?>


    

    function vamosRecargaLasGrillasPorfavor(id){
        
    }
    
    function llamarDesdeBtnTelefono(telefono){
        <?php 
            $campana=0;
            if(isset($_GET["campana_crm"])){
                $campana=$_GET["campana_crm"];
            } 
        ?>
        
        var data={
            accion:"llamadaDesdeG",
            telefono: "A<?=$campana?>"+telefono,
            validarScript: false
        };
        parent.postMessage(data, '*');
    }   
</script>
<script type="text/javascript">
    $(document).ready(function() {
        
        <?php
            if(isset($campSql)){
                //recorro la campaÃ±a para tener los datos que necesito
                /*$resultcampSql = $mysqli->query($campSql);
                while($key = $resultcampSql->fetch_object()){
                    

                    //consulta de datos del usuario
                    $DatosSql = " SELECT ".$key->CAMINC_NomCamPob_b." as campo FROM ".$BaseDatos.".G".$tabla." WHERE G".$tabla."_ConsInte__b=".$_GET['user'];

                    //echo $DatosSql;
                    //recorro la tabla de donde necesito los datos
                    $resultDatosSql = $mysqli->query($DatosSql);
                    if($resultDatosSql){
                        while($objDatos = $resultDatosSql->fetch_object()){ ?>
                            document.getElementById("<?=$key->CAMINC_NomCamGui_b;?>").value = '<?=trim($objDatos->campo);?>';
                    <?php  
                        }   
                    }
                    
                } */  
            }
        ?>
        <?php if(isset($_GET['user'])){ ?>
            
            idTotal = <?php echo $_GET['user'];?>; 
        <?php } ?>
        
    });
</script>
