
<?php
    session_start();
    ini_set('display_errors', 'On');
    ini_set('display_errors', 1);
    include("conexion.php");
    require_once('../helpers/parameters.php');
    if (isset($_SESSION['QUALITY'])) {
        $quality=$_SESSION['QUALITY'];
    }else{
        if (isset($_GET["quality"])){
            $quality=$_GET["quality"];
        }else{
            $quality=0;
        }
    }
    if(!isset($_SESSION['LOGIN_OK']) && !isset($_GET['token']) && !isset($_SESSION['LOGIN_OK_MANAGER'])){
        header('Location: login.php?quality='.$quality);
    }

    $token = null;
	$GION_TITLE = "";
    $str_busqueda = "Buscar";

	include('funciones.php');

    if(isset($_GET['token'])){
        $token  = $_GET['token'];
        $userid = getIdToken($token);
        //echo "Este es el ID > ".$userid;
        
        //echo $token;
        $tokenSql = "SELECT * FROM ".$BaseDatos_systema.".SESSIONS WHERE SESSIONS__USUARI_ConsInte__b = ". $userid ." AND SESSIONS__Token = '".$token."' AND SESSIONS__Estado__b = 1 ";
        $query = $mysqli->query($tokenSql) or trigger_error($mysqli->error." [$tokenSql]"); ;
        // si el resultado de la query es positivo
        if($query->num_rows > 0) {

        }else{
            header('Location: message.php');
        }
    }

    //print_r($_GET['formulario']);
    if(isset($_GET['formulario'])){
        $sqlIdGuion=$mysqli->query("select GUION__ConsInte__b as id from {$BaseDatos_systema}.GUION_ where md5(concat('".clave_get."', GUION__ConsInte__b)) = '".$_GET['formulario']."'");
        if($sqlIdGuion && $sqlIdGuion->num_rows==1){
            $sqlIdGuion=$sqlIdGuion->fetch_object();
            $_GET['formulario']=$sqlIdGuion->id;
        }
		$LsqlGUION = "SELECT GUION__ConsInte__b, GUION__Nombre____b, GUION__Tipo______b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b = '".$_GET['formulario']."'";
		$results = $mysqli->query($LsqlGUION);

		while($key = $results->fetch_object()){
			$GION_TITLE = ($key->GUION__Nombre____b);
            $GUION_TYPE = ($key->GUION__Tipo______b);
        } 
        
        if(isset($_GET['tareabackoffice'])){
            $LsqlTarea = "SELECT TAREAS_BACKOFFICE_ConsInte__b, TAREAS_BACKOFFICE_Nombre_b FROM ".$BaseDatos_systema.".TAREAS_BACKOFFICE WHERE TAREAS_BACKOFFICE_ConsInte__b =".$_GET['tareabackoffice'];
            
            $results = $mysqli->query($LsqlTarea);
    
            while($key = $results->fetch_object()){
                $GION_TITLE = ($key->TAREAS_BACKOFFICE_Nombre_b);
            } 
        }
		
		include('head.php');

        if(isset($_GET['busqueda'])){
            print_r( $_GET['busqueda']);
            print_r($_GET['formulario']);
            include ('formularios/G'.$_GET['formulario'].'/G'.$_GET['formulario'].'_Busqueda.php');
        }else{
            if(isset($_GET['formulario'])){
                //print_r($_GET['formulario']);
                $nombre_fichero = 'formularios/G'.$_GET['formulario'].'/G'.$_GET['formulario'].'.php';
                if(file_exists($nombre_fichero)) {
                    include('formularios/G'.$_GET['formulario'].'/G'.$_GET['formulario'].'.php');
                }else{
                    echo "El Archivo: $nombre_fichero  ¡No Existe!";
                    echo '</br>';
                    echo "Actualmente No Existe Un Formulario De Comunicación a Calificar...";
                    //include('formularios/G2992/G2992.php');
                }
    
            }else{
                print_r("¡No Existe Formulario De Comunicación a Calificar!");
            }
        }
        
    }else{
        if(isset($_GET['campan'])){
            $Guion = 0;//id de la campaña
            $tabla = 0;// $_GET['user'];//ide del usuario

            $Lsql = "SELECT CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_ConsInte__b = ".$_GET['campan'];

            $result = $mysqli->query($Lsql);
            while($obj = $result->fetch_object()){
                $Guion = $obj->CAMPAN_ConsInte__GUION__Gui_b;
                $tabla = $obj->CAMPAN_ConsInte__GUION__Pob_b;
            } 

            
            //SELECT de la camic
            $campSql = "SELECT CAMINC_NomCamPob_b, CAMINC_NomCamGui_b FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPAN_b = ".$_GET['campan'];
            
			$LsqlGUION = "SELECT GUION__ConsInte__b, GUION__Nombre____b FROM ".$BaseDatos_systema.".GUION_ WHERE GUION__ConsInte__b =".$Guion;
		
			$results = $mysqli->query($LsqlGUION);

			while($key = $results->fetch_object()){
				$GION_TITLE = ($key->GUION__Nombre____b);
			} 
			
			include('head.php');
		
            
            if(isset($_GET['busqueda'])){
                include ('formularios/G'.$Guion.'/G'.$Guion.'_Busqueda.php');
            }else{
                include ('formularios/G'.$Guion.'/G'.$Guion.'.php');
            }
        }

        if(isset($_GET['id_campana_cbx'])){
            $Lsql = "SELECT CAMPAN_TipoCamp__b, CAMPAN_ConsInte__GUION__Gui_b, CAMPAN_ConsInte__GUION__Pob_b FROM ".$BaseDatos_systema.".CAMPAN WHERE CAMPAN_IdCamCbx__b =".$_GET['id_campana_cbx'];
            $resultado = $mysqli->query($Lsql);
            $CAMPAN_TipoCamp__b = 1;
            $CAMPAN_ConsInte__GUION__Gui_b = null;
            $CAMPAN_ConsInte__GUION__Pob_b = null;
            while ($key = $resultado->fetch_object()) {
                $CAMPAN_TipoCamp__b = $key->CAMPAN_TipoCamp__b;
                $CAMPAN_ConsInte__GUION__Gui_b = $key->CAMPAN_ConsInte__GUION__Gui_b;
                $CAMPAN_ConsInte__GUION__Pob_b = $key->CAMPAN_ConsInte__GUION__Pob_b;
            }

            include('head.php');
            
            switch ($CAMPAN_TipoCamp__b) {
                case 1:
                    include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Manual.php');
                    break;

                case 5:
                    include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Telefono.php');
                    break;

                case 6:
                    include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_Telefono.php');
                    break;

                case 3:
                   
                    include ('formularios/G'.$CAMPAN_ConsInte__GUION__Pob_b.'/G'.$CAMPAN_ConsInte__GUION__Pob_b.'_Busqueda_ani.php');
                    break;
            }
            
        }

        if(!isset($_GET['id_campana_cbx']) && !isset($_GET['campan']) && !isset($_GET['pagina'])){
            include('head.php');
        }
    }

    if(isset($_GET['pagina'])){
        include('head.php');
        include($_GET['pagina']."/".$_GET['pagina'].".php");
    }
    include('footer.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index ::: CRM</title>
</head>
<body>

    <?php $url_crudG1 = base_url."cruds/DYALOGOCRM_SISTEMA/G1/G1_CRUD.php"; ?>
    <script>
        //Funciones De Perfil - Contraseña y Foto
        $(document).ready(function(){
            $("#btnCambiarFoto").hide();
        });

        //Funcion para validar si las contraseñas son iguales
        const validarPassword = () => {
            const txrPasswordCh = document.getElementById('txrPasswordCh');
            const txrPasswordChR = document.getElementById('txrPasswordChR');
            const btnCambiarFoto = document.getElementById('btnCambiarFoto');
            const form_group_pass2 = txrPasswordChR.closest('.form-group');

            // password1
            txrPasswordCh.oninput = (e) => {
                const p2 = form_group_pass2.querySelector('p');
                if (e.target.value === txrPasswordChR.value){
                    btnCambiarFoto.removeAttribute('disabled');
                    txrPasswordChR.closest('.form-group').classList.remove('has-error');
                    $("#Alerta").css("color", "green");
                    $("#Alerta").text(" ✔️ ¡Las Contraseñas Coinciden!");
                    $("#BtnCambiarPass").prop("disabled", false);
                    $("#ActualPassword").prop("disabled", false);
                    if(p2 !== null){
                        p2.remove();
                    }
        
                }else{
                    btnCambiarFoto.setAttribute('disabled', true)
                    txrPasswordChR.closest('.form-group').classList.add('has-error');
                    $("#Alerta").css("color", "red");
                    $("#Alerta").text("❌ ¡Las Contraseñas Deben Coincidir! ");
                    $("#BtnCambiarPass").prop("disabled", true);

                }
            }

            // password2
            txrPasswordChR.oninput = (e) => {
                const p2 = form_group_pass2.querySelector('p');
                if (e.target.value === txrPasswordCh.value){
                    btnCambiarFoto.removeAttribute('disabled');
                    txrPasswordChR.closest('.form-group').classList.remove('has-error');
                    $("#Alerta").css("color", "green");
                    $("#Alerta").text(" ✔️ ¡Las Contraseñas Coinciden!");
                    $("#BtnCambiarPass").prop("disabled", false);
                    $("#ActualPassword").prop("disabled", false);
                    if(p2 !== null){
                        p2.remove();
                    }
                
                }else{
                    btnCambiarFoto.setAttribute('disabled', true);
                    txrPasswordChR.closest('.form-group').classList.add('has-error');
                    $("#Alerta").css("color", "red");
                    $("#Alerta").text("❌ ¡Las Contraseñas Deben Coincidir! ");
                    $("#BtnCambiarPass").prop("disabled", true);

                }
            }
        }

        <?php if($_SESSION['CLAVE_TEMPORAL'] == "-1"): ?>
            $("#cambiarPerfil").modal("show");
            $("#nombrePasswordCh").css("color", "red");
            $("#nombrePasswordCh").text("!Tu contraseña ha expirado, debes cambiarla ahora!");
            $("#btnCancelar_2").hide();
        <?php endif; ?>

        // SI SE CARGA ALGUN ARCHIVO SE INTENTA ACTUALIZAR LA IMAGEN DE PERFIL
        $("#inpFotoPerfil2").on("change", (e) => {
            $("#btnCambiarFoto").removeAttr("disabled");
        })

        $("#btnCambiarFoto").click(function(){
            const event = new Event('input');
            const txrPasswordCh = document.getElementById('txrPasswordCh');
            const txrPasswordChR = document.getElementById('txrPasswordChR');

            txrPasswordCh.dispatchEvent(event);
            txrPasswordChR.dispatchEvent(event);

            //Se valida si lo que se quiere es cambia la imagen o la contraseña
            if($("#txrPasswordCh").val() != ""){
                let passData = new FormData($("#insertPerfil")[0]);
                let data = {
                    "txrPasswordCh": passData.get('txrPasswordCh'),
                    "txrPasswordChR": passData.get('txrPasswordChR'),
                    "hidUsuari": passData.get('hidUsuari')
                }
                console.log("passData", passData);
                console.log("data", data);

                $.ajax({
                    url: "<?= $url_crudG1 ?>?modificarPassword=true",
                    type: 'POST',
                    data:  data,
                    beforeSend: function () {
                        $.blockUI({
                            baseZ : 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo 'Por favor, Espere un momento';?>' 
                        })
                    },
                    success : function(data){
                        let json = JSON.parse(data)
                        if(json.estado === "success"){
                            alertify.success('<?php echo 'Su contraseña se ha enviado al correo'.' '.$_SESSION['CORREO'];?>');
                            $("#cambiarPerfil").modal('hide');
                            $("#btnCancelar_2").show();
                            $("#ActualPassword").val('');
                            $("#txrPasswordCh").val('');
                            $("#txrPasswordChR").val('');
                            swal({
                                title: "¡Actualizado!  😏",
                                text: "Contraseña Cambiada Exitosamente!",
                                icon: "success",
                                showConfirmButton: false,
                                confirmButtonColor: '#2892DB',
                                timer: 2000
                            });
                        }else{
                            console.log(" ☠ error modificarPassword");
                            alertify.error(json.message);
                        }
                    },
                    complete: function () {
                        $.unblockUI()
                    },
                    error: function (res) {
                        console.log(res);
                        alertify.error(`Se presento un error al guardar la informacion ${res}`);
                    }
                });
            }
        
            if($("#inpFotoPerfil2").val() != ""){
                let imageData = new FormData($("#insertPerfil")[0]);
                imageData.delete("txrPasswordCh");
                imageData.delete("txrPasswordChR")
                console.log("imageData", imageData);

                $.ajax({
                    url: "<?= $url_crudG1 ?>?modificarImagen=true",
                    type: 'POST',
                    data: imageData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $.blockUI({
                            baseZ : 2000,
                            message: '<img src="assets/img/clock.gif" /> <?php echo 'Por favor, Espere un momento';?>' 
                        })
                    },
                    success : function(data){
                        let json = JSON.parse(data)
                        if(json.estado === "success"){
                            alertify.success('<?php echo 'Imagen Actualizada Correctamente';?>');
                            $("#user-image").attr("src", json.image_url);
                            $("#user-image-menu").attr("src", json.image_url);
                            $("#cambiarPerfil").modal('hide');
                            $("#ActualPassword").val('');
                            $("#txrPasswordCh").val('');
                            $("#txrPasswordChR").val('');
                        }else{
                            console.log(" ☠ error modificarImagen");
                            alertify.error(json.message);
                        }
                    },
                    complete: function () {
                        $.unblockUI()
                    },
                    error: function (res) {
                        console.log(res);
                        alertify.error(`Se presento un error al guardar la informacion ${res}`);
                    }
                });
            }
            //window.location.reload();
            
        });

        //Politicas de contraseñas
        const PasswordPolicy = (password) => {
            let pass = 0;
            let obligatoria = 0;
            let RegxMayus = /[A-Z]/;
            let RegxCaracter = /[\W]/;
            let Regxminusculas = /[a-z]/;
            let RegxNumeros = /[0-9]/;

            //Evaluacion de politicas 
            password.length >= 8 ? (obligatoria += 1) : (obligatoria += 0);
            RegxMayus.test(password) ? (pass += 1) : (pass += 0);
            RegxCaracter.test(password) ? (pass += 1) : (pass += 0);
            RegxNumeros.test(password) ? (pass += 1) : (pass += 0);
            Regxminusculas.test(password) ? (pass += 1) : (pass += 0);

            return obligatoria == 1 && pass >= 3;
        };

        //Campo Nueva Contraseña
        $("#txrPasswordCh").keyup(function () {
            var password = $("#txrPasswordCh").val();
            var validacion = PasswordPolicy(password);
            if(validacion){
                $("#BtnCambiarPass").prop("disabled", false);
            }else{
                $("#error_text").css("display", "block");
                $("#BtnCambiarPass").prop("disabled", true);
            }
            $("#Alerta").prop("hidden", false);

            //Validacion de contraseña
            if(password.length >= 8){
                if (/[A-Z]/.test(password)) {
                    if (/[a-z]/.test(password)) {
                        if (/[0-9]/.test(password)) {
                            if(/[\W]/.test(password)){
                                $("#Alerta").css("color", "green");
                                $("#Alerta").text("!Contraseña Valida!  ✔️ ");
                                $("#txrPasswordChR").prop("disabled", false);
                            }else{
                                $("#Alerta").css("color", "red");
                                $("#Alerta").text("❌ La Contraseña Debe Contener Algún Caracter Especial Como '!#$%&/?' ");
                                $("#txrPasswordChR").prop("disabled", true);
                                $("#BtnCambiarPass").prop("disabled", true);
                            }
                        }else{
                            $("#Alerta").css("color", "red");
                            $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo Un Numero ");
                            $("#txrPasswordChR").prop("disabled", true);
                            $("#BtnCambiarPass").prop("disabled", true);
                        }
                    }else{
                        $("#Alerta").css("color", "red");
                        $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo Una Letra 'minuscula' ");
                        $("#txrPasswordChR").prop("disabled", true);
                        $("#BtnCambiarPass").prop("disabled", true);
                    }
                }else{
                    $("#Alerta").css("color", "red");
                    $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo Una Letra 'MAYUSCULA' ");
                    $("#txrPasswordChR").prop("disabled", true);
                    $("#BtnCambiarPass").prop("disabled", true);
                }
            }else{
                $("#Alerta").css("color", "red");
                $("#Alerta").text("❌ La Contraseña Debe Contener Como Mínimo 8 Caracteres ");
                $("#txrPasswordChR").prop("disabled", true);
                $("#BtnCambiarPass").prop("disabled", true);
            }
        });

        //Campo Confirmar Contraseña
        $("#txrPasswordChR").keyup(function () {
            validarPassword();
        });

        //Mostrar Contraeñas
        function VerContrasena(Actual){
            if(Actual == "text"){
                Tipo= "password"
            }else{
                Tipo= "text"
            }
            return Tipo
        }
        $("#VerPassActual").click(function () {
            var Actual= $("#ActualPassword").attr("type");
            var Ahora= VerContrasena(Actual);
            $("#ActualPassword").attr("type", Ahora);
        });
        $("#VerPassNueva").click(function () {
            var Actual= $("#txrPasswordCh").attr("type");
            var Ahora= VerContrasena(Actual);
            $("#txrPasswordCh").attr("type", Ahora);
        });
        $("#VerPassConfirmar").click(function () {
            var Actual= $("#txrPasswordChR").attr("type");
            var Ahora= VerContrasena(Actual);
            $("#txrPasswordChR").attr("type", Ahora);
        });
        
        //Validar Antes De Guardar
        $("#BtnCambiarPass").click(function () {
            let Formulario = new FormData();
            NewPassword= $("#txrPasswordCh").val();
            ConfirPassword= $("#txrPasswordChR").val();
            IdUsuario= $("#IdUsuario").val();
            IdHuesped= $("#IdHuesped").val();
            ActualPassword= $("#ActualPassword").val();

            if(NewPassword != ConfirPassword){
                $("#Alerta").css("color", "red");
                $("#Alerta").text("❌ ¡Las Contraseñas Deben Coincidir! ");
                $("#ActualPassword").prop("disabled", true);
            }else{
                $("#Alerta").css("color", "green");
                $("#Alerta").text(" ✔️ ¡Las Contraseñas Coinciden!");
                $("#ActualPassword").prop("disabled", false);

                //console.log("ActualPassword: ", ActualPassword);
                if((ActualPassword == null) || (ActualPassword == "")){
                    swal({
                        icon: 'error',
                        title: '🤨 Oops...',
                        text: 'Se Debe Diligenciar El Campo "Contraseña Actual"',
                        confirmButtonColor: '#2892DB'
                    })
                    $("#divActual").css("color", "red");
                    $("#ActualPassword").prop("style", "border-color: red;");
                }else{
                    $("#divActual").css("color", "black");
                    $("#ActualPassword").prop("style", "border-color: black;");
                    Formulario.append('IdUsuario', IdUsuario);
                    Formulario.append('IdHuesped', IdHuesped);
                    Formulario.append('ActualPassword', ActualPassword);
                    
                    
                    $.ajax({
                        url: "<?=base_url?>cruds/DYALOGOCRM_SISTEMA/G1/Controller/ValidaPassword.php",
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: Formulario,
                        success: function(php_response) {
                            Respuesta = php_response.msg;
                            console.log(Respuesta);
                            if (Respuesta == "Ok"){
                                ListaPassword= php_response.Resultado[0];
                                ClaveSQL= ListaPassword[0];
                                ClaveDigitada= ListaPassword[1];
                                if(ClaveDigitada == ClaveSQL){
                                    //console.log("ClaveSQL: ", ClaveSQL);
                                    //console.log("ClaveDigitada: ", ClaveDigitada);
                                    $("#btnCambiarFoto").click();
                                }else{
                                    swal({
                                        icon: 'error',
                                        title: '¡Contraseña Incorrecta! 🤨 ',
                                        text: 'La Contraseña Digitada, No Coincide Con La Registrada...',
                                        confirmButtonColor: '#2892DB'
                                    })
                                    console.log("ClaveSQL: ", ClaveSQL);
                                    console.log("ClaveDigitada: ", ClaveDigitada);
                                }
                            }else if (Respuesta == "Nada"){
                                swal({
                                    icon: 'info',
                                    title: ' 🤔 ¡Nada!',
                                    text: 'No Se Encontró Contraseña Registrada, Para Este Usuario...',
                                    confirmButtonColor: '#2892DB'
                                })
                            }else if (Respuesta == "Error"){
                                swal({
                                    icon: 'error',
                                    title: '¡Error Al Validar Contraseña!  😵 ',
                                    text: 'Por Favor, Consultar Con El Desarrollador Del Sistema...',
                                    confirmButtonColor: '#2892DB'
                                })
                                console.log(php_response.Falla);
                            }
                        },
                        error: function(php_response) {
                            swal({
                                icon: 'error',
                                title: ' ☠ ¡Error Servidor!',
                                text: 'Por Favor, Contactar Al Administrador Del Sistema...',
                                confirmButtonColor: '#2892DB'
                            })
                            console.log(php_response.msg);
                            php_response = JSON.stringify(php_response);
                            console.log(php_response);
                        }
                    });
                    
                }
            }
            
        });
        

        //Validar Si Se Debe Notificar Caducidad o Bloquear Automaticamente
        $(document).ready(function(){

            //Notificar Caducidad
            var Notificar= <?php echo $_SESSION['NotificarCaducidad']; ?>;
            console.log("Notificar: ", Notificar);
            if(Notificar == true){
                
                swal({
                    title: "¡Tu Contraseña Vence Pronto!  🫣 ",
                    text: "Por Favor, Modificarla Para Evitar Bloqueos...",
                    type: "warning",
                    confirmButtonColor: "#2892DB",
                    confirmButtonText: "Cambiar Ahora",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    cancelButtonText: "Mas Tarde",
                    },
                    function(){
                    $("#cambiarPerfil").modal("show");
                });

            }

            //Bloquear Automaticamente
            var Bloquear= <?php echo $_SESSION['BloqueoAutomatico']; ?>;
            console.log("Bloquear: ", Bloquear);
            if(Bloquear == true){
                swal({
                    title: "¡Usuario Bloqueado!   🔏",
                    text: "Por Favor Contactar Al Administrador Del Sistema…",
                    timer: 3000
                });
                window.location.href="login.php";
            }

        });

    </script>

</body>
</html>
