<?php

ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."/pages/conexion.php");

// Vamos a pasar por cada bot
$sql = "SELECT * FROM dyalogo_canales_electronicos.dy_bot";
$res = $mysqli->query($sql);

if($res && $res->num_rows > 0){

    while($bot = $res->fetch_object()){

        echo "----- Recorriendo el bot " . $bot->nombre . '<br>';

        // Validamos que el campo donde almacenamos las gestiones antiguas este lleno
        if(!is_null($bot->id_pregun_dato_capturado) && !is_null($bot->id_guion_gestion)){

            // Validamos que esa tabla exista y tenga por lo menos un registro

            $sql = "SELECT * FROM DYALOGOCRM_WEB.B{$bot->id_pregun_dato_capturado} limit 1";
            $resReGBot = $mysqli->query($sql);
            
            if($resReGBot && $resReGBot->num_rows > 0){

                echo '-- Se encontraron registros para el bot ' . $bot->nombre . '<br>';

                // Nos toca buscar campos de sistema
                $sql = "SELECT PREGUN_ConsInte__b AS id, PREGUN_Texto_____b AS nombre from DYALOGOCRM_SISTEMA.PREGUN WHERE PREGUN_ConsInte__GUION__b = {$bot->id_guion_gestion} AND (PREGUN_Texto_____b = 'Tipificacion' OR PREGUN_Texto_____b = 'Observacion' OR PREGUN_Texto_____b = 'Dato principal' OR PREGUN_Texto_____b = 'Tipificacion_Bot' OR PREGUN_Texto_____b = 'UltimaSeccion' OR PREGUN_Texto_____b = 'UltimoMensaje')";
                
                $resCampDefecto = $mysqli->query($sql);

                $camposSistema = [];

                while ($cam = $resCampDefecto->fetch_object()) {
                    if($cam->nombre == 'Tipificacion'){
                        $camposSistema['tipificacion'] = $cam->id;
                    }
                    if($cam->nombre == 'Observacion'){
                        $camposSistema['observacion'] = $cam->id;
                    }
                    if($cam->nombre == 'Dato principal'){
                        $camposSistema['dato_principal'] = $cam->id;
                    }
                    if($cam->nombre == 'Tipificacion_Bot'){
                        $camposSistema['tipificacion_bot'] = $cam->id;
                    }
                    if($cam->nombre == 'UltimaSeccion'){
                        $camposSistema['ultima_seccion'] = $cam->id;
                    }
                    if($cam->nombre == 'UltimoMensaje'){
                        $camposSistema['ultimo_mensaje'] = $cam->id;
                    }
                }

                $camposGx = "
                G{$bot->id_guion_gestion}_FechaInsercion,
                G{$bot->id_guion_gestion}_CodigoMiembro,
                G{$bot->id_guion_gestion}_PoblacionOrigen,
                G{$bot->id_guion_gestion}_Origen_b,
                G{$bot->id_guion_gestion}_IdLlamada,
                G{$bot->id_guion_gestion}_Sentido___b,
                G{$bot->id_guion_gestion}_Canal_____b,
                G{$bot->id_guion_gestion}_LinkContenido,
                G{$bot->id_guion_gestion}_DatoContacto,
                G{$bot->id_guion_gestion}_Paso,
                G{$bot->id_guion_gestion}_Clasificacion,
                G{$bot->id_guion_gestion}_Duracion___b,
                G{$bot->id_guion_gestion}_C{$camposSistema['tipificacion']},
                G{$bot->id_guion_gestion}_C{$camposSistema['observacion']},
                G{$bot->id_guion_gestion}_C{$camposSistema['dato_principal']},
                G{$bot->id_guion_gestion}_C{$camposSistema['tipificacion_bot']},
                G{$bot->id_guion_gestion}_C{$camposSistema['ultima_seccion']},
                G{$bot->id_guion_gestion}_C{$camposSistema['ultimo_mensaje']}";

                $camposBx = "
                B{$bot->id_pregun_dato_capturado}_FechaInsercion,
                B{$bot->id_pregun_dato_capturado}_CodigoMiembro,
                B{$bot->id_pregun_dato_capturado}_PoblacionOrigen,
                B{$bot->id_pregun_dato_capturado}_Origen_b,
                B{$bot->id_pregun_dato_capturado}_IdChatEntrante,
                B{$bot->id_pregun_dato_capturado}_Sentido___b,
                B{$bot->id_pregun_dato_capturado}_Canal_____b,
                B{$bot->id_pregun_dato_capturado}_LinkContenido,
                B{$bot->id_pregun_dato_capturado}_DatoContacto,
                B{$bot->id_pregun_dato_capturado}_Paso,
                B{$bot->id_pregun_dato_capturado}_Clasificacion,
                SEC_TO_TIME(B{$bot->id_pregun_dato_capturado}_Duracion___b) AS duracion,
                -24 AS tipificacion_defecto,
                B{$bot->id_pregun_dato_capturado}_Observacion,
                B{$bot->id_pregun_dato_capturado}_DatoContacto AS dato_principal,
                B{$bot->id_pregun_dato_capturado}_Tipificacion,
                B{$bot->id_pregun_dato_capturado}_UltimaSeccion,
                B{$bot->id_pregun_dato_capturado}_UltimoMensaje";

                // Ahora traemos los campos dinamicos del bot
                $sql = "SELECT a.* from dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos a
                    INNER JOIN dyalogo_canales_electronicos.dy_base_autorespuestas b ON a.id_base_autorespuestas = b.id
                where b.id_bot = {$bot->id} and id_pregun IS NOT NULL";

                $resCampos = $mysqli->query($sql);

                if($resCampos && $resCampos->num_rows > 0){

                    $camposValidos = [];

                    while ($campo = $resCampos->fetch_object()) {
                        
                        // Valido si el campo que voy a insertar existe en BX
                        $sql = "SHOW COLUMNS FROM DYALOGOCRM_WEB.B{$bot->id_pregun_dato_capturado} WHERE Field = 'B{$bot->id_pregun_dato_capturado}_C{$campo->id_pregun}'";
                        $resValidarCampo = $mysqli->query($sql);
                        
                        if($resValidarCampo && $resValidarCampo->num_rows > 0){

                            // Validamos si ya esta se ha llamado con anterioridad
                            if(!in_array($campo->id_pregun_gestion, $camposValidos)){
                                
                                $camposGx .= ", G{$bot->id_guion_gestion}_C{$campo->id_pregun_gestion}";
                                $camposBx .= ", B{$bot->id_pregun_dato_capturado}_C{$campo->id_pregun}";

                                $camposValidos[] = $campo->id_pregun_gestion;
                            }else{
                                echo "-- repetido campo " . $campo->id_pregun_gestion . " Conectado con" . $campo->id_pregun . "<br>";
                            }
                        }

                    }
                }

                $selectBx = "SELECT {$camposBx} FROM DYALOGOCRM_WEB.B{$bot->id_pregun_dato_capturado}";

                $insert = "INSERT INTO DYALOGOCRM_WEB.G{$bot->id_guion_gestion} ({$camposGx}) {$selectBx}";
                
                echo $insert . '<br>';
            }

        }

    }

}

?>