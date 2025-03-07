<?php
	session_start();
    require_once('../helpers/parameters.php');
	if(!isset($_SESSION['LOGIN_OK_MANAGER'])){
		header('Location:'.base_url.'login.php');
	}
	include ('idioma.php');
	include ('pages/conexion.php');
?>

<?php
	//Contenido
	if(isset($_GET['view'])){
		switch ($_GET['view']) {
			
            case 'estrategias':
                //include('pages/Estrategias/estrategias.php');
                include ('cruds/DYALOGOCRM_SISTEMA/G2/G2.php');
                break;

            case 'cargueDatos':
                include ('carga/carga.php');
                break;

            case 'usuariosG1':
                include ('cruds/DYALOGOCRM_SISTEMA/G1/G1.php');
                break;


            case 'flujograma':
                include ('cruds/DYALOGOCRM_SISTEMA/G2/flujograma.php');
                break;

            case 'campan':
                include ('cruds/DYALOGOCRM_SISTEMA/G10/G10.php');
                break;

            case 'com_web_form':
            case 'web_form':
                include ('cruds/DYALOGOCRM_SISTEMA/G13/G13.php');
                break;
                
            case 'mail':
                include ('cruds/DYALOGOCRM_SISTEMA/G14/G14.php');
                break;

            case 'sms_S':
                include ('cruds/DYALOGOCRM_SISTEMA/G15/G15.php');
                break;

            case 'mail_entrante':
                include ('cruds/DYALOGOCRM_SISTEMA/G17/G17.php');
                break;

            case 'llam_saliente':
                include ('cruds/DYALOGOCRM_SISTEMA/G23/G23.php');
                break;

            case 'horarios_entrante':
                include ('cruds/DYALOGOCRM_SISTEMA/G24/G24.php');
                break;

            case 'secciones':
                include('cruds/DYALOGOCRM_SISTEMA/G7/G7.php');
                break;

            case 'chatbot':
                include('cruds/DYALOGOCRM_SISTEMA/G26/G26.php');
                break;

            case 'chatbot1':
                include_once('cruds/DYALOGOCRM_SISTEMA/G261/G26.php');
                break;

            case 'sal_whatsapp':
                include('cruds/DYALOGOCRM_SISTEMA/G27/G27.php');
                break;
            
            case 'com_chat_web':
                include('cruds/DYALOGOCRM_SISTEMA/G28/G28.php');
                break;

            case 'com_chat_whatsapp':
                include('cruds/DYALOGOCRM_SISTEMA/G29/G29.php');
                break;

            case 'com_chat_facebook':
                include('cruds/DYALOGOCRM_SISTEMA/G30/G30.php');
                break;

            case 'com_email_entrante':
                include('cruds/DYALOGOCRM_SISTEMA/G31/G31.php');
                break;
            
            case 'com_sms_entrante':
                include('cruds/DYALOGOCRM_SISTEMA/G32/G32.php');
                break;
            
            case 'com_instagram':
                include('cruds/DYALOGOCRM_SISTEMA/G34/G34.php');
                break;

            case 'cargue_manual':
                include('cruds/DYALOGOCRM_SISTEMA/G35/G35.php');
                break;

            case 'calidad':
                include('cruds/DYALOGOCRM_SISTEMA/G37/G37.php');
                break;

		}
	}
?>

