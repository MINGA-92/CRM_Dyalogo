<?php

use Illuminate\Foundation\Mix;
use Illuminate\Support\Arr;

    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    require_once ("../../../xlsxwriter/xlsxwriter.class.php");
    include(__DIR__."../../../../pages/conexion.php");
    require_once('../../../../helpers/parameters.php');
    require_once('../../../global/GeneradorDeFlechas.php');
    require_once('../../../global/GeneradorDeFlechas.php');

    function NombreParaCamposDeExcell($strNombre_p)
    {   
        $strNombre_t = trim($strNombre_p);

        $arrBuscar_t = ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë','í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î','ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','ñ', 'Ñ', 'ç', 'Ç'];

        $arrCambiar_t = ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A','e', 'e', 'e', 'e', 'E', 'E', 'E', 'E','i', 'i', 'i', 'i', 'I', 'I', 'I', 'I','o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','u', 'u', 'u', 'u', 'U', 'U', 'U', 'U','n', 'N', 'c', 'C'];

        $strNombre_t = str_replace($arrBuscar_t, $arrCambiar_t, $strNombre_t);
        $strNombre_t = preg_replace("/[\s]/", "_", $strNombre_t);
        $strNombre_t = preg_replace("/[^A-Za-z0-9_]/", "", $strNombre_t);   
        for ($i=0; $i < 10; $i++) {
            $strNombre_t = str_replace("__", "_", $strNombre_t);
        }
        $strNombre_t = substr($strNombre_t,0,20);

        return $strNombre_t; 
    }

    function addPasoFlugograma($estrategia,$campan,$flecha=null,$idCallback=null){
        global $mysqli;
        global $BaseDatos_systema;

        if(is_null($flecha)){
            //VALIDAR QUE LA CAMPAÑA NO TENGA UNA CAMPAÑA ASIGNADA PARA EL CALLBACK
            $sql=$mysqli->query("SELECT CAMPAN_CallBack_ConsInte__b FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b={$campan}");
            if($sql && $sql->num_rows === 1){
                $sql=$sql->fetch_object();
                if(is_null($sql->CAMPAN_CallBack_ConsInte__b) || $sql->CAMPAN_CallBack_ConsInte__b == '0'){
                        $data='{ "class": "go.GraphLinksModel",
                  "linkFromPortIdProperty": "fromPort",
                  "linkToPortIdProperty": "toPort",
                  "nodeDataArray": ['."\n";
                
                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$estrategia."'";
                        $res_Pasos = $mysqli->query($Lsql);
                        $i = 0;
                        $separador = '';
                        while ($keu = $res_Pasos->fetch_object()) {
                            if($i != 0){
                                $separador = ','."\n";
                            }
                            $data.=$separador."{\"category\":\"".$keu->ESTPAS_Nombre__b."\", \"nombrePaso\":\"".$keu->ESTPAS_Comentari_b."\", \"active\": ".$keu->ESTPAS_activo.", \"tipoPaso\": ".$keu->ESTPAS_Tipo______b.", \"figure\":\"Circle\", \"key\": ".$keu->ESTPAS_ConsInte__b.", \"loc\":\"".$keu->ESTPAS_Loc______b."\"}";
                            $i++;
                        }
                            $data.=',
                {"category":"salPhone", "text":"Llamadas salientes", "tipoPaso":6, "figure":"Circle", "key":-12, "loc":"-448.21875 91.33332824707031"}'."\n";
                
                        $data.=' ],
                  "linkDataArray": ['."\n";
                
                        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTCON WHERE md5(concat('".clave_get."', ESTCON_ConsInte__ESTRAT_b)) = '".$estrategia."'";
                        $res_Pasos = $mysqli->query($Lsql);
                        $i = 0;
                        $separador = '';
                        while ($keu = $res_Pasos->fetch_object()) {
                            if($i != 0){
                                $separador = ','."\n";
                            }
                            if(!is_null($keu->ESTCON_Coordenadas_b)){
                                $data.=$separador."{\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"points\":".$keu->ESTCON_Coordenadas_b.", \"text\":\"".str_replace("\n", '', $keu->ESTCON_Comentari_b)."\", \"active\":".$keu->ESTCON_Activo_b." }";
                            }else{
                                 $data.=$separador."{\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"active\":".$keu->ESTCON_Activo_b." }";
                            }
                            
                            $i++;
                        }
                
                        $data.="\n".' ]}';
        
                        $data=json_encode(array("estado"=>true,"addCallback"=>true,"data"=>json_decode($data)));
                }else{
                    $data=json_encode(array("estado"=>true,"addCallback"=>false));
                }
    
            }else{
                $data=json_encode(array("estado"=>false,"error"=>'No se identifico a la estrategia'));
            }
        }else{
            //CREAR CONEXIÓN ENTRE CAMPAÑAS
            $data='{ "class": "go.GraphLinksModel",
                "linkFromPortIdProperty": "fromPort",
                "linkToPortIdProperty": "toPort",
                "nodeDataArray": ['."\n";
            
                    $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTPAS WHERE  ESTPAS_ConsInte__ESTRAT_b= '".$estrategia."'";
                    $res_Pasos = $mysqli->query($Lsql);
                    $i = 0;
                    $separador = '';
                    while ($keu = $res_Pasos->fetch_object()) {
                        if($i != 0){
                            $separador = ','."\n";
                        }
                        $data.=$separador."{\"category\":\"".$keu->ESTPAS_Nombre__b."\", \"nombrePaso\":\"".$keu->ESTPAS_Comentari_b."\", \"active\": ".$keu->ESTPAS_activo.", \"tipoPaso\": ".$keu->ESTPAS_Tipo______b.", \"figure\":\"Circle\", \"key\": ".$keu->ESTPAS_ConsInte__b.", \"loc\":\"".$keu->ESTPAS_Loc______b."\"}";
                        $i++;
                    }
            
                    $data.=' ],
                "linkDataArray": ['."\n";
            
                    $Lsql = "SELECT * FROM ".$BaseDatos_systema.".ESTCON WHERE ESTCON_ConsInte__ESTRAT_b= '".$estrategia."'";
                    $res_Pasos = $mysqli->query($Lsql);
                    $i = 0;
                    $separador = '';
                    while ($keu = $res_Pasos->fetch_object()) {
                        if($i != 0){
                            $separador = ','."\n";
                        }
                        if(!is_null($keu->ESTCON_Coordenadas_b)){
                            $data.=$separador."{\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"points\":".$keu->ESTCON_Coordenadas_b.", \"text\":\"".str_replace("\n", '', $keu->ESTCON_Comentari_b)."\", \"active\":".$keu->ESTCON_Activo_b." }";
                        }else{
                            $data.=$separador."{\"from\":".$keu->ESTCON_ConsInte__ESTPAS_Des_b.", \"to\":".$keu->ESTCON_ConsInte__ESTPAS_Has_b.", \"fromPort\":\"".$keu->ESTCON_FromPort_b."\", \"toPort\":\"".$keu->ESTCON_ToPort_b."\", \"visible\":true, \"active\":".$keu->ESTCON_Activo_b." }";
                        }
                        
                        $i++;
                    }
                    $flecha = new GeneradorDeFlechas;
                    $from=$flecha->generarPuerto($campan,$idCallback,'flujograma');
                    $to=$flecha->generarPuerto($idCallback,$campan,'flujograma');
                    $data.=",{\"from\":".$campan.", \"to\":".$idCallback.",\"fromPort\":\"{$from}\", \"toPort\":\"{$to}\", \"visible\":true, \"active\":0, \"colors\":[ \"black\" ]}";
                    $data.="\n".' ]}';

                    $data=json_encode(array("estado"=>true,"addFlecha"=>true,"data"=>json_decode($data)));
        }

       return $data;     
    }

    function getInfoCampan(int $campan):array
    {
        global $mysqli;
        global $BaseDatos_systema;
        
        $sql=$mysqli->query("SELECT CAMPAN_ConsInte__GUION__Pob_b AS bd, CAMPAN_ConsInte__MUESTR_b AS muestra, CAMPAN_IdCamCbx__b AS idCbx FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b={$campan}");
        if($sql && $sql->num_rows==1){
            return $sql->fetch_array();
        }
        return array();
    }

    function getAgentesCampan(int $campanCbx):array
    {
        global $mysqli;
        global $BaseDatos_telefonia;
        global $BaseDatos_systema;

        $sql=$mysqli->query("SELECT A.id_agente AS dy_agente, B.id_usuario_asociado AS dy_usuario, C.USUARI_ConsInte__b AS usuari FROM {$BaseDatos_telefonia}.dy_campanas_agentes A LEFT JOIN {$BaseDatos_telefonia}.dy_agentes B ON A.id_agente=B.id LEFT JOIN {$BaseDatos_systema}.USUARI C ON B.id_usuario_asociado=C.USUARI_UsuaCBX___b WHERE A.id_campana={$campanCbx}");

        $response=array();
        if($sql && $sql->num_rows > 0){
            while($row = $sql->fetch_object()){
                array_push($response,array("dy_agente"=>$row->dy_agente,"dy_usuario"=>$row->dy_usuario, "usuario"=>$row->usuari));
            }
        }
        return $response;
    }

    function distribuirAutomatico(int $bd, int $muestra, array $agentes):array
    {
        global $mysqli;
        global $BaseDatos;
        (String) $strMuestra="G{$bd}_M{$muestra}";
        (Int) $asignados=0;
        (Int) $total=0;

        $sql=$mysqli->query("SELECT COUNT(1) AS total FROM {$BaseDatos}.{$strMuestra}");
        if($sql){
            $sql=$sql->fetch_object();
            $total=$sql->total;

            if($total > 0){
                $limit=round($total/count($agentes));
                //LIMPIAMOS EL CAMPO AGENTE DE LA MUESTRA
                $mysqli->query("UPDATE {$BaseDatos}.{$strMuestra} SET {$strMuestra}_ConIntUsu_b=NULL");
                //ASIGNAMOS LOS REGISTROS A CADA AGENTE
                foreach($agentes as $item){
                    $sql=$mysqli->query("UPDATE {$BaseDatos}.{$strMuestra} SET {$strMuestra}_ConIntUsu_b={$item['usuario']} WHERE {$strMuestra}_ConIntUsu_b IS NULL LIMIT {$limit}");
                    if($sql && $mysqli->affected_rows >= 1){
                        $asignados=$asignados+$mysqli->affected_rows;
                    }
                }
            }
        }

        return array("total"=>$total,"asignados"=>$asignados);
    }

    function distribuirXagente(int $bd, int $muestra, int $columnaAgente):array
    {
        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;

        (String)$strColumna="G{$bd}_C{$columnaAgente}";
        (String) $strMuestra="G{$bd}_M{$muestra}";
        (Int) $asignados=0;
        (Int) $total=0;
        (Int) $huesped=isset($_SESSION['HUESPED']) ? $_SESSION['HUESPED'] : 0;        

        if($huesped > 0){
            $sql=$mysqli->query("SELECT COUNT(1) AS total FROM {$BaseDatos}.{$strMuestra}");
            if($sql){
                $sql=$sql->fetch_object();
                $total=$sql->total;
    
                if($total > 0){
                    //LIMPIAMOS EL CAMPO AGENTE DE LA MUESTRA
                    $mysqli->query("UPDATE {$BaseDatos}.{$strMuestra} SET {$strMuestra}_ConIntUsu_b=NULL");

                    //ASIGNAMOS LOS REGISTROS A CADA AGENTE
                    $sql=$mysqli->query("UPDATE {$BaseDatos}.{$strMuestra} LEFT JOIN {$BaseDatos}.G{$bd} ON G{$bd}_ConsInte__b={$strMuestra}_CoInMiPo__b LEFT JOIN {$BaseDatos_systema}.USUARI ON {$strColumna}=USUARI_Codigo____b SET {$strMuestra}_ConIntUsu_b=USUARI_ConsInte__b WHERE {$strColumna} IS NOT NULL AND {$strColumna} != '' AND USUARI_ConsInte__PROYEC_b={$huesped}");

                    if($sql && $mysqli->affected_rows >=1){
                        $asignados=$mysqli->affected_rows;
                    }
                }
            }
        }
        return array("total"=>$total,"asignados"=>$asignados);
    }

    function getSubForms(int $idBd):array
    {
        global $mysqli;
        global $BaseDatos_systema;
        (Array) $arrSubForms=array();

        $sqlSubforms=$mysqli->query("SELECT GUIDET_ConsInte__GUION__Det_b,GUIDET_Nombre____b FROM {$BaseDatos_systema}.GUIDET LEFT JOIN {$BaseDatos_systema}.GUION_ ON GUIDET_ConsInte__GUION__Det_b=GUION__ConsInte__b WHERE GUIDET_ConsInte__GUION__Mae_b ={$idBd} AND GUION__Tipo______b=2 GROUP BY GUIDET_ConsInte__GUION__Det_b");

        if($sqlSubforms && $sqlSubforms->num_rows > 0){
            while($row = $sqlSubforms->fetch_object()){
                array_push($arrSubForms,array("guion"=>$row->GUIDET_ConsInte__GUION__Det_b,"nombre"=>"Campos del subformulario ".$row->GUIDET_Nombre____b));
            }
        }

        return $arrSubForms;
    }

    function getCamposBd(int $idBd):array
    {
        global $mysqli;
        global $BaseDatos_systema;
        (Array) $arrCamposBd=array();

        $sqlCamposBd=$mysqli->query("SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b,PREGUN_ConsInte__GUION__b,PREGUN_IndiBusc__b FROM {$BaseDatos_systema}.PREGUN LEFT JOIN {$BaseDatos_systema}.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_Tipo______b NOT IN(17,16,15,12,9) AND SECCIO_TipoSecc__b=1 AND PREGUN_ConsInte__GUION__b ={$idBd}");
        if($sqlCamposBd && $sqlCamposBd->num_rows > 0){
            while($row = $sqlCamposBd->fetch_object()){
                array_push($arrCamposBd,array("guion"=>$row->PREGUN_ConsInte__GUION__b,"campo"=>$row->PREGUN_ConsInte__b,"texto"=>$row->PREGUN_Texto_____b,"busqueda"=>$row->PREGUN_IndiBusc__b));
            }
        }

        return $arrCamposBd;
    }

    if(isset($_POST['getCamposBusquedaManual'])){
        (Int) $idBd=isset($_POST['idBd']) && is_numeric($_POST['idBd']) ? $_POST['idBd'] : 0;
        (Bool) $response=false;
        (Array) $data=array();
        
        if($idBd > 0){
            (Array) $arrSubForms=getSubForms($idBd);
            $data[0]=array("campos"=>getCamposBd($idBd),"nombre"=>"Campos de la base de datos de esta campaña");
            if(count($data[0])>0){
                $response=true;
                if(count($arrSubForms)>0){
                    foreach($arrSubForms as $i => $item){
                        array_push($data,array("campos"=>getCamposBd($item['guion']),"nombre"=>$item['nombre']));
                    }
                }
            }
        }

        echo json_encode(array("estado"=>$response, "data"=>$data));
    }

    if(isset($_GET['agregarCamposForm'])){
        $arrCampos = $_POST['arrCampos'];
        for ($i=0; $i < count($arrCampos); $i++) {
            $LsqlUpdate=$mysqli->query("UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_IndiBusc__b=-1 WHERE PREGUN_ConsInte__b={$arrCampos[$i]}");
            if($LsqlUpdate && $mysqli->affected_rows==1){
                $estado = 'ok';
            } else  {
                $estado = 'error';
                break;
            }
        }
        
        echo json_encode(['estado' => $estado]);
    }        
    
    if(isset($_GET['quitarCamposForm'])){
        $arrCampos = $_POST['arrCampos'];
        for ($i=0; $i < count($arrCampos); $i++) {
            $LsqlUpdate=$mysqli->query("UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_IndiBusc__b=0 WHERE PREGUN_ConsInte__b={$arrCampos[$i]}");
            if($LsqlUpdate && $mysqli->affected_rows==1){
                $estado = 'ok';
            } else  {
                $estado = 'error';
                break;
            }
        }
        
        echo json_encode(['estado' => $estado]);
    }

    if(isset($_GET['getDataFlugograma'])){
        $estrategia= isset($_POST['estrategia']) ? $_POST['estrategia'] : false;
        $campan= isset($_POST['campan']) ? $_POST['campan'] : false;
        if($estrategia && $campan){
            echo (addPasoFlugograma($estrategia,$campan));
        }else{
            echo json_encode(array('estado'=>false,'error'=>'No se identifico a la estrategia'));
        }
    }

    if(isset($_GET['insertarCallback'])){
        $idCampan=isset($_POST['idcampan']) ? $_POST['idcampan'] : false;
        $idCallback=isset($_POST['idCallback']) ? $_POST['idCallback'] : false;

        if($idCampan && $idCallback){

            //RECUPERAR HORARIO,HUESPED,ID_CAMPAN_CBX Y NOMBRE DE LA CAMPAÑA ENTRANTE 
            $sqlHora=$mysqli->query("SELECT CAMPAN_IdCamCbx__b,CAMPAN_Nombre____b,CAMPAN_ConsInte__PROYEC_b,CAMPAN_LHoraInicial_b,CAMPAN_LHoraFinal_b,CAMPAN_MHoraInicial_b,CAMPAN_MHoraFinal_b,CAMPAN_MiHoraInicial_b,CAMPAN_MiHoraFinal_b,CAMPAN_JHoraInicial_b,CAMPAN_JHoraFinal_b,CAMPAN_VHoraInicial_b,CAMPAN_VHoraFinal_b,CAMPAN_SHoraInicial_b,CAMPAN_SHoraFinal_b,CAMPAN_DHoraInicial_b,CAMPAN_FHoraInicial_b,CAMPAN_FHoraFinal_b FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b={$idCampan}");
            if($sqlHora && $sqlHora->num_rows===1){
                $B=$sqlHora->fetch_object();
                
                // 1. ACTUALIZAR LA CAMPAÑA DE ENTRADA CON EL ID DE LA CAMPAÑA DE SALIDA PARA EL CALLBACK
                $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.CAMPAN SET CAMPAN_CallBack_ConsInte__b={$idCallback} WHERE CAMPAN_ConsInte__b={$idCampan}");
                
                //2. INCLUIR LOS AGENTES A LA CAMPAÑA DE CALLBACK
                    //2.1 INSERTAR EN ASITAR
                $sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.ASITAR (ASITAR_ConsInte__CAMPAN_b,ASITAR_ConsInte__USUARI_b,ASITAR_UsuarioCBX_b,ASITAR_ConsInte__GUION__Gui_b,ASITAR_ConsInte__GUION__Pob_b,ASITAR_ConsInte__MUESTR_b,ASITAR_IndiConc__b) SELECT {$idCallback},ASITAR_ConsInte__USUARI_b,ASITAR_UsuarioCBX_b,ASITAR_ConsInte__GUION__Gui_b,ASITAR_ConsInte__GUION__Pob_b,ASITAR_ConsInte__MUESTR_b,ASITAR_IndiConc__b FROM {$BaseDatos_systema}.ASITAR WHERE ASITAR_ConsInte__CAMPAN_b={$idCampan}");
                
                    //2.2 INSERTAR EN DY_CAMPANAS_AGENTES
                        //2.2.1 RECUPERAR EL ID DE DY_CAMPANAS DE LA CAMPAÑA DE CALLBACK
                $sqlCallback=$mysqli->query("SELECT CAMPAN_IdCamCbx__b FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b={$idCallback}");
                if($sqlCallback && $sqlCallback->num_rows==1){
                    $sqlCallback=$sqlCallback->fetch_object();
                        //2.2.2 INSERTAR LOS AGENTES EN DY_CAMPANAS_AGENTES PARA LA CAMPAÑA DE CALLBACK
                    $sql=$mysqli->query("INSERT INTO dyalogo_telefonia.dy_campanas_agentes (id_campana,id_agente,prioridad) SELECT $sqlCallback->CAMPAN_IdCamCbx__b,id_agente,prioridad FROM dyalogo_telefonia.dy_campanas_agentes WHERE id_campana={B->CAMPAN_IdCamCbx__b}");    
                }
                
                //3. ACTUALIZAR EL HORARIO DE LA CAMPAÑA DEL CALLBACK
                $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.CAMPAN SET CAMPAN_Nombre____b=CONCAT('CALLBACK ','{$B->CAMPAN_Nombre____b}'),CAMPAN_ConsInte__PROYEC_b={$B->CAMPAN_ConsInte__PROYEC_b},CAMPAN_LHoraInicial_b='{$B->CAMPAN_LHoraInicial_b}',CAMPAN_LHoraFinal_b='{$B->CAMPAN_LHoraFinal_b}',CAMPAN_MHoraInicial_b='{$B->CAMPAN_MHoraInicial_b}',CAMPAN_MHoraFinal_b='{$B->CAMPAN_MHoraFinal_b}',CAMPAN_MiHoraInicial_b='{$B->CAMPAN_MiHoraInicial_b}',CAMPAN_MiHoraFinal_b='{$B->CAMPAN_MiHoraFinal_b}',CAMPAN_JHoraInicial_b='{$B->CAMPAN_JHoraInicial_b}',CAMPAN_JHoraFinal_b='{$B->CAMPAN_JHoraFinal_b}',CAMPAN_VHoraInicial_b='{$B->CAMPAN_VHoraInicial_b}',CAMPAN_VHoraFinal_b='{$B->CAMPAN_VHoraFinal_b}',CAMPAN_SHoraInicial_b='{$B->CAMPAN_SHoraInicial_b}',CAMPAN_SHoraFinal_b='{$B->CAMPAN_SHoraFinal_b}',CAMPAN_DHoraInicial_b='{$B->CAMPAN_DHoraInicial_b}',CAMPAN_FHoraInicial_b='{$B->CAMPAN_FHoraInicial_b}',CAMPAN_FHoraFinal_b='{$B->CAMPAN_FHoraFinal_b}' WHERE CAMPAN_ConsInte__b={$idCallback}");

                //4.ACTUALIZAR EL ENLACE ENTRE CAMPOS
                $sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.CAMINC (CAMINC_ConsInte__CAMPAN_b,CAMINC_ConsInte__GUION__Gui_b,CAMINC_ConsInte__GUION__Pob_b,CAMINC_ConsInte__MUESTR_b,CAMINC_ConsInte__CAMPO_Pob_b,CAMINC_ConsInte__CAMPO_Gui_b,CAMINC_NomCamPob_b,CAMINC_NomCamGui_b,CAMINC_TexPrePob_b,CAMINC_TexPreGui_b) SELECT {$idCallback},CAMINC_ConsInte__GUION__Gui_b,CAMINC_ConsInte__GUION__Pob_b,1990,CAMINC_ConsInte__CAMPO_Pob_b,CAMINC_ConsInte__CAMPO_Gui_b,CAMINC_NomCamPob_b,CAMINC_NomCamGui_b,CAMINC_TexPrePob_b,CAMINC_TexPreGui_b FROM {$BaseDatos_systema}.CAMINC WHERE CAMINC_ConsInte__CAMPAN_b={$idCampan}");

                //5. CREAR LA FLECHA ENTRE LAS CAMPAÑAS
                $sqlCon=$mysqli->query("SELECT ESTPAS_ConsInte__ESTRAT_b,ESTPAS_ConsInte__b FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__CAMPAN_b IN ({$idCampan},{$idCallback})");
                if($sqlCon && $sqlCon->num_rows === 2){
                    $i=0;
                    while($estcon = $sqlCon->fetch_object()){
                        if($i==0){
                            $estrat=$estcon->ESTPAS_ConsInte__ESTRAT_b;
                            $campan=$estcon->ESTPAS_ConsInte__b;
                        }else{
                            $callback=$estcon->ESTPAS_ConsInte__b;
                        }
                        $i++;
                    }

                    //echo (addPasoFlugograma($estrat,$campan,true,$callback));
                    $flecha = new GeneradorDeFlechas;
                    $from=$flecha->generarPuerto($campan,$callback,'flujograma');
                    $to=$flecha->generarPuerto($callback,$campan,'flujograma');

                    $sqlEstcon=$mysqli->query("INSERT INTO {$BaseDatos_systema}.ESTCON (ESTCON_Nombre____b,ESTCON_Comentari_b,ESTCON_ConsInte__ESTPAS_Des_b,ESTCON_ConsInte__ESTPAS_Has_b,ESTCON_FromPort_b,ESTCON_ToPort_b,ESTCON_ConsInte__ESTRAT_b,ESTCON_Deshabilitado_b) VALUES ('Conector','CALLBACK',{$campan},{$callback},'{$from}','{$to}',{$estrat},-1)");
                    if($sqlEstcon){
                       echo json_encode(array("estado"=>true,"addFlecha"=>true,));
                    }else{
                        echo json_encode(array("estado"=>false,"addFlecha"=>true,));
                    }
                    //echo $estrat.'|||'.$campan.'|||'.true.'|||'.$callback;
                }
                
                //6. ACTUALIZAR LA CAMPAÑA DE CALLBACK PARA DEJARLA POR SISTEMA
                $sqlEstpas=$mysqli->query("UPDATE {$BaseDatos_systema}.ESTPAS SET ESTPAS_Generado_Por_Sistema_b=-1 WHERE ESTPAS_ConsInte__b={$callback}");
                
            }


        }
    }

    if(isset($_POST['asignarRegistros'])){
        (Array) $campan = isset($_POST['idCampan']) && intVal($_POST['idCampan']) > 0 ? getInfoCampan($_POST['idCampan']) : array();
        (Int) $distribucion= isset($_POST['distribucion']) ? $_POST['distribucion'] : 0;
        (Int )$columnaAgente=isset($_POST['agente']) && $_POST['agente'] != 0 ? $_POST['agente'] : 0;
        (Int) $total=0;
        (Bool) $estado=false;
        (Int) $asignados=0;
        (String) $mensaje='';
        (Array) $arrResult=array();

        if(count($campan) > 0 && $distribucion > 0){
            if($distribucion == 1){
                $arrAgentes=getAgentesCampan($campan['idCbx']);
                if(count($arrAgentes)>0){
                    $arrResult=distribuirAutomatico($campan['bd'],$campan['muestra'],$arrAgentes);
                }else{
                    $mensaje="No hay agentes asignados a la campaña";
                }
            }else{
                if($columnaAgente > 0){
                    $arrResult=distribuirXagente($campan['bd'],$campan['muestra'],$columnaAgente);
                }else{
                    $mensaje="No se identifico la columna donde esta el correo del agente";
                }
            }

            if(count($arrResult) > 0 && $arrResult['asignados'] > 0){
                $total=$arrResult['total'];
                $asignados=$arrResult['asignados'];
                $estado=true;
                $mensaje="Registros distribuidos exitosamente";
            }else{
                $mensaje="No se pudo realizar la distribución de registros";
            }

        }else{
            $mensaje="Parametros incompletos o invalidos";
        }
        
        echo json_encode(array("estado"=>$estado,"mensaje"=>$mensaje,"asignados"=>$asignados,"total"=>$total));
    }

    if (isset($_GET["intIdMetrica"])) {

        $intIdMetrica = $_GET["intIdMetrica"];
        $bd = $_GET["bd"];
        $dt = $_GET["dt"];
        $ic = $_GET["ic"];
        $lr = $_GET["lr"];
        $mt = $_GET["mt"];
        // $NRGXP = str_replace("\\", "", $_GET["NRGXP"]); 
        $arrTl = str_replace("\\", "", $_GET["arrTl"]); 
        // $NRGXP = json_decode($NRGXP);
        $arrTl = json_decode($arrTl);

        // No se requiere el uso de validacion poir regex, debido a que lo trae desde la vista
        
        // $REGEXPAND = "";
        // $REGEXPOR = "";


        // $REGEXPAND .= "AND (";
        // $REGEXPOR .= "AND (";

        // foreach ($arrTl as $tel) {

        //     $REGEXPOR .= "(G".$bd."_C".$tel." IS NOT NULL AND (";

        //     foreach ($NRGXP as $value) {

        //         $REGEXPAND .= "TRIM(G".$bd."_C".$tel.") NOT REGEXP ".$value." AND ";
        //         $REGEXPOR .= "TRIM(G".$bd."_C".$tel.") REGEXP ".$value." OR ";

        //     }

        //     $REGEXPOR = substr($REGEXPOR,0,-4).")) OR ";

        //     $REGEXPAND = substr($REGEXPAND, 0, -4)."OR G".$bd."_C".$tel." IS NULL) AND (";

        // }

        // $REGEXPAND = substr($REGEXPAND, 0, -6);
        // $REGEXPOR = substr($REGEXPOR,0,-4).")";


        // Se obtiene la vista

        $strView_t = nombreVistas($bd, $mt );

        $strCamposTel_t = "";

        foreach ($arrTl as $num => $tels) {

            $strCamposTel_t .= ", ".getNameColumnView($strView_t, $tels )." AS NUMERO_DE_CONTACTO_".($num+1);
        }


        $strNombreExcell_t = "Metrica";

        $strCamposPrincipales_t = "";

        $strSQLGuion_t = "SELECT CONCAT('G".$bd."_C',GUION__ConsInte__PREGUN_Pri_b) AS ID_PRI, PRI.PREGUN_Texto_____b AS PRI, CONCAT('G".$bd."_C',GUION__ConsInte__PREGUN_Sec_b) AS ID_SEC, SEC.PREGUN_Texto_____b AS SEC  FROM ".$BaseDatos_systema.".GUION_ JOIN ".$BaseDatos_systema.".PREGUN AS PRI ON GUION__ConsInte__PREGUN_Pri_b = PRI.PREGUN_ConsInte__b JOIN ".$BaseDatos_systema.".PREGUN AS SEC ON GUION__ConsInte__PREGUN_Sec_b = SEC.PREGUN_ConsInte__b  WHERE GUION__ConsInte__b = ".$bd;

        $resSQLGuion_t = $mysqli->query($strSQLGuion_t);

        if ($resSQLGuion_t && $resSQLGuion_t->num_rows > 0) {

            $objSQLGuion_t = $resSQLGuion_t->fetch_object();

            $ID_PRI = $objSQLGuion_t->ID_PRI;
            $PRI = getNameColumnView($strView_t, $objSQLGuion_t->ID_PRI);
            $ID_SEC = $objSQLGuion_t->ID_SEC;
            $SEC = getNameColumnView($strView_t, $objSQLGuion_t->ID_SEC);

        }



        $strSQLMetrica_t = "SELECT 'Hubo un Error, puede ser porque no hay datos para mostrar o posiblemente se deba a que esta intentado descargar una gran cantidad de informacion que la aplicacion no soporta.' AS ERROR";

        switch ($intIdMetrica) {
            case '0':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS, AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE CLASIFICACION_UG = 'No contactable'  AND SuperoLimiteIntentos = FALSE ";
                
                $strNombreExcell_t = "TELEFONOS ERRADOS";
                break;
            case '1':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS, AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE REINTENTO_UG = 'NO REINTENTAR' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE";

                $strNombreExcell_t = "NO REINTENTAR";

                break;
            case '2':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS,  AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE SuperoLimiteIntentos = TRUE";

                $strNombreExcell_t = "SUPERAN EL LIMITE DE INTENTOS";
                break;
            case '3':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS,  AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE ACTIVO = 0 AND (REINTENTO_UG != 'NO REINTENTAR' or REINTENTO_UG IS NULL) AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE";

                $strNombreExcell_t = "REGISTROS INACTIVOS";
                break;
            case '4':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS,  AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE ACTIVO = -1 AND REINTENTO_UG = 'REINTENTO AUTOMATICO' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE AND (FecHorMinProGes > NOW())";

                $strNombreExcell_t = "SE MARCARAN DESPUES DEL TIEMPO DEFINIDO";

                break;
            case '5':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS,  AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE ACTIVO = '-1' AND REINTENTO_UG = 'AGENDA' AND SuperoLimiteIntentos = FALSE AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND FECHA_AGENDA_UG > date_format(NOW(), '%Y-%m-%d %H:%i:%s')";
                
                $strNombreExcell_t = "MARCABLES AGENDADOS A FUTURO";
                break;
            case '6':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS,  AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE ACTIVO = '-1' AND (REINTENTO_UG = 'NO GESTIONADO' OR REINTENTO_UG = 'VACIO') AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE";

                $strNombreExcell_t = "MARCABLES SIN GESTION";
                break;
            case '7':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS,  AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE ACTIVO = -1 AND REINTENTO_UG = 'REINTENTO AUTOMATICO' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE AND (FecHorMinProGes <= NOW() OR FecHorMinProGes IS NULL)";

                $strNombreExcell_t = "MARCABLES EN REINTENTO";
                break;
            case '8':

                $strSQLMetrica_t = "SELECT ID AS ID_DB, FECHA_CREACION,  {$PRI}, {$SEC} {$strCamposTel_t} , CANTIDAD_INTENTOS,  AGENTE_ASIGNADO, FecHorMinProGes AS HORA_MINIMA_PROXIMA_GESTION, IF(ACTIVO = -1, 'ACTIVO', 'INACTIVO') AS ACTIVO, REINTENTO_UG AS ESTADO, FECHA_AGENDA_UG AS FECHA_AGENDA, FECHA_CREACION_MUESTRA_DY, FECHA_REACTIVACION_MUESTRA_DY FROM {$BaseDatos}.{$strView_t} WHERE ACTIVO = -1 AND REINTENTO_UG = 'AGENDA' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE AND (FECHA_AGENDA_UG <= date_format(NOW(), '%Y-%m-%d %H:%i:%s') OR FECHA_AGENDA_UG IS NULL)";
                
                $strNombreExcell_t = "MARCABLES AGENDADAS VENCIDAS";
                break;
        }
        
        $strNombreExcell_t .= date('_Y-m-d h_i_s');
        $resSQLMetrica_t = $mysqli->query($strSQLMetrica_t);  
        
        if ($resSQLMetrica_t && $resSQLMetrica_t->num_rows > 0) {
            $arrColumnas_t = $resSQLMetrica_t->fetch_fields();
            $arrStyles = ['font'=>'Arial','font-size'=>10,'fill'=>'#20BEE8','color' => '#FFFF','halign'=>'center'];

            foreach ($arrColumnas_t as $name) {
                $arrHeader_t[$name->name] = "string"; 
            }

            $writer = new XLSXWriter();
            $writer->writeSheetHeader("Metrica", $arrHeader_t, $arrStyles);

            while ($dato = $resSQLMetrica_t->fetch_object()) {
                $writer->writeSheetRow("Metrica",(array)$dato);
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$strNombreExcell_t.'.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->writeToStdOut();
            exit(0);

        }      

    }

    if (isset($_POST["metricaGraf"])) {

        $bd = $_POST["bd"];
        $dt = $_POST["dt"];
        $ic = $_POST["ic"];
        $lr = $_POST["lr"];
        $mt = $_POST["mt"];
        // $arrTl = $_POST["arrTl"];
        // $NRGXP = $_POST["NRGXP"];
        // $NRGXP = str_replace("\\", "", $_POST["NRGXP"]); 
        // $arrTl = str_replace("\\", "", $_POST["arrTl"]); 


        // Ya no se necesitan expresiones regulares por lo cual esta parte se quitaria 

        // $REGEXPAND = "";
        // $REGEXPOR = "";


        // $REGEXPAND .= "AND (";
        // $REGEXPOR .= "AND (";

        // foreach ($arrTl as $tel) {

        //     $REGEXPOR .= "(G".$bd."_C".$tel." IS NOT NULL AND (";

        //     foreach ($NRGXP as $value) {

        //         $REGEXPAND .= "TRIM(G".$bd."_C".$tel.") NOT REGEXP ".$value." AND ";
        //         $REGEXPOR .= "TRIM(G".$bd."_C".$tel.") REGEXP ".$value." OR ";

        //     }

        //     $REGEXPOR = substr($REGEXPOR,0,-4).")) OR ";

        //     $REGEXPAND = substr($REGEXPAND, 0, -4)."OR G".$bd."_C".$tel." IS NULL) AND (";

        // }

        // $REGEXPAND = substr($REGEXPAND, 0, -6);
        // $REGEXPOR = substr($REGEXPOR,0,-4).")";



        // SE OBTIENE EL ESTRAT DEL PASO 
        $strSQLESTRAT = "SELECT ESTPAS_ConsInte__ESTRAT_b as ESTRAT FROM {$BaseDatos_systema}.ESTPAS where ESTPAS_ConsInte__CAMPAN_b = {$ic} ;";

        $resSQLESTRAT = $mysqli->query($strSQLESTRAT);
        $Estrat = $resSQLESTRAT->fetch_object()->ESTRAT;


        // SE OBTIENE EL NOMBRE DE LA VISTA DE LA MUESTRA
        
        $strSQLView = "SELECT nombre FROM {$BaseDatos_general}.vistas_generadas where id_estrat = {$Estrat} and nombre like '%{$mt}%' order by id desc limit 1;";

        $resSQLView = $mysqli->query($strSQLView);
        $View = $resSQLView->fetch_object()->nombre;
    
        // SE INICIA A OBTENER EL CONTEO DE CADA DATO

        $strJSONData_t = '{"CanTelErra":"0","CanSupLim":"0","CanSeMaDeTi":"0","CanInac":"0","CanNoRe":"0"}'; 

        $CanInac = 0; 
        $CanNoRe = 0;   
        $CanSupLim = 0;
        $CanTelErra = 0;
        $CanSeMaDeTi = 0;   
        $CanMarReAgen = 0;
        $CanMarSinGes = 0;
        $CanMarReint = 0;
        $CanMarReAgenVen = 0;

        // SE VALIDA LA CANTIDAD DE REGISTROS INACTIVOS
        $strSQLCanInac_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View} WHERE ACTIVO = 0 AND (REINTENTO_UG != 'NO REINTENTAR' or REINTENTO_UG IS NULL) AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE";

        $resSQLCanInac_t = $mysqli->query($strSQLCanInac_t);

        if ($resSQLCanInac_t && $resSQLCanInac_t->num_rows > 0) {

            $CanInac = $resSQLCanInac_t->fetch_object()->cantidad;

        }

        // Se valida la cantidad de registros en estado no reintentar 

        $strSQLCanNoRe_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View} WHERE REINTENTO_UG = 'NO REINTENTAR' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE ";

        $resSQLCanNoRe_t = $mysqli->query($strSQLCanNoRe_t);

        if ($resSQLCanNoRe_t && $resSQLCanNoRe_t->num_rows > 0) {

            $CanNoRe = $resSQLCanNoRe_t->fetch_object()->cantidad;

        }

         // Se valida la cantidad de registros que superan el limite de intentos

        $strSQLCanSupLim_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View}  WHERE SuperoLimiteIntentos = TRUE ";

        $resSQLCanSupLim_t = $mysqli->query($strSQLCanSupLim_t);

        if ($resSQLCanSupLim_t && $resSQLCanSupLim_t->num_rows > 0) {

            $CanSupLim = $resSQLCanSupLim_t->fetch_object()->cantidad;

        }
        
        // Se valida la cantidad de registros no contactables o no gestionables 

        $strSQLCanTelErra_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View}  WHERE CLASIFICACION_UG = 'No contactable'  AND SuperoLimiteIntentos = FALSE ";

        $resSQLCanTelErra_t = $mysqli->query($strSQLCanTelErra_t);

        if ($resSQLCanTelErra_t && $resSQLCanTelErra_t->num_rows > 0) {

            $CanTelErra = $resSQLCanTelErra_t->fetch_object()->cantidad;

        }



        // Se valida la cantidad de registros para reintento a futuro

        $strSQLCanSeMaDeTi_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View} WHERE ACTIVO = -1 AND REINTENTO_UG = 'REINTENTO AUTOMATICO' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE AND (FecHorMinProGes > NOW())";

        $resSQLCanSeMaDeTi_t = $mysqli->query($strSQLCanSeMaDeTi_t);

        if ($resSQLCanSeMaDeTi_t && $resSQLCanSeMaDeTi_t->num_rows > 0) {

            $CanSeMaDeTi = $resSQLCanSeMaDeTi_t->fetch_object()->cantidad;

        }

        // Se valida la cantidad de registros agendados a futuro

    
        $strSQLCanMarReAgen_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View} WHERE ACTIVO = '-1' AND REINTENTO_UG = 'AGENDA' AND SuperoLimiteIntentos = FALSE AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND FECHA_AGENDA_UG > date_format(NOW(), '%Y-%m-%d %H:%i:%s');";

        $resSQLCanMarReAgen_t = $mysqli->query($strSQLCanMarReAgen_t);

        if ($resSQLCanMarReAgen_t && $resSQLCanMarReAgen_t->num_rows > 0) {

            $CanMarReAgen = $resSQLCanMarReAgen_t->fetch_object()->cantidad;

        }

        // Se valida la cantidad de registros sin gestionar
        
        $strSQLCanMarSinGes_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View} WHERE ACTIVO = '-1' AND (REINTENTO_UG = 'NO GESTIONADO' OR REINTENTO_UG = 'VACIO') AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE";

        $resSQLCanMarSinGes_t = $mysqli->query($strSQLCanMarSinGes_t);

        if ($resSQLCanMarSinGes_t && $resSQLCanMarSinGes_t->num_rows > 0) {

            $CanMarSinGes = $resSQLCanMarSinGes_t->fetch_object()->cantidad;

        }

        // Se valida la cantidad de registros a reintentar 


        $strSQLCanMarReint_t = "SELECT count(*) AS cantidad FROM {$BaseDatos}.{$View} WHERE ACTIVO = -1 AND REINTENTO_UG = 'REINTENTO AUTOMATICO' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE AND (FecHorMinProGes <= NOW() OR FecHorMinProGes IS NULL)";

        $resSQLCanMarReint_t = $mysqli->query($strSQLCanMarReint_t);

        if ($resSQLCanMarReint_t && $resSQLCanMarReint_t->num_rows > 0) {

            $CanMarReint = $resSQLCanMarReint_t->fetch_object()->cantidad;

        }


        // Se valida la cantidad de registros agendado vencidos

        $strSQLCanMarReAgenVen_t = "SELECT COUNT(*) AS cantidad FROM {$BaseDatos}.{$View} WHERE ACTIVO = -1 AND REINTENTO_UG = 'AGENDA' AND (CLASIFICACION_UG != 'No contactable' OR CLASIFICACION_UG IS NULL) AND SuperoLimiteIntentos = FALSE AND (FECHA_AGENDA_UG <= date_format(NOW(), '%Y-%m-%d %H:%i:%s') OR FECHA_AGENDA_UG IS NULL)";

        $resSQLCanMarReAgenVen_t = $mysqli->query($strSQLCanMarReAgenVen_t);

        if ($resSQLCanMarReAgenVen_t && $resSQLCanMarReAgenVen_t->num_rows > 0) {

            $CanMarReAgenVen = $resSQLCanMarReAgenVen_t->fetch_object()->cantidad;

        }

        $consultas= array('noReintentar'=>$strSQLCanNoRe_t,'mayorIntentos'=>$strSQLCanSupLim_t,'telErrado'=>$strSQLCanTelErra_t,'ReintentoAfuturo'=>$strSQLCanSeMaDeTi_t,'AgendaAfuturo'=>$strSQLCanMarReAgen_t,'singestion'=>$strSQLCanMarSinGes_t,'reintentar'=>$strSQLCanMarReint_t,'agendaVencida'=>$strSQLCanMarReAgenVen_t, 'inactivos'=>$strSQLCanInac_t);
        
        echo json_encode([(INT)$CanTelErra,(INT)$CanNoRe,(INT)$CanSupLim,(INT)$CanInac,(INT)$CanSeMaDeTi,(INT)$CanMarReAgen,(INT)$CanMarSinGes,(INT)$CanMarReint,(INT)$CanMarReAgenVen,$consultas]);

    }

    if (isset($_POST["JSONInfoCampan"])) {

        $idPaso_t = $_POST["idPaso_t"];
        $idAgente_t = $_POST["idAgente_t"];
        $arrJSONInfoCampan_t = [];
        $strJSONInfoCampan_t = '{"error" : "error"}';

        $strSQLDatosCampan_t = "SELECT CAMPAN_ConsInte__b,CAMPAN_ConsInte__GUION__Pob_b,CAMPAN_ConsInte__MUESTR_b,CAMPAN_ConfDinam_b,CAMPAN_LimiRein__b FROM ".$BaseDatos_systema.".CAMPAN JOIN ".$BaseDatos_systema.".ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE ESTPAS_ConsInte__b = ".$idPaso_t;

        $resSQLDatosCampan_t = $mysqli->query($strSQLDatosCampan_t);

        if ($resSQLDatosCampan_t && $resSQLDatosCampan_t->num_rows > 0) {


            $objSQLDatosCampan_t = $resSQLDatosCampan_t->fetch_object();

            $ic = $objSQLDatosCampan_t->CAMPAN_ConsInte__b;
            $bd = $objSQLDatosCampan_t->CAMPAN_ConsInte__GUION__Pob_b;
            $mt = $objSQLDatosCampan_t->CAMPAN_ConsInte__MUESTR_b;
            $dt = $objSQLDatosCampan_t->CAMPAN_ConfDinam_b;
            $lr = $objSQLDatosCampan_t->CAMPAN_LimiRein__b;
            $arrTl = [];

            $strSQLTelefonos_t = "SELECT CAMCON_ConsInte__PREGUN_b FROM ".$BaseDatos_systema.".CAMCON WHERE CAMCON_ConsInte__GUION__Pob_b = ".$bd." AND CAMCON_ConsInte__MUESTR_b = ".$mt;

            $resSQLTelefonos_t = $mysqli->query($strSQLTelefonos_t);

            if ($resSQLTelefonos_t && $resSQLTelefonos_t->num_rows > 0) {

                while ($tels = $resSQLTelefonos_t->fetch_object()) {

                    $arrTl[] = $tels->CAMCON_ConsInte__PREGUN_b;

                }

            }

            $arrJSONInfoCampan_t=["ic"=>$ic,"bd"=>$bd,"mt"=>$mt,"dt"=>$dt,"arrTl"=>$arrTl,"lr"=>$lr,"NRGXP"=>[]];

            $strSQLPatrones_t = "SELECT patron_validacion AS NRGXP FROM dyalogo_telefonia.pasos_troncales A JOIN dyalogo_telefonia.tipos_destino B ON A.id_tipos_destino = B.id WHERE id_campana = ".$ic." AND patron_validacion IS NOT NULL";

            $resSQLPatrones_t = $mysqli->query($strSQLPatrones_t);

            if ($resSQLPatrones_t && $resSQLPatrones_t->num_rows > 0) {
                while ($key = $resSQLPatrones_t->fetch_object()) {
                    array_push($arrJSONInfoCampan_t["NRGXP"], $key->NRGXP);
                }
            }

            echo json_encode($arrJSONInfoCampan_t);
        }
    }

    if(isset($_POST['getConfigBusqueda'])){
        $sql=$mysqli->query("SELECT GUION_PERMITEINSERTAR_b,GUION_INSERTAUTO_b FROM {$BaseDatos_systema}.GUION_ WHERE GUION__ConsInte__b={$_POST['poblacion']}");
        $arrDatos=array();
        $estado="Error";
        if($sql && $sql->num_rows == 1){
            $sql=$sql->fetch_object();
            $arrDatos['permiteInsertar']=$sql->GUION_PERMITEINSERTAR_b;
            $arrDatos['insertAuto']=$sql->GUION_INSERTAUTO_b;
            $estado='ok';
        }
        echo json_encode(array("estado"=>$estado, "data"=>$arrDatos));
    }

    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan

    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos, etc, en fin extender mas los formularios en PHP
    
    if (isset($_POST["getListasCompleja"])) {

        $strOpciones_t = "";
         
        $strSQLIdPregui_t = " SELECT MIN(PREGUI_ConsInte__b), PREGUI_ConsInte__CAMPO__b AS id FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$_POST["lista"]; 

        $resSQLIdPregui_t = $mysqli->query($strSQLIdPregui_t);

        $objSQLIdPregui_t = $resSQLIdPregui_t->fetch_object();

        $strSQLBaseLista_t = "SELECT SUBSTRING(CAMPO__Nombre____b,2,(POSITION('_' IN CAMPO__Nombre____b)-2)) AS idBaseLista,  CAMPO__Nombre____b AS idCampoLista FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__b = ".$objSQLIdPregui_t->id;

        $resSQLBaseLista_t = $mysqli->query($strSQLBaseLista_t);

        $objSQLBaseLista_t = $resSQLBaseLista_t->fetch_object();

        $strSQLOpciones_t = "SELECT G".$objSQLBaseLista_t->idBaseLista."_ConsInte__b as id, ".$objSQLBaseLista_t->idCampoLista." AS opcion FROM ".$BaseDatos.".G".$objSQLBaseLista_t->idBaseLista;

        $resSQLOpciones_t = $mysqli->query($strSQLOpciones_t);

        if ($resSQLOpciones_t->num_rows > 0) {
            while($opcion = $resSQLOpciones_t->fetch_object()){
                $strOpciones_t .= '<option value = "'.$opcion->id.'">'.str_replace("&", "", str_replace(">", "", str_replace("<", "", $opcion->opcion))).'</option>';
            }
        }else{
            $strOpciones_t = '<option value = "0">SIN OPCIONES DE FILTRO</option>';            
        }


        echo $strOpciones_t;

     } 
    if(isset($_GET['obtener_nombres_campos'])){
        $guion = $_GET['guion'];
        $comboe = $_GET['campo'];
        echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
        echo '<option value="0">Seleccione</option>';
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion." AND (PREGUN_Tipo______b != 9 or PREGUN_Tipo______b != 12 ) AND PREGUN_FueGener_b != 3 ;";
        //echo $Lsql;
        $res_Resultado = $mysqli->query($Lsql);
        while ($key = $res_Resultado->fetch_object()) {
            echo "<option value='".$key->PREGUN_ConsInte__b."'>".utf8_decode($key->PREGUN_Texto_____b)."</option>";
        }
        echo '</select>';
    }


    if(isset($_GET['obtener_nombres_campos_tel'])){
        
        $guion = $_GET['guion'];
        $comboe = $_GET['campo'];
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion." AND (PREGUN_Tipo______b = 1 or PREGUN_Tipo______b = 3 OR PREGUN_Tipo______b = 4 ) AND PREGUN_FueGener_b != 3 ;";
        //echo $Lsql;
        echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
        echo '<option value="0">Seleccione</option>';
        
        $res_Resultado = $mysqli->query($Lsql);
        while ($key = $res_Resultado->fetch_object()) {
            echo "<option value='".$key->PREGUN_ConsInte__b."'>".utf8_decode($key->PREGUN_Texto_____b)."</option>";
        }
        echo '</select>';
    }

    if(isset($_GET['llenarDatosPregun'])){
        $guion = $_POST['guion'];
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion." AND PREGUN_Tipo______b NOT IN (12,9) AND (PREGUN_Texto_____b != 'OPTIN_DY_WF' AND PREGUN_Texto_____b != 'ESTADO_DY');";
        $res_Resultado = $mysqli->query($Lsql);
        $datos = array();
        while ($key = $res_Resultado->fetch_object()) {
            $datos[] = $key;
        }
        
        echo json_encode($datos);
    }

    if(isset($_POST['booCamordCamposBd'])){

        $intIdBd = $_POST['intIdBd'];

        echo '<option value="G'.$intIdBd.'_ConsInte__b" tipo="3">Id</option>';
        echo '<option value="G'.$intIdBd.'_FechaInsercion" tipo="5">Fecha Insercion</option>';

        $Lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Tipo______b, PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$intIdBd." AND PREGUN_Tipo______b IN (1,3,4,5,8,10,6,11,13) AND PREGUN_Texto_____b NOT IN ('ORIGEN_DY_WF','OPTIN_DY_WF','ESTADO_DY')";
        $res_Resultado = $mysqli->query($Lsql);

        while ($key = $res_Resultado->fetch_object()) {

            echo "<option value='G".$intIdBd."_C".$key->PREGUN_ConsInte__b."' tipo='".$key->PREGUN_Tipo______b."'>".$key->PREGUN_Texto_____b."</option>";
        }
    }

    if(isset($_GET['obtener_nombres_campos_ordenamiento'])){
        $guion = $_GET['guion'];
        $comboe = $_GET['campo'];
        echo '<select class="form-control input-sm"  style="display:none;" name="'.$comboe.'" id="'.$comboe.'">';
        echo '<option value="0">Seleccione</option>';
        echo '<option value="G'.$guion.'_ConsInte__b" tipo="3">Id</option>';
        echo '<option value="G'.$guion.'_FechaInsercion" tipo="5">Fecha Insercion</option>';
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion." AND PREGUN_Tipo______b NOT IN (15,12,9,8,13,11,6,10);";
        //echo $Lsql;
        $res_Resultado = $mysqli->query($Lsql);
        while ($key = $res_Resultado->fetch_object()) {
            echo "<option value='G".$guion."_C".$key->PREGUN_ConsInte__b."' tipo='".$key->PREGUN_Tipo______b."'>".utf8_encode($key->PREGUN_Texto_____b)."</option>";
        }
        echo '</select>';
    }

    if(isset($_GET['Llenar_datos_combo_1_ordenamiento'])){
        $comboe = $_GET['campo'];
        echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
        echo '<option value="0">Seleccione</option>';
        
        echo "<option value='P'>De la base de datos</option>";
        echo "<option value='M'>De la campaña</option>";
        

        echo '</select>';
    }

    if(isset($_GET['Llenar_datos_combo_2_ordenamiento'])){
        $comboe = $_GET['campo'];
        echo '<select class="form-control input-sm" style="display:none;" name="'.$comboe.'" id="'.$comboe.'">';
        echo '<option value="0">Seleccione</option>';
        
        echo "<option value='_Estado____b' tipo='3'>Estado</option>";
        echo "<option value='_FecUltGes_b' tipo='5'>Fecha ultima gestión</option>";
        echo "<option value='_NumeInte__b' tipo='3'>Cantidad de intentos</option>";
        echo "<option value='_UltiGest__b' tipo='3'>Ultima gestión</option>";
        

        echo '</select>';
    }

    if(isset($_GET['Llenar_datos_combo_3_ordenamiento'])){
        $comboe = $_GET['campo'];
        echo '<select class="form-control input-sm"   name="'.$comboe.'" id="'.$comboe.'">';
        echo '<option value="0">Seleccione</option>';
        echo "<option value='ASC'>ASCENDENTE</option>";
        echo "<option value='DESC'>DESCENDENTE</option>";
        echo '</select>';
    }

    if(isset($_GET['Llenar_datos_Combo_usuarios'])){
        
        echo '<select class="form-control input-sm"  name="USUARI_Nombre____b" id="USUARI_Nombre____b">';
        echo '<option value="0">Seleccione</option>';
        $Lsql = "SELECT USUARI_ConsInte__b, USUARI_Nombre____b FROM ".$BaseDatos_systema.".USUARI JOIN ".$BaseDatos_general.".huespedes_usuarios ON id_usuario = USUARI_UsuaCBX___b WHERE id_huesped =".$_SESSION['HUESPED']." AND USUARI_ConsInte__b NOT IN (SELECT ASITAR_ConsInte__USUARI_b FROM ".$BaseDatos_systema.".ASITAR WHERE ASITAR_ConsInte__CAMPAN_b = ".$_GET['campan'].")";
        //echo $Lsql;
        $res_Resultado = $mysqli->query($Lsql);
        while ($key = $res_Resultado->fetch_object()) {
            echo "<option value='".$key->USUARI_ConsInte__b."'>".utf8_encode($key->USUARI_Nombre____b)."</option>";
        }
        echo '</select>';
    }

    if(isset($_GET['getUsuariosCampanha'])){

        $Lsql = "SELECT USUARI_ConsInte__b , USUARI_Nombre____b FROM ".$BaseDatos_systema.".USUARI JOIN ".$BaseDatos_systema.".ASITAR ON ASITAR_ConsInte__USUARI_b = USUARI_ConsInte__b WHERE ASITAR_ConsInte__CAMPAN_b = ".$_POST['campan'];
        $res_Resultado = $mysqli->query($Lsql);
        $datos = array();
        while ($key = $res_Resultado->fetch_object()) {
            $datos[] = $key;
        }
        
        echo json_encode($datos);
    }

    if(isset($_GET['getMonoefCampanha'])){

        if(is_numeric($_POST['campan'])){
            $queryC="G10_ConsInte__b= {$_POST["campan"]}";
        }else{
            $queryC="md5(concat('".clave_get."', G10_ConsInte__b)) = '{$_POST["campan"]}'";
        }
        $str_Lsql = "SELECT G10_C73 , G5_C311 FROM {$BaseDatos_systema}.G10 JOIN {$BaseDatos_systema}.G5 ON G5_ConsInte__b = G10_C73  WHERE $queryC";
                                                            //echo $str_Lsql;
        $res = $mysqli->query($str_Lsql);
        $guion = $res->fetch_array();


        /* ya tenemos el guion ahora que? toca buscar el campo de tipificacion */
        $TipificacionLsql = "SELECT MONOEF_ConsInte__b, MONOEF_Texto_____b FROM ".$BaseDatos_systema.".LISOPC JOIN ".$BaseDatos_systema.".PREGUN ON PREGUN_ConsInte__OPCION_B = LISOPC_ConsInte__OPCION_b 
            JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b WHERE PREGUN_ConsInte__b = ".$guion['G5_C311'];
        $res_Resultado = $mysqli->query($TipificacionLsql);
        $datos = array();
        while ($key = $res_Resultado->fetch_object()) {
            $datos[] = $key;
        }
    
        echo json_encode($datos);   

    }

    if(isset($_GET['borrarReporte'])){
        $id = $_POST['idReporte'];
        $Lsql = "DELETE FROM ".$BaseDatos_general.".reportes_automatizados WHERE id = ".$id;
        if($mysqli->query($Lsql) === true){
            echo "1";
        }else{
            echo "Error".$mysqli->error;
        }
    }

    if(isset($_POST['getTipificaciones'])){

        $str_Lsql = 'SELECT G10_C73 , G10_C74 , G5_C311 FROM '.$BaseDatos_systema.'.G10 JOIN '.$BaseDatos_systema.'.G5 ON G5_ConsInte__b = G10_C73 JOIN '.$BaseDatos_systema.'.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b ='.$_POST['paso'];
        //echo $str_Lsql;

        $res = $mysqli->query($str_Lsql);
        $guion = $res->fetch_array();

        //$guion['G5_C311']
        $TipificacionLsql = "SELECT LISOPC_Clasifica_b ,LISOPC_Nombre____b, (CASE WHEN MONOEF_TipNo_Efe_b = 1 THEN 'Reintento automatico' WHEN MONOEF_TipNo_Efe_b= 2 THEN 'Agenda' WHEN MONOEF_TipNo_Efe_b= 3 THEN 'No reintentar' END) AS reintento FROM ".$BaseDatos_systema.".LISOPC JOIN ".$BaseDatos_systema.".PREGUN ON PREGUN_ConsInte__OPCION_B = LISOPC_ConsInte__OPCION_b LEFT JOIN ".$BaseDatos_systema.".MONOEF ON LISOPC_Clasifica_b=MONOEF_ConsInte__b WHERE PREGUN_ConsInte__b = ".$guion['G5_C311']." AND LISOPC_Borrado_b=0 ORDER BY LISOPC_Nombre____b ASC";
        $options = "<option value='0'>Seleccione</option>";

        $src_Tipificaciones = $mysqli->query($TipificacionLsql);
        if($src_Tipificaciones->num_rows > 0){
            while($key = $src_Tipificaciones->fetch_object()){
                if(isset($_POST['noMuestreEsta'])  && $_POST['noMuestreEsta'] != '0' && $_POST['noMuestreEsta'] != ' '){
                    if($key->LISOPC_Clasifica_b != $_POST['noMuestreEsta']){
                       $options .= "<option value='".$key->LISOPC_Clasifica_b."'>".$key->LISOPC_Nombre____b."</option>"; 
                    }
                }else{
                    $options .= "<option estado='{$key->reintento}' value='{$key->LISOPC_Clasifica_b}'>{$key->LISOPC_Nombre____b} / {$key->reintento}</option>";
                }
                
            }
        }
        echo $options;
    }

    if(isset($_POST['getEstados'])){

        $str_Lsql = 'SELECT G10_C73 , G10_C74 , G5_C311 FROM '.$BaseDatos_systema.'.G10 JOIN '.$BaseDatos_systema.'.G5 ON G5_ConsInte__b = G10_C73 JOIN '.$BaseDatos_systema.'.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b ='.$_POST['paso'];
        //echo $str_Lsql;

        $res = $mysqli->query($str_Lsql);
        $guion = $res->fetch_array();

        $opcionEstado = "<option value='0'>Seleccione</option>";
        $LsqlEstados = "SELECT LISOPC_ConsInte__b as id, LISOPC_Nombre____b as texto FROM ".$BaseDatos_systema.".LISOPC JOIN ".$BaseDatos_systema.".OPCION ON OPCION_ConsInte__b = LISOPC_ConsInte__OPCION_b WHERE OPCION_ConsInte__GUION__b = ".$guion['G10_C74']." AND OPCION_Nombre____b LIKE 'ESTADO_DY_%' ORDER BY LISOPC_Nombre____b ASC";

        $resOpcionesEstado = $mysqli->query($LsqlEstados);
        while($resKey = $resOpcionesEstado->fetch_object()){ 
            $opcionEstado .= "<option value='".$resKey->id."'>".$resKey->texto."</option>";
        }
        echo $opcionEstado;
    }

    if(isset($_POST['crudLisopcDelete'])){
            $id = $_POST['idLisopc'];
            $idNuevaTipificacion = $_POST['idNuevaTipificacion'];
            $intBd= $_POST['idBd'];

            $LsqlMonoEfNew = "SELECT LISOPC_Clasifica_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__b = ".$idNuevaTipificacion;
            $resNew = $mysqli->query($LsqlMonoEfNew);
            $datosMonoEf = $resNew->fetch_array();


            $Lsql = "SELECT LISOPC_Clasifica_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__b = ".$id;
            $res = $mysqli->query($Lsql);
            $datos = $res->fetch_array();

            /*Aqui comenzamos a editar la tipifacion anterior y la campbiamos por la nueva */
            $LslqScriptCamp = "SELECT PREGUN_ConsInte__b, PREGUN_ConsInte__GUION__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['pregun'];
            $resScript = $mysqli->query($LslqScriptCamp);
            $dataEscript = $resScript->fetch_array();

            $UpdateScript = "UPDATE ".$BaseDatos.".G".$dataEscript['PREGUN_ConsInte__GUION__b']." SET G".$dataEscript['PREGUN_ConsInte__GUION__b']."_C".$dataEscript['PREGUN_ConsInte__b']." = ".$idNuevaTipificacion." WHERE  G".$dataEscript['PREGUN_ConsInte__GUION__b']."_C".$dataEscript['PREGUN_ConsInte__b']." = ".$id;
            if($mysqli->query($UpdateScript) === true){
                //Actualizo el script
            }else{
                echo "Error actualizando Script => ".$mysqli->error;
            }

            //Actualizamos las muestras que esten relacionadas en campañas
            $LsqlCampan = "SELECT CAMPAN_ConsInte__MUESTR_b , CAMPAN_ConsInte__GUION__Pob_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__GUION__Gui_b = ".$dataEscript['PREGUN_ConsInte__GUION__b'];
            $resCampan = $mysqli->query($LsqlCampan);
            while ($key = $resCampan->fetch_object()) {
                $sql=$mysqli->query("SELECT COUNT(1) FROM {$BaseDatos}.G{$key->CAMPAN_ConsInte__GUION__Pob_b}_M{$key->CAMPAN_ConsInte__MUESTR_b}");
                if($sql){
                    $LsqlUpdateMuestraGesM = "UPDATE ".$BaseDatos.".G".$key->CAMPAN_ConsInte__GUION__Pob_b."_M".$key->CAMPAN_ConsInte__MUESTR_b." SET G".$key->CAMPAN_ConsInte__GUION__Pob_b."_M".$key->CAMPAN_ConsInte__MUESTR_b."_GesMasImp_b = ".$datosMonoEf['LISOPC_Clasifica_b']." WHERE G".$key->CAMPAN_ConsInte__GUION__Pob_b."_M".$key->CAMPAN_ConsInte__MUESTR_b."_GesMasImp_b = ".$datos['LISOPC_Clasifica_b'];
                    if($mysqli->query($LsqlUpdateMuestraGesM) === true){
    
                    }else{
                        echo "Error actualizando las muestra _GesMasImp_b => ".$mysqli->error;
                    } 
    
                    $LsqlUpdateMuestraUltiG = "UPDATE ".$BaseDatos.".G".$key->CAMPAN_ConsInte__GUION__Pob_b."_M".$key->CAMPAN_ConsInte__MUESTR_b." SET G".$key->CAMPAN_ConsInte__GUION__Pob_b."_M".$key->CAMPAN_ConsInte__MUESTR_b."_UltiGest__b = ".$datosMonoEf['LISOPC_Clasifica_b']." WHERE G".$key->CAMPAN_ConsInte__GUION__Pob_b."_M".$key->CAMPAN_ConsInte__MUESTR_b."_UltiGest__b = ".$datos['LISOPC_Clasifica_b'];
                    if($mysqli->query($LsqlUpdateMuestraUltiG) === true){
    
                    }else{
                        echo "Error actualizando las muestra _UltiGest__b => ".$mysqli->error;
                    }
                }
            }

            //Actualizamos Condia
            $Lsql_Condia = "UPDATE ".$BaseDatos_systema.".CONDIA SET CONDIA_ConsInte__MONOEF_b = ".$datosMonoEf['LISOPC_Clasifica_b']."  WHERE CONDIA_ConsInte__GUION__Gui_b = ".$dataEscript['PREGUN_ConsInte__GUION__b'];
            if( $mysqli->query($Lsql_Condia) === true ){

            }else{
                echo "Erro actualizando condia => ".$mysqli->error;
            }

           

            /* seccion eliminar Lista */
            if($datos['LISOPC_Clasifica_b'] > 5){
                $DeleteMoNoEf = "DELETE FROM ".$BaseDatos_systema.".MONOEF WHERE MONOEF_ConsInte__b > 0 AND MONOEF_ConsInte__b = ".$datos['LISOPC_Clasifica_b'];

                if($mysqli->query($DeleteMoNoEf) === true){
                    $DeleteLiSoPc = "UPDATE ".$BaseDatos_systema.".LISOPC SET LISOPC_Nombre____b=concat(LISOPC_Nombre____b,' BORRADO'), LISOPC_Borrado_b=-1 WHERE LISOPC_ConsInte__b = ".$id;
                    $mysqli->query($DeleteLiSoPc);
                }

            }else{
                $DeleteLiSoPc = "UPDATE ".$BaseDatos_systema.".LISOPC SET LISOPC_Nombre____b=concat(LISOPC_Nombre____b,' BORRADO'), LISOPC_Borrado_b=-1 WHERE LISOPC_ConsInte__b = ".$id;
                $mysqli->query($DeleteLiSoPc);
            }
           
    }

    if(isset($_POST['getTipificacionesCampanas'])){

        if(isset($_POST['idioma'])){
            switch ($_POST['idioma']) {
                case 'en':
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;

                case 'es':
                    include(__DIR__."../../../../idiomas/text_es.php");
                    break;

                default:
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;
            }
        }

        $columna = 3;
        $display = '';
        if(isset($_POST['tipificacionEntrante'])){
            $columna = 5;
            $display = 'style="display:none;"';
        }

        $cuerpo = "<thead>";
        $cuerpo .= "<tr class='active'>";
        $cuerpo .= "<th scope='col' class='col-md-".$columna."'>";
        $cuerpo .= "<label>".$str_opcion_nombre_."</label>";
        $cuerpo .= "</th>";
        
        $cuerpo .= "<th scope='col' class='col-md-2' ".$display .">";
        if(isset($_POST['tipificacionesBackoffice'])){
            $cuerpo .= "<label>".$str_tipo_Reinten2_."</label>";
        }else{
            $cuerpo .= "<label>".$str_tipo_Reintent_."</label>";
        }
        $cuerpo .= "</th>";

        if(!isset($_POST['tipificacionesBackoffice'])){
            $cuerpo .= "<th scope='col' class='col-md-1'>";
            $cuerpo .= "<label>".$str_horas_sumadas_."</label>";
            $cuerpo .= "</th>";
        }
        if(!isset($_POST['tipificacionesBackoffice'])){
            $cuerpo .= "<th scope='col' class='col-md-3'>";
            $cuerpo .= "<label>".$str_opcion_efecti_."</label>";
            $cuerpo .= "</th>";
        }
        $cuerpo .= "<th scope='col' class='col-md-2'>";
        $cuerpo .= "<label>".$str_opcion_import_."</label>";
        $cuerpo .= "</th>";
        
        $cuerpo .= "<th scope='col-4' class='col-md-1'>";
        $cuerpo .= "<label>Eliminar</label>";
        $cuerpo .= "</th>";

        $cuerpo .= "</tr>";
        $cuerpo .= "</thead> ";

        echo $cuerpo;

        /* lo primero es obtener el guion */
        $str_Lsql = 'SELECT G10_C73 , G10_C74 , G5_C311, G5_C312 FROM '.$BaseDatos_systema.'.G10 JOIN '.$BaseDatos_systema.'.G5 ON G5_ConsInte__b = G10_C73 JOIN '.$BaseDatos_systema.'.ESTPAS ON ESTPAS_ConsInte__CAMPAN_b = G10_ConsInte__b WHERE ESTPAS_ConsInte__b ='.$_POST['paso'];
        //echo $str_Lsql;

        $res = $mysqli->query($str_Lsql);
        $guion = $res->fetch_array();

        $IdLista = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$guion['G5_C311'];

        //echo $IdLista;
        $resT = $mysqli->query($IdLista);

        if($resT){


            $ListaT = $resT->fetch_array();

            echo "<input type='hidden' name='IdListaMia' value='".$ListaT['PREGUN_ConsInte__OPCION_B']."'>";

            /* ya tenemos el guion ahora que? toca buscar el campo de tipificacion */
            $TipificacionLsql = "SELECT LISOPC_ConsInte__OPCION_b, LISOPC_ConsInte__b ,LISOPC_Nombre____b, MONOEF_TipNo_Efe_b, MONOEF_Importanc_b, MONOEF_Contacto__b, LISOPC_Valor____b , MONOEF_CanHorProxGes__b FROM ".$BaseDatos_systema.".LISOPC JOIN ".$BaseDatos_systema.".PREGUN ON PREGUN_ConsInte__OPCION_B = LISOPC_ConsInte__OPCION_b 
                JOIN ".$BaseDatos_systema.".MONOEF ON MONOEF_ConsInte__b = LISOPC_Clasifica_b WHERE PREGUN_ConsInte__b = ".$guion['G5_C311']." AND LISOPC_Borrado_b=0 ORDER BY LISOPC_Nombre____b ASC";
            //echo $TipificacionLsql;

            /* Obtener los estado de la aplicacion */
            
            $LsqlEstados_Search = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion['G10_C74']." AND PREGUN_Texto_____b = 'ESTADO_DY'; ";

            $EstadoOpcion = $mysqli->query($LsqlEstados_Search);
            

            $opcionEstado = "<option value='0'>".$str_seleccione."</option>";
            if($EstadoOpcion->num_rows > 0){
                $datoOPcionEstado = $EstadoOpcion->fetch_array();
                $LsqlEstados = "SELECT LISOPC_ConsInte__b as id, LISOPC_Nombre____b as texto FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$datoOPcionEstado['PREGUN_ConsInte__OPCION_B']." AND LISOPC_Borrado_b=0 ORDER BY LISOPC_Nombre____b ASC";
                $resOpcionesEstado = $mysqli->query($LsqlEstados);
                while($resKey = $resOpcionesEstado->fetch_object()){ 
                    $opcionEstado .= "<option value='".$resKey->id."'>".$resKey->texto."</option>";
                }
            }


            $src_Tipificaciones = $mysqli->query($TipificacionLsql);
            if($src_Tipificaciones->num_rows > 0){
                while($key = $src_Tipificaciones->fetch_object()){

                    $cuerpo = "<tr class='' id='id_".$key->LISOPC_ConsInte__b."'>";
                    $cuerpo .= "<td class='col-md-".$columna."'>";
                    $cuerpo .= "<input type='hidden' name='idLisop[]' value='".$key->LISOPC_ConsInte__b."'>";
                    
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<input type='text' name='opciones_".$key->LISOPC_ConsInte__b."' class='form-control' value='".($key->LISOPC_Nombre____b)."'>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</td>";

                    $cuerpo .= "<td class='col-md-2' ".$display.">";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<select name='Tip_NoEfe_".$key->LISOPC_ConsInte__b."' class='form-control'>";

                    if(isset($_POST['tipificacionesBackoffice'])){
                        $Lxsql = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$guion['G5_C312'];
                        $capo  = $mysqli->query($Lxsql);
                        $valorListaEtado = NULL;
                        while ($kay = $capo->fetch_object()) {
                            $valorListaEtado = $kay->PREGUN_ConsInte__OPCION_B;
                        } 
                        $LsqlReintentos = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC.LISOPC_ConsInte__OPCION_b = ".$valorListaEtado." AND LISOPC_Borrado_b=0";
                        $obj = $mysqli->query($LsqlReintentos);
                        while($obje = $obj->fetch_object()){
                            $cuerpo .=  "<option value='".$obje->OPCION_ConsInte__b."'>".($obje->OPCION_Nombre____b)."</option>";
                        }  
                    }else{
                        if($key->MONOEF_TipNo_Efe_b == 1){
                            $cuerpo .= "<option value='1' selected>".$str_opcion_tipono_."</option>";
                        }else{
                            $cuerpo .= "<option value='1'>".$str_opcion_tipono_."</option>";
                        }

                        if($key->MONOEF_TipNo_Efe_b == 2){
                            $cuerpo .= "<option value='2' selected>".$str_opcion_egenda_."</option>";
                        }else{
                            $cuerpo .= "<option value='2'>".$str_opcion_egenda_."</option>";
                        }

                        if($key->MONOEF_TipNo_Efe_b == 3 || isset($_POST['tipificacionEntrante'])){
                            $cuerpo .= "<option value='3' selected>".$str_opcion_norein_."</option>";
                        }else{
                            $cuerpo .= "<option value='3'>".$str_opcion_norein_."</option>";
                        }
                    }
                    
                    
                    $cuerpo .= "</select>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</td>";

                    if(!isset($_POST['tipificacionesBackoffice'])){
                        $cuerpo .= "<td class='col-md-1'>";
                        $cuerpo .= "<div class='form-group'>";
                        $cuerpo .= "<input type='text' class='form-control' placeholder='' value='".$key->MONOEF_CanHorProxGes__b."' name='txtHorasMas_".$key->LISOPC_ConsInte__b."' id='txtHorasMas_".$key->LISOPC_ConsInte__b."'>";
                        $cuerpo .= "</div>";
                        $cuerpo .= "</td>";
                    }

                    if(!isset($_POST['tipificacionesBackoffice'])){
                        $cuerpo .= "<td class='col-md-2'>";
                        $cuerpo .= "<div class='form-group'>";


                        $cuerpo .= "<select class='form-control' name='contacto_".$key->LISOPC_ConsInte__b."'>";

                        if($key->MONOEF_Contacto__b == 1){
                            $cuerpo .= "<option selected value='1'>".$str_opcion_number1."</option>";
                        }else{
                            $cuerpo .= "<option value='1'>".$str_opcion_number1."</option>";
                        }

                        if($key->MONOEF_Contacto__b == 2){
                            $cuerpo .= "<option selected value='2'>".$str_opcion_number2."</option>";
                        }else{
                            $cuerpo .= "<option value='2'>".$str_opcion_number2."</option>";
                        }

                        if($key->MONOEF_Contacto__b == 3){
                            $cuerpo .= "<option  selected value='3'>".$str_opcion_number3."</option>";
                        }else{
                            $cuerpo .= "<option value='3'>".$str_opcion_number3."</option>";
                        }

                        if($key->MONOEF_Contacto__b == 4){
                            $cuerpo .= "<option  selected value='4'>".$str_opcion_number4."</option>";
                        }else{
                            $cuerpo .= "<option value='4'>".$str_opcion_number4."</option>";
                        }

                        if($key->MONOEF_Contacto__b == 5){
                            $cuerpo .= "<option selected value='5'>".$str_opcion_number5."</option>";
                        }else{
                            $cuerpo .= "<option value='5'>".$str_opcion_number5."</option>";
                        }

                        if($key->MONOEF_Contacto__b == 6){
                            $cuerpo .= "<option selected value='6'>".$str_opcion_number6."</option>";
                        }else{
                            $cuerpo .= "<option value='6'>".$str_opcion_number6."</option>";
                        }

                        if($key->MONOEF_Contacto__b == 7){
                            $cuerpo .= "<option selected value='7'>".$str_opcion_number7."</option>";
                        }else{
                            $cuerpo .= "<option value='7'>".$str_opcion_number7."</option>";
                        }

                        $cuerpo .= "</select>";
                        $cuerpo .= "</div>";
                        $cuerpo .= "</td>";
                    }
                    
                    $cuerpo .= "<td class='col-md-3 col-xs-10'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<select name='estado_".$key->LISOPC_ConsInte__b."' class='form-control estadosCTX' id='estado_".$key->LISOPC_ConsInte__b."' placeholder='".$str_opcion_import_."'>";
                    $cuerpo .= $opcionEstado;
                    $cuerpo .= "</select>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</td>";
                    
                    $cuerpo .= "<td class='col-md-1 col-xs-2'>";
                    $cuerpo .= "<div class='form-group'>";
                    $cuerpo .= "<button class='btn btn-danger btn-sm deleteFirme form-control'  title='".$str_opcion_elimina."' type='button' id='".$key->LISOPC_ConsInte__b."'><i class='fa fa-trash-o'></i></button>";
                    $cuerpo .= "</div>";
                    $cuerpo .= "</td>";
                    
                    $cuerpo .= "</tr>";

                    echo $cuerpo;
                    echo "<script>$(function(){ $('#estado_".$key->LISOPC_ConsInte__b."').val('".$key->LISOPC_Valor____b."'); $('#estado_".$key->LISOPC_ConsInte__b."').val('".$key->LISOPC_Valor____b."').change(); });</script>";
                }
            }
        }
    }

    if(isset($_POST['getEnvioCorreoFormulario'])){
        if(isset($_POST['idioma'])){
            switch ($_POST['idioma']) {
                case 'en':
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;

                case 'es':
                    include(__DIR__."../../../../idiomas/text_es.php");
                    break;

                default:
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;
            }
        }

        $Lsql = "SELECT CORREO_SALIENTE_Asunto_b, CORREO_SALIENTE_Nombre_b, CORREO_SALIENTE_Para_b , CORREO_SALIENTE_ConsInte__b, ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS JOIN ".$BaseDatos_systema.".CORREO_SALIENTE ON ESTPAS_ConsInte__b = CORREO_SALIENTE_ConsInte__ESTPAS_b WHERE ESTPAS_ConsInte__CAMPAN_b = ".$_POST['idCampana']." AND ESTPAS_Tipo______b = 7";
        
        $res = $mysqli->query($Lsql);

        if($res->num_rows > 0){
            $campo = '<br/><table class="table table-hover" style="width:100%;">
                    <thead>
                        <tr>
                            <th>'.$str_nombre_mail_ms.'</th>
                            <th>'.$str_asunto_mail_ms.'</th>
                            <th>'.$str_para___mail_ms.'</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($key = $res->fetch_object()) {
                $campo .= '<tr>
                                <td>'.$key->CORREO_SALIENTE_Nombre_b.'</td>
                                <td>'.$key->CORREO_SALIENTE_Asunto_b.'</td>
                                <td>'.$key->CORREO_SALIENTE_Para_b.'</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success editarMail" idCorreo="'.$key->ESTPAS_ConsInte__b.'"><i class="fa fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger borrarMail" idCorreo="'.$key->CORREO_SALIENTE_ConsInte__b.'"><i class="fa fa-trash-o"></i></button>
                                </td>
                            </tr>';
            }

            $campo .= ' </tbody>
                    </table>';

            echo $campo;
        }else{
            echo "<br/><button class='btn btn-primary pull-right' type='button' id='btnCrearCampanaEnviar'>".$campan_conf_env."</button><br/><br/>";
        }
        
    }

    if(isset($_POST['crearPasoModificarEstrategia'])){

        $camanha___ = $_POST['idCampana'];
        $idPaso____ = $_POST['idPaso'];
        $LsqlEstpass = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$idPaso____;
        $resLSql = $mysqli->query($LsqlEstpass);
        $resDats = $resLSql->fetch_array();

        $LsqlGuion = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$camanha___;
        $resCampan = $mysqli->query($LsqlGuion);
        $datosCampan = $resCampan->fetch_array();


        $valida = '';
        $id_New_Paso = 0;
        $Lsql = "UPDATE ".$BaseDatos_systema.".ESTRAT SET ESTRAT_ConsInte__TIPO_ESTRAT_b = 3 WHERE ESTRAT_ConsInte__b = ".$resDats['ESTPAS_ConsInte__ESTRAT_b'];
        if($mysqli->query($Lsql) === true){
            $valida = 0;
        }else{
            echo $mysqli->error;
        }


        $id_Muestras = 0;
        $id_Guion = $datosCampan['CAMPAN_ConsInte__GUION__Pob_b'];
        $Lsql = "INSERT INTO ".$BaseDatos_systema.".MUESTR (MUESTR_Nombre____b, MUESTR_ConsInte__GUION__b) VALUES ('".$id_Guion."_MUESTRA_".rand()."', '".$id_Guion."')";
        if($mysqli->query($Lsql) === true){
            $id_Muestras = $mysqli->insert_id;
            /* toca asociarla al Paso */
           
            
            
            //echo "Entra aqui tambien y este es el id de la muestra".$id_Muestras;

            $CreateMuestraLsql = "CREATE TABLE `".$BaseDatos."`.`G".$id_Guion."_M".$id_Muestras."` (
                                      `G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b` int(10) NOT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_Estado____b` int(10) DEFAULT '0',
                                      `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b` int(10) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_NumeInte__b` int(10) DEFAULT '0',
                                      `G".$id_Guion."_M".$id_Muestras."_UltiGest__b` int(10) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_FecUltGes_b` datetime DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_ConUltGes_b` int(10) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_TienGest__b` varchar(253) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_MailEnvi__b` smallint(5) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_GesMasImp_b` int(10) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_FeGeMaIm__b` datetime DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_CoGesMaIm_b` int(10) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_GruRegRel_b` int(10) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_Comentari_b` longtext,
                                      `G".$id_Guion."_M".$id_Muestras."_EfeUltGes_b` smallint(5) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_EfGeMaIm__b` smallint(5) DEFAULT NULL,
                                      `G".$id_Guion."_M".$id_Muestras."_Activo____b` smallint(5) DEFAULT '-1',
                                      `G".$id_Guion."_M".$id_Muestras."_FecHorAge_b` datetime DEFAULT NULL,
                                      PRIMARY KEY (`G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b`),
                                      KEY `G".$id_Guion."_M".$id_Muestras."_Estado____b_Indice` (`G".$id_Guion."_M".$id_Muestras."_Estado____b`),
                                      KEY `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b_Indice` (`G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b`)
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            if($mysqli->query($CreateMuestraLsql) === true){
                //echo "Si creo la tabla";
                        //creamos el paso que toca para enviar los registros
                $LsqlPas = "INSERT INTO ".$BaseDatos_systema.".ESTPAS (ESTPAS_ConsInte__ESTRAT_b, ESTPAS_Nombre__b, ESTPAS_Tipo______b, ESTPAS_Loc______b, ESTPAS_ConsInte__CAMPAN_b, ESTPAS_ConsInte__MUESTR_b) VALUES (".$resDats['ESTPAS_ConsInte__ESTRAT_b'].", 'salMail', 7 , '351.671875 -182', ".$camanha___.", ".$id_Muestras.")";
                if($mysqli->query($LsqlPas) === true){
                    //Toca cuadrar todo lo demas , como la union
                    $id_New_Paso = $mysqli->insert_id;
                    /*Ahora toca meter el paso*/
                    $Lsql = "INSERT INTO ".$BaseDatos_systema.".ESTCON (ESTCON_Nombre____b, ESTCON_Comentari_b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_Coordenadas_b, ESTCON_FromPort_b , ESTCON_ToPort_b, ESTCON_ConsInte__ESTRAT_b, ESTCON_Consulta_sql_b, ESTCON_Tipo_Consulta_b, ESTCON_Tipo_Insercion_b, ESTCON_ConsInte_PREGUN_Fecha_b, ESTCON_ConsInte_PREGUN_Hora_b, ESTCON_Operacion_Fecha_b, ESTCON_Operacion_Hora_b, ESTCON_Cantidad_Fecha_b, ESTCON_Cantidad_Hora_b) VALUES('Conector' , 'Enviamos el mail a todos los registros', ".$idPaso____." , ".$id_New_Paso.", '[62.82509713949159,0,72.82509713949159,0,351.671875,0,351.671875,-73.2124514302542,351.671875,-146.4249028605084,351.671875,-156.4249028605084]', 'R' , 'B', ".$resDats['ESTPAS_ConsInte__ESTRAT_b']." , 'SELECT G".$datosCampan['CAMPAN_ConsInte__GUION__Pob_b']."_ConsInte__b as id FROM DYALOGOCRM_WEB.G".$datosCampan['CAMPAN_ConsInte__GUION__Pob_b']." LEFT JOIN  DYALOGOCRM_WEB.G".$datosCampan['CAMPAN_ConsInte__GUION__Pob_b']."_M".$datosCampan['CAMPAN_ConsInte__MUESTR_b']." ON G".$datosCampan['CAMPAN_ConsInte__GUION__Pob_b']."_ConsInte__b  = G".$datosCampan['CAMPAN_ConsInte__GUION__Pob_b']."_M".$datosCampan['CAMPAN_ConsInte__MUESTR_b']."_CoInMiPo__b' , '1', '0', '-1', '-1', '1', '1', '0', '0');";
                    if($mysqli->query($Lsql) === true){

                    }
                    $valida = 0;
                }else{
                    echo $mysqli->error;
                }
            }else{
                echo $mysqli->error;
            }
        }else{
            echo "No guardo la muestra => ".$mysqli->error;
        }
        


        if($valida == 0){
            echo json_encode( array('code' => 0 , 'numberPaso' => $id_New_Paso));
        }else{
            echo json_encode( array('code' => -1 , 'error' => $valida));
        }
    }


    if(isset($_POST['cargarEnlacesBDForm'])){
        if(isset($_POST['idioma'])){
            switch ($_POST['idioma']) {
                case 'en':
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;

                case 'es':
                    include(__DIR__."../../../../idiomas/text_es.php");
                    break;

                default:
                    include(__DIR__."../../../../idiomas/text_en.php");
                    break;
            }
        }

        /* Esto es los titulos y eso */
        $campo = '';      

        $LsqlPaso = "SELECT ESTPAS_ConsInte__CAMPAN_b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__b = ".$_POST['paso'];
        $resP = $mysqli->query($LsqlPaso);
        $datosPa = $resP->fetch_array();

        $Lsql = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__GUION__Gui_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b =  ".$datosPa['ESTPAS_ConsInte__CAMPAN_b'];
        $resCam = $mysqli->query($Lsql);
        $dataCamp = $resCam->fetch_array();

        /* Ahora toca buscar los campos de cada uno */
        $datosPob = array();
        $Lsql = "SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b,PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCamp['CAMPAN_ConsInte__GUION__Pob_b']." AND (PREGUN_Tipo______b != 9 or PREGUN_Tipo______b != 12 ) AND PREGUN_FueGener_b != 3 ;";
        $res_Resultado = $mysqli->query($Lsql);
        while ($key = $res_Resultado->fetch_object()) {
            $datosPob[] = $key;
        }

        array_push($datosPob,(object)["PREGUN_ConsInte__b"=>"-1","PREGUN_Texto_____b"=>"ID","PREGUN_Tipo______b"=>"3"]);
        array_push($datosPob,(object)["PREGUN_ConsInte__b"=>"-2","PREGUN_Texto_____b"=>"FECHA INSERCION","PREGUN_Tipo______b"=>"5"]);

        $datosScr = array();
        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$dataCamp['CAMPAN_ConsInte__GUION__Gui_b']." AND (PREGUN_Tipo______b != 9 or PREGUN_Tipo______b != 12 ) AND PREGUN_FueGener_b != 3 ;";
        $res_Resultado = $mysqli->query($Lsql);
        while ($key = $res_Resultado->fetch_object()) {
            $datosScr[] = $key;
        }        

        $Lsql = "SELECT * FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$datosPa['ESTPAS_ConsInte__CAMPAN_b']." ORDER BY CAMINC_TexPrePob_b;";
        $res  = $mysqli->query($Lsql);
        $i = 0;
        while ($resK = $res->fetch_object()) {
            
            $optionsP ='<option value="0">'.$str_seleccione.'</option>';
            $optionsP .='<option value="G'.$dataCamp['CAMPAN_ConsInte__GUION__Pob_b'].'_ConsInte__b">ID</option>';
            $optionsP .='<option value="G'.$dataCamp['CAMPAN_ConsInte__GUION__Pob_b'].'_FechaInsercion">FECHA INSERCION</option>';
            $tipoDato = 0;

            foreach ($datosPob as $key) {
                if($key->PREGUN_ConsInte__b == $resK->CAMINC_ConsInte__CAMPO_Pob_b){
                    $tipoDato = $key->PREGUN_Tipo______b;
                    $optionsP .= '<option value="'.$key->PREGUN_ConsInte__b.'" selected>'.$key->PREGUN_Texto_____b.'</option>';
                }else{
                    $optionsP .= '<option value="'.$key->PREGUN_ConsInte__b.'">'.$key->PREGUN_Texto_____b.'</option>';    
                }   
            }

            $optionsS ='<option value="0">'.$str_seleccione.'</option>';
            foreach ($datosScr as $key) {
                if($tipoDato == $key->PREGUN_Tipo______b){
                    if($key->PREGUN_ConsInte__b == $resK->CAMINC_ConsInte__CAMPO_Gui_b){
                        $optionsS .= '<option value="'.$key->PREGUN_ConsInte__b.'" selected>'.($key->PREGUN_Texto_____b).'</option>';
                    }else{
                        $optionsS .= '<option value="'.$key->PREGUN_ConsInte__b.'">'.($key->PREGUN_Texto_____b).'</option>';    
                    }
                }
            }    
            

            $campo .= "<tr id='".$i."'>
                        <input type='hidden' name='idCamincYa_".$i."' value='".$resK->CAMINC_ConsInte__b."'>
                        <td><select class='form-control' name='datosPob_".$resK->CAMINC_ConsInte__b."' id='datosPob_".$resK->CAMINC_ConsInte__b."'>".$optionsP."</select></td>
                        <td><select class='form-control' name='datosGui_".$resK->CAMINC_ConsInte__b."' id='datosGui_".$resK->CAMINC_ConsInte__b."'>".$optionsS."</select></td>
                        <td style='text-align:center;'><button title='".$campan_dbfor_3_."' class='btn btn-danger btn-sm btnDeleteEsto' type='button' idCam='".$resK->CAMINC_ConsInte__b."' fila='".$i."'><i class='fa fa-trash-o'></i></button></td>
                    ";

            $i++;
        }

        echo $campo;

    }

    if(isset($_POST['deleteCaminc'])){

        $idcaminc = $_POST['idCaminc'];
        
        $Lsql = "DELETE FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__b = ".$idcaminc;
        
        if($mysqli->query($Lsql) === true){
            
            echo "0"; 
        
        }else{  
            
            echo "error => ".$mysqli->error;
        
        }
    }


    if(isset($_POST['getListasDeEsecampo'])){
        $Lsql = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['lista'];
        $res = $mysqli->query($Lsql);
        $datosLISO = $res->fetch_array();


        $Lsql = "SELECT LISOPC_ConsInte__b, LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$datosLISO['PREGUN_ConsInte__OPCION_B']." ORDER BY LISOPC_Nombre____b ASC";
        $res = $mysqli->query($Lsql);
   
        $datosL = array();
        $i = 0;
        while ($key = $res->fetch_object()) {
            $datosL[$i]['LISOPC_Nombre____b'] = $key->LISOPC_Nombre____b;
            $datosL[$i]['LISOPC_ConsInte__b'] = $key->LISOPC_ConsInte__b;
            $i++;
        }

        echo json_encode($datosL);
    
    }

    if(isset($_POST['traerSecciones'])){

        $botId = $mysqli->real_escape_string($_POST['botId']);

        $sql = "SELECT id, nombre FROM {$dyalogo_canales_electronicos}.dy_base_autorespuestas WHERE id_estpas = {$botId}";
        $res = $mysqli->query($sql);

        $datos = [];
        $existe = false;

        if($res->num_rows > 0){

            $i = 0;
            while ($row = $res->fetch_object()) {
                $datos[$i] = $row;
                $i++;
            }

            $existe = true;
        }

        echo json_encode([
            "existe" => $existe,
            "secciones" => $datos
        ]);
    }

    if(isset($_POST['validaMuestra'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        if($id){
            $sql=$mysqli->query("SELECT CAMPAN_ConsInte__GUION__Pob_b AS bd, CAMPAN_ConsInte__MUESTR_b AS muestra FROM {$BaseDatos_systema}.CAMPAN WHERE CAMPAN_ConsInte__b={$id}");
            if($sql && $sql->num_rows > 0){
                $sql=$sql->fetch_object();
                $sqlCountBD=$mysqli->query("SELECT COUNT(1) AS total FROM {$BaseDatos}.G{$sql->bd}");
                $sqlCountMuestra=$mysqli->query("SELECT COUNT(1) AS total FROM {$BaseDatos}.G{$sql->bd}_M{$sql->muestra}");
                
                if($sqlCountBD && $sqlCountMuestra){
                    $sqlCountBD=$sqlCountBD->fetch_object();
                    $sqlCountMuestra=$sqlCountMuestra->fetch_object();
                    $totalBD=$sqlCountBD->total;
                    $totalMuestra=$sqlCountMuestra->total;

                    if($totalBD > 0){
                        if($totalMuestra > 0){
                            echo '1';
                        }else{
                            echo '2';
                        }
                    }else{
                        echo '1';
                    }
                }else{
                    echo '0';
                }
            }else{
                echo '0';
            }
        }else{
            echo '0';
        }
    }



 /**
 *BGCR - Esta funcion retorna el alias de un campo en especifico sobre la vista
 *@param view = nombre de la visa, campo = id del campo
 *@return string. = nombre de alias
 */


function getNameColumnView(string $view,string $campo):string
{
    global $mysqli;
    global $BaseDatos;
    $sql=$mysqli->query("SHOW CREATE VIEW {$BaseDatos}.{$view}");

    $exp='';
    if($sql){
        $sql=$sql->fetch_object();
        $var="Create View";
        $exp=explode($campo,$sql->$var)[1];
        $exp=explode(" AS ",$exp)[1];
        $exp=explode(",",$exp)[0];
        $exp=str_replace("`","",$exp);

    }
    return $exp;
}



/**
 *JDBD - En esta funcion retornaremos el nombre de la vista segun su ID de base.
 *BGCR - Si pasamos el id de la muestra retornara el nombre de la vista de la muestra
 *@param int.
 *@return string. 
 */

function nombreVistas($intIdBd_p, $intIdMuestra_p = null){

    global $mysqli;
    global $BaseDatos;
    global $BaseDatos_systema;
    global $BaseDatos_telefonia;
    global $dyalogo_canales_electronicos;
    global $BaseDatos_general;


    if($intIdMuestra_p !== null){
    //BGCR - Buscamos el estrat del paso

        $strSQLEstrat_t = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM {$BaseDatos_systema}.CAMPAN JOIN {$BaseDatos_systema}.ESTPAS ON CAMPAN_ConsInte__b = ESTPAS_ConsInte__CAMPAN_b WHERE CAMPAN_ConsInte__GUION__Pob_b = {$intIdBd_p} AND CAMPAN_ConsInte__MUESTR_b = {$intIdMuestra_p} LIMIT 1"; 
        
        $resSQLEstrat_t = $mysqli->query($strSQLEstrat_t);


        // Validamos si obtuvimos algo con la campaña en caso de no obtener nada validamos el paso por la muestra
        if($resSQLEstrat_t->num_rows > 0){

            $valSQLEstrat_t = $resSQLEstrat_t->fetch_object()->ESTPAS_ConsInte__ESTRAT_b;
            
        }else{

            $strSQLEstrat_t = "SELECT ESTPAS_ConsInte__ESTRAT_b FROM {$BaseDatos_systema}.ESTPAS WHERE ESTPAS_ConsInte__MUESTR_b = {$intIdMuestra_p} LIMIT 1"; 

            $resSQLEstrat_t = $mysqli->query($strSQLEstrat_t);
            $valSQLEstrat_t = $resSQLEstrat_t->fetch_object()->ESTPAS_ConsInte__ESTRAT_b;


        }

        $strSQLVista_t = "SELECT nombre FROM {$BaseDatos_general}.vistas_generadas where id_estrat = {$valSQLEstrat_t} and nombre like '%{$intIdMuestra_p}%' ORDER BY id DESC LIMIT 1"; 

        $resSQLVista_t = $mysqli->query($strSQLVista_t);
    
        return $resSQLVista_t->fetch_object()->nombre;

    }else{
        //JDBD - Buscamos el nombre de la vista MYSQL.
        $strSQLVista_t = "SELECT nombre FROM ".$BaseDatos_general.".vistas_generadas WHERE id_guion = ".$intIdBd_p." ORDER BY id DESC LIMIT 1"; 

        $resSQLVista_t = $mysqli->query($strSQLVista_t);

        return $resSQLVista_t->fetch_object()->nombre;
    }

}

?>