<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../../conexion.php");
    include(__DIR__."/../../funciones.php");
    date_default_timezone_set('America/Bogota');


    if(isset($_GET["traerGestionYLink"])){// JDBD - Devolver link de la llamada
        $Con = "SELECT G2992_LinkContenido as url FROM ".$BaseDatos.".G2992 WHERE G2992_ConsInte__b = ".$_POST["idLoco"];
        $result = $mysqli->query($Con);
        $url = $result->fetch_array();

        echo $url["url"];
    }  

    if(isset($_GET["traerGestionYLinkTR"])){// JDBD - Devolver link de la llamada
        $Con = "SELECT G2992_IdLlamada as url FROM ".$BaseDatos.".G2992 WHERE G2992_ConsInte__b = ".$_POST["idLoco"];
        $result = $mysqli->query($Con);
        $url = $result->fetch_array();

        echo $url["url"];
    }

    if (isset($_GET["actualizarID"])) {
        // code...
        $Con = "UPDATE ".$BaseDatos.".G2992 SET G2992_C66544 =".$_POST["idLoco"]." WHERE G2992_ConsInte__b = ".$_POST["idLoco"];
        echo $Con;
        $resultado = false;

        if($mysqli->query($Con) === TRUE) {
            
            echo $resultado = true;
        }else{
            echo $resultado;
        }
    }

    // JDBD - se envia calificacion de la gestion al agente que la creo.


    if (isset($_GET["EnviarCalificacion"])) {
        $SC = $_POST["IdGuion"];
        $G = $_POST["IdGestion"];

        $P = "SELECT GUION__ConsInte__PREGUN_Pri_b AS P, GUION__ConsInte__PREGUN_Sec_b AS S FROM ".$BaseDatos_systema.". GUION_ 
              WHERE GUION__ConsInte__b = 2992;";
        $P = $mysqli->query($P);
        $P = $P->fetch_array();

        if ($_POST['origenCalidad'] == "intrusion") {
            $fechaAuditado = "now()";
        }else{
            $fechaAuditado = "'".$_POST['G2992_C61153']."'";
        }

        $upGCE = "UPDATE ".$BaseDatos.".G2992
                    SET G2992_C61150 = -201
                    ,G2992_C59955 = ".$_POST["G2992_C59955"]."
                    ,G2992_C59988 = ".$_POST["G2992_C59988"]."
                    ,G2992_C59990 = ".$_POST["G2992_C59990"]."
                    ,G2992_C59992 = ".$_POST["G2992_C59992"]."
                    ,G2992_C59994 = ".$_POST["G2992_C59994"]."
                    ,G2992_C59996 = ".$_POST["G2992_C59996"]."
                    ,G2992_C59998 = ".$_POST["G2992_C59998"]."
                    ,G2992_C60000 = ".$_POST["G2992_C60000"]."
                    ,G2992_C60002 = ".$_POST["G2992_C60002"]."
                    ,G2992_C60004 = ".$_POST["G2992_C60004"]."
                    ,G2992_C60006 = ".$_POST["G2992_C60006"]."
                    ,G2992_C66545 = '".$_POST["G2992_C66545"]."'
                    ,G2992_C61149 = ".$_POST["G2992_C61149"]."
                    ,G2992_C61151 = '".$_POST["G2992_C61151"]."'
                    ,G2992_C61153 = ".$fechaAuditado." 
                    ,G2992_C61154 = '".$_POST["G2992_C61154"]."' 
                   WHERE G2992_ConsInte__b = ".$_POST["IdGestion"]; 
                            
        echo $upGCE;
        $upGCE = $mysqli->query($upGCE);
        
        $gestion = "SELECT * 
                    FROM ".$BaseDatos.".G2992 
                    WHERE G2992_ConsInte__b = ".$_POST["IdGestion"];
        $gestion = $mysqli->query($gestion);
        
        $gestion = $gestion->fetch_array();

        if (is_null($gestion["G2992_C61149"]) || $gestion["G2992_C61149"] == "") {
            $valCal = "NULL";
        }else{
            $valCal = $gestion["G2992_C61149"];
        }

        if (is_null($gestion["G2992_C61151"]) || $gestion["G2992_C61151"] == "") {
            $valCom = "NULL";
        }else{
            $valCom = $gestion["G2992_C61151"];
        }

        $histCalidad = "INSERT INTO ".$BaseDatos_systema.".CALHIS 
                        (CALHIS_ConsInte__GUION__b,CALHIS_IdGestion_b,CALHIS_FechaGestion_b,CALHIS_ConsInte__USUARI_Age_b,CALHIS_DatoPrincipalScript_b,CALHIS_DatoSecundarioScript_b,CALHIS_FechaEvaluacion_b,CALHIS_ConsInte__USUARI_Cal_b,CALHIS_Calificacion_b,CALHIS_ComentCalidad_b)
                        VALUES
                        (".$_POST["IdGuion"].",".$_POST["IdGestion"].",'".$gestion["G2992_FechaInsercion"]."',".$gestion["G2992_Usuario"].",'".$gestion["G2992_C".$P["P"]]."','".$gestion["G2992_C".$P["S"]]."','".date('Y-m-d H:i:s')."',".$_POST["IdCal"].",".$valCal.",'".$valCom."')";

        if ($mysqli->query($histCalidad)) {
            $H = $mysqli->insert_id;

            $URL = "https://onuris.dyalogo.cloud/QA/index.php?SC=".$SC."&G=".$G."&H=".$H;
        }else{
            $URL="";
        }

        $HTML = "<!DOCTYPE html><html><head><title>HTML</title></head><body><div><h3>LLANOGAS_ SAC_IN AÃ±adir un comentario : </h3><a href = '".$URL."'>".$URL."</a></div><div>";

        //JDBD - obtenemos las secciones del formulario.
        $Secciones = "SELECT SECCIO_ConsInte__b AS id, 
                             SECCIO_TipoSecc__b AS tipo, 
                             SECCIO_Nombre____b AS nom 
                      FROM ".$BaseDatos_systema.".SECCIO 
                      WHERE SECCIO_ConsInte__GUION__b = 2992 
                      AND SECCIO_TipoSecc__b <> 4 ORDER BY FIELD(SECCIO_TipoSecc__b,2) DESC, 
                               SECCIO_ConsInte__b DESC;";

        $email = "SELECT USUARI_Correo___b AS email
                  FROM ".$BaseDatos_systema.".USUARI 
                  WHERE USUARI_ConsInte__b = ".$gestion["G2992_Usuario"];
        $email = $mysqli->query($email);
        $email = $email->fetch_array();

        $Secciones = $mysqli->query($Secciones);

        $itCal = 0;
        $itNor = 0;

        while ($s = $Secciones->fetch_object()) {
            if (($s->tipo == 2) && ($s->id == "9171")) {    
                if ($itCal == 0) {
                    $HTML .= "<div><h1 style='color: #2D0080'>CALIFICACION DE LA LLAMADA CAMPANA LLANOGAS SAC IN</h1><div>";
                }

                $HTML .= "<em style='color: #11CFFF'><h3>".$s->nom."</h3></em>";

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, 
                                    PREGUN_ConsInte__b AS C,
                                    PREGUN_Texto_____b AS nom,
                                    PREGUN_Tipo______b AS tipo
                             FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$s->id." ORDER BY PREGUN_ConsInte__b;";

                $columnas = $mysqli->query($columnas);

                while ($c = $columnas->fetch_object()) {
                    if (isset($gestion["G".$c->G."_C".$c->C])) {
                        $HTML .= "<p><strong>".$c->nom." : </strong>".traductor($gestion["G".$c->G."_C".$c->C],$c->tipo)."</p>"; 
                    }
                }

                if ($itCal == 0) {
                    $HTML .= "</div></div>";
                }
                $itCal ++;
            }/*else{
                if ($itNor == 0) {
                    $HTML .= "<h1 style='color: #2D0080'>INFORMACION DE LA GESTION DE LLAMADA</h1>";
                }

                $HTML .= "<div><em><h3 style='color: #11CFFF'>".$s->nom."</h3></em>";

                $columnas = "SELECT PREGUN_ConsInte__GUION__b AS G, 
                                    PREGUN_ConsInte__b AS C,
                                    PREGUN_Texto_____b AS nom,
                                    PREGUN_Tipo______b AS tipo
                             FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__SECCIO_b = ".$s->id." ORDER BY PREGUN_ConsInte__b;";

                $columnas = $mysqli->query($columnas);

                while ($c = $columnas->fetch_object()) {
                    if (isset($gestion["G".$c->G."_C".$c->C])) {
                         $HTML .= "<p><strong>".$c->nom." : </strong>".traductor($gestion["G".$c->G."_C".$c->C],$c->tipo)."</p>";  
                    }
                    
                }

                $HTML .= "</div>";

                $itNor ++;
            }*/
        }

        $HTML .= "</div></body></html>";
        
                $data = array(  
                    "strUsuario_t"              =>  "crm",
                    "strToken_t"                =>  "D43dasd321",
                    "strIdCfg_t"                =>  "18",
                    "strTo_t"                   =>  $email["email"],
                    "strCC_t"                   =>  $_POST["Correos"],
                    "strCCO_t"                  =>  null,
                    "strSubject_t"              =>  "Calificacion Llamada LLANOGAS_ SAC_IN #". $gestion["G2992_ConsInte__b"],
                    "strMessage_t"              =>  $HTML,
                    "strListaAdjuntos_t"        =>  null
                ); 

                $data_string = json_encode($data); 

                $ch = curl_init("localhost:8080/dyalogocore/api/ce/correo/sendmailservice");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
                        "Accept: application/json",                                                               
                        "Content-Type: application/json",                                                  
                        "Content-Length: ".strlen($data_string)
                    )                                                                      
                ); 
                $respuesta = curl_exec ($ch);
                $error = curl_error($ch);
                if (isset($respuesta)) {
                    echo json_encode($respuesta);
                }else{
                    echo json_encode($error);
                }
                curl_close ($ch);


        
    }