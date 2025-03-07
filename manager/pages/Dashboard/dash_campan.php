<?php
        session_start();
        include ('../conexion.php');
        // include ('../../global/WSCoreClient.php');
        include ('../../global/funcionesGenerales.php');
        include("./MetricasTiempoReal/getMetricas.php");
        date_default_timezone_set('America/Bogota');
        if(isset($_SESSION['HUESPED'])){

            $intIdUsuario_t = $_SESSION['IDENTIFICACION'];
            
            if (isset($_SESSION['ORDEN_CAMPAN'])) {
                $strOrderEstrat_t = $_SESSION['ORDEN_CAMPAN'];
            }else{
                $strOrderEstrat_t = "AD";
            }

            $intCantEstrat_t = 3;
            $intCols_t = 4;

            if(isset($_SESSION['TAMANHO_CAMPAN'])){
                $intCantEstrat_t = $_SESSION['TAMANHO_CAMPAN'];
                switch ($intCantEstrat_t) {
                    case 5:
                        $intCols_t =6; 
                        break;
                    case 6:
                        $intCols_t =4; 
                        break;
                    case 9:
                        $intCols_t =3; 
                        break;
                    case 0:
                        $intCols_t =12; 
                        break;
                    case 12:
                        $intCols_t =3; 
                        break;
                }
            }

            $objMetricas_t = json_decode(getMetricas());
            //JDBD - recorremos cada una de las estrategias.
            $arrHTML_t = []; 
            $arrHTML_t [0]= ""; 
            $intCantidadEstrat_t = count($objMetricas_t);
            foreach ($objMetricas_t as $keyHuesped => $huesped) {

                if(gettype($huesped) == "object"){

                    foreach ($huesped->lstEstrat_t as $key => $estrategia) {
                        //JDBD - aca se crean los colores de las estrategias para las celdas.
                        $strNombreEstrat = $estrategia->objEstrat_t->ESTRAT_Nombre____b;
                        $strColorEstrat_t = $estrategia->objEstrat_t->ESTRAT_Color____b;
                        $strBorTopSol_t = "border-top:solid ".$strColorEstrat_t." 1.0pt;";
                        $strBorLefSol_t = "border-left:solid ".$strColorEstrat_t." 1.0pt;";
                        $strBorRigSol_t = "border-right:solid ".$strColorEstrat_t." 1.0pt;";
                        $strMsoBorTop_t = "mso-border-top-alt:solid ".$strColorEstrat_t." .5pt;";
                        $strMsoBorLef_t = "mso-border-left-alt:solid ".$strColorEstrat_t." .5pt;";
                        $strBorBotSol_t = "border-bottom:solid ".$strColorEstrat_t." 1.0pt;";
                        $strMsoBorBot_t = "mso-border-bottom-alt:solid ".$strColorEstrat_t." .5pt;";
                        $strMsoBorAlt_t = "mso-border-alt:solid ".$strColorEstrat_t." .5pt;";
                        $strMsoBorRight_t = "mso-border-right-alt:solid ".$strColorEstrat_t." .5pt;";
                        $strBackGround_t = "background:".$strColorEstrat_t.";";
                        $strColor_t = "color:".$strColorEstrat_t.";";
                        $strBorSol_t = "border:solid ".$strColorEstrat_t." 1.0pt;";
                        $strSol_t = "solid ".$strColorEstrat_t." .5pt;";

                            foreach ($estrategia->lstPasos_t as $paso) {

                                if($paso->objEstpas_t->ESTPAS_Tipo______b == 1 || $paso->objEstpas_t->ESTPAS_Tipo______b == 6){
                                    $intIdPaso_t = $paso->objEstpas_t->ESTPAS_ConsInte__b;
                                    $intTipoPaso_t = $paso->objEstpas_t->ESTPAS_Tipo______b;

                                    $strHTMLHover_t = "";
                                    $strCLass_t = "";

                                    if ($intTipoPaso_t == 6) {

                                    $strHTMLHover_t = "cursor: pointer;";
                                    $strCLass_t = "visorLlamadas";
                                    }
                                    //JDBD - le ponemos un valor al div para poder ordenarlo

                                    if (count($paso->lstDefinicionMetricas_t) != 0) {

                                        $attr="valor = '-1'";
                                        foreach ($paso->lstDefinicionMetricas_t as $MetricasPaso) {
                                            foreach ($MetricasPaso->values as $keyValMet => $valueMet) {
                                                if($MetricasPaso->CampanId == $intIdPaso_t){
                                                    if ($keyValMet == "#En cola") {
                                                        $attr="valor = '".$valueMet."'";
                                                    }elseif ($keyValMet == "#Sin gestion"){
                                                        $attr="valor = '".$valueMet."'";
                                                    }
                                                }
                                            }

                                        }

                                        //JDBD - recorremos cada paso de la estrategia.
                                        $arrHTML_t[0] .= "<div idpaso=".$intIdPaso_t." ".$attr." class='col-md-".$intCols_t." col-xs-12 col-lg-".$intCols_t." estrategias ".$strCLass_t."' name = '".$strNombreEstrat."' style = 'margin: 0; padding: 1px; ".$strHTMLHover_t."' width = 50%><table class='tableEstrat ' border=1 cellspacing=0 cellpadding=0 width=100%>";

                                            $strNombrePaso_t = $paso->objEstpas_t->ESTPAS_Comentari_b;
                                            

                                            $arrHTML_t[0] .= "<tr><td width=236 colspan=3 valign=top class='tdNomPaso' style='".$strBorTopSol_t.$strBorLefSol_t.$strMsoBorTop_t.$strMsoBorLef_t."'><p class='pNomPaso'><b><span class='sPaso' style='".$strColor_t."font-size:13px;'>".substr($strNombrePaso_t,0,20)."</span></b></p></td>";
                                            //JDBD - validamos el tipo de paso para organizar las metas adecuadamente.
                                            if ($intTipoPaso_t == 1) {
                                                $arrPasos_t = ['#En cola','%TSF','TMO','%Contestadas'];
                                            }elseif ($intTipoPaso_t == 6) {
                                                $arrPasos_t = ['#Sin gestion','#Gestiones','%Contactados','%Efectivos'];
                                            }


                                            // $arrMetEx_t = [];
                                            // foreach ($paso->lstDefinicionMetricas_t as $MetricasPaso) {
                                            //     foreach ($MetricasPaso->values as $keyValMet => $valueMet) {
                                            //         if($MetricasPaso->CampanId  == $intIdPaso_t){
                                            //             $arrMetEx_t[]= $keyValMet;
                                            //         }
                                            //     }

                                            // }

                                            // $arrMetFal_t = array_diff($arrPasos_t,$arrMetEx_t);
                                            // foreach ($arrMetFal_t as $value) {
                                            //     array_push($paso->lstDefinicionMetricas_t, (object)["METDEFNombreb"=>$value,"objMedicion_t"=>(object)["METMEDValorb"=>"N.D."]]);
                                            // }


                                        //JDBD recorremos arrPasos_t para empezar a organizar y mostrar las metas. 
                                        for ($i=0; $i < 4; $i++) { 
                                            //JDBD - recorremos las metas del JSON.
                                            foreach ($paso->lstDefinicionMetricas_t as $MetricasPaso) {

                                                foreach ($MetricasPaso->values as $key => $meta) {

                                                    if($paso->objEstpas_t->ESTPAS_Tipo______b === 'telefonia' || $paso->objEstpas_t->ESTPAS_Tipo______b == "1" || $paso->objEstpas_t->ESTPAS_Tipo______b == "6"){
                                                        
                                                        $strNombreMeta_t = $key;
                                                        $intMetValor_t = $meta;
                        
                                                        //JDBD - comparamos los nombres para mostrar las metas en orden.
                                                        if ($strNombreMeta_t == $arrPasos_t[$i] && $MetricasPaso->CampanId == $intIdPaso_t) {
                                                            //JDBD - esta condicion nos ayuda a cuadrar el rowspan.
                                                            if ($i == 0) {
                        
                                                                $strStyleTd = " style='".$strBorSol_t.$strMsoBorTop_t.$strMsoBorRight_t.$strSol_t."width:83.35pt;border-left:none;mso-border-bottom-alt:background:white;mso-background-themecolor:background1;padding:0cm 1.0pt 0cm 1.0pt;'";
                        
                                                            }elseif ($i == 3 || $i == 2) {
                        
                                                                $strStyleTd = " style='".$strBorBotSol_t.$strMsoBorBot_t."width:2.0cm;border:none;background:white;mso-background-themecolor:background1;padding:0cm 1.0pt 0cm 1.0pt'";
                        
                                                            }else{
                        
                                                                $strStyleTd = " style='".$strBorLefSol_t.$strBorBotSol_t.$strMsoBorLef_t.$strMsoBorBot_t."width:63.5pt;border-top:none;border-right:none;background:white;mso-background-themecolor:background1;padding:0cm 1.0pt 0cm 1.0pt'";
                        
                                                            }
                                                            //JDBD - condicional para aplicar estilos unicos al td que combina filas.
                                                            if ($i == 0) {
                        
                                                                if ($intTipoPaso_t == 1) {
                                                                $strTamCifra_t = "style='font-size:20px;'";
                                                                $padding = "padding: 5.5px;";
                                                                }else{
                                                                $strTamCifra_t = "style='font-size:15px;padding: 10px, 0, 0, 0;'";
                                                                $padding = "padding: 8.4px;";                                            
                                                                }
                        
                                                                $arrHTML_t[0] .= "<td width=111 rowspan=2 valign=top ".$strStyleTd."><p style='margin-bottom:0cm;margin-bottom:.0001pt;text-align:center;line-height:normal;".$padding."' ><span class='sMetaPrincipal'".$strTamCifra_t." id='sMetaPrincipal_".$paso->objCampan_t->CAMPAN_ConsInte__b."'>".$intMetValor_t."</span></p><p class='pNomMeta' style='font-size:5px;'><span class='sNomMeta' style='font-size:9px;'>".$strNombreMeta_t."</span></p></td></tr><tr>";
                        
                                                            }else{
                                                                if ($strNombreMeta_t == '%TSF') {
                                                                if ($intMetValor_t != 'N.D.') {
                                                                    $TSF = round($intMetValor_t, 2,PHP_ROUND_HALF_EVEN);
                                                                }else{
                                                                    $TSF = $intMetValor_t;
                                                                }
                                                                $arrHTML_t[0] .= "<td width=85 valign=top ".$strStyleTd."><p class='pCifra' ><span style='font-size:10px;'>".$TSF."</span></p><p class='pNomMeta' style='font-size:5px;'><span class='sNomMeta'style='font-size:9px;'>".$strNombreMeta_t."</span></p></td>";
                                                                }else{
                                                                $arrHTML_t[0] .= "<td width=85 valign=top ".$strStyleTd."><p class='pCifra' ><span style='font-size:10px;'>".round($intMetValor_t, 2,PHP_ROUND_HALF_EVEN)."</span></p><p class='pNomMeta' style='font-size:5px;'><span class='sNomMeta'style='font-size:9px;'>".$strNombreMeta_t."</span></p></td>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } 
                                        } 

                                        $strHTMLLi_t = "";
                                        if ($intTipoPaso_t == 6) {
                                            $strHTMLLi_t = "<i class='fa fa-bar-chart-o'></i>";
                                        }

                                        $arrHTML_t[0] .= "</tr>";                        
                                        $arrHTML_t[0] .= "<tr><td colspan=4 valign=top class='tdEstrat' style='".$strBorSol_t.$strMsoBorTop_t.$strMsoBorAlt_t.$strBackGround_t."'><p class='pEstrat sNomEstrat' style='height:12px;font-size:10Px;'><strong>".substr($strNombreEstrat,0,25)."<span style='float:right;'>".$strHTMLLi_t." ".nombreTipoPaso($intTipoPaso_t)."</span></strong></p></td></tr></table></div>";
                                    }
                                }

                            }

                    }
                }
            }

            $arrHTML_t[1] = $strOrderEstrat_t;
            $arrHTML_t[2] = $intCantidadEstrat_t;

            echo json_encode($arrHTML_t);
        }
?>
