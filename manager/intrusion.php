<?php
require_once('../conexion.php');
if(isset($_GET['agente']) && isset($_GET['intruso'])){  
    $agente=$_GET['agente'];
    $intruso=$_GET['intruso'];
    // $pass="select contrasena from dyalogo_telefonia.dy_usuarios where id={$intruso}";
    // $pass=$mysqli->query($pass);
    // if($pass->num_rows > 0){
    //     $pass=$pass->fetch_object();
    //     $password=$pass->contrasena;
    // }
    

    $ch = curl_init("http://127.0.0.1:8080/dy_public_front/api/intrusion/{$agente}-{$intruso}-U4xJYZ4z");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


    $respuesta = curl_exec($ch);
    echo $respuesta;
    $error = curl_error($ch);
    curl_close($ch);
}
?>