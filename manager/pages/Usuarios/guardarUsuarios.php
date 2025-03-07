<?php
	include ('../conexion.php');
	session_start();

    function guardar_auditoria($accion, $superAccion){
        include ('../conexion.php');
        $Lsql = "INSERT INTO ".$BaseDatos_systema.".AUACAD (AUACAD_Fecha_____b , AUACAD_Hora______b, AUACAD_Ejecutor__b, AUACAD_TipoAcci__b , AUACAD_SubTipAcc_b, AUACAD_Accion____b , AUACAD_Huesped___b ) VALUES ('".date('Y-m-d')."', '".date('H:s:i')."', ".$_SESSION['IDENTIFICACION'].", 'USUARI', '".$accion."', '".$superAccion."', '".$_SESSION['HUESPED']."' );";
        $mysqli->query($Lsql);
    }  

    
	if(isset($_POST['hidUsuari'])){
		$imagenNombre = $_SESSION['FOTO'];
		if($_POST['hidOculto'] != '0'){
			
            $ruta = "../PerfilesImagenes";
            if(!file_exists($ruta))
            {
                mkdir($ruta);
            }

            $extencion = explode('.', basename($_FILES['inpFotoPerfil']['name']));
            $nombre = $_POST['hidUsuari'];    
            $target_path = $ruta.'/'.$nombre.'.'.$extencion[1]; 
            $target_path = str_replace(' ', '', $target_path);
            
          // echo "es este".basename($_FILES['inpFotoPerfil']['name']);

            copy($_FILES['inpFotoPerfil']['tmp_name'], $target_path);
            $imagenNombre = $nombre.'.'.$extencion[1]; 
            $imagenNombre = str_replace(' ', '', $imagenNombre);
		}

		if(!empty($_POST['txtPassword']) ){
           $Lsql = "UPDATE ".$BaseDatos_systema.".USUARI SET USUARI_Nombre____b = '".$_POST['txtNombre']."',  USUARI_Clave_____b = '".encriptaPassword($_POST['txtPassword'])."', USUARI_Foto______b = '".$imagenNombre."' WHERE USUARI_ConsInte__b = ".$_POST['hidUsuari']; 
        }else{
           $Lsql = "UPDATE ".$BaseDatos_systema.".USUARI SET USUARI_Nombre____b = '".$_POST['txtNombre']."', USUARI_Foto______b = '".$imagenNombre."' WHERE USUARI_ConsInte__b = ".$_POST['hidUsuari']; 
        }

     	if ($mysqli->query($Lsql) === TRUE) {
     		
	        $Lsql = "SELECT USUARI_Cargo_____b, USUARI_ConsInte__b, USUARI_Codigo____b, USUARI_Nombre____b , USUARI_ConsInte__b, USUARI_FechCrea__b , USUARI_InPeToGu__b, PROYEC_NomProyec_b , USUARI_Foto______b , USUARI_Correo___b, USUARI_Identific_b FROM ".$BaseDatos_systema.".USUARI LEFT JOIN ".$BaseDatos_systema.".PROYEC ON PROYEC_ConsInte__b = USUARI_ConsInte__PROYEC_b WHERE USUARI_ConsInte__b = ".$_POST['hidUsuari'];

	       	$query = $mysqli->query($Lsql) or trigger_error($mysqli->error." [$Lsql]");

	        if($query->num_rows > 0) {

	            $datosSesion = array();
	            while($key = $query->fetch_object()){

	               	$_SESSION['CODIGO'] = $key->USUARI_Codigo____b;
					$_SESSION['NOMBRES']   = $key->USUARI_Nombre____b;
					$_SESSION['IDENTIFICACION'] = $key->USUARI_ConsInte__b;
					$_SESSION['FECHA']  = $key->USUARI_FechCrea__b;
					$_SESSION['ACCESO']  = $key->USUARI_InPeToGu__b;
					$_SESSION['PROYECTO']  = $key->PROYEC_NomProyec_b;
		           	$_SESSION['LOGIN_OK']  = true;  

	                $imagenUser = "assets/img/Kakashi.fw.png";
	                if(file_exists("../PerfilesImagenes/".$key->USUARI_Foto______b)){
	                    $imagenUser = "pages/PerfilesImagenes/".$key->USUARI_Foto______b;
	                }
	                $_SESSION['IMAGEN']  = $imagenUser;  
	                $_SESSION['CARGO']  = $key->USUARI_Cargo_____b;


                    $data = array(  
                                    "strNombre_t"               =>  $key->USUARI_Nombre____b,
                                    "strApellido_t"             =>  NULL,
                                    "strCorreoElectronico_t"    =>  $key->USUARI_Correo___b,
                                    "strContrasena_t"           =>  $_POST['txtPassword'],
                                    "strIdentificacion_t"       =>  $key->USUARI_Identific_b,
                                    "intRol_t"                  =>  6,
                                    "strUsuario_t"              =>  'crm',
                                    "strToken_t"                =>  'D43dasd321',
                                    "intIdHuespedCBX_t"         =>  1
                                );                                                                    
                    $data_string = json_encode($data);   
                    echo $data_string; 
                    $ch = curl_init($Api_Gestion.'dyalogocore/api/usuarios/persistir');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                        'Content-Type: application/json',                                                                                
                        'Content-Length: ' . strlen($data_string))                                                                      
                    ); 
                    $respuesta = curl_exec ($ch);
                    $error = curl_error($ch);
                    curl_close ($ch);
                    //echo " Respuesta => ".$respuesta;
                    //echo " Error => ".$error;
                    if(!empty($respuesta) && !is_null($respuesta)){
                        $json = json_decode($respuesta);

                        if($json->strEstado_t == "ok"){
                            
                        }
                          
                    }
                    guardar_auditoria("ACTUALIZAR", "ACTUALIZO LA CONTRASEÑA DE  ".$_SESSION['IDENTIFICACION'] ." EN USUARI");
	           	}   
	            header('Location: /Manager/index.php?page='.$_POST['ruta']);

	    	}

		}
	}

	function right($str, $length) {
       
         return substr($str, -$length);
    } 
    
    function encriptaPassword($pass){

        $strCad =   "".chr(33).chr(41).chr(90).chr(94).chr(77).chr(33).chr(65).chr(183)."'".chr(83).chr(68).chr(33).chr(64).chr(41).chr(35).chr(40).chr(36).chr(36).chr(35).chr(64).chr(35).chr(41).chr(95).chr(33).chr(64).chr(68).chr(70).chr(65).chr(36)."CZ".chr(35).chr(60)."AJDA".chr(62).chr(60)."ASD".chr(33).chr(64).chr(35)."M".chr(35)."N".chr(36).chr(37)."N".chr(94)."M".chr(38)."N".chr(42)."K".chr(40)."s".chr(91) .chr(92).chr(124).chr(93).chr(91).chr(47).chr(46).chr(96).chr(45).chr(43).chr(61).chr(33)."2M2xz".chr(94)."a12_%#@&\|\/".chr(46)."`'[]=-{}-1#".chr(36)."f%A%__#A!_CA?()+_!@#".chr(36)."";

        $clave = "";
        $pass2 = "";
        $CAR = "";
        $Codigo = "";
        $strLen = "";

        
       // echo $strCad;

        if (is_null($pass) || $pass == ""){
            $pass = $strCad;

        }else{
            if(strlen($pass) > 126){
                echo "La palabra es muy larga para encriptar";   

            }

            if(strlen($pass) < 10){
                $strLen = "00".strlen($pass);
                
            }

            if(strlen($pass) > 10 && strlen($pass) < 100){
                $strLen = "0".strlen($pass);
            }
                                  
            if(strlen($pass) >= 100){
                $strLen = strlen($pass);
            }
            
            
            $pass = $pass . substr($strCad, strlen($pass), strlen($strCad)) . $strLen;
       
        }


        $clave = chr(33).chr(123).chr(37).chr(125).chr(252).chr(40).chr(38).chr(41).chr(64).chr(47).chr(42).chr(64).chr(96).chr(94).chr(64).chr(35).chr(36).chr(33).chr(95).chr(91).chr(93);
        $pass2 = "";

       
        $jose = '';
        
        for($i = 0; $i < strlen($pass); $i++){

            $CAR =  substr($pass, $i, 1);

            $Codigo = substr($clave, (($i - 1) % strLen($clave)) + 1, 1);
           
            if($Codigo == ''){
                //echo "<br> ".'aja';
                $Codigo = substr($clave, 0, 1);
            }
            //echo "<br> ITERACCION ".$i." CAR => ".$CAR." , Codigo => ".$this->vaores($Codigo);
            //echo "<br> Este es el que va => ".  $this->right("0". dechex(ord($Codigo) ^ ord($CAR)), 2);
            $pass2 = $pass2 . right("0". dechex(ord($Codigo) ^ ord($CAR)), 2);

        } 
        return strtoupper($pass2);
     
    }  
?>