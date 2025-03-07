<?php
    function generar_tablas_bd($idFormulario_Crear, $generar_tabla, $generar_formulario,  $generar_busqueda, $generar_web_form,$tipoGuion=0,$resJson=false){
        
        global $mysqli;
        global $BaseDatos;
        global $BaseDatos_systema;
        global $BaseDatos_telefonia;
        global $dyalogo_canales_electronicos;
        global $BaseDatos_general;

        $Lsql = "SELECT G{$idFormulario_Crear}_ConsInte__b FROM {$BaseDatos}.G{$idFormulario_Crear} LIMIT 1";
        $generarA = "G".$idFormulario_Crear;
        $res_Lsql = $mysqli->query($Lsql);

        $journey = new GenerarTablaJourney($generarA);

        $fallas=array();
        if($res_Lsql){

            if($generar_tabla != 0){
                
                $journey->validateTable() === true ? $journey->createFields() : $journey->createTable(); // true == si existe || false == no existe.

                //echo "aqui";
                /* La tabla ya ha sido generada por lo menos una vez, toca editarla  */
                /* preguntamos si algun campo fue creado o editado */
                $pregun_Lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Tipo______b, PREGUN_FueGener_b, PREGUN_Longitud__b, PREGUN_Texto_____b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$idFormulario_Crear." AND PREGUN_FueGener_b != 0 AND (PREGUN_Tipo______b != 9  AND PREGUN_Tipo______b != 12  AND PREGUN_Tipo______b != 16 AND PREGUN_Tipo______b != 17)";
                $res_PregunLsql = $mysqli->query($pregun_Lsql);
                $valido = 0;


                /*VALIDAMOS QUE LOS CMAPOS EXISTAN */
                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_ConsInte__b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_ConsInte__b bigint(20) NOT NULL AUTO_INCREMENT";
                    $mysqli->query($edit_Lsql);
                }

                  $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_FechaInsercion'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_FechaInsercion datetime DEFAULT NOW()";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_Usuario'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_Usuario bigint(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_CodigoMiembro'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_CodigoMiembro bigint(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_CodigoMiembro'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_CodigoMiembro bigint(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                 $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_PoblacionOrigen'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_PoblacionOrigen bigint(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                 $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_IdLlamada'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_IdLlamada varchar(50) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_Sentido___b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_Sentido___b varchar(10) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_Canal_____b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_Canal_____b varchar(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                 $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_CantidadIntentos'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_CantidadIntentos bigint(20) DEFAULT 0";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_UltiGest__b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_UltiGest__b bigint(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_FecUltGes_b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_FecUltGes_b datetime DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_GesMasImp_b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_GesMasImp_b bigint(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_FeGeMaIm__b'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_FeGeMaIm__b datetime DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                 $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_EstadoDiligenciamiento'";
                $result = $mysqli->query($Lsql);
                if($result->num_rows === 0){
                    $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_EstadoDiligenciamiento bigint(20) DEFAULT NULL";
                    $mysqli->query($edit_Lsql);
                }

                

               


                if($tipoGuion == 1){

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_FechaInsercionBD_b '";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_FechaInsercionBD_b datetime DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_LinkContenido'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_LinkContenido varchar(500) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_Origen_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_Origen_b varchar(50) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_DetalleCanal'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_DetalleCanal varchar(255) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                     $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_DatoContacto'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_DatoContacto varchar(255) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_Paso'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_Paso bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_Clasificacion'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_Clasificacion smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                }

               



                if($tipoGuion == 2){
                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_TipoReintentoUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_TipoReintentoUG_b  smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_TipoReintentoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_TipoReintentoGMI_b smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_FecHorAgeUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_FecHorAgeUG_b datetime DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }


                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_FecHorAgeGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_FecHorAgeGMI_b datetime DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                   $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_ClasificacionUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_ClasificacionUG_b  smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_ClasificacionGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_ClasificacionGMI_b smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                     $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_EstadoUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_EstadoUG_b  bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                     $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_EstadoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_EstadoGMI_b bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                    

                     $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_UsuarioUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_UsuarioUG_b  bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                     $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_UsuarioGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_UsuarioGMI_b bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_CanalGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_CanalGMI_b varchar(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }


                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_SentidoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_SentidoGMI_b varchar(10) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_CantidadIntentosGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_CantidadIntentosGMI_b  bigint(20) DEFAULT 0";
                        $mysqli->query($edit_Lsql);
                    }


                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_ComentarioUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_ComentarioUG_b  longtext DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }


                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_ComentarioGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_ComentarioGMI_b longtext DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                     $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_LinkContenidoUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_LinkContenidoUG_b varchar(500) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_LinkContenidoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_LinkContenidoGMI_b varchar(500) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_DetalleCanalUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_DetalleCanalUG_b varchar(255) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_DetalleCanalGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_DetalleCanalGMI_b varchar(255) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_DatoContactoUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_DatoContactoUG_b varchar(255) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_DatoContactoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_DatoContactoGMI_b varchar(255) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_PasoUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_PasoUG_b bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }
                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_PasoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_PasoGMI_b bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                      $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_FechaUltimoCargue'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_FechaUltimoCargue datetime DEFAULT now()";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_OrigenUltimoCargue'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_OrigenUltimoCargue varchar(255) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }



                }

                if ($tipoGuion == 3) {
                    # Acá agregamos las columnas nuevas al momento de guardar el Guion tipo - complemento
                    $Lsql = "SHOW COLUMNS FROM " . $BaseDatos . "." . $generarA . " WHERE Field = '" . $generarA . "_TipoReintentoUG_b'";
                    $result = $mysqli->query($Lsql);
                    if ($result->num_rows === 0) {
                        $edit_Lsql = "ALTER TABLE " . $BaseDatos . "." . $generarA . " ADD " . $generarA . "_TipoReintentoUG_b  smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_TipoReintentoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_TipoReintentoGMI_b smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_ClasificacionUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_ClasificacionUG_b  smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_ClasificacionGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_ClasificacionGMI_b smallint(5) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_EstadoUG_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_EstadoUG_b  bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_EstadoGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_EstadoGMI_b bigint(20) DEFAULT NULL";
                        $mysqli->query($edit_Lsql);
                    }

                    $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_CantidadIntentosGMI_b'";
                    $result = $mysqli->query($Lsql);
                    if($result->num_rows === 0){
                        $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_CantidadIntentosGMI_b  bigint(20) DEFAULT 0";
                        $mysqli->query($edit_Lsql);
                    }
                }


                                  
                                  

                      
          


                /* Luego si se proecede con el resto de las cosas a generar */
                while ($res = $res_PregunLsql->fetch_object()) {
                    if($res->PREGUN_FueGener_b == 1){
                        /* Campo Nuevo */
            
                        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_C".$res->PREGUN_ConsInte__b."'";
                        $result = $mysqli->query($Lsql);
                        //echo $res->PREGUN_Tipo______b;
                        if($result->num_rows === 0){
                            /* recorremos todos los campos del guion y lo creamos */
                            if($res->PREGUN_Tipo______b == '5'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '10'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '3'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '6'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '13'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '14'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '11'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '4'){
                                /* la pregunta es Decimal */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b."  double DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '1'){
                                /* es de tipo Varchar */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '15'){
                                /* es de tipo Varchar */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '2'){
                                /* es de tipo Memo */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." longtext CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '8'){
                                /* es de tipo CheckBox */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." smallint(5) DEFAULT NULL";
                            }

                            if($mysqli->query($edit_Lsql) === true){

                                $valido = 1;
                                $str_Lsql = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_FueGener_b = 0 WHERE PREGUN_ConsInte__b = ".$res->PREGUN_ConsInte__b;


                                if($mysqli->query($str_Lsql) == TRUE){

                                }else{
                                    echo "Error Borrando el campo".$mysqli->error;
                                }
                            }else{
                                echo "Error Agregando Columna => ".$mysqli->error;
                            }
                        }

                    }

                    if($res->PREGUN_FueGener_b == 2){

                        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_C".$res->PREGUN_ConsInte__b."'";
                        $result = $mysqli->query($Lsql);
                        if($result->num_rows === 0){
                            /* recorremos todos los campos del guion y lo creamos */
                            if($res->PREGUN_Tipo______b == '5'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '10'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '3'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '6'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '13'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '14'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '11'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '4'){
                                /* la pregunta es Decimal */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b."  double DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '1'){
                                /* es de tipo Varchar */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '15'){
                                /* es de tipo Varchar */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '2'){
                                /* es de tipo Memo */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." longtext CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '8'){
                                /* es de tipo CheckBox */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." ADD ".$generarA."_C".$res->PREGUN_ConsInte__b." smallint(5) DEFAULT NULL";
                            }

                            if($mysqli->query($edit_Lsql) === true){
                                $valido = 1;
                                $str_Lsql = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_FueGener_b = 0 WHERE PREGUN_ConsInte__b = ".$res->PREGUN_ConsInte__b;


                                if($mysqli->query($str_Lsql) == TRUE){

                                }else{
                                    echo "Error Borrando el campo".$mysqli->error;
                                }
                            }else{
                                echo "Error Editando Columna => ".$mysqli->error;
                            }

                        }else{
                            /* campo editado ya estaba creado */
                            /* recorremos todos los campos del guion */
                            if($res->PREGUN_Tipo______b == '5'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '10'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '3'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '6'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '13'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '14'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }


                            if($res->PREGUN_Tipo______b == '11'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '4'){
                                /* la pregunta es Decimal */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b."  double DEFAULT NULL";
                            } 

                            if($res->PREGUN_Tipo______b == '1'){
                                /* es de tipo Varchar */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '15'){
                                /* es de tipo Varchar */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '2'){
                                /* es de tipo Memo */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." longtext CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                            }

                            if($res->PREGUN_Tipo______b == '8'){
                                /* es de tipo CheckBox */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." smallint(5) DEFAULT NULL";
                            }

                            if($mysqli->query($edit_Lsql) === true){    
                                $valido = 1;
                                $str_Lsql = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_FueGener_b = 0 WHERE PREGUN_ConsInte__b = ".$res->PREGUN_ConsInte__b;


                                if($mysqli->query($str_Lsql) == TRUE){

                                }else{
                                    echo "Error Modificando el campo".$mysqli->error;
                                }

                            }else{
                                if($mysqli->errno == 1265){
                                    array_push($fallas, array("tabla"=>$generarA,"campo"=>$res->PREGUN_ConsInte__b,"nombre"=>$res->PREGUN_Texto_____b,"longitud"=>$res->PREGUN_Longitud__b));
                                }else{
                                    $edit_Lsql = "UPDATE ".$BaseDatos.".".$generarA." SET ".$generarA."_C".$res->PREGUN_ConsInte__b." = NULL; ";
                                    if($mysqli->query($edit_Lsql) === true){    
    
                                        if($res->PREGUN_Tipo______b == '5'){
                                            /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                                        }
    
                                        if($res->PREGUN_Tipo______b == '10'){
                                            /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                                        }
    
                                        if($res->PREGUN_Tipo______b == '3'){
                                            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                                        }
    
                                        if($res->PREGUN_Tipo______b == '6'){
                                            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                                        } 
    
                                        if($res->PREGUN_Tipo______b == '13'){
                                            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                                        } 
    
                                        if($res->PREGUN_Tipo______b == '14'){
                                            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                                        } 
    
                                        if($res->PREGUN_Tipo______b == '11'){
                                            /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                                        }
    
                                        if($res->PREGUN_Tipo______b == '4'){
                                            /* la pregunta es Decimal */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b."  double DEFAULT NULL";
                                        } 
    
                                        if($res->PREGUN_Tipo______b == '1'){
                                            /* es de tipo Varchar */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                                        }
    
                                        if($res->PREGUN_Tipo______b == '15'){
                                            /* es de tipo Varchar */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." varchar(253) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                                        }
    
                                        if($res->PREGUN_Tipo______b == '2'){
                                            /* es de tipo Memo */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." longtext CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                                        }
    
                                        if($res->PREGUN_Tipo______b == '8'){
                                            /* es de tipo CheckBox */
                                            $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." CHANGE ".$generarA."_C".$res->PREGUN_ConsInte__b." ".$generarA."_C".$res->PREGUN_ConsInte__b." smallint(5) DEFAULT NULL";
                                        }
    
                                       // echo $edit_Lsql;
                                        //echo "Luego";
                                        if($mysqli->query($edit_Lsql) === true){   
    
                                        }else{
                                            echo "Error Modificando Columna => ".$mysqli->error;
                                        }
                                    }else{
                                        echo "Error Modificando Columna  paso 1 => ".$mysqli->error;
                                    }
                                }
                            }
                        }
                    }

                    if($res->PREGUN_FueGener_b == 3){
                        $Lsql = "SHOW COLUMNS FROM ".$BaseDatos.".".$generarA." WHERE Field = '".$generarA."_C".$res->PREGUN_ConsInte__b."'";
                        $result = $mysqli->query($Lsql);
                        if($result->num_rows === 0){

                        }else{
                            /* campo eliminado */
                            /* recorremos todos los campos del guion */
                            if($res->PREGUN_Tipo______b == '5'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            }

                            if($res->PREGUN_Tipo______b == '10'){
                                /* es de tipo Fecha u Hora y toca ponerle dateTime */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            }

                            if($res->PREGUN_Tipo______b == '3'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            } 


                            if($res->PREGUN_Tipo______b == '6'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            } 


                            if($res->PREGUN_Tipo______b == '13'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            } 

                            if($res->PREGUN_Tipo______b == '14'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            } 

                            if($res->PREGUN_Tipo______b == '11'){
                                /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            } 

                            if($res->PREGUN_Tipo______b == '4'){
                                /* la pregunta es Decimal */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            } 

                            if($res->PREGUN_Tipo______b == '1' || $res->PREGUN_Tipo______b == '15'){
                                /* es de tipo Varchar */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            }

                            if($res->PREGUN_Tipo______b == '2'){
                                /* es de tipo Memo */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            }

                            if($res->PREGUN_Tipo______b == '8'){
                                /* es de tipo CheckBox */
                                $edit_Lsql = "ALTER TABLE ".$BaseDatos.".".$generarA." DROP COLUMN ".$generarA."_C".$res->PREGUN_ConsInte__b." ";
                            }


                            if($mysqli->query($edit_Lsql) === true){
                                $valido = 1;
                            }else{
                                echo "Error Eliminando Columna => ".$mysqli->error;
                            }


                            //Borramos campo_
                            $Lsql_Del_Campo = "DELETE FROM ".$BaseDatos_systema.".CAMPO_ WHERE CAMPO__ConsInte__PREGUN_b = ".$res->PREGUN_ConsInte__b;

                            if($mysqli->query($Lsql_Del_Campo) == TRUE){

                            }else{
                                echo "Error Borrando el Lsql_Del_Campo".$mysqli->error;
                            }

                            /* Borramos camcom */
                            
                            $Lsql_Del_Camco = "DELETE FROM ".$BaseDatos_systema.".CAMCON WHERE CAMCON_ConsInte__PREGUN_b = ".$res->PREGUN_ConsInte__b;

                            if($mysqli->query($Lsql_Del_Camco) == TRUE){

                            }else{
                                echo "Error Borrando el Lsql_Del_Camco".$mysqli->error;
                            }

                            /* Borramos caminc */
                            
                            $Lsql_Del_camin = "DELETE FROM ".$BaseDatos_systema.".CAMINC WHERE CAMINC_ConsInte__CAMPO_Pob_b  = ".$res->PREGUN_ConsInte__b;

                            if($mysqli->query($Lsql_Del_camin) == TRUE){

                            }else{
                                echo "Error Borrando el Lsql_Del_camin".$mysqli->error;
                            }

                            /* Borramos Condiciones */
                            
                            /*$Lsql_Del_condi = "DELETE FROM ".$BaseDatos_systema.".ESTCON_CONDICIONES WHERE campo = ".$res->PREGUN_ConsInte__b;

                            if($mysqli->query($Lsql_Del_condi) == TRUE){

                            }else{
                                echo "Error Borrando el Lsql_Del_condi".$mysqli->error;
                            }*/


                            
                            /* Borramos Condiciones */
                            
                            $Lsql_Del_camor = "DELETE FROM ".$BaseDatos_systema.".CAMORD WHERE CAMORD_POBLCAMP__B LIKE '%".$res->PREGUN_ConsInte__b."'";

                            if($mysqli->query($Lsql_Del_camor) == TRUE){

                            }else{
                                echo "Error Borrando el Lsql_Del_camor".$mysqli->error;
                            }

                            /* Borramos Pregun */
                            
                            $str_Lsql = "DELETE FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__b = ".$res->PREGUN_ConsInte__b;


                            if($mysqli->query($str_Lsql) == TRUE){

                            }else{
                                echo "Error Borrando el pregun  ".$mysqli->error;
                            }
                        }
                    }

                    
                }
            }
            generar_formularios_busquedas($idFormulario_Crear, $generar_formulario, $generar_busqueda, $generar_web_form);
            
        }else{
            if($generar_tabla != 0){
                /* la tabla No fue generada */

                
                //aqui entran los guiones tipo 1 que son script
                if($tipoGuion == 1){

                     $create_Lsql = "CREATE TABLE ".$BaseDatos.".".$generarA." (
                                ".$generarA."_ConsInte__b bigint(20) NOT NULL AUTO_INCREMENT,
                                ".$generarA."_FechaInsercion datetime DEFAULT NOW(),
                                ".$generarA."_FechaInsercionBD_b datetime DEFAULT NULL,
                                ".$generarA."_Usuario bigint(20) DEFAULT NULL,
                                ".$generarA."_CodigoMiembro bigint(20) DEFAULT NULL,
                                ".$generarA."_PoblacionOrigen bigint(20) DEFAULT NULL,                                
                                ".$generarA."_Origen_b varchar(50) DEFAULT NULL,                                
                                ".$generarA."_IdLlamada varchar(50) DEFAULT NULL,                               
                                ".$generarA."_Sentido___b varchar(10) DEFAULT NULL,
                                ".$generarA."_Canal_____b varchar(20) DEFAULT NULL,
                                ".$generarA."_CantidadIntentos bigint(20) DEFAULT 0,

                                ".$generarA."_LinkContenido varchar(500) DEFAULT NULL,
                                ".$generarA."_DetalleCanal varchar(255) DEFAULT NULL,
                                ".$generarA."_DatoContacto varchar(255) DEFAULT NULL,
                                ".$generarA."_Paso bigint(20) DEFAULT NULL,
                                ".$generarA."_Clasificacion smallint(5) DEFAULT NULL,
                                ".$generarA."_Duracion___b time DEFAULT NULL,

                                ".$generarA."_EstadoDiligenciamiento bigint(20) DEFAULT NULL,
                                ".$generarA."_UltiGest__b bigint(20) DEFAULT NULL , 
                                ".$generarA."_FecUltGes_b datetime DEFAULT NULL,
                                ".$generarA."_GesMasImp_b bigint(20) DEFAULT NULL,
                                ".$generarA."_FeGeMaIm__b datetime DEFAULT NULL";

                //aqui entran los guiones tipo 2 base de datos        
                }else  if($tipoGuion == 2){

                    $create_Lsql = "CREATE TABLE ".$BaseDatos.".".$generarA." (
                                    ".$generarA."_ConsInte__b bigint(20) NOT NULL AUTO_INCREMENT,
                                    ".$generarA."_FechaInsercion datetime DEFAULT NOW(),
                                    ".$generarA."_FechaUltimoCargue datetime DEFAULT NOW(),
                                    ".$generarA."_OrigenUltimoCargue varchar(255) DEFAULT NULL,
                                    ".$generarA."_Usuario bigint(20) DEFAULT NULL,                               
                                    ".$generarA."_IdLlamada varchar(50) DEFAULT NULL,

                                    ".$generarA."_UltiGest__b bigint(20) DEFAULT NULL , 
                                    ".$generarA."_GesMasImp_b bigint(20) DEFAULT NULL,
                                    ".$generarA."_FecUltGes_b datetime DEFAULT NULL,
                                    ".$generarA."_FeGeMaIm__b datetime DEFAULT NULL,
                                    ".$generarA."_TipoReintentoUG_b smallint(5) DEFAULT NULL, 
                                    ".$generarA."_TipoReintentoGMI_b smallint(5) DEFAULT NULL,
                                    ".$generarA."_FecHorAgeUG_b datetime DEFAULT NULL,
                                    ".$generarA."_FecHorAgeGMI_b datetime DEFAULT NULL,
                                    ".$generarA."_ClasificacionUG_b smallint(5) DEFAULT NULL,
                                    ".$generarA."_ClasificacionGMI_b smallint(5) DEFAULT NULL,
                                    ".$generarA."_EstadoUG_b  bigint(20) DEFAULT NULL, 
                                    ".$generarA."_EstadoGMI_b bigint(20) DEFAULT NULL,
                                    ".$generarA."_UsuarioUG_b  bigint(20) DEFAULT NULL,
                                    ".$generarA."_UsuarioGMI_b bigint(20) DEFAULT NULL,
                                    ".$generarA."_Canal_____b varchar(20) DEFAULT NULL,
                                    ".$generarA."_CanalGMI_b varchar(20) DEFAULT NULL,
                                    ".$generarA."_Sentido___b varchar(10) DEFAULT NULL,
                                    ".$generarA."_SentidoGMI_b varchar(10) DEFAULT NULL,
                                    ".$generarA."_CantidadIntentos bigint(20) DEFAULT 0,
                                    ".$generarA."_CantidadIntentosGMI_b bigint(20) DEFAULT 0,
                                    ".$generarA."_ComentarioUG_b longtext DEFAULT NULL,
                                    ".$generarA."_ComentarioGMI_b longtext DEFAULT NULL,
                                    ".$generarA."_LinkContenidoUG_b varchar(500) DEFAULT NULL,
                                    ".$generarA."_LinkContenidoGMI_b varchar(500) DEFAULT NULL,
                                    ".$generarA."_DetalleCanalUG_b varchar(255) DEFAULT NULL,
                                    ".$generarA."_DetalleCanalGMI_b varchar(255) DEFAULT NULL,
                                    ".$generarA."_DatoContactoUG_b varchar(255) DEFAULT NULL,
                                    ".$generarA."_DatoContactoGMI_b varchar(255) DEFAULT NULL,
                                    ".$generarA."_PasoUG_b bigint(20) DEFAULT NULL,
                                    ".$generarA."_PasoGMI_b bigint(20) DEFAULT NULL,

                                    ".$generarA."_CodigoMiembro bigint(20) DEFAULT NULL,
                                    ".$generarA."_PoblacionOrigen bigint(20) DEFAULT NULL, 
                                    ".$generarA."_EstadoDiligenciamiento bigint(20) DEFAULT NULL";
                                    
                                    // genero o creo la tabla para el journey
                                    // $journey->validateTable() === true ? $journey->createFields() : $journey->createTable(); // true == si existe || false == no existe.
       
                # Aqui entran los guiones de tipo - otros        
                }else{
                      $create_Lsql = "CREATE TABLE ".$BaseDatos.".".$generarA." (
                                ".$generarA."_ConsInte__b bigint(20) NOT NULL AUTO_INCREMENT,
                                ".$generarA."_FechaInsercion datetime DEFAULT NOW(),
                                ".$generarA."_Usuario bigint(20) DEFAULT NULL,
                                ".$generarA."_CodigoMiembro bigint(20) DEFAULT NULL,
                                ".$generarA."_PoblacionOrigen bigint(20) DEFAULT NULL,                                
                                ".$generarA."_IdLlamada varchar(50) DEFAULT NULL,                               
                                ".$generarA."_Sentido___b varchar(10) DEFAULT NULL,
                                ".$generarA."_Canal_____b varchar(20) DEFAULT NULL,
                                ".$generarA."_CantidadIntentos bigint(20) DEFAULT 0,
                                ".$generarA."_TipoReintentoUG_b smallint(5) DEFAULT NULL,
                                ".$generarA."_TipoReintentoGMI_b smallint(5) DEFAULT NULL,
                                ".$generarA."_ClasificacionUG_b smallint(5) DEFAULT NULL,
                                ".$generarA."_ClasificacionGMI_b smallint(5) DEFAULT NULL,
                                ".$generarA."_EstadoUG_b  bigint(20) DEFAULT NULL,
                                ".$generarA."_EstadoGMI_b bigint(20) DEFAULT NULL,
                                ".$generarA."_CantidadIntentosGMI_b bigint(20) DEFAULT 0,
                                
                                ".$generarA."_LinkContenido varchar(500) DEFAULT NULL,
                                ".$generarA."_DetalleCanal varchar(255) DEFAULT NULL,
                                ".$generarA."_DatoContacto varchar(255) DEFAULT NULL,
                                ".$generarA."_Paso bigint(20) DEFAULT NULL,
                                ".$generarA."_Clasificacion smallint(5) DEFAULT NULL,

                                ".$generarA."_EstadoDiligenciamiento bigint(20) DEFAULT NULL,
                                ".$generarA."_UltiGest__b bigint(20) DEFAULT NULL , 
                                ".$generarA."_FecUltGes_b datetime DEFAULT NULL,
                                ".$generarA."_GesMasImp_b bigint(20) DEFAULT NULL,
                                ".$generarA."_FeGeMaIm__b datetime DEFAULT NULL";
                }

               

                
                               

                $pregun_Lsql = "SELECT PREGUN_ConsInte__b, PREGUN_Tipo______b, PREGUN_Longitud__b FROM ".$BaseDatos_systema.".PREGUN WHERE PREGUN_ConsInte__GUION__b = ".$idFormulario_Crear." AND (PREGUN_Tipo______b != 9  AND PREGUN_Tipo______b != 12  AND PREGUN_Tipo______b != 16 AND PREGUN_Tipo______b != 17)";

                $res_PregunLsql = $mysqli->query($pregun_Lsql);
                
                while ($res = $res_PregunLsql->fetch_object()) {
                    /* recorremos todos los campos del guion */
                    if($res->PREGUN_Tipo______b == '5'){
                        /* es de tipo Fecha u Hora y toca ponerle dateTime */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                    }

                    if($res->PREGUN_Tipo______b == '10'){
                        /* es de tipo Fecha u Hora y toca ponerle dateTime */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." datetime DEFAULT NULL";
                    }

                    if($res->PREGUN_Tipo______b == '3'){
                        /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                    } 

                    if($res->PREGUN_Tipo______b == '6'){
                        /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                    } 

                    if($res->PREGUN_Tipo______b == '13'){
                        /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                    } 

                    if($res->PREGUN_Tipo______b == '14'){
                        /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                    } 

                    if($res->PREGUN_Tipo______b == '11'){
                        /* la pregunta es Entero, Lista o Guion que guardan los Ids */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." bigint(20) DEFAULT NULL";
                    } 

                    if($res->PREGUN_Tipo______b == '4'){
                        /* la pregunta es Decimal */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b."  double DEFAULT NULL";
                    } 

                    if($res->PREGUN_Tipo______b == '1'){
                        /* es de tipo Varchar */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." varchar({$res->PREGUN_Longitud__b}) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                    }

                    if($res->PREGUN_Tipo______b == '15'){
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." varchar(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                    }

                    if($res->PREGUN_Tipo______b == '2'){
                        /* es de tipo Memo */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." longtext CHARSET utf8 COLLATE utf8_general_ci DEFAULT NULL";
                    }

                    if($res->PREGUN_Tipo______b == '8'){
                        /* es de tipo CheckBox */
                        $create_Lsql .= ",".$generarA."_C".$res->PREGUN_ConsInte__b." smallint(5) DEFAULT NULL";
                    }
                }

                $create_Lsql .= ",PRIMARY KEY (".$generarA."_ConsInte__b)) ENGINE=InnoDB AUTO_INCREMENT=0;";


                if($mysqli->query($create_Lsql) === true){
                    $Lsql_Editar_Guion = "UPDATE ".$BaseDatos_systema.".PREGUN SET PREGUN_FueGener_b = 0 WHERE PREGUN_ConsInte__GUION__b = ".$idFormulario_Crear;
                    $mysqli->query($Lsql_Editar_Guion);

                    generar_formularios_busquedas($idFormulario_Crear , $generar_formulario, $generar_busqueda, $generar_web_form);
                    return true;
                    //echo '1';
                }else{
                    echo $mysqli->error;
                }
            }else{
                generar_formularios_busquedas($idFormulario_Crear , $generar_formulario, $generar_busqueda, $generar_web_form);
                return true;
            }
        }

        if($resJson){
            return $fallas;
        }

    }

class GenerarTablaJourney
{
    private $strGuionIdBD;
    public $errors;

    public function __construct(string $strGuionIdBD)
    {
        global $BaseDatos;
        global $mysqli;

        $this->strGuionIdBD = $strGuionIdBD;
        $this->BaseDatos = $BaseDatos;
        $this->mysqli = $mysqli;
        $this->errors = [];
    }

    private function createTableJourney(): void
    {
        # armo la sentencia sql para crear la tabla

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->BaseDatos}`.`{$this->strGuionIdBD}_J` (
            `{$this->strGuionIdBD}_J_ConsInte_b` INT(255) NOT NULL AUTO_INCREMENT,
            `{$this->strGuionIdBD}_J_ConsInte_Miembro_Pob_b` INT(255) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Fecha_Hora_b` DATETIME DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Duracion___b` TIME DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Agente` INT(255) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Sentido___b` VARCHAR(10) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Canal_____b` VARCHAR(20)  DEFAULT NULL,
            `{$this->strGuionIdBD}_J_DatoContacto` VARCHAR(255) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_IdPaso` INT(10) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Tipificacion_b` VARCHAR(100) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Clasificacion_b` SMALLINT(5) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_TipoReintento_b` SMALLINT(5) DEFAULT NULL,
            `{$this->strGuionIdBD}_J_Comentario_b` LONGTEXT ,
            `{$this->strGuionIdBD}_J_LinkContenido_b` VARCHAR(500) DEFAULT NULL,
            PRIMARY KEY (`{$this->strGuionIdBD}_J_ConsInte_b`),
            UNIQUE KEY `{$this->strGuionIdBD}_J_ConsInte_b_UNIQUE` (`{$this->strGuionIdBD}_J_ConsInte_b`),
            INDEX (`{$this->strGuionIdBD}_J_ConsInte_b`),
            INDEX (`{$this->strGuionIdBD}_J_ConsInte_Miembro_Pob_b`)
        ) ENGINE=INNODB DEFAULT CHARSET=UTF8 ";

        mysqli_query($this->mysqli, $sql);

        $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
        $this->mistakes(
            $sql,
            $estado,
            $this->mysqli->error
        );
    }

    protected function addPrimaryKey(string $strNombreColumna): void
    {
        # code...
        $sql = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD PRIMARY KEY ({$this->strGuionIdBD}_J_{$strNombreColumna})";

        $this->mysqli->query($sql);

        $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';

        $this->mistakes(
            $sql,
            $estado,
            $this->mysqli->error
        );
    }

    // agregamos el index
    protected function addIndex(string $strNombreColumna): void
    {
        # code...
        $sql = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD INDEX ({$this->strGuionIdBD}_J_{$strNombreColumna})";

        // echo "<br> addIndex->sql => $sql <br>";
        $this->mysqli->query($sql);

        $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';

        $this->mistakes(
            $sql,
            $estado,
            $this->mysqli->error
        );
    }

    // agregamos unique id a la columna
    protected function addUnique(string $strNombreColumna): void
    {
        # code...
        $sql = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD UNIQUE INDEX {$this->strGuionIdBD}_J_{$strNombreColumna}_UNIQUE ({$this->strGuionIdBD}_J_{$strNombreColumna} ASC)";

        // echo "<br> addUnique->sql => $sql <br>";

        $this->mysqli->query($sql);

        $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';

        $this->mistakes(
            $sql,
            $estado,
            $this->mysqli->error
        );
    }

    // validamos el campo _J_ConsInte_b
    protected  function consInte(): void
    {
        # code...
        if ($this->validateField("ConsInte_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_ConsInte_b INT(255) NOT NULL FIRST";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );

            $this->addPrimaryKey('ConsInte_b');
            $this->addIndex('ConsInte_b');
            $this->addUnique('ConsInte_b');
        }
    }

    // validamos el campo _J_ConsInte__GUION__Pob_b
    protected  function consInteGUION_Pob(): void
    {
        # code...
        if ($this->validateField("ConsInte_Miembro_Pob_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_ConsInte_Miembro_Pob_b INT(255) NOT NULL";
            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';

            $this->mysqli->query($sqlAlter);
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );

        }
    }

    // validamos el campo _J_Fecha_Hora_b
    protected  function fechaHora_b(): void
    {
        # code...
        if ($this->validateField("Fecha_Hora_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Fecha_Hora_b DATETIME DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_Duracion___b
    protected  function duracion___b(): void
    {
        # code...
        if ($this->validateField("Duracion___b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Duracion___b TIME DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_Agente
    protected  function agente(): void
    {
        # code...
        if ($this->validateField("Agente")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Agente INT(255) DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_Sentido___b
    protected  function sentido___b(): void
    {
        # code...
        if ($this->validateField("Sentido___b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Sentido___b VARCHAR(10) COLLATE UTF8_BIN DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_Canal_____b
    protected  function canal_____b(): void
    {
        # code...
        if ($this->validateField("Canal_____b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Canal_____b VARCHAR(20) COLLATE UTF8_BIN DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_DatoContacto
    protected  function datoContacto(): void
    {
        # code...
        if ($this->validateField("DatoContacto")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_DatoContacto VARCHAR(255) COLLATE UTF8_BIN DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_NombrePaso
    protected  function nombrePaso(): void
    {
        # code...
        if ($this->validateField("NombrePaso")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_NombrePaso VARCHAR(255) COLLATE UTF8_BIN DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_Tipificacion_b
    protected  function tipificacion_b(): void
    {
        # code...
        if ($this->validateField("Tipificacion_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Tipificacion_b VARCHAR(100) COLLATE UTF8_BIN DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_Clasificacion_b
    protected  function clasificacion_b(): void
    {
        # code...
        if ($this->validateField("Clasificacion_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Clasificacion_b SMALLINT(5) DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_TipoReintento_b
    protected  function tipoReintento_b(): void
    {
        # code...
        if ($this->validateField("TipoReintento_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_TipoReintento_b SMALLINT(5) DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_Comentario_b
    protected  function comentario_b(): void
    {
        # code...
        if ($this->validateField("Comentario_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_Comentario_b LONGTEXT COLLATE UTF8_BIN";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // validamos el campo _J_LinkContenido_b
    protected  function linkContenido_b(): void
    {
        # code...
        if ($this->validateField("LinkContenido_b")) {
            $sqlAlter = "ALTER TABLE {$this->BaseDatos}.{$this->strGuionIdBD}_J ADD {$this->strGuionIdBD}_J_LinkContenido_b VARCHAR(500) COLLATE UTF8_BIN DEFAULT NULL";

            $this->mysqli->query($sqlAlter);

            $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
            $this->mistakes(
                $sqlAlter,
                $estado,
                $this->mysqli->error
            );
        }
    }

    // Validamos los campos de la tabla
    protected function validateField(string $field): bool
    {
        # code...
        $valido = true; // no existe el campo en la tabla G###_J
        $sql = "SHOW COLUMNS FROM {$this->BaseDatos}.{$this->strGuionIdBD}_J WHERE Field = '{$this->strGuionIdBD}_J_{$field}'";
        $result = $this->mysqli->query($sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $valido = false; // existe un campo, en la tabla G###_J

        }

        $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';

        $this->mistakes(
            $sql,
            $estado,
            $this->mysqli->error
        );

        return $valido;
    }

    // armo el array con lo errores
    public function mistakes(string $consulta, string $estado, string $mensaje): void
    {
        # code...

        $mensaje = $estado == 'ok' ?
            array("strSQL" =>  $consulta, "strMensaje" => "La consulta se ejecutó correctamente {$mensaje}", "strEstado" => $estado)
            :
            array("strSQL" =>  $consulta, "strMensaje" => "La consulta falló {$mensaje}", "strEstado" => "$estado");

        // array("strSQL" =>  $consulta, "strEstado" => $estado, "strMensaje" => $mensaje)

        array_push($this->errors, $mensaje);
    }


    // validamos la tabla si ya existe
    public function validateTable(): bool
    {
        # code...
        $valido = false; // no existe la tabla G###_J 

        $consulta = "SHOW TABLES FROM {$this->BaseDatos} WHERE Tables_in_DYALOGOCRM_WEB = '{$this->strGuionIdBD}_J'";

        $sql = mysqli_query($this->mysqli, $consulta);
        if ($sql && mysqli_num_rows($sql) > 0) {
            # code...
            $valido = true; // existe la tabla G###_J
        }

        $estado = $this->mysqli->errno === 0 ? 'ok' : 'fallo';
        $this->mistakes(
            $consulta,
            $estado,
            $this->mysqli->error
        );

        return $valido;
    }

    // Creamos todos lo campos de la tabla
    public function createFields(): void
    {
        # code...
        $this->consInte(); // validamos el campo _J_ConsInte_b
        $this->consInteGUION_Pob(); // validamos el campo _J_ConsInte__GUION__Pob_b
        $this->fechaHora_b(); // validamos el campo _J_Fecha_Hora_b
        $this->duracion___b(); // validamos el campo _J_Duracion___b
        $this->agente(); // validamos el campo _J_Agente
        $this->sentido___b(); // validamos el campo _J_Sentido___b
        $this->canal_____b(); // validamos el campo _J_Canal_____b
        $this->datoContacto(); // validamos el campo _J_DatoContacto
        $this->nombrePaso(); // validamos el campo _J_NombrePaso
        $this->tipificacion_b(); // validamos el campo _J_Tipificacion_b
        $this->clasificacion_b(); // validamos el campo _J_Clasificacion_b
        $this->tipoReintento_b(); // validamos el campo _J_TipoReintento_b
        $this->comentario_b(); // validamos el campo _J_Comentario_b
        $this->linkContenido_b(); // validamos el campo _J_LinkContenido_b

    }

    // Creamos la tabla journey
    public function createTable(): void
    {
        $this->createTableJourney();

    }
}

    include "generador.php";
