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
            $str_LsqlU = "UPDATE ".$BaseDatos.".G2992 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos.".G2992( G2992_FechaInsercion ,";
            $str_LsqlV = " VALUES ('".date('Y-m-d H:s:i')."',"; 
  
        if(isset($_POST["G2992_C59910"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59910 = '".$_POST["G2992_C59910"]."'";
            $str_LsqlI .= $separador."G2992_C59910";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59910"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59911"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59911 = '".$_POST["G2992_C59911"]."'";
            $str_LsqlI .= $separador."G2992_C59911";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59911"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59913"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59913 = '".$_POST["G2992_C59913"]."'";
            $str_LsqlI .= $separador."G2992_C59913";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59913"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59914"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59914 = '".$_POST["G2992_C59914"]."'";
            $str_LsqlI .= $separador."G2992_C59914";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59914"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59912"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59912 = '".$_POST["G2992_C59912"]."'";
            $str_LsqlI .= $separador."G2992_C59912";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59912"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59915"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59915 = '".$_POST["G2992_C59915"]."'";
            $str_LsqlI .= $separador."G2992_C59915";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59915"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59951"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59951 = '".$_POST["G2992_C59951"]."'";
            $str_LsqlI .= $separador."G2992_C59951";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59951"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59952"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59952 = '".$_POST["G2992_C59952"]."'";
            $str_LsqlI .= $separador."G2992_C59952";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59952"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C59953"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C59953 = '".$_POST["G2992_C59953"]."'";
            $str_LsqlI .= $separador."G2992_C59953";
            $str_LsqlV .= $separador."'".$_POST["G2992_C59953"]."'";
            $validar = 1;
        }
         
  
        $G2992_C61722 = 0;
        //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
        if(isset($_POST["G2992_C61722"])){
            if($_POST["G2992_C61722"] == 'Yes'){
                $G2992_C61722 = 1;
            }else if($_POST["G2992_C61722"] == 'off'){
                $G2992_C61722 = 0;
            }else if($_POST["G2992_C61722"] == 'on'){
                $G2992_C61722 = 1;
            }else if($_POST["G2992_C61722"] == 'No'){
                $G2992_C61722 = 0;
            }else{
                $G2992_C61722 = $_POST["G2992_C61722"] ;
            }   

            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador." G2992_C61722 = ".$G2992_C61722."";
            $str_LsqlI .= $separador." G2992_C61722";
            $str_LsqlV .= $separador.$G2992_C61722;

            $validar = 1;
        }else{
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador." G2992_C61722 = ".$G2992_C61722."";
            $str_LsqlI .= $separador." G2992_C61722";
            $str_LsqlV .= $separador.$G2992_C61722;

            $validar = 1;
        }
  
        if(isset($_POST["G2992_C66507"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C66507 = '".$_POST["G2992_C66507"]."'";
            $str_LsqlI .= $separador."G2992_C66507";
            $str_LsqlV .= $separador."'".$_POST["G2992_C66507"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C61320"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C61320 = '".$_POST["G2992_C61320"]."'";
            $str_LsqlI .= $separador."G2992_C61320";
            $str_LsqlV .= $separador."'".$_POST["G2992_C61320"]."'";
            $validar = 1;
        }
         
 
        $G2992_C66401 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no
        if(isset($_POST["G2992_C66401"])){    
            if($_POST["G2992_C66401"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }
                $G2992_C66401 = "'".str_replace(' ', '',$_POST["G2992_C66401"])." 00:00:00'";
                $str_LsqlU .= $separador." G2992_C66401 = ".$G2992_C66401;
                $str_LsqlI .= $separador." G2992_C66401";
                $str_LsqlV .= $separador.$G2992_C66401;
                $validar = 1;
            }
        }
  
        if(isset($_POST["G2992_C61319"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C61319 = '".$_POST["G2992_C61319"]."'";
            $str_LsqlI .= $separador."G2992_C61319";
            $str_LsqlV .= $separador."'".$_POST["G2992_C61319"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C66484"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C66484 = '".$_POST["G2992_C66484"]."'";
            $str_LsqlI .= $separador."G2992_C66484";
            $str_LsqlV .= $separador."'".$_POST["G2992_C66484"]."'";
            $validar = 1;
        }
         
  
        $G2992_C66402 = NULL;
        //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
        if(isset($_POST["G2992_C66402"])){   
            if($_POST["G2992_C66402"] != '' && $_POST["G2992_C66402"] != 'undefined' && $_POST["G2992_C66402"] != 'null'){
                $separador = "";
                $fecha = date('Y-m-d');
                if($validar == 1){
                    $separador = ",";
                }

                $G2992_C66402 = "'".$fecha." ".str_replace(' ', '',$_POST["G2992_C66402"])."'";
                $str_LsqlU .= $separador." G2992_C66402 = ".$G2992_C66402."";
                $str_LsqlI .= $separador." G2992_C66402";
                $str_LsqlV .= $separador.$G2992_C66402;
                $validar = 1;
            }
        }
  
        $G2992_C65875 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65875"])){
            if($_POST["G2992_C65875"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65875 = $_POST["G2992_C65875"];
                $G2992_C65875 = str_replace(".", "", $_POST["G2992_C65875"]);
                $G2992_C65875 =  str_replace(",", ".", $G2992_C65875);
                $str_LsqlU .= $separador." G2992_C65875 = '".$G2992_C65875."'";
                $str_LsqlI .= $separador." G2992_C65875";
                $str_LsqlV .= $separador."'".$G2992_C65875."'";
                $validar = 1;
            }
        }
  
        $G2992_C65876 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65876"])){
            if($_POST["G2992_C65876"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65876 = $_POST["G2992_C65876"];
                $G2992_C65876 = str_replace(".", "", $_POST["G2992_C65876"]);
                $G2992_C65876 =  str_replace(",", ".", $G2992_C65876);
                $str_LsqlU .= $separador." G2992_C65876 = '".$G2992_C65876."'";
                $str_LsqlI .= $separador." G2992_C65876";
                $str_LsqlV .= $separador."'".$G2992_C65876."'";
                $validar = 1;
            }
        }
  
        $G2992_C65877 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65877"])){
            if($_POST["G2992_C65877"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65877 = $_POST["G2992_C65877"];
                $G2992_C65877 = str_replace(".", "", $_POST["G2992_C65877"]);
                $G2992_C65877 =  str_replace(",", ".", $G2992_C65877);
                $str_LsqlU .= $separador." G2992_C65877 = '".$G2992_C65877."'";
                $str_LsqlI .= $separador." G2992_C65877";
                $str_LsqlV .= $separador."'".$G2992_C65877."'";
                $validar = 1;
            }
        }
  
        $G2992_C65878 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65878"])){
            if($_POST["G2992_C65878"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65878 = $_POST["G2992_C65878"];
                $G2992_C65878 = str_replace(".", "", $_POST["G2992_C65878"]);
                $G2992_C65878 =  str_replace(",", ".", $G2992_C65878);
                $str_LsqlU .= $separador." G2992_C65878 = '".$G2992_C65878."'";
                $str_LsqlI .= $separador." G2992_C65878";
                $str_LsqlV .= $separador."'".$G2992_C65878."'";
                $validar = 1;
            }
        }
  
        $G2992_C65879 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65879"])){
            if($_POST["G2992_C65879"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65879 = $_POST["G2992_C65879"];
                $G2992_C65879 = str_replace(".", "", $_POST["G2992_C65879"]);
                $G2992_C65879 =  str_replace(",", ".", $G2992_C65879);
                $str_LsqlU .= $separador." G2992_C65879 = '".$G2992_C65879."'";
                $str_LsqlI .= $separador." G2992_C65879";
                $str_LsqlV .= $separador."'".$G2992_C65879."'";
                $validar = 1;
            }
        }
  
        $G2992_C65880 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65880"])){
            if($_POST["G2992_C65880"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65880 = $_POST["G2992_C65880"];
                $G2992_C65880 = str_replace(".", "", $_POST["G2992_C65880"]);
                $G2992_C65880 =  str_replace(",", ".", $G2992_C65880);
                $str_LsqlU .= $separador." G2992_C65880 = '".$G2992_C65880."'";
                $str_LsqlI .= $separador." G2992_C65880";
                $str_LsqlV .= $separador."'".$G2992_C65880."'";
                $validar = 1;
            }
        }
  
        $G2992_C65881 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65881"])){
            if($_POST["G2992_C65881"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65881 = $_POST["G2992_C65881"];
                $G2992_C65881 = str_replace(".", "", $_POST["G2992_C65881"]);
                $G2992_C65881 =  str_replace(",", ".", $G2992_C65881);
                $str_LsqlU .= $separador." G2992_C65881 = '".$G2992_C65881."'";
                $str_LsqlI .= $separador." G2992_C65881";
                $str_LsqlV .= $separador."'".$G2992_C65881."'";
                $validar = 1;
            }
        }
  
        $G2992_C65882 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65882"])){
            if($_POST["G2992_C65882"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65882 = $_POST["G2992_C65882"];
                $G2992_C65882 = str_replace(".", "", $_POST["G2992_C65882"]);
                $G2992_C65882 =  str_replace(",", ".", $G2992_C65882);
                $str_LsqlU .= $separador." G2992_C65882 = '".$G2992_C65882."'";
                $str_LsqlI .= $separador." G2992_C65882";
                $str_LsqlV .= $separador."'".$G2992_C65882."'";
                $validar = 1;
            }
        }
  
        $G2992_C65883 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65883"])){
            if($_POST["G2992_C65883"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65883 = $_POST["G2992_C65883"];
                $G2992_C65883 = str_replace(".", "", $_POST["G2992_C65883"]);
                $G2992_C65883 =  str_replace(",", ".", $G2992_C65883);
                $str_LsqlU .= $separador." G2992_C65883 = '".$G2992_C65883."'";
                $str_LsqlI .= $separador." G2992_C65883";
                $str_LsqlV .= $separador."'".$G2992_C65883."'";
                $validar = 1;
            }
        }
  
        $G2992_C65884 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65884"])){
            if($_POST["G2992_C65884"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65884 = $_POST["G2992_C65884"];
                $G2992_C65884 = str_replace(".", "", $_POST["G2992_C65884"]);
                $G2992_C65884 =  str_replace(",", ".", $G2992_C65884);
                $str_LsqlU .= $separador." G2992_C65884 = '".$G2992_C65884."'";
                $str_LsqlI .= $separador." G2992_C65884";
                $str_LsqlV .= $separador."'".$G2992_C65884."'";
                $validar = 1;
            }
        }
  
        $G2992_C65885 = NULL;
        //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
        if(isset($_POST["G2992_C65885"])){
            if($_POST["G2992_C65885"] != ''){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                //$G2992_C65885 = $_POST["G2992_C65885"];
                $G2992_C65885 = str_replace(".", "", $_POST["G2992_C65885"]);
                $G2992_C65885 =  str_replace(",", ".", $G2992_C65885);
                $str_LsqlU .= $separador." G2992_C65885 = '".$G2992_C65885."'";
                $str_LsqlI .= $separador." G2992_C65885";
                $str_LsqlV .= $separador."'".$G2992_C65885."'";
                $validar = 1;
            }
        }
  
        if(isset($_POST["G2992_C71470"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C71470 = '".$_POST["G2992_C71470"]."'";
            $str_LsqlI .= $separador."G2992_C71470";
            $str_LsqlV .= $separador."'".$_POST["G2992_C71470"]."'";
            $validar = 1;
        }
         
  
        if(isset($_POST["G2992_C72118"])){
            $separador = "";
            if($validar == 1){
                $separador = ",";
            }

            $str_LsqlU .= $separador."G2992_C72118 = '".$_POST["G2992_C72118"]."'";
            $str_LsqlI .= $separador."G2992_C72118";
            $str_LsqlV .= $separador."'".$_POST["G2992_C72118"]."'";
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
                $valorG = "G2992_C";
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

            $str_LsqlU .= $separador."G2992_IdLlamada = '".$_GET['id_gestion_cbx']."'";
            $str_LsqlI .= $separador."G2992_IdLlamada";
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
                
                header('Location:http://'.$_SERVER['HTTP_HOST'].'/crm_php/web_forms.php?web=Mjk5Mg==&result=1');

            } else {
                echo "Error Hacieno el proceso los registros : " . $mysqli->error;
            }
        }
    }
    


?>
