<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);

	include ('pages/conexion.php');

	/*$Lsql = "SELECT MUESTR_ConsInte__GUION__b, ESTPAS_ConsInte__MUESTR_b FROM ".$BaseDatos_systema.".MUESTR JOIN ".$BaseDatos_systema.".ESTPAS ON MUESTR_ConsInte__b = ESTPAS_ConsInte__MUESTR_b WHERE ESTPAS_Tipo______b = 8;";
   	// echo $Lsql;
	$res_Lsql_Paso = $mysqli->query($Lsql);

	while ($key = $res_Lsql_Paso->fetch_object()) {
		/* Armo la muestra completa */
		/*$muestraCompleta = "G".$key->MUESTR_ConsInte__GUION__b."_M".$key->ESTPAS_ConsInte__MUESTR_b;
        $muestraId = $key->ESTPAS_ConsInte__MUESTR_b;

        $Lsql = "UPDATE ".$BaseDatos.".".$muestraCompleta." SET ".$muestraCompleta."_Estado____b = 3 ";
        if($mysqli->query($Lsql) === true){

        }else{
        	echo "Error => ".$mysqli->error;
        }

	}*/


    $ActualizaLsql = "SELECT CAMPAN_ActPobGui_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['campana_crm']; 
    $resultado = $mysqli->query($ActualizaLsql);
    $datoArray = $resultado->fetch_array();
    if($datoArray['CAMPAN_ActPobGui_b'] == '-1'){
        /* toca hacer actualizacion desde Script */
        

        /* primero buscamos la campaña que nos esta llegando */
        $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['campana_crm'];

        //echo $Lsql_Campan;

        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
        $datoCampan = $res_Lsql_Campan->fetch_array();
        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];


        $campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$_GET['campana_crm'];
        $resultcampSql = $mysqli->query($campSql);
        $Lsql = 'UPDATE '.$BaseDatos.'.'.$str_Pobla_Campan.' , '.$BaseDatos.'.G'.$int_Guion_Campan.' SET ';
        $i=0;
        while($key = $resultcampSql->fetch_object()){
            $validoparaedicion = false;
            $valorScript = $key->CAMINC_NomCamGui_b;

            $LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".".$str_Pobla_Campan." WHERE Field = '".$key->CAMINC_NomCamPob_b."'";

            $resultShow = $mysqli->query($LsqlShow);
            if($resultShow->num_rows === 0){
                //comentario el campo no existe
                $validoparaedicion = false;
            }else{
                $validoparaedicion = true;
            } 

            $LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".G".$int_Guion_Campan." WHERE Field = '".$key->CAMINC_NomCamGui_b."'";
            //echo $LsqlShow;
            $resultShow = $mysqli->query($LsqlShow);
            if($resultShow->num_rows === 0 ){
                //comentario el campo no existe
                $validoparaedicion = false;
            }else{
                $validoparaedicion = true;
            } 

            /*$LsqlPAsaNull = "SELECT ".$key->CAMINC_NomCamGui_b." as Campo_valido FROM ".$BaseDatos.".G".$int_Guion_Campan." WHERE  G".$int_Guion_Campan.'_ConsInte__b = '.$_GET['consinteRegresado'].' ;';
            $LsqlRes = $mysqli->query($LsqlPAsaNull);
            if($LsqlRes){
                $sata = $LsqlRes->fetch_array();
                if($sata['Campo_valido'] != '' && $sata['Campo_valido'] != null){

                }else{
                    $valorScript = 'NULL';
                }
            }*/

            if($validoparaedicion){
                if($i == 0){
                    $Lsql .= $key->CAMINC_NomCamPob_b . ' = '.$valorScript;
                }else{
                    $Lsql .= " , ".$key->CAMINC_NomCamPob_b . ' = '.$valorScript;
                }
                $i++;    
            }
            
        } 
        $Lsql .= ' WHERE G'.$int_Guion_Campan.'_CodigoMiembro = '.$str_Pobla_Campan.'_ConsInte__b'; 
        //$Lsql .= ' WHERE G'.$int_Guion_Campan.'_CodigoMiembro = '.$str_Pobla_Campan.'_ConsInte__b'; 
        echo "Esta ".$Lsql;
        if($mysqli->query($Lsql) === TRUE ){

        }else{
            echo "NO SE ACTALIZO LA BASE DE DATOS ".$mysqli->error;
        }
    }

    

    /* Ahora obnemps el cbx del man */
    /*$LsqlCbx = "SELECT USUARI_UsuaCBX___b FROM ".$BaseDatos_systema.".USUARI WHERE USUARI_ConsInte__b = ".$_GET['usuario'];
    $ResUsuari = $mysqli->query($LsqlCbx);
    $usuarioCbx = $ResUsuari->fetch_array();

    /* Ahora obtenemos el id del huesped que no tenga asociado */
    /*$Lsql = "SELECT id FROM dyalogo_general.huespedes WHERE id NOT IN(SELECT id_huesped FROM dyalogo_general.huespedes_usuarios WHERE id_usuario =".$usuarioCbx['USUARI_UsuaCBX___b'].");";
    $ResHuespedes = $mysqli->query($Lsql);
    while ($key = $ResHuespedes->fetch_object()) {
        
        $Lslq = "INSERT INTO dyalogo_general.huespedes_usuarios(id_huesped, id_usuario) VALUES (".$key->id.", ".$usuarioCbx['USUARI_UsuaCBX___b'].")";
        if($mysqli->query($Lslq) === true){

        }else{
            echo "Error no pude insertar lo => ".$mysqli->error;
        }
    }

    /* Ontenemos todos los guiones que sean Bases de dtaos */
    /*Lsql = "SELECT GUION__ConsInte__b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__Tipo______b = 2";
    $res = $mysqli->query($Lsql);
    if($res){
        while ($jey = $res->fetch_object()) {
            $int_Pobla_Camp_2 = $jey->GUION__ConsInte__b;
            $LsqPregun  = "SELECT PREGUN_ConsInte__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$int_Pobla_Camp_2." AND PREGUN_Texto_____b = 'ESTADO_DY'";

            $resPregun  = $mysqli->query($LsqPregun);
            if($resPregun->num_rows > 0){
                //El campo esta en la tabla pregun por lo menos
                $datoPregun = $resPregun->fetch_array();
                $LsqlShow = "SHOW COLUMNS FROM ".$BaseDatos.".G".$int_Pobla_Camp_2." WHERE Field = 'G".$int_Pobla_Camp_2."_C".$datoPregun['PREGUN_ConsInte__b']."'";
                $resultShow = $mysqli->query($LsqlShow);
                //echo $LsqlShow;
                if($resultShow->num_rows === 0){
                    //comentario el campo no existe
                }else{
                    //El campo esta creado y toca llenarlo con el valor de  
                    $LsqlUpdate = "UPDATE ".$BaseDatos.".G".$int_Pobla_Camp_2." SET G".$int_Pobla_Camp_2."_C".$datoPregun['PREGUN_ConsInte__b']." = NULL WHERE G".$int_Pobla_Camp_2."_C".$datoPregun['PREGUN_ConsInte__b']." = 0; ";
                    if($mysqli->query($LsqlUpdate) === true){
                        //Lo logro
                    }else{
                        echo "Error Actualizando el estado_dy => ".$mysqli->error;
                    }
                }   
            }
        }
    }*/
?>