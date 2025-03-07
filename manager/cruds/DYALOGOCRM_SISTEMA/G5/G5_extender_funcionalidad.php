<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");
    require_once('../../../../helpers/parameters.php');

    function NombreParaFormula($strNombre_p){   
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

    if (isset($_POST["getTipAccion"])) {
        
        $strSQLAccion_t = "SELECT * FROM ".$BaseDatos_systema.".ACCIONTIPI WHERE md5(concat('".clave_get."', ACCIONTIPI_ConsInte_GUION__b)) = '".$_POST["idGuion"]."'";
        $resSQLAccion_t = $mysqli->query($strSQLAccion_t);

        if ($resSQLAccion_t->num_rows > 0) {

            $arrAccion_t = [];
            while ($row = $resSQLAccion_t->fetch_object()) {
                $arrAccion_t[] = $row; 
            }

            echo json_encode($arrAccion_t);            

        }


    }

    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan

    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos, etc, en fin extender mas los formularios en PHP

    //VALIDAR SI UN CAMPO SE PUEDE DEJAR COMO REQUERIDO
    if(isset($_GET['validaRequerido'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        if($id){
            //VALIDAR SI LA SECCIÓN A LA QUE PERTENCE ESTE CAMPO NO ESTA CONFIGURADA EN SALTOS POR SECCIÓN
            $sqlSeccion=$mysqli->query("SELECT PREGUN_ConsInte__SECCIO_b AS idSeccion FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b={$id}");
            if($sqlSeccion){
                $sqlSeccion=$sqlSeccion->fetch_object();
                $sql=$mysqli->query("SELECT COUNT(1) AS veces FROM {$BaseDatos_systema}.PRSASA WHERE PRSASA_ConsInte__SECCIO_b={$sqlSeccion->idSeccion}");
                if($sql){
                    $sql=$sql->fetch_object();
                    if($sql->veces == 0){
                        // EL CAMPO NO PERTENECE A UNA SECCIÓN CONFIGURADA EN SALTOS POR SECCIÓN, VALIDAMOS AHORA SI EL CAMPO ESTA CONFIGURADO EN UN SALTO
                        $sqlCampo=$mysqli->query("SELECT COUNT(1) AS veces FROM {$BaseDatos_systema}.PRSASA WHERE PRSASA_ConsInte__PREGUN_b={$id}");
                        if($sqlCampo){
                            $sqlCampo=$sqlCampo->fetch_object();
                            if($sqlCampo->veces ==0 ){
                                //EL CAMPO SI PUEDE SER REQUERIDO POR QUE NO SE USA EN UN SALTO Ó EN UNA SECCIÓN CONFIGURADA EN SALTOS POR SECCIÓN
                                echo json_encode(array('estado'=>'ok'));
                            }else{
                                echo json_encode(array('estado'=>'warning','mensaje'=>"El campo esta configurado en un salto"));
                            }
                        }else{
                            echo json_encode(array('estado'=>'error','mensaje'=>"No se logro identificar si el campo esta configurado en un salto"));
                        }
                    }else{
                        echo json_encode(array('estado'=>'warning','mensaje'=>"El campo pertenece a una sección que esta configurada en saltos por sección"));
                    }
                }else{
                    echo json_encode(array('estado'=>'error','mensaje'=>"No se logro identificar si la sección a la que pertenece el campo esta configurada en saltos por sección"));
                }
            }else{
                echo json_encode(array('estado'=>'error','mensaje'=>"No se logro identificar la sección a la que pertenece el campo"));
            }
        }else{
            echo json_encode(array('estado'=>'error','mensaje'=>"No se envio el identificador de campo"));
        }
    }

    //VALIDAR SI UN CAMPO NO ES REQUERIDO Y SE PUEDE CONFIGURAR PARA SALTOS
    if(isset($_GET['validaCampoParaSalto'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        if($id){
            $sql=$mysqli->query("SELECT PREGUN_IndiRequ__b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b={$id}");
            if($sql){
                $sql=$sql->fetch_object();
                if($sql->PREGUN_IndiRequ__b == 0){
                    echo json_encode(array('estado'=>'ok'));
                }else{
                    echo json_encode(array('estado'=>'warning','mensaje'=>"El campo esta configurado como requerido"));
                }
            }else{
                echo json_encode(array('estado'=>'error','mensaje'=>'No se logro validar el campo'));
            }
        }else{
            echo json_encode(array('estado'=>'error','mensaje'=>"No se envio el identificador del campo"));
        }
    }
    //VALIDAR SI UNA SECCIÓN NO TIENE CAMPOS REQUERIDOS Y SE PUEDE CONFIGURAR PARA SALTOS
    if(isset($_GET['validaSeccionParaSalto'])){
        $id=isset($_POST['id']) ? $_POST['id'] : false;
        if($id){
            $sql=$mysqli->query("SELECT COUNT(1) AS veces FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__SECCIO_b={$id} AND PREGUN_IndiRequ__b=-1");
            if($sql){
                $sql=$sql->fetch_object();
                if($sql->veces == 0){
                    echo json_encode(array('estado'=>'ok'));
                }else{
                    echo json_encode(array('estado'=>'warning','mensaje'=>"la sección contiene campos requeridos"));
                }
            }else{
                echo json_encode(array('estado'=>'error','mensaje'=>"No se logro validar la sección"));
            }
        }else{
            echo json_encode(array('estado'=>'error','mensaje'=>"No se envio el identificador de la sección"));
        }
    }

    if(isset($_GET["addPregunWS"])){
        //guion:guion, texto:texto, ws:ws, metodo:metodo, oper:oper}
        $guion=isset($_POST["guion"]) ? $_POST["guion"] : false;
        $texto=isset($_POST["texto"]) ? $_POST["texto"] : false;
        $ws=isset($_POST["ws"]) ? $_POST["ws"] : false;
        $metodo=isset($_POST["metodo"]) ? $_POST["metodo"] : false;
        $oper=isset($_POST["oper"]) ? $_POST["oper"] : false;
        $id=isset($_POST["id"]) ? $_POST["id"] : false;

        if($oper){
            if($oper=='add'){
                if($guion && $texto && $ws && $metodo){
                    $sql=$mysqli->query("INSERT INTO {$BaseDatos_systema}.PREGUN (PREGUN_ConsInte__GUION__b,PREGUN_Texto_____b,PREGUN_Tipo______b,PREGUN_consInte__ws_B,PREGUN_FormaIntegrarWS_b) VALUES ({$guion},'{$texto}',17,{$ws},{$metodo})");
                    echo json_encode(array('mensaje'=>'ok', 'id'=>$mysqli->insert_id));
                }else{
                    echo json_encode(array('mensaje'=>'Hubo un error al procesar la solicitud: Datos incompletos (03)'));
                }
            }elseif($oper=='delete'){
                if($id){
                    //ELIMINAR RELACION CON WEBSERVICE
                    $delCamp = $mysqli->query("DELETE FROM {$BaseDatos_systema}.CAMCONWS WHERE (CAMCONWS_ConsInte__PREGUN__b={$_POST['id']} OR CAMCONWS_ConsInte__PREGUN__llave_b={$_POST['id']})");

                    //ELIMINAR RELACION CON LISTAS DEL WEBSERVICE
                    $delCamp = $mysqli->query("DELETE FROM {$BaseDatos_systema}.LISOPCWS WHERE LISOPCWS_ConsInte__PREGUN__llave_b={$_POST['id']}");

                    //ELIMINAR DE PREGUN
                    $sql=$mysqli->query("DELETE FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b={$id}");
                    echo json_encode(array('mensaje'=>'ok', 'id'=>$id));
                }else{
                    echo json_encode(array('mensaje'=>'Hubo un error al procesar la solicitud: Datos incompletos (04)'));
                }
            }elseif($oper=='actualizar'){
                if($id && $ws && $metodo && $texto){
                    $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_Texto_____b='{$texto}',PREGUN_consInte__ws_B={$ws},PREGUN_FormaIntegrarWS_b={$metodo} WHERE PREGUN_ConsInte__b={$id}");
                    echo json_encode(array('mensaje'=>'ok', 'id'=>$id));
                }else{
                    echo json_encode(array('mensaje'=>'Hubo un error al procesar la solicitud: Datos incompletos (05)'));
                }
            }else{
                echo json_encode(array('mensaje'=>'Hubo un error al procesar la solicitud: Datos incompletos (02)'));
            }
        }else{
            echo json_encode(array('mensaje'=>'Hubo un error al procesar la solicitud: Datos incompletos (01)'));
        }
    }

    if(isset($_GET["getPregunWS"])){
        $guion=isset($_POST["guion"]) ? $_POST["guion"] : false;
        if($guion){
            $sql=$mysqli->query("SELECT PREGUN_ConsInte__b AS id,PREGUN_Texto_____b AS texto,PREGUN_consInte__ws_B AS ws,PREGUN_FormaIntegrarWS_b AS metodo FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__GUION__b={$guion} AND PREGUN_Tipo______b=17 AND PREGUN_FormaIntegrarWS_b !=2");
            if($sql && $sql->num_rows>0){
                $data=array();
                while($fila = $sql->fetch_object()){
                    $data[]=$fila;
                }
                echo json_encode(array('estado'=>2,'data'=>$data));
            }else{
                echo json_encode(array('estado'=>1));
            }
        }else{
            echo json_encode(array('estado'=>0,'mensaje'=>'No se pudo obtener las integraciones del formulario'));
        }
    }

    if(isset($_GET['camposGuion_sub'])){
        if(isset($_POST['seccion']) && $_POST['seccion'] !='' && $_POST['seccion']!=0){
            $intTipoSeccion_t=$mysqli->query("select G7_C38 from {$BaseDatos_systema}.G7 where G7_ConsInte__b={$_POST['seccion']}");
            if($intTipoSeccion_t && $intTipoSeccion_t->num_rows ==1){
                $intTipoSeccion_t=$intTipoSeccion_t->fetch_object();
                $intTipoSeccion_t=$intTipoSeccion_t->G7_C38;   
            }
        }
        
        if(isset($intTipoSeccion_t) && $intTipoSeccion_t== '2'){
            $strCondSeccion="(G7_C38 = 1 or G7_C38=4 or G7_C38=2)";
            $booCalidad=true;
        }else{
            $strCondSeccion="(G7_C38 = 1 or G7_C38=4 or G7_C38=3)";
        }
        
        $strSQLCampos_t = "SELECT  G6_ConsInte__b as id , G6_C39 as nombre, G6_C323 as mostrarSubForm,G6_C40 as tipoCampo,G7_C38 as TipoSeccion FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE G6_C40 != 12 AND G6_C40 != 9 AND G6_C207 = ".$_POST['guion']." AND ".$strCondSeccion." AND G6_C209 != 3 and G6_C40 !=14;";
        $resSQLCampos_t = $mysqli->query($strSQLCampos_t);
        if($resSQLCampos_t && $resSQLCampos_t->num_rows > 0 ){
            
            $arrData_t = [];
            while($row = $resSQLCampos_t->fetch_object()){
            $arrData_t[] = $row;
            }
            echo json_encode($arrData_t);        
        }
    }

    if(isset($_GET['camposGuion'])){
        $booCalidad=false;
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

        if(isset($_POST['seccion']) && $_POST['seccion'] !='' && $_POST['seccion']!=0){
            $intTipoSeccion_t=$mysqli->query("select G7_C38 from {$BaseDatos_systema}.G7 where G7_ConsInte__b={$_POST['seccion']}");
            if($intTipoSeccion_t && $intTipoSeccion_t->num_rows ==1){
                $intTipoSeccion_t=$intTipoSeccion_t->fetch_object();
                $intTipoSeccion_t=$intTipoSeccion_t->G7_C38;   
            }
        }else{
            
        }
        if(isset($intTipoSeccion_t) && $intTipoSeccion_t== '2'){
            $strCondSeccion="(G7_C38 = 1 or G7_C38=4 or G7_C38=2)";
            $booCalidad=true;
        }else{
            $strCondSeccion="(G7_C38 = 1 or G7_C38=4)";
        }
        
        if(is_numeric($_POST['guion'])){
            $_POST['guion']=url::urlSegura($_POST['guion']);
        }
        $strSQLCampos_t = "SELECT  G6_ConsInte__b as id , G6_C39 as nombre, G6_C323 as mostrarSubForm,G6_C40 as tipoCampo,G7_C38 as TipoSeccion, G6_C44 as lista, G6_C51 as requerido FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE G6_C40 != 12 AND G6_C40 != 9 AND G6_C40 != 17 AND G6_C40 != 16 AND md5(concat('".clave_get."', G6_C207)) = '".$_POST["guion"]."' AND ".$strCondSeccion." AND G6_C209 != 3 and G6_C40 !=14;";
        $resSQLCampos_t = $mysqli->query($strSQLCampos_t);
      
      	if(isset($_POST['esMaster'])){
            if(isset($_POST['idioma'])){}
            $arrData_t = [];
            while ($key = $resSQLCampos_t->fetch_object()) {
                if($booCalidad){
                    if(isset($_POST['numero'])){
                        $arrData_t[] = $key;               
                    }else{
                        if($key->TipoSeccion==1 || $key->TipoSeccion==2){
                            echo "<option requerido='{$key->requerido}' value='".$key->id."' tipo='".$key->tipoCampo."'>".($key->nombre)."</option>";    
                        }
                    }
                }else{
                    if(isset($_POST['numero'])){
                        $arrData_t[] = $key;
                    }else{
                        if($key->TipoSeccion==1){
                            echo "<option requerido='{$key->requerido}' value='".$key->id."'>".($key->nombre)."</option>";    
                        }
                    }
                }
            }
            if(count($arrData_t) > 0){
                echo json_encode($arrData_t);
            }
        }else{
          $arrData_t = [];
          while($row = $resSQLCampos_t->fetch_object()){
            $arrData_t[] = $row;
          }
            echo json_encode($arrData_t);
        }
				
    }    

    if(isset($_GET['camposGuionSaltos'])){
        if(isset($_POST['seccion']) && $_POST['seccion'] !='' && $_POST['seccion']!=0){
            $intTipoSeccion_t=$mysqli->query("select G7_C38 from {$BaseDatos_systema}.G7 where G7_ConsInte__b={$_POST['seccion']}");
            if($intTipoSeccion_t && $intTipoSeccion_t->num_rows ==1){
                $intTipoSeccion_t=$intTipoSeccion_t->fetch_object();
                $intTipoSeccion_t=$intTipoSeccion_t->G7_C38;   
            }
        }
        if(isset($intTipoSeccion_t) && $intTipoSeccion_t== '2'){
            $strCondSeccion="(G7_C38 = 1 or G7_C38=4 or G7_C38=2)";
            $booCalidad=true;
        }else{
            $strCondSeccion="(G7_C38 = 1 or G7_C38=4)";
        }
        
        if(is_numeric($_POST['guion'])){
            $_POST['guion']=url::urlSegura($_POST['guion']);
        }
        
        $strSQLCampos_t = "SELECT  G6_ConsInte__b as id , G6_C39 as nombre, G6_C323 as mostrarSubForm,G6_C40 as tipoCampo,G7_C38 as TipoSeccion, G6_C44 as lista, G6_C51 as requerido FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE G6_C40 != 12 AND G6_C40 != 17 AND G6_C40 != 16 AND md5(concat('".clave_get."', G6_C207)) = '".$_POST["guion"]."' AND ".$strCondSeccion." AND G6_C209 != 3 and G6_C40 !=14;";
        $resSQLCampos_t = $mysqli->query($strSQLCampos_t);
      

        while ($key = $resSQLCampos_t->fetch_object()) {
            if($key->TipoSeccion==1){
                $requerido='';
                if($key->requerido == '-1'){
                    $requerido="_R";
                }
                echo "<option value='".$key->id."'>".($key->nombre)."</option>";
            }
        }	
    }

    if(isset($_GET['camposGuionSeccion'])){
        if(is_numeric($_POST['guion'])){
            $_POST['guion']=url::urlSegura($_POST['guion']);
        }
        
        $strSQLCampos_t = "SELECT SECCIO_Nombre____b AS nombre, SECCIO_ConsInte__b as id FROM {$BaseDatos_systema}.SECCIO WHERE md5(concat('".clave_get."', SECCIO_ConsInte__GUION__b)) = '{$_POST['guion']}' AND SECCIO_TipoSecc__b = 1";
        $resSQLCampos_t = $mysqli->query($strSQLCampos_t);
      
        
        while ($key = $resSQLCampos_t->fetch_object()) {
            $requerido='';
            //VALIDAR SI LA SECCIÓN TIENE CAMPOS REQUERIDOS
            $sqlSeccion=$mysqli->query("SELECT PREGUN_IndiRequ__b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_IndiRequ__b=-1 AND PREGUN_ConsInte__SECCIO_b={$key->id}");
            if($sqlSeccion && $sqlSeccion->num_rows > 0){
                $requerido='_R';
            }
            echo "<option value='".$key->id."'>".($key->nombre)."</option>";
        }	
    }

    if(isset($_GET['traehora'])){
        if(isset($_POST['campo'])){
            $sql=$mysqli->query("Select PREGUN_HoraMini__b,PREGUN_HoraMaxi__b,PREGUN_Default___b from {$BaseDatos_systema}.PREGUN where PREGUN_ConsInte__b={$_POST['campo']}");
            $arrHoras=[];
            if($sql){
                $sql=$sql->fetch_object();
                $arrHoras['defecto']=$sql->PREGUN_Default___b;
                $arrHoras['minima']=$sql->PREGUN_HoraMini__b;
                $arrHoras['maxima']=$sql->PREGUN_HoraMaxi__b;
            }else{
                $arrHoras['defecto']=null;
                $arrHoras['minima']=null;
                $arrHoras['maxima']=null;
            }
            echo json_encode($arrHoras);
        }
    }

    if(isset($_GET['opcionLista'])){
        $sql=$mysqli->query("SELECT LISOPC_Nombre____b AS nombre, LISOPC_ConsInte__b AS valor FROM {$BaseDatos_systema}.LISOPC WHERE LISOPC_ConsInte__OPCION_b={$_POST['lista']}");
        if($sql && $sql->num_rows > 0){
            while($key = $sql->fetch_object()){
                if($key->nombre !='' && $key->nombre != null){
                    echo "<option value='{$key->valor}'>{$key->nombre}</option>";
                }
            }
        }
    }

//    *NBG*11-05-2021*COMUNICACION ENTRE FORMULARIO Y SUBFORMULARIO
    if(isset($_GET['TraerCamposSubformulario'])){
        $padre=isset($_POST['padre']) ? $_POST['padre'] :false;
        $hijo=isset($_POST['hijo']) ? $_POST['hijo'] :false;
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['padre']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $padre=$sqlIdGuion->id;
        }
        if(is_numeric($padre) && is_numeric($hijo)){
            $arrSeccionPadre_t= array();
            $arrSeccionHijo_t= array();
            $count=0;
            if($padre == $hijo){
                $condicion=$padre;
                $igual=true;
            }else{
                $condicion=$padre.",".$hijo;
                $igual=false;
            }
            $sqlSeccion=$mysqli->query("SELECT B.PREGUN_ConsInte__b AS ID_PREGUN, B.PREGUN_Texto_____b AS TEXTO_PREGUN, B.PREGUN_Tipo______b AS TIPO_PREGUN, B.PREGUN_ConsInte__GUION__b AS GUION FROM DYALOGOCRM_SISTEMA.SECCIO JOIN DYALOGOCRM_SISTEMA.PREGUN B ON SECCIO_ConsInte__b=B.PREGUN_ConsInte__SECCIO_b WHERE SECCIO_ConsInte__GUION__b IN ($condicion) AND SECCIO_TipoSecc__b=1 AND B.PREGUN_Tipo______b !=12 AND B.PREGUN_Tipo______b !=9");
            
            if($sqlSeccion && $sqlSeccion->num_rows>0){
                while($seccion = $sqlSeccion->fetch_object()){
                    if($igual){
                        $arrSeccionPadre_t[$count]['id']=$seccion->ID_PREGUN;
                        $arrSeccionPadre_t[$count]['texto']=$seccion->TEXTO_PREGUN;
                        $arrSeccionPadre_t[$count]['tipo']=$seccion->TIPO_PREGUN;
                    }else{
                        if($seccion->GUION == $padre){
                            $arrSeccionPadre_t[$count]['id']=$seccion->ID_PREGUN;
                            $arrSeccionPadre_t[$count]['texto']=$seccion->TEXTO_PREGUN;
                            $arrSeccionPadre_t[$count]['tipo']=$seccion->TIPO_PREGUN;
                        }elseif($seccion->GUION == $hijo){
                            $arrSeccionHijo_t[$count]['id']=$seccion->ID_PREGUN;
                            $arrSeccionHijo_t[$count]['texto']=$seccion->TEXTO_PREGUN;
                            $arrSeccionHijo_t[$count]['tipo']=$seccion->TIPO_PREGUN;
                        }
                    }
                    $count++;
                }
                
                if($igual){
                    $arrSeccionHijo_t=$arrSeccionPadre_t;
                }
                
                echo json_encode(array('estado'=>'ok','padre'=>$arrSeccionPadre_t,'hijo'=>$arrSeccionHijo_t));
            }else{
                echo json_encode(array('estado'=>'0','mensaje'=>'Error de lectura'));
            }
        }else{
           echo json_encode(array('estado'=>'0','mensaje'=>'Error de información'));
        }
    }

    if(isset($_GET['insertarcamposcomunicacionsub'])){
        $total=isset($_POST['conteo']) ? $_POST['conteo'] :false;
        
        if($total && is_numeric($total)){
            for($i=0; $i<$total; $i++){
                $guionPadre=isset($_POST['padre_'.$i]) ? $_POST['padre_'.$i] :false;
                $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$guionPadre."'");
                if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                    $sqlIdGuion=$sqlIdGuion->fetch_object();
                    $guionPadre=$sqlIdGuion->id;
                }
                $campoPadre=isset($_POST['select_padre_'.$i]) ? $_POST['select_padre_'.$i] :false;
                $guionHijo=isset($_POST['hijo_'.$i]) ? $_POST['hijo_'.$i] :false;
                $campoHijo=isset($_POST['select_hijo_'.$i]) ? $_POST['select_hijo_'.$i] :false;
                $id=isset($_POST['comunicacion_'.$i]) ? $_POST['comunicacion_'.$i] :false;
                $resultado=1;
                
                if($guionPadre && $campoPadre && $guionHijo && $campoHijo){
                    if(!$id){
                        $sql=$mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.COMUFORM VALUES (NULL,{$guionPadre},{$campoPadre},{$guionHijo},{$campoHijo})");
                    }else{
                        $sql=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.COMUFORM  SET COMUFORM_Guion_Padre_b={$guionPadre}, COMUFORM_IdPregun_Padre_b={$campoPadre}, COMUFORM_Guion_hijo_b={$guionHijo}, COMUFORM_IdPregun_hijo_b={$campoHijo} WHERE COMUFORM_Id_b={$id}");
                    }
                    if(!$sql){
                        $resultado=0;
                    }
                }
            }
            
            echo $resultado;
        }else{
            echo '0';
        }
    }

    if(isset($_GET['GetComunicacion'])){
        $padre=isset($_POST['padre']) ? $_POST['padre'] : false;
        $hijo=isset($_POST['hijo']) ? $_POST['hijo'] : false;
        
        if($padre && $hijo){
            $sql=$mysqli->query("SELECT * FROM DYALOGOCRM_SISTEMA.COMUFORM WHERE md5(concat('".clave_get."', COMUFORM_Guion_Padre_b)) = '".$padre."' AND COMUFORM_Guion_hijo_b={$hijo}");
            if($sql && $sql->num_rows>0){
                $campos=array();
                $i=0;
                while($fila=$sql->fetch_object()){
                    $campos[$i]['idComunicacion']=$fila->COMUFORM_Id_b;
                    $campos[$i]['campoPadre']=$fila->COMUFORM_IdPregun_Padre_b;
                    $campos[$i]['campoHijo']=$fila->COMUFORM_IdPregun_hijo_b;
                    $i++;
                }
                echo json_encode(array('mensaje'=>'ok','campos'=>$campos));
            }else{
                echo json_encode(array('mensaje'=>'sin filas'));
            }
        }else{
            echo json_encode(array('mensaje'=>'Sin datos'));
        }
    }

    if(isset($_GET['EliminarComunicacion'])){
        $id=isset($_POST['id']) ? $_POST['id'] :false;
        if($id){
            if($mysqli->query("DELETE FROM DYALOGOCRM_SISTEMA.COMUFORM WHERE COMUFORM_Id_b={$id}")){
                echo '1';
            }else{
                echo '0';
            }
        }else{
            echo '0';
        }
    }

    if(isset($_GET['TraerOptionMailSms'])){
        $operacion=isset($_POST['operacion']) ? $_POST['operacion'] :false;
        
        if($operacion && isset($_SESSION['HUESPED'])){
            
            if($operacion=='mail'){
                $sql=$mysqli->query("SELECT id,direccion_correo_electronico as cuenta FROM dyalogo_canales_electronicos.dy_ce_configuracion WHERE id_huesped={$_SESSION['HUESPED']} AND estado=1");
                if($sql && $sql->num_rows>0){
                    $filas=array();
                    $i=0;
                    while($fila = $sql->fetch_object()){
                        $filas[$i]['id']=$fila->id;
                        $filas[$i]['cuenta']=$fila->cuenta;
                        $i++;
                    }
                    echo json_encode(array('estado'=>'ok','mensaje'=>$filas));
                }else{
                    echo json_encode(array('estado'=>0,'mensaje'=>'No hay correos configurados'));    
                }
            }elseif($operacion=='sms'){
                $sql=$mysqli->query("SELECT id,proveedor as cuenta FROM dy_sms.configuracion WHERE id_huesped={$_SESSION['HUESPED']}");
                if($sql && $sql->num_rows>0){
                    $filas=array();
                    $i=0;
                    while($fila = $sql->fetch_object()){
                        $filas[$i]['id']=$fila->id;
                        $filas[$i]['cuenta']=$fila->cuenta;
                        $i++;
                    }
                    echo json_encode(array('estado'=>'ok','mensaje'=>$filas));
                }else{
                    echo json_encode(array('estado'=>0,'mensaje'=>'No hay proveedor de sms configurado'));
                }                
            }else{
                echo json_encode(array('estado'=>0,'mensaje'=>'Operacion invalida'));
            }
            
        }else{
            echo json_encode(array('estado'=>0,'mensaje'=>'sin datos'));
        }
    }

    if(isset($_GET['getCamposParaSms'])){
        $guion=isset($_POST['guion']) ? $_POST['guion'] :false;
        if($guion){
            $sql=$mysqli->query("SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE md5(concat('".clave_get."', PREGUN_ConsInte__GUION__b)) = '".$guion."' AND (PREGUN_Tipo______b =1 OR PREGUN_Tipo______b =2 OR PREGUN_Tipo______b =3 OR PREGUN_Tipo______b =4 OR PREGUN_Tipo______b =5 OR PREGUN_Tipo______b =6 OR PREGUN_Tipo______b =10) AND SECCIO_TipoSecc__b=1");
            if($sql && $sql->num_rows>0){
                $campos=array();
                $i=0;
                while($fila = $sql->fetch_object()){
                    $campos[$i]['id']=$fila->PREGUN_ConsInte__b;
                    $campos[$i]['texto']=$fila->PREGUN_Texto_____b;
                    $i++;
                }
                echo json_encode(array('estado'=>'ok', 'mensaje'=>$campos));
            }
        }else{
            echo json_encode(array('estado'=>'error', 'mensaje'=>'No se identifico el guion'));
        }
    }

    if(isset($_GET['TraerBasesHuesped'])){
        if(isset($_SESSION['HUESPED'])){
            $sql=$mysqli->query("SELECT GUION__ConsInte__b,GUION__Nombre____b FROM DYALOGOCRM_SISTEMA.GUION_ WHERE GUION__ConsInte__PROYEC_b={$_SESSION['HUESPED']} AND GUION__Tipo______b=2");
            echo "<option value='0'>Sleccione la BD</option>";
            if($sql && $sql->num_rows>0){
                while($base=$sql->fetch_object()){
                    echo "<option value='{$base->GUION__ConsInte__b}'>{$base->GUION__Nombre____b}</option>";
                }
            }
        }
    }

    if(isset($_GET['getCamposBd'])){
        $guion=isset($_POST['id']) ? $_POST['id'] :false;
        if($guion){
            $sql=$mysqli->query("SELECT PREGUN_ConsInte__b,PREGUN_Texto_____b FROM DYALOGOCRM_SISTEMA.PREGUN LEFT JOIN DYALOGOCRM_SISTEMA.SECCIO ON PREGUN_ConsInte__SECCIO_b=SECCIO_ConsInte__b WHERE PREGUN_ConsInte__GUION__b={$guion} AND (PREGUN_Tipo______b =1 OR PREGUN_Tipo______b =2 OR PREGUN_Tipo______b =3 OR PREGUN_Tipo______b =4 OR PREGUN_Tipo______b =5 OR PREGUN_Tipo______b =6 OR PREGUN_Tipo______b =10) AND SECCIO_TipoSecc__b=1");
            echo "<option value='0'>Seleccione</option>";
            if($sql && $sql->num_rows>0){
                while($fila = $sql->fetch_object()){
                    echo "<option value='{$fila->PREGUN_ConsInte__b}'>{$fila->PREGUN_Texto_____b}</option>";
                }
            }
        }
    }

//  *NBG*-05-2020*ACTUALIZAR LOS CAMPOS A MOSTRAR DE LOS SUBFORMULARIOS
    if(isset($_GET['insertarcampossub'])){
        if(isset($_POST['guion']) && $_POST['guion'] !=0){
            $intGuion=$_POST['guion'];
            if(is_array($_POST['datos'])){
                $strSQLEliminaCamposMostrar="UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_MostrarSubForm=0 WHERE PREGUN_ConsInte__GUION__b={$intGuion}";           
                $strSQLCamposMostrar=$mysqli->query($strSQLEliminaCamposMostrar);     
                foreach($_POST['datos'] as $campo){
                    $strSQLCamposMostrar="UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_MostrarSubForm=1 WHERE PREGUN_ConsInte__b={$campo} and PREGUN_ConsInte__GUION__b={$intGuion}";
                    $strSQLCamposMostrar=$mysqli->query($strSQLCamposMostrar);
                }
                
            }else{
                echo 'Debe seleccionar los campos a mostrar del subformulario';
            }
        }else{
            echo 'No hay un Subformulario Seleccionado';
        }
    }

    if(isset($_GET['camposcorreo'])){
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['guion']=$sqlIdGuion->id;
        }
        $Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE G6_C40 = 1 AND G6_C207 = ".$_POST['guion']." AND G7_C38 = 1 AND G6_C209 != 3;";
        //echo $Lsql ;
        $res_Lsql = $mysqli->query($Lsql);
        echo "<option value=\"0\">Campo correo</option>";
        while ($key = $res_Lsql->fetch_object()) {
            echo "<option value='".$key->id."'>".utf8_encode($key->G6_C39)."</option>";
        }
    
    }

    if(isset($_GET['camposNumericos'])){
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['guion']=$sqlIdGuion->id;
        }
        $Lsql = "SELECT REPLACE(REPLACE(G6_C39,' ','_'),'\t','_') AS G6_C39 FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE (G6_C40 = 3 OR G6_C40 = 4) AND G6_C207 = ".$_POST['guion']." AND G7_C38 NOT IN (3) AND G6_C209 != 3";
        $res_Lsql = $mysqli->query($Lsql);
        while ($key = $res_Lsql->fetch_object()) {
            echo "<li>\${".NombreParaFormula($key->G6_C39)."}</li>";
        }
    
    }

    if(isset($_GET['camposGuion_incude_id'])){
        $Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6 WHERE G6_C40 != 12 AND md5(concat('".clave_get."', G6_C207)) = '".$_POST["guion"]."' AND G6_C209 != 3 ";
        $res_Lsql = $mysqli->query($Lsql);
        echo "<option value='_ConsInte__b'>Seleccione</option>";
        while ($key = $res_Lsql->fetch_object()) {
            echo "<option value='".$key->id."'>".utf8_encode($key->G6_C39)."</option>";
        }
    
    }

    if(isset($_GET['camposPregui'])){
        $Lsql = "SELECT CAMPO__ConsInte__PREGUN_b FROM ".$BaseDatos_systema.".PREGUI JOIN ".$BaseDatos_systema.".CAMPO_ ON CAMPO__ConsInte__b = PREGUI_ConsInte__CAMPO__b WHERE PREGUI_ConsInte__PREGUN_b  = ".$_POST['pregun'];
        $res_Lsql = $mysqli->query($Lsql);
        $data = array();
        $i = 0;
        while ($key = $res_Lsql->fetch_object()) {
            $data[$i]['id'] = $key->CAMPO__ConsInte__PREGUN_b;
            $i++;    
        }
        echo json_encode($data);
    
    }

    if(isset($_GET['camposGuidet'])){
        $Lsql = "SELECT GUIDET_ConsInte__GUION__Mae_b, GUIDET_ConsInte__GUION__Det_b, GUIDET_Nombre____b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_Modo______b, GUIDET_ConsInte__PREGUN_Cre_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__PREGUN_Cre_b  = ".$_POST['pregun'];
        //echo $Lsql;
        $res_Lsql = $mysqli->query($Lsql);
        $data = array();
        $i = 0;
        while ($key = $res_Lsql->fetch_object()) {
            $data[$i] = $key;
            $i++;    
        }
        echo json_encode($data);
    
    }

    if(isset($_GET['dameListas'])){

        $str_Lsql = "SELECT  OPCION_ConsInte__b as id , OPCION_Nombre____b as G8_C45 FROM ".$BaseDatos_systema.".OPCION WHERE OPCION_ConsInte__PROYEC_b = ".$_SESSION['HUESPED']." ORDER BY OPCION_Nombre____b ASC;";
        $combo = $mysqli->query($str_Lsql);
        echo "<option value='0'>Seleccione</option>";
        while($obj = $combo->fetch_object()){
            echo "<option value='".$obj->id."' dinammicos='0'>".$obj->G8_C45."</option>";
        }               
    
    }

    if(isset($_POST['getListasEdit'])){
        $Lsql = "SELECT OPCION_Nombre____b, OPCION_ConsInte__b FROM ".$BaseDatos_systema.".OPCION WHERE OPCION_ConsInte__b = ".$_POST['idOpcion'];
        $res = $mysqli->query($Lsql);
        $datosO = $res->fetch_array();

        $Lsql = "SELECT LISOPC_ConsInte__b, LISOPC_Nombre____b, LISOPC_Respuesta_b, LISOPC_ConsInte__LISOPC_Depende_b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$_POST['idOpcion']." ORDER BY LISOPC_ConsInte__LISOPC_Depende_b ASC, LISOPC_Nombre____b ASC;";

        $res = $mysqli->query($Lsql);
        $datosL = array();
        $i = 0;
        while ($key = $res->fetch_object()) {
            $datosL[$i]['LISOPC_Nombre____b'] = $key->LISOPC_Nombre____b;
            $datosL[$i]['LISOPC_ConsInte__b'] = $key->LISOPC_ConsInte__b;
            $datosL[$i]['LISOPC_Respuesta_b'] = $key->LISOPC_Respuesta_b;
            $datosL[$i]['LISOPC_ConsInte__LISOPC_Depende_b'] = $key->LISOPC_ConsInte__LISOPC_Depende_b;
            $i++;
        }

        echo json_encode(array('code' => '1' , 'opcion' => $datosO['OPCION_Nombre____b'], 'id' => $datosO['OPCION_ConsInte__b'], 'lista' => $datosL, 'total' => $i));
    
    }

    if(isset($_GET['traerCuentas'])){
        $Lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_configuracion ;";
        echo "<option value='0'>Seleccione cuenta</option>";
        $cur_result = $mysqli->query($Lsql);
        while ($key = $cur_result->fetch_object()) {
            echo "<option value='".$key->id."'>".$key->direccion_correo_electronico."</option>";
        }
    
    }

    if(isset($_POST['validarParaBorrar'])){
        $datoWhere = 'CAMPAN_ConsInte__GUION__Pob_b';
        if($_POST['tipo'] == '1'){
            $datoWhere = 'CAMPAN_ConsInte__GUION__Gui_b';
        }
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['id']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['id']=$sqlIdGuion->id;
        }
        $LsqlCampan = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE ".$datoWhere." = ".$_POST['id']." LIMIT 1";
        $resLsql_Campan = $mysqli->query($LsqlCampan);
        if($resLsql_Campan->num_rows > 0){

            $data = $resLsql_Campan->fetch_array();
            $validar = 0;
            echo json_encode(array( 'code' => '-1' , 'campana' => $data['CAMPAN_Nombre____b']));

        }else{

            $Lsql_Pregun = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__PRE_B = ".$_POST['id'];
            $resLsql_Pregun = $mysqli->query($Lsql_Pregun);

            if($resLsql_Pregun->num_rows > 0){
                $data = $resLsql_Pregun->fetch_array();
                $validar = 0;
                echo json_encode(array( 'code' => '-3' , 'campana' => $data['PREGUN_Texto_____b']));

            }else{

                $Lsql_Guidet = "SELECT GUIDET_ConsInte__GUION__Mae_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Det_b = ".$_POST['id'];
                $resLsql_Guidet = $mysqli->query($Lsql_Guidet);

                if($resLsql_Guidet->num_rows > 0){

                    $data = $resLsql_Guidet->fetch_array();
                    $validar = 0;
                    echo json_encode(array( 'code' => '-2' , 'campana' => $data['GUIDET_ConsInte__GUION__Mae_b']));

                }else{

                    echo json_encode(array('code' => 0 , 'message' => 'No hay conexiones'));

                }
            }
        }
    }

    if(isset($_POST['validarPreguiPorCampo'])){
        $campoPregui = $_POST['campo'];
        $Lsql = "SELECT PREGUI_ConsInte__b, PREGUI_ConsInte__CAMPO__b, PREGUI_Consinte__GUION__b, PREGUI_Consinte__CAMPO__GUI_B FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__PREGUN_b = ".$campoPregui;

        $res = $mysqli->query($Lsql);
        $arrayData = array();
        $i = 0;
        while ($key = $res->fetch_object()) {
            $campoGuion = $key->PREGUI_ConsInte__CAMPO__b;
            $campoGuionX = $key->PREGUI_Consinte__CAMPO__GUI_B;

            /* Ahora obtenemos el Id del campo desde La tabla Campo_ */
            $arrayData[$i]['campoGuionX'] = $campoGuionX == 0 ?  null : dameIdCampo($campoGuionX);
            $arrayData[$i]['campoGuion'] = dameIdCampo($campoGuion);
            $arrayData[$i]['PREGUI_ConsInte__b'] = $key->PREGUI_ConsInte__b;
            $i++;
        }

        if($i > 0){
            echo json_encode(array('code' => 0 , 'datosDeCampos' => $arrayData) );
        }else{
            echo json_encode(array('code' => -1 , 'Message' => 'No hay relaciones') );
        }
        
    }

    if(isset($_POST['eliminarPreguiPorCampo'])){
        $campoPregui = $_POST['idPregui'];
        $Lsql = "DELETE FROM ".$BaseDatos_systema.".PREGUI WHERE PREGUI_ConsInte__b = ".$campoPregui;
        if($mysqli->query($Lsql) === true){
            echo "1";
        }else{
            echo $mysqli->error;
        }
        
    }

    if(isset($_POST['getCamposLista'])){

        if (isset($_POST["tipoSec"]) && $_POST["tipoSec"] == "2") {

            $Lsql = "SELECT  G6_ConsInte__b as id , G6_C39, G6_C44 FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE (G6_C40 = 6 OR G6_C40 = 13)  AND md5(concat('".clave_get."', G6_C207)) = '".$_POST['guion']."' AND G7_C38 IN (1,2) AND G6_C209 != 3;";

        }else{

            $Lsql = "SELECT  G6_ConsInte__b as id , G6_C39, G6_C44 FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE (G6_C40 = 6 OR G6_C40 = 13)  AND md5(concat('".clave_get."', G6_C207)) = '".$_POST['guion']."' AND G7_C38 = 1 AND G6_C209 != 3;";
            
            
        }


        $res_Lsql = $mysqli->query($Lsql);
        echo "<option value=\"0\">Seleccione</option>";
        while ($key = $res_Lsql->fetch_object()) {
            echo "<option value='".$key->id."' idOption='".$key->G6_C44."'>".utf8_encode($key->G6_C39)."</option>";
        }

    }

    if (isset($_POST["getTip"])) {
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['intIdGuion_t']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['intIdGuion_t']=$sqlIdGuion->id;
        }
        $intIdGuion_t = $_POST["intIdGuion_t"];

        if ($_POST["getTip"] == "tipificaciones") {

            $strSQLLista_t = "SELECT C.LISOPC_ConsInte__b AS id, C.LISOPC_Nombre____b AS nombre, A.PREGUN_ConsInte__b AS tip FROM ".$BaseDatos_systema.".PREGUN A JOIN ".$BaseDatos_systema.".SECCIO B ON A.PREGUN_ConsInte__SECCIO_b = B.SECCIO_ConsInte__b JOIN ".$BaseDatos_systema.".LISOPC C ON A.PREGUN_ConsInte__OPCION_B = C.LISOPC_ConsInte__OPCION_b WHERE A.PREGUN_ConsInte__GUION__b = ".$intIdGuion_t." AND PREGUN_Texto_____b = 'Tipificación' AND PREGUN_Tipo______b = 6 AND B.SECCIO_TipoSecc__b = 3";

        }else if($_POST["getTip"] == "campos"){

            $strSQLLista_t = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre, 0 AS tip FROM ".$BaseDatos_systema.".PREGUN A JOIN ".$BaseDatos_systema.".SECCIO B ON A.PREGUN_ConsInte__SECCIO_b = B.SECCIO_ConsInte__b WHERE A.PREGUN_ConsInte__GUION__b = ".$intIdGuion_t." AND A.PREGUN_Tipo______b NOT IN (15,12,9,8) AND B.SECCIO_TipoSecc__b NOT IN (4,2,3)";
            
        }

        $resSQLLista_t = $mysqli->query($strSQLLista_t); 

        if ($resSQLLista_t->num_rows > 0) {
            echo "<option tip=\"0\" value=\"0\">Seleccione</option>";
            while($row = $resSQLLista_t->fetch_object()){
                echo "<option tip=\"".$row->tip."\" value=\"".$row->id."\">".$row->nombre."</option>";
            }

        }

    }

    //SECCION PARA LOS SALTOS

    if(isset($_POST['agregarSalto'])){
        /* toca meterlo en el sistema */
        if(isset($_POST['arrNumeroSalto_t'])){

            if ($_POST['arrNumeroSalto_t'] != "") {
                $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
                if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                    $sqlIdGuion=$sqlIdGuion->fetch_object();
                    $_POST['guion']=$sqlIdGuion->id;
                }
                $arrNumeroSalto_t = explode(",", $_POST['arrNumeroSalto_t']);

                $valido = 0;
                $novalido = 0;
                $error ='';

                foreach ($arrNumeroSalto_t as $row => $numero) {
                    
                    if(isset($_POST['opcionesCampo_'.$numero]) && $_POST['opcionesCampo_'.$numero] != 0){

                        $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSADE (PRSADE_ConsInte__GUION__b, PRSADE_ConsInte__OPCION_b, PRSADE_ConsInte__PREGUN_b, PRSADE_NombCont__b) VALUES(".$_POST['guion']." , ".$_POST['opcionesCampo_'.$numero]." , ".$_POST['cmbListaParaSalto']." , 'G".$_POST['guion']."_C".$_POST['cmbListaParaSalto']."');";

                        if($mysqli->query($strSQLInsert_t) === true){

                            $intIdPRSADE_t = $mysqli->insert_id;

                            $requerido=explode("_",$_POST['camposCOnfiguradosGuionTo_'.$numero]);
                            if(count($requerido) > 1){
                                //QUITAR EL REQUERIDO DEL CAMPO EN PREGUN
                                $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_IndiRequ__b=0 WHERE PREGUN_ConsInte__b=$requerido[0]");
                                $_POST['camposCOnfiguradosGuionTo_'.$numero]=$requerido[0];
                            }

                            $strSQLCampos_t = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['camposCOnfiguradosGuionTo_'.$numero];

                            $resSQLCampos_t = $mysqli->query($strSQLCampos_t);
                            $objSQLCampos_t = $resSQLCampos_t->fetch_object();

                            $intLimpiar_t = 0;

                            if (isset($_POST["limpiarCampos_".$numero])) {
                                $intLimpiar_t = 1;
                            }

                            $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSASA (PRSASA_ConsInte__PRSADE_b, PRSASA_ConsInte__PREGUN_b, PRSASA_NombCont__b, PRSASA_TipoPreg__b,PRSASA_Limpiar_b) VALUES (".$intIdPRSADE_t.", ".$_POST['camposCOnfiguradosGuionTo_'.$numero]." , 'G".$_POST['guion']."_C".$_POST['camposCOnfiguradosGuionTo_'.$numero]."' , ".$objSQLCampos_t->PREGUN_Tipo______b.", ".$intLimpiar_t.") ";

                            if($mysqli->query($strSQLInsert_t) === true){
                                $valido += 1;
                            }else{
                                $novalido +=1;
                                $error .= $mysqli->error;
                            }

                        }else{
                            echo json_encode(array('code' => 0, "message" => $mysqli->error));
                        }

                    }

                }
            }

            if($novalido != 0){
                echo json_encode(array('code' => 0, 'total' => $novalido , 'error' => $error ));
            }else{
                echo json_encode(array('code' => 1, 'total' => $valido ));
            }
            
        }

    }

    if(isset($_POST['agregarSaltoSeccion'])){
        /* toca meterlo en el sistema */
        if(isset($_POST['arrNumeroSalto_t'])){

            if ($_POST['arrNumeroSalto_t'] != "") {
                $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
                if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                    $sqlIdGuion=$sqlIdGuion->fetch_object();
                    $_POST['guion']=$sqlIdGuion->id;
                }
                $arrNumeroSalto_t = explode(",", $_POST['arrNumeroSalto_t']);

                $valido = 0;
                $novalido = 0;
                $error ='';

                foreach ($arrNumeroSalto_t as $row => $numero) {
                    
                    if(isset($_POST['opcionesCampoSeccion_'.$numero]) && $_POST['opcionesCampoSeccion_'.$numero] != 0){

                        $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSADE (PRSADE_ConsInte__GUION__b, PRSADE_ConsInte__OPCION_b, PRSADE_ConsInte__PREGUN_b, PRSADE_NombCont__b,PRSADE_By_SECCIO_b) VALUES(".$_POST['guion']." , ".$_POST['opcionesCampoSeccion_'.$numero]." , ".$_POST['cmbListaParaSaltoSeccion']." , 'G".$_POST['guion']."_C".$_POST['cmbListaParaSaltoSeccion']."', 1);";

                        if($mysqli->query($strSQLInsert_t) === true){
                            
                            $intIdPRSADE_t = $mysqli->insert_id;
                            
                            $requerido=explode("_",$_POST['camposCOnfiguradosGuionToSeccion_'.$numero]);
                            if(count($requerido) > 1){
                                //QUITAR EL REQUERIDO DEL CAMPO EN PREGUN
                                $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_IndiRequ__b=0 WHERE PREGUN_ConsInte__SECCIO_b=$requerido[0]");
                                $_POST['camposCOnfiguradosGuionToSeccion_'.$numero]=$requerido[0];
                            }

                            $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSASA (PRSASA_ConsInte__PRSADE_b,PRSASA_ConsInte__PREGUN_b, PRSASA_ConsInte__SECCIO_b, PRSASA_NombCont__b, PRSASA_TipoPreg__b) VALUES (".$intIdPRSADE_t.",-1, ".$_POST['camposCOnfiguradosGuionToSeccion_'.$numero]." , 's_{$_POST['camposCOnfiguradosGuionToSeccion_'.$numero]}' , -1)";

                            if($mysqli->query($strSQLInsert_t) === true){

                                $valido += 1;
                            }else{
                                $novalido +=1;
                                $error .= $mysqli->error;
                            }

                        }else{
                            echo json_encode(array('code' => 0, "message" => $mysqli->error));
                        }

                    }

                }
            }

            if($novalido != 0){
                echo json_encode(array('code' => 0, 'total' => $novalido , 'error' => $error ));
            }else{
                echo json_encode(array('code' => 1, 'total' => $valido ));
            }
            
        }

    }

    if(isset($_POST['getSaltos'])){
        $guion = $_POST['guion'];
        $Lsql = "SELECT PRSADE_ConsInte__PREGUN_b FROM ".$BaseDatos_systema.".PRSADE WHERE md5(concat('".clave_get."', PRSADE_ConsInte__GUION__b)) = '".$guion."' AND PRSADE_By_SECCIO_b= 0 GROUP BY PRSADE_ConsInte__PREGUN_b";
        $res = $mysqli->query($Lsql);
        while ($key = $res->fetch_object()) {
            $Lslq = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$key->PRSADE_ConsInte__PREGUN_b;
            $resPregun = $mysqli->query($Lslq);
            $data = $resPregun->fetch_array();
            $disabled = 'disabled';
            if($_POST['bool_estamosListos'] == true){
                $disabled = '';
            }
            echo '<div class="panel box box-primary" id="salto_'.$key->PRSADE_ConsInte__PREGUN_b.'">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                '.$data['PREGUN_Texto_____b'].'
                            </h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm btnEditarSalto" '.$disabled.' guion="'.$guion.'" valorSalto="'.$key->PRSADE_ConsInte__PREGUN_b.'" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btnEliminarSalto" '.$disabled.' guion="'.$guion.'" valorSalto="'.$key->PRSADE_ConsInte__PREGUN_b.'" >
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>'; 
        }

    }

    if(isset($_POST['getSaltosSeccion'])){
        $guion = $_POST['guion'];
        $Lsql = "SELECT PRSADE_ConsInte__PREGUN_b FROM ".$BaseDatos_systema.".PRSADE WHERE md5(concat('".clave_get."', PRSADE_ConsInte__GUION__b)) = '".$guion."' AND PRSADE_By_SECCIO_b= 1 GROUP BY PRSADE_ConsInte__PREGUN_b ";
        $res = $mysqli->query($Lsql);
        while ($key = $res->fetch_object()) {
            $Lslq = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$key->PRSADE_ConsInte__PREGUN_b;
            $resPregun = $mysqli->query($Lslq);
            $data = $resPregun->fetch_array();
            $disabled = 'disabled';
            if($_POST['bool_estamosListos'] == true){
                $disabled = '';
            }
            echo '<div class="panel box box-primary" id="saltoSeccion_'.$key->PRSADE_ConsInte__PREGUN_b.'">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                '.$data['PREGUN_Texto_____b'].'
                            </h4>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm btnEditarSaltoSeccion" '.$disabled.' guion="'.$guion.'" valorSalto="'.$key->PRSADE_ConsInte__PREGUN_b.'" >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm btnEliminarSaltoSeccion" '.$disabled.' guion="'.$guion.'" valorSalto="'.$key->PRSADE_ConsInte__PREGUN_b.'" >
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>'; 
        }

    }

    if(isset($_POST['deleteSalto'])){
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['guion']=$sqlIdGuion->id;
        }
        $Lsql = "SELECT PRSADE_ConsInte__b FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__GUION__b = ".$_POST['guion']." AND PRSADE_ConsInte__PREGUN_b =  ".$_POST['pregunta']." AND PRSADE_By_SECCIO_b= 0";
        $res = $mysqli->query($Lsql);
        $valido = 0;
        while ($key = $res->fetch_object()) {
            /* Listo tenemos las prasade ahora toca borrar los presada */
            saveAuditoria('ELIMINAR', 'Elimino todo el salto', 'FORMULARIO', 'Saltos', $key->PRSADE_ConsInte__b, "WHERE PRSASA_ConsInte__PRSADE_b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSASA');
            $xLsql = "DELETE FROM ".$BaseDatos_systema.".PRSASA WHERE PRSASA_ConsInte__PRSADE_b = ".$key->PRSADE_ConsInte__b;
            if($mysqli->query($xLsql) === true){
                saveAuditoria('ELIMINAR', 'Elimino todo el salto', 'FORMULARIO', 'Saltos', $key->PRSADE_ConsInte__b, "WHERE PRSADE_ConsInte__b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSADE');
                $deleteSql = "DELETE FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__b = ".$key->PRSADE_ConsInte__b;
                if($mysqli->query($deleteSql) === true){
                    $valido += 1;
                }else{
                    echo $mysqli->error;
                }
            }else{
                echo $mysqli->error;
            }
        }

        if($valido != 0){
            echo "0";
        }

    }

    if(isset($_POST['deleteSaltoSeccion'])){
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['guion']=$sqlIdGuion->id;
        }
        $Lsql = "SELECT PRSADE_ConsInte__b FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__GUION__b = ".$_POST['guion']." AND PRSADE_ConsInte__PREGUN_b =  ".$_POST['pregunta']." AND PRSADE_By_SECCIO_b= 1";
        $res = $mysqli->query($Lsql);
        $valido = 0;
        while ($key = $res->fetch_object()) {
            /* Listo tenemos las prasade ahora toca borrar los presada */
            saveAuditoria('ELIMINAR', 'Elimino todo el salto por seccion', 'FORMULARIO', 'Saltos por seccion', $key->PRSADE_ConsInte__b, "WHERE PRSASA_ConsInte__PRSADE_b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSASA');
            $xLsql = "DELETE FROM ".$BaseDatos_systema.".PRSASA WHERE PRSASA_ConsInte__PRSADE_b = ".$key->PRSADE_ConsInte__b;
            if($mysqli->query($xLsql) === true){
                saveAuditoria('ELIMINAR', 'Elimino todo el salto por seccion', 'FORMULARIO', 'Saltos por seccion', $key->PRSADE_ConsInte__b, "WHERE PRSADE_ConsInte__b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSADE');
                $deleteSql = "DELETE FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__b = ".$key->PRSADE_ConsInte__b;
                if($mysqli->query($deleteSql) === true){
                    $valido += 1;
                }else{
                    echo $mysqli->error;
                }
            }else{
                echo $mysqli->error;
            }
        }

        if($valido != 0){
            echo "0";
        }

    }

    if(isset($_POST['editarSalto'])){

        if(isset($_POST['arrNumeroSalto_t'])){

            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['guion']=$sqlIdGuion->id;
            }
            
            $valido = 0;
            $novalido = 0;
            $error ='';

            $Lsql = "SELECT PRSADE_ConsInte__b FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__GUION__b = ".$_POST['guion']." AND PRSADE_ConsInte__PREGUN_b =  ".$_POST['cmbListaParaSalto']." AND PRSADE_By_SECCIO_b= 0";

            $res = $mysqli->query($Lsql);
            $valido = 0;
            while ($key = $res->fetch_object()) {
                /* Listo tenemos las prasade ahora toca borrar los presada */
                saveAuditoria('ACTUALIZAR', 'Actualizo todo el salto', 'FORMULARIO', 'Saltos por seccion', $key->PRSADE_ConsInte__b, "WHERE PRSASA_ConsInte__PRSADE_b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSASA');
                $xLsql = "DELETE FROM ".$BaseDatos_systema.".PRSASA WHERE PRSASA_ConsInte__PRSADE_b = ".$key->PRSADE_ConsInte__b;
                if($mysqli->query($xLsql) === true){
                    saveAuditoria('ACTUALIZAR', 'Actualizo todo el salto', 'FORMULARIO', 'Saltos por seccion', $key->PRSADE_ConsInte__b, "WHERE PRSADE_ConsInte__b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSADE');
                    $deleteSql = "DELETE FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__b = ".$key->PRSADE_ConsInte__b;
                    if($mysqli->query($deleteSql) === true){
                       
                    }else{
                        echo $mysqli->error;
                    }
                }else{
                    echo $mysqli->error;
                }
            }

            if ($_POST["arrNumeroSalto_t"] != "") {
                
                $arrNumeroSalto_t = explode(",", $_POST["arrNumeroSalto_t"]);

                foreach ($arrNumeroSalto_t as $row => $numero) {
                    
                    if(isset($_POST['opcionesCampo_'.$numero]) && $_POST['opcionesCampo_'.$numero]){

                        $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSADE (PRSADE_ConsInte__GUION__b, PRSADE_ConsInte__OPCION_b, PRSADE_ConsInte__PREGUN_b, PRSADE_NombCont__b) VALUES(".$_POST['guion']." , ".$_POST['opcionesCampo_'.$numero]." , ".$_POST['cmbListaParaSalto']." , 'G".$_POST['guion']."_C".$_POST['cmbListaParaSalto']."');";

                        if($mysqli->query($strSQLInsert_t) === true){

                            $intIdPRSADE_t = $mysqli->insert_id;

                            $requerido=explode("_",$_POST['camposCOnfiguradosGuionTo_'.$numero]);
                            if(count($requerido) > 1){
                                //QUITAR EL REQUERIDO DEL CAMPO EN PREGUN
                                $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_IndiRequ__b=0 WHERE PREGUN_ConsInte__b=$requerido[0]");
                                $_POST['camposCOnfiguradosGuionTo_'.$numero]=$requerido[0];
                            }

                            $strSQLCampos_t = "SELECT PREGUN_Tipo______b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['camposCOnfiguradosGuionTo_'.$numero]."";

                            $resSQLCampos_t = $mysqli->query($strSQLCampos_t);
                            $objSQLCampos_t = $resSQLCampos_t->fetch_object();

                            $intLimpiar_t = 0;

                            if (isset($_POST["limpiarCampos_".$numero])) {
                                $intLimpiar_t = 1;
                            }

                            $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSASA (PRSASA_ConsInte__PRSADE_b, PRSASA_ConsInte__PREGUN_b, PRSASA_NombCont__b, PRSASA_TipoPreg__b,PRSASA_Limpiar_b) VALUES (".$intIdPRSADE_t.", ".$_POST['camposCOnfiguradosGuionTo_'.$numero]." , 'G".$_POST['guion']."_C".$_POST['camposCOnfiguradosGuionTo_'.$numero]."' , ".$objSQLCampos_t->PREGUN_Tipo______b.", ".$intLimpiar_t.") ";

                            if($mysqli->query($strSQLInsert_t) === true){
                                $valido += 1;
                            }else{
                                $novalido +=1;
                                $error .= $mysqli->error;
                            }

                        }else{
                            echo json_encode(array('code' => 0, "message" => $mysqli->error));
                        }

                    }

                }
            }

            if($novalido != 0){
                echo json_encode(array('code' => 0, 'total' => $novalido , 'error' => $error ));
            }else{
                echo json_encode(array('code' => 1, 'total' => $valido ));
            }
        }

    }

    if(isset($_POST['editarSaltoSeccion'])){

        if(isset($_POST['arrNumeroSalto_t'])){

            $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
            if($sqlIdGuion && $sqlIdGuion->num_rows==1){
                $sqlIdGuion=$sqlIdGuion->fetch_object();
                $_POST['guion']=$sqlIdGuion->id;
            }
            
            $valido = 0;
            $novalido = 0;
            $error ='';

            $Lsql = "SELECT PRSADE_ConsInte__b FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__GUION__b = ".$_POST['guion']." AND PRSADE_ConsInte__PREGUN_b =  ".$_POST['cmbListaParaSaltoSeccion']." AND PRSADE_By_SECCIO_b= 1";

            $res = $mysqli->query($Lsql);
            $valido = 0;
            while ($key = $res->fetch_object()) {
                /* Listo tenemos las prasade ahora toca borrar los presada */
                saveAuditoria('ACTUALIZAR', 'Actualizo todo el salto por seccion', 'FORMULARIO', 'Saltos por seccion', $key->PRSADE_ConsInte__b, "WHERE PRSASA_ConsInte__PRSADE_b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSASA');
                $xLsql = "DELETE FROM ".$BaseDatos_systema.".PRSASA WHERE PRSASA_ConsInte__PRSADE_b = ".$key->PRSADE_ConsInte__b;
                if($mysqli->query($xLsql) === true){
                    saveAuditoria('ACTUALIZAR', 'Actualizo todo el salto por seccion', 'FORMULARIO', 'Saltos por seccion', $key->PRSADE_ConsInte__b, "WHERE PRSADE_ConsInte__b ={$key->PRSADE_ConsInte__b}", $BaseDatos_systema, 'PRSADE');
                    $deleteSql = "DELETE FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__b = ".$key->PRSADE_ConsInte__b;
                    if($mysqli->query($deleteSql) === true){
                       
                    }else{
                        echo $mysqli->error;
                    }
                }else{
                    echo $mysqli->error;
                }
            }

            if ($_POST["arrNumeroSalto_t"] != "") {
                
                $arrNumeroSalto_t = explode(",", $_POST["arrNumeroSalto_t"]);

                foreach ($arrNumeroSalto_t as $row => $numero) {
                    
                    if(isset($_POST['opcionesCampoSeccion_'.$numero]) && $_POST['opcionesCampoSeccion_'.$numero]){

                        $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSADE (PRSADE_ConsInte__GUION__b, PRSADE_ConsInte__OPCION_b, PRSADE_ConsInte__PREGUN_b, PRSADE_NombCont__b,PRSADE_By_SECCIO_b) VALUES(".$_POST['guion']." , ".$_POST['opcionesCampoSeccion_'.$numero]." , ".$_POST['cmbListaParaSaltoSeccion']." , 'G".$_POST['guion']."_C".$_POST['cmbListaParaSaltoSeccion']."', 1);";
                        
                        if($mysqli->query($strSQLInsert_t) === true){

                            $intIdPRSADE_t = $mysqli->insert_id;

                            $requerido=explode("_",$_POST['camposCOnfiguradosGuionToSeccion_'.$numero]);
                            if(count($requerido) > 1){
                                //QUITAR EL REQUERIDO DEL CAMPO EN PREGUN
                                $sql=$mysqli->query("UPDATE {$BaseDatos_systema}.PREGUN SET PREGUN_IndiRequ__b=0 WHERE PREGUN_ConsInte__SECCIO_b=$requerido[0]");
                                $_POST['camposCOnfiguradosGuionToSeccion_'.$numero]=$requerido[0];
                            }

                            $intLimpiar_t = 0;

                            $strSQLInsert_t = "INSERT INTO ".$BaseDatos_systema.".PRSASA (PRSASA_ConsInte__PRSADE_b, PRSASA_ConsInte__PREGUN_b,PRSASA_ConsInte__SECCIO_b, PRSASA_NombCont__b, PRSASA_TipoPreg__b,PRSASA_Limpiar_b) VALUES (".$intIdPRSADE_t.", -1,".$_POST['camposCOnfiguradosGuionToSeccion_'.$numero]." , 's_".$_POST['camposCOnfiguradosGuionToSeccion_'.$numero]."' , -1, ".$intLimpiar_t.") ";
                            
                            

                            if($mysqli->query($strSQLInsert_t) === true){
                                $valido += 1;
                            }else{
                                $novalido +=1;
                                $error .= $mysqli->error;
                            }

                        }else{
                            echo json_encode(array('code' => 0, "message" => $mysqli->error));
                        }

                    }

                }
            }

            if($novalido != 0){
                echo json_encode(array('code' => 0, 'total' => $novalido , 'error' => $error ));
            }else{
                echo json_encode(array('code' => 1, 'total' => $valido ));
            }
        }

    }

    if(isset($_POST['getDatosPrasasaPrsade'])){
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['guion']=$sqlIdGuion->id;
        }
        $Lsql ="SELECT  PRSADE_ConsInte__b, PRSADE_ConsInte__OPCION_b, PRSASA_ConsInte__PREGUN_b, PRSASA_Limpiar_b FROM ".$BaseDatos_systema.".PRSADE JOIN ".$BaseDatos_systema.".PRSASA ON PRSASA_ConsInte__PRSADE_b = PRSADE_ConsInte__b WHERE PRSADE_ConsInte__PREGUN_b = ".$_POST['pregun']." AND PRSADE_ConsInte__GUION__b = ".$_POST['guion']." AND PRSASA_ConsInte__PREGUN_b >0;";
        $res = $mysqli->query($Lsql);
        $datos = array();
        $i = 0;
        while ($key = $res->fetch_object()) {
            $datos[$i]['campoPregun'] = $key->PRSADE_ConsInte__OPCION_b;
            $datos[$i]['camposGuion'] = $key->PRSASA_ConsInte__PREGUN_b;
            $datos[$i]['id']          = $key->PRSADE_ConsInte__b;
            $datos[$i]['limpiarCampo']          = $key->PRSASA_Limpiar_b;
            $i++;
        }

        echo json_encode($datos);

    }

    if(isset($_POST['getDatosPrasasaPrsadeSeccion'])){
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_POST['guion']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_POST['guion']=$sqlIdGuion->id;
        }
        $Lsql ="SELECT  PRSADE_ConsInte__b, PRSADE_ConsInte__OPCION_b, PRSASA_ConsInte__PREGUN_b, PRSASA_Limpiar_b, PRSASA_ConsInte__SECCIO_b FROM ".$BaseDatos_systema.".PRSADE JOIN ".$BaseDatos_systema.".PRSASA ON PRSASA_ConsInte__PRSADE_b = PRSADE_ConsInte__b WHERE PRSADE_ConsInte__PREGUN_b = ".$_POST['pregun']." AND PRSADE_ConsInte__GUION__b = ".$_POST['guion']." AND PRSASA_ConsInte__PREGUN_b=-1;";
        $res = $mysqli->query($Lsql);
        $datos = array();
        $i = 0;
        while ($key = $res->fetch_object()) {
            $datos[$i]['campoPregun'] = $key->PRSADE_ConsInte__OPCION_b;
            $datos[$i]['camposGuion'] = $key->PRSASA_ConsInte__PREGUN_b;
            $datos[$i]['id']          = $key->PRSADE_ConsInte__b;
            $datos[$i]['limpiarCampo']          = $key->PRSASA_Limpiar_b;
            $datos[$i]['seccion']     = $key->PRSASA_ConsInte__SECCIO_b;
            $i++;
        }

        echo json_encode($datos);

    }

    if(isset($_POST['deletePrasadeByid'])){
        
        /* Listo tenemos las prasade ahora toca borrar los presada */
        saveAuditoria('ELIMINAR', 'Elimino un salto', 'FORMULARIO', 'Saltos', $_POST['id'], "WHERE PRSASA_ConsInte__PRSADE_b ={$_POST['id']}", $BaseDatos_systema, 'PRSASA');
        $xLsql = "DELETE FROM ".$BaseDatos_systema.".PRSASA WHERE PRSASA_ConsInte__PRSADE_b = ".$_POST['id'];
        if($mysqli->query($xLsql) === true){
            saveAuditoria('ELIMINAR', 'Elimino un salto', 'FORMULARIO', 'Saltos', $_POST['id'], "WHERE PRSADE_ConsInte__b ={$_POST['id']}", $BaseDatos_systema, 'PRSADE');
            $deleteSql = "DELETE FROM ".$BaseDatos_systema.".PRSADE WHERE PRSADE_ConsInte__b = ".$_POST['id'];
            if($mysqli->query($deleteSql) === true){
                echo "0";
            }else{
                echo $mysqli->error;
            }
        }else{
            echo $mysqli->error;
        }
        
    }

    //FIN DE FUNCIONES PARA LOS SALTOS

    if(isset($_GET['getMasterDetail'])){
        $id='';
        if(isset($_POST['id'])){
            $id=" AND GUIDET_ConsInte__PREGUN_Cre_b ={$_POST['id']}";    
        }
        $Lsql = "SELECT GUIDET_ConsInte__PREGUN_Ma1_b, GUIDET_ConsInte__PREGUN_De1_b, GUIDET_Modo______b FROM ".$BaseDatos_systema.".GUIDET WHERE md5(concat('".clave_get."', GUIDET_ConsInte__GUION__Mae_b)) = '".$_POST["guionMa"]."' AND GUIDET_ConsInte__GUION__Det_b = ".$_POST['guionDet'].$id;
        $res = $mysqli->query($Lsql);
        $array = array();
        $i = 0;
        if($res->num_rows > 0){
            while($key = $res->fetch_object()){
                $array[$i]['GUIDET_ConsInte__PREGUN_Ma1_b'] = $key->GUIDET_ConsInte__PREGUN_Ma1_b ;
                $array[$i]['GUIDET_ConsInte__PREGUN_De1_b'] = $key->GUIDET_ConsInte__PREGUN_De1_b ;
                $array[$i]['GUIDET_Modo______b'] = $key->GUIDET_Modo______b ;
                $i++;
            }
            echo json_encode( array('code' => '1' , 'datos' => $array ));
        }else{
            echo json_encode( array('code' => '0' , 'message' => 'no hay datos' ));
        }
        
    }

    if(isset($_GET['getCamposTotalizador'])){
        $sql="SELECT GUIDET_ConsInte__PREGUN_Totalizador_b AS papa,GUIDET_ConsInte__PREGUN_Totalizador_H_b AS hijo,GUIDET_Oper_Totalizador_b AS oper FROM {$BaseDatos_systema}.GUIDET WHERE md5(concat('".clave_get."', GUIDET_ConsInte__GUION__Mae_b)) = '".$_POST["guionMa"]."' AND GUIDET_ConsInte__GUION__Det_b = {$_POST['guionDet']}";
        
        $res = $mysqli->query($sql);
        $array = array();
        $i = 0;
        if($res && $res->num_rows > 0){
            while($key = $res->fetch_object()){
                $array[$i]['papa'] = $key->papa;
                $array[$i]['hijo'] = $key->hijo;
                $array[$i]['oper'] = $key->oper;
                $i++;
            }
            echo json_encode( array('code' => '1' , 'datos' => $array ));
        }else{
            echo json_encode( array('code' => '0' , 'message' => 'no hay datos' ));
        }        
    }

    if(isset($_POST['crearFormasWeb'])){
        if($_POST['operVersin'] == "add"){
            $ruta = NULL;
            if(isset($_FILES['txtfile']['tmp_name']) && !empty($_FILES['txtfile']['tmp_name']) ){
                $aleatorio = mt_rand(100, 999);

                if($_FILES['txtfile']["type"] == "image/jpeg"){
                    $aleatorio = $aleatorio.".jpg";
                }elseif($_FILES['txtfile']["type"] == "image/png"){
                    $aleatorio = $aleatorio.".png";
                }
                //creamos la ruta donde se va a guardar la imagen
                $ruta =  "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/".$aleatorio;
                /* Creamos el directorio */
                $directorio = "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/";
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0755);
                }

                copy($_FILES['txtfile']['tmp_name'], $ruta);
                $ruta = $aleatorio;
            }

            $codigo = $mysqli->real_escape_string($_POST['txtCodigo']);
            $Lsql_insert = "INSERT INTO ".$BaseDatos_systema.".GUION_WEBFORM (id_guion, titulo, color_fondo, color_letra, url_gracias, codigo_a_insertar, nombre_imagen, tipo_optin, tipo_gracias, id_dy_ce_configuracion, Asunto_mail, Cuerpo_mail, id_pregun) VALUES (".$_POST['id']." , '".$_POST['txtTitulo']."' , '".$_POST['txtColor']."' , '".$_POST['txtColorLetra']."' , '".$_POST['txtUrl']."', '".$codigo."', '".$ruta."' , '".$_POST['optin']."', '".$_POST['extanas']."', '".$_POST['cuenta']."', '".$_POST['txtAsunto']."', '".$_POST['txtCuerpoMensaje']."' , '".$_POST['cmbCampoCorreo']."')";
       
            if($mysqli->query($Lsql_insert) === true){
                echo json_encode(array('code' => 1 , 'message' => 'exito'));
            }else{
                echo json_encode(array('code' => 0 , 'message' =>  "Error creando conf web form => ".$mysqli->error));
            }                 
        }else if($_POST['operVersin'] == "edit"){
            $ruta = NULL;
            if(isset($_FILES['txtfile']['tmp_name']) && !empty($_FILES['txtfile']['tmp_name']) ){
                $aleatorio = mt_rand(100, 999);

                if($_FILES['txtfile']["type"] == "image/jpeg"){
                    $aleatorio = $aleatorio.".jpg";
                }elseif($_FILES['txtfile']["type"] == "image/png"){
                    $aleatorio = $aleatorio.".png";
                }
                //creamos la ruta donde se va a guardar la imagen
                $ruta =  "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/".$aleatorio;
                /* Creamos el directorio */
                $directorio = "/var/www/html/crm_php/assets/img/plantilla/".$_POST['id']."/";
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0755);
                }

                copy($_FILES['txtfile']['tmp_name'], $ruta);
                $ruta = $aleatorio;
            }

            $codigo = $mysqli->real_escape_string($_POST['txtCodigo']);
            $Lsql_insert = "UPDATE ".$BaseDatos_systema.".GUION_WEBFORM SET titulo = '".$_POST['txtTitulo']."', color_fondo = '".$_POST['txtColor']."', color_letra = '".$_POST['txtColorLetra']."', url_gracias = '".$_POST['txtUrl']."', codigo_a_insertar = '".$codigo."', nombre_imagen = '".$ruta."' , tipo_optin = '".$_POST['optin']."', tipo_gracias = '".$_POST['extanas']."', id_dy_ce_configuracion = '".$_POST['cuenta']."', Asunto_mail = '".$_POST['txtAsunto']."', Cuerpo_mail = '".$_POST['txtCuerpoMensaje']."', id_pregun = '".$_POST['cmbCampoCorreo']."' WHERE id = ".$_POST['hidVersionForm'];
       
            if($mysqli->query($Lsql_insert) === true){
                echo json_encode(array('code' => 1 , 'message' => 'exito'));
            }else{
                echo json_encode(array('code' => 0 , 'message' =>  "Error creando conf web form => ".$mysqli->error));
            } 

        }

    }

    if(isset($_POST['deletefomaWeb'])){
        $Lsql = "DELETE FROM ".$BaseDatos_systema.".GUION_WEBFORM WHERE id = ".$_POST['idForma'];
        if($mysqli->query($Lsql) === true){
            echo json_encode(array('code' => 1 , 'message' => 'Exito'));
        }else{
            echo json_encode(array('code' => 0, 'message' => $mysqli->error));
        }

    }

    if(isset($_POST['getEdicionFormaWeb'])){
        
        $Lsql = "SELECT id, id_guion, titulo, color_fondo, color_letra, url_gracias, codigo_a_insertar, nombre_imagen, tipo_optin, tipo_gracias, id_dy_ce_configuracion, Asunto_mail, Cuerpo_mail, id_pregun FROM ".$BaseDatos_systema.".GUION_WEBFORM WHERE id = ".$_POST['idForma'];

        $res    = $mysqli->query($Lsql);
        $data   = array();
        $i      = 0;
        while ($key = $res->fetch_object()) {
            
            $data[$i]['id'] = $key->id;
            $data[$i]['titulo'] = $key->titulo;
            $data[$i]['color_fondo'] = $key->color_fondo;
            $data[$i]['color_letra'] = $key->color_letra;
            $data[$i]['url_gracias'] = $key->url_gracias;
            $data[$i]['codigo_a_insertar'] = $key->codigo_a_insertar;

            $directorio = "/var/www/html/crm_php/assets/img/plantilla/".$key->id_guion."/";
            if(file_exists($directorio.$key->nombre_imagen)){
                $data[$i]['nombre_imagen'] = "/crm_php/assets/img/plantilla/".$key->id_guion."/".$key->nombre_imagen;
            }else{
                $data[$i]['nombre_imagen'] = 'assets/img/user2-160x160.jpg';
            }
            
            $data[$i]['tipo_optin'] = $key->tipo_optin;
            $data[$i]['tipo_gracias'] = $key->tipo_gracias;

            $data[$i]['id_dy_ce_configuracion'] = $key->id_dy_ce_configuracion;
            $data[$i]['Asunto_mail'] = $key->Asunto_mail;
            $data[$i]['Cuerpo_mail'] = $key->Cuerpo_mail;
            $data[$i]['id_pregun']   = $key->id_pregun;
            $i++;

        }
        
        echo json_encode(array('code' => '1', 'datos' => $data));
    }

    if(isset($_POST['getDatosListaByPregun'])){
        $Lsql = "SELECT PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$_POST['preguntaLista'];
        $rs   = $mysqli->query($Lsql);
        $datoPr = $rs->fetch_array();

        $Lsql = "SELECT LISOPC_ConsInte__b, LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$datoPr['PREGUN_ConsInte__OPCION_B'];
        echo '<option value="0"><?php echo $str_OpcionDependen_; ?></option>';;
        $res = $mysqli->query($Lsql);
        while ($key = $res->fetch_object()) {
            if(isset($_POST['selected']) && $key->LISOPC_ConsInte__b == $_POST['selected']){
                echo '<option value="'.$key->LISOPC_ConsInte__b.'" selected>'.$key->LISOPC_Nombre____b.'</option>';
            }else{
                echo '<option value="'.$key->LISOPC_ConsInte__b.'">'.$key->LISOPC_Nombre____b.'</option>';
            }
        }
    }

    if(isset($_POST['getAgendadores'])){
        $sql=$mysqli->query("SELECT AGENDADOR_ConsInte__b AS id, AGENDADOR_Nombre____b AS nombre, AGENDADOR_ConsInte__GUION__Dis_b AS disponible, AGENDADOR_ConsInte__PREGUN_AgendaIdenP_b AS cc FROM {$BaseDatos_systema}.AGENDADOR LEFT JOIN {$BaseDatos_systema}.GUION_ ON AGENDADOR_ConsInte__GUION__Dis_b=GUION__ConsInte__b WHERE GUION__ConsInte__PROYEC_b={$_SESSION['HUESPED']}");

        
        if($sql && $sql->num_rows > 0){
            $strHtml='<option value="0">Seleccione</option>';
            while($row =  $sql->fetch_object()){
                $strHtml.="<option value='{$row->id}' guion='{$row->disponible}' cc='{$row->cc}'>{$row->nombre}</option>";
            }
        }else{
            $strHtml="<option value=''> No hay Agendadores creados </option>";
        }

        echo $strHtml;
    }

    if(isset($_POST['getAgendadorGuion'])){
        $sql=$mysqli->query("SELECT AGENDADOR_ConsInte__b AS id, AGENDADOR_Nombre____b AS nombre FROM {$BaseDatos_systema}.AGENDADOR WHERE AGENDADOR_ConsInte__GUION__Dis_b={$_POST['id']}");

        
        if($sql && $sql->num_rows > 0){
            while($row =  $sql->fetch_object()){
                $strHtml="<option value='{$row->id}'>{$row->nombre}</option>";
            }
        }else{
            $strHtml="<option value=''> No hay Agendadores creados </option>";
        }

        echo $strHtml;
    }

    if(isset($_POST['getCampoCcAgendador'])){
        $padre=isset($_POST['padre']) && !is_numeric($_POST['padre']) ? $_POST['padre'] : false;
        $hijo=isset($_POST['hijo']) && is_numeric($_POST['hijo']) ? $_POST['hijo'] : false;

        if($padre && $hijo){
            
            $sql=$mysqli->query("SELECT GUIDET_ConsInte__PREGUN_Ma1_b AS id FROM {$BaseDatos_systema}.GUIDET WHERE md5(concat('".clave_get."', GUIDET_ConsInte__GUION__Mae_b))='{$padre}' AND GUIDET_ConsInte__GUION__Det_b={$hijo};");

            if($sql && $sql->num_rows == 1){
                $sql=$sql->fetch_object();
                $sqlPregun=$mysqli->query("SELECT PREGUN_Texto_____b FROM {$BaseDatos_systema}.PREGUN WHERE PREGUN_ConsInte__b={$sql->id}");

                if($sqlPregun && $sqlPregun->num_rows == 1){
                    $sqlPregun=$sqlPregun->fetch_object();
                    echo "<option value='{$sql->id}'>{$sqlPregun->PREGUN_Texto_____b}</option>";
                }else{
                    echo 03;
                }
            }else{
                echo 02;
            }
        }else{
            echo 01;
        }
    }

    function dameIdCampo($idCampo){

        global $mysqli;
        global $BaseDatos_systema;
        $Lsql = "SELECT CAMPO__ConsInte__PREGUN_b FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__b = ".$idCampo;
        $res  = $mysqli->query($Lsql);
        $dato = $res->fetch_array();

        return $dato['CAMPO__ConsInte__PREGUN_b'];
    
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
