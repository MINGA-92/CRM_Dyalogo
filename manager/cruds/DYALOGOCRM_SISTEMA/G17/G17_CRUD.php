<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include(__DIR__."../../../../pages/conexion.php");

    function guardar_auditoria($accion, $superAccion){
        include(__DIR__."../../../../pages/conexion.php");
        $str_Lsql = "INSERT INTO ".$BaseDatos_systema."AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d H:s:i')."', '".date('Y-m-d H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'G17', '".$accion."', '".$superAccion."', ".$_SESSION['HUESPED']." );";
        $mysqli->query($str_Lsql);
    }   

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //Funciones de la carga maestro y eso
        

        //Datos del formulario
        if(isset($_POST['CallDatos'])){
          
            $str_Lsql = 'SELECT G17_ConsInte__b, G17_C153 as principal ,G17_C153,G17_C154,G17_C155,G17_C156,G17_C157,G17_C158,G17_C159,G17_C199,G17_C161,G17_C162,G17_C163,G17_C164,G17_C165,G17_C166,G17_C167,G17_C168,G17_C169,G17_C170,G17_C171,G17_C172,G17_C173,G17_C174,G17_C175,G17_C176,G17_C177,G17_C178,G17_C179,G17_C180,G17_C181,G17_C182,G17_C183,G17_C184,G17_C185,G17_C186,G17_C187,G17_C188,G17_C189,G17_C190,G17_C191,G17_C196,G17_C197,G17_C198 FROM '.$BaseDatos_systema.'.G17 WHERE G17_ConsInte__b ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G17_C153'] = $key->G17_C153;

                $datos[$i]['G17_C154'] = $key->G17_C154;

                $datos[$i]['G17_C155'] = $key->G17_C155;

                $datos[$i]['G17_C156'] = $key->G17_C156;

                $datos[$i]['G17_C157'] = $key->G17_C157;

                $datos[$i]['G17_C158'] = $key->G17_C158;

                $datos[$i]['G17_C159'] = $key->G17_C159;

                $datos[$i]['G17_C199'] = $key->G17_C199;

                $datos[$i]['G17_C161'] = $key->G17_C161;

                $datos[$i]['G17_C162'] = $key->G17_C162;
  
                if(!is_null($key->G17_C163)){
                    $datos[$i]['G17_C163'] = explode(' ', $key->G17_C163)[1];
                }
  
                if(!is_null($key->G17_C164)){
                    $datos[$i]['G17_C164'] = explode(' ', $key->G17_C164)[1];
                }

                $datos[$i]['G17_C165'] = $key->G17_C165;
  
                if(!is_null($key->G17_C166)){
                    $datos[$i]['G17_C166'] = explode(' ', $key->G17_C166)[1];
                }
  
                if(!is_null($key->G17_C167)){
                    $datos[$i]['G17_C167'] = explode(' ', $key->G17_C167)[1];
                }

                $datos[$i]['G17_C168'] = $key->G17_C168;
  
                if(!is_null($key->G17_C169)){
                    $datos[$i]['G17_C169'] = explode(' ', $key->G17_C169)[1];
                }
  
                if(!is_null($key->G17_C170)){
                    $datos[$i]['G17_C170'] = explode(' ', $key->G17_C170)[1];
                }

                $datos[$i]['G17_C171'] = $key->G17_C171;
  
                if(!is_null($key->G17_C172)){
                    $datos[$i]['G17_C172'] = explode(' ', $key->G17_C172)[1];
                }
  
                if(!is_null($key->G17_C173)){
                    $datos[$i]['G17_C173'] = explode(' ', $key->G17_C173)[1];
                }

                $datos[$i]['G17_C174'] = $key->G17_C174;
  
                if(!is_null($key->G17_C175)){
                    $datos[$i]['G17_C175'] = explode(' ', $key->G17_C175)[1];
                }
  
                if(!is_null($key->G17_C176)){
                    $datos[$i]['G17_C176'] = explode(' ', $key->G17_C176)[1];
                }

                $datos[$i]['G17_C177'] = $key->G17_C177;
  
                if(!is_null($key->G17_C178)){
                    $datos[$i]['G17_C178'] = explode(' ', $key->G17_C178)[1];
                }
  
                if(!is_null($key->G17_C179)){
                    $datos[$i]['G17_C179'] = explode(' ', $key->G17_C179)[1];
                }

                $datos[$i]['G17_C180'] = $key->G17_C180;
  
                if(!is_null($key->G17_C181)){
                    $datos[$i]['G17_C181'] = explode(' ', $key->G17_C181)[1];
                }
  
                if(!is_null($key->G17_C182)){
                    $datos[$i]['G17_C182'] = explode(' ', $key->G17_C182)[1];
                }

                $datos[$i]['G17_C183'] = $key->G17_C183;
  
                if(!is_null($key->G17_C184)){
                    $datos[$i]['G17_C184'] = explode(' ', $key->G17_C184)[1];
                }
  
                if(!is_null($key->G17_C185)){
                    $datos[$i]['G17_C185'] = explode(' ', $key->G17_C185)[1];
                }

                $datos[$i]['G17_C186'] = $key->G17_C186;

                $datos[$i]['G17_C187'] = $key->G17_C187;

                $datos[$i]['G17_C188'] = $key->G17_C188;

                $datos[$i]['G17_C189'] = $key->G17_C189;

                $datos[$i]['G17_C190'] = $key->G17_C190;

                $datos[$i]['G17_C191'] = $key->G17_C191;

                $datos[$i]['G17_C196'] = $key->G17_C196;

                $datos[$i]['G17_C197'] = $key->G17_C197;

                $datos[$i]['G17_C198'] = $key->G17_C198;
      
                $datos[$i]['principal'] = $key->principal;
                $i++;
            }
            echo json_encode($datos);
        }

        //Datos del formulario
        if(isset($_POST['CallDatos_2'])){
          
            $str_Lsql = 'SELECT G17_ConsInte__b, G17_C153 as principal ,G17_C153,G17_C154,G17_C155,G17_C156,G17_C157,G17_C158,G17_C159,G17_C199,G17_C161,G17_C162,G17_C163,G17_C164,G17_C165,G17_C166,G17_C167,G17_C168,G17_C169,G17_C170,G17_C171,G17_C172,G17_C173,G17_C174,G17_C175,G17_C176,G17_C177,G17_C178,G17_C179,G17_C180,G17_C181,G17_C182,G17_C183,G17_C184,G17_C185,G17_C186,G17_C187,G17_C188,G17_C189,G17_C190,G17_C191,G17_C196,G17_C197,G17_C198 FROM '.$BaseDatos_systema.'.G17 WHERE G17_C154 ='.$_POST['id'];
            $result = $mysqli->query($str_Lsql);
            $datos = array();
            $i = 0;

            while($key = $result->fetch_object()){

                $datos[$i]['G17_C153'] = $key->G17_C153;

                $datos[$i]['G17_C154'] = $key->G17_C154;

                $datos[$i]['G17_C155'] = $key->G17_C155;

                $datos[$i]['G17_C156'] = $key->G17_C156;

                $datos[$i]['G17_C157'] = $key->G17_C157;

                $datos[$i]['G17_C158'] = $key->G17_C158;

                $datos[$i]['G17_C159'] = $key->G17_C159;

                $datos[$i]['G17_C199'] = $key->G17_C199;

                $datos[$i]['G17_C161'] = $key->G17_C161;

                $datos[$i]['G17_C162'] = $key->G17_C162;
  
                if(!is_null($key->G17_C163)){
                    $datos[$i]['G17_C163'] = explode(' ', $key->G17_C163)[1];
                }
  
                if(!is_null($key->G17_C164)){
                    $datos[$i]['G17_C164'] = explode(' ', $key->G17_C164)[1];
                }

                $datos[$i]['G17_C165'] = $key->G17_C165;
  
                if(!is_null($key->G17_C166)){
                    $datos[$i]['G17_C166'] = explode(' ', $key->G17_C166)[1];
                }
  
                if(!is_null($key->G17_C167)){
                    $datos[$i]['G17_C167'] = explode(' ', $key->G17_C167)[1];
                }

                $datos[$i]['G17_C168'] = $key->G17_C168;
  
                if(!is_null($key->G17_C169)){
                    $datos[$i]['G17_C169'] = explode(' ', $key->G17_C169)[1];
                }
  
                if(!is_null($key->G17_C170)){
                    $datos[$i]['G17_C170'] = explode(' ', $key->G17_C170)[1];
                }

                $datos[$i]['G17_C171'] = $key->G17_C171;
  
                if(!is_null($key->G17_C172)){
                    $datos[$i]['G17_C172'] = explode(' ', $key->G17_C172)[1];
                }
  
                if(!is_null($key->G17_C173)){
                    $datos[$i]['G17_C173'] = explode(' ', $key->G17_C173)[1];
                }

                $datos[$i]['G17_C174'] = $key->G17_C174;
  
                if(!is_null($key->G17_C175)){
                    $datos[$i]['G17_C175'] = explode(' ', $key->G17_C175)[1];
                }
  
                if(!is_null($key->G17_C176)){
                    $datos[$i]['G17_C176'] = explode(' ', $key->G17_C176)[1];
                }

                $datos[$i]['G17_C177'] = $key->G17_C177;
  
                if(!is_null($key->G17_C178)){
                    $datos[$i]['G17_C178'] = explode(' ', $key->G17_C178)[1];
                }
  
                if(!is_null($key->G17_C179)){
                    $datos[$i]['G17_C179'] = explode(' ', $key->G17_C179)[1];
                }

                $datos[$i]['G17_C180'] = $key->G17_C180;
  
                if(!is_null($key->G17_C181)){
                    $datos[$i]['G17_C181'] = explode(' ', $key->G17_C181)[1];
                }
  
                if(!is_null($key->G17_C182)){
                    $datos[$i]['G17_C182'] = explode(' ', $key->G17_C182)[1];
                }

                $datos[$i]['G17_C183'] = $key->G17_C183;
  
                if(!is_null($key->G17_C184)){
                    $datos[$i]['G17_C184'] = explode(' ', $key->G17_C184)[1];
                }
  
                if(!is_null($key->G17_C185)){
                    $datos[$i]['G17_C185'] = explode(' ', $key->G17_C185)[1];
                }

                $datos[$i]['G17_C186'] = $key->G17_C186;

                $datos[$i]['G17_C187'] = $key->G17_C187;

                $datos[$i]['G17_C188'] = $key->G17_C188;

                $datos[$i]['G17_C189'] = $key->G17_C189;

                $datos[$i]['G17_C190'] = $key->G17_C190;

                $datos[$i]['G17_C191'] = $key->G17_C191;

                $datos[$i]['G17_C196'] = $key->G17_C196;

                $datos[$i]['G17_C197'] = $key->G17_C197;

                $datos[$i]['G17_C198'] = $key->G17_C198;
      
                $datos[$i]['principal'] = $key->principal;

                $datos[$i]['G17_ConsInte__b'] = $key->G17_ConsInte__b;

                $i++;
            }
            echo json_encode($datos);
        }
        //Datos de la lista de la izquierda
        if(isset($_POST['CallDatosJson'])){
            $str_Lsql = 'SELECT G17_ConsInte__b as id,  G17_C153 as camp1, G16_C147 as camp2 ';
            $str_Lsql .= ' FROM '.$BaseDatos_systema.'.G17   LEFT JOIN '.$BaseDatos_systema.'.G16 ON G16_ConsInte__b = G17_C155 ';
            if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                $str_Lsql .= ' WHERE  like "%'.$_POST['Busqueda'].'%" ';
                $str_Lsql .= ' OR  like "%'.$_POST['Busqueda'].'%" ';
            }

            $PEOBUS_VeRegPro__b = 0 ;
            $idUsuario = $_SESSION['IDENTIFICACION'];
            $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 17";
            $query = $mysqli->query($peobus);
            

            while ($key =  $query->fetch_object()) {
                $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
            }

            if($PEOBUS_VeRegPro__b != 0){
                if($_POST['Busqueda'] != '' && !is_null($_POST['Busqueda'])){
                    $str_Lsql .= ' AND G17_Usuario = '.$idUsuario;
                }else{
                    $str_Lsql .= ' WHERE G17_Usuario = '.$idUsuario;
                }
        
            }

            $str_Lsql .= ' ORDER BY G17_ConsInte__b DESC LIMIT 0, 50'; 
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


        //cargar la tabla de variables
        if(isset($_GET['cargarTablaVariables'])){
            echo '<thead>
                    <tr>
                        <th>';
                            
                                $Lsql = "SELECT GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = ".$_POST['poblacion'];
                                $cur_result = $mysqli->query($Lsql);
                                $res_Lsql = $cur_result->fetch_array();
            echo "VARIABLES ".$res_Lsql['GUION__Nombre____b'].'
                            
                        </th>
                    </tr>
                </thead>
                <tbody>';
                        
                $Lsql = "SELECT PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN JOIN ".$BaseDatos_systema.".SECCIO ON SECCIO_ConsInte__b = PREGUN_ConsInte__SECCIO_b WHERE SECCIO_TipoSecc__b = 1 AND PREGUN_ConsInte__GUION__b = ".$_POST['poblacion'];
                $cur_result = $mysqli->query($Lsql);
                while ($key = $cur_result->fetch_object()) {
                    $dato = str_replace(' ', '_', utf8_encode($key->PREGUN_Texto_____b));

                    echo '<tr>';
                    echo '  <td>';
                    echo "\${".substr($dato, 0, 20)."}";
                    echo '  </td>';
                    echo '<tr>';
                }
                        
            echo '</tbody>';
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

        

        if(isset($_GET['CallDatosCombo_Guion_G17_C154'])){
            $Ysql = 'SELECT   G3_ConsInte__b as id , G3_C15 FROM ".$BaseDatos_systema.".G3';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G17_C154" id="G17_C154">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G3_C15)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G17_C155'])){
            $Ysql = 'SELECT   G16_ConsInte__b as id , G16_C147 FROM ".$BaseDatos_systema.".G16';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G17_C155" id="G17_C155">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G16_C147)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G17_C161'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G17_C161" id="G17_C161">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G17_C196'])){
            $Ysql = 'SELECT   G5_ConsInte__b as id , G5_C28 FROM ".$BaseDatos_systema.".G5';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G17_C196" id="G17_C196">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G5_C28)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G17_C198'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM ".$BaseDatos_systema.".G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G17_C198" id="G17_C198">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G18_C192'])){
            $Ysql = 'SELECT   G17_ConsInte__b as id , G17_C153 FROM '.$BaseDatos_systema.'G17';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G18_C192" id="G18_C192">';
            echo '<option >NOMBRE</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G17_C153)."</option>";
            } 
            echo '</select>';
        }

        if(isset($_GET['CallDatosCombo_Guion_G18_C193'])){
            $Ysql = 'SELECT   G6_ConsInte__b as id , G6_C39 FROM '.$BaseDatos_systema.'G6';
            $guion = $mysqli->query($Ysql);
            echo '<select class="form-control input-sm"  name="G18_C193" id="G18_C193">';
            echo '<option >TEXTO</option>';
            while($obj = $guion->fetch_object()){
               echo "<option value='".$obj->id."' dinammicos='0'>".utf8_encode($obj->G6_C39)."</option>";
            } 
            echo '</select>';
        }





        if(isset($_POST['CallEliminate'])){
            if($_POST['oper'] == 'del'){
                $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G17 WHERE G17_ConsInte__b = ".$_POST['id'];
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
            $Zsql = 'SELECT  G17_ConsInte__b as id,  G17_C153 as camp1, G16_C147 as camp2  FROM '.$BaseDatos_systema.'.$BaseDatos_systema.".G17   LEFT JOIN '.$BaseDatos_systema.'.G16 ON G16_ConsInte__b = G17_C155 ORDER BY G17_ConsInte__b DESC LIMIT '.$inicio.' , '.$fin;
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
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G17 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G17(";
            $str_LsqlV = " VALUES ("; 
  
            if(isset($_POST["G17_C153"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C153 = '".$_POST["G17_C153"]."'";
                $str_LsqlI .= $separador."G17_C153";
                $str_LsqlV .= $separador."'".$_POST["G17_C153"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C154"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C154 = '".$_POST["G17_C154"]."'";
                $str_LsqlI .= $separador."G17_C154";
                $str_LsqlV .= $separador."'".$_POST["G17_C154"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C155"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C155 = '".$_POST["G17_C155"]."'";
                $str_LsqlI .= $separador."G17_C155";
                $str_LsqlV .= $separador."'".$_POST["G17_C155"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C156"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C156 = '".$_POST["G17_C156"]."'";
                $str_LsqlI .= $separador."G17_C156";
                $str_LsqlV .= $separador."'".$_POST["G17_C156"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C157"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C157 = '".$_POST["G17_C157"]."'";
                $str_LsqlI .= $separador."G17_C157";
                $str_LsqlV .= $separador."'".$_POST["G17_C157"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C158"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C158 = '".$_POST["G17_C158"]."'";
                $str_LsqlI .= $separador."G17_C158";
                $str_LsqlV .= $separador."'".$_POST["G17_C158"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C159"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C159 = '".$_POST["G17_C159"]."'";
                $str_LsqlI .= $separador."G17_C159";
                $str_LsqlV .= $separador."'".$_POST["G17_C159"]."'";
                $validar = 1;
            }
             
  
            $G17_C199 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G17_C199"])){
                if($_POST["G17_C199"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G17_C199 = $_POST["G17_C199"];
                    $G17_C199 = str_replace(".", "", $_POST["G17_C199"]);
                    $G17_C199 =  str_replace(",", ".", $G17_C199);
                    $str_LsqlU .= $separador." G17_C199 = '".$G17_C199."'";
                    $str_LsqlI .= $separador." G17_C199";
                    $str_LsqlV .= $separador."'".$G17_C199."'";
                    $validar = 1;
                }
            }
  
            if(isset($_POST["G17_C161"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C161 = '".$_POST["G17_C161"]."'";
                $str_LsqlI .= $separador."G17_C161";
                $str_LsqlV .= $separador."'".$_POST["G17_C161"]."'";
                $validar = 1;
            }
             
  
            $G17_C162 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C162"])){
                if($_POST["G17_C162"] == 'Yes'){
                    $G17_C162 = 1;
                }else if($_POST["G17_C162"] == 'off'){
                    $G17_C162 = 0;
                }else if($_POST["G17_C162"] == 'on'){
                    $G17_C162 = 1;
                }else if($_POST["G17_C162"] == 'No'){
                    $G17_C162 = 0;
                }else{
                    $G17_C162 = $_POST["G17_C162"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C162 = ".$G17_C162."";
                $str_LsqlI .= $separador." G17_C162";
                $str_LsqlV .= $separador.$G17_C162;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C162 = ".$G17_C162."";
                $str_LsqlI .= $separador." G17_C162";
                $str_LsqlV .= $separador.$G17_C162;

                $validar = 1;
            }
  
            $G17_C163 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C163"])){   
                if($_POST["G17_C163"] != '' && $_POST["G17_C163"] != 'undefined' && $_POST["G17_C163"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C163 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C163"])."'";
                    $str_LsqlU .= $separador." G17_C163 = ".$G17_C163."";
                    $str_LsqlI .= $separador." G17_C163";
                    $str_LsqlV .= $separador.$G17_C163;
                    $validar = 1;
                }
            }
  
            $G17_C164 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C164"])){   
                if($_POST["G17_C164"] != '' && $_POST["G17_C164"] != 'undefined' && $_POST["G17_C164"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C164 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C164"])."'";
                    $str_LsqlU .= $separador." G17_C164 = ".$G17_C164."";
                    $str_LsqlI .= $separador." G17_C164";
                    $str_LsqlV .= $separador.$G17_C164;
                    $validar = 1;
                }
            }
  
            $G17_C165 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C165"])){
                if($_POST["G17_C165"] == 'Yes'){
                    $G17_C165 = 1;
                }else if($_POST["G17_C165"] == 'off'){
                    $G17_C165 = 0;
                }else if($_POST["G17_C165"] == 'on'){
                    $G17_C165 = 1;
                }else if($_POST["G17_C165"] == 'No'){
                    $G17_C165 = 0;
                }else{
                    $G17_C165 = $_POST["G17_C165"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C165 = ".$G17_C165."";
                $str_LsqlI .= $separador." G17_C165";
                $str_LsqlV .= $separador.$G17_C165;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C165 = ".$G17_C165."";
                $str_LsqlI .= $separador." G17_C165";
                $str_LsqlV .= $separador.$G17_C165;

                $validar = 1;
            }
  
            $G17_C166 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C166"])){   
                if($_POST["G17_C166"] != '' && $_POST["G17_C166"] != 'undefined' && $_POST["G17_C166"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C166 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C166"])."'";
                    $str_LsqlU .= $separador." G17_C166 = ".$G17_C166."";
                    $str_LsqlI .= $separador." G17_C166";
                    $str_LsqlV .= $separador.$G17_C166;
                    $validar = 1;
                }
            }
  
            $G17_C167 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C167"])){   
                if($_POST["G17_C167"] != '' && $_POST["G17_C167"] != 'undefined' && $_POST["G17_C167"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C167 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C167"])."'";
                    $str_LsqlU .= $separador." G17_C167 = ".$G17_C167."";
                    $str_LsqlI .= $separador." G17_C167";
                    $str_LsqlV .= $separador.$G17_C167;
                    $validar = 1;
                }
            }
  
            $G17_C168 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C168"])){
                if($_POST["G17_C168"] == 'Yes'){
                    $G17_C168 = 1;
                }else if($_POST["G17_C168"] == 'off'){
                    $G17_C168 = 0;
                }else if($_POST["G17_C168"] == 'on'){
                    $G17_C168 = 1;
                }else if($_POST["G17_C168"] == 'No'){
                    $G17_C168 = 0;
                }else{
                    $G17_C168 = $_POST["G17_C168"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C168 = ".$G17_C168."";
                $str_LsqlI .= $separador." G17_C168";
                $str_LsqlV .= $separador.$G17_C168;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C168 = ".$G17_C168."";
                $str_LsqlI .= $separador." G17_C168";
                $str_LsqlV .= $separador.$G17_C168;

                $validar = 1;
            }
  
            $G17_C169 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C169"])){   
                if($_POST["G17_C169"] != '' && $_POST["G17_C169"] != 'undefined' && $_POST["G17_C169"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C169 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C169"])."'";
                    $str_LsqlU .= $separador." G17_C169 = ".$G17_C169."";
                    $str_LsqlI .= $separador." G17_C169";
                    $str_LsqlV .= $separador.$G17_C169;
                    $validar = 1;
                }
            }
  
            $G17_C170 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C170"])){   
                if($_POST["G17_C170"] != '' && $_POST["G17_C170"] != 'undefined' && $_POST["G17_C170"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C170 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C170"])."'";
                    $str_LsqlU .= $separador." G17_C170 = ".$G17_C170."";
                    $str_LsqlI .= $separador." G17_C170";
                    $str_LsqlV .= $separador.$G17_C170;
                    $validar = 1;
                }
            }
  
            $G17_C171 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C171"])){
                if($_POST["G17_C171"] == 'Yes'){
                    $G17_C171 = 1;
                }else if($_POST["G17_C171"] == 'off'){
                    $G17_C171 = 0;
                }else if($_POST["G17_C171"] == 'on'){
                    $G17_C171 = 1;
                }else if($_POST["G17_C171"] == 'No'){
                    $G17_C171 = 0;
                }else{
                    $G17_C171 = $_POST["G17_C171"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C171 = ".$G17_C171."";
                $str_LsqlI .= $separador." G17_C171";
                $str_LsqlV .= $separador.$G17_C171;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C171 = ".$G17_C171."";
                $str_LsqlI .= $separador." G17_C171";
                $str_LsqlV .= $separador.$G17_C171;

                $validar = 1;
            }
  
            $G17_C172 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C172"])){   
                if($_POST["G17_C172"] != '' && $_POST["G17_C172"] != 'undefined' && $_POST["G17_C172"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C172 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C172"])."'";
                    $str_LsqlU .= $separador." G17_C172 = ".$G17_C172."";
                    $str_LsqlI .= $separador." G17_C172";
                    $str_LsqlV .= $separador.$G17_C172;
                    $validar = 1;
                }
            }
  
            $G17_C173 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C173"])){   
                if($_POST["G17_C173"] != '' && $_POST["G17_C173"] != 'undefined' && $_POST["G17_C173"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C173 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C173"])."'";
                    $str_LsqlU .= $separador." G17_C173 = ".$G17_C173."";
                    $str_LsqlI .= $separador." G17_C173";
                    $str_LsqlV .= $separador.$G17_C173;
                    $validar = 1;
                }
            }
  
            $G17_C174 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C174"])){
                if($_POST["G17_C174"] == 'Yes'){
                    $G17_C174 = 1;
                }else if($_POST["G17_C174"] == 'off'){
                    $G17_C174 = 0;
                }else if($_POST["G17_C174"] == 'on'){
                    $G17_C174 = 1;
                }else if($_POST["G17_C174"] == 'No'){
                    $G17_C174 = 0;
                }else{
                    $G17_C174 = $_POST["G17_C174"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C174 = ".$G17_C174."";
                $str_LsqlI .= $separador." G17_C174";
                $str_LsqlV .= $separador.$G17_C174;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C174 = ".$G17_C174."";
                $str_LsqlI .= $separador." G17_C174";
                $str_LsqlV .= $separador.$G17_C174;

                $validar = 1;
            }
  
            $G17_C175 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C175"])){   
                if($_POST["G17_C175"] != '' && $_POST["G17_C175"] != 'undefined' && $_POST["G17_C175"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C175 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C175"])."'";
                    $str_LsqlU .= $separador." G17_C175 = ".$G17_C175."";
                    $str_LsqlI .= $separador." G17_C175";
                    $str_LsqlV .= $separador.$G17_C175;
                    $validar = 1;
                }
            }
  
            $G17_C176 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C176"])){   
                if($_POST["G17_C176"] != '' && $_POST["G17_C176"] != 'undefined' && $_POST["G17_C176"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C176 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C176"])."'";
                    $str_LsqlU .= $separador." G17_C176 = ".$G17_C176."";
                    $str_LsqlI .= $separador." G17_C176";
                    $str_LsqlV .= $separador.$G17_C176;
                    $validar = 1;
                }
            }
  
            $G17_C177 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C177"])){
                if($_POST["G17_C177"] == 'Yes'){
                    $G17_C177 = 1;
                }else if($_POST["G17_C177"] == 'off'){
                    $G17_C177 = 0;
                }else if($_POST["G17_C177"] == 'on'){
                    $G17_C177 = 1;
                }else if($_POST["G17_C177"] == 'No'){
                    $G17_C177 = 0;
                }else{
                    $G17_C177 = $_POST["G17_C177"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C177 = ".$G17_C177."";
                $str_LsqlI .= $separador." G17_C177";
                $str_LsqlV .= $separador.$G17_C177;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C177 = ".$G17_C177."";
                $str_LsqlI .= $separador." G17_C177";
                $str_LsqlV .= $separador.$G17_C177;

                $validar = 1;
            }
  
            $G17_C178 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C178"])){   
                if($_POST["G17_C178"] != '' && $_POST["G17_C178"] != 'undefined' && $_POST["G17_C178"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C178 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C178"])."'";
                    $str_LsqlU .= $separador." G17_C178 = ".$G17_C178."";
                    $str_LsqlI .= $separador." G17_C178";
                    $str_LsqlV .= $separador.$G17_C178;
                    $validar = 1;
                }
            }
  
            $G17_C179 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C179"])){   
                if($_POST["G17_C179"] != '' && $_POST["G17_C179"] != 'undefined' && $_POST["G17_C179"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C179 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C179"])."'";
                    $str_LsqlU .= $separador." G17_C179 = ".$G17_C179."";
                    $str_LsqlI .= $separador." G17_C179";
                    $str_LsqlV .= $separador.$G17_C179;
                    $validar = 1;
                }
            }
  
            $G17_C180 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C180"])){
                if($_POST["G17_C180"] == 'Yes'){
                    $G17_C180 = 1;
                }else if($_POST["G17_C180"] == 'off'){
                    $G17_C180 = 0;
                }else if($_POST["G17_C180"] == 'on'){
                    $G17_C180 = 1;
                }else if($_POST["G17_C180"] == 'No'){
                    $G17_C180 = 0;
                }else{
                    $G17_C180 = $_POST["G17_C180"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C180 = ".$G17_C180."";
                $str_LsqlI .= $separador." G17_C180";
                $str_LsqlV .= $separador.$G17_C180;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C180 = ".$G17_C180."";
                $str_LsqlI .= $separador." G17_C180";
                $str_LsqlV .= $separador.$G17_C180;

                $validar = 1;
            }
  
            $G17_C181 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C181"])){   
                if($_POST["G17_C181"] != '' && $_POST["G17_C181"] != 'undefined' && $_POST["G17_C181"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C181 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C181"])."'";
                    $str_LsqlU .= $separador." G17_C181 = ".$G17_C181."";
                    $str_LsqlI .= $separador." G17_C181";
                    $str_LsqlV .= $separador.$G17_C181;
                    $validar = 1;
                }
            }
  
            $G17_C182 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C182"])){   
                if($_POST["G17_C182"] != '' && $_POST["G17_C182"] != 'undefined' && $_POST["G17_C182"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C182 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C182"])."'";
                    $str_LsqlU .= $separador." G17_C182 = ".$G17_C182."";
                    $str_LsqlI .= $separador." G17_C182";
                    $str_LsqlV .= $separador.$G17_C182;
                    $validar = 1;
                }
            }
  
            $G17_C183 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C183"])){
                if($_POST["G17_C183"] == 'Yes'){
                    $G17_C183 = 1;
                }else if($_POST["G17_C183"] == 'off'){
                    $G17_C183 = 0;
                }else if($_POST["G17_C183"] == 'on'){
                    $G17_C183 = 1;
                }else if($_POST["G17_C183"] == 'No'){
                    $G17_C183 = 0;
                }else{
                    $G17_C183 = $_POST["G17_C183"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C183 = ".$G17_C183."";
                $str_LsqlI .= $separador." G17_C183";
                $str_LsqlV .= $separador.$G17_C183;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C183 = ".$G17_C183."";
                $str_LsqlI .= $separador." G17_C183";
                $str_LsqlV .= $separador.$G17_C183;

                $validar = 1;
            }
  
            $G17_C184 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C184"])){   
                if($_POST["G17_C184"] != '' && $_POST["G17_C184"] != 'undefined' && $_POST["G17_C184"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C184 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C184"])."'";
                    $str_LsqlU .= $separador." G17_C184 = ".$G17_C184."";
                    $str_LsqlI .= $separador." G17_C184";
                    $str_LsqlV .= $separador.$G17_C184;
                    $validar = 1;
                }
            }
  
            $G17_C185 = NULL;
            //este es de tipo date hay que preguntar si esta vacia o no o esta undefined
            if(isset($_POST["G17_C185"])){   
                if($_POST["G17_C185"] != '' && $_POST["G17_C185"] != 'undefined' && $_POST["G17_C185"] != 'null'){
                    $separador = "";
                    $fecha = date('Y-m-d');
                    if($validar == 1){
                        $separador = ",";
                    }

                    $G17_C185 = "'".$fecha." ".str_replace(' ', '',$_POST["G17_C185"])."'";
                    $str_LsqlU .= $separador." G17_C185 = ".$G17_C185."";
                    $str_LsqlI .= $separador." G17_C185";
                    $str_LsqlV .= $separador.$G17_C185;
                    $validar = 1;
                }
            }
  
            $G17_C186 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G17_C186"])){
                if($_POST["G17_C186"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G17_C186 = $_POST["G17_C186"];
                    $G17_C186 = str_replace(".", "", $_POST["G17_C186"]);
                    $G17_C186 =  str_replace(",", ".", $G17_C186);
                    $str_LsqlU .= $separador." G17_C186 = '".$G17_C186."'";
                    $str_LsqlI .= $separador." G17_C186";
                    $str_LsqlV .= $separador."'".$G17_C186."'";
                    $validar = 1;
                }
            }
  
            $G17_C187 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G17_C187"])){
                if($_POST["G17_C187"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G17_C187 = $_POST["G17_C187"];
                    $G17_C187 = str_replace(".", "", $_POST["G17_C187"]);
                    $G17_C187 =  str_replace(",", ".", $G17_C187);
                    $str_LsqlU .= $separador." G17_C187 = '".$G17_C187."'";
                    $str_LsqlI .= $separador." G17_C187";
                    $str_LsqlV .= $separador."'".$G17_C187."'";
                    $validar = 1;
                }
            }
  
            $G17_C188 = NULL;
            //este es de tipo numero no se deja ir asi '', si est avacio lo mejor es no mandarlo
            if(isset($_POST["G17_C188"])){
                if($_POST["G17_C188"] != ''){
                    $separador = "";
                    if($validar == 1){
                        $separador = ",";
                    }

                    //$G17_C188 = $_POST["G17_C188"];
                    $G17_C188 = str_replace(".", "", $_POST["G17_C188"]);
                    $G17_C188 =  str_replace(",", ".", $G17_C188);
                    $str_LsqlU .= $separador." G17_C188 = '".$G17_C188."'";
                    $str_LsqlI .= $separador." G17_C188";
                    $str_LsqlV .= $separador."'".$G17_C188."'";
                    $validar = 1;
                }
            }
  
            $G17_C189 = 0;
            //este es tipo check primeo si viene y de acuerdo a su valor se pone 1 o 0
            if(isset($_POST["G17_C189"])){
                if($_POST["G17_C189"] == 'Yes'){
                    $G17_C189 = 1;
                }else if($_POST["G17_C189"] == 'off'){
                    $G17_C189 = 0;
                }else if($_POST["G17_C189"] == 'on'){
                    $G17_C189 = 1;
                }else if($_POST["G17_C189"] == 'No'){
                    $G17_C189 = 0;
                }else{
                    $G17_C189 = $_POST["G17_C189"] ;
                }   

                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C189 = ".$G17_C189."";
                $str_LsqlI .= $separador." G17_C189";
                $str_LsqlV .= $separador.$G17_C189;

                $validar = 1;
            }else{
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador." G17_C189 = ".$G17_C189."";
                $str_LsqlI .= $separador." G17_C189";
                $str_LsqlV .= $separador.$G17_C189;

                $validar = 1;
            }
  
            if(isset($_POST["G17_C190"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C190 = '".$_POST["G17_C190"]."'";
                $str_LsqlI .= $separador."G17_C190";
                $str_LsqlV .= $separador."'".$_POST["G17_C190"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C191"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C191 = '".$_POST["G17_C191"]."'";
                $str_LsqlI .= $separador."G17_C191";
                $str_LsqlV .= $separador."'".$_POST["G17_C191"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C196"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C196 = '".$_POST["G17_C196"]."'";
                $str_LsqlI .= $separador."G17_C196";
                $str_LsqlV .= $separador."'".$_POST["G17_C196"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C197"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C197 = '".$_POST["G17_C197"]."'";
                $str_LsqlI .= $separador."G17_C197";
                $str_LsqlV .= $separador."'".$_POST["G17_C197"]."'";
                $validar = 1;
            }
             
  
            if(isset($_POST["G17_C198"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G17_C198 = '".$_POST["G17_C198"]."'";
                $str_LsqlI .= $separador."G17_C198";
                $str_LsqlV .= $separador."'".$_POST["G17_C198"]."'";
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
                    $valorG = "G17_C";
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

                $str_LsqlU .= $separador."G17_IdLlamada = '".$_GET['id_gestion_cbx']."'";
                $str_LsqlI .= $separador."G17_IdLlamada";
                $str_LsqlV .= $separador."'".$_GET['id_gestion_cbx']."'";
                $validar = 1;
            }



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G17_ConsInte__b =".$_POST["id"]; 
                }else if($_POST["oper"] == 'del' ){
                    $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".G17 WHERE G17_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            //si trae algo que insertar inserta

            //echo $str_Lsql;
            if($validar == 1){
                if ($mysqli->query($str_Lsql) === TRUE) {
                    if($_POST["oper"] == 'add' ){
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G17");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G17");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G17");
                    }
                    
                    echo $mysqli->insert_id;
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }
            }
        }

        
    if(isset($_GET["insertarDatosSubgrilla_0"])){
        
        if(isset($_POST["oper"])){
            $str_Lsql  = '';

            $validar = 0;
            $str_LsqlU = "UPDATE ".$BaseDatos_systema.".G18 SET "; 
            $str_LsqlI = "INSERT INTO ".$BaseDatos_systema.".G18(";
            $str_LsqlV = " VALUES ("; 
  
            if(isset($_POST["G18_C193"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G18_C193 = '".$_POST["G18_C193"]."'";
                $str_LsqlI .= $separador."G18_C193";
                $str_LsqlV .= $separador."'".$_POST["G18_C193"]."'";
                $validar = 1;
            }
            
 
                                                                         
            if(isset($_POST["G18_C194"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G18_C194 = '".$_POST["G18_C194"]."'";
                $str_LsqlI .= $separador."G18_C194";
                $str_LsqlV .= $separador."'".$_POST["G18_C194"]."'";
                $validar = 1;
            }
                                                                          
                                                                           
 
                                                                         
            if(isset($_POST["G18_C195"])){
                $separador = "";
                if($validar == 1){
                    $separador = ",";
                }

                $str_LsqlU .= $separador."G18_C195 = '".$_POST["G18_C195"]."'";
                $str_LsqlI .= $separador."G18_C195";
                $str_LsqlV .= $separador."'".$_POST["G18_C195"]."'";
                $validar = 1;
            }
                                                                          
                                                                           

            if(isset($_POST["Padre"])){
                if($_POST["Padre"] != ''){
                    //esto es porque el padre es el entero
                    
                    $numero = $_POST["Padre"];
         

                    $G18_C192 = $numero;
                    $str_LsqlU .= ", G18_C192 = ".$G18_C192."";
                    $str_LsqlI .= ", G18_C192";
                    $str_LsqlV .= ",".$_POST["Padre"];
                }
            }  



            if(isset($_POST['oper'])){
                if($_POST["oper"] == 'add' ){
                    $str_Lsql = $str_LsqlI.")" . $str_LsqlV.")";
                }else if($_POST["oper"] == 'edit' ){
                    $str_Lsql = $str_LsqlU." WHERE G18_ConsInte__b =".$_POST["providerUserId"]; 
                }else if($_POST['oper'] == 'del'){
                    $str_Lsql = "DELETE FROM  ".$BaseDatos_systema.".G18 WHERE  G18_ConsInte__b = ".$_POST['id'];
                    $validar = 1;
                }
            }

            if($validar == 1){
                // echo $str_Lsql;
                if ($mysqli->query($str_Lsql) === TRUE) {
                    echo $mysqli->insert_id;
                    if($_POST["oper"] == 'add' ){
                       guardar_auditoria("INSERTAR", "INSERTAR REGISTRO EN G18");
                    }else if($_POST["oper"] == 'edit' ){
                       guardar_auditoria("ACTUALIZAR", "ACTUALIZO EL REGISTRO # ".$_POST["padre"]." EN G18");
                    }else if($_POST["oper"] == 'del' ){
                       guardar_auditoria("ELIMINAR", "ELIMINO EL REGISTRO # ".$_POST['id']." EN G18");
                    }
                } else {
                    echo "Error Hacieno el proceso los registros : " . $mysqli->error;
                }  
            }  
        }
    }
                                        
            

        

    if(isset($_GET["callDatosSubgrilla_0"])){

        $id = $_GET['id'];  

        
        $numero = $id;
                

        $SQL = "SELECT G18_ConsInte__b, G17_C153 as G18_C192, G6_C39 as G18_C193, G18_C194, G18_C195 FROM ".$BaseDatos_systema.".G18  LEFT JOIN '.$BaseDatos_systema.'.G17 ON G17_ConsInte__b  =  G18_C192 LEFT JOIN '.$BaseDatos_systema.'.G6 ON G6_ConsInte__b  =  G18_C193 ";

        $SQL .= " WHERE G18_C192 = ".$numero; 

        $PEOBUS_VeRegPro__b = 0 ;
        $idUsuario = $_SESSION['IDENTIFICACION'];
        $peobus = "SELECT * FROM ".$BaseDatos_systema.".PEOBUS WHERE PEOBUS_ConsInte__USUARI_b = ".$idUsuario." AND PEOBUS_ConsInte__GUION__b = 18";
        $query = $mysqli->query($peobus);
        

        while ($key =  $query->fetch_object()) {
            $PEOBUS_VeRegPro__b = $key->PEOBUS_VeRegPro__b ;
        }

        if($PEOBUS_VeRegPro__b != 0){
            $SQL .= " AND G18_Usuario = ".$idUsuario;
        }
    
        $SQL .= " ORDER BY G18_C192";

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
            echo "<row asin='".$fila->G18_ConsInte__b."'>"; 
            echo "<cell>". ($fila->G18_ConsInte__b)."</cell>"; 
            

            echo "<cell>". ($fila->G18_C192)."</cell>";

            echo "<cell>". ($fila->G18_C193)."</cell>";

            echo "<cell>". ($fila->G18_C194)."</cell>";

            echo "<cell>". ($fila->G18_C195)."</cell>";
            echo "</row>"; 
        } 
        echo "</rows>"; 
    }
    }

?>
