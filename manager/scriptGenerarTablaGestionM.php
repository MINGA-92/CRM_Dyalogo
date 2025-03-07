<?php

ini_set('display_errors', 'On');
ini_set('display_errors', 1);
include_once(__DIR__."/pages/conexion.php");
include_once(__DIR__."/cruds/DYALOGOCRM_SISTEMA/G26/G26_CRUD.php");

// Traemos todos los bots creados, la forma de verificar que son version vieja es que id_gestion sea igual al id registro
$sql = "SELECT * FROM dyalogo_canales_electronicos.dy_bot";
$res = $mysqli->query($sql);

if($res && $res->num_rows > 0){

    while($bot = $res->fetch_object()){

        if($bot->id == $bot->id_guion_gestion){

            echo "----- Recorriendo el bot " . $bot->nombre . '<br>';
            
            // Vamos a setear id_guion_gestion a null y le pasaremos el valor id_pregun_dato_capturado por el momento porque hay otra migracion
            $sql = "UPDATE dyalogo_canales_electronicos.dy_bot SET id_guion_gestion = NULL, id_pregun_dato_capturado = {$bot->id_guion_gestion} WHERE id = {$bot->id}";
            $mysqli->query($sql);

            // Generamos la bd
            generarTablaGestiones($bot->id, $bot->id_estpas);
            echo "- Generando tabla <br>";

            // Despues de generado me toca traer el nuevo id
            $sql = "SELECT id, id_guion_gestion FROM dyalogo_canales_electronicos.dy_bot WHERE id = {$bot->id}";
            $resBot = $mysqli->query($sql);

            if($resBot && $resBot->num_rows > 0){

                $bot2 = $resBot->fetch_object();

                // Si no hay id es que no genero nada
                if(!is_null($bot2->id_guion_gestion)){

                    $tablaGestionId = $bot2->id_guion_gestion;
                    echo "- Obteniendo el id de gestion " . $tablaGestionId . '<br>';

                    // Generamos los campos de esa tabla de gestiones
                    $sql = "SELECT a.* from dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos a
                        INNER JOIN dyalogo_canales_electronicos.dy_base_autorespuestas b ON a.id_base_autorespuestas = b.id
                    where b.id_bot = {$bot->id} and id_pregun IS NOT NULL";

                    $resCampos = $mysqli->query($sql);

                    if($resCampos && $resCampos->num_rows > 0){

                        while($campo = $resCampos->fetch_object()){

                            echo '- Recorriendo el campo -> ' . $campo->id . "- con id_pregun -> " . $campo->id_pregun . '<br>';

                            // obtenemos el nombre de la variable
                            if(is_null($campo->nombre_variable) || $campo->nombre_variable == '' || $campo->nombre_variable == 'null' || $campo->nombre_variable == 'NULL'){
                                $nombreVariable = obtenerNombreVariable($campo->id_pregun);
                            }else{
                                $nombreVariable = $campo->nombre_variable;
                            }

                            echo '- Obtenemos el nombre de la variable ' . $nombreVariable . '<br>';

                            $pregunGestionId = verificarCampoPregun($nombreVariable, $tablaGestionId);

                            echo '- Obtenemos el id del campo de gestion ' . $pregunGestionId . '<br>';

                            // Actualizamos el campo
                            $sql = "UPDATE dyalogo_canales_electronicos.dy_base_autorespuestas_contenidos SET nombre_variable = '{$nombreVariable}', id_pregun_gestion = {$pregunGestionId} WHERE id = {$campo->id}";
                            $mysqli->query($sql);
                        }

                        $guion1 = new GenerarGuion;
                        $guion1->generarTabla($tablaGestionId, 1);
                        echo '- Se genera la tabla gestion ' . $tablaGestionId . '<br>';

                    }

                }

            }

        }

    }

}

?>