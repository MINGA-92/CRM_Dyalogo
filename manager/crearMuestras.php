<?php 
	include ('pages/conexion.php');

    /*$Lsql = "select G871_ConsInte__b, G871_C12403, G871_C12404, G905_C12470, G905_C12471 FROM DYALOGOCRM_WEB.G871 LEFT JOIN DYALOGOCRM_WEB.G871_M403 ON G871_ConsInte__b = G871_M403_CoInMiPo__b
LEFT JOIN DYALOGOCRM_WEB.G905 ON G871_ConsInte__b = G905_CodigoMiembro
WHERE (G871_C12403 = '' or G871_C12403 IS NULL) and ( G905_C12470 <> '' or NOT (G905_C12470 IS NULL));";
    $res= $mysqli->query($Lsql);
    while ($key = $res->fetch_object()) {
        $XLsql = "UPDATE DYALOGOCRM_WEB.G871 SET G871_C12403 = '".$key->G905_C12470."' , G871_C12404 = '".$key->G905_C12471."' WHERE G871_ConsInte__b = ".$key->G871_ConsInte__b;

        if($mysqli->query($XLsql) !== true ){
            echo $mysqli->error;
        }
    }*/
	   /*$estPasId = 158;
	   $id_Muestras = 0;
    $id_Guion = 871;
    $Lsql = "INSERT INTO ".$BaseDatos_systema.".MUESTR (MUESTR_Nombre____b, MUESTR_ConsInte__GUION__b) VALUES ('".$id_Guion."_MUESTRA_".rand()."', '".$id_Guion."')";
    if($mysqli->query($Lsql) === true){*/
        $id_Guion = 1075;
        $id_Muestras = 616;
        /* toca asociarla al Paso */
      /*  $PasoLsql = "UPDATE ".$BaseDatos_systema.".ESTPAS SET ESTPAS_ConsInte__MUESTR_b = ".$id_Muestras." WHERE ESTPAS_ConsInte__b = ".$estPasId;
        $mysqli->query($PasoLsql);
        */
        
        //echo "Entra aqui tambien y este es el id de la muestra".$id_Muestras;

        $CreateMuestraLsql = "CREATE TABLE `".$BaseDatos."`.`G".$id_Guion."_M".$id_Muestras."` (
                                  `G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b` int(10) NOT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_Estado____b` int(10) DEFAULT '0',
                                  `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b` int(10) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_NumeInte__b` int(10) DEFAULT '0',
                                  `G".$id_Guion."_M".$id_Muestras."_UltiGest__b` int(10) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_FecUltGes_b` datetime DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_ConUltGes_b` int(10) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_TienGest__b` varchar(253) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_MailEnvi__b` smallint(5) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_GesMasImp_b` int(10) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_FeGeMaIm__b` datetime DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_CoGesMaIm_b` int(10) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_GruRegRel_b` int(10) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_Comentari_b` longtext,
                                  `G".$id_Guion."_M".$id_Muestras."_EfeUltGes_b` smallint(5) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_EfGeMaIm__b` smallint(5) DEFAULT NULL,
                                  `G".$id_Guion."_M".$id_Muestras."_Activo____b` smallint(5) DEFAULT '-1',
                                  `G".$id_Guion."_M".$id_Muestras."_FecHorAge_b` datetime DEFAULT NULL,
                                  PRIMARY KEY (`G".$id_Guion."_M".$id_Muestras."_CoInMiPo__b`),
                                  KEY `G".$id_Guion."_M".$id_Muestras."_Estado____b_Indice` (`G".$id_Guion."_M".$id_Muestras."_Estado____b`),
                                  KEY `G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b_Indice` (`G".$id_Guion."_M".$id_Muestras."_ConIntUsu_b`)
                                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        if($mysqli->query($CreateMuestraLsql) === true){
            //echo "Si creo la tabla";

        }else{
            echo $mysqli->error;
        }
    /*}else{
        echo "No guardo la muestra => ".$mysqli->error;
    }*/