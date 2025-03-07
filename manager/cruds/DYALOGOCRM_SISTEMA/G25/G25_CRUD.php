<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    //include(__DIR__."../../../../pages/conexion.php");
    include(__DIR__."/../../conexion.php");
    date_default_timezone_set('America/Bogota');

    function guardar_auditoria($accion, $superAccion){
        //include(__DIR__."../../../../pages/conexion.php");
        include(__DIR__."/../../conexion.php");
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G25', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }  

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G25_ConsInte__b, G25_C249 as principal ,G25_C249,G25_C250,G25_C251,G25_C252,G25_C253,G25_C254,G25_C255,G25_C257,G25_C258,G25_C259,G25_C260,G25_C261,G25_C262,G25_C263,G25_C264,G25_C265,G25_C266,G25_C267,G25_C268,G25_C269,G25_C272,G25_C273,G25_C274,G25_C275,G25_C276,G25_C277,G25_C278,G25_C279,G25_C285,G25_C286,G25_C289,G25_C290,G25_C291,G25_C292,G25_C295,G25_C296,G25_C297,G25_C298,G25_C299,G25_C300 FROM '.$BaseDatos.'.G25 WHERE G25_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G25_C249'] = $key->G25_C249;

                $datos[$i]['G25_C250'] = $key->G25_C250;

                $datos[$i]['G25_C251'] = $key->G25_C251;

                $datos[$i]['G25_C252'] = $key->G25_C252;

                $datos[$i]['G25_C253'] = $key->G25_C253;

                $datos[$i]['G25_C254'] = $key->G25_C254;

                $datos[$i]['G25_C255'] = $key->G25_C255;

                $datos[$i]['G25_C257'] = $key->G25_C257;

                $datos[$i]['G25_C258'] = $key->G25_C258;

                $datos[$i]['G25_C259'] = $key->G25_C259;

                $datos[$i]['G25_C260'] = $key->G25_C260;

                $datos[$i]['G25_C261'] = $key->G25_C261;

                $datos[$i]['G25_C262'] = $key->G25_C262;

                $datos[$i]['G25_C263'] = $key->G25_C263;

                $datos[$i]['G25_C264'] = $key->G25_C264;

                $datos[$i]['G25_C265'] = $key->G25_C265;

                $datos[$i]['G25_C266'] = $key->G25_C266;

                $datos[$i]['G25_C267'] = $key->G25_C267;
  
                if(!is_null($key->G25_C268)){
                    $datos[$i]['G25_C268'] = explode(' ', $key->G25_C268)[1];
                }

                $datos[$i]['G25_C269'] = $key->G25_C269;

                $datos[$i]['G25_C272'] = $key->G25_C272;

                $datos[$i]['G25_C273'] = $key->G25_C273;

                $datos[$i]['G25_C274'] = $key->G25_C274;

                $datos[$i]['G25_C275'] = $key->G25_C275;

                $datos[$i]['G25_C276'] = $key->G25_C276;

                $datos[$i]['G25_C277'] = $key->G25_C277;

                $datos[$i]['G25_C278'] = $key->G25_C278;

                $datos[$i]['G25_C279'] = $key->G25_C279;

                $datos[$i]['G25_C285'] = $key->G25_C285;

                $datos[$i]['G25_C286'] = $key->G25_C286;

                $datos[$i]['G25_C289'] = $key->G25_C289;

                $datos[$i]['G25_C290'] = $key->G25_C290;

                $datos[$i]['G25_C291'] = $key->G25_C291;

                $datos[$i]['G25_C292'] = $key->G25_C292;

                $datos[$i]['G25_C295'] = $key->G25_C295;

                $datos[$i]['G25_C296'] = $key->G25_C296;

                $datos[$i]['G25_C297'] = $key->G25_C297;

                $datos[$i]['G25_C298'] = $key->G25_C298;

                $datos[$i]['G25_C299'] = $key->G25_C299;

                $datos[$i]['G25_C300'] = $key->G25_C300;
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = "SELECT G25_ConsInte__b as id,  G25_C249 as camp1 , b.LISOPC_Nombre____b as camp2 ";
            $str_Lsql .= " FROM ".$BaseDatos.".G25   LEFT JOIN ".$BaseDatos_systema.".LISOPC as b ON b.LISOPC_ConsInte__b = G25_C250 ";
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= ' WHERE  like "%'.$_POST['Busqueda'].'%" ';
                $str_Lsql .= ' OR  like "%'.$_POST['Busqueda'].'%" ';
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 25";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $str_Lsql .= ' AND G25_Usuario = '.$idUsuario;
                }else{
                    $str_Lsql .= ' WHERE G25_Usuario = '.$idUsuario;
                }
        
            }

            $str_Lsql .= ' ORDER BY G25_ConsInte__b DESC LIMIT 0, 50'; 
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;
            while($key = $result->fetch_object()){
                $datos[$i]['camp1'] = $key->camp1;
                $datos[$i]['camp2'] = $key->camp2;
                $datos[$i]['id'] = $key->id;
                $i++;
            }
            echo json_encode($datos);
        }


        //Esto ya es para cargar los combos en la grilla

        if(isset($_GET['CallDatosLisop_'])){
            $lista = $_GET['idLista'];
            $comboe = $_GET['campo'];
            $str_Lsql = "SELECT LISOPC_ConsInte__b AS OPCION_ConsInte__b, LISOPC_Nombre____b AS OPCION_Nombre____b FROM ".$BaseDatos_systema.".LISOPC WHERE LISOPC_ConsInte__OPCION_b = ".$lista." ORDER BY LISOPC_Nombre____b";
            
            $combo = $mysqli->query($str_Lsql);
            echo '<select class="form-control input-sm"  name="'.$comboe.'" id="'.$comboe.'">';
            echo '<option value="0">Seleccione</option>';
            while($obj = $combo->fetch_object()){
                echo "<option value='".$obj->OPCION_ConsInte__b."'>".$obj->OPCION_Nombre____b."</option>";
            }   
            echo '</select>'; 
        } 

        

        if(isset($_GET['CallDatosCombo_Guion_G25_C252'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C252" id="G25_C252">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C253'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C253" id="G25_C253">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C254'])){
            $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C254" id="G25_C254">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G12_C96)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C255'])){
            $Ysql = 'SELECT   G12_ConsInte__b as id , G12_C96 FROM ".$BaseDatos_systema.".G12';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C255" id="G25_C255">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G12_C96)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C257'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C257" id="G25_C257">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C258'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C258" id="G25_C258">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C259'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C259" id="G25_C259">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C260'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C260" id="G25_C260">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C261'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C261" id="G25_C261">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C272'])){
            $Ysql = 'SELECT   G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C272" id="G25_C272">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C275'])){
            $Ysql = 'SELECT   G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C275" id="G25_C275">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C278'])){
            $Ysql = 'SELECT   G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C278" id="G25_C278">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C279'])){
            $Ysql = 'SELECT   G27_ConsInte__b as id , G27_C280 FROM ".$BaseDatos_systema.".G27';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C279" id="G25_C279">';
            echo '<option >nombre</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G27_C280)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C285'])){
            $Ysql = 'SELECT   G28_ConsInte__b as id , G28_C283 FROM ".$BaseDatos_systema.".G28';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C285" id="G25_C285">';
            echo '<option >nombre</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G28_C283)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C289'])){
            $Ysql = 'SELECT   G29_ConsInte__b as id , G29_C287 FROM ".$BaseDatos_systema.".G29';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C289" id="G25_C289">';
            echo '<option >nombre</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G29_C287)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C290'])){
            $Ysql = 'SELECT   G28_ConsInte__b as id , G28_C283 FROM ".$BaseDatos_systema.".G28';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C290" id="G25_C290">';
            echo '<option >nombre</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G28_C283)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C292'])){
            $Ysql = 'SELECT   G30_ConsInte__b as id , G30_C293 FROM ".$BaseDatos_systema.".G30';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C292" id="G25_C292">';
            echo '<option >nombre</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G30_C293)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C296'])){
            $Ysql = 'SELECT   G27_ConsInte__b as id , G27_C280 FROM ".$BaseDatos_systema.".G27';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C296" id="G25_C296">';
            echo '<option >nombre</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G27_C280)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G25_C299'])){
            $Ysql = 'SELECT   G26_ConsInte__b as id , G26_C270 FROM ".$BaseDatos_systema.".G26';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G25_C299" id="G25_C299">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G26_C270)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G21_C204'])){
            $Ysql = 'SELECT   G10_ConsInte__b as id , G10_C71 FROM '.$BaseDatos_systema.'G10';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G21_C204" id="G21_C204">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G10_C71)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G21_C205'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM '.$BaseDatos_systema.'G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G21_C205" id="G21_C205">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G21_C206'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM '.$BaseDatos_systema.'G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G21_C206" id="G21_C206">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }





        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos.".G25 WHERE G25_ConsInte__b = ".$_POST['id'];
                if ($mysqli->query($str_Lsql) === TRUE) {
                    echo "1";
                } else {
                    echo "Error eliminado los registros : " . $mysqli->error;
                }
            }
        }

        if(isset($_POST['callDatosNuevamente'])){
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $Zsql = "SELECT  G25_ConsInte__b as id,  G25_C249 as camp1 , b.LISOPC_Nombre____b as camp2  FROM ".$BaseDatos.".G25   LEFT JOIN ".$BaseDatos_systema.".LISOPC as b ON b.LISOPC_ConsInte__b = G25_C250 ORDER BY G25_ConsInte__b DESC LIMIT ".$inicio." , ".$fin;
            $result = $mysqli->query($Zsql);
            while($obj = $result->fetch_object()){
                echo "<tr class='CargarDatos' id='".$obj->id."'>
                    <td>
                        <p style='font-size:14px;'><b>".($obj->camp1)."</b></p>
                        <p style='font-size:12px; margin-top:-10px;'>".($obj->camp2)."</p>
                    </td>
                </tr>";
            } 
        }
          
        //Insertar Extras en caso de haber
        


        //Inserciones o actualizaciones
        if(isset($_POST["oper"]) && isset($_GET['insertarDatosGrilla'])){
            $str_Lsql  = '';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos.".G25 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos.".G25(";
            $str_LsqlV = " VALUES ("; 
  
            if(isset($_POST["G25_C249"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C249 = '".$_POST["G25_C249"]."'";
                $str_LsqlI .= $separador."G25_C249";
                $str_LsqlV .= $separador."'".$_POST["G25_C249"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C250"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C250 = '".$_POST["G25_C250"]."'";
                $str_LsqlI .= $separador."G25_C250";
                $str_LsqlV .= $separador."'".$_POST["G25_C250"]."'";
                $validar = 1;
            }
             
  
            $G25_C251 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C251"])){
                if($_POST["G25_C251"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C251 = $_POST["G25_C251"];
                    $G25_C251 = str_replace(".", "", $_POST["G25_C251"]);
                    $G25_C251 =  str_replace(",", ".", $G25_C251);
                    $str_LsqlU .= $separador." G25_C251 = '".$G25_C251."'";
                    $str_LsqlI .= $separador." G25_C251";
                    $str_LsqlV .= $separador."'".$G25_C251."'";
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G25_C252"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C252 = '".$_POST["G25_C252"]."'";
                $str_LsqlI .= $separador."G25_C252";
                $str_LsqlV .= $separador."'".$_POST["G25_C252"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C253"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C253 = '".$_POST["G25_C253"]."'";
                $str_LsqlI .= $separador."G25_C253";
                $str_LsqlV .= $separador."'".$_POST["G25_C253"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C254"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C254 = '".$_POST["G25_C254"]."'";
                $str_LsqlI .= $separador."G25_C254";
                $str_LsqlV .= $separador."'".$_POST["G25_C254"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C255"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C255 = '".$_POST["G25_C255"]."'";
                $str_LsqlI .= $separador."G25_C255";
                $str_LsqlV .= $separador."'".$_POST["G25_C255"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C257"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C257 = '".$_POST["G25_C257"]."'";
                $str_LsqlI .= $separador."G25_C257";
                $str_LsqlV .= $separador."'".$_POST["G25_C257"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C258"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C258 = '".$_POST["G25_C258"]."'";
                $str_LsqlI .= $separador."G25_C258";
                $str_LsqlV .= $separador."'".$_POST["G25_C258"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C259"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C259 = '".$_POST["G25_C259"]."'";
                $str_LsqlI .= $separador."G25_C259";
                $str_LsqlV .= $separador."'".$_POST["G25_C259"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C260"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C260 = '".$_POST["G25_C260"]."'";
                $str_LsqlI .= $separador."G25_C260";
                $str_LsqlV .= $separador."'".$_POST["G25_C260"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C261"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C261 = '".$_POST["G25_C261"]."'";
                $str_LsqlI .= $separador."G25_C261";
                $str_LsqlV .= $separador."'".$_POST["G25_C261"]."'";
                $validar = 1;
            }
             
  
            $G25_C262 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C262"])){
                if($_POST["G25_C262"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C262 = $_POST["G25_C262"];
                    $G25_C262 = str_replace(".", "", $_POST["G25_C262"]);
                    $G25_C262 =  str_replace(",", ".", $G25_C262);
                    $str_LsqlU .= $separador." G25_C262 = '".$G25_C262."'";
                    $str_LsqlI .= $separador." G25_C262";
                    $str_LsqlV .= $separador."'".$G25_C262."'";
                    $validar = 1;
                }
            }
  
            $G25_C263 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C263"])){
                if($_POST["G25_C263"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C263 = $_POST["G25_C263"];
                    $G25_C263 = str_replace(".", "", $_POST["G25_C263"]);
                    $G25_C263 =  str_replace(",", ".", $G25_C263);
                    $str_LsqlU .= $separador." G25_C263 = '".$G25_C263."'";
                    $str_LsqlI .= $separador." G25_C263";
                    $str_LsqlV .= $separador."'".$G25_C263."'";
                    $validar = 1;
                }
            }
  
            $G25_C264 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C264"])){
                if($_POST["G25_C264"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C264 = $_POST["G25_C264"];
                    $G25_C264 = str_replace(".", "", $_POST["G25_C264"]);
                    $G25_C264 =  str_replace(",", ".", $G25_C264);
                    $str_LsqlU .= $separador." G25_C264 = '".$G25_C264."'";
                    $str_LsqlI .= $separador." G25_C264";
                    $str_LsqlV .= $separador."'".$G25_C264."'";
                    $validar = 1;
                }
            }
  
            $G25_C265 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C265"])){
                if($_POST["G25_C265"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C265 = $_POST["G25_C265"];
                    $G25_C265 = str_replace(".", "", $_POST["G25_C265"]);
                    $G25_C265 =  str_replace(",", ".", $G25_C265);
                    $str_LsqlU .= $separador." G25_C265 = '".$G25_C265."'";
                    $str_LsqlI .= $separador." G25_C265";
                    $str_LsqlV .= $separador."'".$G25_C265."'";
                    $validar = 1;
                }
            }
  
            $G25_C266 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C266"])){
                if($_POST["G25_C266"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C266 = $_POST["G25_C266"];
                    $G25_C266 = str_replace(".", "", $_POST["G25_C266"]);
                    $G25_C266 =  str_replace(",", ".", $G25_C266);
                    $str_LsqlU .= $separador." G25_C266 = '".$G25_C266."'";
                    $str_LsqlI .= $separador." G25_C266";
                    $str_LsqlV .= $separador."'".$G25_C266."'";
                    $validar = 1;
                }
            }
  
            $G25_C267 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G25_C267"])){
                if($_POST["G25_C267"] == 'Yes'){
                    $G25_C267 = 1;
                }else if($_POST["G25_C267"] == 'off'){
                    $G25_C267 = 0;
                }else if($_POST["G25_C267"] == 'on'){
                    $G25_C267 = 1;
                }else if($_POST["G25_C267"] == 'No'){
                    $G25_C267 = 0;
                }else{
                    $G25_C267 = $_POST["G25_C267"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C267 = ".$G25_C267."";
                $str_LsqlI .= $separador." G25_C267";
                $str_LsqlV .= $separador.$G25_C267;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C267 = ".$G25_C267."";
                $str_LsqlI .= $separador." G25_C267";
                $str_LsqlV .= $separador.$G25_C267;

                $validar = 1;
            }
  
            $G25_C268 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G25_C268"])){   
                if($_POST["G25_C268"] != '' && $_POST["G25_C268"] != 'undefined' && $_POST["G25_C268"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G25_C268 = "'".$fecha." ".str_replace(' ', '',$_POST["G25_C268"])."'";
                    $str_LsqlU .= $separador." G25_C268 = ".$G25_C268."";
                    $str_LsqlI .= $separador." G25_C268";
                    $str_LsqlV .= $separador.$G25_C268;
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G25_C269"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C269 = '".$_POST["G25_C269"]."'";
                $str_LsqlI .= $separador."G25_C269";
                $str_LsqlV .= $separador."'".$_POST["G25_C269"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C272"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C272 = '".$_POST["G25_C272"]."'";
                $str_LsqlI .= $separador."G25_C272";
                $str_LsqlV .= $separador."'".$_POST["G25_C272"]."'";
                $validar = 1;
            }
             
  
            $G25_C273 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G25_C273"])){
                if($_POST["G25_C273"] == 'Yes'){
                    $G25_C273 = 1;
                }else if($_POST["G25_C273"] == 'off'){
                    $G25_C273 = 0;
                }else if($_POST["G25_C273"] == 'on'){
                    $G25_C273 = 1;
                }else if($_POST["G25_C273"] == 'No'){
                    $G25_C273 = 0;
                }else{
                    $G25_C273 = $_POST["G25_C273"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C273 = ".$G25_C273."";
                $str_LsqlI .= $separador." G25_C273";
                $str_LsqlV .= $separador.$G25_C273;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C273 = ".$G25_C273."";
                $str_LsqlI .= $separador." G25_C273";
                $str_LsqlV .= $separador.$G25_C273;

                $validar = 1;
            }
  
            $G25_C274 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C274"])){
                if($_POST["G25_C274"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C274 = $_POST["G25_C274"];
                    $G25_C274 = str_replace(".", "", $_POST["G25_C274"]);
                    $G25_C274 =  str_replace(",", ".", $G25_C274);
                    $str_LsqlU .= $separador." G25_C274 = '".$G25_C274."'";
                    $str_LsqlI .= $separador." G25_C274";
                    $str_LsqlV .= $separador."'".$G25_C274."'";
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G25_C275"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C275 = '".$_POST["G25_C275"]."'";
                $str_LsqlI .= $separador."G25_C275";
                $str_LsqlV .= $separador."'".$_POST["G25_C275"]."'";
                $validar = 1;
            }
             
  
            $G25_C276 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G25_C276"])){
                if($_POST["G25_C276"] == 'Yes'){
                    $G25_C276 = 1;
                }else if($_POST["G25_C276"] == 'off'){
                    $G25_C276 = 0;
                }else if($_POST["G25_C276"] == 'on'){
                    $G25_C276 = 1;
                }else if($_POST["G25_C276"] == 'No'){
                    $G25_C276 = 0;
                }else{
                    $G25_C276 = $_POST["G25_C276"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C276 = ".$G25_C276."";
                $str_LsqlI .= $separador." G25_C276";
                $str_LsqlV .= $separador.$G25_C276;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C276 = ".$G25_C276."";
                $str_LsqlI .= $separador." G25_C276";
                $str_LsqlV .= $separador.$G25_C276;

                $validar = 1;
            }
  
            if(isset($_POST["G25_C277"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C277 = '".$_POST["G25_C277"]."'";
                $str_LsqlI .= $separador."G25_C277";
                $str_LsqlV .= $separador."'".$_POST["G25_C277"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C278"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C278 = '".$_POST["G25_C278"]."'";
                $str_LsqlI .= $separador."G25_C278";
                $str_LsqlV .= $separador."'".$_POST["G25_C278"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C279"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C279 = '".$_POST["G25_C279"]."'";
                $str_LsqlI .= $separador."G25_C279";
                $str_LsqlV .= $separador."'".$_POST["G25_C279"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C285"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C285 = '".$_POST["G25_C285"]."'";
                $str_LsqlI .= $separador."G25_C285";
                $str_LsqlV .= $separador."'".$_POST["G25_C285"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C286"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C286 = '".$_POST["G25_C286"]."'";
                $str_LsqlI .= $separador."G25_C286";
                $str_LsqlV .= $separador."'".$_POST["G25_C286"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C289"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C289 = '".$_POST["G25_C289"]."'";
                $str_LsqlI .= $separador."G25_C289";
                $str_LsqlV .= $separador."'".$_POST["G25_C289"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C290"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C290 = '".$_POST["G25_C290"]."'";
                $str_LsqlI .= $separador."G25_C290";
                $str_LsqlV .= $separador."'".$_POST["G25_C290"]."'";
                $validar = 1;
            }
             
  
            $G25_C291 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C291"])){
                if($_POST["G25_C291"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C291 = $_POST["G25_C291"];
                    $G25_C291 = str_replace(".", "", $_POST["G25_C291"]);
                    $G25_C291 =  str_replace(",", ".", $G25_C291);
                    $str_LsqlU .= $separador." G25_C291 = '".$G25_C291."'";
                    $str_LsqlI .= $separador." G25_C291";
                    $str_LsqlV .= $separador."'".$G25_C291."'";
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G25_C292"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C292 = '".$_POST["G25_C292"]."'";
                $str_LsqlI .= $separador."G25_C292";
                $str_LsqlV .= $separador."'".$_POST["G25_C292"]."'";
                $validar = 1;
            }
             
  
            $G25_C295 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G25_C295"])){
                if($_POST["G25_C295"] == 'Yes'){
                    $G25_C295 = 1;
                }else if($_POST["G25_C295"] == 'off'){
                    $G25_C295 = 0;
                }else if($_POST["G25_C295"] == 'on'){
                    $G25_C295 = 1;
                }else if($_POST["G25_C295"] == 'No'){
                    $G25_C295 = 0;
                }else{
                    $G25_C295 = $_POST["G25_C295"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C295 = ".$G25_C295."";
                $str_LsqlI .= $separador." G25_C295";
                $str_LsqlV .= $separador.$G25_C295;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C295 = ".$G25_C295."";
                $str_LsqlI .= $separador." G25_C295";
                $str_LsqlV .= $separador.$G25_C295;

                $validar = 1;
            }
  
            if(isset($_POST["G25_C296"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C296 = '".$_POST["G25_C296"]."'";
                $str_LsqlI .= $separador."G25_C296";
                $str_LsqlV .= $separador."'".$_POST["G25_C296"]."'";
                $validar = 1;
            }
             
  
            $G25_C297 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G25_C297"])){
                if($_POST["G25_C297"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G25_C297 = $_POST["G25_C297"];
                    $G25_C297 = str_replace(".", "", $_POST["G25_C297"]);
                    $G25_C297 =  str_replace(",", ".", $G25_C297);
                    $str_LsqlU .= $separador." G25_C297 = '".$G25_C297."'";
                    $str_LsqlI .= $separador." G25_C297";
                    $str_LsqlV .= $separador."'".$G25_C297."'";
                    $validar = 1;
                }
            }
  
            $G25_C298 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G25_C298"])){
                if($_POST["G25_C298"] == 'Yes'){
                    $G25_C298 = 1;
                }else if($_POST["G25_C298"] == 'off'){
                    $G25_C298 = 0;
                }else if($_POST["G25_C298"] == 'on'){
                    $G25_C298 = 1;
                }else if($_POST["G25_C298"] == 'No'){
                    $G25_C298 = 0;
                }else{
                    $G25_C298 = $_POST["G25_C298"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C298 = ".$G25_C298."";
                $str_LsqlI .= $separador." G25_C298";
                $str_LsqlV .= $separador.$G25_C298;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G25_C298 = ".$G25_C298."";
                $str_LsqlI .= $separador." G25_C298";
                $str_LsqlV .= $separador.$G25_C298;

                $validar = 1;
            }
  
            if(isset($_POST["G25_C299"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C299 = '".$_POST["G25_C299"]."'";
                $str_LsqlI .= $separador."G25_C299";
                $str_LsqlV .= $separador."'".$_POST["G25_C299"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G25_C300"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G25_C300 = '".$_POST["G25_C300"]."'";
                $str_LsqlI .= $separador."G25_C300";
                $str_LsqlV .= $separador."'".$_POST["G25_C300"]."'";
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
                    $str_Lsql = "SELECT GUIDET_ConsInte__PREGUN_De1_b FROM ".$BaseDatos_systema.".GUIDET WHERE GUIDET_ConsInte__GUION__Mae_b = ".$_POST['formpadre']." AND 
GUIDET_ConsInte__GUION__Det_b = ".$_POST['formhijo'];

                    $GuidRes = $mysqli->query($str_Lsql);
                    $campo = null;
                    while($ky = $GuidRes->fetch_object()){
                        $campo = $ky->GUIDET_ConsInte__PREGUN_De1_b;
                    }
                    $valorG = "G25_C";
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

                $str_LsqlU .= $separador."G25_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G25_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G25_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $str_Lsql = "DELETE FROM ".$BaseDatos.".G25 WHERE G25_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            //echo $str_Lsql;
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G25");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G25");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G25");
                    }
                    
                    echo $mysqli->insert_id;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }

        
    if(isset($_GET["insertarDatosSubgrilla_1"])){
        
        if(isset($_POST["oper"])){
            $str_Lsql  = '';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G4 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G4(";
            $str_LsqlV = " VALUES ("; 
 
                                                                         
            if(isset($_POST["G4_C21"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G4_C21 = '".$_POST["G4_C21"]."'";
                $str_LsqlI .= $separador."G4_C21";
                $str_LsqlV .= $separador."'".$_POST["G4_C21"]."'";
                $validar = 1;
            }
                                                                          
                                                                           
 
            $G4_C22= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C22"])){    
                if($_POST["G4_C22"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G4_C22 = $_POST["G4_C22"];
                    $str_LsqlU .= $separador." G4_C22 = '".$G4_C22."'";
                    $str_LsqlI .= $separador." G4_C22";
                    $str_LsqlV .= $separador."'".$G4_C22."'";
                    $validar = 1;
                }
            }
 
            $G4_C23= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C23"])){    
                if($_POST["G4_C23"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G4_C23 = $_POST["G4_C23"];
                    $str_LsqlU .= $separador." G4_C23 = '".$G4_C23."'";
                    $str_LsqlI .= $separador." G4_C23";
                    $str_LsqlV .= $separador."'".$G4_C23."'";
                    $validar = 1;
                }
            }
 
            $G4_C25= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C25"])){    
                if($_POST["G4_C25"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G4_C25 = $_POST["G4_C25"];
                    $str_LsqlU .= $separador." G4_C25 = '".$G4_C25."'";
                    $str_LsqlI .= $separador." G4_C25";
                    $str_LsqlV .= $separador."'".$G4_C25."'";
                    $validar = 1;
                }
            }
 
            $G4_C26= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C26"])){    
                if($_POST["G4_C26"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G4_C26 = $_POST["G4_C26"];
                    $str_LsqlU .= $separador." G4_C26 = '".$G4_C26."'";
                    $str_LsqlI .= $separador." G4_C26";
                    $str_LsqlV .= $separador."'".$G4_C26."'";
                    $validar = 1;
                }
            }
 
            $G4_C27= NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G4_C27"])){    
                if($_POST["G4_C27"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G4_C27 = $_POST["G4_C27"];
                    $str_LsqlU .= $separador." G4_C27 = '".$G4_C27."'";
                    $str_LsqlI .= $separador." G4_C27";
                    $str_LsqlV .= $separador."'".$G4_C27."'";
                    $validar = 1;
                }
            }

            if(isset($_POST["Padre"])){
                if($_POST["Padre"] != ''){
                    //esto es porque el padre es el entero
                    
                    $numero = $_POST["Padre"];
         

                    $G4_C24 = $numero;
                    $str_LsqlU .= ", G4_C24 = ".$G4_C24."";
                    $str_LsqlI .= ", G4_C24";
                    $str_LsqlV .= ",".$_POST["Padre"];
                }
            }  



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G4_ConsInte__b =".$_POST["providerUserId"]; 
                }else if($_POST['oper'] == 'del'){
                    $str_Lsql = "DELETE FROM  ".$BaseDatos.".G4 WHERE  G4_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            if($validar == 1){
                // echo $str_Lsql;
                if ($mysqli->query($str_Lsql) === TRUE) {
                    echo $mysqli->insert_id;
                    if($_POST["oper"] == 'add' ){
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G4");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G4");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G4");
                    }
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }  
            }  
        }
    }
                                        
            

        

    if(isset($_GET["callDatosSubgrilla_1"])){

        $id = $_GET['id'];  

        
        $numero = $id;
                

        $SQL = "SELECT G4_ConsInte__b, G4_C21, G4_C22, G4_C23, G4_C24, G4_C25, G4_C26, G4_C27 FROM ".$BaseDatos_systema.".G4  ";

        $SQL .= " WHERE G4_C24 = ".$numero; 

        $PEOBUS_VeRegPro__b = 0 ;
        $idUsuario = $_SESSION['IDENTIFICACION'];
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 4";
        $query = $mysqli->query($peobus);
        

        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $SQL .= " AND G4_Usuario = ".$idUsuario;
        }
    
        $SQL .= " ORDER BY G4_C21";

        // echo $SQL;
        if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) { 
            header("Content-type: application/xhtml+xml;charset=utf-8"); 
        } else { 
            header("Content-type: text/xml;charset=utf-8"); 
        } 

        $et = ">"; 
        echo "<?xml version='1.0' encoding='utf-8'?$et\n"; 
        echo "<rows>"; // be sure to put text data in CDATA
        $result = $mysqli->query($SQL);
        while( $fila = $result->fetch_object() ) {
            echo "<row asin='".$fila->G4_ConsInte__b."'>"; 
            echo "<cell>". ($fila->G4_ConsInte__b)."</cell>"; 
            

            echo "<cell>". ($fila->G4_C21)."</cell>";

            echo "<cell>". $fila->G4_C22."</cell>"; 

            echo "<cell>". $fila->G4_C23."</cell>"; 

            echo "<cell>". $fila->G4_C24."</cell>"; 

            echo "<cell>". $fila->G4_C25."</cell>"; 

            echo "<cell>". $fila->G4_C26."</cell>"; 

            echo "<cell>". $fila->G4_C27."</cell>"; 
            echo "</row>"; 
        } 
        echo "</rows>"; 
    }
    }

?>
