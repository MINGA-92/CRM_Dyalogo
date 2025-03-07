<?php
	include ('../conexion.php');
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		if($_POST['oclOperacion'] == 'ADD'){
			$jsonAguardar 			= $_POST['mySavedModel'];
			$txtNombreEstrategia 	= $_POST['txtNombreEstrategia'];
           	$cmbProyecto         	= $_POST['cmbProyecto'];
            $cmbEstrategia       	= $_POST['cmbEstrategia'];
            $txtComentarios      	= $_POST['txtComentarios'];

            $Lsql = "INSERT INTO ".$BaseDatos_systema.".ESTRAT(ESTRAT_ConsInte__PROYEC_b, ESTRAT_ConsInte__TIPO_ESTRAT_b, ESTRAT_Nombre____b, ESTRAT_Comentari_b, ESTRAT_Flujograma_b) VALUES (".$cmbProyecto ." , ".$cmbEstrategia.", '".$txtNombreEstrategia."', '".$txtComentarios."', '".$jsonAguardar."')";
       	
            if ($mysqli->query($Lsql) === TRUE) {

                $idEstrategia = $mysqli->insert_id;
                $jsonArecorrer = json_decode($jsonAguardar);
                $nodeArray = $jsonArecorrer->nodeDataArray;
  
                foreach ($nodeArray as $key) {
                   // echo $key->category." Jose fue esto!";
                    $LsqlPas = "INSERT INTO ".$BaseDatos_systema.".ESTPAS (ESTPAS_ConsInte__ESTRAT_b, ESTPAS_Nombre__b, ESTPAS_Tipo______b, ESTPAS_Comentari_b, ESTPAS_key_____b) VALUES (".$idEstrategia.", '".$key->category."', '".$key->text."', '".$key->loc."', '".$key->key."')";
                    $mysqli->query($LsqlPas);
                }


                $datosConecciones = $jsonArecorrer->linkDataArray;

                foreach ($datosConecciones as $conescciones) {
                    $LsqlPass = "SELECT ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$idEstrategia." AND ESTPAS_key_____b = '".$conescciones->from."' ";
                    $f = $mysqli->query($LsqlPass);
                    $from = $f->fetch_array();

                    $LsqlPassTo = "SELECT ESTPAS_ConsInte__b FROM ".$BaseDatos_systema.".ESTPAS WHERE ESTPAS_ConsInte__ESTRAT_b = ".$idEstrategia." AND ESTPAS_key_____b = '".$conescciones->to."' ";
                    $t = $mysqli->query($LsqlPassTo);
                    $to = $t->fetch_array();

                    $InsertCon = "INSERT INTO ".$BaseDatos_systema.".ESTCON(ESTCON_Nombre____b, ESTCON_ConsInte__ESTPAS_Des_b, ESTCON_ConsInte__ESTPAS_Has_b, ESTCON_FromPort_b, ESTCON_ToPort_b, ESTCON_Coordenadas_b) VALUES ('Conector', ".$from['ESTPAS_ConsInte__b'].", ".$to['ESTPAS_ConsInte__b'].", '".$conescciones->fromPort."', '".$conescciones->toPort."', '".json_encode($conescciones->points)."')";
                    //echo $InsertCon." ";
                    $mysqli->query($InsertCon);
                }

            	echo json_encode(array('code' => 1 ));
            }else{
            	 echo "Error Hacieno el proceso los registros : " . $mysqli->error;
            }
            
		}

        if($_POST['oclOperacion'] == 'AGENTS'){
            $agentes = trim($_POST['agentes'],',');
            $estrategia = $_POST['estrategia'];
            $agentes = explode(',', $agentes); 

            $Lsql = "DELETE FROM ".$BaseDatos_systema.".ESTRUSU WHERE ESTRUSU_ConsInte_ESTRAT_b = ".$estrategia;
            $mysqli->query($Lsql);
            foreach ($agentes as $agente) {
                $consulta = "INSERT INTO ".$BaseDatos_systema.".ESTRUSU (ESTRUSU_ConsInte_ESTRAT_b, ESTRUSU_ConsInte_USUARI_b) VALUES ($estrategia, $agente);";
                $mysqli->query($consulta);
            }
        

            echo json_encode(array('code' => 1));
            
        }
	}	
?>