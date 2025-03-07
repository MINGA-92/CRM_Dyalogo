<?php

    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."/../../conexion.php");
    date_default_timezone_set('America/Bogota');
        
    //Inserciones o actualizaciones

    if(isset($_POST['getListaHija'])){
        $Lsql = "SELECT LISOPC_ConsInte__b , LISOPC_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__LISOPC_Depende_b = ".$_POST['idPadre']." AND LISOPC_ConsInte__OPCION_b = ".$_POST['opcionID'];
        $res = $mysqli->query($Lsql);
        echo "<option value='0'>Seleccione</option>";
        while($key = $res->fetch_object()){
            echo "<option value='".$key->LISOPC_ConsInte__b."'>".$key->LISOPC_Nombre____b."</option>";
        }
    }
        
    if(isset($_POST["oper"])){
            $str_Lsql  = '';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos.".G3400 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos.".G3400( G3400_FechaInsercion ,";
            $str_LsqlV = " VALUES ('".date('Y-m-d H:s:i')."',"; 
  
        if(isset($_POST["G3400_C69405"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G3400_C69405 = '".$_POST["G3400_C69405"]."'";
            $str_LsqlI .= $separador."G3400_C69405";
            $str_LsqlV .= $separador."'".$_POST["G3400_C69405"]."'";
            $validar = 1;
        }
         
  
        $G3400_C69650 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G3400_C69650"])){
            if($_POST["G3400_C69650"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G3400_C69650 = $_POST["G3400_C69650"];
                $G3400_C69650 = str_replace(".", "", $_POST["G3400_C69650"]);
                $G3400_C69650 =  str_replace(",", ".", $G3400_C69650);
                $str_LsqlU .= $separador." G3400_C69650 = '".$G3400_C69650."'";
                $str_LsqlI .= $separador." G3400_C69650";
                $str_LsqlV .= $separador."'".$G3400_C69650."'";
                $validar = 1;
            }
        }
  
        if(isset($_POST["G3400_C70063"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G3400_C70063 = '".$_POST["G3400_C70063"]."'";
            $str_LsqlI .= $separador."G3400_C70063";
            $str_LsqlV .= $separador."'".$_POST["G3400_C70063"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G3400_C70150"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G3400_C70150 = '".$_POST["G3400_C70150"]."'";
            $str_LsqlI .= $separador."G3400_C70150";
            $str_LsqlV .= $separador."'".$_POST["G3400_C70150"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G3400_C70151"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G3400_C70151 = '".$_POST["G3400_C70151"]."'";
            $str_LsqlI .= $separador."G3400_C70151";
            $str_LsqlV .= $separador."'".$_POST["G3400_C70151"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G3400_C70152"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G3400_C70152 = '".$_POST["G3400_C70152"]."'";
            $str_LsqlI .= $separador."G3400_C70152";
            $str_LsqlV .= $separador."'".$_POST["G3400_C70152"]."'";
            $validar = 1;
        }
         
 
        $padre = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        if(isset($_POST["padre"])){    
            if($_POST["padre"] != '0' && $_POST['padre'] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //primero hay que ir y buscar los campos
                $str_Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST['formpadre']." AND GUIDET_ConsInte__GUION__Det_b = ".$_POST['formhijo'];

                $GuidRes = $mysqli->query($str_Lsql);
                $campo = null;
                while($ky = $GuidRes->fetch_object()){
                    $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                }
                $valorG = "G3400_C";
                $valorH = $valorG.$campo;
                $str_LsqlU .= $separador." " .$valorH." = ".$_POST["padre"];
                $str_LsqlI .= $separador." ".$valorH;
                $str_LsqlV .= $separador.$_POST['padre'] ;
                $validar = 1;
            }
        }

        if(isset($_GET['id_gestion_cbx'])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G3400_IdLlamada = '".$_GET['id_gestion_cbx']."'";
            $str_LsqlI .= $separador."G3400_IdLlamada";
            $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
            $validar = 1;
        }


        if(isset($_POST['oper'])){
            if($_POST["oper"] == 'add' ){
                
                $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
            }
        }

        //Si trae algo que insertar inserta

        //echo $str_Lsql;
        if($validar == 1){
            if ($mysqli->query($str_Lsql) === TRUE) {
                $ultimoResgistroInsertado = $mysqli->insert_id;
                //ahora toca ver lo de la muestra asi que toca ver que pasa 
                /* primero buscamos la campaña que nos esta llegando */
                $Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b , CAMPAN_ActPobGui_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_POST["campana"];

                //echo $Lsql_Campan;

                $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
                $datoCampan = $res_Lsql_Campan->fetch_array();
                $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
                $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
                $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];
                $int_CAMPAN_ActPo = $datoCampan['CAMPAN_ActPobGui_b'];


                if($int_CAMPAN_ActPo == '-1'){
                    /* toca hacer actualizacion desde Script */
                    
                    $campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$_POST["campana"];
                    $resultcampSql = $mysqli->query($campSql);
                    $Lsql = 'UPDATE '.$BaseDatos.'.'.$str_Pobla_Campan.' , '.$BaseDatos.'.G'.$int_Guion_Campan.' SET ';
                    $i=0;
                    while($key = $resultcampSql->fetch_object()){

                        if($i == 0){
                            $Lsql .= $key->CAMINC_NomCamPob_b . ' = '.$key->CAMINC_NomCamGui_b;
                        }else{
                            $Lsql .= " , ".$key->CAMINC_NomCamPob_b . ' = '.$key->CAMINC_NomCamGui_b;
                        }
                        $i++;
                    } 
                    $Lsql .= ' WHERE  G'.$int_Guion_Campan.'_ConsInte__b = '.$ultimoResgistroInsertado.' AND G'.$int_Guion_Campan.'_CodigoMiembro = '.$str_Pobla_Campan.'_ConsInte__b'; 
                    //echo "Esta ".$Lsql;
                    if($mysqli->query($Lsql) === TRUE ){

                    }else{
                        echo "NO sE ACTALIZO LA BASE DE DATOS ".$mysqli->error;
                    }
                }


                //Ahora toca actualizar la muestra
                $MuestraSql = "UPDATE ".$BaseDatos.".".$str_Pobla_Campan."_M".$int_Muest_Campan." SET 
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_Estado____b = 3, 
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b = ".$str_Pobla_Campan."_M".$int_Muest_Campan."_NumeInte__b + 1, 
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_UltiGest__b = '-9' , 
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_ConUltGes_b = 7,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecUltGes_b = '".date('Y-m-d H:i:s')."', 
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_EfeUltGes_b = 3,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_Comentari_b = 'No desea participar',
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_FecHorAge_b = NULL,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_GesMasImp_b = '-9',
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoGesMaIm_b = 7,
                        ".$str_Pobla_Campan."_M".$int_Muest_Campan."_FeGeMaIm__b ='".date('Y-m-d H:i:s')."'";
                $MuestraSql .= " WHERE ".$str_Pobla_Campan."_M".$int_Muest_Campan."_CoInMiPo__b = ".$_POST['id'];
                // echo $MuestraSql;
                if($mysqli->query($MuestraSql) === true){

                }else{
                    echo "Error insertando la muesta => ".$mysqli->error;
                }
                
                header('Location:http://'.$_SERVER['HTTP_HOST'].'/crm_php/web_forms.php?web=MzQwMA==&result=1');

            } else {
                echo "Error Hacieno el proceso los registros : " . $mysqli->error;
            }
        }
    }
    


?>
