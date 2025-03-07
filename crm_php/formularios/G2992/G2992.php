
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
   $url_crud = "formularios/G2992/G2992_CRUD.php";
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
            $Zsql = "SELECT G2992_ConsInte__b as id, G2992_C59910 as camp2 , G2992_C59915 as camp1 FROM ".$BaseDatos.".G2992  WHERE G2992_Usuario = ".$idUsuario." ORDER BY G2992_ConsInte__b DESC LIMIT 0, 50";
        }else{
            $Zsql = "SELECT G2992_ConsInte__b as id, G2992_C59910 as camp2 , G2992_C59915 as camp1 FROM ".$BaseDatos.".G2992  ORDER BY G2992_ConsInte__b DESC LIMIT 0, 50";
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
                $Zsql = "SELECT G2992_ConsInte__b as id, G2992_C59910 as camp2 , G2992_C59915 as camp1 FROM ".$BaseDatos.".G2992 JOIN ".$BaseDatos.".G2992_M".$resultEstpas->muestr." ON G2992_ConsInte__b = G2992_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( (G2992_M".$resultEstpas->muestr."_Estado____b = 0 OR G2992_M".$resultEstpas->muestr."_Estado____b = 1 OR G2992_M".$resultEstpas->muestr."_Estado____b = 3) OR (G2992_M".$resultEstpas->muestr."_Estado____b = 2 AND G2992_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) ) 
                ORDER BY G2992_ConsInte__b DESC LIMIT 0, 50";
            }else{
                $Zsql = "SELECT G2992_ConsInte__b as id, G2992_C59910 as camp2 , G2992_C59915 as camp1 FROM ".$BaseDatos.".G2992 JOIN ".$BaseDatos.".G2992_M".$resultEstpas->muestr." ON G2992_ConsInte__b = G2992_M".$resultEstpas->muestr."_CoInMiPo__b 
                WHERE ( (G2992_M".$resultEstpas->muestr."_Estado____b = 0 OR G2992_M".$resultEstpas->muestr."_Estado____b = 1 OR G2992_M".$resultEstpas->muestr."_Estado____b = 3) OR (G2992_M".$resultEstpas->muestr."_Estado____b = 2 AND G2992_M".$resultEstpas->muestr."_FecHorAge_b <= NOW() ) )
                AND G2992_M".$resultEstpas->muestr."_ConIntUsu_b = ".$idUsuario." 
                ORDER BY G2992_ConsInte__b DESC LIMIT 0, 50";
            }
            
        }

    }else{
        $userid= isset($userid) ? $userid : "-10";
        $idUsuario = isset($_GET["usuario"]) ? $_GET["usuario"] : $userid;
        $Zsql = "SELECT G2992_ConsInte__b as id, G2992_C59910 as camp2 , G2992_C59915 as camp1 FROM ".$BaseDatos.".G2992  ORDER BY G2992_ConsInte__b DESC LIMIT 0, 50";
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
<div class="panel box box-primary" id="9162" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_9162c">
                DATOS DEL SOLICITANTE
            </a>
        </h4>
        
    </div>
    <div id="s_9162c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59910" id="LblG2992_C59910">NOMBRE Y APELLIDOS  SOLICITANTE</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59910" value="<?php if (isset($_GET['G2992_C59910'])) {
                            echo $_GET['G2992_C59910'];
                        } ?>"  name="G2992_C59910"  placeholder="NOMBRE Y APELLIDOS  SOLICITANTE"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59911" id="LblG2992_C59911">CEDULA  SOLICITANTE</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59911" value="<?php if (isset($_GET['G2992_C59911'])) {
                            echo $_GET['G2992_C59911'];
                        } ?>"  name="G2992_C59911"  placeholder="CEDULA  SOLICITANTE"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59913" id="LblG2992_C59913">TELEFONO 1  SOLICITANTE</label><div class="input-group">
                            <input type="text" maxlength="253" onKeyDown="longitud(this.id,'nel')" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id)" class="form-control input-sm" id="G2992_C59913" value="<?php if (isset($_GET['G2992_C59913'])) {
                            echo $_GET['G2992_C59913'];
                        } ?>"  name="G2992_C59913"  placeholder="TELEFONO 1  SOLICITANTE">
                            <div class="input-group-addon telefono" style="cursor:pointer" id="TLF_G2992_C59913" title="Click para llamar">
                        <i class="fa fa-phone"></i>
                    </div>
                            
                            
                        </div></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59914" id="LblG2992_C59914">TELEFONO 2  SOLICITANTE</label><div class="input-group">
                            <input type="text" maxlength="253" onKeyDown="longitud(this.id,'nel')" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id)" class="form-control input-sm" id="G2992_C59914" value="<?php if (isset($_GET['G2992_C59914'])) {
                            echo $_GET['G2992_C59914'];
                        } ?>"  name="G2992_C59914"  placeholder="TELEFONO 2  SOLICITANTE">
                            <div class="input-group-addon telefono" style="cursor:pointer" id="TLF_G2992_C59914" title="Click para llamar">
                        <i class="fa fa-phone"></i>
                    </div>
                            
                            
                        </div></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59912" id="LblG2992_C59912">CORREO  SOLICITANTE</label><input type="email" maxlength="100" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59912" value="<?php if (isset($_GET['G2992_C59912'])) {
                            echo $_GET['G2992_C59912'];
                        } ?>"  name="G2992_C59912"  placeholder="CORREO  SOLICITANTE"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59915" id="LblG2992_C59915">CODIGO Y/O REFERENCIA  SOLICITANTE</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59915" value="<?php if (isset($_GET['G2992_C59915'])) {
                            echo $_GET['G2992_C59915'];
                        } ?>"  name="G2992_C59915"  placeholder="CODIGO Y/O REFERENCIA  SOLICITANTE"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C65749" id="LblG2992_C65749">ASESOR</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C65749" value="<?php if(!isset($_GET["token"])){echo $_SESSION["NOMBRES"];}else{echo getNombreUser($_GET["token"]);}?>" readonly name="G2992_C65749"  placeholder="ASESOR"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

  
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C66500" id="LblG2992_C66500">FECHA CREACION GESTION</label>
                        <input type="text" class="form-control input-sm Fecha" value="<?=date("Y-m-d");?>" readonly name="G2992_C66500" id="G2992_C66500" placeholder="YYYY-MM-DD" nombre="FECHA CREACION GESTION">
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="9164" style='display:none;'>
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_9164c">
                CONTROL
            </a>
        </h4>
        
    </div>
    <div id="s_9164c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59904" id="LblG2992_C59904">Agente</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59904" value="<?php isset($userid) ? NombreAgente($userid) : getNombreUser($token);?>" readonly name="G2992_C59904"  placeholder="Agente"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59905" id="LblG2992_C59905">Fecha</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59905" value="<?php echo date('Y-m-d H:i:s');?>" readonly name="G2992_C59905"  placeholder="Fecha"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59906" id="LblG2992_C59906">Hora</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59906" value="<?php echo date('H:i:s');?>" readonly name="G2992_C59906"  placeholder="Hora"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59907" id="LblG2992_C59907">Campaña</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59907" value="<?php if(isset($_GET["campana_crm"])){ $cmapa = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
                $resCampa = $mysqli->query($cmapa);
                $dataCampa = $resCampa->fetch_array(); echo $dataCampa["CAMPAN_Nombre____b"]; } else { echo "NO TIENE CAMPAÑA";}?>" readonly name="G2992_C59907"  placeholder="Campaña"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="9165" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_9165c">
                DATOS DEL SUSCRIPTOR
            </a>
        </h4>
        
    </div>
    <div id="s_9165c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59916" id="LblG2992_C59916">NOMBRE Y APELLIDOS SUSCRIPTOR</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59916" value="<?php if (isset($_GET['G2992_C59916'])) {
                            echo $_GET['G2992_C59916'];
                        } ?>" readonly name="G2992_C59916"  placeholder="NOMBRE Y APELLIDOS SUSCRIPTOR"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59917" id="LblG2992_C59917">CEDULA SUSCRIPTOR</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59917" value="<?php if (isset($_GET['G2992_C59917'])) {
                            echo $_GET['G2992_C59917'];
                        } ?>" readonly name="G2992_C59917"  placeholder="CEDULA SUSCRIPTOR"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59918" id="LblG2992_C59918">CORREO  SUSCRIPTOR</label><input type="email" maxlength="100" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59918" value="<?php if (isset($_GET['G2992_C59918'])) {
                            echo $_GET['G2992_C59918'];
                        } ?>" readonly name="G2992_C59918"  placeholder="CORREO  SUSCRIPTOR"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59919" id="LblG2992_C59919">TELEFONO 1 SUSCRIPTOR</label><div class="input-group">
                            <input type="text" maxlength="253" onKeyDown="longitud(this.id,'nel')" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id)" class="form-control input-sm" id="G2992_C59919" value="<?php if (isset($_GET['G2992_C59919'])) {
                            echo $_GET['G2992_C59919'];
                        } ?>" readonly name="G2992_C59919"  placeholder="TELEFONO 1 SUSCRIPTOR">
                            <div class="input-group-addon telefono" style="cursor:pointer" id="TLF_G2992_C59919" title="Click para llamar">
                        <i class="fa fa-phone"></i>
                    </div>
                            
                            
                        </div></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59920" id="LblG2992_C59920">TELEFONO 2 SUSCRIPTOR</label><div class="input-group">
                            <input type="text" maxlength="253" onKeyDown="longitud(this.id,'nel')" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id)" class="form-control input-sm" id="G2992_C59920" value="<?php if (isset($_GET['G2992_C59920'])) {
                            echo $_GET['G2992_C59920'];
                        } ?>" readonly name="G2992_C59920"  placeholder="TELEFONO 2 SUSCRIPTOR">
                            <div class="input-group-addon telefono" style="cursor:pointer" id="TLF_G2992_C59920" title="Click para llamar">
                        <i class="fa fa-phone"></i>
                    </div>
                            
                            
                        </div></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59921" id="LblG2992_C59921">CÓDIGO SUSCRIPTOR</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59921" value="<?php if (isset($_GET['G2992_C59921'])) {
                            echo $_GET['G2992_C59921'];
                        } ?>" readonly name="G2992_C59921"  placeholder="CÓDIGO SUSCRIPTOR"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59922" id="LblG2992_C59922">RELACION CON EL PREDIO</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59922" value="<?php if (isset($_GET['G2992_C59922'])) {
                            echo $_GET['G2992_C59922'];
                        } ?>" readonly name="G2992_C59922"  placeholder="RELACION CON EL PREDIO"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59923" id="LblG2992_C59923">ESTADO SUSCRIPCION</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59923" value="<?php if (isset($_GET['G2992_C59923'])) {
                            echo $_GET['G2992_C59923'];
                        } ?>" readonly name="G2992_C59923"  placeholder="ESTADO SUSCRIPCION"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C59924" id="LblG2992_C59924">MORISIDAD</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C59924" value="<?php if (isset($_GET['G2992_C59924'])) {
                            echo $_GET['G2992_C59924'];
                        } ?>" readonly name="G2992_C59924"  placeholder="MORISIDAD"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C65254" id="LblG2992_C65254">REFERENCIA SUSCRIPTOR</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C65254" value="<?php if (isset($_GET['G2992_C65254'])) {
                            echo $_GET['G2992_C65254'];
                        } ?>" readonly name="G2992_C65254"  placeholder="REFERENCIA SUSCRIPTOR"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="9170" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_9170c">
                CLASIFICACION DE LA LLAMADA
            </a>
        </h4>
        
    </div>
    <div id="s_9170c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59951" id="LblG2992_C59951">La solicitud proviene de incumplimiento o errores por parte de la empresa</label><select  class="form-control G2992_C59951 input-sm select2"  style="width: 100%;" name="G2992_C59951" id="G2992_C59951">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3662 ORDER BY LISOPC_Nombre____b ASC";
    
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
                        <label for="G2992_C59952" id="LblG2992_C59952">SECCION</label><select  class="form-control G2992_C59952 input-sm select2"  style="width: 100%;" name="G2992_C59952" id="G2992_C59952">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3663 ORDER BY LISOPC_Nombre____b ASC";
    
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
                        <label for="G2992_C59953" id="LblG2992_C59953">TIPO DE DOLOR O SOLICITUD</label><select  class="form-control G2992_C59953 input-sm select2"  style="width: 100%;" name="G2992_C59953" id="G2992_C59953">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3652 ORDER BY LISOPC_Nombre____b ASC";
    
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

  
                    <!-- CAMPO DE TIPO CHECKBOX O SI/NO -->
                    <div class="form-group">
                        <label for="G2992_C61722" id="LblG2992_C61722">AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="G2992_C61722" id="G2992_C61722" data-error="Before you wreck yourself"  > 
                            </label>
                        </div>
                    </div>
                    <!-- FIN DEL CAMPO SI/NO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<div id="9163" >
<h3 class="box box-title"></h3>

</div>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="9434" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_9434c">
                CIERRE DE LA LLAMADA
            </a>
        </h4>
        
    </div>
    <div id="s_9434c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-4 col-xs-4">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C66507" id="LblG2992_C66507">Agenda</label><select  class="form-control G2992_C66507 input-sm select2"  style="width: 100%;" name="G2992_C66507" id="G2992_C66507">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 4161 ORDER BY LISOPC_Nombre____b ASC";
    
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


            <div class="col-md-4 col-xs-4">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C61320" id="LblG2992_C61320">Seleccion resultado llamada</label><select  class="form-control G2992_C61320 input-sm select2"  style="width: 100%;" name="G2992_C61320" id="G2992_C61320">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3679 ORDER BY LISOPC_Nombre____b ASC";
    
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


            <div class="col-md-4 col-xs-4">

  
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C66401" id="LblG2992_C66401">Fecha llamada</label>
                        <input type="text" class="form-control input-sm Fecha" value="<?php if (isset($_GET['G2992_C66401'])) {
                            echo $_GET['G2992_C66401'];
                        } ?>"  name="G2992_C66401" id="G2992_C66401" placeholder="YYYY-MM-DD" nombre="Fecha llamada">
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-4 col-xs-4">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C61319" id="LblG2992_C61319">Numero radicado</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C61319" value="<?php if (isset($_GET['G2992_C61319'])) {
                            echo $_GET['G2992_C61319'];
                        } ?>"  name="G2992_C61319"  placeholder="Numero radicado"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G2992_C66484" id="LblG2992_C66484">Comentarios</label>
                        <textarea class="form-control input-sm" name="G2992_C66484" id="G2992_C66484"  value="<?php if (isset($_GET['G2992_C66484'])) {
                            echo $_GET['G2992_C66484'];
                        } ?>" placeholder="Comentarios"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-4 col-xs-4">

  
                    <!-- CAMPO TIMEPICKER -->
                    <!-- Estos campos siempre deben llevar Hora en la clase asi class="form-control input-sm Hora" , de l contrario seria solo un campo de texto mas -->
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <label for="G2992_C66402" id="LblG2992_C66402">Hora llamada</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm Hora" value="<?php if (isset($_GET['G2992_C66402'])) {
                            echo $_GET['G2992_C66402'];
                        } ?>"  name="G2992_C66402" id="G2992_C66402" placeholder="HH:MM:SS" >
                                <div class="input-group-addon" id="TMP_G2992_C66402">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                    <!-- FIN DEL CAMPO TIMEPICKER -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(isset($_GET["quality"]) && $_GET["quality"]=="1") : ?>
<div class="panel box box-primary" id="s_9171" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_9171c">
                FORMULARIO CALIFICACION AGENTES
            </a>
        </h4>
        <a style="float: right;" class="btn btn-success pull-right FinalizarCalificacion" role="button" >Finalizar Calificacion&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-paper-plane-o"></i></a>
    </div>
    <div id="s_9171c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59955" id="LblG2992_C59955">1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto)</label><select  class="form-control G2992_C59955 input-sm select2"  style="width: 100%;" name="G2992_C59955" id="G2992_C59955">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59988" id="LblG2992_C59988">2.  Conversación: Tono amable</label><select  class="form-control G2992_C59988 input-sm select2"  style="width: 100%;" name="G2992_C59988" id="G2992_C59988">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59990" id="LblG2992_C59990">3. Lenguaje Adecuado</label><select  class="form-control G2992_C59990 input-sm select2"  style="width: 100%;" name="G2992_C59990" id="G2992_C59990">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59992" id="LblG2992_C59992">4.   Volumen de voz adecuado</label><select  class="form-control G2992_C59992 input-sm select2"  style="width: 100%;" name="G2992_C59992" id="G2992_C59992">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59994" id="LblG2992_C59994">5. Escucha Activa</label><select  class="form-control G2992_C59994 input-sm select2"  style="width: 100%;" name="G2992_C59994" id="G2992_C59994">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59996" id="LblG2992_C59996">6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa</label><select  class="form-control G2992_C59996 input-sm select2"  style="width: 100%;" name="G2992_C59996" id="G2992_C59996">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C59998" id="LblG2992_C59998">7. Si hay interrupción al cliente utiliza protocolo establecido</label><select  class="form-control G2992_C59998 input-sm select2"  style="width: 100%;" name="G2992_C59998" id="G2992_C59998">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C60000" id="LblG2992_C60000">8. Realiza acompañamiento al cliente al dejar la llamada en espera</label><select  class="form-control G2992_C60000 input-sm select2"  style="width: 100%;" name="G2992_C60000" id="G2992_C60000">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C60002" id="LblG2992_C60002">9.   Radicación pertinente de la SPQR´s</label><select  class="form-control G2992_C60002 input-sm select2"  style="width: 100%;" name="G2992_C60002" id="G2992_C60002">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C60004" id="LblG2992_C60004">10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx </label><select  class="form-control G2992_C60004 input-sm select2"  style="width: 100%;" name="G2992_C60004" id="G2992_C60004">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C60006" id="LblG2992_C60006">11.   Invita al cliente a diligenciar la encuesta de satisfaccion</label><select  class="form-control G2992_C60006 input-sm select2"  style="width: 100%;" name="G2992_C60006" id="G2992_C60006">
                                <option value="0">Seleccione</option>
                                <?php
                                    /*
                                        SE RECORRE LA CONSULTA QUE SE CARGO CON ANTERIORIDAD EN LA SECCION DE CARGUE LISTAS DESPLEGABLES
                                    */
                                    $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = 3675 ORDER BY LISOPC_Nombre____b ASC";
    
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
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C66544" id="LblG2992_C66544">ID GESTION</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C66544" value="<?php if (isset($_GET['G2992_C66544'])) {
                            echo $_GET['G2992_C66544'];
                        } ?>" readonly name="G2992_C66544"  placeholder="ID GESTION"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C66545" id="LblG2992_C66545">LINK DE GRABACION</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C66545" value="<?php if (isset($_GET['G2992_C66545'])) {
                            echo $_GET['G2992_C66545'];
                        } ?>" readonly name="G2992_C66545"  placeholder="LINK DE GRABACION"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">


                    <!-- CAMPO DE TIPO LISTA -->
                    <div class="form-group">
                        <label for="G2992_C61150" id="LblG2992_C61150">ESTADO_CALIDAD_Q_DY</label><select disabled class="form-control G2992_C61150 input-sm select2"  style="width: 100%;" name="G2992_C61150" id="G2992_C61150">
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
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- CAMPO TIPO DECIMAL -->
                    <!-- Estos campos siempre deben llevar Decimal en la clase asi class="form-control input-sm Decimal" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C61149" id="LblG2992_C61149">CALIFICACION_Q_DY</label>
                        <input type="text" class="form-control input-sm Decimal "  value="<?php if (isset($_GET['G2992_C61149'])) {
                            echo $_GET['G2992_C61149'];
                        } ?>"  name="G2992_C61149" id="G2992_C61149" placeholder="CALIFICACION_Q_DY">
                    </div>
                    <!-- FIN DEL CAMPO TIPO DECIMAL -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G2992_C61151" id="LblG2992_C61151">COMENTARIO_CALIDAD_Q_DY</label>
                        <textarea class="form-control input-sm" name="G2992_C61151" id="G2992_C61151"  value="<?php if (isset($_GET['G2992_C61151'])) {
                            echo $_GET['G2992_C61151'];
                        } ?>" placeholder="COMENTARIO_CALIDAD_Q_DY"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- CAMPO TIPO MEMO -->
                    <div class="form-group">
                        <label for="G2992_C61152" id="LblG2992_C61152">COMENTARIO_AGENTE_Q_DY</label>
                        <textarea class="form-control input-sm" name="G2992_C61152" id="G2992_C61152" readonly value="<?php if (isset($_GET['G2992_C61152'])) {
                            echo $_GET['G2992_C61152'];
                        } ?>" placeholder="COMENTARIO_AGENTE_Q_DY"></textarea>
                    </div>
                    <!-- FIN DEL CAMPO TIPO MEMO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

  
                    <!-- CAMPO TIPO FECHA -->
                    <!-- Estos campos siempre deben llevar Fecha en la clase asi class="form-control input-sm Fecha" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C61153" id="LblG2992_C61153">FECHA_AUDITADO_Q_DY</label>
                        <input type="text" class="form-control input-sm Fecha" value="<?php if (isset($_GET['G2992_C61153'])) {
                            echo $_GET['G2992_C61153'];
                        } ?>" readonly name="G2992_C61153" id="G2992_C61153" placeholder="YYYY-MM-DD" nombre="FECHA_AUDITADO_Q_DY">
                    </div>
                    <!-- FIN DEL CAMPO TIPO FECHA-->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-12 col-xs-12">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C61154" id="LblG2992_C61154">NOMBRE_AUDITOR_Q_DY</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C61154" value="<?php if (isset($_GET['G2992_C61154'])) {
                            echo $_GET['G2992_C61154'];
                        } ?>" readonly name="G2992_C61154"  placeholder="NOMBRE_AUDITOR_Q_DY"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


                <div class="row">
                    <div class="col-md-12 col-xs-12">       
                        <!--Audio Con Controles -->
                        <audio id="Abtns_9171" controls="controls" style="width: 100%">
                            <source id="btns_9171" src="" type="audio/mp3"/>
                        </audio>
                    </div>
                </div>

                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="10240" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_10240c">
                CALIFICACIONES CALIDAD
            </a>
        </h4>
        
    </div>
    <div id="s_10240c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65875" id="LblG2992_C65875">1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto)</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65875'])) {
                            echo $_GET['G2992_C65875'];
                        } ?>"  name="G2992_C65875" id="G2992_C65875" placeholder="1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto)"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65876" id="LblG2992_C65876">2.  Conversación: Tono amable</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65876'])) {
                            echo $_GET['G2992_C65876'];
                        } ?>"  name="G2992_C65876" id="G2992_C65876" placeholder="2.  Conversación: Tono amable"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65877" id="LblG2992_C65877">3. Lenguaje Adecuado</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65877'])) {
                            echo $_GET['G2992_C65877'];
                        } ?>"  name="G2992_C65877" id="G2992_C65877" placeholder="3. Lenguaje Adecuado"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65878" id="LblG2992_C65878">4.   Volumen de voz adecuado</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65878'])) {
                            echo $_GET['G2992_C65878'];
                        } ?>"  name="G2992_C65878" id="G2992_C65878" placeholder="4.   Volumen de voz adecuado"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65879" id="LblG2992_C65879">5. Escucha Activa</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65879'])) {
                            echo $_GET['G2992_C65879'];
                        } ?>"  name="G2992_C65879" id="G2992_C65879" placeholder="5. Escucha Activa"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65880" id="LblG2992_C65880">6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65880'])) {
                            echo $_GET['G2992_C65880'];
                        } ?>"  name="G2992_C65880" id="G2992_C65880" placeholder="6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65881" id="LblG2992_C65881">7. Si hay interrupción al cliente utiliza protocolo establecido</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65881'])) {
                            echo $_GET['G2992_C65881'];
                        } ?>"  name="G2992_C65881" id="G2992_C65881" placeholder="7. Si hay interrupción al cliente utiliza protocolo establecido"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65882" id="LblG2992_C65882">8. Realiza acompañamiento al cliente al dejar la llamada en espera</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65882'])) {
                            echo $_GET['G2992_C65882'];
                        } ?>"  name="G2992_C65882" id="G2992_C65882" placeholder="8. Realiza acompañamiento al cliente al dejar la llamada en espera"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65883" id="LblG2992_C65883">9.   Radicación pertinente de la SPQR´s</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65883'])) {
                            echo $_GET['G2992_C65883'];
                        } ?>"  name="G2992_C65883" id="G2992_C65883" placeholder="9.   Radicación pertinente de la SPQR´s"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->


            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65884" id="LblG2992_C65884">10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx </label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65884'])) {
                            echo $_GET['G2992_C65884'];
                        } ?>"  name="G2992_C65884" id="G2992_C65884" placeholder="10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx "></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->

  
        </div> 


        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO ENTERO -->
                    <!-- Estos campos siempre deben llevar Numerico en la clase asi class="form-control input-sm Numerico" , de l contrario seria solo un campo de texto mas -->
                    <div class="form-group">
                        <label for="G2992_C65885" id="LblG2992_C65885">11.   Invita al cliente a diligenciar la encuesta de satisfaccion</label><input type="text" class="form-control input-sm Numerico "  value="<?php if (isset($_GET['G2992_C65885'])) {
                            echo $_GET['G2992_C65885'];
                        } ?>"  name="G2992_C65885" id="G2992_C65885" placeholder="11.   Invita al cliente a diligenciar la encuesta de satisfaccion"></div>
                    <!-- FIN DEL CAMPO TIPO ENTERO -->
  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="11199" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_11199c">
                BOT
            </a>
        </h4>
        
    </div>
    <div id="s_11199c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

 
                    <!-- CAMPO TIPO TEXTO -->
                    <div class="form-group">
                        <label for="G2992_C71470" id="LblG2992_C71470">Solicitud</label><input type="text" maxlength="253" onKeyDown="longitud(this.id)" onKeyUp="longitud(this.id)" onfocus="longitud(this.id)" onblur="longitud(this.id,true)" class="form-control input-sm" id="G2992_C71470" value="<?php if (isset($_GET['G2992_C71470'])) {
                            echo $_GET['G2992_C71470'];
                        } ?>"  name="G2992_C71470"  placeholder="Solicitud"></div>
                    <!-- FIN DEL CAMPO TIPO TEXTO -->
  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->


                </div>
            </div> <!-- AQUIFINSECCION -->
            </div>
            <?php endif; ?>

<?php if(!isset($_GET["intrusionTR"])) : ?>
<div class="panel box box-primary" id="11317" >
    <div class="box-header with-border">
        <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#s_11317c">
                INTERCAMBIO DE DOCUMENTACION LLANOGAS
            </a>
        </h4>
        
    </div>
    <div id="s_11317c" class="panel-collapse collapse in">
        <div class="box-body">

        <div class="row">
        

            <div class="col-md-6 col-xs-6">

  
            </div> <!-- AQUIFINCAMPO -->


        </div> <!-- AQUIFINSALDO1 -->

<div class=row>
                        <div class="col-md-12 col-xs-12">
                            
<!-- SI ES MAESTRO - DETALLE CREO LAS TABS --> 

<div class="nav-tabs-custom">

    <ul class="nav nav-tabs">

        <li class="active">
            <a href="#tab_0" data-toggle="tab" id="tabs_click_0">INTERCAMBIO DE DOCUMENTACION LLANOGAS</a>
        </li>

    </ul>


    <div class="tab-content">

        <div class="tab-pane active" id="tab_0"> 
            <table class="table table-hover table-bordered" id="tablaDatosDetalless0" width="100%">
            </table>
            <div id="pagerDetalles0">
            </div> 
            <button title="Crear INTERCAMBIO DE DOCUMENTACION LLANOGAS" class="btn btn-primary btn-sm llamadores" padre="'<?php if(isset($_GET['yourfather'])){ echo $_GET['yourfather']; }else{ echo "0"; }?>' " id="btnLlamar_0"><i class="fa fa-plus"></i></button>
        </div>

    </div>

</div>
                        </div>
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
            <select class="form-control input-sm tipificacion" name="tipificacion" id="G2992_C59899">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = 3648;";
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
            <select class="form-control input-sm tipificacion" name="tipificacion" id="G2992_C59899">
                <option value="0">Tipificaci&oacute;n</option>
                <?php
                $Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b, MONOEF_EFECTIVA__B,  MONOEF_ConsInte__b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, LISOPC_CambRepr__b , MONOEF_Contacto__b FROM ".$BaseDatos_systema.".LISOPC 
                        JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF.MONOEF_ConsInte__b = LISOPC.LISOPC_Clasifica_b
                        WHERE LISOPC.LISOPC_ConsInte__OPCION_b = 3648;";
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
            <select class="form-control input-sm reintento" name="reintento" id="G2992_C59900">
                <option value="0">Reintento</option>
                <option value="1">REINTENTO AUTOMATICO</option>
                <option value="2">AGENDADO</option>
                <option value="3">NO REINTENTAR</option>
            </select>     
        </div>
    </div>
    <div class="col-md-4 col-xs-4">
        <div class="form-group">
            <input type="text" name="TxtFechaReintento" id="G2992_C59901" class="form-control input-sm TxtFechaReintento" placeholder="Fecha Reintento"  >
        </div>
    </div>
    <div class="col-md-4 col-xs-4" style="text-align: left;">
        <div class="form-group">
            <input type="text" name="TxtHoraReintento" id="G2992_C59902" class="form-control input-sm TxtHoraReintento" placeholder="Hora Reintento">
        </div>
    </div>
</div>
<div class="row" style="background-color: #FAFAFA;">
    <div class="col-md-12 col-xs-12">
        <div class="form-group">
            <textarea class="form-control input-sm textAreaComentarios" name="textAreaComentarios" id="G2992_C59903" placeholder="Observaciones"></textarea>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- SECCION : PAGINAS INCLUIDAS -->

<?php 

    include(__DIR__ ."/../pies.php");

?>
<script type="text/javascript" src="formularios/G2992/G2992_eventos.js"></script>
<?php require_once "G2992_extender_funcionalidad.php";?>
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
                
                $("#s_9171").attr("hidden", false);
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
                        $("#G2992_C<?=$_GET['pincheCampo'];?>").attr("opt","<?=$_GET["idFather"]?>");
                        $("#G2992_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET["idFather"]?>");
                        setTimeout(function(){
                            $("#G2992_C<?=$_GET['pincheCampo'];?>").change();       
                        },1000);                        
                    <?php }else{
                    $sqlMiembro=$mysqli->query("SELECT G{$_GET['formularioPadre']}_CodigoMiembro AS miembro FROM DYALOGOCRM_WEB.G{$_GET['formularioPadre']} WHERE G{$_GET['formularioPadre']}_ConsInte__b={$_GET['idFather']}");
                    if($sqlMiembro && $sqlMiembro-> num_rows ==1){
                        $sqlMiembro=$sqlMiembro->fetch_object();
                        $intMiembro=$sqlMiembro->miembro;
                    }
        ?>
                        $("#G2992_C<?=$_GET['pincheCampo'];?>").attr("opt","<?=$intMiembro?>");
                        $("#G2992_C<?=$_GET['pincheCampo'];?>").val("<?=$intMiembro?>");
                        setTimeout(function(){
                            $("#G2992_C<?=$_GET['pincheCampo'];?>").change();       
                        },1000);                        
                <?php } ?>
        <?php }else{ ?>
                $("#G2992_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET['yourfather'];?>");
        <?php } ?>        
        <?php }else{ ?>
            if(document.getElementById("G2992_C<?=$_GET['pincheCampo'];?>").type == "select-one"){
                $.ajax({
                    url      : '<?=$url_crud;?>?Combo_Guion_G<?php echo $_GET['formulario'];?>_C<?php echo $_GET['pincheCampo']; ?>=si',
                    type     : 'POST',
                    data     : { q : <?php echo $_GET["idFather"]; ?> },
                    success  : function(data){
                        $("#G<?php echo $_GET["formulario"]; ?>_C<?php echo $_GET["pincheCampo"]; ?>").html(data);
                    }
                });
            }else{
                $("#G2992_C<?=$_GET['pincheCampo'];?>").val("<?=$_GET['idFather'];?>");
            }
        <?php } ?>
        
    <?php } ?>
    /////////////////////////////////////////////////////////////////////////
    <?php if (!isset($_GET["view"])) {?>
        $("#add").click(function(){
            
            //JDBD - Damos el valor nombre de usuario.
            $("#G2992_C65749").val("<?php if(!isset($_GET["token"])){echo $_SESSION["NOMBRES"];}else{echo getNombreUser($_GET["token"]);}?>");
            //JDBD - Damos el valor fecha actual.
            $("#G2992_C66500").val("<?=date("Y-m-d");?>");
            //JDBD - Damos el valor asignado por defecto a este campo.
            $("#G2992_C59906").val("<?php echo date('H:i:s');?>");
            $("#G2992_C59951").val("0").trigger("change");
            $("#G2992_C59952").val("0").trigger("change");
            $("#G2992_C59953").val("0").trigger("change");
            $("#G2992_C66507").val("0").trigger("change");
            $("#G2992_C61320").val("0").trigger("change");
            $("#G2992_C59955").val("0").trigger("change");
            $("#G2992_C59988").val("0").trigger("change");
            $("#G2992_C59990").val("0").trigger("change");
            $("#G2992_C59992").val("0").trigger("change");
            $("#G2992_C59994").val("0").trigger("change");
            $("#G2992_C59996").val("0").trigger("change");
            $("#G2992_C59998").val("0").trigger("change");
            $("#G2992_C60000").val("0").trigger("change");
            $("#G2992_C60002").val("0").trigger("change");
            $("#G2992_C60004").val("0").trigger("change");
            $("#G2992_C60006").val("0").trigger("change");
            $("#G2992_C61150").val("-203").trigger("change");
            
            
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
                    

            $.jgrid.gridUnload('#tablaDatosDetalless0'); //funcion Recargar 
             
                $("#G2992_C59910").val(item.G2992_C59910); 
                $("#G2992_C59911").val(item.G2992_C59911); 
                $("#G2992_C59913").val(item.G2992_C59913); 
                $("#G2992_C59914").val(item.G2992_C59914); 
                $("#G2992_C59912").val(item.G2992_C59912); 
                $("#G2992_C59915").val(item.G2992_C59915); 
                $("#G2992_C65749").val(item.G2992_C65749); 
                $("#G2992_C66500").val(item.G2992_C66500); 
                $("#G2992_C59899").val(item.G2992_C59899).trigger("change");
                $("#opt_"+item.G2992_C59899).prop("checked",true).trigger("change");  
                $("#G2992_C59900").val(item.G2992_C59900).trigger("change");
                $("#opt_"+item.G2992_C59900).prop("checked",true).trigger("change");  
                $("#G2992_C59901").val(item.G2992_C59901);
                $("#G2992_C59902").val(item.G2992_C59902).trigger("change");  
                $("#G2992_C59903").val(item.G2992_C59903); 
                $("#G2992_C59904").val(item.G2992_C59904); 
                $("#G2992_C59905").val(item.G2992_C59905); 
                $("#G2992_C59906").val(item.G2992_C59906); 
                $("#G2992_C59907").val(item.G2992_C59907); 
                $("#G2992_C59916").val(item.G2992_C59916); 
                $("#G2992_C59917").val(item.G2992_C59917); 
                $("#G2992_C59918").val(item.G2992_C59918); 
                $("#G2992_C59919").val(item.G2992_C59919); 
                $("#G2992_C59920").val(item.G2992_C59920); 
                $("#G2992_C59921").val(item.G2992_C59921); 
                $("#G2992_C59922").val(item.G2992_C59922); 
                $("#G2992_C59923").val(item.G2992_C59923); 
                $("#G2992_C59924").val(item.G2992_C59924); 
                $("#G2992_C65254").val(item.G2992_C65254); 
                $("#G2992_C59951").val(item.G2992_C59951).trigger("change");
                $("#opt_"+item.G2992_C59951).prop("checked",true).trigger("change");  
                $("#G2992_C59952").attr("opt",item.G2992_C59952);  
                $("#G2992_C59953").attr("opt",item.G2992_C59953);    
                if(item.G2992_C61722 == "1"){
                    $("#G2992_C61722")prop('checked', true);
                }else{
                    $("#G2992_C61722").prop('checked', false);
                }  
                $("#G2992_C59955").val(item.G2992_C59955).trigger("change");
                $("#opt_"+item.G2992_C59955).prop("checked",true).trigger("change");  
                $("#G2992_C59988").val(item.G2992_C59988).trigger("change");
                $("#opt_"+item.G2992_C59988).prop("checked",true).trigger("change");  
                $("#G2992_C59990").val(item.G2992_C59990).trigger("change");
                $("#opt_"+item.G2992_C59990).prop("checked",true).trigger("change");  
                $("#G2992_C59992").val(item.G2992_C59992).trigger("change");
                $("#opt_"+item.G2992_C59992).prop("checked",true).trigger("change");  
                $("#G2992_C59994").val(item.G2992_C59994).trigger("change");
                $("#opt_"+item.G2992_C59994).prop("checked",true).trigger("change");  
                $("#G2992_C59996").val(item.G2992_C59996).trigger("change");
                $("#opt_"+item.G2992_C59996).prop("checked",true).trigger("change");  
                $("#G2992_C59998").val(item.G2992_C59998).trigger("change");
                $("#opt_"+item.G2992_C59998).prop("checked",true).trigger("change");  
                $("#G2992_C60000").val(item.G2992_C60000).trigger("change");
                $("#opt_"+item.G2992_C60000).prop("checked",true).trigger("change");  
                $("#G2992_C60002").val(item.G2992_C60002).trigger("change");
                $("#opt_"+item.G2992_C60002).prop("checked",true).trigger("change");  
                $("#G2992_C60004").val(item.G2992_C60004).trigger("change");
                $("#opt_"+item.G2992_C60004).prop("checked",true).trigger("change");  
                $("#G2992_C60006").val(item.G2992_C60006).trigger("change");
                $("#opt_"+item.G2992_C60006).prop("checked",true).trigger("change");  
                $("#G2992_C66544").val(item.G2992_C66544); 
                $("#G2992_C66545").val(item.G2992_C66545); 
                $("#G2992_C61150").val(item.G2992_C61150).trigger("change");
                $("#opt_"+item.G2992_C61150).prop("checked",true).trigger("change");  
                $("#G2992_C61149").val(item.G2992_C61149); 
                $("#G2992_C61151").val(item.G2992_C61151); 
                $("#G2992_C61152").val(item.G2992_C61152); 
                $("#G2992_C61153").val(item.G2992_C61153); 
                $("#G2992_C61154").val(item.G2992_C61154); 
                $("#G2992_C66507").val(item.G2992_C66507).trigger("change");
                $("#opt_"+item.G2992_C66507).prop("checked",true).trigger("change");  
                $("#G2992_C61320").val(item.G2992_C61320).trigger("change");
                $("#opt_"+item.G2992_C61320).prop("checked",true).trigger("change");  
                $("#G2992_C66401").val(item.G2992_C66401); 
                $("#G2992_C61319").val(item.G2992_C61319); 
                $("#G2992_C66484").val(item.G2992_C66484);
                $("#G2992_C66402").val(item.G2992_C66402).trigger("change");  
                $("#G2992_C65875").val(item.G2992_C65875); 
                $("#G2992_C65876").val(item.G2992_C65876); 
                $("#G2992_C65877").val(item.G2992_C65877); 
                $("#G2992_C65878").val(item.G2992_C65878); 
                $("#G2992_C65879").val(item.G2992_C65879); 
                $("#G2992_C65880").val(item.G2992_C65880); 
                $("#G2992_C65881").val(item.G2992_C65881); 
                $("#G2992_C65882").val(item.G2992_C65882); 
                $("#G2992_C65883").val(item.G2992_C65883); 
                $("#G2992_C65884").val(item.G2992_C65884); 
                $("#G2992_C65885").val(item.G2992_C65885); 
                $("#G2992_C71470").val(item.G2992_C71470);
                
                cargarHijos_0(
        $("#G2992_C59911").val());
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
        $("#btnLlamar_0").attr('padre', <?php echo $_GET['registroId'];?>);

        vamosRecargaLasGrillasPorfavor(<?php echo $_GET['registroId'];?>)

        <?php } ?>

        <?php if(isset($_GET['user'])){ ?>
            /*$("#btnLlamar_0").attr('padre', <?php echo $_GET['user'];?>);
            vamosRecargaLasGrillasPorfavor('<?php echo $_GET['user'];?>');
            idTotal = <?php echo $_GET['user'];?>; */
        <?php } ?>

        $("#refrescarGrillas").click(function(){
            

            $.jgrid.gridUnload('#tablaDatosDetalless0');
            
        $("#btnLlamar_0").attr('padre', $("#G2992_C59911").val());
            var id_0 = $("#G2992_C59911").val();
            $.jgrid.gridUnload('#tablaDatosDetalless0'); //funcion Recargar 
            cargarHijos_0(id_0);
        });

        //Esta es la funcionalidad de los Tabs
        
 

    $("#tabs_click_0").click(function(){ 
        $.jgrid.gridUnload('#tablaDatosDetalless0'); 
        $("#btnLlamar_0").attr('padre', $("#G2992_C59911").val());
        var id_0 = $("#G2992_C59911").val();
        $.jgrid.gridUnload('#tablaDatosDetalless0'); //funcion Recargar 
        cargarHijos_0(id_0);
    });

    $("#btnLlamar_0").click(function( event ) {
        event.preventDefault(); 
        var padre = $("#G2992_C59911").val();
        


           
            if($("#oper").val() == 'add'){
                if(before_save()){
                    $("#frameContenedor").attr('src', 'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario=3286&G3286_C67393=Subformulario&view=si&formaDetalle=si&formularioPadre=2992&idFather='+idTotal+'&yourfather='+ padre +'&pincheCampo=67399<?php if(isset($_GET['token'])){ echo "&token=".$_GET['token']; }?>&usuario=<?=$idUsuario?><?php if(isset($_GET['consinte'])){ echo "&consinte=".$_GET['consinte']; }?>');
                    $("#editarDatos").modal('show');
                }else{
                    before_save();
                    var d = new Date();
                    var h = d.getHours();
                    var horas = (h < 10) ? '0' + h : h;
                    var dia = d.getDate();
                    var dias = (dia < 10) ? '0' + dia : dia;
                    var fechaFinal = d.getFullYear() + '-' + meses[d.getMonth()] + '-' + dias + ' '+ horas +':'+d.getMinutes()+':'+d.getSeconds();
                    $("#FechaFinal").val(fechaFinal);
                    
                    var valido = 0;
                    

            if(($("#G2992_C59910").val() == "") && $("#G2992_C59910").prop("disabled") == false){
                alertify.error('NOMBRE Y APELLIDOS  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59910").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59911").val() == "") && $("#G2992_C59911").prop("disabled") == false){
                alertify.error('CEDULA  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59911").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59913").val() == "") && $("#G2992_C59913").prop("disabled") == false){
                alertify.error('TELEFONO 1  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59913").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59915").val() == "") && $("#G2992_C59915").prop("disabled") == false){
                alertify.error('CODIGO Y/O REFERENCIA  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59915").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59951").val()==0 || $("#G2992_C59951").val() == null || $("#G2992_C59951").val() == -1) && $("#G2992_C59951").prop("disabled") == false){
                alertify.error('La solicitud proviene de incumplimiento o errores por parte de la empresa debe ser diligenciado');
                $("#G2992_C59951").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59952").val()==0 || $("#G2992_C59952").val() == null || $("#G2992_C59952").val() == -1) && $("#G2992_C59952").prop("disabled") == false){
                alertify.error('SECCION debe ser diligenciado');
                $("#G2992_C59952").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59953").val()==0 || $("#G2992_C59953").val() == null || $("#G2992_C59953").val() == -1) && $("#G2992_C59953").prop("disabled") == false){
                alertify.error('TIPO DE DOLOR O SOLICITUD debe ser diligenciado');
                $("#G2992_C59953").closest(".form-group").addClass("has-error");
                valido = 1;
            }
                    if (validado == '0') {
                        var form = $("#FormularioDatos");
                        //Se crean un array con los datos a enviar, apartir del formulario 
                        var formData = new FormData($("#FormularioDatos")[0]);
                        $.ajax({
                           url: '<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>&CodigoMiembro=<?php if(isset($_GET['user'])) { echo $_GET["user"]; }else{ echo "0";  } ?><?php if(isset($_GET['id_gestion_cbx'])){ echo "&id_gestion_cbx=".$_GET['id_gestion_cbx']; }?><?php if(!empty($token)){ echo "&token=".$token; }?>',  
                            type: 'POST',
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            //una vez finalizado correctamente
                            success: function(data){
                                try{
                                    afterSave(data);
                                }catch(e){}
                                if(data){
                                    //Si realizo la operacion ,perguntamos cual es para posarnos sobre el nuevo registro
                                    if($("#oper").val() == 'add'){
                                        idTotal = data;
                                    }else{
                                        idTotal= $("#hidId").val();
                                    }
                                    $("#hidId").val(idTotal);

                                    int_guardo = 1;
                                    $(".llamadores").attr('padre', idTotal);
                                    $("#frameContenedor").attr('src', 'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario=3286&G3286_C67393=Subformulario&view=si&formaDetalle=si&formularioPadre=2992&idFather='+idTotal+'&yourfather='+ padre +'&pincheCampo=67399&action=add<?php if(isset($_GET['token'])){ echo "&token=".$_GET['token']; }?>&usuario=<?=$idUsuario?><?php if(isset($_GET['consinte'])){ echo "&consinte=".$_GET['consinte']; }?>');
                                    $("#editarDatos").modal('show');
                                    $("#oper").val('edit');

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
                    }
                }
            }else{

                $("#frameContenedor").attr('src', 'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario=3286&G3286_C67393=Subformulario&view=si&idFather='+idTotal+'&yourfather='+ padre +'&formaDetalle=si&formularioPadre=2992&pincheCampo=67399&action=add<?php if(isset($_GET['token'])){ echo "&token=".$_GET['token']; }?>&usuario=<?=$idUsuario?><?php if(isset($_GET['consinte'])){ echo "&consinte=".$_GET['consinte']; }?>');
                $("#editarDatos").modal('show');
            }
    });
        //Select2 estos son los guiones
        


    $("#G2992_C59951").select2();

    $("#G2992_C59952").select2();

    $("#G2992_C59953").select2();

    $("#G2992_C66507").select2();

    $("#G2992_C61320").select2();

    $("#G2992_C59955").select2();

    $("#G2992_C59988").select2();

    $("#G2992_C59990").select2();

    $("#G2992_C59992").select2();

    $("#G2992_C59994").select2();

    $("#G2992_C59996").select2();

    $("#G2992_C59998").select2();

    $("#G2992_C60000").select2();

    $("#G2992_C60002").select2();

    $("#G2992_C60004").select2();

    $("#G2992_C60006").select2();

    $("#G2992_C61150").select2();
        //datepickers
        

        $("#G2992_C59901").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
        });

        $("#G2992_C66401").datepicker({
            language: "es",
            autoclose: true,
            todayHighlight: true
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
        $("#G2992_C59902").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }
            }
            $("#G2992_C59902").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#G2992_C59902").val($(this).val()).trigger("change")});
        });
        $("#G2992_C59902").change();

        //Timepicker
        var options = { //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
            title: 'Hora llamada', //The Wickedpicker's title,
            showSeconds: true, //Whether or not to show seconds,
            secondsInterval: 1, //Change interval for seconds, defaults to 1
            minutesInterval: 1, //Change interval for minutes, defaults to 1
            beforeShow: null, //A function to be called before the Wickedpicker is shown
            show: null, //A function to be called when the Wickedpicker is shown
            clearable: false, //Make the picker's input clearable (has clickable "x")
        }; 
        $("#G2992_C66402").change(function(){
            let hora=true;
            if($(this).val() != ""){
                try{
                    hora="2022-04-05 "+$(this).val();
                }catch(e){
                    hora=true;
                }
            }
            $("#G2992_C66402").datetimepicker({
                format:"HH:mm:ss",
                //useCurrent:hora
            }).on("dp.hide", function(ev){ $("#G2992_C66402").val($(this).val()).trigger("change")});
        });
        $("#G2992_C66402").change();

        //Validaciones numeros Enteros
        

        $("#G2992_C65875").numeric();
                
        $("#G2992_C65876").numeric();
                
        $("#G2992_C65877").numeric();
                
        $("#G2992_C65878").numeric();
                
        $("#G2992_C65879").numeric();
                
        $("#G2992_C65880").numeric();
                
        $("#G2992_C65881").numeric();
                
        $("#G2992_C65882").numeric();
                
        $("#G2992_C65883").numeric();
                
        $("#G2992_C65884").numeric();
                
        $("#G2992_C65885").numeric();
                

        //Validaciones numeros Decimales
        

        $("#G2992_C61149").numeric({ decimal : ".",  negative : false, scale: 4 });
                

        /* Si son d formulas */
        


        //function para 1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto) 

    $("#G2992_C65875").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 2.  Conversación: Tono amable 

    $("#G2992_C65876").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 3. Lenguaje Adecuado 

    $("#G2992_C65877").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 4.   Volumen de voz adecuado 

    $("#G2992_C65878").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 5. Escucha Activa 

    $("#G2992_C65879").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa 

    $("#G2992_C65880").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 7. Si hay interrupción al cliente utiliza protocolo establecido 

    $("#G2992_C65881").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 8. Realiza acompañamiento al cliente al dejar la llamada en espera 

    $("#G2992_C65882").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 9.   Radicación pertinente de la SPQR´s 

    $("#G2992_C65883").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx  

    $("#G2992_C65884").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //function para 11.   Invita al cliente a diligenciar la encuesta de satisfaccion 

    $("#G2992_C65885").on('blur',function(e){

        
                                var intCalculo_t = ((Number($("#G2992_C65875").val())*5)+(Number($("#G2992_C65876").val())*10)+(Number($("#G2992_C65877").val())*10)+(Number($("#G2992_C65878").val())*10)+(Number($("#G2992_C65879").val())*10)+(Number($("#G2992_C65880").val())*15)+(Number($("#G2992_C65881").val())*5)+(Number($("#G2992_C65882").val())*10)+(Number($("#G2992_C65883").val())*10)+(Number($("#G2992_C65884").val())*5)+(Number($("#G2992_C65885").val())*10))/100;
                        
                                $("#G2992_C61149").val(intCalculo_t.toFixed(0));

                            });

        //Si tienen dependencias

        


    //function para TELEFONO 1  SOLICITANTE 

    $("#TLF_G2992_C59913").click(function(){
        strTel_t=$("#G2992_C59913").val();
        llamarDesdeBtnTelefono(strTel_t);
    });

    //function para TELEFONO 2  SOLICITANTE 

    $("#TLF_G2992_C59914").click(function(){
        strTel_t=$("#G2992_C59914").val();
        llamarDesdeBtnTelefono(strTel_t);
    });

    //function para TELEFONO 1 SUSCRIPTOR 

    $("#TLF_G2992_C59919").click(function(){
        strTel_t=$("#G2992_C59919").val();
        llamarDesdeBtnTelefono(strTel_t);
    });

    //function para TELEFONO 2 SUSCRIPTOR 

    $("#TLF_G2992_C59920").click(function(){
        strTel_t=$("#G2992_C59920").val();
        llamarDesdeBtnTelefono(strTel_t);
    });

    //function para La solicitud proviene de incumplimiento o errores por parte de la empresa 

    $(".G2992_C59951").change(function(){  
        //Esto es la parte de las listas dependientes
        

        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '3663' , idPadre : $(this).val() },
            success : function(data){
                var optG2992_C59952 = $("#G2992_C59952").attr("opt");
                $("#G2992_C59952").html(data);
                if (optG2992_C59952 != null) {
                    $("#G2992_C59952").val(optG2992_C59952).trigger("change");
                }
            }
        });
        
        $.ajax({
            url    : '<?php echo $url_crud; ?>',
            type   : 'post',
            data   : { getListaHija : true , opcionID : '3652' , idPadre : $(this).val() },
            success : function(data){
                var optG2992_C59953 = $("#G2992_C59953").attr("opt");
                $("#G2992_C59953").html(data);
                if (optG2992_C59953 != null) {
                    $("#G2992_C59953").val(optG2992_C59953).trigger("change");
                }
            }
        });
        
    });

    //function para SECCION 

    $(".G2992_C59952").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para TIPO DE DOLOR O SOLICITUD 

    $(".G2992_C59953").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para Agenda 

    $(".G2992_C66507").change(function(){ 
        $("#G2992_C66401").prop('disabled', false);
        
        $("#G2992_C66402").prop('disabled', false);
        
        //JDBD-20-05-17: funcion para saltos.
        if($(this).val() == '48574'){
                            $("#G2992_C66401").closest(".form-group").removeClass("has-error"); 
                            $("#G2992_C66401").prop('disabled', true); 
                          
                            $("#G2992_C66402").closest(".form-group").removeClass("has-error"); 
                            $("#G2992_C66402").prop('disabled', true); 
                          
        }
        if($(this).val()==0){
                            $("#G2992_C66401").closest(".form-group").removeClass("has-error"); 
                            $("#G2992_C66401").prop('disabled', true); 
                          
                            $("#G2992_C66402").closest(".form-group").removeClass("has-error"); 
                            $("#G2992_C66402").prop('disabled', true); 
                          
 } 
        //Esto es la parte de las listas dependientes
        

    });

    //function para Seleccion resultado llamada 

    $(".G2992_C61320").change(function(){ 
        $("#G2992_C61319").prop('disabled', false);
        
        //JDBD-20-05-17: funcion para saltos.
        if($(this).val() == '46330'){
                            $("#G2992_C61319").closest(".form-group").removeClass("has-error"); 
                            $("#G2992_C61319").prop('disabled', true); 
                          
        }
        if($(this).val()==0){
                            $("#G2992_C61319").closest(".form-group").removeClass("has-error"); 
                            $("#G2992_C61319").prop('disabled', true); 
                          
 } 
        //Esto es la parte de las listas dependientes
        

    });

    //function para 1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto) 

    $(".G2992_C59955").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 2.  Conversación: Tono amable 

    $(".G2992_C59988").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 3. Lenguaje Adecuado 

    $(".G2992_C59990").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 4.   Volumen de voz adecuado 

    $(".G2992_C59992").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 5. Escucha Activa 

    $(".G2992_C59994").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa 

    $(".G2992_C59996").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 7. Si hay interrupción al cliente utiliza protocolo establecido 

    $(".G2992_C59998").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 8. Realiza acompañamiento al cliente al dejar la llamada en espera 

    $(".G2992_C60000").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 9.   Radicación pertinente de la SPQR´s 

    $(".G2992_C60002").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx  

    $(".G2992_C60004").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para 11.   Invita al cliente a diligenciar la encuesta de satisfaccion 

    $(".G2992_C60006").change(function(){  
        //Esto es la parte de las listas dependientes
        

    });

    //function para ESTADO_CALIDAD_Q_DY 

    $(".G2992_C61150").change(function(){  
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
                                            
 
                                                $("#G2992_C59910").val(item.G2992_C59910);
 
                                                $("#G2992_C59911").val(item.G2992_C59911);
 
                                                $("#G2992_C59913").val(item.G2992_C59913);
 
                                                $("#G2992_C59914").val(item.G2992_C59914);
 
                                                $("#G2992_C59912").val(item.G2992_C59912);
 
                                                $("#G2992_C59915").val(item.G2992_C59915);
 
                                                $("#G2992_C65749").val(item.G2992_C65749);
 
                                                $("#G2992_C66500").val(item.G2992_C66500);
 
                    $("#G2992_C59899").val(item.G2992_C59899).trigger("change");
                    $("#opt_"+item.G2992_C59899).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59900").val(item.G2992_C59900).trigger("change");
                    $("#opt_"+item.G2992_C59900).prop("checked",true).trigger("change"); 
 
                                                $("#G2992_C59901").val(item.G2992_C59901);

                                                $("#G2992_C59902").val(item.G2992_C59902).trigger("change"); 
 
                                                $("#G2992_C59903").val(item.G2992_C59903);
 
                                                $("#G2992_C59904").val(item.G2992_C59904);
 
                                                $("#G2992_C59905").val(item.G2992_C59905);
 
                                                $("#G2992_C59906").val(item.G2992_C59906);
 
                                                $("#G2992_C59907").val(item.G2992_C59907);
 
                                                $("#G2992_C59916").val(item.G2992_C59916);
 
                                                $("#G2992_C59917").val(item.G2992_C59917);
 
                                                $("#G2992_C59918").val(item.G2992_C59918);
 
                                                $("#G2992_C59919").val(item.G2992_C59919);
 
                                                $("#G2992_C59920").val(item.G2992_C59920);
 
                                                $("#G2992_C59921").val(item.G2992_C59921);
 
                                                $("#G2992_C59922").val(item.G2992_C59922);
 
                                                $("#G2992_C59923").val(item.G2992_C59923);
 
                                                $("#G2992_C59924").val(item.G2992_C59924);
 
                                                $("#G2992_C65254").val(item.G2992_C65254);
 
                    $("#G2992_C59951").val(item.G2992_C59951).trigger("change");
                    $("#opt_"+item.G2992_C59951).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59952").attr("opt",item.G2992_C59952); 
 
                    $("#G2992_C59953").attr("opt",item.G2992_C59953); 
      
                                                if(item.G2992_C61722 == "1"){
                                                   $("#G2992_C61722").prop('checked', true);
                                                }else{
                                                    $("#G2992_C61722").prop('checked', false);
                                                } 
 
                    $("#G2992_C59955").val(item.G2992_C59955).trigger("change");
                    $("#opt_"+item.G2992_C59955).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59988").val(item.G2992_C59988).trigger("change");
                    $("#opt_"+item.G2992_C59988).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59990").val(item.G2992_C59990).trigger("change");
                    $("#opt_"+item.G2992_C59990).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59992").val(item.G2992_C59992).trigger("change");
                    $("#opt_"+item.G2992_C59992).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59994").val(item.G2992_C59994).trigger("change");
                    $("#opt_"+item.G2992_C59994).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59996").val(item.G2992_C59996).trigger("change");
                    $("#opt_"+item.G2992_C59996).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59998").val(item.G2992_C59998).trigger("change");
                    $("#opt_"+item.G2992_C59998).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60000").val(item.G2992_C60000).trigger("change");
                    $("#opt_"+item.G2992_C60000).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60002").val(item.G2992_C60002).trigger("change");
                    $("#opt_"+item.G2992_C60002).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60004").val(item.G2992_C60004).trigger("change");
                    $("#opt_"+item.G2992_C60004).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60006").val(item.G2992_C60006).trigger("change");
                    $("#opt_"+item.G2992_C60006).prop("checked",true).trigger("change"); 
 
                                                $("#G2992_C66544").val(item.G2992_C66544);
 
                                                $("#G2992_C66545").val(item.G2992_C66545);
 
                    $("#G2992_C61150").val(item.G2992_C61150).trigger("change");
                    $("#opt_"+item.G2992_C61150).prop("checked",true).trigger("change"); 
 
                                                $("#G2992_C61149").val(item.G2992_C61149);
 
                                                $("#G2992_C61151").val(item.G2992_C61151);
 
                                                $("#G2992_C61152").val(item.G2992_C61152);
 
                                                $("#G2992_C61153").val(item.G2992_C61153);
 
                                                $("#G2992_C61154").val(item.G2992_C61154);
 
                    $("#G2992_C66507").val(item.G2992_C66507).trigger("change");
                    $("#opt_"+item.G2992_C66507).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C61320").val(item.G2992_C61320).trigger("change");
                    $("#opt_"+item.G2992_C61320).prop("checked",true).trigger("change"); 
 
                                                $("#G2992_C66401").val(item.G2992_C66401);
 
                                                $("#G2992_C61319").val(item.G2992_C61319);
 
                                                $("#G2992_C66484").val(item.G2992_C66484);

                                                $("#G2992_C66402").val(item.G2992_C66402).trigger("change"); 
 
                                                $("#G2992_C65875").val(item.G2992_C65875);
 
                                                $("#G2992_C65876").val(item.G2992_C65876);
 
                                                $("#G2992_C65877").val(item.G2992_C65877);
 
                                                $("#G2992_C65878").val(item.G2992_C65878);
 
                                                $("#G2992_C65879").val(item.G2992_C65879);
 
                                                $("#G2992_C65880").val(item.G2992_C65880);
 
                                                $("#G2992_C65881").val(item.G2992_C65881);
 
                                                $("#G2992_C65882").val(item.G2992_C65882);
 
                                                $("#G2992_C65883").val(item.G2992_C65883);
 
                                                $("#G2992_C65884").val(item.G2992_C65884);
 
                                                $("#G2992_C65885").val(item.G2992_C65885);
 
                                                $("#G2992_C71470").val(item.G2992_C71470);
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
            

            if(($("#G2992_C59910").val() == "") && $("#G2992_C59910").prop("disabled") == false){
                alertify.error('NOMBRE Y APELLIDOS  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59910").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59911").val() == "") && $("#G2992_C59911").prop("disabled") == false){
                alertify.error('CEDULA  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59911").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59913").val() == "") && $("#G2992_C59913").prop("disabled") == false){
                alertify.error('TELEFONO 1  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59913").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59915").val() == "") && $("#G2992_C59915").prop("disabled") == false){
                alertify.error('CODIGO Y/O REFERENCIA  SOLICITANTE debe ser diligenciado');
                $("#G2992_C59915").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59951").val()==0 || $("#G2992_C59951").val() == null || $("#G2992_C59951").val() == -1) && $("#G2992_C59951").prop("disabled") == false){
                alertify.error('La solicitud proviene de incumplimiento o errores por parte de la empresa debe ser diligenciado');
                $("#G2992_C59951").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59952").val()==0 || $("#G2992_C59952").val() == null || $("#G2992_C59952").val() == -1) && $("#G2992_C59952").prop("disabled") == false){
                alertify.error('SECCION debe ser diligenciado');
                $("#G2992_C59952").closest(".form-group").addClass("has-error");
                valido = 1;
            }

            if(($("#G2992_C59953").val()==0 || $("#G2992_C59953").val() == null || $("#G2992_C59953").val() == -1) && $("#G2992_C59953").prop("disabled") == false){
                alertify.error('TIPO DE DOLOR O SOLICITUD debe ser diligenciado');
                $("#G2992_C59953").closest(".form-group").addClass("has-error");
                valido = 1;
            }
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
            colNames:['id','NOMBRE Y APELLIDOS  SOLICITANTE','CEDULA  SOLICITANTE','TELEFONO 1  SOLICITANTE','TELEFONO 2  SOLICITANTE','CORREO  SOLICITANTE','CODIGO Y/O REFERENCIA  SOLICITANTE','ASESOR','FECHA CREACION GESTION','Agente','Fecha','Hora','Campaña','NOMBRE Y APELLIDOS SUSCRIPTOR','CEDULA SUSCRIPTOR','CORREO  SUSCRIPTOR','TELEFONO 1 SUSCRIPTOR','TELEFONO 2 SUSCRIPTOR','CÓDIGO SUSCRIPTOR','RELACION CON EL PREDIO','ESTADO SUSCRIPCION','MORISIDAD','REFERENCIA SUSCRIPTOR','La solicitud proviene de incumplimiento o errores por parte de la empresa','SECCION','TIPO DE DOLOR O SOLICITUD','AUTORIZACIÓN PARA EL TRATAMIENTO DE DATOS PERSONALES','1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto)','2.  Conversación: Tono amable','3. Lenguaje Adecuado','4.   Volumen de voz adecuado','5. Escucha Activa','6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa','7. Si hay interrupción al cliente utiliza protocolo establecido','8. Realiza acompañamiento al cliente al dejar la llamada en espera','9.   Radicación pertinente de la SPQR´s','10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx ','11.   Invita al cliente a diligenciar la encuesta de satisfaccion','ID GESTION','LINK DE GRABACION','ESTADO_CALIDAD_Q_DY','CALIFICACION_Q_DY','COMENTARIO_CALIDAD_Q_DY','COMENTARIO_AGENTE_Q_DY','FECHA_AUDITADO_Q_DY','NOMBRE_AUDITOR_Q_DY','Agenda','Seleccion resultado llamada','Fecha llamada','Numero radicado','Comentarios','Hora llamada','1.  Saludo inicial (Buen día/ tarde gracias por su amale espera, mi nombre es  xxx con quien tengo el gusto)','2.  Conversación: Tono amable','3. Lenguaje Adecuado','4.   Volumen de voz adecuado','5. Escucha Activa','6.  Atención a la solicitud:Da la información o la solución a la solicitud del cliente de manera  correcta y completa','7. Si hay interrupción al cliente utiliza protocolo establecido','8. Realiza acompañamiento al cliente al dejar la llamada en espera','9.   Radicación pertinente de la SPQR´s','10.   Despedida (señor(a) xxx que tenga buen día/ tarde, recuerde que lo atendió xxx ','11.   Invita al cliente a diligenciar la encuesta de satisfaccion','Solicitud'],
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
                        name:'G2992_C59910', 
                        index: 'G2992_C59910', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59911', 
                        index: 'G2992_C59911', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59913', 
                        index: 'G2992_C59913', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59914', 
                        index: 'G2992_C59914', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59915', 
                        index: 'G2992_C59915', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C65749', 
                        index: 'G2992_C65749', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    {  
                        name:'G2992_C66500', 
                        index:'G2992_C66500', 
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
                        name:'G2992_C59904', 
                        index: 'G2992_C59904', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59905', 
                        index: 'G2992_C59905', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59906', 
                        index: 'G2992_C59906', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59907', 
                        index: 'G2992_C59907', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59916', 
                        index: 'G2992_C59916', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59917', 
                        index: 'G2992_C59917', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59919', 
                        index: 'G2992_C59919', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59920', 
                        index: 'G2992_C59920', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59921', 
                        index: 'G2992_C59921', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59922', 
                        index: 'G2992_C59922', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59923', 
                        index: 'G2992_C59923', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59924', 
                        index: 'G2992_C59924', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C65254', 
                        index: 'G2992_C65254', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C59951', 
                        index:'G2992_C59951', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3662&campo=G2992_C59951'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59952', 
                        index:'G2992_C59952', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3663&campo=G2992_C59952'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59953', 
                        index:'G2992_C59953', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3652&campo=G2992_C59953'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C61722', 
                        index:'G2992_C61722', 
                        width:70 ,
                        editable: true, 
                        edittype:"checkbox",
                        editoptions: {
                            value:"1:0"
                        } 
                    }

                    ,
                    { 
                        name:'G2992_C59955', 
                        index:'G2992_C59955', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C59955'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59988', 
                        index:'G2992_C59988', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C59988'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59990', 
                        index:'G2992_C59990', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C59990'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59992', 
                        index:'G2992_C59992', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C59992'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59994', 
                        index:'G2992_C59994', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C59994'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59996', 
                        index:'G2992_C59996', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C59996'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C59998', 
                        index:'G2992_C59998', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C59998'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C60000', 
                        index:'G2992_C60000', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C60000'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C60002', 
                        index:'G2992_C60002', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C60002'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C60004', 
                        index:'G2992_C60004', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C60004'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C60006', 
                        index:'G2992_C60006', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3675&campo=G2992_C60006'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C66544', 
                        index: 'G2992_C66544', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C66545', 
                        index: 'G2992_C66545', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C61150', 
                        index:'G2992_C61150', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=-3&campo=G2992_C61150'
                        }
                    }

                    ,
                    {  
                        name:'G2992_C61149', 
                        index:'G2992_C61149', 
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
                        name:'G2992_C61151', 
                        index:'G2992_C61151', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C61152', 
                        index:'G2992_C61152', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    {  
                        name:'G2992_C61153', 
                        index:'G2992_C61153', 
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
                        name:'G2992_C61154', 
                        index: 'G2992_C61154', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C66507', 
                        index:'G2992_C66507', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=4161&campo=G2992_C66507'
                        }
                    }

                    ,
                    { 
                        name:'G2992_C61320', 
                        index:'G2992_C61320', 
                        width:120 ,
                        editable: true, 
                        edittype:"select" , 
                        editoptions: {
                            dataUrl: '<?=$url_crud;?>?CallDatosLisop_=si&idLista=3679&campo=G2992_C61320'
                        }
                    }

                    ,
                    {  
                        name:'G2992_C66401', 
                        index:'G2992_C66401', 
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
                        name:'G2992_C61319', 
                        index: 'G2992_C61319', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G2992_C66484', 
                        index:'G2992_C66484', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    {  
                        name:'G2992_C66402', 
                        index:'G2992_C66402', 
                        width:70 ,
                        editable: true ,
                        formatter: 'text', 
                        editoptions:{
                            size:20,
                            dataInit:function(el){
                                //Timepicker
                                var options = { 
                                    now: "15:00:00", //hh:mm 24 hour format only, defaults to current time
                                    twentyFour: true, //Display 24 hour format, defaults to false
                                    title: 'Hora llamada', //The Wickedpicker's title,
                                    showSeconds: true, //Whether or not to show seconds,
                                    secondsInterval: 1, //Change interval for seconds, defaults to 1
                                    minutesInterval: 1, //Change interval for minutes, defaults to 1
                                    beforeShow: null, //A function to be called before the Wickedpicker is shown
                                    show: null, //A function to be called when the Wickedpicker is shown
                                    clearable: false, //Make the picker's input clearable (has clickable "x")
                                }; 
                                $(el).wickedpicker(options);
                            }
                        }
                    }
 
                    ,
                    {  
                        name:'G2992_C65875', 
                        index:'G2992_C65875', 
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
                        name:'G2992_C65876', 
                        index:'G2992_C65876', 
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
                        name:'G2992_C65877', 
                        index:'G2992_C65877', 
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
                        name:'G2992_C65878', 
                        index:'G2992_C65878', 
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
                        name:'G2992_C65879', 
                        index:'G2992_C65879', 
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
                        name:'G2992_C65880', 
                        index:'G2992_C65880', 
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
                        name:'G2992_C65881', 
                        index:'G2992_C65881', 
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
                        name:'G2992_C65882', 
                        index:'G2992_C65882', 
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
                        name:'G2992_C65883', 
                        index:'G2992_C65883', 
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
                        name:'G2992_C65884', 
                        index:'G2992_C65884', 
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
                        name:'G2992_C65885', 
                        index:'G2992_C65885', 
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
                        name:'G2992_C71470', 
                        index: 'G2992_C71470', 
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
            sortname: 'G2992_C59915',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'PRUEBAS',
            editurl:"<?=$url_crud;?>?insertarDatosGrilla=si&usuario=<?=$idUsuario?>",
            autowidth: true
            
            ,subGrid: true,
            subGridRowExpanded: function(subgrid_id, row_id) { 
                // we pass two parameters 
                // subgrid_id is a id of the div tag created whitin a table data 
                // the id of this elemenet is a combination of the "sg_" + id of the row 
                // the row_id is the id of the row 
                // If we wan to pass additinal parameters to the url we can use 
                // a method getRowData(row_id) - which returns associative array in type name-value 
                // here we can easy construct the flowing 
                $("#"+subgrid_id).html('');

                var subgrid_table_id_0, pager_id_0; 

                subgrid_table_id_0 = subgrid_id+"_t_0"; 

                pager_id_ = "p_"+subgrid_table_id_0; 

                $("#"+subgrid_id).append("<table id='"+subgrid_table_id_0+"' class='scroll'></table><div id='"+pager_id_0+"' class='scroll'></div>"); 

                jQuery("#"+subgrid_table_id_0).jqGrid({ 
                    url:'<?=$url_crud;?>?callDatosSubgrilla_0=si&id='+row_id,
                    datatype: 'xml',
                    mtype: 'POST',
                    colNames:['id','ID','Celular','Cedula','Correo','Editar mensaje sms','Carta de responsabilidad ','Carta de Instruccion ','Plazo de Financiacion','Cedula Adjunto','Otro Documento1','Otro Documento2','Autorizacion','Central de riesgo','Documento del Predio','Cedula Cambio Popietario','Otro1 Cambio Popietario','Otro 2 Cambio Popietario','Otro', 'padre'],
                    colModel: [ 
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
                                name:'G3286_C67397', 
                                index: 'G3286_C67397', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C67398', 
                                index: 'G3286_C67398', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }
 
                            ,
                            {  
                                name:'G3286_C67399', 
                                index:'G3286_C67399', 
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
                                name:'G3286_C70893', 
                                index: 'G3286_C70893', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C71545', 
                                index:'G3286_C71545', 
                                width:150, 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70872', 
                                index: 'G3286_C70872', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70873', 
                                index: 'G3286_C70873', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70874', 
                                index: 'G3286_C70874', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70875', 
                                index: 'G3286_C70875', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70876', 
                                index: 'G3286_C70876', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70877', 
                                index: 'G3286_C70877', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70881', 
                                index: 'G3286_C70881', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70882', 
                                index: 'G3286_C70882', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70883', 
                                index: 'G3286_C70883', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70884', 
                                index: 'G3286_C70884', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70885', 
                                index: 'G3286_C70885', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C70886', 
                                index: 'G3286_C70886', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                            ,
                            { 
                                name:'G3286_C71553', 
                                index: 'G3286_C71553', 
                                width:160, 
                                resizable:false, 
                                sortable:true , 
                                editable: true 
                            }

                        ,
                        { 
                            name: 'Padre', 
                            index:'Padre', 
                            hidden: true , 
                            editable: false, 
                            editrules: { 
                                edithidden:true 
                            },
                            editoptions:{ 
                                dataInit: function(element) {                     
                                    $(element).val(id); 
                                } 
                            }
                        }
                    ], 
                    rowNum:20, 
                    pager: pager_id_0, 
                    sortname: 'num', 
                    sortorder: "asc",
                    height: '100%' 
                }); 

                jQuery("#"+subgrid_table_id_0).jqGrid('navGrid',"#"+pager_id_0,{edit:false,add:false,del:false}) 

                }, 
                subGridRowColapsed: function(subgrid_id, row_id) { 
                    // this function is called before removing the data 
                    //var subgrid_table_id; 
                    //subgrid_table_id = subgrid_id+"_t"; 
                    //jQuery("#"+subgrid_table_id).remove(); 
                }
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

            $("#btnLlamar_0").attr('padre', id);
            //buscar los datos
            $.ajax({
                url      : '<?=$url_crud;?>',
                type     : 'POST',
                data     : { CallDatos : 'SI', id : id },
                dataType : 'json',
                success  : function(data){
                    //recorrer datos y enviarlos al formulario
                    $.each(data, function(i, item) {
                        

            $.jgrid.gridUnload('#tablaDatosDetalless0'); //funcion Recargar 
            

                        $("#G2992_C59910").val(item.G2992_C59910);

                        $("#G2992_C59911").val(item.G2992_C59911);

                        $("#G2992_C59913").val(item.G2992_C59913);

                        $("#G2992_C59914").val(item.G2992_C59914);

                        $("#G2992_C59912").val(item.G2992_C59912);

                        $("#G2992_C59915").val(item.G2992_C59915);

                        $("#G2992_C65749").val(item.G2992_C65749);

                        $("#G2992_C66500").val(item.G2992_C66500);
 
                    $("#G2992_C59899").val(item.G2992_C59899).trigger("change");
                    $("#opt_"+item.G2992_C59899).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59900").val(item.G2992_C59900).trigger("change");
                    $("#opt_"+item.G2992_C59900).prop("checked",true).trigger("change"); 

                        $("#G2992_C59901").val(item.G2992_C59901);
                        $("#G2992_C59902").val(item.G2992_C59902).trigger("change"); 

                        $("#G2992_C59903").val(item.G2992_C59903);

                        $("#G2992_C59904").val(item.G2992_C59904);

                        $("#G2992_C59905").val(item.G2992_C59905);

                        $("#G2992_C59906").val(item.G2992_C59906);

                        $("#G2992_C59907").val(item.G2992_C59907);

                        $("#G2992_C59916").val(item.G2992_C59916);

                        $("#G2992_C59917").val(item.G2992_C59917);

                        $("#G2992_C59918").val(item.G2992_C59918);

                        $("#G2992_C59919").val(item.G2992_C59919);

                        $("#G2992_C59920").val(item.G2992_C59920);

                        $("#G2992_C59921").val(item.G2992_C59921);

                        $("#G2992_C59922").val(item.G2992_C59922);

                        $("#G2992_C59923").val(item.G2992_C59923);

                        $("#G2992_C59924").val(item.G2992_C59924);

                        $("#G2992_C65254").val(item.G2992_C65254);
 
                    $("#G2992_C59951").val(item.G2992_C59951).trigger("change");
                    $("#opt_"+item.G2992_C59951).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59952").attr("opt",item.G2992_C59952); 
 
                    $("#G2992_C59953").attr("opt",item.G2992_C59953); 
    
                        if(item.G2992_C61722 == "1"){
                           $("#G2992_C61722").prop('checked', true);
                        }else{
                            $("#G2992_C61722").prop('checked', false);
                        } 
 
                    $("#G2992_C59955").val(item.G2992_C59955).trigger("change");
                    $("#opt_"+item.G2992_C59955).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59988").val(item.G2992_C59988).trigger("change");
                    $("#opt_"+item.G2992_C59988).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59990").val(item.G2992_C59990).trigger("change");
                    $("#opt_"+item.G2992_C59990).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59992").val(item.G2992_C59992).trigger("change");
                    $("#opt_"+item.G2992_C59992).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59994").val(item.G2992_C59994).trigger("change");
                    $("#opt_"+item.G2992_C59994).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59996").val(item.G2992_C59996).trigger("change");
                    $("#opt_"+item.G2992_C59996).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C59998").val(item.G2992_C59998).trigger("change");
                    $("#opt_"+item.G2992_C59998).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60000").val(item.G2992_C60000).trigger("change");
                    $("#opt_"+item.G2992_C60000).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60002").val(item.G2992_C60002).trigger("change");
                    $("#opt_"+item.G2992_C60002).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60004").val(item.G2992_C60004).trigger("change");
                    $("#opt_"+item.G2992_C60004).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C60006").val(item.G2992_C60006).trigger("change");
                    $("#opt_"+item.G2992_C60006).prop("checked",true).trigger("change"); 

                        $("#G2992_C66544").val(item.G2992_C66544);

                        $("#G2992_C66545").val(item.G2992_C66545);
 
                    $("#G2992_C61150").val(item.G2992_C61150).trigger("change");
                    $("#opt_"+item.G2992_C61150).prop("checked",true).trigger("change"); 

                        $("#G2992_C61149").val(item.G2992_C61149);

                        $("#G2992_C61151").val(item.G2992_C61151);

                        $("#G2992_C61152").val(item.G2992_C61152);

                        $("#G2992_C61153").val(item.G2992_C61153);

                        $("#G2992_C61154").val(item.G2992_C61154);
 
                    $("#G2992_C66507").val(item.G2992_C66507).trigger("change");
                    $("#opt_"+item.G2992_C66507).prop("checked",true).trigger("change"); 
 
                    $("#G2992_C61320").val(item.G2992_C61320).trigger("change");
                    $("#opt_"+item.G2992_C61320).prop("checked",true).trigger("change"); 

                        $("#G2992_C66401").val(item.G2992_C66401);

                        $("#G2992_C61319").val(item.G2992_C61319);

                        $("#G2992_C66484").val(item.G2992_C66484);
                        $("#G2992_C66402").val(item.G2992_C66402).trigger("change"); 

                        $("#G2992_C65875").val(item.G2992_C65875);

                        $("#G2992_C65876").val(item.G2992_C65876);

                        $("#G2992_C65877").val(item.G2992_C65877);

                        $("#G2992_C65878").val(item.G2992_C65878);

                        $("#G2992_C65879").val(item.G2992_C65879);

                        $("#G2992_C65880").val(item.G2992_C65880);

                        $("#G2992_C65881").val(item.G2992_C65881);

                        $("#G2992_C65882").val(item.G2992_C65882);

                        $("#G2992_C65883").val(item.G2992_C65883);

                        $("#G2992_C65884").val(item.G2992_C65884);

                        $("#G2992_C65885").val(item.G2992_C65885);

                        $("#G2992_C71470").val(item.G2992_C71470);
                        
            cargarHijos_0(
        $("#G2992_C59911").val());       
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
                        var audio = $("#Abtns_9171");
                        $("#btns_9171").attr("src",data+"&streaming=true").appendTo(audio);
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
        

            $.jgrid.gridUnload('#tablaDatosDetalless0'); //funcion descargar 
    }
    
    function CalcularFormula(){
        
    }

    <?php } ?>


    

    function cargarHijos_0(id_0){
        $.jgrid.defaults.width = '1225';
        $.jgrid.defaults.height = '650';
        $.jgrid.defaults.responsive = true;
        $.jgrid.defaults.styleUI = 'Bootstrap';
        var lastSels;
        $("#tablaDatosDetalless0").jqGrid({
            url:'<?=$url_crud;?>?callDatosSubgrilla_0=si&id='+id_0,
            datatype: 'xml',
            mtype: 'POST',
            xmlReader: { 
                root:"rows", 
                row:"row",
                cell:"cell",
                id : "[asin]"
            },
            colNames:['id','ID','Celular','Cedula','Correo','Editar mensaje sms','Carta de responsabilidad ','Carta de Instruccion ','Plazo de Financiacion','Cedula Adjunto','Otro Documento1','Otro Documento2','Autorizacion','Central de riesgo','Documento del Predio','Cedula Cambio Popietario','Otro1 Cambio Popietario','Otro 2 Cambio Popietario','Otro', 'padre'],
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
                        name:'G3286_C67397', 
                        index: 'G3286_C67397', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C67398', 
                        index: 'G3286_C67398', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }
 
                    ,
                    {  
                        name:'G3286_C67399', 
                        index:'G3286_C67399', 
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
                        name:'G3286_C70893', 
                        index: 'G3286_C70893', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    {
                        name:'G3286_C71545', 
                        index:'G3286_C71545', 
                        width:150, 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70872', 
                        index: 'G3286_C70872', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70873', 
                        index: 'G3286_C70873', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70874', 
                        index: 'G3286_C70874', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70875', 
                        index: 'G3286_C70875', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70876', 
                        index: 'G3286_C70876', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70877', 
                        index: 'G3286_C70877', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70881', 
                        index: 'G3286_C70881', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70882', 
                        index: 'G3286_C70882', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70883', 
                        index: 'G3286_C70883', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70884', 
                        index: 'G3286_C70884', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70885', 
                        index: 'G3286_C70885', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C70886', 
                        index: 'G3286_C70886', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
                        editable: true 
                    }

                    ,
                    { 
                        name:'G3286_C71553', 
                        index: 'G3286_C71553', 
                        width:160, 
                        resizable:false, 
                        sortable:true , 
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
                    },
                    editoptions:{ 
                        dataInit: function(element) {                     
                            $(element).val(id_0); 
                        } 
                    }
                }
            ],
            rowNum: 40,
            pager: "#pagerDetalles0",
            rowList: [40,80],
            sortable: true,
            sortname: 'G3286_C67397',
            sortorder: 'asc',
            viewrecords: true,
            caption: 'INTERCAMBIO DE DOCUMENTACION LLANOGAS',
            editurl:"<?=$url_crud;?>?insertarDatosSubgrilla_0=si&usuario=<?=$idUsuario?>",
            height:'250px',
            beforeSelectRow: function(rowid){
                if(rowid && rowid!==lastSels){
                    
                }
                lastSels = rowid;
            }
            ,

            ondblClickRow: function(rowId) {
                $("#frameContenedor").attr('src', 'https://<?php echo $_SERVER["HTTP_HOST"];?>/crm_php/new_index.php?formulario=3286&G3286_C67393=Subformulario&view=si&registroId='+ rowId +'&formaDetalle=si&yourfather='+ idTotal +'&pincheCampo=67399&formularioPadre=2992<?php if(isset($_GET['token'])){ echo "&token=".$_GET['token']; }?>');
                $("#editarDatos").modal('show');

            }
        }); 

        $(window).bind('resize', function() {
            $("#tablaDatosDetalless0").setGridWidth($(window).width());
        }).trigger('resize');
    }

    function vamosRecargaLasGrillasPorfavor(id){
        
        $("#btnLlamar_0").attr('padre', $("#G2992_C59911").val());
            var id_0 = $("#G2992_C59911").val();
            $.jgrid.gridUnload('#tablaDatosDetalless0'); //funcion Recargar 
            cargarHijos_0(id_0);
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
            
        $("#btnLlamar_0").attr('padre', $("#G2992_C59911").val());
            var id_0 = $("#G2992_C59911").val();
            $.jgrid.gridUnload('#tablaDatosDetalless0'); //funcion Recargar 
            cargarHijos_0(id_0);
            idTotal = <?php echo $_GET['user'];?>; 
        <?php } ?>
        
    });
</script>
