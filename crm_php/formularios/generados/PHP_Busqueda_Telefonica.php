<?php
	ini_set('display_errors', 'On');
	ini_set('display_errors', 1);
	include(__DIR__."/../../conexion.php");
	date_default_timezone_set('America/Bogota');
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    	if($_GET['action'] == "GET_DATOS"){

    		$Lsql_Campan = "SELECT CAMPAN_ConsInte__GUION__Pob_b, CAMPAN_ConsInte__MUESTR_b, CAMPAN_ConsInte__GUION__Gui_b  FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET["campana_crm"];
	        $res_Lsql_Campan = $mysqli->query($Lsql_Campan);
	        $datoCampan = $res_Lsql_Campan->fetch_array();
	        $str_Pobla_Campan = "G".$datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Pobla_Camp_2 = $datoCampan['CAMPAN_ConsInte__GUION__Pob_b'];
	        $int_Muest_Campan = $datoCampan['CAMPAN_ConsInte__MUESTR_b'];
	        $int_Guion_Campan = $datoCampan['CAMPAN_ConsInte__GUION__Gui_b'];


			$Lsql = " SELECT * FROM ".$BaseDatos.".".$str_Pobla_Campan;
			$Where = " WHERE ";
			
			if(!isset($_POST['consinte'])){
                if(isset($_POST['Telefono']) && $_POST['Telefono'] !=''){
                    $camposTel=$mysqli->query("SELECT CAMCON_ConsInte__PREGUN_b FROM DYALOGOCRM_SISTEMA.CAMCON WHERE CAMCON_ConsInte__CAMPAN_b={$_GET["campana_crm"]}");
                    if($camposTel && $camposTel->num_rows >0){
                        $and=0;
                        while($campo = $camposTel->fetch_object()){
                            if($and==0){
                                $Where .="{$str_Pobla_Campan}_C{$campo->CAMCON_ConsInte__PREGUN_b}='{$_POST['Telefono']}'";
                            }else{
                                $Where .="OR {$str_Pobla_Campan}_C{$campo->CAMCON_ConsInte__PREGUN_b}='{$_POST['Telefono']}'";
                                $and=1;
                            }
                        }
                    }
                }else{
                    $Where ="WHERE 1";
                }
			}else{
				if(!is_null($_POST['consinte']) && $_POST['consinte'] != ''){
					$Where .= $str_Pobla_Campan."_ConsInte__b = ". $_POST['consinte'];
				}
			}
			


			$Lsql .= $Where;
			//echo $Lsql;
			$resultado = $mysqli->query($Lsql);
			$arrayDatos = array();
			$newJson = array();
			$newJson[0]['cantidad_registros'] = 0;

			//mejora
			if($resultado){
				while ($key = $resultado->fetch_assoc()) {
					$arrayDatos[] = $key;
					$newJson[0]['cantidad_registros'] = $resultado->num_rows;
				}
			}

			$newJson[0]['registros'] = $arrayDatos;
			echo json_encode($newJson);
		}

	}
?>
