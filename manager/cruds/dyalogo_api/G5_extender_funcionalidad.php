<?php
    session_start();
    include(__DIR__."../../../../pages/conexion.php");

    //Este archivo es para agregar funcionalidades al G, y que al momento de generar de nuevo no se pierdan

    //Cosas como nuevas consultas, nuevos Inserts, Envio de correos, etc, en fin extender mas los formularios en PHP


    if(isset($_GET['camposGuion'])){
    	$Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE G6_C40 != 12 AND G6_C40 != 9 AND G6_C207 = ".$_POST['guion']." AND G7_C38 = 1 AND G6_C209 != 3;";
        //echo $Lsql ;
    	$res_Lsql = $mysqli->query($Lsql);
        echo "<option value=\"0\"></option>";
    	while ($key = $res_Lsql->fetch_object()) {
    		echo "<option value='".$key->id."'>".utf8_encode($key->G6_C39)."</option>";
    	}
    }

    if(isset($_GET['camposcorreo'])){
        $Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6 JOIN ".$BaseDatos_systema.".G7 ON G6_C32 = G7_ConsInte__b WHERE G6_C40 != 12 AND G6_C40 != 9 AND G6_C207 = ".$_POST['guion']." AND G7_C38 = 1 AND G6_C209 != 3;";
        //echo $Lsql ;
        $res_Lsql = $mysqli->query($Lsql);
        echo "<option value=\"0\">Campo correo</option>";
        while ($key = $res_Lsql->fetch_object()) {
            echo "<option value='".$key->id."'>".utf8_encode($key->G6_C39)."</option>";
        }
    }


    if(isset($_GET['camposGuion_incude_id'])){
        $Lsql = "SELECT  G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6 WHERE G6_C40 != 12 AND G6_C207 = ".$_POST['guion']." AND G6_C209 != 3 ";
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

        $Lsql = "SELECT LISOPC_ConsInte__b, LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$_POST['idOpcion']." ORDER BY LISOPC_Nombre____b ASC";
        $res = $mysqli->query($Lsql);
        $datosL = array();
        $i = 0;
        while ($key = $res->fetch_object()) {
            $datosL[$i]['LISOPC_Nombre____b'] = $key->LISOPC_Nombre____b;
            $datosL[$i]['LISOPC_ConsInte__b'] = $key->LISOPC_ConsInte__b;
            $i++;
        }

        echo json_encode(array('code' => '1' , 'opcion' => $datosO['OPCION_Nombre____b'], 'id' => $datosO['OPCION_ConsInte__b'], 'lista' => $datosL, 'total' => $i));
    }


    if(isset($_GET['traerCuentas'])){
        $Lsql = "SELECT * FROM ".$dyalogo_canales_electronicos.".dy_ce_configuracion WHERE ;";
        echo "<option value='0'>Seleccione cuenta</option>";
        $cur_result = $mysqli->query($Lsql);
        while ($key = $cur_result->fetch_object()) {
            echo "<option value='".$key->id."'>".$key->direccion_correo_electronico."</option>";
        }
    }

    if(isset($_POST['validarParaBorrar'])){
        $LsqlCampan = "SELECT CAMPAN_Nombre____b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__GUION__Pob_b = ".$_POST['id']." LIMIT 1";
        
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