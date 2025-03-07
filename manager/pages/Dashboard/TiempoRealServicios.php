<?php

session_start();

if (isset($_GET["TiempoReal"])) {
  ini_set('display_errors', 'On');
  ini_set('display_errors', 1);
  require_once('../conexion.php');
  include("../../global/WSCoreClient.php");
  include("../../global/funcionesGenerales.php");
  include("./MetricasTiempoReal/getMetricas.php");
  date_default_timezone_set('America/Bogota');

  $intIdUsuario_t = $_SESSION['IDENTIFICACION'];


  // $arrJsonMetas_t   = json_decode(metricasTiempoReal($intIdUsuario_t));
  $arrMetricasMemory = json_decode(getMetricas());
  $arrTiempoReal_t = [];

  $intAvtividad_t = count($arrMetricasMemory);
  $countEstrat = 0;

  if ($intAvtividad_t > 0) {

    foreach ($arrMetricasMemory as $keyHuesped => $valueHuesped) {

      if(gettype($valueHuesped) == "object"){

        foreach ($valueHuesped->lstEstrat_t as $keyEstrat => $valueEstrat) {

          $intIdEstrat_t = $valueEstrat->objEstrat_t->ESTRAT_ConsInte__b;
          $strNombreEstrat_t = $valueEstrat->objEstrat_t->ESTRAT_Nombre____b;
          $strColorEstrat_t = $valueEstrat->objEstrat_t->ESTRAT_Color____b;

          $arrTiempoReal_t[$countEstrat] = [
            "intIdEstrat_t"     => $intIdEstrat_t,
            "strNombreEstrat_t" => $strNombreEstrat_t,
            "strColorEstrat_t"  => $strColorEstrat_t,
            "arrEstpas_t"       => null
          ];

          $arrPasos_t = [];


          // PRIMERO SACO TODAS LAS CAMPAÑAS QUE DEBO DIBUJAR
          $arrCampan = [];

          foreach ($valueEstrat->lstPasos_t as $keyPaso => $valuePaso) {
            if ($valuePaso->objEstpas_t->ESTPAS_Tipo______b == 1 || $valuePaso->objEstpas_t->ESTPAS_Tipo______b  == 6) {
              array_push($arrCampan, ["indexArr" => $keyPaso, "campanArr" => $valuePaso->objEstpas_t, "objCampan_t" => $valuePaso->objCampan_t, "lstDefinicionMetricas_t" => $valuePaso->lstDefinicionMetricas_t]);
            }
          }


          foreach ($arrCampan as $keyCampan => $valueCampan) {

            $indexCampan = $valueCampan["indexArr"];

            $valuePaso = $valueEstrat->lstPasos_t[$indexCampan];

            $intIdEstpas_t = $valueCampan["campanArr"]->ESTPAS_ConsInte__b;
            $intIdCampan_t = isset($valueCampan["campanArr"]->ESTPAS_ConsInte__CAMPAN_b) ? $valueCampan["campanArr"]->ESTPAS_ConsInte__CAMPAN_b : null;
            $strNombreCampan_t = $valueCampan["campanArr"]->ESTPAS_Comentari_b;
            $intTipoEstpas_t = $valueCampan["campanArr"]->ESTPAS_Tipo______b;
            $strTipoCamp_t = isset($valueCampan["objCampan_t"]->CAMPAN_TipoCamp__b) ? $valueCampan["objCampan_t"]->CAMPAN_TipoCamp__b : null;


            $arrPasos_t[$keyCampan] = [
              "intIdEstpas_t"     => $intIdEstpas_t,
              "intIdCampan_t"     => $intIdCampan_t,
              "strNombreCampan_t" => $strNombreCampan_t,
              "strSentido_t"      => sentido($intTipoEstpas_t),
              "strTipoCamp_t"     => $strTipoCamp_t,
              "arrMetas_t"        => null,
              "arrAgentes_t"      => null
            ];

            // Se valida si la campaña tiene marcador predictivo, si es el caso se obtiene la informacion de este
            if ($strTipoCamp_t == 7) {
              $arrInfoMarcador = getInfoMarcador($intIdEstpas_t, $intIdCampan_t);
              $strIdCola_t = $arrInfoMarcador["strIdCola_t"];

              // SE obtiene el numero de llamadas en cola
              $objColaMarcador_t = json_decode(metricaColaPredictivo($strIdCola_t));
              $intMetColaMarc_t = $objColaMarcador_t->objSerializar_t->$strIdCola_t;


              // SE obtiene el numero de llamadas en curso
              $objLlamadaCursoMarcador_t = json_decode(metricaLlamadasCursoPredictivo($intIdCampan_t));
              $intMetLlamadaCurso_t = $objLlamadaCursoMarcador_t->objSerializar_t;
              $intMetLlamadaCurso_t = count($intMetLlamadaCurso_t);
            }

            $arrOrdenMetas_t = [];
            $arrMetas_t = [];

            if ($intTipoEstpas_t == 6) {

              $arrOrdenMetas_t = [
                0 => [
                  "metas" => ["#Sin gestion", "%Sin gestion"],
                  "color" => "#F37D00", "icono" => "fa fa-users"
                ],
                1 => [
                  "metas" => ["#Gestiones", "TMO"],
                  "color" => "#F3BC00", "icono" => "fa fa-battery-three-quarters"
                ],
                2 => [
                  "metas" => ["#Contactados", "%Contactados"],
                  "color" => "#299E00", "icono" => "fa fa-hourglass-2"
                ],
                3 => [
                  "metas" => ["#Efectivos", "%Efectivos"],
                  "color" => "#009FE3", "icono" => "fa fa-thumbs-o-up"
                ]
              ];

              // ESTA PARTE DE CODIGO YA NO ES NECESARIO DEBIDO A QUE EN EL OBJETO ESPERAMOS QUE TODAS LAS METRICAS ESTEN ESTABLECIDAS
              // foreach ($arrOrdenMetas_t as $keyGrup0 => $valueGrup0) {

              //   foreach ($valueGrup0["metas"] as $keyM0 => $valueM0) {

              //     $intIter0_t = 0;

              //     foreach ($valuePaso->lstDefinicionMetricas_t as $keyMeta0 => $valueMeta0) {

              //       foreach ($valueMeta0->values as $keyStrMeta => $valueMetaNum) {

              //         if(!isset($valueMetaNum->CampanId)){
              //           if ($valueM0 == $keyStrMeta) {
              //             $intIter0_t++;
              //           }
              //         }
              //       }
              //     }

              //     if ($intIter0_t == 0) {

              //       array_push($valuePaso->lstDefinicionMetricas_t, (object)["METDEFNombreb" => $valueM0, "objMedicion_t" => (object)["METMEDValorb" => "N/A"]]);
              //     }
              //   }
              // }

              
              $i = 0;
              foreach ($arrOrdenMetas_t as $keyGrup => $valueGrup) {

                $arrMetas_t[$keyGrup]["strColor_t"] = $valueGrup["color"];
                $arrMetas_t[$keyGrup]["strIcono_t"] = $valueGrup["icono"];
                $j = 0;
                foreach ($valueGrup["metas"] as $keyM => $valueM) {

                  foreach ($valuePaso->lstDefinicionMetricas_t as $keyMeta => $valueMeta) {

                    foreach ($valueMeta->values as $keyValueMet => $valueValMet) {


                      if ($valueM == $keyValueMet) {

                        $intValor_t = "N/A";

                        if ($valueValMet !== "N/A") {
                          $intValor_t = $valueValMet;
                          
                        }

                        if (isset($valuePaso->objEstpas_t->ESTPAS_Tipo______b)) {
                          $canalMeta = $valuePaso->objEstpas_t->ESTPAS_Tipo______b;
                        } else {
                          $canalMeta = 'Sin canal';
                        }

                        $arrMetas_t[$i]["arrMetas_t"][$j] = ["strCanal" => $canalMeta, "strMeta_t" => $valueM, "strValor_t" => $intValor_t];

                        if ($strTipoCamp_t == 7 && ($canalMeta == "telefonia" || $canalMeta == 6) && $valueM == "%Sin gestion") {
                          $arrMetas_t[$i]["arrMetas_t"][$j] = ["strCanal" => $canalMeta, "strMeta_t" => "Predictivo"];
                          $arrMetas_t[$i]["arrMetas_t"][$j]["strValor_t"] = ["intMetColaMarc_t" => $intMetColaMarc_t, "intAceleracion_t" => $arrInfoMarcador["intAceleracion_t"], "intMetLlamadaCurso_t"  => $intMetLlamadaCurso_t];
                        }
                        $j++;
                      }

                      if($valueM == "%Sin gestion"){
                        $arrMetas_t[$i]["arrMetas_t"][$j] = ["strCanal" => 6, "strMeta_t" => $valueM, "strValor_t" => 0];
                      }

                    }


                  }
                }

                $i++;
              }
            } elseif ($intTipoEstpas_t == 1) {

              $arrOrdenMetas_t = [
                0 => [
                  "metas" => ["#En cola", "colaChat", "colaWhatsapp", "colaFacebook", "colaEmail", "colaWeb", "%TSF", "TSFChat", "TSFWhatsapp", "TSFFacebook", "TSFEmail", "TSFWeb"],
                  "color" => "#F37D00", "icono" => "fa fa-users"
                ],
                1 => [
                  "metas" => ["#Recibidas", "recibChat", "recibWhatsapp", "recibFacebook", "recibEmail", "recibWeb", "TMO", "TMOChat", "TMOWhatsapp", "TMOFacebook", "TMOEmail", "TMOWeb"],
                  "color" => "#F3BC00", "icono" => "fa fa-battery-three-quarters"
                ],
                2 => [
                  "metas" => ["#Contestadas", "conChat", "conWhatsapp", "conFacebook", "conEmail", "conWeb", "%Contestadas", "PORCChat", "PORCWhatsapp", "PORCFacebook", "PORCEmail", "PORCWeb"],
                  "color" => "#299E00", "icono" => "fa fa-hourglass-2"
                ],
                3 => [
                  "metas" => ["#Efectivos", "efecChat", "efecWhatsapp", "efecFacebook", "efecEmail", "efecWeb", "%Efectivos", "POREChat", "POREWhatsapp", "POREFacebook", "POREEmail", "POREWeb"],
                  "color" => "#009FE3", "icono" => "fa fa-thumbs-o-up"
                ]
              ];


              $nuevasMetas = [];


              // Se recorre las metas que se deben de sacar
              foreach ($arrOrdenMetas_t as $metasResKey => $metasResValue) {
                // Se recorre los canales de cada meta
                foreach ($metasResValue["metas"] as $metaKey => $idMeta) {

                  // Se guardan las metricas de la campaña de las metricas que ya tenemos apartadas
                  if($metaKey == 0 || $metaKey == 6 ){
                    foreach($valueCampan["lstDefinicionMetricas_t"] as $metKey => $metValue){
                      foreach ($metValue->values as $variantKey => $variantValue) {

                        if($variantKey == $idMeta){
                          $pasoCampanMedicion = (isset($metValue->CampanId)) ? $metValue->CampanId : null;
                      
                          if($pasoCampanMedicion ==  $intIdEstpas_t){
                            array_push($nuevasMetas, (object)["METDEFNombreb" => $variantKey, "objMedicion_t" => (object)["METMEDValorb" => $variantValue, "METMEDCanalb" => "telefonia"]]);
                          }
                        }
                      }

                    }

                  }else{

                    // Se recorre el objeto en busca de la meta respectiva

                    foreach ($valueEstrat->lstPasos_t as $keyPaso => $valuePasoT) {


                      foreach ($valuePasoT->lstDefinicionMetricas_t as $metKey => $metValue) {

                        foreach ($metValue->values as $variantKey => $variantValue) {


                          if (isset($valuePasoT->objEstpas_t->ESTPAS_Tipo______b) && $valuePasoT->objEstpas_t->ESTPAS_Tipo______b != "telefonia" && $valuePasoT->objEstpas_t->ESTPAS_Tipo______b != "1" && $valuePasoT->objEstpas_t->ESTPAS_Tipo______b != "6") {

                            $pasoCampanMedicion = (isset($metValue->CampanId)) ? $metValue->CampanId : null;

                            $tipoMeta = tipoMeta($idMeta);

                            if ($intIdEstpas_t == $pasoCampanMedicion && $variantKey == $tipoMeta) {
                              $strNombreVariante_t = nombreMetaVariante($variantKey);

                              switch ($valuePasoT->objEstpas_t->ESTPAS_Tipo______b) {

                                case "chat":
                                case "14":
                                  array_push($nuevasMetas, (object)["METDEFNombreb" => $strNombreVariante_t . "Chat", "objMedicion_t" => (object)["METMEDValorb" => $variantValue, "METMEDCanalb" => "chat"]]);
                                  break;
                                case "whatsapp":
                                case "15":
                                  array_push($nuevasMetas, (object)["METDEFNombreb" => $strNombreVariante_t . "Whatsapp", "objMedicion_t" => (object)["METMEDValorb" => $variantValue, "METMEDCanalb" => "whatsapp"]]);
                                  break;
                                case "facebook":
                                case "16":
                                  array_push($nuevasMetas, (object)["METDEFNombreb" => $strNombreVariante_t . "Facebook", "objMedicion_t" => (object)["METMEDValorb" => $variantValue, "METMEDCanalb" => "facebook"]]);
                                  break;
                                case "email":
                                case "17":
                                  array_push($nuevasMetas, (object)["METDEFNombreb" => $strNombreVariante_t . "Email", "objMedicion_t" => (object)["METMEDValorb" => $variantValue, "METMEDCanalb" => "email"]]);
                                  break;
                                case "webform":
                                case "19":
                                  array_push($nuevasMetas, (object)["METDEFNombreb" => $strNombreVariante_t . "Web", "objMedicion_t" => (object)["METMEDValorb" => $variantValue, "METMEDCanalb" => "webform"]]);
                                  break;
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }

              foreach ($arrOrdenMetas_t as $keyGrup => $valueGrup) {

                $arrMetas_t[$keyGrup]["strColor_t"] = $valueGrup["color"];
                $arrMetas_t[$keyGrup]["strIcono_t"] = $valueGrup["icono"];

                foreach ($valueGrup["metas"] as $keyM => $valueM) {

                  // SI EN LAS METAS FILTRADAS NO EXISTE UN VALOR QUIERE DECIR QUE NO HAY NINGUN PASO DE ESE TIPO ENCENDIDO
                  
                  if(!findObjectByMETDEFNombreb($valueM, $nuevasMetas )){

                    $arrMetas_t[$keyGrup]["arrMetas_t"][$keyM] = ["strMeta_t" => $valueM, "strValor_t" => "off"];

                  }else{

                    foreach ($nuevasMetas as $keyMeta => $valueMeta) {

                      if ($valueM == $valueMeta->METDEFNombreb) {

                        $intValor_t = ($valueMeta->objMedicion_t->METMEDValorb !== "off") ? $valueMeta->objMedicion_t->METMEDValorb : "off" ;
                        $intValor_t = ($intValor_t == "-1") ? 0 : $intValor_t;
      
                        if ($valueMeta->objMedicion_t->METMEDValorb != "off" && $valueMeta->objMedicion_t->METMEDValorb != "-1" ) {
                          $intValor_t = ceil($valueMeta->objMedicion_t->METMEDValorb);
                        }

                        $arrMetas_t[$keyGrup]["arrMetas_t"][$keyM] = ["strMeta_t" => $valueM, "strValor_t" => $intValor_t];

                      }
                    }
                  }
                }
              }
            }

            $arrAgentes_t = [];

            $arrPasos_t[$keyCampan]["arrMetas_t"] = $arrMetas_t;
          }

          $arrTiempoReal_t[$countEstrat]["arrEstpas_t"] = $arrPasos_t;
          $countEstrat++;
        }
      }
    }
  }

  echo json_encode($arrTiempoReal_t);
}


if (isset($_GET["estadosAgentes"])) {

  ini_set('display_errors', 'On');
  ini_set('display_errors', 1);
  require_once('../conexion.php');
  include("../../global/WSCoreClient.php");
  include("../../global/funcionesGenerales.php");
  date_default_timezone_set('America/Bogota');
  include("./MetricasTiempoReal/getMetricas.php");

  $arrJsonAgentes_t = json_decode(getAgents());


  // Como el objeto que se guarda tiene todos los agentes entonces filtramos los huespedes
  if($_SESSION["UNO"]){
    $arrJsonAgentes_t->objSerializar_t = array_filter( $arrJsonAgentes_t->objSerializar_t, function( $v ) { return $v->idHuesped == $_SESSION["HUESPED_CRM"] ; } );
  }else{
    $options = [];
    foreach ($_SESSION["HUESPED_LIST"] as  $value) {
      array_push($options, (int)$value);
    }

    $arrJsonAgentes_t->objSerializar_t = array_filter( $arrJsonAgentes_t->objSerializar_t, function( $v ) use ($options) { return in_array($v->idHuesped, $options); } );
  }


  $arrAgentes_t = [];

  foreach ($arrJsonAgentes_t->objSerializar_t  as $keyAgente => $valueAgente) {

    if (property_exists($valueAgente,"intIdCampanaActual_t") == false) {

      $valueAgente->intIdCampanaActual_t = null;

    }


    $strNombreEstpasActaual_t = $valueAgente->campanaActual;
    $strCanalActual_t = $valueAgente->canalActual;
    // $strDuracionEstadoTiempo_t = $valueAgente->duracionEstadoTiempo;

    $booEnConversacion = isset($valueAgente->enConversacion) ? $valueAgente->enConversacion : false;
    $strEstado_t = $valueAgente->estado;

    $strFecha1_t = new DateTime($valueAgente->strFechaHoraCambioEstado_t);
    $strFecha2_t = new DateTime("now");
    $strDiffFecha_t = $strFecha1_t->diff($strFecha2_t);
    $strFecha_t = $strDiffFecha_t->format("%H:%I:%S");
    $strDuracionEstadoTiempo_t = $strFecha_t;
    $strFechaHoraCambioEstado_t = $valueAgente->strFechaHoraCambioEstado_t;

    $intIdAgente_t = $valueAgente->idUsuario;
    $strIdentificacionAgente_t = $valueAgente->identificacionUsuario;

    $intIdCampanActual_t = null;
    if (isset($valueAgente->intIdCampanaActual_t)) {
      $intIdCampanActual_t = $valueAgente->intIdCampanaActual_t;
    }

    $lstCampanasAsignadas_t = (isset($valueAgente->lstCampanasAsignadas_t)) ? $valueAgente->lstCampanasAsignadas_t : null;

    $strNombreAgente_t = $valueAgente->nombreUsuario;
    $strPausa_t = $valueAgente->pausa;


    $strFoto_t = "assets/img/Kakashi.fw.png";
    if($valueAgente->strUSUARIFoto_t != "Kakashi.fw.png"){
      if(file_exists("/var/../Dyalogo/img_usuarios/usr".$intIdAgente_t.".jpg")){
        $strFileName = (isset($valueAgente->strUSUARIFoto_t)) ? $valueAgente->strUSUARIFoto_t : $intIdAgente_t.".jpg" ;
        $strFoto_t = "/DyalogoImagenes/usr".$strFileName;
      }
    }

    $arrAgentes_t[$keyAgente] = [
      "strNombreEstpasActaual_t"  => $strNombreEstpasActaual_t,
      "strCanalActual_t"  => $strCanalActual_t,
      "strDuracionEstadoTiempo_t" => $strDuracionEstadoTiempo_t,
      "strEstado_t"               => $strEstado_t,
      "booEnConversacion"         => $booEnConversacion,
      "strFechaHoraCambioEstadoFormat_t"  => null,
      "strFechaHoraCambioEstado_t"  => $strFechaHoraCambioEstado_t,
      "intIdAgente_t"             => $intIdAgente_t,
      "strIdentificacionAgente_t" => $strIdentificacionAgente_t,
      "intIdCampanActual_t"       => $intIdCampanActual_t,
      "strNombreAgente_t"         => $strNombreAgente_t,
      "strPausa_t"                => $strPausa_t,
      "strFoto_t"                 => $strFoto_t,
      "lstCampanasAsignadas_t"    => $lstCampanasAsignadas_t
    ];

  }

          $arrAgentes_t = unique_multidim_array($arrAgentes_t,"strIdentificacionAgente_t");



  $resJsonAgentes_t["arrAgentes_t"] = $arrAgentes_t;


  echo json_encode($resJsonAgentes_t);

}




if (isset($_GET["ConsAcordeon"])) {

  if (isset($_SESSION["ACORDEONES"])) {

    $arrAcordeones_t = json_decode($_SESSION["ACORDEONES"]);

    $strIdAcordeon_t = $_POST["strIdAcordeon_t"];

    $itEA = 0;

    foreach ($arrAcordeones_t as $EAkey => $EAvalue) {

      if ($EAvalue->strIdAcordeon_t == $strIdAcordeon_t) {

        $itEA ++;

        if ($EAvalue->strEstado_t == "none") {

          echo json_encode(["box box collapsed-box","plus","none"]);

        }else{

          echo json_encode(["box box","minus","block"]);

        }

      }

    }

    if ($itEA == 0) {

      echo json_encode(["box box","minus","block"]);

    }



  }else{

    echo json_encode(["box box","minus","block"]);

  }

}

if (isset($_GET["session2"])) {

  echo $_SESSION["ACORDEONES"];

}

function sentido($intTipoCampan_p){

  if ($intTipoCampan_p == 1) {
    return "Entrante";
  }elseif($intTipoCampan_p == 6){
    return "Saliente";
  }
  
}



function unique_multidim_array($array, $key) { 
  $temp_array = array();
  $i = 0;
  $key_array = array();

  foreach($array as $val) {
    if (!in_array($val[$key], $key_array)) {
      $key_array[$i] = $val[$key];
      $temp_array[$i] = $val;
    }
    $i++;
  }
  return $temp_array;
}

function findObjectByMETDEFNombreb($METDEFNombreb, $metasFiltradas){
  $array = $metasFiltradas;

  foreach ( $array as $element ) {
    if ( $METDEFNombreb == $element->METDEFNombreb ) return true;
  }
  return false;
}



function tipoMeta($nameMeta){
  switch($nameMeta){
    case "#En cola": case "colaChat": case "colaWhatsapp": case "colaFacebook": case "colaEmail": case "colaWeb": 
      return "#En cola";
      break;
    case "%TSF": case "TSFChat": case "TSFWhatsapp": case "TSFFacebook": case "TSFEmail": case "TSFWeb":
      return "%TSF";
      break;
    case "#Recibidas": case "recibChat": case "recibWhatsapp": case "recibFacebook": case "recibEmail": case "recibWeb": 
      return "#Recibidas";
      break;
    case "TMO": case "TMOChat": case "TMOWhatsapp": case "TMOFacebook": case "TMOEmail": case "TMOWeb":
      return "TMO";
      break;
    case "#Contestadas": case "conChat": case "conWhatsapp": case "conFacebook": case "conEmail": case "conWeb": 
      return "#Contestadas";
      break;
    case "%Contestadas": case "PORCChat": case "PORCWhatsapp": case "PORCFacebook": case "PORCEmail": case "PORCWeb":
      return "%Contestadas";
      break;
    case "#Efectivos": case "efecChat": case "efecWhatsapp": case"efecFacebook": case "efecEmail": case "efecWeb": 
      return "#Efectivos";
      break;
    case "%Efectivos": case "POREChat": case "POREWhatsapp": case "POREFacebook": case "POREEmail": case "POREWeb":
      return "%Efectivos";
      break;
  }

}
function nombreMetaVariante($nombreMeta_p){

  switch($nombreMeta_p){

    case "#En cola":
      return "cola";
      break;
    case "#Recibidas":
      return "recib";
      break;
    case "#Contestadas":
      return "con";
      break;
    case "#Efectivos":
      return "efec";
      break;

    case "%Contestadas";
      return "PORC";
      break;

    case "%Efectivos";
      return "PORE";
      break;

    case "TMO";
      return "TMO";
      break;

    case "%TSF";
      return "TSF";
      break;

  }


}

if(isset($_POST['capturafoto'])){
  require_once('../conexion.php');
  include("../../global/WSCoreClient.php");
  $objToken_t=$mysqli->query("SELECT a.token FROM dyalogo_telefonia.dy_actividad_actual a LEFT JOIN dyalogo_telefonia.dy_agentes b ON a.id_agente=b.id WHERE b.id_usuario_asociado={$_POST['id']}");
  if($objToken_t && $objToken_t->num_rows>0){
      $objToken_t=$objToken_t->fetch_object();
      $strToken_t=$objToken_t->token;
      $foto=capturarFoto($strToken_t);

      echo $foto;
  }        
}

if(isset($_POST['consultafoto'])){
  require_once('../conexion.php');
  $strFoto_t="SELECT foto_actual FROM dyalogo_general.actividad_actual WHERE id_usuario={$_POST['id']};";
  $strFoto_t=$mysqli->query($strFoto_t);
  if($strFoto_t && $strFoto_t->num_rows>0){
      $strFoto_t=$strFoto_t->fetch_object();
      $Foto_t=$strFoto_t->foto_actual;

      echo $Foto_t;
  }        
}

if(isset($_POST['intrusionChat'])){
  $agente=isset($_POST['id']) ? $_POST['id'] : false;
  if($agente){
      require_once('../conexion.php');
      $sql=$mysqli->query("select a.fecha_hora,a.mensaje,a.agente,b.nombre_usuario,c.identificacion_usuario from dyalogo_canales_electronicos.dy_chat_historico_conversaciones a left join dyalogo_general.actividad_actual b on a.id_chat_entrante=b.id_comunicacion left join dyalogo_canales_electronicos.dy_chat_entrantes c on a.id_chat_entrante=c.id where id_usuario={$agente}");
      if($sql && $sql->num_rows>0){
          $chat=array();
          $i=0;
          while($mensaje = $sql->fetch_object()){
              $chat[$i]['mensaje']=$mensaje->mensaje;
              $chat[$i]['agente']=$mensaje->agente;
              $chat[$i]['fecha_hora']=$mensaje->fecha_hora;
              $chat[$i]['nombre_usuario']=$mensaje->nombre_usuario;
              $chat[$i]['identificacion_usuario']=$mensaje->identificacion_usuario;
              $chat['conteo']=$i;
              $i++;
          }
          echo json_encode(array('estado'=>'ok', 'mensaje'=>$chat));
      }else{
          echo json_encode(array('estado'=>'error', 'mensaje'=>'No se identifico la comunicación'));
      }
  }else{
      echo json_encode(array('estado'=>'error', 'mensaje'=>'No se identifico al agente'));
  }
}

if(isset($_POST['getidComunicacion'])){
  require_once('../conexion.php');

  $response=array();
  $response['idC']='';
  $response['guion']='';
  $response['mensaje']='No se pudo obtener la información de la comunicacion';
  $response['estado']='error';
  $strIdComunicacion="SELECT id_comunicacion FROM dyalogo_general.actividad_actual WHERE id_usuario={$_POST['user']}";
  $strIdComunicacion=$mysqli->query($strIdComunicacion);
  if($strIdComunicacion && $strIdComunicacion->num_rows>0){
      $strIdComunicacion=$strIdComunicacion->fetch_object();
      $strIdComunicacion=$strIdComunicacion->id_comunicacion;
      $response['idC']=$strIdComunicacion;

      $intGuion="SELECT CAMPAN_ConsInte__GUION__Gui_b FROM DYALOGOCRM_SISTEMA.CAMPAN WHERE CAMPAN_ConsInte__b={$_POST['campan']}";
      $intGuion=$mysqli->query($intGuion);
      if($intGuion && $intGuion->num_rows>0){
          $intGuion=$intGuion->fetch_object();
          $intGuion=$intGuion->CAMPAN_ConsInte__GUION__Gui_b;
          $response['guion']=$intGuion;
          
          $intIdRegistro="SELECT G{$intGuion}_ConsInte__b AS idRegistro FROM DYALOGOCRM_WEB.G{$intGuion} WHERE G{$intGuion}_IdLlamada='{$strIdComunicacion}'";
          $intIdRegistro=$mysqli->query($intIdRegistro);
          if($intIdRegistro && $intIdRegistro->num_rows>0){
            $intIdRegistro=$intIdRegistro->fetch_object();
            $intIdRegistro=$intIdRegistro->idRegistro;

            $response['idRegistro']=$intIdRegistro;
            $response['estado']='ok';
          }else{
            $response['estado']='error';
            $response['mensaje']='Aun no existe la gestion para esta llamada';
          }
      }
  }
  
  echo json_encode($response);
}

// Se realiza la actualizacion de la aceleracion en la campaña enviada
if(isset($_POST['updateAceleracion'])){
  require_once('../conexion.php');

  $intIdCampan_t =$_POST["idCampan"];
  $intAceleracion_t =$_POST["intAceleracion"];


  $strSqlAceleration = "UPDATE {$BaseDatos_systema}.CAMPAN SET CAMPAN_Aceleracion_b = '{$intAceleracion_t}' WHERE CAMPAN_ConsInte__b = {$intIdCampan_t}";
  if($mysqli->query($strSqlAceleration) == true){
    echo json_encode(["Exito" => true, "Message" => ""]);
  }else{
    echo json_encode(["Exito" => false, "Message" => $mysqli->error]);
  }

}








/**
 *BGCR - Esta funcion nos trae la innformacion del marcador
 *@param intEstpas_t = id del paso, intCampan_p = id de la campaña
 *@return array [idMarcardor, idCola, Aceleracion]
 */


function getInfoMarcador( int $intEstpas_t ,int $intCampan_p):array {
  global $mysqli;
  global $BaseDatos_telefonia;


  $sqlMarcador_t = "select M.id, M.valor_accion_por_defecto AS cola, C.CAMPAN_Aceleracion_b AS aceleracion  from {$BaseDatos_telefonia}.dy_marcador_campanas M JOIN DYALOGOCRM_SISTEMA.CAMPAN C ON C.CAMPAN_ConsInte__b = M.id_campana_crm where M.nombre = 'MarAuto_P{$intEstpas_t}_CCRM_{$intCampan_p}' ";
          
  $resMarcador_t = $mysqli->query($sqlMarcador_t);

  if($resMarcador_t && $resMarcador_t->num_rows > 0){
      $resMarcador_t = $resMarcador_t->fetch_object();

      $intIdMarcador_t = $resMarcador_t->id;
      $strIdCola_t = $resMarcador_t->cola;
      $intAceleracion_t = $resMarcador_t->aceleracion;
  }

  return [ "intIdMarcador_t" => $intIdMarcador_t, "strIdCola_t" => $strIdCola_t, "intAceleracion_t" => $intAceleracion_t];

}



?>