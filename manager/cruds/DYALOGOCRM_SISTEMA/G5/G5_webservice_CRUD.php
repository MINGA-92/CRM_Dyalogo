<?php
session_start();
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
require_once('../../../../helpers/parameters.php');
require_once('../../../idioma.php');
require_once('../../../pages/conexion.php');
require_once('../../../utils.php');

if(isset($_GET['saveData'])){
    $contador=$_POST['contador'];
    $ws=$_POST['webservice'];
    $i=0;
    while($i<$contador){
        if(isset($_POST["oper_{$i}"])){
            $oper=$_POST["oper_{$i}"];
            $attr=$_POST["attr_{$i}"];
            $valor=$_POST["valor_{$i}"];
            $guion=$_POST["guion"];
            $llave=$_POST["campollave"];
            $estpas=$_POST["estpas"];
            $pregun=isset($_POST["select_{$i}"]) ? $_POST["select_{$i}"] : 'NULL';
            $fijo=isset($_POST["valorFijo_{$i}"]) ? $_POST["valorFijo_{$i}"] : NULL;
            $valido=true;

            if($estpas != 0){
                $llave=$estpas;
            }

            if($oper=='add'){
                
                if($pregun == '0'){
                    $valido=false;
                }
                if($valor == '2' && $fijo == ''){
                    $valido=false; 
                }

                if($valido){
                    $sql=$mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.CAMCONWS VALUES(NULL,{$guion},{$llave},{$attr},{$valor},{$pregun},'{$fijo}',{$estpas})");
                }
            }else{
                $sql=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.CAMCONWS SET CAMCONWS_TipValor_b={$valor}, CAMCONWS_ConsInte__PREGUN__b={$pregun}, CAMCONWS_Valor_b='{$fijo}' WHERE CAMCONWS_ConsInte__b={$oper}");
            }
        }
        $i++;
    }

    header("Location:G5_webservice.php?ws={$ws}&guion={$guion}&llave={$llave}");
}

if(isset($_GET['getData'])){
    $llave=$_POST["llave"];
    $estpas=$_POST["estpas"];
    $conEstpas='';
    if($estpas != 0){
        $conEstpas="AND CAMCONWS_ConsInte_ESTPAS__b={$estpas}";
    }
    $sql=$mysqli->query("SELECT * FROM DYALOGOCRM_SISTEMA.CAMCONWS WHERE CAMCONWS_ConsInte__PREGUN__llave_b={$llave} {$conEstpas}");
    $data=array();
    while($fila = $sql->fetch_object()){
        $data[]=$fila;
    }
    echo json_encode($data);
}

if(isset($_GET["saveListas"])){
    $ws=$_POST["webservice"];
    $llave=$_POST["campollave"];
    $pregunID=$_POST["pregunID"];
    $tabla=isset($_POST["camposG"]) ? $_POST["camposG"] : NULL;
    $estpas=$_POST['estpas'];
    if($estpas != 0){
        $llave=$estpas;
    }else{
        $estpas=NULL;
    }
    if(is_null($tabla)){
        $fila=isset($_POST["iterListas"]) ? $_POST["iterListas"] : false;
        if($fila){
            $i=0;
            while($i <$fila){
                if(isset($_POST["oper_{$i}"])){
                    $lisopc=isset($_POST["hidIdOpcion_{$i}"]) ? $_POST["hidIdOpcion_{$i}"] : 'NULL';
                    $opcWS=$_POST["opcionesWS_{$i}"];
                    $oper=$_POST["oper_{$i}"];
                    if($opcWS !=""){
                        if($oper=='add'){
                            $sql=$mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.LISOPCWS VALUES(NULL,{$lisopc},NULL,'{$opcWS}',{$llave},{$ws},{$pregunID})");
                        }else{
                            $sql=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.LISOPCWS SET LISOPCWS_OpcWS_b='{$opcWS}',LISOPCWS_ConsInte__PREGUN__b={$pregunID} WHERE LISOPCWS_ConsInte__b={$oper}");
                        }
                    }
                }
                $i++;
            }
            echo json_encode(array("mensaje"=>"ok"));
        }else{
            echo json_encode(array("mensaje"=>"Hubo un error al procesar la peticion"));
        }
    }else{
        $oper=isset($_POST["operG"]) ? $_POST["operG"] : false;
        if($oper){
            if($oper=='add'){
                $sql=$mysqli->query("INSERT INTO DYALOGOCRM_SISTEMA.LISOPCWS VALUES(NULL,NULL,{$tabla},NULL,{$llave},{$ws},{$pregunID})");
            }else{
                $sql=$mysqli->query("UPDATE DYALOGOCRM_SISTEMA.LISOPCWS SET LISOPCWS_ConsInte__TablaG__b={$tabla},LISOPCWS_ConsInte__PREGUN__b={$pregunID} WHERE LISOPCWS_ConsInte__b={$oper}");
            }
        }
        echo json_encode(array("mensaje"=>"ok"));
    }
}

if(isset($_GET["getListas"])){  
    $llave=isset($_POST["llave"]) ? $_POST["llave"] : false;
    if($llave){
        $sql=$mysqli->query("SELECT LISOPCWS_ConsInte__b AS id, LISOPCWS_ConsInte__LISOPC__b AS lisopc, LISOPCWS_ConsInte__TablaG__b AS tablaG, LISOPCWS_OpcWS_b AS opcWS FROM DYALOGOCRM_SISTEMA.LISOPCWS WHERE LISOPCWS_ConsInte__PREGUN__llave_b={$llave}");
        if($sql){
            if($sql->num_rows > 0){
                $data=array();
                while($fila = $sql->fetch_object()){
                    $data[]=$fila;
                }
                echo json_encode(array('mensaje'=>'ok', 'data'=>$data));
            }else{
                echo json_encode(array('mensaje'=>'No se han configurado las relaciones de las listas'));
            }
        }else{
            echo json_encode(array('mensaje'=>'No se pudo obtener las relaciones de las listas'));
        }
    }else{
        echo json_encode(array('mensaje'=>'No se pudo obtener las relaciones de las listas'));
    }
}