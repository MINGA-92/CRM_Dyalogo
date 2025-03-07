
<!--- Listas Desplegables Rutas Entrantes --->
<?php

//Lista De Festivos
$ListaFestivos = "";
$ConsultaSQL = "SELECT id, nombre, id_proyecto FROM dyalogo_telefonia.dy_listas_festivos ORDER BY nombre;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaFestivos = $ListaFestivos . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaFestivos = "";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista De Festivos Por Huesped
$ListaFestivosHuesped = "";
$Huesped= $_SESSION['HUESPED'];
$ConsultaSQL = "SELECT id, nombre, id_proyecto FROM dyalogo_telefonia.dy_listas_festivos WHERE id_proyecto= '". $Huesped ."' ORDER BY nombre;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaFestivosHuesped = $ListaFestivosHuesped . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaFestivosHuesped = "";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista Campanas Por Estrategia
$ListaCampanas = "";
$Huesped= $_SESSION['HUESPED'];
$ConsultaSQL = "SELECT ESTPAS_ConsInte__b, CAMPAN_IdCamCbx__b, ESTPAS_Comentari_b FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN DYALOGOCRM_SISTEMA.CAMPAN ON ESTPAS_ConsInte__CAMPAN_b = CAMPAN_ConsInte__b 
WHERE ESTPAS_Tipo______b= 1 AND CAMPAN_ConsInte__PROYEC_b= '". $Huesped ."' AND md5(concat('".clave_get."', ESTPAS_ConsInte__ESTRAT_b)) = '".$_GET['estrategia']."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaCampanas = $ListaCampanas . '<option value="' . $FilaResultado['CAMPAN_IdCamCbx__b'] . '">' . $FilaResultado['ESTPAS_Comentari_b'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaCampanas = "<option disabled>Sin Resultados</option>";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista Campanas Por Huesped
$ListaCampanasHuesped = "";
$Huesped= $_SESSION['HUESPED'];
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_campanas WHERE id_proyecto= '". $Huesped ."' ORDER BY nombre_usuario;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaCampanasHuesped = $ListaCampanasHuesped . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre_usuario'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaCampanasHuesped = "<option disabled>Sin Resultados</option>";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista IVRs Por Estrategia
$ListaIVRs = "";
$ConsultaSQL = "SELECT id, ESTPAS_ConsInte__b, nombre_interno_ivr, ESTPAS_Comentari_b FROM DYALOGOCRM_SISTEMA.ESTPAS INNER JOIN dyalogo_telefonia.dy_ivrs ON ESTPAS_ConsInte__b = id_estpas 
WHERE ESTPAS_Tipo______b= 25 AND id_proyecto= '". $Huesped ."' AND ivr_principal= '1' AND md5(concat('p.fs@3!@M', ESTPAS_ConsInte__ESTRAT_b)) = '".$_GET['estrategia']."';";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaIVRs = $ListaIVRs . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['ESTPAS_Comentari_b'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaIVRs = "<option disabled>Sin Resultados</option>";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista IVRs Por Huesped
$ListaIVRsHuesped = "";
$Huesped= $_SESSION['HUESPED'];
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_ivrs WHERE id_proyecto= '". $Huesped ."' AND ivr_principal= '1' ORDER BY nombre_interno_ivr;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaIVRsHuesped = $ListaIVRsHuesped . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre_raiz'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaIVRsHuesped = "";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista Audios
$ListaAudios = "";
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_audios ORDER BY nombre;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaAudios = $ListaAudios . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaAudios = "";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista Audios por Huesped
$ListaAudiosHuesped = "";
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_audios WHERE id_proyecto= '". $Huesped ."' ORDER BY nombre;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaAudiosHuesped = $ListaAudiosHuesped . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaAudiosHuesped = "";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista Troncales
$ListaTroncales = "";
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_troncales ORDER BY nombre_interno;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaTroncales = $ListaTroncales . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre_interno'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaTroncales = "";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

//Lista Troncales por Huesped
$ListaTroncalesHuesped = "";
$Huesped= $_SESSION['HUESPED'];
$ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_troncales WHERE id_proyecto= '". $Huesped ."' ORDER BY nombre_interno;";
if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
    $CantidadResultados = $ResultadoSQL->num_rows;
    if ($CantidadResultados > 0) {
        while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
            $ListaTroncalesHuesped = $ListaTroncalesHuesped . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre_interno'] . '</option>';
        }
    } else {
        //Sin Resultados
        $ListaTroncalesHuesped = "";
    }
} else {
    //Error en la consulta
    $ErrorConsulta = mysqli_error($mysqli);
    mysqli_close($mysqli);
    echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
    ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
    exit;
}

?>


<!-- Listas Desplegables IVR's -->
<?php
    //Lista Grabaciones Por Huesped
    $ListaGrabaciones = "";
    $ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_audios WHERE id_proyecto= '". $Huesped ."' ORDER BY nombre;";
    if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListaGrabaciones = $ListaGrabaciones . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre'] . '</option>';
            }
        } else {
            //Sin Resultados
            $ListaGrabaciones = "";
        }
    } else {
        //Error en la consulta
        $ErrorConsulta = mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
        exit;
    }

    //Lista Encuesta
    $ListaEncuesta = "";
    $ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_encuestas ORDER BY nombre;";
    if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListaEncuesta = $ListaEncuesta . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre'] . '</option>';
            }
        } else {
            //Sin Resultados
            $ListaEncuesta = "";
        }
    } else {
        //Error en la consulta
        $ErrorConsulta = mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
        exit;
    }

    //Lista Campanas IVR
    $ListaCampanasIVR = "";
    $Huesped= $_SESSION['HUESPED'];
    $ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_campanas WHERE id_proyecto= '". $Huesped ."' AND tipo_campana= '1' ORDER BY nombre_usuario;";
    if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListaCampanasIVR = $ListaCampanasIVR . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre_usuario'] . '</option>';
            }
        } else {
            //Sin Resultados
            $ListaCampanasIVR = "";
        }
    } else {
        //Error en la consulta
        $ErrorConsulta = mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
        exit;
    }

    //Lista Aplicaciones
    $ListaAplicaciones = "";
    $ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_aplicaciones_telefonia ORDER BY nombre;";
    if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListaAplicaciones = $ListaAplicaciones . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre'] . '</option>';
            }
        } else {
            //Sin Resultados
            $ListaAplicaciones = "";
        }
    } else {
        //Error en la consulta
        $ErrorConsulta = mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
        exit;
    }

    //Lista IVRs Para IVRs
    $ListaIVRs_2 = "";
    $ConsultaSQL = "SELECT * FROM dyalogo_telefonia.dy_ivrs ORDER BY nombre_interno_ivr;";
    if ($ResultadoSQL = $mysqli->query($ConsultaSQL)) {
        $CantidadResultados = $ResultadoSQL->num_rows;
        if ($CantidadResultados > 0) {
            while ($FilaResultado = $ResultadoSQL->fetch_assoc()) {
                $ListaIVRs_2 = $ListaIVRs_2 . '<option value="' . $FilaResultado['id'] . '">' . $FilaResultado['nombre_usuario_ivr'] . '</option>';
            }
        } else {
            //Sin Resultados
            $ListaIVRs_2 = "";
        }
    } else {
        //Error en la consulta
        $ErrorConsulta = mysqli_error($mysqli);
        mysqli_close($mysqli);
        echo '<script>alert("Error Falla -> ' . $ErrorConsulta . '");</script>';
        ?><script>window.location.href="<?=base_url?>modulo/error";</script><?php 
        exit;
    }
?>
