
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
                            <input type="text" class="form-control" id="cajaCorreos" name="cajaCorreos" placeholder="Ejemplo1@ejem.com, Ejemplo2@ejem.com">
    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <img hidden id="loading" src="/crm_php/assets/plugins/loading.gif" width="30" height="30">&nbsp;&nbsp;&nbsp;
                            <button id="sendEmails" readonly class="btn btn-primary"><i class="fa fa-paper-plane-o"></i>  Enviar Calificacion </button>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="ModalLoading">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong id="TitleModalLoading">Envio De Reporte</strong></h5>
                </div>
                <div class="modal-body">
                    <!-- loading -->
                    <div id="Loading" class="container-loader">
                        <div class="loader">
                            <img src="<?=base_url?>assets/img/loader.gif" style="margin-top: -20%; margin-left: 5%; color: #11D2FD;">
                            <p class="form-label text-black" style="margin-top: -20%; margin-left: 32%;"><strong id="LabelLoading"> ENVIANDO ... </strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes -->
    <div class="box-body">
        <div class="row">
            
            <div class="col-md-3 col-xs-3">
                <!-- CAMPO TIPO FECHA -->
                <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                <div class="bootstrap-datepicker">
                    <div class="form-group">
                        <label for="G2913_C113582" id="LblG2913_C113582">Fecha Inicial</label>
                        <div class="input-group">
                            <input type="text" class="form-control input-sm Fecha" value="" name="G2913_C113582" id="G2913_C113582" placeholder="YYYY-MM-DD" nombre="Fecha Inicial" inputmode="text">
                            <div class="input-group-addon" id="DTP_G2913_C113582">
                                <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN DEL CAMPO TIPO FECHA-->
            </div> <!-- AQUIFINCAMPO -->
            <div class="col-md-3 col-xs-3">
                <!-- CAMPO TIPO FECHA -->
                <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                <div class="bootstrap-datepicker">
                    <div class="form-group">
                        <label for="G2913_C113583" id="LblG2913_C113583">Fecha Final</label>
                        <div class="input-group">
                            <input type="text" class="form-control input-sm Fecha" value="" name="G2913_C113583" id="G2913_C113583" placeholder="YYYY-MM-DD" nombre="Fecha Final" inputmode="text">
                            <div class="input-group-addon" id="DTP_G2913_C113583">
                                <i class="fa fa-calendar-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN DEL CAMPO TIPO FECHA-->
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-3 col-xs-3">
                <div class="form-group">
                    <label style="color:white">...</label>
                    <button class="form-control input-sm btn btn-primary" id="G2913_C113584" ws="0" llave="113584" name="G2913_C113584">Generar Reporte  C_200 </button>
                </div>
            </div> <!-- AQUIFINCAMPO -->
            <div class="col-md-3 col-xs-3">
                <div class="form-group">
                    <label style="color:white">...</label>
                    <button class="form-control input-sm btn btn-primary" id="G2913_C113585" ws="0" llave="113585" name="G2913_C113585">Generar Reporte  C_600 </button>
                </div>
            </div> <!-- AQUIFINCAMPO -->

        </div>
    </div>


                
<input type="hidden" id="IdGestion">
<input type="hidden" id="IdCampana">
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
   $url_crud = "formularios/G2913/G2913_CRUD.php";
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
            $Zsql = "SELECT G2913_ConsInte__b as id, a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2 FROM ".$BaseDatos.".G2913  LEFT JOIN ".$BaseDatos_systema.".LISOPC as a ON a.LISOPC_ConsInte__b = G2913_C47728 WHERE G2913_Usuario = ".$idUsuario." ORDER BY G2913_ConsInte__b DESC LIMIT 0, 50";
        }else{
            $Zsql = "SELECT G2913_ConsInte__b as id, a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2 FROM ".$BaseDatos.".G2913  LEFT JOIN ".$BaseDatos_systema.".LISOPC as a ON a.LISOPC_ConsInte__b = G2913_C47728 ORDER BY G2913_ConsInte__b DESC LIMIT 0, 50";
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
                $Zsql = "SELECT G2913_ConsInte__b as id, a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2 FROM ".$BaseDatos.".G2913 JOIN ".$BaseDatos.".G2913_M".$resultEstpas->muestr." ON G2913_ConsInte__b = G2913_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( (G2913_M".$resultEstpas->muestr."_Estado____b = 0 OR G2913_M".$resultEstpas->muestr."_Estado____b = 1 OR G2913_M".$resultEstpas->muestr."_Estado____b = 3) OR (G2913_M".$resultEstpas->muestr."_Estado____b = 2 AND G2913_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) ) 
                ORDER BY G2913_ConsInte__b DESC LIMIT 0, 50";
            }else{
                $Zsql = "SELECT G2913_ConsInte__b as id, a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2 FROM ".$BaseDatos.".G2913 JOIN ".$BaseDatos.".G2913_M".$resultEstpas->muestr." ON G2913_ConsInte__b = G2913_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( (G2913_M".$resultEstpas->muestr."_Estado____b = 0 OR G2913_M".$resultEstpas->muestr."_Estado____b = 1 OR G2913_M".$resultEstpas->muestr."_Estado____b = 3) OR (G2913_M".$resultEstpas->muestr."_Estado____b = 2 AND G2913_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) )
                AND G2913_M".$resultEstpas->muestr."_ConIntUsu_b = ".$idUsuario." 
                ORDER BY G2913_ConsInte__b DESC LIMIT 0, 50";
            }
            
        }

    }else{
        $userid= isset($userid) ? $userid : "-10";
        $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;
        $Zsql = "SELECT G2913_ConsInte__b as id, a.LISOPC_Nombre____b as camp1 , G2913_C47708 as camp2 FROM ".$BaseDatos.".G2913  LEFT JOIN ".$BaseDatos_systema.".LISOPC as a ON a.LISOPC_ConsInte__b = G2913_C47728 ORDER BY G2913_ConsInte__b DESC LIMIT 0, 50";
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

<div id="8467" style='display:none;'>
    <h3 class="box box-title"></h3>

        <div class="row">
        

            <div class="col-md-12 col-xs-12">

                    <div class="form-group">
                        <label for="G2913_C47722" id="LblG2913_C47722">Agente</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47722" value="<?php isset($userid) ? NombreAgente($userid) : getNombreUser($token);?>" readonly name="G2913_C47722"  placeholder="Agente"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->
  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47723" id="LblG2913_C47723">Fecha</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47723" value="<?php echo date('Y-m-d H:i:s');?>" readonly name="G2913_C47723"  placeholder="Fecha"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47724" id="LblG2913_C47724">Hora</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47724" value="<?php echo date('H:i:s');?>" readonly name="G2913_C47724"  placeholder="Hora"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47725" id="LblG2913_C47725">Campaña</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47725" value="<?php if(isset($_GET["campana_crm"])){ $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
                $resCampa = $mysqli->query($cmapa);
                $dataCampa = $resCampa->fetch_array(); echo $dataCampa["CAMPAN_Nombre____b"]; } else { echo "NO TIENE CAMPAÑA";}?>" readonly name="G2913_C47725"  placeholder="Campaña"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


</div>

<div id="8465" >
<h3 class="box box-title"></h3>

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47708" id="LblG2913_C47708">ORIGEN_DY_WF</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47708" value="<?php if (isset($_GET['G2913_C47708'])) {
                            echo $_GET['G2913_C47708'];
                        } ?>"  name="G2913_C47708"  placeholder="ORIGEN_DY_WF"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47709" id="LblG2913_C47709">OPTIN_DY_WF</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47709" value="<?php if (isset($_GET['G2913_C47709'])) {
                            echo $_GET['G2913_C47709'];
                        } ?>"  name="G2913_C47709"  placeholder="OPTIN_DY_WF"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47710" id="LblG2913_C47710">ESTADO_DY</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47710" value="<?php if (isset($_GET['G2913_C47710'])) {
                            echo $_GET['G2913_C47710'];
                        } ?>"  name="G2913_C47710"  placeholder="ESTADO_DY"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47711" id="LblG2913_C47711">Numero De Línea:</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47711" value="<?php if (isset($_GET['G2913_C47711'])) {
                            echo $_GET['G2913_C47711'];
                        } ?>"  name="G2913_C47711"  placeholder="Numero De Línea:"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47712" id="LblG2913_C47712">Consultar</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47712" value="<?php if (isset($_GET['G2913_C47712'])) {
                            echo $_GET['G2913_C47712'];
                        } ?>"  name="G2913_C47712"  placeholder="Consultar"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47713" id="LblG2913_C47713">Tipo De Servicio:</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47713" value="<?php if (isset($_GET['G2913_C47713'])) {
                            echo $_GET['G2913_C47713'];
                        } ?>"  name="G2913_C47713"  placeholder="Tipo De Servicio:"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47714" id="LblG2913_C47714">Tipo De Servicio 2:</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47714" value="<?php if (isset($_GET['G2913_C47714'])) {
                            echo $_GET['G2913_C47714'];
                        } ?>"  name="G2913_C47714"  placeholder="Tipo De Servicio 2:"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47715" id="LblG2913_C47715">Tipo De Servicio 3:</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47715" value="<?php if (isset($_GET['G2913_C47715'])) {
                            echo $_GET['G2913_C47715'];
                        } ?>"  name="G2913_C47715"  placeholder="Tipo De Servicio 3:"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C47716" id="LblG2913_C47716">Plantilla De Obsevaciones: </label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C47716" value="<?php if (isset($_GET['G2913_C47716'])) {
                            echo $_GET['G2913_C47716'];
                        } ?>"  name="G2913_C47716"  placeholder="Plantilla De Obsevaciones: "></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->


</div>

<div id="8466" >
<h3 class="box box-title"></h3>

</div>

<div id="s_8468" >
    <!-- <h3 class="box box-title"><a style="float: right;" class="btn btn-success pull-right FinalizarCalificacion" role="button" >Finalizar Calificacion&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-paper-plane-o"></i></a></h3> -->

    <div class="row">
        <div class="col-md-12 col-xs-12">       
            <!--Audio Con Controles -->
            <audio id="Abtns_8468" controls="controls" style="width: 100%">
                <source id="btns_8468" src="" type="audio/mp3"/>
            </audio>
        </div>
        <input type="hidden" name="IdProyecto" id="IdProyecto" value="151">
    </div>

</div> 

<div id="8469" >
    <h3 class="box box-title"></h3>

        <div class="row">
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- lIBRETO O LABEL -->
                    <p style="text-align:justify;" id="G2913_C47726">Buenos días|tardes|noches, podría comunicarme con el señor(a) |NombreCliente|</p>
                    <!-- FIN LIBRETO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- lIBRETO O LABEL -->
                    <p style="text-align:justify;" id="G2913_C47727">Mi nombre es |Agente|, le estoy llamando de |Empresa| con el fin de ...</p>
                    <!-- FIN LIBRETO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


</div>

<?php if(isset($_GET["quality"]) && $_GET["quality"]=="1") : ?>
<div class="panel box box-primary" id="s_8606" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_8606c">
                Formulario Calidad
            </a>
        </h4>
        <!-- <a style="float: right;" class="btn btn-success pull-right FinalizarCalificacion" role="button">Finalizar Calificacion&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-paper-plane-o"></i></a>  -->
        <button class="btn btn-success pull-right" id="BtnFinalizarCalificacion" name="BtnFinalizarCalificacion">Finalizar Calificación</button>
    </div>
    <div id="s_8606c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C48100" id="LblG2913_C48100">Nombre Calidad</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C48100" value="<?php if (isset($_GET['G2913_C48100'])) {
                            echo $_GET['G2913_C48100'];
                        } ?>"  name="G2913_C48100"  placeholder="Nombre Calidad"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C48101" id="LblG2913_C48101">Apellido Calidad</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C48101" value="<?php if (isset($_GET['G2913_C48101'])) {
                            echo $_GET['G2913_C48101'];
                        } ?>"  name="G2913_C48101"  placeholder="Apellido Calidad"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2913_C48187" id="LblG2913_C48187">Calificacion</label><select  class="form-control G2913_C48187 input-sm select2"  style="width: 100%;" name="G2913_C48187" id="G2913_C48187">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 2496 ORDER BY LISOPC_Nombre____b ASC";
    
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

  
                    <!-- CAMPO TIPO DECIMAL -->
                    <!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2913_C48182" id="LblG2913_C48182">CALIFICACION_Q_DY</label>
                        <input type="number" class="form-control input-sm Decimal "  value="<?php if (isset($_GET['G2913_C48182'])) {
                            echo $_GET['G2913_C48182'];
                        } ?>"  name="G2913_C48182" id="G2913_C48182" placeholder="CALIFICACION_Q_DY">
                    </div>
                    <!-- FIN DEL CAMPO TIPO DECIMAL -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G2913_C48183" id="LblG2913_C48183">COMENTARIO_CALIDAD_Q_DY</label>
                        <textarea class="form-control input-sm" name="G2913_C48183" id="G2913_C48183"  value="<?php if (isset($_GET['G2913_C48183'])) {
                            echo $_GET['G2913_C48183'];
                        } ?>" placeholder="COMENTARIO_CALIDAD_Q_DY"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G2913_C48184" id="LblG2913_C48184">COMENTARIO_AGENTE_Q_DY</label>
                        <textarea class="form-control input-sm" name="G2913_C48184" id="G2913_C48184" readonly value="<?php if (isset($_GET['G2913_C48184'])) {
                            echo $_GET['G2913_C48184'];
                        } ?>" placeholder="COMENTARIO_AGENTE_Q_DY"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="bootstrap-datepicker">
                        <div class="form-group">
                            <label for="G2913_C48185" id="LblG2913_C48185">FECHA_AUDITADO_Q_DY</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm Fecha" value="<?php if (isset($_GET['G2913_C48185'])) {
                            echo $_GET['G2913_C48185'];
                        } ?>" readonly name="G2913_C48185" id="G2913_C48185" placeholder="YYYY-MM-DD" nombre="FECHA_AUDITADO_Q_DY">
                                <div class="input-group-addon" id="DTP_G2913_C48185">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C48186" id="LblG2913_C48186">NOMBRE_AUDITOR_Q_DY</label><input type="text" maxlength="255" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C48186" value="<?php if (isset($_GET['G2913_C48186'])) {
                            echo $_GET['G2913_C48186'];
                        } ?>" readonly name="G2913_C48186"  placeholder="NOMBRE_AUDITOR_Q_DY"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
            <div class="col-md-12 col-xs-12">       
                <!--Audio Con Controles -->
                <audio id="Abtns_8606" controls="controls" style="width: 100%">
                    <source id="btns_8606" src="" type="audio/mp3"/>
                </audio>
            </div>
            <input type="hidden" name="IdProyecto" id="IdProyecto" value="151">
        </div>

        </div>
    </div> <!-- AQUIFINSECCION -->
</div>
<?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="8607" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_8607c">
                Formulario Normal
            </a>
        </h4>
        
    </div>
    <div id="s_8607c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C48102" id="LblG2913_C48102">Nombre Normal</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C48102" value="<?php if (isset($_GET['G2913_C48102'])) {
                            echo $_GET['G2913_C48102'];
                        } ?>"  name="G2913_C48102"  placeholder="Nombre Normal"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2913_C48103" id="LblG2913_C48103">Apellido Normal</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2913_C48103" value="<?php if (isset($_GET['G2913_C48103'])) {
                            echo $_GET['G2913_C48103'];
                        } ?>"  name="G2913_C48103"  placeholder="Apellido Normal"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="8634" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_8634c">
                Formulario Reportes
            </a>
        </h4>
        
    </div>
    <div id="s_8634c" class="panel-collapse collapse in">

        <!-- Reportes -->
        <div class="box-body">
            <div class="row">
                
                <div class="col-md-3 col-xs-3">
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="bootstrap-datepicker">
                        <div class="form-group">
                            <label for="G3675_C113582" id="LblG3675_C113582">Fecha Inicial</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm Fecha" value="" name="G3675_C113582" id="G3675_C113582" placeholder="YYYY-MM-DD" nombre="Fecha Inicial" inputmode="text">
                                <div class="input-group-addon" id="DTP_G3675_C113582">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->
                </div> <!-- AQUIFINCAMPO -->
                <div class="col-md-3 col-xs-3">
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="bootstrap-datepicker">
                        <div class="form-group">
                            <label for="G3675_C113583" id="LblG3675_C113583">Fecha Final</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm Fecha" value="" name="G3675_C113583" id="G3675_C113583" placeholder="YYYY-MM-DD" nombre="Fecha Final" inputmode="text">
                                <div class="input-group-addon" id="DTP_G3675_C113583">
                                    <i class="fa fa-calendar-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->
                </div> <!-- AQUIFINCAMPO -->


                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label style="color:white">...</label>
                        <button class="form-control input-sm btn btn-primary" id="G3675_C113584" ws="0" llave="113584" name="G3675_C113584">Generar Reporte  C_200 </button>
                    </div>
                </div> <!-- AQUIFINCAMPO -->
                <div class="col-md-3 col-xs-3">
                    <div class="form-group">
                        <label style="color:white">...</label>
                        <button class="form-control input-sm btn btn-primary" id="G3675_C113585" ws="0" llave="113585" name="G3675_C113585">Generar Reporte  C_600 </button>
                    </div>
                </div> <!-- AQUIFINCAMPO -->

            </div>
        </div>

        <!-- CAMPO TIPO FECHA 
        <div class="bootstrap-datepicker">
            <div class="form-group">
                <label for="G2913_C48201" id="LblG2913_C48201">Fecha Inicial</label>
                <div class="input-group">
                    <input type="text" class="form-control input-sm Fecha" value="<?php if (isset($_GET['G2913_C48201'])) {
                echo $_GET['G2913_C48201'];
            } ?>"  name="G2913_C48201" id="G2913_C48201" placeholder="YYYY-MM-DD" nombre="Fecha Inicial">
                    <div class="input-group-addon" id="DTP_G2913_C48201">
                        <i class="fa fa-calendar-o"></i>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="row" style="background-color: #FAFAFA; ">
    <br/>
    <?php if(isset($_GET['user'])){ ?>
    <div class="col-md-10 col-xs-9">
        <div class="form-group">
            <select class="form-control input-sm tipificacion" name="tipificacion" id="G2913_C47717">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = 3091;";
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
            <select class="form-control input-sm tipificacion" name="tipificacion" id="G2913_C47717">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = 3091;";
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
            <select class="form-control input-sm reintento" name="reintento" id="G2913_C47718">
                <option value="0">Reintento</option>
                <option value="1">REINTENTO AUTOMATICO</option>
                <option value="2">AGENDADO</option>
                <option value="3">NO REINTENTAR</option>
            </select>     
        </div>
    </div>
    <div class="col-md-4 col-xs-4">
        <div class="form-group">
            <input type="text" name="TxtFechaReintento" id="G2913_C47719" class="form-control input-sm TxtFechaReintento" placeholder="Fecha Reintento"  >
        </div>
    </div>
    <div class="col-md-4 col-xs-4" style="text-align: left;">
        <div class="form-group">
            <input type="text" name="TxtHoraReintento" id="G2913_C47720" class="form-control input-sm TxtHoraReintento" placeholder="Hora Reintento">
        </div>
    </div>
</div>
<div class="row" style="background-color: #FAFAFA;">
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <textarea class="form-control input-sm textAreaComentarios" name="textAreaComentarios" id="G2913_C47721" placeholder="Observaciones"></textarea>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- SECCION : PAGINAS INCLUIDAS -->

<?php include(__DIR__ ."/../pies.php"); ?>
<script type="text/javascript" src="formularios/G2913/G2913_eventos.js"></script>
<?php require_once "G2913_extender_funcionalidad.php";?>
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
                    /*$("#BtnFinalizarCalificacion").click(function(){
                        var Cargo= "'. $Cargo .'";
                        console.log("Cargo: ", Cargo);
                        $("#calidad").val("1");
                        $("#enviarCalificacion").modal("show");
                    });*/

                    $("#BtnFinalizarCalificacion").click(function(){
                        $("#calidad").val("1");
                        $("#enviarCalificacion").modal("show");
                    });

                    $("#sendEmails").click(function(){
                        $("#loading").attr("hidden", false);
                        var emailRegex= /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                        var cajaCorreos= $("#cajaCorreos").val();
                        var IdProyecto= $("#IdProyecto").val();
                        var IdGestion= $("#IdGestion").val();
                        var IdCampana= <?=$_GET["campanaId"];?>;
                        var IdGuion= <?=$_GET["formulario"];?>;
                        var BaseUrl= "<?=base_url?>";


                        //Captura Servidor
                        var Contador= 0;
                        for (let i = 0; i < BaseUrl.length; i++) {
                            const Letra = BaseUrl[i];
                            if(Letra == "/") {
                                Contador= Contador+1;
                                if(Contador == 3) {
                                    var Servidor = BaseUrl.slice(0, i);
                                    console.log("Servidor:", Servidor);
                                }
                            }
                        }
                        if((Servidor == "") || (Servidor == undefined)) {
                            Servidor= window.top.location.origin;
                            console.log("Servidor:", Servidor);
                        }

                        //Captura IdGestion
                        if((IdGestion == "") || (IdGestion == undefined)) {
                            var IdGestion= <?=$_GET["registroId"];?>;
                            console.log("IdGestion:", IdGestion);
                        }

                        //Captura Correos
                        if (cajaCorreos == null || cajaCorreos == "") {
                            swal({
                                icon: "error",
                                title: "🤨 Oops...",
                                text: "Debe Agregar Mínimo Un Correo Electrónico",
                                confirmButtonColor: "#2892DB"
                            })
                            cajaCorreos = "";
                            $("#loading").attr("hidden", true);
                        }else{
                            $("#Save").click();
                            $("#ModalLoading").modal();
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

                            if((IdProyecto == "") || (IdProyecto == null) || (IdProyecto == undefined)){
                                var Data = new FormData();
                                Data.append("IdGuion", IdGuion);
                                $.ajax({
                                    url: "<?=$url_crud;?>?ConsultarHuesped=si",  
                                    type: "POST",
                                    data: Data,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function(data){
                                        var IdProyecto = parseInt(data, 10)
                                        console.log("IdProyecto: ", IdProyecto);
                                    },
                                    error : function(){
                                        alertify.error("No Se Puede Consultar Huesped");
                                    },
                                    complete : function(){
                                        console.log("Huesped Ok");
                                        console.log("IdProyecto: ", IdProyecto);
                                    }
                                });
                            }
        
                            var formData = new FormData($("#FormularioDatos")[0]);
                            formData.append("Servidor", Servidor);
                            formData.append("IdProyecto", IdProyecto);
                            formData.append("IdGestion", IdGestion);
                            formData.append("IdCampana", IdCampana);
                            formData.append("IdGuion", IdGuion);
                            formData.append("IdCal", <?=$_SESSION["IDENTIFICACION"];?>);
                            formData.append("Correos", cajaCorreos);

                            $.ajax({
                                url: "<?=$url_crud;?>?EnviarCalificacion=si",  
                                type: "POST",
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function(data){
                                    alertify.success("¡Calificación Enviada!");
                                    
                                    setTimeout(function(){
                                        $("#ModalLoading").modal("hide");
                                        $("#editarDatos").modal("hide");
                                        window.location.reload();
                                        
                                        /*var miRedirect = document.createElement('a');
                                        miRedirect.setAttribute('href', 'http://localhost/manager/index.php?page=TiempoReal');
                                        miRedirect.setAttribute('target', '_blank');  */

                                    }, 2000);

                                    
                                },
                                error : function(){
                                    alertify.error("No Se Puede Enviar La Calificación");
                                    $("#ModalLoading").modal("hide");
                                },
                                complete : function(){
                                    $("#loading").attr("hidden",true);
                                    $("#CerrarCalificacion").click();
                                    $("#cerrarChatIntrusion").click();
                                }
        
                            });

                        }
                        
                    });
                    
                    $("#s_8468").attr("hidden", false);
                    $("#s_8606").attr("hidden", false);
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
                        $("#G2913_C<?=$_GET['pincheCampo'];?>").attr("opt","<?=$_GET["idFather"]?>");
                        $("#G2913_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET["idFather"]?>");
                        setTimeout(function(){
                            $("#G2913_C<?=$_GET['pincheCampo'];?>").change();       
                        },1000);                        
                    <?php }else{
                    $sqlMiembro=$mysqli->query("SELECT G{$_GET['formularioPadre']}_CodigoMiembro AS miembro FROM DYALOGOCRM_WEB.G{$_GET['formularioPadre']} WHERE G{$_GET['formularioPadre']}_ConsInte__b={$_GET['idFather']}");
                    if($sqlMiembro && $sqlMiembro-> num_rows ==1){
                        $sqlMiembro=$sqlMiembro->fetch_object();
                        $intMiembro=$sqlMiembro->miembro;
                    }
        ?>
                        $("#G2913_C<?=$_GET['pincheCampo'];?>").attr("opt","<?=$intMiembro?>");
                        $("#G2913_C<?=$_GET['pincheCampo'];?>").val("<?=$intMiembro?>");
                        setTimeout(function(){
                            $("#G2913_C<?=$_GET['pincheCampo'];?>").change();       
                        },1000);                        
                <?php } ?>
        <?php }else{ ?>
                $("#G2913_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET['yourfather'];?>");
        <?php } ?>        
        <?php }else{ ?>
            if(document.getElementById("G2913_C<?=$_GET['pincheCampo'];?>").type == "select-one"){
                $.ajax({
                    url      : '<?=$url_crud;?>?Combo_Guion_G<?php echo $_GET['formulario'];?>_C<?php echo $_GET['pincheCampo']; ?>=si',
                    type     : 'POST',
                    data     : { q : <?php echo $_GET["idFather"]; ?> },
                    success  : function(data){
                        $("#G<?php echo $_GET["formulario"]; ?>_C<?php echo $_GET["pincheCampo"]; ?>").html(data);
                    }
                });
            }else{
                $("#G2913_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET['idFather'];?>");
            }
        <?php } ?>
        
    <?php } ?>
    /////////////////////////////////////////////////////////////////////////
    <?php if (!isset($_GET["view"])) {?>
        $("#add").click(function(){
            
            //JDBD - Damos el valor asignado por defecto a este campo.
            $("#G2913_C47724").val("<?php echo date('H:i:s');?>");
            $("#G2913_C48187").val("0").trigger("change");
            
            
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
                     
                $("#G2913_C47728").val(item.G2913_C47728).trigger("change");
                $("#opt_"+item.G2913_C47728).prop("checked",true).trigger("change");  
                $("#G2913_C47708").val(item.G2913_C47708); 
                $("#G2913_C47709").val(item.G2913_C47709); 
                $("#G2913_C47710").val(item.G2913_C47710); 
                $("#G2913_C47711").val(item.G2913_C47711); 
                $("#G2913_C47712").val(item.G2913_C47712); 
                $("#G2913_C47713").val(item.G2913_C47713); 
                $("#G2913_C47714").val(item.G2913_C47714); 
                $("#G2913_C47715").val(item.G2913_C47715); 
                $("#G2913_C47716").val(item.G2913_C47716); 
                $("#G2913_C47717").val(item.G2913_C47717).trigger("change");
                $("#opt_"+item.G2913_C47717).prop("checked",true).trigger("change");  
                $("#G2913_C47718").val(item.G2913_C47718).trigger("change");
                $("#opt_"+item.G2913_C47718).prop("checked",true).trigger("change");  
                $("#G2913_C47719").val(item.G2913_C47719);
                $("#G2913_C47720").val(item.G2913_C47720).trigger("change");  
                $("#G2913_C47721").val(item.G2913_C47721); 
                $("#G2913_C47722").val(item.G2913_C47722); 
                $("#G2913_C47723").val(item.G2913_C47723); 
                $("#G2913_C47724").val(item.G2913_C47724); 
                $("#G2913_C47725").val(item.G2913_C47725);   
                if(item.G2913_C47726 == "1"){
                    $("#G2913_C47726").prop('checked', true);
                }else{
                    $("#G2913_C47726").prop('checked', false);
                }    
                if(item.G2913_C47727 == "1"){
                    $("#G2913_C47727").prop('checked', true);
                }else{
                    $("#G2913_C47727").prop('checked', false);
                }  
                $("#G2913_C48100").val(item.G2913_C48100); 
                $("#G2913_C48101").val(item.G2913_C48101); 
                $("#G2913_C48187").val(item.G2913_C48187).trigger("change");
                $("#opt_"+item.G2913_C48187).prop("checked",true).trigger("change");  
                $("#G2913_C48182").val(item.G2913_C48182); 
                $("#G2913_C48183").val(item.G2913_C48183); 
                $("#G2913_C48184").val(item.G2913_C48184); 
                $("#G2913_C48185").val(item.G2913_C48185); 
                $("#G2913_C48186").val(item.G2913_C48186); 
                $("#G2913_C48102").val(item.G2913_C48102); 
                $("#G2913_C48103").val(item.G2913_C48103); 
                $("#G2913_C48201").val(item.G2913_C48201); 
                $("#G2913_C48202").val(item.G2913_C48202);
                
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
        


    $("#G2913_C48187").select2();
        //datepickers
        

        $("#G2913_C47719").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        $("#DTP_G2913_C47719").click(function(){
            $("#G2913_C47719").focus();
        });

        $("#G2913_C48201").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        $("#DTP_G2913_C48201").click(function(){
            $("#G2913_C48201").focus();
        });

        $("#G2913_C48202").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
        $("#DTP_G2913_C48202").click(function(){
            $("#G2913_C48202").focus();
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
        $("#G2913_C47720").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }
            }
            $("#G2913_C47720").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#G2913_C47720").val($(this).val()).trigger("change")});
        });
        $("#G2913_C47720").change();
        $("#TMP_G2913_C47720").click(function(){
            $("#G2913_C47720").focus();
        });

        //Validaciones numeros Enteros
        


        //Validaciones numeros Decimales
        

        $("#G2913_C48182").numeric({ decimal : ".",  negative : false, scale: 4 });
                

        /* Si son d formulas */
        


        //Si tienen dependencias

        


    //function para Calificacion 

    $(".G2913_C48187").change(function(){  
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
                                            
 
                                                $("#G2913_C47728").val(item.G2913_C47728).trigger("change");
                                                $("#opt_"+item.G2913_C47728).prop("checked",true).trigger("change"); 
 
                                                $("#G2913_C47708").val(item.G2913_C47708);
 
                                                $("#G2913_C47709").val(item.G2913_C47709);
 
                                                $("#G2913_C47710").val(item.G2913_C47710);
 
                                                $("#G2913_C47711").val(item.G2913_C47711);
 
                                                $("#G2913_C47712").val(item.G2913_C47712);
 
                                                $("#G2913_C47713").val(item.G2913_C47713);
 
                                                $("#G2913_C47714").val(item.G2913_C47714);
 
                                                $("#G2913_C47715").val(item.G2913_C47715);
 
                                                $("#G2913_C47716").val(item.G2913_C47716);
 
                                                $("#G2913_C47717").val(item.G2913_C47717).trigger("change");
                                                $("#opt_"+item.G2913_C47717).prop("checked",true).trigger("change"); 
                            
                                                $("#G2913_C47718").val(item.G2913_C47718).trigger("change");
                                                $("#opt_"+item.G2913_C47718).prop("checked",true).trigger("change"); 
 
                                                $("#G2913_C47719").val(item.G2913_C47719);

                                                $("#G2913_C47720").val(item.G2913_C47720).trigger("change"); 
 
                                                $("#G2913_C47721").val(item.G2913_C47721);
 
                                                $("#G2913_C47722").val(item.G2913_C47722);
 
                                                $("#G2913_C47723").val(item.G2913_C47723);
 
                                                $("#G2913_C47724").val(item.G2913_C47724);
 
                                                $("#G2913_C47725").val(item.G2913_C47725);
      
                                                if(item.G2913_C47726 == "1"){
                                                   $("#G2913_C47726").prop('checked', true);
                                                }else{
                                                    $("#G2913_C47726").prop('checked', false);
                                                } 
      
                                                if(item.G2913_C47727 == "1"){
                                                   $("#G2913_C47727").prop('checked', true);
                                                }else{
                                                    $("#G2913_C47727").prop('checked', false);
                                                } 
 
                                                $("#G2913_C48100").val(item.G2913_C48100);
 
                                                $("#G2913_C48101").val(item.G2913_C48101);
 
                                                $("#G2913_C48187").val(item.G2913_C48187).trigger("change");
                                                $("#opt_"+item.G2913_C48187).prop("checked",true).trigger("change"); 
 
                                                $("#G2913_C48182").val(item.G2913_C48182);
 
                                                $("#G2913_C48183").val(item.G2913_C48183);
 
                                                $("#G2913_C48184").val(item.G2913_C48184);
 
                                                $("#G2913_C48185").val(item.G2913_C48185);
 
                                                $("#G2913_C48186").val(item.G2913_C48186);
 
                                                $("#G2913_C48102").val(item.G2913_C48102);
 
                                                $("#G2913_C48103").val(item.G2913_C48103);
 
                                                $("#G2913_C48201").val(item.G2913_C48201);
 
                                                $("#G2913_C48202").val(item.G2913_C48202);
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
                            //alertify.error('Un error ha ocurrido y no pudimos guardar la información');
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
            colNames:['id','ESTADO_CALIDAD_Q_DY','ORIGEN_DY_WF','OPTIN_DY_WF','ESTADO_DY','Numero De Línea:','Consultar','Tipo De Servicio:','Tipo De Servicio 2:','Tipo De Servicio 3:','Plantilla De Obsevaciones: ','Agente','Fecha','Hora','Campaña','Nombre Calidad','Apellido Calidad','Calificacion','CALIFICACION_Q_DY','COMENTARIO_CALIDAD_Q_DY','COMENTARIO_AGENTE_Q_DY','FECHA_AUDITADO_Q_DY','NOMBRE_AUDITOR_Q_DY','Nombre Normal','Apellido Normal','Fecha Inicial','Fecha Final'],
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
                        name:'G2913_C47728', 
                        index:'G2913_C47728', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=-3&campo=G2913_C47728'
                        }
                    }

                    ,
                    { 
                        name:'G2913_C47708', 
                        index: 'G2913_C47708', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47709', 
                        index: 'G2913_C47709', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47710', 
                        index: 'G2913_C47710', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47711', 
                        index: 'G2913_C47711', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47712', 
                        index: 'G2913_C47712', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47713', 
                        index: 'G2913_C47713', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47714', 
                        index: 'G2913_C47714', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47715', 
                        index: 'G2913_C47715', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47716', 
                        index: 'G2913_C47716', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47722', 
                        index: 'G2913_C47722', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47723', 
                        index: 'G2913_C47723', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47724', 
                        index: 'G2913_C47724', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C47725', 
                        index: 'G2913_C47725', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C48100', 
                        index: 'G2913_C48100', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C48101', 
                        index: 'G2913_C48101', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C48187', 
                        index:'G2913_C48187', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=2496&campo=G2913_C48187'
                        }
                    }

                    ,
                    {  
                        name:'G2913_C48182', 
                        index:'G2913_C48182', 
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
                        name:'G2913_C48183', 
                        index:'G2913_C48183', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C48184', 
                        index:'G2913_C48184', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    {  
                        name:'G2913_C48185', 
                        index:'G2913_C48185', 
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
                        name:'G2913_C48186', 
                        index: 'G2913_C48186', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C48102', 
                        index: 'G2913_C48102', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2913_C48103', 
                        index: 'G2913_C48103', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    {  
                        name:'G2913_C48201', 
                        index:'G2913_C48201', 
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
                        name:'G2913_C48202', 
                        index:'G2913_C48202', 
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
            sortname: 'G2913_C47728',
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
        console.log("?????????????");
        $(".CargarDatos").click(function(){
            //remover todas las clases activas de la lista de navegacion
            $(".CargarDatos").each(function(){
                $(this).removeClass('active');
            });
            
            //add la clase activa solo ala celda que le dimos click.
            $(this).addClass('active');
            
            var id = $(this).attr('id');
            console.log("id: ", id);
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
                        
 
                        $("#G2913_C47728").val(item.G2913_C47728).trigger("change");
                        $("#opt_"+item.G2913_C47728).prop("checked",true).trigger("change"); 

                        $("#G2913_C47708").val(item.G2913_C47708);

                        $("#G2913_C47709").val(item.G2913_C47709);

                        $("#G2913_C47710").val(item.G2913_C47710);

                        $("#G2913_C47711").val(item.G2913_C47711);

                        $("#G2913_C47712").val(item.G2913_C47712);

                        $("#G2913_C47713").val(item.G2913_C47713);

                        $("#G2913_C47714").val(item.G2913_C47714);

                        $("#G2913_C47715").val(item.G2913_C47715);

                        $("#G2913_C47716").val(item.G2913_C47716);
 
                        $("#G2913_C47717").val(item.G2913_C47717).trigger("change");
                        $("#opt_"+item.G2913_C47717).prop("checked",true).trigger("change"); 
    
                        $("#G2913_C47718").val(item.G2913_C47718).trigger("change");
                        $("#opt_"+item.G2913_C47718).prop("checked",true).trigger("change"); 

                        $("#G2913_C47719").val(item.G2913_C47719);
                        $("#G2913_C47720").val(item.G2913_C47720).trigger("change"); 

                        $("#G2913_C47721").val(item.G2913_C47721);

                        $("#G2913_C47722").val(item.G2913_C47722);

                        $("#G2913_C47723").val(item.G2913_C47723);

                        $("#G2913_C47724").val(item.G2913_C47724);

                        $("#G2913_C47725").val(item.G2913_C47725);
    
                        if(item.G2913_C47726 == "1"){
                           $("#G2913_C47726").prop('checked', true);
                        }else{
                            $("#G2913_C47726").prop('checked', false);
                        } 
    
                        if(item.G2913_C47727 == "1"){
                           $("#G2913_C47727").prop('checked', true);
                        }else{
                            $("#G2913_C47727").prop('checked', false);
                        } 

                        $("#G2913_C48100").val(item.G2913_C48100);

                        $("#G2913_C48101").val(item.G2913_C48101);
 
                        $("#G2913_C48187").val(item.G2913_C48187).trigger("change");
                        $("#opt_"+item.G2913_C48187).prop("checked",true).trigger("change"); 

                        $("#G2913_C48182").val(item.G2913_C48182);

                        $("#G2913_C48183").val(item.G2913_C48183);

                        $("#G2913_C48184").val(item.G2913_C48184);

                        $("#G2913_C48185").val(item.G2913_C48185);

                        $("#G2913_C48186").val(item.G2913_C48186);

                        $("#G2913_C48102").val(item.G2913_C48102);

                        $("#G2913_C48103").val(item.G2913_C48103);

                        $("#G2913_C48201").val(item.G2913_C48201);

                        $("#G2913_C48202").val(item.G2913_C48202);
                               
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
                        var audio = $("#Abtns_8468");
                        $("#btns_8468").attr("src",data+"&streaming=true").appendTo(audio);
                        audio.load();
                    }});
                
                    $.ajax({// JDBD - Obtener el link de la llamada y reproducir
                    url      : '<?=$url_crud;?>?llenarBtnLlamada=si',
                    type     : 'POST',
                    data     : {idReg : id},
                    success  : function(data){
                        var audio = $("#Abtns_8606");
                        $("#btns_8606").attr("src",data+"&streaming=true").appendTo(audio);
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
    
    /*function CalcularFormula(){
        
    }*/

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

<video preload="auto" style="width: 100%; height: 100%;">
    <source type="video/mp4" src="blob:https://tldv.io/846525f2-488e-441f-8a9f-04fc64232636">
</video>

<!-- Funciones Para Generar Reportes -->
<script>

    //Al Cargar Archivo
    $(document).ready(function() {
        $("#G2913_C113583").prop('disabled', true);
    });

    //Funcion Para Selecionar Fecha Inicial
    $('body').on('change', '#G2913_C113582', function() {
        document.getElementById("G2913_C113583").disabled = false;
    });

    //Funcion Para Generar Reporte C_200
    function GenerarReporte_200(FormFechas) {

        $.ajax({
            url: "<?=$url_crud;?>?ConsultarReporte200=si",  
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: FormFechas,
            success: function(php_response){
                var Respuesta= php_response.msg;
                var Resultado= php_response.Resultado;
                //console.log("php_response: ", php_response);
                //console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    console.log("Resultado: ", Resultado);
                    var Contador= 0;
                    var FilaReporte= "";
                    for (let i = 0; i < Resultado.length; i++) {
                        const Datos = Resultado[i];
                        Contador= Contador+1;
                        console.log("Contador: ", Contador);
                        console.log("Datos: ", Datos);
                        for (let d = 0; d < Datos.length; d++) {
                            if(d == 2){
                                var NumeroCuentaInicial= Datos[d];
                                console.log("NumeroCuentaInicial: ", NumeroCuentaInicial);
                                
                                //Contar Numero De Cuenta
                                var CantidadNumeroCuenta= NumeroCuentaInicial.length;
                                console.log("CantidadNumeroCuenta: ", CantidadNumeroCuenta);
                                if(CantidadNumeroCuenta == 25){
                                    var NumeroCuentaFinal= NumeroCuentaInicial;
                                }else{
                                    var CantidadFaltante= 25 - CantidadNumeroCuenta;
                                    console.log("CantidadFaltante: ", CantidadFaltante);
                                    for (let c= 0; c < CantidadFaltante; c++) {
                                        var NumeroCuentaInicial= "0"+NumeroCuentaInicial;
                                    }
                                    var NumeroCuentaFinal= NumeroCuentaInicial;
                                    console.log("NumeroCuentaFinal: ", NumeroCuentaFinal);
                                }

                            }else if(d == 3){
                                var FechaI= Datos[d];
                                console.log("FechaI: ", FechaI);
                                var FechaI2= FechaI.replace("-", "");
                                var FechaF= FechaI2.replace("-", "");
                                console.log("FechaF: ", FechaF);
                            }else if(d == 4){
                                var Host= Datos[d];
                                console.log("Host: ", Host);
                            }else if(d == 5){
                                var CodigoArea= Datos[d];
                                console.log("CodigoArea: ", CodigoArea);
                            }else if(d == 6){
                                var NumeroTelefono= Datos[d];
                                console.log("NumeroTelefono: ", NumeroTelefono);
                            }else if(d == 7){
                                var CodigoAccionI= Datos[d];
                                console.log("CodigoAccionI: ", CodigoAccionI);
                            }else if(d == 8){
                                var CodigoResultadoI= Datos[d];
                                console.log("CodigoResultadoI: ", CodigoResultadoI);
                            }else if(d == 9){
                                var ComentarioI= Datos[d];
                                if((ComentarioI == "")||(ComentarioI == null)) {
                                    var ComentarioI= "Sin Comentarios...";
                                }
                                console.log("ComentarioI: ", ComentarioI);
                                
                                //Contar Caracteres Comentario
                                var NumeroCaracteres= ComentarioI.length;
                                if(NumeroCaracteres < 55) {
                                    var CantidadFaltante= 55 - NumeroCaracteres;
                                    console.log("CantidadFaltante: ", CantidadFaltante);
                                    for (let c= 0; c < CantidadFaltante; c++) {
                                        var ComentarioI= ComentarioI+" ";
                                    }
                                    var ComentarioFinal= ComentarioI;
                                }else{
                                    //Cortar Comentario
                                    var ComentarioCorte= ComentarioI.substring(0, 55);
                                    var ComentarioFinal= ComentarioCorte;
                                }
                                console.log("ComentarioFinal: ", ComentarioFinal);
                            }


                            //Codigo resultado deben ser 2 letras, pero llega un entero! -- Se creo tabla DYALOGOCRM_WEB.G3622_CODIGOS_RESULTADO
                            var NumeroObligaciones = 1;
                            var ExtensionTelefonicaI= "88888888";
                            
                            
                            
                        };

                        var Formulario= "200";
                        var Grupo= "5";
                        var NumeroCuenta= NumeroCuentaFinal;
                        var EspacioBlanco= "             ";
                        var Fecha= FechaF;
                        var Secuencia= "000";
                        var CodigoAccion= CodigoAccionI;
                        var CodigoResultado= CodigoResultadoI;
                        var EspacioBlanco2= "  ";
                        var Host= Host;
                        var Comentario= ComentarioFinal;
                        var EspacioBlanco3= " ";
                        var CodigoArea= CodigoArea;
                        var NumeroTelefono=	NumeroTelefono;
                        var ExtensionTelefonica= ExtensionTelefonicaI;

                        console.log("Formulario: ", Formulario);
                        console.log("Grupo: ", Grupo);
                        console.log("NumeroCuenta: ", NumeroCuenta);
                        console.log("Fecha: ", Fecha);
                        console.log("CodigoAccion: ", CodigoAccion);
                        console.log("CodigoResultado: ", CodigoResultado);
                        console.log("Host: ", Host);
                        console.log("Comentario: ", Comentario);
                        console.log("CodigoArea: ", CodigoArea);
                        console.log("NumeroTelefono: ", NumeroTelefono);
                        console.log("ExtensionTelefonica: ", ExtensionTelefonica);
                            

                        var FilaReporteF= Formulario+Grupo+NumeroCuenta+EspacioBlanco+Fecha+Secuencia+CodigoAccion+CodigoResultado+EspacioBlanco2+Host+Comentario+EspacioBlanco3+CodigoArea+NumeroTelefono+ExtensionTelefonica+"\n";
                        FilaReporte= FilaReporte+FilaReporteF;
                        console.log("FilaReporte: ");
                        console.log(FilaReporte);

                    }

                    function DescargarReporte(NombreArchivo, Info){
                        var blob= new Blob([Info], {
                            type: 'text/csv'
                        });
                        if (window.navigator.msSaveOrOpenBlob) {
                            window.navigator.msSaveBlob(blob, NombreArchivo);
                        } else {
                            var Elemento= window.document.createElement('a');
                            Elemento.href= window.URL.createObjectURL(blob);
                            Elemento.download= NombreArchivo;
                            document.body.appendChild(Elemento);
                            Elemento.click();
                            document.body.removeChild(Elemento);
                        }
                    }
                    DescargarReporte("C_200_NOMBRECASAOAGENCIA_160501.txt", FilaReporte);
                    
                    setTimeout(function(){
                        $("#ModalLoading").modal("hide");
                    }, 2000);

                }else{
                    console.log("Respuesta: ", Respuesta);
                    swal({
                        icon: 'info',
                        title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                        text: '!No Se Encontrarón Resultados Con Estas Fechas!',
                        confirmButtonColor: '#2892DB'
                    })
                    $("#BodyTabla").empty();
                    $('#ModalLoading').modal('hide');
                }

            },
            error: function(php_response) {
                swal({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }

        });
        

        
    }

    //Funcion Para Capturar Fechas C_200
    $("#G2913_C113584").click(function(){ 
        
        let FormFechas = new FormData();
        var FechaInicial= $("#G2913_C113582").val();
        var FechaFinal= $("#G2913_C113583").val();

        function AlertaVacio(CampoVacio) {
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "'+CampoVacio+'"',
                confirmButtonColor: '#2892DB'
            })
        }

        if((FechaInicial == null) || (FechaInicial == "")){
            var CampoVacio= "Fecha Inicial";
            AlertaVacio(CampoVacio);  
        }else if((FechaFinal == null) || (FechaFinal == "")){
            var CampoVacio= "Fecha Final";
            AlertaVacio(CampoVacio);  
        }else{
            $("#TitleModalLoading").text("Reporte C_200");
            $("#LabelLoading").text(" GENERANDO ... ");
            $("#ModalLoading").modal();

            FormFechas.append('FechaInicial', FechaInicial);
            FormFechas.append('FechaFinal', FechaFinal);

            GenerarReporte_200(FormFechas);
            
        }

    });


    //Funcion Para Generar Reporte C_600
    function GenerarReporte_600(FormFechas) {

        var ArrayReporte= [];

        $.ajax({
            url: "<?=$url_crud;?>?ConsultarReporte600=si",  
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: FormFechas,
            success: function(php_response){
                var Respuesta= php_response.msg;
                var Resultado= php_response.Resultado;
                console.log("php_response: ", php_response);
                console.log("Respuesta: ", Respuesta);
                if(Respuesta == "Ok"){
                    console.log("Resultado: ", Resultado);
                    var Contador= 0;
                    var FilaReporte= "";
                    for (let i = 0; i < Resultado.length; i++) {
                        const Datos = Resultado[i];
                        Contador= Contador+1;
                        console.log("Contador: ", Contador);
                        console.log("Datos: ", Datos);


                        for (let r = 0; r < Datos.length; r++) {
                            if(r == 1){
                                var NumeroCuentaInicial= Datos[r];
                                console.log("NumeroCuentaInicial: ", NumeroCuentaInicial);

                                //Contar Numero De Cuenta
                                var CantidadNumeroCuenta= NumeroCuentaInicial.length;
                                console.log("CantidadNumeroCuenta: ", CantidadNumeroCuenta);
                                if(CantidadNumeroCuenta == 12){
                                    var NumeroCuentaFinal= NumeroCuentaInicial;
                                }else{
                                    var CantidadFaltante= 12 - CantidadNumeroCuenta;
                                    console.log("CantidadFaltante: ", CantidadFaltante);
                                    for (let l= 0; l < CantidadFaltante; l++) {
                                        var NumeroCuentaInicial= NumeroCuentaInicial+" ";
                                    }
                                    var NumeroCuentaFinal= NumeroCuentaInicial;
                                    console.log("NumeroCuentaFinal: ", NumeroCuentaFinal);
                                }

                            }else if(r == 2){
                                var FechaElaboracionI= Datos[r];
                                console.log("FechaElaboracionI: ", FechaElaboracionI);
                                var FechaSinHora= FechaElaboracionI.substring(0, 10);
                                var FechaSinHora2= FechaSinHora.replace("-", "");
                                var FechaElaboracionF= FechaSinHora2.replace("-", "");
                                console.log("FechaElaboracionF: ", FechaElaboracionF);
                            }else if(r == 3){
                                var FechaVencimientoI= Datos[r];
                                console.log("FechaVencimientoI: ", FechaVencimientoI);
                                var FechaSinHora= FechaVencimientoI.substring(0, 10);
                                var FechaSinHora2= FechaSinHora.replace("-", "");
                                var FechaVencimientoF= FechaSinHora2.replace("-", "");

                                console.log("FechaVencimientoF: ", FechaVencimientoF);
                            }else if(r == 4){
                                var MontoCentavos= Datos[r];
                                var MontoInicial= MontoCentavos.replace(",", "");
                                console.log("MontoInicial: ", MontoInicial);

                                var CantidadMonto= MontoInicial.length;
                                console.log("CantidadMonto: ", CantidadMonto);
                                if(CantidadMonto == 15){
                                    var MontoFinal= MontoInicial;
                                }else{
                                    var CantidadFaltante= 15 - CantidadMonto;
                                    console.log("CantidadFaltante: ", CantidadFaltante);
                                    for (let l= 0; l < CantidadFaltante; l++) {
                                        var MontoInicial= "0"+MontoInicial;
                                    }
                                    var MontoFinal= MontoInicial;
                                    console.log("MontoFinal: ", MontoFinal);
                                }
                            }
                        }
                        

                        var IdGestorI= "05AM00XX";
                        var CodigoAccion= 98;
                        var CodigoResultado= "CC";


                        //** */

                        
                        var Formulario= "600";
                        var Grupo= "5";
                        var NumeroCuenta= NumeroCuentaInicial;
                        var EspacioBlanco= "             ";
                        var IdGestor= IdGestorI;
                        var CodigoAccion= "LC";
                        var CodigoResultado= "PP";
                        var FechaElaboracion= FechaElaboracionF;
                        var NumeroPromesas= "001";
                        var ConsecutivoPromesa= "001";
                        var FechaVencimiento= FechaVencimientoF;
                        var Monto= MontoFinal;
                        
                        var FilaReporte= Formulario+Grupo+NumeroCuenta+EspacioBlanco+IdGestor+CodigoAccion+CodigoResultado+FechaElaboracion+NumeroPromesas+ConsecutivoPromesa+FechaVencimiento+Monto;
                        console.log("FilaReporte: ");
                        console.log(FilaReporte);

                        
                    }

                }else{
                    console.log("Respuesta: ", Respuesta);
                    swal({
                        icon: 'info',
                        title: ' 🤷🏽‍♂️  ¡Nada Por Aqui, Nada Por Alla! ', 
                        text: '!No Se Encontrarón Resultados Con Estas Fechas!',
                        confirmButtonColor: '#2892DB'
                    })
                    $("#BodyTabla").empty();
                    $('#ModalLoading').modal('hide');
                }
                
            },
            error: function(php_response) {
                swal({
                    icon: 'error',
                    title: '¡Error Servidor!  😵',
                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                    confirmButtonColor: '#2892DB'
                })
                php_response = JSON.stringify(php_response);
                console.log(php_response);
            }

        });


        
        function DescargarReporte(NombreArchivo, Info){
            var Archivo= new Blob([Info], {
                type: 'text/csv'
            });
            if (window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveBlob(Archivo, NombreArchivo);
            } else {
                var Elemento= window.document.createElement('a');
                Elemento.href= window.URL.createObjectURL(Archivo);
                Elemento.download= NombreArchivo;
                document.body.appendChild(Elemento);
                Elemento.click();
                document.body.removeChild(Elemento);
            }
        }
        //DescargarReporte("C_600_ Nombredecasa _fecha.txt", FilaReporte);

        setTimeout(function(){
            $("#ModalLoading").modal("hide");
        }, 2000);

    }
    
    //Funcion Para Capturar Fechas C_600
    $("#G2913_C113585").click(function(){ 
        
        let FormFechas = new FormData();
        var FechaInicial= $("#G2913_C113582").val();
        var FechaFinal= $("#G2913_C113583").val();

        function AlertaVacio(CampoVacio) {
            swal({
                icon: 'error',
                title: '🤨 Oops...',
                text: 'Debe Agregar Un Valor En El Campo "'+CampoVacio+'"',
                confirmButtonColor: '#2892DB'
            })
        }

        if((FechaInicial == null) || (FechaInicial == "")){
            var CampoVacio= "Fecha Inicial";
            AlertaVacio(CampoVacio);  
        }else if((FechaFinal == null) || (FechaFinal == "")){
            var CampoVacio= "Fecha Final";
            AlertaVacio(CampoVacio);  
        }else{
            $("#TitleModalLoading").text("Reporte C_600");
            $("#LabelLoading").text(" GENERANDO ... ");
            $("#ModalLoading").modal();

            FormFechas.append('FechaInicial', FechaInicial);
            FormFechas.append('FechaFinal', FechaFinal);

            GenerarReporte_600(FormFechas);
            
        }

    });
    

</script>
