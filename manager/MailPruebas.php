<?php
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);

	include ('pages/conexion.php');

	
	/* Listo tenemos el paso ahora revisamos que tengan configurado su Respectivo crud */
	/* tenemos la base de datos y la muestra */
	$selTabla = "SELECT MUESTR_ConsInte__GUION__b FROM ".$BaseDatos_systema.".MUESTR WHERE MUESTR_ConsInte__b = 427 ";

	$res_SelTabla = $mysqli->query($selTabla);
	
	$dataTabla = $res_SelTabla->fetch_array();

	$LsqlMail = "SELECT * FROM ".$BaseDatos_systema.".G14 WHERE G14_C136 = 180  ";

    $res_LsqlMail = $mysqli->query($LsqlMail);

    $datosMail = $res_LsqlMail->fetch_array();

   	$muestraCompleta = "G".$dataTabla['MUESTR_ConsInte__GUION__b']."_M427";


    $MuestraLsql = "SELECT ".$muestraCompleta."_CoInMiPo__b as id FROM ".$BaseDatos.".".$muestraCompleta." WHERE ".$muestraCompleta."_Estado____b = 0;";

   // echo $MuestraLsql;
    
    $res_MuestraLsql = $mysqli->query($MuestraLsql);

    while ($resM = $res_MuestraLsql->fetch_object() ) {
    	/* ya tenemos la lista de la muestra a vencer */
    	$cuerpoAusar = cambiarValores($datosMail['G14_C143'],$dataTabla['MUESTR_ConsInte__GUION__b'],$resM->id);
		
		$paraAusar__ = cambiarValores($datosMail['G14_C139'],$dataTabla['MUESTR_ConsInte__GUION__b'],$resM->id); 
        
        $asuntoAusar = cambiarValores($datosMail['G14_C142'],$dataTabla['MUESTR_ConsInte__GUION__b'],$resM->id); 

        

            //$idcofig, $subject, $strCuerpo_t, $to
        $datosAdjuntos = NULL;
        if($datosMail['G14_C144'] != '' && $datosMail['G14_C144'] != null){
            $datosAdjuntos = $datosMail['G14_C144'];
        }

        $cc_Asunto__ = null;
        if($datosMail['G14_C140'] != '' && $datosMail['G14_C140'] != null){
           $cc_Asunto__ = cambiarValores($datosMail['G14_C140'],$dataTabla['MUESTR_ConsInte__GUION__b'],$resM->id); 
        }

        $cc_copia__ = null;
        if($datosMail['G14_C141'] != '' && $datosMail['G14_C141'] != null){
            $cc_copia__ = cambiarValores($datosMail['G14_C140'],$dataTabla['MUESTR_ConsInte__GUION__b'],$resM->id); 
        }

        Send_Mail_From_Rest($datosMail['G14_C138'], $asuntoAusar, $cuerpoAusar, $paraAusar__, $cc_Asunto__, $cc_copia__ , $datosAdjuntos, $muestraCompleta, $resM->id);

	}




	function cambiarMail($campo, $guion, $id_de_registro){
		/* primero es convertir los campos en variables */
		include ('pages/conexion.php');

    	$Lsqlx = "SELECT G".$guion."_C".$campo." as PREGUN FROM ".$BaseDatos.".G".$guion." WHERE G".$guion."_ConsInte__b = ".$id_de_registro.' LIMIT 1';
    	$res = $mysqli->query($Lsqlx);
    	$datosUsuario = $res->fetch_array();
    	if($res){
    		if($datosUsuario['PREGUN'] != '' && $datosUsuario['PREGUN'] != null){
    			return $datosUsuario['PREGUN'];
    		}else{
    			return 0;
    		}
    		
    	}else{
    		return 0;
    	}
        
	}
	


	function cambiarValores($cadena, $guion, $id_de_registro){
		/* primero es convertir los campos en variables */
		include ('pages/conexion.php');
		$Lsql = "SELECT PREGUN_Texto_____b, PREGUN_ConsInte__b, PREGUN_Tipo______b, PREGUN_ConsInte__OPCION_B FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$guion;
		$cadenaFinal = $cadena;
        $cur_result = $mysqli->query($Lsql);
        while ($key = $cur_result->fetch_object()) {	
        	/* ahora toca buscar el valor de esos campos en la jugada */
            $dato = str_replace(' ', '_', ($key->PREGUN_Texto_____b));
        	$buscar = '${'.substr($dato, 0, 20).'}';

            if($key->PREGUN_Tipo______b == '6'){
                $Lsqlx = "SELECT LISOPC_Nombre____b as PREGUN FROM ".$BaseDatos.".G".$guion." JOIN ".$BaseDatos_systema.".LISOPC ON G".$guion."_C".$key->PREGUN_ConsInte__b." = LISOPC_ConsInte__b WHERE G".$guion."_ConsInte__b = ".$id_de_registro.' LIMIT 1';
            }else{
                $Lsqlx = "SELECT G".$guion."_C".$key->PREGUN_ConsInte__b." as PREGUN FROM ".$BaseDatos.".G".$guion." WHERE G".$guion."_ConsInte__b = ".$id_de_registro.' LIMIT 1';
            }

        	
        	$res = $mysqli->query($Lsqlx);
        	$datosUsuario = $res->fetch_array();

        	$cadenaFinal = str_replace($buscar, $datosUsuario['PREGUN'] , $cadenaFinal);
        }	


        $buscar = '${ID_REGISTRO}';
        $cadenaFinal = str_replace($buscar, base64_encode($id_de_registro) , $cadenaFinal);
        
        return $cadenaFinal;
	}


	function Send_Mail_From_Rest($idcofig, $subject, $strCuerpo_t, $to, $cc = null, $co= null, $listaAd= null , $muestraCompleta, $idMuestra ){
        include ('pages/conexion.php');
        $data = array(  
                    "strUsuario_t"              =>  'crm',
                    "strToken_t"                =>  'D43dasd321',
                    "strIdCfg_t"                =>  $idcofig,
                    "strTo_t"                   =>  'david.andrade@dyalogo.com',
                    "strCC_t"                   =>  $cc,
                    "strCCO_t"                  =>  $co,
                    "strSubject_t"              =>  $subject,
                    "strMessage_t"              =>  $strCuerpo_t,
                    "strListaAdjuntos_t"        =>  '/tmp/'.$listaAd

                    );                                                                    
        $data_string = json_encode($data);   
        echo $data_string; 
        $ipDEServicio = 'http://old-customers.dyalogodev.com:8080';
        $ch = curl_init($ipDEServicio.'/dyalogocore/api/ce/correo/sendmailservice');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(    
                'Accept: application/json',                                                               
                'Content-Type: application/json',                                                  
                'Content-Length: '.strlen($data_string)
            )                                                                      
        ); 
        $respuesta = curl_exec ($ch);
        $error = curl_error($ch);
        curl_close ($ch);
        echo "Respuesta => ".$respuesta;
        echo "Error => ".$error;

        $UdpateLsql = "UPDATE ".$BaseDatos.".".$muestraCompleta." SET ".$muestraCompleta."_Estado____b = 3 , ".$muestraCompleta."_FecUltGes_b = '".date('Y-m-d H:i:s')."' , ".$muestraCompleta."_Comentari_b = 'Respuesta => ".$respuesta." - Error => ".$error."' WHERE ".$muestraCompleta."_CoInMiPo__b  = ".$idMuestra;

        $mysqli->query($UdpateLsql);
    }
	
